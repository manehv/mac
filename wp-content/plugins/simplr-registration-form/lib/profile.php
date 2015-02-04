<?php
/*
**
** Profile page shortcode handler
**
*/

function simplr_profile_page() {
	global $current_user,$errors;
	if(!class_exists('Form')) {
		include_once(dirname(__FILE__).'/form.class.php'); 
	}
	
	if(!is_user_logged_in()) {
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		$output = '';
		ob_start();
		wp_login_form();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
		
	} else {
	
		$error_header = '';
		if(is_array($errors)) {
			foreach($errors as $error) {
				$error_header .= '<div id="simplr-message" class="error">' .@$error  . "</div>\n";
			}		
		} elseif(@$_REQUEST['updated'] == 'true') {
				$error_header = '<div id="simplr-message" class="success">'.apply_filters('simplr_profile_updated_message', __('Your profile was saved','simplr-reg')).'</div>';
		}
	
		$fields = get_transient('simplr_profile_transient');
		delete_transient('simplr_profile_transient');
		
		ob_start();
		
		echo '<h3>User Login: '.$current_user->user_login.' / Registered: '.date(get_option('date_format'),strtotime($current_user->user_registered)).'</h3>';
		
		echo '<form id="simplr-form" method="post" action="" >';
		
		SREG_Form::text(array(
			'name'=>'user_nicename',
			'label'=>'Nickname',
			'required'=>false),$current_user->user_nicename,'wide');
		
		SREG_Form::text(array(
			'name'=>'user_email',
			'label'=>'Email',
			'required'=>true),$current_user->user_email,'wide');
		if(!empty($fields)) 
		{	
			foreach($fields as $field) 
			{
			
				$key_val = get_user_meta($current_user->ID,$field['key'],true);	
			
				$args = array(
						'name'		=>$field['key'],
						'label'		=>$field['label'],
						'required'	=>$field['required']
				);
				
				//setup specific field values for date and callback
				if($field['type'] == 'callback') {
					$field['options_array'][1] = array($key_val);
				}
	
				if($field['type'] != '') {
					SREG_Form::$field['type']($args, @$key_val, '', $field['options_array']);
				}
			
			} //endforeach
		}
		
		SREG_Form::hidden( array('name'=>'user_id') ,$current_user->ID,'','');
				if(!get_user_meta($current_user->ID,'fbuser_id',true)) {
					simplr_change_password();
				}
				wp_nonce_field('simplr-profile','_simplr_nonce');
				SREG_Form::submit(array('name'=>'simplr-profile'));
		echo '</form>';
		$output = ob_get_contents(); 
		ob_end_clean();
		return $error_header.$output;
	}
}

/*
**
** Get only fields for the profile page.
**
*/

function simplr_filter_profile_fields($fields) {
	if(!class_exists('Form')) {
		include_once(dirname(__FILE__).'/form.class.php'); 
	}
	$return_array = array();
	foreach($fields as $field) {
		if(@$field['show_in_profile'] != 'no') {
			$return_array[$field['key']] = $field;
		} 
	}
	return apply_filters('simplr_filter_profile_fields',$return_array);
}

/*
**
** Change Password
**
*/

function simplr_change_password() {
	global $current_user;
	$show_password_fields = apply_filters('show_password_fields', true, $current_user);
	if ( $show_password_fields ) :
	?>
	<h3><?php _e('Change Password','simplr-reg'); ?></h3>
	<div class="simplr-passwords">
		
		<div class="option-field password">
		<label for="pass1"><?php _e('New Password'); ?></label>
		<input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" /> 
		<p class="description"><?php _e("If you would like to change the password type a new one. Otherwise leave this blank."); ?></p>
		</div>
		
		<div class="option-field password_confirm">
				<label for="pass1"><?php _e('Confirm Password'); ?></label>
		<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" />
		</div>
		
	</div>
		
	<div class="simplr-clr"></div>
<?php
	endif;
}

/*
**
**
**
*/
add_action('simplr_profile_actions','simplr_profile_init',100);
function simplr_profile_init() {
	global $simplr_options,$errors;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('user-profile');
	$custom = new SREG_Fields();
	$fields = simplr_filter_profile_fields($custom->get_custom()); 
	set_transient('simplr_profile_transient',$fields);
	
	if(isset($_POST['simplr-profile'])) {
		if(!wp_verify_nonce($_POST['_simplr_nonce'],'simplr-profile')) {
			wp_die('No hackers please! Your security nonce check failed. Please try again or contact the systems admin.');
		} else {
			$errors = simplr_validate_profile($_POST,$fields);
			$data = $_POST;
			
			if(!is_array($errors)) {
				//update user
				$userdata = array('ID'=>$data['user_id'],'user_nicename'=>@$data['user_nicename'],'user_email'=>@$data['user_email']);
				
				if( isset($data['pass1']) AND $data['pass1'] != '' AND $data['pass2'] == $data['pass1']) 
					$userdata['user_pass'] = $data['pass1'];
				
				wp_update_user($userdata); 	
					
				//update user meta fields
			
				foreach($fields as $field):
				if($field['type'] == 'date') {
					$dy = $data[$field['key'].'-dy'];
					$mo = $data[$field['key'].'-mo'];
					$yr = $data[$field['key'].'-yr'];
					$dateinput = implode('-', array($yr,$mo,$dy));
					update_user_meta($data['user_id'],$field['key'],$dateinput);		
				} elseif($field['type'] == 'checkbox') {	
					if(isset($data[$field['key']])) {
						update_user_meta($data['user_id'],$field['key'],'on');
					} else {
						delete_user_meta($data['user_id'],$field['key']);
					}
				} else { 
					update_user_meta($data['user_id'], $field['key'], $data[$field['key']]);
				}
				endforeach;
				
				do_action('simplr_profile_save');
				wp_redirect('?p='.$simplr_options->profile_redirect.'&updated=true');
			}
			
		}
	}
	
}

function simplr_validate_profile($data,$fields) {
	global $current_user;
	$errors = array();
	// Validate email
	if(!$data['user_email']) { 
		$errors[] = __("You must enter an email.",'simplr-reg'); 
		add_filter('email_error_class','_sreg_return_error');
	} else {
		if($data['user_email'] != $current_user->user_email) {
			$email_test = email_exists($data['user_email']);
			if($email_test != false) {
					$errors[] .= __('An account with this email has already been registered.','simplr-reg');
					add_filter('email_error_class','_sreg_return_error');
				}
			if( !is_email($data['user_email']) ) {
					$errors[] .= __("Please enter a valid email.",'simplr-reg');
					add_filter('email_error_class','_sreg_return_error');
			}	
		}
	} // end email validation
	
	if($fields) 
	{	
		$custom = new SREG_Fields();
		foreach($fields as $cf) 
		{
			$field = $cf;
			if($field['required'] == 'yes' and $field['key'] != '') 
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
	
	if(isset($data['pass1']) || isset($data['pass2'])) {
		if($data['pass1'] !== $data['pass2']) {
			$errors[] = 'The passwords you entered do not match';
			add_filter('password_error_class','_sreg_return_error');
		}
	}
	
	//use this filter to apply custom validation rules.
	if(!empty($errors)) {
		$errors = apply_filters('simplr_validate_profile_form', $errors); 
	} else {
		$errors = false;
	}
	
	return $errors;
}

function simplr_profile_success_message($messages) {
	$messages = 'Your Profile Was Saved';
	return $messages;
}
?>
