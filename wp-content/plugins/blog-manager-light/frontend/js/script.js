var $ =  jQuery.noConflict(); //Wordpress by default uses jQuery instead of $

$(document).ready(function(){

  //Hover styles
  otw_hover_styles();

  //Socail shares
  otw_social_shares();

  // Responsive Videos
  otw_responsive_videos();

  // Blog image slides
  $('.otw_blog_manager-format-gallery').each( function( i, v ) {
    $this = $(this);

    if( $this.find('.slides').length > 0 ) {
      $this.flexslider({
        namespace: "otw-flex-",
        animation: "slide", // slide or fade
        animationLoop: true,
      });
    }
  });

  // Load More Other Items
  $(document).on('click', '.js-otw_blog_manager-load-more a', function(e) {
    e.preventDefault();
    $this = $(this);

    if( !$this.parent().hasClass('otw_blog_manager-load-more-newspapper') ) {

      $this.html('<div class="preloader">Loading posts...</div>');

      var url = $this.parent('.otw_blog_manager-load-more').parent().find('.otw_blog_manager-pagination.hide a').attr('href');

      if(url === 'undefined' || url === '#' || url === ''){
        $this.text($this.attr('data-empty'));
        return false;
      }

      $this.prop('disabled', true);

      $container = $this.parent().parent().parent().parent().parent().parent().parent().parent().find('.otw_blog_manager-blog-item-holder').parent();
      $mainContainer = $this.parent().parent().parent().parent().parent();

      $.get(url, function(data) {
        if( data.length > 1 ) {
          $pagination = $(data).find('.js-pagination_container').parent().parent();

          $('.js-pagination_container').parent().parent().remove();

          $newElements = $(data).find('.otw_blog_manager-blog-item-holder');

          $container.append( $newElements ); 

          $mainContainer.append( $pagination );
          
          otw_hover_styles();
          otw_responsive_videos();
          otw_social_shares();
          otw_enable_sliders();
          horizontal_layout('.otw_blog_manager-horizontal-layout-items');
        } else {
          $this.html('No More Posts Found').animate({ opacity: 1 }, 2000, function () {
            $this.fadeOut('fast');
          });
        }
      });

    }

  });

  // Load More Widgets
  $(document).on('click', '.js-widget-otw_blog_manager-load-more a', function(e) {

    e.preventDefault();
    $this = $(this);

    var url = $this.parent('.js-widget-otw_blog_manager-load-more').parent().find('.otw_blog_manager-pagination.hide a').attr('href');

    if(url === 'undefined' || url === '#' || url === ''){
      $this.text($this.attr('data-empty'));
      return false;
    }
    $this.html('<div class="preloader">Loading posts...</div>');
    $this.prop('disabled', true);

    $container = $this.parent().parent().parent().parent().parent().parent().find('.js-widget-list');    

    $.get(url, function(data) {
      if( data.length > 1 ) {
        $pagination = $(data).find('.js-otw_blog_manager-widget-pagination-holder').parent().parent();

        // Remove Load More
        $this.parent().parent().remove();

        // Add new Load More BTN
        $('.js-otw_blog_manager-widget-pagination-holder').html( $(data).find('.js-widget-pagination_container') );


        $container.append( $(data).find('.js-widget-list').children() );
        otw_hover_styles();
        otw_responsive_videos();
        otw_social_shares();
        otw_enable_sliders();
      } else {
        $this.html('No More Posts Found').animate({ opacity: 1 }, 2000, function () {
          $this.fadeOut('fast');
        });
      }
    });

  });
 
  //Load More NewsPapper
  $(document).on("click", '.otw_blog_manager-load-more-newspapper a',function(e){

    e.preventDefault();

    $this = $(this);

    var url = $this.attr('href');

    if(url === 'undefined' || url === '#' || url === ''){
      $this.text($this.attr('data-empty'));
      return false;
    }
    $this.html('<div class="preloader">Loading posts...</div>');
    $container = $(this).parent().parent().parent().parent().parent().find('.otw_blog_manager-blog-newspaper')

    $.get(url, function(data) {
      if( data.length > 1 ) {
        var $newElements = $( $(data).find('.otw_blog_manager-blog-item-holder').html() );

        //slider fixing
        $newElements.find('.otw_blog_manager-blog-media-wrapper.otw_blog_manager-format-gallery').each(function(){
          var image = new Image();
          image.src = $(this).find('.slides li img').attr("src");
          $(this).css({'max-width': image.naturalWidth + 'px' });
          $(this).find('.slides li').css({'max-width': image.naturalWidth + 'px' });
        });

        if($newElements.find('.otw_blog_manager-format-gallery .slides').length > 0 ) {
          $newElements.find('.otw_blog_manager-format-gallery').flexslider({
            namespace: "otw-flex-",
            animation: "slide"
          });
        }

        $paginationElement = $(data).find('.js-pagination_container').parent().parent();

        if($this.data('isotope') !== false){
          
          $container.append( $newElements ).isotope( 'appended', $newElements, function(){
            $(this).isotope('reLayout');
          });
        } else {
          $newElements.appendTo( $this.parent('.otw_blog_manager-load-more').parent().find('.otw_blog_manager-blog-item-holder') ).each(function(){
            if($this.data('layout') === 'horizontal'){
              horizontal_layout('.otw_blog_manager-horizontal-layout-items');
            }
          });
        }

        otw_social_shares();
        otw_enable_sliders();

        //next page
        $('.js-pagination_container').parent().parent().remove();
        $container.parent().append( $paginationElement );
        otw_calculate_columns('.otw_blog_manager-mosaic-layout');
      } else {
        $this.html('No More Posts Found').animate({ opacity: 1 }, 2000, function () {
          $this.fadeOut('fast');
        });
      }
    });

    
  });

  //Infinite Scroll for Grid Layout 
  try {
  
    $('.otw_blog_manager-infinite-pagination-holder').infinitescroll({
      navSelector  : '.otw_blog_manager-pagination',    // selector for the paged navigation 
      nextSelector : '.otw_blog_manager-pagination a',  // selector for the NEXT link (to page 2)
      itemSelector : '.otw_blog_manager-blog-item-holder',     // selector for all items you'll retrieve
      // debug: true,
      loading: {
          finishedMsg: 'No More Posts Found',
          msgText: 'Loading posts...',
          img: 'http://i.imgur.com/o4Qsgvx.gif'
        }
      },
      //call horizontal layout as a callback
      function( newElements ) {
        otw_hover_styles();
        otw_responsive_videos();
        otw_social_shares();
        otw_enable_sliders();
      }
    );
  } catch(err) { }

  //Infinite Scroll for Newspaper Layout
  try {
    $('.otw_blog_manager-infinite-scroll').infinitescroll({
      navSelector  : '.otw_blog_manager-pagination',    // selector for the paged navigation 
      nextSelector : '.otw_blog_manager-pagination a',  // selector for the NEXT link (to page 2)
      itemSelector : '.otw_blog_manager-blog-newspaper-item',     // selector for all items you'll retrieve
      loading: {
          finishedMsg: 'No More Posts Found',
          msgText: 'Loading posts...',
          img: 'http://i.imgur.com/o4Qsgvx.gif'
        }
      },
      //call Isotope as a callback
      function( newElements ) {
        var $newElements = $(newElements);
        //slider fixing
        $newElements.find('.otw_blog_manager-blog-media-wrapper.otw_blog_manager-format-gallery').each(function(){
          var image = new Image();
          image.src = $(this).find('.slides li img').attr("src");
          // $(this).css({'max-width': image.naturalWidth + 'px' });
          // $(this).find('.slides li').css({'max-width': image.naturalWidth + 'px' });
        });

        if($newElements.find('.otw_blog_manager-format-gallery .slides').length > 0 ) {
          $newElements.find('.otw_blog_manager-format-gallery').flexslider({
            namespace: "otw-flex-",
            animation: "slide"
          });
        }

        $('.otw_blog_manager-infinite-scroll').isotope( 'appended', $newElements, function(){
          otw_hover_styles();
          otw_responsive_videos();
          otw_social_shares();
          otw_enable_sliders();
          otw_calculate_columns('.otw_blog_manager-mosaic-layout');
          $(this).isotope('reLayout');
          
        });
      }
    );
  } catch(err) {

  }

  //Infinite Scroll for Horizontal Layout
  try {
    $('.otw_blog_manager-horizontal-layout-items-infinite-scroll').infinitescroll({
      navSelector  : '.otw_blog_manager-pagination',    // selector for the paged navigation 
      nextSelector : '.otw_blog_manager-pagination a',  // selector for the NEXT link (to page 2)
      itemSelector : '.otw_blog_manager-horizontal-item',     // selector for all items you'll retrieve
      loading: {
          finishedMsg: 'No More Posts Found',
          msgText: 'Loading posts...',
          img: 'http://i.imgur.com/o4Qsgvx.gif'
        }
      },

      //call horizontal layout as a callback
      function( newElements ) {
        $newElements = $(newElements).find('.otw_blog_manager-horizontal-item');
        otw_social_shares();
        otw_enable_sliders();
        horizontal_layout('.otw_blog_manager-horizontal-layout-items');
      }
    );
  } catch(err) {

  }

  // Timeline
  try {
    $('.otw_blog_manager-blog-timeline').infinitescroll({
      navSelector  : '.otw_blog_manager-pagination',    // selector for the paged navigation 
      nextSelector : '.otw_blog_manager-pagination a',  // selector for the NEXT link (to page 2)
      itemSelector : '.otw_blog_manager-blog-timeline-item',     // selector for all items you'll retrieve
      loading: {
          finishedMsg: 'No More Posts Found',
          msgText: 'Loading posts...',
          img: 'http://i.imgur.com/o4Qsgvx.gif'
        }
      },
      //callback
      function( newElements ) {
        var $newElements = $(newElements);

        //slider fixing
        $newElements.find('.otw_blog_manager-blog-media-wrapper.otw_blog_manager-format-gallery').each(function(){
          var image = new Image();
          image.src = $(this).find('.slides li img').attr("src");
          // $(this).css({'max-width': image.naturalWidth + 'px' });
          // $(this).find('.slides li').css({'max-width': image.naturalWidth + 'px' });
        });

        if($newElements.find('.otw_blog_manager-format-gallery .slides').length > 0 ) {
          $newElements.find('.otw_blog_manager-format-gallery').flexslider({
            namespace: "otw-flex-",
            animation: "slide"
          });
        }

        //hover styles
        $newElements.each(function(){
          otw_hover_styles();
          otw_responsive_videos();
          otw_social_shares();
          otw_enable_sliders();
        });

        $('.otw_blog_manager-blog-timeline').append($newElements);

        $('#infscr-loading').remove().insertAfter( $('.otw_blog_manager-blog-timeline .otw_blog_manager-blog-timeline-item:last') );

        timeline_layout_fixer();
      }
    );
  } catch(err) {

  }
  
  //Comment Form
  $('.otw_blog_manager-btn-reply').on('click', function() {
    if (!$(this).hasClass('otw_blog_manager-cancel-reply')) {

      var comForm = $('.otw_blog_manager-comment-form').clone();
      $('.otw_blog_manager-comment-form').remove();

      $('.otw_blog_manager-btn-reply').removeClass('otw_blog_manager-cancel-reply').html('<b>reply</b>');
      $(this).addClass('otw_blog_manager-cancel-reply').html('<b>cancel</b>');
      $(this).parent().parent().append(comForm);

      $(this).parent().parent().children('.otw_blog_manager-comment-form')
        .focus(function() {
          $(this).siblings('i').addClass('otw_blog_manager-focused');
        })
        .focusout(function() {
          $(this).siblings('i').removeClass('otw_blog_manager-focused');
        });
    }
  });

  $(document)
    .on('click', '.otw_blog_manager-cancel-reply', function() {
      var comForm = $(this).parent().siblings('.otw_blog_manager-comment-form').clone();
      $(this).parent().siblings('.otw_blog_manager-comment-form').remove();

      $(this).removeClass('otw_blog_manager-cancel-reply').html('<b>reply</b>');
      $('.otw_blog_manager-single-post').append(comForm);
    })
    .on('click', '.otw_blog_manager-cancel-reply2', function(event) {
      event.preventDefault();

      var comForm = $(this).parent().parent().clone();
      $(this).parent().parent().remove();

      $('.otw_blog_manager-cancel-reply').removeClass('otw_blog_manager-cancel-reply').html('<b>reply</b>');
      $('.otw_blog_manager-single-post').append(comForm);
    })
    .on('focus', 'input, textarea', function() {
      $(this).siblings('i').addClass('otw_blog_manager-focused');
    })
    .on('focusout', 'input, textarea', function() {
      $(this).siblings('i').removeClass('otw_blog_manager-focused');
    });

  //Slider
  $('.otw_blog_manager-slider').each(function(){
    var $this = $(this);
  
    var flexNav = true; // Show Navigation
    var flexAuto = true;  // Auto play 

    if( $this.data('nav') === 0 ) {
      flexNav = false;
    }

    if( $this.data('auto-slide') === 0 ) {
      flexAuto = false;
    }

    var slider_animation = $(this).data('animation');

    if($this.find('.slides').length > 0 ) {
      
      if( $this.hasClass('otw_blog_manager-carousel') ){
        var item_margin = $this.data('item-margin');
        var item_per_page = $this.data('item-per-page');
        var item_width = ( ($this.width() - ( item_margin * (item_per_page - 1) )) / item_per_page );

        var prev_text = "";
        var next_text = "";

        if($this.data('type') == "widget"){
          prev_text = '<i class="icon-angle-left"></i>';
          next_text = '<i class="icon-angle-right"></i>';
        }

        $this.flexslider({
          namespace: "otw-flex-",
          animation: slider_animation,
          animationLoop: false,
          prevText: prev_text,
          nextText: next_text,
          itemWidth: item_width,
          itemMargin: item_margin,
          controlNav: flexNav,
          slideshow: flexAuto
        });
      } else {

        $this.flexslider({
          namespace: "otw-flex-",
          controlNav: flexNav,
          animation: slider_animation,
          slideshow: flexAuto
        });
      }
    }
    // Hide bullets if paginations is not enabled
    if( $this.data('nav') === 0 ) {
      $this.find( $('.otw-flex-control-nav') ).hide();
    }
  });

  //Timeline Layout
  $('.otw_blog_manager-blog-timeline.with-heading').before('<div class="otw_blog_manager-blog-timeline-heading"></div>');
  timeline_layout_fixer();

  /* Input & Textarea Placeholder */
  $('input[type="text"], textarea').each(function(){
    $(this).attr({'data-value': $(this).attr('placeholder')});
    $(this).removeAttr('placeholder');
    $(this).attr({'value': $(this).attr('data-value')});
  });

  $('input[type="text"], textarea').focus(function(){
    $(this).removeClass('error');
    if($(this).val().toLowerCase() === $(this).attr('data-value').toLowerCase()){
      $(this).val('');
    } 
  }).blur( function(){ 
    if($(this).val() === ''){
      $(this).val($(this).attr('data-value'));
    }
  });

  //IE8 hover fixer
  $('.hover-style-14-desaturate a, .hover-style-16-orton a').on('mouseenter', function(){
    $(this).find('.otw_blog_manager-hover-img').css({'opacity': '0'});
  }).on('mouseleave', function(){
    $(this).find('.otw_blog_manager-hover-img').css({'opacity': '1'});
  });

  $('.hover-style-15-blur a, .hover-style-17-glow a').on('mouseenter', function(){
    $(this).find('.otw_blog_manager-hover-img').css({'opacity': '1'});
  }).on('mouseleave', function(){
    $(this).find('.otw_blog_manager-hover-img').css({'opacity': '0'});
  });
});

var $container = $('.otw_blog_manager-blog-newspaper');

//Blog Newspaper Filter

$(window).on('load', function() {
  // Isotope
  try {
    
    otw_calculate_columns('.otw_blog_manager-mosaic-layout');

    $container.isotope({
      itemSelector: '.otw_blog_manager-blog-newspaper-item',
      layoutMode: 'masonry',
      getSortData: {
        date: function( $elem ) {
          return parseInt(String($elem.find('.otw_blog_manager-blog-date a').attr('data-date')).replace(/-/g, ""));
        },
        alphabetical: function( $elem ) {
          return $elem.find('.otw_blog_manager-blog-title a').text();
        }
      },
      onLayout: function( $elem, instance ) {

        if( $container.find('.otw_blog_manager-mosaic-layout').length !== 0 ) {
          otw_calculate_columns('.otw_blog_manager-mosaic-layout');
        }
      }
    });

    var $optionSets_filter = $('.option-set.otw_blog_manager-blog-filter'),
        $optionLinks_filter = $optionSets_filter.find('a');

    $optionLinks_filter.click(function(e) {
      e.preventDefault();

      var $this = $(this);


      if ($this.hasClass('selected')) {
        return false;
      }

      var $optionSet = $this.parents('.option-set');
      $optionSet.find('.selected').removeClass('selected');
      $this.addClass('selected');

      var selector = $(this).data('filter');

      $(this).parents('.otw_blog_manager-blog-newspaper-filter').parent().parent().parent().find($container).isotope({filter: selector});
    });
  } catch(err) {

  }

  //Blog Sorting
  var $optionSets_sort = $('.option-set.otw_blog_manager-blog-sort'),
      $optionLinks_sort = $optionSets_sort.find('a');

  $optionLinks_sort.click(function(e){
    e.preventDefault();

    var $this = $(this);

    if ($this.hasClass('selected')) {
      return false;
    }

    var $optionSet = $this.parents('.option-set');
    $optionSet.find('.selected').removeClass('selected');
    $this.addClass('selected');

    var value = $this.attr('data-option-value');
    $(this).parents('.otw_blog_manager-blog-newspaper-sort').parent().parent().parent().find($container).isotope({ sortBy : value });
  });

  //Slider width fixing
  $('.otw_blog_manager-blog-media-wrapper.otw_blog_manager-format-gallery.slider').each(function(){
    var image = new Image();
    image.src = $(this).find('.slides li img').attr("src");
    $(this).css({'max-width': image.naturalWidth + 'px' });
    $(this).find('.slides li').css({'max-width': image.naturalWidth + 'px' });
  });

  //horizontal layout
  horizontal_layout('.otw_blog_manager-horizontal-layout-items');
});

//Hover styles
function otw_hover_styles(){

  $('.hover-style-1-full > a, .hover-style-2-shadowin > a, .hover-style-3-border > a, .hover-style-7-shadowout > a').each(function(){
    if( $(this).find('span.theHoverBorder').length == 0 ){
      $(this).append('<span class="theHoverBorder"></span>');
    }
  });

  $('.hover-style-4-slidetop > a, .hover-style-5-slideright > a, .hover-style-8-slidedown > a, .hover-style-9-slideleft > a').each(function(){
    if( $(this).find('span.theHoverBorder').length == 0 ){
      var icon = $(this).parents('.otw_blog_manager-blog-media-wrapper').attr('data-icon');
      $(this).append('<span class="theHoverBorder"><i class="'+ icon +'"></i></span>');
    }
  });

  //Special effects
  $('img', '.hover-style-14-desaturate').each(function(){
    
    $(this).clone().addClass("otw_blog_manager-hover-img").insertAfter( $(this) ).load(function(){
      Pixastic.process(this, "desaturate");
    });

  });

  $('img', '.hover-style-15-blur').each(function(){
    // if( $(this).parent().hasClass('otw-media-container') )
    $(this).clone().addClass("otw_blog_manager-hover-img").insertAfter($(this)).load(function(){
      Pixastic.process(this, "blurfast", {amount: 0.3});
    });
  });

  $('img', '.hover-style-16-orton').each(function(){
    $(this).clone().addClass("otw_blog_manager-hover-img").insertAfter($(this)).load(function(){
      Pixastic.process(this, "blurfast", {amount: 0.05});
    });
  });

  $('img', '.hover-style-17-glow').each(function(){
    $(this).clone().addClass("otw_blog_manager-hover-img").insertAfter($(this)).load(function(){
      Pixastic.process(this, "glow", {amount: 0.3, radius: 0.2});
    });
  });

  //IE8 hover fixer
  $('.hover-style-15-blur a .otw_blog_manager-hover-img, .hover-style-17-glow a .otw_blog_manager-hover-img').css({'opacity': '0'});
  $('.hover-style-14-desaturate a .otw_blog_manager-hover-img, .hover-style-16-orton a .otw_blog_manager-hover-img').css({'opacity': '1'});

  //IE8 frameborder fixer
  $('.otw_blog_manager-format-video iframe, .otw_blog_manager-format-audio iframe').each(function(){
    $(this).attr({'frameBorder': 'no'});
  });
}

function timeline_layout_fixer(){
  $('.otw_blog_manager-blog-timeline .otw_blog_manager-blog-timeline-item').removeClass('odd').removeClass('even');
  $('.otw_blog_manager-blog-timeline .otw_blog_manager-blog-timeline-item:nth-child(2n-1)').addClass('odd');
  $('.otw_blog_manager-blog-timeline .otw_blog_manager-blog-timeline-item:nth-child(2n)').addClass('even');
}

(function($,sr){
  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
    var timeout;

    return function debounced () {
      var obj = this, args = arguments;
      function delayed () {
        if (!execAsap)
          func.apply(obj, args);
        timeout = null;
      };

      if (timeout)
        clearTimeout(timeout);
      else if (execAsap)
        func.apply(obj, args);

      timeout = setTimeout(delayed, threshold || 100);
    };
  }
  // smartresize 
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };
})(jQuery,'smartresize');

//Window resize event
$(window).smartresize(function(){  
  try {
    otw_calculate_columns('.otw_blog_manager-mosaic-layout');
  } catch(err) { }

  try {
    $container.isotope("reLayout");
  } catch(err) { }

  try {
    horizontal_layout('.otw_blog_manager-horizontal-layout-items');
  } catch(err) { }
});

function otw_enable_sliders () {
  $('.otw_blog_manager-format-gallery').each( function( i, v ) {
    $this = $(this);
    
    if( $this.find('.slides').length > 0 ) {
      $this.flexslider({
        namespace: "otw-flex-",
        animation: "slide", // slide or fade
        animationLoop: true
      });
    }
  });
}

//Masonry layout
function otw_calculate_columns(container) {
  
  $(container).each(function(){

    var $this = $(this),
      containerWidth = $this.width(),
      minCol = Math.floor(containerWidth / 12);
      
    if (minCol*3 >= 200) {
      $('> .otw_blog_manager-iso-item', $this).each(function() {
        
        var $this = $(this);
        if ($this.hasClass('otw_blog_manager-1-4')) {
          $this.css('width', minCol*3);
        } else if ($this.hasClass('otw_blog_manager-2-4') || $this.hasClass('otw_blog_manager-1-2')) {
          $this.css('width', minCol*6);
        } else if ($this.hasClass('otw_blog_manager-1-3')) {
          $this.css('width', minCol*4);
        } else if ($this.hasClass('otw_blog_manager-2-3')) {
          $this.css('width', minCol*8);
        }
      });

    } else if ( minCol*3 < 200 && minCol*3 > 150) {
      $('> .otw_blog_manager-iso-item', $this).each(function() {
        
        var $this = $(this);
        if ($this.hasClass('otw_blog_manager-1-4')) {
          $this.css('width', minCol*6);
        } else if ($this.hasClass('otw_blog_manager-2-4') || $this.hasClass('otw_blog_manager-1-2')) {
          $this.css('width', minCol*12);
        } else if ($this.hasClass('otw_blog_manager-1-3')) {
          $this.css('width', minCol*6);
        } else if ($this.hasClass('otw_blog_manager-2-3')) {
          $this.css('width', minCol*12);
        }
      });

    }  else if ( minCol*3 <= 150) {
      $('> .otw_blog_manager-iso-item', $this).each(function() {
        $(this).css('width', minCol*12);
      });
    }

    $('> .otw_blog_manager-iso-item', $this).each(function() {

      if( ($(this).hasClass('otw_blog_manager-1-2') || $(this).hasClass('otw_blog_manager-2-3')) && $(this).hasClass('height1')){
        $(this).css('height', $(this).outerWidth()/2);
        // $(this).css('width', '-=1'); // Hack for spacing sincer margin-right: -1px is ignored
      } else if($(this).hasClass('height2')){
        $(this).css('height', $(this).outerWidth()*2);
      } else {
        $(this).css('height', $(this).outerWidth());
      }

      // console.log ( $(this) );
      
      $imgWidth = $(this).find('.otw_blog_manager-blog-media-wrapper').find('img').width();
      $imgHeight = $(this).find('.otw_blog_manager-blog-media-wrapper').find('img').height();
      console.log ( $(this).width(), $(this).height() );
      console.log ( $imgWidth, $imgHeight );
      $(this).find('.otw_blog_manager-blog-media-wrapper').css({'width': $(this).width(), 'height': $(this).height() });

      // if( $imgHeight > $(this).height() ) {
      //   $heightDif = $imgHeight - $(this).height();
      //   $negativeMove = $heightDif / 2;
      //   console.log( $negativeMove );
      //   $(this).find('.otw_blog_manager-blog-media-wrapper').find('img').css({'margin-top': -$negativeMove});
      // }

    });
  });

}

function horizontal_layout(container){

  $(container).each(function(){
    
    $(this).css({'opacity': '0'});

    var $this = $(this),
      container_width = $(document).find('.otw_blog_manager-horizontal-layout-wrapper').width(),
      row = 1,
      item_margin = $this.find('.otw_blog_manager-blog-item-holder').data('item-margin'),
      cache_width = 0,
      height_items = [];

    $this.find('.otw_blog_manager-blog-item-holder').children('.otw_blog_manager-horizontal-item').each(function(){

      if( $(this).attr('data-original-width') === undefined ){
        var $img = $(this).find('.otw_blog_manager-blog-media-wrapper img');

        $(this).attr({'data-original-width': $img.attr('width')});
        $(this).attr({'data-original-height': $img.attr('height')});

        //remove image size
        $img.attr({'width': ''});
        $img.attr({'height': ''});

      }

      $(this).css({'margin-right': ''});

      cache_width += ( $(this).data('original-width') + item_margin );


      $(this).attr({'data-row': row});

      if( cache_width >= container_width ){
        //new height = original height / original width x new width
        height_items.push( Math.floor($(this).data('original-height') / (cache_width - item_margin ) * container_width) );

        $(this).css({'margin-right': '-5px'});

        cache_width = 0;
        row += 1;
      }
    });


    for (var i = 0; i < height_items.length; i++) {
      cache_width = 0;
      $this.find('.otw_blog_manager-blog-item-holder').children('.otw_blog_manager-horizontal-item[data-row="'+ (i + 1) +'"]').each(function($itemIndex){
        //new width = original wdith / original height x new height
        var new_width = Math.ceil( $(this).data('original-width') / $(this).data('original-height') * height_items[i] );

        cache_width += (new_width+4);
        
        if( cache_width >= container_width ) {
          new_width -= ( cache_width - container_width );
        }

        $(this).find('.otw_blog_manager-blog-media-wrapper').css( {'width': new_width + 'px', 'height': parseInt(height_items[i]) });

      });
    }

    if( $this.find('.otw_blog_manager-blog-item-holder').children('.otw_blog_manager-horizontal-item[data-row="'+ row +'"]').length > 0 ){
      $this.find('.otw_blog_manager-blog-item-holder').children('.otw_blog_manager-horizontal-item[data-row="'+ row +'"]').each(function(){
        $(this).find('.otw_blog_manager-blog-media-wrapper').css({'width': $(this).data('original-width') + 'px', 'height': $(this).data('original-height') });
      });

      $this.find('.otw_blog_manager-blog-item-holder').children('.otw_blog_manager-horizontal-item[data-row="'+ row +'"]:last-child').css({'margin-right': '0px'});
    }

    $this.css({'opacity': '1'});
  });
}

//Social shares
function otw_social_shares(){  

  $('.otw_blog_manager-social-share-buttons-wrapper').each(function(){

    var $this = $(this);
        title = $(this).data('title'),
        description = $(this).data('description'),
        image = $(this).data('image'),
        postUrl = $(this).data('url');

    $.ajax({
      type: 'POST',
      url: socialShareURL,
      dataType: 'json',
      cache: false,
      data: { url: postUrl },
      success: function(data) {
        if(data.info !== 'error'){
          $this.find('.otw_blog-manager-social-share').each(function(){
            if($(this).hasClass('otw-facebook')){
              $(this).append('<span class="data-shares">'+ data.facebook +'</span>');
              // $(this).attr({'href': 'http://www.facebook.com/sharer.php?u='+ postUrl});
            } else if($(this).hasClass('otw-twitter')){
              $(this).append('<span class="data-shares">'+ data.twitter +'</span>');
              // $(this).attr({'href': 'https://twitter.com/intent/tweet?source=tweetbutton&text='+ escape(title) +'&url='+ encodeURIComponent(postUrl)});
            } else if($(this).hasClass('otw-google_plus')){
              $(this).append('<span class="data-shares">'+ data.google_plus +'</span>');
              // $(this).attr({'href': 'https://plus.google.com/share?url='+ postUrl});
            } else if($(this).hasClass('otw-linkedin')){
              $(this).append('<span class="data-shares">'+ data.linkedin +'</span>');
              // $(this).attr({'href': 'http://www.linkedin.com/shareArticle?mini=true&url='+ encodeURIComponent(postUrl) +'&title='+ escape(title) +'&summary='+ escape(description)});
            } else if($(this).hasClass('otw-pinterest')){
              $(this).append('<span class="data-shares">'+ data.pinterest +'</span>');
              // $(this).attr({'href': 'http://pinterest.com/pin/create/button/?url='+ encodeURIComponent(postUrl) +'&media='+ encodeURIComponent(image) +'&description='+ escape(description)});
            }
          });
        }
      }
    });

  });

  $('.otw_blog_manager-social-wrapper').each(function(){
    var $this = $(this);
        title = $(this).data('title'),
        description = $(this).data('description'),
        image = $(this).data('image'),
        url = $(this).data('url');

        

    $(this).children('.otw_blog_manager-social-item').each(function(){
      if($(this).hasClass('otw-facebook')){
        $(this).attr({'href': 'http://www.facebook.com/sharer.php?u='+ url});
      } else if($(this).hasClass('otw-twitter')){
        $(this).attr({'href': 'https://twitter.com/intent/tweet?source=tweetbutton&url='+ encodeURIComponent(url) +'&text='+ escape(title)});
      } else if($(this).hasClass('otw-google_plus')){
        $(this).attr({'href': 'https://plus.google.com/share?url='+ url});
      } else if($(this).hasClass('otw-linkedin')){
        $(this).attr({'href': 'http://www.linkedin.com/shareArticle?mini=true&url='+ encodeURIComponent(url) +'&title='+ escape(title) +'&summary='+ escape(description)});
      } else if($(this).hasClass('otw-pinterest')){
        $(this).attr({'href': 'http://pinterest.com/pin/create/button/?url='+ encodeURIComponent(url) +'&media='+ encodeURIComponent(image) +'&description='+ escape(description)});
      }
    });
  });

  update_social_stuff();

}


function update_social_stuff() {
  //Twitter
  
  $.getScript("http://platform.twitter.com/widgets.js");  
  
  // G+
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
}

function otw_responsive_videos() {
  $('.otw_blog_manager-blog-media-wrapper').fitVids({ customSelector: "iframe[src*='soundcloud.com']"});
}