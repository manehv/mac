<?php 
/*
Plugin Name: State City Dropdown
*/

add_action('admin_menu', 'my_menu_pages');
function my_menu_pages(){
    add_menu_page('Import State City', 'Import State City', 'manage_options', 'my-menu', 'import_state_city_menu_page' );
    
}
 function import_state_city_menu_page() {
?>

<div  class="wrap">
<h2><?php _e('Import State City',''); ?></h2>
<?php
if (isset($_POST['submit'])) {

	    if (is_uploaded_file($_FILES['filename']['tmp_name'])) {

	        echo "<hr/><h2>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h2>";

	        echo "<h3>Displaying contents:</h3>";

	        readfile($_FILES['filename']['tmp_name']);

	    }

	    //Import uploaded file to Database

	    $handle = fopen($_FILES['filename']['tmp_name'], "r");
			
			$i=0;
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					 $i++;
						if($i==1) continue;
	        $import="INSERT into state_city(zip,city,state_code,state_name) values('$data[0]','$data[1]','$data[2]','$data[3]')";

	        mysql_query($import) or die(mysql_error());
	    }
	    fclose($handle);
	    echo "Import done";
	    //view upload form
	}else { ?>
	
			<hr>
			<br/>
	    <form enctype='multipart/form-data'  method='post'>

	    <label> Select File to import:<br /></label>

	    <input size='50' type='file' name='filename'><br /><br />

	    <input type='submit' class='button-primary' name='submit' value='Upload'>
	    
	    </form>
<?php } ?>

	</div>
<?php } ?>