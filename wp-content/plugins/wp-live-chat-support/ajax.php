<?php
@session_start();
@ob_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 604800');
header('Access-Control-Allow-Headers: x-requested-with');

ini_set('html_errors', 0);
define('SHORTINIT', true);


require_once( '../../../wp-load.php' );


define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' ); // full path, no trailing slash

$iterations = 55; 
/* time in microseconds between updating the user on the page within the DB  (lower number = higher resource usage) */
define('WPLC_DELAY_BETWEEN_UPDATES',500000);
/* time in microseconds between long poll loop (lower number = higher resource usage) */
define('WPLC_DELAY_BETWEEN_LOOPS',500000);
/* this needs to take into account the previous constants so that we dont run out of time, which in turn returns a 503 error */
define('WPLC_TIMEOUT',(((WPLC_DELAY_BETWEEN_UPDATES + WPLC_DELAY_BETWEEN_LOOPS))*$iterations)/1000000);



require_once( ABSPATH . WPINC . '/l10n.php' );

require_once( ABSPATH . WPINC . '/link-template.php' );

require_once( ABSPATH . WPINC . '/formatting.php' );

require_once( ABSPATH . WPINC . '/kses.php' );



$plugin_dir = basename(dirname(__FILE__))."/languages/";
load_plugin_textdomain( 'wplivechat', false, $plugin_dir );




global $wpdb;

global $wplc_tblname_chats;

global $wplc_tblname_msgs;

$wplc_tblname_chats = $wpdb->prefix . "wplc_chat_sessions";

$wplc_tblname_msgs = $wpdb->prefix . "wplc_chat_msgs";



require_once("functions.php");




// stuff goes here


/* we're using PHP 'sleep' which may lock other requests until our script wakes up. Call this function to ensure that other requests can run without waiting for us to finish */
session_write_close();

$check = 1;

if ($check == 1) {

    if($_POST['action'] == 'wplc_admin_long_poll'){
        if (defined('WPLC_TIMEOUT')) { @set_time_limit(WPLC_TIMEOUT); } else { @set_time_limit(120); }
        //sleep(6);
        $i = 1;
        while($i <= $iterations){
            
            // update chats if they have timed out every 10 seconds
            if($i %10 == 0) {
                wplc_update_chat_statuses();
            }
            
           

 
            
            if($_POST['wplc_update_admin_chat_table'] == 'false'){
                $old_chat_data = false;
            } else {
                $old_chat_data = $_POST['wplc_update_admin_chat_table'];
            }
            
            $pending = wplc_check_pending_chats();
            $new_chat_data = wplc_list_chats();
            
            if(stripslashes($new_chat_data) !== stripslashes($old_chat_data)){
                $array['old_chat_data'] = $old_chat_data;
                $array['wplc_update_admin_chat_table'] = $new_chat_data;
                $array['pending'] = $pending;
                $array['action'] = "wplc_update_admin_chat";
                
            }
            
            if(isset($array)){
                echo json_encode($array);
                break;
            }
            @ob_end_flush();
            if (defined('WPLC_DELAY_BETWEEN_LOOPS')) { usleep(WPLC_DELAY_BETWEEN_LOOPS); } else { usleep(500000); }
            $i++;
        }
    }
    if($_POST['action'] == "wplc_admin_long_poll_chat"){
        if (defined('WPLC_TIMEOUT')) { @set_time_limit(WPLC_TIMEOUT); } else { @set_time_limit(120); }
        $i = 1;
        $array = array();
        while($i <= $iterations){
            if(isset($_POST['action_2']) && $_POST['action_2'] == "wplc_long_poll_check_user_opened_chat"){
                $chat_status = wplc_return_chat_status($_POST['cid']);
                if($chat_status == 3){
                    $array['action'] = "wplc_user_open_chat";
                }
            } else {
                $new_chat_status = wplc_return_chat_status($_POST['cid']);
                if($new_chat_status != $_POST['chat_status']){
                    $array['chat_status'] = $new_chat_status;
                    $array['action'] = "wplc_update_chat_status";
                }
                $new_chat_message = wplc_return_admin_chat_messages($_POST['cid']);
                if($new_chat_message){
                    
                    $array['chat_message'] = $new_chat_message;
                    $array['action'] = "wplc_new_chat_message";
                }
            }
            if($array){
                echo json_encode($array);
                break;
            }
            @ob_end_flush();
            if (defined('WPLC_DELAY_BETWEEN_LOOPS')) { usleep(WPLC_DELAY_BETWEEN_LOOPS); } else { usleep(500000); }
            $i++;
        }
    }
    if ($_POST['action'] == "wplc_admin_accept_chat") {
        wplc_admin_accept_chat(sanitize_text_field($_POST['cid']));
    }
    if ($_POST['action'] == "wplc_admin_close_chat") {
        $chat_id = sanitize_text_field($_POST['cid']);
        wplc_change_chat_status($chat_id,1);
        echo 'done';
    }
    if ($_POST['action'] == "wplc_admin_send_msg") {
        $chat_id = sanitize_text_field($_POST['cid']);
        $chat_msg = sanitize_text_field($_POST['msg']);
        $wplc_rec_msg = wplc_record_chat_msg("2",$chat_id,$chat_msg);
        if ($wplc_rec_msg) {
            echo 'sent';
        } else {
            echo "There was an error sending your chat message. Please contact support";
        }
    }

    //User Ajax
    
    if($_POST['action'] == 'wplc_call_to_server_visitor'){
        if (defined('WPLC_TIMEOUT')) { @set_time_limit(WPLC_TIMEOUT); } else { @set_time_limit(120); }
        $i = 1;
        $array = array("check" => false);
        
        while($i <= $iterations){
            if($_POST['cid'] == null || $_POST['cid'] == "" || $_POST['cid'] == "null" || $_POST['cid'] == 0){
//                echo 1;
                $user = "user".time();
                $email = "no email set";
                $cid = wplc_log_user_on_page($user,$email,$_POST['wplcsession']);
                $array['cid'] = $cid;
                $array['status'] = wplc_return_chat_status($cid);
                $array['wplc_name'] = $user;
                $array['wplc_email'] = $email;
                $array['check'] = true;        

            } else {
//                echo 2;
                $new_status = wplc_return_chat_status($_POST['cid']);
                $array['wplc_name'] = $_POST['wplc_name'];
                $array['wplc_email'] = $_POST['wplc_email'];
                $array['cid'] = $_POST['cid'];
                if($new_status == $_POST['status']){ // if status matches do the following
                    if($_POST['status'] != 2){
                        /* check if session_variable is different? if yes then stop this script completely. */
                        if (isset($_POST['wplcsession']) && $_POST['wplcsession'] != '' && $i > 1) {
                            $wplc_session_variable = $_POST['wplcsession'];
                            $current_session_variable = wplc_return_chat_session_variable($_POST['cid']);
                            if ($current_session_variable != "" && $current_session_variable != $wplc_session_variable) {
                                /* stop this script */
                                $array['status'] = 11;
                                echo json_encode($array);
                                die();
                            }
                        }


                        if ($i == 1) {
                            wplc_update_user_on_page(sanitize_text_field($_POST['cid']), sanitize_text_field($_POST['status']),$_POST['wplcsession']);
                        }
                    }
                    if ($_POST['status'] == 0){ // browsing - user tried to chat but admin didn't answer so turn back to browsing
                        wplc_update_user_on_page($_POST['cid'], 5,$_POST['wplcsession']);
                        $array['status'] = 5;
                        $array['check'] = true;
                    } else if($_POST['status'] == 3){
                        //wplc_update_user_on_page($_POST['cid'], 3);
                        $messages = wplc_return_user_chat_messages($_POST['cid']);
                        if ($messages){
                            wplc_mark_as_read_user_chat_messages($_POST['cid']);
                            $array['status'] = 3;
                            $array['data'] = $messages;
                            $array['check'] = true;
                        }
                    } 

                } else { // statuses do not match
                    $array['status'] = $new_status;
                    if($new_status == 1){ // completed
                        wplc_update_user_on_page($_POST['cid'], 8,$_POST['wplcsession']);
                        $array['check'] = true;
                        $array['status'] = 8;
                        $array['data'] =  __("Admin has closed and ended the chat","wplivechat");
                    }
                    else if($new_status == 2){ // pending
                        $array['check'] = true;
                        $array['wplc_name'] = wplc_return_chat_name($_POST['cid']);
                        $array['wplc_email'] = wplc_return_chat_email($_POST['cid']);
                    }
                    else if($new_status == 3){ // active
                        $array['data'] = null;
                        $array['check'] = true;
                        if($_POST['status'] == 5){
                            $messages = wplc_return_chat_messages($_POST['cid']);
                            if ($messages){
                                $array['data'] = $messages;
                            }
                        }
                    }
                    else if($new_status == 7){ // timed out
                        wplc_update_user_on_page($_POST['cid'], 5,$_POST['wplcsession']);
                    }
                    else if($new_status == 9){ // user closed chat without inputting or starting a chat
                        $array['check'] = true;
                    } 
                    else if($new_status == 0){ // no answer from admin
                        $array['data'] = __('There is No Answer. Please Try Again Later', 'wplivechat');
                        $array['check'] = true;
                    } 
                    else if($new_status == 10){ // minimized active chat
                        $array['check'] = true;
                        if($_POST['status'] == 5){
                            $messages = wplc_return_chat_messages($_POST['cid']);
                            if ($messages){
                                $array['data'] = $messages;
                            }
                        }
                    }
                }
            }
            if($array['check'] == true){
                echo json_encode($array);
                break;
            }
            $i++;
            @ob_end_flush();
            if (defined('WPLC_DELAY_BETWEEN_LOOPS')) { usleep(WPLC_DELAY_BETWEEN_LOOPS); } else { usleep(500000); }
        }
    }
    
/*  */
    if ($_POST['action'] == "wplc_user_close_chat") {
        if($_POST['status'] == 5){
            wplc_change_chat_status($_POST['cid'],9);
        } else if($_POST['status'] == 3){
            wplc_change_chat_status($_POST['cid'],8);
        }
    }

    if ($_POST['action'] == "wplc_user_minimize_chat") {
        $chat_id = $_POST['cid'];
        wplc_change_chat_status(sanitize_text_field($_POST['cid']),10);
    }
    if ($_POST['action'] == "wplc_user_maximize_chat") {
        $chat_id = $_POST['cid'];
        wplc_change_chat_status(sanitize_text_field($_POST['cid']),3);
    }

    if ($_POST['action'] == "wplc_user_send_msg") {
        $chat_id = sanitize_text_field($_POST['cid']);
        $chat_msg = sanitize_text_field($_POST['msg']);
        $wplc_rec_msg = wplc_record_chat_msg("1",$chat_id,$chat_msg);
        if ($wplc_rec_msg) {
            echo 'sent';
        } else {
            echo "There was an error sending your chat message. Please contact support";
        }
    }
    if ($_POST['action'] == "wplc_start_chat") {
        
        if (isset($_POST['cid'])) {
            if ($_POST['name'] && $_POST['email']) {
                echo wplc_user_initiate_chat(sanitize_text_field($_POST['name']),sanitize_email($_POST['email']),sanitize_text_field($_POST['cid']),$_POST['wplcsession']); // echo the chat session id
            } else {
                echo "error2";
            }
        } else {
            if ($_POST['name'] && $_POST['email']) {
                echo wplc_user_initiate_chat(sanitize_text_field($_POST['name']),sanitize_email($_POST['email']),null,$_POST['wplcsession']); // echo the chat session id
            } else {
                echo "error2";
            }
        }
    }
}

die();