<?php
/* Creates Custom Taxonomies for WordPress */

/* Create the CTC Page only for admins ( 'capability' => 'edit_theme_options' ) */
$args = array(							
			'page_title' => __( 'WCK Taxonomy Creator', 'wck' ),
			'menu_title' => __( 'Taxonomy Creator', 'wck' ),
			'capability' => 'edit_theme_options',
			'menu_slug' => 'ctc-page',
			'page_type' => 'submenu_page',
			'parent_slug' => 'wck-page',
			'priority' => 9,
			'page_icon' => plugins_url('/images/wck-32x32.png', __FILE__)
		);
new WCK_Page_Creator( $args );

/* create the meta box only for admins ( 'capability' => 'edit_theme_options' ) */
add_action( 'init', 'wck_ctc_create_box', 11 );
function wck_ctc_create_box(){
	
	if( is_admin() && current_user_can( 'edit_theme_options' ) ){
		$args = array(
				'public'   => true
			);
		$output = 'objects'; // or objects
		$post_types = get_post_types($args,$output);
		$post_type_names = array(); 
		if( !empty( $post_types ) ){
			foreach ( $post_types  as $post_type ) {
				if ( $post_type->name != 'attachment' && $post_type->name != 'wck-meta-box' && $post_type->name != 'wck-frontend-posting' && $post_type->name != 'wck-option-page' && $post_type->name != 'wck-option-field' && $post_type->name != 'wck-swift-template' ) 
					$post_type_names[] = $post_type->name;
			}
		}
		
		
		$ct_creation_fields = array( 
			array( 'type' => 'text', 'title' => __( 'Taxonomy', 'wck' ), 'description' => __( '(The name of the taxonomy. Name must not contain capital letters or spaces.)', 'wck' ), 'required' => true ),			
			array( 'type' => 'text', 'title' => __( 'Singular Label', 'wck' ), 'required' => true, 'description' => __( 'ex. Writer', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Plural Label', 'wck' ), 'required' => true, 'description' => __( 'ex. Writers', 'wck' ) ),
			array( 'type' => 'checkbox', 'title' => __( 'Attach to', 'wck' ), 'options' => $post_type_names ),
			array( 'type' => 'select', 'title' => __( 'Hierarchical', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'false', 'description' => __( 'Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags.', 'wck' ) ),

			array( 'type' => 'text', 'title' => __( 'Search Items', 'wck' ), 'description' => __( 'ex. Search Writers', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Popular Items', 'wck' ), 'description' => __( 'ex. Popular Writers', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'All Items', 'wck' ), 'description' => __( 'ex. All Writers', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Parent Item', 'wck' ), 'description' => __( 'ex. Parent Genre', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Parent Item Colon', 'wck' ), 'description' => __( 'ex. Parent Genre:', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Edit Item', 'wck' ), 'description' => __( 'ex. Edit Writer', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Update Item', 'wck' ), 'description' => __( 'ex. Update Writer', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Add New Item', 'wck' ), 'description' => __( 'ex. Add New Writer', 'wck' ) ),		
			array( 'type' => 'text', 'title' => __( 'New Item Name', 'wck' ), 'description' => __( 'ex. New Writer Name', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Separate Items With Commas', 'wck' ), 'description' => __( 'ex. Separate writers with commas', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Add Or Remove Items', 'wck' ), 'description' => __( 'ex. Add or remove writers', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Choose From Most Used', 'wck' ), 'description' => __( 'ex. Choose from the most used writers', 'wck' ) ),
			array( 'type' => 'text', 'title' => __( 'Menu Name', 'wck' ) ),	
			
			array( 'type' => 'select', 'title' => __( 'Public', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'true', 'description' => __( 'Meta argument used to define default values for publicly_queriable, show_ui, show_in_nav_menus and exclude_from_search', 'wck' ) ),
			array( 'type' => 'select', 'title' => __( 'Show UI', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'true', 'description' => __( 'Whether to generate a default UI for managing this post type.', 'wck' ) ),
			array( 'type' => 'select', 'title' => __( 'Show Tagcloud', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'true', 'description' => __( 'Whether to allow the Tag Cloud widget to use this taxonomy.', 'wck' ) ),
			array( 'type' => 'select', 'title' => __( 'Show Admin Column', 'wck' ), 'options' => array( 'false', 'true' ), 'default' => 'false', 'description' => __( 'Whether to allow automatic creation of taxonomy columns on associated post-types.', 'wck' ) )
		);

		$args = array(
			'metabox_id' => 'ctc_creation_box',
			'metabox_title' => __( 'Custom Taxonomy Creation', 'wck' ),
			'post_type' => 'ctc-page',
			'meta_name' => 'wck_ctc',
			'meta_array' => $ct_creation_fields,	
			'context' 	=> 'option'
		);


		new Wordpress_Creation_Kit( $args );
	}
}

add_action( 'init', 'wck_ctc_create_taxonomy' );

function wck_ctc_create_taxonomy(){
	$cts = get_option('wck_ctc');
	if( !empty( $cts ) ){
		foreach( $cts as $ct ){
			
			$labels = array(
				'name' => _x( $ct['plural-label'], 'taxonomy general name' ),
				'singular_name' => _x( $ct['singular-label'], 'taxonomy singular name'),
				'search_items' => __( $ct['search-items'] ? $ct['search-items'] : 'Search '.$ct['plural-label'], 'wck' ),
				'popular_items' => __( $ct['popular-items'] ? $ct['popular-items'] : "Popular ".$ct['plural-label'], 'wck' ),
				'all_items' => __( $ct['all-items'] ? $ct['all-items'] : "All ".$ct['plural-label'], 'wck' ) ,
				'parent_item' => __( $ct['parent-item'] ? $ct['parent-item'] : "Parent ".$ct['singular-label'], 'wck' ),
				'parent_item_colon' => __( $ct['parent-item-colon'] ? $ct['parent-item-colon'] : "Parent ".$ct['singular-label'].':', 'wck' ),
				'edit_item' => __( $ct['edit-item'] ? $ct['edit-item'] : "Edit ".$ct['singular-label'], 'wck' ),
				'update_item' => __( $ct['update-item'] ? $ct['update-item'] : "Update ".$ct['singular-label'], 'wck' ),
				'add_new_item' =>  __( $ct['add-new-item'] ? $ct['add-new-item'] : "Add New ". $ct['singular-label'], 'wck' ),
				'new_item_name' => __( $ct['new-item-name'] ? $ct['new-item-name'] :  "New ". $ct['singular-label']. ' Name', 'wck' ), 
				'separate_items_with_commas' => __( $ct['separate-items-with-commas'] ? $ct['separate-items-with-commas'] :  "Separate  ". strtolower( $ct['plural-label'] ). ' with commas', 'wck' ), 
				'add_or_remove_items' => __( $ct['add-or-remove-items'] ? $ct['add-or-remove-items'] : "Add or remove " .strtolower( $ct['plural-label'] ), 'wck' ),
				'choose_from_most_used' => __( $ct['choose-from-most-used'] ? $ct['choose-from-most-used'] : "Choose from the most used " .strtolower( $ct['plural-label'] ), 'wck' ),				
				'menu_name' => $ct['menu-name'] ? $ct['menu-name'] : $ct['plural-label']
			);
			
			$args = array(
				'labels' => $labels,
				'public' => $ct['public'] == 'false' ? false : true,								
				'show_ui' => $ct['show-ui'] == 'false' ? false : true, 								
				'hierarchical' => $ct['hierarchical'] == 'false' ? false : true,
				'show_tagcloud' => $ct['show-tagcloud'] == 'false' ? false : true				
			);			
			
			if( !empty( $ct['show-admin-column'] ) ){
				$args['show_admin_column'] = $ct['show-admin-column'] == 'false' ? false : true;
			}

			if( !empty( $ct['attach-to'] ) )
				$object_type = explode( ', ', $ct['attach-to'] );
			else 
				$object_type = '';
			
			register_taxonomy( $ct['taxonomy'], $object_type, apply_filters( 'wck_ctc_register_taxonomy_args', $args, $ct['taxonomy'] ) );
		}
	}
}

/* Flush rewrite rules */
add_action('init', 'ctc_flush_rules', 20);
function ctc_flush_rules(){
	if( isset( $_GET['page'] ) && $_GET['page'] == 'ctc-page' && isset( $_GET['updated'] ) && $_GET['updated'] == 'true' )
		flush_rewrite_rules( false  );
}

/* add refresh to page */
add_action("wck_refresh_list_wck_ctc", "wck_ctc_after_refresh_list");
add_action("wck_refresh_entry_wck_ctc", "wck_ctc_after_refresh_list");
function wck_ctc_after_refresh_list(){
	echo '<script type="text/javascript">window.location="'. get_admin_url() . 'admin.php?page=ctc-page&updated=true' .'";</script>';
}

/* advanced labels container for add form */
add_action( "wck_before_add_form_wck_ctc_element_5", 'wck_ctc_form_label_wrapper_start' );
function wck_ctc_form_label_wrapper_start(){
	echo '<li><a href="javascript:void(0)" onclick="jQuery(\'#ctc-advanced-label-options-container\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Label Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Label Options', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Label Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Label Options', 'wck' ) .'\');">'. __( 'Show Advanced Label Options', 'wck' ) .'</a></li>';
	echo '<li id="ctc-advanced-label-options-container" style="display:none;"><ul>';
}

add_action( "wck_after_add_form_wck_ctc_element_17", 'wck_ctc_form_label_wrapper_end' );
function wck_ctc_form_label_wrapper_end(){
	echo '</ul></li>';	
}

/* advanced options container for add form */
add_action( "wck_before_add_form_wck_ctc_element_18", 'wck_ctc_form_wrapper_start' );
function wck_ctc_form_wrapper_start(){
	echo '<li><a href="javascript:void(0)" onclick="jQuery(\'#ctc-advanced-options-container\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Options', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Options', 'wck' ) .'\');">'. __( 'Show Advanced Options', 'wck' ) .'</a></li>';
	echo '<li id="ctc-advanced-options-container" style="display:none;"><ul>';
}

add_action( "wck_after_add_form_wck_ctc_element_21", 'wck_ctc_form_wrapper_end' );
function wck_ctc_form_wrapper_end(){
	echo '</ul></li>';	
}

/* advanced label options container for update form */
add_filter( "wck_before_update_form_wck_ctc_element_5", 'wck_ctc_update_form_label_wrapper_start', 10, 2 );
function wck_ctc_update_form_label_wrapper_start( $form, $i ){
	$form .=  '<li><a href="javascript:void(0)" onclick="jQuery(\'#ctc-advanced-label-options-update-container-'.$i.'\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Label Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Label Options', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Label Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Label Options', 'wck' ) .'\');">'. __( 'Show Advanced Label Options', 'wck' ) .'</a></li>';
	$form .= '<li id="ctc-advanced-label-options-update-container-'.$i.'" style="display:none;"><ul>';
	return $form;
}

add_filter( "wck_after_update_form_wck_ctc_element_17", 'wck_ctc_update_form_label_wrapper_end', 10, 2 );
function wck_ctc_update_form_label_wrapper_end( $form, $i ){
	$form .=  '</ul></li>';
	return $form;
}

/* advanced options container for update form */
add_filter( "wck_before_update_form_wck_ctc_element_18", 'wck_ctc_update_form_wrapper_start', 10, 2 );
function wck_ctc_update_form_wrapper_start( $form, $i ){
	$form .=  '<li><a href="javascript:void(0)" onclick="jQuery(\'#ctc-advanced-options-update-container-'.$i.'\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Options', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Options', 'wck' ) .'\');">'. __( 'Show Advanced Options', 'wck' ) .'</a></li>';
	$form .= '<li id="ctc-advanced-options-update-container-'.$i.'" style="display:none;"><ul>';
	return $form;
}

add_filter( "wck_after_update_form_wck_ctc_element_21", 'wck_ctc_update_form_wrapper_end', 10, 2 );
function wck_ctc_update_form_wrapper_end( $form, $i ){
	$form .=  '</ul></li>';	
	return $form;
}


/* advanced label options container for display */
add_filter( "wck_before_listed_wck_ctc_element_5", 'wck_ctc_display_label_wrapper_start', 10, 2 );
function wck_ctc_display_label_wrapper_start( $form, $i ){
	$form .=  '<li><a href="javascript:void(0)" onclick="jQuery(\'#ctc-advanced-label-options-display-container-'.$i.'\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Labels', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Labels', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Labels', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Labels', 'wck' ) .'\');">'. __( 'Show Advanced Labels', 'wck' ) .'</a></li>';
	$form .= '<li id="ctc-advanced-label-options-display-container-'.$i.'" style="display:none;"><ul>';
	return $form;
}

add_filter( "wck_after_listed_wck_ctc_element_17", 'wck_ctc_display_label_wrapper_end', 10, 2 );
function wck_ctc_display_label_wrapper_end( $form, $i ){
	$form .=  '</ul></li>';	
	return $form;
}

/* advanced options container for display */
add_filter( "wck_before_listed_wck_ctc_element_18", 'wck_ctc_display_adv_wrapper_start', 10, 2 );
function wck_ctc_display_adv_wrapper_start( $form, $i ){
	$form .=  '<li><a href="javascript:void(0)" onclick="jQuery(\'#ctc-advanced-options-display-container-'.$i.'\').toggle(); if( jQuery(this).text() == \''. __( 'Show Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Hide Advanced Options', 'wck' ) .'\');  else if( jQuery(this).text() == \''. __( 'Hide Advanced Options', 'wck' ) .'\' ) jQuery(this).text(\''. __( 'Show Advanced Options', 'wck' ) .'\');">'. __( 'Show Advanced Options', 'wck' ) .'</a></li>';
	$form .= '<li id="ctc-advanced-options-display-container-'.$i.'" style="display:none;"><ul>';
	return $form;
}

add_filter( "wck_after_listed_wck_ctc_element_21", 'wck_ctc_display_adv_wrapper_end', 10, 2 );
function wck_ctc_display_adv_wrapper_end( $form, $i ){
	$form .=  '</ul></li>';	
	return $form;
}

/* Add side metaboxes */
add_action('add_meta_boxes', 'wck_ctc_add_side_boxes' );
function wck_ctc_add_side_boxes(){
	add_meta_box( 'wck-ctc-side', __( 'Wordpress Creation Kit', 'wck' ), 'wck_ctc_side_box_one', 'wck_page_ctc-page', 'side', 'high' );
}
function wck_ctc_side_box_one(){
	?>
		<a href="http://www.cozmoslabs.com/wck-custom-fields-custom-post-types-plugin/?utm_source=wpbackend&utm_medium=clientsite&utm_campaign=WCKFree"><img src="<?php echo plugins_url('/images/banner_pro.png', __FILE__) ?>?v=1" width="260" height="385" alt="WCK-PRO"/></a>
	<?php
}

/* Contextual Help */
add_action('load-wck_page_ctc-page', 'wck_ctc_help');

function wck_ctc_help () {    
    $screen = get_current_screen();

    /*
     * Check if current screen is wck_page_cptc-page
     * Don't add help tab if it's not
     */
    if ( $screen->id != 'wck_page_ctc-page' )
        return;

    // Add help tabs
    $screen->add_help_tab( array(
        'id'	=> 'wck_ctc_overview',
        'title'	=> __( 'Overview', 'wck' ),
        'content'	=> '<p>' . __( 'WCK Custom Taxonomy Creator allows you to easily create custom taxonomy for Wordpress without any programming knowledge.<br />Most of the common options for creating a taxonomy are displayed by default while the advanced and label options are just one click away.', 'wck' ) . '</p>',
    ) );
	
	$screen->add_help_tab( array(
        'id'	=> 'wck_ctc_labels',
        'title'	=> __( 'Labels', 'wck' ),
        'content'	=> '<p>' . __( 'For simplicity you are required to introduce only the Singular Label and Plural Label from wchich the rest of the labels will be formed.<br />For a more detailed control of the labels you just have to click the "Show Advanced Label Options" link and all the availabel labels will be displayed', 'wck' ) . '</p>',
    ) );
	
	$screen->add_help_tab( array(
        'id'	=> 'wck_ctc_advanced',
        'title'	=> __( 'Advanced Options', 'wck' ),
        'content'	=> '<p>' . __( 'The Advanced Options are set to the most common defaults for taxonomies. To display them click the "Show Advanced Options" link.', 'wck' ) . '</p>',
    ) );
}
?>