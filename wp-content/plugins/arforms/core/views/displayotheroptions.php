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

global $arformcontroller, $arfieldhelper;

if ($field['type'] == 'hidden'){

    $arfaction = (isset($_GET) and isset($_GET['arfaction'])) ? 'arfaction' : 'action';

    if (is_admin() and (!isset($_GET[$arfaction]) or $_GET[$arfaction] != 'new')){ ?>

<div id="arf_field_<?php $field['id'] ?>_container" class="arfformfield arfmainformfield top_container">
<label class="arf_main_label"><?php echo $field['name'] ?>:</label> <?php echo $field['value']; ?>
</div>
<?php } 

if(!is_admin() && apply_filters('arf_check_for_draw_outside',false,$field))
{
	do_action('arf_drawthisfieldfromoutside',$field);
}
else
{

	if (is_array($field['value'])){
	
		foreach ($field['value'] as $checked){ 
	
			$checked = apply_filters('arfhiddenvalue', $checked, $field); ?><input type="hidden" name="<?php echo $field_name ?>[]" value="<?php echo esc_attr($checked) ?>"/><?php
	
		}
	
	}else{ 
		$hiddenvalue = '<input type="hidden" id="field_'.$field['field_key'].'" name="'.$field_name.'" value="'.esc_attr($field['value']).'"/>';
		$hiddenvalue = $arformcontroller->arf_remove_br( $hiddenvalue );	
		echo $hiddenvalue; 
	} 

}

}else if ($field['type'] == 'break'){

    global $arfprevpage, $arffield;



    if (isset($arfprevpage[$field['form_id']]) and $arfprevpage[$field['form_id']] == $field['field_order']){ 

        echo $arfieldhelper->replace_field_shortcodes($arfieldhelper->get_basic_default_html($field['type']), $field, array(), $form);

        

        $previous_fields = $arffield->getAll("fi.type not in ('divider','captcha','break','html') and fi.form_id=$field[form_id] and fi.field_order < $field[field_order]"); 

        foreach ($previous_fields as $prev){ 

            if (isset($_POST['item_meta'][$prev->id])){ 

                if (is_array($_POST['item_meta'][$prev->id])){

                    foreach ($_POST['item_meta'][$prev->id] as $checked){

                        $checked = apply_filters('arfhiddenvalue', $checked, (array)$prev);

                        echo '<input type="hidden" name="item_meta['.$prev->id.'][]" value="'. $checked .'"/>'."\n";

                    }

                }else{ ?><input type="<?php echo apply_filters('arfpagedfieldtype', 'hidden', array('field' => $prev)) ?>" id="field_<?php echo $prev->field_key ?>" name="item_meta[<?php echo $prev->id ?>]" value="<?php echo stripslashes(esc_html($_POST['item_meta'][$prev->id])) ?>" /><?php       
				}

            }

        } 

    }else{  
	
global $arf_page_number, $arfform, $arf_column_classes, $page_break_hidden_array, $arf_previous_label; 

if( isset($field['classes']) ) {
	unset($arf_column_classes['two']);
	unset($arf_column_classes['three']);
	unset($arf_column_classes);
}

$display_page = '';
if( $arf_page_number == 0 and $total_page == 1 ){
	
	$display_temp = $arfieldhelper->get_display_style($field);
	$display_page = ( !empty($display_temp) ) ? 'style="display:none;"' : '';
} 
else if( $arf_page_number != 0 )
	$display_page = 'style="display:none;"';

global $arf_section_div;
if($arf_section_div) {
	echo "<div class='arf_clear'></div></div>";
	$arf_section_div = 0;
}

echo "<div style='clear:both;height:1px;'>&nbsp;</div></div>";

if( $arf_page_number == 0 )
{
	$arf_previous_label[0] = $field['pre_page_title'];
	$arf_previous_label[1] = $field['pre_page_title'];
}
else
{
	$arf_previous_label[0] = $arf_previous_label[1];
	$arf_previous_label[1] = $field['pre_page_title'];
}
$arf_previous_label_txt = $arf_previous_label[0];
if( empty($arf_previous_label_txt) )
	$arf_previous_label_txt = 'Previous';   	
?><div class="arfsubmitbutton arf_submit_div <?php echo $_SESSION['label_position'];?>_container" id="arf_submit_div_<?php echo $arf_page_number; ?>" <?php echo $display_page; ?>><?php if($arf_page_number != 0 ) { ?><input type="button" name="previous" class="previous_btn" onclick="go_previous('<?php echo $arf_page_number-1; ?>', '<?php echo $form->id; ?>', 'no', '<?php echo $form->form_key; ?>');" value="<?php echo $arf_previous_label_txt; ?>" /><?php } ?><input type="submit" class="next_btn" name="next" value="<?php echo $field['next_page_title']; ?>" /></div><?php

$arf_page_number++;//---------- for conditional logic ----------//
if( $arfieldhelper->get_display_style($field) != '' ){
	if( isset($page_break_hidden_array[$form->id]) ){
		$page_break_hidden_array[$form->id]['data-hide'] .= $arf_page_number.','; 
	} else {
		$page_break_hidden_array[$form->id]['data-hide']  = $arf_page_number.',';
	}	
}//---------- for conditional logic ----------//
	
echo '<div id="page_'.$arf_page_number.'" class="page_break" style="display:none;">';	 
    } 

} ?>