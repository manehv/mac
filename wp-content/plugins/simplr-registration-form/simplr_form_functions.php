<?php

function simplr_validate($data,$atts) {

	global $wp_version;
	
	//incluce necessary files, not required for WP3.1+
	if( version_compare( $wp_version, "3.1", "<") ) {
		require_once(ABSPATH . WPINC . '/registration.php' );
		require_once(ABSPATH . WPINC . '/pluggable.php' );
	}
	
	//empty errors array
	$errors = array();
	
	//setup options and custom fields
	$options = get_option('simplr_reg_options');
	$custom = new SREG_Fields(); 
	
	//recaptcha check/
	if( isset($options->recap_on) AND $options->recap_on == 'yes') {
		$spam = recaptcha_check($data);
		if($spam != false) {
			$errors[] = $spam;
		}
	}
	
	// Make sure passwords match
	if('yes' == @$atts['password']) 
	{
		if($data['password'] != $data['password_confirm']) 
		{
			$errors[] = __('The passwords you entered do not match.', 'simplr-reg');
			add_filter('password_error_class','_sreg_return_error');
		} 
		elseif(empty($data['password']))
		{
			$errors[] = __('You must enter a password to register for this site.', 'simplr-reg');
			add_filter('password_error_class','_sreg_return_error');
		}
	}
	
	// Validate username
	if(!$data['username']) { 
	$errors[] = __("You must enter a username.",'simplr-reg'); 
	add_filter('username_error_class','_sreg_return_error');
	} else {
		// check whether username is valid
		$user_test = validate_username($data['username']);
			if($user_test != true) {
					$errors[] .= __('Invalid Username.','simplr-reg');
					add_filter('username_error_class','_sreg_return_error');
				}
		// check whether username already exists
		$user_id = username_exists( $data['username'] );
			if($user_id) {
					$errors[] .= __('This username already exists.','simplr-reg');
					add_filter('username_error_class','_sreg_return_error');
				}
	} //end username validation
		
		
	// Validate email
	if(!$data['email']) { 
		$errors[] = __("You must enter an email.",'simplr-reg'); 
		add_filter('email_error_class','_sreg_return_error');
	} elseif($data['email'] !== $data['email_confirm']) {
		$errors[] = __("The emails you entered do not match.",'simplr-reg'); 
	} else {
		$email_test = email_exists($data['email']);
		if($email_test != false) {
				$errors[] .= __('An account with this email has already been registered.','simplr-reg');
				add_filter('email_error_class','_sreg_return_error');
			}
		if( !is_email($data['email']) ) {
				$errors[] .= __("Please enter a valid email.",'simplr-reg');
				add_filter('email_error_class','_sreg_return_error');
			}	
	} // end email validation
	
	if($atts['fields']) 
	{
		$fields = explode(',',$atts['fields']);
		foreach($fields as $cf) 
		{
			$field = @$custom->fields->custom[$cf];
			if($field['required'] == 'yes') 
			{
				if($field['type'] == 'date') 
				{ 
					if($data[$field['key'].'-mo'] == '' || $data[$field['key'].'-dy'] == '' || $data[$field['key'].'-yr'] == '') 
					{
					$errors[] = $field['label'] .' is a required field. Please enter a value.';
					add_filter($field['key'].'_error_class','_sreg_return_error');
					}
				} elseif(!isset($data[$field['key']]) || $data[$field['key']] == '' ) {
					$errors[] = $field['label'] .' is a required field. Please enter a value.';
					add_filter($field['key'].'_error_class','_sreg_return_error');
				} 
			}
		}
	}
	
	//use this filter to apply custom validation rules.
	$errors = apply_filters('simplr_validate_form', $errors); 
	return $errors;
}

function sreg_process_form($atts) {
	//security check
	global $sreg;
	if( !$sreg ) $sreg = new stdClass;
	if (!wp_verify_nonce($_POST['simplr_nonce'], 'simplr_nonce') ) { die('Security check'); } 
	
	$sreg->errors = simplr_validate($_POST,$atts);
	
	if( !empty($sreg->errors) ) :
		 $sreg->message = $sreg->errors;
	endif; 
	
	if(!@$sreg->message) {			
		$sreg->output = simplr_setup_user($atts,$_POST);
	} 
//END FUNCTION
}


function simplr_setup_user($atts,$data) {
	//check options
	global $simplr_options;
	$custom = new SREG_Fields();
	$admin_email = @$atts['from'];
	$emessage = @$atts['message'];
	$role = @$atts['role']; 
		if('' == $role) { $role = 'subscriber'; }
		if('administrator' == $role) { wp_die('Do not use this form to register administrators'); }
	require_once(ABSPATH . WPINC . '/registration.php' );
	require_once(ABSPATH . WPINC . '/pluggable.php' );
	
	//Assign POST variables
	$user_name = @$data['username'];
	$fname = @$data['fname'];
	$lname = @$data['lname'];
	$user_name = sanitize_user($user_name, true);
	$email = @$data['email'];
	$user_url = @$data['url'];
	
	
	//This part actually generates the account
	if(isset($data['password'])) {
		$passw = $data['password'];
	} else {
		$passw = wp_generate_password( 12, false );
	}
	
	$userdata = array(
		'user_login' 	=> $user_name,
		'first_name' 	=> $fname,
		'last_name' 	=> $lname,
		'user_pass' 	=> $passw,
		'user_email' 	=> $email,
		'user_url' 	=> $user_url,
		'role' 		=> $role,
	);
	// create user	
	$user_id = wp_insert_user( $userdata );
	
	//multisite support add user to registration log and associate with current site
	if(defined('WP_ALLOW_MULTISITE') OR is_multisite())	{ 
		global $wpdb;
		$ip = getenv('REMOTE_ADDR');
		$site = get_current_site();
		$sid = $site->id;
		$query = $wpdb->prepare("
			INSERT INTO $wpdb->registration_log
			(ID, email, IP, blog_ID, date_registered)
			VALUES (%d, %s, %s, %d, NOW() )
			",$user_id,$email,$ip,$sid);
		$results = $wpdb->query($query);
	}

	//set users status if this is a moderated user 
	if( $simplr_options->mod_on == 'yes' ) {	
		$user_status = 2;
		$user_activation_key = $data['activation_key'] = md5( @constant('AUTH_SALT') . $user_email . rand() );
		global $wpdb;
		$wpdb->update($wpdb->users, array(
			'user_status'	=> $user_status,
			'user_activation_key'	=> $user_activation_key,
			), array('ID'=>$user_id), array('%d','%s'), array('%d') );
	}

	//Process additional fields (Deprecated) 
	$pro_fields = get_option('simplr_profile_fields');
	if($pro_fields) 
	{
		foreach($pro_fields as $field) {
			$key = $field['name'];
			$val = $data[$key];
			if(isset( $val )) { add_user_meta($user_id,$key,$val); }
		}
	}
	
	//save custom fields
	foreach($custom->custom_fields as $field):
		if($field['type'] == 'date')
		{
			$dy = $data[$field['key'].'-dy'];
			$mo = $data[$field['key'].'-mo'];
			$yr = $data[$field['key'].'-yr'];
			$dateinput = implode('-', array($yr,$mo,$dy));
			update_user_meta($user_id,$field['key'],$dateinput);		
		} else {
			if(isset($data[$field['key']])) {
				update_user_meta($user_id, $field['key'], $data[$field['key']]);
			}
		}
	endforeach;
	
	if(isset($data['fbuser_id'])) {
		update_user_meta($user_id, 'fbuser_id', $data['fbuser_id']);
	}

	//Save Hook for custom profile fields
	do_action('simplr_profile_save_meta', $user_id,$data);
	
	// if password was auto generated, add flag for the user to change their auto-generated password
	if(!@$data['password']) {
		$update = update_user_option($user_id, 'default_password_nag', true, true);
	}
	
	//notify admin of new user
	$userdata = get_userdata( $user_id );
	$data = array_merge( (array) $userdata->data , $data );
	simplr_send_notifications($atts,$data,$passw);

	$extra = "Please check your email for confirmation.";
	$extra = apply_filters('simplr_extra_message', __($extra,'simplr-reg') );
	$confirm = '<div class="simplr-message success">Your Registration was successful. '.$extra .'</div>';
	
	//Use this hook for multistage registrations
	do_action('simplr_reg_next_action', array($data, $user_id, $confirm));
	
	//return confirmation message. 
	return apply_filters('simplr_reg_confirmation', $confirm);
}

function simplr_send_notifications($atts, $data, $passw) {
	global $simplr_options;
	$site = get_option('siteurl');
	$name = get_option('blogname');
	$user_name = @$data['username'];
	$email = @$data['email'];
	$notify = @$atts['notify'];
	$emessage = @$atts['message'];
	$headers = "From: $name" . ' <' .get_option('admin_email') .'> ' ."\r\n\\";
	wp_mail($notify, "A new user registered for $name", "A new user has registered for $name.\rUsername: $user_name\r Email: $email \r",$headers);
	$emessage = $emessage . "\r\r---\r";
		if(!isset($data['password'])) {
			$emessage .= "You should login and change your password as soon as possible.\r\r";
		}
	$emessage .= "Username: $user_name\r";
	$emessage .= (isset($data['fbuser_id']))?'Registered with Facebook':"Password: $passw\rLogin: $site";
	if( @$simplr_options->mod_on == 'yes' AND @$simplr_options->mod_activation == 'auto')  {
		$data['blogname'] = get_site_option('blogname');
		$data['link'] = get_home_url( $blog_id, '/?activation_key='.$data['activation_key'] );
		$content = simplr_token_replace( $simplr_options->mod_email, $data );
		$subject = simplr_token_replace( $simplr_options->mod_email_subj, $data );
		$headers = "From: ".get_option('admin_email')." \n";
		wp_mail( $data['user_email'], $subject, $content, $headers);
	} else {
		$emessage = simplr_token_replace( $emessage, $data );
		wp_mail($data['email'],"$name - Registration Confirmation", apply_filters('simplr_email_confirmation_message',$emessage), $headers);
	}
	
}

function simplr_token_replace( $content, $data ) {
	global $blog_id;
	foreach( $data as $k => $v ) {
		if( is_array($v) OR is_object($v) ) simplr_token_replace( $content, (array) $v );
		$content = str_replace( "%%{$k}%%" , $v, $content ); 
	}
	return $content;
}

function simplr_build_form($data,$atts) {
	include_once(SIMPLR_DIR.'/lib/form.class.php');
	if(get_option('users_can_register') != '1') { print('Registrations have been disabled'); 
	} else {
	// retrieve fields and options
	$custom = new SREG_Fields();
	$soptions = get_option('simplr_reg_options');

	$fb_user = sreg_fb_connect();
	if( isset($fb_user) && is_array(@$fb_user))  {
		$fb_button = '<span="fb-window">Connected via Facebook as <fb:name useyou="false" uid="'.$fb_user['id'].'" /></span>';
		$data['username'] = $fb_user['username'];
	} elseif( isset($fb_user) && is_string($fb_user)) {
		$fb_button = $fb_user;
		$fb_user = null;
	}
	
	$label_email = apply_filters('simplr_label_email', __('Email Address:','simplr-reg') );
	$label_confirm_email = apply_filters('simplr_label_confirm_email', __('Confirm Email:','simplr-reg') );
	$label_username = apply_filters('simplr_label_username', __('Your Username:','simplr-reg') );
	$label_pass = apply_filters('simplr_label_password', __('Choose a Password','simpr-reg'));
	$label_confirm = apply_filters('simplr_label_confirm', __('Confirm Password','simpr-reg'));
	
	//POST FORM
	$form = '';
	$form .= apply_filters('simplr-reg-instructions', __('', 'simplr-reg'));
	$form .=  '<div id="simplr-form">';
	if(isset($fb_button)) {
		$form .= '<div class="fb-button">'.$fb_button.'</div>';
	}
	
	$fields = explode(',',@$atts['fields']);
	$form .=  '<form method="post" action="" id="simplr-reg">';
	$form .= apply_filters('simplr-reg-first-form-elem','');

	//if the user has not added their own user name field lets force one
	if( !in_array('username',$fields) OR empty($custom->fields->custom['username']) ) {
		$form .=  '<div class="simplr-field '.apply_filters('username_error_class','') .'">';
		$form .=  '<label for="username" class="left">' .@esc_attr($label_username ).' <span class="required">*</span></label>';
		$form .=  '<input type="text" name="username" class="right" value="'.@esc_attr($data['username']) .'" /><br/>';
		$form .=  '</div><div class="simplr-clr"></div>';
	}

	foreach(@$fields as $field):
		if ( preg_match("#^\{(.*)\}#",$field, $matches) ) {
			$form .= "<h3 class='registration'>".$matches[1]."</h3>";
		}
		$cf = @$custom->fields->custom[$field];
		$out = '';
		if($cf['key'] != '') {
			if($fb_user != null) {
				$key_val = (array_key_exists($cf['key'],$fb_user)) ? $fb_user[$cf['key']] : $data[$cf['key']];
			}
			$args = array(
				'name'		=>$cf['key'],
				'label'		=>$cf['label'],
				'required'	=> $cf['required']
			);

			ob_start();
			//setup specific field values for date and callback
			if(isset($data[$cf['key']])) {
				if($cf['type'] == 'date') {
					$key_val = implode('-',array($data[$cf['key'].'-yr'],$data[$cf['key'].'-mo'],$data[$cf['key'].'-dy']));
				} elseif($cf['key'] != 'user_login' AND $cf['key'] != 'user_password' AND $cf['key'] != 'user_email') { 
					$key_val = $data[$cf['key']];
				}		
			}
			
			if($cf['type'] == 'callback') {
				$cf['options_array'][1] = array( @$data[$cf['key']] );
			}
	
			// do field
			if($cf['type'] != '') {
				SREG_Form::$cf['type']($args, @esc_attr($key_val), '', $cf['options_array']);
			}
			
			$form .= ob_get_contents();
			ob_end_clean();
		}
	endforeach;
	
	$form = apply_filters('simplr-add-personal-fields', $form);
	
	//only insert the email fields if the user hasn't specified them. 
	if( !in_array('email',$fields) ) {	
		$form .=  '<div class="simplr-field email-field '.apply_filters('email_error_class','').'">';
		$form .=  '<label for="email" class="left">' .$label_email .' <span class="required">*</span></label>';
		$form .=  '<input type="text" name="email" class="right" value="'.esc_attr(@$data['email']) .'" /><br/>';
		$form .=  '</div><div class="simplr-clr"></div>';
	} 

	if( !in_array('email_confirm', $fields) ) {
		$form .=  '<div class="simplr-field email-field '.apply_filters('email_error_class','').'">';
		$form .=  '<label for="email" class="left">' .$label_confirm_email .' <span class="required">*</span></label>';
		$form .=  '<input type="text" name="email_confirm" class="right" value="'.esc_attr(@$data['email_confirm']) .'" /><br/>';
		$form .=  '</div><div class="simplr-clr"></div>';
	}
	
	$form = apply_filters('simplr-add-contact-fields', $form);
	
	
	if('yes' == @$atts['password']) 
	{
		$form .=  '<div class="simplr-field '.apply_filters('password_error_class','').'">';
		$form .=  '<label for="password" class="left">' .$label_pass .'</label>';
		$form .=  '<input type="password" name="password" class="right" value="'.esc_attr(@$data['password']) .'"/><br/>';
		$form .=  '</div>';
		
		$form .=  '<div class="simplr-field '.apply_filters('password_error_class','').'">';
		$form .=  '<label for="password-confirm" class="left">' .$label_confirm .'</label>';
		$form .=  '<input type="password" name="password_confirm" class="right" value="'.esc_attr(@$data['password_confirm']) .'"/><br/>';
		$form .=  '</div>';
	}

	//filter for adding profile fields
	$form = apply_filters('simplr_add_form_fields', $form);
	if( isset( $soptions->recap_on ) AND $soptions->recap_on == 'yes') {
		$form .= sreg_recaptcha_field();
	}
	
	//add attributes to form
	if(!empty($atts)) {
		foreach($atts as $k=>$v)
		{
			$form .= '<input type="hidden" name="atts['.$k.']" value="'.$v.'" />';
		}
	}
	 
	//submission button. Use filter to custommize
	$form .=  apply_filters('simplr-reg-submit', '<input type="submit" name="submit-reg" value="Register" class="submit button">');
	
	//wordress nonce for security
	$nonce = wp_create_nonce('simplr_nonce');
	$form .= '<input type="hidden" name="simplr_nonce" value="' .$nonce .'" />';
	
	if(!empty($fb_user)) {
		$form .= '<input type="hidden" name="fbuser_id" value="'.$fb_user['id'].'" />';
	}
	
	$form .= '<div style="clear:both;"></div>';
	$form .=  '</form>';
	$form .=  '</div>';
	if( isset($options->fb_connect_on) AND $soptions->fb_connect_on == 'yes') {
		$form .= sreg_load_fb_script(); 
	}
	return $form;
	}
}

function sreg_basic($atts) {
	require_once dirname(__FILE__).'/lib/sreg.class.php';
	//Check if the user is logged in, if so he doesn't need the registration page
	if ( is_user_logged_in() AND !current_user_can('administrator') ) {
		global $user_ID;
		$first_visit = get_user_meta($user_ID, 'first_visit',true);
		if(empty($first_visit)) {
			$message = !empty($atts['message'])?$atts['message']:"Thank you for registering.";
			update_user_meta($user_ID,'first_visit',date('Y-m-d'));
			echo '<div id="message" class="success"><p>'.$message.'</p></div>';
		} else {
			echo "You are already registered for this site!!!";
		}
	} else {
		//Then check to see whether a form has been submitted, if so, I deal with it.
		global $sreg;
		if( !is_object($sreg) ) $sreg = new Sreg_Submit();
		$out = '';
		if(isset($sreg->success)) {
			return $sreg->output;
		} elseif( isset($sreg->errors) AND is_array($sreg->errors)) {
			foreach($sreg->errors as $mes) {
		        	$out .= '<div class="simplr-message error">'.$mes .'</div>';
	        	}
		} elseif(is_string($sreg->errors)) {
	        	$out = '<div class="simplr-message error">'.$message .'</div>';
		}
		return $out.simplr_build_form($_POST,$atts);

	} //Close LOGIN Conditional

} //END FUNCTION

add_action('wp','simplr_process_form');
function simplr_process_form() {
	if(isset($_POST['submit-reg'])) {
		global $sreg,$simplr_options;
		
		$atts = $_POST['atts'];
		sreg_process_form($atts);

		if(empty($sreg->errors))
		{
			if($simplr_options->fb_connect_on AND !empty($_POST['fbuser_id']) ) {
				simplr_fb_auto_login();
			} elseif(!empty($atts['thanks'])) {
				$page = get_permalink($atts['thanks']);			
				wp_redirect($page);
			} elseif(!empty($simplr_options->thank_you) ) {
				$page = get_permalink($simplr_options->thank_you);
				wp_redirect($page);
			} else {
				$sreg->success = $sreg->output;
			}
		} 
	}
}

//this function determines which version of the registration to call
function sreg_figure($atts) {
	global $options;
	extract(shortcode_atts(array(
	'role' => 'subscriber',
	'from' => get_option('sreg_admin_email'),
	'message' => 'Thank you for registering',
	'notify' => get_option('sreg_email'),
	'fields' => null,
	'fb' => false,
	), $atts));
		if($role != 'admin') {
			$function = sreg_basic($atts);
		} else { 
			$function = 'You should not register admin users via a public form';
		}
	return $function;
}//End Function

function sreg_recaptcha_field() {
 require_once(SIMPLR_DIR .'/lib/recaptchalib.php');
 $options = get_option('simplr_reg_options');
 $publickey = $options->recap_public; // you got this from the signup page
 return recaptcha_get_html($publickey);
}

function recaptcha_check($data) {	
	include_once(SIMPLR_DIR .'/lib/recaptchalib.php');
	$options = get_option('simplr_reg_options');
	$privatekey = $options->recap_public;
	$resp = recaptcha_check_answer($options->recap_private,$_SERVER["REMOTE_ADDR"],$data["recaptcha_challenge_field"],$data["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		return "The reCAPTCHA wasn't entered correctly. Go back and try it again." .
		     "(reCAPTCHA said: " . $resp->error . ")";
	} else {
		return false;
	}
}

function sreg_fb_connect() {
	$options = get_option('simplr_reg_options');
	if( !isset($options->fb_connect_on) OR $options->fb_connect_on != 'yes') {
		return null;
	} else {
	
		require_once(SIMPLR_DIR .'/lib/facebook.php');
		include_once(SIMPLR_DIR .'/lib/fb.class.php');	
		# Creating the facebook object
		
		$facebook = new Facebook(Simplr_Facebook::get_fb_info());
	
	   # Active session, let's try getting the user id (getUser()) and user info (api->('/me'))
    try {
      $uid = $facebook->getUser();
      $user = $facebook->api('/me');
    } catch (FacebookApiException $e) {}
	
	  if(!empty($user)) {
	    # User info ok? Let's print it (Here we will be adding the login and registering routines)
	    $out = $user;
	  } else {
	    # For testing purposes, if there was an error, let's kill the script
		  # There's no active session, let's generate one
			$login_url = $facebook->getLoginUrl();
		  $perms = implode(',',$options->fb_request_perms);
		  $out = 'Register using Facebook <fb:login-button scope="'.$perms.'"></fb:login-button>';
	   }

	   return $out;	   
	}
}

function sreg_load_fb_script() {
	require_once(SIMPLR_DIR .'/lib/facebook.php');
	include_once(SIMPLR_DIR .'/lib/fb.class.php');	
	$ap_info = Simplr_Facebook::get_fb_info();
	ob_start(); 
	?>
	<div id="fb-root"></div>
	<script>
	window.fbAsyncInit = function() {
	  FB.init({
	    appId  : '<?php echo $ap_info['appId']; ?>',
	    status : true, // check login status
	    cookie : <?php echo $ap_info['cookie']; ?>, // enable cookies to allow the server to access the session
	    xfbml  : true,  // parse XFBML
	    oauth : true //enables OAuth 2.0
	  });
	  FB.Event.subscribe('auth.login', function(response) {
        window.location.reload();
    });
    FB.Event.subscribe('auth.logout', function(response) {
        window.location.reload();
    });
	};
	
	(function() {
	  var e = document.createElement('script');
	  e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
	  e.async = true;
	  document.getElementById('fb-root').appendChild(e);
	}());
	</script>
<?php
	$out = ob_get_contents(); 
	ob_end_clean();
	return $out;
}

function _sreg_return_error($class) {
	return apply_filters('sreg_global_error_class','error');
}?>
