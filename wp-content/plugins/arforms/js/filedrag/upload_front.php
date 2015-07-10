<?php
/*
Server-side PHP file upload code for HTML5 File Drag & Drop demonstration
Featured on SitePoint.com
Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
*/
include('simple_image.php');
require_once("../../../../../wp-load.php");

$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
$field_id = (isset($_REQUEST['field_id']) ? $_REQUEST['field_id'] : false);

$wp_upload_dir 	= wp_upload_dir();
$upload_main_url = 	$wp_upload_dir['basedir'].'/arforms/';
$dest2 = $upload_main_url."userfiles/thumbs";
if(isset($_REQUEST['file_type']) && $_REQUEST['file_type'] != '')
	$file_type = $_REQUEST['file_type'];
else
	$file_type = '';

$is_preview = (isset($_REQUEST['is_preview'])) ? $_REQUEST['is_preview']: 0;
$type_array = ($_REQUEST['types_arr']!= '') ? explode(',', $_REQUEST['types_arr']) : array();
	
if ($fn) {
	$checkext = explode(".",$fn);	
	$ext = $checkext[count($checkext)-1];
	if($ext!="php" && $ext!="php3" && $ext!="php4" && $ext!="php5" && $ext!="pl" && $ext!="pl" && $ext!="py" && $ext!="jsp" && $ext!="asp" && $ext!="exe" && $ext!="cgi")
	{
		if( $is_preview == 0 ){
			
			// AJAX call
			file_put_contents(
				$upload_main_url.'userfiles/' . $fn,		
				file_get_contents('php://input')
			);
			
			$pos = strpos($file_type,'image/');
			if($pos !== false)
			{
				$image = new SimpleImage();		
				$image->load($upload_main_url.'userfiles/'.$fn);
				$image->resizeToHeight(100);
				$image->save($upload_main_url.'userfiles/thumbs/'.$fn);
			}
			//changes end here. 
			echo "|$fn|$field_id";
		}
	}
	exit();
}
else {

	// form submit
	$files = $_FILES['fileselect'];
	$fn = $_REQUEST['fname'];
	$file_type_new = $_FILES['fileselect']['type'];
	
	$checkext = explode(".",$fn);	
	$ext = $checkext[count($checkext)-1];
	
	if(isset($_REQUEST['ie_version']) and $_REQUEST['ie_version'] <= '9' and isset($_REQUEST['browser']) and $_REQUEST['browser'] == 'Internet Explorer') {
		$vari = 0;
		foreach($type_array as $val_new ){
			$val_array = explode('|', $val_new);
			if( count($val_array) > 0 ){
				foreach($val_array as $new_ext ){ 
					if( trim($new_ext) == $ext )
						$vari++;
				}
			}
			
		}
		if( $vari > 0 )
			$ie89_validation = true;
		else
			$ie89_validation = false;
					
	} else {
		$ie89_validation = false;
	}
	
	
	
	if($ext!="php" && $ext!="php3" && $ext!="php4" && $ext!="php5" && $ext!="pl" && $ext!="py" && $ext!="jsp" && $ext!="asp" && $ext!="exe" && $ext!="cgi")
	{
		if( ( is_array($type_array) and count($type_array) > 0 and in_array($file_type_new, $type_array) and $is_preview == 0 ) or (count($type_array) == 0 and  $is_preview == 0) or $ie89_validation ){
			
			move_uploaded_file(
					$files['tmp_name'],
					$upload_main_url.'userfiles/' . $fn
				);
			
			$pos = strpos('jpg, jpeg, jpe, gif, png, bmp, tif, tiff, ico,', $ext);
			if($pos !== false)
			{
				$image = new SimpleImage();		
				$image->load($upload_main_url.'userfiles/'.$fn);
				$image->resizeToHeight(100);
				$image->save($upload_main_url.'userfiles/thumbs/'.$fn);
			}
			//changes end here. 
			echo "<p class='uploaded'>|$fn|$field_id</p>";
		} else {
			echo "<p class='error_upload'>file type not allowed</p>";
		}
	}
	else
	{
		echo "<p class='error_upload'>file type not allowed</p>";
	}	
	
}