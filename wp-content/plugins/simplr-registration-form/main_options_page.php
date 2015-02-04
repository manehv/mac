<?php $profile_fields = get_option('simplr_profile_fields'); ?>
<div class="wrap">
	
	<div  class="icon32">
	<img src="<?php echo SIMPLR_URL; ?>/assets/images/sign-up-32.png" ><br></div>
	
	<h2 class="nav-tab-wrapper">
	<a href="?page=simplr_reg_set&regview=main" class="nav-tab <?php if($_GET['regview'] == 'main' || !isset($_GET['regview'])) { echo 'nav-tab-active'; } ?>" >Settings</a>
	<a href="?page=simplr_reg_set&regview=fields&orderby=name&order=desc" class="nav-tab <?php if($_GET['regview'] == 'fields') { echo 'nav-tab-active'; } ?>" >Fields</a>
	<a href="?page=simplr_reg_set&regview=moderation" class="nav-tab <?php if($_GET['regview'] == 'moderation') { echo 'nav-tab-active'; } ?>" >Moderation</a>
	<a href="?page=simplr_reg_set&regview=api" class="nav-tab <?php if($_GET['regview'] == 'api') { echo 'nav-tab-active'; } ?>" >API</a>
	<a href="?page=simplr_reg_set&regview=instructions" class="nav-tab <?php if($_GET['regview'] == 'instructions') { echo 'nav-tab-active'; } ?>" >Instructions</a>
	</h2>
		
	<?php 
	//options page logic
	if(get_option('users_can_register') == 1) {
		$slug = (isset($_GET['regview'])) ? $_GET['regview'] : 'main'; 
		$regview = SIMPLR_DIR . '/views/'.$slug .'.php';
		include($regview);
	} else {
	?>
	<div id="message" class="error errors">
		<p style="color:#333;">Your site is not configured to allow user registrations. To turn on user registration change the membership setting on the <strong>Settings >> General</strong> menu.</p>
	</div>
	<?php
	}
	?>	
	
</div>
