<?php
	class WiziappThemeControllerAdminSettingCallback
	{
		var $wp_customize;
		var $settings;
		var $setting;
		var $method;
		var $type;

		function init($wp_customize, $settings, $setting, $method, $type = '')
		{
			$this->wp_customize = $wp_customize;
			$this->settings = $settings;
			$this->setting = $setting;
			$this->method = $method;
			$this->type = $type;
			add_action('customize_preview_'.$setting, array($this, 'preview'));
			add_action('customize_update_'.$setting, array($this, 'update'));
			add_filter('customize_value_'.$setting, array($this, 'value'));
		}

		function update($value)
		{
			if ($this->type === 'boolean') {
				$value = !empty($value);
			}
			$method = 'set'.$this->method;
			$this->settings->$method($value);
		}

		function preview()
		{
			$value = $this->wp_customize->get_setting($this->setting)->post_value();
			$method = 'set'.$this->method;
			$this->settings->setPreview();
			$this->settings->$method($value);
		}

		function value()
		{
			$method = 'get'.$this->method;
			return $this->settings->$method();
		}
	}

	class WiziappThemeControllerAdmin
	{
		function admin_init()
		{
			// Abort if not on the nav-menus.php admin UI page - avoid adding elsewhere
			if ($GLOBALS['pagenow'] !== 'nav-menus.php')
			{
				return;
			}
			add_meta_box(
				'wiziapp_pattern_menu',
				'Wiziapp Pattern menu',
				array(&$this, 'render_pattern_menu'),
				'nav-menus',
				'side',
				'low'
			);
		}

		function render_pattern_menu()
		{
			global $_nav_menu_placeholder;
			$_nav_menu_placeholder = ($_nav_menu_placeholder < 0) ? $_nav_menu_placeholder - 1 : -1;
?>
			<div id="wiziapp-pattern">
				<div class="tabs-panel tabs-panel-active">
					<ul class="categorychecklist form-no-clear">
<?php
					$count = wiziapp_theme_settings()->getMenuItemCount();
					for ($i = 0; $i < $count; $i++)
					{
						$_nav_menu_placeholder = $_nav_menu_placeholder - (($i === 0)?$i:1);
?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="1" />
								<?php echo esc_attr(wiziapp_theme_settings()->getMenuItemTitle($i)); ?>
							</label>
							<input type="hidden" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom" />
							<input type="hidden" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo esc_attr(wiziapp_theme_settings()->getMenuItemTitle($i)); ?>" class="menu-item-title" />
							<input type="hidden" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo esc_attr(wiziapp_theme_settings()->getMenuItemActionURL($i)); ?>" class="menu-item-url" />
							<input type="hidden" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-db-id]" value="" />
						</li>
<?php
					}
?>
					</ul>
				</div><!-- /.tabs-panel -->

				<p class="button-controls">
					<span class="list-controls">
						<a href="#wiziapp-pattern" class="select-all">Select All</a>
					</span>
					<span class="add-to-menu">
						<input type="submit" id="submit-wiziapp-pattern" class="button-secondary submit-add-to-menu right" name="add-wiziapp-menu-item" value="Add to Menu" />
						<span class="spinner"></span>
					</span>
				</p>
			</div>
<?php
		}

		function admin_style()
		{
			echo '<link rel="stylesheet" type="text/css" media="all" href="'.get_template_directory_uri() . '/style/admin-style.css'.'" />';
		}

		function preview_init()
		{
			add_action( 'wp_footer', array( $this, 'preview_compatibility' ), 30 );
		}

		function preview_compatibility()
		{
			// We need to disable some annoying "features" of the preview script
?>
		<script type="text/javascript">
			jQuery(function() {
				(function($, d, c) {
					function deep_compare(a, b) {
						if (a === b) {
							return true;
						}
						if (typeof a !== typeof b) {
							return false;
						}
						if (typeof a === typeof []) {
							if (a.length !== b.length) {
								return false;
							}
							for (var i = a.length; i > 0; ) {
								i--;
								if (!deep_compare(a[i], b[i])) {
									return false;
								}
							}
							return true;
						}
						if (typeof a === typeof {}) {
							var i;
							for (i in a) {
								if (!deep_compare(a[i], b[i])) {
									return false;
								}
							}
							for (i in b) {
								if (!(i in a)) {
									return false;
								}
							}
							return true;
						}
						return false;
					}
					var reloading = false;
					c.bind("change", function() {
						var reload = false;
						if ($.mobile.ajaxEnabled) {
							$(d.body).unbind("click.preview submit.preview");
						}
						c.each(function(value, key) {
							if (!deep_compare(value.get(), c.settings.values[key]))
							{
								c.settings.values[key] = value.get();
								reload = true;
							}
						});
						if (reload && !reloading)
						{
							reloading = true;
							setTimeout(function() {
								reloading = false;
								$.mobile.changePage($.mobile.activePage.data('url'), {
									allowSamePageTransition: true,
									transition: "none",
									reloadPage: true
								});
							}, 0);
						}
					});
					$(d).bind("pagebeforeload", function(e, data) {
						data.options.type = "post";
						data.options.data = $.extend(data.options.data || {},
							{
								wp_customize: "on",
								theme: <?php echo json_encode(get_stylesheet()); ?>,
								customized: JSON.stringify(c.settings.values),
								customize_messenger_channel: c.settings.channel,
								nonce: <?php echo json_encode(wp_create_nonce('preview-customize_'.get_stylesheet())); ?>

							}
						);
					});
				})(jQuery, document, wp.customize);
			});
		</script>
<?php
		}

		/**
		 * To add new sections and controls to the Theme Customize screen.
		 *
		 * @see add_action('customize_register',$func)
		 * @param \WP_Customize_Manager $wp_customize
		 */
		function register ($wp_customize)
		{
			$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
			$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
			$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
			$section_priority = 30;
			$sections_details = array(
				array(
					'name' => 'post_list',
					'settings_prefix' => 'PostListDisplay',
					'title' => 'Latest Posts Screen Settings',
					'items' => array('author' => 'Author', 'date' => 'Date', 'comments' => 'CommentsCount', 'thumbnail' => 'Thumbnail', 'featured' => 'Featured', 'thumbnail_overlay' => 'ThumbnailOverlay')
				),
				array(
					'name' => 'post',
					'settings_prefix' => 'PostDisplay',
					'title' => 'Post Screen Settings',
					'items' => array('author' => 'Author', 'date' => 'Date', 'comments' => 'Comments', 'categories' => 'Categories', 'tags' => 'Tags',)
				),
				array(
					'name' => 'page',
					'settings_prefix' => 'PageDisplay',
					'title' => 'Page Screen Settings',
					'items' => array('author' => 'Author', 'date' => 'Date', 'comments' => 'Comments', 'subpages' => 'Subpages',)
				),
			);
			for ($i = 0, $count = count($sections_details); $i < $count; $i++)
			{
				$wp_customize->add_section('wiziapp_'.$sections_details[$i]['name'].'_meta', array(
					'title' => $sections_details[$i]['title'],
					'priority' => $section_priority++,
					'capability' => 'edit_theme_options',
				));
				foreach ($sections_details[$i]['items'] as $item => $settings_suffix)
				{
					$id = 'wiziapp_theme_settings_'.$sections_details[$i]['name'].'_display_'.$item;
					$wp_customize->add_setting($id, array(
						'default' => true,
						'type' => $id,
						'capability' => 'edit_theme_options',
						'transport' => 'postMessage'
					));
					$wp_customize->add_control('wiziapp_'.$sections_details[$i]['name'].'_'.$item, array(
						'label' => 'Show '.implode(' ', array_map('ucfirst', explode('_', $item))),
						'section' => 'wiziapp_'.$sections_details[$i]['name'].'_meta',
						'settings' => $id,
						'type' => 'checkbox',
					));
					$callback = new WiziappThemeControllerAdminSettingCallback();
					$callback->init($wp_customize, wiziapp_theme_settings(), $id, $sections_details[$i]['settings_prefix'].$settings_suffix, 'boolean');
				}
			}
			if (!wiziapp_theme_is_in_plugin())
			{
				$wp_customize->add_section('wiziapp_app_icon', array(
					'title' => 'App Icon',
					'priority' => $section_priority++,
					'capability' => 'edit_theme_options',
				));
				$wp_customize->add_setting('wiziapp_theme_settings_app_icon', array(
					'default' => '',
					'type' => 'wiziapp_theme_settings_app_icon',
					'capability' => 'edit_theme_options',
					'transport' => 'postMessage'
				));
				$icon_select = new WP_Customize_Image_Control($wp_customize, 'wiziapp_theme_settings_app_icon', array(
					'label'    => 'App Icon',
					'section'  => 'wiziapp_app_icon',
				));
				$wp_customize->add_control($icon_select);
				$callback = new WiziappThemeControllerAdminSettingCallback();
				$callback->init($wp_customize, wiziapp_theme_settings(), 'wiziapp_theme_settings_app_icon', 'AppIcon');
			}
			else
			{
				$wp_customize->remove_section('static_front_page');
				$wp_customize->add_section('wiziapp_front_page', array(
					'title'          => __( 'Front Page' ),
					'priority' => $section_priority++,
					'capability' => 'edit_theme_options',
				));
				$wp_customize->add_setting( 'wiziapp_theme_settings_front_page', array(
					'default' => '0',
					'type'       => 'wiziapp_theme_settings_front_page',
					'capability' => 'edit_theme_options',
					'transport' => 'postMessage'
				));

				// Some plugins think that querying posts means there must be a current admin screen. Let's just require the necessary files, just in case
				if (file_exists(ABSPATH . 'wp-admin/includes/screen.php'))
				{
					require_once( ABSPATH . 'wp-admin/includes/screen.php' );
				}

				$cats = array();

				foreach (apply_filters('wiziapp_theme_special_frontpage_list', array()) as $id => $special)
				{
					if (is_string($special))
					{
						$special = array('name' => $special, 'items' => array());
					}
					else if (!isset($special['items']))
					{
						$special['items'] = array();
					}
					$cats['added::'.$id] = $special;
				}

				// Add post types
				$get_posts = new WP_Query;
				$post_types = get_post_types( array( 'show_in_nav_menus' => true ), 'object' );
				if ($post_types)
				{
					foreach ( $post_types as $post_type ) {
						$id = $post_type->name;

						// Posts are too many to list, and shouldn't be used as a frontpage anyway
						if ($id == 'post')
						{
							continue;
						}

						// give pages a higher priority
						$priority = ( 'page' == $id ? 'core' : 'default' );
						$posts = $get_posts->query(array('order' => 'ASC', 'orderby' => 'title', 'posts_per_page' => -1, 'post_type' => $id, 'post_status' => 'publish'));

						$children = array();
						$next = array();
						$index = array();
						foreach ($posts as $post)
						{
							if (!isset($children[$post->post_parent]))
							{
								$children[$post->post_parent] = array();
							}
							$children[$post->post_parent][] = $post->ID;
							$next[$post->ID] = array($post->post_parent, count($children[$post->post_parent]));
							$index[$post->ID] = $post->post_title;
						}

						$items = array();
						$cur = array(0, 0, 1);
						while (isset($children[$cur[0]]) && $cur[1] < count($children[$cur[0]]))
						{
							$item_id = $children[$cur[0]][$cur[1]];
							$items[$item_id] = str_repeat('&nbsp;', $cur[2]*3).$index[$item_id];
							if (!isset($next[$item_id][2]))
							{
								$next[$item_id][2] = $cur[2];
							}
							if (isset($children[$item_id]))
							{
								$next[$children[$item_id][count($children[$item_id])-1]] = $next[$item_id];
								$cur = array($item_id, 0, $cur[2]+1);
							}
							else
							{
								$cur = $next[$item_id];
							}
						}

						$cats[$priority.'::'.$id] = array('name' => $post_type->labels->name, 'items' => $items);
					}
				}

				// Add taxonomies
				$taxonomies = get_taxonomies( array( 'show_in_nav_menus' => true ), 'object' );
				if ($taxonomies)
				{
					foreach ( $taxonomies as $tax ) {
						$id = $tax->name;
						$terms = get_terms($id, array(
							'child_of' => 0,
							'hide_empty' => false,
							'hierarchical' => 1,
							'order' => 'ASC',
							'orderby' => 'name',
						));

						$children = array();
						$next = array();
						$index = array();
						foreach ($terms as $term)
						{
							if (!isset($children[$term->parent]))
							{
								$children[$term->parent] = array();
							}
							$children[$term->parent][] = $term->term_id;
							$next[$term->term_id] = array($term->parent, count($children[$term->parent]));
							$index[$term->term_id] = $term->name;
						}

						$items = array();
						$cur = array(0, 0, 1);
						while (isset($children[$cur[0]]) && $cur[1] < count($children[$cur[0]]))
						{
							$item_id = $children[$cur[0]][$cur[1]];
							$items[$item_id] = str_repeat('&nbsp;', $cur[2]*3).$index[$item_id];
							if (!isset($next[$item_id][2]))
							{
								$next[$item_id][2] = $cur[2];
							}
							if (isset($children[$item_id]))
							{
								$next[$children[$item_id][count($children[$item_id])-1]] = $next[$item_id];
								$cur = array($item_id, 0, $cur[2]+1);
							}
							else
							{
								$cur = $next[$item_id];
							}
						}

						$cats['tax::'.$id] = array('name' => $tax->labels->name, 'items' => $items);
					}
				}

				ksort($cats);
				$items = array('' => __('Default'));
				foreach ($cats as $key => $cat)
				{
					$items[$key] = $cat['name'];
					foreach ($cat['items'] as $id => $value)
					{
						$items[$key.'::'.$id] = $value;
					}
				}

				/*foreach (get_posts(array('post_type' => 'any')) as $post)
				{
					$posts[$post->id] = $post->title;
				}*/
				$wp_customize->add_control( 'wiziapp_theme_settings_front_page', array(
					'label'      => __( 'Front page' ),
					'section'    => 'wiziapp_front_page',
					'type'       => 'select',
					'choices'    => $items
				));
				$callback = new WiziappThemeControllerAdminSettingCallback();
				$callback->init($wp_customize, wiziapp_theme_settings(), 'wiziapp_theme_settings_front_page', 'FrontPage');
			}
		}

		function theme_deactivation()
		{
			wiziapp_theme_settings()->deleteAll();
		}
	}

	$wiziapp_theme_admin = new WiziappThemeControllerAdmin();
	add_action('admin_init', array($wiziapp_theme_admin, 'admin_init'));
	add_action('customize_register', array($wiziapp_theme_admin, 'register'));
	add_action('switch_theme', array($wiziapp_theme_admin, 'theme_deactivation'));
	add_action('customize_controls_print_styles', array($wiziapp_theme_admin, 'admin_style'));
	add_action('customize_preview_init', array($wiziapp_theme_admin, 'preview_init'));
