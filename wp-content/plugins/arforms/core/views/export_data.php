<?php
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/
if(!isset($_REQUEST['bulk_export']) && @$_REQUEST['bulk_export']!='yes')
{
	maincontroller::arfafterinstall();
	global $style_settings;
	
	$form_id = $all_form_id;
	
	$form = $arfform->getOne($form_id);
	
	$form_name = sanitize_title_with_dashes($form->name);


	$form_cols = $arffield->getAll("fi.type not in ('divider', 'captcha', 'break', 'html', 'imagecontrol') and fi.form_id=".$form->id, 'field_order ASC');


	$entry_id = armainhelper::get_param('entry_id', false);


	$where_clause = "it.form_id=". (int)$form_id;


	if($entry_id){


		$where_clause .= " and it.id in (";


		$entry_ids = explode(',', $entry_id);
		

		foreach((array)$entry_ids as $k => $it){


			if($k)


				$where_clause .= ",";


			$where_clause .= $it;


			unset($k);


			unset($it);


		}

		$where_clause .= ")";


	}else if(!empty($search)){


		$where_clause = $this->get_search_str($where_clause, $search, $form_id, $fid);


	}

	$where_clause = apply_filters('arfcsvwhere', $where_clause, compact('form_id'));

	$entries = $db_record->getAll($where_clause, '', '', true, false);
	
	$form_cols	= apply_filters('arfpredisplayformcols', $form_cols, $form->id);
	$entries		= apply_filters('arfpredisplaycolsitems', $entries, $form->id);

	$filename = 'ARForms_'.$form_name.'_'. time() .'_0.csv';

	$wp_date_format = apply_filters('arfcsvdateformat', 'Y-m-d H:i:s');

	$charset = get_option('blog_charset');

	$to_encoding = $style_settings->csv_format;

@header('Content-Description: File Transfer');
@header("Content-Disposition: attachment; filename=\"$filename\"");
@header('Content-Type: text/csv; charset=' . $charset, true);
@header('Expires: '. gmdate("D, d M Y H:i:s", mktime(date('H')+2, date('i'), date('s'), date('m'), date('d'), date('Y'))) .' GMT');
@header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
@header('Cache-Control: no-cache, must-revalidate');
@header('Pragma: no-cache');

foreach ($form_cols as $col)
	echo '"'. arrecordhelper::encode_value(strip_tags($col->name), $charset, $to_encoding) .'",';

echo '"'. __('Timestamp', 'ARForms') .'","IP","ID","Key","Country","Browser"'."\n";
foreach($entries as $entry){
global $wpdb;
$res_data = $wpdb->get_results( $wpdb->prepare('SELECT country, browser_info FROM '.$wpdb->prefix.'arf_entries WHERE id = %d', $entry->id), 'ARRAY_A');
$entry->country = $res_data[0]['country'];
$entry->browser = $res_data[0]['browser_info'];
foreach ($form_cols as $col){
	$field_value = isset($entry->metas[$col->id]) ? $entry->metas[$col->id] : false;
	if(!$field_value and $entry->attachment_id){
		$col->field_options = maybe_unserialize($col->field_options);
	}
   if ($col->type == 'file'){
		$field_value = str_replace('thumbs/', '', wp_get_attachment_url($field_value) );
	}else if ($col->type == 'date'){
		$field_value = arfieldhelper::get_date($field_value, $wp_date_format);
	}else{
		$checked_values = maybe_unserialize($field_value);
		$checked_values = apply_filters('arfcsvvalue', $checked_values, array('field' => $col));
		if (is_array($checked_values)){
				$field_value = implode(', ', $checked_values);
		}else{
			$field_value = $checked_values;
		}
		$field_value = arrecordhelper::encode_value($field_value, $charset, $to_encoding);
		$field_value = str_replace('"', '""', stripslashes($field_value));  
	}
	$field_value = str_replace(array("\r\n", "\r", "\n"), ' <br />', $field_value);
	echo "\"$field_value\",";
	unset($col);
	unset($field_value);
}
$formatted_date = date($wp_date_format, strtotime($entry->created_date));
echo "\"{$formatted_date}\",";
echo "\"{$entry->ip_address}\",";
echo "\"{$entry->id}\",";
echo "\"{$entry->entry_key}\",";
echo "\"{$entry->country}\",";
echo "\"{$entry->browser}\"\n";
unset($entry);
}

}
else
{		
global $wpdb;

$plugin_url_list = wp_upload_dir();
$baseurl = $plugin_url_list['baseurl'];
$basedir = $plugin_url_list['basedir'];

$filename_arry = array();

$form_id_arr = explode(",",$all_form_id);
$j=0;
foreach($form_id_arr as $form_id)
{
	$form = $arfform->getOne($form_id);
		
	$form_name = sanitize_title_with_dashes($form->name);


	$form_cols = $arffield->getAll("fi.type not in ('divider', 'captcha', 'break', 'html', 'imagecontrol') and fi.form_id=".$form->id, 'field_order ASC');


	$entry_id = armainhelper::get_param('entry_id', false);


	$where_clause = "it.form_id=". (int)$form_id;


	if($entry_id){


		$where_clause .= " and it.id in (";


		$entry_ids = explode(',', $entry_id);
		

		foreach((array)$entry_ids as $k => $it){


			if($k)


				$where_clause .= ",";


			$where_clause .= $it;


			unset($k);


			unset($it);


		}

		$where_clause .= ")";


	}else if(!empty($search)){


		$where_clause = $this->get_search_str($where_clause, $search, $form_id, $fid);


	}

	$where_clause = apply_filters('arfcsvwhere', $where_clause, compact('form_id'));

	$entries = $db_record->getAll($where_clause, '', '', true, false);
	
	$form_cols	= apply_filters('arfpredisplayformcols', $form_cols, $form->id);
	$entries		= apply_filters('arfpredisplaycolsitems', $entries, $form->id);
	
	$wp_upload_dir 	= wp_upload_dir();
	$dest_dir = $wp_upload_dir['basedir'].'/arforms/';
	
	$filename = @$dest_dir.'ARForms_'.$form_name.'_'. time() .'_'.$j.'.csv';

	$wp_date_format = apply_filters('arfcsvdateformat', 'Y-m-d H:i:s');

	$charset = get_option('blog_charset');

	$to_encoding = @$style_settings->csv_format;

	foreach ($form_cols as $col)
	
		@$list .= arrecordhelper::encode_value(strip_tags($col->name), $charset, $to_encoding).',';

		@$list .= __('Timestamp', 'ARForms') .',IP,ID,Key,Country,Browser <br>';

	foreach($entries as $entry){
		
		
		global $wpdb;
		
		$res_data = $wpdb->get_results( $wpdb->prepare('SELECT country, browser_info FROM '.$wpdb->prefix.'arf_entries WHERE id = %d', $entry->id), 'ARRAY_A');
		$entry->country = $res_data[0]['country'];
		$entry->browser = $res_data[0]['browser_info'];
		
		$i= 0 ;
		$size_of_form_cols =  count($form_cols);
		foreach ($form_cols as $col){
			
			
			
			$field_value = isset($entry->metas[$col->id]) ? $entry->metas[$col->id] : false;
	
	
			
	
	
			if(!$field_value and $entry->attachment_id){
	
	
				$col->field_options = maybe_unserialize($col->field_options);
	
			}
	
	
		   if ($col->type == 'file'){
	
	
				$field_value = str_replace('thumbs/', '', wp_get_attachment_url($field_value) );
	
	
			}else if ($col->type == 'date'){
	
	
				$field_value = arfieldhelper::get_date($field_value, $wp_date_format);
	
	
			}else{
	
	
				$checked_values = maybe_unserialize($field_value);
	
	
				$checked_values = apply_filters('arfcsvvalue', $checked_values, array('field' => $col));
	
	
				
	
	
				if (is_array($checked_values)){
		
						$field_value = implode('^|^', $checked_values);
	
	
					
	
	
				}else{
	
	
					$field_value = $checked_values;
	
	
				}
	
	
				
	
	
				$field_value = arrecordhelper::encode_value($field_value, $charset, $to_encoding);
	
	
				$field_value = str_replace('"', '""', stripslashes($field_value));  
	
	
			}
	
			
				$field_value = str_replace(array("\r\n", "\r", "\n"), ' <br />', $field_value);
			
			if($size_of_form_cols == $i)  // - 1
			{
				$list .= $field_value;			
			}
			else
				$list .= $field_value.',';			
		
			
			unset($col);
			unset($field_value);
			
			if(!isset($_REQUEST['bulk_export']) && $_REQUEST['bulk_export']!='yes')
			{
				$formatted_date = date($wp_date_format, strtotime($entry->created_date));
				echo "\"{$formatted_date}\",";
				echo "\"{$entry->ip_address}\",";
				echo "\"{$entry->id}\",";
				echo "\"{$entry->entry_key}\",";
				echo "\"{$entry->country}\",";
				echo "\"{$entry->browser}\"\n";
				unset($entry);
			}		
			$i++;
			
		}
	
	
		$formatted_date = date($wp_date_format, strtotime($entry->created_date));
	
	
		$list .= $formatted_date.",".$entry->ip_address.",".$entry->id.",".$entry->entry_key.",".$entry->country.",".$entry->browser."<br>";
		
}
	
	$fp = fopen($filename, 'w');
	foreach (explode('<br>',$list) as $line)
	{
		$temp_array1 = explode(",",$line);
		$temp_array2 = array();
		if( count($temp_array1) > 0 ){
			foreach($temp_array1 as $temp_i => $temp_k){
				 $temp_k	= str_replace("^|^", ", ", $temp_k); 	
				 $temp_array2[$temp_i] = $temp_k; 
			}
		}
		fputcsv($fp,$temp_array2);
	}
	fclose($fp);
	
	$file = pathinfo($filename);
	


	$filename_arry[] = $file['basename'];

	
	$j++;
	
	unset($list);
	unset($entry);
	unset($form_cols);
	unset($cols);
	
	}

	$filename_ser =  serialize($filename_arry);

	$compressed_file = 'ARForms_'.time().'.zip';

	Create_zip($filename_ser,$dest_dir.$compressed_file,$dest_dir);

	$compressed_file_url = $file['dirname'].'/'.$file['filename'].'.zip';
	
	@header('Content-Type: application/zip');
	@header('Content-disposition: attachment; filename='.$compressed_file);

	@header('Content-Length: '.filesize($dest_dir.$compressed_file));

	@readfile($dest_dir.$compressed_file);

	@unlink($dest_dir.$compressed_file);

}

function Create_zip($source, $destination, $destinationdir)

{
	$filename = array();
	$filename = unserialize($source);
	
	$zip = new ZipArchive();
	if($zip->open($destination,ZipArchive::CREATE)===TRUE)
	{
		$i = 0;
		foreach($filename as $file)
		{
			
			if($zip->addFile($destinationdir.$file , $file))// Add the files to the .zip file

			$i++;
		}
		$zip->close(); // Closing the zip file
	}
	
	foreach($filename as $file1)
	{

		unlink($destinationdir.$file1);

	}
}
?>