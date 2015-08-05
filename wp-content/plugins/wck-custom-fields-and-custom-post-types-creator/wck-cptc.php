<?php
/* Creates Custom Post Types for WordPress */


$args = array(							
			'page_title' => __( 'WCK Post Type Creator', 'wck' ),
			'menu_title' => __( 'Post Type Creator', 'wck' ),
			'capability' => 'edit_theme_options',
			'menu_slug' => 'cptc-page',									
			'page_type' => 'submenu_page',
			'parent_slug' => 'wck-page',
			'priority' => 8,
			'page_icon' => plugins_url('/images/wck-32x32.png', __FILE__)
		);
$cptc_page = new WCK_Page_Creator( $args );


/* Add Scripts */
add_action('admin_enqueue_scripts', 'wck_cptc_print_scripts' );
function wck_cptc_print_scripts($hook){		
	if( 'wck_page_cptc-page' == $hook ){			
		wp_register_style('wck-cptc-css', plugins_url('/css/wck-cptc.css', __FILE__));
		wp_enqueue_style('wck-cptc-css');	
	}	
}

/* create the meta box only for admins ( 'capability' => 'edit_theme_options' ) */
add_action( 'init', 'wck_cptc_create_box', 11 );
function wck_cptc_create_box(){
	
	if( is_admin() && current_user_can( 'edit_theme_options' ) ){
		/* get registered taxonomies */
		$args = array( 
					'public'   => true 
				);
		$output = 'objects';
		$taxonomies = get_taxonomies($args,$output);
		$taxonomie_names = array();
		
		if( !empty( $taxonomies ) ){
			foreach ($taxonomies  as $taxonomie ) {
				if ( $taxonomie->name != 'nav_menu' && $taxonomie->name != 'post_format')
					$taxonomie_names[] = $taxonomie->name;
			}
		}
		
		/* set up the fields array */
		$cpt_creation_fields = array( 
			array( 'type' => 'text', 'title' => __( 'Post type', 'wck' ), 'description' => __( '(max. 20 characters, can not contain capital letters, hyphens, or spaces)', 'wck' ), 'required' => true ), 
			array( 'type' => 'textarea', 'title' => __( 'Description', 'wck' ), 'description' => __( 'A short descriptive summary of what the post type is.', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Singular Label', 'wck' ), 'required' => true, 'description' => __( 'ex. Book', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Plural Label', 'wck' ), 'required' => true, 'description' => __( 'ex. Books', 'wck' ) ),
			array( 'type' => 'select', 'title' => __( 'Hierarchical', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'false', 'description' => __( 'Whether the post type is hierarchical. Allows Parent to be specified.', 'wck' ) ),
			array( 'type' => 'select', 'title' => __( 'Has Archive', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'false', 'description' => __( 'Enables post type archives. Will use string as archive slug. Will generate the proper rewrite rules if rewrite is enabled.', 'wck' ) ),
			array( 'type' => 'checkbox', 'title' => __( 'Supports', 'wck' ), 'options' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats' ), 'default' =>'title, editor' ),
			
			
			array( 'type' => 'text', 'title' => __( 'Add New', 'wck' ), 'description' => __( 'ex. Add New', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Add New Item', 'wck' ), 'description' => __( 'ex. Add New Book', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Edit Item', 'wck' ), 'description' => __( 'ex. Edit Book', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'New Item', 'wck' ), 'description' => __( 'ex. New Book', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'All Items', 'wck' ), 'description' => __( 'ex. All Books', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'View Items', 'wck' ), 'description' => __( 'ex. View Books', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Search Items', 'wck' ), 'description' => __( 'ex. Search Books', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Not Found', 'wck' ), 'description' => __( 'ex. No Books Found', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Not Found In Trash', 'wck' ), 'description' => __( 'ex. No Books found in Trash', 'wck' ) ),	
			array( 'type' => 'text', 'title' => __( 'Parent Item Colon', 'wck' ), 'description' => __( 'the parent text. This string isn\'t used on non-hierarchical types. In hierarchical ones the default is Parent Page ', 'wck' ) ),	
			array( 'type' => 'text', 'title' => __( 'Menu Name', 'wck' ) ),			
			
			array( 'type' => 'select', 'title' => __( 'Public', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'true', 'description' => __( 'Meta argument used to define default values for publicly_queriable, show_ui, show_in_nav_menus and exclude_from_search', 'wck' ) ),
			array( 'type' => 'select', 'title' => __( 'Show UI', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'true', 'description' => __( 'Whether to generate a default UI for managing this post type.', 'wck' ) ), 
			array( 'type' => 'select', 'title' => __( 'Show In Nav Menus', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'true', 'description' => __( 'Whether post_type is available for selection in navigation menus.', 'wck' ) ), 
			array( 'type' => 'text', 'title' => __( 'Show In Menu', 'wck' ), 'default' => 'true', 'description' => __( 'Whether to show the post type in the admin menu. show_ui must be true. "false" - do not display in the admin menu, "true" - display as a top level menu, "some string" - If an existing top level page such as "tools.php" or "edit.php?post_type=page", the post type will be placed as a sub menu of that.', 'wck' ) ), 
			array( 'type' => 'text', 'title' => __( 'Menu Position', 'wck' ), 'description' => __( 'The position in the menu order the post type should appear.', 'wck' ) ), 
			array( 'type' => 'text', 'title' => __( 'Menu Icon', 'wck' ), 'description' => __( 'The url to the icon to be used for this menu.', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Capability Type', 'wck' ), 'description' => __( 'The string to use to build the read, edit, and delete capabilities.', 'wck' ), 'default' => 'post' ), 		
			array( 'type' => 'checkbox', 'title' => __( 'Taxonomies', 'wck' ), 'options' => $taxonomie_names ),		
			array( 'type' => 'select', 'title' => __( 'Rewrite', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'true', 'description' => __( 'Rewrite permalinks.', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Rewrite Slug', 'wck' ), 'description' => __( 'Defaults to post type name.', 'wck' ) )
		);
		
		/* set up the box arguments */
		$args = array(
			'metabox_id' => 'option_page',
			'metabox_title' => __( 'Custom Post Type Creation', 'wck' ),
			'post_type' => 'cptc-page',
			'meta_name' => 'wck_cptc',
			'meta_array' => $cpt_creation_fields,	
			'context' 	=> 'option',
			'sortable' => false
		);

		/* create the box */
		new Wordpress_Creation_Kit( $args );
	}
}

/* hook to create custom post types */
add_action( 'init', 'wck_cptc_create_cpts' );

function wck_cptc_create_cpts(){
	$cpts = get_option('wck_cptc');
	if( !empty( $cpts ) ){
		foreach( $cpts as $cpt ){
			
			$labels = array(
				'name' => _x( $cpt['plural-label'], 'post type general name'),
				'singular_name' => _x( $cpt['singular-label'], 'post type singular name'),
				'add_new' => _x( $cpt['add-new'] ? $cpt['add-new'] : 'Add New', strtolower( $cpt['singular-label'] ) ),
				'add_new_item' => __( $cpt['add-new-item'] ? $cpt['add-new-item'] : "Add New ".$cpt['singular-label']),
				'edit_item' => __( $cpt['edit-item'] ? $cpt['edit-item'] : "Edit ".$cpt['singular-label'], 'wck' ) ,
				'new_item' => __( $cpt['new-item'] ? $cpt['new-item'] : "New ".$cpt['singular-label'], 'wck' ),
				'all_items' => __( $cpt['all-items'] ? $cpt['all-items'] : "All ".$cpt['plural-label'] , 'wck'),
				'view_item' => __( !empty( $cpt['view-item'] ) ? $cpt['view-item'] : "View ".$cpt['singular-label'] , 'wck'),
				'search_items' => __( $cpt['search-items'] ? $cpt['search-items'] : "Search ".$cpt['plural-label'], 'wck' ),
				'not_found' =>  __( $cpt['not-found'] ? $cpt['not-found'] : "No ". strtolower( $cpt['plural-label'] ) ." found", 'wck' ),
				'not_found_in_trash' => __( $cpt['not-found-in-trash'] ? $cpt['not-found-in-trash'] :  "No ". strtolower( $cpt['plural-label'] ) ." found in Trash", 'wck' ), 
				'parent_item_colon' => __( !empty( $cpt['parent-item-colon'] ) ? $cpt['parent-item-colon'] :  "Parent Page", 'wck' ),
				'menu_name' => $cpt['menu-name'] ? $cpt['menu-name'] : $cpt['plural-label']
			);
			$args = array(
				'labels' => $labels,
				'public' => $cpt['public'] == 'false' ? false : true,
				'description'	=> $cpt['description'],
				'show_ui' => $cpt['show-ui'] == 'false' ? false : true,
				'show_in_nav_menus' => !empty( $cpt['show-in-nav-menus'] ) && $cpt['show-in-nav-menus'] == 'false' ? false : true,	
				'has_archive' => $cpt['has-archive'] == 'false' ? false : true,
				'hierarchical' => $cpt['hierarchical'] == 'false' ? false : true,													
				'supports' => explode( ', ', $cpt['supports'] )				
			);
			
			if( !empty( $cpt['show-in-menu'] ) ){
				$args['show_in_menu'] = $cpt['show-in-menu'] == 'true' ? true : $cpt['show-in-menu'];
			}
			
			if( !empty( $cpt['menu-position'] ) )
				$args['menu_position'] = intval( $cpt['menu-position'] );
			
			if( has_filter( "wck_cptc_capabilities_{$cpt['post-type']}" ) )			
				$args['capabilities'] = apply_filters( "wck_cptc_capabilities_{$cpt['post-type']}", $cpt['capability-type'] );
			else
				$args['capability_type'] = $cpt['capability-type'];
			
			if( !empty( $cpt['taxonomies'] ) )
				$args['taxonomies'] = explode( ', ', $cpt['taxonomies'] );
			
			if( !empty( $cpt['menu-icon'] ) )
				$args['menu_icon'] = $cpt['menu-icon'];
				
			if( $cpt['rewrite'] == 'false' )
				$args['rewrite'] = $cpt['rewrite'] == 'false' ? false : true;
			else{
				if( !empty( $cpt['rewrite-slug'] ) )
					$args['rewrite'] = array('slug' => $cpt['rewrite-slug']);
			}		
			
			register_post_type( $cpt['post-type'], apply_filters( 'wck_cptc_register_post_type_args', $args, $cpt['post-type'] ) );
		}
	}
}

/* Flush rewrite rules */
add_action('init', 'cptc_flush_rules', 20);
function cptc_flush_rules(){
	if( isset( $_GET['page'] ) && $_GET['page'] == 'cptc-page' && isset( $_GET['updated'] ) && $_GET['updated'] == 'true' )
		flush_rewrite_rules( false  );
}

/* advanced labels container for add form */
add_action( "wck_before_add_form_wck_cptc_element_7", 'wck_cptc_form_label_wrapper_start' );
function wck_cptc_form_label_wrapper_start(){
	echo '<li><a href="javascript:void(0)" onclick="jQuery(\'#cptc-advanced-label-options-container\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Label Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Label Options', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Label Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Label Options', 'wck' ) .'\');">'. __('Show Advanced Label Options', 'wck' ) .'</a></li>';
	echo '<li id="cptc-advanced-label-options-container" style="display:none;"><ul>';
}

add_action( "wck_after_add_form_wck_cptc_element_17", 'wck_cptc_form_label_wrapper_end' );
function wck_cptc_form_label_wrapper_end(){
	echo '</ul></li>';	
}

/* advanced options container for add form */
add_action( "wck_before_add_form_wck_cptc_element_18", 'wck_cptc_form_wrapper_start' );
function wck_cptc_form_wrapper_start(){
	echo '<li><a href="javascript:void(0)" onclick="jQuery(\'#cptc-advanced-options-container\').toggle(); if( jQuery(this).text() == \''. __('Show Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\'Hide Advanced Options\');  else if( jQuery(this).text() == \''. __('Hide Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __('Show Advanced Options', 'wck' ) .'\');">'. __('Show Advanced Options', 'wck' ) .'</a></li>';
	echo '<li id="cptc-advanced-options-container" style="display:none;"><ul>';
}

add_action( "wck_after_add_form_wck_cptc_element_27", 'wck_cptc_form_wrapper_end' );
function wck_cptc_form_wrapper_end(){
	echo '</ul></li>';	
}

/* advanced label options container for update form */
add_filter( "wck_before_update_form_wck_cptc_element_7", 'wck_cptc_update_form_label_wrapper_start', 10, 2 );
function wck_cptc_update_form_label_wrapper_start( $form, $i ){
	$form .=  '<li><a href="javascript:void(0)" onclick="jQuery(\'#cptc-advanced-label-options-update-container-'.$i.'\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Label Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Label Options', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Label Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Label Options', 'wck' ) .'\');">'. __( 'Show Advanced Label Options', 'wck' ) .'</a></li>';
	$form .= '<li id="cptc-advanced-label-options-update-container-'.$i.'" style="display:none;"><ul>';
	return $form;
}

add_filter( "wck_after_update_form_wck_cptc_element_17", 'wck_cptc_update_form_label_wrapper_end', 10, 2 );
function wck_cptc_update_form_label_wrapper_end( $form, $i ){
	$form .=  '</ul></li>';
	return $form;
}

/* advanced options container for update form */
add_filter( "wck_before_update_form_wck_cptc_element_18", 'wck_cptc_update_form_wrapper_start', 10, 2 );
function wck_cptc_update_form_wrapper_start( $form, $i ){
	$form .=  '<li><a href="javascript:void(0)" onclick="jQuery(\'#cptc-advanced-options-update-container-'.$i.'\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Options', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Options', 'wck' ) .'\');">'. __( 'Show Advanced Options', 'wck' ) .'</a></li>';
	$form .= '<li id="cptc-advanced-options-update-container-'.$i.'" style="display:none;"><ul>';
	return $form;
}

add_filter( "wck_after_update_form_wck_cptc_element_27", 'wck_cptc_update_form_wrapper_end', 10, 2 );
function wck_cptc_update_form_wrapper_end( $form, $i ){
	$form .=  '</ul></li>';	
	return $form;
}


/* advanced label options container for display */
add_filter( "wck_before_listed_wck_cptc_element_7", 'wck_cptc_display_label_wrapper_start', 10, 2 );
function wck_cptc_display_label_wrapper_start( $form, $i ){
	$form .=  '<li><a href="javascript:void(0)" onclick="jQuery(\'#cptc-advanced-label-options-display-container-'.$i.'\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Labels', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Labels', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Labels', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Labels', 'wck' ) .'\');">'. __( 'Show Advanced Labels', 'wck' ) .'</a></li>';
	$form .= '<li id="cptc-advanced-label-options-display-container-'.$i.'" style="display:none;"><ul>';
	return $form;
}

add_filter( "wck_after_listed_wck_cptc_element_17", 'wck_cptc_display_label_wrapper_end', 10, 2 );
function wck_cptc_display_label_wrapper_end( $form, $i ){
	$form .=  '</ul></li>';	
	return $form;
}

/* advanced options container for display */
add_filter( "wck_before_listed_wck_cptc_element_18", 'wck_cptc_display_adv_wrapper_start', 10, 2 );
function wck_cptc_display_adv_wrapper_start( $form, $i ){
	$form .=  '<li><a href="javascript:void(0)" onclick="jQuery(\'#cptc-advanced-options-display-container-'.$i.'\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Options', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Options', 'wck' ) .'\');">'. __( 'Show Advanced Options', 'wck' ) .'</a></li>';
	$form .= '<li id="cptc-advanced-options-display-container-'.$i.'" style="display:none;"><ul>';
	return $form;
}

add_filter( "wck_after_listed_wck_cptc_element_27", 'wck_cptc_display_adv_wrapper_end', 10, 2 );
function wck_cptc_display_adv_wrapper_end( $form, $i ){
	$form .=  '</ul></li>';	
	return $form;
}

/* add refresh to page */
add_action("wck_refresh_list_wck_cptc", "wck_cptc_after_refresh_list");
add_action("wck_refresh_entry_wck_cptc", "wck_cptc_after_refresh_list");
function wck_cptc_after_refresh_list(){
	echo '<script type="text/javascript">window.location="'. get_admin_url() . 'admin.php?page=cptc-page&updated=true' .'";</script>';
}

/* Add side metaboxes */
add_action('add_meta_boxes', 'wck_cptc_add_side_boxes' );
function wck_cptc_add_side_boxes(){
	add_meta_box( 'wck-cptc-side', __( 'Wordpress Creation Kit', 'wck' ), 'wck_cptc_side_box_one', 'wck_page_cptc-page', 'side', 'high' );
}
function wck_cptc_side_box_one(){
	?>
		<a href="http://www.cozmoslabs.com/wck-custom-fields-custom-post-types-plugin/?utm_source=wpbackend&utm_medium=clientsite&utm_campaign=WCKFree"><img src="<?php echo plugins_url('/images/banner_pro.png', __FILE__) ?>?v=1" width="260" height="385" alt="WCK-PRO"/></a>
	<?php
}


/* Contextual Help */
add_action('load-wck_page_cptc-page', 'wck_cptc_help');

function wck_cptc_help () {    
    $screen = get_current_screen();

    /*
     * Check if current screen is wck_page_cptc-page
     * Don't add help tab if it's not
     */
    if ( $screen->id != 'wck_page_cptc-page' )
        return;

    // Add help tabs
    $screen->add_help_tab( array(
        'id'	=> 'wck_cptc_overview',
        'title'	=> __('Overview', 'wck' ),
        'content'	=> '<p>' . __( 'WCK Custom Post Type Creator allows you to easily create custom post types for Wordpress without any programming knowledge.<br />Most of the common options for creating a post type are displayed by default while the advanced options and label are just one click away.', 'wck' ) . '</p>',
    ) );
	
	$screen->add_help_tab( array(
        'id'	=> 'wck_cptc_labels',
        'title'	=> __( 'Labels', 'wck' ),
        'content'	=> '<p>' . __( 'For simplicity you are required to introduce only the Singular Label and Plural Label from wchich the rest of the labels will be formed.<br />For a more detailed control of the labels you just have to click the "Show Advanced Label Options" link and all the availabel labels will be displayed.', 'wck' ) . '</p>',
    ) );
	
	$screen->add_help_tab( array(
        'id'	=> 'wck_cptc_advanced',
        'title'	=> __('Advanced Options', 'wck' ),
        'content'	=> '<p>' . __( 'The Advanced Options are set to the most common defaults for custom post types. To display them click the "Show Advanced Options" link.', 'wck' ) . '</p>',
    ) );
}
?>