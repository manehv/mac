//---------- for conditional logic ----------//
function arf_rule_apply(form_key, field_id, dep_array){
	
	var object = jQuery('#form_'+form_key);
	for( key in dep_array ) {
		
		var f_id = dep_array[key];
		
		var f_type = window['arf_cl'][form_key][f_id]['field_type'];
		
		var page_no = window['arf_cl'][form_key][f_id]['page'];
			
		var get_display = window['arf_cl'][form_key][f_id]['display'];
		
		var get_if_cond = window['arf_cl'][form_key][f_id]['if_cond'];
		
		var get_rules 	= window['arf_cl'][form_key][f_id]['rules'];
		
		var rule_cout   = get_rules.length;
		
		var matched = 0;
		
		jQuery.each(get_rules, function(index,element){
			var rule_field_id 	= get_rules[index]['field_id'];
			var rule_field_type	= get_rules[index]['field_type'];
			var rule_operator 	= get_rules[index]['operator'];
			var rule_value 		= get_rules[index]['value'];
			if( calculate_rule(rule_field_id, rule_field_type, rule_operator, rule_value) ){
				matched++;
			}
		});
		
		if( ( get_if_cond == 'all' && rule_cout == matched ) || ( get_if_cond == 'any' && matched > 0 ) ){
			apply_rule_on_field(object, f_id, get_display, f_type, page_no);
		} else {
			apply_default_field(object, f_id, get_display, f_type, page_no);
		}
	}
	
	setTimeout(function(){
			jQuery('div.arfmodal').not('#maincontainerdiv div.arfmodal, #arfformsettingpage div.arfmodal').each(function(){
				var screenwidth = jQuery(window).width();
				var windowHeight = jQuery(window).height()- Number(60);
				var windowHeightOrg = jQuery(window).height();
				var actualheight = jQuery(this).find('.arf_fieldset').height();
				
				if( screenwidth <= 770){
					if(windowHeight > actualheight){
						jQuery(this).find('.arf_fieldset').css('height',windowHeightOrg+'px');
						jQuery(this).find('.arf_fieldset').css('min-height','100%');
					}else{
						jQuery(this).find('.arf_fieldset').css('height','auto');
					}	
				}				
			});
			
			jQuery('div.arform_right_fly_form_block_right_main').each(function(){
				var screenwidth = jQuery(window).width();
				var windowHeight = jQuery(window).height()- Number(60) ;
				var actualheight = jQuery(this).find('.arf_fieldset').height();
				
				if( screenwidth <= 770 ){
					if( windowHeight > actualheight ){
						jQuery(this).find('.arf_fieldset').css('height',windowHeight+'px');
					} else {
						jQuery(this).find('.arf_fieldset').css('height','auto');
					}	
				}
			});
			
			jQuery('div.arform_left_fly_form_block_left_main').each(function(){
				var screenwidth = jQuery(window).width();
				var windowHeight = jQuery(window).height()- Number(60) ;
				var actualheight = jQuery(this).find('.arf_fieldset').height();
				if( screenwidth <= 770 ){
					if( windowHeight > actualheight ){
						jQuery(this).find('.arf_fieldset').css('height',windowHeight+'px');
					} else {
						jQuery(this).find('.arf_fieldset').css('height','auto');
					}	
				}
			});
		},500);		
}

function calculate_rule(rule_field_id, rule_field_type, rule_operator, rule_value){
	
	var value2 = rule_value;
	
	if( rule_field_type == 'checkbox') {
		
		var chk = 0;		
		jQuery('input[name="item_meta['+rule_field_id+'][]"]:checked').each(function(i){
			var value1 = jQuery(this).val();
			if( match_rule(value1, value2, rule_operator) ){
				chk++;
			}
		});
		
		if(chk > 0) {
			return true; 
		} else {
			return false;
		}
	} else {		
		
		var value1 = '';
		
		if( rule_field_type == 'radio' || rule_field_type == 'like' ){
			var value1 = jQuery('input[name="item_meta['+rule_field_id+']"]:checked').val();
		} else if( rule_field_type == 'select' ) {
			var value1 = jQuery('select[name="item_meta['+rule_field_id+']"]').val();	
		} else if(rule_field_type == 'textarea') {
			var value1 = jQuery('textarea[name="item_meta['+rule_field_id+']"]').val();
		} else if( rule_field_type == 'scale' ) { 
			widget_id = jQuery('input[name="item_meta['+rule_field_id+']"]').attr('id');
			widget_id = widget_id.replace('field_','');
			
			var color = jQuery('#field_'+widget_id).attr('data-color');
			var datasize = jQuery('#field_'+widget_id).attr('data-size');
			var len = jQuery('#'+widget_id+' .ratings_vote_'+color+datasize).length;
			var value1 = parseInt(len) - parseInt(1);
			if( value1 == 0 ){
				value1 = '';
			}
		} else {
			var value1 = jQuery('input[name="item_meta['+rule_field_id+']"]').val();
		}
		
		return match_rule(value1, value2, rule_operator); 
	}
	
	return false;
}

function match_rule(value1, value2, operator){

	value1 = value1 ? ( isNaN(value1) ? value1.toLowerCase() : value1 ) : ( (value1 == '0') ? 0 : "");

    value2 = value2 ? ( isNaN(value2) ? value2.toLowerCase() : value2 ) : ( (value2 == '0') ? 0 : "");
	
	value1 = value1 ? jQuery.trim(value1) : ( (value1 == '0') ? 0 : "");
	
	value2 = value2 ? jQuery.trim(value2) : ( (value2 == '0') ? 0 : "");
	
	switch (operator) {
		
		case 'is':
			return value1 == value2;
			break;
			
		case 'is not':
		    return value1 != value2;
			break;
			
		case 'greater than':
			value1 = jQuery.isNumeric(value1) ? parseFloat(value1) : 0;
			value2 = jQuery.isNumeric(value2) ? parseFloat(value2) : 0;
			return value1 > value2;
			break;
			
		case 'less than':
			value1 = jQuery.isNumeric(value1) ? parseFloat(value1) : 0;
			value2 = jQuery.isNumeric(value2) ? parseFloat(value2) : 0;
  			return value1 < value2;  
			break;
		
		case 'contains':
			if( value1 != '' && value2 == '' ){
				return false;
			} else if( value1 == '' && value2 != '' ) {
				return false;
			} else if( value1 == '' && value2 == '' ) {
				return true;
			} else if(value1 != '' && value2 != '') {
				return ( value1.indexOf(value2) >= 0  ) ? true : false;
			}
			break;
			
		case 'not contains':			
			if( value1 != '' && value2 == '' ){
				return true;
			} else if( value1 == '' && value2 != '' ) {
				return true;
			} else if( value1 == '' && value2 == '' ){
				return false;
			} else if(value1 != '' && value2 != '') {
				return ( value1.indexOf(value2) >= 0  ) ? false : true;
			}
			break;	
	}
	
	return false;
}
		 
function apply_rule_on_field(object, f_id, get_display, f_type, page_no){
	
	if( f_type == 'break' ){
		
		var form_id = jQuery(object).find('input[name="form_id"]').val();
		
		var data_hide = jQuery('#get_hidden_pages_'+form_id).val();
		
		page_no  = page_no ? page_no : 1;
		page_nav = parseInt(page_no) + 1
		
		if( get_display == 'show' ) {
			
			var data_string = '';
			//set aaray
			if(data_hide != '') {
				var data_arr = data_hide.split(',');
				
				if( jQuery.inArray( page_no, data_arr ) == -1){
				
				} else {
					data_hide = data_hide.replace(page_no+',', '');					
				}
				
			} else {
				data_hide = ',';	
			}
			//store
			data_hide = ( data_hide =='' ) ? ',' : data_hide;
			
			
			//for first page
			var total_pages = jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var current_pages = jQuery(object).find('.page_break:visible').attr('id');
				current_pages = current_pages.replace('page_', '');		
			var page_page_no   = page_no;
			
			if( total_pages == 1 ){
				jQuery(object).find('#arf_submit_div_0').show();
				jQuery(object).find('#previous_last').show();	
				jQuery(object).find('#page_last').hide();
				jQuery(object).find('#submit_form_'+form_id).val('1');
			
			} else {
				
				if( total_pages == page_page_no && page_page_no == current_pages ){	
					jQuery(object).find('#arf_submit_div_'+page_no).hide();
					jQuery(object).find('#previous_last').show();	
					jQuery(object).find('#page_last').show();
					jQuery(object).find('#page_last .arf_submit_div').show();
					jQuery(object).find('#submit_form_'+form_id).val('0');	
				
				} else {
					
					var default_hide_pages = data_hide;
					var page_page_no_new_i = page_page_no; 
					
					for(i=0; i <= total_pages; i++ ){
						if( default_hide_pages.indexOf(','+i+',') >= 0 ) {													
							continue;
						} else {
							page_page_no_new_i = i;
						}						
					}
					
					jQuery('#last_show_page_'+form_id).val(page_page_no_new_i);
					var page_prev_no = parseInt(page_page_no_new_i) - parseInt(1);
					
					if( page_page_no_new_i == current_pages && page_page_no_new_i == 0) {
						
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else if( page_page_no_new_i == current_pages ) {
						
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').show();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else {
						jQuery(object).find('#arf_submit_div_'+current_pages).show();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').hide();
						jQuery(object).find('#submit_form_'+form_id).val('1');	
					}
					
					
				}
			}
			//for first page
			
			// for paeg break
			var data_id = jQuery(object).find('#submit_form_'+form_id).attr('data-val');
			var data_id = parseInt(data_id) - parseInt(1);
							
			jQuery('#get_hidden_pages_'+form_id).val(data_hide);
			//jQuery(object).find('#page_nav_'+page_nav).slideDown('slow');
			//jQuery(object).find('#page_nav_arrow_'+page_nav).slideDown('slow');
			jQuery(object).find('#page_nav_'+page_nav).show();
			jQuery(object).find('#page_nav_arrow_'+page_nav).show();
			
			var last_show_page = jQuery('#last_show_page_'+form_id).val();
				last_show_page = parseInt(last_show_page) + parseInt(1);
			if(last_show_page > 1){
				jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
				jQuery(object).find('#page_nav_'+last_show_page).addClass('arf_page_last');
			}
			
			//for survey total page
			var max_page_no	= jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var total_page_number = max_page_no;				
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
					total_page_number = parseInt(total_page_number) - parseInt(1);				
				} else {
				}
			}
			total_page_number = parseInt(total_page_number) + parseInt(1);	
			jQuery(object).find('.total_survey_page').html(total_page_number);
			
			var current_i = 0;
			var current_page_number = jQuery(object).find('.current_survey_page').html();
				current_page_number = ( current_page_number !== undefined ) ? current_page_number : current_pages;
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
				} else {					
					current_i = parseInt(current_i) + parseInt(1);								
					if( current_pages == pi ){
						current_page_number = current_i; 
					}
				}
			}
			jQuery(object).find('.current_survey_page').html(current_page_number);
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_progressbar(object, current_page_number, total_page_number); // change progress bar
			}
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_pagenavigation(object, current_page_number, total_page_number); // change nav bar
			}
					
		} else if( get_display == 'hide' ) {
			
			if(data_hide != '') {
				var data_arr = data_hide.split(',');
				
				if( jQuery.inArray( page_no, data_arr ) == -1){
					data_hide = data_hide + page_no+',';
				}
			} else {
				data_hide = page_no+',';	
			}
			
			//for first page
			var total_pages = jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var current_pages = jQuery(object).find('.page_break:visible').attr('id');
				current_pages = current_pages.replace('page_', '');
			var page_page_no   = page_no;
			
			if( total_pages == 1 ){
				jQuery(object).find('#arf_submit_div_0').hide();
				jQuery(object).find('#previous_last').hide();	
				jQuery(object).find('#page_last').show();
				jQuery(object).find('#page_last .arf_submit_div').show();
				jQuery(object).find('#submit_form_'+form_id).val('0');
			
			} else {
				
				if( total_pages == page_page_no && page_page_no == current_pages ){					
					jQuery(object).find('#arf_submit_div_'+page_no).show();
					jQuery(object).find('#previous_last').hide();	
					jQuery(object).find('#page_last').hide();
					jQuery(object).find('#submit_form_'+form_id).val('1');	
				
				} else {
					
					var default_hide_pages = data_hide;
					var page_page_no_new_i = page_page_no; 
					var total_hide = page_page_no;
					
					for(i=0; i <= total_pages; i++ ){
						if( default_hide_pages.indexOf(','+i+',') >= 0 ) {													
							continue;
						} else {
							page_page_no_new_i = i;
						}						
					}
					
					jQuery('#last_show_page_'+form_id).val(page_page_no_new_i);
					var page_prev_no = parseInt(page_page_no_new_i) - parseInt(1);
										
					if( page_page_no_new_i == current_pages && page_page_no_new_i == 0) {
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else if( page_page_no_new_i == current_pages ) {
						
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').show();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');
					} else {
						jQuery(object).find('#arf_submit_div_'+current_pages).show();	
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').hide();
						jQuery(object).find('#submit_form_'+form_id).val('1');	
					}					
					
				}
			}
			//for first page
			
			jQuery('#get_hidden_pages_'+form_id).val(data_hide);
			//jQuery(object).find('#page_nav_'+page_nav).slideUp('slow');
			//jQuery(object).find('#page_nav_arrow_'+page_nav).hide('slow');
			jQuery(object).find('#page_nav_'+page_nav).hide();
			jQuery(object).find('#page_nav_arrow_'+page_nav).hide();
			var last_show_page = jQuery('#last_show_page_'+form_id).val();
				last_show_page = parseInt(last_show_page) + parseInt(1);
			if(last_show_page > 1){
				jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
				jQuery(object).find('#page_nav_'+last_show_page).addClass('arf_page_last');
			}
			
			//for survey total page
			var max_page_no	= jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var total_page_number = max_page_no;				
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
					total_page_number = parseInt(total_page_number) - parseInt(1);				
				} else {
				}
			}
			total_page_number = parseInt(total_page_number) + parseInt(1);	
			jQuery(object).find('.total_survey_page').html(total_page_number);
			
			var current_i = 0;
			var current_page_number = jQuery(object).find('.current_survey_page').html();
				current_page_number = ( current_page_number !== undefined ) ? current_page_number : current_pages;
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
				} else {					
					current_i = parseInt(current_i) + parseInt(1);								
					if( current_pages == pi ){
						current_page_number = current_i; 
					}
				}
			}
			jQuery(object).find('.current_survey_page').html(current_page_number);
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_progressbar(object, current_page_number, total_page_number); // change progress bar
			}
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_pagenavigation(object, current_page_number, total_page_number); // change nav bar
			}
						
		}
		
	} else {
		
		if( f_type == 'submit' ){
			
			if( get_display == 'show' ){
				jQuery(object).find('.arf_submit_btn').attr('disabled', false);
				jQuery(object).find('.arf_submit_btn').removeClass('arfsubmitdisabled');
			} else if( get_display == 'hide' ){
				jQuery(object).find('.arf_submit_btn').attr('disabled', true);
				jQuery(object).find('.arf_submit_btn').addClass('arfsubmitdisabled');
			}
			
		} else if( f_type == 'divider' ){
			
			if( get_display == 'show' ){
				jQuery(object).find('#heading_'+f_id).slideDown('slow');
				arf_change_slider(object, f_id, 1);
			} else if( get_display == 'hide' ){
				jQuery(object).find('#heading_'+f_id).slideUp('slow');
			}
			
		} else {
			
			if( get_display == 'show' ){
				jQuery(object).find('#arf_field_'+f_id+'_container').slideDown('slow');
				if( f_type == 'slider' ){
					arf_change_slider(object, f_id, 0);
				}
			} else if( get_display == 'hide' ){
				var form_key 		= jQuery(object).attr('id');
					form_key 		= form_key.replace('form_', '');
				var field_key 		=  window['arf_cl'][ form_key ][ f_id ]['field_key'];	
				var default_value	=  window['arf_cl'][ form_key ][ f_id ]['default_value'];
				
				arf_set_field_default_value(field_key, default_value, f_type, f_id, object );
				jQuery(object).find('#arf_field_'+f_id+'_container').slideUp('slow');
			}
			
		}
	}
	
}

function apply_default_field(object, f_id, get_display, f_type, page_no){
	if( f_type == 'break' ){
		
		var form_id = jQuery(object).find('input[name="form_id"]').val();
		
		var data_hide = jQuery('#get_hidden_pages_'+form_id).val();
		
		page_no  = page_no ? page_no : 1;
		page_nav = parseInt(page_no) + 1
		
		if( get_display == 'hide' ) {
			
			var data_string = '';
			//set aaray
			if(data_hide != '') {
				var data_arr = data_hide.split(',');
				
				if( jQuery.inArray( page_no, data_arr ) == -1){
									
				} else {
					data_hide = data_hide.replace(page_no+',', '');					
				}
				
			} else {
				data_hide = '';	
			}
			//store
			data_hide = ( data_hide =='' ) ? ',' : data_hide;
			
			
			//for first page
			var total_pages = jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var current_pages = jQuery(object).find('.page_break:visible').attr('id');
				current_pages = current_pages.replace('page_', '');		
			var page_page_no   = page_no;
			
			if( total_pages == 1 ){
				jQuery(object).find('#arf_submit_div_0').show();
				jQuery(object).find('#previous_last').show();	
				jQuery(object).find('#page_last').hide();
				jQuery(object).find('#submit_form_'+form_id).val('1');
			
			} else {
				
				if( total_pages == page_page_no && page_page_no == current_pages ){

					jQuery(object).find('#arf_submit_div_'+page_no).hide();
					jQuery(object).find('#previous_last').show();	
					jQuery(object).find('#page_last').show();
					jQuery(object).find('#page_last .arf_submit_div').show();
					jQuery(object).find('#submit_form_'+form_id).val('0');	
				
				} else {
					
					var default_hide_pages = data_hide;
					var page_page_no_new_i = page_page_no; 
					
					for(i=0; i <= total_pages; i++ ){
						if( default_hide_pages.indexOf(','+i+',') >= 0 ) {													
							continue;
						} else {
							page_page_no_new_i = i;
						}						
					}
					
					jQuery('#last_show_page_'+form_id).val(page_page_no_new_i);
					var page_prev_no = parseInt(page_page_no_new_i) - parseInt(1);
					
					if( page_page_no_new_i == current_pages && page_page_no_new_i == 0) {
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else if( page_page_no_new_i == current_pages ) {
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').show();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else {
						jQuery(object).find('#arf_submit_div_'+current_pages).show();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').hide();
						jQuery(object).find('#submit_form_'+form_id).val('1');	
					}
					
					
				}
			}
			//for first page
			
			// for paeg break
			var data_id = jQuery(object).find('#submit_form_'+form_id).attr('data-val');			
			var data_id = parseInt(data_id) - parseInt(1);
						
			jQuery('#get_hidden_pages_'+form_id).val(data_hide);
			//jQuery(object).find('#page_nav_'+page_nav).slideDown('slow');
			//jQuery(object).find('#page_nav_arrow_'+page_nav).slideDown('slow');
			jQuery(object).find('#page_nav_'+page_nav).show();
			jQuery(object).find('#page_nav_arrow_'+page_nav).show();
			
			var last_show_page = jQuery('#last_show_page_'+form_id).val();
				last_show_page = parseInt(last_show_page) + parseInt(1);
			if(last_show_page > 1){
				jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
				jQuery(object).find('#page_nav_'+last_show_page).addClass('arf_page_last');
			}
			
			//for survey total page
			var max_page_no	= jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var total_page_number = max_page_no;				
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
					total_page_number = parseInt(total_page_number) - parseInt(1);				
				} else {
				}
			}
			total_page_number = parseInt(total_page_number) + parseInt(1);	
			jQuery(object).find('.total_survey_page').html(total_page_number);
			
			var current_i = 0;
			var current_page_number = jQuery(object).find('.current_survey_page').html();
				current_page_number = ( current_page_number !== undefined ) ? current_page_number : current_pages;
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
				} else {					
					current_i = parseInt(current_i) + parseInt(1);								
					if( current_pages == pi ){
						current_page_number = current_i; 
					}
				}
			}
			jQuery(object).find('.current_survey_page').html(current_page_number);
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_progressbar(object, current_page_number, total_page_number); // change progress bar
			}
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_pagenavigation(object, current_page_number, total_page_number); // change nav bar
			}
			
		} else if( get_display == 'show' ) {
			if(data_hide != '') {
				var data_arr = data_hide.split(',');
				
				if( jQuery.inArray( page_no, data_arr ) == -1){
					data_hide = data_hide + page_no+',';
				}
				
			} else {
				data_hide = page_no+',';	
			}
			
			//for first page
			var total_pages = jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var current_pages = jQuery(object).find('.page_break:visible').attr('id');
				current_pages = current_pages.replace('page_', '');		
			var page_page_no   = page_no;
			
			if( total_pages == 1 ){
				jQuery(object).find('#arf_submit_div_0').hide();
				jQuery(object).find('#previous_last').hide();	
				jQuery(object).find('#page_last').show();
				jQuery(object).find('#page_last .arf_submit_div').show();
				jQuery(object).find('#submit_form_'+form_id).val('0');
			
			} else {
			 	
				if( total_pages == page_page_no && page_page_no == current_pages ){					
					jQuery(object).find('#arf_submit_div_'+page_no).show();
					jQuery(object).find('#previous_last').hide();	
					jQuery(object).find('#page_last').hide();
					jQuery(object).find('#submit_form_'+form_id).val('1');	
				
				} else {
					
					var default_hide_pages = data_hide;
					var page_page_no_new_i = page_page_no; 
					var total_hide = page_page_no;
					
					for(i=0; i <= total_pages; i++ ){
						if( default_hide_pages.indexOf(','+i+',') >= 0 ) {													
							continue;
						} else {
							page_page_no_new_i = i;
						}						
					}
					
					jQuery('#last_show_page_'+form_id).val(page_page_no_new_i);
					var page_prev_no = parseInt(page_page_no_new_i) - parseInt(1);
										
					if( page_page_no_new_i == current_pages && page_page_no_new_i == 0) {
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else if( page_page_no_new_i == current_pages ) {
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').show();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else {
						jQuery(object).find('#arf_submit_div_'+current_pages).show();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').hide();
						jQuery(object).find('#submit_form_'+form_id).val('1');	
					}
					
					
				}
			}
			//for first page
			
			jQuery('#get_hidden_pages_'+form_id).val(data_hide);	
			//jQuery(object).find('#page_nav_'+page_nav).slideUp('slow');
			//jQuery(object).find('#page_nav_arrow_'+page_nav).hide('slow');
			jQuery(object).find('#page_nav_'+page_nav).hide();
			jQuery(object).find('#page_nav_arrow_'+page_nav).hide();
			
			var last_show_page = jQuery('#last_show_page_'+form_id).val();
				last_show_page = parseInt(last_show_page) + parseInt(1);
			if(last_show_page > 1){
				jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
				jQuery(object).find('#page_nav_'+last_show_page).addClass('arf_page_last');
			}
			
			//for survey total page
			var max_page_no	= jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var total_page_number = max_page_no;				
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
					total_page_number = parseInt(total_page_number) - parseInt(1);				
				} else {
				}
			}
			total_page_number = parseInt(total_page_number) + parseInt(1);	
			jQuery(object).find('.total_survey_page').html(total_page_number);
			
			var current_i = 0;
			var current_page_number = jQuery(object).find('.current_survey_page').html();
				current_page_number = ( current_page_number !== undefined ) ? current_page_number : current_pages;
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
				} else {					
					current_i = parseInt(current_i) + parseInt(1);								
					if( current_pages == pi ){
						current_page_number = current_i; 
					}
				}
			}
			jQuery(object).find('.current_survey_page').html(current_page_number);
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_progressbar(object, current_page_number, total_page_number); // change progress bar
			}
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_pagenavigation(object, current_page_number, total_page_number); // change nav bar
			}
			
		}
		
	} else {
		
		if( f_type == 'submit' ){
			
			if( get_display == 'show' ){
				jQuery(object).find('.arf_submit_btn').attr('disabled', true);
				jQuery(object).find('.arf_submit_btn').addClass('arfsubmitdisabled');
			} else if( get_display == 'hide' ){
				jQuery(object).find('.arf_submit_btn').attr('disabled', false);
				jQuery(object).find('.arf_submit_btn').removeClass('arfsubmitdisabled');
			}
			
		} else if( f_type == 'divider' ){
			
			if( get_display == 'show' ) {
				jQuery(object).find('#heading_'+f_id).slideUp('slow');
				arf_change_slider(object, f_id, 1);
			} else if( get_display == 'hide' ) {
				jQuery(object).find('#heading_'+f_id).slideDown('slow');
			}
		
		} else {
			
			if( get_display == 'show' ) {
				var form_key 		= jQuery(object).attr('id');
					form_key 		= form_key.replace('form_', '');
				var field_key 		=  window['arf_cl'][ form_key ][ f_id ]['field_key'];	
				var default_value	=  window['arf_cl'][ form_key ][ f_id ]['default_value'];
				arf_set_field_default_value(field_key, default_value, f_type, f_id, object );
				jQuery(object).find('#arf_field_'+f_id+'_container').slideUp('slow');
			} else if( get_display == 'hide' ) {
				jQuery(object).find('#arf_field_'+f_id+'_container').slideDown('slow');
				if( f_type == 'slider' ){
					arf_change_slider(object, f_id, 0);
				}
			}
	
		}
		
	}
	
}
//bulk rules apply start
function arf_rule_apply_bulk(form_key){
		
	var object = jQuery('#form_'+form_key);
	
	if( !window['arf_cl'] ){
		return;
	} else if( !window['arf_cl'][form_key] ) {
		return;
	} else if( window['arf_cl'][form_key].length == 0 ){
		return;
	}
	var dep_array = window['arf_cl'][form_key];
	
	for( key in dep_array ) {
		
		var f_id = key;
		
		var f_type = window['arf_cl'][form_key][f_id]['field_type'];
		
		var page_no = window['arf_cl'][form_key][f_id]['page'];
			
		var get_display = window['arf_cl'][form_key][f_id]['display'];
		
		var get_if_cond = window['arf_cl'][form_key][f_id]['if_cond'];
		
		var get_rules 	= window['arf_cl'][form_key][f_id]['rules'];
		
		var rule_cout   = get_rules.length;
		
		var matched = 0;
		
		jQuery.each(get_rules, function(index,element){
			var rule_field_id 	= get_rules[index]['field_id'];
			var rule_field_type	= get_rules[index]['field_type'];
			var rule_operator 	= get_rules[index]['operator'];
			var rule_value 		= get_rules[index]['value'];
			if( calculate_rule(rule_field_id, rule_field_type, rule_operator, rule_value) ){
				matched++;
			}
		});
		
		if( ( get_if_cond == 'all' && rule_cout == matched ) || ( get_if_cond == 'any' && matched > 0 ) ){
			apply_rule_on_field_bulk(object, f_id, get_display, f_type, page_no);
		} else {
			apply_default_field_bulk(object, f_id, get_display, f_type, page_no);
		}
	}
}

function apply_rule_on_field_bulk(object, f_id, get_display, f_type, page_no){
	
	if( f_type == 'break' ){
		
		var form_id = jQuery(object).find('input[name="form_id"]').val();
		
		var data_hide = jQuery('#get_hidden_pages_'+form_id).val();
		
		page_no  = page_no ? page_no : 1;
		page_nav = parseInt(page_no) + 1
		
		if( get_display == 'show' ) {
			
			var data_string = '';
			//set aaray
			if(data_hide != '') {
				var data_arr = data_hide.split(',');
				
				if( jQuery.inArray( page_no, data_arr ) == -1){
				
				} else {
					data_hide = data_hide.replace(page_no+',', '');					
				}
				
			} else {
				data_hide = ',';	
			}
			//store
			data_hide = ( data_hide =='' ) ? ',' : data_hide;
			
			//for first page
			var total_pages = jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var current_pages = jQuery(object).find('.page_break:visible').attr('id');
				current_pages = current_pages.replace('page_', '');		
			var page_page_no   = page_no;
			if( total_pages == 1 ){
				jQuery(object).find('#arf_submit_div_0').show();
				jQuery(object).find('#previous_last').show();	
				jQuery(object).find('#page_last').hide();
				jQuery(object).find('#submit_form_'+form_id).val('1');
			
			} else {
				
				if( total_pages == page_page_no && page_page_no == current_pages ){	
					jQuery(object).find('#arf_submit_div_'+page_no).hide();
					jQuery(object).find('#previous_last').show();	
					jQuery(object).find('#page_last').show();
					jQuery(object).find('#page_last .arf_submit_div').show();
					jQuery(object).find('#submit_form_'+form_id).val('0');	
				
				} else {
					
					var default_hide_pages = data_hide;
					var page_page_no_new_i = page_page_no; 
					
					for(i=0; i <= total_pages; i++ ){
						if( default_hide_pages.indexOf(','+i+',') >= 0 ) {												
							continue;
						} else {
							page_page_no_new_i = i;
						}						
					}
					
					jQuery('#last_show_page_'+form_id).val(page_page_no_new_i);
					var page_prev_no = parseInt(page_page_no_new_i) - parseInt(1);
					
					if( page_page_no_new_i == current_pages && page_page_no_new_i == 0) {
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else if( page_page_no_new_i == current_pages ) {
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').show();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else {
						jQuery(object).find('#arf_submit_div_'+current_pages).show();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').hide();
						jQuery(object).find('#submit_form_'+form_id).val('1');	
					}
					
					
				}
			}
			//for first page
			
			// for paeg break
			var data_id = jQuery(object).find('#submit_form_'+form_id).attr('data-val');
			var data_id = parseInt(data_id) - parseInt(1);
							
			jQuery('#get_hidden_pages_'+form_id).val(data_hide);
			jQuery(object).find('#page_nav_'+page_nav).show();
			jQuery(object).find('#page_nav_arrow_'+page_nav).show();
			
			var last_show_page = jQuery('#last_show_page_'+form_id).val();
				last_show_page = parseInt(last_show_page) + parseInt(1);
			if(last_show_page > 1){
				jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
				jQuery(object).find('#page_nav_'+last_show_page).addClass('arf_page_last');
			}
			
			//for survey total page
			var max_page_no	= jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var total_page_number = max_page_no;				
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
					total_page_number = parseInt(total_page_number) - parseInt(1);				
				} else {
				}
			}
			total_page_number = parseInt(total_page_number) + parseInt(1);	
			jQuery(object).find('.total_survey_page').html(total_page_number);
			
			var current_i = 0;
			var current_page_number = jQuery(object).find('.current_survey_page').html();
				current_page_number = ( current_page_number !== undefined ) ? current_page_number : current_pages;
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
				} else {					
					current_i = parseInt(current_i) + parseInt(1);								
					if( current_pages == pi ){
						current_page_number = current_i; 
					}
				}
			}
			jQuery(object).find('.current_survey_page').html(current_page_number);
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_progressbar(object, current_page_number, total_page_number); // change progress bar
			}
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_pagenavigation(object, current_page_number, total_page_number); // change nav bar
			}
			
		} else if( get_display == 'hide' ) {
			
			if(data_hide != '') {
				var data_arr = data_hide.split(',');
				
				if( jQuery.inArray( page_no, data_arr ) == -1){
					data_hide = data_hide + page_no+',';
				}
				
			} else {
				data_hide = page_no+',';	
			}
			
			//for first page
			var total_pages = jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var current_pages = jQuery(object).find('.page_break:visible').attr('id');
				current_pages = current_pages.replace('page_', '');
			var page_page_no   = page_no;
			if( total_pages == 1 ){
				jQuery(object).find('#arf_submit_div_0').hide();
				jQuery(object).find('#previous_last').hide();	
				jQuery(object).find('#page_last').show();
				jQuery(object).find('#page_last .arf_submit_div').show();
				jQuery(object).find('#submit_form_'+form_id).val('0');
			
			} else {
				
				if( total_pages == page_page_no && page_page_no == current_pages ){					
					jQuery(object).find('#arf_submit_div_'+page_no).show();
					jQuery(object).find('#previous_last').hide();	
					jQuery(object).find('#page_last').hide();
					jQuery(object).find('#submit_form_'+form_id).val('1');	
				
				} else {
					
					var default_hide_pages = data_hide;
					var page_page_no_new_i = page_page_no; 
					var total_hide = page_page_no;
					
					for(i=0; i <= total_pages; i++ ){
						if( default_hide_pages.indexOf(','+i+',') >= 0 ) {														
							continue;
						} else {
							page_page_no_new_i = i;
						}						
					}
					
					jQuery('#last_show_page_'+form_id).val(page_page_no_new_i);
					var page_prev_no = parseInt(page_page_no_new_i) - parseInt(1);
										
					if( page_page_no_new_i == current_pages && page_page_no_new_i == 0) {
						
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else if( page_page_no_new_i == current_pages ) {
						
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').show();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');
					} else {
						jQuery(object).find('#arf_submit_div_'+current_pages).show();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').hide();
						jQuery(object).find('#submit_form_'+form_id).val('1');	
					}
					
					
				}
			}
			//for first page
			
			jQuery('#get_hidden_pages_'+form_id).val(data_hide);
			jQuery(object).find('#page_nav_'+page_nav).hide();
			jQuery(object).find('#page_nav_arrow_'+page_nav).hide();			
			var last_show_page = jQuery('#last_show_page_'+form_id).val();
				last_show_page = parseInt(last_show_page) + parseInt(1);
			if(last_show_page > 1){
				jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
				jQuery(object).find('#page_nav_'+last_show_page).addClass('arf_page_last');
			}
			
			//for survey total page
			var max_page_no	= jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var total_page_number = max_page_no;				
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
					total_page_number = parseInt(total_page_number) - parseInt(1);				
				} else {
				}
			}
			total_page_number = parseInt(total_page_number) + parseInt(1);	
			jQuery(object).find('.total_survey_page').html(total_page_number);
			
			var current_i = 0;
			var current_page_number = jQuery(object).find('.current_survey_page').html();
				current_page_number = ( current_page_number !== undefined ) ? current_page_number : current_pages;
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
				} else {					
					current_i = parseInt(current_i) + parseInt(1);								
					if( current_pages == pi ){
						current_page_number = current_i; 
					}
				}
			}
			jQuery(object).find('.current_survey_page').html(current_page_number);
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_progressbar(object, current_page_number, total_page_number); // change progress bar
			}
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_pagenavigation(object, current_page_number, total_page_number); // change nav bar
			}
			
		}
		
	} else {
		
		if( f_type == 'submit' ){
			
			if( get_display == 'show' ){
				jQuery(object).find('.arf_submit_btn').attr('disabled', false);
				jQuery(object).find('.arf_submit_btn').removeClass('arfsubmitdisabled');
			} else if( get_display == 'hide' ){
				jQuery(object).find('.arf_submit_btn').attr('disabled', true);
				jQuery(object).find('.arf_submit_btn').addClass('arfsubmitdisabled');
			}
			
		} else if( f_type == 'divider' ){
			
			if( get_display == 'show' ){
				jQuery(object).find('#heading_'+f_id).removeClass('arf_error');
				jQuery(object).find('#heading_'+f_id).show();
			} else if( get_display == 'hide' ){
				jQuery(object).find('#heading_'+f_id).removeClass('arf_error');
				jQuery(object).find('#heading_'+f_id).hide();
			}
		
		} else {
			
			if( get_display == 'show' ){
				jQuery(object).find('#arf_field_'+f_id+'_container').removeClass('arf_error');
				jQuery(object).find('#arf_field_'+f_id+'_container').show();
			} else if( get_display == 'hide' ){
				jQuery(object).find('#arf_field_'+f_id+'_container').removeClass('arf_error');
				jQuery(object).find('#arf_field_'+f_id+'_container').hide();
			}
			
		}		
		
	}
	
}

function apply_default_field_bulk(object, f_id, get_display, f_type, page_no){
	if( f_type == 'break' ){
		
		var form_id = jQuery(object).find('input[name="form_id"]').val();
		
		var data_hide = jQuery('#get_hidden_pages_'+form_id).val();
		
		page_no  = page_no ? page_no : 1;
		page_nav = parseInt(page_no) + 1
		
		if( get_display == 'hide' ) {
			
			var data_string = '';
			//set aaray
			if(data_hide != '') {
				var data_arr = data_hide.split(',');
				
				if( jQuery.inArray( page_no, data_arr ) == -1){
									
				} else {
					data_hide = data_hide.replace(page_no+',', '');					
				}
				
			} else {
				data_hide = '';	
			}
			//store
			data_hide = ( data_hide =='' ) ? ',' : data_hide;
			
			//for first page
			var total_pages = jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var current_pages = jQuery(object).find('.page_break:visible').attr('id');
				current_pages = current_pages.replace('page_', '');		
			var page_page_no   = page_no;
			if( total_pages == 1 ){
				jQuery(object).find('#arf_submit_div_0').show();
				jQuery(object).find('#previous_last').show();	
				jQuery(object).find('#page_last').hide();
				jQuery(object).find('#submit_form_'+form_id).val('1');
			
			} else {
				
				if( total_pages == page_page_no && page_page_no == current_pages ){
					
					jQuery(object).find('#arf_submit_div_'+page_no).hide();
					jQuery(object).find('#previous_last').show();	
					jQuery(object).find('#page_last').show();
					jQuery(object).find('#page_last .arf_submit_div').show();
					jQuery(object).find('#submit_form_'+form_id).val('0');	
				
				} else {
					
					var default_hide_pages = data_hide;
					var page_page_no_new_i = page_page_no; 
					
					for(i=0; i <= total_pages; i++ ){
						if( default_hide_pages.indexOf(','+i+',') >= 0 ) {														
							continue;
						} else {
							page_page_no_new_i = i;
						}						
					}
					
					jQuery('#last_show_page_'+form_id).val(page_page_no_new_i);
					var page_prev_no = parseInt(page_page_no_new_i) - parseInt(1);
					
					if( page_page_no_new_i == current_pages && page_page_no_new_i == 0) {
						
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else if( page_page_no_new_i == current_pages ) {
						
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').show();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else {
						jQuery(object).find('#arf_submit_div_'+current_pages).show();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').hide();
						jQuery(object).find('#submit_form_'+form_id).val('1');	
					}
					
					
				}
			}
			//for first page
			
			// for paeg break
			var data_id = jQuery(object).find('#submit_form_'+form_id).attr('data-val');			
			var data_id = parseInt(data_id) - parseInt(1);
						
			jQuery('#get_hidden_pages_'+form_id).val(data_hide);
			jQuery(object).find('#page_nav_'+page_nav).show();
			jQuery(object).find('#page_nav_arrow_'+page_nav).show();
			var last_show_page = jQuery('#last_show_page_'+form_id).val();
				last_show_page = parseInt(last_show_page) + parseInt(1);
			if(last_show_page > 1){
				jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
				jQuery(object).find('#page_nav_'+last_show_page).addClass('arf_page_last');
			}
			
			//for survey total page
			var max_page_no	= jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var total_page_number = max_page_no;				
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
					total_page_number = parseInt(total_page_number) - parseInt(1);				
				} else {
				}
			}
			total_page_number = parseInt(total_page_number) + parseInt(1);	
			jQuery(object).find('.total_survey_page').html(total_page_number);
			
			var current_i = 0;
			var current_page_number = jQuery(object).find('.current_survey_page').html();
				current_page_number = ( current_page_number !== undefined ) ? current_page_number : current_pages;
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
				} else {					
					current_i = parseInt(current_i) + parseInt(1);								
					if( current_pages == pi ){
						current_page_number = current_i; 
					}
				}
			}
			jQuery(object).find('.current_survey_page').html(current_page_number);
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_progressbar(object, current_page_number, total_page_number); // change progress bar
			}
				
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_pagenavigation(object, current_page_number, total_page_number); // change nav bar
			}
			
			
		} else if( get_display == 'show' ) {
			if(data_hide != '') {
				var data_arr = data_hide.split(',');
				
				if( jQuery.inArray( page_no, data_arr ) == -1){
					data_hide = data_hide + page_no+',';
				}
				
			} else {
				data_hide = page_no+',';	
			}
			
			//for first page
			var total_pages = jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var current_pages = jQuery(object).find('.page_break:visible').attr('id');
				current_pages = current_pages.replace('page_', '');		
			var page_page_no   = page_no;
			
			if( total_pages == 1 ){
				jQuery(object).find('#arf_submit_div_0').hide();
				jQuery(object).find('#previous_last').hide();	
				jQuery(object).find('#page_last').show();
				jQuery(object).find('#page_last .arf_submit_div').show();
				jQuery(object).find('#submit_form_'+form_id).val('0');
			
			} else {
			 	
				if( total_pages == page_page_no && page_page_no == current_pages ){					
					jQuery(object).find('#arf_submit_div_'+page_no).show();
					jQuery(object).find('#previous_last').hide();	
					jQuery(object).find('#page_last').hide();
					jQuery(object).find('#submit_form_'+form_id).val('1');	
				
				} else {
					
					var default_hide_pages = data_hide;
					var page_page_no_new_i = page_page_no; 
					var total_hide = page_page_no;
					
					for(i=0; i <= total_pages; i++ ){
						if( default_hide_pages.indexOf(','+i+',') >= 0 ) {												
							continue;
						} else {
							page_page_no_new_i = i;
						}						
					}
					
					jQuery('#last_show_page_'+form_id).val(page_page_no_new_i);
					var page_prev_no = parseInt(page_page_no_new_i) - parseInt(1);
										
					if( page_page_no_new_i == current_pages && page_page_no_new_i == 0) {
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else if( page_page_no_new_i == current_pages ) {
						jQuery(object).find('#arf_submit_div_'+current_pages).hide();
						jQuery(object).find('#previous_last').show();	
						jQuery(object).find('#page_last').show();
						jQuery(object).find('#page_last .arf_submit_div').show();
						jQuery(object).find('#submit_form_'+form_id).val('0');						
					} else {
						jQuery(object).find('#arf_submit_div_'+current_pages).show();
						jQuery(object).find('#previous_last').hide();	
						jQuery(object).find('#page_last').hide();
						jQuery(object).find('#submit_form_'+form_id).val('1');	
					}
					
					
				}
			}
			//for first page
			
			jQuery('#get_hidden_pages_'+form_id).val(data_hide);	
			jQuery(object).find('#page_nav_'+page_nav).hide();
			jQuery(object).find('#page_nav_arrow_'+page_nav).hide();
			var last_show_page = jQuery('#last_show_page_'+form_id).val();
				last_show_page = parseInt(last_show_page) + parseInt(1);
			if(last_show_page > 1){
				jQuery(object).find('.page_break_nav').removeClass('arf_page_last');
				jQuery(object).find('#page_nav_'+last_show_page).addClass('arf_page_last');
			}
			
			//for survey total page
			var max_page_no	= jQuery(object).find('#submit_form_'+form_id).attr('data-max');
			var total_page_number = max_page_no;				
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
					total_page_number = parseInt(total_page_number) - parseInt(1);				
				} else {
				}
			}
			total_page_number = parseInt(total_page_number) + parseInt(1);	
			jQuery(object).find('.total_survey_page').html(total_page_number);
			
			var current_i = 0;
			var current_page_number = jQuery(object).find('.current_survey_page').html();
				current_page_number = ( current_page_number !== undefined ) ? current_page_number : current_pages;
			for(pi = 0; pi <= max_page_no; pi++){
				if( data_hide.indexOf(','+pi+',') >= 0 ){
				} else {					
					current_i = parseInt(current_i) + parseInt(1);								
					if( current_pages == pi ){
						current_page_number = current_i; 
					}
				}
			}
			jQuery(object).find('.current_survey_page').html(current_page_number);
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_progressbar(object, current_page_number, total_page_number); // change progress bar
			}
			
			if( current_page_number != '' && current_page_number != 'undefined' ){
				arf_change_pagenavigation(object, current_page_number, total_page_number); // change nav bar
			}
			
		}
		
	} else {
		
		if( f_type == 'submit' ){
			
			if( get_display == 'show' ){
				jQuery(object).find('.arf_submit_btn').attr('disabled', true);
				jQuery(object).find('.arf_submit_btn').addClass('arfsubmitdisabled');
			} else if( get_display == 'hide' ){
				jQuery(object).find('.arf_submit_btn').attr('disabled', false);
				jQuery(object).find('.arf_submit_btn').removeClass('arfsubmitdisabled');
			}
			
		} else if( f_type == 'divider' ){

			if( get_display == 'show' ) {
				jQuery(object).find('#heading_'+f_id).removeClass('arf_error');
				jQuery(object).find('#heading_'+f_id).hide();
			} else if( get_display == 'hide' ) {
				jQuery(object).find('#heading_'+f_id).removeClass('arf_error');
				jQuery(object).find('#heading_'+f_id).show();
			}
		
		} else {
			
			if( get_display == 'show' ) {
				jQuery(object).find('#arf_field_'+f_id+'_container').removeClass('arf_error');
				jQuery(object).find('#arf_field_'+f_id+'_container').hide();
			} else if( get_display == 'hide' ) {
				jQuery(object).find('#arf_field_'+f_id+'_container').removeClass('arf_error');
				jQuery(object).find('#arf_field_'+f_id+'_container').show();
			}
			
		}
		
	}
	
}
//bulk rules apply end

function arf_set_field_default_value(field_key, default_value, f_type, f_id, object )
{
	
	if( f_type == 'select' )
	{
		jQuery('#field_'+field_key).val( default_value );
		
		if( jQuery.isFunction( jQuery().selectpicker ) ){
			jQuery('#field_'+field_key).selectpicker('refresh');
		}
	}
	else if( f_type == 'radio' )
	{
		if( default_value != '' ){
			jQuery('input[name="item_meta[' +f_id+ ']"][value="' + default_value + '"]').attr('checked', 'checked');
		} else {
			jQuery('input[name="item_meta[' +f_id+ ']"]:checked').removeAttr('checked');
		}
		
		if( jQuery.isFunction( jQuery().iCheck ) ){
			jQuery('input[name="item_meta[' +f_id+ ']"]').iCheck('update');
		}
	}
	else if( f_type == 'checkbox' )
	{
		if( default_value == '' )
		{
			jQuery('input[name="item_meta[' +f_id+ '][]"]:checked').removeAttr('checked');
			if( jQuery.isFunction( jQuery().iCheck ) ){
				jQuery('input[name="item_meta[' +f_id+ '][]"]').iCheck('update');
			}
		}
		else
		{
			jQuery('input[name="item_meta[' +f_id+ '][]"]:checked').removeAttr('checked');
			for(k in default_value )
			{
				jQuery('input[name="item_meta[' +f_id+ '][]"][value="' + default_value[k] + '"]').attr('checked', 'checked');
			}
			if( jQuery.isFunction( jQuery().iCheck ) ){
				jQuery('input[name="item_meta[' +f_id+ '][]"]').iCheck('update');
			}
		}
	}
	else if( f_type == 'slider' )  
	{
		default_value = parseInt(default_value);	
		jQuery('#field_'+field_key+'_slide').slider('setValue', default_value);
		jQuery('#field_'+field_key).val(default_value);
	}
	else if( f_type == 'like' )
	{
		if( default_value == '1' )
		{
			jQuery('#field_'+field_key+'-0').attr('checked', true);
			jQuery('#like_'+field_key+'-0').addClass('active');
			jQuery('#field_'+field_key+'-1').attr('checked', false);
			jQuery('#like_'+field_key+'-1').removeClass('active');
		} 
		else if( default_value == '0' )
		{
			jQuery('#field_'+field_key+'-0').attr('checked', false);
			jQuery('#like_'+field_key+'-0').removeClass('active');
			jQuery('#field_'+field_key+'-1').attr('checked', true);
			jQuery('#like_'+field_key+'-1').addClass('active');
		}
		else
		{
			jQuery('#field_'+field_key+'-0').attr('checked', false);
			jQuery('#like_'+field_key+'-0').removeClass('active');
			jQuery('#field_'+field_key+'-1').attr('checked', false);
			jQuery('#like_'+field_key+'-1').removeClass('active');
		}		
	}
	else if( f_type == 'file' )
	{
		if( jQuery('#field_'+field_key).val() != '' )
		{
			var is_ajax_submission = jQuery('#form_submit_type').val() == 1 ? 1 : 0;
			if( is_ajax_submission ){
				jQuery('#remove_'+field_key).trigger('click');
			}
			
			jQuery('#field_'+field_key).val("");
		}
	}
	else if( f_type == 'time' )  
	{
		jQuery('#field_'+field_key).val('');
	}
	else if( f_type == 'scale' )  
	{
		jQuery('#field_'+field_key).val( default_value );
		set_votes(jQuery('#'+field_key), field_key);
	}
	else if( f_type != 'html' && f_type != 'captcha' )
	{
		jQuery('#field_'+field_key).val( default_value );	
	}
}
//---------- for conditional logic ----------//

function arf_change_pagenavigation(object, current_page_number, total_page_number)
{
	if( total_page_number == '' ){
		return;
	}
	var to_width = ( 100 / total_page_number );
		to_width = to_width.toFixed(3);
	jQuery(object).find('.arf_wizard .page_break_nav').css('width', to_width+'%');
}