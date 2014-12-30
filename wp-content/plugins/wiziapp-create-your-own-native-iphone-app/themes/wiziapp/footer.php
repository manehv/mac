			</div>
<?php
	if (wiziapp_theme_is_in_plugin() && wiziapp_plugin_settings()->getAdFooter() && !wiziapp_plugin_module_switcher()->getExtra('no_ads'))
	{
?>
			<div data-role="footer" data-id="footer" data-position="fixed" data-tap-toggle="false" class="wiziapp-footer">
				<div>
					<iframe src="<?php echo esc_attr(wiziapp_plugin_settings()->getAdFooter()); ?>" width="320" height="50" style="display:block;width:320px;height:50px;margin:0 auto;border:none"></iframe>
				</div>
			</div>
<?php
	}
	switch (wiziapp_theme_settings()->getMenuType())
	{
		case 'popup':
		case 'panel':
			$display = (wiziapp_theme_settings()->getMenuType() == 'popup')?'overlay':'push';
?>
			<div data-role="panel" id="left-panel" class="wiziapp-panel" data-display="<?php echo esc_attr($display); ?>" data-position-fixed="true">
<?php
			if (has_nav_menu('wiziapp_custom'))
			{
				wp_nav_menu(array(
					'theme_location' => 'wiziapp_custom',
					'container' => false,
					'items_wrap' => '<ul data-role="listview" id="%1$s" class="%2$s">%3$s</ul>',
					'container' => '',
					'fallback_cb' => ''
				));
			}
			else
			{
?>
				<ul data-role="listview">
<?php
					$count = wiziapp_theme_settings()->getMenuItemCount();
					for ($i = 0; $i < $count; $i++)
					{
						echo wiziapp_theme_get_menu_item($i);
					}
?>
				</ul>
<?php
			}
?>
				<div class="wiziapp-menu-links">
					<p>
						Wordpress mobile theme by <a href="http://www.wiziapp.com/" target="_blank" data-rel="external">WiziApp</a>
					</p>
<?php
		if (wiziapp_theme_is_in_plugin())
		{
			foreach (wiziapp_plugin_module_switcher()->getExtra('links', array()) as $title => $link)
			{
?>
					<p>
						<a href="<?php echo $link; ?>" data-ajax="false"><?php echo $title; ?></a>
					</p>
<?php
			}
		}
?>
				</div>
			</div>
<?php
			break;
	}
?>

			<!-- @todo This WP feature is not implemented yet -->
			<div data-role="popup" data-overlay-theme="a" data-position-to="window" data-tolerance="15,15">
				<?php dynamic_sidebar(-1); ?>
			</div>
<?php
	do_action('wiziapp_theme_customized_style');
?>
		</div>
<?php
	wp_footer();
?>
	</body>
</html>