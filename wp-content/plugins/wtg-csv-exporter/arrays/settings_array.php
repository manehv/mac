<?php
/** 
 * Default administration settings for WTG CSV Exporter plugin. These settings are installed to the 
 * wp_options table and are used from there by default. 
 * 
 * @package WTG CSV Exporter
 * @author Ryan Bayne   
 * @since 0.0.1
 * @version 1.0.7
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

// install main admin settings option record
$wtgcsvexporter_settings = array();
// encoding
$wtgcsvexporter_settings['standardsettings']['encoding']['type'] = 'utf8';
// admin user interface settings start
$wtgcsvexporter_settings['standardsettings']['ui_advancedinfo'] = false;// hide advanced user interface information by default
// other
$wtgcsvexporter_settings['standardsettings']['ecq'] = array();
$wtgcsvexporter_settings['standardsettings']['chmod'] = '0750';

##########################################################################################
#                                                                                        #
#                                    WIDGET SETTINGS                                     #
#                                                                                        #
##########################################################################################
$wtgcsvexporter_settings['widgetsettings']['dashboardwidgetsswitch'] = 'disabled';

##########################################################################################
#                                                                                        #
#                               CUSTOM POST TYPE SETTINGS                                #
#                                                                                        #
##########################################################################################
$wtgcsvexporter_settings['posttypes']['wtgflags']['status'] = 'disabled'; 
$wtgcsvexporter_settings['posttypes']['posts']['status'] = 'disabled';

##########################################################################################
#                                                                                        #
#                                    NOTICE SETTINGS                                     #
#                                                                                        #
##########################################################################################
$wtgcsvexporter_settings['noticesettings']['wpcorestyle'] = 'enabled';

##########################################################################################
#                                                                                        #
#                                  LOG SETTINGS                                          #
#                                                                                        #
##########################################################################################
$wtgcsvexporter_settings['logsettings']['uselog'] = 1;
$wtgcsvexporter_settings['logsettings']['loglimit'] = 1000;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['outcome'] = true;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['timestamp'] = true;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['line'] = true;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['function'] = true;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['page'] = true; 
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['panelname'] = true;   
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['userid'] = true;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['type'] = true;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['category'] = true;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['action'] = true;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['priority'] = true;
$wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns']['comment'] = true;
?>