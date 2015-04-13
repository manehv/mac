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
?>
<style>
.lblsubheading
{
	font-size:13px !important;
	padding:5px 10px;
}
.subfield .lblsubheading {
    color: #999999;
    font-size: 12px !important;
}
.subfield {
    padding-left: 0px !important;
}
.formroller_label {
    font-size: 13px !important;
}
<?php
	if(is_rtl())
	{
		?>
		div#arfmainform_opacity_exsSlider{
			float:left;
			margin-left:0;
		}
		<?php
	}
?>
</style>
<?php 
						global $arfform, $arformhelper, $arformcontroller, $armainhelper;
						if($is_ref_form == 1)
							$form = $arfform->getAll(array('id' => $id), '', 1,1);
						else
							$form = $arfform->getAll(array('id' => $id), '', 1);
							
						$pre_link = $arformhelper->get_direct_link($form->form_key);
						$width = @$_COOKIE['width'] * 0.80;	
						$width_new = '&width='.$width;
						?>
						<?php 
global $wpdb,$arfadvanceerrcolor;

if($is_ref_form == 1){
	$res = $wpdb->get_results( $wpdb->prepare("SELECT options FROM ".$wpdb->prefix."arf_ref_forms WHERE id = %d", $id), 'ARRAY_A');
} else {
	$res = $wpdb->get_results( $wpdb->prepare("SELECT options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $id), 'ARRAY_A');
}

$res = $res[0];
		
$values_nw = maybe_unserialize($res['options']); 
$values_nw['display_title_form'] = isset($values_nw['display_title_form']) ? $values_nw['display_title_form'] : '';

if( $values_nw['display_title_form'] == '0') {	
	$title = false; 
	$description = false;
} else {
	$title = true; 	
	$description = true;	
}

?>
<script type="text/javascript" language="javascript">
/*jQuery(document).ready(function(){
	try {
		jQuery(".sltstandard1 select").selectize();
    }
    catch(err) {
        // Handle error(s) here
    }
});*/
</script>

<a id="formstylingbookmark" name="formstylingbookmark"></a>
<div id="postbox-container-1">

    <div class="postbox arffieldlist" id="arfmainfieldlist">
	
    <div class="inside">
	
    <div id="arffieldlist_subtitle" style="background-color:#e0e3ea; padding:5px 10px 0px 20px; font-weight:bold; color:#353942; height:25px; border-bottom:3px solid #d1d3d7;"><img align="absmiddle"  src="<?php echo ARFIMAGESURL ?>/fields_elements_icon/field-element-icon_small.png">&nbsp;&nbsp;<?php _e('Basic Form Elements','ARForms'); ?></div>	

    <div id="taxonomy-linkcategory" class="categorydiv">

        <div style="clear:both;"></div>
		<?php
			$basic_opts_style = '';
			if(is_rtl())
			{
				$basic_opts_style = 'padding-left:50px;';
			}
		?>

		<div class="rightside" style=" <?php echo $basic_opts_style; ?>" >
			<div style="float:left;">
        	<div style="float:left;">
            	
			<ul class="field_type_list" style="float:left;">


            <?php 


            $col_class = 'frm_col_one';
			
			
			$array_img = apply_filters('arf_all_field_image_for_editor' ,array(
			
			
			'text' => 'line.png',


            'textarea' => 'paragraff-icon.png',


            'checkbox' => 'checkbox-icon.png',


            'radio' => 'radiobutton-icon.png',


            'select' => 'dropdown-icon.png',


            'captcha' => 'captcha-icon.png',
			

			'email' => 'emailaddress-icon.png',


            'url' => 'website-icon.png',


            'divider' => 'sectionhead-icon.png',


            'break' => 'pagebreak-icon.png',


            'file' => 'fileupload-icon.png',


            'number' => 'number-icon.png', 


            'phone' => 'phone-icon.png', 


            'date' => 'date-icon.png', 


            'time' => 'time-icon.png',


            'image' => 'imageurl-icon.png', 


            'scale' => 'scale-icon.png',


            'hidden' => 'hiddenfield-icon.png', 


            'password'  => 'password-icon.png',


            'html' => 'html-icon.png',


			'like' => 'votting-icon.png',
			
			
			'slider' => 'slider-icon.png',
			
			
			'colorpicker' => 'color-picker-icon.png',
			
			'imagecontrol' => 'image-icon.png',
			
			
			
			));
			
			$array_fieldclass = apply_filters('arf_all_field_css_class_for_editor',array(
			
			
			'text' => 'orange',


            'textarea' => 'purple',


            'checkbox' => 'blue',


            'radio' => 'yellow',


            'select' => 'red',


            'captcha' => 'green',
			

			'email' => 'yellow',


            'url' => 'purple',


            'divider' => 'blue',


            'break' => 'orange',


            'file' => 'green',


            'number' => 'red', 


            'phone' => 'orange', 


            'date' => 'blue', 


            'time' => 'purple',


            'image' => 'yellow', 


            'scale' => 'red',


            'hidden' => 'green', 


            'password'  => 'blue',


            'html' => 'orange',


			'like' => 'purple',
			
			
			'slider' => 'yellow',
			
			
			'colorpicker' => 'blue',
			
			
			'imagecontrol' => 'green',
			));
			
			$i = 0;
            foreach ($arffield_selection as $field_key => $field_type){ ?>
				

                
				<?php
					if(is_rtl())
					{
						$floating_style = 'float:right;';
					}
					else
					{
						$floating_style = 'float:left;';
					}
				?>
                 <li style=" <?php echo $floating_style; ?> margin-bottom: 10px; width: 139px;" class="frmbutton <?php echo $col_class ?> frm_t<?php echo $field_key ?> <?php if( $i % 2 == 0 ) echo 'arffield_odd'; else echo 'arffield_even'; ?>" id="<?php echo $field_key ?>">
                <div class="form-field">
                	<div class="icon-bg_<?php echo $array_fieldclass[ $field_key ];?>"><img src="<?php echo ARFIMAGESURL.'/fields_elements_icon/'.$array_img[ $field_key ]; ?>" /></div>
                    <div class="arrow_<?php echo $array_fieldclass[ $field_key ];?>"></div>
                    <div class="element-name"><a href="javascript:add<?php echo $field_key ?>field(<?php echo $id ?>);"><?php echo $field_type ?></a></div>
                </div>
                </li>

             <?php


             $col_class = (empty($col_class)) ? 'frm_col_one' : '';
			$i++;	

             } ?>

			<?php do_action('arfafteradvancefieldlisting', $id, $is_ref_form, $values); ?>
             </ul>
			 
             </div>
            </div>
       </div>

             <div class="clear"></div>
			
            <div id="arffieldlist_subtitle" style="background-color:#e0e3ea; padding:5px 10px 0px 20px; font-weight:bold; color:#353942; height:25px; border-bottom:3px solid #d1d3d7;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/fields_elements_icon/field-element-icon_small.png">&nbsp;&nbsp;<?php _e('Advanced Form Elements','ARForms'); ?></div>
            
            
			 <div class="rightside" id="arfadvancefields">
			<div style="float:left; width:100%; height:320px;">
			 <ul<?php echo apply_filters('arffielddrag', '') ?>  style="float:left;">


             <?php $col_class = 'frm_col_one';
	
			 $i = 0;
			 global $arfieldhelper;
             foreach ($arfieldhelper->pro_field_selection() as $field_key => $field_type){ ?>


                 
                <li style=" <?php echo $floating_style; ?> margin-bottom: 10px;width: 139px;" class="frmbutton <?php echo $col_class ?> frm_t<?php echo $field_key ?> <?php if( $i % 2 == 0 ) echo 'arffield_odd'; else echo 'arffield_even'; ?>" id="<?php echo $field_key ?>" >
                <div class="form-field">
                	<div class="icon-bg_<?php echo $array_fieldclass[ $field_key ];?>"><img src="<?php echo ARFIMAGESURL.'/fields_elements_icon/'.$array_img[ $field_key ]; ?>" /></div>
                    <div class="arrow_<?php echo $array_fieldclass[ $field_key ];?>"></div>
                    <div class="element-name"><?php echo apply_filters('arfaddnewfieldlinks',$field_type, $id, $field_key) ?></div>
                </div>
                </li>
			
            <?php 


            $col_class = (empty($col_class)) ? 'frm_col_one' : '';

			$i++;
            } ?>
			
            <?php do_action('arfafterbasicfieldlisting', $id, $is_ref_form, $values); ?>

             </ul>

			 </div>
             </div>
             </div>
             <div class="clear"></div>
             
           
        
        
    </div>
    
    </div>
<?php
$data = "";
if($is_ref_form == 1){
	$data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_ref_forms WHERE id = %d", $id), 'ARRAY_A');
} else {
	$data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $id), 'ARRAY_A');
}
$aweber_arr = "";
$aweber_arr   = $data[0]['form_css'];

$arr = maybe_unserialize($aweber_arr);

$newarr = array();
foreach($arr as $k => $v)
	$newarr[$k] = $v;

foreach($newarr as $k => $v){	
	if( strpos($v,'#') === FALSE ) 
	{
		if( ( preg_match('/color/', $k) or in_array($k, array('arferrorbgsetting', 'arferrorbordersetting', 'arferrortextsetting')) ) && ! in_array($k, array('arfcheckradiocolor') )  ) { 
			$newarr[$k] = '#'.$v;
		} else {
			$newarr[$k] = $v;		
		}
	}			
}

global $arrecordcontroller;
$browser_info = $arrecordcontroller->getBrowser($_SERVER['HTTP_USER_AGENT']);
?>
<script type="text/javascript">
function delete_submit_bg_img(){

	if( confirm('Are you sure you want do delete this image ?') ){
		<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ ?>
		jQuery.ajax({type:"POST",url:ajaxurl,xhrFields:{withCredentials: true}, data:"action=delete_submit_bg_img_IE89",
			success:function(msg)
			{
				jQuery('#submit_btn_img_div').html(msg); formChange1();
				
				jQuery('#submit_btn_img_div').addClass("iframe_submit_original_btn");
				jQuery('#submit_btn_img_div').css("background","#1BBAE1");
				//jQuery('#submit_btn_img_div').css("float","left");
				jQuery('#submit_btn_img_div').css("padding","7px 10px 0 10px");
				jQuery('#submit_btn_img_div').css('border','1px solid #CCCCCC');
				//jQuery('#submit_btn_img_div').css('box-shadow','0px 0px 2px rgba(0, 0, 0, 0.4)');
				jQuery('#submit_btn_img_div').css('border-radius','3px');
				jQuery('#arfsbis_iframe').contents().find('#iframe_form').trigger("reset");
				jQuery('#submit_btn_img_div').append('<div id="arfsbis_iframe_div"><iframe style="display:none;" id="arfsbis_iframe" src="<?php echo ARFURL;?>/core/views/iframe.php"></iframe></div>');
			}
		});	
		<?php }else { ?>
		jQuery.ajax({type:"POST",url:ajaxurl,xhrFields:{withCredentials: true}, data:"action=delete_submit_bg_img",
			success:function(msg){ jQuery('#submit_btn_img_div').html(msg); formChange1(); }
		});	
		<?php } ?>
		
	
	} else {	
		return false;
	}

}

function delete_submit_hover_bg_img(){

	if( confirm('Are you sure you want do delete this image ?') ){
		<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ ?>
		jQuery.ajax({type:"POST",url:ajaxurl,xhrFields:{withCredentials: true}, data:"action=delete_submit_hover_bg_img_IE89",
			success:function(msg)
			{
				jQuery('#submit_hover_btn_img_div').html(msg); formChange1();
				
				jQuery('#submit_hover_btn_img_div').addClass("iframe_submit_hover_original_btn");
				jQuery('#submit_hover_btn_img_div').css("background","#1BBAE1");
				//jQuery('#submit_hover_btn_img_div').css("float","left");
				jQuery('#submit_hover_btn_img_div').css("padding","7px 10px 0 10px");
				jQuery('#submit_hover_btn_img_div').css('border','1px solid #CCCCCC');
				//jQuery('#submit_hover_btn_img_div').css('box-shadow','0px 0px 2px rgba(0, 0, 0, 0.4)');
				jQuery('#submit_hover_btn_img_div').css('border-radius','3px');
				jQuery('#arfsbhis_iframe').contents().find('#iframe_form').trigger("reset");
				jQuery('#submit_hover_btn_img_div').append('<div id="arfsbhis_iframe_div"><iframe style="display:none;" id="arfsbhis_iframe" src="<?php echo ARFURL;?>/core/views/iframe.php"></iframe></div>');
			}
		});	
		<?php }else { ?>
		jQuery.ajax({type:"POST",url:ajaxurl,xhrFields:{withCredentials: true}, data:"action=delete_submit_hover_bg_img",
			success:function(msg){ jQuery('#submit_hover_btn_img_div').html(msg); formChange1(); }
		});	
		<?php } ?>
		
	
	} else {	
		return false;
	}

}

function delete_form_bg_img(){

	if( confirm('Are you sure you want do delete this image ?') ){
		<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ ?>
			jQuery.ajax({type:"POST",url:ajaxurl,xhrFields:{withCredentials: true}, data:"action=delete_form_bg_img_IE89",
				success:function(msg)
				{ 
					//alert(msg);
					jQuery('#form_bg_img_div').html(msg); formChange1(); 
					jQuery('#form_bg_img_div').addClass("iframe_original_btn");
					jQuery('#form_bg_img_div').css("background","#1BBAE1");
					//jQuery('#form_bg_img_div').css("float","left");
					jQuery('#form_bg_img_div').css("padding","7px 10px 0 10px");
					jQuery('#form_bg_img_div').css('border','1px solid #CCCCCC');
					//jQuery('#form_bg_img_div').css('box-shadow','0px 0px 2px rgba(0, 0, 0, 0.4)');
					jQuery('#form_bg_img_div').css('border-radius','3px');
					jQuery('#arfmfbi_iframe').contents().find('#iframe_form').trigger("reset");
					//alert('reset iframe');
					jQuery('#form_bg_img_div').append('<div id="arfmfbi_iframe_div"><iframe style="display:none;" id="arfmfbi_iframe" src="<?php echo ARFURL;?>/core/views/iframe.php"></iframe></div>');
				}
			});
		<?php }else { ?>
			jQuery.ajax({type:"POST",url:ajaxurl,xhrFields:{withCredentials: true}, data:"action=delete_form_bg_img",
				success:function(msg){ jQuery('#form_bg_img_div').html(msg); formChange1(); }
			});
		<?php } ?>
	
	} else {	
		return false;
	}

} 
</script>
	<?php
		if(is_rtl())
		{
			$arffieldlist_style = 'display:none;margin-right:0px;';
		}
		else
		{
			$arffieldlist_style = 'display:none;';
		}
	?>
    <div class="arffieldlist_style" id="arfmainfieldlist_style" style=" <?php echo $arffieldlist_style; ?>">
    
    	<div id="frm-styling-action" class="tabs-panel" style="display:none;visibility:hidden; width:350px; max-height:none; overflow:hidden;">
        
        
        <ul class="frm_styling_icons">
			<li>
            <div id="preview-form-styling-setting" <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ echo ''; } else { ?>style="height:1px;"<?php } ?> >	
			<div class="clearfix frm_settings_page">
                <fieldset class="clearfix">			    
            
            		<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
					<style type="text/css">#preview-form-styling-setting .widget-inside { display:none; } </style>
					<?php } ?>

                    <div id="tabformsettings" class="widget clearfix global-font current_widget">
            
            
                        <div id="first_tab" class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Form Settings', 'ARForms') ?></h4></div>
            
                        </div>
            
            
                        <div class="widget-inside" style="display: block;">
                            
                            <div class="field-group clearfix widget_bg_bottom">
            				<?php
								if(is_rtl())
								{
									$form_width_lbl_style = 'float:right;width:134px;text-align:right;';
								}
								else
								{
									$form_width_lbl_style = 'width:134px;';
								}
							?>
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $form_width_lbl_style; ?>"><?php _e('Form Width', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$floating_class_1 = 'arf_float_left';
										$frm_width_txtbx_css = 'float:right;width:87px;';
										$frm_width_drpdwn_css = 'float:right;padding-left:5px;margin:auto 5px auto -25px;';
										$frm_width_drpdwn_val = 'float:right;';
									}
									else
									{
										$floating_class_1 = 'arf_float_right';
										$frm_width_txtbx_css = 'float:left;width:87px;';
										$frm_width_drpdwn_css = 'float:left;padding-left:5px;';
										$frm_width_drpdwn_val = 'float:left;';
									}
							   ?>
                               
                                <div class=" <?php echo $floating_class_1; ?>" style=" <?php echo $frm_width_drpdwn_val;?>">
                                    <input type="text" name="arffw" style=" <?php echo $frm_width_txtbx_css; ?>" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainformwidth']) ?>"/> 
                                    
                                    <?php /*?><div class="sltstandard1" style="float:left;padding-left:5px;">
                                    	<select name="arffu" id="arfformunit" class="demo-default" onchange="change_style_unit(this.value);" style="width:53px;" data-width='53px'>
                                        	<option value="px" <?php selected($newarr['form_width_unit'], 'px') ?>><?php _e('px', 'ARForms') ?></option>
                                            <option value="%" <?php selected($newarr['form_width_unit'], '%') ?>><?php _e('%', 'ARForms') ?></option>
										</select> 
                                    </div><?php */?>
                                    
                                    
                                    
                                    <div class="sltstandard1" style="float:left;padding-left:5px;">
                                        <input id="arffu" name="arffu" value="<?php echo $newarr['form_width_unit'];?>" type="hidden" onchange="change_style_unit(this.value);">
                                        <dl class="arf_selectbox" data-name="arffu" data-id="arffu" style="width:53px;">
                                          <dt><span><?php echo $newarr['form_width_unit'];?></span>
                                            <input value="<?php echo $newarr['form_width_unit'];?>" style="display:none;width:41px;" class="arf_autocomplete" type="text">
                                            <i class="fa fa-caret-down fa-lg"></i></dt>
                                          <dd>
                                            <ul style="display: none;" data-id="arffu">
                                              <li class="arf_selectbox_option" data-value="<?php _e('px', 'ARForms') ?>" data-label="<?php _e('px', 'ARForms') ?>"><?php _e('px', 'ARForms') ?></li>
                                              <li class="arf_selectbox_option" data-value="<?php _e('%', 'ARForms') ?>" data-label="<?php _e('%', 'ARForms') ?>"><?php _e('%', 'ARForms') ?></li>
                                            </ul>
                                          </dd>
                                        </dl>
                                    </div>
                                	
                                
                                </div>    
            
                            </div>
                          
                            
                            
                          
                          <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$form_align_lbl_style = 'float:right;width:64px;';
									}
									else
									{
										$form_align_lbl_style = 'width:64px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $form_align_lbl_style; ?>"><?php _e('Align', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$frm_align_opt_css = 'float:right;margin-left:-5px;;';
									}
									else
									{
										$frm_align_opt_css = 'float:left;';
									}
                               ?>
            					
                                <div class="sltstandard1"  style=" <?php echo $frm_align_opt_css; ?>">
                                    <div class="toggle-btn-grp joint-toggle">
                                            <label onclick="" class="toggle-btn left <?php if($newarr['form_align']=="left"){ echo "success"; }?>"><input type="radio" name="arffa" class="visuallyhidden" value="left" <?php checked($newarr['form_align'], 'left'); ?> /><?php _e('Left', 'ARForms') ?></label><label onclick="" class="toggle-btn center <?php if($newarr['form_align']=="center"){ echo "success"; }?>"><input type="radio" name="arffa"  class="visuallyhidden" value="center" <?php checked($newarr['form_align'], 'center'); ?> /><?php _e('Center', 'ARForms') ?></label><label onclick="" class="toggle-btn right <?php if($newarr['form_align']=="right"){ echo "success"; }?>"><input type="radio" name="arffa" class="visuallyhidden" value="right" <?php checked($newarr['form_align'], 'right'); ?> /><?php _e('Right', 'ARForms') ?></label>
                                    </div>
                                
                                </div>
                                
                            </div>
            
                            
                            
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$frm_bg_lbl_title = 'float:right;width:100%;text-align:right;';
										$frm_bgcol_lbl_title = 'float:right;width:125px;';
										$frm_bgcol_colorpicker = 'float:right;';
									}
									else
									{
										$frm_bg_lbl_title = 'width:100%;';
										$frm_bgcol_lbl_title = 'width:125px;';
										$frm_bgcol_colorpicker = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_bg_lbl_title; ?>"><?php _e('Form Background', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            					
                                <label class="lblsubheading sublblheading" style=" <?php echo $frm_bgcol_lbl_title; ?>"><?php _e('Background Color', 'ARForms') ?></label>
                            
                                <div class="arf_float_right" style=" <?php echo $frm_bgcol_colorpicker; ?>">
                                	
                                    
                                	<div class="arf_coloroption_sub">
                                    	<div class="arf_coloroption arfhex" data-fid="arfformbgcolorsetting" style="background:<?php echo esc_attr($newarr['arfmainformbgcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                        	<div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    
                                	<input type="hidden" name="arffbcs" id="arfformbgcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfmainformbgcolorsetting']) ?>" style="width:100px;" />
                                </div>
                                
                            </div>
                            
                            <div class="field-group clearfix subfield widget_bg_bottom" style="margin-top:11px; padding-bottom:10px;">
                            	<?php
									if(is_rtl())
									{
										$bg_img_lbl = 'float:right;padding-left:0;width:120px;';
										$ajax_loader_style = 'float:left;display:none;margin:5px 0 0 -99px';
									}
									else
									{
										$bg_img_lbl = 'width:120px;';
										$ajax_loader_style = 'float:left;display:none;margin:5px 0 0;';
									}
								?>                        
                                <label class="lblsubheading sublblheading" style=" <?php echo $bg_img_lbl; ?>"><?php _e('Background Image', 'ARForms') ?></label>
                            	
                                <div id="form_bg_img_div" <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9'){ ?> class="iframe_original_btn" data-id="arfmfbi" style="margin-left:5px; position: relative; overflow: hidden; float:left; cursor:pointer; max-width:140px; height:22px; background: #1BBAE1; font-weight:bold; <?php if($newarr['arfmainform_bg_img'] == '') { ?> background:#1BBAE1;padding: 7px 10px 0 10px;font-size:13px;border-radius:3px;color:#FFFFFF;border:1px solid #CCCCCC; <?php } ?>" <?php }else { ?> style="margin-left:0px;" <?php } ?>  >
                                	<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' && $newarr['arfmainform_bg_img'] == ''){ ?><span style="display:inline-block;color:#FFFFFF;text-align:center;"><?php _e('Upload', 'ARForms');?></span><?php } ?>
                                    <?php
									if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ 
										if( $newarr['arfmainform_bg_img'] != '' ) { ?>
                                        	<img src="<?php echo $newarr['arfmainform_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_form_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        	<input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="<?php echo esc_attr($newarr['arfmainform_bg_img']) ?>" id="arfmainform_bg_img" />
                                        <?php } else {?>
                                    
                                    <input type="text" class="original" name="form_bg_img" id="field_arfmfbi" form-id="" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; width: 160px; height: 59px; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />

									<input type="hidden" id="type_arfmfbi" name="type_arfmfbi" value="1" >
									<input type="hidden" value="jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico" id="file_types_arfmfbi" name="field_types_arfmfbi" />
                                    <input type="hidden" name="imagename_form" id="imagename_form" value="" />
                                    <input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="" id="arfmainform_bg_img" />
                                    
									<?php
										}
										echo '<div id="arfmfbi_iframe_div"><iframe style="display:none;" id="arfmfbi_iframe" src="'.ARFURL.'/core/views/iframe.php" ></iframe></div>';
                                    }else {
									?>
										<?php if( $newarr['arfmainform_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['arfmainform_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_form_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="<?php echo esc_attr($newarr['arfmainform_bg_img']) ?>" id="arfmainform_bg_img" />
                                        <?php } else { ?>
                                        
                                        <div class="arfajaxfileupload" style="position: relative; overflow: hidden; float:left; cursor: pointer;">
                                        <div class="file-upload-img"></div>
                                        	<?php _e('Upload', 'ARForms');?>
                                        	<input type="file" name="form_bg_img" id="form_bg_img" data-val="form_bg" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        </div>
                                        
                                        
                                        <input type="hidden" name="imagename_form" id="imagename_form" value="" />
                                        <input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="" id="arfmainform_bg_img" />
                                        &nbsp;&nbsp;<span id="ajax_form_loader" style="display:none; float: left; margin: 5px 0 0;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/wpspin_light.gif"></span>
										<?php } ?>
                                    <?php } ?>
                                </div>
                                
                            </div>
                            
                            
                            
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$frm_brdr_title = 'width:100%;float:right;text-align:right;';
										$frm_brdr_btn = 'float:right;';
									}
									else
									{
										$frm_brdr_title = 'width:100%;';
										$frm_brdr_btn = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_brdr_title; ?>"><?php _e('Form Border', 'ARForms') ?></label> <br />
            
                            </div>
            
                            
                            <div class="field-group clearfix subfield" style="margin-top:3px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:122px;"><?php _e('Type', 'ARForms') ?></label>
                                <div style=" <?php echo $frm_brdr_btn; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" class="toggle-btn normal <?php if($newarr['form_border_shadow']=="shadow"){ echo "success"; }?>"><input type="radio" name="arffbs" class="visuallyhidden" id="arfmainformbordershadow1" value="shadow" <?php checked($newarr['form_border_shadow'], 'shadow'); ?> /><?php _e('Shadow', 'ARForms') ?></label><label onclick="" class="toggle-btn normal <?php if($newarr['form_border_shadow']=="flat"){ echo "success"; }?>"><input type="radio" name="arffbs" class="visuallyhidden" value="flat"  id="arfmainformbordershadow2" <?php checked($newarr['form_border_shadow'], 'flat'); ?> /><?php _e('Flat', 'ARForms') ?></label>
                                    </div>
                                </div>
            
                            </div>
                            
                            <div class="field-group field-group-border subfield clearfix" style="margin-top:25px; margin-bottom:5px;">
            
            					<?php
									if(is_rtl())
									{
										$frm_slider_lbl_start = 'float:left;margin-left:-145px;margin-top:5px;';
										$frm_slider_lbl_end = 'float:right;display:inline;margin-right:45px;margin-top:0px;';
									}
									else
									{
										$frm_slider_lbl_start = 'float:left;margin-left:40px;';
										$frm_slider_lbl_end = 'float:right;display:inline;';
									}
								?>
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Size', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" name="arfmfis" style="width:142px;" class="txtxbox_widget"  id="arfmainfieldset" value="<?php echo esc_attr($newarr['fieldset']) ?>" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                               	<input id="arfmainfieldset_exs" class="arf_slider" data-slider-id='arfmainfieldset_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['fieldset']) ?>" />
                                <br />
                                <div style="width:142px; display:inline;">
                                	<div style=" <?php echo $frm_slider_lbl_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div style=" <?php echo $frm_slider_lbl_end; ?> "><?php _e('50 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfmfis" style="width:100px;" class="txtxbox_widget"  id="arfmainfieldset" value="<?php echo esc_attr($newarr['fieldset']) ?>" size="4" />
                                <?php } ?>
            
                            </div>
            
            
                            <div class="field-group field-group-border subfield clearfix" style="margin-top:25px; margin-bottom:15px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Radius', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" name="arfmfsr" style="width:142px;" class="txtxbox_widget"  id="arfmainfieldsetradius" value="<?php echo esc_attr($newarr['arfmainfieldsetradius']) ?>" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                               <input id="arfmainfieldsetradius_exs" class="arf_slider" data-slider-id='arfmainfieldsetradius_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['arfmainfieldsetradius']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div style=" <?php echo $frm_slider_lbl_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div style=" <?php echo $frm_slider_lbl_end; ?>"><?php _e('100 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfmfsr" style="width:100px;" class="txtxbox_widget"  id="arfmainfieldsetradius" value="<?php echo esc_attr($newarr['arfmainfieldsetradius']) ?>" size="4" />
                                <?php } ?>
                            </div>
            				
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$frm_brd_col_lbl = 'float:right;width:100%;text-align:right;';
									}
									else
									{
										$frm_brd_col_lbl = 'width:100%;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_brd_col_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label> <br />
            
                            </div>
            				<?php
								if(is_rtl())
								{
									$frm_brd_line_main = 'float:right;margin-left:20px;width:43%;margin-top:10px;clear:none;';
									$frm_brd_line_lbl = 'text-align:left;width:126px;';
								}
								else
								{
									$frm_brd_line_main = 'float:left;width:100%;margin-top:10px;clear:none;';
									$frm_brd_line_lbl = 'width:126px;';
								}
							?>
                            <div class="field-group clearfix subfield" style=" <?php echo $frm_brd_line_main; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $frm_brd_line_lbl; ?>"><?php _e('Line', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$frm_brd_line_cls1 = 'arf_float_right';
									}
									else
									{
										$frm_brd_line_cls1 = 'arf_float_left';
										$frm_brd_line_css = 'float:left;';
									}
								?>
            					<div class=" <?php echo $frm_brd_line_cls1; ?>" style=" <?php echo $frm_brd_line_css; ?>">
                                
                                <div class="arf_coloroption_sub">
                                    <div class="arf_coloroption arfhex" data-fid="arfmainfieldsetcolor" style="background:<?php echo esc_attr($newarr['arfmainfieldsetcolor']) ?>;"></div>
                                    <div class="arf_coloroption_subarrow_bg">
                                        <div class="arf_coloroption_subarrow"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="arfmfsc" id="arfmainfieldsetcolor" class="hex txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainfieldsetcolor']) ?>" style="width:100px;" />
                                </div>
            
           
                            </div>
                         	<?php
								if(is_rtl())
								{
									$frm_shadow_main = 'float:right;margin-top:11px;clear:left;width:100%;';
									$frm_shadow_cls = 'arf_float_right';
									$frm_shadow_css = 'margin-right:60px;';
								}
								else
								{
									$frm_shadow_main = 'float:left;margin-top:11px;clear:right;width:100%;';
									$frm_shadow_cls = 'arf_float_left';
									$frm_shadow_css = 'float:left;';
								}
							?>
                            <div class="field-group clearfix subfield " style=" <?php echo $frm_shadow_main; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $frm_brd_line_lbl; ?>"><?php _e('Shadow', 'ARForms') ?></label>
            
            					<div class=" <?php echo $frm_shadow_cls; ?>" style=" <?php echo $frm_shadow_css; ?>">
                                
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfformbordershadowsetting" style="background:<?php echo esc_attr($newarr['arfmainformbordershadowcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                
                                <input type="hidden" name="arffboss" id="arfformbordershadowsetting" class="hex txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainformbordershadowcolorsetting']) ?>" style="width:100px;" />
            					</div>
                                
                            </div>
                            
                            <div class="clear widget_bg_bottom" style="clear:both;"></div>
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$frm_padding_title = 'float:right;width:45px;text-align:right;';
										$frm_padding_btn_main = 'float:right;margin-right:-5px;';
									}
									else
									{
										$frm_padding_title = 'width:45px;';
										$frm_padding_btn_main = 'float:right;margin-right:-29px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_padding_title; ?>"><?php _e('Padding', 'ARForms') ?></label>
            					
                                <div style=" <?php echo $frm_padding_btn_main; ?>">
            						<div style="float:left;"><input type="text" name="arfmainfieldsetpadding_1" id="arfmainfieldsetpadding_1" onchange="arf_change_field_padding('arfmainfieldsetpadding');" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_1']); ?>" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:2px;"><?php _e('Top', 'ARForms');?></span></div>&nbsp;
                                	<div style="float:left; margin-left:8px;"><input type="text" name="arfmainfieldsetpadding_2" id="arfmainfieldsetpadding_2" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_2']); ?>" onchange="arf_change_field_padding('arfmainfieldsetpadding');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Right', 'ARForms');?></span></div>&nbsp;
                                	<div style="float:left; margin-left:8px;"><input type="text" name="arfmainfieldsetpadding_3" id="arfmainfieldsetpadding_3" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_3']); ?>" onchange="arf_change_field_padding('arfmainfieldsetpadding');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px; margin-left:3px;" /><br /><span class="arf_px" style="padding-left:5px;"><?php _e('Bottom', 'ARForms');?></span></div>&nbsp;
                                	<div style="float:left; margin-left:8px;"><input type="text" name="arfmainfieldsetpadding_4" id="arfmainfieldsetpadding_4" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_4']); ?>" onchange="arf_change_field_padding('arfmainfieldsetpadding');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Left', 'ARForms');?></span></div>&nbsp;
                                &nbsp;<span class="arf_px" style="float:left; padding-top:5px; margin-left:6px;"><?php _e('px', 'ARForms') ?></span>
                                </div>
                                <?php 
								$arfmainfieldsetpadding_value = '';
								
								if( esc_attr($newarr['arfmainfieldsetpadding_1']) != '' ){
									$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_1'].'px ';
								}else{
									$arfmainfieldsetpadding_value .= '0px ';
								}
								if( esc_attr($newarr['arfmainfieldsetpadding_2']) != '' ){
									$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_2'].'px ';
								}else{
									$arfmainfieldsetpadding_value .= '0px ';
								}					
								if( esc_attr($newarr['arfmainfieldsetpadding_3']) != '' ){
									$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_3'].'px ';
								}else{
									$arfmainfieldsetpadding_value .= '0px ';
								}
								if( esc_attr($newarr['arfmainfieldsetpadding_4']) != '' ){
									$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_4'].'px';
								}else{
									$arfmainfieldsetpadding_value .= '0px';	
								}
								?>	
                                <input type="hidden" name="arfmfsp" style="width:160px;" id="arfmainfieldsetpadding" class="txtxbox_widget arf_float_right" value="<?php echo $arfmainfieldsetpadding_value; ?>" size="4" />
            
                            </div>
                            
                            
                            
                            
                            <div class="clear" style="margin-top:10px;">
                            	<div>
                                	<?php
										if(is_rtl())
										{
											$frm_title_desc_show = 'float:right;width:160px;';
											$frm_title_desc_check = 'float:left;margin-top:10px;';
										}
										else
										{
											$frm_title_desc_show = 'float:left;width:140px;';
											$frm_title_desc_check = '';
										}
									?>
                                    <div class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_title_desc_show; ?>"><?php _e('Form title & description', 'ARForms') ?></div>
                                    
                                    <div style=" <?php echo $frm_title_desc_check; ?>"><label><span>HIDE&nbsp;</span></label><input type="checkbox" class="js-switch" name="options[display_title_form]" id="display_title_form" <?php if($values_nw['display_title_form']=='1'){ echo 'checked="checked"'; }?> onchange="change_form_title();" value="<?php echo $values_nw['display_title_form']; ?>" /><label><span>&nbsp;SHOW</span></label>
                                    </div>
                                </div>                            
                            </div>
                                
                            <input type="hidden" id="temp_display_title_form" value="1" /> 
            				<div id="form_title_style_div" <?php if($values_nw['display_title_form']=='0'){ echo 'style="display:none;"'; }?>> 
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$frm_title_lbl = 'float:left;width:100%;text-align:right;';
										$frm_title_col_lbl = 'float:right;width:126px;text-align:right;';
										$frm_title_cls = 'arf_float_left';
										$frm_title_css = '';
									}
									else
									{
										$frm_title_lbl = 'width:100%;';
										$frm_title_col_lbl = 'width:126px;';
										$frm_title_cls = 'arf_float_left';
										$frm_title_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading" style=" <?php echo $frm_title_lbl; ?>"><?php _e('Form Title', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield" >
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $frm_title_col_lbl; ?>"><?php _e('Title Color', 'ARForms') ?></label>
            					
                                <div class=" <?php echo $frm_title_cls; ?>" style=" <?php echo $frm_title_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfformtitlecolor" style="background:<?php echo esc_attr($newarr['arfmainformtitlecolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="arfftc" style="width:100px;" id="arfformtitlecolor" class="hex txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainformtitlecolorsetting']) ?>" />
                                </div>
            
                            </div>
                            
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            					<?php
									if(is_rtl())
									{
										$frm_title_txt_align_btn = 'float:right;margin-right:75px;';
									}
									else
									{
										$frm_title_txt_align_btn = 'float:left;margin-left:75px;';
									}
								?>
                                <label class="lblsubheading sublblheading"><?php _e('Text Align', 'ARForms') ?></label>
            
                                <div class="sltstandard1"style=" <?php echo $frm_title_txt_align_btn; ?>">
                                    <div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" class="toggle-btn left <?php if($newarr['arfformtitlealign']=="left"){ echo "success"; }?>"><input type="radio" name="arffta" class="visuallyhidden" value="left" <?php checked($newarr['arfformtitlealign'], 'left') ?> /><?php _e('Left','ARForms');?></label><label onclick="" class="toggle-btn center <?php if($newarr['arfformtitlealign']=="center"){ echo "success"; }?>"><input type="radio" name="arffta"  class="visuallyhidden" value="center" <?php checked($newarr['arfformtitlealign'], 'center') ?> /><?php _e('Center','ARForms');?></label><label onclick="" class="toggle-btn right <?php if($newarr['arfformtitlealign']=="right"){ echo "success"; }?>"><input  class="visuallyhidden" type="radio" name="arffta" value="right" <?php checked($newarr['arfformtitlealign'], 'right') ?> /><?php _e('Right','ARForms');?></label>
                                    </div>
								</div>    	
                            </div>
<div class="field-group field-group-border clearfix" style="margin-top:0px;">
            
                                <label class="lblsubheading" style="width:100%"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            <?php
							 $newarr['check_weight_form_title'] = isset($newarr['check_weight_form_title']) ? $newarr['check_weight_form_title'] : 'normal'; 	
							 $label_font_weight = ""; if($newarr['check_weight_form_title']!="normal"){ $label_font_weight = ", ".$newarr['check_weight_form_title']; }
							 ?>
                             <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style="margin-left:23px;">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showtitlefontsettingpopup" onclick="arfshowformsettingpopup('titlefontsettingpopup')"><?php echo $newarr['arftitlefontfamily'].", ".$newarr['form_title_font_size']."px ".$label_font_weight;?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('titlefontsettingpopup')" /></div>
                                    <div id="titlefontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style="float:right">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('titlefontsettingpopup')" type="button" style="margin-top:-12px; margin-right:3px;">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            
            
                                                <div class="lblsubheading" style="width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <?php /*?><div class="sltstandard2" style="float:right; margin-left:70px;  margin-bottom:10px; position:absolute;">
                                                <?php $get_googlefonts_data = $arformcontroller->get_arf_google_fonts(); ?>
                                                <select name="arftff" id="arftitlefontsetting" style="width:200px;" data-width='200px' data-size='15' onchange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">	
                                                    <optgroup label="Default Fonts">
                                                        <option value="Arial" <?php selected($newarr['arftitlefontfamily'], 'Arial') ?>>Arial</option>
                                        
                                                        <option value="Helvetica" <?php selected($newarr['arftitlefontfamily'], 'Helvetica') ?>>Helvetica</option>
                                                        
                                                        <option value="sans-serif" <?php selected($newarr['arftitlefontfamily'], 'sans-serif') ?>>sans-serif</option>
                                                        
                                                        <option value="Lucida Grande" <?php selected($newarr['arftitlefontfamily'], 'Lucida Grande') ?>>Lucida Grande</option>
                                                        
                                                        <option value="Lucida Sans Unicode" <?php selected($newarr['arftitlefontfamily'], 'Lucida Sans Unicode') ?>>Lucida Sans Unicode</option>
                                                        
                                                        <option value="Tahoma" <?php selected($newarr['arftitlefontfamily'], 'Tahoma') ?>>Tahoma</option>
                                                        
                                                        <option value="Times New Roman" <?php selected($newarr['arftitlefontfamily'], 'Times New Roman') ?>>Times New Roman</option>
                                                        
                                                        <option value="Courier New" <?php selected($newarr['arftitlefontfamily'], 'Courier New') ?>>Courier New</option>
                                                        
                                                        <option value="Verdana" <?php selected($newarr['arftitlefontfamily'], 'Verdana') ?>>Verdana</option>
                                                        
                                                        <option value="Geneva" <?php selected($newarr['arftitlefontfamily'], 'Geneva') ?>>Geneva</option>
                                                        
                                                        <option value="Courier" <?php selected($newarr['arftitlefontfamily'], 'Courier') ?>>Courier</option>
                                                                
                                                        <option value="Monospace" <?php selected($newarr['arftitlefontfamily'], 'Monospace') ?>>Monospace</option>
                                                                
                                                        <option value="Times" <?php selected($newarr['arftitlefontfamily'], 'Times') ?>>Times</option>

                
                                                    </optgroup>
                                                    <optgroup label="Google Fonts">
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<option value='".$goglefontsfamily."' ".selected($newarr['arftitlefontfamily'], $goglefontsfamily)." >".$goglefontsfamily."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </optgroup>
                                                </select>
                                                
                                                </div><?php */?>
                                                
                                                
                                                
                                                <div class="sltstandard2" style="float:right; margin-left:70px;  margin-bottom:10px; position:absolute;">
												  <?php $get_googlefonts_data = $arformcontroller->get_arf_google_fonts(); ?>
                                                  <input id="arftitlefontsetting" name="arftff" value="<?php echo $newarr['arftitlefontfamily'];?>" type="hidden" onChange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arftff" data-id="arftitlefontsetting" style="width:180px;">
                                                    <dt><span><?php echo $newarr['arftitlefontfamily'];?></span>
                                                      <input value="<?php echo $newarr['arftitlefontfamily'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arftitlefontsetting">
                                                        <ol class="arp_selectbox_group_label">Default Fonts</ol>
                                                        <li class="arf_selectbox_option" data-value="Arial" data-label="Arial">Arial</li>
                                                        <li class="arf_selectbox_option" data-value="Helvetica" data-label="Helvetica">Helvetica</li>
                                                        <li class="arf_selectbox_option" data-value="sans-serif" data-label="sans-serif">sans-serif</li>
                                                        <li class="arf_selectbox_option" data-value="Lucida Grande" data-label="Lucida Grande">Lucida Grande</li>
                                                        <li class="arf_selectbox_option" data-value="Lucida Sans Unicode" data-label="Lucida Sans Unicode">Lucida Sans Unicode</li>
                                                        <li class="arf_selectbox_option" data-value="Tahoma" data-label="Tahoma">Tahoma</li>
                                                        <li class="arf_selectbox_option" data-value="Times New Roman" data-label="Times New Roman">Times New Roman</li>
                                                        <li class="arf_selectbox_option" data-value="Courier New" data-label="Courier New">Courier New</li>
                                                        <li class="arf_selectbox_option" data-value="Verdana" data-label="Verdana">Verdana</li>
                                                        <li class="arf_selectbox_option" data-value="Geneva" data-label="Geneva">Geneva</li>
                                                        <li class="arf_selectbox_option" data-value="Courier" data-label="Courier">Courier</li>
                                                        <li class="arf_selectbox_option" data-value="Monospace" data-label="Monospace">Monospace</li>
                                                        <li class="arf_selectbox_option" data-value="Times" data-label="Times">Times</li>
                                                        <ol class="arp_selectbox_group_label">Google Fonts</ol>
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                            </div>
                                        	<div class="field-group clearfix subfield" style="margin-top:10px;">
            									<div class="lblsubheading" style="width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;"><?php _e('Style', 'ARForms') ?></div>
                            
                                                <?php /*?><div class="sltstandard1" style="float:right; margin-left:70px; margin-bottom:10px; position:absolute;">
                                                
                                                <select name="arfftws" id="arfformtitleweightsetting" style="width:100px;" data-width='100px' onchange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">
                            
                            
                                                    <option value="normal" <?php selected($newarr['check_weight_form_title'], 'normal') ?>><?php _e('normal', 'ARForms');?></option>
                            
                            
                                                    <option value="bold" <?php selected($newarr['check_weight_form_title'], 'bold') ?>><?php _e('bold', 'ARForms');?></option>
                                                    
                                                    <option value="italic" <?php selected($newarr['check_weight_form_title'], 'italic') ?>><?php _e('italic', 'ARForms');?></option>
                            
                                                </select>
                                                
                                                </div><?php */?>
                                                
                                                <div class="sltstandard1" style="float:right; margin-left:70px; margin-bottom:10px; position:absolute;">
                                                  <input id="arfformtitleweightsetting" name="arfftws" value="<?php echo $newarr['check_weight_form_title'];?>" type="hidden" onchange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfftws" data-id="arfformtitleweightsetting" style="width:80px;">
                                                    <dt><span><?php echo __($newarr['check_weight_form_title'], 'ARForms');?></span>
                                                      <input value="<?php echo __($newarr['check_weight_form_title'], 'ARForms');?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfformtitleweightsetting">
                                                        <li class="arf_selectbox_option" data-value="normal" data-label="<?php _e('normal', 'ARForms');?>"><?php _e('normal', 'ARForms');?></li>
                                                        <li class="arf_selectbox_option" data-value="bold" data-label="<?php _e('bold', 'ARForms') ?>"><?php _e('bold', 'ARForms') ?></li>
                                                        <li class="arf_selectbox_option" data-value="italic" data-label="<?php _e('italic', 'ARForms') ?>"><?php _e('italic', 'ARForms') ?></li>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                                
                                                          
                            
                                            </div>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            									<div class="lblsubheading" style="width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;"><?php _e('Size', 'ARForms') ?></div>
                                                <div style="margin-left:70px; margin-bottom:10px;">
                                                    <?php /*?><div class="sltstandard1" style="float:left; position:absolute;">
                                                    
                                                    <select name="arfftfss" id="arfformtitlefontsizesetting" style="width:100px;" data-width='100px' data-size='15' onchange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">
                                                        <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['form_title_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                        <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['form_title_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                        <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['form_title_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    
                                                    </div><?php */?>
                                                    
                                                    <?php /*?><div class="sltstandard1" style="float:left; position:absolute;">
                                                      <input id="arfformtitlefontsizesetting" name="arfftfss" value="<?php echo $newarr['form_title_font_size'];?>" type="hidden" onchange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">
                                                      <dl class="arf_selectbox" data-name="arfftfss" data-id="arfformtitlefontsizesetting" style="width:80px;">
                                                        <dt><span><?php echo $newarr['form_title_font_size'];?></span>
                                                          <input value="<?php echo $newarr['form_title_font_size'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                          <i class="fa fa-caret-down fa-lg"></i></dt>
                                                        <dd>
                                                          <ul style="display: none;" data-id="arfformtitlefontsizesetting">
                                                            <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            
                                                          </ul>
                                                        </dd>
                                                      </dl>
                                                    </div><?php */?>
                                                    
                                                    
                                                    <div class="sltstandard1" style="float:left; position:absolute;">
                                                      <input id="arfformtitlefontsizesetting" name="arfftfss" value="<?php echo $newarr['form_title_font_size'];?>" type="hidden" onchange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">
                                                      <dl class="arf_selectbox" data-name="arfftfss" data-id="arfformtitlefontsizesetting" style="width:80px;">
                                                        <dt><span><?php echo $newarr['form_title_font_size'];?></span>
                                                          <input value="<?php echo $newarr['form_title_font_size'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                          <i class="fa fa-caret-down fa-lg"></i></dt>
                                                        <dd>
                                                          <ul style="display: none;" data-id="arfformtitlefontsizesetting">
                                                            <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                          </ul>
                                                        </dd>
                                                      </dl>
                                                    </div>
                                                    <div class="arf_px" style="float:right; margin-right: 90px; padding-top:5px;"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$frm_title_margin_btn = 'float:left;margin-left:-20px;';
										$frm_title_margin_top = 'float:right;';
										$frm_title_margin_btm = $frm_title_margin_lft = $frm_title_margin_rgt = 'float:right;margin-right:8px;';
										$frm_title_margin_px = 'float:right;margin-right:6px;padding-top:5px;';
									}
									else
									{
										$frm_title_margin_btn = 'float:right;margin-right:-28px;';
										$frm_title_margin_top = 'float:left;';
										$frm_title_margin_btm = $frm_title_margin_lft = $frm_title_margin_rgt = 'float:left;margin-left:8px;';
										$frm_title_margin_px = 'float:left;margin-left:6px;padding-top:5px;';
									}
								?>
                                <label class="lblsubheading sublblheading" style="width:45px;"><?php _e('Margin', 'ARForms') ?></label>
            
            					<div style=" <?php echo $frm_title_margin_btn; ?>">
            						<div style=" <?php echo $frm_title_margin_top; ?>"><input type="text" name="arfformtitlepaddingsetting_1" id="arfformtitlepaddingsetting_1" onchange="arf_change_field_padding('arfformtitlepaddingsetting');" value="<?php echo esc_attr($newarr['arfmainformtitlepaddingsetting_1']); ?>" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3	px;"><?php _e('Top', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $frm_title_margin_rgt; ?>"><input type="text" name="arfformtitlepaddingsetting_2" id="arfformtitlepaddingsetting_2" value="<?php echo esc_attr($newarr['arfmainformtitlepaddingsetting_2']); ?>" onchange="arf_change_field_padding('arfformtitlepaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:1px;"><?php _e('Right', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $frm_title_margin_btm; ?>"><input type="text" name="arfformtitlepaddingsetting_3" id="arfformtitlepaddingsetting_3" value="<?php echo esc_attr($newarr['arfmainformtitlepaddingsetting_3']); ?>" onchange="arf_change_field_padding('arfformtitlepaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px; margin-left:3px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Bottom', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $frm_title_margin_lft; ?>"><input type="text" name="arfformtitlepaddingsetting_4" id="arfformtitlepaddingsetting_4" value="<?php echo esc_attr($newarr['arfmainformtitlepaddingsetting_4']); ?>" onchange="arf_change_field_padding('arfformtitlepaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Left', 'ARForms');?></span></div>&nbsp;
                                &nbsp;<span class="arf_px" style=" <?php echo $frm_title_margin_px; ?>"><?php _e('px', 'ARForms') ?></span>
                                </div>
                                <?php 
								$arfformtitlepaddingsetting_value = '';
								
								if( esc_attr($newarr['arfmainformtitlepaddingsetting_1']) != '' ){
									$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_1'].'px ';
								}else{
									$arfformtitlepaddingsetting_value .= '0px ';
								}
								if( esc_attr($newarr['arfmainformtitlepaddingsetting_2']) != '' ){
									$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_2'].'px ';
								}else{
									$arfformtitlepaddingsetting_value .= '0px ';
								}					
								if( esc_attr($newarr['arfmainformtitlepaddingsetting_3']) != '' ){
									$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_3'].'px ';
								}else{
									$arfformtitlepaddingsetting_value .= '0px ';
								}
								if( esc_attr($newarr['arfmainformtitlepaddingsetting_4']) != '' ){
									$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_4'].'px';
								}else{
									$arfformtitlepaddingsetting_value .= '0px';
								}
								?>	
                                <input type="hidden" name="arfftps" style="width:100px;" id="arfformtitlepaddingsetting" class="txtxbox_widget" value="<?php echo $arfformtitlepaddingsetting_value; ?>" />
                            </div>
                            </div>
                            <div class="clear widget_bg_bottom"></div>
            				
                            <?php 
							$is_pagebreak_form = true;
							if (isset($values['fields']) && !empty($values['fields'])){
								foreach($values['fields'] as $field){
									if( $field['type'] == 'break' )
									{
										if( $field['page_break_type'] == 'survey' ){
											$is_pagebreak_form = false;				
										}						
										break;	 
									}
								}
							}							
							?>
                            
                            <!-- arf_pagebreak_style start -->
                            <div id="arf_pagebreak_style" style=" <?php if( ! $is_pagebreak_form ){ echo 'display:none;';}?>">
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:10px;">
                                <?php
								  	if(is_rtl())
									{
										$frm_pg_brk_tab_bg_color = 'float:right;margin-right:0px;width:100%;';
										$frm_pg_brk_active_main = 'float:right;width:100%;margin-left:0px;clear:none;';
										$frm_pg_brk_active_lbl = 'width:126px;padding-left:0px;margin-right:49px;';
										$frm_pg_brk_cls = 'arf_float_right';
										$frm_pg_brk_active_css = '';
										$frm_pg_brk_inactv_main = 'float:right;clear:left;width:100%;';
										$frm_pg_brk_inactv_lbl = 'width:120px;margin-right:-35px;text-align:left;';
										$frm_pg_brk_inactv_css = 'float:right';
									}
									else
									{
										$frm_pg_brk_tab_bg_color = 'width:100%;';
										$frm_pg_brk_active_main = 'float:left;width:100%;clear:none;';
										$frm_pg_brk_active_lbl = 'width:126px;';
										$frm_pg_brk_cls = 'arf_float_left';
										$frm_pg_brk_active_css = 'float:left;';
										$frm_pg_brk_inactv_main = 'float:left;clear:right;width:100%;';
										$frm_pg_brk_inactv_lbl = 'width:126px;';
										$frm_pg_brk_inactv_css = 'float:left;';
									}
								  ?>     
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_pg_brk_tab_bg_color; ?>"><?php _e('Page Break Tab Background Color', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $frm_pg_brk_active_main; ?>">
                                
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_active_lbl; ?>"><?php _e('Active', 'ARForms') ?></label>
                                <div class="<?php echo $frm_pg_brk_cls; ?>" style=" <?php echo $frm_pg_brk_active_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bg_color_pg_break" style="background:<?php echo esc_attr($newarr['bg_color_pg_break']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                               		<input type="hidden" name="arffbcpb" id="frm_bg_color_pg_break" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['bg_color_pg_break']) ?>" style="width:100px;" />
                                </div>
                                
                            </div>
                             
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px; <?php echo $frm_pg_brk_inactv_main; ?>">
                                
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_inactv_lbl; ?>"><?php _e('Inactive', 'ARForms') ?></label>
                                <div class=" <?php echo $frm_pg_brk_cls ?>" style=" <?php echo $frm_pg_brk_inactv_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bg_inavtive_color_pg_break" style="background:<?php echo esc_attr($newarr['bg_inavtive_color_pg_break']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="arfbicpb" id="frm_bg_inavtive_color_pg_break" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['bg_inavtive_color_pg_break']) ?>" style="width:100px;" />
                                </div>
                            </div>
                            
                            <div style="clear:both; height:1px;">&nbsp;</div>
                            <?php
								if(is_rtl())
								{
									$frm_pg_brk_tab_txt_col_lbl = 'float:right;margin-right:0px;width:126px;';
									$frm_pg_brk_tab_txt_col_cls = 'arf_float_right';
									$frm_pg_brk_tab_txt_col_css = 'float:right;';
								}
								else
								{
									$frm_pg_brk_tab_txt_col_lbl = 'width:126px;';
									$frm_pg_brk_tab_txt_col_cls = 'arf_float_left';
									$frm_pg_brk_tab_txt_col_css = 'float:left;';
								}
							?>
                            <div class="field-group field-group-border clearfix subfield widget_bg_bottom" style="margin-top:10px;">
            				
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_tab_txt_col_lbl; ?>"><?php _e('Text Color', 'ARForms') ?></label>
            					<div class=" <?php echo $frm_pg_brk_tab_txt_col_cls; ?>" style=" <?php echo $frm_pg_brk_tab_txt_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_text_color_pg_break" style="background:<?php echo esc_attr($newarr['text_color_pg_break']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfftcpb" id="frm_text_color_pg_break" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['text_color_pg_break']) ?>" style="width:100px;" />
                                </div>
            				
                            </div>
							
                            </div>
                            <!-- arf_pagebreak_style end -->
                            
                            
                            <!-- arf_surveypage_style start -->
                            <div id="arf_surveypage_style" style=" <?php if( $is_pagebreak_form ){ echo 'display:none;';}?>">
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:10px;">
                                <?php
								  	if(is_rtl())
									{
										$frm_pg_brk_tab_bg_color = 'float:right;margin-right:0px;width:100%;';
										$frm_pg_brk_active_main = 'float:right;width:100%;margin-left:0px;clear:none;';
										$frm_pg_brk_active_lbl = 'width:126px;padding-left:0px;margin-right:49px;';
										$frm_pg_brk_cls = 'arf_float_right';
										$frm_pg_brk_active_css = '';
										$frm_pg_brk_inactv_main = 'float:right;clear:left;width:100%;';
										$frm_pg_brk_inactv_lbl = 'width:120px;margin-right:-35px;text-align:left;';
										$frm_pg_brk_inactv_css = 'float:right';
									}
									else
									{
										$frm_pg_brk_tab_bg_color = 'width:100%;';
										$frm_pg_brk_active_main = 'float:left;width:100%;clear:none;';
										$frm_pg_brk_active_lbl = 'width:126px;';
										$frm_pg_brk_cls = 'arf_float_left';
										$frm_pg_brk_active_css = 'float:left;';
										$frm_pg_brk_inactv_main = 'float:left;clear:right;width:100%;';
										$frm_pg_brk_inactv_lbl = 'width:126px;';
										$frm_pg_brk_inactv_css = 'float:left;';
									}
								  ?>     
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_pg_brk_tab_bg_color; ?>"><?php _e('Survey Bar Colors', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $frm_pg_brk_active_main; ?>">
                                
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_active_lbl; ?>"><?php _e('Bar Color', 'ARForms') ?></label>
                                <div class="<?php echo $frm_pg_brk_cls; ?>" style=" <?php echo $frm_pg_brk_active_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bar_color_survey" style="background:<?php echo esc_attr(isset($newarr['bar_color_survey'])?$newarr['bar_color_survey']:"") ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                               		<input type="hidden" name="arfbcs" id="frm_bar_color_survey" class="txtxbox_widget hex" value="<?php echo esc_attr(isset($newarr['bar_color_survey'])?$newarr['bar_color_survey']:"") ?>" style="width:100px;" />
                                </div>
                                
                            </div>
                             
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px; <?php echo $frm_pg_brk_inactv_main; ?>">
                                
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_inactv_lbl; ?>"><?php _e('Background', 'ARForms') ?></label>
                                <div class=" <?php echo $frm_pg_brk_cls ?>" style=" <?php echo $frm_pg_brk_inactv_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bg_color_survey" style="background:<?php echo esc_attr(isset($newarr['bg_color_survey'])?$newarr['bg_color_survey']:"") ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="arfbgcs" id="frm_bg_color_survey" class="txtxbox_widget hex" value="<?php echo esc_attr(isset($newarr['bg_color_survey'])?$newarr['bg_color_survey']:"") ?>" style="width:100px;" />
                                </div>
                            </div>
                            
                            <div style="clear:both; height:1px;">&nbsp;</div>
                            <?php
								if(is_rtl())
								{
									$frm_pg_brk_tab_txt_col_lbl = 'float:right;margin-right:0px;width:126px;';
									$frm_pg_brk_tab_txt_col_cls = 'arf_float_right';
									$frm_pg_brk_tab_txt_col_css = 'float:right;';
								}
								else
								{
									$frm_pg_brk_tab_txt_col_lbl = 'width:126px;';
									$frm_pg_brk_tab_txt_col_cls = 'arf_float_left';
									$frm_pg_brk_tab_txt_col_css = 'float:left;';
								}
							?>
                            <div class="field-group field-group-border clearfix subfield widget_bg_bottom" style="margin-top:10px;">
            				
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_tab_txt_col_lbl; ?>"><?php _e('Title Color', 'ARForms') ?></label>
            					<div class=" <?php echo $frm_pg_brk_tab_txt_col_cls; ?>" style=" <?php echo $frm_pg_brk_tab_txt_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_text_color_survey" style="background:<?php echo esc_attr(isset($newarr['text_color_survey'])?$newarr['text_color_survey']:"") ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfftcs" id="frm_text_color_survey" class="txtxbox_widget hex" value="<?php echo esc_attr(isset($newarr['text_color_survey'])?$newarr['text_color_survey']:"") ?>" style="width:100px;" />
                                </div>
            				
                            </div>
							
                            </div>
                            <!-- arf_surveypage_style end -->
                            
                            
                            <div class="field-group clearfix" style="margin-top:18px;">
            					<?php
									if(is_rtl())
									{
										$modal_win_opct_lbl = 'float:right;width:170px;margin-right:-20px;';
										$modal_win_opct_cls = 'arf_float_left';
										$modal_win_opct_slider_lbl_main = 'width:150px;display:inline;float:left;';
										$modal_win_opct_slider_lbl_start = 'float:left;';
										$modal_win_opct_slider_lbl_end = 'float:right;';
									}
									else
									{
										$modal_win_opct_lbl = 'width:170px;';
										$modal_win_opct_cls = 'arf_float_left';
										$modal_win_opct_slider_lbl_main = 'width:150px;display:inline;';
										$modal_win_opct_slider_lbl_start = 'float:left;margin-left:40px;';
										$modal_win_opct_slider_lbl_end = 'float:left;margin-left:130px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style="width: 102px; padding-right:0; margin-right:0;"><?php _e('Window Opacity', 'ARForms') ?> &nbsp;&nbsp;&nbsp;</label>
                            	 
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right" style="margin-right:5px;">
                                	 <input type="text" name="arfmainform_opacity" id="arfmainform_opacity" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainform_opacity']) ?>" style="width:142px;" />
            					</div>
								<?php } else { ?>
                                 
                                <div style="float:left;margin-top:7px;">
                                	<input id="arfmainform_opacity_exs" class="arf_slider" data-slider-id='arfmainform_opacity_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo ( esc_attr($newarr['arfmainform_opacity']) * 10 ) ?>" />
                                </div>
                                <br />
                                <div style=" <?php echo $modal_win_opct_slider_lbl_main; ?>">
                                	<div style=" <?php echo $modal_win_opct_slider_lbl_start; ?>"><?php _e('0', 'ARForms') ?></div>
                                    <div style=" <?php echo $modal_win_opct_slider_lbl_end; ?>"><?php _e('1', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfmainform_opacity" id="arfmainform_opacity" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainform_opacity']) ?>" style="width:100px;" />
                                <?php } ?>
                                
                            </div>
                            
                            <div style="height:10px;">&nbsp;</div>
                            	
                        	<div class="clear widget_bg_bottom"></div>
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$sc_pad_margin_btn = 'float:left;margin-left:-20px;';
										$sc_pad_margin_top = 'float:right;';
										$sc_pad_margin_btm = $sc_pad_margin_lft = $sc_pad_margin_rgt = 'float:right;margin-right:8px;';
										$sc_pad_margin_px = 'float:right;margin-right:6px;padding-top:5px;';
									}
									else
									{
										$sc_pad_margin_btn = 'float:right;margin-right:-28px;';
										$sc_pad_margin_top = 'float:left;';
										$sc_pad_margin_btm = $sc_pad_margin_lft = $sc_pad_margin_rgt = 'float:left;margin-left:8px;';
										$sc_pad_margin_px = 'float:left;margin-left:6px;padding-top:5px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style="width: 100px; padding-right:0; margin-right:0;"><?php _e('Section Padding', 'ARForms') ?></label>
            
            					<div style=" <?php echo $sc_pad_margin_btn; ?>">
            						<div style=" <?php echo $sc_pad_margin_top; ?>"><input type="text" name="arfsectionpaddingsetting_1" id="arfsectionpaddingsetting_1" onchange="arf_change_field_padding('arfsectionpaddingsetting');" value="<?php echo esc_attr($newarr['arfsectionpaddingsetting_1']); ?>" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Top', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $sc_pad_margin_rgt; ?>"><input type="text" name="arfsectionpaddingsetting_2" id="arfsectionpaddingsetting_2" value="<?php echo esc_attr($newarr['arfsectionpaddingsetting_2']); ?>" onchange="arf_change_field_padding('arfsectionpaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:1px;"><?php _e('Right', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $sc_pad_margin_btm; ?>"><input type="text" name="arfsectionpaddingsetting_3" id="arfsectionpaddingsetting_3" value="<?php echo esc_attr($newarr['arfsectionpaddingsetting_3']); ?>" onchange="arf_change_field_padding('arfsectionpaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px; margin-left:3px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Bottom', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $sc_pad_margin_lft; ?>"><input type="text" name="arfsectionpaddingsetting_4" id="arfsectionpaddingsetting_4" value="<?php echo esc_attr($newarr['arfsectionpaddingsetting_4']); ?>" onchange="arf_change_field_padding('arfsectionpaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Left', 'ARForms');?></span></div>&nbsp;
                                &nbsp;<span class="arf_px" style=" <?php echo $frm_title_margin_px; ?>"><?php _e('px', 'ARForms') ?></span>
                                </div>
                                <?php 
								$arfsectionpaddingsetting_value = '';
								
								if( esc_attr($newarr['arfsectionpaddingsetting_1']) != '' )
									$arfsectionpaddingsetting_value .= $newarr['arfsectionpaddingsetting_1'].'px ';
								else
									$arfsectionpaddingsetting_value .= '15px ';
								
								if( esc_attr($newarr['arfsectionpaddingsetting_2']) != '' )
									$arfsectionpaddingsetting_value .= $newarr['arfsectionpaddingsetting_2'].'px ';
								else
									$arfsectionpaddingsetting_value .= '15px ';
															
								if( esc_attr($newarr['arfsectionpaddingsetting_3']) != '' )
									$arfsectionpaddingsetting_value .= $newarr['arfsectionpaddingsetting_3'].'px ';
								else
									$arfsectionpaddingsetting_value .= '15px ';
								
								if( esc_attr($newarr['arfsectionpaddingsetting_4']) != '' )
									$arfsectionpaddingsetting_value .= $newarr['arfsectionpaddingsetting_4'].'px';
								else
									$arfsectionpaddingsetting_value .= '15px';
								?>	
                                <input type="hidden" name="arfscps" style="width:100px;" id="arfsectionpaddingsetting" class="txtxbox_widget" value="<?php echo $arfsectionpaddingsetting_value; ?>" />
                            </div>
                            <div style="height:10px;">&nbsp;</div>
                        </div>
            
            
                    </div>
                    <input type="hidden" name="arfformid" value="<?php echo $id;?>" />
                    <div id="tablabelsettings" class="widget clearfix global-font">
            
            
                        <div class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Label Settings', 'ARForms') ?></h4></div>
            
            
                        </div>
            
            
                        <div class="widget-inside">
            				
                            
                            <?php
								if(is_rtl())
								{
									$lbl_position_lbl = 'float:right;width:100%;text-align:right;';
									$lbl_position_opt = 'float:left;margin-right:72px;';
									$lbl_txt_align_lbl = 'float:right;width:130px;text-align:right;';
									$lbl_txt_align_opt = 'float:right;margin-right:0px;';
								}
								else
								{
									$lbl_position_lbl = 'width:100%;';
									$lbl_position_opt = 'float:left;margin-left:72px;';
									$lbl_txt_align_lbl = 'width:130px;';
									$lbl_txt_align_opt = 'float:left;margin-right:0px;';
								}
							?>
                            <div class="field-group clearfix clear widget_bg_bottom">

                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_position_lbl; ?>"><?php _e('Label Position', 'ARForms') ?></label>
            
                                <div class="sltstandard1" style=" <?php echo $lbl_position_opt; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                    	<?php foreach (array('top' => __('Top', 'ARForms'), 'left' => __('Left', 'ARForms'), 'right' => __('Right', 'ARForms')) as $pos => $pos_label){ ?>
                                        	<label onclick="" class="toggle-btn <?php echo $pos."pos";?> <?php if($newarr['position']==$pos){ echo "success"; }?>"><input type="radio" name="arfmps" class="visuallyhidden" onchange="frmSetPosClass('<?php echo $pos; ?>')" value="<?php echo $pos ?>" <?php checked($newarr['position'], $pos) ?> /><?php echo $pos_label ?></label>	
                                        <?php }?>
                                    </div>
                                </div>
            
                            </div>
                            
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_txt_align_lbl; ?>"><?php _e('Text Align', 'ARForms') ?></label>
            
                                
                                <div style=" <?php echo $lbl_txt_align_opt; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" class="toggle-btn left <?php if($newarr['align']=="left"){ echo "success"; }?>"><input type="radio" name="arffrma" id="frm_align" class="visuallyhidden" value="left" <?php checked($newarr['align'], 'left'); ?> /><?php _e('Left', 'ARForms') ?></label><label onclick="" class="toggle-btn right <?php if($newarr['align']=="right"){ echo "success"; }?>"><input type="radio" name="arffrma" id="frm_align_2"  class="visuallyhidden" value="right" <?php checked($newarr['align'], 'right'); ?> /><?php _e('Right', 'ARForms') ?></label>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px; padding-bottom:10px;">
            					<?php
									if(is_rtl())
									{
										$lbl_width_lbl = 'float:right;text-align:right;margin-right:8px;width:132px;';
										$lbl_width_cls = 'arf_float_right';
									}
									else
									{
										$lbl_width_lbl = 'width:132px;';
										$lbl_width_cls = 'arf_float_left';
									}
								?>
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_width_lbl; ?>"><?php _e('Label Width', 'ARForms') ?></label>
            
                                
                                <div class=" <?php echo $lbl_width_cls; ?>">
                                	<input type="text" name="arfmws" class="txtxbox_widget" style="width:142px;" id="arfmainformwidthsetting" value="<?php echo esc_attr($newarr["width"]) ?>"  size="5" />
                                    <input type="hidden" name="arfmwu" id="arfmainwidthunit" value="px" /> 
                                    &nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
                                
                            </div>
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$lbl_txt_col_lbl = 'float:right;width:133px;';
										$lbl_txt_col_cls = 'arf_float_right';
										$lbl_txt_col_css = 'float:right;';
									}
									else
									{
										$lbl_txt_col_lbl = 'width:133px;';
										$lbl_txt_col_cls = 'arf_float_left';
										$lbl_txt_col_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_txt_col_lbl; ?>"><?php _e('Text Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $lbl_txt_col_cls; ?>" style=" <?php echo $lbl_txt_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arflabelcolorsetting" style="background:<?php echo esc_attr($newarr['label_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arflcs" id="arflabelcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['label_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:0px;">
            					<?php
									if(is_rtl())
									{
										$font_setting_lbl = 'float:right;width:100%;text-align:right;';
										$font_select_opt = 'float:right;margin-right:24px;';
										$font_popup_clos = 'float:left;';
										$font_popup_clos_btn = 'float:left;margin-right:0px;margin-top:0px;';
									}
									else
									{
										$font_setting_lbl = 'width:100%;';
										$font_select_opt = 'margin-left:24px;';
										$font_popup_clos = 'float:right;';
										$font_popup_clos_btn = 'margin-top:-12px; margin-right:3px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $font_setting_lbl; ?>"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            <?php $label_font_weight = ""; if($newarr['weight']!="normal"){ $label_font_weight = ", ".$newarr['weight']; }?>
                            <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style=" <?php echo $font_select_opt; ?>">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showlabelfontsettingpopup" onclick="arfshowformsettingpopup('labelfontsettingpopup')"><?php echo $newarr['font'].", ".$newarr['font_size']."px ".$label_font_weight;?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('labelfontsettingpopup')" /></div>
                                    <div id="labelfontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style=" <?php echo $font_popup_clos; ?>">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('labelfontsettingpopup')" type="button" style=" <?php echo $font_popup_clos_btn; ?>">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            
            									<?php
													if(is_rtl())
													{
														$font_family_lbl = 'float:right;width:50px;padding-left:10px;padding-top:7px;height:20px;';
														$font_family_opt = 'float:left;margin-right:70px;margin-bottom:10px;position:absolute;';
													}
													else
													{
														$font_family_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
														$font_family_opt = 'float:right; margin-left:70px;  margin-bottom:10px; position:absolute;';
													}
												?>
                                                <div class="lblsubheading" style=" <?php echo $font_family_lbl; ?>"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <?php /*?><div class="sltstandard2" style=" <?php echo $font_family_opt; ?>">
                                                <?php $get_googlefonts_data = $arformcontroller->get_arf_google_fonts(); ?>
                                                <select name="arfmfs" id="arfmainfontsetting" style="width:200px;" data-width='200px' data-size='15' onchange="Changefontsettinghtml('labelfontsettingpopup','arfmainfontsetting','arfmainfontweightsetting','arffontsizesetting');">	
                                                    <optgroup label="Default Fonts">
                                                        <option value="Arial" <?php selected($newarr['font'], 'Arial') ?>>Arial</option>
                                        
                                                        <option value="Helvetica" <?php selected($newarr['font'], 'Helvetica') ?>>Helvetica</option>
                                                        
                                                        <option value="sans-serif" <?php selected($newarr['font'], 'sans-serif') ?>>sans-serif</option>
                                                        
                                                        <option value="Lucida Grande" <?php selected($newarr['font'], 'Lucida Grande') ?>>Lucida Grande</option>
                                                        
                                                        <option value="Lucida Sans Unicode" <?php selected($newarr['font'], 'Lucida Sans Unicode') ?>>Lucida Sans Unicode</option>
                                                        
                                                        <option value="Tahoma" <?php selected($newarr['font'], 'Tahoma') ?>>Tahoma</option>
                                                        
                                                        <option value="Times New Roman" <?php selected($newarr['font'], 'Times New Roman') ?>>Times New Roman</option>
                                                        
                                                        <option value="Courier New" <?php selected($newarr['font'], 'Courier New') ?>>Courier New</option>
                                                        
                                                        <option value="Verdana" <?php selected($newarr['font'], 'Verdana') ?>>Verdana</option>
                                                        
                                                        <option value="Geneva" <?php selected($newarr['font'], 'Geneva') ?>>Geneva</option>
                                                        
                                                        <option value="Courier" <?php selected($newarr['font'], 'Courier') ?>>Courier</option>
                                                                
                                                        <option value="Monospace" <?php selected($newarr['font'], 'Monospace') ?>>Monospace</option>
                                                                
                                                        <option value="Times" <?php selected($newarr['font'], 'Times') ?>>Times</option>

                
                                                    </optgroup>
                                                    <optgroup label="Google Fonts">
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<option value='".$goglefontsfamily."' ".selected($newarr['font'], $goglefontsfamily)." >".$goglefontsfamily."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </optgroup>
                                                </select>
                                                
                                                </div><?php */?>
                                                
                                                <div class="sltstandard2" style=" <?php echo $font_family_opt; ?>">
												  <?php $get_googlefonts_data = $arformcontroller->get_arf_google_fonts(); ?>
                                                  <input id="arfmainfontsetting" name="arfmfs" value="<?php echo $newarr['font'];?>" type="hidden" onchange="Changefontsettinghtml('labelfontsettingpopup','arfmainfontsetting','arfmainfontweightsetting','arffontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfmfs" data-id="arfmainfontsetting" style="width:180px;">
                                                    <dt><span><?php echo $newarr['font'];?></span>
                                                      <input value="<?php echo $newarr['font'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfmainfontsetting">
                                                        <ol class="arp_selectbox_group_label">
                                                          Default Fonts
                                                        </ol>
                                                        <li class="arf_selectbox_option" data-value="Arial" data-label="Arial">Arial</li>
                                                        <li class="arf_selectbox_option" data-value="Helvetica" data-label="Helvetica">Helvetica</li>
                                                        <li class="arf_selectbox_option" data-value="sans-serif" data-label="sans-serif">sans-serif</li>
                                                        <li class="arf_selectbox_option" data-value="Lucida Grande" data-label="Lucida Grande">Lucida Grande</li>
                                                        <li class="arf_selectbox_option" data-value="Lucida Sans Unicode" data-label="Lucida Sans Unicode">Lucida Sans Unicode</li>
                                                        <li class="arf_selectbox_option" data-value="Tahoma" data-label="Tahoma">Tahoma</li>
                                                        <li class="arf_selectbox_option" data-value="Times New Roman" data-label="Times New Roman">Times New Roman</li>
                                                        <li class="arf_selectbox_option" data-value="Courier New" data-label="Courier New">Courier New</li>
                                                        <li class="arf_selectbox_option" data-value="Verdana" data-label="Verdana">Verdana</li>
                                                        <li class="arf_selectbox_option" data-value="Geneva" data-label="Geneva">Geneva</li>
                                                        <li class="arf_selectbox_option" data-value="Courier" data-label="Courier">Courier</li>
                                                        <li class="arf_selectbox_option" data-value="Monospace" data-label="Monospace">Monospace</li>
                                                        <li class="arf_selectbox_option" data-value="Times" data-label="Times">Times</li>
                                                        <ol class="arp_selectbox_group_label">
                                                          Google Fonts
                                                        </ol>
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                            </div>
                                        	<div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
													if(is_rtl())
													{
														$font_style_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
														$font_style_opt = 'float:left; margin-right:70px; margin-bottom:10px; position:absolute;';
													}
													else
													{
														$font_style_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
														$font_style_opt = 'float:right; margin-left:70px; margin-bottom:10px; position:absolute;';
													}
												?>
            									<div class="lblsubheading" style=" <?php echo $font_style_lbl; ?>"><?php _e('Style', 'ARForms') ?></div>
                            
                                                <?php /*?><div class="sltstandard1" style=" <?php echo $font_style_opt; ?>">
                                                
                                                <select name="arfmfws" id="arfmainfontweightsetting" style="width:100px;" data-width='100px' onchange="Changefontsettinghtml('labelfontsettingpopup','arfmainfontsetting','arfmainfontweightsetting','arffontsizesetting');">
                            
                            
                                                    <option value="normal" <?php selected($newarr['weight'], 'normal') ?>><?php _e('normal', 'ARForms');?></option>
                            
                            
                                                    <option value="bold" <?php selected($newarr['weight'], 'bold') ?>><?php _e('bold', 'ARForms');?></option>
                                                    
                                                    <option value="italic" <?php selected($newarr['weight'], 'italic') ?>><?php _e('italic', 'ARForms');?></option>
                            
                                                </select>
                                                
                                                </div><?php */?>
                                                
                                                <div class="sltstandard1" style=" <?php echo $font_style_opt; ?>">
                                                  <input id="arfmainfontweightsetting" name="arfmfws" value="<?php echo $newarr['weight'];?>" type="hidden" onChange="Changefontsettinghtml('labelfontsettingpopup','arfmainfontsetting','arfmainfontweightsetting','arffontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfmfws" data-id="arfmainfontweightsetting" style="width:80px;">
                                                    <dt><span><?php echo __($newarr['weight'], 'ARForms');?></span>
                                                      <input value="<?php echo __($newarr['weight'], 'ARForms');?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfmainfontweightsetting">
                                                        <li class="arf_selectbox_option" data-value="normal" data-label="<?php _e('normal', 'ARForms');?>"><?php _e('normal', 'ARForms');?></li>
                                                        <li class="arf_selectbox_option" data-value="bold" data-label="<?php _e('bold', 'ARForms') ?>"><?php _e('bold', 'ARForms') ?></li>
                                                        <li class="arf_selectbox_option" data-value="italic" data-label="<?php _e('italic', 'ARForms') ?>"><?php _e('italic', 'ARForms') ?></li>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                                
                                                          
                            
                                            </div>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
												if(is_rtl())
												{
													$font_size_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
													$font_size_opt = 'float:right;position:absolute;';
													$font_px_lbl = 'float:left;padding-top:5px;margin-left:85px;';
													$font_size_opt_main = 'margin-right:70px;margin-bottom:10px;';
												}
												else
												{
													$font_size_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
													$font_size_opt = 'float:left;position:absolute';
													$font_px_lbl = 'float:right; margin-right: 90px; padding-top:5px;';
													$font_size_opt_main = 'margin-left:70px;margin-bottom:10px;';
												}
											?>
            									<div class="lblsubheading" style=" <?php echo $font_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></div>
                                                <div style=" <?php echo $font_size_opt_main; ?>">
                                                    <div class="sltstandard1" style=" <?php echo $font_size_opt; ?>">
                                                    
                                                    <?php /*?><select name="arffss" id="arffontsizesetting" style="width:100px;" data-width='100px' data-size='15' onchange="Changefontsettinghtml('labelfontsettingpopup','arfmainfontsetting','arfmainfontweightsetting','arffontsizesetting');">
                                                        <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                        <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                        <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                    </select><?php */?>
                                                    
                                                      <input id="arffontsizesetting" name="arffss" value="<?php echo $newarr['font_size'];?>" type="hidden" onChange="Changefontsettinghtml('labelfontsettingpopup','arfmainfontsetting','arfmainfontweightsetting','arffontsizesetting');">
                                                      <dl class="arf_selectbox" data-name="arffss" data-id="arffontsizesetting" style="width:80px;">
                                                        <dt><span><?php echo $newarr['font_size'];?></span>
                                                          <input value="<?php echo $newarr['font_size'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                          <i class="fa fa-caret-down fa-lg"></i></dt>
                                                        <dd>
                                                          <ul style="display: none;" data-id="arffontsizesetting">
                                                            <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                          </ul>
                                                        </dd>
                                                      </dl>
                                                    
                                                    </div>
                                                    <div class="arf_px" style=" <?php echo $font_px_lbl; ?>"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            
                            
                            <div class="widget_bg_bottom" style="padding-bottom:-4px;"></div>
							<div class="clear" style="margin-top:10px;padding-bottom:5px;">
            						<?php
										if(is_rtl())
										{
											$lbl_hide_lbl = 'width:136px; float:right;margin-top:-5px;margin-left:60px;';
										}
										else
										{
											$lbl_hide_lbl = 'width:136px; float:left;';
										}
									?>
                                    <div class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_hide_lbl; ?>"><?php _e('Hide Labels', 'ARForms') ?></div>
                                    
                                    <div><label><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="arfhl" id="arfhidelabels" value="<?php echo $newarr['hide_labels']!=""?$newarr['hide_labels']:0;?>" onchange="frmSetPosClassHide()"  <?php if($newarr['hide_labels']=='1'){ echo 'checked="checked"'; }?> /><label><span>&nbsp;YES</span></label>
                                     </div>   	
            
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:-4px;"></div>
                             
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$field_desc_set_lbl = 'width:100%;float:left;margin-right:0px;text-align:right;';
										$field_desc_size_lbl = 'width:120px;margin-right:0;text-align:right;';
										$field_desc_size_css = '';
										$field_desc_size_opt = 'float:right;';
									}
									else
									{
										$field_desc_set_lbl = 'width:100%;';
										$field_desc_size_lbl = 'width:120px;';
										$field_desc_size_css = 'margin-left:0px;';
										$field_desc_size_opt = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $field_desc_set_lbl; ?>"><?php _e('Field description settings', 'ARForms') ?></label> <br />
            
                            </div>
            
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $field_desc_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></label>
                                
                                <div class="arf_float_left" style=" <?php echo $field_desc_size_css; ?>">
                                                    
                                <div class="sltstandard1" style=" <?php echo $field_desc_size_opt; ?>">
                                
                                <?php /*?><select name="arfdfss" id="arfdescfontsizesetting" style="width:142px;" data-width='142px' data-size='10'>	
                                        <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                        <option value="<?php echo $i?>" <?php selected($newarr['arfdescfontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                        <?php } ?>
                                        <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                        <option value="<?php echo $i?>" <?php selected($newarr['arfdescfontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                        <?php } ?>
                                        <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                        <option value="<?php echo $i?>" <?php selected($newarr['arfdescfontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                        <?php } ?>
                                </select><?php */?>
                                
                                
                                <input id="arfdescfontsizesetting" name="arfdfss" value="<?php echo $newarr['arfdescfontsizesetting'];?>" type="hidden">
                                                      <dl class="arf_selectbox" data-name="arfdfss" data-id="arfdescfontsizesetting" style="width:80px;">
                                                        <dt><span><?php echo $newarr['arfdescfontsizesetting'];?></span>
                                                          <input value="<?php echo $newarr['arfdescfontsizesetting'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                          <i class="fa fa-caret-down fa-lg"></i></dt>
                                                        <dd>
                                                          <ul style="display: none;" data-id="arfdescfontsizesetting">
                                                            <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                          </ul>
                                                        </dd>
                                                      </dl>
                                
                                </div>
                                &nbsp;<span class="arf_px" style="float:left; margin-left:22px; padding-top:5px;"><?php _e('px', 'ARForms') ?></span>
                                </div>
                            </div>
                            <?php
								if(is_rtl())
								{
									$field_desc_align_lbl = 'width:auto;margin-right:0;text-align:right;';
									$field_desc_align_opt = 'float:right;margin-right:10px;';
								}
								else
								{
									$field_desc_align_lbl = 'width:36px;';
									$field_desc_align_opt = 'float:left;margin-left:10px;';
								}
							?>
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $field_desc_align_lbl; ?>"><?php _e('Align', 'ARForms') ?></label>
            
                                <div class="sltstandard1" style=" <?php echo $field_desc_align_opt; ?>">
                                    <div class="toggle-btn-grp joint-toggle">
                                            <label onclick="" class="toggle-btn left <?php if($newarr['arfdescalighsetting']=="left"){ echo "success"; }?>"><input type="radio" name="arfdas" class="visuallyhidden" value="left" <?php checked($newarr['arfdescalighsetting'], 'left'); ?> /><?php _e('Left', 'ARForms') ?></label><label onclick="" class="toggle-btn center <?php if($newarr['arfdescalighsetting']=="center"){ echo "success"; }?>"><input type="radio" name="arfdas"  class="visuallyhidden" value="center" <?php checked($newarr['arfdescalighsetting'], 'center'); ?> /><?php _e('Center', 'ARForms') ?></label><label onclick="" class="toggle-btn right <?php if($newarr['arfdescalighsetting']=="right"){ echo "success"; }?>"><input type="radio" name="arfdas" class="visuallyhidden" value="right" <?php checked($newarr['arfdescalighsetting'], 'right'); ?> /><?php _e('Right', 'ARForms') ?></label>
                                    </div>
                                
                                </div>    	
                                 
                                <div style="height:10px; clear:both;">&nbsp;</div>    
                            </div>
                            
                           	
                            
                            
                            
                            
            
                        </div>
            
            
                    </div>
            
                    <div id="tabinputfieldsettings" class="widget clearfix global-font">
            
            
                        <div class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Input Field Settings', 'ARForms') ?></h4></div>
            
            
                        </div>
            
            
                        <div class="widget-inside" style="visibility:visible;">
                            
                            
                            <input type="hidden" name="arfmf" value="<?php echo $id; ?>" id="arfmainformid" />
            				
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
                            
            					<?php
									if(is_rtl())
									{
										$field_width_lbl = 'float:right;width:135px;text-align:right;';
										$field_width_cls = 'arf_float_right';
										$field_width_opt = 'float:right;padding-right:7px;';
										$field_width_txt = 'float:right;width:85px;';
									}
									else
									{
										$field_width_lbl = 'width:135px;';
										$field_width_cls = 'arf_float_left';
										$field_width_opt = 'float:left;padding-left:7px;';
										$field_width_txt = 'float:left;width:85px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $field_width_lbl; ?>"><?php _e('Field Width', 'ARForms') ?></label>
            
            					<div class=" <?php echo $field_width_cls; ?>" >
                                
                                    <input type="text" name="arfmfiws" id="arfmainfieldwidthsetting" onchange="change_auto_width();" style=" <?php echo $field_width_txt; ?>" class="txtxbox_widget" value="<?php echo esc_attr($newarr['field_width']) ?>"  size="5" />
                                    
                                    
                                    <div class="sltstandard1" style=" <?php echo $field_width_opt; ?>">
                                    
                                    <?php /*?><select name="arffiu" onchange="change_date_format();" id="arffieldunit" style="width:53px;" data-width='53px'>
                                            <option value="px" <?php selected($newarr['field_width_unit'], 'px') ?>><?php _e('px', 'ARForms') ?></option>
                                            <option value="%" <?php selected($newarr['field_width_unit'], '%') ?>><?php _e('%', 'ARForms') ?></option>
                                    </select><?php */?>
                                    
                                    <input id="arffieldunit" name="arffiu" value="<?php echo $newarr['field_width_unit'];?>" type="hidden" onchange="change_date_format();" >
                                        <dl class="arf_selectbox" data-name="arffiu" data-id="arffieldunit" style="width:53px;">
                                          <dt><span><?php echo $newarr['field_width_unit'];?></span>
                                            <input value="<?php echo $newarr['field_width_unit'];?>" style="display:none;width:41px;" class="arf_autocomplete" type="text">
                                            <i class="fa fa-caret-down fa-lg"></i></dt>
                                          <dd>
                                            <ul style="display: none;" data-id="arffieldunit">
                                              <li class="arf_selectbox_option" data-value="<?php _e('px', 'ARForms') ?>" data-label="<?php _e('px', 'ARForms') ?>"><?php _e('px', 'ARForms') ?></li>
                                              <li class="arf_selectbox_option" data-value="<?php _e('%', 'ARForms') ?>" data-label="<?php _e('%', 'ARForms') ?>"><?php _e('%', 'ARForms') ?></li>
                                            </ul>
                                          </dd>
                                        </dl>
                                    
                                    </div>
            						
                                </div>    
            
                            </div>
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:7px;">
            
            					<?php
									if(is_rtl())
									{
										$text_dir_lbl = 'float:right;text-align:right;width:100%;';
										$text_dir_btn = 'float:left;padding-left:0px;';
										$text_dir_btn_sub = 'float:right;margin-right:87px;';
									}
									else
									{
										$text_dir_lbl = 'width:100%;';
										$text_dir_btn = 'padding-left:0px;';
										$text_dir_btn_sub = 'float:left;margin-left:87px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $text_dir_lbl; ?>"><?php _e('Text Direction', 'ARForms') ?></label>
            
                                <div style=" <?php echo $text_dir_btn; ?>">
                                	<div class="toggle-btn-grp joint-toggle" style=" <?php echo $text_dir_btn_sub;?>">
                                            <label onclick="" class="toggle-btn-large <?php if($newarr['text_direction']=="1"){ echo "success"; }?>"><input type="radio" name="arftds" class="visuallyhidden" id="txt_dir_1" value="1" <?php checked($newarr['text_direction'], 1); ?> /><?php _e('Left to Right', 'ARForms') ?></label><label onclick="" class="toggle-btn-large <?php if($newarr['text_direction']=="0"){ echo "success"; }?>"><input type="radio" name="arftds" class="visuallyhidden" value="0"  id="txt_dir_2" <?php checked($newarr['text_direction'], 0); ?> /><?php _e('Right to Left', 'ARForms') ?></label>
                                    </div>
                                </div>
            
                            </div>
                            
                             
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$font_setting_lbl = 'float:right;text-align:right;width:100%;';
										$font_select_box = 'float:right;margin-right:25px;';
										$font_select_close = 'float:left;';
										$font_select_close_btn = 'margin-top:-12px;margin-right:3px;';
									}
									else
									{
										$font_setting_lbl = 'width:100%';
										$font_select_box = 'float:left;margin-left:25px;';
										$font_select_close = 'float:right;';
										$font_select_close_btn = 'margin-top:-12px;margin-right:3px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $font_setting_lbl; ?>"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            <?php $input_font_weight_html = ""; if($newarr['check_weight']!="normal"){ $input_font_weight_html = ", ".$newarr['check_weight']; }?>
                            <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style=" <?php echo $font_select_box; ?>">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showinputfontsettingpopup" onclick="arfshowformsettingpopup('inputfontsettingpopup')"><?php echo $newarr['check_font'].", ".$newarr['field_font_size']."px ".$input_font_weight_html;?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('inputfontsettingpopup')" /></div>
                                    <div id="inputfontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style=" <?php echo $font_select_close; ?>">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('inputfontsettingpopup')" type="button" style=" <?php echo $font_select_close_btn; ?>">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            								<?php
												if(is_rtl())
												{
													$font_family_lbl = 'float:right;width:50px;padding-left:10px;padding-top:7px;height:20px;';
													$font_family_opt = 'float:right;margin-right:70px;margin-bottom:10px;position:absolute;';
												}
												else
												{
													$font_family_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
													$font_family_opt = 'float:left;margin-left:70px;margin-bottom:10px;position:absolute;';
												}
											?>
            
                                                <div class="lblsubheading" style=" <?php echo $font_family_lbl; ?>"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <?php /*?><div class="sltstandard2" style=" <?php echo $font_family_opt; ?>">
                                                <select name="arfcbfs" id="arfcheckboxfontsetting" style="width:200px;" data-width='200px' data-size="15" onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
                                                    <optgroup label="Default Fonts">
                                                        <option value="Arial" <?php selected($newarr['check_font'], 'Arial') ?>>Arial</option>
                            
                                                        <option value="Helvetica" <?php selected($newarr['check_font'], 'Helvetica') ?>>Helvetica</option>
                                                        
                                                        <option value="sans-serif" <?php selected($newarr['check_font'], 'sans-serif') ?>>sans-serif</option>
                                                        
                                                        <option value="Lucida Grande" <?php selected($newarr['check_font'], 'Lucida Grande') ?>>Lucida Grande</option>
                                                        
                                                        <option value="Lucida Sans Unicode" <?php selected($newarr['check_font'], 'Lucida Sans Unicode') ?>>Lucida Sans Unicode</option>
                                                        
                                                        <option value="Tahoma" <?php selected($newarr['check_font'], 'Tahoma') ?>>Tahoma</option>
                                                        
                                                        <option value="Times New Roman" <?php selected($newarr['check_font'], 'Times New Roman') ?>>Times New Roman</option>
                                                
                                                        <option value="Courier New" <?php selected($newarr['check_font'], 'Courier New') ?>>Courier New</option>
                                                
                                                        <option value="Verdana" <?php selected($newarr['check_font'], 'Verdana') ?>>Verdana</option>
                                                
                                                        <option value="Geneva" <?php selected($newarr['check_font'], 'Geneva') ?>>Geneva</option>
                                                        
                                                        <option value="Courier" <?php selected($newarr['check_font'], 'Courier') ?>>Courier</option>
                                                        
                                                        <option value="Monospace" <?php selected($newarr['check_font'], 'Monospace') ?>>Monospace</option>
                                                        
                                                        <option value="Times" <?php selected($newarr['check_font'], 'Times') ?>>Times</option>
                                                        
                                                    </optgroup>
                                                    <optgroup label="Google Fonts">
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<option value='".$goglefontsfamily."' ".selected($newarr['check_font'], $goglefontsfamily)." >".$goglefontsfamily."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </optgroup>
                                                    
                                                </select>
                                                
                                                </div><?php */?>
                                                
                                                
                                                <div class="sltstandard2" style=" <?php echo $font_family_opt; ?>">
                                                  <input id="arfcheckboxfontsetting" name="arfcbfs" value="<?php echo $newarr['check_font'];?>" type="hidden" onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfcbfs" data-id="arfcheckboxfontsetting" style="width:180px;">
                                                    <dt><span><?php echo $newarr['check_font'];?></span>
                                                      <input value="<?php echo $newarr['check_font'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfcheckboxfontsetting">
                                                        <ol class="arp_selectbox_group_label">Default Fonts</ol>
                                                        <li class="arf_selectbox_option" data-value="Arial" data-label="Arial">Arial</li>
                                                        <li class="arf_selectbox_option" data-value="Helvetica" data-label="Helvetica">Helvetica</li>
                                                        <li class="arf_selectbox_option" data-value="sans-serif" data-label="sans-serif">sans-serif</li>
                                                        <li class="arf_selectbox_option" data-value="Lucida Grande" data-label="Lucida Grande">Lucida Grande</li>
                                                        <li class="arf_selectbox_option" data-value="Lucida Sans Unicode" data-label="Lucida Sans Unicode">Lucida Sans Unicode</li>
                                                        <li class="arf_selectbox_option" data-value="Tahoma" data-label="Tahoma">Tahoma</li>
                                                        <li class="arf_selectbox_option" data-value="Times New Roman" data-label="Times New Roman">Times New Roman</li>
                                                        <li class="arf_selectbox_option" data-value="Courier New" data-label="Courier New">Courier New</li>
                                                        <li class="arf_selectbox_option" data-value="Verdana" data-label="Verdana">Verdana</li>
                                                        <li class="arf_selectbox_option" data-value="Geneva" data-label="Geneva">Geneva</li>
                                                        <li class="arf_selectbox_option" data-value="Courier" data-label="Courier">Courier</li>
                                                        <li class="arf_selectbox_option" data-value="Monospace" data-label="Monospace">Monospace</li>
                                                        <li class="arf_selectbox_option" data-value="Times" data-label="Times">Times</li>
                                                        <ol class="arp_selectbox_group_label">Google Fonts</ol>
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                            </div>
                                        	<div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
												if(is_rtl())
												{
													$font_style_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
													$font_style_opt = 'float:left; margin-right:70px; margin-bottom:10px; position:absolute;';
												}
												else
												{
													$font_style_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
													$font_style_opt = 'float:right; margin-left:70px; margin-bottom:10px; position:absolute;';
												}
											?>
            									<div class="lblsubheading" style=" <?php echo $font_style_lbl; ?>"><?php _e('Style', 'ARForms') ?></div>
                            
                                                <?php /*?><div class="sltstandard1" style=" <?php echo $font_style_opt; ?>">
                                                
                                                <select name="arfcbws" id="arfcheckboxweightsetting" style="width:100px;" data-width='100px' onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
            
            
                                                    <option value="normal" <?php selected($newarr['check_weight'], 'normal') ?>><?php _e('normal', 'ARForms') ?></option>
                        
                                                    <option value="bold" <?php selected($newarr['check_weight'], 'bold') ?>><?php _e('bold', 'ARForms') ?></option>
                                                    
                                                    <option value="italic" <?php selected($newarr['check_weight'], 'italic') ?>><?php _e('italic', 'ARForms') ?></option>
                        
                                                </select>
                                                
                                                </div><?php */?>
                                                
                                                
                                                <div class="sltstandard1" style=" <?php echo $font_style_opt; ?>">
                                                  <input id="arfcheckboxweightsetting" name="arfcbws" value="<?php echo $newarr['check_weight'];?>" type="hidden" onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfcbws" data-id="arfcheckboxweightsetting" style="width:80px;">
                                                    <dt><span><?php echo __($newarr['check_weight'], 'ARForms');?></span>
                                                      <input value="<?php echo __($newarr['check_weight'], 'ARForms');?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfcheckboxweightsetting">
                                                        <li class="arf_selectbox_option" data-value="normal" data-label="<?php _e('normal', 'ARForms');?>"><?php _e('normal', 'ARForms');?></li>
                                                        <li class="arf_selectbox_option" data-value="bold" data-label="<?php _e('bold', 'ARForms') ?>"><?php _e('bold', 'ARForms') ?></li>
                                                        <li class="arf_selectbox_option" data-value="italic" data-label="<?php _e('italic', 'ARForms') ?>"><?php _e('italic', 'ARForms') ?></li>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                                
                                                          
                            
                                            </div>
                                            <?php
												if(is_rtl())
												{
													$font_size_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
													$font_size_opt_wrap = 'margin-bottom:10px; float:right;';
													$font_size_opt = 'float:right;  margin-bottom:10px; position:absolute;';
													$font_size_px_lbl = 'float:right; margin-right:123px; padding-top:5px;';
												}
												else
												{
													$font_size_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
													$font_size_opt_wrap = ' margin-bottom:10px;';
													$font_size_opt = 'float:left;  margin-bottom:10px; position:absolute;';
													$font_size_px_lbl = 'float:left; margin-left:100px; margin-right: 0px; padding-top:5px;';
												}
											?>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            									<div class="lblsubheading" style=" <?php echo $font_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></div>
                                                <div style=" <?php echo $font_size_opt_wrap; ?>">
                                                    <div class="sltstandard1" style=" <?php echo $font_style_opt; ?>">
                                                    
                                                    <?php /*?><select name="arfffss" id="arffieldfontsizesetting" style="width:100px;" data-width='100px' data-size='15' onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">	
															<?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['field_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['field_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['field_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                    </select><?php */?>
                                                    
                                                    <input id="arffieldfontsizesetting" name="arfffss" value="<?php echo $newarr['field_font_size'];?>" type="hidden" onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
                                                    <dl class="arf_selectbox" data-name="arfffss" data-id="arffieldfontsizesetting" style="width:80px;">
                                                      <dt><span><?php echo $newarr['field_font_size'];?></span>
                                                        <input value="<?php echo $newarr['field_font_size'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                      <dd>
                                                        <ul style="display: none;" data-id="arffieldfontsizesetting">
                                                          <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>">
                                                            <?php _e($i, 'ARForms'); ?>
                                                          </li>
                                                          <?php } ?>
                                                          <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>">
                                                            <?php _e($i, 'ARForms'); ?>
                                                          </li>
                                                          <?php } ?>
                                                          <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>">
                                                            <?php _e($i, 'ARForms'); ?>
                                                          </li>
                                                          <?php } ?>
                                                        </ul>
                                                      </dd>
                                                    </dl>

                                                    
                                                    </div>
                                                    <div class="arf_px" style=" <?php echo $font_size_px_lbl; ?>"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$font_normal_state_lbl = 'float:right;width:100%;text-align:right;';
										$font_text_color_main  = 'float:right;margin-top:10px;margin-left:30px;clear:none;width:100%;';
										$font_text_color_cls = 'arf_float_right';
										$font_text_color_css  = 'margin-left:17px;';
										$font_text_color_css_lbl = 'padding-right:10px;padding-left:12px;float:right;text-align:right;';
									}
									else
									{
										$font_normal_state_lbl = 'float:left;width:100%;';
										$font_text_color_main  = 'float:left;margin-top:10px;clear:none;width:100%;';
										$font_text_color_cls = 'arf_float_left';
										$font_text_color_css  = 'margin-right:17px;float:left;';
										$font_text_color_css_lbl = 'padding-left:10px;padding-right:12px;float:left;text-align:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $font_normal_state_lbl; ?>"><?php _e('Normal State', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield " style=" <?php echo $font_text_color_main; ?>">
            
            				 	
                                <label class="lblsubheading" <?php echo 'style="'.$font_text_color_css_lbl.'"';?>><?php _e('Text color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $font_text_color_cls; ?>" style=" <?php echo $font_text_color_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arftextcolorsetting" style="background:<?php echo esc_attr($newarr['text_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arftcs" id="arftextcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['text_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            <?php
								if(is_rtl())
								{
									$bg_col_lbl_wrap = 'margin-top:11px;width:100%;float:left;clear:left;';
									$bg_col_lbl_cls = 'arf_float_left';
									$bg_col_lbl_css = 'float:right;';
									$bg_col_lbl_css_lbl = 'float:right;padding-right:10px;padding-left:12px;';
								}
								else
								{
									$bg_col_lbl_wrap = 'margin-top:11px;width:100%;float:left;clear:right;';
									$bg_col_lbl_cls = 'arf_float_right';
									$bg_col_lbl_css = 'float:left;';
									$bg_col_lbl_css_lbl = 'float:left;padding-left:10px;padding-right:12px;';
								}
							?>
                            <div class="field-group field-group-border clearfix" style=" <?php echo $bg_col_lbl_wrap; ?>">
            
            
                                <label class="background lblsubheading" style=" <?php echo $bg_col_lbl_css_lbl;?>"><?php _e('Background color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $bg_col_lbl_cls; ?>" style=" <?php echo $bg_col_lbl_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bg_color" style="background:<?php echo esc_attr($newarr['bg_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arffmbc" id="frm_bg_color" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['bg_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px; clear:both;"></div> 
       						
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$active_state_col = 'float:right;text-align:right;width:100%;';
										$active_state_bg_col_wrap = 'float:right;margin-top:10px;clear:none;width:100%;';
										$active_state_border_col_wrap = 'margin-top:11px; float:left; clear:left; width:100%;';
										$active_state_border_col_lbl  = 'float:right;padding-right:10px;padding-left:12px;';
									}
									else
									{
										$active_state_col = 'width:100%;';
										$active_state_bg_col_wrap = 'float:left;margin-top:10px;clear:none;width:100%;';
										$active_state_border_col_wrap = 'margin-top:11px; float:left; clear:right; width:100%;';
										$active_state_border_col_lbl  = 'float:left;padding-left:10px;padding-right:12px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $active_state_col; ?>"><?php _e('Active State', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $active_state_bg_col_wrap; ?>">
            
            
                                <label class="background lblsubheading sublblheading" style="width:126px;"><?php _e('Background Color', 'ARForms') ?></label>
            
            					<div class="arf_float_right" style="float:left;">
                                
                                	<div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfbgcoloractivesetting" style="background:<?php echo esc_attr($newarr['arfbgactivecolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfbcas" id="arfbgcoloractivesetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfbgactivecolorsetting']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
            
            				<?php
								if(is_rtl())
								{
									$active_state_brd_col_cls = 'arf_float_left';
									$active_state_brd_col_css = '';
									$active_state_brd_col_lbl = 'float:right;padding-right:10px;padding-left:12px;';
								}
								else
								{
									$active_state_brd_col_cls = 'arf_float_right';
									$active_state_brd_col_css = 'float:left;';
									$active_state_brd_col_lbl = 'float:left;padding-left:10px;padding-right:12px;';
								}
							?>
                            <div class="field-group clearfix subfield" style=" <?php echo $active_state_border_col_wrap; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $active_state_brd_col_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $active_state_brd_col_cls; ?>" style=" <?php echo $active_state_brd_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfborderactivecolorsetting" style="background:<?php echo esc_attr($newarr['arfborderactivecolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfbacs" id="arfborderactivecolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfborderactivecolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px; clear:both;"></div> 
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$error_state_lbl = 'float:right;text-align:right;width:130px;';
										$err_state_bg_col_wrap = 'margin-top:10px; float:right; clear:none; width:100%;';
										$err_state_brd_col_wrap = 'margin-top:11px; float:left; clear:left; width:100%;';
										$err_state_brd_cls = 'arf_float_left';
										$err_state_brd_css = '';
									}
									else
									{
										$error_state_lbl = 'width:130px;';	
										$err_state_bg_col_wrap = 'margin-top:10px; float:left; clear:none; width:100%;';
										$err_state_brd_col_wrap = 'margin-top:11px; float:left; clear:right; width:100%;';
										$err_state_brd_cls = 'arf_float_right';
										$err_state_brd_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $error_state_lbl; ?>"><?php _e('Error State', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $err_state_bg_col_wrap; ?>">
            
            
                                <label class="background lblsubheading sublblheading" style=" <?php echo $active_state_brd_col_lbl;?>"><?php _e('Background Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $err_state_brd_cls; ?>" style=" <?php echo $err_state_brd_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfbgerrorcolorsetting" style="background:<?php echo esc_attr($newarr['arferrorbgcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfbecs" id="arfbgerrorcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrorbgcolorsetting']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
            
                            <div class="field-group field-group-border clearfix subfield " style=" <?php echo $err_state_brd_col_wrap; ?>">
            
            					<?php
									if(is_rtl())
									{
										$err_state_brd_lbl = 'float:right;text-align:right;padding-right:10px;padding-left:12px;';
										$err_state_brd_cls = 'arf_float_left';
										$err_state_brd_css = 'margin-top:8px;';
									}
									else
									{
										$err_state_brd_lbl = 'float:left;padding-left:10px;padding-right:12px;';
										$err_state_brd_cls = 'arf_float_right';
										$err_state_brd_css = 'float:left';
									}
								?>
                                <label class="lblsubheading sublblheading" style=" <?php echo $err_state_brd_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $err_state_brd_cls; ?>" style=" <?php echo $err_state_brd_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfbordererrorcolorsetting" style="background:<?php echo esc_attr($newarr['arferrorbordercolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfboecs" id="arfbordererrorcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrorbordercolorsetting']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px; clear:both;"></div> 
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:10px;">
            					<?php
									if(is_rtl())
									{
										$brd_setting_lbl = 'float:right;text-align:right;width:100%;';
										$brd_size_lbl = 'text-align:right;width:85px;';
										$brd_size_cls = 'arf_float_left';
										$brd_slider_btn_start = 'float:left;margin-left:60px;';
										$brd_slider_btn_end  = 'display:inline;float:right;';
										
									}
									else
									{
										$brd_setting_lbl = 'width:100%;';
										$brd_size_lbl = 'width:85px;';
										$brd_size_cls = 'arf_float_right';
										$brd_slider_btn_start = 'float:left;margin-left:40px;';
										$brd_slider_btn_end  = 'display:inline;float:right;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $brd_setting_lbl; ?>"><?php _e('Border Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield"  style="margin-top:25px; margin-bottom:5px;">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $brd_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" name="arffbws" style="width:142px;" id="arffieldborderwidthsetting" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arffieldborderwidthsetting']) ?>" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arffieldborderwidthsetting_exs" class="arf_slider" data-slider-id='arffieldborderwidthsetting_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['arffieldborderwidthsetting']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style=" <?php echo $brd_slider_btn_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style=" <?php echo $brd_slider_btn_end; ?>"><?php _e('20 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arffbws" style="width:100px;" id="arffieldborderwidthsetting" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arffieldborderwidthsetting']) ?>" size="4" />
           						<?php } ?>
                                
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:25px; margin-bottom:5px;">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $brd_size_lbl; ?>"><?php _e('Radius', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	 <input type="text" name="arfmbs" style="width:142px;" class="txtxbox_widget"  id="arfmainbordersetting" value="<?php echo esc_attr($newarr['border_radius']) ?>" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arfmainbordersetting_exs" class="arf_slider" data-slider-id='arfmainbordersetting_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['border_radius']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style=" <?php echo $brd_slider_btn_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style=" <?php echo $brd_slider_btn_end; ?>"><?php _e('50 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfmbs" style="width:100px;" class="txtxbox_widget"  id="arfmainbordersetting" value="<?php echo esc_attr($newarr['border_radius']) ?>" size="4" />
            					
                                <?php } ?>
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px;">
            
            
                                <label class="lblsubheading sublblheading"><?php _e('Color', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$brd_col_css = 'float:right';
									}
									else
									{
										$brd_col_css = 'float:left;';
									}
								?>
            					<div class=" <?php echo $brd_size_cls; ?>" style=" <?php echo $brd_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_border_color" style="background:<?php echo esc_attr($newarr['border_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arffmboc" id="frm_border_color" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['border_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:40px;"><?php _e('Style', 'ARForms')?></label>
            					<?php
									if(is_rtl())
									{
										$brd_style_opt = 'float:right; margin-right:26px;';
									}
									else
									{
										$brd_style_opt = 'float:left; margin-left:26px;';
									}
								?>
                                <div class="sltstandard1" style=" <?php echo $brd_style_opt; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                            <label onclick="" style="width:55px !important;" class="toggle-btn solid <?php if($newarr['arffieldborderstylesetting']=="solid"){ echo "success"; }?>"><input type="radio" name="arffbss" class="visuallyhidden" value="solid" <?php checked($newarr['arffieldborderstylesetting'], 'solid'); ?> /><?php _e('Solid', 'ARForms') ?></label>
                                            
                                            <label onclick="" style="width:55px !important;" class="toggle-btn dotted <?php if($newarr['arffieldborderstylesetting']=="dotted"){ echo "success"; }?>"><input type="radio" name="arffbss"  class="visuallyhidden" value="dotted" <?php checked($newarr['arffieldborderstylesetting'], 'dotted'); ?> /><?php _e('Dotted', 'ARForms') ?></label>
                                            
                                            <label onclick="" style="width:55px !important;" class="toggle-btn dashed <?php if($newarr['arffieldborderstylesetting']=="dashed"){ echo "success"; }?>"><input type="radio" name="arffbss" class="visuallyhidden" value="dashed" <?php checked($newarr['arffieldborderstylesetting'], 'dashed'); ?> /><?php _e('Dashed', 'ARForms') ?></label>
                                    </div>
                                
                                </div>	
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                            <?php
									if(is_rtl())
									{
										$field_spacing_lbl = 'float:right;width:135px;';
									}
									else
									{
										$field_spacing_lbl = 'width:135px;';
									}
								?>
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px; padding-bottom:10px;">
            
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $field_spacing_lbl; ?>"><?php _e('Field Spacing', 'ARForms') ?></label>
            
            					<div class="arf_float_left">
                                	<input type="text" name="arffms" id="arffieldmarginsetting" style="width:142px;" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arffieldmarginssetting']) ?>"  size="5" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
                                
                            </div>
                            
                            <div class="field-group clearfix" style="margin-top:10px;">
            					<?php
									if(is_rtl())
									{
										$field_inner_spacing_lbl = 'float:right;width:430px;text-align:right;';
										$field_vrtcl_spc_start = 'float:left;margin-left:55px;';
										$field_vrtcl_spc_end  = 'display:inline;float:right;';
									}
									else
									{
										$field_inner_spacing_lbl = 'width:140px;';
										$field_vrtcl_spc_start = 'float:left;margin-left:40px;';
										$field_vrtcl_spc_end  = 'display:inline;float:right;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $field_inner_spacing_lbl; ?>"><?php _e('Field Inner Spacing', 'ARForms') ?></label>
            					
                            </div>
                            <?php 
							$arffieldinnermarginssetting_value = $newarr['arffieldinnermarginssetting_1']."px ".$newarr['arffieldinnermarginssetting_2']."px ".$newarr['arffieldinnermarginssetting_1']."px ".$newarr['arffieldinnermarginssetting_2']."px";							
							?>
                            <div class="field-group clearfix subfield" style="margin-top:25px; margin-bottom:5px;">
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Vertical', 'ARForms') ?></label>
                                           					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input id="arffieldinnermarginsetting_1" name="arffieldinnermarginsetting_1" class="txtxbox_widget" style="width:142px;" type="text" onchange="arf_change_field_spacing2();" value="<?php echo esc_attr($newarr['arffieldinnermarginssetting_1']) ?>" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arffieldinnermarginssetting_1_exs" class="arf_slider" data-slider-id='arffieldinnermarginssetting_1_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="25" data-slider-step="1" data-dvalue="<?php echo floatval($newarr['arffieldinnermarginssetting_1']); ?>" data-slider-value="<?php echo floatval($newarr['arffieldinnermarginssetting_1']) ?>" />
                                <input type="hidden" name="arffieldinnermarginsetting_1" id="arffieldinnermarginsetting_1" value="<?php echo floatval($newarr['arffieldinnermarginssetting_1']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style=" <?php echo $field_vrtcl_spc_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style=" <?php echo $field_vrtcl_spc_end; ?>"><?php _e('25 px', 'ARForms') ?></div>
                                </div>
            					
                                <?php } ?>
                                
                            </div>
                                
                            <div class="field-group clearfix widget_bg_bottom subfield" style="margin-top:25px; margin-bottom:5px;">
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Horizontal', 'ARForms') ?></label>
                                           					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input id="arffieldinnermarginsetting_2" name="arffieldinnermarginsetting_2" class="txtxbox_widget" style="width:142px;" type="text" onchange="arf_change_field_spacing2();" value="<?php echo esc_attr($newarr['arffieldinnermarginssetting_2']) ?>" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arffieldinnermarginssetting_2_exs" class="arf_slider" data-slider-id='arffieldinnermarginssetting_2_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="25" data-slider-step="1" data-dvalue="<?php echo floatval($newarr['arffieldinnermarginssetting_2']); ?>" data-slider-value="<?php echo floatval($newarr['arffieldinnermarginssetting_2']); ?>" />
                                <input type="hidden" name="arffieldinnermarginsetting_2" id="arffieldinnermarginsetting_2" value="<?php echo floatval($newarr['arffieldinnermarginssetting_2']); ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style=" <?php echo $field_vrtcl_spc_start; ?>" ><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style=" <?php echo $field_vrtcl_spc_end; ?>" ><?php _e('25 px', 'ARForms') ?></div>
                                </div>                                
                                <?php } ?>
                                
                                <input type="hidden" name="arffims" id="arffieldinnermarginsetting" style="width:100px;" class="txtxbox_widget" value="<?php echo $arffieldinnermarginssetting_value; ?>"  size="5" />
                            </div>    
                       
                            <div class="clear" style="margin-top:10px;">
            					<?php
									if(is_rtl())
									{
										$field_transparency_lbl = 'float:right;width:140px;text-align:right;margin-top:-3px;margin-left:50px;';
									}
									else
									{
										$field_transparency_lbl = 'float:left;width:140px;';
									}
								?>
            					<div class="lblsubheading lblsubheadingbold" style=" <?php echo $field_transparency_lbl; ?>"><?php _e('Field Transparency', 'ARForms') ?></div>
                                
                                <div>
                                <label><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch chkstanard" name="arfmfo" id="arfmainfield_opacity" value="<?php echo $newarr['arfmainfield_opacity'];?>" <?php if($newarr['arfmainfield_opacity']==1){ echo 'checked="checked"'; }?> /><label><span>&nbsp;YES</span></label>
                                
                                </div>
                                                        
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                             <?php
								if(is_rtl())
								{
									$calender_style_lbl = 'text-align:right;width:100%;';
								}
								else
								{
									$calender_style_lbl = 'width:100%;';
								}
							?>
                            <div class="field-group field-group-border clearfix" style="margin-top:10px;">
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $calender_style_lbl; ?>"><?php _e('Calendar Style', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield">
            
            					<?php
									if(is_rtl())
									{
										$calender_theme_lbl = 'float:right;width:126px;';
										$calender_theme_opt = 'float:right;margin-right:0px;';
									}
									else
									{
										$calender_theme_lbl = 'float:left;width:126px;';
										$calender_theme_opt = 'float:left;';
									}
								?>
                                <label class="lblsubheading sublblheading" style=" <?php echo $calender_theme_lbl; ?>"><?php _e('Theme', 'ARForms') ?></label>
            
                                <div class="sltstandard1" style=" <?php echo $calender_theme_opt; ?>">
                                
                                <?php /*?><select name="arffths" style="line-height:1;width:142px;" data-width='142px'>
            
            
                                    <?php 
            
            							$jquery_themes = $armainhelper->jquery_themes();
                                        foreach($jquery_themes as $theme_name => $theme_title){  ?>
            
            
                                    <option value="<?php echo $theme_name ?>" id="theme_<?php echo $theme_name ?>" <?php selected($theme_name, $newarr['arfcalthemename']) ?>><?php echo $theme_title ?></option> 
            
            
                                    <?php } ?>
            
            
                                </select><?php */?>
                                
								<?php
									$jquery_themes = $armainhelper->jquery_themes();
								?>
                                <input id="arfformsthemesettingselbx" name="arffths" value="<?php if($newarr['arfcalthemename']!="" && $newarr['arfcalthemename']!="default_theme_jquery-ui") { echo $newarr['arfcalthemename']; } else { echo "default_theme"; }?>" type="hidden" >
                                  <dl class="arf_selectbox" data-name="arffths" data-id="arfformsthemesettingselbx" style="width:122px;">
                                    <dt><span><?php if($newarr['arfcalthemename']!="" && $newarr['arfcalthemename']!="default_theme_jquery-ui") { echo $jquery_themes[$newarr['arfcalthemename']]; } else { echo $jquery_themes["default_theme"]; }?></span>
                                      <input value="<?php echo $newarr['arfcalthemename'];?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                    <dd>
                                      <ul style="display: none;" data-id="arfformsthemesettingselbx">
                                        <?php 
                                        foreach($jquery_themes as $theme_name => $theme_title){  ?>
                                            <li class="arf_selectbox_option" id="theme_<?php echo $theme_name ?>" data-value="<?php echo $theme_name ?>" data-label="<?php echo $theme_title ?>"><?php echo $theme_title ?></li>
                                        <?php } ?>
                                        
                                      </ul>
                                    </dd>
                                  </dl>

                                
                                </div>
                                
            
                               
            
            
                                <input type="hidden" value="<?php echo esc_attr($newarr['arfcalthemecss']) ?>" id="frm_theme_css" name="arffthc" />
            
            
                                <input type="hidden" value="<?php echo esc_attr($newarr['arfcalthemename']) ?>" id="frm_theme_name" name="arffthn" />
            
                                <input type="hidden" id="calender_url" value="<?php echo ARFURL.'/css/calender/'?>" />
                                <div class="clear"></div>
            
            
                            </div>
                           
                            <div class="field-group clearfix subfield" style="margin-top:11px;">
            					<?php
									if(is_rtl())
									{
										$date_format_lbl = 'float:right;text-align:right;width:126px;';
										$date_format_opt = 'float:left;margin-left:20px;';
									}
									else
									{
										$date_format_lbl = 'float:left;width:126px;';
										$date_format_opt = 'float:right;margin-right:17px;';
									}
								?>
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $date_format_lbl; ?>"><?php _e('Date Format', 'ARForms') ?></label>
                               
                                
                                <?php
                                $wp_format_date = get_option('date_format');
                                
                                if( $wp_format_date == 'F j, Y' || $wp_format_date =='m/d/Y' ) {
                                
                                 ?>
                                 <div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arffdaf" onchange="change_date_format_new();" id="frm_date_format" style="width:142px;" data-width='142px'>
            
            
                                    <option value="mm/dd/yy" <?php selected($newarr['date_format'], 'mm/dd/yy') ?>><?php echo date('m/d/Y', current_time('timestamp'));?></option>
            
                                    <option value="M d, yy" <?php selected($newarr['date_format'], 'M d, yy') ?>><?php echo date('M d, Y', current_time('timestamp'));?></option>
                                    
                                    <option value="MM d, yy" <?php selected($newarr['date_format'], 'MM d, yy') ?>><?php echo date('F d, Y', current_time('timestamp'));?></option>
            
            
                                </select><?php */?>
                                
                                <?php 
								$arf_selbx_dt_format = "";
                                if($newarr['date_format']=='MM d, yy')
								{
									$arf_selbx_dt_format = date('F d, Y', current_time('timestamp'));
								}else if($newarr['date_format']=='M d, yy') {
									$arf_selbx_dt_format = date('M d, Y', current_time('timestamp'));
								}else {
									$arf_selbx_dt_format = date('m/d/Y', current_time('timestamp'));
								}
								?>
                                <input id="frm_date_format" name="arffdaf" value="<?php echo $newarr['date_format'];?>" type="hidden" onchange="change_date_format_new();">
                                <dl class="arf_selectbox" data-name="arffdaf" data-id="frm_date_format" style="width:122px;">
                                  <dt><span><?php echo $arf_selbx_dt_format;?></span>
                                    <input value="<?php echo $arf_selbx_dt_format;?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_date_format">
                                      <li class="arf_selectbox_option" data-value="mm/dd/yy" data-label="<?php echo date('m/d/Y', current_time('timestamp'));?>"><?php echo date('m/d/Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="M d, yy" data-label="<?php echo date('M d, Y', current_time('timestamp'));?>"><?php echo date('M d, Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="MM d, yy" data-label="<?php echo date('F d, Y', current_time('timestamp'));?>"><?php echo date('F d, Y', current_time('timestamp'));?></li>
                                    </ul>
                                  </dd>
                                </dl>
            
                                </div>
                                 
                                 
                                
                                
                                  <?php } else if( $wp_format_date == 'd/m/Y' ) { ?>
                                
                                <div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arffdaf" id="frm_date_format" onchange="change_date_format_new();" style="width:142px;" data-width='142px'>
            
            
                                    <option value="dd/mm/yy" <?php selected($newarr['date_format'], 'dd/mm/yy') ?>><?php echo date('d/m/Y', current_time('timestamp'));?></option>
            
                                    <option value="d M, yy" <?php selected($newarr['date_format'], 'd M, yy') ?>><?php echo date('d M, Y', current_time('timestamp'));?></option>
                                    
                                    <option value="d MM, yy" <?php selected($newarr['date_format'], 'd MM, yy') ?>><?php echo date('d F, Y', current_time('timestamp'));?></option>
            
            
                                </select><?php */?>
                                
                                <?php 
								$arf_selbx_dt_format = "";
                                if($newarr['date_format']=='d MM, yy')
								{
									$arf_selbx_dt_format = date('d F, Y', current_time('timestamp'));
								}else if($newarr['date_format']=='d M, yy') {
									$arf_selbx_dt_format = date('d M, Y', current_time('timestamp'));
								}else {
									$arf_selbx_dt_format = date('d/m/Y', current_time('timestamp'));
								}
								?>
                                <input id="frm_date_format" name="arffdaf" value="<?php echo $newarr['date_format'];?>" type="hidden" onchange="change_date_format_new();">
                                <dl class="arf_selectbox" data-name="arffdaf" data-id="frm_date_format" style="width:122px;">
                                  <dt><span><?php echo $arf_selbx_dt_format;?></span>
                                    <input value="<?php echo $arf_selbx_dt_format;?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_date_format">
                                      <li class="arf_selectbox_option" data-value="dd/mm/yy" data-label="<?php echo date('d/m/Y', current_time('timestamp'));?>"><?php echo date('d/m/Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="d M, yy" data-label="<?php echo date('d M, Y', current_time('timestamp'));?>"><?php echo date('d M, Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="d MM, yy" data-label="<?php echo date('d F, Y', current_time('timestamp'));?>"><?php echo date('d F, Y', current_time('timestamp'));?></li>
                                    </ul>
                                  </dd>
                                </dl>

            
                                </div>
                                  
                                
                                
                                  <?php } else if( $wp_format_date == 'Y/m/d' ) { ?>
                                
                                <div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arffdaf" id="frm_date_format" onchange="change_date_format_new();" style="width:142px;" data-width='142px'>
            
            
                                    <option value="yy/mm/dd" <?php selected($newarr['date_format'], 'yy/mm/dd') ?>><?php echo date('Y/m/d', current_time('timestamp'));?></option>
            
                                    <option value="yy, M d" <?php selected($newarr['date_format'], 'yy, M d') ?>><?php echo date('Y, M d', current_time('timestamp'));?></option>
                                    
                                    <option value="yy, MM d" <?php selected($newarr['date_format'], 'yy, MM d') ?>><?php echo date('Y, F d', current_time('timestamp'));?></option>
            
            
                                </select><?php */?>
                                
                                <?php 
								$arf_selbx_dt_format = "";
                                if($newarr['date_format']=='yy, MM d')
								{
									$arf_selbx_dt_format = date('Y, F d', current_time('timestamp'));
								}else if($newarr['date_format']=='yy, M d') {
									$arf_selbx_dt_format = date('Y, M d', current_time('timestamp'));
								}else {
									$arf_selbx_dt_format = date('Y/m/d', current_time('timestamp'));
								}
								?>
                                <input id="frm_date_format" name="arffdaf" value="<?php echo $newarr['date_format'];?>" type="hidden" onchange="change_date_format_new();">
                                <dl class="arf_selectbox" data-name="arffdaf" data-id="frm_date_format" style="width:122px;">
                                  <dt><span><?php echo $arf_selbx_dt_format;?></span>
                                    <input value="<?php echo $arf_selbx_dt_format;?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_date_format">
                                      <li class="arf_selectbox_option" data-value="yy/mm/dd" data-label="<?php echo date('Y/m/d', current_time('timestamp'));?>"><?php echo date('Y/m/d', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="yy, M d" data-label="<?php echo date('Y, M d', current_time('timestamp'));?>"><?php echo date('Y, M d', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="yy, MM d" data-label="<?php echo date('Y, F d', current_time('timestamp'));?>"><?php echo date('Y, F d', current_time('timestamp'));?></li>
                                    </ul>
                                  </dd>
                                </dl>

            
                                </div>  
                                  
                              
                                
                                  <?php } else { ?>
                                
                                <div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arffdaf" id="frm_date_format" onchange="change_date_format_new();" style="width:142px;" data-width='142px'>
            
            
                                    <option value="dd/mm/yy" <?php selected($newarr['date_format'], 'dd/mm/yy') ?>><?php echo date('d/m/Y', current_time('timestamp'));?></option>
            
                                    <option value="mm/dd/yy" <?php selected($newarr['date_format'], 'mm/dd/yy') ?>><?php echo date('m/d/Y', current_time('timestamp'));?></option>
                                    
                                    <option value="yy/mm/dd" <?php selected($newarr['date_format'], 'yy/mm/dd') ?>><?php echo date('Y/m/d', current_time('timestamp'));?></option>
                                    
                                    <option value="M d, yy" <?php selected($newarr['date_format'], 'M d, yy') ?>><?php echo date('M d, Y', current_time('timestamp'));?></option>
                                    
                                    <option value="MM d, yy" <?php selected($newarr['date_format'], 'MM d, yy') ?>><?php echo date('F d, Y', current_time('timestamp'));?></option>
            
            
                                </select><?php */?>
                                
                                <?php 
								$arf_selbx_dt_format = "";
                                if($newarr['date_format']=='MM d, yy')
								{
									$arf_selbx_dt_format = date('F d, Y', current_time('timestamp'));
								}else if($newarr['date_format']=='M d, yy') {
									$arf_selbx_dt_format = date('M d, Y', current_time('timestamp'));
								}
								else if($newarr['date_format']=='yy/mm/dd') {
									$arf_selbx_dt_format = date('Y/m/d', current_time('timestamp'));
								}
								else if($newarr['date_format']=='mm/dd/yy') {
									$arf_selbx_dt_format = date('m/d/Y', current_time('timestamp'));
								}else {
									$arf_selbx_dt_format = date('d/m/Y', current_time('timestamp'));
								}
								?>
                                <input id="frm_date_format" name="arffdaf" value="<?php echo $newarr['date_format'];?>" type="hidden" onchange="change_date_format_new();">
                                <dl class="arf_selectbox" data-name="arffdaf" data-id="frm_date_format" style="width:122px;">
                                  <dt><span><?php echo $arf_selbx_dt_format;?></span>
                                    <input value="<?php echo $arf_selbx_dt_format;?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_date_format">
                                      <li class="arf_selectbox_option" data-value="dd/mm/yy" data-label="<?php echo date('d/m/Y', current_time('timestamp'));?>"><?php echo date('d/m/Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="mm/dd/yy" data-label="<?php echo date('m/d/Y', current_time('timestamp'));?>"><?php echo date('m/d/Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="yy/mm/dd" data-label="<?php echo date('Y/m/d', current_time('timestamp'));?>"><?php echo date('Y/m/d', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="M d, yy" data-label="<?php echo date('M d, Y', current_time('timestamp'));?>"><?php echo date('M d, Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="MM d, yy" data-label="<?php echo date('F d, Y', current_time('timestamp'));?>"><?php echo date('F d, Y', current_time('timestamp'));?></li>
                                    </ul>
                                  </dd>
                                </dl>

            
                                </div>  
                                
                                
                                
                                  <?php } ?>                   
            
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:11px;">
            					<?php
									if(is_rtl())
									{
										$check_radio_style_main_lbl = 'float:right;text-align:right;width:100%;';
										$check_radio_style_lbl = 'float:right;width:130px;';
									}
									else
									{
										$check_radio_style_main_lbl = 'width:100%;';
										$check_radio_style_lbl = 'float:left;width:130px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $check_radio_style_main_lbl; ?>"><?php _e('Checkbox & Radio Style', 'ARForms') ?></label> <br />
            
                            </div>
                            <div class="clearfix subfield" id="frm_check_radio_style_div">
                            	<label class="lblsubheading sublblheading" style=" <?php echo $check_radio_style_lbl; ?>"><?php _e('Style', 'ARForms') ?></label>
                            	<div class="sltstandard1" style="float:left;">
                                
                                    <?php /*?><select name="arfcksn" id="frm_check_radio_style" data-size="4" onchange="arf_change_check_radio(); ShowColorSelect(this.value);" style="width:142px;" data-width='142px'>
            
                                    <option value="minimal" <?php selected($newarr['arfcheckradiostyle'], 'minimal') ?>>Minimal</option>
            
                                    <option value="flat" <?php selected($newarr['arfcheckradiostyle'], 'flat') ?>>Flat</option>
                                    
                                    <option value="square" <?php selected($newarr['arfcheckradiostyle'], 'square') ?>>Square</option>
                                    
                                    <option value="futurico" <?php selected($newarr['arfcheckradiostyle'], 'futurico') ?>>Futurico</option>
                                    
                                    <option value="polaris" <?php selected($newarr['arfcheckradiostyle'], 'polaris') ?>>Polaris</option>
                                    
                                    <option value="none" <?php selected($newarr['arfcheckradiostyle'], 'none') ?>>(None)</option>
            
                                </select><?php */?>
            
            						
                                    <input id="frm_check_radio_style" name="arfcksn" value="<?php echo $newarr['arfcheckradiostyle'];?>" type="hidden" onchange="arf_change_check_radio(); ShowColorSelect(this.value);">
                                    <dl class="arf_selectbox" data-name="arfcksn" data-id="frm_check_radio_style" style="width:122px;">
                                      <dt><span><?php echo ucwords($newarr['arfcheckradiostyle']);?></span>
                                        <input value="<?php echo $newarr['arfcheckradiostyle'];?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                      <dd>
                                        <ul style="display: none;" data-id="frm_check_radio_style">
                                          <li class="arf_selectbox_option" data-value="minimal" data-label="Minimal">Minimal</li>
                                          <li class="arf_selectbox_option" data-value="flat" data-label="Flat">Flat</li>
                                          <li class="arf_selectbox_option" data-value="square" data-label="Square">Square</li>
                                          <li class="arf_selectbox_option" data-value="futurico" data-label="Futurico">Futurico</li>
                                          <li class="arf_selectbox_option" data-value="polaris" data-label="Polaris">Polaris</li>
                                          <li class="arf_selectbox_option" data-value="none" data-label="(None)">(None)</li>
                                        </ul>
                                      </dd>
                                    </dl>

            						
                                </div>  
							</div>
                            <div class="clearfix subfield" id="check_radio_main_color" <?php if($newarr['arfcheckradiostyle']!="none"  && $newarr['arfcheckradiostyle']!="polaris" && $newarr['arfcheckradiostyle']!="futurico"){?> style="display:block;margin-top:10px;" <?php }else{ echo "style='display:none;margin-top:10px;'"; }?>>
                            	<label class="lblsubheading sublblheading" style=" <?php echo $check_radio_style_lbl; ?>"><?php _e('Color', 'ARForms') ?></label>
                            	<div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arfcksc" id="frm_check_radio_style_color" onchange="arf_change_check_radio();" data-size="4" style="width:142px;" data-width='142px'>
            						<option value="default" <?php selected($newarr['arfcheckradiocolor'], 'default') ?>>Default</option>
                                    <option value="aero" <?php selected($newarr['arfcheckradiocolor'], 'aero') ?>>Aero</option>
                                    <option value="blue" <?php selected($newarr['arfcheckradiocolor'], 'blue') ?>>Blue</option>
                                    <option value="green" <?php selected($newarr['arfcheckradiocolor'], 'green') ?>>Green</option>
                                    <option value="grey" <?php selected($newarr['arfcheckradiocolor'], 'grey') ?>>Grey</option>
                                    <option value="orange" <?php selected($newarr['arfcheckradiocolor'], 'orange') ?>>Orange</option>
                                    <option value="pink" <?php selected($newarr['arfcheckradiocolor'], 'pink') ?>>Pink</option>
                                    <option value="purple" <?php selected($newarr['arfcheckradiocolor'], 'purple') ?>>Purple</option>
                                    <option value="red" <?php selected($newarr['arfcheckradiocolor'], 'red') ?>>Red</option>
                                    <option value="yellow" <?php selected($newarr['arfcheckradiocolor'], 'yellow') ?>>Yellow</option>
            
                                </select><?php */?>
                                
                                <input id="frm_check_radio_style_color" name="arfcksc" value="<?php echo $newarr['arfcheckradiocolor'];?>" type="hidden" onchange="arf_change_check_radio();">
                                <dl class="arf_selectbox" data-name="arfcksc" data-id="frm_check_radio_style_color" style="width:122px;">
                                  <dt><span><?php echo ucwords($newarr['arfcheckradiocolor']);?></span>
                                    <input value="<?php echo $newarr['arfcheckradiocolor'];?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_check_radio_style_color">
                                      <li class="arf_selectbox_option" data-value="default" data-label="Default">Default</li>
                                      <li class="arf_selectbox_option" data-value="aero" data-label="Aero">Aero</li>
                                      <li class="arf_selectbox_option" data-value="blue" data-label="Blue">Blue</li>
                                      <li class="arf_selectbox_option" data-value="green" data-label="Green">Green</li>
                                      <li class="arf_selectbox_option" data-value="grey" data-label="Grey">Grey</li>
                                      <li class="arf_selectbox_option" data-value="orange" data-label="Orange">Orange</li>
                                      <li class="arf_selectbox_option" data-value="pink" data-label="Pink">Pink</li>
                                      <li class="arf_selectbox_option" data-value="purple" data-label="Purple">Purple</li>
                                      <li class="arf_selectbox_option" data-value="red" data-label="Red">Red</li>
                                      <li class="arf_selectbox_option" data-value="yellow" data-label="Yellow">Yellow</li>
                                    </ul>
                                  </dd>
                                </dl>

            
                                </div>  
							</div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div>
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:11px;">
            					<?php
									if(is_rtl())
									{
										$prefix_suffix_style_main_lbl = 'float:right;text-align:right;width:100%;';
										$prefix_suffix_style_lbl = 'float:right;width:130px;';
										$prefix_suffix_bg_col_lbl_cls = 'arf_float_left';
										$prefix_suffix_bg_col_lbl_css = 'float:right;';
									}
									else
									{
										$prefix_suffix_style_main_lbl = 'width:100%;';
										$prefix_suffix_style_lbl = 'float:left;width:130px;';
										$prefix_suffix_bg_col_lbl_cls = 'arf_float_right';
										$prefix_suffix_bg_col_lbl_css = 'float:left;';
									}
								?>
                             <label class="lblsubheading lblsubheadingbold" style=" <?php echo $prefix_suffix_style_main_lbl; ?>"><?php _e('Prefix & Suffix Style', 'ARForms') ?></label> <br />
                            </div>
                            <div class="clearfix subfield" id="frm_prefix_suffix_style_div">
                            	<label class="lblsubheading sublblheading" style=" <?php echo $prefix_suffix_style_lbl; ?>"><?php _e('Background', 'ARForms') ?></label>
                                <div class=" <?php echo $prefix_suffix_bg_col_lbl_cls; ?>" style=" <?php echo $prefix_suffix_bg_col_lbl_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="prefix_suffix_bg_color" style="background:<?php echo esc_attr($newarr['prefix_suffix_bg_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="pfsfsbg" id="prefix_suffix_bg_color" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['prefix_suffix_bg_color']) ?>" style="width:100px;" />
                                </div>
                            	
							</div>
                            
                            <div class="clearfix subfield" id="frm_prefix_suffix_style_div2">
                            	<label class="lblsubheading sublblheading" style=" <?php echo $prefix_suffix_style_lbl; ?>"><?php _e('Icon Color', 'ARForms') ?></label>
                                <div class=" <?php echo $prefix_suffix_bg_col_lbl_cls; ?>" style=" <?php echo $prefix_suffix_bg_col_lbl_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="prefix_suffix_icon_color" style="background:<?php echo esc_attr($newarr['prefix_suffix_icon_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="pfsfscol" id="prefix_suffix_icon_color" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['prefix_suffix_icon_color']) ?>" style="width:100px;" />
                                </div>
                            	
							</div>
                            
                            <div style="height:10px;">&nbsp;</div>
                            
                        </div>
            
            
                    </div>
            
                    <div id="tabsubmitbuttonsetting" class="widget clearfix global-font">
            
            
                        <div class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Submit Button Settings', 'ARForms') ?></h4></div>
            
            
                        </div>
            
            
                        <div class="widget-inside">
            
            			   <div class="field-group field-group-border clearfix" >
            					<?php
									if(is_rtl())
									{
										$submit_align_lbl = 'float:right;width:132px;';
										$submit_align_btn = 'float:right;padding-top:5px;';
									}
									else
									{
										$submit_align_lbl = 'width:132px;';
										$submit_align_btn = 'float:left;padding-top:5px;';
									}
								?>
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_align_lbl; ?>"><?php _e('Submit align', 'ARForms') ?></label>
            
            					<div style=" <?php echo $submit_align_btn; ?>" class="sltstandard1">
                                	<div class="toggle-btn-grp joint-toggle">
                                            <label onclick="" class="toggle-btn normal <?php if($newarr['arfsubmitalignsetting']=="fixed"){ echo "success"; }?>"><input type="radio" name="arfmsas" class="visuallyhidden" id="frm_submit_align_1" value="fixed" <?php checked($newarr['arfsubmitalignsetting'], 'fixed'); ?> /><?php _e('Fixed', 'ARForms') ?></label><label onclick="" class="toggle-btn normal <?php if($newarr['arfsubmitalignsetting']=="auto"){ echo "success"; }?>"><input type="radio" name="arfmsas" id="frm_submit_align_2"  class="visuallyhidden" value="auto" <?php checked($newarr['arfsubmitalignsetting'], 'auto'); ?> /><?php _e('Auto', 'ARForms') ?></label>
                                    </div>
                                </div>    
            
                            </div>	
            				
                            <div class="widget_bg_bottom" style="padding-bottom:12px;" ></div> 
                            
                            <div class="field-group clearfix " style="margin-top:10px;">
            					<?php
                                	if(is_rtl())
									{
										$submit_font_setting_lbl = 'float:right;text-align:right;width:100%;';
										$submit_font_popup_close = 'float:left;';
										$submit_font_popup_cls_btn = 'margin-top:-12px;margin-right:3px;';
										$submit_font_popup_box = 'margin-right:25px;';
									}
									else
									{
										$submit_font_setting_lbl = 'width:100%;';
										$submit_font_popup_close = 'float:right;';
										$submit_font_popup_cls_btn = 'margin-top:-12px;margin-right:3px;';
										$submit_font_popup_box = 'margin-left:25px;';
									}
                                ?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_font_setting_lbl; ?>"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <?php if($newarr['arfsubmitweightsetting']!="normal"){ $submit_font_weight_html = ", ".$newarr['arfsubmitweightsetting']; }?>
                            <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style=" <?php echo $submit_font_popup_box;?>">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showsubmitfontsettingpopup" onclick="arfshowformsettingpopup('submitfontsettingpopup')"><?php echo $newarr['arfsubmitfontfamily'].", ".$newarr['arfsubmitbuttonfontsizesetting']."px ".$submit_font_weight_html;?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('submitfontsettingpopup')" /></div>
                                    <div id="submitfontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style=" <?php echo $submit_font_popup_close; ?>">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('submitfontsettingpopup')" type="button" style=" <?php echo $submit_font_popup_cls_btn; ?>">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            									<?php
													if(is_rtl())
													{
														$submit_font_family_lbl = 'float:right;width:50px;padding-left:10px;padding-top:7px;height:20px;';
														$submit_font_family_opt = 'float:left;margin-right:70px;margin-bottom:10px;position:absolute;';
													}
													else
													{
														$submit_font_family_lbl = 'float:left;width:50px;padding-left:10px;padding-top:7px;height:20px;';
														$submit_font_family_opt = 'float:right;margin-left:70px;margin-bottom:10px;position:absolute;';
													}
												?>
            
                                                <div class="lblsubheading" style=" <?php echo $submit_font_family_lbl; ?>"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <div class="sltstandard2" style=" <?php echo $submit_font_family_opt; ?>">
                                                
                                                <?php /*?><select name="arfsff" id="arfsubmitfontfamily" style="width:200px;" data-width='200px' data-size="15" onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
                                                    <optgroup label="Default Fonts">
                                                        <option value="Arial" <?php selected($newarr['arfsubmitfontfamily'], 'Arial') ?>>Arial</option>
                                        
                                                        <option value="Helvetica" <?php selected($newarr['arfsubmitfontfamily'], 'Helvetica') ?>>Helvetica</option>
                                                        
                                                        <option value="sans-serif" <?php selected($newarr['arfsubmitfontfamily'], 'sans-serif') ?>>sans-serif</option>
                                                        
                                                        <option value="Lucida Grande" <?php selected($newarr['arfsubmitfontfamily'], 'Lucida Grande') ?>>Lucida Grande</option>
                                                        
                                                        <option value="Lucida Sans Unicode" <?php selected($newarr['arfsubmitfontfamily'], 'Lucida Sans Unicode') ?>>Lucida Sans Unicode</option>
                                                        
                                                        <option value="Tahoma" <?php selected($newarr['arfsubmitfontfamily'], 'Tahoma') ?>>Tahoma</option>
                                                        
                                                        <option value="Times New Roman" <?php selected($newarr['arfsubmitfontfamily'], 'Times New Roman') ?>>Times New Roman</option>
                                                        
                                                        <option value="Courier New" <?php selected($newarr['arfsubmitfontfamily'], 'Courier New') ?>>Courier New</option>
                                                        
                                                        <option value="Verdana" <?php selected($newarr['arfsubmitfontfamily'], 'Verdana') ?>>Verdana</option>
                                                        
                                                        <option value="Geneva" <?php selected($newarr['arfsubmitfontfamily'], 'Geneva') ?>>Geneva</option>
                                                        
                                                        <option value="Courier" <?php selected($newarr['arfsubmitfontfamily'], 'Courier') ?>>Courier</option>
                                                                
                                                        <option value="Monospace" <?php selected($newarr['arfsubmitfontfamily'], 'Monospace') ?>>Monospace</option>
                                                                
                                                        <option value="Times" <?php selected($newarr['arfsubmitfontfamily'], 'Times') ?>>Times</option>
                
                                                    </optgroup>
                                                    <optgroup label="Google Fonts">
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<option value='".$goglefontsfamily."' ".selected($newarr['arfsubmitfontfamily'], $goglefontsfamily)." >".$goglefontsfamily."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </optgroup>
                                                    
                                                </select><?php */?>
                                                
                                                
                                                <input id="arfsubmitfontfamily" name="arfsff" value="<?php echo $newarr['arfsubmitfontfamily'];?>" type="hidden" onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
                                                <dl class="arf_selectbox" data-name="arfsff" data-id="arfsubmitfontfamily" style="width:180px;">
                                                  <dt><span><?php echo $newarr['arfsubmitfontfamily'];?></span>
                                                    <input value="<?php echo $newarr['arfsubmitfontfamily'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                  <dd>
                                                    <ul style="display: none;" data-id="arfsubmitfontfamily">
                                                      <ol class="arp_selectbox_group_label">
                                                        Default Fonts
                                                      </ol>
                                                      <li class="arf_selectbox_option" data-value="Arial" data-label="Arial">Arial</li>
                                                      <li class="arf_selectbox_option" data-value="Helvetica" data-label="Helvetica">Helvetica</li>
                                                      <li class="arf_selectbox_option" data-value="sans-serif" data-label="sans-serif">sans-serif</li>
                                                      <li class="arf_selectbox_option" data-value="Lucida Grande" data-label="Lucida Grande">Lucida Grande</li>
                                                      <li class="arf_selectbox_option" data-value="Lucida Sans Unicode" data-label="Lucida Sans Unicode">Lucida Sans Unicode</li>
                                                      <li class="arf_selectbox_option" data-value="Tahoma" data-label="Tahoma">Tahoma</li>
                                                      <li class="arf_selectbox_option" data-value="Times New Roman" data-label="Times New Roman">Times New Roman</li>
                                                      <li class="arf_selectbox_option" data-value="Courier New" data-label="Courier New">Courier New</li>
                                                      <li class="arf_selectbox_option" data-value="Verdana" data-label="Verdana">Verdana</li>
                                                      <li class="arf_selectbox_option" data-value="Geneva" data-label="Geneva">Geneva</li>
                                                      <li class="arf_selectbox_option" data-value="Courier" data-label="Courier">Courier</li>
                                                      <li class="arf_selectbox_option" data-value="Monospace" data-label="Monospace">Monospace</li>
                                                      <li class="arf_selectbox_option" data-value="Times" data-label="Times">Times</li>
                                                      <ol class="arp_selectbox_group_label">
                                                        Google Fonts
                                                      </ol>
                                                      <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                    </ul>
                                                  </dd>
                                                </dl>

                                                
                                                </div>
                                            </div>
                                        	<div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
													if(is_rtl())
													{
														$submit_btn_font_style = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
														$submit_btn_font_btn = 'float:left; margin-right:70px; margin-bottom:10px; position:absolute;';
													}
													else
													{
														$submit_btn_font_style = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
														$submit_btn_font_btn = 'float:right; margin-left:70px; margin-bottom:10px; position:absolute;';
													}
												?>
            									<div class="lblsubheading" style=" <?php echo $submit_btn_font_style ?>"><?php _e('Style', 'ARForms') ?></div>
                            
                                                <div class="sltstandard1" style=" <?php echo $submit_btn_font_btn; ?>">
                                                
                                                <?php /*?><select name="arfsbwes" id="arfsubmitbuttonweightsetting" style="width:100px;" data-width='100px' onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
            
                                                    <option value="normal" <?php selected($newarr['arfsubmitweightsetting'], 'normal') ?>><?php _e('normal', 'ARForms') ?></option>
                    
                                                    <option value="bold" <?php selected($newarr['arfsubmitweightsetting'], 'bold') ?>><?php _e('bold', 'ARForms') ?></option>
                                                    
                                                    <option value="italic" <?php selected($newarr['arfsubmitweightsetting'], 'italic') ?>><?php _e('italic', 'ARForms') ?></option>
                            
                                                </select><?php */?>
                                                
                                                <input id="arfsubmitbuttonweightsetting" name="arfsbwes" value="<?php echo $newarr['arfsubmitweightsetting'];?>" type="hidden" onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
                                                <dl class="arf_selectbox" data-name="arfsbwes" data-id="arfsubmitbuttonweightsetting" style="width:80px;">
                                                  <dt><span><?php echo __($newarr['arfsubmitweightsetting'], 'ARForms');?></span>
                                                    <input value="<?php echo __($newarr['arfsubmitweightsetting'], 'ARForms');?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                  <dd>
                                                    <ul style="display: none;" data-id="arfsubmitbuttonweightsetting">
                                                      <li class="arf_selectbox_option" data-value="normal" data-label="<?php _e('normal', 'ARForms');?>"><?php _e('normal', 'ARForms');?></li>
                                                      <li class="arf_selectbox_option" data-value="bold" data-label="<?php _e('bold', 'ARForms') ?>"><?php _e('bold', 'ARForms') ?></li>
                                                      <li class="arf_selectbox_option" data-value="italic" data-label="<?php _e('italic', 'ARForms') ?>"><?php _e('italic', 'ARForms') ?></li>
                                                    </ul>
                                                  </dd>
                                                </dl>

                                                
                                                </div>
                                                
                            
                                            </div>
                                            <?php
												if(is_rtl())
												{
													$submit_font_size_lbl = 'float:right;width:50px;padding-left:10px;padding-top:7px;height:20px;';
													$submit_font_size_opt = 'float:left;position:absolute;margin-right:70px;';
													$submit_font_px = 'float:right;margin-right:105px;padding-top:5px;';
												}
												else
												{
													$submit_font_size_lbl = 'float:left;width:50px;padding-left:10px;padding-top:7px;height:20px;';
													$submit_font_size_opt = 'float:right;position:absolute;';
													$submit_font_px = 'float:right;margin-right:90px;padding-top:5px;';
												}
											?>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            									<div class="lblsubheading" style=" <?php echo $submit_font_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></div>
                                                <div style="margin-left:70px; margin-bottom:10px;">
                                                    <div class="sltstandard1" style=" <?php echo $submit_font_size_opt; ?>">
                                                    
                                                    <?php /*?><select name="arfsbfss" id="arfsubmitbuttonfontsizesetting" style="width:100px;" data-width='100px' data-size='15' onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">	
														<?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['arfsubmitbuttonfontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                        <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['arfsubmitbuttonfontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                        <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['arfsubmitbuttonfontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                </select><?php */?>
                                                
                                                <input id="arfsubmitbuttonfontsizesetting" name="arfsbfss" value="<?php echo $newarr['arfsubmitbuttonfontsizesetting'];?>" type="hidden" onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
                                                <dl class="arf_selectbox" data-name="arfsbfss" data-id="arfsubmitbuttonfontsizesetting" style="width:80px;">
                                                  <dt><span><?php echo $newarr['arfsubmitbuttonfontsizesetting'];?></span>
                                                    <input value="<?php echo $newarr['arfsubmitbuttonfontsizesetting'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                  <dd>
                                                    <ul style="display: none;" data-id="arfsubmitbuttonfontsizesetting">
                                                      <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                      <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                      <?php } ?>
                                                      <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                      <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                      <?php } ?>
                                                      <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                      <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                      <?php } ?>
                                                    </ul>
                                                  </dd>
                                                </dl>
                                                    
                                                    </div>
                                                    <div class="arf_px" style=" <?php echo $submit_font_px; ?>"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                           
                            
                            
                           	
            
            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
            
            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$submit_btn_width_height = 'float:right;width:137px;text-align:right;';
										$submit_btn_note = 'float:right;width:280px;';
									}
									else
									{
										$submit_btn_width_height = 'width:137px;';
										$submit_btn_note = 'width:280px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_btn_width_height; ?>"><?php _e('Button Width', 'ARForms') ?><br />(<?php _e('Optional','ARForms');?>)</label>
            
                                <div style="padding-left:10px;">
                                    <div class="arf_float_left">
                                    <input type="text" name="arfsbws" id="arfsubmitbuttonwidthsetting" style="width:142px;" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfsubmitbuttonwidthsetting']) ?>"  onchange="arfsetsubmitwidth();" size="5" />&nbsp;<?php _e('px', 'ARForms') ?>
                                    </div>
                                    <label class="howto" style=" <?php echo $submit_btn_note;?>"><?php _e('If not provided anything it will be auto','ARForms');?></label>
                                    <input type="hidden" name="arfsbaw" id="arfsubmitautowidth" value="<?php echo $newarr['arfsubmitautowidth']; ?>" /> 
                                </div>
            
                            </div>
            
            
                            
            
            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_btn_width_height; ?>"><?php _e(' Button Height', 'ARForms') ?><br />(<?php _e('Optional','ARForms');?>)</label>
            
                                <div style="padding-left:10px;">
                                	<div class="arf_float_left">
                                		<input type="text" name="arfsbhs" id="arfsubmitbuttonheightsetting" style="width:142px;" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfsubmitbuttonheightsetting']) ?>"  size="5" />&nbsp;<?php _e('px', 'ARForms') ?>
                                	</div>
                                <label class="howto" style=" <?php echo $submit_btn_note; ?>"><?php _e('If not provided anything it will be auto','ARForms');?></label>
                                </div>
            
                            </div>
            
            
                            <div class="field-group field-group-border clearfix  subfield" style="margin-bottom:10px; padding-top:5px;">
                           		<?php
									$newarr['arfsubmitbuttontext'] = isset($newarr['arfsubmitbuttontext']) ? $newarr['arfsubmitbuttontext'] : ''; 
									if($newarr['arfsubmitbuttontext'] == '')
									{
										$arf_option = get_option('arf_options');
										$submit_value = $arf_option->submit_value;
									}
									else
									{
										$submit_value = esc_attr($newarr['arfsubmitbuttontext']);
									}
								?>
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_btn_width_height; ?>"><?php _e('Text', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$submit_btn_text_cls = 'arf_float_right';
										$submit_btn_text_css  = '';
									}
									else
									{
										$submit_btn_text_cls = 'arf_float_left';
										$submit_btn_text_css  = '';
									}
								?>
                                <div class=" <?php echo $submit_btn_text_cls; ?>" style=" <?php echo $submit_btn_text_css; ?>">
                                <input type="text" name="arfsubmitbuttontext" id="arfsubmitbuttontext" style="width:142px;" onkeyup="arfsetsubmitautowdith2();" onchange="arfchangesubmitvalue();arfsetsubmitautowdith2();" class="txtxbox_widget" value="<?php echo  $submit_value;?>"  size="5" />
                                </div>
                                                                                              
                            </div>
            
            				<div class="field-group clearfix widget_bg_bottom" style="margin-top:16px;">
            
            					<?php
									if(is_rtl())
									{
										$submit_txt_color_lbl = 'float:right;text-align:right;width:135px;';
										$submit_txt_color_cls = 'arf_float_left';
										$submit_txt_color_css = 'float:right;';
									}
									else
									{
										$submit_txt_color_lbl = 'width:135px;';
										$submit_txt_color_cls = 'arf_float_right';
										$submit_txt_color_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_txt_color_lbl; ?>"><?php _e('Text Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $submit_txt_color_cls; ?>" style=" <?php echo $submit_txt_color_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttontextcolorsetting" style="background:<?php echo esc_attr($newarr['arfsubmittextcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfsbtcs" id="arfsubmitbuttontextcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsubmittextcolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$submit_btn_bg_lbl = 'text-align:right;width:100%;float:right;';
										$submit_btn_bg_col_main = 'margin-top:10px; float: right; clear: none; width:100%;';
										$submit_btn_default_col = 'float:right;text-align:right;padding-right:10px;padding-left:12px;';
										$submit_btn_default_col_cls = 'arf_float_left';
										$submit_btn_default_col_css = 'float:right;';
									}
									else
									{
										$submit_btn_bg_lbl = 'width:100%;';
										$submit_btn_bg_col_main = 'margin-top:10px; float:left; clear:none; width:100%;';
										$submit_btn_default_col = 'float:left;padding-left:10px;padding-right:12px;';
										$submit_btn_default_col_cls = 'arf_float_right';
										$submit_btn_default_col_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_btn_bg_lbl; ?>"><?php _e('Background', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix " style=" <?php echo $submit_btn_bg_col_main; ?>">
            
            
                                <label class="lblsubheading" style=" <?php echo $submit_btn_default_col; ?>"><?php _e('Default Color', 'ARForms') ?></label>
            
            					<div class="<?php echo $submit_btn_default_col_cls; ?>" style=" <?php echo $submit_btn_default_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttonbgcolorsetting" style="background:<?php echo esc_attr($newarr['submit_bg_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfsbbcs" id="arfsubmitbuttonbgcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['submit_bg_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            <?php
								if(is_rtl())
								{
									$hover_color_main = 'float:left;margin-top:11px;clear:left;width:100%;';
									$hover_color_lbl = 'float:right;text-align:right;padding-right:10px;padding-left:12px;';
									$hover_color_cls = 'arf_float_left';
									$hover_color_css = 'float:right;';
								}
								else
								{
									$hover_color_main = 'float:left;margin-top:11px;clear:none;width:100%;';
									$hover_color_lbl = 'float:left;padding-left:10px;padding-right:12px;';
									$hover_color_cls = 'arf_float_right';
									$hover_color_css = 'float:left;';
								}
							?>
                            <div class="field-group field-group-border clearfix " style=" <?php echo $hover_color_main; ?>">
            
            
                                <label class="lblsubheading" style=" <?php echo $hover_color_lbl; ?>"><?php _e('Hover Color', 'ARForms') ?></label>
            
            					<div class="<?php echo $hover_color_cls; ?>" style=" <?php echo $hover_color_css; ?> ">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttoncolorhoversetting" style="background:<?php echo esc_attr($newarr['arfsubmitbuttonbgcolorhoversetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfsbchs" id="arfsubmitbuttoncolorhoversetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsubmitbuttonbgcolorhoversetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            
                            <input type="hidden" name="arfsbcs" id="arfsubmitbuttoncolorsetting" class="hex txtxbox_widget" value="<?php echo esc_attr($newarr['arfsubmitbgcolor2setting']) ?>" style="width:80px;" />
            				
                            <div class="field-group field-group-border clearfix widget_bg_bottom" style="padding-top:11px; padding-bottom:10px;">
            					<?php
									if(is_rtl())
									{
										$submit_btn_bg_img_lbl = 'float:right;text-align:right;width:130px;';
										$submit_btn_bg_img = 'float:left;';
										$submit_btn_bg_img_loader = 'display:none; float: left; margin: 5px 0 0;';
										$submit_btn_ajax_upload = 'position: relative; overflow: hidden; float:right; cursor: pointer;';
									}
									else
									{
										$submit_btn_bg_img_lbl = 'width:130px;';
										$submit_btn_bg_img = 'float:right;';
										$submit_btn_bg_img_loader = 'display:none; float: left; margin: 5px 0 0;';
										$submit_btn_ajax_upload = 'position: relative; overflow: hidden; float:left; cursor: pointer;';
									}
								?>
                                <label class="lblsubheading" style=" <?php echo $submit_btn_bg_img_lbl; ?>"><?php _e('Background Image', 'ARForms') ?></label>
            
                                <div id="submit_btn_img_div" <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9'){ ?> class="iframe_submit_original_btn" data-id="arfsbis" style="margin-left:5px; position: relative; overflow: hidden; float:left; cursor:pointer; max-width:130px; height:22px; background: #1BBAE1; font-weight:bold; <?php if($newarr['submit_bg_img'] == '') { ?> background:#1BBAE1;padding:7px 10px 0 10px;font-size:13px;border-radius:3px;color:#FFFFFF;border:1px solid #CCCCCC;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.4); <?php } ?>" <?php }else { ?> style="margin-left:0px;" <?php } ?>>
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' && $newarr['submit_bg_img'] == ''){ ?> <span style="display:inline-block;color:#FFFFFF;text-align:center;"><?php _e('Upload', 'ARForms');?></span> <?php } ?>
                                    <?php
                                    if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ 
										if( $newarr['submit_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['submit_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_submit_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="<?php echo esc_attr($newarr['submit_bg_img']) ?>" id="arfsubmitbuttonimagesetting" />
                                        <?php } else { ?>
                                        <input type="text" class="original" name="submit_btn_img" id="field_arfsbis" form-id="" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; width: 160px; height: 59px; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        <input type="hidden" id="type_arfsbis" name="type_arfsbis" value="1" >
										<input type="hidden" value="jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico" id="file_types_arfsbis" name="field_types_arfsbis" />
                                        
                                        <input type="hidden" name="imagename" id="imagename" value="" />
                                        <input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="" id="arfsubmitbuttonimagesetting" />
                                        <?php }
										echo '<div id="arfsbis_iframe_div"><iframe style="display:none;" id="arfsbis_iframe" src="'.ARFURL.'/core/views/iframe.php" ></iframe></div>';
                                    }else { 
                                    	if( $newarr['submit_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['submit_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_submit_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="<?php echo esc_attr($newarr['submit_bg_img']) ?>" id="arfsubmitbuttonimagesetting" />
                                        <?php } else { ?>
                                        <div class="arfajaxfileupload" style="position: relative; overflow: hidden; float:left; cursor: pointer;">
                                        	<div class="file-upload-img"></div>
                                            <?php _e('Upload', 'ARForms');?>
                                            <input type="file" name="submit_btn_img" id="submit_btn_img" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        </div>
                                        
                                        <input type="hidden" name="imagename" id="imagename" value="" />
                                        <input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="" id="arfsubmitbuttonimagesetting" />
                                         &nbsp;&nbsp;<span id="ajax_submit_loader" style="display:none; float: left; margin: 5px 0 0;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/wpspin_light.gif"></span>
										<?php }
                                    } ?>
                                    
                                </div>
                                
                                
                                
                                <div style="float:left;width:300px;height:15px;"></div> 
                                
                                <label class="lblsubheading" style=" <?php echo $submit_btn_bg_img_lbl; ?>"><?php _e('Background Hover Image', 'ARForms') ?></label>
            
                                <div id="submit_hover_btn_img_div" <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9'){ ?> class="iframe_submit_hover_original_btn" data-id="arfsbhis" style="margin-left:5px; position: relative; overflow: hidden; float:left; cursor:pointer; max-width:130px; height:22px; background: #1BBAE1; font-weight:bold; <?php if($newarr['submit_hover_bg_img'] == '') { ?> background:#1BBAE1;padding:7px 10px 0 10px;font-size:13px;border-radius:3px;color:#FFFFFF;border:1px solid #CCCCCC;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.4); <?php } ?>" <?php }else { ?> style="margin-left:0px;" <?php } ?>>
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' && $newarr['submit_hover_bg_img'] == ''){ ?> <span style="display:inline-block;color:#FFFFFF;text-align:center;"><?php _e('Upload', 'ARForms');?></span> <?php } ?>
                                    <?php
                                    if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ 
										if( $newarr['submit_hover_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['submit_hover_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_submit_hover_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="<?php echo esc_attr($newarr['submit_hover_bg_img']) ?>" id="arfsubmithoverbuttonimagesetting" />
                                        <?php } else { ?>
                                        <input type="text" class="original" name="submit_hover_btn_img" id="field_arfsbhis" form-id="" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; width: 160px; height: 59px; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        <input type="hidden" id="type_arfsbhis" name="type_arfsbhis" value="1" >
										<input type="hidden" value="jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico" id="file_types_arfsbhis" name="field_types_arfsbhis" />
                                        
                                        <input type="hidden" name="imagename_submit_hover" id="imagename_submit_hover" value="" />
                                        <input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="" id="arfsubmithoverbuttonimagesetting" />
                                        <?php }
										echo '<div id="arfsbhis_iframe_div"><iframe style="display:none;" id="arfsbhis_iframe" src="'.ARFURL.'/core/views/iframe.php" ></iframe></div>';
                                    }else { 
                                    	if( $newarr['submit_hover_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['submit_hover_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_submit_hover_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="<?php echo esc_attr($newarr['submit_hover_bg_img']) ?>" id="arfsubmithoverbuttonimagesetting" />
                                        <?php } else { ?>
                                        <div class="arfajaxfileupload" style="position: relative; overflow: hidden; float:left; cursor: pointer;">
                                        	<div class="file-upload-img"></div>
                                            <?php _e('Upload', 'ARForms');?>
                                            <input type="file" name="submit_hover_btn_img" data-val="submit_hover_bg" id="submit_hover_btn_img" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        </div>
                                        
                                        <input type="hidden" name="imagename_submit_hover" id="imagename_submit_hover" value="" />
                                        <input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="" id="arfsubmithoverbuttonimagesetting" />
                                         &nbsp;&nbsp;<span id="ajax_submit_hover_loader" style="display:none; float: left; margin: 5px 0 0;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/wpspin_light.gif"></span>
										<?php }
                                    } ?>
                                    
                                </div>
                                
            
                            </div>
            
                            
                            
                            
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$submit_border_setting_lbl = 'float:right;text-align:right;width:100%;';
									}
									else
									{
										$submit_border_setting_lbl = 'width:100%;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_border_setting_lbl; ?>"><?php _e('Border Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
            
                            
            
                            <div class="field-group clearfix subfield" style="margin-top:30px; margin-bottom:5px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Size', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" name="arfsbbws" id="arfsubmitbuttonborderwidhtsetting" style="width:142px;" value="<?php echo esc_attr($newarr['arfsubmitborderwidthsetting']) ?>" class="txtxbox_widget" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arfsubmitbuttonborderwidhtsetting_exs" class="arf_slider" data-slider-id='arfsubmitbuttonborderwidhtsetting_exsSlider' style="width:147px; margin-left:1px;" type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['arfsubmitborderwidthsetting']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style="float:left; margin-left:40px;"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style="float:right; display:inline;"><?php _e('20 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfsbbws" id="arfsubmitbuttonborderwidhtsetting" style="width:100px;" value="<?php echo esc_attr($newarr['arfsubmitborderwidthsetting']) ?>" class="txtxbox_widget" size="4" />
                                
                                <?php } ?>
                                
                            </div>
            
                            <div class="field-group clearfix subfield" style="margin-top:30px; margin-bottom:5px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Radius', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" value="<?php echo esc_attr($newarr['arfsubmitborderradiussetting']) ?>" name="arfsbbrs" id="arfsubmitbuttonborderradiussetting" class="txtxbox_widget" size="4" style="width:142px;" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
            					<input id="arfsubmitbuttonborderradiussetting_exs" class="arf_slider" data-slider-id='arfsubmitbuttonborderradiussetting_exsSlider' style="width:147px; margin-left:1px;" type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['arfsubmitborderradiussetting']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style="float:left; margin-left:40px;"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style="float:right; display:inline;"><?php _e('50 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" value="<?php echo esc_attr($newarr['arfsubmitborderradiussetting']) ?>" name="arfsbbrs" id="arfsubmitbuttonborderradiussetting" class="txtxbox_widget" size="4" style="width:100px;" />
                                
                                <?php } ?>
           
                            </div>
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$submit_border_color_lbl = 'float:right;text-align:right;width:100%;';
										$submit_border_line_main = 'clear:none;float:right;width:100%;';
										$submit_border_line_lbl = 'float:right;padding-right:10px;padding-left:12px;';
										$submit_border_line_cls = 'arf_float_left';
										$submit_border_line_css = 'float:right;';
									}
									else
									{
										$submit_border_color_lbl = 'width:100%;';
										$submit_border_line_main = 'clear:none;float:left;width:100%;';
										$submit_border_line_lbl = 'float:left;padding-left:10px;padding-right:12px;';
										$submit_border_line_cls = 'arf_float_right';
										$submit_border_line_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_border_color_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $submit_border_line_main; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $submit_border_line_lbl; ?>"><?php _e('Line', 'ARForms') ?></label>
            
            					<div class="<?php echo $submit_border_line_cls; ?>" style=" <?php echo $submit_border_line_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttonbordercolorsetting" style="background:<?php echo esc_attr($newarr['arfsubmitbordercolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfsbobcs" id="arfsubmitbuttonbordercolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsubmitbordercolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            <?php
								if(is_rtl())
								{
									$submit_border_shadow_main = 'float:left;clear:left;width:100%;';
									$submit_border_shadow_cls = 'arf_float_right';
									$submit_border_shadow_css = 'float:right;';
								}
								else
								{
									$submit_border_shadow_main = 'float:left;clear:right;width:100%;';
									$submit_border_shadow_cls = 'arf_float_left';
									$submit_border_shadow_css = 'float:left;';
								}
							?>
                            <div class="field-group clearfix subfield" style=" margin-top:11px; <?php echo $submit_border_shadow_main; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $submit_border_line_lbl;?>"><?php _e('Shadow', 'ARForms') ?></label>
            
            					<div class=" <?php echo $submit_border_shadow_cls; ?>" style=" <?php echo $submit_border_shadow_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttonshadowcolorsetting" style="background:<?php echo esc_attr($newarr['arfsubmitshadowcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfsbscs" id="arfsubmitbuttonshadowcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsubmitshadowcolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
            				
                            <div style="clear:both; height:1px;">&nbsp;</div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                             
                            <div class="field-group field-group-border clearfix " style="margin-top:10px">
            
            					<?php
									if(is_rtl())
									{
										$margin_lbl = 'float:right;text-align:right;width:50px;';
										$margin_txt_main = 'float:left;margin-left:-10px;';
										$margin_top = $margin_bottom = $margin_right = 'float:right;margin-right:8px';
										$margin_left = 'float:right;margin-right:0px;';
										$margin_top_lbl = $margin_bottom_lbl = $margin_left_lbl = $margin_right_lbl = 'float:left;margin-left:0px';
									}
									else
									{
										$margin_lbl = 'width:50px;';
										$margin_txt_main = 'float:right;margin-right:-28px;';
										$margin_top = $margin_bottom = $margin_right =  'float:left;margin-left:8px';
										$margin_left = 'float:left;margin-left:8px;';
										$margin_top_lbl = $margin_bottom_lbl = $margin_left_lbl = $margin_right_lbl = 'margin-left:8px';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $margin_lbl; ?>"><?php _e('Margin', 'ARForms') ?></label>
            
            					<div style="float:right; margin-right:-28px;">
            						<div style=" <?php echo $margin_top; ?>"><input type="text" name="arfsubmitbuttonmarginsetting_1" id="arfsubmitbuttonmarginsetting_1" onchange="arf_change_field_padding('arfsubmitbuttonmarginsetting');" value="<?php echo esc_attr($newarr['arfsubmitbuttonmarginsetting_1']); ?>" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:35px;" /><br /><span class="arf_px" style=" <?php echo $margin_top_lbl; ?>"><?php _e('Top', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $margin_right; ?>"><input type="text" name="arfsubmitbuttonmarginsetting_2" id="arfsubmitbuttonmarginsetting_2" value="<?php echo esc_attr($newarr['arfsubmitbuttonmarginsetting_2']); ?>" onchange="arf_change_field_padding('arfsubmitbuttonmarginsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:35px;" /><br /><span class="arf_px" style=" <?php echo $margin_right_lbl; ?>"><?php _e('Right', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $margin_bottom; ?>"><input type="text" name="arfsubmitbuttonmarginsetting_3" id="arfsubmitbuttonmarginsetting_3" value="<?php echo esc_attr($newarr['arfsubmitbuttonmarginsetting_3']); ?>" onchange="arf_change_field_padding('arfsubmitbuttonmarginsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:35px; margin-left:3px;" /><br /><span class="arf_px" style=" <?php echo $margin_bottom_lbl; ?>"><?php _e('Bottom', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $margin_left; ?>"><input type="text" name="arfsubmitbuttonmarginsetting_4" id="arfsubmitbuttonmarginsetting_4" value="<?php echo esc_attr($newarr['arfsubmitbuttonmarginsetting_4']); ?>" onchange="arf_change_field_padding('arfsubmitbuttonmarginsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:35px;" /><br /><span class="arf_px" style=" <?php echo $margin_left_lbl; ?>"><?php _e('Left', 'ARForms');?></span></div>&nbsp;
                                &nbsp;<span class="arf_px" style="float:left; padding-top:5px; margin-left:6px;"><?php _e('px', 'ARForms') ?></span>
                                </div>
                                <?php 
								$arfsubmitbuttonmarginsetting_value = '';
								
								if( esc_attr($newarr['arfsubmitbuttonmarginsetting_1']) != '' ){
									$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_1'].'px ';
								}else{
									$arfsubmitbuttonmarginsetting_value .= '0px ';
								}
								if( esc_attr($newarr['arfsubmitbuttonmarginsetting_2']) != '' ){
									$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_2'].'px ';
								}else{
									$arfsubmitbuttonmarginsetting_value .= '0px ';
								}					
								if( esc_attr($newarr['arfsubmitbuttonmarginsetting_3']) != '' ){
									$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_3'].'px ';
								}else{
									$arfsubmitbuttonmarginsetting_value .= '0px ';
								}
								if( esc_attr($newarr['arfsubmitbuttonmarginsetting_4']) != '' ){
									$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_4'].'px';
								}else{
									$arfsubmitbuttonmarginsetting_value .= '0px';	
								}
								?>	
                                <input type="hidden" name="arfsbms" id="arfsubmitbuttonmarginsetting" style="width:100px;" class="txtxbox_widget" value="<?php echo $arfsubmitbuttonmarginsetting_value; ?>" size="6" />
            
                            </div>
            
            
                            
                            
            
            				
            				
                            
                      
                            <div class="clear" style="height:15px;">&nbsp;</div>
            
            
                        </div>
            
            
                    </div>
            
            
                        
            
            
                    <div id="taberrormessagesettings" class="widget clearfix global-font">
            
            
                        <div class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Validation Style Settings', 'ARForms') ?></h4></div>
            
            
                        </div>
            			<?php
							if(is_rtl())
							{
								$validation_style_wrapper_cls = 'arf_style_validation_err';
								$validation_font_setting_lbl = 'float:right;text-align:right;width:100%;';
								$validation_font_popup_close = 'float:left;';
								$validation_font_popup_cls_btn = 'margin-top:-12px;margin-left:3px;';
								$validation_font_popup_cls_lbl = 'margin-right:26px;';
							}
							else
							{
								$validation_style_wrapper_cls = '';
								$validation_font_setting_lbl = 'width:100%;';
								$validation_font_popup_close = 'float:right;';
								$validation_font_popup_cls_btn = 'margin-top:-12px;margin-right:-3px;';
								$validation_font_popup_cls_lbl = 'margin-left:26px;';
							}
						?>
            
                        <div class="widget-inside <?php echo $validation_style_wrapper_cls; ?>" style="border-bottom: 1px solid #CACACA !important;">
            
            
                            <div class="field-group field-group-border clearfix">
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $validation_font_setting_lbl; ?>"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style=" <?php echo $validation_font_popup_cls_lbl;?>">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showerrorfontsettingpopup" onclick="arfshowformsettingpopup('errorfontsettingpopup')"><?php echo $newarr['error_font'].", ".$newarr['arffontsizesetting']."px ";?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('errorfontsettingpopup')" /></div>
                                    <div id="errorfontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style=" <?php echo $validation_font_popup_close; ?>">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('errorfontsettingpopup')" type="button" style=" <?php echo $validation_font_popup_cls_btn; ?>">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            
            									<?php
													if(is_rtl())
													{
														$validation_font_family_lbl = 'float:right;width:50px;padding-right:10px;padding-top:7px;height:20px;';
														$validation_font_family_opt = 'float:left;margin-right:70px;margin-bottom:10px;position:absolute;';
													}
													else
													{
														$validation_font_family_lbl = 'float:left;width:50px;padding-left:10px;padding-top:7px;height:20px;';
														$validation_font_family_opt = 'float:right;margin-left:70px;margin-bottom:10px;position:absolute;';
													}
												?>
                                                <div class="lblsubheading" style=" <?php echo $validation_font_family_lbl; ?>"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <div class="sltstandard2" style=" <?php echo $validation_font_family_opt; ?>">
                                                <?php /*?><select name="arfmefs" id="arfmainerrorfontsetting" style="width:200px;" data-width='200px' data-size='15' onchange="Changefontsettinghtml('errorfontsettingpopup','arfmainerrorfontsetting','','arfmainerrorfontsizesetting');">
                                                    <optgroup label="Default Fonts">
                                                        <option value="Arial" <?php selected($newarr['error_font'], 'Arial') ?>>Arial</option>
                            
                                                        <option value="Helvetica" <?php selected($newarr['error_font'], 'Helvetica') ?>>Helvetica</option>
                                                        
                                                        <option value="sans-serif" <?php selected($newarr['error_font'], 'sans-serif') ?>>sans-serif</option>
                                                        
                                                        <option value="Lucida Grande" <?php selected($newarr['error_font'], 'Lucida Grande') ?>>Lucida Grande</option>
                                                        
                                                        <option value="Lucida Sans Unicode" <?php selected($newarr['error_font'], 'Lucida Sans Unicode') ?>>Lucida Sans Unicode</option>
                                                        
                                                        <option value="Tahoma" <?php selected($newarr['error_font'], 'Tahoma') ?>>Tahoma</option>
                                                        
                                                        <option value="Times New Roman" <?php selected($newarr['error_font'], 'Times New Roman') ?>>Times New Roman</option>
                                                        
                                                        <option value="Courier New" <?php selected($newarr['error_font'], 'Courier New') ?>>Courier New</option>
                                                        
                                                        <option value="Verdana" <?php selected($newarr['error_font'], 'Verdana') ?>>Verdana</option>
                                                        
                                                        <option value="Geneva" <?php selected($newarr['error_font'], 'Geneva') ?>>Geneva</option>
                                                                
                                                        <option value="Courier" <?php selected($newarr['error_font'], 'Courier') ?>>Courier</option>
                                                                
                                                        <option value="Monospace" <?php selected($newarr['error_font'], 'Monospace') ?>>Monospace</option>
                                                                
                                                        <option value="Times" <?php selected($newarr['error_font'], 'Times') ?>>Times</option>
                                                    </optgroup>
                                                    <optgroup label="Google Fonts">
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<option value='".$goglefontsfamily."' ".selected($newarr['error_font'], $goglefontsfamily)." >".$goglefontsfamily."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </optgroup>
                                                </select><?php */?>
                                                
                                                <input id="arfmainerrorfontsetting" name="arfmefs" value="<?php echo $newarr['error_font'];?>" type="hidden" onchange="Changefontsettinghtml('errorfontsettingpopup','arfmainerrorfontsetting','','arfmainerrorfontsizesetting');">
                                                <dl class="arf_selectbox" data-name="arfmefs" data-id="arfmainerrorfontsetting" style="width:180px;">
                                                  <dt><span><?php echo $newarr['error_font'];?></span>
                                                    <input value="<?php echo $newarr['error_font'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                  <dd>
                                                    <ul style="display: none;" data-id="arfmainerrorfontsetting">
                                                      <ol class="arp_selectbox_group_label">
                                                        Default Fonts
                                                      </ol>
                                                      <li class="arf_selectbox_option" data-value="Arial" data-label="Arial">Arial</li>
                                                      <li class="arf_selectbox_option" data-value="Helvetica" data-label="Helvetica">Helvetica</li>
                                                      <li class="arf_selectbox_option" data-value="sans-serif" data-label="sans-serif">sans-serif</li>
                                                      <li class="arf_selectbox_option" data-value="Lucida Grande" data-label="Lucida Grande">Lucida Grande</li>
                                                      <li class="arf_selectbox_option" data-value="Lucida Sans Unicode" data-label="Lucida Sans Unicode">Lucida Sans Unicode</li>
                                                      <li class="arf_selectbox_option" data-value="Tahoma" data-label="Tahoma">Tahoma</li>
                                                      <li class="arf_selectbox_option" data-value="Times New Roman" data-label="Times New Roman">Times New Roman</li>
                                                      <li class="arf_selectbox_option" data-value="Courier New" data-label="Courier New">Courier New</li>
                                                      <li class="arf_selectbox_option" data-value="Verdana" data-label="Verdana">Verdana</li>
                                                      <li class="arf_selectbox_option" data-value="Geneva" data-label="Geneva">Geneva</li>
                                                      <li class="arf_selectbox_option" data-value="Courier" data-label="Courier">Courier</li>
                                                      <li class="arf_selectbox_option" data-value="Monospace" data-label="Monospace">Monospace</li>
                                                      <li class="arf_selectbox_option" data-value="Times" data-label="Times">Times</li>
                                                      <ol class="arp_selectbox_group_label">
                                                        Google Fonts
                                                      </ol>
                                                      <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                    </ul>
                                                  </dd>
                                                </dl>

                                                
                                                </div>
                                            </div>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
													if(is_rtl())
													{
														$validation_font_size_lbl = 'padding-right:10px; padding-top:7px; height:20px; float:right;';
														$validation_font_size_opt = 'float:left;position:absolute;margin-right:70px;';
														$validation_font_size_px = 'float:left;margin-left:20px;padding-top:5px;';
													}
													else
													{
														$validation_font_size_lbl = 'padding-left:10px; padding-top:7px; height:20px; float:left;';
														$validation_font_size_opt = 'float:left;position:absolute;';
														$validation_font_size_px = 'float:right; margin-right: 90px; padding-top:5px;';
													}
												?>
            									<div class="lblsubheading" style=" <?php echo $validation_font_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></div>
                                                <div style="margin-left:70px; margin-bottom:10px;">
                                                    <div class="sltstandard1" style=" <?php echo $validation_font_size_opt; ?>">
                                                    
                                                    <?php /*?><select name="arfmefss" id="arfmainerrorfontsizesetting" style="width:100px;" data-width='100px' data-size='10' onchange="Changefontsettinghtml('errorfontsettingpopup','arfmainerrorfontsetting','','arfmainerrorfontsizesetting');">	
														   <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['arffontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['arffontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['arffontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                    </select><?php */?>
                                                    
                                                    <input id="arfmainerrorfontsizesetting" name="arfmefss" value="<?php echo $newarr['arffontsizesetting'];?>" type="hidden" onchange="Changefontsettinghtml('errorfontsettingpopup','arfmainerrorfontsetting','','arfmainerrorfontsizesetting');">
                                                    <dl class="arf_selectbox" data-name="arfmefss" data-id="arfmainerrorfontsizesetting" style="width:80px;">
                                                      <dt><span><?php echo $newarr['arffontsizesetting'];?></span>
                                                        <input value="<?php echo $newarr['arffontsizesetting'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                      <dd>
                                                        <ul style="display: none;" data-id="arfmainerrorfontsizesetting">
                                                          <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                          <?php } ?>
                                                          <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                          <?php } ?>
                                                          <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                          <?php } ?>
                                                        </ul>
                                                      </dd>
                                                    </dl>

                                                    
                                                    </div>
                                                    <div class="arf_px" style=" <?php echo $validation_font_size_px; ?>"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                           <div style="display:none;">
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div>
                            
                            <div class="field-group field-group-border clearfix">
            
                                <label class="lblsubheading" style="width:100%"><?php _e('Error Settings', 'ARForms') ?></label> <br />
            
                            </div>
                                            
            
                            <div class="field-group field-group-border clearfix subfield">
            
            
                                <label class="lblsubheading" style="width:90px"><?php _e('BG Color', 'ARForms') ?></label>
            
            
                                <div class="hasPicker">
            
            					<div class="arf_float_right" style="margin-right:17px;">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainerrorbgsetting" style="background:<?php echo esc_attr($newarr['arferrorbgsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfmebs" id="arfmainerrorbgsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrorbgsetting']) ?>" style="width:100px;" /></div>
            					</div>	
            
                            </div>
            
            
            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            
                                <label class="lblsubheading" style="width:90px"><?php _e('Text Color', 'ARForms') ?></label>
            
            					<div class="arf_float_right" style="margin-right:17px;">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainerrortextsetting" style="background:<?php echo esc_attr($newarr['arferrortextsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfmets" id="arfmainerrortextsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrortextsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            
                                <label class="lblsubheading" style="width:90px"><?php _e('Border Color', 'ARForms') ?></label>
            
            					<div class="arf_float_right" style="margin-right:17px;">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainerrotbordersetting" style="background:<?php echo esc_attr($newarr['arferrorbordersetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfmebos" id="arfmainerrotbordersetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrorbordersetting']) ?>" style="width:100px;" />
            					</div>	
            
                            </div>
            			   </div>
            				
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div>
                            
                            <div class="field-group field-group-border clearfix">
                                <?php
								if(is_rtl())
								{
									$validation_err_setting_lbl = 'float:right;text-align:right;width:200px;';
									$validation_err_type_btns = 'float:right;margin-left:0px;';
									$validation_err_tooltip_col = 'float:left;margin-left:0px;';
									$validation_err_tooltip_bg_lbl = 'float:right;';
								}
								else
								{
									$validation_err_setting_lbl = 'width:200px;';
									$validation_err_type_btns = 'float:left;';
									$validation_err_tooltip_col = 'float:left;';
									$validation_err_tooltip_bg_lbl = '';
								}
							?> 
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $validation_err_setting_lbl; ?>"><?php _e('Validation error settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px;">
                                
                                <label class="lblsubheading sublblheading"><?php _e('Type', 'ARForms') ?></label>
                                <div style=" <?php echo $validation_err_type_btns; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" class="toggle-btn normal <?php $newarr['arferrorstyle'] = isset($newarr['arferrorstyle']) ? $newarr['arferrorstyle'] : 'normal'; if($newarr['arferrorstyle']=="advance"){ echo "success"; }?>"><input type="radio" name="arfest" class="visuallyhidden" id="arferrorstyle1" onchange="arf_change_error_type();" value="advance" <?php checked($newarr['arferrorstyle'], 'advance'); ?> /><?php _e('Advance', 'ARForms') ?></label><label onclick="" class="toggle-btn normal <?php if($newarr['arferrorstyle']=="normal"){ echo "success"; }?>"><input type="radio" name="arfest" onchange="arf_change_error_type();" class="visuallyhidden" value="normal"  id="arferrorstyle2" <?php checked($newarr['arferrorstyle'], 'normal'); ?> /><?php _e('Normal', 'ARForms') ?></label>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px;">                                
                                <label class="lblsubheading sublblheading" style=" <?php echo $validation_err_tooltip_bg_lbl; ?> "><?php _e('Background Color', 'ARForms') ?></label>
                                <div id="color_palate_advance" class="arf_float_right" style=" <?php if($newarr['arferrorstyle']!="advance"){ echo "display:none;";  }?>float:left;margin-left:59px;">
                                	<div class="toggle-btn-grp-color joint-toggle">
                                	<?php
                                    foreach ($arfadvanceerrcolor as $colorname => $color_value)
									{
										$explodecolor = explode("|",$color_value);
										$boxcolor = $explodecolor[0];
									?>
                                    	<label onclick="" class="toggle-btn-color <?php $newarr['arferrorstylecolor'] = isset($newarr['arferrorstylecolor']) ? $newarr['arferrorstylecolor'] : ''; if($newarr['arferrorstylecolor']==$color_value){ echo "success"; }?>"><input type="radio" name="arfestc" class="visuallyhidden" value="<?php echo $color_value;?>" <?php checked($newarr['arferrorstylecolor'], $color_value); ?> /><span style="background-color:<?php echo $boxcolor;?>; width:16px; height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
                                    <?php
									}
									?>
                                    </div>
                                </div>
                                
                                <div id="color_palate_normal" class="arf_float_right" style=" <?php if($newarr['arferrorstyle']!="normal"){ echo "display:none;";  }?>float:left;margin-left:59px;">
                                	<div class="toggle-btn-grp-color joint-toggle">
                                	<?php
									//unset($arfadvanceerrcolor['white']);
                                    foreach ($arfadvanceerrcolor as $colorname => $color_value)
									{
										$explodecolor = explode("|",$color_value);
										$boxcolor = $explodecolor[2];
									?>
                                    	<label onclick="" class="toggle-btn-color <?php $newarr['arferrorstylecolor'] = isset($newarr['arferrorstylecolor2']) ? $newarr['arferrorstylecolor2'] : ''; if($newarr['arferrorstylecolor2']==$color_value){ echo "success"; }?>"><input type="radio" name="arfestc2" class="visuallyhidden" value="<?php echo $color_value;?>" <?php checked($newarr['arferrorstylecolor2'], $color_value); ?> /><span style="background-color:<?php echo $boxcolor;?>; width:16px; height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
                                    <?php
									}
									?>
                                    </div>
                                </div>
                                
                            </div>
                            <?php
								if(is_rtl())
								{
									$err_validation_position_btns = 'float:right;margin-right:27px;';
								}
								else
								{
									$err_validation_position_btns = 'float:left;margin-left:27px;';
								}
							?>
                            <div class="field-group field-group-border clearfix subfield" id="showadvanceposition" style="margin-top:10px;<?php if($newarr['arferrorstyle']!="advance"){ echo "display:none;";  }?>">                               
                                <label class="lblsubheading sublblheading"><?php _e('Position', 'ARForms') ?></label>
                                <div class="arf_float_right" style=" <?php echo $err_validation_position_btns; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" style="width:55px !important; text-align:center; margin-left:0;" class="toggle-btn-pos toppos <?php $newarr['arferrorstyleposition'] = isset($newarr['arferrorstyleposition']) ? $newarr['arferrorstyleposition'] : 'right'; if($newarr['arferrorstyleposition']=="top"){ echo "success"; }?>"><input type="radio" name="arfestbc" class="visuallyhidden" id="arfestbc1" value="top" <?php checked($newarr['arferrorstyleposition'], 'top'); ?> onchange="arf_change_error_position()" /><?php _e('Top', 'ARForms') ?></label>
                                        <label onclick="" style="width:55px !important; text-align:center;" class="toggle-btn-pos bottompos <?php if($newarr['arferrorstyleposition']=="bottom"){ echo "success"; }?>"><input type="radio" name="arfestbc" class="visuallyhidden" value="bottom"  id="arfestbc2" <?php checked($newarr['arferrorstyleposition'], 'bottom'); ?> onchange="arf_change_error_position()" /><?php _e('Bottom', 'ARForms') ?></label>
                                        <label onclick="" style="width:55px !important; text-align:center;" class="toggle-btn-pos leftpos <?php if($newarr['arferrorstyleposition']=="left"){ echo "success"; }?>"><input type="radio" name="arfestbc" class="visuallyhidden" value="left"  id="arfestbc3" <?php checked($newarr['arferrorstyleposition'], 'left'); ?> onchange="arf_change_error_position()" /><?php _e('Left', 'ARForms') ?></label>
                                        <label onclick="" style="width:55px !important; text-align:center;" class="toggle-btn-pos rightpos <?php if($newarr['arferrorstyleposition']=="right"){ echo "success"; }?>"><input type="radio" name="arfestbc" class="visuallyhidden" value="right"  id="arfestbc4" <?php checked($newarr['arferrorstyleposition'], 'right'); ?> onchange="arf_change_error_position()" /><?php _e('Right', 'ARForms') ?></label>
                                    </div>
                                </div>                                
                            </div>
                      
            				<div class="widget_bg_bottom" style="padding-bottom:12px;"></div>
                            <?php
								if(is_rtl())
								{
									$success_msg_setting_lbl = 'float:right;text-align:right;width:100%;';
									$success_msg_bg_col_lbl = 'float:right;text-align:right;width:130px;margin-right:8px;';
									$success_msg_bg_col_cls = 'arf_float_right';
									$success_msg_bg_col_css = 'margin-right:17px;';
								}
								else
								{
									$success_msg_setting_lbl = 'width:100%;';
									$success_msg_bg_col_lbl = 'width:130px;margin-left:8px;';
									$success_msg_bg_col_cls = 'arf_float_left';
									$success_msg_bg_col_css = 'margin-left:17px;float:none;';
								}
							?>
                            <div class="field-group field-group-border clearfix">
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $success_msg_setting_lbl; ?>"><?php _e('Success Message Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
            
                            
                            <div class="field-group field-group-border clearfix subfield">
            
            
                                <label class="lblsubheading" style=" <?php echo $success_msg_bg_col_lbl; ?>"><?php _e('Background Color', 'ARForms') ?></label>
            
            
                                <div class="hasPicker">
            			
            					<div class=" <?php echo $success_msg_bg_col_cls; ?>" style=" <?php echo $success_msg_bg_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainsucessbgcolorsetting" style="background:<?php echo esc_attr($newarr['arfsucessbgcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input name="arfmsbcs" id="arfmainsucessbgcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsucessbgcolorsetting']) ?>" type="hidden" style="width:100px;" /></div>
            					</div>	
            
                            </div>
            
            				<?php
								if(is_rtl())
								{
									$success_msg_brd_col_lbl = 'float:right;text-align:right;width:130px;';
									$success_msg_brd_col_cls = 'arf_float_right';
									$success_msg_brd_col_css = 'margin-right:17px;';
								}
								else
								{
									$success_msg_brd_col_lbl = 'width:130px;';
									$success_msg_brd_col_cls = 'arf_float_left';
									$success_msg_brd_col_css = 'margin-left:17px;';
								}
							?>
                            <div class="field-group clearfix subfield" style="margin-top:11px; margin-left:8px;">
            
            
                                <label class="lblsubheading" style=" <?php echo $success_msg_brd_col_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label>
            
            					<div class="<?php echo $success_msg_brd_col_cls; ?>" style=" <?php echo $success_msg_brd_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainsucessbordercolorsetting" style="background:<?php echo esc_attr($newarr['arfsucessbordercolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfmsbocs" id="arfmainsucessbordercolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsucessbordercolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
            
            
                            <div class="field-group clearfix subfield" style="margin-top:11px; margin-left:8px;">
            
            					<?php
									if(is_rtl())
									{
										$success_msg_txt_col_lbl = 'float:right;text-align:right;width:130px;';
										$success_msg_txt_col_cls = 'arf_float_right';
										$success_msg_txt_col_css = 'margin-right:17px;';
									}
									else
									{
										$success_msg_txt_col_lbl = 'width:130px;';
										$success_msg_txt_col_cls = 'arf_float_left';
										$success_msg_txt_col_css = 'margin-left:17px;';
									}
								?>
                                <label class="lblsubheading" style=" <?php echo $success_msg_txt_col_lbl; ?>"><?php _e('Text Color', 'ARForms') ?></label>
            					
                                <div class="<?php echo $success_msg_txt_col_cls; ?>" style=" <?php echo $success_msg_txt_col_css; ?>">
     								
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainsucesstextcolorsetting" style="background:<?php echo esc_attr($newarr['arfsucesstextcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>	
                                	<input name="arfmstcs" id="arfmainsucesstextcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsucesstextcolorsetting']) ?>" type="hidden" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            <div class="clear" style="height:10px;"></div>
                            
                            
                            
                        </div>
            
            
                    </div>
                    <div class="buttons-row" style="padding-top:10px; margin-bottom:5px; margin-left:20px; width:300px;">
        		
		                    
                    	<?php 
						global $arfform;
						if($is_ref_form == 1){
							$form = $arfform->getAll(array('id' => $id), '', 1,1);
						}else{
							$form = $arfform->getAll(array('id' => $id), '', 1);
						}
						$pre_link = $arformhelper->get_direct_link($form->form_key);
						$width = @$_COOKIE['width'] * 0.80;
						$width_new = '&width='.$width;
						?>
                       
                    <button class="blueresetbtn" style="" type="button" onclick="reset_global_styling_settings();" ><img src="<?php echo ARFIMAGESURL.'/reset-icon.png'; ?>" align="absmiddle" />&nbsp;&nbsp;<?php _e('Reset', 'ARForms') ?>&nbsp;&nbsp;</button>	

				</div>
                </fieldset>
        	</div>
            </div>
        </li>
        </ul>
    	</div>
	
    </div>
    
    

    
    </div>

    	
    </div>

    
</div>





<style type="text/css">


#arfshowcalimage{


vertical-align:bottom; height:27px; width:30px;


background:url(<?php echo ARFIMAGESURL ?>/themeGallery.png) no-repeat; 


display:inline-block;

float:left;

margin-left:10px;


}


#arfshowcalimage.theme_black-tie{ background-position: 0 0; } 


#arfshowcalimage.theme_blitzer{ background-position: 0 -28px; } 


#arfshowcalimage.theme_cupertino{ background-position: 0 -56px; } 


#arfshowcalimage.theme_dark-hive{ background-position: 0 -84px; } 


#arfshowcalimage.theme_dot-luv{ background-position: 0 -112px; } 


#arfshowcalimage.theme_eggplant{ background-position: 0 -140px; } 


#arfshowcalimage.theme_excite-bike{ background-position: 0 -168px; } 


#arfshowcalimage.theme_flick{ background-position: 0 -196px; } 


#arfshowcalimage.theme_hot-sneaks{ background-position: 0 -224px; } 


#arfshowcalimage.theme_humanity{ background-position: 0 -252px; } 


#arfshowcalimage.theme_le-frog{ background-position: 0 -280px; } 


#arfshowcalimage.theme_mint-choc{ background-position: 0 -308px; } 


#arfshowcalimage.theme_overcast{ background-position: 0 -336px; } 


#arfshowcalimage.theme_pepper-grinder{ background-position: 0 -364px; } 


#arfshowcalimage.theme_redmond{ background-position: 0 -392px; } 


#arfshowcalimage.theme_smoothness{ background-position: 0 -420px; } 


#arfshowcalimage.theme_south-street{ background-position: 0 -448px; } 


#arfshowcalimage.theme_start{ background-position: 0 -476px; } 


#arfshowcalimage.theme_sunny{ background-position: 0 -504px; } 


#arfshowcalimage.theme_swanky-purse{ background-position: 0 -532px; } 


#arfshowcalimage.theme_trontastic{ background-position: 0 -560px; } 


#arfshowcalimage.theme_ui-darkness{ background-position: 0 -588px; } 


#arfshowcalimage.theme_ui-lightness{ background-position: 0 -616px; } 


#arfshowcalimage.theme_vader{ background-position: 0 -644px; } 


</style>

<script type="text/javascript">
var temp_css_preview_iframeurl = "<?php echo $pre_link;?>";

//<![CDATA[


jQuery(document).ready(function($)
{ 
	
	arfupdateformpreviewcss('<?php echo $armainhelper->jquery_css_url($newarr['arfcalthemecss']) ?>');
	
});

function showorhidetitle()
{
	if(document.getElementById("display_title_form").checked==false)
	{
		jQuery('#testiframe').contents().find('.formtitle_style').attr("style","display:none");
		jQuery('#testiframe').contents().find('.arftitlecontainer').attr("style","display:none");    
	}
}

function CallApplyClick()
{ 
	closeslide_hide_fn();
}

function CallPreview()
{
	jQuery("#doslide_show").click();
}

function updateCSS(locStr){
	jQuery('#testiframe').contents().find("body").append('<link href="<?php echo site_url() . (is_admin() ? '/wp-admin' : '') .'/index.php?plugin=ARForms'; ?>&amp;controller=settingspreview&amp;'+ locStr +'" type="text/css" rel="Stylesheet" class="frm-custom-theme"/>');

	if( jQuery('#testiframe').contents().find("link.frm-custom-theme").size() > 3){

		jQuery('#testiframe').contents().find("link.frm-custom-theme:first").remove();

		if( typeof arf_set_on_chnage_update_css_in_out_site_on_preview == 'function' ){
			arf_set_on_chnage_update_css_in_out_site_on_preview(locStr);
		}
	}


};

function frmSetPosClass(value){


if(value=='none') value='none';

if( jQuery('#arfhidelabels').val() == '1' || jQuery('#arfhidelabels').is(':checked') ){
	value = 'none';
}
if( value == 'top' ){
	jQuery('#frm_align').attr("checked", true).trigger('change');
}
jQuery('#testiframe').contents().find('.arfformfield').removeClass('top_container none_container left_container right_container').addClass(value+'_container');   

jQuery('#testiframe').contents().find('.arf_heading_div h2').removeClass('pos_top pos_none pos_left pos_right').addClass('pos_'+value);

jQuery('#testiframe').contents().find('.arf_submit_div').removeClass('top_container none_container left_container right_container').addClass(value+'_container'); 

}

function change_form_title(){

	if( jQuery('#display_title_form').is(':checked') ) {
		jQuery('#display_title_form').val('1');
		jQuery('#form_title_style_div').show();
		//jQuery("#post-body-content").css('height','1710px');
	} else {
		jQuery('#display_title_form').val('0');
		jQuery('#form_title_style_div').hide();
		//jQuery("#post-body-content").css('height','1510px');
	}
	
	if(document.getElementById("display_title_form").value=='0') 
	{
		var value='none';
	}
	else
	{
		var value = 'block';
	}

	jQuery('#testiframe').contents().find('.formtitle_style').attr("style","display:"+value); 
	jQuery('#testiframe').contents().find('.formdescription_style').attr("style","display:"+value);
	jQuery('#testiframe').contents().find('.arftitlecontainer').attr("style","display:"+value);    	

}

function frmSetPosClassHide(){

	if( jQuery('#arfhidelabels').is(':checked') ) 
	{
		value='none';
		jQuery('#arfhidelabels').val('1');
	}
	else
	{
		jQuery('#arfhidelabels').val('0');
		value = jQuery("input[name=arfmps]:radio:checked").val();
	}
	
	jQuery('#testiframe').contents().find('.arfformfield').removeClass('top_container none_container left_container right_container').addClass(value+'_container');  
	
	jQuery('#testiframe').contents().find('.arf_heading_div h2').removeClass('pos_top pos_none pos_left pos_right').addClass('pos_'+value);
}
//]]>
</script>



<?php
if(version_compare( $GLOBALS['wp_version'], '3.7', '<'))
{
	wp_register_script('filedrag-js',ARFURL.'/js/filedrag/filedrag_lower.js');
	$armainhelper->load_scripts(array('filedrag-js'));
}
else
{
	wp_register_script('filedrag-js',ARFURL.'/js/filedrag/filedrag.js');
	$armainhelper->load_scripts(array('filedrag-js'));
}
?>

<script type="text/javascript" language="javascript">
<?php
$wp_upload_dir 	= wp_upload_dir();
if(is_ssl())
{
	$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/');
}
else
{
	$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/';
}
?>
function change_submit_img(){
	var upload_css_url = '<?php echo $upload_css_url; ?>';	
	var img = jQuery('#imagename').val();
	var image = upload_css_url + img;
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_submit_bg&image="+image ,

	success:function(msg){ jQuery('#submit_btn_img_div').html(msg); formChange1(); }	
			
	});
	
}

function change_form_bg_img(){

	var upload_css_url = '<?php echo $upload_css_url; ?>';	
	var img = jQuery('#imagename_form').val();
	var image = upload_css_url + img;
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_form_bg_img&image="+image ,

	success:function(msg){ jQuery('#form_bg_img_div').html(msg); formChange1(); }	
			
	});
	
}

function change_submit_hover_img(){

	var upload_css_url = '<?php echo $upload_css_url; ?>';	
	var img = jQuery('#imagename_submit_hover').val();
	var image = upload_css_url + img;
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_submit_hover_bg&image="+image ,
		
		success:function(msg){ jQuery('#submit_hover_btn_img_div').html(msg); formChange1(); }	
		
	});
}

</script>


<script type="application/javascript" language="javascript">
setTimeout(function(){ 
 //var switchery = new Array();
 if (Array.prototype.forEach) {
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

	elems.forEach(function(html) {
	  if(html.getAttribute("data-switchery")!="true")
	  {	
	  	 switchery[html.id] = new Switchery(html);
	  }	
	});
  } else {
	var elems = document.querySelectorAll('.js-switch');

	for (var i = 0; i < elems.length; i++) {
	  if(elems[i].getAttribute("data-switchery")!="true")
	  {
	  	switchery[elems[i].id] = new Switchery(elems[i]);
	  }	
	}
  }
if (window.PIE) {
var wrapper = document.querySelectorAll('.switchery')
  , handle = document.querySelectorAll('.switchery > small');

if (wrapper.length == handle.length) {
  for (var i = 0; i < wrapper.length; i++) {
	PIE.attach(wrapper[i]);
	PIE.attach(handle[i]);
  }
}
}
},10);
function change_auto_width(){

	if( jQuery('#arfautowidthsetting').is(':checked') ) {	
		
		jQuery('#sltstandard_front select').css({"width": "auto"});
		jQuery('#sltstandard_front select').val('').trigger("liszt:updated");
	
	} else {
		var width = 0;
		width = jQuery('#arfmainfieldwidthsetting').val();
		width = +width + +parseInt(2);
		jQuery('#drop_down_example_chzn').css({"width": width+"px"});
		
		width = jQuery('#arfmainfieldwidthsetting').val();		
		jQuery('#drop_down_example_chzn .chzn-drop').css({"width": width+"px"});
	
	}
	
	width = jQuery('#arfmainfieldwidthsetting').val();
}
jQuery(document).ready(function(){

jQuery("span[name=arfmfo]").click(function(){
	if(jQuery("input[name=arfmfo]").is(':checked'))
	{
		jQuery("input[name=arfmfo]:checkbox").val('1').trigger("change");
	}
	else
	{
		jQuery("input[name=arfmfo]:checkbox").val('0').trigger("change");
	}	
});

/*jQuery("span[name=arfmft]").click(function(){
	if(jQuery("input[name=arfmft]").is(':checked'))
	{
		jQuery("input[name=arfmft]:checkbox").val('1').trigger("change");
	}
	else
	{
		jQuery("input[name=arfmft]:checkbox").val('0').trigger("change");
	}	
})*/

/*jQuery(".toggle-btn:not('.noscript') input[type=radio], .toggle-btn-large:not('.noscript') input[type=radio], .toggle-btn-color:not('.noscript') input[type=radio], .toggle-btn-pos:not('.noscript') input[type=radio]").change(function() {
    if( jQuery(this).attr("name") ) {
        jQuery(this).parent().addClass("success").siblings().removeClass("success");
    } else {
        jQuery(this).parent().toggleClass("success");
    }
});*/

jQuery(".toggle-btn input[type=radio]").change(function() {
    if( jQuery(this).attr("name") ) {
        jQuery(this).parent().addClass("success").siblings().removeClass("success");
    } else {
        jQuery(this).parent().toggleClass("success");
    }
});

jQuery(".toggle-btn-large input[type=radio]").change(function(){
	if( jQuery(this).attr('name') ){
		jQuery(this).parent().addClass("success").siblings().removeClass("success");
	} else {
		jQuery(this).parent().toggleClass("success");
	}
});

jQuery(".toggle-btn-pos input[type=radio]").change(function(){
	if( jQuery(this).attr('name') ){
		jQuery(this).parent().addClass('success').siblings().removeClass("success");
	} else {
		jQuery(this).toggleClass('success');
	}
});

jQuery(".toggle-btn-color input[type=radio]").change(function(){
	if( jQuery(this).attr('name') ){
		jQuery(this).parent().addClass('success').siblings().removeClass("success");
	} else {
		jQuery(this).toggleClass('success');
	}
});
	
	<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ echo ''; } else { ?>
	
	jQuery('#arfmainfieldsetradius_exs').slider();
		
	jQuery('#arfmainfieldsetradius_exs').slider().on('slideStop', function(ev){
		
		var data = jQuery('#arfmainfieldsetradius_exs').slider('getValue');
				
		var id = jQuery(this).attr('id');
			id = id.replace('_exs','');
		
		jQuery('#'+id).val(data).trigger('change');
			
	});
	
	
	jQuery('#arfmainfieldset_exs').slider({ tooltip: 'always' });
			
	jQuery('#arfmainfieldset_exs').slider({ tooltip: 'always' }).on('slideStop', function(ev){
		
		var data = jQuery('#arfmainfieldset_exs').slider('getValue');
				
		var id = jQuery(this).attr('id');
			id = id.replace('_exs','');
		
		jQuery('#'+id).val(data).trigger('change');
			
	});
	
	
	jQuery('#arfmainform_opacity_exs').slider({
	          	formater: function(value) {
	            	if(value>0 && !isNaN(value))
					{	
						var value = ( value == 0 ) ? 0 : value/10;
						
						if( value < 1 && value != 0 ){
							value = value.toFixed(2);
						}
						//console.log("val=>"+value);		
						return value;
					}
					return 0;
	          	}
	        });	
	jQuery('#arfmainform_opacity_exs').slider().on('slideStop', function(ev){
		var data = jQuery('#arfmainform_opacity_exs').slider('getValue');				
		var id = jQuery(this).attr('id');
			id = id.replace('_exs','');		
		var val = ( data == 0 ) ? 0 : data / 10;
		
		if( val < 1 && val != 0 ){
			val = val.toFixed(2);
		}				
		jQuery('#'+id).val(val).trigger('change');			
	});
	
	
	jQuery('#arfmainbordersetting_exs').slider();
		
	jQuery('#arfmainbordersetting_exs').slider().on('slideStop', function(ev){
		
		var data = jQuery('#arfmainbordersetting_exs').slider('getValue');
				
		var id = jQuery(this).attr('id');
			id = id.replace('_exs','');
		
		jQuery('#'+id).val(data).trigger('change');
			
	});
	
	
	jQuery('#arffieldborderwidthsetting_exs').slider();
		
	jQuery('#arffieldborderwidthsetting_exs').slider().on('slideStop', function(ev){
		
		var data = jQuery('#arffieldborderwidthsetting_exs').slider('getValue');
				
		var id = jQuery(this).attr('id');
			id = id.replace('_exs','');
		
		jQuery('#'+id).val(data).trigger('change');
			
	});
	
	
	jQuery('#arfsubmitbuttonborderradiussetting_exs').slider();
		
	jQuery('#arfsubmitbuttonborderradiussetting_exs').slider().on('slideStop', function(ev){
		
		var data = jQuery('#arfsubmitbuttonborderradiussetting_exs').slider('getValue');
				
		var id = jQuery(this).attr('id');
			id = id.replace('_exs','');
		
		jQuery('#'+id).val(data).trigger('change');
			
	});
	
	
	jQuery('#arfsubmitbuttonborderwidhtsetting_exs').slider();
		
	jQuery('#arfsubmitbuttonborderwidhtsetting_exs').slider().on('slideStop', function(ev){
		
		var data = jQuery('#arfsubmitbuttonborderwidhtsetting_exs').slider('getValue');
				
		var id = jQuery(this).attr('id');
			id = id.replace('_exs','');
		
		jQuery('#'+id).val(data).trigger('change');
	});
	
	
	
	jQuery('#arffieldinnermarginssetting_1_exs').slider();
		
	jQuery('#arffieldinnermarginssetting_1_exs').slider().on('slideStop', function(ev){
		
		var data = jQuery('#arffieldinnermarginssetting_1_exs').slider('getValue');
				
		var id = jQuery(this).attr('id');
			id = id.replace('_exs','');		
		arf_change_field_spacing();	
		jQuery('#arffieldinnermarginsetting_1').val(data);
	});
	
	jQuery('#arffieldinnermarginssetting_2_exs').slider();
		
	jQuery('#arffieldinnermarginssetting_2_exs').slider().on('slideStop', function(ev){
		
		var data = jQuery('#arffieldinnermarginssetting_2_exs').slider('getValue');
				
		var id = jQuery(this).attr('id');
			id = id.replace('_exs','');
			
		arf_change_field_spacing();			
		jQuery('#arffieldinnermarginsetting_2').val(data);
	});
	
	<?php } ?>
	
	jQuery('.widget .widget-inside').not('.current_widget .widget-inside').hide();
	jQuery('#frm-styling-action').hide();
	jQuery('#frm-styling-action').css('overflow', 'visible');
	jQuery('#preview-form-styling-setting').css('height', '');
	jQuery('#preview-form-styling-setting').removeAttr('style');
	
});
function ShowColorSelect(checkradiosty)
{
	if(checkradiosty!="none" && checkradiosty!="futurico" && checkradiosty!="polaris")
	{
		jQuery('#check_radio_main_color').show();
	}
	else
	{
		jQuery('#check_radio_main_color').hide();
	}	
}
function frmsetfieldtransparancy()
{
	if(jQuery("input[name=arfmfo]:checkbox:checked").val()==0)
	{
		jQuery("input[name=arfmfo]:checkbox").val('1').trigger('change');
	}
	else
	{
		jQuery("input[name=arfmfo]:checkbox").val('0').trigger('change');
	}
}

function arf_change_error_type(){
	var value = jQuery('input[name="arfest"]:checked').val();
	var form_id = jQuery('#id').val();
	jQuery('#testiframe').contents().find('form #form_tooltip_error_'+form_id).val(value);
	if(value=="advance")
	{
		jQuery("#showadvanceposition").css("display",'block');
		jQuery("#color_palate_advance").css("display",'block');
		jQuery("#color_palate_normal").css("display",'none');
		jQuery('#testiframe').contents().find('.popover').remove();
		if(jQuery('#testiframe').contents().find('input,textarea,select').hasClass("arf_required"))
		{
			jQuery('#testiframe').contents().find('.arf_submit_btn').trigger('click');
		}	
	}
	else
	{
		jQuery("#color_palate_normal").css("display",'block');
		jQuery("#color_palate_advance").css("display",'none');
		jQuery("#showadvanceposition").css("display",'none');
		jQuery('#testiframe').contents().find('.help-block').empty().removeClass('animated bounceInDownNor');
		if(jQuery('#testiframe').contents().find('input,textarea,select').hasClass("arf_required"))
		{
			jQuery('#testiframe').contents().find('.arf_submit_btn').trigger('click');
		}	
	}
}

function arf_change_error_position(){
	var value = jQuery('input[name="arfestbc"]:checked').val();
	var form_id = jQuery('#id').val();
	jQuery('#testiframe').contents().find('form #form_tooltip_error_'+form_id).attr('data-position',value);
	jQuery('#testiframe').contents().find('.popover').remove();
	if(jQuery('#testiframe').contents().find('input,textarea,select').hasClass("arf_required"))
	{
		jQuery('#testiframe').contents().find('.arf_submit_btn').trigger('click');
	}	
}

function arf_change_check_radio(){

	jQuery('#testiframe').contents().find('#arffrm_<?php echo $id; ?>_container input').not('.arf_hide_opacity').iCheck('destroy');
	
	var checkbox_class = '';
	var chk_style = jQuery('#frm_check_radio_style').val();
	var chk_color = jQuery('#frm_check_radio_style_color').val();
	
	if( chk_style != 'none'  ){
		checkbox_class = chk_style;
		
		if(chk_style != 'futurico' && chk_style != 'polaris' && chk_color != 'default'){
			checkbox_class = checkbox_class+'-'+chk_color;
		}
				
		jQuery('#testiframe').contents().find('#arffrm_<?php echo $id; ?>_container input').not('.arf_hide_opacity').iCheck({
			checkboxClass: 'icheckbox_'+checkbox_class,
			radioClass: 'iradio_'+checkbox_class 
		});
		
		jQuery('#testiframe').contents().find('.arf_form input[type="checkbox"]').on('ifChanged', function(event){
			jQuery(this).trigger('change');
		});
		
		jQuery('#testiframe').contents().find('.arf_form input[type="radio"]').on('ifChecked', function(event){
			jQuery(this).trigger('click');
		});
		jQuery('#testiframe').contents().find('.arf_form input[type="checkbox"]').on('ifClicked', function(event){
			jQuery(this).trigger('focus');
		});
		
		jQuery('#testiframe').contents().find('.arf_form input[type="radio"]').on('ifClicked', function(event){
			jQuery(this).trigger('focus');
		});
	}		
	
}

function change_date_format_new(){
	var value = jQuery('#frm_date_format').val();
	if( value == '' || value == 'undefined'){
		value = 'mm/dd/yy';
	}
			
	jQuery('#testiframe').contents().find('#arf_form_date_format').val(value);
}
</script>