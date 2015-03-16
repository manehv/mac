<?php

error_reporting(0);

header("Content-type: application/download");
header("Content-Disposition: attachment; filename=form_".$_GET[id].".txt");
header("Pragma: no-cache");
header("Expires: 0");

require_once('../../../../wp-config.php');
require_once(ABSPATH . 'wp-settings.php');

if ( !is_user_logged_in() )
{
	exit;
}
if (!(isset($_GET['id'])))
{
	exit;
}
$id = intval($_GET['id']);



$table_builder = $wpdb->prefix . "formcraft_builder";

global $wpdb;
$form = $wpdb->get_row( "SELECT * FROM $table_builder WHERE id = '$id'", 'ARRAY_A' );

$form_req['build'] = $_POST['build']; 
$form_req['options'] = $_POST['options']; 
$form_req['con'] = $_POST['con']; 
$form_req['recipients'] = $_POST['recipients']; 
$form_req['dir'] = $_POST['dir']; 
$form_req['dir2'] = $_POST['dir2']; 
print(json_encode($form_req));



?>