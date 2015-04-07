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

class arsettingcontroller{

    function arsettingcontroller(){

        add_action('admin_init',  array(&$this, 'admin_init'));
		
		add_action('admin_menu', array( &$this, 'menu' ), 26);

		add_action('wp_ajax_delete_aweber', array(&$this, 'delete_aweber'));
		
		add_action('wp_ajax_refresh_aweber', array(&$this, 'refresh_aweber'));
		
		add_action('wp_ajax_clear_form', array(&$this, 'clear_form'));
		
		add_action('wp_ajax_verify_autores', array(&$this, 'verify_autores'));
		
		add_action('wp_ajax_delete_autores', array(&$this, 'delete_autores'));
		
		add_action('wp_ajax_upload_submit_bg', array(&$this, 'upload_submit_bg'));
		
		add_action('wp_ajax_upload_submit_hover_bg', array(&$this, 'upload_submit_hover_bg'));
		
		add_action('wp_ajax_delete_submit_bg_img', array(&$this, 'delete_submit_bg_img'));
		
		add_action('wp_ajax_delete_submit_hover_bg_img', array(&$this, 'delete_submit_hover_bg_img'));
		
		add_action('wp_ajax_delete_submit_bg_img_IE89', array(&$this, 'delete_submit_bg_img_IE89'));
		
		add_action('wp_ajax_delete_submit_hover_bg_img_IE89', array(&$this, 'delete_submit_hover_bg_img_IE89'));
		
		add_action('wp_ajax_upload_form_bg_img', array(&$this, 'upload_form_bg_img'));		
		
		add_action('wp_ajax_delete_form_bg_img', array(&$this, 'delete_form_bg_img'));
		
		add_action('wp_ajax_delete_form_bg_img_IE89', array(&$this, 'delete_form_bg_img_IE89'));
		
		add_action('wp_ajax_arfverifypurchasecode', array(&$this, 'arfreqact'));
		
		add_action('wp_ajax_arfdeactivatelicense', array(&$this, 'arfreqlicdeact'));
		
		add_action( 'admin_init', array(&$this, 'arfdeleterefforms') );
    }
	
	function arfreqlicdeact()
	{
		$plugres = arformcontroller::arfdeactivatelicense();
		
		if(isset($plugres) && $plugres!= "" )
		{
			echo $plugres;
			exit;
		}	
		else
		{
			echo "Received Blank Response From Server While License Deactivation";
			exit;
		}
		exit;
			
	}
	
	function arfreqlicdeactuninst()
	{
		$plugres = arformcontroller::arfdeactivatelicense();
		
		return;
	}
	
	function arfreqact()
	{
		$plugres = arformcontroller::arfverifypurchasecode();
		
		if(isset($plugres) && $plugres!= "")
		{
			$responsetext = $plugres;
			
				if($responsetext == "License Activated Successfully.")
				{
					echo "VERIFIED";
					exit;
				}
				else
				{
					echo $plugres;
					exit;
				}	
		}
		else
		{
			echo "Received Blank Response From Server While License Activation";
			exit;
		}
	}
	
	function generateplugincode()
	{
		$siteinfo = array();
		
		$siteinfo[] = arnotifymodel::sitename();
		$siteinfo[] = arformmodel::sitedesc();
		$siteinfo[] = home_url();
		$siteinfo[] = get_bloginfo('admin_email');
		$siteinfo[] = $_SERVER['SERVER_ADDR'];
		
		$newstr = implode("^",$siteinfo);
		$postval = base64_encode($newstr);
		
		return $postval;	
	}
	
    function menu(){
 

        add_submenu_page('ARForms', 'ARForms | '. __('Global Settings', 'ARForms'), __('Global Settings', 'ARForms'), 'arfchangesettings', 'ARForms-settings', array(&$this, 'route'));
		
		add_submenu_page('ARForms', 'ARForms | '. __('Import Export', 'ARForms'), __('Import / Export', 'ARForms'), 'arfchangesettings', 'ARForms-import-export', array(&$this, 'route'));	
		
		add_submenu_page('ARForms', 'ARForms | '.__('Addons','ARForms'), __('Addons','ARForms'),'arfviewforms','ARForms-addons',array(&$this,'route'));
    }
	
	function route(){

		if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'ARForms-import-export')
		{
			return arsettingcontroller::import_export_form();
		} else if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'ARForms-addons' ){
			
			if( file_exists( VIEWS_PATH.'/addon_lists.php' ) ){
				include( VIEWS_PATH.'/addon_lists.php' );				
			}
		}
        else
		{
        	$action = isset($_REQUEST['arfaction']) ? 'arfaction' : 'action';
	
			
			$cur_tab = @isset($_REQUEST['arfcurrenttab']) ? $_REQUEST['arfcurrenttab'] : '';
			 
			$action = armainhelper::get_param($action);
	
	
			if($action == 'process-form')
	
	
				return arsettingcontroller::process_form($cur_tab);
	
	
			else
	
	
				return arsettingcontroller::display_form();
		}

    }
	
	function getdeactlicurl()
	{
		$deactlicurl = "http://www.reputeinfosystems.com/tf/plugins/arforms/verify/deactivelicwc.php";
		
		return $deactlicurl;
	}
	
	function display_form(){


      global $arfsettings, $arfajaxurl, $wpdb, $arfform;

      $arfroles = armainhelper::frm_capabilities();


      


      $uploads = wp_upload_dir();


      $target_path = $uploads['basedir'] . "/arforms/css";


      $sections = apply_filters('arfaddsettingssection', array());


       
	  if( get_option('arf_ar_type') == '' ) {
	  
		  $arr = array(
					'aweber_type' => 0,
					
					'mailchimp_type' => 0,
					
					'getresponse_type' => 0,
					
					'icontact_type' => 0,
					
					'constant_type' => 0,
					
					'gvo_type' => 0,
					
					'ebizac_type' => 0,
					);
							
		  $arr_new = maybe_serialize( $arr );
		  
		  update_option('arf_ar_type', $arr_new);
		  
		}
	  
	  
	  if( get_option('arf_current_tab') == '' ) {
	  
	      update_option('arf_current_tab', 'general_settings');
		  
	  }		
		
		
	  $autores_type = maybe_unserialize( get_option('arf_ar_type') );
	
	  $default_ar = maybe_unserialize( get_option('arfdefaultar') ); 			   


	  $mailchimp_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='1'");


	  $mailchimp_data = $mailchimp_alldata[0];


	  


	  $nfusionsoft_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='2'");


	  $nfusionsoft_data = $nfusionsoft_alldata[0];


	  


	  $aweber_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='3'");


	  $aweber_data = $aweber_alldata[0];


	  


	  $getresponse_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='4'");


	  $getresponse_data = $getresponse_alldata[0];


	  


	  $gvo_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='5'");


	  $gvo_data = $gvo_alldata[0];


	  


	  $ebizac_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='6'");


	  $ebizac_data = $ebizac_alldata[0];
	  
	  
	  
	  
	  $icontact_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='8'");


	  $icontact_data = $icontact_alldata[0];
	  
	  
	  $constant_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='9'");


	  $constant_data = $constant_alldata[0];

      require(VIEWS_PATH . '/settings_form.php');


    }

	function addons_page(){
?><script type="application/javascript" language="javascript">jQuery('#arfsaveformloader').show();</script> <?php
        $plugins = get_plugins();
        $installed_plugins = array();
        foreach($plugins as $key => $plugin){
            $is_active = is_plugin_active($key);
            $installed_plugin = array("plugin" => $key, "name" => $plugin["Name"], "is_active"=>$is_active);
            $installed_plugin["activation_url"] = $is_active ? "" : wp_nonce_url("plugins.php?action=activate&plugin={$key}", "activate-plugin_{$key}");
            $installed_plugin["deactivation_url"] = !$is_active ? "" : wp_nonce_url("plugins.php?action=deactivate&plugin={$key}", "deactivate-plugin_{$key}");

            $installed_plugins[] = $installed_plugin;
        }
		
		global $arfversion, $MdlDb, $arnotifymodel, $arfform, $arfrecordmeta;
		$bloginformation = array();
		$str = $MdlDb->get_rand_alphanumeric(10);
		
		if(is_multisite())
			$multisiteenv = "Multi Site";
		else
			$multisiteenv = "Single Site";
		
		$addon_listing = 1;
								
		$bloginformation[] = $arnotifymodel->sitename();
		$bloginformation[] = $arfform->sitedesc();
		$bloginformation[] = home_url();
		$bloginformation[] = get_bloginfo('admin_email');
		$bloginformation[] = $arfrecordmeta->wpversioninfo();
		$bloginformation[] = $arfrecordmeta->getlanguage();
		$bloginformation[] = $arfversion;
		$bloginformation[] = $_SERVER['REMOTE_ADDR'];
		$bloginformation[] = $str;
		$bloginformation[] = $multisiteenv;
		$bloginformation[] = $addon_listing;
		
		$valstring = implode("||",$bloginformation);
		$encodedval = base64_encode($valstring);
		
        //$body = array("plugins" => urlencode(serialize($installed_plugins)));
        //$options = array('body' => $body, 'headers' => array('Referer' => get_bloginfo("url")), 'timeout' => 15);
		
		//$urltopost = $arfform->getsiteurl();
		//$urltopost = 'http://192.168.0.8/udit/Dimple/addons_listing/addon_list.php';
		$urltopost = 'http://www.arformsplugin.com/arf/addons/addon_list.php';
		
		$raw_response = wp_remote_post( $urltopost, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'plugins' => urlencode(serialize($installed_plugins)), 'wpversion' => $encodedval ),
			'cookies' => array()
			)
		);
		
		//echo "<pre>";print_r($raw_response);echo "</pre>"; exit;
		
		if ( is_wp_error( $raw_response ) || $raw_response['response']['code'] != 200){ 
            echo "<div class='error_message' style='margin-top:100px; padding:20px;'>" . __("Add-On listing is currently unavailable. Please try again later.", "ARForms") . "</div>";
			?><script type="application/javascript" language="javascript">jQuery('#arfsaveformloader').hide();</script><?php
         }
         else{
            echo $raw_response["body"];
			?><script type="application/javascript" language="javascript">jQuery('#arfsaveformloader').hide();</script><?php
         }
    }
	
	function import_export_form()
	{
		 require(VIEWS_PATH . '/import_export_form.php');
	}


    function process_form($cur_tab = ''){


      global $arfsettings, $arfajaxurl, $wpdb;
	  		

      $errors = array();
	  
	  
	if( $cur_tab == 'autoresponder_settings' )  {


	if($_REQUEST['mailchimp_type'] == 1) {
	  	$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_api_key' => @$_REQUEST['mailchimp_api'], 'responder_list' => @$_REQUEST['mailchimp_listid'] ), array('responder_id' => '1'));
	} else {
		$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_web_form' => @$_REQUEST['mailchimp_web_form'] ), array('responder_id' => '1'));
	}

	  


	$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_api_key' => @$_REQUEST['infusionsoft_api'] ), array('responder_id' => '2'));	

	if($_REQUEST['aweber_type'] == 1) {
	  	$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_list' => @$_REQUEST['responder_list'] ), array('responder_id' => '3'));
	} else {
		$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_web_form' => @$_REQUEST['aweber_web_form'] ), array('responder_id' => '3'));
	}

	if($_REQUEST['getresponse_type'] == 1) {
	  	$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_api_key' => @$_REQUEST['getresponse_api'], 'responder_list_id' => @$_REQUEST['getresponse_listid'] ), array('responder_id' => '4'));
	} else {
		$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_web_form' => @$_REQUEST['getresponse_web_form'] ), array('responder_id' => '4'));
	}
	  $wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_api_key' => @$_REQUEST['gvo_api'] ), array('responder_id' => '5'));
	  $wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_api_key' => @$_REQUEST['ebizac_api'] ), array('responder_id' => '6'));
	  
	  if($_REQUEST['icontact_type'] == 1) {
	  	$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_api_key' => @$_REQUEST['icontact_api'], 'responder_username' => @$_REQUEST['icontact_username'], 'responder_password' => @$_REQUEST['icontact_password'], 'responder_list' => @$_REQUEST['icontact_listname'] ), array('responder_id' => '8'));
	} else {
		$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_web_form' => @$_REQUEST['icontact_web_form'] ), array('responder_id' => '8'));
	}

	
	if($_REQUEST['constant_type'] == 1) {
		$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_api_key' => @$_REQUEST['constant_api'], 'responder_list_id' => @$_REQUEST['constant_access_token'], 'responder_list' => @$_REQUEST['constant_listname'] ), array('responder_id' => '9'));
	} else {
		$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_web_form' => @$_REQUEST['constant_web_form'] ), array('responder_id' => '9'));
	}

		
	 	  
	  $arr = array(
	  			'aweber_type' => $_REQUEST['aweber_type'],
				
				'mailchimp_type' => $_REQUEST['mailchimp_type'],
				
				'getresponse_type' => $_REQUEST['getresponse_type'],
				
				'icontact_type' => $_REQUEST['icontact_type'],
				
				'constant_type' => $_REQUEST['constant_type'],
				
				'gvo_type' => $_REQUEST['gvo_type'],
				
				'ebizac_type' => $_REQUEST['ebizac_type'],
				);
						
	  $arr_new = maybe_serialize( $arr );
	  
	  
	  update_option('arf_ar_type', $arr_new);
	  
	  	  
	  $autores_type = $arr;
	  
	  
	    
		
	  
	  } 

	  
	  if( $cur_tab == 'general_settings') {
	  
	  		$arfsettings->update($_POST, $cur_tab);
			
			$autores_type = maybe_unserialize(get_option('arf_ar_type'));
	  
	   }
	  
	  
	 
	  
	  
	  $mailchimp_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='1'");


	  $mailchimp_data = $mailchimp_alldata[0];


	  


	  $nfusionsoft_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='2'");


	  $nfusionsoft_data = $nfusionsoft_alldata[0];


	  


	  $aweber_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='3'");


	  $aweber_data = $aweber_alldata[0];


	  


	  $getresponse_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='4'");


	  $getresponse_data = $getresponse_alldata[0];


	  


	  $gvo_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='5'");


	  $gvo_data = $gvo_alldata[0];


	  


	  $ebizac_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='6'");


	  $ebizac_data = $ebizac_alldata[0];
	  
	  
	  
	  $icontact_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='8'");


	  $icontact_data = $icontact_alldata[0];
	  
	  
	  $constant_alldata = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id='9'");


	  $constant_data = $constant_alldata[0];



	  if( $cur_tab != '' ){
			
	  		update_option('arf_current_tab', $cur_tab);
						
	  }		

				
		
      if( empty($errors) ){
	  
			
        $arfsettings->store($cur_tab);
		
		if($cur_tab == 'general_settings')
		{
	        $message = __('General setting changes saved successfully.', 'ARForms');		
		}	
		elseif($cur_tab == 'autoresponder_settings')
		{
	        $message = __('Email Marketing Tools setting saved successfully.', 'ARForms');		
		}
		else
		{
			$message = __('Settings Saved.', 'ARForms');
		}
		
		if( isset($web_form_msg) and $web_form_msg != '' )
			$web_form_msg_default = 'You have made below required fields which may not supported by system.<br>';
		 
		$web_form_msg = ( (isset($web_form_msg_default)) ? $web_form_msg_default : '').( (isset($web_form_msg)) ? $web_form_msg : '');
		
		@$message_notRquireFeild .= $web_form_msg;
		
      }


      $arfroles = armainhelper::frm_capabilities();


      $sections = apply_filters('arfaddsettingssection', array());
    


      require(VIEWS_PATH . '/settings_form.php');


    }

    function admin_init(){


        global $arfsettings;


        if(isset($_GET) and isset($_GET['page']) and $_GET['page'] == 'ARForms-settings')


            wp_enqueue_script('jquery-ui-datepicker');


        add_action('admin_head-'. sanitize_title($arfsettings->menu) .'_page_ARForms-settings', array(&$this, 'head'));


    }


    


    function head(){

		if( isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] != "ARForms-settings" )
        	$js_file  = ARFURL . '/js/jquery/jquery-ui-themepicker.js';

		$uicss = ARFURL.'/css/ui-all/ui.all.css';

		wp_register_style('ui-css',$uicss);
		armainhelper::load_styles(array('ui-css'));
		
		$customcss = ARFSCRIPTURL.'&amp;controller=settings';

		wp_register_style('custom-css',$customcss);
		armainhelper::load_styles(array('custom-css'));
		
      ?>
      <?php


        require(VIEWS_PATH . '/head.php');


    }
	
	function delete_aweber( $atts ) {
	
	global $wpdb;
		
		$wpdb->update($wpdb->prefix."arf_autoresponder", array( 'responder_api_key' => '', 'responder_list_id' => '', 'responder_list' => '', 'is_verify' =>'0' ), array( 'responder_id' => 3 ) );
	
	
	die();
	
	}
	
	function refresh_aweber( $atts ) {
	
	require_once(AUTORESPONDER_PATH.'aweber/aweber_api/aweber_api.php');

	global $wpdb, $arfsiteurl;


	$res = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 3) );	
	
	$res = $res[0];
	
	$new_arr = explode('|', $res->responder_api_key);
	
	$consumerKey = $new_arr[0];
	
	$consumerSecret = $new_arr[1];
	
	
	$aweber = new AWeberAPI($consumerKey, $consumerSecret);
	
	$aweber->adapter->debug = false;

	$account = $aweber->getAccount($new_arr[2], $new_arr[3]);	
	
	
		foreach($account->lists as $offset => $list) {

				$listname .= $list->name."|";

				$listid .= $list->id."|";

		}
	 
		if($listname!="" && $listid!="") {

			$listingdetails = $listname."-|-".$listid;
		}
	
	$res = $wpdb->update( $wpdb->prefix."arf_autoresponder", array('responder_list_id' => $listingdetails, 'responder_list' => $list->id), array('responder_id' => '3') );
		
	$res_data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 3), 'ARRAY_A');
	$res_data = $res_data[0];
	?>
		<div class="sltstandard" style="float:none; display:inline;">
                    	<select name="responder_list"  style="width:150px;" data-width='150px'>		

                        <?php 


							$aweber_lists = explode("-|-",$listingdetails);


							$aweber_lists_name = explode("|",$aweber_lists[0]);


							$aweber_lists_id = explode("|",$aweber_lists[1]);


							$i=0;


							foreach($aweber_lists_name as $aweber_lists_name1)


							{


								if($aweber_lists_id[$i]!="")


								{

								?>
                            	<option value="<?php echo $aweber_lists_id[$i];?>" <?php if($aweber_lists_id[$i]==$res_data['responder_list']){ echo "selected=selected"; }?>><?php echo $aweber_lists_name1;?></option>


                         <?php  } ?>


                      <?php $i++;


					  		} ?>


                        </select>
                        </div>
    <?php   
	echo '<span id="aweber_refresh" class="frm_refresh_li">Refreshed</span>';                 
	
	die();
	
	}
	
	function clear_form( $atts ) {
	
	global $wpdb;
	
	$form_id = $_POST['id'];
	
	$res = $wpdb->query( $wpdb->prepare( "DELETE FROM ".$wpdb->prefix."arf_fields WHERE form_id = %d",  $form_id) );
	
	echo $res;
	
	die();
	
	}
	
	function verify_autores( $atts ) {
	
	global $wpdb;
	
	$name = $_POST['id'];
	
	$api_key = $_POST['api_key'];
	
	$user = $_POST['user'];
	
	$pass = $_POST['pass'];
	
	$refresh_li = $_POST['refresh_li'];
	
	
	if( $name == 'mailchimp' ) {
		
		require_once(AUTORESPONDER_PATH.'mailchimp/inc/MCAPI.class.php');
		
		$api = new MCAPI($api_key);
		
		$campain = $api->lists();
		
		$lists = $campain['data'];
		
		if( count($lists) > 0 )
			{
			
			$lists_ser = maybe_serialize( $lists );
			
			$res = $wpdb->update($wpdb->prefix."arf_autoresponder", array( 'responder_api_key' =>$api_key, 'is_verify' => 1, 'responder_list_id'=>$lists_ser ), array( 'responder_id' => 1 ));
			
			echo '<div class="sltstandard" style="float:none; display:inline;"><select name="mailchimp_listid" id="mailchimp_listid" style="width:150px;"  data-width="150px">';					foreach ($lists as $list) {
					echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
				}
			echo '</select></div>';
			if($refresh_li == 1)
				echo '<span id="mailchimp_refresh" class="frm_refresh_li">Refreshed</span>';

			}
			
	
	} 
	
	
	if( $name == 'getresponse' ) {
		
		require_once(AUTORESPONDER_PATH.'getresponse/jsonRPCClient.php');
		
		$api_url = 'http://api2.getresponse.com';
		$client = new jsonRPCClient($api_url);
		$camp = $client->get_campaigns($api_key);
		
		if( count($camp) > 0 )
			{
			
			//echo "<pre>";print_r($camp);echo "</pre>"; exit;
			
			$camp_ser = maybe_serialize( $camp );
			
			$res = $wpdb->update($wpdb->prefix."arf_autoresponder", array( 'responder_api_key' =>$api_key, 'is_verify' => 1, 'list_data'=>$camp_ser ), array( 'responder_id' => 4 ));
			
			echo '<div class="sltstandard" style="float:none; display:inline;"><select name="getresponse_listid" id="getresponse_listid" style="width:150px;" data-width="150px">';
			foreach ($camp as $listid => $list) {
					echo '<option value="'.$list['name'].'">'.$list['name'].'</option>';
				}
			echo '</select></div>';
			
			if($refresh_li == 1)
				echo '<span id="getresponse_refresh" class="frm_refresh_li">Refreshed</span>';

			}
			
	} 
	
	
	if( $name == 'icontact' ) {
		
		require_once(AUTORESPONDER_PATH.'icontact/lib/iContactApi.php');

		iContactApi::getInstance()->setConfig(array(
			'appId'       => $api_key, 
			'apiPassword' => $pass, 
			'apiUsername' => $user
		));
		
		$oiContact = iContactApi::getInstance();
		
		try {
		
		$lists = $oiContact->getLists();
		
		if( count($lists) > 0 )
			{
			
			$lists_ser = maybe_serialize( $lists );
			
			$res = $wpdb->update($wpdb->prefix."arf_autoresponder", array( 'responder_api_key' =>$api_key, 'responder_username'=> $user, 'responder_password'=>$pass, 'is_verify' => 1, 'responder_list_id'=>$lists_ser ), array( 'responder_id' => 8 ));
			
			echo '<div class="sltstandard" style="float:none; display:inline;"><select name="icontact_listname" id="icontact_listname" style="width:150px;" data-width="150px">';
				foreach ($lists as $list) {
					echo '<option value="'.$list->listId.'">'.$list->name.'</option>';
				}
			echo '</select></div>';
			
			if($refresh_li == 1)
				echo '<span id="icontact_refresh" class="frm_refresh_li">Refreshed</span>';			

			}
		
		} catch (Exception $oException) { 

			$oiContact->getErrors();

			$oiContact->getLastRequest();

			$oiContact->getLastResponse();
		}		
		
	
	} 
	
	
	if( $name == 'constant' ) {
	
		require_once(AUTORESPONDER_PATH.'constant_contact/list_contact.php');		
			
		$lists_new = $cc->getLists($user);
	
		if( count($lists_new) > 0 )
			{
			
			$i = 0;
			foreach ($lists_new as $list) {
				$new_arr[$i]['id'] = $list->id;
				$new_arr[$i]['name'] = $list->name;
				$new_arr[$i]['status'] = $list->status;
				$new_arr[$i]['contact_count'] = $list->contact_count;
				$i++;
				if($is_exist == '')
					$is_exist = $list->id;
				else
				 	$is_exist = ','.$list->id;
			}
			
			if($is_exist != '')
			{
				$lists_ser = maybe_serialize( $new_arr );
				
				$res = $wpdb->update($wpdb->prefix."arf_autoresponder", array( 'responder_api_key' => $api_key, 'responder_list_id' => $user, 'is_verify' => 1, 'list_data'=>$lists_ser ), array( 'responder_id' => 9 ));
				
				echo '<div class="sltstandard" style="float:none; display:inline;"><select name="constant_listname" id="constant_listname" style="width:150px;" data-width="150px">';
					foreach ($lists_new as $list) {
						echo '<option value="'.$list->id.'">'.$list->name.'</option>';
					}
				echo '</select></div>';
	
				if($refresh_li == 1)
					echo '<span id="constant_refresh" class="frm_refresh_li">Refreshed</span>';
	
				}
			}

	} 
	
	
	die();
	
	}
	
	function delete_autores( $atts ) {
	
	global $wpdb;
	
	$id = $_POST['id'];
	
	if( $id == 'mailchimp' ) {
		
		$wpdb->update($wpdb->prefix."arf_autoresponder", array( 'responder_api_key' => '', 'responder_list_id' => '', 'responder_list' => '', 'is_verify' => 0 ), array( 'responder_id' => 1 ) );
	
	}
	
	if( $id == 'getresponse' ) {
		
		$wpdb->update($wpdb->prefix."arf_autoresponder", array( 'responder_api_key' => '', 'responder_list_id' => '', 'responder_list' => '', 'list_data' => '', 'is_verify' => 0 ), array( 'responder_id' => 4 ) );
	
	}
	
	if( $id == 'icontact' ) {
		
		$wpdb->update($wpdb->prefix."arf_autoresponder", array( 'responder_api_key' => '', 'responder_list_id' => '', 'responder_list' => '', 'is_verify' => 0, 'responder_username'=> '', 'responder_password'=>'' ), array( 'responder_id' => 8 ) );
	
	}

	if( $id == 'constant' ) {
		
		$wpdb->update($wpdb->prefix."arf_autoresponder", array( 'responder_api_key' => '', 'responder_list_id' => '', 'responder_list' => '', 'list_data' => '', 'is_verify' => 0 ), array( 'responder_id' => 9 ) );
	
	}
		
	die();
	
	}
	
	function upload_submit_bg(){
	
	
	$file = $_POST['image'];
	?>
    <input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="<?php echo $file; ?>" id="arfsubmitbuttonimagesetting" />
    <img src="<?php echo $file; ?>" height="30" width="100" />&nbsp;<img style="cursor:pointer;vertical-align: super;" onclick="delete_submit_bg_img();" src="<?php echo esc_attr(ARFURL.'/images/delete-icon.png');?>" />
	<?php
	
	die();
	}
	
	function upload_submit_hover_bg(){
	
	
	$file = $_POST['image'];
	?>
    <input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="<?php echo $file; ?>" id="arfsubmithoverbuttonimagesetting" />
    <img src="<?php echo $file; ?>" height="30" width="100" />&nbsp;<img style="cursor:pointer;vertical-align: super;" onclick="delete_submit_hover_bg_img();" src="<?php echo esc_attr(ARFURL.'/images/delete-icon.png');?>" />
	<?php
	
	die();
	}
	
	function delete_submit_bg_img(){
	
		?>
        
		<input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="" id="arfsubmitbuttonimagesetting" />
        <div class="arfajaxfileupload" style="position: relative; overflow: hidden; float:left; cursor: pointer;">
        	<div class="file-upload-img"></div>
            <?php _e('Upload', 'ARForms');?>
            <input type="file" name="submit_btn_img" id="submit_btn_img" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
        </div>
        
        <input type="hidden" name="imagename" id="imagename" value="" />
        &nbsp;&nbsp;<span id="ajax_submit_loader" style="display:none; float: left; margin: 5px 0 0;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/wpspin_light.gif"></span>
		<?php
        wp_register_script('arffiledrag', ARFURL . '/js/filedrag/filedrag.js');
		wp_print_scripts('arffiledrag');
		?>
		<script type="application/javascript" language="javascript">
		<?php
		$wp_upload_dir 	= wp_upload_dir();
		if(is_ssl())
		{
			$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/');
		}
		else
		{
			$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/';
		}
		?>
		function change_submit_img(){
			var upload_css_url = '<?php echo $upload_css_url; ?>';	
			var img = jQuery('#imagename').val();
			var image = upload_css_url + img;
			jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_submit_bg&image="+image ,
			success:function(msg){ jQuery('#submit_btn_img_div').html(msg); formChange1(); }					
			});
			
		}		
		</script>        
		<?php
		
	die();
	}
	
	function delete_submit_hover_bg_img() {
	
		?>
        
		<input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="" id="arfsubmithoverbuttonimagesetting" />
        <div class="arfajaxfileupload" style="position: relative; overflow: hidden; float:left; cursor: pointer;">
        	<div class="file-upload-img"></div>
            <?php _e('Upload', 'ARForms');?>
            <input type="file" name="submit_hover_btn_img" id="submit_hover_btn_img" data-val="submit_hover_bg" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
        </div>
        
        <input type="hidden" name="imagename_submit_hover" id="imagename_submit_hover" value="" />
        &nbsp;&nbsp;<span id="ajax_submit_hover_loader" style="display:none; float: left; margin: 5px 0 0;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/wpspin_light.gif"></span>
		<?php
        wp_register_script('arffiledrag', ARFURL . '/js/filedrag/filedrag.js');
		wp_print_scripts('arffiledrag');
		?>
		<script type="application/javascript" language="javascript">
		<?php
		$wp_upload_dir 	= wp_upload_dir();
		if(is_ssl())
		{
			$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/');
		}
		else
		{
			$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/';
		}
		?>
		function change_submit_hover_img(){
			var upload_css_url = '<?php echo $upload_css_url; ?>';	
			var img = jQuery('#imagename_submit_hover').val();
			var image = upload_css_url + img;
			jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_submit_hover_bg&image="+image ,
			success:function(msg){ jQuery('#submit_hover_btn_img_div').html(msg); formChange1(); }					
			});
			
		}		
		</script>        
		<?php
		
	die();
	}
	
	function upload_form_bg_img(){
	
	$file = $_POST['image'];
	?>
    <input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="<?php echo $file; ?>" id="arfmainform_bg_img" />
    <img src="<?php echo $file; ?>" height="30" width="100" />&nbsp;<img style="cursor:pointer;vertical-align: super;" onclick="delete_form_bg_img();" src="<?php echo esc_attr(ARFURL.'/images/delete-icon.png');?>" />
	<?php
	
	die();	
	}
	
	function delete_form_bg_img(){
	
	?>
    <div class="arfajaxfileupload" style="position: relative; overflow: hidden; float:left; cursor: pointer;">
    	<div class="file-upload-img"></div>
        <?php _e('Upload', 'ARForms');?>
        <input type="file" name="form_bg_img" id="form_bg_img" data-val="form_bg" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
    </div>
    <input type="hidden" name="imagename_form" id="imagename_form" value="" />
    <input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="" id="arfmainform_bg_img" />
    &nbsp;&nbsp;<span id="ajax_form_loader" style="display:none; float: left; margin: 5px 0 0;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/wpspin_light.gif"></span>
	<?php
	wp_register_script('arffiledrag', ARFURL . '/js/filedrag/filedrag.js');
	wp_print_scripts('arffiledrag');
	?>
	<script type="application/javascript" language="javascript">
	<?php
	$wp_upload_dir 	= wp_upload_dir();
	if(is_ssl())
	{
		$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/');
	}
	else
	{
		$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/';
	}
	?>
	function change_form_bg_img(){

		var upload_css_url = '<?php echo $upload_css_url; ?>';	
		var img = jQuery('#imagename_form').val();
		var image = upload_css_url + img;
		
		jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_form_bg_img&image="+image ,
	
		success:function(msg){ jQuery('#form_bg_img_div').html(msg); formChange1(); }	
				
		});		
	}
	</script>        
	<?php
	
	die();
	}
	
	
	function delete_submit_bg_img_IE89(){
	
		?>
        <span style="display:inline-block;color:#FFFFFF;text-align:center;">Upload</span>
        <input type="text" class="original" name="submit_btn_img" id="field_arfsbis" form-id="" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; width: 160px; height: 59px; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
        
        <input type="hidden" id="type_arfsbis" name="type_arfsbis" value="1" >
		<input type="hidden" value="jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico" id="file_types_arfsbis" name="field_types_arfsbis" />
        <input type="hidden" name="imagename" id="imagename" value="" />
		<input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="" id="arfsubmitbuttonimagesetting" />
        <input type="hidden" name="imagename" id="imagename" value="" />
		<script type="application/javascript" language="javascript">
		<?php
		$wp_upload_dir 	= wp_upload_dir();
		if(is_ssl())
		{
			$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/');
		}
		else
		{
			$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/';
		}
		?>
		function change_submit_img(){
			var upload_css_url = '<?php echo $upload_css_url; ?>';	
			var img = jQuery('#imagename').val();
			var image = upload_css_url + img;
			
			jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_submit_bg&image="+image ,
			success:function(msg){ jQuery('#submit_btn_img_div').html(msg); formChange1(); }
					
			});
			
		}		
		</script>        
		<?php
		
	die();
	}
	
	
	function delete_submit_hover_bg_img_IE89(){
	
		?>
        <span style="display:inline-block;color:#FFFFFF;text-align:center;">Upload</span>
        <input type="text" class="original" name="submit_hover_btn_img" id="field_arfsbhis" form-id="" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; width: 160px; height: 59px; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
        
        <input type="hidden" id="type_arfsbhis" name="type_arfsbhis" value="1" >
		<input type="hidden" value="jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico" id="file_types_arfsbhis" name="field_types_arfsbhis" />
        <input type="hidden" name="imagename_submit_hover" id="imagename_submit_hover" value="" />
		<input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="" id="arfsubmithoverbuttonimagesetting" />
        <input type="hidden" name="imagename_submit_hover" id="imagename_submit_hover" value="" />
		<script type="application/javascript" language="javascript">
		<?php
		$wp_upload_dir 	= wp_upload_dir();
		if(is_ssl())
		{
			$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/');
		}
		else
		{
			$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/';
		}
		?>
		function change_submit_hover_img(){
			var upload_css_url = '<?php echo $upload_css_url; ?>';	
			var img = jQuery('#imagename_submit_hover').val();
			var image = upload_css_url + img;
			
			jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_submit_hover_bg&image="+image ,
			success:function(msg){ jQuery('#submit_hover_btn_img_div').html(msg); formChange1(); }
					
			});
			
		}		
		</script>        
		<?php
		
	die();
	}
	
	
	
	function delete_form_bg_img_IE89(){
	
	?>
    <span style="display:inline-block;color:#FFFFFF;text-align:center;">Upload</span>
    <input type="text" class="original" name="form_bg_img" id="field_arfmfbi" form-id="" file-valid="true" style="position: absolute; cursor: pointer; top: 0px; width: 160px; height: 59px; left: -999px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
    
    <input type="hidden" id="type_arfmfbi" name="type_arfmfbi" value="1" >
    <input type="hidden" value="jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico" id="file_types_arfmfbi" name="field_types_arfmfbi" />
    <input type="hidden" name="imagename_form" id="imagename_form" value="" />
    <input type="hidden" name="arfmfbi" onclick="clear_file_submit();" value="" id="arfmainform_bg_img" />
	<script type="application/javascript" language="javascript">
	<?php
	$wp_upload_dir 	= wp_upload_dir();
	if(is_ssl())
	{
		$upload_css_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/');
	}
	else
	{
		$upload_css_url = 	$wp_upload_dir['baseurl'].'/arforms/';
	}
	?>
	function change_form_bg_img(){

		var upload_css_url = '<?php echo $upload_css_url; ?>';	
		var img = jQuery('#imagename_form').val();
		var image = upload_css_url + img;
		
		jQuery.ajax({type:"POST",url:ajaxurl,data:"action=upload_form_bg_img&image="+image ,
	
		success:function(msg){ jQuery('#form_bg_img_div').html(msg); formChange1(); }	
				
		});		
	}
	</script>        
	<?php
	
	die();
	}
	
	function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   
   return implode(",", $rgb); 
}
	
	function arfdeleterefforms()
	{
		global $arfsettings, $arfajaxurl, $wpdb, $arfform, $db_record, $MdlDb;
		
		// delete from arf_froms 
	  	$res = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_forms WHERE created_date < NOW() - INTERVAL 2 DAY AND is_template=%d AND status!='published'", 0), 'ARRAY_A');
	  	if( is_array($res) and count( $res) > 0 ) 
			$res = $res[0];
	  
	  	if( count( $res) > 0 ) {
	 	 
		 	foreach($res as $key => $form_delete) {	
				$arfform->destroy( $form_delete );
			}
			
		}
		
		// delete from arf_ref_froms 
		$res = $wpdb->get_results( $wpdb->prepare("SELECT id FROM ".$wpdb->prefix."arf_ref_forms WHERE created_date < NOW() - INTERVAL 2 DAY AND is_template=%d", 0), 'ARRAY_A');
	  	if( $res && count( $res) > 0 ) {
	 	 
		 	foreach($res as $key => $rform) {	
				$rformid = $rform['id']; 
				if(isset($rformid) && $rformid > 0	&& $rformid != "")
				{
					$entries = $db_record->getAll(array('it.form_id' => $rformid));
					foreach ($entries as $item)
						$db_record->destroy($item->id);
			
					$query_results_r1 = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->fields` WHERE `form_id` = %d", $rformid));
					$query_results_r2 = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->views` WHERE `form_id` = %d", $rformid));
					$query_results_r3 = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->ar` WHERE `frm_id` = %d", $rformid));
					
					$uploads = wp_upload_dir();
					$target_path = $uploads['basedir'];
					$target_path .= "/arforms";
					$css_path = $target_path."/css/";
					$maincss_path = $target_path."/maincss/";
					if( file_exists( $css_path.'form_'.$rformid.'.css' ) ) {
						@unlink( $css_path.'form_'.$rformid.'.css' );
					}
					if( file_exists( $maincss_path.'maincss_'.$rformid.'.css' ) ) {
						@unlink( $maincss_path.'maincss_'.$rformid.'.css' );
					}
					
					$query_results = $wpdb->query($wpdb->prepare("DELETE FROM `$MdlDb->ref_forms` WHERE `id` = %d", $rformid));
				}
				
			}
						
		}
		
	}
}
?>