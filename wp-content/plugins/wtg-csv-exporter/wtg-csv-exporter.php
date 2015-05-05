<?php         
/*
Plugin Name: WTG CSV Exporter Beta for WordPress
Version: 0.0.4
Plugin URI: http://www.webtechglobal.co.uk
Description: Export WP data to .csv files with intention of creating a file for using with other software.
Author: Ryan Bayne
Author URI: http://www.webtechglobal.co.uk
Last Updated: March 2015
Text Domain: wtgcsvexporter
Domain Path: /languages

GPL v3 

This program is free software downloaded from WordPress.org: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. This means
it can be provided for the sole purpose of being developed further
and we do not promise it is ready for any one persons specific needs.
See the GNU General Public License for more details.

See <http://www.gnu.org/licenses/>.
*/           
  
// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'Direct script access is not allowed!' );

// exit early if WTG CSV Exporter doesn't have to be loaded
if ( ( 'wp-login.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) ) // Login screen
    || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
    || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
    return;
}
              
// package variables
$wtgcsvexporter_currentversion = '0.0.4';# to be removed, version is now in the WTGCSVEXPORTER() class 
$wtgcsvexporter_debug_mode = false;# to be phased out, going to use environment variables (both WP and php.ini instead)

// go into dev mode if on test installation (if directory contains the string you will see errors and other fun stuff for geeks)               
if( strstr( ABSPATH, 'wtgcsvexporter' ) ){
    $wtgcsvexporter_debug_mode = true;     
}               

// avoid error output here and there for the sake of performance...              
if ( ( 'wp-login.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) )
        || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
        || ( defined( 'DOING_CRON' ) && DOING_CRON )
        || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
    $wtgcsvexporter_debug_mode = false;
}                   

// define constants, feel free to add some of your own...                              
if(!defined( "WTGCSVEXPORTER_NAME") ){define( "WTGCSVEXPORTER_NAME", 'WTG CSV Exporter Beta' );} 
if(!defined( "WTGCSVEXPORTER__FILE__") ){define( "WTGCSVEXPORTER__FILE__", __FILE__);}
if(!defined( "WTGCSVEXPORTER_BASENAME") ){define( "WTGCSVEXPORTER_BASENAME",plugin_basename( WTGCSVEXPORTER__FILE__ ) );}
if(!defined( "WTGCSVEXPORTER_ABSPATH") ){define( "WTGCSVEXPORTER_ABSPATH", plugin_dir_path( __FILE__) );}//C:\AppServ\www\wordpress-testing\wtgplugintemplate\wp-content\plugins\wtgplugintemplate/  
if(!defined( "WTGCSVEXPORTER_PHPVERSIONMINIMUM") ){define( "WTGCSVEXPORTER_PHPVERSIONMINIMUM", '5.3.0' );}// The minimum php version that will allow the plugin to work                                
if(!defined( "WTGCSVEXPORTER_IMAGES_URL") ){define( "WTGCSVEXPORTER_IMAGES_URL",plugins_url( 'images/' , __FILE__ ) );}
if(!defined( "WTGCSVEXPORTER_FREE") ){define( "WTGCSVEXPORTER_FREE", 'paid' );} 
        
// require main class...
require_once( WTGCSVEXPORTER_ABSPATH . 'classes/class-wtgcsvexporter.php' );

// call the Daddy methods here or remove some lines as a quick configuration approach...
$WTGCSVEXPORTER = new WTGCSVEXPORTER();
$WTGCSVEXPORTER->custom_post_types();

// localization because we all love speaking a little chinese or russian or Klingon!
// Hmm! has anyone ever translated a WP plugin in Klingon?
function wtgcsvexporter_textdomain() {
    load_plugin_textdomain( 'wtgcsvexporter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'wtgcsvexporter_textdomain' );                                                                                                       
?>