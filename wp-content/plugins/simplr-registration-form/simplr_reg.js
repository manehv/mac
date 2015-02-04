// closure to avoid namespace collision
(function(){
	// creates the plugin
	root = simplr.plugin_dir;
	
	tinymce.create('tinymce.plugins.simplr_reg', {
		// creates control instances based on the control's id.
		// our button's id is &quot;reg_button&quot;
		createControl : function(id, controlManager) {
			if (id == 'simplr_reg_button') {
				// creates the button
				var button = controlManager.createButton('simpr_reg_button', {
					title : 'Registration Form', // title of the button
					image : root+'registration_24x24.png',  // path to the button's image
					onclick : function() {
						// do something when the button is clicked :)
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Register Form Options', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=reg-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('simplr_reg', tinymce.plugins.simplr_reg);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		jQuery('#footer').append(function() {
				jQuery(this).after('<div id="hook"></div>');
				jQuery('#hook').load(simplr.plugin_dir + 'simplr_reg_options.php');
			});
	
	});

	
})()
