<?php
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/
/**
 * @package ARForms
 */


class maincontroller{


    function maincontroller(){
	

        add_action('admin_menu', array( &$this, 'menu' ));

        add_action('admin_head', array(&$this, 'menu_css'));

        add_filter('plugin_action_links_arforms/arforms.php', array( &$this, 'settings_link'), 10, 2 );

		add_action('init', array(&$this, 'front_head'));
		
		add_action('wp_head', array(&$this, 'front_head_js'), 1, 0);

        add_action('wp_footer', array(&$this, 'footer_js'), 1, 0);

        add_action('admin_init', array( &$this, 'admin_js'), 11);
		
		add_action('admin_enqueue_scripts', array( &$this, 'set_js'), 11);
		
		add_action('admin_enqueue_scripts', array( &$this, 'set_css'), 11);
		
        register_activation_hook(FORMPATH.'/arforms.php', array( &$this, 'install' ));
		
        add_action('init', array(&$this, 'parse_standalone_request'));

        add_action('init', array(&$this, 'referer_session'), 1);

        add_shortcode('ARForms', array(&$this, 'get_form_shortcode'));
		
		add_filter( 'widget_text', array(&$this, 'widget_text_filter'), 9 );

		add_shortcode('ARForms_popup', array(&$this, 'get_form_shortcode_popup'));
		
        add_filter( 'widget_text', array(&$this, 'widget_text_filter_popup'), 9 );
		
		add_action('init', array(&$this, 'wp_ar_autoupdate'));
		
		add_action('arfstandaloneroute', array(&$this, 'globalstandalone_route'), 10, 2);
		
		add_filter('upgrader_pre_install', array(&$this, 'arf_backup'), 10, 2);
		
		add_action('admin_init', array( &$this, 'upgrade_data'));
		
		add_action('admin_init', array( &$this, 'arfafterinstall'));
		
		add_action('init', array( &$this, 'arfafterinstall_front'));
		
		add_action('admin_init', array( &$this, 'arf_db_check'));
		
		add_filter('the_content', array( &$this, 'arf_modify_the_content'), 10000 );
		
		add_filter('widget_text', array( &$this, 'arf_modify_the_content'), 10000 );
		
		add_action( 'admin_head',  array($this, 'arf_hide_update_notice_to_all_admin_users'), 10000 );
		
		add_action('init',array(&$this,'arf_export_form_data') );
    }
	
	function arf_modify_the_content( $content )
	{	
		$regex 		= '/<arfsubmit>(.*?)<\/arfsubmit>/is';		
    	$content	= preg_replace_callback( $regex, array(&$this, 'arf_the_content_remove_ptag'), $content );
		
		$regex 		= '/<arffile>(.*?)<\/arffile>/is';
		$content	= preg_replace_callback( $regex, array(&$this, 'arf_the_content_remove_ptag'), $content );
		
		$regex 		= '/<arfpassword>(.*?)<\/arfpassword>/is';
		$content	= preg_replace_callback( $regex, array(&$this, 'arf_the_content_remove_ptag'), $content );
		
		$content = preg_replace("/<arfsubmit>|<\/arfsubmit>|<arffile>|<\/arffile>|<arfpassword>|<\/arfpassword>/is", '', $content );
			
		return $content; 
	}
	
	function arf_the_content_remove_ptag( $match )
	{
		$content = $match[1];
			
		$content = preg_replace( '|<p>|', '', $content );
		 
		$content = preg_replace( '|</p>|', '', $content );
		
		$content = preg_replace( '|<br />|', '', $content );
		 
		return $content;
	}
	
	function arf_the_content_removeptag( $matches )
	{
		return $matches[1]; 
	}
	
	function arf_the_content_removeemptyptag( $matches )
	{
		return $matches[1]; 
	}
	
	function arfafterinstall()
	{	
			global $arfsettings;
			$arfsettings = get_transient('arf_options');
			
			if(!is_object($arfsettings))
			{
				if($arfsettings){ 
					$arfsettings = unserialize(serialize($arfsettings));
				}else{
					$arfsettings = get_option('arf_options');
			
			
					if(!is_object($arfsettings)){
						if($arfsettings) 
							$arfsettings = unserialize(serialize($arfsettings));
						else
							$arfsettings = new arsettingmodel();
						update_option('arf_options', $arfsettings);
						set_transient('arf_options', $arfsettings);
					}
				}
			}
			
			$arfsettings->set_default_options();
			
			
			
			global $style_settings;
			
			$style_settings = get_transient('arfa_options');
			if(!is_object($style_settings))
			{
				if($style_settings){ 
					$style_settings = unserialize(serialize($style_settings));
				}else{
					$style_settings = get_option('arfa_options');
					if(!is_object($style_settings)){
						if($style_settings) 
							$style_settings = unserialize(serialize($style_settings));
						else
							$style_settings = new arstylemodel();
						update_option('arfa_options', $style_settings);
						set_transient('arfa_options', $style_settings);
					}
				}
			}
			
			$style_settings = get_option('arfa_options');
			if(!is_object($style_settings))
			{
				if($style_settings) 
					$style_settings = unserialize(serialize($style_settings));
				else
					$style_settings = new arstylemodel();
				update_option('arfa_options', $style_settings);
			}
		
			$style_settings->set_default_options();
			
			if(!is_admin() and $arfsettings->jquery_css)
				$arfdatepickerloaded = true;
				
			global $arfadvanceerrcolor;
			
			$arfadvanceerrcolor = array('white'=>'#e9e9e9|#000000|#e9e9e9','black'=>'#000000|#FFFFFF|#000000', 'darkred'=>'#ed4040|#FFFFFF|#ed4040','blue'=>'#D9EDF7|#31708F|#0561bf','pink'=>'#F2DEDE|#A94442|#508b27','yellow'=>'#FAEBCC|#8A6D3B|#af7a0c','red'=>'#EF8A80|#FFFFFF|#1393c3','green'=>'#6CCAC9|#FFFFFF|#7a37ac', 'color1' => '#6cca7b|#FFFFFF|#fb9900', 'color2' => '#c2b079|#FFFFFF|#ed40ae', 'color3' => '#f3b431|#FFFFFF|#ff6600', 'color4' => '#6d91d3|#FFFFFF|#0bb7b5', 'color5' => '#a466cc|#FFFFFF|#a79902');	
			
			global $arfdefaulttemplate;
			$arfdefaulttemplate = array(
									'3'	=> __('Contact us','ARForms'),
									'1'	=> __('Subscription Form','ARForms'),
									'5'	=> __('Feedback Form','ARForms'),									
									'6'	=> __('RSVP Form','ARForms'),
									'2'	=> __('Registration Form','ARForms'),	
									'4'	=> __('Survey Form','ARForms'),
									'7' => __('Job Application','ARForms'),
									);
									
			global $arfmsgtounlicop;
					$arfmsgtounlicop = "(";
					$arfmsgtounlicop .= "Un";
					$arfmsgtounlicop .= "lic";
					$arfmsgtounlicop .= "ens";
					$arfmsgtounlicop .= "ed";
					$arfmsgtounlicop .= ")";
	}
	
	function arfafterinstall_front()
	{
		if (!is_admin()) {
			global $arfsettings;
			$arfsettings = get_transient('arf_options');
			
			if(!is_object($arfsettings))
			{
				if($arfsettings){ 
					$arfsettings = unserialize(serialize($arfsettings));
				}else{
					$arfsettings = get_option('arf_options');
			
			
					if(!is_object($arfsettings)){
						if($arfsettings) 
							$arfsettings = unserialize(serialize($arfsettings));
						else
							$arfsettings = new arsettingmodel();
						update_option('arf_options', $arfsettings);
						set_transient('arf_options', $arfsettings);
					}
				}
			}
			
			$arfsettings->set_default_options();
			
			
			
			global $style_settings;
			
			$style_settings = get_transient('arfa_options');
			if(!is_object($style_settings))
			{
				if($style_settings){ 
					$style_settings = unserialize(serialize($style_settings));
				}else{
					$style_settings = get_option('arfa_options');
					if(!is_object($style_settings)){
						if($style_settings) 
							$style_settings = unserialize(serialize($style_settings));
						else
							$style_settings = new arstylemodel();
						update_option('arfa_options', $style_settings);
						set_transient('arfa_options', $style_settings);
					}
				}
			}
			
			$style_settings = get_option('arfa_options');
			if(!is_object($style_settings))
			{
				if($style_settings) 
					$style_settings = unserialize(serialize($style_settings));
				else
					$style_settings = new arstylemodel();
				update_option('arfa_options', $style_settings);
			}
		
			$style_settings->set_default_options();
			
			if(!is_admin() and $arfsettings->jquery_css)
				$arfdatepickerloaded = true;
				
			global $arfadvanceerrcolor;
			
			$arfadvanceerrcolor = array('white'=>'#e9e9e9|#000000|#e9e9e9','black'=>'#000000|#FFFFFF|#000000', 'darkred'=>'#ed4040|#FFFFFF|#ed4040','blue'=>'#D9EDF7|#31708F|#0561bf','pink'=>'#F2DEDE|#A94442|#508b27','yellow'=>'#FAEBCC|#8A6D3B|#af7a0c','red'=>'#EF8A80|#FFFFFF|#1393c3','green'=>'#6CCAC9|#FFFFFF|#7a37ac', 'color1' => '#6cca7b|#FFFFFF|#fb9900', 'color2' => '#c2b079|#FFFFFF|#ed40ae', 'color3' => '#f3b431|#FFFFFF|#ff6600', 'color4' => '#6d91d3|#FFFFFF|#0bb7b5', 'color5' => '#a466cc|#FFFFFF|#a79902');
			
			global $arfdefaulttemplate;
			$arfdefaulttemplate = array(
									'3'	=> __('Contact us','ARForms'),
									'1'	=> __('Subscription Form','ARForms'),
									'5'	=> __('Feedback Form','ARForms'),									
									'6'	=> __('RSVP Form','ARForms'),
									'2'	=> __('Registration Form','ARForms'),	
									'4'	=> __('Survey Form','ARForms'),
									'7' => __('Job Application','ARForms'),
									);
			
			global $arfmsgtounlicop;
					$arfmsgtounlicop = "(";
					$arfmsgtounlicop .= "Un";
					$arfmsgtounlicop .= "lic";
					$arfmsgtounlicop .= "ens";
					$arfmsgtounlicop .= "ed";
					$arfmsgtounlicop .= ")";		
		}
	}
	
	function globalstandalone_route($controller, $action){
		global $armainhelper, $arsettingcontroller;

        if ($controller == 'fields'){
			
			
            if(!defined('DOING_AJAX'))


                define('DOING_AJAX', true);


            global $arfieldcontroller;


            if ($action == 'ajax_get_data')


                $arfieldcontroller->ajax_get_data($armainhelper->get_param('entry_id'), $armainhelper->get_param('field_id'), $armainhelper->get_param('current_field'));


            else if ($action == 'ajax_time_options')


                $arfieldcontroller->ajax_time_options();


        }else if ($controller == 'entries'){

            global $arrecordcontroller;


            if ($action == 'csv'){


                $s = isset($_REQUEST['s']) ? 's' : 'search';


                $arrecordcontroller->csv($armainhelper->get_param('form'), $armainhelper->get_param($s), $armainhelper->get_param('fid'));


                unset($s);


            }else{


                if(!defined('DOING_AJAX'))


                    define('DOING_AJAX', true);

				if($action == 'send_email')


                    $arrecordcontroller->send_email($armainhelper->get_param('entry_id'), $armainhelper->get_param('form_id'), $armainhelper->get_param('type'));


                else if($action == 'create')

				    $arrecordcontroller->ajax_create();

				else if( $action == 'previous' )

				    $arrecordcontroller->ajax_previous();
				else if( $action == 'check_recaptcha' )

				    $arrecordcontroller->ajax_check_recaptcha();

                else if($action == 'update')


                    $arrecordcontroller->ajax_update();


                else if($action == 'destroy')


                    $arrecordcontroller->ajax_destroy();


            }


        }else if($controller == 'settingspreview'){ 


            global $style_settings;


            if(!is_admin())


                $use_saved = true;

			if( isset($_REQUEST['arfmfws']) )
			{
            	include(FORMPATH .'/core/css_create_main.php');
				
				global $arfform, $wpdb, $arrecordhelper, $arfieldhelper, $arformcontrollerm, $arformcontroller;
				$arfformid = $_REQUEST['arfformid'];
				if( $arfformid >= 10000 )
					$form = $arfform->getRefOne((int)$arfformid);
				else
					$form = $arfform->getOne((int)$arfformid);	
				
				$fields = $arfieldhelper->get_form_fields_tmp(false, $form->id, false, 0);
				$values = $arrecordhelper->setup_new_vars($fields, $form);
				
				echo stripslashes_deep(get_option('arf_global_css'));
				$form->options['arf_form_other_css'] = $arformcontroller->br2nl($form->options['arf_form_other_css']);
				echo $armainhelper->esc_textarea($form->options['arf_form_other_css']);
				
				$custom_css_array_form = array(
					'arf_form_outer_wrapper'	=> '.arf_form_outer_wrapper|.arfmodal', 
					'arf_form_inner_wrapper'	=> '.arf_fieldset|.arfmodal',
					'arf_form_title'			=> '.formtitle_style',
					'arf_form_description'		=> 'div.formdescription_style', 
					'arf_form_element_wrapper'	=> '.arfformfield',
					'arf_form_element_label'	=> 'label.arf_main_label',
					'arf_form_elements'			=> '.controls', 
					'arf_submit_outer_wrapper'	=> 'div.arfsubmitbutton', 
					'arf_form_submit_button'	=> '.arfsubmitbutton button.arf_submit_btn',
					'arf_form_next_button'		=> 'div.arfsubmitbutton .next_btn',
					'arf_form_previous_button'	=> 'div.arfsubmitbutton .previous_btn', 
					'arf_form_success_message'	=> '#arf_message_success',
					'arf_form_error_message'	=> '.control-group.arf_error .help-block|.control-group.arf_warning .help-block|.control-group.arf_warning .help-inline|.control-group.arf_warning .control-label|.control-group.arf_error .popover|.control-group.arf_warning .popover',
					'arf_form_page_break'		=> '.page_break_nav',
					);
												
				foreach($custom_css_array_form as $custom_css_block_form => $custom_css_classes_form) 
				{
					
						
						if( isset($form->options[$custom_css_block_form]) and $form->options[$custom_css_block_form] != '' ){
							
							$form->options[$custom_css_block_form] = $arformcontroller->br2nl($form->options[$custom_css_block_form]);
							
							if( $custom_css_block_form == 'arf_form_outer_wrapper' ){
								$arf_form_outer_wrapper_array = explode('|', $custom_css_classes_form);
								
								foreach($arf_form_outer_wrapper_array as $arf_form_outer_wrapper1 ){
									if($arf_form_outer_wrapper1 == '.arf_form_outer_wrapper')
										echo '.ar_main_div_'.$form->id.'.arf_form_outer_wrapper { '.$form->options[$custom_css_block_form].' } ';
									if($arf_form_outer_wrapper1 == '.arfmodal')
										echo '#popup-form-'.$form->id.'.arfmodal{ '.$form->options[$custom_css_block_form].' } ';						
								}
							}
							else if( $custom_css_block_form == 'arf_form_inner_wrapper' ){
								$arf_form_inner_wrapper_array = explode('|', $custom_css_classes_form);
								foreach($arf_form_inner_wrapper_array as $arf_form_inner_wrapper1 ){
									if($arf_form_inner_wrapper1 == '.arf_fieldset')
										echo '.ar_main_div_'.$form->id.' '.$arf_form_inner_wrapper1.' { '.$form->options[$custom_css_block_form].' } ';
									if($arf_form_inner_wrapper1 == '.arfmodal')
										echo '.arfmodal .arfmodal-body .ar_main_div_'.$form->id.' .arf_fieldset { '.$form->options[$custom_css_block_form].' } ';						
								}				
							}
							else if( $custom_css_block_form == 'arf_form_error_message' ){
								$arf_form_error_message_array = explode('|', $custom_css_classes_form);
								
								foreach($arf_form_error_message_array as $arf_form_error_message1 ){
									echo '.ar_main_div_'.$form->id.' '.$arf_form_error_message1.' { '.$form->options[$custom_css_block_form].' } ';
								}
							}	 
							else {			
								echo '.ar_main_div_'.$form->id.' '.$custom_css_classes_form.' { '.$form->options[$custom_css_block_form].' } ';
							}
								
						} // end if
						
					
				}
					
				foreach($values['fields'] as $field){
					
					$field['id'] = $arfieldhelper->get_actual_id($field['id']);
					
					if( isset($field['field_width']) and $field['field_width'] != '' ){
						echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .help-block { width: '.$field['field_width'].'px; } ';
					}
							
					if( $field['type'] == 'divider' ){
						
						if($field['arf_divider_font']!="Arial" && $field['arf_divider_font']!="Helvetica" && $field['arf_divider_font']!="sans-serif" && $field['arf_divider_font']!="Lucida Grande" && $field['arf_divider_font']!="Lucida Sans Unicode" && $field['arf_divider_font']!="Tahoma" && $field['arf_divider_font']!="Times New Roman" && $field['arf_divider_font']!="Courier New" && $field['arf_divider_font']!="Verdana" && $field['arf_divider_font']!="Geneva" && $field['arf_divider_font']!="Courier" && $field['arf_divider_font']!="Monospace" && $field['arf_divider_font']!="Times" && $field['arf_divider_font']!="")
						{
							$googlefontbaseurl = "http://fonts.googleapis.com/css?family=";
							echo "@import url(".$googlefontbaseurl.urlencode($field['arf_divider_font']).");";
						}
						
						if( $field['arf_divider_font_style'] == 'italic' ) {
							$arf_heading_font_style = ' font-weight:normal; font-style:italic; ';			
						} else {
							$arf_heading_font_style = ' font-weight:'.$field['arf_divider_font_style'].'; font-style:normal; ';
						}
						
						if( $field['arf_divider_inherit_bg'] == 1 ){
							echo ' .ar_main_div_'.$form->id.' #heading_'.$field['id'].' { background-color:inherit; } ';
						} else {
							echo ' .ar_main_div_'.$form->id.' #heading_'.$field['id'].' { background-color:'.esc_attr($field['arf_divider_bg_color']).'; } ';
						}
							
						echo ' .ar_main_div_'.$form->id.' #heading_'.$field['id'].' h2.arf_sec_heading_field { font-family:'.stripslashes($field['arf_divider_font']).'; font-size:'.$field['arf_divider_font_size'].'px; '.$arf_heading_font_style.'}';
					}
					
					$custom_css_array = array(
										'css_outer_wrapper'		=> '.arf_form_outer_wrapper', 
										'css_label'				=> '.css_label',
										'css_input_element'		=> '.css_input_element',
										'css_description'		=> '.arf_field_description',
											);
												
					foreach($custom_css_array as $custom_css_block => $custom_css_classes) {
						
						if( isset($field[$custom_css_block]) and $field[$custom_css_block] != '' ){
							
							$field[$custom_css_block] = $arformcontroller->br2nl($field[$custom_css_block]);
							
							if( $custom_css_block == 'css_outer_wrapper' and $field['type'] != 'divider' ){
								echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container { '.$field[$custom_css_block].' } ';
							} else if( $custom_css_block == 'css_outer_wrapper' and $field['type'] == 'divider' ){
								echo ' .ar_main_div_'.$form->id.' #heading_'.$field['id'].' { '.$field[$custom_css_block].' } ';
							} else if( $custom_css_block == 'css_label' and $field['type'] != 'divider' ){
								echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container label.arf_main_label { '.$field[$custom_css_block].' } ';				
							} else if( $custom_css_block == 'css_label' and $field['type'] == 'divider' ){
								echo ' .ar_main_div_'.$form->id.' #heading_'.$field['id'].' h2.arf_sec_heading_field { '.$field[$custom_css_block].' } ';				
							} else if( $custom_css_block == 'css_input_element' ){
								
								if( $field['type'] == 'textarea' )	
								{		
									echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .controls textarea { '.$field[$custom_css_block].' } ';
								}	
								else if( $field['type'] == 'select' )
								{
									echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .controls select { '.$field[$custom_css_block].' } ';
									echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .controls .arfbtn.dropdown-toggle { '.$field[$custom_css_block].' } ';
								}
								else if($field['type'] == 'radio')
								{
									echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .arf_radiobutton label { '.$field[$custom_css_block].' } ';
								}	
								else if($field['type'] == 'checkbox')
								{
									echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .arf_checkbox_style label { '.$field[$custom_css_block].' } ';
								}	
								else if($field['type'] == 'file')
								{
									echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .controls .arfajax-file-upload { '.$field[$custom_css_block].' } ';			
								}
								else if($field['type'] == 'scale')
								{
									echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .controls .rate_widget_div { '.$field[$custom_css_block].' } ';	
								}
								else if($field['type'] == 'colorpicker')
								{
									echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .controls .arfcolorpickerfield { '.$field[$custom_css_block].' } ';	
								}	
								else						
								{
									echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .controls input { '.$field[$custom_css_block].' } ';
									if( $field['type'] == 'email' ){
										echo '.ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container + .confirm_email_container .controls input {'.$field[$custom_css_block].'}';
									}
									if( $field['type'] == 'password' ){
										echo '.ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container + .confirm_password_container .controls input{ '.$field[$custom_css_block].'}';
									}	
								}	
							} else if( $custom_css_block == 'css_description' and $field['type'] != 'divider' ){
								echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .arf_field_description { '.$field[$custom_css_block].' } ';				
							} else if( $custom_css_block == 'css_description' and $field['type'] == 'divider' ){
								echo ' .ar_main_div_'.$form->id.'  #heading_'.$field['id'].' .arf_heading_description { '.$field[$custom_css_block].' } ';				
							}
								
						} // end if
						
					} // end foreach
					
					if( $field['type'] == 'like' ) {
						echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .arf_like_btn.active { background: '.$field['like_bg_color'].'; }';	
						echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .arf_dislike_btn.active { background: '.$field['dislike_bg_color'].'; }';		
					}
					
					if( $field['type'] == 'slider' ) {		
						echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .arf-slider-track { background : '.$field['slider_bg_color2'].'; filter: progid:DXImageTransform.Microsoft.gradient(enabled = false); }';
						
						if( $field['slider_handle'] == 'square' || $field['slider_handle'] == 'triangle' ) {
							echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .arf-slider-track .slider-selection, ';
								echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .arf-slider-track { ';
								echo 'border-radius:0px 0px 0px 0px; ';
							echo ' } '; 
						}		
						echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .arf-slider-track .slider-selection { ';
							echo 'background : '.$field['slider_bg_color'].'; ';	
							echo 'background-color : '.$field['slider_bg_color'].'; ';	
							echo 'filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);';	
						echo ' } '; 
						echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .arf-slider-track .arf-slider-handle { ';
							if( $field['slider_handle'] == 'triangle' ) {
								echo 'border-bottom-color: '.$field['slider_handle_color'].'; ';			
							} else {
								echo 'background: '.$field['slider_handle_color'].'; ';	
								echo 'background-color: '.$field['slider_handle_color'].'; ';	
							}
								echo 'filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);';
						echo ' } '; 		
									
					}
				}
				
			} else
				return false;	

        }
        

    }
	
	function wp_ar_autoupdate() {
		
		require_once(FORMPATH.'/core/wp_ar_auto_update.php');
		
		
		$verified = 0;
		global $arformsplugin, $arformcontroller;
		$verified = $arformcontroller->$arformsplugin();
		
		if($verified == 1)
		{
			$wp_ar_plugin_current_version = '2.7'; 
			$wp_ar_plugin_remote_path = 'http://www.reputeinfosystems.com/tf/plugins/arforms/updatecheck.php'; 
			$wp_ar_plugin_slug = 'arforms/arforms.php';
			new wp_ar_auto_update($wp_ar_plugin_current_version, $wp_ar_plugin_remote_path, $wp_ar_plugin_slug);
		}
	}
	function menu(){


        global $arfsettings, $armainhelper;


        


        if(current_user_can('administrator') and !current_user_can('arfviewforms')){


            global $current_user;


            $arfroles = $armainhelper->frm_capabilities();


            foreach($arfroles as $arfrole => $arfroledescription)


                $current_user->add_cap( $arfrole );


            unset($arfroles);


            unset($arfrole);


            unset($arfroledescription);


        }
		function get_free_menu_position($start, $increment = 0.1)
		{
				foreach ($GLOBALS['menu'] as $key => $menu) {
					$menus_positions[] = $key;
				}
		 
				if (!in_array($start, $menus_positions)) return $start;
		 
				/* the position is already reserved find the closet one */
				while (in_array($start, $menus_positions)) {
					$start += $increment;
				}
				return $start;
		}
 
		$place = get_free_menu_position(26.1 , .1);
			
        if(current_user_can('arfviewforms')){


            global $arformcontroller;

            add_menu_page('ARForms', 'ARForms', 'arfviewforms', 'ARForms', array($arformcontroller, 'route'), ARFIMAGESURL.'/main-icon-small2n.png',(string)$place);
			
        }elseif(current_user_can('arfviewentries')){


            global $arrecordcontroller;


            add_menu_page('ARForms', 'ARForms', 'arfviewentries', 'ARForms', array($arrecordcontroller, 'route'), ARFIMAGESURL.'/main-icon-small2n.png',(string)$place);


        }
		
		add_submenu_page('', '', '', 'administrator', 'ARForms-settings1',array(&$this, 'list_entries'));

    }


    


    function menu_css(){ ?>


<style type="text/css">
#adminmenu .toplevel_page_ARForms div.wp-menu-image img{  padding: 2px 0 0 2px; }

</style>    


<?php    


    }


    


    function get_form_nav($id, $show_nav=false,$values,$record,$template_id=0,$is_ref_form=0){


        global $pagenow, $armainhelper;


        


        $show_nav = $armainhelper->get_param('show_nav', $show_nav);


        


        if($show_nav)


            include(VIEWS_PATH.'/formmenu.php');


    }




    function settings_link($links, $file){


        $settings = '<a href="'.admin_url('admin.php?page=ARForms-settings').'">' . __('Settings', 'ARForms') . '</a>';


        array_unshift($links, $settings);


        


        return $links;


    }

    function admin_js(){
 

        global $arfversion, $pagenow, $maincontroller;

        if(version_compare( $GLOBALS['wp_version'], '3.4', '<'))
		{
			if(version_compare( $GLOBALS['wp_version'], '3.1', '<'))
			{
				$maincontroller->wp_dequeue_script_custom('jquery');
			}
			else
			{	
				wp_dequeue_script('jquery');
			}
			wp_enqueue_script('jquery-latest',ARFURL. '/bootstrap/js/jquery-1.7.2.min.js');
			wp_enqueue_script('jquery-ui-sortable-1',ARFURL.'/bootstrap/js/jquery.ui.sortable.js');
			wp_enqueue_script('jquery-ui-draggable-1',ARFURL.'/bootstrap/js/jquery.ui.draggable.js');
		}
		else
		{
			wp_enqueue_script('jquery');
		}

		
        wp_enqueue_script('jquery-ui-core');

		
 		if(isset($_GET) and (isset($_GET['page']) and preg_match('/ARForms*/', $_GET['page'])) or ($pagenow == 'edit.php' and isset($_GET) and isset($_GET['post_type']) and $_GET['post_type'] == 'frm_display')){

 
            add_filter('admin_body_class', array(&$this, 'admin_body_class'));


            wp_enqueue_script('jquery-ui-sortable');


            wp_enqueue_script('jquery-ui-draggable');


            wp_enqueue_script('admin-widgets');


            wp_enqueue_style('widgets');
			
			
			if(version_compare( $GLOBALS['wp_version'], '3.2', '>'))
			{
				wp_enqueue_script( 'media-upload' );
			}

            if(version_compare( $GLOBALS['wp_version'], '3.4', '<'))
			{
            	wp_enqueue_script('arforms_admin', ARFURL . '/js/arforms_admin.js', array('jquery', 'jquery-ui-draggable-1'), $arfversion);
			}
			else
			{
				wp_enqueue_script('arforms_admin', ARFURL . '/js/arforms_admin.js', array('jquery', 'jquery-ui-draggable'), $arfversion);
			}


            wp_enqueue_script('arforms');
 
			if(is_rtl())
			{
				wp_enqueue_style('arforms-admin-rtl',ARFURL.'/css/arforms-rtl.css', array(), $arfversion);
			}
            wp_enqueue_style('arforms-admin', ARFURL. '/css/arf_plugin.css', array(), $arfversion);
			
			
			wp_enqueue_style('arforms_new', ARFURL. '/css/arforms_new.css', array(), $arfversion);
			
			
            wp_enqueue_script('jquery-elastic', ARFURL.'/js/jquery/jquery.elastic.js', array('jquery'));
			
			wp_enqueue_script('arfjquery-json',ARFURL.'/js/jquery/jquery.json-2.4.js',array('jquery'));
			
			wp_enqueue_script('sack');
			
			if( version_compare( $GLOBALS['wp_version'], '2.9', '>') and version_compare( $GLOBALS['wp_version'], '3.1', '<')) {
					
				wp_enqueue_style('arforms-admin-3.0', ARFURL. '/css/arf_plugin_3.0.css', array(), $arfversion);
					
			}
			
						
			if( version_compare( $GLOBALS['wp_version'], '3.0', '>') and version_compare( $GLOBALS['wp_version'], '3.2', '<')) {
					
				wp_enqueue_style('arforms-admin-3.1', ARFURL. '/css/arf_plugin_3.1.css', array(), $arfversion);
					
			}
			
			if( version_compare( $GLOBALS['wp_version'], '3.1', '>') and version_compare( $GLOBALS['wp_version'], '3.3', '<')) {
					
				wp_enqueue_style('arforms-admin-3.2', ARFURL. '/css/arf_plugin_3.2.css', array(), $arfversion);
					
			}
			
			if( version_compare( $GLOBALS['wp_version'], '3.2', '>') and version_compare( $GLOBALS['wp_version'], '3.4', '<')) {
					
				wp_enqueue_style('arforms-admin-3.3', ARFURL. '/css/arf_plugin_3.3.css', array(), $arfversion);
					
			}
			
			if( version_compare( $GLOBALS['wp_version'], '3.3', '>') and version_compare( $GLOBALS['wp_version'], '3.5', '<')) {
					
				wp_enqueue_style('arforms-admin-3.4', ARFURL. '/css/arf_plugin_3.4.css', array(), $arfversion);
					
			}
			
			if($GLOBALS['wp_version'] >= '3.8' and version_compare( $GLOBALS['wp_version'], '3.9', '<')) {
				
				wp_enqueue_style('arforms-admin-3.8', ARFURL. '/css/arf_plugin_3.8.css', array(), $arfversion);
					
			}
			
			if($GLOBALS['wp_version'] >= '3.9' and version_compare( $GLOBALS['wp_version'], '3.10', '<')) {
				
				wp_enqueue_style('arforms-admin-3.9', ARFURL. '/css/arf_plugin_3.9.css', array(), $arfversion);
					
			}
			
			if($GLOBALS['wp_version'] >= '4.0' and version_compare( $GLOBALS['wp_version'], '4.2', '<')) {
				
				wp_enqueue_style('arforms-admin-4.0', ARFURL. '/css/arf_plugin_4.0.css', array(), $arfversion);
					
			}

        }else if($pagenow == 'post.php' or ($pagenow == 'post-new.php' and isset($_REQUEST['post_type']) and $_REQUEST['post_type'] == 'frm_display')){


            if(isset($_REQUEST['post_type'])){


                $post_type = $_REQUEST['post_type'];


            }else if(isset($_REQUEST['post']) and !empty($_REQUEST['post'])){


                $post = get_post($_REQUEST['post']);


                if(!$post)


                    return;


                $post_type = $post->post_type;


            }else{


                return;


            }


            


            if($post_type == 'frm_display'){


                wp_enqueue_script('jquery-ui-draggable');


                if(version_compare( $GLOBALS['wp_version'], '3.4', '<'))
				{
					wp_enqueue_script('jquery-ui-draggable-1',ARFURL.'/bootstrap/js/jquery.ui.draggable.js');
					wp_enqueue_script('arforms_admin', ARFURL . '/js/arforms_admin.js', array('jquery', 'jquery-ui-draggable-1'), $arfversion);
				}
				else
				{
					wp_enqueue_script('arforms_admin', ARFURL . '/js/arforms_admin.js', array('jquery', 'jquery-ui-draggable'), $arfversion);
				}


                wp_enqueue_script('jquery-elastic', ARFURL.'/js/jquery/jquery.elastic.js', array('jquery'));


                wp_enqueue_style('arforms-admin', ARFURL. '/css/arf_plugin.css', array(), $arfversion);
				
							
				wp_enqueue_style('arforms_new', ARFURL. '/css/arforms_new.css', array(), $arfversion);
				
				if( version_compare( $GLOBALS['wp_version'], '2.9', '>') and version_compare( $GLOBALS['wp_version'], '3.1', '<')) {
						
					wp_enqueue_style('arforms-admin-3.0', ARFURL. '/css/arf_plugin_3.0.css', array(), $arfversion);
						
				}
				
				
				if( version_compare( $GLOBALS['wp_version'], '3.0', '>') and version_compare( $GLOBALS['wp_version'], '3.2', '<')) {
						
					wp_enqueue_style('arforms-admin-3.1', ARFURL. '/css/arf_plugin_3.1.css', array(), $arfversion);
						
				}
				
				if( version_compare( $GLOBALS['wp_version'], '3.1', '>') and version_compare( $GLOBALS['wp_version'], '3.3', '<')) {
						
					wp_enqueue_style('arforms-admin-3.2', ARFURL. '/css/arf_plugin_3.2.css', array(), $arfversion);
						
				}
				
				if( version_compare( $GLOBALS['wp_version'], '3.2', '>') and version_compare( $GLOBALS['wp_version'], '3.4', '<')) {
						
					wp_enqueue_style('arforms-admin-3.3', ARFURL. '/css/arf_plugin_3.3.css', array(), $arfversion);
						
				}
				
				if( version_compare( $GLOBALS['wp_version'], '3.3', '>') and version_compare( $GLOBALS['wp_version'], '3.5', '<')) {
						
					wp_enqueue_style('arforms-admin-3.4', ARFURL. '/css/arf_plugin_3.4.css', array(), $arfversion);
						
				}
				
				if($GLOBALS['wp_version'] >= '3.8' and version_compare( $GLOBALS['wp_version'], '3.9', '<')) {
				
					wp_enqueue_style('arforms-admin-3.8', ARFURL. '/css/arf_plugin_3.8.css', array(), $arfversion);
						
				}

				

            }

        }

    }

	
	
    


    function admin_body_class($classes){


        global $wp_version;


        if(version_compare( $wp_version, '3.4.9', '>'))


            $classes .= ' arf35trigger';


        


        return $classes;


    }
	
	
	
	

    function front_head($ispost = ''){


        global $arfsettings, $arfversion, $arfdbversion, $maincontroller;
		
		if(!is_admin())
		{
			if(version_compare( $GLOBALS['wp_version'], '3.4', '<'))
			{
				if(version_compare( $GLOBALS['wp_version'], '3.1', '<'))
				{
					$maincontroller->wp_dequeue_script_custom('jquery');
				}
				else
				{	
					wp_dequeue_script('jquery');
				}
				wp_deregister_script('jquery');
				wp_register_script('jquery', ARFURL . '/bootstrap/js/jquery-1.7.2.min.js');
				wp_enqueue_script('jquery');
				wp_register_script('arfbootstrap-js', ARFURL . '/bootstrap/js/bootstrap.min.js',array('jquery'));
				wp_register_script('jquery-bootstrap-slect', ARFURL.'/bootstrap/js/bootstrap-select.js', array('jquery'));
				wp_register_script('jquery-validation',ARFURL.'/bootstrap/js/jqBootstrapValidation.js',array('jquery'));
				wp_register_style('arfbootstrap-css', ARFURL.'/bootstrap/css/bootstrap.css');
				wp_register_style('arfbootstrap-select', ARFURL.'/bootstrap/css/bootstrap-select.css', array(), $arfversion);
			}
			else
			{
				wp_enqueue_script('jquery');
				wp_register_script('arfbootstrap-js', ARFURL . '/bootstrap/js/bootstrap.min.js',array('jquery'));
				wp_register_script('jquery-bootstrap-slect', ARFURL.'/bootstrap/js/bootstrap-select.js', array('jquery'));
				wp_register_script('jquery-validation',ARFURL.'/bootstrap/js/jqBootstrapValidation.js',array('jquery'));
				wp_register_style('arfbootstrap-css', ARFURL.'/bootstrap/css/bootstrap.css');
				wp_register_style('arfbootstrap-select', ARFURL.'/bootstrap/css/bootstrap-select.css', array(), $arfversion);
			}
			
			 if(version_compare( $GLOBALS['wp_version'], '3.3', '<'))
			 	wp_register_script('jquery-ui-progressbar', ARFURL . '/js/jquery/jquery.ui.progressbar.min.js');	
			 
			 if(version_compare( $GLOBALS['wp_version'], '3.1', '<'))
			 	wp_register_script('jquery-ui-widget', ARFURL . '/js/jquery/ui.widget.js');	
			
			 wp_register_script('arfbootstrap-timepicker-js', ARFURL .'/bootstrap/js/bootstrap-timepicker.js');
			 wp_register_style('arfbootstrap-timepicker', ARFURL .'/bootstrap/css/bootstrap-timepicker.css');
			 wp_register_script('arfbootstrap-modernizr-js', ARFURL .'/bootstrap/js/modernizr.js');
			 wp_register_script('arfbootstrap-slider-js', ARFURL .'/bootstrap/js/bootstrap-slider.js');
			 wp_register_style('arfbootstrap-slider', ARFURL .'/bootstrap/css/bootstrap-slider.css');
			 wp_register_style('arfdisplaycss', ARFURL .'/css/arf_front.css');
			 wp_register_style('arfrecaptchacss', ARFURL .'/css/recaptcha_style.css');
			 wp_register_style('arf-filedrag', ARFURL .'/css/arf_filedrag.css');
			 wp_register_script('jquery-icheck', ARFURL.'/bootstrap/js/icheck.min.js', array('jquery'));
			 wp_register_script('arf-modal-js', ARFURL.'/js/arf_modal_js.js', array('jquery'));
				
			 wp_register_script('arf-conditional-logic-js', ARFURL.'/js/arf_conditional_logic.js', array('jquery'));
			 
			 wp_register_script('arfbootstrap-inputmask', ARFURL.'/bootstrap/js/bootstrap-inputmask.js', array('jquery'));
			 
			 
			 
			 wp_register_script('arf-colorpicker-js', ARFURL .'/js/colpick.js');
			 
			 wp_register_style('arf-colorpicker', ARFURL .'/css/colpick.css');	
			 
			 wp_register_script('arf-colorpicker-basic-js', ARFURL .'/js/jquery.simple-color-picker.js');
			 
			 wp_register_style('arf-fontawesome-css',ARFURL.'/css/font-awesome.min.css');
			 
			 //wp_enqueue_style( 'arf-fontawesome-css' );
			 
			 //wp_print_styles( 'arf-fontawesome-css' );
		}
		else
		{
			wp_enqueue_script('jquery');
		}	
		wp_register_script('arforms', ARFURL . '/js/arforms.js', array('jquery'), $arfversion, true);

		wp_register_script('recaptcha-ajax', ARFURL . '/js/recaptcha_ajax.js');
		
		if($ispost = '1' && !is_admin())
		{
			global $post;
			$post_content = isset($post->post_content) ? $post->post_content : '';
			$parts = @explode("[ARForms",$post_content);
			if(isset($parts[1]))
			{
				$myidpart = @explode("id=",$parts[1]);
				$myid = @explode("]",$myidpart[1]);
				if($myid[0] > 0)
				{	
					
				}
			}	
			
		}
		
       


        if(!is_admin() and isset($arfsettings->load_style) and $arfsettings->load_style == 'all'){


            $css = apply_filters('getarfstylesheet', ARFURL .'/css/arf_front.css', 'header');


            if(is_array($css)){


                foreach($css as $css_key => $file)


                    wp_enqueue_style('arf-forms'.$css_key, $file, array(), $arfversion);


                unset($css_key);


                unset($file);


            }else


                wp_enqueue_style('arf-forms', $css, array(), $arfversion);


            unset($css);


                


            global $arfcssloaded;


            $arfcssloaded = true;


        }


    }


    


    function footer_js($location='footer'){


        global $arfloadcss, $arfsettings, $arfversion, $arfcssloaded, $arfforms_loaded, $armainhelper;





        if($arfloadcss and !is_admin() and ($arfsettings->load_style != 'none')){


            if($arfcssloaded)


                $css = apply_filters('getarfstylesheet', '', $location);


            else
				
				$css = apply_filters('getarfstylesheet', ARFURL .'/css/arf_front.css', $location);


             


            if(!empty($css)){


                echo "\n".'<script type="text/javascript">';


                if(is_array($css)){


                    foreach($css as $css_key => $file){


                        echo 'jQuery("head").append(unescape("%3Clink rel=\'stylesheet\' id=\'arf-forms'. ($css_key + $arfcssloaded) .'-css\' href=\''. $file. '\' type=\'text/css\' media=\'all\' /%3E"));';



                        unset($css_key);


                        unset($file);


                    }


                }else{


                    echo 'jQuery("head").append(unescape("%3Clink rel=\'stylesheet\' id=\'arfformscss\' href=\''. $css. '\' type=\'text/css\' media=\'all\' /%3E"));';


                }


                unset($css);


                echo '</script>'."\n";


            }


        }
	
		
		if(!is_admin() and $location != 'header' and !empty($arfforms_loaded)) 

            $armainhelper->load_scripts(array('arforms')); 
			
			
    }


  	function front_head_js(){
	
			global $post;
			$wp_upload_dir 	= wp_upload_dir();
			$upload_main_url = 	$wp_upload_dir['baseurl'].'/arforms/maincss';
			
			$post_content = isset($post->post_content) ? $post->post_content : '';
			$parts = @explode("[ARForms",$post_content);
			$myidpart = @explode("id=",$parts[1]);
			$myid = @explode("]",$myidpart[1]);
			
			
			
			if(!is_admin())
			{
				global $wp_query;	
				$posts = $wp_query->posts;
				$pattern = '\[(\[?)(ARForms|ARForms_popup)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
				
				if(is_array($posts))
				{
					foreach ($posts as $post){
						if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
							&& array_key_exists( 2, $matches )
							&& in_array( 'ARForms', $matches[2] ) )
						{
							
							break;	
						}    
					}
					
					$formids = array();
					if(is_array($matches) && count($matches)>0)
					{
						foreach($matches as $k=>$v)
						{
							
							
							foreach($v as $key => $val)
							{
								$parts = explode("id=",$val);
								if($parts > 0 && isset($parts[1]))
								{
									
									if (stripos(@$parts[1], ']') !== false) {
										$partsnew = explode("]",$parts[1]);
										$formids[] = $partsnew[0];
									}
									else if (stripos(@$parts[1], ' ') !== false) {
										
										$partsnew = explode(" ",$parts[1]);
										$formids[] = $partsnew[0];
									}
									else
									{
										
									}
									
								}
								
							}
							
						}
					}
				}
				
				
				$newvalarr = array();
					
				if(isset($formids) and is_array($formids) && count($formids) > 0)
				{
					foreach($formids as $newkey => $newval)
					{
						if(stripos($newval, ' ') !== false) {
						$partsnew = explode(" ",$newval);
						$newvalarr[] = $partsnew[0];
						}
						else
							$newvalarr[] = $newval;
					}
				}
				
				if(is_array($newvalarr) && count($newvalarr) > 0)
				{
					$newvalarr = array_unique($newvalarr);
					foreach($newvalarr as $newkey => $newval)
					{
						global $arforms_loaded;
						$arforms_loaded[ $newval ] = true;
						
						if(is_ssl())
						{
							$fid = str_replace("http://","https://",$upload_main_url.'/maincss_'.$newval.'.css');
						}
						else
						{
							$fid = $upload_main_url.'/maincss_'.$newval.'.css';
						}	
						
						wp_enqueue_style('arfformscss'.$newval,$fid);
						wp_enqueue_style('arfbootstrap-css');
						wp_enqueue_style('arfdisplaycss');
						//wp_enqueue_script( 'arfbootstrap-js');
						//wp_enqueue_script('jquery-validation');
					}
				}
			}

       


    }

	function arf_db_check(){
		global $MdlDb;
		$arf_db_version = get_option('arf_db_version');
		if( ( $arf_db_version == '' || !isset($arf_db_version) ) && IS_WPMU )
				$MdlDb->upgrade($old_db_version);
	}
	
    function install($old_db_version=false){


        global $MdlDb;
		
			$arf_db_version = get_option('arf_db_version');
			if( $arf_db_version == '' || !isset($arf_db_version) )
        		$MdlDb->upgrade($old_db_version);

    }
    
    function referer_session() {


    	global $arfsiteurl, $arfsettings;


    	if ( !isset($_SESSION) )


    		session_start();


    	


    	if ( !isset($_SESSION['arfhttppages']) or !is_array($_SESSION['arfhttppages']) )


    		$_SESSION['arfhttppages'] = array("http://". $_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI']);


    	


    	if ( !isset($_SESSION['arfhttpreferer']) or !is_array($_SESSION['arfhttpreferer']) )


    		$_SESSION['arfhttpreferer'] = array();


    	


    	if (!isset($_SERVER['HTTP_REFERER']) or (isset($_SERVER['HTTP_REFERER']) and (strpos($_SERVER['HTTP_REFERER'], $arfsiteurl) === false) and ! (in_array($_SERVER['HTTP_REFERER'], $_SESSION['arfhttpreferer'])) )) {


    		if (! isset($_SERVER['HTTP_REFERER'])){


    		    $direct = __('Type-in or bookmark', 'ARForms');


    		    if(!in_array($direct, $_SESSION['arfhttpreferer']))


    			    $_SESSION['arfhttpreferer'][] = $direct;


    		}else{


    			$_SESSION['arfhttpreferer'][] = $_SERVER['HTTP_REFERER'];	


    		}


    	}


    	


    	if ($_SESSION['arfhttppages'] and !empty($_SESSION['arfhttppages']) and (end($_SESSION['arfhttppages']) != "http://". $_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI']))


    		$_SESSION['arfhttppages'][] = "http://". $_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI'];


    		

    	if(count($_SESSION['arfhttppages']) > 100){


    	    foreach($_SESSION['arfhttppages'] as $pkey => $ppage){


    	        if(count($_SESSION['arfhttppages']) <= 100)


    	            break;


    	            


    		    unset($_SESSION['arfhttppages'][$pkey]);


    		}


    	}


    }



    function parse_standalone_request(){


        $plugin     = $this->get_param('plugin');


        $action = isset($_REQUEST['arfaction']) ? 'arfaction' : 'action';


        $action = $this->get_param($action);  


        $controller = $this->get_param('controller');





        if( !empty($plugin) and $plugin == 'ARForms' and !empty($controller) ){


          $this->standalone_route($controller, $action);


          exit;


        }


    }



    function standalone_route($controller, $action=''){
		
		global $arformcontroller;


        if($controller == 'forms' and !in_array($action, array('export', 'import')))


            $arformcontroller->preview($this->get_param('form'));

        else


            do_action('arfstandaloneroute', $controller, $action);


    }




    function get_param($param, $default=''){


        return (isset($_POST[$param]) ? $_POST[$param] : (isset($_GET[$param])?$_GET[$param]:$default));


    }


    function get_form_shortcode($atts){
	
        global $arfskipshortcode, $arrecordcontroller;
			

        if($arfskipshortcode){


            $sc = '[ARForms';


            foreach($atts as $k => $v)


                $sc .= ' '. $k .'="'. $v .'"';


            return $sc .']';


        }

        extract(shortcode_atts(array('id' => '', 'key' => '', 'title' => false, 'description' => false, 'readonly' => false, 'entry_id' => false, 'fields' => array()), $atts));


        do_action('ARForms_shortcode_atts', compact('id', 'key', 'title', 'description', 'readonly', 'entry_id', 'fields'));
		
		
		global $wpdb;

		$res = $wpdb->get_results( $wpdb->prepare("SELECT options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $id), 'ARRAY_A');
		$res = (is_array($res) and count($res) > 0) ? $res[0] : $res;
				
		$values = maybe_unserialize((isset($res['options'])) ? $res['options'] : '' );
				
		if( isset($values['display_title_form']) and $values['display_title_form'] == '0') {	
			$title = false; 
			$description = false;
		} else {
			$title = true; 	
			$description = true;
		}
		
        return $arrecordcontroller->show_form($id, $key, $title, $description); 


    }


	function get_form_shortcode_popup($atts){


        global $arfskipshortcode, $arrecordcontroller;


        if($arfskipshortcode){


            $sc = '[ARForms_popup';


            foreach($atts as $k => $v)


                $sc .= ' '. $k .'="'. $v .'"';


            return $sc .']';


        }

        extract(shortcode_atts(array('id' => '', 'key' => '', 'title' => false, 'description' => false, 'readonly' => false, 'entry_id' => false, 'fields' => array(), 'desc' => '','shortcode_type' => ''), $atts));


        do_action('ARForms_popup_shortcode_atts', compact('id', 'key', 'title', 'description', 'readonly', 'entry_id', 'fields', 'desc','shortcode_type'));
		
		global $wpdb;

		$res = $wpdb->get_results( $wpdb->prepare("SELECT options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $id), 'ARRAY_A');
		$res = ( count($res) > 0 ) ? $res[0] : ''; 
				
		$values = maybe_unserialize(isset($res['options']) ? $res['options'] : '');
				
		if( isset($values['display_title_form']) and $values['display_title_form'] == '0') {	
			$title = false; 
			$description = false;
		} else {
			$title = true; 	
			$description = true;
		}
		
		$type = isset($atts['type']) ? $atts['type'] : '';	
		$modal_height = isset($atts['height']) ? $atts['height'] : '540';		
		$modal_width = isset($atts['width']) ? $atts['width'] : '800';
		$position = isset($atts['position']) ? $atts['position'] : '';	
		$btn_angle = isset($atts['angle']) ? $atts['angle'] : '0';
		

		if($shortcode_type == 'normal'){
			return $arrecordcontroller->show_form($id, $key, $title, $description); 
		}
		
        return $arrecordcontroller->show_form_popup($id, $key, $title, $description, $desc , $type, $modal_height, $modal_width, $position, $btn_angle); 
 

    }
	

    function widget_text_filter( $content ){


    	$regex = '/\[\s*ARForms\s+.*\]/';


    	return preg_replace_callback( $regex, array($this, 'widget_text_filter_callback'), $content );


    }



    function widget_text_filter_callback( $matches ) {
		
		if( $matches[0] )
		{
			$parts 		= explode("id=",$matches[0]);
			$partsnew 	= explode(" ",$parts[1]);
			$formid		= $partsnew[0];
			$formid 	= str_replace(']', '', $formid);
			$formid		= @trim($formid);
			global $arforms_loaded;
			$arforms_loaded[ $formid ] = true;
		}
		
        return do_shortcode( $matches[0] );


    }



    function widget_text_filter_popup( $content ){


    	$regex = '/\[\s*ARForms_popup\s+.*\]/';


    	return preg_replace_callback( $regex, array($this, 'widget_text_filter_callback_popup'), $content );


    }




    function widget_text_filter_callback_popup( $matches ) {
		
		if( $matches[0] )
		{
			$parts 		= explode("id=",$matches[0]);
			$partsnew 	= explode(" ",$parts[1]);
			$formid		= $partsnew[0];
			$formid		= @trim($formid);
			global $arforms_loaded;
			$arforms_loaded[ $formid ] = true;
		}
		
        return do_shortcode( $matches[0] );


    }
	


    function get_postbox_class(){


        if(version_compare( $GLOBALS['wp_version'], '3.3.2', '>'))


            return 'postbox-container';


        else


            return 'inner-sidebar';


    }


	function set_js()
	{
		if(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms-entries") 
		{
			wp_register_script('arfbootstrap-js', ARFURL . '/bootstrap/js/bootstrap.min.js',array('jquery'));
			wp_enqueue_script( 'arfbootstrap-js');
			wp_enqueue_script('jquery-bootstrap-slect', ARFURL.'/bootstrap/js/bootstrap-select.js', array('jquery'));
			
			wp_enqueue_script( 'jquery', ARFURL . '/datatables/media/js/jquery.js');
			wp_enqueue_script( 'jquery_dataTables', ARFURL . '/datatables/media/js/jquery.dataTables.js');
			wp_enqueue_script( 'ColVis', ARFURL . '/datatables/media/js/ColVis.js');
			wp_enqueue_script( 'FixedColumns', ARFURL . '/datatables/media/js/FixedColumns.js');
			wp_enqueue_script( 'jqplot_min',ARFURL . '/js/linechart/jquery.jqplot.min.js');
			wp_enqueue_script( 'barRenderer_min',ARFURL . '/js/linechart/jqplot.barRenderer.min.js');
			wp_enqueue_script( 'logAxisRenderer_min',ARFURL . '/js/linechart/jqplot.logAxisRenderer.min.js');	
			wp_enqueue_script( 'canvasTextRenderer_min',ARFURL . '/js/linechart/jqplot.canvasTextRenderer.min.js');	
			wp_enqueue_script( 'canvasAxisLabelRenderer_min',ARFURL . '/js/linechart/jqplot.canvasAxisLabelRenderer.min.js');	
			wp_enqueue_script( 'canvasAxisTickRenderer_min',ARFURL . '/js/linechart/jqplot.canvasAxisTickRenderer.min.js');	
			wp_enqueue_script( 'dateAxisRenderer_min',ARFURL . '/js/linechart/jqplot.dateAxisRenderer.min.js');	
			wp_enqueue_script( 'categoryAxisRenderer_min',ARFURL . '/js/linechart/jqplot.categoryAxisRenderer.min.js');		
			wp_enqueue_script( 'highlighter_min',ARFURL . '/js/linechart/jqplot.highlighter.min.js');
			?>
	        <!--[if lt IE 9]><script language="javascript" type="text/javascript" src='<?php echo ARFURL;?>/js/linechart/jquery.excanvas.min.js'"></script><![endif]-->
			<?php
			wp_register_script('arf_tooltipster', ARFURL.'/js/jquery.tooltipster.js', array('jquery'));
			wp_enqueue_script('arf_tooltipster'); 
		}
		elseif(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms-settings") 
		{
			wp_register_script('arfbootstrap-js', ARFURL . '/bootstrap/js/bootstrap.min.js',array('jquery'));
			wp_enqueue_script( 'arfbootstrap-js');
			wp_enqueue_script('jquery-bootstrap-slect', ARFURL.'/bootstrap/js/bootstrap-select.js', array('jquery'));
			wp_register_script('arf_tooltipster', ARFURL.'/js/jquery.tooltipster.js', array('jquery'));
			wp_enqueue_script('arf_tooltipster');
		}
		elseif(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms-import-export")
		{
			wp_register_script('arfbootstrap-js', ARFURL . '/bootstrap/js/bootstrap.min.js',array('jquery'));
			wp_enqueue_script( 'arfbootstrap-js');
			wp_enqueue_script('jquery-bootstrap-slect', ARFURL.'/bootstrap/js/bootstrap-select.js', array('jquery'));
			wp_enqueue_script('form1', ARFURL.'/js/jquery.form.js','jquey');
		}
		elseif(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms" && !isset($_REQUEST['arfaction']))				
		{
			wp_enqueue_script( 'jquery', ARFURL . '/datatables/media/js/jquery.js');
			wp_enqueue_script( 'jquery_dataTables', ARFURL . '/datatables/media/js/jquery.dataTables.js');
			wp_enqueue_script( 'ColVis', ARFURL . '/datatables/media/js/ColVis.js');
			
			wp_register_script('arfbootstrap-js', ARFURL . '/bootstrap/js/bootstrap.min.js',array('jquery'));
			wp_enqueue_script( 'arfbootstrap-js');
			wp_enqueue_script('jquery-bootstrap-slect', ARFURL.'/bootstrap/js/bootstrap-select.js', array('jquery'));
			
			wp_register_script('arf_tooltipster', ARFURL.'/js/jquery.tooltipster.js', array('jquery'));
			wp_enqueue_script('arf_tooltipster');		
		}
		elseif(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms" && ($_REQUEST['arfaction']=='edit' || $_REQUEST['arfaction']=='new' || $_REQUEST['arfaction']=='duplicate' || $_REQUEST['arfaction']=='update'))				
		{
			wp_enqueue_script('arforms_admin', ARFURL.'/js/arforms_admin.js');
			
			wp_register_script('arfbootstrap-js', ARFURL . '/bootstrap/js/bootstrap.min.js',array('jquery'));
			wp_enqueue_script( 'arfbootstrap-js');
			//wp_enqueue_script('jquery-bootstrap-slect', ARFURL.'/bootstrap/js/bootstrap-select.js', array('jquery'));
			//wp_enqueue_script('jquery-selectize', ARFURL.'/bootstrap/js/selectize.js', array('jquery'));
			wp_register_script('jquery-icheck', ARFURL.'/bootstrap/js/icheck.min.js', array('jquery'));
			wp_enqueue_script('jquery-icheck');
			wp_enqueue_script( 'slideControl_new', ARFURL . '/bootstrap/js/modernizr.js', array('jquery') , '3.6', true );
			wp_enqueue_script( 'slideControl', ARFURL . '/bootstrap/js/bootstrap-slider.js', array('jquery'), '3.6', true );
			wp_enqueue_script('switchery', ARFURL.'/js/switchery.js', array('jquery'));
			
			wp_enqueue_script('arf_colpick', ARFURL.'/js/colpick.js', array('jquery'));
			wp_register_script('arf_tooltipster', ARFURL.'/js/jquery.tooltipster.js', array('jquery'));
			wp_enqueue_script('arf_tooltipster');
		}
	}
	
	function set_css()
	{
		global $arfversion;
		
		if(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms-entries") 
		{ 
			wp_register_style('arfbootstrap-css', ARFURL.'/bootstrap/css/bootstrap.css');
			wp_enqueue_style('arfbootstrap-css');
			wp_enqueue_style('arfbootstrap-select', ARFURL.'/bootstrap/css/bootstrap-select.css', array(), $arfversion);
			wp_enqueue_style('jqplot_min_css',ARFURL . '/js/linechart/jquery.jqplot.min.css');
			
			wp_register_style('arf_tooltipster_css', ARFURL.'/css/tooltipster.css');
			wp_enqueue_style('arf_tooltipster_css');
		}
		elseif(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms-settings")
		{
			wp_register_style('arfbootstrap-css', ARFURL.'/bootstrap/css/bootstrap.css');
			wp_enqueue_style('arfbootstrap-css');
			wp_enqueue_style('arfbootstrap-select', ARFURL.'/bootstrap/css/bootstrap-select.css', array(), $arfversion);
			
			wp_register_style('arf_tooltipster_css', ARFURL.'/css/tooltipster.css');
			wp_enqueue_style('arf_tooltipster_css');
		} 
		elseif(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms-import-export")
		{
			wp_register_style('arfbootstrap-css', ARFURL.'/bootstrap/css/bootstrap.css');
			wp_enqueue_style('arfbootstrap-css');
			wp_enqueue_style('arfbootstrap-select', ARFURL.'/bootstrap/css/bootstrap-select.css', array(), $arfversion);
		} 
		elseif(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms" && !isset($_REQUEST['arfaction']))				
		{
			
			wp_register_style('arfbootstrap-css', ARFURL.'/bootstrap/css/bootstrap.css');
			wp_enqueue_style('arfbootstrap-css');
			wp_enqueue_style('arfbootstrap-select', ARFURL.'/bootstrap/css/bootstrap-select.css', array(), $arfversion);
			
			wp_register_style('arf_tooltipster_css', ARFURL.'/css/tooltipster.css');
			wp_enqueue_style('arf_tooltipster_css');
			
			wp_register_style('arf-fontawesome-css',ARFURL.'/css/font-awesome.min.css');
			wp_enqueue_style( 'arf-fontawesome-css' );
			
		}
		elseif(isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] == "ARForms" && ($_REQUEST['arfaction']=='edit' || $_REQUEST['arfaction']=='new' || $_REQUEST['arfaction']=='duplicate' || $_REQUEST['arfaction']=='update'))				
		{
			wp_register_style('arfbootstrap-css', ARFURL.'/bootstrap/css/bootstrap.css');
			wp_enqueue_style('arfbootstrap-css');
			wp_enqueue_style('arfbootstrap-select', ARFURL.'/bootstrap/css/bootstrap-select.css', array(), $arfversion);
			
			wp_enqueue_style('arfbootstrap-selectizecss', ARFURL.'/bootstrap/css/selectize.default.css', array(), $arfversion);
			wp_register_style('slideControl-css', ARFURL.'/bootstrap/css/bootstrap-slider.css');
			wp_enqueue_style('slideControl-css');
			wp_enqueue_style('switcherycss', ARFURL.'/css/switchery.css', array(), $arfversion);
			
			wp_register_style('arf-form-element', ARFURL.'/css/form-element.css');
			wp_enqueue_style('arf-form-element');
			
			wp_register_style('arf_colpick', ARFURL.'/css/colpick.css');
			wp_enqueue_style('arf_colpick');
			
			wp_register_style('arf_tooltipster_css', ARFURL.'/css/tooltipster.css');
			wp_enqueue_style('arf_tooltipster_css');
			
			wp_register_style('arf-fontawesome-css',ARFURL.'/css/font-awesome.min.css');
			wp_enqueue_style( 'arf-fontawesome-css' );
		}
	}
	function wp_dequeue_script_custom( $handle ) {
		global $wp_scripts;
		if ( !is_a($wp_scripts, 'WP_Scripts') )
			$wp_scripts = new WP_Scripts();
	
		$wp_scripts->dequeue( $handle );
	}
	function wp_dequeue_style_custom( $handle ) {
		global $wp_styles;
		if ( !is_a($wp_styles, 'WP_Styles') )
			$wp_styles = new WP_Styles();
	
		$wp_styles->dequeue( $handle );
	}
	
	function getwpversion()
	{
		global $arfversion, $MdlDb, $arnotifymodel, $arfform, $arfrecordmeta;
		$bloginformation = array();
		$str = $MdlDb->get_rand_alphanumeric(10);
		
		if(is_multisite())
			$multisiteenv = "Multi Site";
		else
			$multisiteenv = "Single Site";
		
		$bloginformation[] = $arnotifymodel->sitename();
		$bloginformation[] = $arfform->sitedesc();
		$bloginformation[] = home_url();
		$bloginformation[] = get_bloginfo('admin_email');
		$bloginformation[] = $arfrecordmeta->wpversioninfo();
		$bloginformation[] = $arfrecordmeta->getlanguage();
		$bloginformation[] = $arfversion;
		$bloginformation[] = $_SERVER['REMOTE_ADDR'];
		$bloginformation[] = $str;
		$bloginformation[] = $multisiteenv;
		
		$arnotifymodel->checksite($str);
		
		$valstring = implode("||",$bloginformation);
		$encodedval = base64_encode($valstring);
		
		$urltopost = $arfform->getsiteurl();
		$response = wp_remote_post( $urltopost, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'wpversion' => $encodedval ),
			'cookies' => array()
			)
		);
	}
	
	//arf_backup used to take backup of particular folder from current version before upgrade with new one.	
	function arf_backup()
	{
		$databaseversion = get_option('arf_db_version');
		update_option('old_db_version',$databaseversion);
	}
	
	//upgrade_data used to reset old folder with new one after upgrade plugin.
	function upgrade_data()
	{
		global $newdbversion;
		
		if(!isset($newdbversion) || $newdbversion == "")
			$newdbversion = get_option('arf_db_version');
		
		if(version_compare($newdbversion, '2.7', '<'))
		{
			$path = FORMPATH.'/core/views/upgrade_latest_data.php';
			include($path);
		}
	}
	
	function arf_rmdirr($dirname)
	{
		// Sanity check
		if (!file_exists($dirname)) {
		return false;
		}
		
		// Simple delete for a file
		if (is_file($dirname)) {
		return unlink($dirname);
		}
		
		// Loop through the folder
		$dir = dir($dirname);
		while (false !== $entry = $dir->read()) {
		// Skip pointers
		if ($entry == '.' || $entry == '..') {
		continue;
		}
		
		// Recurse
		$this->arf_rmdirr("$dirname/$entry");
		} 
		
		// Clean up
		$dir->close();
		return rmdir($dirname);
	}
	
	function arf_copyr($source, $dest)
	{
		global $wp_filesystem;
		// Check for symlinks
		if (is_link($source)) {
			return symlink(readlink($source), $dest);
		}
	
		// Simple copy for a file
		if (is_file($source)) {
			return $wp_filesystem->copy($source, $dest);
		}
	
		// Make destination directory
		if (!is_dir($dest)) {
			$wp_filesystem->mkdir($dest);
		}
	
		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}
	
			// Deep copy directories
			$this->arf_copyr("$source/$entry", "$dest/$entry");
		}
	
		// Clean up
		$dir->close();
		return true;
	}
	
	function arf_hide_update_notice_to_all_admin_users()
	{
		global $pagenow;
		
		if(isset($_GET) and (isset($_GET['page']) and preg_match('/ARForms*/', $_GET['page'])) or ($pagenow == 'edit.php' and isset($_GET) and isset($_GET['post_type']) and $_GET['post_type'] == 'frm_display'))
		{
			remove_all_actions( 'network_admin_notices',10000 );
			remove_all_actions( 'user_admin_notices',10000 );
			remove_all_actions( 'admin_notices',10000 );
			remove_all_actions( 'all_admin_notices',10000 );
		}
	}
	
	function arf_export_form_data(){
		//require_once ABSPATH.'wp-load.php';
		//require_once ABSPATH."wp-admin/includes/file.php";
		
		global $wpdb,$submit_bg_img,$arfmainform_bg_img,$form_custom_css ,$WP_Filesystem, $submit_hover_bg_img;
		
		$arf_db_version = get_option('arf_db_version');
		
		$wp_upload_dir 	= wp_upload_dir();
		$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/';
		
		if( isset($_REQUEST['export_button']) ){
			if(!empty($_REQUEST['frm_add_form_id']))
			{
				
				$form_ids = @implode(",",$_REQUEST['frm_add_form_id']);
				
				$file_name = "ARForms_".time();
				
				$filename = $file_name.".xml";
				
				$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE id in (".$form_ids.")");
				
				$xml          = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
				
				$xml         .= "<forms>\n";
				
				  
				foreach($res as $key => $result_array) 
				{
			
					$xml         .= "\t<form id='".$res[$key]->id."'>\n";	
					
					$xml         .= "\t<site_url>".site_url()."</site_url>\n";
					
					$xml         .= "\t<arf_db_version>".$arf_db_version."</arf_db_version>\n";
					
					$xml         .= "\t\t<general_options>\n";
					foreach($result_array as $key => $value)
					{
						//$key holds the table column name
						if($key == 'options')
						{	
							foreach(unserialize($value) as $ky=>$vl)
							{
								if($ky != 'before_html')
								{
									if(!is_array($vl))
									{
										if($ky == 'success_url')
										{
											$new_field[$ky] = $vl;
											//$new_field[$ky] = @htmlentities($vl,ENT_NOQUOTES,'UTF-8');
											$new_field[$ky] = @str_replace('&amp;','[AND]',$new_field[$ky]);
										}
										else if($ky == 'form_custom_css')
										{
											$form_custom_css = @str_replace(site_url(),'[REPLACE_SITE_URL]',$vl);
											
											$form_custom_css = str_replace('&lt;br /&gt;','[ENTERKEY]',str_replace('&lt;br/&gt;','[ENTERKEY]',str_replace('&lt;br&gt;','[ENTERKEY]',str_replace('<br />','[ENTERKEY]',str_replace('<br/>','[ENTERKEY]',str_replace('<br>','[ENTERKEY]',trim(preg_replace('/\s\s+/', '[ENTERKEY]', $form_custom_css))))))));
											
										}
										else if($ky == 'arf_form_other_css')
										{
											$new_field[$ky] = str_replace('&lt;br /&gt;','[ENTERKEY]',str_replace('&lt;br/&gt;','[ENTERKEY]',str_replace('&lt;br&gt;','[ENTERKEY]',str_replace('<br />','[ENTERKEY]',str_replace('<br/>','[ENTERKEY]',str_replace('<br>','[ENTERKEY]',trim(preg_replace('/\s\s+/', '[ENTERKEY]', str_replace(site_url(),'[REPLACE_SITE_URL]',$vl)))))))));
											
										}
										else
										{
											$string = @( ( is_array($vl) and count($vl) > 0 ) ? $vl : str_replace('&lt;br /&gt;','[ENTERKEY]',str_replace('&lt;br/&gt;','[ENTERKEY]',str_replace('&lt;br&gt;','[ENTERKEY]',str_replace('<br />','[ENTERKEY]',str_replace('<br/>','[ENTERKEY]',str_replace('<br>','[ENTERKEY]',trim(preg_replace('/\s\s+/', '[ENTERKEY]', $vl)))))))) );
											
											$new_field[$ky] 	= $string;
											//$new_field[$ky] 	= @htmlentities($string,ENT_NOQUOTES,'UTF-8');
										}
									}
									else
										$new_field[$ky] = $vl;
								}
								else
								{
									$vl2 = '[REPLACE_BEFORE_HTML]';
									$new_field[$ky] = $vl2;
								}
							}
							$value1 = @serialize($new_field);
							
							$value1 = "<![CDATA[".$value1."]]>";
							
							$xml .= "\t\t\t<$key>";
							
							//embed the SQL data in a CDATA element to avoid XML entity issues
							$xml .= "$value1"; 
								
							//and close the element
							$xml .= "</$key>\n";
						}
						elseif($key == 'form_css')
						{
							$form_css_arry =  unserialize($value);
							foreach($form_css_arry as $form_css_key=>$form_css_val)
							{
								if($form_css_key == "submit_bg_img")
								{
									if($form_css_val!="")
									{
										$explodefilename1 = @explode("/",$form_css_val);
										$newfilename1 =  $explodefilename1[count($explodefilename1)-1];
										$submit_bg_img = $newfilename1;
										if($submit_bg_img!="")
										{
											$newfilename = $submit_bg_img;
							
											@copy($upload_dir.$newfilename, $upload_dir."temp_".$newfilename);
											
											if( file_exists( $upload_dir."temp_".$newfilename ) )
											{
												$filename_arry[] = "temp_".$newfilename;								
												$submit_bg_img = "temp_".$newfilename;
											}
											else
												$submit_bg_img = '';
										}
									}
									else
									{
										$submit_bg_img = $form_css_val;
									}		
								}
								else if($form_css_key == "submit_hover_bg_img")
								{
									if($form_css_val!="")
									{
										$explodefilename1 = @explode("/",$form_css_val);
										$newfilename1 =  $explodefilename1[count($explodefilename1)-1];
										$submit_hover_bg_img = $newfilename1;
										if($submit_hover_bg_img!="")
										{
											$newfilename = $submit_hover_bg_img;
							
											@copy($upload_dir.$newfilename, $upload_dir."temp_".$newfilename);
											
											if( file_exists( $upload_dir."temp_".$newfilename ) )
											{
												$filename_arry[] = "temp_".$newfilename;								
												$submit_hover_bg_img = "temp_".$newfilename;
											}
											else
												$submit_hover_bg_img = '';
										}
									}
									else
									{
										$submit_hover_bg_img = $form_css_val;
									}		
								}
								elseif($form_css_key == "arfmainform_bg_img")
								{
									if($form_css_val!="")
									{
										$explodefilename1 = @explode("/",$form_css_val);
										$newfilename2 =  $explodefilename1[count($explodefilename1)-1];
										$arfmainform_bg_img = $newfilename2;
										
										if($arfmainform_bg_img!="")
										{
											$newfilename1 = $arfmainform_bg_img;
			
											@copy($upload_dir.$newfilename1, $upload_dir."temp_".$newfilename1);
											
											if( file_exists( $upload_dir."temp_".$newfilename1 ) )
											{
												$filename_arry[] = "temp_".$newfilename1;
												$arfmainform_bg_img = "temp_".$newfilename1;
											}
											else
												$arfmainform_bg_img = '';
										}
									}
									else
									{
										$arfmainform_bg_img = $form_css_val;
									}		
									
								}
							}
							
							$value = "<![CDATA[".$value."]]>";
							
							$xml .= "\t\t\t<$key>";
							
							//embed the SQL data in a CDATA element to avoid XML entity issues
							$xml .= "$value"; 
							
							//and close the element
							$xml .= "</$key>\n";
						}
						else if($key == "description" || $key == "name" )
						{
							$value = "<![CDATA[".$value."]]>";
							
							$xml .= "\t\t\t<$key>";
							
							//embed the SQL data in a CDATA element to avoid XML entity issues
							$xml .= "$value"; 
							
							//and close the element
							$xml .= "</$key>\n";
						}
						else
						{
							$xml .= "\t\t\t<$key>";
							
							//embed the SQL data in a CDATA element to avoid XML entity issues
							$xml .= "$value"; 
							
							//and close the element
							$xml .= "</$key>\n";
						}
					}
					$xml .= "\t\t</general_options>\n"; 
					
					
					$xml         .= "\t\t<fields>\n";
						
					$res_fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_fields WHERE form_id = ".$result_array->id);	
					
					foreach($res_fields as $key_fields => $result_field_array) 
					{
						$xml         .= "\t\t\t<field>\n";
						foreach($result_field_array as $key_field => $value_field)
						{
							//$key holds the table column name
							
							if($key_field == 'field_options')
							{
								foreach(unserialize($value_field) as $ky=>$vl)
								{
									if($ky != 'custom_html')
									{
										$vl = @( (is_array($vl) and count($vl) > 0 ) ? $vl : str_replace('&lt;br /&gt;','[ENTERKEY]',str_replace('&lt;br/&gt;','[ENTERKEY]',str_replace('&lt;br&gt;','[ENTERKEY]', str_replace('<br />','[ENTERKEY]',str_replace('<br/>','[ENTERKEY]',str_replace('<br>','[ENTERKEY]',trim(preg_replace('/\s\s+/', '[ENTERKEY]', $vl)))))))) );
										
										$new_field1[$ky] = $vl;
									}
								}
								$value_field_ser = serialize($new_field1);
								
								$value_field_ser = "<![CDATA[".$value_field_ser."]]>";
								
								$xml .= "\t\t\t\t<$key_field>";
								
								//embed the SQL data in a CDATA element to avoid XML entity issues
								$xml .= "$value_field_ser"; 
								
								//and close the element
								$xml .= "</$key_field>\n";
							}
							elseif($key_field == 'conditional_logic')
							{
								foreach(unserialize($value_field) as $ky_cl=>$vl_cl)
								{
									$new_field_cl[$ky_cl] = $vl_cl;
								}		
								$new_field_cl1 = serialize($new_field_cl);
								$xml .= "\t\t\t\t<$key_field>";
									
								//embed the SQL data in a CDATA element to avoid XML entity issues
								$new_field_cl1 = "<![CDATA[".$new_field_cl1."]]>";
								
								$xml .= "$new_field_cl1"; 
								
								//and close the element
								$xml .= "</$key_field>\n";
								
							}
							else
							{
								if(!is_array($vl))
								{
									if($key_field=="description" || $key_field=="name" || $key_field=="default_value")
									{
										$vl1 = "<![CDATA[".stripslashes_deep($value_field)."]]>";
									}
									elseif($key_field=="options")
									{
										$vl1 = "<![CDATA[".$value_field."]]>";
									}
									else
									{
										$vl1 = $value_field;
										//$vl1 = @htmlentities($value_field,ENT_NOQUOTES,'UTF-8');
									}	
								}
								else
								{
									//$vl1 = $value_field;
									$vl1 = "<![CDATA[".stripslashes_deep($value_field)."]]>";
								}	
									
								$xml .= "\t\t\t\t<$key_field>";
								
								//embed the SQL data in a CDATA element to avoid XML entity issues
								$xml .= "$vl1"; 
								
								//and close the element
								$xml .= "</$key_field>\n";
							}
						}
						$xml .= "\t\t\t</field>\n";
					}
					$xml .= "\t\t</fields>\n";
					
					$xml         .= "\t\t<autoresponder>\n";
						
					$res_ar = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = ".$result_array->id);	
					
					foreach($res_ar as $key_ar => $result_ar_array) 
					{
						foreach($result_ar_array as $key_ar => $value_ar)
						{			
							if( $key_ar == 'aweber' ||  $key_ar == 'mailchimp' || $key_ar == 'getresponse' ||  $key_ar == 'gvo' ||  $key_ar == 'ebizac' ||  $key_ar == 'icontact' ||  $key_ar == 'constant_contact' ||  $key_ar == 'infusionsoft' )
							{
								//$key holds the table column name
								$xml .= "\t\t\t\t<$key_ar>\n";
								
								if($value_ar != "") 
								{		
									foreach( maybe_unserialize($value_ar) as $autores_key => $autores_val )
									{
										//$key holds the table column name
										$xml .= "\t\t\t\t\t<$autores_key>";
										
										$autores_val = "<![CDATA[".$autores_val."]]>";
										
										//embed the SQL data in a CDATA element to avoid XML entity issues
										$xml .= "$autores_val"; 
										
										//and close the element
										$xml .= "</$autores_key>\n";
									}
								}								
								//and close the element
								$xml .= "\t\t\t\t</$key_ar>\n";
							}
							else
							{
								//$key holds the table column name
								$xml .= "\t\t\t\t<$key_ar>";
								
								$value_ar = "<![CDATA[".$value_ar."]]>";
								
								//embed the SQL data in a CDATA element to avoid XML entity issues
								$xml .= "$value_ar"; 
								
								//and close the element
								$xml .= "</$key_ar>\n";
							}
						}
					}
					$xml .= "\t\t</autoresponder>\n";
					
					$xml .= "\t\t<submit_bg_img>";
								
					//embed the SQL data in a CDATA element to avoid XML entity issues
					$xml .= "$submit_bg_img"; 
					
					//and close the element
					$xml .= "</submit_bg_img>\n";
					
					
					$xml .= "\t\t<submit_hover_bg_img>";
								
					//embed the SQL data in a CDATA element to avoid XML entity issues
					$xml .= "$submit_hover_bg_img"; 
					
					//and close the element
					$xml .= "</submit_hover_bg_img>\n";
					
			
					$xml .= "\t\t<arfmainform_bg_img>";
								
					//embed the SQL data in a CDATA element to avoid XML entity issues
					$xml .= "$arfmainform_bg_img"; 
					
					//and close the element
					$xml .= "</arfmainform_bg_img>\n";
					
					$xml .= "\t\t<form_custom_css>";
								
					//embed the SQL data in a CDATA element to avoid XML entity issues
					$xml .= "$form_custom_css"; 
					
					//and close the element
					$xml .= "</form_custom_css>\n";	
						
					$xml .= "\t</form>\n\n";
				}
				$xml .= "</forms>";
				
				//WP_Filesystem();
				//global $wp_filesystem;
				//$wp_filesystem->put_contents($upload_dir.$filename , $xml , 0777);
				
				file_put_contents( $upload_dir.$filename, $xml );
				
				$filename_arry[] = $filename;
				
				$file = @pathinfo($upload_dir.$filename);
				
				$filename_ser =  @serialize($filename_arry);
			
				$compressed_file = $file_name.'.zip';
				
				$this->Create_zip($filename_ser,$upload_dir.$compressed_file,$upload_dir);
				
				
				$compressed_file_url = $file['dirname'].'/'.$file['filename'].'.zip';
				
				@header('Content-Type: application/zip; charset=' . $charset, true);
				@header('Content-disposition: attachment; filename='.$compressed_file);
				@header('Content-Length: '.filesize($upload_dir.$compressed_file));
				@readfile($upload_dir.$compressed_file);
				@unlink($upload_dir.$compressed_file);
			}
		}
	}
		
	function Create_zip($source, $destination,$destindir)
	{
		$filename = array();
		$filename = unserialize($source);
		
		$zip = new ZipArchive();
		if($zip->open($destination,ZipArchive::CREATE)===TRUE)
		{
			$i = 0;
			foreach($filename as $file)
			{
				$zip->addFile($destindir.$file, $file);// Add the files to the .zip file
				$i++;
			}
			$zip->close(); // Closing the zip file
		}
		
		foreach($filename as $file1)
		{
			unlink($destindir.$file1);
		}
	}
}	