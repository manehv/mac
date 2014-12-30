<?php
	$query = get_query_var('wiziapp_theme_bookmarks');
	get_header();
	if ($query->haveBookmarks())
	{
?>
<ul data-role="listview" class="wiziapp-content-list wiziapp-content-bookmark-list">
<?php
		while ($query->haveBookmarks())
		{
			$query->theBookmark();
			if ($query->isCategory())
			{
?>
	<li class="wiziapp-bookmark-category" data-role="list-divider" data-icon="none">
		<?php $query->theName(); ?>

	</li>
<?php
			}
			else
			{
?>
	<li class="wiziapp-bookmark" data-icon="arrow-r">
		<a href="<?php $query->theLink(); ?>" rel="external" class="wiziapp-content-list-item">
			<?php $query->theName(); ?>

		</a>
	</li>
<?php
			}
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
<?php
	}
	else
	{
		// TODO - Display if no bookmarks are available
	}
	get_footer();
