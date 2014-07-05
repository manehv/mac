jQuery(function() 
{
  jQuery('.variation').click(function(){
		
		jQuery('.variation').removeClass('active');
		jQuery(this).addClass('active');
		var src=jQuery(this).attr('variation-image');
		jQuery('#v-image').attr('src',src);
		
	});

});