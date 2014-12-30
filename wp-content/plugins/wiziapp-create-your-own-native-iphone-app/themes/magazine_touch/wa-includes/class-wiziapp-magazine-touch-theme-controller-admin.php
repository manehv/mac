<?php
	class WiziappMagazineTouchThemeControllerAdminCallback
	{
		var $wp_customize;
		var $setting;
		var $method;
		var $type;

		function init($wp_customize, $setting, $method, $type = '')
		{
			$this->wp_customize = $wp_customize;
			$this->setting = $setting;
			$this->method = $method;
			$this->type = $type;
			add_action('customize_preview_'.$setting, array($this, 'preview'));
			add_action('customize_update_'.$setting, array($this, 'update'));
			add_action('customize_value_'.$setting, array($this, 'value'));
		}

		function update($value)
		{
			if ($this->type === 'boolean') {
				$value = !empty($value);
			}
			$method = 'set'.$this->method;
			wiziapp_magazine_touch_theme_settings()->$method($value);
		}

		function preview()
		{
			$value = $this->wp_customize->get_setting($this->setting)->post_value();
			$method = 'set'.$this->method;
			wiziapp_magazine_touch_theme_settings()->setPreview();
			wiziapp_magazine_touch_theme_settings()->$method($value);
		}

		function value()
		{
			$method = 'get'.$this->method;
			return wiziapp_magazine_touch_theme_settings()->$method();
		}

		function init_header_customize_js()
		{
			add_action('customize_controls_enqueue_scripts', array($this, 'header_customize_js'));
		}
		function header_customize_js()
		{
			wp_register_script('wiziapp_magazine_touch_theme_header_customize', get_stylesheet_directory_uri().'/scripts/header-customize.js', array('jquery',));
			wp_enqueue_script('wiziapp_magazine_touch_theme_header_customize');
		}
	}

	class WiziappMagazineTouchThemeControllerAdmin
	{
		/**
		 * To add new sections and controls to the Theme Customize screen.
		 *
		 * @see add_action('customize_register',$func)
		 * @param \WP_Customize_Manager $wp_customize
		 */
		function register($wp_customize)
		{
			$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
			$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
			$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';

			$wp_customize->remove_section('title_tagline');

			$this->add_header_section($wp_customize);
			$this->add_color_section($wp_customize);
		}

		function add_header_section($wp_customize)
		{
			$wp_customize->add_section('wiziapp_magazine_touch_theme_header_customizing', array(
				'title' => __('App Header'),
				'priority' => 1,
				'capability' => 'edit_theme_options',
				'description' => __('Customize your App Header settings'),
			));
			$callback = new WiziappMagazineTouchThemeControllerAdminCallback();
			$callback->init_header_customize_js();

			$wp_customize->add_setting('wiziapp_magazine_touch_theme_settings_header_type', array(
				'type' => 'wiziapp_magazine_touch_theme_settings_header_type',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			));
			$wp_customize->add_control('wiziapp_magazine_touch_theme_settings_header_type', array(
				'label' => 'Layout',
				'section' => 'wiziapp_magazine_touch_theme_header_customizing',
				'type'           => 'radio',
				'choices'        => array(
					'text'   => __('Text Header'),
					'image'  => __('Image Header'),
				),
			));
			$callback = new WiziappMagazineTouchThemeControllerAdminCallback();
			$callback->init($wp_customize, 'wiziapp_magazine_touch_theme_settings_header_type', 'AppHeaderType');

			$wp_customize->add_setting('wiziapp_magazine_touch_theme_settings_header_title', array(
				'type' => 'wiziapp_magazine_touch_theme_settings_header_title',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			));
			$wp_customize->add_control('wiziapp_magazine_touch_theme_settings_header_title', array(
				'label' => 'Title',
				'section' => 'wiziapp_magazine_touch_theme_header_customizing',
			));
			$callback = new WiziappMagazineTouchThemeControllerAdminCallback();
			$callback->init($wp_customize, 'wiziapp_magazine_touch_theme_settings_header_title', 'AppHeaderTitle');

			$wp_customize->add_setting('wiziapp_magazine_touch_theme_settings_header_image', array(
			'type' => 'wiziapp_magazine_touch_theme_settings_header_image',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			));
			$icon_select = new WP_Customize_Image_Control($wp_customize, 'wiziapp_magazine_touch_theme_settings_header_image', array(
			'label' => 'Choose Image (1024/44 pixel)',
			'section' => 'wiziapp_magazine_touch_theme_header_customizing',
			));
			$wp_customize->add_control($icon_select);
			$callback = new WiziappMagazineTouchThemeControllerAdminCallback();
			$callback->init($wp_customize, 'wiziapp_magazine_touch_theme_settings_header_image', 'AppHeaderImage');

			$wp_customize->add_setting('wiziapp_magazine_touch_theme_settings_header_color', array(
			'type' => 'wiziapp_magazine_touch_theme_settings_header_color',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			));
			$background_select = new WP_Customize_Color_Control($wp_customize, 'wiziapp_magazine_touch_theme_settings_header_color', array(
				'label' => 'Title Color',
				'section' => 'wiziapp_magazine_touch_theme_header_customizing',
			));
			$wp_customize->add_control($background_select);
			$callback = new WiziappMagazineTouchThemeControllerAdminCallback();
			$callback->init($wp_customize, 'wiziapp_magazine_touch_theme_settings_header_color', 'AppHeaderColor');

			$wp_customize->add_setting('wiziapp_magazine_touch_theme_settings_header_background', array(
			'type' => 'wiziapp_magazine_touch_theme_settings_header_background',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			));
			$background_select = new WP_Customize_Color_Control($wp_customize, 'wiziapp_magazine_touch_theme_settings_header_background', array(
				'label' => 'Background Color',
				'section' => 'wiziapp_magazine_touch_theme_header_customizing',
			));
			$wp_customize->add_control($background_select);
			$callback = new WiziappMagazineTouchThemeControllerAdminCallback();
			$callback->init($wp_customize, 'wiziapp_magazine_touch_theme_settings_header_background', 'AppHeaderBackground');
		}

		function add_color_section($wp_customize)
		{
			$wp_customize->add_section('wiziapp_magazine_touch_theme_color_customizing', array(
				'title' => __('Colors'),
				'priority' => 2,
				'capability' => 'edit_theme_options',
				'description' => __('Customize your App colors'),
			));

			$wp_customize->add_setting('wiziapp_magazine_touch_theme_settings_content_color', array(
				'type' => 'wiziapp_magazine_touch_theme_settings_content_color',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			));
			add_action('customize_render_control_' . 'wiziapp_magazine_touch_theme_settings_content_color', array($this, 'show_edit_css_link'));
			$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'wiziapp_magazine_touch_theme_settings_content_color', array(
				'label' => __('Text Color'),
				'section' => 'wiziapp_magazine_touch_theme_color_customizing',
			)));
			$callback = new WiziappMagazineTouchThemeControllerAdminCallback();
			$callback->init($wp_customize, 'wiziapp_magazine_touch_theme_settings_content_color', 'AppContentColor');

			$wp_customize->add_setting('wiziapp_magazine_touch_theme_settings_start_background_color', array(
				'type' => 'wiziapp_magazine_touch_theme_settings_start_background_color',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			));
			$background_select = new WP_Customize_Color_Control($wp_customize, 'wiziapp_magazine_touch_theme_settings_start_background_color', array(
				'label' => 'Start Background Color',
				'section' => 'wiziapp_magazine_touch_theme_color_customizing',
			));
			$wp_customize->add_control($background_select);
			$callback = new WiziappMagazineTouchThemeControllerAdminCallback();
			$callback->init($wp_customize, 'wiziapp_magazine_touch_theme_settings_start_background_color', 'AppContentStartBackground');

			$wp_customize->add_setting('wiziapp_magazine_touch_theme_settings_end_background_color', array(
				'type' => 'wiziapp_magazine_touch_theme_settings_end_background_color',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			));
			$background_select = new WP_Customize_Color_Control($wp_customize, 'wiziapp_magazine_touch_theme_settings_end_background_color', array(
				'label' => 'End Background Color',
				'section' => 'wiziapp_magazine_touch_theme_color_customizing',
			));
			$wp_customize->add_control($background_select);
			$callback = new WiziappMagazineTouchThemeControllerAdminCallback();
			$callback->init($wp_customize, 'wiziapp_magazine_touch_theme_settings_end_background_color', 'AppContentEndBackground');
		}

		function show_edit_css_link()
		{
?>
			<li>
				<a href="<?php echo add_query_arg(array('theme' => 'magazine_touch_theme',), get_admin_url(get_current_blog_id(), 'theme-editor.php')); ?>">
					Edit CSS
				</a>
			</li>
<?php
		}

		function theme_deactivation()
		{
			wiziapp_magazine_touch_theme_settings()->deleteAll();
		}
	}

	$wiziapp_magazine_touch_theme_admin = new WiziappMagazineTouchThemeControllerAdmin();
	add_action('customize_register', array($wiziapp_magazine_touch_theme_admin, 'register'));
	add_action('switch_theme', array($wiziapp_magazine_touch_theme_admin, 'theme_deactivation'));
