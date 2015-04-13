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

global $arfieldhelper, $arformhelper;
?>
<div id="arfmainformeditorcontainer" class="arf_main_tabs active_tabs">

<?php if(isset($message) and $message != '') { ?><div id="success_message" style="margin:0 15px 5px 20px; width:auto;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message"><?php echo $message;?></div></div><?php } ?>

<?php if( isset($errors) && is_array($errors) && count($errors) > 0 ){ ?>

    <div style="margin-top:5px;">

        <ul id="frm_errors" style="margin-bottom: 3px; margin-top: 3px;">

            <?php foreach( $errors as $error )

                echo '<li><div class="arferrmsgicon"></div><div id="error_message">' . stripslashes($error) . '</div></li>';

            ?>

        </ul>

    </div>
    
    <div style="clear:both"></div>

	<?php } 
	?>
     <div id="form_name_message" style="margin-bottom:5px;display:none;">
            <ul style="margin-bottom: 3px; margin-top: 3px; margin-left:20px;">
    			<li><div class="arferrmsgicon"></div><div id="error_message"><?php _e('Please enter form name', 'ARForms');?></div></li>
            </ul>
   	</div>
    
    <div id="titlediv" class="arftitlediv">
<input type="hidden" value="<?php echo ARFURL.'/images';?>" id="plugin_image_path" />

<div id="form_desc" class="edit_form_item arffieldbox frm_head_box">

	<div class="arfformnamediv">
    	<div class="arfformedit">
        	<span class="arfeditorformname" id="frmform_<?php echo $id; ?>" style="background:none;"><?php echo stripslashes($values['name']); ?></span>
        </div>
        <div class="arfformeditpencil" style="margin-top:3px;"></div>
	</div>
	<div style="clear:both;"></div>
    <div class="arfformdescriptiondiv">
 		<div class="arfdescriptionedit">
    		<div class="arfeditorformdescription" style="background:none;"><?php echo $values['description']; ?></div>
        </div>
        <div class="arfdescriptioneditpencil"></div>    
	</div>
	<div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>

</div>




<div id="arf_fileupload_iframe" class="arf_modal_box" style="display:none;">
    <div class="arf_modal_top_belt">
            <span class="arf_modal_title"><?php _e('Choose File','ARForms'); ?></span>
            <span class="arf_modal_close_btn arfmodal-media-close" data-dismiss="arfmodal" onclick="arfmodal_media_upload_close();"></span>
        </div>
    <div id="arf_media_upload_iframeContent"></div>
</div>

<ul id="new_fields" data-flag="1">


<?php

if (isset($values['fields']) && !empty($values['fields'])){

	$arf_is_page_break_no = 0;
	
    foreach($values['fields'] as $field){

		if( $field['type'] == 'break' && $arf_is_page_break_no == 0 ){
			$field['page_break_first_use'] = 1;
			$arf_is_page_break_no++;
		}
		
        $field_name = "item_meta[". $field['id'] ."]";


        require(VIEWS_PATH .'/newfield.php');


        unset($field);


        unset($field_name);


    }


} ?>

</ul>
<?php
$data = "";
if($is_ref_form == 1)
	$data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_ref_forms WHERE id = %d", $id), 'ARRAY_A');
else
	$data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $id), 'ARRAY_A');
	
$aweber_arr = "";
$aweber_arr   = $data[0]['form_css'];

$arr = maybe_unserialize($aweber_arr);

$newarr = array();
if(count($arr) > 0) {
foreach($arr as $k => $v)
	$newarr[$k] = $v;
}
	
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

$submit_buttonwidth = $newarr['arfsubmitbuttonwidthsetting'] ? $newarr['arfsubmitbuttonwidthsetting'] : '';
?>
<div style="clear:both;"></div>
<div class="arfeditorsubmitdiv">
	<div class="arfsubmitedit">
    	<div class="greensavebtn" data-auto="<?php if($submit_buttonwidth!=''){ echo '1';} else{ echo '0';} ?>" <?php if($submit_buttonwidth!=''){ echo 'style="width:'.$submit_buttonwidth.'px;"'; }?> data-width="<?php echo $submit_buttonwidth;?>">
    		<div class="arfsubmitbtn" id="arfeditorsubmit"><?php echo $submit_value;?></div>
        </div>
    </div>
    <div class="arfsubmiteditpencil arfhelptip" title="<?php _e('Edit Text', 'ARForms'); ?>"></div>
    <div class="arfsubmitsettingpencil arfhelptip" title="<?php _e('Settings', 'ARForms'); ?>" id="field-setting-button-arfsubmit" onclick="arfshowfieldoptions('arfsubmit')" data-lower="<?php if( version_compare( $GLOBALS['wp_version'], '3.3', '<') ){echo "true";}else{ echo "false";}?>"></div>
    
    
    
    
    
    <div style="right:0; margin-top:-65px;" id="main_fieldoptions_modal_arfsubmit" class="main_fieldoptions_modal">
        <div id="field-option-arfsubmit" class="show-field-options arfmodal" style="display:none;margin-top:0px;top:0px;position:absolute;width:auto;min-width: 200px;">
        <div class="arfpopuparrow"></div>
        <div class="arfpopuparrow_right"></div>
        <div class="arfpopuparrow_bottom"></div>
        <div class="arfpopuparrow_right_bottom"></div>
        
        <div class="arfmodal-header"><div><?php _e('Submit Button Options', 'ARForms'); ?><button data-dismiss="arfmodal" onclick="close_add_field_subject('field-option-arfsubmit')" class="close" type="button" style="margin-top:-4px; opacity:1; filter:alpha(opacity=100); outline:none;"><img src="<?php echo ARFIMAGESURL.'/close-btn.png'; ?>" border="0" align="absmiddle" /></button></div></div>
        <div class="arfmodal-body_fieldoptions">
        	        
		<div id="main_fieldoption_table" class="fields_table">  
            
        <div class="fieldoptions_tab_main">
            <div id="fieldoption_inner_tab_selected field_conditional_law_tab" style="background:none; padding:10px 0 0;" class="fieldoption_inner_tab" onclick="arf_open_field_option('arfsubmit', 'field_conditional_law','0');"><?php _e('Conditional Law', 'ARForms') ?></div>
        </div>
        
        <!-- conditional logic div start -->
        <div class="field_conditional_law field_basic_option arf_fieldoptiontab flip" style="display:block;">
        <?php
        //print_r($values);
		$cl_submit_conditional_login = ( isset($values['submit_conditional_logic']) ) ? $values['submit_conditional_logic'] : array();
		$cl_rules_array = ( isset($cl_submit_conditional_login['rules']) ) ? $cl_submit_conditional_login['rules'] : array();
		$cl_submit_conditional_login['enable']=isset($cl_submit_conditional_login['enable']) ? $cl_submit_conditional_login['enable'] : array();
		?>
		<div style=" <?php echo isset($padding) ? $padding : '';?> padding-top:10px; width:90%;">
        
				<div style="width:auto; font-size:14px; text-align:left; padding:5px; display:none; "><input type="checkbox" class="chkstanard" name="conditional_logic_arfsubmit" id="conditional_logic_arfsubmit" onchange="arf_cl_change('arfsubmit');" value="<?php echo $cl_submit_conditional_login['enable']; ?>" <?php checked($cl_submit_conditional_login['enable'], 1) ?> /><label for="conditional_logic_arfsubmit"><span></span><?php _e('Enable Conditional Law', 'ARForms'); ?></label></div>
				<!-- main div start -->
				
				<div id="conditional_logic_div_arfsubmit" style="width:auto; margin-bottom:20px; font-size:14px;">
					<!-- main condition div -->
					<div class="arflabeltitle">
					
					<div class="sltstandard<?php if( count($cl_rules_array) == 0 ){ echo ' arfhelptip'; }?>" <?php if( count($cl_rules_array) == 0 ){ ?>title="<?php _e('Please add one or more rules', 'ARForms'); ?>"<?php }?> style="float:none; display:inline;"><select name="conditional_logic_display_arfsubmit" id="conditional_logic_display_arfsubmit" data-width="100px" <?php if( count($cl_rules_array) == 0 ){ ?>disabled="disabled"<?php }?> >
							<option value="show" <?php if( isset($cl_submit_conditional_login['display']) ) { selected($cl_submit_conditional_login['display'], 'show'); } ?>><?php _e('Enable', 'ARForms'); ?></option>
							<option value="hide" <?php if( isset($cl_submit_conditional_login['display']) ) { selected($cl_submit_conditional_login['display'], 'hide'); } ?>><?php _e('Disable', 'ARForms'); ?></option>
					</select></div>
					&nbsp;<?php _e('this button if', 'ARForms'); ?>&nbsp;
					<div class="sltstandard<?php if( count($cl_rules_array) == 0 ){ echo ' arfhelptip'; }?>" <?php if( count($cl_rules_array) == 0 ){ ?>title="<?php _e('Please add one or more rules', 'ARForms'); ?>"<?php }?> style="float:none; display:inline;"><select name="conditional_logic_if_cond_arfsubmit" id="conditional_logic_if_cond_arfsubmit" data-width="80px" <?php if( count($cl_rules_array) == 0 ){ ?>disabled="disabled"<?php }?> >
							<option value="all" <?php if( isset($cl_submit_conditional_login['if_cond']) ) { selected($cl_submit_conditional_login['if_cond'], 'all'); } ?>><?php _e('All', 'ARForms'); ?></option>
							<option value="any" <?php if( isset($cl_submit_conditional_login['if_cond']) ) { selected($cl_submit_conditional_login['if_cond'], 'any'); } ?>><?php _e('Any', 'ARForms'); ?></option>
					</select></div>
					&nbsp;<?php _e('of the following match:', 'ARForms'); ?>&nbsp;
					
					</div>
					<!-- main condition div -->
					
					<!-- logic rules start -->
                    <button type="button" id="arf_new_law_arfsubmit" onclick="arf_add_new_law('arfsubmit');" style=" <?php if( $cl_submit_conditional_login['enable'] == 1 ) { echo 'display:none;'; } ?>" class="greensavebtn arfaddnewrule"><?php _e('Add New Law', 'ARForms'); ?></button>
                    
					<div id="logic_rules_div_arfsubmit" style=" <?php if( $cl_submit_conditional_login['enable'] == 0 ) { echo 'display:none;'; } ?>" class="logic_rules_div">
						<?php 
						
						if( count($cl_rules_array) > 0 ){ 
							$rule_i = 1;
							foreach($cl_rules_array as $rule) {
						?>
						<div id="arf_cl_rule_arfsubmit<?php echo '_'.$rule_i; ?>" class="cl_rules">
							<input type="hidden" name="rule_array_arfsubmit[]" value="<?php echo $rule_i; ?>" />
							<div class="sltstandard" style="float:none; display:inline;"><?php echo $arfieldhelper->arf_cl_field_menu($form->id, 'arf_cl_field_arfsubmit_'.$rule_i, 'arf_cl_field_arfsubmit_'.$rule_i, $rule['field_id']); ?></div>
							&nbsp;
							<div class="sltstandard" style="float:none; display:inline;"><?php echo $arfieldhelper->arf_cl_rule_menu('arf_cl_op_arfsubmit_'.$rule_i, 'arf_cl_op_arfsubmit_'.$rule_i, $rule['operator']); ?></div>
							&nbsp;
							<input type="text" name="cl_rule_value_arfsubmit<?php echo '_'.$rule_i; ?>" id="cl_rule_value_arfsubmit<?php echo '_'.$rule_i; ?>" class="txtstandardnew" value='<?php echo esc_attr($rule['value']); ?>' />
							&nbsp;
							<span class="bulk_add_remove">
								<span onclick="add_new_rule('arfsubmit');" class="bulk_add">&nbsp;</span>
								<span onclick="delete_rule('arfsubmit', '<?php echo $rule_i; ?>');" class="bulk_remove">&nbsp;</span>
							</span>
							
						</div>
						<?php $rule_i++; }
						
						} 
						?>
					</div>    
					<!-- logic rules end -->
					
				</div>
				<!-- main div end -->
		</div>
		
        </div>
        <!-- conditional logic div end -->
        
        </div>
        
        	
            <div style="clear:both;"></div>
        	<div align="right" class="arfpopupclosediv"> <button type="button" class="arfpopupclose" data-dismiss="arfmodal" onclick="close_add_field_subject('field-option-arfsubmit')" ><?php _e('Done', 'ARForms');?></button> </div>
            
        </div>
        
		        
		</div>
    	
        <input type="hidden" id="field_type_arfsubmit" data-fid="arfsubmit" value="arfsubmit" />
		<input type="hidden" id="field_ref_arfsubmit" value="arfsubmit" />
		<input type="hidden" name="field_options[field_key_arfsubmit]" class="txtstandardnew" value="arfsubmit_key" size="20" />
        <div style="clear:both;"></div>
    </div>
    <div style="clear:both; height:35px;"></div>
    
    
    
    
    
</div>

<?php
	$key = $values['form_key'];
	 
	if($is_ref_form == 1) 
		$form = $arfform->getAll(array('form_key' => $key), '', 1,1);
	else
		$form = $arfform->getAll(array('form_key' => $key), '', 1);
		
	$pre_link = $arformhelper->get_direct_link($form->form_key);
	
	
	$width = @$_COOKIE['width'] * 0.80;
	
	$width_new = '&width='.$width;
	
?>
                 <?php $delete_modal_width  = (@$_COOKIE['width']-850)/2;
					$delete_modal_height = (@$_COOKIE['height']-500)/2;?>
<div style="clear:both;"></div>

</div>



<!-- for preview iframe -->
<?php 
$widthmaincontent = @$_COOKIE['width'] - 650;
$extra_width = "0";
if( version_compare( $GLOBALS['wp_version'], '3.3', '<') ) { $extra_width = "145"; }
$left_width = (	($widthmaincontent)/2 + $extra_width).'px';
if(is_rtl())
{
	$iframediv_loader_style = 'right:'.$left_width.';top:180px;';
}
else
{
	$iframediv_loader_style = 'left:'.$left_width.';top:180px;';
}
?>
<div id="arf_main_style_tab" class="arf_main_tabs" style="display:none;">
	<div id="arf_main_style_tab_message">                            	                            
    </div>
    
    <div class="iframediv_loader" style=" <?php echo $iframediv_loader_style; ?>" align="center"><img src="<?php echo ARFURL.'/images/ajax-loading-teal.gif'; ?>" /></div>
    
    <div id="iframediv"></div><!-- iframe div -->
    
</div>
<!-- for preview iframe -->
<?php
    $delete_modal_width  = (@$_COOKIE['width']-350)/2;
	$delete_modal_height = (@$_COOKIE['height']-180)/2;

$key = $values['form_key'];
if($is_ref_form == 1)	 
	$form = $arfform->getAll(array('form_key' => $key), '', 1,1);
else
	$form = $arfform->getAll(array('form_key' => $key), '', 1);
		
$pre_link = $arformhelper->get_direct_link($form->form_key);

?>
<div style="left:-999px; position:fixed; visibility:hidden;">
	<div class="greensavebtn" style="float:left;" id="arfsubmitbuttontext2"><?php echo $submit_value;?></div>
</div>