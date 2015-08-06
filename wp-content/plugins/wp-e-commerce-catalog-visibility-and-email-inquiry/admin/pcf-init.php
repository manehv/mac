<?php
/**
 * Call this function when plugin is deactivated
 */
function wpec_pcf_install(){
	update_option('a3rev_wpec_pcf_lite_version', '1.2.3');

	// Set Settings Default from Admin Init
	global $wpec_ei_admin_init;
	$wpec_ei_admin_init->set_default_settings();

	// Build sass
	global $wpec_pcf_less;
	$wpec_pcf_less->plugin_build_sass();

	update_option('a3rev_wpec_pcf_just_installed', true);
}

update_option('a3rev_wpec_pcf_plugin', 'wpec_pcf');

/**
 * Load languages file
 */
function wpec_pcf_init() {
	if ( get_option('a3rev_wpec_pcf_just_installed') ) {
		delete_option('a3rev_wpec_pcf_just_installed');
		wp_redirect( admin_url( 'admin.php?page=wpec-cart-email', 'relative' ) );
		exit;
	}
	load_plugin_textdomain( 'wpec_pcf', false, WPEC_PCF_FOLDER.'/languages' );
}
// Add language
add_action('init', 'wpec_pcf_init');

// Add custom style to dashboard
add_action( 'admin_enqueue_scripts', array( 'WPEC_PCF_Hook_Filter', 'a3_wp_admin' ) );

// Add admin sidebar menu css
add_action( 'admin_enqueue_scripts', array( 'WPEC_PCF_Hook_Filter', 'admin_sidebar_menu_css' ) );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WPEC_PCF_Hook_Filter', 'plugin_extra_links'), 10, 2 );


	// Need to call Admin Init to show Admin UI
	global $wpec_ei_admin_init;
	$wpec_ei_admin_init->init();

	// Add upgrade notice to Dashboard pages
	add_filter( $wpec_ei_admin_init->plugin_name . '_plugin_extension', array( 'WPEC_PCF_Functions', 'plugin_extension' ) );

	// Include style into header
	add_action('get_header', array('WPEC_PCF_Hook_Filter', 'add_style_header'), 1);

	// Include script into footer
	add_action('get_footer', array('WPEC_PCF_Hook_Filter', 'script_contact_popup'), 1);

	// AJAX hide yellow message dontshow
	add_action('wp_ajax_wpec_ei_yellow_message_dontshow', array('WPEC_PCF_Functions', 'wpec_ei_yellow_message_dontshow') );
	add_action('wp_ajax_nopriv_wpec_ei_yellow_message_dontshow', array('WPEC_PCF_Functions', 'wpec_ei_yellow_message_dontshow') );

	// AJAX hide yellow message dismiss
	add_action('wp_ajax_wpec_ei_yellow_message_dismiss', array('WPEC_PCF_Functions', 'wpec_ei_yellow_message_dismiss') );
	add_action('wp_ajax_nopriv_wpec_ei_yellow_message_dismiss', array('WPEC_PCF_Functions', 'wpec_ei_yellow_message_dismiss') );

	// AJAX pcf contact popup
	add_action('wp_ajax_pcf_contact_popup', array('WPEC_PCF_Hook_Filter', 'pcf_contact_popup') );
	add_action('wp_ajax_nopriv_pcf_contact_popup', array('WPEC_PCF_Hook_Filter', 'pcf_contact_popup') );

	// AJAX pcf_contact_action
	add_action('wp_ajax_pcf_contact_action', array('WPEC_PCF_Hook_Filter', 'pcf_contact_action') );
	add_action('wp_ajax_nopriv_pcf_contact_action', array('WPEC_PCF_Hook_Filter', 'pcf_contact_action') );

	// Include script admin plugin
	if (in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'))){
		add_action('admin_footer', array('WPEC_PCF_Hook_Filter', 'admin_footer_scripts'));
	}

	// Add email button for each product
	if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')){
		require_once(ABSPATH.'wp-admin/includes/plugin.php');
		$wpec_version = get_plugin_data(WP_PLUGIN_DIR.'/wp-e-commerce/wp-shopping-cart.php');
		if(version_compare($wpec_version['Version'], '3.8.7', '<')){
			// Hide Add To Cart button
			add_action('the_post', array('WPEC_PCF_Hook_Filter', 'hide_addcartbt'));

			// Show Email Inquiry button
			add_action('the_post', array('WPEC_PCF_Hook_Filter', 'script_show_contact_button'));
		}else{
			// Hide Add To Cart button
			add_action('wpsc_product_form_fields_begin', array('WPEC_PCF_Hook_Filter', 'hide_addcartbt'));

			// Show Email Inquiry button
			add_action('wpsc_product_form_fields_begin', array('WPEC_PCF_Hook_Filter', 'script_show_contact_button'));
		}
	} else {
		// Show Email Inquiry button
		add_action('wpsc_product_form_fields_end', array('WPEC_PCF_Hook_Filter', 'add_contact_button'), 100);
	}

	// Add meta boxes to product page
	add_action( 'admin_menu', array('WPEC_PCF_MetaBox', 'add_meta_boxes') );
	if(in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'))){
		add_action('save_post', array('WPEC_PCF_MetaBox','save_meta_boxes' ) );
	}

	// Check upgrade functions
	add_action('plugins_loaded', 'wpec_pcf_upgrade_plugin');
	function wpec_pcf_upgrade_plugin () {

		if(version_compare(get_option('a3rev_wpec_pcf_lite_version'), '1.0.8') === -1){
			update_option('a3rev_wpec_pcf_lite_version', '1.0.8');
			WPEC_PCF_Functions::lite_upgrade_version_1_0_8();
		}

		if(version_compare(get_option('a3rev_wpec_pcf_lite_version'), '1.2.0') === -1){
		// Build sass
			global $wpec_pcf_less;
			$wpec_pcf_less->plugin_build_sass();
			update_option('a3rev_wpec_pcf_lite_version', '1.2.0');
		}

		update_option('a3rev_wpec_pcf_lite_version', '1.2.3');
	}

?>
