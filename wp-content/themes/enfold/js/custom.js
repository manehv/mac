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
	
});