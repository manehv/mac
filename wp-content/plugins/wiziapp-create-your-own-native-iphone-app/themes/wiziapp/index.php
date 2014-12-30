<?php
	if (is_object(get_query_var('wiziapp_theme_terms')))
	{
		switch (get_query_var('wiziapp_theme_terms')->getType())
		{
			case 'category':
				get_template_part('content', 'categorylist');
				break;
			default:
				get_template_part('content', 'taglist');
				break;
		}
	}
	else if (is_object(get_query_var('wiziapp_theme_archive_years')))
	{
		get_template_part('archive', 'years');
	}
	else if (is_object(get_query_var('wiziapp_theme_archive_months')))
	{
		wiziapp_theme_settings()->fromMonthList();
		get_template_part('archive', 'months');
	}
	else if (is_object(get_query_var('wiziapp_theme_bookmarks')))
	{
		get_template_part('content', 'bookmarks');
	}
	else if (is_object(get_query_var('wiziapp_theme_comment')))
	{
		wiziapp_theme_settings()->fromComment();
		get_template_part('content', 'comment');
	}
	else if (is_object(get_query_var('wiziapp_theme_comments')))
	{
		wiziapp_theme_settings()->fromSinglePost();
		// Just a trick to use the comments_template()
		$GLOBALS['withcomments'] = true;
		comments_template();
	}
	else if (get_query_var('wiziapp_theme_comment_post'))
	{
		wiziapp_theme_settings()->fromComment();
		get_template_part('form', 'comment');
	}
	else if (get_query_var('wiziapp_theme_search'))
	{
		get_template_part('form', 'search');
	}
	else if (is_page())
	{
		wiziapp_theme_settings()->fromPageList();
		get_template_part('content', 'page');
		get_template_part('content', 'subsections-page');
	}
	else if (is_single())
	{
		wiziapp_theme_settings()->fromSinglePost();
		get_template_part('content', 'post');
		get_template_part('content', 'subsections-post');
	}
	else if (get_query_var('post_type') == 'page')
	{
		get_template_part('content', 'pagelist');
	}
	else
	{
		wiziapp_theme_settings()->fromPostList();
		get_template_part('content', 'postlist');
	}
