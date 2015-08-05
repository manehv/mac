var $ =  jQuery.noConflict(); //Wordpress by default uses jQuery instead of $

$(document).ready(function(){
	/**
	 * Detect Delete action and prompt message
	 */
	 $('.js-delete-item').on('click', function(e) {
	 	e.preventDefault();

	 	confirmation =  window.confirm( $messages.delete_confirm + ' ' + $(this).data('name') + '?' );

	 	if( confirmation ) {
	 		window.location = $(this).attr('href');
	 	}

	 });

});