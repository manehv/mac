(function($) {
	//load up our header notice
	$(document).ready(function() {
		$('body').prepend('<div id="mod-message" class="'+sreg.state+'"><div class="message">'+sreg.message+'</div></div>');
		$('#mod-message').slideDown(500);
		setTimeout( function() {
			$('#mod-message').slideUp(500);
		}, 5000);
	});	

})(jQuery);
