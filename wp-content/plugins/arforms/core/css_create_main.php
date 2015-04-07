<?php 
if(!isset($saving))

    header("Content-type: text/css");


if(isset($use_saved) and $use_saved){

	
	foreach($new_values as $k => $v){
	
			if( ( preg_match('/color/', $k) or in_array($k, array('arferrorbgsetting', 'arferrorbordersetting', 'arferrortextsetting')) ) && ! in_array($k, array('arfcheckradiocolor') ) ) { 
				$new_values[$k] = '#'.$v;
			} else {
				$new_values[$k] = $v;		
			}
			
	}
	extract((array)$new_values);
	
	global $arsettingcontroller;
	
	$form_border_shadow_color = $new_values['arfmainformbordershadowcolorsetting'];
	
	$field_text_color_pg_break = $new_values['text_color_pg_break']; 
	
	$field_bg_color_pg_break = $new_values['bg_color_pg_break'];
	
	$field_bg_inactive_color_pg_break = $new_values['bg_inavtive_color_pg_break'];
	
	$checkbox_radio_style_val  = $new_values['checkbox_radio_style'];
	
	$form_bg_color = $new_values['arfmainformbgcolorsetting']; 
	
	$form_opacity = $new_values['arfmainform_opacity'];
	
	$fieldset_color = $new_values['arfmainfieldsetcolor']; 
	
	$bg_color_active = $new_values['arfbgactivecolorsetting'];
	
	$border_color_active = $new_values['arfborderactivecolorsetting'];
	
	$submit_bg_img = $new_values['submit_bg_img'];
	
	$submit_hover_bg_img = $new_values['submit_hover_bg_img'];
	
	$submit_border_color = $new_values['arfsubmitbordercolorsetting'];
	
	$submit_text_color = $new_values['arfsubmittextcolorsetting'];
	
	$submit_weight = $new_values['arfsubmitweightsetting'];
	
	$submit_shadow_color = $new_values['arfsubmitshadowcolorsetting'];
	
	$submit_bg_color_hover = $new_values['arfsubmitbuttonbgcolorhoversetting'];
	
	$bg_color_error =  $new_values['arferrorbgcolorsetting'];
	
	$border_color_error = $new_values['arferrorbordercolorsetting'];
	
	$field_border_style = $new_values['arffieldborderstylesetting'];
	
	$border_style_error = $new_values['arfbordererrorstylesetting']; 
	
	$success_border_color =  $new_values['arfsucessbordercolorsetting']; //'#8CCF7A';
	
	$success_bg_color =  $new_values['arfsucessbgcolorsetting']; //'#D8F7CF';
	
	$success_text_color =  $new_values['arfsucesstextcolorsetting']; //'#3B3B3B';
	
	$error_bg = $new_values['arferrorbgsetting'];
    
	$error_border = $new_values['arferrorbordersetting'];
    
	$error_text = $new_values['arferrortextsetting'];
	
	$form_title_padding = $new_values['arfmainformtitlepaddingsetting'];
	
	$description_font = $new_values['check_font'];
	
	$description_font_size = $new_values['arfdescfontsizesetting'];
	
	$description_color = $new_values['label_color'];
    
	$description_align = $new_values['arfdescalighsetting'];
	
	$border_radius = ($new_values['border_radius']=='') ? '0px' : $new_values['border_radius'].'px';
	
	$error_font_size = $new_values['arffontsizesetting'].'px';
	
	$form_title_color = $new_values['arfmainformtitlecolorsetting']; 	
	
	$form_width = $new_values['arfmainformwidth'];
	
	$check_align = $new_values['arfcheckboxalignsetting'];
	
	$radio_align = $new_values['arfradioalignsetting']; 
	
	$field_font_size_without_px = $new_values['field_font_size'];
	
	$submit_align = $new_values['arfsubmitalignsetting'];
	
	$form_width = $form_width.$form_width_unit;
	
	$fieldset = ($fieldset == '') ? '0px' : $fieldset.'px';
	
	$field_font_size = $field_font_size.'px';
	
	$font_size = $font_size.'px';
	
	$fieldset_padding = ($new_values['arfmainfieldsetpadding']=='') ? '0px' : $new_values['arfmainfieldsetpadding'];	
	
	$fieldset_radius = ($new_values['arfmainfieldsetradius']=='') ? '0px' : $new_values['arfmainfieldsetradius'].'px';	
	
	$hide_labels = isset($hide_labels) ? $hide_labels : 0;
	
	$width = $width.$width_unit;
	
	$description_font_size = $description_font_size.'px';
	
	
	
	if( $field_width_unit == '%' and $field_width > 100) {
		$field_width = '100%';
	}else {		
		$field_width_select = $field_width;
		$field_width = ($field_width=='') ? 'auto' : $field_width.$field_width_unit;
	} 

    $field_margin = ($new_values['arffieldmarginssetting']=='') ? '0px' : $new_values['arffieldmarginssetting'].'px';
		
	$field_border_width = ($new_values['arffieldborderwidthsetting']=='') ? '0px' : $new_values['arffieldborderwidthsetting'].'px';	
	
	$field_border_width_select = ($new_values['arffieldborderwidthsetting']=='') ? '0' : $new_values['arffieldborderwidthsetting'];			
	
	$border_width_error = $field_border_width;
	
	$submit_style = isset($submit_style) ? $submit_style : 0;

    $submit_font_size = $new_values['arfsubmitbuttonfontsizesetting'].'px !important';
	
	$submit_font_size_wpx = $new_values['arfsubmitbuttonfontsizesetting'];
	
	$form_title_weight = $new_values['check_weight_form_title'];
	
	$submit_width = ($new_values['arfsubmitbuttonwidthsetting']=='') ? '' : $new_values['arfsubmitbuttonwidthsetting'].'px';
	
	$submit_auto_width = ($new_values['arfsubmitautowidth']=='' || $new_values['arfsubmitautowidth'] < 100 ) ? '100' : $new_values['arfsubmitautowidth']; 		//submit auto width
	
	$submit_width		= ( $submit_width == '' ) ? $submit_auto_width.'px' : $submit_width;
		
	$submit_width_wpx = ($new_values['arfsubmitbuttonwidthsetting']=='') ? $submit_auto_width : $new_values['arfsubmitbuttonwidthsetting']; //submit width
		
	$submit_height_hex = ($new_values['arfsubmitbuttonheightsetting']=='') ? '36' : $new_values['arfsubmitbuttonheightsetting'];
	
	$submit_height_wpx = ($new_values['arfsubmitbuttonheightsetting']=='') ? '' : $new_values['arfsubmitbuttonheightsetting']; //submit hiehgt
	 
    $submit_height = ($new_values['arfsubmitbuttonheightsetting']=='') ? 'auto' : $new_values['arfsubmitbuttonheightsetting'].'px';	

	$submit_border_width = ($new_values['arfsubmitborderwidthsetting']=='') ? '0px' : $new_values['arfsubmitborderwidthsetting'].'px';
	
	$submit_border_radius = ($new_values['arfsubmitborderradiussetting']=='') ? '0px' : $new_values['arfsubmitborderradiussetting'].'px';

    $submit_margin = ($new_values['arfsubmitbuttonmarginsetting']=='') ? '0px' : $new_values['arfsubmitbuttonmarginsetting'];

    $submit_padding = $submit_padding.'px !important';
	 
	$success_font_size = $error_font_size;
			
	$field_textarea_width = $field_width;
			
	$field_textarea_margin = $field_margin;
	
	$field_textarea_font_size = $field_font_size;
		
	$textarea_bg_color	= $bg_color;
		
	$textarea_text_color = $text_color;

	$textarea_border_color = $border_color;

	$field_textarea_border_width = $field_border_width;
			
	$field_textarea_border_style = $field_border_style;
	
	$color_bg_active = $color_bg_active;	
	
	$field_height = ($field_height=='') ? 'auto' : $field_height.'px';
	
	$text_direction	= ($text_direction == 0) ? 'rtl' : 'ltr';
	
	$form_title_font_size = $form_title_font_size.'px';	
	
	$submit_width_loader  = ($new_values['arfsubmitbuttonwidthsetting']=='') ? '1' : $new_values['arfsubmitbuttonwidthsetting'];	
	
	$arffieldpaddingsetting = $field_textarea_pad = $new_values['arffieldinnermarginssetting'];
	
	$arfsubmitfontfamily = $new_values['arfsubmitfontfamily'];
	
	$arfformtitlealign = $new_values['arfformtitlealign'];
	
	$arfcheck_style_name = $new_values['arfcheckradiostyle'];
	
	$arfcheck_style_color = $new_values['arfcheckradiocolor'];
	
	$arf_bar_color_survey = $new_values['bar_color_survey'];
	
	$arf_bg_color_survey = $new_values['bg_color_survey'];
	
	$arf_text_color_survey = $new_values['text_color_survey'];
	
	
	$arf_title_font_family = $new_values['arftitlefontfamily'];
	
	$arferrorstylecolor1 = explode("|",$new_values['arferrorstylecolor']);
	$arferrorstylecolor = $arferrorstylecolor1[0];
	$arferrorstylecolorfont = $arferrorstylecolor1[1];
	
	$arfvalidationerrorstyle = $new_values['arferrorstyle'];
	
	if( $arfvalidationerrorstyle == 'normal' ){
		$arferrorstylecolor2 = explode("|",$new_values['arferrorstylecolor2']);
		$arferrorstylecolor = $arferrorstylecolor2[2];
	}
	
	if( !preg_match('/#/',$arferrorstylecolor) )
		$arferrorstylecolor = '#'.$arferrorstylecolor;
	
	if( !preg_match('/#/',$arferrorstylecolorfont) )
		$arferrorstylecolorfont = '#'.$arferrorstylecolorfont;
		
	if( $field_font_size < '20' ) {
		$fie_field_height = '29';
		$file_field_pad = '6';
	} else if( $field_font_size >= '20' and $field_font_size < '24') {
		$fie_field_height = '45';		
		$file_field_pad = '14';
	} else if( $field_font_size >= '24' ) {
		$field_pad = '8px 15px';
		$fie_field_height = '49';		
		$file_field_pad = '16';		
	}
	
	if( $field_border_width_select == '1' ) {
		$file_field_pad = $file_field_pad + 1;
	} else if($field_border_width_select > 2 and $field_border_width_select < 5 ) {
		$file_field_pad = $file_field_pad - floor($field_border_width_select/2);
	} else if($field_border_width_select == 5 || $field_border_width_select == 6) {
		$file_field_pad = $file_field_pad - floor($field_border_width_select/1.5);	
	} else if($field_border_width_select >= 7) {
		$file_field_pad = $file_field_pad - floor($field_border_width_select/1);		
	}
	
	if( $form_title_font_size <= '20' )
		$form_title_margin = '0 0 25px 35px;';
	else if( $form_title_font_size > '20' and $form_title_font_size <= '28' ) 
		$form_title_margin = '0 0 35px 35px;';
	else if( $form_title_font_size >= '30' and $form_title_font_size <= '36' ) 
		$form_title_margin = '0 0 40px 35px;';			
	else if( $form_title_font_size > '36' ) 
		$form_title_margin = '0 0 45px 35px;';
		
	$prefix_suffix_bg_color = $new_values['prefix_suffix_bg_color'];
	$prefix_suffix_icon_color = $new_values['prefix_suffix_icon_color'];
	 
}	
else if (isset($_REQUEST['arfmfws'])){
	
	$form_id = $_REQUEST['arfmf'];
	
	$form_width_unit = $_REQUEST['arffu'];
	
	$field_width_unit = $_REQUEST['arffiu'];

	$width_unit = $_REQUEST['arfmwu'];
	
	$form_width = $_REQUEST['arffw'].$form_width_unit;

    $form_align = $_REQUEST['arffa'];

    $fieldset = ($_REQUEST['arfmfis']=='') ? '0px' : $_REQUEST['arfmfis'].'px';

    $fieldset_color = $_REQUEST['arfmfsc'];

    $fieldset_padding = ($_REQUEST['arfmfsp']=='') ? '0px' : $_REQUEST['arfmfsp'];
	
	$fieldset_radius = ($_REQUEST['arfmfsr']=='') ? '0px' : $_REQUEST['arfmfsr'].'px';

    $font = $_REQUEST['arfmfs'];

	$font_other = $_REQUEST['arfofs'];
	
    $font_size = $_REQUEST['arffss'].'px';

    $label_color = $_REQUEST['arflcs'];

    $weight = $_REQUEST['arfmfws']; 

    $position = $_REQUEST['arfmps'];
	
	$hide_labels = isset($_REQUEST['arfhl']) ? $_REQUEST['arfhl'] : 0;
	
    $align = $_REQUEST['arffrma'];

    $width = $_REQUEST['arfmws'].$width_unit;

	$description_font = $_REQUEST['arfcbfs'];

    
	$description_font_size = $_REQUEST['arfdfss'].'px';

    $description_color = $_REQUEST['arflcs'];

    $description_style = @$_REQUEST['arfdss']; 

    $description_align = $_REQUEST['arfdas'];
    
	$field_font_size_without_px = $_REQUEST['arfffss'];
	
    $field_font_size = $_REQUEST['arfffss'].'px';
	
	
    $field_width_unit = $_REQUEST['arffiu'];
	
	if( $_REQUEST['arffiu'] == '%' and $_REQUEST['arfmfiws'] > 100)
		$field_width = '100%';	
	else {  
		$field_width_select = $_REQUEST['arfmfiws'];
		$field_width = ($_REQUEST['arfmfiws']=='') ? 'auto' : $_REQUEST['arfmfiws'].$_REQUEST['arffiu'];
	} 

    $field_margin = ($_REQUEST['arffms']=='') ? '0px' : $_REQUEST['arffms'].'px';

    $text_color = $_REQUEST['arftcs'];

    $bg_color = $_REQUEST['arffmbc'];

    $border_color = $_REQUEST['arffmboc'];

    $field_border_width = ($_REQUEST['arffbws']=='') ? '0px' : $_REQUEST['arffbws'].'px';
	
	$field_border_width_select = ($_REQUEST['arffbws']=='') ? '0' : $_REQUEST['arffbws'];		

    $field_border_style = $_REQUEST['arffbss'];

    $bg_color_active = $_REQUEST['arfbcas'];

    $border_color_active = $_REQUEST['arfbacs'];    

    $bg_color_error = $_REQUEST['arfbecs'];

    $border_color_error = $_REQUEST['arfboecs'];

	$border_width_error = $field_border_width;

    $border_style_error = @$_REQUEST['arfbess']; 

    $radio_align = $_REQUEST['arfras'];

    $check_align = $_REQUEST['arfcbas'];

    $check_font = $_REQUEST['arfcbfs'];
	
	$check_font_other = $_REQUEST['arffcfo'];
	
	$check_font_size = $_REQUEST['arfffss'];

    $check_weight = $_REQUEST['arfcbws'];

    $submit_style = isset($_REQUEST['arfsbs']) ? $_REQUEST['arfsbs'] : 0;

    $submit_font_size = $_REQUEST['arfsbfss'].'px !important';
	
	$submit_font_size_wpx = $_REQUEST['arfsbfss'];

    $submit_width = ($_REQUEST['arfsbws']=='') ? '' : $_REQUEST['arfsbws'].'px';
	
	$submit_auto_width = ($_REQUEST['arfsbaw']=='' || $_REQUEST['arfsbaw'] < 100 ) ? '100' : $_REQUEST['arfsbaw']; 		//submit auto width
	
	$submit_width		= ( $submit_width == '' ) ? $submit_auto_width.'px' : $submit_width;
	
	$submit_width_wpx = ($_REQUEST['arfsbws']=='') ? $submit_auto_width : $_REQUEST['arfsbws']; //submit width
	
	$submit_height_hex = ($_REQUEST['arfsbhs']=='') ? '36' : $_REQUEST['arfsbhs'];
	
	$submit_height_wpx = ($_REQUEST['arfsbhs']=='') ? '' : $_REQUEST['arfsbhs']; //submit hiehgt
	 
    $submit_height = ($_REQUEST['arfsbhs']=='') ? 'auto' : $_REQUEST['arfsbhs'].'px';

 	$submit_bg_color = $_REQUEST['arfsbbcs'];

	$submit_bg_color_hover = $_REQUEST['arfsbchs'];
	
    $submit_bg_color2 = $_REQUEST['arfsbcs'];

    $submit_bg_img = $_REQUEST['arfsbis'];
	
	$submit_hover_bg_img = $_REQUEST['arfsbhis'];

    $submit_border_color = $_REQUEST['arfsbobcs'];

    $submit_border_width = ($_REQUEST['arfsbbws']=='') ? '0px' : $_REQUEST['arfsbbws'].'px';

    $submit_text_color = $_REQUEST['arfsbtcs'];

    $submit_weight = $_REQUEST['arfsbwes'];

    $submit_border_radius = ($_REQUEST['arfsbbrs']=='') ? '0px' : $_REQUEST['arfsbbrs'].'px';

    $submit_margin = ($_REQUEST['arfsbms']=='') ? '0px' : $_REQUEST['arfsbms'];

    $submit_shadow_color = $_REQUEST['arfsbscs'];

    $border_radius = (@$_REQUEST['arfmbs']=='') ? '0px' : @$_REQUEST['arfmbs'].'px';

    $error_bg = $_REQUEST['arfmebs'];

    $error_border = $_REQUEST['arfmebos'];

    $error_text = $_REQUEST['arfmets'];

    $error_font_size = $_REQUEST['arfmefss'].'px'; 

	$success_bg_color =  $_REQUEST['arfmsbcs']; //'#D8F7CF';

    $success_border_color =  $_REQUEST['arfmsbocs']; //'#8CCF7A';

    $success_text_color = $_REQUEST['arfmstcs']; //'#3B3B3B';

	$success_font_size = $error_font_size;
	
	$field_textarea_font_size = $_REQUEST['arfffss'];
			
	$field_textarea_width = $field_width;
			
	$field_textarea_margin = $field_margin;
		
	$textarea_bg_color	= $bg_color;
		
	$textarea_text_color = $text_color;

	$textarea_border_color = $border_color;

	$field_textarea_border_width = $field_border_width;
			
	$field_textarea_border_style = $field_border_style;
	
	$field_height = (empty($_REQUEST['arfmfhs'])) ? 'auto' : $_REQUEST['arfmfhs'].'px';
	
	$text_direction	= ($_REQUEST['arftds'] == 0) ? 'rtl' : 'ltr';	
	
	$error_font = $_REQUEST['arfmefs'];
	
	$error_font_other = $_REQUEST['arfmofs'];
	
	$form_title_color = $_REQUEST['arfftc'];
	
	$form_title_font_size = $_REQUEST['arfftfss'].'px';
	
	$form_bg_color = $_REQUEST['arffbcs'];
	
	$form_opacity = $_REQUEST['arfmainform_opacity'];
	
	$form_title_weight = $_REQUEST['arfftws'];
	
	$form_title_padding = $_REQUEST['arfftps'];
	
	$form_border_shadow = $_REQUEST['arffbs'];
	
	$submit_width_loader  = ($_REQUEST['arfsbws']=='') ? '1' : $_REQUEST['arfsbws'];	
	
	$form_border_shadow_color = $_REQUEST['arffboss'];
	
	$arf_title_font_family = $_REQUEST['arftff'];
	
	$section_padding = @$_REQUEST['arfscps'];
	
	$arferrorstylecolor1 = explode("|",$_REQUEST['arfestc']);
	
	$arferrorstylecolor = $arferrorstylecolor1[0];
	$arferrorstylecolorfont = $arferrorstylecolor1[1];
	
	$arfvalidationerrorstyle = $_REQUEST['arfest'];
	
	if( $arfvalidationerrorstyle == 'normal' ){
		$arferrorstylecolor2 = explode("|",$_REQUEST['arfestc2']);
		$arferrorstylecolor = $arferrorstylecolor2[2];
	}
		
	$submit_align = $_REQUEST['arfmsas'];
	
	$arfmainform_bg_img = $_REQUEST['arfmfbi'];		
	$arfmainfield_opacity = @($_REQUEST['arfmfo']=='') ? 0 : $_REQUEST['arfmfo'];
	
	$arffieldpaddingsetting = $field_textarea_pad = $_REQUEST['arffims'];
	
	if( $_REQUEST['arfffss'] < '20' ) {
		$fie_field_height = '29';
		$file_field_pad = '6';
	} 
	
	if( $field_border_width_select == '1' ) {
		$file_field_pad = $file_field_pad + 1;
	} else if($field_border_width_select > 2 and $field_border_width_select < 5 ) {
		$file_field_pad = $file_field_pad - floor($field_border_width_select/2);
	} else if($field_border_width_select == 5 || $field_border_width_select == 6) {
		$file_field_pad = $file_field_pad - floor($field_border_width_select/1.5);	
	} else if($field_border_width_select >= 7) {
		$file_field_pad = $file_field_pad - floor($field_border_width_select/1);		
	}
	
	if( $_REQUEST['arfftfss'] <= '20' )
		$form_title_margin = '0 0 25px 35px;';
	else if( $_REQUEST['arfftfss'] > '20' and $_REQUEST['arfftfss'] <= '28' ) 
		$form_title_margin = '0 0 35px 35px;';
	else if( $_REQUEST['arfftfss'] >= '30' and $_REQUEST['arfftfss'] <= '36' ) 
		$form_title_margin = '0 0 40px 35px;';			
	else if( $_REQUEST['arfftfss'] > '36' ) 
		$form_title_margin = '0 0 45px 35px;';			

	$checkbox_radio_style_val  = ($_REQUEST['arfcrs']=='') ? '1' : $_REQUEST['arfcrs'];	
	
	$field_bg_color_pg_break = $_REQUEST['arffbcpb'];
	
	$field_bg_inactive_color_pg_break = $_REQUEST['arfbicpb'];
	
	$field_text_color_pg_break = $_REQUEST['arfftcpb'];
	
	$arf_bar_color_survey = $_REQUEST['arfbcs'];
	
	$arf_bg_color_survey = $_REQUEST['arfbgcs'];
	
	$arf_text_color_survey = $_REQUEST['arfftcs'];
	
	$arfsubmitfontfamily = $_REQUEST['arfsff'];
	
	$arfformtitlealign = $_REQUEST['arffta'];
	
	$arfcheck_style_name = $_REQUEST['arfcksn'];
	
	$arfcheck_style_color = $_REQUEST['arfcksc'];
	
	$prefix_suffix_bg_color = $_REQUEST['pfsfsbg'];
	
	$prefix_suffix_icon_color = $_REQUEST['pfsfscol'];
}

if( $field_font_size_without_px < '20' ) {
	$file_upload_padding = '10';
	$file_upload_hw = '14px';
	$file_upload_bg = 'upload-icon.png';
	
	if( $field_font_size_without_px <= 13 )
		$file_upload_margin_top = '0px';
	else
		$file_upload_margin_top = '3px';
		
} else if( $field_font_size_without_px >= '20' and $field_font_size_without_px < '26' ) {
	$file_upload_padding = '13';
	$file_upload_hw = '14px';
	
	if( $field_font_size_without_px > 22 )
		$file_upload_margin_top = '9px';
	else
		$file_upload_margin_top = '7px';	
	
	$file_upload_bg = 'upload-icon.png';
} else if( $field_font_size_without_px >= '26' and $field_font_size_without_px < '33' ) {
	$file_upload_padding = '15';
	$file_upload_hw = '25px';
	$file_upload_margin_top = '5px';
	$file_upload_bg = 'upload-icon_25x25.png';	
} else if( $field_font_size_without_px > '33' ) {
	$file_upload_hw = '32px';
	$file_upload_padding = '17';
	$file_upload_margin_top = '7px';
	$file_upload_bg = 'upload-icon_32x32.png';		
} else {
	$file_upload_bg = 'upload-icon_32x32.png';	
}

@$label_margin = (int)$width + 15; 

if($weight=='italic') {
	$weight = 'normal';
	$weight_font_style = 'font-style:italic;';
} else {
	$weight = $weight;
	$weight_font_style = '';
}

if($check_weight=='italic') {
	$check_weight = 'normal';
	$check_weight_font_style = 'font-style:italic !important;';
} else {
	$check_weight = $check_weight;
	$check_weight_font_style = 'font-style:normal !important;';
}

if($submit_weight=='italic') {
	$submit_weight = 'normal';
	$submit_weight_font_style = 'font-style:italic;';
} else {
	$submit_weight = $submit_weight;
	$submit_weight_font_style = 'font-style:normal;';
}

if($form_title_weight=='italic') {
	$form_title_weight = 'normal';
	$form_title_weight_font_style = 'font-style:italic;';
} else {
	$form_title_weight = $form_title_weight;
	$form_title_weight_font_style = 'font-style:normal;';
}

if($font == "Other")
{ 
	$newfont = $font_other;
}else {
	$newfont = $font;
}

if($check_font == "Other")
{ 
	$newfontother = $check_font_other;
}else {
	$newfontother = $check_font;
}

if($error_font == "Other")
{ 
	$newerror_font = $error_font_other;
}else {
	$newerror_font = $error_font;
}

if($newfont!="Arial" && $newfont!="Helvetica" && $newfont!="sans-serif" && $newfont!="Lucida Grande" && $newfont!="Lucida Sans Unicode" && $newfont!="Tahoma" && $newfont!="Times New Roman" && $newfont!="Courier New" && $newfont!="Verdana" && $newfont!="Geneva" && $newfont!="Courier" && $newfont!="Monospace" && $newfont!="Times" && $newfont!="")		
{
	if( is_ssl() )
		$googlefontbaseurl = "https://fonts.googleapis.com/css?family=";
	else
		$googlefontbaseurl = "http://fonts.googleapis.com/css?family=";	
	echo "@import url(".$googlefontbaseurl.urlencode($newfont).");";
}

if($newfontother!="Arial" && $newfontother!="Helvetica" && $newfontother!="sans-serif" && $newfontother!="Lucida Grande" && $newfontother!="Lucida Sans Unicode" && $newfontother!="Tahoma" && $newfontother!="Times New Roman" && $newfontother!="Courier New" && $newfontother!="Verdana" && $newfontother!="Geneva" && $newfontother!="Courier" && $newfontother!="Monospace" && $newfontother!="Times" && $newfontother!="")
{
	if( is_ssl() )
		$googlefontbaseurl = "https://fonts.googleapis.com/css?family=";
	else
		$googlefontbaseurl = "http://fonts.googleapis.com/css?family=";	
	echo "@import url(".$googlefontbaseurl.urlencode($newfontother).");";
}

if($newerror_font!="Arial" && $newerror_font!="Helvetica" && $newerror_font!="sans-serif" && $newerror_font!="Lucida Grande" && $newerror_font!="Lucida Sans Unicode" && $newerror_font!="Tahoma" && $newerror_font!="Times New Roman" && $newerror_font!="Courier New" && $newerror_font!="Verdana" && $newerror_font!="Geneva" && $newerror_font!="Courier" && $newerror_font!="Monospace" && $newerror_font!="Times" && $newerror_font!="")
{	
	if( is_ssl() )
		$googlefontbaseurl = "https://fonts.googleapis.com/css?family=";
	else
		$googlefontbaseurl = "http://fonts.googleapis.com/css?family=";	
	echo "@import url(".$googlefontbaseurl.urlencode($newerror_font).");";
}

if($arfsubmitfontfamily!="Arial" && $arfsubmitfontfamily!="Helvetica" && $arfsubmitfontfamily!="sans-serif" && $arfsubmitfontfamily!="Lucida Grande" && $arfsubmitfontfamily!="Lucida Sans Unicode" && $arfsubmitfontfamily!="Tahoma" && $arfsubmitfontfamily!="Times New Roman" && $arfsubmitfontfamily!="Courier New" && $arfsubmitfontfamily!="Verdana" && $arfsubmitfontfamily!="Geneva" && $arfsubmitfontfamily!="Courier" && $arfsubmitfontfamily!="Monospace" && $arfsubmitfontfamily!="Times" && $arfsubmitfontfamily!="")		
{
	if( is_ssl() )
		$googlefontbaseurl = "https://fonts.googleapis.com/css?family=";
	else
		$googlefontbaseurl = "http://fonts.googleapis.com/css?family=";	
	echo "@import url(".$googlefontbaseurl.urlencode($arfsubmitfontfamily).");";
}


if($arf_title_font_family!="Arial" && $arf_title_font_family!="Helvetica" && $arf_title_font_family!="sans-serif" && $arf_title_font_family!="Lucida Grande" && $arf_title_font_family!="Lucida Sans Unicode" && $arf_title_font_family!="Tahoma" && $arf_title_font_family!="Times New Roman" && $arf_title_font_family!="Courier New" && $arf_title_font_family!="Verdana" && $arf_title_font_family!="Geneva" && $arf_title_font_family!="Courier" && $arf_title_font_family!="Monospace" && $arf_title_font_family!="Times" && $arf_title_font_family!="")		
{
	if( is_ssl() )
		$googlefontbaseurl = "https://fonts.googleapis.com/css?family=";
	else
		$googlefontbaseurl = "http://fonts.googleapis.com/css?family=";	
	echo "@import url(".$googlefontbaseurl.urlencode($arf_title_font_family).");";
}
?>

.arf_form.ar_main_div_<?php echo $form_id;?> {max-width:<?php echo $form_width ?>;margin:0 auto;}

.ar_main_div_<?php echo $form_id;?>, .ar_main_div_<?php echo $form_id;?> form{text-align:<?php echo $form_align ?>; }

.ar_main_div_<?php echo $form_id;?> .arf_fieldset{ <?php if($arfmainform_bg_img!="") { ?> background: rgba(<?php echo $arsettingcontroller->hex2rgb($form_bg_color); ?>, <?php echo $form_opacity; ?> ) url(<?php echo $arfmainform_bg_img; ?>); <?php }else { ?> background:rgba(<?php echo $arsettingcontroller->hex2rgb($form_bg_color); ?>, <?php echo $form_opacity; ?> ); <?php } ?> border:<?php echo $fieldset ?> solid <?php echo $fieldset_color ?>;margin:0;padding:<?php echo $fieldset_padding ?>; -moz-border-radius:<?php echo $fieldset_radius ?>;-webkit-border-radius:<?php echo $fieldset_radius ?>;border-radius:<?php echo $fieldset_radius ?>; 
 
<?php if($form_border_shadow == 'shadow') { ?>
-moz-box-shadow:0px 0px 7px 2px <?php echo $form_border_shadow_color; ?>;
-webkit-box-shadow:0px 0px 7px 2px <?php echo $form_border_shadow_color; ?>;
box-shadow:0px 0px 7px 2px <?php echo $form_border_shadow_color; ?>;
<?php } else { ?>
-moz-box-shadow:none;
-webkit-box-shadow:none;
box-shadow:none;
<?php } ?>
background-position: left top;	
background-repeat: no-repeat;	
}

.ar_main_div_<?php echo $form_id;?> label.arf_main_label{font-family:<?php echo stripslashes($newfont) ?>;font-size:<?php echo $font_size ?> !important;line-height:<?php echo str_replace('px','0%',$font_size) ?>;color:<?php echo $label_color ?>;font-weight:<?php echo $weight ?> !important; <?php echo $weight_font_style; ?> text-align:<?php echo $align ?>;margin:0;padding:0;width:auto;display:block; text-transform:none;}

.ar_main_div_<?php echo $form_id;?> #recaptcha_style{color:<?php echo $label_color ?>;}


.ar_main_div_<?php echo $form_id;?> .arfmainformfield{margin-bottom:<?php echo $field_margin ?>;}

.wp-admin .ar_main_div_<?php echo $form_id;?> .arfmainformfield{margin-bottom:<?php echo $field_margin ?>;}

.ar_main_div_<?php echo $form_id;?> .arfmainformfield.arf_column{clear:none;float:left;margin-right:20px;}

.ar_main_div_<?php echo $form_id;?> p.description, .ar_main_div_<?php echo $form_id;?> div.description, .ar_main_div_<?php echo $form_id;?> div.arf_field_description, .ar_main_div_<?php echo $form_id;?> .help-block{margin:0;padding:0;font-family:<?php echo stripslashes($description_font) ?>;font-size:<?php echo $description_font_size ?>;color:<?php echo $description_color ?>;text-align:<?php echo $description_align ?>;font-style:<?php echo $description_style ?>;max-width:100%;width:<?php echo ($field_width == '') ? 'auto' : $field_width ?>; line-height: 20px;}

.ar_main_div_<?php echo $form_id;?> .left_container p.description, .ar_main_div_<?php echo $form_id;?> .left_container div.description, .ar_main_div_<?php echo $form_id;?> .left_container div.arf_field_description, .ar_main_div_<?php echo $form_id;?> .left_container .help-block{margin-left:<?php echo $label_margin ?>px;}

.ar_main_div_<?php echo $form_id;?> .arfmainformfield.arf_column div.arf_field_description{width:<?php echo ($field_width == '') ? 'auto' : $field_width ?>;max-width:100%;}

.ar_main_div_<?php echo $form_id;?> .left_container .attachment-thumbnail{clear:both;margin-left:<?php echo $label_margin ?>px;}

.ar_main_div_<?php echo $form_id;?> .right_container p.description, .ar_main_div_<?php echo $form_id;?> .right_container div.description, .ar_main_div_<?php echo $form_id;?> .right_container div.arf_field_description, .ar_main_div_<?php echo $form_id;?> .right_container .help-block{margin-right:<?php echo $label_margin ?>px;}

.ar_main_div_<?php echo $form_id;?> .top_container label.arf_main_label, .ar_main_div_<?php echo $form_id;?> .hidden_container label.arf_main_label, .ar_main_div_<?php echo $form_id;?> .pos_top{display:block;float:none;width:auto;}

.ar_main_div_<?php echo $form_id;?> .inline_container label.arf_main_label{ margin-right:10px; margin-left: 3px; }

.ar_main_div_<?php echo $form_id;?> .left_container label.arf_main_label{display:inline;float:left;margin-right:15px;vertical-align:middle;padding-top:5px;width:<?php echo $width; ?>;}

.ar_main_div_<?php echo $form_id;?> .right_container label.arf_main_label, .ar_main_div_<?php echo $form_id;?> .pos_right{display:inline;float:right;margin-left:15px;vertical-align:middle;padding-top:5px;width:<?php echo $width; ?>;}

.ar_main_div_<?php echo $form_id;?> .none_container label.arf_main_label, .ar_main_div_<?php echo $form_id;?> .pos_none{display:none;}

.ar_main_div_<?php echo $form_id;?> input[type=text], .ar_main_div_<?php echo $form_id;?> input[type=password], .ar_main_div_<?php echo $form_id;?> input[type=email], .ar_main_div_<?php echo $form_id;?> input[type=number], .ar_main_div_<?php echo $form_id;?> input[type=url], .ar_main_div_<?php echo $form_id;?> input[type=tel]{font-family:<?php echo stripslashes($newfontother) ?> !important;font-size:<?php echo $field_font_size ?> !important; height:<?php echo $field_height;?>; font-weight:<?php echo $check_weight ?> !important; <?php echo $check_weight_font_style;?> margin-bottom:0;line-height:12px !important;clear:none;cursor:text;}

.ar_main_div_<?php echo $form_id;?> select, #content .ar_main_div_<?php echo $form_id;?> input:not([type=submit], [class=previous_btn]), #content .ar_main_div_<?php echo $form_id;?> select {font-family:<?php echo stripslashes($newfontother) ?>;font-size:<?php echo $field_font_size ?>; font-weight:<?php echo $check_weight ?>; <?php echo $check_weight_font_style;?> margin-bottom:0;clear:none;}

.ar_main_div_<?php echo $form_id;?> textarea, #content .ar_main_div_<?php echo $form_id;?> textarea{font-family:<?php echo stripslashes($newfontother) ?> !important;font-size:<?php echo $field_font_size ?> !important;margin-bottom:0; font-weight:<?php echo $check_weight ?> !important; <?php echo $check_weight_font_style;?>clear:none;}

.ar_main_div_<?php echo $form_id;?> input[type=text], .ar_main_div_<?php echo $form_id;?> input[type=password], .ar_main_div_<?php echo $form_id;?> input[type=email], .ar_main_div_<?php echo $form_id;?> input[type=number], .ar_main_div_<?php echo $form_id;?> input[type=url], .ar_main_div_<?php echo $form_id;?> input[type=tel], .ar_main_div_<?php echo $form_id;?> select, .allfields_style, .allfields_active_style, .allfields_error_style{color:<?php echo $text_color ?> !important;background-color:<?php echo $bg_color ?> !important;border-color:<?php echo $border_color ?> !important;border-width:<?php echo $field_border_width ?> !important;border-style:<?php echo $field_border_style ?>;-moz-border-radius:<?php echo $border_radius ?> !important;-webkit-border-radius:<?php echo $border_radius ?> !important;border-radius:<?php echo $border_radius ?> !important;width:<?php echo ($field_width == '') ? 'auto' : $field_width ?> !important;font-size:<?php echo $field_font_size ?>;padding:<?php echo $arffieldpaddingsetting ?>!important;font-weight:<?php echo $check_weight ?>; <?php echo $check_weight_font_style;?> -webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box; height:<?php echo 'auto';?>;line-height:normal !important; direction:<?php echo $text_direction;?> !important; outline:none;clear:none;box-shadow:inherit; display:inline-block !important; margin: 0 !important; } 


.wp-admin .allfields .smaple-textarea, .ar_main_div_<?php echo $form_id;?> textarea{color:<?php echo $textarea_text_color; ?> !important;background-color:<?php echo $textarea_bg_color; ?> !important;border-color:<?php echo $textarea_border_color; ?> !important;border-width:<?php echo $field_textarea_border_width; ?> !important;border-style:<?php echo $field_textarea_border_style; ?>;-moz-border-radius:<?php echo $border_radius ?> !important;-webkit-border-radius:<?php echo $border_radius ?> !important;border-radius:<?php echo $border_radius ?> !important;width:<?php echo ($field_textarea_width == '') ? 'auto' : $field_textarea_width ?> !important;max-width:100%;font-size:<?php echo $field_textarea_font_size; ?> !important; padding:<?php echo $field_textarea_pad; ?>!important;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box; box-shadow:none; direction:<?php echo $text_direction;?>; outline:none; margin-bottom:0; } 

.wp-admin .ar_main_div_<?php echo $form_id;?> select, .ar_main_div_<?php echo $form_id;?> select {width:<?php echo ($auto_width) ? 'auto' : $field_width ?>;max-width:100%; outline:none;box-shadow:inherit; } 

.ar_main_div_<?php echo $form_id;?> input[type="radio"], .ar_main_div_<?php echo $form_id;?> input[type="checkbox"]{width:auto;border:none;background:transparent;padding:0;}

.ar_main_div_<?php echo $form_id;?> input.auto_width, .ar_main_div_<?php echo $form_id;?> select.auto_width, .ar_main_div_<?php echo $form_id;?> textarea.auto_width{ width:auto; }

.ar_main_div_<?php echo $form_id;?> select.auto_width { width:<?php echo ($auto_width) ? 'auto' : $field_width ?>;max-width:100%; }

.ar_main_div_<?php echo $form_id;?> input[disabled], .ar_main_div_<?php echo $form_id;?> select[disabled], .ar_main_div_<?php echo $form_id;?> textarea[disabled], .ar_main_div_<?php echo $form_id;?> input[readonly], .ar_main_div_<?php echo $form_id;?> select[readonly], .ar_main_div_<?php echo $form_id;?> textarea[readonly]{opacity:.5;filter:alpha(opacity=50);}

.select_style .ar_main_div_<?php echo $form_id;?> select, .select_style .ar_main_div_<?php echo $form_id;?> select.auto_width{ width:100%;}

.ar_main_div_<?php echo $form_id;?> .arfmainformfield input:focus, .ar_main_div_<?php echo $form_id;?> select:focus, .ar_main_div_<?php echo $form_id;?> textarea:focus, .ar_main_div_<?php echo $form_id;?> .frm_focus_field input[type=text], .ar_main_div_<?php echo $form_id;?> .frm_focus_field input[type=password], .ar_main_div_<?php echo $form_id;?> .frm_focus_field input[type=email], .ar_main_div_<?php echo $form_id;?> .frm_focus_field input[type=number], .ar_main_div_<?php echo $form_id;?> .frm_focus_field input[type=url], .ar_main_div_<?php echo $form_id;?> .frm_focus_field input[type=tel], .allfields_active_style{background-color:<?php echo $bg_color_active ?> !important;border-color:<?php echo $border_color_active ?>  !important; box-shadow:none;-o-transition: all .4s;-moz-transition: all .4s;-webkit-transition: all .4s;-ms-transition: all .4s; outline:none; 

-moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
-webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
} 



.ar_main_div_<?php echo $form_id;?> .arfsubmitbutton input[type="submit"], .ar_main_div_<?php echo $form_id;?> .next_btn, .ar_main_div_<?php echo $form_id;?> input[type="button"].previous_btn, .ar_main_div_<?php echo $form_id;?> .previous_btn, .submitbutton_style{clear:none;min-width:<?php echo '100px';//echo ($submit_width == '') ? 'auto' : $submit_width ?>;font-family:<?php echo stripslashes($arfsubmitfontfamily) ?>;font-size:<?php echo $submit_font_size; ?>;height:<?php echo $submit_height ?>;text-align:center;background:<?php echo $submit_bg_color ?>;border-width:<?php echo $submit_border_width ?>;border-color:<?php echo $submit_border_color ?>;border-style:solid;color:<?php echo $submit_text_color ?> !important;cursor:pointer;font-weight:<?php echo $submit_weight ?>;-moz-border-radius:<?php echo $submit_border_radius ?>;-webkit-border-radius:<?php echo $submit_border_radius ?>;border-radius:<?php echo $submit_border_radius ?>;text-shadow:none;-moz-box-sizing:content-box;box-sizing:content-box;-ms-box-sizing:content-box;
filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
-moz-box-shadow:1px 2px 3px <?php echo $submit_shadow_color; ?>;-webkit-box-shadow:1px 2px 3px <?php echo $submit_shadow_color; ?>;box-shadow:1px 2px 3px <?php echo $submit_shadow_color; ?>;-ms-filter:"progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='<?php echo $submit_shadow_color; ?>')";filter:progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='<?php echo $submit_shadow_color; ?>'); margin:<?php echo $submit_margin ?>; <?php echo $submit_weight_font_style;?> padding:0 10px;<?php if($submit_bg_img != ''){/*?> text-indent:-9999px;min-width:150px; <?php */}else{?>text-indent:0px;<?php }?> text-transform: none;max-width:95%;}

.ar_main_div_<?php echo $form_id;?> input[type="submit"]:hover, .ar_main_div_<?php echo $form_id;?> .next_btn:hover, .ar_main_div_<?php echo $form_id;?> .previous_btn:hover, .submitbutton_style_<?php echo $form_id;?> { background-color:<?php echo $submit_bg_color_hover ?> !important;}

.ar_main_div_<?php echo $form_id;?> .next_btn:hover, .ar_main_div_<?php echo $form_id;?> .previous_btn:hover, .ar_main_div_<?php echo $form_id;?> .previous_btn:active, .ar_main_div_<?php echo $form_id;?> input[type="button"].previous_btn:active, .ar_main_div_<?php echo $form_id;?> input[type="button"].previous_btn:hover { background:none; background-color:<?php echo $submit_bg_color_hover ?> !important; padding:0 10px; border-width:<?php echo $submit_border_width ?>;border-color:<?php echo $submit_border_color ?>;border-style:solid;
filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
 } 

<?php if( isset($submit_bg_img) and $submit_bg_img != '' ) { ?>
.ar_main_div_<?php echo $form_id;?> .next_btn:active,.ar_main_div_<?php echo $form_id;?> .next_btn:hover, .ar_main_div_<?php echo $form_id;?> .previous_btn:hover, .ar_main_div_<?php echo $form_id;?> .previous_btn:active, .ar_main_div_<?php echo $form_id;?> input[type="button"].previous_btn:active, .ar_main_div_<?php echo $form_id;?> input[type="button"].previous_btn:hover { background:<?php echo $submit_bg_color ?>; background-color:<?php echo $submit_bg_color_hover ?>; }
<?php } ?>
 
.submitbutton_style{height:auto;}

.ar_main_div_<?php echo $form_id;?> .left_container .arf_radiobutton, .ar_main_div_<?php echo $form_id;?> .none_container .arf_radiobutton{margin<?php echo ($radio_align == 'block') ? "-bottom:5px;" : ':0 20px 5px 0'; ?>}

.ar_main_div_<?php echo $form_id;?> .right_container .arf_radiobutton{margin<?php echo ($radio_align == 'block') ? "-right:{$label_margin}px; margin-bottom:5px;" : ':0 0 5px 20px'; ?>}

.ar_main_div_<?php echo $form_id;?> .arf_checkbox_style{display:<?php echo ($check_align=='inline') ? 'inline-block' : $check_align; ?>;clear:none;box-shadow:inherit; }

.ar_main_div_<?php echo $form_id;?> .left_container .arf_checkbox_style, .ar_main_div_<?php echo $form_id;?> .none_container .arf_checkbox_style{margin<?php echo ($check_align == 'block') ? "-bottom:5px;" : ':2px 20px 5px 0'; ?>}

.ar_main_div_<?php echo $form_id;?> .right_container .arf_checkbox_style{margin<?php echo ($check_align == 'block') ? "-right:{$label_margin}px;margin-bottom:5px;" : ':0 20px 5px 0'; ?>}

.ar_main_div_<?php echo $form_id;?> .arf_horizontal_radio.left_container .arf_radiobutton, .ar_main_div_<?php echo $form_id;?> .right_container .arf_radiobutton{margin:0 20px 5px 0;}

.ar_main_div_<?php echo $form_id;?> .arf_vertical_radio .arf_checkbox_style, .ar_main_div_<?php echo $form_id;?> .arf_vertical_radio .arf_radiobutton, .arf_vertical_radio {display:block;}

.ar_main_div_<?php echo $form_id;?> .arf_horizontal_radio .arf_checkbox_style, .ar_main_div_<?php echo $form_id;?> .arf_horizontal_radio .arf_radiobutton {display:inline-block;margin:0 20px 5px 0;}

.ar_main_div_<?php echo $form_id;?> .top_container .arf_checkbox_style, .ar_main_div_<?php echo $form_id;?> .top_container .arf_radiobutton {margin:0 20px 5px 0;}

.ar_main_div_<?php echo $form_id;?> .arf_radiobutton{display:<?php echo ($radio_align=='inline') ? 'inline-block' : $radio_align; ?>;clear:none;box-shadow:inherit;}
         
.ar_main_div_<?php echo $form_id;?> .arf_radiobutton label, .ar_main_div_<?php echo $form_id;?> .arf_checkbox_style label{font-family:<?php echo stripslashes($newfont) ?>;font-size:<?php echo $font_size ?>;color:<?php echo $label_color ?>;font-weight:<?php echo $weight ?>; <?php echo $weight_font_style;?> display:inline;}

.ar_main_div_<?php echo $form_id;?> .arfblankfield input[type=text], .ar_main_div_<?php echo $form_id;?> .arfblankfield input[type=password], .ar_main_div_<?php echo $form_id;?> .arfblankfield input[type=url], .ar_main_div_<?php echo $form_id;?> .arfblankfield input[type=tel], .ar_main_div_<?php echo $form_id;?> .arfblankfield input[type=number], .ar_main_div_<?php echo $form_id;?> .arfblankfield input[type=email], .ar_main_div_<?php echo $form_id;?> .arfblankfield select, .allfields_error_style {background-color:<?php echo $bg_color_error ?>;border-color:<?php echo $border_color_error ?>;border-width:<?php echo $border_width_error ?>;border-style:<?php echo $border_style_error ?>;} 

<?php echo "/*arf selectbox css start*/"; ?>

.ar_main_div_<?php echo $form_id;?> ul.arfdropdown-menu { overflow-x:hidden; margin:0 !important; }
.ar_main_div_<?php echo $form_id;?> .arfdropdown-menu > li { margin:0 !important; }
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group .current {
	border: <?php echo $field_border_width ?> <?php echo $field_border_style ?>;
}

<?php
$dropdown_menu_min_height = $field_font_size_without_px + ( 2 * ( (int)$field_border_width_select ) );
$fieldpadding 	= explode(' ', $arffieldpaddingsetting);
$fieldpadding_1	= $fieldpadding[0];
$fieldpadding_1 = str_replace('px', '', $fieldpadding_1);

$dropdown_menu_min_height = $dropdown_menu_min_height + ( 2 * ( (int)$fieldpadding_1 ) );
?>
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group .arfbtn.dropdown-toggle {
	border: <?php echo $field_border_width ?> <?php echo $field_border_style ?> <?php echo $border_color ?>;
	background-color:<?php echo $bg_color ?> !important;
	background-image:none;
	box-shadow:none;
	outline:0 !important;
    -moz-border-radius:<?php echo $border_radius ?> !important;
    -webkit-border-radius:<?php echo $border_radius ?> !important;
    border-radius:<?php echo $border_radius ?>;
    padding:<?php echo $arffieldpaddingsetting ?> !important;
    line-height: normal;
    font-size:<?php echo $field_font_size; ?>;
    color:<?php echo $text_color;?> !important; 
    font-family:<?php echo stripslashes($newfontother) ?>;
    font-weight:<?php echo $check_weight ?>;
    text-shadow:none;
 	text-transform:none;	    
	<?php echo $check_weight_font_style;?>;
   	width:100%;
    margin-top:0px;    
    min-height:<?php echo $dropdown_menu_min_height."px"; ?>;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group .arfbtn.dropdown-toggle:focus,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group .arfbtn.dropdown-toggle:focus {
	border: <?php echo $field_border_width ?> <?php echo $field_border_style ?> <?php echo $border_color_active ?>;
	 background-color: <?php echo $bg_color_active; ?> !important;
	background-image:none;
	box-shadow:none;
	outline:0 !important;
    
    font-size:<?php echo $field_font_size; ?>;
    color:<?php echo $text_color;?> !important; 
    font-family:<?php echo stripslashes($newfontother) ?>;
    font-weight:<?php echo $check_weight ?>; 
	<?php echo $check_weight_font_style;?>;
   	width:100%;
    -moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
	-webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
	box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
    margin-top:0px;    
    min-height:<?php echo $dropdown_menu_min_height."px"; ?>;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle {
	border: <?php echo $field_border_width ?> <?php echo $field_border_style ?> <?php echo $border_color_active ?>;
	background-color:<?php echo $bg_color_active ?> !important;
	border-bottom-color:transparent;
	box-shadow:none;
	outline:0 !important;
	outline-style:none;
	border-bottom-left-radius:0px !important;
	border-bottom-right-radius:0px !important;
    
    font-size:<?php echo $field_font_size; ?>;
    color:<?php echo $text_color;?> !important; 
    font-family:<?php echo stripslashes($newfontother) ?>;
    font-weight:<?php echo $check_weight ?>; 
	<?php echo $check_weight_font_style;?>;
   	width:100%;
    -moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
	-webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
	box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
    margin-top:0px;    
    min-height:<?php echo $dropdown_menu_min_height."px"; ?>;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.dropup.open .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.dropup.open .arfbtn.dropdown-toggle {
	border: <?php echo $field_border_width ?> <?php echo $field_border_style ?> <?php echo $border_color_active ?>;
	background-color:<?php echo $bg_color_active ?> !important;
	border-top-color:transparent;
	box-shadow:none;
	outline:0 !important;
	outline-style:none;
	border-top-left-radius:0px;
	border-top-right-radius:0px;
	border-bottom-left-radius:<?php echo $border_radius ?>;
	border-bottom-right-radius:<?php echo $border_radius ?>;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group .arfdropdown-menu {
	margin:0;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open .arfdropdown-menu {
	border: <?php echo $field_border_width ?> <?php echo $field_border_style ?> <?php echo $border_color_active ?>;
	box-shadow:none;
	border-top:none;
	margin:0;
	margin-top:-<?php echo $field_border_width ?>;
	border-top-left-radius:0px;
	border-top-right-radius:0px;	
   	width:100%;
    overflow:hidden;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.dropup.open .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.dropup.open .arfdropdown-menu {
	border: <?php echo $field_border_width ?> <?php echo $field_border_style ?> <?php echo $border_color_active ?>;
	box-shadow:none;
	border-bottom:none;
	margin:0;
	margin-bottom:-<?php echo $field_border_width ?>;
	border-bottom-left-radius:0px;
	border-bottom-right-radius:0px;
	border-top-left-radius:<?php echo $border_radius ?>;
	border-top-right-radius:<?php echo $border_radius ?>;
    
    font-size:<?php echo $field_font_size; ?>;
    color:<?php echo $text_color;?> !important; 
    font-family:<?php echo stripslashes($newfontother) ?>;
    font-weight:<?php echo $check_weight ?>; 
	<?php echo $check_weight_font_style;?>;
   	width:100%;
    margin-top:0px;    
    min-height:<?php echo $dropdown_menu_min_height."px"; ?>;
}

.ar_main_div_<?php echo $form_id;?> .bootstrap-select.btn-group .arfbtn .filter-option {
	<?php if($field_font_size_without_px>=17) { echo "min-height:".($field_font_size_without_px+9)."px;"; } else { echo "min-height:25px;"; } ?>
    padding-top:0px;
    text-align: <?php echo ($text_direction=='rtl') ? 'right' : 'left'; ?>;
    <?php if($field_font_size_without_px<=27 && $field_font_size_without_px>14) { echo "padding-top:1px;"; }
    elseif($field_font_size_without_px>=28 && $field_font_size_without_px<27) { echo "padding-top:1px;"; }
    elseif($field_font_size_without_px>=36) { echo "padding-top:2px;"; } ?>
}

.ar_main_div_<?php echo $form_id;?> .bootstrap-select:not([class*="span"]):not([class*="col-"]):not([class*="form-control"]) {
    width:<?php echo ($field_width == '' || $field_width == 'auto') ? '245px' : $field_width."" ?>;
}

.arfdropdown-menu ul.arfdropdown-menu li a span.text {
	font-size:<?php echo $field_font_size; ?>;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.dropup.open .arfdropdown-menu .arfdropdown-menu.inner,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.dropup.open .arfdropdown-menu .arfdropdown-menu.inner {
	border-top:none;
}

.ar_main_div_<?php echo $form_id;?> .bootstrap-select.btn-group, 
.ar_main_div_<?php echo $form_id;?> .bootstrap-select.btn-group[class*="span"] {
	margin-bottom:2px;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group .arfbtn.dropdown-toggle {
	border: <?php echo $border_width_error ?> <?php echo $field_border_style ?> <?php echo $border_color_error ?>;
    background-color: <?php echo $bg_color_error; ?> !important;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group.open .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle {
	border: <?php echo $border_width_error ?> <?php echo $field_border_style ?> <?php echo $border_color_error ?>;
    border-bottom:none;
    -moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
	-webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
	box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group.dropup.open .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.dropup.open .arfbtn.dropdown-toggle {
	border: <?php echo $border_width_error ?> <?php echo $field_border_style ?> <?php echo $border_color_error ?>;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group.open .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.open .arfdropdown-menu {
	border: <?php echo $border_width_error ?> <?php echo $field_border_style ?> <?php echo $border_color_error ?>;
    border-top:none;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group.dropup.open .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.dropup.open .arfdropdown-menu {
	border: <?php echo $border_width_error ?> <?php echo $field_border_style ?> <?php echo $border_color_error ?>;
    border-bottom:none;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open ul.arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open ul.arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.dropup.open ul.arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.dropup.open ul.arfdropdown-menu { 
	border:none;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open ul.arfdropdown-menu > li,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open ul.arfdropdown-menu > li {
	margin:0 !important;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group.open ul.arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.open ul.arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group.dropup.open ul.arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.dropup.open ul.arfdropdown-menu {
	border:none;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.open ul.arfdropdown-menu > li,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.open ul.arfdropdown-menu > li,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.dropup.open ul.arfdropdown-menu > li,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.dropup.open ul.arfdropdown-menu > li {
	margin:0 !important;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group .arfbtn.dropdown-toggle:focus,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group .arfbtn.dropdown-toggle:focus {
	-moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
	-webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
	box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group .arfdropdown-menu.open,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group .arfdropdown-menu.open {
	border-top:1px <?php echo $field_border_style ?> <?php echo $border_color_error ?>; 
	-moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
	-webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
	box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
}

<?php 
	if($field_width_unit=="%") { 
		$dropdownwidthvar = "width:99%;";
	}else {
		if($field_font_size_without_px!="" && $field_font_size_without_px!="auto") {
			$dropdown_optionwidth = $field_width-28;
			if($dropdown_optionwidth<0) {
				$dropdown_optionwidth = 0;
			}
			$dropdownwidthvar = "width:".$dropdown_optionwidth."px;";
		}else {
			$dropdownwidthvar = "width:217px;";
		}
	}
    ?>

.ar_main_div_<?php echo $form_id;?> .arfdropdown-menu > li > a {
	font-size:<?php echo $field_font_size; ?>;
    color:<?php echo $text_color;?> !important; 
    font-family:<?php echo stripslashes($newfontother) ?>;
    font-weight:<?php echo $check_weight ?>; 
    text-decoration:none;
	<?php echo $check_weight_font_style;?>;
<?php
if($field_font_size_without_px>=36) { echo "padding:14px 12px;"; } elseif($field_font_size_without_px>=28) { echo "padding:12px 12px;"; } 
	elseif($field_font_size_without_px>=24) { echo "padding:10px 12px;"; } elseif($field_font_size_without_px>=22) { echo "padding:08px 12px;"; } 	elseif($field_font_size_without_px>=20) { echo "padding:06px 12px;"; }elseif($field_font_size_without_px>=24) { echo "padding:10px 12px;"; }		
	elseif($field_font_size_without_px<=18) { echo "padding:3px 12px;"; }
?>
	padding:<?php echo $arffieldpaddingsetting ?> !important;
    	line-height: normal;    
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open ul.arfdropdown-menu > li,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open ul.arfdropdown-menu > li {
	text-align: <?php echo ($text_direction=='rtl') ? 'right' : 'left'; ?>;
}

.ar_main_div_<?php echo $form_id;?> .arfdropdown-menu > li:hover > a,
.ar_main_div_<?php echo $form_id;?> .arfdropdown-menu > li:hover > a > span.text {
	color: #ffffff !important;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.bootstrap-select,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.bootstrap-select {
	width:<?php echo ($field_width == '' || $field_width == 'auto') ? '245px' : $field_width."" ?>;
}

.ar_main_div_<?php echo $form_id;?> .arfdropdown-menu > li:hover > a {
	background-color: <?php echo $border_color_active ?> !important;
}
<?php echo '/*arf selectbox css end*/'; ?>

.ar_main_div_<?php echo $form_id;?> .arfblankfield textarea{background-color:<?php echo $bg_color_error ?>;border-color:<?php echo $border_color_error ?>;border-width:<?php echo $border_width_error ?>;border-style:<?php echo $border_style_error ?>;}

.ar_main_div_<?php echo $form_id;?> :invalid, .ar_main_div_<?php echo $form_id;?> :-moz-submit-invalid, .ar_main_div_<?php echo $form_id;?> :-moz-ui-invalid {box-shadow:none;}

.ar_main_div_<?php echo $form_id;?> .help-block{font-weight:<?php echo $weight ?>; color:<?php echo $arferrorstylecolor ?>; <?php echo $weight_font_style; ?> font-family:<?php echo stripslashes($newerror_font) ?>; font-size:<?php echo $error_font_size ?>; }

.ar_main_div_<?php echo $form_id;?> .frm_error_style img{padding-right:10px;vertical-align:middle;}

.ar_main_div_<?php echo $form_id;?> .frm_message img{padding-right:10px;vertical-align:middle;}

.ar_main_div_<?php echo $form_id;?> .trigger_style{cursor:pointer;}

.ar_main_div_<?php echo $form_id;?> .frm_message, .success_style{border:1px solid <?php echo $success_border_color ?>;background-color:<?php echo $success_bg_color ?>;color:<?php echo $success_text_color ?>;}

.allfields_style, .allfields_active_style, .allfields_error_style, .submitbutton_style{width:auto;}

.ar_main_div_<?php echo $form_id;?> .trigger_style span{float:left;}

.ar_main_div_<?php echo $form_id;?> .arfloadingimg{background:url(<?php echo ARFIMAGESURL ?>/ajax_loader.gif) no-repeat center center;padding:6px 12px;}

.ar_main_div_<?php echo $form_id;?> #ui-datepicker-div{display:none;z-index:999 !important;}

.ar_main_div_<?php echo $form_id;?> .arfformfield{clear:both;}

.ar_main_div_<?php echo $form_id;?> #arf_message_success {width:93%; display: inline-block; float:none; min-height:35px; margin: 0 0 15px 0; border-left:6px solid <?php echo $success_border_color;?>; border-right:1px solid <?php echo $success_border_color;?>; border-bottom:1px solid <?php echo $success_border_color;?>; border-top:1px solid <?php echo $success_border_color;?>; moz-border-radius:0px;  -webkit-border-radius:0px; border-radius:0px; font-family:<?php echo stripslashes($newerror_font) ?>; background: <?php echo $success_bg_color;?>; color:<?php echo $success_text_color; ?>; font-size:<?php echo $success_font_size; ?>; }

.ar_main_div_<?php echo $form_id;?> #message_success_preview {width:87%; display: block; float:none; min-height:35px; margin: 0 0 15px 0; border:1px solid <?php echo $success_border_color;?>; moz-border-radius:3px;  -webkit-border-radius:3px; border-radius:3px; font-family:<?php echo stripslashes($newerror_font) ?>; background: <?php echo $success_bg_color;?>; }

.ar_main_div_<?php echo $form_id;?> .msg-detail { float:left; width: 98%; padding:0 0 0 10px; min-height: 37px; line-height: 32px; text-shadow: 0 1px 0 rgba(255,255,255,0.5); }

.ar_main_div_<?php echo $form_id;?> .msg-detail p { padding:0 !important; margin:0 !important; }

.ar_main_div_<?php echo $form_id;?> .msg-title-success { padding:0px 0 0 10px; vertical-align:middle; display:inline; font-weight:bold; }

.ar_main_div_<?php echo $form_id;?> .msg-description-success { letter-spacing:0.1px; padding:0px 0 0 10px; width:88%; vertical-align:middle; display:inline; }

.ar_main_div_<?php echo $form_id;?> .msg-title-error { padding:5px 0 0 10px; vertical-align:middle; display:inline; }

.ar_main_div_<?php echo $form_id;?> .msg-description-error { padding:7px 0 0 10px; letter-spacing:0.1px; vertical-align:middle; display:inline; }

.ar_main_div_<?php echo $form_id;?> .frm_error_style { width:93%; display: inline-block; float:none; min-height:35px; margin: 0 0 10px 0; border-left:6px solid <?php echo $error_border; ?>; border-top:1px solid <?php echo $error_border; ?>; border-right:1px solid <?php echo $error_border; ?>; border-bottom:1px solid <?php echo $error_border; ?>; font-family:<?php echo stripslashes($newerror_font) ?>; background: <?php echo $error_bg;?>; color:<?php echo $error_text; ?>; font-weight:bold; font-size:<?php echo $error_font_size; ?>; }

.ar_main_div_<?php echo $form_id;?> .frm_error_style_preview { width:87%; display: block; float:none; height:35px; margin: 0 0 10px 0; border:1px solid <?php echo $error_border; ?>; -moz-border-radius:3px;   -webkit-border-radius:3px; border-radius:3px; font-family:<?php echo stripslashes($newerror_font) ?>; background: <?php echo $error_bg;?>; }

.ar_main_div_<?php echo $form_id;?> #recaptcha_table { line-height:0 !important; height: 123px; }	

.wp-admin .ar_main_div_<?php echo $form_id;?> label.arf_main_label{text-align:<?php echo $align ?>;}

<?php if($form_align == 'center' || $form_align == 'right'){ ?>.wp-admin .ar_main_div_<?php echo $form_id;?> .right_container .arf_radiobutton {margin<?php echo ($radio_align == 'block') ? "-right:{$label_margin}px;" : ':0'; ?>}<?php } ?>

.ar_main_div_<?php echo $form_id;?> .original{ opacity: 0; position: relative; z-index: 100;<?php echo ($field_width == '') ? 'auto' : ($field_width)?>; }

.ar_main_div_<?php echo $form_id;?> .bootstrap-select .arfdropdown-menu > li > a:hover,
.ar_main_div_<?php echo $form_id;?> .bootstrap-select .arfdropdown-menu > li > a:focus {
  text-decoration: none;
  color: #ffffff !important;
  background-color: <?php echo $border_color_active ?> !important;
}

.ar_main_div_<?php echo $form_id;?> .formtitle_style { padding:0; color:<?php echo $form_title_color; ?>; font-family:<?php echo stripslashes($arf_title_font_family) ?>; text-align:<?php echo $arfformtitlealign; ?>; font-size:<?php echo $form_title_font_size; ?>; font-weight:<?php echo $form_title_weight; ?>; <?php echo $form_title_weight_font_style; ?> }
.ar_main_div_<?php echo $form_id;?> .arftitlecontainer { margin:<?php echo $form_title_padding; ?>; }

<?php 
$width_loader = ($submit_width_loader/2);		
if($submit_align == 'auto') {	?>
.ar_main_div_<?php echo $form_id;?> .arf_submit_div.left_container { text-align:center; clear:both; margin-left:auto; margin-right:auto; }
.ar_main_div_<?php echo $form_id;?> .arf_submit_div.right_container { text-align:center; clear:both; margin-left:auto; margin-right:auto; }
.ar_main_div_<?php echo $form_id;?> .arf_submit_div.top_container,
.ar_main_div_<?php echo $form_id;?> .arf_submit_div.none_container { text-align:center; clear:both; margin-left:auto; margin-right:auto; }

.ar_main_div_<?php echo $form_id;?> #brand-div { font-size: 10px; color: #444444; }
.ar_main_div_<?php echo $form_id;?> #brand-div.left_container { text-align:center; margin-left:auto; margin-right:auto; }
.ar_main_div_<?php echo $form_id;?> #brand-div.right_container { text-align:center; margin-left:auto; margin-right:auto; }
.ar_main_div_<?php echo $form_id;?> #brand-div.top_container,
.ar_main_div_<?php echo $form_id;?> #brand-div.none_container { text-align:center; clear:both; margin-left:auto; margin-right:auto; }

.ar_main_div_<?php echo $form_id;?> #hexagon.left_container { text-align:center; margin-left:auto; margin-right:auto; }
.ar_main_div_<?php echo $form_id;?> #hexagon.right_container { text-align:center; margin-left:auto; margin-right:auto; }
.ar_main_div_<?php echo $form_id;?> #hexagon.top_container, 
.ar_main_div_<?php echo $form_id;?> #hexagon.none_container { text-align:center; margin-left:auto; margin-right:auto; }

<?php } else { ?>
.ar_main_div_<?php echo $form_id;?> .arf_submit_div.left_container { margin-left:<?php echo $label_margin.'px';?>; clear:both; text-align:<?php echo $form_align;?>; }
.ar_main_div_<?php echo $form_id;?> .arf_submit_div.right_container { margin-left:<?php echo '50px';?>; clear:both; text-align:<?php echo $form_align;?>; }
.ar_main_div_<?php echo $form_id;?> .arf_submit_div.top_container,
.ar_main_div_<?php echo $form_id;?> .arf_submit_div.none_container { margin-left:<?php echo '50px';?>; clear:both; text-align:<?php echo $form_align;?>; }

.ar_main_div_<?php echo $form_id;?> .arf_submit_div div.hexagon { margin: <?php echo $submit_margin; ?> }

.ar_main_div_<?php echo $form_id;?> #brand-div { font-size: 10px; color: #444444; }
.ar_main_div_<?php echo $form_id;?> #brand-div.left_container { margin-left:<?php echo $label_margin.'px';?>; text-align:<?php echo $form_align;?>; }
.ar_main_div_<?php echo $form_id;?> #brand-div.right_container { margin-left:<?php echo '50px';?>; text-align:<?php echo $form_align;?>; }
.ar_main_div_<?php echo $form_id;?> #brand-div.top_container { margin-left:<?php echo '50px';?>; text-align:<?php echo $form_align;?>; }

.ar_main_div_<?php echo $form_id;?> #hex.left_container { margin-left:<?php echo ($width_loader/2).'px';?>; text-align:center; }
.ar_main_div_<?php echo $form_id;?> #hex.right_container { margin-left:<?php echo ($width_loader/2).'px';?>; text-align:center; }
.ar_main_div_<?php echo $form_id;?> #hex.top_container,
.ar_main_div_<?php echo $form_id;?> #hex.none_container { margin-left:<?php echo ($width_loader/2).'px';?>; text-align:center; }
<?php }
?>

.ar_main_div_<?php echo $form_id;?> #hexacenter.left_container { margin-left:<?php echo ($label_margin+$width_loader-10).'px';?>; }
.ar_main_div_<?php echo $form_id;?> #hexacenter.right_container { margin-left:<?php echo (40+$width_loader).'px';?>; }
.ar_main_div_<?php echo $form_id;?> #hexacenter.top_container { margin-left:<?php echo (40+$width_loader).'px';?>; }

.ar_main_div_<?php echo $form_id;?> #recaptcha_style { display:inline-block; max-width:100%; }
.ar_main_div_<?php echo $form_id;?> #recaptcha_style .help-block { margin-left:0px; }

.ar_main_div_<?php echo $form_id;?> .recaptcha_style_custom .help-block { margin-left:0px; }

.ar_main_div_<?php echo $form_id;?> div.help-block, .ar_main_div_<?php echo $form_id;?> div.arf_field_description { clear:both; }

.ar_main_div_<?php echo $form_id;?> div.formdescription_style { padding:0; text-align:<?php echo $arfformtitlealign; ?>; width:auto; color:<?php echo $form_title_color; ?>; font-family:<?php echo stripslashes($arf_title_font_family) ?>; } 

.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning .control-label,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning .help-block,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning .help-inline {
  color: <?php echo $arferrorstylecolor;?> ;
  font-family:<?php echo stripslashes($newerror_font) ?>;
  font-size:<?php echo $error_font_size ?>;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning .checkbox,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning .radio,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning input,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning select,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning textarea {
  color: <?php echo $border_color_error;?> !important;
  background-color:<?php echo $bg_color_error ?> !important;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning input,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning select,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning textarea {
  border-color: <?php echo $border_color_error;?> !important;
  background-color:<?php echo $bg_color_error ?> !important;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning input:focus,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning select:focus,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning textarea:focus {
  border-color: <?php echo $border_color_error;?> !important;
  background-color:<?php echo $bg_color_error ?> !important;
  -moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
  -webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
  box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning .input-prepend .add-on,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning .input-append .add-on {
  color: <?php echo $border_color_error;?> !important;
 background-color:<?php echo $bg_color_error ?> !important;
  border-color: <?php echo $border_color_error;?> !important;
    -webkit-box-shadow:none;
     -moz-box-shadow:none;
          box-shadow:none;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .control-label,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .help-block,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .help-inline {
  color: <?php echo $arferrorstylecolor;?> !important;
  font-family:<?php echo stripslashes($newerror_font) ?>;
  font-size:<?php echo $error_font_size ?>;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .checkbox,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .radio,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error input,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error select,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error textarea {
  color: <?php echo $border_color_error;?> !important;
  background-color:<?php echo $bg_color_error ?> !important;
    -webkit-box-shadow:none;
     -moz-box-shadow:none;
          box-shadow:none;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error input,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error select,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error textarea,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error #file-button1 {
  border-color: <?php echo $border_color_error;?> !important;
    -webkit-box-shadow:none;
     -moz-box-shadow:none;
          box-shadow:none;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error input:focus,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error select:focus,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error textarea:focus {
  border-color: <?php echo $border_color_error;?> !important;
  background-color:<?php echo $bg_color_error ?> !important;
  -moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
  -webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
  box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .input-prepend .add-on,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .input-append .add-on {
  color: <?php echo $border_color_error;?> !important;
  background-color:<?php echo $bg_color_error ?> !important;
  border-color: <?php echo $border_color_error;?> !important;
    -webkit-box-shadow:none;
     -moz-box-shadow:none;
          box-shadow:none;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_success .control-label,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success .help-block,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success .help-inline {
  color: <?php echo $text_color?> !important;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_success .checkbox,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success .radio,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success input,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success select,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success textarea {
  color: <?php echo $text_color?> !important;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_success input,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success select,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success textarea,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success #file-button1 {
  border-color: <?php echo $border_color; ?> !important;
    -webkit-box-shadow:none;
     -moz-box-shadow:none;
          box-shadow:none;
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_success input:focus,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success select:focus,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success textarea:focus {
  border-color: <?php echo $border_color_active ?> !important;
  -moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
	-webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
	box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
}

.ar_main_div_<?php echo $form_id;?> .control-group.arf_success .input-prepend .add-on,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_success .input-append .add-on {
  color: <?php echo $text_color?> !important;
  background-color: #dff0d8;
  border-color: <?php echo $border_color_active ?> !important;
    -webkit-box-shadow:none;
     -moz-box-shadow:none;
          box-shadow:none;
}
.help-block ul
{
	margin:0 !important;
}
.help-block li
{
list-style:none;
line-height:15px;	
}

.ar_main_div_<?php echo $form_id;?> .left_container .setting_radio .help-block{margin-left:0px;}
.ar_main_div_<?php echo $form_id;?> .left_container .setting_checkbox .help-block{margin-left:0px;}

.success { background:none !important; border:0px; }
#ui-datepicker-div { display:none; }

.ar_main_div_<?php echo $form_id;?> #hexagon img { -webkit-box-sizing: content-box; -moz-box-sizing: content-box; box-sizing: content-box; }

.ar_main_div_<?php echo $form_id;?> .page_break_nav
{	
	font-size:16px;
    padding:15px 7px;
	margin:3px 1px 3px 1px;
	background:<?php echo $field_bg_inactive_color_pg_break;?>;
	color: <?php echo $field_text_color_pg_break;?>;
    <?php /*?>min-width:70px;<?php */?>
    text-align:center;
   	font-weight:bold;
    line-height: 20px;
	max-width:10%;
    verticle-align:middle;
}
.ar_main_div_<?php echo $form_id;?> .page_nav_selected
{	
	background:<?php echo $field_bg_color_pg_break;?>;    
}
.ar_main_div_<?php echo $form_id;?> .allfields .arf_wizard {
	border:1px solid <?php echo $field_bg_inactive_color_pg_break;?>;
	margin:3px 1% 10px 1%;
    width:98%;
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3), 0 0px 0px rgba(0, 0, 0, 0) inset;
    -moz-box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3), 0 0px 0px rgba(0, 0, 0, 0) inset;
    -moz-border-radius:1px;
    -webkit-border-radius:1px;
    border-radius:1px;
}
.ar_main_div_<?php echo $form_id;?> .arf_wizard {
	border:1px solid <?php echo $field_bg_inactive_color_pg_break;?>;
	margin:3px 1% 10px 1%;
    width:98%;
    -moz-border-radius:1px;
    -webkit-border-radius:1px;
    border-radius:1px;
}
.ar_main_div_<?php echo $form_id;?> .arf_wizard td{
	border:0px;
	padding:15px 5px;
	vertical-align:middle;
}
.ar_main_div_<?php echo $form_id;?> .arf_current_tab_arrow
{	
	border-left: 12px solid rgba(0, 0, 0, 0) !important;
    border-right: 12px solid rgba(0, 0, 0, 0) !important;
    border-top: 9px solid <?php echo $field_bg_color_pg_break;?> !important;
    height: 0;
    margin: auto auto -9px !important;
    width: 0;
}
.ar_main_div_<?php echo $form_id;?> .page_break_nav
{	
    border-right:1px solid rgba(255,255,255,0.7) !important;
}
.ar_main_div_<?php echo $form_id;?> .page_nav_selected,
.ar_main_div_<?php echo $form_id;?> .arf_page_prev,
.ar_main_div_<?php echo $form_id;?> .arf_page_last
{
    border-right:none !important;    
}

<?php echo '/*arf star rating css start*/'; ?>

.ar_main_div_<?php echo $form_id;?> .controls .rating { visibility:hidden; height: 0; padding: 0; width: 0; }
.ar_main_div_<?php echo $form_id;?> .rate_widget { height: 32px; }
.ar_main_div_<?php echo $form_id;?> .ratings_stars_yellow { background: url(<?php echo ARFIMAGESURL; ?>/star-gray.png) no-repeat; float: left; height: 32px; padding: 2px; margin-left:32x; width: 32px; } 
.ar_main_div_<?php echo $form_id;?> .ratings_stars_red { background: url(<?php echo ARFIMAGESURL; ?>/red.png) no-repeat; float: left; height: 32px; padding: 2px; margin-left:2px; width: 32px; }
.ar_main_div_<?php echo $form_id;?> .ratings_stars_green { background: url(<?php echo ARFIMAGESURL; ?>/green.png) no-repeat; float: left; height: 32px; padding: 2px; margin-left:2px; width: 32px; }
.ar_main_div_<?php echo $form_id;?> .ratings_stars_blue { background: url(<?php echo ARFIMAGESURL; ?>/blue.png) no-repeat; float: left; height: 32px; padding: 2px; margin-left:2px; width: 32px; }
.ar_main_div_<?php echo $form_id;?> .ratings_stars_orange { background: url(<?php echo ARFIMAGESURL; ?>/orange.png) no-repeat; float: left; height: 32px; padding: 2px; margin-left:2px; width: 32px; } 
.ar_main_div_<?php echo $form_id;?> .ratings_vote_yellow { background: url(<?php echo ARFIMAGESURL; ?>/star-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_yellow { background: url(<?php echo ARFIMAGESURL; ?>/star-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_red { background: url(<?php echo ARFIMAGESURL; ?>/red-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_red { background: url(<?php echo ARFIMAGESURL; ?>/red-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_green { background: url(<?php echo ARFIMAGESURL; ?>/green-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_green { background: url(<?php echo ARFIMAGESURL; ?>/green-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_blue { background: url(<?php echo ARFIMAGESURL; ?>/blue-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_blue { background: url(<?php echo ARFIMAGESURL; ?>/blue-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_orange { background: url(<?php echo ARFIMAGESURL; ?>/orange-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_orange { background: url(<?php echo ARFIMAGESURL; ?>/orange-color.png) no-repeat; }

.ar_main_div_<?php echo $form_id;?> .ratings_stars_black { background: url(<?php echo ARFIMAGESURL; ?>/black.png) no-repeat; float: left; height: 32px; padding:2px; margin-left:2px; width: 32px; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_black { background: url(<?php echo ARFIMAGESURL; ?>/black-color.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_black { background: url(<?php echo ARFIMAGESURL; ?>/black-color.png) no-repeat; }

.ar_main_div_<?php echo $form_id;?> .ratings_stars_yellow_small { background: url(<?php echo ARFIMAGESURL; ?>/yellow_small.png) no-repeat; float: left; height: 18px; padding:2px; margin:10px 0px 0px 2px; width: 18px; }	
.ar_main_div_<?php echo $form_id;?> .ratings_vote_yellow_small { background: url(<?php echo ARFIMAGESURL; ?>/yellow-color_small.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_yellow_small { background: url(<?php echo ARFIMAGESURL; ?>/yellow-color_small.png) no-repeat; }

.ar_main_div_<?php echo $form_id;?> .ratings_stars_red_small { background: url(<?php echo ARFIMAGESURL; ?>/red_small.png) no-repeat; float: left; height: 18px; padding:2px;  margin:10px 0px 0px 2px; width: 18px; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_red_small { background: url(<?php echo ARFIMAGESURL; ?>/red-color_small.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_red_small { background: url(<?php echo ARFIMAGESURL; ?>/red-color_small.png) no-repeat; }

.ar_main_div_<?php echo $form_id;?> .ratings_stars_orange_small { background: url(<?php echo ARFIMAGESURL; ?>/orange_small.png) no-repeat; float: left; height: 18px; padding:2px;  margin:10px 0px 0px 2px; width: 18px; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_orange_small { background: url(<?php echo ARFIMAGESURL; ?>/orange-color_small.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_orange_small { background: url(<?php echo ARFIMAGESURL; ?>/orange-color_small.png) no-repeat; }


.ar_main_div_<?php echo $form_id;?> .ratings_stars_blue_small { background: url(<?php echo ARFIMAGESURL; ?>/blue_small.png) no-repeat; float: left; height: 18px; padding:2px;  margin:10px 0px 0px 2px; width: 18px; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_blue_small { background: url(<?php echo ARFIMAGESURL; ?>/blue-color_small.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_blue_small { background: url(<?php echo ARFIMAGESURL; ?>/blue-color_small.png) no-repeat; }

.ar_main_div_<?php echo $form_id;?> .ratings_stars_green_small { background: url(<?php echo ARFIMAGESURL; ?>/green_small.png) no-repeat; float: left; height: 18px; padding:2px;  margin:10px 0px 0px 2px; width: 18px; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_green_small { background: url(<?php echo ARFIMAGESURL; ?>/green-color_small.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_green_small { background: url(<?php echo ARFIMAGESURL; ?>/green-color_small.png) no-repeat; }

.ar_main_div_<?php echo $form_id;?> .ratings_stars_black_small { background: url(<?php echo ARFIMAGESURL; ?>/black_small.png) no-repeat; float: left; height: 18px; padding:2px;  margin:10px 0px 0px 2px; width: 18px; }
.ar_main_div_<?php echo $form_id;?> .ratings_vote_black_small { background: url(<?php echo ARFIMAGESURL; ?>/black-color_small.png) no-repeat; }
.ar_main_div_<?php echo $form_id;?> .ratings_over_black_small { background: url(<?php echo ARFIMAGESURL; ?>/black-color_small.png) no-repeat; }

<?php echo '/*arf star rating css end*/'; ?>

.ar_main_div_<?php echo $form_id;?> #hexagon {
	width: <?php echo $submit_height_hex;?>px;	
	height: <?php echo $submit_height_hex;?>px;	
	border-radius: 50%;
	background:<?php echo $submit_bg_color; ?>;	
}

#content .ar_main_div_<?php echo $form_id;?> div.arfsubmitbutton .previous_btn, .ar_main_div_<?php echo $form_id;?> div.arfsubmitbutton .previous_btn { font-weight:<?php echo $submit_weight ?>; <?php echo $submit_weight_font_style;?> }
.ar_main_div_<?php echo $form_id;?> .rate_widget_div { width:<?php echo $field_width; ?>; }

#popup-form-<?php echo $form_id; ?>.arfmodal .arfmodal-header { border-bottom: none; } 
<?php if( $arfmainfield_opacity == 1 ) { ?>
.ar_main_div_<?php echo $form_id;?> input[type=text], 
.ar_main_div_<?php echo $form_id;?> input[type=password], 
.ar_main_div_<?php echo $form_id;?> input[type=email], 
.ar_main_div_<?php echo $form_id;?> input[type=number], 
.ar_main_div_<?php echo $form_id;?> input[type=url], 
.ar_main_div_<?php echo $form_id;?> input[type=tel], 
.ar_main_div_<?php echo $form_id;?> textarea,
.ar_main_div_<?php echo $form_id;?> input[type=text]:focus, 
.ar_main_div_<?php echo $form_id;?> input[type=password]:focus, 
.ar_main_div_<?php echo $form_id;?> input[type=email]:focus, 
.ar_main_div_<?php echo $form_id;?> input[type=number]:focus, 
.ar_main_div_<?php echo $form_id;?> input[type=url]:focus, 
.ar_main_div_<?php echo $form_id;?> input[type=tel]:focus, 
.ar_main_div_<?php echo $form_id;?> textarea:focus,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open .arfbtn.dropdown-toggle,

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group.open .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group.open .arfdropdown-menu,

.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group .arfbtn.dropdown-toggle:focus,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group .arfbtn.dropdown-toggle:focus,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open .arfdropdown-menu:focus,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open .arfdropdown-menu:focus { background-color: transparent !important; }

.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning .checkbox,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning .radio,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning input,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning input:focus,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning select,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_warning textarea,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .checkbox,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .radio,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error input,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error input:focus,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error select,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error textarea,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error textarea:focus {
	background-color: transparent !important;
}
<?php } else { ?>


.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open .arfbtn.dropdown-toggle,
.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open .arfdropdown-menu { background-color: <?php echo $bg_color; ?> !important; }

.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open .arfdropdown-menu:focus,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open .arfdropdown-menu:focus { background-color: <?php echo $bg_color_active; ?> !important; }
<?php } ?>
.ar_main_div_<?php echo $form_id;?> span.arfcheckrequiredfield { color:<?php echo $label_color ?> !important; font-style: normal; font-weight: normal; }
.ar_main_div_<?php echo $form_id;?> h2.pos_left, .ar_main_div_<?php echo $form_id;?> h2.pos_top, .ar_main_div_<?php echo $form_id;?> h2.pos_right { color:<?php echo $label_color ?>; }

.ar_main_div_<?php echo $form_id;?> input:not([type=submit], [type=button]) { margin:0 !important; }
.arfmodal-body { max-height:1000px; }

.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_front .btn-group .arfdropdown-menu,
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .sltstandard_time .btn-group .arfdropdown-menu { background-color: <?php echo $bg_color_error; ?> !important; }

.ar_main_div_<?php echo $form_id;?> .file_main_control { width: <?php if($field_width_unit=='px' && $field_width!='' && $field_width!='auto') { echo $field_width; } else { echo $field_width; } ?> }
.ar_main_div_<?php echo $form_id;?> .arf_file_field { width: 100% }

.ar_main_div_<?php echo $form_id;?> .arfsubmitbutton .arf_submit_btn {
    height:<?php echo $submit_height ?>;
    width:<?php echo ($submit_width == '') ? 'auto' : $submit_width ?>;
    max-width:100%;
    margin:<?php echo $submit_margin ?>; 
    font-weight:<?php echo $submit_weight; ?>;
    font-family:<?php echo stripslashes($arfsubmitfontfamily) ?>;
    font-size:<?php echo $submit_font_size; ?>;
    <?php echo $submit_weight_font_style;?> 
    cursor:pointer;
    outline:none;
    
    background:<?php echo $submit_bg_color ?><?php if(!empty($submit_bg_img)){ ?> url(<?php echo $submit_bg_img; ?>)<?php } ?>;
    background-position: left top;	
    filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);      
    -ms-filter:"progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='<?php echo $submit_shadow_color; ?>')";
    filter:progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='<?php echo $submit_shadow_color; ?>'); 
    
    padding:0 10px;
    vertical-align:top;
    text-transform: none;
    color:<?php echo $submit_text_color ?> !important;
    border:<?php echo $submit_border_width ?> solid <?php echo $submit_border_color ?>;
	
    text-shadow:none;
    -moz-box-sizing:content-box;
    -ms-box-sizing:content-box;
    box-sizing:content-box;
    
    
    -moz-border-radius:<?php echo $submit_border_radius ?>;
    -webkit-border-radius:<?php echo $submit_border_radius ?>;
    border-radius:<?php echo $submit_border_radius ?>;
    
    -moz-box-shadow:1px 2px 3px <?php echo $submit_shadow_color; ?>;
    -webkit-box-shadow:1px 2px 3px <?php echo $submit_shadow_color; ?>;
    box-shadow:1px 2px 3px <?php echo $submit_shadow_color; ?>;
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn:hover{
	<?php if(!empty($submit_hover_bg_img) && !empty($submit_bg_img)){ ?>
		background-image:url(<?php echo $submit_hover_bg_img; ?>) !important;
        background-color:<?php echo $submit_bg_color_hover ?> !important;
    <?php }else { ?>
    	background-image:none !important;
		background-color:<?php echo $submit_bg_color_hover ?> !important;
    <?php } ?>
}

<?php
	// for hieght range 
	$submit_height_wpx = ( $submit_height_wpx == '' ) ? '35' : $submit_height_wpx;
	$submit_width_wpx  = ( $submit_width_wpx == '' ) ? '150' : $submit_width_wpx;
	
	if( $submit_height_wpx < 25 ){										//below 25
		$logo_margin = '-8px';
		$logo_p_margin = '9px';
		$spinner_margin = '40px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
			
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-14px';
			$spinner_margin_left = '-'.($submit_width_d2+10);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-13px';
			$spinner_margin_left = '-'.($submit_width_d2+10);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-12px';
			$spinner_margin_left = '-'.($submit_width_d2+10);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-9px';
			$spinner_margin_left = '-'.($submit_width_d2+7);
		} else {			
			$spinner_margin_top = '-10px';
			$spinner_margin_left = '-'.($submit_width_d2+10);				
		}
						
		$perspective 		= '100px';
		$transform_origin 	= '4px';
		$b_width 			= '8px';	
		$b_div_width 		= '8px';	
		$translateX 		= '7px';
		$translateX_70		= '7px';	
		$translateX_60		= '4px';		
		$b_div_width_extra  = '2px';
	} else if( $submit_height_wpx < 35 and $submit_height_wpx >= 25 ){	//betwen 25-34
		$logo_margin = '-8px';
		$logo_p_margin = '9px';
		$spinner_margin = '40px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
					
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-14px';
			$spinner_margin_left = '-'.($submit_width_d2+10);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-13px';
			$spinner_margin_left = '-'.($submit_width_d2+10);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-12px';
			$spinner_margin_left = '-'.($submit_width_d2+10);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-9px';
			$spinner_margin_left = '-'.($submit_width_d2+7);
		} else {			
			$spinner_margin_top = '-10px';
			$spinner_margin_left = '-'.($submit_width_d2+10);				
		}		
						
		$perspective 		= '100px';
		$transform_origin 	= '4px';
		$b_width 			= '8px';	
		$b_div_width 		= '8px';	
		$translateX 		= '7px';
		$translateX_70		= '7px';	
		$translateX_60		= '4px';
		$b_div_width_extra  = '2px';
	} else if( $submit_height_wpx > 35 and $submit_height_wpx <= 49 ){	//betwen 36-49
		$logo_margin = '2px';
		$logo_p_margin = '18px';
		$spinner_margin = '40px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
			
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-14px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-13px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-12px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-9px';
			$spinner_margin_left = '-'.($submit_width_d2+4);
		} else {			
			$spinner_margin_top = '-10px';
			$spinner_margin_left = '-'.($submit_width_d2+6);				
		}		
						
		$perspective 		= '100px';
		$transform_origin 	= '4px';
		$b_width 			= '8px';	
		$b_div_width 		= '8px';	
		$translateX 		= '7px';
		$translateX_70		= '7px';	
		$translateX_60		= '4px';
		$b_div_width_extra  = '2px';
	} else if( $submit_height_wpx > 49 and $submit_height_wpx <= 60 ){	//betwen 50-60
		$logo_margin = '22px';
		$logo_p_margin = '24px';
		$spinner_margin = '40px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
			
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-17px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-15px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-14px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-11px';
			$spinner_margin_left = '-'.($submit_width_d2+4);
		} else {			
			$spinner_margin_top = '-12px';
			$spinner_margin_left = '-'.($submit_width_d2+6);				
		}
						
		$perspective 		= '150px';
		$transform_origin 	= '6px';
		$b_width 			= '12px';	
		$b_div_width 		= '12px';	
		$translateX 		= '14px';
		$translateX_70		= '14px';	
		$translateX_60		= '8px';		
		$b_div_width_extra  = '2px';
	} else if( $submit_height_wpx > 60 and $submit_height_wpx <= 70 ){	//betwen 61-70
		$logo_margin = '23px';
		$logo_p_margin = '23px';
		$spinner_margin = '50px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
				
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-19px';
			$spinner_margin_left = '-'.($submit_width_d2+8);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-17px';
			$spinner_margin_left = '-'.($submit_width_d2+8);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-16px';
			$spinner_margin_left = '-'.($submit_width_d2+8);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-13px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else {			
			$spinner_margin_top = '-14px';
			$spinner_margin_left = '-'.($submit_width_d2+8);				
		}
						
		$perspective 		= '200px';
		$transform_origin 	= '8px';
		$b_width 			= '16px';	
		$b_div_width 		= '16px';	
		$translateX 		= '21px';
		$translateX_70		= '21px';	
		$translateX_60		= '12px';
		$b_div_width_extra  = '3px';
	} else if( $submit_height_wpx > 70 and $submit_height_wpx <= 80 ){	//betwen 71-80
		$logo_margin = '27px';
		$logo_p_margin = '27px';
		$spinner_margin = '60px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
			
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-19px';
			$spinner_margin_left = '-'.($submit_width_d2+11);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-17px';
			$spinner_margin_left = '-'.($submit_width_d2+11);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-16px';
			$spinner_margin_left = '-'.($submit_width_d2+11);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-13px';
			$spinner_margin_left = '-'.($submit_width_d2+9);
		} else {			
			$spinner_margin_top = '-14px';
			$spinner_margin_left = '-'.($submit_width_d2+11);				
		}		
						
		$perspective 		= '150px';
		$transform_origin 	= '10px';
		$b_width 			= '18px';	
		$b_div_width 		= '18px';	
		$translateX 		= '26px';
		$translateX_70		= '26px';	
		$translateX_60		= '14px';
		$b_div_width_extra  = '3px';
	} else if( $submit_height_wpx > 80 and $submit_height_wpx <= 90 ){	//betwen 81-90
		$logo_margin = '30px';
		$logo_p_margin = '29px';
		$spinner_margin = '70px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
			
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-21px';
			$spinner_margin_left = '-'.($submit_width_d2+13);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-18px';
			$spinner_margin_left = '-'.($submit_width_d2+13);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-17px';
			$spinner_margin_left = '-'.($submit_width_d2+13);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-15px';
			$spinner_margin_left = '-'.($submit_width_d2+11);
		} else {			
			$spinner_margin_top = '-16px';
			$spinner_margin_left = '-'.($submit_width_d2+13);				
		}		
						
		$perspective 		= '150px';
		$transform_origin 	= '11px';
		$b_width 			= '20px';	
		$b_div_width 		= '20px';	
		$translateX 		= '30px';
		$translateX_70		= '30px';	
		$translateX_60		= '15px';
		$b_div_width_extra  = '4px';
	} else if( $submit_height_wpx > 90 and $submit_height_wpx <= 100 ){	//betwen 91-100
		$logo_margin = '36px';
		$logo_p_margin = '36px';
		$spinner_margin = '80px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
				
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-23px';
			$spinner_margin_left = '-'.($submit_width_d2+17);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-21px';
			$spinner_margin_left = '-'.($submit_width_d2+17);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-20px';
			$spinner_margin_left = '-'.($submit_width_d2+17);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-17px';
			$spinner_margin_left = '-'.($submit_width_d2+15);
		} else {			
			$spinner_margin_top = '-18px';
			$spinner_margin_left = '-'.($submit_width_d2+17);				
		}			
						
		$perspective 		= '200px';
		$transform_origin 	= '13px';
		$b_width 			= '22px';	
		$b_div_width 		= '22px';	
		$translateX 		= '35px';
		$translateX_70		= '35px';	
		$translateX_60		= '17px';	
		$b_div_width_extra  = '4px';
	} else if( $submit_height_wpx > 100 ){								// < 100
		$logo_margin = '38px';
		$logo_p_margin = '38px';
		$spinner_margin = '90px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
			
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-24px';
			$spinner_margin_left = '-'.($submit_width_d2+20);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-22px';
			$spinner_margin_left = '-'.($submit_width_d2+20);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-21px';
			$spinner_margin_left = '-'.($submit_width_d2+20);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-18px';
			$spinner_margin_left = '-'.($submit_width_d2+18);
		} else {			
			$spinner_margin_top = '-19px';
			$spinner_margin_left = '-'.($submit_width_d2+20);				
		}	
						
		$perspective 		= '250px';
		$transform_origin 	= '14px';
		$b_width 			= '24px';	
		$b_div_width 		= '24px';	
		$translateX 		= '40px';
		$translateX_70		= '40px';	
		$translateX_60		= '19px';				
		$b_div_width_extra  = '5px';
	} else {															// 36
		$logo_margin = '-3px';
		$logo_p_margin = '14px';
		$spinner_margin = '40px';
		
		$submit_width_d2 = ($submit_width_wpx / 2);		
			
		if( $submit_font_size_wpx > 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top = '-14px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else if( $submit_font_size_wpx > 24 and $submit_font_size_wpx <= 32 ){
			$spinner_margin_top = '-13px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else if( $submit_font_size_wpx > 18 and $submit_font_size_wpx <= 24 ){
			$spinner_margin_top = '-12px';
			$spinner_margin_left = '-'.($submit_width_d2+6);
		} else if( $submit_font_size_wpx < 17 ){
			$spinner_margin_top = '-9px';
			$spinner_margin_left = '-'.($submit_width_d2+4);
		} else {			
			$spinner_margin_top = '-10px';
			$spinner_margin_left = '-'.($submit_width_d2+6);				
		}
				
		$perspective 		= '100px';
		$transform_origin 	= '4px';
		$b_width 			= '8px';	
		$b_div_width 		= '8px';	
		$translateX 		= '7px';
		$translateX_70		= '7px';	
		$translateX_60		= '4px';	
		$b_div_width_extra  = '2px';
	}
?>
.arf_submit_btn.arfstyle-button .arfstyle-spinner {
  z-index: 2;
  display: block;
  opacity: 0;
  filter:alpha(opacity=0);
  pointer-events: none; 
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button .arfstyle-label {
  z-index: 1;
  width:<?php echo ( $submit_width_wpx + 2 ).'px'; ?>;
  max-width:100%;
  <?php if($submit_bg_img != ''){ ?> text-indent:-9999px;<?php }else{?>text-indent:0px;<?php }?> 
}

.arfstyle-button[data-style=zoom-in],
.arfstyle-button[data-style=zoom-in] .arfstyle-label,
.arfstyle-button[data-style=zoom-in] .arfstyle-spinner {
    -webkit-transition: 0.3s ease all !important;
    -moz-transition: 0.3s ease all !important;
    -ms-transition: 0.3s ease all !important;
    -o-transition: 0.3s ease all !important;
    transition: 0.3s ease all !important; 
}

.arfstyle-button[data-style=zoom-in] {
  overflow: hidden; 
}

.ar_main_div_<?php echo $form_id;?> .arfstyle-spinner {
  -webkit-transform: scale(1);
  -moz-transform: scale(1);
  -ms-transform: scale(1);
  -o-transform: scale(1);
  transform: scale(1); 
}

.ar_main_div_<?php echo $form_id;?> .arfstyle-button[data-style=zoom-in] .arfstyle-label {
  display: inline-block;
  width:<?php echo ( $submit_width == '' ) ? 'auto' : ($submit_width_wpx+2).'px'; ?>;
  margin-right: -5px; 
}

.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-label {
  opacity: 0;
  -webkit-transform: scale(2.2);
  -moz-transform: scale(2.2);
  -ms-transform: scale(2.2);
  -o-transform: scale(2.2);
  transform: scale(2.2); 
}
.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner {
  opacity: 1;
  -webkit-transform: none;
  -moz-transform: none;
  -ms-transform: none;
  -o-transform: none;
  transform: none; 
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading
{
	background-color:<?php echo $submit_bg_color_hover ?> !important;
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo{
	<?php
		if( $submit_width <= 100 ){
	?>
    	left: -3px;
    <?php
		} else if( $submit_width > 100 and $submit_width <= 150 ) {
	?>
    	left: -2px;
    <?php
		} else if( $submit_width > 150 and $submit_width <= 200 ) {
	?>
    	left: -1px;
    <?php
		} else if( $submit_width > 200 ){
	?>
    	left: 0;
    <?php
		}
	?>
    
    float:left;
    <?php
	$extra_width = 0;   	
    ?>
	/*margin-left:<?php // echo $spinner_margin_left.'px'; ?>;*/
    
    <?php 
		$spinner_form_width = str_replace('px','',$form_width);
		$spinner_form_width = str_replace(';','',$spinner_form_width);
		
		
		
		/*--------------------*/
		$submit_button_center_percent = "";
		if($submit_height_wpx == $submit_width_wpx){ 
           $submit_button_center_percent = "35%";
        } 
		elseif(($submit_width_wpx / 2 ) < $submit_height_wpx) 
		{ 
			$gab_between_height_width =  $submit_width_wpx - $submit_height_wpx;
			if($gab_between_height_width >= 1 && $gab_between_height_width <= 20){
                $submit_button_center_percent = "37%";
            }
			elseif($gab_between_height_width >= 21 && $gab_between_height_width <= 30)
			{
                $submit_button_center_percent = "40%";
            }
			else 
			{
                $submit_button_center_percent = "45%";
            }
    	} 
		else 
		{
        	$submit_button_center_percent = "47%";
    	}
        
        if($submit_height_wpx == $submit_width_wpx)
		{
           $submit_button_center_percent = "35%";
        } 
		elseif($submit_width_wpx  > $submit_height_wpx) 
		{		
			$gab_between_width =  $submit_width_wpx - $submit_height_wpx;
			if($gab_between_width >= 1 && $gab_between_width <= 10){
				$submit_button_center_percent = "42%";
			}
			elseif($gab_between_width >= 11 && $gab_between_width <= 20)
			{
				$submit_button_center_percent = "42%";
			}
			elseif($gab_between_width >= 21 && $gab_between_width <= 30)
			{
				$submit_button_center_percent = "41%";
			}
			elseif($gab_between_width >= 31 && $gab_between_width <= 35)
			{
				$submit_button_center_percent = "45%";
			}
			elseif($gab_between_width >= 36 && $gab_between_width <= 40)
			{
				$submit_button_center_percent = "43%";
			}
			elseif($gab_between_width >= 41 && $gab_between_width <= 50)
			{
				$submit_button_center_percent = "41%";
			}
			elseif($gab_between_width >= 51 && $gab_between_width <= 60)
			{
				$submit_button_center_percent = "43%";
			}
			elseif($gab_between_width >= 61 && $gab_between_width <= 80)
			{
				$submit_button_center_percent = "45%";
			}
			elseif($gab_between_width >= 81 && $gab_between_width <= 110)
			{
				$submit_button_center_percent = "49%";
			}
			elseif($gab_between_width >= 111 && $gab_between_width <= 120)
			{
				$submit_button_center_percent = "49%";
			}elseif($gab_between_width >80)
			{
				$submit_button_center_percent = "48.5%";
			} else {
				$submit_button_center_percent = "47%";
			}
        }
		elseif($submit_height_wpx > $submit_width_wpx ) 
		{
			$gab_between_height =  $submit_height_wpx - $submit_width_wpx;
			if($gab_between_height >= 1 && $gab_between_height <= 20)
			{
				$submit_button_center_percent = "35%";
			}elseif($gab_between_height >= 21 && $gab_between_height <= 30){
				$submit_button_center_percent = "33%";
			}elseif($gab_between_height >= 31 && $gab_between_height <= 40){ 
				$submit_button_center_percent = "30%";
			}elseif($gab_between_height >= 41 && $gab_between_height <= 50){
				$submit_button_center_percent = "25%";
			}elseif($gab_between_height >= 51 && $gab_between_height <= 60){
				$submit_button_center_percent = "18%";
			} else {
				$submit_button_center_percent = "47%";
			} 
	} else {
		$submit_button_center_percent = "49%";	
    }
		/*----------------------*/
	
		if($submit_width_wpx > $spinner_form_width ){ ?>
			margin-left:<?php echo $submit_button_center_percent;?>;
            
    <?php }else { ?>
			margin-left:<?php echo ($submit_width_wpx/2)-($b_div_width/2)-$b_div_width_extra;?>px;
    <?php } ?>
    
   
    
   <?php 
  /* 	$spinner_height_middle = (-(($submit_height_wpx/2)-($b_div_width)))-$b_div_width_extra;
    
    if($spinner_height_middle>"-11") { $spinner_height_middle = "-11"; }
	?>
    
    top:<?php echo $spinner_height_middle;?>px;
	*/?>
    
    <?php
		$spinner_margin_top = str_replace('px','',$spinner_margin_top);
		$spinner_margin_top = str_replace(';','',$spinner_margin_top);
		if( $submit_font_size_wpx >= 32 and $submit_font_size_wpx <= 40 ){
			$spinner_margin_top -= 12;
		} else {
			$spinner_margin_top -= 5;
		}
		$spinner_margin_top = $spinner_margin_top.'px';
	?>
    top:<?php echo $spinner_margin_top; ?>;
    
    margin-bottom:0px;
    position:relative;
      
    -webkit-perspective: <?php echo $perspective; ?>;
    -webkit-animation: base-cycle 2s linear infinite;
    -webkit-transform-origin: <?php echo $transform_origin.' '.$transform_origin;?>;
    -webkit-perspective-origin: <?php echo $transform_origin.' '.$transform_origin;?>;
    
    -moz-perspective: <?php echo $perspective; ?>;
    -moz-animation: base-cycle 2s linear infinite;
    -moz-transform-origin: <?php echo $transform_origin.' '.$transform_origin;?>;
    -moz-perspective-origin: <?php echo $transform_origin.' '.$transform_origin;?>;
    
    perspective: <?php echo $perspective; ?>;
    animation: base-cycle 2s linear infinite;
    transform-origin: <?php echo $transform_origin.' '.$transform_origin;?>;
    perspective-origin: <?php echo $transform_origin.' '.$transform_origin;?>;
    
    zoom: 1;
}

@media (max-width: 480px) {
    .ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo {
        margin-left:<?php echo $submit_button_center_percent;?>;
    }
}

/*.arfpreivewform .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo { 
	margin-left: <?php echo $spinner_margin_left.'px'; ?>; 
}*/

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .b{
  width: <?php echo $b_width; ?>;
  height: <?php echo $b_width; ?>;
  position: absolute;
  -webkit-transform-style: preserve-3d;
  -moz-transform-style: preserve-3d;
  transform-style: preserve-3d;
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .b div{ 
  width: <?php echo $b_div_width; ?>;
  height: <?php echo $b_div_width; ?>;
  border-radius: 100%;
  position: absolute;
  left: 0;
  top: 0;
  -webkit-transform-style: preserve-3d;
  -moz-transform-style: preserve-3d;
  transform-style: preserve-3d;
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .arfred{}
.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .arfyellow{ 
  -webkit-transform: rotate(90deg); 
  -moz-transform: rotate(90deg); 
  transform: rotate(90deg)
}
.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .arfgreen{ 
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  transform: rotate(180deg)
}
.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .arfblue{ 
  -webkit-transform: rotate(270deg);
  -moz-transform: rotate(270deg);
  transform: rotate(270deg);
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .arfred div {
  background-color: <?php echo $submit_text_color ?>;
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .arfyellow div {
  background-color: <?php echo $submit_text_color ?>;
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .arfblue div {
  background-color: <?php echo $submit_text_color ?>;
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .arfgreen div {
  background-color: <?php echo $submit_text_color ?>;
}

.ar_main_div_<?php echo $form_id;?> .arf_submit_btn.arfstyle-button[data-style=zoom-in].data-loading .arfstyle-spinner .arflogo .b div{
  -webkit-animation: cycle_<?php echo $form_id;?> 2s ease-out infinite;
  -moz-animation: cycle_<?php echo $form_id;?> 2s ease-out infinite;
  animation: cycle_<?php echo $form_id;?> 2s ease-out infinite;
}

@-webkit-keyframes base-cycle {
  0%{ 
    -webkit-transform: rotate(0);
  }
  100%{ 
    -webkit-transform: rotate(360deg);
  }
}

@-moz-keyframes base-cycle {
  0%{ 
    -moz-transform: rotate(0);
  }
  100%{ 
    -moz-transform: rotate(360deg);
  }
}

@keyframes base-cycle {
  0%{ 
    transform: rotate(0)
  }
  100%{ 
    transform: rotate(360deg)
  }
}


@-webkit-keyframes cycle_<?php echo $form_id;?> {
  0%   { 
    -webkit-transform: translateX( <?php echo $translateX; ?> ) rotateY( 0deg );
  }
  60%  { 
    -webkit-transform: translateX( 0 ) rotateY(0deg);
    background-color: <?php echo $submit_text_color ?>; }
  70%  { 
    -webkit-transform: translateX( <?php echo $translateX_60; ?> ) rotateY( 90deg );
  }
  100% { 
    -webkit-transform: translateX( <?php echo $translateX_70; ?> ) rotateY( 0deg );
  }
}

@-moz-keyframes cycle_<?php echo $form_id;?> {
  0%   { 
    -moz-transform: translateX( <?php echo $translateX; ?> ) rotateY( 0deg );
  }
  60%  { 
    -moz-transform: translateX( 0 ) rotateY(0deg);
    background-color: <?php echo $submit_text_color ?>; }
  70%  { 
    -moz-transform: translateX( <?php echo $translateX_60; ?> ) rotateY( 90deg );
  }
  100% { 
    -moz-transform: translateX( <?php echo $translateX_70; ?> ) rotateY( 0deg );
  }
}

@keyframes cycle_<?php echo $form_id;?> {
  0%   { 
    transform: translateX( <?php echo $translateX; ?> ) rotateY( 0deg )
  }
  60%  { 
    transform: translateX( 0 ) rotateY(0deg);
    background-color: <?php echo $submit_text_color ?>; }
  70%  { 
    transform: translateX( <?php echo $translateX_60; ?> ) rotateY( 90deg )
  }
  100% { 
    transform: translateX( <?php echo $translateX_70; ?> ) rotateY( 0deg )
  }
}

.ar_main_div_<?php echo $form_id;?> .arfajax-file-upload {
	font-family:<?php echo stripslashes($newfontother) ?>;
    font-size:<?php echo $field_font_size ?>;
    height:<?php echo $field_height;?>; 
    font-weight:<?php echo $check_weight ?>; 
	<?php echo $check_weight_font_style;?>
    padding: 7px <?php echo $file_upload_padding.'px';?> 5px <?php echo $file_upload_padding.'px';?> !important;
}

.ar_main_div_<?php echo $form_id;?> .ajax-file-remove {
	font-family:<?php echo stripslashes($newfontother) ?>;
}

.ar_main_div_<?php echo $form_id;?> .arfajax-file-upload-img {
	border: medium none !important;
    border-radius: 0 0 0 0 !important;
    box-shadow: none !important;
    height: <?php echo $file_upload_hw; ?>;
    width: <?php echo $file_upload_hw; ?>;
    float:left;
    margin-top: <?php echo $file_upload_margin_top; ?>;
    background: url(<?php echo ARFIMAGESURL.'/'.$file_upload_bg; ?>) no-repeat;
}

.ar_main_div_<?php echo $form_id;?> #arf_message_success .msg-detail {
text-align:left !important;
}
<?php 
if($arfcheck_style_name=="flat")
{
	if($arfcheck_style_color!="default" && $arfcheck_style_color!="#default" && $arfcheck_style_color!="")
	{
		$checkpos = strpos($arfcheck_style_color,'#');
		if($checkpos !== false)
		{
			$arfcheck_style_color = substr($arfcheck_style_color,1);
			$style_property = $arfcheck_style_name."-".$arfcheck_style_color;
			$style_property_image = strtolower($arfcheck_style_color);
		}
		else
		{
			$style_property = $arfcheck_style_name."-".$arfcheck_style_color;
			$style_property_image = strtolower($arfcheck_style_color);
		}	
	}
	else
	{
		$style_property = $arfcheck_style_name;
		$style_property_image = "flat";
	}	
	
?>
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    display: inline-block;
    *display: inline;
    vertical-align: middle;
    margin: -2px 7px 0px 0px;
    padding: 0;
    width: 20px;
    height: 20px;
    background: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>.png) no-repeat;
    border: none;
    cursor: pointer;
}

.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?> {
    background-position: 0 0;
}
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked {
        background-position: -22px 0;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.disabled {
        background-position: -44px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked.disabled {
        background-position: -66px 0;
    }

.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    background-position: -88px 0;
}
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked {
        background-position: -110px 0;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.disabled {
        background-position: -132px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked.disabled {
        background-position: -154px 0;
    }

/* Retina support */
@media only screen and (-webkit-min-device-pixel-ratio: 1.5),
       only screen and (-moz-min-device-pixel-ratio: 1.5),
       only screen and (-o-min-device-pixel-ratio: 3/2),
       only screen and (min-device-pixel-ratio: 1.5) {
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
        background-image: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>@2x.png);
        -webkit-background-size: 176px 22px;
        background-size: 176px 22px;
    }
}
<?php	
}
if($arfcheck_style_name=="minimal")
{
	if($arfcheck_style_color!="default" && $arfcheck_style_color!="#default" && $arfcheck_style_color!="")
	{
		$checkpos = strpos($arfcheck_style_color,'#');
		if($checkpos !== false)
		{
			$arfcheck_style_color = substr($arfcheck_style_color,1);
			$style_property = $arfcheck_style_name."-".$arfcheck_style_color;
			$style_property_image = strtolower($arfcheck_style_color);
		}
		else
		{
			$style_property = $arfcheck_style_name."-".$arfcheck_style_color;
			$style_property_image = strtolower($arfcheck_style_color);
		}
	}
	else
	{
		$style_property = $arfcheck_style_name;
		$style_property_image = "minimal";
	}
?>
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    display: inline-block;
    *display: inline;
    vertical-align: middle;
    margin: -2px 7px 0px 0px;
    padding: 0;
    width: 18px;
    height: 18px;
    background: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>.png) no-repeat;
    border: none;
    cursor: pointer;
}

.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?> {
    background-position: 0 0;
}
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.arf_hover {
        background-position: -20px 0;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked {
        background-position: -40px 0;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.disabled {
        background-position: -60px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked.disabled {
        background-position: -80px 0;
    }

.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    background-position: -100px 0;
}
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.arf_hover {
        background-position: -120px 0;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked {
        background-position: -140px 0;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.disabled {
        background-position: -160px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked.disabled {
        background-position: -180px 0;
    }

/* Retina support */
@media only screen and (-webkit-min-device-pixel-ratio: 1.5),
       only screen and (-moz-min-device-pixel-ratio: 1.5),
       only screen and (-o-min-device-pixel-ratio: 3/2),
       only screen and (min-device-pixel-ratio: 1.5) {
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
        background-image: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>@2x.png);
        -webkit-background-size: 200px 20px;
        background-size: 200px 20px;
    }
}		
<?php	
}
if($arfcheck_style_name=="square")
{
	if($arfcheck_style_color!="default" && $arfcheck_style_color!="#default" && $arfcheck_style_color!="")
	{
		$checkpos = strpos($arfcheck_style_color,'#');
		if($checkpos !== false)
		{
			$arfcheck_style_color = substr($arfcheck_style_color,1);
			$style_property = $arfcheck_style_name."-".$arfcheck_style_color;
			$style_property_image = strtolower($arfcheck_style_color);
		}
		else
		{
			$style_property = $arfcheck_style_name."-".$arfcheck_style_color;
			$style_property_image = strtolower($arfcheck_style_color);
		}
	}
	else
	{
		$style_property = $arfcheck_style_name;
		$style_property_image = "square";
	}
?>
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    display: inline-block;
    *display: inline;
    vertical-align: middle;
    margin: -2px 7px 0px 0px;
    padding: 0;
    width: 22px;
    height: 22px;
    background: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>.png) no-repeat;
    border: none;
    cursor: pointer;
}

.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?> {
    background-position: 0 0;
}
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.arf_hover {
        background-position: -24px 0;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked {
        background-position: -48px 0;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.disabled {
        background-position: -72px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked.disabled {
        background-position: -96px 0;
    }

.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    background-position: -120px 0;
}
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.arf_hover {
        background-position: -144px 0;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked {
        background-position: -168px 0;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.disabled {
        background-position: -192px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked.disabled {
        background-position: -216px 0;
    }

/* Retina support */
@media only screen and (-webkit-min-device-pixel-ratio: 1.5),
       only screen and (-moz-min-device-pixel-ratio: 1.5),
       only screen and (-o-min-device-pixel-ratio: 3/2),
       only screen and (min-device-pixel-ratio: 1.5) {
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
        background-image: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>@2x.png);
        -webkit-background-size: 240px 24px;
        background-size: 240px 24px;
    }
}
<?php
}
if($arfcheck_style_name=="futurico")
{
	$style_property = $arfcheck_style_name;
	$style_property_image = "futurico";
?>
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    display: inline-block;
    *display: inline;
    vertical-align: middle;
    margin: -2px 7px 0px 0px;
    padding: 0;
    width: 16px;
    height: 17px;
    background: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>.png) no-repeat;
    border: none;
    cursor: pointer;
}

.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?> {
    background-position: 0 0;
}
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked {
        background-position: -18px 0;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.disabled {
        background-position: -36px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked.disabled {
        background-position: -54px 0;
    }

.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    background-position: -72px 0;
}
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked {
        background-position: -90px 0;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.disabled {
        background-position: -108px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked.disabled {
        background-position: -126px 0;
    }

/* Retina support */
@media only screen and (-webkit-min-device-pixel-ratio: 1.5),
       only screen and (-moz-min-device-pixel-ratio: 1.5),
       only screen and (-o-min-device-pixel-ratio: 3/2),
       only screen and (min-device-pixel-ratio: 1.5) {
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
        background-image: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>@2x.png);
        -webkit-background-size: 144px 19px;
        background-size: 144px 19px;
    }
}	
<?php
}
if($arfcheck_style_name=="polaris")
{
	$style_property = $arfcheck_style_name;
	$style_property_image = "polaris";
?>
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    display: inline-block;
    *display: inline;
    vertical-align: middle;
    margin: -2px 7px 0px 0px;
    padding: 0;
    width: 29px;
    height: 29px;
    background: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>.png) no-repeat;
    border: none;
    cursor: pointer;
}

.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?> {
    background-position: 0 0;
}
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.arf_hover {
        background-position: -31px 0;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked {
        background-position: -62px 0;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.disabled {
        background-position: -93px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>.checked.disabled {
        background-position: -124px 0;
    }

.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
    background-position: -155px 0;
}
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.arf_hover {
        background-position: -186px 0;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked {
        background-position: -217px 0;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.disabled {
        background-position: -248px 0;
        cursor: default;
    }
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?>.checked.disabled {
        background-position: -279px 0;
    }

/* Retina support */
@media only screen and (-webkit-min-device-pixel-ratio: 1.5),
       only screen and (-moz-min-device-pixel-ratio: 1.5),
       only screen and (-o-min-device-pixel-ratio: 3/2),
       only screen and (min-device-pixel-ratio: 1.5) {
.ar_main_div_<?php echo $form_id;?> .icheckbox_<?php echo $style_property?>,
.ar_main_div_<?php echo $form_id;?> .iradio_<?php echo $style_property?> {
        background-image: url(<?php echo ARFURL;?>/images/skins/<?php echo $arfcheck_style_name;?>/<?php echo $style_property_image;?>@2x.png);
        -webkit-background-size: 310px 31px;
        background-size: 310px 31px;
    }
}
<?php
}
if($arfcheck_style_name=="none")
{
?>
	.ar_main_div_<?php echo $form_id;?> .arf_checkbox_style label, .ar_main_div_<?php echo $form_id;?> .arf_radiobutton:not(#foo) > label {font-size:<?php echo $field_font_size; ?>; color:<?php echo $text_color;?>; font-family:<?php echo stripslashes($newfontother) ?>;font-weight:<?php echo $check_weight ?>; <?php echo $check_weight_font_style;?> }

.ar_main_div_<?php echo $form_id;?> .ar_main_div_<?php echo $form_id;?> .arf_checkbox_style img, .ar_main_div_<?php echo $form_id;?> .ar_main_div_<?php echo $form_id;?> .arf_radiobutton img {
	border: none;
}
.ar_main_div_<?php echo $form_id;?> .arf_checkbox_style input[type="checkbox"], .ar_main_div_<?php echo $form_id;?> .arf_radiobutton input[type="radio"] {
	padding: 0; height: auto; width: auto; float: none; left: auto; position:inherit; opacity:1; margin-right:5px;
}
.ar_main_div_<?php echo $form_id;?> .arf_checkbox_style label, .ar_main_div_<?php echo $form_id;?> .arf_radiobutton label {
	display:inline-block !important;
    margin-bottom:0px;
}
<?php
}
?>
.ar_main_div_<?php echo $form_id;?> .file_name_info {
	font-family:<?php echo stripslashes($newfontother) ?>;
    font-size:<?php echo $field_font_size ?>; 
    font-weight:<?php echo $check_weight ?>; 
	<?php echo $check_weight_font_style;?>
    color:<?php echo $text_color ?> !important;
}

.ar_main_div_<?php echo $form_id;?> .sltstandard_front .btn-group.open .arfdropdown-menu.open,
.ar_main_div_<?php echo $form_id;?> .sltstandard_time .btn-group.open .arfdropdown-menu.open { 
	border-bottom-left-radius:<?php echo $border_radius ?>; 
    border-bottom-right-radius:<?php echo $border_radius ?>;
    border-top:1px <?php echo $field_border_style ?> <?php echo $border_color_active ?>;
    -moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
	-webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
	box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_active);?>, 0.4);
}

.ar_main_div_<?php echo $form_id;?> .arfformfield .controls { width:<?php echo $field_width; ?>; }

.ar_main_div_<?php echo $form_id;?> .popover
{
	background-color: <?php echo $arferrorstylecolor; ?> !important;
}
.ar_main_div_<?php echo $form_id;?> .popover.right .arrow:after {
    border-right-color: <?php echo $arferrorstylecolor; ?> !important;
} 
.ar_main_div_<?php echo $form_id;?> .popover.left .arrow:after {
    border-left-color: <?php echo $arferrorstylecolor; ?> !important;
}
.ar_main_div_<?php echo $form_id;?> .popover.top .arrow:after {
    border-top-color: <?php echo $arferrorstylecolor; ?> !important;
} 
.ar_main_div_<?php echo $form_id;?> .popover.bottom .arrow:after {
    border-bottom-color: <?php echo $arferrorstylecolor; ?> !important;
}
.ar_main_div_<?php echo $form_id;?> .popover-content
{
	color: <?php echo $arferrorstylecolorfont;?> !important;
    font-family:<?php echo stripslashes($newerror_font) ?>;
    font-size:<?php echo $error_font_size ?>;
    line-height:normal;
}

.ar_main_div_<?php echo $form_id;?> .arf_strenth_mtr .inside_title {
	font-family:<?php echo stripslashes($description_font) ?>;font-size:<?php echo $description_font_size ?>;color:<?php echo $description_color ?>;text-align:left;font-style:<?php echo $description_style ?>;
}

.ar_main_div_<?php echo $form_id;?> .arfsubmitbutton .arf_submit_btn.arfsubmitdisabled:hover {    
    background:<?php echo $submit_bg_color ?><?php if(!empty($submit_bg_img)){ ?> url(<?php echo $submit_bg_img; ?>)<?php } ?> !important;
}

<?php /*?>.ar_main_div_<?php echo $form_id;?> .arfpagebreakform .arfsubmitbutton {
	margin-top:<?php echo $field_margin; ?>;
}<?php */?>
.ar_main_div_<?php echo $form_id;?> .arf_survey_nav { color:<?php echo $arf_text_color_survey;?>; font-family:<?php echo stripslashes($newfont) ?>; font-size: 14px; line-height: 1.5; }
.ar_main_div_<?php echo $form_id;?> #arf_progress_bar.ui-progress-bar { background:<?php echo $arf_bg_color_survey;?> !important; }
.ar_main_div_<?php echo $form_id;?> #arf_progress_bar.ui-progress-bar .ui-progressbar-value { background-color:<?php echo $arf_bar_color_survey;?> !important; font-family:<?php echo stripslashes($newfont) ?>; }

<?php 
// css for color picker control start
$colorpickerpadding = "0";
$padding_array = explode(" ", $arffieldpaddingsetting);
$colorpickerpadding = $padding_array[1] ? $padding_array[1] : "0";
$colorpickerpadding	= @trim( str_replace('px', '', $colorpickerpadding) );

$colorpickerpaddingtop 	= $padding_array[0] ? $padding_array[0] : "0";
$colorpickerpaddingtop	= @trim( str_replace('px', '', $colorpickerpaddingtop) );

$colorpickerfield_border_width = @trim( str_replace('px', '', $field_border_width) );
$colorpickerheight		= ( ($field_font_size_without_px) + ($colorpickerpaddingtop*2) ); //+ (2*$colorpickerfield_border_width)

$colorpickerheight_new	= ( ($field_font_size_without_px) + ($colorpickerpaddingtop*2) ) + (2*$colorpickerfield_border_width);

$colorpickerheight_new	= $colorpickerheight_new < 20 ? 20 : $colorpickerheight_new;

$arfcolorpickerfullheight = $colorpickerheight_new;
$colorpickerwidth1			= 148;
$colvaluewidth				= 109;
$arfcolorpickerfullwidth	= 15;	
$arfcolorpickerfullpadding	= "0 13px";
if($colorpickerheight_new < 30){
	$arfcolorpickerheight 		= $colorpickerheight_new - 6;
	$colorpickerpaddingtop		= 6;
	$colorpickerwidth			= $colorpickerwidth1 + (2*$colorpickerfield_border_width);
	$colvaluewidth				= $colorpickerwidth - $colorpickerfield_border_width - 15 -5;  	
	$colrpick_upload_bg 		= '16';
} else if($colorpickerheight_new < 36){
	$arfcolorpickerheight 		= $colorpickerheight_new - 8;
	$colorpickerpaddingtop		= 8;
	$colorpickerwidth			= $colorpickerwidth1 + (2*$colorpickerfield_border_width);
	$colvaluewidth				= $colorpickerwidth - $colorpickerfield_border_width - 15 -5; 	
	$colrpick_upload_bg 		= '16';
} else if($colorpickerheight_new < 41){
	$arfcolorpickerheight 		= $colorpickerheight_new - 10;
	$colorpickerpaddingtop		= 10;
	$colorpickerwidth			= $colorpickerwidth1 + (2*$colorpickerfield_border_width);
	$colvaluewidth				= $colorpickerwidth - $colorpickerfield_border_width - 15 -5; 	
	$colrpick_upload_bg 		= '16';
} else if($colorpickerheight_new < 46){
	$arfcolorpickerheight 		= $colorpickerheight_new - 12;
	$colorpickerpaddingtop		= 12;
	$colorpickerwidth			= $colorpickerwidth1 + (2*$colorpickerfield_border_width);
	$colvaluewidth				= $colorpickerwidth - $colorpickerfield_border_width - 15 -5; 	
	$colrpick_upload_bg 		= '16';
} else if($colorpickerheight_new < 51){
	$arfcolorpickerheight 		= $colorpickerheight_new - 14;
	$colorpickerpaddingtop		= 14;
	$colorpickerwidth			= $colorpickerwidth1 + (2*$colorpickerfield_border_width);
	$colvaluewidth				= $colorpickerwidth - $colorpickerfield_border_width - 24 -5; 	
	$arfcolorpickerfullwidth	= 24;
	$colrpick_upload_bg 		= '22';
} else {
	$arfcolorpickerheight 		= $colorpickerheight_new - 16;
	$colorpickerpaddingtop		= 16;
	$colorpickerwidth			= $colorpickerwidth1 + (2*$colorpickerfield_border_width);
	$colvaluewidth				= $colorpickerwidth - $colorpickerfield_border_width - 24 -5; 	
	$arfcolorpickerfullwidth	= 24;
	$colrpick_upload_bg 		= '22';
}

$colorvaluemargin = $arfcolorpickerfullwidth + $colorpickerfield_border_width;  

$border_radius_pxx	= str_replace('px', '', $border_radius);
$border_radius_px2	= ( $border_radius_pxx < 2 ) ? 0 : $border_radius_pxx-1;
$border_radius_pxx	= ( $border_radius_pxx < 3 ) ? 0 : $border_radius_pxx-2; 
?>
.ar_main_div_<?php echo $form_id;?> .arfcolorpickerfield {
	border:<?php echo $colorpickerfield_border_width.'px';?> <?php echo $field_border_style; ?> <?php echo $border_color;?>;
	width:<?php echo $colorpickerwidth;?>px;
	height:<?php echo $arfcolorpickerfullheight.'px';?>;
	-webkit-border-radius:<?php echo $border_radius;?>;
	-moz-border-radius:<?php echo $border_radius;?>;
	border-radius:<?php echo $border_radius;?>;
	cursor:pointer;
}
.ar_main_div_<?php echo $form_id;?> .arfcolorpickerfield .arfcolorimg {
	height:<?php echo $arfcolorpickerfullheight.'px';?>;
	width:<?php echo $arfcolorpickerfullwidth.'px';?>;
	background:<?php echo $prefix_suffix_bg_color;?>;
	background-repeat:no-repeat;
	background-position:center center;
	border-right:<?php echo $colorpickerfield_border_width.'px';?> <?php echo $field_border_style; ?> <?php echo $border_color;?>;
	-webkit-border-radius:<?php echo $border_radius_px2.'px';?> 0 0 <?php echo $border_radius_px2.'px';?>;
	-moz-border-radius:<?php echo $border_radius_px2.'px';?> 0 0 <?php echo $border_radius_px2.'px';?>;
	border-radius:<?php echo $border_radius_px2.'px';?> 0 0 <?php echo $border_radius_px2.'px';?>;
	float:left;
    font-size:<?php echo $colrpick_upload_bg;?>;
    padding:<?php echo $arfcolorpickerfullpadding;?>;
}
.ar_main_div_<?php echo $form_id;?> .arfcolorpickerfield .arfcolorimg i.fa-paint-brush {
	height:<?php echo $arfcolorpickerfullheight.'px';?>;
    line-height:<?php echo $arfcolorpickerfullheight.'px';?>;
    color:<?php echo $prefix_suffix_icon_color;?>;
}

.ar_main_div_<?php echo $form_id;?> .arfcolorvalue {
	color: #333333;
    <?php /*?>float: left;<?php */?>
    height:<?php echo $arfcolorpickerheight.'px';?>;
    padding-left: 5px;
    padding-right: 5px;
    padding-top:<?php echo $colorpickerpaddingtop.'px';?>;
    margin-left:<?php echo $colorvaluemargin.'px';?>;
    vertical-align: middle;
    <?php /*?>width: <?php echo $colvaluewidth;?>px;<?php */?>
    width:auto;
    background:<?php echo $bg_color;?>;
    -webkit-border-radius:0 <?php echo $border_radius_pxx.'px';?> <?php echo $border_radius_pxx.'px';?> 0;
	-moz-border-radius:0 <?php echo $border_radius_pxx.'px';?> <?php echo $border_radius_pxx.'px';?> 0;
	border-radius:0 <?php echo $border_radius_pxx.'px';?> <?php echo $border_radius_pxx.'px';?> 0;
    font-family:Arial, Helvetica, sans-serif;
    font-size: 15px;
    line-height:normal;
    text-align:<?php echo $text_direction == 'rtl' ? 'right' : 'left'; ?>;
}
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .arfcolorpickerfield {
	border: <?php echo $border_width_error ?> <?php echo $field_border_style; ?> <?php echo $border_color_error ?>;    
}
.ar_main_div_<?php echo $form_id;?> .control-group.arf_error .arfcolorpickerfield:focus {
	-moz-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
	-webkit-box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);
	box-shadow:0px 0px 2px rgba(<?php echo $arsettingcontroller->hex2rgb($border_color_error);?>, 0.4);	
}
.ar_main_div_<?php echo $form_id;?> .arf_heading_div{
	padding:<?php echo $section_padding; ?>;
}

<?php
	
	if( isset( $form_id ) && !empty( $form_id ) ){
		
		global $arfieldhelper;
		$fields = $arfieldhelper->get_form_fields_tmp(false, $form_id, false, 0);
		if( isset( $fields ) && count( $fields ) > 0 ){
			foreach( $fields as $field ){
				$field_type = '';
				
				if( $field->type == 'text' or $field->type == 'email'  or $field->type == 'number' or $field->type == 'time' or $field->type == 'date'  )
					$field_type = 'text';
				else if( $field->type == 'phone' )
					$field_type = 'tel';
				else if( $field->type == 'image' )
					$field_type = 'url';
				else
					$field_type = $field->type;
				if( $field->field_options['enable_arf_prefix'] == 1){
				$field->id = $arfieldhelper->get_actual_id($field->id);	
					
					$arf_prefix_padding = '';
					$arf_prefix_width = '';
					$arf_prefix_padding = '0 10px';
					
					if( $field_width_unit == '%' ){
						if( $field_font_size < 18 )
							$arf_prefix_width = '20px;';
						else if( $field_font_size >= 18 and $field_font_size < 22 )
							$arf_prefix_width = '20px;';
						else if( $field_font_size >= 22 and $field_font_size < 26 )
							$arf_prefix_width = '20px;';
						else if( $field_font_size >= 26 and $field_font_size < 32 )
							$arf_prefix_width = '11%;';
						else if( $field_font_size >= 32 and $field_font_size <= 40 )
							$arf_prefix_width = '14%;';
					} else {
						
						$fldwd = '';
						$fldwd = str_replace('px','',$field_width);
						
					 
						if( $field_font_size < 14 ){
							$fldwd = ( $fldwd * 8 ) / 100;
							$arf_prefix_width = $fldwd.'px';
						}else if( $field_font_size < 16 and $field_font_size >= 14 ){
							$fldwd = ( $fldwd * 9 ) / 100;
							$arf_prefix_width = $fldwd.'px';
						}else if( $field_font_size < 18 and $field_font_size >= 16 ){
							$fldwd = ( $fldwd * 10 ) / 100;
							$arf_prefix_width = $fldwd.'px';
						}else if( $field_font_size < 22 and $field_font_size >= 18 ){
							$fldwd = ( $fldwd * 11 ) / 100;
							$arf_prefix_width = $fldwd.'px';
						}else if( $field_font_size < 26 and $field_font_size >= 22 ){
							$fldwd = ( $fldwd * 12 ) / 100;
							$arf_prefix_width = $fldwd.'px';
						}else if( $field_font_size < 32 and $field_font_size >= 26 ){
							$fldwd = ( $fldwd * 13 ) / 100;
							$arf_prefix_width = $fldwd.'px'; 
						}else if( $field_font_size < 36 and $field_font_size >= 32 ){
							$fldwd = ( $fldwd * 14 ) / 100;
							$arf_prefix_width = $fldwd.'px';
						}else if( $field_font_size <= 40 and $field_font_size >= 36 ){
							$fldwd = ( $fldwd * 15 ) / 100;
							$arf_prefix_width = $fldwd.'px';
						}
					}
					
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_prefix';
					if( $field_type == 'password' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_prefix';
					if( $field->type == 'email' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_prefix';
					echo '{
						display:table-cell;
						width:'.$arf_prefix_width.';
						padding:'.$arf_prefix_padding.';
						vertical-align:middle;
						color:'.$prefix_suffix_icon_color.';
						text-align:center;
						background:'.$prefix_suffix_bg_color.';
						border:'.$field_border_width.' '.$field_border_style.' '.$border_color.';';
						if( is_rtl() ){
							echo '	border-top-right-radius:'.$border_radius.';
									border-bottom-right-radius:'.$border_radius.';';
						} else {
							echo '	border-top-left-radius:'.$border_radius.';
							border-bottom-left-radius:'.$border_radius.';';
						}
							
					echo '}';
					
					echo "@media (min-width:290px) and (max-width:480px){";
						
						echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_prefix';
						if( $field_type == 'password' )
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_prefix';
						if( $field->type == 'email' )
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_prefix';
						echo '{
							display:table-cell;
							width:40px !important;
							padding:0 !important;
							vertical-align:middle;
							text-align:center;
							color:'.$prefix_suffix_icon_color.';
							background:'.$prefix_suffix_bg_color.';
							border:'.$field_border_width.' '.$field_border_style.' '.$border_color.';';
							if( is_rtl() ){
								echo '	border-top-right-radius:'.$border_radius.';
										border-bottom-right-radius:'.$border_radius.';';
							} else {
								echo '	border-top-left-radius:'.$border_radius.';
								border-bottom-left-radius:'.$border_radius.';';
							}
								
						echo '}';
						
					echo "}";
										
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_prefix.arf_prefix_focus';
					if( $field_type == 'password' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_prefix.arf_prefix_focus';
					if( $field->type == 'email' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_prefix.arf_prefix_focus';
					echo '{
						border-color:'.$border_color_active.' !important;
						transition:all 0.4s ease 0s;
						-webkit-transition:all 0.4s ease 0s;
						-moz-transition:all 0.4s ease 0s;
						-o-transition:all 0.4s ease 0s;
						box-shadow:0 0 2px rgba('.$arsettingcontroller->hex2rgb( $border_color_active ).',0.4);
						-moz-box-shadow:0 0 2px rgba('.$arsettingcontroller->hex2rgb( $border_color_active ).',0.4);
						-webkit-box-shadow:0 0 2px rgba('.$arsettingcontroller->hex2rgb( $border_color_active ).',0.4);
						-o-box-shadow:0 0 2px rgba('.$arsettingcontroller->hex2rgb( $border_color_active ).',0.4);
						
					}';
					
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_prefix i';
					if( $field_type == 'password' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_prefix i';
					if( $field->type == 'email' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_prefix i';
					echo '{
						font-size:'.$field_font_size.' !important;
					}';
					
					echo "@media (min-width:290px) and (max-width:480px){";
						echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_prefix i';
						if( $field_type == 'password' )
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_prefix i';
						if( $field->type == 'email' )
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_prefix i';
						echo '{
							font-size:20px !important;
						}';
					echo "}";
					
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container.arf_error .arf_prefix,
					.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container.arf_warning .arf_prefix';
					if( $field_type == 'password' ){
						echo ',.ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.'.arf_error .arf_prefix,
							.ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.'.arf_warning .arf_prefix';
					}
					if( $field->type == 'email' ){
						echo ',.ar_main_div_'.$form_id.'  .arf_confirm_email_field_'.$field->id.'.arf_error .arf_prefix,
							.ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.'.arf_warning .arf_prefix';
					}
					echo '{ border-color:'.$border_color_error.' !important;
						transition:all 0.4s ease 0s;
						-webkit-transition:all 0.4s ease 0s;
						-moz-transition:all 0.4s ease 0s;
						-o-transition:all 0.4s ease 0s;
					}';
					
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container input';
					if( $field_type == 'password' )
						echo ',.ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' input';
					if( $field->type == 'email' )
						echo ',.ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' input';
					if( is_rtl() ){
						echo '{
									border-right:none !important;
									border-top-right-radius:0px !important;
									border-bottom-right-radius:0px !important;
								}';
					} else {
						echo '{
							border-left:none !important;
							border-top-left-radius:0px !important;
							border-bottom-left-radius:0px !important;
						}';
					}
				
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container input';
					if( $field_type == 'password' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' input';
					if( $field->type == 'email' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' input';
					echo '{
						width:100% !important;
					}';
					

				}
				if( $field->field_options['enable_arf_suffix'] == 1){
					
				$field->id = $arfieldhelper->get_actual_id($field->id);	
					$arf_suffix_padding = '';
					$arf_suffix_width = '';
					
					if( $field_width_unit == '%' ){
						$arf_suffix_padding = '0 10px';
						
						if( $field_font_size < 18 )
							$arf_suffix_width = '20px;';
						else if( $field_font_size >= 18 and $field_font_size < 22 )
							$arf_suffix_width = '20px;';
						else if( $field_font_size >= 22 and $field_font_size < 26 )
							$arf_suffix_width = '20px;';
						else if( $field_font_size >= 26 and $field_font_size < 32 )
							$arf_suffix_width = '11%;';
						else if( $field_font_size >= 32 and $field_font_size <= 40 )
							$arf_suffix_width = '14%;';
					
					
					} else {
						$arf_suffix_padding = '0 10px';
												
						$fldwd = '';
						$fldwd = str_replace('px','',$field_width);
						
						if( $field_font_size < 14 ){
							$fldwd = ( $fldwd * 8 ) / 100;
							$arf_suffix_width = $fldwd.'px';
						}else if( $field_font_size < 16 and $field_font_size >= 14 ){
							$fldwd = ( $fldwd * 9 ) / 100;
							$arf_suffix_width = $fldwd.'px';
						}else if( $field_font_size < 18 and $field_font_size >= 16 ){
							$fldwd = ( $fldwd * 10 ) / 100;
							$arf_suffix_width = $fldwd.'px';
						}else if( $field_font_size < 22 and $field_font_size >= 18 ){
							$fldwd = ( $fldwd * 11 ) / 100;
							$arf_suffix_width = $fldwd.'px';
						}else if( $field_font_size < 26 and $field_font_size >= 22 ){
							$fldwd = ( $fldwd * 12 ) / 100;
							$arf_suffix_width = $fldwd.'px';
						}else if( $field_font_size < 32 and $field_font_size >= 26 ){
							$fldwd = ( $fldwd * 13 ) / 100;
							$arf_suffix_width = $fldwd.'px'; 
						}else if( $field_font_size < 36 and $field_font_size >= 32 ){
							$fldwd = ( $fldwd * 14 ) / 100;
							$arf_suffix_width = $fldwd.'px';
						}else if( $field_font_size <= 40 and $field_font_size >= 36 ){
							$fldwd = ( $fldwd * 15 ) / 100;
							$arf_suffix_width = $fldwd.'px';
						}
						
					}
						
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_suffix';
					if( $field_type == 'password' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_suffix';
					if( $field->type == 'email' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_suffix';
					echo '{
						display:table-cell;
						width:'.$arf_suffix_width.';
						text-align:center;
						padding:'.$arf_suffix_padding.';
						vertical-align:middle;
						color:'.$prefix_suffix_icon_color.';
						background:'.$prefix_suffix_bg_color.';
						border:'.$field_border_width.' '.$field_border_style.' '.$border_color.';';
					if( is_rtl() ){
						echo 'border-top-left-radius:'.$border_radius.';
							border-bottom-left-radius:'.$border_radius.';';
					} else {
						echo 'border-top-right-radius:'.$border_radius.';
							border-bottom-right-radius:'.$border_radius.';';
					}
					echo '}';
					
					echo "@media (min-width:290px) and (max-width:480px){";
						
						echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_suffix';
						if( $field_type == 'password' )
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_suffix';
						if( $field->type == 'email' )
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_suffix';
						echo '{
							display:table-cell;
							width:40px !important;
							padding:0 !important;
							vertical-align:middle;
							text-align:center;
							color:'.$prefix_suffix_icon_color.';
							background:'.$prefix_suffix_bg_color.';
							border:'.$field_border_width.' '.$field_border_style.' '.$border_color.';';
							if( is_rtl() ){
								echo 'border-top-left-radius:'.$border_radius.';
									border-bottom-left-radius:'.$border_radius.';';
							} else {
								echo 'border-top-right-radius:'.$border_radius.';
									border-bottom-right-radius:'.$border_radius.';';
							}
								
						echo '}';
						
					echo "}";
					
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_suffix i';
					if( $field_type == 'password' )
						echo ',	.ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_suffix i';
					if( $field->type == 'email' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_suffix i';
					echo '{
						font-size:'.$field_font_size.' !important;
					}';
					
					echo "@media (min-width:290px) and (max-width:480px){";
						echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_suffix i';
						if( $field_type == 'password' )
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_suffix i';
						if( $field->type == 'email' )
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_suffix i';
						echo '{
							font-size:20px !important;
						}';
					echo "}";
										
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_suffix.arf_suffix_focus';
					if( $field_type == 'password' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_suffix.arf_suffix_focus';
					if( $field->type == 'email' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_suffix.arf_suffix_focus';
					echo '{
						border-color:'.$border_color_active.' !important;
						transition:all 0.4s ease 0s;
						-webkit-transition:all 0.4s ease 0s;
						-moz-transition:all 0.4s ease 0s;
						-o-transition:all 0.4s ease 0s;
						box-shadow:0 0 2px rgba('.$arsettingcontroller->hex2rgb( $border_color_active ).',0.4);
						-moz-box-shadow:0 0 2px rgba('.$arsettingcontroller->hex2rgb( $border_color_active ).',0.4);
						-webkit-box-shadow:0 0 2px rgba('.$arsettingcontroller->hex2rgb( $border_color_active ).',0.4);
						-o-box-shadow:0 0 2px rgba('.$arsettingcontroller->hex2rgb( $border_color_active ).',0.4);
					}';
					
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container.arf_error .arf_suffix,
					.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container.arf_warning .arf_suffix';
					if( $field_type == 'password' ){
						echo ',	.ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.'.arf_error .arf_suffix,
								.ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.'.arf_warning .arf_suffix';
					}
					if( $field->type == 'email' ){
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.'.arf_error .arf_suffix,
								.ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.'.arf_warning .arf_suffix';
					}
					echo '{
						border-color:'.$border_color_error.' !important;
						transition:all 0.4s ease 0s;
						-webkit-transition:all 0.4s ease 0s;
						-moz-transition:all 0.4s ease 0s;
						-o-transition:all 0.4s ease 0s;
					}';
					
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container input';
					if( $field_type == 'password' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' input';
					if( $field->type == 'email' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' input';
					if( is_rtl() ){
						echo '{
							border-left:none !important;
							border-top-left-radius:0px !important;
							border-bottom-left-radius:0px !important;
						}';
					} else {
						echo '{
							border-right:none !important;
							border-top-right-radius:0px !important;
							border-bottom-right-radius:0px !important;
						}';
					}
				
					echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container input';
					if( $field_type == 'password' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' input';
					if( $field->type == 'email' )
						echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' input';
					echo '{
						width:100% !important;
					}';
					
				
				}
				if( $field->field_options['enable_arf_prefix'] == 1 || $field->field_options['enable_arf_suffix'] == 1 ){
						echo '.ar_main_div_'.$form_id.' #arf_field_'.$field->id.'_container .arf_prefix_suffix_wrapper';
						if( $field_type == 'password' )
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_password_field_'.$field->id.' .arf_prefix_suffix_wrapper';
						if( $field->type == 'email' ){
							echo ', .ar_main_div_'.$form_id.' .arf_confirm_email_field_'.$field->id.' .arf_prefix_suffix_wrapper';
						}
						echo '{';
							if( $field_width_unit == '%' ){
								echo 'width:'.$field_width.';';
							} else if( $field_width_unit == 'px' ){
								echo 'width:'.($field_width-$field_border_width).'px;';
							}
						echo '}';
				}
			}
		}
	}
	
	
	do_action('arf_outsite_print_style', $new_values,$use_saved,$form_id);
?>

<?php // css for color picker control end ?>