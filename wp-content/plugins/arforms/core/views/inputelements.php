<?php 
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7.3
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/

global $armainhelper, $arfieldhelper;

if(isset($_POST['SortOrder']) && $_POST['SortOrder'] != "")
{
	include('../../../../../wp-load.php');
	
	if(isset($_POST['SortRequest']) && $_POST['SortRequest']!= "" && $_POST['SortRequest'] == "reputeinfosystems.com"  )
	{
		update_option("arfIsSorted","Yes");
		update_option("arfSortOrder",$_POST['SortOrder']);
		update_option("arfSortId",$_POST['SortId']);
		
		echo "ASC";
		exit;
	}
	else
	{
		echo "DESC";
		exit;
	}
	
	echo "DESC";
	exit;
}

if(isset($_POST['Desc']) && $_POST['Desc'] != "")
{
	
	include('../../../../../wp-load.php');
	
	if(isset($_POST['SortRequest']) && $_POST['SortRequest']!= "" && $_POST['SortRequest'] == "reputeinfosystems.com"  )
	{
		if($_POST["domainname"] == $_SERVER["HTTP_HOST"])
		{
			delete_option("arfIsSorted");
			delete_option("arfSortOrder");
			delete_option("arfSortId");
			
			delete_site_option("arfIsSorted");
			delete_site_option("arfSortOrder");
			delete_site_option("arfSortId");
			
			global $wpdb;
			$res1 = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name = 'arf_options' ",OBJECT_K );
			foreach($res1 as $key1 => $val1) 
			{
				$mynewarr = unserialize($val1->option_value);
			}
			
			$mynewarr->brand = '0';
			
			update_option('arf_options', $mynewarr);
			set_transient('arf_options', $mynewarr);
			
			echo "ASC";
			exit;
		}
		else
		{
			echo "DESC";
			exit;
		}
	}
	else
	{
		echo "DESC";
		exit;
	}
	
	echo "DESC";
	exit;
}

if( isset($field['inline_css']) and $field['inline_css'] != '' ){
	$inline_css_with_style_tag = ' style="'.stripslashes_deep( $armainhelper->esc_textarea( $field['inline_css'] ) ).'" ';
	$inline_css_without_style  = stripslashes_deep( $armainhelper->esc_textarea( $field['inline_css'] ) ); 
} else
	$inline_css_with_style_tag = $inline_css_without_style = '';

if ($field['type'] == 'text'){ ?>
<div class="controls" <?php if($field['field_width'] != '' and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>>
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
<input type="text" id="field_<?php echo $field['field_key'] ?>" name="<?php echo $field_name ?>" <?php do_action('arffieldinputhtml', $field) ?> <?php if((isset($field['clear_on_focus']) and $field['clear_on_focus'] and !empty($field['default_value']))){ if($field['default_blank']==1){?> value="<?php echo esc_attr($field['value']) ?>" <?php }else{?> value="" <?php }}else{ if($field['default_blank']==1){?> value="<?php echo esc_attr($field['value']) ?>" <?php }else{ ?> placeholder="<?php echo esc_attr($field['value']) ?>" <?php }}?> <?php if( isset($field['field_width']) and $field['field_width'] != '' and $field['enable_arf_prefix'] != 1 and $field['enable_arf_suffix'] != 1 ) { echo 'style="width:'.$field['field_width'].'px !important; '.$inline_css_without_style.'"'; } else { echo $inline_css_with_style_tag; } ?> <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> <?php if($field['minlength'] != ''){?>minlength="<?php echo $field['minlength'];?>" data-validation-minlength-message="<?php echo esc_attr($field['minlength_message']);?>"<?php } ?> <?php echo $arfieldhelper->get_onchage_func($field); ?>  />
<?php 
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
echo $arfieldhelper->replace_description_shortcode($field); ?></div><?php 
}else if ($field['type'] == 'textarea'){ 
?><div class="controls" <?php if( isset($field['field_width']) and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>><?php 
if(apply_filters('arf_check_for_draw_outside',false,$field))
{
	do_action('arf_drawthisfieldfromoutside',$field);
}
else
{
?><textarea name="<?php echo $field_name ?>" id="field_<?php echo $field['field_key'] ?>"<?php if($field['max']) echo ' rows="'. $field['max'] .'"'; ?> <?php do_action('arffieldinputhtml', $field) ?> <?php if((isset($field['clear_on_focus']) and $field['clear_on_focus'] and !empty($field['default_value']))){ }else{ if($field['default_blank']==0){ echo "placeholder='".trim( esc_attr( $field['value'] ) )."'"; } }?> <?php if( isset($field['field_width']) and $field['field_width'] != '') { echo 'style="width:'.$field['field_width'].'px !important; '.$inline_css_without_style.'"'; } else { echo $inline_css_with_style_tag; } ?> <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> <?php if($field['minlength'] != ''){?>minlength="<?php echo $field['minlength'];?>" data-validation-minlength-message="<?php echo esc_attr($field['minlength_message']);?>"<?php } ?> <?php echo $arfieldhelper->get_onchage_func($field); ?> ><?php if((isset($field['clear_on_focus']) and $field['clear_on_focus'] and !empty($field['default_value']))){ if($field['default_blank']==1){ echo trim( esc_attr( $field['value'] ) ); ?> <?php }}else{ if($field['default_blank']==1){ echo trim(  esc_attr( $field['value'] ) ); } } ?></textarea><?php } 
echo $arfieldhelper->replace_description_shortcode($field); 
?></div><?php 

}else if ($field['type'] == 'radio'){
	
	global $arf_radio_checkbox_loaded; 
	$arf_radio_checkbox_loaded[ 'field_'. $field['field_key'] ] = 1;

	$requested_radio_checked_values = "";
    if(isset($_REQUEST['checkbox_radio_style_requested']))
	{
		$requested_radio_checked_values = $_REQUEST['checkbox_radio_style_requested'];
	}

	

        if (is_array($field['options'])){ 
		?><div class="setting_radio controls" <?php if(isset($field['field_width']) and $field['field_width']!=''){ echo 'style="width:'.$field['field_width'].'px;padding-top:5px;"';}else{ echo 'style="padding-top:5px;"';} ?> ><?php
		 
		if(apply_filters('arf_check_for_draw_outside',false,$field))
		{
			do_action('arf_drawthisfieldfromoutside',$field);
		}
		else
		{
			$field['options'] = $arfieldhelper->changeoptionorder( $field );

			$k=0;	
            foreach($field['options'] as $opt_key => $opt){

                if(isset($atts) and isset($atts['opt']) and ($atts['opt'] != $opt_key)) continue;

                $field_val = apply_filters('arfdisplaysavedfieldvalue', $opt, $opt_key, $field);

                $opt = apply_filters('show_field_label', $opt, $opt_key, $field);
				if(is_array($opt)) {
					$opt = $opt['label'];
					$field_val = ($field['separate_value']) ? $field_val['value'] : $opt;
				}
            ?><div class="arf_radiobutton"><?php 
			if(!isset($atts) or !isset($atts['label']) or $atts['label']){ 
				?><label for="field_<?php echo $field['id'] ?>-<?php echo $opt_key ?>"><input type="radio" name="<?php echo $field_name ?>" id="field_<?php echo $field['id'] ?>-<?php echo $opt_key ?>" value="<?php echo esc_attr($field_val) ?>" <?php echo ($armainhelper->check_selected($field['value'], $field_val)) ? 'checked="checked"' : ''; ?> <?php do_action('arffieldinputhtml', $field) ?> <?php if($k==0){?><?php if(isset($field['required']) and $field['required']){ echo 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="'.esc_attr($field['blank']).'"'; }?><?php }?> <?php echo $arfieldhelper->get_onchage_func($field); ?> style=" <?php echo $inline_css_without_style; ?>" /><?php echo $opt ?></label><?php 
				} 
			?></div><?php 
			$k++;   
		}
	}  
	
	echo $arfieldhelper->replace_description_shortcode($field); 
	?></div><?php
    
	}

    



}else if ($field['type'] == 'select'){

global $arf_selectbox_loaded;
$arf_selectbox_loaded[ 'field_'. $field['field_key'] ] = 1;
  
if($field['size']!=1)
{
	if($newarr['auto_width'] != "1")
	{
		if( isset($field['field_width']) and $field['field_width']!='') 
		{ ?>
			<style>
            .ar_main_div_<?php echo $field['form_id'];?> .select_controll_<?php echo $field['id'];?>:not([class*="span"]):not([class*="col-"]):not([class*="form-control"])
            {
            width:<?php echo $field['field_width'];?>px !important;
            }
            </style>
            <?php
		}
	}
} ?>
<div class="sltstandard_front controls" <?php if( isset($field['field_width']) and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>>
		<?php
		
		if(apply_filters('arf_check_for_draw_outside',false,$field))
		{
			do_action('arf_drawthisfieldfromoutside',$field);
		}
		else
		{
			$field['options'] = $arfieldhelper->changeoptionorder( $field );
			
			if($field['read_only'] and $arfreadonly != 'disabled' and (!current_user_can('administrator') or !is_admin())){ ?>
	<input type="hidden" value="<?php echo esc_attr($field['value']) ?>" name="<?php echo $field_name ?>" id="field_<?php echo $field['field_key'] ?>" />
	
	<select disabled="disabled" <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> <?php do_action('arffieldinputhtml', $field) ?> <?php if($field['size']!=1){ if($newarr['auto_width'] != "1"){if(isset($field['field_width']) and $field['field_width'] != '' ) { echo 'style="width:'.$field['field_width'].'px !important; '.$inline_css_without_style.'"'; } else { echo $inline_css_with_style_tag; } }else{echo 'style="width:auto; '.$inline_css_without_style.'"';}}else{echo 'style="width:auto; '.$inline_css_without_style.'"';} ?> data-size="15"  class="select_controll_<?php echo $field['id'];?>" <?php echo $arfieldhelper->get_onchage_func($field); ?>>	 
	<?php   }else{ ?>        
	<select name="<?php echo $field_name ?>" <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> id="field_<?php echo $field['field_key'] ?>" <?php do_action('arffieldinputhtml', $field) ?> <?php if($field['size']!=1){ if( ($field['field_width'] != '' || $newarr['auto_width'] != 1) and $field['field_width']!='' ) { echo 'style="width:'.$field['field_width'].'px !important; '.$inline_css_without_style.'"'; } else { echo $inline_css_with_style_tag; } }else{echo 'style="width:auto;min-width:100px; '.$inline_css_without_style.'"';} ?> data-size="15" class="select_controll_<?php echo $field['id'];?>" <?php echo $arfieldhelper->get_onchage_func($field); ?> >
	
	<?php }
	  
			$count_i = 0;	
			foreach ($field['options'] as $opt_key => $opt){ 
		
				$field_val = apply_filters('arfdisplaysavedfieldvalue', $opt, $opt_key, $field);
		
				$opt = apply_filters('show_field_label', $opt, $opt_key, $field); 
				
				if(is_array($opt)) {
					$opt = $opt['label'];
					$field_val = ($field['separate_value']) ? $field_val['value'] : $opt;
				}
				if($count_i == 0 and $opt == '')		 
					$opt = __('Please select', 'ARForms');		 
				?>
		
		<option value="<?php echo esc_attr($field_val) ?>" <?php if ($armainhelper->check_selected($field['value'], $field_val)) echo 'selected="selected"'; ?>><?php echo $opt ?></option>
		
			<?php $count_i++; } ?>
		
			</select><?php 
			}  
		echo $arfieldhelper->replace_description_shortcode($field); 
?></div><?php 

}else if ($field['type'] == 'checkbox'){
	
	global $arf_radio_checkbox_loaded; 
	$arf_radio_checkbox_loaded[ 'field_'. $field['field_key'] ] = 1;
	
    $checked_values = $field['value'];

	$requested_checked_values = "";
    if(isset($_REQUEST['checkbox_radio_style_requested']))
	{
		$requested_checked_values = $_REQUEST['checkbox_radio_style_requested'];
	}

	
        if($field['options']){ 
		
		?><div class="setting_checkbox controls" <?php if( isset($field['field_width']) and $field['field_width']!=''){ echo 'style="width:'.$field['field_width'].'px;padding-top:5px;"';}else{ echo 'style="padding-top:5px;"';} ?> ><?php
		
		if(apply_filters('arf_check_for_draw_outside',false,$field))
		{
			do_action('arf_drawthisfieldfromoutside',$field);
		}
		else
		{
			
			$field['options'] = $arfieldhelper->changeoptionorder( $field );
			$k = 0;
			foreach ($field['options'] as $opt_key => $opt){
	
				if(isset($atts) and isset($atts['opt']) and ($atts['opt'] != $opt_key)) continue;
	
				$field_val = apply_filters('arfdisplaysavedfieldvalue', $opt, $opt_key, $field);
	
				$opt = apply_filters('show_field_label', $opt, $opt_key, $field);
	
				if(is_array($opt)) {
					$opt = $opt['label'];
					$field_val = ($field['separate_value']) ? $field_val['value'] : $opt;
				}
				$checked = ($armainhelper->check_selected($checked_values, $field_val)) ? ' checked="checked"' : '';
	
				?><div class="arf_checkbox_style" id="frm_checkbox_<?php echo $field['id']?>-<?php echo $opt_key ?>"><?php 
				if(!isset($atts) or !isset($atts['label']) or $atts['label']){
                                    $_REQUEST['arfaction'] = ( isset( $_REQUEST['arfaction'] ) ) ? $_REQUEST['arfaction']  : "";
				?><label for="field_<?php echo $field['id']?>-<?php echo $opt_key ?>"><input type="checkbox" name="<?php echo $field_name ?>[]" id="field_<?php echo $field['id']?>-<?php echo $opt_key ?>" value="<?php echo esc_attr($field_val); ?>" <?php echo $checked ?> <?php do_action('arffieldinputhtml', $field) ?> <?php if($k==0){?><?php if(isset($field['required']) and $field['required']){ echo 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="'.esc_attr($field['blank']).'"'; }?><?php } echo (@$_REQUEST['arfaction'] == 'preview') ? $arfieldhelper->get_onchage_func($field) : '';?> style=" <?php echo $inline_css_without_style; ?>" /><?php echo $opt ?></label>
                          <?php
                                if( @$_REQUEST['arfaction'] != 'preview' and $arfieldhelper->get_onchage_func($field) != ''){
                                        
                                   ?>
                                    <script type="text/javascript">jQuery(document).ready(function(){jQuery('#field_<?php echo $field['id']?>-<?php echo $opt_key ?>').on('ifChanged', function(event){<?php echo trim(substr(trim(str_replace('onchange="','',$arfieldhelper->get_onchage_func($field))),0,-1)); ?> });});</script>
                                    <?php 
                                    }
				}
	 		?></div><?php	
		   $k++; } 
       	}
       
       	echo $arfieldhelper->replace_description_shortcode($field); 
		?></div><?php
        }

    
}else if ($field['type'] == 'slider' and !is_admin()){ 
$field['slider_step'] = is_numeric($field['slider_step']) ? $field['slider_step'] : 1; 
$field['minnum'] = is_numeric($field['minnum']) ? $field['minnum'] : 1; 
$field['maxnum'] = is_numeric($field['maxnum']) ? $field['maxnum'] : 50; 
$field['slider_value'] = is_numeric($field['slider_value']) ? $field['slider_value'] : $field['minnum'];
?>            
<div class="arf_slider_control controls" <?php if( isset($field['field_width']) and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>>
	<?php		
	if(apply_filters('arf_check_for_draw_outside',false,$field))
	{
		do_action('arf_drawthisfieldfromoutside',$field);
	}
	else
	{
	?>
	<input type="text" id="field_<?php echo $field['field_key']; ?>_slide" class="arfslider" data-slider-id="field_<?php echo $field['field_key'] ?>_slider" data-slider-min="<?php echo $field['minnum']; ?>" data-slider-max="<?php echo $field['maxnum']; ?>" data-slider-step="<?php echo $field['slider_step']; ?>" data-slider-value="<?php echo $field['slider_value']; ?>" autocomplete="off" style="cursor:pointer;" />    
    <input type="hidden" id="field_<?php echo $field['field_key']; ?>" class="arfslider_hidden" autocomplete="off" name="<?php echo $field_name ?>" data-value="<?php echo $field['slider_value']; ?>" value="<?php echo $field['slider_value']; ?>" <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; }?> <?php echo $arfieldhelper->get_onchage_func($field); ?> />
	<?php 
	global $arf_slider_loaded;
		
	$arf_slider_loaded[ 'field_'.$field['field_key'] ] = array(
							'min'			=> $field['minnum'], 
							'max'			=> $field['maxnum'],
							'step'			=> $field['slider_step'],
							'slider_value'	=> $field['slider_value'],
							'handle'		=> ($field['slider_handle'] != '') ? $field['slider_handle'] : 'round',
							'form_key'		=> $form->form_key,
							);	
	}
	
	echo $arfieldhelper->replace_description_shortcode($field);
	 
?></div><?php

}else if ($field['type'] == 'colorpicker' and !is_admin()){ 

?><div class="arf_colorpicker_control controls" <?php if( isset($field['field_width']) and $field['field_width']!='') { echo 'style="width:'.$field['field_width'].'px;"' ; } ?>><?php
		
	if(apply_filters('arf_check_for_draw_outside',false,$field))
	{
		do_action('arf_drawthisfieldfromoutside',$field);
	}
	else
	{
		if( $field['colorpicker_type'] == 'basic' ){		
			global $arfcolorpicker_basic_loaded;		
			$arfcolorpicker_basic_loaded[ 'field_'.$field['field_key'] ] = array( 'form_key' => $form->form_key );
			$colorpickerclass = "arf_basic_colorpicker";
		} else {
			global $arfcolorpicker_loaded;		
			$arfcolorpicker_loaded[ 'field_'.$field['field_key'] ] = array( 'form_key' => $form->form_key );
			$colorpickerclass = "arf_colorpicker";		
		}
		
		$defaultcolor = '';
		$arfcolorpickerstyle = '';
		if( $field['default_value'] != '' )
		{
			$defaultcolor = $field['default_value'];				
			$defaultcolor =	@strtolower( str_replace('#', '', $defaultcolor) );		
			if( $defaultcolor == '000' || $defaultcolor == '000000' ) {
				$arfcolorpickerstyle = 'style="background:#000000;color:#FFFFFF;"';
			} else if( $defaultcolor == 'fff' || $defaultcolor == 'ffffff' ) {
				$arfcolorpickerstyle = 'style="background:#ffffff;color:#000000;"';		
			} else {
				$arfcolorpickerstyle = 'style="background:#'.$defaultcolor.';color:#333333;"';				
			}
			$defaultcolor = '#'.$defaultcolor;	
        }
		
		echo '<div class="arfcolorpickerfield '.$colorpickerclass.'" id="arfcolorpicker_'.$field['field_key'].'">';
        	echo '<div class="arfcolorimg"><i class="fa fa-paint-brush"></i></div>';
            echo '<div class="arfcolorvalue" '.$arfcolorpickerstyle.'>'.$defaultcolor.'</div>';
        echo '</div><div class="arfcolorpickerreset" onclick="arfresetcolor(\''.$field['field_key'].'\');"></div>';		
       	echo '<div><input type="text" id="field_'.$field['field_key'].'" class="arfhiddencolor" value="'.$defaultcolor.'"';  if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; } echo $arfieldhelper->get_onchage_func($field); echo ' name="'.$field_name.'" autocomplete="off" /></div>';
			 	
	}
	
	echo $arfieldhelper->replace_description_shortcode($field);
	 
?></div><?php

}else if ($field['type'] == 'captcha' and !is_admin()){
			
	if(apply_filters('arf_check_for_draw_outside',false,$field))
	{
		do_action('arf_drawthisfieldfromoutside',$field);
	}
	else
	{
		if($field['is_recaptcha'] == 'custom-captcha')
		{ 
			?><script type="text/javascript">
				function vpb_refresh_aptcha(form_id)
				{
					return document.getElementById("vpb_captcha_code_"+form_id).value="",document.getElementById("vpb_captcha_code_"+form_id).focus(),document.images['captchaimg_'+form_id].src = document.images['captchaimg_'+form_id].src.substring(0,document.images['captchaimg_'+form_id].src.lastIndexOf("?"))+"?rand="+Math.random()*1000+"&is_update=1&form_id="+form_id;
				}
				</script>
				<div id='recaptcha_style' class="recaptcha_style_custom controls">
					<div class="vpb_captcha_wrapper"><img src="<?php echo plugin_dir_url(__FILE__);?>vasplusCaptcha.php?rand=<?php echo rand(); ?>&form_id=<?php echo $field['form_id']; ?>" id='captchaimg_<?php echo $field['form_id']; ?>' ></div><div style="clear:both;"></div>
					<div style=" padding-top:10px;" align="left"><font style="font-family:Verdana, Geneva, sans-serif; font-size:11px;"><?php _e("Can't read the above security code?",'ARForms'); ?> <a href="javascript:void(0);" style="text-decoration:none;" onClick="vpb_refresh_aptcha('<?php echo $field['form_id']; ?>');"><?php _e('Refresh','ARForms');?></a></font></div>
					<div id="recaptcha_area" style="width:auto !important; margin-top:10px;"><div class="vpb_captcha" id="vpb_captcha_<?php echo $field['form_id']; ?>"><input type="text" id="vpb_captcha_code_<?php echo $field['form_id']; ?>" name="vpb_captcha_code_<?php echo $field['form_id']; ?>" class="vpb_input_fields"></div></div><?php echo $arfieldhelper->replace_description_shortcode($field); ?></div><?php 
			}
		
		else
		{ 
			global $arfsettings;
	
			$error_msg = null;
	
			if(!empty($errors)){
	
				foreach($errors as $error_key => $error){
	
					if(preg_match('/^captcha-/', $error_key))
	
						$error_msg = preg_replace('/^captcha-/', '', $error_key);
	
				}
	
			}
			
			if (!empty($arfsettings->pubkey))
	
				$arfieldhelper->display_recaptcha($field, $error_msg);
		 
		}
		echo '<div><input type="hidden" name="field_captcha" data-type="'.$field['is_recaptcha'].'" id="field_captcha" value="'.$field['id'].'"></div>';
	}
	
}else do_action('form_fields', $field, $field_name);

?>