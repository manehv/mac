<?php
require_once('aweber_api/aweber_api.php');

global $wpdb;

	$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "arf_autoresponder WHERE responder_id='3'");$res = $res[0];
	
	$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = %d", $fid), 'ARRAY_A' );
			
   	$arr_aweber 	= @unserialize( $data[0]['aweber'] );

	$responder_api_key = @stripslashes( stripslashes_deep( $arr_aweber['type_val'] ) );
	
	if( $responder_api_key != '' )
	{	
		$temp_data = @unserialize($res->list_data);
		
		$consumerKey    = $res->consumer_key; 					# put your credentials here
		$consumerSecret = $res->consumer_secret; 				# put your credentials here
		$accessKey      = $temp_data['accessToken'];		 	# put your credentials here
		$accessSecret   = $temp_data['accessTokenSecret']; 		# put your credentials here
		$account_id     = $temp_data['acc_id']; 				# put the Account ID here
		$list_id        = $responder_api_key; 					# put the List ID here
		
		$aweber = new AWeberAPI($consumerKey, $consumerSecret);
		
		try {
			$account = $aweber->getAccount($accessKey, $accessSecret);
			$listURL = "/accounts/{$account_id}/lists/{$list_id}";
			$list = $account->loadFromUrl($listURL);
		
			# create a subscriber
			$params = array(
				'email' => $email,
				'name' => $fname." ".$lname,
				);
			$subscribers = $list->subscribers;
			$new_subscriber = $subscribers->create($params);
		
			# success!
			//print "A new subscriber was added to the $list->name list!";
				
		} catch(AWeberAPIException $exc) {
			
		}
	}
?>