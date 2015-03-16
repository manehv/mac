var operators = {
  '>': function(a, b) { return a > b },
  '=': function(a, b) { return a == b },
  '<': function(a, b) { return a < b }
};



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
    if (cook[key].substring(0,9)=='FormCraft' || cook[key].substring(1,10)=='FormCraft')
    {

      var form_val = cook[key].split('&');
      var limit_2 = form_val.length-2;
      for (key2 in form_val)
      {
        if (key2>limit_2)
        {
          break;
        }
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

        var field_temp = jQuery('#'+form_id+' [name="'+temp_val[0]+'"]');
        if (field_temp.length)
        {

          if (field_temp.hasClass('reason_ta'))
          {
            temp_val_2[1] = 'textarea';
          }

          if(temp_val_2[1]=='radio' || temp_val_2[1]=='check' || temp_val_2[1]=='matrix')
          {
            var field = jQuery('#'+form_id+' [name="'+temp_val[0]+'"][value="'+temp_val[1]+'"]');
            field.attr('checked', true);
            field.trigger('change');
          }
          else if (temp_val_2[1]=='stars' || temp_val_2[1]=='thumbs')
          {
            var field = jQuery('#'+form_id+' [name="'+temp_val[0]+'"][value="'+temp_val[1]+'"]');
            var temp_id = field.parent('label').attr('id');
            field.attr('checked', true);
            field_click(temp_id, 0);
            field.trigger('change');
          }
          else if (temp_val_2[1]=='smiley')
          {
            var field = jQuery('#'+form_id+' [name="'+temp_val[0]+'"][value="'+temp_val[1]+'"]');
            var temp_id = field.parent('label').attr('id');
            field.attr('checked', true);
            field_click(temp_id, 1);
            field.trigger('change');
          }
          else if (temp_val_2[1]=='text' || temp_val_2[1]=='para' || temp_val_2[1]=='email' || temp_val_2[1]=='dropdown' || temp_val_2[1]=='date' || temp_val_2[1]=='time')
          {
            var field = jQuery('#'+form_id+' [name="'+temp_val[0]+'"]');
            field.val(temp_val[1]);
            field.trigger('change');
          }
          else if(temp_val_2[1]=='slider')
          {
            var field = jQuery('#'+form_id+' [name="'+temp_val[0]+'"]');
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
            var field = jQuery('#'+form_id+' [name="'+temp_val[0]+'"]');
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

function save_user_form(id)
{
  var form_data = jQuery('#'+id).serialize();
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

setInterval(function(){
setupLabel();
}, 3000);

  jQuery('.modal_close').click(function(){

    var id = jQuery(this).parent('.modal').attr('id');
    jQuery('#'+id).removeClass('in');
    jQuery('.modal-backdrop').removeClass('in');

    setTimeout(function(){
      jQuery('#'+id).modal('hide');
    }, 600);
    jQuery('#'+id).css('display','block');

  });


  jQuery('body').on('click', '.input-append .add-on', function(){
    jQuery(this).parents('.input-append').children('input').focus();
  })


// Get Location and Save in Field
  jQuery('.nform .getlocation').each(function(){
    jQuery(this).val(document.location.href);
  });

  // Initializations / Fixes
  jQuery('.fc-modal').each(function(){

   var wd = jQuery(this).find('.nform').width();

   if (wd!=0)
   {
    jQuery(this).css('marginLeft',-jQuery(this).find('.nform').width()/2);
  }
  else
  {
    jQuery(this).css('marginLeft','-200px');
  }
  jQuery(this).css('backgroundColor','none');

  jQuery('body').append(this);
  jQuery(this).wrap('<div class="bootstrap" />')

});


  jQuery('#form_ul').css({
    'height':'auto',
    'opacity': 1
  });

  // Give Border Radius to Cover of Popup Form
  jQuery('.nform').each(function(){
    var rad = jQuery(this).css('borderRadius');
    jQuery(this).parent('.fly_form').css({
      'borderRadius':rad,
      'MozBorderRadius':rad,
      'WebkitBorderRadius':rad
    });
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


  // Conditional Logic (KeyUp)
  jQuery('body').on('keyup', '.field_class', function(){
  jQuery.fx.interval = 40;

    var form = jQuery(this).parents('form').attr('id');
    var val = jQuery(this).val();
    var json = jQuery.parseJSON(jQuery(this).attr('do_what'));
    final_do = new Array();


    for (key in json)
    {

      if (json[key].equals==undefined)
      {
        break;
      }
      if (jQuery(this).hasClass('datepickermdy'))
      {
        var format = jQuery(this).attr('format');
        var val = jQuery(this).val();
        val = parseDate(val, format);
        json[key].equals = parseDate(json[key].equals, format);
      }

      if (json[key].equals && json[key].doit && json[key].to && json[key].law)
      {
        // If all attributes for CLs are valid
        var op = json[key].law;

        if (jQuery.isNumeric(val))
        {
          val = parseInt(val);
        }
        if (jQuery.isNumeric(json[key].equals) &&  json[key].equals!='0')
        {
          json[key].equals = parseInt(json[key].equals);
        }


        if (operators[op](val, json[key].equals))
        {
          if (json[key].doit=='show')
          {
            final_do[json[key].to] = 'down';
            jQuery('#'+form+' .inline2.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery('#'+form+' .inline3.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery('#'+form+' .fe_'+json[key].to).addClass('changed_temp');
            jQuery('#'+form+' .fe_'+json[key].to).addClass('artificial');
          }
          else if (json[key].doit=='hide')
          {

            final_do[json[key].to] = 'up';
            jQuery('#'+form+' .fe_'+json[key].to).addClass('changed_temp');
            jQuery('#'+form+' .fe_'+json[key].to).addClass('artificial');
          }
        }
        else
        {
          var has = jQuery('#'+form+' .fe_'+json[key].to).hasClass('changed_temp');
          var arti = jQuery('#'+form+' .fe_'+json[key].to).hasClass('artificial');

          if (json[key].doit=='show' && has==false && arti==true)
          {
            final_do[json[key].to] = 'up';
            jQuery('#'+form+' .fe_'+json[key].to).removeClass('artificial');
          }
          else if (json[key].doit=='hide' && has==false && arti==true)
          {
            final_do[json[key].to] = 'down';
            jQuery('#'+form+' .inline2.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery('#'+form+' .inline3.fe_'+json[key].to).css({'display':'inline-block'});          
            jQuery('#'+form+' .fe_'+json[key].to).removeClass('artificial');
          }
        }
      } // End of IF

    } // End of For Loop
    jQuery('#'+form+' .changed_temp').removeClass('changed_temp');

    for (key1 in final_do)
    {
      if (final_do[key1]=='up')
      {
        jQuery('#'+form+' .fe_'+key1).slideUp();
        jQuery('#'+form+'.no_submit_hidden .fe_'+key1).find('input').addClass('no_show');
      }
      else
      {
        jQuery('#'+form+' .fe_'+key1).slideDown();
        jQuery('#'+form+' .fe_'+key1).find('input').removeClass('no_show');

      }
    }
  })


  // Conditional Logic (Change)
  jQuery('body').on('change', '.field_class', function(){
  jQuery.fx.interval = 40;


    var form = jQuery(this).parents('form').attr('id');
    var val = jQuery(this).val();
    var json = jQuery.parseJSON(jQuery(this).attr('do_what'));

    var temp_show = false;
    var temp_hide = false;


    for (key2 in json)
    {
      if (json[key2].equals==undefined)
      {
        break;
      }

      if (jQuery(this).hasClass('datepickermdy'))
      {
        var format = jQuery(this).attr('format');
        var val = jQuery(this).val();
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

      if (jQuery(this).hasClass('datepickermdy'))
      {
        var format = jQuery(this).attr('format');
        var val = jQuery(this).val();

        val = parseDate(val, format);

      }


      if (json[key].equals && json[key].doit && json[key].to && json[key].law)
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

        if (operators[op](val, json[key].equals))
        {
          if (json[key].doit=='show')
          {
            final_do[json[key].to] = 'down';
            jQuery('#'+form+' .inline2.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery('#'+form+' .inline3.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery('#'+form+' .fe_'+json[key].to).addClass('changed_temp');
            jQuery('#'+form+' .fe_'+json[key].to).addClass('artificial');
          }
          else if (json[key].doit=='hide')
          {
            final_do[json[key].to] = 'up';
            jQuery('#'+form+' .fe_'+json[key].to).addClass('changed_temp');
            jQuery('#'+form+' .fe_'+json[key].to).addClass('artificial');
          }
        }
        else
        {
          var has = jQuery('#'+form+' .fe_'+json[key].to).hasClass('changed_temp');
          var arti = jQuery('#'+form+' .fe_'+json[key].to).hasClass('artificial');
          if (json[key].doit=='show' && arti==true && has==false && temp_show!=json[key].to)
          {
            final_do[json[key].to] = 'up';
            jQuery('#'+form+' .fe_'+json[key].to).removeClass('artificial');
          }
          else if (json[key].doit=='hide' && arti==true && has==false && temp_hide!=json[key].to)
          {
            final_do[json[key].to] = 'down';
            jQuery('#'+form+' .fe_'+json[key].to).removeClass('artificial');
            jQuery('#'+form+' .inline2.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery('#'+form+' .inline3.fe_'+json[key].to).css({'display':'inline-block'});
          }
        }

      } // End of IF

    } // End of For Loop

    for (key1 in final_do)
    {
      if (final_do[key1]=='up')
      {
        jQuery('#'+form+' .fe_'+key1).slideUp();
        jQuery('#'+form+'.no_submit_hidden .fe_'+key1).find('input').addClass('no_show');
      }
      else
      {
        jQuery('#'+form+' .fe_'+key1).slideDown();
        jQuery('#'+form+' .fe_'+key1).find('input').removeClass('no_show');
      }
    }

    jQuery('#'+form+' .changed_temp').removeClass('changed_temp');

  });


  // Conditional Logic (CheckBoxes)
  jQuery('body').on('change', '.field_class_checkbox', function(){
  jQuery.fx.interval = 40;


    var form = jQuery(this).parents('form').attr('id');

    var name = this.name;
    var array_val = [];
    var val = jQuery(this).val();

    final_do = new Array();

    jQuery("input[name='"+name+"']").each(function(){
      if (jQuery(this).attr('checked'))
      {
        array_val.push(jQuery(this).val())
      }
    })

    var json = jQuery.parseJSON(jQuery(this).attr('do_what'));

    for (key in json)
    {
      if (
        json[key].hasOwnProperty('equals') &&
        json[key].hasOwnProperty('doit') &&
        json[key].hasOwnProperty('to') &&
        typeof json[key] != 'undefined'
        )
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

        // Check if ANY of the values of selected checkboxes match the EQUALS requirement of the CLs
        if (check_array(array_val, json[key].equals, json[key].law))
        {
          var to_temp = '#'+form+' .fe_'+json[key].to;
          if (json[key].doit=='show')
          {
            final_do[json[key].to] = 'down';
            jQuery('.inline2.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery('.inline3.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery(to_temp).addClass('changed_temp');
            jQuery(to_temp).addClass('artificial');
          }
          else if (json[key].doit=='hide')
          {
            final_do[json[key].to] = 'up';
            jQuery(to_temp).addClass('changed_temp');
            jQuery(to_temp).addClass('artificial');
          }
        }
        else
        {
          var id_is = '#'+form+' .fe_'+json[key].to;
          var to_temp = '#'+form+' .fe_'+json[key].to;
          var has = jQuery(id_is).hasClass('changed_temp');
          var arti = jQuery('#'+form+' .fe_'+json[key].to).hasClass('artificial');

          if (json[key].doit=='show' && arti==true && has==false)
          {
            final_do[json[key].to] = 'up';
            jQuery(to_temp).addClass('changed_temp');
            jQuery('#'+form+' .fe_'+json[key].to).removeClass('artificial');
          }
          else if (json[key].doit=='hide' && arti==true && has==false)
          {
            final_do[json[key].to] = 'down';
            jQuery('#'+form+' .inline2.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery('#'+form+' .inline3.fe_'+json[key].to).css({'display':'inline-block'});
            jQuery('#'+form+' .fe_'+json[key].to).removeClass('artificial');
          }
        }
      } // End of IF

    } // End of For Loop
    jQuery('.changed_temp').removeClass('changed_temp');

    for (key1 in final_do)
    {
      if (final_do[key1]=='up')
      {
        jQuery('#'+form+' .fe_'+key1).slideUp('fast');
        jQuery('#'+form+'.no_submit_hidden .fe_'+key1).find('input').addClass('no_show');
      }
      else
      {
        jQuery('#'+form+' .fe_'+key1).slideDown('fast');
        jQuery('#'+form+' .fe_'+key1).find('input').removeClass('no_show');

      }
    }
  })





  // Toggle Sticky Form and Fly Form
  
  // Hide the Fly Form to the Right
  var wd = jQuery('.fly_form').css('width');
  jQuery('.fly_form').css({
    'right': '-'+wd
  });

  // Open the Fly Form on Click
  jQuery('.fly_toggle').click(function(){
    var wd = jQuery('.fly_form form').css('width');
    wd = parseInt(wd);
    var hei = jQuery('.fly_form form').css('height');
    hei = parseInt(hei);

    jQuery('.fly_form').animate({
      'right': '50%',
      'top': '50%',
      'opacity': '1',
      'marginRight': '-'+wd/2+'px',
      'marginTop': '-210px'
    });


    jQuery('.fly_toggle').animate({
      'right': '-'+jQuery('.fly_toggle').css('width'),
      'opacity': '0'
    });
  });
  
  // Close the Fly Form on Click
  jQuery('.fly_form .close').click(function(){

    var wd = jQuery('.fly_form').css('width');

    jQuery('.fly_form').animate({
      'marginRight': '-50px',
    }, 200, function () {
      jQuery('.fly_form').animate({
        'right': '-'+wd,
        'opacity': '0'
      }, 150);
    });
    jQuery('.fly_toggle').animate({
      'right': '-1px',
      'opacity': '1'
    });
  });



  // Open the Sticky Form on Click
  jQuery('.sticky_toggle').click(function(){
    if (jQuery(this).hasClass('sticky_on'))
    {
      jQuery(this).removeClass('sticky_on');      
    }
    else
    {
      jQuery(this).addClass('sticky_on');      
    }

    if (jQuery(this).children('.icon-angle-up').hasClass('icon-angle-up'))
    {
      jQuery(this).children('.icon-angle-up').removeClass('icon-angle-up').addClass('icon-angle-down');      
    }
    else
    {
      jQuery(this).children('.icon-angle-down').removeClass('icon-angle-down').addClass('icon-angle-up');      
    }

    var pad = jQuery('.sticky_nform .nform .form_ul').css('paddingTop');
    pad = parseInt(pad);

    jQuery(this).parents('.sticky_cover').find('.sticky_nform').slideToggle(400);

  });
  
  // Remove Border Radius from Some Corners of Sticky Forms
  jQuery('.sticky_nform form').css({
    'borderTopRightRadius': '0px',
    'WebkitBorderTopRightRadius': '0px',
    'MozBorderTopRightRadius': '0px',
    'borderBottomRightRadius': '0px',
    'WebkitBorderBottomRightRadius': '0px',
    'MozBorderBottomRightRadius': '0px',
    'borderBottomLeftRadius': '0px',
    'WebkitBorderBottomLeftRadius': '0px',
    'MozBorderBottomLeftRadius': '0px'
  })


  setTimeout(function () {
    jQuery('.sticky_cover.open .sticky_toggle').trigger('click');
    jQuery('.fly_cover.open .fly_toggle').trigger('click');
  }, 500);

  jQuery('#form_ul').show();
  jQuery('.nform_res').hide();

  jQuery('.click').removeClass('click');



  var temp_1 = jQuery('.ref_link').text();

  if (temp_1==false)
  {
    jQuery('.ref_link').attr('href','');
    jQuery('.ref_link').attr('ng-href','');
  }



});

// Check if Value matches any value in the array Arr
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

  if (window.increment_form==true)
  {
    return false;
  }

  jQuery.ajax({
    dataType: 'json',
    type: "POST",
    url: FormCraftJS.ajaxurl,
    data: 'id='+id[0]+'&action=formcraft_increment2',
    success: function (response) {
      window.increment_form = true;
    }
  });
}

function placeholders()
{

  jQuery('.hasPlaceholder').each(function(){

    if ( jQuery(this).val() == '' )
    {
      jQuery(this).val(jQuery(this).attr('place'));

    }
  });

    // Add PlaceHolders
    var active = document.activeElement;
    jQuery('.placeholder :text, .placeholder textarea, .placeholder [type="email"]').focus(function () {
      if (jQuery(this).attr('place') != '' && jQuery(this).val() == jQuery(this).attr('place')) {
        jQuery(this).val('').removeClass('hasPlaceholder');
      }
    }).blur(function () {
      if (jQuery(this).attr('place') != '' && (jQuery(this).val() == '' || jQuery(this).val() == jQuery(this).attr('place'))) {
        jQuery(this).val(jQuery(this).attr('place')).addClass('hasPlaceholder');
      }
    });


    jQuery('.placeholder :text, .placeholder textarea, .placeholder [type="email"]').blur();
    jQuery(active).focus();

    jQuery('form').submit(function () {
      jQuery(this).find('.hasPlaceholder').each(function() {
        if ( jQuery(this).val() == jQuery(this).attr('place') )
        {
          jQuery(this).val('');
        }
      });
    });


    // Remove PlaceHolders
    var active = document.activeElement;
    jQuery('.no_placeholder :text, .no_placeholder textarea, .no_placeholder [type="email"]').each(function () {

      if (jQuery(this).val()==jQuery(this).attr('place'))
      {
        jQuery(this).val('').removeClass('hasPlaceholder');
      }

    });


  }


// Initialize the Sliders
function add_sliders(options)
{

  // File Upload JS Functions

  jQuery(function () {

    if (jQuery('.fileupload').length)
    {
      jQuery('.fileupload').fileupload({
        dataType: 'json',
        add: function (e, data) 
        {
          var this_id = '#'+jQuery(this).attr('id')+' ';
          var this_form = '#'+jQuery(this).parents('form').attr('id')+' ';
          var len = jQuery('.upload_ul li').length;
          var type = data.files[0].name;
          var type = type.split('.');
          var type = type[1];
          var allowed = jQuery(this_id).attr('filetype');

          var allowed = allowed.split(' ');
          var found = false;

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


          jQuery("<li class='li_upload_"+len+"'><div class='file-upload-cover'><span class='progress progress-striped'><span class='bar' style='width: 0%'></span></span><span class='cover_fileupload'></span></div></li>").appendTo('.upload_ul');

          data.fname = jQuery('<span class="file_name"></span>')
          .appendTo(this_form+'.li_upload_'+len+' .cover_fileupload')
          data.hidden = jQuery('<input type="hidden" name="files_file'+len+'">')
          .appendTo(this_form+'.li_upload_'+len+' .cover_fileupload')
          data.submit();
        },
        done: function (e, data) {
          if (jQuery('.upload_hidden').val()==null)
          {
            jQuery('.upload_hidden').val(1);
          }
          else
          {
            jQuery('.upload_hidden').val(parseInt(jQuery('.upload_hidden').val())+1);
          }
          jQuery('.upload_hidden').trigger('change');
          var len = jQuery('.upload_ul li').length-1;
          data.hidden.val(data.result.files[0].url);
          jQuery('<span class="name_bar">'+data.result.files[0].name+'</span>').appendTo('.li_upload_'+len+' .bar');
          jQuery('<i data-url="'+data.result.files[0].url+'" class="icon-trash" title="Delete"></i>').appendTo(data.fname).click(function(){

            jQuery.ajax({
              url: FormCraftJS.ajaxurl,
              type: "POST",
              data: 'action=formcraft_delete_file&url='+encodeURIComponent(jQuery(this).attr('data-url')),
              success: function (response) {
                if (response=='Deleted')
                {
                  jQuery(data.fname).text('');
                  jQuery('<span style="color: red; font-weight: bold; margin-left: -20px">Deleted</span>').appendTo(data.fname);
                  jQuery('.upload_hidden').val(parseInt(jQuery('.upload_hidden').val())-1);
                }
              },
              error: function (response) {

              }
            });

          });

        },
        progressall: function (e, data) {
          var len = jQuery('.upload_ul li').length-1;
          var progress = parseInt(data.loaded / data.total * 100, 10);
          jQuery('.li_upload_'+len+' .progress .bar').css(
            'width',
            progress + '%'
            );
        }
      });    
}

});



jQuery('.nform .fileupload').attr('data-url',FormCraftJS.server);

jQuery('.slider').each(function(){

  var id = this.id.split('_');

  if (!(id[3]))
  {
    id[3] = 0;
  }
  if (!(id[4]))
  {
    id[4] = 100;
  }
  if (!(id[5]))
  {
    id[5] = Math.max(parseInt(id[4])/50, 1);
  }

  if (options && jQuery("#"+this.id).hasClass('ui-slider'))
  {

    jQuery( "#"+this.id ).slider('option', {
      min: parseInt(id[3]),
      max: parseInt(id[4]),
      step: parseInt(id[5])
    });
  }
  else 
  {
    jQuery( "#"+this.id ).slider({
      min: parseInt(id[3]),
      max: parseInt(id[4]),
      range: 'min',
      step: parseInt(id[5]),
      slide: function( event, ui ) 
      {
        jQuery( "#"+this.id+"_val" ).html( ui.value );
        jQuery( "#"+this.id+"_val2" ).val( ui.value );
      },
      change: function( event, ui ) 
      {
        jQuery( "#"+this.id+"_val" ).html( ui.value );
        jQuery( "#"+this.id+"_val2" ).val( ui.value );
      }
    });
  }

});

jQuery('.slider-range').each(function(){

  var id = this.id.split('_');

  if (!(id[3]))
  {
    id[3] = 0;
  }
  if (!(id[4]))
  {
    id[4] = 100;
  }
  if (!(id[5]))
  {
    id[5] = Math.max(parseInt(id[4])/50, 1);
  }

  if (options && jQuery("#"+this.id).hasClass('ui-slider'))
  {

    jQuery( "#"+this.id ).slider('option', {
      min: parseInt(id[3]),
      max: parseInt(id[4]),
      step: parseInt(id[5])
    });
  }
  else 
  {
    jQuery( "#"+this.id ).slider({
      min: parseInt(id[3]),
      max: parseInt(id[4]),
      step: parseInt(id[5]),
      range: true,
      values: [0, parseInt(id[4])*.3],
      slide: function( event, ui ) 
      {
        jQuery( "#"+this.id+"_val" ).html( ui.values[0]+' - '+ui.values[1] );
        jQuery( "#"+this.id+"_val2" ).val( ui.values[0]+', '+ui.values[1] );
      },
      change: function( event, ui ) 
      {
        jQuery( "#"+this.id+"_val" ).html( ui.values[0]+' - '+ui.values[1] );
        jQuery( "#"+this.id+"_val2" ).val( ui.values[0]+', '+ui.values[1] );
      }
    });
  }

})



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

// Setup the DatePicker
function jq_functions()
{
 setupLabel();
 jQuery('.label_stars').tooltip();
 jQuery('.vertical .label_stars').tooltip('destroy');
 jQuery('.label_thumb').tooltip();
 jQuery('.vertical .label_thumb').tooltip('destroy');
 jQuery('.label_smiley').tooltip();
 jQuery('.vertical .label_smiley').tooltip('destroy');

 jQuery('.ttip').tooltip();
 jQuery('.ttip').each(function(){
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
  jQuery('#'+id+'.nform input[type=radio], #'+id+'.nform input[type=checkbox]').each(function()
  {
    var name = this.name;
    var radio_check = jQuery("input[name='"+name+"']");
    if( radio_check.filter(':checked').length == 0 )
    {

      returning += '&'+name+'=';

    }         
  })
  return returning+"&";

};

// Submit the Form
function submit_formcraft(id)
{

  jQuery('#'+id+' .nform_res').slideUp(200);

  // animate Submit Button
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
    data: 'id='+fid+'&action=formcraft_submit&'+jQuery('#'+id).mySerialize(id)+jQuery('#'+id+' :input').not('.no_show').serialize()+'&title='+title,
    success: function (response) {
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
          if (response.errors!='none')
          {
            jQuery('#'+id+' .nform_res').slideDown(200);
          }
          placeholders();
        }
        else if (response.sent=='true') // Successfulf Submit
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

          if (response.errors!='none')
          {
            jQuery('#'+id+' .nform_res').slideDown(200);
          }
          jQuery('#'+id+' .nform_res').animate({
            'paddingTop': '40px',
            'paddingBottom': '40px'
          }, 200, function(){

            jQuery('#'+id+' .nform_res').animate({
              'paddingTop': '50px',
              'paddingBottom': '50px'
            }, 280);

          });

          jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');

          setTimeout(function () {
            jQuery('.sticky_on').trigger('click');
          }, 1000);

          setTimeout(function () {
            if (jQuery('.modal-backdrop.in').length)
            {
              jQuery('.modal_close').trigger('click');
            }
          }, 1000);


          if (response.redirect && !(jQuery('.ff_c_t').length))
          {
           window.location.href=response.redirect;
         }

       }
       else if (response.errors)
       {
        placeholders();
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
                marginLeft:'-5px',
                opacity: .85
              }, 300);
              jQuery('#'+id+'.two .'+key).animate(
              {
                marginLeft:'-14px',
                opacity: .85
              }, 300);
              jQuery('#'+id+'.three .'+key).animate(
              {
                marginLeft:'-40%',
                opacity: .85
              }, 300);

            }


          }   
        }

        jQuery('#'+id+'.nform .submit_button').text(sub);
        jQuery('#'+id+' .nform_res').html(response.errors);
        jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');
        jQuery('#'+id+' .nform_res').addClass('alert alert-error');
        if (response.errors!='none')
        {
          jQuery('#'+id+' .nform_res').slideDown(200);
        }

      }
      else // Do if response.sent and .errors are not true
      {
        jQuery('#'+id+'.nform .submit_button').text(sub);
        jQuery('#'+id+' .nform_res').html('');
        jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');
        jQuery('#'+id+' .nform_res').addClass('alert alert-error');
        placeholders();
      }


    }
    else // Do if Response = null
    {
      jQuery('#'+id+'.nform .submit_button').text(sub);
      jQuery('#'+id+' .nform_res').html('');
      jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');
      jQuery('#'+id+' .nform_res').addClass('alert alert-error');
      placeholders();
    }

  }, // Success
  error: function (response) 
  {
    jQuery('#'+id+'.nform .submit_button').prop("disabled",false);
    jQuery('#'+id+'.nform .submit_button').text(sub);
    jQuery('#'+id+'.nform .submit_button').removeClass('loading_class');
    jQuery('#'+id+' .nform_res').html('');
    jQuery('#'+id+' .nform_res').removeClass('alert alert-success alert-error');
    jQuery('#'+id+' .nform_res').addClass('alert alert-error');
    placeholders();
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
    var temp = document.getElementById(id);
    temp.type = 'text';


    var lang = jQuery(this).attr('lang');
    var dmy = jQuery(this).attr('format');

    if (lang==undefined)
    {
      if (dmy==undefined) {dmy='mm-dd-yyyy';}
      jQuery('#'+id).datepicker('remove');
      jQuery('#'+id).datepicker(
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

       jQuery('#'+id).datepicker('remove');
       jQuery('#'+id).datepicker(
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
          jQuery('#'+id).attr('max',ed.getFullYear()+'-'+mm+'-'+dd);      }
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


      jQuery('#'+id).datepicker('remove');
      var dmy = jQuery('#'+id).attr('format');

      if (dmy==undefined) {dmy='mm-dd-yyyy';}



      var lang = jQuery('#'+id).attr('lang');


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


}


jQuery(document).ready(function () {

  jQuery('body').addClass('has-js');


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
    });

// Close Sticky Form and Modals on Esc
jQuery(document).keyup(function(e) {

  if (e.keyCode == 27) 
  { 
    jQuery('.close').trigger('click');
    jQuery('.sticky_on').trigger('click');
  }
});




jQuery('body').on("mouseenter",'.label_stars' , function(){
 field_hover(jQuery(this).attr('id'), 0);
});
jQuery('body').on("click",'.label_stars input' , function(){
 field_click(jQuery(this).parent().attr('id'), 0);
});
jQuery('body').on("mouseleave",'.label_stars' , function(){
 field_hoverout(jQuery(this).attr('id'), 0);
});



jQuery('body').on("click",'.label_thumb' , function(){
jQuery(this).find('input').attr('checked','checked');
jQuery(this).find('input').trigger('input');
jQuery(this).find('input').trigger('change');
  setTimeout(function(){
    setupLabel();
  },100);
});

jQuery('body').on("click",'.label_tick, .label_radio' , function(){
setupLabel();
});


jQuery('body').on("mouseenter",'.label_smiley' , function(){
 field_hover(jQuery(this).attr('id'), 1);
});
jQuery('body').on("click",'.label_smiley input' , function(){
 field_click(jQuery(this).parent().attr('id'), 1);
});
jQuery('body').on("mouseleave",'.label_smiley' , function(){
 field_hoverout(jQuery(this).attr('id'), 1);
});




  // Call Sliders
  add_sliders();

  // Initialize DatePicker
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

  jQuery('.tool').tooltip({trigger: 'hover', placement: 'top'});
  jQuery('.valid_show').css('display','none');
  jQuery('.nform_res').css('display','none');
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

  // Reset Captcha
  jQuery('body').on('click', '.c_image', function () {
    jQuery(this).attr('src', jQuery(this).attr('src')+'&'+Math.random())
  });

  setTimeout(function(){jq_functions();},1000);



});