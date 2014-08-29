/*
	Author: http://codecanyon.net/user/sike?ref=sike
*/

;(function($) {
    $.fn.hotSpot = function(options) {
	   	// plugin default options
		var settings = {
			triggerBy: 'click',  /* mouseover | click */
			delay: 0,
			slideshow: true,
			loop: true,
			autoHide: true,
			slideshowDelay: 4000,
			sticky: true,
			dropInEase: false,
	        displayVideo : true,
	        customIcon : '',
			clickImageToClose: true
		};


		// extends settings with options provided
        if (options) {
			$.extend(settings, options);
		}

		var _this = this;

		var _allowOut = true;
		_this.data('_allowOut', _allowOut);
		var _popOutID;
		var _popArr = [];
		_this.data('slideshow', settings.slideshow);
		// to do: add video support
		var _popContentArr = [];
		$('.popover', _this).each(function(index) {
			var _top = $(this).data('top');
			var _left = $(this).data('left');
			var _width = $(this).data('width');
			if(_width!=0&&_width!=236&&_width!=""){
				$(this).css('width', _width);
				$(this).find('.popover-content').css('width', _width - 28);
				$(this).find('h4.popover-title').css('width', _width - 27);
			}
			var _style = $(this).data('style');
			var _direction = $(this).data('direction');
			if(_style!=""){
				$(this).addClass(_style);
				$(this).find('h4.popover-title').addClass(_style);
				// var _border = 'border-'+_direction+'-color';
				// $(this).find('.arrow').css({
					// 'border-top-color' : '#FFF',
					// 'border-top-color' : 'rgba(255, 255, 255, 0.2)'
				// });
			}
			// var _background = $(this).data('background');
			// var _fontcolor = $(this).data('fontcolor');
			// if(_background!=""){
			// 	$(this).css('background-color', _background);
			// 	$(this).find('.arrow').css('border-top-color', _background);
			// }
			// if(_fontcolor!=""){
			// 	$(this).css('color', _fontcolor);
			// }
			// var _width = $(this).width();
			if(settings.displayVideo){
				var _frame = $(this).find('iframe');
				if(_frame){
					_frame.data('videourl', _frame.attr('src'));
				}
			}


			var _height = $(this).height();
			_popArr[index] = $(this).data('index', index);
			_popContentArr[index] = $('.popover-content > p', $(this)).html();
			if(settings.customIcon!=""){
				$(this).next('.cq-hotspot-custom-icon').css('background', 'url('+ settings.customIcon +') no-repeat');
			}
			$(this).next('.info-icon').show().css({
				top: _top,
				left: _left
			}).delay(500).animate({opacity:1}, 300);
			switch(_direction) {
				case 'top':
					$(this).css({
						top: _top - _height,
						left: _left - _width*.5 + 10
					});
					break;
				case 'left':
					$(this).css({
						top: _top - _height*.5 + 10,
						left: _left - _width - 2
					});
					break;
				case 'bottom':
					$(this).css({
						top: _top + 22,
						left: _left - _width*.5 + 10
					});
					break;
				case 'right':
					$(this).css({
						top: _top - _height*.5 + 10,
						left: _left + 22
					});
					break;
				default:
			}

			if(settings.sticky){
				$(this).on('mouseover', function(event) {
					clearTimeout(_popOutID);
					clearTimeout(_slideID);
					_this.data('_allowOut', false);
				}).on('mouseleave', function(event) {
					if(_this.data('slideshow')||settings.autoHide&&_currentPop){
						clearTimeout(_popOutID);
						_popOutID = setTimeout(function() {
							_this.data('_allowOut', true);
	 	                   	if(Modernizr.csstransitions){
								_currentPop.removeClass('cardIn'+_currentPop.data('direction')).addClass('cardOut'+_currentPop.data('direction')).on('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function(event) {
									clearTimeout(_slideID);
									if(_this.data('slideshow')) startSlideshow();
								});
							}else{
								_currentPop.animate({opacity: 0}, 300, function(){
									clearTimeout(_slideID);
									if(_this.data('slideshow')) startSlideshow();
								});
							}
							_currentPop.data('isshow', false);
						}, settings.delay);
					}
				});
			}
		});

		var _popover_image = $(".popover-image", _this)[0];
		var _containerW, _containerH;
		$("<img/>").attr("src", $(_popover_image).attr("src")).load(function() {
	        _containerW = this.width;
	        _containerH = this.height;
			$(window).trigger('resize');
	    });

		function _resetPopPos(){
			$('.popover', _this).each(function(index) {
				var _top = parseFloat($(this).next('.info-icon').css('top'));
				var _left = parseFloat($(this).next('.info-icon').css('left'));
				var _direction = $(this).data('direction');
				var _width = $(this).width();
				var _height = $(this).height();
				_popArr[index] = $(this).data('index', index);
				// $(this).next('.info-icon').show().css({
				// 	opacity: 1,
				// 	top: _top,
				// 	left: _left
				// });
				switch(_direction) {
					case 'top':
						$(this).css({
							top: _top - _height,
							left: _left - _width*.5 + 10
						});
						break;
					case 'left':
						$(this).css({
							top: _top - _height*.5 + 10,
							left: _left - _width - 2
						});
						break;
					case 'bottom':
						$(this).css({
							top: _top + 22,
							left: _left - _width*.5 + 10
						});
						break;
					case 'right':
						$(this).css({
							top: _top - _height*.5 + 10,
							left: _left + 22
						});
						break;
					default:
				}
			});
		}

		var _slideID, _currentPopIndex = 0;
	    var _currentPop = null;
		if (settings.slideshow) {
			function startSlideshow(){
				clearTimeout(_slideID);
				_slideID = setTimeout(function() {
					nextPop();
				}, settings.slideshowDelay);
			}

	 	    if(Modernizr.csstransitions){
				_popArr[_currentPopIndex].show().addClass('animatedelay ' + 'cardIn'+_popArr[_currentPopIndex].data('direction')).on('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function(event) {
					$(window).trigger('resize');
					startSlideshow();
				});
			}else{
				_popArr[_currentPopIndex].show().delay(500).animate({opacity: 1}, 300, function(){
					startSlideshow();
				});
			}
			_popArr[_currentPopIndex].data('isshow', true);
			_currentPop = _popArr[_currentPopIndex];

			function nextPop(){
				clearTimeout(_slideID);
				var _tmpCurrentPop = _popArr[_currentPopIndex];
				if(_tmpCurrentPop!=null){
					if(Modernizr.csstransitions){
						_tmpCurrentPop.removeClass('cardIn'+_tmpCurrentPop.data('direction')).addClass('cardOut'+_tmpCurrentPop.data('direction'));
					}else{
						_tmpCurrentPop.animate({
							opacity: 0},
							300, function() {
							$(this).hide();
						});
					}
					_tmpCurrentPop.data('isshow', false);

				}
				_currentPopIndex++;
				if(_currentPopIndex>_popArr.length - 1) {
					if(settings.loop){
						_currentPopIndex = 0;
					}else{
						return false;
					}
				}
				// start animation and slideshow if the content is no empty
				if(_popArr[_currentPopIndex].find('p').html()!=""){
					if(Modernizr.csstransitions){
						_popArr[_currentPopIndex].show().removeClass('animatedelay cardOut'+_popArr[_currentPopIndex].data('direction')).addClass('hotspotanimate ' + 'cardIn'+_popArr[_currentPopIndex].data('direction')).on('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function(event) {
							if(_this.data('slideshow')) startSlideshow();
						});
					}else{
						_popArr[_currentPopIndex].show().animate({opacity: 1}, 300, function(){
							if(_this.data('slideshow')) startSlideshow();
						});
					}
				}else{
					if(_this.data('slideshow')) startSlideshow();
				}

				_popArr[_currentPopIndex].data('isshow', true);
				_currentPop = _popArr[_currentPopIndex];

			}

		};

		$('.info-icon', _this).each(function(index) {
			if(settings.dropInEase){
				var _animateIndex = index > 15 ? index%15 : index;
				$(this).addClass('dropin' + _animateIndex + ' cq-dropInDown')
				var _label = $(this).next('.cq-hotspot-label');
				if(_label) _label.addClass('dropin' + _animateIndex + ' cq-dropInDown')
			}
			if(settings.triggerBy=="mouseover"){
				$(this).on('mouseover', function(event) {
					$(window).trigger('resize');
					clearTimeout(_popOutID);
					clearTimeout(_slideID);
					var _relatedPop = $(this).prev('.popover');
					if(_currentPop!=null && !_currentPop.is(_relatedPop)){
						if(settings.displayVideo){
							var _frame = _currentPop.find('iframe');
							if(_frame){
								_frame.attr('src', '');
							}

						}
						if(Modernizr.csstransitions){
							_currentPop.removeClass('cardIn'+_currentPop.data('direction')).addClass('cardOut'+_currentPop.data('direction'));
						}else{
							_currentPop.animate({
								opacity: 0 },
								300, function() {
									$(this).hide();
							});
						}
						_currentPop.data('isshow', false);
					}
					_currentPop = _relatedPop;
					_currentPopIndex = _currentPop.data('index');
					if(_currentPop.find('p').html()!=""){
						if(settings.displayVideo){
							var _frame = _currentPop.find('iframe');
							if(_frame){
								// _frame.data('videourl', _frame.attr('src'));
								_frame.attr('src', _frame.data('videourl'));
							}

						}
						if(Modernizr.csstransitions){
							_currentPop.show().removeClass('animatedelay cardOut'+_currentPop.data('direction')).addClass('hotspotanimate ' + 'cardIn'+_currentPop.data('direction'))
						}else{
							_currentPop.show().animate({opacity: 1}, 300);
						}

					}

					_currentPop.data('isshow', true);
				}).on('mouseleave', function(event) {
					// _currentPop = $('#'+$(this).data('target'));
					_currentPop = $(this).prev('.popover');
					if(_this.data('_allowOut')&&settings.autoHide){
						clearTimeout(_popOutID);
						_popOutID = setTimeout(function() {
							if(settings.displayVideo){
								var _frame = _currentPop.find('iframe');
								if(_frame){
									_frame.attr('src', '');
								}
							}
							if(Modernizr.csstransitions){
								_currentPop.removeClass('cardIn'+_currentPop.data('direction')).addClass('cardOut'+_currentPop.data('direction')).on('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function(event) {
									clearTimeout(_slideID);
									if(_this.data('slideshow')) startSlideshow();
								});
							}else{
								_currentPop.animate({opacity: 0}, 300, function(){
									$(this).hide();
									clearTimeout(_slideID);
									if(_this.data('slideshow')) startSlideshow();
								})
							}
							_currentPop.data('isshow', false);
						}, settings.delay);
					}
				}).on('click', function(event) {
					if($(this).data('link')!=""){
						window.open($(this).data('link'), $(this).data('target'));
					}
					event.preventDefault();
				});
			}else{
				$(this).on('click', function(event) {
					if($(this).data('link')!=""){
						window.open($(this).data('link'), $(this).data('target'));
					}
					event.preventDefault();
					clearTimeout(_slideID);
					$(window).trigger('resize');
					var _relatedPop = $(this).prev('.popover');
					if(_currentPop!=null && !_currentPop.is(_relatedPop)){
						if(settings.displayVideo){
							var _frame = _currentPop.find('iframe');
							if(_frame){
								_frame.attr('src', '');
							}

						}
						if(Modernizr.csstransitions){
							_currentPop.removeClass('cardIn'+_currentPop.data('direction')).addClass('cardOut'+_currentPop.data('direction'));
						}else{
							_currentPop.animate({
								opacity: 0
								},
								300, function() {
									$(this).hide();
							});
						}
						_currentPop.data('isshow', false);
					}
					_currentPop = _relatedPop;
					_currentPopIndex = _currentPop.data('index');

					if(_currentPop.data('isshow')){
						if(Modernizr.csstransitions){
							_currentPop.removeClass('cardIn'+_currentPop.data('direction')).addClass('cardOut'+_currentPop.data('direction'));
						}else{
							_currentPop.animate({
								opacity: 0},
								300, function() {
									$(this).hide();
							});
						}
						_currentPop.data('isshow', false);
					}else{

						if(settings.displayVideo){
							var _frame = _currentPop.find('iframe');
							if(_frame){
								// _frame.data('videourl', _frame.attr('src'));
								_frame.attr('src', _frame.data('videourl'));
							}

						}

						if(_currentPop.find('p').html()!=""){
							if(Modernizr.csstransitions){
								_currentPop.show().removeClass('animatedelay cardOut'+_currentPop.data('direction')).addClass('hotspotanimate ' + 'cardIn'+_currentPop.data('direction'))
							}else{
								_currentPop.show().animate({opacity: 1}, 300);
							}
						}
						_currentPop.data('isshow', true);
					}
				}).on('mouseleave', function(event) {
					if(_this.data('_allowOut')&&settings.autoHide){
						_currentPop = $(this).prev('.popover');
						clearTimeout(_popOutID);
						_popOutID = setTimeout(function() {
							if(settings.displayVideo){
								var _frame = _currentPop.find('iframe');
								if(_frame){
									_frame.attr('src', '');
								}
							}
							if(Modernizr.csstransitions){
								_currentPop.removeClass('cardIn'+_currentPop.data('direction')).addClass('cardOut'+_currentPop.data('direction')).on('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function(event) {
										clearTimeout(_slideID);
										if(_this.data('slideshow')) startSlideshow();
								});;
							}else{
								_currentPop.animate({
									opacity: 0},
									300, function() {
									$(this).hide();
								});
							}
							_currentPop.data('isshow', false);
						}, settings.delay);
					}
				});
			}

		});

		_this.on('click', '.popover-image', function(event) {
			event.preventDefault();
			if(settings.clickImageToClose) _this.hideCurrentPop();
		});

		_this.hideCurrentPop = function(){
			if(_currentPop!=null){
				if(settings.displayVideo){
					var _frame = _currentPop.find('iframe');
					if(_frame){
						// _frame.data('videourl', _frame.attr('src'));
						_frame.attr('src', '');
					}

				}

				if(Modernizr.csstransitions){
					_currentPop.removeClass('cardIn'+_currentPop.data('direction')).addClass('cardOut'+_currentPop.data('direction'));
				}else{
					_currentPop.animate({
						opacity: 0},
						300, function() {
						$(this).hide();
					});
				}
				_currentPop.data('isshow', false);
			}
		}

		_this.resetPopPos = _resetPopPos;

		// make it responsive
		$(window).on('resize', function(event) {
			var _imgW = $('.popover-image', _this).width();
			var _imgH = $('.popover-image', _this).height();
			$('.info-icon', _this).each(function() {
				var _t = $(this).data('top');
				var _l = $(this).data('left');
				$(this).css({
					top: Math.floor((_t)*_imgH/_containerH - 18*(_containerH-_imgH)/_containerH),
					left: Math.floor((_l)*_imgW/_containerW - 10*(_containerW-_imgW)/_containerW)
				});
				// var _label = $(this).next('.cq-hotspot-label');
				var _label = $(this).find('.cq-hotspot-label');
				if(_label){
					var _labelWidth = _label.width();
					// var _lt = _label.data('top');
					// var _ll = _label.data('left');
					if(_label.html()!=""){
						_label.show().css({
							'display': 'inline-block',
							'position': 'absolute',
							'visibility': 'visible',
							'opacity': 1
						});
					}else{
						_label.remove();
					}
				}


			});
			// $('.cq-hotspot-label', _this).each(function() {
				// var _t = $(this).data('top');
				// var _l = $(this).data('left');
				// var _labelWidth = $(this).width();
				// $(this).show().css({
				// 	// 'display': 'inline-block',
				// 	'display': 'inline',
				// 	'visibility': 'visible',
				// 	'opacity': 1,
				// 	top: _t*_imgH/_containerH + 24,
				// 	left: _l*_imgW/_containerW,
				// 	'margin-left': -_labelWidth*.5+10
				// });
			// });
			_resetPopPos();
		});

		$(window).trigger('resize');

		return this;

	};

})(jQuery);

jQuery(document).ready(function($) {
	jQuery('.hotspot-container').each(function() {
		jQuery(this).hotSpot({
	        slideshow : jQuery(this).data('slideshow'),
	        slideshowDelay : jQuery(this).data('slideshowdelay'),
	        triggerBy : jQuery(this).data('triggerby'),
            delay : jQuery(this).data('autohidedelay'),
	        displayVideo : jQuery(this).data('displayvideo'),
	        autoHide : jQuery(this).data('autohide'),
	        sticky: jQuery(this).data('sticky'),
			dropInEase: jQuery(this).data('dropinease'),
			customIcon: jQuery(this).data('customicon'),
	        clickImageToClose: jQuery(this).data('clickimageclose')
	    });
	});

});
