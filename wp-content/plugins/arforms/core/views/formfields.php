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


global $wpdb, $armainhelper, $arfieldhelper, $arformcontroller, $arrecordcontroller;

if( $field['form_id'] >= 10000 )
	$data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_ref_forms WHERE id = %d", $field['form_id']), 'ARRAY_A');
else	
	$data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $field['form_id']), 'ARRAY_A');

$aweber_arr = "";
$aweber_arr   = $data[0]['form_css'];

$newarr = array();

if($aweber_arr != "") {
$arr = unserialize($aweber_arr);

foreach($arr as $k => $v)
	$newarr[$k] = $v;
}
	

if( isset($field['inline_css']) and $field['inline_css'] != '' ){
	$inline_css_with_style_tag = ' style="'.stripslashes_deep( $armainhelper->esc_textarea( $field['inline_css'] ) ).'" ';
	$inline_css_without_style  = stripslashes_deep( $armainhelper->esc_textarea( $field['inline_css'] ) ); 
} else
	$inline_css_with_style_tag = $inline_css_without_style = '';
	
if (in_array($field['type'], array('date'))){ 
?><div class="controls" <?php if( isset($field['field_width']) and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>><?php
if(apply_filters('arf_check_for_draw_outside',false,$field))
{
	do_action('arf_drawthisfieldfromoutside',$field);
}
else
{
	if( $field['enable_arf_prefix'] == 1 || $field['enable_arf_suffix'] == 1){
		
		?>
			<div class="arf_prefix_suffix_wrapper">
		<?php
			if( $field['enable_arf_prefix'] == 1 ){
		?>
				<span id="arf_prefix_<?php echo $field['field_key']; ?>" class="arf_prefix" onclick="arfFocusInputField('<?php echo $field['field_key'] ?>');"><i class="fa <?php echo $field['arf_prefix_icon'] ?>"></i></span>
		<?php
			}
	}

?><input type="text" id="field_<?php echo $field['field_key'] ?>" name="<?php echo $field_name ?>" <?php do_action('arffieldinputhtml', $field) ?> <?php if((isset($field['clear_on_focus']) and $field['clear_on_focus'] and !empty($field['default_value']))){ if($field['default_blank']==1){?> value="<?php echo esc_attr($field['value']) ?>" <?php }else{?> value="" <?php }}else{ if($field['default_blank']==1){?> value="<?php echo esc_attr($field['value']) ?>" <?php }else{ ?> placeholder="<?php echo esc_attr($field['value']) ?>" <?php }}?> <?php if( isset($field['field_width']) and $field['field_width']!='' and $field['enable_arf_prefix'] != 1 and $field['enable_arf_suffix'] != 1) { echo 'style="width:'.$field['field_width'].'px !important '.$inline_css_without_style.'"'; } else { echo $inline_css_with_style_tag; } ?> <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> <?php echo $arfieldhelper->get_onchage_func($field); ?> /><?php 
	
	if( $field['enable_arf_prefix'] == 1 || $field['enable_arf_suffix'] == 1){
		
			if( $field['enable_arf_suffix'] == 1 ){
			?>
					<span id="arf_suffix_<?php echo $field['field_key']; ?>" class="arf_suffix" onclick="arfFocusInputField('<?php echo $field['field_key'] ?>');"><i class="fa <?php echo $field['arf_suffix_icon'] ?>"></i></span>
			<?php
			}
	?>
				</div>
	<?php
		}
if ($field['type'] == 'date' and (!isset($field['read_only']) or !$field['read_only'])){ 

global $arfdatepickerloaded;

if(!is_array($arfdatepickerloaded)) $arfdatepickerloaded = array();

$arfdatepickerloaded['field_'. $field['field_key']] = array(

    'start_year' => $field['start_year'], 'end_year' => $field['end_year'], 

    'locale' => $field['locale'], /*'unique' => @$field['unique'],*/ 'entry_id' => $entry_id,

    'field_id' => $field['id'], 'date_format' => $newarr['date_format'],
	
	'show_year_month_calendar' => $field['show_year_month_calendar'],

);

//calender localization
if($field['locale'] != '')
{
	wp_register_script('locale-js', ARFURL . '/js/jquery-ui-i18n.js',array('jquery'));
	if(!empty($arfdatepickerloaded) and is_array($arfdatepickerloaded))
	{
		//wp_enqueue_script('locale-js');
		echo "<script type='text/javascript' src='".ARFURL."/js/jquery-ui-i18n.js'></script>";
	}
}
//calender localization

}
}  
echo $arfieldhelper->replace_description_shortcode($field); 
?></div><?php 
}else if($field['type'] == 'time'){

if( isset($field['field_width']) and $field['field_width']!='') 
{ ?>
	<style>
	.ar_main_div_<?php echo $field['form_id'];?> .time_controll_<?php echo $field['id'];?>:not([class*="span"]):not([class*="col-"]):not([class*="form-control"])
	{
	width:<?php echo $field['field_width'];?>px !important;
	}
	</style>
	<?php
}
?>
<div class="sltstandard_time controls" <?php if( isset($field['field_width']) and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>>
<?php
if(apply_filters('arf_check_for_draw_outside',false,$field))
{
	do_action('arf_drawthisfieldfromoutside',$field);
}
else
{
	if( $field['enable_arf_prefix'] == 1 || $field['enable_arf_suffix'] == 1){
		
		?>
        	<div class="arf_prefix_suffix_wrapper">
        <?php
			if( $field['enable_arf_prefix'] == 1 ){
		?>
        		<span id="arf_prefix_<?php echo $field['field_key']; ?>" class="arf_prefix" onclick="arfFocusInputField('<?php echo $field['field_key'] ?>');"><i class="fa <?php echo $field['arf_prefix_icon'] ?>"></i></span>
        <?php
			}
	}
?><input type="text" name="<?php echo $field_name ?>" class="arf_timepciker" id="field_<?php echo $field['field_key'] ?>" value="" <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> <?php echo $arfieldhelper->get_onchage_func($field); ?> /><?php 
	if( $field['enable_arf_prefix'] == 1 || $field['enable_arf_suffix'] == 1){
	
		if( $field['enable_arf_suffix'] == 1 ){
		?>
				<span id="arf_suffix_<?php echo $field['field_key']; ?>" class="arf_suffix" onclick="arfFocusInputField('<?php echo $field['field_key'] ?>');"><i class="fa <?php echo $field['arf_suffix_icon'] ?>"></i></span>
		<?php
		}
	?>
			</div>
	<?php
	}
global $arftimepickerloaded;

$arftimepickerloaded['field_'. $field['field_key']] = array(

    'clock' => ((isset($field['clock']) and $field['clock'] == 24) ? true : false),  

    'step' => $field['step'], 'start_time' => $field['start_time'], 'end_time' => $field['end_time'], 'default_hour' => $field['default_hour'], 'default_minutes' => $field['default_minutes'],

    /*'unique' => @$field['unique'],*/ 'entry_id' => $entry_id

);

}
echo $arfieldhelper->replace_description_shortcode($field); 
?></div><?php  
  
}else if(in_array($field['type'], array('email', 'url', 'number', 'password', 'phone', 'confirm_password','confirm_email'))){ 

    $field['type'] = ($field['type'] == 'phone') ? 'tel' : $field['type']; ?>
    <?php
    $regex = "((https?|ftp)\:\/\/)?"; // SCHEME
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
    $regex .= "(\:[0-9]{2,5})?"; // Port
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
	
	if( $field['type'] == 'tel' )
		$field['phone_validation'] = $field['phone_validation'] ? $field['phone_validation'] : 'international';	
?><div class="controls" <?php if( isset($field['field_width']) and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>>
<?php
if(apply_filters('arf_check_for_draw_outside',false,$field))
{
	do_action('arf_drawthisfieldfromoutside',$field);
}
else
{
	$confirm_password_field = '0';
	if( $field['type'] == 'password' and isset($field['confirm_password_arr'][$field['id']]) and $field['confirm_password_arr'][$field['id']] != '' )
	{
		$confirm_password_field = $field['confirm_password_arr'][$field['id']]; 	
	}
	if( $field['type'] == 'confirm_password' ){
		$field['value'] = $field['password_placeholder']; 
		$field['default_value'] = $field['password_placeholder']; 
	}
	if( $field['enable_arf_prefix'] == 1 || $field['enable_arf_suffix'] == 1){
		
		?>
			<div class="arf_prefix_suffix_wrapper">
		<?php
			if( $field['enable_arf_prefix'] == 1 ){
		?>
				<span id="arf_prefix_<?php echo $field['field_key']; ?>" class="arf_prefix" onclick="arfFocusInputField('<?php echo $field['field_key'] ?>');"><i class="fa <?php echo $field['arf_prefix_icon'] ?>"></i></span>
		<?php
			}
	}
	
	
	$confirm_email_field = '0';
	if( $field['type'] == 'email' and isset($field['confirm_email_arr'][$field['id']]) and $field['confirm_email_arr'][$field['id']] != '' )
	{
		$confirm_password_field = $field['confirm_email_arr'][$field['id']]; 	
	}
	if( $field['type'] == 'confirm_email' ){
		$field['value'] = $field['confirm_email_placeholder']; 
		$field['default_value'] = $field['confirm_email_placeholder']; 
	}
		
?><input
	 type="<?php  echo ( ($arfsettings->use_html or $field['type'] == 'password' or $field['type'] == 'confirm_password' ) and $field['type'] != 'number' and $field['type'] != 'email' and $field['type'] != 'confirm_email' ) ? ( $field['type'] == 'confirm_password' ? 'password' : $field['type'] )  : 'text'; ?>" id="field_<?php echo $field['field_key'] ?>" <?php if($field['type']=='number'){?>dir="<?php if($field['text_direction'] == '0') echo 'rtl'; else echo 'ltr'; ?>" <?php } ?>  name="<?php echo $field_name ?>" <?php if((isset($field['clear_on_focus']) and $field['clear_on_focus'] and !empty($field['default_value']))){ if($field['default_blank']==1){?>  value="<?php echo esc_attr($field['value']) ?>" <?php }else{?> value="" <?php }}else{ if($field['default_blank']==1){?> value="<?php echo esc_attr($field['value']) ?>" <?php }else{ ?> placeholder="<?php echo esc_attr($field['value']) ?>" <?php }}?> <?php do_action('arffieldinputhtml', $field) ?> <?php if( isset($field['field_width']) and $field['field_width']!='' and ( $field['enable_arf_prefix'] != 1 || $field['enable_arf_suffix'] != 1 ) ) { echo 'style="width:'.$field['field_width'].'px !important; '.$inline_css_without_style.'"'; }  else { echo $inline_css_with_style_tag; } ?> <?php if($field['type']=='email'){ if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?>  data-validation-regex-regex="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}" 
    data-validation-regex-message="<?php echo esc_attr($field['invalid']);?>" <?php }?> <?php if($field['type']=='url'){ if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> data-validation-regex-regex="<?php echo $regex;?>" data-validation-regex-message="<?php echo esc_attr($field['invalid']);?>" <?php }?> <?php if($field['type']=='number'){ if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> <?php if($field['type']=='number' && $field["maxnum"]!= "" && $field["maxnum"]>0 ){ ?>data-validation-max-message="<?php echo esc_attr($field['invalid']);?>" <?php } ?> onkeydown="arfvalidatenumber(this,event);" <?php } if(($field['type']=='password' || $field['type']=='number') and $field['minlength'] != ''){?>minlength="<?php echo $field['minlength'];?>" data-validation-minlength-message="<?php echo esc_attr($field['minlength_message']);?>"<?php } if($field['type']=='tel'){ if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }
	if( $field['phone_validation'] == 'international' )
	{
	?> data-validation-number-message="<?php echo esc_attr($field['invalid']);?>" <?php
	} else {
		global $arf_inputmask_loaded;
		$arf_inputmask_loaded['field_'.$field['field_key']] = $field['field_key'];
				
		if( $field['phone_validation'] == 'custom_validation_1' )
		{					
			$phone_regex 	= "^[(]{1}[0-9]{3,4}[)]{1}[0-9]{3}[\s]{1,1}[0-9]{4}$";
			$inputmask	 	= "(999)999 9999";
		}
		else if( $field['phone_validation'] == 'custom_validation_2' )
		{		
			$phone_regex 	= "^[(]{1}[0-9]{3,4}[)]{1}[\s]{1}[0-9]{3}[\s]{1}[0-9]{4}$";
			$inputmask	 	= "(999) 999 9999";
		}
		else if( $field['phone_validation'] == 'custom_validation_3' )		
		{
			$phone_regex 	= "^[(]{1}[0-9]{3,4}[)]{1}[0-9]{3}[-]{1}[0-9]{4}$";
			$inputmask	 	= "(999)999-9999";
		}
		else if( $field['phone_validation'] == 'custom_validation_4' )		
		{
			$phone_regex 	= "^[(]{1}[0-9]{3,4}[)]{1}[\s]{1}[0-9]{3}[-]{1}[0-9]{4}$";
			$inputmask	 	= "(999) 999-9999";
		}
		else if( $field['phone_validation'] == 'custom_validation_5' )
		{		
			$phone_regex 	= "^[0-9]{3,4}[\s]{1}[0-9]{3}[\s]{1}[0-9]{4}$";
			$inputmask	 	= "999 999 9999";
		}
		else if( $field['phone_validation'] == 'custom_validation_6' )
		{		
			$phone_regex 	= "^[0-9]{3,4}[\s]{1}[0-9]{3}[-]{1}[0-9]{4}$";
			$inputmask	 	= "999 999-9999";
		}
		else if( $field['phone_validation'] == 'custom_validation_7' )
		{		
			$phone_regex 	= "^[0-9]{3,4}[-]{1}[0-9]{3}[-]{1}[0-9]{4}$";
			$inputmask	 	= "999-999-9999";
		} 					
		?>data-validation-regex-regex="<?php echo $phone_regex;?>" data-mask="<?php echo $inputmask;?>" data-validation-regex-message="<?php echo esc_attr($field['invalid']);?>"<?php
	} 
 
} if($field['type']=='password'){ if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }}  
echo $arfieldhelper->get_onchage_func($field); 
if( $field['type'] == 'confirm_password' ){ ?>data-validation-match-match="item_meta[<?php echo $field['confirm_password_field']; ?>]" data-cpass="1" data-validation-match-message="<?php echo $field['invalid']; ?>" class="arf_password_field" <?php } ?> 

<?php
if($field['type']=='email'){ if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }}  
echo $arfieldhelper->get_onchage_func($field); 
if( $field['type'] == 'confirm_email' ){ ?>data-validation-match-match="item_meta[<?php echo $field['confirm_email_field']; ?>]" data-cpass="1" data-validation-match-message="<?php echo $field['invalid']; ?>"  <?php } ?> 

/><?php 
if( $field['enable_arf_prefix'] == 1 || $field['enable_arf_suffix'] == 1 ){
		if( $field['enable_arf_suffix'] == 1 ){
			?>
					<span id="arf_suffix_<?php echo $field['field_key']; ?>" class="arf_suffix" onclick="arfFocusInputField('<?php echo $field['field_key'] ?>');"><i class="fa <?php echo $field['arf_suffix_icon'] ?>"></i></span>
			<?php
			}
	?>
				</div>
	<?php
		}




if( $field['type']=='password' and isset($field['password_strength']) and $field['password_strength'] == 1 ){ 
	global $arf_password_loaded;
	$arf_password_loaded['field_'.$field['field_key']] = $field['field_key']; 
	$hiddencontent = '';
	global $arforms_loaded;
	$is_loaded_normal = ($arforms_loaded[ $field['form_id'] ]) ? true : false; 
	
	if( $is_loaded_normal )
		$hiddencontent .= '<arfpassword>';
		 	
	$hiddencontent .= '<div id="strenth_meter_'.$field['field_key'].'" class="arf_strenth_mtr">
    	<div class="inside_title">'.__('Strength indicator', 'ARForms').'</div>
    	<div class="arf_strenth_meter">
        	<div class="arfp_box"></div>
            <div class="arfp_box"></div>
            <div class="arfp_box"></div>
            <div class="arfp_box"></div>
            <div class="arfp_box"></div>
        </div>
    </div>';
	if( $is_loaded_normal )
		$hiddencontent .= '</arfpassword>';
		
	$hiddencontent = $arformcontroller->arf_remove_br( $hiddencontent );
	echo $hiddencontent;
 
	} 
 }  

echo $arfieldhelper->replace_description_shortcode($field); 
?></div><?php 

$field['type'] = ($field['type'] == 'tel') ? 'phone' : $field['type'];

}else if ($field['type'] == 'image'){?>
<div class="controls" <?php if( isset($field['field_width']) and $field['field_width']!='' ) { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>>
<?php
if(apply_filters('arf_check_for_draw_outside',false,$field))
{
	do_action('arf_drawthisfieldfromoutside',$field);
}
else
{
	if( $field['enable_arf_prefix'] == 1 || $field['enable_arf_suffix'] == 1){
		
		?>
        	<div class="arf_prefix_suffix_wrapper">
        <?php
			if( $field['enable_arf_prefix'] == 1 ){
		?>
        		<span id="arf_prefix_<?php echo $field['field_key']; ?>" class="arf_prefix" onclick="arfFocusInputField('<?php echo $field['field_key'] ?>');"><i class="fa <?php echo $field['arf_prefix_icon'] ?>"></i></span>
        <?php
			}
	}
?>
<input type="url" id="field_<?php echo $field['field_key'] ?>" name="<?php echo $field_name ?>" <?php if((isset($field['clear_on_focus']) and $field['clear_on_focus'] and !empty($field['default_value']))){ if($field['default_blank']==1){?> value="<?php echo esc_attr($field['value']) ?>" <?php }else{?> value="" <?php }}else{ if($field['default_blank']==1){?> value="<?php echo esc_attr($field['value']) ?>" <?php }else{ ?> placeholder="<?php echo esc_attr($field['value']) ?>" <?php }}?> <?php do_action('arffieldinputhtml', $field) ?> <?php if( isset($field['field_width']) and $field['field_width']!='' and $field['enable_arf_prefix'] != 1 and $field['enable_arf_suffix'] != 1 ){ echo 'style="width:'.$field['field_width'].'px !important;  '.$inline_css_without_style.'"'; } else { echo $inline_css_with_style_tag; } ?>  <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; } ?> <?php echo $arfieldhelper->get_onchage_func($field); ?> /><?php 
	if( $field['enable_arf_prefix'] == 1 || $field['enable_arf_suffix'] == 1){
	
		if( $field['enable_arf_suffix'] == 1 ){
		?>
        		<span id="arf_suffix_<?php echo $field['field_key']; ?>" class="arf_suffix" onclick="arfFocusInputField('<?php echo $field['field_key'] ?>');"><i class="fa <?php echo $field['arf_suffix_icon'] ?>"></i></span>
        <?php
		}
?>
			</div>
<?php
	}
}  
echo $arfieldhelper->replace_description_shortcode($field); 
?></div><?php 

}else if ($field['type'] == 'file'){

$file_extention = get_allowed_mime_types();
$file_ext = '';

if(!empty($field['ftypes']) && $field['restrict'] == '1') 
{
	$field_types = $field['ftypes'];
	$i=0;
	foreach($field_types as $field_type)
	{
		if($i== 0)
			$ftype = $field_type;
		else
			$ftype = $ftype.",".$field_type;
		$i++;
		
		foreach($file_extention as $ext => $file_type_name){
			if( $file_type_name == $field_type )
				$file_ext .= $ext.', ';
		}
	}
} else {

	$field_types = get_allowed_mime_types();
	$i=0;
	foreach($field_types as $field_type)
	{
		if( $field_type != 'application/x-msdownload' ){
			if($i== 0)
				$ftype = $field_type;
			else
				$ftype = $ftype.",".$field_type;
			$i++;
			
			foreach($file_extention as $ext => $file_type_name){
				if( $file_type_name == $field_type )
					$file_ext .= $ext.', ';
			}
			
		}
	}
} 

global $arfsettings;

if( isset($field['field_width']) and $field['field_width']!='' ){
	$file_field_width = $field['field_width'].'px'; 	
}

$filestyle = '<style type="text/css">#arf_field_'.$field['id'].'_container .arfajax-file-upload { background: '.$field['upload_btn_color'].'; color: '.$field['upload_font_color'].'; }</style>';
$filestyle = $arformcontroller->arf_remove_br( $filestyle );	
echo $filestyle;
 
if( $arfsettings->form_submit_type == 1 ){ ?><div class="controls" <?php if( isset($field['field_width']) and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>><?php

if(apply_filters('arf_check_for_draw_outside',false,$field))
{
	do_action('arf_drawthisfieldfromoutside',$field);
}
else
{
$browser_info = $arrecordcontroller->getBrowser($_SERVER['HTTP_USER_AGENT']);
?><div class="file_main_control" style="display:inline-block; <?php if( isset($field['field_width']) and $field['field_width']!='' ){ echo "width:".$field['field_width'].'px'; } ?>">
        <div class="arf_file_field"><div class="<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ echo 'original_btn'; }?> arfajax-file-upload" id="div_<?php echo $field['field_key'] ?>" data-id="<?php echo $field['id'] ?>" form-id="<?php echo $field['form_id'];?>" style="position: relative; overflow: hidden; float:left; cursor:pointer;"><?php echo '<div class="arfajax-file-upload-img" style="float:left;">&nbsp;</div>&nbsp;'.$field['file_upload_text']; 
                if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){
                    echo '<div id="'.$field['field_key'].'_iframe_div"><iframe id="'.$field['field_key'].'_iframe" src="'.ARFURL.'/core/views/iframe.php"></iframe></div>';
                    ?><input type="text" <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> class="original" name="file<?php echo $field['id'] ?>" id="field_<?php echo $field['field_key'] ?>" <?php do_action('arffieldinputhtml', $field) ?> form-id="<?php echo $field['form_id'];?>" file-valid="true" data-invalid-message="<?php echo esc_attr($field['invalid']);?>" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height:100%; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" /><?php
                    echo '<input type="hidden" id="type_'.$field['field_key'].'" name="type_'.$field['field_key'].'" value="1" >';
					echo '<input type="hidden" value="'.$file_ext.'" id="file_types_'.$field['field_key'].'" name="field_types_'.$field['field_key'].'" />';
												
                } else {		
                ?><input type="file" <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> class="file original" name="file<?php echo $field['id'] ?>" id="field_<?php echo $field['field_key'] ?>" data-invalid-message="<?php echo esc_attr($field['invalid']);?>" form-id="<?php echo $field['form_id'];?>" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; outline:none; right:0; z-index: 100; opacity: 0; width:100%" />
                <input type="hidden" id="type_<?php echo $field['field_key'] ?>" name="type_<?php echo $field['field_key'] ?>" value="0" >
                <input type="hidden" value="<?php echo $file_ext;?>" id="file_types_<?php echo $field['field_key'] ?>" name="field_types_<?php echo $field['field_key']?>" /><?php 
				} 
?></div>
            <div id="remove_<?php echo $field['field_key'] ?>" class="ajax-file-remove" style="display:none; position: relative; overflow: hidden; float:left;" form-id="<?php echo $field['form_id']; ?>" data-id="<?php echo $field['id']; ?>" ><img src="<?php echo ARFIMAGESURL.'/remove-icon.png';?>" align="absmiddle" /> &nbsp;<?php echo $field['file_remove_text'];?></div>
		
            <div id="progress_<?php echo $field['field_key'] ?>" class="progress progress-striped active">
                <div class="bar" style="width:0%;"></div>
            </div>
            
        </div>    
        <div id="info_<?php echo $field['field_key'] ?>" class="arf_info arf_file_field" style="display:none;">
            <span id="file_name" class="file_name"></span>
            <span class="percent">% Completed</span>        
            <span id="percent" class="percent">0</span>
        </div>
    </div>
<?php } 
echo $arfieldhelper->replace_description_shortcode($field); ?></div><?php } else { ?>
<div class="controls" <?php if( isset($field['field_width']) and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>>
<?php
if(apply_filters('arf_check_for_draw_outside',false,$field))
{
	do_action('arf_drawthisfieldfromoutside',$field);
}
else
{
?><div class="file_main_control" style="display:inline-block; <?php if( isset($field['field_width']) and $field['field_width']!='' ){ echo "width:".$field['field_width'].'px'; } ?>">    
        <div class="arf_file_field">         
            <div class="arfajax-file-upload" id="divi_<?php echo $field['field_key'] ?>" style="position: relative; overflow: hidden; float:left; cursor: pointer;"><div class="arfajax-file-upload-img">&nbsp;</div>&nbsp;<?php echo $field['file_upload_text'];?><input type="file" <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> class="original_normal" name="file<?php echo $field['id'] ?>" id="field_<?php echo $field['field_key'] ?>" <?php //do_action('arffieldinputhtml', $field) ?>  data-invalid-message="<?php echo esc_attr($field['invalid']);?>" form-id="<?php echo $field['form_id'];?>" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                <?php $browser_info = $arrecordcontroller->getBrowser($_SERVER['HTTP_USER_AGENT']); ?>
                <input type="hidden" value="<?php echo $file_ext;?>" id="file_types_<?php echo $field['field_key'] ?>" name="field_types_<?php echo $field['field_key']?>"  />
            </div>
            <div id="file_name_<?php echo $field['field_key'] ?>" class="file_name_info"><?php _e('No file selected', 'ARForms'); ?></div>        
        </div>    
    </div>
	<?php } ?>    
    <?php echo $arfieldhelper->replace_description_shortcode($field); ?>	
</div>    
<?php } ?>
<input type="hidden" name="<?php echo $field_name ?>" value="<?php echo esc_attr($field['value']) ?>" />
<?php echo $arfieldhelper->get_file_icon($field['value']);

} if ($field['type'] == 'scale'){

    require(VIEWS_PATH .'/star_rating.php');

    if(isset($field['star']) and $field['star']){

        global $arfstarloaded;

        if(!is_array($arfstarloaded))

            $arfstarloaded = array(true);

    }

}else if($field['type'] == 'like'){
	
	require(VIEWS_PATH .'/like_field.php');

}else if($field['type'] == 'form'){

    echo 'FRONT FORM';

} ?>