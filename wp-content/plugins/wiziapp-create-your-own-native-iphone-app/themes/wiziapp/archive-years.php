<?php
	$query = get_query_var('wiziapp_theme_archive_years');
	get_header();
	if ($query->haveArchives())
	{
?>
<ul data-role="listview" class="wiziapp-content-list wiziapp-content-archive-list">
<?php
		while ($query->haveArchives())
		{
			$query->theArchive();
?>
	<li class="wiziapp-archive" data-icon="arrow-r">
		<a href="<?php $query->theLink(); ?>" data-transition="slide" class="wiziapp-content-list-item">
			<div class="wiziapp-archive-calendar"><?php $query->theYear(); ?></div>
			<div class="wiziapp-archive-title"><?php $query->theYear(); ?></div>
			<div class="wiziapp-archive-post-count"><?php $query->theCount(__('No posts', 'wiziapp-smooth-touch'), __('1 post', 'wiziapp-smooth-touch'), __('% posts', 'wiziapp-smooth-touch')); ?></div>
		</a>
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
<?php
	}
	else
	{
		// TODO - Display if no archives are available
	}
	get_footer();
