/***
    Author URI: http://codecanyon.net/user/sike?ref=sike
***/
// the hotspot mini file
!function(a){a.fn.hotSpot=function(b){function l(){a(".popover",d).each(function(b){var c=parseFloat(a(this).next(".info-icon").css("top")),d=parseFloat(a(this).next(".info-icon").css("left")),e=a(this).data("direction"),f=a(this).width(),h=a(this).height();switch(g[b]=a(this).data("index",b),e){case"top":a(this).css({top:c-h,left:d-.5*f+10});break;case"left":a(this).css({top:c-.5*h+10,left:d-f-2});break;case"bottom":a(this).css({top:c+22,left:d-.5*f+10});break;case"right":a(this).css({top:c-.5*h+10,left:d+22})}})}function p(){clearTimeout(m),m=setTimeout(function(){q()},c.slideshowDelay)}function q(){clearTimeout(m);var b=g[n];if(null!=b&&(Modernizr.csstransitions?b.removeClass("cardIn"+b.data("direction")).addClass("cardOut"+b.data("direction")):b.animate({opacity:0},300,function(){a(this).hide()}),b.data("isshow",!1)),n++,n>g.length-1){if(!c.loop)return!1;n=0}""!=g[n].find("p").html()?Modernizr.csstransitions?g[n].show().removeClass("animatedelay cardOut"+g[n].data("direction")).addClass("hotspotanimate cardIn"+g[n].data("direction")).on("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd",function(){d.data("slideshow")&&p()}):g[n].show().animate({opacity:1},300,function(){d.data("slideshow")&&p()}):d.data("slideshow")&&p(),g[n].data("isshow",!0),o=g[n]}var c={triggerBy:"click",delay:0,slideshow:!0,loop:!0,autoHide:!0,slideshowDelay:4e3,sticky:!0,dropInEase:!1,displayVideo:!0,customIcon:"",clickImageToClose:!0};b&&a.extend(c,b);var d=this,e=!0;d.data("_allowOut",e);var f,g=[];d.data("slideshow",c.slideshow);var h=[];a(".popover",d).each(function(b){var e=a(this).data("top"),i=a(this).data("left"),j=a(this).data("width");0!=j&&236!=j&&""!=j&&(a(this).css("width",j),a(this).find(".popover-content").css("width",j-28),a(this).find("h4.popover-title").css("width",j-27));var k=a(this).data("style"),l=a(this).data("direction");if(""!=k&&(a(this).addClass(k),a(this).find("h4.popover-title").addClass(k)),c.displayVideo){var n=a(this).find("iframe");n&&n.data("videourl",n.attr("src"))}var q=a(this).height();switch(g[b]=a(this).data("index",b),h[b]=a(".popover-content > p",a(this)).html(),""!=c.customIcon&&a(this).next(".cq-hotspot-custom-icon").css("background","url("+c.customIcon+") no-repeat"),a(this).next(".info-icon").show().css({top:e,left:i}).delay(500).animate({opacity:1},300),l){case"top":a(this).css({top:e-q,left:i-.5*j+10});break;case"left":a(this).css({top:e-.5*q+10,left:i-j-2});break;case"bottom":a(this).css({top:e+22,left:i-.5*j+10});break;case"right":a(this).css({top:e-.5*q+10,left:i+22})}c.sticky&&a(this).on("mouseover",function(){clearTimeout(f),clearTimeout(m),d.data("_allowOut",!1)}).on("mouseleave",function(){(d.data("slideshow")||c.autoHide&&o)&&(clearTimeout(f),f=setTimeout(function(){d.data("_allowOut",!0),Modernizr.csstransitions?o.removeClass("cardIn"+o.data("direction")).addClass("cardOut"+o.data("direction")).on("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd",function(){clearTimeout(m),d.data("slideshow")&&p()}):o.animate({opacity:0},300,function(){clearTimeout(m),d.data("slideshow")&&p()}),o.data("isshow",!1)},c.delay))})});var j,k,i=a(".popover-image",d)[0];a("<img/>").attr("src",a(i).attr("src")).load(function(){j=this.width,k=this.height,a(window).trigger("resize")});var m,n=0,o=null;return c.slideshow&&(Modernizr.csstransitions?g[n].show().addClass("animatedelay cardIn"+g[n].data("direction")).on("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd",function(){a(window).trigger("resize"),p()}):g[n].show().delay(500).animate({opacity:1},300,function(){p()}),g[n].data("isshow",!0),o=g[n]),a(".info-icon",d).each(function(b){if(c.dropInEase){var e=b>15?b%15:b;a(this).addClass("dropin"+e+" cq-dropInDown");var g=a(this).next(".cq-hotspot-label");g&&g.addClass("dropin"+e+" cq-dropInDown")}"mouseover"==c.triggerBy?a(this).on("mouseover",function(){a(window).trigger("resize"),clearTimeout(f),clearTimeout(m);var d=a(this).prev(".popover");if(null!=o&&!o.is(d)){if(c.displayVideo){var e=o.find("iframe");e&&e.attr("src","")}Modernizr.csstransitions?o.removeClass("cardIn"+o.data("direction")).addClass("cardOut"+o.data("direction")):o.animate({opacity:0},300,function(){a(this).hide()}),o.data("isshow",!1)}if(o=d,n=o.data("index"),""!=o.find("p").html()){if(c.displayVideo){var e=o.find("iframe");e&&e.attr("src",e.data("videourl"))}Modernizr.csstransitions?o.show().removeClass("animatedelay cardOut"+o.data("direction")).addClass("hotspotanimate cardIn"+o.data("direction")):o.show().animate({opacity:1},300)}o.data("isshow",!0)}).on("mouseleave",function(){o=a(this).prev(".popover"),d.data("_allowOut")&&c.autoHide&&(clearTimeout(f),f=setTimeout(function(){if(c.displayVideo){var b=o.find("iframe");b&&b.attr("src","")}Modernizr.csstransitions?o.removeClass("cardIn"+o.data("direction")).addClass("cardOut"+o.data("direction")).on("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd",function(){clearTimeout(m),d.data("slideshow")&&p()}):o.animate({opacity:0},300,function(){a(this).hide(),clearTimeout(m),d.data("slideshow")&&p()}),o.data("isshow",!1)},c.delay))}).on("click",function(b){""!=a(this).data("link")&&window.open(a(this).data("link"),a(this).data("target")),b.preventDefault()}):a(this).on("click",function(b){""!=a(this).data("link")&&window.open(a(this).data("link"),a(this).data("target")),b.preventDefault(),clearTimeout(m),a(window).trigger("resize");var d=a(this).prev(".popover");if(null!=o&&!o.is(d)){if(c.displayVideo){var e=o.find("iframe");e&&e.attr("src","")}Modernizr.csstransitions?o.removeClass("cardIn"+o.data("direction")).addClass("cardOut"+o.data("direction")):o.animate({opacity:0},300,function(){a(this).hide()}),o.data("isshow",!1)}if(o=d,n=o.data("index"),o.data("isshow"))Modernizr.csstransitions?o.removeClass("cardIn"+o.data("direction")).addClass("cardOut"+o.data("direction")):o.animate({opacity:0},300,function(){a(this).hide()}),o.data("isshow",!1);else{if(c.displayVideo){var e=o.find("iframe");e&&e.attr("src",e.data("videourl"))}""!=o.find("p").html()&&(Modernizr.csstransitions?o.show().removeClass("animatedelay cardOut"+o.data("direction")).addClass("hotspotanimate cardIn"+o.data("direction")):o.show().animate({opacity:1},300)),o.data("isshow",!0)}}).on("mouseleave",function(){d.data("_allowOut")&&c.autoHide&&(o=a(this).prev(".popover"),clearTimeout(f),f=setTimeout(function(){if(c.displayVideo){var b=o.find("iframe");b&&b.attr("src","")}Modernizr.csstransitions?o.removeClass("cardIn"+o.data("direction")).addClass("cardOut"+o.data("direction")).on("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd",function(){clearTimeout(m),d.data("slideshow")&&p()}):o.animate({opacity:0},300,function(){a(this).hide()}),o.data("isshow",!1)},c.delay))})}),d.on("click",".popover-image",function(a){a.preventDefault(),c.clickImageToClose&&d.hideCurrentPop()}),d.hideCurrentPop=function(){if(null!=o){if(c.displayVideo){var b=o.find("iframe");b&&b.attr("src","")}Modernizr.csstransitions?o.removeClass("cardIn"+o.data("direction")).addClass("cardOut"+o.data("direction")):o.animate({opacity:0},300,function(){a(this).hide()}),o.data("isshow",!1)}},d.resetPopPos=l,a(window).on("resize",function(){var c=a(".popover-image",d).width(),e=a(".popover-image",d).height();a(".info-icon",d).each(function(){var b=a(this).data("top"),d=a(this).data("left");a(this).css({top:b*e/k,left:d*c/j});var f=a(this).find(".cq-hotspot-label");f&&(f.width(),""!=f.html()?f.show().css({display:"inline-block",position:"absolute",visibility:"visible",opacity:1}):f.remove())}),l()}),a(window).trigger("resize"),this}}(jQuery);

jQuery(document).ready(function ($) {
    var media_frame, _currentInput;
    jQuery('.hotspot-admin-container').find('.upload_image').on('click', _upload);
    jQuery('.hotspot-setting-table').find('.upload_custom_icon').on('click', _uploadIcon);
    function _upload(event){
        if ( media_frame ) {
            media_frame.remove();
        }
        media_frame = wp.media.frames.media_frame = wp.media({
            className: 'media-frame media-frame',
            frame: 'select',
            multiple: false,
            title: 'Select a image for the HotSpot',
            library: {
                type: 'image'
            },
            button: {
                text:  'Use this image'
            }
        });

        _currentInput = jQuery(event.target).prev('input');
        media_frame.on('select', function(){
            var media_attachment = media_frame.state().get('selection').first().toJSON();
            _currentInput.val(media_attachment.url);
        });

        // Now that everything has been set, let's open up the frame.
        media_frame.open();
        return false;
    }

    function _uploadIcon(event){
        if ( media_frame ) {
            media_frame.remove();
        }
        media_frame = wp.media.frames.media_frame = wp.media({
            className: 'media-frame media-frame',
            frame: 'select',
            multiple: false,
            title: 'Select a image for the icon',
            library: {
                type: 'image'
            },
            button: {
                text:  'Use this icon'
            }
        });

        _currentInput = jQuery(event.target).prev('input');
        media_frame.on('select', function(){
            var media_attachment = media_frame.state().get('selection').first().toJSON();
            _currentInput.val(media_attachment.url);
        });

        // Now that everything has been set, let's open up the frame.
        media_frame.open();
        return false;
    }




    var _firstRow = $('.hotspot-admin-container').first().find('.image-item').first();

    jQuery('.hotspot-admin-container').each(function(index) {
        var _firstTextItem = $(this).find('.popover-item').first();
        _firstTextItem.find('.remove-popover').first().hide();
    });

    function _enableRemove(){
        jQuery('.remove-popover').on('click', function() {
            $(this).parent('div').animate({
                opacity: 0},
                300, function() {
                    $(this).remove();
                    _resetSlideNames();
            });
            return false;
        });
    }
    _enableRemove();


    var _hotspot;
    jQuery('.hotspot-container').each(function() {
        _hotspot = jQuery(this).hotSpot({
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

    var _inputTopArr = [];
    var _inputLeftArr = [];

    // $('.cq-hotspot-colorinput').wpColorPicker();

    function _updateInputValue(n, value, str){
        if(str=="top"){
            jQuery('.hotspot-top').each(function(index) {
                // if(index==n) $(this).val(value);
                if(index==n) $(this).attr("value", value);;
            });
        }else{
            jQuery('.hotspot-left').each(function(index) {
                // if(index==n) $(this).val(value);
                if(index==n) $(this).attr("value", value);;
                // _inputLeftArr[index] = $(this).data('index');
            });
        }

    }

    function _enableDrag(){
        // todo: drag the icon to add HotSpot
        // jQuery('.popover-icon').each(function(index) {
        //     var _cloneIcon = $(this).clone(true);
        //     var _container = $('#available-icons');
        //     var _iconCon = $(this).parent();
        //     $(this).draggable({
        //         revert: true,
        //         // containment: $('.hotspot-container'),
        //         start: function(){
        //             // _container.append(_cloneBtn);
        //             // $(this).appendTo($('.hotspot-container'));
        //             // _iconCon.append(_cloneIcon);
        //         },
        //         drag: function(){

        //         },
        //         stop: function(){
        //            var _t = parseFloat($(this).css('top'));
        //            var _l = parseFloat($(this).css('left'));
        //            jQuery('.add-popover').trigger('click');
        //            jQuery('.hotspot-container').append('<a href="#" class="info-icon icon2" data-top="'+_t+'" data-left="'+_l+'"></a>')
        //            _enableDrag();
        //            // jQuery('input.metabox_submit').trigger('click');
        //         }
        //     })
        // });


        jQuery('.info-icon').each(function(index) {
            $(this).data('index', index);
            var _label = $(this).next('span');
            $(this).draggable({
                // revert: false,
                containment: 'parent',
                start: function(){
                    _hotspot.hideCurrentPop();
                    if(_label)_label.css('visibility', 'hidden');
                },
                drag: function(){
                    var _t = parseFloat($(this).css('top'));
                    var _l = parseFloat($(this).css('left'));
                    var _index = $(this).data('index');
                    _updateInputValue($(this).data('index'), _t, 'top');
                    _updateInputValue($(this).data('index'), _l, 'left');
                    $(this).data('top', _t);
                    $(this).data('left', _l);
                    $(this).css({
                        position: 'absolute',
                        top: _t,
                        left: _l
                    });
                    if(_label){
                        var _labelWidth = _label.width();
                        _label.css('visibility', 'hidden');
                        // _label.hide();
                        _label.data('top', _t);
                        _label.data('left', _l);
                        _label.css({
                            top: _t + 24,
                            left: _l,
                            'margin-left': -_labelWidth*.5+10
                        });

                    }

                },
                stop: function(){
                    _hotspot.resetPopPos();
                    $(this).trigger('mouseover');
                }
            });
        });
    }
    _enableDrag();



    function _resetSlideNames(){
        jQuery('.hotspot-admin-container').each(function(index1) {
            $(this).find('.popover-num').each(function(index4) {
                $(this).html(index4+1);
            });
            $(this).find('.popover-item').each(function(index2) {
                $(this).find('input').each(function(index3) {
                    var _name = $(this).data('name')+'['+index1+'][]';
                    $(this).attr('name', _name);
                })
                $(this).find('textarea').each(function(index3) {
                    var _name = $(this).data('name')+'['+index1+'][]';
                    $(this).attr('name', _name);
                })
                $(this).find('select').each(function(index3) {
                    var _name = $(this).data('name')+'['+index1+'][]';
                    $(this).attr('name', _name);
                })

            });
        });
    }

    function _enableAdd(){
        jQuery('.add-popover').on('click', function() {
            var _textItem = $(this).prev('.popover-item').clone(true);
            _textItem.find('.remove-popover').show();
            _textItem.find('textarea').val('');
            _textItem.find('.popover-title').val('');
            _textItem.find('.tiny-text').val('');
            _textItem.find('.biggest-text').val('');
            // _textItem.find('input').val('');
            _textItem.insertAfter($(this).prev('.popover-item'));
            _enableRemove();
            _resetSlideNames();
            return false;
        });

    }
    _enableAdd();
    _resetSlideNames();

});
