<?php
	require_once(dirname(__FILE__).'/hook.php');

	class WiziappPluginThemeLicenses
	{
		var $licenses = null;

		function hasThemeLicense($theme)
		{
			return $theme === 'wiziapp' || $this->getThemeLicense($theme) !== false;
		}

		function getThemeLicense($theme)
		{
			$this->_load();
			return isset($this->licenses[$theme])?$this->licenses[$theme]:false;
		}

		function setThemeLicense($theme, $license = false)
		{
			$this->_load();
			if ($license === false)
			{
				if (!isset($this->licenses[$theme]))
				{
					return;
				}
				unset($this->licenses[$theme]);
			}
			else
			{
				if (isset($this->licenses[$theme]) && $this->licenses[$theme] === $license)
				{
					return;
				}
				$this->licenses[$theme] = $license;
			}
			$this->_save();
		}

		function getThemeLicenses()
		{
			$this->_load();
			return $this->licenses;
		}

		function setThemeLicenses($licenses)
		{
			$this->licenses = $licenses;
			$this->_save();
		}

		function _load()
		{
			if (null === $this->licenses)
			{
				$this->licenses = get_option('wiziapp_plugin_theme_licenses', array());
			}
		}

		function _save()
		{
			add_option('wiziapp_plugin_theme_licenses', $this->licenses);
			update_option('wiziapp_plugin_theme_licenses', $this->licenses);
		}
	}

	function &wiziapp_plugin_theme_licenses()
	{
		static $inst = null;
		if (!$inst)
		{
			$inst = new WiziappPluginThemeLicenses();
		}
		return $inst;
	}
