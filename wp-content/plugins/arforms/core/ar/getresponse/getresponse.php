<?php
    require_once dirname(__FILE__) . '/jsonRPCClient.php';

	$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "arf_autoresponder WHERE responder_id='4'");$res = $res[0];
	
	$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = %d", $fid), 'ARRAY_A' );
			
   	$arr_getresponse 	= unserialize( $data[0]['getresponse'] );

	$responder_api_key = @$arr_getresponse['type_val'];
	
	$campaignName = $res->responder_list_id;

	$subscriberName = $fname." ".$lname;

	$subscriberEmail = $email;
	
	$api_key = $res->responder_api_key; //Place API key here
	$api_url = 'http://api2.getresponse.com';

	# initialize JSON-RPC client
	$client = new jsonRPCClient($api_url);

	if( $campaignName != '' )
	{
		
		$result2 = $client->get_campaigns(
	       $api_key,
	        array (
	        	'name' => array ( 'EQUALS' => $campaignName )
	        )
	    );
		
		$res = array_keys($result2);
	    $CAMPAIGN_IDs = array_pop($res);
	   
		// Add contact to selected campaign id
		 try{
		  $result_contact = $client->add_contact(
			$api_key,
			array (
				'campaign'  => $CAMPAIGN_IDs,
				'name'      => $subscriberName,
				'email'     => $subscriberEmail
			)
		);
		//echo "RESULT";print_r($result_contact);
		 //echo "<p style='color: blue; font-size:24px;'> Contact Added </p>";
		 //exit;
		}
		
		catch (Exception $e) {
			
			//echo $e->getMessage();
			//exit;
		}

	}
?>