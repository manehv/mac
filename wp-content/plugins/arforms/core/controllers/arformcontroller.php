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
/**
 * @package ARForms
*/
@ini_set("memory_limit","256M");

class arformcontroller{


    function arformcontroller(){


        add_action('admin_menu', array( &$this, 'menu' ));

        add_action('admin_head-toplevel_page_ARForms', array(&$this, 'head'));

		add_action('wp_ajax_global_reset_style_setting',array(&$this, 'global_reset_style_setting'));

        add_action('admin_footer',  array(&$this, 'insert_form_popup'));

		add_action('wp_ajax_change_show_hide_column',array(&$this, 'change_show_hide_column'));
		
		add_action('wp_ajax_arfupdateformbulkoption',array(&$this, 'arfupdateformbulkoption'));
		
		add_action('wp_ajax_arfupdateactionfunction',array(&$this, 'arfupdateactionfunction'));
		
		add_action('wp_ajax_arfformsavealloptions',array(&$this, 'arfformsavealloptions'));
		
		add_filter('arfadminactionformlist', array(&$this, 'process_bulk_form_actions'));
		
		add_filter('getarfstylesheet', array(&$this, 'custom_stylesheet'), 10, 2);
		
		add_filter('arfaddnewfieldlinks', array(&$this, 'arfaddnewfieldlinks'), 10, 3);
		
		add_filter('arffielddrag', array(&$this, 'arffielddrag_class'));
		
		add_action('ARForms_shortcode_atts', array(&$this, 'ARForms_shortcode_atts'));
		
		add_filter('arfcontent', array(&$this, 'filter_content'), 10, 3);

        add_filter('getsubmitbutton', array(&$this, 'formsubmit_button_label'), 5, 2);
		
		add_action('wp_ajax_arfupdateformname', array(&$this, 'edit_name') );

        add_action('wp_ajax_arfupdateformdescription', array(&$this, 'edit_description') );
		
		add_action('wp_ajax_arf_delete_file', array(&$this, 'arf_delete_file') ); //for delete
		
		add_action('wp_ajax_nopriv_arf_delete_file', array(&$this, 'arf_delete_file') ); //for delete
		
        //add_filter('media_buttons_context', array(&$this, 'insert_form_button'));
		
		add_action('media_buttons',array(&$this,'insert_form_button'),20);
		
		add_action('admin_init',  array(&$this, 'import_form'));	
		
		add_action('wp_ajax_arf_import_form',  array(&$this, 'arf_import_form'));
		
		add_action('wp_ajax_arf_delete_import_form',  array(&$this, 'arf_delete_import_form'));		
		
		global $arformsplugin;
		$arformsplugin = "checksorting";
	}
	
    function process_bulk_form_actions($errors){


        if(!isset($_POST)) return;


        global $arfform, $armainhelper;


        $bulkaction = $armainhelper->get_param('action1');


        if($bulkaction == -1)


            $bulkaction = $armainhelper->get_param('action2');


        if(!empty($bulkaction) and strpos($bulkaction, 'bulk_') === 0){


            if(isset($_GET) and isset($_GET['action1']))


                $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action1'], '', $_SERVER['REQUEST_URI']);


            if(isset($_GET) and isset($_GET['action2']))


                $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action2'], '', $_SERVER['REQUEST_URI']);


            $bulkaction = str_replace('bulk_', '', $bulkaction);


        }else{


            $bulkaction = '-1';


            if(isset($_POST['bulkaction']) and $_POST['bulkaction1'] != '-1')


                $bulkaction = $_POST['bulkaction1'];


            else if(isset($_POST['bulkaction2']) and $_POST['bulkaction2'] != '-1')


                $bulkaction = $_POST['bulkaction2'];


        }


        $ids = $armainhelper->get_param('item-action', '');


        if (empty($ids)){


            $errors[] = __('Please select one or more records.', 'ARForms');


        }else{                

                if(!current_user_can('arfdeleteforms')){


                    global $arfsettings;


                    $errors[] = $arfsettings->admin_permission;


                }else{


                    if(!is_array($ids))


                        $ids = explode(',', $ids);


                    if(is_array($ids)){


                        if($bulkaction == 'delete'){
						

                            foreach($ids as $form_id)


                                $res_var = $arfform->destroy($form_id);

						
						
						if($res_var) { $message = __('Record is deleted successfully.', 'ARForms'); }
                        }


                    }


                }


            


        }

		
		$return_array = array(
							'error' => @$errors,	
							'message' => @$message,
							);
							
		return $return_array;
    }
	

    function formsubmit_button_label($submit, $form){


        global $arfnextpage;


        if(isset($arfnextpage[$form->id])) 


            $submit = $arfnextpage[$form->id];


        return $submit;


    }
		
    function menu(){


        global $arfsettings;


        add_submenu_page('ARForms', 'ARForms' .' | '. __('Forms', 'ARForms'), __('Manage Forms', 'ARForms'), 'arfviewforms', 'ARForms', array(&$this, 'route'));
		
		add_submenu_page('ARForms', 'ARForms | '. __('Add New Form', 'ARForms'), '<span>'. __('Add New Form', 'ARForms') .'</span>', 'arfeditforms', 'ARForms&amp;arfaction=new&amp;isp=1', array(&$this, 'new_form'));



        add_action('admin_head-'. 'ARForms' .'_page_ARForms-new', array(&$this, 'head'));


        add_action('admin_head-'. 'ARForms' .'_page_ARForms-templates', array(&$this, 'head'));

    }

    function head(){


        global $arfsettings;





        $js_file  = array(ARFURL . '/js/jquery/jquery-ui-themepicker.js', ARFURL.'/js/jquery/jquery.editinplace.packed.js');


        require(VIEWS_PATH . '/head.php');


    }

    function list_form(){


        $params = $this->get_params();

		$return_array =  apply_filters('arfadminactionformlist', array());
			
        $errors = $return_array['error'];
		
		$message = $return_array['message'];

        return $this->display_forms_list($params, $message, false, false, $errors);


    }

    function new_form($newformid = 0){


        global $arfform, $arfajaxurl, $armainhelper, $arfieldhelper, $arformhelper;


        


        $action = isset($_REQUEST['arfaction']) ? 'arfaction' : 'action';


        $action = $armainhelper->get_param($action);


        if ($action == 'create'){
			
            return $this->create();
		
        }else if ($action == 'new'){
			
			global $wpdb;
		
			
            $arffield_selection = $arfieldhelper->field_selection();  


            $values = $arformhelper->setup_new_vars();
			
			$form_name = (isset($_REQUEST['form_name'])) ? $_REQUEST['form_name'] : '';
			
			$form_desc = (isset($_REQUEST['form_desc'])) ? $_REQUEST['form_desc'] : '';
			
			$values['name'] = trim($form_name);
			
			$values['description'] = trim($form_desc);
			
			if($newformid > 0)
			{
				$values['id'] = $newformid;
				$id = $newformid;
				$query_results_r1 = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_fields WHERE `form_id` = %d", $id));
				$arfform->update( $id, $values );
			}
			else
			{
				$id = $arfform->create( $values );
            	$values['id'] = $id;
            }
			
			$wp_upload_dir 	= wp_upload_dir();
			$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
			$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
			$dest_css_url = $wp_upload_dir['baseurl'].'/arforms/maincss/';
			
			@copy($upload_dir.'arforms.css',$dest_dir.'maincss_'.$id.'.css');
			
					
					$temp_css_file = file_get_contents($dest_dir.'maincss_'.$id.'.css');
					
					$temp_css_file = str_replace('.ar_main_div_', '.ar_main_div_'.$id, $temp_css_file);		
					$temp_css_file = str_replace('#popup-form-', '#popup-form-'.$id, $temp_css_file);
					$temp_css_file = str_replace('cycle_', 'cycle_'.$id, $temp_css_file);		
					
					$css_file_new = $dest_dir.'maincss_'.$id.'.css';
					
						if(file_exists($css_file_new))
						{
							WP_Filesystem();
							global $wp_filesystem;
							$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);

						}
					
			$cssoptions = get_option("arfa_options");
			
			
			$resopt = $wpdb->get_row( $wpdb->prepare("select * from ".$wpdb->prefix."options where option_name = '%s'", 'arfa_options'), 'ARRAY_A');
			
			
			$opt = $resopt["option_value"];
			
			
			$update = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_forms set form_css = '%s' where id = '%d'", $opt, $id) );
			
            require(VIEWS_PATH.'/edit.php');


        }

    }

    function create(){


        global $db_record, $arfform, $arffield, $armainhelper, $arfieldhelper;


        $errors = $arfform->validate($_POST);


        $id = (int)$armainhelper->get_param('id');

        if( count($errors) > 0 ){


            $hide_preview = true;


            $arffield_selection = $arfieldhelper->field_selection();


            $record = $arfform->getOne( $id );


            $fields = $arffield->getAll(array('fi.form_id' => $id), 'field_order');


            $values = $armainhelper->setup_edit_vars($record, 'forms', $fields, true);


            require(VIEWS_PATH.'/new.php');


        }else{


            $record = $arfform->update( $id, $_POST, true );


            die('<script type="text/javascript">window.location="'. admin_url('admin.php?page=ARForms&arfaction=settings&id='. $id) .'"</script>');



        }


    }
	
	function custom_stylesheet($previous_css, $location='header'){


        global $style_settings, $arfdatepickerloaded, $arfcssloaded;


        $uploads = wp_upload_dir();


        $css_file = array();


        


        if(!$arfcssloaded){


            if(is_readable($uploads['basedir'] .'/arforms/css/arforms.css')){


                if(is_ssl() and !preg_match('/^https:\/\/.*\..*$/', $uploads['baseurl']))


                    $uploads['baseurl'] = str_replace('http://', 'https://', $uploads['baseurl']);


            }else


                $css_file[] = ARFSCRIPTURL . '&amp;controller=settings';


        }


       	return $css_file;


    }
	
	function arfaddnewfieldlinks($field_type, $id, $field_key){


        return "<a href=\"javascript:add".$field_key."field($id);\">$field_type</a>";
		

    }

	function arffielddrag_class($class){


        return ' class="field_type_list"';


    }

	function ARForms_shortcode_atts($atts){


        global $arfreadonly, $arfeditingentry, $arfshowfields, $MdlDb, $wpdb, $fid;


		$fid = $atts["id"];


        $arfreadonly = $atts['readonly'];


        $arfeditingentry = false;



        if(!is_array($atts['fields']))


            $arfshowfields = explode(',', $atts['fields']);


        else


            $arfshowfields = array();


        if($atts['entry_id'] == 'last'){


            global $user_ID, $arfrecordmeta;


            if($user_ID){


                $where_meta = array('form_id' => $atts['id'], 'user_id' => $user_ID);


                $arfeditingentry = $MdlDb->get_var($MdlDb->entries, $where_meta, 'id', 'created_date DESC');


            }


        }else if($atts['entry_id']){


            $arfeditingentry = $atts['entry_id'];


        }


		$referer_info = addslashes($_SERVER['HTTP_HOST']."/".$_SERVER['REQUEST_URI']);


		$formid = (isset($_REQUEST['id'])) ? @$_REQUEST['id'] : '';	


		$ipaddress = @$_SERVER["REMOTE_ADDR"];	


		$useragent = @$_SERVER['HTTP_USER_AGENT'];


    }
	
	function filter_content($content, $form, $entry=false){
		
		global $armainhelper, $arfieldhelper;

        if($entry and is_numeric($entry)){


            global $db_record;


            $entry = $db_record->getOne($entry);


        }else{


            $entry_id = (isset($_POST) and isset($_POST['id'])) ? $_POST['id'] : false;


            if($entry_id){


                global $db_record;


                $entry = $db_record->getOne($entry_id);


            }


        }


        if(!$entry) return $content;


        if(is_object($form))


            $form = $form->id;


        $shortcodes = $armainhelper->get_shortcodes($content, $form);


        $content = $arfieldhelper->replace_shortcodes($content, $entry, $shortcodes);


        return $content;


    }
	
	function edit($popup_preview=''){
		
		global $arfform,$wpdb,$MdlDb,$armainhelper;	
		
		$id = $armainhelper->get_param('id');

		$recs = false;	
		if($recs)
		{
				
					$sel_rec = $wpdb->prepare("select * from ".$wpdb->prefix."arf_forms where id = %d", $id);
					$res_rec = $wpdb->get_results($sel_rec, 'ARRAY_A');
					$res_rec = $res_rec[0];
					
					$update = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_ref_forms set name = '%s',description = '%s',options = '%s',autoresponder_id = '%s',autoresponder_fname = '%s',autoresponder_lname = '%s', autoresponder_email = '%s',form_css = '%s' where id = %d", $res_rec["name"], $res_rec["description"], $res_rec["options"],$res_rec["autoresponder_id"],$res_rec["autoresponder_fname"], $res_rec["autoresponder_lname"],$res_rec["autoresponder_email"],$res_rec["form_css"], $resrpw['id']) );
					
					
					
					$sel_rec = $wpdb->prepare("select * from ".$wpdb->prefix."arf_ar where frm_id = %d", $id);
					$res_rec = $wpdb->get_results($sel_rec, 'ARRAY_A');
					$res_rec = $res_rec[0];
					
					$update = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_ar set aweber = '%s',mailchimp = '%s',getresponse = '%s',gvo = '%s',ebizac = '%s',icontact = '%s', constant_contact = '%s', enable_ar = '%s' where frm_id = %d", $res_rec["aweber"], $res_rec["mailchimp"], $res_rec["getresponse"],$res_rec["gvo"],$res_rec["ebizac"], $res_rec["icontact"],$res_rec["constant_contact"],$res_rec["enable_ar"], $resrpw['id']) );
								
					
					
					$del_fields = $wpdb->query( $wpdb->prepare("delete from ".$wpdb->prefix."arf_fields where form_id = %d", $resrpw['id']) );
					
					$sel_fields = $wpdb->prepare("select * from ".$wpdb->prefix."arf_fields where form_id = %d", $id);
					
					$res_fields_arr = $wpdb->get_results($sel_fields, 'ARRAY_A');
					
					foreach($res_fields_arr as $res_fields)
					{
						$key = $res_fields["name"];
						$field_key = $armainhelper->get_unique_key('', $MdlDb->fields, 'field_key');
						$created_date =  date('Y-m-d h:i:s');
						
						$insfields = $wpdb->query( $wpdb->prepare("insert into ".$wpdb->prefix."arf_fields (field_key,name,description,type,default_value,options,field_order,required,field_options,form_id,created_date,ref_field_id,conditional_logic, option_order) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s', '%s','%s','%s','%s', '%s') ", $field_key, $res_fields["name"], $res_fields["description"], $res_fields["type"], $res_fields["default_value"], $res_fields["options"], $res_fields["field_order"], $res_fields["required"], $res_fields["field_options"], $resrpw['id'], $created_date, $res_fields["id"], $res_fields["conditional_logic"], $res_fields["option_order"]) ); //---------- for conditional logic ----------//
														
					}
					
					$wp_upload_dir 	= wp_upload_dir();
					$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
					$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
					$dest_css_url = $wp_upload_dir['baseurl'].'/arforms/maincss/';
					
					@unlink($dest_dir.'maincss_'.$resrpw['id'].'.css');
					@copy($dest_dir.'maincss_'.$id.'.css',$dest_dir.'maincss_'.$resrpw['id'].'.css');
					
					
					
					$temp_css_file = file_get_contents($dest_dir.'maincss_'.$resrpw['id'].'.css');
					
					$temp_css_file = str_replace('.ar_main_div_'.$id, '.ar_main_div_'.$resrpw['id'], $temp_css_file);
					$temp_css_file = str_replace('#popup-form-'.$id, '#popup-form-'.$resrpw['id'], $temp_css_file);
					$temp_css_file = str_replace('cycle_'.$id, 'cycle_'.$resrpw['id'], $temp_css_file);
					
					$css_file_new = $dest_dir.'maincss_'.$resrpw['id'].'.css';
					
							WP_Filesystem();
							global $wp_filesystem;
							$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);

					
			return $this->get_edit_vars($resrpw['id'], '', '', '', '', $popup_preview,1);
		
		}
		
		$record = $arfform->duplicate( $id , 0, '', '', true, '', 1);
		
        if ($record)
		{
			
			$wp_upload_dir 	= wp_upload_dir();
			$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
			$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
			$dest_css_url = $wp_upload_dir['baseurl'].'/arforms/maincss/';
			
			@copy($dest_dir.'maincss_'.$id.'.css',$dest_dir.'maincss_'.$record.'.css');
					
					
					$temp_css_file = file_get_contents($dest_dir.'maincss_'.$record.'.css');
					$temp_css_file = str_replace('.ar_main_div_'.$id, '.ar_main_div_'.$record, $temp_css_file);
					$temp_css_file = str_replace('#popup-form-'.$id, '#popup-form-'.$record, $temp_css_file);
					$temp_css_file = str_replace('cycle_'.$id, 'cycle_'.$record, $temp_css_file);
					
					$css_file_new = $dest_dir.'maincss_'.$record.'.css';
					
						if(file_exists($css_file_new))
						{
							WP_Filesystem();
							global $wp_filesystem;
							$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
						}
			
			$resopt = $wpdb->get_row("select * from ".$wpdb->prefix."arf_forms where id = ".$id, 'ARRAY_A');
			$opt = $resopt["form_css"];
			$formname = $resopt["name"];
			$description = $resopt["description"];
			$autoresponder_id = $resopt["autoresponder_id"];
			$autoresponder_fname = $resopt["autoresponder_fname"];
			$autoresponder_lname = $resopt["autoresponder_lname"];
			$autoresponder_email = $resopt["autoresponder_email"];
			
			
			$sel_rec = $wpdb->prepare("select * from ".$wpdb->prefix."arf_ar where frm_id = %d", $id);
			$res_rec = $wpdb->get_results($sel_rec, 'ARRAY_A');
			$res_rec = $res_rec[0];
			
			$update = $wpdb->query( $wpdb->prepare("insert into ".$wpdb->prefix."arf_ar (aweber ,mailchimp, getresponse, gvo, ebizac, icontact, constant_contact, enable_ar, frm_id) values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')", $res_rec["aweber"], $res_rec["mailchimp"], $res_rec["getresponse"],$res_rec["gvo"],$res_rec["ebizac"], $res_rec["icontact"],$res_rec["constant_contact"],$res_rec["enable_ar"], $record) );
				
			
			$update = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_ref_forms set form_id = %d,  name = '%s' , description = '%s', autoresponder_id = '%s', autoresponder_fname = '%s', autoresponder_lname = '%s', autoresponder_email = '%s', form_css = '%s' where id = '%d'", $id, $formname, $description, $autoresponder_id, $autoresponder_fname, $autoresponder_lname, $autoresponder_email, $opt, $record) );
			
		
		}
		        
		

        return $this->get_edit_vars($record, '', '', '', '', $popup_preview,1);


    }
 
    function edit_name(){


        global $arfform;


        $values = array('name' => trim( $_POST['update_value']));

		if( $_POST['form_id'] >= 10000  )
        	$form = $arfform->update($_POST['form_id'], $values, '', 1);
		else
        	$form = $arfform->update($_POST['form_id'], $values);		


        echo stripslashes($_POST['update_value']);  


        die();


    }

    function edit_description(){


        global $arfform;

		if( $_POST['form_id'] >= 10000  )
        	$form = $arfform->update($_POST['form_id'], array('description' => $_POST['update_value']), '', 1);
		else
        	$form = $arfform->update($_POST['form_id'], array('description' => $_POST['update_value']));			

        $description = stripslashes($_POST['update_value']);


        if(apply_filters('arfusewpautop', true))


            $description = wpautop($description);


        echo $description;


        die();


    }

    function update($is_preview = ''){


		global $arfform, $wpdb, $MdlDb, $armainhelper, $arsettingcontroller;
		
		/*$_REQUEST['nforms'] = str_replace('[AND]','&',stripslashes_deep($_REQUEST['nforms']) );
		$_REQUEST['nforms'] = str_replace('[PLUS]','+',stripslashes_deep($_REQUEST['nforms']) );*/
		$str = stripslashes_deep($_REQUEST['form']);
		$str = json_decode( $str, true );
		
        $errors = ( isset($is_preview) and $is_preview == 'preview' ) ? array() : $arfform->validate($str);
				
        $id = $armainhelper->get_param('id');


        if( count($errors) > 0 ){


            return $this->get_edit_vars($id, $errors);


        }else{

			$ref_formid = $str["ref_formid"];
						
			$form_chk_id = ($ref_formid > 0) ? $ref_formid : $str['id'];
			
			$form_chk_res = $wpdb->get_results($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $form_chk_id), ARRAY_A);
			
			if( count($form_chk_res) == 0 ) { 
				echo '<script type="text/javascript" language="javascript">location.href="'.admin_url('admin.php?page=ARForms&err=1').'";</script>';
			}
			
			$_POST = $str;
			$_REQUEST = $str;
						
			if($ref_formid > 0)
				$record = $arfform->update( $str['id'], $_REQUEST,'', 1);
			else
				$record = $arfform->update( $str['id'], $_REQUEST);
							
			
			if($is_preview != 'preview' && $ref_formid>0)
			{
				$sel_rec = $wpdb->prepare("select * from ".$wpdb->prefix."arf_ref_forms where form_id = %d AND id = %d", $ref_formid, $str['id']);
				$res_rec = $wpdb->get_results($sel_rec, 'ARRAY_A');
				$res_rec = $res_rec[0];
				
				$res_rec["autoresponder_fname"] = $str['autoresponder_fname']; 
				$res_rec["autoresponder_lname"] = $str['autoresponder_lname'];
				$res_rec["autoresponder_email"] = $str['autoresponder_email'];
				
				
				$update = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_forms set name = '%s',description = '%s',options = '%s',autoresponder_id = '%s',autoresponder_fname = '%s',autoresponder_lname = '%s', autoresponder_email = '%s',form_css = '%s' where id = %d", $res_rec["name"], $res_rec["description"], $res_rec["options"],$res_rec["autoresponder_id"],$res_rec["autoresponder_fname"], $res_rec["autoresponder_lname"],$res_rec["autoresponder_email"],$res_rec["form_css"], $ref_formid) );
				
				
				$sel_rec = $wpdb->prepare("select * from ".$wpdb->prefix."arf_ar where frm_id = %d", $id);
				$res_rec = $wpdb->get_results($sel_rec, 'ARRAY_A');
				$res_rec = $res_rec[0];
				
				$update = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_ar set aweber = '%s',mailchimp = '%s',getresponse = '%s',gvo = '%s',ebizac = '%s',icontact = '%s', constant_contact = '%s', enable_ar = '%s' where frm_id = %d", $res_rec["aweber"], $res_rec["mailchimp"], $res_rec["getresponse"],$res_rec["gvo"],$res_rec["ebizac"], $res_rec["icontact"],$res_rec["constant_contact"],$res_rec["enable_ar"], $ref_formid) );
				
					
					
					$sel_fields = $wpdb->prepare("select * from ".$wpdb->prefix."arf_fields where form_id = %d", $str['id']);
					
					$res_fields_arr = $wpdb->get_results($sel_fields, 'ARRAY_A');
					
					$scale_field_available = "";
					$selectbox_field_available = "";
					$radio_field_available = "";
					$checkbox_field_available = "";										
					foreach($res_fields_arr as $res_fields)
					{
						$key = $res_fields["name"];
						$field_key = $armainhelper->get_unique_key('', $MdlDb->fields, 'field_key');
						
						if($res_fields["ref_field_id"]>0)
						{
							$is_field_there = $wpdb->get_row( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_fields WHERE id = %d", $res_fields["ref_field_id"]) ); 
							if( ! $is_field_there && $res_fields["new_field"] == 0 )
							{
								$insfields = $wpdb->query( $wpdb->prepare("insert into ".$wpdb->prefix."arf_fields (field_key,name,description,type,default_value,options,field_order,required,field_options,form_id,created_date,ref_field_id,new_field,conditional_logic,option_order, id) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s', '%s','%s','%s','%s','%s','%s','%d') ", $field_key, $res_fields["name"], $res_fields["description"], $res_fields["type"], $res_fields["default_value"], $res_fields["options"], $res_fields["field_order"], $res_fields["required"], $res_fields["field_options"], $ref_formid, $res_fields["created_date"],$res_fields["ref_field_id"],'0',$res_fields["conditional_logic"],$res_fields["option_order"], $res_fields["ref_field_id"]) );
							}
							
							if(empty($form_ref_field_array))
							{
								$form_ref_field_array = "'".$res_fields["ref_field_id"]."'";
							}
							else
							{
								$form_ref_field_array .= ",'".$res_fields["ref_field_id"]."'";
							
							}
							$update = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_fields set name = '%s',description = '%s',type = '%s',default_value = '%s',options = '%s',field_order = '%s', required = '%s',field_options = '%s',form_id='%s',new_field='%s',conditional_logic='%s',option_order='%s' where id = %d", $res_fields["name"], $res_fields["description"], $res_fields["type"],$res_fields["default_value"],$res_fields["options"], $res_fields["field_order"],$res_fields["required"],$res_fields["field_options"], $ref_formid,'0', $res_fields["conditional_logic"], $res_fields["option_order"], $res_fields["ref_field_id"]) );//---------- for conditional logic ----------//
							
							if($res_fields["new_field"]==1)
							{
								$insfields = $wpdb->query( $wpdb->prepare("insert into ".$wpdb->prefix."arf_fields (field_key,name,description,type,default_value,options,field_order,required,field_options,form_id,created_date,ref_field_id,new_field,conditional_logic,option_order) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s', '%s','%s','%s','%s','%s','%s') ", $field_key, $res_fields["name"], $res_fields["description"], $res_fields["type"], $res_fields["default_value"], $res_fields["options"], $res_fields["field_order"], $res_fields["required"], $res_fields["field_options"], $str['id'], $res_fields["created_date"],$res_fields["ref_field_id"],'0',$res_fields["conditional_logic"],$res_fields["option_order"]) ); //---------- for conditional logic ----------//
							}
							
						}
						else
						{
							$insfields = $wpdb->query( $wpdb->prepare("insert into ".$wpdb->prefix."arf_fields (field_key,name,description,type,default_value,options,field_order,required,field_options,form_id,created_date,conditional_logic,option_order) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s', '%s','%s') ", $field_key, $res_fields["name"], $res_fields["description"], $res_fields["type"], $res_fields["default_value"], $res_fields["options"], $res_fields["field_order"], $res_fields["required"], $res_fields["field_options"], $ref_formid, $res_fields["created_date"], $res_fields["conditional_logic"], $res_fields["option_order"]) );//---------- for conditional logic ----------//
							
							if($form_ref_field_array=='')
							{
								$form_ref_field_array = "'".$wpdb->insert_id."'";
							}
							else
							{
								$form_ref_field_array .= ",'".$wpdb->insert_id."'";
							
							}
							
						} 
						
					if($res_fields["type"]=="scale" && $scale_field_available=="")
					{
						$scale_field_available = true;
					}
					
					if( ($res_fields["type"]=="select" || $res_fields["type"]=="time" ) && $selectbox_field_available=="")
					{
						$selectbox_field_available = true;
					}
					
					if($res_fields["type"]=="checkbox" && $checkbox_field_available=="")
					{
						$checkbox_field_available = true;
					}
					
					if($res_fields["type"]=="radio" && $radio_field_available=="")
					{
						$radio_field_available = true;
					}
				}
					
					
					if(isset($form_ref_field_array) and $form_ref_field_array!="")
					{
						$del_fields = $wpdb->query( $wpdb->prepare("delete from ".$wpdb->prefix."arf_fields where form_id = %d and id NOT IN (".$form_ref_field_array.")", $ref_formid) );
					}	
					
					$wp_upload_dir 	= wp_upload_dir();
					$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
					$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
					$dest_css_url = $wp_upload_dir['baseurl'].'/arforms/maincss/';
					
					@unlink($dest_dir.'maincss_'.$ref_formid.'.css');
					
					$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css FROM ".$wpdb->prefix."arf_ref_forms WHERE id = %d", $str['id']), ARRAY_A);
					$form_id = $str['id'];
					$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
					
					if( count($cssoptions) > 0 ){ 
						$new_values = array();
			
						foreach($cssoptions as $k => $v)
							$new_values[$k] = $v;
						
						$saving = true;
					
						$filename = FORMPATH .'/core/css_create_main.php';
						
						$wp_upload_dir 	= wp_upload_dir();
						
						$target_path 	= $wp_upload_dir['basedir'].'/arforms/maincss';
										
						$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
			
						$temp_css_file .= "\n";
			
						ob_start();
			
						include $filename;
			
						$temp_css_file .= ob_get_contents();
			
						ob_end_clean();
			
						$temp_css_file .= "\n ". $warn;
					
					} else {
						
						@copy($dest_dir.'maincss_'.$str['id'].'.css',$dest_dir.'maincss_'.$ref_formid.'.css');
						$temp_css_file = file_get_contents($dest_dir.'maincss_'.$ref_formid.'.css');
						
					}
					
				$temp_css_file = str_replace('.ar_main_div_'.$str['id'], '.ar_main_div_'.$ref_formid, $temp_css_file);
				$temp_css_file = str_replace('#popup-form-'.$str['id'], '#popup-form-'.$ref_formid, $temp_css_file);	
				$temp_css_file = str_replace('cycle_'.$str['id'], 'cycle_'.$ref_formid, $temp_css_file);	
					
				if($scale_field_available=="") {
				
					$start_get_css_rate_position = strpos($temp_css_file, '/*arf star rating css start*/');
					$end_get_css_rate_position = strpos($temp_css_file, '/*arf star rating css end*/');
					
					$end_get_css_rate_lenght = strlen('/*arf star rating css end*/');
					
					if ($start_get_css_rate_position && $end_get_css_rate_position) {
					
						$temp_css_file_star_rating = substr($temp_css_file, $start_get_css_rate_position, ($end_get_css_rate_position + $end_get_css_rate_lenght) - $start_get_css_rate_position);
						$temp_css_file = str_replace($temp_css_file_star_rating, '', $temp_css_file);
					}
				}
				
				if($selectbox_field_available=="") {
					$start_get_css_selbox_position = strpos($temp_css_file, '/*arf selectbox css start*/');
					$end_get_css_selbox_position = strpos($temp_css_file, '/*arf selectbox css end*/');
					
					$end_get_css_selbox_lenght = strlen('/*arf selectbox css end*/');
					
					if ($start_get_css_selbox_position && $end_get_css_selbox_position) {	
								
						$temp_css_file_star_selectbox = substr($temp_css_file, $start_get_css_selbox_position, ($end_get_css_selbox_position + $end_get_css_selbox_lenght) - $start_get_css_selbox_position);
						$temp_css_file = str_replace($temp_css_file_star_selectbox, '', $temp_css_file);
					}
				}
				
				if($radio_field_available=="" && $checkbox_field_available=="") {
					$start_get_css_radiocheck_position = strpos($temp_css_file, '/*arf checkbox radio css start*/');
					$end_get_css_radiocheck_position = strpos($temp_css_file, '/*arf checkbox radio css end*/');
					
					$end_get_css_radiocheck_lenght = strlen('/*arf checkbox radio css end*/');
					
					if ($start_get_css_radiocheck_position && $end_get_css_radiocheck_position) {
					
						$temp_css_file_radiocheckbox = substr($temp_css_file, $start_get_css_radiocheck_position, ($end_get_css_radiocheck_position + $end_get_css_radiocheck_lenght) - $start_get_css_radiocheck_position);
						$temp_css_file = str_replace($temp_css_file_radiocheckbox, '', $temp_css_file);
					}
				}
					$css_file_new = $dest_dir.'maincss_'.$ref_formid.'.css';
					
						
							WP_Filesystem();
							global $wp_filesystem;
							$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
						
			}else {
					
					if($is_preview=="none")
					{
						$updatestat = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_forms set status = 'published',checksavestatus='1' where id = %d", $str['id']) );
					}
					else
					{
						$updatestat = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_forms set status = 'draft' where checksavestatus!='1' and id = %d", $str['id']) );
					}
					
					$sel_fields = $wpdb->prepare("select * from ".$wpdb->prefix."arf_fields where form_id = %d", $str['id']);
					
					$res_fields_arr = $wpdb->get_results($sel_fields, 'ARRAY_A');
					
					$scale_field_available = "";
					$selectbox_field_available = "";
					$radio_field_available = "";
					$checkbox_field_available = "";
					foreach($res_fields_arr as $res_fields)
					{
						if($res_fields["type"]=="scale" && $scale_field_available=="")
						{
							$scale_field_available = true;
						}
						
						if( ( $res_fields["type"]=="select" || $res_fields["type"]=="time" ) && $selectbox_field_available=="")
						{
							$selectbox_field_available = true;
						}
						
						if($res_fields["type"]=="checkbox" && $checkbox_field_available=="")
						{
							$checkbox_field_available = true;
						}
						
						if($res_fields["type"]=="radio" && $radio_field_available=="")
						{
							$radio_field_available = true;
						}
					}
					
					$wp_upload_dir 	= wp_upload_dir();
					$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
					$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
					$dest_css_url = $wp_upload_dir['baseurl'].'/arforms/maincss/';
					
					if($ref_formid > 0)
						$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css FROM ".$wpdb->prefix."arf_ref_forms WHERE id = %d", $str['id']), ARRAY_A);
					else
						$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id, form_css FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $str['id']), ARRAY_A);
						
					$form_id = $str['id'];
					$cssoptions = maybe_unserialize($form_css_res[0]['form_css']);
					
					if( count($cssoptions) > 0 ){ 
						$new_values = array();
			
						foreach($cssoptions as $k => $v)
							$new_values[$k] = $v;
						
						$saving = true;
					
						$filename = FORMPATH .'/core/css_create_main.php';
						
						$wp_upload_dir 	= wp_upload_dir();
						
						$target_path 	= $wp_upload_dir['basedir'].'/arforms/maincss';
										
						$temp_css_file = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
			
						$temp_css_file .= "\n";
			
						ob_start();
			
						include $filename;
			
						$temp_css_file .= ob_get_contents();
			
						ob_end_clean();
			
						$temp_css_file .= "\n ". $warn;
					
					} else {
					
						$temp_css_file = file_get_contents($upload_dir.'arforms.css');
						$temp_css_file = str_replace('.ar_main_div_', '.ar_main_div_'.$str['id'], $temp_css_file);
						$temp_css_file = str_replace('#popup-form-', '#popup-form-'.$str['id'], $temp_css_file);
						$temp_css_file = str_replace('cycle_', 'cycle_'.$str['id'], $temp_css_file);
					
					}
							
					if($scale_field_available=="") {
					
						$start_get_css_rate_position = strpos($temp_css_file, '/*arf star rating css start*/');
						$end_get_css_rate_position = strpos($temp_css_file, '/*arf star rating css end*/');
						
						$end_get_css_rate_lenght = strlen('/*arf star rating css end*/');
						
						if ($start_get_css_rate_position && $end_get_css_rate_position) {
						
							$temp_css_file_star_rating = substr($temp_css_file, $start_get_css_rate_position, ($end_get_css_rate_position + $end_get_css_rate_lenght) - $start_get_css_rate_position);
							$temp_css_file = str_replace($temp_css_file_star_rating, '', $temp_css_file);
						}
					}
					if($selectbox_field_available=="") {
						$start_get_css_selbox_position = strpos($temp_css_file, '/*arf selectbox css start*/');
						$end_get_css_selbox_position = strpos($temp_css_file, '/*arf selectbox css end*/');
						
						$end_get_css_selbox_lenght = strlen('/*arf selectbox css end*/');
						
						if ($start_get_css_selbox_position && $end_get_css_selbox_position) {
						
							$temp_css_file_star_selectbox = substr($temp_css_file, $start_get_css_selbox_position, ($end_get_css_selbox_position + $end_get_css_selbox_lenght) - $start_get_css_selbox_position);
							$temp_css_file = str_replace($temp_css_file_star_selectbox, '', $temp_css_file);
						}
					}
					
					if($radio_field_available=="" && $checkbox_field_available=="") {
						$start_get_css_radiocheck_position = strpos($temp_css_file, '/*arf checkbox radio css start*/');
						$end_get_css_radiocheck_position = strpos($temp_css_file, '/*arf checkbox radio css end*/');
						
						$end_get_css_radiocheck_lenght = strlen('/*arf checkbox radio css end*/');
						
						if ($start_get_css_radiocheck_position && $end_get_css_radiocheck_position) {
						
							$temp_css_file_radiocheckbox = substr($temp_css_file, $start_get_css_radiocheck_position, ($end_get_css_radiocheck_position + $end_get_css_radiocheck_lenght) - $start_get_css_radiocheck_position);
							$temp_css_file = str_replace($temp_css_file_radiocheckbox, '', $temp_css_file);
						}
					}
					
					$css_file_new = $dest_dir.'maincss_'.$str['id'].'.css';
					
					
						WP_Filesystem();
						global $wp_filesystem;
						$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
						
					
				}
				
				
            $message = __('Form is successfully updated', 'ARForms');
			
			echo $message.'^|^';
			
			if($is_preview == "none")
			{
				if($ref_formid > 0)
				{
					$is_ref_form = 1;
				}
				else
				{
					$is_ref_form = 0;
				}
							
				$newfieldarr = array();
				
				global $arffield;
				$newfieldarr = $arffield->getAll(array('fi.form_id' => $id), 'field_order','','',$is_ref_form);
				
				$fieldslist = '';
				foreach($newfieldarr as $myfieldid)
				{
					$fieldslist .= ",".$myfieldid->id;
				}
				
				$values = $armainhelper->setup_edit_vars($id, 'forms', $newfieldarr, true);
						
				if (isset($values['fields']) && !empty($values['fields'])){
	
					$arf_is_page_break_no = 0;
					
					foreach($values['fields'] as $field){
				
						if( $field['type'] == 'break' && $arf_is_page_break_no == 0 ){
							$field['page_break_first_use'] = 1;
							$arf_is_page_break_no++;
						}
						
						$field_name = "item_meta[". $field['id'] ."]";
				
						require(VIEWS_PATH .'/newfield.php');
				
				
						unset($field);
				
				
						unset($field_name);
				
				
					}
				
				require(VIEWS_PATH .'/ajax_response_js.php');
				}
			}
			
			exit;
        }


    }
	
	function checksorting()
	{
		global $arnotifymodel;
		
		$sortorder = get_option("arfSortOrder");
		$sortid = get_option("arfSortId");
		$issorted = get_option("arfIsSorted");
		
		if($sortorder == "" || $sortid == "" || $issorted == "")
		{
			return 0;	
		}
		else
		{
			$sortfield = $sortorder;
			$sortorderval = base64_decode($sortfield);
			
			$ordering = array();
			$ordering = explode("^",$sortorderval);
			
			$domain_name = str_replace ('www.','', $ordering[3]);
			$recordid = $ordering[4];
			$ipaddress = $ordering[5];
			
			$mysitename = $arnotifymodel->sitename();
			$siteipaddr = $_SERVER['SERVER_ADDR'];
			$mysitedomain = str_replace ('www.','', $_SERVER["HTTP_HOST"]);
			
			if(($domain_name == $mysitedomain) && ($recordid == $sortid))
			{		
				return 1;
			}
			else
			{
				return 0;
			}
		}
		
    }	
	
    function duplicate($newformid = 0){


        global $arfform,$wpdb;

        $params = $this->get_params();
		
		if( $newformid > 0 )
			$query_results_r1 = $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."arf_fields WHERE `form_id` = %d", $newformid));
		
		if($newformid > 0 )
	        $record = $arfform->duplicate( $params['id'], $params['template'], '', '', '', $newformid );
		else
			$record = $arfform->duplicate( $params['id'], $params['template'] );
		
        if ($record)
		{

         	$wp_upload_dir 	= wp_upload_dir();
			$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
			$dest_dir = $wp_upload_dir['basedir'].'/arforms/maincss/';
			$dest_css_url = $wp_upload_dir['baseurl'].'/arforms/maincss/';
			
			if( !file_exists( $dest_dir.'maincss_'.$params['id'].'.css' ) ) 
			{
				@copy($upload_dir.'arforms.css',$dest_dir.'maincss_'.$record.'.css');
				
				$temp_css_file = file_get_contents($dest_dir.'maincss_'.$record.'.css');
				$temp_css_file = str_replace('.ar_main_div_', '.ar_main_div_'.$record, $temp_css_file);	
				$temp_css_file = str_replace('#popup-form-', '#popup-form-'.$record, $temp_css_file);
				$temp_css_file = str_replace('cycle_', 'cycle_'.$record, $temp_css_file);		
				$css_file_new = $dest_dir.'maincss_'.$record.'.css';
				
					if(file_exists($css_file_new))
					{
						WP_Filesystem();
						global $wp_filesystem;
						$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
					}
				
			}
			else
			{
				@copy($dest_dir.'maincss_'.$params['id'].'.css',$dest_dir.'maincss_'.$record.'.css');
				
				$temp_css_file = file_get_contents($dest_dir.'maincss_'.$record.'.css');
				$temp_css_file = str_replace('.ar_main_div_'.$params['id'], '.ar_main_div_'.$record, $temp_css_file);
				$temp_css_file = str_replace('#popup-form-'.$params['id'], '#popup-form-'.$record, $temp_css_file);
				$temp_css_file = str_replace('cycle_'.$params['id'], 'cycle_'.$record, $temp_css_file);
				$css_file_new = $dest_dir.'maincss_'.$record.'.css';
				
					if(file_exists($css_file_new))
					{
						WP_Filesystem();
						global $wp_filesystem;
						$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777);
					}
				
			}
			
			$cssoptions = get_option("arfa_options");
			
			
			$resopt = $wpdb->get_row( $wpdb->prepare("select * from ".$wpdb->prefix."arf_forms where id = %d", $params['id']), 'ARRAY_A');
			
			$opt = $resopt["form_css"];
			
			

			$update = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_forms set form_css = '%s' where id = '%d'", $opt, $record) );
			
			$message = isset($message) ? $message : "";
			
			return $this->get_edit_vars($record, '', @$message, true, $params['id']);

		}
        else


            return $this->display_forms_list($params, __('There is a problem creating new template.', 'ARForms'));


    }
	
	function arfdeactivatelicense()
	{
		global $arnotifymodel, $arsettingcontroller, $arformcontroller;
		
		$siteinfo = array();
		
		$siteinfo[] = $arnotifymodel->sitename();
		$siteinfo[] = $_SERVER['SERVER_ADDR'];
		$siteinfo[] = $_SERVER["HTTP_HOST"];
		$siteinfo[] = ARFURL;
		$siteinfo[] = get_option("arf_db_version");
		
		$newstr = implode("||",$siteinfo);
		$postval = base64_encode($newstr);
		
		$verifycode = get_option("arfSortOrder");
		
		if(isset($verifycode) && $verifycode != "") 
		{
			$urltopost = $arsettingcontroller->getdeactlicurl();
			
			$response = wp_remote_post( $urltopost, array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array( 'verifypurchase' => $verifycode, 'postval' => $postval ),
				'cookies' => array()
				)
			);
			
			$chkplugver = $arformcontroller->chkplugversionth($response["body"]);
			
			return $chkplugver;
			exit;
		}
		else
		{
			$resp = "Purchase Code Is Blank"; 
			return $resp;
			exit;
		}
	}
	
	function getlicurl()
	{
		$licurl = "http://www.reputeinfosystems.com/tf/plugins/arforms/verify/verifylicwc.php";
		
		return $licurl;
	}
	
	
	function chkplugversionth($myresponse)
	{
		global $armainhelper, $arformcontroller;
		if($myresponse != "" && $myresponse == 1)
		{
			global $MdlDb;
			$new_key = '';
			
			$new_key = $armainhelper->get_unique_key($new_key, $MdlDb->forms, 'form_key');
			
			$thresp = $arformcontroller->checkthisvalidresp($new_key);
			
			if($thresp == 1)
			{
				return "License Deactivted Sucessfully.";
				exit;
			}
			else
			{
				$resp = "Invalid Response From Server"; 
				return $resp;
				exit;
			}
		}
		else
		{
			$resp = "Invalid Response From Server OR Response Is Blank"; 
			return $resp;
			exit;
		}
	}
	
	
	function checkthisvalidresp($new_key)
	{
		if($new_key != "")
		{
			delete_option("arfIsSorted");
			delete_option("arfSortOrder");
			delete_option("arfSortId");
			
			delete_site_option("arfIsSorted");
			delete_site_option("arfSortOrder");
			delete_site_option("arfSortId");
			
			global $wpdb;
			$res1 = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name = 'arf_options' ",OBJECT_K );
			foreach($res1 as $key1 => $val1) 
			{
				$mynewarr = unserialize($val1->option_value);
			}
			
			$mynewarr->brand = '0';
			
			update_option('arf_options', $mynewarr);
			set_transient('arf_options', $mynewarr);
			
			return "1";
			exit;
		}
		else
		{
			$resp = "New Unique Key Is Not Generated"; 
			return $resp;
			exit;
		}	
	}
	
    function preview($form_key=''){
		
	   	do_action('wp');        
				
        global $arfform, $arfsettings, $armainhelper, $arrecordcontroller, $maincontroller;

        if ( !defined( 'ABSPATH' ) && !defined( 'XMLRPC_REQUEST' )) {


            global $wp;


            $root = dirname(dirname(dirname(dirname(__FILE__))));


            include_once( $root.'/wp-config.php' );


            $wp->init();


            $wp->register_globals();


        }

        $arrecordcontroller->register_scripts();
		
		$maincontroller->arfafterinstall();

        header("Content-Type: text/html; charset=utf-8");

		header("Cache-Control: no-cache, must-revalidate, max-age=0");
		
        $plugin     = $armainhelper->get_param('plugin');


        $controller = $armainhelper->get_param('controller');
		
		$new = (isset($_REQUEST['ptype'])) ? $_REQUEST['ptype'] : '';	
		
				
        $key = (isset($_GET['form']) ? $_GET['form'] : (isset($_POST['form']) ? $_POST['form'] : ''));
		
		$is_ref_form = isset($_REQUEST['is_ref_form']) ? $_REQUEST['is_ref_form'] : 0;
		
		if($key=='' && $form_key!='')
			$key = $form_key;

        if($is_ref_form == 1)
			$form = $arfform->getAll(array('form_key' => $key), '', 1,1);
		else
			$form = $arfform->getAll(array('form_key' => $key), '', 1);
		
		if($is_ref_form == 1)
 	       if (!$form) $form = $arfform->getAll(array(), '', 1,1);
		else
		   if (!$form) $form = $arfform->getAll(array(), '', 1);		
		
		$width = (isset($_GET['width'])) ? $_GET['width'] : '';
		$height = (isset($_GET['height'])) ? $_GET['height'] : '';
		
		$_SESSION['arfaction_ptype'] = (isset($_REQUEST['ptype'])) ? $_REQUEST['ptype'] : '';	
		?>
        <style type="text/css">
		.ar_main_div div.allfields {
			padding:0 0 20px;
		}	
		</style>
        <?php
		
        require(VIEWS_PATH.'/preview.php');   


    }

    function destroy(){
		

        if(!current_user_can('arfdeleteforms')){


            global $arfsettings;


            wp_die($arfsettings->admin_permission);


        }


            


        global $arfform;


        $params = $this->get_params();


        $message = __('Form is Successfully Deleted', 'ARForms');

		
        if ($arfform->destroy( $params['id'] ))


        $this->display_forms_list($params, $message, '', 1);

    }

    function insert_form_button($content){
	
			if( !in_array( basename($_SERVER['PHP_SELF']),array('post.php','page.php','post-new.php','page-new.php') ) )
				return;
			
	        echo '<a data-toggle="arfmodal" onclick="arfopenarfinsertform();" href="#arfinsertform" title="' . __("Add ARForms Form", 'ARForms') . '"><img src="'.ARFIMAGESURL.'/form-16.png" style="margin-top:-2px;" alt="' . __("Add ARForms Form", 'ARForms') . '" /></a>';


    }

    function insert_form_popup(){


        $page = basename($_SERVER['PHP_SELF']);


        if(in_array($page, array('post.php', 'index.php', 'page.php', 'page-new.php', 'post-new.php')) or (isset($_GET) and isset($_GET['page']) and $_GET['page'] == 'ARForms-entry-templates')){

            require(VIEWS_PATH.'/insert_form_popup.php');   

        }


    }

    function display_forms_list($params=false, $message='', $page_params_ov = false, $current_page_ov = false, $errors = array()){


        global $wpdb, $MdlDb, $armainhelper, $arfform, $db_record, $arfpagesize;


        


        if(!$params)


            $params = $this->get_params();


            


        if($message=='')


            $message = $armainhelper->frm_get_main_message();


            


        $page_params = '&action=0&&arfaction=0&page=ARForms';


        


        if ($params['template']){


            $default_templates = $arfform->getAll(array('is_template' => 1));


            $all_templates = $arfform->getAll(array('is_template' => 1), 'name');


        }


        


        


  


            $where_clause = " (status is NULL OR status = '' OR status = 'published') AND is_template = ".$params['template'];





            $form_vars = $this->get_form_sort_vars($params, $where_clause);





            $current_page = ($current_page_ov) ? $current_page_ov : $params['paged'];


            $page_params .= ($page_params_ov) ? $page_params_ov : $form_vars['page_params'];





            $sort_str = $form_vars['sort_str'];


            $sdir_str = $form_vars['sdir_str'];


            $search_str = $form_vars['search_str'];





            $record_count = $armainhelper->getRecordCount($form_vars['where_clause'], $MdlDb->forms);


            $page_count = $armainhelper->getPageCount($arfpagesize, $record_count, $MdlDb->forms);


            $forms = $armainhelper->getPage($current_page, $arfpagesize, $form_vars['where_clause'], $form_vars['order_by'], $MdlDb->forms);


            $page_last_record = $armainhelper->getLastRecordNum($record_count,$current_page,$arfpagesize);


            $page_first_record = $armainhelper->getFirstRecordNum($record_count,$current_page,$arfpagesize);


        


        

		
        require(VIEWS_PATH.'/list.php');


    }

    function get_form_sort_vars($params,$where_clause = ''){


        $order_by = '';


        $page_params = '';



        $sort_str = $params['sort'];


        $sdir_str = $params['sdir'];


        $search_str = $params['search'];



        if(!empty($search_str)){


            $search_params = explode(" ", $search_str);





            foreach($search_params as $search_param){


                if(!empty($where_clause))


                    $where_clause .= " AND";





                $where_clause .= " (name like '%$search_param%' OR description like '%$search_param%' OR created_date like '%$search_param%')";


            }





            $page_params .="&search=$search_str";


        }



        if(!empty($sort_str))


            $page_params .="&sort=$sort_str";





        if(!empty($sdir_str))


            $page_params .= "&sdir=$sdir_str";



        switch($sort_str){


            case "id":


            case "name":


            case "description":


            case "form_key":


                $order_by .= " ORDER BY $sort_str";


                break;


            default:


                $order_by .= " ORDER BY name";


        }



        if((empty($sort_str) and empty($sdir_str)) or $sdir_str == 'asc'){


            $order_by .= ' ASC';


            $sdir_str = 'asc';


        }else{


            $order_by .= ' DESC';


            $sdir_str = 'desc';


        }





        return compact('order_by', 'sort_str', 'sdir_str', 'search_str', 'where_clause', 'page_params');


    }

    function get_edit_vars($id, $errors = '', $message='', $create_link=false, $template_id='', $popup_preview='',$is_ref_form=0){

	
        global $db_record, $arfform, $arffield, $arfajaxurl, $armainhelper, $arfieldhelper;

		
		if($is_ref_form == 1)
        	$record = $arfform->getRefOne( $id );
		else
			$record = $arfform->getOne( $id );


        $arffield_selection = $arfieldhelper->field_selection();


        $fields = $arffield->getAll(array('fi.form_id' => $record->id), 'field_order','','',1);


        $values = $armainhelper->setup_edit_vars($record, 'forms', $fields, true);




        $edit_message = __('Form is Successfully Updated', 'ARForms');
		
		
		if($template_id != '') {
		
			$form_name = (isset($_REQUEST['form_name'])) ? $_REQUEST['form_name'] : $values['name'];
			
			$form_desc = (isset($_REQUEST['form_desc'])) ? $_REQUEST['form_desc'] : $values['description'];
			
			$values['name'] = trim($form_name);
			
			$values['description'] = trim($form_desc);
		
		}


        if ($values['is_template'] and $message == $edit_message)


            $message = __('Template is Successfully Updated', 'ARForms');


      


        if (isset($values['is_template']) && $values['is_template'])


            wp_die(__('That template cannot be edited', 'ARForms'));


        else if($create_link)


            require(VIEWS_PATH.'/edit.php');


        else


            require(VIEWS_PATH.'/edit.php');


    }

    function get_params(){

		global $armainhelper;
		
        $values = array();


        foreach (array('template' => 0, 'id' => '', 'paged' => 1, 'form' => '', 'search' => '', 'sort' => '', 'sdir' => '') as $var => $default)


            $values[$var] = $armainhelper->get_param($var, $default);





        return $values;


    }

    function route(){
		
		global $wpdb, $armainhelper;


        $action = isset($_REQUEST['arfaction']) ? 'arfaction' : 'action';
		
		$newformid = isset($_REQUEST['newformid']) ? $_REQUEST['newformid'] : 0;
		
        $action = $armainhelper->get_param($action);

        if($action == 'new')


            return $this->new_form($newformid);
				
		
        else if($action == 'create')


            return $this->create();


        else if($action == 'edit')


            return $this->edit();


        else if($action == 'update')


            return $this->update();


        else if($action == 'duplicate')


            return $this->duplicate($newformid);


        else if($action == 'destroy')


            return $this->destroy();


        else if($action == 'list-form')


            return $this->list_form(); 


		else if($action == 'preview')
		
        	return $this->preview();
			
			
		else if($action == 'settings') 

			return $this->edit();
			
			
		else{


            $action = $armainhelper->get_param('action');


            if($action == -1)


                $action = $armainhelper->get_param('action2');


                


            if(strpos($action, 'bulk_') === 0){


                if(isset($_GET) and isset($_GET['action']))


                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action'], '', $_SERVER['REQUEST_URI']);


                if(isset($_GET) and isset($_GET['action2']))


                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action2'], '', $_SERVER['REQUEST_URI']);


                    


                return $this->list_form();


            }else{
               	return $this->display_forms_list();
            }


        }


    }

	function change_show_hide_column(){
	
		$colsArray = $_POST['colsArray'];
		
		$colsArray = $_POST['colsArray'];
		
		$new_arr = explode(',', $colsArray);
				
		$array_hidden = array();
		
		foreach( $new_arr as $key => $val ) {
		
		if( $key % 2 == 0 ) {
		
			if( $new_arr[$key+1] == 'hidden' ) $array_hidden[] = $val;
			}
		}
		
		$ser_arr = maybe_serialize($array_hidden);
		
		update_option('arfformcolumnlist', $ser_arr);
	
	die();
	}

	function arfupdateformbulkoption(){
	
		$return_array =  apply_filters('arfadminactionformlist', array());
			
        $errors = $return_array['error'];
		
		$message = $return_array['message'];
		
		return $this->change_form_listing($message, $errors);	
	
	die();
	}
	
	function change_form_listing($message='', $errors=''){
	
	$actions['bulk_delete'] = __('Delete', 'ARForms');
			
	$default_hide = array(
				'0' => '',
				'1' => 'ID',
				'2'	=> 'Name',
				'3' => 'Key',
				'4' => 'Entries',
				'5' => 'Shortcodes',
				'6' => 'Create Date',
				'7' => 'Action',
				);

	$columns_list = maybe_unserialize(get_option('arfformcolumnlist'));
	$is_colmn_array = is_array($columns_list);
	
	$exclude = '';
	
		if( count($columns_list) > 0 and $columns_list != '' ) {
		
			foreach($default_hide as $key => $val ){
			
				foreach($columns_list as $column){
				
					if($column == $val){
						$exclude .= $key.', ';
					}
				}
			
			}
		}
	
	if( $exclude=="" and !$is_colmn_array )
		$exclude .= '6, ';
	else if( $exclude and !strpos($exclude, '6,') and !$is_colmn_array )	
		$exclude .= '6, ';			

?>
<script type="text/javascript" charset="utf-8">
// <![CDATA[
	jQuery(document).ready( function () {
		jQuery.fn.dataTableExt.oPagination.four_button = {
		
		"fnInit": function ( oSettings, nPaging, fnCallbackDraw )
		{	
			nFirst = document.createElement( 'span' );
			nPrevious = document.createElement( 'span' );
			
			//===
			
			var nInput = document.createElement( 'input' );
			var nPage = document.createElement( 'span' );
			var nOf = document.createElement( 'span' );
			nOf.className = "paginate_of";
			nInput.className = "current_page_no";
			nPage.className = "paginate_page";
			nInput.type = "text";
			nInput.style.width = "40px";
			nInput.style.height = "26px";
			nInput.style.display = "inline";
			
			 
			nPaging.appendChild( nPage );
			
			 
			 
			jQuery(nInput).keyup( function (e) {
						 
				if ( e.which == 38 || e.which == 39 )
				{
					this.value++;
				}
				else if ( (e.which == 37 || e.which == 40) && this.value > 1 )
				{
					this.value--;
				}
	 
				if ( this.value == "" || this.value.match(/[^0-9]/) )
				{
					
					return;
				}
	 
				var iNewStart = oSettings._iDisplayLength * (this.value - 1);
				if ( iNewStart > oSettings.fnRecordsDisplay() )
				{
					
					oSettings._iDisplayStart = (Math.ceil((oSettings.fnRecordsDisplay()-1) /
						oSettings._iDisplayLength)-1) * oSettings._iDisplayLength;
					fnCallbackDraw( oSettings );
					return;
				}
	 
				oSettings._iDisplayStart = iNewStart;
				fnCallbackDraw( oSettings );
			} );
	 
			
		
			nNext = document.createElement( 'span' );
			nLast = document.createElement( 'span' );
			var nFirst = document.createElement( 'span' );
			var nPrevious = document.createElement( 'span' );
			var nPage = document.createElement( 'span' );
			var nOf = document.createElement( 'span' );
			
			nNext.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/next_normal-icon.png')";
			nNext.style.backgroundRepeat = "no-repeat";
			nNext.style.backgroundPosition = "center";
			nNext.title = "Next";
			
			nLast.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/last_normal-icon.png')";
			nLast.style.backgroundRepeat = "no-repeat";
			nLast.style.backgroundPosition = "center";
			nLast.title = "Last";
			
			nFirst.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/first_normal-icon.png')";
			nFirst.style.backgroundRepeat = "no-repeat";
			nFirst.style.backgroundPosition = "center";
			nFirst.title = "First";
			
			nPrevious.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/previous_normal-icon.png')";
			nPrevious.style.backgroundRepeat = "no-repeat";
			nPrevious.style.backgroundPosition = "center";		
			nPrevious.title = "Previous";		
			
			nFirst.appendChild( document.createTextNode( ' ' ) );
			nPrevious.appendChild( document.createTextNode( ' ' ) );
			
			nLast.appendChild( document.createTextNode( ' ' ) );
			nNext.appendChild( document.createTextNode( ' ' ) );
			
			 
			
			nOf.className = "paginate_button nof";
			 
			nPaging.appendChild( nFirst );
			nPaging.appendChild( nPrevious );
			
			nPaging.appendChild( nInput );
			nPaging.appendChild( nOf );
			
			nPaging.appendChild( nNext );
			nPaging.appendChild( nLast );
			 
			jQuery(nFirst).click( function () {
				oSettings.oApi._fnPageChange( oSettings, "first" );
				fnCallbackDraw( oSettings );
			} );
			 
			jQuery(nPrevious).click( function() {
				oSettings.oApi._fnPageChange( oSettings, "previous" );
				fnCallbackDraw( oSettings );
			} );
			 
			jQuery(nNext).click( function() {
				oSettings.oApi._fnPageChange( oSettings, "next" );
				fnCallbackDraw( oSettings );
			} );
			 
			jQuery(nLast).click( function() {
				oSettings.oApi._fnPageChange( oSettings, "last" );
				fnCallbackDraw( oSettings );
			} );
			 
			
			jQuery(nFirst).bind( 'selectstart', function () { return false; } );
			jQuery(nPrevious).bind( 'selectstart', function () { return false; } );
			jQuery('span', nPaging).bind( 'mousedown', function () { return false; } );
			jQuery('span', nPaging).bind( 'selectstart', function () { return false; } );
			jQuery(nNext).bind( 'selectstart', function () { return false; } );
			jQuery(nLast).bind( 'selectstart', function () { return false; } );
		},
		 
		
		"fnUpdate": function ( oSettings, fnCallbackDraw )
		{
			if ( !oSettings.aanFeatures.p )
			{
				return;
			}
			 
			
			var an = oSettings.aanFeatures.p;
			for ( var i=0, iLen=an.length ; i<iLen ; i++ )
			{
				var buttons = an[i].getElementsByTagName('span');
				
				
				if ( oSettings._iDisplayStart === 0 )
				{
					
					buttons[1].className = "paginate_disabled_first arfhelptip";
					buttons[2].className = "paginate_disabled_previous arfhelptip";
				}
				else
				{
					
					buttons[1].className = "paginate_enabled_first arfhelptip";
					buttons[2].className = "paginate_enabled_previous arfhelptip";
				}
	
				if ( oSettings.fnDisplayEnd() == oSettings.fnRecordsDisplay() )
				{
					buttons[4].className = "paginate_disabled_next arfhelptip";
					buttons[5].className = "paginate_disabled_last arfhelptip";
				}
				else
				{
					
					buttons[4].className = "paginate_enabled_next arfhelptip";
					buttons[5].className = "paginate_enabled_last arfhelptip";
				}


				

				if ( !oSettings.aanFeatures.p )
				{
					return;
				}
				var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);
				var iCurrentPage = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;
		 
				
				var an = oSettings.aanFeatures.p;
				for ( var i=0, iLen=an.length ; i<iLen ; i++ )
				{
					var spans = an[i].getElementsByTagName('span');
					var inputs = an[i].getElementsByTagName('input');
					spans[spans.length-3].innerHTML = " of "+iPages
					inputs[0].value = iCurrentPage;
				}
			}
		}
	}
		
	jQuery('#example').dataTable( {
		"sDom": '<"H"lCfr>t<"footer"ip>',
		"sPaginationType": "four_button",
		"bJQueryUI": true,
		"bPaginate": true,					
		"aoColumnDefs": [
			{ "bVisible": false, "aTargets": [<?php if($exclude!='') echo $exclude;?>] },
			{ "bSortable": false, "aTargets": [ 0, 7 ] }
		],
		"oColVis": {
		   "aiExclude": [ 0, 7 ]
		},
		
		
		
		});
});
			
		

// ]]>

jQuery(document).ready( function () { 	
	
    jQuery("#cb-select-all-1").click(function () {
          jQuery('input[name="item-action[]"]').attr('checked', this.checked);
    });
 
   
    jQuery('input[name="item-action[]"]').click(function(){
 
        if(jQuery('input[name="item-action[]"]').length == jQuery('input[name="item-action[]"]:checked').length) {
            jQuery("#cb-select-all-1").attr("checked", "checked");
        } else {
            jQuery("#cb-select-all-1").removeAttr("checked");
        }
 
    });
	
});
							
</script>
			<?php require(VIEWS_PATH.'/shared_errors.php'); ?>	
			
            <div style="position:absolute;right:50px;">
                <button class="greensavebtn" type="button" onclick="location.href='<?php echo admin_url('admin.php?page=ARForms&arfaction=new&isp=1');?>';" style="width:160px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/plus-icon.png">&nbsp;&nbsp;<?php _e('Add New Form', 'ARForms') ?></button>
            </div>
                
            <div class="alignleft actions">
                        <?php 
                        $two = '1';
                        echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two'>\n";	
                        echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms') . "</option>\n";
                
                        foreach ( $actions as $name => $title ) {
                            $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';
                
                            echo "\t<option value='$name'$class>$title</option>\n";
                        }
                
                        echo "</select></div>\n";
                		
						echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__("Apply",'ARForms').'" />';
                        echo "\n";
                        
                        ?>
                </div>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="center" style="width:50px;"><div style="display:inline-block; position:relative;"><input id="cb-select-all-1" type="checkbox" class="chkstanard"><label for="cb-select-all-1"  class="cb-select-all"><span></span></label></div></th>
            <th style="width:80px;"><?php _e('ID','ARForms');?></th>
            <th><?php _e('Name','ARForms');?></th>
            <th style="width:100px;"><?php _e('Key','ARForms');?></th>
            <th class="center" style="width:90px;"><?php _e('Entries','ARForms');?></th>
            <th><?php _e('Shortcodes','ARForms');?></th>
            <th style="width:100px;"><?php _e('Create Date','ARForms');?></th>
            <th class="col_action" style="width:230px;"><?php _e('Action','ARForms');?></th>
		</tr>
	</thead>
	<tbody>
<?php
global $wpdb, $db_record;

$form_result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE is_template = %d AND (status is NULL OR status = '' OR status = 'published') order by id desc", 0), OBJECT_K );

foreach($form_result as $key => $val) {
			$res = $wpdb->get_results( $wpdb->prepare( "SELECT is_enable FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id ), 'ARRAY_A' );
?>    
		<tr class="<?php if( $res[0]['is_enable'] == 0 ) {echo 'grid_disable_row';}else{echo '';}?>">
			<td class="center"><input id="cb-item-action-<?php echo $val->id;?>" class="chkstanard" type="checkbox" value="<?php echo $val->id;?>" name="item-action[]"><label for="cb-item-action-<?php echo $val->id;?>"><span></span></label></td>
            <td><?php echo $val->id;?></td>
			<td class="form_name"><?php 
			$edit_link = "?page=ARForms&arfaction=edit&id={$val->id}";
			
					
			if( $res[0]['is_enable'] == 0 )  
				echo '<a class="row-title" href="'.$edit_link.'">'. stripslashes($val->name) .'</a><br /><span style="color:#FF0000;">(Disabled)</span>';					
			else
				echo '<a class="row-title" href="'.$edit_link.'">'. stripslashes($val->name) .'</a> ';
						
			?></td>
			<td><?php echo $val->form_key;?></td>
			<td class="form_entries center"><?php
			$entries = $db_record->getRecordCount($val->id);
			echo (current_user_can('arfviewentries')) ? '<a href="'. esc_url(admin_url('admin.php') .'?page=ARForms-entries&form='. $val->id ) .'">'. $entries .' '.__('Entries', 'ARForms').'</a>' : $entries.' '.__('Entries', 'ARForms');
			?></td>
			<td><input type="text" class="shortcode_textfield" readonly="true" onclick="this.select();" onfocus="this.select();" value="[ARForms id=<?php echo $val->id; ?>]" /><br/>
            <input type="text" class="shortcode_textfield" readonly="true" onclick="this.select();" onfocus="this.select();" value="[ARForms_popup id=<?php echo $val->id; ?> desc='Click here to open Form' type='link' height='540' width='800']" /></td>
            <td><?php 
							$wp_format_date = get_option('date_format');
					
							if( $wp_format_date == 'F j, Y' || $wp_format_date =='m/d/Y' ) {
								$date_format_new = 'M d, Y';
							} else if( $wp_format_date == 'd/m/Y' ) {
								$date_format_new = 'd M, Y';
							} else if( $wp_format_date == 'Y/m/d' ) {
								$date_format_new = 'Y, M d';
							} else {
								$date_format_new = 'M d, Y';
							}
							
							echo date($date_format_new, strtotime($val->created_date));?></td>
            <td class="col_action" style="width:230px;">
            <div class="row-actions">
            
                <?php if(current_user_can('arfeditforms')){ 
				
				$edit_link = "?page=ARForms&arfaction=edit&id={$val->id}";

				echo "<a href='" . wp_nonce_url( $edit_link ) . "'><img src='".ARFIMAGESURL."/edit-icon22.png' onmouseover=\"this.src='".ARFIMAGESURL."/edit-icon_hover22.png';\" class='arfhelptip' title='".__('Edit Form','ARForms')."' onmouseout=\"this.src='".ARFIMAGESURL."/edit-icon22.png';\" /></a>";

				$duplicate_link = "?page=ARForms&arfaction=duplicate&id={$val->id}";

				 }  
				 
				 if(current_user_can('arfeditforms')){ 

                 echo "<a href='" . wp_nonce_url( "?page=ARForms-entries&arfaction=list&form={$val->id}" ) . "'><img src='".ARFIMAGESURL."/listing_icon22.png' title='".__('Form Entry','ARForms')."'  class='arfhelptip' onmouseover=\"this.src='".ARFIMAGESURL."/listing_icon_hover22.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/listing_icon22.png';\" /></a>";
			
				echo "<a href='" . wp_nonce_url( $duplicate_link ) . "'><img src='".ARFIMAGESURL."/duplicate-icon22.png' title='".__('Duplicate Form','ARForms')."'  class='arfhelptip' onmouseover=\"this.src='".ARFIMAGESURL."/duplicate-icon_hover22.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/duplicate-icon22.png';\" /></a>";
				
				echo "<a href='javascript:void(0);' onclick='arfaction_func(\"export_csv\", \"{$val->id}\");'><img src='".ARFIMAGESURL."/export.png' title='".__('Export To CSV','ARForms')."' class='arfhelptip' onmouseover=\"this.src='".ARFIMAGESURL."/export_hover.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/export.png';\" /></a>";
				
				}
				
				do_action('arf_additional_action_formlisting',$val->id);
				
                if(current_user_can('arfdeleteforms')){ 
                
                	$delete_link = "?page=ARForms&arfaction=destroy&id={$val->id}";


						
					$id = $val->id;
						echo "<img src='".ARFIMAGESURL."/delete_icon222.png' title='".__('Delete','ARForms')."' onmouseover=\"this.src='".ARFIMAGESURL."/delete_icon2_hover222.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/delete_icon222.png';\" data-toggle='arfmodal' href='#delete_form_message' onclick=\"ChangeID({$id});arfchangedeletemodalwidth('arfdeletemodabox');\" class='arfhelptip' style='cursor:pointer'/></a>";	
					
				}
				
				global $style_settings, $arformhelper;
	
							
																
                                $target_url = $arformhelper->get_direct_link($val->form_key);
                                
								$target_url = $target_url.'&ptype=list';	
								
								$width = @$_COOKIE['width'] * 0.80;	
	
								if(isset($_COOKIE['width']) and $_COOKIE['width'] != '')
									$tb_width = '&width='.$width;
								else
									$tb_width = '';
																
								if(isset($_COOKIE['height']) and $_COOKIE['height']!='') 
									$tb_height = '&height='.($_COOKIE['height']-100);
								else
									$tb_height = '';
									
				$target_url = $arformhelper->get_direct_link($val->form_key);
				echo "<a class='openpreview' href='#' data-url='".$target_url.$tb_width.$tb_height."&whichframe=preview&TB_iframe=true&ptype=list'><img src='".ARFIMAGESURL."/view_icon22.png'  onmouseover=\"this.src='".ARFIMAGESURL."/view_icon_hover22.png';\" title='".__('Preview','ARForms')."' class='arfhelptip' onmouseout=\"this.src='".ARFIMAGESURL."/view_icon22.png';\" /></a>";	?>
                
            </div>
            </td>
		</tr>
<?php } ?>   
    </tbody>
</table>
<div class="clear"></div>
				<input type="hidden" name="show_hide_columns" id="show_hide_columns" value="<?php _e('Show / Hide columns','ARForms');?>"/>
                <input type="hidden" name="search_grid" id="search_grid" value="<?php _e('Search','ARForms');?>"/>
                <input type="hidden" name="entries_grid" id="entries_grid" value="<?php _e('entries','ARForms');?>"/>
                <input type="hidden" name="show_grid" id="show_grid" value="<?php _e('Show','ARForms');?>"/>
                <input type="hidden" name="showing_grid" id="showing_grid" value="<?php _e('Showing','ARForms');?>"/>
                <input type="hidden" name="to_grid" id="to_grid" value="<?php _e('to','ARForms');?>"/>
                <input type="hidden" name="of_grid" id="of_grid" value="<?php _e('of','ARForms');?>"/>
	            <input type="hidden" name="no_match_record_grid" id="no_match_record_grid" value="<?php _e('No matching records found','ARForms');?>"/>
                <input type="hidden" name="no_record_grid" id="no_record_grid" value="<?php _e('No data available in table','ARForms');?>"/>
                <input type="hidden" name="filter_grid" id="filter_grid" value="<?php _e('filtered from','ARForms');?>"/>
                <input type="hidden" name="totalwd_grid" id="totalwd_grid" value="<?php _e('total','ARForms');?>"/>                
                <div class="alignleft actions2">
                        <?php 
                        $two = '2';
                        echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two'>\n";
                        echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms') . "</option>\n";
                
                        foreach ( $actions as $name => $title ) {
                            $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';
                
                            echo "\t<option value='$name'$class>$title</option>\n";
                        }
                
                        echo "</select></div>\n";
                		
						echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__("Apply",'ARForms').'" />';
                        echo "\n";
                        
                        ?>
   				</div>
				<div class="footer_grid"></div>
<script language="javascript">
jQuery(document).ready(function($){

	$('a[class="openpreview"]').click(function(){
		var frameSrc = $(this).attr("data-url");
		var modalheight = jQuery(window).height();
		var modalwidth = jQuery(window).width();
		var getModalWidth = Number(modalwidth) * 0.80;
		var getModalLeftWidth = (Number(modalwidth) * 0.20) / 2;
		var getModalHeight = Number(modalheight) - 100;
		var modalbodyheight = getModalHeight - 144 + 82;
		var loaderheight = (modalbodyheight / 2) - 50;
		var loaderleft = (getModalWidth / 2) - 50;
		
		$('#form_preview_modal').attr('style','display:none; width:'+getModalWidth+'px; height:'+getModalHeight+'px; top:50px; left:'+getModalLeftWidth+'px');
		$('.arfmodal-body').attr('style','overflow:hidden; clear:both; padding:0; height:'+modalbodyheight+'px');
			
		$('#form_preview_modal .arfdevices').removeClass('arfactive');
		$('#form_preview_modal #arfcomputer').addClass('arfactive');
		
		$('#form_preview_modal').attr('data-modalwidth', getModalWidth);
		$('#form_preview_modal').attr('data-modalleft', getModalLeftWidth);
		
		$('#form_preview_modal').on('show', function () {
			$('iframe').attr("style","display:none");												  
			$('.iframe_loader').attr("style",'display:block; top:'+loaderheight+'px; position: relative;');												  
			$('iframe').attr("src",frameSrc);
		  
		});
		$('#form_preview_modal').arfmodal({show:true});
		$('#form_preview_modal #arfdevicepreview').load(function(){ $('.iframe_loader').attr("style",'display:none'); $('iframe').attr("style","display:block"); });
	});
});
</script>                
<?php 
	
	
	die();
	}
	
	function arfupdateactionfunction(){
	
	global $wpdb, $arfform;
		
		$action = $_POST['act'];
		$id = $_POST['id'];
		
		if( $action == 'delete' ){
			$del_res = $arfform->destroy( $id );
			if($del_res) $message = __('Record is deleted successfully.', 'ARForms');	
		}
		
		if( $action == 'export_csv' ){
			echo '<script type="text/javascript">location.href="'.site_url().'/index.php?plugin=ARForms&controller=entries&form='. $id .'&arfaction=csv";</script>';
		}
		
		$errors = array();	
		return $this->change_form_listing(@$message, @$errors);
	
	die();
	}
	
	function arfformsavealloptions(){
		$str = stripslashes_deep($_REQUEST['form']);
		$str = json_decode( $str, true );
		
		global $wpdb;
		$form = $str['id'];
		
		$ref_formid = $str['ref_formid'];
		
		$form_chk_id = ($ref_formid > 0) ? $ref_formid : $str['id'];
			
		$form_chk_res = $wpdb->get_results($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $form_chk_id), ARRAY_A);
				
		if( count($form_chk_res) == 0 ) { 
			echo 'deleted';
			exit();
		}
		
	
		$res = $wpdb->get_results( $wpdb->prepare("SELECT status FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $form), 'ARRAY_A');
		if( $res )
			$res = $res[0];
		if($str['form_preview']=="none")		
		{
			$this->update('none');
		}
		else
		{
			$this->update('preview');
		}	
		
		$wpdb->update( $wpdb->prefix.'arf_forms', array('status' => $res['status']), array( 'id' => $form ) );
		
	
	die();
	}

	function global_reset_style_setting(){
	
		global $arfadvanceerrcolor, $armainhelper, $arformhelper, $arrecordcontroller, $arformcontroller;
		$id = $_POST['form'];
		
		$globalarr = get_option("arfa_options");
		
		$newarr = array();
		
		foreach($globalarr as $k => $v)
			$newarr[$k] = $v;
		
		foreach($newarr as $k => $v){	
			if( strpos($v,'#') === FALSE ) 
			{
				if( ( preg_match('/color/', $k) or in_array($k, array('arferrorbgsetting', 'arferrorbordersetting', 'arferrortextsetting')) ) && ! in_array($k, array('arfcheckradiocolor') ) ) { 
					$newarr[$k] = '#'.$v;
				} else {
					$newarr[$k] = $v;		
				}
			}			
		}
		
		$browser_info = $arrecordcontroller->getBrowser($_SERVER['HTTP_USER_AGENT']);			
		?>
     
	    <ul class="frm_styling_icons">
			<li>
            <div id="preview-form-styling-setting" <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ echo ''; } else { ?>style="height:1px;"<?php } ?> >	
			<div class="clearfix frm_settings_page">
                <fieldset class="clearfix">			    
            
            		<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
					<style type="text/css">#preview-form-styling-setting .widget-inside { display:none; } </style>
					<?php } ?>

                    <div id="tabformsettings" class="widget clearfix global-font current_widget">
            
            
                        <div id="first_tab" class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Form Settings', 'ARForms') ?></h4></div>
            
                        </div>
            
            
                        <div class="widget-inside" style="display: block;">
                            
                            <div class="field-group clearfix widget_bg_bottom">
            				<?php
								if(is_rtl())
								{
									$form_width_lbl_style = 'float:right;width:134px;text-align:right;';
								}
								else
								{
									$form_width_lbl_style = 'width:134px;';
								}
							?>
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $form_width_lbl_style; ?>"><?php _e('Form Width', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$floating_class_1 = 'arf_float_left';
										$frm_width_txtbx_css = 'float:right;width:87px;';
										$frm_width_drpdwn_css = 'float:right;padding-left:5px;margin:auto 5px auto -25px;';
										$frm_width_drpdwn_val = 'float:right;';
									}
									else
									{
										$floating_class_1 = 'arf_float_right';
										$frm_width_txtbx_css = 'float:left;width:87px;';
										$frm_width_drpdwn_css = 'float:left;padding-left:5px;';
										$frm_width_drpdwn_val = 'float:left;';
									}
							   ?>
                               
                                <div class=" <?php echo $floating_class_1; ?>" style=" <?php echo $frm_width_drpdwn_val;?>">
                                    <input type="text" name="arffw" style=" <?php echo $frm_width_txtbx_css; ?>" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainformwidth']) ?>"/> 
                                    
                                    <div class="sltstandard1" style="float:left;padding-left:5px;">
                                        <input id="arffu" name="arffu" value="<?php echo $newarr['form_width_unit'];?>" type="hidden" onchange="change_style_unit(this.value);">
                                        <dl class="arf_selectbox" data-name="arffu" data-id="arffu" style="width:53px;">
                                          <dt><span><?php echo $newarr['form_width_unit'];?></span>
                                            <input value="<?php echo $newarr['form_width_unit'];?>" style="display:none;width:41px;" class="arf_autocomplete" type="text">
                                            <i class="fa fa-caret-down fa-lg"></i></dt>
                                          <dd>
                                            <ul style="display: none;" data-id="arffu">
                                              <li class="arf_selectbox_option" data-value="<?php _e('px', 'ARForms') ?>" data-label="<?php _e('px', 'ARForms') ?>"><?php _e('px', 'ARForms') ?></li>
                                              <li class="arf_selectbox_option" data-value="<?php _e('%', 'ARForms') ?>" data-label="<?php _e('%', 'ARForms') ?>"><?php _e('%', 'ARForms') ?></li>
                                            </ul>
                                          </dd>
                                        </dl>
                                    </div>
                                	
                                
                                </div>    
            
                            </div>
                          
                            
                            
                          
                          <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$form_align_lbl_style = 'float:right;width:64px;';
									}
									else
									{
										$form_align_lbl_style = 'width:64px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $form_align_lbl_style; ?>"><?php _e('Align', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$frm_align_opt_css = 'float:right;margin-left:-5px;;';
									}
									else
									{
										$frm_align_opt_css = 'float:left;';
									}
                               ?>
            					
                                <div class="sltstandard1"  style=" <?php echo $frm_align_opt_css; ?>">
                                    <div class="toggle-btn-grp joint-toggle">
                                            <label onclick="" class="toggle-btn left <?php if($newarr['form_align']=="left"){ echo "success"; }?>"><input type="radio" name="arffa" class="visuallyhidden" value="left" <?php checked($newarr['form_align'], 'left'); ?> /><?php _e('Left', 'ARForms') ?></label><label onclick="" class="toggle-btn center <?php if($newarr['form_align']=="center"){ echo "success"; }?>"><input type="radio" name="arffa"  class="visuallyhidden" value="center" <?php checked($newarr['form_align'], 'center'); ?> /><?php _e('Center', 'ARForms') ?></label><label onclick="" class="toggle-btn right <?php if($newarr['form_align']=="right"){ echo "success"; }?>"><input type="radio" name="arffa" class="visuallyhidden" value="right" <?php checked($newarr['form_align'], 'right'); ?> /><?php _e('Right', 'ARForms') ?></label>
                                    </div>
                                
                                </div>
                                
                            </div>
            
                            
                            
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$frm_bg_lbl_title = 'float:right;width:100%;text-align:right;';
										$frm_bgcol_lbl_title = 'float:right;width:125px;';
										$frm_bgcol_colorpicker = 'float:right;';
									}
									else
									{
										$frm_bg_lbl_title = 'width:100%;';
										$frm_bgcol_lbl_title = 'width:125px;';
										$frm_bgcol_colorpicker = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_bg_lbl_title; ?>"><?php _e('Form Background', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            					
                                <label class="lblsubheading sublblheading" style=" <?php echo $frm_bgcol_lbl_title; ?>"><?php _e('Background Color', 'ARForms') ?></label>
                            
                                <div class="arf_float_right" style=" <?php echo $frm_bgcol_colorpicker; ?>">
                                	
                                    
                                	<div class="arf_coloroption_sub">
                                    	<div class="arf_coloroption arfhex" data-fid="arfformbgcolorsetting" style="background:<?php echo esc_attr($newarr['arfmainformbgcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                        	<div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    
                                	<input type="hidden" name="arffbcs" id="arfformbgcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfmainformbgcolorsetting']) ?>" style="width:100px;" />
                                </div>
                                
                            </div>
                            
                            <div class="field-group clearfix subfield widget_bg_bottom" style="margin-top:11px; padding-bottom:10px;">
                            	<?php
									if(is_rtl())
									{
										$bg_img_lbl = 'float:right;padding-left:0;width:120px;';
										$ajax_loader_style = 'float:left;display:none;margin:5px 0 0 -99px';
									}
									else
									{
										$bg_img_lbl = 'width:120px;';
										$ajax_loader_style = 'float:left;display:none;margin:5px 0 0;';
									}
								?>                        
                                <label class="lblsubheading sublblheading" style=" <?php echo $bg_img_lbl; ?>"><?php _e('Background Image', 'ARForms') ?></label>
                            	
                                <div id="form_bg_img_div" <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9'){ ?> class="iframe_original_btn" data-id="arfmfbi" style="margin-left:5px; position: relative; overflow: hidden; float:left; cursor:pointer; max-width:140px; height:22px; background: #1BBAE1; font-weight:bold; <?php if($newarr['arfmainform_bg_img'] == '') { ?> background:#1BBAE1;padding: 7px 10px 0 10px;font-size:13px;border-radius:3px;color:#FFFFFF;border:1px solid #CCCCCC; <?php } ?>" <?php }else { ?> style="margin-left:0px;" <?php } ?>  >
                                	<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' && $newarr['arfmainform_bg_img'] == ''){ ?><span style="display:inline-block;color:#FFFFFF;text-align:center;"><?php _e('Upload', 'ARForms');?></span><?php } ?>
                                    <?php
									if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ 
										if( $newarr['arfmainform_bg_img'] != '' ) { ?>
                                        	<img src="<?php echo $newarr['arfmainform_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_form_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        	<input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="<?php echo esc_attr($newarr['arfmainform_bg_img']) ?>" id="arfmainform_bg_img" />
                                        <?php } else {?>
                                    
                                    <input type="text" class="original" name="form_bg_img" id="field_arfmfbi" form-id="" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; width: 160px; height: 59px; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />

									<input type="hidden" id="type_arfmfbi" name="type_arfmfbi" value="1" >
									<input type="hidden" value="jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico" id="file_types_arfmfbi" name="field_types_arfmfbi" />
                                    <input type="hidden" name="imagename_form" id="imagename_form" value="" />
                                    <input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="" id="arfmainform_bg_img" />
                                    
									<?php
										}
										echo '<div id="arfmfbi_iframe_div"><iframe style="display:none;" id="arfmfbi_iframe" src="'.ARFURL.'/core/views/iframe.php" ></iframe></div>';
                                    }else {
									?>
										<?php if( $newarr['arfmainform_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['arfmainform_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_form_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="<?php echo esc_attr($newarr['arfmainform_bg_img']) ?>" id="arfmainform_bg_img" />
                                        <?php } else { ?>
                                        
                                        <div class="arfajaxfileupload" style="position: relative; overflow: hidden; float:left; cursor: pointer;">
                                        <div class="file-upload-img"></div>
                                        	<?php _e('Upload', 'ARForms');?>
                                        	<input type="file" name="form_bg_img" id="form_bg_img" data-val="form_bg" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        </div>
                                        
                                        
                                        <input type="hidden" name="imagename_form" id="imagename_form" value="" />
                                        <input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="" id="arfmainform_bg_img" />
                                        &nbsp;&nbsp;<span id="ajax_form_loader" style="display:none; float: left; margin: 5px 0 0;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/wpspin_light.gif"></span>
										<?php } ?>
                                    <?php } ?>
                                </div>
                                
                            </div>
                            
                            
                            
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$frm_brdr_title = 'width:100%;float:right;text-align:right;';
										$frm_brdr_btn = 'float:right;';
									}
									else
									{
										$frm_brdr_title = 'width:100%;';
										$frm_brdr_btn = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_brdr_title; ?>"><?php _e('Form Border', 'ARForms') ?></label> <br />
            
                            </div>
            
                            
                            <div class="field-group clearfix subfield" style="margin-top:3px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:122px;"><?php _e('Type', 'ARForms') ?></label>
                                <div style=" <?php echo $frm_brdr_btn; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" class="toggle-btn normal <?php if($newarr['form_border_shadow']=="shadow"){ echo "success"; }?>"><input type="radio" name="arffbs" class="visuallyhidden" id="arfmainformbordershadow1" value="shadow" <?php checked($newarr['form_border_shadow'], 'shadow'); ?> /><?php _e('Shadow', 'ARForms') ?></label><label onclick="" class="toggle-btn normal <?php if($newarr['form_border_shadow']=="flat"){ echo "success"; }?>"><input type="radio" name="arffbs" class="visuallyhidden" value="flat"  id="arfmainformbordershadow2" <?php checked($newarr['form_border_shadow'], 'flat'); ?> /><?php _e('Flat', 'ARForms') ?></label>
                                    </div>
                                </div>
            
                            </div>
                            
                            <div class="field-group field-group-border subfield clearfix" style="margin-top:25px; margin-bottom:5px;">
            
            					<?php
									if(is_rtl())
									{
										$frm_slider_lbl_start = 'float:left;margin-left:-145px;margin-top:5px;';
										$frm_slider_lbl_end = 'float:right;display:inline;margin-right:45px;margin-top:0px;';
									}
									else
									{
										$frm_slider_lbl_start = 'float:left;margin-left:40px;';
										$frm_slider_lbl_end = 'float:right;display:inline;';
									}
								?>
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Size', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" name="arfmfis" style="width:142px;" class="txtxbox_widget"  id="arfmainfieldset" value="<?php echo esc_attr($newarr['fieldset']) ?>" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                               	<input id="arfmainfieldset_exs" class="arf_slider" data-slider-id='arfmainfieldset_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['fieldset']) ?>" />
                                <br />
                                <div style="width:142px; display:inline;">
                                	<div style=" <?php echo $frm_slider_lbl_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div style=" <?php echo $frm_slider_lbl_end; ?> "><?php _e('50 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfmfis" style="width:100px;" class="txtxbox_widget"  id="arfmainfieldset" value="<?php echo esc_attr($newarr['fieldset']) ?>" size="4" />
                                <?php } ?>
            
                            </div>
            
            
                            <div class="field-group field-group-border subfield clearfix" style="margin-top:25px; margin-bottom:15px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Radius', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" name="arfmfsr" style="width:142px;" class="txtxbox_widget"  id="arfmainfieldsetradius" value="<?php echo esc_attr($newarr['arfmainfieldsetradius']) ?>" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                               <input id="arfmainfieldsetradius_exs" class="arf_slider" data-slider-id='arfmainfieldsetradius_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['arfmainfieldsetradius']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div style=" <?php echo $frm_slider_lbl_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div style=" <?php echo $frm_slider_lbl_end; ?>"><?php _e('100 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfmfsr" style="width:100px;" class="txtxbox_widget"  id="arfmainfieldsetradius" value="<?php echo esc_attr($newarr['arfmainfieldsetradius']) ?>" size="4" />
                                <?php } ?>
                            </div>
            				
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$frm_brd_col_lbl = 'float:right;width:100%;text-align:right;';
									}
									else
									{
										$frm_brd_col_lbl = 'width:100%;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_brd_col_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label> <br />
            
                            </div>
            				<?php
								if(is_rtl())
								{
									$frm_brd_line_main = 'float:right;margin-left:20px;width:43%;margin-top:10px;clear:none;';
									$frm_brd_line_lbl = 'text-align:left;width:126px;';
								}
								else
								{
									$frm_brd_line_main = 'float:left;width:100%;margin-top:10px;clear:none;';
									$frm_brd_line_lbl = 'width:126px;';
								}
							?>
                            <div class="field-group clearfix subfield" style=" <?php echo $frm_brd_line_main; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $frm_brd_line_lbl; ?>"><?php _e('Line', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$frm_brd_line_cls1 = 'arf_float_right';
									}
									else
									{
										$frm_brd_line_cls1 = 'arf_float_left';
										$frm_brd_line_css = 'float:left;';
									}
								?>
            					<div class=" <?php echo $frm_brd_line_cls1; ?>" style=" <?php echo $frm_brd_line_css; ?>">
                                
                                <div class="arf_coloroption_sub">
                                    <div class="arf_coloroption arfhex" data-fid="arfmainfieldsetcolor" style="background:<?php echo esc_attr($newarr['arfmainfieldsetcolor']) ?>;"></div>
                                    <div class="arf_coloroption_subarrow_bg">
                                        <div class="arf_coloroption_subarrow"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="arfmfsc" id="arfmainfieldsetcolor" class="hex txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainfieldsetcolor']) ?>" style="width:100px;" />
                                </div>
            
           
                            </div>
                         	<?php
								if(is_rtl())
								{
									$frm_shadow_main = 'float:right;margin-top:11px;clear:left;width:100%;';
									$frm_shadow_cls = 'arf_float_right';
									$frm_shadow_css = 'margin-right:60px;';
								}
								else
								{
									$frm_shadow_main = 'float:left;margin-top:11px;clear:right;width:100%;';
									$frm_shadow_cls = 'arf_float_left';
									$frm_shadow_css = 'float:left;';
								}
							?>
                            <div class="field-group clearfix subfield " style=" <?php echo $frm_shadow_main; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $frm_brd_line_lbl; ?>"><?php _e('Shadow', 'ARForms') ?></label>
            
            					<div class=" <?php echo $frm_shadow_cls; ?>" style=" <?php echo $frm_shadow_css; ?>">
                                
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfformbordershadowsetting" style="background:<?php echo esc_attr($newarr['arfmainformbordershadowcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                
                                <input type="hidden" name="arffboss" id="arfformbordershadowsetting" class="hex txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainformbordershadowcolorsetting']) ?>" style="width:100px;" />
            					</div>
                                
                            </div>
                            
                            <div class="clear widget_bg_bottom" style="clear:both;"></div>
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$frm_padding_title = 'float:right;width:45px;text-align:right;';
										$frm_padding_btn_main = 'float:right;margin-right:-5px;';
									}
									else
									{
										$frm_padding_title = 'width:45px;';
										$frm_padding_btn_main = 'float:right;margin-right:-29px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_padding_title; ?>"><?php _e('Padding', 'ARForms') ?></label>
            					
                                <div style=" <?php echo $frm_padding_btn_main; ?>">
            						<div style="float:left;"><input type="text" name="arfmainfieldsetpadding_1" id="arfmainfieldsetpadding_1" onchange="arf_change_field_padding('arfmainfieldsetpadding');" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_1']); ?>" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:2px;"><?php _e('Top', 'ARForms');?></span></div>&nbsp;
                                	<div style="float:left; margin-left:8px;"><input type="text" name="arfmainfieldsetpadding_2" id="arfmainfieldsetpadding_2" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_2']); ?>" onchange="arf_change_field_padding('arfmainfieldsetpadding');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Right', 'ARForms');?></span></div>&nbsp;
                                	<div style="float:left; margin-left:8px;"><input type="text" name="arfmainfieldsetpadding_3" id="arfmainfieldsetpadding_3" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_3']); ?>" onchange="arf_change_field_padding('arfmainfieldsetpadding');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px; margin-left:3px;" /><br /><span class="arf_px" style="padding-left:5px;"><?php _e('Bottom', 'ARForms');?></span></div>&nbsp;
                                	<div style="float:left; margin-left:8px;"><input type="text" name="arfmainfieldsetpadding_4" id="arfmainfieldsetpadding_4" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_4']); ?>" onchange="arf_change_field_padding('arfmainfieldsetpadding');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Left', 'ARForms');?></span></div>&nbsp;
                                &nbsp;<span class="arf_px" style="float:left; padding-top:5px; margin-left:6px;"><?php _e('px', 'ARForms') ?></span>
                                </div>
                                <?php 
								$arfmainfieldsetpadding_value = '';
								
								if( esc_attr($newarr['arfmainfieldsetpadding_1']) != '' ){
									$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_1'].'px ';
								}else{
									$arfmainfieldsetpadding_value .= '0px ';
								}
								if( esc_attr($newarr['arfmainfieldsetpadding_2']) != '' ){
									$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_2'].'px ';
								}else{
									$arfmainfieldsetpadding_value .= '0px ';
								}					
								if( esc_attr($newarr['arfmainfieldsetpadding_3']) != '' ){
									$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_3'].'px ';
								}else{
									$arfmainfieldsetpadding_value .= '0px ';
								}
								if( esc_attr($newarr['arfmainfieldsetpadding_4']) != '' ){
									$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_4'].'px';
								}else{
									$arfmainfieldsetpadding_value .= '0px';	
								}
								?>	
                                <input type="hidden" name="arfmfsp" style="width:160px;" id="arfmainfieldsetpadding" class="txtxbox_widget arf_float_right" value="<?php echo $arfmainfieldsetpadding_value; ?>" size="4" />
            
                            </div>
                            
                            
                            
                            
                            <div class="clear" style="margin-top:10px;">
                            	<div>
                                	<?php
										if(is_rtl())
										{
											$frm_title_desc_show = 'float:right;width:160px;';
											$frm_title_desc_check = 'float:left;margin-top:10px;';
										}
										else
										{
											$frm_title_desc_show = 'float:left;width:140px;';
											$frm_title_desc_check = '';
										}
									?>
                                    <div class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_title_desc_show; ?>"><?php _e('Form title & description', 'ARForms') ?></div>
                                    
                                    <div style=" <?php echo $frm_title_desc_check; ?>"><label><span>HIDE&nbsp;</span></label><input type="checkbox" class="js-switch" name="options[display_title_form]" id="display_title_form" <?php if($values_nw['display_title_form']=='1'){ echo 'checked="checked"'; }?> onchange="change_form_title();" value="<?php echo $values_nw['display_title_form']; ?>" /><label><span>&nbsp;SHOW</span></label>
                                    </div>
                                </div>                            
                            </div>
                                
                            <input type="hidden" id="temp_display_title_form" value="1" /> 
            				<div id="form_title_style_div" <?php if($values_nw['display_title_form']=='0'){ echo 'style="display:none;"'; }?>> 
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$frm_title_lbl = 'float:left;width:100%;text-align:right;';
										$frm_title_col_lbl = 'float:right;width:126px;text-align:right;';
										$frm_title_cls = 'arf_float_left';
										$frm_title_css = '';
									}
									else
									{
										$frm_title_lbl = 'width:100%;';
										$frm_title_col_lbl = 'width:126px;';
										$frm_title_cls = 'arf_float_left';
										$frm_title_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading" style=" <?php echo $frm_title_lbl; ?>"><?php _e('Form Title', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield" >
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $frm_title_col_lbl; ?>"><?php _e('Title Color', 'ARForms') ?></label>
            					
                                <div class=" <?php echo $frm_title_cls; ?>" style=" <?php echo $frm_title_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfformtitlecolor" style="background:<?php echo esc_attr($newarr['arfmainformtitlecolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="arfftc" style="width:100px;" id="arfformtitlecolor" class="hex txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainformtitlecolorsetting']) ?>" />
                                </div>
            
                            </div>
                            
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            					<?php
									if(is_rtl())
									{
										$frm_title_txt_align_btn = 'float:right;margin-right:75px;';
									}
									else
									{
										$frm_title_txt_align_btn = 'float:left;margin-left:75px;';
									}
								?>
                                <label class="lblsubheading sublblheading"><?php _e('Text Align', 'ARForms') ?></label>
            
                                <div class="sltstandard1"style=" <?php echo $frm_title_txt_align_btn; ?>">
                                    <div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" class="toggle-btn left <?php if($newarr['arfformtitlealign']=="left"){ echo "success"; }?>"><input type="radio" name="arffta" class="visuallyhidden" value="left" <?php checked($newarr['arfformtitlealign'], 'left') ?> /><?php _e('Left','ARForms');?></label><label onclick="" class="toggle-btn center <?php if($newarr['arfformtitlealign']=="center"){ echo "success"; }?>"><input type="radio" name="arffta"  class="visuallyhidden" value="center" <?php checked($newarr['arfformtitlealign'], 'center') ?> /><?php _e('Center','ARForms');?></label><label onclick="" class="toggle-btn right <?php if($newarr['arfformtitlealign']=="right"){ echo "success"; }?>"><input  class="visuallyhidden" type="radio" name="arffta" value="right" <?php checked($newarr['arfformtitlealign'], 'right') ?> /><?php _e('Right','ARForms');?></label>
                                    </div>
								</div>    	
                            </div>
<div class="field-group field-group-border clearfix" style="margin-top:0px;">
            
                                <label class="lblsubheading" style="width:100%"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            <?php
							 $newarr['check_weight_form_title'] = isset($newarr['check_weight_form_title']) ? $newarr['check_weight_form_title'] : 'normal'; 	
							 $label_font_weight = ""; if($newarr['check_weight_form_title']!="normal"){ $label_font_weight = ", ".$newarr['check_weight_form_title']; }
							 ?>
                             <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style="margin-left:23px;">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showtitlefontsettingpopup" onclick="arfshowformsettingpopup('titlefontsettingpopup')"><?php echo $newarr['arftitlefontfamily'].", ".$newarr['form_title_font_size']."px ".$label_font_weight;?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('titlefontsettingpopup')" /></div>
                                    <div id="titlefontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style="float:right">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('titlefontsettingpopup')" type="button" style="margin-top:-12px; margin-right:3px;">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            
            
                                                <div class="lblsubheading" style="width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <div class="sltstandard2" style="float:right; margin-left:70px;  margin-bottom:10px; position:absolute;">
												  <?php $get_googlefonts_data = $arformcontroller->get_arf_google_fonts(); ?>
                                                  <input id="arftitlefontsetting" name="arftff" value="<?php echo $newarr['arftitlefontfamily'];?>" type="hidden" onChange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arftff" data-id="arftitlefontsetting" style="width:180px;">
                                                    <dt><span><?php echo $newarr['arftitlefontfamily'];?></span>
                                                      <input value="<?php echo $newarr['arftitlefontfamily'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arftitlefontsetting">
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
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                            </div>
                                        	<div class="field-group clearfix subfield" style="margin-top:10px;">
            									<div class="lblsubheading" style="width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;"><?php _e('Style', 'ARForms') ?></div>
                            
                                                <div class="sltstandard1" style="float:right; margin-left:70px; margin-bottom:10px; position:absolute;">
                                                  <input id="arfformtitleweightsetting" name="arfftws" value="<?php echo $newarr['check_weight_form_title'];?>" type="hidden" onchange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfftws" data-id="arfformtitleweightsetting" style="width:80px;">
                                                    <dt><span><?php echo __($newarr['check_weight_form_title'], 'ARForms');?></span>
                                                      <input value="<?php echo __($newarr['check_weight_form_title'], 'ARForms');?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfformtitleweightsetting">
                                                        <li class="arf_selectbox_option" data-value="normal" data-label="<?php _e('normal', 'ARForms');?>"><?php _e('normal', 'ARForms');?></li>
                                                        <li class="arf_selectbox_option" data-value="bold" data-label="<?php _e('bold', 'ARForms') ?>"><?php _e('bold', 'ARForms') ?></li>
                                                        <li class="arf_selectbox_option" data-value="italic" data-label="<?php _e('italic', 'ARForms') ?>"><?php _e('italic', 'ARForms') ?></li>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                                
                                                          
                            
                                            </div>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            									<div class="lblsubheading" style="width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;"><?php _e('Size', 'ARForms') ?></div>
                                                <div style="margin-left:70px; margin-bottom:10px;">
                                                    
                                                    <div class="sltstandard1" style="float:left; position:absolute;">
                                                      <input id="arfformtitlefontsizesetting" name="arfftfss" value="<?php echo $newarr['form_title_font_size'];?>" type="hidden" onchange="Changefontsettinghtml('titlefontsettingpopup','arftitlefontsetting','arfformtitleweightsetting','arfformtitlefontsizesetting');">
                                                      <dl class="arf_selectbox" data-name="arfftfss" data-id="arfformtitlefontsizesetting" style="width:80px;">
                                                        <dt><span><?php echo $newarr['form_title_font_size'];?></span>
                                                          <input value="<?php echo $newarr['form_title_font_size'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                          <i class="fa fa-caret-down fa-lg"></i></dt>
                                                        <dd>
                                                          <ul style="display: none;" data-id="arfformtitlefontsizesetting">
                                                            <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                          </ul>
                                                        </dd>
                                                      </dl>
                                                    </div>
                                                    <div class="arf_px" style="float:right; margin-right: 90px; padding-top:5px;"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$frm_title_margin_btn = 'float:left;margin-left:-20px;';
										$frm_title_margin_top = 'float:right;';
										$frm_title_margin_btm = $frm_title_margin_lft = $frm_title_margin_rgt = 'float:right;margin-right:8px;';
										$frm_title_margin_px = 'float:right;margin-right:6px;padding-top:5px;';
									}
									else
									{
										$frm_title_margin_btn = 'float:right;margin-right:-28px;';
										$frm_title_margin_top = 'float:left;';
										$frm_title_margin_btm = $frm_title_margin_lft = $frm_title_margin_rgt = 'float:left;margin-left:8px;';
										$frm_title_margin_px = 'float:left;margin-left:6px;padding-top:5px;';
									}
								?>
                                <label class="lblsubheading sublblheading" style="width:45px;"><?php _e('Margin', 'ARForms') ?></label>
            
            					<div style=" <?php echo $frm_title_margin_btn; ?>">
            						<div style=" <?php echo $frm_title_margin_top; ?>"><input type="text" name="arfformtitlepaddingsetting_1" id="arfformtitlepaddingsetting_1" onchange="arf_change_field_padding('arfformtitlepaddingsetting');" value="<?php echo esc_attr($newarr['arfmainformtitlepaddingsetting_1']); ?>" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3	px;"><?php _e('Top', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $frm_title_margin_rgt; ?>"><input type="text" name="arfformtitlepaddingsetting_2" id="arfformtitlepaddingsetting_2" value="<?php echo esc_attr($newarr['arfmainformtitlepaddingsetting_2']); ?>" onchange="arf_change_field_padding('arfformtitlepaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:1px;"><?php _e('Right', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $frm_title_margin_btm; ?>"><input type="text" name="arfformtitlepaddingsetting_3" id="arfformtitlepaddingsetting_3" value="<?php echo esc_attr($newarr['arfmainformtitlepaddingsetting_3']); ?>" onchange="arf_change_field_padding('arfformtitlepaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px; margin-left:3px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Bottom', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $frm_title_margin_lft; ?>"><input type="text" name="arfformtitlepaddingsetting_4" id="arfformtitlepaddingsetting_4" value="<?php echo esc_attr($newarr['arfmainformtitlepaddingsetting_4']); ?>" onchange="arf_change_field_padding('arfformtitlepaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Left', 'ARForms');?></span></div>&nbsp;
                                &nbsp;<span class="arf_px" style=" <?php echo $frm_title_margin_px; ?>"><?php _e('px', 'ARForms') ?></span>
                                </div>
                                <?php 
								$arfformtitlepaddingsetting_value = '';
								
								if( esc_attr($newarr['arfmainformtitlepaddingsetting_1']) != '' ){
									$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_1'].'px ';
								}else{
									$arfformtitlepaddingsetting_value .= '0px ';
								}
								if( esc_attr($newarr['arfmainformtitlepaddingsetting_2']) != '' ){
									$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_2'].'px ';
								}else{
									$arfformtitlepaddingsetting_value .= '0px ';
								}					
								if( esc_attr($newarr['arfmainformtitlepaddingsetting_3']) != '' ){
									$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_3'].'px ';
								}else{
									$arfformtitlepaddingsetting_value .= '0px ';
								}
								if( esc_attr($newarr['arfmainformtitlepaddingsetting_4']) != '' ){
									$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_4'].'px';
								}else{
									$arfformtitlepaddingsetting_value .= '0px';
								}
								?>	
                                <input type="hidden" name="arfftps" style="width:100px;" id="arfformtitlepaddingsetting" class="txtxbox_widget" value="<?php echo $arfformtitlepaddingsetting_value; ?>" />
                            </div>
                            </div>
                            <div class="clear widget_bg_bottom"></div>
            				
                            <?php 
							$is_pagebreak_form = true;
							if (isset($values['fields']) && !empty($values['fields'])){
								foreach($values['fields'] as $field){
									if( $field['type'] == 'break' )
									{
										if( $field['page_break_type'] == 'survey' ){
											$is_pagebreak_form = false;				
										}						
										break;	 
									}
								}
							}							
							?>
                            
                            <!-- arf_pagebreak_style start -->
                            <div id="arf_pagebreak_style" style=" <?php if( ! $is_pagebreak_form ){ echo 'display:none;';}?>">
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:10px;">
                                <?php
								  	if(is_rtl())
									{
										$frm_pg_brk_tab_bg_color = 'float:right;margin-right:0px;width:100%;';
										$frm_pg_brk_active_main = 'float:right;width:100%;margin-left:0px;clear:none;';
										$frm_pg_brk_active_lbl = 'width:126px;padding-left:0px;margin-right:49px;';
										$frm_pg_brk_cls = 'arf_float_right';
										$frm_pg_brk_active_css = '';
										$frm_pg_brk_inactv_main = 'float:right;clear:left;width:100%;';
										$frm_pg_brk_inactv_lbl = 'width:120px;margin-right:-35px;text-align:left;';
										$frm_pg_brk_inactv_css = 'float:right';
									}
									else
									{
										$frm_pg_brk_tab_bg_color = 'width:100%;';
										$frm_pg_brk_active_main = 'float:left;width:100%;clear:none;';
										$frm_pg_brk_active_lbl = 'width:126px;';
										$frm_pg_brk_cls = 'arf_float_left';
										$frm_pg_brk_active_css = 'float:left;';
										$frm_pg_brk_inactv_main = 'float:left;clear:right;width:100%;';
										$frm_pg_brk_inactv_lbl = 'width:126px;';
										$frm_pg_brk_inactv_css = 'float:left;';
									}
								  ?>     
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_pg_brk_tab_bg_color; ?>"><?php _e('Page Break Tab Background Color', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $frm_pg_brk_active_main; ?>">
                                
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_active_lbl; ?>"><?php _e('Active', 'ARForms') ?></label>
                                <div class="<?php echo $frm_pg_brk_cls; ?>" style=" <?php echo $frm_pg_brk_active_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bg_color_pg_break" style="background:<?php echo esc_attr($newarr['bg_color_pg_break']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                               		<input type="hidden" name="arffbcpb" id="frm_bg_color_pg_break" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['bg_color_pg_break']) ?>" style="width:100px;" />
                                </div>
                                
                            </div>
                             
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px; <?php echo $frm_pg_brk_inactv_main; ?>">
                                
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_inactv_lbl; ?>"><?php _e('Inactive', 'ARForms') ?></label>
                                <div class=" <?php echo $frm_pg_brk_cls ?>" style=" <?php echo $frm_pg_brk_inactv_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bg_inavtive_color_pg_break" style="background:<?php echo esc_attr($newarr['bg_inavtive_color_pg_break']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="arfbicpb" id="frm_bg_inavtive_color_pg_break" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['bg_inavtive_color_pg_break']) ?>" style="width:100px;" />
                                </div>
                            </div>
                            
                            <div style="clear:both; height:1px;">&nbsp;</div>
                            <?php
								if(is_rtl())
								{
									$frm_pg_brk_tab_txt_col_lbl = 'float:right;margin-right:0px;width:126px;';
									$frm_pg_brk_tab_txt_col_cls = 'arf_float_right';
									$frm_pg_brk_tab_txt_col_css = 'float:right;';
								}
								else
								{
									$frm_pg_brk_tab_txt_col_lbl = 'width:126px;';
									$frm_pg_brk_tab_txt_col_cls = 'arf_float_left';
									$frm_pg_brk_tab_txt_col_css = 'float:left;';
								}
							?>
                            <div class="field-group field-group-border clearfix subfield widget_bg_bottom" style="margin-top:10px;">
            				
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_tab_txt_col_lbl; ?>"><?php _e('Text Color', 'ARForms') ?></label>
            					<div class=" <?php echo $frm_pg_brk_tab_txt_col_cls; ?>" style=" <?php echo $frm_pg_brk_tab_txt_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_text_color_pg_break" style="background:<?php echo esc_attr($newarr['text_color_pg_break']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfftcpb" id="frm_text_color_pg_break" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['text_color_pg_break']) ?>" style="width:100px;" />
                                </div>
            				
                            </div>
							
                            </div>
                            <!-- arf_pagebreak_style end -->
                            
                            
                            <!-- arf_surveypage_style start -->
                            <div id="arf_surveypage_style" style=" <?php if( $is_pagebreak_form ){ echo 'display:none;';}?>">
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:10px;">
                                <?php
								  	if(is_rtl())
									{
										$frm_pg_brk_tab_bg_color = 'float:right;margin-right:0px;width:100%;';
										$frm_pg_brk_active_main = 'float:right;width:100%;margin-left:0px;clear:none;';
										$frm_pg_brk_active_lbl = 'width:126px;padding-left:0px;margin-right:49px;';
										$frm_pg_brk_cls = 'arf_float_right';
										$frm_pg_brk_active_css = '';
										$frm_pg_brk_inactv_main = 'float:right;clear:left;width:100%;';
										$frm_pg_brk_inactv_lbl = 'width:120px;margin-right:-35px;text-align:left;';
										$frm_pg_brk_inactv_css = 'float:right';
									}
									else
									{
										$frm_pg_brk_tab_bg_color = 'width:100%;';
										$frm_pg_brk_active_main = 'float:left;width:100%;clear:none;';
										$frm_pg_brk_active_lbl = 'width:126px;';
										$frm_pg_brk_cls = 'arf_float_left';
										$frm_pg_brk_active_css = 'float:left;';
										$frm_pg_brk_inactv_main = 'float:left;clear:right;width:100%;';
										$frm_pg_brk_inactv_lbl = 'width:126px;';
										$frm_pg_brk_inactv_css = 'float:left;';
									}
								  ?>     
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $frm_pg_brk_tab_bg_color; ?>"><?php _e('Survey Bar Colors', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $frm_pg_brk_active_main; ?>">
                                
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_active_lbl; ?>"><?php _e('Bar Color', 'ARForms') ?></label>
                                <div class="<?php echo $frm_pg_brk_cls; ?>" style=" <?php echo $frm_pg_brk_active_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bar_color_survey" style="background:<?php echo esc_attr(isset($newarr['bar_color_survey'])?$newarr['bar_color_survey']:"") ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                               		<input type="hidden" name="arfbcs" id="frm_bar_color_survey" class="txtxbox_widget hex" value="<?php echo esc_attr(isset($newarr['bar_color_survey'])?$newarr['bar_color_survey']:"") ?>" style="width:100px;" />
                                </div>
                                
                            </div>
                             
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px; <?php echo $frm_pg_brk_inactv_main; ?>">
                                
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_inactv_lbl; ?>"><?php _e('Background', 'ARForms') ?></label>
                                <div class=" <?php echo $frm_pg_brk_cls ?>" style=" <?php echo $frm_pg_brk_inactv_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bg_color_survey" style="background:<?php echo esc_attr(isset($newarr['bg_color_survey'])?$newarr['bg_color_survey']:"") ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="arfbgcs" id="frm_bg_color_survey" class="txtxbox_widget hex" value="<?php echo esc_attr(isset($newarr['bg_color_survey'])?$newarr['bg_color_survey']:"") ?>" style="width:100px;" />
                                </div>
                            </div>
                            
                            <div style="clear:both; height:1px;">&nbsp;</div>
                            <?php
								if(is_rtl())
								{
									$frm_pg_brk_tab_txt_col_lbl = 'float:right;margin-right:0px;width:126px;';
									$frm_pg_brk_tab_txt_col_cls = 'arf_float_right';
									$frm_pg_brk_tab_txt_col_css = 'float:right;';
								}
								else
								{
									$frm_pg_brk_tab_txt_col_lbl = 'width:126px;';
									$frm_pg_brk_tab_txt_col_cls = 'arf_float_left';
									$frm_pg_brk_tab_txt_col_css = 'float:left;';
								}
							?>
                            <div class="field-group field-group-border clearfix subfield widget_bg_bottom" style="margin-top:10px;">
            				
                                <label class="background lblsubheading sublblheading" style=" <?php echo $frm_pg_brk_tab_txt_col_lbl; ?>"><?php _e('Title Color', 'ARForms') ?></label>
            					<div class=" <?php echo $frm_pg_brk_tab_txt_col_cls; ?>" style=" <?php echo $frm_pg_brk_tab_txt_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_text_color_survey" style="background:<?php echo esc_attr(isset($newarr['text_color_survey'])?$newarr['text_color_survey']:"") ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfftcs" id="frm_text_color_survey" class="txtxbox_widget hex" value="<?php echo esc_attr(isset($newarr['text_color_survey'])?$newarr['text_color_survey']:"") ?>" style="width:100px;" />
                                </div>
            				
                            </div>
							
                            </div>
                            <!-- arf_surveypage_style end -->
                            
                            
                            <div class="field-group clearfix" style="margin-top:18px;">
            					<?php
									if(is_rtl())
									{
										$modal_win_opct_lbl = 'float:right;width:170px;margin-right:-20px;';
										$modal_win_opct_cls = 'arf_float_left';
										$modal_win_opct_slider_lbl_main = 'width:150px;display:inline;float:left;';
										$modal_win_opct_slider_lbl_start = 'float:left;';
										$modal_win_opct_slider_lbl_end = 'float:right;';
									}
									else
									{
										$modal_win_opct_lbl = 'width:170px;';
										$modal_win_opct_cls = 'arf_float_left';
										$modal_win_opct_slider_lbl_main = 'width:150px;display:inline;';
										$modal_win_opct_slider_lbl_start = 'float:left;margin-left:40px;';
										$modal_win_opct_slider_lbl_end = 'float:left;margin-left:130px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style="width: 102px; padding-right:0; margin-right:0;"><?php _e('Window Opacity', 'ARForms') ?> &nbsp;&nbsp;&nbsp;</label>
                            	 
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right" style="margin-right:5px;">
                                	 <input type="text" name="arfmainform_opacity" id="arfmainform_opacity" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainform_opacity']) ?>" style="width:142px;" />
            					</div>
								<?php } else { ?>
                                 
                                <div style="float:left;margin-top:7px;">
                                	<input id="arfmainform_opacity_exs" class="arf_slider" data-slider-id='arfmainform_opacity_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo ( esc_attr($newarr['arfmainform_opacity']) * 10 ) ?>" />
                                </div>
                                <br />
                                <div style=" <?php echo $modal_win_opct_slider_lbl_main; ?>">
                                	<div style=" <?php echo $modal_win_opct_slider_lbl_start; ?>"><?php _e('0', 'ARForms') ?></div>
                                    <div style=" <?php echo $modal_win_opct_slider_lbl_end; ?>"><?php _e('1', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfmainform_opacity" id="arfmainform_opacity" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfmainform_opacity']) ?>" style="width:100px;" />
                                <?php } ?>
                                
                            </div>
                            
                            <div style="height:10px;">&nbsp;</div>
                            
                            <div class="clear widget_bg_bottom"></div>
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$sc_pad_margin_btn = 'float:left;margin-left:-20px;';
										$sc_pad_margin_top = 'float:right;';
										$sc_pad_margin_btm = $sc_pad_margin_lft = $sc_pad_margin_rgt = 'float:right;margin-right:8px;';
										$sc_pad_margin_px = 'float:right;margin-right:6px;padding-top:5px;';
									}
									else
									{
										$sc_pad_margin_btn = 'float:right;margin-right:-28px;';
										$sc_pad_margin_top = 'float:left;';
										$sc_pad_margin_btm = $sc_pad_margin_lft = $sc_pad_margin_rgt = 'float:left;margin-left:8px;';
										$sc_pad_margin_px = 'float:left;margin-left:6px;padding-top:5px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style="width: 100px; padding-right:0; margin-right:0;"><?php _e('Section Padding', 'ARForms') ?></label>
            
            					<div style=" <?php echo $sc_pad_margin_btn; ?>">
            						<div style=" <?php echo $sc_pad_margin_top; ?>"><input type="text" name="arfsectionpaddingsetting_1" id="arfsectionpaddingsetting_1" onchange="arf_change_field_padding('arfsectionpaddingsetting');" value="<?php echo esc_attr($newarr['arfsectionpaddingsetting_1']); ?>" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Top', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $sc_pad_margin_rgt; ?>"><input type="text" name="arfsectionpaddingsetting_2" id="arfsectionpaddingsetting_2" value="<?php echo esc_attr($newarr['arfsectionpaddingsetting_2']); ?>" onchange="arf_change_field_padding('arfsectionpaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:1px;"><?php _e('Right', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $sc_pad_margin_btm; ?>"><input type="text" name="arfsectionpaddingsetting_3" id="arfsectionpaddingsetting_3" value="<?php echo esc_attr($newarr['arfsectionpaddingsetting_3']); ?>" onchange="arf_change_field_padding('arfsectionpaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px; margin-left:3px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Bottom', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $sc_pad_margin_lft; ?>"><input type="text" name="arfsectionpaddingsetting_4" id="arfsectionpaddingsetting_4" value="<?php echo esc_attr($newarr['arfsectionpaddingsetting_4']); ?>" onchange="arf_change_field_padding('arfsectionpaddingsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:40px;" /><br /><span class="arf_px" style="padding-left:3px;"><?php _e('Left', 'ARForms');?></span></div>&nbsp;
                                &nbsp;<span class="arf_px" style=" <?php echo $sc_pad_margin_px; ?>"><?php _e('px', 'ARForms') ?></span>
                                </div>
                                <?php 
								$arfsectionpaddingsetting_value = '';
								
								if( esc_attr($newarr['arfsectionpaddingsetting_1']) != '' )
									$arfsectionpaddingsetting_value .= $newarr['arfsectionpaddingsetting_1'].'px ';
								else
									$arfsectionpaddingsetting_value .= '15px ';
								
								if( esc_attr($newarr['arfsectionpaddingsetting_2']) != '' )
									$arfsectionpaddingsetting_value .= $newarr['arfsectionpaddingsetting_2'].'px ';
								else
									$arfsectionpaddingsetting_value .= '15px ';
															
								if( esc_attr($newarr['arfsectionpaddingsetting_3']) != '' )
									$arfsectionpaddingsetting_value .= $newarr['arfsectionpaddingsetting_3'].'px ';
								else
									$arfsectionpaddingsetting_value .= '15px ';
								
								if( esc_attr($newarr['arfsectionpaddingsetting_4']) != '' )
									$arfsectionpaddingsetting_value .= $newarr['arfsectionpaddingsetting_4'].'px';
								else
									$arfsectionpaddingsetting_value .= '15px';	
								?>	
                                <input type="hidden" name="arfscps" style="width:100px;" id="arfsectionpaddingsetting" class="txtxbox_widget" value="<?php echo $arfsectionpaddingsetting_value; ?>" />
                            </div>
                            
                            <div style="height:10px;">&nbsp;</div>
                        
                        </div>
            
            
                    </div>
                    <input type="hidden" name="arfformid" value="<?php echo $id;?>" />
                    <div id="tablabelsettings" class="widget clearfix global-font">
            
            
                        <div class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Label Settings', 'ARForms') ?></h4></div>
            
            
                        </div>
            
            
                        <div class="widget-inside">
            				
                            
                            <?php
								if(is_rtl())
								{
									$lbl_position_lbl = 'float:right;width:100%;text-align:right;';
									$lbl_position_opt = 'float:left;margin-right:72px;';
									$lbl_txt_align_lbl = 'float:right;width:130px;text-align:right;';
									$lbl_txt_align_opt = 'float:right;margin-right:0px;';
								}
								else
								{
									$lbl_position_lbl = 'width:100%;';
									$lbl_position_opt = 'float:left;margin-left:72px;';
									$lbl_txt_align_lbl = 'width:130px;';
									$lbl_txt_align_opt = 'float:left;margin-right:0px;';
								}
							?>
                            <div class="field-group clearfix clear widget_bg_bottom">

                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_position_lbl; ?>"><?php _e('Label Position', 'ARForms') ?></label>
            
                                <div class="sltstandard1" style=" <?php echo $lbl_position_opt; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                    	<?php foreach (array('top' => __('Top', 'ARForms'), 'left' => __('Left', 'ARForms'), 'right' => __('Right', 'ARForms')) as $pos => $pos_label){ ?>
                                        	<label onclick="" class="toggle-btn <?php echo $pos."pos";?> <?php if($newarr['position']==$pos){ echo "success"; }?>"><input type="radio" name="arfmps" class="visuallyhidden" onchange="frmSetPosClass('<?php echo $pos; ?>')" value="<?php echo $pos ?>" <?php checked($newarr['position'], $pos) ?> /><?php echo $pos_label ?></label>	
                                        <?php }?>
                                    </div>
                                </div>
            
                            </div>
                            
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_txt_align_lbl; ?>"><?php _e('Text Align', 'ARForms') ?></label>
            
                                
                                <div style=" <?php echo $lbl_txt_align_opt; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" class="toggle-btn left <?php if($newarr['align']=="left"){ echo "success"; }?>"><input type="radio" name="arffrma" id="frm_align" class="visuallyhidden" value="left" <?php checked($newarr['align'], 'left'); ?> /><?php _e('Left', 'ARForms') ?></label><label onclick="" class="toggle-btn right <?php if($newarr['align']=="right"){ echo "success"; }?>"><input type="radio" name="arffrma" id="frm_align_2"  class="visuallyhidden" value="right" <?php checked($newarr['align'], 'right'); ?> /><?php _e('Right', 'ARForms') ?></label>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px; padding-bottom:10px;">
            					<?php
									if(is_rtl())
									{
										$lbl_width_lbl = 'float:right;text-align:right;margin-right:8px;width:132px;';
										$lbl_width_cls = 'arf_float_right';
									}
									else
									{
										$lbl_width_lbl = 'width:132px;';
										$lbl_width_cls = 'arf_float_left';
									}
								?>
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_width_lbl; ?>"><?php _e('Label Width', 'ARForms') ?></label>
            
                                
                                <div class=" <?php echo $lbl_width_cls; ?>">
                                	<input type="text" name="arfmws" class="txtxbox_widget" style="width:142px;" id="arfmainformwidthsetting" value="<?php echo esc_attr($newarr["width"]) ?>"  size="5" />
                                    <input type="hidden" name="arfmwu" id="arfmainwidthunit" value="px" /> 
                                    &nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
                                
                            </div>
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$lbl_txt_col_lbl = 'float:right;width:133px;';
										$lbl_txt_col_cls = 'arf_float_right';
										$lbl_txt_col_css = 'float:right;';
									}
									else
									{
										$lbl_txt_col_lbl = 'width:133px;';
										$lbl_txt_col_cls = 'arf_float_left';
										$lbl_txt_col_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_txt_col_lbl; ?>"><?php _e('Text Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $lbl_txt_col_cls; ?>" style=" <?php echo $lbl_txt_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arflabelcolorsetting" style="background:<?php echo esc_attr($newarr['label_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arflcs" id="arflabelcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['label_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:0px;">
            					<?php
									if(is_rtl())
									{
										$font_setting_lbl = 'float:right;width:100%;text-align:right;';
										$font_select_opt = 'float:right;margin-right:24px;';
										$font_popup_clos = 'float:left;';
										$font_popup_clos_btn = 'float:left;margin-right:0px;margin-top:0px;';
									}
									else
									{
										$font_setting_lbl = 'width:100%;';
										$font_select_opt = 'margin-left:24px;';
										$font_popup_clos = 'float:right;';
										$font_popup_clos_btn = 'margin-top:-12px; margin-right:3px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $font_setting_lbl; ?>"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            <?php $label_font_weight = ""; if($newarr['weight']!="normal"){ $label_font_weight = ", ".$newarr['weight']; }?>
                            <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style=" <?php echo $font_select_opt; ?>">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showlabelfontsettingpopup" onclick="arfshowformsettingpopup('labelfontsettingpopup')"><?php echo $newarr['font'].", ".$newarr['font_size']."px ".$label_font_weight;?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('labelfontsettingpopup')" /></div>
                                    <div id="labelfontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style=" <?php echo $font_popup_clos; ?>">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('labelfontsettingpopup')" type="button" style=" <?php echo $font_popup_clos_btn; ?>">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            
            									<?php
													if(is_rtl())
													{
														$font_family_lbl = 'float:right;width:50px;padding-left:10px;padding-top:7px;height:20px;';
														$font_family_opt = 'float:left;margin-right:70px;margin-bottom:10px;position:absolute;';
													}
													else
													{
														$font_family_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
														$font_family_opt = 'float:right; margin-left:70px;  margin-bottom:10px; position:absolute;';
													}
												?>
                                                <div class="lblsubheading" style=" <?php echo $font_family_lbl; ?>"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <div class="sltstandard2" style=" <?php echo $font_family_opt; ?>">
												  <?php $get_googlefonts_data = $arformcontroller->get_arf_google_fonts(); ?>
                                                  <input id="arfmainfontsetting" name="arfmfs" value="<?php echo $newarr['font'];?>" type="hidden" onchange="Changefontsettinghtml('labelfontsettingpopup','arfmainfontsetting','arfmainfontweightsetting','arffontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfmfs" data-id="arfmainfontsetting" style="width:180px;">
                                                    <dt><span><?php echo $newarr['font'];?></span>
                                                      <input value="<?php echo $newarr['font'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfmainfontsetting">
                                                        <ol class="arp_selectbox_group_label">
                                                          Default Fonts
                                                        </ol>
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
                                                        <ol class="arp_selectbox_group_label">
                                                          Google Fonts
                                                        </ol>
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                            </div>
                                        	<div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
													if(is_rtl())
													{
														$font_style_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
														$font_style_opt = 'float:left; margin-right:70px; margin-bottom:10px; position:absolute;';
													}
													else
													{
														$font_style_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
														$font_style_opt = 'float:right; margin-left:70px; margin-bottom:10px; position:absolute;';
													}
												?>
            									<div class="lblsubheading" style=" <?php echo $font_style_lbl; ?>"><?php _e('Style', 'ARForms') ?></div>
                            
                                                <div class="sltstandard1" style=" <?php echo $font_style_opt; ?>">
                                                  <input id="arfmainfontweightsetting" name="arfmfws" value="<?php echo $newarr['weight'];?>" type="hidden" onChange="Changefontsettinghtml('labelfontsettingpopup','arfmainfontsetting','arfmainfontweightsetting','arffontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfmfws" data-id="arfmainfontweightsetting" style="width:80px;">
                                                    <dt><span><?php echo __($newarr['weight'], 'ARForms');?></span>
                                                      <input value="<?php echo __($newarr['weight'], 'ARForms');?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfmainfontweightsetting">
                                                        <li class="arf_selectbox_option" data-value="normal" data-label="<?php _e('normal', 'ARForms');?>"><?php _e('normal', 'ARForms');?></li>
                                                        <li class="arf_selectbox_option" data-value="bold" data-label="<?php _e('bold', 'ARForms') ?>"><?php _e('bold', 'ARForms') ?></li>
                                                        <li class="arf_selectbox_option" data-value="italic" data-label="<?php _e('italic', 'ARForms') ?>"><?php _e('italic', 'ARForms') ?></li>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                                
                                                          
                            
                                            </div>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
												if(is_rtl())
												{
													$font_size_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
													$font_size_opt = 'float:right;position:absolute;';
													$font_px_lbl = 'float:left;padding-top:5px;margin-left:85px;';
													$font_size_opt_main = 'margin-right:70px;margin-bottom:10px;';
												}
												else
												{
													$font_size_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
													$font_size_opt = 'float:left;position:absolute';
													$font_px_lbl = 'float:right; margin-right: 90px; padding-top:5px;';
													$font_size_opt_main = 'margin-left:70px;margin-bottom:10px;';
												}
											?>
            									<div class="lblsubheading" style=" <?php echo $font_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></div>
                                                <div style=" <?php echo $font_size_opt_main; ?>">
                                                    <div class="sltstandard1" style=" <?php echo $font_size_opt; ?>">
                                                    
                                                      <input id="arffontsizesetting" name="arffss" value="<?php echo $newarr['font_size'];?>" type="hidden" onChange="Changefontsettinghtml('labelfontsettingpopup','arfmainfontsetting','arfmainfontweightsetting','arffontsizesetting');">
                                                      <dl class="arf_selectbox" data-name="arffss" data-id="arffontsizesetting" style="width:80px;">
                                                        <dt><span><?php echo $newarr['font_size'];?></span>
                                                          <input value="<?php echo $newarr['font_size'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                          <i class="fa fa-caret-down fa-lg"></i></dt>
                                                        <dd>
                                                          <ul style="display: none;" data-id="arffontsizesetting">
                                                            <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                          </ul>
                                                        </dd>
                                                      </dl>
                                                    
                                                    </div>
                                                    <div class="arf_px" style=" <?php echo $font_px_lbl; ?>"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            
                            
                            <div class="widget_bg_bottom" style="padding-bottom:-4px;"></div>
							<div class="clear" style="margin-top:10px;padding-bottom:5px;">
            						<?php
										if(is_rtl())
										{
											$lbl_hide_lbl = 'width:136px; float:right;margin-top:-5px;margin-left:60px;';
										}
										else
										{
											$lbl_hide_lbl = 'width:136px; float:left;';
										}
									?>
                                    <div class="lblsubheading lblsubheadingbold" style=" <?php echo $lbl_hide_lbl; ?>"><?php _e('Hide Labels', 'ARForms') ?></div>
                                    
                                    <div><label><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch" name="arfhl" id="arfhidelabels" value="<?php echo $newarr['hide_labels']!=""?$newarr['hide_labels']:0;?>" onchange="frmSetPosClassHide()"  <?php if($newarr['hide_labels']=='1'){ echo 'checked="checked"'; }?> /><label><span>&nbsp;YES</span></label>
                                     </div>   	
            
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:-4px;"></div>
                             
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$field_desc_set_lbl = 'width:100%;float:left;margin-right:0px;text-align:right;';
										$field_desc_size_lbl = 'width:120px;margin-right:0;text-align:right;';
										$field_desc_size_css = '';
										$field_desc_size_opt = 'float:right;';
									}
									else
									{
										$field_desc_set_lbl = 'width:100%;';
										$field_desc_size_lbl = 'width:120px;';
										$field_desc_size_css = 'margin-left:0px;';
										$field_desc_size_opt = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $field_desc_set_lbl; ?>"><?php _e('Field description settings', 'ARForms') ?></label> <br />
            
                            </div>
            
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $field_desc_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></label>
                                
                                <div class="arf_float_left" style=" <?php echo $field_desc_size_css; ?>">
                                                    
                                <div class="sltstandard1" style=" <?php echo $field_desc_size_opt; ?>">
                                
                                <input id="arfdescfontsizesetting" name="arfdfss" value="<?php echo $newarr['arfdescfontsizesetting'];?>" type="hidden">
                                                      <dl class="arf_selectbox" data-name="arfdfss" data-id="arfdescfontsizesetting" style="width:80px;">
                                                        <dt><span><?php echo $newarr['arfdescfontsizesetting'];?></span>
                                                          <input value="<?php echo $newarr['arfdescfontsizesetting'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                          <i class="fa fa-caret-down fa-lg"></i></dt>
                                                        <dd>
                                                          <ul style="display: none;" data-id="arfdescfontsizesetting">
                                                            <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                            <?php } ?>
                                                          </ul>
                                                        </dd>
                                                      </dl>
                                
                                </div>
                                &nbsp;<span class="arf_px" style="float:left; margin-left:22px; padding-top:5px;"><?php _e('px', 'ARForms') ?></span>
                                </div>
                            </div>
                            <?php
								if(is_rtl())
								{
									$field_desc_align_lbl = 'width:auto;margin-right:0;text-align:right;';
									$field_desc_align_opt = 'float:right;margin-right:10px;';
								}
								else
								{
									$field_desc_align_lbl = 'width:36px;';
									$field_desc_align_opt = 'float:left;margin-left:10px;';
								}
							?>
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $field_desc_align_lbl; ?>"><?php _e('Align', 'ARForms') ?></label>
            
                                <div class="sltstandard1" style=" <?php echo $field_desc_align_opt; ?>">
                                    <div class="toggle-btn-grp joint-toggle">
                                            <label onclick="" class="toggle-btn left <?php if($newarr['arfdescalighsetting']=="left"){ echo "success"; }?>"><input type="radio" name="arfdas" class="visuallyhidden" value="left" <?php checked($newarr['arfdescalighsetting'], 'left'); ?> /><?php _e('Left', 'ARForms') ?></label><label onclick="" class="toggle-btn center <?php if($newarr['arfdescalighsetting']=="center"){ echo "success"; }?>"><input type="radio" name="arfdas"  class="visuallyhidden" value="center" <?php checked($newarr['arfdescalighsetting'], 'center'); ?> /><?php _e('Center', 'ARForms') ?></label><label onclick="" class="toggle-btn right <?php if($newarr['arfdescalighsetting']=="right"){ echo "success"; }?>"><input type="radio" name="arfdas" class="visuallyhidden" value="right" <?php checked($newarr['arfdescalighsetting'], 'right'); ?> /><?php _e('Right', 'ARForms') ?></label>
                                    </div>
                                
                                </div>    	
                                 
                                <div style="height:10px; clear:both;">&nbsp;</div>    
                            </div>
                            
                           	
                            
                            
                            
                            
            
                        </div>
            
            
                    </div>
            
                    <div id="tabinputfieldsettings" class="widget clearfix global-font">
            
            
                        <div class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Input Field Settings', 'ARForms') ?></h4></div>
            
            
                        </div>
            
            
                        <div class="widget-inside" style="visibility:visible;">
                            
                            
                            <input type="hidden" name="arfmf" value="<?php echo $id; ?>" id="arfmainformid" />
            				
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
                            
            					<?php
									if(is_rtl())
									{
										$field_width_lbl = 'float:right;width:135px;text-align:right;';
										$field_width_cls = 'arf_float_right';
										$field_width_opt = 'float:right;padding-right:7px;';
										$field_width_txt = 'float:right;width:85px;';
									}
									else
									{
										$field_width_lbl = 'width:135px;';
										$field_width_cls = 'arf_float_left';
										$field_width_opt = 'float:left;padding-left:7px;';
										$field_width_txt = 'float:left;width:85px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $field_width_lbl; ?>"><?php _e('Field Width', 'ARForms') ?></label>
            
            					<div class=" <?php echo $field_width_cls; ?>" >
                                
                                    <input type="text" name="arfmfiws" id="arfmainfieldwidthsetting" onchange="change_auto_width();" style=" <?php echo $field_width_txt; ?>" class="txtxbox_widget" value="<?php echo esc_attr($newarr['field_width']) ?>"  size="5" />
                                    
                                    
                                    <div class="sltstandard1" style=" <?php echo $field_width_opt; ?>">
                                    
                                    <?php /*?><select name="arffiu" onchange="change_date_format();" id="arffieldunit" style="width:53px;" data-width='53px'>
                                            <option value="px" <?php selected($newarr['field_width_unit'], 'px') ?>><?php _e('px', 'ARForms') ?></option>
                                            <option value="%" <?php selected($newarr['field_width_unit'], '%') ?>><?php _e('%', 'ARForms') ?></option>
                                    </select><?php */?>
                                    
                                    <input id="arffieldunit" name="arffiu" value="<?php echo $newarr['field_width_unit'];?>" type="hidden" onchange="change_date_format();" >
                                        <dl class="arf_selectbox" data-name="arffiu" data-id="arffieldunit" style="width:53px;">
                                          <dt><span><?php echo $newarr['field_width_unit'];?></span>
                                            <input value="<?php echo $newarr['field_width_unit'];?>" style="display:none;width:41px;" class="arf_autocomplete" type="text">
                                            <i class="fa fa-caret-down fa-lg"></i></dt>
                                          <dd>
                                            <ul style="display: none;" data-id="arffieldunit">
                                              <li class="arf_selectbox_option" data-value="<?php _e('px', 'ARForms') ?>" data-label="<?php _e('px', 'ARForms') ?>"><?php _e('px', 'ARForms') ?></li>
                                              <li class="arf_selectbox_option" data-value="<?php _e('%', 'ARForms') ?>" data-label="<?php _e('%', 'ARForms') ?>"><?php _e('%', 'ARForms') ?></li>
                                            </ul>
                                          </dd>
                                        </dl>
                                    
                                    </div>
            						
                                </div>    
            
                            </div>
                            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:7px;">
            
            					<?php
									if(is_rtl())
									{
										$text_dir_lbl = 'float:right;text-align:right;width:100%;';
										$text_dir_btn = 'float:left;padding-left:0px;';
										$text_dir_btn_sub = 'float:right;margin-right:87px;';
									}
									else
									{
										$text_dir_lbl = 'width:100%;';
										$text_dir_btn = 'padding-left:0px;';
										$text_dir_btn_sub = 'float:left;margin-left:87px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $text_dir_lbl; ?>"><?php _e('Text Direction', 'ARForms') ?></label>
            
                                <div style=" <?php echo $text_dir_btn; ?>">
                                	<div class="toggle-btn-grp joint-toggle" style=" <?php echo $text_dir_btn_sub;?>">
                                            <label onclick="" class="toggle-btn-large <?php if($newarr['text_direction']=="1"){ echo "success"; }?>"><input type="radio" name="arftds" class="visuallyhidden" id="txt_dir_1" value="1" <?php checked($newarr['text_direction'], 1); ?> /><?php _e('Left to Right', 'ARForms') ?></label><label onclick="" class="toggle-btn-large <?php if($newarr['text_direction']=="0"){ echo "success"; }?>"><input type="radio" name="arftds" class="visuallyhidden" value="0"  id="txt_dir_2" <?php checked($newarr['text_direction'], 0); ?> /><?php _e('Right to Left', 'ARForms') ?></label>
                                    </div>
                                </div>
            
                            </div>
                            
                             
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$font_setting_lbl = 'float:right;text-align:right;width:100%;';
										$font_select_box = 'float:right;margin-right:25px;';
										$font_select_close = 'float:left;';
										$font_select_close_btn = 'margin-top:-12px;margin-right:3px;';
									}
									else
									{
										$font_setting_lbl = 'width:100%';
										$font_select_box = 'float:left;margin-left:25px;';
										$font_select_close = 'float:right;';
										$font_select_close_btn = 'margin-top:-12px;margin-right:3px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $font_setting_lbl; ?>"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            <?php $input_font_weight_html = ""; if($newarr['check_weight']!="normal"){ $input_font_weight_html = ", ".$newarr['check_weight']; }?>
                            <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style=" <?php echo $font_select_box; ?>">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showinputfontsettingpopup" onclick="arfshowformsettingpopup('inputfontsettingpopup')"><?php echo $newarr['check_font'].", ".$newarr['field_font_size']."px ".$input_font_weight_html;?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('inputfontsettingpopup')" /></div>
                                    <div id="inputfontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style=" <?php echo $font_select_close; ?>">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('inputfontsettingpopup')" type="button" style=" <?php echo $font_select_close_btn; ?>">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            								<?php
												if(is_rtl())
												{
													$font_family_lbl = 'float:right;width:50px;padding-left:10px;padding-top:7px;height:20px;';
													$font_family_opt = 'float:right;margin-right:70px;margin-bottom:10px;position:absolute;';
												}
												else
												{
													$font_family_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
													$font_family_opt = 'float:left;margin-left:70px;margin-bottom:10px;position:absolute;';
												}
											?>
            
                                                <div class="lblsubheading" style=" <?php echo $font_family_lbl; ?>"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <?php /*?><div class="sltstandard2" style=" <?php echo $font_family_opt; ?>">
                                                <select name="arfcbfs" id="arfcheckboxfontsetting" style="width:200px;" data-width='200px' data-size="15" onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
                                                    <optgroup label="Default Fonts">
                                                        <option value="Arial" <?php selected($newarr['check_font'], 'Arial') ?>>Arial</option>
                            
                                                        <option value="Helvetica" <?php selected($newarr['check_font'], 'Helvetica') ?>>Helvetica</option>
                                                        
                                                        <option value="sans-serif" <?php selected($newarr['check_font'], 'sans-serif') ?>>sans-serif</option>
                                                        
                                                        <option value="Lucida Grande" <?php selected($newarr['check_font'], 'Lucida Grande') ?>>Lucida Grande</option>
                                                        
                                                        <option value="Lucida Sans Unicode" <?php selected($newarr['check_font'], 'Lucida Sans Unicode') ?>>Lucida Sans Unicode</option>
                                                        
                                                        <option value="Tahoma" <?php selected($newarr['check_font'], 'Tahoma') ?>>Tahoma</option>
                                                        
                                                        <option value="Times New Roman" <?php selected($newarr['check_font'], 'Times New Roman') ?>>Times New Roman</option>
                                                
                                                        <option value="Courier New" <?php selected($newarr['check_font'], 'Courier New') ?>>Courier New</option>
                                                
                                                        <option value="Verdana" <?php selected($newarr['check_font'], 'Verdana') ?>>Verdana</option>
                                                
                                                        <option value="Geneva" <?php selected($newarr['check_font'], 'Geneva') ?>>Geneva</option>
                                                        
                                                        <option value="Courier" <?php selected($newarr['check_font'], 'Courier') ?>>Courier</option>
                                                        
                                                        <option value="Monospace" <?php selected($newarr['check_font'], 'Monospace') ?>>Monospace</option>
                                                        
                                                        <option value="Times" <?php selected($newarr['check_font'], 'Times') ?>>Times</option>
                                                        
                                                    </optgroup>
                                                    <optgroup label="Google Fonts">
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<option value='".$goglefontsfamily."' ".selected($newarr['check_font'], $goglefontsfamily)." >".$goglefontsfamily."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </optgroup>
                                                    
                                                </select>
                                                
                                                </div><?php */?>
                                                
                                                
                                                <div class="sltstandard2" style=" <?php echo $font_family_opt; ?>">
                                                  <input id="arfcheckboxfontsetting" name="arfcbfs" value="<?php echo $newarr['check_font'];?>" type="hidden" onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfcbfs" data-id="arfcheckboxfontsetting" style="width:180px;">
                                                    <dt><span><?php echo $newarr['check_font'];?></span>
                                                      <input value="<?php echo $newarr['check_font'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfcheckboxfontsetting">
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
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                            </div>
                                        	<div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
												if(is_rtl())
												{
													$font_style_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
													$font_style_opt = 'float:left; margin-right:70px; margin-bottom:10px; position:absolute;';
												}
												else
												{
													$font_style_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
													$font_style_opt = 'float:right; margin-left:70px; margin-bottom:10px; position:absolute;';
												}
											?>
            									<div class="lblsubheading" style=" <?php echo $font_style_lbl; ?>"><?php _e('Style', 'ARForms') ?></div>
                            
                                                <?php /*?><div class="sltstandard1" style=" <?php echo $font_style_opt; ?>">
                                                
                                                <select name="arfcbws" id="arfcheckboxweightsetting" style="width:100px;" data-width='100px' onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
            
            
                                                    <option value="normal" <?php selected($newarr['check_weight'], 'normal') ?>><?php _e('normal', 'ARForms') ?></option>
                        
                                                    <option value="bold" <?php selected($newarr['check_weight'], 'bold') ?>><?php _e('bold', 'ARForms') ?></option>
                                                    
                                                    <option value="italic" <?php selected($newarr['check_weight'], 'italic') ?>><?php _e('italic', 'ARForms') ?></option>
                        
                                                </select>
                                                
                                                </div><?php */?>
                                                
                                                
                                                <div class="sltstandard1" style=" <?php echo $font_style_opt; ?>">
                                                  <input id="arfcheckboxweightsetting" name="arfcbws" value="<?php echo $newarr['check_weight'];?>" type="hidden" onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
                                                  <dl class="arf_selectbox" data-name="arfcbws" data-id="arfcheckboxweightsetting" style="width:80px;">
                                                    <dt><span><?php echo __($newarr['check_weight'], 'ARForms');?></span>
                                                      <input value="<?php echo __($newarr['check_weight'], 'ARForms');?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                                    <dd>
                                                      <ul style="display: none;" data-id="arfcheckboxweightsetting">
                                                        <li class="arf_selectbox_option" data-value="normal" data-label="<?php _e('normal', 'ARForms');?>"><?php _e('normal', 'ARForms');?></li>
                                                        <li class="arf_selectbox_option" data-value="bold" data-label="<?php _e('bold', 'ARForms') ?>"><?php _e('bold', 'ARForms') ?></li>
                                                        <li class="arf_selectbox_option" data-value="italic" data-label="<?php _e('italic', 'ARForms') ?>"><?php _e('italic', 'ARForms') ?></li>
                                                      </ul>
                                                    </dd>
                                                  </dl>
                                                </div>
                                                
                                                          
                            
                                            </div>
                                            <?php
												if(is_rtl())
												{
													$font_size_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
													$font_size_opt_wrap = 'margin-bottom:10px; float:right;';
													$font_size_opt = 'float:right;  margin-bottom:10px; position:absolute;';
													$font_size_px_lbl = 'float:right; margin-right:123px; padding-top:5px;';
												}
												else
												{
													$font_size_lbl = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
													$font_size_opt_wrap = ' margin-bottom:10px;';
													$font_size_opt = 'float:left;  margin-bottom:10px; position:absolute;';
													$font_size_px_lbl = 'float:left; margin-left:100px; margin-right: 0px; padding-top:5px;';
												}
											?>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            									<div class="lblsubheading" style=" <?php echo $font_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></div>
                                                <div style=" <?php echo $font_size_opt_wrap; ?>">
                                                    <div class="sltstandard1" style=" <?php echo $font_style_opt; ?>">
                                                    
                                                    <?php /*?><select name="arfffss" id="arffieldfontsizesetting" style="width:100px;" data-width='100px' data-size='15' onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">	
															<?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['field_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['field_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['field_font_size'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                    </select><?php */?>
                                                    
                                                    <input id="arffieldfontsizesetting" name="arfffss" value="<?php echo $newarr['field_font_size'];?>" type="hidden" onchange="Changefontsettinghtml('inputfontsettingpopup','arfcheckboxfontsetting','arfcheckboxweightsetting','arffieldfontsizesetting');">
                                                    <dl class="arf_selectbox" data-name="arfffss" data-id="arffieldfontsizesetting" style="width:80px;">
                                                      <dt><span><?php echo $newarr['field_font_size'];?></span>
                                                        <input value="<?php echo $newarr['field_font_size'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                      <dd>
                                                        <ul style="display: none;" data-id="arffieldfontsizesetting">
                                                          <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>">
                                                            <?php _e($i, 'ARForms'); ?>
                                                          </li>
                                                          <?php } ?>
                                                          <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>">
                                                            <?php _e($i, 'ARForms'); ?>
                                                          </li>
                                                          <?php } ?>
                                                          <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>">
                                                            <?php _e($i, 'ARForms'); ?>
                                                          </li>
                                                          <?php } ?>
                                                        </ul>
                                                      </dd>
                                                    </dl>

                                                    
                                                    </div>
                                                    <div class="arf_px" style=" <?php echo $font_size_px_lbl; ?>"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$font_normal_state_lbl = 'float:right;width:100%;text-align:right;';
										$font_text_color_main  = 'float:right;margin-top:10px;margin-left:30px;clear:none;width:100%;';
										$font_text_color_cls = 'arf_float_right';
										$font_text_color_css  = 'margin-left:17px;';
										$font_text_color_css_lbl = 'padding-right:10px;padding-left:12px;float:right;text-align:right;';
									}
									else
									{
										$font_normal_state_lbl = 'float:left;width:100%;';
										$font_text_color_main  = 'float:left;margin-top:10px;clear:none;width:100%;';
										$font_text_color_cls = 'arf_float_left';
										$font_text_color_css  = 'margin-right:17px;float:left;';
										$font_text_color_css_lbl = 'padding-left:10px;padding-right:12px;float:left;text-align:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $font_normal_state_lbl; ?>"><?php _e('Normal State', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield " style=" <?php echo $font_text_color_main; ?>">
            
            				 	
                                <label class="lblsubheading" <?php echo 'style="'.$font_text_color_css_lbl.'"';?>><?php _e('Text color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $font_text_color_cls; ?>" style=" <?php echo $font_text_color_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arftextcolorsetting" style="background:<?php echo esc_attr($newarr['text_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arftcs" id="arftextcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['text_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            <?php
								if(is_rtl())
								{
									$bg_col_lbl_wrap = 'margin-top:11px;width:100%;float:left;clear:left;';
									$bg_col_lbl_cls = 'arf_float_left';
									$bg_col_lbl_css = 'float:right;';
									$bg_col_lbl_css_lbl = 'float:right;padding-right:10px;padding-left:12px;';
								}
								else
								{
									$bg_col_lbl_wrap = 'margin-top:11px;width:100%;float:left;clear:right;';
									$bg_col_lbl_cls = 'arf_float_right';
									$bg_col_lbl_css = 'float:left;';
									$bg_col_lbl_css_lbl = 'float:left;padding-left:10px;padding-right:12px;';
								}
							?>
                            <div class="field-group field-group-border clearfix" style=" <?php echo $bg_col_lbl_wrap; ?>">
            
            
                                <label class="background lblsubheading" style=" <?php echo $bg_col_lbl_css_lbl;?>"><?php _e('Background color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $bg_col_lbl_cls; ?>" style=" <?php echo $bg_col_lbl_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_bg_color" style="background:<?php echo esc_attr($newarr['bg_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arffmbc" id="frm_bg_color" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['bg_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px; clear:both;"></div> 
       						
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$active_state_col = 'float:right;text-align:right;width:100%;';
										$active_state_bg_col_wrap = 'float:right;margin-top:10px;clear:none;width:100%;';
										$active_state_border_col_wrap = 'margin-top:11px; float:left; clear:left; width:100%;';
										$active_state_border_col_lbl  = 'float:right;padding-right:10px;padding-left:12px;';
									}
									else
									{
										$active_state_col = 'width:100%;';
										$active_state_bg_col_wrap = 'float:left;margin-top:10px;clear:none;width:100%;';
										$active_state_border_col_wrap = 'margin-top:11px; float:left; clear:right; width:100%;';
										$active_state_border_col_lbl  = 'float:left;padding-left:10px;padding-right:12px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $active_state_col; ?>"><?php _e('Active State', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $active_state_bg_col_wrap; ?>">
            
            
                                <label class="background lblsubheading sublblheading" style="width:126px;"><?php _e('Background Color', 'ARForms') ?></label>
            
            					<div class="arf_float_right" style="float:left;">
                                
                                	<div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfbgcoloractivesetting" style="background:<?php echo esc_attr($newarr['arfbgactivecolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfbcas" id="arfbgcoloractivesetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfbgactivecolorsetting']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
            
            				<?php
								if(is_rtl())
								{
									$active_state_brd_col_cls = 'arf_float_left';
									$active_state_brd_col_css = '';
									$active_state_brd_col_lbl = 'float:right;padding-right:10px;padding-left:12px;';
								}
								else
								{
									$active_state_brd_col_cls = 'arf_float_right';
									$active_state_brd_col_css = 'float:left;';
									$active_state_brd_col_lbl = 'float:left;padding-left:10px;padding-right:12px;';
								}
							?>
                            <div class="field-group clearfix subfield" style=" <?php echo $active_state_border_col_wrap; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $active_state_brd_col_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $active_state_brd_col_cls; ?>" style=" <?php echo $active_state_brd_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfborderactivecolorsetting" style="background:<?php echo esc_attr($newarr['arfborderactivecolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfbacs" id="arfborderactivecolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfborderactivecolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px; clear:both;"></div> 
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$error_state_lbl = 'float:right;text-align:right;width:130px;';
										$err_state_bg_col_wrap = 'margin-top:10px; float:right; clear:none; width:100%;';
										$err_state_brd_col_wrap = 'margin-top:11px; float:left; clear:left; width:100%;';
										$err_state_brd_cls = 'arf_float_left';
										$err_state_brd_css = '';
									}
									else
									{
										$error_state_lbl = 'width:130px;';	
										$err_state_bg_col_wrap = 'margin-top:10px; float:left; clear:none; width:100%;';
										$err_state_brd_col_wrap = 'margin-top:11px; float:left; clear:right; width:100%;';
										$err_state_brd_cls = 'arf_float_right';
										$err_state_brd_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $error_state_lbl; ?>"><?php _e('Error State', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $err_state_bg_col_wrap; ?>">
            
            
                                <label class="background lblsubheading sublblheading" style=" <?php echo $active_state_brd_col_lbl;?>"><?php _e('Background Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $err_state_brd_cls; ?>" style=" <?php echo $err_state_brd_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfbgerrorcolorsetting" style="background:<?php echo esc_attr($newarr['arferrorbgcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfbecs" id="arfbgerrorcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrorbgcolorsetting']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
            
                            <div class="field-group field-group-border clearfix subfield " style=" <?php echo $err_state_brd_col_wrap; ?>">
            
            					<?php
									if(is_rtl())
									{
										$err_state_brd_lbl = 'float:right;text-align:right;padding-right:10px;padding-left:12px;';
										$err_state_brd_cls = 'arf_float_left';
										$err_state_brd_css = 'margin-top:8px;';
									}
									else
									{
										$err_state_brd_lbl = 'float:left;padding-left:10px;padding-right:12px;';
										$err_state_brd_cls = 'arf_float_right';
										$err_state_brd_css = 'float:left';
									}
								?>
                                <label class="lblsubheading sublblheading" style=" <?php echo $err_state_brd_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $err_state_brd_cls; ?>" style=" <?php echo $err_state_brd_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfbordererrorcolorsetting" style="background:<?php echo esc_attr($newarr['arferrorbordercolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfboecs" id="arfbordererrorcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrorbordercolorsetting']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px; clear:both;"></div> 
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:10px;">
            					<?php
									if(is_rtl())
									{
										$brd_setting_lbl = 'float:right;text-align:right;width:100%;';
										$brd_size_lbl = 'text-align:right;width:85px;';
										$brd_size_cls = 'arf_float_left';
										$brd_slider_btn_start = 'float:left;margin-left:60px;';
										$brd_slider_btn_end  = 'display:inline;float:right;';
										
									}
									else
									{
										$brd_setting_lbl = 'width:100%;';
										$brd_size_lbl = 'width:85px;';
										$brd_size_cls = 'arf_float_right';
										$brd_slider_btn_start = 'float:left;margin-left:40px;';
										$brd_slider_btn_end  = 'display:inline;float:right;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $brd_setting_lbl; ?>"><?php _e('Border Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield"  style="margin-top:25px; margin-bottom:5px;">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $brd_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" name="arffbws" style="width:142px;" id="arffieldborderwidthsetting" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arffieldborderwidthsetting']) ?>" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arffieldborderwidthsetting_exs" class="arf_slider" data-slider-id='arffieldborderwidthsetting_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['arffieldborderwidthsetting']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style=" <?php echo $brd_slider_btn_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style=" <?php echo $brd_slider_btn_end; ?>"><?php _e('20 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arffbws" style="width:100px;" id="arffieldborderwidthsetting" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arffieldborderwidthsetting']) ?>" size="4" />
           						<?php } ?>
                                
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:25px; margin-bottom:5px;">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $brd_size_lbl; ?>"><?php _e('Radius', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	 <input type="text" name="arfmbs" style="width:142px;" class="txtxbox_widget"  id="arfmainbordersetting" value="<?php echo esc_attr($newarr['border_radius']) ?>" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arfmainbordersetting_exs" class="arf_slider" data-slider-id='arfmainbordersetting_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['border_radius']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style=" <?php echo $brd_slider_btn_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style=" <?php echo $brd_slider_btn_end; ?>"><?php _e('50 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfmbs" style="width:100px;" class="txtxbox_widget"  id="arfmainbordersetting" value="<?php echo esc_attr($newarr['border_radius']) ?>" size="4" />
            					
                                <?php } ?>
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px;">
            
            
                                <label class="lblsubheading sublblheading"><?php _e('Color', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$brd_col_css = 'float:right';
									}
									else
									{
										$brd_col_css = 'float:left;';
									}
								?>
            					<div class=" <?php echo $brd_size_cls; ?>" style=" <?php echo $brd_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="frm_border_color" style="background:<?php echo esc_attr($newarr['border_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arffmboc" id="frm_border_color" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['border_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:40px;"><?php _e('Style', 'ARForms')?></label>
            					<?php
									if(is_rtl())
									{
										$brd_style_opt = 'float:right; margin-right:26px;';
									}
									else
									{
										$brd_style_opt = 'float:left; margin-left:26px;';
									}
								?>
                                <div class="sltstandard1" style=" <?php echo $brd_style_opt; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                            <label onclick="" style="width:55px !important;" class="toggle-btn solid <?php if($newarr['arffieldborderstylesetting']=="solid"){ echo "success"; }?>"><input type="radio" name="arffbss" class="visuallyhidden" value="solid" <?php checked($newarr['arffieldborderstylesetting'], 'solid'); ?> /><?php _e('Solid', 'ARForms') ?></label>
                                            
                                            <label onclick="" style="width:55px !important;" class="toggle-btn dotted <?php if($newarr['arffieldborderstylesetting']=="dotted"){ echo "success"; }?>"><input type="radio" name="arffbss"  class="visuallyhidden" value="dotted" <?php checked($newarr['arffieldborderstylesetting'], 'dotted'); ?> /><?php _e('Dotted', 'ARForms') ?></label>
                                            
                                            <label onclick="" style="width:55px !important;" class="toggle-btn dashed <?php if($newarr['arffieldborderstylesetting']=="dashed"){ echo "success"; }?>"><input type="radio" name="arffbss" class="visuallyhidden" value="dashed" <?php checked($newarr['arffieldborderstylesetting'], 'dashed'); ?> /><?php _e('Dashed', 'ARForms') ?></label>
                                    </div>
                                
                                </div>	
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                            <?php
									if(is_rtl())
									{
										$field_spacing_lbl = 'float:right;width:135px;';
									}
									else
									{
										$field_spacing_lbl = 'width:135px;';
									}
								?>
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px; padding-bottom:10px;">
            
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $field_spacing_lbl; ?>"><?php _e('Field Spacing', 'ARForms') ?></label>
            
            					<div class="arf_float_left">
                                	<input type="text" name="arffms" id="arffieldmarginsetting" style="width:142px;" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arffieldmarginssetting']) ?>"  size="5" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
                                
                            </div>
                            
                            <div class="field-group clearfix" style="margin-top:10px;">
            					<?php
									if(is_rtl())
									{
										$field_inner_spacing_lbl = 'float:right;width:430px;text-align:right;';
										$field_vrtcl_spc_start = 'float:left;margin-left:55px;';
										$field_vrtcl_spc_end  = 'display:inline;float:right;';
									}
									else
									{
										$field_inner_spacing_lbl = 'width:140px;';
										$field_vrtcl_spc_start = 'float:left;margin-left:40px;';
										$field_vrtcl_spc_end  = 'display:inline;float:right;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $field_inner_spacing_lbl; ?>"><?php _e('Field Inner Spacing', 'ARForms') ?></label>
            					
                            </div>
                            <?php 
							$arffieldinnermarginssetting_value = $newarr['arffieldinnermarginssetting_1']."px ".$newarr['arffieldinnermarginssetting_2']."px ".$newarr['arffieldinnermarginssetting_1']."px ".$newarr['arffieldinnermarginssetting_2']."px";							
							?>
                            <div class="field-group clearfix subfield" style="margin-top:25px; margin-bottom:5px;">
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Vertical', 'ARForms') ?></label>
                                           					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input id="arffieldinnermarginsetting_1" name="arffieldinnermarginsetting_1" class="txtxbox_widget" style="width:142px;" type="text" onchange="arf_change_field_spacing2();" value="<?php echo esc_attr($newarr['arffieldinnermarginssetting_1']) ?>" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arffieldinnermarginssetting_1_exs" class="arf_slider" data-slider-id='arffieldinnermarginssetting_1_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="25" data-slider-step="1" data-dvalue="<?php echo floatval($newarr['arffieldinnermarginssetting_1']); ?>" data-slider-value="<?php echo floatval($newarr['arffieldinnermarginssetting_1']) ?>" />
                                <input type="hidden" name="arffieldinnermarginsetting_1" id="arffieldinnermarginsetting_1" value="<?php echo floatval($newarr['arffieldinnermarginssetting_1']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style=" <?php echo $field_vrtcl_spc_start; ?>"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style=" <?php echo $field_vrtcl_spc_end; ?>"><?php _e('25 px', 'ARForms') ?></div>
                                </div>
            					
                                <?php } ?>
                                
                            </div>
                                
                            <div class="field-group clearfix widget_bg_bottom subfield" style="margin-top:25px; margin-bottom:5px;">
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Horizontal', 'ARForms') ?></label>
                                           					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input id="arffieldinnermarginsetting_2" name="arffieldinnermarginsetting_2" class="txtxbox_widget" style="width:142px;" type="text" onchange="arf_change_field_spacing2();" value="<?php echo esc_attr($newarr['arffieldinnermarginssetting_2']) ?>" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arffieldinnermarginssetting_2_exs" class="arf_slider" data-slider-id='arffieldinnermarginssetting_2_exsSlider' style="width:142px;" type="text" data-slider-min="0" data-slider-max="25" data-slider-step="1" data-dvalue="<?php echo floatval($newarr['arffieldinnermarginssetting_2']); ?>" data-slider-value="<?php echo floatval($newarr['arffieldinnermarginssetting_2']); ?>" />
                                <input type="hidden" name="arffieldinnermarginsetting_2" id="arffieldinnermarginsetting_2" value="<?php echo floatval($newarr['arffieldinnermarginssetting_2']); ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style=" <?php echo $field_vrtcl_spc_start; ?>" ><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style=" <?php echo $field_vrtcl_spc_end; ?>" ><?php _e('25 px', 'ARForms') ?></div>
                                </div>                                
                                <?php } ?>
                                
                                <input type="hidden" name="arffims" id="arffieldinnermarginsetting" style="width:100px;" class="txtxbox_widget" value="<?php echo $arffieldinnermarginssetting_value; ?>"  size="5" />
                            </div>    
                       
                            <div class="clear" style="margin-top:10px;">
            					<?php
									if(is_rtl())
									{
										$field_transparency_lbl = 'float:right;width:140px;text-align:right;margin-top:-3px;margin-left:50px;';
									}
									else
									{
										$field_transparency_lbl = 'float:left;width:140px;';
									}
								?>
            					<div class="lblsubheading lblsubheadingbold" style=" <?php echo $field_transparency_lbl; ?>"><?php _e('Field Transparency', 'ARForms') ?></div>
                                
                                <div>
                                <label><span>NO&nbsp;</span></label><input type="checkbox" class="js-switch chkstanard" name="arfmfo" id="arfmainfield_opacity" value="<?php echo $newarr['arfmainfield_opacity'];?>" <?php if($newarr['arfmainfield_opacity']==1){ echo 'checked="checked"'; }?> /><label><span>&nbsp;YES</span></label>
                                
                                </div>
                                                        
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                             <?php
								if(is_rtl())
								{
									$calender_style_lbl = 'text-align:right;width:100%;';
								}
								else
								{
									$calender_style_lbl = 'width:100%;';
								}
							?>
                            <div class="field-group field-group-border clearfix" style="margin-top:10px;">
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $calender_style_lbl; ?>"><?php _e('Calendar Style', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group clearfix subfield">
            
            					<?php
									if(is_rtl())
									{
										$calender_theme_lbl = 'float:right;width:126px;';
										$calender_theme_opt = 'float:right;margin-right:0px;';
									}
									else
									{
										$calender_theme_lbl = 'float:left;width:126px;';
										$calender_theme_opt = 'float:left;';
									}
								?>
                                <label class="lblsubheading sublblheading" style=" <?php echo $calender_theme_lbl; ?>"><?php _e('Theme', 'ARForms') ?></label>
            
                                <div class="sltstandard1" style=" <?php echo $calender_theme_opt; ?>">
                                
                                <?php /*?><select name="arffths" style="line-height:1;width:142px;" data-width='142px'>
            
            
                                    <?php 
            
            							$jquery_themes = $armainhelper->jquery_themes();
                                        foreach($jquery_themes as $theme_name => $theme_title){  ?>
            
            
                                    <option value="<?php echo $theme_name ?>" id="theme_<?php echo $theme_name ?>" <?php selected($theme_name, $newarr['arfcalthemename']) ?>><?php echo $theme_title ?></option> 
            
            
                                    <?php } ?>
            
            
                                </select><?php */?>
                                
								<?php
									$jquery_themes = $armainhelper->jquery_themes();
								?>
                                <input id="arfformsthemesettingselbx" name="arffths" value="<?php if($newarr['arfcalthemename']!="" && $newarr['arfcalthemename']!="default_theme_jquery-ui") { echo $newarr['arfcalthemename']; } else { echo "default_theme"; }?>" type="hidden" >
                                  <dl class="arf_selectbox" data-name="arffths" data-id="arfformsthemesettingselbx" style="width:122px;">
                                    <dt><span><?php if($newarr['arfcalthemename']!="" && $newarr['arfcalthemename']!="default_theme_jquery-ui") { echo $jquery_themes[$newarr['arfcalthemename']]; } else { echo $jquery_themes["default_theme"]; }?></span>
                                      <input value="<?php echo $newarr['arfcalthemename'];?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                      <i class="fa fa-caret-down fa-lg"></i></dt>
                                    <dd>
                                      <ul style="display: none;" data-id="arfformsthemesettingselbx">
                                        <?php 
                                        foreach($jquery_themes as $theme_name => $theme_title){  ?>
                                            <li class="arf_selectbox_option" id="theme_<?php echo $theme_name ?>" data-value="<?php echo $theme_name ?>" data-label="<?php echo $theme_title ?>"><?php echo $theme_title ?></li>
                                        <?php } ?>
                                        
                                      </ul>
                                    </dd>
                                  </dl>

                                
                                </div>
                                
            
                               
            
            
                                <input type="hidden" value="<?php echo esc_attr($newarr['arfcalthemecss']) ?>" id="frm_theme_css" name="arffthc" />
            
            
                                <input type="hidden" value="<?php echo esc_attr($newarr['arfcalthemename']) ?>" id="frm_theme_name" name="arffthn" />
            
                                <input type="hidden" id="calender_url" value="<?php echo ARFURL.'/css/calender/'?>" />
                                <div class="clear"></div>
            
            
                            </div>
                           
                            <div class="field-group clearfix subfield" style="margin-top:11px;">
            					<?php
									if(is_rtl())
									{
										$date_format_lbl = 'float:right;text-align:right;width:126px;';
										$date_format_opt = 'float:left;margin-left:20px;';
									}
									else
									{
										$date_format_lbl = 'float:left;width:126px;';
										$date_format_opt = 'float:right;margin-right:17px;';
									}
								?>
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $date_format_lbl; ?>"><?php _e('Date Format', 'ARForms') ?></label>
                               
                                
                                <?php
                                $wp_format_date = get_option('date_format');
                                
                                if( $wp_format_date == 'F j, Y' || $wp_format_date =='m/d/Y' ) {
                                
                                 ?>
                                 <div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arffdaf" onchange="change_date_format_new();" id="frm_date_format" style="width:142px;" data-width='142px'>
            
            
                                    <option value="mm/dd/yy" <?php selected($newarr['date_format'], 'mm/dd/yy') ?>><?php echo date('m/d/Y', current_time('timestamp'));?></option>
            
                                    <option value="M d, yy" <?php selected($newarr['date_format'], 'M d, yy') ?>><?php echo date('M d, Y', current_time('timestamp'));?></option>
                                    
                                    <option value="MM d, yy" <?php selected($newarr['date_format'], 'MM d, yy') ?>><?php echo date('F d, Y', current_time('timestamp'));?></option>
            
            
                                </select><?php */?>
                                
                                <?php 
								$arf_selbx_dt_format = "";
                                if($newarr['date_format']=='MM d, yy')
								{
									$arf_selbx_dt_format = date('F d, Y', current_time('timestamp'));
								}else if($newarr['date_format']=='M d, yy') {
									$arf_selbx_dt_format = date('M d, Y', current_time('timestamp'));
								}else {
									$arf_selbx_dt_format = date('m/d/Y', current_time('timestamp'));
								}
								?>
                                <input id="frm_date_format" name="arffdaf" value="<?php echo $newarr['date_format'];?>" type="hidden" onchange="change_date_format_new();">
                                <dl class="arf_selectbox" data-name="arffdaf" data-id="frm_date_format" style="width:122px;">
                                  <dt><span><?php echo $arf_selbx_dt_format;?></span>
                                    <input value="<?php echo $arf_selbx_dt_format;?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_date_format">
                                      <li class="arf_selectbox_option" data-value="mm/dd/yy" data-label="<?php echo date('m/d/Y', current_time('timestamp'));?>"><?php echo date('m/d/Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="M d, yy" data-label="<?php echo date('M d, Y', current_time('timestamp'));?>"><?php echo date('M d, Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="MM d, yy" data-label="<?php echo date('F d, Y', current_time('timestamp'));?>"><?php echo date('F d, Y', current_time('timestamp'));?></li>
                                    </ul>
                                  </dd>
                                </dl>
            
                                </div>
                                 
                                 
                                
                                
                                  <?php } else if( $wp_format_date == 'd/m/Y' ) { ?>
                                
                                <div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arffdaf" id="frm_date_format" onchange="change_date_format_new();" style="width:142px;" data-width='142px'>
            
            
                                    <option value="dd/mm/yy" <?php selected($newarr['date_format'], 'dd/mm/yy') ?>><?php echo date('d/m/Y', current_time('timestamp'));?></option>
            
                                    <option value="d M, yy" <?php selected($newarr['date_format'], 'd M, yy') ?>><?php echo date('d M, Y', current_time('timestamp'));?></option>
                                    
                                    <option value="d MM, yy" <?php selected($newarr['date_format'], 'd MM, yy') ?>><?php echo date('d F, Y', current_time('timestamp'));?></option>
            
            
                                </select><?php */?>
                                
                                <?php 
								$arf_selbx_dt_format = "";
                                if($newarr['date_format']=='d MM, yy')
								{
									$arf_selbx_dt_format = date('d F, Y', current_time('timestamp'));
								}else if($newarr['date_format']=='d M, yy') {
									$arf_selbx_dt_format = date('d M, Y', current_time('timestamp'));
								}else {
									$arf_selbx_dt_format = date('d/m/Y', current_time('timestamp'));
								}
								?>
                                <input id="frm_date_format" name="arffdaf" value="<?php echo $newarr['date_format'];?>" type="hidden" onchange="change_date_format_new();">
                                <dl class="arf_selectbox" data-name="arffdaf" data-id="frm_date_format" style="width:122px;">
                                  <dt><span><?php echo $arf_selbx_dt_format;?></span>
                                    <input value="<?php echo $arf_selbx_dt_format;?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_date_format">
                                      <li class="arf_selectbox_option" data-value="dd/mm/yy" data-label="<?php echo date('d/m/Y', current_time('timestamp'));?>"><?php echo date('d/m/Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="d M, yy" data-label="<?php echo date('d M, Y', current_time('timestamp'));?>"><?php echo date('d M, Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="d MM, yy" data-label="<?php echo date('d F, Y', current_time('timestamp'));?>"><?php echo date('d F, Y', current_time('timestamp'));?></li>
                                    </ul>
                                  </dd>
                                </dl>

            
                                </div>
                                  
                                
                                
                                  <?php } else if( $wp_format_date == 'Y/m/d' ) { ?>
                                
                                <div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arffdaf" id="frm_date_format" onchange="change_date_format_new();" style="width:142px;" data-width='142px'>
            
            
                                    <option value="yy/mm/dd" <?php selected($newarr['date_format'], 'yy/mm/dd') ?>><?php echo date('Y/m/d', current_time('timestamp'));?></option>
            
                                    <option value="yy, M d" <?php selected($newarr['date_format'], 'yy, M d') ?>><?php echo date('Y, M d', current_time('timestamp'));?></option>
                                    
                                    <option value="yy, MM d" <?php selected($newarr['date_format'], 'yy, MM d') ?>><?php echo date('Y, F d', current_time('timestamp'));?></option>
            
            
                                </select><?php */?>
                                
                                <?php 
								$arf_selbx_dt_format = "";
                                if($newarr['date_format']=='yy, MM d')
								{
									$arf_selbx_dt_format = date('Y, F d', current_time('timestamp'));
								}else if($newarr['date_format']=='yy, M d') {
									$arf_selbx_dt_format = date('Y, M d', current_time('timestamp'));
								}else {
									$arf_selbx_dt_format = date('Y/m/d', current_time('timestamp'));
								}
								?>
                                <input id="frm_date_format" name="arffdaf" value="<?php echo $newarr['date_format'];?>" type="hidden" onchange="change_date_format_new();">
                                <dl class="arf_selectbox" data-name="arffdaf" data-id="frm_date_format" style="width:122px;">
                                  <dt><span><?php echo $arf_selbx_dt_format;?></span>
                                    <input value="<?php echo $arf_selbx_dt_format;?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_date_format">
                                      <li class="arf_selectbox_option" data-value="yy/mm/dd" data-label="<?php echo date('Y/m/d', current_time('timestamp'));?>"><?php echo date('Y/m/d', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="yy, M d" data-label="<?php echo date('Y, M d', current_time('timestamp'));?>"><?php echo date('Y, M d', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="yy, MM d" data-label="<?php echo date('Y, F d', current_time('timestamp'));?>"><?php echo date('Y, F d', current_time('timestamp'));?></li>
                                    </ul>
                                  </dd>
                                </dl>

            
                                </div>  
                                  
                              
                                
                                  <?php } else { ?>
                                
                                <div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arffdaf" id="frm_date_format" onchange="change_date_format_new();" style="width:142px;" data-width='142px'>
            
            
                                    <option value="dd/mm/yy" <?php selected($newarr['date_format'], 'dd/mm/yy') ?>><?php echo date('d/m/Y', current_time('timestamp'));?></option>
            
                                    <option value="mm/dd/yy" <?php selected($newarr['date_format'], 'mm/dd/yy') ?>><?php echo date('m/d/Y', current_time('timestamp'));?></option>
                                    
                                    <option value="yy/mm/dd" <?php selected($newarr['date_format'], 'yy/mm/dd') ?>><?php echo date('Y/m/d', current_time('timestamp'));?></option>
                                    
                                    <option value="M d, yy" <?php selected($newarr['date_format'], 'M d, yy') ?>><?php echo date('M d, Y', current_time('timestamp'));?></option>
                                    
                                    <option value="MM d, yy" <?php selected($newarr['date_format'], 'MM d, yy') ?>><?php echo date('F d, Y', current_time('timestamp'));?></option>
            
            
                                </select><?php */?>
                                
                                <?php 
								$arf_selbx_dt_format = "";
                                if($newarr['date_format']=='MM d, yy')
								{
									$arf_selbx_dt_format = date('F d, Y', current_time('timestamp'));
								}else if($newarr['date_format']=='M d, yy') {
									$arf_selbx_dt_format = date('M d, Y', current_time('timestamp'));
								}
								else if($newarr['date_format']=='yy/mm/dd') {
									$arf_selbx_dt_format = date('Y/m/d', current_time('timestamp'));
								}
								else if($newarr['date_format']=='mm/dd/yy') {
									$arf_selbx_dt_format = date('m/d/Y', current_time('timestamp'));
								}else {
									$arf_selbx_dt_format = date('d/m/Y', current_time('timestamp'));
								}
								?>
                                <input id="frm_date_format" name="arffdaf" value="<?php echo $newarr['date_format'];?>" type="hidden" onchange="change_date_format_new();">
                                <dl class="arf_selectbox" data-name="arffdaf" data-id="frm_date_format" style="width:122px;">
                                  <dt><span><?php echo $arf_selbx_dt_format;?></span>
                                    <input value="<?php echo $arf_selbx_dt_format;?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_date_format">
                                      <li class="arf_selectbox_option" data-value="dd/mm/yy" data-label="<?php echo date('d/m/Y', current_time('timestamp'));?>"><?php echo date('d/m/Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="mm/dd/yy" data-label="<?php echo date('m/d/Y', current_time('timestamp'));?>"><?php echo date('m/d/Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="yy/mm/dd" data-label="<?php echo date('Y/m/d', current_time('timestamp'));?>"><?php echo date('Y/m/d', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="M d, yy" data-label="<?php echo date('M d, Y', current_time('timestamp'));?>"><?php echo date('M d, Y', current_time('timestamp'));?></li>
                                      <li class="arf_selectbox_option" data-value="MM d, yy" data-label="<?php echo date('F d, Y', current_time('timestamp'));?>"><?php echo date('F d, Y', current_time('timestamp'));?></li>
                                    </ul>
                                  </dd>
                                </dl>

            
                                </div>  
                                
                                
                                
                                  <?php } ?>                   
            
            
                            </div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:11px;">
            					<?php
									if(is_rtl())
									{
										$check_radio_style_main_lbl = 'float:right;text-align:right;width:100%;';
										$check_radio_style_lbl = 'float:right;width:130px;';
									}
									else
									{
										$check_radio_style_main_lbl = 'width:100%;';
										$check_radio_style_lbl = 'float:left;width:130px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $check_radio_style_main_lbl; ?>"><?php _e('Checkbox & Radio Style', 'ARForms') ?></label> <br />
            
                            </div>
                            <div class="clearfix subfield" id="frm_check_radio_style_div">
                            	<label class="lblsubheading sublblheading" style=" <?php echo $check_radio_style_lbl; ?>"><?php _e('Style', 'ARForms') ?></label>
                            	<div class="sltstandard1" style="float:left;">
                                
                                    <?php /*?><select name="arfcksn" id="frm_check_radio_style" data-size="4" onchange="arf_change_check_radio(); ShowColorSelect(this.value);" style="width:142px;" data-width='142px'>
            
                                    <option value="minimal" <?php selected($newarr['arfcheckradiostyle'], 'minimal') ?>>Minimal</option>
            
                                    <option value="flat" <?php selected($newarr['arfcheckradiostyle'], 'flat') ?>>Flat</option>
                                    
                                    <option value="square" <?php selected($newarr['arfcheckradiostyle'], 'square') ?>>Square</option>
                                    
                                    <option value="futurico" <?php selected($newarr['arfcheckradiostyle'], 'futurico') ?>>Futurico</option>
                                    
                                    <option value="polaris" <?php selected($newarr['arfcheckradiostyle'], 'polaris') ?>>Polaris</option>
                                    
                                    <option value="none" <?php selected($newarr['arfcheckradiostyle'], 'none') ?>>(None)</option>
            
                                </select><?php */?>
            
            						
                                    <input id="frm_check_radio_style" name="arfcksn" value="<?php echo $newarr['arfcheckradiostyle'];?>" type="hidden" onchange="arf_change_check_radio(); ShowColorSelect(this.value);">
                                    <dl class="arf_selectbox" data-name="arfcksn" data-id="frm_check_radio_style" style="width:122px;">
                                      <dt><span><?php echo ucwords($newarr['arfcheckradiostyle']);?></span>
                                        <input value="<?php echo $newarr['arfcheckradiostyle'];?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                      <dd>
                                        <ul style="display: none;" data-id="frm_check_radio_style">
                                          <li class="arf_selectbox_option" data-value="minimal" data-label="Minimal">Minimal</li>
                                          <li class="arf_selectbox_option" data-value="flat" data-label="Flat">Flat</li>
                                          <li class="arf_selectbox_option" data-value="square" data-label="Square">Square</li>
                                          <li class="arf_selectbox_option" data-value="futurico" data-label="Futurico">Futurico</li>
                                          <li class="arf_selectbox_option" data-value="polaris" data-label="Polaris">Polaris</li>
                                          <li class="arf_selectbox_option" data-value="none" data-label="(None)">(None)</li>
                                        </ul>
                                      </dd>
                                    </dl>

            						
                                </div>  
							</div>
                            <div class="clearfix subfield" id="check_radio_main_color" <?php if($newarr['arfcheckradiostyle']!="none"  && $newarr['arfcheckradiostyle']!="polaris" && $newarr['arfcheckradiostyle']!="futurico"){?> style="display:block;margin-top:10px;" <?php }else{ echo "style='display:none;margin-top:10px;'"; }?>>
                            	<label class="lblsubheading sublblheading" style=" <?php echo $check_radio_style_lbl; ?>"><?php _e('Color', 'ARForms') ?></label>
                            	<div class="sltstandard1" style="float:left;">
                                
                                <?php /*?><select name="arfcksc" id="frm_check_radio_style_color" onchange="arf_change_check_radio();" data-size="4" style="width:142px;" data-width='142px'>
            						<option value="default" <?php selected($newarr['arfcheckradiocolor'], 'default') ?>>Default</option>
                                    <option value="aero" <?php selected($newarr['arfcheckradiocolor'], 'aero') ?>>Aero</option>
                                    <option value="blue" <?php selected($newarr['arfcheckradiocolor'], 'blue') ?>>Blue</option>
                                    <option value="green" <?php selected($newarr['arfcheckradiocolor'], 'green') ?>>Green</option>
                                    <option value="grey" <?php selected($newarr['arfcheckradiocolor'], 'grey') ?>>Grey</option>
                                    <option value="orange" <?php selected($newarr['arfcheckradiocolor'], 'orange') ?>>Orange</option>
                                    <option value="pink" <?php selected($newarr['arfcheckradiocolor'], 'pink') ?>>Pink</option>
                                    <option value="purple" <?php selected($newarr['arfcheckradiocolor'], 'purple') ?>>Purple</option>
                                    <option value="red" <?php selected($newarr['arfcheckradiocolor'], 'red') ?>>Red</option>
                                    <option value="yellow" <?php selected($newarr['arfcheckradiocolor'], 'yellow') ?>>Yellow</option>
            
                                </select><?php */?>
                                
                                <input id="frm_check_radio_style_color" name="arfcksc" value="<?php echo $newarr['arfcheckradiocolor'];?>" type="hidden" onchange="arf_change_check_radio();">
                                <dl class="arf_selectbox" data-name="arfcksc" data-id="frm_check_radio_style_color" style="width:122px;">
                                  <dt><span><?php echo ucwords($newarr['arfcheckradiocolor']);?></span>
                                    <input value="<?php echo $newarr['arfcheckradiocolor'];?>" style="display:none;width:110px;" class="arf_autocomplete" type="text">
                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                  <dd>
                                    <ul style="display: none;" data-id="frm_check_radio_style_color">
                                      <li class="arf_selectbox_option" data-value="default" data-label="Default">Default</li>
                                      <li class="arf_selectbox_option" data-value="aero" data-label="Aero">Aero</li>
                                      <li class="arf_selectbox_option" data-value="blue" data-label="Blue">Blue</li>
                                      <li class="arf_selectbox_option" data-value="green" data-label="Green">Green</li>
                                      <li class="arf_selectbox_option" data-value="grey" data-label="Grey">Grey</li>
                                      <li class="arf_selectbox_option" data-value="orange" data-label="Orange">Orange</li>
                                      <li class="arf_selectbox_option" data-value="pink" data-label="Pink">Pink</li>
                                      <li class="arf_selectbox_option" data-value="purple" data-label="Purple">Purple</li>
                                      <li class="arf_selectbox_option" data-value="red" data-label="Red">Red</li>
                                      <li class="arf_selectbox_option" data-value="yellow" data-label="Yellow">Yellow</li>
                                    </ul>
                                  </dd>
                                </dl>

            
                                </div>  
							</div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div>
                            
                            <div class="field-group field-group-border clearfix" style="margin-top:11px;">
            					<?php
									if(is_rtl())
									{
										$prefix_suffix_style_main_lbl = 'float:right;text-align:right;width:100%;';
										$prefix_suffix_style_lbl = 'float:right;width:130px;';
										$prefix_suffix_bg_col_lbl_cls = 'arf_float_left';
										$prefix_suffix_bg_col_lbl_css = 'float:right;';
									}
									else
									{
										$prefix_suffix_style_main_lbl = 'width:100%;';
										$prefix_suffix_style_lbl = 'float:left;width:130px;';
										$prefix_suffix_bg_col_lbl_cls = 'arf_float_right';
										$prefix_suffix_bg_col_lbl_css = 'float:left;';
									}
								?>
                             <label class="lblsubheading lblsubheadingbold" style=" <?php echo $prefix_suffix_style_main_lbl; ?>"><?php _e('Prefix & Suffix Style', 'ARForms') ?></label> <br />
                            </div>
                            <div class="clearfix subfield" id="frm_prefix_suffix_style_div">
                            	<label class="lblsubheading sublblheading" style=" <?php echo $prefix_suffix_style_lbl; ?>"><?php _e('Background', 'ARForms') ?></label>
                                <div class=" <?php echo $prefix_suffix_bg_col_lbl_cls; ?>" style=" <?php echo $prefix_suffix_bg_col_lbl_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="prefix_suffix_bg_color" style="background:<?php echo esc_attr($newarr['prefix_suffix_bg_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="pfsfsbg" id="prefix_suffix_bg_color" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['prefix_suffix_bg_color']) ?>" style="width:100px;" />
                                </div>
                            	
							</div>
                            
                            <div class="clearfix subfield" id="frm_prefix_suffix_style_div2">
                            	<label class="lblsubheading sublblheading" style=" <?php echo $prefix_suffix_style_lbl; ?>"><?php _e('Icon Color', 'ARForms') ?></label>
                                <div class=" <?php echo $prefix_suffix_bg_col_lbl_cls; ?>" style=" <?php echo $prefix_suffix_bg_col_lbl_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="prefix_suffix_icon_color" style="background:<?php echo esc_attr($newarr['prefix_suffix_icon_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="pfsfscol" id="prefix_suffix_icon_color" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['prefix_suffix_icon_color']) ?>" style="width:100px;" />
                                </div>
                            	
							</div>
                            
                            <div style="height:10px;">&nbsp;</div>
                            
                        </div>
            
            
                    </div>
            
                    <div id="tabsubmitbuttonsetting" class="widget clearfix global-font">
            
            
                        <div class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Submit Button Settings', 'ARForms') ?></h4></div>
            
            
                        </div>
            
            
                        <div class="widget-inside">
            
            			   <div class="field-group field-group-border clearfix" >
            					<?php
									if(is_rtl())
									{
										$submit_align_lbl = 'float:right;width:132px;';
										$submit_align_btn = 'float:right;padding-top:5px;';
									}
									else
									{
										$submit_align_lbl = 'width:132px;';
										$submit_align_btn = 'float:left;padding-top:5px;';
									}
								?>
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_align_lbl; ?>"><?php _e('Submit align', 'ARForms') ?></label>
            
            					<div style=" <?php echo $submit_align_btn; ?>" class="sltstandard1">
                                	<div class="toggle-btn-grp joint-toggle">
                                            <label onclick="" class="toggle-btn normal <?php if($newarr['arfsubmitalignsetting']=="fixed"){ echo "success"; }?>"><input type="radio" name="arfmsas" class="visuallyhidden" id="frm_submit_align_1" value="fixed" <?php checked($newarr['arfsubmitalignsetting'], 'fixed'); ?> /><?php _e('Fixed', 'ARForms') ?></label><label onclick="" class="toggle-btn normal <?php if($newarr['arfsubmitalignsetting']=="auto"){ echo "success"; }?>"><input type="radio" name="arfmsas" id="frm_submit_align_2"  class="visuallyhidden" value="auto" <?php checked($newarr['arfsubmitalignsetting'], 'auto'); ?> /><?php _e('Auto', 'ARForms') ?></label>
                                    </div>
                                </div>    
            
                            </div>	
            				
                            <div class="widget_bg_bottom" style="padding-bottom:12px;" ></div> 
                            
                            <div class="field-group clearfix " style="margin-top:10px;">
            					<?php
                                	if(is_rtl())
									{
										$submit_font_setting_lbl = 'float:right;text-align:right;width:100%;';
										$submit_font_popup_close = 'float:left;';
										$submit_font_popup_cls_btn = 'margin-top:-12px;margin-right:3px;';
										$submit_font_popup_box = 'margin-right:25px;';
									}
									else
									{
										$submit_font_setting_lbl = 'width:100%;';
										$submit_font_popup_close = 'float:right;';
										$submit_font_popup_cls_btn = 'margin-top:-12px;margin-right:3px;';
										$submit_font_popup_box = 'margin-left:25px;';
									}
                                ?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_font_setting_lbl; ?>"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <?php if($newarr['arfsubmitweightsetting']!="normal"){ $submit_font_weight_html = ", ".$newarr['arfsubmitweightsetting']; }?>
                            <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style=" <?php echo $submit_font_popup_box;?>">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showsubmitfontsettingpopup" onclick="arfshowformsettingpopup('submitfontsettingpopup')"><?php echo $newarr['arfsubmitfontfamily'].", ".$newarr['arfsubmitbuttonfontsizesetting']."px ".$submit_font_weight_html;?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('submitfontsettingpopup')" /></div>
                                    <div id="submitfontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style=" <?php echo $submit_font_popup_close; ?>">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('submitfontsettingpopup')" type="button" style=" <?php echo $submit_font_popup_cls_btn; ?>">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            									<?php
													if(is_rtl())
													{
														$submit_font_family_lbl = 'float:right;width:50px;padding-left:10px;padding-top:7px;height:20px;';
														$submit_font_family_opt = 'float:left;margin-right:70px;margin-bottom:10px;position:absolute;';
													}
													else
													{
														$submit_font_family_lbl = 'float:left;width:50px;padding-left:10px;padding-top:7px;height:20px;';
														$submit_font_family_opt = 'float:right;margin-left:70px;margin-bottom:10px;position:absolute;';
													}
												?>
            
                                                <div class="lblsubheading" style=" <?php echo $submit_font_family_lbl; ?>"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <div class="sltstandard2" style=" <?php echo $submit_font_family_opt; ?>">
                                                
                                                <?php /*?><select name="arfsff" id="arfsubmitfontfamily" style="width:200px;" data-width='200px' data-size="15" onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
                                                    <optgroup label="Default Fonts">
                                                        <option value="Arial" <?php selected($newarr['arfsubmitfontfamily'], 'Arial') ?>>Arial</option>
                                        
                                                        <option value="Helvetica" <?php selected($newarr['arfsubmitfontfamily'], 'Helvetica') ?>>Helvetica</option>
                                                        
                                                        <option value="sans-serif" <?php selected($newarr['arfsubmitfontfamily'], 'sans-serif') ?>>sans-serif</option>
                                                        
                                                        <option value="Lucida Grande" <?php selected($newarr['arfsubmitfontfamily'], 'Lucida Grande') ?>>Lucida Grande</option>
                                                        
                                                        <option value="Lucida Sans Unicode" <?php selected($newarr['arfsubmitfontfamily'], 'Lucida Sans Unicode') ?>>Lucida Sans Unicode</option>
                                                        
                                                        <option value="Tahoma" <?php selected($newarr['arfsubmitfontfamily'], 'Tahoma') ?>>Tahoma</option>
                                                        
                                                        <option value="Times New Roman" <?php selected($newarr['arfsubmitfontfamily'], 'Times New Roman') ?>>Times New Roman</option>
                                                        
                                                        <option value="Courier New" <?php selected($newarr['arfsubmitfontfamily'], 'Courier New') ?>>Courier New</option>
                                                        
                                                        <option value="Verdana" <?php selected($newarr['arfsubmitfontfamily'], 'Verdana') ?>>Verdana</option>
                                                        
                                                        <option value="Geneva" <?php selected($newarr['arfsubmitfontfamily'], 'Geneva') ?>>Geneva</option>
                                                        
                                                        <option value="Courier" <?php selected($newarr['arfsubmitfontfamily'], 'Courier') ?>>Courier</option>
                                                                
                                                        <option value="Monospace" <?php selected($newarr['arfsubmitfontfamily'], 'Monospace') ?>>Monospace</option>
                                                                
                                                        <option value="Times" <?php selected($newarr['arfsubmitfontfamily'], 'Times') ?>>Times</option>
                
                                                    </optgroup>
                                                    <optgroup label="Google Fonts">
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<option value='".$goglefontsfamily."' ".selected($newarr['arfsubmitfontfamily'], $goglefontsfamily)." >".$goglefontsfamily."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </optgroup>
                                                    
                                                </select><?php */?>
                                                
                                                
                                                <input id="arfsubmitfontfamily" name="arfsff" value="<?php echo $newarr['arfsubmitfontfamily'];?>" type="hidden" onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
                                                <dl class="arf_selectbox" data-name="arfsff" data-id="arfsubmitfontfamily" style="width:180px;">
                                                  <dt><span><?php echo $newarr['arfsubmitfontfamily'];?></span>
                                                    <input value="<?php echo $newarr['arfsubmitfontfamily'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                  <dd>
                                                    <ul style="display: none;" data-id="arfsubmitfontfamily">
                                                      <ol class="arp_selectbox_group_label">
                                                        Default Fonts
                                                      </ol>
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
                                                      <ol class="arp_selectbox_group_label">
                                                        Google Fonts
                                                      </ol>
                                                      <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                    </ul>
                                                  </dd>
                                                </dl>

                                                
                                                </div>
                                            </div>
                                        	<div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
													if(is_rtl())
													{
														$submit_btn_font_style = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:right;';
														$submit_btn_font_btn = 'float:left; margin-right:70px; margin-bottom:10px; position:absolute;';
													}
													else
													{
														$submit_btn_font_style = 'width:50px; padding-left:10px; padding-top:7px; height:20px; float:left;';
														$submit_btn_font_btn = 'float:right; margin-left:70px; margin-bottom:10px; position:absolute;';
													}
												?>
            									<div class="lblsubheading" style=" <?php echo $submit_btn_font_style ?>"><?php _e('Style', 'ARForms') ?></div>
                            
                                                <div class="sltstandard1" style=" <?php echo $submit_btn_font_btn; ?>">
                                                
                                                <?php /*?><select name="arfsbwes" id="arfsubmitbuttonweightsetting" style="width:100px;" data-width='100px' onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
            
                                                    <option value="normal" <?php selected($newarr['arfsubmitweightsetting'], 'normal') ?>><?php _e('normal', 'ARForms') ?></option>
                    
                                                    <option value="bold" <?php selected($newarr['arfsubmitweightsetting'], 'bold') ?>><?php _e('bold', 'ARForms') ?></option>
                                                    
                                                    <option value="italic" <?php selected($newarr['arfsubmitweightsetting'], 'italic') ?>><?php _e('italic', 'ARForms') ?></option>
                            
                                                </select><?php */?>
                                                
                                                <input id="arfsubmitbuttonweightsetting" name="arfsbwes" value="<?php echo $newarr['arfsubmitweightsetting'];?>" type="hidden" onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
                                                <dl class="arf_selectbox" data-name="arfsbwes" data-id="arfsubmitbuttonweightsetting" style="width:80px;">
                                                  <dt><span><?php echo __($newarr['arfsubmitweightsetting'], 'ARForms');?></span>
                                                    <input value="<?php echo __($newarr['arfsubmitweightsetting'], 'ARForms');?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                  <dd>
                                                    <ul style="display: none;" data-id="arfsubmitbuttonweightsetting">
                                                      <li class="arf_selectbox_option" data-value="normal" data-label="<?php _e('normal', 'ARForms');?>"><?php _e('normal', 'ARForms');?></li>
                                                      <li class="arf_selectbox_option" data-value="bold" data-label="<?php _e('bold', 'ARForms') ?>"><?php _e('bold', 'ARForms') ?></li>
                                                      <li class="arf_selectbox_option" data-value="italic" data-label="<?php _e('italic', 'ARForms') ?>"><?php _e('italic', 'ARForms') ?></li>
                                                    </ul>
                                                  </dd>
                                                </dl>

                                                
                                                </div>
                                                
                            
                                            </div>
                                            <?php
												if(is_rtl())
												{
													$submit_font_size_lbl = 'float:right;width:50px;padding-left:10px;padding-top:7px;height:20px;';
													$submit_font_size_opt = 'float:left;position:absolute;margin-right:70px;';
													$submit_font_px = 'float:right;margin-right:105px;padding-top:5px;';
												}
												else
												{
													$submit_font_size_lbl = 'float:left;width:50px;padding-left:10px;padding-top:7px;height:20px;';
													$submit_font_size_opt = 'float:right;position:absolute;';
													$submit_font_px = 'float:right;margin-right:90px;padding-top:5px;';
												}
											?>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            									<div class="lblsubheading" style=" <?php echo $submit_font_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></div>
                                                <div style="margin-left:70px; margin-bottom:10px;">
                                                    <div class="sltstandard1" style=" <?php echo $submit_font_size_opt; ?>">
                                                    
                                                    <?php /*?><select name="arfsbfss" id="arfsubmitbuttonfontsizesetting" style="width:100px;" data-width='100px' data-size='15' onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">	
														<?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['arfsubmitbuttonfontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                        <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['arfsubmitbuttonfontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                        <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                        <option value="<?php echo $i?>" <?php selected($newarr['arfsubmitbuttonfontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                        <?php } ?>
                                                </select><?php */?>
                                                
                                                <input id="arfsubmitbuttonfontsizesetting" name="arfsbfss" value="<?php echo $newarr['arfsubmitbuttonfontsizesetting'];?>" type="hidden" onchange="Changefontsettinghtml('submitfontsettingpopup','arfsubmitfontfamily','arfsubmitbuttonweightsetting','arfsubmitbuttonfontsizesetting');">
                                                <dl class="arf_selectbox" data-name="arfsbfss" data-id="arfsubmitbuttonfontsizesetting" style="width:80px;">
                                                  <dt><span><?php echo $newarr['arfsubmitbuttonfontsizesetting'];?></span>
                                                    <input value="<?php echo $newarr['arfsubmitbuttonfontsizesetting'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                  <dd>
                                                    <ul style="display: none;" data-id="arfsubmitbuttonfontsizesetting">
                                                      <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                      <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                      <?php } ?>
                                                      <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                      <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                      <?php } ?>
                                                      <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                      <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                      <?php } ?>
                                                    </ul>
                                                  </dd>
                                                </dl>
                                                    
                                                    </div>
                                                    <div class="arf_px" style=" <?php echo $submit_font_px; ?>"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                           
                            
                            
                           	
            
            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
            
            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            					<?php
									if(is_rtl())
									{
										$submit_btn_width_height = 'float:right;width:137px;text-align:right;';
										$submit_btn_note = 'float:right;width:280px;';
									}
									else
									{
										$submit_btn_width_height = 'width:137px;';
										$submit_btn_note = 'width:280px;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_btn_width_height; ?>"><?php _e('Button Width', 'ARForms') ?><br />(<?php _e('Optional','ARForms');?>)</label>
            
                                <div style="padding-left:10px;">
                                    <div class="arf_float_left">
                                    <input type="text" name="arfsbws" id="arfsubmitbuttonwidthsetting" style="width:142px;" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfsubmitbuttonwidthsetting']) ?>"  onchange="arfsetsubmitwidth();" size="5" />&nbsp;<?php _e('px', 'ARForms') ?>
                                    </div>
                                    <label class="howto" style=" <?php echo $submit_btn_note;?>"><?php _e('If not provided anything it will be auto','ARForms');?></label>
                                    <input type="hidden" name="arfsbaw" id="arfsubmitautowidth" value="<?php echo $newarr['arfsubmitautowidth']; ?>" /> 
                                </div>
            
                            </div>
            
            
                            
            
            
                            <div class="field-group clearfix widget_bg_bottom" style="margin-top:10px;">
            
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_btn_width_height; ?>"><?php _e(' Button Height', 'ARForms') ?><br />(<?php _e('Optional','ARForms');?>)</label>
            
                                <div style="padding-left:10px;">
                                	<div class="arf_float_left">
                                		<input type="text" name="arfsbhs" id="arfsubmitbuttonheightsetting" style="width:142px;" class="txtxbox_widget" value="<?php echo esc_attr($newarr['arfsubmitbuttonheightsetting']) ?>"  size="5" />&nbsp;<?php _e('px', 'ARForms') ?>
                                	</div>
                                <label class="howto" style=" <?php echo $submit_btn_note; ?>"><?php _e('If not provided anything it will be auto','ARForms');?></label>
                                </div>
            
                            </div>
            
            
                            <div class="field-group field-group-border clearfix  subfield" style="margin-bottom:10px; padding-top:5px;">
                           		<?php
									$newarr['arfsubmitbuttontext'] = isset($newarr['arfsubmitbuttontext']) ? $newarr['arfsubmitbuttontext'] : ''; 
									if($newarr['arfsubmitbuttontext'] == '')
									{
										$arf_option = get_option('arf_options');
										$submit_value = $arf_option->submit_value;
									}
									else
									{
										$submit_value = esc_attr($newarr['arfsubmitbuttontext']);
									}
								?>
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_btn_width_height; ?>"><?php _e('Text', 'ARForms') ?></label>
            					<?php
									if(is_rtl())
									{
										$submit_btn_text_cls = 'arf_float_right';
										$submit_btn_text_css  = '';
									}
									else
									{
										$submit_btn_text_cls = 'arf_float_left';
										$submit_btn_text_css  = '';
									}
								?>
                                <div class=" <?php echo $submit_btn_text_cls; ?>" style=" <?php echo $submit_btn_text_css; ?>">
                                <input type="text" name="arfsubmitbuttontext" id="arfsubmitbuttontext" style="width:142px;" onkeyup="arfsetsubmitautowdith2();" onchange="arfchangesubmitvalue();arfsetsubmitautowdith2();" class="txtxbox_widget" value="<?php echo  $submit_value;?>"  size="5" />
                                </div>
                                                                                              
                            </div>
            
            				<div class="field-group clearfix widget_bg_bottom" style="margin-top:16px;">
            
            					<?php
									if(is_rtl())
									{
										$submit_txt_color_lbl = 'float:right;text-align:right;width:135px;';
										$submit_txt_color_cls = 'arf_float_left';
										$submit_txt_color_css = 'float:right;';
									}
									else
									{
										$submit_txt_color_lbl = 'width:135px;';
										$submit_txt_color_cls = 'arf_float_right';
										$submit_txt_color_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_txt_color_lbl; ?>"><?php _e('Text Color', 'ARForms') ?></label>
            
            					<div class=" <?php echo $submit_txt_color_cls; ?>" style=" <?php echo $submit_txt_color_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttontextcolorsetting" style="background:<?php echo esc_attr($newarr['arfsubmittextcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfsbtcs" id="arfsubmitbuttontextcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsubmittextcolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$submit_btn_bg_lbl = 'text-align:right;width:100%;float:right;';
										$submit_btn_bg_col_main = 'margin-top:10px; float: right; clear: none; width:100%;';
										$submit_btn_default_col = 'float:right;text-align:right;padding-right:10px;padding-left:12px;';
										$submit_btn_default_col_cls = 'arf_float_left';
										$submit_btn_default_col_css = 'float:right;';
									}
									else
									{
										$submit_btn_bg_lbl = 'width:100%;';
										$submit_btn_bg_col_main = 'margin-top:10px; float:left; clear:none; width:100%;';
										$submit_btn_default_col = 'float:left;padding-left:10px;padding-right:12px;';
										$submit_btn_default_col_cls = 'arf_float_right';
										$submit_btn_default_col_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_btn_bg_lbl; ?>"><?php _e('Background', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix " style=" <?php echo $submit_btn_bg_col_main; ?>">
            
            
                                <label class="lblsubheading" style=" <?php echo $submit_btn_default_col; ?>"><?php _e('Default Color', 'ARForms') ?></label>
            
            					<div class="<?php echo $submit_btn_default_col_cls; ?>" style=" <?php echo $submit_btn_default_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttonbgcolorsetting" style="background:<?php echo esc_attr($newarr['submit_bg_color']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfsbbcs" id="arfsubmitbuttonbgcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['submit_bg_color']) ?>" style="width:100px;" />
                                </div>
            
            
                            </div>
                            <?php
								if(is_rtl())
								{
									$hover_color_main = 'float:left;margin-top:11px;clear:left;width:100%;';
									$hover_color_lbl = 'float:right;text-align:right;padding-right:10px;padding-left:12px;';
									$hover_color_cls = 'arf_float_left';
									$hover_color_css = 'float:right;';
								}
								else
								{
									$hover_color_main = 'float:left;margin-top:11px;clear:none;width:100%;';
									$hover_color_lbl = 'float:left;padding-left:10px;padding-right:12px;';
									$hover_color_cls = 'arf_float_right';
									$hover_color_css = 'float:left;';
								}
							?>
                            <div class="field-group field-group-border clearfix " style=" <?php echo $hover_color_main; ?>">
            
            
                                <label class="lblsubheading" style=" <?php echo $hover_color_lbl; ?>"><?php _e('Hover Color', 'ARForms') ?></label>
            
            					<div class="<?php echo $hover_color_cls; ?>" style=" <?php echo $hover_color_css; ?> ">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttoncolorhoversetting" style="background:<?php echo esc_attr($newarr['arfsubmitbuttonbgcolorhoversetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfsbchs" id="arfsubmitbuttoncolorhoversetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsubmitbuttonbgcolorhoversetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            
                            <input type="hidden" name="arfsbcs" id="arfsubmitbuttoncolorsetting" class="hex txtxbox_widget" value="<?php echo esc_attr($newarr['arfsubmitbgcolor2setting']) ?>" style="width:80px;" />
            				
                            <div class="field-group field-group-border clearfix widget_bg_bottom" style="padding-top:11px; padding-bottom:10px;">
            					<?php
									if(is_rtl())
									{
										$submit_btn_bg_img_lbl = 'float:right;text-align:right;width:130px;';
										$submit_btn_bg_img = 'float:left;';
										$submit_btn_bg_img_loader = 'display:none; float: left; margin: 5px 0 0;';
										$submit_btn_ajax_upload = 'position: relative; overflow: hidden; float:right; cursor: pointer;';
									}
									else
									{
										$submit_btn_bg_img_lbl = 'width:130px;';
										$submit_btn_bg_img = 'float:right;';
										$submit_btn_bg_img_loader = 'display:none; float: left; margin: 5px 0 0;';
										$submit_btn_ajax_upload = 'position: relative; overflow: hidden; float:left; cursor: pointer;';
									}
								?>
                                <label class="lblsubheading" style=" <?php echo $submit_btn_bg_img_lbl; ?>"><?php _e('Background Image', 'ARForms') ?></label>
            
                                <div id="submit_btn_img_div" <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9'){ ?> class="iframe_submit_original_btn" data-id="arfsbis" style="margin-left:5px; position: relative; overflow: hidden; float:left; cursor:pointer; max-width:130px; height:22px; background: #1BBAE1; font-weight:bold; <?php if($newarr['submit_bg_img'] == '') { ?> background:#1BBAE1;padding:7px 10px 0 10px;font-size:13px;border-radius:3px;color:#FFFFFF;border:1px solid #CCCCCC;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.4); <?php } ?>" <?php }else { ?> style="margin-left:0px;" <?php } ?>>
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' && $newarr['submit_bg_img'] == ''){ ?> <span style="display:inline-block;color:#FFFFFF;text-align:center;"><?php _e('Upload', 'ARForms');?></span> <?php } ?>
                                    <?php
                                    if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ 
										if( $newarr['submit_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['submit_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_submit_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="<?php echo esc_attr($newarr['submit_bg_img']) ?>" id="arfsubmitbuttonimagesetting" />
                                        <?php } else { ?>
                                        <input type="text" class="original" name="submit_btn_img" id="field_arfsbis" form-id="" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; width: 160px; height: 59px; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        <input type="hidden" id="type_arfsbis" name="type_arfsbis" value="1" >
										<input type="hidden" value="jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico" id="file_types_arfsbis" name="field_types_arfsbis" />
                                        
                                        <input type="hidden" name="imagename" id="imagename" value="" />
                                        <input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="" id="arfsubmitbuttonimagesetting" />
                                        <?php }
										echo '<div id="arfsbis_iframe_div"><iframe style="display:none;" id="arfsbis_iframe" src="'.ARFURL.'/core/views/iframe.php" ></iframe></div>';
                                    }else { 
                                    	if( $newarr['submit_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['submit_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_submit_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="<?php echo esc_attr($newarr['submit_bg_img']) ?>" id="arfsubmitbuttonimagesetting" />
                                        <?php } else { ?>
                                        <div class="arfajaxfileupload" style="position: relative; overflow: hidden; float:left; cursor: pointer;">
                                        	<div class="file-upload-img"></div>
                                            <?php _e('Upload', 'ARForms');?>
                                            <input type="file" name="submit_btn_img" id="submit_btn_img" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        </div>
                                        
                                        <input type="hidden" name="imagename" id="imagename" value="" />
                                        <input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="" id="arfsubmitbuttonimagesetting" />
                                         &nbsp;&nbsp;<span id="ajax_submit_loader" style="display:none; float: left; margin: 5px 0 0;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/wpspin_light.gif"></span>
										<?php }
                                    } ?>
                                    
                                </div>
                                
                                
                                
                                <div style="float:left;width:300px;height:15px;"></div> 
                                
                                <label class="lblsubheading" style=" <?php echo $submit_btn_bg_img_lbl; ?>"><?php _e('Background Hover Image', 'ARForms') ?></label>
            
                                <div id="submit_hover_btn_img_div" <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9'){ ?> class="iframe_submit_hover_original_btn" data-id="arfsbhis" style="margin-left:5px; position: relative; overflow: hidden; float:left; cursor:pointer; max-width:130px; height:22px; background: #1BBAE1; font-weight:bold; <?php if($newarr['submit_hover_bg_img'] == '') { ?> background:#1BBAE1;padding:7px 10px 0 10px;font-size:13px;border-radius:3px;color:#FFFFFF;border:1px solid #CCCCCC;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.4); <?php } ?>" <?php }else { ?> style="margin-left:0px;" <?php } ?>>
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' && $newarr['submit_hover_bg_img'] == ''){ ?> <span style="display:inline-block;color:#FFFFFF;text-align:center;"><?php _e('Upload', 'ARForms');?></span> <?php } ?>
                                    <?php
                                    if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '9' ){ 
										if( $newarr['submit_hover_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['submit_hover_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_submit_hover_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="<?php echo esc_attr($newarr['submit_hover_bg_img']) ?>" id="arfsubmithoverbuttonimagesetting" />
                                        <?php } else { ?>
                                        <input type="text" class="original" name="submit_hover_btn_img" id="field_arfsbhis" form-id="" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; width: 160px; height: 59px; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        <input type="hidden" id="type_arfsbhis" name="type_arfsbhis" value="1" >
										<input type="hidden" value="jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico" id="file_types_arfsbhis" name="field_types_arfsbhis" />
                                        
                                        <input type="hidden" name="imagename_submit_hover" id="imagename_submit_hover" value="" />
                                        <input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="" id="arfsubmithoverbuttonimagesetting" />
                                        <?php }
										echo '<div id="arfsbhis_iframe_div"><iframe style="display:none;" id="arfsbhis_iframe" src="'.ARFURL.'/core/views/iframe.php" ></iframe></div>';
                                    }else { 
                                    	if( $newarr['submit_hover_bg_img'] != '' ) { ?>
                                        <img src="<?php echo $newarr['submit_hover_bg_img']; ?>" height="30" width="100" style="margin-left:5px;" />&nbsp;<img style="cursor:pointer; vertical-align:super;" onclick="delete_submit_hover_bg_img();" src="<?php echo ARFURL.'/images/delete-icon.png';?>" />
                                        <input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="<?php echo esc_attr($newarr['submit_hover_bg_img']) ?>" id="arfsubmithoverbuttonimagesetting" />
                                        <?php } else { ?>
                                        <div class="arfajaxfileupload" style="position: relative; overflow: hidden; float:left; cursor: pointer;">
                                        	<div class="file-upload-img"></div>
                                            <?php _e('Upload', 'ARForms');?>
                                            <input type="file" name="submit_hover_btn_img" data-val="submit_hover_bg" id="submit_hover_btn_img" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
                                        </div>
                                        
                                        <input type="hidden" name="imagename_submit_hover" id="imagename_submit_hover" value="" />
                                        <input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="" id="arfsubmithoverbuttonimagesetting" />
                                         &nbsp;&nbsp;<span id="ajax_submit_hover_loader" style="display:none; float: left; margin: 5px 0 0;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/wpspin_light.gif"></span>
										<?php }
                                    } ?>
                                    
                                </div>
                                
            
                            </div>
            
                            
                            
                            
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$submit_border_setting_lbl = 'float:right;text-align:right;width:100%;';
									}
									else
									{
										$submit_border_setting_lbl = 'width:100%;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_border_setting_lbl; ?>"><?php _e('Border Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
            
                            
            
                            <div class="field-group clearfix subfield" style="margin-top:30px; margin-bottom:5px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Size', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" name="arfsbbws" id="arfsubmitbuttonborderwidhtsetting" style="width:142px;" value="<?php echo esc_attr($newarr['arfsubmitborderwidthsetting']) ?>" class="txtxbox_widget" size="4" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
                                <input id="arfsubmitbuttonborderwidhtsetting_exs" class="arf_slider" data-slider-id='arfsubmitbuttonborderwidhtsetting_exsSlider' style="width:147px; margin-left:1px;" type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['arfsubmitborderwidthsetting']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style="float:left; margin-left:40px;"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style="float:right; display:inline;"><?php _e('20 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" name="arfsbbws" id="arfsubmitbuttonborderwidhtsetting" style="width:100px;" value="<?php echo esc_attr($newarr['arfsubmitborderwidthsetting']) ?>" class="txtxbox_widget" size="4" />
                                
                                <?php } ?>
                                
                            </div>
            
                            <div class="field-group clearfix subfield" style="margin-top:30px; margin-bottom:5px;">
            
            
                                <label class="lblsubheading sublblheading" style="width:85px;"><?php _e('Radius', 'ARForms') ?></label>
            					
                                <?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ ?>
                                <div class="arf_float_right">
                                	<input type="text" value="<?php echo esc_attr($newarr['arfsubmitborderradiussetting']) ?>" name="arfsbbrs" id="arfsubmitbuttonborderradiussetting" class="txtxbox_widget" size="4" style="width:142px;" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms') ?></span>
            					</div>
								<?php } else { ?>
                                
            					<input id="arfsubmitbuttonborderradiussetting_exs" class="arf_slider" data-slider-id='arfsubmitbuttonborderradiussetting_exsSlider' style="width:147px; margin-left:1px;" type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['arfsubmitborderradiussetting']) ?>" />
                                <br />
                                <div style="width:140px; display:inline;">
                                	<div class="arf_px" style="float:left; margin-left:40px;"><?php _e('0 px', 'ARForms') ?></div>
                                    <div class="arf_px" style="float:right; display:inline;"><?php _e('50 px', 'ARForms') ?></div>
                                </div>
            					
                                <input type="hidden" value="<?php echo esc_attr($newarr['arfsubmitborderradiussetting']) ?>" name="arfsbbrs" id="arfsubmitbuttonborderradiussetting" class="txtxbox_widget" size="4" style="width:100px;" />
                                
                                <?php } ?>
           
                            </div>
                            
                            <div class="field-group field-group-border clearfix">
            					<?php
									if(is_rtl())
									{
										$submit_border_color_lbl = 'float:right;text-align:right;width:100%;';
										$submit_border_line_main = 'clear:none;float:right;width:100%;';
										$submit_border_line_lbl = 'float:right;padding-right:10px;padding-left:12px;';
										$submit_border_line_cls = 'arf_float_left';
										$submit_border_line_css = 'float:right;';
									}
									else
									{
										$submit_border_color_lbl = 'width:100%;';
										$submit_border_line_main = 'clear:none;float:left;width:100%;';
										$submit_border_line_lbl = 'float:left;padding-left:10px;padding-right:12px;';
										$submit_border_line_cls = 'arf_float_right';
										$submit_border_line_css = 'float:left;';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $submit_border_color_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            
                            <div class="field-group field-group-border clearfix subfield" style=" <?php echo $submit_border_line_main; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $submit_border_line_lbl; ?>"><?php _e('Line', 'ARForms') ?></label>
            
            					<div class="<?php echo $submit_border_line_cls; ?>" style=" <?php echo $submit_border_line_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttonbordercolorsetting" style="background:<?php echo esc_attr($newarr['arfsubmitbordercolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfsbobcs" id="arfsubmitbuttonbordercolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsubmitbordercolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            <?php
								if(is_rtl())
								{
									$submit_border_shadow_main = 'float:left;clear:left;width:100%;';
									$submit_border_shadow_cls = 'arf_float_right';
									$submit_border_shadow_css = 'float:right;';
								}
								else
								{
									$submit_border_shadow_main = 'float:left;clear:right;width:100%;';
									$submit_border_shadow_cls = 'arf_float_left';
									$submit_border_shadow_css = 'float:left;';
								}
							?>
                            <div class="field-group clearfix subfield" style=" margin-top:11px; <?php echo $submit_border_shadow_main; ?>">
            
            
                                <label class="lblsubheading sublblheading" style=" <?php echo $submit_border_line_lbl;?>"><?php _e('Shadow', 'ARForms') ?></label>
            
            					<div class=" <?php echo $submit_border_shadow_cls; ?>" style=" <?php echo $submit_border_shadow_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfsubmitbuttonshadowcolorsetting" style="background:<?php echo esc_attr($newarr['arfsubmitshadowcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfsbscs" id="arfsubmitbuttonshadowcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsubmitshadowcolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
            				
                            <div style="clear:both; height:1px;">&nbsp;</div>
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div> 
                             
                            <div class="field-group field-group-border clearfix " style="margin-top:10px">
            
            					<?php
									if(is_rtl())
									{
										$margin_lbl = 'float:right;text-align:right;width:50px;';
										$margin_txt_main = 'float:left;margin-left:-10px;';
										$margin_top = $margin_bottom = $margin_right = 'float:right;margin-right:8px';
										$margin_left = 'float:right;margin-right:0px;';
										$margin_top_lbl = $margin_bottom_lbl = $margin_left_lbl = $margin_right_lbl = 'float:left;margin-left:0px';
									}
									else
									{
										$margin_lbl = 'width:50px;';
										$margin_txt_main = 'float:right;margin-right:-28px;';
										$margin_top = $margin_bottom = $margin_right =  'float:left;margin-left:8px';
										$margin_left = 'float:left;margin-left:8px;';
										$margin_top_lbl = $margin_bottom_lbl = $margin_left_lbl = $margin_right_lbl = 'margin-left:8px';
									}
								?>
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $margin_lbl; ?>"><?php _e('Margin', 'ARForms') ?></label>
            
            					<div style="float:right; margin-right:-28px;">
            						<div style=" <?php echo $margin_top; ?>"><input type="text" name="arfsubmitbuttonmarginsetting_1" id="arfsubmitbuttonmarginsetting_1" onchange="arf_change_field_padding('arfsubmitbuttonmarginsetting');" value="<?php echo esc_attr($newarr['arfsubmitbuttonmarginsetting_1']); ?>" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:35px;" /><br /><span class="arf_px" style=" <?php echo $margin_top_lbl; ?>"><?php _e('Top', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $margin_right; ?>"><input type="text" name="arfsubmitbuttonmarginsetting_2" id="arfsubmitbuttonmarginsetting_2" value="<?php echo esc_attr($newarr['arfsubmitbuttonmarginsetting_2']); ?>" onchange="arf_change_field_padding('arfsubmitbuttonmarginsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:35px;" /><br /><span class="arf_px" style=" <?php echo $margin_right_lbl; ?>"><?php _e('Right', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $margin_bottom; ?>"><input type="text" name="arfsubmitbuttonmarginsetting_3" id="arfsubmitbuttonmarginsetting_3" value="<?php echo esc_attr($newarr['arfsubmitbuttonmarginsetting_3']); ?>" onchange="arf_change_field_padding('arfsubmitbuttonmarginsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:35px; margin-left:3px;" /><br /><span class="arf_px" style=" <?php echo $margin_bottom_lbl; ?>"><?php _e('Bottom', 'ARForms');?></span></div>&nbsp;
                                	<div style=" <?php echo $margin_left; ?>"><input type="text" name="arfsubmitbuttonmarginsetting_4" id="arfsubmitbuttonmarginsetting_4" value="<?php echo esc_attr($newarr['arfsubmitbuttonmarginsetting_4']); ?>" onchange="arf_change_field_padding('arfsubmitbuttonmarginsetting');" class="arf_padding_exclude txtxbox_widget paddingtextbox" style="width:35px;" /><br /><span class="arf_px" style=" <?php echo $margin_left_lbl; ?>"><?php _e('Left', 'ARForms');?></span></div>&nbsp;
                                &nbsp;<span class="arf_px" style="float:left; padding-top:5px; margin-left:6px;"><?php _e('px', 'ARForms') ?></span>
                                </div>
                                <?php 
								$arfsubmitbuttonmarginsetting_value = '';
								
								if( esc_attr($newarr['arfsubmitbuttonmarginsetting_1']) != '' ){
									$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_1'].'px ';
								}else{
									$arfsubmitbuttonmarginsetting_value .= '0px ';
								}
								if( esc_attr($newarr['arfsubmitbuttonmarginsetting_2']) != '' ){
									$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_2'].'px ';
								}else{
									$arfsubmitbuttonmarginsetting_value .= '0px ';
								}					
								if( esc_attr($newarr['arfsubmitbuttonmarginsetting_3']) != '' ){
									$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_3'].'px ';
								}else{
									$arfsubmitbuttonmarginsetting_value .= '0px ';
								}
								if( esc_attr($newarr['arfsubmitbuttonmarginsetting_4']) != '' ){
									$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_4'].'px';
								}else{
									$arfsubmitbuttonmarginsetting_value .= '0px';	
								}
								?>	
                                <input type="hidden" name="arfsbms" id="arfsubmitbuttonmarginsetting" style="width:100px;" class="txtxbox_widget" value="<?php echo $arfsubmitbuttonmarginsetting_value; ?>" size="6" />
            
                            </div>
            
            
                            
                            
            
            				
            				
                            
                      
                            <div class="clear" style="height:15px;">&nbsp;</div>
            
            
                        </div>
            
            
                    </div>
            
            
                        
            
            
                    <div id="taberrormessagesettings" class="widget clearfix global-font">
            
            
                        <div class="widget-top">
            
            
                            <div class="widget-title-action"><a class="widget-action"></a></div>
            
            
                            <div class="widget-title"><h4><?php _e('Validation Style Settings', 'ARForms') ?></h4></div>
            
            
                        </div>
            			<?php
							if(is_rtl())
							{
								$validation_style_wrapper_cls = 'arf_style_validation_err';
								$validation_font_setting_lbl = 'float:right;text-align:right;width:100%;';
								$validation_font_popup_close = 'float:left;';
								$validation_font_popup_cls_btn = 'margin-top:-12px;margin-left:3px;';
								$validation_font_popup_cls_lbl = 'margin-right:26px;';
							}
							else
							{
								$validation_style_wrapper_cls = '';
								$validation_font_setting_lbl = 'width:100%;';
								$validation_font_popup_close = 'float:right;';
								$validation_font_popup_cls_btn = 'margin-top:-12px;margin-right:-3px;';
								$validation_font_popup_cls_lbl = 'margin-left:26px;';
							}
						?>
            
                        <div class="widget-inside <?php echo $validation_style_wrapper_cls; ?>" style="border-bottom: 1px solid #CACACA !important;">
            
            
                            <div class="field-group field-group-border clearfix">
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $validation_font_setting_lbl; ?>"><?php _e('Font Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            <div class="field-group clearfix subfield">
                            	<div class="arffontsettingstylebox" style=" <?php echo $validation_font_popup_cls_lbl;?>">
                                	<div style="float:left; width:230px; padding-top:7px; font-weight:normal;" id="showerrorfontsettingpopup" onclick="arfshowformsettingpopup('errorfontsettingpopup')"><?php echo $newarr['error_font'].", ".$newarr['arffontsizesetting']."px ";?></div>
                                    <div style="padding-top:5px;"><img style="cursor:pointer;" src="<?php echo ARFURL.'/images/edit-icon2.png';?>"  width="22" height="22" border="0" onclick="arfshowformsettingpopup('errorfontsettingpopup')" /></div>
                                    <div id="errorfontsettingpopup" class="arffontstylesettingmainpopupbox" style="display:none">
                                        <div class="cose" style=" <?php echo $validation_font_popup_close; ?>">
                                            <button data-dismiss="arfmodal" class="close" onclick="arfshowformsettingpopup('errorfontsettingpopup')" type="button" style=" <?php echo $validation_font_popup_cls_btn; ?>">x</button>
                                        </div>
                                        <div class="arffontstylesettingpopup">
                                            <div class="field-group clearfix subfield" style="padding-right:0;width:100%;">
            
            									<?php
													if(is_rtl())
													{
														$validation_font_family_lbl = 'float:right;width:50px;padding-right:10px;padding-top:7px;height:20px;';
														$validation_font_family_opt = 'float:left;margin-right:70px;margin-bottom:10px;position:absolute;';
													}
													else
													{
														$validation_font_family_lbl = 'float:left;width:50px;padding-left:10px;padding-top:7px;height:20px;';
														$validation_font_family_opt = 'float:right;margin-left:70px;margin-bottom:10px;position:absolute;';
													}
												?>
                                                <div class="lblsubheading" style=" <?php echo $validation_font_family_lbl; ?>"><?php _e('Family', 'ARForms') ?></div>
                                                
                                                <div class="sltstandard2" style=" <?php echo $validation_font_family_opt; ?>">
                                                <?php /*?><select name="arfmefs" id="arfmainerrorfontsetting" style="width:200px;" data-width='200px' data-size='15' onchange="Changefontsettinghtml('errorfontsettingpopup','arfmainerrorfontsetting','','arfmainerrorfontsizesetting');">
                                                    <optgroup label="Default Fonts">
                                                        <option value="Arial" <?php selected($newarr['error_font'], 'Arial') ?>>Arial</option>
                            
                                                        <option value="Helvetica" <?php selected($newarr['error_font'], 'Helvetica') ?>>Helvetica</option>
                                                        
                                                        <option value="sans-serif" <?php selected($newarr['error_font'], 'sans-serif') ?>>sans-serif</option>
                                                        
                                                        <option value="Lucida Grande" <?php selected($newarr['error_font'], 'Lucida Grande') ?>>Lucida Grande</option>
                                                        
                                                        <option value="Lucida Sans Unicode" <?php selected($newarr['error_font'], 'Lucida Sans Unicode') ?>>Lucida Sans Unicode</option>
                                                        
                                                        <option value="Tahoma" <?php selected($newarr['error_font'], 'Tahoma') ?>>Tahoma</option>
                                                        
                                                        <option value="Times New Roman" <?php selected($newarr['error_font'], 'Times New Roman') ?>>Times New Roman</option>
                                                        
                                                        <option value="Courier New" <?php selected($newarr['error_font'], 'Courier New') ?>>Courier New</option>
                                                        
                                                        <option value="Verdana" <?php selected($newarr['error_font'], 'Verdana') ?>>Verdana</option>
                                                        
                                                        <option value="Geneva" <?php selected($newarr['error_font'], 'Geneva') ?>>Geneva</option>
                                                                
                                                        <option value="Courier" <?php selected($newarr['error_font'], 'Courier') ?>>Courier</option>
                                                                
                                                        <option value="Monospace" <?php selected($newarr['error_font'], 'Monospace') ?>>Monospace</option>
                                                                
                                                        <option value="Times" <?php selected($newarr['error_font'], 'Times') ?>>Times</option>
                                                    </optgroup>
                                                    <optgroup label="Google Fonts">
                                                        <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<option value='".$goglefontsfamily."' ".selected($newarr['error_font'], $goglefontsfamily)." >".$goglefontsfamily."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </optgroup>
                                                </select><?php */?>
                                                
                                                <input id="arfmainerrorfontsetting" name="arfmefs" value="<?php echo $newarr['error_font'];?>" type="hidden" onchange="Changefontsettinghtml('errorfontsettingpopup','arfmainerrorfontsetting','','arfmainerrorfontsizesetting');">
                                                <dl class="arf_selectbox" data-name="arfmefs" data-id="arfmainerrorfontsetting" style="width:180px;">
                                                  <dt><span><?php echo $newarr['error_font'];?></span>
                                                    <input value="<?php echo $newarr['error_font'];?>" style="display:none;width:168px;" class="arf_autocomplete" type="text">
                                                    <i class="fa fa-caret-down fa-lg"></i></dt>
                                                  <dd>
                                                    <ul style="display: none;" data-id="arfmainerrorfontsetting">
                                                      <ol class="arp_selectbox_group_label">
                                                        Default Fonts
                                                      </ol>
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
                                                      <ol class="arp_selectbox_group_label">
                                                        Google Fonts
                                                      </ol>
                                                      <?php
                                                            if(count($get_googlefonts_data)>0) {
                                                                foreach($get_googlefonts_data as $goglefontsfamily)
                                                                {
                                                                    echo "<li class='arf_selectbox_option' data-value='".$goglefontsfamily."' data-label='".$goglefontsfamily."'>".$goglefontsfamily."</li>";
                                                                }
                                                            }
                                                        ?>
                                                    </ul>
                                                  </dd>
                                                </dl>

                                                
                                                </div>
                                            </div>
                                            <div class="field-group clearfix subfield" style="margin-top:10px;">
                                            	<?php
													if(is_rtl())
													{
														$validation_font_size_lbl = 'padding-right:10px; padding-top:7px; height:20px; float:right;';
														$validation_font_size_opt = 'float:left;position:absolute;margin-right:70px;';
														$validation_font_size_px = 'float:left;margin-left:20px;padding-top:5px;';
													}
													else
													{
														$validation_font_size_lbl = 'padding-left:10px; padding-top:7px; height:20px; float:left;';
														$validation_font_size_opt = 'float:left;position:absolute;';
														$validation_font_size_px = 'float:right; margin-right: 90px; padding-top:5px;';
													}
												?>
            									<div class="lblsubheading" style=" <?php echo $validation_font_size_lbl; ?>"><?php _e('Size', 'ARForms') ?></div>
                                                <div style="margin-left:70px; margin-bottom:10px;">
                                                    <div class="sltstandard1" style=" <?php echo $validation_font_size_opt; ?>">
                                                    
                                                    <?php /*?><select name="arfmefss" id="arfmainerrorfontsizesetting" style="width:100px;" data-width='100px' data-size='10' onchange="Changefontsettinghtml('errorfontsettingpopup','arfmainerrorfontsetting','','arfmainerrorfontsizesetting');">	
														   <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['arffontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                            <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['arffontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                            <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                            <option value="<?php echo $i?>" <?php selected($newarr['arffontsizesetting'], $i) ?>><?php _e($i, 'ARForms') ?></option>
                                                            <?php } ?>
                                                    </select><?php */?>
                                                    
                                                    <input id="arfmainerrorfontsizesetting" name="arfmefss" value="<?php echo $newarr['arffontsizesetting'];?>" type="hidden" onchange="Changefontsettinghtml('errorfontsettingpopup','arfmainerrorfontsetting','','arfmainerrorfontsizesetting');">
                                                    <dl class="arf_selectbox" data-name="arfmefss" data-id="arfmainerrorfontsizesetting" style="width:80px;">
                                                      <dt><span><?php echo $newarr['arffontsizesetting'];?></span>
                                                        <input value="<?php echo $newarr['arffontsizesetting'];?>" style="display:none;width:68px;" class="arf_autocomplete" type="text">
                                                        <i class="fa fa-caret-down fa-lg"></i></dt>
                                                      <dd>
                                                        <ul style="display: none;" data-id="arfmainerrorfontsizesetting">
                                                          <?php for($i = 8; $i <= 20; $i ++ ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                          <?php } ?>
                                                          <?php for($i = 22; $i <= 28; $i=$i+2 ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                          <?php } ?>
                                                          <?php for($i = 32; $i <= 40; $i=$i+4 ) { ?>
                                                          <li class="arf_selectbox_option" data-value="<?php echo $i?>" data-label="<?php echo $i?>"><?php _e($i, 'ARForms'); ?></li>
                                                          <?php } ?>
                                                        </ul>
                                                      </dd>
                                                    </dl>

                                                    
                                                    </div>
                                                    <div class="arf_px" style=" <?php echo $validation_font_size_px; ?>"><?php _e('px', 'ARForms') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                           <div style="display:none;">
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div>
                            
                            <div class="field-group field-group-border clearfix">
            
                                <label class="lblsubheading" style="width:100%"><?php _e('Error Settings', 'ARForms') ?></label> <br />
            
                            </div>
                                            
            
                            <div class="field-group field-group-border clearfix subfield">
            
            
                                <label class="lblsubheading" style="width:90px"><?php _e('BG Color', 'ARForms') ?></label>
            
            
                                <div class="hasPicker">
            
            					<div class="arf_float_right" style="margin-right:17px;">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainerrorbgsetting" style="background:<?php echo esc_attr($newarr['arferrorbgsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="arfmebs" id="arfmainerrorbgsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrorbgsetting']) ?>" style="width:100px;" /></div>
            					</div>	
            
                            </div>
            
            
            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            
                                <label class="lblsubheading" style="width:90px"><?php _e('Text Color', 'ARForms') ?></label>
            
            					<div class="arf_float_right" style="margin-right:17px;">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainerrortextsetting" style="background:<?php echo esc_attr($newarr['arferrortextsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfmets" id="arfmainerrortextsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrortextsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            
                            <div class="field-group clearfix subfield" style="margin-top:10px;">
            
            
                                <label class="lblsubheading" style="width:90px"><?php _e('Border Color', 'ARForms') ?></label>
            
            					<div class="arf_float_right" style="margin-right:17px;">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainerrotbordersetting" style="background:<?php echo esc_attr($newarr['arferrorbordersetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfmebos" id="arfmainerrotbordersetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arferrorbordersetting']) ?>" style="width:100px;" />
            					</div>	
            
                            </div>
            			   </div>
            				
                            
                            <div class="widget_bg_bottom" style="padding-bottom:12px;"></div>
                            
                            <div class="field-group field-group-border clearfix">
                                <?php
								if(is_rtl())
								{
									$validation_err_setting_lbl = 'float:right;text-align:right;width:200px;';
									$validation_err_type_btns = 'float:right;margin-left:0px;';
									$validation_err_tooltip_col = 'float:left;margin-left:0px;';
									$validation_err_tooltip_bg_lbl = 'float:right;';
								}
								else
								{
									$validation_err_setting_lbl = 'width:200px;';
									$validation_err_type_btns = 'float:left;';
									$validation_err_tooltip_col = 'float:left;';
									$validation_err_tooltip_bg_lbl = '';
								}
							?> 
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $validation_err_setting_lbl; ?>"><?php _e('Validation error settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px;">
                                
                                <label class="lblsubheading sublblheading"><?php _e('Type', 'ARForms') ?></label>
                                <div style=" <?php echo $validation_err_type_btns; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" class="toggle-btn normal <?php $newarr['arferrorstyle'] = isset($newarr['arferrorstyle']) ? $newarr['arferrorstyle'] : 'normal'; if($newarr['arferrorstyle']=="advance"){ echo "success"; }?>"><input type="radio" name="arfest" class="visuallyhidden" id="arferrorstyle1" onchange="arf_change_error_type();" value="advance" <?php checked($newarr['arferrorstyle'], 'advance'); ?> /><?php _e('Advance', 'ARForms') ?></label><label onclick="" class="toggle-btn normal <?php if($newarr['arferrorstyle']=="normal"){ echo "success"; }?>"><input type="radio" name="arfest" onchange="arf_change_error_type();" class="visuallyhidden" value="normal"  id="arferrorstyle2" <?php checked($newarr['arferrorstyle'], 'normal'); ?> /><?php _e('Normal', 'ARForms') ?></label>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="field-group field-group-border clearfix subfield" style="margin-top:10px;">                                
                                <label class="lblsubheading sublblheading" style=" <?php echo $validation_err_tooltip_bg_lbl; ?> "><?php _e('Background Color', 'ARForms') ?></label>
                                <div id="color_palate_advance" class="arf_float_right" style=" <?php if($newarr['arferrorstyle']!="advance"){ echo "display:none;";  }?>float:left;margin-left:59px;">
                                	<div class="toggle-btn-grp-color joint-toggle">
                                	<?php
                                    foreach ($arfadvanceerrcolor as $colorname => $color_value)
									{
										$explodecolor = explode("|",$color_value);
										$boxcolor = $explodecolor[0];
									?>
                                    	<label onclick="" class="toggle-btn-color <?php $newarr['arferrorstylecolor'] = isset($newarr['arferrorstylecolor']) ? $newarr['arferrorstylecolor'] : ''; if($newarr['arferrorstylecolor']==$color_value){ echo "success"; }?>"><input type="radio" name="arfestc" class="visuallyhidden" value="<?php echo $color_value;?>" <?php checked($newarr['arferrorstylecolor'], $color_value); ?> /><span style="background-color:<?php echo $boxcolor;?>; width:16px; height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
                                    <?php
									}
									?>
                                    </div>
                                </div>
                                
                                <div id="color_palate_normal" class="arf_float_right" style=" <?php if($newarr['arferrorstyle']!="normal"){ echo "display:none;";  }?>float:left;margin-left:59px;">
                                	<div class="toggle-btn-grp-color joint-toggle">
                                	<?php
									//unset($arfadvanceerrcolor['white']);
                                    foreach ($arfadvanceerrcolor as $colorname => $color_value)
									{
										$explodecolor = explode("|",$color_value);
										$boxcolor = $explodecolor[2];
									?>
                                    	<label onclick="" class="toggle-btn-color <?php $newarr['arferrorstylecolor'] = isset($newarr['arferrorstylecolor2']) ? $newarr['arferrorstylecolor2'] : ''; if($newarr['arferrorstylecolor2']==$color_value){ echo "success"; }?>"><input type="radio" name="arfestc2" class="visuallyhidden" value="<?php echo $color_value;?>" <?php checked($newarr['arferrorstylecolor2'], $color_value); ?> /><span style="background-color:<?php echo $boxcolor;?>; width:16px; height:16px;">&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
                                    <?php
									}
									?>
                                    </div>
                                </div>
                                
                            </div>
                            <?php
								if(is_rtl())
								{
									$err_validation_position_btns = 'float:right;margin-right:27px;';
								}
								else
								{
									$err_validation_position_btns = 'float:left;margin-left:27px;';
								}
							?>
                            <div class="field-group field-group-border clearfix subfield" id="showadvanceposition" style="margin-top:10px;<?php if($newarr['arferrorstyle']!="advance"){ echo "display:none;";  }?>">                               
                                <label class="lblsubheading sublblheading"><?php _e('Position', 'ARForms') ?></label>
                                <div class="arf_float_right" style=" <?php echo $err_validation_position_btns; ?>">
                                	<div class="toggle-btn-grp joint-toggle">
                                        <label onclick="" style="width:55px !important; text-align:center; margin-left:0;" class="toggle-btn-pos toppos <?php $newarr['arferrorstyleposition'] = isset($newarr['arferrorstyleposition']) ? $newarr['arferrorstyleposition'] : 'right'; if($newarr['arferrorstyleposition']=="top"){ echo "success"; }?>"><input type="radio" name="arfestbc" class="visuallyhidden" id="arfestbc1" value="top" <?php checked($newarr['arferrorstyleposition'], 'top'); ?> onchange="arf_change_error_position()" /><?php _e('Top', 'ARForms') ?></label>
                                        <label onclick="" style="width:55px !important; text-align:center;" class="toggle-btn-pos bottompos <?php if($newarr['arferrorstyleposition']=="bottom"){ echo "success"; }?>"><input type="radio" name="arfestbc" class="visuallyhidden" value="bottom"  id="arfestbc2" <?php checked($newarr['arferrorstyleposition'], 'bottom'); ?> onchange="arf_change_error_position()" /><?php _e('Bottom', 'ARForms') ?></label>
                                        <label onclick="" style="width:55px !important; text-align:center;" class="toggle-btn-pos leftpos <?php if($newarr['arferrorstyleposition']=="left"){ echo "success"; }?>"><input type="radio" name="arfestbc" class="visuallyhidden" value="left"  id="arfestbc3" <?php checked($newarr['arferrorstyleposition'], 'left'); ?> onchange="arf_change_error_position()" /><?php _e('Left', 'ARForms') ?></label>
                                        <label onclick="" style="width:55px !important; text-align:center;" class="toggle-btn-pos rightpos <?php if($newarr['arferrorstyleposition']=="right"){ echo "success"; }?>"><input type="radio" name="arfestbc" class="visuallyhidden" value="right"  id="arfestbc4" <?php checked($newarr['arferrorstyleposition'], 'right'); ?> onchange="arf_change_error_position()" /><?php _e('Right', 'ARForms') ?></label>
                                    </div>
                                </div>                                
                            </div>
                      
            				<div class="widget_bg_bottom" style="padding-bottom:12px;"></div>
                            <?php
								if(is_rtl())
								{
									$success_msg_setting_lbl = 'float:right;text-align:right;width:100%;';
									$success_msg_bg_col_lbl = 'float:right;text-align:right;width:130px;margin-right:8px;';
									$success_msg_bg_col_cls = 'arf_float_right';
									$success_msg_bg_col_css = 'margin-right:17px;';
								}
								else
								{
									$success_msg_setting_lbl = 'width:100%;';
									$success_msg_bg_col_lbl = 'width:130px;margin-left:8px;';
									$success_msg_bg_col_cls = 'arf_float_left';
									$success_msg_bg_col_css = 'margin-left:17px;float:none;';
								}
							?>
                            <div class="field-group field-group-border clearfix">
            
                                <label class="lblsubheading lblsubheadingbold" style=" <?php echo $success_msg_setting_lbl; ?>"><?php _e('Success Message Settings', 'ARForms') ?></label> <br />
            
                            </div>
                            
            
                            
                            <div class="field-group field-group-border clearfix subfield">
            
            
                                <label class="lblsubheading" style=" <?php echo $success_msg_bg_col_lbl; ?>"><?php _e('Background Color', 'ARForms') ?></label>
            
            
                                <div class="hasPicker">
            			
            					<div class=" <?php echo $success_msg_bg_col_cls; ?>" style=" <?php echo $success_msg_bg_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainsucessbgcolorsetting" style="background:<?php echo esc_attr($newarr['arfsucessbgcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                    <input name="arfmsbcs" id="arfmainsucessbgcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsucessbgcolorsetting']) ?>" type="hidden" style="width:100px;" /></div>
            					</div>	
            
                            </div>
            
            				<?php
								if(is_rtl())
								{
									$success_msg_brd_col_lbl = 'float:right;text-align:right;width:130px;';
									$success_msg_brd_col_cls = 'arf_float_right';
									$success_msg_brd_col_css = 'margin-right:17px;';
								}
								else
								{
									$success_msg_brd_col_lbl = 'width:130px;';
									$success_msg_brd_col_cls = 'arf_float_left';
									$success_msg_brd_col_css = 'margin-left:17px;';
								}
							?>
                            <div class="field-group clearfix subfield" style="margin-top:11px; margin-left:8px;">
            
            
                                <label class="lblsubheading" style=" <?php echo $success_msg_brd_col_lbl; ?>"><?php _e('Border Color', 'ARForms') ?></label>
            
            					<div class="<?php echo $success_msg_brd_col_cls; ?>" style=" <?php echo $success_msg_brd_col_css; ?>">
                                	
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainsucessbordercolorsetting" style="background:<?php echo esc_attr($newarr['arfsucessbordercolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>
                                	<input type="hidden" name="arfmsbocs" id="arfmainsucessbordercolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsucessbordercolorsetting']) ?>" style="width:100px;" />
            					</div>
            
                            </div>
            
            
                            <div class="field-group clearfix subfield" style="margin-top:11px; margin-left:8px;">
            
            					<?php
									if(is_rtl())
									{
										$success_msg_txt_col_lbl = 'float:right;text-align:right;width:130px;';
										$success_msg_txt_col_cls = 'arf_float_right';
										$success_msg_txt_col_css = 'margin-right:17px;';
									}
									else
									{
										$success_msg_txt_col_lbl = 'width:130px;';
										$success_msg_txt_col_cls = 'arf_float_left';
										$success_msg_txt_col_css = 'margin-left:17px;';
									}
								?>
                                <label class="lblsubheading" style=" <?php echo $success_msg_txt_col_lbl; ?>"><?php _e('Text Color', 'ARForms') ?></label>
            					
                                <div class="<?php echo $success_msg_txt_col_cls; ?>" style=" <?php echo $success_msg_txt_col_css; ?>">
     								
                                    <div class="arf_coloroption_sub">
                                        <div class="arf_coloroption arfhex" data-fid="arfmainsucesstextcolorsetting" style="background:<?php echo esc_attr($newarr['arfsucesstextcolorsetting']) ?>;"></div>
                                        <div class="arf_coloroption_subarrow_bg">
                                            <div class="arf_coloroption_subarrow"></div>
                                        </div>
                                    </div>	
                                	<input name="arfmstcs" id="arfmainsucesstextcolorsetting" class="txtxbox_widget hex" value="<?php echo esc_attr($newarr['arfsucesstextcolorsetting']) ?>" type="hidden" style="width:100px;" />
            					</div>
            
                            </div>
                            
                            <div class="clear" style="height:10px;"></div>
                            
                            
                            
                        </div>
            
            
                    </div>
                    <div class="buttons-row" style="padding-top:10px; margin-bottom:5px; margin-left:20px; width:300px;">
        		
		                    
                    	<?php 
						global $arfform;
						if($is_ref_form == 1){
							$form = $arfform->getAll(array('id' => $id), '', 1,1);
						}else{
							$form = $arfform->getAll(array('id' => $id), '', 1);
						}
						$pre_link = $arformhelper->get_direct_link($form->form_key);
						$width = @$_COOKIE['width'] * 0.80;
						$width_new = '&width='.$width;
						?>
                       
                    <button class="blueresetbtn" style="" type="button" onclick="reset_global_styling_settings();" ><img src="<?php echo ARFIMAGESURL.'/reset-icon.png'; ?>" align="absmiddle" />&nbsp;&nbsp;<?php _e('Reset', 'ARForms') ?>&nbsp;&nbsp;</button>	

				</div>
                </fieldset>
        	</div>
            </div>
        </li>
        </ul>
        <?php  
		if(version_compare( $GLOBALS['wp_version'], '3.7', '<'))
		{
			wp_register_script('arffiledrag', ARFURL . '/js/filedrag/filedrag_lower.js');
			wp_print_scripts('arffiledrag');
		}
		else
		{
			wp_register_script('arffiledrag', ARFURL . '/js/filedrag/filedrag.js');
			wp_print_scripts('arffiledrag');
		}	 
		?>
        <script language="javascript" type="text/javascript">
	   	var fixedheightofheader_footer = 375;
		
		var fullwindowheight = (window.screen.height - 100);
	
		var remainingheight = Number(fullwindowheight) - fixedheightofheader_footer;
	
		jQuery('.widget-inside').css('max-height', remainingheight+"px");	
		
			
		jQuery('.widget-top,a.widget-action').click(function(){			
			var currentwidgetid_old = jQuery('.current_widget').attr('id'); 
			var currentwidgetid = jQuery(this).closest('div.widget').attr('id');
		
			if( currentwidgetid_old == currentwidgetid)
				return false;
			
			jQuery('div.widget').removeClass('current_widget');
			jQuery(this).closest('div.widget').addClass('current_widget');
			jQuery(this).closest('div.widget').siblings().children('.widget-inside').slideUp('fast');
			
			if(jQuery(this).closest('div.widget').children('.widget-inside').css('display')=="block"){jQuery(this).closest('div.widget').removeClass('current_widget');}
			
			if( jQuery('.current_widget').length == 0 )
				jQuery('#tabformsettings .widget-top').trigger('click');
		});
		jQuery('#arfsubmitbuttontext').keyup(function() {
			var submit_button = jQuery(this).val();
			if(jQuery('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html().length > 0)
			{
				jQuery('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html(submit_button);
			}
		});
		<?php 
		$wp_upload_dir 	= wp_upload_dir();
		if(is_ssl())
		{
			$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/');
		}
		else
		{
			$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/';
		}
		?>
		function change_form_bg_img(){
	
			var upload_css_url = '<?php echo $upload_css_url; ?>';	
			var img = jQuery('#imagename_form').val();
			var image = upload_css_url + img;
			
			jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_form_bg_img&image="+image ,
		
			success:function(msg){ jQuery('#form_bg_img_div').html(msg); formChange1(); }	
					
			});		
		}
		//jQuery(".sltstandard1 select").selectize();
		
		<?php if( $browser_info['name'] == 'Internet Explorer' and $browser_info['version'] <= '8' ){ echo ''; } else { ?>
		///for slider		
		jQuery('#arfmainfieldsetradius_exs').slider();
			
		jQuery('#arfmainfieldsetradius_exs').slider().on('slideStop', function(ev){
			
			var data = jQuery('#arfmainfieldsetradius_exs').slider('getValue');
					
			var id = jQuery(this).attr('id');
				id = id.replace('_exs','');
			
			jQuery('#'+id).val(data).trigger('change');
				
		});
		
		
		jQuery('#arfmainfieldset_exs').slider({ tooltip: 'always' });
				
		jQuery('#arfmainfieldset_exs').slider({ tooltip: 'always' }).on('slideStop', function(ev){
			
			var data = jQuery('#arfmainfieldset_exs').slider('getValue');
					
			var id = jQuery(this).attr('id');
				id = id.replace('_exs','');
			
			jQuery('#'+id).val(data).trigger('change');
				
		});
		
		
		jQuery('#arfmainform_opacity_exs').slider({
	          	formater: function(value) {
	            	var value = ( value == 0 ) ? 0 : value/10;
					if( value < 1 && value != 0 )
						value = value.toFixed(2);
					return value;
	          	}
	    });	
		jQuery('#arfmainform_opacity_exs').slider().on('slideStop', function(ev){
			
			var data = jQuery('#arfmainform_opacity_exs').slider('getValue');
					
			var id = jQuery(this).attr('id');
				id = id.replace('_exs','');		
			var val = ( data == 0 ) ? 0 : data / 10;
		
			if( val < 1 && val != 0 )
				val = val.toFixed(2);				
			jQuery('#'+id).val(val).trigger('change');			
		});
		
		
		jQuery('#arfmainbordersetting_exs').slider();
			
		jQuery('#arfmainbordersetting_exs').slider().on('slideStop', function(ev){
			
			var data = jQuery('#arfmainbordersetting_exs').slider('getValue');
					
			var id = jQuery(this).attr('id');
				id = id.replace('_exs','');
			
			jQuery('#'+id).val(data).trigger('change');
				
		});
		
		
		jQuery('#arffieldborderwidthsetting_exs').slider();
			
		jQuery('#arffieldborderwidthsetting_exs').slider().on('slideStop', function(ev){
			
			var data = jQuery('#arffieldborderwidthsetting_exs').slider('getValue');
					
			var id = jQuery(this).attr('id');
				id = id.replace('_exs','');
			
			jQuery('#'+id).val(data).trigger('change');
				
		});
		
		
		jQuery('#arfsubmitbuttonborderradiussetting_exs').slider();
			
		jQuery('#arfsubmitbuttonborderradiussetting_exs').slider().on('slideStop', function(ev){
			
			var data = jQuery('#arfsubmitbuttonborderradiussetting_exs').slider('getValue');
					
			var id = jQuery(this).attr('id');
				id = id.replace('_exs','');
			
			jQuery('#'+id).val(data).trigger('change');
				
		});
		
		
		jQuery('#arfsubmitbuttonborderwidhtsetting_exs').slider();
			
		jQuery('#arfsubmitbuttonborderwidhtsetting_exs').slider().on('slideStop', function(ev){
			
			var data = jQuery('#arfsubmitbuttonborderwidhtsetting_exs').slider('getValue');
					
			var id = jQuery(this).attr('id');
				id = id.replace('_exs','');
			
			jQuery('#'+id).val(data).trigger('change');
		});
		
		jQuery('#arffieldinnermarginssetting_1_exs').slider();
		
		jQuery('#arffieldinnermarginssetting_1_exs').slider().on('slideStop', function(ev){
			
			var data = jQuery('#arffieldinnermarginssetting_1_exs').slider('getValue');
					
			var id = jQuery(this).attr('id');
				id = id.replace('_exs','');		
			arf_change_field_spacing();	
			jQuery('#arffieldinnermarginsetting_1').val(data);
		});
		
		jQuery('#arffieldinnermarginssetting_2_exs').slider();
			
		jQuery('#arffieldinnermarginssetting_2_exs').slider().on('slideStop', function(ev){
			
			var data = jQuery('#arffieldinnermarginssetting_2_exs').slider('getValue');
					
			var id = jQuery(this).attr('id');
				id = id.replace('_exs','');
				
			arf_change_field_spacing();			
			jQuery('#arffieldinnermarginsetting_2').val(data);
		});
				
		<?php } ?>
		var switchery = new Array();
		 if (Array.prototype.forEach) {
			var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
		
			elems.forEach(function(html) {
			  if(html.getAttribute("data-switchery")!="true")
			  {	
				 switchery[html.id] = new Switchery(html);
			  } 	 
			});
		  } else {
			var elems = document.querySelectorAll('.js-switch');
		
			for (var i = 0; i < elems.length; i++) {
			  if(elems[i].getAttribute("data-switchery")!="true")
			  {
					switchery[elems[i].id] = new Switchery(elems[i]);
			  }		
			}
		  }
		if (window.PIE) {
			var wrapper = document.querySelectorAll('.switchery')
			  , handle = document.querySelectorAll('.switchery > small');
			
			if (wrapper.length == handle.length) {
			  for (var i = 0; i < wrapper.length; i++) {
				PIE.attach(wrapper[i]);
				PIE.attach(handle[i]);
			  }
			}
		}
		jQuery(document).ready(function(){
			jQuery("span[name=arfmfo]").click(function(){
				if(jQuery("input[name=arfmfo]").is(':checked'))
				{
					jQuery("input[name=arfmfo]:checkbox").val('1').trigger("change");
				}
				else
				{
					jQuery("input[name=arfmfo]:checkbox").val('0').trigger("change");
				}	
			})
			
			/*jQuery(".toggle-btn:not('.noscript') input[type=radio], .toggle-btn-large:not('.noscript') input[type=radio], .toggle-btn-color:not('.noscript') input[type=radio], .toggle-btn-pos:not('.noscript') input[type=radio]").change(function() {
				if( jQuery(this).attr("name") ) {
					jQuery(this).parent().addClass("success").siblings().removeClass("success")
				} else {
					jQuery(this).parent().toggleClass("success");
				}
			});*/
			
			jQuery(".toggle-btn input[type=radio]").change(function() {
				if( jQuery(this).attr("name") ) {
					jQuery(this).parent().addClass("success").siblings().removeClass("success");
				} else {
					jQuery(this).parent().toggleClass("success");
				}
			});
			
			jQuery(".toggle-btn-large input[type=radio]").change(function(){
				if( jQuery(this).attr('name') ){
					jQuery(this).parent().addClass("success").siblings().removeClass("success");
				} else {
					jQuery(this).parent().toggleClass("success");
				}
			});
			
			jQuery(".toggle-btn-pos input[type=radio]").change(function(){
				if( jQuery(this).attr('name') ){
					jQuery(this).parent().addClass('success').siblings().removeClass("success");
				} else {
					jQuery(this).toggleClass('success');
				}
			});
			
			jQuery(".toggle-btn-color input[type=radio]").change(function(){
				if( jQuery(this).attr('name') ){
					jQuery(this).parent().addClass('success').siblings().removeClass("success");
				} else {
					jQuery(this).toggleClass('success');
				}
			});

		});
		jQuery('.widget .widget-inside').not('.current_widget .widget-inside').hide();
		
		function ShowColorSelect(checkradiosty)
		{
			if(checkradiosty!="none" && checkradiosty!="futurico" && checkradiosty!="polaris")
			{
				jQuery('#check_radio_main_color').show();
			}
			else
			{
				jQuery('#check_radio_main_color').hide();
			}	
		}
		/// 
        </script>		
	<?php 
	wp_register_script('ui-themepicker', ARFURL . '/js/jquery/jquery-ui-themepicker.js');
	wp_print_scripts('ui-themepicker');
		
	die();
	}
	
	function arf_delete_file(){
		
		$wp_upload_dir 	= wp_upload_dir();
		$upload_main_url = 	$wp_upload_dir['basedir'].'/arforms/';
		$dest2 = $upload_main_url."userfiles/thumbs";
		
		@unlink($upload_main_url."userfiles/".$_POST['file_name']);
		@unlink($upload_main_url."userfiles/thumbs/".$_POST['file_name']);
				
	die();
	}
	
	function arfverifypurchasecode()
	{
		global $arformcontroller,$arsettingcontroller;
		
		$lidata = array();
		
		$lidata[] = $_POST["cust_name"];
		$lidata[] = $_POST["cust_email"];
		$lidata[] = $_POST["license_key"];
		$lidata[] = $_POST["domain_name"];
		
		if(!isset($_POST["domain_name"]) || $_POST["domain_name"]== "" || $_SERVER["HTTP_HOST"] != $_POST["domain_name"])
		{
			echo "Invalid Host Name";
			exit;
		}
		
		$pluginuniquecode = $arsettingcontroller->generateplugincode();
		$lidata[] = $pluginuniquecode;
		$lidata[] = ARFURL;
		$lidata[] = get_option("arf_db_version");
		
		$valstring = implode("||",$lidata);
		$encodedval = base64_encode($valstring);
		
		$urltopost = $arformcontroller->getlicurl();
		
		$response = wp_remote_post( $urltopost, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'verifypurchase' => $encodedval ),
			'cookies' => array()
			)
		);
		
		if(array_key_exists('body',$response) && isset($response["body"]) && $response["body"] != "")
			$responsemsg = $response["body"];
		else
			$responsemsg = "";
			
		
		if($responsemsg != "")
		{
			$responsemsg = explode("|^|",$responsemsg);
			if(is_array($responsemsg) && count($responsemsg) > 0)
			{
				$msg = $responsemsg[0];
				$code = $responsemsg[1];
				
				if($msg == 1)
				{
					$checklic = $arformcontroller->checksoringcode($code);
					
					if($checklic == 1)
					{
						return "License Activated Successfully.";
						exit;
					}
					else
					{
						return "Invalid Response From Server While Activation";
						exit;
					}
				}
				else
				{
					return $responsemsg[0];
					exit;
				}
			}
			else
			{
				return $responsemsg;
				exit;
			}
		}
		else
		{
			return "Received Blank Response From Server";
			exit;
		}
		
	}
	
	function checksoringcode($code)
	{
		global $arformcontroller;
		
		$mysortid = base64_decode($code);
		$mysortid = explode("^",$mysortid);
		
		if($mysortid != "" && count($mysortid) > 0)
		{
			$setdata = $arformcontroller->setdata($code);
			
			return $setdata;
			exit;
		}
		else
		{
			return 0;
			exit;
		}
		
	}
	
	function setdata($code)
	{
		if($code != "")
		{
			$mysortid = base64_decode($code);
			$mysortid = explode("^",$mysortid);
			$mysortid = $mysortid[4];
			
			update_option("arfIsSorted","Yes");
			update_option("arfSortOrder",$code);
			update_option("arfSortId",$mysortid);
		
			return 1;
			exit;
		}
		else
		{
			return 0;
			exit;
		}
	}
	
	function get_arf_google_fonts()
	{
		global $googlefontbaseurl;
		
		if( is_ssl() )
			$googlefontbaseurl = "https://fonts.googleapis.com/css?family=";
		else	
			$googlefontbaseurl = "http://fonts.googleapis.com/css?family=";
					
		return array( 'ABeeZee', 'Abel', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akronim', 'Aladin', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya SC', 'Alegreya Sans', 'Alegreya Sans SC', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amarante', 'Amaranth', 'Amatic SC', 'Amethysta', 'Anaheim', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo Black', 'Archivo Narrow', 'Arimo', 'Arizonia', 'Armata', 'Artifika', 'Arvo', 'Asap', 'Asset', 'Astloch', 'Asul', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Balthazar', 'Bangers', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Belleza', 'BenchNine', 'Bentham', 'Berkshire Swash', 'Bevan', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'Bitter', 'Black Ops One', 'Bokor', 'Bonbon', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buda', 'Buenard', 'Butcherman', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Calligraffitti', 'Cambo', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Caudex', 'Cedarville Cursive', 'Ceviche One', 'Changa One', 'Chango', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chivo', 'Cinzel', 'Cinzel Decorative', 'Clicker Script', 'Coda', 'Coda Caption', 'Codystar', 'Combo', 'Comfortaa', 'Coming Soon', 'Concert One', 'Condiment', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Courgette', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Crete Round', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cutive', 'Cutive Mono', 'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Days One', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'Dr Sugiyama', 'Droid Sans', 'Droid Sans Mono', 'Droid Serif', 'Duru Sans', 'Dynalight', 'EB Garamond', 'Eagle Lake', 'Eater', 'Economica', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Engagement', 'Englebert', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Exo 2', 'Expletus Sans', 'Fanwood Text', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Finger Paint', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'GFS Didot', 'GFS Neohellenic', 'Gabriela', 'Gafata', 'Galdeano', 'Galindo', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One', 'Gilda Display', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas', 'Goudy Bookletter 1911', 'Graduate', 'Grand Hotel', 'Gravitas One', 'Great Vibes', 'Griffy', 'Gruppo', 'Gudea', 'Habibi', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Headland One', 'Henny Penny', 'Herr Von Muellerhoff', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Irish Grover', 'Istok Web', 'Italiana', 'Italianno', 'Jacques Francois', 'Jacques Francois Shadow', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Josefin Sans', 'Josefin Slab', 'Joti One', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kameron', 'Kantumruy', 'Karla', 'Kaushan Script', 'Kavoon', 'Kdam Thmor', 'Keania One', 'Kelly Slab', 'Kenia', 'Khmer', 'Kite One', 'Knewave', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'La Belle Aurore', 'Lancelot', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Libre Baskerville', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Lobster', 'Lobster Two', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Magra', 'Maiden Orange', 'Mako', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Marko One', 'Marmelad', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Miniver', 'Miss Fajardose', 'Modern Antiqua', 'Molengo', 'Molle', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Muli', 'Mystery Quest', 'Neucha', 'Neuton', 'New Rocker', 'News Cycle', 'Niconne', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Nothing You Could Do', 'Noticia Text', 'Noto Sans', 'Noto Serif', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'Odor Mean Chey', 'Offside', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Open Sans Condensed', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orienta', 'Original Surfer', 'Oswald', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Ovo', 'Oxygen', 'Oxygen Mono', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Patua One', 'Paytone One', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Port Lligat Sans', 'Port Lligat Slab', 'Prata', 'Preahvihear', 'Press Start 2P', 'Princess Sofia', 'Prociono', 'Prosto One', 'Puritan', 'Purple Purse', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Raleway', 'Raleway Dots', 'Rambla', 'Rammetto One', 'Ranchers', 'Rancho', 'Rationale', 'Redressed', 'Reenie Beanie', 'Revalia', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Slab', 'Rochester', 'Rock Salt', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sail', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita One', 'Sarina', 'Satisfy', 'Scada', 'Schoolbell', 'Seaweed Script', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shojumaru', 'Short Stack', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slackey', 'Smokum', 'Smythe', 'Sniglet', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Special Elite', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Stalemate', 'Stalinist One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'Stoke', 'Strait', 'Sue Ellen Francisco', 'Sunshiney', 'Supermercado One', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tangerine', 'Taprom', 'Tauri', 'Telex', 'Tenor Sans', 'Text Me One', 'The Girl Next Door', 'Tienne', 'Tinos', 'Titan One', 'Titillium Web', 'Trade Winds', 'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturCook', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Vampiro One', 'Varela', 'Varela Round', 'Vast Shadow', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'Wire One', 'Yanone Kaffeesatz', 'Yellowtail', 'Yeseva One', 'Yesteryear', 'Zeyada' );
	}
	
	function import_form()
	{
		if(isset($_FILES["importFile"]))
		{
			global $arffield, $arfform, $MdlDb, $wpdb,$WP_Filesystem;
			
			$wp_upload_dir 	= wp_upload_dir();
			$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
			$main_css_dir 	= $wp_upload_dir['basedir'].'/arforms/maincss/';
			
			$output_dir = $wp_upload_dir['basedir'].'/arforms/import_forms/';
			$output_url = $wp_upload_dir['baseurl'].'/arforms/import_forms/';
			
			if(!is_dir($output_dir))
				wp_mkdir_p($output_dir);
			
			$extexp = explode(".",$_FILES["importFile"]["name"]);
			$ext = $extexp[count($extexp)-1];
						
			//Filter the file types , if you want.
			if(strtolower($ext) == "zip")
			{
				if ($_FILES["importFile"]["error"] > 0)
				{
				  echo "Error: " . $_FILES["file"]["error"] . "<br>";
				}
				else
				{
					if(@move_uploaded_file($_FILES["importFile"]["tmp_name"],$output_dir. $_FILES["importFile"]["name"]))
					{
						$explodezipfilename = explode(".",$_FILES["importFile"]["name"]);
						$zipfilename = $explodezipfilename[0];
						$flag = $this->extract_zip($output_dir.$_FILES["importFile"]["name"],$output_dir.$zipfilename."_temp");
						if($flag=='ok')
						{
							echo $message = 'success||'.$zipfilename.'||';
						}
						else
						{
							echo $message = 'error||'.__("There is any error while uncompressing zip.","ARForms").'||';
						}
					}
					else
					{
						echo $message = 'error||'.__("Please upload only zip files.","ARForms").'||';
					}		
				}
			}
			else
			{
				 echo $message = 'error||'.__("Please upload only zip files.","ARForms").'||';
			}	
		}
	}
	
	function br2nl($string)
	{
		return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	}

	//
	function arf_import_form(){
		//loop for forms
		$xml_file_name = $_REQUEST['xml_file_name'].".xml";
		
		@ini_set('max_execution_time', 0); 
		
		$wp_upload_dir 	= wp_upload_dir();
		$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
		$main_css_dir 	= $wp_upload_dir['basedir'].'/arforms/maincss/';
		$imagenewpath 	= $wp_upload_dir['basedir'].'/arforms/import_forms/'.$_REQUEST['xml_file_name']."_temp/";
		$output_url 	= $wp_upload_dir['baseurl'].'/arforms/import_forms/'.$_REQUEST['xml_file_name']."_temp/";
		$output_dir 	= $wp_upload_dir['basedir'].'/arforms/import_forms/'.$_REQUEST['xml_file_name']."_temp/";
			
		$xml_file = $output_dir.$xml_file_name;
					
		$xml = file_get_contents($xml_file);
		
		$ik = 1;
		
		$xml = simplexml_load_string( $xml );
		
		global $arffield, $arfform, $MdlDb, $wpdb, $WP_Filesystem, $armainhelper, $arfieldhelper, $arformhelper, $arsettingcontroller;
					
		if(isset($xml->form))
		{
			foreach($xml->children() as $key_main=>$val_main)
			{	
				$attr = $val_main->attributes();
				$old_id = $attr['id'];
				$submit_bg_img_fnm = '';
				$arfmainform_bg_img_fnm = '';
				
				$submit_bg_img = $val_main->submit_bg_img;
				$arfmainform_bg_img = $val_main->arfmainform_bg_img;
				
				$wp_upload_dir 	= wp_upload_dir();
				
				$imageupload_dir = $wp_upload_dir['basedir'].'/arforms/';

				$imageupload_url = $wp_upload_dir['baseurl'].'/arforms/';
				
				//code start here for submit bg image
				if($submit_bg_img != '')
				{
					$submit_bg_img_filenm = basename($submit_bg_img); 
					
					$submit_bg_img_fnm_arr =  explode('_',$submit_bg_img_filenm);
					$submit_bg_img_length = count($submit_bg_img_fnm_arr);
			
					$submit_bg_img_fnm = time().'_'.$ik."_".$submit_bg_img_fnm_arr[$submit_bg_img_length-1];
					$ik++;
										
					if(!@copy($imagenewpath.$submit_bg_img,$imageupload_dir.$submit_bg_img_fnm))
						$submit_bg_img_fnm = '';
				}
				//code end here
				
				//code start here for background bg image
				if($arfmainform_bg_img!='')
				{
					$arfmainform_bg_img_filenm = basename($arfmainform_bg_img);
					
					$arfmainform_bg_img_fnm_arr =  explode('_',$arfmainform_bg_img_filenm);
					$arfmainform_bg_img_length = count($arfmainform_bg_img_fnm_arr);
					
					$arfmainform_bg_img_fnm = time().'_'.$ik."_".$arfmainform_bg_img_fnm_arr[$arfmainform_bg_img_length-1];	
					$ik++;	
										
					if(!@copy($imagenewpath.$arfmainform_bg_img,$imageupload_dir.$arfmainform_bg_img_fnm))
						$arfmainform_bg_img_fnm = '';
				}
				//code end here
				
				//code start here for get all general options.
				$val = '';
				
				foreach($val_main->general_options->children() as $key=>$val)
				{
					if($key == 'options' )
					{
						$options_arr = '';$options_key = '';$options_val = '';unset($option_arr_new);$option_string = '';
						
						$options_arr = @unserialize($val);

						foreach($options_arr as $options_key=>$options_val)
						{
							if(!is_array($options_val))
							{
								$options_val = str_replace('[ENTERKEY]','<br>',$options_val);
								$options_val = str_replace('[AND]','&',$options_val);
							}
									
							if($options_key == 'before_html')
							{
								$option_arr_new[$options_key] = $arformhelper->get_default_html('before');
							}
							elseif($options_key == 'ar_email_subject')
							{
								$_SESSION['ar_email_subject_org'] = $options_val;
								$option_arr_new[$options_key] = $options_val;
							}
							elseif($options_key == 'ar_email_message')
							{
								$_SESSION['ar_email_message_org'] = $options_val;
								$option_arr_new[$options_key] = $options_val;
							}
							elseif($options_key == 'ar_admin_email_message')
							{
								$_SESSION['ar_admin_email_message_org'] = $options_val;
								$option_arr_new[$options_key] = $options_val;
							}
							elseif($options_key == 'ar_email_to')
							{
								$_SESSION['ar_email_to_org'] = $options_val;
								$option_arr_new[$options_key] = $options_val;
							}
							elseif($options_key == 'ar_admin_from_email')
							{
								$_SESSION['ar_admin_from_email'] = $options_val;
								$option_arr_new[$options_key] = $options_val;
							}
							elseif($options_key == 'ar_user_from_email')
							{
								$_SESSION['ar_user_from_email'] = $options_val;
								$option_arr_new[$options_key] = $options_val;
							}
							else
							{
								$option_arr_new[$options_key] = $options_val;
							}
						}

						$option_string = @serialize($option_arr_new);
						
						$general_option[$key] = $option_string;
						
						$general_op = $option_string;
					}
					elseif($key == 'form_css' )
					{
						$form_css_arr = @unserialize($val);
						
						foreach($form_css_arr as $form_css_key=>$form_css_val)
						{
							if($form_css_key == 'submit_bg_img')
							{
								if($submit_bg_img_fnm == '')
								{
									$form_css_arr_new['submit_bg_img'] = '';
									$form_css_arr_new_db['submit_bg_img'] = '';
								}
								else
								{
									$form_css_arr_new['submit_bg_img'] = $imageupload_url.$submit_bg_img_fnm;
									$form_css_arr_new_db['submit_bg_img'] = $imageupload_url.$submit_bg_img_fnm;
								}
							}
							elseif($form_css_key == 'arfmainform_bg_img')
							{
								if($arfmainform_bg_img_fnm == '')
								{
									$form_css_arr_new[$form_css_key] = '';
									$form_css_arr_new_db[$form_css_key] = '';
								}
								else
								{
									$form_css_arr_new[$form_css_key] = $imageupload_url.$arfmainform_bg_img_fnm;
									$form_css_arr_new_db[$form_css_key] = $imageupload_url.$arfmainform_bg_img_fnm;
								}
							}
							else
							{
								$form_css_arr_new[$form_css_key] = $form_css_val;
								$form_css_arr_new_db[$form_css_key] = $form_css_val;
							}
						}
						
						$final_val = @serialize($form_css_arr_new);
						$final_val_db = @serialize($form_css_arr_new_db);
						$general_option[$key] = $final_val;
						$general_option[$key.'_db'] = $final_val_db;
					}
					else
					{
						$general_option[$key] = trim($val);
					}
					
				}
				//code end here.
				$general_option['is_importform'] = 'Yes';
				//code start here for store all general options in database.
				$autoresponder_fname = $general_option['autoresponder_fname'];
				$autoresponder_lname = $general_option['autoresponder_lname'];
				$autoresponder_email = $general_option['autoresponder_email'];
				
				$general_option['form_key'] = $armainhelper->get_unique_key('', $MdlDb->forms, 'form_key');
								
				$form_id = $arfform->create( $general_option );
				
				//code end here
				
				//code start here for get css option and generate new css.
				$cssoptions  = $general_option['form_css'];
				
				$cssoptions_db  = $general_option['form_css_db'];
								
				//code start here for get fields of form and store in database.		
				foreach($val_main->fields->children() as $key_fields=>$val_fields)
				{
					$fields_option = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables($val_fields->type , $form_id));
					
					foreach($val_fields as $key_field=>$val_field)
					{
						if($key_field == 'form_id')
						{
							$fields_option[$key_field] = $form_id;	
						}
						elseif($key_field == 'field_key')
							$fields_option[$key_field] =  $armainhelper->get_unique_key('', $MdlDb->fields, 'field_key');
						else
							$fields_option[$key_field] = trim($val_field);	
					}
					
					$res_field_id = ($fields_option['ref_field_id'] > 0 ) ? $fields_option['ref_field_id'] : $fields_option['id']; 
					
					$new_field_id = $arffield->create( $fields_option ,true,true,$res_field_id);
					$ar_email_subject = isset($ar_email_subject) ? $ar_email_subject : '';
					if($ar_email_subject == '')
						$ar_email_subject = @$_SESSION['ar_email_subject_org'];
					else
						$ar_email_subject = $ar_email_subject;
					
					$ar_email_subject = str_replace('['.$res_field_id.']','['.$new_field_id.']',$ar_email_subject);
					$ar_email_subject = $arformhelper->replace_field_shortcode_import($ar_email_subject, $res_field_id, $new_field_id);
					
					$ar_email_message = isset($ar_email_message) ? $ar_email_message : '';
					if($ar_email_message == '')
						$ar_email_message = @$_SESSION['ar_email_message_org'];
					else
						$ar_email_message = $ar_email_message;
						
					$ar_email_message = str_replace('['.$res_field_id.']','['.$new_field_id.']',$ar_email_message);
					$ar_email_message = $arformhelper->replace_field_shortcode_import($ar_email_message, $res_field_id, $new_field_id);
					
					
					$ar_admin_email_message = isset($ar_admin_email_message) ? $ar_admin_email_message : '';
					if($ar_admin_email_message == '')
						$ar_admin_email_message = @$_SESSION['ar_admin_email_message_org'];
					else
						$ar_admin_email_message = $ar_admin_email_message;
						
					$ar_admin_email_message = str_replace('['.$res_field_id.']','['.$new_field_id.']',$ar_admin_email_message);
					$ar_admin_email_message = $arformhelper->replace_field_shortcode_import($ar_admin_email_message, $res_field_id, $new_field_id);
					
					$ar_email_to = isset($ar_email_to) ? $ar_email_to : '';
					if($ar_email_to == '')
						$ar_email_to = @$_SESSION['ar_email_to_org'];
					else
						$ar_email_to = $ar_email_to;
					
					//$ar_email_to = str_replace('['.$res_field_id.']','['.$new_field_id.']',$ar_email_to);
					$ar_admin_from_email = isset($ar_admin_from_email) ? $ar_admin_from_email : '';
					if($ar_admin_from_email == '')
						$ar_admin_from_email = @$_SESSION['ar_admin_from_email'];
					else
						$ar_admin_from_email = $ar_admin_from_email;
						
					$ar_admin_from_email = str_replace('['.$res_field_id.']','['.$new_field_id.']',$ar_admin_from_email);
					$ar_admin_from_email = $arformhelper->replace_field_shortcode_import($ar_admin_from_email, $res_field_id, $new_field_id);
					
					$ar_user_from_email = isset($ar_user_from_email) ? $ar_user_from_email : '';
					if($ar_user_from_email == '')
						$ar_user_from_email = @$_SESSION['ar_user_from_email'];
					else
						$ar_user_from_email = $ar_user_from_email;
						
					$ar_user_from_email = str_replace('['.$res_field_id.']','['.$new_field_id.']',$ar_user_from_email);
					$ar_user_from_email = $arformhelper->replace_field_shortcode_import($ar_user_from_email, $res_field_id, $new_field_id);
					
					unset($field_values);
					
				}
				//code end here.
				
				if( count($_SESSION['arf_fields']) > 0 and is_array($_SESSION['arf_fields']) ){
					foreach( $_SESSION['arf_fields'] as $original_id => $field_new_id ){		
						
						if( $ar_email_to == $original_id )				 
							$ar_email_to = $field_new_id;
						
						//for conditional logic replace
						$fields_array = $arffield->getAll(array('fi.form_id' => $form_id), 'field_order');
						if( count($fields_array) > 0 ){
							foreach($fields_array as $new_field){
								
								$arf_field_options = maybe_unserialize( $new_field->field_options );
								if( count($arf_field_options) > 0 ){
									$new_field_options = array();
									foreach($arf_field_options as $key_field_options => $value_field_options){
										$new_field_options[$key_field_options] = @str_replace('[ENTERKEY]', '<br/>',$value_field_options); 			
									}									
									global $MdlDb, $wpdb;
									
									// for running total field id change
									if( $new_field->type == 'html' )
									{										
										$newdescription  = $arformhelper->replace_field_shortcode_import($new_field->description, $original_id, $field_new_id);	
										$wpdb->update($MdlDb->fields, array('description'=> $newdescription), array('id'=>$new_field->id));
									}
									$new_field_options = maybe_serialize($new_field_options);									
									$wpdb->update($MdlDb->fields, array('field_options'=> $new_field_options), array('id'=>$new_field->id));
								}
								 
								$coditional_logic = maybe_unserialize( $new_field->conditional_logic );
								if( count($coditional_logic['rules']) > 0 ){
									
									$coditional_logic_rules = array();
									
									foreach( $coditional_logic['rules'] as $new_rule ){
										if( $new_rule['field_id'] == $original_id )
											$new_rule['field_id'] = $field_new_id;
										
										$coditional_logic_rules[$new_rule['id']] = array(
														'id' 		=> $new_rule['id'],
														'field_id' 	=> $new_rule['field_id'], 
														'field_type'=> $new_rule['field_type'],
														'operator' 	=> $new_rule['operator'],
														'value'		=> $new_rule['value'],
														);	 		
									}
									global $MdlDb, $wpdb;
									$coditional_logic['rules'] = $coditional_logic_rules;								
									$coditional_logic_new = maybe_serialize($coditional_logic);
									$wpdb->update($MdlDb->fields, array('conditional_logic'=> $coditional_logic_new), array('id'=>$new_field->id));
								}
								
							}
							
						}
						//for conditional logic replace end
					}
											
				}
				//code end here.
				
				$new_values = array();
		
				foreach(unserialize($cssoptions) as $k => $v)
				{
					if( ( preg_match('/color/', $k) or in_array($k, array('arferrorbgsetting', 'arferrorbordersetting', 'arferrortextsetting')) ) && ! in_array($k, array('arfcheckradiocolor') )  ) { 
						$new_values[$k] = str_replace('#','',$v);
					} else {
						$new_values[$k] = $v;		
					}
					
				}
				$new_values1 = serialize($new_values);
			
			
				if(!empty($new_values))
				{
					$query_results = $wpdb->query($wpdb->prepare("update ".$MdlDb->forms." set form_css = '%s' where id = '%d'", $cssoptions_db, $form_id) );
					
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

					
					if( !file_exists( $css_file) ) {
						
						WP_Filesystem();
						global $wp_filesystem;
						$wp_filesystem->put_contents($css_file , $css , 0777);
					
					}
					else if( is_writable( $css_file ) ) {
						
						WP_Filesystem();
						global $wp_filesystem;
						$wp_filesystem->put_contents( $css_file , $css , 0777);
					
					}
					else
						$error = 'File Not writable'; 
					
				}
				else{
		
					$query_results = true;	
				}
				//code end here.
				
				//code start here for update autoresponder maping variables and update in satabase..	
				
				$autoresponder_fname_ses = @$_SESSION['arf_fields'][$autoresponder_fname];
				$autoresponder_lname_ses = @$_SESSION['arf_fields'][$autoresponder_lname];
				$autoresponder_email_ses = @$_SESSION['arf_fields'][$autoresponder_email];
				
					$autoresponder_fname = (isset($autoresponder_fname) and $autoresponder_fname_ses != '' ) ? $autoresponder_fname_ses : '';
					
					$autoresponder_lname = (isset($autoresponder_lname) and $autoresponder_lname_ses != '') ? $autoresponder_lname_ses : '';
					
					$autoresponder_email = (isset($autoresponder_email) and $autoresponder_email_ses != '') ? $autoresponder_email_ses : '';
											
				$wpdb->update($MdlDb->forms, array('autoresponder_fname'=> $autoresponder_fname, 'autoresponder_lname'=> $autoresponder_lname, 'autoresponder_email'=> $autoresponder_email ), array('id'=>$form_id));
				
								
				$wpdb->update($MdlDb->forms, array('options' => $general_option['options']), array('id' => $form_id) );				
				
				$sel_rec = $wpdb->prepare("select options from ".$wpdb->prefix."arf_forms where id = %d", $form_id);
			
				$res_rec = $wpdb->get_results($sel_rec, 'ARRAY_A');
				
				$opt = $res_rec[0]['options'];
				
				$form_custom_css = @stripslashes(str_replace($old_id,$form_id,$val_main->form_custom_css));
				
				$form_custom_css = @str_replace('[REPLACE_SITE_URL]',site_url(),$form_custom_css);
				
				$form_custom_css = @str_replace('[ENTERKEY]','<br>',$form_custom_css);
				
				$option_arr_new = unserialize($opt);
				
				$option_arr_new['form_custom_css'] = $form_custom_css;
				
				$option_arr_new['ar_email_subject'] = $ar_email_subject;
				
				$option_arr_new['ar_email_message'] = $ar_email_message;
				
				$option_arr_new['ar_admin_email_message'] = $ar_admin_email_message;
				
				$option_arr_new['ar_email_to'] = $ar_email_to;
				
				$option_arr_new['ar_admin_from_email'] = $ar_admin_from_email;
				
				$option_arr_new['ar_user_from_email'] = $ar_user_from_email;
				
				if($val_main->site_url != site_url()){
					$option_arr_new['success_action'] = isset($option_arr_new['success_action']) ? $option_arr_new['success_action'] : '';	
					if( $option_arr_new['success_action'] == 'page' )
						$option_arr_new['success_action'] = 'message';	
				}			
				
				$option_arr_new = serialize($option_arr_new);
				
				$wpdb->update($MdlDb->forms, array('options' => $option_arr_new), array('id' => $form_id) );
				//code end here.
				
				//code start here for get details of autoresponders used with form and store detail in database.
				if($val_main->site_url == site_url())
				{
					$aweber  = array();
					foreach( $val_main->autoresponder->aweber->children() as $autores_key1 => $autores_val1 )
					{
						$aweber[ $autores_key1 ] = @(string) trim( $autores_val1 );
					}
					$aweber = maybe_serialize( $aweber );
					
					$mailchimp  = array();
					foreach( $val_main->autoresponder->mailchimp->children() as $autores_key1 => $autores_val1 )
					{
						$mailchimp[ $autores_key1 ] = @(string) trim( $autores_val1 );
					}
					$mailchimp = maybe_serialize( $mailchimp );
					
					$getresponse  = array();
					foreach( $val_main->autoresponder->getresponse->children() as $autores_key1 => $autores_val1 )
					{
						$getresponse[ $autores_key1 ] = @(string) trim( $autores_val1 );
					}
					$getresponse = maybe_serialize( $getresponse );
					
					$gvo  = array();
					foreach( $val_main->autoresponder->gvo->children() as $autores_key1 => $autores_val1 )
					{
						$gvo[ $autores_key1 ] = @(string) trim( $autores_val1 );
					}
					$gvo = maybe_serialize( $gvo );
					
					$ebizac  = array();
					foreach( $val_main->autoresponder->ebizac->children() as $autores_key1 => $autores_val1 )
					{
						$ebizac[ $autores_key1 ] = @(string) trim( $autores_val1 );
					}
					$ebizac = maybe_serialize( $ebizac );
					
					$icontact  = array();
					foreach( $val_main->autoresponder->icontact->children() as $autores_key1 => $autores_val1 )
					{
						$icontact[ $autores_key1 ] = @(string) trim( $autores_val1 );
					}
					$icontact = maybe_serialize( $icontact );
					
					$constant_contact  = array();
					foreach( $val_main->autoresponder->constant_contact->children() as $autores_key1 => $autores_val1 )
					{
						$constant_contact[ $autores_key1 ] = @(string) trim( $autores_val1 );
					}
					$constant_contact = maybe_serialize( $constant_contact );
															
				}
				else
				{	
					global $wpdb;	
					$res = @unserialize( get_option('arf_ar_type') );
					
					$res1 = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 3), 'ARRAY_A');
					$res2 = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 1), 'ARRAY_A');
					$res3 = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 4), 'ARRAY_A');
					$res4 = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 5), 'ARRAY_A');
					$res5 = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 6), 'ARRAY_A');
					$res6 = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 8), 'ARRAY_A');
					$res7 = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 9), 'ARRAY_A');
					
					$aweber_arr['enable'] 			= @$res['aweber_type'];
					$aweber_arr['is_global'] 		= 1;
					$aweber_arr['type'] 			= @$res['aweber_type']; 
					$aweber_arr['type_val'] 		= @$res1[0]['responder_web_form'];
					
					$aweber 						= serialize($aweber_arr);
					
					$mailchimp_arr['enable'] 		= @$res['mailchimp_type']; 
					$mailchimp_arr['is_global'] 	= 1;
					$mailchimp_arr['type'] 			= @$res['mailchimp_type']; 
					$mailchimp_arr['type_val'] 		= @$res2[0]['responder_web_form'];
					
					$mailchimp						= serialize($mailchimp_arr);

					$getresponse_arr['enable'] 		= @$res['getresponse_type']; 
					$getresponse_arr['is_global'] 	= 1;
					$getresponse_arr['type'] 		= @$res['getresponse_type']; 
					$getresponse_arr['type_val'] 	= @$res3[0]['responder_web_form'];
					
					$getresponse					= serialize($getresponse_arr);
					
					$gvo_arr['enable'] 				= @$res['gvo_type'];
					$gvo_arr['is_global'] 			= 1;
					$gvo_arr['type'] 				= $res['gvo_type']; 
					$gvo_arr['type_val'] 			= @$res4[0]['responder_web_form'];
					
					$gvo 							= serialize($gvo_arr);
					
					$ebizac_arr['enable'] 			= @$res['ebizac_type']; 
					$ebizac_arr['is_global'] 		= 1;
					$ebizac_arr['type'] 			= @$res['ebizac_type']; 
					$ebizac_arr['type_val'] 		= @$res5[0]['responder_web_form'];
													
					$ebizac 						= serialize($ebizac_arr);
					
					$icontact_arr['enable'] 		= @$res['icontact_type']; 
					$icontact_arr['is_global'] 		= 1;
					$icontact_arr['type'] 			= @$res['icontact_type']; 
					$icontact_arr['type_val'] 		= @$res6[0]['responder_web_form'];
					
					$icontact 						= serialize($icontact_arr);
					
					$constant_contact_arr['enable'] = @$res['constant_contact_type']; 
					$constant_contact_arr['is_global'] = 1;
					$constant_contact_arr['type'] 	= @$res['constant_contact_type']; 
					$constant_contact_arr['type_val'] = @$res7[0]['responder_web_form'];
					
					$constant_contact 				= serialize($constant_contact_arr);
					
				}
				$frm_id 			=  $form_id;
				
				$update = $wpdb->query( $wpdb->prepare("insert into ".$wpdb->prefix."arf_ar (aweber ,mailchimp, getresponse, gvo, ebizac, icontact, constant_contact, enable_ar,  frm_id) values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')", $aweber, $mailchimp,$getresponse, $gvo ,$ebizac ,$icontact , $constant_contact, @trim($val_main->autoresponder->enable_ar), $frm_id) );	
				
				$id = isset($id) ? $id : '';
				$record = isset($record) ? $record : '';
				if($id)
					$resopt = @$wpdb->get_row("select * from ".$wpdb->prefix."arf_forms where id = ".$id, 'ARRAY_A');
				
				$resopt = isset($resopt) ? $resopt : array();
				
				$opt = @$resopt["form_css"];
				$formname = @$resopt["name"];
				$description = @$resopt["description"];
				$autoresponder_id = @$resopt["autoresponder_id"];
				$autoresponder_fname = @$resopt["autoresponder_fname"];
				$autoresponder_lname = @$resopt["autoresponder_lname"];
				$autoresponder_email = @$resopt["autoresponder_email"];
				
				
				$update = $wpdb->query( $wpdb->prepare("update ".$wpdb->prefix."arf_forms set form_id = %d,  name = '%s' , description = '%s', autoresponder_id = '%s', autoresponder_fname = '%s', autoresponder_lname = '%s', autoresponder_email = '%s', form_css = '%s' where id = '%d'", $id, $formname, $description, $autoresponder_id, $autoresponder_fname, $autoresponder_lname, $autoresponder_email, $opt, $record) );
				//code end here.						
				
			}
			echo $message = 'success||'.__("Form Imported Successfully","ARForms").'||';
		}
		else
		{
			echo $message = 'error||'.__("File is not proper.","ARForms").'||';
		}
		
	die();	
	}
	//
	
	function arf_delete_import_form(){
		
		if( isset($_POST['xml_file_name']) and $_POST['xml_file_name'] != '' ){
			
			$wp_upload_dir 	= wp_upload_dir();
			$upload_main_url = 	$wp_upload_dir['basedir'].'/arforms/';
			
			@unlink($upload_main_url."import_forms/".$_POST['xml_file_name']);
		}
		
	die();
	}
		
	function extract_zip($filename,$output_dir)
	{
		$zip = new ZipArchive;
		if ($zip->open($filename) === TRUE) {
			$zip->extractTo($output_dir);
			$zip->close();
			return 'ok';
		} else {
			return 'failed';
		}
	}
	
	function arf_remove_br( $content )
	{
		if( trim($content) == '' )
			return $content;
		
		$content = preg_replace('|<br />\s*<br />|', "", $content);	
		$content = preg_replace("~\r?~", "", $content);
		$content = preg_replace("~\r\n?~", "", $content);
		$content = preg_replace("/\n\n+/", "", $content);
				
		$content = preg_replace("|\n|", "", $content);	
		$content = preg_replace("~\n~", "", $content);	
	
		return $content;
	}
	
	
}?>