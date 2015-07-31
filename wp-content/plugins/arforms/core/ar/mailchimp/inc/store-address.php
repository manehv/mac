<?php


/*///////////////////////////////////////////////////////////////////////


Part of the code from the book 


Building Findable Websites: Web Standards, SEO, and Beyond


by Aarron Walter (aarron@buildingfindablewebsites.com)


http://buildingfindablewebsites.com





Distrbuted under Creative Commons license


http://creativecommons.org/licenses/by-sa/3.0/us/


///////////////////////////////////////////////////////////////////////*/


//require_once("../../../../../../wp-config.php");





function storeAddress(){


	global $email, $fname, $lname, $wpdb, $fid;

	$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "arf_autoresponder WHERE responder_id='1'");$res = $res[0];
	
	$responder_api_key = $res->responder_api_key;
	
	
	
	$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = %d", $fid), 'ARRAY_A' );
			
   	$arr_mailchimp 	= unserialize( $data[0]['mailchimp'] );
	
	$responder_list_id = @$arr_mailchimp['type_val'];


	if( $responder_list_id != '' )
	{
		// Validation
	
	
		if($email==""){ return "No email address provided"; } 
	
	
		
	
	
		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $email)) {
	
	
			return "Email address is invalid"; 
	
	
		}
	
	
		
	
	
		require_once('MCAPI.class.php');
	
	
		// grab an API Key from http://admin.mailchimp.com/account/api/
	
	
		$api = new MCAPI($responder_api_key);
	
	
		
	
	
		// grab your List's Unique Id by going to http://admin.mailchimp.com/lists/
	
	
		// Click the "settings" link for the list - the Unique Id is at the bottom of that page. 
	
	
		$list_id = $responder_list_id;
	
	
		
	
	
		$merge_vars = array('FNAME'=>$fname, 'LNAME'=>$lname,);
	
	
		if($api->listSubscribe($list_id, $email, $merge_vars) === true) {
	
			// It worked!	
			
			//echo "ADDED SUCCESSFULLY"; exit;
	
			return 'Success! Check your email to confirm sign up.';
	
	
		}else{
	
	
			// An error ocurred, return error message	
			
	
			return 'Error: ' . $api->errorMessage;
	
	
		}
	}

}





// If being called via ajax, autorun the function


storeAddress();


?>