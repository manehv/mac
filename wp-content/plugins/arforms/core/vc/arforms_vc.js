jQuery(document).ready(function() {		
								
	jQuery('.ARForms_Popup_Shortode_arfield').each(function(){
		var fild_value = jQuery(this).val();															
		var fild_name = jQuery(this).attr('id');

		if(fild_name == 'id'){
			jQuery('#arfaddformid_vc_popup option[value="' + fild_value + '"]').prop('selected', true);
			jQuery('input#Arf_param_id').val(fild_value);
		}
		
		if(fild_name == 'shortcode_type'){
			if(fild_value == 'normal'){
				jQuery('#shortcode_type_normal_vc').attr('checked', true);
				jQuery('#show_link_inner').slideDown();
				jQuery('#show_link_type_vc').slideUp(700);
				jQuery("#arf_shortcode_type").val(fild_value);	
			}
			if(fild_value == 'popup'){
				jQuery('#shortcode_type_popup_vc').attr('checked', true);
				jQuery('#show_link_inner').slideUp();
				jQuery('#show_link_type_vc').slideDown(700);	
				jQuery("#arf_shortcode_type").val(fild_value);	
				
			}
		}
		
		if(fild_name == 'type'){
			jQuery('#link_type_vc option[value="' + fild_value + '"]').prop('selected', true);
			arf_set_link_type_data(fild_value);
		}
		
		if(fild_name == 'position'){
			jQuery('#link_position_vc option[value="' + fild_value + '"]').prop('selected', true);
		}
		
		if(fild_name == 'desc'){
			jQuery("input#short_caption").val(fild_value);
		}
		if(fild_name == 'width'){
			jQuery("input#modal_width").val(fild_value);
		}
		if(fild_name == 'height'){
			jQuery("input#modal_height").val(fild_value);
		}
		if(fild_name == 'angle'){
			jQuery('#button_angle option[value="' + fild_value + '"]').prop('selected', true);
		}
		
	}); 
	
	
	jQuery('.vc_panel-btn-save, .wpb_save_edit_form' ).click(function(){
		var form_id =  jQuery("#arfaddformid_vc_popup").val();	
		if(form_id == ''){
			alert(jQuery("#arf_blank_forms_msg").val());	
			return false;																
		}
	});
	
	
	jQuery(".sltmodal select").selectpicker(); 
	
	jQuery('#shortcode_type_popup_vc').click(function(){
		jQuery('#show_link_inner').slideUp();
		jQuery('#show_link_type_vc').slideDown(700);	
		jQuery("#arf_shortcode_type").val(jQuery(this).val());	
	});
	jQuery('#shortcode_type_normal_vc').click(function(){
		jQuery('#show_link_inner').slideDown();
		jQuery('#show_link_type_vc').slideUp(700);
		jQuery("#arf_shortcode_type").val(jQuery(this).val());	
	});
	


	jQuery('#link_type_vc').change(function(){
		var show_link_type = jQuery('#link_type_vc').val();
		arf_set_link_type_data(show_link_type);
	});



	jQuery('#arfaddformid_vc').change(function(){
		var arformid = jQuery(this).val();
		if(arformid){
			jQuery(".wpb_vc_param_value").val(arformid);
		}
	});
	
	jQuery('#arfaddformid_vc_popup').change(function(){
		var arformid = jQuery(this).val();
		if(arformid){
			jQuery("#Arf_param_id").val(arformid);
		}
	});
	
});	

function changeflybutton()
{
	var angle	= jQuery('#button_angle').val();
	angle	= angle != '' ? angle : 0;
	jQuery('.arf_fly_btn').css('transform', 'rotate('+angle+'deg)');
}
function arfchangeflybtn()
{
	if( jQuery('#link_position_fly').val() == 'right' ){
		jQuery('.arfbtnleft').hide();
		jQuery('.arfbtnright').show();
	} else {
		jQuery('.arfbtnleft').show();
		jQuery('.arfbtnright').hide();
	}		
}


/***************/
	
function changetopposition(myval){
	var modalheight = jQuery(window).height();
	var top_height 	= Number(modalheight) / 2;
	
	if(myval == "fly")
		jQuery('#arfinsertform').css('top',(top_height-230)+'px');
	else
		jQuery('#arfinsertform').css('top',(top_height-180)+'px');
}


function arf_set_link_type_data(show_link_type){
	if(show_link_type == 'sticky')
	{
		jQuery('#is_sticky_vc').slideDown();
		jQuery('#is_fly_vc').slideUp();
		jQuery('#button_angle_div_vc').slideUp();
	}
	else if(show_link_type == 'fly')
	{	
		jQuery('#is_fly_vc').slideDown();
		jQuery('#is_sticky_vc').slideUp();
		jQuery('#button_angle_div_vc').slideDown();
	}
	else
	{
		jQuery('#is_sticky_vc').slideUp();
		jQuery('#is_fly_vc').slideUp();
		jQuery('#button_angle_div_vc').slideUp();	
	}
	
	if( show_link_type == 'onload' ){
		jQuery('#shortcode_caption_vc').slideUp();
	} else {
		jQuery('#shortcode_caption_vc').slideDown();
	}
}

function showarfpopupfieldlist()
{
	var fild_value = jQuery('input[name="shortcode_type"]:checked').val();
	var fild_name = 'shortcode_type';

	if(fild_name == 'id'){
		jQuery('#arfaddformid_vc_popup option[value="' + fild_value + '"]').prop('selected', true);
		jQuery('input#Arf_param_id').val(fild_value);
	}
	
	if(fild_name == 'shortcode_type'){
		if(fild_value == 'normal'){
			jQuery('#shortcode_type_normal_vc').attr('checked', true);
			jQuery('#show_link_inner').slideDown();
			jQuery('#show_link_type_vc').slideUp(700);
			jQuery("#arf_shortcode_type").val(fild_value);	
		}
		if(fild_value == 'popup'){
			jQuery('#shortcode_type_popup_vc').attr('checked', true);
			jQuery('#show_link_inner').slideUp();
			jQuery('#show_link_type_vc').slideDown(700);	
			jQuery("#arf_shortcode_type").val(fild_value);	
			
		}
	}
	
}

function set_arfaddformid_vc_popup(id)
{
	if(id){
		jQuery("#Arf_param_id").val(id);
	}
}