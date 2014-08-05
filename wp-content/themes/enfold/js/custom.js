jQuery(document).ready(function($)
{
	/*$( window ).resize(function() {
		if ( $(window).width() > 979 )
			$.lockfixed("#idSticky",{offset: {top: 150, bottom: 70}});
		else	
		$( "#idSticky" ).unbind('scroll resize orientationchange load lockfixed:pageupdate');
	});*/
	
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
    $(window).resize(checkWidth);
});