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

class arfieldmodel{
	
	function arfieldmodel() {
		add_filter('arfbeforefieldcreated',array(&$this, 'createfield'));


        add_filter('arfupdatefieldoptions', array(&$this, 'updatefield'), 10, 3);
	}
	  
	function createfield($field_data){


        global $style_settings;


        if ($field_data['field_options']['label'] != 'none')


            $field_data['field_options']['label'] = '';

		
		if( ! isset($field_data['field_options']['field_width']) )


            $field_data['field_options']['field_width'] = '';
        
	
		if ( empty($field_data['field_options']['label_width']) )


            $field_data['field_options']['label_width'] = $style_settings->width;
			
			
		if ( empty($field_data['field_options']['text_direction']) )


            $field_data['field_options']['text_direction'] = $style_settings->text_direction;
				


        switch($field_data['type']){


            case 'number':

				$field_data['name'] = __('Number', 'ARForms');
				
                $field_data['field_options']['maxnum'] = 0;


                break;


            case 'select':


                $field_data['field_options']['size'] = $style_settings->auto_width;


                break;


            case 'date':


                $field_data['field_options']['size'] = '10';

                $field_data['name'] = __('Date', 'ARForms');


                break;


            case 'time':


                $field_data['field_options']['size'] = '10';


                $field_data['name'] = __('Time', 'ARForms');


                break;


            case 'phone':


                $field_data['field_options']['size'] = '15';


                $field_data['name'] = __('Phone', 'ARForms');


                break;


            case 'website':


            case 'url':


                $field_data['name'] = __('Website', 'ARForms');


                break;


            case 'email':


                $field_data['name'] = __('Email', 'ARForms');


                break;


            case 'password':


                $field_data['name'] = __('Password', 'ARForms');


                break;


            case 'html':


                $field_data['name'] = __('HTML', 'ARForms');


                break;


            case 'divider':


                $field_data['name'] = __('Heading', 'ARForms');


                $field_data['field_options']['label'] = 'top';


                break;

			case 'imagecontrol':

                $field_data['name'] = __('Image', 'ARForms');
				
                break;
			
			case 'colorpicker':

                $field_data['name'] = __('Color', 'ARForms');

                break;
					
            case 'break':


                global $MdlDb;


                $page_num = $MdlDb->get_count($MdlDb->fields, array("form_id" => $field_data['form_id'], "type" => 'break'));


                $field_data['name'] = __('Page', 'ARForms') .' '. ($page_num + 2);


        }

		
		
        return apply_filters('arf_before_createfield',$field_data);


    }

    function updatefield($field_options, $field, $values){
		
		global $style_settings, $arfieldhelper;

        $defaults = $arfieldhelper->get_default_field_opts(false, $field);


        unset($defaults['dependent_fields']);


        unset($defaults['post_field']);


        unset($defaults['custom_field']);


        unset($defaults['taxonomy']);


        unset($defaults['exclude_cat']);


        


        $defaults['minnum'] = 1;


        $defaults['maxnum'] = 9999;
						
		
		$defaults['field_width'] = '';
		
		
		$defaults['label_width'] = $style_settings->width;
		
		
		$defaults['text_direction'] = $style_settings->text_direction;
		
		
        foreach ($defaults as $opt => $default)


            $field_options[$opt] = isset($values['field_options'][$opt.'_'.$field->id]) ? $values['field_options'][$opt.'_'.$field->id] : $default;



		if($field->type == 'scale'){


            global $arffield;


            $options = array();


            if((int)$field_options['maxnum'] >= 99)


                $field_options['maxnum'] = 5;


                


            for( $i=$field_options['minnum']; $i<=$field_options['maxnum']; $i++ )


                $options[] = $i;


            


            $arffield->update($field->id, array('options' => maybe_serialize($options)));


        }

        return $field_options;


    }
	
    function create( $values, $return=true, $template=false, $res_field_id='') { 


        global $wpdb, $MdlDb, $armainhelper;


        $new_values = array();


        $key = isset($values['field_key']) ? $values['field_key'] : $values['name'];


        $new_values['field_key'] = $armainhelper->get_unique_key($key, $MdlDb->fields, 'field_key');


        foreach (array('name', 'description', 'type', 'default_value') as $col)


            $new_values[$col] = @stripslashes($values[$col]);



        $new_values['options'] = $values['options'];


        $new_values['field_order'] = isset($values['field_order'])?(int)$values['field_order']:NULL;


        $new_values['required'] = isset($values['required'])?(int)$values['required']:NULL;


        $new_values['form_id'] = isset($values['form_id'])?(int)$values['form_id']:NULL;
		
		
		$new_values['ref_field_id'] = isset($values['ref_field_id'])?(int)$values['ref_field_id']:0; 

		$new_values['option_order'] = isset($values['option_order'])? maybe_serialize($values['option_order']):0; 
		
		if ( isset($values['field_options']['classes']) && $values['field_options']['classes'] == "" )
            $values['field_options']['classes'] = 'arf_1';
			
		if ( isset($values['field_options']['required_indicator']) && $values['field_options']['required_indicator'] == "" )		
            $values['field_options']['required_indicator'] = '*';			
			
        $new_values['field_options'] = is_array($values['field_options']) ? maybe_serialize($values['field_options']) : $values['field_options'];


		$new_values['created_date'] = current_time('mysql');
		//---------- for conditional logic ----------//
		$conditional_logic = array(
			'enable' => 0,
			'display' => 'show',
			'if_cond' => 'all',
			'rules'   => array(),
			);
			
		$new_values['conditional_logic'] = ( isset($values['conditional_logic']) ) ? $values['conditional_logic'] : maybe_serialize($conditional_logic);
		//---------- for conditional logic ----------//

        $query_results = $wpdb->insert( $MdlDb->fields, $new_values );
		
        if($return){


            if($query_results) {
				
				$return_insert_id = $wpdb->insert_id;
				
				if($template) {
					$temp_field_data = $wpdb->update($MdlDb->fields, array('ref_field_id'=>$return_insert_id), array('id'=>$return_insert_id));	
					if($res_field_id != '') $_SESSION['arf_fields'][$res_field_id] = $return_insert_id;
				}
				

                return $return_insert_id;


            } else


                return false;


        } else {
			if($query_results) {
				
				$return_insert_id = $wpdb->insert_id;
				
				if($template) {
					$temp_field_data = $wpdb->update($MdlDb->fields, array('ref_field_id'=>$return_insert_id), array('id'=>$return_insert_id));	
					if($res_field_id != '') $_SESSION['arf_fields'][$res_field_id] = $return_insert_id;
				}
			}	
		}


    }

    function duplicate($old_form_id, $form_id, $copy_keys=false, $blog_id=false, $template=false){
	

        global $MdlDb, $armainhelper;


        foreach ($this->getAll("fi.form_id = $old_form_id", '', '', $blog_id) as $field){


            $values = array();
			
            $new_key = ($copy_keys) ? $field->field_key : '';


            $values['field_key'] = $armainhelper->get_unique_key($new_key, $MdlDb->fields, 'field_key');


            $values['options'] = maybe_serialize($field->options);
			
			$conditional_logic = array(
				'enable' => 0,
				'display' => 'show',
				'if_cond' => 'all',
				'rules'   => array(),
			);
			
			$values['conditional_logic'] = ( isset($field->conditional_logic) ) ? $field->conditional_logic : maybe_serialize($conditional_logic);
			
            $values['form_id'] = $form_id;
			
			$res_field_id = ($field->ref_field_id > 0 ) ? $field->ref_field_id : $field->id; 
			
			if($template)
				$values['ref_field_id'] = '0'; 
			else
				$values['ref_field_id'] = $field->id;
				
            foreach (array('name', 'description', 'type', 'default_value', 'field_order', 'required', 'field_options', 'option_order') as $col)

			if($col == "default_value")
			{
				$values{$col} = maybe_serialize($field->$col);
			}
			else
			{
                $values[$col] = $field->{$col};
			}	
			
			//print_r($values);
			
            $this->create($values, false, $template, $res_field_id);


            unset($field);


        }


    }

    function update( $id, $values ){


        global $wpdb, $MdlDb, $arfieldhelper, $armainhelper;





        if (isset($values['field_key']))


            $values['field_key'] = $armainhelper->get_unique_key($values['field_key'], $MdlDb->fields, 'field_key', $id);


		if ( empty($values['field_options']['required_indicator']) )		
            $values['field_options']['required_indicator'] = '*';			


        if (isset($values['field_options']) and is_array($values['field_options']))


            $values['field_options'] = maybe_serialize($values['field_options']);
		
		
		//---------- for conditional logic ----------//
		if( isset($_REQUEST['conditional_logic_'.$id]) and stripslashes_deep($_REQUEST['conditional_logic_'.$id]) == '1' ) {
						
			$conditional_logic_display = @stripslashes_deep($_REQUEST['conditional_logic_display_'.$id]);
			
			$conditional_logic_if_cond = @stripslashes_deep($_REQUEST['conditional_logic_if_cond_'.$id]);
			
			$conditional_logic_rules = array();
			
			$rule_array = $_REQUEST['rule_array_'.$id] ? $_REQUEST['rule_array_'.$id] : array();
			if( count($rule_array) > 0 ) {
				$i = 1;
				foreach($rule_array as $v){
					
					$conditional_logic_field 		= @stripslashes_deep($_REQUEST['arf_cl_field_'.$id.'_'.$v]);
					$conditional_logic_field_type 	= @$arfieldhelper->get_field_type($conditional_logic_field);
					$conditional_logic_op 			= @stripslashes_deep($_REQUEST['arf_cl_op_'.$id.'_'.$v]);
					$conditional_logic_value 		= @stripslashes_deep($_REQUEST['cl_rule_value_'.$id.'_'.$v]);
					
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
								
			$values['conditional_logic'] = maybe_serialize($conditional_logic);				
		
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
								
			$values['conditional_logic'] = maybe_serialize($conditional_logic);			
		}
		//---------- for conditional logic ----------//
		
        $query_results = $wpdb->update( $MdlDb->fields, $values, array( 'id' => $id ) );


        unset($values);


        


        if($query_results)


            wp_cache_delete( $id, 'arf_field' );


        


        return $query_results;


    }

    function destroy( $id ){


      global $wpdb, $MdlDb;





      do_action('arfbeforedestroyfield', $id);


      do_action('arfbeforedestroyfield_'. $id);


      


      $wpdb->query($wpdb->prepare("DELETE FROM $MdlDb->entry_metas WHERE field_id=%d", $id));


      return $wpdb->query($wpdb->prepare("DELETE FROM $MdlDb->fields WHERE id=%d", $id));


    }

    function getOne( $id ){


        global $wpdb, $MdlDb;


        $results = wp_cache_get( $id, 'arf_field' );


        if(!$results){


          


            if (is_numeric($id))


                $where = array('id' => $id);


            else


                $where = array('field_key' => $id);





            $results = $MdlDb->get_one_record($MdlDb->fields, $where);


            


            if($results)


                wp_cache_set( $results->id, $results, 'arf_field' );


        }


        


        if($results){


            $results->field_options = maybe_unserialize($results->field_options);


            $results->options = maybe_unserialize($results->options);


            $results->default_value = maybe_unserialize($results->default_value);


			$results->option_order = maybe_unserialize($results->option_order);
        }


        


        return stripslashes_deep($results);


    }

    function getAll($where=array(), $order_by = '', $limit = '', $blog_id=false, $is_ref_form=0){


        global $wpdb, $MdlDb, $armainhelper;


        


        if ($blog_id and IS_WPMU){

           $prefix = $wpdb->get_blog_prefix( $blog_id );

           $table_name = "{$prefix}arf_fields"; 
		  
		   if($is_ref_form == 1 )
		 	 $form_table_name = "{$prefix}arf_ref_forms";
		   else
			 $form_table_name = "{$prefix}arf_forms";	


        }else{


            $table_name = $MdlDb->fields;

			if($is_ref_form == 1 )
		 	 $form_table_name = $MdlDb->ref_forms;
		   else
			 $form_table_name = $MdlDb->forms;


        }


        


        


        if(!empty($order_by) and !preg_match("/ORDER BY/", $order_by))


            $order_by = " ORDER BY {$order_by}";





        if(is_numeric($limit))


            $limit = " LIMIT {$limit}";


        


        $query = 'SELECT fi.*, ' .


                 'fr.name as form_name ' . 


                 'FROM '. $table_name . ' fi ' .


                 'LEFT OUTER JOIN ' . $form_table_name . ' fr ON fi.form_id=fr.id';


                  


        if(is_array($where)){       


            extract($MdlDb->get_where_clause_and_values( $where ));





            $query .= "{$where}{$order_by}{$limit}";


            $query = $wpdb->prepare($query, $values);


        }else{


            $query .= $armainhelper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;


        }


        


        if ($limit == ' LIMIT 1' or $limit == 1)


            $results = $wpdb->get_row($query);


        else


            $results = $wpdb->get_results($query);


        


        if($results){
			
			
            if(is_array($results)){


                foreach($results as $r_key => $result){


                    wp_cache_set($result->id, $result, 'arf_field');


                    wp_cache_set($result->field_key, $result, 'arf_field');


                    $results[$r_key]->field_options = maybe_unserialize($result->field_options);


                    $results[$r_key]->options = maybe_unserialize($result->options);


                    $results[$r_key]->default_value = maybe_unserialize($result->default_value);

					$results[$r_key]->option_order = maybe_unserialize($result->option_order);
                }


            }else{


                wp_cache_set($results->id, $results, 'arf_field');


                wp_cache_set($results->field_key, $results, 'arf_field');


                $results->field_options = maybe_unserialize($results->field_options);


                $results->options = maybe_unserialize($results->options);


                $results->default_value = maybe_unserialize($results->default_value);
				
				
				$results->option_order 	= maybe_unserialize($results->option_order);

            }


        }


        return stripslashes_deep($results);


    }
	
}