<?php

	require_once(dirname(__FILE__).'/wa-includes/class-wiziapp-theme-archive-months-query.php');
	require_once(dirname(__FILE__).'/wa-includes/class-wiziapp-theme-archive-years-query.php');
	require_once(dirname(__FILE__).'/wa-includes/class-wiziapp-theme-bookmarks-query.php');
	require_once(dirname(__FILE__).'/wa-includes/class-wiziapp-theme-comments-query.php');
	require_once(dirname(__FILE__).'/wa-includes/class-wiziapp-theme-controller-admin.php');
	require_once(dirname(__FILE__).'/wa-includes/class-wiziapp-theme-settings.php');
	require_once(dirname(__FILE__).'/wa-includes/class-wiziapp-theme-taxonomy-query.php');
	if (!wiziapp_theme_is_in_plugin())
	{
		require_once(dirname(__FILE__).'/wa-includes/tgm-plugin-activation/tgm-plugin-activation.php');
		require_once(dirname(__FILE__).'/wa-includes/tgm-plugin-activation/tgmpa-list-table.php');
		require_once(dirname(__FILE__).'/wa-includes/tgm-plugin-activation/tgm-bulk-installer.php');
		require_once(dirname(__FILE__).'/wa-includes/tgm-plugin-activation/tgm-bulk-installer-skin.php');
	}

	add_action('after_setup_theme', 'wiziapp_theme_setup');
	add_action('widgets_init', 'wiziapp_widgets_init');
	if (!is_admin() )
	{
		// They are not loaded in the admin display
		add_action('wp_enqueue_scripts', 'wiziapp_styles_scripts');
		add_action('wp_enqueue_scripts', 'wiziapp_theme_check_compatibility_scripts');
		add_action('wp_head', 'wiziapp_theme_check_compatibility_head', 15);
		add_action('get_header', 'wiziapp_theme_check_compatibility_header');
	}
	if (!wiziapp_theme_is_in_plugin())
	{
		add_action('tgmpa_register', 'wiziapp_register_required_plugins');
	}
	add_filter('request', 'wiziapp_theme_request');
	add_filter('query_vars', 'wiziapp_theme_query_vars');
	add_filter('show_admin_bar', 'wiziapp_theme_show_admin_bar');
	add_filter('wp_title', 'wiziapp_theme_wp_title', 10, 2);
 	add_filter('wp_nav_menu_items', 'wiziapp_theme_add_login_item');

	/**
	 * Sets up the content width value based on the theme's design and stylesheet.
	 */
	if ( ! isset( $content_width ) )
	{
		$content_width = 960;
	}

	/**
	 * Theme initialization function
	 */

	function wiziapp_theme_setup()
	{
		load_theme_textdomain('wiziapp-smooth-touch', get_template_directory() . '/languages');

		if (!wiziapp_theme_is_in_plugin() || !isset($GLOBALS['pagenow']) || $GLOBALS['pagenow'] !== 'customize.php')
		{
			register_nav_menu('wiziapp_custom', 'The Wiziapp Menu');
		}

		add_theme_support('post-thumbnails');
		// Adds RSS feed links to <head> for posts and comments.
		add_theme_support('automatic-feed-links');

		$fp = wiziapp_theme_settings()->getFrontPage();
		if ($fp)
		{
			$fp = preg_split('!::!', $fp, 3);
			if (count($fp) > 2 && $fp[1] === 'page')
			{
				// Fake frontpage
				add_filter('pre_option_show_on_front', 'wiziapp_theme_show_on_front');
				add_filter('pre_option_page_on_front', 'wiziapp_theme_page_on_front');
			}
		}
	}

	/**
	 * Styles and scripts initialization function
	 */

	function wiziapp_styles_scripts()
	{
		// Loads WiziApp - Smooth Touch theme main stylesheet.
		wp_enqueue_style('wiziapp-theme-style', get_stylesheet_uri());

		wp_register_script('jquery-mobile', get_template_directory_uri() . '/scripts/jquery.mobile-1.3.1.js', array('jquery', 'jquery-ui-widget'), '1.3.1');
		wp_register_style('jquery-mobile', get_template_directory_uri() . '/style/jquery.mobile.structure-1.3.0.min.css', array(), '1.3.0');
		wp_register_script('wiziapp-theme', get_template_directory_uri() . '/scripts/wiziapp-theme.js', array('jquery-mobile'));

		wp_enqueue_script('jquery-mobile');
		wp_enqueue_style('jquery-mobile');
		wp_enqueue_script('wiziapp-theme');

		if (is_singular())
		{
			// Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use).
			// @todo This WP feature is not implemented yet
			wp_enqueue_script('comment-reply');
		}
	}

	/**
	 * Add theme-specific query vars for identifying theme-specific pages
	 *
	 * @param array $qvars The current list of supported query vars
	 */

	function wiziapp_theme_query_vars($qvars)
	{
		$qvars[] = 'wiziapp_display';
		$qvars[] = 'wiziapp_back';
		$qvars[] = 'wiziapp_comment';
		return $qvars;
	}

	/**
	 * Handler for the theme-specific pages
	 *
	 * @param array $query_vars The current query
	 */

	function wiziapp_theme_request($query_vars)
	{
		$paged = (isset($query_vars['paged']) && $query_vars['paged'])?$query_vars['paged']:1;
		$comment_id = isset($query_vars['wiziapp_comment'])?$query_vars['wiziapp_comment']:0;
		$display = isset($query_vars['wiziapp_display'])?$query_vars['wiziapp_display']:'';
		$paged_set = isset($query_vars['paged']);
		unset($query_vars['paged']);
		unset($query_vars['wiziapp_comment']);
		unset($query_vars['wiziapp_display']);
		$temp_query = new WP_Query();
		$temp_query->query($query_vars);
		if ($display == 'subpages')
		{
			// Query for a page, then rewrite the query as a query for the subpages
			$q = wiziapp_theme_array_select($query_vars, array('order', 'orderby'));
			if ($paged_set)
			{
				$q['paged'] = $paged;
			}
			if ($temp_query->is_page())
			{
				$q['post_parent'] = $temp_query->get_queried_object_id();
				$q['post_type'] = 'page';
				if (empty($q['orderby']))
				{
					$q['orderby'] = 'name';
					if (empty($q['order']) || ((strtoupper($q['order']) != 'ASC') && (strtoupper($q['order']) != 'DESC')))
					{
						$q['order'] = 'ASC';
					}
				}
				return $q;
			}
		}
		if ($temp_query->is_front_page() || ($temp_query->is_home() && 'page' == get_option( 'show_on_front') && !get_option( 'page_on_front' )))
		{
			switch ($display)
			{
				case 'search':
					$query_vars['wiziapp_theme_search'] = 1;
					$query_vars['wiziapp_theme_mainpage'] = true;
					return $query_vars;
				case 'bookmarks':
					$new_query = new WiziappThemeBookmarksQuery();
					$new_query->query(array('bookmarks_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged));
					$more_link = get_pagenum_link($paged+1);
					return array('wiziapp_theme_bookmarks' => $new_query, 'wiziapp_theme_more_link' => $more_link, 'wiziapp_theme_mainpage' => true);
				case 'latest':
					$order = isset($query_vars['order'])?array('order' => $query_vars['order']):array();
					$orderby = isset($query_vars['orderby'])?array('orderby' => $query_vars['orderby']):array();
					return array(
						'post_type' => 'post',
						'paged' => $paged,
						'wiziapp_theme_mainpage' => true
					)+$order+$orderby;
				case 'pages':
					$order = isset($query_vars['order'])?array('order' => $query_vars['order']):array();
					$orderby = isset($query_vars['orderby'])?$query_vars['orderby']:false;
					if (!$orderby)
					{
						$orderby = 'name';
						if (empty($order) || ((strtoupper($order['order']) != 'ASC') && (strtoupper($order['order']) != 'DESC')))
						{
							$order = array('order' => 'ASC');
						}
					}
					return array(
						'post_type' => 'page',
						'post_parent' => 0,
						'paged' => $paged,
						'orderby' => $orderby,
						'posts_per_page' => wiziapp_theme_settings()->getItemsPerPage(),
						'wiziapp_theme_mainpage' => true
					)+$order;
				case 'categories':
					$new_query = new WiziappThemeTaxonomyQuery();
					$new_query->query(array('parent' => 0, 'terms_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged));
					$more_link = get_pagenum_link($paged+1);
					return array('wiziapp_theme_terms' => $new_query, 'wiziapp_theme_more_link' => $more_link, 'wiziapp_theme_mainpage' => true);
				case 'tags':
					$new_query = new WiziappThemeTaxonomyQuery();
					$new_query->query(array('parent' => 0, 'terms_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged, 'type' => 'post_tag'));
					$more_link = get_pagenum_link($paged+1);
					return array('wiziapp_theme_terms' => $new_query, 'wiziapp_theme_more_link' => $more_link, 'wiziapp_theme_mainpage' => true);
				case 'archive_months':
					$new_query = new WiziappThemeArchiveMonthsQuery();
					$new_query->query(array('archives_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged));
					$more_link = get_pagenum_link($paged+1);
					return array('wiziapp_theme_archive_months' => $new_query, 'wiziapp_theme_more_link' => $more_link, 'wiziapp_theme_mainpage' => true);
				case 'archive_years':
					$new_query = new WiziappThemeArchiveYearsQuery();
					$new_query->query(array('archives_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged));
					$more_link = get_pagenum_link($paged+1);
					return array('wiziapp_theme_archive_years' => $new_query, 'wiziapp_theme_more_link' => $more_link, 'wiziapp_theme_mainpage' => true);
				case '':
					if ($comment_id)
					{
						break;
					}
					$fp = wiziapp_theme_settings()->getFrontPage();
					if (!$fp)
					{
						break;
					}
					$fp = preg_split('!::!', $fp, 3);
					if (count($fp) < 2)
					{
						break;
					}
					if ($fp[0] === 'added')
					{
						if ($paged_set)
						{
							$query_vars['paged'] = $paged;
						}
						return apply_filters('wiziapp_theme_special_frontpage_request', $fp[1], $query_vars);
					}
					if ($fp[0] === 'tax')
					{
						if (count($fp) < 3)
						{
							$new_query = new WiziappThemeTaxonomyQuery();
							$new_query->query(array('parent' => 0, 'terms_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged, 'type' => $fp[1]));
							$more_link = get_pagenum_link($paged+1);
							return array('wiziapp_theme_terms' => $new_query, 'wiziapp_theme_more_link' => $more_link);
						}
						else
						{
							if (empty($query_vars['tax_query']) || !is_array($query_vars['tax_query']))
							{
								$query_vars['tax_query'] = array();
							}
							$query_vars['tax_query'][] = array(
								'taxonomy' => $fp[1],
								'terms' => array($fp[2]),
								'field' => 'id',
							);
							if ($paged_set)
							{
								$query_vars['paged'] = $paged;
							}
							return $query_vars;
						}
					}
					if (count($fp) < 3)
					{
						$query_vars['post_type'] = $fp[1];
						if ($paged_set)
						{
							$query_vars['paged'] = $paged;
						}
						return $query_vars;
					}
					if ($fp[1] === 'page')
					{
						// Special case for pages, since Wordpress also treats pages as special
						$query_vars['page_id'] = $fp[2];
					}
					else
					{
						$query_vars['post_type'] = $fp[1];
						$query_vars['p'] = $fp[2];
					}
					if ($paged_set)
					{
						$query_vars['paged'] = $paged;
					}
					return $query_vars;
			}
			if ($comment_id)
			{
				if ($display == 'comment')
				{
					$comment = get_comment($comment_id);
					$wiziapp_back = isset($query_vars['wiziapp_back'])?$query_vars['wiziapp_back']:false;
					$query_vars = array('wiziapp_theme_comment_post' => $comment->comment_post_ID, 'wiziapp_theme_comment_parent' => $comment->comment_ID);
					if ($wiziapp_back !== false)
					{
						$query_vars['wiziapp_back'] = $wiziapp_back;
					}
					return $query_vars;
				}
				else
				{
					$comment = new WiziappThemeCommentsQuery();
					$comment->set(array(get_comment($comment_id)));
					$comment->theComment();
					$new_query = new WiziappThemeCommentsQuery();
					$new_query->query(array('parent' => $comment_id, 'order' => get_option('comment_order'), 'comments_per_page' => get_option('comments_per_page'), 'page' => $paged));
					$more_link = get_pagenum_link($paged+1);
					$wiziapp_back = isset($query_vars['wiziapp_back'])?$query_vars['wiziapp_back']:false;
					$query_vars = array('wiziapp_theme_comment' => $comment, 'wiziapp_theme_comments' => $new_query, 'wiziapp_theme_more_link' => $more_link);
					if ($wiziapp_back !== false)
					{
						$query_vars['wiziapp_back'] = $wiziapp_back;
					}
					return $query_vars;
				}
			}
		}
		if ($temp_query->is_single())
		{
			switch ($display)
			{
				case 'categories':
					$new_query = new WiziappThemeTaxonomyQuery();
					$new_query->query(array('post' => $temp_query->get_queried_object_id(), 'terms_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged));
					$more_link = get_pagenum_link($paged+1);
					return array('wiziapp_theme_terms' => $new_query, 'wiziapp_theme_more_link' => $more_link);
				case 'tags':
					$new_query = new WiziappThemeTaxonomyQuery();
					$new_query->query(array('post' => $temp_query->get_queried_object_id(), 'terms_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged, 'type' => 'post_tag'));
					$more_link = get_pagenum_link($paged+1);
					return array('wiziapp_theme_terms' => $new_query, 'wiziapp_theme_more_link' => $more_link);
			}
		}
		if ($temp_query->is_page() || $temp_query->is_single())
		{
			switch ($display)
			{
				case 'comments':
					$post_id = $temp_query->get_queried_object_id();
					$new_query = new WiziappThemeCommentsQuery();
					$new_query->query(array('post' => $temp_query->get_queried_object_id(), 'threaded' => get_option('thread_comments'), 'order' => get_option('comment_order'), 'comments_per_page' => get_option('comments_per_page'), 'page' => isset($query_vars['cpage'])?$query_vars['cpage']:false));
					$more_link = wiziapp_theme_get_comments_pagenum_link($post_id, (isset($query_vars['cpage'])?$query_vars['cpage']:1)+1);
					$comment_link = false;
					if (comments_open($post_id))
					{
						$comment_link = add_query_arg('wiziapp_display', 'comment', get_permalink($post_id));
					}
					$wiziapp_back = isset($query_vars['wiziapp_back'])?$query_vars['wiziapp_back']:false;
					$query_vars = array('wiziapp_theme_comments' => $new_query, 'wiziapp_theme_more_link' => $more_link);
					if ($wiziapp_back !== false)
					{
						$query_vars['wiziapp_back'] = $wiziapp_back;
					}
					if ($comment_link !== false)
					{
						$query_vars['wiziapp_theme_comment_link'] = $comment_link;
					}
					return $query_vars;
				case 'comment':
					$post_id = $temp_query->get_queried_object_id();
					$wiziapp_back = isset($query_vars['wiziapp_back'])?$query_vars['wiziapp_back']:false;
					$query_vars = array('wiziapp_theme_comment_post' => $post_id, 'wiziapp_theme_comment_parent' => 0);
					if ($wiziapp_back !== false)
					{
						$query_vars['wiziapp_back'] = $wiziapp_back;
					}
					return $query_vars;
			}
		}
		if ($temp_query->is_category() || $temp_query->is_tag())
		{
			$new_query = new WiziappThemeTaxonomyQuery();
			if ($temp_query->is_tag())
			{
				$term_disp = 'tags';
				$new_query->query(array('parent' => $temp_query->get_queried_object_id(), 'terms_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged, 'type' => 'post_tag'));
			}
			else
			{
				$term_disp = 'categories';
				$new_query->query(array('parent' => $temp_query->get_queried_object_id(), 'terms_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged));
			}
			$more_link = add_query_arg('wiziapp_display', $term_disp, get_pagenum_link($paged+1), '');
			if ($display == $term_disp || !$temp_query->have_posts())
			{
				return array('wiziapp_theme_terms' => $new_query, 'wiziapp_theme_more_link' => $more_link);
			}
			else
			{
				$query_vars['wiziapp_theme_sub_terms'] = $new_query;
				$query_vars['wiziapp_theme_more_link'] = $more_link;
				if ($paged_set)
				{
					$query_vars['paged'] = $paged;
				}
				return $query_vars;
			}
		}
		if ($temp_query->is_year() && $display == 'archive_months')
		{
			$new_query = new WiziappThemeArchiveMonthsQuery();
			$new_query->query(array('year' => $temp_query->get('year'), 'archives_per_page' => wiziapp_theme_settings()->getItemsPerPage(), 'page' => $paged));
			$more_link = get_pagenum_link($paged+1);
			return array('wiziapp_theme_archive_months' => $new_query, 'wiziapp_theme_more_link' => $more_link);
		}
		if ($paged_set)
		{
			$query_vars['paged'] = $paged;
		}
		return $query_vars;
	}

	/**
	 * Prevent admin bar display
	 */

	function wiziapp_theme_show_admin_bar()
	{
		return false;
	}

	/**
	 * Returns whether there are more post beyond those provided by The Loop
	 *
	 * @return boolean If there are more posts then true, otherwise false
	 */

	function wiziapp_theme_have_more()
	{
		$nopaging = get_query_var('nopaging');
		if (!empty($nopaging))
		{
			return false;
		}
		$page = (int) get_query_var('paged');
		if ($page < 1)
		{
			$page = 1;
		}
		return ($GLOBALS['wp_query']->max_num_pages > $page);
	}

	/**
	 * Returns the URL to an image usable as a post thumbnail, if one exists
	 *
	 * @param string|array $size The desired size of the image
	 * @return string|false The thumbnail, if found, or false, if none found
	 */

	function wiziapp_theme_get_post_thumbnail($size)
	{
		global $post;

		$thumb_id = get_post_thumbnail_id();
		if ($thumb_id)
		{
			$img_src = wp_get_attachment_image_src($thumb_id, $size);
			return $img_src[0];
		}

		$allimages = get_children('post_type=attachment&post_mime_type=image&post_parent=' . $post->ID);
		foreach ($allimages as $img)
		{
			$img_src = wp_get_attachment_image_src($img->ID, $size);
			return $img_src[0];
		}

		$text = $post->post_content;

		if (preg_match('/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'>]*)/i', $text, $matches))
		{
			return $matches[1];
		}

		if (preg_match("/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $text, $matches))
		{
			return "http://i.ytimg.com/vi/{$matches[3]}/0.jpg";
		}
		if (preg_match("/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/(v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $text, $matches))
		{
			return $imageurl = "http://i.ytimg.com/vi/{$matches[3]}/0.jpg";
		}
		if (preg_match("/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/(embed\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $text, $matches))
		{
			return $imageurl = "http://i.ytimg.com/vi/{$matches[3]}/0.jpg";
		}
		if (preg_match("/([a-zA-Z0-9\-\_]+\.|)youtu\.be\/([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $text, $matches))
		{
			return $imageurl = "http://i.ytimg.com/vi/{$matches[2]}/0.jpg";
		}

		return false;
	}

	/**
	 * Prints the number of comments for the current post
	 */

	function wiziapp_theme_the_post_comment_count()
	{
		global $post;
		echo $post->comment_count;
	}

	/**
	 * Returns a link to a specific page in the comments list of a post
	 *
	 * @param int $post The post
	 * @param int $pagenum The page
	 */

	function wiziapp_theme_get_comments_pagenum_link($post, $pagenum)
	{
		global $wp_rewrite;

		$pagenum = (int) $pagenum;

		$result = get_permalink($post);

		if ( $wp_rewrite->using_permalinks() )
			$result = user_trailingslashit( trailingslashit($result) . 'comment-page-' . $pagenum, 'commentpaged');
		else
			$result = add_query_arg( 'cpage', $pagenum, $result );

		$result = add_query_arg( 'wiziapp_display', 'comments', $result );

		$result = apply_filters('wiziapp_theme_get_comments_pagenum_link', $result);

		return $result;
	}

	/**
	 * Returns an array containing values based on keys selected from an array
	 *
	 * @param array $bucket The original array containing all keys and values
	 * @param array $keys The keys selected from the original array
	 */

	function wiziapp_theme_array_select($bucket, $keys)
	{
		$ret = array();
		foreach ($keys as $key)
		{
			if (isset($bucket[$key]))
			{
				$ret[$key] = $bucket[$key];
			}
		}
		return $ret;
	}

	/**
	 * @todo This WP feature is not implemented yet
	 */

	function wiziapp_widgets_init()
	{
		register_sidebar();
	}

	function wiziapp_theme_wp_title($title)
	{
		return bloginfo( 'name' ).( empty($title)?'':' | ' ).$title;
	}

	function wiziapp_theme_is_in_plugin()
	{
		if (!function_exists('wiziapp_plugin_module_switcher'))
		{
			return false;
		}
		return wiziapp_plugin_module_switcher()->theme_root() === get_theme_root();
	}

	function wiziapp_theme_show_on_front()
	{
		return 'page';
	}

	function wiziapp_theme_page_on_front()
	{
		$fp = wiziapp_theme_settings()->getFrontPage();
		$fp = preg_split('!::!', $fp, 3);
		return $fp[2];
	}

	function wiziapp_register_required_plugins() {

		/**
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
			// This is an example of how to include a plugin pre-packaged with a theme.
			array(
				// The plugin name
				'name'     				=> 'Wiziapp',
				// The plugin slug (typically the folder name)
				'slug'     				=> 'wiziapp-create-your-own-native-iphone-app',
				// If false, the plugin is only 'recommended' instead of required
				'required' 				=> false,
			),

			/*
			// This is an example of how to include a plugin from the WordPress Plugin Repository
			array(
			// The plugin name
			'name' 		=> 'BuddyPress',
			// The plugin slug (typically the folder name)
			'slug' 		=> 'buddypress',
			// If false, the plugin is only 'recommended' instead of required
			'required' 	=> false,
			),
			*/
		);

		// The theme text domain, used for internationalising strings
		$theme_text_domain = 'wiziapp-smooth-touch';

		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain, leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the end of each line for what each argument will be.
		 */
		$config = array(
			'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
			'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
			'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
			'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
			'menu'         		=> 'install-required-plugins', 	// Menu slug
			'has_notices'      	=> true,                       	// Show admin notices or not
			'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
			'message' 			=> '',							// Message to output right before the plugins table
			'strings'      		=> array(
				'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
				'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
				'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
				'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
				'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
				'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
				'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
				'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
				'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
				'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
				'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
				'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
				'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
				'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
				'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
				'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
				'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
				'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
			)
		);

		tgmpa($plugins, $config);
	}

	function wiziapp_theme_get_menu_item($i)
	{
		ob_start();
?>
		<li>
			<a href="<?php echo esc_attr(wiziapp_theme_settings()->getMenuItemActionURL($i)); ?>" data-transition="slide" <?php echo wiziapp_theme_settings()->getMenuItemAttributes($i); ?>>
				<span><?php echo esc_html(wiziapp_theme_settings()->getMenuItemTitle($i)); ?></span>
			</a>
		</li>
<?php
		return ob_get_clean();
	}

	function wiziapp_theme_add_login_item($items)
	{
		if (get_option('users_can_register'))
		{
			$items .= PHP_EOL.wiziapp_theme_get_menu_item(wiziapp_theme_settings()->getLoginMenuKey()).PHP_EOL;
		}
		return $items;
	}

	function wiziapp_theme_parent_styles()
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

	function wiziapp_theme_locate_parent_template($template_names, $load = false, $require_once = true ) {
		$located = '';
		foreach ((array) $template_names as $template_name)
		{
			if ($template_name && file_exists(TEMPLATEPATH.'/'.$template_name))
			{
				$located = TEMPLATEPATH . '/' . $template_name;
				break;
			}
		}

		if ( $load && '' != $located )
		{
			load_template( $located, $require_once );
		}

		return $located;
	}

	function wiziapp_theme_get_parent_template_part( $slug, $name = null ) {
		do_action( "wiziapp_theme_get_parent_template_part_{$slug}", $slug, $name );

		$templates = array();
		$name = (string) $name;
		if ( '' !== $name )
			$templates[] = "{$slug}-{$name}.php";

		$templates[] = "{$slug}.php";

		wiziapp_theme_locate_parent_template($templates, true, false);
	}

	function wiziapp_theme_check_compatibility_scripts()
	{
		wiziapp_theme_detect_gravityforms();
	}

	function wiziapp_theme_check_compatibility_head()
	{
		$support_ajax = true;
		$stylesheets = array();
		if (wiziapp_theme_detect_woocommerce())
		{
			$stylesheets[] = get_template_directory_uri().'/woocommerce.css';
			$support_ajax = false;
		}
		if (wiziapp_theme_detect_buddypress())
		{
			$stylesheets[] = get_template_directory_uri().'/buddypress.css';
			$support_ajax = false;
		}
		if (defined('wpsxp_PLUGINS_URL'))
		{
			$support_ajax = false;
		}
		foreach ($stylesheets as $stylesheet)
		{
			echo '<link rel="stylesheet" href="' . $stylesheet . '" type="text/css" media="screen" />'.PHP_EOL;
		}
		if ($support_ajax)
		{
			return;
		}
?>
<script type="text/javascript">
	jQuery.mobile.ajaxEnabled = false;
</script>
<?php
	}

	function wiziapp_theme_check_compatibility_header($name)
	{
		if (wiziapp_theme_detect_woocommerce() && $name === 'shop')
		{
			if (is_archive())
			{
				wiziapp_theme_settings()->fromPageList();
			}
			else
			{
				wiziapp_theme_settings()->back_url = get_permalink((int) woocommerce_get_page_id('shop'));
			}
		}
	}

	function wiziapp_theme_detect_woocommerce()
	{
		return function_exists('woocommerce_get_page_id');
	}

	function wiziapp_theme_detect_buddypress()
	{
		return function_exists('buddypress');
	}

	function wiziapp_theme_detect_gravityforms()
	{
		if (!class_exists('GFCommon'))
		{
			return;
		}
		if (!class_exists('GFFormDisplay'))
		{
			require_once(GFCommon::get_base_path() . "/form_display.php");
		}
		$forms = array();
		foreach (GFFormsModel::get_forms(1) as $form)
		{
			$forms[] = RGFormsModel::get_form_meta($form->id);
		}
		foreach($forms as $form)
		{
			if(isset($form["id"]))
			{
				GFFormDisplay::enqueue_form_scripts($form, true);
			}
		}
	}

