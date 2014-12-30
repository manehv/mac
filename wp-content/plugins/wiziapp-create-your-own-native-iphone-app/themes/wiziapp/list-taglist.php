<?php
	$query = get_query_var('wiziapp_theme_terms');
	if (!is_object($query))
	{
		$query = get_query_var('wiziapp_theme_sub_terms');
	}
	if (is_object($query) && $query->haveTerms())
	{
		while ($query->haveTerms())
		{
			$query->theTerm();
?>
	<li class="wiziapp-tag" data-icon="arrow-r">
		<div class="wiziapp-tag-post-count"><?php $query->theCount(__('No posts', 'wiziapp-smooth-touch'), __('1 post', 'wiziapp-smooth-touch'), __('% posts', 'wiziapp-smooth-touch')); ?></div>
		<a href="<?php $query->theLink(); ?>" data-transition="slide" class="wiziapp-content-list-item">
			<?php $query->theName(); ?>

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
	}
