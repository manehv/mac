(function($,w,d){
	function check() {
		var val = wp.customize.value("wiziapp_plugin");
		if (val && val.get() === "customize") {
			// Hack ahoy: Force value to be dirty, so it is sent to frame
			val._dirty = true;
			wp.customize.unbind("add", check);
		}
	}
	function check_menu() {
		var val = wp.customize.value("wiziapp_theme_menu");
		if (val && val.get() !== "") {
			// Hack ahoy: Force value to be dirty, so it is sent to frame
			val._dirty = true;
			wp.customize.unbind("add", check_menu);
		}
	}
	wp.customize.bind("add", check);
	wp.customize.bind("add", check_menu);
	wp.customize.bind("saved", check);
	check();
	check_menu();
	$(function() {
		$(".wiziapp-plugin-theme-switch").change(function() {
			var h = w.location.href, t = $(this).val(), nh = h.replace(/&theme=[^&]*/, "&theme="+encodeURIComponent(t));
			if (h === nh) {
				return;
			}
			if ($(this).find("option[value="+t.replace(/([^0-9A-Za-z])/g, "\\$1")+"][data-wiziapp-plugin-theme-switch-need-install!=true]").length > 0)
			{
				w.location.href = nh;
				return;
			}
			var title = $(this).prevAll(".customize-control-title").text();

			$.post(ajaxurl, {
				action: "wiziapp_plugin_theme_install",
				theme: t
			}, function(data) {
				if (!data.url) {
					return;
				}
				tb_remove();
				$("#TB_window").stop(true, true);
				tb_show(title, data.url+"&TB_iframe=true&width=800&height=600");
			}, "json");
		});
	});
})(jQuery,window,document);
