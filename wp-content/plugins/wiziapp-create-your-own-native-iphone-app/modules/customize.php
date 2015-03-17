<?php
	require_once(dirname(dirname(__FILE__)).'/includes/theme_licenses.php');

	function wiziapp_plugin_define_WiziappPluginThemeSwitchControl()
	{
		class WiziappPluginThemeSwitchControl extends WP_Customize_Control {
			public $type = 'wiziapp-plugin-theme-switch';

			public function enqueue()
			{
				add_thickbox();
				wp_register_style('wiziapp-plugin-customize', wiziapp_plugin_hook()->plugins_url('/styles/customize.css'), array('thickbox'));
				wp_register_script('wiziapp-plugin-customize', wiziapp_plugin_hook()->plugins_url('/scripts/customize.js'), array('customize-base'));
				wp_enqueue_style('wiziapp-plugin-customize');
				wp_enqueue_script('wiziapp-plugin-customize');
			}

			protected function render_content()
			{
				require(dirname(dirname(__FILE__)).'/config.php');
				$siteurl = urlencode(trailingslashit(get_bloginfo('wpurl')));
				$response = wp_remote_get($wiziapp_plugin_config['build_host'].'/theme/list?url='.$siteurl);
				if (is_wp_error($response))
				{
					wiziapp_plugin_hook()->json_output(array());
				}
				$res = json_decode($response['body'], true);
				if (!is_array($res) || empty($res))
				{
					return;
				}
				$themes = wiziapp_plugin_module_switcher()->get_themes();
				$choices = array();
				foreach ($themes as $name => $theme)
				{
					$choices[$name] = array('title' => $theme, 'installed' => true);
				}
				foreach ($res as $theme)
				{
					if (isset($choices[$theme['name']]))
					{
						continue;
					}
					static $header_tags = array(
						'abbr'    => array( 'title' => true ),
						'acronym' => array( 'title' => true ),
						'code'    => true,
						'em'      => true,
						'strong'  => true,
					);
					$choices[$theme['name']] = array('title' => wp_kses($theme['title'], $header_tags), 'installed' => false);
				}
?>
<label>
	<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	<select <?php $this->link(); ?> class="wiziapp-plugin-theme-switch">
<?php
				foreach ( $choices as $value => $theme )
				{
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . ($theme['installed']?'':' data-wiziapp-plugin-theme-switch-need-install="true"') . '>' . $theme['title'] . '</option>';
				}
?>
	</select>
</label>
<?php
			}
		}
	}

	class WiziappPluginModuleCustomize
	{
		function init()
		{
			if (!isset($_GET['wiziapp_plugin']) || $_GET['wiziapp_plugin'] !== 'customize')
			{
				return;
			}
			add_action('customize_register', array(&$this, 'register'));
		}

		function register($wp_customize)
		{
			wiziapp_plugin_define_WiziappPluginThemeSwitchControl();
			$wp_customize->add_section('wiziapp_plugin_theme_switch', array(
				'title' => __('Preview another theme', 'wiziapp-plugin'),
				'priority' => 1,
				'capability' => 'edit_theme_options',
			));
			$wp_customize->add_setting('wiziapp_plugin_theme_switch', array(
				'default' => get_stylesheet(),
				'type' => 'wiziapp_plugin_theme_switch',
				'capability' => 'edit_theme_options',
				'transport' => 'none',
			));
			$wp_customize->add_control(new WiziappPluginThemeSwitchControl($wp_customize, 'wiziapp_plugin_theme_switch', array(
				'label' => __('Theme', 'wiziapp-plugin'),
				'section' => 'wiziapp_plugin_theme_switch',
				'context' => 'wiziapp_plugin_theme_switch',
			)));
		}
	}

	$module = new WiziappPluginModuleCustomize();
	$module->init();
