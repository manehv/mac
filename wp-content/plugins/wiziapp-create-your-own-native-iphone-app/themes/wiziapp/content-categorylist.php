<?php
	$query = get_query_var('wiziapp_theme_terms');
	get_header();
	if ($query->haveTerms())
	{
?>
<ul data-role="listview" class="wiziapp-content-list wiziapp-content-category-list">
<?php
		get_template_part('list', 'categorylist');
?>
</ul>
<?php
	}
	else
	{
		// TODO - Display if no categories are available
	}
	get_footer();
