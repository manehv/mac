<?php

require_once 'src/Ctct/autoload.php';

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "arf_autoresponder WHERE responder_id='9'");$res = $res[0];
$api_key = stripslashes($res->responder_api_key);
$access_token = stripslashes($res->responder_list_id);


$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = %d", $fid), 'ARRAY_A' );
			
$arr_constant_contact 	= unserialize( $data[0]['constant_contact'] );
$list_id1 = @stripslashes($arr_constant_contact['type_val']);

if( $list_id1 != '' )
{
	define("APIKEY", $api_key);
	define("ACCESS_TOKEN", $access_token);
	
	 
	$cc = new ConstantContact(APIKEY);
	
	// attempt to fetch lists in the account, catching any exceptions and printing the errors to screen
	try{
		$lists = $cc->getLists(ACCESS_TOKEN);
		
		foreach ($lists as $list) {
			if( $list->id == $list_id1 ) {
				$list_id = $list->id;
			}
		}
		
	} catch (CtctException $ex) {
		foreach ($ex->getErrors() as $error) {
			//print_r($error);
		}     
	}
	
	
	// check if the form was submitted
	if (isset($email) && strlen($email) > 1) {
		$action = "Getting Contact By Email Address";
		try {
			// check to see if a contact with the email addess already exists in the account
			$response = $cc->getContactByEmail(ACCESS_TOKEN,$email);
	
			// create a new contact if one does not exist
			if (empty($response->results)) {
				$action = "Creating Contact";
	
				$contact = new Contact();
				$contact->addEmail($email);
				$contact->addList($list_id);
				$contact->first_name = $fname;
				$contact->last_name = $lname;
				$returnContact = $cc->addContact(ACCESS_TOKEN, $contact);
							
			// update the existing contact if address already existed
			} else {            
				$action = "Updating Contact";
	
				$contact = $response->results[0];
				$contact->addList($list_id);
				$contact->first_name = $fname;
				$contact->last_name = $lname;
				$returnContact = $cc->updateContact(ACCESS_TOKEN, $contact);  
			}
			
		// catch any exceptions thrown during the process and print the errors to screen
		} catch (CtctException $ex) {
		   /* echo '<span class="label label-important">Error '.$action.'</span>';
			echo '<div class="container alert-error"><pre class="failure-pre">';
			print_r($ex->getErrors()); 
			echo '</pre></div>';
			die();*/
		}
	}
} 
?>