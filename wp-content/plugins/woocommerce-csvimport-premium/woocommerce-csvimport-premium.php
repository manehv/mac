<?php
/*
Plugin Name: Woocommerce CSV Import Premium add-on
#Plugin URI: http://allaerd.org/
Description: Import grouped, external products and other fields that are not available on the Free version.
Version: 2.0
Author: Allaerd Mensonides
License: GPLv2 or later
Author URI: http://allaerd.org
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include('include/class-woocsv-premium.php');

/* 
//init the class after woocommerce csv import is loaded
add_action('woocsvAfterInit', 'woocsvPremiumInit');

//init 
function woocsvPremiumInit()
{
	$woocsvPremium = new woocsvPremium();
}
*/

