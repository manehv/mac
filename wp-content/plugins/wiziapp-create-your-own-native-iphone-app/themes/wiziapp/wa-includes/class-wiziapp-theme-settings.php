<?php
	class WiziappThemeSettings
	{
		var $autocommit = true;
		var $preview = false;
		var $dirty = false;
		var $options = null;
		var $option = null;
		var $back_url = '';
		var $wiziapp_back = '';
		var $supported_icons = array(
			'search',
			'gears',
			'clock',
			'tags',
			'gear',
			'tag',
			'eye',
			'star',
			'heart',
			'cabinet',
			'inbox',
			'painting',
			'movie',
			'video',
			'album',
			'film',
			'drawer',
			'house',
			'paperclip',
			'tone',
			'microphone',
			'screen',
			'calendar',
			'camera',
			'book',
			'palette',
			'mixer',
			'categories',
			'widescreen',
			'headphones',
			'speaker',
			'picture',
			'id',
			'doghouse',
			'guitar',
			'category',
			'tape',
			'recording',
			'newspaper',
			'pricetag',
			'hall',
			'tune',
			'info',
			'ellipsis',
		);

		function deleteAll()
		{
			if (null === $this->options)
			{
				$this->option = apply_filters('wiziapp_theme_settings_name', 'wiziapp_theme_settings');
			}
			delete_option($this->option);
		}

		function setAutocommit($autocommit = true)
		{
			$this->autocommit = $autocommit;
			if ($autocommit && $this->dirty)
			{
				$this->_save();
			}
		}

		function setPreview($preview = true)
		{
			if ($preview)
			{
				$this->_load();
				$this->preview = $this->options;
			}
			else if ($this->preview !== false)
			{
				$this->options = $this->preview;
				$this->preview = false;
				if ($this->autocommit && $this->dirty)
				{
					$this->_save();
				}
			}
		}

		function getMenuType()
		{
			$this->_load();
			return $this->options['menu_type'];
		}

		function setMenuType($type = 'navbar')
		{
			$this->_load();
			$this->options['menu_type'] = $type;
			$this->_save();
		}

		function getSupportedMenuIcons()
		{
			return $this->supported_icons;
		}

		function getMenuItemCount()
		{
			$this->_load();
			return count($this->options['menu_items']);
		}

		function getMenuItemTitle($item)
		{
			$this->_load();
			return $this->options['menu_items'][$item][0];
		}

		function getMenuItemAttributes($item)
		{
			$this->_load();
			if (!isset($this->options['menu_items'][$item][3]) || !is_array($this->options['menu_items'][$item][3]))
			{
				return;
			}
			$attributes_array = $this->options['menu_items'][$item][3];

			$attributes = '';
			foreach ($attributes_array as $key => $value)
			{
				$attributes .= esc_attr($key).'="'.esc_attr($value).'" ';
			}
			return $attributes;
		}

		function getMenuItemIcon($item)
		{
			$this->_load();
			return $this->options['menu_items'][$item][1];
		}

		function getMenuItemActionURL($item)
		{
			$this->_load();
			return $this->options['menu_items'][$item][2];
		}

		function addMenuItem($title, $icon, $url, $position = false)
		{
			$this->_load();
			array_splice($this->options['menu_items'], (false === $position)?count($this->options['menu_items']):$position, 0, array($title, $icon, $url));
			$this->_save();
		}

		function deleteMenuItem($item)
		{
			$this->_load();
			if (!isset($this->options['menu_items'][$item]))
			{
				return;
			}
			array_splice($this->options['menu_items'], $item, 1);
			$this->_save();
		}

		function moveMenuItem($item, $new_position = false)
		{
			$this->_load();
			if (!isset($this->options['menu_items'][$item]))
			{
				return;
			}
			$value = $this->options['menu_items'][$item];
			array_splice($this->options['menu_items'], $item, 1);
			array_splice($this->options['menu_items'], (false === $new_position)?count($this->options['menu_items']):$new_position, 0, $value);
			$this->_save();
		}

		function prepare_added_query(&$added_query)
		{
			$this->_load();
			if ($this->wiziapp_back != '' && is_array($added_query))
			{
				$added_query['wiziapp_back'] = $this->wiziapp_back;
			}
		}

		function fromPostList()
		{
			$this->_load();
			$terms = array(
				'category' => array('cat', 'getCategoriesUrl',),
				'post_tag' => array('tag', 'getTagsUrl',),
			);
			$queried_object = get_queried_object();
			if (isset($queried_object->taxonomy) && array_key_exists($queried_object->taxonomy, $terms))
			{
				$this->wiziapp_back = $terms[$queried_object->taxonomy][0].'-'.$queried_object->term_id;
				$this->back_url = $this->{$terms[$queried_object->taxonomy][1]}();
			}
			else if (is_archive() && is_date() && is_month())
			{
				global $wp_query;
				$m = '' . preg_replace('|[^0-9]|', '', $wp_query->get('m'));
				$this->wiziapp_back = 'm'.'-'.$m;
				$this->back_url = $this->getMonthsUrl(substr($m, 0, 4));
			}
			else if (is_search())
			{
				$this->back_url = $this->getSearchUrl();
			}
		}

		function fromPageList()
		{
			$this->_load();
			$this->back_url = $this->getPagesUrl();
		}

		function fromMonthList()
		{
			$this->_load();
			$this->back_url = $this->getYearsUrl();
		}

		function fromSinglePost()
		{
			$this->_load();
			global $wp_query;
			$terms = array('tag', 'cat', 'm',);
			$wiziapp_back = $wp_query->get('wiziapp_back');
			$back_data = explode('-', $wiziapp_back);
			if (!isset($back_data[0]) || !in_array($back_data[0], $terms))
			{
				return;
			}
			$this->wiziapp_back = $wiziapp_back;
			if ($back_data[0] === 'cat')
			{
				$this->back_url = esc_html(get_category_link(intval($back_data[1])));
			}
			else if ($back_data[0] === 'tag')
			{
				$this->back_url = esc_html(get_tag_link(intval($back_data[1])));
			}
			else if ($back_data[0] === 'm')
			{
				$this->back_url = esc_html(get_year_link(intval($back_data[1])));
			}
		}

		function fromComment()
		{
			$this->_load();
			global $wp_query;
			$post_id = $wp_query->get('wiziapp_theme_comment_post');
			$wiziapp_back = $wp_query->get('wiziapp_back');
			$this->back_url = esc_html(get_permalink($post_id));
			if ($wiziapp_back != '')
			{
				$this->wiziapp_back = $wiziapp_back;
				$this->back_url = esc_html(add_query_arg('wiziapp_back', $wiziapp_back, $this->back_url));
			}
		}

		function getWiziappBack()
		{
			$this->_load();
			return $this->wiziapp_back;
		}

		function getBackUrl()
		{
			$this->_load();
			return $this->back_url;
		}

		function getLatestUrl()
		{
			return add_query_arg('wiziapp_display', 'latest', trailingslashit(get_bloginfo('url')));
		}

		function getCategoriesUrl()
		{
			return add_query_arg('wiziapp_display', 'categories', trailingslashit(get_bloginfo('url')));
		}

		function getTagsUrl()
		{
			return add_query_arg('wiziapp_display', 'tags', trailingslashit(get_bloginfo('url')));
		}

		function getPagesUrl()
		{
			return add_query_arg('wiziapp_display', 'pages', trailingslashit(get_bloginfo('url')));
		}

		function getMonthsUrl($year)
		{
			$this->_load();
			return add_query_arg('wiziapp_display', 'archive_months', trailingslashit(get_year_link($year)));
		}

		function getYearsUrl()
		{
			return add_query_arg('wiziapp_display', 'archive_years', trailingslashit(get_bloginfo('url')));
		}

		function getLinksUrl()
		{
			return add_query_arg('wiziapp_display', 'bookmarks', trailingslashit(get_bloginfo('url')));
		}

		function getSearchUrl()
		{
			return add_query_arg('wiziapp_display', 'search', trailingslashit(get_bloginfo('url')));
		}

		function getLoginUrl()
		{
			global $wp;
			$current_page_url = add_query_arg($wp->query_string, '', home_url($wp->request));

			if (is_user_logged_in())
			{
				return array(
					__('Logout', 'wiziapp-smooth-touch'),
					'paperclip',
					wp_logout_url($current_page_url),
				);
			}
			else
			{
				return array(
					__('Login', 'wiziapp-smooth-touch'),
					'paperclip',
					wp_login_url($current_page_url),
					array('rel' => 'external',),
				);
			}
		}

		function getLoginMenuKey()
		{
			$count = $this->getMenuItemCount();
			for ($i = 0; $i < $count; $i++)
			{
				if (in_array(strtolower($this->getMenuItemTitle($i)), array('login', 'logout')))
				{
					return $i;
				}
			}
			return 0;
		}

		function getPostListDisplayAuthor()
		{
			$this->_load();
			return $this->options['post_list_display_author'];
		}

		function setPostListDisplayAuthor($display = true)
		{
			$this->_load();
			$this->options['post_list_display_author'] = $display;
			$this->_save();
		}

		function getPostListDisplayDate()
		{
			$this->_load();
			return $this->options['post_list_display_date'];
		}

		function setPostListDisplayDate($display = true)
		{
			$this->_load();
			$this->options['post_list_display_date'] = $display;
			$this->_save();
		}

		function getPostListDisplayCommentsCount()
		{
			$this->_load();
			return $this->options['post_list_display_comments'];
		}

		function setPostListDisplayCommentsCount($display = true)
		{
			$this->_load();
			$this->options['post_list_display_comments'] = $display;
			$this->_save();
		}

		function getPostListDisplayFeatured()
		{
			$this->_load();
			return $this->options['post_list_display_featured'];
		}

		function setPostListDisplayFeatured($display = true)
		{
			$this->_load();
			$this->options['post_list_display_featured'] = $display;
			$this->_save();
		}

		function getPostListDisplayThumbnail()
		{
			$this->_load();
			return $this->options['post_list_display_thumbnail'];
		}

		function setPostListDisplayThumbnail($display = true)
		{
			$this->_load();
			$this->options['post_list_display_thumbnail'] = $display;
			$this->_save();
		}

		function getPostListDisplayThumbnailOverlay()
		{
			$this->_load();
			return $this->options['post_list_display_thumbnail_overlay'];
		}

		function setPostListDisplayThumbnailOverlay($display = true)
		{
			$this->_load();
			$this->options['post_list_display_thumbnail_overlay'] = $display;
			$this->_save();
		}

		function getPostDisplayAuthor()
		{
			$this->_load();
			return $this->options['post_display_author'];
		}

		function setPostDisplayAuthor($display = true)
		{
			$this->_load();
			$this->options['post_display_author'] = $display;
			$this->_save();
		}

		function getPostDisplayDate()
		{
			$this->_load();
			return $this->options['post_display_date'];
		}

		function setPostDisplayDate($display = true)
		{
			$this->_load();
			$this->options['post_display_date'] = $display;
			$this->_save();
		}

		function getPostDisplayCategories()
		{
			$this->_load();
			return $this->options['post_display_categories'];
		}

		function setPostDisplayCategories($display = true)
		{
			$this->_load();
			$this->options['post_display_categories'] = $display;
			$this->_save();
		}

		function getPostDisplayTags()
		{
			$this->_load();
			return $this->options['post_display_tags'];
		}

		function setPostDisplayTags($display = true)
		{
			$this->_load();
			$this->options['post_display_tags'] = $display;
			$this->_save();
		}

		function getPostDisplayComments()
		{
			$this->_load();
			return $this->options['post_display_comments'];
		}

		function setPostDisplayComments($display = true)
		{
			$this->_load();
			$this->options['post_display_comments'] = $display;
			$this->_save();
		}

		function getPageDisplayAuthor()
		{
			$this->_load();
			return $this->options['page_display_author'];
		}

		function setPageDisplayAuthor($display = true)
		{
			$this->_load();
			$this->options['page_display_author'] = $display;
			$this->_save();
		}

		function getPageDisplayDate()
		{
			$this->_load();
			return $this->options['page_display_date'];
		}

		function setPageDisplayDate($display = true)
		{
			$this->_load();
			$this->options['page_display_date'] = $display;
			$this->_save();
		}

		function getPageDisplaySubpages()
		{
			$this->_load();
			return $this->options['page_display_subpages'];
		}

		function setPageDisplaySubpages($display = true)
		{
			$this->_load();
			$this->options['page_display_subpages'] = $display;
			$this->_save();
		}

		function getPageDisplayComments()
		{
			$this->_load();
			return $this->options['page_display_comments'];
		}

		function setPageDisplayComments($display = true)
		{
			$this->_load();
			$this->options['page_display_comments'] = $display;
			$this->_save();
		}

		function getAppIcon()
		{
			$this->_load();
			if (is_string($this->options['app_icon']) && !empty($this->options['app_icon']))
			{
				return trailingslashit(get_bloginfo('url')).$this->options['app_icon'];
			}
			return '';
		}

		function setAppIcon($icon = false)
		{
			$this->_load();
			$url = trailingslashit(get_bloginfo('url'));
			if (substr($icon, 0, strlen($url)) === $url)
			{
				$icon = substr($icon, strlen($url));
			}
			else
			{
				$icon = false;
			}
			$this->options['app_icon'] = $icon;
			$this->_save();
		}

		function getFrontPage()
		{
			$this->_load();
			return $this->options['front_page'];
		}

		function setFrontPage($id)
		{
			$this->_load();
			$this->options['front_page'] = $id;
			$this->_save();
		}

		function getItemsPerPage()
		{
			$this->_load();
			return $this->options['items_per_page'];
		}

		function _load()
		{
			if ($this->back_url === '')
			{
				$this->back_url = $this->getLatestUrl();
			}
			if (null === $this->options)
			{
				$this->option = apply_filters('wiziapp_theme_settings_name', 'wiziapp_theme_settings');
				/*
				 * We merge with the defaults, rather than specifying them,
				 * so we add missing defaults, even if we have partial settings
				 */
				$this->options =
					array(
						'menu_items' => array(
							array(
								__('Latest', 'wiziapp-smooth-touch'),
								'clock',
								$this->getLatestUrl()
							),
							array(
								__('Pages', 'wiziapp-smooth-touch'),
								'book',
								$this->getPagesUrl()
							),
							array(
								__('Categories', 'wiziapp-smooth-touch'),
								'categories',
								$this->getCategoriesUrl()
							),
							array(
								__('Tags', 'wiziapp-smooth-touch'),
								'tags',
								$this->getTagsUrl()
							),
							array(
								__('Archive', 'wiziapp-smooth-touch'),
								'cabinet',
								$this->getYearsUrl()
							),
							array(
								__('Links', 'wiziapp-smooth-touch'),
								'paperclip',
								$this->getLinksUrl()
							),
							array(
								__('Search', 'wiziapp-smooth-touch'),
								'paperclip',
								$this->getSearchUrl()
							),
							$this->getLoginUrl(),
						)
					)+
					get_option($this->option, array())+
					apply_filters('wiziapp_theme_default_settings', array(
						'menu_type' => 'popup',
						'post_list_display_author' => true,
						'post_list_display_date' => true,
						'post_list_display_comments' => true,
						'post_list_display_thumbnail' => true,
						'post_list_display_featured' => true,
						'post_list_display_thumbnail_overlay' => false,
						'post_display_author' => true,
						'post_display_date' => true,
						'post_display_comments' => true,
						'post_display_categories' => true,
						'post_display_tags' => true,
						'page_display_author' => false,
						'page_display_date' => false,
						'page_display_comments' => true,
						'page_display_subpages' => true,
						'app_icon' => false,
						'front_page' => '',
						'items_per_page' => 25,
					));
			}
		}

		function _save()
		{
			if ($this->preview)
			{
				return;
			}
			if (!$this->autocommit)
			{
				$this->dirty = true;
				return;
			}
			add_option($this->option, $this->options);
			update_option($this->option, $this->options);
			$this->dirty = false;
		}
	}

	/**
	 * Returns a global instance of WiziappThemeSettings
	 *
	 * @staticvar WiziappThemeSettings $inst Used to store the instance
	 * @return WiziappThemeSettings The settings
	 */

	function &wiziapp_theme_settings()
	{
		static $inst = null;
		if (!$inst)
		{
			$inst = new WiziappThemeSettings();
		}
		return $inst;
	}

