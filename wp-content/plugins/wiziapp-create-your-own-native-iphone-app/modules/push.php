<?php
	require_once(dirname(dirname(__FILE__)).'/includes/hook.php');
	require_once(dirname(dirname(__FILE__)).'/includes/settings.php');

	class WiziappPluginModulePush
	{
		var $default_push_message = 'New post published';
		var $min_length = 5;
		var $max_length = 105;

		function init()
		{
			wiziapp_plugin_hook()->hookLoad(array(&$this, 'load'));
			wiziapp_plugin_hook()->hookLoadAdmin(array(&$this, 'loadAdmin'));
		}

		function load()
		{
			add_action('transition_post_status', array(&$this, 'transition_post_status'), 10, 3);
			if (!isset($_GET['wiziapp_plugin']) || $_GET['wiziapp_plugin'] !== 'setup_push' || !isset($_GET['wiziapp_plugin_token']) || !isset($_GET['wiziapp_plugin_service']))
			{
				return;
			}
			if (!wiziapp_plugin_settings()->setAndroidPushService($_GET['wiziapp_plugin_token'], $_GET['wiziapp_plugin_service']))
			{
				wiziapp_plugin_hook()->json_output(array('error' => 'invalid_token'));
			}
			wiziapp_plugin_hook()->json_output(array('android_api' => wiziapp_plugin_settings()->getAndroidPushApiKey()));
		}

		function loadAdmin()
		{
			$this->default_push_message = __('New post published', 'wiziapp-plugin');
			if (wiziapp_plugin_settings()->getAndroidPushService() === '')
			{
				$this->setup();
			}
			add_action('transition_post_status', array(&$this, 'transition_post_status'), 10, 3);

			// Define the Wiziapp setting box
			add_action('add_meta_boxes', array($this, 'add_setting_box'));
			// Add Javascripts
			add_action('admin_enqueue_scripts', array($this, 'javascripts'));
			// Save the Push Notification message entered in "Wiziapp plugin setting box" on the Publish post
			add_action('save_post', array($this, 'save_push_message'), 10, 2);
		}

		/**
		 * Adds a box to the main column on the Post edit screens
		 */
		public function add_setting_box()
		{
			add_meta_box(
				'wiziapp_plugin_setting_box',
				'Wiziapp',
				array($this, 'show_setting_box'),
				'',
				'side'
			);
		}

		public function save_push_message($post_id, $post)
		{
			if (!isset($_POST['wiziapp_plugin_push_message']) || wp_is_post_revision($post_id))
			{
				// The Post is a revision
				return;
			}

			$push_message = $_POST['wiziapp_plugin_push_message'];
			$len = function_exists('mb_strlen')?mb_strlen($push_message):strlen($push_message);
			if ($len > $this->max_length)
			{
				return;
			}
			if ($len < $this->min_length)
			{
				$push_message = '';
			}

			$is_send_wiziapp_plugin_push = (isset($_POST['is_send_wiziapp_plugin_push']) && $_POST['is_send_wiziapp_plugin_push'] === '1') ? 'true' : 'false';

			update_post_meta( $post_id, 'wiziapp_plugin_push_message', $push_message );
			update_post_meta( $post_id, 'is_send_wiziapp_plugin_push', $is_send_wiziapp_plugin_push );
		}

		function get_push_message($post_id)
		{
			$wiziapp_plugin_push_message = get_post_meta($post_id, 'wiziapp_plugin_push_message', TRUE);

			if (empty($wiziapp_plugin_push_message))
			{
				return $this->default_push_message;
			}

			return $wiziapp_plugin_push_message;
		}

		function is_send_wiziapp_plugin_push($post_id)
		{
			$is_send_wiziapp_plugin_push = get_post_meta($post_id, 'is_send_wiziapp_plugin_push', TRUE);

			$post_type = get_post_type($post_id);
			if (empty($is_send_wiziapp_plugin_push) && $post_type === "post")
			{
				// The default value for the regular post must be "TRUE".
				$is_send_wiziapp_plugin_push = 'true';
			}

			return $is_send_wiziapp_plugin_push === 'true';
		}

		function javascripts($hook)
		{
			$proper_condition =
			($hook === 'post.php' && isset($_GET['post']) && ctype_digit($_GET['post']) && isset($_GET['action']) && $_GET['action'] === 'edit') ||
			($hook === 'post-new.php');

			if ($proper_condition)
			{
				wp_register_script('wiziapp_plugin_setting_box', wiziapp_plugin_hook()->plugins_url('/scripts/setting_box.js'), array());
				wp_enqueue_script('wiziapp_plugin_setting_box');
			}
		}

		function setup()
		{
			require(dirname(dirname(__FILE__)).'/config.php');
			wp_remote_post($wiziapp_plugin_config['build_host'].'/push/setup', array(
				'body' => array(
					'url' => trailingslashit(get_bloginfo('wpurl')),
					'token' => wiziapp_plugin_settings()->generateAndroidPushSetupToken()
				)
			));
		}

		function send($text)
		{
			$service = wiziapp_plugin_settings()->getAndroidPushService();
			if ($service === '' || wiziapp_plugin_settings()->getAndroidPushSenderId() === '' || wiziapp_plugin_settings()->getAndroidDownload() === false)
			{
				return;
			}
			require(dirname(dirname(__FILE__)).'/config.php');
			wp_remote_post($wiziapp_plugin_config['build_host'].'/push/send', array(
				'body' => array(
					'url' => trailingslashit(get_bloginfo('wpurl')),
					'service' => $service,
					'text' => $text
				)
			));
		}

		function transition_post_status($new_status, $old_status, $post)
		{
			$this->save_push_message($post->ID, $post);
			if ('publish' !== $new_status || 'publish' === $old_status || !$this->is_send_wiziapp_plugin_push($post->ID))
			{
				return;
			}
			$this->send($this->get_push_message($post->ID));
		}

		/**
		 * Prints the box content
		 * @param Object $post
		 */
		public function show_setting_box($post)
		{
?>
<div>
	<input type="checkbox" <?php echo ($this->is_send_wiziapp_plugin_push($post->ID) ? 'checked="checked"' : ''); ?> name="is_send_wiziapp_plugin_push" value="1" />
	Send Push Notification
	<p>Push notification text message:</p>
	<textarea style="width: 100%;" id="wiziapp_plugin_push_message" name="wiziapp_plugin_push_message" data-push-message="<?php echo $this->get_push_message($post->ID); ?>" data-max-length="<?php echo $this->max_length; ?>">
	</textarea>
</div>
<?php
		}
	}

	function &wiziapp_plugin_module_push()
	{
		static $inst = null;
		if (!$inst)
		{
			$inst = new WiziappPluginModulePush();
			$inst->init();
		}
		return $inst;
	}

	wiziapp_plugin_module_push();
