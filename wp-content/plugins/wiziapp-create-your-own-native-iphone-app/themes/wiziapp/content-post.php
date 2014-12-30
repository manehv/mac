<?php
	get_header();
	the_post();
	global $post;
?>
<div <?php post_class('wiziapp-content-post', $post->ID); ?>>
	<div class="wiziapp-post-title"><?php the_title(); ?></div>
<?php
	if (wiziapp_theme_settings()->getPostDisplayAuthor())
	{
		global $authordata;
		if (isset($authordata) && is_object($authordata))
		{
?>
	<div class="wiziapp-post-author">
		<?php _e('By:', 'wiziapp-smooth-touch'); ?> <a href="<?php echo esc_attr(get_author_posts_url( $authordata->ID, $authordata->user_nicename )); ?>" rel="author" data-transition="slide"><?php the_author(); ?></a>

	</div>
<?php
		}
	}
	if (wiziapp_theme_settings()->getPostDisplayDate())
	{
?>
	<div class="wiziapp-post-date"><?php the_date(); ?></div>
<?php
	}
?>
	<div class="wiziapp-post-content">
<?php
	the_content();
	wp_link_pages( array( 'before' => '<div>' . __('Pages:', 'wiziapp-smooth-touch'), 'after' => '</div>' ) );
?>
		<div class="wiziapp-post-content-end"></div>
	</div>
