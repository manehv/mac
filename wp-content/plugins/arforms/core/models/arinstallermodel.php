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

class arinstallermodel {

    var $fields;
    var $forms;
    var $entries;
    var $entry_metas;
    var $autoresponder;

    function arinstallermodel() {


        global $wpdb;


        $this->fields = $wpdb->prefix . "arf_fields";


        $this->forms = $wpdb->prefix . "arf_forms";


        $this->ref_forms = $wpdb->prefix . "arf_ref_forms";


        $this->entries = $wpdb->prefix . "arf_entries";


        $this->entry_metas = $wpdb->prefix . "arf_entry_values";


        $this->autoresponder = $wpdb->prefix . "arf_autoresponder";


        $this->ar = $wpdb->prefix . "arf_ar";


        $this->views = $wpdb->prefix . "arf_views";
    }

    function upgrade($old_db_version = false) {


        global $wpdb, $arfdbversion;


        //$db_version = 15; 


        $old_db_version = (float) $old_db_version;


        if (!$old_db_version)
            $old_db_version = get_option('arf_db_version');





        if ($arfdbversion != $old_db_version) {


            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');





            $charset_collate = '';


            if ($wpdb->has_cap('collation')) {


                if (!empty($wpdb->charset))
                    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";


                if (!empty($wpdb->collate))
                    $charset_collate .= " COLLATE $wpdb->collate";
            }



            $sql = "CREATE TABLE {$this->fields} (


                id int(11) NOT NULL auto_increment,


                field_key varchar(25) default NULL,


                name text default NULL,


                description text default NULL,


                type text default NULL,


                default_value longtext default NULL,


                options longtext default NULL,


                field_order int(11) default 0,


                required int(1) default NULL,


                field_options longtext default NULL,


                form_id int(11) default NULL,


                created_date datetime NOT NULL,

				
				ref_field_id int(11) default NULL,
				
				
				new_field int(1) default 0,
				
				
				conditional_logic longtext default NULL,
								
				option_order text default NULL,
				
                PRIMARY KEY  (id),


                KEY form_id (form_id),


                UNIQUE KEY field_key (field_key)


              ) {$charset_collate};";





            dbDelta($sql);


            $sql = "CREATE TABLE {$this->forms} (


                id int(11) NOT NULL auto_increment,


                form_key varchar(25) default NULL,


                name varchar(255) default NULL,


                description text default NULL,


                is_loggedin boolean default NULL,


                can_edit boolean default NULL,


                is_template boolean default 0,


                status varchar(255) default NULL,


                options longtext default NULL,


                created_date datetime NOT NULL,


				autoresponder_id VARCHAR(255),


				autoresponder_fname VARCHAR(255),
				
				
				autoresponder_lname VARCHAR(255),


				autoresponder_email VARCHAR(255),
				
				
				is_enable int(11) NOT NULL default 1,
				
				
				columns_list text default NULL,

				
				form_css longtext default NULL,
				
				
				form_id int(11) NOT NULL default 0,
				
				
				checksavestatus int(1) NOT NULL default 0,
				
				
                PRIMARY KEY  (id),


                UNIQUE KEY form_key (form_key)


              ) {$charset_collate};";





            dbDelta($sql);

            $sql = "CREATE TABLE {$this->ref_forms} (


                id int(11) NOT NULL auto_increment,


                form_key varchar(25) default NULL,


                name varchar(255) default NULL,


                description text default NULL,


                is_loggedin boolean default NULL,


                can_edit boolean default NULL,


                is_template boolean default 0,


                status varchar(255) default NULL,


                options longtext default NULL,


                created_date datetime NOT NULL,


				autoresponder_id VARCHAR(255),


				autoresponder_fname VARCHAR(255),
				
				
				autoresponder_lname VARCHAR(255),


				autoresponder_email VARCHAR(255),
				
				
				is_enable int(11) NOT NULL default 1,
				
				
				columns_list text default NULL,

				
				form_css longtext default NULL,
				
				
				form_id int(11) NOT NULL default 0,
				
				
                PRIMARY KEY  (id),


                UNIQUE KEY form_key (form_key)


              ) AUTO_INCREMENT=10000 {$charset_collate};";





            dbDelta($sql);


            $sql = "CREATE TABLE {$this->entries} (


                id int(11) NOT NULL auto_increment,


                entry_key varchar(25) default NULL,


                name varchar(255) default NULL,


                description text default NULL,


                ip_address text default NULL,
				
				
				country text default NULL,
				
				
				browser_info text default NULL,


                form_id int(11) default NULL,


                attachment_id int(11) default NULL,


                user_id int(11) default NULL,


                created_date datetime NOT NULL,


                PRIMARY KEY  (id),


                KEY form_id (form_id),


                KEY attachment_id (attachment_id),


                KEY user_id (user_id),


                UNIQUE KEY entry_key (entry_key)


              ) {$charset_collate};";





            dbDelta($sql);



            $sql = "CREATE TABLE {$this->entry_metas} (


                id int(11) NOT NULL auto_increment,


                entry_value longtext default NULL,


                field_id int(11) NOT NULL,


                entry_id int(11) NOT NULL,


                created_date datetime NOT NULL,


                PRIMARY KEY  (id),


                KEY field_id (field_id),


                KEY entry_id (entry_id)


              ) {$charset_collate};";





            dbDelta($sql);


            $sql = "CREATE TABLE {$this->autoresponder} (


					`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,


					`responder_id` INT( 11 ) NOT NULL ,


					`responder_api_key` TEXT NOT NULL ,


					`responder_list_id` TEXT NOT NULL ,


					`responder_list` VARCHAR( 255 ) NOT NULL,
					
					
					`consumer_key` VARCHAR( 255 ) NOT NULL,

					 
					`consumer_secret` VARCHAR( 255 ) NOT NULL,
					
					
					`responder_username` VARCHAR( 255 ) NOT NULL,	
					
					
					`responder_password` VARCHAR( 255 ) NOT NULL,
					
					
					`responder_web_form` TEXT NOT NULL,
					
					
					`is_verify` tinyint(1) default 0,
						
						
					`list_data` TEXT NOT NULL,
					
						
					PRIMARY KEY ( `id` )


					) {$charset_collate};";


            dbDelta($sql);


            $sql = "CREATE TABLE {$this->views} (


                id int(11) NOT NULL auto_increment,


                form_id int(11) default NULL,


                browser_info text default NULL,


                ip_address text default NULL,


                country text default NULL,


                session_id varchar(255) default NULL,


                added_date datetime NOT NULL ,


                PRIMARY KEY  (id)


              ) {$charset_collate};";





            dbDelta($sql);



            for ($i = 1; $i <= 9; $i++) {


                $sql = "INSERT INTO {$this->autoresponder} (responder_id)VALUES('" . $i . "')";


                dbDelta($sql);
            }



            $sql = "CREATE TABLE {$this->ar} (


                id int(11) NOT NULL auto_increment,


                frm_id int(11) NOT NULL,


                aweber TEXT NOT NULL,


                mailchimp TEXT NOT NULL,


                getresponse TEXT NOT NULL,
				
				
				gvo TEXT NOT NULL,
				
				
				ebizac TEXT NOT NULL,
				
				
				icontact TEXT NOT NULL,
				
				
				constant_contact TEXT NOT NULL,


				enable_ar TEXT default NULL,
				
				
                PRIMARY KEY  (id)


              ) {$charset_collate};";





            dbDelta($sql);


            update_option('arf_db_version', $arfdbversion);

            update_option('arf_global_css', '');

            $arr = array(
                'aweber_type' => 0,
                'mailchimp_type' => 0,
                'getresponse_type' => 0,
                'icontact_type' => 0,
                'constant_type' => 0,
                'gvo_type' => 0,
                'ebizac_type' => 0,
            );

            $arr_new = serialize($arr);

            update_option('arf_ar_type', $arr_new);


            $uploads = wp_upload_dir();

            $target_path = $uploads['basedir'];

            wp_mkdir_p($target_path);

            $target_path .= "/arforms";

            wp_mkdir_p($target_path);

            $target_path .= "/maincss";

            wp_mkdir_p($target_path);

            global $arfsettings;
            $arfsettings = get_transient('arf_options');

            if (!is_object($arfsettings)) {
                if ($arfsettings) {
                    $arfsettings = unserialize(serialize($arfsettings));
                } else {
                    $arfsettings = get_option('arf_options');


                    if (!is_object($arfsettings)) {
                        if ($arfsettings)
                            $arfsettings = unserialize(serialize($arfsettings));
                        else
                            $arfsettings = new arsettingmodel();
                        update_option('arf_options', $arfsettings);
                        set_transient('arf_options', $arfsettings);
                    }
                }
            }

            $arfsettings->set_default_options();

            global $style_settings, $maincontroller;

            $style_settings = get_transient('arfa_options');
            if (!is_object($style_settings)) {
                if ($style_settings) {
                    $style_settings = unserialize(serialize($style_settings));
                } else {
                    $style_settings = get_option('arfa_options');
                    if (!is_object($style_settings)) {
                        if ($style_settings)
                            $style_settings = unserialize(serialize($style_settings));
                        else
                            $style_settings = new arstylemodel();
                        update_option('arfa_options', $style_settings);
                        set_transient('arfa_options', $style_settings);
                    }
                }
            }
            $style_settings = get_option('arfa_options');
            if (!is_object($style_settings)) {
                if ($style_settings)
                    $style_settings = unserialize(serialize($style_settings));
                else
                    $style_settings = new arstylemodel();
                update_option('arfa_options', $style_settings);
            }

            $style_settings->set_default_options();
            $style_settings->store();

            if (!is_admin() and $arfsettings->jquery_css)
                $arfdatepickerloaded = true;

            include("artemplate.php");
            $wpdb->query("ALTER TABLE {$this->forms} AUTO_INCREMENT = 100");
            //$wpdb->query("ALTER TABLE {$this->ref_forms} AUTO_INCREMENT = 10000");

            $maincontroller->getwpversion();
        }

        do_action('arfafterinstall');
    }

    function get_count($table, $args = array()) {


        global $wpdb, $MdlDb;


        extract($MdlDb->get_where_clause_and_values($args));





        $query = "SELECT COUNT(*) FROM {$table}{$where}";


        $query = $wpdb->prepare($query, $values);


        return $wpdb->get_var($query);
    }

    function get_where_clause_and_values($args) {


        $where = '';


        $values = array();


        if (is_array($args)) {


            foreach ($args as $key => $value) {


                $where .= (!empty($where)) ? ' AND' : ' WHERE';


                $where .= " {$key}=";


                $where .= (is_numeric($value)) ? "%d" : "%s";





                $values[] = $value;
            }
        }





        return compact('where', 'values');
    }

    function get_var($table, $args = array(), $field = 'id', $order_by = '') {


        global $wpdb, $MdlDb;





        extract($MdlDb->get_where_clause_and_values($args));


        if (!empty($order_by))
            $order_by = " ORDER BY {$order_by}";





        $query = $wpdb->prepare("SELECT {$field} FROM {$table}{$where}{$order_by} LIMIT 1", $values);


        return $wpdb->get_var($query);
    }

    function get_col($table, $args = array(), $field = 'id', $order_by = '') {


        global $wpdb, $MdlDb;





        extract($MdlDb->get_where_clause_and_values($args));


        if (!empty($order_by))
            $order_by = " ORDER BY {$order_by}";





        $query = $wpdb->prepare("SELECT {$field} FROM {$table}{$where}{$order_by}", $values);


        return $wpdb->get_col($query);
    }

    function get_one_record($table, $args = array(), $fields = '*', $order_by = '') {


        global $wpdb, $MdlDb;





        extract($MdlDb->get_where_clause_and_values($args));





        if (!empty($order_by))
            $order_by = " ORDER BY {$order_by}";





        $query = "SELECT {$fields} FROM {$table}{$where} {$order_by} LIMIT 1";


        $query = $wpdb->prepare($query, $values);


        return $wpdb->get_row($query);
    }

    function get_records($table, $args = array(), $order_by = '', $limit = '', $fields = '*') {


        global $wpdb, $MdlDb;





        extract($MdlDb->get_where_clause_and_values($args));





        if (!empty($order_by))
            $order_by = " ORDER BY {$order_by}";





        if (!empty($limit))
            $limit = " LIMIT {$limit}";





        $query = "SELECT {$fields} FROM {$table}{$where}{$order_by}{$limit}";


        $query = $wpdb->prepare($query, $values);


        return $wpdb->get_results($query);
    }

    function assign_rand_value($num) {

        switch ($num) {
            case "1" : $rand_value = "a";
                break;
            case "2" : $rand_value = "b";
                break;
            case "3" : $rand_value = "c";
                break;
            case "4" : $rand_value = "d";
                break;
            case "5" : $rand_value = "e";
                break;
            case "6" : $rand_value = "f";
                break;
            case "7" : $rand_value = "g";
                break;
            case "8" : $rand_value = "h";
                break;
            case "9" : $rand_value = "i";
                break;
            case "10" : $rand_value = "j";
                break;
            case "11" : $rand_value = "k";
                break;
            case "12" : $rand_value = "l";
                break;
            case "13" : $rand_value = "m";
                break;
            case "14" : $rand_value = "n";
                break;
            case "15" : $rand_value = "o";
                break;
            case "16" : $rand_value = "p";
                break;
            case "17" : $rand_value = "q";
                break;
            case "18" : $rand_value = "r";
                break;
            case "19" : $rand_value = "s";
                break;
            case "20" : $rand_value = "t";
                break;
            case "21" : $rand_value = "u";
                break;
            case "22" : $rand_value = "v";
                break;
            case "23" : $rand_value = "w";
                break;
            case "24" : $rand_value = "x";
                break;
            case "25" : $rand_value = "y";
                break;
            case "26" : $rand_value = "z";
                break;
            case "27" : $rand_value = "0";
                break;
            case "28" : $rand_value = "1";
                break;
            case "29" : $rand_value = "2";
                break;
            case "30" : $rand_value = "3";
                break;
            case "31" : $rand_value = "4";
                break;
            case "32" : $rand_value = "5";
                break;
            case "33" : $rand_value = "6";
                break;
            case "34" : $rand_value = "7";
                break;
            case "35" : $rand_value = "8";
                break;
            case "36" : $rand_value = "9";
                break;
        }
        return $rand_value;
    }

    function get_rand_alphanumeric($length) {
        global $MdlDb;
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((double) microtime() * 1000000);
                $num = mt_rand(1, 36);
                $rand_id .= $MdlDb->assign_rand_value($num);
            }
        }
        return $rand_id;
    }

}

?>