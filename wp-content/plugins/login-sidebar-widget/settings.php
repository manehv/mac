<?php
class login_settings {

	private $default_style = '
	.login_wid{
		list-style-type:none;
		border: 1px dashed #999999;
		width:98%;
		float:left;
		padding:2%;
	}
	.login_wid li{
		width:45%;
		float:left;
		margin:2px;
	}
	.afo_social_login{
		padding:5px 0px 0px 0px;
		clear:both;
		width:100% !important;
	}';
	
	function __construct() {
		$this->load_settings();
	}
	
	function login_widget_afo_save_settings(){
		
		if($_POST['option'] == "login_widget_afo_save_settings"){
			
			if ( ! isset( $_POST['login_widget_afo_field'] )  || ! wp_verify_nonce( $_POST['login_widget_afo_field'], 'login_widget_afo_action' ) ) {
			   wp_die( 'Sorry, your nonce did not verify.' );
			   exit;
			} 
		
			update_option( 'redirect_page',  sanitize_text_field($_POST['redirect_page']) );
			update_option( 'logout_redirect_page',  sanitize_text_field($_POST['logout_redirect_page']) );
			update_option( 'link_in_username',  sanitize_text_field($_POST['link_in_username']) );
			update_option( 'login_afo_rem',  sanitize_text_field($_POST['login_afo_rem']) );
			update_option( 'login_afo_forgot_pass_link',  sanitize_text_field($_POST['login_afo_forgot_pass_link']) );
			update_option( 'login_afo_register_link',  sanitize_text_field($_POST['login_afo_register_link']) );
			
			if($_POST['lead_default_style'] == "Yes"){
				update_option( 'custom_style_afo', sanitize_text_field($this->default_style) );
			} else {
				update_option( 'custom_style_afo',  sanitize_text_field($_POST['custom_style_afo']) );
			}
		}
	}
	
	function  login_widget_afo_options () {
	global $wpdb;
	
	$redirect_page = get_option('redirect_page');
	$logout_redirect_page = get_option('logout_redirect_page');
	$link_in_username = get_option('link_in_username');
	$login_afo_rem = get_option('login_afo_rem');
	$login_afo_forgot_pass_link = get_option('login_afo_forgot_pass_link');
	$login_afo_register_link = get_option('login_afo_register_link');
	
	$custom_style_afo = stripslashes(get_option('custom_style_afo'));
	
	$this->donate_form_login();
	$this->fb_comment_addon_add();
	$this->fb_login_pro_add();
	?>
	<form name="f" method="post" action="">
	<?php wp_nonce_field('login_widget_afo_action','login_widget_afo_field'); ?>
	<input type="hidden" name="option" value="login_widget_afo_save_settings" />
	<table width="100%" border="0">
	  <tr>
		<td width="45%"><h1>Login Widget AFO Settings</h1></td>
		<td width="55%">&nbsp;</td>
	  </tr>
	  <tr>
		<td><strong>Login Redirect Page:</strong></td>
		<td><?php
				$args = array(
				'depth'            => 0,
				'selected'         => $redirect_page,
				'echo'             => 1,
				'show_option_none' => '-',
				'id' 			   => 'redirect_page',
				'name'             => 'redirect_page'
				);
				wp_dropdown_pages( $args ); 
			?></td>
	  </tr>
	  
	   <tr>
		<td><strong>Logout Redirect Page:</strong></td>
		 <td><?php
				$args1 = array(
				'depth'            => 0,
				'selected'         => $logout_redirect_page,
				'echo'             => 1,
				'show_option_none' => '-',
				'id' 			   => 'logout_redirect_page',
				'name'             => 'logout_redirect_page'
				);
				wp_dropdown_pages( $args1 ); 
			?></td>
	  </tr>
	   
	  <tr>
		<td><strong>Link in Username</strong></td>
		<td><?php
				$args2 = array(
				'depth'            => 0,
				'selected'         => $link_in_username,
				'echo'             => 1,
				'show_option_none' => '-',
				'id' 			   => 'link_in_username',
				'name'             => 'link_in_username'
				);
				wp_dropdown_pages( $args2 ); 
			?></td>
	  </tr>
	  <tr>
		<td><strong>Add Remember Me</strong></td>
		<td><input type="checkbox" name="login_afo_rem" value="Yes" <?php echo $login_afo_rem == 'Yes'?'checked="checked"':'';?> /></td>
	  </tr>
	  <tr>
		<td><strong>Forgot Password Link</strong></td>
		<td>
			<?php
				$args3 = array(
				'depth'            => 0,
				'selected'         => $login_afo_forgot_pass_link,
				'echo'             => 1,
				'show_option_none' => '-',
				'id' 			   => 'login_afo_forgot_pass_link',
				'name'             => 'login_afo_forgot_pass_link'
				);
				wp_dropdown_pages( $args3 ); 
			?>
			<i>Leave blank to not include the link</i>
			</td>
	  </tr>
	  <tr>
		<td><strong>Register Link</strong></td>
		<td>
			<?php
				$args4 = array(
				'depth'            => 0,
				'selected'         => $login_afo_register_link,
				'echo'             => 1,
				'show_option_none' => '-',
				'id' 			   => 'login_afo_register_link',
				'name'             => 'login_afo_register_link'
				);
				wp_dropdown_pages( $args4 ); 
			?>
			<i>Leave blank to not include the link</i>
			</td>
	  </tr>
	   <tr>
			<td width="45%"><h1>Styling</h1></td>
			<td width="55%">&nbsp;</td>
		  </tr>
	   <tr>
			<td valign="top"><input type="checkbox" name="lead_default_style" value="Yes" /><strong> Load Default Styles</strong><br />
			Check this and hit the save button to go back to default styling.
			</td>
			<td><textarea name="custom_style_afo" style="width:80%; height:200px;"><?php echo $custom_style_afo;?></textarea></td>
		  </tr>
		  
	  <tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="submit" value="Save" class="button button-primary button-large" /></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="2">Use <span style="color:#000066;">[login_widget]</span> shortcode to display login form in post or page.<br />
		 Example: <span style="color:#000066;">[login_widget title="Login Here"]</span></td>
	  </tr>
	  <tr>
		<td colspan="2">Use <span style="color:#000066;">[forgot_password]</span> shortcode to display forgot password form in post or page.<br />
		 Example: <span style="color:#000066;">[forgot_password title="Forgot Password?"]</span></td>
	  </tr>
	</table>
	</form>
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
	
	function login_widget_afo_text_domain(){
		load_plugin_textdomain('lwa', FALSE, basename( dirname( __FILE__ ) ) .'/languages');
	}
	
	function plug_install_afo_fb_login(){
		update_option( 'custom_style_afo', $this->default_style );
	}
	
	function login_widget_afo_menu () {
		add_options_page( 'Login Widget', 'Login Widget Settings', 10, 'login_widget_afo', array( $this,'login_widget_afo_options' ));
	}
	
	function load_settings(){
		add_action( 'admin_menu' , array( $this, 'login_widget_afo_menu' ) );
		add_action( 'admin_init', array( $this, 'login_widget_afo_save_settings' ) );
		add_action( 'plugins_loaded',  array( $this, 'login_widget_afo_text_domain' ) );
		register_activation_hook(__FILE__, array( $this, 'plug_install_afo_fb_login' ) );
	}
	
	function fb_comment_addon_add(){ 
		if ( !is_plugin_active( 'fb-comments-afo-addon/fb_comment.php' ) ) {
	?>
		<table width="98%" border="0" style="background-color:#FFFFD2; border:1px solid #E6DB55; padding:0px 0px 0px 10px; margin:2px;">
	  <tr>
		<td><p>There is a <strong>Facebook Comments Addon</strong> for this plugin. The plugin replace the default <strong>Wordpress</strong> Comments module and enable <strong>Facebook</strong> Comments Module. You can get it <a href="http://aviplugins.com/fb-comments-addon/" target="_blank">here</a> in <strong>USD 1.00</strong> </p></td>
	  </tr>
	</table>
	<?php 
		}
	}
	
	function fb_login_pro_add(){ ?>
	<table width="98%" border="0" style="background-color:#FFFFD2; border:1px solid #E6DB55; padding:0px 0px 0px 10px; margin:2px;">
  <tr>
    <td><p>There is a PRO version of this plugin that supports login with <strong>Facebook</strong>, <strong>Google</strong>,  <strong>Twitter</strong> and <strong>LinkedIn</strong>. You can get it <a href="http://aviplugins.com/fb-login-widget-pro/" target="_blank">here</a> in <strong>USD 1.50</strong> </p></td>
  </tr>
</table>
	<?php }
	
	function donate_form_login(){
	if ( !is_plugin_active( 'fb-comments-afo-addon/fb_comment.php' ) ) {
	?>
	<table width="98%" border="0" style="background-color:#FFFFD2; border:1px solid #E6DB55; margin:2px;">
	 <tr>
	 <td align="right"><h3>Even $0.60 Can Make A Difference</h3></td>
		<td><form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
			  <input type="hidden" name="cmd" value="_xclick">
			  <input type="hidden" name="business" value="avifoujdar@gmail.com">
			  <input type="hidden" name="item_name" value="Donation for plugins (Login)">
			  <input type="hidden" name="currency_code" value="USD">
			  <input type="hidden" name="amount" value="0.60">
			  <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Make a donation with PayPal">
			</form></td>
	  </tr>
	</table>
	<?php }
	}
}
new login_settings;
