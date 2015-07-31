(function ($) {

	$.fn.otwpreview = function( options ) {

		$this = $(this);
		$templateSettings = '';

		/**
		 * Detect template selection
		 * Load template settings based on selection made
		 */
		$this.on('change', function() {
			var templateStyle = $(this).val();

			$.each(templateOptions, function( key, value ) {
				
				$templateSettings = value[templateStyle];
				
			});

		});

		/**
		 * Handle all possible form input changes 
		 */

		// Pagination Item
		$(window).otwadjustpreview({
			element: 'input[name=show-pagination]',
			elementEvent: 'change',
			elementNode: '[data-item="pagination"]'
		});

		// Post Icon Item
		$(window).otwadjustpreview({
			element: 'input[name=show-post-icon]',
			elementEvent: 'change',
			elementNode: '[data-item="icon"]'
		});

		$(window).on('listEvent', function() {
			var list = $('.js-blog-items').val();

			var listItems = list.split(',');

			$.each( listItems, function( key, value ) {
				// console.log( value );
			});
		});



	}

	$.fn.otwadjustpreview = function( options ) {

		var settings = $.extend({
			element: 'input[name=show-pagination]',
			elementEvent: 'change',
			elementNode: '[data-item="pagination"]'
		}, options);

		$( settings.element ).on( settings.elementEvent, function() {
			elementValue = $(this).val();
			if( elementValue == 0 ) {
				$('.js-preview').find( settings.elementNode ).hide();
			} else {
				$('.js-preview').find( settings.elementNode ).show();
			}
		});

	}


}( jQuery ));