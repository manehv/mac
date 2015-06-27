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

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
ini_set('display_startup_error',1);

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
		global $status, $page;
		$this->per_page = 30 ;
		//Set parent defaults

		parent::__construct( array(
				'singular'  => 'city',     //singular name of the listed records
				'plural'    => 'cities',    //plural name of the listed records
				'ajax'      => false        //does this table support ajax?
		) );

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
			'cb'        => '<input type="checkbox" name="bulk[]" />', //Render a checkbox instead of text
			'city'    => 'City',
			'zip' => 'Zip',
			'state_name'      => 'State Name',
			'state_code'      => 'State Code'
		);
		return $columns;
	}
	public function get_columns_forState(){
		$columns = array(
			'cb'        => '<input type="checkbox" name="bulk[]" />', //Render a checkbox instead of text
			'state_name'      => 'State Name',
			'state_code'      => 'State Code',
			'cities'      => 'Total Cities'
		);
		return $columns;
	}
	
	function prepare_items_forState(){
		global $wpdb;
		$columns = $this->get_columns_forState();
		$hidden = array();
		$sortable = $this->get_sortable_columns_forState();
		$this->process_bulk_action();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$data = $this->listDataStates() ;
		$this->items = $data;
		$per_page = $this->per_page ;	
		$current_page = $this->get_pagenum();
		$total_items = $this->getCountDataState();
		$this->set_pagination_args( array(
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
				'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
		) );
	}
	public function prepare_items() {
		global $wpdb;

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->process_bulk_action();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$data = $this->listData() ;
		$this->items = $data;
		$per_page = $this->per_page ;	
		$current_page = $this->get_pagenum();
		$total_items = $this->getCountData();
		$this->set_pagination_args( array(
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
				'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
		) );

	}
	function getCountDataState(){
		global $wpdb ;
		$sql = "select count(*) from ".$wpdb->prefix."states s ";
		$cnt = $wpdb->get_var($sql);
		if($cnt == null) $cnt = 0 ;
		return $cnt ;

	}

	//Find Total Records
	function getCountData(){
		global $wpdb ;
		$sql = "select count(*) from ".$wpdb->prefix."cities c ,
																				".$wpdb->prefix."states s
																				where c.state_id=s.state_code ";
		$cnt = $wpdb->get_var($sql);
		if($cnt == null) $cnt = 0 ;

		return $cnt ;
	}
	//This will be used to display column name
	function column_default($item, $column_name){
			switch($column_name){
					case 'zip':
					case 'state':
					case 'state_code':
					case 'state_name':
					case 'cities':
					case 'city':
							return $item[$column_name];
					default:
							return print_r($item,true); //Show the whole array for troubleshooting purposes
			}
	}
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
    function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
						if(is_array($_GET['city'])){
							foreach($_GET['city'] as $val){
								$this->bulkDelete($val);
							}
						}elseif($_GET['city'] != ""){
							$this->bulkDelete($_GET['city']);
						}elseif(is_array($_GET['state'])){
							foreach($_GET['state'] as $val){
								$this->bulkDeleteState($_GET['state']);
							}
						}elseif($_GET['state'] != ""){
							$this->bulkDeleteState($_GET['state']);
						}
						echo "<div class='updated'><p>Records have been deleted</p></div>";
            //wp_die('Items deleted (or they would be if we had items to delete)!');
        }



    }
	function get_sortable_columns() {
		$sortable_columns = array(
			'zip'  => array('zip',false),
			'city' => array('city',false),
			'state_code'   => array('state_code',false),
			'state_name'   => array('state_name',false)
		);
		return $sortable_columns;
	}
	function get_sortable_columns_forState() {
		$sortable_columns = array(
			'state_code'   => array('state_code',false),
			'state_name'   => array('state_name',false)
		);
		return $sortable_columns;
	}

	function column_state_name($item){
		//Build row actions
		$actions = array(
			//	'edit'      => sprintf('<a href="?page=%s&action=%s&state=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
				'delete'    => sprintf('<a href="?page=%s&action=%s&state=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
		);
		
		//Return the title contents
		return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
				/*$1%s*/ $item['state_name'],
				/*$2%s*/ $item['id'],
				/*$3%s*/ $this->row_actions($actions)
		);
	
	}

    function column_city($item){

        //Build row actions
        $actions = array(
         //	   'edit'      => sprintf('<a href="?page=%s&action=%s&city=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&city=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
        );

        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['city'],
            /*$2%s*/ $item['id'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
    function column_cb($item){
			
			if($_REQUEST['page'] == "list_states")
				$key = 'state' ;
			else
				$key = $this->_args['singular'] ;
				
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $key ,  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
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
					echo "<div class='updated'><p>File \"". $_FILES['filename']['name'] ."\" Imported for cities & states.</p></div>";

					
			endif;	//if file uploaded properly
		}

	}

	// Showing all the states and Cities
	public function listData(){
			global $wpdb;
			$rows = array();
			$current_page = $this->get_pagenum();
			$offset = $this->per_page * ($current_page -1) ;
			if(array_key_exists($_REQUEST['orderby'], $this->get_sortable_columns()) == true ||
				$_REQUEST['orderby'] == ""){
				$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'city'; //If no sort, default to title
			}
			if(in_array($_REQUEST['order'], array('','asc','desc')) == true){
				$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
			}
			$sql = $wpdb->prepare("select c.id, c.zip,c.city,c.state_id, s.state_name from ".$wpdb->prefix."cities c , 
														".$wpdb->prefix."states s  
														where c.state_id=s.state_code order by $orderby $order limit %d, %d" , 
														$offset ,  $this->per_page );
			$result = $wpdb->get_results ($sql );
				foreach($result as $val){
					$rows[] = array(
					'id' => $val->id,
					'zip' => $val->zip,
					'city' =>$val->city,
					'state_code' => $val->state_id,
					'state_name'=> $val->state_name ,
					);
				}
				return $rows;
	}

	// Showing all the states and Cities
	public function listDataStates(){
			global $wpdb;
			$rows = array();
			$current_page = $this->get_pagenum();
			$offset = $this->per_page * ($current_page -1) ;
			if(array_key_exists($_REQUEST['orderby'], $this->get_sortable_columns()) == true || 
				$_REQUEST['orderby'] == ""){
				$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'state_name'; //If no sort, default to title
			}
			if(in_array($_REQUEST['order'], array('','asc','desc')) == true){
				$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
			}
			$sql = $wpdb->prepare("select s.id, state_code, s.state_name, count(distinct c.id) as cities from ".$wpdb->prefix."states s  
														left outer join ".$wpdb->prefix."cities c on c.state_id=s.state_code
														group by state_code
														order by $orderby $order limit %d, %d" , 
														$offset ,  $this->per_page );
			$result = $wpdb->get_results ($sql );
				foreach($result as $val){
					$rows[] = array(
					'id' => $val->id,
					'state_code' => $val->state_code,
					'state_name'=> $val->state_name ,
					'cities'=> $val->cities ,
					);
				}
				return $rows;	
	}	
	//used for bluck delete
	public function bulkDelete($id){
		global $wpdb;
			$wpdb->delete(
				"{$wpdb->prefix}cities",
				array('id' => $id ),
				array('%d')
			);
	}
	public function bulkDeleteState($id){
		global $wpdb;
		$sql = "select state_code from ".$wpdb->prefix."states s ";
		$cnt = $wpdb->get_var($sql);
		if($cnt == null) return 0  ;	
		
		$wpdb->delete(
			"{$wpdb->prefix}cities",
			array('state_id' => $cnt ),
			array('%d')
		);	
		$wpdb->delete(
			"{$wpdb->prefix}states",
			array('id' => $id ),
			array('%d')
		);			
		
	}
  public function showListStates(){
		echo '<h2>List of States</h2>';
		echo '<form id="movies-filter" method="get">'; ?>
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />		
    <?php
		$this->prepare_items_forState(); 
		$this->display();
		echo '</form>';
  }	
  public function showListCity(){
		echo '<h2>List of Cities</h2>';
		echo '<form id="movies-filter" method="get">'; ?>
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->

            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php
		$this->prepare_items();
		$this->display();
		echo '</form>';
  }
	//Rendering Table
	public function renderTable1(){
		if($_POST['submit'] != ""){
			self::parseCSV();
		}
	?>

			<div  class="wrap">
			<h2><?php _e('Import State City',''); ?></h2>
				<form enctype='multipart/form-data'  method='post'>
				<input type='hidden' name='action' value="importcsv">
				<label> Select File to import:<br /></label>
				<input size='50' type='file' name='filename'><br /><br />
				<input type='submit' class='button-primary' name='submit' value='Upload'>
				<p>Just for example you my download this sample file 
					<a href="<?php echo plugins_url( 'import.csv', __FILE__ ); ?>" >CSV File</a> 
					</p>
				</form>
			</div> <!--wrap-->
	<?php }

	//Form Menu
	function my_menu_pages(){
		add_menu_page('states_cities', 'Import Cities', 'manage_options', 'states_cities', array(&$this, 'init') );
		add_submenu_page('states_cities', 'List of Cities', 'List of Cities', 'manage_options', 'list_cities', array( new States_Cities(), 'showListCity'));
		add_submenu_page('states_cities', 'List of States', 'List of States', 'manage_options', 'list_states', array( new States_Cities(),'showListStates'));
	}


	} // Class
	
	$ob = new States_Cities ;

}// If 


