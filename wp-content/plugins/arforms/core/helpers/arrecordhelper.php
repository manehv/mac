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

class arrecordhelper{

	function arrecordhelper(){

		add_filter('arfemailvalue', array(&$this, 'email_value'), 10, 3);

    }
	
	function email_value($value, $meta, $entry){


        global $arffield, $db_record, $arfieldhelper;


        if($entry->id != $meta->entry_id)


            $entry = $db_record->getOne($meta->entry_id);


        $field = $arffield->getOne($meta->field_id);


        if(!$field)


            return $value;



        $field->field_options = maybe_unserialize($field->field_options);


        switch($field->type){

            case 'file':


                $value = $arfieldhelper->get_file_name($value);


                break;


            case 'date':


                $value = $arfieldhelper->get_date($value);


        }


        


        if (is_array($value)){


            $new_value = '';


            foreach($value as $val){


                if (is_array($val))


                    $new_value .= implode(', ', $val) . "\n";


            }


            if ($new_value != '')
                $value = @rtrim($new_value,',');


        }


        return $value;


    }
	
	function setup_edit_vars($values, $record){

        $values['entry_key'] = ($_POST and isset($_POST['entry_key']))?$_POST['entry_key']:$record->entry_key;

        $values['form_id'] = $record->form_id;

        return apply_filters('arfsetupeditentryvars', $values, $record);

    }

    function enqueue_scripts($params){ 

        do_action('arfenqueueformscripts', $params);

    }
	
	function allow_delete($entry){


        global $user_ID;


        $allowed = false;


        if(current_user_can('arfdeleteentries'))


            $allowed = true;


        if($user_ID and !$allowed){


            if(is_numeric($entry)){


                global $MdlDb;


                $allowed = $MdlDb->get_var( $MdlDb->entries, array('id' => $entry, 'user_id' => $user_ID) );


            }else{


                $allowed = ($entry->user_id == $user_ID);


            }


        }



        return apply_filters('arfallowdelete', $allowed, $entry);


    }
	
    function setup_new_vars($fields, $form='', $reset=false){

        global $arfform, $arfsettings, $arfsidebar_width, $arfieldhelper, $armainhelper, $arformhelper;

        $values = array();

        foreach (array('name' => '', 'description' => '', 'entry_key' => '') as $var => $default)

            $values[$var] = $armainhelper->get_post_param($var, $default);

            

        $values['fields'] = array();

        if ($fields){

            foreach($fields as $field){

                $field->field_options = maybe_unserialize($field->field_options);

                $default = $field->default_value;

              

                if ($reset)

                    $new_value = $default;

                else

                    $new_value = ($_POST and isset($_POST['item_meta'][$field->id]) and $_POST['item_meta'][$field->id] != '') ? $_POST['item_meta'][$field->id] : $default;

                

                $is_default = ($new_value == $default) ? true : false;

                    

                if (!is_array($new_value))

                    $new_value = apply_filters('arfgetdefaultvalue', $new_value, $field);

                

                $new_value = str_replace('"', '&quot;', $new_value);

                if($is_default)

                    $field->default_value = $new_value;

                else

                    $field->default_value = apply_filters('arfgetdefaultvalue', $field->default_value, $field);

                    

                $field_array = array(

                    'id' => $field->id,

                    'value' => $new_value,

                    'default_value' => $field->default_value,

                    'name' => $field->name,

                    'description' => $field->description,

                    'type' => apply_filters('arffieldtype', $field->type, $field, $new_value),

                    'options' => $field->options,

                    'required' => $field->required,

                    'field_key' => $field->field_key,

                    'field_order' => $field->field_order,

                    'form_id' => $field->form_id,
					
					'option_order' => maybe_unserialize($field->option_order),	
                );
				

                $opt_defaults = $arfieldhelper->get_default_field_options($field_array['type'], $field, true);

                $opt_defaults['required_indicator'] = '';

                

                foreach ($opt_defaults as $opt => $default_opt){

                    if($opt=="confirm_password_label")
					{
						$field_array[$opt] = (isset($field->field_options[$opt])) ? $field->field_options[$opt] : $default_opt;
					}else {
						$field_array[$opt] = (isset($field->field_options[$opt]) && $field->field_options[$opt] != '') ? $field->field_options[$opt] : $default_opt;
					}

                    unset($opt);

                    unset($default_opt);

                }

                  

                unset($opt_defaults);

                

                if ($field_array['size'] == '')

                    $field_array['size'] = $arfsidebar_width;

            

                

                if ($field_array['custom_html'] == '')

                    $field_array['custom_html'] = $arfieldhelper->get_basic_default_html($field->type);

                    

                $field_array = apply_filters('arfsetupnewfieldsvars', $field_array, $field);

                

                foreach((array)$field->field_options as $k => $v){

                    if(!isset($field_array[$k]))

                        $field_array[$k] = $v;

                    unset($k);

                    unset($v);

                }

                

                $values['fields'][] = $field_array;

             

                if (!$form or !isset($form->id))

                    $form = $arfform->getOne($field->form_id);

            }



            $form->options = maybe_unserialize($form->options);

            if (is_array($form->options)){

                foreach ($form->options as $opt => $value)

                    $values[$opt] = $armainhelper->get_post_param($opt, $value);

            }

            

            if (!isset($values['custom_style']))

                $values['custom_style'] = ($arfsettings->load_style != 'none');

                

            if (!isset($values['email_to']))

                $values['email_to'] = '';



            if (!isset($values['submit_value']))

                $values['submit_value'] = $arfsettings->submit_value;



            if (!isset($values['success_msg']))

                $values['success_msg'] = $arfsettings->success_msg;



            if (!isset($values['akismet']))

                $values['akismet'] = '';



            if (!isset($values['before_html']))

                $values['before_html'] = $arformhelper->get_default_html('before');



            if (!isset($values['after_html']))

                $values['after_html'] = $arformhelper->get_default_html('after');

        }

        

        return apply_filters('arfsetupnewentry', $values);

    }

    function encode_value($line, $from_encoding, $to_encoding){




        $convmap = false;


        switch($to_encoding){


            case 'macintosh':


                $convmap = array(


                    256, 304, 0, 0xffff,


                    306, 337, 0, 0xffff,


                    340, 375, 0, 0xffff,


                    377, 401, 0, 0xffff,


                    403, 709, 0, 0xffff,


                    712, 727, 0, 0xffff,


                    734, 936, 0, 0xffff,


                    938, 959, 0, 0xffff,


                    961, 8210, 0, 0xffff,


                    8213, 8215, 0, 0xffff,


                    8219, 8219, 0, 0xffff,


                    8227, 8229, 0, 0xffff,


                    8231, 8239, 0, 0xffff,


                    8241, 8248, 0, 0xffff,


                    8251, 8259, 0, 0xffff,


                    8261, 8363, 0, 0xffff,


                    8365, 8481, 0, 0xffff,


                    8483, 8705, 0, 0xffff,


                    8707, 8709, 0, 0xffff,


                    8711, 8718, 0, 0xffff,


                    8720, 8720, 0, 0xffff,


                    8722, 8729, 0, 0xffff,


                    8731, 8733, 0, 0xffff,


                    8735, 8746, 0, 0xffff,


                    8748, 8775, 0, 0xffff,


                    8777, 8799, 0, 0xffff,


                    8801, 8803, 0, 0xffff,


                    8806, 9673, 0, 0xffff,


                    9675, 63742, 0, 0xffff,


                    63744, 64256, 0, 0xffff,


                );


            break;


            case 'ISO-8859-1':


                $convmap = array(256, 10000, 0, 0xffff);


            break;


        }


        


        if (is_array($convmap))


            $line = mb_encode_numericentity($line, $convmap, $from_encoding);



		
		
        if ($to_encoding != $from_encoding)


            return iconv($from_encoding, $to_encoding.'//IGNORE', $line);


        else


            return $line;


    }
	
	function display_value($value, $field, $atts=array()){


        global $wpdb, $arfieldhelper, $armainhelper;


        $defaults = array(


            'type' => '', 'show_icon' => true, 'show_filename' => true, 


            'truncate' => false, 'sep' => ', ', 'attachment_id' => 0, 'form_id' => $field->form_id,


            'field' => $field


        );


        $atts = wp_parse_args( $atts, $defaults );


        $field->field_options = maybe_unserialize($field->field_options);


        if(!isset($field->field_options['post_field']))


            $field->field_options['post_field'] = '';


        if(!isset($field->field_options['custom_field']))


            $field->field_options['custom_field'] = '';


        if ($value == '') return $value;


        $value = maybe_unserialize($value);


        if(is_array($value))


            $value = stripslashes_deep($value);


        $value = apply_filters('arfdisplayvaluecustom', $value, $field, $atts);


        $new_value = '';


        if (is_array($value)){


            foreach($value as $val){


                if (is_array($val)){ 


                    $new_value .= implode($atts['sep'], $val);


                    if($atts['type'] != 'data')


                        $new_value .= "<br/>";


                }


                unset($val);


            }


        }





        if (!empty($new_value))


            $value = $new_value;


        else if (is_array($value))


            $value = implode($atts['sep'], $value);


        if ($atts['truncate'] and $atts['type'] != 'image')


            $value = $armainhelper->truncate($value, 50);


        if ($atts['type'] == 'image'){


            $value = '<img src="'.$value.'" height="50px" alt="" />';


        }else if ($atts['type'] == 'file'){


            $old_value = $value;


            $value = '';
			
			
            if ($atts['show_icon'])


                $value .= $arfieldhelper->get_file_icon($old_value);


            if ($atts['show_icon'] and $atts['show_filename'])


                $value .= '<br/>';
				
			if ($atts['show_filename'] && !$atts['show_icon'])


                $value .= $arfieldhelper->get_file_name($old_value);

        }else if ($atts['type'] == 'date'){


            $value = $arfieldhelper->get_date($value);


        }else if ($atts['type'] == 'textarea'){

            $value = nl2br($value);
        } else if( $atts['type'] == 'like' ){
			if( $value != '' ) {
				$class = ($value == '1') ? 'arf_like_btn' : 'arf_dislike_btn';	
				
				if($value == '1')
					$value = '<label style="margin-left:0;" class="'.$class.' active"><img src="'.ARFURL.'/images/like-icon.png" /></label>';
				else
					$value = '<label style="margin-left:0;" class="'.$class.' active"><img src="'.ARFURL.'/images/dislike-icon.png" /></label>';					
			}
		}

		if( $field->type == 'select' || $field->type == 'checkbox' || $field->type == 'radio' )
		{			
			$field_opts	= $wpdb->get_row( $wpdb->prepare("SELECT entry_value FROM ".$wpdb->prefix."arf_entry_values WHERE field_id='%d' AND entry_id='%d'", "-".$field->id, $atts['entry_id']) );
			
			if( $field_opts )
			{
				$field_opts	= maybe_unserialize($field_opts->entry_value);
				if( $field->type == 'checkbox' )
				{
					if( $field_opts && count($field_opts) > 0 )
					{
						$temp_value = "";
						foreach($field_opts as $new_field_opt)
						{
							$temp_value	.= $new_field_opt['label']." (".$new_field_opt['value']."), ";
						}
						$temp_value	= @trim($temp_value);
						$value		= rtrim($temp_value, ",");	
					}					
				} else {
					$value	= $field_opts['label']." (".$field_opts['value'].")";
				}
			}	
		}
			
        return apply_filters('arfdisplayvalue', $value, $field, $atts);


    }

    function get_post_or_entry_value($entry, $field, $atts=array(),$is_for_mail = false){
		
        global $arfrecordmeta;



        if(!is_object($entry)){


            global $db_record;


            $entry = $db_record->getOne($entry);


        }



        $field->field_options = maybe_unserialize($field->field_options);



        if($entry->attachment_id){


            if(!isset($field->field_options['custom_field']))


                $field->field_options['custom_field'] = '';


            


            if(!isset($field->field_options['post_field']))


                $field->field_options['post_field'] = '';


              


            $links = true;


            if(isset($atts['links']))


                $links = $atts['links'];

				$value = $arfrecordmeta->get_entry_meta_by_field($entry->id, $field->id, true, $is_for_mail);


		}else{
			
			$value = $arfrecordmeta->get_entry_meta_by_field($entry->id, $field->id,true, $is_for_mail);
		}


        return $value;


    }
}