<?php
/**
 * WPEC PCF Functions
 *
 * Table Of Contents
 *
 * check_hide_add_cart_button()
 * check_add_email_inquiry_button()
 * check_add_email_inquiry_button_on_shoppage()
 * email_inquiry()
 * get_from_address()
 * get_from_name()
 * get_content_type()
 * plugin_extension()
 * wpec_ei_yellow_message_dontshow()
 * wpec_ei_yellow_message_dismiss()
 * lite_upgrade_version_1_0_8()
 */
class WPEC_PCF_Functions
{	

	public static function check_hide_add_cart_button ($product_id) {
		global $wpec_email_inquiry_rules_roles_settings;
			
		$hide_addcartbt_before_login = $wpec_email_inquiry_rules_roles_settings['hide_addcartbt_before_login'] ;
		
		// dont hide add to cart button if setting is not checked and not logged in users
		if ($hide_addcartbt_before_login == 'no' && !is_user_logged_in() ) return false;
		
		// hide add to cart button if setting is checked and not logged in users
		if ($hide_addcartbt_before_login != 'no' &&  !is_user_logged_in()) return true;
		
		$hide_addcartbt_after_login = $wpec_email_inquiry_rules_roles_settings['hide_addcartbt_after_login'] ;

		// don't hide add to cart if for logged in users is deacticated
		if ( $hide_addcartbt_after_login != 'yes' ) return false;
		
		$role_apply_hide_cart = (array) $wpec_email_inquiry_rules_roles_settings['role_apply_hide_cart'];
		
		$user_login = wp_get_current_user();
		if (is_array($user_login->roles) && count($user_login->roles) > 0) {
			$user_role = '';
			foreach ($user_login->roles as $role_name) {
				$user_role = $role_name;
				break;
			}
			// hide add to cart button if current user role in list apply role
			if ( in_array($user_role, $role_apply_hide_cart) ) return true;
		}
		return false;
		
	}
	
	public static function check_add_email_inquiry_button ($product_id) {
		global $wpec_email_inquiry_global_settings;
			
		$show_email_inquiry_button_before_login = $wpec_email_inquiry_global_settings['show_email_inquiry_button_before_login'];
		
		// dont show email inquiry button if setting is not checked and not logged in users
		if ($show_email_inquiry_button_before_login == 'no' && !is_user_logged_in() ) return false;
		
		// alway show email inquiry button if setting is checked and not logged in users
		if ($show_email_inquiry_button_before_login != 'no' && !is_user_logged_in()) return true;
		
		$show_email_inquiry_button_after_login = $wpec_email_inquiry_global_settings['show_email_inquiry_button_after_login'] ;

		// don't show email inquiry button if for logged in users is deacticated
		if ( $show_email_inquiry_button_after_login != 'yes' ) return false;
		
		$role_apply_show_inquiry_button = (array) $wpec_email_inquiry_global_settings['role_apply_show_inquiry_button'];		
		
		$user_login = wp_get_current_user();		
		if (is_array($user_login->roles) && count($user_login->roles) > 0) {
			$user_role = '';
			foreach ($user_login->roles as $role_name) {
				$user_role = $role_name;
				break;
			}
			// show email inquiry button if current user role in list apply role
			if ( in_array($user_role, $role_apply_show_inquiry_button) ) return true;
		}
		
		return false;
		
	}
	
	public static function check_add_email_inquiry_button_on_shoppage ($product_id=0) {
		global $wpec_email_inquiry_global_settings;
			
		$inquiry_single_only = $wpec_email_inquiry_global_settings['inquiry_single_only'];
		
		if ($inquiry_single_only == 'yes') return false;
		
		return WPEC_PCF_Functions::check_add_email_inquiry_button($product_id);
		
	}
	
	public static function email_inquiry($product_id, $your_name, $your_email, $your_phone, $your_message, $send_copy_yourself = 1) {
		global $wpec_email_inquiry_contact_form_settings;
		
		if ( WPEC_PCF_Functions::check_add_email_inquiry_button( $product_id ) ) {
			
			$wpec_pcf_contact_success = stripslashes( get_option( 'wpec_pcf_contact_success', '' ) );
			if ( trim( $wpec_pcf_contact_success ) != '') $wpec_pcf_contact_success = wpautop(wptexturize(   $wpec_pcf_contact_success ));
			else $wpec_pcf_contact_success = __("Thanks for your inquiry - we'll be in touch with you as soon as possible!", 'wpec_pcf');
			
			$to_email = $wpec_email_inquiry_contact_form_settings['inquiry_email_to'];
			if (trim($to_email) == '') $to_email = get_option('admin_email');
			
			$from_email = get_option('admin_email');
				
			$from_name = ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
			
			$cc_emails = $wpec_email_inquiry_contact_form_settings['inquiry_email_cc'];
			if (trim($cc_emails) == '') $cc_emails = '';
			
			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset='. get_option('blog_charset');
			$headers[] = 'From: '.$from_name.' <'.$from_email.'>';
			
			if (trim($cc_emails) != '') {
				$cc_emails_a = explode("," , $cc_emails);
				if (is_array($cc_emails_a) && count($cc_emails_a) > 0) {
					foreach ($cc_emails_a as $cc_email) {
						$headers[] = 'Cc: '.$cc_email;
					}
				} else {
					$headers[] = 'Cc: '.$cc_emails;
				}
			}
			
			$product_name = get_the_title($product_id);
			$product_url = get_permalink($product_id);
			$subject = __('Email inquiry for', 'wpec_pcf').' '.$product_name;
			
			$content = '
	<table width="99%" cellspacing="0" cellpadding="1" border="0" bgcolor="#eaeaea"><tbody>
	  <tr>
		<td>
		  <table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="#ffffff"><tbody>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Name', 'wpec_pcf').'</strong></font> 
			  </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[your_name]</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Email Address', 'wpec_pcf').'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><a target="_blank" href="mailto:[your_email]">[your_email]</a></font> 
			  </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Phone', 'wpec_pcf').'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[your_phone]</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Product Name', 'wpec_pcf').'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><a target="_blank" href="[product_url]">[product_name]</a></font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Message', 'wpec_pcf').'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[your_message]</font> 
		  </td></tr></tbody></table></td></tr></tbody></table>';
		  
			$content = str_replace('[your_name]', $your_name, $content);
			$content = str_replace('[your_email]', $your_email, $content);
			$content = str_replace('[your_phone]', $your_phone, $content);
			$content = str_replace('[product_name]', $product_name, $content);
			$content = str_replace('[product_url]', $product_url, $content);
			$your_message = str_replace( '://', ':&#173;¬¨‚â†//', $your_message );
			$your_message = str_replace( '.com', '&#173;.com', $your_message );
			$your_message = str_replace( '.net', '&#173;.net', $your_message );
			$your_message = str_replace( '.info', '&#173;.info', $your_message );
			$your_message = str_replace( '.org', '&#173;.org', $your_message );
			$your_message = str_replace( '.au', '&#173;.au', $your_message );
			$content = str_replace('[your_message]', wpautop( $your_message ), $content);
			
			$content = apply_filters('pcf_inquiry_content', $content);
			
			// Filters for the email
			add_filter( 'wp_mail_from', array( 'WPEC_PCF_Functions', 'get_from_address' ) );
			add_filter( 'wp_mail_from_name', array( 'WPEC_PCF_Functions', 'get_from_name' ) );
			add_filter( 'wp_mail_content_type', array( 'WPEC_PCF_Functions', 'get_content_type' ) );
			
			wp_mail( $to_email, $subject, $content, $headers, '' );
			
			// Unhook filters
			remove_filter( 'wp_mail_from', array( 'WPEC_PCF_Functions', 'get_from_address' ) );
			remove_filter( 'wp_mail_from_name', array( 'WPEC_PCF_Functions', 'get_from_name' ) );
			remove_filter( 'wp_mail_content_type', array( 'WPEC_PCF_Functions', 'get_content_type' ) );
			
			return $wpec_pcf_contact_success;
		} else {
			return __("Sorry, this product don't enable email inquiry.", 'wpec_pcf');
		}
	}
	
	public static function get_from_address() {
		$from_email = get_option('admin_email');
			
		return $from_email;
	}
	
	public static function get_from_name() {
		$from_name = ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
			
		return $from_name;
	}
	
	public static function get_content_type() {
		return 'text/html';
	}
	
	public static function plugin_extension() {
		$html = '';
		$html .= '<a href="http://a3rev.com/shop/" target="_blank" style="float:right;margin-top:5px; margin-left:10px;" ><div class="a3-plugin-ui-icon a3-plugin-ui-a3-rev-logo"></div></a>';
		$html .= '<h3>'.__('Upgrade to Catalog Visibility Email inquiry Pro', 'wpec_pcf').'</h3>';
		$html .= '<p>'.__("<strong>NOTE:</strong> All the functions inside the Yellow border on the plugins admin panel are extra functionality that is activated by upgrading to the Pro version", 'wpec_pcf').':</p>';
		$html .= '<p>';
		
		$html .= '<h3>* <a href="'.WPEC_PCF_AUTHOR_URI.'" target="_blank">'.__('WPEC Catalog Visibility and Email Pro', 'wpec_pcf').'</a> '.__('Features', 'wpec_pcf').':</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>1. '.__("Rule: Hide Product Prices.", 'wpec_pcf').'</li>';
		$html .= '<li>2. '.__('Email and Cart Product Page Meta.', 'wpec_pcf').'</li>';
		$html .= '<li>3. '.__('WYSIWYG Email Inquiry button creator.', 'wpec_pcf').'</li>';
		$html .= '<li>4. '.__('WYSIWYG pop-up form creator.', 'wpec_pcf').'</li>';
		$html .= '</ul>';
		$html .= '</p>';
		
		$html .= '<p>'.__('All of our plugins have comprehensive online documentation. Please refer to the plugins docs before raising a support request', 'wpec_pcf').'. <a href="http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-catalog-visibility-and-email-inquiry/" target="_blank">'.__('Visit the a3rev wiki.', 'wpec_pcf').'</a></p>';
		$html .= '<h3>'.__('More a3rev Quality Plugins', 'wpec_pcf').'</h3>';
		$html .= '<p>'.__('Below is a list of the a3rev plugins that are available for free download from wordpress.org', 'wpec_pcf').'</p>';
		$html .= '<h3>'.__('WP e-Commerce Plugins', 'wpec_pcf').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-products-quick-view/" target="_blank">'.__('WP e-Commerce Products Quick View', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-dynamic-gallery/" target="_blank">'.__('WP e-Commerce Dynamic Gallery', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-predictive-search/" target="_blank">'.__('WP e-Commerce Predictive Search', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-ecommerce-compare-products/" target="_blank">'.__('WP e-Commerce Compare Products', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-e-commerce-grid-view/" target="_blank">'.__('WP e-Commerce Grid View', 'wpec_pcf').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		
		$html .= '<h3>'.__('WordPress Plugins', 'wpec_pcf').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/plugins/a3-responsive-slider/" target="_blank">'.__('a3 Responsive Slider', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/contact-us-page-contact-people/" target="_blank">'.__('Contact Us Page - Contact People', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-email-template/" target="_blank">'.__('WordPress Email Template', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/page-views-count/" target="_blank">'.__('Page View Count', 'wpec_pcf').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		return $html;
	}
	
	public static function wpec_ei_yellow_message_dontshow() {
		check_ajax_referer( 'wpec_ei_yellow_message_dontshow', 'security' );
		$option_name   = $_REQUEST['option_name'];
		update_option( $option_name, 1 );
		die();
	}
	
	public static function wpec_ei_yellow_message_dismiss() {
		check_ajax_referer( 'wpec_ei_yellow_message_dismiss', 'security' );
		$session_name   = $_REQUEST['session_name'];
		if ( !isset($_SESSION) ) { @session_start(); } 
		$_SESSION[$session_name] = 1 ;
		die();
	}
	
	public static function lite_upgrade_version_1_0_8() {
		$wpec_pcf_hide_addcartbt = get_option( 'wpec_pcf_hide_addcartbt', 1 );
		$wpec_email_inquiry_rules_roles_settings = array(
			'hide_addcartbt_before_login'	=> ( $wpec_pcf_hide_addcartbt == 1 ) ? 'yes' : 'no',
			'hide_addcartbt_after_login'	=> 'yes',
		);	
		update_option( 'wpec_email_inquiry_rules_roles_settings', $wpec_email_inquiry_rules_roles_settings );
		
		$wpec_pcf_show_button = get_option( 'wpec_pcf_show_button', 1 );
		$wpec_pcf_user = get_option( 'wpec_pcf_user', 0 );
		$wpec_email_inquiry_global_settings = array(
			'show_email_inquiry_button_before_login'	=> ( $wpec_pcf_show_button == 1 ) ? 'yes' : 'no',
			'show_email_inquiry_button_after_login'		=> ( $wpec_pcf_user == 1 ) ? 'yes' : 'no',
			'inquiry_single_only'						=> 'no',
		);	
		update_option( 'wpec_email_inquiry_global_settings', $wpec_email_inquiry_global_settings );
		
	}
	
}
?>