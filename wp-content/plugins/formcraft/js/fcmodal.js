jQuery(document).ready(function () {

jQuery(document).keydown(function(e) {
	if (e.keyCode == 27) { jQuery('.fcclose').click(); }
});
jQuery('body').on('click','.fcclose, .fcmodal-backdrop',function(){
	var id = jQuery(this).parents('.fcmodal').attr('id');
	jQuery('#'+id).removeClass('fcin');
	jQuery('.fcmodal-backdrop').removeClass('fcin');
	setTimeout(function(){jQuery('#'+id).fcmodal('hide');},400);
});

});

+function ($) {
 	'use strict';

  // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
  // ============================================================

  function transitionEnd() {
  	var el = document.createElement('bootstrap')

  	var transEndEventNames = {
  		'WebkitTransition' : 'webkitTransitionEnd',
  		'MozTransition'    : 'transitionend',
  		'OTransition'      : 'oTransitionEnd otransitionend',
  		'transition'       : 'transitionend'
  	}

  	for (var name in transEndEventNames) {
  		if (el.style[name] !== undefined) {
  			return { end: transEndEventNames[name] }
  		}
  	}

    return false // explicit for ie8 (  ._.)
}

  // http://blog.alexmaccaw.com/css-transitions
  $.fn.emulateTransitionEnd = function (duration) {
  	var called = false, $el = this
  	$(this).one($.support.transition.end, function () { called = true })
  	var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
  	setTimeout(callback, duration)
  	return this
  }

  $(function () {
  	$.support.transition = transitionEnd()
  })

}(jQuery);

+function(a){"use strict";var b=function(b,c){this.options=c,this.$element=a(b),this.$backdrop=this.isShown=null,this.options.remote&&this.$element.load(this.options.remote)};b.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},b.prototype.toggle=function(a){return this[this.isShown?"hide":"show"](a)},b.prototype.show=function(b){var c=this,d=a.Event("show.bs.fcmodal",{relatedTarget:b});this.$element.trigger(d);if(this.isShown||d.isDefaultPrevented())return;this.isShown=!0,this.escape(),this.$element.on("click.dismiss.fcmodal",'[data-dismiss="fcmodal"]',a.proxy(this.hide,this)),this.backdrop(function(){var d=a.support.transition&&c.$element.hasClass("fcfade");c.$element.parent().length||c.$element.appendTo(document.body),c.$element.show(),d&&c.$element[0].offsetWidth,c.$element.addClass("fcin").attr("aria-hidden",!1),c.enforceFocus();var e=a.Event("shown.bs.fcmodal",{relatedTarget:b});d?c.$element.find(".fcmodal-dialog").one(a.support.transition.end,function(){c.$element.focus().trigger(e)}).emulateTransitionEnd(300):c.$element.focus().trigger(e)})},b.prototype.hide=function(b){b&&b.preventDefault(),b=a.Event("hide.bs.fcmodal"),this.$element.trigger(b);if(!this.isShown||b.isDefaultPrevented())return;this.isShown=!1,this.escape(),a(document).off("focusin.bs.fcmodal"),this.$element.removeClass("fcin").attr("aria-hidden",!0).off("click.dismiss.fcmodal"),a.support.transition&&this.$element.hasClass("fcfade")?this.$element.one(a.support.transition.end,a.proxy(this.hidefcmodal,this)).emulateTransitionEnd(300):this.hidefcmodal()},b.prototype.enforceFocus=function(){a(document).off("focusin.bs.fcmodal").on("focusin.bs.fcmodal",a.proxy(function(a){this.$element[0]!==a.target&&!this.$element.has(a.target).length&&this.$element.focus()},this))},b.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keyup.dismiss.bs.fcmodal",a.proxy(function(a){a.which==2712&&this.hide()},this)):this.isShown||this.$element.off("keyup.dismiss.bs.fcmodal")},b.prototype.hidefcmodal=function(){var a=this;this.$element.hide(),this.backdrop(function(){a.removeBackdrop(),a.$element.trigger("hidden.bs.fcmodal")})},b.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},b.prototype.backdrop=function(b){var c=this,d=this.$element.hasClass("fcfade")?"fcfade":"";if(this.isShown&&this.options.backdrop){var e=a.support.transition&&d;this.$backdrop=a('<div class="fcmodal-backdrop '+d+'" />').appendTo(document.body),this.$element.on("click.dismiss.fcmodal",a.proxy(function(a){if(a.target!==a.currentTarget)return;this.options.backdrop=="static"?this.$element[0].focus.call(this.$element[0]):this.hide.call(this)},this)),e&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("fcin");if(!b)return;e?this.$backdrop.one(a.support.transition.end,b).emulateTransitionEnd(150):b()}else!this.isShown&&this.$backdrop?(this.$backdrop.removeClass("fcin"),a.support.transition&&this.$element.hasClass("fcfade")?this.$backdrop.one(a.support.transition.end,b).emulateTransitionEnd(150):b()):b&&b()};var c=a.fn.fcmodal;a.fn.fcmodal=function(c,d){return this.each(function(){var e=a(this),f=e.data("bs.fcmodal"),g=a.extend({},b.DEFAULTS,e.data(),typeof c=="object"&&c);f||e.data("bs.fcmodal",f=new b(this,g)),typeof c=="string"?f[c](d):g.show&&f.show(d)})},a.fn.fcmodal.Constructor=b,a.fn.fcmodal.noConflict=function(){return a.fn.fcmodal=c,this},a(document).on("click.bs.fcmodal.data-api",'[data-toggle="fcmodal"]',function(b){var c=a(this),d=c.attr("href"),e=a(c.attr("data-target")||d&&d.replace(/.*(?=#[^\s]+$)/,"")),f=e.data("fcmodal")?"toggle":a.extend({remote:!/#/.test(d)&&d},e.data(),c.data());b.preventDefault(),e.fcmodal(f,this).one("hide",function(){c.is(":visible")&&c.focus()})}),a(document).on("show.bs.fcmodal",".fcmodal",function(){a(document.body).addClass("fcmodal-open")}).on("hidden.bs.fcmodal",".fcmodal",function(){a(document.body).removeClass("fcmodal-open")})}(window.jQuery)
