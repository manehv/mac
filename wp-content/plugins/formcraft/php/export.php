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

global $wpdb;


if ( isset($_GET['id']) && $_GET['id']!='0' )
{
	$id = intval($_GET['id']);

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
	$line = $final_data;
}
else
{

	if ( isset($_GET['from']) && isset($_GET['to']) )
	{
		$to = $_GET['to'];
		$from = $_GET['from'];
		$mysub = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $table_subs LIMIT %d, %d",$from,$to), 'ARRAY_A' );
	}
	else
	{
		$mysub = $wpdb->get_results( "SELECT * FROM $table_subs", 'ARRAY_A' );	
	}

	$line[1][1] = 'Submission ID';
	$line[1][2] = 'Read';
	$line[1][3] = 'Date';
	$line[1][4] = 'Location';
	$line[1][5] = 'Form Name';
	$line[1][6] = 'Field';
	$line[1][7] = 'User Data';

	$skey = 1;

	foreach ($mysub as $key=>$row) {
		$key++;

		$row_id = $row['form_id'];
		$mysub2 = $wpdb->get_results( "SELECT name FROM $table_builder WHERE id='$row_id'", 'ARRAY_A' );

		$form_name = $mysub2[0][name];
		if ($row['seen']=='1')
			{  $seen = 'Read';	}
		else { $seen = 'Unread'; }

		$new = json_decode($row['content'], 1);

		foreach ($new as $value)
		{
			$skey++;

			if ( !(empty($value['type'])) && !($value['type']=='captcha') )
			{

				if ($value['label']=='location' && $value['type']=='hidden' && isset($temp_key))
				{
					$line[$temp_key][4] = $value['value'];
				}
				if (!($set_it[$key]) && $value['label']!='location')
				{
					$set_it[$key]=1;
					$line[$key*$skey][1] = $row['id'];
					$line[$key*$skey][2] = $seen;
					$line[$key*$skey][3] = $row['added'];
					$line[$key*$skey][4] = ' ';
					$line[$key*$skey][5] = $form_name;
					$line[$key*$skey][6] = urldecode($value['label']);
					$line[$key*$skey][7] = urldecode($value['value']);
					$temp_key = $key*$skey;
				}
				else
				{
					if ($value['label']!='location' && $value['type']!='hidden')
					{
						$line[$key*$skey][1] = ' ';
						$line[$key*$skey][2] = ' ';
						$line[$key*$skey][3] = ' ';
						$line[$key*$skey][4] = ' ';
						$line[$key*$skey][5] = ' ';
						$line[$key*$skey][6] = urldecode($value['label']);
						$line[$key*$skey][7] = urldecode($value['value']);					
					}

				}

			}

		}

	}

}

$header = 'Form Submission Data';
header("content-type: text/csv;charset=UTF-8");
header("Content-Disposition: attachment; filename=Submissions.csv");
header("Pragma: no-cache");
header("Expires: 0");
$line = outputCSV($line);
?>