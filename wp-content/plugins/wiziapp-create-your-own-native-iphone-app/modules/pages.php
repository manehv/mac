<?php
	require_once(dirname(dirname(__FILE__)).'/includes/hook.php');

	class WiziappPluginModulePages
	{
		var $_pages = array();
		var $_loaded = false;

		function init()
		{
			$this->_pages['latest'] = __('Latest', 'wiziapp-plugin');
			$this->_pages['pages'] = __('Pages', 'wiziapp-plugin');
			$this->_pages['categories'] = __('Categories', 'wiziapp-plugin');
			$this->_pages['tags'] = __('Tags', 'wiziapp-plugin');
			$this->_pages['archive_years'] = __('Archive', 'wiziapp-plugin');
			$this->_pages['archive_months'] = __('Archive By Months', 'wiziapp-plugin');
			$this->_pages['bookmarks'] = __('Links', 'wiziapp-plugin');
			$this->_pages['search'] = __('Search', 'wiziapp-plugin');

			wiziapp_plugin_hook()->hookInstall(array(&$this, 'install'));
			wiziapp_plugin_hook()->hookUninstall(array(&$this, 'uninstall'));
			wiziapp_plugin_hook()->hookLoad(array(&$this, 'load'));
			wiziapp_plugin_hook()->hookLoadAdmin(array(&$this, 'load'));
		}

		function load()
		{
			if ($this->_loaded)
			{
				return;
			}
			$this->_loaded = true;

			register_post_type('wiziapp', array(
				'labels' => array(
					'name' => __('WiziApp Mobile Pages', 'wiziapp-plugin'),
					'singular_name' => __('WiziApp Mobile Page', 'wiziapp-plugin'),
				),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => false,
				'show_in_nav_menus' => true,
				'exclude_from_search' => true,
				'rewrite' => true,
				'supports' => array('title'),
				'can_export' => false
			));

			add_action('wp', array(&$this, 'route'));
		}

		function route(&$wp)
		{
			global $post;
			if (!empty($post) && isset($post->post_type) && $post->post_type === 'wiziapp')
			{
				$wp->query_vars = array('wiziapp_display' => $post->post_name, 'paged' => isset($wp->query_vars['paged'])?$wp->query_vars['paged']:1);
				$wp->query_vars = apply_filters('request', $wp->query_vars);
				do_action_ref_array('parse_request', array(&$wp));
				$wp->query_posts();
				$wp->handle_404();
				$wp->register_globals();
			}
		}

		function install()
		{
			$this->load();

			foreach ($this->_pages as $name => $title)
			{
				$this->get_page($name);
			}

			flush_rewrite_rules();
		}

		function uninstall()
		{
			$get_posts = new WP_Query();
			$get_menu_items = new WP_Query();
			foreach ($get_posts->query(array('post_type' => 'wiziapp', 'cache_results' => false, 'post_status' => 'any', 'posts_per_page' => -1, 'ignore_sticky_posts' => true, 'no_found_rows' => true)) as $post)
			{
				foreach ($get_menu_items->query(array('post_type' => 'nav_menu_item', 'meta_query' => array(array('key' => '_menu_item_type', 'value' => 'post_type'), array('key' => '_menu_item_object_id', 'value' => $post->ID), array('key' => '_menu_item_object', 'value' => 'wiziapp')), 'cache_results' => false, 'post_status' => 'any', 'posts_per_page' => -1, 'ignore_sticky_posts' => true, 'no_found_rows' => true, 'fields' => 'ids')) as $menu_item)
				{
					update_post_meta($menu_item, '_menu_item_wiziapp_page_name', $post->post_name);
				}
				wp_delete_post($post->ID, true);
			}
		}

		function get_page($name)
		{
			if (!isset($this->_pages[$name]))
			{
				return false;
			}
			$title = $this->_pages[$name];
			$get_posts = new WP_Query();
			$posts = $get_posts->query(array('post_type' => 'wiziapp', 'name' => $name, 'fields' => 'ids'));
			if (isset($posts[0]))
			{
				return $posts[0];
			}
			$post_id = wp_insert_post(array('post_status' => 'publish', 'post_type' => 'wiziapp', 'post_name' => $name, 'post_title' => $title));
			$get_menu_items = new WP_Query();
			foreach ($get_menu_items->query(array('post_type' => 'nav_menu_item', 'meta_query' => array(array('key' => '_menu_item_type', 'value' => 'post_type'), array('key' => '_menu_item_wiziapp_page_name', 'value' => $name), array('key' => '_menu_item_object', 'value' => 'wiziapp')), 'cache_results' => false, 'post_status' => 'any', 'posts_per_page' => -1, 'ignore_sticky_posts' => true, 'no_found_rows' => true, 'fields' => 'ids')) as $menu_item)
			{
				update_post_meta($menu_item, '_menu_item_object_id', $post_id);
				delete_post_meta($menu_item, '_menu_item_wiziapp_page_name');
			}
			return $post_id;
		}
	}

	function &wiziapp_plugin_module_pages()
	{
		static $inst = null;
		if (!$inst)
		{
			$inst = new WiziappPluginModulePages();
			$inst->init();
		}
		return $inst;
	}

	wiziapp_plugin_module_pages();
