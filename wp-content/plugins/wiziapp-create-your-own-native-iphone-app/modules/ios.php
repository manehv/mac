<?php
	require_once(dirname(dirname(__FILE__)).'/includes/hook.php');

	class WiziappPluginModuleIOS
	{
		function init()
		{
			$hook = new WiziappPluginPurchaseHook();
			$hook->hook('build_ios', '/build/ios', array(&$this, '_licensed'), array(&$this, '_analytics'));
			add_action('load-admin.php', array(&$this, 'load'));
			if ($GLOBALS['pagenow'] != 'admin.php' || isset($_GET['page']) || !isset($_GET['wiziapp_plugin']) || $_GET['wiziapp_plugin'] !== 'install_ios')
			{
				return;
			}
			add_filter('wp_admin_bar_class', array(&$this, '_wp_admin_bar_class'));
		}

		function load()
		{
			if (!isset($_GET['wiziapp_plugin']) || $_GET['wiziapp_plugin'] !== 'install_ios')
			{
				return;
			}

			require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Need for plugins_api
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php'; // Need for upgrade classes

			$api = plugins_api('plugin_information', array('slug' => 'wiziapp-ios-app', 'fields' => array('sections' => false)));
			if (is_wp_error($api))
			{
				return;
			}

			wp_register_style('wiziapp-plugin-admin', wiziapp_plugin_hook()->plugins_url('/styles/admin.css'), array());

			wp_enqueue_style('wiziapp-plugin-admin');
			wp_enqueue_style( 'colors' );
			wp_enqueue_style( 'ie' );
			wp_enqueue_script('utils');

			header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
			wp_ob_end_flush_all();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
		<title><?php _e('Installing WiziApp iOS plugin', 'wiziapp-plugin'); ?></title>
<?php
			do_action('admin_enqueue_scripts');
			do_action('admin_print_styles');
			do_action('admin_print_scripts');
			do_action('admin_head');
?>
	</head>
	<body>
		<div id="wpwrap">
			<div id="wpbody">
<?php

			$nonce = 'install-plugin_wiziapp-ios-app';
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			$upgrader = new Plugin_Upgrader($skin = new Plugin_Installer_Skin(array('type' => 'web', 'title' => __('Installing WiziApp iOS plugin', 'wiziapp-plugin'), 'url' => $siteurl.'wp-admin/admin.php?wiziapp_plugin=install_ios', 'nonce' => $nonce, 'plugin' => array('name' => 'WiziApp iOS', 'slug' => 'wiziapp-ios-app', 'source' => $api->download_link), 'api' => $api)));

			$upgrader->init();
			$upgrader->install_strings();

			add_filter('upgrader_source_selection', array($upgrader, 'check_package') );

			$upgrader->run( array(
				'package' => $api->download_link,
				'destination' => WP_PLUGIN_DIR,
				'clear_destination' => true,
				'clear_working' => true,
				'hook_extra' => array(
					'type' => 'plugin',
					'action' => 'install',
				)
			) );

			remove_filter('upgrader_source_selection', array($upgrader, 'check_package') );

			if ( $upgrader->result && !is_wp_error($upgrader->result) )
			{
				// Force refresh of plugin update information
				wp_clean_plugins_cache(true);
			}
			wp_cache_flush();
?>
			</div>
		</div>
	</body>
</html>
<?php
		}

		function _wp_admin_bar_class()
		{
			return '';
		}

		function _licensed($params, $license)
		{
?>
					<script type="text/javascript">
						if (window.parent && window.parent.jQuery) {
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-ios-body-buy").removeClass("wiziapp-plugin-admin-settings-box-body-active");
<?php
			if ($license !== false)
			{
?>
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-ios-body-available .wiziapp-plugin-admin-state-available-license").append(window.parent.document.createTextNode(<?php echo json_encode($license); ?>));
<?php
			}
?>
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-ios-body-available").addClass("wiziapp-plugin-admin-settings-box-body-active");
						}
						if (window.parent && window.parent.tb_remove) {
							window.parent.tb_remove();
						}
					</script>
<?php
		}

		function _analytics()
		{
			return '/ios/purchased';
		}
	}

	$module = new WiziappPluginModuleIOS();
	$module->init();
