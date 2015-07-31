<?php
/**
 * WPEC PCF Hook Filter
 *
 * Table Of Contents
 *
 * hide_addcartbt()
 * add_contact_button()
 * script_show_contact_button()
 * show_contact_button()
 * pcf_contact_popup()
 * pcf_contact_action()
 * add_style_header()
 * footer_print_scripts()
 * script_contact_popup()
 * admin_footer_scripts()
 * a3_wp_admin()
 * admin_sidebar_menu_css()
 * plugin_extra_links()
 */
class WPEC_PCF_Hook_Filter
{
	
	public static function hide_addcartbt() {
		global $post;
		$product_id = $post->ID;
		
		if ( WPEC_PCF_Functions::check_hide_add_cart_button($product_id) && $post->post_type == 'wpsc-product') {
		?>
        <style type="text/css">
			body input#product_<?php echo $product_id; ?>_submit_button, input#product_<?php echo $product_id; ?>_submit_button { display:none !important; visibility:hidden !important; height:0 !important;}
			body button#product_<?php echo $product_id; ?>_submit_button, button#product_<?php echo $product_id; ?>_submit_button { display:none !important; visibility:hidden !important; height:0 !important;}
			body .product_<?php echo $product_id; ?> .wpsc_buy_button, .product_<?php echo $product_id; ?> .wpsc_buy_button { display:none !important; visibility:hidden !important; height:0 !important; }
		</style>
        <?php
		}
	}
	
	public static function add_contact_button() {
		WPEC_PCF_Hook_Filter::show_contact_button(false);
	}
	
	public static function script_show_contact_button() {
		WPEC_PCF_Hook_Filter::show_contact_button(true);
	}
	
	public static function show_contact_button($use_script=true) {
		global $post;
		global $wpec_email_inquiry_global_settings;
		global $wpec_email_inquiry_customize_email_button;
		
		$product_id = $post->ID;
		
		$email_inquiry_button_class = 'pcf_contact_buton';
		
		$inquiry_single_only = $wpec_email_inquiry_global_settings['inquiry_single_only'];
		
		$inquiry_button_type = $wpec_email_inquiry_customize_email_button['inquiry_button_type'];
		
		$inquiry_text_before = $wpec_email_inquiry_customize_email_button['inquiry_text_before'];
		
		$inquiry_hyperlink_text = $wpec_email_inquiry_customize_email_button['inquiry_hyperlink_text'];
		if (trim($inquiry_hyperlink_text) == '') $inquiry_hyperlink_text = __( 'Click Here', 'wpec_pcf' );
		
		$inquiry_trailing_text = $wpec_email_inquiry_customize_email_button['inquiry_trailing_text'];
		
		$inquiry_button_title = $wpec_email_inquiry_customize_email_button['inquiry_button_title'];
		if (trim($inquiry_button_title) == '') $inquiry_button_title = __('Product Enquiry', 'wpec_pcf');
		
		$inquiry_button_position = $wpec_email_inquiry_customize_email_button['inquiry_button_position'];
		
		$inquiry_button_class = '';
		if ( trim( $wpec_email_inquiry_customize_email_button['inquiry_button_class'] ) != '') $inquiry_button_class = $wpec_email_inquiry_customize_email_button['inquiry_button_class'];
		
		$button_link = '';
		if (trim($inquiry_text_before) != '') $button_link .= '<span class="pcf_text_before pcf_text_before_'.$product_id.'">'.trim($inquiry_text_before).'</span> ';
		$button_link .= '<a class="pcf_hyperlink_text pcf_hyperlink_text_'.$product_id.' '. $email_inquiry_button_class .'" id="pcf_contact_button_'.$product_id.'" product_name="'.addslashes($post->post_title).'" product_id="'.$product_id.'">'.$inquiry_hyperlink_text.'</a>';
		if (trim($inquiry_trailing_text) != '') $button_link .= ' <span class="pcf_trailing_text pcf_trailing_text_'.$product_id.'">'.trim($inquiry_trailing_text).'</span>';
		
		$button_button = '<a class="pcf_email_button pcf_button_'.$product_id.' '. $email_inquiry_button_class .' '.$inquiry_button_class.'" id="pcf_contact_button_'.$product_id.'" product_name="'.addslashes($post->post_title).'" product_id="'.$product_id.'"><span>'.$inquiry_button_title.'</span></a>';
		
		if ( ( is_singular('wpsc-product') || $inquiry_single_only != 'yes' ) && WPEC_PCF_Functions::check_add_email_inquiry_button( $product_id ) && $post->post_type == 'wpsc-product') {
			add_action('wp_footer', array('WPEC_PCF_Hook_Filter', 'footer_print_scripts') );
			$button_ouput = '<span class="pcf_button_container">';
			if ($inquiry_button_type == 'link') $button_ouput .= $button_link;
			else $button_ouput .= $button_button;
			
			$button_ouput .= '</span>';
		?>
       		
        <?php
			if ($use_script) {
				if ($inquiry_button_position == 'above') {
		?>
				<script type="text/javascript">
                    (function($){		
                        $(function(){
                            if($("#pcf_contact_button_<?php echo $product_id; ?>").length <= 0){
								var add_cart_float = '';
                                if($("input#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("input#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("input#product_<?php echo $product_id; ?>_submit_button").before('<?php echo $button_ouput; ?><br />');
                                }else if($("button#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("button#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("button#product_<?php echo $product_id; ?>_submit_button").before('<?php echo $button_ouput; ?><br />');
                                }else if($(".product_view_<?php echo $product_id; ?>").length > 0){
                                    $(".product_view_<?php echo $product_id; ?>").find(".more_details").before('<?php echo $button_ouput; ?><br />');
                                }else{
									add_cart_float = $("input.wpsc_buy_button").css("float");
                                    $("input.wpsc_buy_button").before('<?php echo $button_ouput; ?><br />');
                                }
								$("#pcf_contact_button_<?php echo $product_id; ?>").parent(".pcf_button_container").css("float", add_cart_float);
                            }
                        });		  
                    })(jQuery);
                </script>
        <?php		
				} else {
		?>
				<script type="text/javascript">
                    (function($){		
                        $(function(){
                            if($("#pcf_contact_button_<?php echo $product_id; ?>").length <= 0){
								var add_cart_float = '';
                                if($("input#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("input#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("input#product_<?php echo $product_id; ?>_submit_button").after('<br /><?php echo $button_ouput; ?>');
                                }else if($("button#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("button#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("button#product_<?php echo $product_id; ?>_submit_button").after('<br /><?php echo $button_ouput; ?>');
                                }else if($(".product_view_<?php echo $product_id; ?>").length > 0){
                                    $(".product_view_<?php echo $product_id; ?>").find(".more_details").after('<br /><?php echo $button_ouput; ?>');
                                }else{
									add_cart_float = $("input.wpsc_buy_button").css("float");
                                    $("input.wpsc_buy_button").after('<br /><?php echo $button_ouput; ?>');
                                }
								$("#pcf_contact_button_<?php echo $product_id; ?>").parent(".pcf_button_container").css("float", add_cart_float);
                            }
                        });		  
                    })(jQuery);
                </script>
        <?php
				}
				if ( WPEC_PCF_Functions::check_hide_add_cart_button( $product_id ) && $post->post_type == 'wpsc-product') {
		?>
        		<script type="text/javascript">
                    (function($){		
                        $(function(){
							if($("input#product_<?php echo $product_id; ?>_submit_button").length > 0){
								$("input#product_<?php echo $product_id; ?>_submit_button").hide();
							} else if($("button#product_<?php echo $product_id; ?>_submit_button").length > 0){
								$("button#product_<?php echo $product_id; ?>_submit_button").hide();
							}
                        });		  
                    })(jQuery);
                </script>
        <?php		
				}
			} else {
				echo $button_ouput;
			}
		}
	}
	
	public static function pcf_contact_popup() {
		
		global $wpec_email_inquiry_contact_form_settings;
		global $wpec_email_inquiry_customize_email_popup;
		global $wpec_email_inquiry_customize_email_button;
		
		$pcf_contact_action = wp_create_nonce("pcf_contact_action");
		$product_id = $_REQUEST['product_id'];
		$product_name = get_the_title($product_id);
		
		$inquiry_button_title = $wpec_email_inquiry_customize_email_button['inquiry_button_title'];
		if (trim($inquiry_button_title) == '') $inquiry_button_title = __('Product Enquiry', 'wpec_pcf');
		
		$inquiry_text_before = $wpec_email_inquiry_customize_email_button['inquiry_text_before'];
		
		$inquiry_hyperlink_text = $wpec_email_inquiry_customize_email_button['inquiry_hyperlink_text'];
		if (trim($inquiry_hyperlink_text) == '') $inquiry_hyperlink_text = __('Click Here', 'wpec_pcf');
		
		$inquiry_trailing_text = $wpec_email_inquiry_customize_email_button['inquiry_trailing_text'];
		
		if ( trim( $wpec_email_inquiry_customize_email_popup['inquiry_contact_heading'] ) != '') {
			$inquiry_contact_heading = $wpec_email_inquiry_customize_email_popup['inquiry_contact_heading'];
		} else {
			$inquiry_button_type = $wpec_email_inquiry_customize_email_button['inquiry_button_type'];
			
			if ($inquiry_button_type == 'link') $inquiry_contact_heading = $inquiry_text_before .' '. $inquiry_hyperlink_text .' '.$inquiry_trailing_text;
			else $inquiry_contact_heading = $inquiry_button_title;
		}
		
		if ( trim( $wpec_email_inquiry_customize_email_popup['inquiry_contact_text_button'] ) != '') $inquiry_contact_text_button = $wpec_email_inquiry_customize_email_popup['inquiry_contact_text_button'];
		else $inquiry_contact_text_button = __('SEND', 'wpec_pcf');
		
		$inquiry_contact_button_class = '';
		$inquiry_contact_form_class = '';
		if ( trim( $wpec_email_inquiry_customize_email_popup['inquiry_contact_button_class'] ) != '') $inquiry_contact_button_class = $wpec_email_inquiry_customize_email_popup['inquiry_contact_button_class'];
		if ( trim( $wpec_email_inquiry_customize_email_popup['inquiry_contact_form_class'] ) != '') $inquiry_contact_form_class = $wpec_email_inquiry_customize_email_popup['inquiry_contact_form_class'];
		
		$wpec_email_inquiry_form_class = 'pcf_contact_form';
		
	?>	
<div class="<?php echo $wpec_email_inquiry_form_class; ?> <?php echo $inquiry_contact_form_class; ?>">
<div style="padding:10px;">
	<h1 class="pcf_result_heading"><?php echo $inquiry_contact_heading; ?></h1>
	<div class="pcf_contact_content" id="pcf_contact_content_<?php echo $product_id; ?>">
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_name_<?php echo $product_id; ?>"><?php _e('Name','wpec_pcf'); ?> <span class="pcf_required">*</span></label> 
			<input type="text" class="your_name" name="your_name" id="your_name_<?php echo $product_id; ?>" value="" /></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_email_<?php echo $product_id; ?>"><?php _e('Email Address','wpec_pcf'); ?> <span class="pcf_required">*</span></label>
			<input type="text" class="your_email" name="your_email" id="your_email_<?php echo $product_id; ?>" value="" /></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_phone_<?php echo $product_id; ?>"><?php _e('Phone','wpec_pcf'); ?> <span class="pcf_required">*</span></label> 
			<input type="text" class="your_phone" name="your_phone" id="your_phone_<?php echo $product_id; ?>" value="" /></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label"><?php _e('Subject','wpec_pcf'); ?> </label> 
			<?php echo $product_name; ?></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_message_<?php echo $product_id; ?>"><?php _e('Message','wpec_pcf'); ?></label> 
			<textarea class="your_message" name="your_message" id="your_message_<?php echo $product_id; ?>"></textarea></div>
        <div class="pcf_contact_field">
		<a class="pcf_form_button pcf_contact_bt_<?php echo $product_id; ?> <?php echo $inquiry_contact_button_class; ?>" id="pcf_contact_bt_<?php echo $product_id; ?>" product_id="<?php echo $product_id; ?>"><span><?php echo $inquiry_contact_text_button; ?></span></a> <span class="pcf_contact_loading" id="pcf_contact_loading_<?php echo $product_id; ?>"><img src="<?php echo WPEC_PCF_IMAGES_URL; ?>/ajax-loader.gif" /></span>
        </div>
        <div style="clear:both"></div>
	</div>
    <div style="clear:both"></div>
</div>
</div>
	<?php		
		die();
	}
	
	public static function pcf_contact_action() {
		$product_id 	= esc_attr( stripslashes( $_REQUEST['product_id'] ) );
		$your_name 		= esc_attr( stripslashes( $_REQUEST['your_name'] ) );
		$your_email 	= esc_attr( stripslashes( $_REQUEST['your_email'] ) );
		$your_phone 	= esc_attr( stripslashes( $_REQUEST['your_phone'] ) );
		$your_message 	= esc_attr( stripslashes( strip_tags( $_REQUEST['your_message'] ) ) );
		$send_copy_yourself	= esc_attr( stripslashes( $_REQUEST['send_copy'] ) );
		
		$email_result = WPEC_PCF_Functions::email_inquiry($product_id, $your_name, $your_email, $your_phone, $your_message, $send_copy_yourself );
		echo json_encode($email_result );
		die();
	}
		
	public static function add_style_header() {
		wp_enqueue_style('a3_pcf_style', WPEC_PCF_CSS_URL . '/pcf_style.css');
	}
	
	public static function include_customized_style() {
		include( WPEC_PCF_DIR. '/templates/customized_style.php' );
	}
	
	public static function footer_print_scripts() {
		wp_enqueue_style( 'woocommerce_fancybox_styles', WPEC_PCF_JS_URL . '/fancybox/fancybox.css' );
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'fancybox', WPEC_PCF_JS_URL . '/fancybox/fancybox.min.js');
	}
	
	public static function script_contact_popup() {
		$pcf_contact_popup = wp_create_nonce("pcf_contact_popup");
		$pcf_contact_action = wp_create_nonce("pcf_contact_action");
	?>
<script type="text/javascript">
(function($){
	$(function(){
		var ajax_url = "<?php echo admin_url('admin-ajax.php', 'relative'); ?>";
		$(document).on("click", ".pcf_contact_buton", function(){
			var product_id = $(this).attr("product_id");
			var product_name = $(this).attr("product_name");
			
			var popup_wide = 520;
			if ( ei_getWidth()  <= 568 ) { 
				popup_wide = '90%'; 
			}
			$.fancybox({
				href: ajax_url+"?action=pcf_contact_popup&product_id="+product_id+"&security=<?php echo $pcf_contact_popup; ?>",
				centerOnScroll : true,
				easingIn: 'swing',
				easingOut: 'swing',
				width: popup_wide,
				autoScale: true,
				autoDimensions: true,
				height: 460,
				margin: 0,
				maxWidth: "90%",
				maxHeight: "80%",
				padding: 10,
				showCloseButton : true,
				openEffect	: "none",
				closeEffect	: "none"
			});
		});
		
		$(document).on("click", ".pcf_form_button", function(){
			if ( $(this).hasClass('pcf_email_inquiry_sending') ) {
				return false;
			}
			$(this).addClass('pcf_email_inquiry_sending');
			
			var product_id = $(this).attr("product_id");
			var your_name = $("#your_name_"+product_id).val();
			var your_email = $("#your_email_"+product_id).val();
			var your_phone = $("#your_phone_"+product_id).val();
			var your_message = $("#your_message_"+product_id).val();
			var send_copy = 0;
			
			var pcf_contact_error = "";
			var pcf_have_error = false;
			var filter = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			
			if (your_name.replace(/^\s+|\s+$/g, '') == "") {
				pcf_contact_error += "<?php _e('Please enter your Name', 'wpec_pcf'); ?>\n";
				pcf_have_error = true;
			}
			if (your_email == "" || !filter.test(your_email)) {
				pcf_contact_error += "<?php _e('Please enter valid Email address', 'wpec_pcf'); ?>\n";
				pcf_have_error = true;
			}
			if (your_phone.replace(/^\s+|\s+$/g, '') == "") {
				pcf_contact_error += "<?php _e('Please enter your Phone', 'wpec_pcf'); ?>\n";
				pcf_have_error = true;
			}
			if (pcf_have_error) {
				$(this).removeClass('pcf_email_inquiry_sending');
				alert(pcf_contact_error);
				return false;
			}
			$("#pcf_contact_loading_"+product_id).show();
			
			var data = {
				action: 		"pcf_contact_action",
				product_id: 	product_id,
				your_name: 		your_name,
				your_email: 	your_email,
				your_phone: 	your_phone,
				your_message: 	your_message,
				send_copy:		send_copy,
				security: 		"<?php echo $pcf_contact_action; ?>"
			};
			$.post( ajax_url, data, function(response) {
				pcf_response = $.parseJSON( response );
				$("#pcf_contact_loading_"+product_id).hide();
				$("#pcf_contact_content_"+product_id).html(pcf_response);
			});
		});
	});
})(jQuery);
function ei_getWidth() {
    xWidth = null;
    if(window.screen != null)
      xWidth = window.screen.availWidth;

    if(window.innerWidth != null)
      xWidth = window.innerWidth;

    if(document.body != null)
      xWidth = document.body.clientWidth;

    return xWidth;
}
</script>
    <?php
	}
	
	public static function admin_footer_scripts() {
		global $wpec_ei_admin_interface;
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'a3rev-chosen-new-style', $wpec_ei_admin_interface->admin_plugin_url() . '/assets/js/chosen/chosen' . $suffix . '.css' );
		wp_enqueue_script( 'a3rev-chosen-new', $wpec_ei_admin_interface->admin_plugin_url() . '/assets/js/chosen/chosen.jquery' . $suffix . '.js', array( 'jquery' ), true, false );
	?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".chzn-select").chosen(); jQuery(".chzn-select-deselect").chosen({allow_single_deselect:true});
});	
</script>
	<?php
	}
	
	public static function a3_wp_admin() {
		wp_enqueue_style( 'a3rev-wp-admin-style', WPEC_PCF_CSS_URL . '/a3_wp_admin.css' );
	}
	
	public static function admin_sidebar_menu_css() {
		wp_enqueue_style( 'a3rev-wpec-ei-admin-sidebar-menu-style', WPEC_PCF_CSS_URL . '/admin_sidebar_menu.css' );
	}
		
	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WPEC_PCF_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-catalog-visibility-and-email-inquiry/" target="_blank">'.__('Documentation', 'wpec_pcf').'</a>';
		$links[] = '<a href="http://wordpress.org/support/plugin/wp-e-commerce-catalog-visibility-and-email-inquiry/" target="_blank">'.__('Support', 'wpec_pcf').'</a>';
		return $links;
	}
}
?>
