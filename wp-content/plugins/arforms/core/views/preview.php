<?php
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/

global $maincontroller, $arrecordcontroller;

function my_function_admin_bar(){ return false; }
add_filter( 'show_admin_bar' , 'my_function_admin_bar');

remove_action( 'wp_head', 'wc_products_rss_feed' );
remove_action( 'wp_head', 'wc_generator_tag' );		
remove_action( 'get_the_generator_html', 'wc_generator_tag' );				
remove_action( 'get_the_generator_xhtml', 'wc_generator_tag' );

?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
<script type="text/javascript">
var ajaxurl = '<?php echo get_admin_url()."admin-ajax.php"?>';
</script>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php bloginfo('name'); ?></title>

<?php //wp_head();
$maincontroller->front_head(); ?>
<style type="text/css">
input, select, textarea
{
	outline:none;
}
body{ padding:20px; }
.arf_form .arfpreivewform .arf_image_field.ui-draggable img { border:2px solid transparent !important; padding:2px !important; }
.arf_form .arfpreivewform .arf_image_field.ui-draggable img:hover { border:2px dashed #077bdd !important; cursor:move !important; box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.4) !important; padding:2px !important; }
.arf_form .arf_image_field.ui-draggable:hover img,
.arf_form .arf_image_field.ui-draggable img:active,
.arf_form .arf_image_field.ui-draggable img:focus,
.arf_form .arf_image_field.ui-draggable img:hover { -webkit-border:2px dashed #077bdd !important; -moz-border:2px dashed #077bdd !important; border:2px dashed #077bdd !important; cursor:move !important; box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.4) !important; padding:2px !important; }
</style>
<?php wp_print_styles('arfbootstrap-css');
		wp_print_styles('arfbootstrap-select');
		wp_print_styles('arfbootstrap-timepicker');
		wp_print_styles('arfbootstrap-slider');
		
		wp_print_styles('arfdisplaycss');
		
		wp_print_scripts('jquery-validation');	
		wp_print_scripts('jquery-bootstrap-slect');
		
		wp_print_scripts('jquery-icheck');
		wp_print_scripts('arfbootstrap-js');
		wp_print_scripts('arf-conditional-logic-js');
		
		
		do_action('include_outside_js_css_for_preview_header');
		
		
		
$wp_upload_dir 	= wp_upload_dir();
if(is_ssl())
{
	$upload_main_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/maincss');
}
else
{
	$upload_main_url = 	$wp_upload_dir['baseurl'].'/arforms/maincss';
}

wp_register_style('arfformscss', $upload_main_url.'/maincss_'.$form->id.'.css?'.time() );
wp_print_styles('arfformscss');		
?>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function(){
		setTimeout( function(){
			var width = jQuery('.arfshowmainform.arfpreivewform').find('.arf_fieldset').width();
			jQuery('.arfshowmainform.arfpreivewform').find('.arf_prefix_suffix_wrapper').css('max-width',width+'px');
		},500);
	});
	
	jQuery(window).resize(function(){
		setTimeout( function(){
			var width = jQuery('.arfshowmainform.arfpreivewform').find('.arf_fieldset').width();
			jQuery('.arfshowmainform.arfpreivewform').find('.arf_prefix_suffix_wrapper').css('max-width',width+'px');
		},500);
	});
</script>
</head>

<body style=" background:none; background-color:#FFFFFF;">
<?php 
global $wpdb;

$res = $wpdb->get_results( $wpdb->prepare("SELECT options FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $form->id), 'ARRAY_A');
if( $res )
	$res = $res[0];

$res['options'] = isset($res['options']) ? $res['options'] : '';
		
$values = maybe_unserialize($res['options']);

$form_style_css = maybe_unserialize($form->form_css);

$values['display_title_form'] = isset($values['display_title_form']) ? $values['display_title_form'] : '';		
if( $values['display_title_form'] == '0' and $new =='list' ) {	
	$title = false; 
	$description = false;
} else {
	$title = true; 	
	$description = true;	
}
$checkradio_property = "";
if(@$_REQUEST['checkradiostyle']!="")
{
	if(@$_REQUEST['checkradiostyle']!="none")
	{
		if(@$_REQUEST['checkradiocolor']!="default" && @$_REQUEST['checkradiocolor']!="")
		{
			if(@$_REQUEST['checkradiostyle']=="futurico" || @$_REQUEST['checkradiostyle']=="polaris")
			{
				$checkradio_property = @$_REQUEST['checkradiostyle'];
			}
			else
			{
				$checkradio_property = @$_REQUEST['checkradiostyle']."-".$_REQUEST['checkradiocolor'];
			}	
		}
		else
		{
			$checkradio_property = @$_REQUEST['checkradiostyle'];
		}
	}
	else
	{
		$checkradio_property = "";
	}	
}
else
{
	if($form_style_css['arfcheckradiostyle']!="")
	{
		if($form_style_css['arfcheckradiostyle']!="none")
		{
			if($form_style_css['arfcheckradiocolor']!="default" && $form_style_css['arfcheckradiocolor']!="")
			{
				if($form_style_css['arfcheckradiostyle']=="futurico" || $form_style_css['arfcheckradiostyle']=="polaris")
				{
					$checkradio_property = $form_style_css['arfcheckradiostyle'];
				}
				else
				{
					$checkradio_property = $form_style_css['arfcheckradiostyle']."-".$form_style_css['arfcheckradiocolor'];
				}	
			}
			else
			{
				$checkradio_property = $form_style_css['arfcheckradiostyle'];
			}
		}
		else
		{
			$checkradio_property = "";
		}	
	}
}
?>	
<div id="arfdevicebody" class="arfdevicecomputer" align="center" style="width:100%; max-width:100%; margin:0 auto;"><?php echo $arrecordcontroller->show_form($form->id, '', $title, $description, true) ?></div>
<?php
wp_print_scripts('jquery-ui-core');
wp_print_scripts('jquery-ui-draggable');
wp_print_scripts('jquery-ui-progressbar');
wp_print_scripts('jquery-effects-slide');
// sliders and timepicker
wp_print_scripts('arfbootstrap-timepicker-js','','','',false);
wp_print_scripts('arfbootstrap-modernizr-js','','','',false);
wp_print_scripts('arfbootstrap-slider-js','','','',false);
		
wp_print_styles('form_custom_css');

wp_register_script('arfdatepicker', ARFURL . '/js/jquery.ui.datepicker.1.7.3.js');
wp_print_scripts('arfdatepicker');

do_action('include_outside_js_css_for_preview_footer');


?>
<?php $arrecordcontroller->footer_js(true); 
if($checkradio_property!="")
{
?>
<?php }
if( isset($_GET['is_editorform']) && $_GET['is_editorform'] == '1' ) {
?>
<script type="text/javascript" language="javascript">
jQuery(document).ready(function(){
	// for drag and drop image field
	jQuery('.arfpreivewform .arf_image_horizontal_center').each(function(){
		var top = jQuery(this).attr('data-ctop');
		jQuery(this).css('top', '').css('position', 'inherit');
		jQuery(this).find('.arf_image_field').css('top', top);
	});
	jQuery('.arfpreivewform .arf_image_field').draggable({ 
			containment:'parent',
			cursor: "move",
			scroll: false,
			iframeFix: true,
			drag: function(event, ui) {
					jQuery(this).css('top', ui.position.top+'px');
					jQuery(this).css('left', ui.position.left+'px');
			},
			stop: function(event, ui) { 
					jQuery(this).css('top', ui.position.top+'px');
					jQuery(this).css('left', ui.position.left+'px');
				var field_id = jQuery(this).attr('id');
					field_id = field_id.replace('arf_imagefield_', '');
				window.parent.change_image_field_pos( field_id, ui.position.top, ui.position.left ); 
			} 
	});
	// for drag and drop image field end
});
</script>
<?php } ?>
</body>

</html>