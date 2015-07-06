<?php
global $arfieldhelper;
$like_label = isset($field['lbllike']) ? $field['lbllike'] : 'Like';
$dislike_label = isset($field['lbldislike']) ? $field['lbldislike'] : 'Dislike';
?>
<?php if( is_admin() ){ ?>
    <div class="controls">
        <div class="like_container">
            <input type="radio" style="left: -999px;position: absolute;" class="arf_hide_opacity arf_like" name="item_meta[<?php echo $field['id'];?>]" id="field_<?php echo $field['field_key'];?>-0" value="1" <?php checked($field['default_value'], 1);?> /><label id="like_<?php echo $field['field_key'];?>-0" class="arf_like_btn <?php if(isset($field['default_value']) && $field['default_value']=='1'){ echo 'active'; }?> field_edit arfhelptip" for="field_<?php echo $field['field_key'];?>-0" title="<?php echo $field['lbllike'];?>"><img src="<?php echo ARFURL.'/images/'; ?>like-icon.png" /></label>
    		
            <input type="radio" style="left: -999px;position: absolute;" class="arf_hide_opacity arf_like" name="item_meta[<?php echo $field['id'];?>]" id="field_<?php echo $field['field_key'];?>-1" value="0" <?php checked($field['default_value'], 0);?> /><label id="like_<?php echo $field['field_key'];?>-1" class="arf_dislike_btn <?php if(isset($field['default_value']) && $field['default_value']== '0'){ echo 'active'; }?> field_edit arfhelptip" for="field_<?php echo $field['field_key'];?>-1" title="<?php echo $field['lbldislike'];?>"><img src="<?php echo ARFURL.'/images/'; ?>dislike-icon.png" /></label>
        </div>       
        <div style="margin-left:90px; padding-top:3px;"> <div class="arfresetlikebtn arfhelptip" onclick="arfresetlikefield('<?php echo $field['id'];?>');" title="<?php _e('Reset default value', 'ARForms');?>"><img src="<?php echo ARFURL.'/images/reset-icon-new.png'; ?>" align="absmiddle" /></div> </div>
    </div>
<?php } else { ?>
    <div class="controls">
    <?php
		if(!is_admin() && apply_filters('arf_check_for_draw_outside',false,$field))
		{
			do_action('arf_drawthisfieldfromoutside',$field);
		}
		else
		{
		?>
        <div class="like_container">
            <input type="radio" class="arf_hide_opacity arf_like" style=" <?php if( is_rtl() ){ echo 'right: -999px;';} else { echo 'left: -999px;'; }?>position: absolute;" name="item_meta[<?php echo $field['id'];?>]" id="field_<?php echo $field['field_key'];?>-0" value="1" <?php checked($field['default_value'], '1');?> <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="'.esc_attr($field['blank']).'"'; }?> <?php echo $arfieldhelper->get_onchage_func($field); ?> /><label id="like_<?php echo $field['field_key'];?>-0" class="arf_like_btn <?php if(isset($field['default_value']) && $field['default_value']=='1'){ echo 'active'; }?>" for="field_<?php echo $field['field_key'];?>-0" data-title="<?php echo $field['lbllike'];?>"><img src="<?php echo ARFURL.'/images/'; ?>like-icon.png" /></label>
    		
            <input type="radio" class="arf_hide_opacity arf_like" style=" <?php if( is_rtl() ){ echo 'right: -999px;';} else { echo 'left: -999px;'; }?>position: absolute;" name="item_meta[<?php echo $field['id'];?>]" id="field_<?php echo $field['field_key'];?>-1" value="0" <?php checked($field['default_value'], '0');?> <?php echo $arfieldhelper->get_onchage_func($field); ?> /><label id="like_<?php echo $field['field_key'];?>-1" class="arf_dislike_btn <?php if(isset($field['default_value']) && $field['default_value']=='0'){ echo 'active'; }?>" for="field_<?php echo $field['field_key'];?>-1" data-title="<?php echo $field['lbldislike'];?>"><img src="<?php echo ARFURL.'/images/'; ?>dislike-icon.png" /></label>    
        </div><?php } 
       echo $arfieldhelper->replace_description_shortcode($field); 
?></div><?php 
} ?>