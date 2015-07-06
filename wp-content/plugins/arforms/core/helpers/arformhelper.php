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

class arformhelper{

	function arformhelper(){


        add_filter('arfsetupnewformvars', array(&$this, 'setup_new_variables'));


        add_filter('arfsetupeditformvars', array(&$this, 'setup_edit_variables'));


    }
	
	function setup_new_variables($values){
	
		global $arformhelper, $armainhelper;


        foreach ($arformhelper->get_default_options() as $var => $default)


            $values[$var] = $armainhelper->get_param($var, $default);


        return $values;


    }

    function setup_edit_variables($values){

        $values['id'] = isset($values['id']) ? $values['id'] : '';

        global $arfform, $style_settings, $arformhelper, $armainhelper;


        $record = $arfform->getOne($values['id']);
        
        if( isset($record ) ){
        
        foreach (array('is_loggedin' => $record->is_loggedin, 'can_edit' => $record->can_edit) as $var => $default)


            $values[$var] = $armainhelper->get_param($var, $default);
        
        
        }

        foreach ($arformhelper->get_default_options() as $opt => $default){


            if (!isset($values[$opt]))


                $values[$opt] = ($_POST and isset($_POST['options'][$opt])) ? $_POST['options'][$opt] : $default;


        }


        $values['also_email_to'] = (array)$values['also_email_to'];


        return $values;


    }
	
    function get_direct_link($key){


        global $arfsiteurl;	


        $target_url = esc_url(site_url() . '/index.php?plugin=ARForms&controller=forms&arfaction=preview&form='.$key);


        return $target_url;


    }


    function replace_shortcodes($html, $form, $title=false, $description=false){


        foreach (array('form_name' => $title, 'form_description' => $description, 'entry_key' => true) as $code => $show){


            if ($code == 'form_name'){


                $replace_with = $form->name;


            }else if ($code == 'form_description'){


                    $replace_with = $form->description;


            }else if($code == 'entry_key' and isset($_GET) and isset($_GET['entry'])){


                $replace_with = $_GET['entry'];


            }


                

            if (($show == true || $show == 'true') && $replace_with != '' ){


                $html = str_replace('[if '.$code.']', '', $html); 


        	    $html = str_replace('[/if '.$code.']', '', $html);


            }else{


                $html = preg_replace('/(\[if\s+'.$code.'\])(.*?)(\[\/if\s+'.$code.'\])/mis', '', $html);


            }


            $html = str_replace('['.$code.']', $replace_with, $html);   


        }   


        

        $html = str_replace('[form_key]', $form->form_key, $html);
		
		$html = trim($html);

        return apply_filters('arfformreplaceshortcodes', stripslashes($html), $form);


    }
	
	function get_default_options(){


        global $style_settings, $arfsettings;


        return array(


            'edit_value' => $style_settings->update_value, 'edit_msg' => $style_settings->edit_msg, 


            'is_loggedin' => 0, 'logged_in_role' => '', 'can_edit' => 0, 


            'editable_role' => '', 'open_editable' => 0, 'open_editable_role' => '', 


            'copy' => 0, 'single_entry' => 0, 'single_entry_type' => 'user', 


            'success_page_id' => '', 'success_url' => '', 'ajax_submit' => 0, 


            'create_post' => 0, 'cookie_expiration' => 8000,


            'post_type' => 'post', 'post_category' => array(), 'post_content' => '', 


            'post_excerpt' => '', 'post_title' => '', 'post_name' => '', 'post_date' => '',


            'post_status' => '', 'post_custom_fields' => array(), 'post_password' => '',


            'plain_text' => 0, 'also_email_to' => array(), 'update_email' => 0,


            'email_subject' => '', 'email_message' => '[default-message]', 


            'inc_user_info' => 1, 'auto_responder' => 0, 'ar_plain_text' => 0, 


            'ar_email_to' => '', 'ar_reply_to' => get_option('admin_email'), 


            'ar_reply_to_name' => get_option('blogname'), 'ar_email_subject' => '', 


            'ar_email_message' => __('Thank you for subscription with us. We will contact you soon.', 'ARForms'), 
			
			
			'ar_update_email' => 0, 'chk_admin_notification' => 0,
			
			
			'form_custom_css' => '', 'label_position' => $style_settings->position, 'is_custom_css'=>0,
			
			
			'ar_admin_email_to' => get_option('admin_email'), 'ar_admin_reply_to' => get_option('admin_email'), 
			
			
			'ar_admin_email_message' => __('[ARF_form_all_values]', 'ARForms'), 


            'ar_admin_reply_to_name' => get_option('blogname'), 'email_to' => $arfsettings->reply_to, 
			
			
			'reply_to' => $arfsettings->reply_to, 'reply_to_name' => get_option('blogname'),
			
			
			'display_title_form' => '1', 
			
			'ar_user_from_name' => (isset($arfsettings->ar_user_from_name)) ? $arfsettings->ar_user_from_name : '' , 'ar_user_from_email' => (isset($arfsettings->ar_user_from_email))? $arfsettings->ar_user_from_email : '' , 'ar_admin_from_name' => (isset($arfsettings->ar_admin_from_name)) ? $arfsettings->ar_admin_from_name : '', 'ar_admin_from_email' => (isset($arfsettings->ar_admin_from_email)) ? $arfsettings->ar_admin_from_email : '',
			
			//custom css block
			'arf_form_outer_wrapper' => '', 'arf_form_inner_wrapper' => '',			
			
			'arf_form_title' => '', 'arf_form_description' => '', 'arf_form_element_wrapper' => '',
			
			'arf_form_element_label' => '', 'arf_form_submit_button' => '',  'arf_form_success_message' => '',
			
			'arf_form_elements' => '', 'arf_submit_outer_wrapper' => '', 'arf_form_next_button'	=> '',
			
			'arf_form_previous_button' => '', 'arf_form_error_message' => '', 'arf_form_page_break' => '',
			
			'arf_form_fly_sticky'=>'', 'arf_form_modal_css'=>'', 'arf_form_other_css' => '',
			//custom css block
			'admin_email_subject' => '[form_name] '.__('Form submitted on', 'ARForms').' [site_name] ',
			
			'arf_form_link_css' => '', 'arf_form_button_css' => '', 'arf_form_link_hover_css' => '', 'arf_form_button_hover_css' => '',

        );


    }

	function forms_dropdown_new( $field_name, $field_value='', $blank=true, $field_id=false, $onchange=false,$multiple = false,$is_import_export=0 ){


        global $arfform, $armainhelper;


        if (!$field_id)


            $field_id = $field_name;


         if($multiple == 'mutliple')
		{
			$multiple = "multiple";
			$array = '[]';
		}
		
		$optionheight = '';
		$customfontsize = '';
		if($is_import_export == 1)
		{
			$optionheight = 'style="height:25px;font-size:15px;"';
			$customfontsize = "font-size:15px;";
		}

        $where = apply_filters('arfformsdropdowm', "is_template=0 AND is_enable=1 AND (status is NULL OR status = '' OR status = 'published')", $field_name);


        $forms = $arfform->getAll($where, ' ORDER BY name');


        ?>
		<?php
			if(is_rtl())
			{
				$sel_frm_box = 'text-align:right;width:360px;outline:none;'.$customfontsize;
			}
			else
			{
				$sel_frm_box = 'text-align:left;width:360px;outline:none;'.$customfontsize;
			}
		?>
        <select name="<?php echo $field_name.$array; ?>" id="<?php echo $field_id ?>" style=" <?php echo $sel_frm_box; ?>" class="frm-dropdown" <?php if ($onchange) echo 'onchange="'.$onchange.'"'; ?> data-width="360px" data-size="10" <?php echo $multiple;?>>


            <?php if ($blank){ ?>


            <option <?php echo $optionheight;?> value=""><?php echo ($blank == 1) ? '' : '- '. $blank .' -'; ?></option>


            <?php } ?>


            <?php foreach($forms as $form){ ?>


                <option <?php echo $optionheight;?> value="<?php echo $form->id; ?>" <?php selected($field_value, $form->id); ?>><?php echo $armainhelper->truncate($form->name, 33); ?></option>


            <?php } ?>


        </select>


        <?php

 
    }
	
    function forms_dropdown_widget( $field_name, $field_value='', $blank=true, $field_id=false, $onchange=false ){


        global $arfform, $armainhelper;


        if (!$field_id)


            $field_id = $field_name;


        


        $where = apply_filters('arfformsdropdowm', "is_template=0 AND is_enable=1 AND (status is NULL OR status = '' OR status = 'published')", $field_name);


        $forms = $arfform->getAll($where, ' ORDER BY name');


        ?>


        <select name="<?php echo $field_name; ?>" id="<?php echo $field_id ?>" style="width:225px;" class="frm-dropdown" <?php if ($onchange) echo 'onchange="'.$onchange.'"'; ?> data-width="225px" data-size="15">


            <?php if ($blank){ ?>


            <option value=""><?php echo ($blank == 1) ? '' : '- '. $blank .' -'; ?></option>


            <?php } ?>


            <?php foreach($forms as $form){ ?>


                <option value="<?php echo $form->id; ?>" <?php selected($field_value, $form->id); ?>><?php echo $armainhelper->truncate($form->name, 33); ?></option>


            <?php } ?>


        </select>


        <?php

 
    }

    function setup_new_vars(){


        global $MdlDb, $arfsettings, $arformhelper, $armainhelper;


        $values = array();


        foreach (array('name' => __('Untitled Form', 'ARForms'), 'description' => '') as $var => $default)


            $values[$var] = $armainhelper->get_param($var, $default);


        


        if(apply_filters('arfusewpautop', true))


            $values['description'] = wpautop($values['description']);


        


        foreach (array('form_id' => '', 'is_loggedin' => '', 'can_edit' => '', 'is_template' => 0) as $var => $default)


            $values[$var] = $armainhelper->get_param($var, $default);


            


        $values['form_key'] = ($_POST and isset($_POST['form_key'])) ? $_POST['form_key'] : ($armainhelper->get_unique_key('', $MdlDb->forms, 'form_key'));


        


        $defaults = $arformhelper->get_default_opts();


        foreach ($defaults as $var => $default){


            if($var == 'notification'){


                $values[$var] = array();


                foreach($default as $k => $v){


                    $values[$var][$k] = (isset($_POST) and $_POST and isset($_POST['notification'][$var])) ? $_POST['notification'][$var] : $v;


                    unset($k);


                    unset($v);


                }


            }else{


                $values[$var] = (isset($_POST) and $_POST and isset($_POST['options'][$var])) ? $_POST['options'][$var] : $default;


            }


            


            unset($var);


            unset($default);


        }


            


        $values['custom_style'] = (isset($_POST) and $_POST and isset($_POST['options']['custom_style'])) ? $_POST['options']['custom_style'] : ($arfsettings->load_style != 'none');


        $values['before_html'] = $arformhelper->get_default_html('before');


        $values['after_html'] = $arformhelper->get_default_html('after');


        


        return apply_filters('arfsetupnewformvars', $values);


    }


    


    function setup_edit_vars($values, $record){


        global $arfform, $armainhelper;





        $values['form_key'] = @$armainhelper->get_param('form_key', $record->form_key);


        $values['is_template'] = @$armainhelper->get_param('is_template', $record->is_template);





        return apply_filters('arfsetupeditformvars', $values);


    }


    


    function get_default_opts(){


        global $arfsettings;


        


        return array(


            'notification' => array(

                array('email_to' => $arfsettings->reply_to, 'reply_to' => $arfsettings->reply_to, 
				
				'reply_to_name' => get_option('blogname'), 'cust_reply_to' => '', 'cust_reply_to_name' => '')

            ),


            'submit_value' => $arfsettings->submit_value, 'success_action' => 'message',


            'success_msg' => $arfsettings->success_msg, 'show_form' => 0, 'akismet' => '',
			
			'ar_email_message' => __('Thank you for subscription with us. We will contact you soon.', 'ARForms'),	
			
			'ar_admin_email_message' => __('[ARF_form_all_values]', 'ARForms'), 

            'no_save' => 0, 'admin_email_subject' => '[form_name] '.__('Form submitted on', 'ARForms').' [site_name] ',


        );


    }


    


    function get_default_html($loc){


        if ($loc == 'before'){


            $default_html ='[if form_name]<div class="formtitle_style">[form_name]</div>[/if form_name]

						[if form_description]<div class="arf_field_description formdescription_style">[form_description]</div>[/if form_description]';


        }else{


            $default_html = '';


        }


        return $default_html;


    }
	
	function forms_dropdown( $field_name, $field_value='', $blank=true, $field_id=false, $onchange=false ){


        global $arfform, $armainhelper;


        if (!$field_id)


            $field_id = $field_name;


        


        $where = apply_filters('arfformsdropdowm', "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", $field_name);


        $forms = $arfform->getAll($where, ' ORDER BY name');

		global $wpdb, $MdlDb, $db_record;
		
		$record_count = $wpdb->get_results( "SELECT $MdlDb->forms.id, COUNT($MdlDb->entries.id) AS count_num FROM $MdlDb->entries RIGHT JOIN $MdlDb->forms ON $MdlDb->entries.form_id=$MdlDb->forms.id WHERE $MdlDb->forms.is_template=0 AND $MdlDb->forms.is_enable=1 AND ($MdlDb->forms.status is NULL OR $MdlDb->forms.status = '' OR $MdlDb->forms.status = 'published') group by $MdlDb->forms.id", OBJECT_K); 
		?>


        <select name="<?php echo $field_name; ?>" id="<?php echo $field_id ?>" style="width:250px;" class="frm-dropdown" <?php if ($onchange) echo 'onchange="'.$onchange.'"'; ?> data-width="250px" data-size="20">


            <?php if ($blank){ ?>


            <option value=""><?php echo ($blank == 1) ? '' : '- '. $blank .' -'; ?></option>


            <?php } ?>


            <?php foreach($forms as $form){ ?>


                <option value="<?php echo $form->id; ?>" <?php selected($field_value, $form->id); ?>><?php echo $armainhelper->truncate($form->name, 33); ?> (<?php echo isset($record_count[$form->id]->count_num) ? $record_count[$form->id]->count_num : 0 ;?>)</option> 


            <?php } ?>


        </select>


        <?php

 
    }
	
	function replace_field_shortcode($content)
	{
		global $wpdb, $arffield;
				
        $tagregexp = '';
				 
        preg_match_all("/\[(if )?($tagregexp)(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);	
		
		if( $matches and $matches[3] )
		{
			foreach($matches[3] as $shortcode)
			{
				if( $shortcode )
				{
					global $arffield;
					$display=false;
					$show='one'; 
					$odd='';
					
					$field_ids = explode(':',$shortcode);
					
					if(is_array($field_ids))
					{
						$field_id  = end($field_ids); 
						$is_checkbox = explode(".",$field_id);
						
						if(count($is_checkbox)>0)
						{
							$field_id = $is_checkbox[0];
							$is_checkbox[1] = isset( $is_checkbox[1] ) ? $is_checkbox[1] : '';
							$option_id = $is_checkbox[1];
						}
						else
						{
							$option_id = "";
						}
					}
					
					$field = $arffield->getOne( $field_id );
			
					if( !isset($field) || !$field->id )
						return $content;
						
					if($field)
					{
						$field_opts = maybe_unserialize($field->field_options);
						
						$is_sep_val = $field_opts['separate_value'];
						
						$fieldoptions = maybe_unserialize($field->options);
						
						if(isset($option_id) && $option_id !="")
							$optionvalue = $fieldoptions[$option_id];

						if($field->type == "checkbox")
						{
							if($is_sep_val == 1)
							{
								$optionvalue1 = $optionvalue['value'];
								$optionlabel = $optionvalue['label'];
								
								$replace_with = '['.$optionvalue['label'].':'.( ( $field->ref_field_id > 0 ) ? $field->ref_field_id : $field_id ).'.'.$option_id.']';
							}
							else
							{
								if(is_array($optionvalue))
								{
									$optionvalue = $optionvalue['label'];
								}
								$replace_with = '['.$optionvalue.':'.( ( $field->ref_field_id > 0 ) ? $field->ref_field_id : $field_id ).'.'.$option_id.']';
							}
						}
						else
						{
							$replace_with = '['.$field->name.':'.( ( $field->ref_field_id > 0 ) ? $field->ref_field_id : $field_id ).']';
						}
						
					}
					
					$content = str_replace('['.$shortcode.']', $replace_with, $content);
					
				}
			}
		}				
		
		return $content; 
	}
	
	function replace_field_shortcode_import($content, $res_field_id, $new_field_id)
	{
	
		if( ! $res_field_id || ! $new_field_id )
			return $content; 
		 		
		global $wpdb, $arffield;
				
        $tagregexp = '';
				 
        preg_match_all("/\[(if )?($tagregexp)(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);	
		
		if( $matches and $matches[3] )
		{
			foreach($matches[3] as $shortcode)
			{
				if( $shortcode )
				{
					global $arffield;
					$display=false;
					$show='one'; 
					$odd='';
					
					$field_ids = explode(':',$shortcode);
					$field_id  = end($field_ids); 
					
					if(is_array($field_ids))
					{
						$field_id  = end($field_ids); 
						$is_checkbox = explode(".",$field_id);
						
						if(count($is_checkbox)>0)
						{
							$field_id = $is_checkbox[0];
							$is_checkbox[1] = isset( $is_checkbox[1] ) ? $is_checkbox[1] : '';
							$option_id = $is_checkbox[1];
						}
						else
						{
							$option_id = "";
						}
					}
					
					if( $field_id == $res_field_id )
					{ 	
						$field = $arffield->getOne( $new_field_id );
			
						if( $field )
						{	
						$field_opts = maybe_unserialize($field->field_options);
						
						$is_sep_val = $field_opts['separate_value'];
						
						$fieldoptions = maybe_unserialize($field->options);
						
						if(isset($option_id) && $option_id !="")
							$optionvalue = $fieldoptions[$option_id];
						
						if($field->type == "checkbox")
						{
							if($is_sep_val == 1)
							{
								$optionvalue1 = $optionvalue['value'];
								$optionlabel = $optionvalue['label'];
								
								$replace_with = '['.$optionvalue['label'].':'.( ( $field->ref_field_id > 0 ) ? $field->ref_field_id : $field_id ).'.'.$option_id.']';
							}
							else
							{
								if(is_array($optionvalue))
								{
									$optionvalue = $optionvalue['label'];
								}
								$replace_with = '['.$optionvalue.':'.( ( $field->ref_field_id > 0 ) ? $field->ref_field_id : $field_id ).'.'.$option_id.']';
							}
						}
						else
						{
							$replace_with = '['.$field->name.':'.( ( $field->ref_field_id > 0 ) ? $field->ref_field_id : $field_id ).']';
						}
					
							$content = str_replace('['.$shortcode.']', $replace_with, $content);
						}						
					}
				}
			}
		}				
		
		return $content; 
	}

}