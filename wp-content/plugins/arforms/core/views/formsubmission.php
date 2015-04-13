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

global $wpdb,$arfform, $arffield, $db_record, $arfrecordmeta, $user_ID, $arfsettings, $arfcreatedentry, $arfform_params,$MdlDb,$arfdatepickerloaded, $arrecordcontroller, $armainhelper, $maincontroller, $arformcontroller, $arfieldhelper, $arrecordhelper;

$form_name = $form->name;

$arfaset = get_option('arfa_options');

$form_css_submit = unserialize($form->form_css);
if(is_array($form_css_submit))
{
	if($form_css_submit['arfsubmitbuttontext']!='')
		$submit = $form_css_submit['arfsubmitbuttontext'];
	else
		$submit = $arfsettings->submit_value;
}
else
	$submit = $arfsettings->submit_value;

$success_image = '<img src="'.ARFIMAGESURL.'/success_icons/'.$arfaset->arfsucessiconsetting.'" style="padding-right:0px; margin-right:10px; box-shadow: none;" alt="" />';

$saved_message = isset($form->options['success_msg']) ? '<div id="arf_message_success"> 
				<div class="msg-detail">
                    <div class="msg-description-success">'.$form->options['success_msg'].'</div>
				</div>

            </div>' : $arfsettings->success_msg;

$params = $arrecordcontroller->get_recordparams($form);

global $arfsettings;
		
if($arfsettings->form_submit_type != 1 || $is_confirmation_method )
{ 
	$wp_upload_dir 	= wp_upload_dir();
	$upload_dir 	= $wp_upload_dir['basedir'].'/arforms/css/';
	
	
	if(is_ssl())
	{
		$upload_main_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/maincss');
		$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/css');
	}
	else
	{
		$upload_main_url = 	$wp_upload_dir['baseurl'].'/arforms/maincss';
		$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/css';
	}
	
	$fid = $upload_main_url.'/maincss_'.$form->id.'.css';
	wp_register_style('arfformscss'.$form->id,$fid);
	wp_print_styles('arfformscss'.$form->id,$fid);
}



$form_css = unserialize($form->form_css);
if($form_css != '')
{
	if(is_array($form_css))
		$date_picker = $form_css['arfcalthemecss'];
	else
		$date_picker = $form_css->arfcalthemecss;
}

$date_picker_css = $armainhelper->jquery_css_url($date_picker);

wp_register_style('form_custom_css', $date_picker_css);		

$message = $errors = '';

if($is_confirmation_method){
						
		$custom_css_array_form = array(
			'arf_form_success_message'	=> '#arf_message_success',
			);									
		foreach($custom_css_array_form as $custom_css_block_form => $custom_css_classes_form) 
		{
			$form->options[$custom_css_block_form] = $arformcontroller->br2nl($form->options[$custom_css_block_form]);
			
			if( isset($form->options[$custom_css_block_form]) and $form->options[$custom_css_block_form] != '' ){
				echo '<style type="text/css">';
				echo '.ar_main_div_'.$form->id.' '.$custom_css_classes_form.' { '.$form->options[$custom_css_block_form].' } ';
				echo '</style>';	
			} 			
		}		
	
	$confirmation = $form->options['success_action'];
	
	if( $confirmation == 'page' ){
		global $post;
		$page = get_post($form->options['success_page_id']);
		$old_post = $post;
		$post = $page;
		$content = apply_filters('arfcontent', $page->post_content, $form);
		echo $arrecordcontroller->include_css_from_form_content($content);
		echo apply_filters('the_content', $content);
				
	} else {
		$saved_message = isset($form->options['success_msg']) ? '<div id="arf_message_success"> 
					<div class="msg-detail">
						<div class="msg-title-success">'.__('Success', 'ARForms').'</div>
						<div class="msg-description-success">'.$form->options['success_msg'].'</div>
					</div>	
				</div>' : $arfsettings->success_msg;
		?><div class="arf_form<?php echo ' ar_main_div_'.$form->id; ?>" id="arffrm_<?php echo $form->id ?>_container"><?php echo $saved_message ?></div><?php		
	}		

}

if($params['action'] == 'create' and $params['posted_form_id'] == $form->id and isset($_POST)){
	
    $errors = $arfcreatedentry[$form->id]['errors'];
	
    if( !empty($errors) ){
		
        $fields = $arfieldhelper->get_form_fields_tmp(false, $form->id, false, 0);	
        $values = $arrecordhelper->setup_new_vars($fields, $form);

		if($arfsettings->form_submit_type == 1)
			$return["conf_method"] = "error";
        require(VIEWS_PATH .'/new.php'); 
		exit;
?><script type="text/javascript">jQuery(document).ready(function($){ var frm_pos=jQuery('#form_<?php echo $form->form_key ?>').offset();var cOff=document.documentElement.scrollTop || document.body.scrollTop;if(frm_pos) window.scrollTo(frm_pos.left,frm_pos.top);})</script><?php        

	

    }else{
		
       $fields = $arfieldhelper->get_form_fields_tmp(false, $form->id, false, 0);


        if (apply_filters('arfcontinuetocreate', true, $form->id)){


            $values = $arrecordhelper->setup_new_vars($fields, $form, true);


            $created = $arfcreatedentry[$form->id]['entry_id'];


            $saved_message = apply_filters('arfcontent', $saved_message, $form, $created);
			
			
            $conf_method = apply_filters('arfformsubmitsuccess', 'message', $form, $form->options);

			if( !$created or !is_numeric($created) )
				$conf_method = 'message';
			
            if (!$created or !is_numeric($created) or $conf_method == 'message'){
				
				global $MdlDb;
                $page_num = $MdlDb->get_count($MdlDb->fields, array("form_id" => $form->id, "type" => 'break'));
				
				if($arfsettings->form_submit_type == 1)
					$return["conf_method"] = $conf_method;
					 
				$failed_msg =  '<div class="frm_error_style" id="arf_message_error">
									<div class="msg-detail">
										<div class="msg-description-success">'.$arfsettings->failed_msg.'</div>
									</div>
								</div>';
										
			   	$message = (($created and is_numeric($created)) ? do_shortcode($saved_message) : $failed_msg);
				
					
                if (!isset($form->options['show_form']) or $form->options['show_form']){
				
					require(VIEWS_PATH .'/new.php');
					exit;

                }else{ 


                    global $arfforms_loaded, $arfloadcss, $arfcssloaded;


                    $arfforms_loaded[] = $form; 


                    if($values['custom_style']) $arfloadcss = true;





                    if(!$arfcssloaded and $arfloadcss){


                        echo $maincontroller->footer_js('header');


                        $arfcssloaded = true;
						
                    }
					
					if($arfsettings->form_submit_type != 1)		
						$maincontroller->footer_js();				
					
if( $arfsettings->form_submit_type != 1 ) {
						
	$custom_css_array_form = array(
		'arf_form_success_message'	=> '#message_success',
		);
								
	foreach($custom_css_array_form as $custom_css_block_form => $custom_css_classes_form) 
	{
		$form->options[$custom_css_block_form] = $arformcontroller->br2nl($form->options[$custom_css_block_form]);
		
		if( isset($form->options[$custom_css_block_form]) and $form->options[$custom_css_block_form] != '' ){
			echo '<style type="text/css">';
			echo '.ar_main_div_'.$form->id.' '.$custom_css_classes_form.' { '.$form->options[$custom_css_block_form].' } ';
			echo '</style>';	
		} 			
	}
}
?>
<?php if($arfsettings->form_submit_type == 1) 
	 { 
		$return["message"] = '<div class="arf_form arf_form_outer_wrapper ar_main_div_'.$form->id.'" id="arffrm_'.$form->id.'_container">'.$message.'</div>';
		echo json_encode($return);
		exit;
	}
	else
	{ ?>
		<div class="arf_form<?php echo ' arf_form_outer_wrapper ar_main_div_'.$form->id; ?>" id="arffrm_<?php echo $form->id ?>_container"><?php echo $message ?></div>
	<?php }
?>
<?php
									
					if($arfsettings->form_submit_type == 1)
						exit;
					
                } 	
											
            }
			else
			{
				if($arfsettings->form_submit_type == 1)
				{
					$return["conf_method"] = $conf_method;
				}
				
				////// PAGE AND REDIRECT /////
				$form_options = $form->options;
				
				if($conf_method == 'page' and is_numeric($form_options['success_page_id']))
				{
						global $post, $arfsettings;
						if($form_options['success_page_id'] != $post->ID){
							$page = get_post($form_options['success_page_id']);
							$old_post = $post;
							$post = $page;
							$content = apply_filters('arfcontent', $page->post_content, $form, $entry_id);
							
							if($arfsettings->form_submit_type == 1)
							{
								$return["message"] = apply_filters('the_content', $content);
							}
							else
							{
								echo $content;
							}
						}
				}
				else if($method == 'redirect')
				{
					$success_url = apply_filters('arfcontent', $form_options['success_url'], $form, $entry_id);
					$success_msg = isset($form_options['success_msg']) ? stripslashes($form_options['success_msg']) : __('Please wait while you are redirected.', 'ARForms'); 
					echo "<script type='text/javascript'> jQuery(document).ready(function($){ setTimeout(window.location='". $success_url ."', 5000); });</script>";
				}
				
				
				///// PAGE AND REDIRECT /////
				if($arfsettings->form_submit_type == 1)
				{
					echo json_encode($return);
					exit;
				}
			}
        }


    }


}else if(!$is_confirmation_method){


    $fields = $arfieldhelper->get_form_fields_tmp(false, $form->id, false, 0);
	

    do_action('arfdisplayformaction', $params, $fields, $form, $title, $description);


    
		

        $values = $arrecordhelper->setup_new_vars($fields, $form);
		
        require(VIEWS_PATH .'/new.php');
		
		$file_url = MODELS_PATH."/GeoIP.dat";
		
		if(!(extension_loaded('geoip'))) {
			$gi = geoip_open($file_url,GEOIP_STANDARD);
		}
		
		$referrerinfo = $armainhelper->get_referer_info();		

		$form_id = $form->id;
		
		$browser_info = $_SERVER['HTTP_USER_AGENT'];
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		if(!(extension_loaded('geoip'))) {
			if(isset($new_values['ip_address'])) {
				$new_values_ip_addr = $new_values['ip_address'];
			}else { 
				$new_values_ip_addr = "";
			}
			$country_name = geoip_country_name_by_addr($gi,  @$new_values_ip_addr);
		}
		else
		{
			$country_name = "";
		}
		
		if($country_name=="") { $country_name = ""; }
		
		$country = $country_name;
		
		$session_id  = WP_FB_SESSION;
				
		$added_date = current_time('mysql');
		
		if($form_id != 0 && $preview!=true)
		{		
			$sqlQyr = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$MdlDb->views." WHERE session_id= %s AND form_id = %d",$session_id,$form_id),'ARRAY_A');
			$totalViews = $wpdb->num_rows;
			
			
			if($totalViews == 0)
			{

				$qry = $wpdb->query( $wpdb->prepare("insert into ".$MdlDb->views." (form_id,browser_info,ip_address,country,session_id,added_date) VALUES ('%d','%s','%s','%s','%s','%s')", $form_id, $browser_info, $ip_address, $country, $session_id, $added_date) );
				
			}
		}
    
}

$hiddenvalue = '<div class="brand-div"></div><div class=""><input type="hidden" name="form_id" id="form_id" value="'.$form->id.'" /><input type="hidden" name="arfmainformurl" id="arfmainformurl" value="'.ARFURL.'" /></div>';
$hiddenvalue = $arformcontroller->arf_remove_br( $hiddenvalue );	
echo $hiddenvalue; 

if($preview==true)
{
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
		if($form_css_submit['arfcheckradiostyle']!="")
		{
			if($form_css_submit['arfcheckradiostyle']!="none")
			{
				if($form_css_submit['arfcheckradiocolor']!="default" && $form_css_submit['arfcheckradiocolor']!="")
				{
					if($form_css_submit['arfcheckradiostyle']=="futurico" || $form_css_submit['arfcheckradiostyle']=="polaris")
					{
						$checkradio_property = $form_css_submit['arfcheckradiostyle'];
					}
					else
					{
						$checkradio_property = $form_css_submit['arfcheckradiostyle']."-".$form_css_submit['arfcheckradiocolor'];
					}	
				}
				else
				{
					$checkradio_property = $form_css_submit['arfcheckradiostyle'];
				}
			}
			else
			{
				$checkradio_property = "";
			}	
		}
	}

if($checkradio_property!="" && isset($_SESSION['arfaction_ptype']) && $_SESSION['arfaction_ptype'] == 'list'){?>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#arffrm_<?php echo $form->id; ?>_container input').not('.arf_hide_opacity').iCheck({
		checkboxClass: 'icheckbox_<?php echo $checkradio_property;?>',
		radioClass: 'iradio_<?php echo $checkradio_property;?>'
	});	
});  
</script>
<style type="text/css">
.arf_form .icheckbox_<?php echo $checkradio_property;?>,
.arf_form .iradio_<?php echo $checkradio_property;?> { position:relative; }
</style>
<?php } }

if($preview!=true)
{
$checkradio_property = "";
if($form_css_submit['arfcheckradiostyle']!="")
{
	if($form_css_submit['arfcheckradiostyle']!="none")
	{
		if($form_css_submit['arfcheckradiocolor']!="default" && $form_css_submit['arfcheckradiocolor']!="")
		{
			if($form_css_submit['arfcheckradiostyle']=="futurico" || $form_css_submit['arfcheckradiostyle']=="polaris")
			{
				$checkradio_property = $form_css_submit['arfcheckradiostyle'];
			}
			else
			{
				$checkradio_property = $form_css_submit['arfcheckradiostyle']."-".$form_css_submit['arfcheckradiocolor'];
			}	
		}
		else
		{
			$checkradio_property = $form_css_submit['arfcheckradiostyle'];
		}
	}
	else
	{
		$checkradio_property = "";
	}	
}
 
if($checkradio_property!=""){?><div><script language="javascript" type="text/javascript">
jQuery(document).ready(function(){
	if( jQuery.isFunction( jQuery().iCheck ) )
	{	
	  jQuery('#arffrm_<?php echo $form->id; ?>_container input').not('.arf_hide_opacity').iCheck({
		checkboxClass: 'icheckbox_<?php echo $checkradio_property;?>',
		radioClass: 'iradio_<?php echo $checkradio_property;?>',
		increaseArea: '25%' // optional
	  });
	}  	
});  
</script></div><?php }
$inbuild = '';

$licunliceact = 0;
global $arformsplugin;
$licunliceact = $arformcontroller->$arformsplugin();

if($licunliceact == 0) { $inbuild = " (U)"; }
$hiddenvalue = '  
<!--Plugin Name: ARForms	
	Plugin Version: '.get_option('arf_db_version').' '.$inbuild.'
	Developed By: Repute Infosystems
	Developer URL: http://www.reputeinfosystems.com/
-->';
$hiddenvalue = $arformcontroller->arf_remove_br( $hiddenvalue );	
echo $hiddenvalue;  
} ?>