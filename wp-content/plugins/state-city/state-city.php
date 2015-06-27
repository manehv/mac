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

//add_action('admin_menu', array('States_Cities', 'my_menu_pages'));
//add_action('admin_init', array('States_Cities', 'init'));

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if(!class_exists('States_Cities')){
class States_Cities extends WP_List_Table {
	const iZIP = 0 ;
	const iCITY = 1 ;
	const iSTATECODE = 2 ;
	const iSTATE = 3 ;
	public function __construct(){
		add_action('admin_menu', array(&$this, 'my_menu_pages'));
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

  	$this->renderTable1();
	}
	public function get_columns(){
		$columns = array(
			'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
			'zip' => 'Zip',
			'city'    => 'City',
			'stateCode'      => 'State Code',
			'state'      => 'State Name'
		);
		return $columns;
	}	
	public function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $this->listData();
	}
	//This will be used to display column name
	function column_default($item, $column_name){
			switch($column_name){
					case 'zip':
					case 'state':
					case 'stateCode':
					case 'city':
							return $item[$column_name];
					default:
							return print_r($item,true); //Show the whole array for troubleshooting purposes
			}
	}	
	
	function get_sortable_columns() {
		$sortable_columns = array(
			'zip'  => array('zip',false),
			'city' => array('city',false),
			'stateCode'   => array('stateCode',false),
			'state'   => array('state',false)
		);
		return $sortable_columns;
	}
    function column_title($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&movie=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&movie=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }    
	//This will be used for saving cities
	public function saveCities($data){
		global $wpdb;							
//		print_r($data);
		$sql = $wpdb->prepare("select * from  ".$wpdb->prefix."cities where state_id ='%d' and city = '%s'", array($data[self::iSTATECODE], $data[self::iCITY]));
		$state = $wpdb->get_results ($sql);
		
		if($wpdb->num_rows == 0 ){
			$rs = $wpdb->insert( $wpdb->prefix . "cities" , 
																		 array('zip' => $data[self::iZIP] 
																					,'city'=> $data[self::iCITY]
																					,'state_id'=> $data[self::iSTATECODE]
																					),
																		array('%d','%s','%d'));
		}
	}
	
	//This will be used for saving States
	public function saveStates($data){
		global $wpdb;
		 $sql = $wpdb->prepare("select state_code, state_name  from  ". $wpdb->prefix ."states where state_name='%s' ", array($data[self::iSTATE]));
		
		$state = $wpdb->get_results($sql);
		$wpdb->num_rows ;
		
		//print_r($data);
		//die;
		if($wpdb->num_rows == 0 ){
			//echo $sql . "<br>";
			
			$rs = $wpdb->insert( $wpdb->prefix . "states" , array('state_code' => $data[self::iSTATECODE] 
																					,'state_name'=> $data[self::iSTATE]),
																		array('%d','%s'));
		
			return $wpdb->insert_id;
		} else{
			/*
			foreach ($state as $s){
				$stateCode = $s->state_code ;
				break ;
			}
			*/
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
								//print_r($data);
								//continue ;
							
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
					$rows[] = array(
					'zip' => $val->zip,
					'city' =>$val->city,
					'stateCode' => $val->state_id,
					'state'=> $val->state_name ,
					);
				}
				return $rows;	
	}
	
	//used for bluck delete
	public function bulkDelete(){
		global $wpdb;
			$wpdb->delete(
				"{$wpdb->prefix}cities",
				[ 'ID' => $id ],
				[ '%d' ]
			);	
	}
  public function showList(){
		//$ob1 = new States_Cities() ;
		$this->prepare_items(); 
		$this->display();
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
					  $ob1 = new States_Cities() ;
						$ob1->prepare_items(); 
						$ob1->display();
					?>
				</div>
			</div> <!--wrap-->
	<?php }
	
	//Form Menu
	function my_menu_pages(){
		$import_state_city	=	add_menu_page('States Cities', 'States Cities', 'manage_options', 
		'states_cities', array(&$this, 'init') );
		add_submenu_page('states_cities', 'List of Cities', 'List of Cities', 'manage_options', 'list_cities', array( new States_Cities(), 'showList'));
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


