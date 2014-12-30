<?php
	require_once(dirname(dirname(__FILE__)).'/includes/settings.php');
	require_once(dirname(__FILE__).'/switcher.php');

	class WiziappPluginModuleHTML5
	{
		function init()
		{
			wiziapp_plugin_module_switcher()->hookGetTheme(array($this, 'getTheme'));
		}

		function getTheme()
		{
			if (!wiziapp_plugin_settings()->getWebappActive() && !wiziapp_plugin_settings()->getWebappTabletActive())
			{
				return false;
			}
			$theme = '';
			if (!$this->_is_desktop_mode() && !$this->_is_native_iphone_app())
			{
				switch ($this->_get_device_type())
				{
					case 'mobile':
						if (wiziapp_plugin_settings()->getWebappActive())
						{
							$theme = wiziapp_plugin_settings()->getWebappTheme();
						}
						break;
					case 'tablet':
						if (wiziapp_plugin_settings()->getWebappTabletActive())
						{
							$theme = wiziapp_plugin_settings()->getWebappTheme();
						}
						break;
				}
			}
			return array('theme' => $theme, 'head' => array($this, 'wp_head'), 'menu' => wiziapp_plugin_settings()->getWebappMenu(), 'extras' => array('links' => array('Desktop site' => add_query_arg('desktop-site-mode', '1'))));
		}

		function wp_head()
		{
			$icon = wiziapp_plugin_settings()->getWebappIcon();
			if (empty($icon))
			{
				return;
			}
?>
		<link rel="shortcut icon" href="<?php echo esc_attr($icon); ?>">
		<link rel="apple-touch-icon" href="<?php echo esc_attr($icon); ?>" />
		<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr(wiziapp_plugin_settings()->getWebappName()); ?>" />
<?php
		}

		function _get_device_type()
		{
			if (!isset($_SERVER['HTTP_USER_AGENT']))
			{
				return 'unknown';
			}

			$is_iPhone	= stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone')  !== FALSE && stripos($_SERVER['HTTP_USER_AGENT'], 'Mac OS X')	   !== FALSE;
			$is_iPod	= stripos($_SERVER['HTTP_USER_AGENT'], 'iPod')    !== FALSE && stripos($_SERVER['HTTP_USER_AGENT'], 'Mac OS X')	   !== FALSE;
			$is_android	= stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== FALSE && stripos($_SERVER['HTTP_USER_AGENT'], 'AppleWebKit') !== FALSE;
			$is_windows	= stripos($_SERVER['HTTP_USER_AGENT'], 'Windows') !== FALSE && stripos($_SERVER['HTTP_USER_AGENT'], 'IEMobile')	   !== FALSE && stripos($_SERVER['HTTP_USER_AGENT'], 'Phone') !== FALSE;
			$is_iPad	= stripos($_SERVER['HTTP_USER_AGENT'], 'iPad')    !== FALSE || stripos($_SERVER['HTTP_USER_AGENT'], 'webOS') 	   !== FALSE;

			if ($is_iPad)
			{
				return 'tablet';
			}
			else if ($is_iPhone || $is_iPod || $is_android || $is_windows)
			{
				return 'mobile';
			}
			return 'desktop';
		}

		function _is_desktop_mode()
		{
			if (!session_id())
			{
				session_start();
			}

			if (isset($_GET['desktop-site-mode']) && $_GET['desktop-site-mode'] === '1')
			{
				$_SESSION['desktop_site_mode'] = '1';
				wp_redirect(remove_query_arg('desktop-site-mode'));
				exit;
			}

			if (isset($_SESSION['desktop_site_mode']) && $_SESSION['desktop_site_mode'] === '1')
			{
				return true;
			}

			return false;
		}

		function _is_native_iphone_app()
		{
			if (!class_exists('WiziappContentHandler'))
			{
				return false;
			}

			$wiziapp_content_handler = WiziappContentHandler::getInstance();
			if (!$wiziapp_content_handler->isInApp())
			{
				return false;
			}

			return true;
		}
	}

	$module = new WiziappPluginModuleHTML5();
	$module->init();
