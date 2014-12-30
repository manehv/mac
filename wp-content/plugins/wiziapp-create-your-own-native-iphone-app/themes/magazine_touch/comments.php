<?php
	$query = get_query_var('wiziapp_theme_comments');
	$wiziapp_comments_args = array(
		'callback'      => 'wiziapp_comments_view',
		'wiziapp_query' => $query,
	);
	$comment_link = get_query_var('wiziapp_theme_comment_link');
	$back_data = wiziapp_theme_settings()->getWiziappBack();
	if ($back_data != '')
	{
		$comment_link = add_query_arg('wiziapp_back', $back_data, $comment_link);
	}
	get_header();






	$post_id = $post->ID;
	$reply_to = get_query_var('wiziapp_theme_comment_parent');
	$_GET['replytocom'] = $reply_to;
	$wiziapp_comment_form_fields = array_map('wiziapp_comment_form_fields', array('pre' => '<div class="wiziapp-comment-author">'.PHP_EOL, 'author' => 'author', 'email' => 'email', 'url' => 'url', 'post' => '</div>'));
	$wiziapp_comment_form_args = array(
		'logged_in_as'         => wiziapp_comment_logged_in_as($reply_to, $post_id),
		'comment_notes_after' => '',
		'title_reply'          => '',
		'title_reply_to'       => '',
		'cancel_reply_link'    => ' ',
		'fields'			   => $wiziapp_comment_form_fields,
		'comment_field'        => wiziapp_comment_form_textarea(),
	);

	ob_start();
	comment_form($wiziapp_comment_form_args, $post_id);
	$wiziapp_new_child_comment_form = ob_get_clean();
	$wiziapp_new_child_comment_form = str_replace('<input name="submit"', '<input data-role="none" name="submit"', $wiziapp_new_child_comment_form);
	echo $wiziapp_new_child_comment_form;


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

	function wiziapp_comment_form_textarea()
	{
		ob_start();
?>
		<p class="wiziapp-comment-content">
			<textarea data-role="none" name="comment" id="comment" cols="45" rows="8" placeholder="<?php _e('Type your comment here', 'wiziapp-smooth-touch'); ?>" aria-required="true"></textarea>
		</p>
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
		<p>
			<input data-role="none" type="text" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" size="30" <?php echo $placeholder ?> value="<?php echo esc_attr($commenter['comment_author'.($field_name === 'author'?'':'_'.$field_name)]); ?>" />
			<label for="<?php echo $field_name; ?>">
				<span><?php echo $names[$field_name]; ?></span>
			</label>
		</p>
		<?php

		return ob_get_clean();
	}

















	if ($query->haveComments())
	{
?>
<ul data-role="listview" class="wiziapp-content-list wiziapp-content-comment-list">
<?php
		wp_list_comments($wiziapp_comments_args, $query->comments);

		if ($query->haveMore())
		{
?>
	<li class="wiziapp-show-more-link" data-icon="false">
<?php
			 $wiziapp_more_link = get_next_comments_link( __('Show more...', 'wiziapp-smooth-touch'));
			 if (empty($wiziapp_more_link))
			 {
?>
		<a href="<?php echo esc_attr(get_query_var('wiziapp_theme_more_link')); ?>" data-transition="slide" class="wiziapp-content-list-item">
			<?php _e('Show more...', 'wiziapp-smooth-touch'); ?>

		</a>
<?php
			 }
			 else
			 {
	 			echo $wiziapp_more_link;
			 }
?>
	</li>
<?php
		}
?>
</ul>

<?php
	}
	else
	{
		// TODO - Display if no comments are available
	}
	get_footer();

	function wiziapp_comments_view($the_comment, $args, $depth)
	{
		$query = $args['wiziapp_query'];
		$query->theComment();
?>
	<li class="wiziapp-comment" data-icon="arrow-r">
		<div class="wiziapp-comment-avatar">
			<?php $query->theAvatar(60); ?>

		</div>
		<div class="wiziapp-comment-date">
			<?php $query->theDate(); ?> <?php $query->theTime(); ?>

		</div>
		<div class="wiziapp-comment-author">
			<?php $query->theAuthor(); _e(' says:', 'wiziapp-smooth-touch'); ?>

		</div>
		<div class="wiziapp-comment-text">
			<?php $query->theText(); ?>
		</div>
		<div class="wiziapp-comment-add-comment">
			<a href="<?php $query->theReplyLink(); ?>" data-transition="slide"></a>
		</div>
<?php
		$reply_count = $query->getReplyCount();
		if ($reply_count > 0)
		{
?>
		<div class="wiziapp-collapsible wiziapp-collapsible-comment-replies <?php echo apply_filters('wiziapp_theme_dark_background', 'wiziapp-dark-background'); ?>">
			<span class="wiziapp-collapsible-comment-replies-wedge"></span>
			<a href="#" class="wiziapp-collapsible-header wiziapp-collapsible-header-comment-replies" data-transition="slide">
				<?php echo esc_html(($reply_count > 1)?str_replace('%', $reply_count, __('Show % replies...', 'wiziapp-smooth-touch')):__('Show 1 reply...', 'wiziapp-smooth-touch')); ?>
				<span class="wiziapp-child-theme-icon"></span>
			</a>
			<div class="wiziapp-collapsible-content wiziapp-comment-replies" data-wiziapp-url="<?php $query->theLink(); ?>">
				<div class="wiziapp-comment-replies-loading"></div>
			</div>
		</div>
<?php
		}
	}
