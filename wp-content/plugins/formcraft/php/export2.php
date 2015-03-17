<?php

require_once('../../../../wp-config.php');
require_once(ABSPATH . 'wp-settings.php');

if ( !is_user_logged_in() )
{
	exit;
}

$table_builder = $wpdb->prefix . "formcraft_builder";
$table_subs = $wpdb->prefix . "formcraft_submissions";

function outputCSV($data) {
	$outputBuffer = fopen("php://output", 'w');
	foreach($data as $val) 
	{
		fputcsv($outputBuffer, $val);
	}
	fclose($outputBuffer);
}

if (!isset($_GET['id']))
{
	echo "Form ID is required";
	exit;
}

$id = $_GET['id'];

global $wpdb;
$mysub = $wpdb->get_results( "SELECT * FROM $table_subs WHERE form_id='$id'", 'ARRAY_A' );
$mysubr = $wpdb->get_results( "SELECT * FROM $table_subs WHERE seen='1'", 'ARRAY_A' );

if (empty($mysub))
{
	echo "No submissions to export";
	exit;
}


$skey = 1;
$final_data = array();
$final_data[1] = array();


foreach ($mysub as $entry_no=>$entry_data)
{

	$entry_content = json_decode($entry_data['content'], true);
    
    // Make TimeStamp act like a form entry
	$temp_array['label']='Time';
	$temp_array['value']=$entry_data['added'];
	array_unshift($entry_content, $temp_array);

	foreach ($entry_content as $field_no=>$field_data)
	{
		if ($field_data['label']!='' && $field_data['type']!='captcha' && $field_data['type']!='divider')
		{
			$temp_data[$entry_no][$field_no]=urldecode($field_data['label']);
			$temp_data[$entry_no] = array_values($temp_data[$entry_no]);
		}
	}

	$final_data[1] = array_merge($final_data[1], $temp_data[$entry_no]);
}
array_unshift($final_data[1], 'title', 'location');
$final_data[1] = array_unique($final_data[1]);

array_splice($final_data[1], 0, 2, array('Form Title', 'Location'));
$final_data[1] = array_unique($final_data[1]);

$final_data[1] = array_values($final_data[1]);



$row = 2;

foreach ($mysub as $entry_no=>$entry_data)
{

	$entry_content = json_decode($entry_data['content'], true);
	$entry_content = json_decode($entry_data['content'], true);

    // Make TimeStamp act like a form entry
	$temp_array['label']='Time';
	$temp_array['value']=$entry_data['added'];
	array_push($entry_content, $temp_array);

	foreach ($entry_content as $field_no=>$field_data)
	{
		if ($field_data['label']!='' && $field_data['type']!='captcha' && $field_data['type']!='divider')
		{

			if ($field_data['label']=='location')
			{
				$field_data['label']='Location';
			}
			if ($field_data['label']=='title')
			{
				$field_data['label']='Form Title';
			}



			$field_data['label'] = urldecode($field_data['label']);
			$field_data['value'] = urldecode($field_data['value']);

			$array_search = array_search($field_data['label'], $final_data[1]);
            
			if ($field_data['label']==$entry_content[$field_no-1]['label'] && !(empty($field_data['value'])))
			{
			$temp = $final_data[$row][$array_search];
			$final_data[$row][$array_search] = $temp.', '.$field_data['value'];
			}
			else
			{
			$final_data[$row][$array_search] = $field_data['value'];
			}
		}
	}
	$row++;
}

$size = sizeof($final_data[1]);

foreach ($final_data as $key=>$val)
{
	$i = 1;
	while ($i<=$size)
	{


	if (empty($val[$i]))
	{
		$final_data[$key][$i]='';
	}
	$i++;
	}
	ksort($final_data[$key]);
}




$header = 'Form Submission Data';
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=Submissions.csv");
header("Pragma: no-cache");
header("Expires: 0");
$final_data = outputCSV($final_data);
?>