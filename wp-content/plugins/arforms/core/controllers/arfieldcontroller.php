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

class arfieldcontroller{

    function arfieldcontroller(){

        add_filter('arfdisplayfieldtype', array(&$this, 'show_normal_field'), 10, 2);

        add_filter('arfdisplayfieldhtml', array(&$this, 'arfdisplayfieldhtml'), 10, 2);

        add_action('arfdisplayfieldtype1', array(&$this, 'show_other'), 10, 3);

        add_filter('arffieldtype', array( &$this, 'change_type'), 15, 2);
		
		add_filter('arfdisplaysavedfieldvalue', array( &$this, 'use_field_key_value'), 10, 3);
	
        add_action('arfdisplayaddedfields', array(&$this, 'show')); 

        add_filter('arfdisplayfieldoptions', array(&$this, 'display_field_options'));
		
		add_filter('arfdisplayfieldoptions', array(&$this, 'display_basic_field_options'));

        add_action('form_fields', array(&$this, 'form_fields'), 10, 2);

        add_action('arffieldinputhtml', array(&$this, 'input_html'));
		
		add_action('arffieldinputhtml', array(&$this, 'input_fieldhtml'));

        add_filter('arfaddfieldclasses', array(&$this, 'add_field_class'), 20, 2);

        add_action('arfaddsepratevalues', array($this, 'add_separate_value_opt_label')); 

        add_action('arfdatepickerjs', array(&$this, 'arfdatepickerjs'), 10, 2);
		
		add_action('wp_ajax_arfaddnewfieldoption', array(&$this, 'add_field_option'));

		add_action('wp_ajax_arfinsertnewfield', array(&$this, 'create') );
		
		add_action('wp_ajax_arfupdatefieldname', array(&$this, 'edit_name') );
		
		add_action('wp_ajax_arfupdatefielddescription', array(&$this, 'edit_description') );
		
		add_action('wp_ajax_arfmakereqfield', array(&$this, 'mark_required') ); 
    
		add_action('wp_ajax_arfupdateajaxoption', array(&$this, 'arfupdateajaxoption') );
		
		add_action('wp_ajax_arfdeleteformfield', array(&$this, 'destroy') );
		
		add_action('wp_ajax_arfeditorfieldoption', array(&$this, 'edit_option') );
	
		add_action('wp_ajax_arfdeletefieldoption', array(&$this, 'delete_option'));
		
		add_action('wp_ajax_arfpresetoptions', array(&$this, 'arfpresetoptions') );
		
		add_action('wp_ajax_arfupdatefieldorder', array(&$this, 'update_order') );
		
		add_filter('arffieldvaluesaved', array( &$this, 'check_value'), 50, 3);
		
		add_filter('arffieldlabelseen', array( &$this, 'check_label'), 10, 3);
		
		add_action('wp_ajax_arfupdateoptionorder', array(&$this, 'arfupdateoptionorder') );
		
		add_action('wp_ajax_arf_is_prevalidateform_outside', array(&$this, 'arf_prevalidateform_outside') );
		
		add_action('wp_ajax_nopriv_arf_is_prevalidateform_outside', array(&$this, 'arf_prevalidateform_outside') );
		
		add_action('wp_ajax_arf_is_resetformoutside', array(&$this, 'arf_resetformoutside') );
		
		add_action('wp_ajax_nopriv_arf_is_resetformoutside', array(&$this, 'arf_resetformoutside') );
		
	}
	
	function create(){ 
	
		$is_create_new_field = 1;


        global $arffield, $arfajaxurl,$wpdb,$MdlDb, $arfform, $arfieldhelper; 


        $field_data = $_POST['field'];


        $form_id = $_POST['form_id'];


        $values = array();


        if(class_exists('arformmodel'))


            $values['post_type'] = $arfform->post_type($form_id);


        
		$duplicate_id = ( isset($_POST['field_duplicate_id']) and $_POST['field_duplicate_id'] != '' ) ? $_POST['field_duplicate_id'] : '';
	
        $field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables($field_data, $form_id));
		
		
		if( isset($_POST['confirm_password']) and $_POST['confirm_password'] == '1' )
		{
			$field_values['name'] = 'Confirm Password'; 	
		}
						
		if( $duplicate_id != '' ) {
			$field_values_new = $arfieldhelper->setup_edit_variables($arffield->getOne($duplicate_id));
			
			$field_values_new['confirm_password'] = 0;
			
			$field_values_new['field_order']= $field_values['field_order'];
			$field_values_new['conditional_logic']= maybe_serialize($field_values_new['conditional_logic']);
			$field_values_new['form_id'] 		= $field_values['form_id'];
			$field_values_new['id'] 			= '';
			$field_values_new['options'] 		= maybe_serialize( $field_values_new['options'] ); 
			$field_values_new['default_value'] 	= maybe_serialize( $field_values_new['default_value'] ); 
			unset($field_values_new['id']);
			unset($field_values_new['blank']);
			unset($field_values_new['invalid']);
			unset($field_values_new['unique_msg']);
			unset($field_values_new['form_name']);
		
			$field_values = array();
			$field_values = $field_values_new;	
		}	

        $field_id = $arffield->create( $field_values );


        


        if ($field_id){

			$temp_field_data = $wpdb->update($MdlDb->fields, array('ref_field_id'=>$field_id,'new_field'=>'1'), array('id'=>$field_id));	  

            $field = $arfieldhelper->setup_edit_variables($arffield->getOne($field_id));

			$original_field = ( isset($_POST['original_field']) and $_POST['original_field'] != '' ) ? $_POST['original_field'] : ''; 
	    if( $duplicate_id != '' )
				$field['value'] = $field['default_value'];
									
            $field_name = "item_meta[$field_id]";


            $id = $form_id;
			
			$colsize = @$_POST["colsize"];
			
			if( $duplicate_id == '' )
			{
				if($colsize == "3col")
					$field["classes"] = "arf_3";
				elseif($colsize == "2col")
					$field["classes"] = "arf_2";
				else
					$field["classes"] = "arf_1";
			}
				
            require(VIEWS_PATH.'/newfield.php'); 

        }


        die();


    }
	
	function edit_name(){


        global $arffield;


        $id = str_replace('field_', '', $_POST['element_id']);


        $values = array('name' => trim($_POST['update_value']));


        if ($_POST['original_html'] == 'Untitled')


            $values['field_key'] = $_POST['update_value'];


        $form = $arffield->update($id, $values);


        echo stripslashes($_POST['update_value']);  


        die();


    }
	
	function edit_description(){


        global $arffield;


        $id = str_replace('field_', '', $_POST['element_id']);


        $arffield->update($id, array('description' => $_POST['update_value']));


        $description = stripslashes($_POST['update_value']);


        if(apply_filters('arfusewpautop', true))


            $description = wpautop($description);


        echo $description;
		

        die();


    } 
	
	function mark_required(){


        global $arffield;


        $arffield->update($_POST['field'], array('required' => $_POST['required']));


        die();


    }
	
	function arfupdateajaxoption(){


        global $arffield;


        $field = $arffield->getOne($_POST['field']);


        $field->field_options = maybe_unserialize($field->field_options);


        foreach(array('clear_on_focus', 'separate_value', 'default_blank') as $val){


            if(isset($_POST[$val])){


                $new_val = $_POST[$val];


                if($val == 'separate_value')


                    $new_val = (isset($field->field_options[$val]) and $field->field_options[$val]) ? 0 : 1;


                


                $field->field_options[$val] = $new_val;   


                unset($new_val);       


            }  


            unset($val);


        }



		global $wpdb;
		$wpdb->update($wpdb->prefix.'arf_fields', array('field_options' => maybe_serialize($field->field_options) ), array( 'id' => $_POST['field'] ), array('%s'), array('%d') );
        die();


    }
	
	function &show_normal_field($show, $field_type){


        if (in_array($field_type, array('hidden', 'user_id', 'break')))


            $show = false;


        return $show;


    }

    function &arfdisplayfieldhtml($show, $field_type){


        if (in_array($field_type, array('hidden', 'user_id', 'break', 'divider', 'html')))


            $show = false;


        return $show;


    }

    function show_other($field, $form, $total_page = 0){


        $field_name = "item_meta[$field[id]]";


        require(VIEWS_PATH .'/displayotheroptions.php');


    }

    function &change_type($type, $field){


        global $arfshowfields;


        if($type != 'user_id' and !empty($arfshowfields) and !in_array($field->id, $arfshowfields) and !in_array($field->field_key, $arfshowfields))


            $type = 'hidden';


        if($type == 'website') $type = 'url';

		
        return $type;    


    }

    function use_field_key_value($opt, $opt_key, $field){

        
        if((isset($field['use_key']) and $field['use_key']) or (isset($field['type']) and $field['type'] == 'data'))


            $opt = $opt_key;


        return $opt;


    }

    function show($field){


        global $arfajaxurl;


        $field_name = "item_meta[". $field['id'] ."]";


        require(VIEWS_PATH.'/displayfield.php');    


    }

    function display_field_options($display){

	if(isset($display['type']) and $display['type']!='') {
	
        switch($display['type']){


            case 'user_id':


            case 'hidden':


                $display['label_position'] = false;


                $display['description'] = false;


            case 'form':


                $display['required'] = false;


                $display['default_blank'] = false;


                break;


            case 'break':


                $display['required'] = false;


                $display['options'] = true;


                $display['default_blank'] = false;


                $display['css'] = false;


                break;


            case 'email':


            case 'url':


            case 'website':


            case 'phone':


            case 'image':


            case 'date':


            case 'number':


                $display['size'] = true;


                $display['invalid'] = true;


                $display['clear_on_focus'] = true;


                break;


            case 'password':


                $display['size'] = true;


                $display['clear_on_focus'] = true;


                break;


            case 'time':


                $display['size'] = true;


                break;


            case 'file':


                $display['invalid'] = true;


                $display['size'] = true;


                break;

            case 'html':


                $display['label_position'] = false;


                $display['description'] = false;


            case 'divider':


                $display['required'] = false;


                $display['default_blank'] = false;


                break;


        }
	
	}

        return $display;


    }
	
	function display_basic_field_options($display){

	if(isset($display['type']) and $display['type']!='') {
	
        switch($display['type']){


            case 'captcha':


                $display['required'] = false;


                $display['invalid'] = true;


                $display['default_blank'] = false;


            break;


            case 'radio':


                $display['default_blank'] = false;


            break;


            case 'text':


            case 'textarea':


                $display['size'] = true;


                $display['clear_on_focus'] = true;


            break;


            case 'select':


                $display['size'] = true;


            break;


        }

	}
        


        return $display;


    }
	
	function check_value($opt, $opt_key, $field){


        if(is_array($opt)){


            if(isset($field['separate_value']) and $field['separate_value']){


                $opt = isset($opt['value']) ? $opt['value'] : (isset($opt['label']) ? $opt['label'] : reset($opt));


            }else{


                $opt = (isset($opt['label']) ? $opt['label'] : reset($opt));


            }


        }


        return $opt;


    }
	
	function check_label($opt, $opt_key, $field){


        if(is_array($opt))


            $opt = (isset($opt['label']) ? $opt['label'] : reset($opt));


            


        return $opt;


    }

    function form_fields($field, $field_name){


        global $style_settings, $arfsettings, $arfeditingentry, $arffield;


        $entry_id = $arfeditingentry;


        if($field['type'] == 'form' and $field['form_select'])


            $dup_fields = $arffield->getAll("fi.form_id='$field[form_select]' and fi.type not in ('break', 'captcha')");


        require(VIEWS_PATH.'/formfields.php');


    }

    function input_html($field, $echo=true){


        global $arfsettings, $arfnovalidate;

        $add_html = '';

        if(isset($field['read_only']) and $field['read_only']){


            global $arfreadonly;


            if($arfreadonly == 'disabled' or (current_user_can('administrator') and is_admin())) return;


            $add_html .= ' readonly="readonly" ';

        }


        if($arfsettings->use_html){


            if($field['type'] == 'number'){


                if(!is_numeric($field['minnum']))


                    $field['minnum'] = 0;


                if(!is_numeric($field['maxnum']))


                    $field['maxnum'] = 9999999;


                if(!is_numeric($field['step']))


                    $field['step'] = 1;

				if($field['maxnum']>0)
					
					$add_html .= ' max="'.$field['maxnum'].'"';
					
				if($field['minnum']>0)
					
					$add_html .= ' min="'.$field['minnum'].'"';
					
				if($field['step']>0)
					
					$add_html .= ' step="'.$field['step'].'"';
				

            }else if(in_array($field['type'], array('url', 'email'))){


                if(!$arfnovalidate and isset($field['value']) and $field['default_value'] == $field['value'])


                    $arfnovalidate = true;


            }


        }


        


        if(isset($field['dependent_fields']) and $field['dependent_fields']){


            $trigger = ($field['type'] == 'checkbox' or $field['type'] == 'radio') ? 'onclick' : 'onchange';            

            $add_html .= ' '. $trigger .'="frmCheckDependent(this.value,\''.$field['id'].'\')"';

        }


        if($echo)


            echo $add_html;


        return $add_html;


    }

    function add_field_class($class, $field){


        if($field['type'] == 'scale' and isset($field['star']) and $field['star'])


            $class .= ' star';


        else if($field['type'] == 'date')


            $class .= ' frm_date';



        return $class;


    }

   	function add_separate_value_opt_label($field){


        $style = $field['separate_value'] ? '' : "style='display:none;'";


        echo '<div class="arfshowfieldclick">';


        echo '<div class="field_'. $field['id'] .'_option_key frm_option_val_label" '. $style .'>'. __('Option Label', 'ARForms') .'</div>';


        echo '<div class="field_'. $field['id'] .'_option_key frm_option_key_label" '. $style .'>'. __('Saved Value', 'ARForms') .'</div>';


        echo '</div>';


    }

    function arfdatepickerjs($field_id, $options){


        if(isset($options['unique'])){


            global $MdlDb, $wpdb, $arffield;


            $field = $arffield->getOne($options['field_id']);


            $field->field_options = maybe_unserialize($field->field_options);


            $query = "SELECT entry_value FROM $MdlDb->entry_metas WHERE field_id=". (int)$options['field_id'];


            if(is_numeric($options['entry_id'])){


                $query .= " and entry_id != ". (int)$options['entry_id'];


            }else{


                $disabled = wp_cache_get($options['field_id'], 'arfuseddates');


            }


            if(!isset($disabled) or !$disabled)


                $disabled = $wpdb->get_col($query);



            if(isset($post_dates) and $post_dates)


                $disabled = array_merge((array)$post_dates, (array)$disabled);


            $disabled = apply_filters('arfuseddates', $disabled, $field, $options);


            if(!$disabled)


                return;


            if(!is_numeric($options['entry_id']))


                wp_cache_set($options['field_id'], $disabled, 'arfuseddates');


            $formatted = array();    


            foreach($disabled as $dis) 


               $formatted[] = date('Y-n-j', strtotime($dis)); 


            $disabled = $formatted;


            unset($formatted);


            echo ',beforeShowDay: function(date){var m=(date.getMonth()+1),d=date.getDate(),y=date.getFullYear();var disabled='. json_encode($disabled) .';if($.inArray(y+"-"+m+"-"+d,disabled) != -1){return [false];} return [true];}';  


        }


    }

    function ajax_get_data($entry_id, $field_id, $current_field){


        global $arfrecordmeta, $arffield, $arrecordhelper, $arfieldhelper;


        $data_field = $arffield->getOne($field_id);


        $current = $arffield->getOne($current_field);


        $entry_value = $arrecordhelper->get_post_or_entry_value($entry_id, $data_field);


        $value = $arfieldhelper->get_display_value($entry_value, $data_field);

        if($value and !empty($value))


            echo "<p class='frm_show_it'>". $value ."</p>\n";



        echo '<input type="hidden" id="field_'. $current->field_key .'" name="item_meta['. $current_field .']" value="'. stripslashes(esc_attr($entry_value)) .'"/>';


        die();


    }

	function add_field_option(){


        global $arffield, $arfajaxurl;





        $id = $_POST['field_id'];


        $field = $arffield->getOne($id);
		
		if($field->type=='checkbox')
		{
			$fieldname = "Checkbox";
		
		}elseif($field->type=='radio') {
		
			$fieldname = "Radio";
		
		}elseif($field->type=='select'){
		
			$fieldname = "Select";
		
		}else{
			$fieldname = "Option";
		}

        $options = maybe_unserialize($field->options);


        if(!empty($options))


            $last = max(array_keys($options));


        else


            $last = 0;


        


        $opt_key = $last + 1;


        $first_opt = ( count($options) > 0 ) ? reset($options) : '0';


        $next_opt = count($options);


        if($first_opt != '')


            $next_opt++;


        $opt = __($fieldname, 'ARForms') .' '. $next_opt; 


        unset($next_opt);


        


        $field_val = $opt;


        $options[$opt_key] = $opt;

		global $wpdb;
		$wpdb->update($wpdb->prefix.'arf_fields', array('options' => maybe_serialize($options) ), array('id'=>$id), array('%s'), array('%d') );	
        $checked = '';





        $field_data = $arffield->getOne($id);


        $field_data->field_options = maybe_unserialize($field_data->field_options);


        $field = array();


        $field['type'] = $field_data->type;


        $field['id'] = $id;

		$field['separate_value'] = isset($_POST['sep_val']) ? $_POST['sep_val'] : 0;		


        $field_name = "item_meta[$id]";


        


        require(VIEWS_PATH.'/addoptionjs.php');

		$field_val_new = $field_val;

		$opt_new = $opt;

		$opt_key_new = $opt_key;

        require(VIEWS_PATH.'/optionsingle.php');
		
		if(is_array($opt_new)) {
			$opt_new = $opt_new['label'];
			$field_val_new = $field_val_new['value'];
		}
				
		if( isset($_POST['is_checkbox_radio']) and $_POST['is_checkbox_radio'] == 1 )
		{
			if( isset($_POST['checkboxradio_len']) and $_POST['checkboxradio_len'] < 5 )
			{ 
				?>^|^<input type="<?php echo $field['type'] ?>" <?php echo ($field['type'] == 'checkbox')?'class="class_checkbox checkbox_radio_class"':'class="class_radio checkbox_radio_class"'; ?> id="fieldcheck_sub_<?php echo $field['id']?>-<?php echo $opt_key_new; ?>" name="<?php echo $field_name ?>_sub_<?php echo ($field['type'] == 'checkbox')?'[]':''; ?>" disabled="disabled" value="<?php echo esc_attr($field_val_new) ?>"<?php echo isset($checked)? $checked : ''; ?>/><label class="arf_checkbox_radio_label" id="arflbl_<?php echo $field['id']?>-<?php echo $opt_key_new; ?>" for="fieldcheck_sub_<?php echo $field['id']?>-<?php echo $opt_key_new ?>"><?php echo $opt_new; ?></label><?php
			}
			else
				echo '^|^';
		} 
		else if( isset($_POST['is_checkbox_radio']) and $_POST['is_checkbox_radio'] == 0 )
		{
			echo '^|^'.$opt_new;
		}
		die();


    }
	
	function input_fieldhtml($field, $echo=true){


        global $arfsettings, $armainhelper;


        


        $class = ''; 


        $add_html = '';


		if($field['type'] == 'date' || $field['type'] == 'phone' )
			
			$field['size'] = '';        


        if(isset($field['size']) and $field['size'] > 0){


            if(!in_array($field['type'], array('textarea', 'select', 'data', 'time')))


                $add_html .= ' size="'. $field['size'] .'"';


            $class .= " auto_width";


        }


        


        if(isset($field['max']) and !empty($field['max']) and $field['type']!= "textarea")


            $add_html .= ' maxlength="'. $field['max'] .'"';


        


        if(!is_admin() or !isset($_GET) or !isset($_GET['page']) or $_GET['page'] == 'ARForms_entries'){


            $action = isset($_REQUEST['arfaction']) ? 'arfaction' : 'action';


            $action = $armainhelper->get_param($action);


            


            if(isset($field['required']) and $field['required']){



                if($field['type'] == 'file' and $action == 'edit'){



                }else
				{
					if($field['type'] == 'select' )
					{
	                    $class .= "select_controll_".$field['id']." arf_required ";	
					}
					elseif($field['type'] == 'time' )
					{
	                    $class .= "time_controll_".$field['id']." arf_required";	
					}
					else
					{
	                    $class .= " arf_required";
					}										

				}
            }



            if(isset($field['clear_on_focus']) and $field['clear_on_focus'] and !empty($field['default_value'])){


				  $val = esc_attr($field['default_value']); 
	
                $add_html .= ' onfocus="arfcleardedaultvalueonfocus('."'". $val ."'". ',this,'."'".$field['default_blank']."'".')" onblur="arfreplacededaultvalueonfocus('."'". $val ."'". ',this,'."'".$field['default_blank']."'".')" placeholder="'.$val.'"';


                


                if($field['value'] == $field['default_value'])


                    $class .= ' arfdefault';


            }


        }


        


        if(isset($field['input_class']) and !empty($field['input_class']))


            $class .= ' '. $field['input_class'];


        


        $class = apply_filters('arfaddfieldclasses', $class, $field);


        if(!empty($class))


            $add_html .= ' class="'. $class .'"';


            


        if(isset($field['shortcodes']) and !empty($field['shortcodes'])){


            foreach($field['shortcodes'] as $k => $v){


                $add_html .= ' '. $k .'="'. $v .'"';


                unset($k);


                unset($v);


            }


        }


        


        if($echo)


            echo $add_html;


        


        return $add_html;


    }
	
	function ajax_time_options(){


	    global $style_settings, $MdlDb, $wpdb, $armainhelper, $arfrecordmeta;


	    extract($_POST);


	    
	    $time_key = str_replace('field_', '', $time_field);


	    $date_key = str_replace('field_', '', $date_field);


	    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($date)))


	        $date = $armainhelper->convert_date($date, $style_settings->date_format, 'Y-m-d');


	    $date_entries = $arfrecordmeta->getEntryIds("fi.field_key='$date_key' and entry_value='$date'");





	    $opts = array('' => '');


        $time = strtotime($start);


        $end = strtotime($end);


        $step = explode(':', $step);


        $step = (isset($step[1])) ? ($step[0] * 3600 + $step[1] * 60) : ($step[0] * 60);


        $format = ($clock) ? 'H:i' : 'h:i A';


        


        while($time <= $end){


            $opts[date($format, $time)] = date($format, $time);


            $time += $step;


        }


        


	    if($date_entries and !empty($date_entries)){


	        $used_times = $wpdb->get_col( $wpdb->prepare("SELECT entry_value FROM $MdlDb->entry_metas it LEFT JOIN $MdlDb->fields fi ON (it.field_id = fi.id) WHERE fi.field_key= %s and it.entry_id in (". implode(',', $date_entries).")", $time_key) );


	        


	        if($used_times and !empty($used_times)){


	            $number_allowed = apply_filters('arfallowedtimecount', 1, $time_key, $date_key);


	            $count = array();


	            foreach($used_times as $used){


	                if(!isset($opts[$used]))


	                    continue;


	                    


	                if(!isset($count[$used]))


	                    $count[$used] = 0;


	                $count[$used]++;


	                


	                if((int)$count[$used] >= $number_allowed)


	                    unset($opts[$used]);


	            }


	            unset($count);


	        }


	    }


	    


	    echo json_encode($opts);


	    die();


	}
	
	function destroy(){


        global $arffield;


        $field_id = $arffield->destroy($_POST['field_id']);


        die();


    }  
	
	function edit_option(){


        global $arffield;


        $ids = explode('-', $_POST['element_id']);


        $id = str_replace('field_', '', $ids[0]);


        if(strpos($_POST['element_id'], 'key_')){


            $id = str_replace('key_', '', $id);


            $new_value = $_POST['update_value'];


        }else{


            $new_label = $_POST['update_value'];


        }


        $field = $arffield->getOne($id);


        $options = maybe_unserialize($field->options);


        $this_opt = (array)$options[$ids[1]];


        


        $label = isset($this_opt['label']) ? $this_opt['label'] : reset($this_opt);


        if(isset($this_opt['value']))


            $value =  $this_opt['value'];


            


        if(!isset($new_label))


            $new_label = $label;


            


        if(isset($new_value) or isset($value))


            $update_value = isset($new_value) ? $new_value : $value;


        


        if(isset($update_value) and $update_value != $new_label)


            $options[$ids[1]] = array('value' => $update_value, 'label' => $new_label);


        else


            $options[$ids[1]] = $_POST['update_value'];


   		global $wpdb;
		$wpdb->update($wpdb->prefix.'arf_fields', array('options' => maybe_serialize($options) ), array('id'=>$id), array('%s'), array('%d') );
        echo stripslashes($_POST['update_value']);


        die();


    }
	
	function delete_option(){


        global $arffield;


        $field = $arffield->getOne($_POST['field_id']);


        $options = maybe_unserialize($field->options);


        unset($options[$_POST['opt_key']]);


		global $wpdb;
		$wpdb->update($wpdb->prefix.'arf_fields', array('options' => maybe_serialize($options) ), array('id'=>$_POST['field_id']), array('%s'), array('%d') );
        die();


    }
	
	function arfpresetoptions(){


        if(!is_admin() or !current_user_can('arfeditforms'))


            return;


        


        global $arffield, $arfajaxurl, $arfieldhelper;


        


        extract($_POST);


        


        $field = $arffield->getOne($field_id);


        


        if(!in_array($field->type, array('radio', 'checkbox', 'select')))


            return;


        


        $field = $arfieldhelper->setup_edit_variables($field);


        $opts = stripslashes($opts);    


        $opts = explode("\n", rtrim($opts, "\n"));


        if($field['separate_value']){


            foreach($opts as $opt_key => $opt){


                if(strpos($opt, '|') !== false){


                    $vals = explode('|', $opt);


                    if($vals[0] != $vals[1])


                        $opts[$opt_key] = array('label' => $vals[0], 'value' => $vals[1]);


                    unset($vals);


                }


                unset($opt_key);


                unset($opt);


            }


        }


        
		if($field['type'] == 'select') {		
			$opt1 = array('0'=>'');
			$opts = array_merge($opt1, $opts);
		} 		

		global $wpdb;
		$wpdb->update($wpdb->prefix.'arf_fields', array('options' => maybe_serialize($opts) ), array('id'=>$field_id), array('%s'), array('%d') );	
        


        $field['options'] = stripslashes_deep($opts);


        $field_name = $field['name'];


 		$is_preset_field_choices = true;


        if ($field['type'] == 'radio' or $field['type'] == 'checkbox'){

			$field_name = "item_meta[".$field['id']."]";
            require(VIEWS_PATH.'/radiobutton.php');


        }else{


            foreach ($field['options'] as $opt_key => $opt){ 


                $field_val = apply_filters('arffieldvaluesaved', $opt, $opt_key, $field);


                $opt = apply_filters('arffieldlabelseen', $opt, $opt_key, $field);


                require(VIEWS_PATH.'/optionsingle.php');


            }


        }


		if( $is_checkbox_radio && $is_checkbox_radio == 1 )
		{
			echo '^|^';
			if (is_array($field['options'])){
				$i = 0;
				foreach($field['options'] as $opt_key => $opt){
			
					$field_val = apply_filters('arfdisplaysavedfieldvalue', $opt, $opt_key, $field);
			
					$opt = apply_filters('show_field_label', $opt, $opt_key, $field);
					
					if(is_array($opt)) {
						$opt = $opt['label'];
						$field_val = ($field['separate_value']) ? $field_val['value'] : $opt;
					}
												
					$checked = (isset($field['value']) and ((!is_array($field['value']) && $field['value'] == $field_val ) || (is_array($field['value']) && in_array($field_val, $field['value'])))) ? ' checked="true"':'';
							
					?><div class="arf_check_radio_fields">
						<input type="<?php echo $field['type'] ?>" <?php echo ($field['type'] == 'checkbox')?'class="class_checkbox checkbox_radio_class"':'class="class_radio checkbox_radio_class"'; ?> id="fieldcheck_sub_<?php echo $field['id']?>-<?php echo $opt_key ?>" name="<?php echo $field_name ?>_sub_<?php echo ($field['type'] == 'checkbox')?'[]':''; ?>" disabled="disabled" value="<?php echo esc_attr($field_val) ?>"<?php echo isset($checked)? $checked : ''; ?>/><label class="arf_checkbox_radio_label" id="arflbl_<?php echo $field['id']?>-<?php echo $opt_key ?>" for="fieldcheck_<?php echo $field['id']?>-<?php echo $opt_key ?>"><?php echo $opt; ?></label>
					</div><?php
					
					unset($checked);
					$i++;
					if( $i == 5 )
						break;
				}  
			
			}
			
		}
	
        die();


    }
	
	function update_order(){


        if(isset($_POST) and isset($_POST['arfmainfieldid'])){


            global $arffield, $wpdb;


            


            foreach ($_POST['arfmainfieldid'] as $position => $item)
				$wpdb->update($wpdb->prefix.'arf_fields', array('field_order' => $position), array('id'=>$item) );

        }


        die();


    }
	
	function arfupdateoptionorder()
	{
		global $wpdb;	
		
		$field_id = $_POST['field_id'];
		
		$fid	  = $_POST['fid'];	
		
		$order = maybe_serialize( $_POST[ 'arfoptionorder_'.$field_id ] );
		
		$wpdb->update($wpdb->prefix.'arf_fields', array( 'option_order' => $order ), array( 'id' => $field_id ), array('%s'), array('%d') );
		
		die();
	}
	
	function arf_prevalidateform_outside()
	{
		
		$form_id 	= $_POST['form_id'];
		
		$arf_errors = array();
		
		$arf_form_data = array();
		
		$values		= $_POST;
		
		$arf_form_data = apply_filters('arf_populate_field_from_outside', $arf_form_data, $form_id, $values);	// for populate data in form
		
		$arf_errors = apply_filters('arf_validate_form_outside_errors', $arf_errors, $form_id, $values, $arf_form_data);	// for form validate filter
		
		if( $arf_errors['arf_form_data'] )
		{	
			$arf_form_data = array_merge($arf_form_data, $arf_errors['arf_form_data']);
		}	
		
		unset($arf_errors['arf_form_data']);
			
		if( count($arf_form_data) > 0 )
		{
			echo '^arf_populate=';
			foreach( $arf_form_data as $field_id => $field_value )
			{
				echo $field_id.'^|^'.$field_value.'~|~'; 	
			}
			echo '^arf_populate=';
		}
				
		if( count($arf_errors) > 0 )
		{
			foreach( $arf_errors as $field_id => $error )
			{
				echo $field_id.'^|^'.$error.'~|~'; 	
			}
		}
		else
		{
			echo 0;
		}
			
	die();	
	}
	
	function arf_resetformoutside()
	{
		global $arfform, $arfieldhelper;
		 
		$form_id 	= $_POST['form_id'];
		
		$arf_form_data = array();
		
		if( $form_id >= 10000 )
			$form = $arfform->getRefOne( (int) $form_id );
		else
			$form = $arfform->getOne( (int) $form_id );
			
		$fields = $arfieldhelper->get_form_fields_tmp(false, $form->id, false, 0);
		
		$values = $arrecordhelper->setup_new_vars($fields, $form);
		
		$arf_form_data = apply_filters('arf_populate_field_after_from_submit', $arf_form_data, $form_id, $values, $form);	// for populate data in form
		
		if( count($arf_form_data) > 0 )
		{
			$arferr = array();
			foreach( $arf_form_data as $field_id => $field_value )
			{
				$arferr[$fieldid]	=  $fieldvalue;
			}
			$return["conf_method"] = "validationerror";
			$return["message"] = $arferr;
			
			echo json_encode($return);
			exit;
		}
			
	die();	
	}
			
}
?>