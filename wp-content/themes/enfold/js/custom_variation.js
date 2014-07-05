jQuery(function() 
{
	 jQuery('.clshide').hide();
	 var variation_id=jQuery('.active').attr('variation_id');
	 jQuery('.'+variation_id).show();
		
  jQuery('.variation').click(function(){
		
		jQuery('.variation').removeClass('active');
		jQuery(this).addClass('active');
		var variation_id=jQuery(this).attr('variation_id');
		jQuery('.clshide').hide();
		jQuery('.'+variation_id).show();
		
		
	});

});