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
global $wpdb, $db_record, $MdlDb, $armainhelper, $arfieldhelper, $arsettingcontroller;

if(version_compare($newdbversion, '1.0', '>') || version_compare($newdbversion, '1', '='))
{
	global $wpdb;
	
	delete_option('arftempsetting');
	
	$resval = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name = 'arf_options' ",OBJECT_K );
	foreach($resval as $mykey => $myval) 
	{
		$mynewarrsetting = addslashes($myval->option_value);
		$ins = mysql_query("insert into ".$wpdb->prefix."options (option_name,option_value,autoload) VALUES ('arftempsetting','".$mynewarrsetting."','yes') ");
		
	}
			
	if(version_compare($newdbversion, '1.2', '<'))
	{
		global $wpdb;
		$wpdb->query("RENAME TABLE ".$wpdb->prefix."arf_items TO ".$wpdb->prefix."arf_entries ");
		$wpdb->query("RENAME TABLE ".$wpdb->prefix."arf_item_metas TO ".$wpdb->prefix."arf_entry_values ");
		
		delete_option('arfa_db_version');
	}
		
	if(version_compare($newdbversion, '2.0', '<'))
	{
		require_once(MODELS_PATH.'/arsettingmodel.php');
		require_once(MODELS_PATH.'/arstylemodel.php');
		
		global $wpdb;

		$updateoptionsetting = new arsettingmodel();
		update_option('arf_options', $updateoptionsetting);
		set_transient('arf_options', $updateoptionsetting);
		
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name = 'arftempsetting' ",OBJECT_K );
		foreach($res as $key => $val) 
		{
			$optionval = $val->option_value;
			
			$optionval = str_replace("settingmodel","arsettingmodel",$optionval);
			$optionval = str_replace("O:12:","O:14:",$optionval);
			$myarr = unserialize($optionval);
			
			$res1 = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name = 'arf_options' ",OBJECT_K );
			foreach($res1 as $key1 => $val1) 
			{
				$mynewarr = unserialize($val1->option_value);
			}
			
			$mynewarr->pubkey = $myarr->pubkey;
			$mynewarr->privkey = $myarr->privkey;
			$mynewarr->re_theme = $myarr->re_theme;
			$mynewarr->re_lang = $myarr->re_lang;
			$mynewarr->re_msg = $myarr->re_msg;
			$mynewarr->success_msg = $myarr->success_msg;
			$mynewarr->failed_msg = $myarr->failed_msg;
			$mynewarr->blank_msg = $myarr->blank_msg;
			
			$mynewarr->invalid_msg = $myarr->invalid_msg;
			$mynewarr->submit_value = $myarr->submit_value;
			$mynewarr->reply_to_name = $myarr->reply_to_name;
			$mynewarr->reply_to = $myarr->reply_to;
			$mynewarr->brand = $myarr->brand;
			$mynewarr->form_submit_type = $myarr->form_submit_type;
			
			
			update_option('arf_options', $mynewarr);
			set_transient('arf_options', $mynewarr);
			
		}
		delete_option('arftempsetting');
		
		$updateoptionsetting->set_default_options(); 
		
		$updatestylesettings = new arstylemodel();
	
		update_option('arfa_options', $updatestylesettings);
		set_transient('arfa_options', $updatestylesettings);
	
		$updatestylesettings->set_default_options();
		$updatestylesettings->store();
		
		
		
		$cssoptions = get_option("arfa_options");
		$new_values = array();
	
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
	
		$filename = FORMPATH .'/core/css_create_main.php';
	
		if(is_file($filename)) 
		{
			$uploads = wp_upload_dir();
			$target_path = $uploads['basedir'];
			$target_path .= "/arforms";
			$target_path .= "/css";
			$use_saved = true; 
			$form_id = ''; 
			$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
			$css .= "\n";
			ob_start();
			include $filename;
			$css .= ob_get_contents();
			ob_end_clean();
			$css .= "\n ". $warn;
			$css_file = $target_path .'/arforms.css';
	
			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->put_contents( $css_file , $css , 0777);
	
			update_option('arfa_css', $css);
			delete_transient('arfa_css');
			set_transient('arfa_css', $css);
		}
	
		// Udpate forms with new css options and email marketer options	
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE status!='draft' order by id desc",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$form_id = $val->id;
			$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
			
			$cssoptions['arffieldinnermarginssetting'] = '6px 10px 6px 10px';
			$cssoptions['bg_inavtive_color_pg_break'] = '7ec3fc';
			
			$cssoptions['arfsubmitfontfamily'] = $cssoptions['check_font'];
			$cssoptions['arfmainfieldsetpadding_1'] = '30';
			$cssoptions['arfmainfieldsetpadding_2'] = '10';
			$cssoptions['arfmainfieldsetpadding_3'] = '30';
			$cssoptions['arfmainfieldsetpadding_4'] = '10';
			$cssoptions['arfmainformtitlepaddingsetting_1'] = '0';
			$cssoptions['arfmainformtitlepaddingsetting_2'] = '0';
			$cssoptions['arfmainformtitlepaddingsetting_3'] = '15';
			$cssoptions['arfmainformtitlepaddingsetting_4'] = '45';
			$cssoptions['arffieldinnermarginssetting_1'] = '6';
			$cssoptions['arffieldinnermarginssetting_2'] = '10';
			$cssoptions['arffieldinnermarginssetting_3'] = '6';
			$cssoptions['arffieldinnermarginssetting_4'] = '10';
			$cssoptions['arfsubmitbuttonmarginsetting_1'] = '10';
			$cssoptions['arfsubmitbuttonmarginsetting_2'] = '0';
			$cssoptions['arfsubmitbuttonmarginsetting_3'] = '0';
			$cssoptions['arfsubmitbuttonmarginsetting_4'] = '10';
			$cssoptions['arfformtitlealign'] = 'left';
			
			$cssoptions['arfcheckradiostyle'] = 'minimal';
			$cssoptions['arfcheckradiocolor'] = 'default';
			
			$sernewarr = serialize($cssoptions);
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'form_css' => $sernewarr ), array( 'id' => $val->id ) );
			
			if( count($cssoptions) > 0 )
			{
				$new_values = array();
	
				foreach($cssoptions as $k => $v)
					$new_values[$k] =  str_replace("#",'',$v);
	
				$saving = true;
				$use_saved = true;
				$filename = FORMPATH .'/core/css_create_main.php';
				$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
				$temp_css_file .= "\n";
				ob_start();
				include $filename;
				$temp_css_file .= ob_get_contents();
				ob_end_clean();
				$temp_css_file .= "\n ". $warn;
				$wp_upload_dir 	= wp_upload_dir();
				$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
				$css_file_new = $dest_dir.'maincss_'.$form_id.'.css';
	
				WP_Filesystem();
				global $wp_filesystem;
				$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
			}
	
			// form level custom css
			$unserarr = array();
			$unserarr = maybe_unserialize($val->options);
			$unserarr["arf_form_outer_wrapper"] = '';
			$unserarr["arf_form_inner_wrapper"] = '';
			$unserarr["arf_form_title"] = '';
			$unserarr["arf_form_description"] = '';
			$unserarr["arf_form_element_wrapper"] = '';
			$unserarr["arf_form_element_label"] = '';
			$unserarr["arf_form_submit_button"] = '';
			$unserarr["arf_form_success_message"] = '';
			$unserarr["arf_form_elements"] = '';
			$unserarr["arf_submit_outer_wrapper"] = '';
			$unserarr["arf_form_next_button"] = '';
			$unserarr["arf_form_previous_button"] = '';
			$unserarr["arf_form_error_message"] = '';
			$unserarr["arf_form_page_break"] = '';
			$unserarr["arf_form_fly_sticky"] = '';
			$unserarr["arf_form_modal_css"] = '';
			$unserarr["arf_form_other_css"] = $unserarr["form_custom_css"];
			
	
			$seriarr = serialize($unserarr);
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'options' => $seriarr ), array( 'id' => $val->id ) );
			// form level custom css
	
			// form level email marketing settings	
			$arsetting = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = %d", $val->id), ARRAY_A);
			$aweber_settings = maybe_unserialize($arsetting[0]["aweber"]);
			$mailchimp_settings = maybe_unserialize($arsetting[0]["mailchimp"]);
			$getresponse_settings = maybe_unserialize($arsetting[0]["getresponse"]);
			$gvo_settings = maybe_unserialize($arsetting[0]["gvo"]);
			$ebizac_settings = maybe_unserialize($arsetting[0]["ebizac"]);
			$icontact_settings = maybe_unserialize($arsetting[0]["icontact"]);
			$constant_contact_settings = maybe_unserialize($arsetting[0]["constant_contact"]);
	
			$aweber_arr = array();
			$aweber_arr['enable'] = $aweber_settings['enable'];
			$aweber_arr['type'] = $aweber_settings['type'];
			$aweber_arr['type_val'] = $aweber_settings['type_val'];
			$ar_aweber = serialize( $aweber_arr );
	
			$mailchimp_arr = array();
			$mailchimp_arr['enable'] = $mailchimp_settings['enable'];
			$mailchimp_arr['type'] = $mailchimp_settings['type'];
			$mailchimp_arr['type_val'] = $mailchimp_settings['type_val'];
			$ar_mailchimp = serialize( $mailchimp_arr );
	
			$getresponse_arr = array();
			$getresponse_arr['enable'] = $getresponse_settings['enable'];
			$getresponse_arr['type'] = $getresponse_settings['type'];
			$getresponse_arr['type_val'] = $getresponse_settings['type_val'];
			$ar_getresponse = serialize( $getresponse_arr );
	
			$gvo_arr = array();
			$gvo_arr['enable'] = $gvo_settings['enable'];
			$gvo_arr['type'] = $gvo_settings['type'];
			$gvo_arr['type_val'] = $gvo_settings['type_val'];
			$ar_gvo = serialize( $gvo_arr );
	
			$ebizac_arr = array();
			$ebizac_arr['enable'] = $ebizac_settings['enable'];
			$ebizac_arr['type'] = $ebizac_settings['type'];
			$ebizac_arr['type_val'] = $ebizac_settings['type_val'];
			$ar_ebizac = serialize( $ebizac_arr );
	
			$icontact_arr = array();
			$icontact_arr['enable'] = $icontact_settings['enable'];
			$icontact_arr['type'] = $icontact_settings['type'];
			$icontact_arr['type_val'] = $icontact_settings['type_val'];
			$ar_icontact = serialize( $icontact_arr );
	
			$constant_contact_arr = array();
			$constant_contact_arr['enable'] = $constant_contact_settings['enable'];
			$constant_contact_arr['type'] = $constant_contact_settings['type'];
			$constant_contact_arr['type_val'] = $constant_contact_settings['type_val'];
			$ar_constant_contact = serialize( $constant_contact_arr );
			
			//AR table
			$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_ar ADD `enable_ar` TEXT DEFAULT NULL ");
		
			$ar_global_autoresponder = array(
									'aweber' 			=> $aweber_arr['enable'],
									'mailchimp' 		=> $mailchimp_arr['enable'],
									'getresponse' 		=> $getresponse_arr['enable'],
									'gvo'				=> $gvo_arr['enable'],
									'ebizac' 			=> $ebizac_arr['enable'],
									'icontact'			=> $icontact_arr['enable'],
									'constant_contact' 	=> $constant_contact_arr['enable'],
									 );
			
			$enable_ar = serialize($ar_global_autoresponder);			
			$res = $wpdb->update( $wpdb->prefix."arf_ar", array( 'enable_ar' => $enable_ar ), array( 'frm_id' => $form_id ) );
									 
			$res = $wpdb->update( $wpdb->prefix."arf_ar", array( 'aweber' => $ar_aweber, 'mailchimp' => $ar_mailchimp, 'getresponse' => $ar_getresponse, 'gvo' => $ar_gvo, 'ebizac' => $ar_ebizac, 'icontact' => $ar_icontact, 'constant_contact' => $ar_constant_contact ), array( 'frm_id' => $form_id ) );
			// form level email marketing settings
	
			// for file upload,captcha and field level custom css default options
			global $arffield;
			$form_fields = $arffield->getAll("fi.form_id = ".$form_id, " ORDER BY field_order");
			foreach($form_fields as $key => $val) 
			{
				$val->field_options['is_recaptcha'] = 'recaptcha';
				$val->field_options['file_upload_text'] = 'Upload';
				$val->field_options['file_remove_text'] = 'Remove';
				$val->field_options['upload_btn_color'] = '#077bdd';
				$val->field_options['inline_css'] = '';
				$val->field_options['css_outer_wrapper'] = '';
				$val->field_options['css_label'] = '';
				$val->field_options['css_input_element'] = '';
				$val->field_options['css_description'] = '';
				
				$val->field_options['arf_divider_font'] = 'Helvetica';
				$val->field_options['arf_divider_font_size'] = '16';
				$val->field_options['arf_divider_font_style'] = 'bold';
				
				$val->field_options['arf_divider_bg_color'] = '#ffffff';
				
				$optionsnewval = serialize($val->field_options);
				$res = $wpdb->update( $wpdb->prefix."arf_fields", array( 'field_options' => $optionsnewval ), array( 'id' => $val->id ) );
			}
		}
	
		//for conditional logic
		$wpdb->query("ALTER TABLE ".$MdlDb->fields." ADD conditional_logic longtext default NULL");
	
		// For Other Database changes
		
		//Entry Values table
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entry_values CHANGE `meta_value` `entry_value` LONGTEXT DEFAULT NULL");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entry_values CHANGE `item_id` `entry_id` INT( 11 ) NOT NULL");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entry_values CHANGE `created_at` `created_date` DATETIME NOT NULL ");
		
		// Views table
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_views CHANGE `ip` `ip_address` TEXT DEFAULT NULL");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_views CHANGE `browser` `browser_info` TEXT DEFAULT NULL");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_views DROP `referer` ");
	
		// Entries table
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entries CHANGE `item_key` `entry_key` VARCHAR( 255 ) DEFAULT NULL ");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entries CHANGE `ip` `ip_address` TEXT DEFAULT NULL");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entries CHANGE `browser` `browser_info` TEXT DEFAULT NULL");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entries DROP `referer` ");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entries DROP `parent_item_id` ");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entries CHANGE `post_id` `attachment_id` INT( 11 ) DEFAULT NULL ");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_entries CHANGE `created_at` `created_date` DATETIME NOT NULL ");
	
		// Forms table
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_forms CHANGE `created_at` `created_date` DATETIME NOT NULL ");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_forms CHANGE `logged_in` `is_loggedin` TINYINT( 1 ) NULL DEFAULT NULL");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_forms CHANGE `editable` `can_edit` TINYINT( 1 ) NULL DEFAULT NULL");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_forms DROP `default_template` ");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_forms ADD `checksavestatus` INT( 1 ) NOT NULL DEFAULT '0' ");
			
		// Fields table
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_fields CHANGE `created_at` `created_date` DATETIME NOT NULL");
		
		// Delete Fake Forms
		$wpdb->query("DELETE FROM ".$wpdb->prefix."arf_forms WHERE `form_id` > 0 ");
		
		// Create Reference Form
		$charset_collate = '';
        if( $wpdb->has_cap( 'collation' ) ){
            if( !empty($wpdb->charset) )
                $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
            if( !empty($wpdb->collate) )
                $charset_collate .= " COLLATE $wpdb->collate";
        }
		
		$sql = "CREATE TABLE ".$wpdb->prefix."arf_ref_forms (
					id int(11) NOT NULL auto_increment,
					form_key varchar(255) default NULL,
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
				  ) {$charset_collate};";
	
		$wpdb->query($sql);
		
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_ref_forms AUTO_INCREMENT = 10000");
	
	}
	
	if(version_compare($newdbversion, '2.0.5', '<'))
	{
		// database update
		global $wpdb;
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_forms MODIFY `autoresponder_id` VARCHAR(255) NULL DEFAULT NULL");
		$wpdb->query("ALTER TABLE ".$wpdb->prefix."arf_ref_forms MODIFY `autoresponder_id` VARCHAR(255) NULL DEFAULT NULL");
		
		// styling updates
		$updatestylesettings = new arstylemodel();
	
		update_option('arfa_options', $updatestylesettings);
		set_transient('arfa_options', $updatestylesettings);
	
		$updatestylesettings->set_default_options();
		$updatestylesettings->store();
		
		// update arforms.css
		$cssoptions = get_option("arfa_options");
		$new_values = array();
	
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
	
		$filename = FORMPATH .'/core/css_create_main.php';
	
		if(is_file($filename)) 
		{
			$uploads = wp_upload_dir();
			$target_path = $uploads['basedir'];
			$target_path .= "/arforms";
			$target_path .= "/css";
			$use_saved = true; 
			$form_id = ''; 
			$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
			$css .= "\n";
			ob_start();
			include $filename;
			$css .= ob_get_contents();
			ob_end_clean();
			$css .= "\n ". $warn;
			$css_file = $target_path .'/arforms.css';
	
			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->put_contents( $css_file , $css , 0777);
	
			update_option('arfa_css', $css);
			delete_transient('arfa_css');
			set_transient('arfa_css', $css);
		}
		
		// Udpate forms
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE status!='draft' order by id desc",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$form_id = $val->id;
			$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
			
			
			$formoptions = maybe_unserialize($form_css_res[0]['options']);
			$formoptions['admin_email_subject'] = '[form_name] '.__('Form submitted on', 'ARForms').' [site_name] ';
			
			$sernewoptarr = serialize($formoptions);
			
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'options' => $sernewoptarr ), array( 'id' => $val->id ) );
			
			
			if( count($cssoptions) > 0 )
			{
				$new_values = array();
	
				foreach($cssoptions as $k => $v)
					$new_values[$k] =  str_replace("#",'',$v);
	
				$saving = true;
				$use_saved = true;
				$filename = FORMPATH .'/core/css_create_main.php';
				$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
				$temp_css_file .= "\n";
				ob_start();
				include $filename;
				$temp_css_file .= ob_get_contents();
				ob_end_clean();
				$temp_css_file .= "\n ". $warn;
				$wp_upload_dir 	= wp_upload_dir();
				$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
				$css_file_new = $dest_dir.'maincss_'.$form_id.'.css';
	
				WP_Filesystem();
				global $wp_filesystem;
				$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
			}
			
			
			// for file upload font color option on fields
			global $arffield;
			$form_fields = $arffield->getAll("fi.form_id = ".$form_id, " ORDER BY field_order");
			foreach($form_fields as $key => $val) 
			{
				$val->field_options['upload_font_color'] = '#ffffff';
				
				$optionsnewval = serialize($val->field_options);
				$res = $wpdb->update( $wpdb->prefix."arf_fields", array( 'field_options' => $optionsnewval ), array( 'id' => $val->id ) );
			}
			
		}
		
	}

	if(version_compare($newdbversion, '2.5', '<'))
	{
		//Database updates
		$wpdb->query("ALTER TABLE ".$MdlDb->fields." ADD option_order text default NULL");
		
		// styling updates
		
		$updatestylesettings = new arstylemodel();
	
		update_option('arfa_options', $updatestylesettings);
		set_transient('arfa_options', $updatestylesettings);
	
		$updatestylesettings->set_default_options();
		$updatestylesettings->store();
		
		// update arforms.css
		$cssoptions = get_option("arfa_options");
		$new_values = array();
	
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
	
		$filename = FORMPATH .'/core/css_create_main.php';
	
		if(is_file($filename)) 
		{
			$uploads = wp_upload_dir();
			$target_path = $uploads['basedir'];
			$target_path .= "/arforms";
			$target_path .= "/css";
			$use_saved = true; 
			$form_id = ''; 
			$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
			$css .= "\n";
			ob_start();
			include $filename;
			$css .= ob_get_contents();
			ob_end_clean();
			$css .= "\n ". $warn;
			$css_file = $target_path .'/arforms.css';
	
			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->put_contents( $css_file , $css , 0777);
	
			update_option('arfa_css', $css);
			delete_transient('arfa_css');
			set_transient('arfa_css', $css);
		}
		
		// Udpate forms
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE status!='draft' order by id desc",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$form_id = $val->id;
			$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
			
			// Udpate forms with new css options
			$cssoptions['arferrorstyle'] = 'advance';
			$cssoptions['arferrorstylecolor'] = '#ed4040|#FFFFFF|#ed4040';
			$cssoptions['arferrorstylecolor2'] = '#ed4040|#FFFFFF|#ed4040';
			$cssoptions['arferrorstyleposition'] = 'bottom';
			$cssoptions['arfsubmitautowidth'] = '100';
			$cssoptions['arftitlefontfamily'] = 'Helvetica';
			
			if($cssoptions['width_unit'] == "%")
			{
				$cssoptions['width'] = '130';
				$cssoptions['width_unit'] = 'px';
			}
			
			$sernewarr = serialize($cssoptions);
			
			$formoptions = maybe_unserialize($form_css_res[0]['options']);
			
			$shortcodes = $armainhelper->get_shortcodes($formoptions['ar_email_message'], $val->id);
			if(count($shortcodes[3]) > 0 && is_array($shortcodes[3]))
			{
				global $arffield;
				foreach($shortcodes[3] as $fieldkey => $fieldval)
				{
					$field = $arffield->getOne( $fieldval );
					$myfieldname = $field->name;
					
					$replacewith = '['.$myfieldname.':'.$fieldval.']';
					
					$formoptions['ar_email_message'] = str_replace('['.$fieldval.']', $replace_with, $formoptions['ar_email_message']);
					
				}
			}
			
			$shortcodes = $armainhelper->get_shortcodes($formoptions['ar_email_subject'], $val->id);
			if(count($shortcodes[3]) > 0 && is_array($shortcodes[3]))
			{
				global $arffield;
				foreach($shortcodes[3] as $fieldkey => $fieldval)
				{
					$field = $arffield->getOne( $fieldval );
					$myfieldname = $field->name;
					
					$replacewith = '['.$myfieldname.':'.$fieldval.']';
					
					$formoptions['ar_email_subject'] = str_replace('['.$fieldval.']', $replace_with, $formoptions['ar_email_subject']);
					
				}
			}
			
			$shortcodes = $armainhelper->get_shortcodes($formoptions['ar_user_from_email'], $val->id);
			if(count($shortcodes[3]) > 0 && is_array($shortcodes[3]))
			{
				global $arffield;
				foreach($shortcodes[3] as $fieldkey => $fieldval)
				{
					$field = $arffield->getOne( $fieldval );
					$myfieldname = $field->name;
					
					$replacewith = '['.$myfieldname.':'.$fieldval.']';
					
					$formoptions['ar_user_from_email'] = str_replace('['.$fieldval.']', $replace_with, $formoptions['ar_user_from_email']);
					
				}
			}
			
			$shortcodes = $armainhelper->get_shortcodes($formoptions['ar_admin_from_email'], $val->id);
			if(count($shortcodes[3]) > 0 && is_array($shortcodes[3]))
			{
				global $arffield;
				foreach($shortcodes[3] as $fieldkey => $fieldval)
				{
					$field = $arffield->getOne( $fieldval );
					$myfieldname = $field->name;
					
					$replacewith = '['.$myfieldname.':'.$fieldval.']';
					
					$formoptions['ar_admin_from_email'] = str_replace('['.$fieldval.']', $replace_with, $formoptions['ar_admin_from_email']);
					
				}
			}
			
			$sernewoptarr = serialize($formoptions);
			
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'form_css' => $sernewarr,'options' => $sernewoptarr ), array( 'id' => $val->id ) );
			
			if( count($cssoptions) > 0 )
			{
				$new_values = array();
	
				foreach($cssoptions as $k => $v)
					$new_values[$k] =  str_replace("#",'',$v);
	
				$saving = true;
				$use_saved = true;
				$filename = FORMPATH .'/core/css_create_main.php';
				$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
				$temp_css_file .= "\n";
				ob_start();
				include $filename;
				$temp_css_file .= ob_get_contents();
				ob_end_clean();
				$temp_css_file .= "\n ". $warn;
				$wp_upload_dir 	= wp_upload_dir();
				$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
				$css_file_new = $dest_dir.'maincss_'.$form_id.'.css';
	
				WP_Filesystem();
				global $wp_filesystem;
				$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
			}
			
			
			// for like, slider and other changes for form fields
			global $arffield;
			$form_fields = $arffield->getAll("fi.form_id = ".$form_id, " ORDER BY field_order");
			foreach($form_fields as $key => $val) 
			{
				$val->field_options['lbllike'] = __('Like', 'ARForms');
				$val->field_options['lbldislike'] = __('Dislike', 'ARForms');
				$val->field_options['slider_handle'] = 'round';
				$val->field_options['slider_step'] = '1';
				$val->field_options['slider_bg_color'] = '#d1dee5';
				$val->field_options['slider_handle_color'] = '#0480BE';
				$val->field_options['slider_value'] = '1';
				$val->field_options['like_bg_color'] = '#087ee2';
				$val->field_options['dislike_bg_color'] = '#ff1f1f';
				$val->field_options['slider_bg_color2'] = '#bcc7cd';
				$val->field_options['upload_font_color'] = '#ffffff';
				$val->field_options['confirm_password'] = '0';
				$val->field_options['password_strenth'] = '0';
				$val->field_options['invalid_password'] = __('Confirm Password does not match with password', 'ARForms');
				$val->field_options['placehodertext'] = '';
				$val->field_options['phone_validation'] = 'international';
				$val->field_options['confirm_password_label'] = __('Confirm Password', 'ARForms');
				
				
				if($val->field_options['custom_width_field'] == '0')
				{
					$val->field_options['field_width'] = '';
				}
				
				$optionsnewval = serialize($val->field_options);
				$res = $wpdb->update( $wpdb->prefix."arf_fields", array( 'field_options' => $optionsnewval ), array( 'id' => $val->id ) );
			}
			
		}
		
		// Update Existing Templates
				
		// Subscription Form
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE id = 1 and is_template = 1 ",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_id = $val->id;
			
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$formoptions = maybe_unserialize($form_css_res[0]['options']);
			
			$formoptions['display_title_form'] = '1';
			$sernewoptarr = serialize($formoptions);
						
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'options' => $sernewoptarr ), array( 'id' => $val->id ) );
		
			$cssoptions = get_option("arfa_options");
		
			$new_values = array();
			
			foreach($cssoptions as $k => $v)
				$new_values[$k] = $v;
				
			$new_values['arfmainformwidth'] = "550";
			$new_values['form_width_unit'] = "px";
			$new_values['form_border_shadow'] = "shadow";
			$new_values['arfmainformbordershadowcolorsetting'] = "#d4d2d4";
			$new_values['arfmainformtitlecolorsetting'] = "#696969";
			$new_values['arfformtitlealign'] = "center";
			$new_values['check_weight_form_title'] = "bold";
			$new_values['form_title_font_size'] = 26;
			$new_values['arfmainformtitlepaddingsetting_3'] = 25;
			$new_values['width'] = 90;
			$new_values['arfdescfontsizesetting'] = 14;
			$new_values['arfbgactivecolorsetting'] = "#fafafa";
			$new_values['arfborderactivecolorsetting'] = "#20bfe3";
			$new_values['arffieldborderwidthsetting'] = "2";
			$new_values['arffieldinnermarginssetting_1'] = "10";
			$new_values['arffieldinnermarginssetting_3'] = "10";
			$new_values['arfsubmitalignsetting'] = "auto";
			$new_values['arfsubmitbuttonwidthsetting'] = "150";
			$new_values['arfsubmitbuttonheightsetting'] = "42";
			$new_values['submit_bg_color'] = "#20bfe3";
			$new_values['arfsubmitbuttonbgcolorhoversetting'] = "#19adcf";
			$new_values['arfsubmitbordercolorsetting'] = "#e1e1e3";
			$new_values['arfsubmitshadowcolorsetting'] = "#f0f0f0";
			$new_values['arfsubmitbuttonmarginsetting_4'] = "-20";
			$new_values['arffieldmarginssetting'] = 20;
			$new_values['arferrorstyle'] = "normal";
			
			$new_values1 = maybe_serialize($new_values);
			
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'form_css' => $new_values1), array( 'id' => $val->id ) );

				
			if(!empty($new_values)){
				
				$query_results = $wpdb->query($wpdb->prepare("update ".$MdlDb->forms." set form_css = '%s' where id = '%d'", $new_values1, $form_id) );
	
					$use_saved = true; 
					$filename = FORMPATH .'/core/css_create_main.php';
					$wp_upload_dir 	= wp_upload_dir();
					$target_path 	= $wp_upload_dir['basedir'].'/arforms/maincss';
					$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
					$css .= "\n";
					ob_start();
					include $filename;
					$css .= ob_get_contents();
					ob_end_clean();
					$css .= "\n ". $warn;
					$css_file = $target_path .'/maincss_'.$form_id.'.css';
					WP_Filesystem();
					global $wp_filesystem;
					$wp_filesystem->put_contents( $css_file, $css, 0777);
					
			}
			
			$query_results_r1 = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_fields WHERE `form_id` = %d", $form_id));
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
			$field_values['name'] = 'First Name';
			$field_values['default_value'] = 'First Name';
			$field_values['description'] = '';
			$field_values['required'] = 1;
			
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
			$field_values['name'] = 'Last Name';
			$field_values['default_value'] = 'Last Name';
			$field_values['description'] = '';
			$field_values['required'] = 1;
			
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('email', $form_id));
			$field_values['name'] = 'Email';
			$field_values['default_value'] = 'Email Address';
			$field_values['required'] = 1;
			$field_values['field_options']['invalid'] = 'Please enter a valid email address';
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			unset($values);
			
			
			
		}
		
		

		
		// Contact Us Form
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE id = 3 and is_template = 1 ",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_id = $val->id;
			
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$formoptions = maybe_unserialize($form_css_res[0]['options']);
			
			$formoptions['display_title_form'] = '1';
			$sernewoptarr = serialize($formoptions);
						
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'options' => $sernewoptarr ), array( 'id' => $val->id ) );
			
			$cssoptions = get_option("arfa_options");
		
			$new_values = array();
			
			foreach($cssoptions as $k => $v)
				$new_values[$k] = $v;
				
			$new_values['arfmainformtitlecolorsetting'] = "#0d0e12";
			$new_values['arfmainformtitlepaddingsetting_3'] = 30;
			$new_values['border_radius'] = 2;
			$new_values['arffieldmarginssetting'] = 18;
			$new_values['arffieldinnermarginssetting_1'] = 10;
			$new_values['arffieldinnermarginssetting_3'] = 10;
			$new_values['arfsubmitbuttonwidthsetting'] = 120;
			$new_values['arfsubmitbuttonheightsetting'] = 40;
			$new_values['arfsubmitbuttonmarginsetting_1'] = 20;
			$new_values['arfsubmitbuttonmarginsetting_4'] = "-46";
			
			$new_values1 = maybe_serialize($new_values);
			
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'form_css' => $new_values1), array( 'id' => $val->id ) );
							
			if(!empty($new_values)){
				
				$query_results = $wpdb->query($wpdb->prepare("update ".$MdlDb->forms." set form_css = '%s' where id = '%d'", $new_values1, $form_id) );
	
					$use_saved = true; 
					$filename = FORMPATH .'/core/css_create_main.php';
					$wp_upload_dir 	= wp_upload_dir();
					$target_path 	= $wp_upload_dir['basedir'].'/arforms/maincss';
					$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
					$css .= "\n";
					ob_start();
					include $filename;
					$css .= ob_get_contents();
					ob_end_clean();
					$css .= "\n ". $warn;
					$css_file = $target_path .'/maincss_'.$form_id.'.css';
					WP_Filesystem();
					global $wp_filesystem;
					$wp_filesystem->put_contents( $css_file, $css, 0777);
					
			}
			
			$query_results_r1 = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_fields WHERE `form_id` = %d", $form_id));
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
			$field_values['name'] = 'First Name';
			$field_values['description'] = '';
			$field_values['required'] = 1;
			$field_values['field_order'] = '1';
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
			$field_values['name'] = 'Last Name';
			$field_values['required'] = 1;
			$field_values['field_order'] = '2';
			$field_values['field_options']['label'] = 'hidden';
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('email', $form_id));
			$field_values['name'] = __('Email', 'ARForms');
			$field_values['required'] = 1;
			$field_values['field_options']['invalid'] = __('Please enter a valid email address', 'ARForms');
			$field_values['field_order'] = '3';
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('url', $form_id));
			$field_values['name'] = __('Website', 'ARForms');
			$field_values['field_options']['invalid'] = __('Please enter a valid website', 'ARForms');
			$field_values['field_order'] = '4';
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
			$field_values['name'] = __('Subject', 'ARForms');
			$field_values['required'] = 1;
			$field_values['field_order'] = '5';
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('textarea', $form_id));
			$field_values['name'] = __('Message', 'ARForms');
			$field_values['required'] = 1;
			$field_values['field_order'] = '6';
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('captcha', $form_id));
			$field_values['name'] = __('Captcha', 'ARForms');
			$field_values['field_options']['label'] = 'none';
			$field_values['field_options']['is_recaptcha'] = 'custom-captcha';
			$field_values['field_order'] = '7';
			$arffield->create( $field_values );
			unset($field_values);
			unset($values);
			
		}
		
		// Survey Form
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE id = 4 and is_template = 1 ",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_id = $val->id;
			
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$formoptions = maybe_unserialize($form_css_res[0]['options']);
			
			$formoptions['display_title_form'] = '1';
			$formoptions['arf_form_title'] = "border-bottom:1px solid #4a494a;padding-bottom:5px;";
			$sernewoptarr = serialize($formoptions);
						
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'options' => $sernewoptarr ), array( 'id' => $val->id ) );
			
			$cssoptions = get_option("arfa_options");
		
			$new_values = array();
			
			foreach($cssoptions as $k => $v)
				$new_values[$k] = $v;
				
			$new_values['fieldset'] = "0";
			$new_values['arfformtitlealign'] = "center";
			$new_values['check_weight_form_title'] = "bold";
			$new_values['form_title_font_size'] = "32";
			
			$new_values1 = maybe_serialize($new_values);
			
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'form_css' => $new_values1), array( 'id' => $val->id ) );
							
			if(!empty($new_values)){
				
				$query_results = $wpdb->query($wpdb->prepare("update ".$MdlDb->forms." set form_css = '%s' where id = '%d'", $new_values1, $form_id) );
	
					$use_saved = true; 
					$filename = FORMPATH .'/core/css_create_main.php';
					$wp_upload_dir 	= wp_upload_dir();
					$target_path 	= $wp_upload_dir['basedir'].'/arforms/maincss';
					$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
					$css .= "\n";
					ob_start();
					include $filename;
					$css .= ob_get_contents();
					ob_end_clean();
					$css .= "\n ". $warn;
					$css_file = $target_path .'/maincss_'.$form_id.'.css';
					WP_Filesystem();
					global $wp_filesystem;
					$wp_filesystem->put_contents( $css_file, $css, 0777);
					
			}
			
			$query_results_r1 = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_fields WHERE `form_id` = %d", $form_id));
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('radio', $form_id));
			$field_values['name'] = '1. When you visit ARForms, do you see it as... (choose one)';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('Problem solvers','An inspiration','Ideas generator','Solution'));
			$field_values['field_options']['css_input_element'] = 'padding-top:10px;padding-left:20px;';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('checkbox', $form_id));
			$field_values['name'] = '2. Which words best describe ARForms? (choose as many that apply)';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('Unhelpful','Difficult to use','Supportive','Solutions focused','Good value','Global','Community based','Friendly','Creative','Inspiring','Developer world'));
			$field_values['field_options']['css_input_element'] = 'padding-top:10px;padding-left:20px;';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('radio', $form_id));
			$field_values['name'] = '3. Which best describes your relationship with ARForms?';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('I am aware of it','Rarely use it','Use it sometimes','Frequent user','Do not know it'));
			$field_values['field_options']['css_input_element'] = 'padding-top:10px;padding-left:20px;';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('radio', $form_id));
			$field_values['name'] = '4. When I visit ARForms for something I need to work on, I feel...(choose one)';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('Concerned I won\'t be able to find what I am looking for','Inspired','Reluctant','Indifferent','Excited to be starting a project','Know I will end up browsing lots of things'));
			$field_values['field_options']['css_input_element'] = 'padding-top:10px;padding-left:20px;';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('radio', $form_id));
			$field_values['name'] = '5. Which of the following best describes your area of work?';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('Administrative','Computing','Web Design','Creative','Web Development','Marketing','Technical'));
			$field_values['field_options']['css_input_element'] = 'padding-top:10px;padding-left:20px;';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('radio', $form_id));
			$field_values['name'] = '6. How often do you use ARForms?';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('It is my first time','Weekly','Monthly','Quarterly','Annually','Occasionally'));
			$field_values['field_options']['css_input_element'] = 'padding-top:10px;padding-left:20px;';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('textarea', $form_id));
			$field_values['name'] = 'Other Comments About ARForms';
			$field_values['required'] = 0;
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			unset($values);
			
		}
		
		// RSVP Form
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE id = 6 and is_template = 1 ",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_id = $val->id;
			
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$formoptions = maybe_unserialize($form_css_res[0]['options']);
			
			$formoptions['display_title_form'] = '1';
			$formoptions['arf_form_title'] = "background-color:rgb(147, 217, 226);padding: 10px;border-radius:5px;";
			$sernewoptarr = serialize($formoptions);
						
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'options' => $sernewoptarr ), array( 'id' => $val->id ) );
			
			$cssoptions = get_option("arfa_options");
		
			$new_values = array();
			
			foreach($cssoptions as $k => $v)
				$new_values[$k] = $v;
				
			$new_values['form_border_shadow'] = "shadow";
			$new_values['form_border_shadow'] = 1;
			$new_values['arfmainfieldsetcolor'] = "#c9c7c9";
			$new_values['arfmainformbordershadowcolorsetting'] = "#ebebeb";
			$new_values['arfmainformtitlecolorsetting'] = "#ffffff";
			$new_values['arfformtitlealign'] = "center";
			$new_values['arftitlefontfamily'] = "Courier";
			$new_values['check_weight_form_title'] = "bold";
			$new_values['form_title_font_size'] = 28;
			$new_values['arfmainformtitlepaddingsetting_3'] = 30;
			$new_values['check_font'] = "sans-serif";
			$new_values['text_color'] = "#384647";
			$new_values['arfborderactivecolorsetting'] = "#6fdeed";
			$new_values['arferrorbordercolorsetting'] = "#f28888";
			$new_values['arfcheckradiocolor'] = "aero";
			$new_values['arfsubmitfontfamily'] = "Verdana";
			$new_values['arfsubmitweightsetting'] = "bold";
			$new_values['arfsubmitbuttonfontsizesetting'] = "19";
			$new_values['arfsubmitbuttonwidthsetting'] = "140";
			$new_values['arfsubmitbuttonheightsetting'] = "44";
			$new_values['submit_bg_color'] = "#84d1db";
			$new_values['arfsubmitbuttonbgcolorhoversetting'] = "#6ac7d4";
			$new_values['arfsubmitshadowcolorsetting'] = "#f0f0f0";
			$new_values['arfsubmitbuttonmarginsetting_1'] = "15";
			$new_values['arfsubmitbuttonmarginsetting_4'] = "-45";
			$new_values['arferrorstylecolor'] = "#F2DEDE|#A94442|#508b27";
			
			$new_values1 = maybe_serialize($new_values);
			
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'form_css' => $new_values1), array( 'id' => $val->id ) );
							
			if(!empty($new_values)){
				
				$query_results = $wpdb->query($wpdb->prepare("update ".$MdlDb->forms." set form_css = '%s' where id = '%d'", $new_values1, $form_id) );
	
					$use_saved = true; 
					$filename = FORMPATH .'/core/css_create_main.php';
					$wp_upload_dir 	= wp_upload_dir();
					$target_path 	= $wp_upload_dir['basedir'].'/arforms/maincss';
					$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
					$css .= "\n";
					ob_start();
					include $filename;
					$css .= ob_get_contents();
					ob_end_clean();
					$css .= "\n ". $warn;
					$css_file = $target_path .'/maincss_'.$form_id.'.css';
					WP_Filesystem();
					global $wp_filesystem;
					$wp_filesystem->put_contents( $css_file, $css, 0777);
					
			}
			
			$query_results_r1 = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_fields WHERE `form_id` = %d", $form_id));
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
			$field_values['name'] = 'Full Name';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('email', $form_id));
			$field_values['name'] = 'Email';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('phone', $form_id));
			$field_values['name'] = 'Phone';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('textarea', $form_id));
			$field_values['name'] = 'Address';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
			$field_values['name'] = 'City';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('select', $form_id));
			$field_values['name'] = 'Your Meal Selection';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('Chicken','Steak','Vegetarian'));
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('select', $form_id));
			$field_values['name'] = 'Are you bringing a guest?';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('Yes','No'));
			$bringing_guest_field_id = $arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('select', $form_id));
			$field_values['name'] = 'How many guests will be there?';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('One','Two', 'Three', 'Four'));
			$conditional_rule = array(
								'1' => array(
										'id'	     => 1,
										'field_id'   => $bringing_guest_field_id,
										'field_type' => 'select',
										'operator'	 => 'equals',
										'value'		 => 'Yes',
									),
							);		
			$conditional_logic_exp = array(
										'enable'	=>	1,
										'display'	=>	'show',
										'if_cond'	=>	'all',
										'rules'		=>	$conditional_rule,
									 );
			$field_values['conditional_logic'] = maybe_serialize($conditional_logic_exp);
			
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('time', $form_id));
			$field_values['name'] = 'Which is your suitable time?';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$arffield->create( $field_values );
			unset($field_values);
			
			$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('radio', $form_id));
			$field_values['name'] = 'How much interested in our ARForms?';
			$field_values['required'] = 1;
			$field_values['field_options']['classes'] = '';
			$field_values['options'] = maybe_serialize(array('Extremely','Very','Moderately','Slightly','Not Excited'));
			$arffield->create( $field_values );
			unset($field_values);
			unset($values);
			
		}
		
		// Add Job Application Template
		global $arffield, $arfform, $MdlDb, $wpdb;
		
		$values['name'] = __('Job Application Form','ARForms');
		$values['description'] = '';
		$values['options']['custom_style'] = 1;
		$values['options']['display_title_form'] = 1;
		$values['is_template'] = '1';
		$values['status'] = 'published';
		$values['form_key'] = 'JobApplication';
		$values['options']['display_title_form'] = "1";
		$values['options']['arf_form_description'] = "margin:0px !important;";
				
		$form_id = $arfform->create( $values );
		
		$updatestat = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_forms set id = '7' where id = %d", $form_id) );
		
		$form_id = '7';
		
		$cssoptions = get_option("arfa_options");
		
		$new_values = array();
		
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
		
		$new_values['arfmainformwidth'] = "800";
		$new_values['arfmainformbgcolorsetting'] = "#fcfcfc";
		$new_values['form_width_unit'] = "px";
		$new_values['form_border_shadow'] = "shadow";
		$new_values['fieldset'] = "1";
		$new_values['arfmainfieldsetcolor'] = "#e0e0de";
		$new_values['arfmainformbordershadowcolorsetting'] = "#dedede";
		$new_values['arfmainfieldsetpadding_1'] = "20";
		$new_values['arfmainfieldsetpadding_2'] = "30";
		$new_values['arfmainfieldsetpadding_4'] = "30";
		$new_values['arfmainformtitlecolorsetting'] = "#767a74";
		$new_values['arfformtitlealign'] = "center";
		$new_values['check_weight_form_title'] = "bold";
		$new_values['arfmainformtitlepaddingsetting_3'] = "30";
		$new_values['label_color'] = "#787778";
		$new_values['weight'] = "bold";
		$new_values['font_size'] = "14";
		$new_values['text_color'] = "#565657";
		$new_values['bg_color'] = "#fffcff";
		$new_values['arfbgactivecolorsetting'] = "#f5f9fc";
		$new_values['arferrorbordercolorsetting'] = "#ebc173";
		$new_values['border_radius'] = "2";
		$new_values['border_color'] = "#b0b0b5";
		$new_values['arffieldmarginssetting'] = "18";
		$new_values['arfcheckradiostyle'] = "square";
		$new_values['arfcheckradiocolor'] = "yellow";
		$new_values['arfsubmitalignsetting'] = "auto";
		$new_values['arfsubmitbuttonwidthsetting'] = "100";
		$new_values['arfsubmitbuttonheightsetting'] = "45";
		$new_values['arfsubmitbuttontext'] = "Apply Now";
		$new_values['submit_bg_color'] = "#a969e0";
		$new_values['arfsubmitbuttonbgcolorhoversetting'] = "#9249d1";
		$new_values['arfsubmitbuttonmarginsetting_1'] = "0";
		$new_values['error_font'] = "Verdana";
		$new_values['arffontsizesetting'] = "11";
		$new_values['arferrorstylecolor'] = "#FAEBCC|#8A6D3B|#af7a0c";
		$new_values['arferrorstyleposition'] = "right";
		$new_values['arfborderactivecolorsetting'] = '#a969e0';
		
		
		
		
		$new_values1 = maybe_serialize($new_values);
						
		if(!empty($new_values)){
			
			$query_results = $wpdb->query($wpdb->prepare("update ".$MdlDb->forms." set form_css = '%s' where id = '%d'", $new_values1, $form_id) );

				$use_saved = true;
				
				$filename = FORMPATH .'/core/css_create_main.php';
				
				$wp_upload_dir 	= wp_upload_dir();
				
				$target_path 	= $wp_upload_dir['basedir'].'/arforms/maincss';
								
				$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
	
	
				$css .= "\n";
	
	
				ob_start();
	
	
				include $filename;
	
	
				$css .= ob_get_contents();
	
	
				ob_end_clean();
	
	
				$css .= "\n ". $warn;
	
				$css_file = $target_path .'/maincss_'.$form_id.'.css';
				
						WP_Filesystem();
						global $wp_filesystem;
						$wp_filesystem->put_contents( $css_file, $css, 0777);	
						
				wp_cache_delete( $form_id, 'arfform');
				
		}else{
	
			$query_results = true;	
		}
		
			
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = __('First Name','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['default_value'] = 'First Name';
		$field_values['field_options']['blank'] = 'Please Enter First Name';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = __('Last name','ARForms');
		$field_values['required'] = 0;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['default_value'] = 'Last Name';
		$field_values['field_options']['blank'] = 'Please Enter Last Name';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('email', $form_id));
		$field_values['name'] = __('Email','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['default_value'] = 'Email';
		$field_values['field_options']['blank'] = 'Please Enter Email';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('phone', $form_id));
		$field_values['name'] = __('Contact No','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['default_value'] = 'Contact No';
		$field_values['field_options']['blank'] = 'Please Enter Contact No';
		$arffield->create( $field_values );
		unset( $field_values );
	
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('textarea', $form_id));
		$field_values['name'] = __('Address','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$field_values['field_options']['blank'] = 'Please Enter Address';
		$field_values['field_options']['max'] = '2';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('select', $form_id));
		$field_values['name'] = __('Position apply for?','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['options'] = maybe_serialize(array('',__('Developer','ARForms'),__('Manager','ARForms'),__('Clerk','ARForms'),__('Representative','ARForms')));
		$field_values['field_options']['blank'] = 'Please Select Position';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('select', $form_id));
		$field_values['name'] = __('Are you applying for?','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['options'] = maybe_serialize(array('',__('Full Time','ARForms'),__('Part Time','ARForms')));
		$field_values['field_options']['blank'] = 'Please Select Applying for';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('divider', $form_id));
		$field_values['name'] = __('Education and Experience Details','ARForms');
		$field_values['required'] = 0;
		$field_values['field_options']['css_label'] = 'padding-top:20px;margin-bottom:20px;';
		$field_values['field_options']['arf_divider_font'] = 'Arial';
		$field_values['field_options']['arf_divider_font_size'] = '18';
		$field_values['field_options']['arf_divider_bg_color'] = '#fcfcfc';
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = __('Diploma / Degree Name','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['field_options']['blank'] = 'Please Enter Diploma / Degree';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = __('College / University Name','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['field_options']['blank'] = 'Please Enter College / University';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('number', $form_id));
		$field_values['name'] = __('Graduation Year','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['field_options']['blank'] = 'Please Enter Graduation Year';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = __('Percentage','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['field_options']['blank'] = 'Please Enter Percentage';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('textarea', $form_id));
		$field_values['name'] = __('Skills & Qualification','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$field_values['field_options']['blank'] = 'Please Enter Skills & Qualification';
		$field_values['field_options']['max'] = '2';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('number', $form_id));
		$field_values['name'] = __('Desired Salary','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['field_options']['blank'] = 'Please Enter Desired Salary';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('radio', $form_id));
		$field_values['name'] = __('Fresher / Experienced','ARForms');
		$field_values['required'] = 1;
		$field_values['options'] = maybe_serialize(array(__('Fresher','ARForms'),__('Experienced','ARForms')));
		$field_values['field_options']['align'] = 'inline';
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['field_options']['blank'] = 'Please Select Fresher / Experienced';
		$frsh_exp_id = $arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = __('Experience','ARForms');
		$field_values['description'] = __('(e.g. 3 months, 2 years etc)','ARForms');
		$field_values['required'] = 1;
		$conditional_rule = array(
								'1' => array(
										'id'	     => 1,
										'field_id'   => $frsh_exp_id,
										'field_type' => 'radio',
										'operator'	 => 'equals',
										'value'		 => __('Experienced','ARForms'),
									),
							);		
		$conditional_logic_exp = array(
									'enable'	=>	1,
									'display'	=>	'show',
									'if_cond'	=>	'all',
									'rules'		=>	$conditional_rule,
								 );
		$field_values['conditional_logic'] = maybe_serialize($conditional_logic_exp);
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['field_options']['blank'] = 'Please Enter Experience';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('number', $form_id));
		$field_values['name'] = __('Current Salary','ARForms');
		$field_values['required'] = 1;
		$conditional_rule = array(
								'1' =>	array(
										'id'	     => 1,
										'field_id'   => $frsh_exp_id,
										'field_type' => 'radio',
										'operator'	 => 'equals',
										'value'		 => __('Experienced','ARForms')
									),
							);		
		$conditional_logic_exp = array(
								'enable'	=> 1,
								'display'	=> 'show',
								'if_cond'	=> 'all',
								'rules'		=> $conditional_rule
							 );
		$field_values['conditional_logic'] = maybe_serialize($conditional_logic_exp);
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['field_options']['blank'] = 'Please Enter Current Salary';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('textarea', $form_id));
		$field_values['name'] = __('Current Company Detail','ARForms');
		$field_values['required'] = 1;
		$conditional_rule = array(
								'1' =>	array(
										'id'	     => 1,
										'field_id'   => $frsh_exp_id,
										'field_type' => 'radio',
										'operator'	 => 'equals',
										'value'		 => __('Experienced','ARForms')
									),
							);		
		$conditional_logic_exp = array(
								'enable'	=> 1,
								'display'	=> 'show',
								'if_cond'	=> 'all',
								'rules'		=> $conditional_rule
							);
		$field_values['conditional_logic'] = maybe_serialize($conditional_logic_exp);
		$field_values['field_options']['classes'] = '';
		$field_values['field_options']['blank'] = 'Please Enter Current Company Detail';
		$arffield->create( $field_values );
		unset( $field_values );
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('file', $form_id));
		$field_values['name'] = __('Upload Resume','ARForms');
		$field_values['required'] = 1;
		$field_values['field_options']['restrict'] = 1;
		$field_values['field_options']['upload_btn_color'] = '#a969e0';
		$field_values['field_options']['ftypes'] = array('doc'=>'application/msword','docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document','pdf'=>'application/pdf','txt|asc|c|cc|h'=>'text/plain','rtf'=>'application/rtf');
		$field_values['field_options']['classes'] = 'arf_2';
		$field_values['field_options']['blank'] = 'Please Select Resume';
		$arffield->create( $field_values );
		unset( $field_values );
		unset($values);
		
		
	}
	
	if(version_compare($newdbversion, '2.5.2', '<'))
	{
		// Udpate forms
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE status!='draft' order by id desc",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$form_id = $val->id;
			$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
			
			if( count($cssoptions) > 0 )
			{
				$new_values = array();
	
				foreach($cssoptions as $k => $v)
					$new_values[$k] =  str_replace("#",'',$v);
	
				$saving = true;
				$use_saved = true;
				$filename = FORMPATH .'/core/css_create_main.php';
				$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
				$temp_css_file .= "\n";
				ob_start();
				include $filename;
				$temp_css_file .= ob_get_contents();
				ob_end_clean();
				$temp_css_file .= "\n ". $warn;
				$wp_upload_dir 	= wp_upload_dir();
				$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
				$css_file_new = $dest_dir.'maincss_'.$form_id.'.css';
	
				WP_Filesystem();
				global $wp_filesystem;
				$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
			}
			
		}
			
	}
	
	
	if(version_compare($newdbversion, '2.5.3', '<'))
	{
		// Udpate forms
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE status!='draft' order by id desc",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$form_id = $val->id;
			$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
			
			if( count($cssoptions) > 0 )
			{
				$new_values = array();
	
				foreach($cssoptions as $k => $v)
					$new_values[$k] =  str_replace("#",'',$v);
	
				$saving = true;
				$use_saved = true;
				$filename = FORMPATH .'/core/css_create_main.php';
				$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
				$temp_css_file .= "\n";
				ob_start();
				include $filename;
				$temp_css_file .= ob_get_contents();
				ob_end_clean();
				$temp_css_file .= "\n ". $warn;
				$wp_upload_dir 	= wp_upload_dir();
				$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
				$css_file_new = $dest_dir.'maincss_'.$form_id.'.css';
	
				WP_Filesystem();
				global $wp_filesystem;
				$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
			}
			
		}
			
	}
	
	if(version_compare($newdbversion, '2.5.4', '<'))
	{
		// Update styling settings
		require_once(MODELS_PATH.'/arstylemodel.php');
		
		$updatestylesettings = new arstylemodel();
	
		update_option('arfa_options', $updatestylesettings);
		set_transient('arfa_options', $updatestylesettings);
	
		$updatestylesettings->set_default_options();
		$updatestylesettings->store();
		
		$cssoptions = get_option("arfa_options");
		$new_values = array();
	
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
	
		$filename = FORMPATH .'/core/css_create_main.php';
	
		if(is_file($filename)) 
		{
			$uploads = wp_upload_dir();
			$target_path = $uploads['basedir'];
			$target_path .= "/arforms";
			$target_path .= "/css";
			$use_saved = true; 
			$form_id = ''; 
			$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
			$css .= "\n";
			ob_start();
			include $filename;
			$css .= ob_get_contents();
			ob_end_clean();
			$css .= "\n ". $warn;
			$css_file = $target_path .'/arforms.css';
	
			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->put_contents( $css_file , $css , 0777);
	
			update_option('arfa_css', $css);
			delete_transient('arfa_css');
			set_transient('arfa_css', $css);
		}
		
		// Udpate forms
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE status!='draft' order by id desc",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$form_id = $val->id;
			$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
			
			if( count($cssoptions) > 0 )
			{
				$new_values = array();
	
				foreach($cssoptions as $k => $v)
					$new_values[$k] =  str_replace("#",'',$v);
	
				$saving = true;
				$use_saved = true;
				$filename = FORMPATH .'/core/css_create_main.php';
				$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
				$temp_css_file .= "\n";
				ob_start();
				include $filename;
				$temp_css_file .= ob_get_contents();
				ob_end_clean();
				$temp_css_file .= "\n ". $warn;
				$wp_upload_dir 	= wp_upload_dir();
				$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
				$css_file_new = $dest_dir.'maincss_'.$form_id.'.css';
	
				WP_Filesystem();
				global $wp_filesystem;
				$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
			}
			
		}
			
	}
	
	if(version_compare($newdbversion, '2.6', '<'))
	{
		// Update main style options in database

		require_once(MODELS_PATH.'/arstylemodel.php');
		$updatestylesettings = new arstylemodel();
			
		update_option('arfa_options', $updatestylesettings);
		set_transient('arfa_options', $updatestylesettings);
			
		$updatestylesettings->set_default_options();
		$updatestylesettings->store();
		
		// Update arforms.css
		
		$cssoptions = get_option("arfa_options");
		$new_values = array();
		
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
			
		$filename = FORMPATH .'/core/css_create_main.php';
			
		if(is_file($filename)) 
		{
			$uploads = wp_upload_dir();
			$target_path = $uploads['basedir'];
			$target_path .= "/arforms";
			$target_path .= "/css";
			$use_saved = true; 
			$form_id = ''; 
			$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
			$css .= "\n";
			ob_start();
			include $filename;
			$css .= ob_get_contents();
			ob_end_clean();
			$css .= "\n ". $warn;
			$css_file = $target_path .'/arforms.css';
	
			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->put_contents( $css_file , $css , 0777);
	
			update_option('arfa_css', $css);
			delete_transient('arfa_css');
			set_transient('arfa_css', $css);
		}
		
		// Udpate forms css
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE status!='draft' order by id desc",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$form_id = $val->id;
			$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
			
			$cssoptions['bar_color_survey'] = '#007ee4';
			$cssoptions['bg_color_survey'] = '#dadde2';
			$cssoptions['text_color_survey'] = '#333333';
			
			$sernewarr = serialize($cssoptions);
			
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'form_css' => $sernewarr), array( 'id' => $val->id ) );
			
			if( count($cssoptions) > 0 )
			{
				$new_values = array();
	
				foreach($cssoptions as $k => $v)
					$new_values[$k] =  str_replace("#",'',$v);
	
				$saving = true;
				$use_saved = true;
				$filename = FORMPATH .'/core/css_create_main.php';
				$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
				$temp_css_file .= "\n";
				ob_start();
				include $filename;
				$temp_css_file .= ob_get_contents();
				ob_end_clean();
				$temp_css_file .= "\n ". $warn;
				$wp_upload_dir 	= wp_upload_dir();
				$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
				$css_file_new = $dest_dir.'maincss_'.$form_id.'.css';
	
				WP_Filesystem();
				global $wp_filesystem;
				$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
			}
			
			// for color picker and running total changes
			global $arffield;
			$form_fields = $arffield->getAll("fi.form_id = ".$form_id, " ORDER BY field_order");
			foreach($form_fields as $key => $val) 
			{
				$val->field_options['image_url'] = ARFURL.'/images/no-image.png';
				$val->field_options['image_left'] = '0px';
				$val->field_options['image_top'] = '0px';
				$val->field_options['image_height'] = '';
				$val->field_options['image_width'] = '';
				$val->field_options['image_center'] = 'no';
				$val->field_options['enable_total'] = '0';
				$val->field_options['colorpicker_type'] = 'advanced';
				$val->field_options['show_year_month_calendar'] = '0';
				
				$optionsnewval = serialize($val->field_options);
				$res = $wpdb->update( $wpdb->prefix."arf_fields", array( 'field_options' => $optionsnewval ), array( 'id' => $val->id ) );
			}
		}
			
	}
	
	if(version_compare($newdbversion, '2.6.2', '<'))
	{
		// Udpate forms' fields options
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE status!='draft' order by id desc",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_id = $val->id;
			
			// for confirm password placeholder option
			global $arffield;
			$form_fields = $arffield->getAll("fi.form_id = ".$form_id, " ORDER BY field_order");
			foreach($form_fields as $key => $val) 
			{
				$val->field_options['password_placeholder'] = '';
				
				$optionsnewval = serialize($val->field_options);
				$res = $wpdb->update( $wpdb->prefix."arf_fields", array( 'field_options' => $optionsnewval ), array( 'id' => $val->id ) );
			}
		}
	}
	
	if(version_compare($newdbversion, '2.7', '<'))
	{
		
		// Update main style options in database

		require_once(MODELS_PATH.'/arstylemodel.php');
		$updatestylesettings = new arstylemodel();
			
		update_option('arfa_options', $updatestylesettings);
		set_transient('arfa_options', $updatestylesettings);
			
		$updatestylesettings->set_default_options();
		$updatestylesettings->store();
		
		// Update arforms.css
		
		$cssoptions = get_option("arfa_options");
		$new_values = array();
		
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
			
		$filename = FORMPATH .'/core/css_create_main.php';
			
		if(is_file($filename)) 
		{
			$uploads = wp_upload_dir();
			$target_path = $uploads['basedir'];
			$target_path .= "/arforms";
			$target_path .= "/css";
			$use_saved = true; 
			$form_id = ''; 
			$css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
			$css .= "\n";
			ob_start();
			include $filename;
			$css .= ob_get_contents();
			ob_end_clean();
			$css .= "\n ". $warn;
			$css_file = $target_path .'/arforms.css';
	
			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->put_contents( $css_file , $css , 0777);
	
			update_option('arfa_css', $css);
			delete_transient('arfa_css');
			set_transient('arfa_css', $css);
		}
		
		
		// Udpate forms css
		global $wpdb, $db_record, $MdlDb;
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE status!='draft' order by id desc",OBJECT_K );
	
		foreach($res as $key => $val) 
		{
			$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css, options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id), ARRAY_A);
			$form_id = $val->id;
			$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
			
			$cssoptions['prefix_suffix_bg_color'] = '#e7e8ec';
			$cssoptions['prefix_suffix_icon_color'] = '#808080';
			$cssoptions['submit_hover_bg_img'] = '';
			
			$cssoptions['arfsectionpaddingsetting_1'] = '15';
			$cssoptions['arfsectionpaddingsetting_2'] = '10';
			$cssoptions['arfsectionpaddingsetting_3'] = '15';
			$cssoptions['arfsectionpaddingsetting_4'] = '10';
			
			$sernewarr = serialize($cssoptions);
			
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'form_css' => $sernewarr), array( 'id' => $val->id ) );
			
			
			// UPDATE ADMIN EMAIL NOTIFICATIONS
			$formoptions = maybe_unserialize($form_css_res[0]['options']);
			$formoptions['ar_admin_email_message'] = '[ARF_form_all_values]';
			
			$sernewoptarr = serialize($formoptions);
			
			$res = $wpdb->update( $wpdb->prefix."arf_forms", array( 'options' => $sernewoptarr ), array( 'id' => $val->id ) );
			
			if( count($cssoptions) > 0 )
			{
				$new_values = array();
	
				foreach($cssoptions as $k => $v)
					$new_values[$k] =  str_replace("#",'',$v);
	
				$saving = true;
				$use_saved = true;
				$filename = FORMPATH .'/core/css_create_main.php';
				$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
				$temp_css_file .= "\n";
				ob_start();
				include $filename;
				$temp_css_file .= ob_get_contents();
				ob_end_clean();
				$temp_css_file .= "\n ". $warn;
				$wp_upload_dir 	= wp_upload_dir();
				$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
				$css_file_new = $dest_dir.'maincss_'.$form_id.'.css';
	
				WP_Filesystem();
				global $wp_filesystem;
				$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
			}
			
		}
	}
		
	update_option('arf_db_version','2.7');
	
	global $newdbversion;
	$newdbversion = '2.7';
}
?>