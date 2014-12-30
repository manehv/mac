(function($) {

	var wiziapp_plugin_push_message;
	var max_length;
	var max_length_warning;
	var jqEasyCounterMsg = $('<div>&nbsp;</div>');

	$(document).ready(function() {
		wiziapp_plugin_push_message = $('#wiziapp_plugin_push_message');
		wiziapp_plugin_push_message.val( wiziapp_plugin_push_message.data("push-message") );
		max_length = wiziapp_plugin_push_message.data("max-length");
		max_length_warning = max_length - 15;

		wiziapp_plugin_push_message
		.after(jqEasyCounterMsg)
		.bind('keydown keyup keypress', doCount)
		.bind('focus paste', function() { setTimeout(doCount, 10); })
		.bind('blur', countStop);
	});

	function countStop() {
		jqEasyCounterMsg
		.stop()
		.fadeTo( 'fast', 0);

		return false;
	}

	function doCount() {
		var val = wiziapp_plugin_push_message.val();
		var message_length = val.length;

		if (message_length > max_length) {
			wiziapp_plugin_push_message
			.val(val.substring(0, max_length))
			.scrollTop(wiziapp_plugin_push_message.scrollTop());
		};

		if (message_length > max_length_warning) {
			jqEasyCounterMsg.css({"color" : "#F00"});
		} else {
			jqEasyCounterMsg.css({"color" : "#000"});
		};

		jqEasyCounterMsg
		.text('Maximum 105 Characters. Printed: ' + message_length + "/" + max_length)
		.stop()
		.fadeTo('fast', 1);
	}

})(jQuery);
