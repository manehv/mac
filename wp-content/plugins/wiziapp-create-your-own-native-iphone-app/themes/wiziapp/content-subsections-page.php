<?php
	$subsections = array();
	if (wiziapp_theme_settings()->getPageDisplaySubpages())
	{
		$query = new WP_Query();
		$query->query(array('post_parent' => get_the_ID(), 'post_type' => 'page'));
		$count = $query->found_posts;
		if ($count > 0)
		{
			$subsections[str_replace('%', $count, __('Subpages(%)' ,'wiziapp-smooth-touch'))] = add_query_arg('wiziapp_display', 'subpages', get_permalink());
		}
	}
	if (wiziapp_theme_settings()->getPageDisplayComments())
	{
		global $post;
		$count = $post->comment_count;
		if ($count > 0 || comments_open())
		{
			$subsections[str_replace('%', $count, __('Comments(%)' ,'wiziapp-smooth-touch'))] = add_query_arg('wiziapp_display', 'comments', get_permalink());
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
			<a href="<?php echo esc_attr($url); ?>" class="<?php echo $layout; ?>"><?php echo esc_html($title); ?></a>
<?php
		}
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
