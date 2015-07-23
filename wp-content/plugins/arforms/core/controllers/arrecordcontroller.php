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

class arrecordcontroller{

    function arrecordcontroller(){


        add_action('admin_menu', array( &$this, 'menu' ), 20);

        add_action('admin_init', array(&$this, 'admin_js'), 1);

        add_action('init', array(&$this, 'register_scripts'));

        add_action('wp_enqueue_scripts', array(&$this, 'add_js'));

        add_action('wp_footer', array(&$this, 'footer_js'), 1);

        add_action('admin_footer', array(&$this, 'footer_js'));

        add_action('arfentryexecute', array(&$this, 'process_update_entry'), 10, 3);

        add_action('arfactionsubmitbutton', array($this, 'ajax_submit_button'), 10, 2);

        add_filter('arfformsubmitsuccess', array(&$this, 'get_confirmation_method'), 10, 2);

        add_action('arfformsubmissionsuccessaction', array(&$this, 'confirmation'), 10, 4);

        add_filter('arffieldsreplaceshortcodes', array(&$this, 'filter_shortcode_value'), 10, 4);

        add_action('wp_ajax_updatechart', array( &$this, 'updatechart') );
		
		add_action('wp_ajax_managecolumns', array( &$this, 'managecolumns') );
		
		add_action('wp_ajax_updateentries', array( &$this, 'frm_change_entries') );
		
		add_action('wp_ajax_arfchangebulkentries', array( &$this, 'arfchangebulkentries') );
		
		add_action('wp_ajax_recordactions', array( &$this, 'arfentryactionfunc') );
		
		add_action('wp', array(&$this, 'process_entry'), 10, 0);
		
		add_filter('arfemailvalue', array(&$this, 'filter_email_value'), 10, 3);
		
		add_action('wp_ajax_current_modal', array(&$this, 'current_modal') );
		
		add_action('wp_ajax_nopriv_current_modal', array(&$this, 'current_modal') );
		
    }

	function show_form($id='', $key='', $title=false, $description=false, $preview=false, $is_widget_or_modal=false){
					
        global $arfform, $user_ID, $arfsettings, $post, $wpdb, $armainhelper, $arrecordcontroller;


        if ($id) 
		{
			if( $id >= 10000 )
				$form = $arfform->getRefOne((int)$id);
			else
				$form = $arfform->getOne((int)$id);	
		}
        else if ($key) 
		{
			$form = $arfform->getOne($key);
		}



        $is_confirmation_method = false;  
		if( isset($_REQUEST['arf_conf']) and $_REQUEST['arf_conf'] != '' ){
			if( isset($_REQUEST['arf_conf']) and $_REQUEST['arf_conf'] == $id )
				$is_confirmation_method = true; 	
		}
		
		
        $form = apply_filters('arfpredisplayform', $form);
		
        $res = $wpdb->get_results( $wpdb->prepare( "SELECT is_enable FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $id ), 'ARRAY_A' );
		
		if((@$form->is_template or @$form->status == 'draft') and !($preview))
		{
			return __('Please select a valid form', 'ARForms');
		}
       	else if(!$form or 


            (($form->is_template or $form->status == 'draft') and !isset($_GET) and !isset($_GET['form'])) ){


            return __('Please select a valid form', 'ARForms');


        } else if( !isset($_GET['form']) and $post and $res[0]['is_enable'] == '0' ){
			
			return __('This form is currently not active, please try again later.', 'ARForms');
		
		} else if ($form->is_loggedin and !$user_ID){


            global $arfsettings;


            return do_shortcode($arfsettings->login_msg);


        }





        if($form->is_loggedin and $user_ID and isset($form->options['logged_in_role']) and $form->options['logged_in_role'] != ''){

            if($armainhelper->user_has_permission($form->options['logged_in_role'])){
							
                return $arrecordcontroller->get_form(VIEWS_PATH.'/formsubmission.php', $form, $title, $description, $preview, $is_widget_or_modal, $is_confirmation_method);


            }else{


                global $arfsettings;


                return do_shortcode($arfsettings->login_msg);


            }


        }else    
		{
            return $arrecordcontroller->get_form(VIEWS_PATH.'/formsubmission.php', $form, $title, $description, $preview, $is_widget_or_modal, $is_confirmation_method);
		}

    }
	
	function get_recordparams($form=null){


        global $arfform, $arfform_params, $armainhelper;





        if(!$form)


            $form = $arfform->getAll(array(), 'name', 1);


            


        if($arfform_params and isset($arfform_params[$form->id]))


            return $arfform_params[$form->id];


           


        $action_var = isset($_REQUEST['arfaction']) ? 'arfaction' : 'action';


        $action = apply_filters('arfshownewentrypage', $armainhelper->get_param($action_var, 'new'), $form);


        


        $default_values = array(


            'id' => '', 'form_name' => '', 'paged' => 1, 'form' => $form->id, 'form_id' => $form->id, 


            'field_id' => '', 'search' => '', 'sort' => '', 'sdir' => '', 'action' => $action


        );


            


        $values['posted_form_id'] = $armainhelper->get_param('form_id');


        if (!is_numeric($values['posted_form_id']))


            $values['posted_form_id'] = $armainhelper->get_param('form');





        if ($form->id == $values['posted_form_id']){ 


            foreach ($default_values as $var => $default){


                if($var == 'action')


                    $values[$var] = $armainhelper->get_param($action_var, $default);


                else


                    $values[$var] = $armainhelper->get_param($var, $default);


                unset($var);


                unset($default);


            }


        }else{


            foreach ($default_values as $var => $default){


                $values[$var] = $default;


                unset($var);


                unset($default);


            }


        }





        if(in_array($values['action'], array('create', 'update')) and (!isset($_POST) or (!isset($_POST['action']) and !isset($_POST['arfaction']))))


            $values['action'] = 'new';





        return $values;


    }
	
	function process_entry($errors=''){
		
		global $wpdb;
        if(!isset($_POST) or !isset($_POST['form_id']) or !is_numeric($_POST['form_id']) or !isset($_POST['entry_key']))


            return;





        global $db_record, $arfform, $arfcreatedentry, $arfform_params, $arrecordcontroller;


        


        $form = $arfform->getOne($_POST['form_id']);


        if(!$form)


            return;
       

        if(!$arfform_params)


            $arfform_params = array();


        $params = $arrecordcontroller->get_recordparams($form);

	
        $arfform_params[$form->id] = $params;


        if(!$arfcreatedentry)


            $arfcreatedentry = array();


          


        if(isset($arfcreatedentry[$_POST['form_id']]))


            return;

        $_SESSION['arf_recaptcha_allowed_'.$_POST['form_id']] = isset($_SESSION['arf_recaptcha_allowed_'.$_POST['form_id']]) ? $_SESSION['arf_recaptcha_allowed_'.$_POST['form_id']] : '';
            

        if( $errors == '' && $_SESSION['arf_recaptcha_allowed_'.$_POST['form_id']]=="" )
		{			
			$arferr = array();
			$errors = $arrecordcontroller->internal_check_recaptcha();
			if(count($errors)>0)
			{
				foreach( $errors as $field_id => $field_value )
				{
					$arferr[$field_id]	=  $field_value;
				}
				
				$return["conf_method"] = "captchaerror";
				$return["message"] = $arferr;
				
				echo json_encode($return);
				exit;
			}
		}
		unset($_SESSION['arf_recaptcha_allowed_'.$_POST['form_id']]);
		
        $arfcreatedentry[$_POST['form_id']] = array('errors' => $errors);
		
		if(isset($_POST['using_ajax']) and $_POST['using_ajax'] == 'yes')
		{
			// For outsite validation form
			$form_id 	= $_POST['form_id'];
			
			$arf_errors = array();
			
			$arf_form_data = array();
			
			$values		= $_POST;
			
			$arf_form_data = apply_filters('arf_populate_field_from_outside', $arf_form_data, $form_id, $values);	// for populate data in form
			
			$arf_errors = apply_filters('arf_validate_form_outside_errors', $arf_errors, $form_id, $values, $arf_form_data);	// for form validate filter
			
			if( isset($arf_errors['arf_form_data']) and $arf_errors['arf_form_data'] )
			{	
				$arf_form_data = array_merge($arf_form_data, $arf_errors['arf_form_data']);
			}
			
			unset($arf_errors['arf_form_data']);
			// For card Token
			if( count($arf_form_data) > 0 )
			{
				foreach( $arf_form_data as $fieldid => $fieldvalue )
					$_POST[$fieldid]	=  $fieldvalue;
			}	
			// For outsite validation form end
		}
				
        if( empty($errors) && @count($arf_errors) == 0){


            $_POST['arfentrycookie'] = 1;
			
			if($params['action'] == 'create'){

				
                if (apply_filters('arfcontinuetocreate', true, $_POST['form_id']) and !isset($arfcreatedentry[$_POST['form_id']]['entry_id']))
				{
					unset($_SESSION['vpb_captcha_code_'.$_POST['form_id']]);
					
                    $arfcreatedentry[$_POST['form_id']]['entry_id'] = $db_record->create( $_POST );
				}

            }

           	do_action('arfentryexecute', $params, $errors, $form);	
            unset($_POST['arfentrycookie']);

			
        } else {
			// Return errors
			if( $arf_errors )
			{
				//echo 'test';
				$return["conf_method"] = "validationerror";
				$return["message"] = $arf_errors;
				
				echo json_encode($return);
				exit;
			}
			exit;				
		}
		
		
		
		if(isset($_POST['using_ajax']) and $_POST['using_ajax'] == 'yes')
		{
			echo do_shortcode("[ARForms id=".$_POST['form_id']."]");	
		}
			
    }
	
    function menu(){


        global $arfsettings, $armainhelper;


        if(current_user_can('administrator') and !current_user_can('arfviewentries')){


            global $wp_roles;


            $arfroles = $armainhelper->frm_capabilities();


            foreach($arfroles as $arfrole => $arfroledescription){


                if(!in_array($arfrole, array('arfviewforms', 'arfeditforms', 'arfdeleteforms', 'arfchangesettings' , 'arfimportexport' )))


                    $wp_roles->add_cap( 'administrator', $arfrole );


            }


        }


        add_submenu_page('ARForms', 'ARForms' .' | '. __('Form Entries', 'ARForms'), __('Form Entries', 'ARForms'), 'arfviewentries', 'ARForms-entries', array(&$this, 'route'));


        add_action('admin_head-'. 'ARForms' .'_page_ARForms-entries', array(&$this, 'head'));


    }


    function head(){


        global $style_settings, $armainhelper;


        $css_file = array($armainhelper->jquery_css_url($style_settings->arfcalthemecss));


        require(VIEWS_PATH . '/head.php');


    }


    function admin_js(){


        if (isset($_GET) and isset($_GET['page']) and ($_GET['page'] == 'ARForms-entries' or $_GET['page'] == 'ARForms-entry-templates' or $_GET['page'] == 'ARForms-import')){



            if(!function_exists('wp_editor')){


                    add_action( 'admin_print_footer_scripts', 'wp_tiny_mce', 25 );


                add_filter('tiny_mce_before_init', array(&$this, 'remove_fullscreen'));


                if ( user_can_richedit() ){


            	    wp_enqueue_script('editor');


            	    wp_enqueue_script('media-upload');


            	}


            	wp_enqueue_script('common');


            	wp_enqueue_script('post');


        	}


        	if($_GET['page'] == 'ARForms-entries')


        	    wp_enqueue_script('jquery-ui-datepicker');


        }


    }


    function remove_fullscreen($init){


        if(isset($init['plugins'])){


            $init['plugins'] = str_replace('wpfullscreen,', '', $init['plugins']);


            $init['plugins'] = str_replace('fullscreen,', '', $init['plugins']);

        }

        return $init;


    }



    function register_scripts(){


        global $wp_scripts, $armainhelper;


        wp_register_script('jquery-frm-rating', ARFURL . '/js/jquery.rating.min.js', array('jquery'), '3.13', true);


        wp_register_script('jquery-star-metadata', ARFURL . '/js/jquery.MetaData.js', array('jquery'), '', true);


        wp_register_script('jquery-maskedinput', ARFURL . '/js/jquery.maskedinput.min.js', array('jquery'), '1.3', true);

        wp_register_script('jquery-frmtimepicker', ARFURL . '/js/jquery.timePicker.min.js', array('jquery'), '0.3', true);



        if(!isset($wp_scripts->registered) or !isset( $wp_scripts->registered['jquery-ui-datepicker'])){


            $date_ver = $armainhelper->datepicker_version();


            wp_register_script('jquery-ui-datepicker', ARFURL . '/js/jquery.ui.datepicker'. $date_ver .'.js', array('jquery', 'jquery-ui-core'), empty($date_ver) ? '1.8.16' : trim($date_ver, '.'), true);


        }


    }



    function add_js(){        


        if(is_admin())


            return;


        global $arfsettings;


        if($arfsettings->accordion_js){


            wp_enqueue_script('jquery-ui-widget');


            wp_enqueue_script('jquery-ui-accordion', ARFURL.'/js/jquery.ui.accordion.js', array('jquery', 'jquery-ui-core'), '1.8.16', true);


        }


    }

	function &filter_email_value($value, $meta, $entry, $atts=array()){


        global $arffield;


        


        $field = $arffield->getOne($meta->field_id);


        if(!$field)


            return $value; 


            


        $value = $this->filter_entry_display_value($value, $field, $atts);


        return $value;


    }

    function footer_js($preview=false){


        global $arfrtloaded, $arfdatepickerloaded, $arftimepickerloaded, $arfstarloaded;


        global $arfhiddenfields, $arfforms_loaded, $arfcalcfields, $arfrules, $arfinputmasks;


        if(empty($arfforms_loaded))


            return;


        $form_ids = '';


        foreach($arfforms_loaded as $form){


            if(!is_object($form))


                continue;



            if($form_ids != '')


                $form_ids .= ',';


            $form_ids .= '#form_'. $form->form_key;


        }



        $scripts = array('arforms');


        if(!empty($arfdatepickerloaded))


            $scripts[] = 'jquery-ui-datepicker';


        if(!empty($arftimepickerloaded))


            $scripts[] = 'jquery-frmtimepicker';


        if($arfstarloaded){ 


            $scripts[] = 'jquery-frm-rating';


            if(is_array($arfstarloaded) and in_array('split', $arfstarloaded))


                $scripts[] = 'jquery-star-metadata'; 


        }



        $arfinputmasks = apply_filters('arfinputmasks', $arfinputmasks, $arfforms_loaded);


        if(!empty($arfinputmasks)) 


            $scripts[] = 'jquery-maskedinput';


        if(!empty($scripts)){


            global $wp_scripts;


            $wp_scripts->do_items( $scripts );


        }



        unset($scripts);


        include_once(VIEWS_PATH.'/common.php');


    }

    function list_entries(){


        $params = $this->get_params();


        return $this->display_list($params);


    }

    function create(){


        global $arfform, $db_record;


        $params = $this->get_params();


        if($params['form'])


            $form = $arfform->getOne($params['form']);


        $errors = $db_record->validate($_POST);


        if( count($errors) > 0 ){


            $this->get_new_vars($errors, $form);


        }else{


            if (isset($_POST['arfpageorder'.$form->id])){


                $this->get_new_vars('', $form); 


            }else{


                $_SERVER['REQUEST_URI'] = str_replace('&arfaction=new', '', $_SERVER['REQUEST_URI']);


                $record = $db_record->create( $_POST );

			
                if ($record)


                    $message = __('Entry is Successfully Created', 'ARForms');


                $this->display_list($params, $message, '', 1);


            }


        }


    }

    function destroy(){


        if(!current_user_can('arfdeleteentries')){


            global $arfsettings;


            wp_die($arfsettings->admin_permission);


        }


        global $db_record, $arfform;


        $params = $this->get_params();


        if($params['form'])


            $form = $arfform->getOne($params['form']);


        $message = '';    


        if ($db_record->destroy( $params['id'] ))


            $message = __('Entry is Successfully Deleted', 'ARForms');


        $this->display_list($params, $message, '', 1);


    }


    function destroy_all(){


        if(!current_user_can('arfdeleteentries')){


            global $arfsettings;


            wp_die($arfsettings->admin_permission);


        }


        global $db_record, $arfform, $MdlDb;


        $params = $this->get_params();


        $message = '';   


        $errors = array();


        if($params['form']){


            $form = $arfform->getOne($params['form']);


            $entry_ids = $MdlDb->get_col($MdlDb->entries, array('form_id' => $form->id));


            foreach($entry_ids as $entry_id){


                if ($db_record->destroy( $entry_id ))


                    $message = __('Entries were Successfully Destroyed', 'ARForms');


            }


        }else{


            $errors = __('No entries were specified', 'ARForms');


        }


        $this->display_list($params, $message, '', 0, $errors);


    }


    function bulk_actions($action='list-form'){


        global $db_record, $arfsettings, $armainhelper;


        $params = $this->get_params();


        $errors = array();


        $bulkaction = '-1';


        if($action == 'list-form'){


            if($_REQUEST['bulkaction'] != '-1')


                $bulkaction = $_REQUEST['bulkaction'];


            else if($_POST['bulkaction2'] != '-1')


                $bulkaction = $_REQUEST['bulkaction2'];


        }else{


            $bulkaction = str_replace('bulk_', '', $action);


        }


        $items = $armainhelper->get_param('item-action', '');


        if (empty($items)){


            $errors[] = __('Please select one or more records.', 'ARForms');


        }else{


            if(!is_array($items))


                $items = explode(',', $items);


            if($bulkaction == 'delete'){


                if(!current_user_can('arfdeleteentries')){


                    $errors[] = $arfsettings->admin_permission;


                }else{


                    if(is_array($items)){


                        foreach($items as $entry_id)


                            $db_record->destroy($entry_id);


                    }


                }


            }else if($bulkaction == 'csv'){


                if(!current_user_can('arfviewentries'))


                    wp_die($arfsettings->admin_permission);





                global $arfform;


                $form_id = $params['form'];


                if($form_id){


                    $form = $arfform->getOne($form_id);


                }else{


                    $form = $arfform->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1');


                    if($form)


                        $form_id = $form->id;


                    else


                        $errors[] = __('No form is found', 'ARForms');


                }


                if($form_id and is_array($items)){


                    echo '<script type="text/javascript">window.onload=function(){location.href="'.site_url().'/index.php?plugin=ARForms&controller=entries&form='. $form_id .'&arfaction=csv&entry_id='. implode(',', $items) .'";}</script>';


                }


            }


        }


        $this->display_list($params, '', false, false, $errors);


    }

	function show_form_popup($id='', $key='', $title=false, $description=false, $desc = '' , $type = 'link', $modal_height='540', $modal_width='800', $position='left', $btn_angle='0', $bgcolor = '', $txtcolor = ''){


        global $arfform, $user_ID, $arfsettings, $post, $wpdb, $armainhelper, $arrecordcontroller;


        if ($id) $form = $arfform->getOne((int)$id);

        else if ($key) $form = $arfform->getOne($key);
				 
        $form = apply_filters('arfpredisplayform', $form);
		
		
        $res = $wpdb->get_results( $wpdb->prepare( "SELECT is_enable FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $id ), 'ARRAY_A' );
		
		if( ( isset( $form )  and !empty( $form ) ) and ( @$form->is_template or @$form->status == 'draft') )
		{
			return __('Please select a valid form', 'ARForms');
		}
        else if(!$form or 


            (($form->is_template or $form->status == 'draft') and !isset($_GET) and !isset($_GET['form']) )){

            return __('Please select a valid form', 'ARForms');


        } else if( !isset($_GET['form']) and $post and $res[0]['is_enable'] == '0' ){
			
			return __('This form is currently not active, please try again later.', 'ARForms');
		
		} else if ($form->is_loggedin and !$user_ID){


            global $arfsettings;


            return do_shortcode($arfsettings->login_msg);


        }





        if($form->is_loggedin and $user_ID and isset($form->options['logged_in_role']) and $form->options['logged_in_role'] != ''){


            if($armainhelper->user_has_permission($form->options['logged_in_role'])){

				return $arrecordcontroller->get_form_popup(VIEWS_PATH.'/view-modal.php', $form, $title, $description, $desc , $type, $modal_height, $modal_width, $position, $btn_angle);


            }else{


                global $arfsettings;


                return do_shortcode($arfsettings->login_msg);


            }


        }else    

                
            return $arrecordcontroller->get_form_popup(VIEWS_PATH.'/view-modal.php', $form, $title, $description, $desc , $type, $modal_height, $modal_width, $position, $btn_angle, $bgcolor,$txtcolor);


    }
	
	function get_form_popup($filename, $form, $title, $description, $desc , $type, $modal_height, $modal_width, $position, $btn_angle,  $bgcolor,$txtcolor) {

        wp_print_styles('arfbootstrap-css');
        wp_print_styles('arfdisplaycss');
        wp_print_scripts('jquery-validation');	
        wp_print_scripts('arfbootstrap-js');
		
        if (is_file($filename)) {

            $contents = '';
            ob_start();
            
            if( $bgcolor == ''){
            
                if( $type == 'fly' ){
                
                    $bgcolor = ($position == 'left') ? '#2d6dae' : '#8ccf7a';
                    
                } else if( $type == 'sticky'){
                    
                    $bgcolor = ( in_array($position,array('right','bottom','left'))) ? '#1bbae1' : '#93979d';
                    
                }
            
            }
            
            if( $txtcolor == '')
                $txtcolor = '#ffffff';
            
            
            
            $contents .= "<style type='text/css'>";
                $contents .= "#arf-popup-form-{$form->id} .arf_fly_sticky_btn{";
                    $contents .= "background:{$bgcolor};";
                    $contents .= "color:{$txtcolor};";
                $contents .= "}";
            $contents .= "</style>";
            
            include $filename;


            $contents .= ob_get_contents();


            ob_end_clean();

	
            return $contents;
           

        }


        return false;


    }
	
	function process_update_entry($params, $errors, $form){

        global $db_record, $arfsavedentries, $arfcreatedentry,$arfsettings;


        $form->options = stripslashes_deep(maybe_unserialize($form->options));


        if($params['action'] == 'update' and in_array((int)$params['id'], (array)$arfsavedentries))


            return;



        if($params['action'] == 'create' and isset($arfcreatedentry[$form->id]) and isset($arfcreatedentry[$form->id]['entry_id']) and is_numeric($arfcreatedentry[$form->id]['entry_id'])){


            $entry_id = $params['id'] = $arfcreatedentry[$form->id]['entry_id'];

            $conf_method = apply_filters('arfformsubmitsuccess', 'message', $form, $form->options);


            if ($conf_method == 'redirect'){


                $success_url = apply_filters('arfcontent', $form->options['success_url'], $form, $entry_id);

				if($arfsettings->form_submit_type == 1)
				{
					$return["conf_method"] = "redirect";
					$return["message"] = $success_url;
					echo json_encode($return);
					exit;
				}
				else
				{
					wp_redirect( $success_url );
				}
                exit;


            }


        }else if ($params['action'] == 'destroy'){

            $this->ajax_destroy($form->id, false, false);


        }


    }


    function show_responses($id, $fields, $form, $title=false,$description=false, $message='', $errors=''){


        global $arfform, $arffield, $db_record, $arfaentry, $arfrecordmeta, $user_ID, $style_settings, $arfnextpage, $arfprevpage, $arfloadcss, $armainhelper;


        if(is_object($id)){


            $item = $id;


            $id = $item->id;


        }else


            $item = $db_record->getOne($id, true);


        $values = $armainhelper->setup_edit_vars($item, 'entries', $fields);


        if($values['custom_style']) $arfloadcss = true;


        $submit = (isset($arfnextpage[$form->id])) ? $arfnextpage[$form->id] : (isset($values['edit_value']) ? $values['edit_value'] : $style_settings->update_value);


        if(!isset($arfprevpage[$form->id]) and isset($_POST['item_meta']) and empty($errors) and $form->id == $armainhelper->get_param('form_id')){


            $form->options = stripslashes_deep(maybe_unserialize($form->options));


            $conf_method = apply_filters('arfformsubmitsuccess', 'message', $form);


            if ($conf_method != 'message')


                do_action('arfformsubmissionsuccessaction', $conf_method, $form, $form->options, $id);


        }else if(isset($arfprevpage[$form->id]) or !empty($errors)){


            $jump_to_form = true;


        }
    }


    function ajax_submit_button($form, $action='create'){


        global $arfnovalidate;



        if($arfnovalidate)


            echo ' formnovalidate="formnovalidate"';
        
    }


    function get_confirmation_method($method, $form){


        $method = (isset($form->options['success_action']) and !empty($form->options['success_action'])) ? $form->options['success_action'] : $method;


        return $method;


    }



    function confirmation($method, $form, $form_options, $entry_id){


        if($method == 'page' and is_numeric($form_options['success_page_id'])){


            global $post, $arfsettings;


            if($form_options['success_page_id'] != $post->ID){


                $page = get_post($form_options['success_page_id']);


                $old_post = $post;


                $post = $page;


                $content = apply_filters('arfcontent', $page->post_content, $form, $entry_id);

				$return["message"] = $content;
				
                $post = $old_post;
			
				if($arfsettings->form_submit_type != 1) {
					echo "<script type='text/javascript'>
						jQuery(document).ready(function(){
							popup_tb_show('".$form->id."');
						});    
					</script>";
				}
			
            }
			
        }else if($method == 'redirect'){


            $success_url = apply_filters('arfcontent', $form_options['success_url'], $form, $entry_id);


            $success_msg = isset($form_options['success_msg']) ? stripslashes($form_options['success_msg']) : __('Please wait while you are redirected.', 'ARForms'); 

            echo "<script type='text/javascript'> jQuery(document).ready(function($){ setTimeout(window.location='". $success_url ."', 5000); });</script>";


        }
		
		return $return["message"];

    }
	
    function csv($all_form_id, $search = '', $fid = ''){
			
        if(!current_user_can('arfviewentries')){


            global $arfsettings;


            wp_die($arfsettings->admin_permission);


        }


        if( !ini_get('safe_mode') ){


            set_time_limit(0); 


        }


        global $current_user, $arfform, $arffield, $db_record, $arfrecordmeta, $wpdb, $style_settings;
		

        require(VIEWS_PATH.'/export_data.php');


    }

    function display_list($params=false, $message='', $page_params_ov = false, $current_page_ov = false, $errors = array()){


        global $wpdb, $MdlDb, $armainhelper, $arfform, $db_record, $arfrecordmeta, $arfpagesize, $arffield, $arfcurrentform;


        if(!$params)


            $params = $this->get_params();


        $errors = array();



        $form_select = $arfform->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name');



        if($params['form'])


            $form = $arfform->getOne($params['form']);


        else


            $form = (isset($form_select[0])) ? $form_select[0] : 0;



        if($form){


            $params['form'] = $form->id;


            $arfcurrentform = $form;


	        $where_clause = " it.form_id=$form->id";


        }else{


            $where_clause = '';


		}

        


        $page_params = "&action=0&arfaction=0&form=";


        $page_params .= ($form) ? $form->id : 0;


        


        if ( ! empty( $_REQUEST['s'] ) )


            $page_params .= '&s='. urlencode($_REQUEST['s']);


        


        if ( ! empty( $_REQUEST['search'] ) )


            $page_params .= '&search='. urlencode($_REQUEST['search']);





    	if ( ! empty( $_REQUEST['fid'] ) )


    	    $page_params .= '&fid='. $_REQUEST['fid'];


        


        
        	
		    $item_vars = $this->get_sort_vars($params, $where_clause);
							

    		$page_params .= ($page_params_ov) ? $page_params_ov : $item_vars['page_params'];



            if($form){


    			$form_cols = $arffield->getAll("fi.type not in ('divider', 'captcha', 'break', 'html', 'imagecontrol') and fi.form_id=". (int)$form->id, 'field_order ASC', '');


    	        $record_where = ($item_vars['where_clause'] == " it.form_id=$form->id") ? $form->id : $item_vars['where_clause'];


    	    }else{


    	        $form_cols = array();


    	        $record_where = $item_vars['where_clause'];


    	    }
			
			$current_page = ($current_page_ov) ? $current_page_ov: $params['paged'];

            $sort_str = $item_vars['sort_str'];


            $sdir_str = $item_vars['sdir_str'];


            $search_str = $item_vars['search_str'];

            $fid = $item_vars['fid'];

            $record_count = $db_record->getRecordCount($record_where);

            $page_count = $db_record->getPageCount($arfpagesize, $record_count);
			
            $items = $db_record->getPage('','', $item_vars['where_clause'], $item_vars['order_by']);

            $page_last_record = $armainhelper->getLastRecordNum($record_count, $current_page, $arfpagesize);

            $page_first_record = $armainhelper->getFirstRecordNum($record_count, $current_page, $arfpagesize);
			
			if( isset($_REQUEST['form']) &&  $_REQUEST['form'] == '-1' or (!isset($_REQUEST['form']) or empty( $_REQUEST['form']))) {
				$form_cols 	= array();
				$items		= array();
			}	
			

        
		
        require_once(VIEWS_PATH.'/view_records.php');
    }


    


    function get_sort_vars($params=false, $where_clause = ''){


        global $arfrecordmeta, $arfcurrentform;


        


        if(!$params)


            $params = $this->get_params($arfcurrentform);


 


        $order_by = '';


        $page_params = '';


        $sort_str = $params['sort'];


        $sdir_str = $params['sdir'];


        $search_str = $params['search'];


        $fid = $params['fid'];


        if(!empty($sort_str))


            $page_params .="&sort=$sort_str";


        if(!empty($sdir_str))


            $page_params .= "&sdir=$sdir_str";



        if(!empty($search_str)){


            $where_clause = $this->get_search_str($where_clause, $search_str, $params['form'], $fid);


            $page_params .= "&search=$search_str";


            if(is_numeric($fid))


                $page_params .= "&fid=$fid";


        }


        if(is_numeric($sort_str))


            $order_by .= " ORDER BY ID"; 


        else if ($sort_str == "entry_key")


            $order_by .= " ORDER BY entry_key";


        else


            $order_by .= " ORDER BY ID";





        if((empty($sort_str) and empty($sdir_str)) or $sdir_str == 'desc'){


            $order_by .= ' DESC';


            $sdir_str = 'desc';


        }else{


            $order_by .= ' ASC';


            $sdir_str = 'asc';


        }


        


        return compact('order_by', 'sort_str', 'sdir_str', 'fid', 'search_str', 'where_clause', 'page_params');


    }


    


    function get_search_str($where_clause='', $search_str, $form_id=false, $fid=false){


        global $arfrecordmeta, $armainhelper, $arfform;


        $where_item = '';


        $join = ' (';


        if(!is_array($search_str))


            $search_str = explode(" ", $search_str);



        foreach($search_str as $search_param){


            $search_param = esc_sql( like_escape( $search_param ) );


            if(!is_numeric($fid)){


                $where_item .= (empty($where_item)) ? ' (' : ' OR';
                    


                if(in_array($fid, array('created_date', 'user_id'))){


                    if($fid == 'user_id' and !is_numeric($search_param))


                        $search_param = $armainhelper->get_user_id_param($search_param);


                    $where_item .= " it.{$fid} like '%$search_param%'";


                }else{


                    $where_item .= " it.name like '%$search_param%' OR it.entry_key like '%$search_param%' OR it.description like '%$search_param%' OR it.created_date like '%$search_param%'";


                }


            }


            if(empty($fid) or is_numeric($fid)){


                $where_entries = "(entry_value LIKE '%$search_param%'";


                if($data_fields = $arfform->has_field('data', $form_id, false)){


                    $df_form_ids = array();


                    foreach((array)$data_fields as $df){


                        $df->field_options = maybe_unserialize($df->field_options);


                        if (is_numeric($df->field_options['form_select']))


                            $df_form_ids[] = $df->field_options['form_select'];


                        unset($df);


                    }


                    


                    unset($data_fields);


                    global $wpdb, $MdlDb;


                    $data_form_ids = $wpdb->get_col("SELECT form_id FROM $MdlDb->fields WHERE id in (". implode(',', $df_form_ids).")");


                    unset($df_form_ids);


                    if($data_form_ids){


                        $data_entry_ids = $arfrecordmeta->getEntryIds("fi.form_id in (". implode(',', $data_form_ids).") and entry_value LIKE '%". $search_param ."%'");


                        if(!empty($data_entry_ids))


                            $where_entries .= " OR entry_value in (".implode(',', $data_entry_ids).")";


                    }


                    unset($data_form_ids);


                }



                $where_entries .= ")";


                if(is_numeric($fid))


                    $where_entries .= " AND fi.id=$fid";



                $meta_ids = $arfrecordmeta->getEntryIds($where_entries);


                if (!empty($meta_ids)){


                    if(!empty($where_clause)){


                        $where_clause .= " AND" . $join;


                        if(!empty($join)) $join = '';


                    }


                    $where_clause .= " it.id in (".implode(',', $meta_ids).")";


                }else{


                    if(!empty($where_clause)){


                        $where_clause .= " AND" . $join;


                        if(!empty($join)) $join = '';


                    }


                    $where_clause .= " it.id=0";


                }


            }


        }


        


        if(!empty($where_item)){


            $where_item .= ')';


            if(!empty($where_clause))


                $where_clause .= empty($fid) ? ' OR' : ' AND';


            $where_clause .= $where_item;


            if(empty($join))


                $where_clause .= ')';


        }else{


            if(empty($join))


                $where_clause .= ')';


        }





        return $where_clause;


    }





    function get_new_vars($errors = '', $form = '',$message = ''){


        global $arfform, $arffield, $db_record, $arfsettings, $arfnextpage, $arfieldhelper;


        $title = true;


        $description = true;


        $fields = $arfieldhelper->get_all_form_fields($form->id, !empty($errors));


        $values = $arrecordhelper->setup_new_vars($fields, $form);


        $submit = (isset($arfnextpage[$form->id])) ? $arfnextpage[$form->id] : (isset($values['submit_value']) ? $values['submit_value'] : $arfsettings->submit_value);  


        require_once(VIEWS_PATH.'/new.php');


    }


    function get_params($form=null){


        global $arfform, $armainhelper;


        if(!$form)


            $form = $arfform->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1');


        $values = array();


        foreach (array('id' => '', 'form_name' => '', 'paged' => 1, 'form' => (($form) ? $form->id : 0), 'field_id' => '', 'search' => '', 'sort' => '', 'sdir' => '', 'fid' => '') as $var => $default)


            $values[$var] = $armainhelper->get_param($var, $default);

        return $values;


    }



    function &filter_shortcode_value($value, $tag, $atts, $field){


        if(isset($atts['show']) and $atts['show'] == 'value')


            return $value;


        $value = $this->filter_display_value($value, $field);


        return $value;


    }
	
	function &filter_entry_display_value($value, $field, $atts=array()){


        $field->field_options = maybe_unserialize($field->field_options);


        


        $saved_value = (isset($atts['saved_value']) and $atts['saved_value']) ? true : false;


        if(!in_array($field->type, array( 'checkbox')) or !isset($field->field_options['separate_value']) or !$field->field_options['separate_value'] or $saved_value)


            return $value;


            


        $field->options = maybe_unserialize($field->options);


        $f_values = array();


        $f_labels = array();


        foreach($field->options as $opt_key => $opt){


            if(!is_array($opt))


                continue;


            


            $f_labels[$opt_key] = isset($opt['label']) ? $opt['label'] : reset($opt);


            $f_values[$opt_key] = isset($opt['value']) ? $opt['value'] : $f_labels[$opt_key];


            if($f_labels[$opt_key] == $f_values[$opt_key]){


                unset($f_values[$opt_key]);


                unset($f_labels[$opt_key]);


            }


            unset($opt_key);


            unset($opt);


        }





        if(!empty($f_values)){


            foreach((array)$value as $v_key => $val){


                if(in_array($val, $f_values)){


                    $opt = array_search($val, $f_values);


                    if(is_array($value))


                        $value[$v_key] = $f_labels[$opt];


                    else


                        $value = $f_labels[$opt];


                }


                unset($v_key);


                unset($val);


            }


        }


        


        return $value;


    }
	

    function &filter_display_value($value, $field){
		global $arrecordcontroller;
        $value = $arrecordcontroller->filter_entry_display_value($value, $field);


        return $value;


    }


    function route(){

		global $armainhelper; 
        $action = $armainhelper->get_param('arfaction');
		
		if($action == 'create')


            return $this->create();

        else if($action == 'destroy')


            return $this->destroy();


        else if($action == 'destroy_all')


            return $this->destroy_all();
			
		
		else if($action == 'graph')


            return $this->display_graph();	


        else if($action == 'list-form')


            return $this->bulk_actions($action);


        else{


            $action = $armainhelper->get_param('action');


            if($action == -1)


                $action = $armainhelper->get_param('action2');


            if(strpos($action, 'bulk_') === 0){


                if(isset($_GET) and isset($_GET['action']))


                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action'], '', $_SERVER['REQUEST_URI']);


                if(isset($_GET) and isset($_GET['action2']))


                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action2'], '', $_SERVER['REQUEST_URI']);


                return $this->bulk_actions($action);


            }else{


                return $this->display_list();


            }


        }


    }
	
	function get_form($filename, $form, $title, $description, $preview=false, $is_widget_or_modal=false, $is_confirmation_method=false) {
		
		global $arfsettings;
		
		if($arfsettings->form_submit_type != 1)
		{
			wp_print_styles('arfbootstrap-css');
			wp_print_styles('arfdisplaycss');
			wp_print_scripts('jquery-validation');	
			wp_print_scripts('arfbootstrap-js');
		}
		
        if (is_file($filename)) {


            ob_start();


            include $filename;


            $contents = ob_get_contents();


            ob_end_clean();
			
			return $contents;
			
        }


        return false;


    }

   

    function ajax_create(){

		
        global $db_record;

        $errors = $db_record->validate($_POST, array('file'));
		
		
        if(empty($errors)){


            echo false;


        }else{


            $errors = str_replace('"', '&quot;', stripslashes_deep($errors));


            $obj = array();


            foreach($errors as $field => $error){


                $field_id = str_replace('field', '', $field);


                $obj[$field_id] = $error;


            }


            echo json_encode($obj);


        }


        die();


    }


    


    function ajax_update(){


        return $this->ajax_create();


    }


    


    function ajax_destroy($form_id=false, $ajax=true, $echo=true){


        global $user_ID, $MdlDb, $db_record, $arfdeletedentries, $armainhelper;



        $entry_key = $armainhelper->get_param('entry');


        if(!$form_id)


            $form_id = $armainhelper->get_param('form_id');


        if(!$entry_key)


            return;



        if(is_array($arfdeletedentries) and in_array($entry_key, $arfdeletedentries))


            return;


            


        $where = array();


        if(!current_user_can('arfdeleteentries'))


            $where['user_id'] = $user_ID;


            


        if(is_numeric($entry_key))


            $where['id'] = $entry_key;


        else


            $where['entry_key'] = $entry_key;



        $entry = $MdlDb->get_one_record( $MdlDb->entries, $where, 'id, form_id' );



        if($form_id and $entry->form_id != (int)$form_id)


            return;


        


        $entry_id = $entry->id;



        apply_filters('arfallowdelete', $entry_id, $entry_key, $form_id);


        if(!$entry_id){


            $message = __('There is an error deleting that entry', 'ARForms');


            if($echo)


                echo '<div class="frm_message">'. $message .'</div>';


        }else{


            $db_record->destroy( $entry_id );


            if(!$arfdeletedentries)


                $arfdeletedentries = array();


            $arfdeletedentries[] = $entry_id;


            


            if($ajax){


                if($echo)


                    echo $message = 'success';


            }else{


                $message = __('Your entry is successfully deleted', 'ARForms');


                


                if($echo)


                    echo '<div class="frm_message">'. $message .'</div>';


            }


        }


        return $message;


    }

    function send_email($entry_id, $form_id, $type){

		global $arnotifymodel;
		
        if(current_user_can('arfviewforms') or current_user_can('arfeditforms')){


            if($type=='autoresponder')


                $sent_to = $arnotifymodel->autoresponder($entry_id, $form_id);


            else


                $sent_to = $arnotifymodel->entry_created($entry_id, $form_id);


            


            if(is_array($sent_to))


                echo implode(',', $sent_to);


            else


                echo $sent_to;


        }else{


            _e('No one! You do not have permission', 'ARForms');


        }


    }


	function display_graph(){
	
		$form = $_REQUEST['form'];
		require_once(VIEWS_PATH.'/graph.php');
	
	}
	
	function updatechart(){
	
		$form = $_POST['form'];
		$type = $_POST['type'];
		require_once(VIEWS_PATH.'/graph_ajax.php');
	
	die();
	}    
	
	
	function managecolumns(){
	
	global $wpdb;
	
	$form = $_POST['form'];
	
	$colsArray = $_POST['colsArray'];
	
	$new_arr = explode(',', $colsArray);
			
	$array_hidden = array();
	
	foreach( $new_arr as $key => $val ) {
	
	if( $key % 2 == 0 ) {
	
		if( $new_arr[$key+1] == 'hidden' ) $array_hidden[] = $val;
		}
	}
	
	$ser_arr = maybe_serialize($array_hidden);
	
	$wpdb->update($wpdb->prefix.'arf_forms', array( 'columns_list' => $ser_arr ), array( 'id' => $form));
	
	die();
	}
	
	
	function arfchangebulkentries(){
		
		global $armainhelper, $arrecordcontroller;
		$action = $armainhelper->get_param('action1');


            if($action == -1)

                $action = $armainhelper->get_param('action2');



            if(strpos($action, 'bulk_') === 0){


                if(isset($_GET) and isset($_GET['action1']))


                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action1'], '', $_SERVER['REQUEST_URI']);


                if(isset($_GET) and isset($_GET['action2']))


                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action2'], '', $_SERVER['REQUEST_URI']);


                    
				global $db_record, $arfsettings;


        $params = $this->get_params();

		$params['form'] = $_POST['form'];
		
        $errors = array();


        $bulkaction = '-1';


        if($action == 'list-form'){


            if($_REQUEST['bulkaction1'] != '-1')


                $bulkaction = $_REQUEST['bulkaction1'];


            else if($_POST['bulkaction2'] != '-1')


                $bulkaction = $_REQUEST['bulkaction2'];


        }else{


            $bulkaction = str_replace('bulk_', '', $action);


        }

		


        $items = $armainhelper->get_param('item-action', '');
		

        if (empty($items)){


            $errors[] = __('Please select one or more records.', 'ARForms');


        }else{


            if(!is_array($items))


                $items = explode(',', $items);


            if($bulkaction == 'delete'){


                if(!current_user_can('arfdeleteentries')){


                    $errors[] = $arfsettings->admin_permission;


                }else{


                    if(is_array($items)){


                        foreach($items as $entry_id)


                            $del_res = $db_record->destroy($entry_id);

					if($del_res) $message = __('Entries deleted successfully', 'ARForms');	
                    }


                }


            }else if($bulkaction == 'csv'){


                if(!current_user_can('arfviewentries'))


                    wp_die($arfsettings->admin_permission);





                global $arfform;


                


                $form_id = $params['form'];


                if($form_id){


                    $form = $arfform->getOne($form_id);


                }else{


                    $form = $arfform->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1');


                    if($form)


                        $form_id = $form->id;


                    else


                        $errors[] = __('No form is found', 'ARForms');


                }


                


                if($form_id and is_array($items)){
						
					echo '<script type="text/javascript">location.href="'.site_url().'/index.php?plugin=ARForms&controller=entries&form='. $form_id .'&arfaction=csv&entry_id='. implode(',', $items) .'";</script>';	

                }


            }


        }
			
			$arrecordcontroller->frm_change_entries($_POST['form'], $_POST['start_date'], $_POST['end_date'], '1', @$message, @$errors);
				

            }else{
				
			 $items = $armainhelper->get_param('item-action', '');
		
	        if (empty($items)){

    	        $errors[] = __('Please select one or more records.', 'ARForms');

	        }

				
                return $this->frm_change_entries($_POST['form'], $_POST['start_date'], $_POST['end_date'], '1', @$message, @$errors);


            }

	
	
	
		
	die();
	}
	
	
	
	function frm_change_entries($new_form_id='', $new_start_date='', $new_end_date='', $bulk='', $message='', $errors=''){
		
		global $wpdb, $MdlDb, $armainhelper, $arfform, $db_record, $arfrecordmeta, $arfpagesize, $arffield, $arfcurrentform, $arformhelper, $arrecordcontroller;
		
		if(isset($bulk) && $bulk =='1') {
			$new_form_id = $new_form_id;
			$new_start_date = $new_start_date;
			$new_end_date = $new_end_date;
		} else {
			$new_form_id = $_POST['form'];
			$new_start_date = $_POST['start_date'];
			$new_end_date = $_POST['end_date'];
		}
				
		if( !isset($new_form_id) && $new_form_id == '' )
			
			$new_form_id == '-1';
		
		
        if(empty($params) || !$params)

            $params = $this->get_params();   


        
		$params['form'] = $new_form_id; 
		

        $form_select = $arfform->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name');



        if($params['form'])

            $form = $arfform->getOne($params['form']);

        else

            $form = (isset($form_select[0])) ? $form_select[0] : 0;


        

        if($form){


            $params['form'] = $form->id;


            $arfcurrentform = $form;


	        $where_clause = " it.form_id=$form->id";


        }else{


            $where_clause = '';


		}

        


        $page_params = "&action=0&arfaction=0&form=";


        $page_params .= ($form) ? $form->id : 0;



    	if ( ! empty( $_REQUEST['fid'] ) )


    	    $page_params .= '&fid='. $_REQUEST['fid'];


        
		    $item_vars = $this->get_sort_vars($params, $where_clause);
							

    		$page_params .= ( isset($page_params_ov) ) ? $page_params_ov : $item_vars['page_params'];



            if($form){


    			$form_cols = $arffield->getAll("fi.type not in ('divider', 'captcha', 'break', 'html', 'imagecontrol') and fi.form_id=". (int)$form->id, 'field_order ASC', '');
				
    	        $record_where = ($item_vars['where_clause'] == " it.form_id=$form->id") ? $form->id : $item_vars['where_clause'];


    	    }else{


    	        $form_cols = array();


    	        $record_where = $item_vars['where_clause'];


    	    }
							

            $current_page = ( isset($current_page_ov) ) ? $current_page_ov: $params['paged'];

            $sort_str = $item_vars['sort_str'];


            $sdir_str = $item_vars['sdir_str'];


            $search_str = $item_vars['search_str'];

            $fid = $item_vars['fid'];

            $record_count = $db_record->getRecordCount($record_where);

            $page_count = $db_record->getPageCount($arfpagesize, $record_count);
			
			global $style_settings, $wp_scripts;
			$wp_format_date = get_option('date_format');
	
			if( $wp_format_date == 'F j, Y' || $wp_format_date =='m/d/Y' ) {
				$date_format_new = 'mm/dd/yy';
			} else if( $wp_format_date == 'd/m/Y' ) {
				$date_format_new = 'dd/mm/yy';
			} else if( $wp_format_date == 'Y/m/d' ) {
				$date_format_new = 'dd/mm/yy';
			} else {
				$date_format_new = 'mm/dd/yy';
			}
			
			$show_new_start_date = $new_start_date;
			$show_new_end_date = $new_end_date;
			
			if( $new_start_date != '' and $new_end_date != '' ) {
				if($date_format_new=='dd/mm/yy')
				{
					$new_start_date = str_replace('/', '-', $new_start_date);
					$new_end_date = str_replace('/', '-', $new_end_date);
				}
				$new_start_date_var = date('Y-m-d', strtotime($new_start_date));
				
				$new_end_date_var = date('Y-m-d', strtotime($new_end_date));
				
				$item_vars['where_clause'] .= " and DATE(it.created_date) >= '".$new_start_date_var."' and DATE(it.created_date) <= '".$new_end_date_var."'";
			
			} else if( $new_start_date != '' and $new_end_date == '' ) {
				if($date_format_new=='dd/mm/yy')
				{
					$new_start_date = str_replace('/', '-', $new_start_date);
				}
				$new_start_date_var = date('Y-m-d', strtotime($new_start_date));
				
				$item_vars['where_clause'] .= " and DATE(it.created_date) >= '".$new_start_date_var."'";
			
			} else if( $new_start_date == '' and $new_end_date != '' ) {
				if($date_format_new=='dd/mm/yy')
				{
					$new_end_date = str_replace('/', '-', $new_end_date);
				}	
				$new_end_date_var = date('Y-m-d', strtotime($new_end_date));
				
				$item_vars['where_clause'] .= " and DATE(it.created_date) <= '".$new_end_date_var."'";
			
			}
			
			
			
			
            $items = $db_record->getPage('', '', $item_vars['where_clause'], $item_vars['order_by']);
			
		    $page_last_record = $armainhelper->getLastRecordNum($record_count, $current_page, $arfpagesize);

            $page_first_record = $armainhelper->getFirstRecordNum($record_count, $current_page, $arfpagesize);
			
			if( (isset($new_form_id) && $new_form_id == '-1') || ( empty($new_form_id) || empty($form->id) ) ) {				
				$form_cols 	= array();
				$items		= array();
			}
							
        if($form->id != '-1' || $form->id !=''){
		
		$form_cols	= apply_filters('arfpredisplayformcols', $form_cols, $form->id);
		$items		= apply_filters('arfpredisplaycolsitems', $items, $form->id);		

		$action_no = 0;
		
		$default_hide = array(
						'0' => '',
						'1' => 'ID',
						);
		if( count($form_cols) > 0 ){
		
			for($i=2; 1+count($form_cols) >= $i; $i++){
				$j = $i-2;
				$default_hide[$i] = $armainhelper->truncate($form_cols[$j]->name, 40); 	
			}
			$default_hide[$i] = 'Entry Key';
			$default_hide[$i+1] = 'Entry creation date';
			$default_hide[$i+2] = 'Browser Name';
			$default_hide[$i+3] = 'IP Address';
			$default_hide[$i+4] = 'Country';
			$default_hide[$i+5] = 'Action';
			$action_no = $i+5;
		}
		else
		{
			$default_hide['2'] = 'Entry Key';
			$default_hide['3'] = 'Entry creation date';
			$default_hide['4'] = 'Browser Name';
			$default_hide['5'] = 'IP Address';
			$default_hide['6'] = 'Country';
			$default_hide['7'] = 'Action';
			$action_no = 7;
		}
		
		
		$columns_list_res = $wpdb->get_results( $wpdb->prepare('SELECT columns_list FROM '.$wpdb->prefix.'arf_forms WHERE id = %d', $form->id), ARRAY_A);
		$columns_list_res = $columns_list_res[0];
		
		$columns_list = maybe_unserialize($columns_list_res['columns_list']);
		$is_colmn_array = is_array($columns_list);
		
		$exclude = '';
		
		$exclude_array = "";	
			if( count($columns_list) > 0 and $columns_list != '' ) {
			
				foreach($columns_list as $keys => $column){
				
					foreach($default_hide as $key => $val ){

						if($column == $val)
						{
							if($exclude_array=="")
							{
								$exclude_array[] = $key;
							}
							else
							{
								if(!in_array($key,$exclude_array)){
									$exclude_array[] = $key;
									
									$exclude_no++;
								}
							}
						}
					}
				
				}
			}
			
			$ipcolumn = ($action_no - 2);	 
			if( $exclude_array=="" and !$is_colmn_array )
				$exclude_array = array($ipcolumn);
			else if( is_array($exclude_array) and !in_array($ipcolumn, $exclude_array) and !$is_colmn_array )	
				array_push($exclude_array, $ipcolumn);
					
		}
		
		if($exclude_array!="")
		{
			$exclude = implode(",",$exclude_array);
		}	
		
		$actions = array( 'bulk_delete' => __('Delete', 'ARForms'));

		$actions['bulk_csv'] = __('Export to CSV', 'ARForms');

	global $style_settings, $wp_scripts;
	$wp_format_date = get_option('date_format');
					
							if( $wp_format_date == 'F j, Y' || $wp_format_date =='m/d/Y' ) {
								$date_format_new = 'mm/dd/yy';
							} else if( $wp_format_date == 'd/m/Y' ) {
								$date_format_new = 'dd/mm/yy';
							} else if( $wp_format_date == 'Y/m/d' ) {
								$date_format_new = 'dd/mm/yy';
							} else {
								$date_format_new = 'mm/dd/yy';
							}

	global $arf_entries_action_column_width;
	?>
    <script type="text/javascript" charset="utf-8">
	// <![CDATA[
	jQuery(document).ready( function () {
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional['']);

	jQuery("#datepicker_from").datepicker(jQuery.extend(jQuery.datepicker.regional['<?php echo (isset($options['locale'])) ? $options['locale'] : ''; ?>'], {dateFormat:'<?php echo $date_format_new; ?>',changeMonth:false,changeYear:false,yearRange:'<?php echo '1970' .':'. '2050' ?>'}));

	jQuery("#datepicker_to").datepicker(jQuery.extend(jQuery.datepicker.regional['<?php echo (isset($options['locale'])) ? $options['locale'] : ''; ?>'], {dateFormat:'<?php echo $date_format_new; ?>',changeMonth:false,changeYear:false,yearRange:'<?php echo '1970' .':'. '2050' ?>'}));	
	
		
	
		jQuery.fn.dataTableExt.oPagination.four_button = {

			"fnInit": function ( oSettings, nPaging, fnCallbackDraw )
			{	
				nFirst = document.createElement( 'span' );
				nPrevious = document.createElement( 'span' );
				
				
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
				nNext.appendChild( document.createTextNode( ' ' ) );
				nLast.appendChild( document.createTextNode( ' ' ) );
				
				 
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
					
					if(iPages == 0 && iCurrentPage == 1) iPages = iPages + 1;
			 
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
	
		var oTables = jQuery('#example').dataTable( {
			"sDom": '<"H"lCfr>t<"footer"ip>',
			"sPaginationType": "four_button",
			"bJQueryUI": true,
			"bPaginate": true,
			"bAutoWidth": false,
			
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"oColVis": {
			   "aiExclude": [ 0 , <?php echo $action_no;?>]
			},
			
			"aoColumnDefs": [
				{ "sType": "html", "bVisible": false, "aTargets": [<?php if($exclude!='') echo $exclude;?>] },
				{ "bSortable": false, "aTargets": [ 0 , <?php echo $action_no;?>] }
			],	
		});
		new FixedColumns( oTables, {
			"iLeftColumns": 0,
			"iLeftWidth": 0,
			"iRightColumns": 1,
			"iRightWidth": <?php echo isset($arf_entries_action_column_width) ? $arf_entries_action_column_width : '120'; ?>,
		} ); 
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
    
    <?php
		if(is_rtl())
		{
			$sel_frm_div = 'float:right;margin-top:15px;';
			$sel_frm_txt = 'float:right;text-align:right;width:65%;';
		}
		else
		{
			$sel_frm_div = 'float:left;margin-top:15px;';
			$sel_frm_txt = 'float:left;text-align:left;width:65%;';
		}
	?>
    <div class="arf_form_entry_select">
    <div class="arf_form_entry_select_sub">	
        <div>
            <div class="arf_form_entry_left"><?php _e('Select form','ARForms');?>:</div>
            <div style=" <?php echo $sel_frm_txt; ?>" ><div class="sltstandard" style="float:none;"><?php $arformhelper->forms_dropdown('arfredirecttolist', $new_form_id, __('Select Form', 'ARForms'), false,  "");?></div></div>
        </div>
        <?php
				if(is_rtl())
				{
					$sel_frm_date_wrap = 'float:right;text-align:right;width:65%';
					$sel_frm_sel_date = 'float:right;';
					$sel_frm_button = 'float:right;margin-top:15px;';
				}
				else
				{
					$sel_frm_date_wrap = 'float:left;text-align:left;width:65%';
					$sel_frm_sel_date = 'float:left;';
					$sel_frm_button = 'float:left;margin-top:15px;';
				}
			?>
        <div style=" <?php echo $sel_frm_div ?>">
            <div class="arf_form_entry_left"><div><?php _e('Select date From','ARForms');?>:</div><div class="arf_form_entry_left_sub">(<?php _e('optional','ARForms');?>)</div></div>
            <div style=" <?php echo $sel_frm_date_wrap; ?>">
                <div style=" <?php echo $sel_frm_sel_date; ?>"><input type="text" class="txtstandardnew" id="datepicker_from" value="<?php echo $show_new_start_date; ?>" name="datepicker_from" style="vertical-align:middle; width:105px;" /></div> <div class="arfentrytitle"><?php _e('To','ARForms');?>:</div>&nbsp;&nbsp;<div style="float:left;"><input type="text" class="txtstandardnew" id="datepicker_to" name="datepicker_to"  value="<?php echo $show_new_end_date; ?>" style="vertical-align:middle;  width:105px;"/></div>
            
        </div>
                
        <div style=" <?php echo $sel_frm_button; ?>">
            <div class="arf_form_entry_left">&nbsp;</div>
            <div style="float:left;text-align:left;"><button type="button" class="greensavebtn" style="width:103px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;" onclick="change_frm_entries();"><?php _e('Submit','ARForms');?></button></div>
        </div>        
           
        <input type="hidden" name="please_select_form" id="please_select_form" value="<?php _e('Please select a form','ARForms');?>" />
        </div>
        <div style="clear:both;"></div>
    </div>    
    </div>
  <div style="clear:both; height:30px;"></div>
					
                    
                    <?php do_action('arfbeforelistingentries'); ?>
                    
                    <form method="get" id="list_entry_form" onsubmit="return apply_bulk_action();" style="float:left;width:100%;">
                    
                    <input type="hidden" name="page" value="ARForms-entries" />
                    
                    <input type="hidden" name="form" value="<?php echo ($form) ? $form->id : '-1'; ?>" />
                    
                    <input type="hidden" name="arfaction" value="list" />
                    
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
                    
                    <?php require(VIEWS_PATH.'/shared_errors.php'); ?>    
                    
                    <div class="alignleft actions">
                            <?php 
                            $two = '1';
                            echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two' id='action$two'>\n";
                            echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms') . "</option>\n";
                    
                            foreach ( $actions as $name => $title ) {
                                $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';
                    
                                echo "\t<option value='$name'$class>$title</option>\n";
                            }
                    
                            echo "</select></div>\n";
                    		
							echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__("Apply","ARForms").'" />';	
                        
                            echo "\n";
                            
                            ?>
                    </div>                                        
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                        <thead>
                            <tr> 
                                <th class="center"><div style="display:inline-block; position:relative;"><input id="cb-select-all-1" type="checkbox" class="chkstanard"><label for="cb-select-all-1"  class="cb-select-all"><span></span></label></div></th>
                                <th><?php _e('ID','ARForms');?></th>
                                <?php if(count($form_cols) > 0) { foreach ($form_cols as $col){ ?>
                                <th><?php echo $armainhelper->truncate($col->name, 40) ?></th>
                                <?php } } ?>
                                <th><?php _e('Entry Key','ARForms');?></th>
                                <th><?php _e('Entry creation date','ARForms');?></th>
                                <th><?php _e('Browser Name','ARForms');?></th>
                                <th><?php _e('IP Address','ARForms');?></th>
                                <th><?php _e('Country','ARForms');?></th>
                                <th class="col_action"><?php _e('Action','ARForms');?></th>          
                            </tr>
                        </thead>
                        <tbody>
                    <?php if(count($items) > 0) { foreach($items as $key => $item) { ?>    
                            <tr>
                                <td class="center"><input id="cb-item-action-<?php echo $item->id;?>" class="chkstanard" type="checkbox" value="<?php echo $item->id;?>" name="item-action[]"><label for="cb-item-action-<?php echo $item->id;?>"><span></span></label></td>
                                <td><?php echo $item->id;?></td>
                    <?php 
					
					foreach ($form_cols as $col){ ?>
                    
                            <td>
                                <?php 
                    
                                $field_value = isset($item->metas[$col->id]) ? $item->metas[$col->id] : false;
                                
                    
                                $col->field_options = maybe_unserialize($col->field_options);
                                
                    			global $arrecordhelper;
                                echo $arrecordhelper->display_value($field_value, $col, array('type' => $col->type, 'truncate' => true, 'attachment_id' => $item->attachment_id, 'entry_id' => $item->id));  
                    
                                 ?>
                    
                            </td>
                    
                        <?php } ?>
                        	<td><?php echo $item->entry_key;?></td>
                            <td><?php echo date(get_option('date_format'), strtotime($item->created_date));?></td>
                            <td><?php $browser_info = $this->getBrowser($item->browser_info); echo $browser_info['name'].' (Version: '.$browser_info['version'].')'; ?></td>
                            <td><?php echo $item->ip_address;?></td>
                            <td><?php echo $item->country;?></td>
                            <td class="col_action">			
                                <div class="row-actions">  
                    
                                  <?php 
                                       
								   if(is_rtl())
								   {
								   	echo "<a href='javascript:void(0);' onclick='open_entry_thickbox({$item->id});'><img src='".ARFIMAGESURL."/view_icon23_rtl.png' title='".__("View Entry","ARForms")."' class='arfhelptip' onmouseover=\"this.src='".ARFIMAGESURL."/view_icon23_hover_rtl.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/view_icon23_rtl.png';\" /></a>"; 
								   }
								   else
								   {
								   echo "<a href='javascript:void(0);' onclick='open_entry_thickbox({$item->id});'><img src='".ARFIMAGESURL."/view_icon23.png' title='".__("View Entry","ARForms")."' class='arfhelptip' onmouseover=\"this.src='".ARFIMAGESURL."/view_icon23_hover.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/view_icon23.png';\" /></a>"; 
                    				}
									
								  do_action('arf_additional_action_entries',$item->id,$form->id);
                    
                                  $delete_link = "?page=ARForms-entries&arfaction=destroy&id={$item->id}";
                    
                    
                                  $delete_link .= "&form=".$params['form'];
                         
                    									
									$id = $item->id;
								
									if(is_rtl())
									{
										echo "<img src='".ARFIMAGESURL."/delete_icon223_rtl.png' class='arfhelptip' title=".__("Delete","ARForms")." onmouseover=\"this.src='".ARFIMAGESURL."/delete_icon223_hover_rtl.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/delete_icon223_rtl.png';\" onclick=\"ChangeID({$id}); arfchangedeletemodalwidth('arfdeletemodabox');\" data-toggle='arfmodal' href='#delete_form_message' style='cursor:pointer' /></a>";
										
										
									}
									else
									{
										echo "<img src='".ARFIMAGESURL."/delete_icon223.png' class='arfhelptip' title=".__("Delete","ARForms")." onmouseover=\"this.src='".ARFIMAGESURL."/delete_icon223_hover.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/delete_icon223.png';\" onclick=\"ChangeID({$id}); arfchangedeletemodalwidth('arfdeletemodabox');\" data-toggle='arfmodal' href='#delete_form_message' style='cursor:pointer' /></a>";
									}
                    				
									
									
									echo "<div id='view_entry_{$item->id}' style='display:none; max-height:540px; width:800px; left:20%;' class='arfviewentrymodal arfmodal arfhide arffade'>
										<div class='arfnewmodalclose' data-dismiss='arfmodal'><img src='".ARFIMAGESURL."/close-button.png' align='absmiddle' /></div>
    
                                        <div class='newform_modal_title_container'>
                                            <div class='newform_modal_title' style='text-align:center;'><img src='".ARFIMAGESURL."/view-entry-icon.png' align='absmiddle' />&nbsp;".__('VIEW ENTRY','ARForms')."</div>
                                        </div>	
    
								   		<div class='arfentry_modal_content'>".$arrecordcontroller->get_entries_list($item->id)."</div>
										
										<div style='clear:both;'></div>
										
										<div class='arfviewentryclose' data-dismiss='arfmodal'><img src='".ARFIMAGESURL."/close-btnicon.png' align='absmiddle' style='margin-right:10px;' />".__('Close', 'ARForms')."</div>
                                        
										</div>";
                                ?>
                    
                                </div>
                                </td>              
                            </tr>
                    <?php } } ?>
                    	       
                        </tbody>
                    </table>
                    
                    <div class="alignleft actions2">
                            <?php 
                            $two = '2';
                            echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two' id='action$two'>\n";
                            echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms') . "</option>\n";
                    
                            foreach ( $actions as $name => $title ) {
                                $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';
                    
                                echo "\t<option value='$name'$class>$title</option>\n";
                            }
                    
                            echo "</select></div>\n";
                    		
							echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__("Apply","ARForms").'" />';		
                            
                            echo "\n";
                            
                            ?>
                    </div>
                    <div class="footer_grid"></div> 
                    </form>
                    
                    <?php do_action('arfafterlistingentries'); ?>
    
                    <div style="clear:both;"></div>
                    <br /><br />
                    
<script type="text/javascript">
function ChangeID(id)
{
	document.getElementById('delete_entry_id').value = id;
}
</script>
    <?php	
	
	die();
	}

	
	function get_entries_list($id = '') {

		global $db_record, $arffield, $arfrecordmeta, $user_ID, $armainhelper, $arrecordhelper;
		
		if(!$id)


            $id = $armainhelper->get_param('id');


        if(!$id)


            $id = $armainhelper->get_param('entry_id');


        


        $entry = $db_record->getOne($id, true);


        $data = maybe_unserialize($entry->description);


        if(!is_array($data) or !isset($data['referrer']))


            $data = array('referrer' => $data);



        $fields = $arffield->getAll("fi.type not in ('captcha','html', 'imagecontrol') and fi.form_id=". (int)$entry->form_id, 'fi.field_order');
		
		$fields		= apply_filters('arfpredisplayformcols', $fields, $entry->form_id);
		$entry		= apply_filters('arfpredisplayonecol', $entry, $entry->form_id);

        $date_format = get_option('date_format');


        $time_format = get_option('time_format');


        $show_comments = true;



        if($show_comments){


            $comments = $arfrecordmeta->getAll("entry_id=$id and field_id=0", ' ORDER BY it.created_date ASC');


            $to_emails = apply_filters('arftoemail', array(), $entry, $entry->form_id);


        }



	$var = '<table class="form-table"><tbody>';


                        foreach($fields as $field){ 


                            if ($field->type == 'divider'){ 


                       				$var .= '</tbody></table>


                       	 					<div class="arfentrydivider">'.stripslashes($field->name).'</div>


                        					<table class="form-table"><tbody>';
							} else if( $field->type == 'break' ){
								
								$var .= '</tbody></table>
										
										<div class="arfpagebreakline"></div>
										
										<table class="form-table"><tbody>';
								
                         	} else { 

							if(is_rtl())
							{
								$txt_align = 'text-align:right;';
							}
							else
							{
								$txt_align = 'text-align:left;';
							}
                        $var .=  '<tr class="arfviewentry_row" valign="top">


                            <td class="arfviewentry_left" scope="row"><strong>'.stripslashes($field->name).':</strong></td>


                            <td  class="arfviewentry_right" style="'.$txt_align.'">';

					
                       


                            $field_value = isset($entry->metas[$field->id]) ? $entry->metas[$field->id] : false; 


                            $field->field_options = maybe_unserialize($field->field_options);


                             $var .= $display_value = $arrecordhelper->display_value($field_value, $field, array('type' => $field->type, 'attachment_id' => $entry->attachment_id, 'show_filename' => true, 'show_icon' => true, 'entry_id' => $entry->id));





                            if(is_email($display_value) and !in_array($display_value, $to_emails))


                                $to_emails[] = $display_value;


                        


                            $var .=  '</td>


                        </tr>';


                        	 }


                        }  

                        $var .= '<tr class="arfviewentry_row"><td class="arfviewentry_left"><strong>'.__('Created at', 'ARForms').':</strong></td><td class="arfviewentry_right">'.$armainhelper->get_formatted_time($entry->created_date, $date_format, $time_format);



                            if($entry->user_id){



                            	}



                         $var .= '</td></tr>';
						 
						 $temp_var = apply_filters('arf_entry_payment_detail', $id);
						 
						 $var .= ( $temp_var != $id ) ? $temp_var : '';
						
						 $var = apply_filters('arfafterviewentrydetail', $var, $id); 	
						  	
                         $var .= '</tbody></table>';	

		return $var;
		
	}
	
	
	function arfentryactionfunc(){
	
	global $db_record;
		
	if( $_REQUEST['act'] == 'delete' and $_REQUEST['id']!='' ){
		
		$del_res = $db_record->destroy( $_REQUEST['id'] );
		
		if($del_res) $message = __('Entry deleted successfully', 'ARForms');	
		
		$errors = array();
		
		return $this->frm_change_entries($_POST['form'], $_POST['start_date'], $_POST['end_date'], '1', $message, $errors);	
	}
	
	
	die();
	}
	
	function include_css_from_form_content($post_content){
			
		global $post, $submit_ajax_page;
		
		$submit_ajax_page = 1;
		
		$wp_upload_dir 	= wp_upload_dir();
		if(is_ssl())
		{
			$upload_main_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/maincss');
		}
		else
		{
			$upload_main_url = 	$wp_upload_dir['baseurl'].'/arforms/maincss';
		}

		$parts = explode("[ARForms",$post_content);
		$myidpart = explode("id=",$parts[1]);
		$myid = explode("]",$myidpart[1]);
		
		
		
		if(!is_admin())
		{
			global $wp_query;	
			$posts = $wp_query->posts;
			$pattern = get_shortcode_regex();
			
			
			if (   preg_match_all( '/'. $pattern .'/s', $post_content, $matches )
				&& array_key_exists( 2, $matches )
				&& in_array( 'ARForms', $matches[2] ) )
			{
				
			}  
			
			$formids = array();
								
			foreach($matches as $k=>$v)
			{
				foreach($v as $key => $val)
				{
					$parts = explode("id=",$val);
					if($parts > 0)
					{
						
						if (@stripos($parts[1], ']') !== false) {
							$partsnew = @explode("]",$parts[1]);
							$formids[] = @$partsnew[0];
						}
						else if (@stripos($parts[1], ' ') !== false) {
							
							$partsnew = @explode(" ",$parts[1]);
							$formids[] = @$partsnew[0];
						}
						else
						{
							
						}
						
					}
					
				}
				
			}
				
			$newvalarr = array();
				
			if(is_array($formids) && count($formids) > 0)
			{
				foreach($formids as $newkey => $newval)
				{
					if(stripos($newval, ' ') !== false) {
					$partsnew = explode(" ",$newval);
					$newvalarr[] = $partsnew[0];
					}
					else
						$newvalarr[] = $newval;
				}
			}
			
			if(is_array($newvalarr) && count($newvalarr) > 0)
			{
				$newvalarr = array_unique($newvalarr);
				foreach($newvalarr as $newkey => $newval)
				{
					$fid1 = $upload_main_url.'/maincss_'.$newval.'.css';
					
					wp_register_style('arfformscss'.$newval, $upload_main_url.'/maincss_'.$newval.'.css');
					wp_print_styles('arfformscss'.$newval);
				}	
			}
		}
	}
	
	function ajax_check_recaptcha(){
		
		global $wpdb,$errors, $arfieldhelper, $maincontroller;
		
		$errors = array();
		
		$arf_options = get_option('arf_options');
		
		$default_blank_msg = $arf_options->blank_msg;
		
		$fields = $arfieldhelper->get_form_fields_tmp(false, $_POST['form_id'], false, 0);
		
		foreach($fields as $field) {
			$field_id = ($field->ref_field_id > 0) ? $field->ref_field_id : $field->id;
			
			if($field->field_options['is_recaptcha'] == 'custom-captcha')
			{
				if ($field->type == 'captcha' and isset($_POST['recaptcha_challenge_field'])){
				
					$security_code     = trim(strip_tags($_POST['vpb_captcha_code']));
					
					if($_POST['vpb_captcha_code'] == '')
					{
						$errors[$field_id] = $default_blank_msg;
					}	
					else
					{				
						if(empty($_SESSION['vpb_captcha_code_'.$_POST['form_id']]) || strcasecmp($_SESSION['vpb_captcha_code_'.$_POST['form_id']], $_POST['vpb_captcha_code']) != 0)
						{
							$errors[$field_id] = (!isset($field->field_options['invalid']) or $field->field_options['invalid'] == '') ? $arfsettings->re_msg : $field->field_options['invalid'];
						}
						else
						{					
							$errors['captcha'] =  'success';
							
							$_SESSION['arf_recaptcha_allowed_'.$_POST['form_id']] = 1;
						}
					}
				}
			}
			else
			{
				
				if ($field->type == 'captcha' and isset($_POST['recaptcha_challenge_field'])){
	
					$maincontroller->arfafterinstall();	
					
					global $arfsettings;
	
	
	
					if(!function_exists('recaptcha_check_answer'))
	
	
						require_once(FORMPATH.'/core/recaptchalib.php');
	
	
	
	
	
					$response = recaptcha_check_answer($arfsettings->privkey,
	
													$_SERVER['REMOTE_ADDR'],
	
													$_POST['recaptcha_challenge_field'],
	
													$_POST['recaptcha_response_field']);
	
					if (!$response->is_valid) {
						
						$errors[$field_id] = (!isset($field->field_options['invalid']) or $field->field_options['invalid'] == '') ? $arfsettings->re_msg : $field->field_options['invalid'];
	
	
					} else {
						$errors['captcha'] =  'success';
						$_SESSION['arf_recaptcha_allowed_'.$_POST['form_id']] = 1;
	
					}
	
					
					
				}
			
			}
		}
		
		echo json_encode($errors);
		die();
	}
	
	
	function internal_check_recaptcha(){
		
		global $wpdb,$errors, $arfieldhelper, $maincontroller;
		
		$errors = array();
		
		$arf_options = get_option('arf_options');
		
		$default_blank_msg = $arf_options->blank_msg;
		
		$fields = $arfieldhelper->get_form_fields_tmp(false, $_POST['form_id'], false, 0);
		
		foreach($fields as $field) {
			$field_id = ($field->ref_field_id > 0) ? $field->ref_field_id : $field->id;
			
			if($field->field_options['is_recaptcha'] == 'custom-captcha')
			{
				if ($field->type == 'captcha'){
				
					$security_code     = trim(strip_tags($_POST['vpb_captcha_code']));
					
					if($_POST['vpb_captcha_code_'. $_POST['form_id']] == '')
					{
						$errors[$field_id] = $default_blank_msg;
					}	
					else
					{				
						if(empty($_SESSION['vpb_captcha_code_'.$_POST['form_id']]) || strcasecmp($_SESSION['vpb_captcha_code_'.$_POST['form_id']], $_POST['vpb_captcha_code_'. $_POST['form_id']]) != 0)
						{
							$errors[$field_id] = (!isset($field->field_options['invalid']) or $field->field_options['invalid'] == '') ? $arfsettings->re_msg : $field->field_options['invalid'];
						}
					}
				}
			}
			else
			{
				
				if ($field->type == 'captcha'){
	
					$maincontroller->arfafterinstall();	
					
					global $arfsettings;
	
	
	
					if(!function_exists('recaptcha_check_answer'))
	
	
						require_once(FORMPATH.'/core/recaptchalib.php');
	
	
	
	
	
					$response = recaptcha_check_answer($arfsettings->privkey,
	
													$_SERVER['REMOTE_ADDR'],
	
													$_POST['recaptcha_challenge_field'],
	
													$_POST['recaptcha_response_field']);
	
					if (!$response->is_valid) {
						
						$errors[$field_id] = (!isset($field->field_options['invalid']) or $field->field_options['invalid'] == '') ? $arfsettings->re_msg : $field->field_options['invalid'];
	
	
					} else {
						//$errors['captcha'] =  'success';
	
					}
	
					
					
				}
			
			}
		}
		
		return $errors;
	}
	
	function getBrowser($user_agent) 
	{ 
		$u_agent = $user_agent; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
	
		//First get the platform?
		if (@preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (@preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (@preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		
		// Next get the name of the useragent yes seperately and for good reason
		if(@preg_match('/MSIE/i',$u_agent)  && !@preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		} 
		elseif(@preg_match('/Firefox/i',$u_agent)) 
		{ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} 
		elseif(@preg_match('/Chrome/i',$u_agent)) 
		{ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} 
		elseif(@preg_match('/Safari/i',$u_agent)) 
		{ 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		} 
		elseif(@preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} 
		elseif(@preg_match('/Netscape/i',$u_agent)) 
		{ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} elseif( @preg_match( '/Trident/', $u_agent ) ){
                        $bname = 'Internet Explorer';
                        $ub = "rv";
                }
		//echo $u_agent;
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ |:]+(?<version>[0-9.|a-zA-Z.]*)#';
                
		if (!@preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
		
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	} 
	function current_modal()
	{
		if(isset($_REQUEST['position_modal']) && $_REQUEST['position_modal']!='')
		{
			$current_modal = $_REQUEST['position_modal'];
			$_SESSION['last_open_modal'] = $current_modal;
			echo $_SESSION['last_open_modal'] ;
			exit;
		}		
	}
}
?>