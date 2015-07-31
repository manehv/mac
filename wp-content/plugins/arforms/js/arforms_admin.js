var switchery = new Array();
jQuery.fn.extend({
	insertAt : function(index, string) {
		return jQuery(this).val( jQuery(this).val().substring(0, index) + string + jQuery(this).val().substring(index) );
	}
});

function stopEnterKey(evt) {
	var evt = (evt) ? evt : ((event) ? event : null);
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	
	if ((evt.keyCode == 13) && (node.type == "text")) 
	{ 
		return false; 
	}
}
document.onkeypress = stopEnterKey;

jQuery(document).ready(function($)
{
	 var screenWidth = window.screen.width;
	 var screenHeight = window.screen.height;
	 
    jQuery("#arfmainfieldlist").css('height',screenHeight+"px");
   
jQuery(window).scroll(function () {

   var screenWidth = window.screen.width, screenHeight = window.screen.height;
	var newwidth =  screenWidth - (385+157);
	var newheight = screenHeight - 300; 
	var iframeheight = screenHeight - 345;
	var tabheight = screenHeight - 345;
});
var verifying = false;
jQuery('#verify-purchase-code').click(function () {
	
	var cust_name = jQuery('#li_customer_name').val();
	var cust_email = jQuery('#li_customer_email').val();
	var license_key = jQuery('#li_license_key').val();
	var domain_name = jQuery('#li_domain_name').val();
	
	if(cust_name=='') {
			jQuery('#li_customer_name').css('border-color', '#ff0000');
			jQuery('#li_customer_name_error').css('display', 'block');
		} else {
			jQuery('#li_customer_name').css('border-color', '#BCCBDA');
			jQuery('#li_customer_name_error').css('display', 'none');	
		}

	if(license_key=='') {
			jQuery('#li_license_key').css('border-color', '#ff0000');
			jQuery('#li_license_key_error').css('display', 'block');
		} else {
			jQuery('#li_license_key').css('border-color', '#BCCBDA');
			jQuery('#li_license_key_error').css('display', 'none');	
		}
	
	if(domain_name=='') {
			jQuery('#li_domain_name').css('border-color', '#ff0000');
			jQuery('#li_domain_name_error').css('display', 'block');
		} else {
			jQuery('#li_domain_name').css('border-color', '#BCCBDA');
			jQuery('#li_domain_name_error').css('display', 'none');	
		}
		
		if(cust_name=='' || license_key=='' || domain_name==''){
			return false;
		}
			
	jQuery('#license_loader').css('display', 'inline');
			
	jQuery.ajax({
		
		type:"POST",url:ajaxurl,

		data:"action=arfverifypurchasecode&cust_name="+cust_name+"&cust_email="+cust_email+"&license_key="+license_key+"&domain_name="+domain_name,

		success: function(html)
		{ 
			jQuery('#license_loader').css('display', 'none');
			if(html == "VERIFIED")
			{
				jQuery('#license_success').css('display', '');
				jQuery('#license_error').css('display', 'none');
			}
			else
			{
				jQuery('#license_error').html(html);
				jQuery('#license_error').css('display', '');
				jQuery('#license_success').css('display', 'none');
			}
		}

	});
	
	return false;

});

	try {
		$(".frm-bulk-select-class").selectpicker();
	}catch(err) {
		jQuery.fn.extend({
			selectpicker : function(index, string) {
				return;
			}
		});
	}
//$(".frm-bulk-select-class").selectpicker();

$('a[class="openpreview"]').click(function(){
	var frameSrc = $(this).attr("data-url");
	var modalheight = jQuery(window).height();
	var modalwidth = jQuery(window).width();
	var getModalWidth = Number(modalwidth) * 0.80;
	var getModalLeftWidth = (Number(modalwidth) * 0.20) / 2;
	var getModalHeight = Number(modalheight) - 100;
	var modalbodyheight = getModalHeight - 144 + 82;
	var loaderheight = (modalbodyheight / 2) - 50;
	var loaderleft = (getModalWidth / 2) - 50;
	
	$('#form_preview_modal').attr('style','display:none; width:'+getModalWidth+'px; height:'+getModalHeight+'px; top:50px; left:'+getModalLeftWidth+'px');
	$('.arfmodal-body').attr('style','overflow:hidden; clear:both; padding:0; height:'+modalbodyheight+'px');
    	
	$('#form_preview_modal .arfdevices').removeClass('arfactive');
	$('#form_preview_modal #arfcomputer').addClass('arfactive');
	
	$('#form_preview_modal').attr('data-modalwidth', getModalWidth);
	$('#form_preview_modal').attr('data-modalleft', getModalLeftWidth);
	
	$('#form_preview_modal').on('show', function () {
		$('iframe').attr("style","display:none");												  
		$('.iframe_loader').attr("style",'display:block; top:'+loaderheight+'px; position: relative;');												  
        $('iframe').attr("src",frameSrc);
      
	});
    $('#form_preview_modal').arfmodal({show:true});
	$('#form_preview_modal #arfdevicepreview').load(function(){ $('.iframe_loader').attr("style",'display:none'); $('iframe').attr("style","display:block"); });
});

var validate1 = function() {
 
  if(event.target.name=='inplace_value')
  {
	  if ( event.which == 13 ) {
		   event.preventDefault();
	  }
  }
  else
  {
	  $('.arfmainformbuilder form:first').submit(function(){$('.inplace_field').blur();})		  
  }
 
};

	if(typeof(__ARFDEFAULTDESCRIPTION)!='undefined'){
		var def_desc=__ARFDEFAULTDESCRIPTION;
	}
	if(typeof(__ARFDEFAULTSECTION)!='undefined'){
		var def_section=__ARFDEFAULTSECTION;
	}
var form_id = $('#id').val();

window.onscroll=document.documentElement.onscroll=arfsetoffsetformenu;

arfsetoffsetformenu();

$("input[name='options[success_action]']").change(function(){

$('.success_action_box').hide();

if($(this).val()=='redirect')
{
	$('.success_action_redirect_box.success_action_box').fadeIn('slow');
}
else if($(this).val()=='page')
{
	$('.success_action_page_box.success_action_box').fadeIn('slow');
}
else
{
	$('.frm_show_form_opt').show();
	$('.success_action_message_box.success_action_box').fadeIn('slow');
}
});

if($('.widget-top').length>0)
{
	$('.widget-top,a.widget-action').click(function(){
		
		jQuery('.arffontstylesettingmainpopupbox').css('display', 'none');
		var currentwidgetid_old = $('.current_widget').attr('id'); 
		
		var currentwidgetid = $(this).closest('div.widget').attr('id');
		
		if( currentwidgetid_old == currentwidgetid){
			return false;
		}
		
		$('div.widget').removeClass('current_widget');
		
		$(this).closest('div.widget').addClass('current_widget');
		
		$(this).closest('div.widget').siblings().children('.widget-inside').slideUp('fast');
		
		if( $(this).closest('div.widget').children('.widget-inside').css('display')=="none" )
		{
			$(this).closest('div.widget').children('.widget-inside').slideDown('fast');
			return false;
		}
		
		if( $('.current_widget').length == 0 ){
			$('#tabformsettings .widget-top').trigger('click');
		}
	});
}

if($('.arfeditorformname').length>0)
{
	$('.arfmainformbuilder form:first').submit(function(){$('.inplace_field').blur();})
	
	$(".arfeditorfieldopt_desc").editInPlace({
		url:ajaxurl,params:"action=arfupdatefielddescription",default_text:def_desc,field_type:'textarea',textarea_rows:3
	});
	
	$(".arfoptioneditorfield, .arfoptioneditorfield_select, .arfoptioneditorfield_key").editInPlace({url:ajaxurl,params:"action=arfeditorfieldoption",default_text:'(Blank)' ,success:function(res){ 
																																															   arf_change_opt_val( jQuery(this).attr('data-fid'), jQuery(this).attr('id'), jQuery(this).text(), jQuery(this).attr('data-original') ); 
	   arf_change_opt_label( jQuery(this).attr('data-fid'), jQuery(this).attr('id'), jQuery(this).text() );																																														   } });
	
	$(".arfeditorfieldopt_divider_label").editInPlace({url:ajaxurl,params:"action=arfupdatefieldname", default_text:def_section, value_required:"false", success:function(){

update_cl_field_menu(); /* for conditional logic */
arf_update_name_dropdown();
jQuery(".sltstandard select").selectpicker();

} });
	
	var def_title = '(Click here to add text)';
	if( typeof(__ARFDEFAULTTITLE) != 'undefined' ){
		var def_title = __ARFDEFAULTTITLE;
	}
	$(".arfeditorfieldopt_label").not('.arfeditorfieldopt_divider_label').editInPlace({url:ajaxurl,params:"action=arfupdatefieldname", desc:def_title, value_required:"true", success:function(){
update_cl_field_menu(); /* for conditional logic */ 
arf_update_name_dropdown();
//change_password_field_dropdown(); // for password dropdown
jQuery(".sltstandard select").selectpicker();
																																											} });
	
	$('select[name^="item_meta"], textarea[name^="item_meta"]').css('float','left');
	$('input[name^="item_meta"]').not(':radio, :checkbox').css('float','left');

}


$('.arfcategorytabs a').click(function(){
	var t = $(this).attr('href');
	if(typeof(t)=='undefined'){ return false; }
	var c = t.replace('#', '.');
	var pro=$('#taxonomy-linkcategory .arfcategorytabs li').length > 2;
	$('#arfcurrenttab').val($(this).closest('li').attr('id'));
	$(this).closest('li').addClass('tabs active').siblings('li').removeClass('tabs active');
	if($(this).closest('div').find('.tabs-panel').length>0){ $(this).closest('div').children('.tabs-panel').hide(); }
	else{ $(this).closest('div.inside').find('.tabs-panel, .hide_with_tabs').hide();
	if($(this).closest('ul').hasClass('arfformsettingtabs')){
		if(t=='#html_settings'){if(pro){$('#taxonomy-linkcategory .arfcategorytabs li').hide();$('#frm_html_tab').show();}$('#frm_html_tags_tab').click();}
		else if($('#frm_html_tags_tab').is(':visible')){
			if(pro){$('#taxonomy-linkcategory .arfcategorytabs li').show();$('#frm_html_tab').hide();}
			$('#frm_insert_fields_tab').click();
		}
	}}
	$(t).show();
	$(c).show();
	return false;
});

$("input[name='arffths'] option").each(function(){


$(this).hover(function(){$('#arfshowcalimage').removeClass().addClass($(this).attr('id'));},'');


});

$("input[name='arffths']").change(function(){

var calender_url = jQuery('#calender_url').val();


var css=calender_url+$(this).val()+'_jquery-ui.css';

if(jQuery('#testiframe').contents().find("head").length)
{
	arfupdateformpreviewcss(css);	
}
else
{
	arfupdateformcss(css);
}


var themeName=$("input[name='arffths'] li[data-value='"+$(this).val()+"']").html();


$('input[name="arffthc"]').val($(this).val()); $('input[name="arffthn"]').val(themeName);


return false;


});

jQuery('.field_type_list > li').draggable({connectToSortable:'#new_fields',cursor:'move',helper:'clone',revert:'invalid',delay:10});

jQuery('ul.field_type_list, .field_type_list li, ul.frm_code_list, .frm_code_list li, .frm_code_list li a, #frm_adv_info #category-tabs li, #frm_adv_info #category-tabs li a').disableSelection();

if($.isFunction($.on)){

$('#new_fields').on('click', 'li.ui-state-default', function(evt){arfclickvisfunction(evt.target,$(this));});
$('.arfmainformbuilder').on('keyup', 'input[name^="item_meta"], textarea[name^="item_meta"]', function(){arfdefaulttriggers($(this))});
$('.arfmainformbuilder').on('change', 'select[name^="item_meta"]', function(){arfdefaulttriggers($(this))});

}else{

$('li.ui-state-default').live('click', function(evt){arfclickvisfunction(evt.target,$(this));});
$('.arfmainformbuilder input[name^="item_meta"], .arfmainformbuilder textarea[name^="item_meta"]').live('keyup', function(){arfdefaulttriggers($(this))});
$('.arfmainformbuilder select[name^="item_meta"]').live('change', function(){arfdefaulttriggers($(this))});	

}

if(document.getElementById('new_fields'))
{
	var plugin_image_path = $('#plugin_image_path').val();
	if($( window ).height() < '700')
	{
		if(plugin_image_path != '')
		{
			$('#new_fields').css({'background-image':'url("'+plugin_image_path+'/watermark.png")',"background-size":"275px 275px"});
		}
	}
	else
	{
		$('#new_fields').css({'background-image':'url("'+plugin_image_path+'/watermark.png")'});	
	}
	
}

$(document).on('keyup','#arfsubmitbuttontext',function(){
	var submit_button = $(this).val();
	if($('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html().length > 0)
	{
		$('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html(submit_button);
	}
	else if($('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html().length == 0)
	{
		$('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html(submit_button);	
	}
});

/*$('#arfsubmitbuttontext').keyup(function() {
	var submit_button = $(this).val();
	if($('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html().length > 0)
	{
		$('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html(submit_button);
	}
	else if($('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html().length == 0)
	{
		$('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html(submit_button);	
	}
});*/

jQuery('#success_message').delay(3000).fadeOut('slow');

if(document.cookie!=null)
	{
		var cookielist = document.cookie.split(';');							 	
		if(cookielist.length>0)
		{
			var wp_chk_version = "";	
			for(var i=0;i < cookielist.length;i++) {
				var c = cookielist[i];
				if(c.indexOf('wp_ver=1') == 0)
				{
					wp_chk_version = 1;	
				}
			}
		}
		if(wp_chk_version!=1)
		{
			jQuery(".sltstandard select").selectpicker(); 
			jQuery(".sltmodal select").selectpicker(); 
		}
	}
	else
	{
		jQuery(".sltstandard select").selectpicker(); 
		jQuery(".sltmodal select").selectpicker(); 
	}

	jQuery(".sltstandard select").selectpicker(); 
	jQuery(".sltmodal select").selectpicker();  

	jQuery(window).on("scroll", onScroll);
	
	jQuery('.arfsettingli').on('click', function(e){
		jQuery('.arfsettingli').removeClass('arfactive');
		jQuery(this).addClass('arfactive');			
		var id = jQuery(this).attr('id');
			id = id.replace('arfsetting_', '');	
		jQuery(window).off("scroll");
		if ( jQuery('#arf_'+id).is(':visible') )
		{
			jQuery('html, body').stop().animate({
				'scrollTop': jQuery('#arf_'+id).offset().top-150
			}, 700, 'swing', function () {
				jQuery(window).on("scroll", onScroll);
			});
		}
	});
	
	jQuery('.html_field_description').on('blur', function(){
		var $elm = jQuery(this);
		var input = $elm[0];
		if (input.setSelectionRange) {
			pos_start	= input.selectionStart;
			pos_end		= input.selectionEnd;
			jQuery(this).attr('data-startpos', pos_start);
		} 
	});
	
	if( jQuery.isFunction( jQuery().editInPlace ) )
	{
		if( typeof(__ARFDEFAULTDESCRIPTION) != 'undefined' ) {
			var def_desc = __ARFDEFAULTDESCRIPTION;
		}
		if( typeof(__ARFDEFAULTTITLE) != 'undefined' ){
			var def_title = __ARFDEFAULTTITLE;
		}
		var form_id = jQuery('#id').val();
		jQuery('.arfeditorformname').editInPlace({
			url:ajaxurl,params:"action=arfupdateformname&form_id="+form_id,value_required:"true", bg_over:"transparent", default_text:def_title
		});
		
		jQuery(".arfeditorformdescription").editInPlace({
			url:ajaxurl,params:"action=arfupdateformdescription&form_id="+form_id,value_required:"true", bg_over:"transparent", default_text:def_desc
		});
		
		jQuery(".arfsubmitbtn").editInPlace({
			url:ajaxurl,params:"action=arfsubmitbtn&form_id="+form_id,value_required:"true", bg_over:"transparent"
		});
		
		jQuery('.arfformeditpencil').on('click', function(){
			jQuery('.arfeditorformname').trigger('click');
			jQuery('.arfformedit').css('width', '98.7%');
			jQuery('.arfformeditpencil').hide();
		});
		
		jQuery('.arfdescriptioneditpencil').on('click', function(){
			jQuery('.arfeditorformdescription').trigger('click');
			jQuery('.arfdescriptionedit').css('width', '98.7%');
			jQuery('.arfdescriptioneditpencil').hide();
		});
		
		jQuery('.arfsubmiteditpencil').on('click', function(){
			jQuery('.arfsubmitbtn').trigger('click');
			jQuery('.arfsubmiteditpencil').hide();
			jQuery('.arfsubmitsettingpencil').hide();
			jQuery('.arfsubmitedit .greensavebtn').css('padding','5px');
		});
	}
	
	//CheckFieldPos_height();	
	
	CheckFieldPos(1,1);
	
	checkpage_breakpos();
	
	var browser_name = jQuery('#arf_browser_name').val();
	//console.log('b:'+browser_name);
	if( browser_name == 'Mozilla Firefox' ){
		var fixedheightofheader_footer = 385;	
	}else{
		var fixedheightofheader_footer = 375;
	}
	var fullwindowheight = (window.screen.height - 100);
	
	var remainingheight = Number(fullwindowheight) - fixedheightofheader_footer;
	
	jQuery('.widget-inside').css('max-height', remainingheight+"px");
	
	jQuery('.widget-inside').css('height', remainingheight+"px");
	
	if( jQuery.isFunction( jQuery().tooltipster ) )	
	{
		setTimeout(function(){
			jQuery('.arfhelptip').tooltipster({
				theme: 'arf_admin_tooltip',
				position:'top',
				contentAsHTML:true,
				onlyOne:true,
				multiple:true,
				maxWidth:400,
			});
		}, 10);
	}
	
	arfsetsubmitautowdith();
	
	jQuery('body').append('<div id="arfsaveformloader"></div>');
	
	var width = jQuery(window).width();
	var left_width	= ( jQuery('body').hasClass('folded') ) ? 36 : 160;
	var total_width		= width - left_width - 350 - 20;
	jQuery('#formeditorpart').css('width', total_width + 'px');
	
	var total_width_preview_div = total_width - 10;
	jQuery('#arf_main_style_tab').css('width', total_width_preview_div + 'px');
	
	var addtosite_width= width - left_width - 325 - 35;
	jQuery('.arfaddtosite_container').css('width', addtosite_width + 'px');
	
	jQuery(document).on('click','.arf_selectbox',function() {
		
		$this = jQuery(this);
		
		/*jQuery('.arf_selectbox').each(function(){
			if( $this.find('dd ul').attr('data-id') != jQuery(this).find('dd ul').attr('data-id') ){
				jQuery(this).find('dd ul').hide();
			}
		});*/
		
		jQuery(this).find('dd ul').toggle();
		var col_id = jQuery(this).find('dd ul').attr('data-column');
		if( jQuery(this).find('dd ul').is(":visible") ){
			var id = jQuery(this).find('dd ul').attr('data-id');
			var value = jQuery('#main_'+col_id).find("input#"+id).val();
			
			if( value != '' && id != '' ){
				jQuery(this).find('dd ul li').each(function(){
					if( jQuery(this).attr('data-value') == value ){
						
						var target = jQuery(this);
						target_position =  target.position().top;
						
						if( Math.floor(target_position) > jQuery(this).parent().height() ){
							jQuery(this).parent().animate({scrollTop:target.position().top},0);
						}
					}
				});
			}
		}
	});
	
	jQuery(document).on('click','.arf_selectbox dt',function(){
															 
		var this_parent = jQuery(this).parent();
		
		if( jQuery(this).parent().find('dd ul').is(":visible") == false ){
			//alert("check data");
			jQuery('dd ul').not(this).hide();
			
			var ul_h = this_parent.find('dd ul').height();
			var dd_h = this_parent.height();
			var win_h = jQuery(window);
			var offsetTop = this_parent.offset().top - win_h.scrollTop();
			//alert(win_h.height() +'-'+ offsetTop +'-'+ dd_h +'<'+ ul_h)
			
			if (win_h.height() - offsetTop - dd_h < ul_h) {
				//this_parent.find('dd ul').css('bottom', '0px');
				this_parent.find('dd ul').addClass('arfdropdownoptiontop');
			}else {
				//this_parent.find('dd ul').css('bottom', 'inherit');
				this_parent.find('dd ul').removeClass('arfdropdownoptiontop');
			}
			
			var chk_field_enabled = this_parent.find('dd ul').attr("data-id");
			//alert(chk_field_enabled);
			var isDisabled = jQuery("#"+chk_field_enabled).prop('disabled');
			var isReadonly = jQuery("#"+chk_field_enabled).prop('readonly');
			if(isDisabled || isReadonly)
			{
				//alert("disabled");
				//jQuery(this).find('span').show();
				//jQuery(this).find('input').hide();
				if(isDisabled)
				{
					this_parent.find('dd ul').hide();
					this_parent.find('dt').addClass("arf_disable_selectbox");
				}
				else if(isReadonly)
				{
					this_parent.find('dd ul').hide();
				}
				return false;
			}else {
				//alert("not disabled");
				//jQuery(this).find('span').hide();
				//jQuery(this).find('input').show();
				//jQuery(this).find('input').focus();
				this_parent.find('dt').removeClass("arf_disable_selectbox");
			}
		} else {
			var chk_field_enabled = this_parent.find('dd ul').attr("data-id");
			//alert(chk_field_enabled);
			var isDisabled = jQuery("#"+chk_field_enabled).prop('disabled');
			if(isDisabled)
			{
				this_parent.find('dd ul').hide();
				return false;
			}else {
				this_parent.find('dd ul').show();
			}
		}
	});

	jQuery(document).on('keyup','.arf_selectbox dt input',function(){
		
		jQuery(this).parent().parent().find('dd ul').scrollTop();
		
		var value = jQuery(this).val();
		value = value.toLowerCase();
		
		jQuery(this).parent().parent().find('dd ul').show();
		
		jQuery(this).parent().parent().find('dd ul li').each(function(x){
			var text = jQuery(this).attr('data-label').toLowerCase();
			(text.indexOf(value) != -1 ) ? jQuery(this).show() : jQuery(this).hide();	
		});
			
	});
	
	jQuery(document).on('click',".arf_selectbox dd ul li",function(e) {
		jQuery(document).find('.arf_selectbox:active dd ul').hide();
		var text = jQuery(this).html();
		//jQuery(this).parent().parent().parent().find('dt span').html(jQuery(this).data('label'));
		jQuery(this).parent().parent().parent().find('dt span').html(jQuery(this).attr('data-label'));
		jQuery(this).parent().parent().parent().find('dt span').show();
		jQuery(this).parent().parent().parent().find('dt input').val( jQuery(this).data('label') );
		jQuery(this).parent().parent().parent().find('dt input').hide();
		var id = jQuery(this).parent().attr('data-id');
		var value = jQuery(this).attr('data-value');
		var column_id = jQuery(this).parent().attr('data-column');
		if( typeof( column_id ) !== 'undefined' ){
			jQuery('#main_'+column_id).find('input#'+id).val( value );
			jQuery('#main_'+column_id).find('input#'+id).trigger('change');
		}else{
			jQuery('input#'+id).val( value );
			jQuery('input#'+id).trigger('change');
		}
		
		
		jQuery(this).parent().find('li').show();
		
	});
	
	jQuery(document).bind('click', function(e) {
		var $clicked = jQuery(e.target);
		if (! $clicked.parents().hasClass("arf_selectbox")){
			jQuery(".arf_selectbox dd ul").hide();
			jQuery('.arf_selectbox dt span').show();
			jQuery('.arf_selectbox dt input').hide();
			
			jQuery('.arf_autocomplete').each(function(){
				if( jQuery(this).val() == '' ){
					jQuery(this).val( jQuery(this).parent().find('span').html() );
				}
			});
		}
			
		jQuery('.arf_selectbox').removeClass('active');
	});
	
	if(!jQuery('#auto_responder').is(':checked'))
	{
		jQuery('#ar_email_message').attr('disabled', 'disabled');
		//jQuery('#wp-ar_email_message-wrap input').attr('disabled', 'disabled');
	}

	if(!jQuery('#chk_admin_notification').is(':checked'))
	{
		jQuery('#ar_admin_email_message').attr('disabled', 'disabled');
		//jQuery('#wp-ar_admin_email_message-wrap input').attr('disabled', 'disabled');
	}
	
	jQuery(".arfoptionul").sortable({
		axis:'y',							  					  	
		placeholder:'opt-placeholder',
		cursor:'move',
		opacity:0.80,
		forcePlaceholderSize:true,
		update:function(){
			var field_id	= jQuery(this).attr('id');
				field_id	= field_id.replace('arfoptionul_', '');
				
			var order = jQuery(this).sortable('serialize');
			jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arfupdateoptionorder&field_id="+field_id+"&"+order, success:function(res){ } });
			arfreordercheckradio( field_id );
		}
	});
	
});

function arfwidgetclickfunction(obj)
{
	return;
	if(obj.hasClass('widget-action')){ return; }
	if(obj.parents().hasClass('arf35trigger')){ return; }
	inside=obj.closest('div.widget').children('.widget-inside');
	if(inside.is(':hidden')){inside.slideDown('fast');}else{inside.slideUp('fast');}
}

function arfdefaulttriggers(obj)
{
	var n=obj.attr('name');
	if(typeof(n)=='undefined'){ return false; }
	var n=n.substring(10,n.length-1);
	arfdefaultshows(n,obj.val());	
}

function arfshowconditionaldiv(div,value,show_if,class_id)
{
	if(value==show_if){ jQuery(class_id+div).fadeIn('slow'); } else { jQuery(class_id+div).fadeOut('slow'); }
	
	if( jQuery('#chk_admin_notification').is(':checked') || jQuery('#auto_responder').is(':checked') ){
		jQuery('.email_show').css('display', '').fadeIn('slow');
	} else {
		jQuery('.email_show').css('display', 'none').fadeIn('slow');
	}
}

function addnewfrmfield(form_id,field_type)
{
	var pg_break_pre_first = jQuery("#page_break_first_pre_btn_txt").val();
	var pg_break_next_first = jQuery("#page_break_first_next_btn_txt").val();
	var pg_break_first_select = jQuery("#page_break_first_select").val();
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arfinsertnewfield&form_id="+form_id+"&field="+field_type+"&pg_break_pre_first="+pg_break_pre_first+"&pg_break_next_first="+pg_break_next_first+"&pg_break_first_select="+pg_break_first_select,
	
	success:function(msg){jQuery('#new_fields').append(msg); jQuery('#new_fields li:last .arfeditorfieldopt_label').click(); CheckFieldPos('0','0');
	if( field_type == 'break' ){
		checkpage_breakpos();
	}
	update_cl_field_menu();
	arf_update_name_dropdown();
	
	jQuery(".sltstandard select").selectpicker();
}

});

jQuery(".sltstandard select").selectpicker(); 

}

function arfmakerequiredfieldfunction(field_id,required,chkflag){

    var thisid='req_field_'+field_id;

	if(required=='0')
	{
		var switch_to='1';var atitle='Click to mark as not compulsory field.';var checked='checked="checked"';
		var invalid_msg = ( typeof(__ARFINVALID) != 'undefined' ) ? __ARFINVALID : 'This field cannot be blank.';
		var current_invalid_msg = jQuery('#arfrequiredfieldtext'+field_id).val();
			if( current_invalid_msg == ''){
				jQuery('#arfrequiredfieldtext'+field_id).val(invalid_msg);
			}
		jQuery('#arfrequiredfieldtext'+field_id).removeAttr('disabled');
	}
	else
	{
		var switch_to='0';var atitle='Click to mark as compulsory field.';var checked='';
		jQuery('#arfrequiredfieldtext'+field_id).attr('disabled','disabled');
	}
	
	jQuery('#'+thisid).attr('href', 'javascript:arfmakerequiredfieldfunction('+field_id+','+switch_to+',1)');
	jQuery('#'+thisid).attr('class', 'arfaction_icon arffieldrequiredicon alignleft arfcheckrequiredfield'+switch_to+'');
	jQuery('#'+thisid).attr('title', atitle);
	
	jQuery('#'+thisid).tooltipster('destroy');
	jQuery('#'+thisid).tooltipster({
		theme: 'arf_admin_tooltip',
		position:'top',
		contentAsHTML:true,
		onlyOne:true,
		content:atitle,
		multiple:true,
		maxWidth:400,
	});
	
	jQuery('#frm_'+thisid).attr('onchange', 'arfmakerequiredfieldfunction('+field_id+','+switch_to+',2)');
	if( checked == '' ){
		if(chkflag=='1')
		{
			switchery['frm_'+thisid].setPosition(true);
		}
		jQuery('#frm_'+thisid).attr('checked', false);
	} else {
		if(chkflag=='1')
		{
			switchery['frm_'+thisid].setPosition(true); 	
		}
		jQuery('#frm_'+thisid).attr('checked', 'checked');
	}
};

function arfplaceseparatevalue(field_id,required){

	jQuery('.field_'+field_id+'_option_key').toggle();
	
	if(jQuery('#separate_value_'+field_id).is(':checked')){
		var checked='checked="checked"';
	} else {
		var checked='';	
	}

	jQuery('.field_'+field_id+'_option').toggleClass('arfwithkey');
	
	if(checked != ''){ jQuery('#arf_field_'+field_id+'_opts').attr('style','width:540px;'); }else{ jQuery('#arf_field_'+field_id+'_opts').attr('style','width:330px;'); } 

	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arfupdateajaxoption&field="+field_id+"&separate_value=1"});
	
	if( field_id ){
		arfreordercheckradio( field_id );
	}
}

function arfdefaultshows(n,fval){
	if(fval){jQuery('#arfcleardefaultvalueonfocus_'+n+',#arfcleardefaultvalueonfocus_'+n+' a').css('visibility','visible').fadeIn('slow');}
	else{jQuery('#arfcleardefaultvalueonfocus_'+n+',#arfcleardefaultvalueonfocus_'+n+' a').css('visibility','visible').fadeOut('slow');}
}

function arfcleardefaultvalueonfocus(field_id, active, chkflag)
{
	jQuery('.arftooltip').fadeOut('fast');
    var thisid='clear_field_'+field_id;
    if (active=='1'){var switch_to='0';var new_class='arficoninactive';var t='Do not clear default text on focus.';var checked='';}
    else{var switch_to='1';var new_class='';var t='Clear default text on focus.';var checked='checked="checked"';}
    
	jQuery('#'+thisid).attr('href', 'javascript:arfcleardefaultvalueonfocus('+field_id+','+switch_to+',1)');
	jQuery('#'+thisid).attr('class', new_class +' arfaction_icon frm_reload_icon');
	jQuery('#'+thisid).attr('title', t);						

	jQuery('#frm_'+thisid).attr('onchange', 'arfcleardefaultvalueonfocus('+field_id+','+switch_to+',2)');
	if( checked == '' ){
		if(chkflag=='1')
		{
			switchery['frm_'+thisid].setPosition(true);	
		}
		jQuery('#frm_'+thisid).attr('checked', false);
	} else {
		if(chkflag=='1')
		{
			switchery['frm_'+thisid].setPosition(true);
		}
		jQuery('#frm_'+thisid).attr('checked', 'checked');	
	}
	
	
	if( active == 1 ){
		var field_value = jQuery('input[name="item_meta['+field_id+']"]').val();																 
		
		if( field_value === undefined ){
			var field_value = jQuery('textarea[name="item_meta['+field_id+']"]').val();
		}
		if( field_value === undefined ){
			var field_value = jQuery('select[name="item_meta['+field_id+']"]').val();
		}
		if( field_value == '' ){
			jQuery('#clear_field_'+field_id).css('visibility','hidden');
		}
	} else {
		jQuery('#arfcleardefaultvalueonfocus_'+field_id).show();		
		jQuery('#clear_field_'+field_id).show();
		jQuery('#clear_field_'+field_id).css('visibility','visible');
	}
	
};

function arfdefaultblank(field_id,active,chkflag)
{
	jQuery('.arftooltip').fadeOut('fast');
    var thisid='default_blank_'+field_id;
    if(active=='1'){var switch_to='0';var new_class='arficoninactive'; var t='Pass the validation with default value.';var checked='';}
	else{var switch_to='1';var new_class=''; var t='Do not pass the validation with default value.';var checked='checked="checked"';}

	jQuery('#'+thisid).attr('href', 'javascript:arfdefaultblank('+field_id+','+switch_to+',1)');
	jQuery('#'+thisid).attr('class', new_class +' arfaction_icon frm_error_icon');
	jQuery('#'+thisid).attr('title', t);							
	
	jQuery('#frm_'+thisid).attr('onchange', 'arfdefaultblank('+field_id+','+switch_to+',2)');
	if( checked == '' ){
		if(chkflag=='1')
		{
			switchery['frm_'+thisid].setPosition(true);	
		}
		jQuery('#frm_'+thisid).attr('checked', false);
	} else {
		if(chkflag=='1')
		{
			switchery['frm_'+thisid].setPosition(true);
		}
		jQuery('#frm_'+thisid).attr('checked', 'checked');	
	}	
	
	
	if( active == 1 ){
		
		var field_value = jQuery('input[name="item_meta['+field_id+']"]').val();																 
		
		if( field_value === undefined ){
			var field_value = jQuery('textarea[name="item_meta['+field_id+']"]').val();
		}
		if( field_value === undefined ){
			var field_value = jQuery('select[name="item_meta['+field_id+']"]').val();
		}
		if( field_value == '' ){
			jQuery('#default_blank_'+field_id).css('visibility','hidden');
		}
	} else {
		jQuery('#arfcleardefaultvalueonfocus_'+field_id).show();
		jQuery('#default_blank_'+field_id).show();
		jQuery('#default_blank_'+field_id).css('visibility','visible');
	}
	
};

function arfaddnewfieldoption(field_id,table)
{
	if(jQuery('#separate_value_'+field_id).is(':checked')){ var sep_val = 1; } else { var sep_val = 0; }	
	
	var type = jQuery('#field_type_'+field_id).val();
	
	var is_checkbox_radio = ( type == 'radio' || type == 'checkbox' ) ? 1 : 0;
	
	var checkboxradio_len = ( type == 'radio' || type == 'checkbox' ) ? jQuery('#arf_checkboxradio_'+field_id+' .arf_check_radio_fields').length : 0;
	
	var data = {action:'arfaddnewfieldoption',field_id:field_id,t:table,sep_val:sep_val, is_checkbox_radio:is_checkbox_radio, checkboxradio_len:checkboxradio_len };		

	jQuery.post(ajaxurl,data,function(msg){	

		if( is_checkbox_radio == 1 )
		{
			var new_msg = msg.split('^|^');
					msg	= new_msg[0]; 
			var check_input = new_msg[1];  		
		} else {
			var new_msg = msg.split('^|^');
					msg	= new_msg[0]; 
			var option_input = new_msg[1];  	
		}
		
		if( ! msg ){
			return;
		}
		jQuery('#arf_field_'+field_id+'_opts ul.arfoptionul').append(msg);
		
		if( type == 'select' )
		{
			jQuery('select[name="item_meta['+field_id+']"]').append('<option value="'+option_input+'">'+option_input+'</option>');
		}
		
		jQuery("#arfmainfieldid_"+field_id+" .arfoptioneditorfield, #arfmainfieldid_"+field_id+" .arfoptioneditorfield_key").editInPlace({ url:ajaxurl,params:"action=arfeditorfieldoption", default_text:"(Blank)" ,success:function(res){ 
																																																									  		arf_change_opt_val( jQuery(this).attr('data-fid'), jQuery(this).attr('id'), jQuery(this).text(), jQuery(this).attr('data-original') ); 
																																																											
		arf_change_opt_label( jQuery(this).attr('data-fid'), jQuery(this).attr('id'), jQuery(this).text() );																																																														
																																							} });
		if(table=='row'){ jQuery('#frm-grid-'+field_id+' tr:last').after(msg);}
		
		if( is_checkbox_radio == 1 && check_input )
		{
			jQuery('#arf_checkboxradio_'+field_id).append('<div class="arf_check_radio_fields">'+check_input+'</div>');
		}
		arfcheckoptionlength( field_id );
		arfupdateoptionorder( field_id );
		arf_update_name_dropdown();
		if( is_checkbox_radio == 1 )
		{
			CheckFieldPos_height();
		}
	});

};

function arffielddelete_option(field_id, opt_key)
{
    jQuery.ajax({type:"POST",url:ajaxurl,
        data:"action=arfdeletefieldoption&field_id="+field_id+"&opt_key="+opt_key,
        success:function(msg){ 
		
			jQuery('#arffielddelete_'+field_id+'-'+opt_key+'_container').fadeOut("slow");
			
			var type = jQuery('#field_type_'+field_id).val();	
			
			jQuery('#arfoptionorder_'+field_id+'-'+opt_key).remove();
			
			if( type == 'select' ){
				arfreordercheckradio( field_id );
			}
			var is_checkbox_radio = ( type == 'radio' || type == 'checkbox' ) ? 1 : 0;	
			if( is_checkbox_radio == 1 )
			{
				jQuery('#arflbl_'+field_id+'-'+opt_key).parent('div.arf_check_radio_fields').fadeOut('slow', function(){ CheckFieldPos_height(); });
			}
			arfupdateoptionorder( field_id );
			arfreordercheckradio( field_id );
			setTimeout(function(){
				arfcheckoptionlength( field_id );
			}, 700);
			
		}
    });
};


function fielddelete(field_id)
{ 
	var field_type = jQuery('#field_type_'+field_id).val(); 
		
	jQuery.ajax({
        type:"POST",url:ajaxurl,
        data:"action=arfdeleteformfield&field_id="+field_id,
        success:function(msg){
			var f_id = jQuery('#field_type_'+field_id).attr('data-fid');
			jQuery("#arfmainfieldid_"+field_id+" .multi_column_div").html(' ');
			jQuery("#arfmainfieldid_"+field_id+" .pg-break-div").html(' ');
			
			jQuery("#arfmainfieldid_"+field_id).remove();
			jQuery("#new_fields").sortable("refresh");
			jQuery("#new_fields").sortable("refreshPositions");
			
			checkpage_breakpos();	
			CheckFieldPos('1','0');
			delete_cl_field_menu(field_id, f_id);	//---------- for conditional logic ----------//
			arf_delete_name_dropdown(field_id, f_id);
			jQuery(".sltstandard select").selectpicker();
			jQuery(".arfmodal-backdrop").remove();
		}
    });
	
	//jQuery('[data-dismiss="arfmodal"]').trigger("click");
};

function arffieldhover(show, field_id)
{
	var html_id = '#arfmainfieldid_'+field_id;
	if(show)
	{
		jQuery('.arfshowiconsonhover'+field_id).css('visibility','visible');
	}
	else
	{
		jQuery('.arfshowiconsonhover'+field_id).css('visibility','hidden');
	}
	
	if(show){jQuery(html_id).children('.arfshowfieldhover').css('visibility','visible');}
	else{if(!jQuery(html_id).is('.selected')){jQuery(html_id).children('.arfshowfieldhover').css('visibility','hidden');}}
}

function arfclickvisfunction(target,obj){

	if(obj.hasClass('selected')){ return; }
	jQuery('.arfshowfieldhover').css('visibility','hidden'); obj.children('.arfshowfieldhover').css('visibility','visible');
	jQuery('.arfshowfieldclick').hide(); obj.find('.arfshowfieldclick').show(); 
	//jQuery('.show-field-options').hide(); //obj.find('.show-field-options').show();
	//---------- for field tab ----------//
	var id = obj.attr('id');
	id = id.replace('arfmainfieldid_','');
	arf_open_field_option(id, 'field_basic_option', 1);
	//---------- for field tab ----------//
	var i=obj.find('input[name^="item_meta"], select[name^="item_meta"], textarea[name^="item_meta"]')[0];
	if(jQuery(i).val()){ obj.find('.arfdefaultvalicons').show().css('visibility', 'visible'); }
	else { obj.find('.arfdefaultvalicons').hide().css('visibility', 'hidden'); }
	jQuery('li.ui-state-default.selected').removeClass('selected'); obj.addClass('selected');
}

function arfshowfieldoptions(id){
	arf_open_field_option(id, 'field_basic_option', 1);
	jQuery('.show-field-options').hide();	
	jQuery('#field-option-'+id).show().css('visibility', 'hidden');
	arf_change_offset(id, 0);
	jQuery('#field-option-'+id).css('visibility', 'visible');
}

function arfsetoffsetformenu()
{ 
	var fields = jQuery('#postbox-container-1 .arffieldlist');
	if(fields.length>0){
		var offset=283;
	}else{
		var fields = jQuery('#frm_adv_info');
		if(fields.length==0) { return; }
		var offset=455;
	}
	var currentOffset = document.documentElement.scrollTop || document.body.scrollTop; // body for Safari
	if(jQuery('#frm_position_ele').length>0){ 
		var eleOffset=jQuery('#frm_position_ele').offset();
		var offset=eleOffset.top;
	}
	var desiredOffset = offset + 2 - currentOffset;
	
	if (desiredOffset < 35){ desiredOffset = 35; }
		//fields.attr('style', 'top:'+desiredOffset + 'px;'); 
}

function arfaddcodefornewfield(element,variable)
{
	if(typeof(element)=='object'){
		var element_id=element.closest('div').attr('class').split(' ')[1];
		if(element.hasClass('arfnoallow')){ return; }
	}else{var element_id=element;}
	
	var variable1 = jQuery('#arfmodalfieldval_'+variable).text();
	variable2 = '['+variable1+':'+variable+']';
	
	if(!element_id){ var rich=true; }
	else{var rich=jQuery('#wp-'+element_id+'-wrap.wp-editor-wrap').length > 0;}
	
	//variable='['+variable+']';
	if(rich)
	{
		wpActiveEditor=element_id;
		send_to_editor(variable2);
		return;
	}
	
	var start_pos = jQuery('#'+element_id).attr('data-startpos'); 
	
		
	var content_box	=	jQuery('#'+element_id);
	if(content_box)
	{
		if(content_box.hasClass('arfnotemailto')){ var variable=', '+variable; }
		if(variable=='[default-html]' || variable=='[default-plain]')
		{
			var p=0;
			if(variable=='[default-plain]'){ var p=1; }
			jQuery.ajax({type:"POST",url:ajaxurl,
		        data:"action=frm_get_default_html&form_id="+jQuery('input[name="id"]').val()+'&plain_text='+p,
		        success:function(msg){arfaddnewcontentforfield(element_id,msg, start_pos);} 
		    });
		}
		else
		{
			arfaddnewcontentforfield(element_id, variable2, start_pos);
		}
	}
}

function arfaddnewcontentforfield(content_box,variable, start_pos)
{
    
	if( start_pos == 0 )
	{
		jQuery('#'+content_box).val( jQuery('#'+content_box).val()+variable );	
	}
	else
	{
		jQuery('#'+content_box).insertAt(start_pos, variable);	
	}
	
}

function arfupdateformcss(locStr){
	var cssLink = jQuery('<link href="'+locStr+'" type="text/css" rel="Stylesheet" class="ui-theme" />');
	jQuery("head").append(cssLink);
	if( jQuery("link.ui-theme").size() > 3){
		jQuery("link.ui-theme:first").remove();
	}	
}

function show_api( id ) {
	if( id == 'aweber' ){
		var is_verify = jQuery('#aweber_status').val();
		if( is_verify == 1 ){
			jQuery('#'+id+'_api_tr4').css('display', '');	
		} else {
			jQuery('#'+id+'_api_tr1').css('display', '');
			jQuery('#'+id+'_api_tr2').css('display', '');
			jQuery('#'+id+'_api_tr3').css('display', '');	
		}
	} else {
		jQuery('#'+id+'_api_tr1').css('display', '');
		jQuery('#'+id+'_api_tr2').css('display', '');
		jQuery('#'+id+'_api_tr3').css('display', '');
		jQuery('#'+id+'_api_tr4').css('display', '');
	}
	jQuery('#'+id+'_web_form_tr').css('display', 'none');
}

function show_web_form( id ) {
	jQuery('#'+id+'_api_tr1').css('display', 'none');
	jQuery('#'+id+'_api_tr2').css('display', 'none');
	jQuery('#'+id+'_api_tr3').css('display', 'none');
	jQuery('#'+id+'_api_tr4').css('display', 'none');
	jQuery('#'+id+'_web_form_tr').css('display', '');
}

function hide_api_web_form( id ) {
	jQuery('#'+id+'_web_form_tr').css('display', 'none');
	jQuery('#'+id+'_api_tr1').css('display', 'none');
	jQuery('#'+id+'_api_tr2').css('display', 'none');
	jQuery('#'+id+'_api_tr3').css('display', 'none');
	jQuery('#'+id+'_api_tr4').css('display', 'none');
}

function aweber_continue( url ) {
	var consumer_key = jQuery('#consumer_key').val();
	var consumer_secret = jQuery('#consumer_secret').val();
	var i = 0;
	if(consumer_key=='') {
		jQuery('#consumer_key').css('border-color', '#ff0000');
		jQuery('#consumer_key_error').css('display', 'block');
		i++;
	} else {
		jQuery('#consumer_key').css('border-color', '#BCCBDA');
		jQuery('#consumer_key_error').css('display', 'none');	
	}
	
	if(consumer_secret=='') {
		jQuery('#consumer_secret').css('border-color', '#ff0000');
		jQuery('#consumer_secret_error').css('display', 'block');
		i++;
	} else {
		jQuery('#consumer_secret').css('border-color', '#BCCBDA');
		jQuery('#consumer_secret_error').css('display', 'none');	
	}

	if(i > 0){
		return false;
	}else{
		window.open(url + '?consumer_key=' + consumer_key + '&consumer_secret=' + consumer_secret, 'Aweber Login', 'height=400, width=800');
	}
}


function action_aweber( act ) {
	if( act == 'delete' ) {
		if( confirm('Are you sure to delete configuration ?') ) {
			jQuery.ajax({
				type:"POST",url:ajaxurl,
				data:"action=delete_aweber",
				success: function(html){ jQuery('#aweber_api_tr1').css('display', '');
					jQuery('#aweber_api_tr2').css('display', '');
					jQuery('#aweber_api_tr3').css('display', '');
					jQuery('#aweber_status').val('0');
					jQuery('#aweber_api_list_tr1').css('display', 'none');  } 
				});
		} else {
			return false;
		}
	}
	
	else if( act == 'refresh' ) {
			jQuery('#aweber_loader2').css('display', 'inline');	
			jQuery.ajax({
				type:"POST",url:ajaxurl,
				data:"action=refresh_aweber",
				success: function(html){ jQuery('#select_aweber').html(html);  jQuery(".sltstandard select").selectpicker(); jQuery('#aweber_loader2').css('display', 'none'); jQuery('#aweber_refresh').delay(3000).fadeOut('slow'); }	
			});
	}
}

function verify_autores( id, refresh_li ) {
	if( id == 'mailchimp' ) {
		var api_key = jQuery('#mailchimp_api').val();
		var user = '';
		var pass = '';
		if(api_key=='') {
			jQuery('#mailchimp_api').css('border-color', '#ff0000');
			jQuery('#mailchimp_api_error').css('display', 'block');
			return false;
		} else {
			jQuery('#mailchimp_api').css('border-color', '#BCCBDA');
			jQuery('#mailchimp_api_error').css('display', 'none');	
		}
		
		jQuery('#mailchimp_link').css('display', 'none');
		
		if(refresh_li != '1'){
			jQuery('#mailchimp_loader').css('display', 'inline');
		} else {
			jQuery('#mailchimp_verify').css('display', 'none');
			jQuery('#mailchimp_refresh').css('display', 'none');
			jQuery('#mailchimp_loader2').css('display', 'inline');
		}
	}
	if( id == 'getresponse' ) {
		var api_key = jQuery('#getresponse_api').val();
		var user = '';
		var pass = '';
		
		if(api_key=='') {
			jQuery('#getresponse_api').css('border-color', '#ff0000');
			jQuery('#getresponse_api_error').css('display', 'block');
			return false;
		} else {
			jQuery('#getresponse_api').css('border-color', '#BCCBDA');
			jQuery('#getresponse_api_error').css('display', 'none');	
		}
		
		jQuery('#getresponse_link').css('display', 'none');
		
		if(refresh_li != '1'){
			jQuery('#getresponse_loader').css('display', 'inline');
		} else {
			jQuery('#getresponse_verify').css('display', 'none');
			jQuery('#getresponse_refresh').css('display', 'none');
			jQuery('#getresponse_loader2').css('display', 'inline');			
		}
	}
	if( id == 'icontact' ) {
		var api_key = jQuery('#icontact_api').val();
		var user = jQuery('#icontact_username').val();
		var pass = jQuery('#icontact_password').val();
		var i = 0;
		if(api_key=='') {
			jQuery('#icontact_api').css('border-color', '#ff0000');
			jQuery('#icontact_api_error').css('display', 'block');
			i++;
		} else {
			jQuery('#icontact_api').css('border-color', '#BCCBDA');
			jQuery('#icontact_api_error').css('display', 'none');	
		}
		
		if(user=='') {
			jQuery('#icontact_username').css('border-color', '#ff0000');
			jQuery('#icontact_username_error').css('display', 'block');
			i++;
		} else {
			jQuery('#icontact_username').css('border-color', '#BCCBDA');
			jQuery('#icontact_username_error').css('display', 'none');	
		}
		
		if(pass=='') {
			jQuery('#icontact_password').css('border-color', '#ff0000');
			jQuery('#icontact_password_error').css('display', 'block');
			i++;
		} else {
			jQuery('#icontact_password').css('border-color', '#BCCBDA');
			jQuery('#icontact_password_error').css('display', 'none');	
		}
		
		if(i > 0) {
			return false;
		}
			
		jQuery('#icontact_link').css('display', 'none');
		
		if(refresh_li != '1'){
			jQuery('#icontact_loader').css('display', 'inline');
		} else {
			jQuery('#icontact_verify').css('display', 'none');
			jQuery('#icontact_refresh').css('display', 'none');
			jQuery('#icontact_loader2').css('display', 'inline');
		}
	}
	if( id == 'constant' ) {
		var api_key = jQuery('#constant_api').val();
		var user = jQuery('#constant_access_token').val();
		var pass = '';
		var i = 0;
		if(api_key=='') {
			jQuery('#constant_api').css('border-color', '#ff0000');
			jQuery('#constant_api_error').css('display', 'block');
			i++;
		} else {
			jQuery('#constant_api').css('border-color', '#BCCBDA');
			jQuery('#constant_api_error').css('display', 'none');	
		}
		
		if(user=='') {
			jQuery('#constant_access_token').css('border-color', '#ff0000');
			jQuery('#constant_access_token_error').css('display', 'block');
			i++;
		} else {
			jQuery('#constant_access_token').css('border-color', '#BCCBDA');
			jQuery('#constant_access_token_error').css('display', 'none');	
		}

		if(i > 0) {
			return false;
		}
		jQuery('#constant_link').css('display', 'none');
		
		if(refresh_li != '1'){
			jQuery('#constant_loader').css('display', 'inline');
		} else {
			jQuery('#constant_verify').css('display', 'none');
			jQuery('#constant_refresh').css('display', 'none');
			jQuery('#constant_loader2').css('display', 'inline');
		}
	}
			jQuery.ajax({
				type:"POST",url:ajaxurl,
				data:"action=verify_autores&id="+id+"&api_key="+api_key+"&user="+user+"&pass="+pass+"&refresh_li="+refresh_li,
				success: function(html){ 
				if(id == 'icontact') { 
						if(refresh_li != '1') {
							if(html != ''){
								jQuery('#icontact_verify').css('display', 'inline');
							}
							jQuery('#icontact_loader').css('display', 'none');
						} else {
							jQuery('#icontact_loader2').css('display', 'none');
						}
						
						if(html != '') {
							jQuery('#select_icontact').html(html);
							jQuery('#select_status').val('1');
							jQuery('#icontact_del_link').css('display', 'block');
						} else {
							jQuery('#icontact_error').css('display', 'inline');
							jQuery('#icontact_status').val('0');
														
							jQuery('#icontact_listname').html('');
							jQuery('#icontact_listname').attr('disabled', 'true')
							jQuery("#icontact_listname").selectpicker('refresh');
						}
						
					jQuery('#icontact_verify').delay(3000).fadeOut('slow');
					jQuery('#icontact_refresh').delay(3000).fadeOut('slow');	
						
					} 
				if(id == 'mailchimp') {
					
						if(refresh_li != '1') {
							if(html != ''){
								jQuery('#mailchimp_verify').css('display', 'inline');
							}
							jQuery('#mailchimp_loader').css('display', 'none');
						} else {
							jQuery('#mailchimp_loader2').css('display', 'none');
						}
						
						
						if(html != '') {
							jQuery('#select_mailchimp').html(html);
							jQuery('#mailchimp_status').val('1');
							jQuery('#mailchimp_del_link').css('display', 'block');
							
						} else {
							jQuery('#mailchimp_error').css('display', 'inline');
							jQuery('#mailchimp_status').val('0');
											
							jQuery('#mailchimp_listid').html('');
							jQuery('#mailchimp_listid').attr('disabled', 'true')
							jQuery("#mailchimp_listid").selectpicker('refresh');

						}																		
					
					
					jQuery('#mailchimp_verify').delay(3000).fadeOut('slow');
					jQuery('#mailchimp_refresh').delay(3000).fadeOut('slow');
					
					}
				if(id == 'constant') { 
						if(refresh_li != '1') {
							if(html != ''){
								jQuery('#constant_verify').css('display', 'inline');
							}
							jQuery('#constant_loader').css('display', 'none');		
						} else {
							jQuery('#constant_loader2').css('display', 'none');
						}
						
						if(html != '') {
							jQuery('#select_constant').html(html);
							jQuery('#constant_status').val('1');
							jQuery('#constant_del_link').css('display', 'block');
						} else {
							jQuery('#constant_error').css('display', 'inline');
							jQuery('#constant_status').val('0');
					
							jQuery('#constant_listname').html('');
							jQuery('#constant_listname').attr('disabled', 'true')
							jQuery("#constant_listname").selectpicker('refresh');
						}
					
					jQuery('#constant_verify').delay(3000).fadeOut('slow');
					jQuery('#constant_refresh').delay(3000).fadeOut('slow');
					
					}
				if(id == 'getresponse') { 
						if(refresh_li != '1'){
							if(html != ''){
								jQuery('#getresponse_verify').css('display', 'inline');
							}
							jQuery('#getresponse_loader').css('display', 'none');
						} else {
							jQuery('#getresponse_loader2').css('display', 'none');
						}
						
						if(html != '') {
							jQuery('#select_getresponse').html(html);
							jQuery('#getresponse_status').val('1');
							jQuery('#getresponse_del_link').css('display', 'block');
						} else {
							jQuery('#getresponse_error').css('display', 'inline');
							jQuery('#getresponse_status').val('0');
					
							jQuery('#getresponse_listid').html('');
							jQuery('#getresponse_listid').attr('disabled', 'true')
							jQuery("#getresponse_listid").selectpicker('refresh');	
						}
						
					jQuery('#getresponse_verify').delay(3000).fadeOut('slow');
					jQuery('#getresponse_refresh').delay(3000).fadeOut('slow');	
						
					}
				
				jQuery(".sltstandard select").selectpicker(); 
				}
			});
}

function action_autores( act, id ) {
	
	if( act == 'delete' ) {
		
		if( confirm('Are you sure to delete configuration ?') ) {
			
			jQuery.ajax({
				
				type:"POST",url:ajaxurl,

				data:"action=delete_autores&id="+id,

				success: function(html){ 
								
				if(id == 'icontact') { 									
					jQuery('#icontact_api').val('');
					jQuery('#icontact_username').val('');
					jQuery('#icontact_password').val('');
					jQuery('#icontact_link').css('display', 'inline');
					jQuery('#icontact_del_link').css('display', 'none');
					jQuery('#icontact_verify').css('display', 'none');
					jQuery('#icontact_status').val('0');
														
					jQuery('#icontact_listname').html('');
					jQuery('#icontact_listname').attr('disabled', 'true')
					jQuery("#icontact_listname").selectpicker('refresh');	
					} 
				
				if(id == 'mailchimp') {
					jQuery('#mailchimp_api').val('');
					jQuery('#mailchimp_link').css('display', 'inline');
					jQuery('#mailchimp_del_link').css('display', 'none');
					jQuery('#mailchimp_verify').css('display', 'none');									
					jQuery('#mailchimp_status').val('0');
					
					jQuery('#mailchimp_listid').html('');
					jQuery('#mailchimp_listid').attr('disabled', 'true')
					jQuery("#mailchimp_listid").selectpicker('refresh');	
					
					}
				
				if(id == 'constant') { 
					jQuery('#constant_api').val('');
					jQuery('#constant_access_token').val('');
					jQuery('#constant_link').css('display', 'inline');
					jQuery('#constant_del_link').css('display', 'none');
					jQuery('#constant_verify').css('display', 'none');
					jQuery('#constant_status').val('0');
					
					jQuery('#constant_listname').html('');
					jQuery('#constant_listname').attr('disabled', 'true')
					jQuery("#constant_listname").selectpicker('refresh');
					}
				
				if(id == 'getresponse') { 
					jQuery('#getresponse_api').val('');
					jQuery('#getresponse_link').css('display', 'inline');
					jQuery('#getresponse_del_link').css('display', 'none');
					jQuery('#getresponse_verify').css('display', 'none');
					jQuery('#getresponse_status').val('0');
					
					jQuery('#getresponse_listid').html('');
					jQuery('#getresponse_listid').attr('disabled', 'true')
					jQuery("#getresponse_listid").selectpicker('refresh');	
					}							
				
				jQuery(".sltstandard select").selectpicker(); 
				}

			});
		}
		else {
			return false
		}
	}
	else if( act == 'refresh' ) {
		
		verify_autores( id, '1' );
		
	}
}

function submit_form_type() {
	
	if( jQuery('#form_name_new').val() == '' ){
		jQuery('#form_name_new').addClass('form_name_new_required');
		jQuery('#form_name_new_required').show();
		jQuery('#form_name_new').focus();
		return false;
	}else {
		jQuery('#form_name_new').removeClass('form_name_new_required');
		jQuery('#form_name_new_required').hide();
		jQuery('#new').submit();
	}
		
}

function show_setting( id, no ) {
	if( jQuery('#autores_'+no).is(':checked') ) {
		jQuery('#ar_lbl_'+no).hide();
		jQuery('#ar_lbl_inner_'+no).show();		
		jQuery('#'+id+'_main_div').addClass('arforms_autores-open').removeClass('arforms_autores');
		jQuery('#'+id+'_main_div .autores_img').show();
		jQuery('#setting_div_'+id).show();
	}
	else {
		jQuery('#ar_lbl_inner_'+no).hide();
		jQuery('#ar_lbl_'+no).show();		
		jQuery('#'+id+'_main_div').addClass('arforms_autores').removeClass('arforms_autores-open');
		jQuery('#'+id+'_main_div .autores_img').hide();
		jQuery('#setting_div_'+id).hide();		
	}
}

function change_custom_css() {
	if ( jQuery('#is_custom_css').is(':checked') ) {
		jQuery('#form_custom_css').removeAttr('readonly');
		jQuery('#is_custom_css').val('1');
	}
	else
	{
		jQuery('#form_custom_css').attr('readonly', 'readonly');
		jQuery('#is_custom_css').val('0');
	}
}

function check_checkbox() {
	jQuery('#pre2').attr('checked', 'checked');
}


function open_entry_thickbox(id) {
	jQuery("#view_entry_"+id).arfmodal();
}


function change_graph()
{
	var form = jQuery('#arfredirecttolist3').val();
	var type = jQuery('#chart_type').val();
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=updatechart&type="+type+"&form="+form,
		success:function(msg){ jQuery('#chart_div').html(msg); }
	});
}

function change_graph_pre(type){
	var form = jQuery('#arfredirecttolist3').val();
	if(type == "yearly")
	{
		var val = jQuery('#current_year').val();
		new_year = val -1;
		jQuery('#current_year').val(new_year);
		var last_arg = "&new_year="+new_year;
	}
	else if(type == "monthly")
	{
		var val = jQuery('#current_month').val();			
		var val_year = jQuery('#current_month_year').val();			
				
		new_month = parseInt(val);
		new_month_year = parseInt(val_year);
		if(new_month == 1)
		{
			new_month_year =new_month_year -1;
			new_month = 12;
		}
		else{
			new_month = new_month-1;
		}
		jQuery('#current_month').val(new_month);
		jQuery('#current_month_year').val(new_month_year);
		
		var last_arg = "&new_month="+new_month+"&new_month_year="+new_month_year;		
	}
	else if(type == "weekly")
	{
		var val = jQuery('#current_week').val();			
		new_week = parseInt(val)-1;
		jQuery('#current_month').val(new_week);
		var last_arg = "&new_week="+new_week;		
	}
	else if(type == "daily")
	{
		var val = jQuery('#current_day').val();	
		var val_day_month = jQuery('#current_day_month').val();	
		var val_day_year = jQuery('#current_day_year').val();			
		
		new_day = parseInt(val);
		new_day_month = parseInt(val_day_month);
		new_day_year = parseInt(val_day_year);
		
		if(val == 1 )
		{
			new_day_month = new_day_month-1;
			if(new_day_month == 0){
				new_day_month =12;
			}
			var d = new Date(new_day_year, new_day_month , 0);
			new_day = d.getDate();
		}
		else
		{
			new_day = parseInt(val)-1;
		}
		
		if(val_day_month == 1 && val == 1)
		{
			new_day_year = new_day_year-1;
		}
		else
		{
			new_day_year = new_day_year;
		}
			
		jQuery('#current_day').val(new_day);
		jQuery('#current_day_month').val(new_day_month);
		jQuery('#current_day_year').val(new_day_year);
		
		var last_arg = "&new_day="+new_day+"&new_day_month="+new_day_month+"&new_day_year="+new_day_year;		
	}
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=updatechart&type="+type+"&form="+form+"&calculate=pre"+last_arg,
		success:function(msg){ jQuery('#chart_div').html(msg);}
	});
}

function change_graph_next(type){
	var form = jQuery('#arfredirecttolist3').val();
	var last_arg;
	if(type == "yearly")
	{
		var val = jQuery('#current_year').val();			
		new_year = parseInt(val)+1;
		jQuery('#current_year').val(new_year);
		var last_arg = "&new_year="+new_year;
	}
	else if(type == "monthly")
	{
		var val = jQuery('#current_month').val();			
		var val_year = jQuery('#current_month_year').val();			
		
		new_month = parseInt(val);
		new_month_year = parseInt(val_year);
		if(new_month == 12)
		{
			new_month_year =new_month_year +1;
			new_month = 1;
		}
		else{
			new_month = new_month+1;
		}
		jQuery('#current_month').val(new_month);
		jQuery('#current_month_year').val(new_month_year);
		
		var last_arg = "&new_month="+new_month+"&new_month_year="+new_month_year;		
	}
	else if(type == "weekly")
	{
		var val = jQuery('#current_week').val();			
		new_week = parseInt(val)+1;
		jQuery('#current_month').val(new_week);
		var last_arg = "&new_week="+new_week;		
	}
	else if(type == "daily")
	{	
		var val = jQuery('#current_day').val();	
		var val_day_month = jQuery('#current_day_month').val();	
		var val_day_year = jQuery('#current_day_year').val();			
		
		new_day = parseInt(val);
		new_day_month = parseInt(val_day_month);
		new_day_year = parseInt(val_day_year);
		
		var d = new Date(new_day_year, new_day_month , 0);
		old_day = d.getDate();
			
		if(val == old_day)
		{
			new_day_month = new_day_month+1;
			new_day =1;
		}
		else
		{
			new_day = parseInt(val)+1;
		}
		
		if(val_day_month == 12 && (val == old_day))
		{
			new_day_year = new_day_year+1;
			new_day =1;
			new_day_month =1;
		}
		else
		{
			new_day_year = new_day_year;
		}
			
		jQuery('#current_day').val(new_day);
		jQuery('#current_day_month').val(new_day_month);
		jQuery('#current_day_year').val(new_day_year);
		var last_arg = "&new_day="+new_day+"&new_day_month="+new_day_month+"&new_day_year="+new_day_year;	
	}
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=updatechart&type="+type+"&form="+form+"&calculate=next"+last_arg,
		success:function(msg){ jQuery('#chart_div').html(msg); }
	});
}

function change_style_unit( id ) {
	jQuery('.style_unit_class').text(id);	
}

function change_frm_entries(){
	var form = jQuery('#arfredirecttolist').val();
	var srart_date = jQuery('#datepicker_from').val();
	var end_date = jQuery('#datepicker_to').val();
	var please_select_form = jQuery('#please_select_form').val();
	if( form == '' ) { alert(please_select_form); return false; }
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=updateentries&form="+form+"&start_date="+srart_date+"&end_date="+end_date,
		success:function(msg){ 
			jQuery('#form_entries').html(msg); 
			jQuery(".sltstandard select").selectpicker(); 
			jQuery('#success_message').delay(3000).fadeOut('slow');
			jQuery('#form_entries .arfhelptip').tooltipster({
				theme: 'arf_admin_tooltip',
				position:'top',
				contentAsHTML:true,
				onlyOne:true,
				multiple:true,
				maxWidth:400,
			});
		}
	});
}

function arf_delete_bulk_entries(val){
	var action_delete = jQuery('#delete_bulk_entry_flag').val('true');
	jQuery('form#list_entry_form').submit();
	jQuery('[data-dismiss="arfmodal"]').trigger("click");
}

function apply_bulk_action(){
	var str = jQuery('form').serialize();
	var form = jQuery('#arfredirecttolist').val();
	var srart_date = jQuery('#datepicker_from').val();
	var end_date = jQuery('#datepicker_to').val();
	var please_select_form = jQuery('#please_select_form').val();
	if( form == '' ) { alert(please_select_form); return false; }
	
	var action1 = jQuery('select[name="action1"]').val();
	var action2 = jQuery('select[name="action2"]').val();
	var action_delete = jQuery('#delete_bulk_entry_flag').val();
	var chk_count = jQuery('input[name="item-action[]"]:checked').length;		
	if( ( action1 == 'bulk_delete' || action2 == 'bulk_delete' ) && chk_count > 0 ){
		if(action_delete == 'false'){
			arfchangedeletemodalwidth('arfdeletemodabox');
			jQuery('#delete_bulk_entry_flag').attr("data-toggle", "arfmodal").attr('href', '#delete_bulk_entry_message').trigger("click");
			return false;
		}	
	}
	jQuery('#delete_bulk_entry_flag').val('false');
	
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arfchangebulkentries&form="+form+"&start_date="+srart_date+"&end_date="+end_date+"&"+str,
		success:function(msg){ 
			jQuery('#form_entries').html(msg); 
			jQuery(".sltstandard select").selectpicker();  
			jQuery('#success_message').delay(3000).fadeOut('slow');
			jQuery('#form_entries .arfhelptip').tooltipster({
				theme: 'arf_admin_tooltip',
				position:'top',
				contentAsHTML:true,
				onlyOne:true,
				multiple:true,
				maxWidth:400,
			});
		}
	});
	return false;
}

function arf_delete_bulk_form(val){
	var action_delete = jQuery('#bulk_delete_flag').val('true');
	jQuery('#arfmainformnewlist').submit();
	jQuery('[data-dismiss="arfmodal"]').trigger("click");
}

function apply_bulk_action_form(){
	var action1 = jQuery('select[name="action1"]').val();
	var action2 = jQuery('select[name="action2"]').val();
	var action_delete = jQuery('#bulk_delete_flag').val();
	var chk_count = jQuery('input[name="item-action[]"]:checked').length;	
	if( ( action1 == 'bulk_delete' || action2 == 'bulk_delete' ) && chk_count > 0 ){
		if(action_delete == 'false'){
			arfchangedeletemodalwidth('arfdeletemodabox');
			jQuery('#bulk_delete_flag').attr("data-toggle", "arfmodal").attr('href', '#delete_bulk_form_message').trigger("click");
			return false;
		}	
	}
	jQuery('#bulk_delete_flag').val('false');
	
	var str = jQuery('form').serialize();
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arfupdateformbulkoption&"+str,
		success:function(msg){ 
				jQuery('#arfmainformnewlist').html(msg); 
				jQuery(".sltstandard select").selectpicker();  
				jQuery('#success_message').delay(3000).fadeOut('slow');
				
				jQuery('#arfmainformnewlist .arfhelptip').tooltipster({
					theme: 'arf_admin_tooltip',
					position:'top',
					contentAsHTML:true,
					onlyOne:true,
					multiple:true,
					maxWidth:400,
				});
			}
	});
	return false;
}

function arfentryactionfunc(act, id){
	if(act == 'delete')
	{
		id = document.getElementById('delete_entry_id').value; 
	}
		
	var form = jQuery('#arfredirecttolist').val();
	var srart_date = jQuery('#datepicker_from').val();
	var end_date = jQuery('#datepicker_to').val();
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=recordactions&form="+form+"&start_date="+srart_date+"&end_date="+end_date+"&act="+act+"&id="+id,
		success:function(msg){ 
			jQuery('#form_entries').html(msg); 
			jQuery(".sltstandard select").selectpicker();  
			jQuery('#success_message').delay(3000).fadeOut('slow');
			jQuery('#form_entries .arfhelptip').tooltipster({
				theme: 'arf_admin_tooltip',
				position:'top',
				contentAsHTML:true,
				onlyOne:true,
				multiple:true,
				maxWidth:400,
			});
		}
	});
	if(act == 'delete')
	{
		jQuery('[data-dismiss="arfmodal"]').trigger("click");
	}
	return false;
}

function check_typ(id, no){
	if( jQuery('#autores_'+no).is(':disabled') ) {
		alert('Please enable this autoresponder from global setting');
	}
}

function change_title_val(){
	if( jQuery('#display_title_form').is(':checked') ) {
		jQuery('#display_title_form').val('1');
		jQuery('#form_title_style_div').show();
	} else {
		jQuery('#display_title_form').val('0');
		jQuery('#form_title_style_div').hide();
	}
}

function change_graph_new(val)
{
	var form = jQuery('#arfredirecttolist3').val();
	var type = val;
	if(val=="daily")
	{
		document.getElementById(val+"_unselected").style.display= "none";
		document.getElementById(val+"_selected").style.display= "block";
		document.getElementById("monthly_selected").style.display= "none";
		document.getElementById("monthly_unselected").style.display= "block";
		document.getElementById("yearly_selected").style.display= "none";
		document.getElementById("yearly_unselected").style.display= "block";
	}
	else if(val=="monthly")
	{
		document.getElementById(val+"_unselected").style.display= "none";
		document.getElementById(val+"_selected").style.display= "block";
		document.getElementById("daily_selected").style.display= "none";
		document.getElementById("daily_unselected").style.display= "block";
		document.getElementById("yearly_selected").style.display= "none";
		document.getElementById("yearly_unselected").style.display= "block";
	}
	else if(val=="yearly")
	{
		document.getElementById(val+"_unselected").style.display= "none";
		document.getElementById(val+"_selected").style.display= "block";
		document.getElementById("daily_selected").style.display= "none";
		document.getElementById("daily_unselected").style.display= "block";
		document.getElementById("monthly_selected").style.display= "none";
		document.getElementById("monthly_unselected").style.display= "block";
	}
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=updatechart&type="+type+"&form="+form,
		success:function(msg){ jQuery('#chart_div').html(msg); }
	});
}

function global_form_validate(){
	if( jQuery('#arfcurrenttab').val() == 'general_settings' ) {
	var i = 0;	
		if( jQuery('#frm_blank_msg').val() =='' ) {
			jQuery('#frm_blank_msg').css('border-color', '#ff0000');
			jQuery('#arfblankerrmsg').css('display', 'block');
			i++;
		} else {
			jQuery('#frm_blank_msg').css('border-color', '#BCCBDA');
			jQuery('#arfblankerrmsg').css('display', 'none');	
		}
		if( jQuery('#arfinvalidmsg').val() =='' ) {
			jQuery('#arfinvalidmsg').css('border-color', '#ff0000');
			jQuery('#arfinvalidmsg_error').css('display', 'block');
			i++;
		} else {
			jQuery('#arfinvalidmsg').css('border-color', '#BCCBDA');
			jQuery('#arfinvalidmsg_error').css('display', 'none');	
		}
		if( jQuery('#arfsuccessmsg').val() =='' ) {
			jQuery('#arfsuccessmsg').css('border-color', '#ff0000');
			jQuery('#arfsuccessmsgerr').css('display', 'block');
			i++;
		} else {
			jQuery('#arfsuccessmsg').css('border-color', '#BCCBDA');
			jQuery('#arfsuccessmsgerr').css('display', 'none');	
		}
		if( jQuery('#arfmessagefailed').val() =='' ) {
			jQuery('#arfmessagefailed').css('border-color', '#ff0000');
			jQuery('#arferrormessagefailed').css('display', 'block');
			i++;
		} else {
			jQuery('#arfmessagefailed').css('border-color', '#BCCBDA');
			jQuery('#arferrormessagefailed').css('display', 'none');	
		}	
		if( jQuery('#arfvaluesubmit').val() =='' ) {
			jQuery('#arfvaluesubmit').css('border-color', '#ff0000');
			jQuery('#arferrorsubmitvalue').css('display', 'block');
			i++;
		} else {
			jQuery('#arfvaluesubmit').css('border-color', '#BCCBDA');
			jQuery('#arferrorsubmitvalue').css('display', 'none');	
		}	
		if( jQuery('#arfvaluesubmit').val() =='' ) {
			jQuery('#arfvaluesubmit').css('border-color', '#ff0000');
			jQuery('#arferrorsubmitvalue').css('display', 'block');
			i++;
		} else {
			jQuery('#arfvaluesubmit').css('border-color', '#BCCBDA');
			jQuery('#arferrorsubmitvalue').css('display', 'none');	
		}	
		if( jQuery('#frm_reply_to_name').val() =='' ) {
			jQuery('#frm_reply_to_name').css('border-color', '#ff0000');
			jQuery('#frm_reply_to_name_error').css('display', 'block');
			i++;
		} else {
			jQuery('#frm_reply_to_name').css('border-color', '#BCCBDA');
			jQuery('#frm_reply_to_name_error').css('display', 'none');	
		}	
		if( jQuery('#frm_reply_to').val() =='' ) {
			jQuery('#frm_reply_to').css('border-color', '#ff0000');
			jQuery('#frm_reply_to_error').css('display', 'block');
			i++;
		} else {
			jQuery('#frm_reply_to').css('border-color', '#BCCBDA');
			jQuery('#frm_reply_to_error').css('display', 'none');	
		}
                
                if( jQuery('.arf_success_message_show_time').val() =='' || !(parseInt(jQuery('.arf_success_message_show_time').val())>=0)) {
			jQuery('.arf_success_message_show_time').css('border-color', '#ff0000');
 			i++;
		} else {
			jQuery('.arf_success_message_show_time').css('border-color', '#BCCBDA');
			
		}
		if(i > 0){
			return false;
		} else {
			return true;
		}
	} else {
		return true;	
	}
}

function arfaction_func(act, id){
	del_id = id;
	if(act == 'delete')
	{
		del_id = document.getElementById('delete_id').value; 
	}
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arfupdateactionfunction&act="+act+"&id="+del_id ,
		success:function(msg){ 
			jQuery('#arfmainformnewlist').html(msg); 
			jQuery(".sltstandard select").selectpicker();  
			jQuery('#success_message').delay(3000).fadeOut('slow');
			jQuery('#arfmainformnewlist .arfhelptip').tooltipster({
				theme: 'arf_admin_tooltip',
				position:'top',
				contentAsHTML:true,
				onlyOne:true,
				multiple:true,
				maxWidth:400,
			});
		}
	});
	if(act == 'delete')
	{
		jQuery('[data-dismiss="arfmodal"]').trigger("click");
	}
	return false;
}

function show_verify_btn( id ){
	if(id == 'icontact' && jQuery('#constant_status').val() == '0' ) { 									
		jQuery('#icontact_error').css('display', 'none');
		jQuery('#icontact_link').css('display', 'inline');
	} 
	if(id == 'mailchimp' && jQuery('#mailchimp_status').val() == '0') {
		jQuery('#mailchimp_error').css('display', 'none');
		jQuery('#mailchimp_link').css('display', 'inline');
	}
	if(id == 'constant' && jQuery('#constant_status').val() == '0') { 
		jQuery('#constant_error').css('display', 'none');
		jQuery('#constant_link').css('display', 'inline');
	}
	if(id == 'getresponse' && jQuery('#getresponse').val() == '0') { 									
		jQuery('#getresponse_error').css('display', 'none');
		jQuery('#getresponse_link').css('display', 'inline');
	}									
}

function arfupdateformpreviewcss(locStr){
	var cssLink = jQuery('<link href="'+locStr+'" type="text/css" rel="Stylesheet" class="ui-theme" />');
	jQuery('#testiframe').contents().find("body").append(cssLink);
	if( jQuery('#testiframe').contents().find("link.ui-theme").size() > 3){
		jQuery('#testiframe').contents().find("link.ui-theme:first").remove();
	}	
}

function change_custom_width_field(id){
	if( jQuery('#frm_custom_width_field_'+id).is(':checked') ){
		jQuery('#frm_custom_width_field_'+id).val('1');
		jQuery('#frm_custom_width_field_'+id+'_div').removeAttr('disabled');
	} else {
		jQuery('#frm_custom_width_field_'+id).val('0');
		jQuery('#frm_custom_width_field_'+id+'_div').attr('disabled','disabled');
	}	
}

function reset_global_styling_settings(){
	var form_id = jQuery('#id').val();
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=global_reset_style_setting&form="+form_id,
	success:function(msg){ 
			jQuery('#frm-styling-action').html(msg);
			jQuery(".sltstandard select").selectpicker(); 				
			formChange1();
			frmSetPosClass('left');
			if(jQuery('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html().length > 0)
			{
				var submit_button = jQuery('#arfsubmitbuttontext').val();
				jQuery('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html(submit_button);
			}
			else if(jQuery('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html().length == 0)
			{
				var submit_button = jQuery('#arfsubmitbuttontext').val();
				jQuery('#testiframe').contents().find('.arf_submit_btn').find('.arfstyle-label').html(submit_button);	
			}
			var submit_value = jQuery('#arfsubmitbuttontext').val();
			jQuery('#arfeditorsubmit').text( submit_value );
			jQuery('.iframediv_loader').show();
			document.getElementById("iframediv").style.display = 'none';
			var checkstatus = 1;
			var checkradiostyle = "frm_check_radio_style";
			var checkradiocolor = "frm_check_radio_style_color";
			DoShow3(checkstatus, checkradiostyle,checkradiocolor);
		}
	});
}

function ClosePreview()
{
	jQuery('#form_preview_modal iframe').attr('src','');	
	jQuery('#form_preview_modal [data-dismiss="arfmodal"]').trigger("click");	
}

function arfstorebulkoptionvalue(id,opts)
{	
    jQuery('#frm_bulk_options_sel-'+id).val(JSON.parse(opts).join("\n"));
	if(document.getElementById('#frm_bulk_options_sel-'+id)!=null)
	{
		document.getElementById('#frm_bulk_options_sel-'+id).innerText = JSON.parse(opts).join("\n");
	}
    return false;
}

function arfupdatebulkoptions(field_id,opts){
	document.getElementById("arfshowfieldbulkoptions_success-"+field_id).style.display = "block";
	
	var type = jQuery('#field_type_'+field_id).val();
	
	var is_checkbox_radio = ( type == 'radio' || type == 'checkbox' ) ? 1 : 0;
	
	var checkboxradio_len = ( type == 'radio' || type == 'checkbox' ) ? jQuery('#arf_checkboxradio_'+field_id+' .arf_check_radio_fields').length : 0;
	
	jQuery.ajax({
		type:"POST",url:ajaxurl,
		data:{action:'arfpresetoptions',field_id:field_id,opts:opts, is_checkbox_radio: is_checkbox_radio, checkboxradio_len:checkboxradio_len },
		success:function(res){
		
			if( is_checkbox_radio == 1 )
			{
				var new_html 	= res.split('^|^'); 
				var		html 	= new_html[0];
				var input_html	= new_html[1];
			} else {
				var html = res; 
			}
			
			if( ! html ){
				return;
			}
			jQuery('#arfoptionul_'+field_id).html(html);
			
			if( jQuery('#separate_value_'+field_id).is(':checked') ){
				jQuery('.field_'+field_id+'_option_key').show();
				
				if( !jQuery('.field_'+field_id+'_option').hasClass('arfwithkey') ){
					jQuery('.field_'+field_id+'_option').addClass('arfwithkey');
				}
				jQuery('#arf_field_'+field_id+'_opts').attr('style','width:540px;');	
			} else {
				jQuery('.field_'+field_id+'_option_key').hide();
				
				if( jQuery('.field_'+field_id+'_option').hasClass('arfwithkey') ){
					jQuery('.field_'+field_id+'_option').removeClass('arfwithkey');
				}
				jQuery('#arf_field_'+field_id+'_opts').attr('style','width:330px;');
			}
				
			if(jQuery('select[name="item_meta['+field_id+']"]').length>0){
				var o=opts.replace(/\s\s*$/,'').split("\n");
				var sel='';
				sel +='<option value=""></option>'; 
				for (var i=0;i<o.length;i++){sel +='<option value="'+o[i]+'">'+o[i]+'</option>';}
				jQuery('select[name="item_meta['+field_id+']"]').html(sel);
			}
			if( is_checkbox_radio == 1 && input_html )
			{
				jQuery('#arf_checkboxradio_'+field_id).html( input_html );
			}
			arfcheckoptionlength( field_id );
			arfupdateoptionorder( field_id );
			if( is_checkbox_radio == 1 && input_html ) { CheckFieldPos_height(); }
			
			setTimeout(function(){ document.getElementById("arfshowfieldbulkoptions_success-"+field_id).style.display = "none"; },1000);
		}
	});	
}

function arfshowbulkfieldoptions1(id)
{
	document.getElementById("arfshowfieldbulkoptions-"+id).style.display = 'block';	
}

function addtextfield(id)
{
	var type = 'text';
	addnewfrmfield(id,type);	
}

function addtextareafield(id)
{
	var type = 'textarea';
	addnewfrmfield(id,type);	
}

function addcheckboxfield(id)
{
	var type = 'checkbox';
	addnewfrmfield(id,type);	
}

function addradiofield(id)
{
	var type = 'radio';
	addnewfrmfield(id,type);	
}

function addselectfield(id)
{
	var type = 'select';
	addnewfrmfield(id,type);	
}

function addcaptchafield(id)
{
	var type = 'captcha';
	addnewfrmfield(id,type);	
}

function addemailfield(id)
{
	var type = 'email';
	addnewfrmfield(id,type);	
}

function addurlfield(id)
{
	var type = 'url';
	addnewfrmfield(id,type);	
}

function adddividerfield(id)
{
	var type = 'divider';
	addnewfrmfield(id,type);	
}

function addbreakfield(id)
{
	var type = 'break';
	addnewfrmfield(id,type);	
}

function addfilefield(id)
{
	var type = 'file';
	addnewfrmfield(id,type);	
}

function addnumberfield(id)
{
	var type = 'number';
	addnewfrmfield(id,type);	
}

function addphonefield(id)
{
	var type = 'phone';
	addnewfrmfield(id,type);	
}

function adddatefield(id)
{
	var type = 'date';
	addnewfrmfield(id,type);	
}

function addtimefield(id)
{
	var type = 'time';
	addnewfrmfield(id,type);	
}

function addimagefield(id)
{
	var type = 'image';
	addnewfrmfield(id,type);	
}

function addscalefield(id)
{
	var type = 'scale';
	addnewfrmfield(id,type);	
}

function addhiddenfield(id)
{
	var type = 'hidden';
	addnewfrmfield(id,type);	
}

function addpasswordfield(id)
{
	var type = 'password';
	addnewfrmfield(id,type);	
}

function addhtmlfield(id)
{
	var type = 'html';
	addnewfrmfield(id,type);	
}

function addlikefield(id)
{
	var type = 'like';
	addnewfrmfield(id,type);	
}

function addsliderfield(id)
{
	var type = 'slider';
	addnewfrmfield(id,type);	
}

function addcolorpickerfield(id)
{
	var type = 'colorpicker';
	addnewfrmfield(id,type);	
}

function addimagecontrolfield(id)
{
	var type = 'imagecontrol';
	addnewfrmfield(id,type);	
}
function ShowCurrentStar(id)
{
	var selectboxval = document.getElementById("field_star_color_"+id).value;
	var selectbox_small_big = document.getElementById("field_star_size_"+id).value;
	var small_class = "";
	if(selectbox_small_big=="small") {
		small_class = "_small";
	}
	var newstarhtml = '<span class="star_1 ratings_stars ratings_stars_'+selectboxval+small_class+' ratings_over_'+selectboxval+small_class+'" data-color="'+selectboxval+'" data-val="1"></span>';
	document.getElementById("showlivestar_"+id).innerHTML = "";
	document.getElementById("showlivestar_"+id).innerHTML = newstarhtml;
}

function CheckFieldPos(is_set_height,is_page_load)
{
	jQuery('.show-field-options').hide();
	
	//console.log('inside checkposition');
	
	var queryArr = [];
	jQuery('.multicolfield').each(function(index) {
		if( jQuery(this).is(":checked") ){
		 var _locationId = index;
		 var _locID    = jQuery(this).attr('data-id');
		 var _locValue    = jQuery(this).val();
		 if( _locValue == 'arf_3' ){
		 	var _locPos  = 3;
		 } else if( _locValue == 'arf_2' ) {
		 	var _locPos  = 2;
		 } else {
		 	var _locPos  = 1;
		 }
		 var locations = {  
			"locationId":_locationId,                                
			"locID" 	:_locID,
			"locValue" 	:_locValue,
			"locPos" 	:_locPos  
		 };
		 queryStr = { "locations" : locations };
		 queryArr.push(queryStr);
		}
	 });

	var arf_class_two = '';
	var arf_class_three = '';
	var temp_class = '';
	
	var is_sec_col = 0;
	var is_sec_col_m3 = 0;
	var is_third_col_m3 = 0;
	
	var mylipos = 0;
	var my31collipos = 0;
	var my32collipos = 0;
	var addfor23columns = 0;
	var addfor33columns = 0;
	
	for ( var i=0; i < queryArr.length; i++){ 
		if( queryArr[i].locations.locID != '' && queryArr[i].locations.locValue != '' ) {
			if( queryArr[i].locations.locValue == 'arf_2' && arf_class_two =='' ){
				arf_class_two = '(First)';
				arf_class_three = '';
			} else if( queryArr[i].locations.locValue == 'arf_2' && arf_class_two !='' && arf_class_two == '(First)' ){
				arf_class_two = '(Second)';
				arf_class_three = '';
			} else if( queryArr[i].locations.locValue == 'arf_3' && arf_class_three =='' ){
				arf_class_three = '(First)';
				arf_class_two = '';
			} else if( queryArr[i].locations.locValue == 'arf_3' && arf_class_three !='' && arf_class_three == '(First)' ){
				arf_class_three = '(Second)';
				arf_class_two = '';
			} else if( queryArr[i].locations.locValue == 'arf_3' && arf_class_three !='' && arf_class_three == '(Second)'  ){
				arf_class_three = '(Third)';
				arf_class_two = '';
			} else if( queryArr[i].locations.locValue == 'arf_1' ) {
				arf_class_two 	= '';
				arf_class_three = '';
			}
			
			jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf31colclass arf21colclass arf_1col arf_3col arf_2col arf_23col');		
			if( arf_class_two != '' ) 	
			{
				var tmp_class = arf_class_two; 
				if( arf_class_two == '(Second)' )
				{					
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf31colclass arf_3col arf_23col').addClass('arf_2col');
				}
			}
			else if( arf_class_three !='' )		
			{
				var tmp_class = arf_class_three;
				if( arf_class_three == '(Second)' )
				{
					addfor23columns = 1;
				}
				if( arf_class_three == '(Third)' )
				{					
					addfor33columns = 1;
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf_2col arf_23col').addClass('arf_3col');
				}		
			}
			else
			{
				var tmp_class = '';
			}
			
			var plugin_image_path = jQuery('#plugin_image_path').val();
			var to_append = 'Multi Column: '+tmp_class;
			
			if( queryArr[i].locations.locValue == 'arf_2' )
			{
				jQuery('#arf_col_'+queryArr[i].locations.locID).html(to_append);
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('width','45.5%');
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('float','left');
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('clear','none');
				
				if( arf_class_two == "(First)" )
				{
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf31colclass arf_23col arf_31col arf_3col arf_2col'); //removed all class
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).addClass('arf21colclass');
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('clear','both');
				}
				else if( arf_class_two == "(Second)" )
				{
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf31colclass arf_23col arf_3col arf_31col arf21colclass');
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).addClass('arf_2col');	
				}
				
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf1columns arf3columns').addClass('arf2columns');	
			}
			else if( queryArr[i].locations.locValue == 'arf_3' )
			{
				jQuery('#arf_col_'+queryArr[i].locations.locID).html(to_append);
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('width','29%');
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('float','left');
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('clear','none');
					
				if( arf_class_three == "(First)" )
				{
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf_2col arf_23col arf_3col arf21colclass');
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).addClass('arf31colclass');
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('clear','both');
				}
				else if( arf_class_three == "(Second)" )
				{
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf31colclass arf_2col arf_31col arf_3col arf21colclass');
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).addClass('arf_23col');
				}
				else if( arf_class_three == "(Third)" )
				{
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf31colclass arf_2col arf_31col arf_23col arf21colclass');
					jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).addClass('arf_3col');
				}
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf2columns arf1columns').addClass('arf3columns');
			}
			else
			{
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('clear','both');
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('width','95%');
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('float','none');
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('height','auto');
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('border-right','none');			
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).addClass('arf_1col');				
				jQuery('#arf_col_'+queryArr[i].locations.locID).html(' ');
				
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf21colclass arf31colclass arf31col arf31colclass arf_2col arf_23col arf_3col arf21colclass'); 
																				   
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).removeClass('arf2columns arf3columns').addClass('arf1columns arf_1col');
			}
			
			
			if( arf_class_two == "(First)" ){
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('border-right','2px dashed #E6E6E6');
			} else if( arf_class_three == "(First)" ) {
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('border-right','2px dashed #E6E6E6');
			} else if( arf_class_three == "(Second)" ){
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('border-right','2px dashed #E6E6E6');
			} else {
				jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).css('border-right','none');
			}
			
			if( arf_class_two == '(Second)' ) { arf_class_two = ''; }
			if( arf_class_three == '(Third)' ) { arf_class_three = ''; }
			
		} // end of if cond.
	}
	// end of for loop.
	
	jQuery('.blankli2col').remove();
	jQuery('.blankli32col').remove();
	jQuery('.blankli33col').remove();
	jQuery('.blankli1col').remove();
	
	for ( var i=0; i < queryArr.length; i++)
	{
		if( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).hasClass('arf21colclass') && (!(jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('arf_2col')) ) && (!(jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('blankli2col')) ))
		{
			jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).after("<li class='blankli2col'>&nbsp;</li>");
		}
		
		if( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).hasClass('arf31colclass') && (!(jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('arf_23col')) ) )
		{
			jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).after("<li class='blankli32col'>&nbsp;</li>");
		}
		
		if( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).hasClass('arf31colclass') && ((jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('arf_23col')) || (jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('blankli32col')) ) && (!(jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().next().hasClass('arf_3col')) ) )
		{
			jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().after("<li class='blankli33col'>&nbsp;</li>");
		}
		
		if( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).hasClass('arf1columns') && !( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('blankli1col') )  )
		{
			jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).after("<li class='blankli1col'>&nbsp;</li>");
		}
		
		if( (jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).hasClass('arf21columns') || ( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('blankli2col') || jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('arf_2col') ) && ! ( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().next().hasClass('blankli1col') ) ) )
		{
			//alert('test');
			jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().after("<li class='blankli1col'>&nbsp;</li>");
		}
		
		if( (jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).hasClass('arf31colclass') && ( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('arf_23col') || jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().hasClass('blankli32col') ) &&  ( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().next().hasClass('arf_3col') || jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().next().hasClass('blankli33col')) && ! ( jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().next().next().hasClass('blankli1col'))  ) )
		{
			jQuery('#arfmainfieldid_'+queryArr[i].locations.locID).next().next().after("<li class='blankli1col'>&nbsp;</li>");
		}
		
	}
	
	jQuery('#new_fields li').each(function(){
		if( ( jQuery(this).hasClass('edit_field_type_hidden') || jQuery(this).hasClass('edit_field_type_imagecontrol') ) && !( jQuery(this).next().hasClass('blankli1col') )  )
		{
			jQuery(this).removeClass('arf21colclass arf2columns arf31colclass arf31col arf31colclass arf_2col arf_23col arf_3col arf21colclass'); 	
			jQuery(this).addClass('arf1columns').css('border-right', 'none');
			jQuery(this).after("<li class='blankli1col'>&nbsp;</li>");
		}									   
	});

	if( is_set_height == '1' )
	{
		setTimeout(function(){
			CheckFieldPos_height();
		}, 500);
	}
	
}

function save_pg_break_next_btn_val() {
	var first_next_btn = jQuery("#first_pg_break_next").val();
	jQuery("#page_break_first_next_btn_txt").val(first_next_btn);
}

function save_pg_break_pre_btn_val() {
	var first_pre_btn = jQuery("#first_pg_break_pre").val();
	jQuery("#page_break_first_pre_btn_txt").val(first_pre_btn);
}

function change_page_break_select( val ) {
	jQuery('#page_break_first_select').val(val);
	jQuery(".page_break_select").each(function(e){
												   
		if( jQuery(this).attr('id') !== undefined ){
			var page_break_select_id = jQuery(this).attr('id');
			//alert(val);
			//alert(page_break_select_id);
			//alert("HTML="+jQuery("#"+page_break_select_id+"_"+val).html());
			
			jQuery("."+page_break_select_id+"_dt").find("span").html(jQuery("#"+page_break_select_id+"_"+val).html());
			
			jQuery(this).val( val );
			//jQuery(this).selectpicker('refresh');
			
		}
	
	});
	
	if( val == 'wizard' ){
		jQuery('#arf_pagebreak_style').show();
		jQuery('#arf_surveypage_style').hide();
	} else {
		jQuery('#arf_pagebreak_style').hide();
		jQuery('#arf_surveypage_style').show();
	}
	
}

function checkpage_breakpos(){
	var i = 1;
	jQuery('.pagebreak_field').each(function(index) {
		var id = jQuery(this).val();
		if( i == 1 ) {
			jQuery('#pg_break_div_'+id).css('display', 'block');
			jQuery('#page_break_first_use_'+id).val('1');
			jQuery('#arf_page_break_label_'+id).html(__ARFSECONDPAGELABEL);
		} else {
			jQuery('#pg_break_div_'+id).css('display', 'none');
			jQuery('#page_break_first_use_'+id).val('0');
			var page_no = (i+1); 
			var pagelable = __ARFPAGELABELARRAY[page_no];
			if(__ARFPAGELABELARRAY.length < (page_no+1)){
				pagelable = page_no;
			}
			jQuery('#arf_page_break_label_'+id).html(pagelable+' '+__ARFPAGELABELTEXT);
		}
		i++;
	});
}

//---------- for conditional logic ----------//
function arf_cl_change(field_id){
	if( jQuery('#conditional_logic_'+field_id).is(":checked") ){
		jQuery('#conditional_logic_'+field_id).val('1');
		jQuery('#conditional_logic_div_'+field_id).show();	
	} else {
		jQuery('#conditional_logic_'+field_id).val('0');
		jQuery('#conditional_logic_div_'+field_id).hide();
	}
}

function add_new_rule(field_id){
	
	var rules = [];
	jQuery('input[name^="rule_array_'+field_id+'"]').each(function(){ rules.push(this.value); }); 
	
	if( rules.length > 0 )
	{
		var maxValueInArray = Math.max.apply(Math, rules);
		var next_rule = parseInt(maxValueInArray) + parseInt(1);
	}
	else
	{
		var next_rule = 1;
	}
	
	var input_hidden = '<input type="hidden" name="rule_array_'+field_id+'[]" value="'+next_rule+'" />';
	
	var field_menu = '<div class="sltstandard" style="float:none;display:inline-block;margin-right:10px;">';
	//field_menu += '<select name="arf_cl_field_'+field_id+'_'+next_rule+'" id="arf_cl_field_'+field_id+'_'+next_rule+'" class="field_dropdown_menu" data-width="150px">';
	
	var field_opt = '';
	var cntr = 0;
	var cntr_field_id = '';
	var cntr_field_name = '';
	
	jQuery('.arfmainformfield').each(function(i){
		if( jQuery(this).is(':visible') ) {
			
			var id = jQuery(this).attr('id');
			id = id.replace('arfmainfieldid_','');
			
			var f_id = jQuery('#field_type_'+id).attr('data-fid');
			var name = jQuery('#field_'+id).text();
			var type = jQuery('#field_type_'+id).val();
			
			if(cntr==0) {
				cntr_field_id = f_id;
				cntr_field_name = name;
			}
			
			if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'html' && type != 'file' && type != 'imagecontrol' ){
				//field_opt += '<option value="'+f_id+'">'+name+'</option>';
				field_opt += '<li class="arf_selectbox_option" data-value="'+f_id+'" data-label="'+name+'">'+name+'</li>';
			}
			cntr++;
		}
	});
	
	field_menu += '<input type="hidden" name="arf_cl_field_'+field_id+'_'+next_rule+'" id="arf_cl_field_'+field_id+'_'+next_rule+'" value="'+cntr_field_id+'" >';
	
	field_menu += '<dl class="arf_selectbox" data-name="arf_cl_field_'+field_id+'_'+next_rule+'" data-id="arf_cl_field_'+field_id+'_'+next_rule+'" style="width:130px;">';
	field_menu += '<dt class="arf_cl_field_'+field_id+'_'+next_rule+'_dt"><span>'+cntr_field_name+'</span>';
	field_menu += '<input type="text" class="arf_autocomplete" value="'+cntr_field_name+'" style="display:none;width:118px;">';
	field_menu += '<i class="fa fa-caret-down fa-lg"></i></dt>';
	field_menu += '<dd><ul class="field_dropdown_menu" style="display: none;" data-id="arf_cl_field_'+field_id+'_'+next_rule+'">';
	
	field_menu += field_opt;
	
	field_menu += '</ul></dd></dl>';
	
	field_menu += '</div>&nbsp;&nbsp;';
	
	var equals 		= (typeof(__ARFEQUALS)!='undefined') ? __ARFEQUALS : 'equals';
	var not_equals	= (typeof(__ARFNOTEQUALS)!='undefined') ? __ARFNOTEQUALS : 'not equals';
	var greater_than= (typeof(__ARFGREATER)!='undefined') ? __ARFGREATER : 'greater than';
	var less_than 	= (typeof(__ARFLESS)!='undefined') ? __ARFLESS : 'less than';
	var contains 	= (typeof(__ARFCONTAIN)!='undefined') ? __ARFCONTAIN : 'contains';
	var not_contains= (typeof(__ARFNOTCONTAIN)!='undefined') ? __ARFNOTCONTAIN : 'not contains';
	
	var operator_menu = '';
	
	operator_menu += '&nbsp;<div class="sltstandard" style="float:none;display:inline-block;margin-right:10px;">';
	
	//operator_menu += '<select name="arf_cl_op_'+field_id+'_'+next_rule+'" id="arf_cl_op_'+field_id+'_'+next_rule+'"class="operator_dropdown_menu" data-size="10" data-width="150px">';
	
	operator_menu += '<input type="hidden" name="arf_cl_op_'+field_id+'_'+next_rule+'" id="arf_cl_op_'+field_id+'_'+next_rule+'" value="is" >';
	
	operator_menu += '<dl class="arf_selectbox" data-name="arf_cl_op_'+field_id+'_'+next_rule+'" data-id="arf_cl_op_'+field_id+'_'+next_rule+'" style="width:130px;">';
	operator_menu += '<dt class="arf_cl_op_'+field_id+'_'+next_rule+'_dt"><span>'+equals+'</span>';
	operator_menu += '<input type="text" class="arf_autocomplete" value="'+equals+'" style="display:none;width:118px;">';
	operator_menu += '<i class="fa fa-caret-down fa-lg"></i></dt>';
	operator_menu += '<dd><ul class="operator_dropdown_menu" style="display: none;" data-id="arf_cl_op_'+field_id+'_'+next_rule+'">';
	
	/*operator_menu += '<option value="is">'+equals+'</option>';
	operator_menu += '<option value="is not">'+not_equals+'</option>';
	operator_menu += '<option value="greater than">'+greater_than+'</option>';
	operator_menu += '<option value="less than">'+less_than+'</option>';
	operator_menu += '<option value="contains">'+contains+'</option>';
	operator_menu += '<option value="not contains">'+not_contains+'</option>';
	*/
	
	operator_menu += '<li class="arf_selectbox_option" data-value="is" data-label="'+equals+'">'+equals+'</li>';
	operator_menu += '<li class="arf_selectbox_option" data-value="is not" data-label="'+not_equals+'">'+not_equals+'</li>';
	operator_menu += '<li class="arf_selectbox_option" data-value="greater than" data-label="'+greater_than+'">'+greater_than+'</li>';
	operator_menu += '<li class="arf_selectbox_option" data-value="less than" data-label="'+less_than+'">'+less_than+'</li>';
	operator_menu += '<li class="arf_selectbox_option" data-value="contains" data-label="'+contains+'">'+contains+'</li>';
	operator_menu += '<li class="arf_selectbox_option" data-value="not contains" data-label="'+not_contains+'">'+not_contains+'</li>';
	
	operator_menu += '</ul></dd></dl>';
	operator_menu += '</div>&nbsp;&nbsp;';
	
	var rule_value = '&nbsp;<input type="text" name="cl_rule_value_'+field_id+'_'+next_rule+'" id="cl_rule_value_'+field_id+'_'+next_rule+'" class="txtstandardnew" value="" />&nbsp;&nbsp;';
		
	var bulk_div = '&nbsp;<span class="bulk_add_remove">';
        bulk_div += '<span onclick="add_new_rule(\''+field_id+'\');" class="bulk_add">&nbsp;</span>&nbsp;';
        bulk_div += '<span onclick="delete_rule(\''+field_id+'\', \''+next_rule+'\');" class="bulk_remove">&nbsp;</span>';
        bulk_div += '</span>';
	
	var div_to_append = '<div id="arf_cl_rule_'+field_id+'_'+next_rule+'" class="cl_rules">' + input_hidden + field_menu + operator_menu + rule_value + bulk_div + '</div>';
	
	jQuery('#logic_rules_div_'+field_id).append(div_to_append);
	jQuery(".sltstandard select").selectpicker();
	jQuery('#logic_rules_div_'+field_id+' .bulk_remove').css('display', 'inline-block');
}

function delete_rule(field_id, rule_no) {
	var numrules = jQuery('#logic_rules_div_'+field_id+' .cl_rules').length;
	if(numrules > 1) {
		jQuery('#logic_rules_div_'+field_id+' #arf_cl_rule_'+field_id+'_'+rule_no).fadeOut().remove();
	} 
	if(numrules == 1){
		jQuery('#logic_rules_div_'+field_id+' #arf_cl_rule_'+field_id+'_'+rule_no).fadeOut().remove();
		jQuery('#arf_new_law_'+field_id).show();
		jQuery('#logic_rules_div_'+field_id).hide();
		jQuery('#conditional_logic_'+field_id).val('0');
		jQuery('#conditional_logic_'+field_id).attr('checked', false);
		jQuery('#conditional_logic_display_'+field_id).attr('disabled', true);//.selectpicker('refresh');
		jQuery('#conditional_logic_if_cond_'+field_id).attr('disabled', true);//.selectpicker('refresh');

		jQuery('#conditional_logic_display_'+field_id).parent().find("dt").addClass('arf_disable_selectbox');
		jQuery('#conditional_logic_if_cond_'+field_id).parent().find("dt").addClass('arf_disable_selectbox');
		
		if( typeof(__ARFADDRULE)!='undefined' )	{
			var atitle = __ARFADDRULE;
		} else {
			var atitle = 'Please add one or more rules';
		}
			
		jQuery('#conditional_logic_display_'+field_id).parents('.sltstandard').first().tooltipster({theme: 'arf_admin_tooltip', position:'top',contentAsHTML:true,onlyOne:true,content:atitle,multiple:true,maxWidth:400});
		
		jQuery('#conditional_logic_if_cond_'+field_id).parents('.sltstandard').first().tooltipster({theme: 'arf_admin_tooltip', position:'top',contentAsHTML:true,onlyOne:true,content:atitle,multiple:true,maxWidth:400});
		
	}
}

function change_cl_field_menu(field_id){
	var field_opt = '';
		if( jQuery('#'+field_id).is(':visible') ) {
			var id = field_id.replace('arfmainfieldid_','');		
			var f_id = jQuery('#field_type_'+id).attr('data-fid');
			var name = jQuery('#field_'+id).text();
			var type = jQuery('#field_type_'+id).val();
			if(name == ''){
				name = jQuery('#field_'+id+' input').val();
			}
			if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'html' && type != 'file' && type != 'imagecontrol' ){
				//field_opt = '<option value="'+f_id+'">'+name+'</option>';		
				field_opt = '<li class="arf_selectbox_option" data-value="'+f_id+'" data-label="'+name+'">'+name+'</li>';
			}
		}

		if( field_opt != '' ) {	
			jQuery('.field_dropdown_menu').not('#arf_cl_field_'+f_id+'_1').each(function(i){
				jQuery(this).append(field_opt);
				//jQuery(this).selectpicker('refresh');
			});
		}
		//alert("puted");
}

function delete_cl_field_menu(field_id, f_id){
	//var f_id = jQuery('#field_type_'+field_id).attr('data-fid');
	jQuery('.field_dropdown_menu').each(function(i){
		//var op_selected  = jQuery(this).find('option:selected').val();
		var op_selected  = jQuery(this).val();
		if( op_selected !== undefined && op_selected == f_id ){
			delete_rule_dropdown( jQuery(this).attr('id') );
		}
		jQuery(this).find("li[data-value='"+f_id+"']").remove();
		//jQuery(this).selectpicker('refresh');
	});
}

function update_cl_field_menu(){
	jQuery('.field_dropdown_menu').each(function(i){
		var $globalselect = jQuery(this);
		jQuery('.arfmainformfield').each(function(j){
			if( jQuery(this).is(':visible') ) {
				var id = jQuery(this).attr('id');
				id = id.replace('arfmainfieldid_','');
				var f_id = jQuery('#field_type_'+id).attr('data-fid');
				var name = jQuery('#field_'+id).text();
				if(name == ''){
					name = jQuery('#field_'+id+' input').val();
				}
				var type = jQuery('#field_type_'+id).val();
				if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'html' && type != 'file' && type != 'imagecontrol' ) {
					
					if( $globalselect.find('li[data-value="'+f_id+'"]').length > 0 )
					{
						$globalselect.find('li[data-value="'+f_id+'"]').html(name);
						$globalselect.find('li[data-value="'+f_id+'"]').attr("data-label",name);
					}
					else {
						//var field_opt = '<option value="'+f_id+'">'+name+'</option>';
						var field_opt = '<li class="arf_selectbox_option" data-value="'+f_id+'" data-label="'+name+'">'+name+'</li>';
						$globalselect.append(field_opt);
					}
				}	
			}
		});
		//$globalselect.selectpicker('refresh');		
	});
}

function arf_add_new_law(id){
	jQuery('#arf_new_law_'+id).hide();
	jQuery('#conditional_logic_'+id).val('1');
	jQuery('#conditional_logic_'+id).attr('checked', 'checked');
	jQuery('#conditional_logic_display_'+id).attr('disabled', false);//.selectpicker('refresh');
	jQuery('#conditional_logic_if_cond_'+id).attr('disabled', false);//.selectpicker('refresh');
	
	jQuery('#conditional_logic_display_'+id).parent().find("dt").removeClass('arf_disable_selectbox');
	jQuery('#conditional_logic_if_cond_'+id).parent().find("dt").removeClass('arf_disable_selectbox');
	
	try {
		jQuery('#conditional_logic_display_'+id).parents('.sltstandard').first().tooltipster('destroy');
		jQuery('#conditional_logic_display_'+id).parents('.sltstandard').first().removeAttr('title');	
		jQuery('#conditional_logic_if_cond_'+id).parents('.sltstandard').first().tooltipster('destroy');
		jQuery('#conditional_logic_if_cond_'+id).parents('.sltstandard').first().removeAttr('title');
	} catch(e){ }
	
	add_new_rule( id );
	jQuery('#logic_rules_div_'+id).css('display', 'inline-block');
}

//---------- for conditional logic ----------//

//-- custom css block --//

function arf_field_open_css_block(field_id, id){
	jQuery('#custom_css_classes_'+field_id+' .arf_custom_css_block_style').not('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_style').slideUp(function(){
		jQuery('#custom_css_classes_'+field_id+' .arf_custom_css_block_title').not('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_style').removeClass('open');																																																																																		
	});
	jQuery('#custom_css_classes_'+field_id+' .arf_custom_css_block_title .field_bulk').not('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_style .field_bulk').removeClass('open');	
	if( jQuery('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_style').is(':visible') ){
		jQuery('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_title .field_bulk').removeClass('open');
		jQuery('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_style').slideUp(function(){
			jQuery('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_title').removeClass('open');
		});
	} else if ( !jQuery('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_style').is(':visible') ) {
		jQuery('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_title .field_bulk').addClass('open');		
		jQuery('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_style').slideDown(function(){
			jQuery('#custom_css_classes_'+field_id+' #'+id+' .arf_custom_css_block_title').addClass('open');			
		});
	}
}	//-- custom css block --//

function arf_remove_css_block(id){
	jQuery('#'+id+'_btn').removeClass('arfactive');
	jQuery('#'+id).html(' ');
	jQuery('#'+id).remove();
}

function add_custom_css_block(id, title, remove){
	if( jQuery('#arf_custom_css_block').find('#'+id).length == 0 ) {
		jQuery('#'+id+'_btn').addClass('arfactive');
		
		var div_to_append = '<div id="'+id+'" class="arf_form_custom_css_block"><div class="arf_form_css_tab"><div class="arf_form_custom_css_block_title">'+title+'</div> </div> <div class="arf_form_custom_css_block_style"><textarea name="options['+id+']" style="width:430px !important;" cols="50" rows="4" class="arfplacelonginput txtmultinew"></textarea></div><div class="arfcustomcssclose" onclick="arf_remove_css_block(\''+id+'\');"></div><br/><div class="lblsubtitle" style="float:left;clear:both;">e.g. display:block;</div></div>';
		jQuery('#arf_custom_css_block').append(div_to_append);
	}
	
	jQuery('#arf_custom_css_block').find('#'+id+' textarea').focus();
	jQuery(window.opera?'html, .arfmodal-body':'html, body, .arfmodal-body').animate({ scrollTop: jQuery('#arf_custom_css_block').find('#'+id).offset().top-250 }, 'slow' );
		
	return false;
}

function arf_open_css_block(id){
	jQuery('.custom_css_block_style').not('#'+id+' .custom_css_block_style').slideUp();
	jQuery('#'+id+' .custom_css_block_style').slideToggle();
}

function OpenInNewTab(url )
{
  var win=window.open(url, '_blank');
  win.focus();
}

function changerecaptchaimage(form_id,image_id, field_id)
{
	if(image_id == 'recaptcha')
	{
		jQuery('#recaptcha_'+form_id).show();
		jQuery('#custom-captcha_'+form_id).hide();
		jQuery('#field-option-'+field_id+' .property').hide();
	}
	else
	{
		jQuery('#recaptcha_'+form_id).hide();
		jQuery('#custom-captcha_'+form_id).show();
		jQuery('#field-option-'+field_id+' .property').show();
	}
	
	if(image_id == 'custom-captcha')
	{
		jQuery('#setup_captcha_message').hide();
		jQuery('#arfmainfieldid_'+field_id).removeClass('arf-recaptcha').addClass('arf-custom-captcha');
	}
	else
	{
		jQuery('#setup_captcha_message').show();
		jQuery('#arfmainfieldid_'+field_id).removeClass('arf-custom-captcha').addClass('arf-recaptcha');
	}
	
	if( image_id == 'custom-captcha' ){
		jQuery('#arfmainfieldid_'+field_id).find('#field_custom_css_tab').show();
	} else {
		jQuery('#arfmainfieldid_'+field_id).find('#field_custom_css_tab').hide();
	}
}

function delete_rule_dropdown(dropdown_id){
	if( dropdown_id != '' && jQuery('#'+dropdown_id).length > 0 )
	{	
		var field_arr = dropdown_id.split('_');			
		var field_id = field_arr[3];
		var rule_number = field_arr[4];
		if( field_id != '' && rule_number != '' )
		{
			if( jQuery('#conditional_logic_'+field_id).is(":checked") )
			{
				var numrules = jQuery('#logic_rules_div_'+field_id+' .cl_rules').length;
				if( numrules > 1 )
				{
					delete_rule(field_id, rule_number);
				} else {
					//jQuery('#'+dropdown_id+' option:first-child').attr('selected', 'selected');
					//jQuery('#'+dropdown_id).selectpicker('refresh');
					//jQuery('#arf_cl_op_'+field_id+'_'+rule_number+' option:first-child').attr('selected', 'selected');
					//jQuery('#arf_cl_op_'+field_id+'_'+rule_number).selectpicker('refresh');
					jQuery('#cl_rule_value_'+field_id+'_'+rule_number).val('');
					
					jQuery('#conditional_logic_'+field_id).attr("checked", false);
					jQuery('#conditional_logic_'+field_id).val('0');
					
					jQuery('#conditional_logic_display_'+field_id).attr('disabled', true);//.selectpicker('refresh');
					jQuery('#conditional_logic_if_cond_'+field_id).attr('disabled', true);//.selectpicker('refresh');
					
					if( typeof(__ARFADDRULE)!='undefined' ){
						var atitle = __ARFADDRULE;
					} else {
						var atitle = 'Please add one or more rules';
					}
					jQuery('#conditional_logic_display_'+field_id).parents('.sltstandard').first().tooltipster({theme: 'arf_admin_tooltip', position:'top',contentAsHTML:true,onlyOne:true,content:atitle,multiple:true,maxWidth:400});
					
					jQuery('#conditional_logic_if_cond_'+field_id).parents('.sltstandard').first().tooltipster({theme: 'arf_admin_tooltip', position:'top',contentAsHTML:true,onlyOne:true,content:atitle,multiple:true,maxWidth:400});
					
					jQuery('#arf_new_law_'+field_id).show();
					jQuery('#logic_rules_div_'+field_id).hide();
				}
			} 			
		} // end of if
		
	}
}

/*This is new function of field option 04-Jan-2014*/
function arf_open_field_option(id, type, flag){
	
	if (!jQuery('#field-option-'+id+' .'+type).is(':hidden')) {
		return false;
	}
	
	jQuery('#field-option-'+id+' .fieldoption_inner_tab').removeClass('fieldoption_inner_tab_selected');
	
	if(flag==1){
		jQuery('#field-option-'+id+' .arf_fieldoptiontab').css('display', 'none').removeClass('in').addClass('flip out');
	} else {
		jQuery('#field-option-'+id+' .arf_fieldoptiontab').slideUp(1000).removeClass('in').addClass('flip out');
	}
	
	if( type == 'field_basic_option' )
	{
		jQuery('#field-option-'+id+' #field_basic_option_tab').addClass('fieldoption_inner_tab_selected');
		if(flag==1){
			jQuery('#field-option-'+id+' .field_basic_option').css('display', 'block').removeClass('out').addClass('in');
		} else {
			jQuery('#field-option-'+id+' .field_basic_option').slideDown(1000).removeClass('out').addClass('in');
		}
	}
	else if( type == 'field_custom_css' )	
	{
		jQuery('#field-option-'+id+' #field_custom_css_tab').addClass('fieldoption_inner_tab_selected');
		if(flag==1){
			jQuery('#field-option-'+id+' .field_custom_css').css('display', 'block').removeClass('out').addClass('in');
		} else {
			jQuery('#field-option-'+id+' .field_custom_css').slideDown(1000).removeClass('out').addClass('in');
		}
	}
	else if( type == 'field_conditional_law' )
	{
		jQuery('#field-option-'+id+' #field_conditional_law_tab').addClass('fieldoption_inner_tab_selected');
		
		if(flag==1){
			jQuery('#field-option-'+id+' .field_conditional_law').css('display', 'block').removeClass('out').addClass('in');
		} else {
			jQuery('#field-option-'+id+' .field_conditional_law').slideDown(1000).removeClass('out').addClass('in');
		}
	}
	
	//if( jQuery('#main_fieldoptions_modal_'+id).hasClass('arftop') || jQuery('#main_fieldoptions_modal_'+id).hasClass('arfleft') )
	setTimeout(function(){
		arf_change_offset( id, 0 );
	}, 1000);
}

function submitMainForm()
{
	jQuery('#frm_main_form').submit();
}

function check_import_form_selected()
{
	var import_val = jQuery( "#frm_add_form_id" ).val();	
	
	var opt_export = jQuery("input[name='opt_export']:checked").val();
	var ARFSCRIPTURL_cus =  jQuery("#ARFSCRIPTURL_cus" ).val();
	
	if (import_val==null || import_val=="")
  	{
		jQuery('#arf_xml_select_form_error').show();
		return false;
	}
	else
	{
		jQuery('#arf_xml_select_form_error').hide();
		
		if(opt_export == 'opt_export_entries')
		{
			location.href=ARFSCRIPTURL_cus+'&controller=entries&form='+import_val+'&arfaction=csv&bulk_export=yes';
			return false;
		}
		else
		{
			jQuery('#import_modal_form [data-dismiss="modal"]').trigger("click");
			return true;
		}
	}	
}
function check_import_file_selected()
{
	jQuery("#file_name_error").css('display','none');
	jQuery("#file_not_error").css('display','none');
	jQuery("#file_name_new").css('display','');
	
	var importFile = jQuery( "#importFile" ).val();	
	var extension = importFile.substr( (importFile.lastIndexOf('.') +1) );
	if (importFile==null || importFile=="")
  	{
		alert('Please select file');		
		return false;
	}
	else if(extension != 'zip')
	{
		jQuery("#file_name_error").css('display','');
		jQuery("#file_name_new").css('display','none');
		file_name_error
		return false;
	}
	else
	{
		return true;
	}	
}

function arf_form_preview_load(id)
{
	arf_open_main_tab(id);
}

function arf_open_main_tab(id){
	if( id == 'form' ){
		
		jQuery("#post-body-content").css('height','');
		jQuery("#maineditcontentview").css('position','');
		jQuery("#maineditcontentview").css('height','auto');	
		jQuery('#arf_form_li_nav').addClass('active_tab');
		jQuery('#arf_style_li_nav').removeClass('active_tab');
		
		jQuery('#arf_main_style_tab').removeClass('active_tabs');
		jQuery('#arf_main_style_tab').hide();
		
		jQuery('#arfmainformeditorcontainer').addClass('active_tabs');	
		jQuery('#arfmainformeditorcontainer').show();
		
		jQuery('#arfmainfieldlist_style').css('display','none');
		
		jQuery("#arfmainformeditorcontainer").css('display','block');
		jQuery("#arf_main_style_tab").css('display','none');
		
		jQuery('#frm-styling-action').css('visibility','hidden');			// for show style tab
		jQuery('#frm-styling-action').css('display','none');
		
		jQuery('#arfmainfieldlist').css('display','block');
		
		jQuery( "#arfmainfieldlist" ).removeClass("arffieldlist1");
		jQuery("#maineditcontentview").css('margin-top','90px');
		
		arf_change_form_tab('editor');
	} else if( id == 'style' ) {
		jQuery('#arf_form_li_nav').removeClass('active_tab');
		jQuery('#arf_style_li_nav').addClass('active_tab');
		
		jQuery('#arfmainformeditorcontainer').removeClass('active_tabs');
		jQuery('#arfmainfieldlist').hide();
		
		jQuery('#arf_main_style_tab').addClass('active_tabs');
		jQuery('#arfmainfieldlist_style').show();
		
		jQuery('#arfmainfieldlist_style').css('display','block');
		
		jQuery("#arfmainformeditorcontainer").css('display','none');
		jQuery("#arf_main_style_tab").css('display','block');
		
		jQuery('#frm-styling-action').css('visibility','visible');			// for show style tab
		jQuery('#frm-styling-action').css('display','block');
		
		jQuery('.iframediv_loader').show();
	
		jQuery( "#arfmainfieldlist" ).addClass("arffieldlist1");
		
		if( jQuery('#preview-form-styling-setting .current_widget').length == 0 ){
			jQuery('#preview-form-styling-setting #first_tab .widget-title').trigger('click');
		}
		
		DoShow1('1');
	}
}

function add_field_fun(block)
{
	is_show = jQuery('#'+block).css('display');
	if(is_show == 'block')
	{
		jQuery('#'+block).fadeOut('slow');			
	}
	else
	{
		jQuery('.main_field_modal').hide();
		jQuery('#'+block).parent('.main_field_modal').show();
		jQuery('#'+block).fadeIn('slow');
	}
}

function close_add_field_subject(block)
{
	jQuery('#'+block).fadeOut('slow');
	jQuery('.arf_prefix_postfix_wrapper').hide();
}

function arfclosefileallowed(class_name, type){
	if( type == 1 ){
		jQuery('.'+class_name).show();
	} else {
		jQuery('.'+class_name).hide();
	}
}

function arf_change_field_padding(id){
	var value1 = jQuery('#'+id+'_1').val();	
	var value2 = jQuery('#'+id+'_2').val();	
	var value3 = jQuery('#'+id+'_3').val();	
	var value4 = jQuery('#'+id+'_4').val();
	
	var final_val = '';
	
	if( value1 != '' ){
		final_val += value1+'px ';
	} else {
		final_val += '0px ';
	}
		
	if( value2 != '' ){
		final_val += value2+'px ';
	} else {
		final_val += '0px ';
	}

	if( value3 != '' ){
		final_val += value3+'px ';
	} else {
		final_val += '0px ';
	}
	
	if( value4 != '' ){
		final_val += value4+'px';
	} else {
		final_val += '0px';
	}
	
	jQuery('#'+id).val(final_val).trigger('change');	
}
function arfshowformsettingpopup(whichshow)
{
	if(jQuery('#'+whichshow).css('display')=="none") 
	{
		//jQuery('#'+whichshow).find('select').selectize();
		jQuery('#'+whichshow).fadeIn('fast'); 
	}
	else 
	{	
		jQuery('#'+whichshow).fadeOut('fast');
	}
}
jQuery(document).mouseup(function(e)
{							 
    var container = jQuery('.arffontstylesettingmainpopupbox:visible');
	var arffontawesomemodal = jQuery('.arffontawesomemodal:visible');
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
		var id = container.attr('id');
		if( id ){
	 		jQuery('#'+id).fadeOut('fast');
		}
    }
	if( !arffontawesomemodal.is(e.target) && arffontawesomemodal.has(e.target).length === 0 ){
		var arffmodalid = arffontawesomemodal.attr('id');
		if( arffmodalid ){
			jQuery('#'+arffmodalid).hide();
		}
	}
});
function Changefontsettinghtml(whichshow,fontfamilyid,fontstyleid,fontsizeid)
{
	if(whichshow!="")
	{
		var font_familyid = "";	
		var font_styleid = "";
		var font_sizeid = "";
		var fullupdatehtml = "";
		if(fontfamilyid!="")
		{
			 font_familyid = document.getElementById(fontfamilyid).value;
			 fullupdatehtml = fullupdatehtml + font_familyid;
		}
		if(fontsizeid!="")
		{
			 font_sizeid = document.getElementById(fontsizeid).value;
			 fullupdatehtml = fullupdatehtml + ", "+font_sizeid+"px";
		}
		if(fontstyleid!="")
		{
			 font_styleid = document.getElementById(fontstyleid).value;
			 if(font_styleid!="normal")
			 {
			 	fullupdatehtml = fullupdatehtml + ", "+font_styleid;
			 }
		}
		jQuery('#show'+whichshow).html(fullupdatehtml);
	}
}

function deactivate_license()
{
	jQuery('#deactivate_loader').css('display', 'inline');
	
	jQuery.ajax({
		
		type:"POST",url:ajaxurl,

		data:"action=arfdeactivatelicense",

		success: function(html)
		{ 
			jQuery('#deactivate_loader').css('display', 'none');
			if(html == "License Deactivted Sucessfully.")
			{
				jQuery('#deactivate_success').css('display', '');
			}
			else
			{
				jQuery('#deactivate_error').css('display', '');
			}
		}

	});
	
	return false;		
}

function arf_change_opt_val(fid, id, val, old_value){
	
	var sep_value = ( jQuery('#separate_value_'+fid).is(':checked') ) ? 1 : 0;
	var type = jQuery('#field_type_'+fid).val();
	
	if( type == 'radio' || type == 'checkbox' ){
		
		if( id.indexOf('field_key') >= 0 && sep_value == 1 ){
			var f_id = id.replace('field_key_', '');
			var value = (val == '(Blank)' || val == '' ) ? '' : val;
			jQuery('#fieldcheck_'+f_id).val(value);
			
		} else if( id.indexOf('field_') >= 0 && sep_value == 0 ){
			
			var f_id = id.replace('field_', '');
			var value = (val == '(Blank)' || val == '' ) ? '' : val;
			jQuery('#fieldcheck_'+f_id).val(value);		
		}
	
	} else if( type == 'select' ){
		
		if( fid ){
			arfreordercheckradio( fid );
		}
	}
		
}

function arf_change_class_css( fid, prop_id ){
	var value = jQuery('textarea[name="field_options['+prop_id+']"]').val();
	
	if( value != '' && value !== undefined ){
		jQuery('#arf_custom_css_block_title_'+prop_id).addClass('arf_active_css');
	} else {
		jQuery('#arf_custom_css_block_title_'+prop_id).removeClass('arf_active_css');
	}
	
}

function arf_removeVariableFromURL(url_string, variable_name) {
    var URL = String(url_string);
    var regex = new RegExp( "\\?" + variable_name + "=[^&]*&?", "gi");
    URL = URL.replace(regex,'?');
    regex = new RegExp( "\\&" + variable_name + "=[^&]*&?", "gi");
    URL = URL.replace(regex,'&');
    URL = URL.replace(/(\?|&)$/,'');
    regex = null;
    return URL;
  }
  
function arf_change_name_dropdown(field_id){
	
	// for name type dropdown
	var field_opt = '';
	if( jQuery('#'+field_id).is(':visible') ) {
		var id = field_id.replace('arfmainfieldid_','');		
		var f_id = jQuery('#field_type_'+id).attr('data-fid');
		var name = jQuery('#field_'+id).text();
		var type = jQuery('#field_type_'+id).val();
		if(name == ''){
			name = jQuery('#field_'+id+' input').val();
		}
		if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'html' && type != 'imagecontrol' ){
			//field_opt = '<option value="'+f_id+'">'+name+'</option>';		
			field_opt = '<li class="arf_selectbox_option" data-value="'+f_id+'" data-label="'+name+'">'+name+'</li>';
		}
	}

	if( field_opt != '' ) {	
		jQuery('.arf_name_field_dropdown').each(function(i){
			jQuery(this).append(field_opt);
			//jQuery(this).selectpicker('refresh');
		});
	}
	
	// for change email field dropdown
	var field_opt = '';
	if( jQuery('#'+field_id).is(':visible') ) {
		var id = field_id.replace('arfmainfieldid_','');		
		var f_id = jQuery('#field_type_'+id).attr('data-fid');
		var name = jQuery('#field_'+id).text();
		var type = jQuery('#field_type_'+id).val();
		if(name == ''){
			name = jQuery('#field_'+id+' input').val();
		}
		if(type == 'text' || type == 'email' || type == 'hidden' || type == 'select' || type == 'radio'){
			//field_opt = '<option value="'+f_id+'">'+name+'</option>';		
			field_opt = '<li class="arf_selectbox_option" data-value="'+f_id+'" data-label="'+name+'">'+name+'</li>';
		}
	}

	if( field_opt != '' ) {	
		jQuery('.arf_email_field_dropdown').each(function(i){
			jQuery(this).append(field_opt);
			//jQuery(this).selectpicker('refresh');
		});
	}
	
	// for add field dropdown, name type
	var fields_list_subject	= '';
	var fields_list_message	= '';
	var fields_list_admin_message = '';
	var fields_list_user_from_email = '';
	var fields_list_admin_from_email = '';
	var fields_list_addtotal = '';
	var fields_list_admin_to_email = '';
        var fields_list_admin_to_email_subject = '';
	
	jQuery('.arfmainformfield').each(function(j){
		if( jQuery(this).is(':visible') ) {
			var id = jQuery(this).attr('id');
			id = id.replace('arfmainfieldid_','');
			var f_id = jQuery('#field_type_'+id).attr('data-fid');
			var name = jQuery('#field_'+id).text();
			if(name == ''){
				name = jQuery('#field_'+id+' input').val();
			}
			var type = jQuery('#field_type_'+id).val();
                        
			if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'html' && type != 'imagecontrol' ) {
				
				fields_list_addtotal += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddtotalfield(this,\''+f_id+'\',\'\')">'+name+'</div>';

			} 
			
                        if( type !='divider' && type !='break' && type != 'captcha' && type != 'html' && type !='imagecontrol' && type != 'file' && type != 'like'){
                            fields_list_admin_to_email_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'admin_email_subject\',\''+f_id+'\')">'+name+'</div>';
                            fields_list_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_email_subject\',\''+f_id+'\')">'+name+'</div>';
                        }
                        
			if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'imagecontrol' ) {
				fields_list_message += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_email_message\',\''+f_id+'\')">'+name+'</div>';
				fields_list_admin_message += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_admin_email_message\',\''+f_id+'\')">'+name+'</div>';
			}
					
			if( type == 'text' || type == 'email' || type == 'hidden' || type == 'radio' || type == 'select'){
				fields_list_user_from_email += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_user_from_email\',\''+f_id+'\')">'+name+'</div>';
				
				fields_list_admin_from_email += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_admin_from_email\',\''+f_id+'\')">'+name+'</div>';
				
				fields_list_admin_to_email += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'options_admin_reply_to_notification\',\''+f_id+'\'">'+name+'</div>';
                                
			}
		}
	});
	
	jQuery('#add_field_subject .arfmodal-body_p').html(fields_list_subject);
	jQuery('#add_field_message .arfmodal-body_p').html(fields_list_message);
	jQuery('#add_field_admin_message .arfmodal-body_p').html(fields_list_admin_message);
	jQuery('#add_field_user_email .arfmodal-body_email').html(fields_list_user_from_email);
	jQuery('#add_field_admin_email .arfmodal-body_email').html(fields_list_admin_from_email);
	jQuery('#add_field_admin_email_to .arfmodal-body_email').html(fields_list_admin_to_email);
        jQuery('#add_field_admin_email_subject .arfmodal-body_email').html(fields_list_admin_to_email_subject);
	jQuery('.arftotalfielddropdown .arfmodal-body_p').html(fields_list_addtotal);
	
}

function arf_update_name_dropdown(){
	
	// for change name field dropdown
	jQuery('.arf_name_field_dropdown').each(function(i){
		var $globalselect = jQuery(this);
		jQuery('.arfmainformfield').each(function(j){
			if( jQuery(this).is(':visible') ) {
				var id = jQuery(this).attr('id');
				id = id.replace('arfmainfieldid_','');
				var f_id = jQuery('#field_type_'+id).attr('data-fid');
				var name = jQuery('#field_'+id).text();
				if(name == ''){
					name = jQuery('#field_'+id+' input').val();
				}
				var type = jQuery('#field_type_'+id).val();
				if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'html' && type != 'imagecontrol' ) {
					
					if( $globalselect.find('li[data-value="'+f_id+'"]').length > 0 )
					{
						$globalselect.find('li[data-value="'+f_id+'"]').html(name);
						$globalselect.find('li[data-value="'+f_id+'"]').attr("data-label",name);
					}
					else {
						//var field_opt = '<option value="'+f_id+'">'+name+'</option>';
						var field_opt = '<li class="arf_selectbox_option" data-value="'+f_id+'" data-label="'+name+'">'+name+'</li>';
						$globalselect.append(field_opt);
					}
				}	
			}
		});
		//$globalselect.selectpicker('refresh');		
	});
	
	// for change email field dropdown
	jQuery('.arf_email_field_dropdown').each(function(i){
		var $globalselect = jQuery(this);
		jQuery('.arfmainformfield').each(function(j){
			if( jQuery(this).is(':visible') ) {
				var id = jQuery(this).attr('id');
				id = id.replace('arfmainfieldid_','');
				var f_id = jQuery('#field_type_'+id).attr('data-fid');
				var name = jQuery('#field_'+id).text();
				if(name == ''){
					name = jQuery('#field_'+id+' input').val();
				}
				var type = jQuery('#field_type_'+id).val();
				if(type == 'text' || type == 'email' || type == 'hidden' || type == 'radio' || type == 'select') {
					//alert($globalselect.find('li[data-value="'+f_id+'"]').length);
					if( $globalselect.find('li[data-value="'+f_id+'"]').length > 0 )
					{
						$globalselect.find('li[data-value="'+f_id+'"]').html(name);
						$globalselect.find('li[data-value="'+f_id+'"]').attr("data-label",name);
					}
					else {
						//var field_opt = '<option value="'+f_id+'">'+name+'</option>';
						var field_opt = '<li class="arf_selectbox_option" data-value="'+f_id+'" data-label="'+name+'">'+name+'</li>';
						$globalselect.append(field_opt);
					}
				}	
			}
		});
		//$globalselect.selectpicker('refresh');		
	});
	
	
	// for add field dropdown, name type
	var fields_list_subject	= '';
	var fields_list_message	= '';
	var fields_list_admin_message = '';
	var fields_list_user_from_email = '';
	var fields_list_admin_from_email = '';
	var fields_list_addtotal = '';
	var fields_list_admin_to_email = '';
        var fields_list_admin_to_email_subject = '';
	
	jQuery('.arfmainformfield').each(function(j){
		if( jQuery(this).is(':visible') ) {
			var id = jQuery(this).attr('id');
			id = id.replace('arfmainfieldid_','');
			var f_id = jQuery('#field_type_'+id).attr('data-fid');
			var name = jQuery('#field_'+id).text();
			if(name == ''){
				name = jQuery('#field_'+id+' input').val();
			}
			var type = jQuery('#field_type_'+id).val();
			if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'html' && type != 'imagecontrol' ) {
				
				if(type == "checkbox")
				{
					
					fields_list_addtotal += '<div class="modal_field_val_bold" id="arfmodalfieldval_'+id+'" onclick="return false">'+name.substring(0,40)+'</div>';
					
					var option = '';
					var type = jQuery('#field_type_'+id).val();
					
					if( jQuery('#separate_value_'+id).is(':checked') ){
						var is_sep = 1; 
					} else {
						var is_sep = 0;	
					}
					var selected= jQuery('select[name="item_meta['+id+']"]').val();
					
					var i = 0;
					jQuery('#arfoptionul_'+id+' li').each(function(){
						var keys 	= jQuery(this).attr('id');
							keys 	= keys.split('-');
						var key		= keys[1];
					if( jQuery('#field_'+id+'-'+key).hasClass('editInPlace-active') )
					{	
						var label 	= jQuery('#field_'+id+'-'+key).find('input').val();
						if( label === undefined ){
							label 	= jQuery('#field_'+id+'-'+key).text();
						}
					} else {
						var label 	= jQuery('#field_'+id+'-'+key).text();
					}
					
					if( type == 'select' && label == '(Blank)' ){
						label = '';	
					}
						var value	= jQuery('#field_key_'+id+'-'+key).text();
						var checked = jQuery('#fieldcheck_'+id+'-'+key).is(':checked') ? 'checked="checked"' : ''; 
						
						if( ! is_sep ){
							value 	= label;
						}
						i++;
						
						if( type == 'checkbox' ){
							fields_list_addtotal += '<div class="modal_field_val" id="arfmodalfieldval_'+id+'_'+key+'" onclick="arfaddtotalfield(this,\''+id+'\',\''+key+'\')">&nbsp;&nbsp;&nbsp;&nbsp;'+label.substring(0,40)+'</div>';
						}
					});
					
				}
				else
				{
					fields_list_addtotal += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddtotalfield(this,\''+f_id+'\',\'\')">'+name.substring(0,40)+'</div>';
				}
				
				//fields_list_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_email_subject\',\''+f_id+'\')">'+name+'</div>';
                               // fields_list_admin_to_email_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'admin_email_subject\',\''+f_id+'\')">'+name+'</div>';

			} 
			
                        if( type !='divider' && type !='break' && type != 'captcha' && type != 'html' && type !='imagecontrol' && type != 'file' && type != 'like'){
                            fields_list_admin_to_email_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'admin_email_subject\',\''+f_id+'\')">'+name+'</div>';
                            fields_list_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_email_subject\',\''+f_id+'\')">'+name+'</div>';
                        }
                        
			if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'imagecontrol' ) {
				fields_list_message += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_email_message\',\''+f_id+'\')">'+name+'</div>';
				fields_list_admin_message += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_admin_email_message\',\''+f_id+'\')">'+name+'</div>';
                                
			} 
			
			
			if( type == 'text' || type == 'email' || type == 'hidden' || type=='radio' || type == 'select' ){
				fields_list_user_from_email += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_user_from_email\',\''+f_id+'\')">'+name+'</div>';
				
				fields_list_admin_from_email += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_admin_from_email\',\''+f_id+'\')">'+name+'</div>';
				
				fields_list_admin_to_email += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'options_admin_reply_to_notification\',\''+f_id+'\')">'+name+'</div>';
                                
			}
			
		}
	});
	
	jQuery('#add_field_subject .arfmodal-body_p').html(fields_list_subject);
	jQuery('#add_field_message .arfmodal-body_p').html(fields_list_message);
	jQuery('#add_field_admin_message .arfmodal-body_p').html(fields_list_admin_message);
	jQuery('#add_field_user_email .arfmodal-body_email').html(fields_list_user_from_email);
	jQuery('#add_field_admin_email .arfmodal-body_email').html(fields_list_admin_from_email);
	jQuery('#add_field_admin_email_to .arfmodal-body_email').html(fields_list_admin_to_email);
        jQuery('#add_field_admin_email_subject .arfmodal-body_email').html(fields_list_admin_to_email_subject);
	jQuery('.arftotalfielddropdown .arfmodal-body_p').html(fields_list_addtotal);
}

function arf_delete_name_dropdown(field_id, f_id){
	
	// for name type dropdown
	jQuery('.arf_name_field_dropdown').each(function(i){
		var op_selected  = jQuery(this).find('option:selected').val();
		if( op_selected !== undefined && op_selected == f_id ){
			delete_rule_dropdown( jQuery(this).attr('id') );
		}
		jQuery(this).find("li[data-value='"+f_id+"']").remove();
		//jQuery(this).selectpicker('refresh');
	});
	
	// for eamil type dropdown
	jQuery('.arf_email_field_dropdown').each(function(i){
		var op_selected  = jQuery(this).find('option:selected').val();
		if( op_selected !== undefined && op_selected == f_id ){
			delete_rule_dropdown( jQuery(this).attr('id') );
		}
		jQuery(this).find("li[data-value='"+f_id+"']").remove();
		//jQuery(this).selectpicker('refresh');
	});
	
	// for add field dropdown, name type
	var fields_list_subject	= '';
	var fields_list_message	= '';
	var fields_list_admin_message = '';
	var fields_list_user_from_email = '';
	var fields_list_admin_from_email = '';
	var fields_list_addtotal = '';
	var fields_list_admin_to_email = '';
        var fields_list_admin_to_email_subject = '';
	
	jQuery('.arfmainformfield').not('#arfmainfieldid_'+field_id).each(function(j){
		if( jQuery(this).is(':visible') ) {
			var id = jQuery(this).attr('id');
			id = id.replace('arfmainfieldid_','');
			var f_id = jQuery('#field_type_'+id).attr('data-fid');
			var name = jQuery('#field_'+id).text();
			if(name == ''){
				name = jQuery('#field_'+id+' input').val();
			}
			var type = jQuery('#field_type_'+id).val();
			if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'html' && type != 'imagecontrol' ) {
				
				//fields_list_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_email_subject\',\''+f_id+'\')">'+name+'</div>';
				
				fields_list_addtotal += '<div class="modal_field_val"  id="arfmodalfieldval_'+f_id+'" onclick="arfaddtotalfield(this,\''+f_id+'\',\'\')">'+name+'</div>';
                                
                                //fields_list_admin_to_email_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'admin_email_subject\',\''+f_id+'\')">'+name+'</div>';
                        }
                        
                        if( type !='divider' && type !='break' && type != 'captcha' && type != 'html' && type !='imagecontrol' && type != 'file' && type != 'like'){
                            fields_list_admin_to_email_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'admin_email_subject\',\''+f_id+'\')">'+name+'</div>';
                            fields_list_subject += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_email_subject\',\''+f_id+'\')">'+name+'</div>';
                        }
			
			if(type != 'divider' && type != 'break' && type != 'captcha' && type != 'imagecontrol' ) {
				fields_list_message += '<div class="modal_field_val"  id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_email_message\',\''+f_id+'\')">'+name+'</div>';
				fields_list_admin_message += '<div class="modal_field_val"  id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_admin_email_message\',\''+f_id+'\')">'+name+'</div>';
			}
			
			if( type == 'text' || type == 'email' || type == 'hidden' || type == 'radio' || type == 'select' ){
				fields_list_user_from_email += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_user_from_email\',\''+f_id+'\')">'+name+'</div>';
				
				fields_list_admin_from_email += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'ar_admin_from_email\',\''+f_id+'\')">'+name+'</div>';
				
				fields_list_admin_to_email += '<div class="modal_field_val" id="arfmodalfieldval_'+f_id+'" onclick="arfaddcodefornewfield(\'options_admin_reply_to_notification\',\''+f_id+'\')">'+name+'</div>';

			}
		}
	});
	
	jQuery('#add_field_subject .arfmodal-body_p').html(fields_list_subject);
	jQuery('#add_field_message .arfmodal-body_p').html(fields_list_message);
	jQuery('#add_field_admin_message .arfmodal-body_p').html(fields_list_admin_message);
	jQuery('#add_field_user_email .arfmodal-body_email').html(fields_list_user_from_email);
	jQuery('#add_field_admin_email .arfmodal-body_email').html(fields_list_admin_from_email);
	jQuery('#add_field_admin_email_to .arfmodal-body_email').html(fields_list_admin_to_email);
        jQuery('#add_field_admin_email_subject .arfmodal-body_email').html(fields_list_admin_to_email_subject);
	jQuery('.arftotalfielddropdown .arfmodal-body_p').html(fields_list_addtotal);
}



function arfduplicatefield(form_id,field_type, duplicate_id, old_field_id)
{
	if( !duplicate_id ){
		return;
	}	
	var pg_break_pre_first = jQuery("#page_break_first_pre_btn_txt").val();
	var pg_break_next_first = jQuery("#page_break_first_next_btn_txt").val();
	var pg_break_first_select = jQuery("#page_break_first_select").val();
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arfinsertnewfield&form_id="+form_id+"&field="+field_type+"&pg_break_pre_first="+pg_break_pre_first+"&pg_break_next_first="+pg_break_next_first+"&field_duplicate_id="+duplicate_id+"&pg_break_first_select="+pg_break_first_select,
		success:function(msg){
			jQuery('#new_fields').append(msg); 
			jQuery('#new_fields li:last .arfeditorfieldopt_label').click(); 			
			CheckFieldPos('1','0');
			update_cl_field_menu();	
			arf_update_name_dropdown();
			checkpage_breakpos();
			//jQuery(".sltstandard select").selectpicker();
		}
	});
	
	//jQuery(".sltstandard select").selectpicker(); 

}

function arf_change_field_spacing2(){
	var final_val = '';
	var vertical 	= jQuery('#arffieldinnermarginsetting_1').val();
	var horizontal 	= jQuery('#arffieldinnermarginsetting_2').val();	
	
	vertical = ( vertical == '' || vertical == 'undefined' ) ? 0 : vertical;
	
	horizontal = ( horizontal == '' || horizontal == 'undefined' ) ? 0 : horizontal;
	
	final_val = vertical+'px '+horizontal+'px '+vertical+'px '+horizontal+'px';
	
	jQuery('#arffieldinnermarginsetting').val(final_val).trigger('change');
}

function arf_change_field_spacing(){
	var vertical 	= jQuery('#arffieldinnermarginssetting_1_exs').slider('getValue');
	var horizontal 	= jQuery('#arffieldinnermarginssetting_2_exs').slider('getValue');
	var final_val = '';
		
	vertical = ( vertical == '' || vertical == 'undefined' ) ? 0 : vertical;
	
	horizontal = ( horizontal == '' || horizontal == 'undefined' ) ? 0 : horizontal;
	
	final_val = vertical+'px '+horizontal+'px '+vertical+'px '+horizontal+'px';
	
	jQuery('#arffieldinnermarginsetting').val(final_val).trigger('change');
}

function arf_change_slider_class(field_id) {
	var type = jQuery('#slider_handle_'+field_id).val();
	if( type == 'triangle' ){
		jQuery('#slider_sample_'+field_id).removeClass('slider_class slider_class2').addClass('slider_class3');	
	} else if( type == 'square'){
		jQuery('#slider_sample_'+field_id).removeClass('slider_class2 slider_class3').addClass('slider_class2');			
	} else {
		jQuery('#slider_sample_'+field_id).removeClass('slider_class2 slider_class3').addClass('slider_class');		
	}

}

function arf_open_inputsetting(){
	jQuery('#arf_preview_tab').trigger('click');
	
	if( !jQuery('#preview-form-styling-setting #tabinputfieldsettings').hasClass('current_widget') )
	{
		jQuery('#preview-form-styling-setting #tabinputfieldsettings .widget-title').trigger('click');
		
		setTimeout(function(){
			jQuery(window.opera?'html':'html, body').animate({
				scrollTop: jQuery( jQuery('#preview-form-styling-setting #tabinputfieldsettings') ).offset().top-100
			}, 1000);
		}, 100);	
	}
	else 
	{
		jQuery(window.opera?'html':'html, body').animate({
			scrollTop: jQuery( jQuery('#preview-form-styling-setting #tabinputfieldsettings') ).offset().top-100
		}, 1000);
	}
	return false;
}

function arf_selectform( id )
{
	jQuery('.arf_modalform_box').removeClass('arfactive');	
	jQuery('#arftemplate_'+id).addClass('arfactive');
	
	if( id == 'blankform' )
	{
		jQuery('#arfnewaction').val('new');
		jQuery('#template_list_id').val('');
		jQuery('#template_list_id').attr('disabled', true);
	}
	else
	{
		jQuery('#arfnewaction').val('duplicate');
		jQuery('#template_list_id').val(id);
		jQuery('#template_list_id').attr('disabled', false);
	}
}

function arfchangeplaceholder( id )
{	
	var placeholder_text = jQuery('#placeholdertext_'+id).val();
	
	if( placeholder_text !== undefined ){
		jQuery('#itemmeta_'+id).val( placeholder_text );
	}
}

function changeclockhours(val, id, fldid, defaultval)
{
	var myfieldid = "field_default_hour_"+id;
	var mynewval = val;
	
	var mynewdropdown = '';
	var optselected = '';
	
	mynewdropdown += '<select id="field_'+fldid+'" name="field_options[default_hour_'+fldid+']" >';
	for (var i = 0; i <= mynewval; i++) {
		if(i == defaultval){
			optselected = "selected='selected'";
		} else {
			optselected = '';
		}
        mynewdropdown += '<option value="'+i+'" '+optselected+' >'+i+'</option> ';
    }
	
	mynewdropdown += '</select>';
	mynewdropdown += '<br /> <div class="howto">&nbsp;(HH)</div>';
	
	jQuery('#'+myfieldid).html( mynewdropdown );
}

function arfchangelabelname( id )
{	
	var placeholder_text = jQuery('#arfname_'+id).val();

	if( placeholder_text === undefined ){
		return;	
	}
	update_cl_field_menu();	
	arf_update_name_dropdown();				
	
	var def_title = '(Click here to add text)';
	if( typeof(__ARFDEFAULTTITLE) != 'undefined' ){
		var def_title = __ARFDEFAULTTITLE;
	}
	placeholder_text = jQuery.trim( placeholder_text );
	if( placeholder_text == '' ){
		jQuery('#field_'+id).text( def_title );
	} else {
		jQuery('#field_'+id).text( placeholder_text );
	}
}

function arfchangeitemmeta( id )
{
	var placeholder_text = jQuery('#itemmeta_'+id).val();
	
	if( placeholder_text !== undefined ){
		jQuery('#placeholdertext_'+id).val( placeholder_text );
	}
}

function arfshowem( id )
{
	//alert( id );		
	jQuery('.arfemailbars').removeClass('arfactive');
	jQuery('#arfem_'+id).addClass('arfactive');	
	jQuery('.arfemdiv').hide();
	jQuery('#arfem_'+id+'_div').show();
}

function arfgetformpreview()
{
	var frameSrc = jQuery('#arfpreviewbtn').attr("data-url");
	var modalheight = jQuery(window).height();
	var modalwidth = jQuery(window).width();
	var getModalWidth = Number(modalwidth) * 0.80;	
	var getModalLeftWidth = (Number(modalwidth) * 0.20) / 2;
	
	var getModalHeight = Number(modalheight) - 100;
	var modalbodyheight = getModalHeight - 144 + 82;
	var loaderheight = (modalbodyheight / 2) - 50;
	var loaderleft = ( getModalWidth / 2 ) - 50;

	jQuery('#form_previewmodal').attr('style','display:none; width:'+getModalWidth+'px; height:'+getModalHeight+'px; top:50px; left:'+getModalLeftWidth+'px');
	
	jQuery('#form_previewmodal .arfdevices').removeClass('arfactive');
	jQuery('#form_previewmodal #arfcomputer').addClass('arfactive');
	
	jQuery('#form_previewmodal').attr('data-modalwidth', getModalWidth);
	jQuery('#form_previewmodal').attr('data-modalleft', getModalLeftWidth);
	
	jQuery('#form_previewmodal .arfmodal-body').attr('style','overflow:hidden; clear:both; padding:0; height:'+modalbodyheight+'px');
    jQuery('#form_previewmodal').on('show', function () {
		jQuery('#form_previewmodal iframe').attr("style","display:none");												  
		jQuery('#form_previewmodal .iframe_loader').attr("style",'display:block; top:'+loaderheight+'px;left:'+loaderleft+'px;');												
		jQuery('#form_previewmodal iframe').attr("src", frameSrc);     
	});
	
	var form_id = jQuery('#id').val();	
	var form 	= jQuery('#frm_main_form').serialize();
	
	//var newformvalues = filterformdata(jQuery("#frm_main_form").serializeArray());
	var fields = jQuery("#frm_main_form").FilterFormData();
	
	/*var frmsa = jQuery("#frm_main_form").serializeArray();
	for( var key in frmsa ){
		var k = frmsa[key].name;
		var v = frmsa[key].value;
		if( k.search(/(.*?)\[(.*?)\]/) != -1 ){
			var x = k.replace(/(.*?)\[(.*?)\]/,'$2');
			var m = k.replace(/(.*?)\[(.*?)\]/,'$1');
			fields[m] = {};
			fields[m][x] = {};
		}
	}
	
	for( var key in frmsa ){
		var k = frmsa[key].name;
		var v = frmsa[key].value;
		if( k.search(/(.*?)\[(.*?)\]/) != -1 ){
			var x = k.replace(/(.*?)\[(.*?)\]/,'$2');
			var m = k.replace(/(.*?)\[(.*?)\]/,'$1');
			fields[m][x] = v;
		} else {
			fields[k] = v;
		}
	}*/
	fields['form_id'] = form_id;
	//fields['form_preview'] = form_preview;
	fields['action'] = 'arfformsavealloptions';
	var jsondata = jQuery.toJSON( fields );
	var mysack = new sack( ajaxurl );
	mysack.execute = 0;
	mysack.method = 'POST';
	mysack.setVar( "action", "arfformsavealloptions" );
	mysack.setVar( "form_id", form_id );
	//mysack.setVar( "form_preview",form_preview );
	mysack.setVar( "filtered_form", jsondata );
	mysack.onError = function() { alert('<?php echo esc_js(__("Ajax error while saving form", "ARForms")) ?>' )};
	mysack.onCompletion = loaded_ajax_DoShow3;
	mysack.runAJAX();
	
	function loaded_ajax_DoShow3(){
		
		var msg = mysack.response;
		
		var reponse 	= msg.split('^|^'); 
		var	sucmessage 	= reponse[0];
		var new_html	= reponse[1];
		
		if(sucmessage == 'deleted'){ window.location = __ARFDELETEURL; }
		else { jQuery('#form_previewmodal').arfmodal({show:true}); }
	
	}
	/*return false;
	
	var nvals = jQuery('form').serializeObject();
	nvals['form_id'] = form_id;
	nvals['action'] = 'arfformsavealloptions';
	nvals = JSON.stringify( nvals );
	nvals = nvals.replace(/&/g,'[AND]');
	nvals = nvals.replace(/\+/g,'[PLUS]');
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arfformsavealloptions&form_id="+form_id+"&nforms="+nvals,
			success:function(msg){
				
				var reponse 	= msg.split('^|^'); 
				var	sucmessage 	= reponse[0];
				var new_html	= reponse[1];
				
				if(sucmessage == 'deleted'){ window.location = __ARFDELETEURL; }
				else { jQuery('#form_previewmodal').arfmodal({show:true}); }
			}
	});*/
	
	jQuery('#arfdevicepreview').load(function(){								  	
		jQuery('#form_previewmodal .iframe_loader').attr("style",'display:none'); 
		jQuery('#form_previewmodal iframe').attr("style","display:block");
		
		if(document.getElementById("display_title_form").value=='0'){
			var value='none';
		} else {
			var value = 'block';
		}
		jQuery('#arfdevicepreview').contents().find('.formtitle_style').attr("style","display:"+value); 
		jQuery('#arfdevicepreview').contents().find('.formdescription_style').attr("style","display:"+value);	

		var checkbox_class = '';
		var chk_style = jQuery('#frm_check_radio_style').val();
		var chk_color = jQuery('#frm_check_radio_style_color').val();
		
		if( chk_style != 'none'  ){
			checkbox_class = chk_style;
			
			if(chk_style != 'futurico' && chk_style != 'polaris' && chk_color != 'default'){
				checkbox_class = checkbox_class+'-'+chk_color;
			}
			jQuery('#arfdevicepreview').contents().find('.arf_form input[type="checkbox"]').on('ifChanged', function(event){
				jQuery(this).trigger('change');
			});
			
			jQuery('#arfdevicepreview').contents().find('.arf_form input[type="radio"]').on('ifChecked', function(event){
				jQuery(this).trigger('click');
			});
			
			jQuery('#arfdevicepreview').contents().find('.arf_form input[type="checkbox"]').on('ifClicked', function(event){
				jQuery(this).trigger('focus');
			});
			
			jQuery('#arfdevicepreview').contents().find('.arf_form input[type="radio"]').on('ifClicked', function(event){
				jQuery(this).trigger('focus');
			});
												
			jQuery('#arfdevicepreview').contents().find('.arf_form input').not('.arf_hide_opacity').iCheck({
				checkboxClass: 'icheckbox_'+checkbox_class,
				radioClass: 'iradio_'+checkbox_class 
			});																	
		}
		
		var theme_css = jQuery("input[name='arffths']").val();
		var calender_url = jQuery('#calender_url').val();
		var css = calender_url+theme_css+'_jquery-ui.css';
		arfupdateformpreviewcss(css);
	});
}

function arfchangedevice( id )
{
	jQuery('.arfdevices').removeClass('arfactive');	
	jQuery('#arf'+id).addClass('arfactive');
	
	jQuery('#arfdevicepreview').contents().find('.popover').remove();
	jQuery('#arfdevicepreview').contents().find('.help-block').empty().removeClass('animated bounceInDownNor');	
		
	if( id == 'mobile' )
	{
		jQuery('#arfdevicepreview').contents().find('#arfdevicebody').attr('class', 'arfdevicemobile');
		var modalwidth 	= jQuery(window).width();
		var left_width 	= Number(modalwidth) / 2;
			left_width = left_width - 190;
		jQuery('#form_previewmodal').css('left', left_width+'px');
		jQuery('#form_previewmodal').css('width', '380px');
	}
	else if( id == 'tablet' )
	{
		jQuery('#arfdevicepreview').contents().find('#arfdevicebody').attr('class', 'arfdevicetablet');
		var modalwidth 	= jQuery(window).width();
		var left_width 	= Number(modalwidth) / 2;
			left_width = left_width - 414;
		jQuery('#form_previewmodal').css('left', left_width+'px');
		jQuery('#form_previewmodal').css('width', '828px');
	}
	else
	{
		jQuery('#arfdevicepreview').contents().find('#arfdevicebody').attr('class', 'arfdevicecomputer');
		var getmodalwidth 	= jQuery('#form_previewmodal').attr('data-modalwidth');
		var left_width 		= jQuery('#form_previewmodal').attr('data-modalleft');
		jQuery('#form_previewmodal').css('left', left_width+'px');
		jQuery('#form_previewmodal').css('width', getmodalwidth+'px');
	}
	
	arfpreviewdevicechanged();	// change slider tooltip
	
	if( jQuery('#arfdevicepreview').contents().find('.arf_form .arfformfield.arf_error').length > 0 )
	{
		setTimeout(function(){
			jQuery('#arfdevicepreview').contents().find('.arf_submit_btn').trigger('click');
		}, 500);
	}
	if( id == 'mobile' )
	{
	var tabindex = 1;
		jQuery('#arfdevicepreview').contents().find('input, textarea, select, .vpb_input_fields').each(function() {
			if (this.type != "hidden") {
				var $input = jQuery(this);
				//console.log($input.attr("name")+ " ==>",$input.attr("tabindex"));
				$input.attr("tabindex", tabindex);
				//console.log("AFTER ===>",$input.attr("tabindex"));
				tabindex++;
			}
		});
	}
}

function arflistchangedevice( id )
{
	jQuery('.arfdevices').removeClass('arfactive');	
	jQuery('#arf'+id).addClass('arfactive');
	
	jQuery('#arfdevicepreview').contents().find('.popover').remove();
	jQuery('#arfdevicepreview').contents().find('.help-block').empty().removeClass('animated bounceInDownNor');	
		
	if( id == 'mobile' )
	{
		jQuery('#arfdevicepreview').contents().find('#arfdevicebody').attr('class', 'arfdevicemobile');
		var modalwidth 	= jQuery(window).width();
		var left_width 	= Number(modalwidth) / 2;
			left_width = left_width - 190;
		jQuery('#form_preview_modal').css('left', left_width+'px');
		jQuery('#form_preview_modal').css('width', '380px');
	}
	else if( id == 'tablet' )
	{
		jQuery('#arfdevicepreview').contents().find('#arfdevicebody').attr('class', 'arfdevicetablet');
		var modalwidth 	= jQuery(window).width();
		var left_width 	= Number(modalwidth) / 2;
			left_width = left_width - 414;
		jQuery('#form_preview_modal').css('left', left_width+'px');
		jQuery('#form_preview_modal').css('width', '828px');
	}
	else
	{
		jQuery('#arfdevicepreview').contents().find('#arfdevicebody').attr('class', 'arfdevicecomputer');
		var getmodalwidth 	= jQuery('#form_preview_modal').attr('data-modalwidth');
		var left_width 		= jQuery('#form_preview_modal').attr('data-modalleft');
		jQuery('#form_preview_modal').css('left', left_width+'px');
		jQuery('#form_preview_modal').css('width', getmodalwidth+'px');
	}
	
	arfpreviewdevicechanged();	// change slider tooltip
	
	if( jQuery('#arfdevicepreview').contents().find('.arf_form .arfformfield.arf_error').length > 0 )
	{
		setTimeout(function(){
			jQuery('#arfdevicepreview').contents().find('.arf_submit_btn').trigger('click');
		}, 500);
	}
	
}

function arfsaveaddtosite()
{
	setTimeout(function(){
		jQuery('.arfaddtosite_btn').hide();
		jQuery('.arfaddtositeshortcode').show();
	}, 1000);
}

function arfselectsetting( id )
{
	return;
}

function onScroll(event)
{
//jQuery(window).scroll(function(e){
	// Cache selectors
	var lastId,
    topMenu = jQuery(".arfsettingleftmenu"),
    menuItems = topMenu.find(".arfsettingli"),
    scrollItems = menuItems.map(function(){
      var item = jQuery(this);
      if (item.length) { return item; }
    });

	// Get container scroll position
   	var fromTop = jQuery(this).scrollTop() + 160;
   
   	// Get id of current scroll item
   	var cur = scrollItems.map(function(){ 
		var fid = jQuery(this).attr('id');
			fid = fid.replace('arfsetting_', '');
		if ( jQuery('#arf_'+fid).is(':visible') )
		{
			if ( jQuery('#arf_'+fid).offset().top < fromTop ){
		   		return this;
			}
		}
   	});
   
   	// Get the id of the current element
  	cur = cur[cur.length-1];
   	var id = cur && cur.length ? cur[0].id : "";
   
   	if (lastId !== id) {
		menuItems.removeClass("arfactive");
		jQuery('#'+id).addClass("arfactive");
   	}                   
}

//reset slider tooltip
function arfresetslider()
{
	jQuery('#arf_isformchange').val('0')
	jQuery('.widget').removeClass('current_widget');
	jQuery('#preview-form-styling-setting #tabformsettings').addClass('current_widget');
	jQuery('#preview-form-styling-setting #tabformsettings .widget-inside').show().css('padding', '5px 14px 5px 10px');
	jQuery('.widget .widget-inside').show().css('height', '0px').css('padding', '0px');
	
	var fixedheightofheader_footer = 375;
		
		var fullwindowheight = (window.screen.height - 100);
		
		var remainingheight = Number(fullwindowheight) - fixedheightofheader_footer;
		
	jQuery('#preview-form-styling-setting #tabformsettings .widget-inside').css('height', remainingheight+"px").css('padding', '5px 14px 5px 10px');
	
	setTimeout(function(){
		jQuery('.arf_slider').each(function(){
			var slider_id	= jQuery(this).attr('data-slider-id');								
			var id			= jQuery(this).attr('id');		
			var ac_id		= id.replace('_exs', ''); 
			var slider_val 	= jQuery('#'+id).slider('getValue');
			var	slider_val1	= parseFloat( jQuery.trim(slider_val) );
			
			jQuery('#'+slider_id).trigger('mousedown').trigger('mouseup');
			jQuery('#'+id).slider('setValue', slider_val1);
			
			if( ac_id != 'arfmainform_opacity' )
			{
				if( ac_id == 'arffieldinnermarginssetting_1' ){
					jQuery('#arffieldinnermarginsetting_1').val( slider_val1 );
				} else if( ac_id == 'arffieldinnermarginssetting_2' ) {
					jQuery('#arffieldinnermarginsetting_2').val( slider_val1 );	
				} else {
					jQuery('#'+ac_id).val( slider_val1 );	
				}
			}
			else
			{
				var slider_val2 = ( slider_val1 == 0 || slider_val1 == 1 ) ? slider_val1 : ( slider_val1 / 10 ).toFixed(2);	
				jQuery('#'+ac_id).val( slider_val2 );		
			}
	
		});
		
		arf_change_field_spacing2();
		
		var fixedheightofheader_footer = 375;
		
		var fullwindowheight = (window.screen.height - 100);
		
		var remainingheight = Number(fullwindowheight) - fixedheightofheader_footer;
	
		jQuery('.widget .widget-inside').css('height', remainingheight+"px").css('padding', '5px 14px 5px 10px').hide(); 
		jQuery('.widget .widget-inside').css('padding', '5px 14px 5px 10px').hide(); 
		jQuery('#preview-form-styling-setting #tabformsettings .widget-inside').show();		
		jQuery('#arf_isformchange').val('1');
		
	}, 100);	
}

jQuery(document).mouseup(function(e)
{
	var container 		= jQuery('.show-field-options:visible');
	var container_tab 	= jQuery('.field-setting-button');
	var container_color = jQuery('.colpick_hex');
	var container_arf_modal_box = jQuery('.arf_modal_box');
	var container_prefix = jQuery('div.arf_prefix_postfix_wrapper:visible');
	var fontawesome_modal = jQuery('div.arffontawesomemodal');
	
	var field_dropdown_menu = jQuery('.arf_selectbox');
		//for button icon model
	var Iebrowser = true;
	var ua = window.navigator.userAgent;
	var msie = ua.indexOf("MSIE ");
	
	if (msie > 0) {  
	  var Iebrowser_version = parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)));
	  	if(parseInt(Iebrowser_version) < 10){
			var Iebrowser = false;
		}
	} 
	if(Iebrowser){
		if(jQuery(".arf_selectbox").find('dd').find('ul').is(':visible')){
			return false;
		}
	}
		
		
    if (!container.is(e.target) && container.has(e.target).length === 0 && !container_tab.is(e.target) && container_tab.has(e.target).length === 0 && !container_color.is(e.target) && container_color.has(e.target).length === 0 && !container_arf_modal_box.is(e.target) && container_arf_modal_box.has(e.target).length === 0 && !container_prefix.is(e.target) && container_prefix.has(e.target).length === 0 && !fontawesome_modal.is(e.target) && fontawesome_modal.has(e.target).length === 0) 
    {
		var id = container.attr('id');
		if( id )
		{
			container.find('[data-dismiss="arfmodal"]').trigger("click");
			container_prefix.hide();
		}
    }
	
	// for running total
	var container 		= jQuery('.arftotalfielddropdown:visible');
    if (!container.is(e.target) && container.has(e.target).length === 0 ) 
    {
		var id = container.attr('id');
		if( id ){
			container.find('[data-dismiss="arfmodal"]').trigger("click");
		}
    }
	
});

function arf_fieldremove_css_block(field_id, id){
	jQuery('#'+id+'_'+field_id+'_btn').removeClass('arfactive');
	jQuery('#arf_'+id+'_'+field_id).html(' ');
	jQuery('#arf_'+id+'_'+field_id).remove();
}

function add_fieldcustom_css_block(field_id, id, title){
	if( jQuery('#custom_css_blocks_'+field_id).find('#arf_'+id+'_'+field_id).length == 0 ) {
		jQuery('#'+id+'_'+field_id+'_btn').addClass('arfactive');
		
		var div_to_append = '<div id="arf_'+id+'_'+field_id+'" class="arf_form_custom_css_block"><div class="arf_form_css_tab"><div class="arf_form_custom_css_block_title">'+title+'</div> </div> <div class="arf_form_custom_css_block_style"><textarea name="field_options['+id+'_'+field_id+']" style="width:430px !important;" cols="50" rows="4" class="arfplacelonginput txtmultinew"></textarea></div><div class="arfcustomcssclose" onclick="arf_fieldremove_css_block(\''+field_id+'\', \''+id+'\');"></div><br/><div class="lblsubtitle" style="float:left;clear:both;">e.g. display:block;</div></div>';
		
		jQuery('#custom_css_blocks_'+field_id).append(div_to_append);
	}
		
	return false;
}

/* for advance field */
jQuery(document).on('click', function(){
	jQuery('.arf_advanceemailfield').each(function(){
		var $elm = jQuery(this);
		var input = $elm[0];
		if (input.setSelectionRange) {
			pos_start	= input.selectionStart;
			pos_end		= input.selectionEnd;
			jQuery(this).attr('data-startpos', pos_start);
		} 
	});
});


function filterformdata(elearray)
{
	var formarray = elearray;
	
	for (index = 0; index < formarray.length; ++index) 
	{
		var mydescpos = formarray[index].name.search("description"); 
		var myplhlderpos = formarray[index].name.search("placeholdertext");
		var mymaxrpos = formarray[index].name.search("max");
		var myclearfieldpos = formarray[index].name.search("frm_clear_field");
		var mydefaultblnkpos = formarray[index].name.search("frm_default_blank");
		var myrulearrpos = formarray[index].name.search("rule_value");
		var myfldwidthpos = formarray[index].name.search("field_width");
		var myreqindipos = formarray[index].name.search("required_indicator");
		var myitemmetapos = formarray[index].name.search("item_meta");
		
		var myformnamepos = formarray[index].name.search("form_name");
		var myformdescpos = formarray[index].name.search("form_desc");
		
		if(myitemmetapos >= 0 || myformnamepos >= 0 || myformdescpos >= 0 )
		{
			if(formarray[index].value == ""){
				delete formarray[index];
			}
		}
		
		if(myreqindipos > 0 || myplhlderpos > 0)
		{
			delete formarray[index];
		}
		
		if(mydescpos > 0 || mymaxrpos > 0 || myclearfieldpos > 0 || mydefaultblnkpos > 0 || myrulearrpos > 0 || myfldwidthpos > 0)
		{
			if(formarray[index].value == ""){
				delete formarray[index];
			}
		}
	}

	return newformvalues = jQuery.param(formarray);
}

function arfchangedeletemodalwidth( class_name )
{
	var modalwidth 	= jQuery(window).width();
	var left_width 	= Number(modalwidth) / 2;
		left_width 	= left_width - 280;
		
	var modalheight = jQuery(window).height();
	var top_height 	= Number(modalheight) / 2;
		top_height 	= top_height - 100;		
	
	jQuery('.'+class_name).css('left', left_width+'px');
	jQuery('.'+class_name).css('top', top_height+'px');	
}


function arfchangesubmitvalue()
{
	var submit_value = jQuery('#arfsubmitbuttontext').val();
	jQuery('#arfeditorsubmit').text( submit_value );
	arfsetsubmitautowdith();
}

//for li height
function CheckFieldPos_height()
{	
	var queryArr = [];
	jQuery('.multicolfield').each(function(index) {
		if( jQuery(this).is(":checked") ){
		 var _locationId = index;
		 var _locID    = jQuery(this).attr('data-id');
		 var _locValue    = jQuery(this).val();
		 if( _locValue == 'arf_3' ){
		 	var _locPos  = 3;
		 } else if( _locValue == 'arf_2' ) {
		 	var _locPos  = 2;
		 } else {
		 	var _locPos  = 1;
		 }
		 var locations = {  
			"locationId":_locationId,                                
			"locID" 	:_locID,
			"locValue" 	:_locValue,
			"locPos" 	:_locPos  
		 };
		 queryStr = { "locations" : locations };
		 queryArr.push(queryStr);
		}
	 });
	
	var if_large_height = false;
	var if_large_checkbox_radio = false;
	var column1_id		= '';
	var column2_id		= '';
	var column3_id		= '';
	var column1_height  = '75';
	var column2_height  = '75';
	var column3_height  = '75';
	var max_height		= '75';
	
	var arf_class_two 	= '';
	var arf_class_three = '';
	var temp_class		= '';
	for ( var i=0; i < queryArr.length; i++){ 
		if( queryArr[i].locations.locID != '' && queryArr[i].locations.locValue != '' ) {
			var fid = queryArr[i].locations.locID;
			if( queryArr[i].locations.locValue == 'arf_2' && arf_class_two =='' ){
				arf_class_two = '(First)';
				arf_class_three = '';
			} else if( queryArr[i].locations.locValue == 'arf_2' && arf_class_two !='' && arf_class_two == '(First)' ){
				arf_class_two = '(Second)';
				arf_class_three = '';
			} else if( queryArr[i].locations.locValue == 'arf_3' && arf_class_three =='' ){
				arf_class_three = '(First)';
				arf_class_two = '';
			} else if( queryArr[i].locations.locValue == 'arf_3' && arf_class_three !='' && arf_class_three == '(First)' ){
				arf_class_three = '(Second)';
				arf_class_two = '';
			} else if( queryArr[i].locations.locValue == 'arf_3' && arf_class_three !='' && arf_class_three == '(Second)'  ){
				arf_class_three = '(Third)';
				arf_class_two = '';
			} else if( queryArr[i].locations.locValue == 'arf_1' ) {
				arf_class_two 	= '';
				arf_class_three = '';
			}
			
			if( ( arf_class_two == '(First)' && arf_class_three == '' ) || ( arf_class_three == '(First)' && arf_class_two == '' ) )
			{
				if_large_height = false;
				if_large_checkbox_radio = false;
				column1_id		= '';
				column2_id		= '';
				column3_id		= '';
				
				column1_height	= '75';
				column2_height	= '75';
				column3_height	= '75';
				max_height		= '75';
			}
			
			if( queryArr[i].locations.locValue == 'arf_2' )
			{
				
				if( jQuery('#arfmainfieldid_'+fid).hasClass('edit_field_type_textarea') || jQuery('#arfmainfieldid_'+fid).hasClass('edit_field_type_captcha') ){
					if_large_height = true;
				}
	
				if( jQuery('#arfmainfieldid_'+fid).hasClass('edit_field_type_checkbox') || jQuery('#arfmainfieldid_'+fid).hasClass('edit_field_type_radio') ){
					if_large_checkbox_radio = true;
				}
					
				if( arf_class_two == '(First)' )
				{
					column1_id = fid;
					column2_id = '';
					max_height = '75';
					
					if( if_large_checkbox_radio )
					{
						var temp_height = jQuery('#arfmainfieldid_'+column1_id).height();
						jQuery('#arfmainfieldid_'+column1_id).css('height', 'auto');
						column1_height = jQuery('#arfmainfieldid_'+column1_id).height();
						jQuery('#arfmainfieldid_'+column1_id).height( temp_height );
						if( column1_height > max_height ){
							max_height = column1_height;
						}
					}					
				}
				
				if( arf_class_two == '(Second)' )
				{
					column2_id = fid;
					if( if_large_checkbox_radio )
					{
						var temp_height = jQuery('#arfmainfieldid_'+column2_id).height();
						jQuery('#arfmainfieldid_'+column2_id).css('height', 'auto');
						column2_height = jQuery('#arfmainfieldid_'+column2_id).height();
						jQuery('#arfmainfieldid_'+column2_id).height( temp_height );
						if( column2_height > max_height ){
							max_height = column2_height;
						}
					}
					
				}
				
				if( if_large_height && max_height < 120 ){
						max_height = '120';
				}
						
				if( if_large_checkbox_radio )
				{
					if( column1_id != '' ){ jQuery('#arfmainfieldid_'+column1_id).height( max_height );	}
					if( jQuery('#arfmainfieldid_'+column1_id).next().hasClass('blankli2col') ){
						jQuery('#arfmainfieldid_'+column1_id).next().height( max_height );
					} else if( column2_id != '' ) { jQuery('#arfmainfieldid_'+column2_id).height( max_height );	}
				}
				else if( if_large_height )
				{	
					if( column1_id != '' ) { jQuery('#arfmainfieldid_'+column1_id).css('height', '120px');	}
					if( jQuery('#arfmainfieldid_'+column1_id).next().hasClass('blankli2col') ){
						jQuery('#arfmainfieldid_'+column1_id).next().height( max_height );
					} else if( column2_id != '' ) { 
						jQuery('#arfmainfieldid_'+column2_id).css('height', '120px');
					}
				} 
				else
				{
					if( column1_id != '' ){ jQuery('#arfmainfieldid_'+column1_id).css('height', '75px'); }
					if( jQuery('#arfmainfieldid_'+column1_id).next().hasClass('blankli2col') ){
						jQuery('#arfmainfieldid_'+column1_id).next().height( max_height );
					} else if( column2_id != '' ) { 
						jQuery('#arfmainfieldid_'+column2_id).css('height', '75px');
					}
				}
			
				if( arf_class_two == '(Second)' )			
				{
					if_large_height = false;
					if_large_checkbox_radio = false;
					column1_id		= '';
					column2_id		= '';
					column3_id		= '';
					
					column1_height	= '75';
					column2_height	= '75';
					column3_height	= '75';
					max_height		= '75';
				}
				
			}
			else if( queryArr[i].locations.locValue == 'arf_3' )
			{
				if( jQuery('#arfmainfieldid_'+fid).hasClass('edit_field_type_textarea') || jQuery('#arfmainfieldid_'+fid).hasClass('edit_field_type_captcha') ){
					if_large_height = true;
				}
				if( jQuery('#arfmainfieldid_'+fid).hasClass('edit_field_type_checkbox') || jQuery('#arfmainfieldid_'+fid).hasClass('edit_field_type_radio') ){
					if_large_checkbox_radio = true;
				}
					
				if( arf_class_three == '(First)' )
				{
					column1_id = fid;
					column2_id = '';
					column3_id = '';
					max_height = '75';
						
					if( if_large_checkbox_radio )
					{
						var temp_height = jQuery('#arfmainfieldid_'+column1_id).height();
						jQuery('#arfmainfieldid_'+column1_id).css('height', 'auto');
						column1_height = jQuery('#arfmainfieldid_'+column1_id).height();
						jQuery('#arfmainfieldid_'+column1_id).height( temp_height );
						if( column1_height > max_height ){
							max_height = column1_height;
						}
					}
					
				}
				
				if( arf_class_three == '(Second)' )
				{
					column2_id = fid;
					column3_id = '';
					
					if( if_large_checkbox_radio )
					{
						var temp_height = jQuery('#arfmainfieldid_'+column2_id).height();
						jQuery('#arfmainfieldid_'+column2_id).css('height', 'auto');
						column2_height = jQuery('#arfmainfieldid_'+column2_id).height();
						jQuery('#arfmainfieldid_'+column2_id).height( temp_height );
						if( column2_height > max_height ){
							max_height = column2_height;
						}
					}
				}
				
				if( arf_class_three == '(Third)' )
				{
					column3_id = fid;
					
					if( if_large_checkbox_radio )
					{
						var temp_height = jQuery('#arfmainfieldid_'+column3_id).height();
						jQuery('#arfmainfieldid_'+column3_id).css('height', 'auto');
						column3_height = jQuery('#arfmainfieldid_'+column3_id).height();
						jQuery('#arfmainfieldid_'+column3_id).height( temp_height );					
						if( column3_height > max_height ){
							max_height = column3_height;
						}
					}
				}
				
				if( if_large_height && max_height < 120 ){
					max_height = '120';
				}
						
				if( if_large_checkbox_radio )
				{
					if( column1_id != '' ) { jQuery('#arfmainfieldid_'+column1_id).height( max_height ); }
					
					if( jQuery('#arfmainfieldid_'+column1_id).next().hasClass('blankli32col') ){
						jQuery('#arfmainfieldid_'+column1_id).next().height( max_height );
					} else if( column2_id != '' ) { jQuery('#arfmainfieldid_'+column2_id).height( max_height );	}
					
					if( jQuery('#arfmainfieldid_'+column1_id).next().next().hasClass('blankli33col') ){
						jQuery('#arfmainfieldid_'+column1_id).next().next().height( max_height );		
					} else if( column3_id != '' ) { jQuery('#arfmainfieldid_'+column3_id).height( max_height ); }
				}
				else if( if_large_height )
				{		
					if( column1_id != '' ) { jQuery('#arfmainfieldid_'+column1_id).css('height', '120px'); }
					
					if( jQuery('#arfmainfieldid_'+column1_id).next().hasClass('blankli32col') ){
						jQuery('#arfmainfieldid_'+column1_id).next().height( max_height );
					} else if( column2_id != '' ) { jQuery('#arfmainfieldid_'+column2_id).css('height', '120px'); }
					
					if( jQuery('#arfmainfieldid_'+column1_id).next().next().hasClass('blankli33col') ){
						jQuery('#arfmainfieldid_'+column1_id).next().next().height( max_height );					
					} else if( column3_id != '' ) { jQuery('#arfmainfieldid_'+column3_id).css('height', '120px'); }
				} 
				else
				{
					if( column1_id != '' ) { jQuery('#arfmainfieldid_'+column1_id).css('height', '75px'); }
					
					if( jQuery('#arfmainfieldid_'+column1_id).next().hasClass('blankli32col') ){
						jQuery('#arfmainfieldid_'+column1_id).next().height( max_height );
					} else if( column2_id != '' ) { jQuery('#arfmainfieldid_'+column2_id).css('height', '75px'); }
										
					if( jQuery('#arfmainfieldid_'+column1_id).next().next().hasClass('blankli33col') ){
						jQuery('#arfmainfieldid_'+column1_id).next().next().height( max_height );
					} else if( column3_id != '' ) { jQuery('#arfmainfieldid_'+column3_id).css('height', '75px');	 }
				}
			
				if( arf_class_three == '(Third)' )			
				{
					if_large_height = false;
					if_large_checkbox_radio = false;
					column1_id		= '';
					column2_id		= '';
					column3_id		= '';
					
					column1_height	= '75';
					column2_height	= '75';
					column3_height	= '75';
					max_height		= '75';
				}	
			}
			else
			{
				if_large_height = false;
				if_large_checkbox_radio = false;
				column1_id		= '';
				column2_id		= '';	
				column3_id		= '';
				
				column1_height	= '75';
				column2_height	= '75';
				column3_height	= '75';
				max_height		= '75';
				
				jQuery('#arfmainfieldid_'+fid).css('height', 'auto');		
			}
			
			if( arf_class_two == '(Second)' ) { arf_class_two = ''; }
			if( arf_class_three == '(Third)' ) { arf_class_three = ''; }
			
		} // end of if cond.
	}
	// end of for loop.
}
//end li height


function arf_change_opt_label(fid, id, val)
{
	var type = jQuery('#field_type_'+fid).val();
	
	if( type == 'radio' || type == 'checkbox' ){
		
		var new_id = id.replace('field_', 'arflbl_');
		
		jQuery('#'+new_id).text( val );
	}
}

function arfchangefieldalign( id )
{
	var align = jQuery('#arf_field_align_'+id).val();

	if( align == 'inline' ){
		jQuery('#arf_checkboxradio_'+id).removeClass('arf_multiple_row').addClass('arf_single_row');
	} else {
		jQuery('#arf_checkboxradio_'+id).removeClass('arf_single_row').addClass('arf_multiple_row');
	}
	
	CheckFieldPos_height();
}
function arfsetsubmitautowdith()
{
	var submit_text = jQuery('#arfeditorsubmit').text();
	
	jQuery('#arfsubmitbuttontext2').text( submit_text );

	var submit_outer = jQuery('#arfsubmitbuttontext2').outerWidth();		
	submit_outer = (submit_outer + 20);
	jQuery('#arfsubmitautowidth').val( submit_outer );
}

function arfsetsubmitautowdith2()
{
	var submit_text = jQuery('#arfsubmitbuttontext').val();
	
	jQuery('#arfsubmitbuttontext2').text( submit_text );

	var submit_outer = jQuery('#arfsubmitbuttontext2').outerWidth();		
		submit_outer = (submit_outer + 20);
	jQuery('#arfsubmitautowidth').val( submit_outer );
}


function arfsetsubmitwidth()
{
	var submit_width = jQuery('#arfsubmitbuttonwidthsetting').val();	
		submit_width = jQuery.trim(submit_width);
	if( submit_width == '' )
	{
		jQuery('.arfsubmitedit .greensavebtn').removeAttr('style');
		jQuery('.arfsubmitedit .greensavebtn').attr('data-auto', '0');
	} else {
		submit_width = parseInt(submit_width);
		jQuery('.arfsubmitedit .greensavebtn').attr('data-auto', '1');
		jQuery('.arfsubmitedit .greensavebtn').attr('data-width', submit_width);
		jQuery('.arfsubmitedit .greensavebtn').css('width', submit_width+'px');			
	}
}

function arf_open_field_options( field_id )
{
	jQuery('.show-field-options').hide();		
	jQuery('#arffieldoptions_'+field_id).show().css('visibility', 'hidden');
	arf_change_offset(field_id, 1);
	jQuery('#arffieldoptions_'+field_id).css('visibility', 'visible');
}

function arfcheckoptionlength( field_id )
{
	if( jQuery('#arfoptionul_'+field_id+' .arfoptionli').length > 0 ){
		jQuery('#arfaddanoption_' + field_id ).hide();
	} else {
		jQuery('#arfaddanoption_' + field_id ).show();
	}
		
	if( jQuery('#arf_checkboxradio_'+field_id+' .arf_check_radio_fields:visible').length >= 5 )
	{
		var count_no = jQuery('#arf_field_'+field_id+'_opts li.arfoptionli').length;
		jQuery('#arf_checkbox_notice_'+field_id+' .arf_cb_total').text( count_no );
		if( count_no == 5 ){
			jQuery('#arf_checkbox_notice_'+field_id).hide();
		} else {
			jQuery('#arf_checkbox_notice_'+field_id).show();
		}
	}
	else {
		jQuery('#arf_checkbox_notice_'+field_id).hide();
	}
}

function arfchangesubcheckradio( field_id )
{
	if( jQuery('#fieldcheck_'+field_id).is(':checked') )
	{
		jQuery('#fieldcheck_sub_'+field_id).attr('checked', true);
	}
	else
	{
		jQuery('#fieldcheck_sub_'+field_id).attr('checked', false);
	}
}



function arfreordercheckradio( field_id )
{
	if( ! field_id ){
		return;
	}
	var option = '';
	var type = jQuery('#field_type_'+field_id).val();
	
	if( jQuery('#separate_value_'+field_id).is(':checked') ){
		var is_sep = 1; 
	} else {
		var is_sep = 0;	
	}
	
	var selected= jQuery('select[name="item_meta['+field_id+']"]').val();
	
	var i = 0;
	jQuery('#arfoptionul_'+field_id+' li').each(function(){
		var keys 	= jQuery(this).attr('id');
			keys 	= keys.split('-');
		var key		= keys[1];
	if( jQuery('#field_'+field_id+'-'+key).hasClass('editInPlace-active') )
	{	
		var label 	= jQuery('#field_'+field_id+'-'+key).find('input').val();
		if( label === undefined ){
			label 	= jQuery('#field_'+field_id+'-'+key).text();
		}
	} else {
		var label 	= jQuery('#field_'+field_id+'-'+key).text();
	}
	
	if( type == 'select' && label == '(Blank)' ){
		label = '';	
	}
		var value	= jQuery('#field_key_'+field_id+'-'+key).text();
		var checked = jQuery('#fieldcheck_'+field_id+'-'+key).is(':checked') ? 'checked="checked"' : ''; 
		
		if( ! is_sep ){
			value 	= label;
		}
		i++;
		if( i < 6 )
		{
			if( type == 'radio' ){
				option += '<div class="arf_check_radio_fields"><input id="fieldcheck_sub_'+field_id+'-'+key+'" class="class_radio checkbox_radio_class" type="radio" '+checked+' value="'+value+'" disabled="disabled" name="item_meta['+field_id+']_sub_"><label id="arflbl_'+field_id+'-'+key+'" class="arf_checkbox_radio_label" for="fieldcheck_sub_'+field_id+'-'+key+'">'+label+'</label></div>';
			} else if( type == 'checkbox' ) {
				option += '<div class="arf_check_radio_fields"><input id="fieldcheck_sub_'+field_id+'-'+key+'" class="class_checkbox checkbox_radio_class" type="checkbox" value="'+value+'" '+checked+' disabled="disabled" name="item_meta['+field_id+']_sub_[]"><label id="arflbl_'+field_id+'-'+key+'" class="arf_checkbox_radio_label" for="fieldcheck_sub_'+field_id+'-'+key+'">'+label+'</label></div>';
			}
		}
		
		if( type == 'select' )
		{
			if( selected == value ){
				option += '<option value="'+value+'" selected="selected">'+label+'</option>';
			} else {
				option += '<option value="'+value+'">'+label+'</option>';
			}
		}
	});
	
	if( type == 'select' )
	{
		jQuery('select[name="item_meta['+field_id+']"]').html( option );
	}
	else {
		jQuery('#arf_checkboxradio_'+field_id).html( option );
	}
}

function arfupdateoptionorder( field_id )
{
	var order = jQuery('#arfoptionul_'+field_id).sortable('serialize');
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=arfupdateoptionorder&field_id="+field_id+"&"+order, success:function(res){ } });
}

function arf_change_offset( id, is_opt )
{
	if( ! id ){
		return;
	}
		
	if( is_opt == 1 )
	{	
		jQuery('#main_fieldoptions_modal_opt_'+id).css('left', '');
		jQuery('#main_fieldoptions_modal_opt_'+id).css('top', '');
	} 
	else
	{
		jQuery('#main_fieldoptions_modal_'+id).css('left', '');
		jQuery('#main_fieldoptions_modal_'+id).css('top', '');
	}
	
	var lower_ver	= jQuery('#field-setting-button-'+id).attr('data-lower');
	var doc_height 	= jQuery(document).height(); 
	
	if( is_opt == 1 ){
		var el 			= document.getElementById('arffieldoptions_'+id);
	} else {
		var el 			= document.getElementById('field-option-'+id);
	}
		
	if( ! el ){
		return;
	}
		
	var el_right	= parseInt( jQuery(el).offsetParent().width() ) - el.offsetWidth - el.offsetLeft; 
	
	var el_height	= el .offsetHeight;
	var el_width	= el .offsetWidth;
	var el_top 		= jQuery(el).offset().top; 	
		el_top 		= parseInt( el_top );
	var el_left		= jQuery(el).offset().left;
		el_left 	= parseInt( el_left );
	var el_bottom 	= el_top + el_height;
	
	if( is_opt == 1 ){
		jQuery('#main_fieldoptions_modal_opt_'+id).removeClass('arftop arfleft');
	} else {
		jQuery('#main_fieldoptions_modal_'+id).removeClass('arftop arfleft');
	}
	//console.log( 'R:'+el_right+'	B:'+el_bottom+'		T:'+el_top+'	L:'+el_left+'	H:'+doc_height );
	
	if( doc_height < ( el_bottom + 50 ) && el_top > 400 && is_opt == 0 )
	{
		var to_top = el_top - el_height + 180; 
		jQuery('#main_fieldoptions_modal_'+id).css('top', to_top+'px');
		jQuery('#main_fieldoptions_modal_'+id).addClass('arftop');
		jQuery('.arf_modal_box'+id).css('top', to_top+'px');
	}
	
	if( el_right < 50 )
	{
		if( lower_ver == 'true' )
		{
			if( jQuery('body.wp-admin').hasClass('folded') ){
				var to_left = el_left - el_width - 100 + 32;
			} else {
				var to_left = el_left - el_width - 100 + 145;
			}
				
			if( to_left < 15 ){
				to_left = 15;
			}
		}
		else
		{
			var to_left = el_left - el_width - 100;
		
			var to_fixed = -140; 
			if( to_left < to_fixed ){
				to_left = to_fixed;
			}
		}
		
		if( is_opt == 1 )
		{
			jQuery('#main_fieldoptions_modal_opt_'+id).css('left', to_left+'px');
			jQuery('#main_fieldoptions_modal_opt_'+id).addClass('arfleft');
			jQuery('.arf_modal_box'+id).css('left', to_left+'px');
		}
		else
		{
			if(id!="arfsubmit")
			{
				jQuery('#main_fieldoptions_modal_'+id).css('left', to_left+'px');
				jQuery('#main_fieldoptions_modal_'+id).addClass('arfleft');
				jQuery('.arf_modal_box'+id).css('left', to_left+'px');
			}else {
				jQuery('#main_fieldoptions_modal_'+id).css('left', '100px');
				jQuery('.arf_modal_box'+id).css('left', '100pxpx');
			}
		}
		
	}
}
function arfchangeconfirmpassword( id )
{
	if( jQuery('input[name="field_options[confirm_password_'+id+']"]').is(':checked') )
	{
		jQuery('#confirm_password_label_'+id).attr('disabled', false);
		jQuery('#invalid_password_'+id).attr('disabled', false);
		jQuery('#password_placeholder_'+id).attr('disabled', false);
	}
	else
	{
		jQuery('#confirm_password_label_'+id).attr('disabled', true);
		jQuery('#invalid_password_'+id).attr('disabled', true);
		jQuery('#password_placeholder_'+id).attr('disabled', true);
	}
}

function arfchangeconfirmemail( id )
{
	if( jQuery('input[name="field_options[confirm_email_'+id+']"]').is(':checked') )
	{
		jQuery('#confirm_email_label_'+id).attr('disabled', false);
		jQuery('#invalid_confirm_email_'+id).attr('disabled', false);
		jQuery('#confirm_email_placeholder_'+id).attr('disabled', false);
		
	}
	else
	{
		jQuery('#confirm_email_label_'+id).attr('disabled', true);
		jQuery('#invalid_confirm_email_'+id).attr('disabled', true);
		jQuery('#confirm_email_placeholder_'+id).attr('disabled', true);

	}
}


function arfresetlikefield( field_id )
{
	jQuery('input[name="item_meta['+field_id+']"]').attr('checked', false);
	jQuery('#arfmainfieldid_'+field_id).find('.arf_dislike_btn, .arf_like_btn').removeClass('active');
}

jQuery(window).resize(function(){
	var width = jQuery(window).width();
	var left_width	= ( jQuery('body').hasClass('folded') ) ? 36 : 160;
	var total_width		= width - left_width - 350 - 20;
	jQuery('#formeditorpart').css('width', total_width + 'px');
	
	var total_width_preview_div = total_width - 20;
	jQuery('#arf_main_style_tab').css('width', total_width_preview_div + 'px');
	
	var addtosite_width= width - left_width - 325 - 35;
	jQuery('.arfaddtosite_container').css('width', addtosite_width + 'px');
});

function arfpreviewdevicechanged(){
	document.getElementById('arfdevicepreview').contentWindow.changepreviewslider();
}

function arfchangesmtpsetting()
{
	if( jQuery('input[name="frm_smtp_server"]:checked').val() == 'custom' )
	{
		jQuery('.arfsmptpsettings').show();	
	}
	else
	{
		jQuery('.arfsmptpsettings').hide();			
	}
}

function change_image_field_pos( id, postop, posleft  )
{
	posleft = posleft + 4;
	postop	= postop + 4;
	var fieldid = jQuery('#field_ref_'+id).val();
	jQuery('#arfimage_left_'+fieldid).val(posleft+'px');
	jQuery('#arfimage_top_'+fieldid).val(postop+'px');
}

function arfimagecenteralign( field_id )
{
	if( jQuery('#arfimage_center_'+field_id+'_1').is(':checked') )
	{
		jQuery('#arfimage_left_'+field_id).attr('disabled', true);
	}
	else
	{
		jQuery('#arfimage_left_'+field_id).attr('disabled', false);
	}
}

function arf_show_runnig_total( field_id )
{
	if( jQuery('#arfenable_total_'+field_id).is(':checked') )	
	{
		var text_value = jQuery('#arf_field_description_'+field_id).val();
		if( text_value.indexOf('<arftotal>') == '-1' )
		{
			var start_pos = jQuery('#arf_field_description_'+field_id).attr('data-startpos');
				start_pos = start_pos ? start_pos : 0;				
			if( start_pos == 0 ) {
				jQuery('#arf_field_description_'+field_id).insertAt(start_pos, " <arftotal></arftotal>");	
			} else {
				var text_value_new = text_value + " <arftotal></arftotal>";	
				jQuery('#arf_field_description_'+field_id).val( text_value_new );
			}
		}
		text_value = jQuery('#arf_field_description_'+field_id).val();
		jQuery('.arf_field_list_total_'+field_id).slideDown();
		var text_arftotal_pos = text_value.indexOf("<arftotal>");
		text_arftotal_pos = Number(text_arftotal_pos)+Number(10);
		jQuery('#arf_field_description_'+field_id).attr('data-startpos', text_arftotal_pos);
	}
	else
	{
		jQuery('.arf_field_list_total_'+field_id).slideUp();
	}
}

function arfaddtotalfield(element,variable,inc)
{
	
	var field = jQuery(element).parents('.arfmodal').first().attr('id');
		field = field.replace('add_field_total_', '');
	var element_id = 'arf_field_description_'+field;

	var start_pos = jQuery('#'+element_id).attr('data-startpos'); 
	
	var variable1 = jQuery('#arfmodalfieldval_'+variable).text();
	
	if(inc != "")
	{
		variable1 = jQuery('#arfmodalfieldval_'+variable+'_'+inc).text();
	}
	
	variable1 = variable1.trim();
	
	variable2 = '['+variable1+':'+variable+']';
	
	if(inc != ""){
		variable2 = '['+variable1+':'+variable+'.'+inc+']';
	}
		
	var content_box	=	jQuery('#'+element_id);
	start_pos = start_pos ? start_pos : 0;
	if(content_box)
	{
		if( start_pos == 0 ){
			jQuery('#'+element_id).val( jQuery('#'+element_id).val()+variable2 );
		} else {
			jQuery('#'+element_id).insertAt(start_pos, variable2);
		}
	}
	
	var datastartpos = jQuery('#'+element_id).attr('data-startpos');
	var newvardatstrtpos = Number(datastartpos)+Number(variable2.length);
	jQuery('#'+element_id).attr('data-startpos', newvardatstrtpos);
	
	jQuery(element).parents('.arfmodal').first().hide();
}

function arfaddtotalopcode( field_id, op ){
	var element_id = 'arf_field_description_'+field_id;	
	var start_pos = jQuery('#'+element_id).attr('data-startpos'); 
		start_pos = start_pos ? start_pos : 0;
	var	variable2 = op;
		
	var content_box	=	jQuery('#'+element_id);
	if(content_box)
	{
		if( start_pos == 0 ){
			jQuery('#'+element_id).val( jQuery('#'+element_id).val()+variable2 );
		} else {
			jQuery('#'+element_id).insertAt(start_pos, variable2);
		}
	}
	
	jQuery('#'+element_id).attr('data-startpos',(Number(start_pos) + 1));
}

function arfvalidateregex( field_id )
{
	var content = jQuery('#arf_field_description_'+field_id).val();
	var data 	= content.split('<arftotal>');
	var data1 	= data[1] ? data[1] : '';
	var data2	= data1.split('</arftotal>');
	var regexp	= data2[0] ? data2[0] : '';
	
	var regex = /\[(if )?()(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/;
        
	var matches;
	
	while(matches = regex.exec(regexp)) {
            
            regexp = regexp.replace(matches[0], 1);
	}
	
	regexp = regexp.replace(/(\n|\r\n)/g, '');
	
	var validate_regex = /^[0-9 -/*\(\)]+$/i;
		
	if(typeof(__ARFINVALIDF)!='undefined') { var message = __ARFINVALIDF; }
	else { var message = 'Your formula is invalid'; }
				
	if(regexp != '' && ! validate_regex.test(regexp) )
	{		
			jQuery('#arf_validate_result_'+field_id).removeClass('arfvalidatsuccess').addClass('arfvalidaterror').show().html( message );	
	}
	else
	{
		try {
			var regex_res = eval(regexp);	// try regex			
			if(typeof(__ARFVALIDF)!='undefined') { var message = __ARFVALIDF; }
			else{var message = 'Your Formula is valid'; }
			jQuery('#arf_validate_result_'+field_id).removeClass('arfvalidaterror').addClass('arfvalidatsuccess').show().html( message );
			setTimeout(function(){ jQuery('#arf_validate_result_'+field_id).hide(); }, 5000);
		} catch(e) {
			jQuery('#arf_validate_result_'+field_id).removeClass('arfvalidatsuccess').addClass('arfvalidaterror').show().html( message );
		}
	}
}

function CheckUserAutomaticResponseEnableDisable()
{
	if( jQuery("#auto_responder").is(':checked') )
	{
		jQuery('#options_ar_user_email_to').removeAttr('disabled');
		jQuery('.options_ar_user_email_to_dt').removeClass('arf_disable_selectbox');
		jQuery('#ar_email_subject').removeAttr('disabled');
		jQuery('#options_ar_user_from_name').removeAttr('disabled');
		jQuery('#ar_user_from_email').removeAttr('disabled');
		jQuery('#ar_email_message').removeAttr('disabled');
		//jQuery('#wp-ar_email_message-wrap .quicktags-toolbar input').removeAttr('disabled');
		jQuery('#add_field_email_subject_but').removeAttr('disabled');
		jQuery('#add_field_user_email_but').removeAttr('disabled');
		jQuery('#add_field_message_but').removeAttr('disabled');
	}else {
		jQuery('#options_ar_user_email_to').attr('disabled', 'disabled');
		jQuery('.options_ar_user_email_to_dt').addClass('arf_disable_selectbox');
		jQuery('#ar_email_subject').attr('disabled', 'disabled');
		jQuery('#options_ar_user_from_name').attr('disabled', 'disabled');
		jQuery('#ar_user_from_email').attr('disabled', 'disabled');
		jQuery('#ar_email_message').attr('disabled', 'disabled');
		//jQuery('#wp-ar_email_message-wrap .quicktags-toolbar input').attr('disabled', 'disabled');
		jQuery('#add_field_email_subject_but').attr('disabled', 'disabled');
		jQuery('#add_field_user_email_but').attr('disabled', 'disabled');
		jQuery('#add_field_message_but').attr('disabled', 'disabled');
		
		close_add_field_subject('add_field_subject');
		close_add_field_subject('add_field_user_email');
		close_add_field_subject('add_field_message');
	}
	//jQuery('#options_ar_user_email_to').selectpicker('refresh');
}

function CheckAdminAutomaticResponseEnableDisable()
{
	if( jQuery("#chk_admin_notification").is(':checked') )
	{
		jQuery('#options_admin_reply_to_notification').removeAttr('disabled');
		jQuery('#ar_admin_from_email').removeAttr('disabled');
		jQuery('#admin_email_subject').removeAttr('disabled');
		jQuery('#ar_admin_email_message').removeAttr('disabled');
		//jQuery('#wp-ar_admin_email_message-wrap .quicktags-toolbar input').removeAttr('disabled');
		jQuery('#add_field_admin_message_but').removeAttr('disabled');
		jQuery("#add_field_admin_email_but_to").removeAttr('disabled');
		jQuery('#add_field_admin_email_but').removeAttr('disabled');
                jQuery('#add_field_admin_email_but_subject').removeAttr('disabled');
	}else {
		jQuery('#options_admin_reply_to_notification').attr('disabled', 'disabled');
		jQuery('#ar_admin_from_email').attr('disabled', 'disabled');
		jQuery('#admin_email_subject').attr('disabled', 'disabled');
		jQuery('#ar_admin_email_message').attr('disabled', 'disabled');
		//jQuery('#wp-ar_admin_email_message-wrap .quicktags-toolbar input').attr('disabled', 'disabled');
		jQuery('#add_field_admin_message_but').attr('disabled', 'disabled');
		jQuery("#add_field_admin_email_but_to").attr('disabled','disabled');
		jQuery('#add_field_admin_email_but').attr('disabled', 'disabled');
		jQuery('#add_field_admin_email_but_subject').attr('disabled','disabled');
		close_add_field_subject('add_field_admin_email');
		close_add_field_subject('add_field_admin_message');
                close_add_field_subject('add_field_admin_email_subject');
	}
}

function open_arf_modal_add_file(data_insert_id,data_insert)
{
	jQuery("#arf_media_upload_iframe").remove();
	
	jQuery('.arf_modal_box').css('top', Number(jQuery('#main_fieldoptions_modal_'+data_insert_id).offset().top)-Number(28) + 'px');
	
	jQuery("#arf_fileupload_iframe").show();
	jQuery('.arfmodal-backdrop').show();
	jQuery('#arf_fileupload_iframe').on('show', function () {
		if(jQuery("#arf_media_upload_iframeContent").html()=="")
		{
			var iframe_div = document.createElement('iframe');
			iframe_div.setAttribute('src','media-upload.php?post_id=0&type=image');
			iframe_div.setAttribute('id','arf_media_upload_iframe');
			iframe_div.setAttribute('class','arf-media-iframe');
			iframe_div.setAttribute('style','float:left;width:100%;height:310px;');
			jQuery("#arf_media_upload_iframeContent").append(iframe_div);
		}
	});
	jQuery('#arf_fileupload_iframe').arfmodal({show:true});
	
	jQuery('.arf-media-iframe').load(function(){
		setTimeout(function(){	
			jQuery('.arf-media-iframe').attr('verticalscrolling', 'yes"');
			jQuery('.arf-media-iframe').css('border', 'solid 1px #f00');
			jQuery('.arf-media-iframe').attr('hspace', '0');	
			jQuery('.arf-media-iframe').attr('style', 'overflow:hidden;width:100%;height:310px;');
			jQuery('.arf-media-iframe').attr('css','width:100%');
			jQuery('.arf-media-iframe').find('html').attr('style','auto !important;');
		}, 100);
	});
	
	window.send_to_editor = function(html) {
		var $html = jQuery('<div />', { 'class':'media-html', 'html': html });
		
		var hieght 	= $html.find('img').attr('height');			
		var width 	= $html.find('img').attr('width');			
		var img_src = $html.find('img').attr('src');			
		var a_src	= $html.find('a').attr('href');
		if( data_insert == 'image' )
		{
			jQuery('#arfimage_url_'+data_insert_id).val( img_src );
			jQuery('#arfimage_height_'+data_insert_id).val( hieght );
			jQuery('#arfimage_width_'+data_insert_id).val( width );
		}else {
			jQuery('#arfimage_url_'+data_insert_id).val( img_src );
		}
		arfmodal_media_upload_close();
	};
}

function arfmodal_media_upload_close()
{
	jQuery('#arf_fileupload_iframe').fadeOut(300);
	jQuery('.arfmodal-backdrop').fadeOut(300);
	jQuery("#arf_media_upload_iframeContent").html('');
}


/* Custom Select Box */
/* Custom Select Box */

function arfshowprefixsuffixmodal( field_id ){
	
	var id = '';
	
	id = field_id;
	
	var pre_field_id = 'arf_enable_arf_prefix_'+id;
	
	var suf_field_id = 'arf_enable_arf_suffix_'+id;
	
	var parent = jQuery('#field-option-'+field_id);
	
	parent.find('#arf_prefix_postfix_wrapper').show();
	
	//parent.find('#field_prefix_suffix').css('margin-left','20%');
	
	//jQuery('#field_prefix_suffix').css('margin-top','20%');
	
	/*parent.find('#arf_prefix').attr('data-id',id);
	
	parent.find('#arf_suffix').attr('data-id',id);
	
	parent.find('#arfprefixicon').attr('data-id',id);
	
	parent.find('#arfsuffixicon').attr('data-id',id);
	
	jQuery('input#arfprefixcolorsetting').attr('data-id',id);*/
	
	var prefix_icon = parent.find('#arf_prefix_icon_'+id).val();
	parent.find('#arfprefixicon').val( prefix_icon );
	
	var suffix_icon = parent.find('#arf_suffix_icon_'+id).val();
	parent.find('#arfsuffixicon').val( suffix_icon );
		
	if( jQuery('#enable_arf_prefix_'+id).val() == 1 ){
		parent.find('input#arf_prefix').attr('checked',true);
		//var elem = document.getElementById('arf_prefix');
		//parent.find('#field_prefix_suffix').find('#enable_prefix').find('.switchery').remove();
		//new Switchery( elem );
	} else {
		parent.find('input#arf_prefix').attr('checked',false);
		//var elem = document.getElementById('arf_prefix');
		//parent.find('#field_prefix_suffix').find('#enable_prefix').find('.switchery').remove();
		//new Switchery( elem );
	}
	
	if( jQuery('#enable_arf_suffix_'+id).val() == 1 ){
		parent.find('input#arf_suffix').attr('checked',true); 
		/*var elem = document.getElementById('arf_suffix');
		parent.find('#field_prefix_suffix').find('#enable_suffix').find('.switchery').remove();
		new Switchery( elem );*/
	} else {
		parent.find('input#arf_suffix').attr('checked',false);
		/*var elem = document.getElementById('arf_suffix');
		parent.find('#field_prefix_suffix').find('#enable_suffix').find('.switchery').remove();
		new Switchery( elem );*/
	}
	
	/*var prefix_bgcolor = jQuery('#arf_prefix_bgcol_'+id).val();
	parent.find('#field_prefix_suffix').find('th#prefix_bgcolor').find('.arf_prefix_suffix_sub_options').css('background-color',prefix_bgcolor);
	//parent.find('#field_prefix_suffix').find('th#prefix_bgcolor').find('.arf_prefix_suffix_sub_options').attr('data-id',id);
	
	var prefix_iconcol = jQuery('#arf_prefix_iconcol_'+id).val();
	parent.find('#field_prefix_suffix').find('th#prefix_iconcolor').find('.arf_prefix_suffix_sub_options').css('background-color',prefix_iconcol);
	//parent.find('#field_prefix_suffix').find('th#prefix_iconcolor').find('.arf_prefix_suffix_sub_options').attr('data-id',id);
	
	var suffix_bgcolor = jQuery('#arf_suffix_bgcol_'+id).val();
	parent.find('#field_prefix_suffix').find('th#suffix_bgcolor').find('.arf_prefix_suffix_sub_options').css('background-color',suffix_bgcolor);
	//parent.find('#field_prefix_suffix').find('th#suffix_bgcolor').find('.arf_prefix_suffix_sub_options').attr('data-id',id);
	
	var suffix_iconcol = jQuery('#arf_suffix_iconcol_'+id).val();
	parent.find('#field_prefix_suffix').find('th#suffix_iconcolor').find('.arf_prefix_suffix_sub_options').css('background-color',suffix_iconcol);*/
	//parent.find('#field_prefix_suffix').find('th#suffix_iconcolor').find('.arf_prefix_suffix_sub_options').attr('data-id',id);
	
	/*parent.find('#field_prefix_suffix').find('.arf_prefix_suffix_sub').colpick({
		layout:'hex',
		submit:0,
		onBeforeShow:function(){
			var fid 	= jQuery(this).find('.arfhex').attr('data-fid');
			var id = jQuery(this).find('.arfhex').attr('data-id');
			if( fid == 'arfprefixcolorsetting' ){
				var color = jQuery('#arf_prefix_bgcol_'+id).val();
			} else if( fid == 'arfsuffixcolorsetting' ) {
				var color = jQuery('#arf_suffix_bgcol_'+id).val();
			} else if( fid == 'arfprefixiconcolorsetting' ) {
				var color = jQuery('#arf_prefix_iconcol_'+id).val();
			} else if( fid == 'arfsuffixiconcolorsetting' ) {
				var color = jQuery('#arf_suffix_iconcol_'+id).val();
			} if( color ){
				var	new_color= color.replace('#','');
				if( new_color ){
					jQuery(this).colpickSetColor(new_color);
				}
			}
		},
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			jQuery(el).find('.arfhex').css('background','#'+hex);
			if(!bySetColor) jQuery(el).val(hex);
			var fid = jQuery(el).find('.arfhex').attr('data-fid');
			var id = jQuery(el).find('.arfhex').attr('data-id');
			if( fid ){
				jQuery('#'+fid).val('#'+hex);
				if( fid == 'arfprefixcolorsetting' ){
					jQuery('#arf_prefix_bgcol_'+id).val('#'+hex);
				}else if( fid == 'arfsuffixcolorsetting' ){
					jQuery('#arf_suffix_bgcol_'+id).val('#'+hex);
				}else if( fid == 'arfprefixiconcolorsetting' ){
					jQuery('#arf_prefix_iconcol_'+id).val('#'+hex);
				}else if( fid == 'arfsuffixiconcolorsetting' ){
					jQuery('#arf_suffix_iconcol_'+id).val('#'+hex);
				}
			}
		}
	});*/
	
}

function arfchangeprefix(ischeck,obj){
	var id = jQuery(obj).attr('data-id');
	if( ischeck ){
		jQuery('#enable_arf_prefix_'+id).val(1);
	} else {
		jQuery('#enable_arf_prefix_'+id).val(0);
	}
}

function arfchangesuffix(ischeck,obj){
	var id = jQuery(obj).attr('data-id');
	if( ischeck ){
		jQuery('#enable_arf_suffix_'+id).val(1);
	} else {
		jQuery('#enable_arf_suffix_'+id).val(0);
	}
}
/*
function showfontawesomemodal(obj,name){
	var id = jQuery(obj).attr('data-id');
	
	jQuery('.arf_fainsideimge').attr('data-id',id);
	jQuery('.arf_fainsideimge').attr('data-field',name);
}

jQuery(document).on('click','.arf_fainsideimge',function(e){
	var fid = jQuery(this).attr('data-id');
	
	var field = jQuery(this).attr('data-field');
	if( field == 'prefix' ){
		jQuery('#field-option-'+fid).find('#arfprefixicon').val( jQuery(this).attr('id') );
		jQuery('#field-option-'+fid).find('#arf_prefix_icon_'+fid).val( jQuery(this).attr('id') );
	} else if( field == 'suffix' ){
		jQuery('#field-option-'+fid).find('#arfsuffixicon').val( jQuery(this).attr('id') );
		jQuery('#field-option-'+fid).find('#arf_suffix_icon_'+fid).val( jQuery(this).attr('id') );
	}
	jQuery('#arf_fontawesome_modal').arfmodal('hide');
});*/

jQuery(document).on('show.bs.modal','#arf_fontawesome_modal',function(){
	jQuery(this).css('left','18%');
});

jQuery(document).on('click','#arfprefixpostfixmodalclose',function(){
	var id = jQuery(this).attr('data-id');
	jQuery('#field-option-'+id).find('#arf_prefix_postfix_wrapper').hide();
});
jQuery(document).on('click','#arfprefixpostfixmodalclosenew',function(){
	var id = jQuery(this).attr('data-id');
	jQuery('#field-option-'+id).find('#arf_prefix_postfix_wrapper').hide();
});
jQuery.fn.serializeObject = function(options) {
	
    options = jQuery.extend({}, options);

    var self = this,
        json = {},
        push_counters = {},
        patterns = {};
	
	patterns.validate = /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/
	patterns.key = /[a-zA-Z0-9_]+|(?=\[\])/g;
	patterns.push = /^$/;
	patterns.fixed = /^\d+$/;
	patterns.named = /^[a-zA-Z0-9_]+$/;
	
	var p = new Array();
	var newbase = {};
    this.build = function(base, key, value){
		
		if( key == 'item_meta'){
			jQuery(value).each(function(n){
				if( value[n] !== undefined ){
					newbase[n] = value[n];
				}
			});
			base[key] = newbase;
		} else {
			base[key] = value;
		}
		
		return base;
    };

    this.push_counter = function(key){
        if(push_counters[key] === undefined){
            push_counters[key] = 0;
        }
        return push_counters[key]++;
    };
	
	

    jQuery.each(jQuery(this).serializeArray(), function(){

        // skip invalid keys
        if(!patterns.validate.test(this.name)){
            return;
        }

        var k,
            keys = this.name.match(patterns.key),
            merge = this.value,
            reverse_key = this.name;
			
		
        while((k = keys.pop()) !== undefined){

            // adjust reverse_key
            reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
			
            // push
            if(k.match(patterns.push)){
				merge = self.build([], self.push_counter(reverse_key), merge);
            }

            // fixed
            else if(k.match(patterns.fixed)){
                merge = self.build([], k, merge);
            }

            // named
            else if(k.match(patterns.named)){
                merge = self.build({}, k, merge);
            }
			
        }
						
        json = jQuery.extend(true, json, merge);
		
		
    });


    return json;
}


jQuery.fn.FilterFormData = function(){
	
	var formarray = jQuery(this).serializeArray();
		
	for( index = 0; index < formarray.length; ++index ){
		
		
		var mydescpos = formarray[index].name.search("description"); 
		var myplhlderpos = formarray[index].name.search("placeholdertext");
		var mymaxrpos = formarray[index].name.search("max");
		var myclearfieldpos = formarray[index].name.search("frm_clear_field");
		var mydefaultblnkpos = formarray[index].name.search("frm_default_blank");
		var myrulearrpos = formarray[index].name.search("rule_value");
		var myfldwidthpos = formarray[index].name.search("field_width");
		var myreqindipos = formarray[index].name.search("required_indicator");
		var myitemmetapos = formarray[index].name.search("item_meta");
		
		var myformnamepos = formarray[index].name.search("form_name");
		var myformdescpos = formarray[index].name.search("form_desc");
		
		if(myitemmetapos >= 0 || myformnamepos >= 0 || myformdescpos >= 0 )
		{
			if(formarray[index].value == ""){
				delete formarray[index];
			}
		}
		
		if(myreqindipos > 0 || myplhlderpos > 0)
		{
			delete formarray[index];
		}
		
		if(mydescpos > 0 || mymaxrpos > 0 || myclearfieldpos > 0 || mydefaultblnkpos > 0 || myrulearrpos > 0 || myfldwidthpos > 0)
		{
			if(formarray[index].value == ""){
				delete formarray[index];
			}
		}
		
	}
	
	var fields = {};
	
	var frmsa = formarray;
	
	var p = 0;
	
	for( var key in frmsa ){
		var k = frmsa[key].name;
		var v = frmsa[key].value;
		
		if( k.search(/(.*?)\[(.*?)\]/) != -1 ){
			var x = k.replace(/(.*?)\[(.*?)\]/,'$2');
			var m = k.replace(/(.*?)\[(.*?)\]/,'$1');
			if( m.search(/(.*?)\[(.*?)\]/) != -1 ){
				var m = m.replace(/(.*?)\[(.*?)\]/,'$1');
				fields[m] = {};
			} else {
				fields[m] = {};
			}
			if( x == '' ){
				fields[m][p] = {};
				p++;
			} else {
				fields[m][x] = {};			
			}
		}
	}

	var p = 0;
	var z = 0;
	for( var key in frmsa ){
		var k = frmsa[key].name;
		var v = frmsa[key].value;
		
		if( k.search(/(.*?)\[(.*?)\]/) != -1 ){
			var x = k.replace(/(.*?)\[(.*?)\]/,'$2');
			var m = k.replace(/(.*?)\[(.*?)\]/,'$1');
			if( m.search(/(.*?)\[(.*?)\]/) != -1 ){
				var m = m.replace(/(.*?)\[(.*?)\]/,'$1');
				
			}
			if( fields[m] == null )
					fields[m] = {};
			if( x == '' ){
				fields[m][p] = v;
				p++;
			} else {
				if( x.search( /(.*?)\[(.*?)\]/ ) != -1 ){
					x = x.replace( /(.*?)\[(.*?)\]/ , '$1' );
					if( fields[m][x] == null ){
						fields[m][x] = {};
						var z = 0;
					}
					
					fields[m][x][z] = v;
					z++;
				} else {
					fields[m][x] = v;
				}
			}
			
		} else {
			fields[k] = v;
		}
	}
	nfields = getObj( fields );
	return nfields;
	
}

function getObj( obj ){
	var new_fields = {};
	var x = 0;
	var fields = {};
	var ftypes = new Array();
	for( var key in obj ){
		if( key.search( /(.*?)\[(.*?)\]/ ) != -1 ){
			var f = key.replace( /(.*?)\[(.*?)\]/,'$2');
			var k = key.replace( /(.*?)\[(.*?)\]/,'$1');
			if( typeof ( obj[key] == 'object' ) ){
				
				for( var n in obj[key] ){
					var p = n.replace( /(.*?)\[(.*?)\]/,'$1');
					var o = n.replace( /(.*?)\[(.*?)\]/,'$2');
					obj[k][p] = {};
				}
				
				for( var n in obj[key] ){
					var o = n.replace( /(.*?)\[(.*?)\]/,'$2');
					var f = n.replace( /(.*?)\[(.*?)\]/,'$1');
					if( ftypes.indexOf( f ) == -1 ){
						fields = {};
					}
					ftypes.push( f );
					fields[o] = obj[key][n];
					obj[k][p] = fields;
				}
				delete obj[key][n];
			}
			delete obj[key];
		} else {
			new_fields[key] = obj[key];
		}
	}
	return obj;
}

jQuery(document).on('click','.arf_prefix_suffix_container_wrapper'   ,function(e){
	var field_id = jQuery(this).attr('field-id');
	var action = jQuery(this).attr('data-action');
	var type = jQuery(this).attr('data-field');
	var ftype = jQuery(this).attr('data-field_type');


	if( action == 'edit' ){
		showfontawesomemodal( field_id, type );
	}
	
	if( action == 'remove' ){
		if( !confirm( 'Are you sure you want to remove '+type+'?') ){
			return false;
		}
		
		jQuery("#arf_"+type+"_icon_"+field_id).val('');
		jQuery("#arf_select_"+type+"_"+field_id).html('');
		jQuery("#arf_remove_"+type+"_"+field_id).hide();
		jQuery("#enable_arf_"+type+"_"+field_id).val(0);
		
		add_editor_prefix_suffix( field_id,ftype );
	}
});


function showfontawesomemodal(id,name){
	
	jQuery('.arf_fainsideimge').attr('data-id',id);
	jQuery('.arf_fainsideimge').attr('data-field',name);
}

jQuery(document).on('click','.arf_fainsideimge',function(e){
	var fid = jQuery(this).attr('data-id');
	var html = jQuery(this).html();
	var field = jQuery(this).attr('data-field');
	var ftype = jQuery('#arf_edit_suffix_'+fid).attr('data-field_type');
	if( html == '' ){
		jQuery("#arf_"+field+"_icon_"+fid).val('');
		//jQuery("#arf_select_"+field+"_"+fid).html('No Icon');
                jQuery("#arf_select_"+field+"_"+fid).html(jQuery(this).attr("no_icon_text"));
		jQuery("#arf_remove_"+field+"_"+fid).hide();
		jQuery("#enable_arf_"+field+"_"+fid).val(0);
		
		add_editor_prefix_suffix( fid,ftype );
		jQuery('#arf_fontawesome_modal').arfmodal('hide');
		return false;
	}
	if( field == 'prefix' ){
		jQuery('#field-option-'+fid).find('#arf_prefix_icon_'+fid).val( jQuery(this).attr('id') );
		jQuery('#field-option-'+fid).find('#arf_select_prefix_'+fid).html(html);
		jQuery('#field-option-'+fid).find('#arf_remove_prefix_'+fid).show();
		jQuery('#enable_arf_prefix_'+fid).val(1);
		
		add_editor_prefix_suffix( fid,ftype );
	} else if( field == 'suffix' ){
		jQuery('#field-option-'+fid).find('#arf_suffix_icon_'+fid).val( jQuery(this).attr('id') );
		jQuery('#field-option-'+fid).find('#arf_select_suffix_'+fid).html(html);
		jQuery('#field-option-'+fid).find('#arf_remove_suffix_'+fid).show();
		jQuery('#enable_arf_suffix_'+fid).val(1);
		add_editor_prefix_suffix( fid,ftype );
	}
	jQuery('#arf_fontawesome_modal').arfmodal('hide');
});

function add_editor_prefix_suffix( field_id, ftype ){
	var field = jQuery('#itemmeta_'+field_id);
	field.removeClass('arf_both_pre_suffix,arf_prefix_only,arf_suffix_only,arf_prefix_suffix');
	var new_html = '';
	var inp_cls = '';
	var input_cls = '';
	if( ftype != 'time' ){
		new_html += "<div class='arf_editor_prefix_suffix_wrapper' id='prefix_suffix_wrapper_"+field_id+"'>";
			
			if( jQuery('#enable_arf_prefix_'+field_id).val() == 1 && jQuery('#enable_arf_suffix_'+field_id).val() == 1 ){
				inp_cls = 'arf_both_pre_suffix';
			} else if( jQuery('#enable_arf_prefix_'+field_id).val() == 1 && jQuery('#enable_arf_suffix_'+field_id).val() != 1 ){
				inp_cls = 'arf_prefix_only';
			} else if( jQuery('#enable_arf_prefix_'+field_id).val() != 1 && jQuery('#enable_arf_suffix_'+field_id).val() == 1 ){
				inp_cls = 'arf_suffix_only';
			}
			
			input_cls += 'arf_prefix_suffix';
			
			field.addClass(inp_cls);
			
			field.addClass(input_cls);
			
			//console.log( jQuery('#enable_arf_suffix_'+field_id).val() +'  '+ jQuery('#enable_arf_prefix_'+field_id).val() );
			
			if( jQuery('#enable_arf_suffix_'+field_id).val() == 0 && jQuery('#enable_arf_prefix_'+field_id).val() == 0){
				field.removeClass('arf_both_pre_suffix');
				field.removeClass('arf_prefix_only');
				field.removeClass('arf_suffix_only');
				field.removeClass('arf_prefix_suffix');
			}
			
			if( jQuery('#enable_arf_prefix_'+field_id).val() == 1 && jQuery('#arf_prefix_icon_'+field_id).val() != ''  ){
				new_html += "<span class='arf_editor_prefix' id='arf_editor_prefix_"+field_id+"'>";
					new_html += "<i class='fa "+jQuery('#arf_prefix_icon_'+field_id).val()+"'></i>";
				new_html += "</span>";
			}
			
			new_html += field[0].outerHTML;
							
			if( jQuery('#enable_arf_suffix_'+field_id).val() == 1 && jQuery('#arf_suffix_icon_'+field_id).val() != '' ){
				new_html += "<span class='arf_editor_suffix' id='arf_editor_suffix_"+field_id+"'>";
					new_html += "<i class='fa "+jQuery("#arf_suffix_icon_"+field_id).val()+"'></i>";
				new_html += "</span>";
			}
			
		new_html += "</div>";
		
		if( jQuery('#enable_arf_suffix_'+field_id).val() == 1 || jQuery('#enable_arf_prefix_'+field_id).val() == 1){
			jQuery('#itemmeta_'+field_id).remove();
			jQuery('#prefix_suffix_wrapper_'+field_id).remove();
			jQuery('#arfmainfieldid_'+field_id).find('.allfields').prepend( new_html );
		} else {
			if( jQuery('#prefix_suffix_wrapper_'+field_id).length  > 0 ){
				jQuery('#prefix_suffix_wrapper_'+field_id).remove();
				jQuery('#arfmainfieldid_'+field_id).find('.allfields').prepend( field[0].outerHTML );
			}
		}
	}
}

jQuery(document).on('focusin','.arf_editor_prefix_suffix_wrapper input',function(e){
	
	var cls = 'get_focused';
	var fid = jQuery(this).attr('id');
	fid = fid.replace('itemmeta_','');
	jQuery('#arf_editor_prefix_'+fid).addClass(cls);
	jQuery('#arf_editor_suffix_'+fid).addClass(cls);
});

jQuery(document).on('focusout','.arf_editor_prefix_suffix_wrapper input',function(e){
	cls = 'get_focused';
	var fid = jQuery(this).attr('id');
	fid = fid.replace('itemmeta_','');
	jQuery('#arf_editor_prefix_'+fid).removeClass(cls);
	jQuery('#arf_editor_suffix_'+fid).removeClass(cls);
});

function changearfsectionbgtype( field_id,is_checked ){

	//jQuery('.arf_clr_disable').hide();
        var obj = jQuery('#arf_divider_inherit_bg_'+field_id).parent();
	if( is_checked ){
		obj.find('.arf_clr_disable').css('display','inline-block');
		obj.find('.arf_coloroption_sub[data-cls="arf_clr_disable"]').hide();
	} else {
		obj.find('.arf_clr_disable').hide();
		obj.find('.arf_coloroption_sub[data-cls="arf_clr_disable"]').show();
	}
}
function arf_change_form_submission_type($this)
{
	if( jQuery($this).val() == '1' )
	{
		jQuery('.arf_success_message_show_time_wrapper').show();	
	}
	else
	{
		jQuery('.arf_success_message_show_time_wrapper').hide();			
	}
}
function arfvalidatenumber_admin(field, event)
{
    var nVer = navigator.appVersion;
    var nAgt = navigator.userAgent;
    var browserName = navigator.appName;
    var fullVersion = '' + parseFloat(navigator.appVersion);
    var majorVersion = parseInt(navigator.appVersion, 10);
    var nameOffset, verOffset, ix;

    // In Opera 15+, the true version is after "OPR/" 
    if ((verOffset = nAgt.indexOf("OPR/")) != -1) {
        browserName = "Opera";
    }
    // In older Opera, the true version is after "Opera" or after "Version"
    else if ((verOffset = nAgt.indexOf("Opera")) != -1) {
        browserName = "Opera";
    }
    // In MSIE, the true version is after "MSIE" in userAgent
    else if ((verOffset = nAgt.indexOf("MSIE")) != -1) {
        browserName = "Microsoft Internet Explorer";
        browserName = "Netscape";
        fullVersion = nAgt.substring(verOffset + 5);
    }
    // In Chrome, the true version is after "Chrome" 
    else if ((verOffset = nAgt.indexOf("Chrome")) != -1) {
        browserName = "Chrome";
    }
    // In Safari, the true version is after "Safari" or after "Version" 
    else if ((verOffset = nAgt.indexOf("Safari")) != -1) {
        browserName = "Safari";
    }
    // In Firefox, the true version is after "Firefox" 
    else if ((verOffset = nAgt.indexOf("Firefox")) != -1) {
        browserName = "Firefox";
    }

    // In most other browsers, "name/version" is at the end of userAgent 
    else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) <
            (verOffset = nAgt.lastIndexOf('/')))
    {
        browserName = nAgt.substring(nameOffset, verOffset);
        if (browserName.toLowerCase() == browserName.toUpperCase()) {
            browserName = navigator.appName;
        }
    }

    if (browserName == "Chrome" || browserName == "Safari" || browserName == "Opera")
    {
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 116 || event.keyCode == 107 || event.keyCode == 109 || event.keyCode == 110 ||
                (event.keyCode == 190 && event.shiftKey == false) ||
                (event.keyCode == 61 && event.shiftKey == true) ||
                (event.keyCode == 173 && event.shiftKey == false) ||
                (event.keyCode == 189 && event.shiftKey == false) ||
                (event.keyCode == 187 && event.shiftKey == true) ||
                // Allow: Ctrl+A
                        (event.keyCode == 65 && event.ctrlKey === true) ||
                        // Allow: Ctrl+C
                                (event.keyCode == 67 && event.ctrlKey === true) ||
                                // Allow: Ctrl+C
                                        (event.keyCode == 88 && event.ctrlKey === true) ||
                                        // Allow: home, end, left, right
                                                (event.keyCode >= 35 && event.keyCode <= 39)) {
                                    // let it happen, don't do anything
                                    return;
                                } else {
                                    // Ensure that it is a number and stop the keypress
                                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                        event.preventDefault();
                                    }
                                }
                            }
                            else if (browserName == "Firefox")
                            {
                                if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 116 || event.keyCode == 107 || event.keyCode == 109 || event.keyCode == 110 || event.keyCode == 189 ||
                                        (event.keyCode == 190 && event.shiftKey == false) ||
                                        (event.keyCode == 61 && event.shiftKey == true) ||
                                        (event.keyCode == 173 && event.shiftKey == false) ||
                                        (event.keyCode == 187 && event.shiftKey == true) ||
                                        // Allow: Ctrl+A
                                                (event.keyCode == 65 && event.ctrlKey === true) ||
                                                // Allow: Ctrl+C
                                                        (event.keyCode == 67 && event.ctrlKey === true) ||
                                                        // Allow: Ctrl+C
                                                                (event.keyCode == 88 && event.ctrlKey === true) ||
                                                                // Allow: home, end, left, right
                                                                        (event.keyCode >= 35 && event.keyCode <= 39)) {
                                                            // let it happen, don't do anything
                                                            return;
                                                        } else {
                                                            // Ensure that it is a number and stop the keypress
                                                            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                                                event.preventDefault();
                                                            }
                                                        }
                                                    }
                                                    else if (browserName == "Microsoft Internet Explorer" || browserName == "Netscape")
                                                    {
                                                        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 116 || event.keyCode == 107 || event.keyCode == 109 || event.keyCode == 110 ||
                                                                (event.keyCode == 190 && event.shiftKey == false) ||
                                                                (event.keyCode == 61 && event.shiftKey == true) ||
                                                                (event.keyCode == 173 && event.shiftKey == false) ||
                                                                (event.keyCode == 187 && event.shiftKey == true) ||
                                                                (event.keyCode == 189 && event.shiftKey == false) ||
                                                                // Allow: Ctrl+A
                                                                        (event.keyCode == 65 && event.ctrlKey === true) ||
                                                                        // Allow: Ctrl+C
                                                                                (event.keyCode == 67 && event.ctrlKey === true) ||
                                                                                // Allow: Ctrl+C
                                                                                        (event.keyCode == 88 && event.ctrlKey === true) ||
                                                                                        // Allow: home, end, left, right
                                                                                                (event.keyCode >= 35 && event.keyCode <= 39)) {
                                                                                    // let it happen, don't do anything
                                                                                    return;
                                                                                } else {
                                                                                    // Ensure that it is a number and stop the keypress
                                                                                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                                                                        event.preventDefault ? event.preventDefault() : event.returnValue = false;
                                                                                        //event.preventDefault();
                                                                                    }
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                                if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 116 || event.keyCode == 107 || event.keyCode == 109 || event.keyCode == 110 || event.keyCode == 187 ||
                                                                                        (event.keyCode == 190 && event.shiftKey == false) ||
                                                                                        (event.keyCode == 61 && event.shiftKey == true) ||
                                                                                        (event.keyCode == 173 && event.shiftKey == false) ||
                                                                                        (event.keyCode == 189 && event.shiftKey == true) ||
                                                                                        // Allow: Ctrl+A
                                                                                                (event.keyCode == 65 && event.ctrlKey === true) ||
                                                                                                // Allow: Ctrl+C
                                                                                                        (event.keyCode == 67 && event.ctrlKey === true) ||
                                                                                                        // Allow: Ctrl+C
                                                                                                                (event.keyCode == 88 && event.ctrlKey === true) ||
                                                                                                                // Allow: home, end, left, right
                                                                                                                        (event.keyCode >= 35 && event.keyCode <= 39)) {
                                                                                                            // let it happen, don't do anything
                                                                                                            return;
                                                                                                        } else {
                                                                                                            // Ensure that it is a number and stop the keypress
                                                                                                            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                                                                                                event.preventDefault();
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }