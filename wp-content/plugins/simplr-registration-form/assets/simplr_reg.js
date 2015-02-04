// closure to avoid namespace collision
jQuery(function($) {
	// creates the plugin
	simplr = []
	root = userSettings.simplr_plugin_dir;

	$('#insert-registration-form').on('click', function(e) {
		e.preventDefault();
		$.get(root + 'simplr_reg_options.php', function(resp) {
			$('body').append(resp);
			$('#reg-form').show().after('<div class="media-modal-backdrop"></div>');	
		});
	});

});
