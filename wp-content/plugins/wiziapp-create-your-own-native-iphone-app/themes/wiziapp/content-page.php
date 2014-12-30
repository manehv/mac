<?php
	get_header();
	the_post();
?>
<div class="wiziapp-content-page">
	<div class="wiziapp-page-title"><?php the_title(); ?></div>
<?php
	if (wiziapp_theme_settings()->getPageDisplayAuthor())
	{
		global $authordata;
		if (isset($authordata) && is_object($authordata))
		{
?>
	<div class="wiziapp-page-author">
		<?php _e('By:', 'wiziapp-smooth-touch'); ?> <a href="<?php echo esc_attr(get_author_posts_url( $authordata->ID, $authordata->user_nicename )); ?>" rel="author" data-transition="slide"><?php the_author(); ?></a>

	</div>
<?php
		}
	}
	if (wiziapp_theme_settings()->getPageDisplayDate())
	{
?>
	<div class="wiziapp-page-date"><?php the_date(); ?></div>
<?php
	}
?>
	<div class="wiziapp-page-content">
<?php
	the_content();
	wp_link_pages( array( 'before' => '<div>' . __('Pages:', 'wiziapp-smooth-touch'), 'after' => '</div>' ) );
?>
		<div class="wiziapp-page-content-end"></div>
	</div>
