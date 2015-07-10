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

class arfieldhelper {

    function arfieldhelper() {

        add_filter('arfgetdefaultvalue', array(&$this, 'get_default_value'), 10, 3);

        add_filter('arfreplaceshortcodes', array(&$this, 'replace_html_shortcodes'), 10, 2);

        add_filter('arfsetupeditfieldsvars', array(&$this, 'setup_edit_vars'), 10, 3);

        add_filter('arfgetpagedfields', array(&$this, 'get_form_fields'), 10, 3);

        add_filter('arfothercustomhtml', array(&$this, 'get_default_html'), 10, 2);

        add_filter('arfsetupeditfieldvars', array(&$this, 'setup_new_field_vars'), 10);

        add_filter('arfsetupnewfieldsvars', array(&$this, 'setup_new_vars'), 10, 2);

        add_filter('arfbeforereplaceshortcodes', array(&$this, 'before_replace_shortcodes'), 10, 4);

        add_filter('arfpostedfieldids', array(&$this, 'posted_field_ids'));
    }

    function get_default_value($value, $field, $dynamic_default = true, $return_array = false) {


        if (is_array(maybe_unserialize($value)))
            return $value;


        if ($field and $dynamic_default) {


            $field->field_options = maybe_unserialize($field->field_options);


            if (isset($field->field_options['dyn_default_value']) and ! empty($field->field_options['dyn_default_value'])) {


                $prev_val = $value;


                $value = $field->field_options['dyn_default_value'];
            }
        }



        preg_match_all("/\[(date|time|email|login|display_name|first_name|last_name|user_meta|post_meta|post_id|post_title|post_author_email|ip_address|auto_id|get|get-(.?)|\d*)\b(.*?)(?:(\/))?\]/s", $value, $matches, PREG_PATTERN_ORDER);



        if (!isset($matches[0]))
            return $value;


        foreach ($matches[0] as $match_key => $val) {


            switch ($val) {


                case '[date]':


                    global $style_settings;


                    $new_value = date_i18n($style_settings->date_format, strtotime(current_time('mysql')));


                    break;


                case '[time]':


                    $new_value = date('H:i:s', strtotime(current_time('mysql')));


                    break;


                case '[email]':


                    global $current_user;


                    $new_value = (isset($current_user->user_email)) ? $current_user->user_email : '';


                    break;


                case '[login]':


                    global $current_user;


                    $new_value = (isset($current_user->user_login)) ? $current_user->user_login : '';


                    break;


                case '[display_name]':


                    global $current_user;


                    $new_value = (isset($current_user->display_name)) ? $current_user->display_name : '';


                    break;


                case '[first_name]':


                    global $current_user;


                    $new_value = (isset($current_user->user_firstname)) ? $current_user->user_firstname : '';


                    break;


                case '[last_name]':


                    global $current_user;


                    $new_value = (isset($current_user->user_lastname)) ? $current_user->user_lastname : '';


                    break;


                case '[post_id]':


                    global $post;


                    if ($post)
                        $new_value = $post->ID;


                    break;


                case '[post_title]':


                    global $post;


                    if ($post)
                        $new_value = $post->post_title;


                    break;


                case '[post_author_email]':


                    $new_value = get_the_author_meta('user_email');


                    break;


                case '[user_id]':


                    global $user_ID;


                    $new_value = $user_ID ? $user_ID : '';


                    break;


                case '[ip_address]':


                    $new_value = $_SERVER['REMOTE_ADDR'];


                    break;


                default:


                    $atts = shortcode_parse_atts(stripslashes($matches[3][$match_key]));


                    $shortcode = $matches[1][$match_key];





                    if (preg_match("/\[get-(.?)\b(.*?)?\]/s", $val)) {


                        $param = str_replace('[get-', '', $val);


                        if (preg_match("/\[/s", $param))
                            $val .= ']';
                        else
                            $param = trim($param, ']');


                        global $armainhelper;

                        $new_value = $armainhelper->get_param($param);


                        if (is_array($new_value) and ! $return_array)
                            $new_value = implode(', ', $new_value);
                    }else {


                        switch ($shortcode) {


                            case 'get':


                                $new_value = '';


                                if (isset($atts['param'])) {


                                    if (strpos($atts['param'], '&#91;')) {


                                        $atts['param'] = str_replace('&#91;', '[', $atts['param']);


                                        $atts['param'] = str_replace('&#93;', ']', $atts['param']);
                                    }


                                    global $armainhelper;

                                    $new_value = $armainhelper->get_param($atts['param'], false);


                                    if (!$new_value) {


                                        global $wp_query;


                                        if (isset($wp_query->query_vars[$atts['param']]))
                                            $new_value = $wp_query->query_vars[$atts['param']];
                                    }


                                    if (!$new_value and isset($atts['default']))
                                        $new_value = $atts['default'];


                                    else if (!$new_value and isset($prev_val))
                                        $new_value = $prev_val;
                                }





                                if (is_array($new_value) and ! $return_array)
                                    $new_value = implode(', ', $new_value);


                                break;


                            case'auto_id':


                                global $arfrecordmeta;





                                $last_entry = $arfrecordmeta->get_max($field);





                                if (!$last_entry and isset($atts['start']))
                                    $new_value = (int) $atts['start'];





                                if (!isset($new_value))
                                    $new_value = $last_entry + 1;


                                break;


                            case 'user_meta':


                                if (isset($atts['key'])) {


                                    global $current_user;


                                    $new_value = (isset($current_user->{$atts['key']})) ? $current_user->{$atts['key']} : '';
                                }


                                break;


                            case 'post_meta':


                                if (isset($atts['key'])) {


                                    global $post;


                                    if ($post) {


                                        $post_meta = get_post_meta($post->ID, $atts['key'], true);


                                        if ($post_meta)
                                            $new_value = $post_meta;
                                    }
                                }


                                break;


                            default:


                                if (is_numeric($shortcode)) {

                                    global $armainhelper;


                                    $new_value = $armainhelper->get_param('item_meta[' . $shortcode . ']', false);





                                    if (!$new_value and isset($atts['default']))
                                        $new_value = $atts['default'];





                                    if (is_array($new_value) and ! $return_array)
                                        $new_value = implode(', ', $new_value);
                                }else {


                                    $new_value = $val;
                                }


                                break;
                        }
                    }
            }


            if (!isset($new_value))
                $new_value = '';





            if (is_array($new_value))
                $value = $new_value;
            else
                $value = str_replace($val, $new_value, $value);


            unset($new_value);
        }


        return do_shortcode($value);
    }

    function setup_new_field_vars($values) {

        global $arfieldhelper;

        $values['field_options'] = maybe_unserialize($values['field_options']);


        foreach ($arfieldhelper->get_default_field_opts($values) as $opt => $default)
            $values[$opt] = (isset($values['field_options'][$opt])) ? $values['field_options'][$opt] : $default;


        return $values;
    }

    function setup_new_vars($values, $field) {


        $values['use_key'] = false;


        $field->field_options = maybe_unserialize($field->field_options);


        foreach ($this->get_default_field_opts($values, $field) as $opt => $default)
            $values[$opt] = (isset($field->field_options[$opt]) && $field->field_options[$opt] != '') ? $field->field_options[$opt] : $default;


        $values['hide_field'] = (array) $values['hide_field'];


        $values['hide_field_cond'] = (array) $values['hide_field_cond'];


        $values['hide_opt'] = (array) $values['hide_opt'];


        if ($values['type'] == 'date') {


            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $values['value'])) {


                global $style_settings, $armainhelper;


                $values['value'] = $armainhelper->convert_date($values['value'], 'Y-m-d', $style_settings->date_format);
            }
        } else if (!empty($values['options'])) {


            foreach ($values['options'] as $val_key => $val_opt) {


                if (is_array($val_opt)) {


                    foreach ($val_opt as $opt_key => $opt) {


                        $values['options'][$val_key][$opt_key] = $this->get_default_value($opt, $field, false);


                        unset($opt_key);


                        unset($opt);
                    }
                } else {


                    $values['options'][$val_key] = $this->get_default_value($val_opt, $field, false);
                }


                unset($val_key);


                unset($val_opt);
            }
        }

        if (is_array($values['value'])) {


            foreach ($values['value'] as $val_key => $val)
                $values['value'][$val_key] = apply_filters('arfgetdefaultvalue', $val, $field);
        } else if (!empty($values['value'])) {


            $values['value'] = apply_filters('arfgetdefaultvalue', $values['value'], $field);
        }

        return $values;
    }

    function field_selection() {


        $fields = apply_filters('arfavailablefields', array(
            'text' => __('Single Line Text', 'ARForms'),
            'textarea' => __('Multiline Text', 'ARForms'),
            'checkbox' => __('Checkboxes', 'ARForms'),
            'radio' => __('Radio Buttons', 'ARForms'),
            'select' => __('Dropdown', 'ARForms'),
            'file' => __('File Upload', 'ARForms'),
        ));





        return $fields;
    }

    function get_all_form_fields($form_id, $error = false) {


        global $arffield;


        $fields = apply_filters('arfgetpagedfields', false, $form_id, $error);


        if (!$fields)
            $fields = $arffield->getAll(array('fi.form_id' => $form_id), 'field_order');


        return $fields;
    }

    function pro_field_selection() {


        return apply_filters('arfaavailablefields', array(
            'email' => __('Email Address', 'ARForms'),
            'captcha' => __('CAPTCHA', 'ARForms'),
            'number' => __('Number', 'ARForms'),
            'phone' => __('Phone Number', 'ARForms'),
            'date' => __('Date', 'ARForms'),
            'time' => __('Time', 'ARForms'),
            'url' => __('Website/URL', 'ARForms'),
            'image' => __('Image URL', 'ARForms'),
            'hidden' => __('Hidden Field', 'ARForms'),
            'password' => __('Password', 'ARForms'),
            'html' => __('HTML', 'ARForms'),
            'divider' => __('Section', 'ARForms'),
            'break' => __('Page Break', 'ARForms'),
            'scale' => __('Star Rating', 'ARForms'),
            'like' => __('Like button', 'ARForms'),
            'slider' => __('Slider', 'ARForms'),
            'colorpicker' => __('Color Picker', 'ARForms'),
            'imagecontrol' => __('Image', 'ARForms'),
        ));
    }

    function setup_edit_vars($values, $field, $entry_id = false) {


        $values['use_key'] = false;

        $field->field_options = maybe_unserialize($field->field_options);

        $values['option_order'] = maybe_unserialize($field->option_order);

        foreach ($this->get_default_field_opts($values, $field) as $opt => $default) {


            $values[$opt] = stripslashes_deep(($_POST and isset($_POST['field_options'][$opt . '_' . $field->id]) ) ? $_POST['field_options'][$opt . '_' . $field->id] : (isset($field->field_options[$opt]) ? $field->field_options[$opt] : $default));
        }


        $values['hide_field'] = (array) $values['hide_field'];


        $values['hide_field_cond'] = (array) $values['hide_field_cond'];


        $values['hide_opt'] = (array) $values['hide_opt'];


        if ($values['type'] == 'date') {


            global $style_settings, $armainhelper;


            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $values['value']))
                $values['value'] = $armainhelper->convert_date($values['value'], 'Y-m-d', $style_settings->date_format);


            else if (preg_match('/^\d{4}-\d{2}-\d{2}/', $values['value']))
                $values['value'] = $armainhelper->convert_date($values['value'], 'Y-m-d H:i:s', $style_settings->date_format);
        }else if ($values['type'] == 'file') {


            if ($values['post_field'] != 'post_custom') {


                global $arfrecordmeta;


                $values['value'] = $arfrecordmeta->get_entry_meta_by_field($entry_id, $values['id']);
            }
        }
        return $values;
    }

    function get_default_field_opts($values = false, $field = false) {


        global $style_settings;


        $minnum = 1;


        $maxnum = 10;


        $step = 1;


        $align = 'block';


        if ($values) {


            if ($values['type'] == 'number') {


                $minnum = 0;


                $maxnum = 9999;
            } else if ($values['type'] == 'time') {


                $step = 30;
            } else if ($values['type'] == 'radio') {


                $align = 'inline';
            } else if ($values['type'] == 'checkbox') {


                $align = 'block';
            } else if ($values['type'] == 'scale') {


                $maxnum = 5;
            }
        }

        if ($values['type'] == 'slider') {
            $minnum = 0;
            $maxnum = 50;
        }

        $end_minute = 60 - (int) $step;


        unset($values);


        unset($field);


        return array(
            'slide' => 0, 'form_select' => '', 'show_hide' => 'show', 'any_all' => 'any', 'align' => $align,
            'hide_field' => array(), 'hide_field_cond' => array('=='), 'hide_opt' => array(), 'star' => 0,
            'post_field' => '', 'custom_field' => '', 'taxonomy' => 'category', 'exclude_cat' => 0, 'ftypes' => array(),
            'data_type' => '', 'restrict' => 0, 'start_year' => 2000, 'end_year' => 2020, 'read_only' => 0,
            'locale' => '', 'attach' => false, 'minnum' => $minnum, 'maxnum' => $maxnum,
            'step' => $step, 'clock' => 12, 'start_time' => '00:00', 'end_time' => '23:' . $end_minute,
            'dependent_fields' => 0, 'use_calc' => 0, 'calc' => '', 'duplication' => 1,
            'dyn_default_value' => '', 'field_width' => '', 'label_width' => $style_settings->width,
            'text_direction' => $style_settings->text_direction, 'align_radio' => '1', 'custom_width_field' => '0',
        );
    }

    function check_data_values($values) {


        $check = true;

        return $check;
    }

    function setup_new_variables($type = '', $form_id = '') {


        global $arfsettings, $arfieldhelper;





        $defaults = $arfieldhelper->get_default_field_options($type, $form_id);


        $defaults['field_options']['custom_html'] = $arfieldhelper->get_basic_default_html($type);





        $values = array();





        foreach ($defaults as $var => $default) {


            if ($var == 'field_options') {


                $values['field_options'] = array();


                foreach ($default as $opt_var => $opt_default) {


                    $values['field_options'][$opt_var] = $opt_default;


                    unset($opt_var);


                    unset($opt_default);
                }
            } else {


                $values[$var] = $default;
            }


            unset($var);


            unset($default);
        }





        if ($type == 'checkbox')
            $values['options'] = maybe_serialize(array(__('Checkbox 1', 'ARForms'), __('Checkbox 2', 'ARForms')));


        else if ($type == 'radio')
            $values['options'] = maybe_serialize(array(__('Radio 1', 'ARForms'), __('Radio 2', 'ARForms')));


        else if ($type == 'select')
            $values['options'] = maybe_serialize(array('', __('Select 1', 'ARForms')));


        else if ($type == 'textarea')
            $values['field_options']['max'] = '3';


        else if ($type == 'captcha')
            $values['invalid'] = $arfsettings->re_msg;





        return $values;
    }

    function setup_edit_variables($record) {


        global $arfrecordmeta, $arfform, $armainhelper, $arfieldhelper;



        $values = array('id' => $record->id, 'form_id' => $record->form_id, 'conditional_logic' => maybe_unserialize($record->conditional_logic), 'option_order ' => maybe_unserialize($record->option_order)); //---------- for conditional logic ----------//



        foreach (array('name' => $record->name, 'description' => $record->description) as $var => $default)
            $values[$var] = $default;

        $values['form_name'] = ($record->form_id) ? $arfform->getName($record->form_id) : '';





        foreach (array('field_key' => $record->field_key, 'type' => $record->type, 'default_value' => $record->default_value, 'field_order' => $record->field_order, 'required' => $record->required) as $var => $default)
            $values[$var] = $armainhelper->get_param($var, $default);





        $values['options'] = $record->options;


        $values['field_options'] = $record->field_options;





        $defaults = $arfieldhelper->get_default_field_options($values['type'], $record, true);





        if ($values['type'] == 'captcha') {


            global $arfsettings;


            $defaults['invalid'] = $arfsettings->re_msg;
        }





        foreach ($defaults as $opt => $default)
            $values[$opt] = (isset($record->field_options[$opt])) ? $record->field_options[$opt] : $default;





        $values['custom_html'] = (isset($record->field_options['custom_html'])) ? $record->field_options['custom_html'] : $arfieldhelper->get_basic_default_html($record->type);





        return apply_filters('arfsetupeditfieldvars', $values, $values['field_options']);
    }

    function _show_category($atts) {

        global $arfieldhelper;


        extract($atts);


        if (!is_object($cat))
            return;


        $checked = '';


        if (is_array($value))
            $checked = (in_array($cat->cat_ID, $value)) ? 'checked="checked" ' : '';


        else if ($cat->cat_ID == $value)
            $checked = 'checked="checked" ';
        else
            $checked = '';


        $class = '';


        $sanitized_name = ((isset($field['id'])) ? $field['id'] : $field['field_options']['taxonomy']) . '-' . $cat->cat_ID;
        ?>


        <div class="frm_<?php echo $type ?>" id="frm_<?php echo $type . '_' . $sanitized_name ?>">


            <label<?php echo $class ?> for="field_<?php echo $sanitized_name ?>"><input type="<?php echo $type ?>" name="<?php echo $field_name ?>" <?php echo (isset($hide_id) and $hide_id) ? '' : 'id="field_' . $sanitized_name . '"'; ?> value="<?php echo $cat->cat_ID ?>" <?php echo $checked;
        do_action('arffieldinputhtml', $field);
        ?> /><?php echo $cat->cat_name ?></label>

            <?php
            $children = get_categories(array('type' => $post_type, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false, 'exclude' => $exclude, 'parent' => $cat->cat_ID, 'taxonomy' => $taxonomy));


            if ($children) {


                $level++;


                foreach ($children as $key => $cat) {
                    ?>


                    <div class="catlevel_<?php echo $level ?>"><?php $arfieldhelper->_show_category(compact('cat', 'field', 'field_name', 'exclude', 'type', 'value', 'exclude', 'level', 'onchange', 'post_type', 'taxonomy', 'hide_id')) ?></div>


                    <?php
                }
            }


            echo '</div>';
        }

        function get_status_options($field) {


            global $arfform;

            $post_type = $arfform->post_type($field->form_id);


            $post_type_object = get_post_type_object($post_type);


            $options = array();


            if (!$post_type_object)
                return $options;


            $can_publish = current_user_can($post_type_object->cap->publish_posts);


            $options = get_post_statuses();



            if (!$can_publish) {


                unset($options['publish']);


                if (isset($options['future']))
                    unset($options['future']);
            }


            return $options;
        }

        function get_user_options() {


            global $wpdb;


            $users = (function_exists('get_users')) ? get_users(array('fields' => array('ID', 'user_login', 'display_name'), 'blog_id' => $GLOBALS['blog_id'])) : get_users(array('fields' => array('ID', 'user_login', 'display_name'), 'blog_id' => $GLOBALS['blog_id']));


            $options = array('' => '');


            foreach ($users as $user)
                $options[$user->ID] = (!empty($user->display_name)) ? $user->display_name : $user->user_login;


            return $options;
        }

        function get_linked_options($values, $field, $entry_id = false) {


            global $arfrecordmeta, $user_ID, $arffield, $MdlDb, $arrecordhelper;


            $metas = array();


            $selected_field = $arffield->getOne($values['form_select']);


            if (!$selected_field)
                return array();


            $selected_field->field_options = maybe_unserialize($selected_field->field_options);



            $attach_ids = array();


            if ($values['restrict'] and $user_ID) {


                $entry_user = $user_ID;


                if ($entry_id and is_admin()) {


                    $entry_user = $MdlDb->get_var($MdlDb->entries, array('id' => $entry_id), 'user_id');


                    if (!$entry_user or empty($entry_user))
                        $entry_user = $user_ID;
                }



                if (isset($selected_field->form_id)) {


                    $linked_where = array('form_id' => $selected_field->form_id, 'user_id' => $entry_user);


                    $entry_ids = $MdlDb->get_col($MdlDb->entries, $linked_where, 'id');


                    unset($linked_where);
                }


                if (isset($entry_ids) and ! empty($entry_ids))
                    $metas = $arfrecordmeta->getAll("it.entry_id in (" . implode(',', $entry_ids) . ") and field_id=" . (int) $values['form_select'], ' ORDER BY entry_value');
            }else {


                $metas = $MdlDb->get_records($MdlDb->entry_metas, array('field_id' => $values['form_select']), 'entry_value', '', 'entry_id, entry_value');


                $attach_ids = $MdlDb->get_records($MdlDb->entries, array('form_id' => $selected_field->form_id), '', '', 'id, attachment_id');
            }

            $options = array();


            foreach ($metas as $meta) {


                $meta = (array) $meta;


                if (empty($meta['entry_value']))
                    continue;


                if ($selected_field->type == 'image')
                    $options[$meta['entry_id']] = $meta['entry_value'];
                else
                    $options[$meta['entry_id']] = $arrecordhelper->display_value($meta['entry_value'], $selected_field, array('type' => $selected_field->type, 'show_icon' => false, 'show_filename' => false));


                unset($meta);
            }


            unset($metas);


            natcasesort($options);





            return $options;
        }

        function posted_field_ids($where) {


            if (isset($_POST['form_id']) and isset($_POST['arfpageorder' . $_POST['form_id']]))
                $where .= ' and fi.field_order < ' . (int) $_POST['arfpageorder' . $_POST['form_id']];


            return $where;
        }

        function get_form_fields($fields, $form_id, $error = false) {


            global $arfprevpage, $arffield, $arfnextpage, $armainhelper;


            $prev_page = $armainhelper->get_param('arfpageorder' . $form_id, false);


            $prev_page = (int) $prev_page;


            $where = "fi.type='break' AND fi.form_id=" . (int) $form_id;


            if ($error and ! $prev_page)
                $prev_page = 999;



            if ($prev_page) {


                if ($error) {


                    $where_error = $where . " AND fi.field_order <" . ($prev_page);


                    $prev_page_obj = $arffield->getAll($where_error, 'field_order DESC', 1);


                    $prev_page = ($prev_page_obj) ? $prev_page_obj->field_order : false;
                }



                if ($prev_page and ! isset($prev_page_obj)) {


                    $prev_where = $where . " AND fi.field_order=" . $prev_page;


                    $prev_page_obj = $arffield->getAll($prev_where, 'field_order DESC', 1);
                }


                $arfprevpage[$form_id] = $prev_page;


                $where .= ' AND fi.field_order >=' . ($prev_page + 1);
            } else
                unset($arfprevpage[$form_id]);


            $next_page = $arffield->getAll($where, 'field_order', 1);


            unset($where);


            if ($next_page or $prev_page) {


                $query = "(fi.type != 'break'";


                if ($next_page)
                    $query .= " or fi.id = $next_page->id";


                if ($prev_page)
                    $query .= " or fi.id = $prev_page_obj->id";


                $query .= ") and fi.form_id=$form_id";


                if ($prev_page)
                    $query .= " and fi.field_order >= $prev_page";


                if ($next_page)
                    $query .= " and fi.field_order <= $next_page->field_order";


                if (is_admin())
                    $query .= " and fi.type != 'captcha'";


                $fields = $arffield->getAll($query, ' ORDER BY field_order');
            }


            if ($next_page)
                $arfnextpage[$form_id] = $next_page->name;
            else
                unset($arfnextpage[$form_id]);


            return $fields;
        }

        function get_form_fields_tmp($fields, $form_id, $error = false, $previous = false) {


            global $arfprevpage, $arffield, $arfnextpage;


            $query = "fi.form_id=" . (int) $form_id;

            $fields = $arffield->getAll($query, ' ORDER BY field_order');

            return $fields;
        }

        function get_basic_default_html($type = 'text') {


            if (apply_filters('arfdisplayfieldhtml', true, $type)) {


                $for = (in_array($type, array('radio', 'checkbox', 'data'))) ? '' : 'for="field_[key]"';


                $default_html = '<div id="arf_field_[id]_container" class="arfformfield control-group arfmainformfield [required_class][error_class]"  [field_style]>

<label ' . $for . ' class="arf_main_label">[field_name]

<span class="arfcheckrequiredfield">[required_label]</span>

</label>

[input]

[if description]<div class="arf_field_description" [description_style]>[description]</div>[/if description]

[if error]<div class="arf_frm_error" [description_style]>[error]</div>[/if error]

</div>';
            } else
                $default_html = apply_filters('arfothercustomhtml', '', $type);





            return apply_filters('arfcustomhtml', $default_html, $type);
        }

        function get_default_field_options($type, $field, $limit = false) {


            $field_options = array(
                'size' => '', 'max' => '', 'label' => '', 'blank' => '',
                'required_indicator' => '*', 'invalid' => '', 'separate_value' => 0,
                'clear_on_focus' => 0, 'default_blank' => 0, 'classes' => 'arf_1',
                'custom_html' => '', 'star_color' => 'yellow', 'star_size' => 'small', 'star_val' => '',
                'first_page_label' => 'Step1', 'second_page_label' => 'Step2', 'pre_page_title' => 'Previous', 'next_page_title' => 'Next', 'page_break_type' => 'wizard', 'page_break_first_use' => '0', 'is_recaptcha' => 'recaptcha',
                'inline_css' => '', 'css_outer_wrapper' => '', 'css_label' => '', 'css_input_element' => '', 'css_description' => '',
                'file_upload_text' => 'Upload', 'file_remove_text' => 'Remove', 'upload_btn_color' => '#077bdd', 'arf_divider_font' => 'Helvetica',
                'arf_divider_font_size' => '16', 'arf_divider_font_style' => 'bold', 'arf_divider_bg_color' => '#ffffff', 'arf_divider_inherit_bg' => '0',
                'lbllike' => __('Like', 'ARForms'), 'lbldislike' => __('Dislike', 'ARForms'), 'slider_handle' => 'round', 'slider_step' => '1',
                'slider_bg_color' => '#d1dee5', 'slider_handle_color' => '#0480BE', 'slider_value' => '1',
                'like_bg_color' => '#087ee2', 'dislike_bg_color' => '#ff1f1f', 'slider_bg_color2' => '#bcc7cd',
                'upload_font_color' => '#ffffff', 'confirm_password' => 0, 'password_strength' => 0,
                'is_set_confirm' => 0, 'invalid_password' => __('Confirm Password does not match with password', 'ARForms'),
                'placehodertext' => '', 'phone_validation' => 'international', 'confirm_password_label' => __('Confirm Password', 'ARForms'),
                'image_url' => ARFURL . '/images/no-image.png', 'image_left' => '0px', 'image_top' => '0px', 'image_height' => '', 'image_width' => '',
                'image_center' => 'no', 'enable_total' => 0, 'colorpicker_type' => 'advanced', 'default_hour' => '0', 'default_minutes' => '0',
                'show_year_month_calendar' => '0', 'password_placeholder' => '', 'minlength' => '', 'minlength_message' => 'Invalid mininum characters length',
                'confirm_email' => '', 'confirm_email_label' => __('Confirm Email', 'ARForms'), 'invalid_confirm_email' => __('Confirm Email does not match with email', 'ARForms'),
                'confirm_email_placeholder' => '', 'enable_arf_prefix' => '0', 'arf_prefix_icon' => '', 'enable_arf_suffix' => '0', 'arf_suffix_icon' => '',
            );


            if ($type == 'captcha')
                $field_options['invalid'] = __('The reCAPTCHA was not entered correctly', 'ARForms');
            else if ($type == 'email')
                $field_options['invalid'] = __('Email is invalid', 'ARForms');
            else if ($type == 'file')
                $field_options['invalid'] = __('File is invalid', 'ARForms');
            else if ($type == 'number')
                $field_options['invalid'] = __('Number is out of range', 'ARForms');
            else if ($type == 'phone')
                $field_options['invalid'] = __('Phone is invalid', 'ARForms');
            else if ($type == 'image')
                $field_options['invalid'] = __('Image is invalid', 'ARForms');
            else if ($type == 'date')
                $field_options['invalid'] = __('Date is invalid', 'ARForms');
            else if ($type == 'url')
                $field_options['invalid'] = __('Website is invalid', 'ARForms');


            $field_options = apply_filters('arf_add_more_field_options_outside', $field_options, $type);

            if ($limit)
                return $field_options;





            global $MdlDb, $armainhelper, $arfsettings;





            $form_id = (is_numeric($field)) ? $field : $field->form_id;





            $key = is_numeric($field) ? $armainhelper->get_unique_key('', $MdlDb->fields, 'field_key') : $field->field_key;


            $field_count = $armainhelper->getRecordCount("form_id='$form_id'", $MdlDb->fields);





            return array(
                'name' => __('Untitled', 'ARForms'), 'description' => '',
                'field_key' => $key, 'type' => $type, 'options' => '', 'default_value' => '',
                'field_order' => $field_count + 1, 'required' => false,
                'blank' => $arfsettings->blank_msg, 'unique_msg' => $arfsettings->unique_msg,
                'invalid' => __('This field is invalid', 'ARForms'), 'form_id' => $form_id,
                'field_options' => $field_options
            );
        }

        function show_onfocus_js($field_id, $clear_on_focus) {
            
        }

        function get_default_html($default_html, $type) {


            if ($type == 'break') {


                $default_html = '<h2 class="pos_[label_position]">[field_name]</h2>

[if description]<div class="arf_field_description">[description]</div>[/if description]';
            } else if ($type == 'divider') {


                $default_html = '<div id="heading_[id]" class="arf_heading_div" [field_style]>

<h2 class="arf_sec_heading_field pos_[label_position][collapse_class]">[field_name]</h2>

[collapse_this]

[if description]<div class="arf_field_description arf_heading_description" [description_style]>[description]</div>[/if description]

</div>';
            } else if ($type == 'html') {


                $default_html = '<div id="arf_field_[id]_container" class="arfformfield control-group arfmainformfield [error_class]" [field_style]>[description]</div>';
            }


            return $default_html;
        }

        function replace_field_shortcodes($html, $field, $errors = array(), $form = false) {


            global $arfreadonly, $arfieldhelper, $arrecordcontroller;





            $html = stripslashes($html);


            $html = apply_filters('arfbeforereplaceshortcodes', $html, $field, $errors, $form);





            $field_name = 'item_meta[' . $field['id'] . ']';


            if (isset($field['multiple']) and $field['multiple'] and ( $field['type'] == 'select' or ( $field['type'] == 'data' and isset($field['data_type']) and $field['data_type'] == 'select')))
                $field_name .= '[]';





            $html = str_replace('[id]', $field['id'], $html);




            $html = str_replace('[key]', $field['field_key'], $html);



            $required = ($field['required'] == '0') ? '' : $field['required_indicator'];


            if (!is_array($errors))
                $errors = array();


            $error = (isset($errors['field' . $field['id']])) ? stripslashes($errors['field' . $field['id']]) : false;


            foreach (array('description' => $field['description'], 'required_label' => $required, 'error' => $error) as $code => $value) {

                if ($code == 'description') {
                    if ($field['type'] != 'html' && $field['type'] != 'divider')
                        $value = '';
                }

                if (!$value or $value == '')
                    $html = preg_replace('/(\[if\s+' . $code . '\])(.*?)(\[\/if\s+' . $code . '\])/mis', '', $html);


                else {


                    $html = str_replace('[if ' . $code . ']', '', $html);


                    $html = str_replace('[/if ' . $code . ']', '', $html);
                }


                if ($field['type'] == 'html' && $code == 'description' && $field['enable_total'] == 1) {

                    $regex = '/<arftotal>(.*?)<\/arftotal>/is';

                    preg_match($regex, $value, $arftotalmatches);

                    if ($arftotalmatches) {
                        $value = $arfieldhelper->arf_replace_running_total_field($value, $arftotalmatches, $field);
                    }
                }


                if ($field['type'] != 'checkbox') {
                    $html = str_replace('[' . $code . ']', $value, $html);
                } else {
                    if ($field['name'] != '' and $code == 'required_label') {
                        $html = str_replace('[' . $code . ']', $value, $html);
                    } else if ($field['name'] == '' and $code == 'required_label') {
                        $html = str_replace('[' . $code . ']', '', $html);
                    } else {
                        $html = str_replace('[' . $code . ']', $value, $html);
                    }
                }


                $description_style = ( isset($field['field_width']) and $field['field_width'] == '' ) ? 'style="width:' . $field['field_width'] . 'px;"' : '';

                $html = str_replace('[description_style]', $description_style, $html);
            }

            //---------- for conditional logic ----------//
            $field_style = $arfieldhelper->get_display_style($field);

            $html = str_replace('[field_style]', $field_style, $html);
            //---------- for conditional logic ----------//


            $required_class = ($field['required'] == '0') ? '' : ' arffieldrequired';

            if ($field['type'] == 'confirm_password')
                $required_class .= ' confirm_password_container arf_confirm_password_field_' . $field['confirm_password_field'];

            if ($field['type'] == 'confirm_email')
                $required_class .= ' confirm_email_container arf_confirm_email_field_' . $field['confirm_email_field'];

            $html = str_replace('[required_class]', $required_class, $html);



            global $db_record, $arfform, $arffield, $arfajaxurl, $MdlDb, $wpdb;

            if ($form->id >= 10000)
                $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_ref_forms WHERE id = %d", $form->id), 'ARRAY_A');
            else
                $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arf_forms WHERE id = %d", $form->id), 'ARRAY_A');

            $aweber_arr = "";
            $aweber_arr = $data[0]['form_css'];

            $newarr = array();

            if ($aweber_arr != "") {
                $arr = maybe_unserialize($aweber_arr);



                foreach ($arr as $k => $v)
                    $newarr[$k] = $v;
            }
            $values['label_position'] = ($newarr['hide_labels'] == '1') ? 'none' : $newarr['position'];


            global $style_settings;

            $field['label'] = ($values['label_position'] and $values['label_position'] != '') ? $values['label_position'] : $style_settings->position;


            $html = str_replace('[label_position]', (($field['type'] == 'divider' or $field['type'] == 'break') ? $field['label'] : ' arf_main_label'), $html);




            $html = str_replace('[field_name]', $field['name'], $html);



            $error_class = isset($errors['field' . $field['id']]) ? ' arfblankfield' : '';


            $error_class .= ' ' . $field['label'] . '_container';




            //if(!empty($field['classes'])){
            if (isset($field['classes'])) {

                if (!strpos($html, 'arfformfield '))
                    $error_class .= ' arfformfield';

                global $arf_column_classes, $is_multi_column_loaded;

                if ($field['type'] != 'imagecontrol') {

                    if (isset($field['classes']) and $field['classes'] == 'arf_2' and empty($arf_column_classes['two'])) {
                        $arf_column_classes['two'] = '1';
                        $arf_classes = 'frm_first_half';

                        $arf_column_classes['three'] = '';
                        unset($arf_column_classes['three']);

                        $is_multi_column_loaded[] = $form->form_key; // for is_multi_column_loaded forms
                    } else if (isset($field['classes']) and $field['classes'] == 'arf_2' and isset($arf_column_classes['two']) and $arf_column_classes['two'] == '1') {
                        $arf_classes = 'frm_last_half';
                        $arf_column_classes['two'] = '';

                        unset($arf_column_classes['two']);
                        $arf_column_classes['three'] = '';
                        unset($arf_column_classes['three']);
                    } else if (isset($field['classes']) and $field['classes'] == 'arf_3' and empty($arf_column_classes['three'])) {
                        $arf_column_classes['three'] = '1';
                        $arf_classes = 'frm_first_third';

                        $arf_column_classes['two'] = '';
                        unset($arf_column_classes['two']);
                        $is_multi_column_loaded[] = $form->form_key; // for is_multi_column_loaded forms
                    } else if (isset($field['classes']) and $field['classes'] == 'arf_3' and isset($arf_column_classes['three']) and $arf_column_classes['three'] == '1') {
                        $arf_column_classes['three'] = '2';
                        $arf_classes = 'frm_third';

                        $arf_column_classes['two'] = '';
                        unset($arf_column_classes['two']);
                    } else if (isset($field['classes']) and $field['classes'] == 'arf_3' and isset($arf_column_classes['three']) and $arf_column_classes['three'] == '2') {
                        $arf_classes = 'frm_last_third';

                        $arf_column_classes['three'] = '';
                        unset($arf_column_classes['three']);
                        $arf_column_classes['two'] = '';
                        unset($arf_column_classes['two']);
                    } else {
                        $arf_column_classes = array();
                        $arf_classes = '';
                    }

                    if (isset($arf_column_classes['three']) and $arf_column_classes['three'] == '3') {
                        $arf_column_classes['three'] = '';
                        unset($arf_column_classes['three']);
                    }
                    if (isset($arf_column_classes['two']) and $arf_column_classes['two'] == '2') {
                        $arf_column_classes['two'] = '';
                        unset($arf_column_classes['two']);
                    }
                }

                $arf_classes = isset($arf_classes) ? $arf_classes : '';
                $error_class .= ' ' . $arf_classes;
            }


            $html = str_replace('[error_class]', $error_class, $html);




            $entry_key = (isset($_GET) and isset($_GET['entry'])) ? $_GET['entry'] : '';


            $html = str_replace('[entry_key]', $entry_key, $html);



            preg_match_all("/\[(input|deletelink)\b(.*?)(?:(\/))?\]/s", $html, $shortcodes, PREG_PATTERN_ORDER);





            foreach ($shortcodes[0] as $short_key => $tag) {


                $atts = shortcode_parse_atts($shortcodes[2][$short_key]);





                if (!empty($shortcodes[2][$short_key])) {


                    $tag = str_replace('[', '', $shortcodes[0][$short_key]);


                    $tag = str_replace(']', '', $tag);


                    $tags = explode(' ', $tag);


                    if (is_array($tags))
                        $tag = $tags[0];
                } else
                    $tag = $shortcodes[1][$short_key];





                $replace_with = '';





                if ($tag == 'input') {


                    if (isset($atts['opt']))
                        $atts['opt'] --;


                    $field['input_class'] = isset($atts['class']) ? $atts['class'] : '';


                    if (isset($atts['class']))
                        unset($atts['class']);


                    $field['shortcodes'] = $atts;


                    ob_start();


                    include(VIEWS_PATH . '/inputelements.php');


                    $replace_with = ob_get_contents();


                    ob_end_clean();
                }
                $html = str_replace($shortcodes[0][$short_key], $replace_with, $html);
            }





            if ($form) {


                $form = (array) $form;





                $html = str_replace('[form_key]', $form['form_key'], $html);




                $html = str_replace('[form_name]', $form['name'], $html);
            }


            $html .= "\n";





            return apply_filters('arfreplaceshortcodes', $html, $field, array('errors' => $errors, 'form' => $form));
        }

        function display_recaptcha($field, $error = null) {


            global $arfsettings, $arfieldhelper;





            if (!function_exists('recaptcha_get_html'))
                require_once(FORMPATH . '/core/recaptchalib.php');





            $lang = apply_filters('arfrecaptchalang', $arfsettings->re_lang, $field);





            if (defined('DOING_AJAX')) {


                global $arfrecaptchaloaded;


                if (!$arfrecaptchaloaded)
                    $arfrecaptchaloaded = '';





                $arfrecaptchaloaded .= "Recaptcha.create('" . $arfsettings->pubkey . "','field_" . $field['field_key'] . "',{theme:'" . $arfsettings->re_theme . "',lang:'" . $lang . "'" . apply_filters('arfrecaptchacustom', '', $field) . "});";
                ?>


                <div id="field_<?php echo $field['field_key'] ?>"></div>


        <?php }else { ?>


                <script type="text/javascript">var RecaptchaOptions = {theme: '<?php echo $arfsettings->re_theme ?>', lang: '<?php echo $lang ?>'<?php echo apply_filters('arfrecaptchacustom', '', $field) ?>};</script>


                <?php
                echo '<div id="recaptcha_style">' . recaptcha_get_html($arfsettings->pubkey . '&hl=' . $lang, $error, is_ssl()) . $arfieldhelper->replace_description_shortcode($field) . '</div>';
            }
        }

        function before_replace_shortcodes($html, $field, $error, $form) {

            if ($form != '') {
                $form_css = maybe_unserialize($form->form_css);
                if (is_array($form_css)) {
                    $arfcheckboxalignsetting = $form_css['arfcheckboxalignsetting'];
                    $arfradioalignsetting = $form_css['arfradioalignsetting'];
                }
            }
            global $style_settings;

            if (isset($field['align']) and ( $field['type'] == 'radio' or $field['type'] == 'checkbox')) {


                $required_class = '[required_class]';

                if (($field['type'] == 'radio' and $field['align'] != $arfradioalignsetting) or ( $field['type'] == 'checkbox' and $field['align'] != $arfcheckboxalignsetting)) {

                    if ($field['align'] != 'global')
                        $required_class .= ($field['align'] == 'inline') ? ' arf_horizontal_radio' : ' arf_vertical_radio';


                    $html = str_replace('[required_class]', $required_class, $html);
                }
            }


            if (isset($field['classes']) and strpos($field['classes'], 'frm_grid') !== false) {


                $opt_count = count($field['options']) + 1;


                $html = str_replace('[required_class]', '[required_class] frm_grid_' . $opt_count, $html);


                unset($opt_count);
            }


            return $html;
        }

        function replace_html_shortcodes($html, $field) {


            if ($field['type'] == 'divider') {


                global $arfdiv;


                $trigger = '';


                $html = str_replace(array('none_container', 'top_container', 'left_container', 'right_container'), '', $html);

                global $MdlDb, $arf_page_number, $arfieldhelper;
                $page_num = $MdlDb->get_count($MdlDb->fields, array("form_id" => $field['form_id'], "type" => 'break'));

                if ($page_num > 0) {
                    $collapse_div = '<div class="divider_' . $arf_page_number . '">' . "\n";
                } else {
                    $collapse_div = '<div>' . "\n";
                }

                if (preg_match('/\[(collapse_this)\]/s', $html)) {
                    global $arf_section_div;

                    if ($arf_section_div) {
                        $html = "<div class='arf_clear'></div></div>\n" . $html;
                    } else {
                        $arf_section_div = 1;
                    }

                    $html = str_replace('[collapse_this]', $collapse_div, $html);
                }

                //---------- for conditional logic ----------//
                $field_style = $arfieldhelper->get_display_style($field);

                $html = str_replace('[field_style]', $field_style, $html);
                //---------- for conditional logic ----------//



                $html = str_replace('[collapse_class]', $trigger, $html);
            } else if ($field['type'] == 'html') {


                $html = apply_filters('arfgetdefaultvalue', $html, (object) $field, false);


                $html = do_shortcode($html);
            }


            return $html;
        }

        function get_file_icon($media_id) {

            global $arfieldhelper;

            if (!is_numeric($media_id))
                return;

            $image_array = wp_get_attachment_image_src($media_id, array(150, 150), true);

            $image_array_link = str_replace('thumbs/', '', $image_array[0]);

            $img_size = @getimagesize($image_array[0]);

            $img_height = $img_width = '';

            $img_height = $img_size[1];

            $img_height = ($img_height == '' ) ? '' : 'height="' . $img_height . '"';

            $img_width = $img_size[0];

            $img_width = ($img_width == '' ) ? '' : 'width="' . $img_width . '"';


            $image = '<img class="attachment-thumnail" alt="' . $image_array[0] . '" src="' . $image_array[0] . '" border="0" ' . $img_height . ' ' . $img_width . '>';

            if ($image and ! preg_match("/wp-content\/uploads/", $image)) {


                $attachment = get_post($media_id);


                $label = basename($attachment->guid);


                $image = $arfieldhelper->get_file_name_link($media_id);

                $image .= '<img class="attachment-thumnail" alt="' . $image_array[0] . '" src="' . $image_array[0] . '" border="0" ' . $img_height . ' ' . $img_width . '></a>';
            } else {
                $image = '<a href="' . $image_array_link . '" target="_blank"><img class="attachment-thumnail" alt="' . $image_array[0] . '" src="' . $image_array[0] . '" border="0" ' . $img_height . ' ' . $img_width . '></a>';
            }


            return $image;
        }

        function get_file_name_link($media_id, $short = true) {
            if (is_numeric($media_id)) {

                if ($short) {

                    $attachment = get_post($media_id);

                    $label = basename($attachment->guid);
                }
                $url = wp_get_attachment_url($media_id);

                $url = str_replace('thumbs/', '', $url);

                if (is_admin()) {
                    global $arfsiteurl;

                    $url = '<a href="' . $url . '">';
                }

                return $url;
            }
        }

        function get_file_name($media_id, $short = true) {


            if (is_numeric($media_id)) {


                if ($short) {


                    $attachment = get_post($media_id);


                    $label = basename($attachment->guid);
                }


                $url = wp_get_attachment_url($media_id);

                $url = str_replace('thumbs/', '', $url);


                if (is_admin()) {


                    global $arfsiteurl;


                    $url = '<a href="' . $url . '">' . $label . '</a>';
                }


                return $url;
            }
        }

        function get_date($date, $date_format = false) {


            if (empty($date))
                return $date;


            if (!$date_format)
                $date_format = get_option('date_format');


            if (preg_match('/^\d{1-2}\/\d{1-2}\/\d{4}$/', $date)) {

                global $style_settings, $armainhelper;

                $date = $armainhelper->convert_date($date, $style_settings->date_format, 'Y-m-d');
            }

            $date = str_replace('/', '-', $date);

            if (strtotime($date) == '')
                return $date;

            return date_i18n($date_format, strtotime($date));
        }

        function get_field_options($form_id, $value = '', $include = 'not', $types = "'break','divider','data','file','captcha'", $data_children = false) {


            global $arffield, $armainhelper;


            $fields = $arffield->getAll("fi.type $include in ($types) and fi.form_id=" . (int) $form_id, 'fi.field_order');


            foreach ($fields as $field) {


                $field->field_options = maybe_unserialize($field->field_options);
                ?>
                <option value="<?php echo $field->id ?>" <?php selected($value, $field->id) ?>><?php echo $armainhelper->truncate($field->name, 50) ?></option>
                <?php
            }
        }

        function value_meets_condition($observed_value, $cond, $hide_opt) {


            if ($hide_opt == '')
                return false;





            if (is_array($observed_value)) {


                if ($cond == '==') {


                    $m = in_array($hide_opt, $observed_value);
                } else if ($cond == '!=') {


                    $m = !in_array($hide_opt, $observed_value);
                } else if ($cond == '>') {


                    $min = min($observed_value);


                    $m = $min > $hide_opt;
                } else if ($cond == '<') {


                    $max = max($observed_value);


                    $m = $max < $hide_opt;
                }
            } else {


                if ($cond == '==')
                    $m = $observed_value == $hide_opt;


                else if ($cond == '!=')
                    $m = $observed_value != $hide_opt;


                else if ($cond == '>')
                    $m = $observed_value > $hide_opt;


                else if ($cond == '<')
                    $m = $observed_value < $hide_opt;
            }


            return $m;
        }

        function get_shortcode_select($form_id, $target_id = 'content', $type = 'all', $style = '') {


            global $arffield, $MdlDb, $armainhelper;


            $field_list = array();


            if (is_numeric($form_id)) {


                $exclude = "'divider','captcha','break','html'";


                if ($type == 'field_opt')
                    $exclude .= ",'data','checkbox'";


                $field_list = $arffield->getAll("fi.type not in (" . $exclude . ") and fi.form_id=" . (int) $form_id, 'field_order');
            }


            $linked_forms = array();
            ?>

            <select class="frm_shortcode_select" onchange="arfaddcodefornewfield('<?php echo $target_id ?>', this.value);
                    this.value = '';" <?php echo (isset($style)) ? $style : ''; ?> data-width='330px'>


                <option value="">- <?php _e('Select a value to insert into the box below', 'ARForms') ?> -</option>


        <?php if ($type != 'field_opt') { ?>







                    <?php
                }


                if (!empty($field_list)) {


                    foreach ($field_list as $field) {


                        $field->field_options = maybe_unserialize($field->field_options);
                        ?>
                        <option value="[<?php echo ($field->ref_field_id > 0 ) ? $field->ref_field_id : $field->id; ?>]"><?php echo $field_name = $armainhelper->truncate($field->name, 60) ?> </option>

                        <?php
                    }
                }


                if ($type != 'field_opt') {
                    ?>


                    </optgroup>



        <?php } ?>


            </select>    


            <?php
        }

        function replace_shortcodes($content, $entry, $shortcodes, $display = false, $show = 'one', $odd = '') {


            global $arffield, $arfrecordmeta, $post, $style_settings, $armainhelper, $arfieldhelper, $arrecordhelper, $arrecordcontroller;

            if (is_array($shortcodes[0])) {
                foreach ($shortcodes[0] as $short_key => $tag) {


                    $conditional = false;


                    $atts = shortcode_parse_atts($shortcodes[3][$short_key]);





                    if (!empty($shortcodes[3][$short_key])) {


                        if ($conditional)
                            $tag = str_replace('[if ', '', $shortcodes[0][$short_key]);
                        else
                            $tag = str_replace('[', '', $shortcodes[0][$short_key]);


                        $tag = str_replace(']', '', $tag);


                        $tags = explode(' ', $tag);


                        if (is_array($tags))
                            $tag = $tags[0];
                    } else
                        $tag = $shortcodes[2][$short_key];





                    switch ($tag) {


                        case 'detaillink':


                            if ($display and $detail_link)
                                $content = str_replace($shortcodes[0][$short_key], $detail_link, $content);


                            break;


                        case 'id':


                            $content = str_replace($shortcodes[0][$short_key], $entry->id, $content);


                            break;


                        case 'post-id':


                        case 'attachment_id':


                            $content = str_replace($shortcodes[0][$short_key], $entry->attachment_id, $content);


                            break;





                        case 'key':


                            $content = str_replace($shortcodes[0][$short_key], $entry->entry_key, $content);


                            break;





                        case 'ip_address':


                            $content = str_replace($shortcodes[0][$short_key], $entry->ip_address, $content);


                            break;





                        case 'user_agent':


                        case 'user-agent':


                            $entry->description = maybe_unserialize($entry->description);


                            $content = str_replace($shortcodes[0][$short_key], $entry->description['browser'], $content);


                            break;





                        case 'created-at':


                        case 'updated-at':


                        case 'evenodd':


                            $content = str_replace($shortcodes[0][$short_key], $odd, $content);


                            break;





                        case 'siteurl':


                            global $arfsiteurl;


                            $content = str_replace($shortcodes[0][$short_key], $arfsiteurl, $content);


                            break;





                        case 'sitename':


                            $content = str_replace($shortcodes[0][$short_key], get_option('blogname'), $content);


                            break;





                        case 'get':


                            if (isset($atts['param'])) {


                                $param = $atts['param'];


                                $replace_with = $armainhelper->get_param($param);


                                if (is_array($replace_with))
                                    $replace_with = implode(', ', $replace_with);





                                $content = str_replace($shortcodes[0][$short_key], $replace_with, $content);


                                unset($param);


                                unset($replace_with);
                            }


                            break;





                        default:


                            if ($tag == 'deletelink') {
                                
                            } else {


                                $field = $arffield->getOne($tag);
                            }





                            $sep = (isset($atts['sep'])) ? $atts['sep'] : ', ';





                            if (!isset($field))
                                $field = false;





                            if ($field) {


                                $field->field_options = maybe_unserialize($field->field_options);


                                $replace_with = $arrecordhelper->get_post_or_entry_value($entry, $field, $atts);


                                $replace_with = maybe_unserialize($replace_with);


                                $atts['entry_id'] = $entry->id;


                                $atts['entry_key'] = $entry->entry_key;


                                $atts['attachment_id'] = $entry->attachment_id;


                                $replace_with = apply_filters('arffieldsreplaceshortcodes', $replace_with, $tag, $atts, $field);
                            }





                            if (isset($replace_with) and is_array($replace_with))
                                $replace_with = implode($sep, $replace_with);





                            if ($field and $field->type == 'file') {



                                $size = (isset($atts['size'])) ? $atts['size'] : 'thumbnail';


                                if ($size != 'id')
                                    $replace_with = $arfieldhelper->get_media_from_id($replace_with, $size);
                            }




                            if ($field) {


                                if (isset($atts['show']) and $atts['show'] == 'field_label') {


                                    $replace_with = stripslashes($field->name);
                                } else if (empty($replace_with) and $replace_with != '0') {


                                    $replace_with = '';


                                    if ($field->type == 'number')
                                        $replace_with = '0';
                                }else {


                                    $replace_with = $arfieldhelper->get_display_value($replace_with, $field, $atts);
                                }
                            }





                            if (isset($atts['sanitize']))
                                $replace_with = sanitize_title_with_dashes($replace_with);





                            if (isset($atts['sanitize_url']))
                                $replace_with = urlencode(htmlentities($replace_with));





                            if (isset($atts['truncate'])) {


                                if (isset($atts['more_text'])) {


                                    $more_link_text = $atts['more_text'];
                                } else
                                    $more_link_text = (isset($atts['more_link_text'])) ? $atts['more_link_text'] : '. . .';





                                if ($display and $show == 'all') {


                                    $more_link_text = ' <a href="' . $detail_link . '">' . $more_link_text . '</a>';


                                    $replace_with = $armainhelper->truncate($replace_with, (int) $atts['truncate'], 3, $more_link_text);
                                } else {


                                    $replace_with = wp_specialchars_decode(strip_tags($replace_with), ENT_QUOTES);


                                    $part_one = substr($replace_with, 0, (int) $atts['truncate']);


                                    $part_two = substr($replace_with, (int) $atts['truncate']);


                                    $replace_with = $part_one . '<a onclick="jQuery(this).next().css(\'display\', \'inline\');jQuery(this).css(\'display\', \'none\')" class="frm_text_exposed_show"> ' . $more_link_text . '</a><span style="display:none;">' . $part_two . '</span>';
                                }
                            }





                            if (isset($atts['clickable']))
                                $replace_with = make_clickable($replace_with);





                            if (!isset($replace_with))
                                $replace_with = '';





                            $content = str_replace($shortcodes[0][$short_key], $replace_with, $content);







                            unset($replace_with);





                            if (isset($field))
                                unset($field);
                    }


                    unset($atts);


                    unset($conditional);
                }
            }


            return $content;
        }

        function get_media_from_id($replace_with, $size = 'thumbnail') {


            if ($size == 'label') {


                $attachment = get_post($replace_with);


                $replace_with = basename($attachment->guid);
            } else {


                $image = wp_get_attachment_image_src($replace_with, $size);



                if ($image)
                    $replace_with = $image[0];
                else
                    $replace_with = wp_get_attachment_url($replace_with);
            }





            return $replace_with;
        }

        function get_display_value($replace_with, $field, $atts = array()) {

            global $armainhelper, $arfieldhelper;

            $sep = (isset($atts['sep'])) ? $atts['sep'] : ', ';


            if ($field->type == 'date') {


                if (isset($atts['time_ago']))
                    $atts['format'] = 'Y-m-d H:i:s';





                if (!isset($atts['format']))
                    $atts['format'] = false;





                $replace_with = $arfieldhelper->get_date($replace_with, $atts['format']);





                if (isset($atts['time_ago']))
                    $replace_with = $armainhelper->human_time_diff(strtotime($replace_with), strtotime(date_i18n('Y-m-d')));
            }else if (is_numeric($replace_with) and $field->type == 'file') {


                $size = (isset($atts['size'])) ? $atts['size'] : 'thumbnail';


                if ($size != 'id')
                    $replace_with = $arfieldhelper->get_media_from_id($replace_with, $size);
            }else if ($field->type == 'textarea') {


                $autop = isset($atts['wpautop']) ? $atts['wpautop'] : true;


                if (apply_filters('arfusewpautop', $autop))
                    $replace_with = wpautop($replace_with);


                unset($autop);
            }else if ($field->type == 'number') {


                if (!isset($atts['decimal'])) {


                    $num = explode('.', $replace_with);


                    $atts['decimal'] = (isset($num[1])) ? strlen($num[1]) : 0;
                }





                if (!isset($atts['dec_point']))
                    $atts['dec_point'] = '.';





                if (!isset($atts['thousands_sep']))
                    $atts['thousands_sep'] = '';





                $replace_with = number_format($replace_with, $atts['decimal'], $atts['dec_point'], $atts['thousands_sep']);
            }

            $replace_with = stripslashes_deep($replace_with);


            return $replace_with;
        }

        function get_table_options($field_options) {


            $columns = array();


            $rows = array();


            if (is_array($field_options)) {


                foreach ($field_options as $opt_key => $opt) {


                    switch (substr($opt_key, 0, 3)) {


                        case 'col':


                            $columns[$opt_key] = $opt;


                            break;


                        case 'row':


                            $rows[$opt_key] = $opt;


                            break;
                    }
                }
            }


            return array($columns, $rows);
        }

        function set_table_options($field_options, $columns, $rows) {


            if (is_array($field_options)) {


                foreach ($field_options as $opt_key => $opt) {


                    if (substr($opt_key, 0, 3) == 'col' or substr($opt_key, 0, 3) == 'row')
                        unset($field_options[$opt_key]);
                }
            } else
                $field_options = array();





            foreach ($columns as $opt_key => $opt)
                $field_options[$opt_key] = $opt;





            foreach ($rows as $opt_key => $opt)
                $field_options[$opt_key] = $opt;





            return $field_options;
        }

        function show_default_blank_js($field_id, $default_blank) {
            
        }

        //---------- for conditional logic ----------//
        function arf_cl_field_menu($form_id, $select_name, $select_id = '', $default_field_id = 0) {

            global $arffield, $arfieldhelper;

            if (empty($form_id) or ! $form_id)
                return false;

            $fields = $arffield->getAll("fi.type not in ('divider', 'captcha', 'break', 'html', 'file', 'imagecontrol') and fi.form_id=" . (int) $form_id, 'field_order');

            if (count($fields) > 0) {

                $select_id = (isset($select_id)) ? $select_id : $select_name;

                //echo '<select name="'.$select_name.'" id="'.$select_id.'" class="field_dropdown_menu" data-size="10" data-width="150px">';	
                $arf_cl_field_selected_option = array();
                $arf_cl_field_options = '';
                $cntr = 0;
                foreach ($fields as $field) {

                    $field_id = $arfieldhelper->get_actual_id($field->id);

                    if (( $default_field_id != 0 and $default_field_id == $field_id ) || ( $cntr == 0 )) {
                        $arf_cl_field_selected_option['field_id'] = $field_id;
                        $arf_cl_field_selected_option['name'] = $field->name;
                    }

                    $arf_cl_field_options .= '<li class="arf_selectbox_option" data-value="' . $field_id . '" data-label="' . $field->name . '">' . $field->name . '</li>';
                    $cntr++;
                }
                //echo '</select>';

                echo '<input id="' . $select_id . '" name="' . $select_name . '" value="' . $arf_cl_field_selected_option['field_id'] . '" type="hidden">
				  <dl class="arf_selectbox" data-name="' . $select_name . '" data-id="' . $select_id . '" style="width:130px;">
				  	<dt><span>' . $arf_cl_field_selected_option['name'] . '</span>
					<input value="' . $arf_cl_field_selected_option['name'] . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
					<i class="fa fa-caret-down fa-lg"></i></dt>
				  	<dd>
						<ul class="field_dropdown_menu" style="display: none;" data-id="' . $select_id . '">
					  		' . $arf_cl_field_options . '
						</ul>
				  	</dd>
				  </dl>';
            }
        }

        function arf_cl_rule_menu($select_name, $select_id = '', $default_rule = 'is') {

            $conditional_rules = array(
                'is' => __('equals', 'ARForms'),
                'is not' => __('not equals', 'ARForms'),
                'greater than' => __('greater than', 'ARForms'),
                'less than' => __('less than', 'ARForms'),
                'contains' => __('contains', 'ARForms'),
                'not contains' => __('not contains', 'ARForms'),
            );

            $select_id = (isset($select_id)) ? $select_id : $select_name;

            //echo '<select name="'.$select_name.'" class="operator_dropdown_menu" id="'.$select_id.'" data-width="150px">';
            $arf_cl_field_selected_option = array();
            $arf_cl_field_options = '';
            $cntr = 0;
            foreach ($conditional_rules as $rule_id => $rule) {

                if (( isset($default_rule) and $default_rule == $rule_id ) || ( $cntr == 0 )) {
                    //echo '<option value="'.$rule_id.'" selected="selected">'.$rule.'</option>';
                    $arf_cl_field_selected_option['rule_id'] = $rule_id;
                    $arf_cl_field_selected_option['rule'] = $rule;
                }
                //echo '<option value="'.$rule_id.'">'.$rule.'</option>';
                $arf_cl_field_options .= '<li class="arf_selectbox_option" data-value="' . $rule_id . '" data-label="' . $rule . '">' . $rule . '</li>';
                $cntr++;
            }
            //echo '</select>';

            echo '<input id="' . $select_id . '" name="' . $select_name . '" value="' . $arf_cl_field_selected_option['rule_id'] . '" type="hidden">
				  <dl class="arf_selectbox" data-name="' . $select_name . '" data-id="' . $select_id . '" style="width:130px;">
				  	<dt><span>' . $arf_cl_field_selected_option['rule'] . '</span>
					<input value="' . $arf_cl_field_selected_option['rule'] . '" style="display:none;width:118px;" class="arf_autocomplete" type="text">
					<i class="fa fa-caret-down fa-lg"></i></dt>
				  	<dd>
						<ul class="operator_dropdown_menu" style="display: none;" data-id="' . $select_id . '">
					  		' . $arf_cl_field_options . '
						</ul>
				  	</dd>
				  </dl>';
        }

        function get_actual_id($field_id) {
            global $wpdb, $MdlDb;
            $res = $wpdb->get_results($wpdb->prepare("select ref_field_id from " . $MdlDb->fields . " where id = %d", $field_id));
            if(isset($res) && $res != "" && is_array($res) && isset($res[0]))
                $res = $res[0];

            $id = ( isset($res) and is_object($res) and isset($res->ref_field_id) and $res->ref_field_id > 0 ) ? $res->ref_field_id : $field_id;
            return $id;
        }

        function get_field_type($filed_id = '') {

            if (empty($filed_id) or $filed_id == '')
                return false;

            global $wpdb, $MdlDb;
            $res = $wpdb->get_results($wpdb->prepare("SELECT id, type FROM " . $MdlDb->fields . " WHERE id = %d", $filed_id));
            $res = $res[0];

            return $res->type;
        }

        function get_onchage_func($field = '') {

            if (empty($field) or $field == '' or is_admin())
                return false;

            $returnstring = "";
            $conditional_change_fnc = "";
            $runningtotal_change_fnc = "";

            global $arfieldhelper;

            $field['id'] = $arfieldhelper->get_actual_id($field['id']);
            global $wpdb, $MdlDb;
            if ($field['form_id'] >= 10000)
                $form = $wpdb->get_results($wpdb->prepare("SELECT id, form_key, options FROM " . $MdlDb->ref_forms . " WHERE id = %d", $field['form_id']));
            else
                $form = $wpdb->get_results($wpdb->prepare("SELECT id, form_key, options FROM " . $MdlDb->forms . " WHERE id = %d", $field['form_id']));

            $form = $form[0];

            $res = $wpdb->get_results($wpdb->prepare("SELECT id, type, field_options, description, conditional_logic FROM " . $MdlDb->fields . " WHERE form_id = %d ORDER BY field_order", $field['form_id']));

            $string = '';
            $stringfnc = '';
            foreach ($res as $data) {
                // for conditional logic
                $conditional_logic = maybe_unserialize($data->conditional_logic);
                if (isset($conditional_logic['enable']) and $conditional_logic['enable'] == 1) {
                    if (count($conditional_logic['rules']) > 0) {

                        foreach ($conditional_logic['rules'] as $val) {

                            if ($val['field_id'] == $field['id']) {
                                $data->id = $arfieldhelper->get_actual_id($data->id);
                                $string .= $data->id . ',';
                            }
                        }
                    }
                }
                // for conditional logic end
                //for Running Total
                $field_options = maybe_unserialize($data->field_options);
                if ($data->type == 'html' && $field_options['enable_total'] == 1) {
                    $regex = '/<arftotal>(.*?)<\/arftotal>/is';

                    preg_match($regex, $data->description, $arftotalmatches);

                    if ($arftotalmatches) {
                        $regexp = $arftotalmatches[1];

                        if ($arfieldhelper->arf_is_field_inregexp($regexp, $field['id'])) {
                            $data->id = $arfieldhelper->get_actual_id($data->id);
                            $stringfnc .= $data->id . ',';
                        }
                    }
                }
                //for Running Total end
            }

            $formoptions = maybe_unserialize($form->options);
            $submit_conditional_logic = $formoptions['submit_conditional_logic'] ? $formoptions['submit_conditional_logic'] : array();

            if (isset($submit_conditional_logic['enable']) and $submit_conditional_logic['enable'] == 1) {
                if (count($submit_conditional_logic['rules']) > 0) {

                    foreach ($submit_conditional_logic['rules'] as $val) {

                        if ($val['field_id'] == $field['id']) {
                            //$data->id = $arfieldhelper->get_actual_id($data->id);
                            $string .= "'arfsubmit',"; //$data->id.',';
                        }
                    }
                }
            }

            if ($string != '') {

                $string = rtrim($string, ',');

                $conditional_change_fnc = ' arf_rule_apply(\'' . $form->form_key . '\', \'' . $field['id'] . '\', [' . $string . ']);';
            }

            if ($stringfnc != '') {

                $stringfnc = rtrim($stringfnc, ',');

                $runningtotal_change_fnc = ' arf_calculate_total(\'' . $form->form_key . '\', \'' . $field['id'] . '\', [' . $stringfnc . ']);';
            }


            if ($conditional_change_fnc != '' || $runningtotal_change_fnc != '') {
                if ($field['type'] == 'checkbox' || $field['type'] == 'radio' || $field['type'] == 'like') {
                    if ($field['type'] == 'radio')
                        return ' onclick="' . $conditional_change_fnc . $runningtotal_change_fnc . '" ';
                    else
                        return ' onchange="' . $conditional_change_fnc . $runningtotal_change_fnc . '" ';
                } else if ($field['type'] == 'select' || $field['type'] == 'time' || $field['type'] == 'scale' || $field['type'] == 'slider') {
                    return ' onchange="' . $conditional_change_fnc . $runningtotal_change_fnc . '" ';
                } else {
                    return ' onchange="' . $conditional_change_fnc . $runningtotal_change_fnc . '" onkeyup="setTimeout(function(){' . $conditional_change_fnc . $runningtotal_change_fnc . '}, 100);" ';
                }
            } else
                return '';
        }

        function get_form_logic_rules($form_id, $form_key) {

            if (empty($form_id) || $form_id == '' || empty($form_key))
                return false;

            global $wpdb, $MdlDb, $arfieldhelper;
            $res = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $MdlDb->fields . " WHERE form_id = %d ORDER BY field_order", $form_id));

            $logic_rules = '';
            $page_no = 0;
            foreach ($res as $data) {

                if ($data->type == 'break')
                    $page_no++;

                $conditional_logic = maybe_unserialize($data->conditional_logic);

                if (isset($conditional_logic['enable']) and $conditional_logic['enable'] == 1) {

                    if (count($conditional_logic['rules']) > 0) {

                        $string = '';
                        foreach ($conditional_logic['rules'] as $val) {

                            $string .= " { 'rule_no' : '" . $val['id'] . "', 'field_id' : '" . $val['field_id'] . "', 'field_type' : '" . $val['field_type'] . "', 'operator' : '" . $val['operator'] . "', 'value' : '" . addslashes($val['value']) . "' },";
                        }

                        $string = rtrim($string, ',');

                        $default_value = $arfieldhelper->get_field_defautl_value($data);

                        $logic_rules .= (($data->ref_field_id > 0) ? $data->ref_field_id : $data->id) . " : { 'display': '" . $conditional_logic['display'] . "', 'if_cond': '" . $conditional_logic['if_cond'] . "', 'field_type': '" . $data->type . "', 'field_key': '" . $data->field_key . "', 'default_value': " . $default_value . ", 'page': '" . $page_no . "', 'rules':[" . $string . "] }, ";
                    }
                }
            }

            // for submit conditional logic

            if ($form_id >= 10000)
                $form = $wpdb->get_results($wpdb->prepare("SELECT options FROM " . $MdlDb->ref_forms . " WHERE id = %d", $form_id));
            else
                $form = $wpdb->get_results($wpdb->prepare("SELECT options FROM " . $MdlDb->forms . " WHERE id = %d", $form_id));

            $form = $form[0];

            $formoptions = maybe_unserialize($form->options);
            $submit_conditional_logic = $formoptions['submit_conditional_logic'] ? $formoptions['submit_conditional_logic'] : array();

            if (isset($submit_conditional_logic['enable']) and $submit_conditional_logic['enable'] == 1) {
                if (count($submit_conditional_logic['rules']) > 0) {
                    $string = '';
                    foreach ($submit_conditional_logic['rules'] as $val) {

                        $string .= " { 'rule_no' : '" . $val['id'] . "', 'field_id' : '" . $val['field_id'] . "', 'field_type' : '" . $val['field_type'] . "', 'operator' : '" . $val['operator'] . "', 'value' : '" . $val['value'] . "' },";
                    }

                    $string = rtrim($string, ',');

                    $default_value = ''; //$arfieldhelper->get_field_defautl_value( $data ); 

                    $logic_rules .= "'arfsubmit' : { 'display': '" . $submit_conditional_logic['display'] . "', 'if_cond': '" . $submit_conditional_logic['if_cond'] . "', 'field_type': 'submit', 'field_key': '" . $form_key . "', 'default_value': '" . $default_value . "', 'page': '" . $page_no . "', 'rules':[" . $string . "] }, ";
                }
            }


            if (isset($logic_rules) and $logic_rules != '') {

                return '<div><script type="text/javascript" language="javascript">if(window[\'jQuery\']){ if(!window[\'arf_cl\']) window[\'arf_cl\'] = new Array(); window[\'arf_cl\'][\'' . $form_key . '\'] = { ' . $logic_rules . ' }; }</script></div>';
            }

            return '';
        }

        function get_field_defautl_value($field) {
            global $armainhelper;
            if (!$field)
                return;

            $field = (array) $field;

            $value1 = '';

            $field_options = maybe_unserialize($field['field_options']);
            $field['default_value'] = isset($field['default_value']) ? $field['default_value'] : '';

            $field_options['default_blank'] = isset($field_options['default_blank']) ? $field_options['default_blank'] : '';

            if ((isset($field_options['clear_on_focus']) and $field_options['clear_on_focus'] and ! empty($field['default_value']))) {

                if ($field_options['default_blank'] == 1) {
                    $value1 = trim($armainhelper->esc_textarea($field['default_value']));
                }
            } else {

                if ($field_options['default_blank'] == 1) {
                    $value1 = trim($armainhelper->esc_textarea($field['default_value']));
                }
            }


            //for star rating
            if ($field['type'] == 'scale') {
                $value1 = ( isset($field['default_value']) and $field['default_value'] != '' ) ? $field['default_value'] : '';
            }
            //for star rating
            // for radio and select
            if ($field['type'] == 'radio' || $field['type'] == 'select') {

                $field_options = maybe_unserialize($field['options']);

                foreach ($field_options as $opt_key => $opt) {
                    $field_val = $opt;
                    if (is_array($opt)) {
                        $opt = $opt['label'];
                        $field_val = isset($field_options['separate_value']) ? $field_val['value'] : $opt;
                    }
                    if ($field['default_value'] == $field_val)
                        $value1 = addslashes($field_val);
                }
            }
            // for radio and select
            // for checkbox	
            if ($field['type'] == 'checkbox') {

                $field_options = maybe_unserialize($field['options']);

                $default_value = maybe_unserialize($field['default_value']);

                foreach ($field_options as $opt_key => $opt) {
                    $field_val = $opt;

                    if (is_array($opt)) {
                        $opt = $opt['label'];
                        $field_val = isset($field_options['separate_value']) ? $field_val['value'] : $opt;
                    }
                }

                if ($default_value && is_array($default_value)) {
                    $str_for_check = "[";
                    foreach ($default_value as $chk_value) {
                        $value1 = $chk_value; //trim( strtolower($chk_value) );
                        $str_for_check .= "'" . addslashes($value1) . "', ";
                    }
                    $str_for_check = rtrim($str_for_check, ', ');
                    $str_for_check .= "]";
                    return $str_for_check;
                } else {
                    return "''";
                }
            }

            // for hidden
            if ($field['type'] == 'hidden' || $field['type'] == 'like') {
                $value1 = $field['default_value'];
            }

            if ($field['type'] == 'slider') {
                $field['slider_value'] = isset($field_options['slider_value']) ? $field_options['slider_value'] : '';
                $value1 = ($field['slider_value'] != '') ? $field['slider_value'] : ( is_numeric($field_options['minnum']) ? $field_options['minnum'] : 1 );
            }

            return "'" . $value1 . "'";
        }

        function get_display_style($field = '') {

            global $wpdb, $MdlDb, $arfieldhelper;
            $res = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $MdlDb->fields . " WHERE form_id = %d  ORDER BY field_order", $field['form_id']), OBJECT_K);
            $style = '';
            $field['id'] = $arfieldhelper->get_actual_id($field['id']);

            foreach ($res as $data) {

                $data->id = $arfieldhelper->get_actual_id($data->id);

                if ($field['id'] == $data->id) {

                    $conditional_logic = maybe_unserialize($data->conditional_logic);

                    if (isset($conditional_logic['enable']) and $conditional_logic['enable'] == 1) {

                        if (count($conditional_logic['rules']) > 0) {

                            $matched = 0;
                            $rule_cout = count($conditional_logic['rules']);
                            foreach ($conditional_logic['rules'] as $val) {

                                foreach ($res as $data_field) {
                                    $data_field->id = $arfieldhelper->get_actual_id($data_field->id);
                                    if ($data_field->id == $val['field_id'])
                                        $res_field_send = $data_field;
                                }
                                if ($arfieldhelper->calculate_rule($res_field_send, $val['operator'], $val['value']))
                                    $matched++;
                            }

                            if (($conditional_logic['if_cond'] == 'all' && $rule_cout == $matched) || ($conditional_logic['if_cond'] == 'any' && $matched > 0))
                                $style = ($conditional_logic['display'] == 'hide') ? 'style="display:none;"' : '';
                            else
                                $style = ($conditional_logic['display'] == 'show') ? 'style="display:none;"' : '';
                        }
                    }
                }
            }


            return $style;
        }

        function get_display_style_submit($form) {
            $style = '';
            global $wpdb, $MdlDb, $arfieldhelper;
            $res = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $MdlDb->fields . " WHERE form_id = %d  ORDER BY field_order", $form->id), OBJECT_K);

            $formoptions = maybe_unserialize($form->options);

            $submit_conditional_logic = $formoptions['submit_conditional_logic'] ? $formoptions['submit_conditional_logic'] : array();

            if (isset($submit_conditional_logic['enable']) and $submit_conditional_logic['enable'] == 1) {

                if (count($submit_conditional_logic['rules']) > 0) {

                    $matched = 0;
                    $rule_cout = count($submit_conditional_logic['rules']);
                    foreach ($submit_conditional_logic['rules'] as $val) {

                        foreach ($res as $data_field) {
                            $data_field->id = $arfieldhelper->get_actual_id($data_field->id);
                            if ($data_field->id == $val['field_id'])
                                $res_field_send = $data_field;
                        }
                        if ($arfieldhelper->calculate_rule($res_field_send, $val['operator'], $val['value']))
                            $matched++;
                    }

                    if (($submit_conditional_logic['if_cond'] == 'all' && $rule_cout == $matched) || ($submit_conditional_logic['if_cond'] == 'any' && $matched > 0))
                        $style = ($submit_conditional_logic['display'] == 'hide') ? 'style="display:none;"' : '';
                    else
                        $style = ($submit_conditional_logic['display'] == 'show') ? 'style="display:none;"' : '';
                }
            }

            return $style;
        }

        function calculate_rule($field, $operator, $value) {

            global $armainhelper, $arfieldhelper;

            $field = (array) $field;

            $value1 = '';
            $value2 = isset($value) ? $value : '';

            $field_options = maybe_unserialize($field['field_options']);
            $field['default_value'] = isset($field['default_value']) ? $field['default_value'] : '';
            $field_options['default_blank'] = isset($field_options['default_blank']) ? $field_options['default_blank'] : '';

            if ((isset($field_options['clear_on_focus']) and $field_options['clear_on_focus'] and ! empty($field['default_value']))) {

                if ($field_options['default_blank'] == 1) {
                    $value1 = trim($armainhelper->esc_textarea($field['default_value']));
                }
            } else {

                if ($field_options['default_blank'] == 1) {
                    $value1 = trim($armainhelper->esc_textarea($field['default_value']));
                }
            }

            //for star rating
            if ($field['type'] == 'scale') {
                $value1 = ( isset($field['default_value']) and $field['default_value'] != '' ) ? $field['default_value'] : '';
            }
            //for star rating
            // for radio and select
            if ($field['type'] == 'radio' || $field['type'] == 'select') {

                $fieldoptions = maybe_unserialize($field['options']);

                foreach ($fieldoptions as $opt_key => $opt) {
                    $field_val = $opt;
                    if (is_array($opt)) {
                        $opt = $opt['label'];
                        $field_val = ($field_options['separate_value']) ? $field_val['value'] : $opt;
                    }
                    if ($field['default_value'] == $field_val)
                        $value1 = $field_val;
                }
            }
            // for radio and select
            // for checkbox	
            if ($field['type'] == 'checkbox') {

                $fieldoptions = maybe_unserialize($field['options']);

                $default_value = maybe_unserialize($field['default_value']);

                foreach ($fieldoptions as $opt_key => $opt) {
                    $field_val = $opt;

                    if (is_array($opt)) {
                        $opt = $opt['label'];
                        $field_val = ($field_options['separate_value']) ? $field_val['value'] : $opt;
                    }
                }
            }

            // for hidden
            if ($field['type'] == 'hidden' || $field['type'] == 'like') {
                $value1 = $field['default_value'];
            }

            if ($field['type'] == 'slider') {
                $field['slider_value'] = isset($field_options['slider_value']) ? $field_options['slider_value'] : '';
                $value1 = ($field['slider_value'] != '') ? $field['slider_value'] : ( is_numeric($field_options['minnum']) ? $field_options['minnum'] : 1 );
            }

            $value1 = trim(strtolower($value1));

            $value2 = trim(strtolower($value2));

            if ($field['type'] == 'checkbox') {
                $chk = 0;
                if ($default_value && is_array($default_value)) {
                    foreach ($default_value as $chk_value) {
                        $value1 = trim(strtolower($chk_value));
                        if ($arfieldhelper->ar_match_rule($value1, $value2, $operator))
                            $chk++;
                    }
                }

                if ($chk > 0)
                    return true;
                else
                    return false;
            } else {

                return $arfieldhelper->ar_match_rule($value1, $value2, $operator);
            }
        }

        function ar_match_rule($value1, $value2, $operator) {
            // for checkbox
            switch ($operator) {

                case 'is':
                    return $value1 == $value2;
                    break;

                case 'is not':
                    return $value1 != $value2;
                    break;

                case 'greater than':
                    $value1 = floatval($value1);
                    $value2 = floatval($value2);

                    return $value1 > $value2;
                    break;

                case 'less than':
                    $value1 = floatval($value1);
                    $value2 = floatval($value2);
                    return $value1 < $value2;
                    break;
                case 'contains':
                    if ($value1 != '' && empty($value2))
                        return false;
                    else if (empty($value1) && $value2 != '')
                        return false;
                    else if (empty($value1) && empty($value2))
                        return true;
                    else if ($value1 != '' && $value2 != '')
                        return ( strpos($value1, $value2) !== FALSE ) ? true : false;
                    break;

                case 'not contains':
                    if ($value1 != '' && empty($value2))
                        return true;
                    else if (empty($value1) && $value2 != '')
                        return true;
                    else if (empty($value1) && empty($value2))
                        return false;
                    else if ($value1 != '' && $value2 != '')
                        return ( strpos($value1, $value2) !== FALSE ) ? false : true;
                    break;
            }

            return false;
        }

        //---------- for conditional logic ----------//
        function get_shortcode_modal($form_id, $target_id = 'content', $type = 'all', $style = '', $is_total_field = false) {



            global $arffield, $MdlDb, $armainhelper;


            $field_list = array();


            if (is_numeric($form_id)) {


                $exclude = "'divider','captcha','break'";


                if ($type == 'field_opt')
                    $exclude .= ",'data','checkbox'";


                if ($target_id == 'admin_email_subject' or $target_id == 'ar_email_subject') {
                    $exclude .= ",'html','file','like'";
                }

                $field_list = $arffield->getAll("fi.type not in (" . $exclude . ") and fi.form_id=" . (int) $form_id, 'field_order');
            }


            $linked_forms = array();
            ?>



        <?php if ($type != 'field_opt') { ?>







                <?php
            }


            if (!empty($field_list)) {


                foreach ($field_list as $field) {


                    $field->field_options = maybe_unserialize($field->field_options);


                    if ($type == 'email' && $target_id != 'options_admin_reply_to_notification' && $target_id != 'ar_admin_from_email' && $target_id != 'ar_user_from_email' && $target_id != 'admin_email_subject') {
                        if ($field->type == 'email' || $field->type == 'text') {
                            ?>
                            <div class="modal_field_val" id="arfmodalfieldval_<?php echo $field->ref_field_id; ?>" onclick="arfaddcodefornewfield('<?php echo $target_id; ?>', '<?php echo $field->ref_field_id; ?>')"><?php echo $field_name = $armainhelper->truncate($field->name, 60) . '</div>'; ?>
                                <?php
                            }
                        } else if ($type == 'email' && ( $target_id == 'options_admin_reply_to_notification' || $target_id == 'ar_admin_from_email' || $target_id == 'ar_user_from_email' )) {

                            if ($field->type == 'email' || $field->type == 'text' || $field->type == 'radio' || $field->type == 'select' || $field->type == 'hidden') {
                                ?>
                                <div class="modal_field_val" id="arfmodalfieldval_<?php echo $field->ref_field_id; ?>" onclick="arfaddcodefornewfield('<?php echo $target_id; ?>', '<?php echo $field->ref_field_id; ?>')"><?php echo $field_name = $armainhelper->truncate($field->name, 60) . '</div>'; ?>
                                    <?php
                                }
                            } else {
                                if ($is_total_field) {
                                    if ($field->type != 'html') {
                                        ?><div class="modal_field_val" id="arfmodalfieldval_<?php echo $field->ref_field_id; ?>" onclick="arfaddtotalfield('<?php echo $target_id; ?>', '<?php echo $field->ref_field_id; ?>', '')"><?php
                                        echo $field_name = $armainhelper->truncate($field->name, 60) . '</div>';
                                    }
                                } else {

                                    if (( $target_id == "ar_email_subject" || $target_id == 'admin_email_subject' ) && $field->type != 'html') {
                                        ?><div class="modal_field_val" id="arfmodalfieldval_<?php echo $field->ref_field_id; ?>" onclick="arfaddcodefornewfield('<?php echo $target_id; ?>', '<?php echo $field->ref_field_id; ?>')"><?php
                                            echo $field_name = $armainhelper->truncate($field->name, 60) . '</div>';
                                        } else if ($target_id != "ar_email_subject") {
                                            ?><div class="modal_field_val" id="arfmodalfieldval_<?php echo $field->ref_field_id; ?>" onclick="arfaddcodefornewfield('<?php echo $target_id; ?>', '<?php echo $field->ref_field_id; ?>')"><?php
                                                echo $field_name = $armainhelper->truncate($field->name, 60) . '</div>';
                                            }
                                        }
                                    }
                                }
                            }


                            if ($type != 'field_opt') {
                                ?>



        <?php } ?>


                                <?php
                            }

                            function get_shortcode_total_modal($form_id, $target_id = 'content', $type = 'all', $style = '', $is_total_field = false) {


                                global $arffield, $MdlDb, $armainhelper;


                                $field_list = array();


                                if (is_numeric($form_id)) {

                                    $exclude = "'divider','captcha','break','html'";

                                    $field_list = $arffield->getAll("fi.type not in (" . $exclude . ") and fi.form_id=" . (int) $form_id, 'field_order');
                                }


                                $linked_forms = array();


                                if (!empty($field_list)) {

                                    foreach ($field_list as $field) {

                                        if ($field->type == "checkbox") {
                                            $choices = maybe_unserialize($field->options);

                                            $field_opts = maybe_unserialize($field->field_options);

                                            $is_sep_val = $field_opts['separate_value'];
                                            ?>
                                            <div class="modal_field_val" id="arfmodalfieldval_<?php echo $field->ref_field_id; ?>" onclick="javascript:return false;"><strong><?php
                                                    echo $field_name = $armainhelper->truncate($field->name, 40) . '</div></strong>';

                                                    $inc = 0;
                                                    foreach ($choices as $choice) {
                                                        if ($is_sep_val == 0) {
                                                            if (is_array($choice)) {
                                                                $choice = $choice['label'];
                                                            }
                                                            ?>
                                                            <div class="modal_field_val" id="arfmodalfieldval_<?php echo $field->ref_field_id . '_' . $inc; ?>" onclick="arfaddtotalfield(this, '<?php echo $field->ref_field_id; ?>', '<?php echo $inc; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $field_name = $armainhelper->truncate($choice, 40); ?></div>
                                                            <?php
                                                        } else {
                                                            if (is_array($choice)) {
                                                                $choice = $choice['label'];
                                                            }
                                                            ?>
                                                            <div class="modal_field_val" id="arfmodalfieldval_<?php echo $field->ref_field_id . '_' . $inc; ?>" onclick="arfaddtotalfield(this, '<?php echo $field->ref_field_id; ?>', '<?php echo $inc; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $field_name = $armainhelper->truncate($choice, 40); ?></div>
                                                            <?php
                                                        }
                                                        $inc++;
                                                    }
                                                } else {
                                                    $field->field_options = maybe_unserialize($field->field_options);
                                                    ?>
                                                    <div class="modal_field_val" id="arfmodalfieldval_<?php echo $field->ref_field_id; ?>" onclick="arfaddtotalfield(this, '<?php echo $field->ref_field_id; ?>', '')"><?php echo $field_name = $armainhelper->truncate($field->name, 40); ?></div> 

                                                    <?php
                                                }
                                            }
                                        }
                                    }

                                    function replace_description_shortcode($field) {

                                        global $arformcontroller;

                                        $code = 'description';
                                        $value = $field['description'];

                                        $html = '[if description]<div class="arf_field_description" [description_style]>[description]</div>[/if description]';

                                        if (!$value or $value == '')
                                            $html = preg_replace('/(\[if\s+' . $code . '\])(.*?)(\[\/if\s+' . $code . '\])/mis', '', $html);
                                        else {
                                            $html = str_replace('[if ' . $code . ']', '', $html);
                                            $html = str_replace('[/if ' . $code . ']', '', $html);
                                        }
                                        $html = str_replace('[' . $code . ']', $value, $html);

                                        $description_style = ( isset($field['field_width']) and $field['field_width'] != '' ) ? 'style="width:' . $field['field_width'] . 'px;"' : '';
                                        $html = str_replace('[description_style]', $description_style, $html);

                                        $html = $arformcontroller->arf_remove_br($html);
                                        return $html;
                                    }

                                    function arf_getfields_basic_options_section() {

                                        $fieldsbasicoptionsarr = apply_filters('arfavailablefieldsbasicoptions', array(
                                            'text' => array('isrequired' => 1, 'fieldsize' => 2, 'customwidth' => 3, 'labelname' => 4, 'requiredmsg' => 5, 'minlength_message' => 6, 'placeholdertext' => 7, 'cleartextonfocus' => 8, 'validatedefaultvalue' => 9, 'fielddescription' => 10, /* 'minlength'=> 10, */ 'multicolsetting' => 11, 'arf_prefix' => 12),
                                            'textarea' => array('isrequired' => 1, 'fieldsize' => 2, 'customwidth' => 3, 'labelname' => 4, 'requiredmsg' => 5, 'minlength_message' => 6, 'placeholdertext' => 7, 'cleartextonfocus' => 8, 'validatedefaultvalue' => 9, 'fielddescription' => 10, /* 'minlength'=> 10, */ 'multicolsetting' => 11),
                                            'checkbox' => array('isrequired' => 1, 'requiredmsg' => 2, 'alignment' => 3, /* 'usersepratevalues' => 4, */ 'labelname' => 4, 'fielddescription' => 5, 'multicolsetting' => 6/* , 'field_checkbox_opt' => 8 */),
                                            'radio' => array('isrequired' => 1, 'requiredmsg' => 2, 'alignment' => 3, /* 'usersepratevalues' => 4, */ 'labelname' => 4, 'fielddescription' => 4, 'multicolsetting' => 6/* , 'field_checkbox_opt' => 8 */),
                                            'select' => array('isrequired' => 1, 'requiredmsg' => 2, 'customwidth' => 3, /* 'validatedefaultvalue' => 4, */ 'labelname' => 5, 'fielddescription' => 6, /* 'usersepratevalues' => 7, */ 'multicolsetting' => 7/* , 'field_select_opt' => 9 */),
                                            'file' => array('isrequired' => 1, 'requiredmsg' => 2, 'customwidth' => 3, 'allowedfiletypes' => 4, 'invalidmessage' => 5, 'attachfiletoemail' => 6, 'uploadbuttonbgcolor' => 7, /* 'uploadbuttonfontcolor' => 8, */ 'uploadbuttontext' => 8, 'removebuttontext' => 9, 'labelname' => 10, 'fielddescription' => 11, 'multicolsetting' => 12),
                                            //'email' => array('isrequired' => 1, 'requiredmsg' => 2, 'fieldsize' => 3, 'customwidth' => 4, 'labelname' => 5, 'fielddescription' => 6,  'placeholdertext' => 7, 'cleartextonfocus' => 8,  'invalidmessage' => 9, 'multicolsetting' => 10 ),
                                            'email' => array('isrequired' => 1, 'requiredmsg' => 2, 'fieldsize' => 3, 'customwidth' => 4, 'labelname' => 5, 'fielddescription' => 6, 'placeholdertext' => 7, 'cleartextonfocus' => 8, 'invalidmessage' => 9, 'confirm_email' => 11, 'confirm_email_label' => 12, 'invalid_confirm_email' => 13, 'confirm_email_placeholder' => '14', 'multicolsetting' => 10, 'arf_prefix' => 11),
                                            'captcha' => array('invalidmessage' => 1, 'labelname' => 2, 'fielddescription' => 3, 'captchastyle' => 4, 'multicolsetting' => 5),
                                            'number' => array('isrequired' => 1, 'fieldsize' => 2, 'customwidth' => 3, 'labelname' => 4, 'requiredmsg' => 5, 'minlength_message' => 6, 'placeholdertext' => 7, 'cleartextonfocus' => 8, 'validatedefaultvalue' => 9, 'numberrange' => 10, 'invalidmessage' => 11, /* 'minlength'=> 13, */ 'fielddescription' => 12, 'multicolsetting' => 13, 'arf_prefix' => 14),
                                            'phone' => array('isrequired' => 1, 'requiredmsg' => 2, 'fieldsize' => 3, 'customwidth' => 4, 'labelname' => 5, 'fielddescription' => 6, 'placeholdertext' => 7, 'cleartextonfocus' => 8, 'validatedefaultvalue' => 9, 'phone_validation' => 10, 'invalidmessage' => 11, 'multicolsetting' => 12, 'arf_prefix' => 13),
                                            'date' => array('isrequired' => 1, 'requiredmsg' => 2, 'customwidth' => 3, 'calendarlocalization' => 4, 'labelname' => 5, 'fielddescription' => 6, 'placeholdertext' => 7, 'cleartextonfocus' => 8, 'multicolsetting' => 9, 'yearrange' => 10, 'calendarhideshow' => 11, 'arf_prefix' => 12),
                                            'time' => array('isrequired' => 1, 'requiredmsg' => 2, 'customwidth' => 3, 'clocksetting' => 4, 'labelname' => 5, 'fielddescription' => 6, 'multicolsetting' => 7, 'arf_prefix' => 18),
                                            'url' => array('isrequired' => 1, 'requiredmsg' => 2, 'customwidth' => 3, 'invalidmessage' => 4, 'labelname' => 5, 'fielddescription' => 6, 'placeholdertext' => 7, 'cleartextonfocus' => 8, 'multicolsetting' => 9, 'arf_prefix' => 10),
                                            'image' => array('isrequired' => 1, 'requiredmsg' => 2, 'customwidth' => 3, 'placeholdertext' => 4, 'labelname' => 5, 'fielddescription' => 6, 'cleartextonfocus' => 7, 'multicolsetting' => 8, 'arf_prefix' => 9),
                                            'hidden' => array(),
                                            'password' => array('isrequired' => 1, 'fieldsize' => 2, 'customwidth' => 3, 'labelname' => 4, 'requiredmsg' => 5, 'minlength_message' => 6, 'placeholdertext' => 7, 'cleartextonfocus' => 8, 'password_strength' => 9, 'confirm_password' => 10, 'confirm_password_label' => 11, 'invalid_password' => 12, 'password_placeholder' => 13, /* 'minlength'=> 14, */ 'fielddescription' => 14, 'multicolsetting' => 15, 'arf_prefix' => 16),
                                            'html' => array('htmlcontent' => 1, 'multicolsetting' => 2, 'labelname' => 3),
                                            'divider' => array('fontfamilyoption' => 1, 'fontsizeoption' => 2, 'fontstyleoption' => 3, 'bgcoloroption' => 4, 'labelname' => 5, 'fielddescription' => 6, 'multicolsetting' => 7),
                                            'break' => array('firstpagelabel' => 1, 'secondpagelabel' => 2, 'prevbtntext' => 3, 'nextbtntext' => 4, 'pagebreakstyle' => 5, 'multicolsetting' => 6),
                                            'scale' => array('isrequired' => 1, 'requiredmsg' => 2, 'starrange' => 3, 'starstyle' => 4, 'labelname' => 5, 'fielddescription' => 6, 'starsize' => 7, 'multicolsetting' => 8),
                                            'like' => array('isrequired' => 1, 'requiredmsg' => 2, 'likebtntitle' => 3, 'dislikebtntitle' => 4, 'labelname' => 5, 'fielddescription' => 6, 'likebtnactivecolor' => 7, 'multicolsetting' => 8),
                                            'slider' => array('handletype' => 1, 'numberrange' => 2, 'numberofsteps' => 3, 'defaultvalue' => 4, 'labelname' => 5, 'fielddescription' => 6, 'trackbgcolor' => 7, 'handlecolor' => 8, 'multicolsetting' => 9),
                                            'colorpicker' => array('isrequired' => 1, 'requiredmsg' => 2, 'colorpicker_type' => '3', 'defaultcolor' => 4, 'labelname' => 5, 'fielddescription' => 6, 'multicolsetting' => 7),
                                            'imagecontrol' => array('image_url' => 1, 'labelname' => 2, 'fielddescription' => 3, 'image_horizontal_center' => 4, 'image_left' => 5, 'image_top' => 6, 'image_height' => 7, 'image_width' => 8),
                                        ));

                                        return $fieldsbasicoptionsarr;
                                    }

                                    function arf_get_field_option_value($field, $option) {
                                        global $armainhelper, $arfieldhelper, $arformcontroller, $arformhelper;
                                        // for file upload
                                        $mimes = get_allowed_mime_types();

                                        // for captcha
                                        $is_recaptcha = $field['is_recaptcha'];
                                        if ($is_recaptcha == '')
                                            $is_recaptcha = 'recaptcha';

                                        // for calendar
                                        $locales = array(
                                            '' => __('English/Western', 'ARForms'), 'af' => __('Afrikaans', 'ARForms'),
                                            'sq' => __('Albanian', 'ARForms'), 'ar' => __('Arabic', 'ARForms'),
                                            'hy' => __('Armenian', 'ARForms'), 'az' => __('Azerbaijani', 'ARForms'),
                                            'eu' => __('Basque', 'ARForms'), 'bs' => __('Bosnian', 'ARForms'),
                                            'bg' => __('Bulgarian', 'ARForms'), 'ca' => __('Catalan', 'ARForms'),
                                            'zh-HK' => __('Chinese Hong Kong', 'ARForms'), 'zh-CN' => __('Chinese Simplified', 'ARForms'),
                                            'zh-TW' => __('Chinese Traditional', 'ARForms'), 'hr' => __('Croatian', 'ARForms'),
                                            'cs' => __('Czech', 'ARForms'), 'da' => __('Danish', 'ARForms'),
                                            'nl' => __('Dutch', 'ARForms'), 'en-GB' => __('English/UK', 'ARForms'),
                                            'eo' => __('Esperanto', 'ARForms'), 'et' => __('Estonian', 'ARForms'),
                                            'fo' => __('Faroese', 'ARForms'), 'fa' => __('Farsi/Persian', 'ARForms'),
                                            'fi' => __('Finnish', 'ARForms'), 'fr' => __('French', 'ARForms'),
                                            'fr-CH' => __('French/Swiss', 'ARForms'), 'de' => __('German', 'ARForms'),
                                            'el' => __('Greek', 'ARForms'), 'he' => __('Hebrew', 'ARForms'),
                                            'hu' => __('Hungarian', 'ARForms'), 'is' => __('Icelandic', 'ARForms'),
                                            'it' => __('Italian', 'ARForms'), 'ja' => __('Japanese', 'ARForms'),
                                            'ko' => __('Korean', 'ARForms'), 'lv' => __('Latvian', 'ARForms'),
                                            'lt' => __('Lithuanian', 'ARForms'), 'ms' => __('Malaysian', 'ARForms'),
                                            'no' => __('Norwegian', 'ARForms'), 'pl' => __('Polish', 'ARForms'),
                                            'pt-BR' => __('Portuguese/Brazilian', 'ARForms'), 'ro' => __('Romanian', 'ARForms'),
                                            'ru' => __('Russian', 'ARForms'), 'sr' => __('Serbian', 'ARForms'),
                                            'sr-SR' => __('Serbian', 'ARForms'), 'sk' => __('Slovak', 'ARForms'),
                                            'sl' => __('Slovenian', 'ARForms'), 'es' => __('Spanish', 'ARForms'),
                                            'sv' => __('Swedish', 'ARForms'), 'ta' => __('Tamil', 'ARForms'),
                                            'th' => __('Thai', 'ARForms'), 'tu' => __('Turkish', 'ARForms'),
                                            'uk' => __('Ukrainian', 'ARForms'), 'vi' => __('Vietnamese', 'ARForms')
                                        );

                                        // for page break
                                        $first_break_pre_val = "";
                                        $first_break_next_val = "";

                                        $first_pre_pg_btn_id = "";
                                        $first_next_pg_btn_id = "";
                                        $default_selected_pg_brk_type = "";
                                        if (isset($_POST['pg_break_pre_first'])) {
                                            $first_break_pre_val = $_POST['pg_break_pre_first'];
                                        }
                                        if (isset($_POST['pg_break_next_first'])) {
                                            $first_break_next_val = $_POST['pg_break_next_first'];
                                        }

                                        if (isset($_POST['pg_break_first_select'])) {
                                            $default_selected_pg_brk_type = $_POST['pg_break_first_select'];
                                        } else {
                                            $default_selected_pg_brk_type = $field['page_break_type'];
                                        }

                                        $first_pre_pg_btn_id = "id='first_pg_break_pre' onblur='save_pg_break_pre_btn_val()'";
                                        $first_next_pg_btn_id = "id='first_pg_break_next' onblur='save_pg_break_next_btn_val()'";
                                        $second_page_label_txt = "Second Page Label";
                                        //$default_selected_pg_brk_type = $field['page_break_type'];
                                        // for like button
                                        $like_label = (isset($field['lbllike']) and $field['lbllike'] != '') ? $field['lbllike'] : 'Like';
                                        $dislike_label = (isset($field['lbldislike']) and $field['lbldislike'] != '') ? $field['lbldislike'] : 'Dislike';
                                        $like_bg_color = ( $field['like_bg_color'] == '' || $field['like_bg_color'] == '#') ? '#39ABEB' : $field['like_bg_color'];
                                        $dislike_bg_color = ( $field['dislike_bg_color'] == '' || $field['dislike_bg_color'] == '#') ? '#e00b0b' : $field['dislike_bg_color'];

                                        // for slider
                                        $slider_left_bg_color = ( $field['slider_bg_color'] == '' || $field['slider_bg_color'] == '#') ? '#d1dee5' : $field['slider_bg_color'];
                                        $slider_right_bg_color = ( $field['slider_bg_color2'] == '' || $field['slider_bg_color2'] == '#') ? '#bcc7cd' : $field['slider_bg_color2'];
                                        $slider_handle_color = ( $field['slider_handle_color'] == '' || $field['slider_handle_color'] == '#') ? '#0480BE' : $field['slider_handle_color'];
                                        $slider_step = is_numeric($field['slider_step']) ? $field['slider_step'] : 1;
                                        $field['minnum'] = is_numeric($field['minnum']) ? $field['minnum'] : 1;
                                        $field['maxnum'] = is_numeric($field['maxnum']) ? $field['maxnum'] : 50;
                                        $field['slider_value'] = is_numeric($field['slider_value']) ? $field['slider_value'] : $field['minnum'];
                                        ?>
                                        <style type="text/css">
                                            tr.fieldoptions_label_style td{
                                                float:left;
                                                padding-bottom:5px;
                                            }
                                        </style>
                                        <?php
                                        do_action('arf_add_more_field_option_in_out_side', $field, $option);

                                        switch ($option) {
                                            case 'isrequired':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Required field', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="padding-top:10px;">
                                                            <label class="lblswitch"><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[required_<?php echo $field['id'] ?>]" id="frm_req_field_<?php echo $field['id'] ?>"  <?php echo ($field['required']) ? 'checked="checked"' : ''; ?> onchange="arfmakerequiredfieldfunction(<?php echo $field['id'] ?>,<?php echo $field_required = ($field['required'] == '0') ? '0' : '1'; ?>, '2')" value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label>
                                                            <input type="hidden" name="field_options[required_indicator_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['required_indicator']); ?>" />
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'requiredmsg':
                                                ?>

                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Message for blank field', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td><input id="arfrequiredfieldtext<?php echo $field['id'] ?>" type="text" name="field_options[blank_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['blank']); ?>" class="arfplacelonginput txtstandardnew arfblank_txt" style="width:210px !important; float:left;" <?php if (!$field['required']) { ?> disabled="disabled" <?php } ?> /></td></tr> 
                                                </table>

                                                <?php
                                                break;

                                            case 'fieldsize':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Field Size ( Characters) ', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <?php if (in_array($field['type'], array('select', 'time', 'data'))) { ?>
                                                                <?php if (!isset($values['custom_style']) or $values['custom_style']) { ?>
                                                                    <input type="checkbox" class="chkstanard" id="frm_auto_width_field_<?php echo $field['id'] ?>" name="field_options[size_<?php echo $field['id'] ?>]" value="1" <?php echo (isset($field['size']) and $field['size']) ? 'checked="checked"' : ''; ?> /><label for="frm_auto_width_field_<?php echo $field['id'] ?>" class="howto"><span></span><?php _e('automatic width', 'ARForms') ?></label>
                                                                <?php }
                                                            } else {
                                                                ?>

                    <?php if (in_array($field['type'], array('text', 'textarea', 'number', 'password'))) { ?>
                                                                    <input type="text" style="width:100px; float:left; margin-right:5px;" name="field_options[max_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo esc_attr($field['max']); ?>" size="5" />
                                                                    &nbsp;&nbsp;&nbsp;
                                                                    <input type="text" style="width:100px; float:left; margin-left:5px;" name="field_options[minlength_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo $field['minlength']; ?>" style="width:100px;" />
                                                                <?php } else { ?>
                                                                    <input type="text" style="width:180px;" name="field_options[max_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo esc_attr($field['max']); ?>" size="5" />
                                                                    <?php } ?>
                                                                <br /> 
                                                                <div class="howto" style="width:110px; float:left;">
                                                                <?php echo ($field['type'] == 'textarea') ? __('Number of rows', 'ARForms') : __('Maximum', 'ARForms') ?>
                                                                </div>
                                                                <?php if (in_array($field['type'], array('text', 'textarea', 'number', 'password'))) { ?>
                                                                    <div class="howto" style="width:100px; float:left;"><?php echo __('Minimum', 'ARForms') ?></div> 
                    <?php } ?>
                <?php } ?>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'customwidth':
                                                $field['field_width'] = $field['field_width'] ? $field['field_width'] : '';
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">	
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Field custom width', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td style="float:left"><div style="float:left;"><input id="frm_custom_width_field_<?php echo $field['id'] ?>_div" type="text" style="width:170px;" class="txtstandardnew" name="field_options[field_width_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['field_width']); ?>" /></div><div style="padding-top:5px; float:left;">&nbsp;px</div></td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'cleartextonfocus':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Clear Default Text on Focus', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <label class="lblswitch"><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[frm_clear_field_<?php echo $field['id'] ?>]" id="frm_clear_field_<?php echo $field['id'] ?>"  <?php echo ($field['clear_on_focus']) ? 'checked="checked"' : ''; ?> onchange="arfcleardefaultvalueonfocus(<?php echo $field['id'] ?>,<?php echo $field['clear_on_focus']; ?>, 2)" value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label>
                                                            <input type="hidden" name="field_options[frm_clear_field_indicator_<?php echo $field['id'] ?>]" value="<?php $field['frm_clear_field_indicator'] = isset($field['frm_clear_field_indicator']) ? $field['frm_clear_field_indicator'] : '';
                                                   echo esc_attr($field['frm_clear_field_indicator']);
                                                   ?>" />
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'validatedefaultvalue':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Validate default value', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td><label class="lblswitch"><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[frm_default_blank_<?php echo $field['id'] ?>]" id="frm_default_blank_<?php echo $field['id'] ?>"  <?php echo ($field['default_blank']) ? 'checked="checked"' : ''; ?> onchange="arfdefaultblank(<?php echo $field['id'] ?>,<?php echo $field['default_blank']; ?>, 2)" value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label>
                                                            <input type="hidden" name="field_options[frm_default_blank_indicator_<?php echo $field['id'] ?>]" value="<?php $field['frm_default_blank_indicator'] = isset($field['frm_default_blank_indicator']) ? $field['frm_default_blank_indicator'] : '';
                                                echo esc_attr($field['frm_default_blank_indicator']);
                                                ?>" />
                                                        </td>
                                                    </tr>
                                                </table>	

                                                <?php
                                                break;

                                            case 'multicolsetting':

                                                $field['classes'] = (!isset($field['classes']) || empty($field['classes']) ) ? 'arf_1' : $field['classes'];
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Columns', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td><div style="float:left; padding-top:5px;"><div class="multi_column_div"><input type="radio" onclick="CheckFieldPos('1', '0');" class="rdostandard multicolfield" name="field_options[classes_<?php echo $field['id'] ?>]" data-id="<?php echo $field['id']; ?>" id="classes_<?php echo $field['id'] ?>_1" value="arf_1" <?php $armainhelper->checked($field['classes'], 'arf_1'); ?> /><label for="classes_<?php echo $field['id'] ?>_1"><span class="lblsubtitle_span_column"></span><div class="api_lable_column arf_one_column"><?php /* ?><br /><br /><label class="arf_coulmn_title"><?php _e('One', 'ARForms') ?></label><?php */ ?></div></label>
                                                                    <input type="radio" class="rdostandard multicolfield" name="field_options[classes_<?php echo $field['id'] ?>]" onclick="CheckFieldPos('1', '0');" id="classes_<?php echo $field['id'] ?>_2" value="arf_2" data-id="<?php echo $field['id']; ?>" <?php $armainhelper->checked($field['classes'], 'arf_2'); ?> /><label for="classes_<?php echo $field['id'] ?>_2"><span class="lblsubtitle_span_column"></span><div class="api_lable_column arf_two_column"><?php /* ?><br /><br /><label class="arf_coulmn_title"><?php _e('Two', 'ARForms') ?></label><?php */ ?></div></label>
                                                                    <input type="radio" class="rdostandard multicolfield" name="field_options[classes_<?php echo $field['id'] ?>]" onclick="CheckFieldPos('1', '0');" id="classes_<?php echo $field['id'] ?>_3" value="arf_3" data-id="<?php echo $field['id']; ?>" <?php $armainhelper->checked($field['classes'], 'arf_3'); ?> /><label for="classes_<?php echo $field['id'] ?>_3"><span class="lblsubtitle_span_column"></span><div class="api_lable_column arf_three_column"><?php /* ?><br /><br /><label class="arf_coulmn_title"><?php _e('Three', 'ARForms') ?></label><?php */ ?></div></label></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'alignment':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">  	
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Alignment', 'ARForms') ?>:</td></tr>

                                                    <tr class="fieldoptions_field_style">
                                                        <td class="sltstandard" style="float:none;">

                <?php /* ?><select onchange="arfchangefieldalign('<?php echo $field['id'] ?>');" id="arf_field_align_<?php echo $field['id'];?>" name="field_options[align_<?php echo $field['id'] ?>]" style="width:180px;" data-width="180px">
                  <option value="block" <?php selected($field['align'], 'block') ?>><?php _e('Multiple Rows', 'ARForms'); ?></option>
                  <option value="inline" <?php selected($field['align'], 'inline') ?>><?php _e('Single Row', 'ARForms'); ?></option>
                  </select><?php */ ?>

                                                            <input id="arf_field_align_<?php echo $field['id']; ?>" name="field_options[align_<?php echo $field['id'] ?>]" value="<?php
                                                   if ($field['align'] == 'inline') {
                                                       echo 'inline';
                                                   } else {
                                                       echo 'block';
                                                   }
                ?>" type="hidden" onchange="arfchangefieldalign('<?php echo $field['id'] ?>');">
                                                            <dl class="arf_selectbox" data-name="field_options[align_<?php echo $field['id'] ?>]" data-id="arf_field_align_<?php echo $field['id']; ?>" style="width:160px;">
                                                                <dt><span><?php
                                                                if ($field['align'] == 'inline') {
                                                                    echo __('Single Row', 'ARForms');
                                                                } else {
                                                                    echo __('Multiple Rows', 'ARForms');
                                                                }
                                                                ?></span>
                                                                <input value="<?php
                                                                if ($field['align'] == 'inline') {
                                                                    echo __('Single Row', 'ARForms');
                                                                } else {
                                                                    echo __('Multiple Rows', 'ARForms');
                                                                }
                                                                ?>" style="display:none;width:148px;" class="arf_autocomplete" type="text">
                                                                <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                <dd>
                                                                    <ul style="display: none;" data-id="arf_field_align_<?php echo $field['id']; ?>">
                                                                        <li class="arf_selectbox_option" data-value="block" data-label="<?php _e('Multiple Rows', 'ARForms'); ?>"><?php _e('Multiple Rows', 'ARForms'); ?></li>
                                                                        <li class="arf_selectbox_option" data-value="inline" data-label="<?php _e('Single Row', 'ARForms'); ?>"><?php _e('Single Row', 'ARForms'); ?></li>

                                                                    </ul>
                                                                </dd>
                                                            </dl>

                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'usersepratevalues':
                ?>
                                                <table style="float:left;">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Use separate values', 'ARForms'); ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <label class="lblswitch">&nbsp;&nbsp;<span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[separate_value_<?php echo $field['id'] ?>]" id="separate_value_<?php echo $field['id'] ?>"  <?php echo ($field['separate_value']) ? 'checked="checked"' : ''; ?> onchange="arfplaceseparatevalue(<?php echo $field['id'] ?>)" value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label><span style="margin-left:10px;"><img src="<?php echo ARFIMAGESURL ?>/tooltips-icon.png" alt="?" class="arfhelptip" title="<?php _e('Add a separate value to use for calculations, email routing, saving to the database, and many other uses. The option values are saved while the option labels are shown in the form.', 'ARForms') ?>" align="absmiddle" /></span>
                                                            </span>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'allowedfiletypes':

                if ($mimes) {
                    ?>
                                                    <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                        <tr><td class="fieldoptions_label_style"><?php _e('Allowed file types', 'ARForms') ?>:<span style="margin-left:10px;"><img src="<?php echo ARFIMAGESURL ?>/tooltips-icon.png" alt="?" class="arfhelptip" title="<?php _e('Here \'All Types\' means all the file types supported by your wordpress installation.', 'ARForms') ?>" align="absmiddle" /></span></td></tr>
                                                        <tr><td class="fieldoptions_field_style"><input type="radio" class="rdostandard" name="field_options[restrict_<?php echo $field['id'] ?>]" id="restrict_<?php echo $field['id'] ?>_0" value="0" <?php $armainhelper->checked($field['restrict'], 0); ?> onclick="arfshowconditionaldiv('restrict_box_<?php echo $field['id'] ?>', this.value, 1, '.')" /> <label for="restrict_<?php echo $field['id'] ?>_0"><span></span><?php _e('All types', 'ARForms') ?></label>&nbsp;<input type="radio" class="rdostandard" name="field_options[restrict_<?php echo $field['id'] ?>]" id="restrict_<?php echo $field['id'] ?>_1" value="1" <?php $armainhelper->checked($field['restrict'], 1); ?> onclick="arfshowconditionaldiv('restrict_box_<?php echo $field['id'] ?>', this.value, 1, '.')" /> <label for="restrict_<?php echo $field['id'] ?>_1"><span></span><?php _e('Specify types', 'ARForms') ?></label></td></tr>
                                                        <div style="float:left; width:380px; font-size:14px;">
                                                            <span class="restrict_box_<?php echo $field['id'] ?>" <?php echo ($field['restrict'] == 1) ? '' : 'style="display:none"'; ?>>


                                                                <label for="check_all_ftypes_<?php echo $field['id'] ?>"></label>


                                                            </span>


                                                            <div style="position:absolute;z-index:101; background-color:#f4f4f4; border: 1px solid #BCC4CC; padding:10px 10px 15px 15px; display:none;" class="restrict_box_<?php echo $field['id'] ?>" >
                                                                <div class="cose" style="float:right">
                                                                    <button data-dismiss="arfmodal" class="close" onclick="arfclosefileallowed('restrict_box_<?php echo $field['id'] ?>', '0');" type="button" style="margin-top:-12px; margin-right:-3px;">x</button>
                                                                </div>

                                                                <div class="main_allowed_types">

                                                                    <div class="arffieldoptionslist" style="width:650px;">


                                                                        <div class="alignleft" style="width:99% !important">


                                                                            <?php
                                                                            ksort($mimes);

                                                                            $mcount = count($mimes);


                                                                            $third = ceil($mcount / 3);


                                                                            $c = 0;

                                                                            $mimes['exe'] = '';
                                                                            unset($mimes['exe']);

                                                                            foreach ($mimes as $ext_preg => $mime) {


                                                                                if ($c == $third or ( ($c / 2) == $third)) {
                                                                                    
                                                                                }
                                                                                ?>


                                                                                <div style=" width:30%; margin-top:3px; margin-left:5px; float:left;"><input type="checkbox" class="chkstanard" id="field_options[ftypes_<?php echo $field['id'] ?>][<?php echo $ext_preg ?>]" name="field_options[ftypes_<?php echo $field['id'] ?>][<?php echo $ext_preg ?>]" value="<?php echo $mime ?>" <?php if (isset($field['ftypes']) and ! empty($field['ftypes'])) $armainhelper->checked($field['ftypes'], $mime); ?> /><label for="field_options[ftypes_<?php echo $field['id'] ?>][<?php echo $ext_preg ?>]" class="howto"><span></span><?php echo str_replace('|', ', ', $ext_preg); ?></label>
                                                                                </div>


                                                                                <?php
                                                                                $c++;


                                                                                unset($ext_preg);


                                                                                unset($mime);
                                                                            }


                                                                            unset($c);


                                                                            unset($mcount);


                                                                            unset($third);
                                                                            ?>


                                                                        </div>


                                                                    </div>

                                                                </div>	<!-- end allowed type -->	

                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </table>
                <?php } ?>

                                                <?php
                                                break;

                                            case 'invalidmessage':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Message for invalid submission', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td><input type="text" name="field_options[invalid_<?php echo $field['id'] ?>]" class="txtstandardnew arfplacelonginput" value="<?php echo esc_attr($field['invalid']); ?>" style="width:195px !important; float:left;" /></td></tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'attachfiletoemail':
                                                ?>
                                                <table style="float:left;" class="emailattachwidth" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Attach file with email', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td><label class="lblswitch"><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[attach_<?php echo $field['id'] ?>]" id="field_options[attach_<?php echo $field['id'] ?>]"  <?php echo (isset($field['attach']) and $field['attach']) ? 'checked="checked"' : ''; ?> value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label></td></tr>
                                                </table>
                <?php
                break;

            case 'uploadbuttonbgcolor':
                $upload_btn_color = ($field['upload_btn_color'] == '' || $field['upload_btn_color'] == '#') ? '#077bdd' : $field['upload_btn_color'];
                $upload_font_color = ($field['upload_font_color'] == '' || $field['upload_font_color'] == '#') ? '#ffffff' : $field['upload_font_color'];
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Upload color', 'ARForms') ?>:</td></tr>

                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <div style="float:left; width:60px; font-size:14px;">
                                                                <div class="arf_coloroption_sub">
                                                                    <div class="arf_coloroption arfhex" data-fid="upload_btn_color_<?php echo $field['id'] ?>" style="background:<?php echo esc_attr($upload_btn_color); ?>;"></div>
                                                                    <div class="arf_coloroption_subarrow_bg">
                                                                        <div class="arf_coloroption_subarrow"></div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="field_options[upload_btn_color_<?php echo $field['id'] ?>]" id="upload_btn_color_<?php echo $field['id'] ?>" class="hex txtstandardnew" value="<?php echo esc_attr($upload_btn_color); ?>" style="width:90px;" />
                                                                <span class="howto" style="padding-left:0px;float:left; margin-top: 2px;"><?php _e('Button', 'ARForms'); ?></span>
                                                            </div>
                                                            <div style="float:left; width:50px; margin-left:15px; font-size:14px;">
                                                                <div class="arf_coloroption_sub">
                                                                    <div class="arf_coloroption arfhex" data-fid="upload_font_color_<?php echo $field['id'] ?>" style="background:<?php echo esc_attr($upload_font_color); ?>;"></div>
                                                                    <div class="arf_coloroption_subarrow_bg">
                                                                        <div class="arf_coloroption_subarrow"></div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="field_options[upload_font_color_<?php echo $field['id'] ?>]" id="upload_font_color_<?php echo $field['id'] ?>" class="hex txtstandardnew" value="<?php echo esc_attr($upload_font_color); ?>" style="width:90px;" />
                                                                <span class="howto" style="padding-left:0px;float:left; margin-top: 2px;"><?php _e('Font', 'ARForms'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'uploadbuttonfontcolor':
                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Upload font color', 'ARForms') ?>:</td></tr>
                <?php $upload_font_color = ($field['upload_font_color'] == '' || $field['upload_font_color'] == '#') ? '#ffffff' : $field['upload_font_color']; ?>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <div class="arf_coloroption_sub">
                                                                <div class="arf_coloroption arfhex" data-fid="upload_font_color_<?php echo $field['id'] ?>" style="background:<?php echo esc_attr($upload_font_color); ?>;"></div>
                                                                <div class="arf_coloroption_subarrow_bg">
                                                                    <div class="arf_coloroption_subarrow"></div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="field_options[upload_font_color_<?php echo $field['id'] ?>]" id="upload_font_color_<?php echo $field['id'] ?>" class="hex txtstandardnew" value="<?php echo esc_attr($upload_font_color); ?>" style="width:90px;" /></td></tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'uploadbuttontext':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Upload button text', 'ARForms') ?>:</td></tr>
                                                    <?php $file_upload_text = ($field['file_upload_text'] == '') ? 'Upload' : $field['file_upload_text']; ?>
                                                    <tr class="fieldoptions_field_style"><td><input type="text" name="field_options[file_upload_text_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo esc_attr($file_upload_text); ?>" style="width:210px;" /></td></tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'removebuttontext':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Remove Button text', 'ARForms') ?>:</td></tr>
                <?php $file_remove_text = ($field['file_remove_text'] == '') ? 'Remove' : $field['file_remove_text']; ?>
                                                    <tr class="fieldoptions_field_style"><td><input type="text" name="field_options[file_remove_text_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo esc_attr($file_remove_text); ?>" style="width:210px;" /></td></tr>
                                                </table>

                                                            <?php
                                                            break;

                                                        case 'captchastyle':
                                                            ?>

                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Select captcha', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                <?php /* ?><select name="field_options[is_recaptcha_<?php echo $field['id'];?>]" id="is_recaptcha-<?php echo $field['id'];?>" class="frm-bulk-select-class" onchange="changerecaptchaimage('<?php echo $field['id'];?>',this.value, '<?php echo $field['id'];?>')" data-width="150px">
                  <option value="recaptcha" <?php selected($is_recaptcha, 'recaptcha') ?>>Recaptcha</option>
                  <option value="custom-captcha" <?php selected($is_recaptcha, 'custom-captcha') ?>>Default captcha</option>
                  </select><?php */ ?>

                                                            <input class="frm-bulk-select-class" id="is_recaptcha-<?php echo $field['id']; ?>" name="field_options[is_recaptcha_<?php echo $field['id']; ?>]" value="<?php
                                                    if ($is_recaptcha == 'custom-captcha') {
                                                        echo 'custom-captcha';
                                                    } else {
                                                        echo 'recaptcha';
                                                    }
                ?>" type="hidden" onchange="changerecaptchaimage('<?php echo $field['id']; ?>', this.value, '<?php echo $field['id']; ?>')">
                                                            <dl class="arf_selectbox" data-name="field_options[is_recaptcha_<?php echo $field['id']; ?>]" data-id="is_recaptcha-<?php echo $field['id']; ?>" style="width:140px;">
                                                                <dt><span><?php
                                                       if ($is_recaptcha == 'custom-captcha') {
                                                           echo 'Default captcha';
                                                       } else {
                                                           echo 'Recaptcha';
                                                       }
                                                       ?></span>
                                                                <input value="<?php
                                                       if ($is_recaptcha == 'custom-captcha') {
                                                           echo 'Default captcha';
                                                       } else {
                                                           echo 'Recaptcha';
                                                       }
                ?>" style="display:none;width:128px;" class="arf_autocomplete" type="text">
                                                                <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                <dd>
                                                                    <ul style="display: none;" data-id="is_recaptcha-<?php echo $field['id']; ?>">
                                                                        <li class="arf_selectbox_option" data-value="recaptcha" data-label="Recaptcha">Recaptcha</li>
                                                                        <li class="arf_selectbox_option" data-value="custom-captcha" data-label="Default captcha">Default captcha</li>
                                                                    </ul>
                                                                </dd>
                                                            </dl>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'numberrange':
                ?>
                                                <table style="float:left; width:100%;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Number Range', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td style="float:left; padding-right:10px;"><input type="text" name="field_options[minnum_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo esc_attr($field['minnum']); ?>" size="5" /><br /><span class="howto"><?php echo _e('minimum', 'ARForms') ?></span></td><td style="float:left;"><input type="text" name="field_options[maxnum_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo esc_attr($field['maxnum']); ?>" size="5" /><br /><span class="howto"><?php _e('maximum', 'ARForms') ?></span></td></tr>
                                                    <tr><td colspan="2"><span class="howto"><?php _e('(Give 0 (Zero) value for unlimited maximum range)', 'ARForms') ?></span></td></tr>
                                                </table>
                                                                <?php
                                                                break;

                                                            case 'calendarlocalization':
                                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Calendar Localization', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <div class="sltstandard" style="float:none;">

                                                                <?php /* ?><select name="field_options[locale_<?php echo $field['id'] ?>]" style="width:180px;" data-width="180px" data-size="15">


                                                                  <?php foreach($locales as $locale_key => $locale){


                                                                  $selected = (isset($field['locale']) && $field['locale'] == $locale_key)? ' selected="selected"':''; ?>


                                                                  <option value="<?php echo $locale_key ?>"<?php echo $selected; ?>><?php echo $locale ?></option>


                                                                  <?php } ?>


                                                                  </select><?php */ ?>

                <?php
                $cntr = 0;
                $option_locale_selected = array();
                $options_locale_options = '';
                foreach ($locales as $locale_key => $locale) {

                    if ((isset($field['locale']) && $field['locale'] == $locale_key) || $cntr == 0) {
                        $option_locale_selected['locale'] = $locale;
                        $option_locale_selected['locale_key'] = $locale_key;
                    }

                    $options_locale_options .= '<li class="arf_selectbox_option" data-value="' . $locale_key . '" data-label="' . $locale . '">' . $locale . '</li>';
                    $cntr++;
                }
                ?>


                                                                <input class="frm-bulk-select-class" id="field_date_locale-<?php echo $field['id']; ?>" name="field_options[locale_<?php echo $field['id'] ?>]" value="<?php echo $option_locale_selected['locale_key']; ?>" type="hidden">

                                                                <dl class="arf_selectbox" data-name="field_options[locale_<?php echo $field['id'] ?>]" data-id="field_date_locale-<?php echo $field['id']; ?>" style="width:140px;">

                                                                    <dt><span><?php echo $option_locale_selected['locale']; ?></span>
                                                                    <input value="<?php echo $option_locale_selected['locale']; ?>" style="display:none;width:128px;" class="arf_autocomplete" type="text">
                                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                    <dd>
                                                                        <ul style="display:none;" data-id="field_date_locale-<?php echo $field['id']; ?>">
                <?php echo $options_locale_options; ?>
                                                                        </ul>
                                                                    </dd>
                                                                </dl>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'yearrange':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Year Range', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style fieldoptions_labelstyle"><td class="lblswitch" style="float:left"><span><?php _e('Start Year', 'ARForms') ?>:&nbsp;</span><input type="text" name="field_options[start_year_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo isset($field['start_year']) ? $field['start_year'] : ''; ?>" size="4"/></td><td class="lblswitch" style="float:left; padding-left:6px;"><span><?php _e('End Year', 'ARForms') ?>:&nbsp;</span><input type="text" name="field_options[end_year_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo isset($field['end_year']) ? $field['end_year'] : ''; ?>" size="4"/></td></tr>
                                                </table>
                                                                    <?php
                                                                    break;

                                                                case 'clocksetting':
                                                                    ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">	
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Clock Settings', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <div style="width:90px; font-size:14px; float:left;"><div class="sltstandard" style="float:left;">

                <?php /* ?><select name="field_options[clock_<?php echo $field['id'] ?>]" style="width:70px;" data-width="70px" onchange="javascript:changeclockhours(this.value,'<?php echo $field['field_key']; ?>','<?php echo $field['id']; ?>','<?php echo $field['default_hour']; ?>');">
                  <option value="12" <?php selected($field['clock'], 12) ?>>12</option>
                  <option value="24" <?php selected($field['clock'], 24) ?>>24</option>
                  </select> <?php */ ?>

                <?php
                $option_time_clock_hour_selected = "";
                if ($field['clock'] == 24) {
                    $option_time_clock_hour_selected = "24";
                } else {
                    $option_time_clock_hour_selected = "12";
                }
                ?>

                                                                    <input id="field_time_clock-<?php echo $field['id']; ?>" name="field_options[clock_<?php echo $field['id'] ?>]" value="<?php echo $option_time_clock_hour_selected; ?>" type="hidden" onchange="javascript:changeclockhours(this.value, '<?php echo $field['field_key']; ?>', '<?php echo $field['id']; ?>', '<?php echo $field['default_hour']; ?>');">

                                                                    <dl class="arf_selectbox" data-name="field_options[clock_<?php echo $field['id'] ?>]" data-id="field_time_clock-<?php echo $field['id']; ?>" style="width:60px;">

                                                                        <dt><span><?php echo $option_time_clock_hour_selected; ?></span>
                                                                        <input value="<?php echo $option_time_clock_hour_selected ?>" style="display:none;width:48px;" class="arf_autocomplete" type="text">
                                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                        <dd>
                                                                            <ul style="display:none;" data-id="field_time_clock-<?php echo $field['id']; ?>">
                                                                                <li class="arf_selectbox_option" data-value="12" data-label="12">12</li>
                                                                                <li class="arf_selectbox_option" data-value="24" data-label="24">24</li>
                                                                            </ul>
                                                                        </dd>
                                                                    </dl>


                                                                </div> <span class="howto" style="padding-left:0px;float:left;"><?php _e('hour', 'ARForms') ?></span></div>
                                                            <div style="float:left; width:92px; font-size:14px;">

                                                                <div class="sltstandard" style="float:left;">

                                                                    <?php /* ?><select name="field_options[step_<?php echo $field['id'] ?>]" id="time_step_<?php echo $field['id'] ?>"  style=" width:70px;" data-width="70px">
                                                                      <option value="1" <?php selected($field['step'], 1);?>>1</option>
                                                                      <option value="2" <?php selected($field['step'], 2);?>>2</option>
                                                                      <option value="3" <?php selected($field['step'], 3);?>>3</option>
                                                                      <option value="4" <?php selected($field['step'], 4);?>>4</option>
                                                                      <option value="5" <?php selected($field['step'], 5);?>>5</option>
                                                                      <option value="10" <?php selected($field['step'], 10);?>>10</option>
                                                                      <option value="15" <?php selected($field['step'], 15);?>>15</option>
                                                                      <option value="20" <?php selected($field['step'], 20);?>>20</option>
                                                                      <option value="25" <?php selected($field['step'], 25);?>>25</option>
                                                                      <option value="30" <?php selected($field['step'], 30);?>>30</option>
                                                                      </select><?php */ ?>

                                                                    <?php
                                                                    $option_time_clock_step_selected = "";
                                                                    if ($field['step'] == 30) {
                                                                        $option_time_clock_step_selected = "30";
                                                                    } else if ($field['step'] == 25) {
                                                                        $option_time_clock_step_selected = "25";
                                                                    } else if ($field['step'] == 25) {
                                                                        $option_time_clock_step_selected = "25";
                                                                    } else if ($field['step'] == 20) {
                                                                        $option_time_clock_step_selected = "20";
                                                                    } else if ($field['step'] == 15) {
                                                                        $option_time_clock_step_selected = "15";
                                                                    } else if ($field['step'] == 10) {
                                                                        $option_time_clock_step_selected = "10";
                                                                    } else if ($field['step'] == 5) {
                                                                        $option_time_clock_step_selected = "5";
                                                                    } else if ($field['step'] == 4) {
                                                                        $option_time_clock_step_selected = "4";
                                                                    } else if ($field['step'] == 3) {
                                                                        $option_time_clock_step_selected = "3";
                                                                    } else if ($field['step'] == 2) {
                                                                        $option_time_clock_step_selected = "2";
                                                                    } else {
                                                                        $option_time_clock_step_selected = "1";
                                                                    }
                                                                    ?>
                                                                    <input id="time_step_<?php echo $field['id']; ?>" name="field_options[step_<?php echo $field['id'] ?>]" value="<?php echo $option_time_clock_step_selected; ?>" type="hidden">

                                                                    <dl class="arf_selectbox" data-name="field_options[step_<?php echo $field['id'] ?>]" data-id="time_step_<?php echo $field['id']; ?>" style="width:60px;">

                                                                        <dt><span><?php echo $option_time_clock_step_selected; ?></span>
                                                                        <input value="<?php echo $option_time_clock_step_selected ?>" style="display:none;width:48px;" class="arf_autocomplete" type="text">
                                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                        <dd>
                                                                            <ul style="display:none;" data-id="time_step_<?php echo $field['id']; ?>">
                                                                                <li class="arf_selectbox_option" data-value="1" data-label="1">1</li>
                                                                                <li class="arf_selectbox_option" data-value="2" data-label="2">2</li>
                                                                                <li class="arf_selectbox_option" data-value="3" data-label="3">3</li>
                                                                                <li class="arf_selectbox_option" data-value="4" data-label="4">4</li>
                                                                                <li class="arf_selectbox_option" data-value="5" data-label="5">5</li>
                                                                                <li class="arf_selectbox_option" data-value="10" data-label="10">10</li>
                                                                                <li class="arf_selectbox_option" data-value="15" data-label="15">15</li>
                                                                                <li class="arf_selectbox_option" data-value="20" data-label="20">20</li>
                                                                                <li class="arf_selectbox_option" data-value="25" data-label="25">25</li>
                                                                                <li class="arf_selectbox_option" data-value="30" data-label="30">30</li>
                                                                            </ul>
                                                                        </dd>
                                                                    </dl>

                                                                </div>
                                                                <br /><div class="howto" style="padding-right:10px; margin-top:5px;"><?php _e('minute', 'ARForms') ?></div> </div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'htmlcontent':
                $htmldesciprion = $armainhelper->esc_textarea($field['description']);
                $htmldesciprion = $arformhelper->replace_field_shortcode($htmldesciprion);
                ?>
                                                <table style="float:left; width:100%;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Content', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style fieldoptions_htmlfield"><td style="float:left;width:100%;"><textarea id="arf_field_description_<?php echo $field['id']; ?>" name="field_options[description_<?php echo $field['id'] ?>]" class="txtmultinew html_field_description" style="width:98%;" rows="8"><?php echo $htmldesciprion; ?></textarea></div>
                                                            <div style="font-size:14px; padding:5px 5px 5px 15px;">[ <?php _e('Embedded tags for youtube, map etc are supported.', 'ARForms'); ?> ]</div>
                                                            <div style="font-size:14px; padding:5px 5px 5px 15px;"><input type="checkbox" class="chkstanard" id="arfenable_total_<?php echo $field['id']; ?>" name="field_options[enable_total_<?php echo $field['id'] ?>]" value="1" <?php checked($field['enable_total'], 1); ?> onchange="arf_show_runnig_total('<?php echo $field['id']; ?>');" /><label for="arfenable_total_<?php echo $field['id']; ?>"><span></span><?php _e('Enable Running Total', 'ARForms'); ?></label>                    
                                                                <div class="arf_field_list_total_<?php echo $field['id']; ?> arf_runnigtotal_block" style=" <?php
                if ($field['enable_total'] != 1) {
                    echo 'display:none;';
                }
                ?>">
                                                                    <div class="arf_running_total_note"><?php _e('For Running Total you need to add formula inside', 'ARForms'); ?> &lt;arftotal>&lt;/arftotal>.	<br> e.g. <b>&lt;arftotal></b><span style="color:#1bbae1;">([Prodcut:123]*[Qty:125])+5</span><b>&lt;/arftotal></b></div>

                                                                    <button type="button" class="arfemailaddbtn arftotaladdbtn" onclick="add_field_fun('add_field_total_<?php echo $field['id']; ?>')" id="add_field_subject_but" style="margin-left:0;"><?php _e('Add Field', 'ARForms'); ?>&nbsp;&nbsp;<img src="<?php echo ARFIMAGESURL ?>/down-arrow.png" align="absmiddle" /></button>

                                                                    <div class="arf_running_total_operator">
                                                                        <button type="button" class="arf_runningtotal_operator_btn" onclick="arfaddtotalopcode('<?php echo $field['id']; ?>', '+');">+</button>
                                                                        <button type="button" class="arf_runningtotal_operator_btn" onclick="arfaddtotalopcode('<?php echo $field['id']; ?>', '-');">-</button>
                                                                        <button type="button" class="arf_runningtotal_operator_btn" onclick="arfaddtotalopcode('<?php echo $field['id']; ?>', '*');">*</button>
                                                                        <button type="button" class="arf_runningtotal_operator_btn" onclick="arfaddtotalopcode('<?php echo $field['id']; ?>', '/');">/</button>
                                                                        <button type="button" class="arf_runningtotal_operator_btn" onclick="arfaddtotalopcode('<?php echo $field['id']; ?>', '(');">(</button>
                                                                        <button type="button" class="arf_runningtotal_operator_btn" onclick="arfaddtotalopcode('<?php echo $field['id']; ?>', ')');">)</button>
                                                                    </div>

                                                                    <div class="arf_validateregex_fnc"><button class="arf_validate_result_btn" type="button" onclick="arfvalidateregex('<?php echo $field['id']; ?>');"><?php _e('Validate Formula', 'ARForms'); ?></button><div id="arf_validate_result_<?php echo $field['id']; ?>" class="arf_validate_result"></div></div>

                                                                    <!-- add field -->
                                                                    <div style=" position:relative;right:0;left:115px;top:-31px;" class="main_field_modal <?php //echo $main_field_modal_cls; ?>">
                                                                        <div class="arfmodal arfaddfieldmodal arftotalfielddropdown" id="<?php echo 'add_field_total_' . $field['id']; ?>" style=" display:none;margin-top:0px;position:absolute;width:auto;min-width:208px;">
                                                                            <div class="arfmodal-header"><div class="arfaddfieldtitle"><?php _e('Fields', 'ARForms'); ?><div data-dismiss="arfmodal" onclick="close_add_field_subject('add_field_total_<?php echo $field['id']; ?>')" style="float:right; cursor:pointer; margin-right:-5px; margin-top:-3px;"><img src="<?php echo ARFIMAGESURL . '/close-button.png' ?>" align="absmiddle" /></div></div></div>
                                                                            <div class="arfmodal-body_p">
                <?php $arfieldhelper->get_shortcode_total_modal($field['form_id'], 'arf_field_description_' . $field['id'], 'no_email', 'style="width:330px;"', true); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- add field -->



                                                                </div>
                                                        </td>                  
                                                    </tr>
                                                </table>

                                                                <?php
                                                                break;

                                                            case 'fontfamilyoption':
                                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Font Family', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left;">
                                                            <div class="sltstandard" style="float:right; margin-left:0px; margin-bottom:10px; ">
                                                                <?php $get_googlefonts_data = $arformcontroller->get_arf_google_fonts(); ?>
                                                                <?php /* ?><select name="field_options[arf_divider_font_<?php echo $field['id'] ?>]" style="width:200px;" data-width='200px' data-size='15'>	
                                                                  <optgroup label="Default Fonts">
                                                                  <option value="Arial" <?php selected($field['arf_divider_font'], 'Arial') ?>>Arial</option>
                                                                  <option value="Helvetica" <?php selected($field['arf_divider_font'], 'Helvetica') ?>>Helvetica</option>
                                                                  <option value="sans-serif" <?php selected($field['arf_divider_font'], 'sans-serif') ?>>sans-serif</option>
                                                                  <option value="Lucida Grande" <?php selected($field['arf_divider_font'], 'Lucida Grande') ?>>Lucida Grande</option>
                                                                  <option value="Lucida Sans Unicode" <?php selected($field['arf_divider_font'], 'Lucida Sans Unicode') ?>>Lucida Sans Unicode</option>
                                                                  <option value="Tahoma" <?php selected($field['arf_divider_font'], 'Tahoma') ?>>Tahoma</option>
                                                                  <option value="Times New Roman" <?php selected($field['arf_divider_font'], 'Times New Roman') ?>>Times New Roman</option>
                                                                  <option value="Courier New" <?php selected($field['arf_divider_font'], 'Courier New') ?>>Courier New</option>
                                                                  <option value="Verdana" <?php selected($field['arf_divider_font'], 'Verdana') ?>>Verdana</option>
                                                                  <option value="Geneva" <?php selected($field['arf_divider_font'], 'Geneva') ?>>Geneva</option>
                                                                  <option value="Courier" <?php selected($field['arf_divider_font'], 'Courier') ?>>Courier</option>
                                                                  <option value="Monospace" <?php selected($field['arf_divider_font'], 'Monospace') ?>>Monospace</option>
                                                                  <option value="Times" <?php selected($field['arf_divider_font'], 'Times') ?>>Times</option>
                                                                  </optgroup>
                                                                  <optgroup label="Google Fonts">
                                                                  <?php
                                                                  if(count($get_googlefonts_data)>0) {
                                                                  foreach($get_googlefonts_data as $goglefontsfamily)
                                                                  {
                                                                  echo "<option value='".$goglefontsfamily."' ".selected($field['arf_divider_font'], $goglefontsfamily)." >".$goglefontsfamily."</option>";
                                                                  }
                                                                  }
                                                                  ?>
                                                                  </optgroup>
                                                                  </select><?php */ ?>

                                                                <input id="field_arf_divider_font_<?php echo $field['id'] ?>" name="field_options[arf_divider_font_<?php echo $field['id'] ?>]" value="<?php echo $field['arf_divider_font']; ?>" type="hidden">
                                                                <dl class="arf_selectbox" data-name="field_options[arf_divider_font_<?php echo $field['id'] ?>]" data-id="field_arf_divider_font_<?php echo $field['id'] ?>" style="width:180px;">
                                                                    <dt><span><?php echo $field['arf_divider_font']; ?></span>
                                                                    <input value="<?php echo $field['arf_divider_font']; ?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                    <dd>
                                                                        <ul style="display: none;" data-id="field_arf_divider_font_<?php echo $field['id'] ?>">
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
                                                if (count($get_googlefonts_data) > 0) {
                                                    foreach ($get_googlefonts_data as $goglefontsfamily) {
                                                        echo "<li class='arf_selectbox_option' data-value='" . $goglefontsfamily . "' data-label='" . $goglefontsfamily . "'>" . $goglefontsfamily . "</li>";
                                                    }
                                                }
                                                ?>
                                                                        </ul>
                                                                    </dd>
                                                                </dl>
                                                            </div> 

                                                        </td>
                                                    </tr>
                                                </table>

                                                                <?php
                                                                break;

                                                            case 'fontsizeoption':
                                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Font Size', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left;">
                                                            <div class="sltstandard" style="float:left;">
                                                                            <?php /* ?><select name="field_options[arf_divider_font_size_<?php echo $field['id'] ?>]" style="width:180px;" data-width='180px' data-size='15'>	
                                                                              <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                                              <option value="<?php echo $i?>" <?php selected($field['arf_divider_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                                              <?php } ?>
                                                                              <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                                              <option value="<?php echo $i?>" <?php selected($field['arf_divider_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                                              <?php } ?>
                                                                              <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                                              <option value="<?php echo $i?>" <?php selected($field['arf_divider_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                                              <?php } ?>
                                                                              </select><?php */ ?>

                                                                <input id="field_arf_divider_font_size_<?php echo $field['id'] ?>" name="field_options[arf_divider_font_size_<?php echo $field['id'] ?>]" value="<?php echo $field['arf_divider_font_size']; ?>" type="hidden">
                                                                <dl class="arf_selectbox" data-name="field_options[arf_divider_font_size_<?php echo $field['id'] ?>]" data-id="field_arf_divider_font_size_<?php echo $field['id'] ?>" style="width:160px;">
                                                                    <dt><span><?php echo $field['arf_divider_font_size']; ?></span>
                                                                    <input value="<?php echo $field['arf_divider_font_size']; ?>" style="display:none;width:148px;" class="arf_autocomplete" type="text">
                                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                    <dd>
                                                                        <ul style="display: none;" data-id="field_arf_divider_font_size_<?php echo $field['id'] ?>">
                                                <?php for ($i = 8; $i <= 20; $i ++) { ?>
                                                                                <li class="arf_selectbox_option" data-value="<?php echo $i ?>" data-label="<?php echo $i ?>"><?php _e($i, 'ARForms'); ?></li>
                                                <?php } ?>
                                                <?php for ($i = 22; $i <= 28; $i = $i + 2) { ?>
                                                                                <li class="arf_selectbox_option" data-value="<?php echo $i ?>" data-label="<?php echo $i ?>"><?php _e($i, 'ARForms'); ?></li>
                                                <?php } ?>
                <?php for ($i = 32; $i <= 40; $i = $i + 4) { ?>
                                                                                <li class="arf_selectbox_option" data-value="<?php echo $i ?>" data-label="<?php echo $i ?>"><?php _e($i, 'ARForms'); ?></li>
                <?php } ?>
                                                                        </ul>
                                                                    </dd>
                                                                </dl>

                                                            </div>
                                                            &nbsp;<span class="arf_px" style="float:right; padding-top:5px;margin-left:17px;"><?php _e('px', 'ARForms') ?></span> 
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'fontstyleoption':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Font Style', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left;">
                                                            <div class="sltstandard" style="float:right; margin-right:17px;">
                <?php /* ?><select name="field_options[arf_divider_font_style_<?php echo $field['id'] ?>]" style="width:180px;" data-width='180px'>
                  <option value="normal" <?php selected($field['arf_divider_font_style'], 'normal') ?>><?php _e('normal', 'ARForms') ?></option>
                  <option value="bold" <?php selected($field['arf_divider_font_style'], 'bold') ?>><?php _e('bold', 'ARForms') ?></option>
                  <option value="italic" <?php selected($field['arf_divider_font_style'], 'italic') ?>><?php _e('italic', 'ARForms') ?></option>
                  </select><?php */ ?>

                                                                <input id="field_arf_divider_font_style_<?php echo $field['id'] ?>" name="field_options[arf_divider_font_style_<?php echo $field['id'] ?>]" value="<?php echo $field['arf_divider_font_style']; ?>" type="hidden">
                                                                <dl class="arf_selectbox" data-name="field_options[arf_divider_font_style_<?php echo $field['id'] ?>]" data-id="field_arf_divider_font_style_<?php echo $field['id'] ?>" style="width:160px;">
                                                                    <dt><span><?php echo __($field['arf_divider_font_style'], 'ARForms'); ?></span>
                                                                    <input value="<?php echo __($field['arf_divider_font_style'], 'ARForms'); ?>" style="display:none;width:148px;" class="arf_autocomplete" type="text">
                                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                    <dd>
                                                                        <ul style="display: none;" data-id="field_arf_divider_font_style_<?php echo $field['id'] ?>">
                                                                            <li class="arf_selectbox_option" data-value="normal" data-label="<?php _e('normal', 'ARForms'); ?>"><?php _e('normal', 'ARForms'); ?></li>
                                                                            <li class="arf_selectbox_option" data-value="bold" data-label="<?php _e('bold', 'ARForms') ?>"><?php _e('bold', 'ARForms') ?></li>
                                                                            <li class="arf_selectbox_option" data-value="italic" data-label="<?php _e('italic', 'ARForms') ?>"><?php _e('italic', 'ARForms') ?></li>
                                                                        </ul>
                                                                    </dd>
                                                                </dl>

                                                            </div>

                                                        </td>
                                                    </tr>
                                                </table>

                                                            <?php
                                                            break;

                                                        case 'bgcoloroption':
                                                            ?>
                                                            <?php /* ?><style type="text/css">
                                                              #field-option-<?php echo $field['id'] ?> #picker { left:auto; }
                                                              </style><?php */ ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Background Color', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left;">
                <?php $arf_divider_bg_color = ($field['arf_divider_bg_color'] == '' || $field['arf_divider_bg_color'] == '#') ? '#ffffff' : $field['arf_divider_bg_color']; ?>
                <?php
                $arf_divider_inherit_style = "";
                $arf_divider_bgcolor_style = "";
                if ($field['arf_divider_inherit_bg'] == 1) {
                    $arf_divider_inherit_style = "display:inline-block;";
                    $arf_divider_bgcolor_style = "display:none;";
                } else {
                    $arf_divider_inherit_style = "display:none;";
                    $arf_divider_bgcolor_style = "display:inline-block;";
                }
                ?>
                                                            <div class="arf_clr_disable" id="arf_divider_bg_color_disabled_<?php echo $field['id'] ?>" style=" <?php echo $arf_divider_inherit_style; ?>">
                                                                <div class="arf_coloroption arfhex" data-fid="arf_divider_bg_color_<?php echo $field['id'] ?>" style="background:<?php echo $arf_divider_bg_color; ?>;"></div>
                                                                <div class="arf_coloroption_subarrow_bg">
                                                                    <div class="arf_coloroption_subarrow"></div>
                                                                </div>
                                                            </div>
                                                            <div class="arf_coloroption_sub" data-cls="arf_clr_disable" style=" <?php echo $arf_divider_bgcolor_style; ?>">
                                                                <div class="arf_coloroption arfhex" data-fid="arf_divider_bg_color_<?php echo $field['id'] ?>" style="background:<?php echo $arf_divider_bg_color; ?>;"></div>
                                                                <div class="arf_coloroption_subarrow_bg">
                                                                    <div class="arf_coloroption_subarrow"></div>
                                                                </div>
                                                            </div>
                                                            <input type="checkbox" onchange="changearfsectionbgtype('<?php echo $field['id']; ?>', this.checked);" <?php checked(@$field['arf_divider_inherit_bg'], 1); ?> data-id='arf_divider_inherit_bg' value="1" id="arf_divider_inherit_bg_<?php echo $field['id']; ?>" name="field_options[arf_divider_inherit_bg_<?php echo $field['id'] ?>]" class="chkstanard"><label for="arf_divider_inherit_bg_<?php echo $field['id']; ?>" style="position:relative;top:-10px;left:6px;" ><span></span><spam class="arf_automatic_response_enable_title" style='font-weight:normal;'><?php _e('Inherit', 'ARForms') ?></spam></label>
                                                            <input type="hidden" name="field_options[arf_divider_bg_color_<?php echo $field['id'] ?>]" id="arf_divider_bg_color_<?php echo $field['id'] ?>" class="hex txtstandardnew" value="<?php echo $arf_divider_bg_color; ?>" style="width:90px;" />
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'firstpagelabel':

                global $MdlDb;
                $page_num = $MdlDb->get_count($MdlDb->fields, array("form_id" => $field['form_id'], "type" => 'break'));
                $second_page_label_txt = "Page Label";
                if ($page_num == 1 || $field['page_break_first_use'] == 1) {
                    ?>
                                                    <table style="float:left;" id="pg_break_div_<?php echo $field['id'] ?>" class="pg-break-div" border="0" cellpadding="0" cellspacing="0">
                                                        <tr class="fieldoptions_label_style"><td><?php _e('First Page Label', 'ARForms') ?></td></tr>

                                                        <tr class="fieldoptions_field_style">
                                                            <td style="float:left;">
                                                                <input type="text" id="field_options[first_page_label_<?php echo $field['id'] ?>]" name="field_options[first_page_label_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo ( isset($field['first_page_label']) and $field['first_page_label'] != '' ) ? esc_attr($field['first_page_label']) : 'Step1'; ?>" size="10" style="width:180px;" />
                                                                <input type="hidden" id="page_break_first_use_<?php echo $field['id'] ?>" name="field_options[page_break_first_use_<?php echo $field['id'] ?>]" value="1" /><br />
                                                                <input type="hidden" name="page_number_<?php echo $field['id'] ?>" class="pagebreak_field" value="<?php echo $field['id']; ?>" id="page_number_<?php echo $field['id'] ?>" /> 
                                                            </td>
                                                        </tr>
                                                    </table>

                <?php } else { ?>
                                                    <table style="float:left;" id="pg_break_div_<?php echo $field['id'] ?>" class="pg-break-div" cellpadding="0" cellspacing="0" border="0">
                                                        <tr class="fieldoptions_label_style"><td><?php _e('First Page Label', 'ARForms') ?></td></tr>
                                                        <tr class="fieldoptions_field_style">
                                                            <td style="float:left;">
                                                                <input type="text" id="field_options[first_page_label_<?php echo $field['id'] ?>]" name="field_options[first_page_label_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo ( isset($field['first_page_label']) and $field['first_page_label'] != '' ) ? esc_attr($field['first_page_label']) : 'Step1'; ?>" size="10" style="width:180px;" />
                                                                <input type="hidden" id="page_break_first_use_<?php echo $field['id'] ?>" name="field_options[page_break_first_use_<?php echo $field['id'] ?>]" value="0" /><br />
                                                                <input type="hidden" name="page_number_<?php echo $field['id']; ?>" class="pagebreak_field" value="<?php echo $field['id']; ?>" id="page_number_<?php echo $field['id']; ?>" /> 
                                                            </td>
                                                        </tr>
                                                    </table>       
                                                <?php } ?>

                                                <?php
                                                break;

                                            case 'secondpagelabel':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td id="arf_page_break_label_<?php echo $field['id'] ?>"><?php _e($second_page_label_txt, 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left;">
                                                            <input type="text" id="field_options[second_page_label_<?php echo $field['id'] ?>]" name="field_options[second_page_label_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo ( isset($field['second_page_label']) and $field['second_page_label'] != '' ) ? esc_attr($field['second_page_label']) : 'Step2'; ?>" size="10" style="width:180px;" /><br /> 
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'prevbtntext':
                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Previous Button Text', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left">
                <?php
                if ($first_break_pre_val == "") {
                    $pre_page_title = esc_attr($field['pre_page_title']);
                } else {
                    $pre_page_title = esc_attr($first_break_pre_val);
                }
                ?>
                                                            <input type="text" <?php echo $first_pre_pg_btn_id; ?> name="field_options[pre_page_title_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo ( isset($pre_page_title) and $pre_page_title != '' ) ? $pre_page_title : 'Previous'; ?>" size="10" style="width:180px;" /><br /> 
                                                        </td>
                                                    </tr>
                                                </table>

                                                            <?php
                                                            break;


                                                        case 'nextbtntext':
                                                            ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Next Button Text', 'ARForms') ?></td></tr>

                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left">
                                                <?php
                                                if ($first_break_next_val == "") {
                                                    $next_page_title = esc_attr($field['next_page_title']);
                                                } else {
                                                    $next_page_title = esc_attr($first_break_next_val);
                                                }
                                                ?>
                                                            <input type="text" <?php echo $first_next_pg_btn_id; ?> name="field_options[next_page_title_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo ( isset($next_page_title) and $next_page_title != '' ) ? $next_page_title : 'Next'; ?>" size="10" style="width:180px;" /><br />
                                                                <?php
                                                                $first_next_pg_btn_id = "";
                                                                $first_pre_pg_btn_id = "";
                                                                ?> 
                                                        </td>
                                                    </tr>
                                                </table>

                                                                <?php
                                                                break;

                                                            case 'pagebreakstyle':
                                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Style', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left">
                                                            <div class="sltstandard" style="float:left;">
                                                                        <?php /* ?><select class="page_break_select" onchange="change_page_break_select(this.value);" name="field_options[page_break_type_<?php echo $field['id'] ?>]" style="width:180px;" data-width="180px">

                                                                          <option value="wizard" <?php selected($default_selected_pg_brk_type, 'wizard') ?>><?php _e('Wizard', 'ARForms') ?></option>

                                                                          <option value="survey" <?php selected($default_selected_pg_brk_type, 'survey') ?>><?php _e('Survey', 'ARForms') ?></option>

                                                                          </select><?php */ ?>

                                                                <input id="field_page_break_type_<?php echo $field['id'] ?>" class="page_break_select" name="field_options[page_break_type_<?php echo $field['id'] ?>]" value="<?php
                                                    if ($default_selected_pg_brk_type == 'survey') {
                                                        echo 'survey';
                                                    } else {
                                                        echo 'wizard';
                                                    }
                                                    ?>" type="hidden" onchange="change_page_break_select(this.value);">

                                                                <dl class="arf_selectbox" data-name="field_options[page_break_type_<?php echo $field['id'] ?>]" data-id="field_page_break_type_<?php echo $field['id'] ?>" style="width:160px;">

                                                                    <dt class="field_page_break_type_<?php echo $field['id'] ?>_dt"><span><?php
                                                    if ($default_selected_pg_brk_type == 'survey') {
                                                        echo __('Survey', 'ARForms');
                                                    } else {
                                                        echo __('Wizard', 'ARForms');
                                                    }
                                                    ?></span>
                                                                    <input value="<?php
                                                if ($default_selected_pg_brk_type == 'survey') {
                                                    echo __('Survey', 'ARForms');
                                                } else {
                                                    echo __('Wizard', 'ARForms');
                                                }
                                                ?>" style="display:none;width:148px;" class="arf_autocomplete" type="text">
                                                                    <i class="fa fa-caret-down fa-lg"></i>
                                                                    </dt>

                                                                    <dd>
                                                                        <ul style="display: none;" data-id="field_page_break_type_<?php echo $field['id'] ?>">
                                                                            <li id="field_page_break_type_<?php echo $field['id']; ?>_wizard" class="arf_selectbox_option" data-value="wizard" data-label="<?php _e('Wizard', 'ARForms'); ?>"><?php _e('Wizard', 'ARForms'); ?></li>
                                                                            <li id="field_page_break_type_<?php echo $field['id']; ?>_survey" class="arf_selectbox_option" data-value="survey" data-label="<?php _e('Survey', 'ARForms'); ?>"><?php _e('Survey', 'ARForms'); ?></li>

                                                                        </ul>
                                                                    </dd>
                                                                </dl>

                                                            </div>

                                                        </td>
                                                    </tr>
                                                </table>

                                                                <?php
                                                                break;

                                                            case 'starrange':
                                                                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Range', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[maxnum_<?php echo $field['id'] ?>]" value="<?php echo $field['maxnum']; ?>"  style="width:180px;" /></div>
                                                            <input type="hidden" class="txtstandardnew" name="field_options[minnum_<?php echo $field['id'] ?>]" value="<?php echo $field['minnum']; ?>" />
                                                        </td>
                                                    </tr>
                                                </table>

                                                                <?php
                                                                break;

                                                            case 'starstyle':
                                                                $field['star_color'] = $field['star_color'] ? $field['star_color'] : 'yellow';
                                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Style', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left">
                                                            <div class="sltstandard" style="float:left;">
                                                                <?php /* ?><select id="field_options[star_color_<?php echo $field['id'] ?>]" name="field_options[star_color_<?php echo $field['id'] ?>]" style="width:180px;" data-width="180px" onchange="ShowCurrentStar('<?php echo $field['id'] ?>');">

                                                                  <option value="yellow" <?php selected($field['star_color'], 'yellow') ?>>Yellow</option>

                                                                  <option value="red" <?php selected($field['star_color'], 'red') ?>>Red</option>

                                                                  <option value="orange" <?php selected($field['star_color'], 'orange') ?>>Orange</option>

                                                                  <option value="blue" <?php selected($field['star_color'], 'blue') ?>>Blue</option>

                                                                  <option value="green" <?php selected($field['star_color'], 'green') ?>>Green</option>

                                                                  <option value="black" <?php selected($field['star_color'], 'black') ?>>Black</option>

                                                                  </select><?php */ ?>

                <?php
                if ($field['star_color'] == "") {
                    $field['star_color'] = "yellow";
                }

                $field_star_color_option = "";
                if ($field['star_color'] == 'black') {
                    $field_star_color_option = "Black";
                } else if ($field['star_color'] == 'green') {
                    $field_star_color_option = "Green";
                } else if ($field['star_color'] == 'blue') {
                    $field_star_color_option = "Blue";
                } else if ($field['star_color'] == 'orange') {
                    $field_star_color_option = "Orange";
                } else if ($field['star_color'] == 'red') {
                    $field_star_color_option = "Red";
                } else {
                    $field_star_color_option = "Yellow";
                }
                ?>

                                                                <input id="field_star_color_<?php echo $field['id'] ?>" name="field_options[star_color_<?php echo $field['id'] ?>]" value="<?php echo $field['star_color']; ?>" type="hidden" onchange="ShowCurrentStar('<?php echo $field['id'] ?>');">

                                                                <dl class="arf_selectbox" data-name="field_options[star_color_<?php echo $field['id'] ?>]" data-id="field_star_color_<?php echo $field['id'] ?>" style="width:160px;">

                                                                    <dt class="field_star_color_<?php echo $field['id'] ?>_dt"><span><?php echo $field_star_color_option; ?></span>
                                                                    <input value="<?php echo $field_star_color_option; ?>" style="display:none;width:148px;" class="arf_autocomplete" type="text">
                                                                    <i class="fa fa-caret-down fa-lg"></i>
                                                                    </dt>

                                                                    <dd>
                                                                        <ul style="display: none;" data-id="field_star_color_<?php echo $field['id'] ?>">
                                                                            <li class="arf_selectbox_option" data-value="yellow" data-label="<?php _e('Yellow', 'ARForms'); ?>"><?php _e('Yellow', 'ARForms'); ?></li>

                                                                            <li class="arf_selectbox_option" data-value="red" data-label="<?php _e('Red', 'ARForms'); ?>"><?php _e('Red', 'ARForms'); ?></li>
                                                                            <li class="arf_selectbox_option" data-value="orange" data-label="<?php _e('Orange', 'ARForms'); ?>"><?php _e('Orange', 'ARForms'); ?></li>
                                                                            <li class="arf_selectbox_option" data-value="blue" data-label="<?php _e('Blue', 'ARForms'); ?>"><?php _e('Blue', 'ARForms'); ?></li>
                                                                            <li class="arf_selectbox_option" data-value="green" data-label="<?php _e('Green', 'ARForms'); ?>"><?php _e('Green', 'ARForms'); ?></li>
                                                                            <li class="arf_selectbox_option" data-value="black" data-label="<?php _e('Black', 'ARForms'); ?>"><?php _e('Black', 'ARForms'); ?></li>      

                                                                        </ul>
                                                                    </dd>
                                                                </dl>

                                                            </div>

                                                        </td>
                                                    </tr>
                                                </table>

                                                                <?php
                                                                break;

                                                            case 'starsize':
                                                                $field['star_size'] = $field['star_size'] ? $field['star_size'] : 'small';
                                                                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Size', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left">
                                                            <div class="sltstandard" style="float:left;">

                <?php /* ?><select id="field_options[star_size_<?php echo $field['id'] ?>]" name="field_options[star_size_<?php echo $field['id'] ?>]" style="width:80px;" data-width="80px" onchange="ShowCurrentStar('<?php echo $field['id'] ?>');">

                  <option value="big" <?php selected($field['star_size'], 'big') ?>>Big</option>

                  <option value="small" <?php selected($field['star_size'], 'small') ?>>Small</option>

                  </select><?php */ ?>


                <?php
                if ($field['star_size'] == "") {
                    $field['star_size'] = "big";
                }

                $field_star_size_option = "";
                if ($field['star_size'] == 'small') {
                    $field_star_size_option = "Small";
                } else {
                    $field_star_size_option = "Big";
                }
                ?>

                                                                <input id="field_star_size_<?php echo $field['id'] ?>" name="field_options[star_size_<?php echo $field['id'] ?>]" value="<?php echo $field['star_size']; ?>" type="hidden" onchange="ShowCurrentStar('<?php echo $field['id'] ?>');">

                                                                <dl class="arf_selectbox" data-name="field_options[star_size_<?php echo $field['id'] ?>]" data-id="field_star_size_<?php echo $field['id'] ?>" style="width:60px;">

                                                                    <dt class="field_star_size_<?php echo $field['id'] ?>_dt"><span><?php echo $field_star_size_option; ?></span>
                                                                    <input value="<?php echo $field_star_size_option; ?>" style="display:none;width:48px;" class="arf_autocomplete" type="text">
                                                                    <i class="fa fa-caret-down fa-lg"></i>
                                                                    </dt>

                                                                    <dd>
                                                                        <ul style="display: none;" data-id="field_star_size_<?php echo $field['id'] ?>">
                                                                            <li class="arf_selectbox_option" data-value="big" data-label="<?php _e('Big', 'ARForms'); ?>"><?php _e('Big', 'ARForms'); ?></li>

                                                                            <li class="arf_selectbox_option" data-value="small" data-label="<?php _e('Small', 'ARForms'); ?>"><?php _e('Small', 'ARForms'); ?></li>
                                                                        </ul>
                                                                    </dd>
                                                                </dl>

                                                            </div>

                <?php
                $small_val = "";
                if (@$field['star_size'] == "small") {
                    $small_val = "_small";
                }
                ?>
                                                            <div id="showlivestar_<?php echo $field['id'] ?>" style="float:left;padding-left:10px;margin-top:-2px;margin-left:15px;">
                                                                <span class="star_1 ratings_stars ratings_stars_<?php echo $field['star_color'] . $small_val; ?> ratings_over_<?php echo $field['star_color'] . $small_val; ?>" data-color="" data-val="1"></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'likebtntitle':
                ?>

                                                <table style="float:left;">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Like Title', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left">
                                                            <div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[lbllike_<?php echo $field['id'] ?>]" value="<?php echo $like_label; ?>"  style="width:180px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'dislikebtntitle':
                ?>


                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Dislike Title', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left">
                                                            <input type="text" class="txtstandardnew" name="field_options[lbldislike_<?php echo $field['id'] ?>]" value="<?php echo $dislike_label; ?>"  style="width:160px;" />		
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'likebtnactivecolor':
                ?>

                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">	
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Active color', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="width:60px; font-size:14px; float:left;">

                                                            <div class="arf_coloroption_sub">
                                                                <div class="arf_coloroption arfhex" data-fid="like_bg_color_<?php echo $field['id'] ?>" style="background:<?php echo esc_attr($like_bg_color); ?>;"></div>
                                                                <div class="arf_coloroption_subarrow_bg">
                                                                    <div class="arf_coloroption_subarrow"></div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="field_options[like_bg_color_<?php echo $field['id'] ?>]" id="like_bg_color_<?php echo $field['id'] ?>" class="txtstandardnew hex" value="<?php echo esc_attr($like_bg_color); ?>" style="width:80px;" size="5" /><!--<br />--><span class="howto" style="padding-left:0px;float:left; margin-top: 2px;"><?php _e('Like', 'ARForms') ?></span>

                                                        </td>

                                                        <td style="float:left; width:92px; font-size:14px; margin-left:15px;">
                                                            <div class="arf_coloroption_sub">
                                                                <div class="arf_coloroption arfhex" data-fid="dislike_bg_color_<?php echo $field['id'] ?>" style="background:<?php echo esc_attr($dislike_bg_color); ?>;"></div>
                                                                <div class="arf_coloroption_subarrow_bg">
                                                                    <div class="arf_coloroption_subarrow"></div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="field_options[dislike_bg_color_<?php echo $field['id'] ?>]" id="dislike_bg_color_<?php echo $field['id'] ?>" class="txtstandardnew hex" value="<?php echo esc_attr($dislike_bg_color); ?>" style="width:80px;" size="5" /><!--<br />--><div class="howto" style="padding-right:10px; margin-top:2px;"><?php _e('Dislike', 'ARForms') ?></div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                                <?php
                                                                break;

                                                            case 'handletype':
                                                                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Handle type', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left">
                                                            <div class="sltstandard" style="float:none;">        
                <?php /* ?><select onchange="arf_change_slider_class(<?php echo $field['id'] ?>);" name="field_options[slider_handle_<?php echo $field['id'] ?>]" id="slider_handle_<?php echo $field['id'] ?>" style="width:180px;" data-width="180px" data-size="15">
                  <option value="round" <?php selected($field['slider_handle'], 'round');?>><?php _e('Round', 'ARForms'); ?></option>
                  <option value="square" <?php selected($field['slider_handle'], 'square');?>><?php _e('Square', 'ARForms'); ?></option>
                  <option value="triangle" <?php selected($field['slider_handle'], 'triangle');?>><?php _e('Triangle', 'ARForms'); ?></option>
                  </select><?php */ ?>


                <?php
                if ($field['slider_handle'] == "") {
                    $field['slider_handle'] = "round";
                }

                $field_slider_handle_option = "";
                if ($field['slider_handle'] == 'triangle') {
                    $field_slider_handle_option = __('Triangle', 'ARForms');
                } else if ($field['slider_handle'] == 'square') {
                    $field_slider_handle_option = __('Square', 'ARForms');
                } else {
                    $field_slider_handle_option = __('Round', 'ARForms');
                }
                ?>

                                                                <input id="slider_handle_<?php echo $field['id'] ?>" name="field_options[slider_handle_<?php echo $field['id'] ?>]" value="<?php echo $field['slider_handle']; ?>" type="hidden" onchange="arf_change_slider_class(<?php echo $field['id'] ?>);" >

                                                                <dl class="arf_selectbox" data-name="field_options[slider_handle_<?php echo $field['id'] ?>]" data-id="slider_handle_<?php echo $field['id'] ?>" style="width:160px;">

                                                                    <dt class="slider_handle_<?php echo $field['id'] ?>_dt"><span><?php echo $field_slider_handle_option; ?></span>
                                                                    <input value="<?php echo $field_slider_handle_option; ?>" style="display:none;width:148px;" class="arf_autocomplete" type="text">
                                                                    <i class="fa fa-caret-down fa-lg"></i>
                                                                    </dt>

                                                                    <dd>
                                                                        <ul style="display: none;" data-id="slider_handle_<?php echo $field['id'] ?>">
                                                                            <li class="arf_selectbox_option" data-value="round" data-label="<?php _e('Round', 'ARForms'); ?>"><?php _e('Round', 'ARForms'); ?></li>
                                                                            <li class="arf_selectbox_option" data-value="square" data-label="<?php _e('Square', 'ARForms'); ?>"><?php _e('Square', 'ARForms'); ?></li>
                                                                            <li class="arf_selectbox_option" data-value="triangle" data-label="<?php _e('Triangle', 'ARForms'); ?>"><?php _e('Triangle', 'ARForms'); ?></li>
                                                                        </ul>
                                                                    </dd>
                                                                </dl>

                                                            </div>

                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'numberofsteps':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Step', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[slider_step_<?php echo $field['id']; ?>]" value="<?php echo $slider_step; ?>"  style="width:210px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'defaultvalue':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Default value', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[slider_value_<?php echo $field['id']; ?>]" value="<?php echo $field['slider_value']; ?>"  style="width:180px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'trackbgcolor':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><?php _e('Track BG color', 'ARForms') ?>:</tr>

                                                    <tr class="fieldoptions_field_style">
                                                        <td style="width:90px; font-size:14px; float:left;">
                                                            <div class="arf_coloroption_sub">
                                                                <div class="arf_coloroption arfhex" data-fid="slider_bg_color_<?php echo $field['id'] ?>" style="background:<?php echo esc_attr($slider_left_bg_color); ?>;"></div>
                                                                <div class="arf_coloroption_subarrow_bg">
                                                                    <div class="arf_coloroption_subarrow"></div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="field_options[slider_bg_color_<?php echo $field['id'] ?>]" id="slider_bg_color_<?php echo $field['id'] ?>" class="txtstandardnew hex" value="<?php echo esc_attr($slider_left_bg_color); ?>" style="width:80px;" size="5" /><!--<br />--><span class="howto" style="padding-left:0px;float:left; margin-top: 2px;"><?php _e('Left side', 'ARForms') ?></span></td>

                                                        <td style="float:left; width:92px; font-size:14px; margin-left:15px;">
                                                            <div class="arf_coloroption_sub">
                                                                <div class="arf_coloroption arfhex" data-fid="slider_bg_color2_<?php echo $field['id'] ?>" style="background:<?php echo esc_attr($slider_right_bg_color); ?>;"></div>
                                                                <div class="arf_coloroption_subarrow_bg">
                                                                    <div class="arf_coloroption_subarrow"></div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="field_options[slider_bg_color2_<?php echo $field['id'] ?>]" id="slider_bg_color2_<?php echo $field['id'] ?>" class="txtstandardnew hex" value="<?php echo esc_attr($slider_right_bg_color); ?>" style="width:80px;" size="5" /><!--<br />--><div class="howto" style="padding-right:10px; margin-top:2px;"><?php _e('Right side', 'ARForms') ?></div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'handlecolor':
                                                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Handle color', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;">
                                                                <div class="arf_coloroption_sub">
                                                                    <div class="arf_coloroption arfhex" data-fid="slider_handle_color_<?php echo $field['id'] ?>" style="background:<?php echo $slider_handle_color; ?>;"></div>
                                                                    <div class="arf_coloroption_subarrow_bg">
                                                                        <div class="arf_coloroption_subarrow"></div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" class="hex txtstandardnew" name="field_options[slider_handle_color_<?php echo $field['id']; ?>]" id="slider_handle_color_<?php echo $field['id']; ?>" value="<?php echo $slider_handle_color; ?>"  style="width:100px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'placeholdertext':
                $field['default_value'] = $field['default_value'] ? $field['default_value'] : '';
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Placeholder Text', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[placeholdertext_<?php echo $field['id']; ?>]" id="placeholdertext_<?php echo $field['id']; ?>" onkeyup="arfchangeplaceholder('<?php echo $field['id']; ?>');" value="<?php echo $field['default_value']; ?>"  style="width:185px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'fielddescription':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Field Description', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[description_<?php echo $field['id']; ?>]" id="description_<?php echo $field['id']; ?>" value="<?php echo $field['description']; ?>" style="width:185px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                                <?php
                                                                break;

                                                            case 'labelname':
                                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Label Name', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[name_<?php echo $field['id']; ?>]" id="arfname_<?php echo $field['id']; ?>" onkeyup="arfchangelabelname('<?php echo $field['id']; ?>');" value="<?php echo $field['name']; ?>" style="width:185px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                                <?php
                                                                break;

                                                            case 'phone_validation':

                                                                $field['phone_validation'] = $field['phone_validation'] ? $field['phone_validation'] : 'international';
                                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Phone Number Format', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <div class="sltstandard" style="float:left;">
                                                                <?php /* ?><select name="field_options[phone_validation_<?php echo $field['id'];?>]" id="phone_validation_<?php echo $field['id'];?>" data-width="185px">
                                                                  <option value="international" <?php selected('international', $field['phone_validation']);?>>1234567890</option>
                                                                  <option value="custom_validation_1" <?php selected('custom_validation_1', $field['phone_validation']);?>>(123)456 7890</option>
                                                                  <option value="custom_validation_2" <?php selected('custom_validation_2', $field['phone_validation']);?>>(123) 456 7890</option>
                                                                  <option value="custom_validation_3" <?php selected('custom_validation_3', $field['phone_validation']);?>>(123)456-7890</option>
                                                                  <option value="custom_validation_4" <?php selected('custom_validation_4', $field['phone_validation']);?>>(123) 456-7890</option>
                                                                  <option value="custom_validation_5" <?php selected('custom_validation_5', $field['phone_validation']);?>>123 456 7890</option>
                                                                  <option value="custom_validation_6" <?php selected('custom_validation_6', $field['phone_validation']);?>>123 456-7890</option>
                                                                  <option value="custom_validation_7" <?php selected('custom_validation_7', $field['phone_validation']);?>>123-456-7890</option>
                                                                  </select><?php */ ?>

                <?php
                $selected_phone_number = "";
                if ($field['phone_validation'] == 'custom_validation_1') {
                    $selected_phone_number = "(123)456 7890";
                } else if ($field['phone_validation'] == 'custom_validation_2') {
                    $selected_phone_number = "(123) 456 7890";
                } else if ($field['phone_validation'] == 'custom_validation_3') {
                    $selected_phone_number = "(123)456-7890";
                } else if ($field['phone_validation'] == 'custom_validation_4') {
                    $selected_phone_number = "(123) 456-7890";
                } else if ($field['phone_validation'] == 'custom_validation_5') {
                    $selected_phone_number = "123 456 7890";
                } else if ($field['phone_validation'] == 'custom_validation_6') {
                    $selected_phone_number = "123 456-7890";
                } else if ($field['phone_validation'] == 'custom_validation_7') {
                    $selected_phone_number = "123-456-7890";
                } else {
                    $selected_phone_number = "1234567890";
                }
                ?>

                                                                <input id="phone_validation_<?php echo $field['id']; ?>" name="field_options[phone_validation_<?php echo $field['id']; ?>]" value="<?php echo $field['phone_validation']; ?>" type="hidden">
                                                                <dl class="arf_selectbox" data-name="field_options[phone_validation_<?php echo $field['id']; ?>]" data-id="phone_validation_<?php echo $field['id']; ?>" style="width:165px;">
                                                                    <dt><span><?php echo $field['phone_validation']; ?></span>
                                                                    <input value="<?php echo $field['phone_validation']; ?>" style="display:none;width:153px;" class="arf_autocomplete" type="text">
                                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                                    <dd>
                                                                        <ul style="display: none;" data-id="phone_validation_<?php echo $field['id']; ?>">
                                                                            <li class="arf_selectbox_option" data-value="international" data-label="1234567890">1234567890</li>
                                                                            <li class="arf_selectbox_option" data-value="custom_validation_1" data-label="(123)456 7890">(123)456 7890</li>
                                                                            <li class="arf_selectbox_option" data-value="custom_validation_2" data-label="(123) 456 7890">(123) 456 7890</li>
                                                                            <li class="arf_selectbox_option" data-value="custom_validation_3" data-label="(123)456-7890">(123)456-7890</li>
                                                                            <li class="arf_selectbox_option" data-value="custom_validation_4" data-label="(123) 456-7890">(123) 456-7890</li>
                                                                            <li class="arf_selectbox_option" data-value="custom_validation_5" data-label="123 456 7890">123 456 7890</li>
                                                                            <li class="arf_selectbox_option" data-value="custom_validation_6" data-label="123 456-7890">123 456-7890</li>
                                                                            <li class="arf_selectbox_option" data-value="custom_validation_7" data-label="123-456-7890">123-456-7890</li>

                                                                        </ul>
                                                                    </dd>
                                                                </dl>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'password_strength':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Password Strength Meter', 'ARForms') ?>:<span style="margin-left:10px;"><img src="<?php echo ARFIMAGESURL ?>/tooltips-icon.png" alt="?" class="arfhelptip" title="<?php _e('Strength Indicator will be indicate password as a strong, only when password contains alpha numeric value and special characters.', 'ARForms') ?>" align="absmiddle" /></span></td></tr>
                                                    <tr class="fieldoptions_field_style"><td><label class="lblswitch"><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[password_strength_<?php echo $field['id'] ?>]" id="password_strength_<?php echo $field['id'] ?>"  <?php echo (isset($field['password_strength']) and $field['password_strength']) ? 'checked="checked"' : ''; ?> value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label></td></tr>
                                                </table>

                <?php
                break;

            case 'confirm_password':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Confirm Password', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td><label class="lblswitch"><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[confirm_password_<?php echo $field['id'] ?>]" onchange="arfchangeconfirmpassword('<?php echo $field['id']; ?>');" id="confirm_password_<?php echo $field['id'] ?>"  <?php echo (isset($field['confirm_password']) and $field['confirm_password']) ? 'checked="checked"' : ''; ?> value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label></td></tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'confirm_password_label':

                                                $field['confirm_password_label'] = (!isset($field['confirm_password_label']) || empty($field['confirm_password_label']) ) ? __('Confirm Password', 'ARForms') : $field['confirm_password_label'];
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Confirm Password Label', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[confirm_password_label_<?php echo $field['id']; ?>]" id="confirm_password_label_<?php echo $field['id']; ?>" value="<?php echo $field['confirm_password_label']; ?>" style="width:210px;" <?php
                                                if (!isset($field['confirm_password']) or ! $field['confirm_password']) {
                                                    echo 'disabled="disabled"';
                                                }
                                                ?> /></div>
                                                        </td>
                                                    </tr>
                                                </table>




                                                <!--------->
                                                <?php
                                                break;

                                            case 'confirm_email':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Confirm Email', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td><label class="lblswitch"><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[confirm_email_<?php echo $field['id'] ?>]" onchange="arfchangeconfirmemail('<?php echo $field['id']; ?>');" id="confirm_email_<?php echo $field['id'] ?>"  <?php echo (isset($field['confirm_email']) and $field['confirm_email']) ? 'checked="checked"' : ''; ?> value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label></td></tr>
                                                </table>

                                                                                                               <?php
                                                                                                               break;
                                                                                                           case 'confirm_email_label':
                                                                                                               $field['confirm_email_label'] = (!isset($field['confirm_email_label']) || empty($field['confirm_email_label']) ) ? __('Confirm Email', 'ARForms') : $field['confirm_email_label'];
                                                                                                               ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Confirm Email Label', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[confirm_email_label_<?php echo $field['id']; ?>]" id="confirm_email_label_<?php echo $field['id']; ?>" value="<?php echo $field['confirm_email_label']; ?>" style="width:210px;" <?php
                                if (!isset($field['confirm_email']) or ! $field['confirm_email']) {
                                    echo 'disabled="disabled"';
                                }
                                ?> /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'invalid_confirm_email':
                $field['invalid_confirm_email'] = (!isset($field['invalid_confirm_email']) || empty($field['invalid_confirm_email']) ) ? __('Confirm Email does not match with email', 'ARForms') : $field['invalid_confirm_email'];
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Message for invalid Email', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[invalid_confirm_email_<?php echo $field['id']; ?>]" id="invalid_confirm_email_<?php echo $field['id']; ?>" value="<?php echo $field['invalid_confirm_email']; ?>" style="width:210px;" <?php
                if (!isset($field['confirm_email']) or ! $field['confirm_email']) {
                    echo 'disabled="disabled"';
                }
                ?> /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;
            case 'confirm_email_placeholder':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Confirm Email Placeholder', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[confirm_email_placeholder_<?php echo $field['id']; ?>]" id="confirm_email_placeholder_<?php echo $field['id']; ?>" value="<?php echo $field['confirm_email_placeholder']; ?>" style="width:185px;" <?php
                                                if (!isset($field['confirm_email']) or ! $field['confirm_email']) {
                                                    echo 'disabled="disabled"';
                                                }
                ?> /></div>
                                                        </td>
                                                    </tr>
                                                </table>




                                                <!------------>





                                                            <?php
                                                            break;

                                                        case 'invalid_password':
                                                            $field['invalid_password'] = (!isset($field['invalid_password']) || empty($field['invalid_password']) ) ? __('Confirm Password does not match with password', 'ARForms') : $field['invalid_password'];
                                                            ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Message for invalid Password', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[invalid_password_<?php echo $field['id']; ?>]" id="invalid_password_<?php echo $field['id']; ?>" value="<?php echo $field['invalid_password']; ?>" style="width:210px;" <?php
                                                            if (!isset($field['confirm_password']) or ! $field['confirm_password']) {
                                                                echo 'disabled="disabled"';
                                                            }
                                                            ?> /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'colorpicker_type':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Colorpicker Type', 'ARForms') ?>:</td></tr>

                                                    <tr class="fieldoptions_field_style">
                                                        <td class="sltstandard" style="float:none;">

                <?php /* ?><select id="arf_field_colorpicker_type_<?php echo $field['id'];?>" name="field_options[colorpicker_type_<?php echo $field['id'] ?>]" style="width:180px;" data-width="180px">
                  <option value="advanced" <?php selected($field['colorpicker_type'], 'advanced') ?>><?php _e('Advanced', 'ARForms'); ?></option>
                  <option value="basic" <?php selected($field['colorpicker_type'], 'basic') ?>><?php _e('Basic', 'ARForms'); ?></option>
                  </select><?php */ ?>


                                                <?php
                                                if ($field['colorpicker_type'] == "") {
                                                    $field['colorpicker_type'] = "advanced";
                                                }

                                                $field_colorpicker_type_option = "";
                                                if ($field['colorpicker_type'] == 'basic') {
                                                    $field_colorpicker_type_option = __('Basic', 'ARForms');
                                                } else {
                                                    $field_colorpicker_type_option = __('Advanced', 'ARForms');
                                                }
                                                ?>

                                                            <input id="arf_field_colorpicker_type_<?php echo $field['id']; ?>" name="field_options[colorpicker_type_<?php echo $field['id'] ?>]" value="<?php echo $field['colorpicker_type']; ?>" type="hidden" onchange="arf_change_slider_class(<?php echo $field['id'] ?>);" >

                                                            <dl class="arf_selectbox" data-name="field_options[colorpicker_type_<?php echo $field['id'] ?>]" data-id="arf_field_colorpicker_type_<?php echo $field['id']; ?>" style="width:160px;">

                                                                <dt class="arf_field_colorpicker_type_<?php echo $field['id']; ?>_dt"><span><?php echo $field_colorpicker_type_option; ?></span>
                                                                <input value="<?php echo $field_colorpicker_type_option; ?>" style="display:none;width:148px;" class="arf_autocomplete" type="text">
                                                                <i class="fa fa-caret-down fa-lg"></i>
                                                                </dt>

                                                                <dd>
                                                                    <ul style="display: none;" data-id="arf_field_colorpicker_type_<?php echo $field['id']; ?>">
                                                                        <li class="arf_selectbox_option" data-value="advanced" data-label="<?php _e('Advanced', 'ARForms'); ?>"><?php _e('Advanced', 'ARForms'); ?></li>
                                                                        <li class="arf_selectbox_option" data-value="basic" data-label="<?php _e('Basic', 'ARForms'); ?>"><?php _e('Basic', 'ARForms'); ?></li>
                                                                    </ul>
                                                                </dd>
                                                            </dl>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'image_url':
                                                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0"  border="0" width="100%">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Image URL', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left" width="100%">
                                                            <div style="float:left;width:100%;"><input type="text" class="txtstandardnew" name="field_options[image_url_<?php echo $field['id']; ?>]" id="arfimage_url_<?php echo $field['id']; ?>" value="<?php echo $field['image_url']; ?>" style="width:600px;float:left;" />
                                                                <button data-insert="image" data-id="<?php echo $field['id']; ?>" type="button" class="arf_modal_add_file_btn" onclick="open_arf_modal_add_file('<?php echo $field['id']; ?>', 'image');"><?php _e('Add File', 'ARForms'); ?></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'image_left':

                $arfdisabled = $field['image_center'] == 'yes' ? 'disabled="disabled"' : '';
                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Left', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" <?php echo $arfdisabled; ?> class="txtstandardnew" name="field_options[image_left_<?php echo $field['id']; ?>]" id="arfimage_left_<?php echo $field['id']; ?>" value="<?php echo $field['image_left']; ?>" style="width:150px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'image_top':
                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Top', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[image_top_<?php echo $field['id']; ?>]" id="arfimage_top_<?php echo $field['id']; ?>" value="<?php echo $field['image_top']; ?>" style="width:150px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                <?php
                break;

            case 'image_height':
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Height', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[image_height_<?php echo $field['id']; ?>]" id="arfimage_height_<?php echo $field['id']; ?>" value="<?php echo $field['image_height']; ?>" style="width:150px;" />&nbsp;px</div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'image_width':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Width', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[image_width_<?php echo $field['id']; ?>]" id="arfimage_width_<?php echo $field['id']; ?>" value="<?php echo $field['image_width']; ?>" style="width:150px;" />&nbsp;px</div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'image_horizontal_center':

                                                $field['image_center'] = isset($field['image_center']) ? $field['image_center'] : 'no';
                                                ?>
                                                <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Horizontal Center', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <input type="radio" class="rdostandard" name="field_options[image_center_<?php echo $field['id'] ?>]" id="arfimage_center_<?php echo $field['id'] ?>_0" value="no" <?php $armainhelper->checked($field['image_center'], 'no'); ?> onclick="arfimagecenteralign(<?php echo $field['id']; ?>);" /> <label for="arfimage_center_<?php echo $field['id'] ?>_0"><span></span><?php _e('No', 'ARForms') ?></label>&nbsp;<input type="radio" class="rdostandard" name="field_options[image_center_<?php echo $field['id'] ?>]" id="arfimage_center_<?php echo $field['id'] ?>_1" value="yes" <?php $armainhelper->checked($field['image_center'], 'yes'); ?> onclick="arfimagecenteralign(<?php echo $field['id']; ?>);" /> <label for="arfimage_center_<?php echo $field['id'] ?>_1"><span></span><?php _e('Yes', 'ARForms') ?></label>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'defaultcolor':
                                                $field['default_value'] = $field['default_value'] ? $field['default_value'] : '';
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Default Value', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[placeholdertext_<?php echo $field['id']; ?>]" id="placeholdertext_<?php echo $field['id']; ?>" onkeyup="arfchangeplaceholder('<?php echo $field['id']; ?>');" value="<?php echo $field['default_value']; ?>"  style="width:185px;" /></div>
                                                        </td>
                                                    </tr>
                                                </table>


                                                <?php
                                                break;

                                            case 'calendarhideshow':
                                                ?>
                                                <table style="float:left;">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Show Year/Month Dropdown', 'ARForms') ?></td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td>
                                                            <label class="lblswitch"><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="field_options[show_year_month_calendar_<?php echo $field['id'] ?>]" id="frm_show_year_month_calendar_field_<?php echo $field['id'] ?>"  <?php echo ($field['show_year_month_calendar']) ? 'checked="checked"' : ''; ?> value="1" /><label class="lblswitch"><span>&nbsp;YES</span></label>
                                                            <input type="hidden" name="field_options[frm_show_year_month_calendar_field_indicator_<?php echo $field['id'] ?>]" value="<?php $field['show_year_month_calendar'] = isset($field['show_year_month_calendar']) ? $field['show_year_month_calendar'] : '';
                                echo esc_attr($field['show_year_month_calendar']);
                                                ?>" />
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php
                                                break;

                                            case 'password_placeholder':
                                                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Confirm Password Placeholder', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style">
                                                        <td style="float:left"><div style="float:left;"><input type="text" class="txtstandardnew" name="field_options[password_placeholder_<?php echo $field['id']; ?>]" id="password_placeholder_<?php echo $field['id']; ?>" value="<?php echo $field['password_placeholder']; ?>" style="width:185px;" <?php
                                if (!isset($field['confirm_password']) or ! $field['confirm_password']) {
                                    echo 'disabled="disabled"';
                                }
                                ?> /></div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                                            <?php
                                                                            break;

                                                                        case 'minlength':
                                                                            ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Minimum Length', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td><input type="text" name="field_options[minlength_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo $field['minlength']; ?>" style="width:100px;" /></td></tr>
                                                </table>

                <?php
                break;

            case 'minlength_message':
                $field['minlength_message'] = $field['minlength_message'] != "" ? $field['minlength_message'] : "Invalid mininum characters";
                ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Message for minimum length', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td><input type="text" name="field_options[minlength_message_<?php echo $field['id'] ?>]" class="txtstandardnew" value="<?php echo $field['minlength_message']; ?>" style="width:210px;" /></td></tr>
                                                </table>

                                                                            <?php
                                                                            break;

                                                                        case 'arf_prefix':
                                                                            ?>
                                                <table style="float:left;" border="0" cellpadding="0" cellspacing="0">
                                                    <tr class="fieldoptions_label_style"><td><?php _e('Add Icon (Bootstrap Style)', 'ARForms') ?>:</td></tr>
                                                    <tr class="fieldoptions_field_style"><td style="float:left;width:100%;">
                                                            <div class="arf_field_prefix_suffix_wrapper" id="arf_field_prefix_suffix_wrapper_<?php echo $field['id']; ?>">

                                                                <div class="arf_prefix_wrapper">

                                                                    <div class="arf_prefix_suffix_container_wrapper" data-action='edit' data-field='prefix' field-id='<?php echo $field['id']; ?>' id="arf_edit_prefix_<?php echo $field['id']; ?>" data-toggle="arfmodal" href="#arf_fontawesome_modal" data-field_type='<?php echo $field['type']; ?>'>
                                                                        <div class="arf_prefix_container" id="arf_select_prefix_<?php echo $field['id']; ?>">
                <?php
                if ($field['arf_prefix_icon'] != '') {
                    echo "<i id='arf_prefix_suffix_icon' class='arf_prefix_suffix_icon fa {$field['arf_prefix_icon']}'></i>";
                } else {
                    _e('No Icon', 'ARForms');
                }
                ?>
                                                                        </div>
                                                                        <div class="arf_prefix_suffix_action_container">
                                                                            <div class="arf_prefix_suffix_action" title="<?php _e('Change Icon', 'ARForms') ?>">
                                                                                <!--i class="fa fa-edit fa-lg"></i-->
                                                                                <i class="fa fa-caret-down fa-lg"></i>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="howto"> <?php _e('Prefix', 'ARForms'); ?> </div>
                                                                </div>


                                                                <div class="arf_suffix_wrapper">

                                                                    <div class="arf_prefix_suffix_container_wrapper" data-action='edit' data-field='suffix' field-id='<?php echo $field['id']; ?>' id="arf_edit_suffix_<?php echo $field['id']; ?>" data-toggle="arfmodal" href="#arf_fontawesome_modal" data-field_type='<?php echo $field['type']; ?>'>
                                                                        <div class="arf_suffix_container" id="arf_select_suffix_<?php echo $field['id']; ?>">
                                                <?php
                                                if ($field['arf_suffix_icon'] != '') {
                                                    echo "<i id='arf_prefix_suffix_icon' class='arf_prefix_suffix_icon fa {$field['arf_suffix_icon']}'></i>";
                                                } else {
                                                    _e('No Icon', 'ARForms');
                                                }
                                                ?>
                                                                        </div>

                                                                        <div class="arf_prefix_suffix_action_container">
                                                                            <div class="arf_prefix_suffix_action" title="<?php _e('Change Icon', 'ARForms') ?>">
                                                                                <!--i class="fa fa-edit fa-lg"></i-->
                                                                                <i class="fa fa-caret-down fa-lg"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="howto"> <?php _e('Suffix', 'ARForms'); ?> </div>
                                                                </div>



                                                            </div>
                                                            <input type="hidden" name="field_options[enable_arf_prefix_<?php echo $field['id'] ?>]" id="enable_arf_prefix_<?php echo $field['id'] ?>" value="<?php echo $field['enable_arf_prefix']; ?>" />
                                                            <input type="hidden" name="field_options[arf_prefix_icon_<?php echo $field['id']; ?>]" id="arf_prefix_icon_<?php echo $field['id']; ?>" value="<?php echo $field['arf_prefix_icon']; ?>" />
                                                            <input type="hidden" name="field_options[enable_arf_suffix_<?php echo $field['id'] ?>]" id="enable_arf_suffix_<?php echo $field['id'] ?>" value="<?php echo $field['enable_arf_suffix']; ?>" />
                                                            <input type="hidden" name="field_options[arf_suffix_icon_<?php echo $field['id']; ?>]" id="arf_suffix_icon_<?php echo $field['id']; ?>" value="<?php echo $field['arf_suffix_icon']; ?>" />
                                                        </td></tr>
                                                </table>
                                                <?php
                                                break;

                                            default:
                                        }
                                    }

                                    function arf_replace_shortcodes($content = '', $entry = 0, $is_for_mail = false) {
                                        if (!$entry)
                                            return $content;

                                        $tagregexp = '';

                                        //preg_match_all("/\[(if )?($tagregexp)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);
                                        //preg_match_all("/\[(.*?)\]",$content,$matches, PREG_PATTERN_ORDER);
                                        //	preg_match_all("/\[(if )?($tagregexp)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);
                                        //preg_match_all("/\[[^\]]*\]/", $content, $matches);

                                        preg_match_all("/\[([^\]]*)\]/", $content, $matches);
                                        //echo "<pre>";print_r($matches);

                                        if ($matches and $matches[1]) {
                                            foreach ($matches[1] as $shortcode) {
                                                if ($shortcode) {
                                                    global $arffield;
                                                    $display = false;
                                                    $show = 'one';
                                                    $odd = '';

                                                    $field_ids = explode(':', $shortcode);
                                                    $field_id = end($field_ids);

                                                    $field = "";
                                                    if (count($field_ids) > 1) {
                                                        $field = $arffield->getOne($field_id);

                                                        if (!isset($field))
                                                            $field = false;

                                                        $sep = (isset($atts['sep'])) ? $atts['sep'] : ', ';
                                                    }

                                                    if ($field) {

                                                        global $arfieldhelper, $arrecordhelper;


                                                        $field->field_options = maybe_unserialize($field->field_options);

                                                        $replace_with = $arrecordhelper->get_post_or_entry_value($entry, $field, array(), $is_for_mail);

                                                        $replace_with = maybe_unserialize($replace_with);

                                                        $atts['entry_id'] = $entry->id;


                                                        $atts['entry_key'] = $entry->entry_key;


                                                        $atts['attachment_id'] = $entry->attachment_id;
                                                        
                                                        $tag = isset($tag) ? $tag : '';


                                                        $replace_with = apply_filters('arffieldsreplaceshortcodes', $replace_with, $tag, $atts, $field);


                                                        if (isset($replace_with) and is_array($replace_with))
                                                            $replace_with = implode($sep, $replace_with);





                                                        if ($field and $field->type == 'file') {

                                                            $size = (isset($atts['size'])) ? $atts['size'] : 'thumbnail';

                                                            if ($size != 'id')
                                                                $replace_with = $arfieldhelper->get_media_from_id($replace_with, $size);
                                                        }




                                                        if ($field) {


                                                            if (isset($atts['show']) and $atts['show'] == 'field_label') {


                                                                $replace_with = stripslashes($field->name);
                                                            } else if (empty($replace_with) and $replace_with != '0') {


                                                                $replace_with = '';


                                                                if ($field->type == 'number')
                                                                    $replace_with = '0';
                                                            }else {


                                                                $replace_with = $arfieldhelper->get_display_value($replace_with, $field, $atts);
                                                            }
                                                        }



                                                        if (isset($atts['sanitize']))
                                                            $replace_with = sanitize_title_with_dashes($replace_with);





                                                        if (isset($atts['sanitize_url']))
                                                            $replace_with = urlencode(htmlentities($replace_with));



                                                        if (isset($atts['clickable']))
                                                            $replace_with = make_clickable($replace_with);


                                                        if (!isset($replace_with))
                                                            $replace_with = '';


                                                        $content = str_replace('[' . $shortcode . ']', $replace_with, $content);
                                                    }
                                                }
                                            }
                                        }

                                        return $content;
                                    }

                                    function changeoptionorder($field) {
                                        if (!$field)
                                            return;

                                        global $wpdb;

                                        $option_order = @maybe_unserialize($field['option_order']);

                                        if (is_array($option_order)) {
                                            $options = $field['options'];
                                            $arr2ordered = array();

                                            foreach ($option_order as $key) {
                                                $arr2ordered[$key] = $options[$key];
                                            }
                                            return $arr2ordered;
                                        } else
                                            return $field['options'];
                                    }

                                    function array_push_after($src, $in, $pos) {
                                        if (is_int($pos))
                                            $R = array_merge(array_slice($src, 0, $pos + 1), $in, array_slice($src, $pos + 1));
                                        else {
                                            foreach ($src as $k => $v) {
                                                $R[$k] = $v;
                                                if ($k == $pos)
                                                    $R = array_merge($R, $in);
                                            }
                                        }return $R;
                                    }

                                    function get_confirm_password_field($field) {
                                        if (!$field)
                                            return;

                                        global $MdlDb, $wpdb, $armainhelper, $arfieldhelper;

                                        $key = $armainhelper->get_unique_key('', $MdlDb->fields, 'field_key');
                                        $label = $field['confirm_password_label'];
                                        $invalid = $field['invalid_password'];

                                        $field['confirm_password_field'] = $arfieldhelper->get_actual_id($field['id']);
                                        $field['id'] = rand(0000000, 9999999);
                                        $field['field_key'] = $key;
                                        $field['name'] = $label;
                                        $field['invalid'] = $invalid;
                                        $field['type'] = 'confirm_password';
                                        $field['required'] = 0;
                                        $field['password_strenth'] = 0;

                                        return $field;
                                    }

                                    function get_confirm_email_field($field) {
                                        if (!$field)
                                            return;

                                        global $MdlDb, $wpdb, $armainhelper, $arfieldhelper;

                                        $key = $armainhelper->get_unique_key('', $MdlDb->fields, 'field_key');
                                        $label = $field['confirm_email_label'];
                                        $invalid = $field['invalid_confirm_email'];

                                        $field['confirm_email_field'] = $arfieldhelper->get_actual_id($field['id']);
                                        $field['id'] = rand(0000000, 9999999);
                                        $field['field_key'] = $key;
                                        $field['name'] = $label;
                                        $field['invalid'] = $invalid;
                                        $field['type'] = 'confirm_email';
                                        $field['required'] = 0;
                                        //$field['password_strenth'] = 0;

                                        return $field;
                                    }

                                    function get_form_pagebreak_fields($form_id, $form_key, $values) {
                                        global $MdlDb, $wpdb, $arfieldhelper;
                                        $page_num = $MdlDb->get_count($MdlDb->fields, array("form_id" => $form_id, "type" => 'break'));
                                        if ($page_num > 0 && $values['fields']) {
                                            $pagebreak_fields = "0:[";
                                            $page_number = 1;
                                            foreach ($values['fields'] as $field) {
                                                if ($field['type'] == 'break') {
                                                    $pagebreak_fields .= "], " . $page_number . ": [";
                                                    $page_number++;
                                                } else {
                                                    $field['id'] = $arfieldhelper->get_actual_id($field['id']);
                                                    $pagebreak_fields .= $field['id'] . ",";
                                                }
                                            }
                                            $pagebreak_fields .= "]";

                                            return '<div><script type="text/javascript" language="javascript">if(window[\'jQuery\']){ if(!window[\'arf_page_fields\']) window[\'arf_page_fields\'] = new Array(); window[\'arf_page_fields\'][\'' . $form_id . '\'] = { ' . $pagebreak_fields . ' }; }</script></div>';
                                        }
                                    }

                                    function arf_replace_running_total_field($value, $matches, $field) {
                                        if (!$matches[1])
                                            return $value;

                                        $regexp = $matches[1];

                                        global $arfieldhelper;

                                        $total = $arfieldhelper->arf_replace_runningtotal_shortcode($regexp, $field);

                                        $replaceWith = '<div id="arf_running_total_' . $field['id'] . '" class="arf_running_total" data-arfcalc="' . $total . '">&nbsp;</div>';

                                        $regex = '/<arftotal>(.*?)<\/arftotal>/is';

                                        $value = preg_replace($regex, $replaceWith, $value);

                                        return $value;
                                    }

                                    function arf_replace_runningtotal_shortcode($content = '', $field_ref) {
                                        global $armainhelper;

                                        $tagregexp = '';

                                        preg_match_all("/\[(if )?($tagregexp)(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);

                                        if ($matches and $matches[3]) {
                                            foreach ($matches[3] as $shortcode) {
                                                if ($shortcode) {
                                                    global $arffield, $wpdb;
                                                    $display = false;
                                                    $show = 'one';
                                                    $odd = '';

                                                    $field_ids = explode(':', $shortcode);

                                                    if (is_array($field_ids)) {
                                                        $field_id = end($field_ids);
                                                        $is_checkbox = explode(".", $field_id);
                                                        $is_checkbox[1] = isset($is_checkbox[1]) ? $is_checkbox[1] : '';
                                                        if (count($is_checkbox) > 0) {
                                                            $field_id = $is_checkbox[0];
                                                            $option_id = $is_checkbox[1];
                                                        } else {
                                                            $option_id = "";
                                                        }
                                                    }

                                                    $field_id = end($field_ids);

                                                    if ($field_ref['form_id'] >= 10000) {
                                                        $get_ref_field = $wpdb->get_row($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "arf_fields WHERE ref_field_id='%d' AND form_id='%d'", $field_id, $field_ref['form_id']));
                                                        $field = $arffield->getOne($get_ref_field->id);
                                                    } else {
                                                        $field = $arffield->getOne($field_id);
                                                    }

                                                    if (!isset($field))
                                                        $field = false;

                                                    if ($field) {

                                                        $field = (array) $field;

                                                        // get default value

                                                        $value1 = '';

                                                        $field_options = maybe_unserialize($field['field_options']);
                                                        $field['default_value'] = isset($field['default_value']) ? $field['default_value'] : '';
                                                        $field_options['default_blank'] = isset($field_options['default_blank']) ? $field_options['default_blank'] : '';

                                                        if ((isset($field_options['clear_on_focus']) and $field_options['clear_on_focus'] and ! empty($field['default_value']))) {

                                                            if ($field_options['default_blank'] == 1) {
                                                                $value1 = trim($armainhelper->esc_textarea($field['default_value']));
                                                            }
                                                        } else {

                                                            if ($field_options['default_blank'] == 1) {
                                                                $value1 = trim($armainhelper->esc_textarea($field['default_value']));
                                                            }
                                                        }

                                                        //for star rating
                                                        if ($field['type'] == 'scale') {
                                                            $value1 = ( isset($field['default_value']) and $field['default_value'] != '' ) ? $field['default_value'] : '';
                                                        }
                                                        //for star rating
                                                        // for radio and select
                                                        if ($field['type'] == 'radio' || $field['type'] == 'select') {

                                                            $fieldoptions = maybe_unserialize($field['options']);

                                                            foreach ($fieldoptions as $opt_key => $opt) {
                                                                $field_val = $opt;
                                                                if (is_array($opt)) {
                                                                    $opt = $opt['label'];

                                                                    $field_val = ($field_options['separate_value']) ? $field_val['value'] : $opt;
                                                                }
                                                                if ($field['default_value'] == $field_val)
                                                                    $value1 = $field_val;
                                                            }
                                                        }
                                                        // for radio and select
                                                        // for checkbox	
                                                        if ($field['type'] == 'checkbox') {

                                                            $fieldoptions = maybe_unserialize($field['options']);

                                                            $default_value = maybe_unserialize($field['default_value']);

                                                            if (isset($option_id) && $option_id != "") {
                                                                $optionval = $fieldoptions[$option_id];

                                                                if ($field_options['separate_value'] == 1) {
                                                                    if (is_array($optionval) and ! empty($optionval)) {
                                                                        $optionvalue = $optionval['value'];
                                                                        $optionlabel = $optionval['label'];
                                                                    } else {
                                                                        $optionvalue = $optionval;
                                                                    }
                                                                } else {
                                                                    if (isset($optionvalue) and is_array($optionvalue)) {
                                                                        $optionvalue = $optionval['label'];
                                                                    } else {
                                                                        if (is_array($optionval) && count($optionval) > 0 && array_key_exists('label', $optionval))
                                                                            $optionvalue = $optionval['label'];
                                                                        else
                                                                            $optionvalue = $optionval;
                                                                    }
                                                                }

                                                                if ($armainhelper->check_selected($default_value, $optionvalue))
                                                                    $value1 = $optionvalue;
                                                            }
                                                        }

                                                        // for hidden
                                                        if ($field['type'] == 'hidden' || $field['type'] == 'like') {
                                                            $value1 = $field['default_value'];
                                                        }

                                                        if ($field['type'] == 'slider') {
                                                            $field['slider_value'] = isset($field_options['slider_value']) ? $field_options['slider_value'] : '';
                                                            $value1 = ($field['slider_value'] != '') ? $field['slider_value'] : ( is_numeric($field_options['minnum']) ? $field_options['minnum'] : 1 );
                                                        }

                                                        $value1 = trim(strtolower($value1));

                                                        $replace_with = (float) $value1 ? (float) $value1 : 0;
                                                        // end of get default value	

                                                        if (!isset($replace_with))
                                                            $replace_with = '';

                                                        $content = str_replace('[' . $shortcode . ']', $replace_with, $content);
                                                    }
                                                }
                                            }
                                        }

                                        return $content;
                                    }

                                    function arf_getall_running_total_str($form_id, $form_key, $values) {
                                        global $arfieldhelper;

                                        $returnstr = "";
                                        if ($values['fields']) {
                                            $running_total_array = array();

                                            foreach ($values['fields'] as $field) {
                                                if ($field['type'] == 'html' && $field['enable_total'] == 1) {
                                                    $regex = '/<arftotal>(.*?)<\/arftotal>/is';

                                                    preg_match($regex, $field['description'], $arftotalmatches);

                                                    if ($arftotalmatches) {
                                                        $regexp = $arftotalmatches[1];

                                                        $running_total_array[$field['id']] = $arfieldhelper->arf_replace_runningtotal_shortcode_exp($regexp, $field);
                                                    }
                                                }
                                            }

                                            if ($running_total_array) {
                                                $runningtotal_fields = "";

                                                foreach ($running_total_array as $field_id => $field_data) {
                                                    $field_id = $arfieldhelper->get_actual_id($field_id);
                                                    $runningtotal_fields .= $field_id . ": { 'regexp': '" . $field_data['regexp'] . "', 'fields':[" . $field_data['dep_fields'] . "] }, ";
                                                }

                                                $returnstr = "<script type='text/javascript' language='javascript'>";
                                                $returnstr .= 'if(window[\'jQuery\']){ if(!window[\'arf_runningtotal_fields\']) window[\'arf_runningtotal_fields\'] = new Array(); window[\'arf_runningtotal_fields\'][\'' . $form_key . '\'] = { ' . $runningtotal_fields . ' }; }';
                                                $returnstr .= "</script>";
                                            }
                                        }

                                        return $returnstr;
                                    }

                                    function arf_replace_runningtotal_shortcode_exp($content = '', $field_ref) {
                                        $tagregexp = '';

                                        preg_match_all("/\[(if )?($tagregexp)(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);

                                        if ($matches and $matches[3]) {
                                            $regexp = "";
                                            $dep_array = "";
                                            foreach ($matches[3] as $shortcode) {
                                                if ($shortcode) {
                                                    global $arffield;

                                                    $field_ids = explode(':', $shortcode);

                                                    $field_id = end($field_ids);
                                                    $field = $arffield->getOne($field_id);

                                                    if (!isset($field))
                                                        $field = false;

                                                    if ($field) {

                                                        $replace_with = $field->id ? $field_id : 0;

                                                        $dep_array .= "{'field_id': '" . $replace_with . "', 'field_type' : '" . $field->type . "'}, ";

                                                        $replace_with = "{" . $replace_with . "}";

                                                        $content = str_replace('[' . $shortcode . ']', $replace_with, $content);
                                                    }
                                                }
                                            }
                                        }
                                        $dep_array = isset($dep_array) ? $dep_array : '';
                                        return array('regexp' => $content, 'dep_fields' => $dep_array);
                                    }

                                    function arf_is_field_inregexp($content = '', $field_ref) {
                                        $tagregexp = '';

                                        preg_match_all("/\[(if )?($tagregexp)(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);

                                        if ($matches and $matches[3]) {
                                            foreach ($matches[3] as $shortcode) {
                                                if ($shortcode) {
                                                    global $arffield;

                                                    $field_ids = explode(':', $shortcode);
                                                    $field_id = end($field_ids);

                                                    $field = $arffield->getOne($field_id);

                                                    if (!isset($field))
                                                        $field = false;

                                                    if ($field) {
                                                        if ($field_ref == $field->id) {
                                                            return true;
                                                            //break;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        return false;
                                    }

                                    //post_validation_filed_display its useing to postvalidation 
                                    function post_validation_filed_display($field = '', $allfiled, $posted_item_fields) {

                                        global $wpdb, $MdlDb, $arfieldhelper;
                                        //$res = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$MdlDb->fields." WHERE form_id = %d  ORDER BY field_order", $field['form_id']), OBJECT_K);
                                        $style = '';
                                        //$field['id'] = $arfieldhelper->get_actual_id($field['id']);

                                        foreach ($allfiled as $data) {

                                            //$data->id = $arfieldhelper->get_actual_id($data->id);

                                            if ($field->id == $data->id) {

                                                $conditional_logic = maybe_unserialize($data->conditional_logic);

                                                if (isset($conditional_logic['enable']) and $conditional_logic['enable'] == 1) {

                                                    if (count($conditional_logic['rules']) > 0) {

                                                        $matched = 0;
                                                        $rule_cout = count($conditional_logic['rules']);
                                                        foreach ($conditional_logic['rules'] as $val) {
                                                            foreach ($allfiled as $data_field) {
                                                                $data_field->id = $arfieldhelper->get_actual_id($data_field->id);
                                                                if ($data_field->id == $val['field_id'])
                                                                    $res_field_send = $data_field;
                                                            }
                                                            if ($arfieldhelper->post_validation_calculate_rule($res_field_send, $val['operator'], $val['value'], @$posted_item_fields[$res_field_send->id]))
                                                                $matched++;
                                                        }

                                                        if (($conditional_logic['if_cond'] == 'all' && $rule_cout == $matched) || ($conditional_logic['if_cond'] == 'any' && $matched > 0))
                                                            $style = ($conditional_logic['display'] == 'hide') ? 'false' : 'true';
                                                        else
                                                            $style = ($conditional_logic['display'] == 'show') ? 'false' : 'true';
                                                    }
                                                }
                                            }
                                        }


                                        return $style;
                                    }

                                    //post_validation_calculate_rule its use on postvalidation value
                                    function post_validation_calculate_rule($field, $operator, $value2, $value1) {

                                        global $armainhelper, $arfieldhelper;



                                        $value2 = trim(strtolower($value2));

                                        if ($field->type == 'checkbox') {
                                            $chk = 0;
                                            $default_value = $value1;
                                            if ($default_value && is_array($default_value)) {
                                                foreach ($default_value as $chk_value) {
                                                    $value1 = trim(strtolower($chk_value));
                                                    if ($arfieldhelper->ar_match_rule($value1, $value2, $operator))
                                                        $chk++;
                                                }
                                            }

                                            if ($chk > 0)
                                                return true;
                                            else
                                                return false;
                                        } else {
                                            $value1 = trim(strtolower($value1));
                                            //echo $value1 ."==". $value2."==".$operator;
                                            //echo $arfieldhelper->ar_match_rule($value1, $value2, $operator);
                                            return $arfieldhelper->ar_match_rule($value1, $value2, $operator);
                                        }
                                    }

                                }
                                ?>