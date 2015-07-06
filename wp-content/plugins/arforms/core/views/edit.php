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

global $aresponder, $responder_fname, $responder_lname, $responder_email, $mailchimpkey, $mailchimpid, $infusionsoftkey, $aweberkey, $aweberid, $getresponsekey, $getresponseid, $gvokey, $gvoid, $ebizackey, $ebizacid, $style_settings, $arfsettings, $arformhelper, $arrecordcontroller, $armainhelper, $arformcontroller, $arfieldhelper, $maincontroller;

$record = $arfform->getOne($id);

$responder_fname = $record->autoresponder_fname;
$responder_lname = $record->autoresponder_lname;
$responder_email = $record->autoresponder_email;

$display = apply_filters('arfdisplayfieldoptions', array('label_position' => true));
?>
<?php
//wp_print_scripts('sack');
wp_enqueue_script('sack');
$key = $values['form_key'];
$is_ref_form = isset($is_ref_form) ? $is_ref_form : '';
if ($is_ref_form == 1) {
    $form = $arfform->getAll(array('form_key' => $key), '', 1, 1);
    $pre_link = $arformhelper->get_direct_link($form->form_key);
    $pre_link .= "&is_ref_form=1";
} else {
    $form = $arfform->getAll(array('form_key' => $key), '', 1);
    $pre_link = $arformhelper->get_direct_link($form->form_key);
}
?>
<?php if (isset($_GET['arfaction']) and $_GET['arfaction'] == "new") { ?>
    <script type="text/javascript"  language="javascript">
        function getCookie(c_name)
        {
            var c_value = document.cookie;
            var c_start = c_value.indexOf(" " + c_name + "=");
            if (c_start == -1)
            {
                c_start = c_value.indexOf(c_name + "=");
            }
            if (c_start == -1)
            {
                c_value = null;
            }
            else
            {
                c_start = c_value.indexOf("=", c_start) + 1;
                var c_end = c_value.indexOf(";", c_start);
                if (c_end == -1)
                {
                    c_end = c_value.length;
                }
                c_value = unescape(c_value.substring(c_start, c_end));
            }
            return c_value;
        }


        function removeVariableFromURL(url_string, variable_name) {
            var URL = String(url_string);
            var regex = new RegExp("\\?" + variable_name + "=[^&]*&?", "gi");
            URL = URL.replace(regex, '?');
            regex = new RegExp("\\&" + variable_name + "=[^&]*&?", "gi");
            URL = URL.replace(regex, '&');
            URL = URL.replace(/(\?|&)$/, '');
            regex = null;
            return URL;
        }

        function processAjaxData(response, urlPath) {
            document.getElementById("content").innerHTML = response.html;
            document.title = response.pageTitle;
            if (window.history.pushState) {
                window.history.pushState({"html": response.html, "pageTitle": response.pageTitle}, "", urlPath);
            }
        }

        function popup_tb_show() {


            var $_GET = {};

            document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
                function decode(s) {
                    return decodeURIComponent(s.split("+").join(" "));
                }

                $_GET[decode(arguments[1])] = decode(arguments[2]);
            });

            var isp = $_GET["isp"];


            var pageurl = removeVariableFromURL(document.URL, 'isp');

            if (window.history.pushState) {
                window.history.pushState({path: pageurl}, '', pageurl);
            }



            if (getCookie('is_popup_show') == null || isp == '1') {
                document.cookie = "is_popup_show=1";
            }

            var get_form_cookie = getCookie('is_popup_show');

            if (get_form_cookie == 1) {

                jQuery(document).ready(function () {
                    var totalwidth = jQuery(window).width();
                    var left_width = Number(totalwidth) / 2;
                    left_width = left_width - 280;
                    var totalheight = jQuery(window).height();
                    var modal_height = jQuery("#new_form_div").outerHeight();
                    var top_height = Number(totalheight - modal_height) / 2;
                    top_height = top_height - 12;

                    //alert(top_height);		
                    jQuery("#new_form_div").css('left', left_width + 'px');
                    if (top_height > 20) {
                        jQuery("#new_form_div").css('top', top_height + 'px');
                    }
                    jQuery("#new_form_div").arfmodal();
                });

                document.cookie = "is_popup_show=0";
            }

        }

        jQuery(document).ready(function () {
            popup_tb_show();
        });

    <?php if (isset($template_id) and $template_id != '') { ?>
            jQuery(document).ready(function () {

                var pageurl = removeVariableFromURL(document.URL, 'arfaction');
                if (window.history.pushState) {
                    window.history.pushState({path: pageurl}, '', pageurl + '&id=<?php echo $template_id; ?>&arfaction=duplicate&form_name=<?php echo $values['name']; ?>&form_desc=<?php echo $values['description']; ?>');
                }

            });
    <?php } ?>
    </script>
<?php } ?>

<?php
global $wpdb;

if ($is_ref_form == 1) {
    $res = $wpdb->get_results($wpdb->prepare("SELECT status, form_id, form_key FROM " . $wpdb->prefix . "arf_ref_forms WHERE id = %d", $id), 'ARRAY_A');
} else {
    $res = $wpdb->get_results($wpdb->prepare("SELECT status, form_id, form_key FROM " . $wpdb->prefix . "arf_forms WHERE id = %d", $id), 'ARRAY_A');
}
$res = $res[0];
$ref_formkey = $res['form_id'];

if ($res['status'] != 'draft' && $is_ref_form != 1) {
    ?>

    <script type="text/javascript" language="javascript">
        function removeVariableFromURL(url_string, variable_name) {
            var URL = String(url_string);
            var regex = new RegExp("\\?" + variable_name + "=[^&]*&?", "gi");
            URL = URL.replace(regex, '?');
            regex = new RegExp("\\&" + variable_name + "=[^&]*&?", "gi");
            URL = URL.replace(regex, '&');
            URL = URL.replace(/(\?|&)$/, '');
            regex = null;
            return URL;
        }

        function processAjaxData(response, urlPath) {
            document.getElementById("content").innerHTML = response.html;
            document.title = response.pageTitle;
            if (window.history.pushState) {
                window.history.pushState({"html": response.html, "pageTitle": response.pageTitle}, "", urlPath);
            }
        }


        var pageurl = removeVariableFromURL(document.URL, 'arfaction');

        pageurl = removeVariableFromURL(pageurl, 'id');

        pageurl += '&arfaction=edit&id=<?php echo $id; ?>';

        if (window.history.pushState) {
            window.history.pushState({path: pageurl}, '', pageurl);
        }

    </script>
<?php } ?>

<script type="text/javascript" language="javascript">

    var height = jQuery(window).height();
    document.cookie = 'height=' + height;

    var width = jQuery(window).width();
    document.cookie = 'width=' + width;

</script>

<style>
    body{ overflow-x: hidden !important;}
    #doslide_show, #doslide_hide { }
    #doslide_hide {display:none;}
    .slider_maindiv { 
        min-height: 300px;
        position: fixed;
        width:1px;
        z-index: 100;
        border-radius:10px;
        height:1px;
        top:0;
        left:0;
    }
    #slidecontent {display:none; width:100%; height:100%; border-radius:10px; z-index:101;}
    .title_main_div { padding-top:10px; border-radius:0px 10px 0px 0px; padding-bottom:10px; }

</style>

<script type="text/javascript">


    function DoShow1(checkstatus)
    {
        var screenWidth = window.screen.width, screenHeight = window.screen.height;
        var newwidth = screenWidth - (385 + 157)<?php
if ($GLOBALS['wp_version'] < 3.1) {
    echo '-(14)';
}
?>;
                var newheight = screenHeight - 300;
        var iframeheight = screenHeight - 100 - 165 - 10;
        //var iframeheight = screenHeight - 345;
        var tabheight = screenHeight - 345;

        var currentwidgetid = jQuery(".current_widget").attr('id');

        document.getElementById("iframediv").style.display = 'none';
        var form = jQuery('form').serialize();
        //console.log( currentwidgetid );

        //var newformvalues = filterformdata(jQuery('form').serializeArray());

        jQuery('.iframediv_loader').show();
        //var nvals = jQuery('form').serializeObject();

        var fields = jQuery("#frm_main_form").FilterFormData();

        fields['form_id'] = <?php echo $id; ?>;
        fields['action'] = 'arfformsavealloptions';
        var jsondata = jQuery.toJSON(fields);

        var arfsack = new sack(ajaxurl);
        arfsack.execute = 0;
        arfsack.method = 'POST';
        arfsack.setVar("action", "arfformsavealloptions");
        arfsack.setVar("form_id", <?php echo $id; ?>);
        arfsack.setVar("filtered_form", jsondata);
        arfsack.onError = function () {
            alert('<?php echo esc_js(__("Ajax error while saving form", "ARForms")) ?>')
        };
        arfsack.onCompletion = loaded_ajax_DoShow1;
        arfsack.runAJAX();


        function loaded_ajax_DoShow1() {

            var msg = arfsack.response;

            var reponse = msg.split('^|^');
            var sucmessage = reponse[0];
            var new_html = reponse[1];

            if (sucmessage == 'deleted') {
                window.location = __ARFDELETEURL;
            }
            else {

                jQuery("#iframediv").html(' ').append('<iframe style="height:' + iframeheight + 'px; display:block; width:100%; margin-top:0px;" frameborder="0" name="test" id="testiframe" src="<?php echo $pre_link . "&title=true&description=true&is_editorform=1"; ?>" hspace="0"></iframe>');

                jQuery('#testiframe').load(function () {
                    jQuery('.iframediv_loader').hide();
                    document.getElementById("iframediv").style.display = 'block';
                    change_form_title();

                    var checkbox_class = '';
                    var chk_style = jQuery('#frm_check_radio_style').val();
                    var chk_color = jQuery('#frm_check_radio_style_color').val();

                    if (chk_style != 'none') {
                        checkbox_class = chk_style;

                        if (chk_style != 'futurico' && chk_style != 'polaris' && chk_color != 'default') {
                            checkbox_class = checkbox_class + '-' + chk_color;
                        }
                        jQuery('#testiframe').contents().find('.arf_form input[type="checkbox"]').on('ifChanged', function (event) {
                            jQuery(this).trigger('change');
                        });

                        jQuery('#testiframe').contents().find('.arf_form input[type="radio"]').on('ifChecked', function (event) {
                            jQuery(this).trigger('click');
                        });

                        jQuery('#testiframe').contents().find('.arf_form input[type="checkbox"]').on('ifClicked', function (event) {
                            jQuery(this).trigger('focus');
                        });

                        jQuery('#testiframe').contents().find('.arf_form input[type="radio"]').on('ifClicked', function (event) {
                            jQuery(this).trigger('focus');
                        });

                        jQuery('#testiframe').contents().find('#arffrm_<?php echo $id; ?>_container input').not('.arf_hide_opacity').iCheck({
                            checkboxClass: 'icheckbox_' + checkbox_class,
                            radioClass: 'iradio_' + checkbox_class
                        });

                    }

                    var theme_css = jQuery("input[name='arffths']").val();
                    var calender_url = jQuery('#calender_url').val();
                    var css = calender_url + theme_css + '_jquery-ui.css';
                    arfupdateformpreviewcss(css);

                    if (checkstatus == 1)
                    {
                        var locStr1 = window.location.hash;

<?php
$browser_info = $arrecordcontroller->getBrowser($_SERVER['HTTP_USER_AGENT']);
if ($browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9') {
    ?>
                            var locStr2 = locStr1.replace(/%23/g, "");
<?php } else { ?>
                            var locStr2 = locStr1.replace(/%23/g, "").replace(/[\?#]+/g, "");
<?php } ?>
                        updateCSS(locStr2);
                    }
                });

            }


        }

    }

    function DoShow3(checkstatus, checkradiostyle, checkradiocolor)
    {
        var screenWidth = window.screen.width, screenHeight = window.screen.height;
        var newwidth = screenWidth - (385 + 157);
        var newheight = screenHeight - 300;
        var iframeheight = screenHeight - 345;
        var tabheight = screenHeight - 345;

        var currentwidgetid = jQuery(".current_widget").attr('id');

        var checkradio_style = document.getElementById(checkradiostyle).value;
        var checkradio_color = document.getElementById(checkradiocolor).value;
        var checkradio_property = "";

        var form = jQuery('form').serialize();

        var newformvalues = filterformdata(jQuery('form').serializeArray());

        var nvals = jQuery('form').serializeObject();

        //var fields = {};
        var fields = jQuery("#frm_main_form").FilterFormData();

        fields['form_id'] = <?php echo $id; ?>;
        //fields['form_preview'] = form_preview;
        fields['action'] = 'arfformsavealloptions';
        var jsondata = jQuery.toJSON(fields);
        var arfsack = new sack(ajaxurl);
        arfsack.execute = 0;
        arfsack.method = 'POST';
        arfsack.setVar("action", "arfformsavealloptions");
        arfsack.setVar("form_id", <?php echo $id; ?>);
        //arfsack.setVar( "form_preview",form_preview );
        arfsack.setVar("filtered_form", jsondata);
        arfsack.onError = function () {
            alert('<?php echo esc_js(__("Ajax error while saving form", "ARForms")) ?>')
        };
        arfsack.onCompletion = loaded_ajax_DoShow3;
        arfsack.runAJAX();

        function loaded_ajax_DoShow3() {

            var msg = arfsack.response;

            var reponse = msg.split('^|^');
            var sucmessage = reponse[0];
            var new_html = reponse[1];

            if (sucmessage == 'deleted') {
                window.location = __ARFDELETEURL;
            }
            else {

                jQuery("#iframediv").html(' ').append('<iframe style="height:' + iframeheight + 'px; display:block; width:100%; margin-top:0px;" frameborder="0" name="test" id="testiframe" src="<?php echo $pre_link . "&title=true&description=true&is_editorform=1&checkradiostyle="; ?>' + checkradio_style + '<?php echo "&checkradiocolor=" ?>' + checkradio_color + '" hspace="0"></iframe>');
                jQuery('#testiframe').load(function () {
                    jQuery('.iframediv_loader').hide();
                    document.getElementById("iframediv").style.display = 'block';
                    change_form_title();

                    var theme_css = jQuery("input[name='arffths']").val();
                    var calender_url = jQuery('#calender_url').val();
                    var css = calender_url + theme_css + '_jquery-ui.css';
                    arfupdateformpreviewcss(css);

                    var checkbox_class = '';
                    var chk_style = jQuery('#frm_check_radio_style').val();
                    var chk_color = jQuery('#frm_check_radio_style_color').val();

                    if (chk_style != 'none') {
                        checkbox_class = chk_style;

                        if (chk_style != 'futurico' && chk_style != 'polaris' && chk_color != 'default') {
                            checkbox_class = checkbox_class + '-' + chk_color;
                        }

                        jQuery('#testiframe').contents().find('#arffrm_<?php echo $id; ?>_container input').not('.arf_hide_opacity').iCheck({
                            checkboxClass: 'icheckbox_' + checkbox_class,
                            radioClass: 'iradio_' + checkbox_class
                        });

                        jQuery('#testiframe').contents().find('.arf_form input[type="checkbox"]').on('ifChanged', function (event) {
                            jQuery(this).trigger('change');
                        });

                        jQuery('#testiframe').contents().find('.arf_form input[type="radio"]').on('ifChecked', function (event) {
                            jQuery(this).trigger('click');
                        });

                        jQuery('#testiframe').contents().find('.arf_form input[type="checkbox"]').on('ifClicked', function (event) {
                            jQuery(this).trigger('focus');
                        });

                        jQuery('#testiframe').contents().find('.arf_form input[type="radio"]').on('ifClicked', function (event) {
                            jQuery(this).trigger('focus');
                        });

                    }

                    if (checkstatus == 1)
                    {
                        var locStr1 = window.location.hash;
                        var locStr = locStr1.replace(/%23/g, "").replace(/[\?#]+/g, "");
                        updateCSS(locStr);
                    }
                });

                jQuery('.arfshowmainform:not(.arfpreivewform)').each(function () {
                    var width = jQuery(this).find('.arf_fieldset').width();
                    jQuery(this).find('.arf_prefix_suffix_wrapper').css('max-width', width + 'px');
                });
            }


        }
    }


    function DoHide()
    {
        jQuery("#iframediv").html(' ');
    }

    function change_date_format() {

        DoShow1('1');
    }

    function arfshowloginimg2()
    {
<?php if (is_rtl()) { ?>
            jQuery("#lastimageli").css('background-image', 'url(<?php echo ARFIMAGESURL; ?>/navigation_menu/last-hover_rtl.png)');
<?php } else { ?>
            jQuery("#lastimageli").css('background-image', 'url(<?php echo ARFIMAGESURL; ?>/navigation_menu/last-hover.png)');
<?php } ?>
    }

    function arfhideloginimg2()
    {
<?php if (is_rtl()) { ?>
            jQuery("#lastimageli").css('background-image', 'url(<?php echo ARFIMAGESURL; ?>/navigation_menu/last-simple_rtl.png)');

            if (jQuery('#tab_addtosite').hasClass('current'))
                jQuery("#lastimageli").css('background-image', 'url(<?php echo ARFIMAGESURL; ?>/navigation_menu/last-hover_rtl.png)');
<?php } else { ?>
            jQuery("#lastimageli").css('background-image', 'url(<?php echo ARFIMAGESURL; ?>/navigation_menu/last-simple.png)');

            if (jQuery('#tab_addtosite').hasClass('current'))
                jQuery("#lastimageli").css('background-image', 'url(<?php echo ARFIMAGESURL; ?>/navigation_menu/last-hover.png)');
<?php } ?>
    }

    function arfshowloginimg1()
    {
        if (jQuery('#tab_styling').hasClass('current')) {
            jQuery('#tab_styling_img').show();
        } else {
            jQuery('#tab_styling_img').hide();
        }
    }
    function arfhideloginimg1()
    {
        jQuery('#tab_styling_img').hide();
    }

    function arf_change_form_tab(id)
    {
        jQuery('.arf_current_tab').val(id);
        jQuery('.arf_tab_content').hide();
        jQuery('#arf_' + id + '_conent').show();
        jQuery('.arfformtab').removeClass('current lastDone nextdone');
        jQuery('#tab_' + id).addClass('current');
        jQuery('.arfformtab').addClass('done');
        jQuery('#maincontainerdiv').show();
        jQuery('#arfformsettingpage').hide();
        jQuery('#arfaddtosite').hide();
        if (id == 'addtosite')
        {
            jQuery('#tab_formsetting').removeClass('done').addClass('lastDone');
            jQuery('#tab_addtosite').removeClass('done lastDone').addClass('current');

            jQuery('#maincontainerdiv').hide();
            jQuery('#arfaddtosite').show();

        } else if (id == 'formsetting') {
            jQuery('#tab_styling').removeClass('current').addClass('lastDone');
            jQuery('#tab_formsetting').removeClass('lastDone done').addClass('current');
            jQuery('#tab_addtosite').addClass('nextdone');

            jQuery('#maincontainerdiv').hide();
            jQuery('#arfformsettingpage').show();
            e = window.event;
            onScroll(e);

        } else if (id == 'editor') {
            jQuery('#tab_editor').removeClass('lastDone done').addClass('current');
            jQuery('#tab_styling').addClass('nextdone');
            CheckFieldPos_height();
            jQuery('.arffontstylesettingmainpopupbox').css('display', 'none');
        } else if (id == 'styling') {
            jQuery('#tab_editor').removeClass('done').addClass('lastDone');
            jQuery('#tab_styling').removeClass('done').addClass('current');
            jQuery('#tab_formsetting').addClass('nextdone')
            jQuery(window.opera ? 'html' : 'html, body').animate({scrollTop: 0}, 'fast');
            arf_form_preview_load('style');
            arfresetslider();
        }

        if (!jQuery('#tab_styling').hasClass('current')) {
            jQuery('#tab_styling_img').hide();
        }

<?php if (is_rtl()) { ?>
            if (!jQuery('#tab_addtosite').hasClass('current')) {
                jQuery("#lastimageli").css('background-image', 'url(<?php echo ARFIMAGESURL; ?>/navigation_menu/last-simple_rtl.png)');
            }
<?php } else { ?>
            if (!jQuery('#tab_addtosite').hasClass('current')) {
                jQuery("#lastimageli").css('background-image', 'url(<?php echo ARFIMAGESURL; ?>/navigation_menu/last-simple.png)');
            }
<?php } ?>
    }

    function ChangeIcon(thid, imgname)
    {
        document.getElementById(thid).src = '<?php echo ARFIMAGESURL ?>/editor_icons/' + imgname;
    }

// form submit validation
    function arfmainformedit(is_addtosite_page) {
        var def_title = '(Click here to add text)';
        if (typeof (__ARFDEFAULTTITLE) != 'undefined') {
            var def_title = __ARFDEFAULTTITLE;
        }

        if (jQuery('.arfeditorformname').text() == def_title || jQuery('.arfeditorformname').text() == '') {
            jQuery('#form_name_message').delay(0).fadeIn('slow');
            arf_form_preview_load('form');
            return false;
        }

        if (jQuery('#success_action_message').is(':checked') && jQuery('#success_msg').val() == '')
        {
            jQuery('#success_msg').css('border-color', '#ff0000');
            jQuery('#success_msg_error').css('display', 'block');
            jQuery('#general-settings').hide();
            arf_change_form_tab('formsetting');
            jQuery('#success_msg').focus();
            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#success_msg').offset().top - 250}, 'slow');
            return false;
        }
        else
        {
            jQuery('#success_msg').css('border-color', '');
            jQuery('#success_msg_error').css('display', 'none');
        }

        if (jQuery('#success_action_redirect').is(':checked') && jQuery('#success_url').val() == '')
        {
            jQuery('#success_url').css('border-color', '#ff0000');
            jQuery('#success_url_error').css('display', 'block');
            jQuery('#general-settings').hide();
            arf_change_form_tab('formsetting');
            jQuery('#success_url').focus();
            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#success_url').offset().top - 250}, 'slow');
            return false;
        }
        else
        {
            jQuery('#success_url').css('border-color', '');
            jQuery('#success_url_error').css('display', 'none');
        }

        if (jQuery('#success_action_page').is(':checked') && jQuery('#option_success_page_id').val() == '')
        {
            jQuery('.frm-pages-dropdown .arfbtn.dropdown-toggle').css('border-color', '#ff0000');
            jQuery('.frm-pages-dropdown .arfdropdown-menu.open').css('border-color', '#ff0000');
            jQuery('#option_success_page_id_error').css('display', 'block');
            arf_change_form_tab('formsetting');
            jQuery('#option_success_page_id').focus();
            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#option_success_page_id').offset().top - 250}, 'slow');
            return false;
        }
        else
        {
            jQuery('.frm-pages-dropdown .arfbtn.dropdown-toggle').css('border-color', '');
            jQuery('.frm-pages-dropdown .arfdropdown-menu.open').css('border-color', '');
            jQuery('#option_success_page_id_error').css('display', 'none');
        }

        if (jQuery('#auto_responder').is(':checked') && jQuery('#ar_email_message').val() == '')
        {
            jQuery('#ar_email_message').css('border-color', '#ff0000');
            jQuery('#ar_email_message_error').css('display', 'block');
            jQuery('#general-settings').hide();
            arf_change_form_tab('formsetting');
            jQuery('#ar_email_message').focus();
            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#ar_email_message').offset().top - 250}, 'slow');
            return false;
        }
        else
        {
            jQuery('#ar_email_message').css('border-color', '');
            jQuery('#ar_email_message_error').css('display', 'none');
        }

        if (jQuery('#chk_admin_notification').is(':checked') && jQuery('#ar_admin_email_message').val() == '')
        {
            jQuery('#ar_admin_email_message').css('border-color', '#ff0000');
            jQuery('#ar_admin_email_message_error').css('display', 'block');
            jQuery('#general-settings').hide();
            arf_change_form_tab('formsetting');
            jQuery('#ar_admin_email_message').focus();
            jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#ar_admin_email_message').offset().top - 250}, 'slow');
            return false;
        }
        else
        {
            jQuery('#ar_admin_email_message').css('border-color', '');
            jQuery('#ar_admin_email_message_error').css('display', 'none');
        }

<?php
// validate form outside
do_action('arf_render_admin_form_validation', $id, $is_ref_form, $values);
?>

        var form = jQuery("#frm_main_form").serialize();
        var form_id = jQuery('#frm_main_form').find('#id').val();
        var form_preview = "none";

        //var newformvalues = filterformdata(jQuery("#frm_main_form").serializeArray());
        //var nvals = jQuery("#frm_main_form").serializeObject();

        var fields = jQuery("#frm_main_form").FilterFormData();

        jQuery('#arfsubmitall').attr('disabled', true);
        jQuery('#arfaddtosubmit').attr('disabled', true);

        jQuery('.arfmaincontainerfade').addClass('arffadeactive');
        jQuery('#arfsaveformloader').show();

        var current_tab = jQuery('.arfformtab.current').attr('id');

        fields['form_id'] = form_id;
        fields['form_preview'] = form_preview;
        fields['action'] = 'arfformsavealloptions';
        var jsondata = jQuery.toJSON(fields);
        var arfsack = new sack(ajaxurl);
        arfsack.execute = 0;
        arfsack.method = 'POST';
        arfsack.setVar("action", "arfformsavealloptions");
        arfsack.setVar("form_id", form_id);
        arfsack.setVar("form_preview", form_preview);
        arfsack.setVar("filtered_form", jsondata);
        arfsack.onError = function () {
            alert('<?php echo esc_js(__("Ajax error while saving form", "ARForms")) ?>')
        };
        arfsack.onCompletion = loaded_ajax;
        arfsack.runAJAX();


        function loaded_ajax() {

            var msg = arfsack.response;

            var reponse = msg.split('^|^');

            var sucmessage = reponse[0];

            var new_html = reponse[1];


            jQuery('#new_fields').html('');
            jQuery('#new_fields').html(new_html);
            CheckFieldPos('0', '0');
            CheckFieldPos_height();
            checkpage_breakpos();
            jQuery(".sltstandard select").selectpicker();

            jQuery('.arfmaincontainerfade').removeClass('arffadeactive');
            jQuery('#arfsaveformloader').hide();

            if (sucmessage == 'deleted')
            {
                window.location = __ARFDELETEURL;
            }
            else
            {
                if (sucmessage != "")
                {
                    if (is_addtosite_page == 1) {
                        jQuery('#form_name_message').css("display", "none");
                        jQuery('#arf_addtosite_message').before('<div id="success_message" style="margin:0 5px 5px 0px; width:auto; text-align:left;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message">' + sucmessage + '</div></div>');
                        jQuery('#success_message').delay(3000).fadeOut('slow');
                        jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#success_message').offset().top - 250}, 'slow');
                        setTimeout(function () {
                            jQuery('#success_message').remove();
                            jQuery('#arfsubmitall').attr('disabled', false);
                            jQuery('#arfaddtosubmit').attr('disabled', false);
                        }, 4000);
                    } else {
                        jQuery('#form_name_message').css("display", "none");

                        if (current_tab == 'tab_addtosite') {
                            jQuery('#arf_addtosite_message').before('<div id="success_message" style="margin:0 5px 5px 0px; width:auto; text-align:left;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message">' + sucmessage + '</div></div>');
                        }
                        else if (current_tab == 'tab_styling') {
                            jQuery('#arf_main_style_tab_message').html('<div id="success_message" style="margin:0 5px 5px 20px; width:auto;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message">' + sucmessage + '</div></div>');
                        }
                        else if (current_tab == 'tab_formsetting') {
                            jQuery('#arfformsettingpage_message').before('<div id="success_message" style="margin:0 15px 5px 0px; width:auto;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message">' + sucmessage + '</div></div>');
                        }
                        else {
                            jQuery('#form_name_message').before('<div id="success_message" style="margin:0 5px 5px 20px; width:auto;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message">' + sucmessage + '</div></div>');
                        }
                        jQuery('#success_message').delay(3000).fadeOut('slow');
                        jQuery(window.opera ? 'html, .arfmodal-body' : 'html, body, .arfmodal-body').animate({scrollTop: jQuery('#success_message').offset().top - 250}, 'slow', function () {
                            if (current_tab == 'tab_formsetting') {
                                jQuery('#arfsetting_onsubmit').addClass('arfactive');
                            }
                        });

                        setTimeout(function () {
                            jQuery('#success_message').remove();
                            jQuery('#arfsubmitall').attr('disabled', false);
                            jQuery('#arfaddtosubmit').attr('disabled', false);
                        }, 4000);
                    }

                    if (window.history.pushState && form_id < 10000) {
                        var pageurl = arf_removeVariableFromURL(document.URL, 'arfaction');
                        pageurl = arf_removeVariableFromURL(pageurl, 'id');
                        pageurl += '&arfaction=edit&id=' + form_id;
                        window.history.pushState({path: pageurl}, '', pageurl);
                    }


                }
            }
        }

        return false;

    }
</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        var images = '';
        var images2 = '';
<?php
$attachments = array('field-editor-icon.png', 'field-editor-icon_hover.png', 'publish-icon.png', 'publish-icon_hover.png', 'setting-header-icon.png', 'setting-header-icon_hover.png', 'styling-icon.png', 'styling-icon_hover.png', 'last-hover.png', 'last-simple.png', 'navCurrentBtn.png', 'navCurrentBtn2.png', 'navDoneBtn.png', 'navDoneBtn-white.png', 'navLastDoneBtn.png', 'navLastDoneBtn2.png');
foreach ($attachments as $attachment) {
    ?>
            images += '<img src="<?php echo ARFIMAGESURL . '/navigation_menu/' . $attachment; ?>" style="display:none" />';
    <?php
}
$attachments = array('1column.png', '2column.png', '3column.png', 'blanform-icon.png', 'blanform-icon_hover.png', 'contact-form-icon.png', 'contact-form-icon_hover.png', 'feedback-form-icon.png', 'feedback-form-icon_hover.png', 'registration-form-icon.png', 'registration-form-icon_hover.png', 'rsvp-form-icon.png', 'rsvp-form-icon_hover.png', 'subscription-form-icon.png', 'subscription-form-icon_hover.png', 'survay-form-icon.png', 'survay-form-icon_hover.png', 'job-apps-icon.png', 'job-apps-icon_hover.png', 'ajax_loader_gray_64.gif', 'mailchimp_small1.png', 'mailchimp_small1_hover.png', 'aweber_small1.png', 'aweber_small1_hover.png', 'icontact1.png', 'icontact1_hover.png', 'constant-contact1.png', 'constant-contact1_hover.png', 'getresponse1.png', 'getresponse1_hover.png', 'ebizac1.png', 'ebizac1_hover.png', 'gvo1.png', 'gvo1_hover.png', 'dark-radio-green.png');
foreach ($attachments as $attachment) {
    ?>
            images2 += '<img src="<?php echo ARFIMAGESURL . '/' . $attachment; ?>" style="display:none" />';
<?php } ?>
        if (images != '') {
            jQuery('body').append(images);
            jQuery('body').append(images2);
        }
    });
</script>


<div class="wrap">

    <div id="new_form_div" style="display:none; left:30%;" class="arfmodal arfhide arffade">
        <?php require(VIEWS_PATH . '/new-selection-modal.php'); ?>
        <!--<div style="clear:both">&nbsp;</div>-->
    </div>
    <form method="post" id="frm_main_form" onsubmit="return arfmainformedit(0);">  
        <?php
        $show_preview = true;


        if (version_compare($GLOBALS['wp_version'], '3.3.3', '<')) {
            ?>

            <div id="poststuff">


                <?php
                $widthmaincontent = @$_COOKIE['width'] - 397;
                $heightmaincontent = @$_COOKIE['height'] * 0.80;
                $paddingheight = (@$_COOKIE['height'] * 0.20) / 2;
                ?>

                <style type="text/css">
                    .iframe_loader {
                        vertical-align:middle;
                        position:absolute;
                        top:<?php echo (($heightmaincontent - 100) / 2) . 'px'; ?>;
                        left:<?php echo (($widthmaincontent - 140) / 2) . 'px'; ?>;
                        display:none;
                    }
                </style>
            <?php } else {
                ?>


                <div id="poststuff">


                <?php } ?>





                <div id="post-body" class="">

                    <div style="width:100%;">
                        <div id="post-body-content">

                            <div class="arfmainformbuilder<?php echo ($values['custom_style']) ? ' ar_main_div' : ''; ?>">


                                <input type="hidden" name="arfmainformurl" id="arfmainformurl" value="<?php echo ARFURL; ?>" />   

                                <input type="hidden" name="prev_arfaction" value="<?php $_GET["arfaction"]; ?>" />

                                <input type="hidden" name="arfaction" value="update" />

                                <input type="hidden" name="ref_formid" value="<?php echo $ref_formkey; ?>" />

                                <input type="hidden" name="frm_autoresponder_no" id="frm_autoresponder_no" value="" />


                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />

                                <?php
                                if (isset($values['fields'])) {
                                    foreach ($values['fields'] as $field) {

                                        if ($field["type"] == "break") {
                                            $prepg_temp = $field["pre_page_title"];
                                            $next_temp = $field["next_page_title"];
                                            $default_selected_tmp = $field['page_break_type'];
                                            break;
                                        }
                                    }
                                }
                                ?>

                                <input type="hidden" id="arf_isformchange" name="arf_isformchange" data-value="1" value="1" />

                                <input type="hidden" id="page_break_first_pre_btn_txt" value="<?php echo isset($prepg_temp) ? $prepg_temp : "Previous"; ?>" />

                                <input type="hidden" id="page_break_first_next_btn_txt" value="<?php echo isset($next_temp) ? $next_temp : "Next"; ?>" />

                                <input type="hidden" id="page_break_first_select" value="<?php echo isset($default_selected_tmp) ? $default_selected_tmp : "wizard"; ?>" />
                                <?php $browser_info = $arrecordcontroller->getBrowser($_SERVER['HTTP_USER_AGENT']); ?>
                                <input type="hidden" id="arf_browser_name" value="<?php echo $browser_info['name']; ?>" />

                                <?php wp_nonce_field('update-options'); ?>

                                <div class="formsettings" style="height:90px; background-color:#eff0f5; border-bottom:3px solid #edd052;">
                                    <?php
                                    if (is_rtl()) {
                                        $stl = 'margin-top: 25px; width: 90%; float: right;';
                                    } else {
                                        $stl = 'margin-top: 25px; width: 90%; float: left;';
                                    }
                                    ?>
                                    <div style=" <?php echo $stl; ?>">
                                        <!-- start of setting_tabrow -->
                                        <?php
                                        if (is_rtl()) {
                                            ?>
                                            <div id="arf_tabmain">
                                                <ul class="fiveStep" id="arfmainNav">

                                                    <div class="lastimageli" id="lastimageli"></div>

                                                    <li id="tab_addtosite" class="arfformtab mainNavNoBg done" onmouseover="arfshowloginimg2()" onmouseout="arfhideloginimg2();"><div class="arf_fixer_img"></div><a href="javascript:arf_change_form_tab('addtosite');"><div class="arf_addtosite_icon"></div>&nbsp;<?php _e('Add To Site', 'ARForms'); ?></a></li>

                                                    <li id="tab_formsetting" class="arfformtab thriddmenu done"><div class="arf_fixer_img"></div><a href="javascript:arf_change_form_tab('formsetting');"><div class="arf_changepass_icon"></div>&nbsp;<?php _e('Settings', 'ARForms'); ?></a></li>

                                                    <li id="tab_styling" class="arfformtab secondmenu done nextdone"><div id="tab_styling_img" class="arf_fixer_img2"></div><div class="arf_fixer_img"></div><a href="javascript:arf_change_form_tab('styling');"><div class="arf_login_icon"></div>&nbsp;<?php _e('Styling', 'ARForms'); ?></a></li>

                                                    <li id="tab_editor" onmouseover="arfshowloginimg1()" onmouseout="arfhideloginimg1();" class="arfformtab firstmenu current"><a href="javascript:arf_change_form_tab('editor'); arf_form_preview_load('form');"><div class="arf_userreg_icon"></div>&nbsp;<?php _e('Field Editor', 'ARForms'); ?></a><div class="arf_fixer_img"></div></li>

                                                </ul>

                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div id="arf_tabmain">
                                                <ul class="fiveStep" id="arfmainNav">

                                                    <li id="tab_editor" onmouseover="arfshowloginimg1()" onmouseout="arfhideloginimg1();" class="arfformtab firstmenu current"><a href="javascript:arf_change_form_tab('editor'); arf_form_preview_load('form');"><div class="arf_userreg_icon"></div>&nbsp;<?php _e('Field Editor', 'ARForms'); ?></a><div class="arf_fixer_img"></div></li>

                                                    <li id="tab_styling" class="arfformtab secondmenu done nextdone"><div id="tab_styling_img" class="arf_fixer_img2"></div><div class="arf_fixer_img"></div><a href="javascript:arf_change_form_tab('styling');"><div class="arf_login_icon"></div>&nbsp;<?php _e('Styling', 'ARForms'); ?></a></li>

                                                    <li id="tab_formsetting" class="arfformtab thriddmenu done"><div class="arf_fixer_img"></div><a href="javascript:arf_change_form_tab('formsetting');"><div class="arf_changepass_icon"></div>&nbsp;<?php _e('Settings', 'ARForms'); ?></a></li>

                                                    <li id="tab_addtosite" class="arfformtab mainNavNoBg done" onmouseover="arfshowloginimg2()" onmouseout="arfhideloginimg2();"><div class="arf_fixer_img"></div><a href="javascript:arf_change_form_tab('addtosite');"><div class="arf_addtosite_icon"></div>&nbsp;<?php _e('Add To Site', 'ARForms'); ?></a></li>

                                                    <div class="lastimageli" id="lastimageli"></div>

                                                </ul>

                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <!-- end of setting_tabrow -->

                                        <?php
                                        if (is_rtl()) {
                                            $style = 'float:left;width:365px;padding-right:10px;text-align:right;';
                                        } else {
                                            $style = 'float:right; width:342px; padding-right: 15px; text-align:right;';
                                        }
                                        ?>
                                        <div id="formmainoptionbuttons" style=" <?php echo $style; ?>">
                                            <?php
                                            if (is_rtl()) {
                                                $btn_style = 'float:right;width:auto;';
                                            } else {
                                                $btn_style = 'float:left;width:auto;';
                                            }
                                            ?>  
                                            <div style=" <?php echo $btn_style; ?>"><button type="submit" id="arfsubmitall" class="greensavebtn" style="width:103px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/save-icon.png">&nbsp;&nbsp;<?php _e('Save', 'ARForms') ?></button>&nbsp;&nbsp;</div>

                                            <div style=" <?php echo $btn_style; ?>"><button type="button" onclick="location.href = '?page=ARForms'" class="whitecancelbtn" style="width:101px; height:41px; border-radius:3px; background-color:#ffffff;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/cancel-icon.png">&nbsp;&nbsp;<?php _e('Cancel', 'ARForms') ?></button>&nbsp;&nbsp;</div>

                                            <div style=" <?php echo $btn_style; ?>"><button id="arfpreviewbtn" type="button" data-url="<?php echo $pre_link . "&title=false&description=false"; ?>" onclick="arfgetformpreview();" class="bluepreviewbtn" style="width:107px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/previewbtn-icon.png">&nbsp;&nbsp;<?php _e('Preview', 'ARForms') ?></button>&nbsp;&nbsp;</div>

                                        </div>
                                    </div>
                                </div>	
                                <div class="clear"></div>
                                <div class="arfmaincontainerfade"></div>
                                <div class="arfmaincontainer">
                                    <div id="maincontainerdiv" style="width:100%">
                                        <?php
                                        if (is_rtl()) {
                                            $form_editor_style = 'float:right;margin-right:-20px;';
                                            $form_editor_class = 'formeditorpart_rtl';
                                        } else {
                                            $form_editor_style = 'float:left;';
                                            $form_editor_class = '';
                                        }
                                        ?>
                                        <div id="formeditorpart" class="<?php echo $form_editor_class; ?>" style=" <?php echo $form_editor_style; ?>">
                                            <div id="maineditcontentview" style="min-height:300px; width:100%; float:left; margin-top:90px;">
                                                <div style="clear:both;"></div>

                                                <?php require(VIEWS_PATH . '/edit_form.php'); ?>
                                            </div>
                                        </div>
                                        <?php
                                        if (is_rtl()) {
                                            $field_editor_controller_style = 'field_controller';
                                            $controller_style = '';
                                        } else {
                                            $controller_style = 'float:right;width:350px;';
                                            $field_editor_controller_style = '';
                                        }
                                        ?>
                                        <div style=" <?php echo $controller_style; ?>" class=" <?php echo $field_editor_controller_style; ?>">
                                            <?php require(VIEWS_PATH . '/addcontrols.php'); ?>


                                            <div id="arfformsettingpage" style="display:none;">
                                                <div class="arfsettingleftmenu_tab">
                                                    <div class="arfsettingleftmenu">
                                                        <div id="arfsetting_onsubmit" onclick="arfselectsetting('onsubmit');" class="arfsettingli arfactive"><?php _e('On Submit', 'ARForms'); ?></div>
                                                        <?php do_action('arf_after_onsubmit_setting_menu', $id, $is_ref_form, $values); ?>

                                                        <div id="arfsetting_autoresponse" onclick="arfselectsetting('autoresponse');" class="arfsettingli"><?php _e('Auto response email', 'ARForms'); ?></div>
                                                        <?php do_action('arf_after_autoresponse_setting_menu', $id, $is_ref_form, $values); ?>

                                                        <div id="arfsetting_emailmarketer" onclick="arfselectsetting('emailmarketer');" class="arfsettingli"><?php _e('Email marketers', 'ARForms'); ?></div>
                                                        <?php do_action('arf_after_emailmarketer_setting_menu', $id, $is_ref_form, $values); ?>

                                                        <div id="arfsetting_customcss" onclick="arfselectsetting('customcss');" class="arfsettingli"><?php _e('Custom CSS', 'ARForms'); ?></div>
                                                        <?php do_action('arf_after_allsetting_menu', $id, $is_ref_form, $values); ?>

                                                    </div>                    
                                                </div>

                                                <div class="arfsettingcontainer">

                                                    <div id="arfformsettingpage_message">            	                            
                                                    </div>

                                                    <div id="arf_onsubmit" class="arfsettingsubcontainer">

                                                        <div class="arfformtable">

                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft arfsettingtitle"><?php _e('On Submit', 'ARForms'); ?></div>
                                                                <div class="arfcolumnright"></div>
                                                            </div>

                                                            <div class="arfsettingspacer"></div>

                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft">

                                                                    <input type="radio" class="rdostandard" name="options[success_action]" id="success_action_message" value="message" <?php checked(@$values['success_action'], 'message'); ?> /> &nbsp;<label for="success_action_message"><span></span><?php _e('Display a Message', 'ARForms') ?></label>&nbsp;&nbsp;

                                                                    <input type="radio" class="rdostandard" name="options[success_action]" id="success_action_redirect" value="redirect" <?php checked(@$values['success_action'], 'redirect'); ?> /> &nbsp;<label for="success_action_redirect" <?php echo (isset($pro_feature)) ? $pro_feature : ''; ?>><span></span><?php _e('Redirect to URL', 'ARForms') ?></label>&nbsp;&nbsp;

                                                                    <input type="radio" class="rdostandard" name="options[success_action]" id="success_action_page" value="page" <?php checked(@$values['success_action'], 'page') ?> /> &nbsp;<label for="success_action_page" <?php echo (isset($pro_feature)) ? $pro_feature : ''; ?>><span></span><?php _e('Display content from another page', 'ARForms') ?></label>

                                                                </div>
                                                                <div class="arfcolumnright"></div>
                                                            </div>

                                                            <div class="arfsettingspacer"></div>

                                                            <div class="arftablerow success_action_message_box success_action_box" <?php echo ($values['success_action'] == 'message') ? '' : 'style="display:none;"'; ?>>
                                                                <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Confirmation Message', 'ARForms') ?></div>
                                                                <div class="arfcolumnright">

                                                                    <textarea id="success_msg" name="options[success_msg]" style="width:430px !important;" cols="50" rows="4" class="arfplacelonginput txtmultinew"><?php echo $armainhelper->esc_textarea($arformcontroller->br2nl($values['success_msg'])); ?></textarea><br />
                                                                    <div class="arferrmessage" id="success_msg_error" style="display:none;"><?php _e('This field cannot be blank.', 'ARForms'); ?></div>

                                                                </div>
                                                            </div>

                                                            <div class="arftablerow success_action_redirect_box success_action_box" <?php echo ($values['success_action'] == 'redirect') ? '' : 'style="display:none;"'; ?>>
                                                                <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Redirect to URL', 'ARForms') ?></div>
                                                                <div class="arfcolumnright">

                                                                    <input type="text" name="options[success_url]" id="success_url" value="<?php if (isset($values['success_url'])) echo esc_attr($values['success_url']); ?>" class="txtstandardnew" size="55"  style="width:430px !important;">
                                                                    <div class="arferrmessage" id="success_url_error" style="display:none;"><?php _e('This field cannot be blank.', 'ARForms'); ?></div>

                                                                </div>
                                                            </div>

                                                            <div class="arftablerow success_action_page_box success_action_box" <?php echo ($values['success_action'] == 'page') ? '' : 'style="display:none;"'; ?>>
                                                                <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Use Content from Page', 'ARForms'); ?></div>
                                                                <div class="arfcolumnright">

                                                                    <div class="sltstandard" style="float:none;"><?php $armainhelper->wp_pages_dropdown('options[success_page_id]', isset($values['success_page_id']) ? $values['success_page_id'] : "", '', 'option_success_page_id'); ?></div>
                                                                    <div class="arferrmessage" id="option_success_page_id_error" style="display:none;"><?php _e('This field cannot be blank.', 'ARForms'); ?></div>

                                                                </div>
                                                            </div>

                                                            <?php do_action('arf_additional_onsubmit_settings', $id, $is_ref_form, $values); ?>

                                                        </div>

                                                    </div>

                                                    <?php do_action('arf_after_onsubmit_settings_container', $id, $is_ref_form, $values); ?>

                                                    <div id="arf_autoresponse" class="arfsettingsubcontainer">

                                                        <div class="arfformtable">

                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft arfsettingtitle"><?php _e('Auto Response Email', 'ARForms'); ?></div>
                                                                <div class="arfcolumnright"></div>
                                                            </div>
                                                            <?php
                                                            $arfsettingspacer = '';
                                                            if (is_rtl()) {
                                                                $arfsettingspacer = 'float:left;margin-bottom:5px;width:100%;';
                                                            }
                                                            ?>
                                                            <div class="arfsettingspacer" style=" <?php echo $arfsettingspacer; ?>"></div>
                                                            <?php $values['auto_responder'] = isset($values['auto_responder']) ? $values['auto_responder'] : ''; ?>
                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft"><input type="checkbox" class="chkstanard" name="options[auto_responder]" id="auto_responder" value="1" <?php checked(@$values['auto_responder'], 1); ?> onchange="CheckUserAutomaticResponseEnableDisable();" /><label for="auto_responder" ><span></span><spam class="arf_automatic_response_enable_title"><?php _e('Send an automatic response to users after submitting the form', 'ARForms') ?></spam></label></div>
                                                                <div class="arfcolumnright"></div>
                                                            </div>

                                                            <div class="arfsettingspacer" style=" <?php echo $arfsettingspacer; ?>"></div>
                                                            <?php
                                                            $auto_responder_disabled = "";
                                                            if (@$values['auto_responder'] < 1) {
                                                                $auto_responder_disabled = "disabled='disabled'";
                                                            }

                                                            if (is_rtl()) {
                                                                $tab_row_style = 'float:right';
                                                                $txt_align = 'text-align:right';
                                                                $float = 'float:right';
                                                            } else {
                                                                $tab_row_style = '';
                                                                $txt_align = '';
                                                                $float = 'float:left';
                                                            }
                                                            ?>
                                                            <div class="arftablerow" style=" <?php echo $tab_row_style; ?>">
                                                                <div class="arfcolmnleft" style=' <?php echo $txt_align; ?>'>

                                                                    <div class="arftablerow">
                                                                        <div class="arfcolumnleft arfsettingsubtitle"><?php echo stripslashes(__('Select field to send mail', 'ARForms')); ?></div>
                                                                        <div class="arfcolumnright" style=" <?php echo $tab_row_style; ?>">
                                                                            <span id="select_ar_email_to">
                                                                                <div class="sltstandard" style=" <?php echo $float; ?>">

                                                                                    <?php
                                                                                    $selectbox_field_options = "";
                                                                                    $selectbox_field_value_label = "";
                                                                                    $user_responder_email = "";
                                                                                    if (!empty($values['fields'])) {
                                                                                        foreach ($values['fields'] as $val_key => $fo) {
                                                                                            if (in_array($fo['type'], array('email', 'text', 'hidden', 'radio', 'select'))) {
                                                                                                if (($fo["id"] == $values['ar_email_to']) || ($fo["ref_field_id"] == $values['ar_email_to'])) {
                                                                                                    $selectbox_field_value_label = $fo["name"];
                                                                                                    $user_responder_email = $values['ar_email_to'];
                                                                                                }

                                                                                                $current_field_id = ($fo["ref_field_id"] > 0 ) ? $fo["ref_field_id"] : $fo["id"];
                                                                                                $selectbox_field_options .= '<li class="arf_selectbox_option" data-value="' . $current_field_id . '" data-label="' . $fo["name"] . '">' . $fo["name"] . '</li>';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                    <?php $user_responder_email = apply_filters('arf_change_autoresponse_selected_email_value_in_outside', $user_responder_email, $id, $is_ref_form, $values); ?>
                                                                                    <?php $selectbox_field_value_label = apply_filters('arf_change_autoresponse_selected_email_label_in_outside', $selectbox_field_value_label, $id, $is_ref_form, $values); ?>
                                                                                    <input id="options_ar_user_email_to" name="options[ar_email_to]" value="<?php
                                                                                    if ($responder_email != "") {
                                                                                        echo $responder_email;
                                                                                    } else {
                                                                                        echo $user_responder_email;
                                                                                    }
                                                                                    ?>" type="hidden" <?php echo $auto_responder_disabled; ?>>
                                                                                    <dl class="arf_selectbox" data-name="options[ar_email_to]" data-id="options_ar_user_email_to" style="width:160px;">
                                                                                        <dt class="options_ar_user_email_to_dt <?php
                                                                                        if ($auto_responder_disabled != "") {
                                                                                            echo 'arf_disable_selectbox';
                                                                                        }
                                                                                        ?>"><span><?php
                                                                                                if ($selectbox_field_value_label != "") {
                                                                                                    echo $selectbox_field_value_label;
                                                                                                } else {
                                                                                                    echo __('Select Field', 'ARForms');
                                                                                                }
                                                                                                ?></span>
                                                                                        <input value="<?php
                                                                                        if ($responder_email != "") {
                                                                                            echo $responder_email;
                                                                                        } else {
                                                                                            echo $user_responder_email;
                                                                                        }
                                                                                        ?>" style="display:none;width:148px;" class="arf_autocomplete" type="text">
                                                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                                        <dd>
                                                                                            <ul class="arf_email_field_dropdown" style="display: none;" data-id="options_ar_user_email_to">
                                                                                                <li class="arf_selectbox_option" data-value="" data-label="<?php _e('Select Field', 'ARForms'); ?>"><?php _e('Select Field', 'ARForms'); ?></li>

                                                                                                <?php echo $selectbox_field_options; ?>
                                                                                                <?php do_action('arf_add_autoresponse_email_option_in_out_side', $id, $is_ref_form, $values); ?>           
                                                                                            </ul>
                                                                                        </dd>
                                                                                    </dl>
                                                                                </div>
                                                                                <div class="tooltip_main"><img src="<?php echo ARFIMAGESURL ?>/tooltips-icon.png" alt="?" style="margin-left:20px;" class="arfhelptip" title="<?php _e('Please map desired email field from the list of fields used in your form. And system will send response email to this address.', 'ARForms') ?>"/></div>
                                                                            </span>
                                                                            <div style="clear:both"></div>
                                                                            <label class="howto" style="font-style:normal; display:none;"><?php _e('Select <strong>Single Line Text field</strong> or <strong>Email field</strong> to send autoresponse email to that address. First you need to create that field in form itself.', 'ARForms') ?></label>	
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="arfcolmnright">

                                                                    <div class="arftablerow">
                                                                        <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Subject', 'ARForms'); ?></div>
                                                                        <div class="arfcolumnright">
                                                                            <?php
                                                                            if (is_rtl()) {
                                                                                $email_subject_style = 'float:right;margin-top:0px;width:260px;';
                                                                                $email_subject_txt = 'width:260px;';
                                                                                $email_subject_add_field_btn = 'float:right;margin-top:-1px;margin-right:13px;';
                                                                                $email_subject_field_opt_cls = 'email_subject_add_field_opt_cls';
                                                                                $email_subject_add_field_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto;min-width:208px;';
                                                                            } else {
                                                                                $email_subject_style = 'float:left;margin-top:0px;width:300px;';
                                                                                $email_subject_txt = 'width:297px;';
                                                                                $email_subject_add_field_btn = 'float:left;margin-top:0px;';
                                                                                $email_subject_add_field_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto;min-width:208px;';
                                                                            }
                                                                            ?>
                                                                            <div style="float:left;margin-top:0px;width:100%;"> 
                                                                                <div style=" <?php echo $email_subject_style ?>">
                                                                                    <?php
                                                                                    $ar_email_subject = isset($values['ar_email_subject']) ? $values['ar_email_subject'] : '';
                                                                                    $ar_email_subject = $arformhelper->replace_field_shortcode($ar_email_subject);
                                                                                    ?>    	   
                                                                                    <input type="text" name="options[ar_email_subject]" id="ar_email_subject" size="55" class="txtstandardnew arf_advanceemailfield" value="<?php echo esc_attr($ar_email_subject); ?>" style=" <?php echo $email_subject_txt; ?>" <?php echo $auto_responder_disabled; ?>/> 
                                                                                </div>
                                                                                <div style=" <?php echo $email_subject_add_field_btn; ?>">
                                                                                    <button type="button" class="arfemailaddbtn" onclick="add_field_fun('add_field_subject')" id="add_field_email_subject_but" <?php echo $auto_responder_disabled; ?>><?php _e('Add Field', 'ARForms'); ?>&nbsp;&nbsp;<img src="<?php echo ARFIMAGESURL ?>/down-arrow.png" align="absmiddle" /></button>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                            if (is_rtl()) {
                                                                                $main_field_modal = '';
                                                                                $main_field_modal_cls = 'auto_res_user_subject';
                                                                            } else {
                                                                                $main_field_modal = 'position:relative;right:0;left:224px;top:38px;';
                                                                                $main_field_modal_cls = '';
                                                                            }
                                                                            ?>
                                                                            <div style=" <?php echo $main_field_modal; ?>" class="main_field_modal <?php echo $main_field_modal_cls; ?>">
                                                                                <div class="arfmodal arfaddfieldmodal" id="add_field_subject" style=" <?php echo $email_subject_add_field_opt; ?>">
                                                                                    <div class="arfmodal-header"><div class="arfaddfieldtitle"><?php _e('Fields', 'ARForms'); ?><div data-dismiss="arfmodal" onclick="close_add_field_subject('add_field_subject')" style="float:right; cursor:pointer; margin-right:-5px; margin-top:-3px;"><img src="<?php echo ARFIMAGESURL . '/close-button.png' ?>" align="absmiddle" /></div></div></div>
                                                                                    <div class="arfmodal-body_p">
                                                                                        <?php $arfieldhelper->get_shortcode_modal($values['id'], 'ar_email_subject', 'no_email', 'style="width:330px;"'); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>    

                                                            <div class="arfsettingspacer" style=" <?php echo $arfsettingspacer; ?>"></div>

                                                            <div class="arftablerow" style=" <?php echo $tab_row_style; ?>">
                                                                <div class="arfcolmnleft" style=" <?php echo $txt_align; ?>">

                                                                    <div class="arftablerow">
                                                                        <div class="arfcolumnleft arfsettingsubtitle"><?php _e('From/Replyto Name', 'ARForms') ?></div>
                                                                        <div class="arfcolumnright"><input type="text" class="txtstandardnew" id="options_ar_user_from_name" name="options[ar_user_from_name]" value="<?php echo (isset($values['ar_user_from_name']) && $values['ar_user_from_name'] != '') ? $values['ar_user_from_name'] : $arfsettings->reply_to_name; ?>" style="width:297px;"  <?php echo $auto_responder_disabled; ?>></div>
                                                                    </div>

                                                                </div>

                                                                <div class="arfcolmnright">
                                                                    <?php
                                                                    if (is_rtl()) {
                                                                        $reply_to_email = 'float:right;width:260px;margin-top:0px;';
                                                                        $reply_to_email_txt = 'width:260px;';
                                                                        $reply_to_email_btn = 'float:right;margin-top:-1px;margin-right:13px;';
                                                                        $reply_to_email_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto;min-width:200px;margin-left:10px;';
                                                                    } else {
                                                                        $reply_to_email = 'float:left;width:300px;margin-top:0px;';
                                                                        $reply_to_email_txt = 'width:297px;';
                                                                        $reply_to_email_btn = 'float:left;margin-top:0px;';
                                                                        $reply_to_email_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto;min-width:200px;';
                                                                    }
                                                                    ?>
                                                                    <div class="arftablerow">
                                                                        <div class="arfcolumnleft arfsettingsubtitle"><?php _e('From/Reply to Email', 'ARForms'); ?></div>
                                                                        <div class="arfcolumnright">

                                                                            <div style="float:left;margin-top:0px;width:100%;"> 
                                                                                <div style=" <?php echo $reply_to_email; ?>">
                                                                                    <?php
                                                                                    $ar_user_from_email = isset($values['ar_user_from_email']) ? $values['ar_user_from_email'] : '';
                                                                                    if ($ar_user_from_email == '')
                                                                                        $ar_user_from_email = $arfsettings->reply_to;
                                                                                    else
                                                                                        $ar_user_from_email = $values['ar_user_from_email'];

                                                                                    $ar_user_from_email = $arformhelper->replace_field_shortcode($ar_user_from_email);
                                                                                    ?>
                                                                                    <input type="text" class="txtstandardnew arf_advanceemailfield" style=" <?php echo $reply_to_email_txt; ?>" value="<?php echo $ar_user_from_email; ?>" id="ar_user_from_email" name="options[ar_user_from_email]" <?php echo $auto_responder_disabled; ?> />
                                                                                </div>
                                                                                <div style=" <?php echo $reply_to_email_btn; ?>">
                                                                                    <button type="button" class="arfemailaddbtn" onclick="add_field_fun('add_field_user_email')" id="add_field_user_email_but" <?php echo $auto_responder_disabled; ?>><?php _e('Add Field', 'ARForms'); ?>&nbsp;&nbsp;<img src="<?php echo ARFIMAGESURL ?>/down-arrow.png" align="absmiddle" /></button>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                            if (is_rtl()) {
                                                                                $auto_res_email_cls = 'auto_res_user_email';
                                                                                $auto_res_email_style = '';
                                                                            } else {
                                                                                $auto_res_email_style = 'position:relative;right:0;left:224px;top:38px;';
                                                                                $auto_res_email_cls = '';
                                                                            }
                                                                            ?>
                                                                            <div style=" <?php echo $auto_res_email_style; ?>" class="main_field_modal <?php echo $auto_res_email_cls; ?>">
                                                                                <div class="arfmodal arfaddfieldmodal" id="add_field_user_email" style=" <?php echo $reply_to_email_opt; ?>">

                                                                                    <div class="arfmodal-header"><div class="arfaddfieldtitle"><?php _e('Fields', 'ARForms'); ?><div data-dismiss="arfmodal" onclick="close_add_field_subject('add_field_user_email')" style="float:right; cursor:pointer; margin-right:-5px; margin-top:-3px;"><img src="<?php echo ARFIMAGESURL . '/close-button.png' ?>" align="absmiddle" /></div></div></div>

                                                                                    <div class="arfmodal-body_email">
                                                                                        <?php $arfieldhelper->get_shortcode_modal($values['id'], 'ar_user_from_email', 'email', 'style="width:330px;"'); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </div>            
                                                            <?php
                                                            if (is_rtl()) {
                                                                $arfsettingspacer_1 = 'float:left;width:100%;margin-bottom:15px;';
                                                                $arfsettingspace_1 = '';
                                                            } else {
                                                                $arfsettingspace_1 = 'height:15px;';
                                                                $arfsettingspacer_1 = 'height:15px;';
                                                            }
                                                            ?>
                                                            <div style=" <?php echo $arfsettingspacer_1; ?>"></div>

                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Message', 'ARForms'); ?></div>
                                                                <?php
                                                                if (is_rtl()) {
                                                                    $auto_res_msg_style = 'float:right;margin-top:0px;width:565px;';
                                                                    $auto_res_msg_btn = 'float:right;margin-top:0px;margin-right:10px;';
                                                                    $auto_res_msg_add_field_opt = 'display:none;top:0px;position:absolute;width:auto; min-width:200px;margin-left:67px;';
                                                                } else {
                                                                    $auto_res_msg_style = 'float:left;margin-top:0px;width:565px;';
                                                                    $auto_res_msg_btn = 'float:left;margin-top:0px;';
                                                                    $auto_res_msg_add_field_opt = 'display:none;top:0px;position:absolute;width:auto; min-width:200px;';
                                                                }
                                                                ?>
                                                                <div class="arfcolumnright">
                                                                    <div style="float:left;margin-top:0px;width:100%;"> 
                                                                        <div style=" <?php echo $auto_res_msg_style; ?>">
                                                                            <?php
                                                                            $ar_email_message = (isset($values['ar_email_message']) and ! empty($values['ar_email_message']) ) ? esc_attr($arformcontroller->br2nl($values['ar_email_message'])) : '';
                                                                            $ar_email_message = $arformhelper->replace_field_shortcode($ar_email_message);

                                                                            $email_editor_settings = array(
                                                                                'wpautop' => true,
                                                                                'media_buttons' => false,
                                                                                'textarea_name' => 'options[ar_email_message]',
                                                                                'textarea_rows' => '4',
                                                                                'tinymce' => false,
                                                                                'editor_class' => "txtmultinew arf_advanceemailfield",
                                                                            );
                                                                            if (version_compare($GLOBALS['wp_version'], '3.3', '<')) {
                                                                                echo '<textarea name="options[ar_email_message]" id="ar_email_message" cols="50" rows="5" style="width:99% !important; " class="txtmultinew arfplacelonginput arf_advanceemailfield" ' . $auto_responder_disabled . '>' . $ar_email_message . '</textarea>';
                                                                            } else {
                                                                                wp_editor($ar_email_message, 'ar_email_message', $email_editor_settings);
                                                                            }
                                                                            ?>
                                                                            <div style="margin-top: 5px;">
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_all_values]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with form\'s all fields & labels.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_referer]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with entry referer.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_added_date_time]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with entry added time.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_ipaddress]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with IP Address.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_browsername]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with user browser name.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_entryid]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with Entry ID.', 'ARForms'); ?></label></div>
                                                                                <?php do_action('arf_add_auto_response_mail_shortcode_in_out_side', $id, $is_ref_form, $values); ?>
                                                                            </div>
                                                                        </div>
                                                                        <div style=" <?php echo $auto_res_msg_btn; ?>">
                                                                            <button type="button" class="arfemailaddbtn add_field_btn" onclick="add_field_fun('add_field_message')" id="add_field_message_but" <?php echo $auto_responder_disabled; ?>><?php _e('Add Field', 'ARForms'); ?>&nbsp;&nbsp;<img src="<?php echo ARFIMAGESURL ?>/down-arrow.png" align="absmiddle" /></button>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    if (is_rtl()) {
                                                                        $auto_res_user_msg_cls = 'auto_res_user_msg';
                                                                        $auto_res_user_msg_css = '';
                                                                    } else {
                                                                        $auto_res_user_msg_cls = '';
                                                                        $auto_res_user_msg_css = 'position:relative;right;0;left:490px;top:38px;';
                                                                    }
                                                                    $auto_res_msg_add_field_opt = isset($auto_res_msg_add_field_opt) ? $auto_res_msg_add_field_opt : '';
                                                                    ?>
                                                                    <div style=" <?php echo $auto_res_user_msg_css; ?>" class="main_field_modal <?php echo $auto_res_user_msg_cls; ?>">
                                                                        <div class="arfmodal arfaddfieldmodal" id="add_field_message" style=" <?php echo $auto_res_msg_add_field_opt; ?>">
                                                                            <div class="arfmodal-header"><div class="arfaddfieldtitle"><?php _e('Fields', 'ARForms'); ?><div data-dismiss="arfmodal" onclick="close_add_field_subject('add_field_message')" style="float:right; cursor:pointer; margin-right:-5px; margin-top:-3px;"><img src="<?php echo ARFIMAGESURL . '/close-button.png' ?>" align="absmiddle" /></div></div></div>

                                                                            <div class="arfmodal-body_p">
                                                                                <?php $arfieldhelper->get_shortcode_modal($values['id'], 'ar_email_message', 'no_email', 'style="width:330px;"'); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="arferrmessage" id="ar_email_message_error" style="display:none;"><?php _e('This field cannot be blank.', 'ARForms'); ?></div>

                                                                </div>
                                                            </div>


                                                            <div style="height:40px;"></div>
                                                            <?php $values['chk_admin_notification'] = isset($values['chk_admin_notification']) ? $values['chk_admin_notification'] : ''; ?>                                      
                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft"><input type="checkbox" class="chkstanard" name="options[chk_admin_notification]" id="chk_admin_notification" value="1" <?php checked(@$values['chk_admin_notification'], 1); ?> onchange="CheckAdminAutomaticResponseEnableDisable();" /><label for="chk_admin_notification" ><span></span><spam class="arf_automatic_response_enable_title"><?php _e('Send an automatic response to admin after submitting the form', 'ARForms') ?></spam></label></div>
                                                                <div class="arfcolumnright"></div>
                                                            </div>

                                                            <div class="arfsettingspacer"></div>

                                                            <?php
                                                            $chk_admin_notification_disabled = "";
                                                            if (@$values['chk_admin_notification'] < 1) {
                                                                $chk_admin_notification_disabled = "disabled='disabled'";
                                                            }
                                                            ?>

                                                            <div class="arftablerow" style=" <?php echo $tab_row_style; ?>">
                                                                <div class="arfcolmnleft" style=" <?php echo $txt_align; ?>">

                                                                    <div class="arftablerow">
                                                                        <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Admin Email', 'ARForms'); ?></div>
                                                                        <div class="arfcolumnright">
                                                                            <?php
                                                                            if (is_rtl()) {
                                                                                $admin_to_email = 'float:right;margin-top:0px;width:260px;';
                                                                                $admin_to_email_txt = 'width:260px;';
                                                                                $admin_to_email_btn = 'float:right;margin-top:0px;margin-right:13px;';
                                                                                $admin_to_email_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto; min-width: 200px;margin-left:12px;';
                                                                            } else {
                                                                                $admin_to_email = 'float:left;margin-top:0px;width:300px;';
                                                                                $admin_to_email_txt = 'width:297px;';
                                                                                $admin_to_email_btn = 'float:left;margin-top:0px;';
                                                                                $admin_to_email_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto; min-width: 200px;';
                                                                            }
                                                                            ?>
                                                                            <div style="float:left;margin-top:0px;width:100%;"> 
                                                                                <div style=" <?php echo $admin_to_email; ?>">
                                                                                    <?php
                                                                                    $ar_admin_to_email = isset($values['notification'][0]['reply_to']) ? esc_attr($values['notification'][0]['reply_to']) : '';
                                                                                    if ($ar_admin_to_email == '') {
                                                                                        $ar_admin_to_email = $arfsettings->reply_to;
                                                                                    } else {
                                                                                        $ar_admin_to_email = $values['notification'][0]['reply_to'];
                                                                                    }
                                                                                    $ar_admin_to_email = $arformhelper->replace_field_shortcode($ar_admin_to_email);
                                                                                    ?>
                                                                                    <input type="text" name="options[reply_to]" id="options_admin_reply_to_notification" value="<?php echo $ar_admin_to_email; ?>" class="txtstandardnew arf_advanceemailfield" style=" <?php echo $admin_to_email_txt; ?>" <?php echo $chk_admin_notification_disabled; ?> />
                                                                                </div>
                                                                                <div style=" <?php echo $admin_to_email_btn; ?>">
                                                                                    <button type="button" class="arfemailaddbtn" onclick="add_field_fun('add_field_admin_email_to')" id="add_field_admin_email_but_to"  <?php echo $chk_admin_notification_disabled; ?>><?php _e('Add Field', 'ARForms'); ?>&nbsp;&nbsp;<img src="<?php echo ARFIMAGESURL ?>/down-arrow.png" align="absmiddle" /></button>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                            if (is_rtl()) {
                                                                                $auto_res_admin_email_cls = 'auto_res_admin_email';
                                                                                $auto_res_admin_email_css = '';
                                                                            } else {
                                                                                $auto_res_admin_email_cls = '';
                                                                                $auto_res_admin_email_css = 'position:relative;right:0;left:224px;top:38px;';
                                                                            }
                                                                            ?>
                                                                            <div style=" <?php echo $auto_res_admin_email_css; ?>" class="main_field_modal <?php echo $auto_res_admin_email_cls; ?>">
                                                                                <div class="arfmodal arfaddfieldmodal" id="add_field_admin_email_to" style=" <?php echo $admin_to_email_opt; ?>">

                                                                                    <div class="arfmodal-header"><div class="arfaddfieldtitle"><?php _e('Fields', 'ARForms'); ?><div data-dismiss="arfmodal" onclick="close_add_field_subject('add_field_admin_email_to')" style="float:right; cursor:pointer; margin-right:-5px; margin-top:-3px;"><img src="<?php echo ARFIMAGESURL . '/close-button.png' ?>" align="absmiddle" /></div></div></div>

                                                                                    <div class="arfmodal-body_email">
                                                                                        <?php $arfieldhelper->get_shortcode_modal($values['id'], 'options_admin_reply_to_notification', 'email', 'style="width:330px;"'); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="arfcolmnright">

                                                                    <div class="arftablerow">
                                                                        <div class="arfcolumnleft arfsettingsubtitle"><?php _e('From/Replyto Email', 'ARForms'); ?></div>
                                                                        <div class="arfcolumnright">
                                                                            <?php
                                                                            if (is_rtl()) {
                                                                                $admin_from_email = 'float:right;margin-top:0px;width:260px;';
                                                                                $admin_from_email_txt = 'width:260px;';
                                                                                $admin_from_email_btn = 'float:right;margin-top:0px;margin-right:13px;';
                                                                                $admin_from_email_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto; min-width: 200px;margin-left:12px;';
                                                                            } else {
                                                                                $admin_from_email = 'float:left;margin-top:0px;width:300px;';
                                                                                $admin_from_email_txt = 'width:297px;';
                                                                                $admin_from_email_btn = 'float:left;margin-top:0px;';
                                                                                $admin_from_email_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto; min-width: 200px;';
                                                                            }
                                                                            ?>
                                                                            <div style="float:left;margin-top:0px;width:100%;"> 
                                                                                <div style=" <?php echo $admin_from_email; ?>">
                                                                                    <?php
                                                                                    $ar_admin_from_email = isset($values['ar_admin_from_email']) ? $values['ar_admin_from_email'] : '';
                                                                                    if ($ar_admin_from_email == '') {
                                                                                        $ar_admin_from_email = $arfsettings->reply_to;
                                                                                    } else {
                                                                                        $ar_admin_from_email = $values['ar_admin_from_email'];
                                                                                    }
                                                                                    $ar_admin_from_email = $arformhelper->replace_field_shortcode($ar_admin_from_email);
                                                                                    ?>
                                                                                    <input type="text" class="txtstandardnew arf_advanceemailfield" style=" <?php echo $admin_from_email_txt; ?>" value="<?php echo $ar_admin_from_email; ?>" id="ar_admin_from_email" name="options[ar_admin_from_email]" <?php echo $chk_admin_notification_disabled; ?> />
                                                                                </div>
                                                                                <div style=" <?php echo $admin_from_email_btn; ?>">
                                                                                    <button type="button" class="arfemailaddbtn" onclick="add_field_fun('add_field_admin_email')" id="add_field_admin_email_but"  <?php echo $chk_admin_notification_disabled; ?>><?php _e('Add Field', 'ARForms'); ?>&nbsp;&nbsp;<img src="<?php echo ARFIMAGESURL ?>/down-arrow.png" align="absmiddle" /></button>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                            if (is_rtl()) {
                                                                                $auto_res_admin_email_cls = 'auto_res_admin_email';
                                                                                $auto_res_admin_email_css = '';
                                                                            } else {
                                                                                $auto_res_admin_email_cls = '';
                                                                                $auto_res_admin_email_css = 'position:relative;right:0;left:224px;top:38px;';
                                                                            }
                                                                            ?>
                                                                            <div style=" <?php echo $auto_res_admin_email_css; ?>" class="main_field_modal <?php echo $auto_res_admin_email_cls; ?>">
                                                                                <div class="arfmodal arfaddfieldmodal" id="add_field_admin_email" style=" <?php echo $admin_from_email_opt; ?>">

                                                                                    <div class="arfmodal-header"><div class="arfaddfieldtitle"><?php _e('Fields', 'ARForms'); ?><div data-dismiss="arfmodal" onclick="close_add_field_subject('add_field_admin_email')" style="float:right; cursor:pointer; margin-right:-5px; margin-top:-3px;"><img src="<?php echo ARFIMAGESURL . '/close-button.png' ?>" align="absmiddle" /></div></div></div>

                                                                                    <div class="arfmodal-body_email">
                                                                                        <?php $arfieldhelper->get_shortcode_modal($values['id'], 'ar_admin_from_email', 'email', 'style="width:330px;"'); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>    
                                                            <?php
                                                            if (is_rtl()) {
                                                                $arfsettingspacer_2 = 'float:right;width:100%;height:15px;';
                                                            } else {
                                                                $arfsettingspacer_2 = 'height:15px;';
                                                            }
                                                            ?>
                                                            <div class="arfsettingspacer" style=' <?php echo $arfsettingspacer_2; ?>'></div>

                                                            <div class="arftablerow">
                                                                <div class="arfcolmnleft" style=' <?php echo $txt_align; ?>'>

                                                                    <div class="arftablerow">
                                                                        <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Subject', 'ARForms') ?></div>
                                                                        <div class="arfcolumnright">
                                                                            <?php
                                                                            if (is_rtl()) {
                                                                                $admin_email_subject = 'float:right;margin-top:0px;width:260px;';
                                                                                $admin_email_subject_txt = 'width:260px;';
                                                                                $admin_email_subject_btn = 'float:right;margin-top:0px;margin-right:13px;';
                                                                                $admin_email_subject_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto; min-width: 200px;margin-left:12px;';
                                                                            } else {
                                                                                $admin_email_subject = 'float:left;margin-top:0px;width:300px;';
                                                                                $admin_email_subject_txt = 'width:297px;';
                                                                                $admin_email_subject_btn = 'float:left;margin-top:0px;';
                                                                                $admin_email_subject_opt = 'display:none;margin-top:0px;top:0px;position:absolute;width:auto; min-width: 200px;';
                                                                            }
                                                                            ?>
                                                                            <div style="float:left;margin-top:0px;width:100%">
                                                                                <div style="<?php echo $admin_email_subject; ?>">
                                                                                    <?php
                                                                                    $admin_email_subject_value = (isset($values['admin_email_subject'])) ? esc_attr($values['admin_email_subject']) : '';
                                                                                    if ($admin_email_subject_value == '') {
                                                                                        $admin_email_subject_value = '[form_name] Form submitted on [site_name]';
                                                                                    } else {
                                                                                        $admin_email_subject_value = $values['admin_email_subject'];
                                                                                    }
                                                                                    ?>
                                                                                    <input type="text" name="options[admin_email_subject]" id="admin_email_subject" size="55" class="txtstandardnew arf_advanceemailfield" value="<?php echo $admin_email_subject_value; ?>" style="<?php echo $admin_email_subject_txt; ?>" <?php echo $chk_admin_notification_disabled; ?>/>

                                                                                </div>
                                                                                <div style=" <?php echo $admin_email_subject_btn; ?>">
                                                                                    <button type="button" class="arfemailaddbtn" onclick="add_field_fun('add_field_admin_email_subject')" id="add_field_admin_email_but_subject"  <?php echo $chk_admin_notification_disabled; ?>><?php _e('Add Field', 'ARForms'); ?>&nbsp;&nbsp;<img src="<?php echo ARFIMAGESURL ?>/down-arrow.png" align="absmiddle" /></button>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                            if (is_rtl()) {
                                                                                $auto_res_admin_email_sub_cls = 'auto_res_admin_email_subject';
                                                                                $auto_res_admin_email_sub_css = '';
                                                                            } else {
                                                                                $auto_res_admin_email_sub_cls = '';
                                                                                $auto_res_admin_email_sub_css = 'position:relative;right:0;left:224px;top:38px;';
                                                                            }
                                                                            ?>
                                                                            <div style=" <?php echo $auto_res_admin_email_sub_css; ?>" class="main_field_modal <?php echo $auto_res_admin_email_sub_cls; ?>">
                                                                                <div class="arfmodal arfaddfieldmodal" id="add_field_admin_email_subject" style=" <?php echo $admin_email_subject_opt; ?>">

                                                                                    <div class="arfmodal-header"><div class="arfaddfieldtitle"><?php _e('Fields', 'ARForms'); ?><div data-dismiss="arfmodal" onclick="close_add_field_subject('add_field_admin_email_subject')" style="float:right; cursor:pointer; margin-right:-5px; margin-top:-3px;"><img src="<?php echo ARFIMAGESURL . '/close-button.png' ?>" align="absmiddle" /></div></div></div>

                                                                                    <div class="arfmodal-body_email">
                                                                                        <?php $arfieldhelper->get_shortcode_modal($values['id'], 'admin_email_subject', 'email', 'style="width:330px;"'); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div style="margin-top: 5px;">
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[form_name]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with form name.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[site_name]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with name of site.', 'ARForms'); ?></label></div>

                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="arfcolmnright"></div>
                                                            </div>

                                                            <div class="arfsettingspacer" style=" <?php echo $arfsettingspacer_2; ?>"></div>

                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Admin Message', 'ARForms'); ?></div>

                                                                <div class="arfcolumnright">
                                                                    <div style="float:left;margin-top:0px;width:100%;">
                                                                        <div style=" <?php echo $auto_res_msg_style; ?>">
                                                                            <?php
                                                                            $ar_admin_email_message = (isset($values['ar_admin_email_message']) and ! empty($values['ar_admin_email_message']) ) ? esc_attr($arformcontroller->br2nl($values['ar_admin_email_message'])) : '';
                                                                            $ar_admin_email_message = $arformhelper->replace_field_shortcode($ar_admin_email_message);

                                                                            $email_editor_settings = array(
                                                                                'wpautop' => true,
                                                                                'media_buttons' => false,
                                                                                'textarea_name' => 'options[ar_admin_email_message]',
                                                                                'textarea_rows' => '4',
                                                                                'tinymce' => false,
                                                                                'editor_class' => "txtmultinew arf_advanceemailfield",
                                                                            );
                                                                            if (version_compare($GLOBALS['wp_version'], '3.3', '<')) {
                                                                                echo '<textarea name="options[ar_admin_email_message]" id="ar_admin_email_message" class="txtmultinew arfplacelonginput arf_advanceemailfield" style="width:99% !important;" rows="5" cols="50" ' . $chk_admin_notification_disabled . '>' . $ar_admin_email_message . '</textarea>';
                                                                            } else {
                                                                                wp_editor($ar_admin_email_message, 'ar_admin_email_message', $email_editor_settings);
                                                                            }
                                                                            ?>

                                                                            <div style="margin-top: 5px;">
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_all_values]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with form\'s all fields & labels.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_referer]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with entry referer.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_added_date_time]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with entry added time.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_ipaddress]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with IP Address.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_browsername]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with user browser name.', 'ARForms'); ?></label></div>
                                                                                <div class="sub_content"><label class="lblsubtitle"><code>[ARF_form_entryid]</code> - </label><label class="lblsubtitle" style="text-shadow:none;"><?php _e('This will be replaced with Entry ID.', 'ARForms'); ?></label></div>
                                                                                <?php do_action('arf_add_admin_mail_shortcode_in_outside', $id, $is_ref_form, $values); ?>
                                                                            </div>
                                                                        </div>
                                                                        <div style=" <?php echo $auto_res_msg_btn; ?>">
                                                                            <button type="button" class="arfemailaddbtn add_field_btn" onclick="add_field_fun('add_field_admin_message')" id="add_field_admin_message_but" <?php echo $chk_admin_notification_disabled; ?>><?php _e('Add Field', 'ARForms'); ?>&nbsp;&nbsp;<img src="<?php echo ARFIMAGESURL ?>/down-arrow.png" align="absmiddle" /></button>
                                                                        </div>

                                                                    </div>

                                                                    <div style=" <?php echo $auto_res_user_msg_css; ?>" class="main_field_modal <?php echo $auto_res_user_msg_cls; ?>">
                                                                        <div class="arfmodal arfaddfieldmodal" id="add_field_admin_message" style=" <?php echo $auto_res_msg_add_field_opt; ?>">
                                                                            <div class="arfmodal-header"><div class="arfaddfieldtitle"><?php _e('Fields', 'ARForms'); ?><div data-dismiss="arfmodal" onclick="close_add_field_subject('add_field_admin_message')" style="float:right; cursor:pointer; margin-right:-5px; margin-top:-3px;"><img src="<?php echo ARFIMAGESURL . '/close-button.png' ?>" align="absmiddle" /></div></div></div>

                                                                            <div class="arfmodal-body_p">
                                                                                <?php $arfieldhelper->get_shortcode_modal($values['id'], 'ar_admin_email_message', 'no_email', 'style="width:330px;"'); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="arferrmessage" id="ar_admin_email_message_error" style="display:none;"><?php _e('This field cannot be blank.', 'ARForms'); ?></div>

                                                                </div>
                                                                <div class="arfcolmnright"></div>
                                                            </div>

                                                            <div style="display:none;">
                                                                <input type="text" name="options[email_to]" class="txtstandardnew" value="<?php echo (isset($values['notification'][0]['reply_to'])) ? esc_attr($values['notification'][0]['reply_to']) : ''; ?>" style="width: 269px;" />
                                                                <textarea name="options[email_message]" class="txtmultinew arfplacelonginput" id="email_message" cols="50" rows="5" style="margin-top:10px;"><?php echo (isset($values['email_message'])) ? $armainhelper->esc_textarea($arformcontroller->br2nl($values['email_message'])) : ''; ?></textarea>	
                                                                <input type="checkbox" class="chkstanard" name="options[inc_user_info]" id="inc_user_info" value="1" <?php
                                                                $values['inc_user_info'] = isset($values['inc_user_info']) ? $values['inc_user_info'] : '';
                                                                checked($values['inc_user_info'], 1);
                                                                ?> />
                                                                <input type="checkbox" class="chkstanard" name="options[plain_text]" id="plain_text" value="1" <?php
                                                                $values['plain_text'] = isset($values['plain_text']) ? $values['plain_text'] : '';
                                                                checked($values['plain_text'], 1);
                                                                ?> />
                                                            </div>

                                                            <?php do_action('arf_additional_autoresponder_settings', $id, $is_ref_form, $values); ?>

                                                        </div>                            
                                                    </div>

                                                    <?php do_action('arf_after_autoresponder_settings_container', $id, $is_ref_form, $values); ?>

                                                    <div id="arf_emailmarketer" class="arfsettingsubcontainer">

                                                        <?php
                                                        global $wpdb;
                                                        $responder_fname = $record->autoresponder_fname;
                                                        $responder_lname = $record->autoresponder_lname;
                                                        $responder_email = $record->autoresponder_email;
                                                        $display = apply_filters('arfdisplayfieldoptions', array('label_position' => true));
                                                        $arfaction = $_REQUEST['arfaction'];
                                                        $arf_template_id = isset($template_id) ? $template_id : 0;

                                                        $res = @maybe_unserialize(get_option('arf_ar_type'));

                                                        $res1 = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_autoresponder WHERE responder_id = %d", 3), 'ARRAY_A');
                                                        $res2 = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_autoresponder WHERE responder_id = %d", 1), 'ARRAY_A');
                                                        $res3 = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_autoresponder WHERE responder_id = %d", 4), 'ARRAY_A');
                                                        $res4 = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_autoresponder WHERE responder_id = %d", 5), 'ARRAY_A');
                                                        $res5 = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_autoresponder WHERE responder_id = %d", 6), 'ARRAY_A');
                                                        $res6 = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_autoresponder WHERE responder_id = %d", 8), 'ARRAY_A');
                                                        $res7 = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_autoresponder WHERE responder_id = %d", 9), 'ARRAY_A');

                                                        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_ar WHERE frm_id = %d", $id), 'ARRAY_A');

                                                        $aweber_arr = @maybe_unserialize(isset($data[0]['aweber']) ? $data[0]['aweber'] : '' );
                                                        $mailchimp_arr = @maybe_unserialize(isset($data[0]['mailchimp']) ? $data[0]['mailchimp'] : '' );
                                                        $getresponse_arr = @maybe_unserialize(isset($data[0]['getresponse']) ? $data[0]['getresponse'] : '' );
                                                        $gvo_arr = @maybe_unserialize(isset($data[0]['gvo']) ? $data[0]['gvo'] : '' );
                                                        $ebizac_arr = @maybe_unserialize(isset($data[0]['ebizac']) ? $data[0]['ebizac'] : '' );
                                                        $icontact_arr = @maybe_unserialize(isset($data[0]['icontact']) ? $data[0]['icontact'] : '' );
                                                        $constant_contact_arr = @maybe_unserialize(isset($data[0]['constant_contact']) ? $data[0]['constant_contact'] : '' );
                                                        $data[0]['enable_ar'] = isset($data[0]['enable_ar']) ? $data[0]['enable_ar'] : '';
                                                        $global_enable_ar = maybe_unserialize(isset($data[0]['enable_ar']) ? $data[0]['enable_ar'] : '' );

                                                        $current_active_ar = '';

                                                        if (isset($mailchimp_arr['enable']) && $mailchimp_arr['enable'] == 1) {
                                                            $current_active_ar = 'mailchimp';
                                                        } else if (isset($aweber_arr['enable']) && $aweber_arr['enable'] == 1) {
                                                            $current_active_ar = 'aweber';
                                                        } else if (isset($icontact_arr['enable']) && $icontact_arr['enable'] == 1) {
                                                            $current_active_ar = 'icontact';
                                                        } else if (isset($constant_contact_arr['enable']) && $constant_contact_arr['enable'] == 1) {
                                                            $current_active_ar = 'constant_contact';
                                                        } else if (isset($getresponse_arr['enable']) && $getresponse_arr['enable'] == 1) {
                                                            $current_active_ar = 'getresponse';
                                                        } else if (isset($gvo_arr['enable']) && $gvo_arr['enable'] == 1) {
                                                            $current_active_ar = 'gvo';
                                                        } else if (isset($ebizac_arr['enable']) && $ebizac_arr['enable'] == 1) {
                                                            $current_active_ar = 'ebizac';
                                                        } else {
                                                            $current_active_ar = 'mailchimp';
                                                        }

                                                        $setact = 0;
                                                        global $arformsplugin;
                                                        $setact = $arformcontroller->$arformsplugin();
                                                        
                                                        ?>	

                                                        <div class="arfformtable">

                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft arfsettingtitle"><?php _e('Email Marketers', 'ARForms'); ?></div>
                                                                <div class="arfcolumnright"></div>
                                                            </div>

                                                            <div class="arfemailmarketdiv">
                                                                <div class="arfemailmarketbar">
                                                                    <div id="arfem_mailchimp" class="arfemailbars <?php
                                                                    if ($current_active_ar == 'mailchimp') {
                                                                        echo "arfactive";
                                                                    }
                                                                    ?>" onclick="arfshowem('mailchimp');"><div class="arfmailchimpimg"></div></div>
                                                                    <div id="arfem_aweber" class="arfemailbars <?php
                                                                    if ($current_active_ar == 'aweber') {
                                                                        echo "arfactive";
                                                                    }
                                                                    ?>" onclick="arfshowem('aweber');"><div class="arfaweberimg"></div></div>
                                                                    <div id="arfem_icontact" class="arfemailbars <?php
                                                                    if ($current_active_ar == 'icontact') {
                                                                        echo "arfactive";
                                                                    }
                                                                    ?>" onclick="arfshowem('icontact');"><div class="arficontactpimg"></div></div>
                                                                    <div id="arfem_constant_contact" class="arfemailbars <?php
                                                                    if ($current_active_ar == 'constant_contact') {
                                                                        echo "arfactive";
                                                                    }
                                                                    ?>" onclick="arfshowem('constant_contact');"><div class="arfconstantimg"></div></div>
                                                                    <div id="arfem_getresponse" class="arfemailbars <?php
                                                                    if ($current_active_ar == 'getresponse') {
                                                                        echo "arfactive";
                                                                    }
                                                                    ?>" onclick="arfshowem('getresponse');"><div class="arfgetresponseimg"></div></div>
                                                                    <div id="arfem_ebizac" class="arfemailbars <?php
                                                                    if ($current_active_ar == 'ebizac') {
                                                                        echo "arfactive";
                                                                    }
                                                                    ?>" onclick="arfshowem('ebizac');"><div class="arfebizacimg"></div></div>
                                                                    <div id="arfem_gvo" class="arfemailbars <?php
                                                                    if ($current_active_ar == 'gvo') {
                                                                        echo "arfactive";
                                                                    }
                                                                    ?>" onclick="arfshowem('gvo');"><div class="arfgvoimg"></div></div>
                                                                </div>

                                                                <div style="clear:both; height:10px;"></div>
                                                                <div style="clear:both;">

                                                                    <div id="arfem_mailchimp_div" class="arfemdiv" <?php
                                                                    if ($current_active_ar == 'mailchimp') {
                                                                        echo 'style="display:block;"';
                                                                    }
                                                                    ?>>

                                                                        <div class="arfemailcontrol">
                                                                            <?php if (($arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) and $res['mailchimp_type'] != 2) { ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_1" value="1" checked="checked" onchange="show_setting('mailchimp', '1');" <?php
                                                                                if ($setact != 1) {
                                                                                    echo 'onclick="return false"';
                                                                                }
                                                                                ?> /><label for="autores_1"><span class="ar_lbl_span"></span><?php _e('Mailchimp', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Mailchip with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } else { ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_1" value="1" <?php
                                                                                if (isset($mailchimp_arr['enable']) && $mailchimp_arr['enable'] == 1) {
                                                                                    echo "checked=checked";
                                                                                }
                                                                                ?> onchange="show_setting('mailchimp', '1');" <?php
                                                                                       if ($setact != 1) {
                                                                                           echo 'onclick="return false"';
                                                                                       }
                                                                                       ?> /><label for="autores_1"><span class="ar_lbl_span"></span><?php _e('Mailchimp', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Mailchip with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } ?>		
                                                                        </div>

                                                                        <div class="arfemailcontent">
                                                                            <?php
                                                                            $rand_num = rand(1111, 9999);
                                                                            $next = 'mailchimp';
                                                                            if ($next == 'mailchimp' && $res['mailchimp_type'] == 1) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['mailchimp']) and $global_enable_ar['mailchimp'] == 0 and isset($mailchimp_arr['enable']) and $mailchimp_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
															<div class="textarea_space"></div>
															<span classs="lblstandard">' . __('LIST NAME', 'ARForms') . ' :</span>
															<div class="textarea_space"></div>
															
															<div class="sltstandard" style="float:none;">';
                                                                                    //echo '<select name="i_mailchimp_list" style="width:180px;" id="i_mailchimp_list" data-width="180px" '.( $setact != 1 ? "readonly=readonly" : '' ).'>';

                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $lists = @maybe_unserialize($res2[0]['responder_list_id']);
                                                                                    if (count($lists) > 0 && is_array($lists)) {
                                                                                        $cntr = 0;
                                                                                        foreach ($lists as $list) {
                                                                                            if ($res2[0]['responder_list'] == $list['id'] || $cntr == 0) {
                                                                                                $selected_list_id = $list['id'];
                                                                                                $selected_list_label = $list['name'];
                                                                                                //echo '<option selected="selected" value="'.$list['id'].'">'.$list['name'].'</option>';	
                                                                                            }
                                                                                            //echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';	
                                                                                            $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $list['id'] . '" data-label="' . $list['name'] . '">' . $list['name'] . '</li>';

                                                                                            $cntr++;
                                                                                        }
                                                                                    }
                                                                                    //echo '</select>';

                                                                                    echo '<input id="i_mailchimp_list" name="i_mailchimp_list" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
														  <dl class="arf_selectbox" data-name="i_mailchimp_list" data-id="i_mailchimp_list" style="width:170px;">
															<dt><span>' . $selected_list_label . '</span>
															<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
															<i class="fa fa-caret-down fa-lg"></i></dt>
															<dd>
																<ul class="field_dropdown_menu" style="display: none;" data-id="i_mailchimp_list">
																	' . $responder_list_option . '
																</ul>
															</dd>
														  </dl>';


                                                                                    echo '</div>';
                                                                                    echo '</div>';
                                                                                } else {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
															<div class="textarea_space"></div>
															<span classs="lblstandard">' . __('LIST NAME', 'ARForms') . ':</span>
															<div class="textarea_space"></div>
															<div class="sltstandard" style="float:none;">';
                                                                                    //echo '<select name="i_mailchimp_list" style="width:180px;" id="i_mailchimp_list" data-width="180px" '.( $setact != 1 ? "readonly=readonly" : '' ).'>';

                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $lists = @maybe_unserialize($res2[0]['responder_list_id']);
                                                                                    if (count($lists) > 0 && is_array($lists)) {
                                                                                        $cntr = 0;
                                                                                        foreach ($lists as $list) {
                                                                                            if ($mailchimp_arr['type_val'] == $list['id'] || $cntr == 0) {
                                                                                                $selected_list_id = $list['id'];
                                                                                                $selected_list_label = $list['name'];

                                                                                                //echo '<option selected="selected" value="'.$list['id'].'">'.$list['name'].'</option>';
                                                                                            }
                                                                                            //echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';	
                                                                                            $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $list['id'] . '" data-label="' . $list['name'] . '">' . $list['name'] . '</li>';
                                                                                            $cntr++;
                                                                                        }
                                                                                    }
                                                                                    //echo '</select>';

                                                                                    echo '<input id="i_mailchimp_list" name="i_mailchimp_list" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
														  <dl class="arf_selectbox" data-name="i_mailchimp_list" data-id="i_mailchimp_list" style="width:170px;">
															<dt><span>' . $selected_list_label . '</span>
															<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
															<i class="fa fa-caret-down fa-lg"></i></dt>
															<dd>
																<ul class="field_dropdown_menu" style="display: none;" data-id="i_mailchimp_list">
																	' . $responder_list_option . '
																</ul>
															</dd>
														  </dl>';

                                                                                    echo '</div>';
                                                                                    echo '</div>';
                                                                                }

                                                                                echo '</div>';
                                                                            } else if ($next == 'mailchimp' && $res['mailchimp_type'] == 0) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['mailchimp']) and $global_enable_ar['mailchimp'] == 0 and isset($mailchimp_arr['enable']) and $mailchimp_arr['enable'] == 0 )) {
                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
															<div class="textarea_space"></div>
															<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($res2[0]['responder_web_form']) . '</textarea>
														  </div>';
                                                                                } else {
                                                                                    $mailchimp_arr['type_val'] = isset($mailchimp_arr['type_val']) ? $mailchimp_arr['type_val'] : '';
                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
															<div class="textarea_space"></div>
															<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep(str_replace('/amp;/', '&', $mailchimp_arr['type_val'])) . '</textarea>
														  </div>';
                                                                                }
                                                                                echo '</div>';
                                                                            }
                                                                            ?>	
                                                                        </div>

                                                                    </div>

                                                                    <div id="arfem_aweber_div" class="arfemdiv" <?php
                                                                    if ($current_active_ar == 'aweber') {
                                                                        echo 'style="display:block;"';
                                                                    }
                                                                    ?>>

                                                                        <div class="arfemailcontrol">
                                                                            <?php if (($arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) and $res['aweber_type'] != 2) { ?>
                                                                                <input type="checkbox" class="chkstanard" style="width: 227px;height: 30px;margin-left: -5px;margin-top: -4px;" name="autoresponders[]" id="autores_3" value="3" checked="checked" onchange="show_setting('aweber', '3');" <?php
                                                                                if ($setact != 1) {
                                                                                    echo 'onclick="return false"';
                                                                                }
                                                                                ?>  /><label for="autores_3"><span class="ar_lbl_span"></span><?php _e('Aweber', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Aweber with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } else { ?>
                                                                                <input type="checkbox" class="chkstanard" style="width: 227px;height: 30px;margin-left: -5px;margin-top: -4px;" name="autoresponders[]" id="autores_3" value="3" <?php
                                                                                if (isset($aweber_arr['enable']) && $aweber_arr['enable'] == 1) {
                                                                                    echo "checked=checked";
                                                                                }
                                                                                ?> onchange="show_setting('aweber', '3');" <?php
                                                                                       if ($setact != 1) {
                                                                                           echo 'onclick="return false"';
                                                                                       }
                                                                                       ?>  /><label for="autores_3"><span class="ar_lbl_span"></span><?php _e('Aweber', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Aweber with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } ?>		
                                                                        </div>

                                                                        <div class="arfemailcontent">
                                                                            <?php
                                                                            // for aweber 
                                                                            $rand_num = rand(1111, 9999);
                                                                            $next = 'aweber';

                                                                            if ($next == 'aweber' and $res['aweber_type'] == 1) {

                                                                                $aweber = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_autoresponder WHERE responder_id = %d", 3));

                                                                                $aweber_data = $aweber[0];

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (($arfaction == 'new' || ($arfaction == 'duplicate' and $arf_template_id < 100)) || (isset($global_enable_ar['aweber']) and $global_enable_ar['aweber'] == 0 and isset($aweber_arr['enable']) and $aweber_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '"  class="autoresponder_inner_block">
													<div class="textarea_space"></div>
													<span classs="lblstandard">' . __('LIST NAME', 'ARForms') . ':</span> 
													<div class="textarea_space"></div>
													<div class="sltstandard" style="float:none;">';


                                                                                    $aweber_lists = explode("-|-", $aweber_data->responder_list_id);

                                                                                    $aweber_lists_name = explode("|", $aweber_lists[0]);

                                                                                    $aweber_lists_id = explode("|", $aweber_lists[1]);

                                                                                    $i = 0;


                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $cntr = 0;
                                                                                    if (count($aweber_lists_name) > 0 && is_array($aweber_lists_name)) {
                                                                                        foreach ($aweber_lists_name as $aweber_lists_name1) {

                                                                                            if ($aweber_lists_id[$i] != "") {

                                                                                                if ($aweber_lists_id[$i] == $aweber_data->responder_list || $cntr == 0) {
                                                                                                    $selected_list_id = $aweber_lists_id[$i];
                                                                                                    $selected_list_label = $aweber_lists_name1;
                                                                                                }
                                                                                                $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $aweber_lists_id[$i] . '" data-label="' . $aweber_lists_name1 . '">' . $aweber_lists_name1 . '</li>';
                                                                                                $cntr++;
                                                                                            }
                                                                                            $i++;
                                                                                        }
                                                                                    }

                                                                                    echo '<input id="i_aweber_list" name="i_aweber_list" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
														  <dl class="arf_selectbox" data-name="i_aweber_list" data-id="i_aweber_list" style="width:170px;">
															<dt><span>' . $selected_list_label . '</span>
															<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
															<i class="fa fa-caret-down fa-lg"></i></dt>
															<dd>
																<ul class="field_dropdown_menu" style="display: none;" data-id="i_aweber_list">
																	' . $responder_list_option . '
																</ul>
															</dd>
														  </dl>';

                                                                                    //echo '</select>';
                                                                                    echo '</div>
												  		  </div>';
                                                                                } else {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
													<div class="textarea_space"></div>
													<span classs="lblstandard">' . __('LIST NAME', 'ARForms') . ':</span> 
													<div class="textarea_space"></div>
													<div class="sltstandard" style="float:none;">';
                                                                                    //echo '<select name="i_aweber_list" id="i_aweber_list" style="width:180px;" data-width="180px" '.( $setact != 1 ? "readonly=readonly" : '' ).'>';

                                                                                    $aweber_lists = explode("-|-", $aweber_data->responder_list_id);

                                                                                    $aweber_lists_name = explode("|", $aweber_lists[0]);

                                                                                    $aweber_lists_id = explode("|", $aweber_lists[1]);

                                                                                    $i = 0;

                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $cntr = 0;
                                                                                    if (count($aweber_lists_name) > 0 && is_array($aweber_lists_name)) {
                                                                                        foreach ($aweber_lists_name as $aweber_lists_name1) {

                                                                                            if ($aweber_lists_id[$i] != "") {

                                                                                                if ($aweber_lists_id[$i] == $aweber_arr['type_val'] || $cntr == 0) {
                                                                                                    $selected_list_id = $aweber_lists_id[$i];
                                                                                                    $selected_list_label = $aweber_lists_name1;
                                                                                                }
                                                                                                $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $aweber_lists_id[$i] . '" data-label="' . $aweber_lists_name1 . '">' . $aweber_lists_name1 . '</li>';
                                                                                                $cntr++;
                                                                                            }
                                                                                            $i++;
                                                                                        }
                                                                                    }

                                                                                    echo '<input id="i_aweber_list" name="i_aweber_list" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
														  <dl class="arf_selectbox" data-name="i_aweber_list" data-id="i_aweber_list" style="width:170px;">
															<dt><span>' . $selected_list_label . '</span>
															<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
															<i class="fa fa-caret-down fa-lg"></i></dt>
															<dd>
																<ul class="field_dropdown_menu" style="display: none;" data-id="i_aweber_list">
																	' . $responder_list_option . '
																</ul>
															</dd>
														  </dl>';
                                                                                    //echo '</select>';
                                                                                    echo '</div>
												  		  </div>';
                                                                                }

                                                                                echo '</div>';
                                                                            } else if ($next == 'aweber' and $res['aweber_type'] == 0) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (($arfaction == 'new' || ($arfaction == 'duplicate' and $arf_template_id < 100)) || (isset($global_enable_ar['aweber']) and $global_enable_ar['aweber'] == 0 and isset($aweber_arr['enable']) and $aweber_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($res1[0]['responder_web_form']) . '</textarea>
													  </div>';
                                                                                } else {
                                                                                    $aweber_arr['type_val'] = isset($aweber_arr['type_val']) ? $aweber_arr['type_val'] : '';
                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($aweber_arr['type_val']) . '</textarea>
													  </div>';
                                                                                }

                                                                                echo '</div>';
                                                                            }


                                                                            // end of aweber
                                                                            ?>
                                                                        </div>	

                                                                    </div>


                                                                    <div id="arfem_icontact_div" class="arfemdiv" <?php
                                                                    if ($current_active_ar == 'icontact') {
                                                                        echo 'style="display:block;"';
                                                                    }
                                                                    ?>>

                                                                        <div class="arfemailcontrol">
                                                                            <?php
                                                                            if (($arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) and $res['icontact_type'] != 2) {
                                                                                $autochecked = '';
                                                                                if ($res['icontact_type'] == 1 and $res6[0]['is_verify'] == 1)
                                                                                    $autochecked = 'checked="checked"';
                                                                                else if ($res['icontact_type'] == 0 and trim($res6[0]['responder_web_form']) != '')
                                                                                    $autochecked = 'checked="checked"';
                                                                                ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_8" value="8" <?php echo $autochecked; ?> onchange="show_setting('icontact', '8');" <?php
                                                                                if ($setact != 1) {
                                                                                    echo 'onclick="return false"';
                                                                                }
                                                                                ?>  /><label for="autores_8"><span class="ar_lbl_span"></span><?php _e('Icontact', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Icontact with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } else { ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_8" value="8" <?php
                                                                                if (isset($icontact_arr['enable']) && $icontact_arr['enable'] == 1) {
                                                                                    echo "checked=checked";
                                                                                }
                                                                                ?> onchange="show_setting('icontact', '8');" <?php
                                                                                       if ($setact != 1) {
                                                                                           echo 'onclick="return false"';
                                                                                       }
                                                                                       ?>  /><label for="autores_8"><span class="ar_lbl_span"></span><?php _e('Icontact', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Icontact with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } ?>		
                                                                        </div>

                                                                        <div class="arfemailcontent">
                                                                            <?php
                                                                            $rand_num = rand(1111, 9999);
                                                                            $next = 'icontact';

                                                                            if ($next == 'icontact' && $res['icontact_type'] == 1) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['icontact']) and $global_enable_ar['icontact'] == 0 and isset($icontact_arr['enable']) and $icontact_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block" style="margin-top:0px;">
														<div class="textarea_space"></div>
														<span classs="lblstandard">' . __('LIST NAME', 'ARForms') . ':</span>
														<div class="textarea_space"></div>
														<div class="sltstandard" style="float:none;">';

                                                                                    //echo '<select name="i_icontact_list" style="width:180px;" id="i_icontact_list" data-width="180px" '.( $setact != 1 ? "readonly=readonly" : '' ).'>';
                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $cntr = 0;
                                                                                    $lists = @maybe_unserialize($res6[0]['responder_list_id']);
                                                                                    if (count($lists) > 0 && is_array($lists)) {

                                                                                        foreach ($lists as $list) {
                                                                                            if ($res6[0]['responder_list'] == $list->listId || $cntr == 0) {
                                                                                                $selected_list_id = $list->listId;
                                                                                                $selected_list_label = $list->name;
                                                                                            }
                                                                                            $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $list->listId . '" data-label="' . $list->name . '">' . $list->name . '</li>';
                                                                                            $cntr++;
                                                                                        }
                                                                                    }

                                                                                    echo '<input id="i_icontact_list" name="i_icontact_list" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
												  <dl class="arf_selectbox" data-name="i_icontact_list" data-id="i_icontact_list" style="width:170px;">
													<dt><span>' . $selected_list_label . '</span>
													<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
													<i class="fa fa-caret-down fa-lg"></i></dt>
													<dd>
														<ul class="field_dropdown_menu" style="display: none;" data-id="i_icontact_list">
															' . $responder_list_option . '
														</ul>
													</dd>
												  </dl>';

                                                                                    //echo '</select>';
                                                                                    echo '</div>';
                                                                                    echo '</div>';
                                                                                } else {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block" style="margin-top:0px;">
														<div class="textarea_space"></div>
														<span classs="lblstandard">' . __('LIST NAME', 'ARForms') . ':</span>
														<div class="textarea_space"></div>
														<div class="sltstandard" style="float:none;">';
                                                                                    //echo '<select name="i_icontact_list" style="width:180px;" id="i_icontact_list" data-width="180px" '.( $setact != 1 ? "readonly=readonly" : '' ).'>';	
                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $cntr = 0;
                                                                                    $lists = @maybe_unserialize($res6[0]['responder_list_id']);
                                                                                    if (count($lists) > 0 && is_array($lists)) {

                                                                                        foreach ($lists as $list) {
                                                                                            if ($icontact_arr['type_val'] == $list->listId || $cntr == 0) {
                                                                                                //echo '<option selected="selected" value="'.$list->listId.'">'.$list->name.'</option>';
                                                                                                $selected_list_id = $list->listId;
                                                                                                $selected_list_label = $list->name;
                                                                                            }
                                                                                            //echo '<option value="'.$list->listId.'">'.$list->name.'</option>';
                                                                                            $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $list->listId . '" data-label="' . $list->name . '">' . $list->name . '</li>';
                                                                                            $cntr++;
                                                                                        }
                                                                                    }
                                                                                    //echo '</select>';

                                                                                    echo '<input id="i_icontact_list" name="i_icontact_list" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
													  <dl class="arf_selectbox" data-name="i_icontact_list" data-id="i_icontact_list" style="width:170px;">
														<dt><span>' . $selected_list_label . '</span>
														<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
														<i class="fa fa-caret-down fa-lg"></i></dt>
														<dd>
															<ul class="field_dropdown_menu" style="display: none;" data-id="i_icontact_list">
																' . $responder_list_option . '
															</ul>
														</dd>
													  </dl>';

                                                                                    echo '</div>';
                                                                                    echo '</div>';
                                                                                }

                                                                                echo '</div>';
                                                                            } else if ($next == 'icontact' && $res['icontact_type'] == 0) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['icontact']) and $global_enable_ar['icontact'] == 0 and isset($icontact_arr['enable']) and $icontact_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block" style="margin-top:0px;">
														<div class="textarea_space"></div>
														<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($res6[0]['responder_web_form']) . '</textarea>
													  </div>';
                                                                                } else {
                                                                                    $icontact_arr['type_val'] = isset($icontact_arr['type_val']) ? $icontact_arr['type_val'] : '';
                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block" style="margin-top:0px;">
														<div class="textarea_space"></div>
														<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($icontact_arr['type_val']) . '</textarea>
													  </div>';
                                                                                }

                                                                                echo '</div>';
                                                                            }
                                                                            ?>
                                                                        </div>	

                                                                    </div>


                                                                    <div id="arfem_constant_contact_div" class="arfemdiv" <?php
                                                                    if ($current_active_ar == 'constant_contact') {
                                                                        echo 'style="display:block;"';
                                                                    }
                                                                    ?>>

                                                                        <div class="arfemailcontrol">
                                                                            <?php
                                                                            if (($arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) and $res['constant_type'] != 2) {
                                                                                $autochecked = '';
                                                                                if ($res['constant_type'] == 1 and $res7[0]['is_verify'] == 1) {
                                                                                    $autochecked = 'checked="checked"';
                                                                                } else if ($res['constant_type'] == 0 and trim($res7[0]['responder_web_form']) != '') {
                                                                                    $autochecked = 'checked="checked"';
                                                                                }
                                                                                ?>    
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_9" value="9" <?php echo $autochecked; ?> onchange="show_setting('constant', '9');" <?php
                                                                                if ($setact != 1) {
                                                                                    echo 'onclick="return false"';
                                                                                }
                                                                                ?>  /><label for="autores_9"><span class="ar_lbl_span"></span><?php _e('Constant Contact', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Constant Contact with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } else { ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_9" value="9" <?php
                                                                                if (isset($constant_contact_arr['enable']) and $constant_contact_arr['enable'] == 1) {
                                                                                    echo "checked=checked";
                                                                                }
                                                                                ?> onchange="show_setting('constant', '9');" <?php
                                                                                       if ($setact != 1) {
                                                                                           echo 'onclick="return false"';
                                                                                       }
                                                                                       ?>  /><label for="autores_9"><span class="ar_lbl_span"></span><?php _e('Constant Contact', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Constant Contact with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } ?>		
                                                                        </div>

                                                                        <div class="arfemailcontent">
                                                                            <?php
                                                                            $rand_num = rand(1111, 9999);
                                                                            $next = 'constant_contact';

                                                                            if ($next == 'constant_contact' && $res['constant_type'] == 1) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['constant_contact']) and $global_enable_ar['constant_contact'] == 0 and isset($constant_contact_arr['enable']) and $constant_contact_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<span classs="lblstandard">' . __('LIST NAME', 'ARForms') . ':</span>
														<div class="textarea_space"></div>
														<div class="sltstandard" style="float:none;">';
                                                                                    //echo '<select name="i_constant_contact_list" style="width:180px;" id="i_constant_contact_list" data-width="180px" '.( $setact != 1 ? "readonly=readonly" : '' ).'>';
                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $cntr = 0;
                                                                                    $lists_new = @maybe_unserialize($res7[0]['list_data']);

                                                                                    if (count($lists_new) > 0 && is_array($list_new)) {

                                                                                        foreach ($lists_new as $list) {
                                                                                            if ($res7[0]['responder_list'] == $list['id'] || $cntr == 0) {
                                                                                                //echo '<option selected="selected" value="'.$list['id'].'">'.$list['name'].'</option>';
                                                                                                $selected_list_id = $list['id'];
                                                                                                $selected_list_label = $list['name'];
                                                                                            }
                                                                                            //echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';	
                                                                                            $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $list['id'] . '" data-label="' . $list['name'] . '">' . $list['name'] . '</li>';
                                                                                            $cntr++;
                                                                                        }
                                                                                    }
                                                                                    //echo '</select>';

                                                                                    echo '<input id="i_constant_contact_list" name="i_constant_contact_list" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
													  <dl class="arf_selectbox" data-name="i_constant_contact_list" data-id="i_constant_contact_list" style="width:170px;">
														<dt><span>' . $selected_list_label . '</span>
														<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
														<i class="fa fa-caret-down fa-lg"></i></dt>
														<dd>
															<ul class="field_dropdown_menu" style="display: none;" data-id="i_constant_contact_list">
																' . $responder_list_option . '
															</ul>
														</dd>
													  </dl>';

                                                                                    echo '</div>';
                                                                                    echo '</div>';
                                                                                } else {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<span classs="lblstandard">' . __('LIST NAME', 'ARForms') . ':</span>
														<div class="textarea_space"></div>
														<div class="sltstandard" style="float:none;">';
                                                                                    //echo '<select name="i_constant_contact_list" style="width:180px;" id="i_constant_contact_list" data-width="180px" '.( $setact != 1 ? "readonly=readonly" : '' ).'>';
                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $cntr = 0;
                                                                                    $lists_new = @maybe_unserialize($res7[0]['list_data']);

                                                                                    if (count($lists_new) > 0 && is_array($list_new)) {

                                                                                        foreach ($lists_new as $list) {
                                                                                            if ($constant_contact_arr['type_val'] == $list['id']) {
                                                                                                $selected_list_id = $list['id'];
                                                                                                $selected_list_label = $list['name'];
                                                                                            }
                                                                                            //echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
                                                                                            $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $list['id'] . '" data-label="' . $list['name'] . '">' . $list['name'] . '</li>';
                                                                                            $cntr++;
                                                                                        }
                                                                                    }
                                                                                    //echo '</select>';

                                                                                    echo '<input id="i_constant_contact_list" name="i_constant_contact_list" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
													  <dl class="arf_selectbox" data-name="i_constant_contact_list" data-id="i_constant_contact_list" style="width:170px;">
														<dt><span>' . $selected_list_label . '</span>
														<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
														<i class="fa fa-caret-down fa-lg"></i></dt>
														<dd>
															<ul class="field_dropdown_menu" style="display: none;" data-id="i_constant_contact_list">
																' . $responder_list_option . '
															</ul>
														</dd>
													  </dl>';

                                                                                    echo '</div>';
                                                                                    echo '</div>';
                                                                                }

                                                                                echo '</div>';
                                                                            } else if ($next == 'constant_contact' && $res['constant_type'] == 0) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['constant_contact']) and $global_enable_ar['constant_contact'] == 0 and isset($constant_contact_arr['enable']) and $constant_contact_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($res7[0]['responder_web_form']) . '</textarea>
													  </div>';
                                                                                } else {
                                                                                    $constant_contact_arr['type_val'] = isset($constant_contact_arr['type_val']) ? $constant_contact_arr['type_val'] : '';
                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($constant_contact_arr['type_val']) . '</textarea>
													  </div>';
                                                                                }
                                                                                echo '</div>';
                                                                            }
                                                                            ?>
                                                                        </div>	

                                                                    </div>


                                                                    <div id="arfem_getresponse_div" class="arfemdiv" <?php
                                                                    if ($current_active_ar == 'getresponse') {
                                                                        echo 'style="display:block;"';
                                                                    }
                                                                    ?>>

                                                                        <div class="arfemailcontrol">
                                                                            <?php
                                                                            if (($arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) and $res['getresponse_type'] != 2) {
                                                                                $autochecked = '';
                                                                                if ($res['getresponse_type'] == 1 and $res3[0]['is_verify'] == 1) {
                                                                                    $autochecked = 'checked="checked"';
                                                                                } else if ($res['getresponse_type'] == 0 and trim($res3[0]['responder_web_form']) != '') {
                                                                                    $autochecked = 'checked="checked"';
                                                                                }
                                                                                ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_4" value="4" <?php echo $autochecked; ?> onchange="show_setting('getresponse', '4');" <?php
                                                                                if ($setact != 1) {
                                                                                    echo 'onclick="return false"';
                                                                                }
                                                                                ?>  /><label for="autores_4"><span class="ar_lbl_span"></span><?php _e('Getresponse', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Getresponse with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } else { ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_4" value="4" <?php
                                                                                if (isset($getresponse_arr['enable']) and $getresponse_arr['enable'] == 1) {
                                                                                    echo "checked=checked";
                                                                                }
                                                                                ?> onchange="show_setting('getresponse', '4');" <?php
                                                                                       if ($setact != 1) {
                                                                                           echo 'onclick="return false"';
                                                                                       }
                                                                                       ?>  /><label for="autores_4"><span class="ar_lbl_span"></span><?php _e('Getresponse', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure Getresponse with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } ?>		
                                                                        </div>

                                                                        <div class="arfemailcontent">
                                                                            <?php
                                                                            $rand_num = rand(1111, 9999);
                                                                            $next = 'getresponse';

                                                                            if ($next == 'getresponse' && $res['getresponse_type'] == 1) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['getresponse']) and $global_enable_ar['getresponse'] == 0 and isset($getresponse_arr['enable']) and $getresponse_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<span classs="lblstandard">CAMPAIGN NAME:</span>
														<div class="textarea_space"></div>
														<div class="sltstandard" style="float:none;">';
                                                                                    //echo '<select name="i_campain_name" style="width:180px;" id="i_campain_name" data-width="180px" '.( $setact != 1 ? "readonly=readonly" : '' ).'>';
                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $cntr = 0;
                                                                                    $lists = @maybe_unserialize($res3[0]['list_data']);
                                                                                    if (count($lists) > 0 && is_array($lists)) {

                                                                                        foreach ($lists as $listid => $list) {
                                                                                            if ($res3[0]['responder_list_id'] == $list['name']) {
                                                                                                //echo '<option selected="selected" value="'.$list['name'].'">'.$list['name'].'</option>';
                                                                                                $selected_list_id = $list['name'];
                                                                                                $selected_list_label = $list['name'];
                                                                                            }
                                                                                            //echo '<option value="'.$list['name'].'">'.$list['name'].'</option>';
                                                                                            $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $list['name'] . '" data-label="' . $list['name'] . '">' . $list['name'] . '</li>';
                                                                                            $cntr++;
                                                                                        }
                                                                                    }
                                                                                    //echo '</select>';

                                                                                    echo '<input id="i_campain_name" name="i_campain_name" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
													  <dl class="arf_selectbox" data-name="i_campain_name" data-id="i_campain_name" style="width:170px;">
														<dt><span>' . $selected_list_label . '</span>
														<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
														<i class="fa fa-caret-down fa-lg"></i></dt>
														<dd>
															<ul class="field_dropdown_menu" style="display: none;" data-id="i_campain_name">
																' . $responder_list_option . '
															</ul>
														</dd>
													  </dl>';

                                                                                    echo '</div>';
                                                                                    echo '</div>';
                                                                                } else {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<span classs="lblstandard">CAMPAIGN NAME:</span>
														<div class="textarea_space"></div>
														<div class="sltstandard" style="float:none;">';
                                                                                    //echo '<select name="i_campain_name" style="width:180px;" id="i_campain_name" data-width="180px" '.( $setact != 1 ? "readonly=readonly" : '' ).'>';
                                                                                    $selected_list_id = "";
                                                                                    $selected_list_label = "";
                                                                                    $responder_list_option = "";
                                                                                    $cntr = 0;
                                                                                    $lists = @maybe_unserialize($res3[0]['list_data']);
                                                                                    if (count($lists) > 0 && is_array($lists)) {

                                                                                        foreach ($lists as $listid => $list) {
                                                                                            if ($getresponse_arr['type_val'] == $list['name']) {
                                                                                                //echo '<option selected="selected" value="'.$list['name'].'">'.$list['name'].'</option>';
                                                                                                $selected_list_id = $list['name'];
                                                                                                $selected_list_label = $list['name'];
                                                                                            }
                                                                                            //echo '<option value="'.$list['name'].'">'.$list['name'].'</option>';
                                                                                            $responder_list_option .= '<li class="arf_selectbox_option" data-value="' . $list['name'] . '" data-label="' . $list['name'] . '">' . $list['name'] . '</li>';
                                                                                            $cntr++;
                                                                                        }
                                                                                    }
                                                                                    //echo '</select>';

                                                                                    echo '<input id="i_campain_name" name="i_campain_name" value="' . $selected_list_id . '" type="hidden" class="frm-dropdown frm-pages-dropdown" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>
													  <dl class="arf_selectbox" data-name="i_campain_name" data-id="i_campain_name" style="width:170px;">
														<dt><span>' . $selected_list_label . '</span>
														<input value="' . $selected_list_label . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
														<i class="fa fa-caret-down fa-lg"></i></dt>
														<dd>
															<ul class="field_dropdown_menu" style="display: none;" data-id="i_campain_name">
																' . $responder_list_option . '
															</ul>
														</dd>
													  </dl>';

                                                                                    echo '</div>';
                                                                                    echo '</div>';
                                                                                }
                                                                                echo '</div>';
                                                                            } else if ($next == 'getresponse' && $res['getresponse_type'] == 0) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['getresponse']) and $global_enable_ar['getresponse'] == 0 and isset($getresponse_arr['enable']) and $getresponse_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($res3[0]['responder_web_form']) . '</textarea>
													  </div>';
                                                                                } else {
                                                                                    $getresponse_arr['type_val'] = isset($getresponse_arr['type_val']) ? $getresponse_arr['type_val'] : '';
                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
														<div class="textarea_space"></div>
														<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($getresponse_arr['type_val']) . '</textarea>
													  </div>';
                                                                                }
                                                                                echo '</div>';
                                                                            }
                                                                            ?>
                                                                        </div>	

                                                                    </div>


                                                                    <div id="arfem_gvo_div" class="arfemdiv" <?php
                                                                    if ($current_active_ar == 'gvo') {
                                                                        echo 'style="display:block;"';
                                                                    }
                                                                    ?>>

                                                                        <div class="arfemailcontrol">
                                                                            <?php
                                                                            if (($arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) and $res['gvo_type'] != 2) {
                                                                                $autochecked = '';
                                                                                if ($res['gvo_type'] == 0 and trim($res4[0]['responder_web_form']) != '')
                                                                                    $autochecked = 'checked="checked"';
                                                                                ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_5" value="5" <?php echo $autochecked; ?> onchange="show_setting('gvo', '5');" <?php
                                                                                if ($setact != 1) {
                                                                                    echo 'onclick="return false"';
                                                                                }
                                                                                ?>  /><label for="autores_5"><span class="ar_lbl_span"></span><?php _e('GVO', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure GVO with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } else { ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_5" value="5" <?php
                                                                                if ($res['gvo_type'] == 2) {
                                                                                    echo 'disabled="disabled"';
                                                                                }
                                                                                ?> <?php
                                                                                if (isset($gvo_arr['enable']) and $gvo_arr['enable'] == 1) {
                                                                                    echo "checked=checked";
                                                                                }
                                                                                ?> onchange="show_setting('gvo', '5');" <?php
                                                                                       if ($setact != 1) {
                                                                                           echo 'onclick="return false"';
                                                                                       }
                                                                                       ?>  /><label for="autores_5"><span class="ar_lbl_span"></span><?php _e('GVO', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure GVO with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } ?>		
                                                                        </div>

                                                                        <div class="arfemailcontent">
                                                                            <?php
                                                                            $rand_num = rand(1111, 9999);
                                                                            $next = 'gvo';
                                                                            if ($next == 'gvo' && $res['gvo_type'] == 0) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['gvo']) and $global_enable_ar['gvo'] == 0 and isset($gvo_arr['enable']) and $gvo_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
													<div class="textarea_space"></div>
													<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($res4[0]['responder_api_key']) . '</textarea>
												  </div>';
                                                                                } else {
                                                                                    $gvo_arr['type_val'] = isset($gvo_arr['type_val']) ? $gvo_arr['type_val'] : '';
                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
													<div class="textarea_space"></div>
													<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($gvo_arr['type_val']) . '</textarea>
												  </div>';
                                                                                }
                                                                                echo '</div>';
                                                                            }
                                                                            ?>
                                                                        </div>	

                                                                    </div>


                                                                    <div id="arfem_ebizac_div" class="arfemdiv" <?php
                                                                    if ($current_active_ar == 'ebizac') {
                                                                        echo 'style="display:block;"';
                                                                    }
                                                                    ?>>

                                                                        <div class="arfemailcontrol">
                                                                            <?php
                                                                            if (($arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) and $res['ebizac_type'] != 2) {
                                                                                $autochecked = '';
                                                                                if ($res['ebizac_type'] == 0 and trim($res5[0]['responder_web_form']) != '') {
                                                                                    $autochecked = 'checked="checked"';
                                                                                }
                                                                                ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_6" value="6" <?php echo $autochecked; ?> onchange="show_setting('ebizac', '6');" <?php
                                                                                if ($setact != 1) {
                                                                                    echo 'onclick="return false"';
                                                                                }
                                                                                ?>  /><label for="autores_6"><span class="ar_lbl_span"></span><?php _e('eBizac', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure eBizac with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } else { ?>
                                                                                <input type="checkbox" class="chkstanard" name="autoresponders[]" id="autores_6" value="6" <?php
                                                                                if ($res['ebizac_type'] == 2) {
                                                                                    echo 'disabled="disabled"';
                                                                                }
                                                                                ?> <?php
                                                                                if (isset($ebizac_arr['enable']) and $ebizac_arr['enable'] == 1) {
                                                                                    echo "checked=checked";
                                                                                }
                                                                                ?> onchange="show_setting('ebizac', '6');" <?php
                                                                                       if ($setact != 1) {
                                                                                           echo 'onclick="return false"';
                                                                                       }
                                                                                       ?>  /><label for="autores_6"><span class="ar_lbl_span"></span><?php _e('eBizac', 'ARForms'); ?><font style="color:#FF0000; font-size:13px;"><?php
                                                                                       if ($setact != 1) {
                                                                                           _e('&nbsp;&nbsp;&nbsp;(Please Activate your license to configure eBizac with this form.)', 'ARForms');
                                                                                       }
                                                                                       ?></font></label>
                                                                            <?php } ?>		
                                                                        </div>

                                                                        <div class="arfemailcontent">
                                                                            <?php
                                                                            $rand_num = rand(1111, 9999);
                                                                            $next = 'ebizac';
                                                                            if ($next == 'ebizac' && $res['ebizac_type'] == 0) {

                                                                                echo '<div id="select-autores_' . $rand_num . '" class="select_autores">';

                                                                                if (( $arfaction == 'new' || ( $arfaction == 'duplicate' and $arf_template_id < 100 ) ) || (isset($global_enable_ar['ebizac']) and $global_enable_ar['ebizac'] == 0 and isset($ebizac_arr['enable']) and $ebizac_arr['enable'] == 0 )) {

                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
													<div class="textarea_space"></div>
													<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($res5[0]['responder_api_key']) . '</textarea>
												  </div>';
                                                                                } else {
                                                                                    $ebizac_arr['type_val'] = isset($ebizac_arr['type_val']) ? $ebizac_arr['type_val'] : '';
                                                                                    echo '<div id="autores-' . $next . '" class="autoresponder_inner_block">
													<div class="textarea_space"></div>
													<textarea class="txtmultinew" name="web_form_' . $next . '" id="web_form_' . $next . '" style="width:100%; height:100px;" ' . ( $setact != 1 ? "readonly=readonly" : '' ) . '>' . stripslashes_deep($ebizac_arr['type_val']) . '</textarea>
												  </div>';
                                                                                }
                                                                                echo '</div>';
                                                                            }
                                                                            ?>
                                                                        </div>

                                                                    </div>

                                                                </div>	

                                                            </div>

                                                            <div style="height:30px; clear:both;"></div>                            

                                                            <div class="arftablerow" style="display: table; width: 100%;">
                                                                <div class="arfcolumnleft" style="display:table-cell; width:50%;">

                                                                    <div class="arftablerow" style="display: table; width: 100%;">
                                                                        <div class="arfcolumnleft arfsettingsubtitle" style="display:table-row;">
                                                                            <div class="arfcolumnleft" style="display:table-cell;"><?php _e('First name mapping', 'ARForms'); ?></div>
                                                                            <div class="arfcolumnright" style="display:table-cell;"><?php _e('Last name mapping', 'ARForms'); ?></div>
                                                                        </div>
                                                                        <div class="arfcolumnright" style="display:table-row">

                                                                            <div class="arfcolumnleft" style="display:table-cell; width:160px">

                                                                                <span id="frm_responder_first_name">
                                                                                    <div class="sltstandard" style="float:none;">

                                                                                        <?php
                                                                                        $selectbox_field_options = "";
                                                                                        $selectbox_field_value_label = "";
                                                                                        if (isset($values['fields']) and count($values['fields']) > 0) {
                                                                                            foreach ($values['fields'] as $field1) {
                                                                                                if ($field1['type'] != 'divider' && $field1['type'] != 'break' && $field1['type'] != 'captcha' && $field1['type'] != 'html') {

                                                                                                    if (($field1["id"] == $responder_fname) || ($field1["ref_field_id"] == $responder_fname)) {
                                                                                                        $selectbox_field_value_label = $field1["name"];
                                                                                                    }

                                                                                                    $current_field_id = ($field1["ref_field_id"] > 0 ) ? $field1["ref_field_id"] : $field1["id"];
                                                                                                    $selectbox_field_options .= '<li class="arf_selectbox_option" data-value="' . $current_field_id . '" data-label="' . $field1["name"] . '">' . $field1["name"] . '</li>';
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                        $values["ref_field_id"] = isset($values["ref_field_id"]) ? $values["ref_field_id"] : '';
                                                                                        ?>

                                                                                        <input id="autoresponder_fname" name="autoresponder_fname" value="<?php echo $responder_fname; ?>" type="hidden" <?php
                                                                                        if ($setact != 1) {
                                                                                            echo "readonly=readonly";
                                                                                        }
                                                                                        ?>>
                                                                                        <dl class="arf_selectbox" data-name="autoresponder_fname" data-id="autoresponder_fname" style="width:140px;">
                                                                                            <dt><span><?php
                                                                                                if ($selectbox_field_value_label != "") {
                                                                                                    echo $selectbox_field_value_label;
                                                                                                } else {
                                                                                                    echo __('Select First Name', 'ARForms');
                                                                                                }
                                                                                                ?></span>
                                                                                            <input value="<?php
                                                                                            if ($values["id"] == $responder_fname) {
                                                                                                echo $values["id"];
                                                                                            } else if ($values["ref_field_id"] == $responder_fname) {
                                                                                                echo $values["ref_field_id"];
                                                                                            }
                                                                                            ?>" style="display:none;width:128px;" class="arf_autocomplete" type="text">
                                                                                            <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                                            <dd>
                                                                                                <ul class="arf_name_field_dropdown" style="display: none;" data-id="autoresponder_fname">
                                                                                                    <li class="arf_selectbox_option" data-value="" data-label="<?php _e('Select First Name', 'ARForms'); ?>"><?php _e('Select First Name', 'ARForms'); ?></li>

                                                                                                    <?php echo $selectbox_field_options; ?>

                                                                                                </ul>
                                                                                            </dd>
                                                                                        </dl>

                                                                                    </div>
                                                                                </span>

                                                                            </div>
                                                                            <div class="arfcolumnright" style="display:table-cell; width:160px">

                                                                                <span id="frm_responder_last_name">          
                                                                                    <div class="sltstandard" style="float:none;">

                                                                                        <?php
                                                                                        $selectbox_field_options = "";
                                                                                        $selectbox_field_value_label = "";
                                                                                        if (isset($values['fields']) and count($values['fields']) > 0) {
                                                                                            foreach ($values['fields'] as $field1) {
                                                                                                if ($field1['type'] != 'divider' && $field1['type'] != 'break' && $field1['type'] != 'captcha' && $field1['type'] != 'html') {

                                                                                                    if (($field1["id"] == $responder_lname) || ($field1["ref_field_id"] == $responder_lname)) {
                                                                                                        $selectbox_field_value_label = $field1["name"];
                                                                                                    }

                                                                                                    $current_field_id = ($field1["ref_field_id"] > 0 ) ? $field1["ref_field_id"] : $field1["id"];
                                                                                                    $selectbox_field_options .= '<li class="arf_selectbox_option" data-value="' . $current_field_id . '" data-label="' . $field1["name"] . '">' . $field1["name"] . '</li>';
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                        <input id="autoresponder_lname" name="autoresponder_lname" value="<?php echo $responder_lname; ?>" type="hidden" <?php
                                                                                        if ($setact != 1) {
                                                                                            echo "readonly=readonly";
                                                                                        }
                                                                                        ?>>
                                                                                        <dl class="arf_selectbox" data-name="autoresponder_lname" data-id="autoresponder_lname" style="width:140px;">
                                                                                            <dt><span><?php
                                                                                                if ($selectbox_field_value_label != "") {
                                                                                                    echo $selectbox_field_value_label;
                                                                                                } else {
                                                                                                    echo __('Select Last Name', 'ARForms');
                                                                                                }
                                                                                                ?></span>
                                                                                            <input value="<?php
                                                                                            if ($values["id"] == $responder_lname) {
                                                                                                echo $values["id"];
                                                                                            } else if ($values["ref_field_id"] == $responder_lname) {
                                                                                                echo $values["ref_field_id"];
                                                                                            }
                                                                                            ?>" style="display:none;width:128px;" class="arf_autocomplete" type="text">
                                                                                            <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                                            <dd>
                                                                                                <ul class="arf_name_field_dropdown" style="display: none;" data-id="autoresponder_lname">
                                                                                                    <li class="arf_selectbox_option" data-value="" data-label="<?php _e('Select Last Name', 'ARForms'); ?>"><?php _e('Select Last Name', 'ARForms'); ?></li>

                                                                                                    <?php echo $selectbox_field_options; ?>

                                                                                                </ul>
                                                                                            </dd>
                                                                                        </dl>


                                                                                    </div>                                            
                                                                                </span>

                                                                            </div>

                                                                        </div>
                                                                    </div>    	

                                                                </div>
                                                                <div class="arfcolumnright" style="display:table-cell; width:50%;">

                                                                    <div class="arftablerow" style="display:table-cell;">
                                                                        <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Email field mapping', 'ARForms'); ?></div>
                                                                        <div class="arfcolumnright">

                                                                            <span id="frm_responder_email">
                                                                                <div class="sltstandard" style="float:none;">

                                                                                    <?php
                                                                                    $selectbox_field_options = "";
                                                                                    $selectbox_field_value_label = "";
                                                                                    if (isset($values['fields']) and count($values['fields']) > 0) {
                                                                                        foreach ($values['fields'] as $field1) {
                                                                                            if (in_array($field1['type'], array('email', 'text'))) {
                                                                                                if (($field1["id"] == $responder_email) || ($field1["ref_field_id"] == $responder_email)) {
                                                                                                    $selectbox_field_value_label = $field1["name"];
                                                                                                }

                                                                                                $current_field_id = ($field1["ref_field_id"] > 0 ) ? $field1["ref_field_id"] : $field1["id"];
                                                                                                $selectbox_field_options .= '<li class="arf_selectbox_option" data-value="' . $current_field_id . '" data-label="' . $field1["name"] . '">' . $field1["name"] . '</li>';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    ?>

                                                                                    <input id="autoresponder_email" name="autoresponder_email" value="<?php echo $responder_email; ?>" type="hidden" <?php
                                                                                    if ($setact != 1) {
                                                                                        echo "readonly=readonly";
                                                                                    }
                                                                                    ?>>
                                                                                    <dl class="arf_selectbox" data-name="autoresponder_email" data-id="autoresponder_email" style="width:140px;">
                                                                                        <dt><span><?php
                                                                                            if ($selectbox_field_value_label != "") {
                                                                                                echo $selectbox_field_value_label;
                                                                                            } else {
                                                                                                echo __('Select Field', 'ARForms');
                                                                                            }
                                                                                            ?></span>
                                                                                        <input value="<?php
                                                                                        if ($values["id"] == $responder_email) {
                                                                                            echo $values["id"];
                                                                                        } else if ($values["ref_field_id"] == $responder_email) {
                                                                                            echo $values["ref_field_id"];
                                                                                        }
                                                                                        ?>" style="display:none;width:128px;" class="arf_autocomplete" type="text">
                                                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                                        <dd>
                                                                                            <ul class="arf_email_field_dropdown" style="display: none;" data-id="autoresponder_email">
                                                                                                <li class="arf_selectbox_option" data-value="" data-label="<?php _e('Select Field', 'ARForms'); ?>"><?php _e('Select Field', 'ARForms'); ?></li>

                                                                                                <?php echo $selectbox_field_options; ?>

                                                                                            </ul>
                                                                                        </dd>
                                                                                    </dl>

                                                                                </div>                                        
                                                                            </span>                                              
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <?php do_action('arf_additional_emailmarketers_settings', $id, $is_ref_form, $values); ?>
                                                        </div>                        
                                                    </div>

                                                    <?php do_action('arf_after_emailmarketers_settings_container', $id, $is_ref_form, $values); ?>

                                                    <div id="arf_customcss" class="arfsettingsubcontainer" style="border:none;">

                                                        <div class="arfformtable">

                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft arfsettingtitle"><?php _e('Custom CSS', 'ARForms'); ?></div><div class="howto"><?php _e('( Click on cloud buttons given below to copy your relevant css properties. Apply properties directly. Do not define class )', 'ARForms'); ?></div>
                                                                <div class="arfcolumnright"></div>
                                                            </div>
                                                            <div style="height:20px;"></div>
                                                            <?php
                                                            $customcss_array = array();
                                                            $custom_css_array = array(
                                                                'arf_form_outer_wrapper' => __('Form outer wrapper', 'ARForms'),
                                                                'arf_form_inner_wrapper' => __('Form inner wrapper', 'ARForms'),
                                                                'arf_form_title' => __('Form title', 'ARForms'),
                                                                'arf_form_description' => __('Form description', 'ARForms'),
                                                                'arf_form_element_wrapper' => __('Field wrapper', 'ARForms'),
                                                                'arf_form_element_label' => __('Field label', 'ARForms'),
                                                                'arf_form_elements' => __('Input elements', 'ARForms'),
                                                                'arf_submit_outer_wrapper' => __('Submit wrapper', 'ARForms'),
                                                                'arf_form_submit_button' => __('Submit button', 'ARForms'),
                                                                'arf_form_next_button' => __('Next button', 'ARForms'),
                                                                'arf_form_previous_button' => __('Previous button', 'ARForms'),
                                                                'arf_form_success_message' => __('Success message', 'ARForms'),
                                                                'arf_form_error_message' => __('Validation (Error)', 'ARForms'),
                                                                'arf_form_page_break' => __('Page break', 'ARForms'),
                                                                'arf_form_fly_sticky' => __('Fly / Sticky Button', 'ARForms'),
                                                                'arf_form_modal_css' => __('Modal', 'ARForms'),
                                                                'arf_form_link_css' => __('Link (Popup)', 'ARForms'),
                                                                'arf_form_button_css' => __('Button (Popup)', 'ARForms'),
                                                                'arf_form_link_hover_css' => __('Link Hover (Popup)', 'ARForms'),
                                                                'arf_form_button_hover_css' => __('Button Hover (Popup)', 'ARForms'),
                                                            );
                                                            foreach ($custom_css_array as $custom_css_block => $custom_css_block_title) {
                                                                if (isset($values[$custom_css_block]) and $values[$custom_css_block] != '') {
                                                                    $customcss_array[$custom_css_block] = true;
                                                                } else {
                                                                    $customcss_array[$custom_css_block] = false;
                                                                }
                                                            }
                                                            ?>
                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Custom Styling Properties', 'ARForms'); ?></div>

                                                                <div class="arfcolumnright">
                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_outer_wrapper']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_outer_wrapper_btn" onclick="add_custom_css_block('arf_form_outer_wrapper', '<?php echo addslashes(__('Form outer wrapper', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Form outer wrapper', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_inner_wrapper']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_inner_wrapper_btn" onclick="add_custom_css_block('arf_form_inner_wrapper', '<?php echo addslashes(__('Form inner wrapper', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Form inner wrapper', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_title']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_title_btn" onclick="add_custom_css_block('arf_form_title', '<?php echo addslashes(__('Form title', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Form title', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_description']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_description_btn" onclick="add_custom_css_block('arf_form_description', '<?php echo addslashes(__('Form description', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Form description', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_element_wrapper']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_element_wrapper_btn" onclick="add_custom_css_block('arf_form_element_wrapper', '<?php echo addslashes(__('Field wrapper', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Field wrapper', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_element_label']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_element_label_btn" onclick="add_custom_css_block('arf_form_element_label', '<?php echo addslashes(__('Field label', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Field label', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_elements']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_elements_btn" onclick="add_custom_css_block('arf_form_elements', '<?php echo addslashes(__('Input elements', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Input elements', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_page_break']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_page_break_btn" onclick="add_custom_css_block('arf_form_page_break', '<?php echo addslashes(__('Page break', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Page break', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_submit_outer_wrapper']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_submit_outer_wrapper_btn" onclick="add_custom_css_block('arf_submit_outer_wrapper', '<?php echo addslashes(__('Submit wrapper', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Submit wrapper', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_submit_button']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_submit_button_btn" onclick="add_custom_css_block('arf_form_submit_button', '<?php echo addslashes(__('Submit button', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Submit button', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_next_button']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_next_button_btn" onclick="add_custom_css_block('arf_form_next_button', '<?php echo addslashes(__('Next button', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Next button', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_previous_button']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_previous_button_btn" onclick="add_custom_css_block('arf_form_previous_button', '<?php echo addslashes(__('Previous button', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Previous button', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_success_message']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_success_message_btn" onclick="add_custom_css_block('arf_form_success_message', '<?php echo addslashes(__('Success message', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Success message', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_error_message']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_error_message_btn" onclick="add_custom_css_block('arf_form_error_message', '<?php echo addslashes(__('Validation (Error)', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Validation (Error)', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_fly_sticky']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_fly_sticky_btn" onclick="add_custom_css_block('arf_form_fly_sticky', '<?php echo addslashes(__('Fly / Sticky Button', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Fly / Sticky Button', 'ARForms'); ?></button>&nbsp;&nbsp;

                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_modal_css']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_modal_css_btn" onclick="add_custom_css_block('arf_form_modal_css', '<?php echo addslashes(__('Modal', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Modal', 'ARForms'); ?></button>&nbsp;&nbsp;
                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_link_css']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_link_css_btn" onclick="add_custom_css_block('arf_form_link_css', '<?php echo addslashes(__('Link (Popup)', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Link (Popup)', 'ARForms'); ?></button>&nbsp;&nbsp;
                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_link_hover_css']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_link_hover_css_btn" onclick="add_custom_css_block('arf_form_link_hover_css', '<?php echo addslashes(__('Link Hover (Popup)', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Link Hover (Popup)', 'ARForms'); ?></button>&nbsp;&nbsp;
                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_button_css']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_button_css_btn" onclick="add_custom_css_block('arf_form_button_css', '<?php echo addslashes(__('Button (Popup)', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Button (Popup)', 'ARForms'); ?></button>&nbsp;&nbsp;  
                                                                    <button class="arfcustomcssbtn <?php
                                                                    if ($customcss_array['arf_form_button_hover_css']) {
                                                                        echo 'arfactive';
                                                                    }
                                                                    ?>" id="arf_form_button_hover_css_btn" onclick="add_custom_css_block('arf_form_button_hover_css', '<?php echo addslashes(__('Button Hover (Popup)', 'ARForms')); ?>', '<?php echo addslashes(__('Remove', 'ARForms')); ?>');" type="button"><?php _e('Button Hover (Popup)', 'ARForms'); ?></button>&nbsp;&nbsp;  
                                                                </div>
                                                            </div>
                                                            <div style="height:30px;"></div>
                                                            <div class="arftablerow">
                                                                <div class="arfcolumnleft"></div>
                                                                <div class="arfcolumnright">
                                                                    <?php
                                                                    if (is_rtl()) {
                                                                        $arf_custom_css_block_style = 'float:right;width:auto;';
                                                                    } else {
                                                                        $arf_custom_css_block_style = 'float:left;width:auto;';
                                                                    }
                                                                    ?>
                                                                    <div id="arf_custom_css_block" style=" <?php echo $arf_custom_css_block_style; ?>">
                                                                        <?php
                                                                        foreach ($custom_css_array as $custom_css_block => $custom_css_block_title) {
                                                                            if (isset($values[$custom_css_block]) and $values[$custom_css_block] != '') {

                                                                                echo '<div id="' . $custom_css_block . '" class="arf_form_custom_css_block">';
                                                                                echo '<div class="arf_form_css_tab"><div class="arf_form_custom_css_block_title">' . $custom_css_block_title . '</div></div>';
                                                                                echo '<div class="arf_form_custom_css_block_style"><textarea name="options[' . $custom_css_block . ']" style="width:430px !important;" cols="50" rows="4" class="arfplacelonginput txtmultinew">' . stripslashes_deep($armainhelper->esc_textarea($arformcontroller->br2nl($values[$custom_css_block]))) . '</textarea></div>';
                                                                                echo '<div class="arfcustomcssclose" onclick="arf_remove_css_block(\'' . $custom_css_block . '\');"></div><br/><div class="lblsubtitle" style="float:left; clear:both;">e.g. display:block;</div></div>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <div style="clear:both"></div>
                                                                </div>
                                                            </div>

                                                            <div class="arfsettingspacer" style="height:35px;"></div>

                                                            <div class="arftablerow arf_form_other_css_wrapper">
                                                                <div class="arfcolumnleft arfsettingsubtitle"><?php _e('Other CSS', 'ARForms'); ?></div>
                                                                <div class="arfcolumnright"><textarea id="arf_form_other_css" name="options[arf_form_other_css]" style="width:430px !important;" cols="50" rows="4" class="arfplacelonginput txtmultinew"><?php
                                                                        $values['arf_form_other_css'] = isset($values['arf_form_other_css']) ? $values['arf_form_other_css'] : '';
                                                                        echo $armainhelper->esc_textarea($arformcontroller->br2nl($values['arf_form_other_css']));
                                                                        ?></textarea>
                                                                    <br/><span class="lblsubtitle" style="margin-top:5px; float:left;" ><?php _e('You can enter classes not directly properties.', 'ARForms'); ?> (e.g. #active { color:#ff0000; })</span>
                                                                </div>
                                                            </div>

                                                            <?php do_action('arf_additional_customcss_settings', $id, $is_ref_form, $values); ?>

                                                        </div>

                                                    </div>

                                                    <?php do_action('arfafterformsetting', $id, $is_ref_form, $values); ?>

                                                    <div style="clear:both;"></div>        
                                                </div>
                                                <div style="clear:both;"></div>
                                            </div>
                                            <!-- form settings end -->

                                            <!-- add to site -->
                                            <div id="arfaddtosite" style="display:none;">
                                                <div class="arfaddtosite_container">

                                                    <div class="arfaddtosite_content">

                                                        <div class="arfaddtosite_save_form">

                                                            <div id="arf_addtosite_message">

                                                            </div>

                                                            <div class="add_to_site_title"><?php _e('Have you saved your form already ?', 'ARForms'); ?></div>
                                                            <div style="clear:both; margin:30px 0;"> <button type="button" id="arfaddtosubmit" onclick="arfmainformedit(1);" class="greensavebtn" style="width:103px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/save-icon.png">&nbsp;&nbsp;<?php _e('Save', 'ARForms') ?></button> </div>
                                                            <div class="add_to_site_subtitle"><?php _e('You should save your latest changes before publishing form to the page.', 'ARForms'); ?></div>                           
                                                        </div>

                                                        <div class="arfaddtosite_way_publish" style="text-align:center;">

                                                            <div class="arf_way_publish_title" style="text-align:center;"><?php _e('Ways to publish your form', 'ARForms'); ?></div>

                                                            <div class="arf_way_publish_subtitle" style="text-align:center;"><?php _e('Follow these steps to add the form into page:', 'ARForms'); ?></div>

                                                            <div style="margin-top:35px; text-align:center;"><img src="<?php echo ARFIMAGESURL . '/step1.png' ?>" /></div>

                                                            <div class="arf_linktype_title" style="text-align:center;"><?php _e('Selecting', 'ARForms'); ?> <span class="arf_linktype_subtitle"><?php _e('LINK TYPE', 'ARForms'); ?></span> <?php _e('in the popup box:', 'ARForms'); ?></div>

                                                            <div style="margin-top:35px; text-align:center;"><img src="<?php echo ARFIMAGESURL . '/step2.png' ?>" /></div>

                                                            <div class="arf_way_publish_title" style="text-align:center;"><?php _e('Insert form into widget:', 'ARForms'); ?></div>

                                                            <div style="margin-top:35px; text-align:center;"><img src="<?php echo ARFIMAGESURL . '/step3.png' ?>" /></div>

                                                        </div>


                                                        <div style="clear:both;"></div>        	                             	
                                                    </div>

                                                    <div style="clear:both;"></div>
                                                </div>
                                                <div class="arfaddtosite_sidebar">
                                                    <?php
                                                    if (is_rtl()) {
                                                        $normal_view_style = 'direction:ltr;text-align:right;';
                                                        $popup_view_style = 'direction:ltr;text-align:right;';
                                                        $fly_view_style = 'direction:ltr;text-align:right;';
                                                        $sticky_view_style = 'direction:ltr;text-align:right;';
                                                        $normal_view_style_php = 'direction:ltr;text-align:right;width:100%;';
                                                        $popup_view_style_php = 'direction:ltr;text-align:right;width:100%;word-wrap:break-word;';
                                                    } else {
                                                        $normal_view_style = '';
                                                        $popup_view_style = '';
                                                        $fly_view_style = '';
                                                        $sticky_view_style = '';
                                                        $normal_view_style_php = 'width:100%;';
                                                        $popup_view_style_php = 'width:100%;word-wrap:break-word;';
                                                    }
                                                    ?>
                                                    <div class="arfaddtosite_sidebartitle"><?php _e('SHORTCODES', 'ARForms'); ?></div>
                                                    <div class="arfaddtosite_sidebarsubtitle"><?php _e('Please use any of the following shortcode to add form into post / page / widget', 'ARForms'); ?></div>

                                                    <div class="arfaddtosite_sidebar_title"><?php _e('Normal View', 'ARForms'); ?></div>
                                                    <div class="arfaddtosite_sidebar_subtitle" style=" <?php echo $normal_view_style; ?>">[ARForms id=<?php
                                                        if ($ref_formkey != 0) {
                                                            echo $ref_formkey;
                                                        } else {
                                                            echo $id;
                                                        }
                                                        ?>]</div>


                                                    <div class="arfaddtosite_sidebar_title"><?php _e('Pop-up View', 'ARForms'); ?></div>
                                                    <div class="arfaddtosite_sidebar_subtitle" style=" <?php echo $popup_view_style; ?>">[ARForms_popup id=<?php
                                                        if ($ref_formkey != 0) {
                                                            echo $ref_formkey;
                                                        } else {
                                                            echo $id;
                                                        }
                                                        ?> desc="Click here to open Form" type="link" height="540" width="800"]</div>
                                                    <div class="arfaddtosite_sidebar_subtitle"><?php echo stripcslashes(__('Note : Possible argument for type argument 1.) type="link"  2.) type="button".', 'ARForms')); ?></div>
                                                    <div class="arfaddtosite_sidebar_subtitle"><?php echo stripcslashes(__(': You can adjust height and width of modal box', 'ARForms')); ?></div>

                                                    <div class="arfaddtosite_sidebar_title"><?php _e('Fly View', 'ARForms'); ?></div>
                                                    <div class="arfaddtosite_sidebar_subtitle" style=" <?php echo $fly_view_style; ?>">[ARForms_popup id=<?php
                                                        if ($ref_formkey != 0) {
                                                            echo $ref_formkey;
                                                        } else {
                                                            echo $id;
                                                        }
                                                        ?> desc="Click here to open Form" type="fly" position="left" height="540" width="800"]</div>
                                                    <div class="arfaddtosite_sidebar_subtitle"><?php echo stripcslashes(__('Note : Possible argument for position argument 1.) position="left"  2.) position="right".', 'ARForms')); ?></div>


                                                    <div class="arfaddtosite_sidebar_title"><?php _e('Sticky view', 'ARForms'); ?></div>
                                                    <div class="arfaddtosite_sidebar_subtitle" style=" <?php echo $sticky_view_style; ?>" >[ARForms_popup id=<?php
                                                        if ($ref_formkey != 0) {
                                                            echo $ref_formkey;
                                                        } else {
                                                            echo $id;
                                                        }
                                                        ?> desc="Click here to open Form" type="sticky" position="top" height="540" width="800"]</div>
                                                    <div class="arfaddtosite_sidebar_subtitle"><?php echo stripcslashes(__('Note : Possible argument for position argument 1.) position="top"  2.) position="bottom".', 'ARForms')); ?></div>


                                                    <div class="arfaddtosite_sidebar_title" style="font-size:16px;"><?php _e('Please use any of the following shortcode to add form into template', 'ARForms'); ?></div>

                                                    <div class="arfaddtosite_sidebar_title"><?php _e('Normal View', 'ARForms'); ?></div>
                                                    <div class="arfaddtosite_sidebar_subtitle" style=" <?php echo $normal_view_style_php; ?>">&lt;?php echo maincontroller::get_form_shortcode(array('id' => '<?php
                                                        if ($ref_formkey != 0) {
                                                            echo $ref_formkey;
                                                        } else {
                                                            echo $id;
                                                        }
                                                        ?>')); ?></div>

                                                    <div class="arfaddtosite_sidebar_title"><?php _e('Pop-up View', 'ARForms'); ?></div>
                                                    <div class="arfaddtosite_sidebar_subtitle" style=" <?php echo $popup_view_style_php; ?>">&lt;?php echo maincontroller::get_form_shortcode_popup(array('id' => '<?php
                                                        if ($ref_formkey != 0) {
                                                            echo $ref_formkey;
                                                        } else {
                                                            echo $id;
                                                        }
                                                        ?>', 'desc'=>'Click here to open Form', 'type'=>'link', 'height'=>'540', 'width'=>'800')); ?></div>

                                                    <div class="arfaddtosite_sidebar_title"><?php _e('Fly View', 'ARForms'); ?></div>
                                                    <div class="arfaddtosite_sidebar_subtitle" style=" <?php echo $popup_view_style_php; ?>">&lt;?php echo maincontroller::get_form_shortcode_popup(array('id' => '<?php
                                                        if ($ref_formkey != 0) {
                                                            echo $ref_formkey;
                                                        } else {
                                                            echo $id;
                                                        }
                                                        ?>', 'desc'=>'Click here to open Form', 'type'=>'fly', 'position'=>'left', 'height'=>'540', 'width'=>'800')); ?></div>

                                                    <div class="arfaddtosite_sidebar_title"><?php _e('Sticky view', 'ARForms'); ?></div>
                                                    <div class="arfaddtosite_sidebar_subtitle" style=" <?php echo $popup_view_style_php; ?>">&lt;?php echo maincontroller::get_form_shortcode_popup(array('id' => '<?php
                                                        if ($ref_formkey != 0) {
                                                            echo $ref_formkey;
                                                        } else {
                                                            echo $id;
                                                        }
                                                        ?>', 'desc'=>'Click here to open Form', 'type'=>'sticky', 'position'=>'top', 'height'=>'540', 'width'=>'800')); ?></div>

                                                    <div style="clear:both;"></div>

                                                </div>
                                            </div>	
                                            <!-- add to site end -->
                                        </div>

                                    </div>
                                </div>



                                <?php
                                $delete_modal_width = (@$_COOKIE['width'] - 850) / 2;
                                $delete_modal_height = (@$_COOKIE['height'] - 500) / 2;
                                ?>




                            </div>


                        </div>


                    </div>


                    <?php
                    if (version_compare($GLOBALS['wp_version'], '3.3.2', '>')) {
                        ?>
                        <?php
                        $widthmaincontent = @$_COOKIE['width'] - 397;
                        $heightmaincontent = @$_COOKIE['height'] * 0.80;
                        $paddingheight = (@$_COOKIE['height'] * 0.20) / 2;
                        ?>
                        <style type="text/css">
                            .iframe_loader {
                                vertical-align:middle;
                                position:absolute;
                                top:<?php echo ($heightmaincontent / 2) . 'px'; ?>;
                                left:<?php echo (($widthmaincontent - 140) / 2) . 'px'; ?>;
                                display:none;
                            }
                        </style>
                        <?php
                    }
                    ?>


                </div>


            </div>

    </form>

</div>

<div id="form_previewmodal" class="arfmodal arfhide arffade" style="display:none;left:15%; width:1074px; height:480px;">
    <div class="arfmodal-header">
        <div style="padding-top:10px;font-size:24.5px; color:#3E6289; float:left;">

            <div onclick="arfchangedevice('computer');" title="<?php _e('Computer View', 'ARForms'); ?>" class="arfdevicesbg arfhelptip"><div id="arfcomputer" class="arfdevices arfactive"></div></div>
            <div onclick="arfchangedevice('tablet');" title="<?php _e('Tablet View', 'ARForms'); ?>" class="arfdevicesbg arfhelptip"><div id="arftablet" class="arfdevices"></div></div>
            <div onclick="arfchangedevice('mobile');" title="<?php _e('Mobile View', 'ARForms'); ?>" class="arfdevicesbg arfhelptip"><div id="arfmobile" class="arfdevices"></div></div>                
        </div>
        <div style="float:right; padding-top:20px; cursor:pointer;" data-dismiss="arfmodal"><img src="<?php echo ARFURL . '/images/close-button2.png'; ?>" align="absmiddle" /></div>
    </div>
    <div class="arfmodal-body" style="height:355px; overflow:hidden; clear:both;">
        <div class="iframe_loader" align="center"><img src="<?php echo ARFURL . '/images/ajax-loading-teal.gif'; ?>" /></div>	
        <iframe id="arfdevicepreview" src="" frameborder="0" height="100%" width="100%"></iframe>
    </div>
</div>
<div id="arf_fontawesome_modal" class="arfmodal arfhide arffade arffontawesomemodal" data-backdrop="true" style="display:none;left:15%;width:850px;height:auto;top:50px !important;">
    <div class="arfmodal-header" style="background:#1bbae1;height:50px;padding:0 15px;">
        <div style="padding-top:10px;font-size:18px;color:#3e6289;float:left;line-height:30px;">
            <div class="arf_modal_title_new"><?php _e('Choose Icon', 'ARForms'); ?></div>
        </div>
        <div style="float:right; padding-top:10px; cursor:pointer;" data-dismiss="arfmodal"><img src="<?php echo ARFURL . '/images/close-button2.png'; ?>" align="absmiddle" /></div>
    </div>
    <div class="arfmodal-body" style="height:450px;overflow:scroll;clear:both;padding:20px 13px 25px 19px;width:96%;float:left;overflow-x:hidden;">
        <?php
        if (is_rtl()) {
            $is_rtl = 'arf_rtl';
        } else {
            $is_rtl = '';
        }
        require( VIEWS_PATH . '/arf_font_awesome.php' );
        ?>
    </div>
</div>
<?php require(VIEWS_PATH . '/footer.php'); ?>

<?php unset($display); ?>