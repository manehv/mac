<?php
	get_header();
	if (have_posts())
	{
?>
<ul data-role="listview" class="wiziapp-content-list wiziapp-content-post-list">
<?php
		$terms_query = get_query_var('wiziapp_theme_sub_terms');
		if (is_object($terms_query) && $terms_query->haveTerms())
		{
			switch ($terms_query->getType())
			{
				case 'category':
					get_template_part('list', 'categorylist');
					break;
				default:
					get_template_part('list', 'taglist');
					break;
			}
		}
		$featured = (wiziapp_theme_settings()->getPostListDisplayFeatured() && is_home() && !is_paged())?'wiziapp-post-featured':'wiziapp-post-not-featured';
		$back_data = wiziapp_theme_settings()->getWiziappBack();
		while (have_posts())
		{
			the_post();
			$post_url = ($back_data == '')?get_permalink():add_query_arg('wiziapp_back', $back_data, get_permalink());

			$thumb = array();
			if (wiziapp_theme_settings()->getPostListDisplayThumbnail())
			{
				$thumb[0] = wiziapp_theme_get_post_thumbnail(array(73, 55));
				$thumb[1] = wiziapp_theme_get_post_thumbnail(array(200, 150));
				$thumb[2] = wiziapp_theme_get_post_thumbnail('medium');
				foreach ($thumb as $key => $value)
				{
					if (empty($value))
					{
						unset($thumb[$key]);
					}
				}
			}
			if (empty($thumb))
			{
				$featured = 'wiziapp-post-not-featured';
			}
			else
			{
				$featured = apply_filters('wiziapp_theme_post_featured', $featured);
			}
?>
	<li class="<?php echo $featured; ?>" data-icon="arrow-r">
<?php
			if (wiziapp_theme_settings()->getPostListDisplayCommentsCount() && (comments_open() || get_comments_number()))
			{
?>
		<div class="wiziapp-post-comments-count"><?php wiziapp_theme_the_post_comment_count(); ?></div>
<?php
			}
?>
		<a href="<?php echo $post_url; ?>" data-transition="slide" class="wiziapp-content-list-item">
<?php
			if (!empty($thumb))
			{
?>
			<div class="wiziapp-post-thumbnail">
<?php
				if (!empty($thumb[0]))
				{
?>
				<div class="wiziapp-post-thumbnail-img-small" style="background-image: url(<?php echo esc_attr($thumb[0]); ?>)"></div>
<?php
				}
				if (!empty($thumb[1]))
				{
?>
				<div class="wiziapp-post-thumbnail-img-medium" style="background-image: url(<?php echo esc_attr($thumb[1]); ?>)"></div>
<?php
				}
				if (!empty($thumb[2]))
				{
?>
				<div class="wiziapp-post-thumbnail-img-large" style="background-image: url(<?php echo esc_attr($thumb[2]); ?>)"></div>
<?php
				}
					if (wiziapp_theme_settings()->getPostListDisplayThumbnailOverlay())
					{
?>
				<div class="wiziapp-post-thumbnail-overlay"></div>
<?php
					}
?>
			</div>
<?php
			}
?>
			<div class="wiziapp-post-pre-description"></div>
			<div class="wiziapp-post-description">
				<div class="wiziapp-post-title"><?php the_title(); ?></div>
<?php
			if (wiziapp_theme_settings()->getPostListDisplayDate())
			{
?>
				<div class="wiziapp-post-date"><?php echo apply_filters('wiziapp_theme_post_details', __('Posted', 'wiziapp-smooth-touch').' '); ?><?php the_date(); ?>&nbsp;</div>
<?php
			}
			if (wiziapp_theme_settings()->getPostListDisplayAuthor())
			{
?>
				<div class="wiziapp-post-author"><?php (wiziapp_theme_settings()->getPostListDisplayDate())?_e('by', 'wiziapp-smooth-touch'):_e('By', 'wiziapp-smooth-touch'); ?> <?php the_author(); ?></div>
<?php
			}
?>
			</div>
			<div class="wiziapp-post-content"><?php echo wp_strip_all_tags(get_the_excerpt()); ?></div>
			<div class="wiziapp-post-post-description"></div>
		</a>
	</li>
<?php
			$featured = 'wiziapp-post-not-featured';
		}
		if (wiziapp_theme_have_more())
		{
?>
	<li class="wiziapp-show-more-link" data-icon="false">
<?php
			$next_posts_link_element = get_next_posts_link(__('Show more...', 'wiziapp-smooth-touch'));
			echo str_replace('<a', '<a data-transition="slide" class="wiziapp-content-list-item"', $next_posts_link_element);
?>

	</li>
<?php
		}
?>
</ul>
<?php
	}
	else
	{
		// TODO - Display if no posts are available
	}
	get_footer();
