<?php
	$comment = get_query_var('wiziapp_theme_comment');
	$query = get_query_var('wiziapp_theme_comments');
	get_header();
?>
<div class="wiziapp-comment">
	<div class="wiziapp-comment-avatar">
		<?php $comment->theAvatar(60); ?>

	</div>
	<div class="wiziapp-comment-date">
		<?php $comment->theDate(); ?> <?php $query->theTime(); ?>

	</div>
	<div class="wiziapp-comment-author">
		<?php $comment->theAuthor(); _e(' says:', 'wiziapp-smooth-touch'); ?>

	</div>
	<div class="wiziapp-comment-text">
		<?php $comment->theText(); ?>
	</div>
	<div class="wiziapp-comment-add-comment">
		<a href="<?php $comment->theReplyLink(); ?>" data-transition="slide"></a>
	</div>
<?php
	if ($query->haveComments())
	{
		$reply_count = $query->getCount();
?>
	<div class="wiziapp-collapsible wiziapp-collapsible-comment-replies">
		<span class="wiziapp-collapsible-comment-replies-wedge"></span>
		<a href="#" class="wiziapp-collapsible-header wiziapp-collapsible-header-comment-replies" data-transition="slide">
			<?php echo esc_html(($reply_count > 1)?str_replace('%', $reply_count, __('Show % replies...', 'wiziapp-smooth-touch')):__('Show 1 reply...', 'wiziapp-smooth-touch')); ?>
			<span class="wiziapp-child-theme-icon"></span>
		</a>
		<div class="wiziapp-collapsible-content wiziapp-comment-replies">
			<ul data-role="listview" class="wiziapp-content-list wiziapp-content-comment-list">
<?php
		while ($query->haveComments())
		{
			$query->theComment();
?>
				<li class="wiziapp-comment<?php echo (isset($_GET['background']) && $_GET['background'] === '1') ? ' wiziapp-dark-background' : ''; ?>" data-icon="arrow-r">
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
					<div class="wiziapp-collapsible wiziapp-collapsible-comment-replies<?php echo (isset($_GET['background']) && $_GET['background'] === '1') ? ' wiziapp-white-background' : ' wiziapp-dark-background'; ?>">
						<span class="wiziapp-collapsible-comment-replies-wedge<?php echo (isset($_GET['background']) && $_GET['background'] === '1') ? ' wiziapp-white-wedge' : ' wiziapp-dark-wedge'; ?>"></span>
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
?>
				</li>
<?php
		}
		if ($query->haveMore())
		{
?>
				<li class="wiziapp-show-more-link" data-icon="false">
					<a href="<?php echo esc_attr(get_query_var('wiziapp_theme_more_link')); ?>" data-transition="slide" class="wiziapp-content-list-item">
						<?php _e('Show more...', 'wiziapp-smooth-touch'); ?>

					</a>
				</li>
<?php
		}
?>
			</ul>
		</div>
	</div>
</div>
<?php
	}
	else
	{
		// TODO - Display if no comments are available
	}
	get_footer();
