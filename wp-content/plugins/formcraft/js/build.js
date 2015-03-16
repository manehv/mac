  function jq_click_before(id)
  {
   var id2 = parseInt(id)-1;
   jQuery('.fe_'+id2).trigger('click');
  }


jQuery(document).ready(function () {

jQuery( ".image_cap_cover" ).draggable({ 
  containment: ".nform",
  scroll: false,
  drag: function(event, ui) {
    var id = jQuery(this).attr('id');
    jQuery("#"+id+"l").val(ui.position.left+'px');
    jQuery("#"+id+"t").val(ui.position.top+'px');

    jQuery("#"+id+"l").trigger('input');
    jQuery("#"+id+"t").trigger('input');
  }
});

  jQuery('#test_email').click(function()
  {
    var id = jQuery('.form_id').attr('val');
    var this_id = jQuery(this).attr('id');
    var btn_text = jQuery(this).html();

    jQuery('#test_response').slideUp(200);
    jQuery(this).attr('temp', btn_text);
    jQuery(this).html('Sending');

    window.text_sending = true;

    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: 'action=formcraft_test_email&id='+id,
      success: function (response) {
        window.text_sending = false;
        jQuery('#test_response').html(response);
        jQuery('#test_response').slideDown(200);
        jQuery('#'+this_id).html(jQuery('#'+this_id).attr('temp'));
        jQuery('#'+this_id).attr('temp','');
      },
      error: function (response) {
        window.text_sending = false;
        jQuery('#'+this_id).html(jQuery('#'+this_id).attr('temp'));
        jQuery('#test_response').html('Failed');
        jQuery('#test_response').slideDown(200);
        jQuery('#'+this_id).attr('temp','');
      }
    });
  });


  window.saving = false;

  jQuery( ".con_slider" ).each(function( index ) {
    var id_is = jQuery(this).attr('id');
    var val = jQuery( "#"+id_is+"_v" ).val();

    jQuery( "#"+id_is ).slider('option', {
      value: val
    });
  });




  // Show Field Options
  jQuery('body').on('click','.nform_li', function()
  {

    var id = this.id.split('_');
    jQuery('.nform_li').removeClass('nform_li_before');
    jQuery(this).addClass('nform_li_before');

    jQuery('.nform_edit_div').hide();

    jQuery('#edit_'+id[1]).show();
    jQuery('#edit_'+id[1]+" .accordion-body").show();


    if(jQuery('#edit_'+id[1]).css('display')!='block')
    {
      jQuery('#edit_'+id[1]).show();
    }

  });

  jQuery('body').on('click','.min-btn', function()
  {
    // Minimize Field Options
    jQuery('.nform_edit_div').stop().slideUp();
    
    // Remove Focus on Minimize
    jQuery('.nform_li_before').each(function(){
      jQuery(this).removeClass('nform_li_before');
    });

  });




  setInterval(function()
  {
    if (window.saving==false)
    {
      save_formcraft();
    }
  }
  , 180000);

  setInterval(function(){add_sliders(1);},3000);
  setInterval(function(){placeholders();},2000);

  jQuery('.btn-toggle').click(function(){
    if (jQuery(this).hasClass('active'))
    {
      jQuery(this).removeClass('active');
    }
    else 
    {
      jQuery(this).addClass('active');
    }
  });

  jQuery('body').on('mouseover', '#well .accordion-toggle', function() {
    var id = jQuery(this).attr('id').substring(4,3);
    jQuery('#fe'+id).addClass('fehover');
  });
  jQuery('body').on('mouseout', '#well .accordion-toggle', function() {
    var id = jQuery(this).attr('id').substring(4,3);
    jQuery('#fe'+id).removeClass('fehover');
  });

  jQuery('body').on('mouseover', '#well .accordion-body.collapse', function() {
    var id = jQuery(this).attr('id').substring(8,11);
    jQuery('#fe'+id).addClass('fehover');
  });
  jQuery('body').on('mouseout', '#well .accordion-body.collapse', function() {
    var id = jQuery(this).attr('id').substring(8,11);
    jQuery('#fe'+id).removeClass('fehover');
  });


 }); // End of Document Ready


