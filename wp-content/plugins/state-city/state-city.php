<?php 
/*
Plugin Name: States Cities
Plugin URI: http://8manos.com/
Description: This will be used to show states and cities
Author: Manish R
Text Domain: States Cities
Domain Path: /languages/
Version: 0.1.0
*/
 
//error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
//ini_set('display_errors', 1);
//ini_set('display_startup_error', 1);
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

add_action('admin_menu', array('States_Cities', 'my_menu_pages'));
//add_action('admin_init', array('States_Cities', 'init'));

if(!class_exists('States_Cities')){
class States_Cities{
	const iZIP = 0 ;
	const iCITY = 1 ;
	const iSTATECODE = 2 ;
	const iSTATE = 3 ;
	public function __construct(){
		
	// Install plugin
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}
	public function activate(){
		require_once("state_install.php");
			states_cities_install();
	}
	public function deactivate(){
		require_once("state_install.php");
			states_cities_uninstall();
	}
	public function init(){
    wp_enqueue_script('media-upload');  // For WP media uploader
		wp_enqueue_script('thickbox');  // For WP media uploader
		wp_enqueue_script('jquery-ui-tabs');  // For admin panel page tabs
		wp_enqueue_script('jquery-ui-dialog');  // For admin panel popup alerts
  	wp_enqueue_script( 'import_state_city', plugins_url( '/state-city.js', __FILE__ ), array('jquery') );
  	wp_enqueue_style('import_state_city', plugins_url( '/state-city.css', __FILE__ )); 			

  	States_Cities::renderTable1();
	}
	
	//This will be used for saving cities
	public function saveCities($data){
		global $wpdb;							
//		print_r($data);
		$sql = $wpdb->prepare("select count(*) from  ".$wpdb->prefix."cities where state_id ='%d' and city = '%s'", array($data[iSTATECODE], $data[iCITY]));
		$state = $wpdb->get_results ($sql);
		
		if($wpdb->num_rows == 0 ){
			$rs = $wpdb->insert( $wpdb->prefix . "cities" , 
																		 array('zip' => $data[iZIP] 
																					,'city'=> $data[iCITY]
																					,'state_id'=> $data[iSTATECODE]
																					),
																		array('%d','%s','%d'));
		}
	}
	
	//This will be used for saving States
	public function saveStates($data){
		global $wpdb;
		
		$sql = $wpdb->prepare("select state_code, state_name  from  ". $wpdb->prefix ."states where state_name='%s'", $data[iSTATE]);

		$state = $wpdb->get_results($sql);
		
		if($wpdb->num_rows == 0 ){
		
			$rs = $wpdb->insert( $wpdb->prefix . "states" , array('state_code' => $data[iSTATECODE] 
																					,'state_name'=> $data[iSTATE]),
																		array('%d','%s'));
		
			return $wpdb->insert_id;
		} else{
			
			foreach ($state as $s){
				$stateCode = $s->state_code ;
				break ;
			}
			
			return $stateCode ; 
		}
	
	}
	
	public function parseCSV(){
		global $wpdb;	

		if (isset($_POST['submit'])) {
			if (is_uploaded_file($_FILES['filename']['tmp_name'])):
					//Import uploaded file to Database
					$handle = fopen($_FILES['filename']['tmp_name'], "r");
					
					$i=0;
					while (($data = fgetcsv($handle)) !== FALSE):
							$i++;
								if($i==1) continue;
							$id = self::saveStates($data);
							self::saveCities($data);
					endwhile;
					fclose($handle);
					if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
								echo "<h2>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h2>";
					}
			endif;	//if file uploaded properly
		}

	}
		
	// Showing all the states and Cities
	public function listData(){
			global $wpdb;
			$rows = array();
			$result = $wpdb->get_results ( "select c.zip,c.city,c.state_id, s.state_name from ".$wpdb->prefix."cities c , 
																				".$wpdb->prefix."states s  
																				where c.state_id=s.state_code order by state_name,city " );
				foreach($result as $val){
					$rows[$val->zip] = array(
					'zip' => $val->zip,
					'city' =>$val->city,
					'state_code' => $val->state_id,
					'state_name'=> $val->state_name ,
					);
				}
				//print_r($rows);
				return $rows;	
	}
	
	//used for bluck delete
	public function bulkDelete(){
	
	}

	//Rendering Table
	public function renderTable1(){ 
		if($_POST['submit'] != ""){
			self::parseCSV();
		}
	?>
		
			<div  class="wrap">
			<h2><?php _e('Import State City',''); ?></h2>
			<div id="tabs" class="tabs">
								<ul>
									<li><a class="nav-tab" href="#tabs-1"><?php _e('Upload',''); ?></a></li>
									<li><a class="nav-tab" href="#tabs-2"><?php _e('Report',''); ?></a></li>
							</ul>
				<div id="tabs-1">	
					<br/>
						<form enctype='multipart/form-data'  method='post'>
						<input type='hidden' name='action' value="importcsv">
						<label> Select File to import:<br /></label>
						<input size='50' type='file' name='filename'><br /><br />
						<input type='submit' class='button-primary' name='submit' value='Upload'>
					</form>					
				</div>
				<div id="tabs-2">
					<h2>State City Table</h2>		
					<?php 
					echo States_Cities::listData();
					?>
				</div>
			</div> <!--wrap-->
	<?php }
	
	//Form Menu
	function my_menu_pages(){
		$import_state_city	=	add_menu_page('States Cities', 'States Cities', 'manage_options', 
		'states_cities', array('States_Cities', 'init') );
	}

	
		//Create menu
		function menu_list(){

		}
	} // Class
	
	add_action( 'importcsv', 'parseCSV' );	
	$ob = new States_Cities ;
	
	function parseCSV(){
		echo "Manish ";
		die;
		$ob->parseCSV();
	}
}// If 


