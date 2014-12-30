<?php
	require_once(dirname(__FILE__).'/hook.php');

	class WiziappPluginPurchaseHook
	{
		private $type;
		private $api_root;
		private $success_callback;
		private $success_analytics_callback;
		private $install_title_callback;
		private $extra_params;
		private $extra_details;
		private $balance_callback;
		private $expiration_callback;

		function hook($type, $api_root, $success_callback, $success_analytics_callback, $install_title_callback = false, $extra_params = array(), $extra_details = array())
		{
			$this->type = $type;
			$this->api_root = $api_root;
			$this->success_callback = $success_callback;
			$this->success_analytics_callback = $success_analytics_callback;
			$this->install_title_callback = $install_title_callback;
			$this->extra_params = $extra_params;
			$this->extra_details = $extra_details;
			$this->balance_callback = false;
			$this->expiration_callback = false;
			add_action('load-admin.php', array(&$this, 'load'));
			wiziapp_plugin_hook()->hookLoadAdmin(array(&$this, 'loadAdmin'));
			if ($GLOBALS['pagenow'] != 'admin.php' || isset($_GET['page']) || !isset($_GET['wiziapp_plugin']) || !in_array($_GET['wiziapp_plugin'], array($this->type.'_license', $this->type.'_license_cancelled', $this->type.'_license_error')))
			{
				return;
			}
			add_filter('wp_admin_bar_class', array(&$this, '_wp_admin_bar_class'));
		}

		function hookBalance($balance_callback)
		{
			$this->balance_callback = $balance_callback;
		}

		function hookExpiration($expiration_callback)
		{
			$this->expiration_callback = $expiration_callback;
		}

		function load()
		{
			if (!isset($_GET['wiziapp_plugin']) || !in_array($_GET['wiziapp_plugin'], array($this->type.'_license', $this->type.'_license_cancelled', $this->type.'_license_error')))
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
		<title><?php _e('Billing', 'wiziapp-plugin'); ?></title>
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
				<div class="wiziapp-plugin-popup-license">
<?php
			if ($_GET['wiziapp_plugin'] === $this->type.'_license_cancelled')
			{
?>
					<script type="text/javascript">
						if (window.parent && window.parent.tb_remove) {
							window.parent.tb_remove();
						}
					</script>
<?php
			}
			else if ($_GET['wiziapp_plugin'] === $this->type.'_license_error')
			{
?>
					<div class="wrap">
						<h2><?php _e('Billing error', 'wiziapp-plugin') ?></h2>
						<p class="error"><?php _e('An error occurred during the billing process. Please reload the page and try again', 'wiziapp-plugin') ?></p>
					</div>
<?php
			}
			else if ($_GET['wiziapp_plugin'] === $this->type.'_license')
			{
				$params = array();
				foreach ($this->extra_params as $key)
				{
					if (!isset($_GET['wiziapp_plugin_'.$key]) || !is_string($_GET['wiziapp_plugin_'.$key]))
					{
						$params = false;
						break;
					}
					$params[$key] = $_GET['wiziapp_plugin_'.$key];
				}
				if ($params !== false)
				{
					$analytics = call_user_func($this->success_analytics_callback, $params);
					if (!empty($analytics))
					{
?>
					<script type="text/javascript">
						if (window.parent && window.parent.jQuery) {
							window.parent.jQuery(".wiziapp-plugin-admin-container").trigger("track-page", <?php echo json_encode($analytics); ?>);
						}
					</script>
<?php
					}
					$details = array();
					foreach ($this->extra_details as $key)
					{
						if (isset($_GET['wiziapp_plugin_'.$key]) && is_string($_GET['wiziapp_plugin_'.$key]))
						{
							$details[$key] = $_GET['wiziapp_plugin_'.$key];
						}
					}
					$license = (isset($_GET['wiziapp_plugin_license']) && is_string($_GET['wiziapp_plugin_license']))?$_GET['wiziapp_plugin_license']:false;
					call_user_func($this->success_callback, $params, $license, $details);
				}
			}
?>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
			exit;
		}

		function loadAdmin()
		{
			add_action('wp_ajax_wiziapp_plugin_'.$this->type.'_buy', array(&$this, 'buy'));
			add_action('wp_ajax_wiziapp_plugin_'.$this->type.'_license', array(&$this, 'license'));
			add_action('wp_ajax_wiziapp_plugin_'.$this->type.'_license_balance', array(&$this, 'license_balance'));
			add_action('wp_ajax_wiziapp_plugin_'.$this->type.'_license_expiration', array(&$this, 'license_expiration'));
			add_action('wp_ajax_wiziapp_plugin_hash_to_url', array(&$this, 'hash_to_url'));
			if ($this->install_title_callback !== false)
			{
				add_action('wp_ajax_wiziapp_plugin_'.$this->type.'_install', array(&$this, 'install'));
			}
		}

		function buy()
		{
			$params = array();
			$keys = array_merge(array('type', 'package'), $this->extra_params);
			foreach ($keys as $key)
			{
				if (!isset($_POST[$key]) || !is_string($_POST[$key]))
				{
					wiziapp_plugin_hook()->json_output(array('success' => false));
				}
				$params[$key] = $_POST[$key];
			}
			require(dirname(dirname(__FILE__)).'/config.php');
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			$response = wp_remote_post($wiziapp_plugin_config['build_host'].$this->api_root.'/buy', array(
				'body' => array(
					'url' => $siteurl
				)+$params
			));
			if (is_wp_error($response))
			{
				wiziapp_plugin_hook()->json_output(array('success' => false));
			}
			$res = json_decode($response['body'], true);
			if (!is_array($res) || empty($res['success']) || !isset($res['url']))
			{
				wiziapp_plugin_hook()->json_output(array('success' => false));
			}
			wiziapp_plugin_hook()->json_output(array('success' => true, 'url' => $res['url'], 'supports_frame' => !isset($res['supports_frame']) || $res['supports_frame']));
		}

		function license()
		{
			$params = array();
			$keys = array_merge(array('license'), $this->extra_params);
			foreach ($keys as $key)
			{
				if (!isset($_POST[$key]) || !is_string($_POST[$key]))
				{
					wiziapp_plugin_hook()->json_output(array('success' => false));
				}
				$params[$key] = $_POST[$key];
			}
			require(dirname(dirname(__FILE__)).'/config.php');
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			$response = wp_remote_post($wiziapp_plugin_config['build_host'].$this->api_root.'/license', array(
				'body' => array(
					'url' => $siteurl
				)+$params
			));
			if (is_wp_error($response))
			{
				wiziapp_plugin_hook()->json_output(array('success' => false));
			}
			$res = json_decode($response['body'], true);
			if (!is_array($res) || empty($res['success']))
			{
				wiziapp_plugin_hook()->json_output(array('success' => false));
			}
			$params_str = '';
			foreach ($keys as $key)
			{
				$params_str .= '&wiziapp_plugin_'.$key.'='.urlencode($params[$key]);
			}
			foreach ($this->extra_details as $key)
			{
				if (isset($res[$key]) && is_string($res[$key]))
				{
					$params_str .= '&wiziapp_plugin_'.$key.'='.urlencode($res[$key]);
				}
			}
			wiziapp_plugin_hook()->json_output(array('success' => true, 'url' => $siteurl.'wp-admin/admin.php?wiziapp_plugin='.$this->type.'_license'.$params_str));
		}

		function license_balance()
		{
			$balance = array('count' => 0, 'remaining' => 0);
			$params = array();
			$params_str = '';
			foreach ($this->extra_params as $key)
			{
				if (!isset($_POST[$key]) || !is_string($_POST[$key]))
				{
					wiziapp_plugin_hook()->json_output($balance);
				}
				$params[$key] = $_POST[$key];
				$params_str .= '&'.$key.'='.urlencode($_POST[$key]);
			}
			require(dirname(dirname(__FILE__)).'/config.php');
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			$response = wp_remote_get($wiziapp_plugin_config['build_host'].$this->api_root.'/license/balance?url='.urlencode($siteurl).$params_str);
			if (!is_wp_error($response))
			{
				$res = json_decode($response['body'], true);
				if (is_array($res) && isset($res['count']) && isset($res['remaining']))
				{
					$balance = $res;
				}
			}
			if ($this->balance_callback !== false)
			{
				$balance = call_user_func($this->balance_callback, $balance, $params);
			}
			wiziapp_plugin_hook()->json_output($balance);
		}

		function license_expiration()
		{
			$expiration = array('expiration' => false);
			$params = array();
			$params_str = '';
			foreach ($this->extra_params as $key)
			{
				if (!isset($_POST[$key]) || !is_string($_POST[$key]))
				{
					wiziapp_plugin_hook()->json_output($expiration);
				}
				$params[$key] = $_POST[$key];
				$params_str .= '&'.$key.'='.urlencode($_POST[$key]);
			}
			require(dirname(dirname(__FILE__)).'/config.php');
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			$response = wp_remote_get($wiziapp_plugin_config['build_host'].$this->api_root.'/license/expiration?url='.urlencode($siteurl).$params_str);
			if (!is_wp_error($response))
			{
				$res = json_decode($response['body'], true);
				if (is_array($res) && isset($res['expiration']))
				{
					$expiration = $res;
				}
			}
			if ($this->expiration_callback !== false)
			{
				$expiration = call_user_func($this->expiration_callback, $expiration, $params);
			}
			wiziapp_plugin_hook()->json_output($expiration);
		}

		function install()
		{
			$params = '';
			foreach ($this->extra_params as $key)
			{
				if (!isset($_POST[$key]) || !is_string($_POST[$key]))
				{
					return;
				}
				$params .= '&wiziapp_plugin_'.$key.'='.urlencode($_POST[$key]);
			}
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			wiziapp_plugin_hook()->json_output(array('success' => true, 'url' => $siteurl.'wp-admin/admin.php?wiziapp_plugin='.$this->type.'_license'.$params));
		}

		function hash_to_url()
		{
			if (!isset($_POST['hash']))
			{
				return;
			}
			$get = array();
			parse_str($_POST['hash'], $get);
			if (!isset($get['wiziapp_plugin']) || ($get['wiziapp_plugin'] !== $this->type.'_license' && $get['wiziapp_plugin'] !== $this->type.'_license_error'))
			{
				return;
			}
			if ($get['wiziapp_plugin'] === $this->type.'_license')
			{
				foreach ($this->extra_params as $key)
				{
					if (!isset($get['wiziapp_plugin_'.$key]) || !is_string($get['wiziapp_plugin_'.$key]))
					{
						return;
					}
				}
			}
			$params = array();
			$params_str = '';
			$keys = array_merge(array('license'), $this->extra_params, $this->extra_details);
			foreach ($keys as $key)
			{
				if (isset($get['wiziapp_plugin_'.$key]) && is_string($get['wiziapp_plugin_'.$key]))
				{
					$params[$key] = $get['wiziapp_plugin_'.$key];
					$params_str .= '&wiziapp_plugin_'.$key.'='.urlencode($get['wiziapp_plugin_'.$key]);
				}
			}
			$analytics_add = array();
			if ($get['wiziapp_plugin'] === $this->type.'_license')
			{
				$analytics = call_user_func($this->success_analytics_callback, $params);
				if (!empty($analytics))
				{
					$analytics_add['track_page'] = $analytics;
				}
			}
			if ($this->install_title_callback === false)
			{
				wiziapp_plugin_hook()->json_output($analytics_add);
			}
			wiziapp_plugin_hook()->json_output(array(
				'title' => ($get['wiziapp_plugin'] !== $this->type.'_license')?__('Billing error', 'wiziapp-plugin'):call_user_func($this->install_title_callback, $params),
				'url' => (function_exists('admin_url')?admin_url('admin.php'):(trailingslashit(get_bloginfo('wpurl')).'wp-admin/admin.php')).'?wiziapp_plugin='.$this->type.'_license'.$params_str.'&TB_iframe=true&width=800&height=600'
			)+$analytics_add);
		}

		function _wp_admin_bar_class()
		{
			return '';
		}
	}
