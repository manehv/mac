<?php
	class WiziappPluginHook
	{
		var $hooks_load = array();
		var $hooks_load_admin = array();
		var $hooks_install = array();
		var $hooks_uninsall = array();

		function load()
		{
			load_plugin_textdomain('wiziapp-plugin', dirname(dirname(__FILE__)).'/languages', dirname(plugin_basename( __FILE__ )).'/languages');
			if (is_admin())
			{
				foreach ($this->hooks_load_admin as $cb)
				{
					call_user_func($cb);
				}
			}
			else
			{
				foreach ($this->hooks_load as $cb)
				{
					call_user_func($cb);
				}
			}
		}

		function install()
		{
			foreach ($this->hooks_install as $cb)
			{
				call_user_func($cb);
			}
		}

		function uninstall()
		{
			foreach ($this->hooks_uninsall as $cb)
			{
				call_user_func($cb);
			}
		}

		function hookLoad($cb)
		{
			$this->hooks_load[] = $cb;
		}

		function hookLoadAdmin($cb)
		{
			$this->hooks_load_admin[] = $cb;
		}

		function hookInstall($cb)
		{
			$this->hooks_install[] = $cb;
		}

		function hookUninstall($cb)
		{
			$this->hooks_uninsall[] = $cb;
		}

		function plugins_url($path = '')
		{
			if (function_exists('plugins_url'))
			{
				return plugins_url($path, dirname(__FILE__));
			}
			return trailingslashit(get_bloginfo('wpurl')).'wp-content/plugins/'.dirname(plugin_basename(dirname(__FILE__))).$path;
		}

		function json_output($output)
		{
			while (ob_get_level() > 0)
			{
				ob_end_clean();
			}
			header('Content-Type: text/json');
			echo json_encode($output);
			ob_start(array(&$this, '_clean'));
			exit;
		}

		function _clean()
		{
			return '';
		}
	}

	function &wiziapp_plugin_hook()
	{
		static $inst = null;
		if (!$inst)
		{
			$inst = new WiziappPluginHook();
		}
		return $inst;
	}

	add_action('setup_theme', array(wiziapp_plugin_hook(), 'load'));
	register_activation_hook(plugin_basename(dirname(dirname(__FILE__)).'/wiziapp.php'), array(wiziapp_plugin_hook(), 'install'));
	register_deactivation_hook(plugin_basename(dirname(dirname(__FILE__)).'/wiziapp.php'), array(wiziapp_plugin_hook(), 'uninstall'));
