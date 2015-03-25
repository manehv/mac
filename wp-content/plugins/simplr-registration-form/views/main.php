<?php
$data = $_POST;
$simplr_reg = get_option('simplr_reg_options');
if(isset($data['main-submit'])) {
	if(!wp_verify_nonce(-1, $data['reg-main']) && !current_user_can('manage_options')){ wp_die('Death to hackers!');}
	$simplr_reg->email_message = $data['email_message'];
	$simplr_reg->default_email = $data['default_email'];
	$simplr_reg->stylesheet = $data['stylesheet'];
	$simplr_reg->styles = $data['styles'];
	$simplr_reg->register_redirect = $data['register_redirect'];
	$simplr_reg->thank_you = $data['thank_you'];
//	$simplr_reg->login_redirect = $data['login_redirect'];
	$simplr_reg->profile_redirect = $data['profile_redirect'];
	//$simplr_reg->recap_public = $data['recap_public'];
	//$simplr_reg->recap_private = $data['recap_private'];
	//$simplr_reg->recap_on = $data['recap_on'];
	update_option('simplr_reg_options',$simplr_reg);
}
?>
<form action="?page=simplr_reg_set&regview=main" method="post" id="add-field">
	<h3><?php _e('Core Settings','simplr-reg'); ?></h3>
	<?php
	SREG_Form::related_select(array(
		'name'=>'register_redirect',
		'label'=>__('Redirect default signup action to this page','simplr-reg'),
		'required'=>false,
		'post_type'=>'page','comment'=>__('Use this option to redirect the default WordPress wp-login.php?action=redirect action. This will not redirect your login page as a whole.','simplr-reg')),
		@$simplr_reg->register_redirect,
		'wide chzn'
	);

	/*SREG_Form::related_select(array(
		'name'=>'login_redirect',
		'label'=>'Redirect default login action to this page',
		'required'=>false,
		'post_type'=>'page'),
		$simplr_reg->login_redirect,
		'wide'
		);*/

	SREG_Form::related_select(array(
		'name'=>'profile_redirect',
		'label'=>__('Redirect default profile page','simplr-reg'),
		'required'=>false,
		'post_type'=>'page',
		'comment'=>__('Use this option to force users to access only their "front" end profile page ... i.e. the page with the [profile_page] shortcode','simplr-reg')),
		@$simplr_reg->profile_redirect,
		'wide chzn'
		);

	SREG_Form::related_select(array(
		'name'			=>'thank_you',
		'label'			=>__('Custom Thank You Page','simplr-reg'),
		'required'	=>false,
		'post_type'	=>'page',
		'comment' 	=> __('You can leave this field blank. The default behavior outputs a confirmation on the page which the registration shortcode is save. Use this only if you want to redirect the user elsewhere after registration.','simplr-reg')),
		@$simplr_reg->thank_you,
		'wide chzn',''
	);

	SREG_Form::text(array(
		'name'=>'default_email',
		'label'=>__('Default FROM Email','simplr-reg'),
		'required'=>false
	),
	$simplr_reg->default_email?$simplr_reg->default_email:get_option('admin_email'), 'wide'
	);
	SREG_Form::radio(array(
		'name'=>'styles',
		'label'=>__('Disable Plugin Styles','simplr-reg'),
		'required'=>'no',
		'default' => 'no'
		), @$simplr_reg->styles, '',
		array('yes'=>'Yes','no'=>'No')
	);
if( $simplr_reg->styles != 'yes' ):

	SREG_Form::select(array(
		'name'=>'style_skin',
		'label'	=> __('Select a style','simplr-reg'),
		'required'	=> 'no',
		'default'	=> 'default.css',
		'helper'	=> 'skins'),
		@$simplr_reg->style_skin, '', '');

	SREG_Form::text(array(
		'name'=>'stylesheet',
		'label'=>__('Override Default Stylesheet','simplr-reg'),
		'required'=>false,
		'comment'=>__('Specify a custom stylesheet. Will not apply if "Plugin Styles" are disabled','simplr-reg')
	),
	@$simplr_reg->stylesheet, 'wide'
	);
endif;
	/*Deprecated: All registration forms now have custom messages.
	SREG_Form::textarea(array(
		'name'=>'email_message',
		'label'=>'Email Message',
		'required'=>false
	),
	@$simplr_reg->email_message, 'wide',
		array('95%','200px')
	);*/
	?>
	<?php echo wp_nonce_field(-1,"reg-main"); ?>
	<p class="submit">
		<input type="submit" class="button-primary" name="main-submit" value="<?php _e('Save Changes','simplr-reg') ?>" />
	</p>
</form>
