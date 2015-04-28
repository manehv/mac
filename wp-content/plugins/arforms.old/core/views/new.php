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

global $arformcontroller, $armainhelper, $arfieldhelper, $arrecordcontroller,$wpdb;


?>
<style type="text/css">
<?php
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
			if( is_ssl() )
				$googlefontbaseurl = "https://fonts.googleapis.com/css?family=";
			else	
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
							
	if( in_array( $field['type'], array('text','email','date','time','password','number','image','url','phone','number') ) ){
		$custom_css_array['css_add_icon'] = '.arf_prefix, .arf_suffix';
	}
	
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
						echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container + .confirm_email_container .arf_prefix_suffix_wrapper{ '.$field[$custom_css_block].' }';
					}
					if( $field['type'] == 'password' ){
						echo '.ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container + .confirm_password_container .controls input{ '.$field[$custom_css_block].'}';
						echo ' .ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container + .confirm_password_container .arf_prefix_suffix_wrapper { '.$field[$custom_css_block].' } ';
					}	 
					echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .arf_prefix_suffix_wrapper { '.$field[$custom_css_block].' } ';
					
					
				}	
			} else if( $custom_css_block == 'css_description' and $field['type'] != 'divider' ){
				echo ' .ar_main_div_'.$form->id.'  #arf_field_'.$field['id'].'_container .arf_field_description { '.$field[$custom_css_block].' } ';				
			} else if( $custom_css_block == 'css_description' and $field['type'] == 'divider' ){
				echo ' .ar_main_div_'.$form->id.'  #heading_'.$field['id'].' .arf_heading_description { '.$field[$custom_css_block].' } ';				
			} else if( $custom_css_block == 'css_add_icon' and $field['type'] != 'divider' ){
				echo '.ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .arf_prefix,
				.ar_main_div_'.$form->id.' #arf_field_'.$field['id'].'_container .arf_suffix { '.$field[$custom_css_block].' } ';
				if( $field['type'] == 'email' ){
					echo '.ar_main_div_'.$form->id.' .arf_confirm_email_field_'.$field['id'].' .arf_prefix,
					.ar_main_div_'.$form->id.' .arf_confirm_email_field_'.$field['id'].' .arf_suffix {'. $field[$custom_css_block].' } ';
				}
				if( $field['type'] == 'password' ){
					echo '.ar_main_div_'.$form->id.' .arf_confirm_password_field_'.$field['id'].' .arf_prefix,
					.ar_main_div_'.$form->id.' .arf_confirm_password_field_'.$field['id'].' .arf_suffix {'. $field[$custom_css_block].' } ';
				}
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
?>
</style>
<?php  
$arf_form_logic_rules	= $arfieldhelper->get_form_logic_rules($form->id, $form->form_key);
global $arf_conditional_logic_loaded;
if( !empty($arf_form_logic_rules) ) 
	$arf_conditional_logic_loaded[ $form->id ]	= 1;

echo $arf_form_logic_rules;

$arf_form_logic_rules	= $arfieldhelper->get_form_logic_rules($form->id, $form->form_key);

$arf_page_break_fields	= $arfieldhelper->get_form_pagebreak_fields($form->id, $form->form_key, $values);

echo $arf_page_break_fields;
 		
?><div class="arf_form ar_main_div_<?php echo $form->id ?> arf_form_outer_wrapper" id="arffrm_<?php echo $form->id ?>_container"><?php 
//pre render action
do_action('arf_predisplay_form',$form);
do_action('arf_predisplay_form'.$form->id,$form);

if(isset($preview) and $preview) { echo '<div id="form_success_'.$form->id.'" style="display:none;">'.$saved_message.'</div>'; } 

global $MdlDb;
$page_num = $MdlDb->get_count($MdlDb->fields, array("form_id" => $form->id, "type" => 'break'));
if( $page_num > 0)
	$temp_calss = 'arfpagebreakform';
else
	$temp_calss = '';

if( isset($is_modal_form) and $is_modal_form )
	echo '<div class="arf_content_another_page" style="display:none;"></div>';
	
if(isset($preview) and $preview) { ?>
<form enctype="<?php echo apply_filters('arfformenctype', 'multipart/form-data', $form) ?>" method="post" class="arfshowmainform arfpreivewform <?php echo $temp_calss;?> <?php do_action('arfformclasses', $form) ?>" id="form_<?php echo $form->form_key ?>" novalidate="">
<?php } else {  ?>
<form enctype="<?php echo apply_filters('arfformenctype', 'multipart/form-data', $form) ?>" method="post" class="arfshowmainform <?php echo $temp_calss;?> <?php do_action('arfformclasses', $form) ?>" id="form_<?php echo $form->form_key ?>" <?php echo ($arfsettings->use_html) ? '' : 'action=""'; ?> novalidate="">
<?php } ?>
<?php $browser_info = $arrecordcontroller->getBrowser($_SERVER['HTTP_USER_AGENT']);

$hiddencontent = '<input type="hidden" name="arf_browser_name" id="arf_browser_name" data-version="'.$browser_info['version'].'" value="'.$browser_info['name'].'" />
	<input type="hidden" name="imagename_'.$form->id.'" id="imagename_'.$form->id.'" value="" />
	<input type="hidden" name="upload_field_id_'.$form->id.'" id="upload_field_id_'.$form->id.'" value="" />
	<input type="hidden" name="editor_loaded" id="editor_loaded" value="0" />
	<input type="hidden" name="form_key_'.$form->id.'" id="form_key_'.$form->id.'" value="'.$form->form_key.'" />';
	$hiddencontent = $arformcontroller->arf_remove_br( $hiddencontent );
	echo $hiddencontent;
$preview = $preview ? 1 : 0;
$hiddencontent = '<input type="hidden" name="is_form_preview_'.$form->id.'" id="is_form_preview_'.$form->id.'" value="'.( $preview ? 1 : 0 ).'" />
	<input type="hidden" name="arf_validate_outside_'.$form->id.'" id="arf_validate_outside_'.$form->id.'" data-validate="'.( ( apply_filters('arf_validateform_outside', false, $form) ) ? 1 : 0 ).'" value="'.( ( apply_filters('arf_validateform_outside', false, $form) ) ? 1 : 0).'" />
	<input type="hidden" name="arf_is_validate_outside_'.$form->id.'" id="arf_is_validate_outside_'.$form->id.'" data-validate="'.( ( apply_filters('arf_is_validateform_outside', false, $form) ) ? 1 : 0 ).'" value="'.( ( apply_filters('arf_is_validateform_outside', false, $form) ) ? 1 : 0 ).'" />
	<input type="hidden" name="arf_is_resetform_aftersubmit_'.$form->id.'" id="arf_is_resetform_aftersubmit_'.$form->id.'" value="'.( ( apply_filters('arf_is_resetform_aftersubmit', true, $form) ) ? 1 : 0 ).'" />
	<input type="hidden" name="arf_is_resetform_outside_'.$form->id.'" id="arf_is_resetform_outside_'.$form->id.'" value="'.( ( apply_filters('arf_is_resetform_outside', false, $form) ) ? 1 : 0 ).'" />';
	
$hiddencontent = $arformcontroller->arf_remove_br( $hiddencontent );
echo $hiddencontent;

$arfrunningtotal = $arfieldhelper->arf_getall_running_total_str($form->id, $form->form_key, $values);
echo $arfrunningtotal; 

if( $values['fields'] ){
	foreach($values['fields'] as $field)
	{
		if( $field['type'] == 'html' || $field['enable_total'] == 1 )
		{
			$field['id'] = $arfieldhelper->get_actual_id($field['id']);
			echo '<input type="hidden" name="item_meta['.$field['id'].']" id="arf_item_meta_'.$field['id'].'" value="" />';
		} 
	}
}
	
$arr = maybe_unserialize($form->form_css);
$newarr = array();
foreach($arr as $k => $v)
	$newarr[$k] = $v;
			
if(isset($preview) and $preview) { ?><input type="hidden" name="arf_form_date_format" id="arf_form_date_format" value="<?php echo $newarr['date_format']; ?>" /><?php } ?><input type="hidden" name="form_tooltip_error_<?php echo $form->id ?>" id="form_tooltip_error_<?php echo $form->id ?>" data-color="<?php echo $newarr['arferrorstylecolor']; ?>" data-position="<?php echo $newarr['arferrorstyleposition']; ?>" value="<?php echo $newarr['arferrorstyle'];?>" /><input type="text" name="fake_text" id="fake_text" value="" style="height:0 !important; margin:0 !important; opacity: 0 !important; filter:alpha(opacity=0); padding:0 !important; width:0 !important; float:left;" /><?php 


include(VIEWS_PATH.'/errors.php');


$form_action = 'create';


require(VIEWS_PATH.'/form.php'); 


?></form><?php

	if( ( isset($preview) and $preview ) or (isset($_POST['is_preview']) and $_POST['is_preview']=='yes') ) { 
		global $arfsettings;
		
		if($arfsettings->form_submit_type == 1)  
			 wp_enqueue_script('filedrag', ARFURL.'/js/filedrag/filedrag_front.js');						
		
	} else {
	 
	 	global $arfsettings, $submit_ajax_page; 
				  
		//$arfsettings->form_submit_type == 1 and 	 
		if( isset($submit_ajax_page) and $submit_ajax_page == 1 ) {
	 	
			$arrecordcontroller->footer_js();
			$submit_ajax_page = 0;
		}
	} 

$form = apply_filters('arfafterdisplayform', $form);

//pre render action
do_action('arf_afterdisplay_form',$form);
do_action('arf_afterdisplay_form'.$form->id,$form);
?></div>