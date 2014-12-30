<?php
	class WiziappPluginMenus
	{
		var $menu = false;
		var $menus = false;

		function getMenus()
		{
			if ($this->menus !== false)
			{
				return $this->menus;
			}
			if (function_exists('wp_get_nav_menus'))
			{
				$menus = wp_get_nav_menus( array('orderby' => 'name') );
				foreach ($menus as $menu)
				{
					$ret[$menu->term_id] = $menu->name;
				}
			}
			$this->menus = $ret;
			return $ret;
		}

		function setMenu($menu)
		{
			if ($menu === '')
			{
				$menu = false;
			}
			if ($this->menu === false)
			{
				if ($menu === false)
				{
					return;
				}
				add_filter('theme_mod_nav_menu_locations', array(&$this, 'theme_mod_nav_menu_locations'));
			}
			else if ($menu === false)
			{
				remove_filter('theme_mod_nav_menu_locations', array(&$this, 'theme_mod_nav_menu_locations'));
			}
			$this->menu = $menu;
		}

		function theme_mod_nav_menu_locations($locations)
		{
			if (!is_array($locations))
			{
				$locations = array();
			}
			return array('wiziapp_custom' => $this->menu)+$locations;
		}

		function get_menu_urls()
		{
			$menu_items = wp_get_nav_menu_items(wiziapp_plugin_settings()->getWebappMenu());
			if (!is_array($menu_items))
			{
				return array();
			}
			array_walk($menu_items, array($this, '_get_urls_only'));
			$menu_items_additional = array_map(array($this, '_change_query_string'), $menu_items);
			$menu_items = array_merge($menu_items, $menu_items_additional);

			return $menu_items;
		}

		function _get_urls_only(&$item, $key)
		{
			if (!isset($item->url)){
				$item = '';
				return;
			}
			$item = untrailingslashit($item->url);
			$item = str_replace(array('http://', 'https://'), '', $item);
		}

		function _change_query_string($url)
		{
			return preg_replace('/(\?|&)(wiziapp)(=)/i', '$1wiziapp_display$3', $url);
		}
	}

	function &wiziapp_plugin_menus()
	{
		static $inst = null;
		if (!$inst)
		{
			$inst = new WiziappPluginMenus();
		}
		return $inst;
	}
