<?php
	get_header();
?>
<form action="<?php echo esc_url(trailingslashit(home_url())); ?>" method="get">
	<div class="wiziapp-form-search">
		<label class="wiziapp-search-query">
			<input type="text" name="s" value="" />
		</label>
		<div class="wiziapp-search-submit">
			<input name="submit" type="submit" value="<?php _e('Search', 'wiziapp-smooth-touch'); ?>" />
		</div>
	</div>
</form>
<?php
	get_footer();
