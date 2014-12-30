<?php
	require_once(dirname(__FILE__).'/hook.php');
	require_once(dirname(__FILE__).'/theme_licenses.php');
	require_once(dirname(dirname(__FILE__)).'/modules/pages.php');

	class WiziappPluginSettings
	{
		var $autocommit = true;
		var $preview = false;
		var $dirty = false;
		var $options = null;

		function deleteAll()
		{
			if(!empty($this->options['android']['attachment'])) {
				wp_delete_attachment($this->options['android']['attachment']);
			}
			delete_option('wiziapp_plugin_settings');
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

		function isConfigured()
		{
			$this->_load();
			return $this->options['configured'];
		}

		function setConfigured()
		{
			$this->_load();
			if (!$this->options['configured'])
			{
				$this->options['configured'] = true;
				$this->_save();
			}
		}

		function getAnalytics()
		{
			$this->_load();
			return $this->options['general']['analytics'];
		}

		function setAnalytics($account)
		{
			$this->_load();
			$this->options['general']['analytics'] = $account;
			$this->_save();
		}

		function getWebappActive()
		{
			$this->_load();
			return $this->options['webapp']['active'];
		}

		function setWebappActive($active)
		{
			$this->_load();
			$this->options['webapp']['active'] = $active;
			$this->_save();
		}

		function getWebappTabletActive()
		{
			$this->_load();
			return $this->options['webapp']['active_tablet'];
		}

		function setWebappTabletActive($active)
		{
			$this->_load();
			$this->options['webapp']['active_tablet'] = $active;
			$this->_save();
		}

		function getWebappTheme()
		{
			$this->_load();
			return $this->options['webapp']['theme'];
		}

		function setWebappTheme($theme)
		{
			$this->_load();
			$this->options['webapp']['theme'] = $theme;
			$this->_save();
		}

		function getWebappIcon()
		{
			$this->_load();
			return $this->_getAttachment($this->options['webapp']['icon']);
		}

		function setWebappIcon($id)
		{
			$this->_load();
			$this->options['webapp']['icon'] = $this->_setAttachment($id, $this->options['webapp']['icon'], 1024, 1024);
			$this->_save();
			return $this->options['webapp']['icon'] == $id;
		}

		function getWebappName()
		{
			$this->_load();
			return $this->options['webapp']['name'];
		}

		function setWebappName($name)
		{
			$this->_load();
			$this->options['webapp']['name'] = $name;
			$this->_save();
		}

		function getWebappMenu()
		{
			$this->_load();
			return $this->options['webapp']['menu'];
		}

		function setWebappMenu($menu)
		{
			$this->_load();
			$this->options['webapp']['menu'] = $menu;
			$this->_save();
		}

		function getAndroidExpiration()
		{
			$this->_load();
			return $this->options['android']['expiration'];
		}

		function setAndroidExpiration($expiration)
		{
			$this->_load();
			$this->options['android']['expiration'] = $expiration;
			$this->_save();
		}

		function getAndroidActive()
		{
			$this->_load();
			return $this->options['android']['active'];
		}

		function setAndroidActive($active)
		{
			$this->_load();
			$this->options['android']['active'] = $active;
			$this->_save();
		}

		function getAndroidTheme()
		{
			$this->_load();
			return $this->options['android']['theme'];
		}

		function setAndroidTheme($theme)
		{
			$this->_load();
			$this->options['android']['theme'] = $theme;
			$this->_save();
		}

		function getAndroidIcon()
		{
			$this->_load();
			return $this->_getAttachment($this->options['android']['icon'], wiziapp_plugin_hook()->plugins_url('/images/default-icon.png'));
		}

		function setAndroidIcon($id)
		{
			$this->_load();
			$this->options['android']['icon'] = $this->_setAttachment($id, $this->options['android']['icon']);
			$this->_save();
			return $this->options['android']['icon'] == $id;
		}

		function getAndroidName()
		{
			$this->_load();
			return $this->options['android']['name'];
		}

		function setAndroidName($name)
		{
			$this->_load();
			$this->options['android']['name'] = $name;
			$this->_save();
		}

		function getAndroidSplash()
		{
			$this->_load();
			return $this->_getAttachment($this->options['android']['splash'], wiziapp_plugin_hook()->plugins_url('/images/default-splash.png'));
		}

		function setAndroidSplash($id)
		{
			$this->_load();
			$this->options['android']['splash'] = $this->_setAttachment($id, $this->options['android']['splash']);
			$this->_save();
			return $this->options['android']['splash'] == $id;
		}

		function getAndroidNeedBuild()
		{
			$this->_load();
			return $this->options['android']['need_build'];
		}

		function setAndroidNeedBuild($need)
		{
			$this->_load();
			$this->options['android']['need_build'] = $need;
			$this->_save();
		}

		function getAndroidBuildToken()
		{
			$this->_load();
			return $this->options['android']['build_token'];
		}

		function setAndroidBuildToken($token)
		{
			$this->_load();
			$this->options['android']['build_token'] = $token;
			$this->_save();
		}

		function getAndroidPushSenderId()
		{
			$this->_load();
			return $this->options['android']['push']['sender_id'];
		}

		function setAndroidPushSenderId($sender_id)
		{
			$this->_load();
			$this->options['android']['push']['sender_id'] = $sender_id;
			$this->_save();
		}

		function getAndroidPushApiKey()
		{
			$this->_load();
			return $this->options['android']['push']['api_key'];
		}

		function setAndroidPushApiKey($api_key)
		{
			$this->_load();
			$this->options['android']['push']['api_key'] = $api_key;
			$this->_save();
		}

		function generateAndroidPushSetupToken()
		{
			$this->_load();
			$this->options['android']['push']['setup_token'] = wp_create_nonce('wiziapp-plugin-setup-push');
			$this->_save();
			return $this->options['android']['push']['setup_token'];
		}

		function getAndroidPushService()
		{
			$this->_load();
			return $this->options['android']['push']['service'];
		}

		function setAndroidPushService($token, $service)
		{
			$this->_load();
			if ($this->options['android']['push']['setup_token'] !== $token)
			{
				return false;
			}
			$this->options['android']['push']['setup_token'] = false;
			$this->options['android']['push']['service'] = $service;
			$this->_save();
			return true;
		}

		function getAndroidAdmobId()
		{
			$this->_load();
			return $this->options['android']['admob_id'];
		}

		function setAndroidAdmobId($admob_id)
		{
			$this->_load();
			$this->options['android']['admob_id'] = $admob_id;
			$this->_save();
		}

		function getAndroidDownload()
		{
			$this->_load();
			return empty($this->options['android']['attachment'])?false:wp_get_attachment_url($this->options['android']['attachment']);
		}

		function setAndroidDownload($file, $package)
		{
			$this->_load();
			$attachment = array(
				'post_mime_type' => 'application/vnd.android.package-archive',
				'post_title' => 'Android APK',
				'post_content' => ''
			);
			$attach_id = wp_insert_attachment($attachment, $file);
			if(!empty($this->options['android']['attachment'])) {
				wp_delete_attachment($this->options['android']['attachment']);
			}
			$this->options['android']['attachment'] = $attach_id;
			$this->options['android']['package'] = $package;
			$this->options['android']['package_published'] = false;
			$this->_save();
		}

		function getAndroidPackage()
		{
			$this->_load();
			return $this->options['android']['package'];
		}

		function getAndroidPublished()
		{
			$this->_load();
			if ($this->options['android']['package_published'])
			{
				return true;
			}
			if ($this->options['android']['package'] === false)
			{
				return false;
			}
			$response = wp_remote_head('https://play.google.com/store/apps/details?id='.urlencode($this->options['android']['package']));
			if (is_wp_error($response) || $response['response']['code'] != 200)
			{
				return false;
			}
			$this->options['android']['package_published'] = true;
			$this->_save();
			return true;
		}

		function getAndroidMenu()
		{
			$this->_load();
			return $this->options['android']['menu'];
		}

		function setAndroidMenu($menu)
		{
			$this->_load();
			$this->options['android']['menu'] = $menu;
			$this->_save();
		}

		function getAndroidIntroScreen()
		{
			$this->_load();
			return $this->options['android']['intro_screen'];
		}

		function setAndroidIntroScreen($active)
		{
			$this->_load();
			$this->options['android']['intro_screen'] = $active;
			$this->_save();
		}

		function getIPadActive()
		{
/*			$this->_load();
			return $this->options['ipad']['active'];*/
			return true;
		}

		function setIPadActive($active)
		{
			$this->_load();
			$this->options['ipad']['active'] = $active;
			$this->_save();
		}

		function getIPadTheme()
		{
/*			$this->_load();
			return $this->options['ipad']['theme'];*/
			return $this->getWebappTheme();
		}

		function setIPadTheme($theme)
		{
			$this->_load();
			$this->options['ipad']['theme'] = $theme;
			$this->_save();
		}

		function getIPadMenu()
		{
/*			$this->_load();
			return $this->options['ipad']['menu'];*/
			return $this->getWebappMenu();
		}

		function setIPadMenu($menu)
		{
			$this->_load();
			$this->options['ipad']['menu'] = $menu;
			$this->_save();
		}

		function getAdExpiration()
		{
			$this->_load();
			return $this->options['ads']['access'];
		}

		function setAdExpiration($expiration)
		{
			$this->_load();
			$this->options['ads']['access'] = $expiration;
			$this->_save();
		}

		function getAdFooter($ignore_access = false)
		{
			$this->_load();
			if ($ignore_access || $this->_hasAdAccess())
			{
				return $this->options['ads']['footer_url'];
			}
			require(dirname(dirname(__FILE__)).'/config.php');
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			$post_url = '';
			if (is_single())
			{
				$post = get_queried_object_id();
				$tags = array();
				$posttags = get_the_tags($post);
				if ($posttags)
				{
					foreach($posttags as $tag)
					{
						$tags[] = $tag->name;
					}
				}
				$post_url = '&post_url='.urlencode(get_permalink($post)).'&post_tags='.urlencode(implode(',', $tags));
			}
			return $wiziapp_plugin_config['build_host'].'/ads/display?url='.urlencode($siteurl).$post_url;
		}

		function setAdFooter($url)
		{
			$this->_load();
			$this->options['ads']['footer_url'] = $url;
			$this->_save();
		}

		function getAdsenseClient($ignore_access = false)
		{
			$this->_load();
			if (!$ignore_access && !$this->_hasAdAccess())
			{
				return '';
			}
			return $this->options['adsense']['client'];
		}

		function setAdsenseClient($id)
		{
			$this->_load();
			$this->options['adsense']['client'] = $id;
			$this->_save();
		}

		function getAdsenseSlot($ignore_access = false)
		{
			$this->_load();
			if (!$ignore_access && !$this->_hasAdAccess())
			{
				return '';
			}
			return $this->options['adsense']['slot'];
		}

		function setAdsenseSlot($id)
		{
			$this->_load();
			$this->options['adsense']['slot'] = $id;
			$this->_save();
		}

		function getAdIFrameUrl($ignore_access = false)
		{
			$this->_load();
			if (!$ignore_access && !$this->_hasAdAccess())
			{
				return '';
			}
			return $this->options['ad_iframe']['url'];
		}

		function setAdIFrameUrl($value)
		{
			$this->_load();
			$this->options['ad_iframe']['url'] = $value;
			$this->_save();
		}

		function getAdIFrameWidth()
		{
			$this->_load();
			return $this->options['ad_iframe']['width'];
		}

		function setAdIFrameWidth($value)
		{
			$this->_load();
			$this->options['ad_iframe']['width'] = $value;
			$this->_save();
		}

		function getAdIFrameHeight()
		{
			$this->_load();
			return $this->options['ad_iframe']['height'];
		}

		function setAdIFrameHeight($value)
		{
			$this->_load();
			$this->options['ad_iframe']['height'] = $value;
			$this->_save();
		}

		function _hasAdAccess()
		{
			$stylesheet = get_stylesheet();
			return ($this->options['ads']['access'] !== false && $this->options['ads']['access'] > date('Y-m-d\\TH:i:s')) || ($stylesheet !== 'wiziapp' && wiziapp_plugin_theme_licenses()->hasThemeLicense($stylesheet));
		}

		function _load()
		{
			if (null === $this->options)
			{
				if (function_exists('is_nav_menu') && !is_nav_menu('wiziapp_custom'))
				{
					$menu_id = wp_update_nav_menu_object(0, array(
						'menu-name' => 'wiziapp_custom',
						'description' => __('WiziApp Default Menu', 'wiziapp-plugin')
					));
					if ($menu_id && !is_wp_error($menu_id))
					{
						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-type' => 'post_type',
							'menu-item-object' => 'wiziapp',
							'menu-item-status' => 'publish',
							'menu-item-object-id' => wiziapp_plugin_module_pages()->get_page('latest')
						));
						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-type' => 'post_type',
							'menu-item-object' => 'wiziapp',
							'menu-item-status' => 'publish',
							'menu-item-object-id' => wiziapp_plugin_module_pages()->get_page('pages')
						));
						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-type' => 'post_type',
							'menu-item-object' => 'wiziapp',
							'menu-item-status' => 'publish',
							'menu-item-object-id' => wiziapp_plugin_module_pages()->get_page('categories')
						));
						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-type' => 'post_type',
							'menu-item-object' => 'wiziapp',
							'menu-item-status' => 'publish',
							'menu-item-object-id' => wiziapp_plugin_module_pages()->get_page('tags')
						));
						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-type' => 'post_type',
							'menu-item-object' => 'wiziapp',
							'menu-item-status' => 'publish',
							'menu-item-object-id' => wiziapp_plugin_module_pages()->get_page('archive_years')
						));
						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-type' => 'post_type',
							'menu-item-object' => 'wiziapp',
							'menu-item-status' => 'publish',
							'menu-item-object-id' => wiziapp_plugin_module_pages()->get_page('bookmarks')
						));
						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-type' => 'post_type',
							'menu-item-object' => 'wiziapp',
							'menu-item-status' => 'publish',
							'menu-item-object-id' => wiziapp_plugin_module_pages()->get_page('search')
						));
					}
				}
				else if (function_exists('wp_get_nav_menu_object') && ($menu = wp_get_nav_menu_object('wiziapp_custom')))
				{
					$menu_id = $menu->term_id;
				}
				else
				{
					$menu_id = '';
				}
				/*
				 * We merge with the defaults, rather than specifying them,
				 * so we can ensure their structure, and update it on version
				 * changes.
				 */
				$this->options = $this->_deep_merge(get_option('wiziapp_plugin_settings', array()),
					array(
						'configured' => false,
						'general' => array(
							'analytics' => ''
						),
						'webapp' => array(
							'active' => true,
							'active_tablet' => true,
							'theme' => 'wiziapp',
							'name' => get_bloginfo('name'),
							'icon' => false,
							'menu' => $menu_id
						),
						'android' => array(
							'expiration' => true,
							'active' => true,
							'theme' => 'wiziapp',
							'icon' => false,
							'name' => get_bloginfo('name'),
							'splash' => false,
							'need_build' => true,
							'build_token' => '',
							'push' => array(
								'sender_id' => '',
								'api_key' => '',
								'service' => '',
								'setup_token' => false
							),
							'admob_id' => '',
							'menu' => $menu_id,
							'intro_screen' => true,
							'attachment' => 0,
							'package' => false,
							'package_published' => false
						),
						'ipad' => array(
							'active' => true,
							'theme' => 'wiziapp',
							'menu' => $menu_id
						),
						'ads' => array(
							'access' => false,
							'footer_url' => ''
						),
						'adsense' => array(
							'client' => '',
							'slot' => ''
						),
						'ad_iframe' => array(
							'url' => '',
							'width' => '320',
							'height' => '50'
						)
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
			add_option('wiziapp_plugin_settings', $this->options);
			update_option('wiziapp_plugin_settings', $this->options);
			$this->dirty = false;
		}

		function _deep_merge($values, $structure)
		{
			foreach ($structure as $key => $value)
			{
				if (!isset($values[$key]))
				{
					continue;
				}
				if (!is_array($structure[$key]))
				{
					$structure[$key] = $values[$key];
					continue;
				}
				if (!is_array($values[$key]))
				{
					continue;
				}
				$structure[$key] = $this->_deep_merge($values[$key], $structure[$key]);
			}
			return $structure;
		}

		function _setAttachment($id, $prev_id, $width = false, $height = false)
		{
			if ($id === false)
			{
				if ($prev_id !== false)
				{
					wp_delete_attachment($prev_id);
				}
				return $id;
			}
			$post = get_post($id);
			if ($post->post_type === 'attachment' && $post->post_mime_type === 'image/png')
			{
				$meta = get_post_meta($id, '_wp_attachment_metadata');
				if (!empty($meta) && !empty($meta[0]) && ($width === false || (isset($meta[0]['width']) && $meta[0]['width'] === $width)) && ($height === false || (isset($meta[0]['height']) && $meta[0]['height'] === $height)))
				{
					if ($prev_id !== false)
					{
						wp_delete_attachment($prev_id);
					}
					return $id;
				}
			}
			if ($id !== false)
			{
				wp_delete_attachment($id);
			}
			return $prev_id;
		}

		function _getAttachment($id, $default = false)
		{
			if (empty($id))
			{
				return $default;
			}
			$post = get_post($id);
			if (empty($post))
			{
				return $default;
			}
			return $post->guid;
		}

		function _install()
		{
			$this->_load();
			if ($this->options['configured'])
			{
				$this->options['configured'] = false;
				$this->_save();
			}
		}
	}

	function &wiziapp_plugin_settings()
	{
		static $inst = null;
		if (!$inst)
		{
			$inst = new WiziappPluginSettings();
		}
		return $inst;
	}

	wiziapp_plugin_hook()->hookInstall(array(wiziapp_plugin_settings(), '_install'));
