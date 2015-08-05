<?php

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * @see http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
 * @see http://wp.smashingmagazine.com/2011/11/03/native-admin-tables-wordpress/ 
 */
class NetAdminLoginLogTable extends WP_List_Table {

    private $mTable;

    function __construct( $table ) {
	$this->mTable = $table;
	
	parent::__construct( array() );
        }

    function get_columns() {
	$columns = array( 
	    'id' => 'Login ID',
	    'site_id' => 'Network',
	    'blog_id' => 'Site',
	    'user_login' => 'Username',
	    'display_name' => 'Name',
	    'user_role' => 'User Role',
	    'time' => 'Time',
	    'login_result' => 'Login Result',
	 );
	return $columns;
    }

    function get_sortable_columns() { 
        $columns = array( 
            'id' => array( 'id', false ),
            'site_id' => array( 'site_id', false ),
            'blog_id' => array( 'blog_id', false ),
            'user_login' => array( 'user_login', false ),
            /*'display_name' => array( 'display_name', false ),*/
            'user_role' => array( 'user_role', false ),
            'time' => array( 'time', false ),
            'login_result' => array( 'login_result', false ),
         );
        return $columns;
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers, $blog_id;
        $screen = get_current_screen();
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );
	$where = '';
	
        /* -- Preparing your query -- */
	if( isset( $GLOBALS['site_id'] ) && '1' != $GLOBALS['site_id'] )
	    $where = "site_id='{$GLOBALS['site_id']}'";

	$query = "SELECT * FROM $this->mTable $where";

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty( $_GET["orderby"] ) ? mysql_real_escape_string( $_GET["orderby"] ) : 'ASC';
        $order = !empty( $_GET["order"] ) ? mysql_real_escape_string( $_GET["order"] ) : '';
        if( !empty( $orderby ) & !empty( $order ) ){ 
	    $query .=' ORDER BY '.$orderby.' '.$order;
	} else {
	    $query .=' ORDER BY time DESC';
	}

        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query( $query ); //return the total number of affected rows

        /*How many to display per page?*/
        //define options
        $per_page_field = 'per_page';
        $per_page_option = $screen->id . '_' . $per_page_field;

        $perpage = get_option( $per_page_option, 20 );

        //Which page is this?
        $paged = !empty( $_GET["paged"] ) ? mysql_real_escape_string( $_GET["paged"] ) : '';

        //Page Number
        if( empty( $paged ) || !is_numeric( $paged ) || $paged<=0 ){ $paged=1; }

        //How many pages do we have in total?
        $totalpages = ceil( $totalitems/$perpage );

        //adjust the query to take pagination into account
            if( !empty( $paged ) && !empty( $perpage ) ){
                    $offset=( $paged-1 )*$perpage;
                $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
            }

        /* -- Register the pagination -- */
                $this->set_pagination_args( array(
                        "total_items" => $totalitems,
                        "total_pages" => $totalpages,
                        "per_page" => $perpage,
                ) );
                //The pagination links are automatically built according to those parameters

        /* -- Register the Columns -- */
                $columns = $this->get_columns();
                $_wp_column_headers[$screen->id]=$columns;

        /* -- Fetch the items -- */
                $this->items = $wpdb->get_results( $query, 'ARRAY_A' );
    }

    function column_default( $item, $column_name ) {
	switch ( $column_name ) {
	    case 'display_name':

		if ( is_numeric( $item['uid'] ) && $item['uid'] > 0 ) {
		    $user = get_userdata( $item['uid'] );
		    return $user->display_name . $this->row_actions( array( '<a href="'.admin_url().'network/user-edit.php?user_id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fnetwork%2Fusers.php?page=snll_network_menu">Edit</a>', '<a href="'.wp_nonce_url( admin_url().'network/users.php', 'deleteuser' ).'&action=deleteuser&id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fnetwork%2Fusers.php?page=snll_network_menu">Remove</a>' ) );
		}
		else
		    return '';
		break;

	    case 'site_id':
		$name = $this->get_network_meta( $item['site_id'], 'site_name' );
		return $name[0]['meta_value'];
		break;

	    case 'blog_id':
		$name = $this->get_site_meta( $item['blog_id'], 'blogname' );
		return $name;
		break;
	    default:
		return $item[$column_name]; //Show the whole array for troubleshooting purposes
	}
    }

    function get_network_meta( $site_id, $name ) {
	global $wpdb;

	$table = $wpdb->get_blog_prefix( 0 ) . 'sitemeta';

	$sql = "SELECT * FROM $table WHERE meta_key='$name' AND site_id='$site_id'";
	$data = $wpdb->get_results( $sql, 'ARRAY_A' );
	return $data;
    }

    function get_site_meta( $blog_id, $name ) {

	return get_blog_option( $blog_id, $name );
	global $wpdb;

	$table = $wpdb->get_blog_prefix( 0 ) . 'sitemeta';

	$sql = "SELECT * FROM $table WHERE meta_key='$name' AND site_id='$site_id'";
	$data = $wpdb->get_results( $sql, 'ARRAY_A' );
	return $data;
    }

}
