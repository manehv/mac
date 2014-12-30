<?php
	get_header();
	$post_id = get_query_var('wiziapp_theme_comment_post');
	$reply_to = get_query_var('wiziapp_theme_comment_parent');
	$_GET['replytocom'] = $reply_to;
	$wiziapp_comment_form_fields = array_map('wiziapp_comment_form_fields', array('pre' => '<div class="wiziapp-comment-author">'.PHP_EOL, 'author' => 'author', 'email' => 'email', 'url' => 'url', 'post' => '</div>'));
	$wiziapp_comment_form_args = array(
		'comment_notes_before' => wiziapp_comment_notes_before($reply_to, $post_id),
		'logged_in_as'         => wiziapp_comment_logged_in_as($reply_to, $post_id),
		'comment_notes_after' => '',
		'title_reply'          => '',
		'title_reply_to'       => '',
		'cancel_reply_link'    => ' ',
		'fields'			   => $wiziapp_comment_form_fields,
		'comment_field'        => wiziapp_comment_form_textarea(),
	);

	comment_form($wiziapp_comment_form_args, $post_id);

	get_footer();

	function wiziapp_comment_logged_in_as($reply_to, $post_id)
	{
		$link = add_query_arg('wiziapp_display', 'comment', $reply_to?add_query_arg('wiziapp_comment', $reply_to, trailingslashit(get_bloginfo('url'))):get_permalink($post_id));
		$user = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';

		ob_start();
?>
		<div class="wiziapp-comment-header">
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr(wiziapp_theme_settings()->getBackUrl()); ?>" />
			<span><?php echo esc_html(str_replace('%', $user_identity, __('Replying as %', 'wiziapp-smooth-touch'))); ?></span>
			<a href="<?php echo esc_html(wp_logout_url($link)); ?>"><?php _e('Log out?', 'wiziapp-smooth-touch'); ?></a>
		</div>
<?php

		return PHP_EOL.ob_get_clean();
	}

	function wiziapp_comment_notes_before($reply_to, $post_id)
	{
		ob_start();
?>
		<div class="wiziapp-comment-header">
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr(wiziapp_theme_settings()->getBackUrl()); ?>" />
<?php
			if ($reply_to)
			{
?>
			<?php echo esc_html(str_replace('%', get_comment_author($reply_to), __('Reply to %', 'wiziapp-smooth-touch'))); ?>

<?php
			}
			else
			{
				$post = get_post($post_id);
?>
			<?php echo esc_html(str_replace('%', $post->post_title, __('Comment on %', 'wiziapp-smooth-touch'))); ?>

<?php
			}
?>
			<a href="<?php echo wiziapp_theme_settings()->getBackUrl(); ?>" data-transition="slide"><?php _e('Cancel', 'wiziapp-smooth-touch'); ?></a>
		</div>
<?php

		return PHP_EOL.ob_get_clean();
	}

	function wiziapp_comment_form_textarea()
	{
		ob_start();
		?>
			<div class="wiziapp-comment-content">
				<label for="comment">
					<textarea name="comment" id="comment" cols="45" rows="8" placeholder="<?php _e('Type your comment here', 'wiziapp-smooth-touch'); ?>" aria-required="true"></textarea>
				</label>
			</div>
		<?php

		return PHP_EOL.PHP_EOL.ob_get_clean();
	}

	function wiziapp_comment_form_fields($field_name)
	{
		$names = array(
			'author' => __('Name', 'wiziapp-smooth-touch'),
			'email' => __('Email', 'wiziapp-smooth-touch'),
			'url' => __('Website', 'wiziapp-smooth-touch'),
		);
		if (!isset($names[$field_name])) {
			return $field_name;
		}
		$commenter = wp_get_current_commenter();
		$required = get_option('require_name_email');
		$placeholder = '';
		if ($required && $field_name !== 'url')
		{
			$placeholder = 'placeholder="'.__('Required', 'wiziapp-smooth-touch').'" aria-required="true"';
		}

		ob_start();
		?>
		<label for="<?php echo $field_name; ?>">
			<span><?php echo $names[$field_name]; ?>:</span>
			<input type="text" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" size="30" <?php echo $placeholder ?> value="<?php echo esc_attr($commenter['comment_author'.($field_name === 'author'?'':'_'.$field_name)]); ?>" />
		</label>
		<?php

		return ob_get_clean();
	}
