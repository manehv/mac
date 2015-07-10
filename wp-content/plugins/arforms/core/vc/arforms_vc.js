jQuery(document).ready(function () {

    jQuery('.ARForms_Popup_Shortode_arfield').each(function () {
        var fild_value = jQuery(this).val();
        var fild_name = jQuery(this).attr('id');


        if (fild_name == 'id') {
            jQuery('#arfaddformid_vc_popup option[value="' + fild_value + '"]').prop('selected', true);
            jQuery('input#Arf_param_id').val(fild_value);
        }

        if (fild_name == 'shortcode_type') {
            if (fild_value == 'normal') {
                jQuery('#shortcode_type_normal_vc').attr('checked', true);
                jQuery('#show_link_inner').slideDown();
                jQuery('#show_link_type_vc').slideUp(700);
                jQuery("#arf_shortcode_type").val(fild_value);
            }
            if (fild_value == 'popup') {
                jQuery('#shortcode_type_popup_vc').attr('checked', true);
                jQuery('#show_link_inner').slideUp();
                jQuery('#show_link_type_vc').slideDown(700);
                jQuery("#arf_shortcode_type").val(fild_value);
            }
        }

        if (fild_name == 'type') {
            jQuery('#link_type_vc option[value="' + fild_value + '"]').prop('selected', true);
            arf_set_link_type_data(fild_value);
        }

        if (fild_name == 'position') {
            jQuery('select#link_position_vc').find('option').each(function () {
                if (jQuery(this).attr('value') == fild_value)
                    jQuery(this).attr('selected', true);
                else
                    jQuery(this).attr('selected', false);
            });
            jQuery(".sltmodal select").selectpicker('refresh');
        }

        if (fild_name == 'desc') {
            jQuery("input#short_caption").val(fild_value);
        }
        if (fild_name == 'width') {
            jQuery("input#modal_width").val(fild_value);
        }
        if (fild_name == 'height') {
            jQuery("input#modal_height").val(fild_value);
        }


        if (fild_name == 'angle') {
            jQuery('#button_angle option[value="' + fild_value + '"]').prop('selected', true);
        }

        if (fild_name == 'bgcolor' || fild_name == 'txtcolor') {
            arf_load_vc_colpick();
        }

        if (fild_name == 'bgcolor') {
            jQuery('.arfhex[data-fid="arf_modal_btn_bg_color.wpb_vc_param_value"]').css('background', fild_value);
            jQuery("input#arf_modal_btn_bg_color").val(fild_value);
        }

        if (fild_name == 'txtcolor') {
            jQuery('.arfhex[data-fid="arf_modal_btn_txt_color.wpb_vc_param_value"]').css('background', fild_value);
            jQuery("input#arf_modal_btn_txt_color").val(fild_value);
        }
    });


    jQuery('.vc_panel-btn-save, .wpb_save_edit_form').click(function () {
        var form_id = jQuery("#arfaddformid_vc_popup").val();
        if (form_id == '') {
            alert(jQuery("#arf_blank_forms_msg").val());
            return false;
        }
    });


    jQuery(".sltmodal select").selectpicker();

    jQuery('#shortcode_type_popup_vc').click(function () {
        jQuery('#show_link_inner').slideUp();
        jQuery('#show_link_type_vc').slideDown(700);
        jQuery("#arf_shortcode_type").val(jQuery(this).val());
    });
    jQuery('#shortcode_type_normal_vc').click(function () {
        jQuery('#show_link_inner').slideDown();
        jQuery('#show_link_type_vc').slideUp(700);
        jQuery("#arf_shortcode_type").val(jQuery(this).val());
    });


    jQuery('#link_type_vc').change(function () {
        var show_link_type = jQuery('#link_type_vc').val();
        arf_set_link_type_data(show_link_type);
    });

    jQuery('#arfaddformid_vc').change(function () {
        var arformid = jQuery(this).val();
        if (arformid) {
            jQuery(".wpb_vc_param_value").val(arformid);
        }
    });

    jQuery('#arfaddformid_vc_popup').change(function () {
        var arformid = jQuery(this).val();
        if (arformid) {
            jQuery("#Arf_param_id").val(arformid);
        }
    });

});

function changeflybutton()
{
    var angle = jQuery('#button_angle').val();
    angle = angle != '' ? angle : 0;
    jQuery('.arf_fly_btn').css('transform', 'rotate(' + angle + 'deg)');
}
function arfchangeflybtn()
{
    if (jQuery('#link_position_fly').val() == 'right') {
        jQuery('.arfbtnleft').hide();
        jQuery('.arfbtnright').show();
    } else {
        jQuery('.arfbtnleft').show();
        jQuery('.arfbtnright').hide();
    }
}


/***************/

function changetopposition(myval) {
    var modalheight = jQuery(window).height();
    var top_height = Number(modalheight) / 2;

    if (myval == "fly")
        jQuery('#arfinsertform').css('top', (top_height - 230) + 'px');
    else
        jQuery('#arfinsertform').css('top', (top_height - 180) + 'px');
}


function arf_set_link_type_data(show_link_type) {

    var tid = jQuery('.arfmodal_vcfields #arf_btn_txtcolor .arf_coloroption.arfhex').attr('data-fid');
    jQuery('#' + tid).val('#ffffff');

    var link_sticky_html = '';
    var link_fly_html = '';

    var top_label = (typeof __LINK_POSITION_TOP !== undefined) ? __LINK_POSITION_TOP : 'Top';
    var bottom_label = (typeof __LINK_POSITION_BOTTOM !== undefined) ? __LINK_POSITION_BOTTOM : 'Bottom';
    var left_label = (typeof __LINK_POSITION_LEFT !== undefined) ? __LINK_POSITION_LEFT : 'Left';
    var right_label = (typeof __LINK_POSITION_RIGHT !== undefined) ? __LINK_POSITION_RIGHT : 'Right';

    link_sticky_html += "<option value='top' selected='selected'>" + top_label + "</option>";
    link_sticky_html += "<option value='bottom'>" + bottom_label + "</option>";
    link_sticky_html += "<option value='left'>" + left_label + "</option>";
    link_sticky_html += "<option value='right'>" + right_label + "</option>";

    link_fly_html += "<option value='left' selected='selected'>" + left_label + "</option>";
    link_fly_html += "<option value='right'>" + right_label + "</option>";

    if (show_link_type == 'sticky')
    {
        jQuery('#is_sticky_vc').slideDown();
//        jQuery('#is_fly_vc').slideUp();
//        jQuery('select#link_position_fly').attr('disabled',true);
//        jQuery('select#link_position_vc').attr('disabled',false);
        jQuery('select#link_position_vc').html(link_sticky_html);
        jQuery('#button_angle_div_vc').slideUp();
        jQuery(".sltmodal select").selectpicker('refresh');
        jQuery('.arfmodal_vcfields#arfmodalbuttonstyles').slideDown();
        jQuery(".arfmodal_vcfields #arf_btn_bgcolor .arf_coloroption.arfhex").css('background', '#93979d');
        var fid = jQuery('.arfmodal_vcfields #arf_btn_bgcolor .arf_coloroption.arfhex').attr('data-fid');
        jQuery('#' + fid).val('#93979d');
    }
    else if (show_link_type == 'fly')
    {
//        jQuery('#is_fly_vc').slideDown();
        jQuery('#is_sticky_vc').slideDown();

//        jQuery('select#link_position_vc').attr('disabled',true);
//        jQuery('select#link_position_fly').attr('disabled',false);
        jQuery('select#link_position_vc').html(link_fly_html);
        jQuery(".sltmodal select").selectpicker('refresh');
        jQuery('#button_angle_div_vc').slideDown();
        jQuery('.arfmodal_vcfields#arfmodalbuttonstyles').slideDown();
        jQuery(".arfmodal_vcfields #arf_btn_bgcolor .arf_coloroption.arfhex").css('background', '#2d6dae');
        var fid = jQuery('.arfmodal_vcfields #arf_btn_bgcolor .arf_coloroption.arfhex').attr('data-fid');
        jQuery('#' + fid).val('#2d6dae');
    }
    else
    {
        jQuery('#is_sticky_vc').slideUp();
        //jQuery('#is_fly_vc').slideUp();
        //jQuery('select#link_position_vc').attr('disabled',true);
        //jQuery('select#link_position_fly').attr('disabled',true);
        jQuery('#button_angle_div_vc').slideUp();
        jQuery('.arfmodal_vcfields#arfmodalbuttonstyles').slideUp();
    }


    if (show_link_type == 'onload') {
        jQuery('#shortcode_caption_vc').slideUp();
    } else {
        jQuery('#shortcode_caption_vc').slideDown();
    }
}

function showarfpopupfieldlist()
{
    var fild_value = jQuery('input[name="shortcode_type"]:checked').val();
    var fild_name = 'shortcode_type';

    if (fild_name == 'id') {
        jQuery('#arfaddformid_vc_popup option[value="' + fild_value + '"]').prop('selected', true);
        jQuery('input#Arf_param_id').val(fild_value);
    }

    if (fild_name == 'shortcode_type') {
        if (fild_value == 'normal') {
            jQuery('#shortcode_type_normal_vc').attr('checked', true);
            jQuery('#show_link_inner').slideDown();
            jQuery('#show_link_type_vc').slideUp(700);
            jQuery("#arf_shortcode_type").val(fild_value);
        }
        if (fild_value == 'popup') {
            jQuery('#shortcode_type_popup_vc').attr('checked', true);
            jQuery('#show_link_inner').slideUp();
            jQuery('#show_link_type_vc').slideDown(700);
            jQuery("#arf_shortcode_type").val(fild_value);

        }
    }

}

function set_arfaddformid_vc_popup(id)
{
    if (id) {
        jQuery("#Arf_param_id").val(id);
    }
}

jQuery('#link_position_fly').change(function () {
    var position = jQuery(this).val();

    var color = (position == 'left') ? '#2d6dae' : '#8ccf7a';

    jQuery(".arfmodal_vcfields #arf_btn_bgcolor .arf_coloroption.arfhex").css('background', color);
    var fid = jQuery('.arfmodal_vcfields #arf_btn_bgcolor .arf_coloroption.arfhex').attr('data-fid');
    jQuery('#' + fid).val(color);

});

jQuery('#link_position_vc').change(function () {
    var position = jQuery(this).val();
    var color = (['left', 'right', 'bottom'].indexOf(position) > -1) ? '#1bbae1' : '#93979d';

    jQuery(".arfmodal_vcfields #arf_btn_bgcolor .arf_coloroption.arfhex").css('background', color);
    var fid = jQuery('.arfmodal_vcfields #arf_btn_bgcolor .arf_coloroption.arfhex').attr('data-fid');
    jQuery('#' + fid).val(color);
});
function arf_load_vc_colpick() {
    jQuery('.arfmodal_vcfields .arf_coloroption_sub:not(.arf_clr_disable)').colpick({
        layout: 'hex',
        submit: 0,
        onBeforeShow: function () {
            var fid = jQuery(this).find('.arfhex').attr('data-fid');
            var color = jQuery('#' + fid).val();

            if (jQuery(this).attr('data-cls') == 'arf_clr_disable') {
                jQuery('.arf_clr_disable .arfhex').css('background', color);
            }
            var new_color = color.replace('#', '');
            if (new_color)
                jQuery(this).colpickSetColor(new_color);
        },
        onChange: function (hsb, hex, rgb, el, bySetColor) {

            if (jQuery(el).attr('data-cls') == 'arf_clr_disable') {
                jQuery('.arf_clr_disable .arfhex').css('background', '#' + hex);
            }
            if (typeof arf_set_on_chnage_color_value_in_out_site == 'function') {
                arf_set_on_chnage_color_value_in_out_site(hsb, hex, rgb, el, bySetColor);
            }

            jQuery(el).find('.arfhex').css('background', '#' + hex);
            if (!bySetColor)
                jQuery(el).val(hex);
            var fid = jQuery(el).find('.arfhex').attr('data-fid');
            if (fid)
                jQuery('#' + fid).val('#' + hex);
        }
    });
}