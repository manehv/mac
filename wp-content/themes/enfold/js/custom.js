jQuery(document).ready(function($)
{
	//OLD Code
	//$.lockfixed("#idSticky",{offset: {top: 120, bottom: 70}});
	//New Code
	if($(window).height() > ($("#idSticky").height() + 200) ){  ; // 200 is buffer kept for menu and footer
		$.lockfixed("#idSticky",{offset: {top: 120, bottom: 70}});
	}
	//for showing gallery
	 $('#showImage').click(function(){
		 $('.woocommerce-main-image').click();
		 return false;
	 });
	 //onclick="javascript: document.getElementsByClassName('woocommerce-main-image').item(0).click();return false;"
	 $('#scroll-top').click(function(){
			$('html,body').animate({ scrollTop: $('.clsContent').offset().top - 140 },1000, function () {
			});
			return false;
		});
	

	$('#des-top').click(function(){
			$('html,body').animate({  scrollTop: $('#desc').offset().top - 140 },1000, function () {
	});
	return false;
	});

	$(".clsFinish").each(function() {
		var text = $(this).text();
		text = text.replace("pa_color", "color");
		$(this).text(text);
	});

	if( $('.variations_form').length > 0 ){
		$('#idSticky .single_add_to_cart_button').on( 'click', function(e){
			e.preventDefault();
			console.log("kidnap");
			$('.clsContent .single_add_to_cart_button').click();
		});
	}
	setTimeout(function(){ // Do not remove setTimeout as woocoomerce "on" event is running very late. so its much needed
			$(document).off('click',".plus, .minus")
								 .on("click",".plus, .minus", function(e) {
		
		// Get values
		var $qty		= $( this ).closest( '.quantity' ).find( '.qty' ),
			currentVal	= parseFloat( $qty.val() ),
			max			= parseFloat( $qty.attr( 'max' ) ),
			min			= parseFloat( $qty.attr( 'min' ) ),
			step		= $qty.attr( 'step' ),
			$qty_all = $( '.qty' ) ;

		// Format values
		if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
		if ( max === '' || max === 'NaN' ) max = '';
		if ( min === '' || min === 'NaN' ) min = 0;
		if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

		// Change the value
		if ( $( this ).is( '.plus' ) ) {
			if ( max && ( max == currentVal || currentVal > max ) ) {
				$qty_all.val(max);
			} else {
				var $val = currentVal + parseFloat( step );
				$qty_all.val($val);
			}

		} else {

			if ( min && ( min == currentVal || currentVal < min ) ) {
				$qty_all.val(min);
			} else if ( currentVal > 0 ) {
				var $val = currentVal - parseFloat( step );
				$qty_all.val( $val);
			}

		}

		
		// Trigger change event
		$qty.trigger( 'change' );
	});	
	}, 1000); // Timeout
});