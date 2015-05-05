<?php
/** 
 * Database tables information for past and new versions.
 * 
 * This file is not fully in use yet. The intention is to migrate it to the
 * installation class and rather than an array I will simply store every version
 * of each tables query. Each query can be broken down to compare against existing 
 * tables. I find this array approach too hard to maintain over many plugins.
 * 
 * @todo move this to installation class but also reduce the array to actual queries per version
 * 
 * @package WTG CSV Exporter
 * @author Ryan Bayne   
 * @since 0.0.1
 * @version 8.1.2
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
 
 
/*   Column Array Example Returned From "mysql_query( "SHOW COLUMNS FROM..."
        
          array(6) {
            [0]=>
            string(5) "row_id"
            [1]=>
            string(7) "int(11)"
            [2]=>
            string(2) "NO"
            [3]=>
            string(3) "PRI"
            [4]=>
            NULL
            [5]=>
            string(14) "auto_increment"
          }
                  
    +------------+----------+------+-----+---------+----------------+
    | Field      | Type     | Null | Key | Default | Extra          |
    +------------+----------+------+-----+---------+----------------+
    | Id         | int(11)  | NO   | PRI | NULL    | auto_increment |
    | Name       | char(35) | NO   |     |         |                |
    | Country    | char(3)  | NO   | UNI |         |                |
    | District   | char(20) | YES  | MUL |         |                |
    | Population | int(11)  | NO   |     | 0       |                |
    +------------+----------+------+-----+---------+----------------+            
*/
   
global $wpdb;   
$wtgcsvexporter_tables_array =  array();
##################################################################################
#                                 wtglog                                         #
##################################################################################        
$wtgcsvexporter_tables_array['tables']['wtglog']['name'] = $wpdb->prefix . 'wtglog';
$wtgcsvexporter_tables_array['tables']['wtglog']['required'] = false;// required for all installations or not (boolean)
$wtgcsvexporter_tables_array['tables']['wtglog']['pluginversion'] = '0.0.1';
$wtgcsvexporter_tables_array['tables']['wtglog']['usercreated'] = false;// if the table is created as a result of user actions rather than core installation put true
$wtgcsvexporter_tables_array['tables']['wtglog']['version'] = '0.0.1';// used to force updates based on version alone rather than individual differences
$wtgcsvexporter_tables_array['tables']['wtglog']['primarykey'] = 'row_id';
$wtgcsvexporter_tables_array['tables']['wtglog']['uniquekey'] = 'row_id';
// wtglog - row_id
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['row_id']['type'] = 'bigint(20)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['row_id']['null'] = 'NOT NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['row_id']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['row_id']['default'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['row_id']['extra'] = 'AUTO_INCREMENT';
// wtglog - outcome
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['outcome']['type'] = 'tinyint(1)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['outcome']['null'] = 'NOT NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['outcome']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['outcome']['default'] = '1';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['outcome']['extra'] = '';
// wtglog - timestamp
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['timestamp']['type'] = 'timestamp';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['timestamp']['null'] = 'NOT NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['timestamp']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['timestamp']['default'] = 'CURRENT_TIMESTAMP';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['timestamp']['extra'] = '';
// wtglog - line
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['line']['type'] = 'int(11)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['line']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['line']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['line']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['line']['extra'] = '';
// wtglog - file
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['file']['type'] = 'varchar(250)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['file']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['file']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['file']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['file']['extra'] = '';
// wtglog - function
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['function']['type'] = 'varchar(250)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['function']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['function']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['function']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['function']['extra'] = '';
// wtglog - sqlresult
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlresult']['type'] = 'blob';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlresult']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlresult']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlresult']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlresult']['extra'] = '';
// wtglog - sqlquery
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlquery']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlquery']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlquery']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlquery']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlquery']['extra'] = '';
// wtglog - sqlerror
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlerror']['type'] = 'mediumtext';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlerror']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlerror']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlerror']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['sqlerror']['extra'] = '';
// wtglog - wordpresserror
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['wordpresserror']['type'] = 'mediumtext';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['wordpresserror']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['wordpresserror']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['wordpresserror']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['wordpresserror']['extra'] = '';
// wtglog - screenshoturl
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['screenshoturl']['type'] = 'varchar(500)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['screenshoturl']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['screenshoturl']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['screenshoturl']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['screenshoturl']['extra'] = '';
// wtglog - userscomment
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userscomment']['type'] = 'mediumtext';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userscomment']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userscomment']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userscomment']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userscomment']['extra'] = '';
// wtglog - page
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['page']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['page']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['page']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['page']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['page']['extra'] = '';
// wtglog - version
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['version']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['version']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['version']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['version']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['version']['extra'] = '';
// wtglog - panelid
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelid']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelid']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelid']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelid']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelid']['extra'] = '';
// wtglog - panelname
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelname']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelname']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelname']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelname']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['panelname']['extra'] = '';
// wtglog - tabscreenid
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenid']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenid']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenid']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenid']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenid']['extra'] = '';
// wtglog - tabscreenname
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenname']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenname']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenname']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenname']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['tabscreenname']['extra'] = '';
// wtglog - dump
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['dump']['type'] = 'longblob';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['dump']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['dump']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['dump']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['dump']['extra'] = '';
// wtglog - ipaddress
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['ipaddress']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['ipaddress']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['ipaddress']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['ipaddress']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['ipaddress']['extra'] = '';
// wtglog - userid
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userid']['type'] = 'int(11)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userid']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userid']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userid']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['userid']['extra'] = '';
// wtglog - comment
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['comment']['type'] = 'mediumtext';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['comment']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['comment']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['comment']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['comment']['extra'] = '';
// wtglog - type
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['type']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['type']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['type']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['type']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['type']['extra'] = '';
// wtglog - category
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['category']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['category']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['category']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['category']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['category']['extra'] = '';
// wtglog - action
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['action']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['action']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['action']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['action']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['action']['extra'] = '';
// wtglog - priority
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['priority']['type'] = 'varchar(45)';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['priority']['null'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['priority']['key'] = '';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['priority']['default'] = 'NULL';
$wtgcsvexporter_tables_array['tables']['wtglog']['columns']['priority']['extra'] = '';              
?>