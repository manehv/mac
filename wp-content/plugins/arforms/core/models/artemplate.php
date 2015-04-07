<?php
		global $arffield, $arfform, $MdlDb, $wpdb, $arfieldhelper;
		
		$wp_upload_dir 	= wp_upload_dir();
		$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
		$main_css_dir 	= $wp_upload_dir['basedir'].'/arforms/maincss/';
		
		$values['name'] = 'Subscription Form';
		$values['description'] = 'Gather user information';
		$values['options']['custom_style'] = 1;
		$values['is_template'] = '1';
		$values['status'] = 'published';
		$values['form_key'] = 'Subscription';
		$values['options']['display_title_form'] = "1";
		
		$form_id = $arfform->create( $values );
		
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
		
		
		$values['name'] = 'Registration form';
		$values['description'] = 'Gather User information';
		$values['options']['custom_style'] = 1;
		$values['is_template'] = '1';
		$values['status'] = 'published';
		$values['form_key'] = 'Registration';
		
		$form_id = $arfform->create( $values );
		
		
		$cssoptions = get_option("arfa_options");
		
		$new_values = array();
		
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
		
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
		$field_values['name'] = 'First Name';
		$field_values['description'] = '';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = 'Last Name';
		$field_values['description'] = '';
		$field_values['required'] = 1;
		$field_values['field_options']['label'] = 'hidden';
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('email', $form_id));
		$field_values['name'] = 'Email';
		$field_values['required'] = 1;
		$field_values['field_options']['invalid'] = 'Please enter a valid email address';
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('url', $form_id));
		$field_values['name'] = 'Website';
		$field_values['field_options']['invalid'] = 'Please enter a valid website';
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = 'Address';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = 'Address Line 2';
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
		$field_values['name'] = 'State';
		$field_values['required'] = 1;
		$field_values['options'] = maybe_serialize(array('', 'AL', 'AK', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'));
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = 'Postal Code';
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('select', $form_id));
		$field_values['name'] = 'Country';
		$field_values['required'] = 1;
		$field_values['options'] = maybe_serialize(array('', 'Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Cook Islands', 'Costa Rica', 'Croatia (Hrvatska)', 'Cuba', 'Cyprus', 'Czech Republic', 'Congo (DRC)', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Islas Malvinas)', 'Faroe Islands', 'Fiji Islands', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern and Antarctic Lands', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hong Kong SAR', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macao SAR', 'Macedonia, Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia and Montenegro', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'));
		$field_values['field_options']['size'] = 1;
		$arffield->create( $field_values );
		unset($field_values);
		unset($values);
		
		
		
		$values['name'] = __('Contact Us', 'ARForms');
		$values['description'] = __('We would like to hear from you. Please send us a message by filling out the form below and we will get back with you shortly.', 'ARForms');
		$values['options']['custom_style'] = 1;
		$values['is_template'] = '1';
		$values['status'] = 'published';
		$values['form_key'] = 'ContactUs';
		$values['options']['display_title_form'] = "1";
		
		$form_id = $arfform->create( $values );
		
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
		//$new_values['arferrorstyle'] = "normal";
		
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
		
		
		
		
		
		$values['name'] = 'Survey Form';
		$values['description'] = 'Gather User information';
		$values['options']['custom_style'] = 1;
		$values['is_template'] = '1';
		$values['status'] = 'published';
		$values['form_key'] = 'Survey';
		$values['options']['display_title_form'] = "1";
		$values['options']['arf_form_title'] = "border-bottom:1px solid #4a494a;padding-bottom:5px;";
				
		$form_id = $arfform->create( $values );
		
		$cssoptions = get_option("arfa_options");
		
		$new_values = array();
		
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
		
		
		$new_values['fieldset'] = "0";
		$new_values['arfformtitlealign'] = "center";
		$new_values['check_weight_form_title'] = "bold";
		$new_values['form_title_font_size'] = "32";
		
		
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
		
		
		
		$values['name'] = 'Feedback Form';
		$values['description'] = 'Gather User information';
		$values['options']['custom_style'] = 1;
		$values['is_template'] = '1';
		$values['status'] = 'published';
		$values['form_key'] = 'Feedback';
		
		$form_id = $arfform->create( $values );
		
		
		$cssoptions = get_option("arfa_options");
		
		$new_values = array();
		
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
		
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
		$field_values['name'] = 'First Name';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = 'Last Name';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('email', $form_id));
		$field_values['name'] = 'E-mail Address';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = 'Company Name';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('url', $form_id));
		$field_values['name'] = 'Website';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('text', $form_id));
		$field_values['name'] = 'Subject';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('radio', $form_id));
		$field_values['name'] = 'How did you find us?';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$field_values['options'] = maybe_serialize(array('Search Engine','Link From Another Site','News Article','Televistion Ad','Word of Mouth'));
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('radio', $form_id));
		$field_values['name'] = 'How often do you visit our site?';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$field_values['options'] = maybe_serialize(array('Daily','Weekly','Monthly','Yearly'));
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('select', $form_id));
		$field_values['name'] = 'Please rate the quality of our content. (10=Best 1=Worst)';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$field_values['options'] = maybe_serialize(array('10','9','8','7','6','5','4','3','2','1'));
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('select', $form_id));
		$field_values['name'] = 'Please rate the quality of our site design. (10=Best 1=Worst)';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$field_values['options'] = maybe_serialize(array('10','9','8','7','6','5','4','3','2','1'));
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('checkbox', $form_id));
		$field_values['name'] = 'Suitable word for ARForms';
		$field_values['required'] = 1;
		$field_values['field_options']['classes'] = '';
		$field_values['options'] = maybe_serialize(array('Good','Best','Difficult','Creative','Helpful','Unhelpful'));
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('textarea', $form_id));
		$field_values['name'] = 'What was your favorite part of the ARForms?';
		$field_values['required'] = 0;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('textarea', $form_id));
		$field_values['name'] = 'Did you experience any problems or have any suggestions?';
		$field_values['required'] = 0;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		
		$field_values = apply_filters('arfbeforefieldcreated', $arfieldhelper->setup_new_variables('textarea', $form_id));
		$field_values['name'] = 'Other Comment';
		$field_values['required'] = 0;
		$field_values['field_options']['classes'] = '';
		$arffield->create( $field_values );
		unset($field_values);
		unset($values);
		
		
		
		
		$values['name'] = 'RSVP Form';
		$values['description'] = 'Gather User information';
		$values['options']['custom_style'] = 1;
		$values['is_template'] = '1';
		$values['status'] = 'published';
		$values['form_key'] = 'RSVP';
		$values['options']['display_title_form'] = "1";
		$values['options']['arf_form_title'] = "background-color:rgb(147, 217, 226);padding: 10px;border-radius:5px;";
		
		$form_id = $arfform->create( $values );
		
		
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
		
		$values['name'] = __('Job Application Form','ARForms');
		$values['description'] = '';
		$values['options']['custom_style'] = 1;
		$values['is_template'] = '1';
		$values['status'] = 'published';
		$values['form_key'] = 'JobApplication';
		$values['options']['display_title_form'] = "1";
		$values['options']['arf_form_description'] = "margin:0px !important;";
				
		$form_id = $arfform->create( $values );
		
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
?>