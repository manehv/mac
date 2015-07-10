//global for tracking open and focused toolbar panels on refresh

var openGroups = [];

var focusedEl = null;



//backbutton and hash bookmarks support

var hash = {

	storedHash: '',

	currentTabHash: '', //The hash that's only stored on a tab switch

	cache: '',

	interval: null,

	listen: true, // listen to hash changes?

	

	 // start listening again

	startListening: function() {

		setTimeout(function(){hash.listen = true;}, 600);

	},

	 // stop listening to hash changes

	stopListening:function(){hash.listen = false;},

	//check if hash has changed

	checkHashChange:function(){

		var locStr = hash.currHash();

		if(hash.storedHash != locStr) {

			if(hash.listen == true) hash.refreshToHash(); ////update was made by back button

			hash.storedHash = locStr;

		}

		if(!hash.interval) hash.interval = setInterval(hash.checkHashChange, 500);

	},

	

	//refresh to a certain hash

	refreshToHash: function(locStr) {

		if(locStr) var newHash = true;

		locStr = locStr || hash.currHash();

		updateCSS(locStr);

		// remember which groups are open

		openGroups = [];

		jQuery('div.theme-group-content').each(function(i){

			if(jQuery(this).is(':visible')){openGroups.push(i);}

		});

		

		// remember any focused element

		focusedEl = null;

		jQuery('form input, form select, form .texturePicker').each(function(i){

			if(jQuery(this).is('.focus')){focusedEl = i;}

		});

		

		// if the hash is passed

		if(newHash){ hash.updateHash(locStr, true); }

	},

	

	updateHash: function(locStr, ignore) {

		if(ignore == true){ hash.stopListening(); }

		window.location.hash = locStr;

		if(ignore == true){ 

			hash.storedHash = locStr; 

			hash.startListening();

		}

		

	},
	updateHashPreview: function(locStr, ignore) {

		if(ignore == true){ hash.stopListening(); }

		var hashstore = window.location.hash = locStr;
		
		//var iframeurl = document.getElementById('testiframe').src;
		var iframeurl = jQuery('#testiframe').attr('src');
			iframeurl = iframeurl ? iframeurl : '';
			
		var spliturl = iframeurl.split('#');
		var newurl = spliturl[0];
		//jQuery('#testiframe').src()
		//document.getElementById('testiframe').src = newurl +"#"+hashstore;
		jQuery('#testiframe').attr('src', newurl +"#"+hashstore);  
		
		if(ignore == true){ 

			hash.storedHash = locStr; 

			hash.startListening();

		}

		

	},

	

	clean: function(locStr){return locStr.replace(/%23/g, "").replace(/[\?#]+/g, "");},

	

	currHash: function(){return hash.clean(window.location.hash);},

	

	currSearch: function(){return hash.clean(window.location.search);},

	

	init: function(){

		hash.storedHash = '';

		hash.checkHashChange();

	}	

};



jQuery.fn.spinDown = function() {

	return this.click(function() {

		var $this = jQuery(this);

		$this.next().slideToggle(100);

		$this.parent().siblings().children('.state-active').click(); //close open tabs

		$this.prev().toggleClass('not-active');

		$this.find('.icon').toggleClass('icon-triangle-1-s').end().toggleClass('state-active');

		//jQuery('li.ui-state-default .ui-state-active').removeClass('ui-state-active');

		$this.find('.ui-icon').toggleClass('ui-icon-triangle-1-s').end().toggleClass('ui-state-active');

		if($this.is('.corner-all')) { $this.removeClass('corner-all').addClass('corner-top'); }

		else if($this.is('.corner-top')) { $this.removeClass('corner-top').addClass('corner-all'); }

		if($this.is('.ui-corner-all')) { $this.removeClass('ui-corner-all').addClass('ui-corner-top'); }

		else if($this.is('.ui-corner-top')) { $this.removeClass('ui-corner-top').addClass('ui-corner-all'); }

		return false;

	});

};


function formChange1(){

	var locStr = jQuery('div[id="preview-form-styling-setting"] :input[class!="txtmulti"]').not('.arf_padding_exclude').serialize();

	//locStr = hash.clean(locStr);
	var arf_isformchange = jQuery('#arf_isformchange').val();
		arf_isformchange = arf_isformchange !== undefined ? arf_isformchange : 1;
	
	if( arf_isformchange == 1 )
	{
		updateCSS(locStr);
		hash.updateHashPreview(locStr, true);
	}
};


jQuery(document).ready(function($){

    // hover class toggles in app panel

    jQuery('.state-default').hover(

    	function(){ jQuery(this).addClass('state-hover'); }, 

    	function(){ jQuery(this).removeClass('state-hover'); }

    );

    

    $('div.theme-group .theme-group-header').addClass('corner-all').spinDown();

    

    // focus and blur classes in form

	$('input, select').focus(function(){

		$('input.focus, select.focus').removeClass('focus');

		$(this).addClass('focus');

	}).blur(function(){ $(this).removeClass('focus');});

	

	// change event in form

	/*$('form[name="frm_settings_form"]').bind('change', function() {

		formChange();

		return false;

	});*/
	
	$('div[id="preview-form-styling-setting"]').not('input[class="arf_padding_exclude"]').bind('change', function() {

		formChange1();

		return false;

	});


});


jQuery(document).ready(function(){
        
	if( jQuery.isFunction( jQuery().colpick ) )
	{
		jQuery('.arf_coloroption_sub:not(.arf_clr_disable)').colpick({
			layout:'hex',
			submit:0,
			onBeforeShow:function(){
				var fid 	= jQuery(this).find('.arfhex').attr('data-fid');
				var color 	= jQuery('#'+fid).val();
                                 var did = fid.replace('arf_divider_bg_color_','');
				if( jQuery(this).attr('data-cls') == 'arf_clr_disable'){
                                    jQuery('#arf_divider_bg_color_disabled_'+did+'.arf_clr_disable .arfhex').css('background',color);
				}
				var	new_color= color.replace('#','');
				if( new_color )
					jQuery(this).colpickSetColor(new_color);
			},
			onChange:function(hsb,hex,rgb,el,bySetColor) {
                                
				if(typeof arf_set_on_chnage_color_value_in_out_site == 'function'){
					arf_set_on_chnage_color_value_in_out_site(hsb,hex,rgb,el,bySetColor);	
				}
				
				jQuery(el).find('.arfhex').css('background','#'+hex);
				if(!bySetColor) jQuery(el).val(hex);
				var fid = jQuery(el).find('.arfhex').attr('data-fid');
				if( fid )
					jQuery('#'+fid).val('#'+hex);
                                var did = fid.replace('arf_divider_bg_color_','');
                                if( jQuery(el).attr('data-cls') == 'arf_clr_disable'){
                                    jQuery('#arf_divider_bg_color_disabled_'+did+'.arf_clr_disable .arfhex').css('background','#'+hex);
                                }
			}
		});
		
		jQuery('.colpick_hex_field input').on('change', function(){
			if(jQuery('#testiframe').contents().find("body").length)
			{
				if( jQuery('#tab_styling').hasClass("current") )
				{	
					formChange1();
				}
			}
		});
		
		// apply colors to iframe
		jQuery('.colpick_hex').on('mouseup', function(){
			document.dragging = false;
			if(jQuery('#testiframe').contents().find("body").length)
			{
				if( jQuery('#tab_styling').hasClass("current") )
				{	
					formChange1();
				}
			}
		});
	}

});