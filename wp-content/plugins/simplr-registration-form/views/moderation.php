<?php
$data = $_POST;
$defaults = (object) array(
	'mod_on'                    => 'no',
	'mod_email'                 => __("You've successfully registered for %%blogname%% but before your account can be used you must activate it. To activate use the link below %%link%%. ",'simplr-reg'),
	'mod_email_subj'            => __("Please activate your %%blogname%% account",'simplr-reg'),
	'mod_activation'            => "auto",
	'mod_email_activated'       => __("Your account was activated. Your username is %%username%%.",'simplr-reg'),
	'mod_email_activated_subj'  => __("Your %%blogname%% account was activated.",'simplr-reg'),
	'mod_roles'                 => array('administrator'),
);
$simplr_reg = get_option('simplr_reg_options');
//setup defaults
foreach($defaults as $k => $v ) {
	$simplr_reg->$k = $simplr_reg->$k ? $simplr_reg->$k : $defaults->$k;
}
if(isset($data['mod-submit'])) {
	if(!wp_verify_nonce(-1, $data['reg-mod']) && !current_user_can('manage_options')){ wp_die('Death to hackers!');}
	foreach($data as $k => $v) {
		$simplr_reg->$k = $v ? $v : $defaults->$k;
	}
	update_option('simplr_reg_options', $simplr_reg);
	echo '<div id="message" class="updated alert message"><p>'.__("Settings saved",'simplr-reg').'</p></div>';
}
?>
<form id="add-field" action="" method="post">
<h3><?php _e('Moderation','simplr-reg'); ?>"</h3>
<p><?php _e('These settings allow you to enable and control moderation','simplr-reg'); ?><p>
<?php SREG_Form::select(array(
	'name'		=> 'mod_on',
	'label'		=> __('Enable Moderation?','simplr-reg'),
	'required'	=> false
	),
	$simplr_reg->mod_on, 'wide chzn',
		array('yes'=>'Yes','no'=>'No')
	);
?>

<div class="mod-choices" <?php if($simplr_reg->mod_on == 'no' ) { echo 'style="display:none;"'; } ?>>
	<?php SREG_Form::select(array(
		'name'	=> 'mod_activation',
		'label'	=> __('Approval Mode','simplr-reg'),
		'required'	=> false,
		'default'	=> 'auto',
		'comment'	=> __("In *automatic* mode, the user is activated/approved as soon as the activation link in the moderation email is clicked. In *manual* mode the user is only approved after a site admin has approved that account.",'simplr-reg')), $simplr_reg->mod_activation, 'select chzn wide alignleft',
			array('auto'=> __('Automatic','simplr-reg'),'manually'=>__('Manual','simplr-reg'))
		);
	?>

	<?php SREG_Form::text(array(
		'name' => 'mod_email_subj',
		'label'=> __('Email Subject Line','simplr-reg'),
		'required'=>false,
		'default'	=> __("Welcome to %%blogname%%",'simplr-reg'),
		), $simplr_reg->mod_email_subj, 'text input');
	?>
	<?php SREG_Form::textarea(array(
		'name'	=> 'mod_email',
		'label'	=> __('Moderation Email','simplr-reg'),
		'required'	=> false,
		'comment'	=> __("You can use user submitted values in this field by wrapping them in %%. For instance to use the value of the field 'first_name' you would type 'Welcome, %%first_name%% '. Use %%link%% for the activation link. ",'simplr-reg'),
		'default'	=> __("hello",'simplr-reg'),
		),
		$simplr_reg->mod_email, 'textarea wide',
		array( '500px', '150px')
	); ?>
	<?php $roles = new WP_Roles(); ?>
	<?php SREG_Form::select(array(
		'name'	=> 'mod_roles[]',
		'label'	=> __('Roles','simplr-reg'),
		'multiple'	=> true,
		'comment'	=> __("Which user roles can moderate new users.",'simplr-reg'),
		'required'	=> true), $simplr_reg->mod_roles, 'wide chzn alignleft', $roles->get_names()
		);
	?>
	<?php SREG_Form::text(array(
		'name' => 'mod_email_activated_subj',
		'label'=> __('Account Activated Email Subject Line','simplr-reg'),
		'required'=>false,
		), $simplr_reg->mod_email_activated_subj, 'text input');
	?>
	<?php SREG_Form::textarea(array(
		'name'  => 'mod_email_activated',
		'label' => __('Account Activated Email','simplr-reg'),
		'required' => false,
		'comment'  => "This email is sent to alert the user their account was activated. ",
		),
		$simplr_reg->mod_email_activated, 'textarea wide',
		array( '500px', '150px')
		); ?>
</div>

<p class="submit">
	<?php wp_nonce_field('reg-mod',-1); ?>
	<input type="submit" name="mod-submit" class="button button-primary" value="<?php _e('Submit','simplr-reg'); ?>" />
</p>

<script>
jQuery.noConflict();
jQuery(document).ready(function($) {
	$('select[name="mod_on"]').change(function() {
		var val =$(this).find('option:selected').val();
		if(val == 'yes') { $('.mod-choices').slideDown(); } else { $('.mod-choices').slideUp(); }
	});
});
</script>
</form>
