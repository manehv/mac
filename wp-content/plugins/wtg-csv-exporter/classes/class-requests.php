<?php
/** 
* Class for handling $_POST and $_GET requests
* 
* The class is called in the process_admin_POST_GET() method found in the WTGCSVEXPORTER class. 
* The process_admin_POST_GET() method is hooked at admin_init. It means requests are handled in the admin
* head, globals can be updated and pages will show the most recent data. Nonce security is performed
* within process_admin_POST_GET() then the require method for processing the request is used.
* 
* Methods in this class MUST be named within the form or link itself, basically a unique identifier for the form.
* i.e. the Section Switches settings have a form name of "sectionswitches" and so the method in this class used to
* save submission of the "sectionswitches" form is named "sectionswitches".
* 
* process_admin_POST_GET() uses eval() to call class + method 
* 
* @package WTG CSV Exporter
* @author Ryan Bayne   
* @since 0.0.1
*/

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
* Class processes form submissions, the class is only loaded once nonce and other security checked
* 
* @author Ryan R. Bayne
* @package WTG CSV Exporter
* @since 0.0.1
* @version 1.0.2
*/
class WTGCSVEXPORTER_Requests {  
    public function __construct() {
        global $wtgcsvexporter_settings;
    
        // create class objects
        $this->WTGCSVEXPORTER = WTGCSVEXPORTER::load_class( 'WTGCSVEXPORTER', 'class-wtgcsvexporter.php', 'classes' ); # plugin specific functions
        $this->UI = $this->WTGCSVEXPORTER->load_class( 'WTGCSVEXPORTER_UI', 'class-ui.php', 'classes' ); # interface, mainly notices
        $this->DB = $this->WTGCSVEXPORTER->load_class( 'WTGCSVEXPORTER_DB', 'class-wpdb.php', 'classes' ); # database interaction
        $this->PHP = $this->WTGCSVEXPORTER->load_class( 'WTGCSVEXPORTER_PHP', 'class-phplibrary.php', 'classes' ); # php library by Ryan R. Bayne
        $this->Files = $this->WTGCSVEXPORTER->load_class( 'WTGCSVEXPORTER_Files', 'class-files.php', 'classes' );
        $this->Forms = $this->WTGCSVEXPORTER->load_class( 'WTGCSVEXPORTER_Formbuilder', 'class-forms.php', 'classes' );
        $this->WPCore = $this->WTGCSVEXPORTER->load_class( 'WTGCSVEXPORTER_WPCore', 'class-wpcore.php', 'classes' );
    }
    
    /**
    * Processes security for $_POST and $_GET requests,
    * then calls another function to complete the specific request made.
    * 
    * This function is called by process_admin_POST_GET() which is hooked by admin_init.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function process_admin_request() { 
        $method = 'post';// post or get
         
        // ensure processing requested
        // if a hacker changes this, no processing happens so no validation required
        if(!isset( $_POST['wtgcsvexporter_admin_action'] ) && !isset( $_GET['wtgcsvexporteraction'] ) ) {
            return;
        }          
               
        // handle $_POST action - form names are validated
        if( isset( $_POST['wtgcsvexporter_admin_action'] ) && $_POST['wtgcsvexporter_admin_action'] == true){        
            if( isset( $_POST['wtgcsvexporter_admin_referer'] ) ){        
                
                // a few forms have the wtgcsvexporter_admin_referer where the default hidden values are not in use
                check_admin_referer( $_POST['wtgcsvexporter_admin_referer'] ); 
                $function_name = $_POST['wtgcsvexporteraction'];     
                   
            } else {                                       
                
                // 99% of forms will use this method
                check_admin_referer( $_POST['wtgcsvexporter_form_name'] );
                $function_name = $_POST['wtgcsvexporter_form_name'];
            
            }        
        }
                          
        // $_GET request
        if( isset( $_GET['wtgcsvexporteraction'] ) ){      
            check_admin_referer( $_GET['wtgcsvexporteraction'] );        
            $function_name = $_GET['wtgcsvexporteraction'];
            $method = 'get';
        }     
                   
        // arriving here means check_admin_referer() security is positive       
        global $wtgcsvexporter_debug_mode, $cont;

        $this->PHP->var_dump( $_POST, '<h1>$_POST</h1>' );           
        $this->PHP->var_dump( $_GET, '<h1>$_GET</h1>' );    
                              
        // $_POST security
        if( $method == 'post' ) {                      
            // check_admin_referer() wp_die()'s if security fails so if we arrive here WordPress security has been passed
            // now we validate individual values against their pre-registered validation method
            // some generic notices are displayed - this system makes development faster
            $post_result = true;
            $post_result = $this->Forms->apply_form_security();// ensures $_POST['wtgcsvexporter_form_formid'] is set, so we can use it after this line
            
            // apply my own level of security per individual input
            if( $post_result ){ $post_result = $this->Forms->apply_input_security(); }// detect hacking of individual inputs i.e. disabled inputs being enabled 
            
            // validate users values
            if( $post_result ){ $post_result = $this->Forms->apply_input_validation( $_POST['wtgcsvexporter_form_formid'] ); }// values (string,numeric,mixed) validation

            // cleanup to reduce registered data
            $this->Forms->deregister_form( $_POST['wtgcsvexporter_form_formid'] );
                    
            // if $overall_result includes a single failure then there is no need to call the final function
            if( $post_result === false ) {        
                return false;
            }
        }
        
        // handle a situation where the submitted form requests a function that does not exist
        if( !method_exists( $this, $function_name ) ){
            wp_die( sprintf( __( "The method for processing your request was not found. This can usually be resolved quickly. Please report method %s does not exist. <a href='https://www.youtube.com/watch?v=vAImGQJdO_k' target='_blank'>Watch a video</a> explaining this problem.", 'wtgcsvexporter' ), 
            $function_name) ); 
            return false;// should not be required with wp_die() but it helps to add clarity when browsing code and is a precaution.   
        }
        
        // all security passed - call the processing function
        if( isset( $function_name) && is_string( $function_name ) ) {
            eval( 'self::' . $function_name .'();' );
        }
    }  

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */    
    public function request_success( $form_title, $more_info = '' ){  
        $this->UI->create_notice( "Your submission for $form_title was successful. " . $more_info, 'success', 'Small', "$form_title Updated");          
    } 

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */    
    public function request_failed( $form_title, $reason = '' ){
        $this->UI->n_depreciated( $form_title . ' Unchanged', "Your settings for $form_title were not changed. " . $reason, 'error', 'Small' );    
    }

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */    
    public function logsettings() {
        global $wtgcsvexporter_settings;
        $wtgcsvexporter_settings['globalsettings']['uselog'] = $_POST['wtgcsvexporter_radiogroup_logstatus'];
        $wtgcsvexporter_settings['globalsettings']['loglimit'] = $_POST['wtgcsvexporter_loglimit'];
                                                   
        ##################################################
        #           LOG SEARCH CRITERIA                  #
        ##################################################
        
        // first unset all criteria
        if( isset( $wtgcsvexporter_settings['logsettings']['logscreen'] ) ){
            unset( $wtgcsvexporter_settings['logsettings']['logscreen'] );
        }
                                                           
        // if a column is set in the array, it indicates that it is to be displayed, we unset those not to be set, we dont set them to false
        if( isset( $_POST['wtgcsvexporter_logfields'] ) ){
            foreach( $_POST['wtgcsvexporter_logfields'] as $column){
                $wtgcsvexporter_settings['logsettings']['logscreen']['displayedcolumns'][$column] = true;                   
            }
        }
                                                                                 
        // outcome criteria
        if( isset( $_POST['wtgcsvexporter_log_outcome'] ) ){    
            foreach( $_POST['wtgcsvexporter_log_outcome'] as $outcomecriteria){
                $wtgcsvexporter_settings['logsettings']['logscreen']['outcomecriteria'][$outcomecriteria] = true;                   
            }            
        } 
        
        // type criteria
        if( isset( $_POST['wtgcsvexporter_log_type'] ) ){
            foreach( $_POST['wtgcsvexporter_log_type'] as $typecriteria){
                $wtgcsvexporter_settings['logsettings']['logscreen']['typecriteria'][$typecriteria] = true;                   
            }            
        }         

        // category criteria
        if( isset( $_POST['wtgcsvexporter_log_category'] ) ){
            foreach( $_POST['wtgcsvexporter_log_category'] as $categorycriteria){
                $wtgcsvexporter_settings['logsettings']['logscreen']['categorycriteria'][$categorycriteria] = true;                   
            }            
        }         

        // priority criteria
        if( isset( $_POST['wtgcsvexporter_log_priority'] ) ){
            foreach( $_POST['wtgcsvexporter_log_priority'] as $prioritycriteria){
                $wtgcsvexporter_settings['logsettings']['logscreen']['prioritycriteria'][$prioritycriteria] = true;                   
            }            
        }         

        ############################################################
        #         SAVE CUSTOM SEARCH CRITERIA SINGLE VALUES        #
        ############################################################
        // page
        if( isset( $_POST['wtgcsvexporter_pluginpages_logsearch'] ) && $_POST['wtgcsvexporter_pluginpages_logsearch'] != 'notselected' ){
            $wtgcsvexporter_settings['logsettings']['logscreen']['page'] = $_POST['wtgcsvexporter_pluginpages_logsearch'];
        }   
        // action
        if( isset( $_POST['csv2pos_logactions_logsearch'] ) && $_POST['csv2pos_logactions_logsearch'] != 'notselected' ){
            $wtgcsvexporter_settings['logsettings']['logscreen']['action'] = $_POST['csv2pos_logactions_logsearch'];
        }   
        // screen
        if( isset( $_POST['wtgcsvexporter_pluginscreens_logsearch'] ) && $_POST['wtgcsvexporter_pluginscreens_logsearch'] != 'notselected' ){
            $wtgcsvexporter_settings['logsettings']['logscreen']['screen'] = $_POST['wtgcsvexporter_pluginscreens_logsearch'];
        }  
        // line
        if( isset( $_POST['wtgcsvexporter_logcriteria_phpline'] ) ){
            $wtgcsvexporter_settings['logsettings']['logscreen']['line'] = $_POST['wtgcsvexporter_logcriteria_phpline'];
        }  
        // file
        if( isset( $_POST['wtgcsvexporter_logcriteria_phpfile'] ) ){
            $wtgcsvexporter_settings['logsettings']['logscreen']['file'] = $_POST['wtgcsvexporter_logcriteria_phpfile'];
        }          
        // function
        if( isset( $_POST['wtgcsvexporter_logcriteria_phpfunction'] ) ){
            $wtgcsvexporter_settings['logsettings']['logscreen']['function'] = $_POST['wtgcsvexporter_logcriteria_phpfunction'];
        }
        // panel name
        if( isset( $_POST['wtgcsvexporter_logcriteria_panelname'] ) ){
            $wtgcsvexporter_settings['logsettings']['logscreen']['panelname'] = $_POST['wtgcsvexporter_logcriteria_panelname'];
        }
        // IP address
        if( isset( $_POST['wtgcsvexporter_logcriteria_ipaddress'] ) ){
            $wtgcsvexporter_settings['logsettings']['logscreen']['ipaddress'] = $_POST['wtgcsvexporter_logcriteria_ipaddress'];
        }
        // user id
        if( isset( $_POST['wtgcsvexporter_logcriteria_userid'] ) ){
            $wtgcsvexporter_settings['logsettings']['logscreen']['userid'] = $_POST['wtgcsvexporter_logcriteria_userid'];
        }
        
        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        $this->UI->n_postresult_depreciated( 'success', __( 'Log Settings Saved', 'wtgcsvexporter' ), __( 'It may take sometime for new log entries to be created depending on your websites activity.', 'wtgcsvexporter' ) );  
    }  
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */       
    public function beginpluginupdate() {
        $this->Updates = $this->WTGCSVEXPORTER->load_class( 'WTGCSVEXPORTER_Formbuilder', 'class-forms.php', 'classes' );
        
        // check if an update method exists, else the plugin needs to do very little
        eval( '$method_exists = method_exists ( $this->Updates , "patch_' . $_POST['wtgcsvexporter_plugin_update_now'] .'" );' );

        if( $method_exists){
            // perform update by calling the request version update procedure
            eval( '$update_result_array = $this->Updates->patch_' . $_POST['wtgcsvexporter_plugin_update_now'] .'( "update");' );       
        }else{
            // default result to true
            $update_result_array['failed'] = false;
        } 
      
        if( $update_result_array['failed'] == true){           
            $this->UI->create_notice( __( 'The update procedure failed, the reason should be displayed below. Please try again unless the notice below indicates not to. If a second attempt fails, please seek support.', 'wtgcsvexporter' ), 'error', 'Small', __( 'Update Failed', 'wtgcsvexporter' ) );    
            $this->UI->create_notice( $update_result_array['failedreason'], 'info', 'Small', 'Update Failed Reason' );
        }else{  
            // storing the current file version will prevent user coming back to the update screen
            global $wtgcsvexporter_currentversion;        
            update_option( 'wtgcsvexporter_installedversion', $wtgcsvexporter_currentversion);

            $this->UI->create_notice( __( 'Good news, the update procedure was complete. If you do not see any errors or any notices indicating a problem was detected it means the procedure worked. Please ensure any new changes suit your needs.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Update Complete', 'wtgcsvexporter' ) );
            
            // do a redirect so that the plugins menu is reloaded
            wp_redirect( get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=wtgcsvexporter' );
            exit;                
        }
    }
    
    /**
    * Save drip feed limits  
    */
    public function schedulerestrictions() {
        $wtgcsvexporter_schedule_array = $this->WTGCSVEXPORTER->get_option_schedule_array();
        
        // if any required values are not in $_POST set them to zero
        if(!isset( $_POST['day'] ) ){
            $wtgcsvexporter_schedule_array['limits']['day'] = 0;        
        }else{
            $wtgcsvexporter_schedule_array['limits']['day'] = $_POST['day'];            
        }
        
        if(!isset( $_POST['hour'] ) ){
            $wtgcsvexporter_schedule_array['limits']['hour'] = 0;
        }else{
            $wtgcsvexporter_schedule_array['limits']['hour'] = $_POST['hour'];            
        }
        
        if(!isset( $_POST['session'] ) ){
            $wtgcsvexporter_schedule_array['limits']['session'] = 0;
        }else{
            $wtgcsvexporter_schedule_array['limits']['session'] = $_POST['session'];            
        }
                                 
        // ensure $wtgcsvexporter_schedule_array is an array, it may be boolean false if schedule has never been set
        if( isset( $wtgcsvexporter_schedule_array ) && is_array( $wtgcsvexporter_schedule_array ) ){
            
            // if times array exists, unset the [times] array
            if( isset( $wtgcsvexporter_schedule_array['days'] ) ){
                unset( $wtgcsvexporter_schedule_array['days'] );    
            }
            
            // if hours array exists, unset the [hours] array
            if( isset( $wtgcsvexporter_schedule_array['hours'] ) ){
                unset( $wtgcsvexporter_schedule_array['hours'] );    
            }
            
        }else{
            // $schedule_array value is not array, this is first time it is being set
            $wtgcsvexporter_schedule_array = array();
        }
        
        // loop through all days and set each one to true or false
        if( isset( $_POST['wtgcsvexporter_scheduleday_list'] ) ){
            foreach( $_POST['wtgcsvexporter_scheduleday_list'] as $key => $submitted_day ){
                $wtgcsvexporter_schedule_array['days'][$submitted_day] = true;        
            }  
        } 
        
        // loop through all hours and add each one to the array, any not in array will not be permitted                              
        if( isset( $_POST['wtgcsvexporter_schedulehour_list'] ) ){
            foreach( $_POST['wtgcsvexporter_schedulehour_list'] as $key => $submitted_hour){
                $wtgcsvexporter_schedule_array['hours'][$submitted_hour] = true;        
            }           
        }    

        if( isset( $_POST['deleteuserswaiting'] ) )
        {
            $wtgcsvexporter_schedule_array['eventtypes']['deleteuserswaiting']['switch'] = 'enabled';                
        }
        
        if( isset( $_POST['eventsendemails'] ) )
        {
            $wtgcsvexporter_schedule_array['eventtypes']['sendemails']['switch'] = 'enabled';    
        }        
  
        $this->WTGCSVEXPORTER->update_option_schedule_array( $wtgcsvexporter_schedule_array );
        $this->UI->notice_depreciated( __( 'Schedule settings have been saved.', 'wtgcsvexporter' ), 'success', 'Large', __( 'Schedule Times Saved', 'wtgcsvexporter' ) );   
    } 
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */       
    public function logsearchoptions() {
        $this->UI->n_postresult_depreciated( 'success', __( 'Log Search Settings Saved', 'wtgcsvexporter' ), __( 'Your selections have an instant effect. Please browse the Log screen for the results of your new search.', 'wtgcsvexporter' ) );                   
    }
 
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */        
    public function defaultcontenttemplate () {        
        $this->UI->create_notice( __( 'Your default content template has been saved. This is a basic template, other advanced options may be available by activating the WTG CSV Exporter Templates custom post type (pro edition only) for managing multiple template designs.' ), 'success', 'Small', __( 'Default Content Template Updated' ) );         
    }
        
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */       
    public function reinstalldatabasetables() {
        $installation = new WTGCSVEXPORTER_Install();
        $installation->reinstalldatabasetables();
        $this->UI->create_notice( 'All tables were re-installed. Please double check the database status list to
        ensure this is correct before using the plugin.', 'success', 'Small', 'Tables Re-Installed' );
    }
     
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */          
    public function globalswitches() {
        global $wtgcsvexporter_settings;
        $wtgcsvexporter_settings['noticesettings']['wpcorestyle'] = $_POST['uinoticestyle'];        
        $wtgcsvexporter_settings['posttypes']['wtgflags']['status'] = $_POST['flagsystemstatus'];
        $wtgcsvexporter_settings['widgetsettings']['dashboardwidgetsswitch'] = $_POST['dashboardwidgetsswitch'];
        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings ); 
        $this->UI->create_notice( __( 'Global switches have been updated. These switches can initiate the use of 
        advanced systems. Please monitor your blog and ensure the plugin operates as you expected it to. If
        anything does not appear to work in the way you require please let WebTechGlobal know.' ),
        'success', 'Small', __( 'Global Switches Updated' ) );       
    } 
       
    /**
    * save capability settings for plugins pages
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function pagecapabilitysettings() {
        global $wtgcsvexporter_menu_array;
        
        // get the capabilities array from WP core
        $capabilities_array = $this->WPCore->capabilities();

        // get stored capability settings 
        $saved_capability_array = get_option( 'wtgcsvexporter_capabilities' );

        // to ensure no extra values are stored (more menus added to source) loop through page array
        foreach( $wtgcsvexporter_menu_array as $key => $page_array ) {
            
            // ensure $_POST value is also in the capabilities array to ensure user has not hacked form, adding their own capabilities
            if( isset( $_POST['pagecap' . $page_array['name'] ] ) && in_array( $_POST['pagecap' . $page_array['name'] ], $capabilities_array ) ) {
                $saved_capability_array['pagecaps'][ $page_array['name'] ] = $_POST['pagecap' . $page_array['name'] ];
            }
                
        }
          
        update_option( 'wtgcsvexporter_capabilities', $saved_capability_array );
         
        $this->UI->create_notice( __( 'Capabilities for this plugins pages have been stored. Due to this being security related I recommend testing before you logout. Ensure that each role only has access to the plugin pages you intend.' ), 'success', 'Small', __( 'Page Capabilities Updated' ) );        
    }
    
    /**
    * Saves the plugins global dashboard widget settings i.e. which to display, what to display, which roles to allow access
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function dashboardwidgetsettings() {
        global $wtgcsvexporter_settings, $wtgcsvexporter_menu_array;
            
        foreach( $wtgcsvexporter_menu_array as $key => $section_array ) {

            if( isset( $_POST[ $section_array['name'] . 'dashboardwidgetsswitch' ] ) ) {
                $wtgcsvexporter_settings['widgetsettings'][ $section_array['name'] . 'dashboardwidgetsswitch'] = $_POST[ $section_array['name'] . 'dashboardwidgetsswitch' ];    
            }
            
            if( isset( $_POST[ $section_array['name'] . 'widgetscapability' ] ) ) {
                $wtgcsvexporter_settings['widgetsettings'][ $section_array['name'] . 'widgetscapability'] = $_POST[ $section_array['name'] . 'widgetscapability' ];    
            }

        }

        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );    
        $this->UI->create_notice( __( 'Your dashboard widget settings have been saved. Please check your dashboard to ensure it is configured as required per role.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Settings Saved', 'wtgcsvexporter' ) );         
    }
     
    /**
    * Updates exportable columns (from wp_posts) in the "allposts" profile.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function exportallpostscolumns() {
        global $wtgcsvexporter_settings, $wpdb;
        if( !isset( $_POST['exportablecolumns'] ) ) {
            $this->UI->create_notice( __( 'No columns were selected for the posts table. Please select one or more columns to be exported into your .csv file.', 'wtgcsvexporter' ), 'error', 'Small', __( 'Columns Required', 'wtgcsvexporter' ) );         
            return false;        
        }
        
        if( !is_array( $_POST['exportablecolumns'] ) ) {
            $this->UI->create_notice( __( 'WebTechGlobals own plugin framework security has discontinued your request as a problem was detected.', 'wtgcsvexporter' ), 'error', 'Small', __( 'Request Cancelled', 'wtgcsvexporter' ) );         
            return false;            
        }
        
        // when storing we must considering multiple table profiles
        // ['tables'] stores array of db table names without prefix
        $wtgcsvexporter_settings['csvexportprofiles']['allposts']['tables']['posts'] = $_POST['exportablecolumns']; 
        $columns_string = implode( ',', $wtgcsvexporter_settings['csvexportprofiles']['allposts']['tables']['posts'] );
        
        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        //$this->UI->create_notice( __( 'You have updated the export profile and your .csv file should be downloading now.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Export Success', 'wtgcsvexporter' ) );                 
        
        $select_posts_result = $this->DB->selectwherearray( $wpdb->prefix . 'posts', 
        null /*condition*/, 
        null /*orderby*/, 
        $columns_string, 
        'ARRAY_A',
        null /*sort*/ );
         
        // create .csv file (locally) then download it and then delete it unless user settings allows it to be kept on the server
        $export_result = $this->Files->export_csv_download( $select_posts_result, $wtgcsvexporter_settings['csvexportprofiles']['allposts']['tables']['posts'] );
    }   
    
    /**
    * Updates exportable columns (from wp_posts) in the "posts" profile.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function exportpostscolumns() {
        global $wtgcsvexporter_settings, $wpdb;
        if( !isset( $_POST['exportablecolumns'] ) ) {
            $this->UI->create_notice( __( 'No columns were selected for the posts table. Please select one or more columns as part of your "Posts" profile to be exported into your .csv file.', 'wtgcsvexporter' ), 'error', 'Small', __( 'Columns Required', 'wtgcsvexporter' ) );         
            return false;        
        }
        
        if( !is_array( $_POST['exportablecolumns'] ) ) {
            $this->UI->create_notice( __( 'WebTechGlobals own plugin framework security has detected a problem with your request.', 'wtgcsvexporter' ), 'error', 'Small', __( 'Request Cancelled', 'wtgcsvexporter' ) );         
            return false;            
        }
        
        // when storing we must considering multiple table profiles
        // ['tables'] stores array of db table names without prefix
        $wtgcsvexporter_settings['csvexportprofiles']['posts']['tables']['posts'] = $_POST['exportablecolumns']; 
        $columns_string = implode( ',', $wtgcsvexporter_settings['csvexportprofiles']['posts']['tables']['posts'] );
        
        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        //$this->UI->create_notice( __( 'You have updated the export profile and your .csv file should be downloading now.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Export Success', 'wtgcsvexporter' ) );                 
        
        $select_posts_result = $this->DB->selectwherearray( $wpdb->prefix . 'posts', 
        null /*condition*/, 
        null /*orderby*/, 
        $columns_string, 
        'ARRAY_A',
        null /*sort*/ );
         
        // create .csv file (locally) then download it and then delete it unless user settings allows it to be kept on the server
        $export_result = $this->Files->export_csv_download( $select_posts_result, $wtgcsvexporter_settings['csvexportprofiles']['posts']['tables']['posts'] );
    }  
      
    /**
    * Updates exportable columns (from wp_posts) in the "pages" profile.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function exportpagescolumns() {
        global $wtgcsvexporter_settings, $wpdb;
        if( !isset( $_POST['exportablecolumns'] ) ) {
            $this->UI->create_notice( __( 'No columns were selected for the posts table. Please select one or more columns as part of your "Pages" profile to be exported into your .csv file.', 'wtgcsvexporter' ), 'error', 'Small', __( 'Columns Required', 'wtgcsvexporter' ) );         
            return false;        
        }
        
        if( !is_array( $_POST['exportablecolumns'] ) ) {
            $this->UI->create_notice( __( 'WTG security has discontinued your request because problem was detected.', 'wtgcsvexporter' ), 'error', 'Small', __( 'Request Cancelled', 'wtgcsvexporter' ) );         
            return false;            
        }
        
        // when storing we must considering multiple table profiles
        // ['tables'] stores array of db table names without prefix
        $wtgcsvexporter_settings['csvexportprofiles']['pages']['tables']['posts'] = $_POST['exportablecolumns']; 
        $columns_string = implode( ',', $wtgcsvexporter_settings['csvexportprofiles']['pages']['tables']['posts'] );
        
        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );

        $select_posts_result = $this->DB->selectwherearray( $wpdb->prefix . 'posts', 
        null /*condition*/, 
        null /*orderby*/, 
        $columns_string, 
        'ARRAY_A',
        null /*sort*/ );
         
        // create .csv file (locally) then download it and then delete it unless user settings allows it to be kept on the server
        $export_result = $this->Files->export_csv_download( $select_posts_result, $wtgcsvexporter_settings['csvexportprofiles']['pages']['tables']['posts'] );
   }    
   
   /**
   * Saves primary data selection on single file export view.
   * 
   * @author Ryan R. Bayne
   * @package WTG CSV Exporter
   * @since 0.0.1
   * @version 1.0
   */
    public function primarybasic() {
        global $wtgcsvexporter_settings;

        if( isset( $_POST['primarysources'] ) ) {
            $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'] = $_POST['primarysources'];    
        }

        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        $this->UI->create_notice( __( 'Your primary data selection has been saved.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Primary Selection Saved', 'wtgcsvexporter' ) );               
    }
   
    /**
    * Saves selection of meta keys for exporting to .csv file.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function metabasic() {
        global $wtgcsvexporter_settings;

        $meta_keys = $this->DB->metakeys_distinct();
        
        $total_keys = count( $meta_keys );
        
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['metakeys'] ) )
        {
            unset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['metakeys'] );
        }
        
        for($i=0;$i<=$total_keys;$i++){
            if( isset( $_POST['distinctmetakeys' . $i] ) )
            {
               $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['metakeys'][] = $_POST['distinctmetakeys' . $i]; 
            }
        }

        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        $this->UI->create_notice( __( 'Your meta-key selections have been saved.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Meta Keys Saved', 'wtgcsvexporter' ) );               
    }
    
    /**
    * Selectedd taxonomies for export to .csv file.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function taxonomiesbasic() {
        global $wtgcsvexporter_settings;

        $meta_keys = $this->DB->metakeys_distinct();
        
        $totaltaxonomies = count( get_taxonomies() );
        
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['taxonomies'] ) )
        {
            unset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['taxonomies'] );
        }
                
        for($i=0;$i<=$totaltaxonomies;$i++){
            if( isset( $_POST['exporttaxonomies' . $i] ) )
            {
               $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['taxonomies'][] = $_POST['exporttaxonomies' . $i]; 
            }
        }

        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        $this->UI->create_notice( __( 'Your taxonomy selections have been saved.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Taxonomy Selections Saved', 'wtgcsvexporter' ) );               
    }
    
    /**
    * Final Actions: create profile by moving singlefileprofile profile
    * to it's own array in $wtgcsvexporter_settings.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function finishbasic() {
        global $wtgcsvexporter_settings;
        
        
        
        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        $this->UI->create_notice( __( 'Your new CSV export profile has been created permanently. You can now use this view to create another new profile.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Created Permanent Profile', 'wtgcsvexporter' ) );                   
    }
        
    /**
    * Saves selected database tables user wants to be included in
    * csv export. User will need to select specific columns on an additional
    * form after this save.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function tablesdetailed() {
        global $wtgcsvexporter_settings;

        $meta_keys = $this->DB->metakeys_distinct();
        
        $totaltables = count( $this->DB->get_tables() );
        
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['tables'] ) )
        {
            unset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['tables'] );
        }
                
        for($i=0;$i<=$totaltables;$i++){
            if( isset( $_POST['includedtables' . $i] ) )
            {
               $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['tables'][] = $_POST['includedtables' . $i]; 
            }
        }

        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        $this->UI->create_notice( __( 'Your table selections have been saved.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Table Selections Saved', 'wtgcsvexporter' ) );
    }
     
    /**
    * Saves selected columns to be exported. Each column is added to
    * .csv file ass a new header.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function columnsdetailed() {
        global $wtgcsvexporter_settings;
        
        $totaltables = $this->DB->get_tables();
        
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['columns'] ) )
        {
            unset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['columns'] );
        }
                
        foreach( $totaltables as $table ){
            if( isset( $_POST['expcols' . $table] ) )
            {
               $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['columns'][$table] = $_POST['expcols' . $table]; 
            }
        } 
        
        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        $this->UI->create_notice( __( 'Your settings for adding individual columns to your .csv file have been saved. If you experience any troubles please consider the size of data in each column, troubleshoot by reducing the number of columns.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Column Selections Saved', 'wtgcsvexporter' ) );           
    }
            
    /**
    * Final action for creating detailed profile.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function finishdetailed() {
        global $wtgcsvexporter_settings;
         
        $this->WTGCSVEXPORTER->update_settings( $wtgcsvexporter_settings );
        $this->UI->create_notice( __( 'Your new CSV export profile has been created permanently. You can now use this view to create another new profile.', 'wtgcsvexporter' ), 'success', 'Small', __( 'Created Permanent Profile', 'wtgcsvexporter' ) );                   
    }
    
    /**
    * Exports .csv file based on single file profile which is a temporary
    * profile. 
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0                          
    */
    public function testbasicprofile() {
        global $wtgcsvexporter_settings;
        $primary = false;
        $headers_array = array();
        $headers_row = '';

        // establish primary data sources
        $initial_primary_sources = array( 'comments' => __( 'Comments', 'wtgcsvexporter' ), 'allposttypes' => __( 'All Post Types', 'wtgcsvexporter' ) );
        // get post types for adding to primary data sources
        $this->post_types_objects = get_post_types( '', 'objects' ); 
        // rebuild the post types array
        $this->post_types_array = array();
        if( $this->post_types_objects )
        {
            foreach( $this->post_types_objects as $pt )
            {
                $this->post_types_array = array_merge( $this->post_types_array, array( $pt->name => $pt->labels->name ) );
            }
        }      
        // merge the initial sources with the post types  
        $primary_sources_array = array_merge( $initial_primary_sources, $this->post_types_array );        
        
        // primary source is always required in this procedure
        if( !isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'] ) ){
            $this->UI->create_notice( __( 'Your profile.', 'wtgcsvexporter' ), 'error', 'Small', __( 'You do not have a primary data source saved for your single file profile. This must be corrected before you can run an export.', 'wtgcsvexporter' ) );                           
            return false;
        } else {
            $primary = $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'];
        }
        
        // query the primary data
        switch ( $primary ) {
           case 'comments':
         
                    $defaults = array(
                        'author_email' => '',
                        'author__in' => '',
                        'author__not_in' => '',
                        'include_unapproved' => '',
                        'fields' => '',
                        'ID' => '',
                        'comment__in' => '',
                        'comment__not_in' => '',
                        'karma' => '',
                        'number' => '',
                        'offset' => '',
                        'orderby' => '',
                        'order' => 'DESC',
                        'parent' => '',
                        'post_author__in' => '',
                        'post_author__not_in' => '',
                        'post_ID' => '',
                        'post_id' => 0,
                        'post__in' => '',
                        'post__not_in' => '',
                        'post_author' => '',
                        'post_name' => '',
                        'post_parent' => '',
                        'post_status' => '',
                        'post_type' => '',
                        'status' => 'all',
                        'type' => '',
                        'user_id' => '',
                        'search' => '',
                        'count' => false,
                        'meta_key' => '',
                        'meta_value' => '',
                        'meta_query' => '',
                        'date_query' => null, // See WP_Date_Query
                    );        
                    
                    $primary_object = get_comments( $defaults );

                    // add new headers based on the query performed
                    
             break;
           case 'allposttypes':
           
                    // this should be post, page or a custom post type
                    $args = array(
                        'posts_per_page'   => 5,
                        'offset'           => 0,
                        'category'         => '',
                        'category_name'    => '',
                        'orderby'          => 'post_date',
                        'order'            => 'DESC',
                        'include'          => '',
                        'exclude'          => '',
                        'meta_key'         => '',
                        'meta_value'       => '',
                        'post_type'        => 'all',
                        'post_mime_type'   => '',
                        'post_parent'      => '',
                        //'post_status'      => 'publish',
                        'suppress_filters' => true 
                    );
                    $primary_object = get_posts( $args );
                                    
             break;
           default:
           
                    // this should be post, page or a custom post type
                    $args = array(
                        'posts_per_page'   => 5,
                        'offset'           => 0,
                        'category'         => '',
                        'category_name'    => '',
                        'orderby'          => 'post_date',
                        'order'            => 'DESC',
                        'include'          => '',
                        'exclude'          => '',
                        'meta_key'         => '',
                        'meta_value'       => '',
                        'post_type'        => $primary,
                        'post_mime_type'   => '',
                        'post_parent'      => '',
                        //'post_status'      => 'publish',
                        'suppress_filters' => true 
                    );
                    $primary_object = get_posts( $args ); 
                           
             break;
        }

        // change primary data objects to array
        $first = true;
        foreach( $primary_object as $key => $post_object )
        {
            $records_array[] = (array) $post_object;    
            
            // add new primary headers
            if( $first )
            {
                $headers_array = array_keys( $records_array[0] );
                $first = false;// prevents headers being set again    
            } 
        }
        
        // add meta data and columns
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['metakeys'] ) ) 
        {
            // loop through each post or comment, query it's meta and add the meta to the array
            foreach( $records_array as $primarykey => $anitem )
            {                                  
                foreach( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['metakeys'] as $metakey )
                {                                          
                    $anitems_meta_value = get_post_meta( $anitem['ID'], $metakey, false );  
                    
                    // if value returned add it to records
                    if( $anitems_meta_value )
                    {
                        $records_array[ $primarykey ][ $metakey ] = serialize( $anitems_meta_value );
                    }  
                    
                    // add meta key as new column
                    if( !in_array( $metakey, $headers_array ) )
                    {
                        $headers_array[] = $metakey;    
                    }                               
                }
            }
        }

        // add taxonomies and columns
        // avoid doing this if primary does not have taxonomies
        if( $primary !== 'comments' && isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['taxonomies'] ) ) 
        {
            foreach( $records_array as $primarykey => $anitem )
            {
                foreach( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['taxonomies'] as $secondarykey => $tax )
                {
                    if( $tax == 'post_tag' )
                    {
                        $anitems_taxonomy_result = wp_get_post_tags( $anitem['ID'] );
                        if( $anitems_taxonomy_result )
                        {
                            $records_array[ $primarykey ][ $tax ] = serialize( (array) $anitems_taxonomy_result );  
                        }
                    }
         
                    // add tax name as new column header
                    if( !in_array( $tax, $headers_array ) )
                    {
                        $headers_array[] = $tax;    
                    }                    
                }
            }    
        }        

        $this->Files->export_csv_download( $records_array, $headers_array, time() . '.csv', true );
    }
    
    /**
    * Exports .csv file based on single file profile which is a temporary
    * profile. 
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0                          
    */
    public function testdetailedprofile() {
        global $wtgcsvexporter_settings;
        $primary = false;
        $headers_array = array();
        $headers_row = '';

        
        
        // establish primary data sources
        $initial_primary_sources = array( 'comments' => __( 'Comments', 'wtgcsvexporter' ), 'allposttypes' => __( 'All Post Types', 'wtgcsvexporter' ) );
        
        
        
        // get post types for adding to primary data sources
        $this->post_types_objects = get_post_types( '', 'objects' ); 
        
        
        // rebuild the post types array
        $this->post_types_array = array();
        if( $this->post_types_objects )
        {
            foreach( $this->post_types_objects as $pt )
            {
                $this->post_types_array = array_merge( $this->post_types_array, array( $pt->name => $pt->labels->name ) );
            }
        }      
        // merge the initial sources with the post types  
        $primary_sources_array = array_merge( $initial_primary_sources, $this->post_types_array );        
        
        
        
        
        // primary source is always required in this procedure
        if( !isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'] ) ){
            $this->UI->create_notice( __( 'Your profile.', 'wtgcsvexporter' ), 'error', 'Small', __( 'You do not have a primary data source saved for your single file profile. This must be corrected before you can run an export.', 'wtgcsvexporter' ) );                           
            return false;
        } else {
            $primary = $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'];
        }
        
        // add individually selected tables
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['tables'] ) ) 
        {
            // if individually selected columns exist in this table then do not add the entire table
            //$wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['columns'];    
        }        
        
        // add individually selected columns
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['columns'] ) ) 
        {

   
        }      
   
   
        var_dump( $headers_array );
        //$this->Files->export_csv_download( $records_array, $headers_array, time() . '.csv', true );
        
        
    }
        
}// WTGCSVEXPORTER_Requests       
?>
