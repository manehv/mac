<?php

	require_once(dirname(__FILE__).'/wa-includes/class-wiziapp-magazine-touch-theme-controller-admin.php');
	require_once(dirname(__FILE__).'/wa-includes/class-wiziapp-magazine-touch-theme-settings.php');

	if (!is_admin() )
	{
		// They are not loaded in the admin display
		add_action('wp_head', 'wiziapp_magazine_touch_parent_styles', 6);
		add_action('wp_head', 'wiziapp_magazine_touch_javascript');
		add_action('wiziapp_theme_customized_style', 'wiziapp_magazine_touch_theme_customized_style');
		add_filter('wiziapp_theme_header_title', 'wiziapp_magazine_touch_theme_header_title');
		add_action('wiziapp_theme_search_button', 'wiziapp_magazine_touch_search_button_print');
		add_filter('wiziapp_theme_menu_button_class', 'wiziapp_magazine_touch_menu_button_class');
		add_filter('wiziapp_theme_post_featured', 'wiziapp_magazine_touch_post_featured');
		add_filter('wiziapp_theme_comments_title', 'wiziapp_magazine_touch_comments_title');
		add_filter('wiziapp_theme_categories_title', 'wiziapp_magazine_touch_categories_title');
		add_filter('wiziapp_theme_tags_title', 'wiziapp_magazine_touch_tags_title');
	}

	function wiziapp_magazine_touch_parent_styles()
	{
		global $wp_locale;

		// Loads parent stylesheet.
		$stylesheets = array();
		$stylesheets[] = get_template_directory_uri().'/style.css';

		// Load localized stylesheet
		$stylesheet_dir_uri = get_template_directory_uri();
		$dir = get_template_directory();
		$locale = get_locale();
		if (file_exists("$dir/$locale.css"))
		{
			$stylesheet_uri = "$stylesheet_dir_uri/$locale.css";
		}
		elseif (!empty($wp_locale->text_direction) && file_exists("$dir/{$wp_locale->text_direction}.css"))
		{
			$stylesheet_uri = "$stylesheet_dir_uri/{$wp_locale->text_direction}.css";
		}
		else
		{
			$stylesheet_uri = '';
		}
		$stylesheets[] = apply_filters('locale_stylesheet_uri', $stylesheet_uri, $stylesheet_dir_uri);
		foreach ($stylesheets as $stylesheet)
		{
			if ($stylesheet)
			{
				echo '<link rel="stylesheet" href="' . $stylesheet . '" type="text/css" media="screen" />'.PHP_EOL;
			}
		}
	}

	function wiziapp_magazine_touch_javascript()
	{
?>
		<script type="text/javascript">
			(function($, w, d, undef) {
				$(d).bind("pageshow", function(e) {
					$("div.wiziapp-customized-background:has(ul.wiziapp-content-post-list)").css("background", "#cfe1e1");
					$(":jqmData(role='page'):has(ul.wiziapp-content-page-list), :jqmData(role='page'):has(ul.wiziapp-content-category-list), :jqmData(role='page'):has(ul.wiziapp-content-tag-list), :jqmData(role='page'):has(ul.wiziapp-content-bookmark-list)")
					.css("background", "#282828");
				});
			})(jQuery, window, document);
		</script>
<?php
	}

	function wiziapp_magazine_touch_theme_header_title($title)
	{
		if (wiziapp_magazine_touch_theme_settings()->getAppHeaderType() !== 'text')
		{
			return '';
		}
		$custom_title = wiziapp_magazine_touch_theme_settings()->getAppHeaderTitle();
		return empty($custom_title)?$title:$custom_title;
	}

	function wiziapp_magazine_touch_theme_customized_style()
	{
		if (wiziapp_magazine_touch_theme_settings()->getAppHeaderType() === 'text')
		{
			$wiziapp_header_background = wiziapp_magazine_touch_theme_settings()->getAppHeaderBackground();
		}
		else if (wiziapp_magazine_touch_theme_settings()->getAppHeaderType() === 'image')
		{
			$wiziapp_header_background = 'url('.esc_html(wiziapp_magazine_touch_theme_settings()->getAppHeaderImage()).') no-repeat center center';
		}

?>
		<style type="text/css">
			div.wiziapp-header {
				color: <?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppHeaderColor()); ?>;
				background: <?php echo esc_html($wiziapp_header_background); ?>;
			}

			div.wiziapp-customized-background {
				background: <?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentEndBackground()); ?>;
			}

			div.wiziapp-content-post, div.wiziapp-content-page {
				color: <?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentColor()); ?>;
			}

			.wiziapp-content-list li {
				background: #eee;
				background-image: -webkit-gradient(linear, left top, left bottom, from(<?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentStartBackground()); ?>), to(<?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentEndBackground()); ?>));
				background-image: -webkit-linear-gradient(<?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentStartBackground()); ?>, <?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentEndBackground()); ?>);
				background-image:    -moz-linear-gradient(<?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentStartBackground()); ?>, <?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentEndBackground()); ?>);
				background-image:     -ms-linear-gradient(<?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentStartBackground()); ?>, <?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentEndBackground()); ?>);
				background-image:      -o-linear-gradient(<?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentStartBackground()); ?>, <?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentEndBackground()); ?>);
				background-image:         linear-gradient(<?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentStartBackground()); ?>, <?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentEndBackground()); ?>);
			}

			a.wiziapp-content-list-item {
				color: <?php echo esc_html(wiziapp_magazine_touch_theme_settings()->getAppContentColor()); ?> !important;
			}
		</style>
<?php
	}

	function wiziapp_magazine_touch_comments_title($comments_title)
	{
		return '% Comments';
	}

	function wiziapp_magazine_touch_categories_title($categories_title)
	{
		return 'Categories';
	}

	function wiziapp_magazine_touch_tags_title($tags_title)
	{
		return 'Tags';
	}

	function wiziapp_magazine_touch_menu_button_class($class)
	{
		return 'ui-btn-left wiziapp_magazine_touch_menu';
	}

	function wiziapp_magazine_touch_post_featured($featured)
	{
		return 'wiziapp-post-not-featured';
	}

	function wiziapp_magazine_touch_search_button_print($url)
	{
?>
		<a href="<?php echo $url; ?>" data-icon="search" data-iconpos="notext" data-role="button" class="ui-btn-right">Search</a>
<?php
	}
