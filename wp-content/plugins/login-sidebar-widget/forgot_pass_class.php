<?php

class afo_forgot_pass_class {
	
	public function ForgotPassForm(){
		if(!session_id()){
			@session_start();
		}
		
		$this->error_message();
		if(!is_user_logged_in()){
		?>
		<form name="forgot" id="forgot" method="post" action="">
		<input type="hidden" name="option" value="afo_forgot_pass" />
			<ul class="login_wid forgot_pass">
				<li><?php _e('Email','lwa');?></li>
				<li><input type="text" name="user_username" required="required"/></li>
				<li><input name="forgot" type="submit" value="<?php _e('Submit','lwa');?>" /></li>
				<li class="forgot-text">Please enter your email. The password reset link will be provided in your email.</li>
			</ul>
		</form>
		<?php 
		}
	}
	
	public function message_close_button(){
		$cb = '<a href="javascript:void(0);" onclick="closeMessage();" class="close_button_afo">x</a>';
		return $cb;
	}
	
	public function error_message(){
		if(!session_id()){
			@session_start();
		}
		
		if($_SESSION['msg']){
			echo '<div class="'.$_SESSION['msg_class'].'">'.$_SESSION['msg'].$this->message_close_button().'</div>';
			unset($_SESSION['msg']);
			unset($_SESSION['msg_class']);
		}
	}
} 


function forgot_pass_validate(){
	if(!session_id()){
		@session_start();
	}
	
	if(isset($_GET['key']) && $_GET['action'] == "reset_pwd") {
		global $wpdb;
		$reset_key = $_GET['key'];
		$user_login = $_GET['login'];
		$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));
		
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		
		if(!empty($reset_key) && !empty($user_data)) {
			$new_password = wp_generate_password(7, false);
				wp_set_password( $new_password, $user_data->ID );
			//mailing reset details to the user
			$headers = 'From: '.get_bloginfo('name').' <no-reply@wordpress.com>' . "\r\n";
			$message = __('Your new password for the account at:') . "\r\n\r\n";
			$message .= site_url() . "\r\n\r\n";
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
			$message .= sprintf(__('Password: %s'), $new_password) . "\r\n\r\n";
			$message .= __('You can now login with your new password at: ') . site_url() . "\r\n\r\n";
			
			if ( $message && !wp_mail($user_email, 'Password Reset Request', $message, $headers) ) {
				wp_die('Email failed to send for some unknown reason');
				exit;
			}
			else {
				wp_die('New Password successfully sent to your mail address.');
				exit;
			}
		} 
		else {
			wp_die('Not a Valid Key.');
			exit;
		}
}

	if($_POST['option'] == "afo_forgot_pass"){
	
		global $wpdb;
		$msg = '';
		if(empty($_POST['user_username'])) {
			$_SESSION['msg_class'] = 'error_wid_login';
			$msg .= __('Email is empty!','lwa');
		}
		
		$user_username = $wpdb->escape(trim($_POST['user_username']));
		
		$user_data = get_user_by('email', $user_username);
		if(empty($user_data)) { 
			$_SESSION['msg_class'] = 'error_wid_login';
			$msg .= __('Invalid E-mail address!','lwa');
		}
		
		$user_login = $user_data->data->user_login;
		$user_email = $user_data->data->user_email;
		
		if($user_email){
			$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
			if(empty($key)) {
				$key = wp_generate_password(10, false);
				$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));	
			}
			
			//mailing reset details to the user
			$headers = 'From: '.get_bloginfo('name').' <no-reply@wordpress.com>' . "\r\n";
			$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
			$message .= site_url() . "\r\n\r\n";
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
			$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
			$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
			$message .= site_url() . "?action=reset_pwd&key=$key&login=" . rawurlencode($user_login) . "\r\n";
			
			if ( !wp_mail($user_email, 'Password Reset Request', $message, $headers) ) {
				$_SESSION['msg_class'] = 'error_wid_login';
				$_SESSION['msg'] = __('Email failed to send for some unknown reason.','lwa');
			}
			else {
				$_SESSION['msg_class'] = 'success_wid_login';
				$_SESSION['msg'] = __('We have just sent you an email with Password reset instructions.','lwa');
			}
		} else {
			$_SESSION['msg_class'] = 'error_wid_login';
			$_SESSION['msg'] = $msg;
		}
	}
}
	

add_action( 'init', 'forgot_pass_validate' );
?>