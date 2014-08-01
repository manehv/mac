jQuery(document).ready(function($)
{
	if ( $(window).width() > 979 ){
		$.lockfixed("#idSticky",{offset: {top: 100, bottom: 70}});
	}
});