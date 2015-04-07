(function($)
{
    "use strict";

    $(document).ready(function()
    {
        var aviabodyclasses = AviaBrowserDetection('html');

		$.avia_utilities = $.avia_utilities || {};
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && 'ontouchstart' in document.documentElement)
    	{
    		$.avia_utilities.isMobile =  true;
    	}
    	else
    	{
    		$.avia_utilities.isMobile =  false;
    	}
    	
        //check if user uses IE7 - if yes don't execute the function or the menu will break
        if(aviabodyclasses.indexOf("avia-msie-7") == -1) avia_responsive_menu();

        // decreases header size when user scrolls down
        avia_header_size();

        //show scroll top button
        avia_scroll_top_fade();

        //creates search tooltip
        new $.AviaTooltip({"class": 'avia-search-tooltip',data: 'avia-search-tooltip', event:'click', position:'bottom', scope: "body", attach:'element'});

        //creates relate posts tooltip
        new $.AviaTooltip({"class": 'avia-related-tooltip', data: 'avia-related-tooltip', scope: ".related_posts", attach:'element', delay:0});

        //creates ajax search
        new $.AviaAjaxSearch({scope:'#header'});

		// actiavte portfolio sorting
		if($.fn.avia_iso_sort)
		$('.grid-sort-container').avia_iso_sort();

		//activates the mega menu javascript
		if($.fn.aviaMegamenu)
		$(".main_menu .menu").aviaMegamenu({modify_position:true});
		
		$.avia_utilities.avia_ajax_call();
		
		
    });

	$.avia_utilities = $.avia_utilities || {};
	
	$.avia_utilities.avia_ajax_call = function(container)
	{
		if(typeof container == 'undefined'){ container = 'body';};
		
		
		$('a.avianolink').on('click', function(e){ e.preventDefault(); });
        $('a.aviablank').attr('target', '_blank');

        //activates the prettyphoto lightbox
        $(container).avia_activate_lightbox({callback:'avia_lightbox_callback'});
        
        //scrollspy for main menu. must be located before smoothscrolling
		if($.fn.avia_scrollspy)
		{
			if(container == 'body')
			{
				$('body').avia_scrollspy({target:'.main_menu .menu li > a'});
			}
			else
			{
				$('body').avia_scrollspy('refresh');
			}
		}

		//smooth scrooling
		if($.fn.avia_smoothscroll)
		$('a[href*=#]', container).avia_smoothscroll(container);

		avia_small_fixes(container);

		avia_hover_effect(container);

		avia_iframe_fix(container);

		//activate html5 video player
		if($.fn.avia_html5_activation && $.fn.mediaelementplayer)
		$(".avia_video, .avia_audio", container).avia_html5_activation({ratio:'16:9'});

	}
	
	
	// -------------------------------------------------------------------------------------------
	// Error log helper
	// -------------------------------------------------------------------------------------------
	
	$.avia_utilities.log = function(text, type, extra)
	{
		if(typeof console == 'undefined'){return;} if(typeof type == 'undefined'){type = "log"} type = "AVIA-" + type.toUpperCase(); 
		console.log("["+type+"] "+text); if(typeof extra != 'undefined') console.log(extra); 
	}



	// -------------------------------------------------------------------------------------------
	// modified SCROLLSPY by bootstrap
	// -------------------------------------------------------------------------------------------

	
	  function AviaScrollSpy(element, options)
	  {
	  	var self = this;
	  
		    var process = $.proxy(self.process, self)
		      , refresh = $.proxy(self.refresh, self)
		      , $element = $(element).is('body') ? $(window) : $(element)
		      , href
		    self.$body = $('body')
		    self.$win = $(window)
		    self.options = $.extend({}, $.fn.avia_scrollspy.defaults, options)
		    self.selector = (self.options.target
		      || ((href = $(element).attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) //strip for ie7
		      || '')
		    
		   	self.activation_true = false;
		   	
		    if(self.$body.find(self.selector + "[href*=#]").length)
		    {
		    	self.$scrollElement = $element.on('scroll.scroll-spy.data-api', process);
		    	self.$win.on('av-height-change', refresh);
		    	self.$body.on('av_resize_finished', refresh);
		    	self.activation_true = true;
		    	self.checkFirst();
		    	
		    	setTimeout(function()
	  			{
		    		self.refresh()
		    		self.process()
		    		
		    	},100);
		    }
	    
	  }
	
	  AviaScrollSpy.prototype = {
	
	      constructor: AviaScrollSpy
		, checkFirst: function () {
		
			var current = window.location.href.split('#')[0],
				matching_link = this.$body.find(this.selector + "[href='"+current+"']").attr('href',current+'#top');
		}
	    , refresh: function () {
	    
	    if(!this.activation_true) return;
	    
	        var self = this
	          , $targets
	
	        this.offsets = $([])
	        this.targets = $([])
	
	        $targets = this.$body
	          .find(this.selector)
	          .map(function () {
	            var $el = $(this)
	              , href = $el.data('target') || $el.attr('href')
	              , hash = this.hash
	              , hash = hash.replace(/\//g, "")
	              , $href = /^#\w/.test(hash) && $(hash)
	             
	            return ( $href
	              && $href.length
	              && [[ $href.position().top + (!$.isWindow(self.$scrollElement.get(0)) && self.$scrollElement.scrollTop()), href ]] ) || null
	          })
	          .sort(function (a, b) { return a[0] - b[0] })
	          .each(function () {
	            self.offsets.push(this[0])
	            self.targets.push(this[1])
	          })
	          
	      }
	
	    , process: function () {
	    	
	    	if(!this.offsets) return;
	    	
	        var scrollTop = this.$scrollElement.scrollTop() + this.options.offset
	          , scrollHeight = this.$scrollElement[0].scrollHeight || this.$body[0].scrollHeight
	          , maxScroll = scrollHeight - this.$scrollElement.height()
	          , offsets = this.offsets
	          , targets = this.targets
	          , activeTarget = this.activeTarget
	          , i

	        if (scrollTop >= maxScroll) {
	          return activeTarget != (i = targets.last()[0])
	            && this.activate ( i )
	        }
	
	        for (i = offsets.length; i--;) {
	          activeTarget != targets[i]
	            && scrollTop >= offsets[i]
	            && (!offsets[i + 1] || scrollTop <= offsets[i + 1])
	            && this.activate( targets[i] )
	        }
	      }
	
	    , activate: function (target) {
	        var active
	          , selector
	
	        this.activeTarget = target
	
	        $(this.selector)
	          .parent('.' + this.options.applyClass)
	          .removeClass(this.options.applyClass)
	
	        selector = this.selector
	          + '[data-target="' + target + '"],'
	          + this.selector + '[href="' + target + '"]'
	
	        active = $(selector)
	          .parent('li')
	          .addClass(this.options.applyClass)
	
	        if (active.parent('.dropdown-menu').length)  {
	          active = active.closest('li.dropdown').addClass(this.options.applyClass)
	        }
	
	        active.trigger('activate')
	      }
	
	  }
	
	
	 /* AviaScrollSpy PLUGIN DEFINITION
	  * =========================== */
	
	  $.fn.avia_scrollspy = function (option) {
	    return this.each(function () {
	      var $this = $(this)
	        , data = $this.data('scrollspy')
	        , options = typeof option == 'object' && option
	      if (!data) $this.data('scrollspy', (data = new AviaScrollSpy(this, options)))
	      if (typeof option == 'string') data[option]()
	    })
	  }
	
	  $.fn.avia_scrollspy.Constructor = AviaScrollSpy
	
	  $.fn.avia_scrollspy.defaults = {
	    offset: (parseInt($('.html_header_sticky #main').data('scroll-offset'), 10)) + parseInt($('html').css('margin-top'),10),
	    applyClass: 'current-menu-item'
	  }






    // -------------------------------------------------------------------------------------------
    // detect browser and add class to body
    // -------------------------------------------------------------------------------------------

    function AviaBrowserDetection(outputClassElement)
    {
        if(typeof($.browser) !== 'undefined')
        {
            var bodyclass = '';

            if($.browser.msie){
                bodyclass += 'avia-msie';
            }else if($.browser.webkit){
                bodyclass += 'avia-webkit';
            }else if($.browser.mozilla)
            {
                bodyclass += 'avia-mozilla';
            }

            if($.browser.version) bodyclass += ' ' + bodyclass + '-' + parseInt($.browser.version) + ' ';

            if($.browser.ipad){
                bodyclass += ' avia-ipad ';
            }else if($.browser.iphone){
                bodyclass += ' avia-iphone ';
            }else if($.browser.android){
                bodyclass += ' avia-android ';
            }else if($.browser.win){
                bodyclass += ' avia-windows ';
            }else if($.browser.mac){
                bodyclass += ' avia-mac ';
            }else if($.browser.linux){
                bodyclass += ' avia-linux ';
            }
        }

        if(outputClassElement) $(outputClassElement).addClass(bodyclass)
        
        return bodyclass;
    }



    // -------------------------------------------------------------------------------------------
	// responsive menu function
	// -------------------------------------------------------------------------------------------

    function avia_responsive_menu()
    {
    	var $html = $('html'), win = $(window), header = $('.responsive #header');

    	if(!header.length) return;

    	var menu 			  	= header.find('.main_menu ul:eq(0)'),
	    	first_level_items 	= menu.find('>li').length,
	    	bottom_menu 	  	= $('html').is('.html_bottom_nav_header'),
	    	container			= $('#wrap_all'),
    		show_menu_btn		= $('#advanced_menu_toggle'),
    		hide_menu_btn		= $('#advanced_menu_hide'),
    		mobile_advanced 	= menu.clone().attr({id:"mobile-advanced", "class":""}),
    		sub_hidden			= $html.is('.html_header_mobile_behavior'),
			insert_menu 		= function()
			{
				var after_menu = $('#header .logo');
				show_menu_btn.insertAfter(after_menu);
				mobile_advanced.find('.noMobile').remove();
				mobile_advanced.prependTo(container);
				hide_menu_btn.prependTo(container);
			
			},
			set_height = function()
			{
				var height = mobile_advanced.outerHeight(true), win_h  = win.height();
				
				if(height < win_h) height = win_h;
				container.css({'height':height});
				mobile_advanced.css({position:'absolute'});
			},
			hide_menu = function()
			{	
				container.removeClass('show_mobile_menu');
				container.css({'height':"auto", 'overflow':'hidden'});
				return false;
			},
			autohide = function()
			{
				if(container.is('.show_mobile_menu') && hide_menu_btn.css('display') == 'none'){ hide_menu(); }
			},
			show_menu = function()
			{
				if(container.is('.show_mobile_menu'))
				{
					hide_menu();
				}
				else
				{
					win.scrollTop(0);
					container.addClass('show_mobile_menu');
					set_height();
				}
				return false;
			};
		
		
		$html.on('click', '#mobile-advanced li a, #mobile-advanced .mega_menu_title', function()
		{
			var current = $(this);
			
			//if submenu items are hidden do the toggle
			if(sub_hidden)
			{
				var list_item = current.siblings('ul, .avia_mega_div');
				if(list_item.length)
				{
					if(list_item.hasClass('visible_sublist'))
					{
						list_item.removeClass('visible_sublist');
					}
					else
					{
						list_item.addClass('visible_sublist');
					}
					set_height();
					return false;
				}
			}
			
			//when clicked on anchor link remove the menu so the body can scroll to the anchor
			if(current.filter('[href*=#]').length)
			{
				container.removeClass('show_mobile_menu');
				container.css({'height':"auto"});
			}
			
		});
		

		show_menu_btn.click(show_menu);
		hide_menu_btn.click(hide_menu);
		win.on( 'debouncedresize',  autohide );
		insert_menu();
    }


    // -------------------------------------------------------------------------------------------
	// html 5 videos
	// -------------------------------------------------------------------------------------------
    $.fn.avia_html5_activation = function(options)
	{
		var defaults =
		{
			ratio: '16:9'
		};

		var options  = $.extend(defaults, options),
			isMobile = $.avia_utilities.isMobile;
		
		// if(isMobile) return;
		
		this.each(function()
		{
		var fv 			= $(this),
	      	id_to_apply = '#' + fv.attr('id'),
	      	posterImg 	= fv.attr('poster');
		

		fv.mediaelementplayer({
		    // if the <video width> is not specified, this is the default
		    defaultVideoWidth: 480,
		    // if the <video height> is not specified, this is the default
		    defaultVideoHeight: 270,
		    // if set, overrides <video width>
		    videoWidth: -1,
		    // if set, overrides <video height>
		    videoHeight: -1,
		    // width of audio player
		    audioWidth: 400,
		    // height of audio player
		    audioHeight: 30,
		    // initial volume when the player starts
		    startVolume: 0.8,
		    // useful for <audio> player loops
		    loop: false,
		    // enables Flash and Silverlight to resize to content size
		    enableAutosize: false,
		    // the order of controls you want on the control bar (and other plugins below)
		    features: ['playpause','progress','current','duration','tracks','volume'],
		    // Hide controls when playing and mouse is not over the video
		    alwaysShowControls: false,
		    // force iPad's native controls
		    iPadUseNativeControls: false,
		    // force iPhone's native controls
		    iPhoneUseNativeControls: false,
		    // force Android's native controls
		    AndroidUseNativeControls: false,
		    // forces the hour marker (##:00:00)
		    alwaysShowHours: false,
		    // show framecount in timecode (##:00:00:00)
		    showTimecodeFrameCount: false,
		    // used when showTimecodeFrameCount is set to true
		    framesPerSecond: 25,
		    // turns keyboard support on and off for this instance
		    enableKeyboard: true,
		    // when this player starts, it will pause other players
		    pauseOtherPlayers: true,
		    poster: posterImg,
		    success: function (mediaElement, domObject) { 
         	
				setTimeout(function()
				{
					if (mediaElement.pluginType == 'flash') 
					{	
						mediaElement.addEventListener('canplay', function() { fv.trigger('av-mediajs-loaded'); }, false);
					}
					else
					{
				        fv.trigger('av-mediajs-loaded').addClass('av-mediajs-loaded');
					}
				         
				     mediaElement.addEventListener('ended', function() {  fv.trigger('av-mediajs-ended'); }, false);  
				     
				},10);
		         
		    },
		    // fires when a problem is detected
		    error: function () { 
		
		    },
		    
		    // array of keyboard commands
		    keyActions: []
			});
				
			});
		}



 	// -------------------------------------------------------------------------------------------
	// hover effect for images
	// -------------------------------------------------------------------------------------------
    function avia_hover_effect(container)
    {
    	if(container == 'body')
    	{
    		var elements = $('#main a img').parents('a').not('.noLightbox, .noLightbox a, .avia-gallery-thumb a, .avia-layerslider a, .noHover, .noHover a').add('#main .avia-hover-fx');
    	}
    	else
    	{
    		var elements = $('a img', container).parents('a').not('.noLightbox, .noLightbox a, .avia-gallery-thumb a, .avia-layerslider a, .noHover, .noHover a').add('.avia-hover-fx', container);
    	}

		var overlay = "", isMobile 	= $.avia_utilities.isMobile;

		if(isMobile) return; //hover overlay for mobile device doesnt really make sense. in addition it often slows done the click event

	   elements.each(function(e)
       {
            var link      = $(this), current = link.find('img:first');

            if(current.hasClass('alignleft')) link.addClass('alignleft').css({float:'left', margin:0, padding:0});
            if(current.hasClass('alignright')) link.addClass('alignright').css({float:'right', margin:0, padding:0});
            if(current.hasClass('aligncenter')) link.addClass('aligncenter').css({float:'none','text-align':'center', margin:0, padding:0});

            if(current.hasClass('alignnone'))
            {
               link.addClass('alignnone').css({margin:0, padding:0});;
               if(!link.css('display') || link.css('display') == 'inline') { link.css({display:'inline-block'}); }
            }
            
            if(!link.css('position') || link.css('position') == 'static') { link.css({position:'relative', overflow:'hidden'}); }

        });

		elements.on('mouseenter', function(e)
		{
			var link  		= $(this),
				current	 	= link.find('img:first'),
				url		 	= link.attr('href'),
				span_class	= "overlay-type-video",
				opa			= link.data('opacity') || 0.7,
				overlay_offset = 5;

			overlay = link.find('.image-overlay');

			if(!overlay.length)
			{
				if(current.outerHeight() > 100)
				{
					if(link.height() == 0) { link.addClass(current.get(0).className); current.get(0).className = ""; }
					if(!link.css('display') || link.css('display') == 'inline') { link.css({display:'block'}); }
	
					if(url)
					{
						if( url.match(/(jpg|gif|jpeg|png|tif)/) ) span_class = "overlay-type-image";
						if(!url.match(/(jpg|gif|jpeg|png|\.tif|\.mov|\.swf|vimeo\.com|youtube\.com)/) ) span_class = "overlay-type-extern";
					}
	
					overlay = $("<span class='image-overlay "+span_class+"' style='opacity: 0;'><span class='image-overlay-inside'></span></span>").appendTo(link);
				}
			}

			if(current.outerHeight() > 100)
			{
				overlay.css({left:(current.position().left - overlay_offset) + parseInt(current.css("margin-left"),10), top:current.position().top + parseInt(current.css("margin-top"),10)})
					   .css({overflow:'hidden',display:'block','height':current.outerHeight(),'width':(current.outerWidth() + (2*overlay_offset))}).stop().animate({opacity:opa}, 400);
			}
			else
			{
				overlay.css({display:"none"});
			}

		}).on('mouseleave', elements, function(){

			if(overlay.length)
			{
				overlay.stop().animate({opacity:0}, 400);
			}
		});

    }








// -------------------------------------------------------------------------------------------
// Smooth scrooling when clicking on anchor links
// -------------------------------------------------------------------------------------------

	(function($)
	{
		$.fn.avia_smoothscroll = function(apply_to_container)
		{
			if(!this.length) return;
			
			var the_win = $(window),
				$main 	= $('.html_header_top.html_header_sticky #main'),
				$meta 	= $('.html_header_top #header_meta'),
				$alt  	= $('.html_header_top #header_main_alternate'),
				shrink	= $('.html_header_top.html_header_shrinking').length,
				fixedMainPadding = 0,
				calc_main_padding= function()
				{
					var tempPadding  		= parseInt($main.data('scroll-offset'),10) || 0,
						non_shrinking		= parseInt($meta.outerHeight(),10) || 0,
						non_shrinking2		= parseInt($alt.outerHeight(),10) || 0; 
					
					if(tempPadding > 0 && shrink) 
					{
						tempPadding = (tempPadding / 2 ) + non_shrinking + non_shrinking2;
					}
					else
					{
						tempPadding = tempPadding + non_shrinking + non_shrinking2;
					}
					
					tempPadding += parseInt($('html').css('margin-top'),10);
					fixedMainPadding = tempPadding;
				};
			
			calc_main_padding();
			the_win.on("debouncedresize av-height-change",  calc_main_padding);

			var hash = window.location.hash.replace(/\//g, "");
			
			//if a scroll event occurs at pageload and an anchor is set and a coresponding element exists apply the offset to the event
			if (fixedMainPadding > 0 && hash && apply_to_container == 'body')
			{
				var scroll_to_el = $(hash);
				
				if(scroll_to_el.length)
				{
					the_win.on('scroll.avia_first_scroll', function()
					{	
						setTimeout(function(){ //small delay so other scripts can perform necessary resizing
							the_win.scrollTop( scroll_to_el.offset().top - fixedMainPadding).off('scroll.avia_first_scroll');
						},10); 
				    });
			    }
			}
			
			

			return this.each(function()
			{
				$(this).click(function(e) {

				   var newHash  = this.hash.replace(/\//g, ""),
				   	   clicked  = $(this),
				   	   data		= clicked.data();
					
				   if(newHash != '' && newHash != '#' && newHash != '#prev' && newHash != '#next' && !clicked.is('.comment-reply-link, #cancel-comment-reply-link, .no-scroll'))
				   {
					   var container = "", originHash = "";
					   
					   if("#next-section" == newHash)
					   {
					   		originHash  = newHash;
					   		container   = clicked.parents('.container_wrap:eq(0)').next('.container_wrap');
					   		newHash		= '#' + container.attr('id');
					   }
					   else
					   {
					   		container = $(this.hash.replace(/\//g, ""));
					   }
					   
					   

					   if(container.length)
					   {
						   var target = container.offset().top - fixedMainPadding,
						   	   hash = window.location.hash,
						   	   hash = hash.replace(/\//g, ""),
							   oldLocation=window.location.href.replace(hash, ''),
							   newLocation=this,
							   duration= data.duration || 1200,
							   easing= data.easing || 'easeInOutQuint';
							
						   // make sure it's the same location
						   if(oldLocation+newHash==newLocation || originHash)
						   {
						      // animate to target and set the hash to the window.location after the animation
						      $('html:not(:animated),body:not(:animated)').animate({ scrollTop: target }, duration, easing, function() {

						         // add new hash to the browser location
						         //window.location.href=newLocation;
						         if(window.history.replaceState)
						         window.history.replaceState("", "", newHash);
						      });

						      // cancel default click action
						      e.preventDefault();
						   }
						}
					}
				});
			});
		};
	})(jQuery);


	// -------------------------------------------------------------------------------------------
	// iframe fix for firefox and ie so they get proper z index
	// -------------------------------------------------------------------------------------------
	function avia_iframe_fix(container)
	{
		var iframe 	= jQuery('iframe[src*="youtube.com"]:not(.av_youtube_frame)', container),
			youtubeEmbed = jQuery('iframe[src*="youtube.com"]:not(.av_youtube_frame) object, iframe[src*="youtube.com"]:not(.av_youtube_frame) embed', container).attr('wmode','opaque');

			iframe.each(function()
			{
				var current = jQuery(this),
					src 	= current.attr('src');

				if(src)
				{
					if(src.indexOf('?') !== -1)
					{
						src += "&wmode=opaque";
					}
					else
					{
						src += "?wmode=opaque";
					}

					current.attr('src', src);
				}
			});
	}

	// -------------------------------------------------------------------------------------------
	// small js fixes for pixel perfection :)
	// -------------------------------------------------------------------------------------------
	function avia_small_fixes(container)
	{
		if(!container) container = document;

		//make sure that iframes do resize correctly. uses css padding bottom iframe trick
		var win		= jQuery(window),
			iframes = jQuery('.avia-iframe-wrap iframe:not(.avia-slideshow iframe):not( iframe.no_resize):not(.avia-video iframe)', container),
			adjust_iframes = function()
			{
				iframes.each(function(){

					var iframe = jQuery(this), parent = iframe.parent(), proportions = 56.25;

					if(this.width && this.height)
					{
						proportions = (100/ this.width) * this.height;
						parent.css({"padding-bottom":proportions+"%"});
					}
				});
			};

			adjust_iframes();

	}

	// -------------------------------------------------------------------------------------------
	// Ligthbox activation
	// -------------------------------------------------------------------------------------------

	(function($)
	{
		$.fn.avia_activate_lightbox = function(variables)
		{
			var defaults =
			{
				autolinkElements: 'a[rel^="prettyPhoto"], a[rel^="lightbox"], a[href$=jpg], a[href$=png], a[href$=gif], a[href$=jpeg], a[href$=".mov"] , a[href$=".swf"] , a[href*="vimeo.com"] , a[href*="youtube.com/watch"] , a[href*="screenr.com"]'
			};

			var options 	= $.extend(defaults, variables),
				win		    = $(window),
				ww			= parseInt(win.width(),10) * 0.8, 	//controls the default lightbox width: 80% of the window size
				wh 			= (ww/16)*9;						//controls the default lightbox height (16:9 ration for videos. images are resized by the lightbox anyway)


			return this.each(function()
			{
				var elements = $(options.autolinkElements, this).not('.noLightbox, .noLightbox a, .fakeLightbox'),
					lastParent = "",
					counter = 0;

				elements.each(function()
				{
					var el = $(this),
						rel = el.data('rel'),
						parentPost = el.parents('.content:eq(0)'),
						group = 'auto_group';

					if(parentPost.get(0) != lastParent)
					{
						lastParent = parentPost.get(0);
						counter ++;
					}

					if(rel != "" && typeof rel != 'undefined')
					{
						el.attr('rel','lightbox['+rel+']');
					}

                    if((el.attr('rel') == undefined || el.attr('rel') == '') && !el.hasClass('noLightbox'))
                    {
                        if(elements.length > 1)
                        {
                            el.attr('rel','lightbox['+group+counter+']');
                        }
                        else
                        {
                            el.attr('rel','lightbox');
                        }
                    }
                });

                if(options.callback) var callbackfn = window[options.callback];

                if(typeof(callbackfn) !== 'undefined' && typeof(callbackfn) === "function")
                {
                    callbackfn(elements,ww,wh);
                }
                else
                {
                    if($.fn.prettyPhoto)
                    elements.prettyPhoto({ social_tools:'',slideshow: 5000, deeplinking: false, overlay_gallery:false, default_width: ww, default_height: wh });
                }

			});
		};
	})(jQuery);



// -------------------------------------------------------------------------------------------
// Avia Menu
// -------------------------------------------------------------------------------------------

(function($)
{
	$.fn.aviaMegamenu = function(variables)
	{
		var defaults =
		{
			modify_position:true,
			delay:300
		};

		var options = $.extend(defaults, variables);

		return this.each(function()
		{
			var left_menu	= $('html:first').filter('.bottom_nav_header').length,
				isMobile 	= $.avia_utilities.isMobile,
				menu = $(this),
				menuItems = menu.find(">li"),
				megaItems = menuItems.find(">div").parent().css({overflow:'hidden'}),
				menuActive = menu.find('>.current-menu-item>a, >.current_page_item>a'),
				dropdownItems = menuItems.find(">ul").parent(),
				parentContainer = menu.parent(),
				mainMenuParent = menu.parents('.main_menu').eq(0),
				parentContainerWidth = parentContainer.width(),
				delayCheck = {},
				mega_open = [];

			if(!menuActive.length){ menu.find('.current-menu-ancestor:eq(0) a:eq(0), .current_page_ancestor:eq(0) a:eq(0)').parent().addClass('active-parent-item')}

			menuItems.on('click' ,'a', function()
			{
				if(this.href == window.location.href + "#" || this.href == window.location.href + "/#")
				return false;
			});

			menuItems.each(function()
			{
				var item = $(this),
					pos = item.position(),
					megaDiv = item.find("div:first").css({opacity:0, display:"none"}),
					normalDropdown = "";

				//check if we got a mega menu
				if(!megaDiv.length)
				{
					normalDropdown = item.find(">ul").css({display:"none"});
				}

				//if we got a mega menu or dropdown menu add the arrow beside the menu item
				if(megaDiv.length || normalDropdown.length)
				{
					var link = item.addClass('dropdown_ul_available').find('>a');
					link.append('<span class="dropdown_available"></span>');

					//is a mega menu main item doesnt have a link to click use the default cursor
					if(typeof link.attr('href') != 'string' || link.attr('href') == "#"){ link.css('cursor','default').click(function(){return false;}); }
				}


				//correct position of mega menus
				if(options.modify_position && megaDiv.length)
				{
					if(!left_menu)
					{
						if(pos.left + megaDiv.width() < parentContainerWidth)
						{
							megaDiv.css({right: -megaDiv.outerWidth() + item.outerWidth()  });
							//item.css({position:'static'});
						}
						else if(pos.left + megaDiv.width() > parentContainerWidth)
						{
							megaDiv.css({right: -mainMenuParent.outerWidth() + (pos.left + item.outerWidth() ) });
						}
					}
					else
					{
						if(megaDiv.width() > pos.left + item.outerWidth())
						{
							megaDiv.css({left: (pos.left* -1)});
						}
						else if(pos.left + megaDiv.width() > parentContainerWidth)
						{
							megaDiv.css({left: (megaDiv.width() - pos.left) * -1 });
						}
					}
				}



			});


			function megaDivShow(i)
			{
				if(delayCheck[i] == true)
				{
					var item = megaItems.filter(':eq('+i+')').css({overflow:'visible'}).find("div:first"),
						link = megaItems.filter(':eq('+i+')').find("a:first");
						mega_open["check"+i] = true;

						item.stop().css('display','block').animate({opacity:1},300);

						if(item.length)
						{
							link.addClass('open-mega-a');
						}
				}
			}

			function megaDivHide (i)
			{
				if(delayCheck[i] == false)
				{
					megaItems.filter(':eq('+i+')').find(">a").removeClass('open-mega-a');

					var listItem = megaItems.filter(':eq('+i+')'),
						item = listItem.find("div:first");


					item.stop().css('display','block').animate({opacity:0},300, function()
					{
						$(this).css('display','none');
						listItem.css({overflow:'hidden'});
						mega_open["check"+i] = false;
					});
				}
			}

			if(isMobile)
			{
				megaItems.each(function(i){

					$(this).bind('click', function()
					{
						if(mega_open["check"+i] != true) return false;
					});
				});
			}


			//bind event for mega menu
			megaItems.each(function(i){

				$(this).hover(

					function()
					{
						delayCheck[i] = true;
						setTimeout(function(){megaDivShow(i); },options.delay);
					},

					function()
					{
						delayCheck[i] = false;
						setTimeout(function(){megaDivHide(i); },options.delay);
					}
				);
			});


			// bind events for dropdown menu
			dropdownItems.find('li').andSelf().each(function()
			{
				var currentItem = $(this),
					sublist = currentItem.find('ul:first'),
					showList = false;

				if(sublist.length)
				{
					sublist.css({display:'block', opacity:0, visibility:'hidden'});
					var currentLink = currentItem.find('>a');

					currentLink.bind('mouseenter', function()
					{
						sublist.stop().css({visibility:'visible'}).animate({opacity:1});
					});

					currentItem.bind('mouseleave', function()
					{
						sublist.stop().animate({opacity:0}, function()
						{
							sublist.css({visibility:'hidden'});
						});
					});

				}

			});

		});
	};
})(jQuery);




// -------------------------------------------------------------------------------------------
//Portfolio sorting
// -------------------------------------------------------------------------------------------

    $.fn.avia_iso_sort = function(options)
	{
		$.extend( $.Isotope.prototype, {
		  _customModeReset : function() {

		  	this.fitRows = {
		        x : 0,
		        y : 0,
		        height : 0
		      };

		   },
		  _customModeLayout : function( $elems ) {

		    var instance		= this,
		        containerWidth	= this.element.width(),
		        props			= this.fitRows,
		        percentBase 	= this.element.data('margin_base') || 6,
		        margin			= this.element.is('.no_margin-container') ? 0 : (containerWidth / 100) * percentBase, //margin based on %
		        extraRange		= 2; // adds a little range for % based calculation error in some browsers

		      $elems.each( function() {
		        var $this = $(this),
		            atomW = $this.outerWidth() ,
		            atomH = $this.outerHeight(true);

		        if ( props.x !== 0 && atomW + props.x > containerWidth + extraRange ) {
		          // if this element cannot fit in the current row
		          props.x = 0;
		          props.y = props.height;
		        }

		     	//webkit gets blurry elements if position is a float value
		     	props.x = Math.round(props.x);
		     	props.y = Math.round(props.y);

		        // position the atom
		        instance._pushPosition( $this, props.x, props.y );

		        props.height = Math.max( props.y + atomH, props.height );
		        props.x += atomW + margin;


		      });

		  },
		  _customModeGetContainerSize : function() {

		  	return { height : this.fitRows.height };

		  },
		  _customModeResizeChanged : function() {

		  	return true;

		   }
		});



		return this.each(function()
		{
			var the_body		= $('body'),
				container		= $(this),
				portfolio_id	= container.data('portfolio-id'),
				parentContainer	= container.parents('.entry-content-wrapper'),
				filter			= parentContainer.find('.sort_width_container[data-portfolio-id="' + portfolio_id + '"]').find('#js_sort_items').css({visibility:"visible", opacity:0}),
				links			= filter.find('a'),
				imgParent		= container.find('.grid-image'),
				isoActive		= false,
				items			= $('.post-entry', container);

			function applyIso()
			{
				container.addClass('isotope_activated').isotope({
					layoutMode : 'customMode', itemSelector : '.flex_column'
				}, function()
				{
					container.css({overflow:'visible'});
					the_body.trigger('av_resize_finished');
				});

				isoActive = true;
				setTimeout(function(){ parentContainer.addClass('avia_sortable_active'); }, 0);
			};

			links.bind('click',function()
			{
				var current		= $(this),
			  		selector	= current.data('filter');

					links.removeClass('active_sort');
					current.addClass('active_sort');
					container.attr('id', 'grid_id_'+selector);

					parentContainer.find('.open_container .ajax_controlls .avia_close').trigger('click');
					//container.css({overflow:'hidden'})
					container.isotope({ layoutMode : 'customMode', itemSelector : '.flex_column' , filter: '.'+selector}, function()
					{
						container.css({overflow:'visible'});
						the_body.trigger('av_resize_finished');
					});

					return false;
			});

			// update columnWidth on window resize
			$(window).on( 'debouncedresize', function()
			{
			  	applyIso();
			});

			$.avia_utilities.preload({container: container, single_callback:  function()
				{
					filter.animate({opacity:1}, 400); applyIso();

					//call a second time to for the initial resizing
					setTimeout(function(){ applyIso(); });

					imgParent.css({height:'auto'}).each(function(i)
					{
						var currentLink = $(this);

						setTimeout(function()
						{
							currentLink.animate({opacity:1},1500);
						}, (100 * i));
					});
				}
			});

		});
	};




    //check if the browser supports element rotation
    function avia_header_size()
    {
        var win	            = $(window),
            header          = $('.html_header_top.html_header_sticky #header');
            
        if(!header.length) return;
        
        var logo            = $('#header_main .container .logo img, #header_main .container .logo a'),
            elements        = $('#header_main .container, #header_main .main_menu ul:first-child > li > a:not(.avia_mega_div a)'),
            el_height       = $(elements).filter(':first').height(),
            isMobile        = $.avia_utilities.isMobile,
            scroll_top		= $('#scroll-top-link'),
            transparent 	= header.is('.av_header_transparency'),
            shrinking		= header.is('.av_header_shrinking'),
            set_height      = function()
            {	
                var st = win.scrollTop(), newH = 0;
				
				if(shrinking && !isMobile)
                {
	                if(st < el_height/2)
	                {
	                    newH = el_height - st;
	                    header.removeClass('header-scrolled');
	                }
	                else
	                {
	                    newH = el_height/2;
	                    header.addClass('header-scrolled');
	                }
	                
	                elements.css({'height': newH + 'px', 'lineHeight': newH + 'px'});
                	logo.css({'maxHeight': newH + 'px'});
                }
                
                if(transparent)
                {
                	if(st > 50)
                	{
                		header.removeClass('av_header_transparency');
                	}
                	else
                	{
                		header.addClass('av_header_transparency');
                	}
                }

               
            }

            if($('body').is('.avia_deactivate_menu_resize')) shrinking = false;
            
            if(!transparent && !shrinking) return;
            
			win.on( 'debouncedresize',  function(){ el_height = $(elements).attr('style',"").filter(':first').height(); set_height(); } );
            win.on( 'scroll',  function(){ window.requestAnimationFrame( set_height )} );
            set_height();
    }


   function avia_scroll_top_fade()
   {
   		 var win 		= $(window),
   		 	 timeo = false,
   		 	 scroll_top = $('#scroll-top-link'),
   		 	 set_status = function()
             {
             	var st = win.scrollTop();

             	if(st < 500)
             	{
             		scroll_top.removeClass('avia_pop_class');
             	}
             	else if(!scroll_top.is('.avia_pop_class'))
             	{
             		scroll_top.addClass('avia_pop_class');
             	}
             };

   		 win.on( 'scroll',  function(){ window.requestAnimationFrame( set_status )} );
         set_status();
	}




	$.AviaAjaxSearch  =  function(options)
	{
	   var defaults = {
            delay: 300,                //delay in ms until the user stops typing.
            minChars: 3,               //dont start searching before we got at least that much characters
            scope: 'body'

        }

        this.options = $.extend({}, defaults, options);
        this.scope   = $(this.options.scope);
        this.timer   = false;
        this.lastVal = "";
		
        this.bind_events();
	}


	$.AviaAjaxSearch.prototype =
    {
        bind_events: function()
        {
            this.scope.on('keyup', '#s:not(".av_disable_ajax_search #s")' , $.proxy( this.try_search, this));
        },

        try_search: function(e)
        {
            clearTimeout(this.timer);

            //only execute search if chars are at least "minChars" and search differs from last one
            if(e.currentTarget.value.length >= this.options.minChars && this.lastVal != $.trim(e.currentTarget.value))
            {
                //wait at least "delay" miliseconds to execute ajax. if user types again during that time dont execute
                this.timer = setTimeout($.proxy( this.do_search, this, e), this.options.delay);
            }
        },

        do_search: function(e)
        {
            var obj          = this,
                currentField = $(e.currentTarget).attr( "autocomplete", "off" ),
                form         = currentField.parents('form:eq(0)'),
                results      = form.find('.ajax_search_response'),
                loading      = $('<div class="ajax_load"><span class="ajax_load_inner"></span></div>'),
                action 		 = form.attr('action'),
                values       = form.serialize();
                values      += '&action=avia_ajax_search';

           	//check if the form got get parameters applied and also apply them
           	if(action.indexOf('?') != -1)
           	{
           		action  = action.split('?');
           		values += "&" + action[1];
           	}

            if(!results.length) results = $('<div class="ajax_search_response"></div>').appendTo(form);

            //return if we already hit a no result and user is still typing
            if(results.find('.ajax_not_found').length && e.currentTarget.value.indexOf(this.lastVal) != -1) return;

            this.lastVal = e.currentTarget.value;

            $.ajax({
				url: avia_framework_globals.ajaxurl,
				type: "POST",
				data:values,
				beforeSend: function()
				{
					loading.insertAfter(currentField);
				},
				success: function(response)
				{
				    if(response == 0) response = "";
                    results.html(response);
				},
				complete: function()
				{
				    loading.remove();
				}
			});
        }
    }










	$.AviaTooltip  =  function(options)
	{
	   var defaults = {
            delay: 1500,                //delay in ms until the tooltip appears
            delayOut: 300,             //delay in ms when instant showing should stop
            "class": "avia-tooltip",     //tooltip classname for css styling and alignment
            scope: "body",             //area the tooltip should be applied to
            data:  "avia-tooltip",     //data attribute that contains the tooltip text
            attach:"body",          //either attach the tooltip to the "mouse" or to the "element" // todo: implement mouse, make sure that it doesnt overlap with screen borders
            event: 'mouseenter',       //mousenter and leave or click and leave
            position:'top'             //top or bottom
        }

        this.options = $.extend({}, defaults, options);
        this.body    = $('body');
        this.scope   = $(this.options.scope);
        this.tooltip = $('<div class="'+this.options['class']+' avia-tt"><span class="avia-arrow-wrap"><span class="avia-arrow"></span></span></div>');
        this.inner   = $('<div class="inner_tooltip"></div>').prependTo(this.tooltip);
        this.open    = false;
        this.timer   = false;
        this.active  = false;

        this.bind_events();
	}

	$.AviaTooltip.openTTs = [];
    $.AviaTooltip.prototype =
    {
        bind_events: function()
        {
            this.scope.on(this.options.event + ' mouseleave', '[data-'+this.options.data+']', $.proxy( this.start_countdown, this) );

            if(this.options.event != 'click')
            {
                this.scope.on('mouseleave', '[data-'+this.options.data+']', $.proxy( this.hide_tooltip, this) );
            }
            else
            {
                this.body.on('mousedown', $.proxy( this.hide_tooltip, this) );
            }
        },

        start_countdown: function(e)
        {
            clearTimeout(this.timer);

            if(e.type == this.options.event)
            {
                var delay = this.options.event == 'click' ? 0 : this.open ? 0 : this.options.delay;

                this.timer = setTimeout($.proxy( this.display_tooltip, this, e), delay);
            }
            else if(e.type == 'mouseleave')
            {
                this.timer = setTimeout($.proxy( this.stop_instant_open, this, e), this.options.delayOut);
            }
            e.preventDefault();
        },

        reset_countdown: function(e)
        {
            clearTimeout(this.timer);
            this.timer = false;
        },

        display_tooltip: function(e)
        {
            var element = $(e.currentTarget),
                text    = element.data(this.options.data),
                newTip  = element.data('avia-created-tooltip'),
                attach  = this.options.attach == 'element' ? element : this.body,
                offset  = this.options.attach == 'element' ? element.position() : element.offset();

            this.inner.html(text);
            newTip = typeof newTip != 'undefined' ? $.AviaTooltip.openTTs[newTip] : this.options.attach == 'element' ? this.tooltip.clone().insertAfter(attach) : this.tooltip.clone().appendTo(attach);
            this.open = true;
            this.active = newTip;

            if((newTip.is(':animated:visible') && e.type == 'click') || element.is('.'+this.options['class']) || element.parents('.'+this.options['class']).length != 0) return;


            var real_top  = offset.top - newTip.outerHeight(),
                real_left = (offset.left + (element.outerWidth() / 2)) - (newTip.outerWidth() / 2);

            if(this.options.position == 'bottom')
            {
                real_top = offset.top + element.outerHeight();
            }

            newTip.css({opacity:0, display:'block', top: real_top - 10, left: real_left }).stop().animate({top: real_top, opacity:1},200);
            newTip.find('input, textarea').focus();
            $.AviaTooltip.openTTs.push(newTip);
            element.data('avia-created-tooltip', $.AviaTooltip.openTTs.length - 1);

        },

        hide_tooltip: function(e)
        {
            var element = $(e.currentTarget) , newTip, animateTo;

            if(this.options.event == 'click')
            {
                element = $(e.target);

                if(!element.is('.'+this.options['class']) && element.parents('.'+this.options['class']).length == 0)
                {
                    if(this.active.length) { newTip = this.active; this.active = false;}
                }
            }
            else
            {
                newTip = element.data('avia-created-tooltip');
                newTip = typeof newTip != 'undefined' ? $.AviaTooltip.openTTs[newTip] : false;
            }

            if(newTip)
            {
                animateTo = parseInt(newTip.css('top'),10) - 10;
                newTip.animate({top: animateTo, opacity:0},200, function()
                {
                    newTip.css({display:'none'});

                });
            }
        },

        stop_instant_open: function(e)
        {
            this.open = false;
        }
    }


})( jQuery );




/*!
 * Isotope PACKAGED v2.1.1
 * Filter & sort magical layouts
 * http://isotope.metafizzy.co
 */

(function(t){function e(){}function i(t){function i(e){e.prototype.option||(e.prototype.option=function(e){t.isPlainObject(e)&&(this.options=t.extend(!0,this.options,e))})}function n(e,i){t.fn[e]=function(n){if("string"==typeof n){for(var s=o.call(arguments,1),a=0,u=this.length;u>a;a++){var p=this[a],h=t.data(p,e);if(h)if(t.isFunction(h[n])&&"_"!==n.charAt(0)){var f=h[n].apply(h,s);if(void 0!==f)return f}else r("no such method '"+n+"' for "+e+" instance");else r("cannot call methods on "+e+" prior to initialization; "+"attempted to call '"+n+"'")}return this}return this.each(function(){var o=t.data(this,e);o?(o.option(n),o._init()):(o=new i(this,n),t.data(this,e,o))})}}if(t){var r="undefined"==typeof console?e:function(t){console.error(t)};return t.bridget=function(t,e){i(e),n(t,e)},t.bridget}}var o=Array.prototype.slice;"function"==typeof define&&define.amd?define("jquery-bridget/jquery.bridget",["jquery"],i):"object"==typeof exports?i(require("jquery")):i(t.jQuery)})(window),function(t){function e(e){var i=t.event;return i.target=i.target||i.srcElement||e,i}var i=document.documentElement,o=function(){};i.addEventListener?o=function(t,e,i){t.addEventListener(e,i,!1)}:i.attachEvent&&(o=function(t,i,o){t[i+o]=o.handleEvent?function(){var i=e(t);o.handleEvent.call(o,i)}:function(){var i=e(t);o.call(t,i)},t.attachEvent("on"+i,t[i+o])});var n=function(){};i.removeEventListener?n=function(t,e,i){t.removeEventListener(e,i,!1)}:i.detachEvent&&(n=function(t,e,i){t.detachEvent("on"+e,t[e+i]);try{delete t[e+i]}catch(o){t[e+i]=void 0}});var r={bind:o,unbind:n};"function"==typeof define&&define.amd?define("eventie/eventie",r):"object"==typeof exports?module.exports=r:t.eventie=r}(this),function(t){function e(t){"function"==typeof t&&(e.isReady?t():s.push(t))}function i(t){var i="readystatechange"===t.type&&"complete"!==r.readyState;e.isReady||i||o()}function o(){e.isReady=!0;for(var t=0,i=s.length;i>t;t++){var o=s[t];o()}}function n(n){return"complete"===r.readyState?o():(n.bind(r,"DOMContentLoaded",i),n.bind(r,"readystatechange",i),n.bind(t,"load",i)),e}var r=t.document,s=[];e.isReady=!1,"function"==typeof define&&define.amd?define("doc-ready/doc-ready",["eventie/eventie"],n):"object"==typeof exports?module.exports=n(require("eventie")):t.docReady=n(t.eventie)}(window),function(){function t(){}function e(t,e){for(var i=t.length;i--;)if(t[i].listener===e)return i;return-1}function i(t){return function(){return this[t].apply(this,arguments)}}var o=t.prototype,n=this,r=n.EventEmitter;o.getListeners=function(t){var e,i,o=this._getEvents();if(t instanceof RegExp){e={};for(i in o)o.hasOwnProperty(i)&&t.test(i)&&(e[i]=o[i])}else e=o[t]||(o[t]=[]);return e},o.flattenListeners=function(t){var e,i=[];for(e=0;t.length>e;e+=1)i.push(t[e].listener);return i},o.getListenersAsObject=function(t){var e,i=this.getListeners(t);return i instanceof Array&&(e={},e[t]=i),e||i},o.addListener=function(t,i){var o,n=this.getListenersAsObject(t),r="object"==typeof i;for(o in n)n.hasOwnProperty(o)&&-1===e(n[o],i)&&n[o].push(r?i:{listener:i,once:!1});return this},o.on=i("addListener"),o.addOnceListener=function(t,e){return this.addListener(t,{listener:e,once:!0})},o.once=i("addOnceListener"),o.defineEvent=function(t){return this.getListeners(t),this},o.defineEvents=function(t){for(var e=0;t.length>e;e+=1)this.defineEvent(t[e]);return this},o.removeListener=function(t,i){var o,n,r=this.getListenersAsObject(t);for(n in r)r.hasOwnProperty(n)&&(o=e(r[n],i),-1!==o&&r[n].splice(o,1));return this},o.off=i("removeListener"),o.addListeners=function(t,e){return this.manipulateListeners(!1,t,e)},o.removeListeners=function(t,e){return this.manipulateListeners(!0,t,e)},o.manipulateListeners=function(t,e,i){var o,n,r=t?this.removeListener:this.addListener,s=t?this.removeListeners:this.addListeners;if("object"!=typeof e||e instanceof RegExp)for(o=i.length;o--;)r.call(this,e,i[o]);else for(o in e)e.hasOwnProperty(o)&&(n=e[o])&&("function"==typeof n?r.call(this,o,n):s.call(this,o,n));return this},o.removeEvent=function(t){var e,i=typeof t,o=this._getEvents();if("string"===i)delete o[t];else if(t instanceof RegExp)for(e in o)o.hasOwnProperty(e)&&t.test(e)&&delete o[e];else delete this._events;return this},o.removeAllListeners=i("removeEvent"),o.emitEvent=function(t,e){var i,o,n,r,s=this.getListenersAsObject(t);for(n in s)if(s.hasOwnProperty(n))for(o=s[n].length;o--;)i=s[n][o],i.once===!0&&this.removeListener(t,i.listener),r=i.listener.apply(this,e||[]),r===this._getOnceReturnValue()&&this.removeListener(t,i.listener);return this},o.trigger=i("emitEvent"),o.emit=function(t){var e=Array.prototype.slice.call(arguments,1);return this.emitEvent(t,e)},o.setOnceReturnValue=function(t){return this._onceReturnValue=t,this},o._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},o._getEvents=function(){return this._events||(this._events={})},t.noConflict=function(){return n.EventEmitter=r,t},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return t}):"object"==typeof module&&module.exports?module.exports=t:n.EventEmitter=t}.call(this),function(t){function e(t){if(t){if("string"==typeof o[t])return t;t=t.charAt(0).toUpperCase()+t.slice(1);for(var e,n=0,r=i.length;r>n;n++)if(e=i[n]+t,"string"==typeof o[e])return e}}var i="Webkit Moz ms Ms O".split(" "),o=document.documentElement.style;"function"==typeof define&&define.amd?define("get-style-property/get-style-property",[],function(){return e}):"object"==typeof exports?module.exports=e:t.getStyleProperty=e}(window),function(t){function e(t){var e=parseFloat(t),i=-1===t.indexOf("%")&&!isNaN(e);return i&&e}function i(){}function o(){for(var t={width:0,height:0,innerWidth:0,innerHeight:0,outerWidth:0,outerHeight:0},e=0,i=s.length;i>e;e++){var o=s[e];t[o]=0}return t}function n(i){function n(){if(!d){d=!0;var o=t.getComputedStyle;if(p=function(){var t=o?function(t){return o(t,null)}:function(t){return t.currentStyle};return function(e){var i=t(e);return i||r("Style returned "+i+". Are you running this code in a hidden iframe on Firefox? "+"See http://bit.ly/getsizebug1"),i}}(),h=i("boxSizing")){var n=document.createElement("div");n.style.width="200px",n.style.padding="1px 2px 3px 4px",n.style.borderStyle="solid",n.style.borderWidth="1px 2px 3px 4px",n.style[h]="border-box";var s=document.body||document.documentElement;s.appendChild(n);var a=p(n);f=200===e(a.width),s.removeChild(n)}}}function a(t){if(n(),"string"==typeof t&&(t=document.querySelector(t)),t&&"object"==typeof t&&t.nodeType){var i=p(t);if("none"===i.display)return o();var r={};r.width=t.offsetWidth,r.height=t.offsetHeight;for(var a=r.isBorderBox=!(!h||!i[h]||"border-box"!==i[h]),d=0,l=s.length;l>d;d++){var c=s[d],y=i[c];y=u(t,y);var m=parseFloat(y);r[c]=isNaN(m)?0:m}var g=r.paddingLeft+r.paddingRight,v=r.paddingTop+r.paddingBottom,_=r.marginLeft+r.marginRight,I=r.marginTop+r.marginBottom,L=r.borderLeftWidth+r.borderRightWidth,z=r.borderTopWidth+r.borderBottomWidth,b=a&&f,x=e(i.width);x!==!1&&(r.width=x+(b?0:g+L));var S=e(i.height);return S!==!1&&(r.height=S+(b?0:v+z)),r.innerWidth=r.width-(g+L),r.innerHeight=r.height-(v+z),r.outerWidth=r.width+_,r.outerHeight=r.height+I,r}}function u(e,i){if(t.getComputedStyle||-1===i.indexOf("%"))return i;var o=e.style,n=o.left,r=e.runtimeStyle,s=r&&r.left;return s&&(r.left=e.currentStyle.left),o.left=i,i=o.pixelLeft,o.left=n,s&&(r.left=s),i}var p,h,f,d=!1;return a}var r="undefined"==typeof console?i:function(t){console.error(t)},s=["paddingLeft","paddingRight","paddingTop","paddingBottom","marginLeft","marginRight","marginTop","marginBottom","borderLeftWidth","borderRightWidth","borderTopWidth","borderBottomWidth"];"function"==typeof define&&define.amd?define("get-size/get-size",["get-style-property/get-style-property"],n):"object"==typeof exports?module.exports=n(require("desandro-get-style-property")):t.getSize=n(t.getStyleProperty)}(window),function(t){function e(t,e){return t[s](e)}function i(t){if(!t.parentNode){var e=document.createDocumentFragment();e.appendChild(t)}}function o(t,e){i(t);for(var o=t.parentNode.querySelectorAll(e),n=0,r=o.length;r>n;n++)if(o[n]===t)return!0;return!1}function n(t,o){return i(t),e(t,o)}var r,s=function(){if(t.matchesSelector)return"matchesSelector";for(var e=["webkit","moz","ms","o"],i=0,o=e.length;o>i;i++){var n=e[i],r=n+"MatchesSelector";if(t[r])return r}}();if(s){var a=document.createElement("div"),u=e(a,"div");r=u?e:n}else r=o;"function"==typeof define&&define.amd?define("matches-selector/matches-selector",[],function(){return r}):"object"==typeof exports?module.exports=r:window.matchesSelector=r}(Element.prototype),function(t){function e(t,e){for(var i in e)t[i]=e[i];return t}function i(t){for(var e in t)return!1;return e=null,!0}function o(t){return t.replace(/([A-Z])/g,function(t){return"-"+t.toLowerCase()})}function n(t,n,r){function a(t,e){t&&(this.element=t,this.layout=e,this.position={x:0,y:0},this._create())}var u=r("transition"),p=r("transform"),h=u&&p,f=!!r("perspective"),d={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"otransitionend",transition:"transitionend"}[u],l=["transform","transition","transitionDuration","transitionProperty"],c=function(){for(var t={},e=0,i=l.length;i>e;e++){var o=l[e],n=r(o);n&&n!==o&&(t[o]=n)}return t}();e(a.prototype,t.prototype),a.prototype._create=function(){this._transn={ingProperties:{},clean:{},onEnd:{}},this.css({position:"absolute"})},a.prototype.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},a.prototype.getSize=function(){this.size=n(this.element)},a.prototype.css=function(t){var e=this.element.style;for(var i in t){var o=c[i]||i;e[o]=t[i]}},a.prototype.getPosition=function(){var t=s(this.element),e=this.layout.options,i=e.isOriginLeft,o=e.isOriginTop,n=parseInt(t[i?"left":"right"],10),r=parseInt(t[o?"top":"bottom"],10);n=isNaN(n)?0:n,r=isNaN(r)?0:r;var a=this.layout.size;n-=i?a.paddingLeft:a.paddingRight,r-=o?a.paddingTop:a.paddingBottom,this.position.x=n,this.position.y=r},a.prototype.layoutPosition=function(){var t=this.layout.size,e=this.layout.options,i={};e.isOriginLeft?(i.left=this.position.x+t.paddingLeft+"px",i.right=""):(i.right=this.position.x+t.paddingRight+"px",i.left=""),e.isOriginTop?(i.top=this.position.y+t.paddingTop+"px",i.bottom=""):(i.bottom=this.position.y+t.paddingBottom+"px",i.top=""),this.css(i),this.emitEvent("layout",[this])};var y=f?function(t,e){return"translate3d("+t+"px, "+e+"px, 0)"}:function(t,e){return"translate("+t+"px, "+e+"px)"};a.prototype._transitionTo=function(t,e){this.getPosition();var i=this.position.x,o=this.position.y,n=parseInt(t,10),r=parseInt(e,10),s=n===this.position.x&&r===this.position.y;if(this.setPosition(t,e),s&&!this.isTransitioning)return this.layoutPosition(),void 0;var a=t-i,u=e-o,p={},h=this.layout.options;a=h.isOriginLeft?a:-a,u=h.isOriginTop?u:-u,p.transform=y(a,u),this.transition({to:p,onTransitionEnd:{transform:this.layoutPosition},isCleaning:!0})},a.prototype.goTo=function(t,e){this.setPosition(t,e),this.layoutPosition()},a.prototype.moveTo=h?a.prototype._transitionTo:a.prototype.goTo,a.prototype.setPosition=function(t,e){this.position.x=parseInt(t,10),this.position.y=parseInt(e,10)},a.prototype._nonTransition=function(t){this.css(t.to),t.isCleaning&&this._removeStyles(t.to);for(var e in t.onTransitionEnd)t.onTransitionEnd[e].call(this)},a.prototype._transition=function(t){if(!parseFloat(this.layout.options.transitionDuration))return this._nonTransition(t),void 0;var e=this._transn;for(var i in t.onTransitionEnd)e.onEnd[i]=t.onTransitionEnd[i];for(i in t.to)e.ingProperties[i]=!0,t.isCleaning&&(e.clean[i]=!0);if(t.from){this.css(t.from);var o=this.element.offsetHeight;o=null}this.enableTransition(t.to),this.css(t.to),this.isTransitioning=!0};var m=p&&o(p)+",opacity";a.prototype.enableTransition=function(){this.isTransitioning||(this.css({transitionProperty:m,transitionDuration:this.layout.options.transitionDuration}),this.element.addEventListener(d,this,!1))},a.prototype.transition=a.prototype[u?"_transition":"_nonTransition"],a.prototype.onwebkitTransitionEnd=function(t){this.ontransitionend(t)},a.prototype.onotransitionend=function(t){this.ontransitionend(t)};var g={"-webkit-transform":"transform","-moz-transform":"transform","-o-transform":"transform"};a.prototype.ontransitionend=function(t){if(t.target===this.element){var e=this._transn,o=g[t.propertyName]||t.propertyName;if(delete e.ingProperties[o],i(e.ingProperties)&&this.disableTransition(),o in e.clean&&(this.element.style[t.propertyName]="",delete e.clean[o]),o in e.onEnd){var n=e.onEnd[o];n.call(this),delete e.onEnd[o]}this.emitEvent("transitionEnd",[this])}},a.prototype.disableTransition=function(){this.removeTransitionStyles(),this.element.removeEventListener(d,this,!1),this.isTransitioning=!1},a.prototype._removeStyles=function(t){var e={};for(var i in t)e[i]="";this.css(e)};var v={transitionProperty:"",transitionDuration:""};return a.prototype.removeTransitionStyles=function(){this.css(v)},a.prototype.removeElem=function(){this.element.parentNode.removeChild(this.element),this.emitEvent("remove",[this])},a.prototype.remove=function(){if(!u||!parseFloat(this.layout.options.transitionDuration))return this.removeElem(),void 0;var t=this;this.on("transitionEnd",function(){return t.removeElem(),!0}),this.hide()},a.prototype.reveal=function(){delete this.isHidden,this.css({display:""});var t=this.layout.options;this.transition({from:t.hiddenStyle,to:t.visibleStyle,isCleaning:!0})},a.prototype.hide=function(){this.isHidden=!0,this.css({display:""});var t=this.layout.options;this.transition({from:t.visibleStyle,to:t.hiddenStyle,isCleaning:!0,onTransitionEnd:{opacity:function(){this.isHidden&&this.css({display:"none"})}}})},a.prototype.destroy=function(){this.css({position:"",left:"",right:"",top:"",bottom:"",transition:"",transform:""})},a}var r=t.getComputedStyle,s=r?function(t){return r(t,null)}:function(t){return t.currentStyle};"function"==typeof define&&define.amd?define("outlayer/item",["eventEmitter/EventEmitter","get-size/get-size","get-style-property/get-style-property"],n):"object"==typeof exports?module.exports=n(require("wolfy87-eventemitter"),require("get-size"),require("desandro-get-style-property")):(t.Outlayer={},t.Outlayer.Item=n(t.EventEmitter,t.getSize,t.getStyleProperty))}(window),function(t){function e(t,e){for(var i in e)t[i]=e[i];return t}function i(t){return"[object Array]"===f.call(t)}function o(t){var e=[];if(i(t))e=t;else if(t&&"number"==typeof t.length)for(var o=0,n=t.length;n>o;o++)e.push(t[o]);else e.push(t);return e}function n(t,e){var i=l(e,t);-1!==i&&e.splice(i,1)}function r(t){return t.replace(/(.)([A-Z])/g,function(t,e,i){return e+"-"+i}).toLowerCase()}function s(i,s,f,l,c,y){function m(t,i){if("string"==typeof t&&(t=a.querySelector(t)),!t||!d(t))return u&&u.error("Bad "+this.constructor.namespace+" element: "+t),void 0;this.element=t,this.options=e({},this.constructor.defaults),this.option(i);var o=++g;this.element.outlayerGUID=o,v[o]=this,this._create(),this.options.isInitLayout&&this.layout()}var g=0,v={};return m.namespace="outlayer",m.Item=y,m.defaults={containerStyle:{position:"relative"},isInitLayout:!0,isOriginLeft:!0,isOriginTop:!0,isResizeBound:!0,isResizingContainer:!0,transitionDuration:"0.4s",hiddenStyle:{opacity:0,transform:"scale(0.001)"},visibleStyle:{opacity:1,transform:"scale(1)"}},e(m.prototype,f.prototype),m.prototype.option=function(t){e(this.options,t)},m.prototype._create=function(){this.reloadItems(),this.stamps=[],this.stamp(this.options.stamp),e(this.element.style,this.options.containerStyle),this.options.isResizeBound&&this.bindResize()},m.prototype.reloadItems=function(){this.items=this._itemize(this.element.children)},m.prototype._itemize=function(t){for(var e=this._filterFindItemElements(t),i=this.constructor.Item,o=[],n=0,r=e.length;r>n;n++){var s=e[n],a=new i(s,this);o.push(a)}return o},m.prototype._filterFindItemElements=function(t){t=o(t);for(var e=this.options.itemSelector,i=[],n=0,r=t.length;r>n;n++){var s=t[n];if(d(s))if(e){c(s,e)&&i.push(s);for(var a=s.querySelectorAll(e),u=0,p=a.length;p>u;u++)i.push(a[u])}else i.push(s)}return i},m.prototype.getItemElements=function(){for(var t=[],e=0,i=this.items.length;i>e;e++)t.push(this.items[e].element);return t},m.prototype.layout=function(){this._resetLayout(),this._manageStamps();var t=void 0!==this.options.isLayoutInstant?this.options.isLayoutInstant:!this._isLayoutInited;this.layoutItems(this.items,t),this._isLayoutInited=!0},m.prototype._init=m.prototype.layout,m.prototype._resetLayout=function(){this.getSize()},m.prototype.getSize=function(){this.size=l(this.element)},m.prototype._getMeasurement=function(t,e){var i,o=this.options[t];o?("string"==typeof o?i=this.element.querySelector(o):d(o)&&(i=o),this[t]=i?l(i)[e]:o):this[t]=0},m.prototype.layoutItems=function(t,e){t=this._getItemsForLayout(t),this._layoutItems(t,e),this._postLayout()},m.prototype._getItemsForLayout=function(t){for(var e=[],i=0,o=t.length;o>i;i++){var n=t[i];n.isIgnored||e.push(n)}return e},m.prototype._layoutItems=function(t,e){function i(){o.emitEvent("layoutComplete",[o,t])}var o=this;if(!t||!t.length)return i(),void 0;this._itemsOn(t,"layout",i);for(var n=[],r=0,s=t.length;s>r;r++){var a=t[r],u=this._getItemLayoutPosition(a);u.item=a,u.isInstant=e||a.isLayoutInstant,n.push(u)}this._processLayoutQueue(n)},m.prototype._getItemLayoutPosition=function(){return{x:0,y:0}},m.prototype._processLayoutQueue=function(t){for(var e=0,i=t.length;i>e;e++){var o=t[e];this._positionItem(o.item,o.x,o.y,o.isInstant)}},m.prototype._positionItem=function(t,e,i,o){o?t.goTo(e,i):t.moveTo(e,i)},m.prototype._postLayout=function(){this.resizeContainer()},m.prototype.resizeContainer=function(){if(this.options.isResizingContainer){var t=this._getContainerSize();t&&(this._setContainerMeasure(t.width,!0),this._setContainerMeasure(t.height,!1))}},m.prototype._getContainerSize=h,m.prototype._setContainerMeasure=function(t,e){if(void 0!==t){var i=this.size;i.isBorderBox&&(t+=e?i.paddingLeft+i.paddingRight+i.borderLeftWidth+i.borderRightWidth:i.paddingBottom+i.paddingTop+i.borderTopWidth+i.borderBottomWidth),t=Math.max(t,0),this.element.style[e?"width":"height"]=t+"px"}},m.prototype._itemsOn=function(t,e,i){function o(){return n++,n===r&&i.call(s),!0}for(var n=0,r=t.length,s=this,a=0,u=t.length;u>a;a++){var p=t[a];p.on(e,o)}},m.prototype.ignore=function(t){var e=this.getItem(t);e&&(e.isIgnored=!0)},m.prototype.unignore=function(t){var e=this.getItem(t);e&&delete e.isIgnored},m.prototype.stamp=function(t){if(t=this._find(t)){this.stamps=this.stamps.concat(t);for(var e=0,i=t.length;i>e;e++){var o=t[e];this.ignore(o)}}},m.prototype.unstamp=function(t){if(t=this._find(t))for(var e=0,i=t.length;i>e;e++){var o=t[e];n(o,this.stamps),this.unignore(o)}},m.prototype._find=function(t){return t?("string"==typeof t&&(t=this.element.querySelectorAll(t)),t=o(t)):void 0},m.prototype._manageStamps=function(){if(this.stamps&&this.stamps.length){this._getBoundingRect();for(var t=0,e=this.stamps.length;e>t;t++){var i=this.stamps[t];this._manageStamp(i)}}},m.prototype._getBoundingRect=function(){var t=this.element.getBoundingClientRect(),e=this.size;this._boundingRect={left:t.left+e.paddingLeft+e.borderLeftWidth,top:t.top+e.paddingTop+e.borderTopWidth,right:t.right-(e.paddingRight+e.borderRightWidth),bottom:t.bottom-(e.paddingBottom+e.borderBottomWidth)}},m.prototype._manageStamp=h,m.prototype._getElementOffset=function(t){var e=t.getBoundingClientRect(),i=this._boundingRect,o=l(t),n={left:e.left-i.left-o.marginLeft,top:e.top-i.top-o.marginTop,right:i.right-e.right-o.marginRight,bottom:i.bottom-e.bottom-o.marginBottom};return n},m.prototype.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},m.prototype.bindResize=function(){this.isResizeBound||(i.bind(t,"resize",this),this.isResizeBound=!0)},m.prototype.unbindResize=function(){this.isResizeBound&&i.unbind(t,"resize",this),this.isResizeBound=!1},m.prototype.onresize=function(){function t(){e.resize(),delete e.resizeTimeout}this.resizeTimeout&&clearTimeout(this.resizeTimeout);var e=this;this.resizeTimeout=setTimeout(t,100)},m.prototype.resize=function(){this.isResizeBound&&this.needsResizeLayout()&&this.layout()},m.prototype.needsResizeLayout=function(){var t=l(this.element),e=this.size&&t;return e&&t.innerWidth!==this.size.innerWidth},m.prototype.addItems=function(t){var e=this._itemize(t);return e.length&&(this.items=this.items.concat(e)),e},m.prototype.appended=function(t){var e=this.addItems(t);e.length&&(this.layoutItems(e,!0),this.reveal(e))},m.prototype.prepended=function(t){var e=this._itemize(t);if(e.length){var i=this.items.slice(0);this.items=e.concat(i),this._resetLayout(),this._manageStamps(),this.layoutItems(e,!0),this.reveal(e),this.layoutItems(i)}},m.prototype.reveal=function(t){var e=t&&t.length;if(e)for(var i=0;e>i;i++){var o=t[i];o.reveal()}},m.prototype.hide=function(t){var e=t&&t.length;if(e)for(var i=0;e>i;i++){var o=t[i];o.hide()}},m.prototype.getItem=function(t){for(var e=0,i=this.items.length;i>e;e++){var o=this.items[e];if(o.element===t)return o}},m.prototype.getItems=function(t){if(t&&t.length){for(var e=[],i=0,o=t.length;o>i;i++){var n=t[i],r=this.getItem(n);r&&e.push(r)}return e}},m.prototype.remove=function(t){t=o(t);var e=this.getItems(t);if(e&&e.length){this._itemsOn(e,"remove",function(){this.emitEvent("removeComplete",[this,e])});for(var i=0,r=e.length;r>i;i++){var s=e[i];s.remove(),n(s,this.items)}}},m.prototype.destroy=function(){var t=this.element.style;t.height="",t.position="",t.width="";for(var e=0,i=this.items.length;i>e;e++){var o=this.items[e];o.destroy()}this.unbindResize();var n=this.element.outlayerGUID;delete v[n],delete this.element.outlayerGUID,p&&p.removeData(this.element,this.constructor.namespace)},m.data=function(t){var e=t&&t.outlayerGUID;return e&&v[e]},m.create=function(t,i){function o(){m.apply(this,arguments)}return Object.create?o.prototype=Object.create(m.prototype):e(o.prototype,m.prototype),o.prototype.constructor=o,o.defaults=e({},m.defaults),e(o.defaults,i),o.prototype.settings={},o.namespace=t,o.data=m.data,o.Item=function(){y.apply(this,arguments)},o.Item.prototype=new y,s(function(){for(var e=r(t),i=a.querySelectorAll(".js-"+e),n="data-"+e+"-options",s=0,h=i.length;h>s;s++){var f,d=i[s],l=d.getAttribute(n);try{f=l&&JSON.parse(l)}catch(c){u&&u.error("Error parsing "+n+" on "+d.nodeName.toLowerCase()+(d.id?"#"+d.id:"")+": "+c);continue}var y=new o(d,f);p&&p.data(d,t,y)}}),p&&p.bridget&&p.bridget(t,o),o},m.Item=y,m}var a=t.document,u=t.console,p=t.jQuery,h=function(){},f=Object.prototype.toString,d="function"==typeof HTMLElement||"object"==typeof HTMLElement?function(t){return t instanceof HTMLElement}:function(t){return t&&"object"==typeof t&&1===t.nodeType&&"string"==typeof t.nodeName},l=Array.prototype.indexOf?function(t,e){return t.indexOf(e)}:function(t,e){for(var i=0,o=t.length;o>i;i++)if(t[i]===e)return i;return-1};"function"==typeof define&&define.amd?define("outlayer/outlayer",["eventie/eventie","doc-ready/doc-ready","eventEmitter/EventEmitter","get-size/get-size","matches-selector/matches-selector","./item"],s):"object"==typeof exports?module.exports=s(require("eventie"),require("doc-ready"),require("wolfy87-eventemitter"),require("get-size"),require("desandro-matches-selector"),require("./item")):t.Outlayer=s(t.eventie,t.docReady,t.EventEmitter,t.getSize,t.matchesSelector,t.Outlayer.Item)}(window),function(t){function e(t){function e(){t.Item.apply(this,arguments)}e.prototype=new t.Item,e.prototype._create=function(){this.id=this.layout.itemGUID++,t.Item.prototype._create.call(this),this.sortData={}},e.prototype.updateSortData=function(){if(!this.isIgnored){this.sortData.id=this.id,this.sortData["original-order"]=this.id,this.sortData.random=Math.random();var t=this.layout.options.getSortData,e=this.layout._sorters;for(var i in t){var o=e[i];this.sortData[i]=o(this.element,this)}}};var i=e.prototype.destroy;return e.prototype.destroy=function(){i.apply(this,arguments),this.css({display:""})},e}"function"==typeof define&&define.amd?define("isotope/js/item",["outlayer/outlayer"],e):"object"==typeof exports?module.exports=e(require("outlayer")):(t.Isotope=t.Isotope||{},t.Isotope.Item=e(t.Outlayer))}(window),function(t){function e(t,e){function i(t){this.isotope=t,t&&(this.options=t.options[this.namespace],this.element=t.element,this.items=t.filteredItems,this.size=t.size)}return function(){function t(t){return function(){return e.prototype[t].apply(this.isotope,arguments)}}for(var o=["_resetLayout","_getItemLayoutPosition","_manageStamp","_getContainerSize","_getElementOffset","needsResizeLayout"],n=0,r=o.length;r>n;n++){var s=o[n];i.prototype[s]=t(s)}}(),i.prototype.needsVerticalResizeLayout=function(){var e=t(this.isotope.element),i=this.isotope.size&&e;return i&&e.innerHeight!==this.isotope.size.innerHeight},i.prototype._getMeasurement=function(){this.isotope._getMeasurement.apply(this,arguments)},i.prototype.getColumnWidth=function(){this.getSegmentSize("column","Width")},i.prototype.getRowHeight=function(){this.getSegmentSize("row","Height")},i.prototype.getSegmentSize=function(t,e){var i=t+e,o="outer"+e;if(this._getMeasurement(i,o),!this[i]){var n=this.getFirstItemSize();this[i]=n&&n[o]||this.isotope.size["inner"+e]}},i.prototype.getFirstItemSize=function(){var e=this.isotope.filteredItems[0];return e&&e.element&&t(e.element)},i.prototype.layout=function(){this.isotope.layout.apply(this.isotope,arguments)},i.prototype.getSize=function(){this.isotope.getSize(),this.size=this.isotope.size},i.modes={},i.create=function(t,e){function o(){i.apply(this,arguments)}return o.prototype=new i,e&&(o.options=e),o.prototype.namespace=t,i.modes[t]=o,o},i}"function"==typeof define&&define.amd?define("isotope/js/layout-mode",["get-size/get-size","outlayer/outlayer"],e):"object"==typeof exports?module.exports=e(require("get-size"),require("outlayer")):(t.Isotope=t.Isotope||{},t.Isotope.LayoutMode=e(t.getSize,t.Outlayer))}(window),function(t){function e(t,e){var o=t.create("masonry");return o.prototype._resetLayout=function(){this.getSize(),this._getMeasurement("columnWidth","outerWidth"),this._getMeasurement("gutter","outerWidth"),this.measureColumns();var t=this.cols;for(this.colYs=[];t--;)this.colYs.push(0);this.maxY=0},o.prototype.measureColumns=function(){if(this.getContainerWidth(),!this.columnWidth){var t=this.items[0],i=t&&t.element;this.columnWidth=i&&e(i).outerWidth||this.containerWidth}this.columnWidth+=this.gutter,this.cols=Math.floor((this.containerWidth+this.gutter)/this.columnWidth),this.cols=Math.max(this.cols,1)},o.prototype.getContainerWidth=function(){var t=this.options.isFitWidth?this.element.parentNode:this.element,i=e(t);this.containerWidth=i&&i.innerWidth},o.prototype._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth%this.columnWidth,o=e&&1>e?"round":"ceil",n=Math[o](t.size.outerWidth/this.columnWidth);n=Math.min(n,this.cols);for(var r=this._getColGroup(n),s=Math.min.apply(Math,r),a=i(r,s),u={x:this.columnWidth*a,y:s},p=s+t.size.outerHeight,h=this.cols+1-r.length,f=0;h>f;f++)this.colYs[a+f]=p;return u},o.prototype._getColGroup=function(t){if(2>t)return this.colYs;for(var e=[],i=this.cols+1-t,o=0;i>o;o++){var n=this.colYs.slice(o,o+t);e[o]=Math.max.apply(Math,n)}return e},o.prototype._manageStamp=function(t){var i=e(t),o=this._getElementOffset(t),n=this.options.isOriginLeft?o.left:o.right,r=n+i.outerWidth,s=Math.floor(n/this.columnWidth);s=Math.max(0,s);var a=Math.floor(r/this.columnWidth);a-=r%this.columnWidth?0:1,a=Math.min(this.cols-1,a);for(var u=(this.options.isOriginTop?o.top:o.bottom)+i.outerHeight,p=s;a>=p;p++)this.colYs[p]=Math.max(u,this.colYs[p])},o.prototype._getContainerSize=function(){this.maxY=Math.max.apply(Math,this.colYs);var t={height:this.maxY};return this.options.isFitWidth&&(t.width=this._getContainerFitWidth()),t},o.prototype._getContainerFitWidth=function(){for(var t=0,e=this.cols;--e&&0===this.colYs[e];)t++;return(this.cols-t)*this.columnWidth-this.gutter},o.prototype.needsResizeLayout=function(){var t=this.containerWidth;return this.getContainerWidth(),t!==this.containerWidth},o}var i=Array.prototype.indexOf?function(t,e){return t.indexOf(e)}:function(t,e){for(var i=0,o=t.length;o>i;i++){var n=t[i];if(n===e)return i}return-1};"function"==typeof define&&define.amd?define("masonry/masonry",["outlayer/outlayer","get-size/get-size"],e):"object"==typeof exports?module.exports=e(require("outlayer"),require("get-size")):t.Masonry=e(t.Outlayer,t.getSize)}(window),function(t){function e(t,e){for(var i in e)t[i]=e[i];return t}function i(t,i){var o=t.create("masonry"),n=o.prototype._getElementOffset,r=o.prototype.layout,s=o.prototype._getMeasurement;e(o.prototype,i.prototype),o.prototype._getElementOffset=n,o.prototype.layout=r,o.prototype._getMeasurement=s;var a=o.prototype.measureColumns;o.prototype.measureColumns=function(){this.items=this.isotope.filteredItems,a.call(this)};var u=o.prototype._manageStamp;return o.prototype._manageStamp=function(){this.options.isOriginLeft=this.isotope.options.isOriginLeft,this.options.isOriginTop=this.isotope.options.isOriginTop,u.apply(this,arguments)},o}"function"==typeof define&&define.amd?define("isotope/js/layout-modes/masonry",["../layout-mode","masonry/masonry"],i):"object"==typeof exports?module.exports=i(require("../layout-mode"),require("masonry-layout")):i(t.Isotope.LayoutMode,t.Masonry)}(window),function(t){function e(t){var e=t.create("fitRows");return e.prototype._resetLayout=function(){this.x=0,this.y=0,this.maxY=0,this._getMeasurement("gutter","outerWidth")},e.prototype._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth+this.gutter,i=this.isotope.size.innerWidth+this.gutter;0!==this.x&&e+this.x>i&&(this.x=0,this.y=this.maxY);var o={x:this.x,y:this.y};return this.maxY=Math.max(this.maxY,this.y+t.size.outerHeight),this.x+=e,o},e.prototype._getContainerSize=function(){return{height:this.maxY}},e}"function"==typeof define&&define.amd?define("isotope/js/layout-modes/fit-rows",["../layout-mode"],e):"object"==typeof exports?module.exports=e(require("../layout-mode")):e(t.Isotope.LayoutMode)}(window),function(t){function e(t){var e=t.create("vertical",{horizontalAlignment:0});return e.prototype._resetLayout=function(){this.y=0},e.prototype._getItemLayoutPosition=function(t){t.getSize();var e=(this.isotope.size.innerWidth-t.size.outerWidth)*this.options.horizontalAlignment,i=this.y;return this.y+=t.size.outerHeight,{x:e,y:i}},e.prototype._getContainerSize=function(){return{height:this.y}},e}"function"==typeof define&&define.amd?define("isotope/js/layout-modes/vertical",["../layout-mode"],e):"object"==typeof exports?module.exports=e(require("../layout-mode")):e(t.Isotope.LayoutMode)}(window),function(t){function e(t,e){for(var i in e)t[i]=e[i];return t}function i(t){return"[object Array]"===h.call(t)}function o(t){var e=[];if(i(t))e=t;else if(t&&"number"==typeof t.length)for(var o=0,n=t.length;n>o;o++)e.push(t[o]);else e.push(t);return e}function n(t,e){var i=f(e,t);-1!==i&&e.splice(i,1)}function r(t,i,r,u,h){function f(t,e){return function(i,o){for(var n=0,r=t.length;r>n;n++){var s=t[n],a=i.sortData[s],u=o.sortData[s];if(a>u||u>a){var p=void 0!==e[s]?e[s]:e,h=p?1:-1;return(a>u?1:-1)*h}}return 0}}var d=t.create("isotope",{layoutMode:"masonry",isJQueryFiltering:!0,sortAscending:!0});d.Item=u,d.LayoutMode=h,d.prototype._create=function(){this.itemGUID=0,this._sorters={},this._getSorters(),t.prototype._create.call(this),this.modes={},this.filteredItems=this.items,this.sortHistory=["original-order"];for(var e in h.modes)this._initLayoutMode(e)},d.prototype.reloadItems=function(){this.itemGUID=0,t.prototype.reloadItems.call(this)},d.prototype._itemize=function(){for(var e=t.prototype._itemize.apply(this,arguments),i=0,o=e.length;o>i;i++){var n=e[i];n.id=this.itemGUID++}return this._updateItemsSortData(e),e
},d.prototype._initLayoutMode=function(t){var i=h.modes[t],o=this.options[t]||{};this.options[t]=i.options?e(i.options,o):o,this.modes[t]=new i(this)},d.prototype.layout=function(){return!this._isLayoutInited&&this.options.isInitLayout?(this.arrange(),void 0):(this._layout(),void 0)},d.prototype._layout=function(){var t=this._getIsInstant();this._resetLayout(),this._manageStamps(),this.layoutItems(this.filteredItems,t),this._isLayoutInited=!0},d.prototype.arrange=function(t){function e(){o.reveal(i.needReveal),o.hide(i.needHide)}this.option(t),this._getIsInstant();var i=this._filter(this.items);this.filteredItems=i.matches;var o=this;this._isInstant?this._noTransition(e):e(),this._sort(),this._layout()},d.prototype._init=d.prototype.arrange,d.prototype._getIsInstant=function(){var t=void 0!==this.options.isLayoutInstant?this.options.isLayoutInstant:!this._isLayoutInited;return this._isInstant=t,t},d.prototype._filter=function(t){var e=this.options.filter;e=e||"*";for(var i=[],o=[],n=[],r=this._getFilterTest(e),s=0,a=t.length;a>s;s++){var u=t[s];if(!u.isIgnored){var p=r(u);p&&i.push(u),p&&u.isHidden?o.push(u):p||u.isHidden||n.push(u)}}return{matches:i,needReveal:o,needHide:n}},d.prototype._getFilterTest=function(t){return s&&this.options.isJQueryFiltering?function(e){return s(e.element).is(t)}:"function"==typeof t?function(e){return t(e.element)}:function(e){return r(e.element,t)}},d.prototype.updateSortData=function(t){var e;t?(t=o(t),e=this.getItems(t)):e=this.items,this._getSorters(),this._updateItemsSortData(e)},d.prototype._getSorters=function(){var t=this.options.getSortData;for(var e in t){var i=t[e];this._sorters[e]=l(i)}},d.prototype._updateItemsSortData=function(t){for(var e=t&&t.length,i=0;e&&e>i;i++){var o=t[i];o.updateSortData()}};var l=function(){function t(t){if("string"!=typeof t)return t;var i=a(t).split(" "),o=i[0],n=o.match(/^\[(.+)\]$/),r=n&&n[1],s=e(r,o),u=d.sortDataParsers[i[1]];return t=u?function(t){return t&&u(s(t))}:function(t){return t&&s(t)}}function e(t,e){var i;return i=t?function(e){return e.getAttribute(t)}:function(t){var i=t.querySelector(e);return i&&p(i)}}return t}();d.sortDataParsers={parseInt:function(t){return parseInt(t,10)},parseFloat:function(t){return parseFloat(t)}},d.prototype._sort=function(){var t=this.options.sortBy;if(t){var e=[].concat.apply(t,this.sortHistory),i=f(e,this.options.sortAscending);this.filteredItems.sort(i),t!==this.sortHistory[0]&&this.sortHistory.unshift(t)}},d.prototype._mode=function(){var t=this.options.layoutMode,e=this.modes[t];if(!e)throw Error("No layout mode: "+t);return e.options=this.options[t],e},d.prototype._resetLayout=function(){t.prototype._resetLayout.call(this),this._mode()._resetLayout()},d.prototype._getItemLayoutPosition=function(t){return this._mode()._getItemLayoutPosition(t)},d.prototype._manageStamp=function(t){this._mode()._manageStamp(t)},d.prototype._getContainerSize=function(){return this._mode()._getContainerSize()},d.prototype.needsResizeLayout=function(){return this._mode().needsResizeLayout()},d.prototype.appended=function(t){var e=this.addItems(t);if(e.length){var i=this._filterRevealAdded(e);this.filteredItems=this.filteredItems.concat(i)}},d.prototype.prepended=function(t){var e=this._itemize(t);if(e.length){this._resetLayout(),this._manageStamps();var i=this._filterRevealAdded(e);this.layoutItems(this.filteredItems),this.filteredItems=i.concat(this.filteredItems),this.items=e.concat(this.items)}},d.prototype._filterRevealAdded=function(t){var e=this._filter(t);return this.hide(e.needHide),this.reveal(e.matches),this.layoutItems(e.matches,!0),e.matches},d.prototype.insert=function(t){var e=this.addItems(t);if(e.length){var i,o,n=e.length;for(i=0;n>i;i++)o=e[i],this.element.appendChild(o.element);var r=this._filter(e).matches;for(i=0;n>i;i++)e[i].isLayoutInstant=!0;for(this.arrange(),i=0;n>i;i++)delete e[i].isLayoutInstant;this.reveal(r)}};var c=d.prototype.remove;return d.prototype.remove=function(t){t=o(t);var e=this.getItems(t);if(c.call(this,t),e&&e.length)for(var i=0,r=e.length;r>i;i++){var s=e[i];n(s,this.filteredItems)}},d.prototype.shuffle=function(){for(var t=0,e=this.items.length;e>t;t++){var i=this.items[t];i.sortData.random=Math.random()}this.options.sortBy="random",this._sort(),this._layout()},d.prototype._noTransition=function(t){var e=this.options.transitionDuration;this.options.transitionDuration=0;var i=t.call(this);return this.options.transitionDuration=e,i},d.prototype.getFilteredItemElements=function(){for(var t=[],e=0,i=this.filteredItems.length;i>e;e++)t.push(this.filteredItems[e].element);return t},d}var s=t.jQuery,a=String.prototype.trim?function(t){return t.trim()}:function(t){return t.replace(/^\s+|\s+$/g,"")},u=document.documentElement,p=u.textContent?function(t){return t.textContent}:function(t){return t.innerText},h=Object.prototype.toString,f=Array.prototype.indexOf?function(t,e){return t.indexOf(e)}:function(t,e){for(var i=0,o=t.length;o>i;i++)if(t[i]===e)return i;return-1};"function"==typeof define&&define.amd?define(["outlayer/outlayer","get-size/get-size","matches-selector/matches-selector","isotope/js/item","isotope/js/layout-mode","isotope/js/layout-modes/masonry","isotope/js/layout-modes/fit-rows","isotope/js/layout-modes/vertical"],r):"object"==typeof exports?module.exports=r(require("outlayer"),require("get-size"),require("desandro-matches-selector"),require("./item"),require("./layout-mode"),require("./layout-modes/masonry"),require("./layout-modes/fit-rows"),require("./layout-modes/vertical")):t.Isotope=r(t.Outlayer,t.getSize,t.matchesSelector,t.Isotope.Item,t.Isotope.LayoutMode)}(window);

/*!
Waypoints - 3.1.1
Copyright © 2011-2015 Caleb Troughton
Licensed under the MIT license.
https://github.com/imakewebthings/waypoints/blog/master/licenses.txt
*/
!function(){"use strict";function t(o){if(!o)throw new Error("No options passed to Waypoint constructor");if(!o.element)throw new Error("No element option passed to Waypoint constructor");if(!o.handler)throw new Error("No handler option passed to Waypoint constructor");this.key="waypoint-"+e,this.options=t.Adapter.extend({},t.defaults,o),this.element=this.options.element,this.adapter=new t.Adapter(this.element),this.callback=o.handler,this.axis=this.options.horizontal?"horizontal":"vertical",this.enabled=this.options.enabled,this.triggerPoint=null,this.group=t.Group.findOrCreate({name:this.options.group,axis:this.axis}),this.context=t.Context.findOrCreateByElement(this.options.context),t.offsetAliases[this.options.offset]&&(this.options.offset=t.offsetAliases[this.options.offset]),this.group.add(this),this.context.add(this),i[this.key]=this,e+=1}var e=0,i={};t.prototype.queueTrigger=function(t){this.group.queueTrigger(this,t)},t.prototype.trigger=function(t){this.enabled&&this.callback&&this.callback.apply(this,t)},t.prototype.destroy=function(){this.context.remove(this),this.group.remove(this),delete i[this.key]},t.prototype.disable=function(){return this.enabled=!1,this},t.prototype.enable=function(){return this.context.refresh(),this.enabled=!0,this},t.prototype.next=function(){return this.group.next(this)},t.prototype.previous=function(){return this.group.previous(this)},t.invokeAll=function(t){var e=[];for(var o in i)e.push(i[o]);for(var n=0,r=e.length;r>n;n++)e[n][t]()},t.destroyAll=function(){t.invokeAll("destroy")},t.disableAll=function(){t.invokeAll("disable")},t.enableAll=function(){t.invokeAll("enable")},t.refreshAll=function(){t.Context.refreshAll()},t.viewportHeight=function(){return window.innerHeight||document.documentElement.clientHeight},t.viewportWidth=function(){return document.documentElement.clientWidth},t.adapters=[],t.defaults={context:window,continuous:!0,enabled:!0,group:"default",horizontal:!1,offset:0},t.offsetAliases={"bottom-in-view":function(){return this.context.innerHeight()-this.adapter.outerHeight()},"right-in-view":function(){return this.context.innerWidth()-this.adapter.outerWidth()}},window.Waypoint=t}(),function(){"use strict";function t(t){window.setTimeout(t,1e3/60)}function e(t){this.element=t,this.Adapter=n.Adapter,this.adapter=new this.Adapter(t),this.key="waypoint-context-"+i,this.didScroll=!1,this.didResize=!1,this.oldScroll={x:this.adapter.scrollLeft(),y:this.adapter.scrollTop()},this.waypoints={vertical:{},horizontal:{}},t.waypointContextKey=this.key,o[t.waypointContextKey]=this,i+=1,this.createThrottledScrollHandler(),this.createThrottledResizeHandler()}var i=0,o={},n=window.Waypoint,r=window.onload;e.prototype.add=function(t){var e=t.options.horizontal?"horizontal":"vertical";this.waypoints[e][t.key]=t,this.refresh()},e.prototype.checkEmpty=function(){var t=this.Adapter.isEmptyObject(this.waypoints.horizontal),e=this.Adapter.isEmptyObject(this.waypoints.vertical);t&&e&&(this.adapter.off(".waypoints"),delete o[this.key])},e.prototype.createThrottledResizeHandler=function(){function t(){e.handleResize(),e.didResize=!1}var e=this;this.adapter.on("resize.waypoints",function(){e.didResize||(e.didResize=!0,n.requestAnimationFrame(t))})},e.prototype.createThrottledScrollHandler=function(){function t(){e.handleScroll(),e.didScroll=!1}var e=this;this.adapter.on("scroll.waypoints",function(){(!e.didScroll||n.isTouch)&&(e.didScroll=!0,n.requestAnimationFrame(t))})},e.prototype.handleResize=function(){n.Context.refreshAll()},e.prototype.handleScroll=function(){var t={},e={horizontal:{newScroll:this.adapter.scrollLeft(),oldScroll:this.oldScroll.x,forward:"right",backward:"left"},vertical:{newScroll:this.adapter.scrollTop(),oldScroll:this.oldScroll.y,forward:"down",backward:"up"}};for(var i in e){var o=e[i],n=o.newScroll>o.oldScroll,r=n?o.forward:o.backward;for(var s in this.waypoints[i]){var a=this.waypoints[i][s],l=o.oldScroll<a.triggerPoint,h=o.newScroll>=a.triggerPoint,p=l&&h,u=!l&&!h;(p||u)&&(a.queueTrigger(r),t[a.group.id]=a.group)}}for(var c in t)t[c].flushTriggers();this.oldScroll={x:e.horizontal.newScroll,y:e.vertical.newScroll}},e.prototype.innerHeight=function(){return this.element==this.element.window?n.viewportHeight():this.adapter.innerHeight()},e.prototype.remove=function(t){delete this.waypoints[t.axis][t.key],this.checkEmpty()},e.prototype.innerWidth=function(){return this.element==this.element.window?n.viewportWidth():this.adapter.innerWidth()},e.prototype.destroy=function(){var t=[];for(var e in this.waypoints)for(var i in this.waypoints[e])t.push(this.waypoints[e][i]);for(var o=0,n=t.length;n>o;o++)t[o].destroy()},e.prototype.refresh=function(){var t,e=this.element==this.element.window,i=this.adapter.offset(),o={};this.handleScroll(),t={horizontal:{contextOffset:e?0:i.left,contextScroll:e?0:this.oldScroll.x,contextDimension:this.innerWidth(),oldScroll:this.oldScroll.x,forward:"right",backward:"left",offsetProp:"left"},vertical:{contextOffset:e?0:i.top,contextScroll:e?0:this.oldScroll.y,contextDimension:this.innerHeight(),oldScroll:this.oldScroll.y,forward:"down",backward:"up",offsetProp:"top"}};for(var n in t){var r=t[n];for(var s in this.waypoints[n]){var a,l,h,p,u,c=this.waypoints[n][s],d=c.options.offset,f=c.triggerPoint,w=0,y=null==f;c.element!==c.element.window&&(w=c.adapter.offset()[r.offsetProp]),"function"==typeof d?d=d.apply(c):"string"==typeof d&&(d=parseFloat(d),c.options.offset.indexOf("%")>-1&&(d=Math.ceil(r.contextDimension*d/100))),a=r.contextScroll-r.contextOffset,c.triggerPoint=w+a-d,l=f<r.oldScroll,h=c.triggerPoint>=r.oldScroll,p=l&&h,u=!l&&!h,!y&&p?(c.queueTrigger(r.backward),o[c.group.id]=c.group):!y&&u?(c.queueTrigger(r.forward),o[c.group.id]=c.group):y&&r.oldScroll>=c.triggerPoint&&(c.queueTrigger(r.forward),o[c.group.id]=c.group)}}for(var g in o)o[g].flushTriggers();return this},e.findOrCreateByElement=function(t){return e.findByElement(t)||new e(t)},e.refreshAll=function(){for(var t in o)o[t].refresh()},e.findByElement=function(t){return o[t.waypointContextKey]},window.onload=function(){r&&r(),e.refreshAll()},n.requestAnimationFrame=function(e){var i=window.requestAnimationFrame||window.mozRequestAnimationFrame||window.webkitRequestAnimationFrame||t;i.call(window,e)},n.Context=e}(),function(){"use strict";function t(t,e){return t.triggerPoint-e.triggerPoint}function e(t,e){return e.triggerPoint-t.triggerPoint}function i(t){this.name=t.name,this.axis=t.axis,this.id=this.name+"-"+this.axis,this.waypoints=[],this.clearTriggerQueues(),o[this.axis][this.name]=this}var o={vertical:{},horizontal:{}},n=window.Waypoint;i.prototype.add=function(t){this.waypoints.push(t)},i.prototype.clearTriggerQueues=function(){this.triggerQueues={up:[],down:[],left:[],right:[]}},i.prototype.flushTriggers=function(){for(var i in this.triggerQueues){var o=this.triggerQueues[i],n="up"===i||"left"===i;o.sort(n?e:t);for(var r=0,s=o.length;s>r;r+=1){var a=o[r];(a.options.continuous||r===o.length-1)&&a.trigger([i])}}this.clearTriggerQueues()},i.prototype.next=function(e){this.waypoints.sort(t);var i=n.Adapter.inArray(e,this.waypoints),o=i===this.waypoints.length-1;return o?null:this.waypoints[i+1]},i.prototype.previous=function(e){this.waypoints.sort(t);var i=n.Adapter.inArray(e,this.waypoints);return i?this.waypoints[i-1]:null},i.prototype.queueTrigger=function(t,e){this.triggerQueues[e].push(t)},i.prototype.remove=function(t){var e=n.Adapter.inArray(t,this.waypoints);e>-1&&this.waypoints.splice(e,1)},i.prototype.first=function(){return this.waypoints[0]},i.prototype.last=function(){return this.waypoints[this.waypoints.length-1]},i.findOrCreate=function(t){return o[t.axis][t.name]||new i(t)},n.Group=i}(),function(){"use strict";function t(t){this.$element=e(t)}var e=window.jQuery,i=window.Waypoint;e.each(["innerHeight","innerWidth","off","offset","on","outerHeight","outerWidth","scrollLeft","scrollTop"],function(e,i){t.prototype[i]=function(){var t=Array.prototype.slice.call(arguments);return this.$element[i].apply(this.$element,t)}}),e.each(["extend","inArray","isEmptyObject"],function(i,o){t[o]=e[o]}),i.adapters.push({name:"jquery",Adapter:t}),i.Adapter=t}(),function(){"use strict";function t(t){return function(){var i=[],o=arguments[0];return t.isFunction(arguments[0])&&(o=t.extend({},arguments[1]),o.handler=arguments[0]),this.each(function(){var n=t.extend({},o,{element:this});"string"==typeof n.context&&(n.context=t(this).closest(n.context)[0]),i.push(new e(n))}),i}}var e=window.Waypoint;window.jQuery&&(window.jQuery.fn.waypoint=t(window.jQuery)),window.Zepto&&(window.Zepto.fn.waypoint=t(window.Zepto))}();


/*
 * jQuery Browser Plugin 0.0.5
 * https://github.com/gabceb/jquery-browser-plugin
 *
 * Original jquery-browser code Copyright 2005, 2013 jQuery Foundation, Inc. and other contributors
 * http://jquery.org/license
 *
 * Modifications Copyright 2014 Gabriel Cebrian
 * https://github.com/gabceb
 *
 * Released under the MIT license
 */!function(a,b){"use strict";var c,d;if(a.uaMatch=function(a){a=a.toLowerCase();var b=/(opr)[\/]([\w.]+)/.exec(a)||/(chrome)[ \/]([\w.]+)/.exec(a)||/(version)[ \/]([\w.]+).*(safari)[ \/]([\w.]+)/.exec(a)||/(webkit)[ \/]([\w.]+)/.exec(a)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(a)||/(msie) ([\w.]+)/.exec(a)||a.indexOf("trident")>=0&&/(rv)(?::| )([\w.]+)/.exec(a)||a.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(a)||[],c=/(ipad)/.exec(a)||/(iphone)/.exec(a)||/(android)/.exec(a)||/(windows phone)/.exec(a)||/(win)/.exec(a)||/(mac)/.exec(a)||/(linux)/.exec(a)||[];return{browser:b[3]||b[1]||"",version:b[2]||"0",platform:c[0]||""}},c=a.uaMatch(b.navigator.userAgent),d={},c.browser&&(d[c.browser]=!0,d.version=c.version,d.versionNumber=parseInt(c.version)),c.platform&&(d[c.platform]=!0),(d.chrome||d.opr||d.safari)&&(d.webkit=!0),d.rv){var e="msie";c.browser=e,d[e]=!0}if(d.opr){var f="opera";c.browser=f,d[f]=!0}if(d.safari&&d.android){var g="android";c.browser=g,d[g]=!0}d.name=c.browser,d.platform=c.
platform,a.browser=d}(jQuery,window);

/*Vimeo Frogaloop API for videos*/
var Froogaloop=function(){function e(a){return new e.fn.init(a)}function h(a,c,b){if(!b.contentWindow.postMessage)return!1;var f=b.getAttribute("src").split("?")[0],a=JSON.stringify({method:a,value:c});"//"===f.substr(0,2)&&(f=window.location.protocol+f);b.contentWindow.postMessage(a,f)}function j(a){var c,b;try{c=JSON.parse(a.data),b=c.event||c.method}catch(f){}"ready"==b&&!i&&(i=!0);if(a.origin!=k)return!1;var a=c.value,e=c.data,g=""===g?null:c.player_id;c=g?d[g][b]:d[b];b=[];if(!c)return!1;void 0!==
a&&b.push(a);e&&b.push(e);g&&b.push(g);return 0<b.length?c.apply(null,b):c.call()}function l(a,c,b){b?(d[b]||(d[b]={}),d[b][a]=c):d[a]=c}var d={},i=!1,k="";e.fn=e.prototype={element:null,init:function(a){"string"===typeof a&&(a=document.getElementById(a));this.element=a;a=this.element.getAttribute("src");"//"===a.substr(0,2)&&(a=window.location.protocol+a);for(var a=a.split("/"),c="",b=0,f=a.length;b<f;b++){if(3>b)c+=a[b];else break;2>b&&(c+="/")}k=c;return this},api:function(a,c){if(!this.element||
!a)return!1;var b=this.element,f=""!==b.id?b.id:null,d=!c||!c.constructor||!c.call||!c.apply?c:null,e=c&&c.constructor&&c.call&&c.apply?c:null;e&&l(a,e,f);h(a,d,b);return this},addEvent:function(a,c){if(!this.element)return!1;var b=this.element,d=""!==b.id?b.id:null;l(a,c,d);"ready"!=a?h("addEventListener",a,b):"ready"==a&&i&&c.call(null,d);return this},removeEvent:function(a){if(!this.element)return!1;var c=this.element,b;a:{if((b=""!==c.id?c.id:null)&&d[b]){if(!d[b][a]){b=!1;break a}d[b][a]=null}else{if(!d[a]){b=
!1;break a}d[a]=null}b=!0}"ready"!=a&&b&&h("removeEventListener",a,c)}};e.fn.init.prototype=e.fn;window.addEventListener?window.addEventListener("message",j,!1):window.attachEvent("onmessage",j);return window.Froogaloop=window.$f=e}();


// http://paulirish.com/2011/requestanimationframe-for-smart-animating/ + http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating
// requestAnimationFrame polyfill by Erik Möller. fixes from Paul Irish and Tino Zijdel. can be removed if IE9 is no longer supported or all parallax scripts are gone MIT license
(function(){var lastTime=0;var vendors=['ms','moz','webkit','o'];for(var x=0;x<vendors.length&&!window.requestAnimationFrame;++x){window.requestAnimationFrame=window[vendors[x]+'RequestAnimationFrame'];window.cancelAnimationFrame=window[vendors[x]+'CancelAnimationFrame']||window[vendors[x]+'CancelRequestAnimationFrame']}if(!window.requestAnimationFrame)window.requestAnimationFrame=function(callback,element){var currTime=new Date().getTime();var timeToCall=Math.max(0,16-(currTime-lastTime));var id=window.setTimeout(function(){callback(currTime+timeToCall)},timeToCall);lastTime=currTime+timeToCall;return id};if(!window.cancelAnimationFrame)window.cancelAnimationFrame=function(id){clearTimeout(id)}}());

