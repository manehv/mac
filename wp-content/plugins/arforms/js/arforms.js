jQuery(document).ready(function ($)
{
    if (jQuery.isFunction(jQuery().selectpicker))
    {
        setTimeout(function () {
            $(".sltstandard_front select").selectpicker();
        }, 100);
    }

    jQuery(".vpb_captcha").keypress(function () {
        jQuery(this).parents('.arfformfield').find('.popover').remove();
        jQuery(this).removeClass('control-group');
        jQuery(this).removeClass('arf_error');
    });

    var trigger = $('.arfblankfield').closest('.toggle_container').prev('.trigger_style');
    if (trigger)
        arfsectionswap(trigger);

    jQuery('.original').change(function () {

        var fileselect = jQuery(this).attr('id');

        var field_key_arr1 = jQuery(this).attr('id').split('field_');

        jQuery('#overlay_' + field_key_arr1[1]).val(jQuery(this).val().replace(/^.*[\\\/]/, ''));
    });

    jQuery('.rate_widget').each(function (i) {

        widget_id = jQuery(this).attr('id');

        jQuery('.ratings_stars').hover(
                function () {
                    var color = jQuery(this).attr('data-color');
                    var datasize = jQuery(this).attr('data-size');
                    jQuery(this).prevAll().andSelf().addClass('ratings_over_' + color + datasize);
                    jQuery(this).nextAll().removeClass('ratings_vote_' + color + datasize);
                },
                function () {
                    var color = jQuery(this).attr('data-color');
                    var datasize = jQuery(this).attr('data-size');
                    jQuery(this).prevAll().andSelf().removeClass('ratings_over_' + color + datasize);
                    widget_id_new = jQuery(this).parent().attr('id');
                    set_votes(jQuery(this).parent(), widget_id_new);
                }
        );

        jQuery('.ratings_stars').bind('click', function () {
            var star = this;
            var widget = jQuery(this).parent();

            var clicked_data = {
                clicked_on: jQuery(star).attr('data-val'),
                widget_id: jQuery(star).parent().attr('id')
            };
            widget_id_new = jQuery(this).parent().attr('id');

            jQuery('#field_' + widget_id_new).val(clicked_data.clicked_on);
            jQuery('#field_' + widget_id_new).trigger('click');
            set_votes(widget, widget_id_new);
        });

    });

    jQuery('.arf_form input[type="checkbox"]').on('ifChanged', function (event) {
        jQuery(this).trigger('change');
    });

    jQuery('.arf_form input[type="radio"]').on('ifChecked', function (event) {
        jQuery(this).trigger('click');
    });

    jQuery('.arfpagebreakform').each(function () {
        var form_id = jQuery(this).find('input[name="form_id"]').val();
        var last_show_page = jQuery('#last_show_page_' + form_id).val();
        last_show_page = parseInt(last_show_page) + parseInt(1);
        if (last_show_page > 1) {
            jQuery(this).find('.page_break_nav').removeClass('arf_page_last');
            jQuery(this).find('#page_nav_' + last_show_page).addClass('arf_page_last');
        }
    });

    if (jQuery.isFunction(jQuery().progressbar)) {
        jQuery('form.arfpagebreakform').each(function () {
            var total_survey_page = jQuery(this).find('.total_survey_page').text();
            var current_page = 1;
            var completed = (current_page / total_survey_page) * 100;
            completed = completed.toFixed(2);
            completed = parseInt(completed);

            if (jQuery(this).find("#arf_progress_bar")) {
                jQuery(this).find("#arf_progress_bar").progressbar({
                    value: completed
                }).find('.ui-progressbar-value').html('<span class="ui-label">' + completed + '%</span>');
            }

        });
    }

    jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function () {
        var field = jQuery(this).attr('id');
        var field = field.replace('like_', 'field_');
        if (!jQuery("#" + field).is(':checked')) {
            jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
        }
    });

    jQuery('.arf_like').on("click", function () {

        var field = jQuery(this).attr('id');
        var field_data = field.split('-');

        var field_id = field_data[0];
        field_id = field_id.replace('field_', '');
        field_id = 'like_' + field_id;
        var like = field_data[1];

        if (like == 1) {
            jQuery('#' + field_id + '-0').removeClass('active');
            jQuery('#' + field_id + '-1').addClass('active');
        } else if (like == 0) {
            jQuery('#' + field_id + '-1').removeClass('active');
            jQuery('#' + field_id + '-0').addClass('active');
        }
    });

    jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').each(function () {
        var title = jQuery(this).attr('data-title');
        if (title !== undefined) {

            if (typeof jQuery(this).popover == 'function')
            {
                jQuery(this).popover({
                    html: true,
                    trigger: 'hover',
                    placement: 'top',
                    content: title,
                    title: '',
                    animation: false
                });
            }



            //jQuery(this).popover('show');
        }
    });

    jQuery('.arfhiddencolor').on('focus', function () {
        jQuery(this).parents('.arf_colorpicker_control').first().find('.arf_colorpicker, .arf_basic_colorpicker').first().trigger('click');
    });
    if (jQuery.isFunction(jQuery().colpick))
    {
        jQuery('.arf_colorpicker').colpick({
            layout: 'hex',
            submit: 0,
            color: 'ffffff',
            onBeforeShow: function () {
                var fid = jQuery(this).attr('id');
                var fid = fid.replace('arfcolorpicker_', '');
                var color = jQuery('#field_' + fid).val();
                var new_color = color.replace('#', '');
                if (new_color)
                    $(this).colpickSetColor(new_color);
            },
            onChange: function (hsb, hex, rgb, el, bySetColor) {
                var field_key = jQuery(el).attr('id');
                field_key = field_key.replace('arfcolorpicker_', '');
                jQuery('#field_' + field_key).val('#' + hex).trigger('change');
                jQuery(el).find('.arfcolorvalue').text('#' + hex);
                jQuery(el).find('.arfcolorvalue').css('background', '#' + hex);
                var arffontcolor = HextoHsl(hex) > 0.5 ? '#000000' : '#ffffff';
                jQuery(el).find('.arfcolorvalue').css('color', arffontcolor);

            }
        });
    }

    if (jQuery.isFunction(jQuery().simpleColorPicker))
    {
        jQuery('.arf_basic_colorpicker').simpleColorPicker({
            onChangeColor: function (color) {
                var field_key = jQuery(this).attr('id');
                field_key = field_key.replace('arfcolorpicker_', '');
                jQuery('#field_' + field_key).val(color).trigger('change');
                jQuery(this).find('.arfcolorvalue').text(color);
                jQuery(this).find('.arfcolorvalue').css('background', color);
                var hex = color.replace('#', '');
                var arffontcolor = HextoHsl(hex) > 0.5 ? '#000000' : '#ffffff';

                if (hex == "ffff00")
                {
                    arffontcolor = "#000000";
                }
                jQuery(this).find('.arfcolorvalue').css('color', arffontcolor);
            }
        });
    }

    jQuery('.arf_form .arf_running_total').each(function () {
        var mainstr = jQuery(this).attr('data-arfcalc');
        mainstr = mainstr ? mainstr : '';
        mainstr = mainstr.replace(/(\n|\r\n)/g, '');

        var validate_regex = /^[0-9 -/*\(\)]+$/i;

        //console.log(mainstr);
        if (mainstr != '' && validate_regex.test(mainstr)) {
            try {
                var value = eval(mainstr);
            } catch (e) {
            }
        } else {
            var value = 0;
        }

        value = value ? value : 0;
        value = value.toFixed(2);
        jQuery(this).html(value);
        var field_id = jQuery(this).attr('id');
        field_id = field_id.replace('arf_running_total_', '');
        jQuery('#arf_item_meta_' + field_id).val(value);
    });

    jQuery('div.arfmodal').each(function () {
        var modal_width = jQuery(this).css('width');
        jQuery(this).find('.arfmodal-body').attr('data-modalwidth', modal_width);
    });

    jQuery('div.arform_right_fly_form_block_right_main').each(function () {
        var modal_width = jQuery(this).css('width');
        jQuery(this).find('.arfmodal-body').attr('data-modalwidth', modal_width);
    });

    jQuery('div.arform_left_fly_form_block_left_main').each(function () {
        var modal_width = jQuery(this).css('width');
        jQuery(this).find('.arfmodal-body').attr('data-modalwidth', modal_width);
    });

    var width = jQuery(window).width() - 5;
    jQuery('div.arfmodal').each(function () {
        var modal_width = jQuery(this).outerWidth();
        var modalwidth = jQuery(this).find('.arfmodal-body').attr('data-modalwidth');
        if (modalwidth)
        {
            modalwidth = modalwidth.replace('px', '');

            var windowHeight = jQuery(window).height() + jQuery(window).scrollTop();
            if (modal_width > width && modalwidth >= modal_width)
            {
                jQuery(this).addClass('arfresponsivemodal');
                var setModalHeight = Number(windowHeight) - Number(60);

                //jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height",setModalHeight+"px");
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border", "0px");
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border-radius", "0px");
            }
            else
            {
                var mtop = jQuery(this).attr('data-mtop');
                var mleft = jQuery(this).attr('data-mleft');

                jQuery(this).css('top', mtop + 'px');
                jQuery(this).css('left', mleft + 'px');
                jQuery(this).removeClass('arfresponsivemodal');
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").removeAttr("style");
            }
        }
    });

    jQuery('div.arform_right_fly_form_block_right_main').each(function () {
        var modal_width = jQuery(this).outerWidth();
        var modalwidth = jQuery(this).find('.arfmodal-body').attr('data-modalwidth');
        if (modalwidth)
        {
            modalwidth = modalwidth.replace('px', '');

            var windowHeight = jQuery(window).height() + jQuery(window).scrollTop();
            if (modal_width > width && modalwidth >= modal_width)
            {

                jQuery(this).addClass('arfresponsivemodal');
                var setModalHeight = Number(windowHeight) - Number(60);


                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border", "0px");
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border-radius", "0px");
            }
            else
            {
                var mtop = jQuery(this).attr('data-mtop');
                var mleft = jQuery(this).attr('data-mleft');

                jQuery(this).css('top', mtop + 'px');
                jQuery(this).css('left', mleft + 'px');
                jQuery(this).removeClass('arfresponsivemodal');
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").removeAttr("style");
            }
        }
    });

    jQuery('div.arform_left_fly_form_block_left_main').each(function () {
        var modal_width = jQuery(this).outerWidth();
        var modalwidth = jQuery(this).find('.arfmodal-body').attr('data-modalwidth');
        if (modalwidth)
        {
            modalwidth = modalwidth.replace('px', '');

            var windowHeight = jQuery(window).height() + jQuery(window).scrollTop();
            if (modal_width > width && modalwidth >= modal_width)
            {

                jQuery(this).addClass('arfresponsivemodal');
                var setModalHeight = Number(windowHeight) - Number(60);


                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border", "0px");
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border-radius", "0px");
            }
            else
            {
                var mtop = jQuery(this).attr('data-mtop');
                var mleft = jQuery(this).attr('data-mleft');

                jQuery(this).css('top', mtop + 'px');
                jQuery(this).css('left', mleft + 'px');
                jQuery(this).removeClass('arfresponsivemodal');
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").removeAttr("style");
            }
        }
    });

    jQuery('.arfshowmainform:not(.arfpreivewform)').each(function () {

        setTimeout(function () {
            var width = jQuery(this).find('.arf_fieldset').width();
            jQuery(this).find('.arf_prefix_suffix_wrapper').css('max-width', width + 'px');
        }, 500);
    });
});

function arfremoveformentry(entry_id, ajax_url, prefix) {
    jQuery('#arfdelete_' + entry_id).replaceWith('<span class="arfloadingimg" id="arfdelete_' + entry_id + '"></span>');
    ajax_url = is_ssl_replace(ajax_url);
    jQuery.ajax({
        type: "POST", url: ajax_url,
        data: "controller=entries&arfaction=destroy&entry=" + entry_id,
        success: function (html) {
            if (html == 'success') {
                jQuery('#' + prefix + entry_id).fadeOut('slow');
            } else {
                jQuery('#arfdelete_' + entry_id).replaceWith(html);
            }
        }
    });
}

function arfcleardedaultvalueonfocus(default_value, thefield, default_blank)
{
    var default_value = default_value.replace(/(\n|\r\n)/g, '\r');
    var this_val = thefield.placeholder.replace(/(\n|\r\n)/g, '\r');

    if (this_val == default_value)
    {
        thefield.placeholder = '';
        jQuery(thefield).removeClass('arfdefault');
        if (thefield.value != "" && this_val == thefield.value)
        {
            thefield.value = '';
        }
    }
}

function arfreplacededaultvalueonfocus(default_value, thefield, default_blank)
{
    var default_value = default_value.replace(/(\n|\r\n)/g, '\r');

    if (thefield.placeholder == '')
    {
        thefield.placeholder = default_value;
        jQuery(thefield).addClass('arfdefault');
        if (thefield.value == "" && default_blank == '1')
        {
            thefield.value = default_value;
        }
    }
}

function reloadcapcha(object, key)
{
    if (jQuery(object).find('#vpb_captcha_code').length > 0)
    {
        document.getElementById("vpb_captcha_code").value = "", document.images['captchaimg_' + form_id].src = document.images['captchaimg_' + form_id].src.substring(0, document.images['captchaimg_' + form_id].src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000 + "&form_id=" + form_id;
    }
    else if (jQuery(object).find('#recaptcha_area').length)
    {
        var show_captcha = true;
        var captcha_key = key;
        Recaptcha.reload("t");
    }
}

function isJSON(MyStr) {
    try {
        //var MyJSON = JSON.stringify(MyStr);
        var json = JSON.parse(MyStr);

        if (typeof (MyStr) == 'string')
        {
            if (MyStr.length == 0) {
                return '';
            } else {
                return MyStr;
            }
        }
    }
    catch (e) {
        return '';
    }
}

function arfformsubmission(object, ajax_url, is_page_break)
{
    var main_css_url = jQuery('#main_css_url').val();
    var form_id = jQuery(object).find('#form_id').val();
    ajax_url = is_ssl_replace(ajax_url);
    jQuery.ajax({
        type: "POST", url: ajax_url,
        data: jQuery(object).serialize() + "&arfaction=create&using_ajax=yes",
        success: function (html) {
            jQuery('.frm_error_style').hide();

            var result = html;

            var res = '';
            res = isJSON(result);
            if (res == "")
            {
                data = result.split('^conf_method=');
                new_data = data[1].replace('^conf_method=', '');
                data_org = new_data.split('|^|');

                if (data_org[0] == 'addon') {
                    jQuery('body').append(data_org[1]);
                    return false;
                }
            }

            var conf_method = '';
            var resmessage = '';

            data = jQuery.parseJSON(result)
            jQuery.each(data, function (i, e) {
                if (i == "conf_method") {
                    conf_method = e;
                }
                if (i == "message") {
                    resmessage = e;
                }
            });


            if (conf_method == "captchaerror")
            {
                field_data = resmessage;

                for (key in field_data)
                {



                    var field_value = field_data[key];//field_data[1];
                    jQuery('input[name="' + key + '"], textarea[name="' + key + '"], select[name="' + key + '"]').first().val(field_value);
                    jQuery(object).find('#arf_field_' + key + '_container').addClass('arfblankfield');

                    if (jQuery(object).find('#arf_field_' + key + '_container #recaptcha_area').length) {

                        var $controlGroup = jQuery(object).find('#arf_field_' + key + '_container #recaptcha_area');
                        var $helpBlock = $controlGroup.find(".help-block").first();

                        jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery($controlGroup.find("input:visible").first()).offset().top - 100}, 'slow');
                        $controlGroup.find("input:visible").first().focus();

                        var error_type = (jQuery('#form_tooltip_error_' + form_id).val() == 'advance') ? 'advance' : 'normal';

                        if (error_type == 'advance')
                        {
                            jQuery(object).find('#arf_field_' + key + '_container #recaptcha_area').not('.recaptcha_style_custom #recaptcha_area').addClass('controls');
                            arf_show_tooltip($controlGroup, $helpBlock, field_value);
                            jQuery(object).find('#arf_field_' + key + '_container #recaptcha_area').not('.recaptcha_style_custom #recaptcha_area').removeClass('controls');
                        } else {
                            if (!$helpBlock.length) {
                                $helpBlock = jQuery('<div class="help-block"><ul><li>' + field_value + '</li></ul></div>');
                                $controlGroup.append($helpBlock);
                                $controlGroup.find('.help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                            }
                            else
                            {
                                $helpBlock = jQuery('<ul role="alert"><li>' + field_value + '</li></ul>');
                                $controlGroup.find('.help-block').append($helpBlock);
                                $controlGroup.find('.help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                            }
                        }

                        if (jQuery('#vpb_captcha_code_' + form_id).length > 0)
                        {
                            document.getElementById("vpb_captcha_code_" + form_id).value = "";
                            jQuery(object).find("#vpb_captcha_" + form_id).addClass('control-group');
                            jQuery(object).find("#vpb_captcha_" + form_id).addClass('arf_error');
                            document.images['captchaimg_' + form_id].src = document.images['captchaimg_' + form_id].src.substring(0, document.images['captchaimg_' + form_id].src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000 + "&form_id=" + form_id;
                        }
                        else
                        {
                            var show_captcha = true;
                            var captcha_key = key;
                            Recaptcha.reload();
                        }
                    }

                    var arf_bowser_name = jQuery(object).find('#arf_browser_name').val();
                    var arf_bowser_version = jQuery(object).find('#arf_browser_name').attr('data-version');

                    if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                        jQuery(object).find('.arf_submit_btn .arfstyle-label').hide();
                        jQuery(object).find('.arf_submit_btn .arf_ie_image').css('display', 'inline-block');
                    } else {
                        jQuery(object).find('.arf_submit_btn').toggleClass('data-loading');
                    }
                    jQuery(object).find('.arf_submit_btn').attr('disabled', false);

                }
            }




            if (conf_method == "validationerror")
            {
                var ErrorObj = resmessage;

                if (ErrorObj != '')
                {
                    msgObj = resmessage;
                    // hide loader
                    if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                        jQuery(object).find('.arf_submit_btn .arfstyle-label').show();
                        jQuery(object).find('.arf_submit_btn .arf_ie_image').hide();
                        jQuery(object).find('.arf_submit_btn').attr('disabled', false);
                    } else {
                        jQuery(object).find('.arf_submit_btn').removeClass('data-loading');
                        jQuery(object).find('.arf_submit_btn').attr('disabled', false);
                    }

                    var remain_error = [];
                    var last_show_page = jQuery('#last_show_page_' + form_id).val();

                    if (result.length > 0 && last_show_page !== undefined && last_show_page != '')
                    {
                        for (key1 in msgObj)
                        {
                            var field_id = key1;
                            var error_msg = msgObj[key1];

                            if (field_id == 'arf_message_error') {

                            } else {
                                // get first field
                                var first_field_id = field_id;
                                var error_page_id = get_error_page_id(form_id, field_id);
                                break;
                            }
                        }

                        // get all fields id 
                        var total_error = 0;
                        var error_page_fields = window['arf_page_fields'][form_id][error_page_id];
                        for (key in msgObj)
                        {
                            var field_id = key;
                            var error_msg = msgObj[key];
                            if (field_id == 'arf_message_error') {
                                total_error++;
                            } else {
                                var tmpres = is_field_in_page(field_id, error_page_fields);
                                if (tmpres == false) {
                                    delete result[key];
                                } else {
                                    total_error++;
                                }
                            }
                        }

                        if (total_error > 0)
                        {
                            jQuery(object).find('#previous_last').show();
                            // go to previous page
                            var form_key = jQuery(object).find("input[name='form_key']").val();
                            var current_page = jQuery(object).find('.page_break:visible').attr('id');
                            current_page = current_page.replace('page_', '');

                            if (error_page_id !== undefined && error_page_id != '' && error_page_id != last_show_page) {
                                go_previous(error_page_id, form_id, 'no', form_key);
                            }
                            if (error_page_id !== undefined && error_page_id != '' && error_page_id != last_show_page) {
                                var timeout = 510;
                            } else {
                                var timeout = 10;
                            }
                            setTimeout(function () {
                                jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().hide();
                                var i = 1;
                                var focus_field_id = '';
                                var focus_error_msg = '';
                                for (key in msgObj)
                                {
                                    var field_id = key;
                                    var error_msg = msgObj[key];
                                    if (field_id == 'arf_message_error') {
                                        jQuery('#arffrm_' + form_id + '_container #arf_message_error .msg-description-success').first().html(error_msg);
                                        jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().show();

                                        jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().offset().top - 130}, 'slow');
                                    } else {
                                        arf_show_validate_message(form_id, field_id, error_msg);
                                        if (i == 1)
                                        {
                                            focus_field_id = field_id;
                                            focus_error_msg = error_msg;
                                        }
                                    }
                                    i++;
                                }

                                if (focus_field_id != '' && focus_error_msg != '')
                                {
                                    if (jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').length > 0)
                                    {
                                        var scrolltop = jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top;
                                        jQuery(window.opera ? '.arfmodal-body' : '.arfmodal-body').animate({scrollTop: jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top - jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield').first()).offset().top - 50}, 'slow');
                                        var tmp_div_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first().attr('id');
                                        var tmp_field_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                        if (jQuery('#' + tmp_field_id).is('select')) {
                                            jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                        } else {
                                            jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                        }
                                    }
                                    else
                                    {
                                        jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery(jQuery(object).find('.arfformfield.arf_error').first()).offset().top - 100}, 'slow');

                                        var tmp_div_id = jQuery(object).find('.arfformfield.arf_error').first().attr('id');
                                        var tmp_field_id = jQuery(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                        if (jQuery('#' + tmp_field_id).is('select')) {
                                            jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                        } else {
                                            jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                        }
                                    }

                                    var fieldname = tmp_div_id.replace('arf_field_', '').replace('_container', '');
                                    for (k2 in msgObj)
                                    {
                                        if (k2 == fieldname)
                                            focus_error_msg = msgObj[k2];
                                    }

                                    revalidate_focus_outsidevalidate(tmp_field_id, tmp_div_id, focus_error_msg);
                                }

                            }, timeout);
                            //end of show page break error
                        }

                    }
                    else
                    {


                        jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().hide();
                        var i = 1;
                        var focus_field_id = '';
                        var focus_error_msg = '';

                        for (key in msgObj)
                        {
                            var field_id = key;
                            var error_msg = msgObj[key];

                            if (field_id == 'arf_message_error') {
                                jQuery('#arffrm_' + form_id + '_container #arf_message_error .msg-description-success').first().html(error_msg);
                                jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().show();

                                jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().offset().top - 130}, 'slow');

                            } else {
                                arf_show_validate_message(form_id, field_id, error_msg);
                                if (i == 1)
                                {
                                    focus_field_id = field_id;
                                    focus_error_msg = error_msg;
                                }
                            }
                            i++;
                        }

                        if (focus_field_id != '' && focus_error_msg != '')
                        {
                            if (jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').length > 0)
                            {
                                var scrolltop = jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top;
                                jQuery(window.opera ? '.arfmodal-body' : '.arfmodal-body').animate({scrollTop: jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top - jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield').first()).offset().top - 50}, 'slow');
                                var tmp_div_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first().attr('id');
                                var tmp_field_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                if (jQuery('#' + tmp_field_id).is('select')) {
                                    jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                } else {
                                    jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                }
                            }
                            else
                            {
                                jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery(jQuery(object).find('.arfformfield.arf_error').first()).offset().top - 100}, 'slow');

                                var tmp_div_id = jQuery(object).find('.arfformfield.arf_error').first().attr('id');
                                var tmp_field_id = jQuery(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                if (jQuery('#' + tmp_field_id).is('select')) {
                                    jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                } else {
                                    jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                }
                            }

                            var fieldname = tmp_div_id.replace('arf_field_', '').replace('_container', '');
                            for (k2 in msgObj)
                            {
                                if (k2 == fieldname)
                                    focus_error_msg = msgObj[k2];
                            }
                            revalidate_focus_outsidevalidate(tmp_field_id, tmp_div_id, focus_error_msg);
                        }

                    }

                }
                // Outsite Validation end
            }

            if (conf_method != "")
            {
                data = '' //html.split('^conf_method=');
                new_data = ''; //data[1].replace('^conf_method=','');
                data_org = ''; //new_data.split('|^|');


                if (conf_method == 'addon') {
                    jQuery('body').append(resmessage);
                }
                else if (conf_method == 'message')
                {
                    if (document.getElementById('popup-form-' + form_id))
                    {

                        jQuery(object).find('#hexagon').hide();
                        jQuery('#popup-form-' + form_id).find('#arf_message_success').parent().replaceWith('');
                        jQuery('#submit_loader').hide();
                        var arf_bowser_name = jQuery(object).find('#arf_browser_name').val();
                        var arf_bowser_version = jQuery(object).find('#arf_browser_name').attr('data-version');

                        if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                            jQuery(object).find('.arf_submit_btn .arfstyle-label').show();
                            jQuery(object).find('.arf_submit_btn .arf_ie_image').hide();
                        } else {
                            jQuery(object).find('.arf_submit_btn').toggleClass('data-loading');
                        }
                        jQuery(object).find('.arf_submit_btn').attr('disabled', false);

                        jQuery(object).find('#previous_last').show('');
                        msg = resmessage;


                        if (jQuery('#popup-form-' + form_id).find('#arf_message_success').css('display') == 'block')
                        {
                            jQuery('#popup-form-' + form_id).find('.arfmodal-body').prepend(msg);
                            jQuery('.arfmodal-body').scrollTop(jQuery('#arf_message_success'));

                            if (jQuery('#arf_message_error').length > 0 && jQuery('#arf_message_error').is(':visible'))
                            {
                                jQuery('.arfmodal-body').scrollTop(jQuery('#arf_message_error'));
                            }

                            jQuery(object).find(".help-block").empty();
                            jQuery(object).find('.frm_error_style').hide();

                            if (is_page_break == 'yes') {
                                go_previous('0', jQuery(object).find('input[name="form_id"]').val(), 'yes', jQuery(object).find('input[name="form_key"]').val());
                            }
                            jQuery(object).find('.vpb_captcha').removeClass('arf_error');
                            if (jQuery(object).find('#vpb_captcha_code_' + form_id).length > 0)
                            {
                                document.getElementById("vpb_captcha_code_" + form_id).value = "";
                                document.images['captchaimg_' + form_id].src = document.images['captchaimg_' + form_id].src.substring(0, document.images['captchaimg_' + form_id].src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000 + "&form_id=" + form_id;
                            }
                            else if (jQuery(object).find("#recaptcha_style").length > 0) {
                                jQuery(object).find("#recaptcha_style").append('<div id="recaptcha_widget_div"><div id="recaptcha_area"></div></div>');
                                Recaptcha.widget = Recaptcha.$("recaptcha_widget_div");
                                Recaptcha.challenge_callback();
                                Recaptcha.reload("t");
                            }

                        }
                        else
                        {
                            jQuery('#popup-form-' + form_id).find('.arfmodal-body').prepend(msg);

                            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#arffrm_' + form_id + '_container #arf_message_success').first().offset().top - 130}, 'slow');


                            /*
                             if(jQuery.browser.webkit){
                             //jQuery('.arfmodal-body').scrollTop(jQuery(top));
                             setTimeout(function(){
                             jQuery(window.opera?'html, .arfmodal-body':'html, body, .arfmodal-body').animate({ scrollTop: jQuery('#arffrm_'+form_id+'_container #arf_message_success').first().offset().top-130 }, 'slow');
                             },1000);
                             }else{
                             
                             setTimeout(function(){
                             jQuery(window.opera?'html, .arfmodal-body':'html, body, .arfmodal-body').animate({ scrollTop: jQuery('#arffrm_'+form_id+'_container #arf_message_success').first().offset().top-130 }, 'slow');
                             },1000);
                             // jQuery('.arfmodal-body').scrollTop(jQuery('#arf_message_success'));
                             }
                             */


                            if (jQuery('#arf_message_error').length > 0 && jQuery('#arf_message_error').is(':visible'))
                            {
                                jQuery('.arfmodal-body').scrollTop(jQuery('#arf_message_error'));
                            }


                            jQuery(object).find(".help-block").empty();
                            jQuery(object).find('.frm_error_style').hide();

                            if (is_page_break == 'yes') {
                                go_previous('0', jQuery(object).find('input[name="form_id"]').val(), 'yes', jQuery(object).find('input[name="form_key"]').val());
                            }
                            jQuery(object).find('.vpb_captcha').removeClass('arf_error');
                            if (jQuery(object).find('#vpb_captcha_code_' + form_id).length > 0)
                            {
                                document.getElementById("vpb_captcha_code_" + form_id).value = "";
                                document.images['captchaimg_' + form_id].src = document.images['captchaimg_' + form_id].src.substring(0, document.images['captchaimg_' + form_id].src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000 + "&form_id=" + form_id;
                            }
                            else if (jQuery(object).find("#recaptcha_style").length > 0) {
                                jQuery(object).find("#recaptcha_style").append('<div id="recaptcha_widget_div"><div id="recaptcha_area"></div></div>');
                                Recaptcha.widget = Recaptcha.$("recaptcha_widget_div");
                                Recaptcha.challenge_callback();
                                Recaptcha.reload("t");
                            }

                        }

                        var form_key = jQuery(object).find('input[name="form_key"]').val();

                        var is_formreset = jQuery(object).find('input[name="arf_is_resetform_aftersubmit_' + form_id + '"]').val();
                        if (is_formreset == 1)
                        {
                            jQuery(object).trigger("reset");

                            jQuery('#imagename_' + form_id).val('');

                            jQuery('.rate_widget').each(function (i) {
                                var widget_id = jQuery(this).attr('id');
                                var widget = jQuery(this);
                                set_votes(widget, widget_id);
                            });

                            if (jQuery.isFunction(jQuery().iCheck)) {
                                jQuery(object).find('input').iCheck('update');
                            }
                            if (jQuery.isFunction(jQuery().selectpicker)) {
                                object.find('select').selectpicker('render');
                            }
                            jQuery(object).find('.arfajax-file-upload').show();
                            jQuery(object).find('.ajax-file-remove').hide();
                            jQuery(object).find('.arfajax-file-upload, .ajax-file-remove').each(function () {
                                jQuery(this).css('margin-top', "0px");
                            });
                            jQuery(object).find('.progress, .arf_info').hide();

                            reset_like_field(object); //reset like field
                            reset_slider_field(object);
                            reset_running_total(object);
                            reset_colorpicker(object);

                            if (typeof (__ARFSTRRNTH_INDICATOR) != 'undefined') {
                                var strenth_indicator = __ARFSTRRNTH_INDICATOR;
                            } else {
                                var strenth_indicator = 'Strength indicator';
                            }
                            jQuery(object).find('.arf_strenth_meter').removeClass('short bad good strong');
                            jQuery(object).find('.arf_strenth_mtr .inside_title').html(strenth_indicator);
                        }

                        arf_reset_page_nav();

                        if (typeof arf_rule_apply_bulk == 'function') {
                            arf_rule_apply_bulk(form_key);	// reset all field to default state//---------- for conditional logic ----------//
                        }
                        // for reset form and field outside
                        var is_formreset_outside = jQuery(object).find('input[name="arf_is_resetform_outside_' + form_id + '"]').val();
                        if (is_formreset_outside == 1) {
                            arf_resetform_outside(object, form_id);
                        }

                        var arf_data_validate = jQuery(object).find("#arf_validate_outside_" + form_id).attr('data-validate');
                        jQuery(object).find("#arf_validate_outside_" + form_id).val(arf_data_validate);
                        arf_success_message_show_time = jQuery(object).find('#arf_success_message_show_time_'+form_id).val();
                        if(!arf_success_message_show_time>0)
                        {
                            arf_success_message_show_time=3;
                        }
                        if(arf_success_message_show_time!=0)
                        {
                            arf_success_message_show_time= arf_success_message_show_time*1000;
                            //console.log(arf_success_message_show_time);
                            
                             setTimeout(function () {
                            jQuery('#popup-form-' + form_id).find('#arf_message_success').parent().hide("slow");
                        }, arf_success_message_show_time);
                            
                        }
                       

                        jQuery('div.arfmodal').not('#maincontainerdiv div.arfmodal, #arfformsettingpage div.arfmodal').each(function () {
                            var screenwidth = jQuery(window).width();
                            var windowHeight = jQuery(window).height() - Number(60);
                            var actualheight = jQuery(this).find('.arf_fieldset').height();
                            if (screenwidth <= 770) {
                                if (windowHeight > actualheight) {
                                    jQuery(this).find('.arf_fieldset').css('height', windowHeight + 'px');
                                } else {
                                    jQuery(this).find('.arf_fieldset').css('height', 'auto');
                                }
                            }
                        });

                        jQuery('div.arform_right_fly_form_block_right_main').each(function () {
                            var screenwidth = jQuery(window).width();
                            var windowHeight = jQuery(window).height() - Number(60);
                            var actualheight = jQuery(this).find('.arf_fieldset').height();
                            if (screenwidth <= 770) {
                                if (windowHeight > actualheight) {
                                    jQuery(this).find('.arf_fieldset').css('height', windowHeight + 'px');
                                } else {
                                    jQuery(this).find('.arf_fieldset').css('height', 'auto');
                                }
                            }
                        });

                        jQuery('div.arform_left_fly_form_block_left_main').each(function () {
                            var screenwidth = jQuery(window).width();
                            var windowHeight = jQuery(window).height() - Number(60);
                            var actualheight = jQuery(this).find('.arf_fieldset').height();
                            if (screenwidth <= 770) {
                                if (windowHeight > actualheight) {
                                    jQuery(this).find('.arf_fieldset').css('height', windowHeight + 'px');
                                } else {
                                    jQuery(this).find('.arf_fieldset').css('height', 'auto');
                                }
                            }
                        });

                    }
                    else
                    {

                        jQuery(object).find('#hexagon').hide();
                        jQuery('#submit_loader').hide();
                        var arf_bowser_name = jQuery(object).find('#arf_browser_name').val();
                        var arf_bowser_version = jQuery(object).find('#arf_browser_name').attr('data-version');

                        if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                            jQuery(object).find('.arf_submit_btn .arfstyle-label').show();
                            jQuery(object).find('.arf_submit_btn .arf_ie_image').hide();
                        } else {
                            jQuery(object).find('.arf_submit_btn').toggleClass('data-loading');
                        }
                        jQuery(object).find('.arf_submit_btn').attr('disabled', false);

                        jQuery(object).find('#previous_last').show('');

                        msg = resmessage;



                        ob_form_id = jQuery(object).find('input[name="form_id"]').val();
                        if (jQuery('#arffrm_' + ob_form_id + '_container #arf_message_success').css('display') == 'block')
                        {
                            jQuery('.frm_error_style').hide();
                            jQuery('#arffrm_' + ob_form_id + '_container').replaceWith(msg).show(' ');

                            jQuery('#content').find('div').removeClass('arfblankfield');
                            jQuery('.allfields').find('div').removeClass('arfblankfield');
                            jQuery(object).find(".help-block").empty();
                            jQuery(object).find(".frm_error_style").remove();

                            if (is_page_break == 'yes') {
                                go_previous('0', jQuery(object).find('input[name="form_id"]').val(), 'yes', jQuery(object).find('input[name="form_key"]').val());
                            }
                            if (jQuery(object).find('#vpb_captcha_code_' + form_id).length > 0)
                            {
                                document.getElementById("vpb_captcha_code_" + form_id).value = "";
                                jQuery(object).find("#vpb_captcha_" + form_id).addClass('control-group');
                                jQuery(object).find("#vpb_captcha_" + form_id).addClass('arf_error');
                                document.images['captchaimg_' + form_id].src = document.images['captchaimg_' + form_id].src.substring(0, document.images['captchaimg_' + form_id].src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000 + "&form_id=" + form_id;
                            }
                            else if (jQuery(object).find("#recaptcha_style").length > 0) {
                                jQuery(object).find("#recaptcha_style").append('<div id="recaptcha_widget_div"><div id="recaptcha_area"></div></div>');
                                Recaptcha.widget = Recaptcha.$("recaptcha_widget_div");
                                Recaptcha.challenge_callback();
                                Recaptcha.reload("t");
                            }

                        }
                        else
                        {
                            if (jQuery('#arffrm_' + ob_form_id + '_container').find("#arf_message_success").html() != "undefined" && jQuery('#arffrm_' + ob_form_id + '_container').find("#arf_message_success").html() != null)
                            {
                                jQuery('#arffrm_' + ob_form_id + '_container').find('#arf_message_success').parent().replaceWith('');
                            }
                            jQuery(object).parent('div.arf_form').before(msg);

                            if (jQuery('div.arf_widget_form').parent(object).find('#arf_message_success').length > 0)
                            {
                                jQuery(window.opera ? 'html' : 'html, body').animate({scrollTop: jQuery(jQuery('div.arf_widget_form').parent(object).find('#arf_message_success')).offset().top - 130}, 'slow');
                            }
                            else
                            {
                            }
                            if (jQuery('div.arf_widget_form').parent(object).find('#arf_message_error').length > 0 && jQuery('div.arf_widget_form').parent(object).find('#arf_message_error').is(':visible'))
                            {
                                jQuery(window.opera ? 'html' : 'html, body').animate({scrollTop: jQuery(jQuery('div.arf_widget_form').parent(object).find('#arf_message_error')).offset().top - 130}, 'slow');
                            }

                            jQuery('#content').find('div').removeClass('arfblankfield');
                            jQuery('.allfields').find('div').removeClass('arfblankfield');
                            jQuery(object).find(".help-block").empty();
                            jQuery(object).find(".frm_error_style").remove();

                            if (is_page_break == 'yes') {
                                go_previous('0', jQuery(object).find('input[name="form_id"]').val(), 'yes', jQuery(object).find('input[name="form_key"]').val());
                            }
                            if (jQuery(object).find('#vpb_captcha_code_' + form_id).length > 0)
                            {
                                document.getElementById("vpb_captcha_code_" + form_id).value = "";
                                document.images['captchaimg_' + form_id].src = document.images['captchaimg_' + form_id].src.substring(0, document.images['captchaimg_' + form_id].src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000 + "&form_id=" + form_id;
                            }
                            else if (jQuery(object).find("#recaptcha_style").length > 0) {
                                jQuery(object).find("#recaptcha_style").append('<div id="recaptcha_widget_div"><div id="recaptcha_area"></div></div>');
                                Recaptcha.widget = Recaptcha.$("recaptcha_widget_div");
                                Recaptcha.challenge_callback();
                                Recaptcha.reload("t");
                            }

                        }

                        var form_key = jQuery(object).find('input[name="form_key"]').val();

                        // reset form and field here.
                        var is_formreset = jQuery(object).find('input[name="arf_is_resetform_aftersubmit_' + form_id + '"]').val();
                        if (is_formreset == 1) {

                            jQuery(object).trigger("reset");

                            jQuery('#imagename_' + form_id).val('');

                            jQuery('.rate_widget').each(function (i) {
                                var widget_id = jQuery(this).attr('id');
                                var widget = jQuery(this);
                                set_votes(widget, widget_id);
                            });

                            if (jQuery.isFunction(jQuery().iCheck)) {
                                jQuery(object).find('input').iCheck('update');
                            }
                            if (jQuery.isFunction(jQuery().selectpicker)) {
                                jQuery(object).find('select').selectpicker('render');
                            }
                            jQuery(object).find('.arfajax-file-upload').show();
                            jQuery(object).find('.ajax-file-remove').hide();
                            jQuery(object).find('.arfajax-file-upload, .ajax-file-remove').each(function () {
                                jQuery(this).css('margin-top', "0px");
                            });
                            jQuery(object).find('.progress, .arf_info').hide();

                            reset_like_field(object); //reset like field
                            reset_slider_field(object); //reset slider field
                            reset_running_total(object);
                            reset_colorpicker(object);

                            if (typeof (__ARFSTRRNTH_INDICATOR) != 'undefined') {
                                var strenth_indicator = __ARFSTRRNTH_INDICATOR;
                            } else {
                                var strenth_indicator = 'Strength indicator';
                            }
                            jQuery(object).find('.arf_strenth_meter').removeClass('short bad good strong');
                            jQuery(object).find('.arf_strenth_mtr .inside_title').text(strenth_indicator);
                        }

                        arf_reset_page_nav();

                        if (typeof arf_rule_apply_bulk == 'function') {
                            arf_rule_apply_bulk(form_key);	// reset all field to default state		//---------- for conditional logic ----------//	
                        }
                        // for reset form and field outside
                        var is_formreset_outside = jQuery(object).find('input[name="arf_is_resetform_outside_' + form_id + '"]').val();
                        if (is_formreset_outside == 1)
                        {
                            arf_resetform_outside(object, form_id);
                        }

                        var arf_data_validate = jQuery(object).find("#arf_validate_outside_" + ob_form_id).attr('data-validate');
                        jQuery(object).find("#arf_validate_outside_" + ob_form_id).val(arf_data_validate);

                        if (jQuery('#arffrm_' + ob_form_id + '_container #arf_message_success').length > 0)
                        {
                            jQuery(window.opera ? 'html' : 'html, body').animate({
                                scrollTop: jQuery('#arffrm_' + ob_form_id + '_container #arf_message_success').offset().top - 130
                            }, 1000, function () { //scroll complete function
                            });
                        }

                        if (jQuery('#arffrm_' + ob_form_id + '_container #arf_message_error').length > 0 && jQuery('#arffrm_' + ob_form_id + '_container #arf_message_error').is(':visible'))
                        {
                            jQuery(window.opera ? 'html' : 'html, body').animate({
                                scrollTop: jQuery('#arffrm_' + ob_form_id + '_container #arf_message_error').offset().top - 130
                            }, 1000, function () { //scroll complete function
                            });
                        }
                        arf_success_message_show_time = jQuery(object).find('#arf_success_message_show_time_'+ob_form_id).val();
                        if(!arf_success_message_show_time>0)
                        {
                            arf_success_message_show_time=3;
                        }
                        if(arf_success_message_show_time!=0)
                        {
                            arf_success_message_show_time= arf_success_message_show_time*1000;
                            //console.log(arf_success_message_show_time);
                            setTimeout(function () {
                                jQuery('#arffrm_' + ob_form_id + '_container #arf_message_success').parent().hide("slow");
                            }, arf_success_message_show_time);
                            
                        }
                    }
                }
                else if (conf_method == 'page')
                {
                    if (document.getElementById('popup-form-' + form_id))
                    {
                        jQuery('#popup-form-' + form_id).find('#arf_message_success').parent().replaceWith('');
                        jQuery('#submit_loader').hide();
                        var arf_bowser_name = jQuery(object).find('#arf_browser_name').val();
                        var arf_bowser_version = jQuery(object).find('#arf_browser_name').attr('data-version');

                        if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                            jQuery(object).find('.arf_submit_btn .arfstyle-label').show();
                            jQuery(object).find('.arf_submit_btn .arf_ie_image').hide();
                        } else {
                            jQuery(object).find('.arf_submit_btn').toggleClass('data-loading');
                        }
                        jQuery(object).find('.arf_submit_btn').attr('disabled', false);

                        jQuery(object).find('#previous_last').show('');

                        jQuery('.frm_error_style').hide();
                        jQuery('#content').find('div').removeClass('arfblankfield');
                        jQuery('.allfields').find('div').removeClass('arfblankfield');
                        jQuery(object).find(".help-block").empty();
                        jQuery(object).find(".frm_error_style").remove();
                        jQuery(object).trigger("reset");
                        if (is_page_break == 'yes') {
                            go_previous('0', jQuery(object).find('input[name="form_id"]').val(), 'yes', jQuery(object).find('input[name="form_key"]').val());
                        }
                        jQuery('.rate_widget').each(function (i) {
                            var widget_id = jQuery(this).attr('id');
                            var widget = jQuery(this);
                            set_votes(widget, widget_id);
                        });

                        if (jQuery.isFunction(jQuery().iCheck)) {
                            jQuery(object).find('input').iCheck('update');
                        }
                        if (jQuery.isFunction(jQuery().selectpicker)) {
                            jQuery(object).find('select').selectpicker('render');
                        }
                        jQuery(object).find('.arfajax-file-upload').show();
                        jQuery(object).find('.ajax-file-remove').hide();
                        jQuery(object).find('.arfajax-file-upload, .ajax-file-remove').each(function () {
                            jQuery(this).css('margin-top', "0px");
                        });
                        jQuery(object).find('.progress, .arf_info').hide();
                        var form_key = jQuery(object).find('input[name="form_key"]').val();

                        if (typeof arf_rule_apply_bulk == 'function') {
                            arf_rule_apply_bulk(form_key);	// reset all field to default state		//---------- for conditional logic ----------//
                        }
                        arf_reset_page_nav();

                        if (jQuery('#vpb_captcha_code_' + form_id).length > 0)
                        {
                            document.getElementById("vpb_captcha_code_" + form_id).value = "";
                            document.images['captchaimg_' + form_id].src = document.images['captchaimg_' + form_id].src.substring(0, document.images['captchaimg_' + form_id].src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000 + "&form_id=" + form_id;
                        }
                        else if (jQuery('#form_' + form_key).find("#recaptcha_style").length > 0) {
                            jQuery('#form_' + form_key).find("#recaptcha_style").append('<div id="recaptcha_widget_div"><div id="recaptcha_area"></div></div>');
                            Recaptcha.widget = Recaptcha.$("recaptcha_widget_div");
                            Recaptcha.challenge_callback();
                            Recaptcha.reload("t");
                        }

                        jQuery('#popup-form-' + form_id).find('.arfmodal-body #form_' + form_key).hide();
                        jQuery('#popup-form-' + form_id).find('.arfmodal-body .arf_content_another_page').show();
                        jQuery('#popup-form-' + form_id).find('.arfmodal-body .arf_content_another_page').html(resmessage);

                        //for star rating
                        jQuery('.rate_widget').each(function (i) {
                            widget_id = jQuery(this).attr('id');
                            jQuery('.ratings_stars').hover(
                                    function () {
                                        var color = jQuery(this).attr('data-color');
                                        var datasize = jQuery(this).attr('data-size');
                                        jQuery(this).prevAll().andSelf().addClass('ratings_over_' + color + datasize);
                                        jQuery(this).nextAll().removeClass('ratings_vote_' + color + datasize);
                                    },
                                    function () {
                                        var color = jQuery(this).attr('data-color');
                                        var datasize = jQuery(this).attr('data-size');
                                        jQuery(this).prevAll().andSelf().removeClass('ratings_over_' + color + datasize);
                                        widget_id_new = jQuery(this).parent().attr('id');
                                        set_votes(jQuery(this).parent(), widget_id_new);
                                    }
                            );

                            jQuery('.ratings_stars').bind('click', function () {
                                var star = this;
                                var widget = jQuery(this).parent();
                                var clicked_data = {
                                    clicked_on: jQuery(star).attr('data-val'),
                                    widget_id: jQuery(star).parent().attr('id')
                                };
                                widget_id_new = jQuery(this).parent().attr('id');

                                jQuery('#field_' + widget_id_new).val(clicked_data.clicked_on);
                                jQuery('#field_' + widget_id_new).trigger('click');
                                set_votes(widget, widget_id_new);
                            });

                        });
                        //for star rating

                        // for file upload
                        var arfmainformurl = jQuery('#arfmainformurl').val();
                        var url = arfmainformurl + '/js/filedrag/filedrag_front.js';
                        var submit_type = jQuery('#form_submit_type').val();
                        if (submit_type == 1) {
                            jQuery.getScript(url);
                        }
                        jQuery('.original_normal').on('change', function (e) {
                            var id = jQuery(this).attr('id');
                            id = id.replace('field_', '');

                            var fileName = jQuery(this).val();
                            fileName = fileName.replace(/C:\\fakepath\\/i, '');
                            if (fileName != '') {
                                jQuery('#file_name_' + id).html(fileName);
                            }
                        });
                        // for file upload end

                        //for like button
                        jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function () {
                            var field = jQuery(this).attr('id');
                            var field = field.replace('like_', 'field_');
                            if (!jQuery("#" + field).is(':checked')) {
                                jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
                            }
                        });

                        jQuery('.arf_form .arf_like').on("click", function () {
                            var field = jQuery(this).attr('id');
                            field_data = field.split('-');

                            var field_id = field_data[0];
                            field_id = field_id.replace('field_', '');
                            field_id = 'like_' + field_id;
                            var like = field_data[1];

                            if (like == 1) {
                                jQuery('#' + field_id + '-0').removeClass('active');
                                jQuery('#' + field_id + '-1').addClass('active');
                            } else if (like == 0) {
                                jQuery('#' + field_id + '-1').removeClass('active');
                                jQuery('#' + field_id + '-0').addClass('active');
                            }
                        });

                        jQuery('.arf_like_btn, .arf_dislike_btn').each(function () {
                            var title = jQuery(this).attr('data-title');
                            if (title !== undefined) {
                                jQuery(this).popover({
                                    html: true,
                                    trigger: 'hover',
                                    placement: 'top',
                                    content: title,
                                    title: '',
                                    animation: false
                                });
                            }
                        });
                        //for like button end
                        arf_success_message_show_time = jQuery(object).find('#arf_success_message_show_time_'+ob_form_id).val();
                        if(!arf_success_message_show_time>0)
                        {
                            arf_success_message_show_time=3;
                        }
                        if(arf_success_message_show_time!=0)
                        {
                            arf_success_message_show_time= arf_success_message_show_time*1000;
                           // console.log(arf_success_message_show_time);
                            
                             setTimeout(function () {
                                jQuery('#popup-form-' + form_id).find('#arf_message_success').hide("slow");
                            }, arf_success_message_show_time);
                        }
                       
                    }
                    else
                    {
                        jQuery('#content').find('#arf_message_success').parent().replaceWith('');
                        jQuery('#submit_loader').hide();
                        var arf_bowser_name = jQuery(object).find('#arf_browser_name').val();
                        var arf_bowser_version = jQuery(object).find('#arf_browser_name').attr('data-version');

                        if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                            jQuery(object).find('.arf_submit_btn .arfstyle-label').show();
                            jQuery(object).find('.arf_submit_btn .arf_ie_image').hide();
                        } else {
                            jQuery(object).find('.arf_submit_btn').toggleClass('data-loading');
                        }
                        jQuery(object).find('.arf_submit_btn').attr('disabled', false);

                        jQuery(object).find('#previous_last').show('');
                        msg = resmessage;
                        jQuery(object).parent('div.arf_form').replaceWith(msg);
                        var form_key = jQuery('#form_key').val();
                        if (jQuery(msg).find('.recaptcha_style_custom').length > 0)
                        {

                        }
                        else if (jQuery('#form_' + form_key).find("#recaptcha_style").length > 0) {
                            setTimeout(function () {
                                jQuery('#form_' + form_key).find("#recaptcha_style").append('<div id="recaptcha_widget_div"><div id="recaptcha_area"></div></div>');
                                Recaptcha.widget = Recaptcha.$("recaptcha_widget_div");
                                Recaptcha.challenge_callback();
                                /*Recaptcha.reload("t");*/ }, 1000);
                        }
                         arf_success_message_show_time = jQuery(object).find('#arf_success_message_show_time_'+ob_form_id).val();
                        if(!arf_success_message_show_time>0)
                        {
                            arf_success_message_show_time=3;
                        }
                        if(arf_success_message_show_time!=0)
                        {
                            arf_success_message_show_time= arf_success_message_show_time*1000;
                            //console.log(arf_success_message_show_time);
                            
                            setTimeout(function () {
                                jQuery('#arf_message_success').hide("slow");
                            }, arf_success_message_show_time);
                        }
                        
                    }
                }
                else if (conf_method == 'redirect') {
                    jQuery(location).attr('href', resmessage);
                }
            }

            if (typeof reset_field_in_outsite == 'function') {
                reset_field_in_outsite(object, result)
            }
        }
    });
}

function arfsectionswap($sec)
{
    $sec.next('.toggle_container').slideToggle(200);
    if ($sec.hasClass('active'))
    {
        $sec.removeClass('active'), $sec.children('.ui-icon-triangle-1-s').addClass('ui-icon-triangle-1-e');
        $sec.children('.ui-icon-triangle-1-e').removeClass('ui-icon-triangle-1-s');
    }
    else
    {
        $sec.addClass("active"), $sec.children('.ui-icon-triangle-1-e').addClass('ui-icon-triangle-1-s');
        $sec.children('.ui-icon-triangle-1-s').removeClass('ui-icon-triangle-1-e');
    }
}

function set_votes(widget, widget_id)
{
    var avg = jQuery('#field_' + widget_id).val();
    if (avg == '' || avg == null) {
        avg = '0';
    }
    var color = jQuery('#field_' + widget_id).attr('data-color');
    var datasize = jQuery('#field_' + widget_id).attr('data-size');
    jQuery(widget).find('.star_' + avg).prevAll().andSelf().addClass('ratings_vote_' + color + datasize);
    jQuery(widget).find('.star_' + avg).nextAll().removeClass('ratings_vote_' + color + datasize);
    jQuery('#field_' + widget_id).trigger('change');	//---------- for conditional logic ----------//
    var avg_value = (avg == '0') ? '' : avg;
    jQuery('#field_' + widget_id).val(avg_value);
}

function go_previous(id, form_id, submited, form_key)
{
    object = jQuery('#form_' + form_key);

    var current_pages = jQuery(object).find('.page_break:visible').attr('id');
    current_pages = current_pages.replace('page_', '');

    //---------- for conditional logic ----------//
    var data_hide = jQuery(object).find('#get_hidden_pages_' + form_id).val();

    if (data_hide !== undefined && data_hide != '' && data_hide.indexOf(',' + id + ',') >= 0 && id != 0) {

        var n_id = parseInt(id) - parseInt(1);
        go_previous(n_id, form_id, submited, form_key);

    } else if (current_pages == id && id != 0) {
        var n_id = parseInt(id) - parseInt(1);
        go_previous(n_id, form_id, submited, form_key);

    } else {

        var arf_data_validate = jQuery(object).find("#arf_validate_outside_" + form_id).attr('data-validate');
        jQuery(object).find("#arf_validate_outside_" + form_id).val(arf_data_validate);

        if (data_hide !== undefined && id == 0 && submited == 'yes') {

            var data_hide = jQuery(object).find('#submit_form_' + form_id).attr('data-hide');

            var data_arr = data_hide.split(',');

            jQuery.each(data_arr, function (i, v) {
                var page_nav_no = parseInt(v) + parseInt(1);
                jQuery(object).find('#page_nav_' + page_nav_no).hide();
                jQuery(object).find('#page_nav_arrow_' + page_nav_no).hide();
            });

            var last_show_page = jQuery('#last_show_page_' + form_id).attr('data-last');
            jQuery('#last_show_page_' + form_id).val(last_show_page);
            jQuery(object).find('#get_hidden_pages_' + form_id).val(data_hide);

            if (typeof arf_rule_apply_bulk == 'function') {
                arf_rule_apply_bulk(form_key);	// reset all field to default state
            }

        }

        if (id == 0) {
            jQuery('#submit_form_' + form_id).attr('data-val', '1');
        } else if (id == 1) {
            jQuery('#submit_form_' + form_id).attr('data-val', '2');
        }
        //---------- for conditional logic ----------//

        var data_id = jQuery(object).find('#submit_form_' + form_id).attr('data-val');
        var max_id = jQuery(object).find('#submit_form_' + form_id).attr('data-max');

        var tmp_id = parseInt(id) + parseInt(1);

        if (tmp_id == data_id) {
            var data_id_new = data_id;
        } else {
            var data_id_new = parseInt(data_id) - parseInt(1);
        }

        jQuery(object).find('#submit_form_' + form_id).attr('data-val', data_id_new);

        if (submited == 'yes') {
            jQuery(object).find('#submit_form_' + form_id).attr('data-val', '1');
        }
        jQuery(object).find('#submit_form_' + form_id).val('1');

        jQuery(object).find('.page_break').css('display', 'none');
        if (jQuery.isFunction(jQuery().effect)) {
            jQuery(object).find('#page_' + id).effect("slide", {"direction": "right"}, 500);
        } else {
            jQuery(object).find('#page_' + id).css('display', 'block');
        }
        if (jQuery(object).find('#arf_wizard_table, #arf_progress_bar').first().is(':visible') && submited != 'yes')
        {
            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({
                scrollTop: jQuery(object).find('#arf_wizard_table, #arf_progress_bar').first().offset().top - 130
            }, 'slow');
        }


        jQuery(object).find('.arf_submit_div').css('display', 'none'); //.not('#page_last .arf_submit_div')
        jQuery(object).find('#arf_submit_div_' + id).css('display', 'block');

        if (submited != 'yes') {
            arf_change_modal_slider(object);
        }
        if (submited == 'yes') {
            jQuery(object).find('#page_last').css('display', 'none');
        }
        if (max_id != id) {
            jQuery(object).find('#page_last').css('display', 'none');
        }
        if (submited == 'yes') {
            var is_formreset = jQuery(object).find('input[name="arf_is_resetform_aftersubmit_' + form_id + '"]').val();
            if (is_formreset == 1)
            {
                jQuery(object).find('.arfajax-file-upload').show();
                jQuery(object).find('.ajax-file-remove').hide();
                jQuery(object).find('.arfajax-file-upload, .ajax-file-remove').each(function () {
                    jQuery(this).css('margin-top', "0px");
                });
                jQuery(object).find('.progress, .arf_info').hide();
            }

            if (last_show_page == 0) {
                jQuery(object).find('#arf_submit_div_0').hide();
                jQuery(object).find('#previous_last').hide();
                jQuery(object).find('#page_last').show();
                jQuery(object).find('#page_last .arf_submit_div').show();
                jQuery(object).find('#submit_form_' + form_id).val('0');
            } else if (last_show_page > 0) {
                jQuery(object).find('#arf_submit_div_0').show();
                jQuery(object).find('#previous_last').show();
                jQuery(object).find('#page_last').hide();
                jQuery(object).find('#submit_form_' + form_id).val('1');
            }
        }
        // for page nav
        jQuery(object).find('.page_break_nav').removeClass('page_nav_selected');
        var page_id = parseInt(id) + parseInt(1);

        jQuery(object).find('.arf_current_tab_arrow').remove();
        jQuery(object).find('#page_nav_arrow_' + page_id).html('<div class="arf_current_tab_arrow"></div>');
        jQuery(object).find('#page_nav_arrow_' + page_id).addClass('page_nav_selected');

        //for survey style
        //for survey style		
        var data_hide = jQuery(object).find('#get_hidden_pages_' + form_id).val();
        var max_page_no = jQuery(object).find('#submit_form_' + form_id).attr('data-max');
        var current_i = 0;
        var current_page_number = id;
        data_hide = (data_hide !== undefined) ? data_hide : '';

        for (pi = 0; pi <= max_page_no; pi++) {
            if (data_hide.indexOf(',' + pi + ',') >= 0) {
                //total_page_number = parseInt(total_page_number) - parseInt(1);				
            } else {
                current_i = parseInt(current_i) + parseInt(1);
                if (id == pi) {
                    current_page_number = current_i;
                }
            }
        }
        jQuery(object).find('.current_survey_page').html(current_page_number);

        if (current_page_number != '' && current_page_number != 'undefined') {
            arf_change_progressbar(object, current_page_number, ''); // change progress bar
        }

        if (id != 0) {
            jQuery(object).find('.page_break_nav').removeClass('arf_page_prev');
            var temp_id = parseInt(id) - parseInt(1);
            arf_nav_prev(object, form_id, temp_id);
            //jQuery(object).find('#page_nav_'+id).addClass('arf_page_prev');

            var last_show_page = jQuery('#last_show_page_' + form_id).val();
            last_show_page = parseInt(last_show_page) + parseInt(1);
            if (last_show_page > 1) {
                jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
                jQuery(object).find('#page_nav_' + last_show_page).addClass('arf_page_last');
            }
        }

        jQuery(object).find('#page_nav_' + page_id).addClass('page_nav_selected');
        // for page nav end

    }

}

function go_next(id, object)
{

    //---------- for conditional logic ----------//
    var form_id = jQuery(object).find('input[name="form_id"]').val();

    var data_hide = jQuery(object).find('#get_hidden_pages_' + form_id).val();

    var data_max = jQuery(object).find('#submit_form_' + form_id).attr('data-max');

    if (data_hide.indexOf(',' + id + ',') >= 0) {

        var next_id = parseInt(id) + parseInt(1);
        var next_id_new = parseInt(next_id) + parseInt(1);

        if (next_id_new <= data_max) {
            jQuery('#submit_form_' + form_id).attr('data-val', next_id_new);
        }
        go_next(next_id, object);

    } else {

        var arf_data_validate = jQuery(object).find("#arf_validate_outside_" + form_id).attr('data-validate');
        jQuery(object).find("#arf_validate_outside_" + form_id).val(arf_data_validate);

        if (data_max == id) {
            jQuery('#submit_form_' + form_id).val('0');
        }
        //---------- for conditional logic ----------//
        jQuery(object).find('.page_break').css('display', 'none');
        if (jQuery.isFunction(jQuery().effect)) {
            jQuery(object).find('#page_' + id).effect("slide", {"direction": "left"}, 500);
        } else {
            jQuery(object).find('#page_' + id).css('display', 'block');
        }
        if (jQuery(object).find('#arf_wizard_table, #arf_progress_bar').first().is(':visible'))
        {
            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({
                scrollTop: jQuery(object).find('#arf_wizard_table, #arf_progress_bar').first().offset().top - 130
            }, 'slow');
        }

        jQuery(object).find('.arf_submit_div').css('display', 'none');
        jQuery(object).find('#arf_submit_div_' + id).css('display', 'block');
        //jQuery(object).find('#page_'+id).show('slide',{direction:'right'}, 500);
        var last_show_page = jQuery('#last_show_page_' + form_id).val();
        if (last_show_page == id) {
            jQuery(object).find('#arf_submit_div_' + id).hide();
            //jQuery(object).find('#page_'+id+' .arf_submit_div').hide();
            jQuery(object).find('#previous_last').show();
            jQuery(object).find('#page_last').show();
            jQuery(object).find('#page_last .arf_submit_div').show();
            jQuery(object).find('#submit_form_' + form_id).val('0');
        } else {
            jQuery(object).find('#arf_submit_div_' + id).show();
        }


        arf_change_modal_slider(object);

        var last_page_id = jQuery(object).find('#last_page_id').val();
        // for page nav
        jQuery(object).find('.page_break_nav').removeClass('page_nav_selected');
        var page_id = parseInt(id) + parseInt(1);

        jQuery(object).find('.arf_current_tab_arrow').remove();
        jQuery(object).find('#page_nav_arrow_' + page_id).html('<div class="arf_current_tab_arrow"></div>');


        //for survey style		
        var data_hide = jQuery(object).find('#get_hidden_pages_' + form_id).val();
        var max_page_no = jQuery(object).find('#submit_form_' + form_id).attr('data-max');
        var current_i = 0;
        var current_page_number = id;
        for (pi = 0; pi <= max_page_no; pi++) {
            if (data_hide.indexOf(',' + pi + ',') >= 0) {
                //total_page_number = parseInt(total_page_number) - parseInt(1);				
            } else {
                current_i = parseInt(current_i) + parseInt(1);
                if (id == pi) {
                    current_page_number = current_i;
                }
            }
        }
        jQuery(object).find('.current_survey_page').html(current_page_number);

        if (current_page_number != '' && current_page_number != 'undefined') {
            arf_change_progressbar(object, current_page_number, ''); // change progress bar
        }

        jQuery(object).find('#page_nav_' + page_id).addClass('page_nav_selected');

        if (id != 0) {
            jQuery(object).find('.page_break_nav').removeClass('arf_page_prev');
            jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
            var temp_id = parseInt(id) - parseInt(1);
            arf_nav_prev(object, form_id, temp_id);

            var last_show_page = jQuery('#last_show_page_' + form_id).val();
            last_show_page = parseInt(last_show_page) + parseInt(1);
            if (last_show_page > 1) {
                jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
                jQuery(object).find('#page_nav_' + last_show_page).addClass('arf_page_last');
            }
        }
        // for page nav end
        if (last_page_id == id) {
            jQuery(object).find('.arf_submit_div').css('display', 'none');
            jQuery(object).find('#page_last').css('display', 'block');
            jQuery(object).find('#page_last .arf_submit_div').css('display', 'block');
        }

    }

}

function arf_nav_prev(object, form_id, id) {
    var data_hide = jQuery(object).find('#get_hidden_pages_' + form_id).val();

    if (data_hide.indexOf(',' + id + ',') >= 0)
    {
        var id_new = parseInt(id) - parseInt(1);
        arf_nav_prev(object, form_id, id_new);
    }
    else
    {
        var id_new = parseInt(id) + parseInt(1);
        jQuery(object).find('#page_nav_' + id_new).addClass('arf_page_prev');
        return true;
    }
}

function revalidate_focus(tmp_field_id, tmp_div_id, object)
{
    if (tmp_field_id != '' && tmp_field_id != 'undefined' && tmp_div_id != '' && tmp_div_id != 'undefined')
    {
        setTimeout(function () {

            if (jQuery('#' + tmp_field_id).attr('aria-invalid') == 'false') {

                jQuery('#' + tmp_field_id).attr('aria-invalid', 'true');

                jQuery('.arfmodal-body').find('#' + tmp_div_id).addClass('arf_error');

                jQuery('#' + tmp_div_id).addClass('arf_error');

                var required_message = jQuery('#' + tmp_field_id).attr('data-validation-required-message');

                if (required_message === undefined) {
                    required_message = jQuery('#' + tmp_field_id).attr('data-validation-minchecked-message');
                }
                if (required_message === undefined && jQuery('#' + tmp_field_id).attr('data-cpass') == '1') {
                    required_message = jQuery('#' + tmp_field_id).attr('data-validation-match-message');
                }
                if (required_message == '' || required_message === undefined) {
                    required_message = 'This field cannot be blank.';
                }
                var $this = jQuery('#' + tmp_field_id);
                var $controlGroup = $this.parents(".control-group").first();
                var $helpBlock = $controlGroup.find(".help-block").first();

                var form_id = $this.closest('form').find('#form_id').val();
                var error_type = (jQuery('#form_tooltip_error_' + form_id).val() == 'advance') ? 'advance' : 'normal';

                if (error_type == 'advance')
                {
                    arf_show_tooltip($controlGroup, $helpBlock, required_message);
                } else {
                    if (!$helpBlock.length) {
                        $helpBlock = jQuery('<div class="help-block"><ul><li>' + required_message + '</li></ul></div>');
                        $controlGroup.find('.controls').append($helpBlock);
                        $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                        //$helpBlock.removeClass('animated bounceInDown').addClass('animated bounceInDown');
                    }
                    else
                    {
                        $helpBlock = jQuery('<ul role="alert"><li>' + required_message + '</li></ul>');
                        $controlGroup.find('.controls .help-block').append($helpBlock);
                        $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                        //$helpBlock.removeClass('animated bounceInDown').addClass('animated bounceInDown');
                    }
                }

            }

        }, 10);
    }

}


function arfgetformerrors_new(object, ajax_url, event)
{
    if (typeof (__ARFMAINURL) != 'undefined') {
        var ajax_url = __ARFMAINURL;
    }
    if (typeof (__ARFERR) != 'undefined') {
        var file_error = __ARFERR;
    } else {
        var file_error = 'Sorry, this file type is not permitted for security reasons.';
    }
    var form_id = jQuery(object).find('input[name="form_id"]').attr('value');

    var submit_type = jQuery(object).find('#form_submit_type').val();

    if (submit_type == 1) {

        var upload_flag = 0;
        jQuery(".original").each(function (index) {
            var fileToUpload = jQuery(this).attr('file-valid');

            if (fileToUpload == 'false')
            {
                var fileId = jQuery(this).attr('id');
                var file = document.getElementById(fileId);

                if (jQuery('#' + fileId).attr('data-invalid-message') !== undefined && jQuery('#' + fileId).attr('data-invalid-message') != '') {
                    var arf_invalid_file_message = jQuery('#' + fileId).attr('data-invalid-message');
                } else {
                    var arf_invalid_file_message = file_error;
                }
                var field_types_id = '#' + fileId.replace("field_", "file_types_");

                var $this = jQuery('#' + fileId);
                var $controlGroup = $this.parents(".control-group").first();
                var $helpBlock = $controlGroup.find(".help-block").first();

                var form_id = $this.closest('form').find('#form_id').val();
                var error_type = (jQuery('#form_tooltip_error_' + form_id).val() == 'advance') ? 'advance' : 'normal';

                if (error_type == 'advance')
                {
                    arf_show_tooltip($controlGroup, $helpBlock, arf_invalid_file_message);
                } else {
                    if (!$helpBlock.length) {
                        $helpBlock = jQuery('<div class="help-block"><ul><li>' + arf_invalid_file_message + '</li></ul></div>');
                        $controlGroup.find('.controls').append($helpBlock);
                        $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                    }
                    else
                    {
                        $helpBlock = jQuery('<ul role="alert"><li>' + arf_invalid_file_message + '</li></ul>');
                        $controlGroup.find('.controls .help-block').append($helpBlock);
                        $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                    }
                }

                upload_flag++;

            }
        });

        if (upload_flag > 0)
        {
            jQuery('#submit_loader').hide();
            jQuery(object).find('input[type="submit"]').show('');
            is_goto_next = false;
        }
        else
        {

            var arf_bowser_name = jQuery(object).find('#arf_browser_name').val();
            var arf_bowser_version = jQuery(object).find('#arf_browser_name').attr('data-version');

            if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                jQuery(object).find('.arf_submit_btn .arfstyle-label').hide();
                jQuery(object).find('.arf_submit_btn .arf_ie_image').css('display', 'inline-block');
            } else {
                jQuery(object).find('.arf_submit_btn').toggleClass('data-loading');
            }

            jQuery(object).find('.arf_submit_btn').attr('disabled', true);

            arfformsubmission(object, ajax_url, 'no');

        }

    } else {

        // for recaptcha start		

        if (jQuery(object).find("#vpb_captcha_code_" + form_id).length > 0 && jQuery(object).find("#vpb_captcha_code_" + form_id).is(":visible") == true)
        {
            event.preventDefault();
            checkRecaptcha_new(object, ajax_url);

        }
        else if (jQuery(object).find("#recaptcha_style").length > 0 && jQuery(object).find("#recaptcha_style").is(":visible") == true && jQuery(object).find("#field_captcha").attr('data-type') == 'recaptcha') {

            event.preventDefault();
            checkRecaptcha_new(object, ajax_url);
        }
        else {

            var is_submit_form = jQuery('#is_submit_form_' + form_id).val();

            if (is_submit_form == 1) {

                jQuery('#is_submit_form_' + form_id).val('0');

                var arf_bowser_name = jQuery(object).find('#arf_browser_name').val();
                var arf_bowser_version = jQuery(object).find('#arf_browser_name').attr('data-version');

                if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                    jQuery(object).find('.arf_submit_btn .arfstyle-label').hide();
                    jQuery(object).find('.arf_submit_btn .arf_ie_image').css('display', 'inline-block');
                } else {
                    jQuery(object).find('.arf_submit_btn').toggleClass('data-loading');
                }

                jQuery(object).submit();		// submit from here.
            }

        }
        // for recaptcha end
    }

}

jQuery('.original_normal').on('change', function (e) {
    var id = jQuery(this).attr('id');
    id = id.replace('field_', '');

    var fileName = jQuery(this).val();
    fileName = fileName.replace(/C:\\fakepath\\/i, '');
    if (fileName != '') {
        jQuery('#file_name_' + id).html(fileName);
    }
});

jQuery(document).on("click", function () {
    jQuery('.arfmodal input[type="checkbox"]').on('ifChanged', function (event) {
        jQuery(this).trigger('change');
    });
    jQuery('.arf_flymodal input[type="checkbox"]').on('ifChanged', function (event) {
        jQuery(this).trigger('change');
    });

    jQuery('.arfmodal input[type="radio"]').on('ifChecked', function (event) {
        jQuery(this).trigger('click');
    });
    jQuery('.arf_flymodal input[type="radio"]').on('ifChecked', function (event) {
        jQuery(this).trigger('click');
    });
});


function arf_reset_page_nav() {
    jQuery('.arfpagebreakform').each(function () {
        var form_id = jQuery(this).find('input[name="form_id"]').val();
        var last_show_page = jQuery('#last_show_page_' + form_id).val();
        last_show_page = parseInt(last_show_page) + parseInt(1);
        if (last_show_page > 1) {
            jQuery(this).find('.page_break_nav').removeClass('arf_page_last, arf_page_prev');
            jQuery(this).find('#page_nav_' + last_show_page).addClass('arf_page_last');
        }
    });
}

function arf_show_tooltip(control_obj, helpblock_obj, message) {

    jQuery(control_obj).find('.popover-content').html(message);

    if (typeof (control_obj) === undefined || typeof (helpblock_obj) === undefined || typeof (message) === undefined) {
        return;
    }
    var form_id = jQuery(control_obj).find('input,select,textarea').closest('form').find('#form_id').val();

    var color = jQuery('#form_tooltip_error_' + form_id).attr('data-color');

    var position = jQuery('#form_tooltip_error_' + form_id).attr('data-position');

    var border_color = "#f4f4f4";//jQuery('#form_tooltip_error_'+form_id).attr('data-bcolor');

    var error_tip_id = control_obj.attr('id');
    var error_tip_id = error_tip_id.replace('_container', '');

    var bounceeffect = "bounceInRight";
    if (position == 'left')
    {
        bounceeffect = "bounceInLeft";
    }
    else if (position == 'right')
    {
        bounceeffect = "bounceInRight";
    }
    else if (position == 'top')
    {
        bounceeffect = "bounceInDown";
    }
    else if (position == 'bottom')
    {
        bounceeffect = "bounceInUp";
    }

    if (jQuery(control_obj).find('.popover').length <= 0)
    {
        jQuery(control_obj).find('input,select,textarea').parents('.controls').popover('destroy');
        jQuery(control_obj).find('input,select,textarea').parents('.controls').popover({
            html: true,
            trigger: 'manual',
            placement: position,
            content: message,
            animation: false
        });
        jQuery(control_obj).find('input,select,textarea').parents('.controls').popover('show');
        jQuery(control_obj).find('.popover').addClass("animated " + bounceeffect);
    }
    else
    {
        jQuery(control_obj).find('.popover').show();
    }
}
function arf_show_tooltip_destroy(control_obj, helpblock_obj, message)
{
    if (typeof (control_obj) === undefined || typeof (helpblock_obj) === undefined || typeof (message) === undefined) {
        return;
    }
    jQuery(control_obj).find('input,select,textarea').parents('.controls').popover('destroy');
}

function arf_change_progressbar(object, current_page_number, total_pages)
{
    if (current_page_number == '') {
        return;
    }
    if (total_pages == '') {
        total_pages = jQuery(object).find('.total_survey_page').text();
    }
    var cur_page = current_page_number;

    var completed = (cur_page / total_pages) * 100;
    completed = completed.toFixed(2);

    jQuery(object).find("#arf_progress_bar .ui-progressbar-value").css("width", completed + "%").show();

    jQuery(object).find("#arf_progress_bar .ui-progressbar-value").html('<span class="ui-label">' + parseInt(completed) + '%</span>');

}

function reset_like_field(object) {
    jQuery(object).find('.arf_like_btn, .arf_dislike_btn').removeClass('active');
    jQuery(object).find('.arf_like').each(function (i) {

        var field = jQuery(this).attr('id');
        field_data = field.split('-');

        var field_id = field_data[0];
        field_id = field_id.replace('field_', '');
        field_id = 'like_' + field_id;
        var like = field_data[1];

        if (jQuery(this).is(':checked')) {
            if (like == 1) {
                jQuery('#' + field_id + '-1').addClass('active');
                jQuery('#' + field_id + '-0').removeClass('active');
            }

            if (like == 0) {
                jQuery('#' + field_id + '-0').addClass('active');
                jQuery('#' + field_id + '-1').removeClass('active');
            }
        }
    });
}

function reset_slider_field(object) {
    jQuery(object).find('.arfslider').each(function () {
        var field_id = jQuery(this).attr('id');
        field_id = field_id.replace('_slide', '');

        setTimeout(function () {
            var value = jQuery('#' + field_id).attr('data-value');
            var minnum = jQuery('#' + field_id + '_slide').attr('data-slider-min');
            value = value ? parseFloat(value) : parseFloat(minnum);
            jQuery('#' + field_id + '_slide').slider('setValue', value);
            jQuery('#' + field_id).val(value).trigger('change');
        }, 300);
    });

}

function arf_change_modal_slider(object) {
    jQuery(object).find('.arfslider').each(function () {
        var field_id = jQuery(this).attr('id');
        field_id = field_id.replace('_slide', '');

        setTimeout(function () {
            var value = jQuery('#' + field_id + '_slide').slider('getValue');
            var minnum = jQuery('#' + field_id + '_slide').attr('data-slider-min');
            value = value ? parseFloat(value) : parseFloat(minnum);

            if (jQuery('#' + field_id + '_slider').is(':visible')) {
                jQuery('#' + field_id + '_slider').trigger('mousedown').trigger('mouseup');
                jQuery('#' + field_id + '_slide').slider('setValue', value);
                jQuery('#' + field_id).val(value);
            }
        }, 300);
    });
}

function arf_change_slider(object, f_id, heading_id) {

    if (heading_id == 1) {
        jQuery(object).find('#heading_' + f_id + ' .arfslider').each(function () {
            var field_id = jQuery(this).attr('id');
            field_id = field_id.replace('_slide', '');

            setTimeout(function () {
                var value = jQuery('#' + field_id + '_slide').slider('getValue');
                var minnum = jQuery('#' + field_id + '_slide').attr('data-slider-min');
                value = value ? parseFloat(value) : parseFloat(minnum);

                jQuery('#' + field_id + '_slider').trigger('mousedown').trigger('mouseup');
                jQuery('#' + field_id + '_slide').slider('setValue', value);
                jQuery('#' + field_id).val(value);
            }, 300);

        });
    } else {
        jQuery(object).find('#arf_field_' + f_id + '_container .arfslider').each(function () {
            var field_id = jQuery(this).attr('id');
            field_id = field_id.replace('_slide', '');

            setTimeout(function () {
                var value = jQuery('#' + field_id + '_slide').slider('getValue');
                var minnum = jQuery('#' + field_id + '_slide').attr('data-slider-min');
                value = value ? parseFloat(value) : parseFloat(minnum);


                jQuery('#' + field_id + '_slider').trigger('mousedown').trigger('mouseup');
                jQuery('#' + field_id + '_slide').slider('setValue', value);
                jQuery('#' + field_id).val(value);
            }, 300);
        });
    }
}

function arf_password_meter(field_key) {
    if (field_key == '' || field_key == 'undefined') {
        return;
    }
    var password = jQuery('#field_' + field_key).val();

    if (typeof (__ARFSTRRNTH_INDICATOR) != 'undefined') {
        var strenth_indicator = __ARFSTRRNTH_INDICATOR;
    } else {
        var strenth_indicator = 'Strength indicator';
    }
    if (typeof (__ARFSTRRNTH_SHORT) != 'undefined') {
        var strenth_short = __ARFSTRRNTH_SHORT;
    } else {
        var strenth_short = 'Short';
    }
    if (typeof (__ARFSTRRNTH_BAD) != 'undefined') {
        var strenth_bad = __ARFSTRRNTH_BAD;
    } else {
        var strenth_bad = 'Bad';
    }
    if (typeof (__ARFSTRRNTH_GOOD) != 'undefined') {
        var strenth_good = __ARFSTRRNTH_GOOD;
    } else {
        var strenth_good = 'Good';
    }
    if (typeof (__ARFSTRRNTH_STRONG) != 'undefined') {
        var strenth_strong = __ARFSTRRNTH_STRONG;
    } else {
        var strenth_strong = 'Strong';
    }
    var strength = '';
    var strength_label = strenth_indicator;

    if (password.length < 4 && password.length > 0)
    {
        strength = 'short';
        strength_label = strenth_short;
    }

    if (password.length > 3)
    {
        strength = 'bad';
        strength_label = strenth_bad;
    }

    if (password.length > 5)
    {
        strength = 'good';
        strength_label = strenth_good;
    }

    //if password contains both lower and uppercase characters, increase strength value
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) && password.length > 5)
    {
        strength = 'good';
        strength_label = strenth_good;
    }

    //if it has one special character, increase strength value
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/) && password.match(/([a-zA-Z])/) && password.match(/([0-9])/) && password.length > 5)
    {
        strength = 'strong';
        strength_label = strenth_strong;
    }

    if (strength != '')
    {
        jQuery('#strenth_meter_' + field_key + ' .arf_strenth_meter').removeClass('short bad good strong').addClass(strength);
        jQuery('#strenth_meter_' + field_key + ' .inside_title').html(strength_label);
    }
    else
    {
        jQuery('#strenth_meter_' + field_key + ' .arf_strenth_meter').removeClass('short bad good strong');
        jQuery('#strenth_meter_' + field_key + ' .inside_title').html(strenth_indicator);
    }

}

function arf_validate_form_outside(object, event)
{
    var form_id = jQuery(object).find("input[name='form_id']").val();
    var form = jQuery(object).serialize();
    var ajax_url = '';

    if (typeof (ajaxurl) != 'undefined') {
        ajax_url = ajaxurl;
    } else if (typeof (__ARFAJAXURL) != 'undefined') {
        ajax_url = __ARFAJAXURL;
    }
    if (ajax_url == '') {
        return false;
    }
    //show loader
    var arf_bowser_name = jQuery(object).find('#arf_browser_name').val();
    var arf_bowser_version = jQuery(object).find('#arf_browser_name').attr('data-version');

    if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
        jQuery(object).find('.arf_submit_btn .arfstyle-label').hide();
        jQuery(object).find('.arf_submit_btn .arf_ie_image').show();
    } else {
        jQuery(object).find('.arf_submit_btn').addClass('data-loading');
    }

    ajax_url = is_ssl_replace(ajax_url);
    jQuery.ajax({
        type: "POST", url: ajax_url,
        data: form + "&action=arf_prevalidate_field",
        success: function (res) {
            // hide loader
            if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                jQuery(object).find('.arf_submit_btn .arfstyle-label').show();
                jQuery(object).find('.arf_submit_btn .arf_ie_image').hide();
            } else {
                jQuery(object).find('.arf_submit_btn').removeClass('data-loading');
            }

            if (res == 0)
            {
                jQuery('#arffrm_' + form_id + '_container #arf_message_error').hide();
                jQuery(object).find("#arf_validate_outside_" + form_id).val('0');
                jQuery(object).trigger('submit');
            }
            else
            {
                var remain_error = [];
                var result = res.split('~|~');
                var last_show_page = jQuery('#last_show_page_' + form_id).val();

                if (result.length > 0 && last_show_page !== undefined && last_show_page != '')
                {

                    for (key in result)
                    {
                        var field_data = result[key];
                        if (field_data != '')
                        {
                            var field_data1 = field_data.split('^|^');
                            var field_id = field_data1[0];
                            var error_msg = field_data1[1];

                            if (field_id == 'arf_message_error') {

                            } else {
                                // get first field
                                var first_field_id = field_id;
                                var error_page_id = get_error_page_id(form_id, field_id);
                                break;
                            }
                        }
                    }

                    // get all fields id 
                    var total_error = 0;
                    var error_page_fields = window['arf_page_fields'][form_id][error_page_id];

                    for (key in result)
                    {
                        var field_data = result[key];
                        if (field_data != '')
                        {
                            var field_data1 = field_data.split('^|^');
                            var field_id = field_data1[0];
                            var error_msg = field_data1[1];

                            if (field_id == 'arf_message_error') {
                                total_error++;
                            } else {

                                var tmpres = is_field_in_page(field_id, error_page_fields);
                                if (tmpres == false) {
                                    delete result[key];
                                } else {
                                    total_error++;
                                }
                            }
                        }
                    }

                    if (total_error > 0)
                    {
                        // go to previous page

                        var form_key = jQuery(object).find("input[name='form_key']").val();
                        var current_page = jQuery(object).find('.page_break:visible').attr('id');
                        current_page = current_page.replace('page_', '');

                        if (error_page_id !== undefined && error_page_id != '' && error_page_id != last_show_page) {
                            go_previous(error_page_id, form_id, 'no', form_key);
                        }
                        if (error_page_id !== undefined && error_page_id != '' && error_page_id != last_show_page) {
                            var timeout = 510;
                        } else {
                            var timeout = 10;
                        }


                        setTimeout(function () {
                            if (result.length > 0)
                            {
                                jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().hide();
                                var i = 1;
                                var focus_field_id = '';
                                var focus_error_msg = '';
                                for (key in result)
                                {
                                    var field_data = result[key];
                                    if (field_data != '')
                                    {
                                        var field_data = field_data.split('^|^');
                                        var field_id = field_data[0];
                                        var error_msg = field_data[1];

                                        if (field_id == 'arf_message_error') {
                                            jQuery('#arffrm_' + form_id + '_container #arf_message_error .msg-description-success').first().html(error_msg);
                                            jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().show();

                                            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().offset().top - 130}, 'slow');

                                        } else {
                                            arf_show_validate_message(form_id, field_id, error_msg);
                                            if (i == 1)
                                            {
                                                focus_field_id = field_id;
                                                focus_error_msg = error_msg;
                                            }
                                        }
                                        i++;
                                    }
                                }

                                if (focus_field_id != '' && focus_error_msg != '')
                                {
                                    if (jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').length > 0)
                                    {
                                        var scrolltop = jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top;
                                        jQuery(window.opera ? '.arfmodal-body' : '.arfmodal-body').animate({scrollTop: jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top - jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield').first()).offset().top - 50}, 'slow');
                                        var tmp_div_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first().attr('id');
                                        var tmp_field_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                        if (jQuery('#' + tmp_field_id).is('select')) {
                                            jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                        } else {
                                            jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                        }
                                    }
                                    else
                                    {
                                        jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery(jQuery(object).find('.arfformfield.arf_error').first()).offset().top - 100}, 'slow');

                                        var tmp_div_id = jQuery(object).find('.arfformfield.arf_error').first().attr('id');
                                        var tmp_field_id = jQuery(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                        if (jQuery('#' + tmp_field_id).is('select')) {
                                            jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                        } else {
                                            jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                        }
                                    }

                                    revalidate_focus_outsidevalidate(tmp_field_id, tmp_div_id, focus_error_msg);
                                }

                            }
                        }, timeout);
                        //end of show page break error
                    }

                }
                else
                {

                    if (result.length > 0)
                    {
                        jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().hide();
                        var i = 1;
                        var focus_field_id = '';
                        var focus_error_msg = '';
                        for (key in result)
                        {
                            var field_data = result[key];
                            if (field_data != '')
                            {
                                var field_data = field_data.split('^|^');
                                var field_id = field_data[0];
                                var error_msg = field_data[1];

                                if (field_id == 'arf_message_error') {
                                    jQuery('#arffrm_' + form_id + '_container #arf_message_error .msg-description-success').first().html(error_msg);
                                    jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().show();

                                    jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().offset().top - 130}, 'slow');

                                } else {
                                    arf_show_validate_message(form_id, field_id, error_msg);
                                    if (i == 1)
                                    {
                                        focus_field_id = field_id;
                                        focus_error_msg = error_msg;
                                    }
                                }
                                i++;
                            }
                        }

                        if (focus_field_id != '' && focus_error_msg != '')
                        {
                            if (jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').length > 0)
                            {
                                var scrolltop = jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top;
                                jQuery(window.opera ? '.arfmodal-body' : '.arfmodal-body').animate({scrollTop: jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top - jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield').first()).offset().top - 50}, 'slow');
                                var tmp_div_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first().attr('id');
                                var tmp_field_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                if (jQuery('#' + tmp_field_id).is('select')) {
                                    jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                } else {
                                    jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                }
                            }
                            else
                            {
                                jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery(jQuery(object).find('.arfformfield.arf_error').first()).offset().top - 100}, 'slow');

                                var tmp_div_id = jQuery(object).find('.arfformfield.arf_error').first().attr('id');
                                var tmp_field_id = jQuery(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                if (jQuery('#' + tmp_field_id).is('select')) {
                                    jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                } else {
                                    jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                }
                            }

                            revalidate_focus_outsidevalidate(tmp_field_id, tmp_div_id, focus_error_msg);
                        }

                    }
                }
            }

        }
    });

}

function arf_is_validateform_outside(object, event)
{
    var form_id = jQuery(object).find("input[name='form_id']").val();
    var form = jQuery(object).serialize();
    var ajax_url = '';

    if (typeof (ajaxurl) != 'undefined') {
        ajax_url = ajaxurl;
    } else if (typeof (__ARFAJAXURL) != 'undefined') {
        ajax_url = __ARFAJAXURL;
    }
    if (ajax_url == '') {
        return false;
    }
    //show loader
    var arf_bowser_name = jQuery(object).find('#arf_browser_name').val();
    var arf_bowser_version = jQuery(object).find('#arf_browser_name').attr('data-version');
    if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
        jQuery(object).find('.arf_submit_btn .arfstyle-label').hide();
        jQuery(object).find('.arf_submit_btn .arf_ie_image').show();
    } else {
        jQuery(object).find('.arf_submit_btn').addClass('data-loading');
    }
    ajax_url = is_ssl_replace(ajax_url);
    jQuery.ajax({
        type: "POST", url: ajax_url,
        data: form + "&action=arf_is_prevalidateform_outside",
        success: function (res) {
            // hide loader
            if (arf_bowser_name == 'Opera' || (arf_bowser_name == 'Internet Explorer' && arf_bowser_version <= 9)) {
                jQuery(object).find('.arf_submit_btn .arfstyle-label').show();
                jQuery(object).find('.arf_submit_btn .arf_ie_image').hide();
            } else {
                jQuery(object).find('.arf_submit_btn').removeClass('data-loading');
            }

            if (res.indexOf('^arf_populate=') != -1)
            {
                var data = res.split('^arf_populate=');
                var new_data = data[1].replace('^arf_populate=', '');
                var res = data[2];

                var result = new_data.split('~|~');
                if (result.length > 0)
                {
                    for (key in result)
                    {
                        var field_data = result[key];
                        if (field_data != '')
                        {
                            var field_data = field_data.split('^|^');
                            var field_name = field_data[0];
                            var field_value = field_data[1];
                            jQuery('input[name="' + field_name + '"], textarea[name="' + field_name + '"], select[name="' + field_name + '"]').first().val(field_value);
                        }
                    }
                }
            }

            if (res == 0)
            {
                jQuery('#arffrm_' + form_id + '_container #arf_message_error').hide();
                jQuery(object).find("#arf_is_validate_outside_" + form_id).val('0');
                jQuery(object).trigger('submit');
            }
            else
            {
                var remain_error = [];
                var result = res.split('~|~');
                var last_show_page = jQuery('#last_show_page_' + form_id).val();

                if (result.length > 0 && last_show_page !== undefined && last_show_page != '')
                {

                    for (key in result)
                    {
                        var field_data = result[key];
                        if (field_data != '')
                        {
                            var field_data1 = field_data.split('^|^');
                            var field_id = field_data1[0];
                            var error_msg = field_data1[1];

                            if (field_id == 'arf_message_error') {

                            } else {
                                // get first field
                                var first_field_id = field_id;
                                var error_page_id = get_error_page_id(form_id, field_id);
                                break;
                            }
                        }
                    }

                    // get all fields id 
                    var total_error = 0;
                    var error_page_fields = window['arf_page_fields'][form_id][error_page_id];

                    for (key in result)
                    {
                        var field_data = result[key];
                        if (field_data != '')
                        {
                            var field_data1 = field_data.split('^|^');
                            var field_id = field_data1[0];
                            var error_msg = field_data1[1];

                            if (field_id == 'arf_message_error') {
                                total_error++;
                            } else {

                                var tmpres = is_field_in_page(field_id, error_page_fields);
                                if (tmpres == false) {
                                    delete result[key];
                                } else {
                                    total_error++;
                                }
                            }
                        }
                    }

                    if (total_error > 0)
                    {
                        // go to previous page

                        var form_key = jQuery(object).find("input[name='form_key']").val();
                        var current_page = jQuery(object).find('.page_break:visible').attr('id');
                        current_page = current_page.replace('page_', '');

                        if (error_page_id !== undefined && error_page_id != '' && error_page_id != last_show_page) {
                            go_previous(error_page_id, form_id, 'no', form_key);
                        }
                        if (error_page_id !== undefined && error_page_id != '' && error_page_id != last_show_page) {
                            var timeout = 510;
                        } else {
                            var timeout = 10;
                        }


                        setTimeout(function () {
                            if (result.length > 0)
                            {
                                jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().hide();
                                var i = 1;
                                var focus_field_id = '';
                                var focus_error_msg = '';
                                for (key in result)
                                {
                                    var field_data = result[key];
                                    if (field_data != '')
                                    {
                                        var field_data = field_data.split('^|^');
                                        var field_id = field_data[0];
                                        var error_msg = field_data[1];

                                        if (field_id == 'arf_message_error') {
                                            jQuery('#arffrm_' + form_id + '_container #arf_message_error .msg-description-success').first().html(error_msg);
                                            jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().show();

                                            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().offset().top - 130}, 'slow');

                                        } else {
                                            arf_show_validate_message(form_id, field_id, error_msg);
                                            if (i == 1)
                                            {
                                                focus_field_id = field_id;
                                                focus_error_msg = error_msg;
                                            }
                                        }
                                        i++;
                                    }
                                }

                                if (focus_field_id != '' && focus_error_msg != '')
                                {
                                    if (jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').length > 0)
                                    {
                                        var scrolltop = jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top;
                                        jQuery(window.opera ? '.arfmodal-body' : '.arfmodal-body').animate({scrollTop: jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top - jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield').first()).offset().top - 50}, 'slow');
                                        var tmp_div_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first().attr('id');
                                        var tmp_field_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                        if (jQuery('#' + tmp_field_id).is('select')) {
                                            jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                        } else {
                                            jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                        }
                                    }
                                    else
                                    {
                                        jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery(jQuery(object).find('.arfformfield.arf_error').first()).offset().top - 100}, 'slow');

                                        var tmp_div_id = jQuery(object).find('.arfformfield.arf_error').first().attr('id');
                                        var tmp_field_id = jQuery(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                        if (jQuery('#' + tmp_field_id).is('select')) {
                                            jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                        } else {
                                            jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                        }
                                    }

                                    revalidate_focus_outsidevalidate(tmp_field_id, tmp_div_id, focus_error_msg);
                                }

                            }
                        }, timeout);
                        //end of show page break error
                    }

                }
                else
                {

                    if (result.length > 0)
                    {
                        jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().hide();
                        var i = 1;
                        var focus_field_id = '';
                        var focus_error_msg = '';
                        for (key in result)
                        {
                            var field_data = result[key];
                            if (field_data != '')
                            {
                                var field_data = field_data.split('^|^');
                                var field_id = field_data[0];
                                var error_msg = field_data[1];

                                if (field_id == 'arf_message_error') {
                                    jQuery('#arffrm_' + form_id + '_container #arf_message_error .msg-description-success').first().html(error_msg);
                                    jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().show();

                                    jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#arffrm_' + form_id + '_container #arf_message_error').first().offset().top - 130}, 'slow');

                                } else {
                                    arf_show_validate_message(form_id, field_id, error_msg);
                                    if (i == 1)
                                    {
                                        focus_field_id = field_id;
                                        focus_error_msg = error_msg;
                                    }
                                }
                                i++;
                            }
                        }

                        if (focus_field_id != '' && focus_error_msg != '')
                        {
                            if (jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').length > 0)
                            {
                                var scrolltop = jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top;
                                jQuery(window.opera ? '.arfmodal-body' : '.arfmodal-body').animate({scrollTop: jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first()).offset().top - jQuery(jQuery('.arfmodal-body').parent(object).find('.arfformfield').first()).offset().top - 50}, 'slow');
                                var tmp_div_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error').first().attr('id');
                                var tmp_field_id = jQuery('.arfmodal-body').parent(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                if (jQuery('#' + tmp_field_id).is('select')) {
                                    jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                } else {
                                    jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                }
                            }
                            else
                            {
                                jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery(jQuery(object).find('.arfformfield.arf_error').first()).offset().top - 100}, 'slow');

                                var tmp_div_id = jQuery(object).find('.arfformfield.arf_error').first().attr('id');
                                var tmp_field_id = jQuery(object).find('.arfformfield.arf_error input, .arfformfield.arf_error select, .arfformfield.arf_error textarea').first().attr('id');

                                if (jQuery('#' + tmp_field_id).is('select')) {
                                    jQuery(object).find('#' + tmp_div_id + ' .dropdown-toggle[data-toggle="arfdropdown"]').focus();
                                } else {
                                    jQuery(object).find('#' + tmp_div_id + ' input, #' + tmp_div_id + ' textarea').first().focus();
                                }
                            }

                            revalidate_focus_outsidevalidate(tmp_field_id, tmp_div_id, focus_error_msg);
                        }

                    }
                }
            }

        }
    });

}

function arf_show_validate_message(form_id, field_id, required_message)
{
    if (field_id == '') {
        return;
    }
    var field_key = jQuery('input[name="item_meta[' + field_id + ']"], textarea[name="item_meta[' + field_id + ']"], select[name="item_meta[' + field_id + ']"]').first().attr('id');

    var form_key = jQuery('#' + field_key).parents('form').first().attr('id');

    jQuery('#' + form_key).removeClass('arf_success').addClass('arf_error');

    jQuery('#' + field_key).attr('aria-invalid', 'true');

    var div_id = jQuery('#' + field_key).parents('.arfformfield').attr('id');

    jQuery('.arfmodal-body').find('#' + div_id).removeClass('arf_success').addClass('arf_error');

    jQuery('#' + div_id).removeClass('arf_success').addClass('arf_error');

    var $this = jQuery('#' + field_key);
    var $controlGroup = $this.parents(".control-group").first();
    var $helpBlock = $controlGroup.find(".help-block").first();

    var error_type = (jQuery('#form_tooltip_error_' + form_id).val() == 'advance') ? 'advance' : 'normal';

    if (error_type == 'advance')
    {
        arf_show_tooltip($controlGroup, $helpBlock, required_message);
    } else {
        if (!$helpBlock.length) {
            $helpBlock = jQuery('<div class="help-block"><ul><li>' + required_message + '</li></ul></div>');
            $controlGroup.find('.controls').append($helpBlock);
            $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
        }
        else
        {
            $helpBlock = jQuery('<ul role="alert"><li>' + required_message + '</li></ul>');
            $controlGroup.find('.controls .help-block').append($helpBlock);
            $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
        }
    }

}

function revalidate_focus_outsidevalidate(tmp_field_id, tmp_div_id, required_message)
{
    if (tmp_field_id != '' && tmp_field_id != 'undefined' && required_message != '' && required_message != 'undefined')
    {
        setTimeout(function () {

            if (jQuery('#' + tmp_field_id).attr('aria-invalid') == 'false') {

                jQuery('#' + tmp_field_id).attr('aria-invalid', 'true');

                jQuery('.arfmodal-body').find('#' + tmp_div_id).removeClass('arf_success').addClass('arf_error');

                jQuery('#' + tmp_div_id).removeClass('arf_success').addClass('arf_error');

                if (required_message == '' || required_message === undefined) {
                    required_message = 'This field cannot be blank.';
                }
                var $this = jQuery('#' + tmp_field_id);
                var $controlGroup = $this.parents(".control-group").first();
                var $helpBlock = $controlGroup.find(".help-block").first();

                var form_id = $this.closest('form').find('#form_id').val();
                var error_type = (jQuery('#form_tooltip_error_' + form_id).val() == 'advance') ? 'advance' : 'normal';

                if (error_type == 'advance')
                {
                    arf_show_tooltip($controlGroup, $helpBlock, required_message);
                } else {
                    if (!$helpBlock.length) {
                        $helpBlock = jQuery('<div class="help-block"><ul><li>' + required_message + '</li></ul></div>');
                        $controlGroup.find('.controls').append($helpBlock);
                        $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                    }
                    else
                    {
                        $helpBlock = jQuery('<ul role="alert"><li>' + required_message + '</li></ul>');
                        $controlGroup.find('.controls .help-block').append($helpBlock);
                        $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                    }
                }

            }

        }, 10);
    }

}

// reset fields outside.
function arf_resetform_outside(object, form_id)
{
    var form = jQuery(object).serialize();
    var ajax_url = '';

    if (typeof (ajaxurl) != 'undefined') {
        ajax_url = ajaxurl;
    } else if (typeof (__ARFAJAXURL) != 'undefined') {
        ajax_url = __ARFAJAXURL;
    }

    if (ajax_url == '') {
        return false;
    }
    ajax_url = is_ssl_replace(ajax_url);
    jQuery.ajax({
        type: "POST", url: ajax_url,
        data: form + "&action=arf_is_resetformoutside",
        success: function (res) {
            if (res != '')
            {
                var result = res.split('~|~');
                if (result.length > 0)
                {
                    for (key in result)
                    {
                        var field_data = result[key];
                        if (field_data != '')
                        {
                            var field_data = field_data.split('^|^');
                            var field_name = field_data[0];
                            var field_value = field_data[1];
                            jQuery('input[name="' + field_name + '"], textarea[name="' + field_name + '"], select[name="' + field_name + '"]').first().val(field_value);
                        }
                    }
                }

            }
        }
    });
}

function changepreviewslider() {
    jQuery('.arfshowmainform').find('.arfslider').each(function () {
        var field_id = jQuery(this).attr('id');
        field_id = field_id.replace('_slide', '');
        setTimeout(function () {
            if (jQuery('#' + field_id + '_slider').is(':visible')) {
                var value = jQuery('#' + field_id + '_slide').slider('getValue');
                var minnum = jQuery('#' + field_id + '_slide').attr('data-slider-min');
                value = value ? parseFloat(value) : parseFloat(minnum);

                jQuery('#' + field_id + '_slider').trigger('mousedown').trigger('mouseup');
                jQuery('#' + field_id + '_slide').slider('setValue', value);
                jQuery('#' + field_id).val(value);
            }
        }, 510);
    });
}


function get_error_page_id(form_id, field_id)
{
    var pages = window['arf_page_fields'][form_id];
    for (ky in pages)
    {
        var page_fields = pages[ky];
        var page_num = ky;

        for (fid in page_fields)
        {
            if (page_fields[fid] == field_id)
            {
                return page_num;
                break;
            }
        }
    }
}

function is_field_in_page(field_id, error_page_fields)
{
    for (k in error_page_fields)
    {
        if (field_id == error_page_fields[k])
        {
            return true;
            break;
        }
    }
    return false;
}


function arf_calculate_total(form_key, field_id, dep_array) {

    var object = jQuery('#form_' + form_key);
    
    var contour = 1;
    var total_rule = dep_array.length;
    for (key in dep_array) {

        if (contour > total_rule) {
            return false;
        }
        
        var f_id = dep_array[key];

        var f_exp = window['arf_runningtotal_fields'][form_key][f_id]['regexp'];

        if (f_exp != '')
        {
            arf_apply_running_total(object, f_id, f_exp, form_key);
        }
        contour++;
    }
}

function arf_apply_running_total(object, f_id, f_exp, form_key)
{

    setTimeout(function () {
        var dep_fields = window['arf_runningtotal_fields'][form_key][f_id]['fields'];
        var regexp = f_exp;

        if (dep_fields)
        {
            for (key in dep_fields)
            {
                var field_data = dep_fields[key];
                var field_id = field_data['field_id'];
                var field_tpye = field_data['field_type'];

                var value1 = '';

                if (field_tpye == 'checkbox') {

                    var myval = field_id.split(".");
                    var firstval = myval[0];
                    var pos = myval[1];
                    var value1 = jQuery('#field_' + firstval + '-' + pos + ':checked').val();

                } else {

                    if (field_tpye == 'radio' || field_tpye == 'like') {
                        var value1 = jQuery('input[name="item_meta[' + field_id + ']"]:checked').val();
                    } else if (field_tpye == 'select') {
                        var value1 = jQuery('select[name="item_meta[' + field_id + ']"]').val();
                    } else if (field_tpye == 'textarea') {
                        var value1 = jQuery('textarea[name="item_meta[' + field_id + ']"]').val();
                    } else if (field_tpye == 'scale') {
                        widget_id = jQuery('input[name="item_meta[' + field_id + ']"]').attr('id');
                        widget_id = widget_id.replace('field_', '');

                        var color = jQuery('#field_' + widget_id).attr('data-color');
                        var datasize = jQuery('#field_' + widget_id).attr('data-size');
                        var len = jQuery('#' + widget_id + ' .ratings_vote_' + color + datasize).length;
                        var value1 = parseInt(len) - parseInt(1);
                        if (value1 == 0) {
                            value1 = '';
                        }
                    } else {
                        var value1 = jQuery('input[name="item_meta[' + field_id + ']"]').val();
                    }
                }

                var value1 = value1 ? value1 : 0;

                regexp = regexp.replace('{' + field_id + '}', Number(value1));
            }

        }

        regexp = regexp.replace(/(\n|\r\n)/g, '');

        var validate_regex = /^[0-9 -/*\(\)]+$/i;
        var total = '0';
        if (validate_regex.test(regexp))
        {
            try {
                var total = eval(regexp);
            } catch (e) {
            }
        }
        total = total ? total : 0;
        if (total == 0) {
            total = '0.00';
        } else {
            total = total.toFixed(2);
        }
        jQuery('#arf_running_total_' + f_id).html(total);
        jQuery('#arf_item_meta_' + f_id).val(total);
    }, 100);
}

function reset_running_total(object) {
    jQuery(object).find('.arf_running_total').each(function () {
        var mainstr = jQuery(this).attr('data-arfcalc');
        mainstr = mainstr ? mainstr : '';
        mainstr = mainstr.replace(/(\n|\r\n)/g, '');

        var validate_regex = /^[0-9 -/*\(\)]+$/i;
        var value = 0;
        if (mainstr != '' && validate_regex.test(mainstr)) {
            try {
                value = eval(mainstr);
            } catch (e) {
            }
        }
        value = value ? value : 0;
        value = value.toFixed(2);
        jQuery(this).html(value);
    });
}

function HextoHsl(hex) {
    var hex = '#' + hex;
    if (hex.length == 7) {
        var rgb = [parseInt('0x' + hex.substring(1, 3)) / 255, parseInt('0x' + hex.substring(3, 5)) / 255, parseInt('0x' + hex.substring(5, 7)) / 255];
    } else if (hex.length == 4) {
        var rgb = [parseInt('0x' + hex.substring(1, 2)) / 15, parseInt('0x' + hex.substring(2, 3)) / 15, parseInt('0x' + hex.substring(3, 4)) / 15];
    } else {
        return 1;
    }

    var min, max, delta, h, s, l;
    var r = rgb[0], g = rgb[1], b = rgb[2];
    min = Math.min(r, Math.min(g, b));
    max = Math.max(r, Math.max(g, b));
    delta = max - min;
    l = (min + max) / 2;
    s = 0;
    if (l > 0 && l < 1) {
        s = delta / (l < 0.5 ? (2 * l) : (2 - 2 * l));
    }
    h = 0;
    if (delta > 0) {
        if (max == r && max != g)
            h += (g - b) / delta;
        if (max == g && max != b)
            h += (2 + (b - r) / delta);
        if (max == b && max != r)
            h += (4 + (r - g) / delta);
        h /= 6;
    }
    //return [h, s, l];
    return l;
}
function reset_colorpicker(object) {
    jQuery(object).find('.arfhiddencolor').each(function () {
        var field_key = jQuery(this).attr('id');
        field_key = field_key.replace('field_', '');
        var color = jQuery(this).val();
        jQuery('#arfcolorpicker_' + field_key).find('.arfcolorvalue').text(color);
        jQuery('#arfcolorpicker_' + field_key).find('.arfcolorvalue').css('background', color);
        var hex = color.replace('#', '');
        var arffontcolor = hex == '' ? '#000000' : (HextoHsl(hex) > 0.5 ? '#000000' : '#ffffff');
        jQuery('#arfcolorpicker_' + field_key).find('.arfcolorvalue').css('color', arffontcolor);
    });
}

function arfresetcolor(field_key)
{
    jQuery('#arfcolorpicker_' + field_key).find('.arfcolorvalue').text('');
    jQuery('#arfcolorpicker_' + field_key).find('.arfcolorvalue').css('background', '');
    jQuery('#field_' + field_key).val('').trigger('change');
}

function is_ssl_replace(url)
{
    if (location.protocol == 'https:')
    {
        url = url.replace('http://', 'https://');
    }
    return url;
}
function arf_validate_file(e, fid)
{
    var files = jQuery('#' + fid).find("[type=file]");

    var validfile = true;

    for (var i = 0, f; f = files[i]; i++) {

        var fileid = f.id;
        var filename = f.name;

        var field_key_arr = fileid.split('field_');
        var field_key = field_key_arr[1];

        var field_type = jQuery('#file_types_' + field_key).val();
        types_arr = field_type.split(",");

        var filename2 = f.value;

        var file_index = filename2.lastIndexOf('.');// .xml

        var file_extension = filename2.substring(file_index + 1);

        file_extension = file_extension.toLowerCase();

        var arf_bowser_name = jQuery('#arf_browser_name').val();
        var arf_bowser_version = jQuery('#arf_browser_name').attr('data-version');

        var arf_file_validation = (field_type.indexOf(file_extension) >= 0) ? true : false;


        if (f.value != "")
        {
            if (file_extension != "php" && file_extension != "php3" && file_extension != "php4" && file_extension != "php5" && file_extension != "pl" && file_extension != "py" && file_extension != "jsp" && file_extension != "asp" && file_extension != "exe" && file_extension != "cgi")
            {
                if (arf_file_validation) {
                }
                else
                {
                    var $this = jQuery('#' + fileid);
                    var $controlGroup = $this.parents(".control-group").first();
                    var $helpBlock = $controlGroup.find(".help-block").first();

                    if (jQuery('#' + fileid).attr('data-invalid-message') !== undefined && jQuery('#' + fileid).attr('data-invalid-message') != '') {
                        var arf_invalid_file_message = jQuery('#' + fileid).attr('data-invalid-message');
                    } else {
                        var arf_invalid_file_message = file_error;
                    }
                    var form_id = $this.closest('form').find('#form_id').val();
                    var error_type = (jQuery('#form_tooltip_error_' + form_id).val() == 'advance') ? 'advance' : 'normal';

                    if (error_type == 'advance')
                    {
                        arf_show_tooltip($controlGroup, $helpBlock, arf_invalid_file_message);
                    } else {
                        if (!$helpBlock.length) {
                            $helpBlock = jQuery('<div class="help-block"><ul><li>' + arf_invalid_file_message + '</li></ul></div>');
                            $controlGroup.find('.controls').append($helpBlock);
                            $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                        }
                        else
                        {
                            $helpBlock = jQuery('<ul role="alert"><li>' + arf_invalid_file_message + '</li></ul>');
                            $controlGroup.find('.controls .help-block').append($helpBlock);
                            $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                        }
                    }
                    validfile = false;

                }
            }
            else
            {
                var $this = jQuery('#' + fileid);
                var $controlGroup = $this.parents(".control-group").first();
                var $helpBlock = $controlGroup.find(".help-block").first();

                if (jQuery('#' + fileid).attr('data-invalid-message') !== undefined && jQuery('#' + fileid).attr('data-invalid-message') != '') {
                    var arf_invalid_file_message = jQuery('#' + fileid).attr('data-invalid-message');
                } else {
                    var arf_invalid_file_message = file_error;
                }
                var form_id = $this.closest('form').find('#form_id').val();
                var error_type = (jQuery('#form_tooltip_error_' + form_id).val() == 'advance') ? 'advance' : 'normal';

                if (error_type == 'advance')
                {
                    arf_show_tooltip($controlGroup, $helpBlock, arf_invalid_file_message);
                } else {
                    if (!$helpBlock.length) {
                        $helpBlock = jQuery('<div class="help-block"><ul><li>' + arf_invalid_file_message + '</li></ul></div>');
                        $controlGroup.find('.controls').append($helpBlock);
                        $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                    }
                    else
                    {
                        $helpBlock = jQuery('<ul role="alert"><li>' + arf_invalid_file_message + '</li></ul>');
                        $controlGroup.find('.controls .help-block').append($helpBlock);
                        $controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
                    }
                }
                validfile = false;
            }
        }
        if (files.length == i + 1)

        {
            break;
        }
    }

    if (!(validfile)) {
        return false;
    } else {
        return true;
    }
}
function CheckFormSubmit()
{
    if (form1chk == "")
    {
        return false;
    }
    else
    {
        return true;
    }
}

function change_tabindex_radio(field, tabindex)
{
    if (jQuery(field).attr('type') == 'checkbox' || jQuery(field).attr('type') == 'radio')
    {
        jQuery(field).each(function () {
            jQuery(this).attr('tabindex', '');
        });
    }
}

jQuery(window).resize(function () {
    var width = jQuery(window).width() - 5;
    jQuery('div.arfmodal').not('#maincontainerdiv div.arfmodal, #arfformsettingpage div.arfmodal').each(function () {
        var modal_width = jQuery(this).outerWidth();
        var modalwidth = jQuery(this).find('.arfmodal-body').attr('data-modalwidth');
        if (modalwidth !== undefined) {
            modalwidth = modalwidth.replace('px', '');
        }
        var windowHeight = jQuery(window).height() + jQuery(window).scrollTop();

        if (modal_width > width && modalwidth >= modal_width)
        {
            jQuery(this).addClass('arfresponsivemodal');
            var setModalHeight = Number(windowHeight) - Number(60);

            //jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height",setModalHeight+"px");
            jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border", "0px");
            jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border-radius", "0px");
        }
        else
        {
            var mtop = jQuery(this).attr('data-mtop');
            var mleft = jQuery(this).attr('data-mleft');
            jQuery(this).css('top', mtop + 'px');
            jQuery(this).css('left', mleft + 'px');
            jQuery(this).removeClass('arfresponsivemodal');

            var windowWidth = jQuery(window).width() + jQuery(window).scrollLeft();
            var modalWidth = jQuery(this).width() + jQuery(this).scrollLeft();

            var modalFromLeft = (Number(windowWidth) - Number(modalWidth)) / Number(2);

            jQuery(this).css("left", modalFromLeft);
            jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").removeAttr("style");
        }

        var screenwidth = jQuery(window).width();
        if (screenwidth <= 770)
        {

            var windowHeight = jQuery(window).height() - Number(60);
            var actualheight = jQuery(this).find('.arf_fieldset').height();

            if (actualheight < windowHeight)
            {
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height", windowHeight + "px");
            }
        }

    });


    jQuery('div.arform_right_fly_form_block_right_main').each(function () {
        var modal_width = jQuery(this).outerWidth();
        var modalwidth = jQuery(this).find('.arfmodal-body').attr('data-modalwidth');
        modalwidth = modalwidth.replace('px', '');

        var windowHeight = jQuery(window).height() + jQuery(window).scrollTop();

        if (modal_width > width && modalwidth >= modal_width)
        {
            jQuery(this).addClass('arfresponsivemodal');
            var setModalHeight = Number(windowHeight) - Number(60);


            jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border", "0px");
            jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border-radius", "0px");
        }
        else
        {
            var mtop = jQuery(this).attr('data-mtop');
            var mleft = jQuery(this).attr('data-mleft');
            jQuery(this).css('top', mtop + 'px');
            jQuery(this).css('left', mleft + 'px');
            jQuery(this).removeClass('arfresponsivemodal');

            var windowWidth = jQuery(window).width() + jQuery(window).scrollLeft();
            var modalWidth = jQuery(this).width() + jQuery(this).scrollLeft();

            var modalFromLeft = (Number(windowWidth) - Number(modalWidth)) / Number(2);

            jQuery(this).css("left", modalFromLeft);
            jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").removeAttr("style");
        }

        var screenwidth = jQuery(window).width();
        if (screenwidth <= 770)
        {

            var windowHeight = jQuery(window).height() - Number(60);
            var actualheight = jQuery(this).find('.arf_fieldset').height();

            if (actualheight < windowHeight) {
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height", windowHeight + "px");
            }
        }

    });

    jQuery('div.arform_left_fly_form_block_left_main').each(function () {
        var modal_width = jQuery(this).outerWidth();
        var modalwidth = jQuery(this).find('.arfmodal-body').attr('data-modalwidth');
        modalwidth = modalwidth.replace('px', '');

        var windowHeight = jQuery(window).height() + jQuery(window).scrollTop();

        if (modal_width > width && modalwidth >= modal_width)
        {
            jQuery(this).addClass('arfresponsivemodal');
            var setModalHeight = Number(windowHeight) - Number(60);


            jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border", "0px");
            jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("border-radius", "0px");
        }
        else
        {
            var mtop = jQuery(this).attr('data-mtop');
            var mleft = jQuery(this).attr('data-mleft');
            jQuery(this).css('top', mtop + 'px');
            jQuery(this).css('left', mleft + 'px');
            jQuery(this).removeClass('arfresponsivemodal');

            var windowWidth = jQuery(window).width() + jQuery(window).scrollLeft();
            var modalWidth = jQuery(this).width() + jQuery(this).scrollLeft();

            var modalFromLeft = (Number(windowWidth) - Number(modalWidth)) / Number(2);

            jQuery(this).css("left", modalFromLeft);
            jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").removeAttr("style");
        }

        var screenwidth = jQuery(window).width();
        if (screenwidth <= 770)
        {
            var windowHeight = jQuery(window).height() - Number(60);
            var actualheight = jQuery(this).find('.arf_fieldset').height();

            if (actualheight < windowHeight) {
                jQuery(this).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height", windowHeight + "px");
            }
        }
    });

    jQuery('.arfshowmainform:not(.arfpreivewform)').each(function () {
        setTimeout(function () {
            var width = jQuery(this).find('.arf_fieldset').width();
            jQuery(this).find('.arf_prefix_suffix_wrapper').css('max-width', width + 'px');
        }, 500);
    });

});

function arfvalidatenumber(field, event)
{
    var nVer = navigator.appVersion;
    var nAgt = navigator.userAgent;
    var browserName = navigator.appName;
    var fullVersion = '' + parseFloat(navigator.appVersion);
    var majorVersion = parseInt(navigator.appVersion, 10);
    var nameOffset, verOffset, ix;

    // In Opera 15+, the true version is after "OPR/" 
    if ((verOffset = nAgt.indexOf("OPR/")) != -1) {
        browserName = "Opera";
    }
    // In older Opera, the true version is after "Opera" or after "Version"
    else if ((verOffset = nAgt.indexOf("Opera")) != -1) {
        browserName = "Opera";
    }
    // In MSIE, the true version is after "MSIE" in userAgent
    else if ((verOffset = nAgt.indexOf("MSIE")) != -1) {
        browserName = "Microsoft Internet Explorer";
        browserName = "Netscape";
        fullVersion = nAgt.substring(verOffset + 5);
    }
    // In Chrome, the true version is after "Chrome" 
    else if ((verOffset = nAgt.indexOf("Chrome")) != -1) {
        browserName = "Chrome";
    }
    // In Safari, the true version is after "Safari" or after "Version" 
    else if ((verOffset = nAgt.indexOf("Safari")) != -1) {
        browserName = "Safari";
    }
    // In Firefox, the true version is after "Firefox" 
    else if ((verOffset = nAgt.indexOf("Firefox")) != -1) {
        browserName = "Firefox";
    }

    // In most other browsers, "name/version" is at the end of userAgent 
    else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) <
            (verOffset = nAgt.lastIndexOf('/')))
    {
        browserName = nAgt.substring(nameOffset, verOffset);
        if (browserName.toLowerCase() == browserName.toUpperCase()) {
            browserName = navigator.appName;
        }
    }

    if (browserName == "Chrome" || browserName == "Safari" || browserName == "Opera")
    {
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 116 || event.keyCode == 107 || event.keyCode == 109 || event.keyCode == 110 ||
                (event.keyCode == 190 && event.shiftKey == false) ||
                (event.keyCode == 61 && event.shiftKey == true) ||
                (event.keyCode == 173 && event.shiftKey == false) ||
                (event.keyCode == 189 && event.shiftKey == false) ||
                (event.keyCode == 187 && event.shiftKey == true) ||
                // Allow: Ctrl+A
                        (event.keyCode == 65 && event.ctrlKey === true) ||
                        // Allow: Ctrl+C
                                (event.keyCode == 67 && event.ctrlKey === true) ||
                                // Allow: Ctrl+C
                                        (event.keyCode == 88 && event.ctrlKey === true) ||
                                        // Allow: home, end, left, right
                                                (event.keyCode >= 35 && event.keyCode <= 39)) {
                                    // let it happen, don't do anything
                                    return;
                                } else {
                                    // Ensure that it is a number and stop the keypress
                                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                        event.preventDefault();
                                    }
                                }
                            }
                            else if (browserName == "Firefox")
                            {
                                if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 116 || event.keyCode == 107 || event.keyCode == 109 || event.keyCode == 110 || event.keyCode == 189 ||
                                        (event.keyCode == 190 && event.shiftKey == false) ||
                                        (event.keyCode == 61 && event.shiftKey == true) ||
                                        (event.keyCode == 173 && event.shiftKey == false) ||
                                        (event.keyCode == 187 && event.shiftKey == true) ||
                                        // Allow: Ctrl+A
                                                (event.keyCode == 65 && event.ctrlKey === true) ||
                                                // Allow: Ctrl+C
                                                        (event.keyCode == 67 && event.ctrlKey === true) ||
                                                        // Allow: Ctrl+C
                                                                (event.keyCode == 88 && event.ctrlKey === true) ||
                                                                // Allow: home, end, left, right
                                                                        (event.keyCode >= 35 && event.keyCode <= 39)) {
                                                            // let it happen, don't do anything
                                                            return;
                                                        } else {
                                                            // Ensure that it is a number and stop the keypress
                                                            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                                                event.preventDefault();
                                                            }
                                                        }
                                                    }
                                                    else if (browserName == "Microsoft Internet Explorer" || browserName == "Netscape")
                                                    {
                                                        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 116 || event.keyCode == 107 || event.keyCode == 109 || event.keyCode == 110 ||
                                                                (event.keyCode == 190 && event.shiftKey == false) ||
                                                                (event.keyCode == 61 && event.shiftKey == true) ||
                                                                (event.keyCode == 173 && event.shiftKey == false) ||
                                                                (event.keyCode == 187 && event.shiftKey == true) ||
                                                                (event.keyCode == 189 && event.shiftKey == false) ||
                                                                // Allow: Ctrl+A
                                                                        (event.keyCode == 65 && event.ctrlKey === true) ||
                                                                        // Allow: Ctrl+C
                                                                                (event.keyCode == 67 && event.ctrlKey === true) ||
                                                                                // Allow: Ctrl+C
                                                                                        (event.keyCode == 88 && event.ctrlKey === true) ||
                                                                                        // Allow: home, end, left, right
                                                                                                (event.keyCode >= 35 && event.keyCode <= 39)) {
                                                                                    // let it happen, don't do anything
                                                                                    return;
                                                                                } else {
                                                                                    // Ensure that it is a number and stop the keypress
                                                                                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                                                                        event.preventDefault ? event.preventDefault() : event.returnValue = false;
                                                                                        //event.preventDefault();
                                                                                    }
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                                if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 116 || event.keyCode == 107 || event.keyCode == 109 || event.keyCode == 110 || event.keyCode == 187 ||
                                                                                        (event.keyCode == 190 && event.shiftKey == false) ||
                                                                                        (event.keyCode == 61 && event.shiftKey == true) ||
                                                                                        (event.keyCode == 173 && event.shiftKey == false) ||
                                                                                        (event.keyCode == 189 && event.shiftKey == true) ||
                                                                                        // Allow: Ctrl+A
                                                                                                (event.keyCode == 65 && event.ctrlKey === true) ||
                                                                                                // Allow: Ctrl+C
                                                                                                        (event.keyCode == 67 && event.ctrlKey === true) ||
                                                                                                        // Allow: Ctrl+C
                                                                                                                (event.keyCode == 88 && event.ctrlKey === true) ||
                                                                                                                // Allow: home, end, left, right
                                                                                                                        (event.keyCode >= 35 && event.keyCode <= 39)) {
                                                                                                            // let it happen, don't do anything
                                                                                                            return;
                                                                                                        } else {
                                                                                                            // Ensure that it is a number and stop the keypress
                                                                                                            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                                                                                                event.preventDefault();
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }

                                                                                                jQuery(document).on('focusin', 'input', function (e) {
                                                                                                    if (jQuery(this).parent().hasClass('arf_prefix_suffix_wrapper')) {
                                                                                                        jQuery(this).parent().find('.arf_prefix').addClass('arf_prefix_focus');
                                                                                                        jQuery(this).parent().find('.arf_suffix').addClass('arf_suffix_focus');
                                                                                                    }
                                                                                                });

                                                                                                jQuery(document).on('focusout', 'input', function (e) {
                                                                                                    if (jQuery(this).parent().hasClass('arf_prefix_suffix_wrapper')) {
                                                                                                        jQuery(this).parent().find('.arf_prefix').removeClass('arf_prefix_focus');
                                                                                                        jQuery(this).parent().find('.arf_suffix').removeClass('arf_suffix_focus');
                                                                                                    }
                                                                                                });

                                                                                                function arfFocusInputField(key)
                                                                                                {
                                                                                                    if (key != "")
                                                                                                    {
                                                                                                        jQuery("#field_" + key).focus();
                                                                                                    }
                                                                                                }