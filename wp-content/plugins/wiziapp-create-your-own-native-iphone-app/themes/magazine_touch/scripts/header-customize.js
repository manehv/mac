/**
 * WiziApp - Smooth Touch
 *
 * License: Distributed under the GPL
 * Copyright: Wiziapp Solutions Ltd, http://www.wiziapp.com
 */

(function($, w, d, undef) {
	$(d).ready(function() {
		var radiobuttons = $("#customize-control-wiziapp_advanced_theme_settings_header_type input");
		radiobuttons.click(function(event) {
			check_shown_elements($(this).val());
		});
		check_shown_elements(radiobuttons.filter(":checked").val());
	});

	function check_shown_elements(type_selected) {
		$("#customize-control-wiziapp_advanced_theme_settings_header_title, #customize-control-wiziapp_advanced_theme_settings_header_color, #customize-control-wiziapp_advanced_theme_settings_header_background")
		.toggle(type_selected === "text");
		$("#customize-control-wiziapp_advanced_theme_settings_header_image")
		.toggle(type_selected === "image");
	}
})(jQuery, window, document);
