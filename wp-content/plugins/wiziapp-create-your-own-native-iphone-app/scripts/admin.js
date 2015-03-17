(function($,w,d){
	$(function() {
		$(".wiziapp-plugin-admin-header-tab[id]").click(function() {
			var id = $(this).attr("id").replace(/([^a-zA-Z0-9\-])/, "\\$1");
			var tabid = id.replace("wiziapp-plugin-admin-tab-header", "wiziapp-plugin-admin-tab");
			$(this).addClass("wiziapp-plugin-admin-header-tab-active");
			$(".wiziapp-plugin-admin-header-tab:not(#"+id+")").removeClass("wiziapp-plugin-admin-header-tab-active");
			$("#"+tabid).addClass("wiziapp-plugin-admin-tab-active");
			$(".wiziapp-plugin-admin-tab:not(#"+tabid+")").removeClass("wiziapp-plugin-admin-tab-active");
			$("#"+tabid).trigger("wiziapp-plugin-admin-tab-shown");
		});
		$(".wiziapp-plugin-admin-settings-box-value-switch").click(function() {
			$(this).toggleClass("wiziapp-plugin-admin-settings-box-value-switch-on");
			$(this).trigger($(this).hasClass("wiziapp-plugin-admin-settings-box-value-switch-on")?"switch-on":"switch-off");
			tb_remove();
			$("#TB_window").stop(true, true);
			tb_show("", "#TB_inline?width=600&height=80&inlineId=wiziapp-plugin-admin-settings-box-change-note");
		});

		// Fix for thickbox stupidity
		$("#wiziapp-plugin-admin-upgraded a.thickbox").click(function() {
			tb_remove();
			$("#TB_window").stop(true, true);
		});

		var trackPage = (function() {
			var analytics_url = $(".wiziapp-plugin-admin-container").attr("data-wiziapp-plugin-analytics-url");
			var queue = [];
			var inprogress = false;
			function queue_process() {
				if (!queue.length) {
					inprogress = false;
					return;
				}
				queue.shift()(queue_process);
			}
			function queue_async(func) {
				queue.push(func);
				if (inprogress) {
					return;
				}
				inprogress = true;
				queue_process();
			}
			return function(page, done) {
				queue_async(function(cb) {
					var f = $("<iframe style=\"position:absolute; top: -100px; height: 1px;\" height=\"1\">");
					w.wiziapp_analytics_complete = function() {
						f.remove();
						if (done) {
							done();
						}
						return cb();
					};
					f.attr("src", analytics_url+encodeURIComponent(page));
					$("body").append(f);
				});
			};
		})();

		var post_ajax = (function() {
			var ajax_queue = [];
			var ajax_inprogress = false;
			function do_call(data, done)
			{
				$.post(ajaxurl, data, function(retdata) {
					if (ajax_queue.length > 0)
					{
						var queue_next = ajax_queue.shift();
						do_call(queue_next[0], queue_next[1]);
					}
					else
					{
						ajax_inprogress = false;
					}
					done(retdata);
				}, "json");
			}
			return function(data, done) {
				if (ajax_inprogress) {
					ajax_queue.push([data, done]);
					return;
				}
				ajax_inprogress = true;
				do_call(data, done);
			};
		})();

		function updater(name) {
			this._name = name;
			this._handlers = {};
		}
		updater.prototype = {
			_ajaxPending: false,
			_isValuePending: false,
			_prevValue: false,
			_handlers: false,
			force: function(newvalue) {
				var me = this;
				me._trigger("updating", newvalue);
				if (me._ajaxPending) {
					return;
				}
				me._prevValue = newvalue;
				me._trigger("updated", me._prevValue);
			},
			update: function(newvalue) {
				var me = this;
				if (me._prevValue === newvalue)
				{
					if (me._isValuePending) {
						me._trigger("updating", newvalue);
						me._isValuePending = false;
					}
					return;
				}
				me._trigger("updating", newvalue);
				if (me._ajaxPending) {
					me._valuePending = newvalue;
					me._isValuePending = true;
					return;
				}
				me._prevValue = newvalue;
				me._ajaxPending = true;
				function ajaxdone(data) {
					if ("value" in data) {
						me._prevValue = data["value"];
						if (me._isValuePending && me._valuePending === me._prevValue) {
							me._isValuePending = false;
						}
					}
					if ("states" in data) {
						var i;
						for (i in data["states"]) {
							updaterForName(data["states"][i]).update("");
						}
					}
					if ("extra" in data) {
						me._trigger("extra", data["extra"]);
					}
					if (!me._isValuePending) {
						me._ajaxPending = false;
						me._trigger("updated", me._prevValue);
						return;
					}
					me._isValuePending = false;
					me._prevValue = me._valuePending;
					post_ajax({
						action: "wiziapp_plugin_admin_settings_update",
						name: me._name,
						value: me._valuePending
					}, ajaxdone);
				}
				post_ajax({
					action: "wiziapp_plugin_admin_settings_update",
					name: me._name,
					value: newvalue
				}, ajaxdone);
			},
			bind: function(event, handler) {
				if (!this._handlers[event]) {
					this._handlers[event] = [];
				}
				this._handlers[event].push(handler);
				if (event === "updated" && !this._ajaxPending && !this._isValuePending)
				{
					handler(this._prevValue);
				}
			},
			_trigger: function(event) {
				if (!this._handlers[event]) {
					return;
				}
				var handlers = this._handlers[event].slice(0);
				var args = Array.prototype.slice.call(arguments, 1);
				var i;
				for (i = 0; i < handlers.length; i++) {
					handlers[i].apply(this, args);
				}
			}
		};

		var updaters = {};
		function updaterForName(name) {
			if (!updaters[name]) {
				updaters[name] = new updater(name);
			}
			return updaters[name];
		}

		var theme_change = (function() {
			var ud = [updaterForName("webapp_theme"), updaterForName("android_theme")];
			var themes = [false, false];
			var updates = [false, false];
			var i;
			var _handlers = [];
			for (i = 0; i < ud.length; i++)
				(function(i) {
					ud[i].bind("updating", function() {
						updates[i] = true;
						$(".wiziapp-plugin-admin-overlay").show();
					});
					ud[i].bind("updated", function(newval) {
						themes[i] = newval;
						updates[i] = false;
						var j;
						for (j = 0; j < themes.length; j++) {
							if (updates[j])
							{
								return;
							}
							if (themes[j] !== false && themes[j] !== newval) {
								newval = false;
							}
						}
						var handlers = _handlers.slice(0);
						for (j = 0; j < handlers.length; j++) {
							handlers[j].call(this, newval);
						}
						$(".wiziapp-plugin-admin-overlay").hide();
					});
				})(i);
			return function(cb) {
				_handlers.push(cb);
			};
		})();

		var theme_set = (function() {
			var ud = [updaterForName("webapp_theme"), updaterForName("android_theme")];
			return function(newval) {
				var i;
				for (i = 0; i < ud.length; i++) {
					ud[i].update(newval);
				}
			};
		})();

		$(".wiziapp-plugin-admin-container").bind("track-page", function(e, data) {
			trackPage(data);
		});

		$(".wiziapp-plugin-admin-settings-box-option-text").each(function() {
			var ud = updaterForName($(this).attr("data-wiziapp-plugin-admin-option-id")),
				inp = $(this).find("input[type=text]");
			ud.force(inp.val());
			inp.bind("keydown keyup keypressed mousedown mouseup change input textinput propertychange cut paste", function() {
				ud.update(inp.val());
			});
		});

		$(".wiziapp-plugin-admin-settings-box-option-select").each(function() {
			var ud = updaterForName($(this).attr("data-wiziapp-plugin-admin-option-id")),
				inp = $(this).find("select"),
				o = inp.find("option[selected]");
			if (o.length) {
				ud.force(o.val());
			}
			o = null;
			inp.bind("keydown keyup keypressed mousedown mouseup change input textinput propertychange", function() {
				ud.update(inp.val());
			});
			ud.bind("updating", function(newval) {
				inp.val(newval);
			});
		});

		$(".wiziapp-plugin-admin-settings-box-option-radio").each(function() {
			var ud = updaterForName($(this).attr("data-wiziapp-plugin-admin-option-id")),
				inp = $(this).find(".wiziapp-plugin-admin-settings-box-value-radio"),
				o = inp.find("input:checked");
			if (o.length) {
				ud.force(o.val());
			}
			o = null;
			inp.find("input").bind("keydown keyup keypressed mousedown mouseup click change input textinput propertychange", function() {
				ud.update(inp.find("input:checked").val());
			});
			ud.bind("updating", function(newval) {
				if (newval !== inp.find("input:checked").val()) {
					inp.find("input[value="+newval.replace(/([^0-9A-Za-z])/g, "\\$1")+"]").click();
				}
			});
		});

		$(".wiziapp-plugin-admin-settings-box-option-switch").each(function() {
			var ud = updaterForName($(this).attr("data-wiziapp-plugin-admin-option-id")),
				inp = $(this).find(".wiziapp-plugin-admin-settings-box-value-switch");
			ud.force(inp.hasClass("wiziapp-plugin-admin-settings-box-value-switch-on")?"true":"false");
			inp.bind("switch-on", function() {
				ud.update("true");
			});
			inp.bind("switch-off", function() {
				ud.update("false");
			});
		});

		$(".wiziapp-plugin-admin-settings-box-option-image").each(function() {
			var ud = new updater($(this).attr("data-wiziapp-plugin-admin-option-id"));
			var cnt = $(this).find(".wiziapp-plugin-admin-settings-box-value-image");
			new wp.Uploader({
				container: cnt,
				browser: cnt.find(".wiziapp-plugin-admin-settings-box-value-image-uploader a"),
				dropzone: cnt.find(".wiziapp-plugin-admin-settings-box-value-image-uploader"),
				success: function(attachment) {
					ud.update(attachment.get("id"));
					cnt.find(".media-item").hide();
				},
				progress: function(attachment) {
					var amount = ((200*attachment.get("loaded")/attachment.get("size")) << 0);
					cnt.find(".media-item").show();
					cnt.find(".progress .percent").text((amount >> 1)+"%");
					cnt.find(".progress .bar").width(amount);
					cnt.removeClass("wiziapp-plugin-admin-settings-box-value-has-error");
				},
				plupload: {},
				params: {}
			});
			ud.bind("extra", function(extra) {
				if (extra.url)
				{
					cnt.find(".wiziapp-plugin-admin-settings-box-value-image-preview img").attr("src", extra.url);
					cnt.addClass("wiziapp-plugin-admin-settings-box-value-has-image");
				}
				else
				{
					cnt.removeClass("wiziapp-plugin-admin-settings-box-value-has-image");
				}
				if (extra.error)
				{
					cnt.addClass("wiziapp-plugin-admin-settings-box-value-has-error");
				}
				else
				{
					cnt.removeClass("wiziapp-plugin-admin-settings-box-value-has-error");
				}
			});
		});

		$(".wiziapp-plugin-admin-settings-box-option-state").each(function() {
			var ud = updaterForName($(this).attr("data-wiziapp-plugin-admin-option-id")),
				timer = false,
				me = $(this);

			function settimer() {
				if (timer !== false) {
					return;
				}
				timer = setTimeout(function() {
					timer = false;
					ud.update("");
				}, 5000);
			}
			if (me.find(".wiziapp-plugin-admin-settings-box-value-state-active").is(".wiziapp-plugin-admin-settings-box-value-state-temporary")) {
				settimer();
			}
			ud.bind("updated", function(newval) {
				if (newval === false) {
					return;
				}
				me.find(".wiziapp-plugin-admin-settings-box-value-state[data-wiziapp-plugin-admin-option-state="+newval+"]").addClass("wiziapp-plugin-admin-settings-box-value-state-active");
				me.find(".wiziapp-plugin-admin-settings-box-value-state-active[data-wiziapp-plugin-admin-option-state!="+newval+"]").removeClass("wiziapp-plugin-admin-settings-box-value-state-active");
				if (me.find(".wiziapp-plugin-admin-settings-box-value-state-active").is(".wiziapp-plugin-admin-settings-box-value-state-temporary")) {
					settimer();
				}
				else if (timer !== false) {
					clearTimeout(timer);
					timer = false;
				}
			});
		});

		$(".wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id$=_theme] .wiziapp-plugin-admin-settings-box-value-button input").click(function() {
			var theme = $(this).closest(".wiziapp-plugin-admin-settings-box-option").find(".wiziapp-plugin-admin-settings-box-value select").val();
			var admin_url = $(this).closest(".wiziapp-plugin-admin-container").attr("data-wiziapp-plugin-admin-url");
			var prepared_url = admin_url+"customize.php?wiziapp_plugin=customize&theme="+encodeURIComponent(theme)+"&return="+encodeURIComponent(admin_url+"admin.php?page=wiziapp-plugin-settings");
			var wiziapp_theme_menu = $("#wiziapp-plugin-admin-settings-box-general .wiziapp-plugin-admin-settings-box-body div[data-wiziapp-plugin-admin-option-id$='_navigation'] select").val();
			w.location.href = prepared_url+(wiziapp_theme_menu?"&wiziapp_theme_menu="+encodeURIComponent(wiziapp_theme_menu):"");
		});
		$(".wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id$=_navigation] .wiziapp-plugin-admin-settings-box-label-description a, .wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id$=_navigation] .wiziapp-plugin-admin-settings-box-value-button input").click(function() {
			var menu = $(this).closest(".wiziapp-plugin-admin-settings-box-option").find(".wiziapp-plugin-admin-settings-box-value select").val();
			var admin_url = $(this).closest(".wiziapp-plugin-admin-container").attr("data-wiziapp-plugin-admin-url");
			w.location.href = admin_url+"nav-menus.php"+((menu === "")?"":("?action=edit&menu="+encodeURIComponent(menu)));
			return false;
		});

		$(".wiziapp-plugin-admin-settings-box-themes-controls").each(function() {
			var inp = $(this).find("select"),
				img = $(this).find("img"),
				ud = updaterForName("webapp_theme"),
				o = inp.find("option[selected]"),
				a = $(".wiziapp-plugin-admin-settings-box-themes-customize a");
			if (o.length) {
				ud.force(o.val());
			}
			o = null;
			inp.bind("keydown keyup keypressed mousedown mouseup change input textinput propertychange", function() {
				ud.update(inp.val());
			});
			ud.bind("updated", function(newname) {
				var newsrc = $("#wiziapp-plugin-admin-tab-themes .available-theme[data-wiziapp-plugin-admin-theme="+newname.replace(/([^0-9A-Za-z])/g, "\\$1")+"] .screenshot img");
				if (newsrc.length > 0 && newsrc.attr("src")) {
					img.attr("src", newsrc.attr("src"));
					img.show();
				}
				else {
					img.hide();
				}
				var h = a.attr("href");
				if (/&theme=/.test(h))
				{
					h = h.replace(/&theme=[^&]*/, "&theme="+encodeURIComponent(newname));
				}
				else
				{
					h += "&theme="+encodeURIComponent(newname);
				}
				a.attr("href", h);
				inp.val(newname);
			});
			updaterForName("webapp_navigation").bind("updated", function(val) {
				var h = a.attr("href");
				if (/&wiziapp_theme_menu=/.test(h))
				{
					h = h.replace(/&wiziapp_theme_menu=[^&]*/, val?"&wiziapp_theme_menu="+encodeURIComponent(val):"");
				}
				else if (val)
				{
					h += "&wiziapp_theme_menu="+encodeURIComponent(val);
				}
				a.attr("href", h);
			});
		});

		$(".wiziapp-plugin-admin-settings-box-themes-browse").click(function(event) {
			event.preventDefault();

			$("#wiziapp-plugin-admin-tab-header-themes").click();
		});

		updaterForName("android_app").bind("extra", function(val) {
			$("#wiziapp-plugin-admin-settings-box-option-android_app-state-download a").attr("href", val);
		});
		$("#wiziapp-plugin-admin-settings-box-option-android_app-state-need-build input").click(function() {
			post_ajax({
				action: "wiziapp_plugin_android_build"
			}, function() {
				updaterForName("android_app").update("");
			});
		});

		$("#wiziapp-plugin-admin-settings-box-android-body-buy").each(function() {
			var a = $(this).find(".wiziapp-plugin-admin-state-buy-billing-simulate a");
			updaterForName("android_theme").bind("updated", function(newname) {
				var h = a.attr("href");
				if (/&theme=/.test(h))
				{
					h = h.replace(/&theme=[^&]*/, "&theme="+encodeURIComponent(newname));
				}
				else
				{
					h += "&theme="+encodeURIComponent(newname);
				}
				a.attr("href", h);
			});
			updaterForName("android_navigation").bind("updated", function(val) {
				var h = a.attr("href");
				if (/&wiziapp_theme_menu=/.test(h))
				{
					h = h.replace(/&wiziapp_theme_menu=[^&]*/, val?"&wiziapp_theme_menu="+encodeURIComponent(val):"");
				}
				else if (val)
				{
					h += "&wiziapp_theme_menu="+encodeURIComponent(val);
				}
				a.attr("href", h);
			});
		});

		function popup_billing(args)
		{
			trackPage(args.trackPage);

			var o;
			var box = $("#wiziapp-plugin-admin-billing-type");

			var loader = box.find(".wiziapp-plugin-ajax-loader");
			loader.hide();

			o = box.find(".wiziapp-plugin-admin-billing-type-details-product");
			o.contents().filter(function() {return !$(this).is(".wiziapp-plugin-admin-billing-type-details-label");}).remove();
			o.append(d.createTextNode(args.product));

			if (args.license) {
				o = box.find(".wiziapp-plugin-admin-billing-type-details-license");
				o.contents().filter(function() {return !$(this).is(".wiziapp-plugin-admin-billing-type-details-label");}).remove();
				o.append(d.createTextNode(args.license));
				o.show();
			}
			else {
				box.find(".wiziapp-plugin-admin-billing-type-details-license").hide();
			}

			var typebox = box.find(".wiziapp-plugin-admin-billing-type-selection-title, .wiziapp-plugin-admin-billing-type-selection");
			typebox.show();

			o = box.find(".wiziapp-plugin-admin-billing-type-details-price");
			o.contents().filter(function() {return !$(this).is(".wiziapp-plugin-admin-billing-type-details-label");}).remove();
			o.append(d.createTextNode(args.price));

			o = box.find(".wiziapp-plugin-admin-billing-type-details-terms input");
			o.each(function(){ this.checked = true; });
			o.unbind().change(function() {
				if ($(this).is(":checked")) {
					typebox.show();
				}
				else {
					typebox.hide();
				}
			});

			$.each("cardcom paypal".split(" "), function(i, type) {
				var o = box.find(".wiziapp-plugin-admin-billing-type-"+type);
				o.unbind().click(function(event) {
					event.preventDefault();

					loader.show();
					post_ajax($.extend({
						type: type
					}, args.actionParams), function(data) {
						if (!data.url) {
							loader.hide();
							return;
						}
						trackPage(args.trackPage+"/"+type, function() {
							loader.hide();
							if (data.supports_frame) {
								tb_remove();
								$("#TB_window").stop(true, true);
								tb_show(args.title, data.url+"&TB_iframe=true&width=800&height=600");
							}
							else {
								loader.show();
								w.location.href = data.url;
							}
						});
					});
				});
			});

			tb_remove();
			$("#TB_window").stop(true, true);
			tb_show(args.title, "#TB_inline?width=800&height=600&inlineId=wiziapp-plugin-admin-billing-type");
		}

		function popup_license(args)
		{
			tb_remove();
			$("#TB_window").stop(true, true);

			var box = $("#wiziapp-plugin-admin-license");

			var loader = box.find(".wiziapp-plugin-ajax-loader");
			loader.hide();

			var k = box.find(".wiziapp-plugin-admin-license-key");
			var e = box.find(".error");
			box.find(".wiziapp-plugin-admin-license-activate").unbind().click(function(event) {
				event.preventDefault();

				loader.show();
				post_ajax($.extend({
					license: k.val()
				}, args.actionParams), function(data) {
					loader.hide();
					if (!data.url) {
						e.show();
						return;
					}
					tb_remove();
					$("#TB_window").stop(true, true);
					tb_show(args.title, data.url+"&TB_iframe=true&width=800&height=600");
				});
			});
			e.hide();

			tb_show(args.title, "#TB_inline?width=800&height=600&inlineId=wiziapp-plugin-admin-license");
		}

		$("#wiziapp-plugin-admin-settings-box-android-body-buy .wiziapp-plugin-admin-state-buy-billing-buy a, .wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id=android_license] .wiziapp-plugin-admin-settings-box-value-button input").click(function(event) {
			event.preventDefault();

			var title = $("#wiziapp-plugin-admin-settings-box-android-body-buy .wiziapp-plugin-admin-state-buy-billing-buy a").attr("title");

			popup_billing({
				trackPage: "/android",
				title: title,
				product: title,
				price: $("#wiziapp-plugin-admin-settings-box-android-body-buy .wiziapp-plugin-admin-state-buy-billing-price-amount").text()+"/year",
				actionParams: {
					action: "wiziapp_plugin_build_android_buy",
					package: "1year"
				}
			});
		});
		$("#wiziapp-plugin-admin-settings-box-android-body-buy .wiziapp-plugin-admin-state-buy-billing-license a, .wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id=android_license] .wiziapp-plugin-admin-state-buy-billing-license a").click(function(event) {
			event.preventDefault();

			popup_license({
				title: $(this).attr("title"),
				actionParams: {
					action: "wiziapp_plugin_build_android_license"
				}
			});
		});

		if ($("#wiziapp-plugin-admin-settings-box-android-body-loading").is(".wiziapp-plugin-admin-settings-box-body-active"))
		{
			post_ajax({
				action: "wiziapp_plugin_build_android_license_expiration"
			}, function(data) {
				$("#wiziapp-plugin-admin-settings-box-android-body-loading").removeClass("wiziapp-plugin-admin-settings-box-body-active");
				if (data && data.packages && data.packages[0] && data.packages[0].price) {
					$("#wiziapp-plugin-admin-settings-box-android-body-buy .wiziapp-plugin-admin-state-buy-billing-price-amount").text("$"+data.packages[0].price);
				}
				if (data && data.expiration !== false && new Date(data.expiration)-new Date() > 0) {
					$("#wiziapp-plugin-admin-settings-box-option-android_license-state-licensed").text(new Date(data.expiration).toString());
					$("#wiziapp-plugin-admin-settings-box-android-body").addClass("wiziapp-plugin-admin-settings-box-body-active");
				}
				else {
					$("#wiziapp-plugin-admin-settings-box-android-body-buy").addClass("wiziapp-plugin-admin-settings-box-body-active");
				}
			});
		}

		$("#wiziapp-plugin-admin-settings-box-ios-body-buy .wiziapp-plugin-admin-state-buy-billing-buy a").click(function(event) {
			event.preventDefault();

			popup_billing({
				trackPage: "/ios",
				title: $(this).attr("title"),
				product: $(this).attr("title"),
				price: $("#wiziapp-plugin-admin-settings-box-ios-body-buy .wiziapp-plugin-admin-state-buy-billing-price-amount").text()+"/year",
				actionParams: {
					action: "wiziapp_plugin_build_ios_buy",
					package: "1year"
				}
			});
		});
		$("#wiziapp-plugin-admin-settings-box-ios-body-buy .wiziapp-plugin-admin-state-buy-billing-license a").click(function(event) {
			event.preventDefault();

			popup_license({
				title: $(this).attr("title"),
				actionParams: {
					action: "wiziapp_plugin_build_ios_license"
				}
			});
		});

		if ($("#wiziapp-plugin-admin-settings-box-ios-body-loading").is(".wiziapp-plugin-admin-settings-box-body-active"))
		{
			post_ajax({
				action: "wiziapp_plugin_build_ios_license_balance"
			}, function(data) {
				$("#wiziapp-plugin-admin-settings-box-ios-body-loading").removeClass("wiziapp-plugin-admin-settings-box-body-active");
				if (data && data.count && data.count > 0 && data.license) {
					$("#wiziapp-plugin-admin-settings-box-ios-body-available .wiziapp-plugin-admin-state-available-license").append(d.createTextNode(data.license));
					$("#wiziapp-plugin-admin-settings-box-ios-body-available").addClass("wiziapp-plugin-admin-settings-box-body-active");
				}
				else if (data.packages && data.packages[0] && data.packages[0].price) {
					$("#wiziapp-plugin-admin-settings-box-ios-body-buy .wiziapp-plugin-admin-state-buy-billing-price-amount").text("$"+data.packages[0].price);
					$("#wiziapp-plugin-admin-settings-box-ios-body-buy").addClass("wiziapp-plugin-admin-settings-box-body-active");
				}
			});
		}

		$("#wiziapp-plugin-admin-settings-box-monetization-body-buy .wiziapp-plugin-admin-state-buy-billing-buy a, .wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id=monetization_license] .wiziapp-plugin-admin-settings-box-value-button input").click(function(event) {
			event.preventDefault();

			var title = $("#wiziapp-plugin-admin-settings-box-monetization-body-buy .wiziapp-plugin-admin-state-buy-billing-buy a").attr("title");

			popup_billing({
				trackPage: "/monetization",
				title: title,
				product: title,
				price: $("#wiziapp-plugin-admin-settings-box-monetization-body-buy .wiziapp-plugin-admin-state-buy-billing-price-amount").text()+"/year",
				actionParams: {
					action: "wiziapp_plugin_ads_buy",
					package: "1year"
				}
			});
		});
		$("#wiziapp-plugin-admin-settings-box-monetization-body-buy .wiziapp-plugin-admin-state-buy-billing-license a, .wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id=monetization_license] .wiziapp-plugin-admin-state-buy-billing-license a").click(function(event) {
			event.preventDefault();

			popup_license({
				title: $(this).attr("title"),
				actionParams: {
					action: "wiziapp_plugin_ads_license"
				}
			});
		});

		$("#wiziapp-plugin-admin-settings-box-monetization .wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id=monetization_license]").hide();
		post_ajax({
			action: "wiziapp_plugin_ads_license_expiration"
		}, function(data) {
			if (data && data.packages && data.packages[0] && data.packages[0].price) {
				$("#wiziapp-plugin-admin-settings-box-monetization-body-buy .wiziapp-plugin-admin-state-buy-billing-price-amount").text("$"+data.packages[0].price);
			}
			if (data && data.expiration !== false && new Date(data.expiration)-new Date() > 0) {
				$("#wiziapp-plugin-admin-settings-box-option-monetization_license-state-licensed").text(new Date(data.expiration).toString());
				$("#wiziapp-plugin-admin-settings-box-monetization .wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id=monetization_license]").show();
			}
			else {
				$("#wiziapp-plugin-admin-settings-box-monetization-body-buy").show();
			}
		});

		trackPage("/settings");

		function theme_bind(theme, name)
		{
			var details = theme.find(".themedetaildiv");
			theme.find(".theme-detail").click(function(event) {
				details.toggle();
				event.preventDefault();
			});
			theme.find(".activatelink").click(function(event) {
				event.preventDefault();
				theme_set(name);
			});
			theme.find(".wiziapp-plugin-theme-install a").click(function(event) {
				event.preventDefault();

				var title = $(this).attr("title");

				post_ajax({
					action: "wiziapp_plugin_theme_install",
					theme: name
				}, function(data) {
					if (!data.url) {
						return;
					}
					tb_remove();
					$("#TB_window").stop(true, true);
					tb_show(title, data.url+"&TB_iframe=true&width=800&height=600");
				});
			});
			theme.find(".action-links a[href!=\#]").each(function() {
				var a = $(this);
				updaterForName("webapp_navigation").bind("updated", function(val) {
					var h = a.attr("href");
					if (/&wiziapp_theme_menu=/.test(h))
					{
						h = h.replace(/&wiziapp_theme_menu=[^&]*/, val?"&wiziapp_theme_menu="+encodeURIComponent(val):"");
					}
					else if (val)
					{
						h += "&wiziapp_theme_menu="+encodeURIComponent(val);
					}
					a.attr("href", h);
				});
			});
			theme_change(function(newname) {
				if (newname === name) {
					theme.addClass("is-active-theme");
					theme.removeClass("is-not-active-theme");
				}
				else {
					theme.removeClass("is-active-theme");
					theme.addClass("is-not-active-theme");
				}
			});
		}

		$("#wiziapp-plugin-admin-tab-themes .available-theme").each(function() {
			var theme = $(this),
				name = theme.attr("data-wiziapp-plugin-admin-theme");
			theme_bind(theme, name);
		});

		$("#wiziapp-plugin-admin-themes-box-complete .wiziapp-plugin-admin-themes-box-complete-buy a").click(function(event) {
			event.preventDefault();

			popup_billing({
				trackPage: "/themes/global/1year",
				title: $(this).attr("title"),
				product: $(this).attr("title"),
				price: $("#wiziapp-plugin-admin-themes-box-complete .wiziapp-plugin-admin-themes-box-complete-price-amount").text()+"/year",
				actionParams: {
					action: "wiziapp_plugin_theme_buy",
					theme: "global",
					package: "1year"
				}
			});
		});
		$("#wiziapp-plugin-admin-themes-box-complete .wiziapp-plugin-admin-themes-box-complete-license a").click(function(event) {
			event.preventDefault();

			popup_license({
				title: $(this).attr("title"),
				actionParams: {
					action: "wiziapp_plugin_theme_license",
					theme: "global"
				}
			});
		});

		$("settings themes plugins".split(" ")).each(function() {
			var tab = this;
			$("#wiziapp-plugin-admin-tab-"+tab).bind("wiziapp-plugin-admin-tab-shown", function() {
				trackPage("/"+tab);
			});
		});

		$("#wiziapp-plugin-admin-tab-themes .available-theme a.load-customize").each(function() {
			var a = $(this);
			updaterForName("webapp_navigation").bind("updated", function(val) {
				var h = a.attr("href");
				if (/&wiziapp_theme_menu=/.test(h))
				{
					h = h.replace(/&wiziapp_theme_menu=[^&]*/, val?"&wiziapp_theme_menu="+encodeURIComponent(val):"");
				}
				else if (val)
				{
					h += "&wiziapp_theme_menu="+encodeURIComponent(val);
				}
				a.attr("href", h);
			});
		});

		$("#wiziapp-plugin-admin-tab-themes").one("wiziapp-plugin-admin-tab-shown", function() {
			var tmpl = $(".wiziapp-plugin-admin-themes-template");
			post_ajax({
				action: "wiziapp_plugin_theme_list"
			}, function(data) {
				$("#wiziapp-plugin-admin-tab-themes .available-theme.wiziapp-plugin-ajax-loader-container").remove();
				var i;
				for (i = 0; i < data.length; i++)
					(function(data) {
						var i, o, a, s;
						var theme = $("#wiziapp-plugin-admin-tab-themes .available-theme[data-wiziapp-plugin-admin-theme="+data.name.replace(/([^0-9A-Za-z])/g, "\\$1")+"]");
						if (!theme.length) {
							theme = tmpl.children().clone();
							theme.attr("data-wiziapp-plugin-admin-theme", data.name);
							tmpl.before(theme);

							o = theme.find(".screenshot img");
							if (data.screenshots.length > 0) {
								o.attr("src", data.screenshots[0]);
							}
							else {
								o.remove();
							}
							theme.find("h3").html(data.title);
							o = theme.find(".theme-author");
							if (data.author) {
								if (data.author_uri) {
									a = $("<a>");
									a.attr("href", data.author_uri);
									a.text(data.author);
									s = o.text().split("{}");
									o.text("");
									for (i = 0; i < s.length-1; i++) {
										if (s[i]) {
											o.append(d.createTextNode(s[i]));
										}
										o.append(a.clone());
									}
									if (s.length && s[s.length-1]) {
										o.append(d.createTextNode(s[s.length-1]));
									}
								}
								else {
									o.text(o.text().replace(/\{\}/, data.author));
								}
							}
							else {
								o.remove();
							}

							o = theme.find(".themedetaildiv p:eq(0)");
							if (data.version) {
								o.append(d.createTextNode(data.version));
							}
							else {
								o.remove();
							}

							theme.find(".action-links a[href!=\#]").each(function() {
								$(this).attr("href", $(this).attr("href").replace(/\{\}/g, data.name).replace(/\{p\}/g, data.parent?data.parent.name:data.name));
							});
							theme.find(".action-links a[title]").each(function() {
								$(this).attr("title", $(this).attr("title").replace(/\{\}/g, data.title));
							});

							theme_bind(theme, data.name);
						}
						else {
							$(".wiziapp-plugin-admin-settings-box-option[data-wiziapp-plugin-admin-option-id$=_theme] .wiziapp-plugin-admin-settings-box-value select, .wiziapp-plugin-admin-settings-box-themes-controls select")
							.not(":has(option[value="+data.name.replace(/([^0-9A-Za-z])/g, "\\$1")+"])")
							.append("<option value="+data.name.replace(/([^0-9A-Za-z])/g, function(x) {return "&#"+x.charCodeAt(0)+";";})+">"+data.title.replace(/([^0-9A-Za-z])/g, function(x) {return "&#"+x.charCodeAt(0)+";";})+"</option>");
						}

						if (data.installed) {
							theme.removeClass("wiziapp-plugin-theme-is-not-installed").addClass("wiziapp-plugin-theme-is-installed");
						}
						else {
							theme.removeClass("wiziapp-plugin-theme-is-installed").addClass("wiziapp-plugin-theme-is-not-installed");
						}
						theme.removeClass("wiziapp-plugin-theme-is-not-licensed").addClass("wiziapp-plugin-theme-is-licensed");
						if (data.need_update) {
							theme.removeClass("wiziapp-plugin-theme-is-not-need-update").addClass("wiziapp-plugin-theme-is-need-update");
						}
						else {
							theme.removeClass("wiziapp-plugin-theme-is-need-update").addClass("wiziapp-plugin-theme-is-not-need-update");
						}
						o = theme.find(".wiziapp-plugin-theme-price");
						if (data.packages && data.packages.length > 0) {
							o.text("$"+data.packages[0].price);
						}
						else {
							o.remove();
						}
						theme.removeClass("wiziapp-plugin-theme-is-price-unknown").removeClass("wiziapp-plugin-theme-is-not-price-unknown");

						theme.find(".wiziapp-plugin-theme-demo").click(function(event) {
							if (theme.is(".wiziapp-plugin-theme-is-not-installed") || theme.is(".wiziapp-plugin-theme-is-need-update")) {
								event.preventDefault();

								var title = $(this).attr("title");

								post_ajax({
									action: "wiziapp_plugin_theme_install",
									theme: data.name
								}, function(data) {
									if (!data.url) {
										return;
									}
									tb_remove();
									$("#TB_window").stop(true, true);
									tb_show(title, data.url+"&TB_iframe=true&width=800&height=600");
								});
							}
						});

						theme.find(".screenshot").click(function(event) {
							event.preventDefault();
							theme.find("a.wiziapp-plugin-theme-pricing, .wiziapp-plugin-theme-install a, a.hide-if-customize, a.hide-if-no-customize, a.activatelink").filter(":visible").first().click();
						});

						theme.find("a.wiziapp-plugin-theme-pricing").click(function(event) {
							event.preventDefault();

							trackPage("/themes/"+data.name);

							tb_remove();
							$("#TB_window").stop(true, true);

							var title = $(this).attr("title");

							var i, o;
							var box = $("#wiziapp-plugin-admin-themes-billing");

							var loader = box.find(".wiziapp-plugin-ajax-loader");
							loader.hide();

							var packages_box = box.find(".wiziapp-plugin-admin-themes-billing-packages");
							packages_box.html("");
							for (i = 0; i < data.packages.length; i++)
								(function(package) {
									var li = $("<li><label><input type=\"radio\" /><span class=\"wiziapp-plugin-admin-themes-billing-packages-description\"></span></label></li>");
									li.find(".wiziapp-plugin-admin-themes-billing-packages-description").text(package.description);
									li.find("label").attr("for", "wiziapp-plugin-admin-themes-billing-packages-theme-"+package.theme+"-package-"+package.name);
									li.find("input").attr("id", "wiziapp-plugin-admin-themes-billing-packages-theme-"+package.theme+"-package-"+package.name);
									li.find("input").attr("name", "theme_"+data.theme+"_package");
									li.find("input").attr("value", ""+i);
									if (i === data.packages.length-1) {
										li.find("input").attr("checked", "checked");
									}

									packages_box.append(li);
								})(data.packages[i]);

							box.find(".wiziapp-plugin-admin-themes-billing-buy").click(function(event) {
								event.preventDefault();

								var package = data.packages[packages_box.find("input:checked").val() << 0];
								if (!package) {
									return;
								}

								popup_billing({
									trackPage: "/themes/"+((package.theme.substr(0, 7) === "global.")?package.theme.substr(7):package.theme)+"/"+package.name,
									title: title,
									product: package.theme_title,
									license: package.description,
									price: "$"+package.price,
									actionParams: {
										action: "wiziapp_plugin_theme_buy",
										theme: package.theme,
										package: package.name
									}
								});
							});

							o = box.find(".wiziapp-plugin-admin-themes-billing-license a");
							o.unbind().click(function(event) {
								event.preventDefault();

								var package = data.packages[packages_box.find("input:checked").val() << 0];
								if (!package) {
									return;
								}

								popup_license({
									title: $(this).attr("title"),
									actionParams: {
										action: "wiziapp_plugin_theme_license",
										theme: package.theme
									}
								});
							});

							o = box.find(".wiziapp-plugin-admin-themes-billing-demo");
							if (data.demo_link) {
								o.show().find("a").href(data.demo_link);
							}
							else {
								o.hide();
							}

							box.find(".wiziapp-plugin-admin-themes-billing-main h3").text(data.title);

							o = box.find(".wiziapp-plugin-admin-themes-billing-screenshot");
							o.find("img").remove();
							for (i = 0; i < data.screenshots.length; i++) {
								var img = $("<img src=\"\" alt=\"\" class=\"hidden\" />");
								img.attr("src", data.screenshots[i]);
								o.append(img);
							}
							o.find("img:eq(0)").show();

							$(".wiziapp-plugin-admin-themes-billing-screenshot-prev").hide();
							if (data.screenshots.length > 1) {
								$(".wiziapp-plugin-admin-themes-billing-screenshot-next").show();
							}
							else {
								$(".wiziapp-plugin-admin-themes-billing-screenshot-next").hide();
							}

							o = box.find(".wiziapp-plugin-admin-themes-billing-description");
							if (data.long_description) {
								o.show().html(data.long_description);
							}
							else if (data.description) {
								o.show().html(data.description);
							}
							else {
								o.hide();
							}

							tb_show(title, "#TB_inline?width=800&height=600&inlineId=wiziapp-plugin-admin-themes-billing");
						});
					})(data[i]);
			});
		});

		$("#wiziapp-plugin-admin-themes-billing .wiziapp-plugin-admin-themes-billing-screenshot-prev").click(function(event) {
			event.preventDefault();
			var imgs = $(this).closest(".wiziapp-plugin-admin-themes-billing-screenshot").find("img");
			var prev = imgs.filter(":visible").prev("img");
			if (!prev.length) {
				prev = imgs.first();
			}
			imgs.not(prev).hide();
			prev.show();
			if (!prev.prev("img").length) {
				$(this).hide();
			}
			if (prev.next("img").length) {
				$(".wiziapp-plugin-admin-themes-billing-screenshot-next").show();
			}
			else {
				$(".wiziapp-plugin-admin-themes-billing-screenshot-next").hide();
			}
		});
		$("#wiziapp-plugin-admin-themes-billing .wiziapp-plugin-admin-themes-billing-screenshot-next").click(function(event) {
			event.preventDefault();
			var imgs = $(this).closest(".wiziapp-plugin-admin-themes-billing-screenshot").find("img");
			var next = imgs.filter(":visible").next("img");
			if (!next.length) {
				next = imgs.last();
			}
			imgs.not(next).hide();
			next.show();
			if (!next.next("img").length) {
				$(this).hide();
			}
			if (next.prev("img").length) {
				$(".wiziapp-plugin-admin-themes-billing-screenshot-prev").show();
			}
			else {
				$(".wiziapp-plugin-admin-themes-billing-screenshot-prev").hide();
			}
		});

		var hash = w.location.href.replace(/^[^#]*/, "");
		hash = decodeURIComponent(hash.substr(1));
		if (hash)
		{
			var loader = $(".wiziapp-plugin-admin-overlay");
			loader.show();
			post_ajax({
				action: "wiziapp_plugin_hash_to_url",
				hash: hash
			}, function(data) {
				loader.hide();
				if (data && data.track_page) {
					trackPage(data.track_page);
				}
				if (!data || !data.url || !data.title) {
					return;
				}
				tb_remove();
				$("#TB_window").stop(true, true);
				tb_show(data.title, data.url+"&TB_iframe=true&width=800&height=600");
				$("#TB_window").bind("tb_unload", function() {
					w.location.href = "#";
				});
			});
			return;
		}

		if ($("#wiziapp-plugin-admin-upgraded").length > 0)
		{
			$(".wiziapp-plugin-admin-upgraded-donelink").click(function(event) {
				event.preventDefault();
				tb_remove();
				post_ajax({
					action: "wiziapp_plugin_upgrade_dismiss"
				}, function() {});
			});
			setTimeout(function() {
				tb_remove();
				$("#TB_window").stop(true, true);
				tb_show($("#wiziapp-plugin-admin-upgraded").attr("data-title"), "#TB_inline?width=800&height=600&inlineId=wiziapp-plugin-admin-upgraded");
			}, 500);
		}
	});
})(jQuery,window,document);
