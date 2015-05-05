<?php
/** 
* Plugin update procedure.
* 
* If an update needs to modify the existing installation we use this. Create a new function as shown in example
* and increment the digit on the end i.e. the version is added to the end without zeros or periods.
* 
* @package WTG CSV Exporter
* @author Ryan Bayne   
* @since 0.0.1
*/

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

//class WTGCSVEXPORTER_UpdatePlugin extends WTGCSVEXPORTER_PHP{ commented 14092014
class WTGCSVEXPORTER_UpdatePlugin {  
    public function patch_800( $action_requested = 'update' ){
        $update_array = array();
        $update_array['info']['modificationrequired'] = true;
        $update_array['info']['intro'] = __( 'Version 0.0.1 is not compatible with older installations of WTG CSV Exporter.', 'wtgcsvexporter' );
        $update_array['info']['urgency'] = 'high';
        #############################
        #          CHANGES          #
        #############################
        // new features
        //$update_array['changes']['added'][0] = array( 'title' => __( 'Mailing System', 'wtgcsvexporter' ), 'about' => __( 'Can now perform mass email campaigns with emails being sent in small batches using the plugins schedule procedures.', 'wtgcsvexporter' ) );
        //$update_array['changes']['added'][1] = array( 'title' => __( 'Dashboard Widgets', 'wtgcsvexporter' ), 'about' => __( 'Section dashboard widgets added with switches to activate. Most are empty but ready to be customized.', 'wtgcsvexporter' ) );
        // bug fixes        
        //$update_array['changes']['fixes'][0] = array( 'title' => __( 'Download Failure', 'wtgcsvexporter' ), 'about' => __( 'Premium download package download attempts should no longer fail and display a notice regarding a user ID being required.', 'wtgcsvexporter' ) );
        // general changes        
        //$update_array['changes']['general'][0] = array( 'title' => __( 'Files', 'wtgcsvexporter' ), 'about' => __( 'The plugin package has totally changed, please delete the existing plugin folder.', 'wtgcsvexporter' ) );
        //$update_array['changes']['general'][1] = array( 'title' => __( 'Download Statistics', 'wtgcsvexporter' ), 'about' => __( 'Download statistics are being collected and used in graphs.', 'wtgcsvexporter' ) );
        //$update_array['changes']['general'][2] = array( 'title' => __( 'Updating', 'wtgcsvexporter' ), 'about' => __( 'The functions and approach to updating the plugin has been improved.', 'wtgcsvexporter' ) );
        // configuration changes        
        //$update_array['changes']['config'][0] = array( 'title' => __( 'Various Options', 'wtgcsvexporter' ), 'about' => __( 'At this stage development is rapid, with many new settings being added. Please always check all options screens and ensure nothing is configured incorrectly for your needs.', 'wtgcsvexporter' ) );
        ############################
        #     REQUEST RESULT       #
        ############################   
        $update_array['failed'] = false;
        $update_array['failedreason'] = __( 'No Applicable', 'wtgcsvexporter' );
                    
        if( $action_requested == 'update' )
        {             
            /* no installation changes required for version 0.0.3 */
            return $update_array;
        }   
        elseif( $action_requested == 'info' )
        {
            return $update_array['info'];    
        }
        elseif( $action_requested == 'changes' )
        {
            return $update_array['changes'];    
        }
    }    
    public function nextversion_clean() {
        global $wtgcsvexporter_currentversion;
        
        $installed_version = WTGCSVEXPORTER::get_installed_version();
        
        $installed_version_cleaned = str_replace( '.', '', $installed_version);
        
        return $installed_version_cleaned + 1;       
    }
    public function changelist( $scope = 'next' ){
        global $wtgcsvexporter_currentversion;
        
        // standard messages for change types
        $added = __( 'New features added to the plugin, be sure to configure them to suit your needs.', 'wtgcsvexporter' );
        $fixes = __( 'Bug fixes, remember a fix may instantly change how the plugin operates on your site.', 'wtgcsvexporter' );
        $general = __( 'General improvements to how features operate, the interface and procedures.', 'wtgcsvexporter' );
        $config = __( 'These changes involve changing the plugins installation, database updates involved.', 'wtgcsvexporter' );
                                                                            
        $next_version = $this->nextversion_clean();

        eval( '$method_exists = method_exists ( $this , "patch_' . $next_version .'" );' );

        if(!$method_exists){
            return false;
        }
        
        eval( '$changes_array = $this->patch_' . $next_version .'( "changes");' );
        
        foreach( $changes_array as $key => $group){
            $test = '<ol>';
            foreach( $group as $item){
                $test .= '<li><strong>'.$item['title'].': </strong>'.$item['about'].'</li>';
            }
            $test .= '</ol>';
            
            echo $this->UI->notice_return( 'info', 'Small',ucfirst( $key ), $$key . $test);                
        }    
    } 
}
?>
