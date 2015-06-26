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
 
	
add_action('admin_menu', 'my_menu_pages');
function my_menu_pages(){
 $import_state_city=add_menu_page('Import State City', 'Import State City', 'manage_options', 'import_state_city', 'import_state_city_menu_page' );
}

 function import_state_city_menu_page() { 
	global $wpdb;
    wp_enqueue_script('media-upload');  // For WP media uploader
		wp_enqueue_script('thickbox');  // For WP media uploader
		wp_enqueue_script('jquery-ui-tabs');  // For admin panel page tabs
		wp_enqueue_script('jquery-ui-dialog');  // For admin panel popup alerts
  	wp_enqueue_script( 'import_state_city', plugins_url( '/state-city.js', __FILE__ ), array('jquery') );
  	
  	wp_enqueue_style('import_state_city', plugins_url( '/state-city.css', __FILE__ )); ?>
 	
 
<div  class="wrap">
<h2><?php _e('Import State City',''); ?></h2>
<div id="tabs" class="tabs">
					<ul>
    				<li><a class="nav-tab" href="#tabs-1"><?php _e('Upload',''); ?></a></li>
    				<li><a class="nav-tab" href="#tabs-2"><?php _e('Report',''); ?></a></li>
        </ul>
	<div id="tabs-1">
	<?php
	if (isset($_POST['submit'])) {

		if (is_uploaded_file($_FILES['filename']['tmp_name'])):
					//Import uploaded file to Database
					$handle = fopen($_FILES['filename']['tmp_name'], "r");
					
					$i=0;
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE):
							$i++;
								if($i==1) continue;
							global $wpdb;							
							$state = $wpdb->get_results ( "select state_name  from  state where state_name='".$data[3]."' " );
							$city = $wpdb->get_results ( "select zip , city from  city where zip='".$data[0]."' and city='".$data[1]."'" );
							if(count($state)==0){			
							$a2 = $wpdb->query("INSERT INTO state (state_code,state_name) VALUES('".$data[2]."','".$data[3]."')");
							}
							if(count($city)==0){							
							$a1 =$wpdb->query("INSERT INTO city(zip,city,state_id) VALUES('".$data[0]."','".$data[1]."','".$data[2]."')");
							}
					endwhile;
					fclose($handle);
						if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
									echo "<h2>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h2>";
						}
			endif;	//if file uploaded properly
			
		}else { ?>
		
				
				<br/>
				<form enctype='multipart/form-data'  method='post'>

				<label> Select File to import:<br /></label>

				<input size='50' type='file' name='filename'><br /><br />

				<input type='submit' class='button-primary' name='submit' value='Upload'>
				
				</form>
	<?php } ?>
	</div> <!--tab-1-->
	<div id="tabs-2">
		<h2>State City Table</h2>
		<?php
		if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		function get_state_city_data(){
			global $wpdb;
			$rows = array();
			$result = $wpdb->get_results ( "select c.zip,c.city,c.state_id, s.state_name from wp_cities c , wp_states s  
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
		class My_List_Table extends WP_List_Table {
						
			function column_cb( $item ) {
			$array = get_state_city_data();
			foreach($array as $key=>$value){
			//$zip=$key;
			}
			//$zip=reset($array);
			//print_r($zip);
					return sprintf(
						'<input type="checkbox" name="bulk-delete[]" value="'.$zip['zip'].'" />', 
						$this->_args['singular'], 
						$item['ID']
							);
			}
			function get_columns(){
					$columns = array(
						'cb'           => '<input type="checkbox" />',
						'zip' => 'Zip',
						'city'    => 'City',
						'state_code' => 'State Code',
						'state_name' => 'State Name'
					);
					return $columns;
			}
			
			function prepare_items() {
					$columns = $this->get_columns();
					$hidden = array();
					$sortable = array();
					$this->_column_headers = array($columns, $hidden, $sortable);
					$per_page = 50;
					$current_page = $this->get_pagenum();
					$total_items = count(get_state_city_data());
					// only ncessary because we have sample data
					$this->found_data = array_slice(get_state_city_data(),(($current_page-1)*$per_page),$per_page);
					$this->set_pagination_args( array(
								'total_items' => $total_items,                  //WE have to calculate the total number of items
								'per_page'    => $per_page                     //WE have to determine how many items to show on a page
					) );
					$this->process_bulk_action();
					?>
					<form method="post">
					<input type="hidden" name="page" value="my_list_test" />
					<?php $this->search_box('search', 'search_id'); ?>
					</form>
					<?php
					$this->items = $this->found_data;
			}	
			function process_bulk_action() {        
      
        if( 'delete'===$this->current_action() ) {
           foreach($_GET['bulk-delete'] as $id) {                             
                multiple_delete($id);
            }
        }        
			}
			function column_default( $item, $column_name ) {
					switch( $column_name ) { 
						case 'cb':
						case 'zip':
						case 'city':
						case 'state_code':
						case 'state_name':
							return $item[ $column_name ];
						default:
							return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
					}
			}
			function get_bulk_actions() {
				$actions = array(
				'delete'    => 'Delete'
				);
				return $actions;
			}
			
		}
		$myListTable = new My_List_Table();
		
	
    $myListTable->prepare_items(); 
    $myListTable->display(); 
		?>
	</div> <!--tabs-2-->
	</div> <!--tabs-->
	</div> <!--wrap-->
	
<?php } ?>