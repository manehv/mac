<?php
	get_header();
	if (have_posts())
	{
?>
<ul data-role="listview" class="wiziapp-content-list wiziapp-content-page-list">
<?php
		while (have_posts())
		{
			the_post();
?>
	<li class="wiziapp-page" data-icon="arrow-r">
		<a href="<?php the_permalink(); ?>" data-transition="slide" class="wiziapp-content-list-item">
			<?php the_title(); ?>

		</a>
	</li>
<?php
			// TODO - Display page link
		}
		if (wiziapp_theme_have_more())
		{
?>
	<li class="wiziapp-show-more-link" data-icon="false">
		<a href="<?php next_posts(); ?>" data-transition="slide" class="wiziapp-content-list-item">
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
		// TODO - Display if no pages are available
	}
	get_footer();
