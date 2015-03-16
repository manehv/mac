function show_submissions(page,search)
{

  if (typeof page=='string')
  {
    var data = 'action=formcraft_page&page='+page;
  }
  else
  {
    var data = 'action=formcraft_page&search='+search;
    var page = 0;
  }
  jQuery('#subs tbody').html('<tr><td colspan="6"><center><div style="margin: 30px auto; width: 30px;font-size: 14px; color: #888">loading...</div></center></td></tr>')

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    dataType: "json",
    data: data,
    success: function (response) {
      jQuery('.fc_pagination .active').removeClass('active');
      jQuery('.fc_pagination .page:nth-child('+page+')').addClass('active');

      if (response.length==0)
      {
        jQuery('#subs tbody').html('<center style="margin: 20px; font-size: 14px">No Results</center>');
        return false;
      }

      for (var sub in response)
      {
        var read = response[sub]['seen'] == '' || response[sub]['seen'] == null ? 'Unread' : 'Read';
        var shade = response[sub]['seen'] == '' || response[sub]['seen'] == null ? 'row_shade' : '';
        var id = response[sub]['id'];
        var name = response[sub]['name'] ? response[sub]['name'] : 'deleted';

        var row = '<tr id="sub_'+id+'" class="'+shade+'">';

        var row = row + '<td>'+id+'</td>';
        var row = row + '<td id="rd_'+id+'">'+read+'</td>';
        var row = row + '<td id="rd_'+id+'">'+response[sub]['added']+'</td>';
        var row = row + '<td>'+name+'</td>';

        var row = row + '<td><a class="fc-btn show-message" id="upd_'+id+'" data-target="#view_modal" data-toggle="fcmodal">View</a><div class="sub-content" id="sub-content-'+id+'">'+response[sub]['content']+'</div></td>';

        var row = row + '<td><i class="formcraft-trash sub_upd" id="del_'+id+'" title="Delete message"></i>&nbsp;<i class="formcraft-bookmark-empty sub_upd" id="read_'+id+'" title="Mark as unread"></i></td>';
        var row = row + '</tr>';
        var html = html + row;
      }
      jQuery('#subs tbody').html('');
      jQuery('#subs tbody').append(html);

    },
    error: function (response) {
      jQuery('#save_form_btn').html(jQuery('#save_form_btn').attr('data-error'));
      window.saving = false;
    }
  });   
}


function drawChart(id, from, to)
{
  jQuery('#chart-cover').addClass('loading')
  if (id)
  {
    var jsonData = jQuery.ajax({
      url: ajaxurl,
      dataType: "json",
      type: "POST",
      data: 'id='+id+'&action=formcraft_chart&from='+from+'&to='+to,
      async: false
    }).responseText;

  }
  else
  {
    var jsonData = jQuery.ajax({
      url: ajaxurl,
      dataType: "json",
      data: 'action=formcraft_chart&from='+from+'&to='+to,
      async: false
    }).responseText;
  }

  var jsonData = jQuery.parseJSON( jsonData );

  var totalViews = 0;
  for (values in jsonData.views)
  {
    totalViews = totalViews + parseInt(jsonData.views[values][1]);
  }
  var totalSubmissions = 0;
  for (values in jsonData.submissions)
  {
    totalSubmissions = totalSubmissions + parseInt(jsonData.submissions[values][1]);
  }

  jQuery({someValue: parseInt(jQuery('#tvs').text())}).animate({someValue: totalViews}, {
    duration: 900,
    easing:'swing',
    step: function() {
      jQuery('#tvs').text(Math.ceil(this.someValue));
    }
  });
  jQuery({someValue: parseInt(jQuery('#tss').text())}).animate({someValue: totalSubmissions}, {
    duration: 900,
    easing:'swing',
    step: function() {
      jQuery('#tss').text(Math.ceil(this.someValue));
    }
  });
  jQuery({someValue: parseInt(jQuery('#tcs').text())}).animate({someValue: (Math.round((totalSubmissions/totalViews)*10000)/100)}, {
    duration: 900,
    easing:'swing',
    step: function() {
      jQuery('#tcs').text(Math.ceil(this.someValue)+'%');
    }
  });
  setTimeout(function(){
    jQuery('#tvs').text(totalViews);
    jQuery('#tss').text(totalSubmissions);
    jQuery('#tcs').text((Math.round((totalSubmissions/totalViews)*10000)/100)+'%');
  }, 1000);    

  var plot = jQuery.plot("#chart-inner", [{
    data: jsonData.views,
    label: 'views',
    color: 'rgb(255, 175, 80)',
    bars: {show:true, align: 'center'}
  },{
    data: jsonData.submissions,
    label: 'submissions',
    color: 'rgb(28, 160, 28)',
    lines: {show:true}
  }], {
    series: {
      points: { show:true }
    },
    grid: {
      hoverable: true,
      clickable: true
    },
    xaxis: {
      mode: "categories"
    }
  });
  jQuery('#chart-cover').removeClass('loading')  

  jQuery("#chart-inner").bind("plothover", function (event, pos, item) {

    if (item) {
      if (previousPoint != item.dataIndex) {

        previousPoint = item.dataIndex;

        jQuery("#tooltip").remove();
        var x = Object.keys(item.series.xaxis.categories)[item.datapoint[0]],
        y = item.datapoint[1];

        showTooltip(item.pageX, item.pageY,
         y + ' ' + item.series.label + " on " + x);
      }
    } else {
      jQuery("#tooltip").remove();
      previousPoint = null;            
    }
  });


  jQuery("#chart-inner").bind("plotclick", function (event, pos, item) {
    if (item) {
      jQuery("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
      plot.highlight(item.series, item.datapoint);
    }
  });

}



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
};




jQuery(function () {

  jQuery('#import').fileupload({
    dataType: 'json',
    add: function (e, data) 
    {
      var type = data.files[0].name;
      var type = type.split('.');
      var type = type[1];
      if (type!='txt')
      {
        alert('Only .txt files');
        return false;
      }
      data.submit();
      jQuery('#fu-label').text('wait');
      jQuery('#import').prop("disabled",true);
    },
    done: function (e, resp) {
      if(resp.result.failed)
      {
        jQuery('#import').prop("disabled",false);
        jQuery('#fu-label').text(resp.failed);
      }
      else
      {
        jQuery('#import_form').val(resp.result.files.new_name);
        jQuery('#import').prop("disabled",true);
        jQuery('#fu-label').html('<i class="formcraft-ok" style="font-size: 10px"></i> Done');
        jQuery('#rand_b').trigger('click');
        setupLabel();
        jQuery('#import').parent().addClass('green');
      }


    },
    fail: function (e, data){

      jQuery('.import').prop("disabled",false);
      jQuery('#import_field_label').text('Failed');
      jQuery('#fu-label').text('Rety');

    }
  });  

});



jQuery(document).ready(function () {

  setTimeout(function(){
    jQuery('#fc-page-1').trigger('click');    
  }, 1000);





  /* Update Submissions */
  jQuery('body').on('click','.sub_upd, .show-message',function(){

    var id = jQuery(this).attr('id').split('_');
    var id2 = jQuery(this).attr('id');

    jQuery('#view_modal .modal-body').html(jQuery('#sub-content-'+id[1]).html());

    if (id[0]=='upd')
    {
      jQuery('#view_modal .modal-body').html(jQuery('#upd_text_'+id[1]).html());
      jQuery('#view_modal .myModalLabel').html(jQuery('#upd_name_'+id[1]).html());
      jQuery('#rd_'+id[1]).html('Read');
      jQuery(this).parent().parent().addClass('row_shade');
      jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        data: 'action=formcraft_sub_upd&type=upd&id='+id[1],
        success: function (response) {
          jQuery('#'+id2).parent().parent().removeClass('row_shade');          
        },
        error: function (response) {
        }
      });
    }
    else if (id[0]=='del')
    {
      jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        data: 'action=formcraft_sub_upd&type=del&id='+id[1],
        success: function (response) {
          if (response=='D')
          {
            jQuery('#'+id2).removeClass('formcraft-trash');
            jQuery('#'+id2).addClass('formcraft-ok').css('color','green');
          }
        },
        error: function (response) {
        }
      });
    }
    else if (id[0]=='read')
    {
      jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        data: 'action=formcraft_sub_upd&type=read&id='+id[1],
        success: function (response) {
          if (response=='D')
          {
            jQuery('#rd_'+id[1]).html('Unread');
            jQuery('#'+id2).parent().parent().addClass('row_shade');
          }
        },
        error: function (response) {
        }
      });
    }

  });

  // Set up DataTable
  if (jQuery('#subs').length)
  {
    jQuery('#ext').dataTable({
      "sPaginationType": "full_numbers"
    });
    jQuery('#files_manager_table').dataTable({
      "sPaginationType": "full_numbers"
    });
  }



  jQuery('#new_form').submit(function(event){
    event.preventDefault();
    var data = 'action=formcraft_add&import_form='+jQuery('#import_form').val()+'&name='+jQuery('#new_name').val()+'&desc='+jQuery('#new_desc').val()+'&type_form='+jQuery('[name="type_form"]:checked').val()+'&duplicate='+jQuery('[name="duplicate"]').val();
    jQuery('.response_ajax').html('processing ...');
    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: 'json',
      data: data,
      success: function (response) {
        if (response.Added)
        {
          jQuery('.response_ajax').html('Added');
          window.location.href = 'admin.php?page=formcraft_admin&id='+response.Added;
        }
        else if (response.Error)
        {
          jQuery('.response_ajax').html(response.Error);
        }
      },
      error: function (response) {
        jQuery('.response_ajax').html(response);
      }
    });    
  });


  jQuery('#subs_search').submit(function(event){
    event.preventDefault();
    show_submissions(false,jQuery('#search_query').val());    
  });  


  drawChart();
  jQuery('.datepicker-field').datepicker().on('changeDate', function(ev){
    jQuery(this).datepicker('hide');
    jQuery(this).trigger('change');
  });

  jQuery('.datepicker-field').wrap("<div class='datepicker-cover'></div>");

  jQuery('body').on('click', '.datepicker-cover', function(){
    jQuery(this).find('input').focus();
  });

  jQuery('body').on('click','.nav-main li', function(event)
  {
    event.preventDefault();
    var index = jQuery(this).parent().index();
    jQuery('.tab-content .tab-pane').removeClass('active');
    jQuery('.tab-content .tab-pane:eq('+index+')').addClass('active');
    jQuery('.nav-main table td li').removeClass('active');
    jQuery('.nav-main table td:eq('+index+') li').addClass('active');
  });


  jQuery("input.rand2").focus(function(){
    event.stopPropagation();
  });

  jQuery('#rand_a').change(function(){
    jQuery('#rand_aa').trigger('click');
    setupLabel();
  });

  jQuery('body').on('submit','#fc-pk',function(event){
    event.preventDefault();
    jQuery('#fc-pk .response').text('...');        
    jQuery.ajax({
      type: "GET",
      url: ajaxurl,
      data: 'key='+jQuery('#fc-pk-input').val()+'&action=formcraft_verifyLicense',
      dataType: "json",
      success: function(response)
      {
        if (response.message)
        {
          jQuery('#fc-pk .response').text(response.message);
        }
        else
        {
          jQuery('#fc-pk .response').text('Unknown error');
        }
      },
    });        
  });  


  jQuery('body').on('click','.delete_from_manager',function(){
    jQuery(this).html(jQuery(this).attr('data-loading'));
    if (jQuery(this).attr('data-name')){var data = 'name='+encodeURIComponent(jQuery(this).attr('data-name'));}
    else if (jQuery(this).attr('data-key')){var data = 'key='+encodeURIComponent(jQuery(this).attr('data-key'));}
    var id_this = this.id;
    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: 'action=formcraft_delete_file&'+data,
      success: function (response) {
        if (response=='Deleted')
        {
          jQuery('#'+id_this).removeClass('btn-danger');
          jQuery('#'+id_this).addClass('btn-success');
          jQuery('#'+id_this).html(jQuery('#'+id_this).attr('data-complete'));
        }
      },
      error: function (response) {
      }
    });
  });


  jQuery('#export').click(function(){
    window.open(Url.exporturl,'_blank');
  });


  jQuery('body').on('click', '.delete-row', function() {

   if (confirm('Are you sure you want to delete the form? You can\'t undo this action.')) {

    if(jQuery(this).hasClass('btn-danger'))
    {
      var this_id = jQuery(this).attr('id');
      jQuery(this).html(jQuery(this).attr('data-loading'));
      var id = jQuery(this).parent('td').parent('tr').attr('id');
      jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        data: 'action=formcraft_del&id='+id,
        success: function (response) {
          if (response=='Deleted')
          {
            jQuery('#'+this_id).html(jQuery('#'+this_id).attr('data-complete'));
            jQuery('#'+this_id).removeClass('btn-danger');
            jQuery('#'+this_id).addClass('btn-success');
          }
          else
          {
            jQuery('#'+this_id).html(jQuery('#'+this_id).attr('data-reset'));
          }
        },
        error: function (response) {
          alert("There was an error.");
        }
      });
    }

  }

});


  jQuery('body').on('click', '.row_click', function() {
    var id = jQuery(this).parent('tr').attr('id');
    window.location.href = 'admin.php?page=formcraft_admin&id='+id;
  });

      // Edit Form Name and Description
      jQuery("body").on('click', '.edit_btn', function(event){
        event.stopPropagation();
        jQuery(this).hide();
        jQuery(this).parent().children('.rand').hide();

        var name = jQuery(this).prev('a').html();
        jQuery(this).prev('input.rand2').show();
        jQuery(this).prev('input.rand2').focus();
        jQuery(this).next('a.save_btn').show();
      });

      jQuery('body').on('click','.rand2',function(event){
        event.stopPropagation();
      });

      jQuery("body").on('click', '.save_btn', function(event){
        event.stopPropagation();
        jQuery(this).hide();
        var this_id = jQuery(this).attr('id');
        var id = jQuery(this).attr('id').split('_');
        var val = jQuery(this).parents().children('.rand2').val();

        jQuery.ajax({
          url: ajaxurl,
          type: "POST",
          data: 'action=formcraft_name_update&name='+val+'&id='+id[1],
          success: function (response) 
          {
            if (response=='D')
            {
              jQuery('#'+this_id).parent().children('.rand').text(val);
              jQuery('#'+this_id).parent().children('input.rand2').hide();
              jQuery('#'+this_id).parent().children('.rand').show();
              jQuery('#'+this_id).parent().children('.edit_btn').show();

            }
            else
            {
              jQuery('#'+this_id).show();
              jQuery('#'+this_id).parent().children('input.rand2').hide();
              jQuery('#'+this_id).parent().children('.rand').show();
              jQuery('#'+this_id).parent().children('.edit_btn').show();
            }
          },
          error: function (response) 
          {
           jQuery('#'+this_id).show();
         }
       });


      });



jQuery('#stats_select, #chart-from, #chart-to').change(function(){
  var id = jQuery('#stats_select').val();
  var from = jQuery('#chart-from').val();
  var to = jQuery('#chart-to').val();
  drawChart(id, from, to);
});

jQuery('#chart-to').datepicker('remove');
jQuery('#chart-to').datepicker({'endDate': new Date()});

jQuery('#chart-from').change(function(){
  var sd = jQuery(this).val().split('/');
  sd = new Date( parseInt(sd[0]), parseInt(sd[1])-1, parseInt(sd[2]) );
  jQuery('#chart-to').datepicker('remove');
  jQuery('#chart-to').datepicker({'startDate': sd}).on('changeDate', function(ev){
    jQuery(this).datepicker('hide');
    jQuery(this).trigger('change');
  });
});




jQuery('#export_select').change(function(){
  var val = jQuery(this).val();
  if (val=='0')
  {
    var href = jQuery('#export_url').attr('href');
    var href = href.replace('?id='+href.substring(href.indexOf('?id=')+4, href.length),'?id=0');    
  }
  else
  {
    var href = jQuery('#export_url').attr('href');
    var href = href.replace('?id='+href.substring(href.indexOf('?id=')+4, href.length),'?id='+val);    
  }
  jQuery('#export_url').attr('href',href);
});

setupLabel();
jQuery('body').addClass('has-js');
jQuery('body').on("click",'.label_check, .label_radio' , function(){
  setupLabel();
});

jQuery('body').on('click', '.show-message', function(){
  var html = jQuery(this).parent().find('.sub-content').html();
  jQuery('#print_area').html(html);
});


jQuery('body').on('click','.fc_pagination > .page',function(){
  show_submissions(jQuery(this).text(),false);
});


});