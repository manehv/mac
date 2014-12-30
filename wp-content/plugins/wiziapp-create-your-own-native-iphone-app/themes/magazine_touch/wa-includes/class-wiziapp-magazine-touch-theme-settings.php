<?php
	class WiziappMagazineTouchThemeSettings
	{
		var $autocommit = true;
		var $preview = false;
		var $dirty = false;
		var $options = null;
		var $option = null;

		function deleteAll()
		{
			if (null === $this->options)
			{
				$this->option = 'wiziapp_plugin_wiziapp_magazine_touch_theme_settings';
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

		function getAppHeaderType()
		{
			$this->_load();
			return $this->options['app_header_type'];
		}

		function setAppHeaderType($type)
		{
			$this->_load();
			$this->options['app_header_type'] = $type;
			$this->_save();
		}

		function getAppHeaderTitle()
		{
			$this->_load();
			return $this->options['app_header_title'];
		}

		function setAppHeaderTitle($title)
		{
			$this->_load();
			$this->options['app_header_title'] = $title;
			$this->_save();
		}

		function getAppHeaderImage()
		{
			return $this->options['app_header_image'];
		}

		function setAppHeaderImage($image)
		{
			$this->_load();
			$this->options['app_header_image'] = $image;
			$this->_save();
		}

		function getAppHeaderColor()
		{
			return $this->options['app_header_color'];
		}

		function setAppHeaderColor($color)
		{
			$this->_load();
			$this->options['app_header_color'] = $color;
			$this->_save();
		}

		function getAppHeaderBackground()
		{
			return $this->options['app_header_background'];
		}

		function setAppHeaderBackground($background)
		{
			$this->_load();
			$this->options['app_header_background'] = $background;
			$this->_save();
		}

		function getAppContentColor()
		{
			return $this->options['app_content_color'];
		}

		function setAppContentColor($color)
		{
			$this->_load();
			$this->options['app_content_color'] = $color;
			$this->_save();
		}

		function getAppContentStartBackground()
		{
			return $this->options['app_content_start_background'];
		}

		function setAppContentStartBackground($start_background)
		{
			$this->_load();
			$this->options['app_content_start_background'] = $start_background;
			$this->_save();
		}

		function getAppContentEndBackground()
		{
			return $this->options['app_content_end_background'];
		}

		function setAppContentEndBackground($end_background)
		{
			$this->_load();
			$this->options['app_content_end_background'] = $end_background;
			$this->_save();
		}

		function _load()
		{
			if (null === $this->options)
			{
				$this->option = 'wiziapp_plugin_wiziapp_magazine_touch_theme_settings';
				/*
				 * We merge with the defaults, rather than specifying them,
				 * so we add missing defaults, even if we have partial settings
				 */
				 $this->options =
				 get_option($this->option, array())+
				 array(
					 'app_header_type' => 'text',
					 'app_header_title' => get_bloginfo('name'),
					 'app_header_image' => '',
					 'app_header_color' => '#fff',
					 'app_header_background' => '#111',
					 'app_content_color' => '#4B5A6F !important',
					 'app_content_start_background' => '#fff',
					 'app_content_end_background' => '#f1f1f1',
				 );
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
	 * Returns a global instance of WiziappMagazineTouchThemeSettings
	 *
	 * @staticvar WiziappMagazineTouchThemeSettings $inst Used to store the instance
	 * @return WiziappMagazineTouchThemeSettings The settings
	 */

	function &wiziapp_magazine_touch_theme_settings()
	{
		static $inst = null;
		if (!$inst)
		{
			$inst = new WiziappMagazineTouchThemeSettings();
		}
		return $inst;
	}

