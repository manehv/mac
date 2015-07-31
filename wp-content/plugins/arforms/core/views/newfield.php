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
@ini_set("memory_limit", "256M");

global $style_settings, $armainhelper, $arfieldhelper, $arformcontroller;

$display = apply_filters('arfdisplayfieldoptions', array(
    'type' => $field['type'], 'field_data' => $field, 'required' => true,
    'description' => true, 'options' => true, 'label_position' => true,
    'invalid' => false, 'size' => false, 'clear_on_focus' => false,
    'default_blank' => true, 'css' => true
        ));

$prepop = array();


$prepop[__('Countries', 'ARForms')] = $armainhelper->get_countries();

$states = $armainhelper->get_us_states();


$prepop[__('U.S. States', 'ARForms')] = array_values($states);


$prepop[__('U.S. State Abbreviations', 'ARForms')] = array_keys($states);





$prepop[__('Age Group', 'ARForms')] = array(
    __('Under 18', 'ARForms'), __('18-24', 'ARForms'), __('25-34', 'ARForms'),
    __('35-44', 'ARForms'), __('45-54', 'ARForms'), __('55-64', 'ARForms'),
    __('65 or Above', 'ARForms'), __('Prefer Not to Answer', 'ARForms')
);





$prepop[__('Satisfaction', 'ARForms')] = array(
    __('Very Satisfied', 'ARForms'), __('Satisfied', 'ARForms'), __('Neutral', 'ARForms'),
    __('Unsatisfied', 'ARForms'), __('Very Unsatisfied', 'ARForms'), __('N/A', 'ARForms')
);


$prepop[__('Days', 'ARForms')] = array(
    __('1', 'ARForms'), __('2', 'ARForms'), __('3', 'ARForms'), __('4', 'ARForms'), __('5', 'ARForms'), __('6', 'ARForms'),
    __('7', 'ARForms'), __('8', 'ARForms'), __('9', 'ARForms'), __('10', 'ARForms'), __('11', 'ARForms'), __('12', 'ARForms'),
    __('13', 'ARForms'), __('14', 'ARForms'), __('15', 'ARForms'), __('16', 'ARForms'), __('17', 'ARForms'), __('18', 'ARForms'),
    __('19', 'ARForms'), __('20', 'ARForms'), __('21', 'ARForms'), __('22', 'ARForms'), __('23', 'ARForms'), __('24', 'ARForms'),
    __('25', 'ARForms'), __('26', 'ARForms'), __('27', 'ARForms'), __('28', 'ARForms'), __('29', 'ARForms'), __('30', 'ARForms'),
    __('31', 'ARForms'),
);


$prepop[__('Week Days', 'ARForms')] = array(
    __('Sunday', 'ARForms'), __('Monday', 'ARForms'), __('Tuesday', 'ARForms'),
    __('Wednesday', 'ARForms'), __('Thursday', 'ARForms'), __('Friday', 'ARForms'),
    __('Saturday', 'ARForms'),
);


$prepop[__('Months', 'ARForms')] = array(
    __('January', 'ARForms'), __('February', 'ARForms'), __('March', 'ARForms'), __('April', 'ARForms'),
    __('May', 'ARForms'), __('June', 'ARForms'), __('July', 'ARForms'), __('August', 'ARForms'), __('September', 'ARForms'),
    __('October', 'ARForms'), __('November', 'ARForms'), __('December', 'ARForms'),
);

$current_year = date("Y");

$from_year = "1935";

$year_display = array();
for ($yr_counter = $from_year; $yr_counter <= $current_year; $yr_counter++) {
    $year_display[] = $yr_counter;
}

$prepop[__('Years', 'ARForms')] = $year_display;




$prepop[__('Prefix', 'ARForms')] = array(
    __('Mr', 'ARForms'), __('Mrs', 'ARForms'), __('Ms', 'ARForms'), __('Miss', 'ARForms'),
    __('Sr', 'ARForms'),
);

$country_codes = $armainhelper->get_country_codes();

ksort($country_codes);

$country_codes = array_keys($country_codes);

$prepop[__('Telephone Country Codes', 'ARForms')] = $country_codes;
?>

<?php
$myliclass = "";
if ($field['classes'] == "arf_2") {
    $myliclass = "width:45.5%;float:left;clear:none;height:130px;";
} else if ($field['classes'] == "arf_3") {
    $myliclass = "width:29%;float:left;clear:none;height:130px;";
} else {
    $myliclass = "float:none;clear:both;height:auto;";
}


global $arf_column_classes;

if (isset($field['classes']) and $field['classes'] == 'arf_2' and empty($arf_column_classes['two'])) {
    $arf_column_classes['two'] = '(First)';
    unset($arf_column_classes['three']);
} else if (isset($field['classes']) and $field['classes'] == 'arf_2' and isset($arf_column_classes['two']) and $arf_column_classes['two'] == '(First)') {
    $arf_column_classes['two'] = '(Second)';
    unset($arf_column_classes['three']);
} else if (isset($field['classes']) and $field['classes'] == 'arf_3' and empty($arf_column_classes['three'])) {
    $arf_column_classes['three'] = '(First)';
    unset($arf_column_classes['two']);
} else if (isset($field['classes']) and $field['classes'] == 'arf_3' and isset($arf_column_classes['three']) and $arf_column_classes['three'] == '(First)') {
    $arf_column_classes['three'] = '(Second)';
    unset($arf_column_classes['two']);
} else if (isset($field['classes']) and $field['classes'] == 'arf_3' and isset($arf_column_classes['three']) and $arf_column_classes['three'] == '(Second)') {
    $arf_column_classes['three'] = '(Third)';
    unset($arf_column_classes['two']);
} else if (isset($field['classes']) and $field['classes'] == 'arf_1') {
    unset($arf_column_classes['two']);
    unset($arf_column_classes['three']);
    unset($arf_column_classes);
}

$multicolclass = '';
$multicolborder = ' border-right:none;';
if (isset($arf_column_classes['two']) and $arf_column_classes['two'] != '') {
    if ($arf_column_classes['two'] == '(First)') {
        $multicolborder = ' border-right:2px dashed #E6E6E6; clear:both;';
        $multicolclass .= " arf21colclass";
    }
    if ($arf_column_classes['two'] == '(Second)')
        $multicolclass = 'arf_2col';

    $multicolclass .= " arf2columns";
}
else if (isset($arf_column_classes['three']) and $arf_column_classes['three'] != '') {
    if ($arf_column_classes['three'] == '(First)') {
        $multicolborder = ' border-right:2px dashed #E6E6E6; clear:both;';
        $multicolclass = 'arf_31col arf31colclass';
    }
    if ($arf_column_classes['three'] == '(Second)') {
        $multicolborder = ' border-right:2px dashed #E6E6E6;';
        $multicolclass = 'arf_23col';
    } else if ($arf_column_classes['three'] == '(Third)')
        $multicolclass = 'arf_3col';

    $multicolclass .= " arf3columns";
}
else {
    $multicolclass .= " arf1columns";
}

if ($field['type'] == 'captcha') {
    if ($field['is_recaptcha'] == 'custom-captcha')
        $multicolclass .= " arf-custom-captcha";
    else
        $multicolclass .= " arf-recaptcha";
}

if ($field['options'] && is_array($field['options']) && ( $field['type'] == 'radio' || $field['type'] == 'checkbox' || $field['type'] == 'select' ))
    $field['options'] = $arfieldhelper->changeoptionorder($field);
?>
<li style=" <?php echo $myliclass . $multicolborder; ?> margin:0px;" id="arfmainfieldid_<?php echo $field['id']; ?>" class="arfmainformfield edit_form_item arffieldbox ui-state-default <?php echo $display['options'] ?> edit_field_type_<?php echo $display['type'] ?> top_container <?php echo $multicolclass; ?>" onmouseover="arffieldhover(1,<?php echo $field['id']; ?>)" onmouseout="arffieldhover(0,<?php echo $field['id']; ?>)">

    <?php
    if (isset($is_create_new_field) && $is_create_new_field == 1) {
        require(VIEWS_PATH . '/addfieldjs.php');
    }
    ?>

    <div class="fieldname-row">
        <?php
        if (isset($arf_column_classes['three']) and $arf_column_classes['three'] == '(Third)')
            unset($arf_column_classes['three']);
        if (isset($arf_column_classes['two']) and $arf_column_classes['two'] == '(Second)')
            unset($arf_column_classes['two']);
        ?>
        <?php
        $delete_modal_width = (@$_COOKIE['width'] - 560) / 2;
        $delete_modal_height = (@$_COOKIE['height'] - 180) / 2;
        ?>

        <div id="delete_field_message_<?php echo $field['id']; ?>" style="display:none;left:<?php echo $delete_modal_width . 'px'; ?>; top:<?php echo $delete_modal_height . 'px'; ?>;" class="arfmodal arfhide arffade arfdeletemodabox">
            <div class="arfnewmodalclose" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL . '/close-button.png'; ?>" align="absmiddle" /></div>
            <input type="hidden" value="" id="delete_id"/>
            <div class="arfdelete_modal_title"><img src="<?php echo ARFIMAGESURL . '/delete-field-icon.png'; ?>" align="absmiddle" style="margin-top:-5px;" />&nbsp;<?php _e('DELETE FIELD', 'ARForms'); ?></div>
            <div class="arfdelete_modal_msg"><?php _e('Are you sure you want to delete this field?', 'ARForms'); ?></div>
            <div class="arf_delete_modal_row">
                <div class="arf_delete_modal_left" onclick="fielddelete(<?php echo $field['id']; ?>);"><img src="<?php echo ARFIMAGESURL . '/okay-icon.png'; ?>" align="absmiddle" style="margin-right:10px;" />&nbsp;<?php _e('Okay', 'ARForms'); ?></div>
                <div class="arf_delete_modal_right" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL . '/cancel-btnicon.png'; ?>" align="absmiddle" style="margin-right:10px;" />&nbsp;<?php _e('Cancel', 'ARForms'); ?></div>
            </div>
        </div>


<?php do_action('arfextrafieldactions', $field['id']); ?>


        <div class="fieldname">
            <?php
            $arf_disply_required_field = true;
            $arf_disply_required_field = apply_filters('arf_disply_required_field_outside', $arf_disply_required_field, $field);

            if ($display['required'] and $field['type'] != 'slider' && $field['type'] != 'imagecontrol' && $arf_disply_required_field) {
                ?>

                <span id="require_field_<?php echo $field['id']; ?>">

                    <a href="javascript:arfmakerequiredfieldfunction(<?php echo $field['id']; ?>,<?php echo $field_required = ($field['required'] == '0') ? '0' : '1'; ?>,'1')" class="arfaction_icon arfhelptip arffieldrequiredicon alignleft arfcheckrequiredfield<?php echo $field_required ?>" id="req_field_<?php echo $field['id']; ?>" title="<?php echo __('Click to mark as', 'ARForms') . ( $field['required'] == '0' ? ' ' : ' not ') . __('compulsory field.', 'ARForms'); ?>"></a>

                </span>

            <?php } ?>

<?php if ($display['type'] == 'break') { ?><BR />
                <div style="border-top:1px dashed #1BBAE1;text-align:center;width:98%;margin:0 1%;">
                    <span class="page-break-txt">Page Break</span>
                </div>

            <?php } else if ($field['type'] == 'divider') { ?>
                <label class="arfeditorfieldopt_divider_label arf_main_label arfeditorfieldopt_label" id="field_<?php echo $field['id']; ?>"><?php echo $field['name'] ?></label>
            <?php } else { ?>
                <label class="arfeditorfieldopt_label arf_main_label" id="field_<?php echo $field['id']; ?>"><?php echo $field['name'] ?></label>

                <?php if ($field['type'] == 'hidden') { ?>
                    <input type="hidden" name="field_options[name_<?php echo $field['id']; ?>]" id="arfname_<?php echo $field['id']; ?>" value="<?php echo $field['name']; ?>" />
                <?php } ?>

<?php } ?>

        </div>

    </div>

    <div class="arf_fieldiconbox">
        <div class="field-icon arfshowiconsonhover<?php echo $field['id']; ?>" style="visibility:hidden;">
                <?php if ($field['type'] != 'hidden') { ?>
                <div class="field-iconbox">
                    <a href="javascript:void(0);" onclick="arfshowfieldoptions('<?php echo $field['id']; ?>')" id="field-setting-button-<?php echo $field['id']; ?>" class="frm-setting arfhelptip" title="<?php _e('Field Options', 'ARForms'); ?>" data-lower="<?php
                    if (version_compare($GLOBALS['wp_version'], '3.3', '<')) {
                        echo "true";
                    } else {
                        echo "false";
                    }
                    ?>"><img id="field-setting-icon<?php echo $field['id']; ?>" src="<?php echo ARFIMAGESURL ?>/editor_icons/setting-icon_hover.png" alt="Duplicate" /></a> 
                </div>
<?php } ?>

            <div class="field-iconbox">
                <a href="javascript:void(0);" onclick="arfduplicatefield('<?php echo $field['form_id']; ?>', '<?php echo $field['type']; ?>', '<?php echo (isset($field['ref_field_id'])) ? $field['ref_field_id'] : $field['id']; ?>', '<?php echo $field['id']; ?>');" class="frm-duplicate arfhelptip" title="<?php _e('Duplicate', 'ARForms'); ?>"><img src="<?php echo ARFIMAGESURL ?>/editor_icons/duplicate-icon_hover.png" alt="Duplicate" id="field-duplicate-icon<?php echo $field['id']; ?>" /></a>
            </div>	

            <div class="field-iconbox">
                <a href="javascript:void(0);" class="frm-move arfhelptip" title="<?php _e('Move', 'ARForms'); ?>"><img src="<?php echo ARFIMAGESURL ?>/editor_icons/move-icon_hover.png" alt="Move" id="field-move-icon<?php echo $field['id']; ?>" /></a>
            </div>	

            <div class="field-iconbox">
                <a data-toggle="arfmodal" href="#delete_field_message_<?php echo $field['id']; ?>" onclick="arfchangedeletemodalwidth('arfdeletemodabox');" class="frm-delete arfhelptip" id="arffielddelete<?php echo $field['id']; ?>" title="<?php _e('Delete Field', 'ARForms') ?>"><img src="<?php echo ARFIMAGESURL ?>/editor_icons/delete-icon_hover.png" alt="Delete" id="field-delete-icon<?php echo $field['id']; ?>" /></a>
            </div>    
        </div>
    </div>

    <div class="allfields">    

        <?php
        if ($display['type'] == 'text') {
            $input_cls = '';
            $inp_cls = '';
            if ($display['field_data']['enable_arf_prefix'] == 1 or $display['field_data']['enable_arf_suffix'] == 1) {
                echo "<div class='arf_editor_prefix_suffix_wrapper' id='prefix_suffix_wrapper_{$field['id']}'>";
                if ($display['field_data']['enable_arf_prefix'] == 1 && $display['field_data']['enable_arf_suffix'] == 1) {
                    $inp_cls = 'arf_both_pre_suffix';
                } else if ($display['field_data']['enable_arf_prefix'] == 1) {
                    $inp_cls = 'arf_prefix_only';
                } else if ($display['field_data']['enable_arf_suffix'] == 1) {
                    $inp_cls = 'arf_suffix_only';
                }

                if ($display['field_data']['enable_arf_prefix'] == 1) {
                    echo "<span class='arf_editor_prefix' id='arf_editor_prefix_{$field['id']}'><i class='fa " . $display['field_data']['arf_prefix_icon'] . "'></i></span>";
                }
                $input_cls = 'arf_prefix_suffix';
            }
            ?>
            <input type="text" class="textbox <?php echo $input_cls . ' ' . $inp_cls; ?>" name="<?php echo $field_name ?>" id="itemmeta_<?php echo $field['id']; ?>" onkeyup="arfchangeitemmeta('<?php echo $field['id']; ?>');" value="<?php echo esc_attr($field['default_value']); ?>" <?php //echo (isset($field['size']) && $field['size']) ? 'style="width:auto" size="'.$field['size'] .'"' : '';  ?> /> 

            <?php
            if ($display['field_data']['enable_arf_prefix'] == 1 or $display['field_data']['enable_arf_suffix'] == 1) {
                if ($display['field_data']['enable_arf_suffix'] == 1) {
                    echo "<span class='arf_editor_suffix' id='arf_editor_suffix_{$field['id']}'><i class='fa " . $display['field_data']['arf_suffix_icon'] . "'></i></span>";
                }
                echo "</div>";
            }
            ?>

<?php } else if ($display['type'] == 'colorpicker') { ?>
            <div class="arf_editor_prefix_suffix_wrapper arf_editor_colorpicker">
                <span class="arf_editor_prefix"  id='arf_editor_prefix_<?php echo $field['id']; ?>'><i class="fa fa-paint-brush"></i></span>
                <input type="text" class="textbox arf_prefix_only arf_prefix_suffix" name="<?php echo $field_name ?>" id="itemmeta_<?php echo $field['id']; ?>" onkeyup="arfchangeitemmeta('<?php echo $field['id']; ?>');" value="<?php echo esc_attr($field['default_value']); ?>" />
            </div>

<?php } else if ($field['type'] == 'textarea') { ?>


            <textarea name="<?php echo $field_name ?>"<?php //if ($field['size']) echo ' style="width:auto" cols="'.$field['size'].'"'   ?> id="itemmeta_<?php echo $field['id']; ?>" onkeyup="arfchangeitemmeta('<?php echo $field['id']; ?>');" rows="<?php echo $field['max']; ?>"><?php echo $armainhelper->esc_textarea($field['default_value']); ?></textarea> 





            <?php
        } else if ($field['type'] == 'radio' or $field['type'] == 'checkbox') {

            if (!isset($field['align']) || empty($field['align'])) {
                if ($field['type'] == 'checkbox')
                    $field['align'] = 'block';
                else
                    $field['align'] = 'inline';
            }

            $field['prepop'] = $prepop;
            ?>
            <div class="arf_checkbox_div" id="arf_checkbox_div_<?php echo $field['id']; ?>">
                <div id="arf_checkboxradio_<?php echo $field['id']; ?>" class="arf_checkboxradio_inside <?php
                         if ($field['align'] == 'inline') {
                             echo 'arf_single_row';
                         } else {
                             echo 'arf_multiple_row';
                         }
                         ?>">
                         <?php
                         if (is_array($field['options'])) {
                             $i = 0;
                             $total_options = count($field['options']);

                             foreach ($field['options'] as $opt_key => $opt) {
                                 $field_val = apply_filters('arfdisplaysavedfieldvalue', $opt, $opt_key, $field);
                                 $opt = apply_filters('show_field_label', $opt, $opt_key, $field);

                                 if (is_array($opt)) {
                                     $opt = $opt['label'];
                                     $field_val = ($field['separate_value']) ? $field_val['value'] : $opt;
                                 }

                                 $checked = (isset($field['value']) and ( (!is_array($field['value']) && $field['value'] == $field_val ) || (is_array($field['value']) && in_array($field_val, $field['value'])))) ? ' checked="true"' : '';

                                 if (is_array($opt)) {
                                     $opt = $opt['label'];
                                     $field_val = $field_val['value'];
                                 }
                                 ?>
                            <div class="arf_check_radio_fields">
                                <input type="<?php echo $field['type'] ?>" <?php echo ($field['type'] == 'checkbox') ? 'class="class_checkbox checkbox_radio_class"' : 'class="class_radio checkbox_radio_class"'; ?> id="fieldcheck_sub_<?php echo $field['id'] ?>-<?php echo $opt_key ?>" name="<?php echo $field_name ?>_sub_<?php echo ($field['type'] == 'checkbox') ? '[]' : ''; ?>" disabled="disabled" value="<?php echo esc_attr($field_val) ?>"<?php echo isset($checked) ? $checked : ''; ?>/><label class="arf_checkbox_radio_label" id="arflbl_<?php echo $field['id'] ?>-<?php echo $opt_key ?>" for="fieldcheck_sub_<?php echo $field['id'] ?>-<?php echo $opt_key ?>"><?php echo $opt; ?></label>
                            </div>    
                            <?php
                            unset($checked);
                            $i++;
                            if ($i == 5)
                                break;
                        }
                    }
                    $field['default_value'] = maybe_unserialize($field['default_value']);
                    ?>
                </div>
            </div>
            <div style="clear:both;"></div>
            <div id="arf_checkbox_notice_<?php echo $field['id']; ?>" style="margin-left:10px; margin-top:10px; <?php if ($total_options <= 5) echo "display:none;"; ?>" class="howto"><span class="arf_cb_current">5</span> <?php _e('of', 'ARForms') ?> <span class="arf_cb_total"><?php echo $total_options ?></span> <?php _e('options shown. Click "Edit options" Button to view all', 'ARForms'); ?></div>

            <button type="button" style="margin-top:10px;height:27px; font-size:13px;" onclick="arf_open_field_options('<?php echo $field['id']; ?>')" class="btn_2"><?php _e('Edit Options', 'ARForms'); ?></button>

            <div style="right:0; margin-top:-100px; position:absolute;" id="main_fieldoptions_modal_opt_<?php echo $field['id']; ?>" class="main_fieldoptions_modal">
                <div id="arffieldoptions_<?php echo $field['id'] ?>" class="show-field-options arfmodal" style="display:none;margin-top:0px;top:0px;position:absolute;width:auto;min-width: 200px;">
                    <div class="arfpopuparrow"></div>
                    <div class="arfpopuparrow_right"></div>
                    <div class="arfpopuparrow_bottom"></div>
                    <div class="arfpopuparrow_right_bottom"></div>

                    <div class="arfmodal-header"><div><?php _e('Edit Options', 'ARForms'); ?><button data-dismiss="arfmodal" onclick="close_add_field_subject('arffieldoptions_<?php echo $field['id']; ?>')" class="close" type="button" style="margin-top:-4px; opacity:1; filter:alpha(opacity=100); outline:none;"><img src="<?php echo ARFIMAGESURL . '/close-btn.png'; ?>" border="0" align="absmiddle" /></button></div></div>
                    <div class="arfmodal-body_fieldoptions">

                        <div style="width:auto;">
                            <span class="fieldoptions_label_style"><?php _e('Use separate values', 'ARForms'); ?>:</span><label class="lblswitch">&nbsp;&nbsp;<span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[separate_value_<?php echo $field['id'] ?>]" id="separate_value_<?php echo $field['id'] ?>"  <?php echo ($field['separate_value']) ? 'checked="checked"' : ''; ?> onchange="arfplaceseparatevalue(<?php echo $field['id'] ?>)" value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label><span style="margin-left:10px;"><img src="<?php echo ARFIMAGESURL ?>/tooltips-icon.png" alt="?" class="arfhelptip" title="<?php _e('Add a separate value to use for calculations, email routing, saving to the database, and many other uses. The option values are saved while the option labels are shown in the form.', 'ARForms') ?>" align="absmiddle" /></span>
                        </div>

                        <div style="width:auto;">
                            <div id="arf_field_<?php echo $field['id'] ?>_opts" class="clear <?php echo (count($field['options']) > 5) ? ' arffieldoptionslist' : ' arffieldoptionslist'; ?>" <?php if ($field['separate_value'] == 1) { ?> style="width:540px;"<?php } else { ?> style="width:330px;"<?php } ?>>

    <?php do_action('arfaddsepratevalues', $field); ?>
                                <ul id="arfoptionul_<?php echo $field['id'] ?>" class="arfoptionul">
    <?php require(VIEWS_PATH . '/radiobutton.php'); ?>
                                </ul>
                            </div>
                        </div>

                        <div id="frm_add_field_<?php echo $field['id']; ?>" class="arfshowfieldclick"><br  />

                            <div style="float:left; padding-right:10px; padding-top:3px;">

                                <button onclick="javascript:arfaddnewfieldoption(<?php echo $field['id']; ?>)" id="arfaddanoption_<?php echo $field['id']; ?>" class="btn_2" type="button" style="height:26px;font-size:12px;margin:0px 5px 5px 0px;line-height:23px;padding:0 10px;<?php
                                if (!is_array($field['options']) || !($field['options'])) {
                                    echo 'display:inline;';
                                } else {
                                    echo 'display:none;';
                                }
                                ?>"><?php _e('Add an Option', 'ARForms') ?></button>

                                <?php if (!isset($field['post_field']) or $field['post_field'] != 'post_category') { ?>
                                    <?php //_e('or', 'ARForms'); ?>
                                    <a href="javascript:void(0)" onclick="arfshowbulkfieldoptions1(<?php echo $field['id']; ?>);" style="color: #21759B;" class="arlinks">
                                        <button class="btn_2" type="button" style="height:26px;font-size:12px;margin-left:5px;line-height:23px;"><?php _e('Preset Field Choices', 'ARForms') ?></button>
                                    </a>

                                </div>
                                <div id="arfshowfieldbulkoptions-<?php echo $field['id']; ?>" style="float:left; display:none; width:320px;">
        <?php /* ?><select id="frm_bulk_options-select-<?php echo $field['id'];?>" class="frm-bulk-select-class" onchange="arfstorebulkoptionvalue('<?php echo $field['id'];?>',this.value)">
          <option value=''><?php _e('Select', 'ARForms'); ?></option>
          <?php foreach($prepop as $label => $pop){ ?>
          <option value='<?php echo str_replace("'", '&#145;', json_encode($pop)) ?>'><?php echo $label ?></option>
          <?php } ?>
          </select><?php */ ?>

                                    <input class='frm-bulk-select-class' id='frm_bulk_options-select-<?php echo $field['id']; ?>' value=' ' type='hidden' onchange='arfstorebulkoptionvalue("<?php echo $field['id']; ?>", this.value)'>
                                    <dl class='arf_selectbox' data-name='frm_bulk_options-select-<?php echo $field['id']; ?>' data-id='frm_bulk_options-select-<?php echo $field['id']; ?>' style='width:200px;margin-right:30px;float:left;'>
                                        <dt><span><?php _e('Select', 'ARForms'); ?></span>
                                        <input value='' style='display:none;width:128px;' class='arf_autocomplete' type='text'>
                                        <i class='fa fa-caret-down fa-lg'></i></dt>
                                        <dd>
                                            <ul style='display: none;' data-id='frm_bulk_options-select-<?php echo $field['id']; ?>'>
                                                <li class='arf_selectbox_option' data-value='' data-label='<?php _e('Select', 'ARForms'); ?>'><?php _e('Select', 'ARForms'); ?></li>
        <?php foreach ($prepop as $label => $pop) { ?>
                                                    <li class='arf_selectbox_option' data-value='<?php echo str_replace("'", '&#145;', json_encode($pop)) ?>' data-label='<?php echo $label ?>'><?php echo $label ?></li>
        <?php } ?>                            
                                            </ul>
                                        </dd>
                                    </dl>




                                    <textarea name="frm_bulk_options-<?php echo $field['id']; ?>" class="frm_bulk_options_text_size" id="frm_bulk_options_sel-<?php echo $field['id']; ?>" style="height:0px;width:0px;float:right; visibility:hidden;"></textarea>
                                    <button onClick="arfupdatebulkoptions(<?php echo $field['id'] ?>, jQuery('#frm_bulk_options_sel-<?php echo $field['id']; ?>').val());
                                            return false;" class="btn_apply" style="margin-right:5px;"><?php _e('Apply', 'ARForms') ?></button>
                                </div>
                                <div id="arfshowfieldbulkoptions_success-<?php echo $field['id']; ?>" style="padding-top:8px; font-size:12px; font-weight:bold; float:left; display:none;"><?php _e('Saving', 'ARForms'); ?>...</div>

    <?php } ?>        
                        </div>
                        <div style="clear:both;"></div>
                        <div align="right" class="arfpopupclosediv"> <button type="button" class="arfpopupclose" data-dismiss="arfmodal" onclick="close_add_field_subject('arffieldoptions_<?php echo $field['id']; ?>')" ><?php _e('Done', 'ARForms'); ?></button> </div>
                    </div>
                </div>

            </div>
            <!--<div style="clear:both;"></div>-->

                    <?php
                } else if ($field['type'] == 'select') {
                    ?>
            <select name="<?php echo $field_name;
            echo (isset($field['multiple']) and $field['multiple']) ? '[]' : '';
                    ?>" <?php
                echo (isset($field['size']) && $field['size']) ? 'style="width:auto"' : '';

                echo (isset($field['multiple']) and $field['multiple']) ? ' multiple="multiple"' : '';
                ?> >

                <?php
                foreach ($field['options'] as $opt_key => $opt) {

                    $field_val = apply_filters('arfdisplaysavedfieldvalue', $opt, $opt_key, $field);

                    $opt = apply_filters('show_field_label', $opt, $opt_key, $field);

                    if (is_array($opt)) {
                        $opt = $opt['label'];
                        $field_val = ($field['separate_value']) ? $field_val['value'] : $opt;
                    }

                    $selected = ($field['default_value'] == $field_val) ? (' selected="selected"') : ('');
                    ?>

                    <option value="<?php echo $field_val ?>"<?php echo $selected ?>><?php echo $opt ?></option>

    <?php } ?>

            </select>

            <div style="clear:both;"></div>
            <button type="button" style="margin-top:10px; height:27px; font-size:13px;" onclick="arf_open_field_options('<?php echo $field['id']; ?>')" class="btn_2"><?php _e('Edit Options', 'ARForms'); ?></button>

            <div style="right:0; margin-top:-100px; position:absolute;" id="main_fieldoptions_modal_opt_<?php echo $field['id']; ?>" class="main_fieldoptions_modal">
                <div id="arffieldoptions_<?php echo $field['id'] ?>" class="show-field-options arfmodal" style="display:none;margin-top:0px;top:0px;position:absolute;width:auto;min-width: 200px;">
                    <div class="arfpopuparrow"></div>
                    <div class="arfpopuparrow_right"></div>
                    <div class="arfpopuparrow_bottom"></div>
                    <div class="arfpopuparrow_right_bottom"></div>

                    <div class="arfmodal-header"><div><?php _e('Edit Options', 'ARForms'); ?><button data-dismiss="arfmodal" onclick="close_add_field_subject('arffieldoptions_<?php echo $field['id']; ?>')" class="close" type="button" style="margin-top:-4px; opacity:1; filter:alpha(opacity=100); outline:none;"><img src="<?php echo ARFIMAGESURL . '/close-btn.png'; ?>" border="0" align="absmiddle" /></button></div></div>
                    <div class="arfmodal-body_fieldoptions">

                        <div style="width:auto;">
                            <span class="fieldoptions_label_style"><?php _e('Use separate values', 'ARForms'); ?>:</span><label class="lblswitch">&nbsp;&nbsp;<span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[separate_value_<?php echo $field['id'] ?>]" id="separate_value_<?php echo $field['id'] ?>"  <?php echo ($field['separate_value']) ? 'checked="checked"' : ''; ?> onchange="arfplaceseparatevalue(<?php echo $field['id'] ?>)" value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label><span style="margin-left:10px;"><img src="<?php echo ARFIMAGESURL ?>/tooltips-icon.png" alt="?" class="arfhelptip" title="<?php _e('Add a separate value to use for calculations, email routing, saving to the database, and many other uses. The option values are saved while the option labels are shown in the form.', 'ARForms') ?>" align="absmiddle" /></span>
                        </div>

                        <div class="arfshowfieldclick">

                            <div id="arf_field_<?php echo $field['id'] ?>_opts"<?php echo (count($field['options']) > 5) ? ' class="arffieldoptionslist"' : ' class="arffieldoptionslist"'; ?> <?php if ($field['separate_value'] == 1) { ?> style="width:520px;"<?php } else { ?> style="width:310px;"<?php } ?>>

    <?php do_action('arfaddsepratevalues', $field); ?>
                                <ul id="arfoptionul_<?php echo $field['id'] ?>" class="arfoptionul">
    <?php
    foreach ($field['options'] as $opt_key => $opt) {
        $field_val = apply_filters('arfdisplaysavedfieldvalue', $opt, $opt_key, $field);
        $opt = apply_filters('show_field_label', $opt, $opt_key, $field);
        require(VIEWS_PATH . '/optionsingle.php');
    }
    ?>
                                </ul>    
                            </div>


                            <div id="frm_add_field_<?php echo $field['id']; ?>"> <br  />

                                <div style="float:left; padding-right:10px; padding-top:3px;">

                                    <button onclick="javascript:arfaddnewfieldoption(<?php echo $field['id']; ?>)" id="arfaddanoption_<?php echo $field['id']; ?>" class="btn_2" type="button" style="height:26px;font-size:12px;margin:0px 5px 5px 0px;line-height:23px;padding:0 10px;<?php
    if (!is_array($field['options']) || !($field['options'])) {
        echo 'display:inline;';
    } else {
        echo 'display:none;';
    }
    ?>"><?php _e('Add an Option', 'ARForms') ?></button>

                                    <?php if (!isset($field['post_field']) or $field['post_field'] != 'post_category') { ?>

                                        <?php //_e('or', 'ARForms'); ?>

                                        <a href="javascript:void(0)" onclick="arfshowbulkfieldoptions1(<?php echo $field['id']; ?>);" style="color: #21759B;" class="arlinks">
                                            <button class="btn_2" type="button" style="height:26px;font-size:12px;margin-left:5px;line-height:23px;"><?php _e('Preset Field Choices', 'ARForms') ?></button>
                                        </a>
                                    </div>
                                    <div id="arfshowfieldbulkoptions-<?php echo $field['id']; ?>" style="float:left; display:none; width:320px;">
        <?php /* ?><select id="frm_bulk_options-select-<?php echo $field['id'];?>" class="frm-bulk-select-class" onchange="arfstorebulkoptionvalue('<?php echo $field['id'];?>',this.value)">
          <option value=''><?php _e('Select', 'ARForms'); ?></option>
          <?php foreach($prepop as $label => $pop){ ?>
          <option value='<?php echo str_replace("'", '&#145;', json_encode($pop)) ?>'><?php echo $label ?></option>
          <?php }?>
          </select><?php */ ?>

                                        <input class='frm-bulk-select-class' id='frm_bulk_options-select-<?php echo $field['id']; ?>' value=' ' type='hidden' onchange='arfstorebulkoptionvalue("<?php echo $field['id']; ?>", this.value)'>
                                        <dl class='arf_selectbox' data-name='frm_bulk_options-select-<?php echo $field['id']; ?>' data-id='frm_bulk_options-select-<?php echo $field['id']; ?>' style='width:200px;margin-right:30px;float:left;'>
                                            <dt><span><?php _e('Select', 'ARForms'); ?></span>
                                            <input value='' style='display:none;width:128px;' class='arf_autocomplete' type='text'>
                                            <i class='fa fa-caret-down fa-lg'></i></dt>
                                            <dd>
                                                <ul style='display: none;' data-id='frm_bulk_options-select-<?php echo $field['id']; ?>'>
                                                    <li class='arf_selectbox_option' data-value='' data-label='<?php _e('Select', 'ARForms'); ?>'><?php _e('Select', 'ARForms'); ?></li>
        <?php foreach ($prepop as $label => $pop) { ?>
                                                        <li class='arf_selectbox_option' data-value='<?php echo str_replace("'", '&#145;', json_encode($pop)) ?>' data-label='<?php echo $label ?>'><?php echo $label ?></li>
                                    <?php } ?>                            
                                                </ul>
                                            </dd>
                                        </dl>

                                        <textarea name="frm_bulk_options-<?php echo $field['id']; ?>" class="frm_bulk_options_text_size" id="frm_bulk_options_sel-<?php echo $field['id']; ?>" style="height:0px;width:0px;float:right; visibility:hidden;"></textarea>
                                        <button onClick="arfupdatebulkoptions(<?php echo $field['id'] ?>, jQuery('#frm_bulk_options_sel-<?php echo $field['id']; ?>').val());
                                                return false;" class="btn_apply" style="margin-right:5px;"><?php _e('Apply', 'ARForms') ?></button>
                                    </div>
                                    <div id="arfshowfieldbulkoptions_success-<?php echo $field['id']; ?>" style="padding-top:8px; font-size:12px; font-weight:bold; float:left; display:none;"><?php _e('Saving', 'ARForms'); ?>...</div>

            <?php } ?>        
                            </div>        
                        </div>
                        <div style="clear:both;"></div>
                        <div align="right" class="arfpopupclosediv"> <button type="button" class="arfpopupclose" data-dismiss="arfmodal" onclick="close_add_field_subject('arffieldoptions_<?php echo $field['id']; ?>')" ><?php _e('Done', 'ARForms'); ?></button> </div>
                    </div>
                </div>

            </div>

            <?php
            $field['prepop'] = $prepop;
        } else if ($field['type'] == 'captcha') {


            global $arfsettings;

            if ($field['is_recaptcha'] == 'custom-captcha') {
                $recaptcha_enable = "style=display:none;";
                $captcha_enable = "style=display:block;";
            } else {
                $recaptcha_enable = "style=display:block;";
                $captcha_enable = "style=display:none;";
            }
            ?>

            <img id="recaptcha_<?php echo $field['id']; ?>" src="<?php echo ARFURL ?>/images/<?php echo $arfsettings->re_theme ?>-captcha.png" alt="captcha" class="alignleft captcha_class" <?php echo $recaptcha_enable; ?>/>

            <div id="custom-captcha_<?php echo $field['id']; ?>" class="alignleft custom_captcha_div captcha_class" <?php echo $captcha_enable; ?>></div>

            <div style="clear:both"></div>


            <?php if (empty($arfsettings->pubkey) && $field['is_recaptcha'] != 'custom-captcha') { ?>


                <div class="howto" id="setup_captcha_message" style="font-weight:bold;color:red;line-height:1;font-size:11px;"><?php echo __('Please setup public and private key in Global Settings otherwise recaptcha will not appear', 'ARForms') ?></div>


            <?php } ?>


            <input type="hidden" name="<?php echo $field_name ?>" value="1"/>


            <?php
        } else if ($field['type'] == 'scale') {
            $range = $field['maxnum'];
            if ($range == "") {
                $range = "0";
            }
            for ($i = 0; $i < $range; $i++) {
                $field['options'][$i] = $i + 1;
            }
            $field['star_color'] = $field['star_color'] ? $field['star_color'] : 'yellow';
            $field['star_size'] = $field['star_size'] ? $field['star_size'] : 'small';
            require(VIEWS_PATH . '/star_rating.php');
        } else if ($field['type'] == 'like') {
            require(VIEWS_PATH . '/like_field.php');
        } else if ($field['type'] == 'slider') {

            $slider_class = 'slider_class';

            if ($field['slider_handle'] == 'square')
                $slider_class = 'slider_class2';
            else if ($field['slider_handle'] == 'triangle')
                $slider_class = 'slider_class3';
            ?>
            <div id="slider_sample_<?php echo $field['id']; ?>" class="<?php echo $slider_class; ?>"></div>
            <?php
        }else {


            do_action('arfdisplayaddedfields', $field);
        }





        if ($display['clear_on_focus']) {

            do_action('arfextrafield_displayoptions', $field);
        }
        ?>

        <div class="clear"></div>


    </div>


                <?php if ($display['options']) { ?>

        <div style="right:0; margin-top:-100px;" id="main_fieldoptions_modal_<?php echo $field['id']; ?>" class="main_fieldoptions_modal">
            <div id="field-option-<?php echo $field['id'] ?>" class="show-field-options arfmodal" style="display:none;margin-top:0px;top:0px;position:absolute;width:auto;min-width: 200px;">
                <div class="arfpopuparrow"></div>
                <div class="arfpopuparrow_right"></div>
                <div class="arfpopuparrow_bottom"></div>
                <div class="arfpopuparrow_right_bottom"></div>

                <div class="arfmodal-header"><div><?php _e('Field Options', 'ARForms'); ?>&nbsp;&nbsp;&nbsp;[ <?php echo __('Field ID', 'ARForms') . ": " . $arfieldhelper->get_actual_id($field['id']); ?> ]<button data-dismiss="arfmodal" onclick="close_add_field_subject('field-option-<?php echo $field['id']; ?>')" class="close" type="button" style="margin-top:-4px; opacity:1; filter:alpha(opacity=100); outline:none;"><img src="<?php echo ARFIMAGESURL . '/close-btn.png'; ?>" border="0" align="absmiddle" /></button></div></div>
                <div class="arfmodal-body_fieldoptions">

                                 <?php if ($field['type'] != 'hidden') {
                                     ?>
                        <div id="main_fieldoption_table" class="fields_table">  

                            <div class="fieldoptions_tab_main">
                                <div id="field_basic_option_tab" class="fieldoption_inner_tab_selected fieldoption_inner_tab" onclick="arf_open_field_option('<?php echo $field['id']; ?>', 'field_basic_option', '0');"><?php _e('Basic Options', 'ARForms') ?></div> 
                                <div id="field_custom_css_tab" class="fieldoption_inner_tab" onclick="arf_open_field_option('<?php echo $field['id']; ?>', 'field_custom_css', '0');" style=" <?php
                                    if (($field['is_recaptcha'] == 'recaptcha' and $field['type'] == 'captcha' ) || $field['type'] == 'break' || $field['type'] == 'imagecontrol') {
                                        echo 'display:none;';
                                    }
                                    ?>"><?php _e('CSS Property', 'ARForms') ?></div>
                                <div style=" <?php
                                    if ($field['type'] == 'imagecontrol') {
                                        echo 'display:none;';
                                    }
                                    ?>" id="field_conditional_law_tab" class="fieldoption_inner_tab" onclick="arf_open_field_option('<?php echo $field['id']; ?>', 'field_conditional_law', '0');"><?php _e('Conditional Law', 'ARForms') ?></div>
                            </div>

                            <!-- Basic Option Start New -->
                            <table class="field_basic_option arf_fieldoptiontab flip" border="0" cellpadding="0" cellspacing="0">
                                <tbody style="float:left;width:100%;clear:both;height:auto;">
                                    <?php
                                    $arffieldoptions = $arfieldhelper->arf_getfields_basic_options_section();
                                    foreach ($arffieldoptions as $arffieldoption => $options) {

                                        if ($arffieldoption == $field['type']) {

                                            $totaloptions = count($options);

                                            if ($totaloptions > 0) {
                                                $int = 1;
                                                $innercounter = 1;
                                                foreach ($options as $k => $v) {
                                                    if ($innercounter == 1)
                                                        echo "<tr class='arffieldoption_clear' style='clear:both;width:100%;float:left;height:auto;padding-bottom:10px;'>";

                                                    if ($k == "multicolsetting")
                                                        $mainwidth = "auto";
                                                    else if ($k == "htmlcontent")
                                                        $mainwidth = "100%";
                                                    else if ($k == "captchastyle")
                                                        $mainwidth = "33%";
                                                    else if ($field['type'] == 'captcha' && $k == "invalidmessage")
                                                        $mainwidth = "33%";
                                                    else if ($field['type'] == 'captcha' && $k == 'labelname')
                                                        $mainwidth = "29%";
                                                    else if ($field['type'] == 'date' && $k == "yearrange")
                                                        $mainwidth = "43%";
                                                    else if ($field['type'] == 'date' && $k == 'calendarhideshow')
                                                        $mainwidth = "27%";
                                                    else if ($field['type'] == 'date' && $k == "arf_prefix")
                                                        $mainwidth = "25%";
                                                    else if ($field['type'] == 'url' && $k == "invalidmessage")
                                                        $mainwidth = "29%";
                                                    else if ($field['type'] == 'divider' && ( $k == "fontfamilyoption" || $k == 'bgcoloroption' ))
                                                        $mainwidth = "32%";
                                                    else if ($field['type'] == 'divider' && ( $k == 'fontsizeoption' || $k == 'labelname' ))
                                                        $mainwidth = "30%";
                                                    else if ($field['type'] == 'url' && $k == "cleartextonfocus")
                                                        $mainwidth = "29%";
                                                    else if ($field['type'] == 'break' && $k == 'prevbtntext')
                                                        $mainwidth = "29%";
                                                    else if ($field['type'] == 'break')
                                                        $mainwidth = "33%";
                                                    else if ($k == "image_url")
                                                        $mainwidth = "100%";
                                                    else if ($k == "password_placeholder")
                                                        $mainwidth = "29%";
                                                    else if ($innercounter == 1)
                                                        $mainwidth = "29%";
                                                    else if ($field['type'] == 'password' && $k == "minlength")
                                                        $mainwidth = "22%";
                                                    else
                                                        $mainwidth = "33%";
                                                    $mainwidth = apply_filters('arf_set_field_width_in_outside', $mainwidth, $k, $field);

                                                    $arfhide = "";
                                                    if ($k == "multicolsetting" && ($field['type'] == 'break' || $field['type'] == 'divider' || $field['type'] == 'imagecontrol'))
                                                        $arfhide = "display:none;";

                                                    if (( $k == "labelname" || $k == "fielddescription" ) && $field['type'] == 'imagecontrol')
                                                        $arfhide = "display:none;";

                                                    $newfieldoptionclass = '';
                                                    if (( $field['type'] == 'radio' || $field['type'] == 'checkbox' || $field['type'] == 'file' || $field['type'] == 'select' ) && $k == "labelname")
                                                        $newfieldoptionclass = 'chk_opt_labelname';
                                                    ?>

                                                <td class="<?php echo $newfieldoptionclass; ?>" style="float:left; margin-right:10px; width:<?php echo $mainwidth; ?>; <?php echo $arfhide; ?> height:auto;">
                                                <?php echo $arfieldhelper->arf_get_field_option_value($field, $k); ?>
                                                </td>
                                                <?php
                                                if ($int % 3 == 0 && $totaloptions > $int) {
                                                    $innercounter = 0;
                                                    echo "</tr>";
                                                }
                                                ?>

                        <?php
                        $int ++;
                        $innercounter ++;
                    }
                }
                echo "</tr>";
            }
        }
        ?>
                                </tbody>
                            </table>
                            <!-- Basic Option Start New -->

                            <!-- css/property start --->
                            <div class="field_custom_css arf_fieldoptiontab" style="display:none;">
                                <div class="lblsubtitle1"><?php _e('CSS Custom Property', 'ARForms'); ?></div>
                                <div class="howto"><?php _e('Click on cloud buttons given below to copy your relevant css properties. Apply properties directly. Do not define class.', 'ARForms'); ?></div>
                                <div class="field_css">
                                    <div style="margin-top:20px;">
                                        <div class="lblsubtitle1" style="vertical-align:top; width:100px; float:left;"><?php _e('Custom CSS', 'ARForms'); ?></div>
                                        <div style="margin-left:100px;">
                                            <div id="custom_css_btns_<?php echo $field['id'] ?>" style="margin-bottom:20px;">
                                                <?php
                                                $prepost_fields = array('text', 'email', 'date', 'time', 'password', 'number', 'image', 'url', 'phone', 'number');
                                                if ($field['type'] == 'html') {
                                                    $custom_css_array = array(
                                                        'css_outer_wrapper' => __('Wrapper', 'ARForms'),
                                                    );
                                                } else {
                                                    $custom_css_array = array(
                                                        'css_outer_wrapper' => __('Wrapper', 'ARForms'),
                                                        'css_label' => __('Label', 'ARForms'),
                                                        'css_input_element' => __('Input element', 'ARForms'),
                                                        'css_description' => __('Description', 'ARForms'),
                                                    );
                                                }

                                                if ($field['type'] == 'like' || $field['type'] == 'slider' || $field['type'] == 'divider')
                                                    unset($custom_css_array['css_input_element']);

                                                if (in_array($field['type'], $prepost_fields)) {
                                                    $add_icon_array = array('css_add_icon' => __('Icon', 'ARForms'));
                                                    $custom_css_array['css_add_icon'] = __('Icon', 'ARForms');
                                                }

                                                $customcss_array = array();

                                                foreach ($custom_css_array as $custom_css_block => $custom_css_block_title) {
                                                    if (isset($field[$custom_css_block]) and $field[$custom_css_block] != '')
                                                        $customcss_array[$custom_css_block] = true;
                                                    else
                                                        $customcss_array[$custom_css_block] = false;
                                                }

                                                foreach ($custom_css_array as $custom_css_block => $custom_css_block_title) {
                                                    ?>
                                                    <button class="arfcustomcssbtn <?php
                                                    if ($customcss_array[$custom_css_block]) {
                                                        echo 'arfactive';
                                                    }
                                                    ?>" id="<?php echo $custom_css_block . '_' . $field['id']; ?>_btn" onclick="add_fieldcustom_css_block('<?php echo $field['id'] ?>', '<?php echo $custom_css_block; ?>', '<?php echo addslashes($custom_css_block_title); ?>');" type="button"><?php echo $custom_css_block_title; ?></button>&nbsp;&nbsp;
            <?php
        }
        ?>
                                            </div>
                                            <div id="custom_css_blocks_<?php echo $field['id'] ?>">
        <?php
        foreach ($custom_css_array as $custom_css_block => $custom_css_block_title) {
            if (isset($field[$custom_css_block]) and $field[$custom_css_block] != '') {

                echo '<div id="arf_' . $custom_css_block . '_' . $field['id'] . '" class="arf_form_custom_css_block">';
                echo '<div class="arf_form_css_tab"><div class="arf_form_custom_css_block_title">' . $custom_css_block_title . '</div></div>';
                echo '<div class="arf_form_custom_css_block_style"><textarea name="field_options[' . $custom_css_block . '_' . $field['id'] . ']" style="width:430px !important;" cols="50" rows="4" class="arfplacelonginput txtmultinew">' . stripslashes_deep($armainhelper->esc_textarea($arformcontroller->br2nl($field[$custom_css_block]))) . '</textarea></div>';
                echo '<div class="arfcustomcssclose" onclick="arf_fieldremove_css_block(\'' . $field['id'] . '\', \'' . $custom_css_block . '\');"></div><br/><div class="lblsubtitle" style="float:left;clear:both;">e.g. display:block;</div></div>';
            }
        }
        ?>
                                            </div>
                                            <div>
                                            </div>
                                        </div>        
                                    </div>
                                </div>

                            </div>
                            <!-- css/property end --->

                            <!-- conditional logic div start -->
                            <div class="field_conditional_law arf_fieldoptiontab" style="display:none;">
                                <div class="howto"><?php _e('Set conditional logic to show / hide this field by applying rules which will depend on the user\'s input. You can add as many conditions as you needed to match your criteria.', 'ARForms'); ?></div>
                                                <?php
                                                if ($field['type'] != 'hidden') { //---------- for conditional logic ----------// 
                                                    $cl_rules_array = ( isset($field['conditional_logic']['rules']) ) ? $field['conditional_logic']['rules'] : array();
                                                    ?>
                                    <div style=" <?php echo isset($padding) ? $padding : ''; ?> padding-top:10px; width:90%;">

                                        <div style="width:auto; font-size:14px; text-align:left; padding:5px; display:none; "><input type="checkbox" class="chkstanard" name="conditional_logic_<?php echo $field['id'] ?>" id="conditional_logic_<?php echo $field['id'] ?>" onchange="arf_cl_change('<?php echo $field['id']; ?>');" value="<?php echo $field['conditional_logic']['enable']; ?>" <?php checked($field['conditional_logic']['enable'], 1) ?> /><label for="conditional_logic_<?php echo $field['id'] ?>"><span></span><?php _e('Enable Conditional Law', 'ARForms'); ?></label></div>
                                        <!-- main div start -->

                                        <div id="conditional_logic_div_<?php echo $field['id'] ?>" style="width:auto; margin-bottom:20px; font-size:14px;">
                                            <!-- main condition div -->
                                            <div class="arflabeltitle">

                                                <div class="sltstandard<?php
                                                                if (count($cl_rules_array) == 0) {
                                                                    echo ' arfhelptip';
                                                                }
                                                                ?>" <?php if (count($cl_rules_array) == 0) { ?>title="<?php _e('Please add one or more rules', 'ARForms'); ?>"<?php } ?> style="float:none;display:inline-block;margin-right:15px;">

                                                        <?php /* ?><select name="conditional_logic_display_<?php echo $field['id']; ?>" id="conditional_logic_display_<?php echo $field['id']; ?>" data-width="80px" <?php if( count($cl_rules_array) == 0 ){ ?>disabled="disabled"<?php }?> >
                                                          <option value="show" <?php if( isset($field['conditional_logic']['display']) ) { selected($field['conditional_logic']['display'], 'show'); } ?>><?php _e('Show', 'ARForms'); ?></option>
                                                          <option value="hide" <?php if( isset($field['conditional_logic']['display']) ) { selected($field['conditional_logic']['display'], 'hide'); } ?>><?php _e('Hide', 'ARForms'); ?></option>
                                                          </select><?php */ ?>

                                                    <input id="conditional_logic_display_<?php echo $field['id']; ?>" name="conditional_logic_display_<?php echo $field['id']; ?>" value="<?php
                                                   if ($field['conditional_logic']['display'] == 'hide') {
                                                       echo 'hide';
                                                   } else {
                                                       echo 'show';
                                                   }
                                                   ?>" type="hidden" <?php if (count($cl_rules_array) == 0) { ?>disabled="disabled"<?php } ?>>
                                                    <dl class="arf_selectbox" data-name="conditional_logic_display_<?php echo $field['id']; ?>" data-id="conditional_logic_display_<?php echo $field['id']; ?>" style="width:60px;">
                                                        <dt class="<?php
                                                   if (count($cl_rules_array) == 0) {
                                                       echo "arf_disable_selectbox";
                                                   }
                                                   ?>"><span><?php
                                                     if ($field['conditional_logic']['display'] == 'hide') {
                                                         echo __('Hide', 'ARForms');
                                                     } else {
                                                         echo __('Show', 'ARForms');
                                                     }
                                                     ?></span>
                                                        <input value="<?php
                                        if ($field['conditional_logic']['display'] == 'hide') {
                                            echo __('Hide', 'ARForms');
                                        } else {
                                            echo __('Show', 'ARForms');
                                        }
                                                     ?>" style="display:none;width:48px;" class="arf_autocomplete" type="text">
                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                        <dd>
                                                            <ul style="display: none;" data-id="conditional_logic_display_<?php echo $field['id']; ?>">
                                                                <li class="arf_selectbox_option" data-value="show" data-label="<?php _e('Show', 'ARForms'); ?>"><?php _e('Show', 'ARForms'); ?></li>
                                                                <li class="arf_selectbox_option" data-value="hide" data-label="<?php _e('Hide', 'ARForms'); ?>"><?php _e('Hide', 'ARForms'); ?></li>

                                                            </ul>
                                                        </dd>
                                                    </dl>

                                                </div>
                                                <span class="arfconditional_desc_text">&nbsp;<?php _e('this field if', 'ARForms'); ?>&nbsp;</span>
                                                <div class="sltstandard<?php
                                                   if (count($cl_rules_array) == 0) {
                                                       echo ' arfhelptip';
                                                   }
                                                   ?>" <?php if (count($cl_rules_array) == 0) { ?>title="<?php _e('Please add one or more rules', 'ARForms'); ?>"<?php } ?> style="float:none;display:inline-block;margin-right:15px;">

            <?php /* ?><select name="conditional_logic_if_cond_<?php echo $field['id']; ?>" id="conditional_logic_if_cond_<?php echo $field['id']; ?>" data-width="80px" <?php if( count($cl_rules_array) == 0 ){ ?>disabled="disabled"<?php }?> >
              <option value="all" <?php if( isset($field['conditional_logic']['if_cond']) ) { selected($field['conditional_logic']['if_cond'], 'all'); } ?>><?php _e('All', 'ARForms'); ?></option>
              <option value="any" <?php if( isset($field['conditional_logic']['if_cond']) ) { selected($field['conditional_logic']['if_cond'], 'any'); } ?>><?php _e('Any', 'ARForms'); ?></option>
              </select><?php */ ?>

                                                    <input id="conditional_logic_if_cond_<?php echo $field['id']; ?>" name="conditional_logic_if_cond_<?php echo $field['id']; ?>" value="<?php
            if ($field['conditional_logic']['if_cond'] == 'any') {
                echo 'any';
            } else {
                echo 'all';
            }
            ?>" type="hidden" <?php if (count($cl_rules_array) == 0) { ?>disabled="disabled"<?php } ?>>
                                                    <dl class="arf_selectbox" data-name="conditional_logic_if_cond_<?php echo $field['id']; ?>" data-id="conditional_logic_if_cond_<?php echo $field['id']; ?>" style="width:60px;">
                                                        <dt class="<?php
                                if (count($cl_rules_array) == 0) {
                                    echo "arf_disable_selectbox";
                                }
            ?>"><span><?php
                                            if ($field['conditional_logic']['if_cond'] == 'any') {
                                                echo __('Any', 'ARForms');
                                            } else {
                                                echo __('All', 'ARForms');
                                            }
                                            ?></span>
                                                        <input value="<?php
                                     if ($field['conditional_logic']['if_cond'] == 'any') {
                                         echo __('Any', 'ARForms');
                                     } else {
                                         echo __('All', 'ARForms');
                                     }
                                     ?>" style="display:none;width:48px;" class="arf_autocomplete" type="text">
                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                        <dd>
                                                            <ul style="display: none;" data-id="conditional_logic_if_cond_<?php echo $field['id']; ?>">
                                                                <li class="arf_selectbox_option" data-value="all" data-label="<?php _e('All', 'ARForms'); ?>"><?php _e('All', 'ARForms'); ?></li>
                                                                <li class="arf_selectbox_option" data-value="any" data-label="<?php _e('Any', 'ARForms'); ?>"><?php _e('Any', 'ARForms'); ?></li>

                                                            </ul>
                                                        </dd>
                                                    </dl>

                                                </div>
                                                <span class="arfconditional_desc_text">&nbsp;<?php _e('of the following match:', 'ARForms'); ?>&nbsp;</span>

                                            </div>
                                            <!-- main condition div -->

                                            <!-- logic rules start -->
                                            <button type="button" id="arf_new_law_<?php echo $field['id']; ?>" onclick="arf_add_new_law('<?php echo $field['id']; ?>');" style=" <?php
                                    if ($field['conditional_logic']['enable'] == 1) {
                                        echo 'display:none;';
                                    }
                                    ?>" class="greensavebtn arfaddnewrule"><?php _e('Add New Law', 'ARForms'); ?></button>

                                            <div id="logic_rules_div_<?php echo $field['id']; ?>" style=" <?php
                            if ($field['conditional_logic']['enable'] == 0) {
                                echo 'display:none;';
                            } else {
                                echo "display:inline-block;";
                            }
                            ?>" class="logic_rules_div">
                        <?php
                        if (count($cl_rules_array) > 0) {
                            $rule_i = 1;
                            foreach ($cl_rules_array as $rule) {
                                ?>
                                                        <div id="arf_cl_rule_<?php echo $field['id'] . '_' . $rule_i; ?>" class="cl_rules">
                                                            <input type="hidden" name="rule_array_<?php echo $field['id']; ?>[]" value="<?php echo $rule_i; ?>" />
                                                            <div class="sltstandard" style="float:none;display:inline-block;margin-right:10px;"><?php echo $arfieldhelper->arf_cl_field_menu($field['form_id'], 'arf_cl_field_' . $field['id'] . '_' . $rule_i, 'arf_cl_field_' . $field['id'] . '_' . $rule_i, $rule['field_id']); ?></div>
                                                            &nbsp;
                                                            <div class="sltstandard" style="float:none;display:inline-block;margin-right:10px;"><?php echo $arfieldhelper->arf_cl_rule_menu('arf_cl_op_' . $field['id'] . '_' . $rule_i, 'arf_cl_op_' . $field['id'] . '_' . $rule_i, $rule['operator']); ?></div>
                                                            &nbsp;
                                                            <input type="text" name="cl_rule_value_<?php echo $field['id'] . '_' . $rule_i; ?>" id="cl_rule_value_<?php echo $field['id'] . '_' . $rule_i; ?>" class="txtstandardnew" value="<?php echo esc_attr($rule['value']); ?>" />
                                                            &nbsp;
                                                            <span class="bulk_add_remove">
                                                                <span onclick="add_new_rule('<?php echo $field['id']; ?>');" class="bulk_add">&nbsp;</span>
                                                                <span onclick="delete_rule('<?php echo $field['id']; ?>', '<?php echo $rule_i; ?>');" class="bulk_remove">&nbsp;</span>
                                                            </span>

                                                        </div>
                                                <?php
                                                $rule_i++;
                                            }
                                        }
                                        ?>
                                            </div>    
                                            <!-- logic rules end -->

                                        </div>
                                        <!-- main div end -->
                                    </div>
                                    <?php } ?>
                            </div>
                            <!-- conditional logic div end -->

                        </div>
                                <?php } ?>

                    <div style="clear:both;"></div>
                    <div align="right" class="arfpopupclosediv"> <button type="button" class="arfpopupclose" data-dismiss="arfmodal" onclick="close_add_field_subject('field-option-<?php echo $field['id']; ?>')" ><?php _e('Done', 'ARForms'); ?></button> </div>

                </div>
                            <?php } ?>    
                            <?php /* ARForms Prefix Postfix Modal */ ?>
            <div class="arf_prefix_postfix_wrapper" id="arf_prefix_postfix_wrapper" style="display:none;float:left;width:100%; background:rgba(0,0,0,0.1);border-radius:6px;height:100%;text-align:center;position:absolute;top:0;">
                <div id="field_prefix_suffix" class="arfprefixsuffix" style="width:470px;height:205px; margin:15% auto auto;">
                    <div class="arfmodal-header" style="background:#1bbae1;height:50px;padding:0 15px;">
                        <div style="padding-top:10px;font-size:18px;color:#3e6289;float:left;line-height:30px;">
                            <div class="arf_modal_title_new"><?php _e('Select Prefix / Suffix', 'ARForms'); ?></div>
                        </div>
                        <div style="float:right; padding-top:10px; cursor:pointer;" data-id='<?php echo $field['id']; ?>' id='arfprefixpostfixmodalclosenew'><img src="<?php echo ARFURL . '/images/close-button2.png'; ?>" align="absmiddle" /></div>
                    </div>
                    <div class="arfmodal-body" style="height:135px;overflow:hidden;clear:both;padding:10px 10px;">
                        <table width="100%" cellpadding="5" cellspacing="0" border="0" style="padding:0">
                            <tr>
                                <th width="50%" colspan="2"> <?php _e('Prefix', 'ARForms'); ?> </th>
                                <th width="50%" colspan="2"> <?php _e('Suffix', 'ARForms'); ?> </th>
                            </tr>
                            <tr>
                                <th colspan="2" id="enable_prefix"><label><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="arf_prefix" id="arf_prefix" data-id='<?php echo $field['id']; ?>'  <?php echo ($field['enable_arf_prefix']) ? 'checked="checked"' : ''; ?> value="1" onchange="javascript:arfchangeprefix(this.checked, this);" /><label class="lblswitch"><span>&nbsp;YES</span></label></th>
                                <th colspan="2" id="enable_suffix"><label><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="arf_suffix" id="arf_suffix" data-id='<?php echo $field['id']; ?>'  <?php echo ($field['enable_arf_suffix']) ? 'checked="checked"' : ''; ?> value="1" onchange="javascript:arfchangesuffix(this.checked, this);" /><label class="lblswitch"><span>&nbsp;YES</span></label></th>
                            </tr>
                            <?php /* ?><tr>
                              <th><?php _e('Background','ARForms'); ?></th>
                              <th id="prefix_bgcolor">
                              <div class="arf_prefix_suffix_sub">
                              <div class="arf_prefix_suffix_sub_options arfhex" data-id='<?php echo $field['id']; ?>' data-fid="arfprefixcolorsetting" ></div>
                              <div class="arf_prefix_suffix_subarrow_bg">
                              <div class="arf_prefix_suffix_subarrow"></div>
                              </div>
                              </div>

                              <input type="hidden" name="arfprefixcolorsetting" id="arfprefixcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfprefixcolorsetting']) ?>" style="width:100px;" /></th>
                              <th><?php _e('Background','ARForms'); ?></th>
                              <th id="suffix_bgcolor">
                              <div class="arf_prefix_suffix_sub">
                              <div class="arf_prefix_suffix_sub_options arfhex" data-id='<?php echo $field['id']; ?>' data-fid="arfsuffixcolorsetting" ></div>
                              <div class="arf_prefix_suffix_subarrow_bg">
                              <div class="arf_prefix_suffix_subarrow"></div>
                              </div>
                              </div>

                              <input type="hidden" name="arfsuffixcolorsetting" id="arfsuffixcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfprefixcolorsetting']) ?>" style="width:100px;" />
                              </th>
                              </tr><?php */ ?>
<?php /* ?><tr>
  <th><?php _e('Icon Color','ARForms'); ?></th>
  <th id="prefix_iconcolor">
  <div class="arf_prefix_suffix_sub">
  <div class="arf_prefix_suffix_sub_options arfhex" data-id='<?php echo $field['id']; ?>' data-fid="arfprefixiconcolorsetting" ></div>
  <div class="arf_prefix_suffix_subarrow_bg">
  <div class="arf_prefix_suffix_subarrow"></div>
  </div>
  </div>

  <input type="hidden" name="arfprefixiconcolorsetting" id="arfprefixiconcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfprefixcolorsetting']) ?>" style="width:100px;" /></th>
  <th><?php _e('Icon Color','ARForms'); ?></th>
  <th id="suffix_iconcolor">
  <div class="arf_prefix_suffix_sub">
  <div class="arf_prefix_suffix_sub_options arfhex" data-id='<?php echo $field['id']; ?>' data-fid="arfsuffixiconcolorsetting" ></div>
  <div class="arf_prefix_suffix_subarrow_bg">
  <div class="arf_prefix_suffix_subarrow"></div>
  </div>
  </div>

  <input type="hidden" name="arfsuffixiconcolorsetting" id="arfsuffixiconcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfprefixcolorsetting']) ?>" style="width:100px;" />
  </th>
  </tr><?php */ ?>
                            <tr>
                                <th align="right" colspan="2" id="prefix_font_icons">
                                    <input type="text" name="arfprefixicon" readonly="readonly" data-toggle="arfmodal" href="#arf_fontawesome_modal" onclick="javascript:showfontawesomemodal(this, 'prefix');" id="arfprefixicon" class="txtxbox_widget" value="" data-id="<?php echo $field['id']; ?>" />
                                </th>
                                <th align="right" colspan="2" id="suffix_font_icons">
                                    <input type="text" name="arfsuffixicon" readonly="readonly" data-toggle="arfmodal" href="#arf_fontawesome_modal" onclick="javascript:showfontawesomemodal(this, 'suffix');" id="arfsuffixicon" class="txtxbox_widget" value="" data-id="<?php echo $field['id']; ?>" />
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4" align="right">
                                    <button id="arfprefixpostfixmodalclose" data-id='<?php echo $field['id']; ?>' class="arfpopupclose" type="button"><?php _e('Done', 'ARForms'); ?></button>
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
<?php /* ARForms Prefix Postfix Modal */ ?>
        </div>

        <input type="hidden" id="field_type_<?php echo $field['id']; ?>" data-fid="<?php echo $arfieldhelper->get_actual_id($field['id']); ?>" value="<?php echo $field['type']; ?>" />
        <input type="hidden" id="field_ref_<?php echo $arfieldhelper->get_actual_id($field['id']); ?>" value="<?php echo $field['id']; ?>" />
        <input type="hidden" name="field_options[field_key_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo esc_attr($field['field_key']); ?>" size="20" />
    </div>

</li>

<?php unset($display); ?>