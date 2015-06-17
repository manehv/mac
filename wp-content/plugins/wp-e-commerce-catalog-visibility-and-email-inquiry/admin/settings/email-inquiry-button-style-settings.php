<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
WPEC EI Button Style Settings

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

class WPEC_EI_Button_Style_Settings extends WPEC_Email_Inquiry_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'button-style';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'wpec_email_inquiry_customize_email_button';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'wpec_email_inquiry_customize_email_button';
	
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
		$custom_type = array( 'button_hyperlink_margin_blue_message' );
		
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
				'success_message'	=> __( 'Button Style Settings successfully saved.', 'wpec_pcf' ),
				'error_message'		=> __( 'Error: Button Style Settings can not save.', 'wpec_pcf' ),
				'reset_message'		=> __( 'Button Style Settings successfully reseted.', 'wpec_pcf' ),
			);
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
		
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );
				
		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
				
		// Add yellow border for pro fields
		add_action( $this->plugin_name . '_settings_pro_email_inquiry_button_hyperlink_start_before', array( $this, 'pro_fields_before' ) );
		add_action( $this->plugin_name . '_settings_pro_email_inquiry_button_hyperlink_end_after', array( $this, 'pro_fields_after' ) );
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
			'name'				=> 'button-style',
			'label'				=> __( 'Button Style', 'wpec_pcf' ),
			'callback_function'	=> 'wpec_ei_button_style_settings_form',
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
		
  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
		
			array(
            	'name' => __( 'Email Inquiry Button / Hyperlink', 'wpec_pcf' ),
                'type' => 'heading',
           	),
			array(  
				'name' 		=> __( 'Button or Hyperlink Text', 'wpec_pcf' ),
				'id' 		=> 'inquiry_button_type',
				'class' 	=> 'inquiry_button_type',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'button',
				'free_version'		=> true,
				'checked_value'		=> 'button',
				'unchecked_value'	=> 'link',
				'checked_label' 	=> __( 'Button', 'wpec_pcf' ),
				'unchecked_label'	=> __( 'Hyperlink', 'wpec_pcf' ),
			),
			array(  
				'name' 		=> __( 'Relative Position', 'wpec_pcf' ),
				'desc'		=> __( 'Position relative to add to cart button location', 'wpec_pcf' ),
				'id' 		=> 'inquiry_button_position',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'below',
				'free_version'		=> true,
				'checked_value'		=> 'below',
				'unchecked_value'	=> 'above',
				'checked_label' 	=> __( 'Below', 'wpec_pcf' ),
				'unchecked_label'	=> __( 'Above', 'wpec_pcf' ),
			),
			array(  
				'name' 		=> __( 'Button or Hyperlink Margin', 'wpec_pcf' ),
				'id' 		=> 'inquiry_button_margin',
				'type' 		=> 'array_textfields',
				'free_version'		=> true,
				'ids'		=> array( 
	 								array( 
											'id' 		=> 'inquiry_button_margin_top',
	 										'name' 		=> __( 'Top', 'wpec_pcf' ),
	 										'css'		=> 'width:40px;',
											'free_version'		=> true,
	 										'default'	=> 5 ),
	 
	 								array(  'id' 		=> 'inquiry_button_margin_bottom',
	 										'name' 		=> __( 'Bottom', 'wpec_pcf' ),
	 										'css'		=> 'width:40px;',
											'free_version'		=> true,
	 										'default'	=> 5 ),
											
									array( 
											'id' 		=> 'inquiry_button_margin_left',
	 										'name' 		=> __( 'Left', 'wpec_pcf' ),
	 										'css'		=> 'width:40px;',
											'free_version'		=> true,
	 										'default'	=> 0 ),
											
									array( 
											'id' 		=> 'inquiry_button_margin_right',
	 										'name' 		=> __( 'Right', 'wpec_pcf' ),
	 										'css'		=> 'width:40px;',
											'free_version'		=> true,
	 										'default'	=> 0 ),
	 							)
			),
			array(
                'type' 		=> 'heading',
				'class'		=> 'blue_message_container button_hyperlink_margin_blue_message_container',
           	),
			array(
                'type' 		=> 'button_hyperlink_margin_blue_message',
           	),
			
			array(
                'type' 		=> 'heading',
          		'id' 		=> 'pro_email_inquiry_button_hyperlink_start'
           	),
			
			array(
            	'name' 		=> __( 'Email Inquiry Button Style', 'wpec_pcf' ),
                'type' 		=> 'heading',
          		'class' 	=> 'email_inquiry_button_type_container'
           	),
			array(  
				'name' 		=> __( 'Button Title', 'wpec_pcf' ),
				'desc' 		=> __( "&lt;empty&gt; for default 'Product Enquiry' or enter text", 'wpec_pcf' ),
				'id' 		=> 'inquiry_button_title',
				'type' 		=> 'text',
				'default'	=> __( 'Product Enquiry', 'wpec_pcf' )
			),
			array(  
				'name' 		=> __( 'Button Padding', 'wpec_pcf' ),
				'desc' 		=> __( 'Padding from Button text to Button border', 'wpec_pcf' ),
				'id' 		=> 'inquiry_button_padding',
				'type' 		=> 'array_textfields',
				'ids'		=> array( 
	 								array(  'id' 		=> 'inquiry_button_padding_tb',
	 										'name' 		=> __( 'Top/Bottom', 'wpec_pcf' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 7 ),
	 
	 								array(  'id' 		=> 'inquiry_button_padding_lr',
	 										'name' 		=> __( 'Left/Right', 'wpec_pcf' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 8 ),
	 							)
			),
			array(  
				'name' 		=> __( 'Background Colour', 'wpec_pcf' ),
				'desc' 		=> __( 'Default', 'wpec_pcf' ) . ' [default_value]',
				'id' 		=> 'inquiry_button_bg_colour',
				'type' 		=> 'color',
				'default'	=> '#EE2B2B'
			),
			array(  
				'name' 		=> __( 'Background Colour Gradient From', 'wpec_pcf' ),
				'desc' 		=> __( 'Default', 'wpec_pcf' ) . ' [default_value]',
				'id' 		=> 'inquiry_button_bg_colour_from',
				'type' 		=> 'color',
				'default'	=> '#FBCACA'
			),
			array(  
				'name' 		=> __( 'Background Colour Gradient To', 'wpec_pcf' ),
				'desc' 		=> __( 'Default', 'wpec_pcf' ) . ' [default_value]',
				'id' 		=> 'inquiry_button_bg_colour_to',
				'type' 		=> 'color',
				'default'	=> '#EE2B2B'
			),
			array(  
				'name' 		=> __( 'Button Border', 'wpec_pcf' ),
				'id' 		=> 'inquiry_button_border',
				'type' 		=> 'border',
				'default'	=> array( 'width' => '1px', 'style' => 'solid', 'color' => '#EE2B2B', 'corner' => 'rounded' , 'rounded_value' => 3 ),
			),
			array(  
				'name' 		=> __( 'Button Font', 'wpec_pcf' ),
				'id' 		=> 'inquiry_button_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '12px', 'face' => 'Arial, sans-serif', 'style' => 'bold', 'color' => '#FFFFFF' )
			),
			array(  
				'name' => __( 'Button Shadow', 'wpec_pcf' ),
				'id' 		=> 'inquiry_button_shadow',
				'type' 		=> 'box_shadow',
				'default'	=> array( 'enable' => 0, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#999999', 'inset' => '' )
			),
			array(  
				'name' 		=> __( 'CSS Class', 'wpec_pcf' ),
				'desc' 		=> __( 'Enter your own button CSS class', 'wpec_pcf' ),
				'id' 		=> 'inquiry_button_class',
				'type' 		=> 'text',
				'default'	=> ''
			),
			
			array(
            	'name' 		=> __( 'Hyperlink Styling', 'wpec_pcf' ),
                'type' 		=> 'heading',
          		'class'		=> 'email_inquiry_hyperlink_type_container'
           	),
			array(  
				'name' 		=> __( 'Text Before', 'wpec_pcf' ),
				'desc' 		=> __( "&lt;empty&gt; for default 'no text' or add text to prepent link text", 'wpec_pcf' ),
				'id' 		=> 'inquiry_text_before',
				'type' 		=> 'text',
				'default'	=> '',
			),
			array(  
				'name' 		=> __( 'Hyperlink Text', 'wpec_pcf' ),
				'desc' 		=> __( "&lt;empty&gt; for default 'Click Here' or your own text", 'wpec_pcf' ),
				'id' 		=> 'inquiry_hyperlink_text',
				'type' 		=> 'text',
				'default'	=> __( 'Click Here', 'wpec_pcf' )
			),
			array(  
				'name' 		=> __( 'Trailing Text', 'wpec_pcf' ),
				'desc' 		=> __( "&lt;empty&gt; for default 'no text' or add text to trail linked text", 'wpec_pcf' ),
				'id' 		=> 'inquiry_trailing_text',
				'type' 		=> 'text',
				'default'	=> '',
			),
			array(  
				'name' 		=> __( 'Hyperlink Font', 'wpec_pcf' ),
				'id' 		=> 'inquiry_hyperlink_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '12px', 'face' => 'Arial, sans-serif', 'style' => 'bold', 'color' => '#000000' )
			),
			
			array(  
				'name' 		=> __( 'Hyperlink hover Colour', 'wpec_pcf' ),
				'id' 		=> 'inquiry_hyperlink_hover_color',
				'type' 		=> 'color',
				'default'	=> '#999999'
			),
			
			array(
            	'name' 		=> __( 'Reset Products Button Style', 'wpec_pcf' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Global Re-Set', 'wpec_pcf' ),
				'desc' 		=> __( "Set to Yes and Save Changes will reset ALL products that have custom Button or Hyperlink Text settings to the settings made above.", 'wpec_pcf' ),
				'id' 		=> 'wpec_email_inquiry_reset_products_button_style_options',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'separate_option'	=> true,
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'YES', 'wpec_pcf' ),
				'unchecked_label' 	=> __( 'NO', 'wpec_pcf' ),
			),
			
			array(
                'type' 		=> 'heading',
          		'id' 		=> 'pro_email_inquiry_button_hyperlink_end'
           	),
			
        ));
	}
	
	public function button_hyperlink_margin_blue_message( $value ) {
	?>
    	<tr valign="top" class="button_hyperlink_margin_blue_message_tr" style=" ">
			<th scope="row" class="titledesc">&nbsp;</th>
			<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
            <?php 
				$button_hyperlink_margin_blue_message = '<div><strong>'.__( 'Tip', 'wpec_pcf' ).':</strong> '.__( 'If you see margin between the add to cart button and the email button before adding a value here that margin is added by your theme. Increasing the margin here will add to the themes default button margin.', 'wpec_pcf' ).'</div>
				<div style="clear:both"></div>
                <a class="button_hyperlink_margin_blue_message_dontshow" style="float:left;" href="javascript:void(0);">'.__( "Don't show again", 'wpec_pcf' ).'</a>
                <a class="button_hyperlink_margin_blue_message_dismiss" style="float:right;" href="javascript:void(0);">'.__( "Dismiss", 'wpec_pcf' ).'</a>
                <div style="clear:both"></div>';
            	echo $this->blue_message_box( $button_hyperlink_margin_blue_message, '450px' ); 
			?>
<style>
.a3rev_panel_container .button_hyperlink_margin_blue_message_container {
<?php if ( get_option( 'wpec_ei_button_hyperlink_margin_message_dontshow', 0 ) == 1 ) echo 'display: none !important;'; ?>
<?php if ( !isset($_SESSION) ) { @session_start(); } if ( isset( $_SESSION['wpec_ei_button_hyperlink_margin_message_dismiss'] ) ) echo 'display: none !important;'; ?>
}
</style>
<script>
(function($) {
$(document).ready(function() {
	
	$(document).on( "click", ".button_hyperlink_margin_blue_message_dontshow", function(){
		$(".button_hyperlink_margin_blue_message_tr").slideUp();
		$(".button_hyperlink_margin_blue_message_container").slideUp();
		var data = {
				action: 		"wpec_ei_yellow_message_dontshow",
				option_name: 	"wpec_ei_button_hyperlink_margin_message_dontshow",
				security: 		"<?php echo wp_create_nonce("wpec_ei_yellow_message_dontshow"); ?>"
			};
		$.post( "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>", data);
	});
	
	$(document).on( "click", ".button_hyperlink_margin_blue_message_dismiss", function(){
		$(".button_hyperlink_margin_blue_message_tr").slideUp();
		$(".button_hyperlink_margin_blue_message_container").slideUp();
		var data = {
				action: 		"wpec_ei_yellow_message_dismiss",
				session_name: 	"wpec_ei_button_hyperlink_margin_message_dismiss",
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
.blue_message_container {
	margin-top: -15px;	
}
.blue_message_container a {
	text-decoration:none;	
}
.blue_message_container th, .blue_message_container td {
	padding-top: 0 !important;
	padding-bottom: 0 !important;
}
</style>
<script>
(function($) {
$(document).ready(function() {
	if ( $("input.inquiry_button_type:checked").val() == 'button') {
		$(".email_inquiry_button_type_container").css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		$(".email_inquiry_hyperlink_type_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
	} else {
		$(".email_inquiry_button_type_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
		$(".email_inquiry_hyperlink_type_container").css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
	}
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.inquiry_button_type', function( event, value, status ) {
		$(".email_inquiry_button_type_container").hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		$(".email_inquiry_hyperlink_type_container").hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		if ( status == 'true') {
			$(".email_inquiry_button_type_container").slideDown();
			$(".email_inquiry_hyperlink_type_container").slideUp();
		} else {
			$(".email_inquiry_button_type_container").slideUp();
			$(".email_inquiry_hyperlink_type_container").slideDown();
		}
	});
});
})(jQuery);
</script>
    <?php	
	}
}

global $wpec_ei_button_style_settings;
$wpec_ei_button_style_settings = new WPEC_EI_Button_Style_Settings();

/** 
 * wpec_ei_button_style_settings_form()
 * Define the callback function to show subtab content
 */
function wpec_ei_button_style_settings_form() {
	global $wpec_ei_button_style_settings;
	$wpec_ei_button_style_settings->settings_form();
}

?>
