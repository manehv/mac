<?php
	require_once(dirname(dirname(__FILE__)).'/includes/settings.php');
	require_once(dirname(dirname(__FILE__)).'/includes/hook.php');
	require_once(dirname(dirname(__FILE__)).'/includes/purchase_hook.php');
	require_once(dirname(__FILE__).'/switcher.php');

	class WiziappPluginModuleAndroid
	{
		function init()
		{
			$hook = new WiziappPluginPurchaseHook();
			$hook->hook('build_android', '/build/android', array(&$this, '_licensed'), array(&$this, '_analytics'));
			$hook->hookExpiration(array(&$this, '_expiration'));
			wiziapp_plugin_module_switcher()->hookGetTheme(array($this, 'getTheme'));
			wiziapp_plugin_hook()->hookLoad(array(&$this, 'load'));
			wiziapp_plugin_hook()->hookLoadAdmin(array(&$this, 'loadAdmin'));
			wiziapp_plugin_hook()->hookInstall(array(&$this, 'build'));
		}

		function load()
		{
			if (!isset($_GET['wiziapp_plugin']) || $_GET['wiziapp_plugin'] !== 'build' || !isset($_FILES['result']))
			{
				return;
			}
			if (!isset($_GET['wiziapp_plugin_token']) || $_GET['wiziapp_plugin_token'] !== wiziapp_plugin_settings()->getAndroidBuildToken())
			{
				wiziapp_plugin_hook()->json_output(array('error' => 'invalid_token'));
			}
			if (!isset($_GET['wiziapp_plugin_package']) || !is_string($_GET['wiziapp_plugin_package']))
			{
				wiziapp_plugin_hook()->json_output(array('error' => 'missing_package'));
			}
			if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
			add_filter('upload_mimes', array(&$this, 'upload_mimes'));
			$upload_overrides = array('test_form' => false);
			$_FILES['result']['name'] = $_SERVER['SERVER_NAME'].'.apk';
			$uploaded_file = wp_handle_upload($_FILES['result'], $upload_overrides);
			if(!isset($uploaded_file['file']))
			{
				wiziapp_plugin_hook()->json_output(array('error' => 'upload_failed'));
				exit;
			}
			wiziapp_plugin_settings()->setAndroidDownload($uploaded_file['file'], $_GET['wiziapp_plugin_package']);
			wiziapp_plugin_settings()->setAndroidBuildToken(false);
			wiziapp_plugin_hook()->json_output(array('success' => true));
		}

		function _licensed()
		{
			require(dirname(dirname(__FILE__)).'/config.php');
			$siteurl = trailingslashit(get_bloginfo('wpurl'));
			$response = wp_remote_get($wiziapp_plugin_config['build_host'].'/build/android/license/expiration?url='.urlencode($siteurl));
			if (!is_wp_error($response))
			{
				$res = json_decode($response['body'], true);
				if (is_array($res) && isset($res['expiration']))
				{
					wiziapp_plugin_settings()->setAndroidExpiration($res['expiration']);
?>
?>
					<script type="text/javascript">
						if (window.parent && window.parent.jQuery) {
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-android-body-buy").removeClass("wiziapp-plugin-admin-settings-box-body-active");
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-android-body").addClass("wiziapp-plugin-admin-settings-box-body-active");
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-option-android_license-state-licensed").text(new Date(<?php echo json_encode($res['expiration']); ?>).toString());
							window.parent.jQuery("#wiziapp-plugin-admin-settings-box-option-android_app-state-need-build.wiziapp-plugin-admin-settings-box-value-state-active input").click();
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
			wiziapp_plugin_settings()->setAndroidExpiration($expiration['expiration']);
			return $expiration;
		}

		function _analytics()
		{
			return '/android/purchased';
		}

		function loadAdmin()
		{
			add_action('wp_ajax_wiziapp_plugin_android_build', array(&$this, 'build_ajax'));
			$expire = wiziapp_plugin_settings()->getAndroidExpiration();
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
				<?php _e('The WiziApp Android App license will expire in less than a month. To extend it for additional one year, please click the "Extend" button on the Wiziapp plugin - "Settings" - "Android App".', 'wiziapp-plugin'); ?>
			</p>
		</div>
<?php
		}

		function getTheme()
		{
			if (!wiziapp_plugin_settings()->getAndroidActive())
			{
				return false;
			}
			if (!isset($_SERVER['HTTP_USER_AGENT']) || ($_SERVER['HTTP_USER_AGENT'] !== '72dcc186a8d3d7b3d8554a14256389a4' && stripos($_SERVER['HTTP_USER_AGENT'], 'wiziapp_user_agent=android_app') === false))
			{
				if (wiziapp_plugin_settings()->getAndroidIntroScreen() && wiziapp_plugin_settings()->getAndroidDownload())
				{
					add_action('wp_head', array(&$this, 'wp_head'));
				}
				return false;
			}
			return array('theme' => wiziapp_plugin_settings()->getAndroidTheme(), 'menu' => wiziapp_plugin_settings()->getAndroidMenu(), 'extras' => array('no_ads' => true));
		}

		function build()
		{
			require(dirname(dirname(__FILE__)).'/config.php');
			$response = wp_remote_post($wiziapp_plugin_config['build_host'].'/build', array(
				'body' => array(
					'url' => trailingslashit(get_bloginfo('wpurl')),
					'name' => wiziapp_plugin_settings()->getAndroidName(),
					'icon' => wiziapp_plugin_settings()->getAndroidIcon(),
					'splash' => wiziapp_plugin_settings()->getAndroidSplash(),
					'sender_id' => wiziapp_plugin_settings()->getAndroidPushSenderId(),
					'user_agent' => 'Mozilla/5.0 (Linux; U; Android 4.0.2; en-us; Galaxy Nexus Build/ICL53F) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 wiziapp_user_agent=android_app',
					'admob_id' => wiziapp_plugin_settings()->getAndroidAdmobId()
				), 'timeout' => 90
			));
			if (is_wp_error($response))
			{
				return 'request_failed';
			}
			$res = json_decode($response['body'], true);
			if (!is_array($res) || empty($res['success']) || !isset($res['token']) || !is_string($res['token']))
			{
				return 'request_failed';
			}
			wiziapp_plugin_settings()->setAndroidBuildToken($res['token']);
			wiziapp_plugin_settings()->setAndroidNeedBuild(false);
			return true;
		}

		function build_ajax()
		{
			$ret = $this->build();
			if ($ret === true)
			{
				wiziapp_plugin_hook()->json_output(array('success' => true));
			}
			wiziapp_plugin_hook()->json_output(array('error' => $ret));
		}

		function wp_head()
		{
			$published = wiziapp_plugin_settings()->getAndroidPublished();

			echo '<script type="text/javascript">'.PHP_EOL;
			// Outputs minified intro screen JavaScript - Do not alter
			echo '(function(){';
				echo 'var d=document,w=window,o=!1,t,x,y,z;';

				// Browser detect
				echo 'if(!/android/.test(navigator.userAgent.toLowerCase()) || /(;|^)\\s*wiziapp_android_seen\\s*\\=\\s*true\\s*(;|$)/.test(d.cookie))';
					echo 'return;';

				echo 'd.cookie="wiziapp_android_seen=true";';

				// OnReady detect
				echo 'if(d.readyState==="complete")';
					echo 'r();';
				echo 'else if(d.addEventListener){';
					echo 'd.addEventListener("DOMContentLoaded",r,o);';

					// Safety fallback: onLoad
					echo 'w.addEventListener("load",r,o);';
				echo '}';
				echo 'else{';
					echo 'd.attachEvent("onreadystatechange",r);';

					// Safety fallback: onLoad
					echo 'w.attachEvent("onload",r);';

					// Repeatedly test doScroll
					echo 'try{';
						echo 't=w.frameElement;';
						echo 'if(!t)t=d.documentElement;';
						echo 'if(t.doScroll)';
							echo '(function s(){';
								echo 'try{';
									echo 't.doScroll("left");';
									echo 'r();';
								echo '}';
								echo 'catch(e){';
									echo 'setTimeout(s,50);';
								echo '}';
							echo '})();';
					echo '}';
					echo 'catch(e){';
					echo '}';
				echo '}';

				// Ready handler
				echo 'function r(){';
					// Only activate once
					echo 'if(o)';
						echo 'return;';
					echo 'o=!0;';

					// Create our layer
					echo 'y=d.createElement("div");';
					echo 'y.setAttribute("style", "display:block;position:absolute;top:0;bottom:0;left:0;right:0;width:100%;height:100%;background:url("+'.json_encode(wiziapp_plugin_hook()->plugins_url('/images/intro/bg.png')).'+") #e9e7e9;z-index:9999;border:none;padding:0;margin:0;border-radius:0;text-decoration:none;cursor:default;float:none");';
					echo 'y.innerHTML="';
						echo '\\x3cdiv style=\\"display:block;position:static;width:auto;height:auto;background:url("+'.json_encode(wiziapp_plugin_hook()->plugins_url('/images/intro/bg.png')).'+");border:none;border-radius:0;padding:10px;margin:0;text-align:center;color:#555;font:bold 14px sans-serif;text-decoration:none;cursor:default;float:none\\"\\x3e';
							echo __('Get the ultimate Android experience with our new App!', 'wiziapp-plugin');
							echo '\\x3cdiv style=\\"display:block;position:relative;top:0;bottom:0;left:0;right:0;width:260px;height:130px;background:url("+'.json_encode(wiziapp_plugin_hook()->plugins_url($published?'/images/intro/gp.png':'/images/intro/ic.png')).'+") center 10px no-repeat;padding:0;margin:10px auto;border-radius:0;border:none;cursor:default;float:none\\"\\x3e';
								echo '\\x3cdiv style=\\"display:block;position:static;float:left;padding:0;margin:0;border-radius:0;border:none;width:30px;height:43px;background:url("+'.json_encode(wiziapp_plugin_hook()->plugins_url('/images/intro/al.png')).'+");cursor:default\\"\\x3e\\x3c/div\\x3e';
								echo '\\x3cdiv style=\\"display:block;position:static;float:right;padding:0;margin:0;border-radius:0;border:none;width:30px;height:43px;background:url("+'.json_encode(wiziapp_plugin_hook()->plugins_url('/images/intro/ar.png')).'+");cursor:default\\"\\x3e\\x3c/div\\x3e';
			if ($published)
			{
								echo '\\x3cdiv style=\\"display:block;position:absolute;top:33px;bottom:60px;left:75px;right:40px;width:auto;height:auto;background:none;border:none;border-radius:0;padding:0;margin:0;text-align:center;color:#fff;font:bold 20px serif;text-decoration:none;cursor:default;float:none\\"\\x3e'.__('Google Play', 'wiziapp-plugin').'\\x3c/div\\x3e';
								echo '\\x3ca style=\\"display:block;position:absolute;top:10px;bottom:50px;left:30px;right:30px;margin:0;padding:0;width:200px;height:70px;border-radius:0;border:none;background:none;cursor:pointer\\" rel=\\"external\\" href=\\""+'.json_encode('https://play.google.com/store/apps/details?id='.urlencode(wiziapp_plugin_settings()->getAndroidPackage())).'+"\\"\\x3e\\x3c/a\\x3e';
			}
			else
			{

								echo '\\x3cdiv style=\\"display:block;position:absolute;top:35px;bottom:60px;left:75px;right:40px;width:auto;height:auto;background:none;border:none;border-radius:0;padding:0;margin:0;text-align:center;color:#fff;font:bold 18px sans-serif;text-decoration:none;cursor:default;float:none\\"\\x3e'.__('Download Now', 'wiziapp-plugin').'\\x3c/div\\x3e';
								echo '\\x3ca style=\\"display:block;position:absolute;top:10px;bottom:50px;left:30px;right:30px;margin:0;padding:0;width:200px;height:70px;border-radius:0;border:none;background:none;cursor:pointer\\" rel=\\"external\\" href=\\""+'.json_encode(esc_attr(wiziapp_plugin_settings()->getAndroidDownload())).'+"\\"\\x3e\\x3c/a\\x3e';
			}
							echo '\\x3c/div\\x3e';
							echo __('No thanks. Continue to:', 'wiziapp-plugin');
							echo '\\x3cdiv style=\\"display:block;position:static;width:200px;height:auto;margin:10px auto 0;padding:10px 0px;background:#777;color:#fff;font:bold 14px sans-serif;border-radius:5px;text-decoration:none;border:none;cursor:pointer;float:none\\"\\x3e'.__('Mobile Site', 'wiziapp-plugin').'\\x3c/div\\x3e';
						echo '\\x3c/div\\x3e';
					echo '";';

					echo 'function a() {';
						// Get the close button div
						echo 'x=y.lastChild;';
						echo 'while((x.tagName||"").toLowerCase()!="div")';
							echo 'x=x.previousSibling;';
						echo 'x=x.lastChild;';
						echo 'while((x.tagName||"").toLowerCase()!="div")';
							echo 'x=x.previousSibling;';

						// Apply hide click handler
						echo 'c(x,function(){';
							echo 'y.parentNode.removeChild(y);';
						echo '});';
					echo '}';

					echo 'a();';

			if (!$published)
			{
					// Get the link
					echo 'x=x.previousSibling;';
					echo 'while((x.tagName||"").toLowerCase()!="div")';
						echo 'x=x.previousSibling;';
					echo 'x=x.lastChild;';
					echo 'while((x.tagName||"").toLowerCase()!="a")';
						echo 'x=x.previousSibling;';

					// Apply download click handler
					echo 'c(x,function(){';
						echo 'setTimeout(function(){';
							echo 'y.innerHTML="';
								echo '\\x3cdiv style=\\"display:block;position:static;width:auto;height:auto;background:url("+'.json_encode(wiziapp_plugin_hook()->plugins_url('/images/intro/bg.png')).'+");border:none;border-radius:0;padding:10px;margin:0;text-align:center;color:#555;font:bold 14px sans-serif;text-decoration:none;cursor:default;float:none\\"\\x3e';
									echo '\\x3cdiv style=\\"display:block;position:relative;top:0;bottom:0;left:0;right:0;width:auto;height:100px;background:url("+'.json_encode(wiziapp_plugin_hook()->plugins_url('/images/intro/au.png')).'+") left top no-repeat;padding:0;margin:0;border-radius:0;border:none;cursor:default;float:none\\"\\x3e\\x3c/div\\x3e';
									echo '\\x3cdiv style=\\"display:block;position:relative;top:0;bottom:0;left:0;right:0;width:auto;height:60px;background:url("+'.json_encode(wiziapp_plugin_hook()->plugins_url('/images/intro/in.png')).'+") left top no-repeat;padding:0 0 0 60px;margin:10px;text-align:left;color:#000;font:bold 16px sans-serif;text-decoration:none;border-radius:0;border:none;cursor:default;float:none\\"\\x3e';
										echo __('Please pull-down your notification center to complete the installation', 'wiziapp-plugin');
									echo '\\x3c/div\\x3e';
									echo __('Continue to:', 'wiziapp-plugin');
									echo '\\x3cdiv style=\\"display:block;position:static;width:200px;height:auto;margin:10px auto 0;padding:10px 0px;background:#777;color:#fff;font:bold 14px sans-serif;border-radius:5px;text-decoration:none;border:none;cursor:pointer;float:none\\"\\x3e'.__('Mobile Site', 'wiziapp-plugin').'\\x3c/div\\x3e';
								echo '\\x3c/div\\x3e';
							echo '";';
							echo 'a();';
						echo '},1000);';
					echo '});';
			}

					// Append to body
					echo 'd.body.appendChild(y);';

					// Click handler
					echo 'function c(e,l){';
						echo 'if(e.addEventListener)';
							echo 'e.addEventListener("click",l,!1);';
						echo 'else ';
							echo 'e.attachEvent("onclick",l);';
					echo '}';
				echo '}';
			echo '})();';
			echo PHP_EOL.'</script>'.PHP_EOL;
		}

		function upload_mimes($mime_types = array())
		{
			$mime_types['apk'] = 'application/vnd.android.package-archive';
			return $mime_types;
		}
	}

	$module = new WiziappPluginModuleAndroid();
	$module->init();
