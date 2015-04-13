<?php
// Load the iContact library
require_once('lib/iContactApi.php');

$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "arf_autoresponder WHERE responder_id='8'");$res = $res[0];
$api_key = stripslashes($res->responder_api_key);
$api_username = stripslashes($res->responder_username);
$api_password = stripslashes($res->responder_password);

$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = %d", $fid), 'ARRAY_A' );
			
$arr_icontact 	= unserialize( $data[0]['icontact'] );

$list_id1 = @$arr_icontact['type_val'];

if( $list_id1 != '' )
{		
	// Give the API your information
	iContactApi::getInstance()->setConfig(array(
		'appId'       => $api_key, 
		'apiPassword' => $api_password, 
		'apiUsername' => $api_username
	));
	
	// Store the singleton
	$oiContact = iContactApi::getInstance();
	// Try to make the call(s)
	try {
		$contact = $oiContact->addContact($email, null, null, $fname, $lname, null, null, null, null, null, null, null, null, null);
		
		// Get lists
		$lists = $oiContact->getLists();
		
		foreach ($lists as $list) {
			if( $list->listId == $list_id1 ) {
				$list_id = $list->listId;
			}
		}
		// Subscribe contact to list
		$list = $oiContact->subscribeContactToList($contact->contactId, $list_id, 'normal');
		
	} catch (Exception $oException) { // Catch any exceptions
		// Dump errors
		$oiContact->getErrors();
		// Grab the last raw request data
		$oiContact->getLastRequest();
		// Grab the last raw response data
		$oiContact->getLastResponse();
	}
}
?>
