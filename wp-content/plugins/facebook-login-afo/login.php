<?php
/*
Plugin Name: Facebook Login Widget
Plugin URI: http://avifoujdar.wordpress.com/category/my-wp-plugins/
Description: This is a facebook login plugin as widget. This widget also supports default wordpress user login. 
Version: 2.2.0
Author: avimegladon
Author URI: http://avifoujdar.wordpress.com/
*/

/**
	  |||||   
	<(`0_0`)> 	
	()(afo)()
	  ()-()
**/

include_once dirname( __FILE__ ) . '/login_afo_widget.php';


class afo_fb_login {
	
	function __construct() {
		add_action( 'admin_menu', array( $this, 'facebook_login_widget_afo_menu' ) );
		add_action( 'admin_init',  array( $this, 'facebook_login_widget_afo_save_settings' ) );
		add_action( 'plugins_loaded',  array( $this, 'fb_login_widget_text_domain' ) );
	}
	
	function  fb_login_widget_afo_options () {
		global $wpdb;
		$afo_fb_app_id = get_option('afo_fb_app_id');
		$afo_fb_app_secret = get_option('afo_fb_app_secret');
		
		$this->donate_form_facebook_login();
		$this->fb_comment_addon_add();
		$this->fb_login_pro_add();
		$this->help_support();
		?>
		<form name="f" method="post" action="">
		<input type="hidden" name="option" value="login_widget_afo_save_settings" />
		<table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px; margin:2px;">
		  <tr>
			<td width="45%"><h1>Facebook Login Widget</h1></td>
			<td width="55%">&nbsp;</td>
		  </tr>
		  <tr>
			<td><strong>App ID:</strong></td>
			<td><input type="text" name="afo_fb_app_id" value="<?php echo $afo_fb_app_id;?>" /></td>
		  </tr>
		  
		   <tr>
			<td><strong>App Secret:</strong></td>
			 <td><input type="text" name="afo_fb_app_secret" value="<?php echo $afo_fb_app_secret;?>" /></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="submit" value="Save" class="button button-primary button-large" /></td>
		  </tr>
		  <tr>
			<td colspan="2"><?php $this->fb_login_help();?></td>

		  </tr>
		</table>
		</form>
		<?php 
	}
	
	function fb_login_widget_text_domain(){
		load_plugin_textdomain('flw', FALSE, basename( dirname( __FILE__ ) ) .'/languages');
	}
	
	function fb_login_help(){ ?>
		<p><font color="#FF0000"><strong>Note*</strong></font>
			    <br />
		      You need create a new facebook API Applitation to setup this plugin. Please follow the instructions provided below.
			</p>
			  <p>
			  <strong>1.</strong> Go to <a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a> <br /><br />
			  <strong>2.</strong> Click on Create a new app button. A popup will open.<br /><br />
              <strong>3.</strong> Add the required informations and don't forget to make your app live. This is very important otherwise your app will not work for all users.<br /><br />
			  <strong>4.</strong> Then Click the "Create App" button and follow the instructions, your new app will be created. <br /><br />
			  <strong>5.</strong> Copy and Paste "App ID" and "App Secret" here. <br /><br />
			  <strong>6.</strong> That's All. Have fun :)
			  </p>
			  
	<?php }
	
	function fb_comment_plugin_addon_options(){
	global $wpdb;
	$fb_comment_addon = new afo_fb_comment_settings;
	$fb_comments_color_scheme = get_option('fb_comments_color_scheme');
	$fb_comments_width = get_option('fb_comments_width');
	$fb_comments_no = get_option('fb_comments_no');
	?>
	<form name="f" method="post" action="">
	<input type="hidden" name="option" value="save_afo_fb_comment_settings" />
	<table width="100%" border="0" style="background-color:#FFFFFF; margin-top:20px; width:98%; padding:5px; border:1px solid #999999; ">
	  <tr>
		<td colspan="2"><h1>Social Comments Settings</h1></td>
	  </tr>
	  <?php do_action('fb_comments_settings_top');?>
	   <tr>
		<td><h3>Facebook Comments</h3></td>
		<td></td>
	  </tr>
	   <tr>
		<td><strong>Language</strong></td>
		<td><select name="fb_comments_language">
			<option value=""> -- </option>
			<?php echo $fb_comment_addon->language_selected($fb_comments_language);?>
		</select>
		</td>
	  </tr>
	 <tr>
		<td><strong>Color Scheme</strong></td>
		<td><select name="fb_comments_color_scheme">
			<?php echo $fb_comment_addon->get_color_scheme_selected($fb_comments_color_scheme);?>
		</select>
		</td>
	  </tr>
	   <tr>
		<td><strong>Width</strong></td>
		<td><input type="text" name="fb_comments_width" value="<?php echo $fb_comments_width;?>"/> In Percent (%)</td>
	  </tr>
	   <tr>
		<td><strong>No of Comments</strong></td>
		<td><input type="text" name="fb_comments_no" value="<?php echo $fb_comments_no;?>"/> Default is 10</td>
	  </tr>
	  <?php do_action('fb_comments_settings_bottom');?>
	  <tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="Save" class="button button-primary button-large" /></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="2">Use <span style="color:#000066;">[social_comments]</span> shortcode to display Facebook / Disqus Comments in post or page.<br />
		 Example: <span style="color:#000066;">[social_comments title="Comments"]</span>
		 <br /> <br />
		 Or else<br /> <br />
		 You can use this function <span style="color:#000066;">social_comments()</span> in your template to display the Facebook Comments. <br />
		 Example: <span style="color:#000066;">&lt;?php social_comments("Comments");?&gt;</span>
		 </td>
	  </tr>
	</table>
	</form>
	<?php 
	}
	
	function facebook_login_widget_afo_save_settings(){
		if(isset($_POST['option']) and $_POST['option'] == "login_widget_afo_save_settings"){
			update_option( 'afo_fb_app_id', $_POST['afo_fb_app_id'] );
			update_option( 'afo_fb_app_secret', $_POST['afo_fb_app_secret'] );
		}
	}
	
	function facebook_login_widget_afo_menu () {
		add_options_page( 'FB Login Widget', 'FB Login Widget', 'activate_plugins', 'fb_login_widget_afo', array( $this, 'fb_login_widget_afo_options' ));
	}
	
	function help_support(){ ?>
	<table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px; margin:2px;">
	  <tr>
		<td align="right"><a href="http://aviplugins.com/support.php" target="_blank">Help and Support</a></td>
	  </tr>
	</table>
	<?php
	}
	
	function fb_login_pro_add(){ ?>
	<table width="98%" border="0" style="background-color:#FFFFD2; border:1px solid #E6DB55; padding:0px 0px 0px 10px; margin:2px;">
  <tr>
    <td><p>There is a PRO version of this plugin that supports login with <strong>Facebook</strong>, <strong>Google</strong>,  <strong>Twitter</strong> and <strong>LinkedIn</strong>. You can get it <a href="http://aviplugins.com/fb-login-widget-pro/" target="_blank">here</a> in <strong>USD 3.00</strong> </p></td>
  </tr>
</table>
	<?php }
	
	function fb_comment_addon_add(){ 
		if ( !is_plugin_active( 'fb-comments-afo-addon/fb_comment.php' ) ) {
	?>
		<table width="98%" border="0" style="background-color:#FFFFD2; border:1px solid #E6DB55; padding:0px 0px 0px 10px; margin:2px;">
	  <tr>
		<td><p>There is a <strong>Facebook Comments Addon</strong> for this plugin. The plugin replace the default <strong>Wordpress</strong> Comments module and enable <strong>Facebook</strong>/<strong>Disqus</strong> Comments Module. You can get it <a href="http://www.aviplugins.com/fb-comments-afo-addon/" target="_blank">here</a> in <strong>USD 1.00</strong> </p></td>
	  </tr>
	</table>
	<?php 
		}
	}
	
	function donate_form_facebook_login(){
		if ( !is_plugin_active( 'fb-comments-afo-addon/fb_comment.php' ) ) {
	?>
		<table width="98%" border="0" style="background-color:#FFFFD2; border:1px solid #E6DB55; margin:2px;">
		 <tr>
		 <td align="right"><h3>Even $0.60 Can Make A Difference</h3></td>
			<td><form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
				  <input type="hidden" name="cmd" value="_xclick">
				  <input type="hidden" name="business" value="avifoujdar@gmail.com">
				  <input type="hidden" name="item_name" value="Donation for plugins (FB Login)">
				  <input type="hidden" name="currency_code" value="USD">
				  <input type="hidden" name="amount" value="0.60">
				  <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Make a donation with PayPal">
				</form></td>
		  </tr>
		</table>
	<?php }
	}
}
new afo_fb_login;