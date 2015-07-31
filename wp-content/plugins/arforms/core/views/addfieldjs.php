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
global $arfajaxurl;
?>
<script type="text/javascript">


jQuery(document).ready(function($){


$('select[name^="item_meta"], textarea[name^="item_meta"]').css('float','left');


$('input[name^="item_meta"]').not(':radio, :checkbox').css('float','left');

$("#arfmainfieldid_<?php echo $field['id']; ?> .arfoptioneditorfield, #arfmainfieldid_<?php echo $field['id']; ?> .arfoptioneditorfield_key").editInPlace({


url:"<?php echo $arfajaxurl ?>",params:"action=arfeditorfieldoption", default_text:"<?php _e('(Blank)', 'ARForms') ?>" ,success:function(res){
 	arf_change_opt_val( jQuery(this).attr('data-fid'), jQuery(this).attr('id'), jQuery(this).text(), jQuery(this).attr('data-original') ); 
	arf_change_opt_label( jQuery(this).attr('data-fid'), jQuery(this).attr('id'), jQuery(this).text() );
	}
});

var def_title = '(Click here to add text)';
if( typeof(__ARFDEFAULTTITLE) != 'undefined' ){
	var def_title = __ARFDEFAULTTITLE;
}

$("#arfmainfieldid_<?php echo $field['id']; ?> .arfeditorfieldopt_label").not('.arfeditorfieldopt_divider_label').editInPlace({


url:"<?php echo $arfajaxurl ?>",params:"action=arfupdatefieldname", value_required:"true", desc:def_title, success:function(){ 
	update_cl_field_menu(); /* for conditional logic */
	arf_update_name_dropdown();
	change_password_field_dropdown(); // for password dropdown
	jQuery(".sltstandard select").selectpicker(); 
	}

});

$("#arfmainfieldid_<?php echo $field['id']; ?> .arfeditorfieldopt_divider_label").editInPlace({


url:"<?php echo $arfajaxurl ?>",params:"action=arfupdatefieldname", default_text:"<?php _e('(Blank Section)', 'ARForms') ?>", value_required:"false", success:function(){ 
	update_cl_field_menu(); /* for conditional logic */ 
	arf_update_name_dropdown();
	jQuery(".sltstandard select").selectpicker();
}

});


$("#arfmainfieldid_<?php echo $field['id']; ?> .arfeditorfieldopt_desc").editInPlace({


url:"<?php echo $arfajaxurl ?>",params:"action=arfupdatefielddescription",field_type:'textarea',textarea_rows:3,


default_text:"(<?php _e('Click here to add optional description or instructions', 'ARForms') ?>)"


});

});

jQuery(document).ready(function(){
	jQuery('#arfmainfieldid_<?php echo $field['id']; ?> .html_field_description').on('blur', function(){
		var $elm = jQuery(this);
		var input = $elm[0];
		if (input.setSelectionRange) {
			pos_start	= input.selectionStart;
			pos_end		= input.selectionEnd;
			jQuery(this).attr('data-startpos', pos_start);
		} 
	});
});

jQuery(document).ready(function(){
	jQuery('#arfmainfieldid_<?php echo $field['id']; ?> .arf_like').on("click", function(){
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
});

jQuery(document).ready(function(){
	jQuery('#arfmainfieldid_<?php echo $field['id']; ?> .arfhelptip').tooltipster({
		theme: 'arf_admin_tooltip',
		position:'top',
		contentAsHTML:true,
		onlyOne:true,
		multiple:true,
		maxWidth:400,
	});
});

<?php if( $field['type'] == 'checkbox' || $field['type'] == 'radio' || $field['type'] == 'select' ) { ?>
jQuery(document).ready(function(){
	jQuery("#arfmainfieldid_<?php echo $field['id']; ?> #arfoptionul_<?php echo $field['id']; ?>").sortable({
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
<?php } ?>
//for switch
<?php $afr_action_new=isset($_REQUEST['action'])?$_REQUEST['action']:"";  ?>
<?php if($afr_action_new != 'arfpresetoptions') { ?>

 if (Array.prototype.forEach) {
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	
	elems.forEach(function(html) {
	  	
	  if(html.getAttribute("data-switchery")!="true")
	  {				
	  	switchery[html.id] = new Switchery(html);
	  } 	
	});
  } else {
	var elems = document.querySelectorAll('.js-switch');

	for (var i = 0; i < elems.length; i++) {
	  if(elems[i].getAttribute("data-switchery")!="true")
	  {	
	  	switchery[elems[i].id] = new Switchery(elems[i]);
	  } 	
	}
  }
if (window.PIE) {
var wrapper = document.querySelectorAll('.switchery')
  , handle = document.querySelectorAll('.switchery > small');

if (wrapper.length == handle.length) {
  for (var i = 0; i < wrapper.length; i++) {
	PIE.attach(wrapper[i]);
	PIE.attach(handle[i]);
  }
}
}
<?php } ?>	
//for switch
<?php if( $field['type'] == 'file' || $field['type'] == 'divider' || $field['type'] == 'slider' || $field['type'] == 'like' || $field['type'] == 'colorpicker' ) { ?>
jQuery('#arfmainfieldid_<?php echo $field['id']; ?> .arf_coloroption_sub:not(.arf_clr_disable)').colpick({
    layout:'hex',
    submit:0,
    onBeforeShow:function(){
        var fid 	= jQuery(this).find('.arfhex').attr('data-fid');
        var color 	= jQuery('#'+fid).val();
        var did = fid.replace('arf_divider_bg_color_','');
            if( jQuery(this).attr('data-cls') == 'arf_clr_disable'){
                    jQuery('.arf_clr_disable .arfhex').css('background',color);
            }
        color	= color.replace('#','');
        jQuery(this).colpickSetColor(color, true);
    },
    onChange:function(hsb,hex,rgb,el,bySetColor) {
        
        jQuery(el).find('.arfhex').css('background','#'+hex);
        if(!bySetColor){ jQuery(el).val(hex); }
        var fid = jQuery(el).find('.arfhex').attr('data-fid');
        if( fid ){
            jQuery('#'+fid).val('#'+hex);
        }
        var did = fid.replace('arf_divider_bg_color_','');
        if( jQuery(el).attr('data-cls') == 'arf_clr_disable'){
            jQuery('#arf_divider_bg_color_disabled_'+did+'.arf_clr_disable .arfhex').css('background','#'+hex);
        }
    }
});
<?php } ?>
</script>
<!-- for color picker -->
