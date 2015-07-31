<?php
/* Creates Custom Meta Box Fields for WordPress. It supports repeater fields and uses AJAX to handle data. */

/* Add Scripts */
add_action('admin_enqueue_scripts', 'wck_cfc_print_scripts' );
function wck_cfc_print_scripts($hook){
	if( isset( $_GET['post_type'] ) || isset( $_GET['post'] ) ){
		if( isset( $_GET['post_type'] ) )
			$post_type = $_GET['post_type'];
		else if( isset( $_GET['post'] ) )
			$post_type = get_post_type( $_GET['post'] );

		if( 'wck-meta-box' == $post_type ){
			wp_register_style('wck-cfc-css', plugins_url('/css/wck-cfc.css', __FILE__));
			wp_enqueue_style('wck-cfc-css');

			wp_register_script('wck-cfc-js', plugins_url('/js/wck-cfc.js', __FILE__), array( 'jquery' ), '1.0' );
			wp_enqueue_script('wck-cfc-js');
		}
	}
}

/* hook to create custom post types */
add_action( 'init', 'wck_cfc_create_custom_fields_cpt' );

function wck_cfc_create_custom_fields_cpt(){
	if( is_admin() && current_user_can( 'edit_theme_options' ) ){
		$labels = array(
			'name' => _x( 'WCK Custom Meta Boxes', 'post type general name'),
			'singular_name' => _x( 'Custom Meta Box', 'post type singular name'),
			'add_new' => _x( 'Add New', 'Custom Meta Box' ),
			'add_new_item' => __( "Add New Meta Box", "wck" ),
			'edit_item' => __( "Edit Meta Box", "wck" ) ,
			'new_item' => __( "New Meta Box", "wck" ),
			'all_items' => __( "Custom Fields Creator", "wck" ),
			'view_item' => __( "View Meta Box", "wck" ),
			'search_items' => __( "Search Meta Boxes", "wck" ),
			'not_found' =>  __( "No Meta Boxes found", "wck" ),
			'not_found_in_trash' => __( "No Meta Boxes found in Trash", "wck" ),
			'parent_item_colon' => '',
			'menu_name' => __( "Custom Meta Boxes", "wck" )
		);
		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => 'wck-page',
			'has_archive' => false,
			'hierarchical' => false,
			'capability_type' => 'post',
			'supports' => array( 'title' )
		);

		register_post_type( 'wck-meta-box', $args );
	}
}

/* add admin body class to cfc custom post type */
add_filter( 'admin_body_class', 'wck_cfc_admin_body_class' );
function wck_cfc_admin_body_class( $classes ){
	if( isset( $_GET['post_type'] ) || isset( $_GET['post'] ) ){
		if( isset( $_GET['post_type'] ) )
			$post_type = $_GET['post_type'];
		else if( isset( $_GET['post'] ) )
			$post_type = get_post_type( $_GET['post'] );

		if( 'wck-meta-box' == $post_type ){
			$classes .= ' wck_page_cfc-page ';
		}
	}
	return $classes;
}

/* Remove view action from post list view */
add_filter('post_row_actions','wck_cfc_remove_view_action');
function wck_cfc_remove_view_action($actions){
	global $post;
   if ($post->post_type =="wck-meta-box"){
	   unset( $actions['view'] );
   }
   return $actions;
}


/* create the meta box */
add_action( 'init', 'wck_cfc_create_box', 500 );
function wck_cfc_create_box(){
	global $wpdb;

	/* get post types */
	$public_cpt_arg = apply_filters( 'wck_cfc_public_cpt_arg', true );
	$args = array(
			'public'   => $public_cpt_arg
		);
	$output = 'objects'; // or objects
	$post_types = get_post_types($args,$output);
	$post_type_names = array();
	if( !empty( $post_types ) ){
		foreach ($post_types  as $post_type ) {
			if ( $post_type->name != 'attachment' && $post_type->name != 'wck-meta-box' && $post_type->name != 'wck-frontend-posting' && $post_type->name != 'wck-option-page' && $post_type->name != 'wck-option-field' && $post_type->name != 'wck-swift-template' )
				$post_type_names[] = $post_type->name;
		}
	}
	/* add CPTC registered with WCK that are not public */
	if( $public_cpt_arg ){
		$cpts = get_option('wck_cptc');
		if( !empty( $cpts ) ){
			foreach( $cpts as $cpt ){
				if( $cpt['public'] == 'false' )
					$post_type_names[] = $cpt['post-type'];
			}
		}
	}

	/* get page templates */
	$templates = wck_get_page_templates();

	/* set up the fields array */
	$cfc_box_args_fields = array(
		array( 'type' => 'text', 'title' => __( 'Meta name', 'wck' ), 'description' => __( 'The name of the meta field. It is the name by which you will query the data in the frontend. Must be unique, only lowercase letters, no spaces and no special characters.', 'wck' ), 'required' => true ),
		array( 'type' => 'select', 'title' => __( 'Post Type', 'wck' ), 'options' => $post_type_names, 'default-option' => true, 'description' => __( 'What post type the meta box should be attached to', 'wck' ), 'required' => true ),
		array( 'type' => 'select', 'title' => __( 'Repeater', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'false', 'description' => __( 'Whether the box supports just one entry or if it is a repeater field. By default it is a single field.', 'wck' ) ),
		array( 'type' => 'select', 'title' => __( 'Sortable', 'wck' ), 'options' => array( 'true', 'false' ), 'default' => 'false', 'description' => __( 'Whether the entries are sortable or not. This is valid for repeater fields.', 'wck' ) ),
		array( 'type' => 'text', 'title' => __( 'Post ID', 'wck' ), 'description' => __( 'ID of a post on which the meta box should appear. You can also input multiple IDs and separate them with ","', 'wck' ) )			
	);

	/* only in pro version */
	if( function_exists( 'wck_nr_add_repeater_boxes' ) ){
			$nested_arg = array( array( 'type' => 'select', 'title' => __( 'Nested', 'wck' ), 'options' => array( 'true', 'false' ), 'default' => 'false', 'description' => __( 'Set to true if you want this metabox to be a nested repeater inside another repeater.', 'wck' ) ) );
			array_splice( $cfc_box_args_fields, 1, 0, $nested_arg );
	}

	if( !empty( $templates ) )
		$cfc_box_args_fields[] = array( 'type' => 'select', 'title' => __( 'Page Template', 'wck' ), 'options' => $templates, 'default-option' => true, 'description' => __( 'If post type is "page" you can further select a page templete. The meta box will only appear  on the page that has that selected page template.', 'wck' ) );

	/* set up the box arguments */
	$args = array(
		'metabox_id' => 'wck-cfc-args',
		'metabox_title' => __( 'Meta Box Arguments', 'wck' ),
		'post_type' => 'wck-meta-box',
		'meta_name' => 'wck_cfc_args',
		'meta_array' => $cfc_box_args_fields,
		'sortable' => false,
		'single' => true
	);

	/* create the box */
	new Wordpress_Creation_Kit( $args );

	/* set up field types */
	$field_types = array( 'text', 'textarea', 'select', 'checkbox', 'radio', 'upload', 'wysiwyg editor', 'datepicker', 'country select', 'user select', 'cpt select' );
	$field_types = apply_filters( 'wck_field_types', $field_types );

	/* setup post types */
	$post_types = get_post_types( array( 'public'   => true ), 'names' );

	/* set up the fields array */
	$cfc_box_fields_fields = apply_filters( 'wck_cfc_box_fields_fields', array(
		array( 'type' => 'text', 'title' => __( 'Field Title', 'wck' ), 'description' => __( 'Title of the field. A slug will automatically be generated.', 'wck' ), 'required' => true ),
		array( 'type' => 'select', 'title' => __( 'Field Type', 'wck' ), 'options' => $field_types, 'default-option' => true, 'description' => __( 'The field type', 'wck' ), 'required' => true ),
		array( 'type' => 'textarea', 'title' => __( 'Description', 'wck' ), 'description' => 'The description of the field.' ),
		array( 'type' => 'select', 'title' => __( 'Required', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'false', 'description' => __( 'Whether the field is required or not', 'wck' ) ),
		array( 'type' => 'select', 'title' => __( 'CPT', 'wck' ), 'options' => $post_types, 'default' => 'post', 'description' => __( 'Select what custom post type should be used in the CPT Select.', 'wck' ) ),
		array( 'type' => 'text', 'title' => __( 'Default Value', 'wck' ), 'description' => __( 'Default value of the field. For Checkboxes if there are multiple values separate them with a ",". For an Upload field input an attachment id.', 'wck' ) ),
		array( 'type' => 'text', 'title' => __( 'Options', 'wck' ), 'description' => __( 'Options for field types "select", "checkbox" and "radio". For multiple options separate them with a ",". You can use the following structure if you want the label to be different from the value: %LabelOne%valueone,%LabelTwo%valuetwo,%LabelThree%valuethree', 'wck' ) ),
		array( 'type' => 'checkbox', 'title' => __( 'Attach upload to post', 'wck' ), 'description' => __( 'Uploads will be attached to the post if this is checked', 'wck' ), 'options' => array( 'yes' ), 'default' => 'yes' )
	) );


	/* set up the box arguments */
	$args = array(
		'metabox_id' => 'wck-cfc-fields',
		'metabox_title' => __( 'Meta Box Fields', 'wck' ),
		'post_type' => 'wck-meta-box',
		'meta_name' => 'wck_cfc_fields',
		'meta_array' => $cfc_box_fields_fields
	);

	/* create the box */
	new Wordpress_Creation_Kit( $args );
}

/* advanced label options container for update form */
add_action( "wck_before_add_form_wck_cfc_args_element_0", 'wck_cfc_description_for_args_box' );
function wck_cfc_description_for_args_box(){
	echo '<div class="cfc-message"><p>'. __( 'Enter below the arguments for the meta box.', 'wck' ) .'</p></div>';
}

/* add css classes on update form. Allows us to show/hide elements based on field type select value */
add_filter( 'wck_update_container_class_wck_cfc_fields', 'wck_cfc_update_container_class', 10, 4 );
function wck_cfc_update_container_class($wck_update_container_css_class, $meta, $results, $element_id) {
	$wck_element_type = Wordpress_Creation_Kit::wck_generate_slug( $results[$element_id]["field-type"] );
	return "class='update_container_$meta update_container_$wck_element_type element_type_$wck_element_type'";
}

add_filter( 'wck_element_class_wck_cfc_fields', 'wck_cfc_element_class', 10, 4 );
function wck_cfc_element_class($wck_element_class, $meta, $results, $element_id){
	$wck_element_type = Wordpress_Creation_Kit::wck_generate_slug( $results[$element_id]["field-type"] );
	$wck_element_class = "class='element_type_$wck_element_type'";
	return $wck_element_class;
}

/* Show the slug for field title */
add_filter( "wck_after_listed_wck_cfc_fields_element_0", 'wck_cfc_display_field_title_slug', 10, 3 );
function wck_cfc_display_field_title_slug( $form, $i, $value ){
		$form .= '<li class="slug-title"><em>'. __( 'Slug:', 'wck' ) .'</em><span>'. Wordpress_Creation_Kit::wck_generate_slug( $value ) .'</span> '. __( '(Note:changing the slug when you already have a lot of existing entries may result in unexpected behavior.)', 'wck' ) .' </li>';
	return $form;
}

/* add refresh to page */
add_action("wck_refresh_list_wck_cfc", "wck_cfc_after_refresh_list");
function wck_cfc_after_refresh_list(){
	echo '<script type="text/javascript">window.location="'. get_admin_url() . 'admin.php?page=cfc-page&updated=true' .'";</script>';
}

/* hook to create custom meta boxes */
add_action( 'admin_init', 'wck_cfc_create_boxes' );

function wck_cfc_create_boxes_args(){
	$args = array(
		'post_type' => 'wck-meta-box',
		'numberposts' => -1
	);

	$all_meta_boxes = get_posts( $args );

	$all_box_args = array();

	if( !empty( $all_meta_boxes ) ){
		foreach( $all_meta_boxes as $meta_box ){
			$wck_cfc_args = get_post_meta( $meta_box->ID, 'wck_cfc_args', true );
			$wck_cfc_fields = get_post_meta( $meta_box->ID, 'wck_cfc_fields', true );

			$box_title = get_the_title( $meta_box->ID );
			/* treat case where the post has no title */
			if( empty( $box_title ) )
				$box_title = '(no title)';

			$fields_array = array();
			if( !empty( $wck_cfc_fields ) ){
				foreach( $wck_cfc_fields as $wck_cfc_field ){
					$fields_inner_array = array( 'type' => $wck_cfc_field['field-type'], 'title' => $wck_cfc_field['field-title'] );

					if( !empty( $wck_cfc_field['description'] ) )
						$fields_inner_array['description'] = $wck_cfc_field['description'];
					if( !empty( $wck_cfc_field['required'] ) )
						$fields_inner_array['required'] = $wck_cfc_field['required'] == 'false' ? false : true;
					if ( !empty( $wck_cfc_field['cpt'] ) )
						$fields_inner_array['cpt'] = $wck_cfc_field['cpt'];
					if( isset( $wck_cfc_field['default-value'] ) )
						$fields_inner_array['default'] = $wck_cfc_field['default-value'];
					if( !empty( $wck_cfc_field['options'] ) ){
						$fields_inner_array['options'] = explode( ',', $wck_cfc_field['options'] );

						if( !empty( $fields_inner_array['options'] ) ){
							foreach( $fields_inner_array['options'] as  $key => $value ){
								$fields_inner_array['options'][$key] = trim( $value );
							}
						}

					}
					if( !empty( $wck_cfc_field['attach-upload-to-post'] ) )
						$fields_inner_array['attach_to_post'] = $wck_cfc_field['attach-upload-to-post'] == 'yes' ? true : false;

					$fields_array[] = $fields_inner_array;
				}
			}

			if( !empty( $wck_cfc_args ) ){
				foreach( $wck_cfc_args as $wck_cfc_arg ){

					/* metabox_id must be different from meta_name */
					$metabox_id = Wordpress_Creation_Kit::wck_generate_slug( $box_title );
					if( $wck_cfc_arg['meta-name'] == $metabox_id || 'content' == $metabox_id )
						$metabox_id = 'wck-'. $metabox_id;

					$box_args = array(
									'metabox_id' => $metabox_id,
									'metabox_title' => $box_title,
									'post_type' => $wck_cfc_arg['post-type'],
									'meta_name' => $wck_cfc_arg['meta-name'],
									'meta_array' => $fields_array
								);
					if( !empty( $wck_cfc_arg['sortable'] ) )
						$box_args['sortable'] = $wck_cfc_arg['sortable'] == 'false' ? false : true;

					if( !empty( $wck_cfc_arg['repeater'] ) )
						$box_args['single'] = $wck_cfc_arg['repeater'] == 'false' ? true : false;

					if( !empty( $wck_cfc_arg['post-id'] ) )
						$box_args['post_id'] = $wck_cfc_arg['post-id'];

					if( !empty( $wck_cfc_arg['page-template'] ) )
						$box_args['page_template'] = $wck_cfc_arg['page-template'];

					$box_args['unserialize_fields'] = apply_filters( 'wck_cfc_unserialize_fields_'.$wck_cfc_arg['meta-name'], false );

					/* nested repeater arg for pro version only */
					if( !empty( $wck_cfc_arg['nested'] ) )
						$box_args['nested'] = $wck_cfc_arg['nested'] == 'false' ? false : true;

					$all_box_args[] = apply_filters( "wck_cfc_box_args_".$wck_cfc_arg['meta-name'], $box_args );
				}
			}
		}
	}
	return $all_box_args;
}

function wck_cfc_create_boxes(){
	$all_box_args = wck_cfc_create_boxes_args();
	if( !empty( $all_box_args ) ){
		foreach( $all_box_args as $box_args ){
			new Wordpress_Creation_Kit( $box_args );
		}
	}
}

/* Meta Name Verification */
add_filter( 'wck_required_test_wck_cfc_args_meta-name', 'wck_cfc_ceck_meta_name', 10, 3 );
function wck_cfc_ceck_meta_name( $bool, $value, $post_id ){
	global $wpdb;

	$wck_cfc_args = get_post_meta( $post_id, 'wck_cfc_args', true );

	if( empty( $wck_cfc_args ) ){
		//this is the add case
		$check_meta_existance = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(meta_key) FROM $wpdb->postmeta WHERE meta_key = %s", $value ) );
	}
	else{
		//this is the update case
		if( $wck_cfc_args[0]['meta-name'] != $value ){
			$check_meta_existance = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(meta_key) FROM $wpdb->postmeta WHERE meta_key = %s", $value ) );
		}
		else
			$check_meta_existance = false;
	}

	if( strpos( $value, ' ' ) === false )
		$contains_spaces = false;
	else
		$contains_spaces = true;

	if( trim( strtolower( $value ) ) !== 'content' && trim( strtolower( $value ) ) !== 'action' )
		$restricted_name = false;
	else
		$restricted_name = true;

	return ( $check_meta_existance || empty($value) || $contains_spaces || $restricted_name );
}

add_filter( 'wck_required_message_wck_cfc_args_meta-name', 'wck_cfc_change_meta_message', 10, 2 );
function wck_cfc_change_meta_message( $message, $value ){
	if( empty( $value ) )
		return $message;
	else if( strpos( $value, ' ' ) !== false )
		return __( "Choose a different Meta Name as this one contains spaces\n", "wck" );
	else if( trim( strtolower( $value ) ) === 'content' || trim( strtolower( $value ) ) === 'action' )
		return __( "Choose a different Meta Name as this one is reserved\n", "wck" );
	else
		return __( "Choose a different Meta Name as this one already exists\n", "wck" );
}

/* Field Name Verification */
add_filter( 'wck_required_test_wck_cfc_fields_field-title', 'wck_cfc_ceck_field_title', 10, 3 );
function wck_cfc_ceck_field_title( $bool, $value, $post_id ){

	if( trim( strtolower( $value ) ) !== 'content' && trim( strtolower( $value ) ) !== 'action' )
		$restricted_name = false;
	else
		$restricted_name = true;

	return ( empty($value) || $restricted_name );
}

add_filter( 'wck_required_message_wck_cfc_fields_field-title', 'wck_cfc_change_field_title_message', 10, 2 );
function wck_cfc_change_field_title_message( $message, $value ){
	if( empty( $value ) )
		return $message;
	else if( trim( strtolower( $value ) ) === 'content' || trim( strtolower( $value ) ) === 'action' )
		return __( "Choose a different Field Title as this one is reserved\n", "wck" );
}

/* Add the separate meta for post type, post id and page template */
add_action( 'wck_before_add_meta', 'wck_cfc_add_separate_meta', 10, 3 );
function wck_cfc_add_separate_meta( $meta, $id, $values ){
	if( $meta == 'wck_cfc_args' ){
		// Post Type
		if( !empty( $values['post-type'] ) ){
			update_post_meta( $id, 'wck_cfc_post_type_arg', $values['post-type'] );
		}

		// Post Id
		if( !empty( $values['post-id'] ) ){
			update_post_meta( $id, 'wck_cfc_post_id_arg', $values['post-id'] );
		}

		// Page Template
		if( !empty( $values['page-template'] ) ){
			update_post_meta( $id, 'wck_cfc_page_template_arg', $values['page-template'] );
		}
	}
}

/* Change meta_key in db if field changed and also update the separate meta for post type, post id and page template */
add_action( 'wck_before_update_meta', 'wck_cfc_change_meta_key', 10, 4 );
function wck_cfc_change_meta_key( $meta, $id, $values, $element_id ){
	global $wpdb;
	if( $meta == 'wck_cfc_args' ){
		$wck_cfc_args = get_post_meta( $id, 'wck_cfc_args', true );
        if( !empty( $wck_cfc_args ) ) {
            if ($wck_cfc_args[0]['meta-name'] != $values['meta-name']) {
                $wpdb->update(
                    $wpdb->postmeta,
                    array('meta_key' => $values['meta-name']),
                    array('meta_key' => $wck_cfc_args[0]['meta-name'])
                );
            }

            // Post Type
            if ($wck_cfc_args[0]['post-type'] != $values['post-type']) {
                update_post_meta($id, 'wck_cfc_post_type_arg', $values['post-type']);
            }

            // Post Id
            if ($wck_cfc_args[0]['post-id'] != $values['post-id']) {
                update_post_meta($id, 'wck_cfc_post_id_arg', $values['post-id']);
            }

            // Page Template
            if ($wck_cfc_args[0]['page-template'] != $values['page-template']) {
                update_post_meta($id, 'wck_cfc_page_template_arg', $values['page-template']);
            }
        }
	}
}

/* Change Field Title in db if field changed */
add_action( 'wck_before_update_meta', 'wck_cfc_change_field_title', 10, 4 );
function wck_cfc_change_field_title( $meta, $id, $values, $element_id ){
	global $wpdb;
	if( $meta == 'wck_cfc_fields' ){
		$wck_cfc_fields = get_post_meta( $id, 'wck_cfc_fields', true );
        if( !empty( $wck_cfc_fields ) ) {
            if ($wck_cfc_fields[$element_id]['field-title'] != $values['field-title']) {

                $wck_cfc_args = get_post_meta($id, 'wck_cfc_args', true);
                $meta_name = $wck_cfc_args[0]['meta-name'];
                $post_id_with_this_meta = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s", $meta_name));

                if (!empty($post_id_with_this_meta)) {
                    foreach ($post_id_with_this_meta as $post) {
                        $results = get_post_meta($post->post_id, $meta_name, true);
                        if (!empty($results)) {
                            foreach ($results as $key => $result) {
                                $results[$key][Wordpress_Creation_Kit::wck_generate_slug($values['field-title'])] = $results[$key][Wordpress_Creation_Kit::wck_generate_slug($wck_cfc_fields[$element_id]['field-title'])];
                                unset($results[$key][Wordpress_Creation_Kit::wck_generate_slug($wck_cfc_fields[$element_id]['field-title'])]);
                            }
                        }
                        update_post_meta($post->post_id, $meta_name, $results);
                    }
                }
            }
        }
	}
}

/* Add Custom columns to listing */
add_filter("manage_wck-meta-box_posts_columns", "wck_cfc_edit_columns" );
function wck_cfc_edit_columns($columns){
	$columns['cfc-id'] = __( "Id", "wck" );
	$columns['cfc-post-type'] = __( "Post Type", "wck" );
	$columns['cfc-page-template'] = __( "Page Template", "wck" );

	/* only in pro version */
	if( function_exists( 'wck_nr_add_repeater_boxes' ) ){
		$columns['cfc-nested-repeater'] = __( "Nested Repeater", "wck" );
	}

	return $columns;
}

/* Register the column as sortable */
add_filter( 'manage_edit-wck-meta-box_sortable_columns', 'wck_cfc_register_sortable_columns' );
function wck_cfc_register_sortable_columns( $columns ) {
	$columns['cfc-id'] = 'cfc-id';
	$columns['cfc-post-type'] = 'cfc-post-type';
	$columns['cfc-page-template'] = 'cfc-page-template';

	return $columns;
}

/* Tell WordPress how to handle the sorting */
add_filter( 'request', 'wck_cfc_column_orderby' );
function wck_cfc_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'cfc-id' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'wck_cfc_post_id_arg',
			'orderby' => 'meta_value_num'
		) );
	}

	if ( isset( $vars['orderby'] ) && 'cfc-post-type' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'wck_cfc_post_type_arg',
			'orderby' => 'meta_value'
		) );
	}

	if ( isset( $vars['orderby'] ) && 'cfc-page-template' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'wck_cfc_page_template_arg',
			'orderby' => 'meta_value'
		) );
	}

	return $vars;
}

/* Let's set up what to display in the columns */
add_action("manage_wck-meta-box_posts_custom_column",  "wck_cfc_custom_columns", 10, 2);
function wck_cfc_custom_columns( $column_name, $post_id ){
	if( $column_name == 'cfc-id' ){
		$post_id_arg = get_post_meta( $post_id, 'wck_cfc_post_id_arg', true );
		echo $post_id_arg;
	}

	if( $column_name == 'cfc-post-type' ){
		$post_type_arg = get_post_meta( $post_id, 'wck_cfc_post_type_arg', true );
		echo $post_type_arg;
	}

	if( $column_name == 'cfc-page-template' ){
		$page_template_arg = get_post_meta( $post_id, 'wck_cfc_page_template_arg', true );
		echo $page_template_arg;
	}

	/* only in pro version */
	if( function_exists( 'wck_nr_add_repeater_boxes' ) ){
		if( $column_name == 'cfc-nested-repeater' ){
			$box_args = get_post_meta( $post_id, 'wck_cfc_args', true );
			if( !empty( $box_args[0]['nested'] ) )
				echo $box_args[0]['nested'];
		}
	}
}

/* Add side metaboxes */
add_action('add_meta_boxes', 'wck_cfc_add_side_boxes' );
function wck_cfc_add_side_boxes(){
	add_meta_box( 'wck-cfc-side', __( 'Wordpress Creation Kit', 'wck' ), 'wck_cfc_side_box_one', 'wck-meta-box', 'side', 'low' );
}
function wck_cfc_side_box_one(){
	?>
		<a href="http://www.cozmoslabs.com/wck-custom-fields-custom-post-types-plugin/?utm_source=wpbackend&utm_medium=clientsite&utm_campaign=WCKFree"><img src="<?php echo plugins_url('/images/banner_pro.png', __FILE__) ?>?v=1" width="260" height="385" alt="WCK-PRO"/></a>
	<?php
}


/* Contextual Help */
add_action('current_screen', 'wck_cfc_help');

function wck_cfc_help () {
    $screen = get_current_screen();
    /*
     * Check if current screen is wck_page_cptc-page
     * Don't add help tab if it's not
     */
    if ( $screen->id != 'wck-meta-box' )
        return;

    // Add help tabs
    $screen->add_help_tab( array(
        'id'	=> 'wck_cfc_overview',
        'title'	=> __( 'Overview', 'wck' ),
        'content'	=> '<p>' . __( 'WCK Custom Fields Creator allows you to easily create custom meta boxes for Wordpress without any programming knowledge.', 'wck' ) . '</p>',
    ) );

	$screen->add_help_tab( array(
        'id'	=> 'wck_cfc_arguments',
        'title'	=> __( 'Meta Box Arguments', 'wck' ),
        'content'	=> '<p>' . __( 'Define here the rules for the meta box. This rules are used to set up where the meta box will appear, it\'s type and also the meta key name stored in the database. The name of the entry (Enter title here) will be used as the meta box title.', 'wck' ) . '</p>',
    ) );

	$screen->add_help_tab( array(
        'id'	=> 'wck_cfc_fields',
        'title'	=> __( 'Meta Box Fields', 'wck' ),
        'content'	=> '<p>' . __( 'Define here the fields contained in the meta box. From "Field Title" a slug will be automatically generated and you will use this slug to display the data in the frontend.', 'wck' ) . '</p>',
    ) );

	$screen->add_help_tab( array(
        'id'	=> 'wck_cfc_example',
        'title'	=> __( 'CFC Frontend Example', 'wck' ),
        'content'	=> '<p>' . __( 'Let\'s consider we have a meta box with the following arguments:<br /> - Meta name: books <br /> - Post Type: post <br />And we also have two fields deffined:<br /> - A text field with the Field Title: Book name <br /> - And another text field with the Field Title: Author name ', 'wck' ) . '</p>' . '<p>' . __( 'You will notice that slugs will automatically be created for the two text fields. For "Book name" the slug will be "book-name" and for "Author name" the slug will be "author-name"', 'wck' ) . '</p>' . '<p>' . __( 'Let\'s see what the code for displaying the meta box values in single.php of your theme would be:', 'wck' ) . '</p>' . '<pre>' . '$books = get_post_meta( $post->ID, \'books\', true ); <br />foreach( $books as $book){<br />	echo $book[\'book-name\'];<br / >	echo $book[\'author-name\'];<br />}' . '</pre>' . '<p>' . __( 'So as you can see the Meta Name "books" is used as the $key parameter of the funtion <a href="http://codex.wordpress.org/Function_Reference/get_post_meta" target="_blank">get_post_meta()</a> and the slugs of the text fields are used as keys for the resulting array. Basically CFC stores the entries as post meta in a multidimensioanl array. In our case the array would be: <br /><pre>array( array( "book-name" => "The Hitchhiker\'s Guide To The Galaxy", "author-name" => "Douglas Adams" ),  array( "book-name" => "Ender\'s Game", "author-name" => "Orson Scott Card" ) );</pre> This is true even for single entries.', 'wck' ) . '</p>'
    ) );
}

/**
 * Get the Page Templates available in the current theme
 *
 * Based on wordpress get_page_templates()
 *
 * @return array Key is the template name, value is the %Template Name%filename string format of the template
 */
function wck_get_page_templates() {

	$page_templates = array();
	$theme_templates = array_flip(wp_get_theme()->get_page_templates());
	if( !empty( $theme_templates ) ){
		foreach( $theme_templates  as $key => $value){
			$page_templates[$key] = "%$key%$value";
		}
	}
	return $page_templates;
}

/* Filter post update message */
add_filter( 'post_updated_messages', 'wck_cfc_filter_post_update_message' );
function wck_cfc_filter_post_update_message($messages){
	$messages['wck-meta-box'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Metabox updated.', 'wck')
	);
	return $messages;
}

/* Filter Field Types for free version */
add_filter( 'wck_field_types', 'wck_cfc_filter_field_types' );
function wck_cfc_filter_field_types( $field_types ){
	$wck_premium_update = WCK_PLUGIN_DIR.'/update/';
	if ( !file_exists ($wck_premium_update . 'update-checker.php'))
		$field_types = array( 'text', 'textarea', 'select', 'checkbox', 'radio', 'upload', 'wysiwyg editor' );

	return $field_types;
}
?>
