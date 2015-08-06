var $ =  jQuery.noConflict(); //Wordpress by default uses jQuery instead of $

$(document).ready(function(){
	var frameUpload; // WP Media holder;
	/**
	 * Init Blog Items ( Title, Description, Media, Meta, Continue Reading )
	 * Init Meta Items ( Author, Date, Category, Tags, Comments )
	 */
	setupBlogElements();

	// Translation enabled messages
	var $messages = JSON.parse( messages );

	// Disable caching of AJAX responses - DEVELOPMENT ONLY
	$.ajaxSetup ({
	    cache: false
	});

	/**
	 * Enable jQuery UI Accordion for Add List Page
	 */
	$( '.accordion-container' ).accordion({
		header: "> ul > li > h3",
		collapsible: true,
		heightStyle: 'content',
		speed: 'fast'
	});

	/**
	 * Enable Color Picker
	 */
	$element = new Array();
	$('.js-color-picker').each( function(index, element) {
		$element[index] = element;

		$($element[index]).ColorPicker({
			color: '#000000',
				onShow: function (colpkr) {
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					// $($colr).next( 'input').change();
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					
					$( $element[index] ).parent().children('.js-color-picker-value').val( '#'+hex );
					$( $element[index] ).children('.js-color-container').css( 'backgroundColor', '#'+hex );
					
				}
			});	
		
	});

	/**
	 * Autocomplete Functionality for: Categories, Tags and Users (Authors)
	 */
	$('.js-categories').select2({
		allowClear 	: true,
		multiple 	: true,
		data 		: JSON.parse( categories )
	});

	$('.js-tags').select2({
		allowClear 	: true,
		multiple 	: true,
		data 		: JSON.parse( tags )
	});

	$('.js-users').select2({
		allowClear 	: true,
		multiple 	: true,
		data 		: JSON.parse( users )
	});

	$('.js-pages').select2({
		allowClear 	: true,
		multiple 	: false,
		data 		: JSON.parse( pages ),
		placeholder: 'Page Name'
	});

	$('.js-fonts').select2({
		allowClear 	: true,
		multiple 	: false,
		data 		: JSON.parse( fonts ),
		placeholder: 'Font Family'
	});


	/**
	 * Select All Funcitonality
	 */

	$('.js-select-categories, .js-select-tags, .js-select-users').on('change', function(e) {
		var sectionName = $(this).data('section');

		if( $(this).is(':checked') ) {

			var itemsID = [];
			$.each( JSON.parse( eval(sectionName)) , function(index, value) {
				itemsID.push( value.id );
			});

			$('.js-'+sectionName+'-select').val(itemsID);
			$('.js-'+sectionName+'-counter').html( itemsID.length );
			$('.js-'+sectionName+'-count').show();
			$('.js-'+sectionName+'').select2("enable", false);

		} else {
			$('.js-'+sectionName+'-select').val( '' );
			$('.js-'+sectionName+'-counter').html( '' );
			$('.js-'+sectionName+'-count').hide();
			$('.js-'+sectionName+'').select2("enable", true);
		}

	});
	
	
	if( $('.js-template-style').size() && $('.js-template-style').val() ){
	
		var js_templ_val = $('.js-template-style').val();
		if( typeof( js_template_options[ js_templ_val ] ) == 'object' ){
			$( '.default_thumb_width' ).html( js_template_options[ js_templ_val ].width );
			$( '.default_thumb_height' ).html( js_template_options[ js_templ_val ].height );
		}else{
			$( '.default_thumb_width' ).html( '' );
			$( '.default_thumb_height' ).html( '' );
		}
	}
	/**
	 * Load Front End preview based on selection
	 */
	 
	$('.js-template-style').on('change', function(e){
		
		// Get Current Page Selection
		var pageName = $(this).val();

		// Evaluate page selection and load preview
		// Variable templates can be found in otw-admin-bm-variables.js
		$.each( templates, function( index, obj) {
			
			if ( obj.name == pageName ) {
				// Preview is disabled.
				//$('.js-preview').load( frontendURL + obj.url );		
			}
			
		});
		
		if( typeof( js_template_options[ this.value ] ) == 'object' ){
		
			$( '.default_thumb_width' ).html( js_template_options[ this.value ].width );
			$( '.default_thumb_height' ).html( js_template_options[ this.value ].height );
		}else{
			$( '.default_thumb_width' ).html( '' );
			$( '.default_thumb_height' ).html( '' );
		}
		
		$('.js-mosaic-settings').hide();
		$('.js-slider-settings').hide();
		$('.js-news-settings').hide();
		$('.js-horizontal-settings').hide();

		// Add Mosaic Specific Settings to the page
		if( pageName == '1-3-mosaic' || pageName == '1-4-mosaic' ) {
			// Show Mosaic Specific Settings
			$('.js-mosaic-settings').show();

		} else if ( pageName == '2-column-news' || pageName == '3-column-news' || pageName == '4-column-news' ) {
			// Show News Specific Settings
			$('.js-news-settings').show();

		} else if (
				pageName == 'slider' ||
				pageName == '3-column-carousel' || 
				pageName == '4-column-carousel' || 
				pageName == '5-column-carousel' ||
				pageName == '2-column-carousel-wid' ||
				pageName == '3-column-carousel-wid' ||
				pageName == '4-column-carousel-wid'
			) {
			// Show Slider / Carousel Specific Settings
			$('.js-slider-settings').show();

		} else if ( pageName == 'horizontal-layout' ) {
			$('.js-horizontal-settings').show();
		}

	});

	/*
	$('.js-template-style').otwpreview();
	*/
	
	/**
	 * POST and PAGES custom Meta BOX media selection
	 */

	$('.js-otw-media-type').on('change', function(e) {
		
		var mediaType = $(this).val();

		$('.js-meta-youtube').hide();
		$('.js-meta-vimeo').hide();
		$('.js-meta-soundcloud').hide();
		$('.js-meta-image').hide();
		$('.js-meta-slider').hide();

		switch ( mediaType ) {
			case 'youtube':
				$('.js-meta-youtube').show();
			break;
			case 'vimeo':
				$('.js-meta-vimeo').show();
			break;
			case 'soundcloud':
				$('.js-meta-soundcloud').show();
			break;
			case 'img':
				$('.js-meta-image').show();
			break;
			case 'slider':
				$('.js-meta-slider').show();
			break;
		}

	});

	/**
	 * Make Slider Elements Sortable
	 */
	$('.js-meta-slider-preview').sortable({
		update: function( event, ui ) {
			updateSliderAssets();
		}
	});

	/**
	 * Add functionality to delete images from slider
	 */

	$(document).on('click', '.b-delete_btn', function(e) {
		e.preventDefault();
		
		// Get current selected item
		item = $(this).parent();

		//Remove item from the list
		$(item).remove();

		// Update assets list
		updateSliderAssets ();
	});

	/**
	 * Add Functionality for WordPress Media Upload
	 */
	$(document).on('click', '.js-add-image', function(e) {
		e.preventDefault();
		/**
		 * WordPress Based Media Selection and Upload (Images)
		 * Used for Post Meta information: Images and Slider Images
		 */

		if( frameUpload ) {
			frameUpload.open();
			return;
		}

		frameUpload = wp.media({
			id: 'otw-bm-media-upload',
			// Set the title of the modal.
			title: $messages['modal_title'],
			multiple: false,
			// Tell the modal to show only images.
			library: {
				type: 'image'
			},
			// Customize the submit button.
			button: {
				// Set the text of the button.
				text: $messages['modal_btn'],
				// Change close: false, in order to prevent window to close on selection
				close: true
			}
		});

		frameUpload.on( 'select', function() {
			var attachements = frameUpload.state().get('selection').first().id;
			var attachementURL = wp.media.attachment( attachements ).attributes.url;

			if( $('.js-otw-media-type').val() === 'slider' ) {

				imgTAG = '<li class="b-slider__item" data-src="'+attachementURL+'">';
				imgTAG += '<a href="#" class="b-delete_btn"></a>';
				imgTAG += '<img src="'+attachementURL+'" width="100" />';
				imgTAG += '</li>';
				
				$('.js-meta-slider-preview').append( imgTAG ); //Display IMG
				updateSliderAssets();

			} else {
				// Create HTML for visual effect
				var imgTAG = '<img src="'+attachementURL+'" width="150" />';
				// Append HTML for visual preview
				$('.js-img-preview').html( imgTAG ); //Display IMG

				// Add Image to Hidden input - save to DB
				$('.js-img-url').val( attachementURL );
			}

		})

		frameUpload.open();
	});


	/**
	 * Capture All Links from Preview and Prevent Default
	 * Prevent Browser to follow # link
	 */
	$('.js-preview').on('click', 'a', function(e) {
		e.preventDefault();
	});

	/**
	 * Interface for Meta Elements
	 * Drag & Drop support + Sortable Support
	 */
	$('.js-meta-active, .js-meta-inactive').sortable({
		connectWith: ".b-meta-box",
		update: function( event, ui ) {
			updateBlogMetaElements();
		},
		stop: function( event, ui ) {
			$.event.trigger({
				type: "metaEvent"
			});
		}
	});

	/**
	 * Interface for Blog List Elements
	 * Drag & Drop support + Sortable Support
	 */
	$('.js-bl-active, .js-bl-inactive').sortable({
		connectWith: ".b-bl-box",
		update: function( event, ui ) {
			updateBlogListElements();
		},
		stop: function( event, ui ) {
			$.event.trigger({
				type: "listEvent"
			});
		}
	});

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
	 
	$('#white_spaces').change( function(){
		
		if( this.value == 'no' ){
			$( '#white_spaces_color_container' ).hide();
		}else{
			$( '#white_spaces_color_container' ).show();
		}
	 } );
	 
	if( $('#white_spaces').val() == 'no' ){
		$( '#white_spaces_color_container' ).hide();
	}else{
		$( '#white_spaces_color_container' ).show();
	}

});

/**
 * Iterate Assets from media slider and put them into a hidden field
 * Used to save possition + image path in DB
 */
function updateSliderAssets () {
	var imagesArray = new Array();
	$('.b-slider-preview > .b-slider__item').each(function( item, value) {
		imagesArray.push( $(value).data('src') );
	});

	// Add Array to hidden input
	$('.js-img-slider-url').val( imagesArray );
}

/**
 * Iterate On Blog List Items
 * Detect Items that will be used in the list
 * Drag & Drop List Functionality
 */
function updateBlogListElements () {
	var elementsArray = new Array();

	$('.js-bl-active > .js-bl--item').each( function( item, value )  {
		elementsArray.push( $(value).data('value') );
	});

	$('.js-blog-items').val( elementsArray );
}

/**
 * Iterate On Blog Meta Items
 * Detect Items that will be used in the meta
 * Drag & Drop List Functionality
 */
function updateBlogMetaElements () {
	var elementsArray = new Array();

	$('.js-meta-active > .js-meta--item').each( function( item, value )  {
		elementsArray.push( $(value).data('value') );
	});

	$('.js-meta-items').val( elementsArray );
}


/**
 * Get state of Blog List Elements and Blog Meta Elements
 * Modify interface based on current input Edit / Add Error
 */
function setupBlogElements () {
	blogElements = $('.js-blog-items').val();
	metaElements = $('.js-meta-items').val();
	
	if( typeof blogElements !== 'undefined' ) {
		blogItems = blogElements.split(',');

		$(blogItems).each( function( item, value ) {
			
			$('.js-bl-inactive > .js-bl--item').each( function( blItem, blValue )  {
				if( $(blValue).data('value') == value ) {

					$('.js-bl-active').append( $(blValue) );
				} 
			});

		});
	}
}