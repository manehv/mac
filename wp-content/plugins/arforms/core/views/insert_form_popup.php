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

global $armainhelper, $arformhelper;
?>
<script>
	
	function arfopenarfinsertform(){
		var modalwidth 	= jQuery(window).width();
		var left_width 	= Number(modalwidth) / 2;
			left_width 	= left_width - 280;
			
		var modalheight = jQuery(window).height();
		var top_height 	= Number(modalheight) / 2;
			top_height 	= top_height - 280;
		
		jQuery('#arfinsertform').css({left:left_width+'px', top:top_height+'px'});		
	}
	
	function changetopposition(myval){
		
		var modalheight = jQuery(window).height();
		var top_height 	= Number(modalheight) / 2;
		
		if(myval == "fly"){
			jQuery('#arfinsertform').css('top',(top_height-280)+'px');
		}else{
			jQuery('#arfinsertform').css('top',(top_height-280)+'px');
		}
	}

    function arfinsertform(){


        var form_id=jQuery("#arfaddformid").val();


        if(form_id==""){alert("<?php _e('Please select a form', 'ARForms') ?>");return;}
		
		jQuery('#arfaddtopageloader').show();
		
				jQuery('#arfaddtopageloader').hide();		
		
  				
				var titile_val = jQuery('#form_title_i').val();		
		 
				var title_qs= ( titile_val == 'yes' ) ? " title=true" : "";
		
		
				var description_qs= ( titile_val == 'yes' ) ? " description=true" : "";
		
				var shrt_type = jQuery('input[name="shortcode_type"]:checked').val();
				
				var link_type = jQuery('#link_type').val();		
				
				var link_position = jQuery('#link_position').val();
				
				var link_position_fly = jQuery('#link_position_fly').val();
						
				var modal_height = jQuery('#modal_height').val();
				
				var modal_width = jQuery('#modal_width').val(); 
                                
                                var bgcolor = jQuery('#arf_modal_btn_bg_color').val();
                                
                                var txtcolor = jQuery('#arf_modal_btn_txt_color').val();
				
                                
                                
				if( shrt_type == 'normal' )
				{
					setTimeout(function(){
						window.send_to_editor(" [ARForms id="+form_id+"]");
					},10);
				}
				else if( shrt_type == 'popup' ){
															
					setTimeout(function(){	
						var caption	= jQuery('#short_caption').val();
						if(link_type == 'sticky')
							window.send_to_editor(" [ARForms_popup id="+form_id+" desc='"+caption+"' type='"+link_type+"' position='"+link_position+"' height='"+modal_height+"' width='"+modal_width+"' bgcolor='"+bgcolor+"' txtcolor='"+txtcolor+"' ]");
						else if(link_type == 'fly')
						{
							var button_angle = jQuery('#button_angle').val();
							window.send_to_editor(" [ARForms_popup id="+form_id+" desc='"+caption+"' type='"+link_type+"' position='"+link_position_fly+"' height='"+modal_height+"' width='"+modal_width+"' angle='"+button_angle+"' bgcolor='"+bgcolor+"' txtcolor='"+txtcolor+"']");
						}
						else if(link_type == 'onload'){
							window.send_to_editor(" [ARForms_popup id="+form_id+" type='"+link_type+"' position='"+link_position_fly+"' height='"+modal_height+"' width='"+modal_width+"']");
						}else{
							window.send_to_editor(" [ARForms_popup id="+form_id+" desc='"+caption+"' type='"+link_type+"' height='"+modal_height+"' width='"+modal_width+"']");
						}
					}, 10);
											
				}				
				
				jQuery('[data-dismiss="arfmodal"]').trigger("click");
		
    }

    function frm_insert_display(){


        var display_id = jQuery("#frm_add_display_id").val();


        if(display_id==""){alert("<?php _e('Please select a custom display', 'ARForms') ?>");return;}


        var filter_qs=jQuery("#frm_filter_content").is(":checked") ? " filter=1" : "";


        var win = window.dialogArguments || opener || parent || top;


        win.send_to_editor("[display-frm-data id="+display_id+filter_qs+"]");


    }
	
	function arfchangepageload()
	{
		var is_onload = jQuery('input[name="open_type"]:checked').val();
		if( is_onload == 'yes' )
		{
			jQuery('#normal_link_type').hide();
			jQuery('#load_link_type_div').show();
		}
		else
		{
			jQuery('#load_link_type_div').hide();
			jQuery('#normal_link_type').show();
		}
	}   
</script>
<script>
jQuery(document).ready(function() {		
jQuery(".sltmodal select").selectpicker(); 
	jQuery('#shortcode_type_popup').click(function(){
		jQuery('#show_link_inner').slideUp();
		jQuery('#show_link_type').slideDown(700);		
	});
	jQuery('#shortcode_type_normal').click(function(){
		jQuery('#show_link_inner').slideDown();
		jQuery('#show_link_type').slideUp(700);		
	});
	
	
	jQuery('#link_type').change(function(){
		var show_link_type = jQuery('#link_type').val();
		//alert(show_link_type);
                var tid = jQuery('#arf_btn_txtcolor .arf_coloroption.arfhex').attr('data-fid');
                jQuery('#'+tid).val('#ffffff');
		if(show_link_type == 'sticky')
		{
			jQuery('#is_sticky').slideDown();
			jQuery('#is_fly').slideUp();
			jQuery('#button_angle_div').slideUp();
                        jQuery('#arfmodalbuttonstyles').slideDown();
                        jQuery("#arf_btn_bgcolor .arf_coloroption.arfhex").css('background','#93979d');
                        var fid = jQuery('#arf_btn_bgcolor .arf_coloroption.arfhex').attr('data-fid');
                        jQuery('#'+fid).val('#93979d');
                }
		else if(show_link_type == 'fly')
		{
			jQuery('#is_fly').slideDown();
			jQuery('#is_sticky').slideUp();
			jQuery('#button_angle_div').slideDown();
                        jQuery('#arfmodalbuttonstyles').slideDown();
                        jQuery("#arf_btn_bgcolor .arf_coloroption.arfhex").css('background','#2d6dae');
                        var fid = jQuery('#arf_btn_bgcolor .arf_coloroption.arfhex').attr('data-fid');
                        jQuery('#'+fid).val('#2d6dae');
		}
		else
		{
			jQuery('#is_sticky').slideUp();
			jQuery('#is_fly').slideUp();
			jQuery('#button_angle_div').slideUp();
                        jQuery('#arfmodalbuttonstyles').slideUp();
		}
		if( show_link_type == 'onload' ){
			jQuery('#shortcode_caption').slideUp();
		} else {
			jQuery('#shortcode_caption').slideDown();
		}
	});
        
        jQuery('#link_position_fly').change(function(){
            var position = jQuery(this).val();
            
            var color = (position == 'left') ? '#2d6dae' : '#8ccf7a';
            
            jQuery("#arf_btn_bgcolor .arf_coloroption.arfhex").css('background',color);
            var fid = jQuery('#arf_btn_bgcolor .arf_coloroption.arfhex').attr('data-fid');
            jQuery('#'+fid).val(color);
            
        });
        
        jQuery('#link_position').change(function(){
           var position = jQuery(this).val();
           var color = (['left','right','bottom'].indexOf(position) > -1) ? '#1bbae1' : '#93979d';
           
           jQuery("#arf_btn_bgcolor .arf_coloroption.arfhex").css('background',color);
           var fid = jQuery('#arf_btn_bgcolor .arf_coloroption.arfhex').attr('data-fid');
           jQuery('#'+fid).val(color);
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
</script>

<?php
wp_register_style('arfbootstrap-css',ARFURL.'/bootstrap/css/bootstrap.css');
$armainhelper->load_styles(array('arfbootstrap-css'));

wp_register_style('arfbootstrap-select-css',ARFURL.'/bootstrap/css/bootstrap-select.css');
$armainhelper->load_styles(array('arfbootstrap-select-css'));

wp_register_script('arfbootstrap-js',ARFURL.'/bootstrap/js/bootstrap.js');
$armainhelper->load_scripts(array('arfbootstrap-js'));

wp_register_script('arfbootstrap-select-js',ARFURL.'/bootstrap/js/bootstrap-select.js');
$armainhelper->load_scripts(array('arfbootstrap-select-js'));

wp_register_script('arforms_colpick-js',ARFURL.'/js/colpick.js');
$armainhelper->load_scripts(array('arforms_colpick-js'));

wp_register_style('arforms_colpick-css',ARFURL.'/css/colpick.css');
$armainhelper->load_styles(array('arforms_colpick-css'));

wp_register_script('arf-themepicker-js',ARFURL.'/js/jquery/jquery-ui-themepicker.js');
$armainhelper->load_scripts(array('arf-themepicker-js'));

?>
<style type="text/css">

@font-face {
    font-family: 'open_sansregular';
    src: url('<?php echo ARFURL;?>/fonts/opensans-regular-webfont.eot');
    src: url('<?php echo ARFURL;?>/fonts/opensans-regular-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?php echo ARFURL;?>/fonts/opensans-regular-webfont.woff') format('woff'),
         url('<?php echo ARFURL;?>/fonts/opensans-regular-webfont.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'open_sansbold';
    src: url('<?php echo ARFURL;?>/fonts/opensans-bold-webfont.eot');
    src: url('<?php echo ARFURL;?>/fonts/opensans-bold-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?php echo ARFURL;?>/fonts/opensans-bold-webfont.woff') format('woff'),
         url('<?php echo ARFURL;?>/fonts/opensans-bold-webfont.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'open_sansextrabold';
    src: url('<?php echo ARFURL;?>/fonts/opensans-extrabold-webfont.eot');
    src: url('<?php echo ARFURL;?>/fonts/opensans-extrabold-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?php echo ARFURL;?>/fonts/opensans-extrabold-webfont.woff') format('woff'),
         url('<?php echo ARFURL;?>/fonts/opensans-extrabold-webfont.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'open_sanssemibold';
    src: url('<?php echo ARFURL;?>/fonts/opensans-semibold-webfont.eot');
    src: url('<?php echo ARFURL;?>/fonts/opensans-semibold-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?php echo ARFURL;?>/fonts/opensans-semibold-webfont.woff') format('woff'),
         url('<?php echo ARFURL;?>/fonts/opensans-semibold-webfont.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'aileron_regular';
    src: url('<?php echo ARFURL;?>/fonts/aileron-regular-webfont.eot');
    src: url('<?php echo ARFURL;?>/fonts/aileron-regular-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?php echo ARFURL;?>/fonts/aileron-regular-webfont.woff') format('woff'),
         url('<?php echo ARFURL;?>/fonts/aileron-regular-webfont.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}

#arfinsertform.arfmodal {
	border-radius:0px;
	text-align:center;
	width:560px;
	height:auto;
    left:35%;
	border:none;
}
.arfmodal .btn-group.bootstrap-select 
{
	text-align:left;
}

.arfmodal .btn-group .btn.dropdown-toggle,.arfmodal .btn-group .arfbtn.dropdown-toggle {
	border: 1px solid #CCCCCC;
	background-color:#FFFFFF;
	background-image:none;
	box-shadow:none;
	outline:0 !important;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1) inset;
}
.arfmodal .btn-group.open .btn.dropdown-toggle,.arfmodal .btn-group.open .arfbtn.dropdown-toggle {
	border:solid 1px #CCCCCC;
	background-color:#FFFFFF;
	border-bottom-color:transparent;
	box-shadow:none;
	outline:0 !important;
	outline-style:none;
	border-bottom-left-radius:0px;
	border-bottom-right-radius:0px;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1) inset;
}
.arfmodal .btn-group.dropup.open .btn.dropdown-toggle, .arfmodal .btn-group.dropup.open .arfbtn.dropdown-toggle {
	border:solid 1px #CCCCCC;
	background-color:#FFFFFF;
	border-top-color:transparent;
	box-shadow:none;
	outline:0 !important;
	outline-style:none;
	border-top-left-radius:0px;
	border-top-right-radius:0px;
	border-bottom-left-radius:6px;
	border-bottom-right-radius:6px;
}
.arfmodal .btn-group .arfdropdown-menu {
	margin:0;
}
.arfmodal .btn-group.open .arfdropdown-menu {
	border:solid 1px #CCCCCC;
	box-shadow:none;
	border-top:none;
	margin:0;
	margin-top:-1px;
	border-top-left-radius:0px;
	border-top-right-radius:0px;	
}
.arfmodal .btn-group.dropup.open .arfdropdown-menu {
	border-top:solid 1px #CCCCCC;
	box-shadow:none;
	border-bottom:none;
	margin:0;
	margin-bottom:-1px;
	border-bottom-left-radius:0px;
	border-bottom-right-radius:0px;
	border-top-left-radius:6px;
	border-top-right-radius:6px;	
}
.arfmodal .btn-group.dropup.open .arfdropdown-menu .arfdropdown-menu.inner {
	border-top:none;
}
.arfmodal .btn-group.open ul.arfdropdown-menu {
	border:none;
}

.arfmodal .arfdropdown-menu > li {
	margin:0px;
}

.arfmodal .arfdropdown-menu > li > a {
	padding: 6px 12px;
	text-decoration:none;
}

.arfmodal .arfdropdown-menu > li:hover > a {
	background:#1BBAE1;
}

.arfmodal .bootstrap-select.btn-group, 
.arfmodal .bootstrap-select.btn-group[class*="span"] {
	margin-bottom:5px;
}

.arfmodal ul, .wrap ol {
	margin:0;
	padding:0;
	}
	
.arfmodal form {
	margin:0;
}	

.arfmodal label {
	display:inline;
	margin-left:5px;
}

.arfnewmodalclose
{
	font-size: 15px;
    font-weight: bold;
    height: 19px;
    position: absolute;
    right: 3px;
    top:5px;
    width: 19px;
	cursor:pointer;
	color:#D1D6E5;
} 
#arfinsertform
{
	text-align:center;
}
.newform_modal_title
{
	font-size:24px;
	font-family:'open_sansextrabold', Arial, Helvetica, Verdana, sans-serif;
	/*font-weight:bold;*/
	color:#d1d6e5;
	margin-top:14px;
}

#arfcontinuebtn
{
	background:#1bbae1;
	font-family:'open_sanssemibold', Arial, Helvetica, Verdana, sans-serif;
	/*font-weight:bold;*/
	font-size:18px;
	cursor:pointer;
	color:#ffffff;
	margin-top:10px;
	padding-top:18px;	
	height:42px;
}

.arfmodal .txtmodal1 
{
	height:36px;
	border:1px solid #cccccc;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
	color:#353942;
	background:#FFFFFF;
	font-family:'open_sansregular', Arial, Helvetica, Verdana, sans-serif;
	font-size:14px;
	margin:0px;
	letter-spacing:0.8px;
	padding:0px 10px 0 10px;
	width:360px;
	outline:none;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1) inset;
	-webkit-box-shadow: 0px 0px 1px rgba(0, 0, 0, 0), 0 1px 1px rgba(0, 0, 0, 0.1) inset;
	-moz-box-shadow: 0px 0px 1px rgba(0, 0, 0, 0), 0 1px 1px rgba(0, 0, 0, 0.1) inset;
	-webkit-box-sizing: content-box;
	-moz-box-sizing: content-box;
	box-sizing: content-box;
}
.arfmodal .txtmodal1:focus
{
	/*background:#eff3f5;*/
	border:1px solid #1BBAE1;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1) inset;
	-webkit-box-shadow: 0px 0px 1px rgba(0, 0, 0, 0), 0 1px 1px rgba(0, 0, 0, 0.1) inset;
	-moz-box-shadow: 0px 0px 1px rgba(0, 0, 0, 0), 0 1px 1px rgba(0, 0, 0, 0.1) inset;
	transition:none;
}
.newmodal_field_title
{
	margin:20px 0 10px 0;
	font-family:'open_sansbold', Arial, Helvetica, Verdana, sans-serif;
	/*font-weight:bold;*/
	font-size:14px;
	color:#353942;
}
.arfmodal input[class="rdomodal"] {
    display:none;
}

.arfmodal input[class="rdomodal"] + label {
    color:#333333;
    font-size:14px;
	font-family:'aileron_regular', Arial, Helvetica, Verdana, sans-serif;
}

.arfmodal input[class="rdomodal"] + label span {
    display:inline-block;
    width:19px;
    height:19px;
    margin:-1px 4px 0 0;
    vertical-align:middle;
    background:url(<?php echo ARFURL;?>/images/dark-radio-green.png) -37px top no-repeat;
    cursor:pointer;
}

.arfmodal input[class="rdomodal"]:checked + label span
{
    background:url(<?php echo ARFURL;?>/images/dark-radio-green.png) -56px top no-repeat;
}
.arfmodalfields
{
	display:table;
    text-align: center;
	margin-top:10px;
	width:100%;
}
.arfmodalfields .arfmodalfield_left
{
	display:table-cell;
	text-align:right;
	width:45%;
	padding-right:20px;	
	font-family:'open_sansbold', Arial, Helvetica, Verdana, sans-serif;
	font-weight:normal;
	font-size:14px;
	color:#353942;
}
.arfmodalfields .arfmodalfield_right
{
	display:table-cell;
	text-align:left;
}
.arfmodal .arf_px
{
	font-family:'aileron_regular', Arial, Helvetica, Verdana, sans-serif;
	font-size:12px;
	color:#353942;	
}

/* RTL Language */
body.rtl .arfnewmodalclose
{
	right:auto;
	left:3px;
}
body.rtl .arfmodalfields .arfmodalfield_left
{
	text-align:left;
}
body.rtl .arfmodalfields .arfmodalfield_right
{
	text-align:right;
	padding-right:20px;	
}
body.rtl .arfmodal .bootstrap-select.btn-group .arfbtn .filter-option
{
	top:5px;
	right:8px;
	left:auto;
}

body.rtl .arfmodal .bootstrap-select.btn-group .arfbtn .caret
{
	left:8px;
	right:auto;
}
body.rtl .arfmodal .btn-group.open .arfdropdown-menu {
	text-align:right;
}
.arf_coloroption_sub{
    border: 4px solid #dcdfe4;
    border-radius: 2px;
    cursor: pointer;
    height: 22px;
    width: 47px;
    margin-left:22px;
    margin-top:5px;
}

.arf_coloroption{
    cursor: pointer;
    height: 22px;
    width: 47px;
}

.arf_coloroption_subarrow_bg{
    background: none repeat scroll 0 0 #dcdfe4;
    height: 8px;
    margin-left: 39px;
    margin-top: -8px;
    text-align: center;
    vertical-align: middle;
    width: 8px;
}

.arf_coloroption_subarrow{
    background: <?php echo "url(".ARFURL."/images/colpickarrow.png) no-repeat center center"; ?>;
    height: 3px;
    padding-left: 5px;
    padding-top: 6px;
    width: 5px;
}

.colpick_hex{
    z-index:99999;
    top:-30px;
}
</style>        

<div id="arfinsertform" style="display:none;"  class="arfmodal hide fade">
	
    <div class="arfnewmodalclose" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/close-button.png';?>" align="absmiddle" /></div>
    
    <div class="newform_modal_title_container">
    	<div class="newform_modal_title"><img src="<?php echo ARFIMAGESURL.'/add-newform-icon.png'; ?>" align="absmiddle" />&nbsp;<?php _e('ADD ARFORMS FORM','ARForms');?></div>
    </div>	
    
    <input type="hidden" id="form_title_i" value="" />
    <div class="newform_modal_fields" style="margin-bottom:30px;">
    	
        <div class="newmodal_field_title"><?php _e('Select a form to insert into page','ARForms');?>&nbsp;<span class="newmodal_required" style="color:#ff0000; vertical-align:top;">*</span></div>
        <div class="newmodal_field">
        	<div class="sltmodal">
      		<?php $arformhelper->forms_dropdown_new( 'arfaddformid', '', 'Select form' )?>
     		</div><div id="form_name_new_required" class="arferrmessage" style="display:none;"><?php _e('Please enter form name','ARForms');?></div>
        </div>
        
        <div class="newmodal_field_title"><?php _e('How you want to include this form into page?','ARForms');?></div>
        <div class="newmodal_field"><input type="radio" class="rdomodal" checked="checked" name="shortcode_type" value="normal" id="shortcode_type_normal"  /><label for="shortcode_type_normal" <?php if( is_rtl() ){ echo 'style="float:right; margin-right:167px;"';}?>><span <?php if( is_rtl() ){ echo 'style="margin-left:5px;"'; }?>></span><?php _e('Internal','ARForms');?></label>
       &nbsp;&nbsp;&nbsp;&nbsp;
      <input type="radio" class="rdomodal" name="shortcode_type" value="popup" id="shortcode_type_popup" /><label for="shortcode_type_popup" <?php if( is_rtl() ){ echo 'style="float:right;"';}?>><span <?php if( is_rtl() ){ echo 'style="margin-left:5px;"'; }?>></span><?php _e('External popup window','ARForms');?></label></div>
      
     <div id="show_link_type" style="display:none; margin-top:15px;">   
     	
        <div class="arfmodalfields" id="normal_link_type"> 	
     		<div class="arfmodalfield_left"><?php _e('Link Type?', 'ARForms');?></div>
          	<div class="arfmodalfield_right">
                <div class="sltmodal" style="float:none; font-size:15px; <?php if( is_rtl() ){ echo 'text-align:right;'; }else{ echo 'text-align:left;'; }?>">
                    <select name="link_type" id="link_type" data-width="150px" onchange="javascript:changetopposition(this.value);">
                        <option value="link" selected="selected"><?php _e('Link','ARForms');?></option>
                        <option value="button"><?php _e('Button','ARForms');?></option>
                        <option value="sticky"><?php _e('Sticky','ARForms');?></option>
                        <option value="fly"><?php _e('Fly','ARForms');?></option>
                        <option value="onload"><?php _e('On Page Load','ARForms');?></option>
                    </select>
                </div>
          	</div>          
        </div>
        
        <div class="arfmodalfields" id="shortcode_caption"> 	
     		<div class="arfmodalfield_left"><?php _e('Caption :','ARForms');?></div>
          	<div class="arfmodalfield_right">
                <input type="text" name="short_caption" id="short_caption" value="Click here to open Form" class="txtmodal1" style="width:250px;" />
          	</div>          
        </div>
        
        <div class="arfmodalfields" id="is_sticky" style="display:none;"> 	
     		<div class="arfmodalfield_left"><?php _e('Link Position?','ARForms');?></div>
          	<div class="arfmodalfield_right">
                <div class="sltmodal" style="float:none; font-size:15px;<?php if( is_rtl() ){ echo 'text-align:right;'; }else{ echo 'text-align:left;'; }?>">
                    <select name="link_position" id="link_position" data-width="150px">
                	<option value="top" selected="selected"><?php _e('Top','ARForms');?></option>
                    	<option value="bottom"><?php _e('Bottom','ARForms');?></option>
                        <option value="left" ><?php _e('Left','ARForms');?></option>
                    	<option value="right"><?php _e('Right','ARForms');?></option>
                    </select>
                </div>
          	</div>          
        </div>
        
        <div class="arfmodalfields" id="is_fly" style="display:none;"> 	
     		<div class="arfmodalfield_left"><?php _e('Link Position?','ARForms');?></div>
          	<div class="arfmodalfield_right">
                <div class="sltmodal" style="float:none; font-size:15px; <?php if( is_rtl() ){ echo 'text-align:right;'; }else{ echo 'text-align:left;'; }?>">
                    <select name="link_position_fly" id="link_position_fly" data-width="150px" onchange="arfchangeflybtn();">
                		<option value="left" selected="selected"><?php _e('Left','ARForms');?></option>
                    	<option value="right"><?php _e('Right','ARForms');?></option>
                	</select>
                </div>
          	</div>          
        </div>
         
         <div class="arfmodalfields" id="arfmodalbuttonstyles" style="display:none;">
             <div class="arfmodalfield_left" style='vertical-align:middle;'><?php _e('Colors :','ARForms'); ?></div>
             <div class="arfmodalfield_right">
                 <div style="display:inline">
                     <div class="height_setting" style="display:inline;float:left;width:140px;"><div style="margin-top: 0px;padding-left: 10px;"><?php _e('Background','ARForms');?></div>
                        <div style="display: inline-block;" id="arf_btn_bgcolor" class="arf_coloroption_sub">
                            <div data-fid="arf_modal_btn_bg_color" class="arf_coloroption arfhex"></div>
                            <div class="arf_coloroption_subarrow_bg">
                                <div class="arf_coloroption_subarrow"></div>
                            </div>
                        </div>
                        <input type="hidden" name="arf_modal_btn_bg_color" id="arf_modal_btn_bg_color" class="txtmodal1" />
                     </div>
                     <div class="height_setting" style="display:inline;float:left;width:140px;"><div style="margin-top: 4px;padding-left: 30px;"><?php _e('Text','ARForms');?></div>
                         <div style="display: inline-block;" id="arf_btn_txtcolor" class="arf_coloroption_sub">
                            <div style="background:#ffffff;" data-fid="arf_modal_btn_txt_color" class="arf_coloroption arfhex"></div>
                            <div class="arf_coloroption_subarrow_bg">
                                <div class="arf_coloroption_subarrow"></div>
                            </div>
                        </div>
                        <input type="hidden" name="arf_modal_btn_txt_color" id="arf_modal_btn_txt_color" class="txtmodal1" />
                     </div>
                 </div>
             </div>
         </div>
        
        <div class="arfmodalfields"> 	
     		<div class="arfmodalfield_left" style="vertical-align: middle;"><?php _e('Size :', 'ARForms'); ?></div>
          	<div class="arfmodalfield_right">
            	<div style="display:inline;">
                    <div class="height_setting" style="display: inline;float: left;width: 140px;"><input type="text" class="txtmodal1" name="modal_height" id="modal_height" value="540" style="width:70px;" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms');?></span><br/><div style="margin-top: 4px;padding-left: 22px;"><?php _e('Height','ARForms');?></div></div>                    
                    <div class="height_setting" style="display: inline-block; float: none;"><input type="text" class="txtmodal1" name="modal_width" id="modal_width" value="800" style="width:70px;" />&nbsp;<span class="arf_px"><?php _e('px', 'ARForms');?></span><br/><div style="margin-top: 4px;padding-left: 22px;"><?php _e('Width','ARForms');?></div></div>
                </div>
           	</div>          
        </div>
        
        <div class="arfmodalfields" id="button_angle_div" style="display:none;"> 	
     		<div class="arfmodalfield_left" style="padding-top: 10px; vertical-align: top;"><?php _e('Button angle :', 'ARForms'); ?></div>
          	<div class="arfmodalfield_right">
            	<div class="sltmodal" style="float:none; font-size:15px;display:inline-block; <?php if( is_rtl() ){ echo 'text-align:right;'; }else{ echo 'text-align:left;'; }?>">
                <select name="button_angle" id="button_angle" data-width="70px" onchange="changeflybutton();">
                		<option value="0" selected="selected">0</option>
                        <option value="90" >90</option>
                        <option value="-90" >-90</option>
                </select>
                </div>
                
            </div>          
        </div>
        
     </div>
     
    </div>
    	
    <div style="clear:both;"></div>
    <div id="arfaddtopageloader" align="center" style="text-align:center; display:none; padding-bottom:10px;"><img src="<?php echo ARFIMAGESURL.'/ajax_loader_gray_32.gif'; ?>" align="absmiddle" /></div>
    <div style="clear:both;"></div>
    <div id="arfcontinuebtn" onclick="arfinsertform();"><img src="<?php echo ARFIMAGESURL.'/addtopage-icon.png'; ?>" align="absmiddle" style="margin-right:10px;" /><?php _e('Add to page', 'ARForms'); ?></div>
</div>    