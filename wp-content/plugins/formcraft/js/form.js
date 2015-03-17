+function(a){"use strict";var b=function(a,b){this.type=this.options=this.enabled=this.timeout=this.hoverState=this.$element=null,this.init("tooltip",a,b)};b.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1},b.prototype.init=function(b,c,d){this.enabled=!0,this.type=b,this.$element=a(c),this.options=this.getOptions(d);var e=this.options.trigger.split(" ");for(var f=e.length;f--;){var g=e[f];if(g=="click")this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this));else if(g!="manual"){var h=g=="hover"?"mouseenter":"focusin",i=g=="hover"?"mouseleave":"focusout";this.$element.on(h+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(i+"."+this.type,this.options.selector,a.proxy(this.leave,this))}}this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},b.prototype.getDefaults=function(){return b.DEFAULTS},b.prototype.getOptions=function(b){return b=a.extend({},this.getDefaults(),this.$element.data(),b),b.delay&&typeof b.delay=="number"&&(b.delay={show:b.delay,hide:b.delay}),b},b.prototype.getDelegateOptions=function(){var b={},c=this.getDefaults();return this._options&&a.each(this._options,function(a,d){c[a]!=d&&(b[a]=d)}),b},b.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type);clearTimeout(c.timeout),c.hoverState="in";if(!c.options.delay||!c.options.delay.show)return c.show();c.timeout=setTimeout(function(){c.hoverState=="in"&&c.show()},c.options.delay.show)},b.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type);clearTimeout(c.timeout),c.hoverState="out";if(!c.options.delay||!c.options.delay.hide)return c.hide();c.timeout=setTimeout(function(){c.hoverState=="out"&&c.hide()},c.options.delay.hide)},b.prototype.show=function(){var b=a.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(b);if(b.isDefaultPrevented())return;var c=this,d=this.tip();this.setContent(),this.options.animation&&d.addClass("fade");var e=typeof this.options.placement=="function"?this.options.placement.call(this,d[0],this.$element[0]):this.options.placement,f=/\s?auto?\s?/i,g=f.test(e);g&&(e=e.replace(f,"")||"top"),d.detach().css({top:0,left:0,display:"block"}).addClass(e),this.options.container?d.appendTo(this.options.container):d.insertAfter(this.$element);var h=this.getPosition(),i=d[0].offsetWidth,j=d[0].offsetHeight;if(g){var k=this.$element.parent(),l=e,m=document.documentElement.scrollTop||document.body.scrollTop,n=this.options.container=="body"?window.innerWidth:k.outerWidth(),o=this.options.container=="body"?window.innerHeight:k.outerHeight(),p=this.options.container=="body"?0:k.offset().left;e=e=="bottom"&&h.top+h.height+j-m>o?"top":e=="top"&&h.top-m-j<0?"bottom":e=="right"&&h.right+i>n?"left":e=="left"&&h.left-i<p?"right":e,d.removeClass(l).addClass(e)}var q=this.getCalculatedOffset(e,h,i,j);this.applyPlacement(q,e),this.hoverState=null;var r=function(){c.$element.trigger("shown.bs."+c.type)};a.support.transition&&this.$tip.hasClass("fade")?d.one(a.support.transition.end,r).emulateTransitionEnd(150):r()}},b.prototype.applyPlacement=function(b,c){var d,e=this.tip(),f=e[0].offsetWidth,g=e[0].offsetHeight,h=parseInt(e.css("margin-top"),10),i=parseInt(e.css("margin-left"),10);isNaN(h)&&(h=0),isNaN(i)&&(i=0),b.top=b.top+h,b.left=b.left+i,a.offset.setOffset(e[0],a.extend({using:function(a){e.css({top:Math.round(a.top),left:Math.round(a.left)})}},b),0),e.addClass("in");var j=e[0].offsetWidth,k=e[0].offsetHeight;c=="top"&&k!=g&&(d=!0,b.top=b.top+g-k);if(/bottom|top/.test(c)){var l=0;b.left<0&&(l=b.left*-2,b.left=0,e.offset(b),j=e[0].offsetWidth,k=e[0].offsetHeight),this.replaceArrow(l-f+j,j,"left")}else this.replaceArrow(k-g,k,"top");d&&e.offset(b)},b.prototype.replaceArrow=function(a,b,c){this.arrow().css(c,a?50*(1-a/b)+"%":"")},b.prototype.setContent=function(){var a=this.tip(),b=this.getTitle();a.find(".tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},b.prototype.hide=function(){function e(){b.hoverState!="in"&&c.detach(),b.$element.trigger("hidden.bs."+b.type)}var b=this,c=this.tip(),d=a.Event("hide.bs."+this.type);this.$element.trigger(d);if(d.isDefaultPrevented())return;return c.removeClass("in"),a.support.transition&&this.$tip.hasClass("fade")?c.one(a.support.transition.end,e).emulateTransitionEnd(150):e(),this.hoverState=null,this},b.prototype.fixTitle=function(){var a=this.$element;(a.attr("title")||typeof a.attr("data-original-title")!="string")&&a.attr("data-original-title",a.attr("title")||"").attr("title","")},b.prototype.hasContent=function(){return this.getTitle()},b.prototype.getPosition=function(){var b=this.$element[0];return a.extend({},typeof b.getBoundingClientRect=="function"?b.getBoundingClientRect():{width:b.offsetWidth,height:b.offsetHeight},this.$element.offset())},b.prototype.getCalculatedOffset=function(a,b,c,d){return a=="bottom"?{top:b.top+b.height,left:b.left+b.width/2-c/2}:a=="top"?{top:b.top-d,left:b.left+b.width/2-c/2}:a=="left"?{top:b.top+b.height/2-d/2,left:b.left-c}:{top:b.top+b.height/2-d/2,left:b.left+b.width}},b.prototype.getTitle=function(){var a,b=this.$element,c=this.options;return a=b.attr("data-original-title")||(typeof c.title=="function"?c.title.call(b[0]):c.title),a},b.prototype.tip=function(){return this.$tip=this.$tip||a(this.options.template)},b.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},b.prototype.validate=function(){this.$element[0].parentNode||(this.hide(),this.$element=null,this.options=null)},b.prototype.enable=function(){this.enabled=!0},b.prototype.disable=function(){this.enabled=!1},b.prototype.toggleEnabled=function(){this.enabled=!this.enabled},b.prototype.toggle=function(b){var c=b?a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type):this;c.tip().hasClass("in")?c.leave(c):c.enter(c)},b.prototype.destroy=function(){clearTimeout(this.timeout),this.hide().$element.off("."+this.type).removeData("bs."+this.type)};var c=a.fn.tooltip;a.fn.tooltip=function(c){return this.each(function(){var d=a(this),e=d.data("bs.tooltip"),f=typeof c=="object"&&c;if(!e&&c=="destroy")return;e||d.data("bs.tooltip",e=new b(this,f)),typeof c=="string"&&e[c]()})},a.fn.tooltip.Constructor=b,a.fn.tooltip.noConflict=function(){return a.fn.tooltip=c,this}}(jQuery)

if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function (searchElement, fromIndex) {
    if ( this === undefined || this === null ) {
      throw new TypeError( '"this" is null or not defined' );
    }

      var length = this.length >>> 0; // Hack to convert object.length to a UInt32

      fromIndex = +fromIndex || 0;

      if (Math.abs(fromIndex) === Infinity) {
        fromIndex = 0;
      }

      if (fromIndex < 0) {
        fromIndex += length;
        if (fromIndex < 0) {
          fromIndex = 0;
        }
      }

      for (;fromIndex < length; fromIndex++) {
        if (this[fromIndex] === searchElement) {
          return fromIndex;
        }
      }

      return -1;
    };
  }
  if (!String.prototype.trim) {
    String.prototype.trim = function () {
      return this.replace(/^\s+|\s+$/g, '');
    };
  }

  var operators = {
    '>': function(a, b) { return a > b },
    '=': function(a, b) { return a == b },
    '<': function(a, b) { return a < b }
  };


  function add_sliders(options)
  {

    jQuery('.slider').each(function(){
      var min = parseFloat(jQuery(this).attr('data-min'));
      var max = parseFloat(jQuery(this).attr('data-max'));
      var step = parseFloat(jQuery(this).attr('data-step'));

      if (options && jQuery("#"+this.id).hasClass('ui-slider'))
      {
        jQuery( "#"+this.id ).slider('option', {
          min: min,
          max: max,
          step: step
        });
      }
      else 
      {
        jQuery( "#"+this.id ).slider({
          min: min,
          max: max,
          range: 'min',
          value: 0,
          step: step,
          slide: function( event, ui ) 
          {
            jQuery( "#"+this.id+"_val" ).html( ui.value );
            jQuery( "#"+this.id+"_val2" ).val( ui.value ).trigger('input');
          },
          change: function( event, ui ) 
          {
            jQuery( "#"+this.id+"_val" ).html( ui.value );
            jQuery( "#"+this.id+"_val2" ).val( ui.value ).trigger('input');
          },
          create: function( event, ui ) 
          {
            jQuery( "#"+this.id+"_val" ).html( min );
            jQuery( "#"+this.id+"_val2" ).val( min ).trigger('input');
          }
        });
      }
    });


jQuery('.slider-range').each(function(){

  var min = parseFloat(jQuery(this).attr('data-min'));
  var max = parseFloat(jQuery(this).attr('data-max'));
  var step = parseFloat(jQuery(this).attr('data-step'));

  if (options && jQuery("#"+this.id).hasClass('ui-slider'))
  {
    jQuery( "#"+this.id ).slider('option', {
      min: min,
      step: step,
      max: max
    });
  }
  else 
  {
    inimax = min+((max-min)*.3);
    jQuery( "#"+this.id ).slider({
      min: min,
      step: step,
      max: max,
      range: true,
      values: [0, inimax],
      slide: function( event, ui ) 
      {
        jQuery( "#"+this.id+"_val" ).html( ui.values[0]+' - '+ui.values[1] );
        jQuery( "#"+this.id+"_val2" ).val( ui.values[0]+', '+ui.values[1] ).trigger('input');
      },
      change: function( event, ui ) 
      {
        jQuery( "#"+this.id+"_val" ).html( ui.values[0]+' - '+ui.values[1] );
        jQuery( "#"+this.id+"_val2" ).val( ui.values[0]+', '+ui.values[1] ).trigger('input');
      },
      create: function( event, ui ) 
      {
        jQuery( "#"+this.id+"_val" ).html( min+' - '+inimax );
        jQuery( "#"+this.id+"_val2" ).val( min+', '+inimax ).trigger('input');
      }            
    });
  }

});

}

function prepareMath()
{

  window.FCMATH_MAP = [];  
  jQuery('.nform').each(function(){
    var identifier = this.id;
    window.FCMATH_MAP[identifier] = [];
    jQuery(this).find('.nform_li').each(function(){
      var index = jQuery(this).attr('id').split('_')[1];
      if (typeof jQuery(this).find('.name_holder').val()!='undefined')
      {
        window.FCMATH_MAP[identifier][index] = jQuery(this).find('.name_holder').val();
      }
      else
      {
        window.FCMATH_MAP[identifier][index] = jQuery(this).find('.name_holder').val();        
      }
    });
  });

  window.FCMATH_BASE = {};
  jQuery('.nform').each(function(){
    form_id = this.id;
    window.FCMATH_BASE[form_id] = {};

    jQuery(this).find('.form_ul li .text_hidden_class').each(function(){
      var text = jQuery(this).val();
      var pattern = /\[(.*?)\]/g;
      while ((match = pattern.exec(text)) != null)
      {
        /* Create Span Templates */
        var identifier = Math.random().toString(36).replace(/[^a-z]+/g, '').substring(0,8);
        jQuery(this).val('');
        jQuery(this).attr('id','bind-'+identifier);

        /* Create Window Variables */
        window.FCMATH_BASE[form_id][identifier] = {};
        window.FCMATH_BASE[form_id][identifier].variables = [];
        window.FCMATH_BASE[form_id][identifier].string = match[1];

        if (match[1].replace(/[^*\-+\/]+/g, '')=='')
        {
          window.FCMATH_BASE[form_id][identifier].resultType = 'string';
        }
        else
        {
          window.FCMATH_BASE[form_id][identifier].resultType = 'integer';
        }

        var fields = match[1].split(/[*\-+()\/]/);
        for (field in fields)
        {
          if (fields[field].toString().trim()=='')continue;
          window.FCMATH_BASE[form_id][identifier].variables.push(fields[field].toString().trim());
        }
      }
    });

jQuery(this).find('.form_ul li .custom-text').each(function(){
  var text = jQuery(this).text();
  var html = jQuery(this).html();
  var pattern = /\[(.*?)\]/g;
  while ((match = pattern.exec(text)) != null)
  {
    /* Create Span Templates */
    var identifier = Math.random().toString(36).replace(/[^a-z]+/g, '').substring(0,8);     
    var html = html.replace('['+match[1]+']','<span id="bind-'+identifier+'"></span>');
    jQuery(this).html(html);

    /* Create Window Variables */
    window.FCMATH_BASE[form_id][identifier] = {};
    window.FCMATH_BASE[form_id][identifier].variables = [];
    window.FCMATH_BASE[form_id][identifier].string = match[1];

    if (match[1].replace(/[^*\-+\/]+/g, '')=='')
    {
      window.FCMATH_BASE[form_id][identifier].resultType = 'string';
    }
    else
    {
      window.FCMATH_BASE[form_id][identifier].resultType = 'integer';
    }

    var fields = match[1].split(/[*\-+()\/]/);
    for (field in fields)
    {
      if (fields[field].toString().trim()=='')continue;
      window.FCMATH_BASE[form_id][identifier].variables.push(fields[field].toString().trim());
    }
  }
});

});
}

function valueByIndex(form_id, index)
{
  if (typeof jQuery('#'+form_id+' .fe_'+index).find('.field_class_checkbox:checked').val()=='undefined')
  {
    if (jQuery('#'+form_id+' .fe_'+index).find('.field_class').hasClass('datepicker-field'))
    {
      var value = jQuery('#'+form_id+' .fe_'+index).find('.field_class').val();
      var value = (Date.parse(value)/(1000*60*60*24));
    }
    else
    {
      var value = jQuery('#'+form_id+' .fe_'+index).find('.field_class').val();
    }
  }
  else
  {
    var value = 0;
    jQuery('#'+form_id+' .fe_'+index).find('.field_class_checkbox:checked').each(function(){
      value = value + parseFloat(jQuery(this).val());
    });
  }

  if (value=='' || parseFloat(value)!=value)
  {
    return 0;
  }
  else
  {
    return parseFloat(value);
  }
}

function spinTo(selector, to)
{
  var from = jQuery(selector).text()=='' ? 0 : parseFloat(jQuery(selector).text());
  jQuery({someValue: from}).animate({someValue: parseFloat(to)}, {
    duration: 500,
    easing:'swing',
    step: function() {
      jQuery(selector).text(Math.ceil(this.someValue));
    }
  });
  setTimeout(function(){
    jQuery(selector).text(parseFloat(to));
  }, 550);
}

function refreshMath(form_id, element)
{
  var field_name = jQuery(element).parents('.nform_li').find('.name_holder').val();
  var field_index = jQuery(element).parents('.nform_li').attr('id').split('_')[1];
  for (bindKey in window.FCMATH_BASE[form_id])
  {
    for (variable in window.FCMATH_BASE[form_id][bindKey].variables)
    {
      thisVariable = window.FCMATH_BASE[form_id][bindKey].variables[variable];

      if (thisVariable==field_name)
      {

        var mathResult = window.FCMATH_BASE[form_id][bindKey].string;

        /* Substitute the field values, and make the result */
        for (key in window.FCMATH_BASE[form_id][bindKey].variables)
        {
          var index_ = window.FCMATH_MAP[form_id].indexOf(window.FCMATH_BASE[form_id][bindKey].variables[key]);
          if (index_==-1)continue;
          var mathResult = mathResult.replace(window.FCMATH_BASE[form_id][bindKey].variables[key], valueByIndex(form_id, index_));
        }

        var finalValue = parseFloat(eval(mathResult))==parseInt(eval(mathResult)) ? parseInt(eval(mathResult)) : parseFloat(eval(mathResult)).toFixed(2);

        if(jQuery('#bind-'+bindKey).attr('type')=='hidden')
        {
          jQuery('#bind-'+bindKey).val(finalValue).trigger('input');
        }
        else
        {
          if(jQuery('#'+form_id).hasClass('spin'))
          {
            spinTo('#bind-'+bindKey, finalValue);
          }
          else
          {
            jQuery('#bind-'+bindKey).text(finalValue);
          }
        }
      }
    }
  }
}

function has3d(){
  var el = document.createElement('p'),
  has3d,
  transforms = {
    'webkitTransform':'-webkit-transform',
    'OTransform':'-o-transform',
    'msTransform':'-ms-transform',
    'MozTransform':'-moz-transform',
    'transform':'transform'
  };

    // Add it to the body to get the computed style
    document.body.insertBefore(el, null);

    for(var t in transforms){
      if( el.style[t] !== undefined ){
        el.style[t] = 'translate3d(1px,1px,1px)';
        has3d = window.getComputedStyle(el).getPropertyValue(transforms[t]);
      }
    }

    document.body.removeChild(el);

    return (has3d !== undefined && has3d.length > 0 && has3d !== "none");
  }

  function formcraft_cl(element)
  {
    jQuery.fx.interval = 40;
    if(jQuery('.ff_c_t').length){return false;};

    var form = jQuery(element).parents('form').attr('id');
    if (jQuery(element).attr('type')=='checkbox')
    {
      var val = [];
      jQuery('[name="'+jQuery(element).attr('name')+'"]').each(function(){
        if(jQuery(this).prop('checked')==true)
        {
          val.push(jQuery(this).val());
        }
      });
    }
    else
    {
      var val = jQuery(element).val();
    }
    var json = jQuery.parseJSON(jQuery(element).attr('do_what'));

    var temp_show = false;
    var temp_hide = false;


    for (key2 in json)
    {
      if (json[key2].equals==undefined)
      {
        break;
      }

      if (jQuery(element).hasClass('datepickermdy'))
      {
        var format = jQuery(element).attr('format');
        var val = jQuery(element).val();
        val = parseDate(val, format);
        json[key2].equals = parseDate(json[key2].equals, format);
      }
      if (jQuery.isNumeric(val))
      {
        val = parseInt(val);
      }
      if (jQuery.isNumeric(json[key2].equals) &&  json[key2].equals!='0')
      {
        json[key2].equals = parseInt(json[key2].equals);
      }


      var op = json[key2].law;
      if(json[key2].equals=='' || json[key2].to=='')
      {
        continue;
      }

      if (operators[op](val, json[key2].equals) && json[key2].doit=='show')
      {
        var temp_show = json[key2].to;
      }
      if (operators[op](val, json[key2].equals) && json[key2].doit=='hide')
      {
        var temp_hide = json[key2].to;
      }
    }

    final_do = new Array();

    for (key in json)
    {
      var op = json[key].law;

      if (jQuery(element).hasClass('datepickermdy'))
      {
        var format = jQuery(element).attr('format');
        var val = jQuery(element).val();
        val = parseDate(val, format);
      }

      if (json[key].equals && json[key].doit && json[key].law)
      {
        // If all attributes for CLs are valid
        if (jQuery.isNumeric(val))
        {
          val = parseInt(val);
        }
        if (jQuery.isNumeric(json[key].equals) &&  json[key].equals!='0')
        {
          json[key].equals = parseInt(json[key].equals);
        }

        var fulfilled = jQuery(element).attr('type')=='checkbox' ? check_array(val, json[key].equals, op) : operators[op](val, json[key].equals);

        if (fulfilled)
        {
          if (json[key].doit=='show')
          {
            final_do[json[key].to] = 'down';
            keys = split_comma(json[key].to);
            for (keyTemp in keys)
            {
              if(typeof keys[keyTemp]=='function') {continue;}
              jQuery('#'+form+' .fe_'+keys[keyTemp]).addClass('changed_temp');
              jQuery('#'+form+' .fe_'+keys[keyTemp]).addClass('artificial');              
            }
          }
          else if (json[key].doit=='hide')
          {
            final_do[json[key].to] = 'up';
            keys = split_comma(json[key].to);
            for (keyTemp in keys)
            {
              if(typeof keys[keyTemp]=='function') {continue;}
              jQuery('#'+form+' .fe_'+keys[keyTemp]).addClass('changed_temp');
              jQuery('#'+form+' .fe_'+keys[keyTemp]).addClass('artificial');              
            }
          }
          else if (json[key].doit=='redirect')
          {
            if(window.finalRedirect.indexOf(json[key].redirect)==-1)
            {
              window.finalRedirect.push(json[key].redirect);
            }
          }
          else if (json[key].doit=='emails')
          {
            if(window.finalEmails.indexOf(json[key].emails)==-1)
            {
              window.finalEmails.push(json[key].emails);
            }
          }          
        }
        else
        {

          keys = split_comma(json[key].to);
          for (keyTemp in keys)
          {
            if(typeof keys[keyTemp]=='function') {continue;}
            var has = jQuery('#'+form+' .fe_'+keys[keyTemp]).hasClass('changed_temp');
            var arti = jQuery('#'+form+' .fe_'+keys[keyTemp]).hasClass('artificial');
            if (json[key].doit=='show' && arti==true && has==false && temp_show!=keys[keyTemp])
            {
              final_do[keys[keyTemp]] = 'up';
              jQuery('#'+form+' .fe_'+keys[keyTemp]).removeClass('artificial');
            }
            else if (json[key].doit=='hide' && arti==true && has==false && temp_hide!=keys[keyTemp])
            {
              final_do[keys[keyTemp]] = 'down';
              jQuery('#'+form+' .fe_'+keys[keyTemp]).removeClass('artificial');
              jQuery('#'+form+' .inline2.fe_'+keys[keyTemp]).css({'display':'inline-block'});
              jQuery('#'+form+' .inline3.fe_'+keys[keyTemp]).css({'display':'inline-block'});
              jQuery('#'+form+' .inline4.fe_'+keys[keyTemp]).css({'display':'inline-block'});
            }
            else if (json[key].doit=='redirect')
            {
              if(window.finalRedirect.indexOf(json[key].redirect)!=-1)
              {
                window.finalRedirect.splice(window.finalRedirect.indexOf(json[key].redirect),1);
              }
            }
            else if (json[key].doit=='emails')
            {
              if(window.finalEmails.indexOf(json[key].emails)!=-1)
              {
                window.finalEmails.splice(window.finalEmails.indexOf(json[key].emails),1);
              }
            }            
          }
        }
      } // End of IF
    } // End of For Loop

    for (key1 in final_do)
    {
      if (typeof final_do!='object') break;
      keys = split_comma(key1);
      for (thisKey in keys)
      {
        keys[thisKey] = keys[thisKey].toString().trim();
        if (keys[thisKey]=='' || parseInt(keys[thisKey])!=keys[thisKey]) continue;
        if (final_do[key1]=='up')
        {
          jQuery('#'+form+' .fe_'+keys[thisKey]).slideUp();
          jQuery('#'+form+'.no_submit_hidden .fe_'+keys[thisKey]).find('input, select, textarea').addClass('no_show');
        }
        else
        {
          if(navigator.appName!='Microsoft Internet Explorer'){
            setTimeout(function(){
              if(typeof keys[thisKey]!='function')
              {
                jQuery('#'+form+' .fe_'+keys[thisKey]).css('display','inline-block');
              }
            },500);
          }
          jQuery('#'+form+' .inline2.fe_'+keys[thisKey]).css('display','inline-block');
          jQuery('#'+form+' .inline3.fe_'+keys[thisKey]).css('display','inline-block');
          jQuery('#'+form+' .inline4.fe_'+keys[thisKey]).css('display','inline-block');
          jQuery('#'+form+' .fe_'+keys[thisKey]).slideDown(500, function(){
            if(typeof keys[thisKey]!='function')
            {
              jQuery('#'+form+' .fe_'+keys[thisKey]).css('display','inline-block');
            }
          });
          jQuery('#'+form+' .fe_'+keys[thisKey]).find('input, select, textarea').removeClass('no_show');
        }
      }
    }

    jQuery('#'+form+' .changed_temp').removeClass('changed_temp');
  }


  function split_comma(key1)
  {
    keyArray = key1.indexOf(',')==-1 ? key1+',' : key1;
    var keys = keyArray.split(',');
    return keys;  
  }


  function load_user_form()
  {
    var cook = document.cookie.split(';');
    var limit_1 = cook.length-2; 

    for (key in cook)
    {
      if (key>limit_1)
      {
        break;
      }
      if(typeof cook[key]=='function')
      {
        continue;
      }    
      if (cook[key].substring(0,9)=='FormCraft' || cook[key].substring(1,10)=='FormCraft')
      {
        var form_val = cook[key].split('&');
        var limit_2 = form_val.length-1;
        for (key2 in form_val)
        {
          if (key2>limit_2)
          {
            break;
          }
          if(typeof form_val[key2]=='function') continue;
          temp_val = form_val[key2].split('=');
          temp_val[0] = decodeURIComponent(temp_val[0].replace(/\+/g,' '));
          temp_val[1] = decodeURIComponent(temp_val[1].replace(/\+/g,' '));
          if (temp_val[0]=='FORM_ID')
          {
           var form_id = temp_val[1];
         }
         else if (form_id)
         {
          var temp_val_2 = temp_val[0].split('_');
          var field_temp = jQuery('.a_123'+form_id+' [name="'+temp_val[0]+'"]');
          if (field_temp.length)
          {
            if (field_temp.hasClass('reason_ta'))
            {
              temp_val_2[1] = 'textarea';
            }

            if(temp_val_2[1]=='radio' || temp_val_2[1]=='check' || temp_val_2[1]=='matrix')
            {
              var field = jQuery('.a_123'+form_id+' [name="'+temp_val[0]+'"][value="'+temp_val[1]+'"]');
              field.attr('checked', true);
              field.trigger('change');
            }
            else if (temp_val_2[1]=='stars' || temp_val_2[1]=='thumbs')
            {
              var field = jQuery('.a_123'+form_id+' [name="'+temp_val[0]+'"][value="'+temp_val[1]+'"]');
              var temp_id = field.parent('label').attr('id');
              field.attr('checked', true);
              field_click(temp_id, 0);
              field.trigger('change');
            }
            else if (temp_val_2[1]=='smiley')
            {
              var field = jQuery('.a_123'+form_id+' [name="'+temp_val[0]+'"][value="'+temp_val[1]+'"]');
              var temp_id = field.parent('label').attr('id');
              field.attr('checked', true);
              field_click(temp_id, 1);
              field.trigger('change');
            }
            else if (temp_val_2[1]=='text' || temp_val_2[1]=='para' || temp_val_2[1]=='email' || temp_val_2[1]=='dropdown' || temp_val_2[1]=='date' || temp_val_2[1]=='time')
            {
              var field = jQuery('.a_123'+form_id+' [name="'+temp_val[0]+'"]');
              field.val(temp_val[1]);
              field.trigger('change');
            }
            else if(temp_val_2[1]=='slider')
            {
              var field = jQuery('.a_123'+form_id+' [name="'+temp_val[0]+'"]');
              field.val(temp_val[1]);
              jQuery('.slider').each(function(){
                var temp_slider_value = jQuery("#"+this.id+"_val2").val();
                temp_slider_value = parseInt(temp_slider_value);
                if (!(isNaN(temp_slider_value)))
                {
                  jQuery( "#"+this.id ).slider( "option", "value", temp_slider_value );
                }
              });
            } 
            else if(temp_val_2[1]=='slider-range')
            {
              var field = jQuery('.a_123'+form_id+' [name="'+temp_val[0]+'"]');
              field.val(temp_val[1]);
              jQuery('.slider-range').each(function(){
                var temp_slider_value = jQuery("#"+this.id+"_val2").val().split(', ');
                temp_slider_value[0] = parseInt(temp_slider_value[0]);
                temp_slider_value[1] = parseInt(temp_slider_value[1]);

                if (!(isNaN(temp_slider_value[0])))
                {
                  jQuery( "#"+this.id ).slider( "option", "values", [temp_slider_value[0], temp_slider_value[1]] );
                }

              });
            }          
          }

        }
      }
    }
  }
}

function save_user_form(id, real_id)
{
  var form_data = jQuery('#'+real_id).serialize();
  var data = "FORM_ID="+id+"&"+form_data;
  var date = new Date();
  date.setDate(date.getDate() + 7);
  document.cookie="FormCraft_"+id+"=true&"+data+"; expires="+date.toUTCString();
}

function parseDate(val, format)
{
  if(val.search('-'))
  {
    var date = val.toString().split('-');    
  }
  else
  {
    var date = val.toString().split('/');    
  }


  if (format=='dd-mm-yyyy')
  {
    var tmp = (parseInt(date[2], 10)*365)+(parseInt(date[1], 10)*31)+(parseInt(date[0], 10));
  }
  else if (format=='mm-dd-yyyy')
  {
    var tmp = (parseInt(date[2], 10)*365)+(parseInt(date[1], 10))+(parseInt(date[0], 10)*31);
  }
  return tmp;
}

jQuery(document).ready(function () {

  jQuery('body').on('click','.datepicker-cover',function(){
    jQuery(this).find('input').trigger('focus');
  });


  jQuery('body').on('click','.fcclose',function(){
    var id = jQuery(this).parents('.fcmodal').attr('id');
    jQuery('#'+id).removeClass('fcin');
    jQuery('.fcmodal-backdrop').removeClass('fcin');
    setTimeout(function(){jQuery('#'+id).fcmodal('hide');},400);  
  });
  jQuery('body').on('focus','.fcclose',function(){
    var id = jQuery(this).parents('.fcmodal').attr('id');
    jQuery('#'+id).removeClass('fcin');
    jQuery('.fcmodal-backdrop').removeClass('fcin');
    setTimeout(function(){jQuery('#'+id).fcmodal('hide');},400);  
  });
  jQuery('body').on('touchstart','.fcclose',function(){
    var id = jQuery(this).parents('.fcmodal').attr('id');
    jQuery('#'+id).removeClass('fcin');
    jQuery('.fcmodal-backdrop').removeClass('fcin');
    setTimeout(function(){jQuery('#'+id).fcmodal('hide');},400);  
  }); 

  window.finalRedirect = [];
  window.finalEmails = [];
  jQuery('.inst.ttip').css('position','absolute');

  jQuery('body').on('click', '.input-append .add-on', function(){
    jQuery(this).parents('.input-append').children('input').focus();
  });

  /* Get Location and Save in Field */
  jQuery('.nform .getlocation').each(function(){
    jQuery(this).val(document.location.href);
  });

  jQuery('.fc-nav-tabs > li > a').click(function(event){
    event.preventDefault();
    jQuery(this).parents('.fc-nav-tabs').find('li').removeClass('active');
    jQuery(this).parent().addClass('active');
    var abc = jQuery(this).parent().index() + 1;

    jQuery(this).parent().parent().parent().find('> .fc-tab-content > div').hide();
    jQuery(this).parent().parent().parent().find('> .fc-tab-content > div').removeClass('active');
    jQuery(this).parent().parent().parent().find('> .fc-tab-content > div:nth-child('+abc+')').show();
    jQuery(this).parent().parent().parent().find('> .fc-tab-content > div:nth-child('+abc+')').addClass('active');
  });


  /* Initializations / Fixes */
  jQuery('.fcmodal').each(function(){
    if (jQuery(this).find('.nform').css('width') && jQuery(this).find('.nform').css('width').indexOf('%')==-1)
    {
      var width = jQuery(this).find('.nform').width();
    }
    else
    {
      var width = parseInt(jQuery(this).find('.nform').width())/100;
      var width = jQuery('body').width()*width;
      jQuery(this).find('.nform').css('width',width+'px');
    }
    jQuery(this).find('.fcmodal-dialog').css('width',width+'px');
    jQuery('body').append(this);
  });


  jQuery('.fly_cover').each(function(){
    jQuery('body').append(this);
  });
  jQuery('.fc-backdrop').each(function(){
    jQuery('body').append(this);
  });  

  jQuery('#form_ul').css({
    'height':'auto',
    'opacity': 1
  });

  /* Give Border Radius to Cover of Popup Form */
  jQuery('.nform').each(function(){
    var rad = jQuery(this).css('borderRadius');
    jQuery(this).parent('.fly_form').css({
      'borderRadius':rad,
      'MozBorderRadius':rad,
      'WebkitBorderRadius':rad
    });
  });

  jQuery('body').on('click','.label_thumb input' , function(){
   field_click(jQuery(this).parent().attr('id'), 0);
   setTimeout(function(){
    setupLabel();
  },100);
 });

  jQuery('body').on('touchstart','.label_thumb' , function(){
    jQuery(this).find('input').click();
    setTimeout(function(){
      setupLabel();
    },100);
  });

  jQuery('body').on('click','.label_tick, .label_radio, .label_check' , function(){
    setTimeout(function(){
      setupLabel();
    },100);
  });


  // Remove Top Border from Forms with No Title
  jQuery('.form_title').each(function(){
    if (!jQuery(this).text())
    {
      jQuery(this).parent('form').find('.form_ul').css({
        'borderTop':'0'
      });
    }
  })


  jQuery('.ui-slider-range.ui-widget-header').css('width','0');

  jQuery('.reason_ta').css({
    display: 'none'
  });

  jQuery('.reason_text').text('');
  jQuery('.reason_cover').css({'display':'none'});


  // Toggle Sticky Form and Fly Form
  
  // Hide the Fly Form to the Right
  var wd = jQuery('.fly_form').css('width');
  jQuery('.fly_form').each(function(){
    if (jQuery(this).find('.nform').css('width') && jQuery(this).find('.nform').css('width').indexOf('%')==-1)
    {
      var width = parseInt(jQuery(this).find('.nform').width());
    }
    else
    {
      var width = parseInt(jQuery(this).find('.nform').width())/100;
      var width = jQuery('body').width()*width;
      jQuery(this).find('.nform').css('width',width+'px');
    }
    jQuery(this).css('margin-right','-'+((width/2)+10)+'px');
    jQuery('body').append(this);
  });

  if (has3d()==false)
  {  
    jQuery('.fly_form').css('right','-40%');
    //jQuery('.fly_form').show();    
  }
  else
  {
    //jQuery('.fly_form').show();  
  }
  jQuery('.fly_form .nform').addClass('twist');


  var fix_fly_toggle = setInterval(function(){
    if (jQuery('.fly_toggle').height()!=0) {
      clearInterval(fix_fly_toggle);
      jQuery('.fly_toggle').css('margin-top','-'+(jQuery('.fly_toggle').height()/2)+'px');
      jQuery('.fly_toggle').animate({'right':'0px'});
    }
  }, 500);


  /* Open the Fly Form on Click */
  jQuery('.fly_toggle').click(function(){
    jQuery('.fly_form').show();      
    var wd = jQuery('.fly_form form').css('width');
    wd = parseInt(wd);
    var hei = jQuery('.fly_form form').css('height');
    hei = parseInt(hei);
    
    jQuery('.fly_form .nform').removeClass('twist');
    jQuery('.fc-backdrop').show();

    if (has3d()==false)
    {
      jQuery('.fly_form').animate({
        'right': '50%',
        'opacity': '1'
      });
    }
    else
    {
      jQuery('.fly_form').addClass('show_fly');
    }
    jQuery('.fly_form').addClass('show_fly');

    jQuery('.fly_toggle').animate({
      'right': '-'+jQuery('.fly_toggle').css('width'),
      'opacity': '0'
    });    

  });
  
  /* Close the Fly Form on Click */
  jQuery('.fly_form .fcclose').click(function(){

    var wd = jQuery('.fly_form').css('width');
    jQuery('.fc-backdrop').fadeOut();
    var hei = jQuery('.fly_form .nform').addClass('twist');

    if (has3d()==false)
    {
      jQuery('.fly_form').animate({
        'right': '60%'
      }, 200, function () {
        jQuery('.fly_form').animate({
          'right': '-100%'
        }, 500);
      });      
    }
    else
    {
      jQuery('.fly_form').removeClass('show_fly');      
    }

    jQuery('.fly_toggle').animate({
      'right': '-1px',
      'opacity': '1'
    });
    setTimeout(function(){
      jQuery('.fly_form').hide();       
    }, 500);
  });

  var height = jQuery(window).height()*.8;
  jQuery('.sticky_nform').css('max-height',height);
  jQuery('.sticky_nform').css('overflow', 'auto');

  /* Open the Sticky Form on Click */
  jQuery('.sticky_toggle').click(function()
  {
    increment_form(jQuery(this).attr('id'));
    jQuery('.sticky_toggle').slideUp();
    var pad = jQuery('.sticky_nform .nform .form_ul').css('paddingTop');
    pad = parseInt(pad);
    jQuery(this).parents('.sticky_cover').find('.sticky_nform').slideToggle(300);
  });

  /* Open the Sticky Form on Click */
  jQuery('.sticky_close').click(function()
  {
    jQuery('.sticky_toggle').slideDown();
    var pad = jQuery('.sticky_nform .nform .form_ul').css('paddingTop');
    pad = parseInt(pad);
    jQuery(this).parents('.sticky_cover').find('.sticky_nform').slideUp(300);
  });

  jQuery('.sticky_nform').each(function(){
    var color = jQuery(this).find('#fe_title').css('color');
    jQuery('.sticky_close').css('color',color);
  });


  setTimeout(function () {
    jQuery('.sticky_cover.open .sticky_toggle').trigger('click');
    jQuery('.fly_cover.open .fly_toggle').trigger('click');
  }, 500);

  jQuery('#form_ul').show();

  jQuery('.click').removeClass('click');



  var temp_1 = jQuery('.ref_link').text();

  if (temp_1==false)
  {
    jQuery('.ref_link').attr('href','');
    jQuery('.ref_link').attr('ng-href','');
  }



});

/* Check if Value matches any value in the array Arr */
function check_array(arr, value, law)
{
  var op = law;
  for (key_temp in arr)
  {
    if (op)
    {
      if (operators[op](arr[key_temp], value))
      {
        return true;
      }
    }
  }
  return false;
}

function increment_form(idd)
{
  var id = idd.split('_');
  if (window.increment_form_true==true)
  {
    return false;
  }
  jQuery.ajax({
    dataType: 'json',
    type: "POST",
    url: FormCraftJS.ajaxurl,
    data: 'id='+id[0]+'&action=formcraft_increment2',
    success: function (response) {
      window.increment_form_true = true;
    }
  });  
}

function placeholders()
{
  jQuery('.nform.placeholder input, .nform.placeholder textarea').placeholder();
}


// Initialize the File Upload
function add_upload(options)
{

  /* File Upload JS Functions */
  jQuery(function () {

    if (jQuery('.fileupload-input').length)
    {
      jQuery('.fileupload-input').fileupload({
        dataType: 'json',
        add: function (e, data) 
        {
          var upload_ul = jQuery(this).parents('.upload_input_cover').find('.upload_ul');
          var this_id = '#'+jQuery(this).attr('id')+' ';
          var this_form = '#'+jQuery(this).parents('form').attr('id')+' ';
          var len = jQuery(this).parents('.upload_input_cover').find('.upload_ul li').length;
          var type = data.files[0].name;
          var type = type.split('.');
          var type = type[1];
          var allowed = jQuery(this_id).attr('filetype');

          var allowed = allowed.split(' ');
          var found = false;

          var size = data.files[0].size/1024;
          var mins = isNaN(jQuery(this_id).attr('mins')) ? 0 : parseInt(jQuery(this_id).attr('mins'));
          var maxs = isNaN(jQuery(this_id).attr('maxs')) ? 0 : parseInt(jQuery(this_id).attr('maxs'));

          var allowedNos = parseInt(jQuery(this_id).attr('filemax'));
          var used = parseInt(jQuery(this_id).parents('.upload_input_cover').find('.upload_hidden').val());
          if (used>=allowedNos)
          {
            alert(jQuery(this_id).attr('filetype3_error'));
            return false;            
          }

          if (size<mins && mins!=0)
          {
            alert(jQuery(this_id).attr('filetype1_error'));
            return false;
          }
          if (size>maxs && maxs!=0)
          {
            alert(jQuery(this_id).attr('filetype2_error'));
            return false;
          }

          if (jQuery(this_id).attr('filetype'))
          {
            for (var key in allowed)
            {
              if (allowed[key]==type)
              {
                var found = true;
              }
            }
          }
          else
          {
            var found = true;
          }

          if (found==false)
          {
            alert(jQuery(this_id).attr('filetype_error'));
            return false;
          }

          upload_ul.append("<li class='li_upload_"+len+"'><div class='file-upload-cover'><span class='progress progress-striped'><span class='bar' style='width: 0%'></span></span><span class='cover_fileupload'></span></div></li>")

          data.fname = jQuery('<span class="file_name"></span>').appendTo(upload_ul.find('.li_upload_'+len+' .cover_fileupload'));
          data.hidden = jQuery('<input type="hidden" name="files_file'+len+'">').appendTo(upload_ul.find('.li_upload_'+len+' .cover_fileupload'));
          data.whichLi = upload_ul.find('li').length;
          data.submit();
        },
        done: function (e, data)
        {
          var len = data.whichLi - 1;
          if (data.result.failed)
          {
            jQuery(this).parents('.upload_input_cover').find('.upload_ul .li_upload_'+len).html(data.result.failed);
            return false;
          }

          if (jQuery(this).parents('.upload_input_cover').find('.upload_hidden').val()==null)
          {
            jQuery(this).parents('.upload_input_cover').find('.upload_hidden').val(1);
          }
          else
          {
            jQuery(this).parents('.upload_input_cover').find('.upload_hidden').val(parseInt(jQuery(this).parents('.upload_input_cover').find('.upload_hidden').val())+1);
          }

          jQuery(this).parents('.upload_input_cover').find('.upload_hidden').trigger('change');
          data.hidden.val(data.result.files.fullurl);
          jQuery('<span class="name_bar">'+data.result.files.name+'</span>').appendTo(jQuery(this).parents('.upload_input_cover').find('.upload_ul .li_upload_'+len+' .progress .bar'));
          jQuery('<i data-key="'+data.result.files.new_name+'" class="formcraft-trash" title="Delete"></i>').appendTo(data.fname).click(function()
          {
            jQuery.ajax({
              url: FormCraftJS.ajaxurl,
              type: "POST",
              data: 'action=formcraft_delete_file&key='+encodeURIComponent(jQuery(this).attr('data-key')),
              success: function (response) {
                if (response=='Deleted')
                {
                  jQuery(data.fname).text('');
                  jQuery('<span style="color: red; font-weight: bold; margin-left: -20px"><i class="formcraft-ok" style="color: green"></i></span>').appendTo(data.fname);
                  data.fname.parent().find('input').remove();
                  data.fname.parent().parent().find('.progress').css('opacity','.5');
                  data.fname.parents('.upload_input_cover').find('.upload_hidden').val(parseInt(data.fname.parents('.upload_input_cover').find('.upload_hidden').val())-1);
                }
              },
              error: function (response) {

              }
            });

          });

        },
        progress: function (e, data)
        {
          var progress = parseInt(data.loaded / data.total * 100, 10);
          var len = data.whichLi -1;
          jQuery(this).parents('.upload_input_cover').find('.upload_ul .li_upload_'+len+' .progress .bar').css(
            'width',
            progress + '%'
            );
        }     
      });    
}

});

jQuery('.nform .fileupload-input').attr('data-url', FormCraftJS.server);

}




// Setup the Radios and CheckBoxes
function setupLabel() 
{

 if (jQuery('.label_check input').length) {
  jQuery('.label_check').each(function(){ 
    jQuery(this).removeClass('c_on');
  });
  jQuery('.label_check input:checked').each(function(){ 
    jQuery(this).parent('label').addClass('c_on');
  });                
};



if (jQuery('.label_radio input').length) {
  jQuery('.label_radio').each(function(){ 
    jQuery(this).removeClass('r_on');
  });
  jQuery('.label_radio input:checked').each(function(){ 
    jQuery(this).parent('label').addClass('r_on');

  });
};

if (jQuery('.label_thumb input').length) {
  jQuery('.label_thumb').each(function(){ 
    jQuery(this).removeClass('r_on');
  });
  jQuery('.label_thumb input:checked').each(function(){ 
    jQuery(this).parent('label').addClass('r_on');
  });
};



if (jQuery('.label_tick input').length) {
  jQuery('.label_tick').each(function(){ 
    jQuery(this).removeClass('r_on');
  });
  jQuery('.label_tick input:checked').each(function(){ 
    jQuery(this).parent('label').addClass('r_on');
  });
};




};

/* Setup the DatePicker */
function jq_functions()
{
 setupLabel();
 jQuery('.label_stars').tooltip();
 jQuery('.vertical .label_stars').tooltip('destroy');
 jQuery('.label_thumb').tooltip();
 jQuery('.vertical .label_thumb').tooltip('destroy');
 jQuery('.label_smiley').tooltip();
 jQuery('.vertical .label_smiley').tooltip('destroy');
}

function tooltipSet()
{
 jQuery('.ttip').tooltip();
 jQuery('.ttip').each(function()
 {
  var ttle = jQuery(this).attr('data-original-title');
  if (ttle)
  {
    jQuery(this).parents('.q_cover').show();
  }
  else
  {
    jQuery(this).parents('.q_cover').hide();
  }
});  
}



jQuery.fn.mySerialize = function(id) 
{
  var returning = '';
  jQuery('#'+id+'.nform input[type=radio], #'+id+'.nform input[type=checkbox]').not('.no_show').each(function()
  {
    var name = this.name;
    var radio_check = jQuery('input[name="'+name+'"]');
    if( radio_check.filter(':checked').length == 0 )
    {
      returning += '&'+name+'=';
    }         
  })
  return returning+"&";
};

/* Submit the Form */
function submit_formcraft(id)
{
  jQuery('#'+id+' .nform_res').hide();    
  jQuery('#'+id+' .nform_res').html('');    

  var emails = '';
  if (jQuery('#emails_'+id).length)
  {
    var emails = jQuery('#emails_'+id).val();
  }  

  /* Animate Submit Button */
  var sub = jQuery('#'+id+'.nform .submit_button').text();
  jQuery('#'+id+'.nform .submit_button').html('');
  jQuery('#'+id+'.nform .submit_button').prop("disabled",true);


  var pd = jQuery('#'+id+'.nform .submit_button').css('height');
  jQuery('#'+id+'.nform .submit_button').addClass('loading_class');


  var fid = jQuery('#'+id+' .form_id').attr('val');
  var title = jQuery('#'+id+' .form_title').html();

  jQuery.ajax({
    dataType: 'json',
    type: "POST",
    url: FormCraftJS.ajaxurl,
    data: 'id='+fid+'&action=formcraft_submit&'+jQuery('#'+id).mySerialize(id)+jQuery('#'+id+' :input').not('.no_show').serialize()+'&title='+title+'&emails='+emails,
    success: function (response)
    {
      jQuery('#'+id+'.nform .submit_button').prop("disabled",false);

      jQuery('#'+id+'.nform .submit_button').text(sub);
      jQuery('#'+id+'.nform .submit_button').removeClass('loading_class');

      jQuery('#'+id+' .valid_show').css('display','none');

      if (response!=null)
      {
        if (response.sent=='false')
        {
          jQuery('#'+id+' .nform_res').html(""+response.msg);
          jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');
          jQuery('#'+id+' .nform_res').addClass('alert alert-error');
          if (response.errors!='none' && response.errors!='')
          {
            jQuery('#'+id+' .nform_res').slideDown(200);
          }
        }
        else if (response.sent=='true')
        {
          jQuery('#'+id+' .form_submit').slideUp();
          jQuery('#'+id+'.nform .submit_button').prop("disabled",true);

          var temp = '<div class="nform_res nform_success" style="display: none">'+response.msg+'</div>';
          jQuery(temp).insertAfter('#'+id+' .form_ul');
          
          jQuery('#'+id+' .form_title').css({
            'borderBottom':'1px solid #ddd'
          });


          jQuery('#'+id+' .form_ul').animate({ 
            height: '0', 
            paddingTop: '0', 
            paddingBottom: '0', 
            opacity: '0'
          }, 300, function(){
            jQuery('#'+id+' .form_ul').hide();
          });
          jQuery('#'+id+' .form_ul li').slideUp();

          jQuery('#'+id+' .form_title').animate({ 
            height: '0', 
            paddingTop: '0', 
            paddingBottom: '0', 
            opacity: '0'
          }, 300);

          if (response.errors!='none' && response.errors!='')
          {
            jQuery('#'+id+' .nform_res').slideDown(200);
          }

          jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');

          setTimeout(function () {
            jQuery('.sticky_on').trigger('click');
          }, 3000);

          setTimeout(function () {
            if (jQuery('.modal-backdrop.in').length)
            {
              jQuery('.modal_close').trigger('click');
            }
          }, 3000);


          if (response.redirect && !(jQuery('.ff_c_t').length))
          {
           window.location.href=response.redirect;
         }
       }
       else if (typeof response.errors!='undefined')
       {

        for (var key in response)
        {
          if (response.hasOwnProperty(key)) 
          {

            if (jQuery(window).width() < 740) 
            {
              var temp = response[key];
              jQuery('#'+id+' .'+key).html(""+temp);

              jQuery('#'+id+' .valid_show.'+key).css({
                'display': 'block'
              });
              jQuery('#'+id+' .valid_show.'+key).slideDown();
            } 
            else 
            {

              var temp = response[key];
              jQuery('#'+id+' .'+key).html(""+temp);

              jQuery('#'+id+' .valid_show.'+key).css('margin-left','100px');
              jQuery('#'+id+' .valid_show.'+key).css('display','inline');
              jQuery('#'+id+' .valid_show.'+key).css('opacity','0');

              var temp = response[key];

              jQuery('#'+id+'.one .'+key).animate(
              {
                marginLeft:'-15px',
                opacity: .85
              }, 300);
              jQuery('#'+id+'.two .'+key).animate(
              {
                marginLeft:'-14px',
                opacity: .85
              }, 300);
              jQuery('#'+id+'.three .'+key).animate(
              {
                marginLeft:'-45%',
                opacity: .85
              }, 300);
            }
          }   
        }

        jQuery('#'+id+'.nform .submit_button').text(sub);
        jQuery('#'+id+' .nform_res').html(response.errors);
        jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');
        jQuery('#'+id+' .nform_res').addClass('alert alert-error');
        if (response.errors!='none' && response.errors!='')
        {
          jQuery('#'+id+' .nform_res').slideDown(200);
        }
      }
      else
      {
        jQuery('#'+id+'.nform .submit_button').text(sub);
        jQuery('#'+id+' .nform_res').html('');
        jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');
        jQuery('#'+id+' .nform_res').addClass('alert alert-error');
      }
    }
    else
    {
      jQuery('#'+id+'.nform .submit_button').text(sub);
      jQuery('#'+id+' .nform_res').html('');
      jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');
      jQuery('#'+id+' .nform_res').addClass('alert alert-error');
    }
  },
  error: function (response) 
  {
    jQuery('#'+id+'.nform .submit_button').prop("disabled",false);
    jQuery('#'+id+'.nform .submit_button').text(sub);
    jQuery('#'+id+'.nform .submit_button').removeClass('loading_class');
    jQuery('#'+id+' .nform_res').html('');
    jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');
    jQuery('#'+id+' .nform_res').addClass('alert alert-error');
  }
});
}





/* Submit the Form */
function submit_formcraft_new(form_id, form_uniq, id)
{
  if (jQuery('#waspnet').length>0 && jQuery('#waspnet').val()!='') { return false; }
  jQuery('#'+form_id+' .nform_res').hide();    
  jQuery('#'+form_id+' .nform_res').html('');

  var multi = 'true'; if (jQuery('.nform.no_allow_multi').length) { var multi = 'false'; }
  var emails = ''; if (jQuery('#emails_'+form_uniq).length) { var emails = jQuery('#emails_'+form_uniq).val(); }

  /* Animate Submit Button */
  var sub = jQuery('#'+form_id+'.nform .submit_button').text();
  jQuery('#'+form_id+'.nform .submit_button').html('');
  jQuery('#'+form_id+'.nform .submit_button').prop("disabled",true);


  var pd = jQuery('#'+form_id+'.nform .submit_button').css('height');
  jQuery('#'+form_id+'.nform .submit_button').addClass('loading_class');


  var fid = jQuery('#'+form_id+' .form_id').attr('val');
  var title = jQuery('#'+form_id+' .form_title').html();
  var emails = emails+','+window.finalEmails.join();

  jQuery.ajax({
    dataType: 'json',
    type: "POST",
    url: FormCraftJS.ajaxurl,
    data: 'id='+fid+'&action=formcraft_submit&'+jQuery('#'+id).mySerialize(form_id)+jQuery('#'+form_id+' :input').not('.no_show').serialize()+'&title='+title+'&emails='+emails+'&multi='+multi,
    success: function (response)
    {
      jQuery('#'+form_id+'.nform .submit_button').prop("disabled",false);

      jQuery('#'+form_id+'.nform .submit_button').text(sub);
      jQuery('#'+form_id+'.nform .submit_button').removeClass('loading_class');

      jQuery('#'+form_id+' .valid_show').css('display','none');

      if (response!=null)
      {
        if (response.sent=='false')
        {
          jQuery('#'+form_id+' .nform_res').html(""+response.msg);
          jQuery('#'+form_id+' .nform_res').removeClass('alert alert-success alert-error');
          jQuery('#'+form_id+' .nform_res').addClass('alert alert-error');
          if (response.errors!='none' && response.errors!='')
          {
            jQuery('#'+form_id+' .nform_res').slideDown(200);
          }
        }
        else if (response.sent=='true')
        {
          jQuery('#'+form_id+' .form_submit').slideUp();
          jQuery('#'+form_id+'.nform .submit_button').prop("disabled",true);

          var temp = '<div class="nform_res nform_success" style="display: none">'+response.msg+'</div>';
          jQuery(temp).insertAfter('#'+form_id+' .form_ul');
          

          jQuery('#'+form_id+' .form_ul li').slideUp(300);
          jQuery('#'+form_id+' .form_title').slideUp(300);
          jQuery('#'+form_id+' .form_ul').slideUp(300);
          jQuery('#'+form_id+'.nform_res').slideDown(400);
          setTimeout(function(){
            jQuery('#'+form_id+' .nform_res').animate({
              'paddingTop': '60px',
              'paddingBottom': '74px'
            }, 300);
          }, 300);

          if(jQuery('#'+form_id).height()>600)
          {
            jQuery('#'+form_id+' .anchor_trigger')[0].click();
          }

          if (response.errors!='none' && response.errors!='')
          {
            jQuery('#'+form_id+' .nform_res').slideDown(200);
          }

          jQuery('#'+form_id+' .nform_res').removeClass('alert alert-success alert-error');

          setTimeout(function () {
            jQuery('.sticky_close').trigger('click');
          }, 3000);

          setTimeout(function ()
          {
            jQuery('.fcclose').trigger('click');
          }, 3000);

          if (window.finalRedirect && window.finalRedirect!='' && typeof window.finalRedirect!='undefined')
          {
            window.location.href=window.finalRedirect.slice(-1)[0];
          }
          if (response.redirect && !(jQuery('.ff_c_t').length))
          {
            window.location.href=response.redirect;
          }
        }
        else if (typeof response.errors!='undefined')
        {
          for (var key in response)
          {
            if (response.hasOwnProperty(key)) 
            {

              if (jQuery(window).width() < 740) 
              {
                var temp = response[key];
                jQuery('#'+form_id+' .'+key).html(""+temp);

                jQuery('#'+form_id+' .valid_show.'+key).css({
                  'display': 'block'
                });
                jQuery('#'+form_id+' .valid_show.'+key).slideDown();
              } 
              else 
              {

                var temp = response[key];
                jQuery('#'+form_id+' .'+key).html(""+temp);

                jQuery('#'+form_id+' .valid_show.'+key).css('margin-left','100px');
                jQuery('#'+form_id+' .valid_show.'+key).css('display','inline');
                jQuery('#'+form_id+' .valid_show.'+key).css('opacity','0');

                var temp = response[key];

                jQuery('#'+form_id+'.one .'+key).animate(
                {
                  marginLeft:'-15px',
                  opacity: .85
                }, 300);
                jQuery('#'+form_id+'.two .'+key).animate(
                {
                  marginLeft:'-14px',
                  opacity: .85
                }, 300);
                jQuery('#'+form_id+'.three .'+key).animate(
                {
                  marginLeft:'-45%',
                  opacity: .85
                }, 300);
              }
            }   
          }

          jQuery('#'+form_id+'.nform .submit_button').text(sub);
          jQuery('#'+form_id+' .nform_res').html(response.errors);
          jQuery('#'+form_id+' .nform_res').removeClass('alert alert-success alert-error');
          jQuery('#'+form_id+' .nform_res').addClass('alert alert-error');
          if (response.errors!='none' && response.errors!='')
          {
            jQuery('#'+form_id+' .nform_res').slideDown(200);
          }

        }
        else
        {
          jQuery('#'+form_id+'.nform .submit_button').text(sub);
          jQuery('#'+form_id+' .nform_res').html('');
          jQuery('#'+form_id+' .nform_res').removeClass('alert alert-success alert-error');
          jQuery('#'+form_id+' .nform_res').addClass('alert alert-error');
        }
      }
      else
      {
        jQuery('#'+form_id+'.nform .submit_button').text(sub);
        jQuery('#'+form_id+' .nform_res').html('');
        jQuery('#'+form_id+' .nform_res').removeClass('alert alert-success alert-error');
        jQuery('#'+form_id+' .nform_res').addClass('alert alert-error');
      }
    },
    error: function (response) 
    {
      jQuery('#'+form_id+'.nform .submit_button').prop("disabled",false);
      jQuery('#'+form_id+'.nform .submit_button').text(sub);
      jQuery('#'+form_id+'.nform .submit_button').removeClass('loading_class');
      jQuery('#'+form_id+' .nform_res').html('');
      jQuery('#'+form_id+' .nform_res').removeClass('alert alert-success alert-error');
      jQuery('#'+form_id+' .nform_res').addClass('alert alert-error');
    }
  });
}

function checkInput(type) 
{
  var input = document.createElement("input");
  input.setAttribute("type", type);
  return input.type == type;
}


function update_date()
{

  jQuery('.timepicker').timepicker({
    minuteStep: 5
  });

  jQuery('.timepicker').focus(function(){
    jQuery(this).timepicker('showWidget');
  });


  jQuery('.datepickermdy').each(function(){

    var id = this.id;
    var form = jQuery(this).parents('form').attr('id').replace('<!--STARTID-->','').replace('<!--ENDID-->','');
    var id2 = form+' #'+id;
    var temp = document.getElementById(id);
    temp.type = 'text';

    var lang = jQuery(this).attr('lang');
    var dmy = jQuery(this).attr('format');


    if (lang==undefined)
    {
      if (dmy==undefined) {dmy='mm-dd-yyyy';}
      jQuery('#'+id2).datepicker(
      {
        'format': dmy,
        'language': lang,
        'autoclose': true
      });
      datepicker_sd_ed();

    }
    else
    {
      jQuery.get(FormCraftJS.locale+"bootstrap-datepicker."+lang+".js", function(){
       jQuery('#'+id2).datepicker(
       {
        'format': dmy,
        'language': lang,
        'autoclose': true
      });
       datepicker_sd_ed();

     });

    }

  });




} // End of Function



function field_hover(id)
{
  var id = id.split('_');
  var id_dec = id[2];
  var id_inc = id[2];
  while (id_dec>=0)
  {
    jQuery('#'+id[0]+'_'+id[1]+'_'+id_dec).addClass('hover');
    jQuery('#'+id[0]+'_'+id[1]+'_'+id_inc).removeClass('hover_empty');
    id_dec--;
  }
  while (id_inc<=10)
  {
    id_inc++;
    jQuery('#'+id[0]+'_'+id[1]+'_'+id_inc).removeClass('hover');
    jQuery('#'+id[0]+'_'+id[1]+'_'+id_inc).addClass('hover_empty');
  }

}

function field_click(id, before)
{
  var val = jQuery('#'+id+' .show_true').val();

  var id = id.split('_');

  var id_inc = id[2];


  if (val)
  {
    jQuery('.reason_ta').css({'display':'block'});
    jQuery('#textarea_'+id[1]+'_div').slideDown('fast');
  }
  else
  {
    jQuery('#textarea_'+id[1]+'_div').slideUp('fast');
    jQuery('#textarea_'+id[1]+'_div textarea').val('');
  }


  var reason = jQuery('#'+id[0]+'_'+id[1]+'_'+id[2]).attr('data-reason');
  jQuery('#textarea_reason_'+id[1]).text(reason);

  if (before)
  {
    var id_dec = id[2]-1;
    jQuery('#'+id[0]+'_'+id[1]+'_'+id[2]).addClass('click');
    while (id_dec>=0)
    {
      jQuery('#'+id[0]+'_'+id[1]+'_'+id_dec).removeClass('click');
      id_dec--;
    }
  }
  else
  {
   var id_dec = id[2];
   while (id_dec>=0)
   {
    jQuery('#'+id[0]+'_'+id[1]+'_'+id_dec).addClass('click');
    id_dec--;

  }
}
while (id_inc<=30)
{
  id_inc++;
  jQuery('#'+id[0]+'_'+id[1]+'_'+id_inc).removeClass('click');
}


}

function field_hoverout(id)
{
  var id = id.split('_');
  var i = 0;
  while (i<=30)
  {
    jQuery('#'+id[0]+'_'+id[1]+'_'+i).removeClass('hover');
    jQuery('#'+id[0]+'_'+id[1]+'_'+i).removeClass('hover_empty');
    i++;
  }

}


function datepicker_sd_ed()
{

  jQuery('.datepickermdy').each(function(){

    var id = this.id;
    if (jQuery('#'+id).attr('days_r'))
    {
      var days = jQuery('#'+id).attr('days_r');
    }

    if (jQuery('#'+id).attr('min'))
    {
      if (jQuery('#'+id).attr('min').charAt(0)=='t')
      {
        var sd = new Date();
        var abc = parseInt(jQuery('#'+id).attr('min').substring(1));
        sd.setDate(sd.getDate()+abc);
        var dd = sd.getDate();
        var mm = sd.getMonth()+1;
        if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm}
          jQuery('#'+id).attr('min',sd.getFullYear()+'-'+mm+'-'+dd);
      }
      else
      {
        var sd = jQuery('#'+id).attr('min').split('-');
        sd = new Date( parseInt(sd[0]), parseInt(sd[1])-1, parseInt(sd[2]) );
      }
    }


    if (jQuery('#'+id).attr('max'))
    {
      if (jQuery('#'+id).attr('max').charAt(0)=='t')
      {
        var ed = new Date();
        var abc = parseInt(jQuery('#'+id).attr('max').substring(1));
        ed.setDate(ed.getDate()+abc+1);
        var dd = ed.getDate();
        var mm = ed.getMonth()+1;
        if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm}
          jQuery('#'+id).attr('max',ed.getFullYear()+'-'+mm+'-'+dd);
      }
      else
      {
        var ed = jQuery('#'+id).attr('max').split('-');
        ed = new Date( parseInt(ed[0]), parseInt(ed[1])-1, parseInt(ed[2])+1 );
      }
    }

    if (sd==undefined)
    {
      sd = new Date(1900,01,01);
    }
    if (ed==undefined)
    {
      ed = new Date(9999,01,01);
    }


    var dmy = jQuery('#'+id).attr('format');

    if (dmy==undefined) {dmy='mm-dd-yyyy';}
    var lang = jQuery('#'+id).attr('lang');

    jQuery.get(FormCraftJS.locale+"bootstrap-datepicker."+lang+".js", function(){
      jQuery('#'+id).datepicker(
      {
        'startDate': sd,
        'format': dmy,
        'endDate': ed,
        'daysOfWeekDisabled': days,
        'autoclose': true,
        'language': lang
      });
    });
  });


}


jQuery(document).ready(function () {

  jQuery('a.anchor_trigger').click(function(){
    jQuery('html, body').animate({
      scrollTop: jQuery( jQuery.attr(this, 'href') ).offset().top-50
    }, 500);
    return false;
  });

  jQuery('.fileupload-input').attr('multiple','true');

  jQuery('.nform').each(function(){

    var from = jQuery(this).attr('id').indexOf('<!--STARTID-->');
    var to = jQuery(this).attr('id').indexOf('<!--ENDID-->');
    if (jQuery(this).parent().find('.form_un').length==0) {return false;}
    if (from!=-1 && to!=-1)
    {
      jQuery(this).attr('id', jQuery(this).attr('id').replace(jQuery(this).attr('id').substring(from, to+12),jQuery(this).parent().find('.form_un').val()));
    }


    var from = jQuery(this).attr('id').indexOf('STARTID');
    var to = jQuery(this).attr('id').indexOf('ENDID');
    if (jQuery(this).parent().find('.form_un').length==0) {return false;}
    if (from!=-1 && to!=-1)
    {
      jQuery(this).attr('id', jQuery(this).attr('id').replace(jQuery(this).attr('id').substring(from, to+5),jQuery(this).parent().find('.form_un').val()));
    }


  });

  jQuery('body').on('input','.inst_change',function(){
    tooltipSet();
  });
  jQuery('body').on('click','.nform button',function(){
    if(jQuery(this).attr('type')=='reset')
    {
      setTimeout(function(){
        setupLabel();
      }, 100);
    }
  });

  jQuery('.hidden_fc_variables').each(function(){
    var selector = jQuery(this).attr('data-bindWhat');
    var modal = jQuery(this).attr('data-bindTo');
    jQuery(selector).attr('data-target',modal).attr('data-toggle','fcmodal');
  });

  if (jQuery('.ff_c_t').length==0)
  {
    jQuery('.nform .form_title').each(function(){
      if (jQuery(this).text()=='')
      {
        jQuery(this).css('border-bottom','0px');
      }
    });
    if(typeof window.GoogleFont!='undefined')
    {
      WebFont.load({
        google: { families: [window.GoogleFont] }
      });      
    }
  }

  jQuery('body').on('submit','.nform',function(event)
  {
    event.preventDefault;
    if (jQuery('.ff_c_t').length){return false;}
    var id = jQuery(this).attr('id').split('_');
    submit_formcraft_new(jQuery(this).attr('id'), id[0],id[1]);
    return false;
  });

  jQuery('.nform').on('click','.label_div',function(){
    jQuery(this).parents().children('label').trigger('click');
  });
  jQuery('.nform').on('click','.label_div',function(){
    jQuery(this).parents('label').trigger('click');
  });

    // Initialize PlaceHolders
    placeholders();
    datepicker_sd_ed();

    // Pre-Load Image
    var img=new Image();
    img.src=FormCraftJS.other+'/images/loader_4.gif';

    jQuery('.nform.no_submit_hidden .is_hidden').each(function(){
      jQuery(this).find('input').addClass('no_show');
      jQuery(this).find('textarea').addClass('no_show');
      jQuery(this).find('select').addClass('no_show');
    });

    /* Close Sticky Form and Modals on Esc */
    jQuery(document).keyup(function(e) {
      if (e.keyCode == 27) 
      { 
        jQuery('.close').trigger('click');
        jQuery('.sticky_on').trigger('click');
      }
    });




    jQuery('body').on("mouseenter",'.label_stars, .label_smiley' , function(){
     field_hover(jQuery(this).attr('id'), 0);
   });
    jQuery('body').on("mouseleave",'.label_stars, .label_smiley' , function(){
     field_hoverout(jQuery(this).attr('id'), 0);
   });


    jQuery('body').on('click','.label_stars input, .label_thumb input' , function(){
     field_click(jQuery(this).parent().attr('id'), 0);
   });
    jQuery('body').on('click','.label_smiley input' , function(){
     field_click(jQuery(this).parent().attr('id'), 1);
   });


    jQuery('body').on('touchstart','.label_stars, .label_thumb' , function(){
     field_click(jQuery(this).attr('id'), 0);
   });

    jQuery('body').on('touchstart','.label_smiley' , function(){
     field_click(jQuery(this).attr('id'), 1);
   });
    

    if(navigator.appName=='Microsoft Internet Explorer'){
      jQuery('.label_stars,.label_thumb').click(function(event){
       field_click(jQuery(this).attr('id'), 0);    
     });
      jQuery('.label_smiley').click(function(event){
       field_click(jQuery(this).attr('id'), 1);    
     });  
    }



    add_sliders();
    add_upload();
    update_date();


  // Update DatePicker on Change in Config
  jQuery('body').on('change','.language_select',function(){

    var lang = jQuery('.datepickermdy').attr('lang');
    var dmy = jQuery('.datepickermdy').attr('format');

    jQuery.get(FormCraftJS.locale+"bootstrap-datepicker."+lang+".js", function(){

     jQuery('.datepickermdy').datepicker('remove');
     jQuery('.datepickermdy').datepicker(
     {
      'format': dmy,
      'language': lang,
      'autoclose': true
    });

     datepicker_sd_ed();
   });

  });




  jQuery('body').on('change','.date_restrict',function(){
    datepicker_sd_ed();
  });

  if (jQuery('.check_no_conflict').length)
  {
    jQuery('body').removeClass('has-js');
  }
  else
  {
    jQuery('body').addClass('has-js');
  }

  jQuery('.tool').tooltip({trigger: 'hover', placement: 'top'});
  jQuery('.valid_show').css('display','none');
  jQuery('.nform_res').html('');
  jQuery('.upload_ul').html('');

  jQuery('.bootstrap-timepicker').each(function(){


    var abcd = jQuery(this).find('.bootstrap-timepicker-widget').length;
    if (abcd>1)
    {
      var abcd = jQuery(this).find('.bootstrap-timepicker-widget').length;
      jQuery(this).children('.bootstrap-timepicker-widget').slice(1).remove();
    }


  });


  

  jQuery('body').on("click",'.label_check, .label_radio' , function(){
    setupLabel('click');
  });
  jq_functions();
  tooltipSet();

  // Reset Captcha
  jQuery('body').on('click', '.c_image', function () {
    jQuery(this).attr('src', jQuery(this).attr('src')+'&'+Math.random())
  });

  setTimeout(function(){ jQuery('.c_image').trigger('click'); },500);

  setTimeout(function(){jq_functions();},1000);



});