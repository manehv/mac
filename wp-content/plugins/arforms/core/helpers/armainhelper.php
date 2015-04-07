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

class armainhelper{

    function get_pages(){
	  global $wpdb, $armainhelper;
     
	 $post = $wpdb->get_results('select * from '.$wpdb->prefix.'posts where post_type = "page" and (post_status = "publish" or post_status = "private") order by post_title asc limit 0,999');
	 
	  return $post;


    }

    function wp_pages_dropdown($field_name, $page_id, $truncate=false, $id=''){

		global $wpdb, $armainhelper;
		
        $pages = $armainhelper->get_pages();

		/*
    ?>


        <select name="<?php echo $field_name; ?>" id="<?php if($id!='') echo $id; else echo $field_name; ?>" class="frm-dropdown frm-pages-dropdown" style="width:250px;" data-width="250px" data-size="20">	


            <option value="">- <?php _e('Select a page','ARForms');?></option>


            <?php foreach($pages as $page){ ?>


                <option value="<?php echo $page->ID; ?>" <?php echo (((isset($_POST[$field_name]) and $_POST[$field_name] == $page->ID) or (!isset($_POST[$field_name]) and $page_id == $page->ID))?' selected="selected"':''); ?>><?php echo ($truncate)? $armainhelper->truncate($page->post_title, $truncate) : $page->post_title; ?> </option>


            <?php } ?>


        </select>


    <?php
		*/
		
		if($id!='') { $selec_id = $id; } else { $selec_id = $field_name; }
		
		$arf_cl_field_selected_option = array();
		$arf_cl_field_options = '';
		$cntr = 0;
		foreach($pages as $page) {
			
			$post_title_value = ($truncate)? $armainhelper->truncate($page->post_title, $truncate) : $page->post_title;
			
			if((isset($_POST[$field_name]) and $_POST[$field_name] == $page->ID) or (!isset($_POST[$field_name]) and $page_id == $page->ID) || $cntr==0)
			{
				$arf_cl_field_selected_option['page_id'] = $page->ID;
				$arf_cl_field_selected_option['name'] = $post_title_value;
			}
			
			$arf_cl_field_options .= '<li class="arf_selectbox_option" data-value="'. $page->ID .'" data-label="'. $post_title_value .'">'. $post_title_value .'</li>';		
			$cntr++;
		}
		
		echo '<input id="'.$select_id.'_arf_wp_pages" name="'.$field_name.'" value="'.$arf_cl_field_selected_option['page_id'].'" type="hidden" class="frm-dropdown frm-pages-dropdown">
			  <dl class="arf_selectbox" data-name="'.$field_name.'" data-id="'.$select_id.'_arf_wp_pages" style="width:240px;">
				<dt><span>'.$arf_cl_field_selected_option['name'].'</span>
				<input value="'.$arf_cl_field_selected_option['name'].'" style="display:none;width:118px;" class="arf_autocomplete" type="text">
				<i class="fa fa-caret-down fa-lg"></i></dt>
				<dd>
					<ul class="field_dropdown_menu" style="display: none;" data-id="'.$select_id.'_arf_wp_pages">
						'.$arf_cl_field_options.'
					</ul>
				</dd>
			  </dl>';
    }

    function esc_textarea( $text ) {


        $safe_text = str_replace('&quot;', '"', $text);


        $safe_text = htmlspecialchars( $safe_text, ENT_NOQUOTES );


    	return apply_filters( 'esc_textarea', $safe_text, $text );


    }

    function script_version($handle, $list='scripts'){


        global $wp_scripts;


    	if(!$wp_scripts)


    	    return false;


        


        $ver = 0;


        


        if ( isset($wp_scripts->registered[$handle]) )


            $query = $wp_scripts->registered[$handle];


            


    	if ( is_object( $query ) )


    	    $ver = $query->ver;





    	return $ver;


    }

    function get_unique_key($name='', $table_name, $column, $id = 0, $num_chars = 6){


        global $wpdb;





        $key = '';


        


        if (!empty($name)){


            if(function_exists('sanitize_key'))


                $key = sanitize_key($name);


            else


                $key = sanitize_title_with_dashes($name);


        }


        


        if(empty($key)){


            $max_slug_value = pow(36, $num_chars);


            $min_slug_value = 37; 


            $key = base_convert( rand($min_slug_value, $max_slug_value), 10, 36 );


        }





        if (is_numeric($key) or in_array($key, array('id', 'key', 'created-at', 'detaillink', 'editlink', 'siteurl', 'evenodd')))


            $key = $key .'a';


            


        $query = "SELECT $column FROM $table_name WHERE $column = %s AND ID != %d LIMIT 1";


        $key_check = $wpdb->get_var($wpdb->prepare($query, $key, $id));


        


        if ($key_check or is_numeric($key_check)){


            $suffix = 2;


			do {


				$alt_post_name = substr($key, 0, 200-(strlen($suffix)+1)). "$suffix";


				$key_check = $wpdb->get_var($wpdb->prepare($query, $alt_post_name, $id));


				$suffix++;


			} while ($key_check || is_numeric($key_check));


			$key = $alt_post_name;


        }


        return $key;


    }

    function setup_edit_vars($record, $table, $fields='', $default=false){


        if(!$record) return false;


        global $arfrecordmeta, $arfform, $arfsettings, $arfsidebar_width, $armainhelper, $arfieldhelper, $arformhelper, $arrecordhelper;


        $values = array();




		if(isset($record->id))
		{
        	$values['id'] = $record->id;
		}



		if(isset($record->name) && isset($record->description))
		{
	        foreach (array('name' => $record->name, 'description' => $record->description) as $var => $default_val)

    	          $values[$var] = $armainhelper->get_param($var, $default_val);

		}
		
        if(apply_filters('arfusewpautop', true))


            $values['description'] = @wpautop($values['description']);


        $values['fields'] = array();


        


        if ($fields){


            foreach($fields as $field){





                if ($default){


                    $entry_value = $field->default_value;


                }else{


                    if(isset($record->metas)){


                        $entry_value = isset($record->metas[$field->id]) ? $record->metas[$field->id] : false;


                    }else{


                        $entry_value = $arfrecordmeta->get_entry_meta_by_field($record->id, $field->id);


                    }


                }


                


                $field_type = isset($_POST['field_options']['type_'.$field->id]) ? $_POST['field_options']['type_'.$field->id] : $field->type;


                $new_value = (isset($_POST['item_meta'][$field->id])) ? stripslashes_deep(maybe_unserialize($_POST['item_meta'][$field->id])) : $entry_value;





                $field_array = array(


                    'id' => $field->id,


                    'value' => $new_value,


                    'default_value' => $field->default_value,


                    'name' => $field->name,


                    'description' => $field->description,


                    'type' => apply_filters('arffieldtype', $field_type, $field, $new_value),


                    'options' => $field->options,


                    'required' => $field->required,


                    'field_key' => $field->field_key,


                    'field_order' => $field->field_order,


                    'form_id' => $field->form_id,
					
					
					'ref_field_id' => $field->ref_field_id,
					
					
					'conditional_logic' => maybe_unserialize($field->conditional_logic),//---------- for conditional logic ----------//		

                );



                $opt_defaults = $arfieldhelper->get_default_field_options($field_array['type'], $field, true);


                foreach ($opt_defaults as $opt => $default_opt){


                    $field_array[$opt] = ($_POST and isset($_POST['field_options'][$opt.'_'.$field->id]) ) ? stripslashes_deep(maybe_unserialize($_POST['field_options'][$opt.'_'.$field->id])) : (isset($field->field_options[$opt]) ? $field->field_options[$opt] : $default_opt);


                    if($opt == 'blank' and $field_array[$opt] == ''){


                        $field_array[$opt] = $arfsettings->blank_msg;


                    }else if($opt == 'invalid' and $field_array[$opt] == ''){


                        if($field_type == 'captcha')


                            $field_array[$opt] = $arfsettings->re_msg;


                        else


                            $field_array[$opt] = $field_array['name'] . ' ' . __('is invalid', 'ARForms');


                    }


                }


                


                unset($opt_defaults);


                    


                if ($field_array['custom_html'] == '')


                    $field_array['custom_html'] = $arfieldhelper->get_basic_default_html($field_type);


                


                if ($field_array['size'] == '')


                    $field_array['size'] = $arfsidebar_width;


                


                $field_array = @apply_filters('arfsetupeditfieldsvars', $field_array, $field, $values['id']);


                


                foreach((array)$field->field_options as $k => $v){


                    if(!isset($field_array[$k]))


                        $field_array[$k] = $v;


                    unset($k);


                    unset($v);


                }


                


                $values['fields'][] = $field_array;


                


                unset($field);   


            }


        }


      


        if ($table == 'entries')


            $form = $arfform->getOne( $record->form_id );


        else if ($table == 'forms')

			if(isset($record->id))
			{
	            $form = $arfform->getOne( $record->id );
			}




        if (isset($form)){


            $form->options = maybe_unserialize($form->options);


            $values['form_name'] = (isset($record->form_id)) ? $form->name : '';


            if (is_array($form->options)){


                foreach ($form->options as $opt => $value){


                    if(in_array($opt, array('email_to', 'reply_to', 'reply_to_name')))


                        $values['notification'][0][$opt] = $armainhelper->get_param('notification[0]['. $opt .']', $value);


                    


                    $values[$opt] = $armainhelper->get_param($opt, $value);


                }


            }


        }


        


        $form_defaults = $arformhelper->get_default_opts();



        foreach ($form_defaults as $opt => $default){


            if (!isset($values[$opt]) or $values[$opt] == ''){


                if($opt == 'notification'){


                    $values[$opt] = ($_POST and isset($_POST[$opt])) ? $_POST[$opt] : $default;


                    


                    foreach($default as $o => $d){


                        if($o == 'email_to')


                            $d = ''; 


                        $values[$opt][0][$o] = ($_POST and isset($_POST[$opt][0][$o])) ? $_POST[$opt][0][$o] : $d;


                        unset($o);


                        unset($d);


                    }


                }else{


                    $values[$opt] = ($_POST and isset($_POST['options'][$opt])) ? $_POST['options'][$opt] : $default;


                }


            }


            unset($opt);


            unset($defaut);


        }


            


        if (!isset($values['custom_style']))


            $values['custom_style'] = ($_POST and isset($_POST['options']['custom_style'])) ? $_POST['options']['custom_style'] : ($arfsettings->load_style != 'none');





        if (!isset($values['before_html']))


            $values['before_html'] = (isset($_POST['options']['before_html']) ? $_POST['options']['before_html'] : $arformhelper->get_default_html('before'));





        if (!isset($values['after_html']))


            $values['after_html'] = (isset($_POST['options']['after_html']) ? $_POST['options']['after_html'] : $arformhelper->get_default_html('after'));





        if ($table == 'entries')


            $values = $arrecordhelper->setup_edit_vars( $values, $record );


        else if ($table == 'forms')


            $values = $arformhelper->setup_edit_vars( $values, $record );





        return $values;


    }

    function get_us_states(){


        return apply_filters('arfusstates', array(


            'AL' => 'Alabama', 'AK' => 'Alaska', 'AR' => 'Arkansas', 'AZ' => 'Arizona', 


            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 


            'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 


            'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 


            'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine','MD' => 'Maryland', 


            'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 


            'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 


            'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 


            'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 


            'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 


            'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 


            'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 


            'WI' => 'Wisconsin', 'WY' => 'Wyoming'


        ));


    }

    function get_countries(){


        return apply_filters('arfcountries', array(


            __('Afghanistan', 'ARForms'), __('Albania', 'ARForms'), __('Algeria', 'ARForms'), 


            __('American Samoa', 'ARForms'), __('Andorra', 'ARForms'), __('Angola', 'ARForms'),


            __('Anguilla', 'ARForms'), __('Antarctica', 'ARForms'), __('Antigua and Barbuda', 'ARForms'), 


            __('Argentina', 'ARForms'), __('Armenia', 'ARForms'), __('Aruba', 'ARForms'),


            __('Australia', 'ARForms'), __('Austria', 'ARForms'), __('Azerbaijan', 'ARForms'),


            __('Bahamas', 'ARForms'), __('Bahrain', 'ARForms'), __('Bangladesh', 'ARForms'), 


            __('Barbados', 'ARForms'), __('Belarus', 'ARForms'), __('Belgium', 'ARForms'),


            __('Belize', 'ARForms'), __('Benin', 'ARForms'), __('Bermuda', 'ARForms'), 


            __('Bhutan', 'ARForms'), __('Bolivia', 'ARForms'), __('Bosnia and Herzegovina', 'ARForms'),


            __('Botswana', 'ARForms'), __('Brazil', 'ARForms'), __('Brunei', 'ARForms'), 


            __('Bulgaria', 'ARForms'), __('Burkina Faso', 'ARForms'), __('Burundi', 'ARForms'),


            __('Cambodia', 'ARForms'), __('Cameroon', 'ARForms'), __('Canada', 'ARForms'), 


            __('Cape Verde', 'ARForms'), __('Cayman Islands', 'ARForms'), __('Central African Republic', 'ARForms'), 


            __('Chad', 'ARForms'), __('Chile', 'ARForms'), __('China', 'ARForms'),


            __('Colombia', 'ARForms'), __('Comoros', 'ARForms'), __('Congo', 'ARForms'),


            __('Costa Rica', 'ARForms'), /*__('C&ocirc;te d\'Ivoire', 'ARForms'),*/ __('Croatia', 'ARForms'),


            __('Cuba', 'ARForms'), __('Cyprus', 'ARForms'), __('Czech Republic', 'ARForms'), 


            __('Denmark', 'ARForms'), __('Djibouti', 'ARForms'), __('Dominica', 'ARForms'),


            __('Dominican Republic', 'ARForms'), __('East Timor', 'ARForms'), __('Ecuador', 'ARForms'), 


            __('Egypt', 'ARForms'), __('El Salvador', 'ARForms'), __('Equatorial Guinea', 'ARForms'),


            __('Eritrea', 'ARForms'), __('Estonia', 'ARForms'), __('Ethiopia', 'ARForms'), 


            __('Fiji', 'ARForms'), __('Finland', 'ARForms'), __('France', 'ARForms'), 


            __('French Guiana', 'ARForms'), __('French Polynesia', 'ARForms'), __('Gabon', 'ARForms'), 


            __('Gambia', 'ARForms'), __('Georgia', 'ARForms'), __('Germany', 'ARForms'),


            __('Ghana', 'ARForms'), __('Gibraltar', 'ARForms'), __('Greece', 'ARForms'), 


            __('Greenland', 'ARForms'), __('Grenada', 'ARForms'), __('Guam', 'ARForms'),


            __('Guatemala', 'ARForms'), __('Guinea', 'ARForms'), __('Guinea-Bissau', 'ARForms'), 


            __('Guyana', 'ARForms'), __('Haiti', 'ARForms'), __('Honduras', 'ARForms'), 


            __('Hong Kong', 'ARForms'), __('Hungary', 'ARForms'), __('Iceland', 'ARForms'), 


            __('India', 'ARForms'), __('Indonesia', 'ARForms'), __('Iran', 'ARForms'), 


            __('Iraq', 'ARForms'), __('Ireland', 'ARForms'), __('Israel', 'ARForms'), 


            __('Italy', 'ARForms'), __('Jamaica', 'ARForms'), __('Japan', 'ARForms'), 


            __('Jordan', 'ARForms'), __('Kazakhstan', 'ARForms'), __('Kenya', 'ARForms'), 


            __('Kiribati', 'ARForms'), __('North Korea', 'ARForms'), __('South Korea', 'ARForms'), 


            __('Kuwait', 'ARForms'), __('Kyrgyzstan', 'ARForms'), __('Laos', 'ARForms'), 


            __('Latvia', 'ARForms'), __('Lebanon', 'ARForms'), __('Lesotho', 'ARForms'), 


            __('Liberia', 'ARForms'), __('Libya', 'ARForms'), __('Liechtenstein', 'ARForms'), 


            __('Lithuania', 'ARForms'), __('Luxembourg', 'ARForms'), __('Macedonia', 'ARForms'), 


            __('Madagascar', 'ARForms'), __('Malawi', 'ARForms'), __('Malaysia', 'ARForms'), 


            __('Maldives', 'ARForms'), __('Mali', 'ARForms'), __('Malta', 'ARForms'), 


            __('Marshall Islands', 'ARForms'), __('Mauritania', 'ARForms'), __('Mauritius', 'ARForms'), 


            __('Mexico', 'ARForms'), __('Micronesia', 'ARForms'), __('Moldova', 'ARForms'), 


            __('Monaco', 'ARForms'), __('Mongolia', 'ARForms'), __('Montenegro', 'ARForms'), 


            __('Montserrat', 'ARForms'), __('Morocco', 'ARForms'), __('Mozambique', 'ARForms'), 


            __('Myanmar', 'ARForms'), __('Namibia', 'ARForms'), __('Nauru', 'ARForms'), 


            __('Nepal', 'ARForms'), __('Netherlands', 'ARForms'), __('New Zealand', 'ARForms'),


            __('Nicaragua', 'ARForms'), __('Niger', 'ARForms'), __('Nigeria', 'ARForms'), 


            __('Norway', 'ARForms'), __('Northern Mariana Islands', 'ARForms'), __('Oman', 'ARForms'), 


            __('Pakistan', 'ARForms'), __('Palau', 'ARForms'), __('Palestine', 'ARForms'), 


            __('Panama', 'ARForms'), __('Papua New Guinea', 'ARForms'), __('Paraguay', 'ARForms'), 


            __('Peru', 'ARForms'), __('Philippines', 'ARForms'), __('Poland', 'ARForms'), 


            __('Portugal', 'ARForms'), __('Puerto Rico', 'ARForms'), __('Qatar', 'ARForms'), 


            __('Romania', 'ARForms'), __('Russia', 'ARForms'), __('Rwanda', 'ARForms'), 


            __('Saint Kitts and Nevis', 'ARForms'), __('Saint Lucia', 'ARForms'), 


            __('Saint Vincent and the Grenadines', 'ARForms'), __('Samoa', 'ARForms'), 


            __('San Marino', 'ARForms'), __('Sao Tome and Principe', 'ARForms'), __('Saudi Arabia', 'ARForms'),


            __('Senegal', 'ARForms'), __('Serbia and Montenegro', 'ARForms'), __('Seychelles', 'ARForms'), 


            __('Sierra Leone', 'ARForms'), __('Singapore', 'ARForms'), __('Slovakia', 'ARForms'), 


            __('Slovenia', 'ARForms'), __('Solomon Islands', 'ARForms'), __('Somalia', 'ARForms'), 


            __('South Africa', 'ARForms'), __('Spain', 'ARForms'), __('Sri Lanka', 'ARForms'), 


            __('Sudan', 'ARForms'), __('Suriname', 'ARForms'), __('Swaziland', 'ARForms'), 


            __('Sweden', 'ARForms'), __('Switzerland', 'ARForms'), __('Syria', 'ARForms'), 


            __('Taiwan', 'ARForms'), __('Tajikistan', 'ARForms'), __('Tanzania', 'ARForms'), 


            __('Thailand', 'ARForms'), __('Togo', 'ARForms'), __('Tonga', 'ARForms'), 


            __('Trinidad and Tobago', 'ARForms'), __('Tunisia', 'ARForms'), __('Turkey', 'ARForms'), 


            __('Turkmenistan', 'ARForms'), __('Tuvalu', 'ARForms'), __('Uganda', 'ARForms'), 


            __('Ukraine', 'ARForms'), __('United Arab Emirates', 'ARForms'), __('United Kingdom', 'ARForms'),


            __('United States', 'ARForms'), __('Uruguay', 'ARForms'), __('Uzbekistan', 'ARForms'), 


            __('Vanuatu', 'ARForms'), __('Vatican City', 'ARForms'), __('Venezuela', 'ARForms'), 


            __('Vietnam', 'ARForms'), __('Virgin Islands, British', 'ARForms'), 


            __('Virgin Islands, U.S.', 'ARForms'), __('Yemen', 'ARForms'), __('Zambia', 'ARForms'), 


            __('Zimbabwe', 'ARForms')


        ));


    }
	
	function get_country_codes(){


        return apply_filters('arfcountrycodes', array(
			'+1'	=> 'North America',	
			'+269' 	=> 'Mayotte, Comoros Is.', 
			'+501' 	=> 'Belize', 
			'+690'	=> 'Tokelau',
			'+20'	=> 'Egypt', 	
			'+27'	=> 'South Africa', 
			'+502' 	=> 'Guatemala',
			'+691' 	=> 'F.S. Micronesia',
			'+212' 	=> 'Morocco',
			'+290' 	=> 'Saint Helena',
			'+503' 	=> 'El Salvador', 	
			'+692' 	=> 'Marshall Islands',
			'+213' 	=> 'Algeria', 	
			'+291' 	=> 'Eritrea',
			'+504' 	=> 'Honduras',
			'+7' 	=> 'Russia, Kazakhstan',
			'+216' 	=> 'Tunisia', 	
			'+297' 	=> 'Aruba',
			'+505' 	=> 'Nicaragua', 	
			'+800' 	=> 'Int\'l Freephone',
			'+218' 	=> 'Libya',
			'+298' 	=> 'Færoe Islands',
			'+506' 	=> 'Costa Rica', 	
			'+81' 	=> 'Japan',
			'+220' 	=> 'Gambia', 	
			'+299' 	=> 'Greenland', 	
			'+507' 	=> 'Panama', 	
			'+82' 	=> 'Korea (South)',
			'+221' 	=> 'Senegal', 	
			'+30' 	=> 'Greece', 	
			'+508' 	=> 'St Pierre & Miquélon',
			'+84'	=> 'Viet Nam',
			'+222'	=> 'Mauritania',
			'+31'	=> 'Netherlands', 	
			'+509' 	=> 'Haiti', 	
			'+850' 	=> 'DPR Korea (North)',
			'+223' 	=> 'Mali',
			'+32' 	=> 'Belgium', 	
			'+51' 	=> 'Peru', 	  	 
			'+224'	=> 'Guinea', 	
			'+33'	=> 'France', 	
			'+52' 	=> 'Mexico', 	
			'+852' 	=> 'Hong Kong',
			'+225' 	=> 'Ivory Coast', 	
			'+34' 	=> 'Spain',
			'+53' 	=> 'Cuba', 	
			'+853' 	=> 'Macau',
			'+226' 	=> 'Burkina Faso', 	
			'+350' 	=> 'Gibraltar', 	
			'+54'	=> 'Argentina', 	
			'+855' 	=> 'Cambodia',
			'+227' 	=> 'Niger', 	
			'+351' 	=> 'Portugal', 	
			'+55' 	=> 'Brazil', 	
			'+856' 	=> 'Laos',
			'+228' 	=> 'Togo', 	
			'+352'   => 'Luxembourg',
			'+56' 	=> 'Chile', 	
			'+86' 	=> '(People\'s Rep.) China',
			'+229' 	=> 'Benin', 	
			'+353' 	=> 'Ireland', 	
			'+57'	=> 'Colombia', 	
			'+870' 	=> 'Inmarsat SNAC',
			'+230' 	=> 'Mauritius', 	
			'+354' 	=> 'Iceland', 	
			'+58' 	=> 'Venezuela', 	
			'+871' 	=> 'Inmarsat (Atl-East)',
			'+231' 	=> 'Liberia', 	
			'+355' 	=> 'Albania', 	
			'+590' 	=> 'Guadeloupe', 	
			'+872'	=> 'Inmarsat (Pacific)',
			'+232' 	=> 'Sierra Leone', 	
			'+356' 	=> 'Malta', 	
			'+591' 	=> 'Bolivia', 	
			'+873' 	=> 'Inmarsat (Indian O.)',
			'+233' 	=> 'Ghana', 	
			'+357' 	=> 'Cyprus', 	
			'+592' 	=> 'Guyana', 	
			'+874' 	=> 'Inmarsat (Atl-West)',
			'+234' 	=> 'Nigeria', 	
			'+358' 	=> 'Finland', 	
			'+593' 	=> 'Ecuador', 	
			'+880' 	=> 'Bangladesh',
			'+235' 	=> 'Chad', 	
			'+359' 	=> 'Bulgaria', 	
			'+594' 	=> 'Guiana (French)', 	
			'+881' 	=> 'Satellite services',
			'+236' 	=> 'Central African Rep.', 	
			'+36' 	=> 'Hungary', 	
			'+595' 	=> 'Paraguay', 	
			'+886' 	=> 'Taiwan/"reserved"',
			'+237'	=> 'Cameroon', 	
			'+370'	=> 'Lithuania', 	
			'+596' 	=> 'Martinique', 	
			'+90'	=> 'Turkey',
			'+238' 	=> 'Cape Verde', 	
			'+371' 	=> 'Latvia', 	
			'+597' 	=> 'Suriname', 	
			'+91' 	=> 'India',
			'+239' 	=> 'São Tomé & Principé', 	
			'+372' 	=> 'Estonia', 	
			'+598' 	=> 'Uruguay', 	
			'+92' 	=> 'Pakistan',
			'+240' 	=> 'Equatorial Guinea',
			'+373' 	=> 'Moldova', 	
			'+599' 	=> 'Netherlands Antilles', 	
			'+93' 	=>	'Afghanistan',
			'+241' 	=> 'Gabon', 	
			'+374' 	=> 'Armenia', 	
			'+60' 	=> 'Malaysia', 	
			'+94' 	=> 'Sri Lanka',
			'+242' 	=> 'Congo', 	
			'+375' 	=> 'Belarus', 	
			'+61' 	=> 'Australia', 	
			'+95' 	=> 'Myanmar (Burma)',
			'+243' 	=> 'Zaire', 	
			'+376' 	=> 'Andorra', 	
			'+62' 	=> 'Indonesia', 	
			'+960' 	=> 'Maldives',
			'+244' 	=> 'Angola', 	
			'+377' 	=> 'Monaco', 	
			'+63' 	=> 'Philippines', 	
			'+961' 	=> 'Lebanon',
			'+245' 	=> 'Guinea-Bissau', 	
			'+378' 	=> 'San Marino', 	
			'+64' 	=> 'New Zealand', 	
			'+962' 	=> 'Jordan',
			'+246' 	=> 'Diego Garcia', 	
			'+379' 	=> 'Vatican City (use +39)', 	
			'+65' 	=> 'Singapore', 	
			'+963' 	=> 'Syria',
			'+247' 	=> 'Ascension', 	
			'+380' 	=> 'Ukraine', 	
			'+66' 	=> 'Thailand', 	
			'+964' 	=> 'Iraq',
			'+248' 	=> 'Seychelles', 	
			'+381' 	=> 'Yugoslavia', 	
			'+670' 	=> 'East Timor', 	
			'+965' 	=> 'Kuwait',
			'+249' 	=> 'Sudan', 	
			'+385' 	=> 'Croatia', 	  	  	
			'+966' 	=> 'Saudi Arabia',
			'+250' 	=> 'Rwanda', 	
			'+386' 	=> 'Slovenia', 	
			'+672' 	=> 'Australian Ext. Terr.', 	
			'+967' 	=> 'Yemen',
			'+251' 	=> 'Ethiopia', 	
			'+387' 	=> 'Bosnia - Herzegovina', 	
			'+673' 	=> 'Brunei Darussalam', 	
			'+968' 	=> 'Oman',
			'+252' 	=> 'Somalia', 	
			'+389' 	=> '(FYR) Macedonia', 	
			'+674' 	=> 'Nauru', 	
			'+970' 	=> 'Palestine',
			'+253' 	=> 'Djibouti', 	
			'+39' 	=> 'Italy', 	
			'+675'	=> 'Papua New Guinea', 	
			'+971'	=> 'United Arab Emirates',
			'+254' 	=> 'Kenya', 	
			'+40' 	=> 'Romania', 	
			'+676' 	=> 'Tonga', 	
			'+972' 	=> 'Israel',
			'+255' 	=> 'Tanzania', 	
			'+41' 	=> 'Switzerland, (Liecht.)', 	
			'+677' 	=> 'Solomon Islands', 	
			'+973' 	=> 'Bahrain',
			'+256' 	=> 'Uganda', 	  	  	
			'+678' 	=> 'Vanuatu', 	
			'+974' 	=> 'Qatar',
			'+257' 	=> 'Burundi', 	
			'+420' 	=> 'Czech Republic', 	
			'+679' 	=> 'Fiji', 	
			'+975' 	=> 'Bhutan',
			'+258' 	=> 'Mozambique', 	
			'+421' 	=> 'Slovakia', 	
			'+680'	=> 'Palau', 	
			'+976' 	=> 'Mongolia',
			'+260' 	=> 'Zambia', 	
			'+423' 	=> 'Liechtenstein', 	
			'+681' 	=> 'Wallis and Futuna', 	
			'+977' 	=> 'Nepal',
			'+261' 	=> 'Madagascar', 	
			'+43' 	=> 'Austria', 	
			'+682' 	=> 'Cook Islands', 	
			'+98' 	=> 'Iran',
			'+262' 	=> 'Reunion Island', 	
			'+44' 	=> 'United Kingdom', 	
			'+683' 	=> 'Niue', 	
			'+992' 	=> 'Tajikistan',
			'+263' 	=> 'Zimbabwe', 	
			'+45' 	=> 'Denmark', 	
			'+684' 	=> 'American Samoa', 	
			'+993' 	=> 'Turkmenistan',
			'+264' 	=> 'Namibia', 	
			'+46' 	=> 'Sweden', 	
			'+685' 	=> 'Western Samoa',
			'+994' 	=> 'Azerbaijan',
			'+265' 	=> 'Malawi', 	
			'+47' 	=> 'Norway', 	
			'+686' 	=> 'Kiribati', 	
			'+995' 	=> 'Rep. of Georgia',
			'+266' 	=> 'Lesotho', 	
			'+48' 	=> 'Poland', 	
			'+687' 	=> 'New Caledonia', 	
			'+996' 	=> 'Kyrgyz Republic',
			'+267' 	=> 'Botswana', 	
			'+49' 	=> 'Germany', 	
			'+688' 	=> 'Tuvalu', 	
			'+997' 	=> 'Kazakhstan',
			'+268' 	=> 'Swaziland',
			'+500' 	=> 'Falkland Islands', 	
			'+689' 	=> 'French Polynesia', 	
			'+998' 	=> 'Uzbekistan',
        ));


    }
	
    function user_has_permission($needed_role){        


        if($needed_role == '' or current_user_can($needed_role))


            return true;


            


        $roles = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' );


        foreach ($roles as $role){


        	if (current_user_can($role))


        		return true;


        	if ($role == $needed_role)


        		break;


        }


        return false;


    }

    function is_super_admin($user_id=false){


        if(function_exists('is_super_admin'))


            return is_super_admin($user_id);


        else


            return is_site_admin($user_id);


    }

    function checked($values, $current){
		
		global $armainhelper;


        if($armainhelper->check_selected($values, $current))


            echo ' checked="checked"';


    }

    function check_selected($values, $current){



        if(is_array($values))


            $values = array_map(array('armainhelper', 'recursive_trim'), $values);


        else


            $values = trim($values);


        $current = trim($current);



        if((is_array($values) && in_array($current, $values)) or (!is_array($values) and $values == $current))


            return true;


        else


            return false;


    }

    function recursive_trim(&$value) {


        if (is_array($value))


            $value = array_map(array('armainhelper', 'recursive_trim'), $value);


        else


            $value = trim($value);


            


        return $value;


    }

    function frm_get_main_message( $message = ''){
            return $message;
    }

    function truncate($str, $length, $minword = 3, $continue = '...'){


        $length = (int)$length;


        $str = strip_tags($str);


        $original_len = (function_exists('mb_strlen')) ? mb_strlen($str) : strlen($str);


        


        if($length == 0){


            return '';


        }else if($length <= 10){


            $sub = (function_exists('mb_substr')) ? mb_substr($str, 0, $length) : substr($str, 0, $length);


            return $sub . (($length < $original_len) ? $continue : '');


        }


            


        $sub = '';


        $len = 0;





        $words = (function_exists('mb_split')) ? mb_split(' ', $str) : explode(' ', $str);


            


        foreach ($words as $word){


            $part = (($sub != '') ? ' ' : '') . $word;


            $sub .= $part;


            $len += (function_exists('mb_strlen')) ? mb_strlen($part) : strlen($part);


            $total_len = (function_exists('mb_strlen')) ? mb_strlen($sub) : strlen($sub);


            


            if (str_word_count($sub) > $minword && $total_len >= $length)


                break;


            


            unset($total_len);


        }


        


        return $sub . (($len < $original_len) ? $continue : '');


    }

    function prepend_and_or_where( $starts_with = ' WHERE ', $where = '' ){


        if(is_array($where)){


            global $MdlDb, $wpdb;


            extract($MdlDb->get_where_clause_and_values( $where ));


            $where = $wpdb->prepare($where, $values);


        }else{


            $where = (( $where == '' ) ? '' : $starts_with . $where);


        }


        


        return $where;


    }

    function getLastRecordNum($r_count,$current_p,$p_size){


      return (($r_count < ($current_p * $p_size))?$r_count:($current_p * $p_size));


    }

    function getFirstRecordNum($r_count,$current_p,$p_size){


      if($current_p == 1)


        return 1;


      else


        return ($this->getLastRecordNum($r_count,($current_p - 1),$p_size) + 1);


    }

    function getRecordCount($where="", $table_name){


        global $wpdb, $armainhelper;


        $query = 'SELECT COUNT(*) FROM ' . $table_name . $armainhelper->prepend_and_or_where(' WHERE ', $where);


        return $wpdb->get_var($query);


    }

    function getPageCount($p_size, $where="", $table_name){


        if(is_numeric($where))


            return ceil((int)$where / (int)$p_size);


        else


            return ceil((int)$this->getRecordCount($where, $table_name) / (int)$p_size);


    }

    function getPage($current_p,$p_size, $where = "", $order_by = '', $table_name){


        global $wpdb, $armainhelper;


        $end_index = $current_p * $p_size;


        $start_index = $end_index - $p_size;


        $query = 'SELECT *  FROM ' . $table_name . $armainhelper->prepend_and_or_where(' WHERE', $where) . $order_by .' LIMIT ' . $start_index . ',' . $p_size;


        $results = $wpdb->get_results($query);


        return $results;


    }

    function get_referer_query($query) {


    	if (strpos($query, "google.")) {


    	    $pattern = '/^.*[\?&]q=(.*)$/';


    	} else if (strpos($query, "bing.com")) {


    		$pattern = '/^.*q=(.*)$/';


    	} else if (strpos($query, "yahoo.")) {


    		$pattern = '/^.*[\?&]p=(.*)$/';


    	} else if (strpos($query, "ask.")) {


    		$pattern = '/^.*[\?&]q=(.*)$/';


    	} else {


    		return false;


    	}


    	preg_match($pattern, $query, $matches);


    	$querystr = substr($matches[1], 0, strpos($matches[1], '&'));


    	return urldecode($querystr);


    }

    function get_referer_info(){
	
		global $armainhelper;


        $referrerinfo = '';


    	$keywords = array();


    	$i = 1;


    	if(isset($_SESSION) and isset($_SESSION['arfhttpreferer']) and $_SESSION['arfhttpreferer']){


        	foreach ($_SESSION['arfhttpreferer'] as $referer_info) {


        		$referrerinfo .= str_pad("Referer $i: ",20) . $referer_info. "\r\n";


        		$keywords_used = $armainhelper->get_referer_query($referer_info);


        		if ($keywords_used)


        			$keywords[] = $keywords_used;





        		$i++;


        	}


        	


        	$referrerinfo .= "\r\n";


	    }else{


	        $referrerinfo = @$_SERVER['HTTP_REFERER'];


	    }





    	$i = 1;


    	if(isset($_SESSION) and isset($_SESSION['arfhttppages']) and $_SESSION['arfhttppages']){


        	foreach ($_SESSION['arfhttppages'] as $page) {


        		$referrerinfo .= str_pad("Page visited $i: ",20) . $page. "\r\n";


        		$i++;


        	}


        	


        	$referrerinfo .= "\r\n";


	    }





    	$i = 1;


    	foreach ($keywords as $keyword) {


    		$referrerinfo .= str_pad("Keyword $i: ",20) . $keyword. "\r\n";


    		$i++;


    	}


    	$referrerinfo .= "\r\n";


    	


    	return $referrerinfo;


    }    

	function jquery_themes(){


        return array(
		
			'default_theme'      => 'Default',

            'ui-lightness'  => 'UI Lightness',

            'smoothness'    => 'Smoothness',

            'redmond'       => 'Redmond',

            'overcast'      => 'Overcast',

            'flick'         => 'Flick',

            'pepper-grinder'=> 'Pepper Grinder',

            'cupertino'     => 'Cupertino',

            'blitzer'       => 'Blitzer',

            'humanity'      => 'Humanity',

        );


    }
	
	 function jquery_css_url($arfcalthemecss){

		
        $uploads = wp_upload_dir();
		
		if(!$arfcalthemecss or $arfcalthemecss == '' or $arfcalthemecss == 'default_theme'){
		
			$css_file = ARFURL . '/css/calender/default_theme_jquery-ui.css';
		
		}
        elseif(!$arfcalthemecss or $arfcalthemecss == '' or $arfcalthemecss == 'ui-lightness'){

			$css_file = ARFURL . '/css/calender/ui-lightness_jquery-ui.css';

        }else if(preg_match('/^http.?:\/\/.*\..*$/', $arfcalthemecss)){
			
            $css_file = $arfcalthemecss;

        }else{
			
            $file_path = ARFURL . '/css/calender/'. $arfcalthemecss . '_jquery-ui.css';
			
			$css_file = $file_path;			
            

        }


        return $css_file;


    }
	
	function datepicker_version(){
	
		global $armainhelper;


        $jq = $armainhelper->script_version('jquery');


	    $new_ver = true;


	    if($jq){


	        $new_ver = ((float)$jq >= 1.5) ? true : false;


        }else{


            global $wp_version;


            $new_ver = ($wp_version >= 3.2) ? true : false;


        }

        return ($new_ver) ? '' : '.1.7.3';


    }
	
	function get_user_id_param($user_id){


        if($user_id and !empty($user_id) and !is_numeric($user_id)){


            if($user_id == 'current'){


                global $user_ID;


                $user_id = $user_ID;


            }else{


                if(function_exists('get_user_by'))


                    $user = get_user_by('login', $user_id);


                else


                    $user = get_userdatabylogin($user_id);


                if($user)


                    $user_id = $user->ID;


                unset($user);


            }


        }


        return $user_id;


    }
	
	function get_formatted_time($date, $date_format=false, $time_format=false){


        if(empty($date))


            return $date;


        if(!$date_format)


            $date_format = get_option('date_format');


        if (preg_match('/^\d{1-2}\/\d{1-2}\/\d{4}$/', $date)){ 


            global $style_settings, $armainhelper;


            $date = $armainhelper->convert_date($date, $style_settings->date_format, 'Y-m-d');


        }


        $do_time = (date('H:i:s', strtotime($date)) == '00:00:00') ? false : true;   


        $date = get_date_from_gmt($date);


        $formatted = date_i18n($date_format, strtotime($date));


        if($do_time){


            if(!$time_format)


                $time_format = get_option('time_format');


            $trimmed_format = trim($time_format);


            if($time_format and !empty($trimmed_format))


                $formatted .= ' '. __('at', 'ARForms') .' '. date_i18n($time_format, strtotime($date));


        }


        return $formatted;

    }
	
	function get_custom_taxonomy($post_type, $field){


        $taxonomies = get_object_taxonomies($post_type);


        if(!$taxonomies){


            return false;


        }else{


            $field = (array)$field;


            if(!isset($field['taxonomy'])){


                $field['field_options'] = maybe_unserialize($field['field_options']);


                $field['taxonomy'] = $field['field_options']['taxonomy'];


            }


            


            if(isset($field['taxonomy']) and in_array($field['taxonomy'], $taxonomies))


                return $field['taxonomy'];


            else if($post_type == 'post')


                return 'category';


            else


                return reset($taxonomies);


        }


    }
	
	function convert_date($date_str, $from_format, $to_format){


        $base_struc     = preg_split("/[\/|.| |-]/", $from_format);


        $date_str_parts = preg_split("/[\/|.| |-]/", $date_str );


        $date_elements = array();


        $p_keys = array_keys( $base_struc );


        foreach ( $p_keys as $p_key ){


            if ( !empty( $date_str_parts[$p_key] ))


                $date_elements[$base_struc[$p_key]] = $date_str_parts[$p_key];


            else


                return false;


        }


        if(is_numeric($date_elements['m']))


            $dummy_ts = mktime(0, 0, 0, $date_elements['m'], (isset($date_elements['j']) ? $date_elements['j'] : $date_elements['d']), $date_elements['Y'] );


        else


            $dummy_ts = strtotime($date_str);


        return date( $to_format, $dummy_ts );


    }
	
	function get_shortcodes($content, $form_id){


        global $arffield;


        $fields = $arffield->getAll("fi.type not in ('divider','captcha','break','html') and fi.form_id=".$form_id);


        


        $tagregexp = 'editlink|siteurl|sitename|id|key|attachment_id|ip_address|created-at';


        foreach ($fields as $field)


            $tagregexp .= '|'. $field->id . '|'. $field->field_key;


        preg_match_all("/\[(if )?($tagregexp)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);


        return $matches;


    }
	
	function human_time_diff($from, $to=''){


    	if ( empty($to) )


    		$to = time();


    	$chunks = array(


    		array( 60 * 60 * 24 * 365 , __( 'year', 'ARForms' ), __( 'years', 'ARForms' ) ),


    		array( 60 * 60 * 24 * 30 , __( 'month', 'ARForms' ), __( 'months', 'ARForms' ) ),


    		array( 60 * 60 * 24 * 7, __( 'week', 'ARForms' ), __( 'weeks', 'ARForms' ) ),


    		array( 60 * 60 * 24 , __( 'day', 'ARForms' ), __( 'days', 'ARForms' ) ),


    		array( 60 * 60 , __( 'hour', 'ARForms' ), __( 'hours', 'ARForms' ) ),


    		array( 60 , __( 'minute', 'ARForms' ), __( 'minutes', 'ARForms' ) ),


    		array( 1, __( 'second', 'ARForms' ), __( 'seconds', 'ARForms' ) )


    	);


    	$diff = (int) ($to - $from);


    	if ( 0 > $diff )


    		return '';


    	for ( $i = 0, $j = count($chunks); $i < $j; $i++) {


    		$seconds = $chunks[$i][0];


    		if ( ( $count = floor($diff / $seconds) ) != 0 )


    			break;


    	}


    	$output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];


    	if ( !(int)trim($output) )


    		$output = '0 ' . __( 'seconds', 'ARForms' );


    	return $output;


    }
	
	function upload_file($field_id,$fomr_id = null){
		
	
        require_once(ABSPATH . 'wp-admin/includes/file.php');


        require_once(ABSPATH . 'wp-admin/includes/image.php');

		require_once(ABSPATH . 'wp-admin/includes/media.php');
		
        require_once(plugin_dir_path( __FILE__ ).'arupload_media.php');


        add_filter('upload_dir', array('armainhelper', 'upload_dir'));
		

        $media_id = media_handle_upload_custom($field_id, 0,$fomr_id);
		

        remove_filter('upload_dir', array('armainhelper', 'upload_dir'));


        


        return $media_id;


    }
	
	function upload_dir($uploads){

        $relative_path = apply_filters('arfuploadfolder', 'arforms/userfiles');


        $relative_path = untrailingslashit($relative_path);


        


        if(!empty($relative_path)){


            $uploads['path'] = $uploads['basedir'] .'/'. $relative_path;


            $uploads['url'] = $uploads['baseurl'] .'/'. $relative_path;


            $uploads['subdir'] = '/'. $relative_path;


        }





        return $uploads;


    }
	
	function get_param($param, $default='', $src='get'){
		
		

        if(strpos($param, '[')){


            $params = explode('[', $param);


            $param = $params[0];    


        }
						
		//@$str = str_replace('[AND]','&',stripslashes_deep(@$_POST['nforms']) );
		//@$str = str_replace('[PLUS]','+',stripslashes_deep(@$_POST['nforms']) );
		@$str = stripslashes_deep(@$_POST['form']);
		@$str = json_decode( @$str, true );
			
		

        if($src == 'get'){
			
				
            $value = ( isset($_POST[$param]) 
						? 
						stripslashes_deep($_POST[$param]) 
							:
							(isset( $str[$param] )
								?
								stripslashes_deep( $str[$param] )
								:
								(isset($_GET[$param]) 
									? 
									stripslashes_deep($_GET[$param]) 
										:
										$default)));
			
			
            if( ( !isset($_POST[$param]) or !isset($str[$param])) and isset($_GET[$param]) and !is_array($value))


                $value = urldecode($value);


        }else{
		
            $value = isset($_POST[$param]) ? stripslashes_deep(maybe_unserialize($_POST[$param])) : isset($str[$param]) ? stripslashes_deep( maybe_unserialize( $str[$param] ) ) : $default;


        }


        


        if(isset($params) and is_array($value) and !empty($value)){


            foreach($params as $k => $p){


                if(!$k or !is_array($value))


                    continue;


                    


                $p = trim($p, ']');


                $value = (isset($value[$p])) ? $value[$p] : $default;


            }


        }

        return $value;


    }

	function frm_capabilities(){

        $cap = array(


            'arfviewforms' => __('View Forms and Templates', 'ARForms'),


            'arfeditforms' => __('Add/Edit Forms and Templates', 'ARForms'),


            'arfdeleteforms' => __('Delete Forms and Templates', 'ARForms'),


            'arfchangesettings' => __('Access this Settings Page', 'ARForms'),
			
			
			'arfimportexport' => __('Access this Settings Page', 'ARForms'),


        );



		$cap['arfviewentries'] = __('View Entries from Admin Area', 'ARForms');


		$cap['arfcreateentries'] = __('Add Entries from Admin Area', 'ARForms');


		$cap['arfeditentries'] = __('Edit Entries from Admin Area', 'ARForms');


		$cap['arfdeleteentries'] = __('Delete Entries from Admin Area', 'ARForms');


		$cap['arfviewreports'] = __('View Reports', 'ARForms');


		$cap['arfeditdisplays'] = __('Add/Edit Custom Displays', 'ARForms');


        return $cap;


    }

    function get_post_param($param, $default=''){


        return isset($_POST[$param]) ? stripslashes_deep(maybe_unserialize($_POST[$param])) : $default;


    }

    function load_scripts($scripts){


        global $wp_version;


        if(version_compare( $wp_version, '3.3', '<')){


            global $wp_scripts;


            $wp_scripts->do_items( (array)$scripts );


        }else{


            foreach((array)$scripts as $s)


                wp_enqueue_script($s);


        }


    }

    function load_styles($styles){


        global $wp_version;


        if(version_compare( $wp_version, '3.3', '<')){


            global $wp_styles;


            $wp_styles->do_items( (array)$styles );


        }else{


            foreach((array)$styles as $s)


                wp_enqueue_style($s);


        }


    }

}?>