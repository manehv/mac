<?php
	require_once(dirname(dirname(__FILE__)).'/includes/settings.php');
	require_once(dirname(__FILE__).'/switcher.php');

	class WiziappPluginModuleIPad
	{
		function init()
		{
			wiziapp_plugin_module_switcher()->hookGetTheme(array($this, 'getTheme'));
		}

		function getTheme()
		{
			if (!wiziapp_plugin_settings()->getIPadActive())
			{
				return false;
			}
			if (!isset($_SERVER['HTTP_USER_AGENT']))
			{
				return false;
			}
			if (stripos($_SERVER['HTTP_USER_AGENT'], 'wiziapp_user_agent=ipad_app') === false)
			{
				return false;
			}
			return array('theme' => wiziapp_plugin_settings()->getIPadTheme(), 'menu' => wiziapp_plugin_settings()->getIPadMenu());
		}
	}

	$module = new WiziappPluginModuleIPad();
	$module->init();
