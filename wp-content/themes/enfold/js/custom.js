jQuery(document).ready(function($)
{
	$( window ).resize(function() {
		if ( $(window).width() > 979 )
			$.lockfixed("#idSticky",{offset: {top: 100, bottom: 70}});
		else	
		$( "#idSticky" ).unbind('scroll resize orientationchange load lockfixed:pageupdate');
		
	});
});