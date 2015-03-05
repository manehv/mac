<?php
global $current_user;
get_currentuserinfo();
?><div class="wrap">
   
    
    <div id="icon-options-general" class="icon32 icon32-posts-post"><br></div><h2><?php _e("WP Live Chat Support Feedback","wplivechat") ?></h2>
    <h3><?php _e("We'd love to hear your comments and/or suggestions","wplivechat"); ?></h3>
    <form name="wplc_feedback" action="" method="POST">
     <table width='100%'>
        <tr>
            <td width="250px" >
                <label><?php _e("Your Name","wplivechat"); ?></label>
            </td>
            <td>
                <input type="text" class='wplc-input' name="wplc_nl_feedback_name" value="<?php echo $current_user->user_firstname; ?>"/>
           </td>
        </tr>
        <tr>
            <td width="250px" >
                <label><?php _e("Your Email","wplivechat"); ?></label>
            </td>
            <td>
                <input type="text" class='wplc-input' name="wplc_nl_feedback_email" value="<?php echo $current_user->user_email; ?>"/>
           </td>
        </tr>
        <tr>
            <td width="250px" >
                <label><?php _e("Your Website","wplivechat"); ?></label>
            </td>
            <td>
                <input type="text" class='wplc-input' name="wplc_nl_feedback_website" value="<?php echo get_site_url(); ?>"/>
           </td>
        </tr>
        <tr>
            <td width="250px" valign='top' >
                <label><?php _e("Feedback","wplivechat"); ?></label>
            </td>
            <td>
                <textarea name="wplc_nl_feedback_feedback" cols='60' rows='10'></textarea>
           </td>
        </tr>
        <tr>
            <td width="250px" valign='top' >
                
            </td>
            <td>
                <input type='submit' name='wplc_nl_send_feedback' class='button-primary' value='<?php _e("Send Feedback","wplivechat") ?>' />
           </td>
        </tr>
     </table>
    
    </form>
    
