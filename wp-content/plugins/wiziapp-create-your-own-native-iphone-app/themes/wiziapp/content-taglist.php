<?php
	$query = get_query_var('wiziapp_theme_terms');
	get_header();
	if ($query->haveTerms())
	{
?>
<ul data-role="listview" class="wiziapp-content-list wiziapp-content-tag-list">
<?php
		get_template_part('list', 'taglist');
?>
</ul>
<?php
	}
	else
	{
		// TODO - Display if no tags are available
	}
	get_footer();
