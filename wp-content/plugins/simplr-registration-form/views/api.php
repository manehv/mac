<?php 
$data = $_POST; 
$simplr_reg = get_option('simplr_reg_options');
if(isset($data['main-submit'])) {
	if(!wp_verify_nonce(-1, $data['reg-api']) && !current_user_can('manage_options')){ wp_die('Death to hackers!');}
		$simplr_reg->recap_public = $data['recap_public'];
		$simplr_reg->recap_private = $data['recap_private'];
		$simplr_reg->recap_on = $data['recap_on'];
		update_option('simplr_reg_options',$simplr_reg);
} elseif(isset($data['fb-submit'])) {
	if(!wp_verify_nonce(-1, @$data['reg-fb']) && !current_user_can('manage_options')){ wp_die('Death to hackers!');}
		$simplr_reg->fb_connect_on = $data['fb_connect_on'];
		$simplr_reg->fb_app_id = @$data['fb_app_id'];
		$simplr_reg->fb_app_key = @$data['fb_app_key'];
		$simplr_reg->fb_app_secret = @$data['fb_app_secret'];	
		$simplr_reg->fb_login_allow = @$data['fb_login_allow'];
		$simplr_reg->fb_login_redirect = @$data['fb_login_redirect'];
		$simplr_reg->fb_request_perms = @$data['fb_request_perms'];
		$simplr_reg->fb_auto_register = @$data['fb_auto_register'];
		update_option('simplr_reg_options',$simplr_reg);
}
?>
<form action="?page=simplr_reg_set&regview=api" method="post" id="add-field">
<h3>Recaptcha</h3>
<script>
	jQuery.noConflict();
	jQuery(document).ready(function() {
		jQuery('select[name="recap_on"]').change(function() {
			val = jQuery(this).find('option:selected').attr('value');
			if( val == 'yes' ) {
			jQuery('#recap-hidden').show(); 
			} else {
			jQuery('#recap-hidden').hide(); 
			}
		});
		
		jQuery('select[name="fb_connect_on"]').change(function() {
			val = jQuery(this).find('option:selected').attr('value');
			if( val == 'yes' ) {
			jQuery('#fb-hidden').show(); 
			} else {
			jQuery('#fb-hidden').hide(); 
			}
		});
		
	});
	</script>
	<p>If you would like to use ReCaptcha for blocking spam registrations, enter you API keys below:</p>
	<?php 
	SREG_Form::select(array(
	'name'=>'recap_on',
	'label'=>'Enable ReCaptcha?',
	'comment'=>'In order to use Recaptcha anti-spam protection you first need to set you an API account here: http://www.google.com/recaptcha',
	'required'=>false
	),
	$simplr_reg->recap_on, 'wide chzn',
	array('yes'=>'Yes','no'=>'No')
	);
?>

<div id="recap-hidden" <?php if($simplr_reg->recap_on != 'yes') { ?>style="display:none;"<?php } ?>>
<?php
	SREG_Form::text(array(
	'name'=>'recap_public',
	'label'=>'ReCaptcha Public Key',
	'required'=>false
	),
	$simplr_reg->recap_public, 'wide'
	);
	SREG_Form::text(array(
	'name'=>'recap_private',
	'label'=>'ReCaptcha Private Key',
	'required'=>false
	),
	$simplr_reg->recap_private, 'wide '
	);
	?>
</div>
<?php echo wp_nonce_field(-1,"reg-api"); ?>
	<p class="submit">
		<input type="submit" class="button-primary" name="recaptcha-submit" value="<?php _e('Save Changes') ?>" />
	</p>
</form>

<form action="?page=simplr_reg_set&regview=api" method="post" id="add-field">
<h3>Facebook</h3>
	<p>If you intend to use Facebook for Authentication complete form below:</p>
	<?php 
	SREG_Form::select(array(
	'name'=>'fb_connect_on',
	'label'=>'Enable Facebook Connect?',
	'required'=>false,
	'comment'=>'In order to user Facebook Connect you will need to have set up an application at http://www.facebook.com/devloper'
	),
	$simplr_reg->fb_connect_on, 'wide chzn', 
	array('yes'=>'Yes','no'=>'No')
	);
?>

<div id="fb-hidden" <?php if($simplr_reg->fb_connect_on != 'yes') { ?>style="display:none;"<?php } ?>>
<?php
	SREG_Form::text(array(
	'name'=>'fb_app_id',
	'label'=>'Facebook App ID',
	'required'=>false
	),
	$simplr_reg->fb_app_id, 'wide'
	);
	
	/*SREG_Form::text(array(
	'name'=>'fb_app_key',
	'label'=>'Facebook Application Key',
	'required'=>false
	),
	$simplr_reg->fb_app_key, 'wide'
	);*/
	
	SREG_Form::text(array(
	'name'=>'fb_app_secret',
	'label'=>'Facebook Application Secret',
	'required'=>false
	),
	$simplr_reg->fb_app_secret, 'wide'
	);
	
	SREG_Form::checkbox_group(array(
		'name' => 'fb_request_perms',
		'label'=>'Permissions to Request',
		'required'=>true,
		'helper'=>'perms'),
		$simplr_reg->fb_request_perms,'checkgroup',
		''
	);
	
	SREG_Form::select(array(
		'name'=>'fb_login_allow',
		'label'=>'Allow users to login using their Facebook account?',
		'required'=>false,
		'default'=>'no',
		'comment'=>''
		),
		$simplr_reg->fb_login_allow, 
		'wide',
		array('yes'=>'Yes','no'=>'No')
	);
	
	SREG_Form::text(array(
	'name'=>'fb_login_redirect',
	'label'=>'Facebook Login Redirect',
	'required'=>false,
	'comment' => "Where should the user be redirected after logging in with Facebook."
	),
	esc_attr($simplr_reg->fb_login_redirect), 'wide'
	);

	SREG_Form::checkbox(array(
		'name'=>'fb_auto_register',
		'label'=>"Auto-Register",
		'required'=>false,
		'comment'=>"Enabling this option will automatically register and login the user after agreeing to connect your application to his/her profile."
	),$simplr_reg->fb_auto_register,'checkbox');
	?>
</div>
<?php echo wp_nonce_field(-1,"fb-api"); ?>
	<p class="submit">
		<input type="submit" class="button-primary" name="fb-submit" value="<?php _e('Save Changes') ?>" />
	</p>
</form>
