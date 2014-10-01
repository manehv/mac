jQuery(document).ready(function($)
{
	$.lockfixed("#idSticky",{offset: {top: 120, bottom: 70}});
	
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
});