function open_modal_box(id, height, width, checkradio_style_prop){
	jQuery(document).ready(function($)
	{
		jQuery(".vpb_captcha").keypress(function(){ 
			jQuery(this).parents('.arfformfield').find('.popover').remove();
			jQuery(this).removeClass('control-group');
			jQuery(this).removeClass('arf_error');
		});
	});
	
	var admin_ajax_url = jQuery('#admin_ajax_url').val();
	admin_ajax_url = is_ssl_replace(admin_ajax_url);
	jQuery.ajax({type:"POST",url:admin_ajax_url,data:"action=current_modal&position_modal=arf_modal_default",
	success:function(errObj){}
	});	
	
	var screenheight = jQuery(window).height();
	var screenwidth = jQuery(window).width();
	var modal_body_height = Number(height);
	var checkstep = 0;

	if((height) >= screenheight)
	{
		var tmp_height = Number(height) - Number(screenheight);
		checkstep = 1;
	}
	else
	{
		var tmp_height = Number(screenheight) - Number(height);	
		checkstep = 0;
	}
	
	if(checkstep!=0)
	{
		var total_height = 20;
		modal_body_height = screenheight - 55;
		jQuery('#popup-form-'+id).css('max-height', (screenheight - 40)+'px');
		jQuery('#popup-form-'+id).css('height', (screenheight - 40)+'px');
		jQuery('#popup-form-'+id+' .arfmodal-body').css('height', modal_body_height+'px');
	}
	else
	{
		var total_height = Number(tmp_height/2);
		jQuery('#popup-form-'+id).css('max-height', modal_body_height+'px');
		jQuery('#popup-form-'+id).css('height', modal_body_height+'px');
		jQuery('#popup-form-'+id+' .arfmodal-body').css('height', (modal_body_height-15)+'px');
	}

	var tmp_width = Number(screenwidth) - Number(width);
	var total_width = Number(tmp_width / 2);
	
	jQuery('#popup-form-'+id).css('top', total_height+'px');
	
	jQuery('#popup-form-'+id).css('left', total_width+'px');
	
	jQuery('#popup-form-'+id).css('data-mtop', total_height);
	jQuery('#popup-form-'+id).css('data-mleft', total_width);
	
	
		
	var form_key = jQuery('#form_key_'+id).val();
	jQuery('#popup-form-'+id).find('.arfmodal-body #form_'+form_key).show();
	jQuery('#popup-form-'+id).find('.arfmodal-body .arf_content_another_page').empty().hide();
								   
	jQuery('#popup-form-'+id).arfmodal({show:true});
	
	if(screenwidth <= 770)
	{	
		jQuery('#popup-form-'+id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height",'auto');
		
		var windowHeight = jQuery(window).height()- Number(60) ;
		var windowHeightOrg = jQuery(window).height();
		var actualheight = jQuery('#popup-form-'+id).find('.arf_fieldset').height();
		
		if(actualheight < windowHeight)
		{	
			jQuery('#popup-form-'+id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height",windowHeightOrg +"px");
		}
	}
	
	var data_open = jQuery('#popup-form-'+id).attr('data-open'); 
		data_open = ( data_open !== undefined ) ? data_open : true; 
	if(checkradio_style_prop!="" && data_open == true )
	{
		if( jQuery.isFunction( jQuery().iCheck ) )
		{
			jQuery('#arffrm_'+id+'_container input').not('.arf_hide_opacity').iCheck({
				checkboxClass: 'icheckbox_'+checkradio_style_prop,
				radioClass: 'iradio_'+checkradio_style_prop,
				increaseArea: '25%' // optional
			});
		}
		jQuery('#popup-form-'+id).attr('data-open', 'false'); 
	}
	
	// for colorpicker
	if( data_open == true )
	{
		arfmodalcolorpicker( id );
	}
	
	// for file upload
	var arfmainformurl = jQuery('#arfmainformurl').val();
		arfmainformurl = is_ssl_replace(arfmainformurl);
	var url = arfmainformurl+'/js/filedrag/filedrag_front.js';
	var submit_type = jQuery('#form_submit_type').val();
	if( submit_type == 1 ){
		jQuery.getScript(url);
	}
	jQuery('.original_normal').on('change', function(e){	   
		var id = jQuery(this).attr('id');
			id = id.replace('field_', '');
		
		var fileName = jQuery(this).val();	
		fileName = fileName.replace(/C:\\fakepath\\/i, '');	
		if( fileName != '' ){
			jQuery('#file_name_'+id).html(fileName);
		}
	});
	// for file upload end
	
	//for star rating
	jQuery('.rate_widget').each(function(i) {

		widget_id = jQuery(this).attr('id');
    
        jQuery('.ratings_stars').hover(

            function() {
				var color = jQuery(this).attr('data-color');
				var datasize = jQuery(this).attr('data-size');
				jQuery(this).prevAll().andSelf().addClass('ratings_over_'+color+datasize);
                jQuery(this).nextAll().removeClass('ratings_vote_'+color+datasize);
            },
            function() {
				var color = jQuery(this).attr('data-color');
				var datasize = jQuery(this).attr('data-size');
				jQuery(this).prevAll().andSelf().removeClass('ratings_over_'+color+datasize);
				widget_id_new = jQuery(this).parent().attr('id');
				set_votes(jQuery(this).parent(), widget_id_new);
            }
        );
                
        jQuery('.ratings_stars').bind('click', function() {
            var star = this;
            var widget = jQuery(this).parent();
            
            var clicked_data = {
                clicked_on : jQuery(star).attr('data-val'),
                widget_id : jQuery(star).parent().attr('id')
            };
			widget_id_new = jQuery(this).parent().attr('id');  
			
			jQuery('#field_'+widget_id_new).val(clicked_data.clicked_on);		
			jQuery('#field_'+widget_id_new).trigger('click');
            set_votes(widget, widget_id_new);
        });
        
    });
	//for star rating
	
	//for like button
	jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function() {
		var field 	= jQuery(this).attr('id');
		var	field 	= field.replace('like_', 'field_');
		if( !jQuery("#"+field).is(':checked') ){
			jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
		}
	});
	
	jQuery('.arf_form .arf_like').on("click", function() {
		var field 		= jQuery(this).attr('id');
			field_data 	= field.split('-'); 
		
		var field_id = field_data[0];
			field_id = field_id.replace('field_', '');
			field_id = 'like_'+field_id;
		var like	 = field_data[1];
		
		if( like == 1 ){
			jQuery('#'+field_id+'-0').removeClass('active');
			jQuery('#'+field_id+'-1').addClass('active');
		} else if( like == 0 ){
			jQuery('#'+field_id+'-1').removeClass('active');
			jQuery('#'+field_id+'-0').addClass('active');
		}	
	});
	jQuery('.arf_like_btn, .arf_dislike_btn').each(function(){
		var title = jQuery(this).attr('data-title');
		if( title !== undefined ){
			jQuery(this).popover({
				html: true,
				trigger: 'hover',
				placement: 'top',
				content: title,
				title: '',
				animation: false
			});
		}	
	});
	//for like button end
	
	arf_change_modal_slider( jQuery('#form_'+form_key) );		//for modal slider
}

function open_modal_box_fly_left(id, height, width, checkradio_style_prop)
{
	
	jQuery(document).ready(function($)
	{
		jQuery(".vpb_captcha").keypress(function(){
			jQuery(this).parents('.arfformfield').find('.popover').remove();
			jQuery(this).removeClass('control-group');
			jQuery(this).removeClass('arf_error');
		});
	});
	
	var admin_ajax_url = jQuery('#admin_ajax_url').val();
	admin_ajax_url = is_ssl_replace(admin_ajax_url);
	jQuery.ajax({type:"POST",url:admin_ajax_url,data:"action=current_modal&position_modal=arf_modal_left",
	success:function(errObj){}
	});	
	
	var screenheight = jQuery(window).height();
	var screenwidth = jQuery(window).width();
	var modal_body_height = Number(height);
	var checkstep = 0;

	if((height) >= screenheight)
	{
		var tmp_height = Number(height) - Number(screenheight);
		checkstep = 1;
	}
	else
	{
		var tmp_height = Number(screenheight) - Number(height);	
		checkstep = 0;
	}
	
	if(checkstep!=0)
	{
		var total_height = 20;
		modal_body_height = screenheight - 57;
		jQuery('#popup-form-'+id).css('max-height', (screenheight - 40)+'px');
		jQuery('#popup-form-'+id).css('height', (screenheight - 40)+'px');
		jQuery('#popup-form-'+id+' .arfmodal-body').css('height', modal_body_height+'px');
	}
	else
	{
		var total_height = Number(tmp_height/2);
		jQuery('#popup-form-'+id).css('max-height', modal_body_height+'px');
		jQuery('#popup-form-'+id).css('height', modal_body_height+'px');
		jQuery('#popup-form-'+id+' .arfmodal-body').css('height', (modal_body_height-15)+'px');
	}

	var tmp_width = Number(screenwidth) - Number(width);
	var total_width = Number(tmp_width / 2);
	
	jQuery('#popup-form-'+id).css('top',total_height);
	
	jQuery('#popup-form-'+id).css('data-mtop', total_height);
	jQuery('#popup-form-'+id).css('data-mleft', total_width);
	
	jQuery('.arform_side_block_left_'+id).hide();
	if (jQuery('#popup-form-'+id).hasClass('in'))
	{
		jQuery('#popup-form-'+id).removeClass('in');
		jQuery('.arform_side_block').show();
		jQuery('#popup-form-'+id).animate({
			'top': total_height+'px',
			'left': total_width+'px',									  
			'right': total_width+'px',
			'bottom': total_height+'px',
		},500);
	}
	else
	{
		jQuery('#popup-form-'+id).addClass('in');
		
		var form_key = jQuery('#form_key_'+id).val();
		jQuery('#popup-form-'+id).find('.arfmodal-body #form_'+form_key).show();
		jQuery('#popup-form-'+id).find('.arfmodal-body .arf_content_another_page').empty().hide();
	
		jQuery('#popup-form-'+id+' .arform_sb_fx_form').css('display', 'block');
		jQuery('#open_modal_box_fly_left_'+id).show();
		jQuery('#popup-form-'+id).animate({
			'top': total_height+'px',
			'left': total_width+'px',									  
			'right': total_width+'px',
			'bottom': total_height+'px',
		},500);
		jQuery('#popup-form-'+id).css('display', 'block');
		
		var data_open = jQuery('#popup-form-'+id).attr('data-open'); 
			data_open = ( data_open !== undefined ) ? data_open : true; 
		
		if(checkradio_style_prop!="" && data_open == true )
		{
			if( jQuery.isFunction( jQuery().iCheck ) )
			{
				jQuery('#arffrm_'+id+'_container input').not('.arf_hide_opacity').iCheck({
					checkboxClass: 'icheckbox_'+checkradio_style_prop,
					radioClass: 'iradio_'+checkradio_style_prop,
					increaseArea: '25%' // optional
				});
			}
			jQuery('#popup-form-'+id).attr('data-open', 'false');
		}
		
		// for colorpicker
		if( data_open == true )
		{
			arfmodalcolorpicker( id );
		}
	
		// for file upload
		var arfmainformurl = jQuery('#arfmainformurl').val();
			arfmainformurl = is_ssl_replace(arfmainformurl);
		var url = arfmainformurl+'/js/filedrag/filedrag_front.js';
		var submit_type = jQuery('#form_submit_type').val();
		if( submit_type == 1 ){
			jQuery.getScript(url);
		}
		jQuery('.original_normal').on('change', function(e){	   
			var id = jQuery(this).attr('id');
				id = id.replace('field_', '');
			
			var fileName = jQuery(this).val();	
			fileName = fileName.replace(/C:\\fakepath\\/i, '');	
			if( fileName != '' ){
				jQuery('#file_name_'+id).html(fileName);
			}
		});
		// for file upload end
		//for star rating
		jQuery('.rate_widget').each(function(i) {
	
			widget_id = jQuery(this).attr('id');
		
			jQuery('.ratings_stars').hover(
	
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().addClass('ratings_over_'+color+datasize);
					jQuery(this).nextAll().removeClass('ratings_vote_'+color+datasize);
				},
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().removeClass('ratings_over_'+color+datasize);
					widget_id_new = jQuery(this).parent().attr('id');
					set_votes(jQuery(this).parent(), widget_id_new);
				}
			);
					
			jQuery('.ratings_stars').bind('click', function() {
				var star = this;
				var widget = jQuery(this).parent();
				
				var clicked_data = {
					clicked_on : jQuery(star).attr('data-val'),
					widget_id : jQuery(star).parent().attr('id')
				};
				widget_id_new = jQuery(this).parent().attr('id');  
				
				jQuery('#field_'+widget_id_new).val(clicked_data.clicked_on);		
				jQuery('#field_'+widget_id_new).trigger('click');
				set_votes(widget, widget_id_new);
			});
			
		});
		//for star rating
		
		//for like button
		jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function() {
			var field 	= jQuery(this).attr('id');
			var	field 	= field.replace('like_', 'field_');
			if( !jQuery("#"+field).is(':checked') ){
				jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
			}
		});
		
		jQuery('.arf_form .arf_like').on("click", function() {
			var field 		= jQuery(this).attr('id');
				field_data 	= field.split('-'); 
			
			var field_id = field_data[0];
				field_id = field_id.replace('field_', '');
				field_id = 'like_'+field_id;
			var like	 = field_data[1];
			
			if( like == 1 ){
				jQuery('#'+field_id+'-0').removeClass('active');
				jQuery('#'+field_id+'-1').addClass('active');
			} else if( like == 0 ){
				jQuery('#'+field_id+'-1').removeClass('active');
				jQuery('#'+field_id+'-0').addClass('active');
			}	
		});
		jQuery('.arf_like_btn, .arf_dislike_btn').each(function(){
			var title = jQuery(this).attr('data-title');
			if( title !== undefined ){
				jQuery(this).popover({
					html: true,
					trigger: 'hover',
					placement: 'top',
					content: title,
					title: '',
					animation: false
				});
			}	
		});
		//for like button end
		
		arf_change_modal_slider( jQuery('#form_'+form_key) );		//for modal slider
		
	}
	
	if(screenwidth <= 770)
	{
		jQuery('#popup-form-'+id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height",'auto');
		
		var windowHeight = jQuery(window).height()- Number(60) ;
		var actualheight = jQuery('#popup-form-'+id).find('.arf_fieldset').height();
		
		if(actualheight < windowHeight){
			jQuery('#popup-form-'+id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height",windowHeight+"px");
		}
	}

}

function open_modal_box_fly_left_move(id, height, width)
{
	
	var modalwidth = jQuery(window).width();
	modalwidth = Number(modalwidth)+Number(20);
	
	var tmp_width = Number(modalwidth) - Number(width); 
	var total_width = Number(tmp_width / 2)-Number(50);
	
	jQuery('#popup-form-'+id).removeClass('in');
	
	var screenwidth = jQuery(window).width();
	if(screenwidth <= 770)
	{
		jQuery('#popup-form-'+id).hide();
	}
	else
	{
	

	jQuery('#popup-form-'+id).animate({
			'left':total_width+'px',
		},200, function(){ 
	});

	jQuery('#popup-form-'+id).animate({
			'left':modalwidth+'px',
		},500, function(){ 
	});
	}
	jQuery('.arform_side_block_right_'+id).show(800);
	jQuery('#open_modal_box_fly_right_'+id).show();
}

function open_modal_box_fly_right(id, height, width, checkradio_style_prop)
{
	jQuery(document).ready(function($)
	{
		jQuery(".vpb_captcha").keypress(function(){
			jQuery(this).parents('.arfformfield').find('.popover').remove();
			jQuery(this).removeClass('control-group');
			jQuery(this).removeClass('arf_error');
		});
	});
	
	var admin_ajax_url = jQuery('#admin_ajax_url').val();
	admin_ajax_url = is_ssl_replace(admin_ajax_url);
	jQuery.ajax({type:"POST",url:admin_ajax_url,data:"action=current_modal&position_modal=arf_modal_right",
	success:function(errObj){}
	});	
	
	
	var screenheight = jQuery(window).height();
	var screenwidth = jQuery(window).width();
	var modal_body_height = Number(height);
	var checkstep = 0;

	if(height >= screenheight)
	{
		var tmp_height = Number(height) - Number(screenheight);
		checkstep = 1;
	}
	else
	{
		var tmp_height = Number(screenheight) - Number(height);	
		checkstep = 0;
	}
	
	if(checkstep!=0)
	{
		var total_height = 20;
		modal_body_height = screenheight - 59;
		jQuery('#popup-form-'+id).css('max-height', (screenheight - 40)+'px');
		jQuery('#popup-form-'+id).css('height', (screenheight - 40)+'px');
		jQuery('#popup-form-'+id+' .arfmodal-body').css('height', modal_body_height+'px');
	}
	else
	{
		var total_height = Number(tmp_height/2);
		jQuery('#popup-form-'+id).css('max-height', modal_body_height+'px');
		jQuery('#popup-form-'+id).css('height', modal_body_height+'px');
		jQuery('#popup-form-'+id+' .arfmodal-body').css('height', (modal_body_height-19)+'px');
	}

	var tmp_width = Number(screenwidth) - Number(width);
	var total_width = Number(tmp_width / 2);
	
	jQuery('#popup-form-'+id).css('left',screenwidth);
	jQuery('#popup-form-'+id).css('top',total_height);
	
	jQuery('#popup-form-'+id).css('data-mtop', total_height);
	jQuery('#popup-form-'+id).css('data-mleft', total_width);
	
	jQuery('.arform_side_block_right_'+id).hide();
	if (jQuery('#popup-form-'+id).hasClass('in'))
	{
		jQuery('#popup-form-'+id).removeClass('in');
		jQuery('.arform_side_block').show();
		jQuery('#popup-form-'+id).animate({
			'right': total_width+'px',
			'bottom': total_height+'px',
			'top': total_height+'px',
			'left': total_width+'px',
		},500);
	}
	else
	{
		jQuery('#popup-form-'+id).addClass('in');
		
		var form_key = jQuery('#form_key_'+id).val();
		jQuery('#popup-form-'+id).find('.arfmodal-body #form_'+form_key).show();
		jQuery('#popup-form-'+id).find('.arfmodal-body .arf_content_another_page').empty().hide();
		
		jQuery('#popup-form-'+id+' .arform_sb_fx_form').css('display', 'block');
		jQuery('.arform_side_block').hide();
		jQuery('#popup-form-'+id).animate({
			'right': total_width+'px',
			'bottom': total_height+'px',
			'top': total_height+'px',
			'left': total_width+'px',
		},500);
		jQuery('#popup-form-'+id).css('display', 'block');
		
		var data_open = jQuery('#popup-form-'+id).attr('data-open'); 
			data_open = ( data_open !== undefined ) ? data_open : true; 
		if(checkradio_style_prop!="" && data_open == true )
		{
			if( jQuery.isFunction( jQuery().iCheck ) )
			{
				jQuery('#arffrm_'+id+'_container input').not('.arf_hide_opacity').iCheck({
					checkboxClass: 'icheckbox_'+checkradio_style_prop,
					radioClass: 'iradio_'+checkradio_style_prop,
					increaseArea: '25%' // optional
				});
			}
			jQuery('#popup-form-'+id).attr('data-open', 'false');
		}
		
		// for colorpicker
		if( data_open == true )
		{
			arfmodalcolorpicker( id );
		}
		
		// for file upload
		var arfmainformurl = jQuery('#arfmainformurl').val();
			arfmainformurl = is_ssl_replace(arfmainformurl);
		var url = arfmainformurl+'/js/filedrag/filedrag_front.js';
		var submit_type = jQuery('#form_submit_type').val();
		if( submit_type == 1 ){
			jQuery.getScript(url);
		}
		jQuery('.original_normal').on('change', function(e){	   
			var id = jQuery(this).attr('id');
				id = id.replace('field_', '');
			
			var fileName = jQuery(this).val();	
			fileName = fileName.replace(/C:\\fakepath\\/i, '');	
			if( fileName != '' ){
				jQuery('#file_name_'+id).html(fileName);
			}
		});
		// for file upload end
		//for star rating
		jQuery('.rate_widget').each(function(i) {
	
			widget_id = jQuery(this).attr('id');
		
			jQuery('.ratings_stars').hover(
	
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().addClass('ratings_over_'+color+datasize);
					jQuery(this).nextAll().removeClass('ratings_vote_'+color+datasize);
				},
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().removeClass('ratings_over_'+color+datasize);
					widget_id_new = jQuery(this).parent().attr('id');
					set_votes(jQuery(this).parent(), widget_id_new);
				}
			);
					
			jQuery('.ratings_stars').bind('click', function() {
				var star = this;
				var widget = jQuery(this).parent();
				
				var clicked_data = {
					clicked_on : jQuery(star).attr('data-val'),
					widget_id : jQuery(star).parent().attr('id')
				};
				widget_id_new = jQuery(this).parent().attr('id');  
				
				jQuery('#field_'+widget_id_new).val(clicked_data.clicked_on);		
				jQuery('#field_'+widget_id_new).trigger('click');
				set_votes(widget, widget_id_new);
			});
			
		});
		//for star rating
		
		//for like button
		jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function() {
			var field 	= jQuery(this).attr('id');
			var	field 	= field.replace('like_', 'field_');
			if( !jQuery("#"+field).is(':checked') ){
				jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
			}
		});
		
		jQuery('.arf_form .arf_like').on("click", function() {
			var field 		= jQuery(this).attr('id');
				field_data 	= field.split('-'); 
			
			var field_id = field_data[0];
				field_id = field_id.replace('field_', '');
				field_id = 'like_'+field_id;
			var like	 = field_data[1];
			
			if( like == 1 ){
				jQuery('#'+field_id+'-0').removeClass('active');
				jQuery('#'+field_id+'-1').addClass('active');
			} else if( like == 0 ){
				jQuery('#'+field_id+'-1').removeClass('active');
				jQuery('#'+field_id+'-0').addClass('active');
			}	
		});
		jQuery('.arf_like_btn, .arf_dislike_btn').each(function(){
			var title = jQuery(this).attr('data-title');
			if( title !== undefined ){
				jQuery(this).popover({
					html: true,
					trigger: 'hover',
					placement: 'top',
					content: title,
					title: '',
					animation: false
				});
			}	
		});
		//for like button end		
		
		arf_change_modal_slider( jQuery('#form_'+form_key) );		//for modal slider
	}
	
	if(screenwidth <= 770)
	{
		jQuery('#popup-form-'+id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height",'auto');
		
		var windowHeight = jQuery(window).height()- Number(60) ;
		var actualheight = jQuery('#popup-form-'+id).find('.arf_fieldset').height();
		
		if(actualheight < windowHeight){
			jQuery('#popup-form-'+id).find(".arf_form_outer_wrapper .arfshowmainform .allfields .arf_fieldset").css("height",windowHeight+"px");
		}
	}

}

function open_modal_box_fly_right_move(id, height, width) 
{
	
	var modalwidth = jQuery(window).width();
	modalwidth = Number(modalwidth)+Number(20);
	
	var tmp_width = Number(modalwidth) - Number(width); 
	var total_width = Number(tmp_width / 2)+Number(50);
	
	jQuery('#popup-form-'+id).removeClass('in');
	
	var screenwidth = jQuery(window).width();
	if(screenwidth <= 770)
	{
		jQuery('#popup-form-'+id).hide();
	}
	else
	{
	
	
		jQuery('#popup-form-'+id).animate({
			'left':total_width+'px',
		},200, function(){ 
		});
		
		jQuery('#popup-form-'+id).animate({
				'left': -modalwidth+'px',
				//'top': '100%',
			},800, function(){ 
		});
	}
	
	jQuery('.arform_side_block_left_'+id).show(800);
	jQuery('#open_modal_box_fly_right_'+id).show();
}

function open_modal_box_sitcky_bottom(id, height, width, checkradio_style_prop) 
{
	jQuery(document).ready(function($)
	{
		jQuery(".vpb_captcha").keypress(function(){
			jQuery(this).parents('.arfformfield').find('.popover').remove();
			jQuery(this).removeClass('control-group');
			jQuery(this).removeClass('arf_error');
		});
	});
	
	var admin_ajax_url = jQuery('#admin_ajax_url').val();
	admin_ajax_url = is_ssl_replace(admin_ajax_url);
	jQuery.ajax({type:"POST",url:admin_ajax_url,data:"action=current_modal&position_modal=arf_modal_bottom",
	success:function(errObj){}
	});	
	
	var modal_body_height = Number(height) - 140;
	var screenheight = jQuery(window).height();
	var newheightformainmodal = 0;
	if(height >= screenheight)
	{
		modal_body_height = screenheight - 55;	
		newheightformainmodal = screenheight - 55;
	}
	else
	{
		modal_body_height = Number(height) + 36;
		newheightformainmodal = Number(height);
	}
	jQuery('#popup-form-'+id).css('max-height', (newheightformainmodal)+'px');
	jQuery('#popup-form-'+id).css('height', (newheightformainmodal)+'px');
	
	/*jQuery('#popup-form-'+id).css('data-mtop', total_height);
	jQuery('#popup-form-'+id).css('data-mleft', total_width);*/
	
	jQuery('#popup-form-'+id+' .arfmodal-body').css('height', modal_body_height+'px');
	if (jQuery('.arform_bottom_fixed_block_bottom').hasClass('arform_bottom_fixed_block_open'))
	{
		jQuery('.arform_bottom_fixed_block_bottom').removeClass('arform_bottom_fixed_block_open');
	}
	else
	{
		var form_key = jQuery('#form_key_'+id).val();
		jQuery('#popup-form-'+id).find('.arfmodal-body #form_'+form_key).show();
		jQuery('#popup-form-'+id).find('.arfmodal-body .arf_content_another_page').empty().hide();
		
		jQuery('.arform_bottom_fixed_block_bottom').addClass('arform_bottom_fixed_block_open');
		
		var data_open = jQuery('#popup-form-'+id).attr('data-open'); 
			data_open = ( data_open !== undefined ) ? data_open : true;
		if(checkradio_style_prop!="" && data_open == true )
		{
			if( jQuery.isFunction( jQuery().iCheck ) )
			{
				jQuery('#arffrm_'+id+'_container input').not('.arf_hide_opacity').iCheck({
					checkboxClass: 'icheckbox_'+checkradio_style_prop,
					radioClass: 'iradio_'+checkradio_style_prop,
					increaseArea: '25%' // optional
				});
			}
			jQuery('#popup-form-'+id).attr('data-open', 'false'); 
		}
		
		// for colorpicker
		if( data_open == true )
		{
			arfmodalcolorpicker( id );
		}
		
		// for file upload
		var arfmainformurl = jQuery('#arfmainformurl').val();
			arfmainformurl = is_ssl_replace(arfmainformurl);
		var url = arfmainformurl+'/js/filedrag/filedrag_front.js';
		var submit_type = jQuery('#form_submit_type').val();
		if( submit_type == 1 ){
			jQuery.getScript(url);
		}
		jQuery('.original_normal').on('change', function(e){	   
			var id = jQuery(this).attr('id');
				id = id.replace('field_', '');
			
			var fileName = jQuery(this).val();	
			fileName = fileName.replace(/C:\\fakepath\\/i, '');	
			if( fileName != '' ){
				jQuery('#file_name_'+id).html(fileName);
			}
		});
		// for file upload end
		//for star rating
		jQuery('.rate_widget').each(function(i) {
	
			widget_id = jQuery(this).attr('id');
		
			jQuery('.ratings_stars').hover(
	
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().addClass('ratings_over_'+color+datasize);
					jQuery(this).nextAll().removeClass('ratings_vote_'+color+datasize);
				},
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().removeClass('ratings_over_'+color+datasize);
					widget_id_new = jQuery(this).parent().attr('id');
					set_votes(jQuery(this).parent(), widget_id_new);
				}
			);
					
			jQuery('.ratings_stars').bind('click', function() {
				var star = this;
				var widget = jQuery(this).parent();
				
				var clicked_data = {
					clicked_on : jQuery(star).attr('data-val'),
					widget_id : jQuery(star).parent().attr('id')
				};
				widget_id_new = jQuery(this).parent().attr('id');  
				
				jQuery('#field_'+widget_id_new).val(clicked_data.clicked_on);		
				jQuery('#field_'+widget_id_new).trigger('click');
				set_votes(widget, widget_id_new);
			});
			
		});
		//for star rating
		
		//for like button
		jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function() {
			var field 	= jQuery(this).attr('id');
			var	field 	= field.replace('like_', 'field_');
			if( !jQuery("#"+field).is(':checked') ){
				jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
			}
		});
		
		jQuery('.arf_form .arf_like').on("click", function() {
			var field 		= jQuery(this).attr('id');
				field_data 	= field.split('-'); 
			
			var field_id = field_data[0];
				field_id = field_id.replace('field_', '');
				field_id = 'like_'+field_id;
			var like	 = field_data[1];
			
			if( like == 1 ){
				jQuery('#'+field_id+'-0').removeClass('active');
				jQuery('#'+field_id+'-1').addClass('active');
			} else if( like == 0 ){
				jQuery('#'+field_id+'-1').removeClass('active');
				jQuery('#'+field_id+'-0').addClass('active');
			}	
		});
		jQuery('.arf_like_btn, .arf_dislike_btn').each(function(){
			var title = jQuery(this).attr('data-title');
			if( title !== undefined ){
				jQuery(this).popover({
					html: true,
					trigger: 'hover',
					placement: 'top',
					content: title,
					title: '',
					animation: false
				});
			}	
		});
		//for like button end
		
		arf_change_modal_slider( jQuery('#form_'+form_key) );		//for modal slider
	} 
	jQuery('.arform_bottom_fixed_block_bottom').parents('.arform_bottom_fixed_main_block_bottom').find('.arform_bottom_fixed_form_block_bottom_main').slideToggle("500");
}

function open_modal_box_sitcky_top(id, height, width, checkradio_style_prop) 
{
	jQuery(document).ready(function($)
	{
		jQuery(".vpb_captcha").keypress(function(){
			jQuery(this).parents('.arfformfield').find('.popover').remove();
			jQuery(this).removeClass('control-group');
			jQuery(this).removeClass('arf_error');
		});
	});
	
	var modal_body_height = Number(height) - 140;
	var screenheight = jQuery(window).height();
	var newheightformainmodal = 0;
	if(height >= screenheight)
	{
		modal_body_height = screenheight - 55;	
		newheightformainmodal = screenheight - 55;
	}
	else
	{
		modal_body_height = Number(height) + 36 - 26;
		newheightformainmodal = Number(height); // + 10;
	}
	jQuery('#popup-form-'+id).css('max-height', (newheightformainmodal)+'px');
	jQuery('#popup-form-'+id).css('height', (newheightformainmodal)+'px');
	jQuery('#popup-form-'+id).css('zIndex', '9998');
	jQuery('.arform_bottom_fixed_block_top').css('zIndex', '9999');
	jQuery('.arform_bottom_fixed_block_top').css('position', 'relative');
	
	/*jQuery('#popup-form-'+id).css('data-mtop', total_height);
	jQuery('#popup-form-'+id).css('data-mleft', total_width);*/
	
	var applycheckstyleprop = "";
	jQuery('#popup-form-'+id+' .arfmodal-body').css('height', modal_body_height+'px');
	if (jQuery('.arform_bottom_fixed_block_top').hasClass('arform_bottom_fixed_block_open'))
	{
		jQuery('.arform_bottom_fixed_block_top').removeClass('arform_bottom_fixed_block_open');
	}
	else
	{
		var form_key = jQuery('#form_key_'+id).val();
		jQuery('#popup-form-'+id).find('.arfmodal-body #form_'+form_key).show();
		jQuery('#popup-form-'+id).find('.arfmodal-body .arf_content_another_page').empty().hide();
		
		jQuery('.arform_bottom_fixed_block_top').addClass('arform_bottom_fixed_block_open');
		// for file upload
		var arfmainformurl = jQuery('#arfmainformurl').val();
			arfmainformurl = is_ssl_replace(arfmainformurl);
		var url = arfmainformurl+'/js/filedrag/filedrag_front.js';
		var submit_type = jQuery('#form_submit_type').val();
		if( submit_type == 1 ){
			jQuery.getScript(url);
		}
		
		jQuery('.original_normal').on('change', function(e){	   
			var id = jQuery(this).attr('id');
				id = id.replace('field_', '');
			
			var fileName = jQuery(this).val();	
			fileName = fileName.replace(/C:\\fakepath\\/i, '');	
			if( fileName != '' ){
				jQuery('#file_name_'+id).html(fileName);
			}
		});
		// for file upload end	
		//for star rating
		jQuery('.rate_widget').each(function(i) {
	
			widget_id = jQuery(this).attr('id');
		
			jQuery('.ratings_stars').hover(
	
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().addClass('ratings_over_'+color+datasize);
					jQuery(this).nextAll().removeClass('ratings_vote_'+color+datasize);
				},
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().removeClass('ratings_over_'+color+datasize);
					widget_id_new = jQuery(this).parent().attr('id');
					set_votes(jQuery(this).parent(), widget_id_new);
				}
			);
					
			jQuery('.ratings_stars').bind('click', function() {
				var star = this;
				var widget = jQuery(this).parent();
				
				var clicked_data = {
					clicked_on : jQuery(star).attr('data-val'),
					widget_id : jQuery(star).parent().attr('id')
				};
				widget_id_new = jQuery(this).parent().attr('id');  
				
				jQuery('#field_'+widget_id_new).val(clicked_data.clicked_on);		
				jQuery('#field_'+widget_id_new).trigger('click');
				set_votes(widget, widget_id_new);
			});
			
		});
		applycheckstyleprop = 1;
		//for star rating
		
		//for like button
		jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function() {
			var field 	= jQuery(this).attr('id');
			var	field 	= field.replace('like_', 'field_');
			if( !jQuery("#"+field).is(':checked') ){
				jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
			}
		});
		
		jQuery('.arf_form .arf_like').on("click", function() {
			var field 		= jQuery(this).attr('id');
				field_data 	= field.split('-'); 
			
			var field_id = field_data[0];
				field_id = field_id.replace('field_', '');
				field_id = 'like_'+field_id;
			var like	 = field_data[1];
			
			if( like == 1 ){
				jQuery('#'+field_id+'-0').removeClass('active');
				jQuery('#'+field_id+'-1').addClass('active');
			} else if( like == 0 ){
				jQuery('#'+field_id+'-1').removeClass('active');
				jQuery('#'+field_id+'-0').addClass('active');
			}	
		});
		jQuery('.arf_like_btn, .arf_dislike_btn').each(function(){
			var title = jQuery(this).attr('data-title');
			if( title !== undefined ){
				jQuery(this).popover({
					html: true,
					trigger: 'hover',
					placement: 'top',
					content: title,
					title: '',
					animation: false
				});
			}	
		});
		//for like button end
		
		arf_change_modal_slider( jQuery('#form_'+form_key) );		//for modal slider
		
	} 
	jQuery('.arform_bottom_fixed_block_top').parents('.arform_bottom_fixed_main_block_top').find('.arform_bottom_fixed_form_block_top_main').slideToggle("500");
	if(applycheckstyleprop==1)
	{
		var data_open = jQuery('#popup-form-'+id).attr('data-open'); 
			data_open = ( data_open !== undefined ) ? data_open : true;
			
		if(checkradio_style_prop!="" && data_open == true )
		{
			if( jQuery.isFunction( jQuery().iCheck ) )
			{
				jQuery('#arffrm_'+id+'_container input').not('.arf_hide_opacity').iCheck({
					checkboxClass: 'icheckbox_'+checkradio_style_prop,
					radioClass: 'iradio_'+checkradio_style_prop,
					increaseArea: '30%' // optional
				});
			}
			jQuery('#popup-form-'+id).attr('data-open', 'false'); 
		}
		
		// for colorpicker
		if( data_open == true )
		{
			arfmodalcolorpicker( id );
		}
		
	}
}

function arfmodalcolorpicker( id )
{
	if( ! id ){
		return;
	}
	
	jQuery('.arfhiddencolor').on('focus', function(){
		jQuery(this).parents('.arf_colorpicker_control').first().find('.arf_colorpicker, .arf_basic_colorpicker').first().trigger('click');
	});
	
	if( jQuery.isFunction( jQuery().colpick ) )
	{
		jQuery('#arffrm_'+id+'_container .arf_colorpicker').colpick({
			layout:'hex',
			submit:0,
			color: 'ffffff',
			onBeforeShow:function(){		
				var fid 	= jQuery(this).attr('id');
				var	fid		= fid.replace('arfcolorpicker_', '');
				var color 	= jQuery('#field_'+fid).val();
				var	new_color= color.replace('#','');
				if( new_color ){
					jQuery(this).colpickSetColor(new_color);
				}
			},
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				var field_key 	= jQuery(el).attr('id');
					field_key	= field_key.replace('arfcolorpicker_', '');
				jQuery('#field_'+field_key).val('#'+hex).trigger('change');
				jQuery(el).find('.arfcolorvalue').text('#'+hex);
				jQuery(el).find('.arfcolorvalue').css('background', '#'+hex);
				var arffontcolor	= HextoHsl(hex) > 0.5 ? '#000000' : '#ffffff';
				jQuery(el).find('.arfcolorvalue').css('color', arffontcolor);
			}
		});
	}
	
	if( jQuery.isFunction( jQuery().simpleColorPicker ) )
	{
		jQuery('.arf_basic_colorpicker').simpleColorPicker({
			onChangeColor: function(color){
				var field_key 	= jQuery(this).attr('id');
					field_key	= field_key.replace('arfcolorpicker_', '');
				jQuery('#field_'+field_key).val(color).trigger('change');
				jQuery(this).find('.arfcolorvalue').text(color);
				jQuery(this).find('.arfcolorvalue').css('background', color);
				var hex 	= color.replace('#', ''); 
				var arffontcolor	= HextoHsl(hex) > 0.5 ? '#000000' : '#ffffff';
				if(hex=="ffff00")
				{
					arffontcolor = "#000000";
				}
				jQuery(this).find('.arfcolorvalue').css('color', arffontcolor);
			}
		});
	}
	
	jQuery('#popup-form-'+id).attr('data-open', 'false'); 	
}




/**02-02-2015**/
function open_modal_box_sitcky_left(id, height, width, checkradio_style_prop) 
{
	jQuery(document).ready(function($)
	{
		jQuery(".vpb_captcha").keypress(function(){
			jQuery(this).parents('.arfformfield').find('.popover').remove();
			jQuery(this).removeClass('control-group');
			jQuery(this).removeClass('arf_error');
		});
		
	});
	
	var admin_ajax_url = jQuery('#admin_ajax_url').val();
	admin_ajax_url = is_ssl_replace(admin_ajax_url);
	jQuery.ajax({type:"POST",url:admin_ajax_url,data:"action=current_modal&position_modal=arf_modal_sitcky_left",
	success:function(errObj){}
	});	
	
	
	
	var modal_body_height = Number(height) - 140;
	var screenheight = jQuery(window).height();
	var newheightformainmodal = 0;
	if(height >= screenheight)
	{
		modal_body_height = screenheight - 55;	
		newheightformainmodal = screenheight - 55;
	}
	else
	{
		modal_body_height = Number(height);
		newheightformainmodal = Number(height);
	}
	
	jQuery('#popup-form-'+id).css('max-height', (newheightformainmodal)+'px');
	jQuery('#popup-form-'+id).css('height', (newheightformainmodal)+'px');
	
	/*jQuery('#popup-form-'+id).css('data-mtop', total_height);
	jQuery('#popup-form-'+id).css('data-mleft', total_width);*/
	
	
	
	jQuery('#popup-form-'+id+' .arfmodal-body').css('height', modal_body_height+'px');
	
	
	
	if (jQuery('.arform_bottom_fixed_block_left').hasClass('arform_bottom_fixed_block_open'))
	{
		jQuery('.arform_bottom_fixed_block_left').removeClass('arform_bottom_fixed_block_open');
		//jQuery('.arform_bottom_fixed_block_left').css('margin-left','0');
		jQuery('.arform_bottom_fixed_block_left').animate({'margin-left': '0'}, 500);
		jQuery('.arform_bottom_fixed_form_block_left_main').animate({'margin-left': '-'+width+'px'}, 500);
	}
	else
	{
		jQuery('.arform_bottom_fixed_form_block_left_main').animate({'margin-left': '0'}, 500);
		
		
		//jQuery('.arform_bottom_fixed_block_left').css('margin-left',width+'px');
		jQuery('.arform_bottom_fixed_block_left').animate({'margin-left': width+'px'}, 500);
		 
		
		var form_key = jQuery('#form_key_'+id).val();
		jQuery('#popup-form-'+id).find('.arfmodal-body #form_'+form_key).show();
		jQuery('#popup-form-'+id).find('.arfmodal-body .arf_content_another_page').empty().hide();
		
		jQuery('.arform_bottom_fixed_block_left').addClass('arform_bottom_fixed_block_open');
		
		var data_open = jQuery('#popup-form-'+id).attr('data-open'); 
			data_open = ( data_open !== undefined ) ? data_open : true;
		if(checkradio_style_prop!="" && data_open == true )
		{
			if( jQuery.isFunction( jQuery().iCheck ) )
			{
				jQuery('#arffrm_'+id+'_container input').not('.arf_hide_opacity').iCheck({
					checkboxClass: 'icheckbox_'+checkradio_style_prop,
					radioClass: 'iradio_'+checkradio_style_prop,
					increaseArea: '25%' // optional
				});
			}
			jQuery('#popup-form-'+id).attr('data-open', 'false'); 
		}
		
		// for colorpicker
		if( data_open == true )
		{
			arfmodalcolorpicker( id );
		}
		
		// for file upload
		var arfmainformurl = jQuery('#arfmainformurl').val();
			arfmainformurl = is_ssl_replace(arfmainformurl);
		var url = arfmainformurl+'/js/filedrag/filedrag_front.js';
		var submit_type = jQuery('#form_submit_type').val();
		if( submit_type == 1 ){
			jQuery.getScript(url);
		}
		jQuery('.original_normal').on('change', function(e){	   
			var id = jQuery(this).attr('id');
				id = id.replace('field_', '');
			
			var fileName = jQuery(this).val();	
			fileName = fileName.replace(/C:\\fakepath\\/i, '');	
			if( fileName != '' ){
				jQuery('#file_name_'+id).html(fileName);
			}
		});
		// for file upload end
		//for star rating
		jQuery('.rate_widget').each(function(i) {
	
			widget_id = jQuery(this).attr('id');
		
			jQuery('.ratings_stars').hover(
	
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().addClass('ratings_over_'+color+datasize);
					jQuery(this).nextAll().removeClass('ratings_vote_'+color+datasize);
				},
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().removeClass('ratings_over_'+color+datasize);
					widget_id_new = jQuery(this).parent().attr('id');
					set_votes(jQuery(this).parent(), widget_id_new);
				}
			);
					
			jQuery('.ratings_stars').bind('click', function() {
				var star = this;
				var widget = jQuery(this).parent();
				
				var clicked_data = {
					clicked_on : jQuery(star).attr('data-val'),
					widget_id : jQuery(star).parent().attr('id')
				};
				widget_id_new = jQuery(this).parent().attr('id');  
				
				jQuery('#field_'+widget_id_new).val(clicked_data.clicked_on);		
				jQuery('#field_'+widget_id_new).trigger('click');
				set_votes(widget, widget_id_new);
			});
			
		});
		//for star rating
		
		//for like button
		jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function() {
			var field 	= jQuery(this).attr('id');
			var	field 	= field.replace('like_', 'field_');
			if( !jQuery("#"+field).is(':checked') ){
				jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
			}
		});
		
		jQuery('.arf_form .arf_like').on("click", function() {
			var field 		= jQuery(this).attr('id');
				field_data 	= field.split('-'); 
			
			var field_id = field_data[0];
				field_id = field_id.replace('field_', '');
				field_id = 'like_'+field_id;
			var like	 = field_data[1];
			
			if( like == 1 ){
				jQuery('#'+field_id+'-0').removeClass('active');
				jQuery('#'+field_id+'-1').addClass('active');
			} else if( like == 0 ){
				jQuery('#'+field_id+'-1').removeClass('active');
				jQuery('#'+field_id+'-0').addClass('active');
			}	
		});
		jQuery('.arf_like_btn, .arf_dislike_btn').each(function(){
			var title = jQuery(this).attr('data-title');
			if( title !== undefined ){
				jQuery(this).popover({
					html: true,
					trigger: 'hover',
					placement: 'top',
					content: title,
					title: '',
					animation: false
				});
			}	
		});
		//for like button end
		
		arf_change_modal_slider( jQuery('#form_'+form_key) );		//for modal slider
	
	}
	

	/*jQuery('.arform_bottom_fixed_form_block_left_main').css('margin-left','0');*/
	
	var options1 = { direction:'left'};
	//jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').show('slow');
	//jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').fadeIn();
	
	//jQuery('.arform_bottom_fixed_form_block_left_main').effect('slide', options1, 5000);
	
	//jQuery('.arform_bottom_fixed_form_block_left_main').toggle('slide', options1, 5000);
	
	//jQuery('.arform_bottom_fixed_form_block_left_main').animate({"left":"800px"}, "slow");
	
	//jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').toggle('slide', options1, 5000);
	
	
	
	//jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').toggle('slide', options1, 500);
	

}


function open_modal_box_sitcky_right(id, height, width, checkradio_style_prop) 
{
	jQuery(document).ready(function($)
	{
		jQuery(".vpb_captcha").keypress(function(){
			jQuery(this).parents('.arfformfield').find('.popover').remove();
			jQuery(this).removeClass('control-group');
			jQuery(this).removeClass('arf_error');
		});
	});
	
	var admin_ajax_url = jQuery('#admin_ajax_url').val();
	admin_ajax_url = is_ssl_replace(admin_ajax_url);
	jQuery.ajax({type:"POST",url:admin_ajax_url,data:"action=current_modal&position_modal=arf_modal_bottom",
	success:function(errObj){}
	});	
	
	var modal_body_height = Number(height) - 140;
	var screenheight = jQuery(window).height();
	var newheightformainmodal = 0;
	if(height >= screenheight)
	{
		modal_body_height = screenheight - 55;	
		newheightformainmodal = screenheight - 55;
	}
	else
	{
		modal_body_height = Number(height);
		newheightformainmodal = Number(height);
	}
	jQuery('#popup-form-'+id).css('max-height', (newheightformainmodal)+'px');
	jQuery('#popup-form-'+id).css('height', (newheightformainmodal)+'px');
	
	/*jQuery('#popup-form-'+id).css('data-mtop', total_height);
	jQuery('#popup-form-'+id).css('data-mleft', total_width);*/
	
	jQuery('#popup-form-'+id+' .arfmodal-body').css('height', modal_body_height+'px');
	if (jQuery('.arform_bottom_fixed_block_right').hasClass('arform_bottom_fixed_block_open'))
	{
		jQuery('.arform_bottom_fixed_block_right').removeClass('arform_bottom_fixed_block_open');
		
		//jQuery('.arform_bottom_fixed_block_right').css('margin-right','0');
		jQuery('.arform_bottom_fixed_block_right').animate({'margin-right': '0'}, 500);
		jQuery('.arform_bottom_fixed_form_block_right_main').animate({'margin-right': '-'+width+'px'}, 500);
	}
	else
	{	
		jQuery('.arform_bottom_fixed_form_block_right_main').animate({'margin-right': '0'}, 500);
		//jQuery('.arform_bottom_fixed_block_right').css('margin-right',width+'px');
		jQuery('.arform_bottom_fixed_block_right').animate({'margin-right': width+'px'}, 500);
		
		var form_key = jQuery('#form_key_'+id).val();
		jQuery('#popup-form-'+id).find('.arfmodal-body #form_'+form_key).show();
		jQuery('#popup-form-'+id).find('.arfmodal-body .arf_content_another_page').empty().hide();
		
		jQuery('.arform_bottom_fixed_block_right').addClass('arform_bottom_fixed_block_open');
		
		var data_open = jQuery('#popup-form-'+id).attr('data-open'); 
			data_open = ( data_open !== undefined ) ? data_open : true;
		if(checkradio_style_prop!="" && data_open == true )
		{
			if( jQuery.isFunction( jQuery().iCheck ) )
			{
				jQuery('#arffrm_'+id+'_container input').not('.arf_hide_opacity').iCheck({
					checkboxClass: 'icheckbox_'+checkradio_style_prop,
					radioClass: 'iradio_'+checkradio_style_prop,
					increaseArea: '25%' // optional
				});
			}
			jQuery('#popup-form-'+id).attr('data-open', 'false'); 
		}
		
		// for colorpicker
		if( data_open == true )
		{
			arfmodalcolorpicker( id );
		}
		
		// for file upload
		var arfmainformurl = jQuery('#arfmainformurl').val();
			arfmainformurl = is_ssl_replace(arfmainformurl);
		var url = arfmainformurl+'/js/filedrag/filedrag_front.js';
		var submit_type = jQuery('#form_submit_type').val();
		if( submit_type == 1 ){
			jQuery.getScript(url);
		}
		
		jQuery('.original_normal').on('change', function(e){	   
			var id = jQuery(this).attr('id');
				id = id.replace('field_', '');
			
			var fileName = jQuery(this).val();	
			fileName = fileName.replace(/C:\\fakepath\\/i, '');	
			if( fileName != '' ){
				jQuery('#file_name_'+id).html(fileName);
			}
		});
		// for file upload end
		//for star rating
		jQuery('.rate_widget').each(function(i) {
	
			widget_id = jQuery(this).attr('id');
		
			jQuery('.ratings_stars').hover(
	
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().addClass('ratings_over_'+color+datasize);
					jQuery(this).nextAll().removeClass('ratings_vote_'+color+datasize);
				},
				function() {
					var color = jQuery(this).attr('data-color');
					var datasize = jQuery(this).attr('data-size');
					jQuery(this).prevAll().andSelf().removeClass('ratings_over_'+color+datasize);
					widget_id_new = jQuery(this).parent().attr('id');
					set_votes(jQuery(this).parent(), widget_id_new);
				}
			);
					
			jQuery('.ratings_stars').bind('click', function() {
				var star = this;
				var widget = jQuery(this).parent();
				
				var clicked_data = {
					clicked_on : jQuery(star).attr('data-val'),
					widget_id : jQuery(star).parent().attr('id')
				};
				widget_id_new = jQuery(this).parent().attr('id');  
				
				jQuery('#field_'+widget_id_new).val(clicked_data.clicked_on);		
				jQuery('#field_'+widget_id_new).trigger('click');
				set_votes(widget, widget_id_new);
			});
			
		});
		//for star rating
		
		//for like button
		jQuery('.arf_like_btn, .arf_dislike_btn').not('.field_edit').on("click", function() {
			var field 	= jQuery(this).attr('id');
			var	field 	= field.replace('like_', 'field_');
			if( !jQuery("#"+field).is(':checked') ){
				jQuery("#" + jQuery(this).attr("for")).trigger('click').trigger('change');
			}
		});
		
		jQuery('.arf_form .arf_like').on("click", function() {
			var field 		= jQuery(this).attr('id');
				field_data 	= field.split('-'); 
			
			var field_id = field_data[0];
				field_id = field_id.replace('field_', '');
				field_id = 'like_'+field_id;
			var like	 = field_data[1];
			
			if( like == 1 ){
				jQuery('#'+field_id+'-0').removeClass('active');
				jQuery('#'+field_id+'-1').addClass('active');
			} else if( like == 0 ){
				jQuery('#'+field_id+'-1').removeClass('active');
				jQuery('#'+field_id+'-0').addClass('active');
			}	
		});
		jQuery('.arf_like_btn, .arf_dislike_btn').each(function(){
			var title = jQuery(this).attr('data-title');
			if( title !== undefined ){
				jQuery(this).popover({
					html: true,
					trigger: 'hover',
					placement: 'top',
					content: title,
					title: '',
					animation: false
				});
			}	
		});
		//for like button end
		
		arf_change_modal_slider( jQuery('#form_'+form_key) );		//for modal slider
	} 
	
	//jQuery('.arform_bottom_fixed_block_left').parents('.arform_bottom_fixed_main_block_left').find('.arform_bottom_fixed_form_block_left_main').slideToggle("500");
	
	var options_right = { direction:'right'};
	
	//jQuery('.arform_bottom_fixed_block_right').parents('.arform_bottom_fixed_main_block_right').find('.arform_bottom_fixed_form_block_right_main').toggle('slide', options_right, 500);
	
}