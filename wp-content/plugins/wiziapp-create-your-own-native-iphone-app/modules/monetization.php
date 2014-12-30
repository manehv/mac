<?php
	require_once(dirname(dirname(__FILE__)).'/includes/settings.php');
	require_once(dirname(dirname(__FILE__)).'/includes/hook.php');
	require_once(dirname(dirname(__FILE__)).'/includes/purchase_hook.php');

	class WiziappPluginModuleMonetization
	{
		function init()
		{
			$hook = new WiziappPluginPurchaseHook();
			$hook->hook('ads', '/ads', array(&$this, '_licensed'), array(&$this, '_analytics'));
			$hook->hookExpiration(array(&$this, '_expiration'));
			wiziapp_plugin_hook()->hookLoadAdmin(array(&$this, 'loadAdmin'));
		}

		function _licensed($params, $license)
		{
			if ($license === false)
			{
				return;
			}
			require(dirname(dirname(__FILE__)).'/config.php');
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			$response = wp_remote_get($wiziapp_plugin_config['build_host'].'/ads/license/expiration?url='.urlencode($siteurl));
			if (!is_wp_error($response))
			{
				$res = json_decode($response['body'], true);
				if (is_array($res) && isset($res['expiration']))
				{
					wiziapp_plugin_settings()->setAdExpiration($res['expiration']);
?>
					<script type="text/javascript">
						if (window.parent && window.parent.jQuery) {
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-monetization-body-buy").hide();
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-option-monetization_license-state-licensed").text(new Date(<?php echo json_encode($res['expiration']); ?>).toString());
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-monetization .wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id=monetization_license]").show();
						}
						if (window.parent && window.parent.tb_remove) {
							window.parent.tb_remove();
						}
					</script>
<?php
				}
			}
		}

		function _expiration($expiration)
		{
			require(dirname(dirname(__FILE__)).'/config.php');
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			$response = wp_remote_get($wiziapp_plugin_config['build_host'].'/theme/license/expiration?url='.urlencode($siteurl).'&theme=global');
			if (!is_wp_error($response))
			{
				$res = json_decode($response['body'], true);
				if (is_array($res) && isset($res['expiration']))
				{
					if ($expiration['expiration'] === false || ($res['expiration'] !== false || $res['expiration'] > $expiration['expiration']))
					{
						$expiration['expiration'] = $res['expiration'];
					}
				}
			}
			wiziapp_plugin_settings()->setAdExpiration($expiration['expiration']);
			return $expiration;
		}

		function _analytics()
		{
			return '/ads/purchased';
		}

		function loadAdmin()
		{
			$expire = wiziapp_plugin_settings()->getAdExpiration();
			if ($expire !== false)
			{
				$expire = strtotime($expire)-time();
				if ($expire > 0 && $expire < 2592000)
				{
					add_action('admin_notices', array(&$this, '_expire_notice'));
				}
			}
		}

		function _expire_notice()
		{
?>
		<div class="error fade">
			<p style="line-height: 150%">
				<?php _e('The WiziApp Ad space license will expire in less than a month. To extend it for additional one year, please click the "Extend" button on the Wiziapp plugin - "Settings" - "Ad Space".', 'wiziapp-plugin'); ?>
			</p>
		</div>
<?php
		}
	}

	$module = new WiziappPluginModuleMonetization();
	$module->init();
