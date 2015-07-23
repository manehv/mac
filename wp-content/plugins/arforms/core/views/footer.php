<?php
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7.3
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/
?>
<script type="text/javascript">


var __ARFPAGELABELTEXT = "<?php echo addslashes(__('Page Label', 'ARForms')); ?>";
var __ARFSECONDPAGELABEL = "<?php echo addslashes(__('Second Page Label', 'ARForms')); ?>";
var __ARFPAGELABELARRAY = new Array("","<?php echo addslashes(__('First', 'ARForms')); ?>", "<?php echo addslashes(__('Second', 'ARForms')); ?>", 
									"<?php echo addslashes(__('Third', 'ARForms')); ?>", "<?php echo addslashes(__('Fourth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Fifth', 'ARForms')); ?>","<?php echo addslashes(__('Sixth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Seventh', 'ARForms')); ?>","<?php echo addslashes(__('Eighth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Ninth', 'ARForms')); ?>","<?php echo addslashes(__('Tenth', 'ARForms')); ?>",
									
									"<?php echo addslashes(__('Eleventh', 'ARForms')); ?>","<?php echo addslashes(__('Twelfth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Thirteenth', 'ARForms')); ?>","<?php echo addslashes(__('Fourteenth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Fifteenth', 'ARForms')); ?>","<?php echo addslashes(__('Sixteenth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Seventeenth', 'ARForms')); ?>","<?php echo addslashes(__('Eighteenth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Nineteenth', 'ARForms')); ?>","<?php echo addslashes(__('Twentieth', 'ARForms')); ?>",
									
									"<?php echo addslashes(__('Twenty First', 'ARForms')); ?>","<?php echo addslashes(__('Twenty Second', 'ARForms')); ?>",
									"<?php echo addslashes(__('Twenty Third', 'ARForms')); ?>","<?php echo addslashes(__('Twenty Fourth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Twenty Fifth', 'ARForms')); ?>","<?php echo addslashes(__('Twenty Sixth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Twenty Seventh', 'ARForms')); ?>","<?php echo addslashes(__('Twenty Eighth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Twenty Ninth', 'ARForms')); ?>","<?php echo addslashes(__('Thirtieth', 'ARForms')); ?>",
									
									"<?php echo addslashes(__('Thirty First', 'ARForms')); ?>","<?php echo addslashes(__('Thirty Second', 'ARForms')); ?>",
									"<?php echo addslashes(__('Thirty Third', 'ARForms')); ?>","<?php echo addslashes(__('Thirty Fourth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Thirty Fifth', 'ARForms')); ?>","<?php echo addslashes(__('Thirty Sixth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Thirty Seventh', 'ARForms')); ?>","<?php echo addslashes(__('Thirty Eighth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Thirty Ninth', 'ARForms')); ?>","<?php echo addslashes(__('Fortieth', 'ARForms')); ?>",
									
									"<?php echo addslashes(__('Forty First', 'ARForms')); ?>","<?php echo addslashes(__('Forty Second', 'ARForms')); ?>",
									"<?php echo addslashes(__('Forty Third', 'ARForms')); ?>","<?php echo addslashes(__('Forty Fourth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Forty Fifth', 'ARForms')); ?>","<?php echo addslashes(__('Forty Sixth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Forty Seventh', 'ARForms')); ?>","<?php echo addslashes(__('Forty Eighth', 'ARForms')); ?>",
									"<?php echo addslashes(__('Forty Ninth', 'ARForms')); ?>","<?php echo addslashes(__('Fiftieth', 'ARForms')); ?>"
); 

__ARFMAINURL='<?php echo $arfajaxurl ?>';

__ARFDEFAULTTITLE="<?php echo addslashes(__('(Click here to add text)', 'ARForms')); ?>";

__ARFDEFAULTDESCRIPTION="<?php echo addslashes(__('(Click here to add description or instructions)', 'ARForms')); ?>";

__ARFDEFAULTSECTION="<?php echo addslashes(__('(Blank Section)', 'ARForms')); ?>";

__ARFDELETEURL='<?php echo admin_url('admin.php?page=ARForms&err=1'); ?>';	


__ARFINVALID = '<?php global $arfsettings; echo $arfsettings->blank_msg; ?>';		

//---------- for conditional logic ----------//
__ARFEQUALS		= '<?php echo addslashes(__('equals', 'ARForms')); ?>';
__ARFNOTEQUALS 	= '<?php echo addslashes(__('not equals', 'ARForms')); ?>';
__ARFGREATER	= '<?php echo addslashes(__('greater than', 'ARForms')); ?>';
__ARFLESS 		= '<?php echo addslashes(__('less than', 'ARForms')); ?>';
__ARFCONTAIN 	= '<?php echo addslashes(__('contains', 'ARForms')); ?>';
__ARFNOTCONTAIN	= '<?php echo addslashes(__('not contains', 'ARForms')); ?>';
__ARFADDRULE	= '<?php echo addslashes(__('Please add one or more rules', 'ARForms')); ?>';
//---------- for conditional logic ----------//

var mycolsize = "1col";

jQuery(document).ready(function($){

$("#new_fields").sortable({

    placeholder:'sortable-placeholder',cursor:'move',opacity:0.65,distance:1,

    cancel:'.mylastli,.blankli1col,.blankli2col,.blankli32col,.blankli33col,.widget,.arffieldoptionslist,input,textarea,select, .main_fieldoptions_modal',

    accepts:'field_type_list',revert:false,forcePlaceholderSize:true,tolerance:"pointer",cursorAt:{ left: 5, top: 40 },
	
	start: function( event, ui ) {
	
		(ui.item).attr('data-startPos', (ui.item).index() );
		(ui.item).attr('data-theight', (ui.item).innerHeight() - 14 );
		(ui.item).attr('data-tclass', (ui.item).attr('class') );
						
		jQuery('.show-field-options').hide();
		
		$('#new_fields').attr("is_resized","1col");
		 
		var mywidth = jQuery('#new_fields').width();
		
		(ui.item).css('background-color','#f3f6f9');
		
		var order= $('#new_fields').sortable("toArray");

		if( (ui.item).hasClass('arf2columns') || (ui.item).hasClass('arf3columns') ){
			ui.placeholder.height( (ui.item).innerHeight() - 14 );
		}else{
			ui.placeholder.height( 110 );		
		}
		if( (ui.item).hasClass('arf3columns') )
		{
			ui.placeholder.width( (mywidth / 3) - 70); 
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).width((mywidth / 3) - 70);
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
		}
		else if( (ui.item).hasClass('arf2columns') )
		{
			ui.placeholder.width( (mywidth / 2) - 50); 
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).width((mywidth / 2) - 50);
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
		}
		else
		{
			ui.placeholder.width( mywidth - 10);
  			ui.placeholder.css('clear', 'both');
			ui.placeholder.css('float', 'none');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).css('clear','both');
			(ui.item).css('float','none');
			(ui.item).width( mywidth - 10);
			
		}
		
		if( (ui.item).hasClass('arf1columns') && (ui.item).prev().hasClass('blankli1col') )
		{
			(ui.item).prev().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}
		if( (ui.item).hasClass('arf1columns') && (ui.item).next().next().hasClass('blankli1col') )
		{
			(ui.item).next().next().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}
		
		jQuery('#new_fields li').each(function(){
			if( jQuery(this).hasClass('blankli2col') || jQuery(this).hasClass('blankli32col') || jQuery(this).hasClass('blankli33col') )
			{
				jQuery(this).attr('data-theight', jQuery(this).height() );  
			}									   
		});
		
		if( (ui.item).hasClass('ui-draggable') && (ui.item).prev().hasClass('blankli1col') && (ui.item).next().hasClass('sortable-placeholder') )
		{
			(ui.item).prev().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}
	},
	
	change: function (event, ui) {
		var index = ui.placeholder.index();
		var mywidth = jQuery('#new_fields').width();
		var fi_id = (ui.item).attr('id');
		
		ui.placeholder.css({'width':'', 'height':'110px', 'border': '2px dashed #e6e6e6'});
		ui.placeholder.attr('data-nochange', 'false');
		jQuery('#new_fields li#temp').remove();
		
		ui.placeholder.width( mywidth - 10);
		ui.placeholder.css('clear', 'both');
		ui.placeholder.css('float', 'none');
		ui.placeholder.css('border', '2px dashed #1BBAE1');
		
		(ui.item).css('clear','both');
		(ui.item).css('float','none');
		(ui.item).width( mywidth - 10);
			
		if( fi_id != '' && fi_id !== undefined ){
			fi_id = fi_id.replace('arfmainfieldid_', ''); 
		}
		
		(ui.item).attr('class', (ui.item).attr('data-tclass')+' ui-sortable-helper' );
		
		//for adding blankli1col add 
		jQuery('#new_fields li').each(function(){
			
			if( jQuery(this).hasClass('arf1columns') && jQuery(this).next().hasClass('blankli1col') )
			{	
				jQuery(this).next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
				
			if( (jQuery(this).hasClass('arf21columns') || ( jQuery(this).next().hasClass('blankli2col') || jQuery(this).next().hasClass('arf_2col') ) && ( jQuery(this).next().next().hasClass('blankli1col') ) ) )
			{
				jQuery(this).next().next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (jQuery(this).hasClass('arf31colclass') && ( jQuery(this).next().hasClass('arf_23col') || jQuery(this).next().hasClass('blankli32col') ) &&  ( jQuery(this).next().next().hasClass('arf_3col') || jQuery(this).next().next().hasClass('blankli33col')) && ( jQuery(this).next().next().next().hasClass('blankli1col'))  ) )
			{
				jQuery(this).next().next().next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( jQuery(this).hasClass('edit_field_type_hidden') && jQuery(this).hasClass('edit_field_type_imagecontrol') && jQuery(this).next().hasClass('blankli1col') )
			{
				jQuery(this).next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}	
			
		});
		
		// for remove blankli1col after 1 columns
		if( (ui.item).hasClass('arf1columns') && (ui.placeholder).prev().hasClass('blankli1col') )
		{
			(ui.placeholder).prev().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}
		if( (ui.item).hasClass('arf1columns') && (ui.item).prev().hasClass('blankli1col') )
		{
			(ui.item).prev().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}
		if( (ui.item).hasClass('arf1columns') && (ui.placeholder).next().hasClass('blankli1col') )
		{
			(ui.placeholder).next().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}		
		
		if( jQuery('.sortable-placeholder').next().hasClass('blankli1col') )
		{
			jQuery('.sortable-placeholder').next().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}
		
		if( jQuery('.sortable-placeholder').next().next().hasClass('blankli1col') && jQuery('.sortable-placeholder').next().hasClass('ui-sortable-helper') )
		{
			jQuery('.sortable-placeholder').next().next().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}
		
		if( (ui.item).hasClass('ui-sortable-helper') && (ui.item).next().hasClass('blankli1col'))
		{
			(ui.item).next().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}
		
		if( jQuery('.sortable-placeholder').prev().hasClass('blankli1col') )
		{
			jQuery('.sortable-placeholder').prev().css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none'});
		}
		if( (ui.placeholder).next().hasClass('blankli2col') )
		{
			(ui.placeholder).next().css('width','1px');
			(ui.placeholder).next().css('height','1px');
			(ui.placeholder).next().css('padding','0px');
			(ui.placeholder).next().css('border','none');
		}
		else
		{	
			jQuery('.blankli2col').css('width', '');
			//jQuery('.blankli2col').width((mywidth / 2) -50);
			jQuery('.blankli2col').each(function(){
				if( jQuery(this).attr('data-theight') ){
					jQuery(this).height( jQuery(this).attr('data-theight') );
				}
			});
			jQuery('.blankli2col').css('padding', '');
			jQuery('.blankli2col').css('border-bottom','2px dashed #E6E6E6');
		}

		if( (ui.placeholder).next().hasClass('blankli32col'))
		{
			(ui.placeholder).next().css('width','1px');
			(ui.placeholder).next().css('height','1px');
			(ui.placeholder).next().css('padding','0px');
			(ui.placeholder).next().css('border','none');
		}
		else
		{
			jQuery('.blankli32col').css('width', '');	
			//jQuery('.blankli32col').width((mywidth / 3) -70);
			jQuery('.blankli32col').each(function(){
				if( jQuery(this).attr('data-theight') ){
					jQuery(this).height( jQuery(this).attr('data-theight') );
				}
			});
			jQuery('.blankli32col').css('padding', '');
			jQuery('.blankli32col').css('border-bottom','2px dashed #E6E6E6');
			jQuery('.blankli32col').css('border-right','2px dashed #E6E6E6');
		}
		
		if( (ui.placeholder).next().hasClass('blankli33col'))
		{
			(ui.placeholder).next().css('width','1px');
			(ui.placeholder).next().css('height','1px');
			(ui.placeholder).next().css('padding','0px');
			(ui.placeholder).next().css('border','none');
		}
		else
		{
			jQuery('.blankli33col').css('width', '');	
			//jQuery('.blankli33col').width((mywidth / 3) -70);
			jQuery('.blankli33col').each(function(){
				if( jQuery(this).attr('data-theight') ){
					jQuery(this).height( jQuery(this).attr('data-theight') );
				}
			});
			jQuery('.blankli33col').css('padding', '');
			jQuery('.blankli33col').css('border-bottom','2px dashed #E6E6E6');
		}
				
		var diff_of_li = 0;
		
		diff_of_li = ( (ui.placeholder.index() ) -  ( ui.item.index() ) )
		
		if( ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') && ((ui.item).hasClass('arf_2col') && (ui.item).prev().hasClass('arf21colclass') && (ui.placeholder).next().hasClass('arf2columns')) && ( diff_of_li == -2) )
		{
				
				ui.placeholder.width( (mywidth / 2) -50);
				ui.placeholder.css('clear', 'left');
				ui.placeholder.css('float', 'left');
				ui.placeholder.css('border', '2px dashed #1BBAE1');
				
				if( (ui.placeholder).prev().hasClass('blankli1col') )
				{
					(ui.placeholder).prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
				}
				if( (ui.placeholder).next().next().next().hasClass('blankli1col') )
				{
					(ui.placeholder).next().next().next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
				}
				
				(ui.item).addClass('arf2columns');
				
				$('#new_fields').attr("is_resized","2col");		
				jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
				
				(ui.item).css('clear','both');
				(ui.item).css('float','left');
				(ui.item).width( (mywidth / 2) -70);
				(ui.item).addClass('arf21colclass');
				
				(ui.item).prev().addClass('arf_2col');
				(ui.item).prev().width((mywidth / 2) -50);			
				(ui.item).prev().css('clear','none');
				(ui.item).prev().css('float','left');
				
				// for placeholde height.
				if( (ui.placeholder).next().hasClass('arf2columns') ){
					var next_height = (ui.placeholder).next().innerHeight(); 
				}
				next_height = next_height ? next_height : 0;			
				if( next_height ){
					(ui.placeholder).height( next_height );
				}
		}
		else if( ( (  (ui.item).hasClass('arf_2col') && diff_of_li == -2  ) || (ui.item).hasClass('arf1columns') ) && !(ui.placeholder).next().hasClass('blankli1col') && (ui.item).prev().hasClass('arf21colclass') && ( (ui.placeholder).next().hasClass('arf3columns') || (ui.placeholder).next().next().hasClass('arf3columns') || (ui.placeholder).next().next().next().hasClass('arf3columns')  ) ){
			
			//console.log('Placeholder bet 3col '+(ui.item).attr('data-startPos') );
			ui.placeholder.css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none', 'margin':'0'});
			ui.placeholder.attr('data-nochange', 'true');
			if( (ui.item).next().hasClass('blankli1col') )
			{
				(ui.item).next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
				
			if( (ui.item).attr('data-theight') ){
				var to_height = (ui.item).attr('data-theight')+'px';	
			} else {
				var to_height = '110px';	
			}		
			if( (ui.item).hasClass('arf3columns') )
			{
				jQuery('#new_fields').attr("is_resized","3col");		
				jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
							
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 3) - 70 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else if( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') )
			{
				jQuery('#new_fields').attr("is_resized","2col");		
				jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 2) - 50 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else
			{
				jQuery('#new_fields').attr("is_resized","1col");		
				jQuery('#classes_'+ fi_id +'_1').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth -10 ))+'px;height:110px;">&nbsp;</li>');
				}
			}
		}
				
		else if( ( (ui.item).hasClass('arf1columns') ||  (ui.item).hasClass('arf3columns') ) &&  (ui.placeholder).next().hasClass('arf_2col') && (ui.placeholder).prev().hasClass('arf21colclass') )
		{
			//console.log('Placehode remove Case 1' );
			
			ui.placeholder.css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none', 'margin':'0'});
			ui.placeholder.attr('data-nochange', 'true');
			
			if( (ui.item).attr('data-theight') ){
				var to_height = (ui.item).attr('data-theight')+'px';	
			} else {
				var to_height = '110px';
			}
			if( (ui.item).hasClass('arf3columns') )
			{
				jQuery('#new_fields').attr("is_resized","3col");		
				jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
							
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+( parseInt( (ui.item).attr('data-startPos') ) + 1 )+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 3) - 70 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else if( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') )
			{
				jQuery('#new_fields').attr("is_resized","2col");		
				jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 2) - 50 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else
			{
				jQuery('#new_fields').attr("is_resized","1col");		
				jQuery('#classes_'+ fi_id +'_1').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth -10 ))+'px;height:110px;">&nbsp;</li>');
				}
			}
				
		}
		else if( ( (ui.item).hasClass('arf1columns') || (ui.item).hasClass('arf2columns') ) &&  (ui.placeholder).next().hasClass('arf_23col') && (ui.placeholder).next().next().hasClass('arf_3col') )
		{
			//console.log('Placehode remove Case 2');
			
			ui.placeholder.css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none', 'margin':'0'});
			ui.placeholder.attr('data-nochange', 'true');
			
			if( (ui.item).attr('data-theight') ){
				var to_height = (ui.item).attr('data-theight')+'px';	
			} else {
				var to_height = '110px';
			}
			if( (ui.item).hasClass('arf3columns') )
			{
				jQuery('#new_fields').attr("is_resized","3col");		
				jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
							
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 3) - 70 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else if( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') )
			{
				jQuery('#new_fields').attr("is_resized","2col");		
				jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+( parseInt( (ui.item).attr('data-startPos') ) + 1 )+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 2) - 50 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
				
				if( (ui.item).hasClass('arf21colclass') && (ui.item).prev().prev().hasClass('blankli1col') )
				{	
					(ui.item).prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
				}
				
				if( (ui.item).hasClass('arf_2col') && (ui.item).prev().prev().prev().hasClass('blankli1col') )
				{	
					(ui.item).prev().prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
				}	
			}
			else
			{
				jQuery('#new_fields').attr("is_resized","1col");		
				jQuery('#classes_'+ fi_id +'_1').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth -10 ))+'px;height:110px;">&nbsp;</li>');
				}
			}
			
		}
		else if( ( (ui.item).hasClass('arf1columns') ||  (ui.item).hasClass('arf2columns') ) &&  (ui.placeholder).prev().hasClass('arf_23col') && (ui.placeholder).next().hasClass('arf_3col') )
		{
			//console.log('Placehode remove Case 3');
			
			ui.placeholder.css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none', 'margin':'0'});
			ui.placeholder.attr('data-nochange', 'true');
			
			if( (ui.item).attr('data-theight') ){
				var to_height = (ui.item).attr('data-theight')+'px';	
			} else {
				var to_height = '110px';
			}
			if( (ui.item).hasClass('arf3columns') )
			{
				jQuery('#new_fields').attr("is_resized","3col");		
				jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
							
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 3) - 70 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else if( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') )
			{
				jQuery('#new_fields').attr("is_resized","2col");		
				jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+( parseInt( (ui.item).attr('data-startPos') ) + 1 )+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 2) - 50 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
				
				if( (ui.item).hasClass('arf21colclass') && (ui.item).prev().prev().hasClass('blankli1col') )
				{	
					(ui.item).prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
				}
				
				if( (ui.item).hasClass('arf_2col') && (ui.item).prev().prev().prev().hasClass('blankli1col') )
				{	
					(ui.item).prev().prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
				}
						
			}
			else
			{
				jQuery('#new_fields').attr("is_resized","1col");		
				jQuery('#classes_'+ fi_id +'_1').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth -10 ))+'px;height:110px;">&nbsp;</li>');
				}
			}
			
		}
		else if( ( (ui.item).hasClass('arf1columns') ||  (ui.item).hasClass('arf2columns') ) &&  (ui.placeholder).next().hasClass('arf_23col') && (ui.placeholder).prev().hasClass('arf21colclass') )
		{
			//console.log('Placehode remove Case 4');
			
			if((ui.item).hasClass('arf2columns'))
			{
				jQuery('#new_fields').attr("is_resized","2col");		
				jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
			}
			
			ui.placeholder.css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none', 'margin':'0'});
			ui.placeholder.attr('data-nochange', 'true');
		}		
		else if( ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') && ( (ui.item).hasClass('arf_23col') || (ui.item).hasClass('arf_3col') ) && ! (ui.placeholder).prev().hasClass('blankli1col') && ! (ui.placeholder).prev().hasClass('arf1columns') && ! (ui.placeholder).prev().hasClass('arf2columns') && ! (ui.placeholder).prev().hasClass('mylastli') && (diff_of_li >= -2 && diff_of_li < 3) )
		{
			//console.log('Placehode remove Case 5');
			ui.placeholder.width( (mywidth / 3) -70);
  			
			if( (ui.placeholder).prev().hasClass('arf_23col') && (ui.placeholder).prev().prev().hasClass('arf31colclass') )
			{
				//console.log('Placehode remove Case 5 of 0' );
				ui.placeholder.css('clear', 'none');
				(ui.item).css('clear','none');
			}
			else if( (ui.placeholder).next().hasClass('arf_23col') && !(ui.placeholder).prev().hasClass('arf31colclass') )
			{
				//console.log('Placehode remove Case 5 of 1');
				ui.placeholder.css('clear', 'both');
				(ui.item).css('clear','both');
			}
			else
			{
				//console.log('Placehode remove Case 5 of 2');	
				if( (ui.item).has('arf_23col') && (ui.placeholder).next().hasClass('arf31colclass') )
				{
					(ui.placeholder).next().css('clear', 'none');
				}
				else
				{
					ui.placeholder.css('clear', 'none');
					(ui.item).css('clear','none');
				}				
			}
								
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			if( (ui.placeholder).next().next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).next().next().next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().next().next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 3) -70);
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf2columns');
			(ui.item).removeClass('arf21colclass');
			(ui.item).removeClass('arf_2col');
			(ui.item).removeClass('arf_1col');
			
			(ui.item).addClass('arf3columns');
			
			$('#new_fields').attr("is_resized","3col");
			jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
			
			if( (ui.placeholder).prev().hasClass('arf31colclass') ){
				(ui.item).addClass('arf_23col');
			}
			
			if( (ui.placeholder).prev().hasClass('arf_23col') || (ui.placeholder).prev().hasClass('blankli32col'))
			{
				(ui.item).removeClass('arf_23col');
				(ui.item).addClass('arf_3col');
			}
			
			if( (ui.placeholder).prev().prev().prev().hasClass('arf31colclass') ){
				var prev_prev_prev_height = (ui.placeholder).prev().prev().prev().innerHeight();				
			}if( (ui.placeholder).prev().prev().hasClass('arf31colclass') ){
				var prev_prev_height = (ui.placeholder).prev().prev().innerHeight();
			}if( (ui.placeholder).prev().hasClass('arf3columns') ){
				var prev_height = (ui.placeholder).prev().innerHeight(); 
			}if( (ui.placeholder).next().hasClass('arf3columns') ){
				var next_height = (ui.placeholder).next().innerHeight();
			}
			var height_to = '';
			prev_prev_prev_height	= prev_prev_prev_height ? prev_prev_prev_height : 0;
			prev_prev_height	= prev_prev_height ? prev_prev_height : 0;
			prev_height = prev_height ? prev_height : 0;
			next_height = next_height ? next_height : 0;
			
			if( prev_prev_prev_height > prev_height ){
				prev_height = prev_prev_prev_height;
			}
			
			if( prev_prev_height > prev_height ){
				prev_height = prev_prev_height;
			}
			
			if( next_height > prev_height ){
				height_to = next_height; 
			}else{
				height_to = prev_height;
			}
			if( height_to ){
				(ui.placeholder).height( height_to );
			}
		}
		else if( ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') && ( (ui.item).hasClass('arf_3col') || (ui.item).hasClass('arf_23col') ) && (ui.placeholder).next().hasClass('arf31colclass') && (diff_of_li >= -3 && diff_of_li < 2) )
		{
			//console.log('Placehode remove Case 5 of 3 new' );
			ui.placeholder.width( (mywidth / 3) -70);
  			ui.placeholder.css('clear', 'both');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.placeholder).next().css('clear', 'none');
			
			if( (ui.placeholder).prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).next().next().next().next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().next().next().next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
						
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 3) -70);
			
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf2columns');
			(ui.item).removeClass('arf21colclass');
			(ui.item).removeClass('arf_2col');
			(ui.item).removeClass('arf_1col');			
			(ui.item).addClass('arf3columns');
			
			$('#new_fields').attr("is_resized","3col");
			jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
			
			// for placeholde height.
			if( (ui.placeholder).prev().hasClass('arf3columns') ){
				var prev_height = (ui.placeholder).prev().innerHeight(); 
			} if( (ui.placeholder).next().hasClass('arf3columns') ) {
				var next_height = (ui.placeholder).next().innerHeight();
			}
			var height_to = '';
			prev_height = prev_height ? prev_height : 0;
			next_height = next_height ? next_height : 0;			
			if( next_height > prev_height ){
				height_to = next_height; 
			}else{
				height_to = prev_height;
			}
			if( height_to ){
				(ui.placeholder).height( height_to );
			}
			
		}
		else if( ( (ui.item).hasClass('arf1columns') || (ui.item).hasClass('arf2columns') || (ui.item).hasClass('arf_3col') ) &&  (ui.placeholder).next().hasClass('arf_23col') && ( (ui.placeholder).prev().hasClass('arf3columns') || (ui.placeholder).prev().hasClass('blankli33col') ) )
		{
			//console.log('Placehode remove Case 5 after	:');
			
			ui.placeholder.css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none', 'margin':'0'});
			ui.placeholder.attr('data-nochange', 'true');
			
			if( (ui.item).attr('data-theight') ){
				var to_height = (ui.item).attr('data-theight')+'px';	
			}else{
				var to_height = '110px';
			}
			if( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') )
			{
				jQuery('#new_fields').attr("is_resized","2col");		
				jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 2) - 50 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else
			{
				jQuery('#new_fields').attr("is_resized","1col");		
				jQuery('#classes_'+ fi_id +'_1').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth -10 ))+'px;height:110px;">&nbsp;</li>');
				}
			}
						
		}
		else if( ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') && ((ui.item).hasClass('arf31colclass') ) && (diff_of_li > 0 && diff_of_li < 4) )
		{
			
			ui.placeholder.width( (mywidth / 3) -70);
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			if( (ui.placeholder).next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
						
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 3) -70);
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf2columns');
			(ui.item).removeClass('arf21colclass');
			(ui.item).removeClass('arf_2col');
			(ui.item).removeClass('arf_1col');
			
			(ui.item).addClass('arf3columns');
			
			$('#new_fields').attr("is_resized","3col");
			//fi_id
			jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
			
			if( (ui.placeholder).prev().hasClass('arf31colclass') ){
				(ui.item).addClass('arf_23col');
			}
			if( (ui.placeholder).prev().hasClass('arf_23col') || (ui.placeholder).prev().hasClass('blankli32col'))
			{
				(ui.item).removeClass('arf_23col');
				(ui.item).addClass('arf_3col');
			}
			
			// for placeholde height.
			if( (ui.placeholder).prev().hasClass('arf3columns') ){
				var prev_height = (ui.placeholder).prev().innerHeight(); 
			}if( (ui.placeholder).next().hasClass('arf3columns') ){
				var next_height = (ui.placeholder).next().innerHeight();
			}
			var height_to = '';
			prev_height = prev_height ? prev_height : 0;
			next_height = next_height ? next_height : 0;			
			if( next_height > prev_height ){
				height_to = next_height; 
			}else{
				height_to = prev_height;
			}if( height_to ){
				(ui.placeholder).height( height_to );
			}
			
		}
		else if( ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') && ((ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col')) && ( (ui.placeholder).prev().hasClass('arf_2col') || (ui.placeholder).prev().hasClass('arf_21colclass') ) && ( diff_of_li == 2))
		{
			
			ui.placeholder.width( (mywidth / 2) -50);
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 2) -50);
			
			(ui.item).removeClass('arf31colclass');
			(ui.item).removeClass('arf_1col');
			(ui.item).removeClass('arf_23col');
			(ui.item).removeClass('arf_3col');
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf3columns');
			(ui.item).addClass('arf2columns');
			
			$('#new_fields').attr("is_resized","2col");
			
			jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
			
			if( (ui.placeholder).prev().hasClass('arf21colclass') )
			{
				(ui.item).addClass('arf_2col');
			}
			
			if( (ui.placeholder).next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			// for placeholde height.
			if( (ui.placeholder).prev().hasClass('arf2columns') ){
				var prev_height = (ui.placeholder).prev().innerHeight();
			}
			var height_to = '';
			prev_height = prev_height ? prev_height : 0;
			height_to = prev_height;
			if( height_to ){
				(ui.placeholder).height( height_to );
			}
			
		}
		else if( ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') && ( (ui.placeholder).prev().hasClass('blankli32col') ||  (ui.placeholder).next().hasClass('blankli33col') || (ui.placeholder).next().hasClass('blankli32col') || (ui.placeholder).next().hasClass('blankli33col') ) && !((ui.placeholder).next().hasClass('arf31colclass') || (ui.placeholder).next().hasClass('arf1columns')  ))
		{
			ui.placeholder.width( (mywidth / 3) -70);
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 3) -70);
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf2columns');
			(ui.item).removeClass('arf21colclass');
			(ui.item).removeClass('arf_2col');
			(ui.item).removeClass('arf_1col');
			
			(ui.item).addClass('arf3columns');
			
			$('#new_fields').attr("is_resized","3col");

			//fi_id
			jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
			
			if( (ui.placeholder).prev().hasClass('arf31colclass') ){
				(ui.item).addClass('arf_23col');
			}
			if( (ui.placeholder).prev().hasClass('arf_23col') || (ui.placeholder).prev().hasClass('blankli32col'))
			{
				(ui.item).removeClass('arf_23col');
				(ui.item).addClass('arf_3col');
			}
			
			if( (ui.placeholder).next().next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).prev().prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).prev().prev().prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			// for placeholde height.
			if( (ui.placeholder).prev().hasClass('arf3columns') ){
				var prev_height = (ui.placeholder).prev().innerHeight();
			}
			var height_to = '';
			prev_height = prev_height ? prev_height : 0;
			height_to = prev_height;
			if( height_to ){
				(ui.placeholder).height( height_to );
			}
					
		}
		
		else if( ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') && ((ui.placeholder).prev().hasClass('blankli2col') || (ui.placeholder).next().hasClass('blankli2col') ) && !( (ui.placeholder).next().hasClass('blankli1col') )  && !((ui.placeholder).next().hasClass('arf1columns') || (ui.placeholder).next().hasClass('arf31colclass') ))
		{
			
			if( (ui.placeholder).prev().prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			ui.placeholder.width( (mywidth / 2) -50);
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 2) -50);
			
			(ui.item).removeClass('arf31colclass');
			(ui.item).removeClass('arf_1col');
			(ui.item).removeClass('arf_23col');
			(ui.item).removeClass('arf_3col');
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf3columns');
			(ui.item).addClass('arf2columns');
			
			$('#new_fields').attr("is_resized","2col");
			
			jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
			
		
			if( (ui.placeholder).prev().hasClass('arf21colclass') )
			{
				(ui.item).addClass('arf_2col');
			}
			
			// for placeholde height.
			if( (ui.placeholder).prev().hasClass('arf2columns') ){
				var prev_height = (ui.placeholder).prev().innerHeight();
			}
			var height_to = '';
			prev_height = prev_height ? prev_height : 0;
			height_to = prev_height;
			if( height_to ){
				(ui.placeholder).height( height_to );
			}
		}
		else if( (ui.placeholder).next().hasClass('arf_2col') && (ui.placeholder).prev().hasClass('arf21colclass') )
		{
			// to prevent 2col break.
			
			ui.placeholder.css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none', 'margin':'0'});
			ui.placeholder.attr('data-nochange', 'true');
			
			if( (ui.placeholder).next().next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.item).attr('data-theight') ){
				var to_height = (ui.item).attr('data-theight')+'px';	
			} else {
				var to_height = '110px';
			}				
			if( (ui.item).hasClass('arf3columns') )
			{
				jQuery('#new_fields').attr("is_resized","3col");		
				jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
							
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 3) - 70 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else if( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') )
			{
				jQuery('#new_fields').attr("is_resized","2col");		
				jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+( parseInt( (ui.item).attr('data-startPos') ) + 1 )+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 2) - 50 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else
			{
				jQuery('#new_fields').attr("is_resized","1col");		
				jQuery('#classes_'+ fi_id +'_1').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth -10 ))+'px;height:110px;">&nbsp;</li>');
				}
			}
					
		}
		else if( ( (ui.item).hasClass('arf3columns') || (ui.item).hasClass('ui-draggable') ) && ( ( (ui.placeholder).prev().hasClass('arf31colclass') && (ui.placeholder).next().hasClass('arf_23col') ) || ( (ui.placeholder).prev().hasClass('arf_23col') && (ui.placeholder).next().hasClass('arf_3col') ) ) )
		{
			// to prevent 3col break.
			
			ui.placeholder.css({'width':'0', 'height':'0', 'border':'none', 'padding':'0', 'float':'none', 'clear':'none', 'margin':'0'});
			ui.placeholder.attr('data-nochange', 'true');
			
			if( (ui.placeholder).next().next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
						
			if( (ui.item).attr('data-theight') ){
				var to_height = (ui.item).attr('data-theight')+'px';	
			}else{
				var to_height = '110px';
			}
			if( (ui.item).hasClass('arf3columns') )
			{
				jQuery('#new_fields').attr("is_resized","3col");		
				jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
							
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+( parseInt( (ui.item).attr('data-startPos') ) + 1 )+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth / 3) - 70 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else if( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') )
			{
				jQuery('#new_fields').attr("is_resized","2col");		
				jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:both;border:2px dashed #1BBAE1;width:'+( (mywidth / 2) - 50 )+'px;height:'+to_height+';">&nbsp;</li>');
				}
			}
			else
			{
				jQuery('#new_fields').attr("is_resized","1col");		
				jQuery('#classes_'+ fi_id +'_1').attr('checked',true);
				
				if( jQuery('#new_fields li#temp').length == 0 ){
					jQuery('#new_fields li:nth-child('+(ui.item).attr('data-startPos')+')').after('<li class="sortable-placeholder" id="temp"  style="float:left;clear:none;border:2px dashed #1BBAE1;width:'+( (mywidth -10 ))+'px;height:110px;">&nbsp;</li>');
				}
			}
		}
		else if(  ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') && /*(ui.item).hasClass('arf1columns') &&*/ ( (ui.placeholder).prev().hasClass('arf21colclass') && (ui.placeholder).next().hasClass('blankli2col') ) )
		{
			
			//console.log('Placehode custom case from up to down 1  ===========>  ' + ui.placeholder.index());
			
			var temp_field  = (ui.placeholder).next().clone();
			
			var temp_index 	= (ui.placeholder).next().index() - 1;
			
			if( (ui.placeholder).prev().prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).next().hasClass('blankli2col') )
			{
				(ui.placeholder).next().css('width','1px');
				(ui.placeholder).next().css('height','1px');
				(ui.placeholder).next().css('padding','0px');
				(ui.placeholder).next().css('border','none');
			}
						
			ui.placeholder.width( (mywidth / 2) -50);
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 2) -50);
			
			(ui.item).removeClass('arf31colclass');
			(ui.item).removeClass('arf_1col');
			(ui.item).removeClass('arf_23col');
			(ui.item).removeClass('arf_3col');
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf3columns');
			(ui.item).addClass('arf2columns');
			
			$('#new_fields').attr("is_resized","2col");
			
			jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
			
		
			if( (ui.placeholder).prev().hasClass('arf21colclass') )
			{
				(ui.item).addClass('arf_2col');
			}
			
			// for placeholde height.
			if( (ui.placeholder).prev().hasClass('arf2columns') ){
				var prev_height = (ui.placeholder).prev().innerHeight(); 
			}
			var height_to = '';
			prev_height = prev_height ? prev_height : 0;
			height_to = prev_height;
			if( height_to )	{
				(ui.placeholder).height( height_to );
			}
		}
		
		else if( ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') && /*(ui.item).hasClass('arf1columns') &&*/ ( (ui.placeholder).prev().hasClass('blankli2col') && (ui.placeholder).next().hasClass('blankli1col') ) )
		{
			//console.log('final case 1');
			
			if( (ui.placeholder).prev().prev().prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).prev().hasClass('blankli2col') )
			{
				(ui.placeholder).prev().css('width','0px');
				(ui.placeholder).prev().css('height','0px');
				(ui.placeholder).prev().css('padding','0px');
				(ui.placeholder).prev().css('border','none');
			}
						
			ui.placeholder.width( (mywidth / 2) -50);
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 2) -50);
			
			(ui.item).removeClass('arf31colclass');
			(ui.item).removeClass('arf_1col');
			(ui.item).removeClass('arf_23col');
			(ui.item).removeClass('arf_3col');
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf3columns');
			(ui.item).addClass('arf2columns');
			
			$('#new_fields').attr("is_resized","2col");
			
			jQuery('#classes_'+ fi_id +'_2').attr('checked',true);
			
		
			if( (ui.placeholder).prev().hasClass('arf21colclass') )
			{
				(ui.item).addClass('arf_2col');
			}
			
			// for placeholde height.
			if( (ui.placeholder).prev().prev().hasClass('arf2columns') ){
				var prev_height = (ui.placeholder).prev().prev().innerHeight(); 
			}
			var height_to = '';
			prev_height = prev_height ? prev_height : 0;
			height_to = prev_height;
			if( height_to )	{
				(ui.placeholder).height( height_to );
			}				
		}
		
		else if(  ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') &&  /*(ui.item).hasClass('arf1columns') &&*/ ( (ui.placeholder).prev().hasClass('arf_23col') && (ui.placeholder).next().hasClass('blankli33col') ) )
		{
			
			//console.log('Placehode custom case from up to down 2  ===========>  ' + ui.placeholder.index());
			
			var temp_field  = (ui.placeholder).next().clone();
			
			var temp_index 	= (ui.placeholder).next().index() - 1;
			
			if( (ui.placeholder).prev().prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).next().hasClass('blankli33col') )
			{
				(ui.placeholder).next().css('width','1px');
				(ui.placeholder).next().css('height','1px');
				(ui.placeholder).next().css('padding','0px');
				(ui.placeholder).next().css('border','none');
			}
						
			ui.placeholder.width( (mywidth / 3) -70);
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 3) -70);
			
			(ui.item).removeClass('arf31colclass');
			(ui.item).removeClass('arf_1col');
			(ui.item).removeClass('arf_23col');
			(ui.item).removeClass('arf_3col');
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf2columns');
			(ui.item).addClass('arf3columns');
			
			$('#new_fields').attr("is_resized","3col");
			
			jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
			
		
			if( (ui.placeholder).prev().hasClass('arf_23col') )
			{
				(ui.item).addClass('arf_3col');
			}
			
			// for placeholde height.
			if( (ui.placeholder).prev().hasClass('arf3columns') ){
				var prev_height = (ui.placeholder).prev().innerHeight(); 
			}
			var height_to = '';
			prev_height = prev_height ? prev_height : 0;
			height_to = prev_height;
			if( height_to )	{
				(ui.placeholder).height( height_to );
			}
		}
		
		else if( ! (ui.item).hasClass('frm_thidden') && ! (ui.item).hasClass('frm_timagecontrol') && ! (ui.item).hasClass('frm_tbreak') && ! (ui.item).hasClass('frm_tdivider') && ! (ui.item).hasClass('edit_field_type_hidden') && ! (ui.item).hasClass('edit_field_type_imagecontrol') && ! (ui.item).hasClass('edit_field_type_break') && ! (ui.item).hasClass('edit_field_type_divider') &&  /*(ui.item).hasClass('arf1columns') &&*/ ( (ui.placeholder).prev().hasClass('blankli33col') && (ui.placeholder).next().hasClass('blankli1col') ) )
		{
			//console.log('final case 2');
			
			if( (ui.placeholder).prev().prev().prev().prev().hasClass('blankli1col') )
			{
				(ui.placeholder).prev().prev().prev().prev().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).next().hasClass('blankli1col') )
			{
				(ui.placeholder).next().css({'width':'', 'height':'', 'border':'', 'padding':'', 'float':'', 'clear':''});
			}
			
			if( (ui.placeholder).prev().hasClass('blankli33col') )
			{
				(ui.placeholder).prev().css('width','0px');
				(ui.placeholder).prev().css('height','0px');
				(ui.placeholder).prev().css('padding','0px');
				(ui.placeholder).prev().css('border','none');
			}
						
			ui.placeholder.width( (mywidth / 3) -70);
  			ui.placeholder.css('clear', 'none');
			ui.placeholder.css('float', 'left');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).css('clear','none');
			(ui.item).css('float','left');
			(ui.item).width( (mywidth / 3) -70);
			
			(ui.item).removeClass('arf31colclass');
			(ui.item).removeClass('arf_1col');
			(ui.item).removeClass('arf_23col');
			(ui.item).removeClass('arf_3col');
			(ui.item).removeClass('arf1columns');
			(ui.item).removeClass('arf2columns');
			(ui.item).addClass('arf3columns');
			
			$('#new_fields').attr("is_resized","3col");
			
			jQuery('#classes_'+ fi_id +'_3').attr('checked',true);
			
		
			if( (ui.placeholder).prev().hasClass('arf_23col') )
			{
				(ui.item).addClass('arf_3col');
			}
			
			// for placeholde height.
			if( (ui.placeholder).prev().prev().hasClass('arf3columns') ){
				var prev_height = (ui.placeholder).prev().prev().innerHeight(); 
			}
			var height_to = '';
			prev_height = prev_height ? prev_height : 0;
			height_to = prev_height;
			if( height_to )	{
				(ui.placeholder).height( height_to );
			}				
		}
		else
		{

			ui.placeholder.width( mywidth - 10);
  			ui.placeholder.css('clear', 'both');
			ui.placeholder.css('float', 'none');
			ui.placeholder.css('border', '2px dashed #1BBAE1');
			
			(ui.item).css('clear','both');
			(ui.item).css('float','none');
			
			(ui.item).addClass('arf1columns');
			(ui.item).removeClass('arf2columns');
			(ui.item).removeClass('arf3columns');
			
			jQuery('#classes_'+ fi_id +'_1').attr('checked',true);
			
			$('#new_fields').attr("is_resized","1col");
		}
		
	},	
	
	beforeStop: function( event, ui ){
		//console.log('pc:'+ui.placeholder.attr('data-nochange') );
		if( ui.placeholder.attr('data-nochange') == 'true' && ( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') || (ui.item).hasClass('arf_31col') || (ui.item).hasClass('arf31colclass') || (ui.item).hasClass('arf_23col') || (ui.item).hasClass('arf_3col') ) )
		{
			//console.log('inside 0');
			jQuery('#new_fields li#temp').remove();
		}
		else if( ui.placeholder.attr('data-nochange') == 'true' && (ui.item).hasClass('arf1columns') )
		{
			//console.log('inside 01');
			jQuery('#new_fields li#temp').remove();	
		}
		
		if( (ui.item).hasClass('ui-draggable') )
		{
			jQuery('#new_fields').attr('data-nochange', (ui.placeholder).attr('data-nochange') );
			jQuery('#new_fields').attr('data-startPos', (ui.item).attr('data-startPos') );
			(ui.placeholder).attr('data-nochange', 'false');
			jQuery('#new_fields li#temp').remove();	
		}
			
	},
	
	stop: function(event, ui) {
		
		if( ui.placeholder.attr('data-nochange') == 'true' && ( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') || (ui.item).hasClass('arf_31col') || (ui.item).hasClass('arf31colclass') || (ui.item).hasClass('arf_23col') || (ui.item).hasClass('arf_3col') ) )
		{
			//console.log('inside 1');
			if( (ui.item).hasClass('arf_2col') )
			{
				(ui.item).css('clear', 'none');
				(ui.item).css('float', 'left');
			}
			if( (ui.item).hasClass('arf21colclass') )
			{
				(ui.item).css('clear', 'both');
				(ui.item).css('float', 'left');
			}
			if( (ui.item).hasClass('arf_31col') )
			{
				(ui.item).css('clear', 'both');
				(ui.item).css('float', 'left');
			}
			if( (ui.item).hasClass('arf_23col') )
			{
				(ui.item).css('clear', 'none');
				(ui.item).css('float', 'left');
			}
			if( (ui.item).hasClass('arf_3col') )
			{
				(ui.item).css('clear', 'none');
				(ui.item).css('float', 'left');
			}
			ui.placeholder.attr('data-nochange', 'false');						
		}
		else if( ui.placeholder.attr('data-nochange') == 'true' && (ui.item).hasClass('arf1columns') )
		{
			//console.log('inside 11');
			if( (ui.item).hasClass('arf1columns') )
			{
				(ui.item).css('clear', 'both');
				(ui.item).css('float', 'none');
			}
			ui.placeholder.attr('data-nochange', 'false');		
		}
		
		jQuery('.arf21colclass').css('float','left');
		
		(ui.item).css('background-color','');
		
		CheckFieldPos('1','0');
		
		jQuery('#temp').remove();
	},
	
	out:function( event, ui ){
		//console.log('out	'+ (ui.item).attr('class') );
		if( (ui.item).hasClass('ui-draggable') )
		{
			jQuery('#temp').remove();
			setTimeout(function(){	
				CheckFieldPos('1','0');
			}, 100);
		}
	},
	
	receive:function(event,ui){
		
		jQuery('#temp').remove();
		
		$('#new_fields').attr("data-flag","0");
        var new_id=(ui.item).attr('id');
		
		mycolsize = $('#new_fields').attr("is_resized");
			
		if( jQuery('#new_fields').attr('data-nochange') == 'true' )
		{
			var new_field = jQuery('#new_fields .frmbutton.frm_t'+new_id).clone();			
			jQuery('#new_fields .frmbutton.frm_t'+new_id).remove();
			jQuery('#new_fields').append(new_field);
		}		
		
        jQuery('#new_fields .frmbutton.frm_t'+new_id).replaceWith('<div class="arfbutton_loadingnow" id="'+new_id+'"><img class="frmbutton frmbutton_loadingnow" src="<?php echo ARFIMAGESURL; ?>/ajax-loader1.gif" alt="<?php _e('Loading...', 'ARForms'); ?>" align="middle" /></div>');
		
		if( mycolsize == "2col")
		{
			jQuery('.wrap .arfbutton_loadingnow').css('width','45%');
			jQuery('.wrap .arfbutton_loadingnow').css('clear','none');
			jQuery('.wrap .arfbutton_loadingnow').css('float','left');
			jQuery('.wrap .arfbutton_loadingnow').css('margin-left','24%');
			jQuery('.wrap .arfbutton_loadingnow').css('margin-top','-150px');
			jQuery('.wrap .arfbutton_loadingnow').css('position','absolute');
		}
		
		if( mycolsize == "3col")
		{
			jQuery('.wrap .arfbutton_loadingnow').css('width','29%');
			jQuery('.wrap .arfbutton_loadingnow').css('clear','none');
			jQuery('.wrap .arfbutton_loadingnow').css('float','left');
			jQuery('.wrap .arfbutton_loadingnow').css('margin-left','20%');
			jQuery('.wrap .arfbutton_loadingnow').css('margin-top','-150px');
			jQuery('.wrap .arfbutton_loadingnow').css('position','absolute');
		}
		
		if( mycolsize == "3col" && ( jQuery('.wrap .arfbutton_loadingnow').prev().hasClass('arf_23col') || jQuery('.wrap .arfbutton_loadingnow').prev().hasClass('blankli33col') ) )
		{
			jQuery('.wrap .arfbutton_loadingnow').css('width','29%');
			jQuery('.wrap .arfbutton_loadingnow').css('clear','none');
			jQuery('.wrap .arfbutton_loadingnow').css('float','left');
			jQuery('.wrap .arfbutton_loadingnow').css('margin-left','43%');
			jQuery('.wrap .arfbutton_loadingnow').css('margin-top','-150px');
			jQuery('.wrap .arfbutton_loadingnow').css('position','absolute');
		}
		
		if( mycolsize == "1col")
		{
			jQuery('.wrap .arfbutton_loadingnow').css('padding-top','0');
			jQuery('.wrap .arfbutton_loadingnow').css('padding-bottom','45px');
		}
			
		var pg_break_pre_first = jQuery("#page_break_first_pre_btn_txt").val();
		var pg_break_next_first = jQuery("#page_break_first_next_btn_txt").val();
		var pg_break_first_select = jQuery("#page_break_first_select").val();

        jQuery.ajax({

            type:"POST",url:"<?php echo $arfajaxurl ?>",data:"action=arfinsertnewfield&form_id=<?php echo $id; ?>&colsize="+mycolsize+"&field="+new_id+"&pg_break_pre_first="+pg_break_pre_first+"&pg_break_next_first="+pg_break_next_first+"&pg_break_first_select="+pg_break_first_select,


            success:function(msg){ 
				
				mycolsize = "1col";
				
				$('#new_fields').attr("is_resized","1col");
				
                $('.arfbutton_loadingnow#'+new_id).replaceWith(msg);
				
				
                var regex = /id="(\S+)"/; match=regex.exec(msg);


                $('#'+match[1]+' .arfeditorfieldopt_label').click();


                var order= $('#new_fields').sortable('serialize');

				jQuery('.rate_widget').each(function(i) {
						//field id
						widget_id = jQuery(this).attr('id');
					
						jQuery('.ratings_stars').hover(
							// Handles the mouseover
							function() {
								var color = jQuery(this).attr('data-color');
								var datasize = jQuery(this).attr('data-size');
								jQuery(this).prevAll().andSelf().addClass('ratings_over_'+color+datasize);
								jQuery(this).nextAll().removeClass('ratings_vote_'+color+datasize);
							},
							// Handles the mouseout
							function() {
								var color = jQuery(this).attr('data-color');
								var datasize = jQuery(this).attr('data-size');
								jQuery(this).prevAll().andSelf().removeClass('ratings_over_'+color+datasize);
								set_votes(jQuery(this).parent(), widget_id);
							}
						);
								
						// This actually records the vote
						jQuery('.ratings_stars').bind('click', function() {
							var star = this;
							var widget = jQuery(this).parent();
							
							var clicked_data = {
								clicked_on : jQuery(star).attr('data-val'),
								widget_id : jQuery(star).parent().attr('id')
							};
							jQuery('#field_'+widget_id).val(clicked_data.clicked_on);
							jQuery('#field_'+widget_id).trigger('click');
							set_votes(widget, widget_id); 
						});
						
					});
					
                jQuery.ajax({type:"POST",url:"<?php echo $arfajaxurl ?>",data:"action=arfupdatefieldorder&"+order});
				
				CheckFieldPos('1','0');
				
				checkpage_breakpos(); 
				
				change_cl_field_menu(match[1]);	//---------- for conditional logic ----------//
				
				arf_change_name_dropdown(match[1]);
				
				jQuery(".sltstandard select").selectpicker(); 
				
				jQuery('body').removeAttr('style');
            	$('#new_fields').attr("data-flag","1");
				
			}
			
        });


    },
	
	update:function(event, ui){
		if( ui.placeholder.attr('data-nochange') == 'true' && ( (ui.item).hasClass('arf21colclass') || (ui.item).hasClass('arf_2col') || (ui.item).hasClass('arf_31col') || (ui.item).hasClass('arf31colclass') || (ui.item).hasClass('arf_23col') || (ui.item).hasClass('arf_3col') ) )
		{
			//console.log('inside 2');
			return false;
			event.preventDefault();
			ui.placeholder.attr('data-nochange', 'false');		
		}
		else if( ui.placeholder.attr('data-nochange') == 'true' && (ui.item).hasClass('arf1columns') )
		{
			//console.log('inside 22');
			return false;
			event.preventDefault();
			ui.placeholder.attr('data-nochange', 'false');		
		}
			
		if($('#new_fields').attr("data-flag")==1)
		{
			var order= $('#new_fields').sortable('serialize');
			
			mycolsize = "1col";
			
			$('#new_fields').attr("is_resized","1col");
				
			jQuery.ajax({type:"POST",url:"<?php echo $arfajaxurl ?>",data:"action=arfupdatefieldorder&"+order});
			
			CheckFieldPos('1','0');

			checkpage_breakpos();
			
		}
	}

});


});

</script>