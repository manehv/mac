<?php 
global $wpdb, $email, $name;

require_once 'src/Ctct/ConstantContact.php';

$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "arf_autoresponder WHERE responder_id='9'");$res = $res[0];
$api_key = stripslashes($res->responder_api_key);
$api_username = stripslashes($res->responder_username);
$api_password = stripslashes($res->responder_password);

// Connecting to your account
$ConstantContact = new ConstantContact("basic", $api_key, $api_username, $api_password);

// Get potential contact lists
$lists = $ConstantContact->getLists();
// Lists are returned in multidimentional arrays 0 being the list, and 1 being the next50

$emailAddress = $email;

// Search for our new Email address
$search = $ConstantContact->searchContactsByEmail($emailAddress);

// If the search didnt return a contact object
if($search == false)
{
	echo "Created new contact";
	// Create a new Contact Object to store data into
	$contactObj = new Contact();
	// Adding multiple lists to this new Contact Object
	$contactObj->lists = array($lists['lists'][0]->id, $lists['lists'][1]->id);
	// Set the email address
	$contactObj->emailAddress = $emailAddress;
	// Create the Contact and DONE
	$Contact = $ConstantContact->addContact($contactObj);
	
	echo $contactObj->emailaddress;
	
} // Otherwise we update our existing
else 
{
	echo $contactObj->emailaddress . " was added to your new list";
	// Gather data from our previous search and store it into a data type
	$contactObj = $ConstantContact->getContactDetails($search[0]);
	// We need to get the old list and add a new list to it as
	// this request requires a PUT and will remove the lists
	// as they are stored in an array
	 array_push($contactObj->lists, $lists['lists'][3]->id );
	
	// Update the contact and DONE
	$UpdateContact = $ConstantContact->updateContact($contactObj);
}
?>