<?php
   /*
   Plugin Name: Casengo Contact Widget
   Plugin URI: http://www.casengo.com/plugins/wordpress/v2
   Description: A plugin to add the Casengo widget to the Wordpress site
   Version: 2.1.1
   Author: Thijs van der Veen
   Author URI: http://www.casengo.com
   License: GPL2
   */

function casengo() {
	
  $cas_domain = get_option('cas_widget_domain');
  $cas_type = get_option('cas_widget_type');
  $cas_pos = get_option('cas_widget_pos');
  $cas_label = get_option('cas_widget_label');
  $cas_theme = get_option('cas_widget_theme');
  $cas_lang = get_option('cas_widget_lang');
  $cas_lang_id = get_option('cas_widget_lang_id');
  
  // DEFAULT VALUES
  if(!isset($cas_domain)) $cas_domain = 'support';
  if(!isset($cas_type)) $cas_type = 'inline';
  if(!isset($cas_pos)) $cas_pos = 'middle-left';
  if(!isset($cas_label)) $cas_label = 'Contact';
  if(!isset($cas_theme)) $cas_theme = 'darkgrey';
  if(!isset($cas_lang)) $cas_lang = '';
  if(!isset($cas_lang_id)) $cas_lang_id = '';
  
  // embed script	

  // if language is specified
  if(trim($cas_lang) !== '' && $cas_lang != 'custom_lang') {
    $cas_language_add = ' language="' . $cas_lang . '"';
  } else {
    $cas_language_add = '';
  }
  
  if($cas_lang === 'custom_lang') {
    $cas_language_add = ' language="' . $cas_lang_id . '"';
  }

if($cas_type != 'inline') {

  echo '
  		<!-- Live Chat and Customer Support Software by Casengo - WordPress Live Chat and Customer Support Software v2.0 - http://www.casengo.com/ -->
        <!--Place this code where you want the button to be rendered -->
		<div class="casengo-vipbtn"><span style="display:none" subdomain="' . $cas_domain . '" group="39" label="' . $cas_label . '" position="' . $cas_pos . '" theme="' . $cas_theme . '"' . $cas_language_add . ' /></div>
		<!--Place this code after the last Casengo script -->
		<script type="text/javascript">
			(function() {
				var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
				po.src = \'//' . $cas_domain . '.casengo.com/apis/vip-widget.js?r=\'+new Date().getTime();
				var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
			})();
		</script>
<a style="display:none" id="Casengo-Customer-Support-Software" href="http://www.casengo.com">Casengo is customer support software, crm, webcare and live chat software for webshops, e-commerce websites, and small businesses. Casengo supports email, livechat, social media, faq, self service and online chat.</a> 

<a style="display:none" id="Casengo-Customer-Support-Software-and-Live-Chat-FAQ" href="http://support.casengo.com">Check the Casengo Customer Support and Live Chat FAQ page for answers to frequently asked questions, and how to get Casengo customer support software, live-chat, and helpdesk software going.</a>  
        
<a style="display:none" id="Casengo-CRM-Live-Chat-and-Customer-Service-Blog" href="http://www.casengo.com/blog">The blog for anyone interested in customer support, customer service, live chat, social CRM, small business tips and Casengo product updates.</a>
		<!-- // Casengo Wordpress Live Chat and Customer Support Software -->
  ';
  
} else {
  echo '
  		<!-- Live Chat and Customer Support Software by Casengo - WordPress Live Chat and Customer Support Software v2.0 - http://www.casengo.com/ -->
        <!--Place this code where you want the button to be rendered -->
		<div class="casengo-vipbtn"><span style="display:none" subdomain="' . $cas_domain . '" group="undefined" ctype="inline"' . $cas_language_add . ' /></div>
		<!--Place this code after the last Casengo script -->
		<script type="text/javascript">
			(function() {
				var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
				po.src = \'//' . $cas_domain . '.casengo.com/apis/inline-widget.js?r=\'+new Date().getTime();
				var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
			})();
		</script>
<a style="display:none" id="Casengo-Customer-Support-Software" href="http://www.casengo.com">Casengo is customer support software, crm, webcare and live chat software for webshops, e-commerce websites, and small businesses. Casengo supports email, livechat, social media, faq, self service and online chat.</a> 

<a style="display:none" id="Casengo-Customer-Support-Software-and-Live-Chat-FAQ" href="http://support.casengo.com">Check the Casengo Customer Support and Live Chat FAQ page for answers to frequently asked questions, and how to get Casengo customer support software, live-chat, and helpdesk software going.</a>  
        
<a style="display:none" id="Casengo-CRM-Live-Chat-and-Customer-Service-Blog" href="http://www.casengo.com/blog">The blog for anyone interested in customer support, customer service, live chat, social CRM, small business tips and Casengo product updates.</a>
		<!-- // Casengo Wordpress Live Chat and Customer Support Software -->
  ';
  }
}

add_action( 'wp_footer', 'casengo' );

// *** ADMIN

function casengo_activate_plugin() {
    // work-around to redirect to admin plugin page after plugin activiation
    add_option('casengo_do_activation_redirect', true);
}

function casengo_redirect() {
    // redirect to plugin admin page after plugin activation
    if (get_option('casengo_do_activation_redirect', false)) {
        delete_option('casengo_do_activation_redirect');
	 wp_redirect(admin_url('admin.php?page=the-casengo-chat-widget/casengo.php'));
    }
}

//add_action( 'admin_menu', 'my_plugin_menu' );
add_action( 'admin_menu', 'casengo_admin_menu' );

register_activation_hook( __FILE__, 'casengo_activate_plugin' );
add_action('admin_init', 'casengo_redirect');

/*
function casengo_admin_menu_exists($menu_name) {
	global $menu;
	$menuExist = false;
	foreach($menu as $item) {
		if(strtolower($item[0]) == strtolower($menu_name)) {
			$menuExist = true;
		}
	}
	return $menuExist;

}
*/

function casengo_admin_menu_exists( $handle, $sub = true){
  global $menu, $submenu;
  $check_menu = $sub ? $submenu : $menu;
  if( empty( $check_menu ) )
    return false;
  foreach( $check_menu as $k => $item ){
    if( $sub ){
      foreach( $item as $sm ){
        if($handle == $sm[2])
          return true;
      }
    } else {
      if( $handle == $item[2] )
        return true;
    }
  }
  return false;
}

function casengo_admin_menu() {
	$file = dirname( __FILE__ ) . '/casengo.php';
	$icon = plugin_dir_url(__FILE__) . "/images/favicon.png";
	//if (! casengo_admin_menu_exists(dirname( __FILE__ ) . '/casengo.php')) {
		add_menu_page('Casengo ( Chat )', 'Casengo ( Chat )', 'manage_options', dirname( __FILE__ ) . '/casengo.php', '', $icon);
	//}
	add_submenu_page(dirname( __FILE__ ) . '/casengo.php', 'Settings', 'Settings', 'manage_options', dirname( __FILE__ ) . '/casengo.php', 'casengo_settings');	

	//if (! casengo_admin_menu_exists('casengo-friends')) {
		add_submenu_page(dirname( __FILE__ ) . '/casengo.php', 'Our Friends', 'Our Friends', 'manage_options', dirname( __FILE__ ) . '/friends.php');
	//}

}

function my_plugin_menu() {
	// deprecated code
	add_options_page( 'Casengo Chat Widget Options', 'Casengo Chat Widget', 'manage_options', 'casengoWidgetPlugin', 'casengo_settings' );
}

function casengo_settings() {

  if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

    // variables for the field and option names 
    $opt_name = 'cas_widget_pos';
    $hidden_field_name = 'cas_submit_hidden';
    $data_field_name = 'cas_widget_pos';

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

        // Save the posted values in the database
        update_option( 'cas_widget_type', $_POST['cas_widget_type']);
        update_option( 'cas_widget_pos', $_POST['cas_widget_pos']);
        update_option( 'cas_widget_domain', $_POST['cas_widget_domain']);
        
        // If customer enters empty label, use default
        if($_POST['cas_widget_label'] == '') {
            $label='Contact';
        } else {
            $label=stripslashes($_POST['cas_widget_label']);
        }
        
        update_option( 'cas_widget_label', $label);
        update_option( 'cas_widget_theme', $_POST['cas_widget_theme']);
        update_option( 'cas_widget_lang', $_POST['cas_widget_lang']);
        update_option( 'cas_widget_lang_id', $_POST['cas_widget_lang_id']);

        // Put an settings updated message on the screen

?>

<div class="updated"><p><?php _e('Settings saved. <strong><a href="' . get_site_url() . '">Visit your site</a></strong> to check your new widget settings.', 'menu-general' ); ?></p></div>
<?php

    }

    // Now display the settings editing screen
    echo '<div class="wrap">';

    // header
    echo "<h2>" . __( 'Casengo Chat Widget options', 'menu-general' ) . "</h2>";

    // settings form
  
    ?>

    <?php
      // Read in existing option value from database
      $opt_type = get_option( 'cas_widget_type' );
      $opt_val = get_option( 'cas_widget_pos' );
      $opt_theme = get_option( 'cas_widget_theme' );
      $opt_lang = get_option( 'cas_widget_lang' );
      $opt_lang_id = get_option( 'cas_widget_lang_id' );
    ?>

<script type="text/javascript">
    function OnSelectionChange(select) {
        var sel = select.options[select.selectedIndex].value;
        toggleChatWindowType(sel);
    }

    function OnSelectionLangChange(select) {
        var sel = select.options[select.selectedIndex].value;
        toggleLanguageType(sel);
    }
    
    function toggleChatWindowType(tp) {
        if(tp === 'inline') {
            document.getElementById('cas_position_of_button').style.display = 'none';
            document.getElementById('cas_color_theme').style.display = 'none';
            document.getElementById('cas_button_label').style.display = 'none';
            document.getElementById('inline_information_bar').style.display = '';
        } else {
            document.getElementById('cas_position_of_button').style.display = '';
            document.getElementById('cas_color_theme').style.display = '';
            document.getElementById('cas_button_label').style.display = '';
            document.getElementById('inline_information_bar').style.display = 'none';
        }
    }
    
    function toggleLanguageType(tp) {
        if(tp === 'custom_lang') {
            document.getElementById('cas_lang_id').style.display = '';
            document.getElementById('cas_widget_lang_id').focus();
        } else {
            document.getElementById('cas_lang_id').style.display = 'none';
        }
    }
</script>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<p>To configure the live chat plugin you must have a Casengo account. Have an account already? Great! If not, <a href="http://get.casengo.com/signup/?ref=wordpress-plugin-admin&amp;utm_source=WordPress&amp;utm_medium=Plugin&amp;utm_campaign=WordPress%2BPlugin%2BSignups" target="_blank" title="Sign up for a free Casengo account" rel="nofollow">sign up here</a>.</p>
<br>
<p><h3><strong><?php _e("Your Casengo subdomain (eg. mycompanyname)", 'menu-test' ); ?></h3></strong>
Enter your subdomain of your Casengo account below. This field is mandatory. If it is not specified, the button will not appear on the site.<br><br>
<table style="margin-left:20px">
<tr>
<td style="width:160px">Subdomain:</td>
<td>
http://<input type="text" name="cas_widget_domain" size="20" style="font-weight: bold" value="<?php echo get_option('cas_widget_domain') ?>">.casengo.com
</td>
</tr>
<tr>
<td style="width:120px">Language:</td>
<td>
<select id="cas_widget_lang" name="<?php echo 'cas_widget_lang'; ?>" style="width:200px" value="" onchange="OnSelectionLangChange(this)">
<option <?php if ($opt_lang === '') echo 'selected="true"' ?> value="">(Default)</option>
<option <?php if ($opt_lang === 'en_US') echo 'selected="true"' ?> value="en_US">English</option>
<option <?php if ($opt_lang === 'nl_NL') echo 'selected="true"' ?> value="nl_NL">Nederlands</option>
<option <?php if ($opt_lang === 'custom_lang') echo 'selected="true"' ?> value="custom_lang">Custom Language ID</option>
</select>
</td>
</tr>
<tr id="cas_lang_id">
<td>Custom language ID:</td>
<td>
<input type="text" name="cas_widget_lang_id" maxlength="8" size="6" value="<?php echo $opt_lang_id; ?>" />
</td>
</tr>
</table>
<p><h3><strong><?php _e("Appearance", 'menu-test' ); ?></strong></h3>
Specify how the chat button appears on your site<br><br>
<table style="margin-left:20px">
<tr id="cas_chat_window_type">
<td style="width:160px">Chat window type:</td>
<td>
<select id="cas_widget_type" name="<?php echo 'cas_widget_type'; ?>" style="width:200px" value="" onchange="OnSelectionChange(this)">
<option <?php if ($opt_type === 'inline' || $opt_type === '' || $opt_val === 'inline') echo 'selected="true"' ?> value="inline">Inline chat widget (default)</option>
<option <?php if ($opt_type === 'popup') echo 'selected="true"' ?> value="popup">Popup window</option>
</select>
</td>
</tr>
<tr id="cas_position_of_button">
<td style="width:160px">Position of button:</td>
<td>
<select id="cas_widget_pos" name="<?php echo 'cas_widget_pos'; ?>" style="width:200px" value="">
<option <?php if ($opt_val === 'middle-left') echo 'selected="true"' ?> value="middle-left">Middle-left</option>
<option <?php if ($opt_val === 'middle-right') echo 'selected="true"' ?> value="middle-right">Middle-right</option>
<option <?php if ($opt_val === 'bottom-right') echo 'selected="true"' ?> value="bottom-right">Bottom-right</option>
</select>
</td>
</tr>
<tr id="cas_color_theme">
<td style="width:160px"><span id="cas_widget_lbl_theme">Color theme:</span></td>
<td>
<select id="cas_widget_theme" name="<?php echo 'cas_widget_theme'; ?>" style="width:200px" value="">
<option <?php if ($opt_theme === 'darkgrey') echo 'selected="true"' ?> value="darkgrey">Dark grey (default)</option>
<option <?php if ($opt_theme === 'lightgrey') echo 'selected="true"' ?> value="lightgrey">Light grey</option>
<option <?php if ($opt_theme === 'white') echo 'selected="true"' ?> value="white">White</option>
<option <?php if ($opt_theme === 'orange') echo 'selected="true"' ?> value="orange">Orange</option>
<option <?php if ($opt_theme === 'blue') echo 'selected="true"' ?> value="blue">Blue</option>
<option <?php if ($opt_theme === 'purple') echo 'selected="true"' ?> value="purple">Purple</option>
<option <?php if ($opt_theme === 'red') echo 'selected="true"' ?> value="red">Red</option>
<option <?php if ($opt_theme === 'green') echo 'selected="true"' ?> value="green">Green</option>
</select>
</td>
</tr>
<tr id="cas_button_label">
<td style="width:160px"><span id="cas_widget_lbl_label">Button label:</span></td>
<td>
<input id="cas_widget_label" type="text" name="cas_widget_label" size="40" value="<?php echo get_option('cas_widget_label') ?>">
</td>
</tr>
<tr id="inline_information_bar"><td></td><td><br>
<strong>To change the appearance (color, position etc.) of the inline chat, click the button below to go to the casengo settings page. (Login required!)</strong><br>
<br><span><a href="http://login.casengo.com/admin/#!/channels/vip/inline" class="button-primary" target="_blank">Customize inline chat</a></span></td></tr>
</table>
<br />
<hr />
<script type="text/javascript">

    function cas_popupwindow() {
        var w = 500;
        var h = 600;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        window.open('//support.casengo.com/vip', 'casengo support', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    }
</script>
if you need help to configure Casengo Live Chat Widget on your website, click here to <a href="#" onclick="cas_popupwindow(); return false;">chat with us</a>.
<p class="submit">

<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>
<script type="text/javascript">
<?php

if($opt_type === 'inline') {
    echo 'toggleChatWindowType(\'inline\');';
} else {
    echo 'toggleChatWindowType(\'popup\');';
}

if($opt_lang === 'custom_lang') {
    echo 'toggleLanguageType(\'custom_lang\');';
} else {
    echo 'toggleLanguageType(\'' . $opt_lang . '\');';
}

?>
</script>

<?php } ?>