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

class arformmodel{

  function arformmodel() {
  
   		add_filter('arfformoptionsbeforeupdateform', array(&$this, 'update_options'), 10, 2);

        add_filter('arfupdatefieldtoptions', array(&$this, 'arfupdatefieldtoptions'), 10, 3);

        add_action('change_form', array(&$this, 'updateform'), 10, 2);

        add_filter('arfvalidationofcurrentform', array(&$this, 'validateform'), 10, 2);
  
  }

  function update_options($options, $values){


        global $style_settings, $arformhelper;


        $defaults = $arformhelper->get_default_options();


        unset($defaults['is_loggedin']);


        unset($defaults['can_edit']);


        $defaults['inc_user_info'] = 0;


        foreach($defaults as $opt => $default)


            $options[$opt] = (isset($values['options'][$opt])) ? $values['options'][$opt] : $default;



        unset($defaults);



        $options['single_entry'] = (isset($values['options']['single_entry'])) ? $values['options']['single_entry'] : 0;


        if ($options['single_entry'])


            $options['single_entry_type'] = (isset($values['options']['single_entry_type'])) ? $values['options']['single_entry_type'] : 'cookie';



       if (IS_WPMU)

            $options['copy'] = (isset($values['options']['copy'])) ? $values['options']['copy'] : 0;


        return $options;


    }

function sitedesc()
{
	return get_bloginfo('description');
}
  function arfupdatefieldtoptions($field_options, $field, $values){


        $post_fields = array(


            'post_category', 'post_content', 'post_excerpt', 'post_title', 


            'post_name', 'post_date', 'post_status'


        );


        


        $field_options['post_field'] = $field_options['custom_field'] = '';


        $field_options['taxonomy'] = 'category';


        $field_options['exclude_cat'] = 0;


        


        if(!isset($values['options']['create_post']) or !$values['options']['create_post'])


            return $field_options;


            


        foreach($post_fields as $post_field){


            if(isset($values['options'][$post_field]) and $values['options'][$post_field] == $field->id)


                $field_options['post_field'] = $post_field;


        }

        return $field_options;


    }
  
  function updateform($id, $values){


        global $wpdb, $arfform, $MdlDb, $arffield;


        if (isset($values['options'])){


            $is_loggedin = isset($values['is_loggedin']) ? $values['is_loggedin'] : 0;


            $can_edit = isset($values['can_edit']) ? $values['can_edit'] : 0;


            $updated = $wpdb->update( $MdlDb->forms, array('is_loggedin' => $is_loggedin, 'can_edit' => $can_edit), array( 'id' => $id ) );


            if($updated){


                wp_cache_delete( $id, 'arfform');


                unset($updated);


            }


        }

        if (isset($values['field_options'])){


            $all_fields = $arffield->getAll(array('fi.form_id' => $id));


            if ($all_fields){


                foreach($all_fields as $field){


                    $option_array[$field->id] = maybe_unserialize($field->field_options);


                    $option_array[$field->id]['dependent_fields'] = false;


                    unset($field);


                }


                foreach($option_array as $field_id => $field_options){


                    $arffield->update($field_id, array('field_options' => $field_options));


                    unset($field_options);


                }


                unset($option_array);


            }


        }


    }
  
  function validateform( $errors, $values ){


        global $arffield, $arfieldhelper;


        if (isset($values['is_loggedin']) or isset($values['can_edit']) or (isset($values['single_entry']) and isset($values['options']['single_entry_type']) and $values['options']['single_entry_type'] == 'user')){


            $form_id = $values['id'];


            $user_field = $arffield->getAll(array('fi.form_id' => $form_id, 'type' => 'user_id'));


            if (!$user_field){


                $new_values = $arfieldhelper->setup_new_variables('user_id',$form_id);


                $new_values['name'] = __('User ID', 'ARForms');


                $arffield->create($new_values);


            }


        }



        if (isset($values['options']['auto_responder'])){


            if (!isset($values['options']['ar_email_message']) or $values['options']['ar_email_message'] == '')


                $errors[] = __("Please insert a message for your auto responder.", 'ARForms');


            if (isset($values['options']['ar_reply_to']) and !is_email(trim($values['options']['ar_reply_to'])))


                $errors[] = __("That is not a valid reply-to email address for your auto responder.", 'ARForms');


        }
		
		if (isset($values['options']['chk_admin_notification'])) {
			
			if (!isset($values['options']['ar_admin_email_message']) or $values['options']['ar_admin_email_message'] == '')
			
				$errors[] = __("Please insert a message for your auto responder.", 'ARForms');
			
		}


        return $errors;


    }
	
  function create( $values ){


    global $wpdb, $MdlDb, $arfsettings, $arformhelper, $armainhelper;


    


    $new_values = array();


    $new_values['form_key'] = $armainhelper->get_unique_key($values['form_key'], $MdlDb->forms, 'form_key');


    $new_values['name'] = $values['name'];


    $new_values['description'] = $values['description'];


    $new_values['status'] = isset($values['status']) ? $values['status'] : 'draft';


    $new_values['is_template'] = isset($values['is_template']) ? (int)$values['is_template'] : 0;


    $new_values['can_edit'] = isset($values['can_edit']) ? (int)$values['can_edit'] : 0;


    $options = array();


    


    $defaults = $arformhelper->get_default_opts();


    foreach ($defaults as $var => $default){


        $options[$var] = isset($values['options'][$var]) ? $values['options'][$var] : $default;


        unset($var);


        unset($default);


    }


        


    $options['before_html'] = isset($values['options']['before_html']) ? $values['options']['before_html'] : $arformhelper->get_default_html('before');


    $options['after_html'] = isset($values['options']['after_html']) ? $values['options']['after_html'] : $arformhelper->get_default_html('after');
	$values['is_importform'] = isset($values['is_importform']) ? $values['is_importform'] : '';
	if($values['is_importform']!='Yes')
	{

		$options = apply_filters('arfformoptionsbeforeupdateform', $options, $values);
	
	
		$new_values['options'] = maybe_serialize($options);
	}
	else
	{
		$new_values['options'] = $values['options'];
	}	


    $new_values['created_date'] = current_time('mysql', 1);


	


	if(isset($_REQUEST['autoresponder']) and count($_REQUEST['autoresponder'])>0)


	{


		foreach($_REQUEST['autoresponder'] as $aresponder)


		{


			$_REQUEST['autoresponder_id'] .= $aresponder."|";


		}


	}else


	{


		$_REQUEST['autoresponder_id'] = "";


	}


	


	$new_values['autoresponder_id'] = $_REQUEST['autoresponder_id'];



    $query_results = $wpdb->insert( $MdlDb->forms, $new_values );


    


    return $wpdb->insert_id;


  }


  


  function duplicate( $id, $template=false, $copy_keys=false, $blog_id=false, $is_from_edit=false, $newformid=0, $is_ref_form=0 ){


    global $wpdb, $MdlDb, $arfform, $arffield, $arformhelper, $armainhelper;


    


    $values = $arfform->getOne( $id, $blog_id );
	
	$autoresponder_fname = $values->autoresponder_fname;
    $autoresponder_lname = $values->autoresponder_lname;
    $autoresponder_email = $values->autoresponder_email;
	

    if(!$values)
	{
		return false;
	}

        


    $new_values = array();


    $new_key = ($copy_keys) ? $values->form_key : '';

	if($is_ref_form == 1)
	{
    	$new_values['form_key'] = $armainhelper->get_unique_key($new_key, $MdlDb->ref_forms, 'form_key');
	}
	else
	{
		$new_values['form_key'] = $armainhelper->get_unique_key($new_key, $MdlDb->forms, 'form_key');
	}

	$form_name = (isset($_REQUEST['form_name'])) ? $_REQUEST['form_name'] : '';
			
	$form_desc = (isset($_REQUEST['form_desc'])) ? $_REQUEST['form_desc'] : '';
	
			
	$new_values['name'] = trim($form_name);
			
	$new_values['description'] = trim($form_desc);
			    				
    $new_values['status'] = (!$template) ? 'draft' : '';


    


    if ($blog_id){


        $new_values['status'] = 'published';


        $new_options = maybe_unserialize($values->options);


        $new_options['email_to'] = get_option('admin_email');


        $new_options['copy'] = false;


        $new_values['options'] = $new_options;


    }else


        $new_values['options'] = $values->options;


    $new_values['options']['notification'][0] = array('email_to' => get_option('admin_email'), 'reply_to' => get_option('admin_email'), 
				
													'reply_to_name' => get_option('blogname'), 'cust_reply_to' => '', 'cust_reply_to_name' => '');
				


    if(is_array($new_values['options']))


        $new_values['options'] = maybe_serialize($new_values['options']);


        


    $new_values['is_loggedin'] = $values->is_loggedin ? $values->is_loggedin : 0;


    $new_values['can_edit'] = $values->can_edit ? $values->can_edit : 0;


    $new_values['created_date'] = current_time('mysql', 1);


    $new_values['is_template'] = ($template) ? 1 : 0;

	if($newformid > 0 )
		$query_results = $wpdb->update( $MdlDb->forms, $new_values ,array('id'=>$newformid) );
	else
	{
		if($is_ref_form == 1)
		{
			$query_results = $wpdb->insert( $MdlDb->ref_forms, $new_values );
		}
		else
		{
			$query_results = $wpdb->insert( $MdlDb->forms, $new_values );
		}
	}
	
	
    if($query_results){

		if($newformid > 0 )
       		$form_id = $newformid;
		else
			$form_id = $wpdb->insert_id;
		
		if($is_from_edit)	
       		$arffield->duplicate($id, $form_id, $copy_keys, $blog_id);	
		else {			
			$arffield->duplicate($id, $form_id, $copy_keys, $blog_id, true);	

			$autoresponder_fname = (isset($autoresponder_fname) and isset($_SESSION['arf_fields'][$autoresponder_fname]) ) ? $_SESSION['arf_fields'][$autoresponder_fname] : '';
    		$autoresponder_lname = (isset($autoresponder_lname) and isset($_SESSION['arf_fields'][$autoresponder_lname]) ) ? $_SESSION['arf_fields'][$autoresponder_lname] : '';
    		$autoresponder_email = (isset($autoresponder_email) and isset($_SESSION['arf_fields'][$autoresponder_email]) ) ? $_SESSION['arf_fields'][$autoresponder_email] : '';
			
			$form_options = maybe_unserialize($new_values['options']);
			
			if( $template < 100 ) {
				global $arfsettings;
				$form_options['success_msg'] = $arfsettings->success_msg;	
			}
			
			if( count($_SESSION['arf_fields']) > 0 and is_array($_SESSION['arf_fields']) ){
				foreach( $_SESSION['arf_fields'] as $original_id => $field_new_id ){					
					$form_options['ar_email_to'] 		= str_replace('['.$original_id.']','['.$field_new_id.']',$form_options['ar_email_to']);
					$form_options['ar_email_subject'] 	= str_replace('['.$original_id.']','['.$field_new_id.']',$form_options['ar_email_subject']);
					$form_options['ar_email_message'] 	= str_replace('['.$original_id.']','['.$field_new_id.']',$form_options['ar_email_message']);
					$form_options['ar_user_from_email'] = str_replace('['.$original_id.']','['.$field_new_id.']',$form_options['ar_user_from_email']);
					$form_options['ar_admin_from_email']= str_replace('['.$original_id.']','['.$field_new_id.']',$form_options['ar_admin_from_email']);
					$form_options['ar_admin_email_message'] = str_replace('['.$original_id.']','['.$field_new_id.']',$form_options['ar_admin_email_message']);
					
					$form_options['ar_email_to'] 		= $arformhelper->replace_field_shortcode_import($form_options['ar_email_to'], $original_id, $field_new_id);
					$form_options['ar_email_subject'] 	= $arformhelper->replace_field_shortcode_import($form_options['ar_email_subject'], $original_id, $field_new_id);
					$form_options['ar_email_message'] 	= $arformhelper->replace_field_shortcode_import($form_options['ar_email_message'], $original_id, $field_new_id);
					$form_options['ar_user_from_email'] = $arformhelper->replace_field_shortcode_import($form_options['ar_user_from_email'], $original_id, $field_new_id);
					$form_options['ar_admin_from_email']= $arformhelper->replace_field_shortcode_import($form_options['ar_admin_from_email'], $original_id, $field_new_id);
					$form_options['ar_admin_email_message']= $arformhelper->replace_field_shortcode_import($form_options['ar_admin_email_message'], $original_id, $field_new_id);
					
					$fields_array = $arffield->getAll(array('fi.form_id' => $form_id), 'field_order');
					if( count($fields_array) > 0 ){
						foreach($fields_array as $new_field){
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
								
								$coditional_logic['rules'] = $coditional_logic_rules;
								$coditional_logic_new = maybe_serialize($coditional_logic);
								$wpdb->update($MdlDb->fields, array('conditional_logic'=> $coditional_logic_new), array('id'=>$new_field->id));
							}
							
						}
						
					}
					
				}
				
				$form_options = maybe_serialize($form_options);
				$wpdb->update($MdlDb->forms, array('options'=>$form_options), array('id'=>$form_id));	
			}
			
			$wpdb->update($MdlDb->forms, array('autoresponder_fname'=> $autoresponder_fname, 'autoresponder_lname'=> $autoresponder_lname, 'autoresponder_email'=> $autoresponder_email ), array('id'=>$form_id));
			
			//duplicate autoresponder
			if( isset($id) and $id != '' ){
			
				$sel_rec = $wpdb->prepare("select * from ".$wpdb->prefix."arf_ar where frm_id = %d", $id);
				$res_rec = $wpdb->get_results($sel_rec, 'ARRAY_A');
				if( $res_rec )
					$res_rec = $res_rec[0];
					
					$res_rec["aweber"] = isset($res_rec["aweber"]) ? $res_rec["aweber"] : ''; 
					$res_rec["mailchimp"] = isset($res_rec["mailchimp"]) ? $res_rec["mailchimp"] : ''; 
					$res_rec["getresponse"] = isset($res_rec["getresponse"]) ? $res_rec["getresponse"] : ''; 
					$res_rec["gvo"] = isset($res_rec["gvo"]) ? $res_rec["gvo"] : ''; 
					$res_rec["ebizac"] = isset($res_rec["ebizac"]) ? $res_rec["ebizac"] : ''; 
					$res_rec["icontact"] = isset($res_rec["icontact"]) ? $res_rec["icontact"] : ''; 
					$res_rec["constant_contact"] = isset($res_rec["constant_contact"]) ? $res_rec["constant_contact"] : ''; 
					
				$update = $wpdb->query( $wpdb->prepare("insert into ".$wpdb->prefix."arf_ar (aweber ,mailchimp, getresponse, gvo, ebizac, icontact, constant_contact, frm_id) values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')", $res_rec["aweber"], $res_rec["mailchimp"], $res_rec["getresponse"],$res_rec["gvo"],$res_rec["ebizac"], $res_rec["icontact"],$res_rec["constant_contact"], $form_id) );
			
			}
			//
			
		} 
		     
	  return $form_id;


   }else


      return false;


  }





  function update( $id, $values, $create_link = false,$is_ref_form=0 ){


    global $wpdb, $MdlDb, $arffield, $arfsettings, $arformhelper, $arfieldhelper, $armainhelper;
	
	$values = apply_filters('arfchangevaluesbeforeupdateform', $values);
	
	do_action('arfbeforeupdateform', $id, $values, $create_link,$is_ref_form);
    do_action('arfbeforeupdateform_'.$id, $id, $values, $create_link,$is_ref_form);
	
    if ($create_link or isset($values['options']) or isset($values['item_meta']) or isset($values['field_options']))


        $values['status'] = 'published';



    if (isset($values['form_key']))
	{
		if($is_ref_form == 1)
			$values['form_key'] = $armainhelper->get_unique_key($values['form_key'], $MdlDb->ref_forms, 'form_key', $id);
		else
			$values['form_key'] = $armainhelper->get_unique_key($values['form_key'], $MdlDb->forms, 'form_key', $id);
	
	}
	
	

    $form_fields = array('form_key', 'name', 'description', 'status');


    $new_values = array();


    if (isset($values['options'])){


        $options = array();


        $defaults = $arformhelper->get_default_opts();


        foreach ($defaults as $var => $default){


            if($var == 'notification')


                $options[$var] = isset($values[$var]) ? $values[$var] : $default;


            else


                $options[$var] = isset($values['options'][$var]) ? $values['options'][$var] : $default;

        }



        $options['custom_style'] = isset($values['options']['custom_style']) ? $values['options']['custom_style'] : 0;
		
        $options['before_html'] = isset($values['options']['before_html']) ? $values['options']['before_html'] : $arformhelper->get_default_html('before');


        $options['after_html'] = isset($values['options']['after_html']) ? $values['options']['after_html'] : $arformhelper->get_default_html('after');


        $options = apply_filters('arfformoptionsbeforeupdateform', $options, $values);
		
		$options['display_title_form'] = isset($values['options']['display_title_form']) ? $values['options']['display_title_form'] : 0;
		
		$options['email_to'] = $options['reply_to'];
		
		
		
		//---------- for submit button conditional logic ----------//
		$submitbtnid = "arfsubmit";
		if( isset($_REQUEST['conditional_logic_'.$submitbtnid]) and stripslashes_deep($_REQUEST['conditional_logic_'.$submitbtnid]) == '1' ) {
						
			$conditional_logic_display = @stripslashes_deep($_REQUEST['conditional_logic_display_'.$submitbtnid]);
			
			$conditional_logic_if_cond = @stripslashes_deep($_REQUEST['conditional_logic_if_cond_'.$submitbtnid]);
			
			$conditional_logic_rules = array();
			
			$rule_array = $_REQUEST['rule_array_'.$submitbtnid] ? $_REQUEST['rule_array_'.$submitbtnid] : array();
			if( count($rule_array) > 0 ) {
				$i = 1;
				foreach($rule_array as $v){
					
					$conditional_logic_field 		= @stripslashes_deep($_REQUEST['arf_cl_field_'.$submitbtnid.'_'.$v]);
					$conditional_logic_field_type 	= @$arfieldhelper->get_field_type($conditional_logic_field);
					$conditional_logic_op 			= @stripslashes_deep($_REQUEST['arf_cl_op_'.$submitbtnid.'_'.$v]);
					$conditional_logic_value 		= @stripslashes_deep($_REQUEST['cl_rule_value_'.$submitbtnid.'_'.$v]);
					
					$conditional_logic_rules[$i]= array(
													'id' => $i, 
													'field_id' 	=> $conditional_logic_field,
													'field_type'=> $conditional_logic_field_type, 
													'operator' 	=> $conditional_logic_op,
													'value' 	=> $conditional_logic_value,
													);													 
				$i++;
				}
			
			}
						
			$conditional_logic = array(
								'enable' => 1,
								'display' => $conditional_logic_display,
								'if_cond' => $conditional_logic_if_cond,
								'rules'   => $conditional_logic_rules,
								);
								
			$options['submit_conditional_logic'] = $conditional_logic;				
		
		} else {
			$conditional_logic_display = isset($conditional_logic_display) ? $conditional_logic_display : 'show';
			$conditional_logic_if_cond = isset($conditional_logic_if_cond) ? $conditional_logic_if_cond : 'all';
			$conditional_logic_rules = isset($conditional_logic_rules) ? $conditional_logic_rules : array();
			$conditional_logic = array(
								'enable' => 0,
								'display' => $conditional_logic_display,
								'if_cond' => $conditional_logic_if_cond,
								'rules'   => $conditional_logic_rules,
								);
								
			$options['submit_conditional_logic'] = $conditional_logic;			
		}
		//---------- for conditional logic ----------//
		
        $new_values['options'] = maybe_serialize($options);


    }
	
    foreach ($values as $value_key => $value){


        if (in_array($value_key, $form_fields))


            $new_values[$value_key] = $value;


    }
	
	$all_fields = $arffield->getAll(array('fi.form_id' => $id));
	
    if ($all_fields and (isset($values['options']) or isset($values['item_meta']) or isset($values['field_options']))){


        if(!isset($values['item_meta']))


            $values['item_meta'] = array();


        $existing_keys = array_keys($values['item_meta']);
					
        foreach ($all_fields as $fid){

            if (!in_array($fid->id, $existing_keys))
                $values['item_meta'][$fid->id] = '';	
        }
						
        foreach ($values['item_meta'] as $field_id => $default_value){ 


            $field = $arffield->getOne($field_id);

            if (!$field) continue;
			
			
            $field_options = maybe_unserialize($field->field_options);


            if(isset($values['options']) and isset($values['field_options']['custom_html_'.$field_id])){

               

				$field_options = apply_filters('arfupdatefieldtoptions', $field_options, $field, $values);
				
				
				$arffield->update($field_id, array('field_options' => $field_options));
				

                if(isset($values['field_options']['custom_html_'.$field_id])){


                    $field_options['custom_html'] = isset($values['field_options']['custom_html_'.$field_id]) ? $values['field_options']['custom_html_'.$field_id] : (isset($field_options['custom_html']) ? $field_options['custom_html'] : $arfieldhelper->get_basic_default_html($field->type));


                    $field_options = apply_filters('arfupdatefieldtoptions', $field_options, $field, $values);
					

                    $arffield->update($field_id, array('field_options' => $field_options));


                }else if($field->type == 'hidden'){


                    $prev_opts = $field_options;


                    $field_options = apply_filters('arfupdatefieldtoptions', $field_options, $field, $values);


                    if($prev_opts != $field_options)


                        $arffield->update($field_id, array('field_options' => $field_options));


                    unset($prev_opts);


                }


            }else{

				
                foreach (apply_filters('arf_save_more_field_from_out_side',array('size', 'max', 'label', 'invalid', 'required_indicator', 'blank', 'classes', 'star_color', 'star_size', 'star_val', 'pre_page_title', 'next_page_title', 'page_break_type', 'first_page_label', 'second_page_label', 'page_break_first_use','is_recaptcha','inline_css', 'css_outer_wrapper', 'css_label', 'css_input_element', 'css_description', 'file_upload_text', 'file_remove_text', 'upload_btn_color', 'arf_divider_font', 'arf_divider_font_size', 'arf_divider_font_style', 'arf_divider_bg_color', 'arf_divider_inherit_bg','separate_value', 'lbllike', 'lbldislike', 'slider_handle', 'slider_step', 'slider_bg_color', 'slider_handle_color', 'slider_value', 'like_bg_color', 'dislike_bg_color', 'slider_bg_color2', 'upload_font_color', 'confirm_password', 'password_strength', 'invalid_password', 'placeholdertext', 'phone_validation', 'confirm_password_label', 'image_url', 'image_left', 'image_top', 'image_height', 'image_width', 'image_center', 'enable_total', 'colorpicker_type', 'show_year_month_calendar', 'password_placeholder', 'minlength', 'minlength_message', 'default_hour','default_minutes','confirm_email','confirm_email_label','invalid_confirm_email','confirm_email_placeholder','enable_arf_prefix','arf_prefix_icon','enable_arf_suffix','arf_suffix_icon','css_add_icon')) as $opt)

                $field_options[$opt] = isset($values['field_options'][$opt.'_'.$field_id]) ? trim($values['field_options'][$opt.'_'.$field_id]) : ''; 


                $field_options['separate_value'] = isset($values['field_options']['separate_value_'.$field_id]) ? trim($values['field_options']['separate_value_'.$field_id]) : 0;
				
				$field_options['clear_on_focus'] = isset($values['field_options']['frm_clear_field_'.$field_id]) ? trim($values['field_options']['frm_clear_field_'.$field_id]) : 0;
				
				$field_options['default_blank'] = isset($values['field_options']['frm_default_blank_'.$field_id]) ? trim($values['field_options']['frm_default_blank_'.$field_id]) : 0;
				 
                $field_options = apply_filters('arfupdatefieldoptions', $field_options, $field, $values);


                $default_value = maybe_serialize($values['item_meta'][$field_id]);

                $field_key = (isset($values['field_options']['field_key_'.$field_id]))? $values['field_options']['field_key_'.$field_id] : $field->field_key;


                $field_type = (isset($values['field_options']['type_'.$field_id]))? $values['field_options']['type_'.$field_id] : $field->type;


                $field_description = (isset($values['field_options']['description_'.$field_id]))? $values['field_options']['description_'.$field_id] : '';
				
				$field_name = (isset($values['field_options']['name_'.$field_id]))? $values['field_options']['name_'.$field_id] : $field->name;
				
				$field_required = (isset($values['field_options']['required_'.$field_id]))? $values['field_options']['required_'.$field_id] : 0;
				
                $arffield->update($field_id, array('field_key' => $field_key, 'type' => $field_type, 'default_value' => $default_value, 'field_options' => $field_options, 'description' => $field_description, 'name' => $field_name, 'required' => $field_required));
				
            }


        }

    }    


	if(isset($_REQUEST['autoresponder']) and count($_REQUEST['autoresponder'])>0)


	{


		foreach($_REQUEST['autoresponder'] as $aresponder)


		{


			$_REQUEST['autoresponder_id'] .= $aresponder."|";


		}


	}else


	{


		$_REQUEST['autoresponder_id'] = "";


	}
	
	
		$type = @maybe_unserialize( get_option('arf_ar_type') );	
		
		
		$res = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 3), 'ARRAY_A');
		
			if( isset($_REQUEST['autoresponders']) && in_array('3', $_REQUEST['autoresponders']) ) {	
				$aweber_arr['enable'] = 1;
			}
			else {				
				$aweber_arr['enable'] = 0;
			}	
							
			if( $type['aweber_type'] == 1 ) {
				$aweber_arr['type'] = 1; 
				$aweber_arr['type_val'] = @$_REQUEST['i_aweber_list'];		
			}
			else if($type['aweber_type'] == 0) {
				$aweber_arr['type'] = 0; 
				$aweber_arr['type_val'] = @stripslashes_deep($_REQUEST['web_form_aweber']);		
			}
		
		
		
		
		$res = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 1), 'ARRAY_A');
		
			if( isset($_REQUEST['autoresponders']) && in_array('1', $_REQUEST['autoresponders']) ) {
				$mailchimp_arr['enable'] = 1;
			}
			else {				
				$mailchimp_arr['enable'] = 0;
			}	
							
			if( $type['mailchimp_type'] == 1 ) {
				$mailchimp_arr['type'] = 1; 
				$mailchimp_arr['type_val'] = @$_REQUEST['i_mailchimp_list'];		
			}
			else if($type['mailchimp_type'] == 0) {
				$mailchimp_arr['type'] = 0; 
				$mailchimp_arr['type_val'] = @stripslashes_deep($_REQUEST['web_form_mailchimp']);		
			}
		
		
		
		
		$res = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 4), 'ARRAY_A');
		
			if( isset($_REQUEST['autoresponders']) && in_array('4', $_REQUEST['autoresponders']) ) {
				$getresponse_arr['enable'] = 1;
			}
			else {
				$getresponse_arr['enable'] = 0;
			}	
							
			if( $type['getresponse_type'] == 1 ) {
				$getresponse_arr['type'] = 1; 
				$getresponse_arr['type_val'] = @$_REQUEST['i_campain_name'];	
			}
			else if($type['getresponse_type'] == 0) {
				$getresponse_arr['type'] = 0; 
				$getresponse_arr['type_val'] = @stripslashes_deep($_REQUEST['web_form_getresponse']);		
			}

		
		
		
		
		$res = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 8), 'ARRAY_A');
		
			if( isset($_REQUEST['autoresponders']) && in_array('8', $_REQUEST['autoresponders']) ) {
				$icontact_arr['enable'] = 1;
			}
			else {				
				$icontact_arr['enable'] = 0;
			}
								
			if( $type['icontact_type'] == 1 ) {
				$icontact_arr['type'] = 1; 
				$icontact_arr['type_val'] = @$_REQUEST['i_icontact_list'];		
			}
			else if($type['icontact_type'] == 0) {
				$icontact_arr['type'] = 0; 
				$icontact_arr['type_val'] = @stripslashes_deep($_REQUEST['web_form_icontact']);		
			}
		
		
		
		
		$res = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 9), 'ARRAY_A');
		
			if( isset($_REQUEST['autoresponders']) && in_array('9', $_REQUEST['autoresponders']) ) {
				$constant_contact_arr['enable'] = 1;
			}
			else {				
				$constant_contact_arr['enable'] = 0;
			}
			
				
			if( $type['constant_type'] == 1 ) {
				$constant_contact_arr['type'] = 1; 
				$constant_contact_arr['type_val'] = @$_REQUEST['i_constant_contact_list'];		
			}
			else if($type['constant_type'] == 0) {
				$constant_contact_arr['type'] = 0; 
				$constant_contact_arr['type_val'] = @stripslashes_deep($_REQUEST['web_form_constant_contact']);		
			}
		
		
		
		$res = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 5), 'ARRAY_A');
		
			if( isset($_REQUEST['autoresponders']) && in_array('5', $_REQUEST['autoresponders']) ) {
				$gvo_arr['enable'] = 1;
			}
			else {				
				$gvo_arr['enable'] = 0;
			}	
								
			if($type['gvo_type'] == 0) {
				$gvo_arr['type'] = 0; 
				$gvo_arr['type_val'] = @stripslashes_deep($_REQUEST['web_form_gvo']);		
			}
				
		
		
		
		
		$res = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 6), 'ARRAY_A');
		
			if( isset($_REQUEST['autoresponders']) && in_array('6', $_REQUEST['autoresponders']) ) {
			
				$ebizac_arr['enable'] = 1;
			}
			else {
				
				$ebizac_arr['enable'] = 0;
			}	
							
			if($type['ebizac_type'] == 0) {
				$ebizac_arr['type'] = 0; 
				$ebizac_arr['type_val'] = @stripslashes_deep($_REQUEST['web_form_ebizac']);		
			}
				
		$ar_global_autoresponder = array(
									'aweber' 			=> $aweber_arr['enable'],
									'mailchimp' 		=> $mailchimp_arr['enable'],
									'getresponse' 		=> $getresponse_arr['enable'],
									'gvo'				=> $gvo_arr['enable'],
									'ebizac' 			=> $ebizac_arr['enable'],
									'icontact'			=> $icontact_arr['enable'],
									'constant_contact' 	=> $constant_contact_arr['enable'],
									 );
		
		$ar_aweber 				= maybe_serialize( $aweber_arr );
		$ar_mailchimp 			= maybe_serialize( $mailchimp_arr );
		$ar_getresponse 		= maybe_serialize( $getresponse_arr );
		$ar_gvo 				= maybe_serialize( $gvo_arr );
		$ar_ebizac 				= maybe_serialize( $ebizac_arr );
		$ar_icontact			= maybe_serialize( $icontact_arr );
		$ar_constant_contact	= maybe_serialize( $constant_contact_arr );
		
		
		$res = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = %d", $id), 'ARRAY_A');
		
		if( $wpdb->num_rows != 1 )
				$res = $wpdb->query( $wpdb->prepare("INSERT INTO ".$wpdb->prefix."arf_ar (frm_id, aweber, mailchimp, getresponse, gvo, ebizac, icontact, constant_contact) VALUES (%d, %s, %s, %s, %s, %s, %s, %s)", $id, $ar_aweber, $ar_mailchimp, $ar_getresponse, $ar_gvo, $ar_ebizac, $ar_icontact, $ar_constant_contact) );
		else 
				$res = $wpdb->update( $wpdb->prefix."arf_ar", array( 'aweber' => $ar_aweber, 'mailchimp' => $ar_mailchimp, 'getresponse' => $ar_getresponse, 'gvo' => $ar_gvo, 'ebizac' => $ar_ebizac, 'icontact' => $ar_icontact, 'constant_contact' => $ar_constant_contact ), array( 'frm_id' => $id ) );
	
		
		if( $id < 10000 ){
			$enable_ar = maybe_serialize($ar_global_autoresponder);			
			$res = $wpdb->update( $wpdb->prefix."arf_ar", array( 'enable_ar' => $enable_ar ), array( 'frm_id' => $id ) );
		}	



	$new_values['autoresponder_id'] = @$_REQUEST['autoresponder_id'];


	$new_values['autoresponder_fname'] = @$_REQUEST['autoresponder_fname'];		
	
	
	$new_values['autoresponder_lname'] = @$_REQUEST['autoresponder_lname'];


	$new_values['autoresponder_email'] = @$_REQUEST['autoresponder_email'];


    if(!empty($new_values)){

		if($is_ref_form == 1)
        	$query_results = $wpdb->update( $MdlDb->ref_forms, $new_values, array( 'id' => $id ) );
		else
			$query_results = $wpdb->update( $MdlDb->forms, $new_values, array( 'id' => $id ) );

        if($query_results)


            wp_cache_delete( $id, 'arfform');


    }else{


        $query_results = true;


    }

		$new_values2 = array();
		
		$_REQUEST['arfmf'] = $id;
		
		$new_values2['arfmainformwidth'] = @$_REQUEST['arffw'];	
		
		$new_values2['form_width_unit'] = @$_REQUEST['arffu'];
		
		$new_values2['text_direction'] = @$_REQUEST['arftds'];
		
		$new_values2['form_align'] = @$_REQUEST['arffa'];
		
		$new_values2['arfmainfieldsetpadding'] = @$_REQUEST['arfmfsp'];
		
		$new_values2['form_border_shadow'] = @$_REQUEST['arffbs'];
		
		$new_values2['fieldset'] = @$_REQUEST['arfmfis'];
		
		$new_values2['arfmainfieldsetradius'] = @$_REQUEST['arfmfsr'];
		
		$new_values2['arfmainfieldsetcolor'] = @$_REQUEST['arfmfsc'];
		
		$new_values2['arfmainformbordershadowcolorsetting'] = @$_REQUEST['arffboss'];
		
		$new_values2['arfmainformtitlecolorsetting'] = @$_REQUEST['arfftc'];
		
		$new_values2['check_weight_form_title'] = @$_REQUEST['arfftws'];
		
		$new_values2['form_title_font_size'] = @$_REQUEST['arfftfss'];
		
		$new_values2['arfmainformtitlepaddingsetting'] = @$_REQUEST['arfftps'];
		
		$new_values2['arfmainformbgcolorsetting'] = @$_REQUEST['arffbcs'];
		
		$new_values2['font'] = @$_REQUEST['arfmfs'];
		
		$new_values2['label_color'] = @$_REQUEST['arflcs'];
		
		$new_values2['weight'] = @$_REQUEST['arfmfws'];
		
		$new_values2['font_size'] = @$_REQUEST['arffss'];
		
		$new_values2['align'] = @$_REQUEST['arffrma'];
		
		$new_values2['position'] = @$_REQUEST['arfmps'];
		
		$new_values2['width'] = @$_REQUEST['arfmws'];
		
		$new_values2['width_unit'] = @$_REQUEST['arfmwu'];
		
		$new_values2['arfdescfontsizesetting'] = @$_REQUEST['arfdfss'];
		
		$new_values2['arfdescalighsetting'] = @$_REQUEST['arfdas'];
		
		$new_values2['hide_labels'] = @$_REQUEST['arfhl'];
		
		$new_values2['check_font'] = @$_REQUEST['arfcbfs'];
		
		$new_values2['check_weight'] = @$_REQUEST['arfcbws'];
		
		$new_values2['field_font_size'] = @$_REQUEST['arfffss'];
		
		$new_values2['text_color'] = @$_REQUEST['arftcs'];
		
		$new_values2['border_radius'] = @$_REQUEST['arfmbs'];
		
		$new_values2['border_color'] = @$_REQUEST['arffmboc'];
		
		$new_values2['arffieldborderwidthsetting'] = @$_REQUEST['arffbws'];
		
		$new_values2['arffieldborderstylesetting'] = @$_REQUEST['arffbss'];
		
		if( isset($_REQUEST['arffiu']) and $_REQUEST['arffiu'] =='%' and isset($_REQUEST['arfmfiws']) and $_REQUEST['arfmfiws'] > '100' )
			$new_values2['field_width'] = '100';
		else
			$new_values2['field_width'] = @$_REQUEST['arfmfiws'];
		
		$new_values2['field_width_unit'] = @$_REQUEST['arffiu'];
		
		$new_values2['arffieldmarginssetting'] = @$_REQUEST['arffms'];
		
		$new_values2['arffieldinnermarginssetting'] = @$_REQUEST['arffims'];
		
		$new_values2['bg_color'] = @$_REQUEST['arffmbc'];
		
		$new_values2['arfbgactivecolorsetting'] = @$_REQUEST['arfbcas'];
		
		$new_values2['arfborderactivecolorsetting'] = @$_REQUEST['arfbacs'];
		
		$new_values2['arferrorbgcolorsetting'] = @$_REQUEST['arfbecs'];
		
		$new_values2['arferrorbordercolorsetting'] = @$_REQUEST['arfboecs'];
		
		$new_values2['arfradioalignsetting'] = @$_REQUEST['arfras'];
		
		$new_values2['arfcheckboxalignsetting'] = @$_REQUEST['arfcbas'];
		
		
		$new_values2['auto_width'] = @$_REQUEST['arfautowidthsetting'];
		
		$new_values2['arfcalthemename'] = @$_REQUEST['arffths'];
		
		$new_values2['arfcalthemecss'] = @$_REQUEST['arffthc'];
		
		$new_values2['date_format'] = @$_REQUEST['arffdaf'];
		
		$new_values2['arfsubmitbuttontext'] = @$_REQUEST['arfsubmitbuttontext'];
		
		$new_values2['arfsubmitweightsetting'] = @$_REQUEST['arfsbwes'];
		
		$new_values2['arfsubmitbuttonfontsizesetting'] = @$_REQUEST['arfsbfss'];
		
		$new_values2['arfsubmitbuttonwidthsetting'] = @$_REQUEST['arfsbws'];
		
		$new_values2['arfsubmitbuttonheightsetting'] = @$_REQUEST['arfsbhs'];
		
		
		$new_values2['submit_bg_color'] = @$_REQUEST['arfsbbcs'];
		
		$new_values2['arfsubmitbuttonbgcolorhoversetting'] = @$_REQUEST['arfsbchs'];
		
		$new_values2['arfsubmitbgcolor2setting'] = @$_REQUEST['arfsbcs'];
		
		$new_values2['arfsubmittextcolorsetting'] = @$_REQUEST['arfsbtcs'];
		
		$new_values2['arfsubmitbordercolorsetting'] = @$_REQUEST['arfsbobcs'];
		
		$new_values2['arfsubmitborderwidthsetting'] = @$_REQUEST['arfsbbws'];
		
		$new_values2['arfsubmitborderradiussetting'] = @$_REQUEST['arfsbbrs'];
		
		$new_values2['arfsubmitshadowcolorsetting'] = @$_REQUEST['arfsbscs'];
		
		$new_values2['arfsubmitbuttonmarginsetting'] = @$_REQUEST['arfsbms'];
		
		
		$new_values2['submit_bg_img'] = @$_REQUEST['arfsbis'];
		
		$new_values2['submit_hover_bg_img'] = @$_REQUEST['arfsbhis'];
		
		$new_values2['error_font'] = @$_REQUEST['arfmefs'];
		
		$new_values2['error_font_other'] = @$_REQUEST['arfmofs'];
		
		$new_values2['arffontsizesetting'] = @$_REQUEST['arfmefss'];
		
		$new_values2['arferrorbgsetting'] = @$_REQUEST['arfmebs'];
		
		$new_values2['arferrortextsetting'] = @$_REQUEST['arfmets'];					
		
		$new_values2['arferrorbordersetting'] = @$_REQUEST['arfmebos'];
		
		$new_values2['arfsucessbgcolorsetting'] = @$_REQUEST['arfmsbcs'];
		
		$new_values2['arfsucessbordercolorsetting'] = @$_REQUEST['arfmsbocs'];
		
		$new_values2['arfsucesstextcolorsetting'] = @$_REQUEST['arfmstcs'];
		
		$new_values2['arfsubmitalignsetting'] = @$_REQUEST['arfmsas'];
		
		$new_values2['checkbox_radio_style'] = @$_REQUEST['arfcrs'];
		
		$new_values2['bg_color_pg_break'] = @$_REQUEST['arffbcpb'];
		
		$new_values2['bg_inavtive_color_pg_break'] = @$_REQUEST['arfbicpb'];
		
		$new_values2['text_color_pg_break'] = @$_REQUEST['arfftcpb'];
		
		$new_values2['arfmainform_bg_img'] = @$_REQUEST['arfmfbi'];
		
		$new_values2['arfsubmitfontfamily'] = @$_REQUEST['arfsff'];	
		
		$new_values2['arfmainfieldsetpadding_1'] = @$_REQUEST['arfmainfieldsetpadding_1'];	
		$new_values2['arfmainfieldsetpadding_2'] = @$_REQUEST['arfmainfieldsetpadding_2'];	
		$new_values2['arfmainfieldsetpadding_3'] = @$_REQUEST['arfmainfieldsetpadding_3'];	
		$new_values2['arfmainfieldsetpadding_4'] = @$_REQUEST['arfmainfieldsetpadding_4'];
		$new_values2['arfmainformtitlepaddingsetting_1'] = @$_REQUEST['arfformtitlepaddingsetting_1'];
		$new_values2['arfmainformtitlepaddingsetting_2'] = @$_REQUEST['arfformtitlepaddingsetting_2'];
		$new_values2['arfmainformtitlepaddingsetting_3'] = @$_REQUEST['arfformtitlepaddingsetting_3'];
		$new_values2['arfmainformtitlepaddingsetting_4'] = @$_REQUEST['arfformtitlepaddingsetting_4'];
		$new_values2['arffieldinnermarginssetting_1'] = @$_REQUEST['arffieldinnermarginsetting_1'];
		$new_values2['arffieldinnermarginssetting_2'] = @$_REQUEST['arffieldinnermarginsetting_2'];
		$new_values2['arffieldinnermarginssetting_3'] = @$_REQUEST['arffieldinnermarginsetting_3'];
		$new_values2['arffieldinnermarginssetting_4'] = @$_REQUEST['arffieldinnermarginsetting_4'];
		$new_values2['arfsubmitbuttonmarginsetting_1'] = @$_REQUEST['arfsubmitbuttonmarginsetting_1'];
		$new_values2['arfsubmitbuttonmarginsetting_2'] = @$_REQUEST['arfsubmitbuttonmarginsetting_2'];
		$new_values2['arfsubmitbuttonmarginsetting_3'] = @$_REQUEST['arfsubmitbuttonmarginsetting_3'];
		$new_values2['arfsubmitbuttonmarginsetting_4'] = @$_REQUEST['arfsubmitbuttonmarginsetting_4'];
		$new_values2['arfsectionpaddingsetting_1'] = @$_REQUEST['arfsectionpaddingsetting_1'];
		$new_values2['arfsectionpaddingsetting_2'] = @$_REQUEST['arfsectionpaddingsetting_2'];
		$new_values2['arfsectionpaddingsetting_3'] = @$_REQUEST['arfsectionpaddingsetting_3'];
		$new_values2['arfsectionpaddingsetting_4'] = @$_REQUEST['arfsectionpaddingsetting_4'];
		$new_values2['arfcheckradiostyle'] = @$_REQUEST['arfcksn'];
		$new_values2['arfcheckradiocolor'] = @$_REQUEST['arfcksc'];
		
		$new_values2['arferrorstyle'] = @$_REQUEST['arfest'];
		$new_values2['arferrorstylecolor'] = @$_REQUEST['arfestc'];
		$new_values2['arferrorstylecolor2'] = @$_REQUEST['arfestc2'];
		$new_values2['arferrorstyleposition'] = @$_REQUEST['arfestbc'];
		
		$new_values2['arfformtitlealign'] = @$_REQUEST['arffta'];
		$new_values2['arfsubmitautowidth'] = @$_REQUEST['arfsbaw'];
		
		$new_values2['arftitlefontfamily'] = @$_REQUEST['arftff'];
		
		$new_values2['bar_color_survey'] = @$_REQUEST['arfbcs'];
		$new_values2['bg_color_survey'] = @$_REQUEST['arfbgcs'];
		$new_values2['text_color_survey'] = @$_REQUEST['arfftcs'];
		
		$new_values2['arfsectionpaddingsetting'] = @$_REQUEST['arfscps'];
		
		if( isset($_REQUEST['arfmainform_opacity']) and $_REQUEST['arfmainform_opacity'] > 1 ) 
			$new_values2['arfmainform_opacity'] = '1';
		else
			$new_values2['arfmainform_opacity'] = @$_REQUEST['arfmainform_opacity'];		
		
		$new_values2['arfmainfield_opacity'] = @$_REQUEST['arfmfo'];
		
		$new_values2['prefix_suffix_bg_color'] = @$_REQUEST['pfsfsbg'];
		$new_values2['prefix_suffix_icon_color'] = @$_REQUEST['pfsfscol'];
		
		$new_values1 = @maybe_serialize($new_values2);
		
		
		if(!empty($new_values2)){
			
			if($is_ref_form == 1)
				$query_results = $wpdb->query("update ".$MdlDb->ref_forms." set form_css = '".$new_values1."' where id = '".$id."'");
			else
				$query_results = $wpdb->query("update ".$MdlDb->forms." set form_css = '".$new_values1."' where id = '".$id."'");

			if($query_results > 0)
			{
				$saving = true;
				
				global $arsettingcontroller;
				
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
	
				$css_file = $target_path .'/maincss_'.$id.'.css';
				
				if(file_exists($css_file))
				{
					WP_Filesystem();
					global $wp_filesystem;
					$wp_filesystem->put_contents( $css_file, $css , 0777);

				}
				wp_cache_delete( $id, 'arfform');
			}
		
		}else{
	
			$query_results = true;	
		}
		
		
    do_action('change_form', $id, $values);

	do_action('arfafterupdateform', $id, $values, $create_link,$is_ref_form);
    do_action('arfafterupdateform_'.$id, $id, $values, $create_link,$is_ref_form);
	
    do_action('arfupdateform_'. $id, $values);


    $query_results = apply_filters('arfchangevaluesafterupdateform', $query_results);


    return $query_results;


  }





  function destroy( $id ){


    global $wpdb, $MdlDb, $db_record;

    $form = $this->getOne($id);
	
    if (!$form or $form->is_template)
        return false;
	
	do_action('arfbeforedestroyform', $id);

   	do_action('arfbeforedestroyform_'. $id);
	
	// delete referenced form
	$form_css_res = $wpdb->get_results($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_ref_forms WHERE form_id = %d", $id), ARRAY_A);
	//$rformid = isset($form_css_res[0]['id']) ? $form_css_res[0]['id'] : '';
	if( $form_css_res )
	{
		foreach( $form_css_res as $refform )
		{
			$rformid = $refform['id']; 
			if(isset($rformid) && $rformid > 0	&& $rformid != "")
			{
				$entries = $db_record->getAll(array('it.form_id' => $rformid));
				foreach ($entries as $item)
					$db_record->destroy($item->id);
		
				$query_results_r1 = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->fields` WHERE `form_id` = %d", $rformid));
				$query_results_r2 = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->views` WHERE `form_id` = %d", $rformid));
				$query_results_r3 = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->ar` WHERE `frm_id` = %d", $rformid));
				
				$uploads = wp_upload_dir();
				$target_path = $uploads['basedir'];
				$target_path .= "/arforms";
				$css_path = $target_path."/css/";
				$maincss_path = $target_path."/maincss/";
				if( file_exists( $css_path.'form_'.$rformid.'.css' ) ) {
					unlink( $css_path.'form_'.$rformid.'.css' );
				}
				if( file_exists( $maincss_path.'maincss_'.$rformid.'.css' ) ) {
					unlink( $maincss_path.'maincss_'.$rformid.'.css' );
				}
				
				$query_results = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->ref_forms` WHERE `id` = %d", $rformid));
			}
		}
	}
	// delete referenced form

    $entries = $db_record->getAll(array('it.form_id' => $id));


    foreach ($entries as $item)


        $db_record->destroy($item->id);



    $query_results = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->fields` WHERE `form_id` = %d", $id));
	
	$query_results = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->views` WHERE `form_id` = %d", $id));
	
	$query_results = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->ar` WHERE `frm_id` = %d", $id));

	
		$uploads = wp_upload_dir();

        $target_path = $uploads['basedir'];

        $target_path .= "/arforms";
		
		$css_path = $target_path."/css/";
		
		$maincss_path = $target_path."/maincss/";
	
		if( file_exists( $css_path.'form_'.$id.'.css' ) ) {
			@unlink( $css_path.'form_'.$id.'.css' );
		}
		
		if( file_exists( $maincss_path.'maincss_'.$id.'.css' ) ) {
			@unlink( $maincss_path.'maincss_'.$id.'.css' );
		}

    $query_results = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->forms` WHERE `id` = %d", $id));

	
	
    if ($query_results){


        do_action('arfdestroyform', $id);


        do_action('arfdestroyform_'. $id);


    }


    return $query_results;


  }


  


  function getName( $id ){


      global $wpdb, $MdlDb;


      $query = "SELECT name FROM $MdlDb->forms WHERE ";


      $query .= (is_numeric($id)) ? "id" : "form_key";


      $query .= $wpdb->prepare("=%s", $id);


      $r = $wpdb->get_var($query);


      return stripslashes($r);


  }

  function getOne( $id, $blog_id=false ){


      global $wpdb, $MdlDb;


      


      if ($blog_id and IS_WPMU){
         $prefix = $wpdb->get_blog_prefix( $blog_id );
         $table_name = "{$prefix}arf_forms";


      }else{


          $table_name = $MdlDb->forms;


          $cache = wp_cache_get($id, 'arfform');


          if($cache){


              if(isset($cache->options))


                  $cache->options = maybe_unserialize($cache->options);


              


              return stripslashes_deep($cache);


         }


      }


      


      if (is_numeric($id))


          $where = array('id' => $id);


      else


          $where = array('form_key' => $id);


          


      $results = $MdlDb->get_one_record($table_name, $where);


      


      if(isset($results->options)){


          wp_cache_set($results->id, $results, 'arfform');


          $results->options = maybe_unserialize($results->options);


      }


      return stripslashes_deep($results);


  }
  
  function getRefOne( $id, $blog_id=false ){


      global $wpdb, $MdlDb;


      


      if ($blog_id and IS_WPMU){
         $prefix = $wpdb->get_blog_prefix( $blog_id );
         $table_name = "{$prefix}arf_ref_forms";


      }else{


          $table_name = $MdlDb->ref_forms;


          $cache = wp_cache_get($id, 'arfform');


          if($cache){


              if(isset($cache->options))


                  $cache->options = maybe_unserialize($cache->options);


              


              return stripslashes_deep($cache);


         }


      }


      


      if (is_numeric($id))


          $where = array('id' => $id);


      else


          $where = array('form_key' => $id);


          


      $results = $MdlDb->get_one_record($table_name, $where);


      


      if(isset($results->options)){


          wp_cache_set($results->id, $results, 'arfform');


          $results->options = maybe_unserialize($results->options);


      }


      return stripslashes_deep($results);


  }

function getsiteurl()
{
	global $arsettingmodel;
	$siteurl = $arsettingmodel->checkdbstatus();
	return $siteurl;
}



    function getAll( $where = array(), $order_by = '', $limit = '', $is_ref_form=0 ){


        global $wpdb, $MdlDb, $armainhelper;


        


        if(is_numeric($limit))


            $limit = " LIMIT {$limit}";


            

		if($is_ref_form == 1)
        	$query = 'SELECT * FROM ' . $MdlDb->ref_forms . $armainhelper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;
		else
			$query = 'SELECT * FROM ' . $MdlDb->forms . $armainhelper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;

            


        if ($limit == ' LIMIT 1' or $limit == 1){


            if(is_array($where))
			{
				if($is_ref_form == 1)
                	$results = $MdlDb->get_one_record($MdlDb->ref_forms, $where, '*', $order_by);
				else
					$results = $MdlDb->get_one_record($MdlDb->forms, $where, '*', $order_by);
			}
            else
			{

                $results = $wpdb->get_row($query);
			}

                


            if($results){


                wp_cache_set($results->id, $results, 'arfform');


                $results->options = maybe_unserialize($results->options);


            }


        }else{


            if(is_array($where))
			{
				if($is_ref_form == 1)
                	$results = $MdlDb->get_records($MdlDb->ref_forms, $where, $order_by, $limit);
				else
					$results = $MdlDb->get_records($MdlDb->forms, $where, $order_by, $limit);
			}
            else
			{

                $results = $wpdb->get_results($query);
			}

            


            if($results){


                foreach($results as $result){


                    wp_cache_set($result->id, $result, 'arfform');


                    $result->options = maybe_unserialize($result->options);


                }


            }


        }


      


        return stripslashes_deep($results);


    }





  function validate( $values ){


      $errors = array();

      return apply_filters('arfvalidationofcurrentform', $errors, $values);


  }

  function has_field($type, $form_id, $single=true){


        global $MdlDb;


        if($single)


            $included = $MdlDb->get_one_record($MdlDb->fields, compact('form_id', 'type'));


        else


            $included = $MdlDb->get_records($MdlDb->fields, compact('form_id', 'type'));


        return $included;


    }

  function post_type($form_id){


        if(is_numeric($form_id)){


            global $MdlDb;


            $cache = wp_cache_get($form_id, 'arfform');


            if($cache)


                $form_options = $cache->options;


            else


                $form_options = $MdlDb->get_var($MdlDb->forms, array('id' => $form_id), 'options');


            $form_options = maybe_unserialize($form_options);


            return (isset($form_options['post_type'])) ? $form_options['post_type'] : 'post';


        }else{


            $form = (array) $form_id;


            return (isset($form['post_type'])) ? $form['post_type'] : 'post';


        }


    }



}