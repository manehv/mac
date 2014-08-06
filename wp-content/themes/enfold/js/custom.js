jQuery(document).ready(function($)
{
	
	
	$.lockfixed("#idSticky",{offset: {top: 150, bottom: 114}});
		 
/*		if (!!$('#idSticky').offset()) 
		{
 
				var stickyTop = $('#idSticky').offset().top; // returns number
		
		  
				$(window).scroll(function(){ // scroll event
		
		 		var windowTop = $(window).scrollTop(); // returns number
		
			 if ( $(window).width() > 979 )
			{	
				
					if (stickyTop < windowTop){
				
						$('#idSticky').css({ position: 'fixed', top: 0 });
					}
					else {
				
						$('#idSticky').css('position','static');
					}
			}		
		
				});
	  }
	
	*/ 
	
	/*	$.lockfixed("#idSticky",{offset: {top: 150, bottom: 70}});
	
	$( window ).resize(function() {
	if ( $(window).width() > 979 )
		$.lockfixed("#idSticky",{offset: {top: 150, bottom: 70}});
	else	
	  $( "#idSticky" ).unbind('scroll resize orientationchange load lockfixed:pageupdate');
 });
	
	function checkWidth() {
        var windowSize = $(window).width();

        if (windowSize <= 979) {
           $( "#idSticky" ).unbind('scroll resize orientationchange load lockfixed:pageupdate');
        }
        else {
            $.lockfixed("#idSticky",{offset: {top: 150, bottom: 70}});
        }
    }
    checkWidth();
    $(window).resize(checkWidth);*/
});