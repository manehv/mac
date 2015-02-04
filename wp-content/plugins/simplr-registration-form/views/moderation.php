<?php
$data = $_POST;
$defaults = (object) array(
	'mod_on'			=> 'no',
	'mod_email'			=> "You've successfully registered for %%blogname%% but before your account can be used you must activate it. To activate use the link below %%link%%. ",
	'mod_email_subj'		=> "Please activate your %%blogname%% account",
	'mod_activation'		=> "auto",
	'mod_email_activated' 		=> "Your account was activated.",
	'mod_email_activated_subj' 	=> "Your %%blogname%% account was activated.",
	'mod_roles'			=> array('administrator'),
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
	echo '<div id="message" class="updated alert message"><p>'.__("Settings saved",'simplr-reg-form').'</p></div>';
}
?>
<form id="add-field" action="" method="post" >
<h3>Moderation</h3>
<p>These settings allow you to enable and control moderation<p>
<?php SREG_Form::select(array(
	'name'		=> 'mod_on',
	'label'		=> 'Enable Moderation?',
	'required'	=> false
	),
	$simplr_reg->mod_on, 'wide chzn',
		array('yes'=>'Yes','no'=>'No')
	);
?>

<div class="mod-choices" <?php if($simplr_reg->mod_on == 'no' ) { echo 'style="display:none;"'; } ?>>
	<?php SREG_Form::select(array(	
		'name'	=> 'mod_activation',
		'label'	=> 'Approval Mode',
		'required'	=> false,
		'default'	=> 'auto',
		'comment'	=> "In *automatic* mode, the user is activated/approved as soon as the activation link in the moderation email is clicked. In *manual* mode the user is only approved after a site admin has approved that account."), $simplr_reg->mod_activation, 'select chzn wide alignleft',
			array('auto'=> 'Automatic','manually'=>'Manual')
		);
	?>

	<?php SREG_Form::text(array(
		'name' => 'mod_email_subj',
		'label'=> 'Email Subject Line',
		'required'=>false,
		'default'	=> "Welecome to %%blogname%%",
		), $simplr_reg->mod_email_subj, 'text input'); 
	?>
	<?php SREG_Form::textarea(array(
		'name'	=> 'mod_email',
		'label'	=> 'Moderation Email',
		'required'	=> false,
		'comment'	=> "You can use user submitted values in this field by wrapping them in %%. For instance to use the value of the field 'first_name' you would type 'Welcome, %%first_name%% '. Use %%link%% for the activation link. ",
		'default'	=> "hello",
		),
		$simplr_reg->mod_email, 'textarea wide',
		array( '500px', '150px')
	); ?>
	<?php $roles = new WP_Roles(); ?>
	<?php SREG_Form::select(array(
		'name'	=> 'mod_roles[]',
		'label'	=> 'Roles',
		'multiple'	=> true,
		'comment'	=> "Which user roles can moderate new users.",
		'required'	=> true), $simplr_reg->mod_roles, 'wide chzn alignleft', $roles->get_names()
		);
	?> 
	<?php SREG_Form::text(array(
                'name' => 'mod_email_activated_subj',
                'label'=> 'Account Activated Email Subject Line',
                'required'=>false,
                ), $simplr_reg->mod_email_activated_subj, 'text input');
        ?>
        <?php SREG_Form::textarea(array(
                'name'  => 'mod_email_activated',
                'label' => 'Account Activated Email',
                'required'      => false,
                'comment'       => "This email is sent to alert the user their account was activated. ",
                ),
                $simplr_reg->mod_email_activated, 'textarea wide',
                array( '500px', '150px')
        ); ?>
</div>

<p class="submit">
	<?php wp_nonce_field('reg-mod',-1); ?>
	<input type="submit" name="mod-submit" class="button button-primary" value="Submit" />
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
