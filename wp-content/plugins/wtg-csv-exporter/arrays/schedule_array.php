<?php
/** 
 * Default schedule array for WTG CSV Exporter plugin 
 * 
 * @package WTG CSV Exporter
 * @author Ryan Bayne   
 * @since 0.0.1
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

$wtgcsvexporter_schedule_array = array();
// history
$wtgcsvexporter_schedule_array['history']['lastreturnreason'] = __( 'None', 'wtgcsvexporter' );
$wtgcsvexporter_schedule_array['history']['lasteventtime'] = time();
$wtgcsvexporter_schedule_array['history']['lasteventtype'] = __( 'None', 'wtgcsvexporter' );
$wtgcsvexporter_schedule_array['history']['day_lastreset'] = time();
$wtgcsvexporter_schedule_array['history']['hour_lastreset'] = time();
$wtgcsvexporter_schedule_array['history']['hourcounter'] = 1;
$wtgcsvexporter_schedule_array['history']['daycounter'] = 1;
$wtgcsvexporter_schedule_array['history']['lasteventaction'] = __( 'None', 'wtgcsvexporter' );
// times/days
$wtgcsvexporter_schedule_array['days']['monday'] = true;
$wtgcsvexporter_schedule_array['days']['tuesday'] = true;
$wtgcsvexporter_schedule_array['days']['wednesday'] = true;
$wtgcsvexporter_schedule_array['days']['thursday'] = true;
$wtgcsvexporter_schedule_array['days']['friday'] = true;
$wtgcsvexporter_schedule_array['days']['saturday'] = true;
$wtgcsvexporter_schedule_array['days']['sunday'] = true;
// times/hours
$wtgcsvexporter_schedule_array['hours'][0] = true;
$wtgcsvexporter_schedule_array['hours'][1] = true;
$wtgcsvexporter_schedule_array['hours'][2] = true;
$wtgcsvexporter_schedule_array['hours'][3] = true;
$wtgcsvexporter_schedule_array['hours'][4] = true;
$wtgcsvexporter_schedule_array['hours'][5] = true;
$wtgcsvexporter_schedule_array['hours'][6] = true;
$wtgcsvexporter_schedule_array['hours'][7] = true;
$wtgcsvexporter_schedule_array['hours'][8] = true;
$wtgcsvexporter_schedule_array['hours'][9] = true;
$wtgcsvexporter_schedule_array['hours'][10] = true;
$wtgcsvexporter_schedule_array['hours'][11] = true;
$wtgcsvexporter_schedule_array['hours'][12] = true;
$wtgcsvexporter_schedule_array['hours'][13] = true;
$wtgcsvexporter_schedule_array['hours'][14] = true;
$wtgcsvexporter_schedule_array['hours'][15] = true;
$wtgcsvexporter_schedule_array['hours'][16] = true;
$wtgcsvexporter_schedule_array['hours'][17] = true;
$wtgcsvexporter_schedule_array['hours'][18] = true;
$wtgcsvexporter_schedule_array['hours'][19] = true;
$wtgcsvexporter_schedule_array['hours'][20] = true;
$wtgcsvexporter_schedule_array['hours'][21] = true;
$wtgcsvexporter_schedule_array['hours'][22] = true;
$wtgcsvexporter_schedule_array['hours'][23] = true;
// limits
$wtgcsvexporter_schedule_array['limits']['hour'] = '1000';
$wtgcsvexporter_schedule_array['limits']['day'] = '5000';
$wtgcsvexporter_schedule_array['limits']['session'] = '300';
// event types (update event_action() if adding more eventtypes)
// deleteuserswaiting - this is the auto deletion of new users who have not yet activated their account 
$wtgcsvexporter_schedule_array['eventtypes']['deleteuserswaiting']['name'] = __( 'Delete Users Waiting', 'wtgcsvexporter' ); 
$wtgcsvexporter_schedule_array['eventtypes']['deleteuserswaiting']['switch'] = 'disabled'; 
?>