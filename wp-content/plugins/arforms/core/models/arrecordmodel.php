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
if (!(extension_loaded('geoip'))) {
    // geoip php extension loaded, don't load this library
    include("geoip.inc");
}

class arrecordmodel {

    function create($values) {

        global $wpdb, $MdlDb, $arfrecordmeta, $fid, $armainhelper, $db_record, $arfieldhelper, $arfsettings;

        $checkfield_validation = $db_record->validate($values, false, 1);
        if (count($checkfield_validation) > 0) {
            return false;
        }

        $form_id = $values["form_id"];

        $fields = $arfieldhelper->get_form_fields_tmp(false, $form_id, false, 0);

        $posted_item_fields = $values["item_meta"];

        $tempfields = array();
        foreach ($fields as $field) {
            $field_conditional_logic = maybe_unserialize($field->conditional_logic);

            if ($field_conditional_logic['enable'] == '1') {
                $display = $arfieldhelper->post_validation_filed_display($field, $fields, $values["item_meta"]);
                if ($display == 'false') {
                    $tempfields[] = $field->id;
                }
            }
        }

        //$values = apply_filters('arf_before_create_formentry', $values);
        //do_action('arfbeforecreateentry', $values);

        foreach ($values['item_meta'] as $key => $value) {
            if (is_array($tempfields)) {
                if (in_array($key, $tempfields)) {
                    unset($values['item_meta'][$key]);
                }
            }
        }

        $tmpbreaks = array();
        $allfieldsarr = array();
        $allfieldstype = array();
        foreach ($fields as $key => $postfield) {

            $allfieldsarr[] = $postfield->id;
            $allfieldstype[] = $postfield->type;
            if (is_array($tempfields) and ! empty($tempfields) and in_array($postfield->id, $tempfields)) {
                if (( $postfield->type == 'break' || $postfield->type == 'divider')) {
                    $tmpbreaks[] = $key;
                }
            }
        }


        $fieldsarray = array();
        foreach ($tmpbreaks as $key => $value) {
            $first = @$tmpbreaks[$key];
            $last = @$tmpbreaks[$key + 1];

            if (empty($last)) {
                $last = $this->get_next_page_break($allfieldsarr, $allfieldstype, $first);
            }
            
            for ($x = $first; $x <= $last; $x++) {
                if (( @$allfieldstype[$x + 1] == 'break' || @$allfieldstype[$x + 1] == 'divider' ) and ! in_array(@$allfieldsarr[$x + 1], $tempfields)) {
                    $last = $x + 1;
                    //continue;
                }

                if (@$allfieldstype[$last] == 'divider') {
                    for ($y = ($last - 1); $y >= $first; $y--) {
                        if (@$allfieldstype[$y] == 'break' and in_array(@$allfieldsarr[$y], $tempfields)) {
                            $last_new = $this->get_next_page_break($allfieldsarr, $allfieldstype, $last);
                            for ($xy = $first; $xy <= $last_new; $xy++) {
                                $fieldsarray[] = @$allfieldsarr[$xy];
                            }
                        }
                    }
                }
                $fieldsarray[] = @$allfieldsarr[$x];
            }
        }
        
        if (isset($fieldsarray) and ! empty($fieldsarray)) {

            foreach ($fieldsarray as $key => $value) {

                unset($values['item_meta'][$value]);
            }
        }

        foreach ($fields as $k => $f) {

            if (isset($fieldsarray) and ! empty($fieldsarray) and is_array($fieldsarray)) {

                if (in_array($f->id, $fieldsarray)) {

                    unset($fields[$k]);
                }
            }
        }

        foreach ($fields as $postfield) {

            $field_conditional_logic = maybe_unserialize($postfield->conditional_logic);

            if ($field_conditional_logic['enable'] == '1') {

                $display = $arfieldhelper->post_validation_filed_display($postfield, $fields, $values["item_meta"]);

                if ($display == 'true') {
                    if ($postfield->required) {

                        if ($arfsettings->form_submit_type != 1) {
                            if ($postfield->type == "file") {
                                if (@$_FILES["file" . $postfield->id]["name"] == '') {
                                    return false;
                                    break;
                                }
                            } else if ($postfield->type == 'number') {
                                if ($posted_item_fields[$postfield->id] == '' or ! is_numeric($posted_item_fields[$postfield->id])) {
                                    return false;
                                    break;
                                }
                            } else {
                                if ($posted_item_fields[$postfield->id] == '') {
                                    return false;
                                    break;
                                }
                            }
                        } else {
                            if ($postfield->type == 'number') {
                                if ($posted_item_fields[$postfield->id] == '' or ! is_numeric($posted_item_fields[$postfield->id])) {
                                    return false;
                                    break;
                                }
                            } else {

                                if ($posted_item_fields[$postfield->id] == '') {
                                    return false;
                                    break;
                                }
                            }
                        }
                    }
                }
            } else {
                                
                if ($postfield->required) {

                    if ($arfsettings->form_submit_type != 1) {
                        if ($postfield->type == "file") {
                            if (@$_FILES["file" . $postfield->id]["name"] == '') {
                                return false;
                                break;
                            }
                        } else if ($postfield->type == 'number') {
                            if ($posted_item_fields[$postfield->id] == '' or ! is_numeric($posted_item_fields[$postfield->id])) {
                                return false;
                                break;
                            }
                        } else {
                            if ($posted_item_fields[$postfield->id] == '') {
                                return false;
                                break;
                            }
                        }
                    } else {
                        if ($postfield->type == 'number') {
                            if ($posted_item_fields[$postfield->id] == '' or ! is_numeric($posted_item_fields[$postfield->id])) {
                                return false;
                                break;
                            }
                        } else {

                            if ($posted_item_fields[$postfield->id] == '') {
                                return false;
                                break;
                            }
                        }
                    }
                }
            }
        }

        $values = apply_filters('arf_before_create_formentry', $values);

        do_action('arfbeforecreateentry', $values);

        $fid = $values["form_id"];


        $new_values = array();


        $new_values['entry_key'] = $armainhelper->get_unique_key($values['entry_key'], $MdlDb->entries, 'entry_key');


        $field_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $MdlDb->fields . " WHERE form_id = %d", $fid));
        if (count($field_data) > 0) {
            foreach ($field_data as $new_field) {
                if ($new_field->type == 'scale') {
                    $values['item_meta'][$new_field->id] = ( isset($values['item_meta'][$new_field->id]) and $values['item_meta'][$new_field->id] != '' ) ? $values['item_meta'][$new_field->id] : 0;
                }
            }
        }

        $new_values['name'] = isset($values['name']) ? $values['name'] : $values['entry_key'];


        if (is_array($new_values['name']))
            $new_values['name'] = reset($new_values['name']);


        $new_values['ip_address'] = $_SERVER['REMOTE_ADDR'];





        if (isset($values['description']) and ! empty($values['description'])) {


            $new_values['description'] = $values['description'];
        } else {


            $referrerinfo = $armainhelper->get_referer_info();





            $new_values['description'] = maybe_serialize(array('browser' => $_SERVER['HTTP_USER_AGENT'],
                'referrer' => $referrerinfo,
                'http_referrer' => @$_SERVER['HTTP_REFERER'])
            );
        }


        $new_values['browser_info'] = $_SERVER['HTTP_USER_AGENT'];


        $file_url = dirname(__FILE__) . "/GeoIP.dat";

        if (!(extension_loaded('geoip'))) {
            $gi = geoip_open($file_url, GEOIP_STANDARD);
            $country_name = geoip_country_name_by_addr($gi, $new_values['ip_address']);
        } else {
            $country_name = "";
        }

        if ($country_name == "") {
            $country_name = "";
        }


        $new_values['country'] = $country_name;




        $new_values['form_id'] = isset($values['form_id']) ? (int) $values['form_id'] : null;


        $new_values['created_date'] = isset($values['created_date']) ? $values['created_date'] : current_time('mysql');


        if (isset($values['arfuserid']) and is_numeric($values['arfuserid'])) {


            $new_values['user_id'] = $values['arfuserid'];
        } else {


            global $user_ID;


            if ($user_ID)
                $new_values['user_id'] = $user_ID;
        }



        $create_entry = true;


        if ($create_entry)
            $query_results = $wpdb->insert($MdlDb->entries, $new_values);



        if (isset($query_results) and $query_results) {


            $entry_id = $wpdb->insert_id;

            global $arfsavedentries;

            $arfsavedentries[] = (int) $entry_id;

            if (isset($_REQUEST['form_display_type']) and $_REQUEST['form_display_type'] != '') {
                global $wpdb;
                $arf_meta_insert = array(
                    'entry_value' => $_REQUEST['form_display_type'],
                    'field_id' => '0',
                    'entry_id' => $entry_id,
                    'created_date' => current_time('mysql'),
                );
                $wpdb->insert($wpdb->prefix . 'arf_entry_values', $arf_meta_insert, array('%s', '%d', '%d', '%s'));
            }

            if (isset($values['item_meta']))
                $arfrecordmeta->update_entry_metas($entry_id, $values['item_meta']);

            $arfcreatedentry[$_POST['form_id']]['entry_id'] = $entry_id;

            $images_string = $_POST['imagename_' . $_POST['form_id']];

            $imagesToUpload = explode(',', $images_string);

            $upload_field_string = explode(',', $_POST['upload_field_id_' . $_POST['form_id']]);

            if (isset($_REQUEST['using_ajax']) && $_REQUEST['using_ajax'] == 'yes') {
                foreach ($imagesToUpload as $key => $image) {
                    if ($image != "") {
                        $full_image_name = pathinfo($image);
                        $image_name = $full_image_name['filename'];
                        $image_extention = $full_image_name['extension'];
                        $upload_dir = wp_upload_dir();
                        $upload_baseurl = $upload_dir['baseurl'];
                        $upload_basepath = $upload_dir['basedir'];
                        $image_path = $upload_baseurl . "/arforms/userfiles/" . $image;
                        $image_path1 = $upload_basepath . "/arforms/userfiles/" . $image;

                        $info = @getimagesize($image_path1);
                        $mime_type = $info['mime'];

                        $args = array("post_title" => $image_name . '.' . $image_extention, 'post_name' => $image_name, 'post_type' => 'attachment', 'post_mime_type' => $mime_type, "guid" => $image_path);
                        $lastid = wp_insert_post($args);
                        //$lastid = $wpdb->insert_id;

                        $path = '';
                        if (preg_match('/image\//', $mime_type)) {
                            $path = 'arforms/userfiles/thumbs/';
                        } else {
                            $path = 'arforms/userfiles/';
                        }

                        $wpdb->query($wpdb->prepare("insert into " . $wpdb->prefix . "postmeta (post_id,meta_key,meta_value) values ('%d','_wp_attached_file','%s')", $lastid, $path . $image));
                        
                        
                        
                        $field_id = isset($_POST['field_id']) ? $_POST['field_id'] : "";

                        $upload_field_key = $upload_field_string[$key];

                        $upload_field_id = $wpdb->get_row("select * from " . $wpdb->prefix . "arf_fields where field_key ='" . $upload_field_key . "'");
                        $field_id = $upload_field_id->id;

                        $entry_value = $_POST['item_meta'][$field_id];

                        $check_upload_field_available = $wpdb->get_row("select * from " . $wpdb->prefix . "arf_entry_values where field_id='" . $field_id . "' and entry_id='" . $arfcreatedentry[$_POST['form_id']]['entry_id'] . "'");
                        if ($check_upload_field_available->id != '') {
                            $wpdb->query('UPDATE ' . $wpdb->prefix . 'arf_entry_values SET entry_value="' . $lastid . '" WHERE field_id="' . $field_id . '" and entry_id="' . $arfcreatedentry[$_POST['form_id']]['entry_id'] . '"');
                        } else {
                            $wpdb->query('insert into ' . $wpdb->prefix . 'arf_entry_values (entry_value,field_id,entry_id,created_date) values("' . $lastid . '","' . $field_id . '","' . $arfcreatedentry[$_POST['form_id']]['entry_id'] . '",NOW())');
                        }
                    }
                }
            }

            $entry_id = apply_filters('arf_after_create_formentry', $entry_id, $new_values['form_id']);

            do_action('arfaftercreateentry', $entry_id, $new_values['form_id']);

            return $entry_id;
        } else
            return false;
    }

    function &destroy($id) {


        global $wpdb, $MdlDb;


        $id = (int) $id;

        $id = apply_filters('arf_before_destroy_entry', $id);

        $wpdb->query($wpdb->prepare('DELETE FROM ' . $MdlDb->entry_metas . ' WHERE entry_id=%d', $id));


        $result = $wpdb->query($wpdb->prepare('DELETE FROM ' . $MdlDb->entries . ' WHERE id=%d', $id));

        $result = apply_filters('arf_after_destroy_entry', $result);

        return $result;
    }

    function getOne($id, $meta = false) {


        global $wpdb, $MdlDb;


        $query = "SELECT it.*, fr.name as form_name, fr.form_key as form_key FROM $MdlDb->entries it 


                  LEFT OUTER JOIN $MdlDb->forms fr ON it.form_id=fr.id WHERE ";


        if (is_numeric($id))
            $query .= $wpdb->prepare('it.id=%d', $id);
        else
            $query .= $wpdb->prepare('it.entry_key=%s', $id);





        $entry = $wpdb->get_row($query);





        if ($meta and $entry) {


            global $arfrecordmeta;


            $metas = $arfrecordmeta->getAll("entry_id=$entry->id and field_id != 0");


            $entry_metas = array();


            foreach ($metas as $meta_val)
                $entry_metas[$meta_val->field_id] = $entry_metas[$meta_val->field_key] = maybe_unserialize($meta_val->entry_value);





            $entry->metas = $entry_metas;
        }





        return stripslashes_deep($entry);
    }

    function getAll($where = '', $order_by = '', $limit = '', $meta = false, $inc_form = true) {


        global $wpdb, $MdlDb, $armainhelper;





        if (is_numeric($limit))
            $limit = " LIMIT {$limit}";





        if ($inc_form) {


            $query = "SELECT it.*, fr.name as form_name,fr.form_key as form_key


                FROM $MdlDb->entries it LEFT OUTER JOIN $MdlDb->forms fr ON it.form_id=fr.id" .
                    $armainhelper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;
        } else {


            $query = "SELECT it.id, it.entry_key, it.name, it.ip_address, it.form_id, it.attachment_id, it.user_id, 

                it.created_date FROM $MdlDb->entries it" .
                    $armainhelper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;
        }


        $entries = $wpdb->get_results($query, OBJECT_K);


        unset($query);





        if ($meta and $entries) {


            if ($limit == '' and ! is_array($where) and preg_match('/^it\.form_id=\d+$/', $where)) {


                $meta_where = 'fi.form_id=' . substr($where, 11);
            } else if ($limit == '' and is_array($where) and count($where) == 1 and isset($where['it.form_id'])) {


                $meta_where = 'fi.form_id=' . $where['it.form_id'];
            } else {


                $meta_where = "entry_id in (" . implode(',', array_keys($entries)) . ")";
            }


            $query = $wpdb->prepare("SELECT entry_id, entry_value, field_id, 


                fi.field_key as field_key FROM $MdlDb->entry_metas it 


                LEFT OUTER JOIN $MdlDb->fields fi ON it.field_id=fi.id 


                WHERE $meta_where and field_id != %d", 0);





            $metas = $wpdb->get_results($query);


            unset($query);





            if ($metas) {


                foreach ($metas as $m_key => $meta_val) {


                    if (!isset($entries[$meta_val->entry_id]))
                        continue;





                    if (!isset($entries[$meta_val->entry_id]->metas))
                        $entries[$meta_val->entry_id]->metas = array();





                    $entries[$meta_val->entry_id]->metas[$meta_val->field_id] = $entries[$meta_val->entry_id]->metas[$meta_val->field_key] = maybe_unserialize($meta_val->entry_value);
                }
            }
        }





        return stripslashes_deep($entries);
    }

    function getRecordCount($where = '') {


        global $wpdb, $MdlDb, $armainhelper;


        if (is_numeric($where)) {


            $query = "SELECT COUNT(*) FROM $MdlDb->entries WHERE form_id=" . $where;
        } else {


            $query = "SELECT COUNT(*) FROM $MdlDb->entries it LEFT OUTER JOIN $MdlDb->forms fr ON it.form_id=fr.id" .
                    $armainhelper->prepend_and_or_where(' WHERE ', $where);
        }


        return $wpdb->get_var($query);
    }

    function getPageCount($p_size, $where = '') {


        if (is_numeric($where))
            return ceil((int) $where / (int) $p_size);
        else
            return ceil((int) $this->getRecordCount($where) / (int) $p_size);
    }

    function getPage($current_p, $p_size, $where = '', $order_by = '') {


        global $wpdb, $MdlDb, $armainhelper;


        $end_index = $current_p * $p_size;


        $start_index = $end_index - $p_size;

        if ($current_p != '' and $p_size != '')
            $results = $this->getAll($where, $order_by, " LIMIT $start_index,$p_size;", true);
        else
            $results = $this->getAll($where, $order_by, "", true);

        return $results;
    }

    function validate($values, $exclude = false, $unset_custom_captcha = 0) {
        
    }

    function akismet($values) {


        global $akismet_api_host, $akismet_api_port, $arfsiteurl;





        $content = '';


        foreach ($values['item_meta'] as $val) {


            if ($content != '')
                $content .= "\n\n";


            if (is_array($val))
                $val = implode(',', $val);


            $content .= $val;
        }





        if ($content == '')
            return false;





        $datas = array();


        $datas['blog'] = $arfsiteurl;


        $datas['user_ip'] = preg_replace('/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR']);


        $datas['user_agent'] = $_SERVER['HTTP_USER_AGENT'];


        $datas['referrer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;


        $datas['comment_type'] = 'ARForms';


        if ($permalink = get_permalink())
            $datas['permalink'] = $permalink;





        $datas['comment_content'] = $content;





        foreach ($_SERVER as $key => $value)
            if (!in_array($key, array('HTTP_COOKIE', 'argv')))
                $datas["$key"] = $value;





        $query_string = '';


        foreach ($datas as $key => $data)
            $query_string .= $key . '=' . urlencode(stripslashes($data)) . '&';





        $response = akismet_http_post($query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);


        return ( is_array($response) and $response[1] == 'true' ) ? true : false;
    }

    function user_can_edit($entry, $form) {

        global $db_record;

        $allowed = $db_record->user_can_edit_check($entry, $form);

        return apply_filters('arfusercanedit', $allowed, compact('entry', 'form'));
    }

    function user_can_edit_check($entry, $form) {

        global $user_ID, $armainhelper, $db_record, $arfform;



        if (!$user_ID)
            return false;



        if (is_numeric($form))
            $form = $arfform->getOne($form);



        $form->options = maybe_unserialize($form->options);


        if ($form->can_edit and isset($form->options['open_editable']) and $form->options['open_editable'] and isset($form->options['open_editable_role']) and $armainhelper->user_has_permission($form->options['open_editable_role']))
            return true;



        if (is_object($entry)) {

            if ($entry->user_id == $user_ID)
                return true;
            else
                return false;
        }



        $where = "user_id='$user_ID' and fr.id='$form->id'";

        if ($entry and ! empty($entry)) {

            if (is_numeric($entry))
                $where .= ' and it.id=' . $entry;
            else
                $where .= " and entry_key='" . $entry . "'";
        }



        return $db_record->getAll($where, '', ' LIMIT 1', true);
    }

    function get_next_page_break($allfieldsarr, $allfieldstype, $first) {

        for ($x = ($first + 1); $x <= count($allfieldsarr); $x++) {

            if ($x == count($allfieldsarr) and ( @$allfieldstype[$x] != 'break' or @$allfieldstype[$x] != 'divider' )) {
                return count($allfieldsarr) - 1;
            } else if (@$allfieldstype[$x] == 'break' || @$allfieldstype[$x] == 'divider') {
                return ($x - 1);
            }
        }
    }

}
