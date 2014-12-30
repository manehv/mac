<?php
	$subsections = array();
	if (wiziapp_theme_settings()->getPostDisplayComments())
	{
		global $post;
		$count = $post->comment_count;
		if ($count > 0 || comments_open())
		{
			$added_query_string = array('wiziapp_display' => 'comments',);
			wiziapp_theme_settings()->prepare_added_query($added_query_string);
			$subsections[str_replace('%', $count, __(apply_filters('wiziapp_theme_comments_title', 'Comments(%)'), 'wiziapp-smooth-touch'))] = add_query_arg($added_query_string, get_permalink());
		}
	}
	if (wiziapp_theme_settings()->getPostDisplayCategories())
	{
		$count = count(wp_get_post_terms(get_the_ID(), 'category', array('fields' => 'ids')));
		if ($count > 0)
		{
			$subsections[str_replace('%', $count, __(apply_filters('wiziapp_theme_categories_title', 'Categories(%)'), 'wiziapp-smooth-touch'))] = add_query_arg('wiziapp_display', 'categories', get_permalink());
		}
	}
	if (wiziapp_theme_settings()->getPostDisplayTags())
	{
		$count = count(wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'ids')));
		if ($count > 0)
		{
			$subsections[str_replace('%', $count, __(apply_filters('wiziapp_theme_tags_title', 'Tags(%)'), 'wiziapp-smooth-touch'))] = add_query_arg('wiziapp_display', 'tags', get_permalink());
		}
	}
	if (!empty($subsections))
	{
		$layout_by_count = array('', ' ui-grid-solo', ' ui-grid-a', ' ui-grid-b', ' ui-grid-c', ' ui-grid-d');
		$layout_by_index = array('ui-block-a', 'ui-block-b', 'ui-block-c', 'ui-block-d', 'ui-block-e');
		$layout = $layout_by_count[count($subsections)];
?>
	<div class="wiziapp-tabs">
		<div class="wiziapp-tabs-header<?php echo $layout; ?>">
<?php
		$index = 0;
		foreach ($subsections as $title => $url)
		{
			$layout = $layout_by_index[$index++];
?>
			<a href="<?php echo esc_attr($url); ?>" class="<?php echo $layout; ?>" data-transition="slide"><?php echo esc_html($title); ?><span></span></a>
<?php
		}
		ob_start();
		// @todo This WP feature is not implemented yet
		the_tags();
		ob_end_clean();
?>
		</div>
		<div class="wiziapp-tabs-content">
			<div class="wiziapp-tabs-content-loading"></div>
		</div>
	</div>
<?php
	}
?>
</div>
<?php
	get_footer();
