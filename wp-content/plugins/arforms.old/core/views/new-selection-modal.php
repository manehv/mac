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
global $arfform;

$all_templates = $arfform->getAll(array('is_template' => 1), 'name');
?>
<div class="arfnewmodalclose" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/close-button.png';?>" align="absmiddle" /></div>
<div id="new_form_selection_modal">
	<form method="get" name="new" id="new">
        <input type="hidden" name="arfaction" id="arfnewaction" value="new" />
        <input type="hidden" name="page" value="ARForms" />
        <input type="hidden" name="newformid" value="<?php echo $id;?>" />
        <input type="hidden" name="id" id="template_list_id" value="" />    
    <div class="newform_modal_title_container">
    	<div class="newform_modal_title"><img src="<?php echo ARFIMAGESURL.'/add-newform-icon.png'; ?>" align="absmiddle" />&nbsp;<?php _e('NEW FORM','ARForms');?></div>
    </div>	
    <div class="newform_modal_fields">
    	
        <div class="newmodal_field_title"><?php _e('Form Name','ARForms');?>&nbsp;<span class="newmodal_required" style="color:#ff0000; vertical-align:top;">*</span></div>
        <div class="newmodal_field"><input name="form_name" id="form_name_new" value="" class="txtmodal1" /><br /><div id="form_name_new_required" class="arferrmessage" style="display:none;"><?php _e('Please enter form name','ARForms');?></div></div>
        
        <div class="newmodal_field_title"><?php _e('Form Description','ARForms');?></div>
        <div class="newmodal_field"><textarea name="form_desc" id="form_desc_new" class="txtmultimodal1" rows="3"></textarea></div>
        
        <div class="newmodal_field_title"><?php _e('Template','ARForms');?></div>
        <div class="newmodal_field arfdefaulttemplate">
        
        	<div id="arftemplate_blankform" onclick="arf_selectform('blankform');" class="arf_modalform_box arfactive" style="margin-bottom:5px;">
            	<div class="arf_formbox_hover"></div>
                <div id="arfblankformimg" class="arf_modalform_boximg"></div>
                <div class="arf_modalform_boxtitle"><?php _e('Blank Form','ARForms');?></div>  
            </div>
            <?php 
			global $arfdefaulttemplate;
			if( $arfdefaulttemplate )
			{
				$ti = 1;
				foreach($arfdefaulttemplate as $template_id => $template_name)
				{
				?>
                <div id="arftemplate_<?php echo $template_id ?>" onclick="arf_selectform('<?php echo $template_id ?>');" class="arf_modalform_box" <?php if($ti <= 3){ ?>style="margin-bottom:5px;"<?php } ?>>
            		<div class="arf_formbox_hover"></div>
                	<div id="arftempimg_<?php echo $template_id ?>" class="arf_modalform_boximg"></div>
                	<div class="arf_modalform_boxtitle"><?php echo $template_name;?></div>  
            	</div>
                <?php
				$ti++;
				}
			}
			?> 
        </div>
        
    </div>
	<div style="clear:both;"></div>
    <div id="arfcontinuebtn" onclick="submit_form_type();"><img src="<?php echo ARFIMAGESURL.'/continue-icon.png'; ?>" align="absmiddle" style=" <?php if( is_rtl() ){ echo 'margin-left:10px;'; } else { echo 'margin-right:10px;'; } ?>" /><?php _e('Continue', 'ARForms'); ?></div>
    </form>
    
</div>