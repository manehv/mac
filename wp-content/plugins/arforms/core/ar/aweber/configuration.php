<?php
require_once("../../../../../../wp-load.php");

require_once('aweber_api/aweber_api.php');

global $wpdb, $arfsiteurl;

$consumer_key = $_GET['consumer_key'];

$consumer_secret = $_GET['consumer_secret'];

$res = $wpdb->query( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_autoresponder WHERE responder_id = %d", 3) );

if( $wpdb->num_rows != 1 )
	
	$res = $wpdb->query( $wpdb->prepare("INSERT INTO ".$wpdb->prefix."arf_autoresponder (responder_id, consumer_key, consumer_secret) VALUES (%d, %s, %s)", 3, $consumer_key, $consumer_secret) );

else

	$res = $wpdb->update( $wpdb->prefix."arf_autoresponder", array('consumer_key' => $consumer_key, 'consumer_secret' => $consumer_secret), array('responder_id' => 3) );


$autores_type = unserialize( get_option('arf_ar_type') );
$autores_type['aweber_type'] = 1;
$arr_new1 = serialize( $autores_type );
update_option('arf_ar_type', $arr_new1);
update_option('arf_current_tab', 'autoresponder_settings');
	
$consumerKey    = $consumer_key;

$consumerSecret = $consumer_secret;

$aweber = new AWeberAPI($consumerKey, $consumerSecret);

if (empty($_COOKIE['accessToken']) || empty($_GET['oauth_token'])) {


    if (empty($_GET['oauth_token'])) {


        $callbackUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 

        list($requestToken, $requestTokenSecret) = $aweber->getRequestToken($callbackUrl);


        setcookie('requestTokenSecret', $requestTokenSecret);


        setcookie('callbackUrl', $callbackUrl);


        header("Location: {$aweber->getAuthorizeUrl()}");


        exit();


    }





    $aweber->user->tokenSecret = $_COOKIE['requestTokenSecret'];


    $aweber->user->requestToken = $_GET['oauth_token'];


    $aweber->user->verifier = $_GET['oauth_verifier'];


    list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();


    setcookie('accessToken', $accessToken);


    setcookie('accessTokenSecret', $accessTokenSecret);


    header('Location: '.$_COOKIE['callbackUrl']);


    exit();


}

# set this to true to view the actual api request and response

$aweber->adapter->debug = false;
$account = $aweber->getAccount($_COOKIE['accessToken'], $_COOKIE['accessTokenSecret']);

foreach($account->lists as $offset => $list) {


	$listname .= $list->name."|";


	$listid .= $list->id."|";


}

if($consumerKey!="" && $consumerSecret!="" && $_COOKIE['accessToken']!="" && $_COOKIE['accessTokenSecret']!="" && $account->id!="")
{
	$alldetails  = $consumerKey."|".$consumerSecret."|".$_COOKIE['accessToken']."|".$_COOKIE['accessTokenSecret']."|".$account->id;
}

if($listname!="" && $listid!="")
{
	$listingdetails = $listname."-|-".$listid;
}

if($consumerKey!="" && $consumerSecret!="" && $_COOKIE['accessToken']!="" && $_COOKIE['accessTokenSecret']!="" && $account->id!="")
{
$temp = array('accessToken' =>$_COOKIE['accessToken'], 'accessTokenSecret'=>$_COOKIE['accessTokenSecret'], 'acc_id'=>$account->id );
$temp_data = serialize($temp);

$wpdb->update($wpdb->prefix."arf_autoresponder", array('responder_api_key' => $alldetails, 'responder_list_id' => $listingdetails, 'responder_list' => $list->id, 'list_data'=>$temp_data, 'is_verify' =>'1'), array('responder_id' => '3'));
}

echo "<script>window.opener.location.replace('".admin_url()."admin.php?page=ARForms-settings');</script>";
echo '<script>window.close();</script>';
exit;
?>