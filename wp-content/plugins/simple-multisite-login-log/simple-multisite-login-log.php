<?php

/*
  Plugin Name: Simple Network Login Log
  Version: 0.6.2
  Description: Track user logins across your Multisite network. Site owners see logins for their site. Network admins see all logins in their dashboard. Compatible with wp-multi-network
  Author: Jordan Beaver
  Author URI: http://jordan.beaveris.me
  Plugin URI: https://github.com/gehidore/simple-multisite-login-log
  Network: true
  Text Domain: snll
 */


new SimpleNetworkLoginLog();

/**
 * @todo For site - display only logins for that site
 * @todo On a multinetwork - display only logins for that network
 * @todo If current network admin area is from blog 1 display all logins across all networks
 *  
 */
class SimpleNetworkLoginLog {

    /**
     * Holds the version of the database required 
     * for the current version of the plugin
     * 
     * @var integer 
     */
    private $mDbVersion = '1.0';

    /**
     * Name of the table to store logs in
     * 
     * @var string 
     */
    private $mTable;

    public function __construct() {
	global $wpdb;

	// Register activation/install hook
	register_activation_hook( __FILE__, array( $this, 'activate' ) );

	// Save name of login table
	$this->mTable = $wpdb->get_blog_prefix( 0 ) . 'simple_network_login_log';


	// Save login action
	add_action( 'init', array( $this, 'initLoginAction' ) );


	add_action( 'network_admin_menu', array( $this, 'addNetworkAdminMenu' ) );
	add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
	add_action( 'admin_head', array( $this, 'screenOptions' ) );
    }

    /**
     * Fires on plugin activation. Will create the log table
     * 
     * @global WPDB $wpdb 
     */
    public function activate() {
	// Pull in wpdb
	global $wpdb;

	$cur_db_version = get_option( 'snll_db_version' );

	// Setup new database table if it needs to be setup
	// http://codex.wordpress.org/Creating_Tables_with_Plugins
	if ( true || $cur_db_version != $this->mDbVersion ) {

	    if ( !$wpdb->get_row( "SHOW TABLES LIKE '{$this->mTable}'" ) ) {
		$sql = "CREATE TABLE  " . $this->mTable . "
                    ( 
                        id INT( 11 ) NOT NULL AUTO_INCREMENT ,
			site_id INT( 11 ) NOT NULL,
			blog_id INT( 11 ) NOT NULL,
                        uid INT( 11 ) NOT NULL ,
                        user_login VARCHAR( 255 ) NOT NULL ,
			password VARCHAR ( 255 ) NOT NULL,
                        user_role VARCHAR( 255 ) NOT NULL ,
			time TIMESTAMP DEFAULT NOW() NOT NULL ,
                        ip VARCHAR( 100 ) NOT NULL ,
                        login_result TINYINT ( 1 ) ,
                        data LONGTEXT NOT NULL ,
			user_agent VARCHAR( 255 ) NOT NULL,
                        PRIMARY KEY ( id ) ,
                        INDEX ( uid, ip, login_result )
                    );";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		//update_option( "snll_db_version", $this->mDbVersion );
	    }
	}
    }

    /**
     * Sets up the hooks to login functions
     *  
     */
    public function initLoginAction() {

	// Successful login
	add_action( 'wp_login', array( $this, 'loginSuccess' ) );

	//Action on failed login
	add_action( 'wp_login_failed', array( $this, 'loginFailed' ) );
    }

    /**
     * Fires when a login has been successful
     * 
     * @param string $user - Username
     */
    public function loginSuccess( $user_login ) {
	// pwd
	// password
	$this->logAttempt( $user_login, 1 );
    }

    public function loginFailed( $user_login ) {
	$this->logAttempt( $user_login, 0 );
    }

    /**
     * Logs the result of the login attempt
     * 
     * @param string $user
     * @param boolean $result 
     */
    public function logAttempt( $user_login, $result ) {
	global $wpdb, $current_site, $blog_id;



	/**
	 * If we have a valid user attempt to get their info 
	 */
	$userdata = get_user_by( 'login', $user_login );

	// Id of the valid user
	$uid = ( $userdata && $userdata->ID ) ? $userdata->ID : 0;

	// Get user role
	$user_role = '';
	if ( $uid ) {
	    $user = new WP_User( $uid );
	    if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
		$user_role = implode( ', ', $user->roles );
	    }
	}


	/*
	 * Log password if user attempt is bad
	 */
	$password = '';
	if ( !$result ) {
	    
	    if ( isset( $_POST['pwd'] ) )
		$password = $_POST['pwd'];
	    else if ( isset( $_POST['password'] ) )
		$password = $_POST['password'];
	}
	
	$site_id = ( isset( $GLOBALS['site_id'] ) )? $GLOBALS['site_id']: 1;


	$data = array( 
	    'uid' => $uid,
	    'user_login' => $user_login,
	    'user_role' => $user_role,
	    'password' => $password,
	    'site_id' => $site_id,
	    'blog_id' => $GLOBALS['blog_id'],
	    'ip' => isset( $_SERVER['HTTP_X_REAL_IP'] ) ? esc_attr( $_SERVER['HTTP_X_REAL_IP'] ) : esc_attr( $_SERVER['REMOTE_ADDR'] ),
	    'login_result' => $result,
	    'user_agent' => $_SERVER['HTTP_USER_AGENT']
	 );

	$wpdb->insert( $this->mTable, $data );
    }

    public function addNetworkAdminMenu() {
	add_users_page( 'Login Log', 'Login Log', 'add_users', 'snll_network_menu', array( $this, 'renderNetworkAdminMenu' ) );
    }

    public function renderNetworkAdminMenu() {
	require_once( 'net-admin-login-log-table.php' );
	
	$myListTable = new NetAdminLoginLogTable( $this->mTable );
	echo '<div class="wrap"><h2>Login Log</h2>';
	$myListTable->prepare_items();
	$myListTable->display();
	echo '</div>';
    }

    public function addAdminMenu() {
	add_users_page( 'Login Log', 'Login Log', 'add_users', 'snll_admin_menu', array( $this, 'renderAdminMenu' ) );
    }

    public function renderAdminMenu() {
	require_once( 'login-log-table.php' );
	
	$myListTable = new LoginLogTable( $this->mTable );
	echo '<div class="wrap"><h2>Login Log</h2>';
	$myListTable->prepare_items();
	$myListTable->display();
	echo '</div>';
    }

    public function screenOptions() {   

        //execute only on network or site login_log page, othewise return null
        $page = ( isset( $_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        if( 'snll_network_menu' == $page OR 'snll_admin_menu' == $page ){

        $current_screen = get_current_screen();

        //define options
        $per_page_field = 'per_page';
        $per_page_option = $current_screen->id . '_' . $per_page_field;

        //Save options that were applied
        if( isset( $_REQUEST['wp_screen_options'] ) && isset( $_REQUEST['wp_screen_options']['value'] ) )
        {   
            update_option( $per_page_option, esc_html( $_REQUEST['wp_screen_options']['value'] ) );
        }   

        //prepare options for display

        //if per page option is not set, use default
        $per_page_val = get_option( $per_page_option, 20 );
        $args = array( 'label' => __( 'Records', 'sll' ), 'default' => $per_page_val );

        //display options
        add_screen_option( $per_page_field, $args );
        $_per_page = get_option( 'users_page_login_log_per_page' );
	} else {

	    return;
	}
    }   

}

// end class
