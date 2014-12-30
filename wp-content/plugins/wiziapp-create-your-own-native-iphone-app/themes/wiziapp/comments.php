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
	if ($comment_link)
	{
?>
<div class="wiziapp-add-comment">
	<span></span>
	<a href="<?php echo esc_attr($comment_link); ?>" data-transition="slide">
		<?php _e('Add new comment', 'wiziapp-smooth-touch'); ?>
	</a>
</div>
<?php
	}
?>
<ul data-role="listview" class="wiziapp-content-list wiziapp-content-comment-list">
<?php
	if ($query->haveComments())
	{
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
	}
	else
	{
		// TODO - Display if no comments are available
	}
?>
</ul>

<?php
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
