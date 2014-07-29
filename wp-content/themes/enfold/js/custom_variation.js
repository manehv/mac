jQuery(function() 
{
		
	 jQuery('.clshide').hide();
	 var variation_id=jQuery('.active').attr('variation_id');
	 jQuery('.'+variation_id).show();
		
	 
	 
	 //updating qty
	 jQuery('.single_add_to_cart_button').click(function(){
	 jQuery('.set-aqy').val(jQuery('.quantity.buttons_added').find('.qty').val());
		 
	});
	
	 
	 
	//for showing galary
	 jQuery('#showImage').click(function(){
		 jQuery('.woocommerce-main-image').click();
		 return false;
	 });
	 //onclick="javascript: document.getElementsByClassName('woocommerce-main-image').item(0).click();return false;"
	 
		//functionalty for click on colour 
  jQuery('.colour_click').click(function(){
		
		jQuery('.variation').removeClass('active');
		jQuery(this).addClass('active');
		var variation_id=jQuery(this).attr('variation_id');
  	jQuery('.clshide').hide();
		jQuery('.'+variation_id).show();
		jQuery('.model').children().removeClass('active');
		jQuery('.model').find('.'+variation_id).find('.variation').addClass('active');
	  jQuery('.clsVariation	').find('[variation_id='+variation_id+']').addClass('active');
		
		jQuery('.clsModel').parent().hide();
	
		var active_attr=[];
		var show_var=[];
		jQuery('.variation.active').each(function(){
			active_attr.push(jQuery(this).attr('attr-value'));			
		});

		for(var i=0;i<(product_variation.length);i++)
		{
			
			  for(var j=0;j<active_attr.length;j++)
				{
					 if(product_variation[i].attributes.attribute_color==active_attr[j])
					 {
						 jQuery('.model	').find('[variation_id='+product_variation[i].variation_id+']').show()
                                                                                           .parent().show();  						 
					 } 
				}
			 
		}
		
		
	});
	
	//functionalty for click on model
	 jQuery('.clsModel').click(function()
	 {
		
		  jQuery('.variation').removeClass('active');
		jQuery(this).addClass('active');
		var variation_id=jQuery(this).attr('variation_id');
  	jQuery('.clshide').hide();
		jQuery('.'+variation_id).show();
		jQuery('.model').children().removeClass('active');
		jQuery('.model').find('.'+variation_id).find('.variation').addClass('active');
	  jQuery('.clsVariation	').find('[variation_id='+variation_id+']').addClass('active');
		for(var i=0;i<(product_variation.length);i++)
		{
			if(variation_id==product_variation[i].variation_id)
			jQuery('.clsVariation	').find('[attr-value='+product_variation[i].attributes.attribute_color+']').addClass('active');	
		}
		
	
	 });
	
	
	//default model selected
	 
	 
	 if (typeof product_variation != 'undefined')
	 { 
	jQuery('.clsModel').hide();
	
		var active_attr=[];
		var show_var=[];
		jQuery('.variation.active').each(function(){
			active_attr.push(jQuery(this).attr('attr-value'));			
		});

		for(var i=0;i<(product_variation.length);i++)
		{
			
			  for(var j=0;j<active_attr.length;j++)
				{
					 if(product_variation[i].attributes.attribute_color==active_attr[j])
					 {
						// console.log(product_variation[i].variation_id);
						 jQuery('.model	').find('[variation_id='+product_variation[i].variation_id+']').show();
					 } 
				}
			 
		}
		
	 }
	 
	jQuery('#scroll-top').click(function(){
      	jQuery('html,body').animate({ scrollTop: 0 },1000, function () {
		   });
				return false;
	});
	

	jQuery('#des-top').click(function(){
		     jQuery('html,body').animate({  scrollTop: jQuery('#desc').offset().top - 120 },1000, function () {
        		   
	   });
     	return false;
	});
	
	
});