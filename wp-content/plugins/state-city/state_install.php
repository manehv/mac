<?php
global $states_cities_version;
$states_cities_version = '1.0';

function states_cities_install() {
	global $wpdb;
	global $states_cities_version;

	$table_state = $wpdb->prefix . 'states';
	$table_cities = $wpdb->prefix . 'cities';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE  $table_cities (
  id int(20) NOT NULL AUTO_INCREMENT,
  zip int(20) NOT NULL,
  city varchar(50) NOT NULL,
  state_id int(20) NOT NULL,
  PRIMARY KEY (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	$sql = "CREATE TABLE $table_state(
	id int(20) not null AUTO_INCREMENT,
  state_code int(20) NOT NULL,
  state_name varchar(50) NOT NULL,
	 PRIMARY KEY (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );	
	
	add_option( 'states_cities_version', $states_cities_version );
}

function states_cities_uninstall(){
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}states" );	
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}cities" );	
}
