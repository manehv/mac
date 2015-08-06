<div class="wrap">
 <style type="text/css">

	input {
    border:1px solid #aaa;
    background: #fff;
}
input:focus {
    background: #fff;
    border:1px solid #555;
    box-shadow: 0px 0px 3px #ccc, 0 10px 15px #eee inset;
    border-radius:2px;
}
label {
width:100%;
   margin-bottom: 18px;
    display:inline-block;
	font-size:12px;
}
p, h2, h3 {
font-family: "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;
}
h3 {
margin-top:0px;
}

.adhs_description_field {
width:470px;
float: left;
margin-right:50px;
margin-bottom:20px;
line-height: 1.5em;
text-align: justify;
}
.adhs_description_field_touch {
width:280px;
float: left;
margin-right:30px;
margin-bottom:-5px;
}

.adhs_description_field span {
font-size:13px;
}

 </style>
    <?php screen_icon(); ?>

	<form action="options.php" method="post" id="<?php echo $plugin_id; ?>_options_form" name="<?php echo $plugin_id; ?>_options_form">

	<?php settings_fields($plugin_id.'_options'); ?>

    <h2><?php _e('ATHS Options &raquo; Settings', 'adhs'); ?></h2>
	<div style="width:510px; height: 275px; background-color: #F2FBFD; margin-left: auto; margin-right: auto; margin-bottom: 20px; margin-top: 20px; padding: 16px; border: 1px solid #B7E9E9;">
	<h3 align="center"><?php _e('Keep in touch with me.', 'adhs'); ?></h3>
		<div style="width:270px; float: left; margin-right:40px;">
			<a href="https://twitter.com/tulipemedia" class="twitter-follow-button" data-show-count="true" data-lang="en" data-size="large">Follow @tulipemedia</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			<br /><br />
			<div id="fb-root"></div>
				<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1&appId=222667281101667";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like-box" data-href="http://www.facebook.com/tulipemedia" data-width="240" data-show-faces="false" data-stream="false" data-header="true"></div>
			<h4 style="margin-bottom:4px;"><?php _e('Let me know that you are using my plugin!', 'adhs'); ?></h4>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://tulipemedia.com/en/add-to-home-screen-wordpress-plugin/" data-lang="en" data-hashtags="iPhone,iPad,Apple,iOS" data-text="Using the Add to home screen #WordPress #plugin by @tulipemedia!">Spread the word!</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

		</div>
		<div style="width:200px; float: left; padding:0">
			<div class="g-person" data-width="180" data-height="225" data-href="//plus.google.com/110058232548204790434" data-rel="author"></div>

			<script type="text/javascript">
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/platform.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
			</script>
		</div>
		
		<div style="display:block; width: 420px; height:40px; margin-top: 5px;">

		</div>
	</div>
    <table class="widefat">
		<thead>
		   <tr>
			 <th><input type="submit" name="submit" value="<?php _e('Save Settings', 'adhs'); ?>" class="button-primary" /></th>
		   </tr>
		</thead>
		<tfoot>
		   <tr>
			 <th><input type="submit" name="submit" value="<?php _e('Save Settings', 'adhs'); ?>" class="button-primary"></th>
		   </tr>
		</tfoot>
		<tbody>
		   <tr>
			 <td style="padding:25px; font-size: 25px;">
			 <h2 style="margin-bottom:15px;"><?php _e('Floating bubble options', 'adhs'); ?></h2>
				 <label for="returningvisitor">
				 <h3><?php _e('Show to returning visitors only', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('Set this to true and the message won\'t be shown the first time one user visits your blog. It can be useful to target only returning visitors and not irritate first time visitors. <i>I recommend to check this value</i>.', 'adhs'); ?></span>
					</div>
					<input type="checkbox" name="returningvisitor" <?php checked( get_option('returningvisitor') == 'on',true); ?> />
                 </label>
                 <label for="message">
				 <h3><?php _e('Custom message', 'adhs'); ?></h3>
                    <div class="adhs_description_field">
						<span><?php _e('Type the custom message that you want appearing in the balloon. You can also display default message in the language of your choice by typing the locale (e.g: en_us).', 'adhs'); ?></span>
						<span><br /><?php _e('<i>Use %device to show user\'s device on message, and %icon to display the add icon.</i>', 'adhs'); ?></span>
					</div>
                    <textarea style="width:380px" rows="3" cols="50" name="message"/><?php echo get_option('message'); ?></textarea>
                 </label>
				 <label for="animationin">
				  <h3><?php _e('Animation in', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('The animation the balloon appears with.', 'adhs'); ?></span>
					</div>
					<select name="animationin" id="animationin">
						<option value="drop"<?php echo selected(get_option('animationin'),drop); ?>>drop</option>
						<option value="bubble"<?php echo selected(get_option('animationin'),bubble); ?>>bubble</option>
						<option value="fade"<?php echo selected(get_option('animationin'),fade); ?>>fade</option>
					</select>
                 </label>
				 <label for="animationout">
					<h3><?php _e('Animation out', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('The animation the balloon exits with.', 'adhs'); ?></span>
					</div>
					<select name="animationout" id="animationout">
						<option value="drop"<?php echo selected(get_option('animationout'),drop); ?>>drop</option>
						<option value="bubble"<?php echo selected(get_option('animationout'),bubble); ?>>bubble</option>
						<option value="fade"<?php echo selected(get_option('animationout'),fade); ?>>fade</option>
					</select>
                 </label>
                 <label for="startdelay">
					<h3><?php _e('Start delay', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('Milliseconds to wait before showing the message. Default: 2000', 'adhs'); ?></span>
					</div>
                     <input type="text" name="startdelay" value="<?php echo get_option('startdelay'); ?>"  />
                 </label>
                 <label for="lifespan">
					<h3><?php _e('Lifespan', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('Milliseconds to wait before hiding the message. Default: 20000', 'adhs'); ?></span>
					</div>
					<input type="text" name="lifespan" value="<?php echo get_option('lifespan'); ?>"  />
                 </label>
                 <label for="bottomoffset">
					<h3><?php _e('Bottom offset', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('Distance in pixels from the bottom (iPhone) or the top (iPad). Default: 14', 'adhs'); ?></span>
                    </div>
					<input type="text" name="bottomoffset" value="<?php echo get_option('bottomoffset'); ?>"  />
                 </label>
                 <label for="expire">
					<h3><?php _e('Expire timeframe', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('Minutes before displaying the message again. Default: 0 (=always show). It\'s highly recommended to set a timeframe in order to prevent showing message at each and every page load for those who didn\'t add the Web App to their homescreen or those who added it but load the blog on Safari!<br /><i>Recommended values: 43200 for one month or 525600 for one year.</i>', 'adhs'); ?></span>
					</div>
                    <input type="text" name="expire" value="<?php echo get_option('expire'); ?>"  />
                 </label>
				 <hr style="color:#F2F3F3; background-color:#F2F3F3">
				 <h2 style="margin-bottom:15px;"><?php _e('iOs touch icons', 'adhs'); ?></h2>
                 <label for="touchicon">
					<h3><?php _e('Touch icon', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('If checked, the script checks for link rel="apple-touch-icon" in the page HEAD and displays the application icon next to the message.', 'adhs'); ?></span>
					</div>
                    <input type="checkbox" name="touchicon" <?php checked( get_option('touchicon') == 'on',true); ?> />
                 </label>
                <label for="aths_touchicon_precomposed">
					<h3><?php _e('Precomposed icons', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('If checked, icons will display without the Apple gloss effect.', 'adhs'); ?></span>
					</div>
                    <input type="checkbox" name="aths_touchicon_precomposed" <?php checked( get_option('aths_touchicon_precomposed') == 'on',true); ?> />
                </label>
				<label style="margin-bottom:-5px;">
				<h3><?php _e('Touch icons URLs', 'adhs'); ?></h3>
				<div class="adhs_description_field">
				<span><?php _e('If mentionned, those fields add <i>link rel="apple-touch-icon"</i> in the page HEAD (convenient for those who have no touch icon). Just paste the URLs of your icons.', 'adhs'); ?></span>
				</div>
				</label>
				<label for="touchicon_url">
					<div class="adhs_description_field_touch">
						<span><?php _e('57x57 touch icon URL (for iPhone 3GS and 2011 iPod Touch).', 'adhs'); ?></span>
					</div>
					<input type="url" size="60" name="touchicon_url" value="<?php echo get_option('touchicon_url'); ?>"  />
                </label>
				<label for="touchicon_url72">
					<div class="adhs_description_field_touch">
						<span><?php _e('72x72 touch icon URL (for 1st generation iPad, iPad 2 and iPad mini).', 'adhs'); ?></span>
					</div>
					<input type="url" size="60" name="touchicon_url72" value="<?php echo get_option('touchicon_url72'); ?>"  />
                </label>
				<label for="touchicon_url114">
					<div class="adhs_description_field_touch">
						<span><?php _e('114x114 touch icon URL (for iPhone 4, 4S, 5 and 2012 iPod Touch).', 'adhs'); ?></span>
					</div>
					<input type="url" size="60" name="touchicon_url114" value="<?php echo get_option('touchicon_url114'); ?>"  />
                </label>
				<label for="touchicon_url144">
					<div class="adhs_description_field_touch">
						<span><?php _e('144x144 touch icon URL (for iPad 3rd and 4th generation).', 'adhs'); ?></span>
					</div>
					<input type="url" size="60" name="touchicon_url144" value="<?php echo get_option('touchicon_url144'); ?>"  />
                </label>
				
				<label for="addmetawebcapabletitle" style="margin-top:15px">
				<h3><?php _e('Title of your Web App', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span class="adhs_description_field"><?php _e('Type the name of your blog (max: 12 characters !). Default: it takes the default title of the page.', 'adhs'); ?></span>
					</div>
					 <input type="text" name="addmetawebcapabletitle" value="<?php echo get_option('addmetawebcapabletitle'); ?>"  />
                </label>
				<label for="pagetarget">
				<h3><?php _e('On which page the balloon should appear?', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span class="adhs_description_field"><?php _e('Keep in mind that if someone adds your blog to home screen from a single article page for instance, the web app will load this page and not the homepage of the blog. That\'s why you could choose to open the floating balloon on homepage only and not on all pages of your blog.', 'adhs'); ?></span>
					</div>
					<select name="pagetarget" id="pagetarget">
						<option value="homeonly"<?php echo selected(get_option('pagetarget'),homeonly); ?>><?php _e('Home only', 'adhs'); ?></option>
						<option value="allpages"<?php echo selected(get_option('pagetarget'),allpages); ?>><?php _e('All pages', 'adhs'); ?></option>
					</select>
                </label>
				 <hr style="color:#F2F3F3; background-color:#F2F3F3">
				 <h2><?php _e('If your theme is non responsive, these options might interest you', 'adhs'); ?></h2>
				 <p style="margin-bottom:26px;"><?php _e('In non-responsive themes, the floating balloon may be to small. These settings will allow you to increase dimensions and appearance.', 'adhs'); ?></p>
				 <label for="zoom_adhs">
				  <h3><?php _e('Zoom of the floating balloon (%)', 'adhs'); ?></h3>
				  <div class="adhs_description_field">
					<span><?php _e('Recommended to leave blank if your theme is responsive design. If not, it is recommended to increase the balloon dimensions by zooming. <i>Recommended value for non-responsive themes: at leat 300</i>', 'adhs'); ?></span>
				  </div>
                  <input type="text" name="zoom_adhs" value="<?php echo get_option('zoom_adhs'); ?>"  />
                 </label>
				 <label for="font_adhs">
				 <h3><?php _e('Font-size of the floating balloon (%)', 'adhs'); ?></h3>
				 <div class="adhs_description_field">
					<span><?php _e('Recommended to leave blank if your theme is responsive design. If not, it is recommended to increase it, especially if you have increased the floating balloon via the zoom field. <i>Recommended value for non-responsive themes: 200</i>', 'adhs'); ?></span>
				 </div>
                 <input type="text" name="font_adhs" value="<?php echo get_option('font_adhs'); ?>"  />
                 </label>
				 <label for="lineheight_adhs">
				 <h3><?php _e('Line-height (%)', 'adhs'); ?></h3>
				 <div class="adhs_description_field">
					<span><?php _e('Recommended to leave blank if your theme is responsive design. Decrease it if you have increased the zoom field. <i>Recommended value for non responsive sites: 120</i>', 'adhs'); ?></span>
				 </div>
                 <input type="text" name="lineheight_adhs" value="<?php echo get_option('lineheight_adhs'); ?>"  />
                 </label>
				<hr style="color:#F2F3F3; background-color:#F2F3F3">
				<h2><?php _e('Navigation options', 'adhs'); ?></h2>
				<p style="margin-bottom:26px;"><?php _e('Basically, you have two ways to browse your web app: on Safari or in a separate window. Each browser has its pros and cons.', 'adhs'); ?></p>
				<label for="browseraths">
				<h3><?php _e('Choose your browser', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span class="adhs_description_field"><?php _e('In Safari mode, your blog will open on Safari browser when you will tap on your icon, but the balloon will continue to appear (we cannot prevent it opening in this mode even if user has alreay added the blog to its homescreen). The solution is to set an important expire timeframe (e.g on year).<br /><br />In the fullscreen mode, the web app will not open in Safari mode but as a separate Web application with a custom nav bottom bar. The pro is that the balloon will never appear again in this mode. The con is that you will not have Safari native options.', 'adhs'); ?></span>
					</div>
					<select name="browseraths" id="browseraths">
						<option value="safarimode"<?php echo selected(get_option('browseraths'),safarimode); ?>>Safari mode</option>
						<option value="fullscreenmode"<?php echo selected(get_option('browseraths'),fullscreenmode); ?>>Fullscreen mode</option>
					</select>
				</label>
				<hr style="color:#F2F3F3; background-color:#F2F3F3">
				<h2><?php _e('Full Screen mode options', 'adhs'); ?></h2>
				<p style="margin-bottom:26px;"><?php _e('Parameter these options if you have chosen Full Screen mode to browse your blog.', 'adhs'); ?></p>
				
				<label style="margin-bottom:-5px;">
					<h3><?php _e('Startup images URLs', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span><?php _e('You can specify startup images that are displayed while your web application launches. Just paste URLs of the images you want to use as startup screens if you don\'t already have ones.<br /><i>Note: these options work only on Full Screen mode</i>.', 'adhs'); ?></span>
					</div>
				</label>
                <label for="touch_startup_url">
					<div class="adhs_description_field_touch">
						<span><?php _e('320x460px (iPhone 3GS, 2011 iPod Touch).', 'adhs'); ?></span>
					</div>
					<input type="url" size="60" name="touch_startup_url" value="<?php echo get_option('touch_startup_url'); ?>"  />
                </label>
                <label for="touch_startup_url920">
					<div class="adhs_description_field_touch">
						<span><?php _e('640x920 (iPhone 4, 4S and 2011 iPod Touch).', 'adhs'); ?></span>
					</div>
					<input type="url" size="60" name="touch_startup_url920" value="<?php echo get_option('touch_startup_url920'); ?>"  />
                </label>
                <label for="touch_startup_url1096">
					<div class="adhs_description_field_touch">
						<span><?php _e('640x1096 (iPhone 5 and 2012 iPod Touch).', 'adhs'); ?></span>
					</div>
					<input type="url" size="60" name="touch_startup_url1096" value="<?php echo get_option('touch_startup_url1096'); ?>"  />
                </label>
                <label for="touch_startup_url748">
					<div class="adhs_description_field_touch">
						<span><?php _e('1024x748 (iPad landscape).', 'adhs'); ?></span>
					</div>
					<input type="url" size="60" name="touch_startup_url748" value="<?php echo get_option('touch_startup_url748'); ?>"  />
                </label>
                <label for="touch_startup_url1004">
					<div class="adhs_description_field_touch">
						<span><?php _e('768x1004 (iPad Portrait).', 'adhs'); ?></span>
					</div>
					<input type="url" size="60" name="touch_startup_url1004" value="<?php echo get_option('touch_startup_url1004'); ?>"  />
                </label>
				
				<label for="addmetawebcapablelinks" style="margin-top:15px">
				<h3><?php _e('Force links to switch to Safari browser.', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span class="adhs_description_field"><?php _e('If checked, clicking on links from Fullscreen mode will switch from Web App to Safari browser.', 'adhs'); ?></span>
					</div>
					<input type="checkbox" name="addmetawebcapablelinks" <?php checked( get_option('addmetawebcapablelinks') == 'on',true); ?> />
                </label>
				<label for="webappnavbar">
				<h3><?php _e('Disable bottom nav bar of the Fullscreen mode', 'adhs'); ?></h3>
					<div class="adhs_description_field">
						<span class="adhs_description_field"><?php _e('If checked, the bottom nav bar of the Fullscreen mode that provides a way for visitors to go back, forward or reload will be disabled. It could be useful if you need to load a "real" fullscreen view of your website, without any buttons.', 'adhs'); ?></span>
					</div>
					<input type="checkbox" name="webappnavbar" <?php checked( get_option('webappnavbar') == 'on',true); ?> />
                </label>
             </td>
		   </tr>
		</tbody>
	</table>

	</form>

</div>