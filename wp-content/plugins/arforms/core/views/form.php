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
@ini_set('max_execution_time', 0); 
global $arfforms_loaded, $arfloadcss, $arfcssloaded, $arfsettings, $style_settings, $arrecordcontroller, $maincontroller, $arfieldhelper, $arformhelper, $arformcontroller, $arf_column_classes;

$arf_column_classes = array();

$arfforms_loaded[] = $form; 

$browser_info = @$arrecordcontroller->getBrowser($_SERVER['HTTP_USER_AGENT']);

if(isset($values['custom_style']) and $values['custom_style']) $arfloadcss = true;


global $arforms_loaded;
$is_loaded_normal = ($arforms_loaded[$form->id]) ? true : false; 

if(!$arfcssloaded and $arfloadcss){


echo $maincontroller->footer_js('header');


$arfcssloaded = true;


}


$values['label_position'] = (isset($values['label_position']) and $values['label_position'] != '') ? $values['label_position'] : $style_settings->position;

wp_print_styles('arfbootstrap-css');
wp_print_styles('arfdisplaycss');
//wp_print_scripts('jquery-validation');	
//wp_print_scripts('arfbootstrap-js');

$wp_upload_dir 	= wp_upload_dir();
$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';


if(is_ssl())
{
	$upload_main_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/maincss');
	$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/css');
}
else
{
	$upload_main_url = 	$wp_upload_dir['baseurl'].'/arforms/maincss';
	$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/css';
}
	
$fid = $upload_main_url.'/maincss_'.$form->id.'.css';
wp_register_style('arfformscss'.$form->id,$fid);
wp_print_styles('arfformscss'.$form->id,$fid);


		
?><div class="allfields"><?php
 
global $MdlDb;
$imagecontrol_num = $MdlDb->get_count($MdlDb->fields, array("form_id" => $form->id, "type" => 'imagecontrol'));
if( $imagecontrol_num > 0 && $values['fields'] )
{
	foreach($values['fields'] as $field)
	{
		if( $field['type'] == 'imagecontrol' )
		{
			$field['id'] = $arfieldhelper->get_actual_id($field['id']);	
    		$field_name = 'item_meta['. $field['id'] .']';		
			//apply filter before display field
			$field = apply_filters('arfbeforefielddisplay', $field);
			if( $field['image_url'] != '' )
			{
				$arfheightwidth = "";
				if( $field['image_width'] != '' )
					$arfheightwidth .= "width:".str_replace( array("px", " "),"",strtolower($field['image_width'] ) )."px;";
				if( $field['image_height'] != '' )
					$arfheightwidth .= "height:".str_replace( array("px", " "),"",strtolower($field['image_height'] ) )."px;";
				
				$arfimagealignclass = '';
				$arfimageleft		= 'left:'.$field['image_left'].'; ';
				$arfimagetop		= 'top:'.$field['image_top'].';';				
				$arfimagealign = '';
				$datacsstop = '';
				if( $field['image_center'] == 'yes' )
				{
					$arfimagealignclass = 'arf_image_horizontal';
					$arfimageleft		= '';
					$arfimagetop		= '';
					$arfimagealign 		= 'align="center"';
					
					if(isset($_SESSION['arfaction_ptype']) && $_SESSION['arfaction_ptype'] != 'list')
						$datacsstop = 'data-ctop="'.$field['image_top'].'"';
				}
				
				if( $field['image_center'] == 'yes' )						
					echo '<div class="arf_image_horizontal_center" '.$datacsstop.' style="top:'.$field['image_top'].';">';
				
				echo '<div id="arf_imagefield_'.$field['id'].'" class="arf_image_field '.$arfimagealignclass.'" '.$arfimagealign.' style="'.$arfimageleft.$arfimagetop.'"><img src="'.$field['image_url'].'" style="'.$arfheightwidth.'" /></div>';
				
				if( $field['image_center'] == 'yes' )
					echo '</div>';
			}
		}
	}	
}

?><div class="arf_fieldset"><?php
 
if( $title && $description )
{
	$arftitlecontent = '<div class="arftitlecontainer">'.$arformhelper->replace_shortcodes($form->options['before_html'], $form, $title, $description).'</div>';
	$arftitlecontent = $arformcontroller->arf_remove_br( $arftitlecontent );
	
	echo $arftitlecontent;
}

global $MdlDb, $arf_page_number, $page_break_hidden_array;
$arf_page_number = 0;		
$page_num = $MdlDb->get_count($MdlDb->fields, array("form_id" => $form->id, "type" => 'break'));

//---------- for conditional logic ----------//
$i = 1;
$field_page_break_type = '';
if($values['fields'] and $page_num > 0 ){
	
	$cntr_break = 0;
	
	
	foreach($values['fields'] as $field){
		if( $field['type'] == 'break' ){
			if($cntr_break==0 && $i==1) {
				$field_page_break_type = $field['page_break_type'];
			}
			$i++;
		}		
	}
	
	if( $field_page_break_type == 'survey' ){
		global $arf_survey_form_loaded;
		$arf_survey_form_loaded[$form->id] = $form->form_key;
		
		$total_page_shows = $page_num;
		foreach($values['fields'] as $field){
			if( $field['type'] == 'break' ){
				$display_temp = $arfieldhelper->get_display_style($field);
				if( !empty($display_temp) )
					$total_page_shows--;
				unset($display_temp);		
			}	
		}	
		echo '<div class="arf_survey_nav"><div id="current_survey_page" class="survey_step">'.__('Step', 'ARForms').' </div><div id="current_survey_page" class="current_survey_page">1</div><div class="survey_middle">'.__('of', 'ARForms').'</div><div id="total_survey_page" class="total_survey_page">'.($total_page_shows+1).'</div></div>';
		
				
        echo '<div style="clear:both; margin-top:25px;"></div>
        	<div id="arf_progress_bar" style="margin-bottom:20px; clear:both;" class="ui-progress-bar"></div>';
        		
	} else {
		global $arf_wizard_form_loaded;
		$arf_wizard_form_loaded[$form->id] = $form->form_key;
		
		$total_page_shows = $page_num;
		foreach($values['fields'] as $field){
			if( $field['type'] == 'break' ){
				$display_temp = $arfieldhelper->get_display_style($field);
				if( !empty($display_temp) )
					$total_page_shows--;
				unset($display_temp);		
			}	
		}
	}	
				
}

$i=1;
if($page_num>0)
{
	//$td_width_w = number_format((100/($page_num+1)),1);
	$td_width_w = number_format((100/($total_page_shows+1)),3);
	$td_width = $td_width_w."%";
}
if($values['fields'] and $page_num > 0 ){
	$enterrowdata = "";
	if( $field_page_break_type == 'wizard' )
	{
		echo '<div id="arf_wizard_table" class="arf_wizard">';
		echo '<div class="arf_wizard_upper_tab">';
	}
	$cntr_break = 0;
	foreach($values['fields'] as $field){
		$field_type = $field['type'];
		$field['id'] = $arfieldhelper->get_actual_id($field['id']);
		if($field_type=="break") {
			$first_page_break_field_val = $field; //first page break field
			$display_page_break = $arfieldhelper->get_display_style($field);
			if( empty($display_page_break) )
				$display_page_break = "";
			else
				$display_page_break = "display:none;";
					
			$field_first_page_label = $field['first_page_label'];
			$field_second_page_label = $field['second_page_label'];
			$field_pre_page_title = $field['pre_page_title'];
			if($cntr_break==0 && $i==1) {
				$field_page_break_type = $field['page_break_type'];
			}
			if($field_page_break_type=="wizard")
			{
				if($cntr_break==0 && $i==1) {
					
					echo '<div style="width:'.$td_width.';" id="page_nav_'.$i.'" class="page_break_nav page_nav_selected">'.$field_first_page_label.'</div>';
					$i++; 
					echo '<div style="width:'.$td_width.'; '.$display_page_break.'" id="page_nav_'.$i.'" class="page_break_nav">'.$field_second_page_label.'</div>';
					$cntr_break++;
				}else {
					echo '<div style="width:'.$td_width.'; '.$display_page_break.'" id="page_nav_'.$i.'" class="page_break_nav">'.$field_second_page_label.'</div>';
				}
				$i++;
				$enterrowdata ="<br>";
			}
		}
		$field_name = 'item_meta['. $field['id'] .']';
	}
	
	if( $field_page_break_type == 'wizard' )
	{
		echo '</div>';
	}
	
	$cntr_break = 0;
	$i=1;
	if( $field_page_break_type == 'wizard' )
	{
		echo '<div class="arf_wizard_lower_tab">';
	}
	
	foreach($values['fields'] as $field){
		$field_type = $field['type'];
		$field['id'] = $arfieldhelper->get_actual_id($field['id']);
		if($field_type=="break") {
			$field_first_page_label = $field['first_page_label'];
			$field_second_page_label = $field['second_page_label'];
			$field_pre_page_title = $field['pre_page_title'];
			if($cntr_break==0 && $i==1) {
				$field_page_break_type = $field['page_break_type'];
			}
			if($field_page_break_type=="wizard")
			{
				$display_temp = $arfieldhelper->get_display_style($field);
				$display = ( !empty($display_temp) ) ? 'display:none;' : '';
				
				if($cntr_break==0 && $i==1) {
					
					echo '<div style="width:'.$td_width.'; padding:0;" id="page_nav_arrow_'.$i.'" class="page_break_nav page_nav_selected"><div class="arf_current_tab_arrow"></div></div>';
					$i++; 
					echo '<div style="width:'.$td_width.';padding:0;'.$display.'" id="page_nav_arrow_'.$i.'" class="page_break_nav"></div>';
					$cntr_break++;
				}else {					 					
					echo '<div style="width:'.$td_width.';padding:0;'.$display.'" id="page_nav_arrow_'.$i.'" class="page_break_nav"></div>';
				}
				$i++;
				$enterrowdata ="<div class='arf_wizard_clear' style='clear:both; height:15px;'></div>";
			}
		}
		$field_name = 'item_meta['. $field['id'] .']';
	}
	//---------- for conditional logic ----------//
	if( $field_page_break_type == 'wizard' )
	{
		echo '</div>';
		echo '</div>'.$enterrowdata;
	}
}

?><div id="page_0" class="page_break"><?php  
$hiddenfield = '<input type="hidden" name="arfaction" value="'.esc_attr($form_action).'" />
<input type="hidden" name="form_id" id="form_id" value="'.esc_attr($form->id).'" />
<input type="hidden" name="form_key" id="form_key" value="'.esc_attr($form->form_key).'" />';
$hiddenfield = $arformcontroller->arf_remove_br( $hiddenfield );
echo $hiddenfield;

$pageURL = "";
$pageURL = get_permalink( get_the_ID() ); 
if($pageURL == "")
	$pageURL = site_url();

$hiddenfield = '<input type="hidden" name="form_display_type" id="form_display_type" value="'.(($is_widget_or_modal) ? 1 : 0).'|'.$pageURL.'" />';
echo $arformcontroller->arf_remove_br( $hiddenfield );
if (isset($id)){ 
	$hiddenfield = '<input type="hidden" name="id" value="'.esc_attr($id).'" />';
	echo $arformcontroller->arf_remove_br( $hiddenfield ); 
} 
$hiddenfield = '<input type="hidden" name="form_submit_type" id="form_submit_type" value="'.$arfsettings->form_submit_type.'" />';
echo $arformcontroller->arf_remove_br( $hiddenfield ); 

if (isset($controller) && isset($plugin)){ 
$hiddenfield = '<input type="hidden" name="controller" value="'.esc_attr($controller).'" />	
<input type="hidden" name="plugin" value="'.esc_attr($plugin).'" />';
echo $arformcontroller->arf_remove_br( $hiddenfield ); 
}
	global $wpdb;		
	
	$css_data_arr   = $form->form_css;
	
	$arr = maybe_unserialize($css_data_arr);
	
	$newarr = array();
	foreach($arr as $k => $v)
		$newarr[$k] = $v;

	$_SESSION['label_position'] = $newarr['position'];
	

if($values['fields']){

//get array for confirm password 
$arf_load_password = array();
$arf_load_confirm_email = array();

$values['confirm_password_arr'] = array();	
$values['confirm_email_arr'] = array();	

foreach($values['fields'] as $field)
{
	if( $field['type'] == 'password' )
	{
		$field['id'] = $arfieldhelper->get_actual_id($field['id']);		
		if( isset($field['confirm_password']) and $field['confirm_password'] == 1 and isset($arf_load_password['confrim_pass_field']) and $arf_load_password['confrim_pass_field'] == $field['id'] )
			$values['confirm_password_arr'][$field['id']] = isset($field['confirm_password_field'])?$field['confirm_password_field']:"";    
		else		
			$arf_load_password['confrim_pass_field'] = isset($field['confirm_password_field'])?$field['confirm_password_field']:"";
	}
	
	if( $field['type'] == 'email' )
	{
		$field['id'] = $arfieldhelper->get_actual_id($field['id']);		
		if( isset($field['confirm_email']) and $field['confirm_email'] == 1 and isset($arf_load_confirm_email['confrim_email_field']) and $arf_load_confirm_email['confrim_pass_field'] == $field['id'] )
			$values['confirm_email_arr'][$field['id']] = isset($field['confirm_email_field'])?$field['confirm_email_field']:"";    
		else		
			$arf_load_confirm_email['confrim_email_field'] = isset($field['confirm_email_field'])?$field['confirm_email_field']:"";
	}
		
}
$totalpass = 0;
foreach($values['fields'] as $arrkey => $field)
{
	if( $field['type'] == 'password' && $field['confirm_password'] )
	{
		$confirm_password_field = $arfieldhelper->get_confirm_password_field( $field );
		$values['fields'] = $arfieldhelper->array_push_after($values['fields'], array( $confirm_password_field ), $arrkey + $totalpass );
		$totalpass++;
	}
	
	if( $field['type'] == 'email' && $field['confirm_email'] )
	{
		$confirm_email_field = $arfieldhelper->get_confirm_email_field( $field );
		$values['fields'] = $arfieldhelper->array_push_after($values['fields'], array( $confirm_email_field ), $arrkey + $totalpass );
		$totalpass++;
	}
	
}

foreach($values['fields'] as $field){
	
	$field['id'] = $arfieldhelper->get_actual_id($field['id']); //---------- for conditional logic ----------//
	
    $field_name = 'item_meta['. $field['id'] .']';
	
	$field['confirm_password_arr'] = $values['confirm_password_arr']; 
		
	//apply filter before display field
	$field = apply_filters('arfbeforefielddisplay', $field);

	
	global $arf_captcha_loaded, $arf_file_loaded; 
	if( $field['type'] == 'captcha' )
		$arf_captcha_loaded++;
	else if( $field['type'] == 'file' )
		$arf_file_loaded++;	
	
    if (apply_filters('arfdisplayfieldtype', true, $field['type']))
	{
	
		if( $field['type'] != 'html' )
		{
			$field_content = $arfieldhelper->replace_field_shortcodes($arfieldhelper->get_basic_default_html($field['type']), $field, $errors, $form); 
        	$field_content = $arformcontroller->arf_remove_br( $field_content );			
			if( $field['type'] == 'file' && $is_loaded_normal )
			{
				echo '<arffile>'.$field_content.'</arffile>';				
			}
			else if( $field['type'] == 'imagecontrol' ) 
			{ 
			}
			else
			{
				echo $field_content;
			} 
		}
		else
		{
			echo $arfieldhelper->replace_field_shortcodes($arfieldhelper->get_basic_default_html($field['type']), $field, $errors, $form);
		}
	}
    else
	{
        do_action('arfdisplayfieldtype1', $field, $form, $page_num);
	}
	
	global $arf_column_classes;
	if( isset($field['classes']) and $field['classes'] == 'arf_2' and isset($arf_column_classes['two']) and $arf_column_classes['two']=='1'){
		echo '<div class="arf_half_middle"></div>';
	} else if( isset($field['classes']) and $field['classes'] == 'arf_3' and isset($arf_column_classes['three']) and $arf_column_classes['three'] == '1' ){
		echo '<div class="arf_half_middle"></div>';	
	} else if( isset($field['classes']) and $field['classes'] == 'arf_3' and isset($arf_column_classes['three']) and $arf_column_classes['three'] == '2' ){
		echo '<div class="arf_third_middle"></div>';	
	}
	
	//after field display
	do_action('arfafterdisplayfield', $field);
}    


}

if (is_admin()){ $hiddencontent = '<div><input type="hidden" name="entry_key" value="'.esc_attr($values['entry_key']).'" /></div>'; } else { $hiddencontent = '<div><input type="hidden" name="entry_key" value="'.esc_attr($values['entry_key']).'" /></div>'; }
$hiddencontent = $arformcontroller->arf_remove_br( $hiddencontent );
echo $hiddencontent;
		
do_action('arfentryform', $form, $form_action, $errors);

global $arf_section_div;
if($arf_section_div) {
	echo "<div class='arf_clear'></div></div>";
	$arf_section_div = 0;
}
	
global $arfdiv;


if($arfdiv){

    echo "</div>";

    $arfdiv = false;


} 
?><div style="clear:both;height:1px;">&nbsp;</div></div><?php 
global $MdlDb, $arf_page_number, $page_break_hidden_array;
$page_num = $MdlDb->get_count($MdlDb->fields, array("form_id" => $form->id, "type" => 'break'));

if( $page_num > 0 )
	$page_break_hidden_array[$form->id]['data-hide'] = ','.$page_break_hidden_array[$form->id]['data-hide'];

if (!$form->is_template and $form->id != ''){  

if( $page_num == 1 ){
	
	$display_temp = $arfieldhelper->get_display_style( $first_page_break_field_val );
	$display_submit = ( !empty($display_temp) ) ? '' : 'style="display:none;"';
	$display_previous = ( !empty($display_temp) ) ? 'style="display:none;"' : '';
	if( $display_submit == '' ){
		$is_submit_form = 0;
		$last_show_page = 0;
	} else {
		$is_submit_form = 1;
		$last_show_page = 1;	
	}
} else if( $page_num > 1 ){
	 $total_page_number = $arf_page_number;
	 $last_show_page = $arf_page_number;		 
	 $compare_value = explode(',', $page_break_hidden_array[$form->id]['data-hide'] );
	 
	 foreach($compare_value as $k1=>$v1){	
	 	if(is_null($v1) || $v1 == '')
			unset($compare_value[$k1]);
	 }
	  
	 for($i=0; $i <= $total_page_number; $i++ ){
	 	
		if( in_array($i, $compare_value) ) {
			continue;
		} else {
			$last_show_page = $i;
		}
		
	 }
	 
	 if( $last_show_page == 0 ){
	 	$display_submit = '';
		$display_previous = 'style="display:none;"';
		?><style type="text/css">.ar_main_div_<?php echo $form->id; ?> #arf_submit_div_0 { display:none; }</style><?php
		$is_submit_form = 0;
	 } else {
	 	$display_submit = 'style="display:none;"';
		$display_previous = 'style="display:none;"';
		$is_submit_form = 1;
	 }
	 
	 
} else {
	$display_submit = 'style="display:none;"'; 
	$display_previous = '';
	$is_submit_form = 1;
}

if(isset($preview) and $preview) { 
	global $style_settings;

		
	$aweber_arr = "";
	$aweber_arr   = $form->form_css;
	
	$arr = maybe_unserialize($aweber_arr);
	
	$newarr = array();
	foreach($arr as $k => $v)
		$newarr[$k] = $v;
    
	$submit_height = ($newarr['arfsubmitbuttonheightsetting']=='') ? '35' : $newarr['arfsubmitbuttonheightsetting'];	
    $padding_loading_tmp = $submit_height-24;
	$padding_loading = $padding_loading_tmp/2;
	
	$submit_width = @$newarr['arfsubmitbuttonwidthsetting'];
	
	$submit_width_loader  = ($submit_width=='') ? '1' : $submit_width;	
	$width_loader = ($submit_width_loader/2);
	$width_to_add = $submit_width_loader;
	$top_margin = $submit_height + 5;
	$label_margin = isset($newarr['width']) ? $newarr['width'] : 0;
	$label_margin = $label_margin + 15;
	
?><div class="arfsubmitbutton <?php echo $_SESSION['label_position'];?>_container" <?php if($arf_page_number > 0 and $page_num > 0){ echo 'id="page_last"'; echo $display_submit; } ?>><div class="arf_submit_div <?php echo $_SESSION['label_position'];?>_container"><?php 

if($arf_page_number > 0 and $page_num > 0 ) { 
	echo '<input type="button" value="'.$field_pre_page_title.'" '.$display_previous.' name="previous" id="previous_last" class="previous_btn" onclick="go_previous(\''.($arf_page_number-1).'\', \''.$form->id.'\', \'no\', \''.$form->form_key.'\');"  />';	
	echo '<input type="hidden" value="'.$arf_page_number.'" name="last_page_id" id="last_page_id"  />';
}

if($arf_page_number > 0 and $page_num > 0 ) { 	
	echo '<input type="hidden" value="1" name="is_submit_form_'.$form->id.'" id="is_submit_form_'.$form->id.'" />';
	echo '<input type="hidden" data-last="'.$last_show_page.'" value="'.$last_show_page.'" name="last_show_page_'.$form->id.'" id="last_show_page_'.$form->id.'" />';
	echo '<input type="hidden" value="'.$is_submit_form.'" data-val="1" data-hide="'.$page_break_hidden_array[$form->id]['data-hide'].'" data-max="'.$arf_page_number.'" name="submit_form_'.$form->id.'" id="submit_form_'.$form->id.'" />';//---------- for conditional logic ----------//
	echo '<input type="hidden" value="'.$page_break_hidden_array[$form->id]['data-hide'].'" name="get_hidden_pages_'.$form->id.'" id="get_hidden_pages_'.$form->id.'" />';
} else {
	echo '<input type="hidden" value="1" name="is_submit_form_'.$form->id.'" id="is_submit_form_'.$form->id.'" />';
	echo '<input type="hidden" value="0" data-val="0" data-max="0" name="submit_form_'.$form->id.'" id="submit_form_'.$form->id.'" />';
}

$submit = apply_filters('getsubmitbutton', $submit, $form);
$is_submit_hidden 	= false;
$submitbtnstyle 	= '';
$submitbtnclass 	= '';
if( $arfieldhelper->get_display_style_submit($form) != '' )
{
	$is_submit_hidden 	= true;
	$submitbtnclass 	= 'arfsubmitdisabled';
	$submitbtnstyle 	= 'disabled="disabled"';
}  
	$submit_btn_content ='<button class="arf_submit_btn btn btn-info arfstyle-button '.$submitbtnclass.'" data-style="zoom-in" '.$submitbtnstyle.'>
        <span class="arfstyle-label">'.esc_attr($submit).'</span>
        <span class="arfstyle-spinner">
            <div class="arflogo">
            	<div class="b arfred" ><div></div></div>
            	<div class="b arfblue" ><div></div></div>
            	<div class="b arfyellow" ><div></div></div>
            	<div class="b arfgreen" ><div></div></div>
            </div>
        </span>
        <span class="arf_ie_image" style="display:none;">';
        	if( ( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ) || $browser_info['name'] == 'Opera' ){ 
        		$submit_btn_content .= '<img src="'.ARFURL.'/images/submit_btn_image.GIF" style="width:24px; box-shadow:none; vertical-align:middle; height:24px; padding-top:'.$padding_loading.'px" />';
            } 
        $submit_btn_content .= '</span>        
	</button>';
	
	$submit_btn_content = $arformcontroller->arf_remove_br( $submit_btn_content );
	echo $submit_btn_content;	
?></div><input type="hidden" name="submit_btn_image" id="submit_btn_image" value="<?php echo ARFURL.'/images/submit_loading_img.gif'; ?>" /></div>
<div style="clear:both"></div>
<?php 
} else { 
?><div class="arfsubmitbutton <?php echo $_SESSION['label_position'];?>_container" <?php if($arf_page_number > 0 and $page_num > 0){ echo 'id="page_last" '; echo $display_submit; } ?>><div class="arf_submit_div <?php echo $_SESSION['label_position'];?>_container"><?php

if($arf_page_number > 0 and $page_num > 0 ) { 
	echo '<input type="button" value="'.$field_pre_page_title.'" '.$display_previous.' name="previous" id="previous_last" class="previous_btn" onclick="go_previous(\''.($arf_page_number-1).'\', \''.$form->id.'\', \'no\', \''.$form->form_key.'\');"  />';		
	echo '<input type="hidden" value="'.$arf_page_number.'" name="last_page_id" id="last_page_id" />';
}

if($arf_page_number > 0 and $page_num > 0 ) { 	
	echo '<input type="hidden" value="1" name="is_submit_form_'.$form->id.'" id="is_submit_form_'.$form->id.'" />';
	echo '<input type="hidden" data-last="'.$last_show_page.'" value="'.$last_show_page.'" name="last_show_page_'.$form->id.'" id="last_show_page_'.$form->id.'" />';
	echo '<input type="hidden" value="'.$is_submit_form.'" data-val="1" data-hide="'.$page_break_hidden_array[$form->id]['data-hide'].'" data-max="'.$arf_page_number.'" name="submit_form_'.$form->id.'" id="submit_form_'.$form->id.'" />';//---------- for conditional logic ----------//
	echo '<input type="hidden" value="'.$page_break_hidden_array[$form->id]['data-hide'].'" name="get_hidden_pages_'.$form->id.'" id="get_hidden_pages_'.$form->id.'" />';
} else {
	echo '<input type="hidden" value="1" name="is_submit_form_'.$form->id.'" id="is_submit_form_'.$form->id.'" />';	
	echo '<input type="hidden" value="0" data-val="0" data-max="0" name="submit_form_'.$form->id.'" id="submit_form_'.$form->id.'" />';
}

$submit = apply_filters('getsubmitbutton', $submit, $form);
$is_submit_hidden 	= false;
$submitbtnstyle 	= '';
$submitbtnclass 	= '';
if( $arfieldhelper->get_display_style_submit($form) != '' )
{
	$is_submit_hidden 	= true;
	$submitbtnclass 	= 'arfsubmitdisabled';
	$submitbtnstyle 	= 'disabled="disabled"';
}

$submit_btn_content = '';
if( $is_loaded_normal )
	$submit_btn_content .= '<arfsubmit>';
	
$submit_btn_content .= '<button class="arf_submit_btn btn btn-info arfstyle-button '.$submitbtnclass.'" data-style="zoom-in" '.$submitbtnstyle.'>';

$submit_btn_content .= '<span class="arfstyle-label">'.esc_attr($submit).'</span>';
		if( ( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ) || $browser_info['name'] == 'Opera' ){ 
			$padding_loading = isset($padding_loading) ? $padding_loading : '';
			$submit_btn_content .= '<span class="arf_ie_image" style="display:none;">';
			$submit_btn_content .= '<img src="'.ARFURL.'/images/submit_btn_image.GIF" style="width:24px; box-shadow:none; vertical-align:middle; height:24px; padding-top:'.$padding_loading.'px;"/>';
			$submit_btn_content .= '</span>';   
		} 
		
$submit_btn_content .= '<span class="arfstyle-spinner"><div class="arflogo"><div class="b arfred"><div></div></div><div class="b arfblue"><div></div></div><div class="b arfyellow"><div></div></div><div class="b arfgreen"><div></div></div></div></span>';
        		             
	 	$submit_btn_content .= '</button>';
if( $is_loaded_normal )
	$submit_btn_content .= '</arfsubmit>';
		
	$submit_btn_content = $arformcontroller->arf_remove_br($submit_btn_content);
	echo $submit_btn_content;
	  
	?></div></div><div style="clear:both"></div><?php 
	}  
} else { ?>
<p class="arfsubmitbutton <?php echo $_SESSION['label_position'];?>_container">
<?php $submit = apply_filters('getsubmitbutton', $submit, $form); ?>
<input type="submit" value="<?php echo esc_attr($submit) ?>" onclick="return false;" <?php do_action('arfactionsubmitbutton', $form, $form_action); ?> />
<div id='submit_loader' class="submit_loader" style="display:none;"></div>
</p>
<?php } 

$arfoptions = get_option( "arf_options" ); 

$remove_status = ($arfoptions->brand);

if($remove_status==0) { 

?><div id="brand-div" class="brand-div <?php echo $_SESSION['label_position'];?>_container" style="margin-top:20px; font-size:12px !important; display:block !important;"><?php _e('Powered by','ARForms');?>&nbsp;<a href="http://codecanyon.net/item/arforms-exclusive-wordpress-form-builder-plugin/6023165?ref=reputeinfosystems" target="_blank" style="margin:20px 0;">ARForms</a><?php   $licact = 0;
				
				$licact = 0;
				global $arformsplugin;
				global $arfmsgtounlicop;
				$licact = $arformcontroller->$arformsplugin();
				
				if($licact == 0) { ?>
                <span style="color:#FF0000; font-size:12px !important; display:block !important;"><?php _e('&nbsp;&nbsp;'.$arfmsgtounlicop,'ARForms');?></span>
				<?php } ?></div><?php }
?></div></div><?php 

echo $arformhelper->replace_shortcodes(@$values['after_html'], $form); 

global $wp_filter;

if(isset($wp_filter['arfentriesfooterscripts']) and !empty($wp_filter['arfentriesfooterscripts'])){ 
	?><script type="text/javascript"><?php do_action('arfentriesfooterscripts', $values['fields'], $form); ?></script><?php 
} ?>