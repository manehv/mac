<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
WPEC EI Global Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class WPEC_EI_Global_Settings extends WPEC_Email_Inquiry_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'settings';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'wpec_email_inquiry_global_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'wpec_email_inquiry_global_settings';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 1;
	
	/**
	 * @var array
	 */
	public $form_fields = array();
	
	/**
	 * @var array
	 */
	public $form_messages = array();
	
	public function custom_types() {
		$custom_type = array( 'hide_inquiry_button_yellow_message' );
		
		return $custom_type;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		// add custom type
		foreach ( $this->custom_types() as $custom_type ) {
			add_action( $this->plugin_name . '_admin_field_' . $custom_type, array( $this, $custom_type ) );
		}
		
		$this->init_form_fields();
		//$this->subtab_init();
		
		$this->form_messages = array(
				'success_message'	=> __( 'Email Inquiry Settings successfully saved.', 'wpec_pcf' ),
				'error_message'		=> __( 'Error: Email Inquiry Settings can not save.', 'wpec_pcf' ),
				'reset_message'		=> __( 'Email Inquiry Settings successfully reseted.', 'wpec_pcf' ),
			);
			
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
			
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'after_save_settings' ), 11 );
				
		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {
		
		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
		global $wpec_ei_admin_interface;
		
		$wpec_ei_admin_interface->reset_settings( $this->form_fields, $this->option_name, false );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* reset_default_settings()
	/* Reset default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function reset_default_settings() {
		global $wpec_ei_admin_interface;
		
		$wpec_ei_admin_interface->reset_settings( $this->form_fields, $this->option_name, true, true );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* after_save_settings()
	/* Process when clean on deletion option is un selected */
	/*-----------------------------------------------------------------------------------*/
	public function after_save_settings() {
		if ( ( isset( $_POST['bt_save_settings'] ) || isset( $_POST['bt_reset_settings'] ) ) && get_option( 'wpec_email_inquiry_lite_clean_on_deletion' ) == 0  )  {
			$uninstallable_plugins = (array) get_option('uninstall_plugins');
			unset($uninstallable_plugins[WPEC_PCF_NAME]);
			update_option('uninstall_plugins', $uninstallable_plugins);
		}
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		global $wpec_ei_admin_interface;
		
		$wpec_ei_admin_interface->get_settings( $this->form_fields, $this->option_name );
	}
	
	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array ( 
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {
		
		$subtab_data = array( 
			'name'				=> 'global-settings',
			'label'				=> __( 'Settings', 'wpec_pcf' ),
			'callback_function'	=> 'wpec_ei_global_settings_form',
		);
		
		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {
	
		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();
		
		return $subtabs_array;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
		global $wpec_ei_admin_interface;
		
		$output = '';
		$output .= $wpec_ei_admin_interface->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );
		
		return $output;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {
		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$roles = $wp_roles->get_names();
		
  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
		
			array(
            	'name' 		=> __( 'Trouble Shooting', 'wpec_pcf' ),
                'type' 		=> 'heading',
				'class'		=> 'trouble_shooting_container',
           	),
			array(  
				'name' 		=> __( "WPEC Theme Compatibility", 'wpec_pcf' ),
				'class'		=> 'rules_roles_explanation',
				'id' 		=> 'rules_roles_explanation',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'show',
				'free_version'		=> true,
				'checked_value'		=> 'show',
				'unchecked_value' 	=> 'hide',
				'checked_label'		=> __( 'SHOW', 'wpec_pcf' ),
				'unchecked_label' 	=> __( 'HIDE', 'wpec_pcf' ),
			),
			array(
				'desc'		=> '<table class="form-table"><tbody><tr valign="top"><td class="titledesc" scope="row" colspan="2" ><div>'.__( "The Email Inquiry Button / Hyperlink text will not work if the bespoke theme you are using removes or replaces (with a custom function) 2 core WP e-Commerce functions and does not use the WP e-Commerce template structure and hierarchy. The 2 core functions required are:", 'wpec_pcf' ).'</div><p>'.esc_attr("do_action('wpsc_product_form_fields_begin')").'</p><p>'.esc_attr("do_action('wpsc_product_form_fields_end')").'</p></td></tr><tr valign="top"><td class="titledesc" scope="row" colspan="2" ><div>'.__( "<strong>Note:</strong> The Email Inquiry Button shows just fine in the Default WordPress theme. You will only have an issue where a theme dev has made customized WP e-Commerce templates and functions and not followed the WP e-Commerce Codex. If this is the case you should change themes.", 'wpec_pcf' ).'</div></td></tr></tbody></table>',
                'type' 		=> 'heading',
				'class'		=> 'rules_roles_explanation_container',
           	),
		
			array(
            	'name' 		=> __( "Product Page Rule: Show Email Inquiry Button", 'wpec_pcf' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( "Apply for all users before log in", 'wpec_pcf' ),
				'class'		=> 'show_email_inquiry_button_before_login',
				'id' 		=> 'show_email_inquiry_button_before_login',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'free_version'		=> true,
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'wpec_pcf' ),
				'unchecked_label' 	=> __( 'OFF', 'wpec_pcf' ),
			),
			array(  
				'name' 		=> __( "Apply by user role after log in", 'wpec_pcf' ),
				'class'		=> 'show_email_inquiry_button_after_login',
				'id' 		=> 'show_email_inquiry_button_after_login',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'free_version'		=> true,
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'wpec_pcf' ),
				'unchecked_label' 	=> __( 'OFF', 'wpec_pcf' ),
			),
			
			array(
				'class'		=> 'show_email_inquiry_button_after_login_container',
                'type' 		=> 'heading',
           	),
			array(  
				'desc' 		=> '',
				'id' 		=> 'role_apply_show_inquiry_button',
				'type' 		=> 'multiselect',
				'placeholder' => __( 'Choose Roles', 'wpec_pcf' ),
				'css'		=> 'width:450px; min-height:80px; max-width:100%;',
				'options'	=> $roles,
				'free_version'		=> true,
			),
			array(
                'type' 		=> 'heading',
				'class'		=> 'yellow_message_container hide_inquiry_button_yellow_message_container',
           	),
			array(
                'type' 		=> 'hide_inquiry_button_yellow_message',
           	),
			
			array(
				'name'		=> __( 'Product Cards', 'wpec_pcf' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Email Inquiry Feature', 'wpec_pcf' ),
				'desc'		=> __( "ON to show Button / Link Text on Product Cards (grid view) display.", 'wpec_pcf' ),
				'id' 		=> 'inquiry_single_only',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'free_version'		=> true,
				'checked_value'		=> 'no',
				'unchecked_value'	=> 'yes',
				'checked_label' 	=> __( 'ON', 'wpec_pcf' ),
				'unchecked_label'	=> __( 'OFF', 'wpec_pcf' ),
			),
			
			array(
				'name'		=> __( 'Product Page Rules Reset:', 'wpec_pcf' ),
                'type' 		=> 'heading',
				'class'		=> 'pro_feature_fields',
           	),
			array(  
				'name' 		=> __( "Reset All Products", 'wpec_pcf' ),
				'desc' 		=> __( "<strong>Warning:</strong> Set to Yes and Save Changes will reset ALL custom Product Page and Product Card Rules and Roles on ALL products back to the admin panels Global settings.", 'wpec_pcf' ),
				'id' 		=> 'wpec_email_inquiry_reset_products_options',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'separate_option'	=> true,
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'wpec_pcf' ),
				'unchecked_label' 	=> __( 'OFF', 'wpec_pcf' ),
			),
			
			array(
            	'name' => __( 'House Keeping :', 'wpec_pcf' ),
                'type' => 'heading',
           	),
			array(  
				'name' 		=> __( 'Clean up on Deletion', 'wpec_pcf' ),
				'desc' 		=> __( "On deletion (not deactivate) the plugin will completely remove all tables and data it created, leaving no trace it was ever here.", 'wpec_pcf' ),
				'id' 		=> 'wpec_email_inquiry_lite_clean_on_deletion',
				'type' 		=> 'onoff_checkbox',
				'default'	=> '1',
				'free_version'		=> true,
				'separate_option'	=> true,
				'checked_value'		=> '1',
				'unchecked_value'	=> '0',
				'checked_label'		=> __( 'ON', 'wpec_pcf' ),
				'unchecked_label' 	=> __( 'OFF', 'wpec_pcf' ),
			),
			
        ));
	}
		
	public function hide_inquiry_button_yellow_message( $value ) {
		$customized_settings = get_option( $this->option_name, array() );
	?>
    	<tr valign="top" class="hide_inquiry_button_yellow_message_tr" style=" ">
			<th scope="row" class="titledesc">&nbsp;</th>
			<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
            <?php 
				$hide_inquiry_button_blue_message = '<div><strong>'.__( 'Note', 'wpec_pcf' ).':</strong> '.__( "If you do not apply the Show Email Inquiry Button Rule to your role i.e. 'administrator' you will need to either log out or open the site in another browser where you are not logged in to see the Email Inquiry Button feature is activated.", 'wpec_pcf' ).'</div>
                <div style="clear:both"></div>
                <a class="hide_inquiry_button_yellow_message_dontshow" style="float:left;" href="javascript:void(0);">'.__( "Don't show again", 'wpec_pcf' ).'</a>
                <a class="hide_inquiry_button_yellow_message_dismiss" style="float:right;" href="javascript:void(0);">'.__( "Dismiss", 'wpec_pcf' ).'</a>
                <div style="clear:both"></div>';
            	echo $this->blue_message_box( $hide_inquiry_button_blue_message, '450px' ); 
			?>
<style>
.a3rev_panel_container .hide_inquiry_button_yellow_message_container {
<?php if ( $customized_settings['show_email_inquiry_button_before_login'] == 'no' && $customized_settings['show_email_inquiry_button_after_login'] == 'no' ) echo 'display: none;'; ?>
<?php if ( get_option( 'wpec_ei_hide_inquiry_button_message_dontshow', 0 ) == 1 ) echo 'display: none !important;'; ?>
<?php if ( !isset($_SESSION) ) { @session_start(); } if ( isset( $_SESSION['wpec_ei_hide_inquiry_button_message_dismiss'] ) ) echo 'display: none !important;'; ?>
}
</style>
<script>
(function($) {
$(document).ready(function() {
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.show_email_inquiry_button_after_login', function( event, value, status ) {
		if ( status == 'true' ) {
			$(".hide_inquiry_button_yellow_message_container").slideDown();
		} else if( $("input.show_email_inquiry_button_before_login").prop( "checked" ) == false ) {
			$(".hide_inquiry_button_yellow_message_container").slideUp();
		}
	});
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.show_email_inquiry_button_before_login', function( event, value, status ) {
		if ( status == 'true' ) {
			$(".hide_inquiry_button_yellow_message_container").slideDown();
		} else if( $("input.show_email_inquiry_button_after_login").prop( "checked" ) == false ) {
			$(".hide_inquiry_button_yellow_message_container").slideUp();
		}
	});
	
	$(document).on( "click", ".hide_inquiry_button_yellow_message_dontshow", function(){
		$(".hide_inquiry_button_yellow_message_tr").slideUp();
		$(".hide_inquiry_button_yellow_message_container").slideUp();
		var data = {
				action: 		"wpec_ei_yellow_message_dontshow",
				option_name: 	"wpec_ei_hide_inquiry_button_message_dontshow",
				security: 		"<?php echo wp_create_nonce("wpec_ei_yellow_message_dontshow"); ?>"
			};
		$.post( "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>", data);
	});
	
	$(document).on( "click", ".hide_inquiry_button_yellow_message_dismiss", function(){
		$(".hide_inquiry_button_yellow_message_tr").slideUp();
		$(".hide_inquiry_button_yellow_message_container").slideUp();
		var data = {
				action: 		"wpec_ei_yellow_message_dismiss",
				session_name: 	"wpec_ei_hide_inquiry_button_message_dismiss",
				security: 		"<?php echo wp_create_nonce("wpec_ei_yellow_message_dismiss"); ?>"
			};
		$.post( "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>", data);
	});
});
})(jQuery);
</script>
			</td>
		</tr>
    <?php
	
	}
		
	public function include_script() {
	?>
<style>
.yellow_message_container {
	margin-top: -15px;	
}
.yellow_message_container a {
	text-decoration:none;	
}
.yellow_message_container th, .yellow_message_container td, .show_email_inquiry_button_after_login_container th, .show_email_inquiry_button_after_login_container td {
	padding-top: 0 !important;
	padding-bottom: 0 !important;
}
</style>
<script>
(function($) {
	
	$(document).ready(function() {
		if ( $("input.rules_roles_explanation").is(':checked') == false ) {
			$(".rules_roles_explanation_container").hide();
		}
		
		if ( $("input.show_email_inquiry_button_after_login:checked").val() == 'yes') {
			$('.show_email_inquiry_button_after_login_container').css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		} else {
			$('.show_email_inquiry_button_after_login_container').css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
		}
		
		$(document).on( "a3rev-ui-onoff_checkbox-switch", '.rules_roles_explanation', function( event, value, status ) {
			if ( status == 'true' ) {
				$(".rules_roles_explanation_container").slideDown();
			} else {
				$(".rules_roles_explanation_container").slideUp();
			}
		});
			
		$(document).on( "a3rev-ui-onoff_checkbox-switch", '.show_email_inquiry_button_after_login', function( event, value, status ) {
			$('.show_email_inquiry_button_after_login_container').hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
			if ( status == 'true' ) {
				$(".show_email_inquiry_button_after_login_container").slideDown();
			} else {
				$(".show_email_inquiry_button_after_login_container").slideUp();
			}
		});
		
	});
	
})(jQuery);
</script>
    <?php	
	}
}

global $wpec_ei_global_settings;
$wpec_ei_global_settings = new WPEC_EI_Global_Settings();

/** 
 * wpec_ei_global_settings_form()
 * Define the callback function to show subtab content
 */
function wpec_ei_global_settings_form() {
	global $wpec_ei_global_settings;
	$wpec_ei_global_settings->settings_form();
}

?>
