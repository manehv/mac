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
global $current_user, $arformhelper;
@ini_set('max_execution_time', 0); 
?>
<style>
	#frm_add_form_id{height:150px !important;}
</style>
   
<div class="wrap arfforms_page arf_imortexport"> 

<span class="h2" style="padding-left:30px; padding-top:30px; position:absolute;"><?php _e('Import / Export Forms', 'ARForms'); ?></span>
  <div id="poststuff" class="metabox-holder">
    <div id="post-body">
      <div class="inside">
        <div class="frm_settings_form wrap_content">
        <div style="margin-left: 15px;">
            <div id="success_message" style="margin-bottom:0px !important; margin-top:30px !important; width:95%;display:none">
            	<div class="arfsuccessmsgicon"></div><div class="arf_success_message">
                <?php _e('Form Imported Successfully.', 'ARForms'); ?>
                </div>
            </div>
            
            <?php
				if( !extension_loaded('zip') ){
			?>
            	<div id="form_name_message" style="margin-bottom:5px;">
                    <ul style="margin-bottom: 3px; margin-top: 3px; margin-right:25px;">
                        <li>
                        	<div class="arferrmsgicon"></div>
                        	<div id="error_message"><?php _e('Please Enable Zip extension on your server.',ARP_PT_TXTDOMAIN); ?></div>
                        </li>
	                </ul>
                </div>
			<?php
				}
			?>
            
            <div id="form_name_message" style="margin-bottom:5px;display:none;">
            <ul style="margin-bottom: 3px; margin-top: 3px; margin-right:25px;">
    			<li><div class="arferrmsgicon"></div><div id="error_message"></div></li>
            </ul>
   			</div>
    	
          </div>
          <div style="clear:both"></div>
          <div class="modal-body" style="height:600px;clear:both;padding:15px;">
            
         <div class="opt_export_div">
            <label style="font-size:16px;cursor:auto;"><span></span>
            <span class="lbltitle"><?php _e('Export Form(s)', 'ARForms'); ?>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<?php _e('Export Entries', 'ARForms') ?></span>
            </label>
        </div>
       
        <div style="clear:both; margin-top:20px;"></div>
       
        <div class="export_opt_part" id="export_opt_part" style="display:block; margin-left:15px;">
            <?php $plugin_url_list = plugin_dir_url( __FILE__ );?>
            
             <form id="exportForm" onSubmit="return check_import_form_selected();" method="post">
             <input type="hidden" value="<?php echo site_url().'/index.php?plugin=ARForms';?>" name="ARFSCRIPTURL_cus" id="ARFSCRIPTURL_cus" />
             <div id="export_forms" class="export_forms">
                <input type="radio" class="rdostandard" name="opt_export" id="opt_export_form" value="opt_export_form" checked="checked" />
                &nbsp;
                <label for="opt_export_form" style="font-size:14px; margin-left:-5px;"><span></span>
                <?php _e('Export Form(s)', 'ARForms'); ?>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" class="rdostandard" name="opt_export" id="opt_export_entries" value="opt_export_entries"/>
                &nbsp;
                <label for="opt_export_entries" style="font-size:14px;"><span></span>
                <?php _e('Export Entries', 'ARForms'); ?>
                </label>
            </div>
            <Br />
            
           
              <table class="form-table">
                <tr>
                  <td colspan="2" style="padding:0;"><span class="lblsubtitle" style="font-size:14px;"><?php _e('Please Select Form(s)', 'ARForms'); ?></span>
                  <div style="clear:both; margin-top:5px;"></div>
                  <div class="" style="float:none; width:120px; font-size:15px;text-align:left;">
                  	
                   <?php $arformhelper->forms_dropdown_new( 'frm_add_form_id', '', 'Select form' ,'','','mutliple',1)?>   
                    </div>
                    <div id="arf_xml_select_form_error" style=" height:29px; width:360px; text-align:right; line-height:29px;font-weight:bold;display:none;color:#FF0000;"><?php _e('Please Select Form','ARForms');?></div></td>
                </tr>
                <tr style="margin-top:10px;">
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  
                  <td colspan="2" style="padding:15px 0 0;"><input name="export_button" type="submit" id="export_button" class="greensavebtn arfexportbtn" value="<?php _e('Export', 'ARForms') ?>">
                  
                
                  </td>
                </tr>
              </table>
            </form>
        </div>
        <br />
        <div style="width:100%; margin-top:30px; border-bottom:1px solid #E3E4E7;"></div>
        <br />
        <div class="opt_import_div" style="float:left;">
            <label style="font-size:15px;cursor:auto;"><span></span>
            <span class="lbltitle"><?php _e('Import Form(s)', 'ARForms') ?></span>
            </label>
           <br /><br />
        </div>
        
        <div class="import_opt_part" id="import_opt_part" style="display:block">
            <form id="importForm" action="" method="post" enctype="multipart/form-data" onsubmit="return check_import_file_selected();">
                <table class="form-table">
				<tr>
					<td align="left" colspan="2" style="padding:0; padding-left:15px;"><label class="lblsubtitle"><?php _e('Please upload zip file exported from ARForms plugin.', 'ARForms'); ?></label><br /><br /></td>
				</tr>
                <?php /*?><tr>
                  <td></td>
                  <td></td>
                </tr><?php */?>  
                <tr>
                  <td class="tdclass" style="padding:0; padding-left:15px; width:140px; vertical-align:middle !important;" align="left" ><div class="arf_export_select_file"><?php _e('Please Select File :', 'ARForms') ?></div></td>
                  <td style="padding-left:0;"><div id="message"></div>
                   <div class="arf_file_field" style="width:100%; cursor:pointer; ">
                  <div class="<?php if( isset($browser_info['name']) and $browser_info['name'] == 'Internet Explorer' and isset($browser_info['version']) and $browser_info['version'] <= '9' ){ echo 'original_btn'; }?> arfajax-file-upload bluepreviewbtn" id="div" data-id="<?php echo (isset($field['id']) ? $field['id'] : ''); ?>" form-id="<?php echo (isset($field['form_id']) ? $field['form_id'] : '');?>"><span style="cursor:pointer;"><img src="<?php echo ARFIMAGESURL.'/upload-icon.png';?>" align="absmiddle" style="margin-top:-5px !important;"/>&nbsp;&nbsp;<?php echo _e('Upload','ARForms');?></span>
            
                        <input type="file" accept=".zip" size="60" name="importFile" id="importFile" class="original_import" style="position: absolute; cursor: pointer; top: 0px; max-width:200px; right:0; height: 29px; padding:0; margin:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" onchange="return check_import_file_selected();">
            
            </div>
            
            <div id="remove_import_button" class="remove_imported_form redremovebtn ajax-file-remove" style="display:none; position: relative; overflow: hidden; float:left;" onclick="arf_delete_import_file();" ><img src="<?php echo ARFIMAGESURL.'/remove-icon.png';?>" align="absmiddle" /> &nbsp;<?php echo _e('Remove','ARForms');?></div>
            
            <span id="file_name_error" class="arf_importerr"><?php _e('Please select valid file','ARForms');?></span>
            <span id="file_not_error" class="arf_importerr"><?php _e('Please select file','ARForms');?></span>
            <span id="file_name_new" class="arf_importerr" style="display:"></span>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td style="padding-left:0;">
                    
                    <div id="progress" class="progress progress-striped active" style="margin-bottom:0;">
                        <div class="bar" style="width:0%; box-sizing: content-box;"></div>
                    </div>
                    <div id="info" class="arf_info arf_file_field" style="display:none;">
                        <span id="file_name" class="file_name"></span>
                        <span class="percent">% Completed</span>        
                        <span id="percent" class="percent">&nbsp;&nbsp;&nbsp;0</span>
                    </div>
                    </td>
                  </td>
                </tr>
                <tr style="margin-top:10px;">
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td style="padding:0;" colspan="2">
                  	<input type="hidden" name="arf_xml_file_name" id="arf_xml_file_name" value="" /><input type="hidden" name="arf_import_disable" id="arf_import_disable" value="1" />
                    
                    <input type="button" id="import_btn" style="margin-left:15px;" onclick="arf_xml_upload();" class="greensavebtn arf_importbtn" value="<?php _e('Import', 'ARForms') ?>">&nbsp;&nbsp;<span id="import_loader" style="display:none; margin-left:10px;"><img src="<?php echo ARFURL.'/images/loading_299_1.gif';?>" height="15" /></span>	
                  
                  </td>
                </tr>
                </table>
            </form>
        </div>
            
		</div>
      </div>
    </div>
    </div>
  </div>
  <div class="documentation_link" align="right"><a href="<?php echo ARFURL;?>/documentation/index.html" style="margin-right:10px;" target="_blank">
    <?php _e('Documentation','ARForms');?>
    </a>|<a href="http://www.arformsplugin.com/arforms-support/" style="margin-left:10px;" target="_blank">
    <?php _e('Support','ARForms');?>
    </a></div>
</div>
<script>
jQuery(document).ready(function(options) {
	var options = { 
	beforeSend: function() 
	{
		jQuery('#progress').removeClass('progress');
		jQuery('#progress div.bar').css('width',"0%");
		jQuery('#progress').addClass('progress');
		jQuery('#progress').addClass('active').show();
		jQuery('#info').css('display', 'inline-block');
		jQuery('#arf_import_disable').val('0');
	},
	uploadProgress: function(event, position, total, percentComplete) 
	{
		// uploading.......
		jQuery('#progress div.bar').css('width', percentComplete + "%");
		jQuery('#info #percent').html('&nbsp;&nbsp;&nbsp;'+percentComplete);	
	},
	success: function() 
	{
		jQuery('#info #percent').html('&nbsp;&nbsp;&nbsp;100'); 
		jQuery('#progress div.bar').css('width', "100%"); 
		jQuery('#progress').removeClass('active');																	  
		jQuery('#div').css('margin-top',"0px");
		
		jQuery('.arfajax-file-upload').hide();
		jQuery('#remove_import_button').show();
	},
	complete: function(response) 
	{
		var data = response.responseText.split('||');
		jQuery("#error_message").hide();
		jQuery('#arf_import_disable').val('1');
		
		if(data[0] == 'success')
		{
			jQuery("#form_name_message").hide();
			msg_class = jQuery("#success_message .arf_success_message");
			msg_class.html(data[1]);
			jQuery('#arf_xml_file_name').val(data[1]);
		}
		else
		{
			msg_class = jQuery("#error_message");
			msg_class.html(data[1]);
			msg_class.show();
		}
	},
	error: function()
	{
		jQuery("#message").html("<font color='red'> ERROR: unable to upload files</font>");
	} 
}; 
jQuery("#importForm").ajaxForm(options);
});
jQuery('.original_import').on('change', function(e){	   
	var id = jQuery(this).attr('id');
		id = id.replace('field_', '');
	
	var fileName = jQuery(this).val();	
	fileName = fileName.replace(/C:\\fakepath\\/i, '');	
	if( fileName != '' ){
		jQuery('#file_name_new').html(fileName);		
		
		var extension = fileName.substr( (fileName.lastIndexOf('.') +1) );
		if(extension == 'zip')
		{
			jQuery("#form_name_message").hide();
			jQuery("#importForm").submit();
		}
		else{
			jQuery('#file_name_error').show();
		}
	}
});

function arf_xml_upload(){
		
	if( jQuery('#arf_import_disable').val() == 0 ){
		return false;
	}
	var xml_file_name = jQuery('#arf_xml_file_name').val();
	if(xml_file_name != ''){
		jQuery('#import_loader').show();
		jQuery.ajax({
			type:"POST",url:ajaxurl,
			data:"action=arf_import_form&xml_file_name="+xml_file_name,
			success:function(msg){
				jQuery('#import_loader').hide();
				var data = msg.split('||');
				jQuery("#error_message").hide();
				
				if(data[0] == 'success')
				{
					msg_class = jQuery("#success_message .arf_success_message");
					msg_class.html(data[1]);
					jQuery(window.opera?'html':'html, body').animate({ scrollTop:jQuery('#message_success')}, 'slow' );
					jQuery('#arf_xml_file_name').val('');
					jQuery('#file_name_new').html('');
					jQuery('#info #percent').html('&nbsp;&nbsp;&nbsp;0'); 
					jQuery('#progress div.bar').css('width', "0%"); 
					jQuery('#info').hide();
					jQuery('#progress').hide();
					jQuery('#importForm').trigger('reset');
					
					jQuery('.arfajax-file-upload').show();
					jQuery('#remove_import_button').hide();
					
					jQuery("#success_message").show().delay(3000).fadeOut('slow');
				}
				else
				{
					msg_class = jQuery("#error_message");
					msg_class.html(data[1]);
					jQuery(window.opera?'html':'html, body').animate({ scrollTop:jQuery('#error_message')}, 'slow' );
					jQuery('#arf_xml_file_name').val('');
					jQuery('#file_name_new').html('');
					jQuery('#info #percent').html('&nbsp;&nbsp;&nbsp;0'); 
					jQuery('#progress div.bar').css('width', "0%"); 
					jQuery('#info').hide();
					jQuery('#progress').hide();
					jQuery('#importForm').trigger('reset');
					
					jQuery('.arfajax-file-upload').show();
					jQuery('#remove_import_button').hide();
					
					msg_class.show();
					jQuery('#form_name_message').show();
				}
				
			}
    	});
	
	} else {
		jQuery('#file_name_error').hide();
		jQuery('#file_not_error').show();
	}
}

function arf_delete_import_file(){
	var xml_file_name = jQuery('#arf_xml_file_name').val();
	if(xml_file_name != ''){
	
		jQuery.ajax({
			type:"POST",url:ajaxurl,
			data:"action=arf_delete_import_form&xml_file_name="+xml_file_name,
			success:function(msg){
					jQuery('#arf_xml_file_name').val('');
					jQuery('#file_name_new').html('');
					jQuery('#info #percent').html('&nbsp;&nbsp;&nbsp;0'); 
					jQuery('#progress div.bar').css('width', "0%"); 
					jQuery('#info').hide();
					jQuery('#progress').hide();
					jQuery('#importForm').trigger('reset');	
					
					jQuery('.arfajax-file-upload').show();
					jQuery('#remove_import_button').hide();
					
			}
		});
	}
				
} 
</script>