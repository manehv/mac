<style>
<?php
global $wpec_ei_admin_interface, $wpec_ei_fonts_face;

// Email Inquiry Button Style
global $wpec_email_inquiry_customize_email_button;
extract($wpec_email_inquiry_customize_email_button);
?>
@charset "UTF-8";
/* CSS Document */

/* Email Inquiry Button Style */
.pcf_button_container { 
	margin-bottom: <?php echo $inquiry_button_margin_bottom; ?>px !important;
	margin-top: <?php echo $inquiry_button_margin_top; ?>px !important;
	margin-left: <?php echo $inquiry_button_margin_left; ?>px !important;
	margin-right: <?php echo $inquiry_button_margin_right; ?>px !important;
}
body .pcf_button_container .pcf_contact_buton {
	position: relative !important;
	cursor:pointer;
	display: inline-block !important;
	line-height: 1 !important;
}
body .pcf_button_container .pcf_email_button {
	padding: 7px 8px !important;
	margin:0;
	
	/*Background*/
	background-color: <?php echo $inquiry_button_bg_colour; ?> !important;
	background: -webkit-gradient(
					linear,
					left top,
					left bottom,
					color-stop(.2, <?php echo $inquiry_button_bg_colour_from; ?>),
					color-stop(1, <?php echo $inquiry_button_bg_colour_to; ?>)
				) !important;;
	background: -moz-linear-gradient(
					center top,
					<?php echo $inquiry_button_bg_colour_from; ?> 20%,
					<?php echo $inquiry_button_bg_colour_to; ?> 100%
				) !important;;
	
		
	/*Border*/
	<?php echo $wpec_ei_admin_interface->generate_border_css( $inquiry_button_border ); ?>
	
	/* Shadow */
	<?php echo $wpec_ei_admin_interface->generate_shadow_css( $inquiry_button_shadow ); ?>
	
	/* Font */
	<?php echo $wpec_ei_fonts_face->generate_font_css( $inquiry_button_font ); ?>
	
	text-align: center !important;
	text-shadow: 0 -1px 0 hsla(0,0%,0%,.3);
	text-decoration: none !important;
}

body .pcf_button_container .pcf_hyperlink_text {
	/* Font */
	<?php echo $wpec_ei_fonts_face->generate_font_css( $inquiry_hyperlink_font ); ?>
}

body .pcf_button_container .pcf_hyperlink_text:hover {
	color: <?php echo $inquiry_hyperlink_hover_color ; ?> !important;	
}

<?php
// Email Inquiry Form Button Style
global $wpec_email_inquiry_customize_email_popup;
extract($wpec_email_inquiry_customize_email_popup);
?>

/* Email Inquiry Form Style */
.pcf_contact_form * {
	box-sizing:content-box !important;
	-moz-box-sizing:content-box !important;
	-webkit-box-sizing:content-box !important;	
}
.email_inquiry_cb , #fancybox-content, #fancybox-wrap {
	box-sizing:content-box !important;
	-moz-box-sizing:content-box !important;
	-webkit-box-sizing:content-box !important;	
}
.email_inquiry_cb #cboxLoadedContent, .pcf_contact_form, #fancybox-content > div {
	background-color: <?php echo $inquiry_form_bg_colour; ?> !important;	
}

/* Email Inquiry Form Button Style */
body .pcf_form_button, .pcf_form_button {
	position: relative !important;
	cursor:pointer;
	display: inline-block !important;
	line-height: 1 !important;
}
body .pcf_form_button, .pcf_form_button {
	padding: 7px 8px !important;
	margin:0;
	
	/*Background*/
	background-color: <?php echo $inquiry_contact_button_bg_colour; ?> !important;
	background: -webkit-gradient(
					linear,
					left top,
					left bottom,
					color-stop(.2, <?php echo $inquiry_contact_button_bg_colour_from; ?>),
					color-stop(1, <?php echo $inquiry_contact_button_bg_colour_to; ?>)
				) !important;;
	background: -moz-linear-gradient(
					center top,
					<?php echo $inquiry_contact_button_bg_colour_from; ?> 20%,
					<?php echo $inquiry_contact_button_bg_colour_to; ?> 100%
				) !important;;
	
	/*Border*/
	<?php echo $wpec_ei_admin_interface->generate_border_css( $inquiry_contact_button_border ); ?>
	
	/* Shadow */
	<?php echo $wpec_ei_admin_interface->generate_shadow_css( $inquiry_contact_button_shadow ); ?>
	
	/* Font */
	<?php echo $wpec_ei_fonts_face->generate_font_css( $inquiry_contact_button_font ); ?>
		
	text-align: center !important;
	text-shadow: 0 -1px 0 hsla(0,0%,0%,.3);
	text-decoration: none !important;
}

/* Contact Form Heading */
h1.pcf_result_heading {
	<?php echo $wpec_ei_fonts_face->generate_font_css( $inquiry_contact_heading_font ); ?>
}
</style>
