<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width" />
		<title><?php
			/*
			 * Print the <title> tag based on what is being viewed.
			 */

			wp_title( '|', true, 'right' );
		?></title>
<?php
	$app_icon = wiziapp_theme_is_in_plugin()?false:wiziapp_theme_settings()->getAppIcon();
	if (!empty($app_icon))
	{
?>
		<link rel="shortcut icon" href="<?php echo esc_html($app_icon); ?>" />
		<link rel="apple-touch-icon" href="<?php echo esc_html($app_icon); ?>" />
<?php
	}
?>
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
	if (wiziapp_theme_is_in_plugin() && wiziapp_plugin_settings()->getAnalytics() !== '')
	{
?>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', <?php echo json_encode(wiziapp_plugin_settings()->getAnalytics()); ?>]);
			jQuery(document).delegate("[data-role=page]", "pageshow", function() {
				var hash = document.location.hash;

				if (hash) {
					_gaq.push(['_trackPageview', hash.substr(1)]);
				}
				else {
					_gaq.push(['_trackPageview']);
				}
			});

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' === document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
<?php
	}
	if (wiziapp_theme_is_in_plugin() && wiziapp_plugin_settings()->getAdsenseClient() && wiziapp_plugin_settings()->getAdsenseSlot() && !wiziapp_plugin_module_switcher()->getExtra('no_ads'))
	{
?>
		<script type="text/javascript">
			(function($, d) {
				var html = <?php
					echo json_encode('<div style="margin:0 -15px"><ins class="adsbygoogle" style="display:block;width:320px;height:50px;margin:0 auto" data-ad-client="'.
						esc_attr(wiziapp_plugin_settings()->getAdsenseClient()).'" data-ad-slot="'.esc_attr(wiziapp_plugin_settings()->getAdsenseSlot()).'"></ins></div>');
				?>;
				$(d).bind("pagebeforechange", function(e, data) {
					if (!data.toPage || !data.toPage.is || !data.toPage.is("[data-role=page]")) {
						return;
					}
					if (data.options && data.options.fromPage && data.options.fromPage.is && data.options.fromPage.is(data.toPage)) {
						return;
					}
					var page = data.toPage;
					var add = $(html).add(html);
					$("google_persistent_state google_persistent_state_async".split(" ")).each(function() {
						if (window[this] && window[this].S) {
							window[this].S.google_prev_ad_formats_by_region = {};
							window[this].S.google_num_ad_slots = 0;
						}
					});
					var con = page.find(".wiziapp-content-post .wiziapp-post-content");
					if (con.length) {
						con.prepend(add.eq(0)).append(add.eq(1));
						(adsbygoogle = window.adsbygoogle || []).push({element: add.eq(0).find("ins").get(0)});
						(adsbygoogle = window.adsbygoogle || []).push({element: add.eq(1).find("ins").get(0)});
					}
					con = null;

					page.one("pagehide", function() {
						add.remove();
					});
				});
			})(jQuery, document);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = document.location.protocol+'//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
<?php
	}
?>
	</head>
	<body <?php body_class(); ?>>
		<div data-role="page" data-url="<?php echo esc_html($_SERVER['REQUEST_URI']); ?>">
<?php
	$wiziapp_theme_header_class = 'wiziapp-header';
	$wiziapp_theme_back_url = '';
	$wiziapp_theme_current_url = untrailingslashit(preg_replace('/[\?&]androidapp=1/i', '', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	$is_wiziapp_theme_back_button = $wiziapp_theme_current_url !== str_replace(array('http://', 'https://'), '', untrailingslashit(get_bloginfo('url')));
	if ($is_wiziapp_theme_back_button && get_query_var('wiziapp_theme_mainpage'))
	{
		$is_wiziapp_theme_back_button = false;
	}
	if ($is_wiziapp_theme_back_button && wiziapp_theme_is_in_plugin())
	{
		$is_wiziapp_theme_back_button = !in_array($wiziapp_theme_current_url, wiziapp_plugin_menus()->get_menu_urls());
	}
	if ($is_wiziapp_theme_back_button)
	{
		$wiziapp_theme_back_url = wiziapp_theme_settings()->getBackUrl();
		$wiziapp_theme_header_class .= ' wiziapp-header-has-back';
	}
	do_action_ref_array('wiziapp_theme_back_button', array(&$wiziapp_theme_header_class, &$wiziapp_theme_back_url))
?>
			<div data-role="header" data-id="header" data-position="fixed" data-tap-toggle="false" class="<?php echo apply_filters('wiziapp_theme_header_class', $wiziapp_theme_header_class); ?>">
<?php
	do_action('wiziapp_theme_header_app_icon');
?>
				<a href="<?php echo esc_attr($wiziapp_theme_back_url); ?>" class="wiziapp-back-button" data-role="button" data-icon="back" data-transition="slide" data-direction="reverse">
					<?php _e('Back', 'wiziapp-smooth-touch'); ?>
				</a>
<?php
	switch (wiziapp_theme_settings()->getMenuType())
	{
		case 'popup':
		case 'panel':
?>
				<a href="#left-panel" data-icon="bars" data-iconpos="notext" data-role="button" class="<?php echo apply_filters('wiziapp_theme_menu_button_class', 'ui-btn-right'); ?>">
					<?php _e('Menu', 'wiziapp-smooth-touch'); ?>
				</a>
<?php
			break;
	}
?>
				<h1><?php echo apply_filters('wiziapp_theme_header_title', esc_html(get_bloginfo('name'))); ?></h1>
<?php
	do_action('wiziapp_theme_search_button', wiziapp_theme_settings()->getSearchUrl());
?>
			</div>
			<div <?php echo ((is_object(get_query_var('wiziapp_theme_comments')))?'':'class="wiziapp-customized-background"'); ?> data-role="content">
