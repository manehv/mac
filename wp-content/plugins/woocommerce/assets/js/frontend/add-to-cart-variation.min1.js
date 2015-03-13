jQuery(document).ready(function($) {

	var temp=1;
	var obj;
	/**
	 * Variations form handling
	 */
	var clicked_radio='';
	$('form.variations_form')

		// On clicking the reset variation button
		.on('click','#clear', function( event ) {
    
			$('input:radio').prop('disabled',false)
			                 .prop('checked',false);
			//$(this).find('.variations input:radio:checked').each( function() {
				//$(this).checked = false;
			//}
			return false;
		} )

		// Upon changing an option
		.on( 'change','.variations input:radio', function(event) {
			
			$variation_form = $(this).closest('form.variations_form');
			$variation_form.find('input[name=variation_id]').val('').change();
				 
			$variation_form
				.trigger( 'woocommerce_variation_radio_change' )
				.trigger( 'check_variations', [ '', false ] );
		
			
			$(this).blur();

			if( $().uniform && $.isFunction( $.uniform.update ) ) {
				$.uniform.update();
			}

		})

		// Upon gaining focus
		.on( 'focusin', '.variations input:radio', function( event ) {

				clicked_radio=$(this).val()+'/'+$(this).attr('name');
			
		
				
			$variation_form = $(this).closest('form.variations_form');

			$variation_form
				.trigger( 'woocommerce_variation_radio_focusin' )
				.trigger( 'check_variations', [ $(this).attr('name'), true ] );

		})

		// Check variations
		.on( 'check_variations', function( event, exclude, focus ) {
			var all_set 			= true;
			var any_set 			= false;
			var showing_variation 	= false;
			var current_settings 	= {};
			var $variation_form 	= $(this);
			var $reset_variations	= $variation_form.find('.reset_variations');

			$variation_form.find('.variations input:radio:checked').each( function() {
	
 
				if ( $(this).val().length == 0 ) {
					all_set = false;
				} else {
					any_set = true;
				}
			

				if ( exclude && $(this).attr('name') == exclude ) {
					
					all_set = false;
					current_settings[$(this).attr('name')] = '';

				} else {
					
	            	// Encode entities
	            	value = $(this).val()
			            .replace(/&/g, '&')
			            .replace(/"/g, '"')
			            .replace(/'/g, "'")
			            .replace(/</g, '<')
			            .replace(/>/g, '>');
 
					// Add to settings array
					current_settings[ $(this).attr('name') ] = value;
					
				}

			});

			var product_id			= parseInt( $variation_form.attr( 'data-product_id' ) );
			var all_variations		= window[ "product_variations_" + product_id ];
			
		
			
			// Fallback
			if ( ! all_variations ) 
				all_variations = window[ "product_variations" ];
			
	        var matching_variations = find_matching_variations(all_variations, current_settings );
				
				 if(matching_variations.length)
				 {
					 $('.hideform').show().fadeIn(1000);
					 
				 }
				 else
				 {
					 
					 $('.hideform').hide().slideUp(1000);
				 }
					
	       

	        if ( all_set ) {

	        	var variation = matching_variations.pop();
						
				

	        	if ( variation ) {
				if ( ! exclude ) {
					$variation_form.find('.single_variation_wrap').slideDown('200');
				}
	        		// Found - set ID
	            	$variation_form
	            		.find('input[name=variation_id]')
	            		.val( variation.variation_id )
	            		.change();
					
	            	$variation_form.trigger( 'found_variation', [ variation ] );

	            } else {

	            	// Nothing found - reset fields
	            	//$variation_form.find('.variations input:radio').val('');
					if ( ! exclude ) {
					$variation_form.find('.single_variation_wrap').slideUp('200');
				}
	            	if ( ! focus )
	            		$variation_form.trigger( 'reset_image' );

	            }

	        } else {

	            $variation_form.trigger( 'update_variation_values', [ matching_variations ] );

	            if ( ! focus )
	            	$variation_form.trigger( 'reset_image' );

				if ( ! exclude ) {
					$variation_form.find('.single_variation_wrap').slideUp('200');
				}

	        }

	        if ( any_set ) {

	        	if ( $reset_variations.css('visibility') == 'hidden' )
	        		$reset_variations.css('visibility','visible').hide().fadeIn();

	        } else {

				$reset_variations.css('visibility','hidden');

			}

		} )

		// Reset product image
		.on( 'reset_image', function( event ) {

			var $product 		= $(this).closest( '.product' );
			var $product_img 	= $product.find( 'div.images img:eq(0)' );
			var $product_link 	= $product.find( 'div.images a.zoom:eq(0)' );
			var o_src 			= $product_img.attr('data-o_src');
			var o_title 		= $product_img.attr('data-o_title');
	        var o_href 			= $product_link.attr('data-o_href');

	        if ( o_src && o_href && o_title ) {
		        $product_img
		        	.attr( 'src', o_src )
		        	.attr( 'alt', o_title )
		        	.attr( 'title', o_title );
	            $product_link
	            	.attr( 'href', o_href );
	        }

		})

		// Disable option fields that are unavaiable for current set of attributes
		.on('update_variation_values', function( event, variations ) {
         
	     $variation_form = $(this).closest(".variations_form");
       
			 
			 
	        // Loop through selects and disable/enable options based on selections
	        $variation_form.find('.variations input:radio').each(function( index, el ){

				  	current_attr_radio = $(el);
						
					//	current_attr_radio.parent().append('<input id="'+current_attr_radio[0].id+'" type="radio" name="'+current_attr_radio[0].name+'" value="'+current_attr_radio[0].value+'">'+current_attr_radio[0].value);
	       
						//$('.variations_form').find('input:radio').removeClass('active');
	        	// Disable all
	        	current_attr_radio.find('input:radio').attr('checked','');
					 
						
	        	// Get name
		        var current_attr_name 	= current_attr_radio.attr('name');
			
					//	console.log(current_attr_name);

		        // Loop through variations
		        for ( num in variations ) {

						
		            var attributes = variations[ num ].attributes;

		            for ( attr_name in attributes ) {

		                var attr_val = attributes[ attr_name ];

		                if ( attr_name == current_attr_name ) {

		                    if ( attr_val ) {
                         
													// Decode entities
		                    	attr_val = $("<div/>").html( attr_val ).text();

		                    	// Add slashes
		                    	attr_val = attr_val.replace(/'/g, "\\'");
		                    	attr_val = attr_val.replace(/"/g, "\\\"");

		                    	// Compare the meercat
												 
													$('.variations_form').find('input:radio[value="' + attr_val.toUpperCase() + '"]').addClass( 'active' );
													//current_attr_radio.find('input:radio[value="' + attr_val.toUpperCase() + '"]').addClass( 'active' );

		                    } else {
		                    	current_attr_radio.find('option').addClass('active');
		                    }

		                }

		            }

		        }
		        
		     //  $('.variations_form').find('input[type="radio"]:not(.active)').hide();
		     
		         //current_attr_select.find("option:gt(0):not(.active)").remove()
		         

	        });

			// Custom event for when variations have been updated
			$variation_form.trigger('woocommerce_update_variation_values');

		})
	
	/*.on("update_variation_values", function(t, n) {
            $variation_form = e(this).closest(".variations_form");
            $variation_form.find(".variations input:radio").each(function(t, r) {
                current_attr_select = e(r);
                current_attr_select.data("attribute_options") || current_attr_select.data("attribute_options", current_attr_select.find("option:gt(0)").get());
                current_attr_select.find("option:gt(0)").remove();
                current_attr_select.append(current_attr_select.data("attribute_options"));
                current_attr_select.find("option:gt(0)").removeClass("active");
                var i = current_attr_select.attr("name");
                for (num in n)
                    if (typeof n[num] != "undefined") {
                        var s = n[num].attributes;
                        for (attr_name in s) {
                            var o = s[attr_name];
                            if (attr_name == i)
                                if (o) {
                                    o = e("<div/>").html(o).text();
                                    o = o.replace(/'/g, "\\'");
                                    o = o.replace(/"/g, '\\"');
                                    current_attr_select.find('option[value="' + o + '"]').addClass("active")
                                } else current_attr_select.find("option:gt(0)").addClass("active")
                        }
                    }
                current_attr_select.find("option:gt(0):not(.active)").remove()
            });
            $variation_form.trigger("woocommerce_update_variation_values")
        })*/

		// Show single variation details (price, stock, image)
		.on( 'found_variation', function( event, variation ) {
	      	var $variation_form = $(this);
	       
	        var $product 		= $(this).closest( '.product' );
			var $product_img 	= $product.find( 'div.images img:eq(0)' );
			var $product_link 	= $product.find( 'div.images a.zoom:eq(0)' );
		
			var o_src 			= $product_img.attr('data-o_src');
			var o_title 		= $product_img.attr('data-o_title');
	        var o_href 			= $product_link.attr('data-o_href');

	        var variation_image = variation.image_src;
	        var variation_link = variation.image_link;
			var variation_title = variation.image_title;

			$variation_form.find('.variations_button').show();
	        $variation_form.find('.single_variation').html( variation.price_html + variation.availability_html );

	        if ( ! o_src ) {
	        	o_src = ( ! $product_img.attr('src') ) ? '' : $product_img.attr('src');
	            $product_img.attr('data-o_src', o_src );
	        }

	        if ( ! o_href ) {
	        	o_href = ( ! $product_link.attr('href') ) ? '' : $product_link.attr('href');
	            $product_link.attr('data-o_href', o_href );
	        }

	        if ( ! o_title ) {
	        	o_title = ( ! $product_img.attr('title') ) ? '' : $product_img.attr('title');
	            $product_img.attr('data-o_title', o_title );
	        }

	        if ( variation_image && variation_image.length > 1 ) {
	            $product_img
	            	.attr( 'src', variation_image )
	            	.attr( 'alt', variation_title )
	            	.attr( 'title', variation_title );
	            $product_link
	            	.attr( 'href', variation_link );
	        } else {
	            $product_img
	            	.attr( 'src', o_src )
	            	.attr( 'alt', o_title )
	            	.attr( 'title', o_title );
	            $product_link
	            	.attr( 'href', o_href );
	        }

	        
	       
	        var $single_variation_wrap = $variation_form.find('.single_variation_wrap');

	        if ( variation.sku )
	        	 $product.find('.product_meta').find('.sku').text( variation.sku );
	        else
	        	 $product.find('.product_meta').find('.sku').text('');

	        $single_variation_wrap.find('.quantity').show();
	        
	        if ( ! variation.is_in_stock && ! variation.backorders_allowed ) {
		        $variation_form.find('.variations_button').hide();
	        }
	        
	        if ( variation.min_qty )
	        	$single_variation_wrap.find('input[name=quantity]').attr( 'data-min', variation.min_qty ).val( variation.min_qty );
	        else
	        	$single_variation_wrap.find('input[name=quantity]').removeAttr('data-min');

	        if ( variation.max_qty )
	        	$single_variation_wrap.find('input[name=quantity]').attr('data-max', variation.max_qty);
	        else
	        	$single_variation_wrap.find('input[name=quantity]').removeAttr('data-max');

	        if ( variation.is_sold_individually == 'yes' ) {
	        	$single_variation_wrap.find('input[name=quantity]').val('1');
	        	$single_variation_wrap.find('.quantity').hide();
	        }

	        $single_variation_wrap.slideDown('200').trigger( 'show_variation', [ variation ] );

		} );

	/**
	 * Initial states and loading
	 */
	$('form.variations_form .variations input:radio').change();


	/**
	 * Helper functions for variations
	 */

    // Search for matching variations for given set of attributes
    function find_matching_variations(product_variations,settings ) {
			
			
			
			  //for disabled product attr			
					$('input[type=radio]').prop('disabled',true);
					
					
	      	var tempvar=0;
					var cmpkey=[];
					var product_variations1=product_variations;
		     jQuery('.variations fieldset').find('input[type=radio]:first').each(function()
				 { 
					 
					  var pname=jQuery(this).attr('name');
						
						if(tempvar==0)
						{
							 tempvar=1;
								$('input[name='+pname+']').prop('disabled',false); 
						}
						
						if(settings[pname])
						{	
							var tempcol=[];
						  cmpkey.push({name:pname,value:settings[pname]});
							tempcol=get_filter_collection(product_variations1,cmpkey);
							if(tempcol.length)
					    product_variations1=tempcol;
				      
						}	
		
			  });
			
				var enabled_attr=[0];
			
				for(var i=0;i<product_variations1.length;i++)
				{	
				   for (var key in product_variations1[i].attributes) 
					 {
						 enabled_attr[product_variations1[i].attributes[key]]=product_variations1[i].attributes[key];
					 }
				}
		
	 
		  for (var key in enabled_attr) 
			{	
			
				if(enabled_attr[key])
				{	
		     $('input[value='+(enabled_attr[key]).toUpperCase()+']').prop('disabled',false);
				                                                        
				} 
	
			}	
			
			$(':radio').each(function(){
          if ($(this).is(':disabled')) {
           $(this).attr('checked', false);
		     }
		  });
			
			
	   		$('.variations fieldset').each(function(){
				
				if($(this).find('input[type=radio]').not(':disabled').length==1)
				{
					$(this).find('input[type=radio]').not(':disabled').prop('checked',true);
				
	    	}
				
			});
			
			
		    var matching = [];
        for (var i = 0; i < product_variations.length; i++) {
        	var variation = product_variations[i];
        	var variation_id = variation.variation_id;
			    
			
			if (variations_match(variation.attributes,settings)) {
			    
				   matching.push(variation);
			     var value,valtxt='';
					 for (var key in variation.attributes) {
							
						  valtxt += variation.attributes[key]+" ";
							
						}
						
				
					 if(temp==1)//for when page load
					 {	
						 $('.variations').find('tr').find('input[type=radio]').attr('name');
						 temp=0;
						 for (var key in variation.attributes) 
						 { 
							  $('input[value='+(variation.attributes[key]).toUpperCase()+']').prop('checked',true);
						 }
           }
           
					  $('.variation_image').attr('src',variation.image_src);
		        $('#prodtitle').text($('#prodtitle').attr('title')+" "+valtxt);
						$('#sku').text(variation.sku);
					  $('#price').html((variation.price_html));
						$('#addtocart').val(variation.variation_id);
 				    $('del').remove();
								
          }
         
        }
 
        return matching;
    }
    
    function get_filter_collection(productCollection,filtter_json)
		{
		
			var trmpobj=[];
			var chk=0;
			for(var i=0;i<productCollection.length;i++)
			{
				chk=0;
				 for(var j=0;j<filtter_json.length;j++)
				 {
					 if(productCollection[i].attributes[filtter_json[j].name].toUpperCase()==(filtter_json[j].value).toUpperCase())
					 {
					   chk=1;
					 }
					 else
					 {
						 chk=0;
					 }
					 
				 }
				 if(chk==1)
				  trmpobj.push(productCollection[i]);	 
				
			}
		//	console.log(productCollection);
		return trmpobj;
		}

	// Check if two arrays of attributes match
    function variations_match( attrs1, attrs2 ) {
			
			var match = true;
        for ( attr_name in attrs1 ) {
			      var val1 = attrs1[ attr_name ];
            var val2 = attrs2[ attr_name ];
              
						 if(val1 !== undefined && val2 !== undefined)
						 {
						   val1 = attrs1[ attr_name ].toUpperCase();
               val2 = attrs2[ attr_name ].toUpperCase();
           	 
						 }
				
            if ( val1 !== undefined && val2 !== undefined && val1.length != 0 && val2.length != 0 && val1 != val2 ) {
                match = false;
            }
        }
     
        return match;
    }
    
   $('.single_add_to_cart_button').click(function(){
          	   $('.set-aqy').val(jQuery('.quantity.buttons_added').find('.qty').val());
		 
	           });

});