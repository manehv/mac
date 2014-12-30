<?php
	require_once(dirname(dirname(__FILE__)).'/includes/hook.php');
	require_once(dirname(dirname(__FILE__)).'/includes/purchase_hook.php');
	require_once(dirname(dirname(__FILE__)).'/includes/theme_licenses.php');
	require_once(dirname(__FILE__).'/switcher.php');

	class WiziappPluginModuleThemePurchase
	{
		function init()
		{
			$hook = new WiziappPluginPurchaseHook();
			$hook->hook('theme', '/theme', array(&$this, '_install_theme'), array(&$this, '_analytics'), array(&$this, '_install_title'), array('theme'), array('theme_title', 'theme_global'));
			wiziapp_plugin_hook()->hookLoadAdmin(array(&$this, 'loadAdmin'));
		}

		function _install_title()
		{
			return __('Installing theme', 'wiziapp-plugin');
		}

		function _install_theme($params, $license, $details)
		{
			if ((isset($params['theme_global']) && $params['theme_global'] === 'true') || $params['theme'] === 'global')
			{
?>
					<script type="text/javascript">
						if (window.parent && window.parent.jQuery) {
							window.parent.jQuery(".available-theme[data-wiziapp-plugin-admin-theme]").addClass("wiziapp-plugin-theme-licensed");
							window.parent.jQuery("#wiziapp-plugin-admin-themes-box-complete").hide();
						}
					</script>
<?php
			}
			if ($params['theme'] === 'global')
			{
?>
					<script type="text/javascript">
						if (window.parent && window.parent.tb_remove) {
							window.parent.tb_remove();
						}
					</script>
<?php
				return;
			}

			// FIXME: Check if this is even needed
			wiziapp_plugin_module_switcher()->_hook_root();

			require(dirname(dirname(__FILE__)).'/config.php');
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			if ($license !== false)
			{
				$balance = array('count' => 0, 'remaining' => 0);
				$response = wp_remote_get($wiziapp_plugin_config['build_host'].'/theme/license/balance?url='.urlencode($siteurl).'&theme='.urlencode($params['theme']).'&license='.urlencode($license));
				if (!is_wp_error($response))
				{
					$res = json_decode($response['body'], true);
					if (is_array($res) && isset($res['count']) && isset($res['remaining']))
					{
						$balance = $res;
					}
					if (!empty($balance['count']))
					{
						wiziapp_plugin_theme_licenses()->setThemeLicense($params['theme'], $license);
					}
				}
?>
					<div class="wrap license">
						<h2><?php _e('Purchase Complete', 'wiziapp-plugin') ?></h2>
<?php
				if (isset($details['theme_title']))
				{
?>
						<p><span><?php _e('Product:', 'wiziapp-plugin'); ?></span> <?php echo esc_html($details['theme_title']); ?></p>
<?php
				}
?>
						<p><span><?php _e('License:', 'wiziapp-plugin'); ?></span> <?php echo sprintf(__('%1$d of %2$d used', 'wiziapp-plugin'), $res['count']-$res['remaining'], $res['count']-0); ?></p>
						<p><span><?php _e('License key:', 'wiziapp-plugin'); ?></span> <?php echo esc_html($license); ?></p>
					</div>
<?php
			}
			$nonce = wp_hash(wp_nonce_tick().'wiziapp_plugin_theme_'.$params['theme'], 'nonce');
			$dladdress = $wiziapp_plugin_config['build_host'].'/theme/download/'.urlencode($params['theme']).'?url='.urlencode($siteurl).'&nonce='.urlencode($nonce);

			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			$skin = new WP_Upgrader_Skin(array(
				'title' => sprintf( __('Installing Theme: %s'), $params['theme'] ),
				'url' => $siteurl.'wp-admin/admin.php?wiziapp_plugin=theme_license&wiziapp_plugin_theme='.urlencode($params['theme']).($license !== false?'&wiziapp_plugin_license='.urlencode($license):'').(isset($details['theme_title'])?'&wiziapp_plugin_theme_title='.urlencode($details['theme_title']):''),
				'nonce' => 'install-theme_' . $params['theme']
			));
			$upgrader = new Theme_Upgrader($skin);

			$upgrader->init();
			$upgrader->upgrade_strings();
			$upgrader->install_strings();

			$skin->header();
			$upgrader->run(array(
				'package' => $dladdress,
				'destination' => dirname(dirname(__FILE__)).'/themes/' . $params['theme'],
				'is_multi' => true,
				'clear_destination' => true,
				'clear_working' => true
			));
			if ( $upgrader->result && !is_wp_error($upgrader->result) )
			{
				if (function_exists('wp_clean_themes_cache'))
				{
					wp_clean_themes_cache();
				}

				$has_license = wiziapp_plugin_theme_licenses()->getThemeLicense($params['theme']);
				$theme = wiziapp_plugin_module_switcher()->get_theme_title($params['theme']);
				if ($has_license)
				{
?>
					<a href="#" class="donelink" title="<?php echo esc_attr__('Themes page'); ?>"><?php _e('Done', 'wiziapp-plugin'); ?></a>
<?php
				}
				else
				{
					$admin_base = function_exists('admin_url')?admin_url():(trailingslashit(get_bloginfo('wpurl')).'wp-admin/');
					$customize_link = esc_attr($admin_base.'customize.php?wiziapp_plugin=customize&theme='.urlencode($params['theme']).'&return='.urlencode($admin_base.'admin.php?page=wiziapp-plugin-settings'));
?>
					<a href="<?php echo esc_attr($customize_link); ?>" class="donelink" title="<?php echo esc_attr__('Themes page'); ?>"><?php _e('Live Preview', 'wiziapp-plugin'); ?></a>
<?php
				}
?>
					<script type="text/javascript">
						if (window.parent && window.parent.jQuery) {
							window.parent.jQuery(<?php echo json_encode('.available-theme[data-wiziapp-plugin-admin-theme='.preg_replace('/([^0-9A-Za-z])/', '\\\\\\1', $params['theme']).']'); ?>).removeClass("wiziapp-plugin-theme-is-not-installed").addClass("wiziapp-plugin-theme-is-installed").removeClass("wiziapp-plugin-theme-is-need-update").addClass("wiziapp-plugin-theme-is-not-need-update")<?php
				if ($has_license)
				{
?>.removeClass("wiziapp-plugin-theme-is-not-licensed").addClass("wiziapp-plugin-theme-is-licensed")<?php
				}
				else
				{
?>.removeClass("wiziapp-plugin-theme-is-licensed").addClass("wiziapp-plugin-theme-is-not-licensed")<?php
				}
?>;
						}

<?php
				if ($has_license)
				{
?>
						window.parent
						.jQuery(".wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id$=_theme] .wiziapp-plugin-admin-settings-box-value select, .wiziapp-plugin-admin-settings-box-themes-controls select")
						.not(<?php echo json_encode(':has(option[value='.preg_replace('/([^0-9A-Za-z])/', '\\\\\\1', $params['theme']).'])'); ?>)
						.append("<option value=\"<?php echo esc_attr($params['theme']); ?>\"><?php echo esc_html($theme); ?></option>");

						jQuery(".donelink").click(function() {
							if (window.parent && window.parent.tb_remove) {
								window.parent.tb_remove();
							}
						});
<?php
				}
				else
				{
?>
						jQuery(".donelink").click(function(e) {
							if (window.parent) {
								e.preventDefault();
								window.parent.location.href = jQuery(this).attr("href");
							}
						});
<?php
				}
?>
					</script>
<?php
				wp_ob_end_flush_all();
				flush();

				do_action( 'upgrader_process_complete', $upgrader, array( 'action' => 'install', 'type' => 'theme' ), $dladdress );
			}
			$skin->footer();
		}

		function _analytics($params)
		{
			return '/themes/'.(((isset($params['theme_global']) && $params['theme_global'] === 'true') || $params['theme'] === 'global')?(($params['theme'] !== 'global')?$params['theme'].'/':'').'global':$params['theme']).'/purchased';
		}

		function loadAdmin()
		{
			add_action('wp_ajax_wiziapp_plugin_theme_list', array(&$this, 'theme_list'));
		}

		function theme_list()
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
				wiziapp_plugin_hook()->json_output(array());
			}
			$themes = wiziapp_plugin_module_switcher()->get_themes(false);
			$name_map = array();
			foreach ($themes as $theme)
			{
				$name_map[$theme['Stylesheet']] = __($theme['Name'], $theme['TextDomain']);
			}
			foreach ($res as $theme)
			{
				if (isset($name_map[$theme['name']]))
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
				$name_map[$theme['name']] = wp_kses($theme['title'], $header_tags);
			}
			foreach ($res as $key => $theme)
			{
				if (isset($themes[$theme['name']]))
				{
					$theme['installed'] = true;
					if (isset($theme['version']) && $themes[$theme['name']]['Version'] != $theme['version'])
					{
						$theme['need_update'] = true;
					}
				}
				$screenshots = $theme['screenshots'];
				$theme['screenshots'] = array();
				for ($i = 0; $i < $screenshots; )
				{
					$i++;
					$theme['screenshots'][] = $wiziapp_plugin_config['build_host'].'/theme/screenshot/'.$i.'/'.$theme['name'].'?url='.$siteurl;
				}
				if (isset($theme['parent']))
				{
					$theme['parent'] = array('name' => $theme['parent'], 'title' => isset($name_map[$theme['parent']])?$name_map[$theme['parent']]:'', 'installed' => isset($themes[$theme['parent']]));
				}
				if (isset($theme['title']))
				{
					static $header_tags = array(
						'abbr'    => array( 'title' => true ),
						'acronym' => array( 'title' => true ),
						'code'    => true,
						'em'      => true,
						'strong'  => true,
					);
					$theme['title'] = wp_kses($theme['title'], $header_tags);
				}
				else
				{
					$theme['title'] = esc_html($theme['name']);
				}
				if (isset($theme['description']))
				{
					static $header_tags_with_a = array(
						'a'       => array( 'href' => true, 'title' => true ),
						'abbr'    => array( 'title' => true ),
						'acronym' => array( 'title' => true ),
						'code'    => true,
						'em'      => true,
						'strong'  => true,
					);
					$theme['description'] = wptexturize(wp_kses($theme['description'], $header_tags_with_a));
				}
				$packages = array();
				foreach ($theme['packages'] as $p)
				{
					$p['description'] = ($p['count'] > 1)?sprintf(__('%1$d licenses $%2$d', 'wiziapp-plugin'), $p['count'], $p['price']):sprintf(__('This Theme Only $%2$d/One Time', 'wiziapp-plugin'), $p['count'], $p['price']);
					$p['theme'] = $theme['name'];
					$p['theme_title'] = $theme['title'];
					$packages[] = $p;
					break;
				}
				if (isset($theme['global_packages']))
				{
					$p = $theme['global_packages'][0];
					$p['theme_title'] = $p['description'];
					$p['description'] = sprintf(__('%1$s (All Themes) $%2$d/Year', 'wiziapp-plugin'), $p['description'], $p['price']);
					$p['theme'] = 'global.'.$theme['name'];
					$packages[] = $p;
				}
				$theme['packages'] = $packages;
				$res[$key] = $theme;
			}
			$licenses = array();
			foreach ($res as $theme)
			{
				if (isset($theme['name']) && isset($theme['license']))
				{
					$licenses[$theme['name']] = $theme['license'];
				}
			}
			wiziapp_plugin_theme_licenses()->setThemeLicenses($licenses);
			wiziapp_plugin_hook()->json_output(array_values($res));
		}
	}

	$module = new WiziappPluginModuleThemePurchase();
	$module->init();
