
<center>
    <h1 style="font-weight: 300; font-size: 50px; line-height: 50px;">
        <?php _e("Welcome to ",'wplivechat'); ?> 
        <strong style='color: #ec822c;'>WP Live Chat Support</strong> 
        <small><?php _e('Version 4', 'wplivechat'); ?></small>
    </h1>
    <div class="about-text" style="margin: 0;"><?php _e('Provide Instant Live Chat Support!', 'wplivechat'); ?></div>

    <h2 style="font-size: 25px;"><?php _e('How did you find us?', 'wplivechat'); ?></h2>
    <form method="post" name="wplc_find_us_form" style="font-size: 16px;">
        <div  style="text-align: left; width:275px;">
            <input type="radio" name="wplc_find_us" id="wordpress" value='repository'>
            <label for="wordpress">
                <?php _e('WordPress.org plugin repository ', 'wplivechat'); ?>
            </label>
            <br/>
            <input type='text' placeholder="<?php _e('Search Term', 'wplivechat'); ?>" name='wplc_nl_search_term' style='margin-top:5px; margin-left: 23px; width: 100%  '>
            <br/>
            <input type="radio" name="wplc_find_us" id="search_engine" value='search_engine'>
            <label for="search_engine">
                <?php _e('Google or other search Engine', 'wplivechat'); ?>
            </label>
            <br/>
            <input type="radio" name="wplc_find_us" id="friend" value='friend'>
            
            <label for='friend'>
                <?php _e('Friend recommendation', 'wplivechat'); ?>
            </label>
            <br/>   
            <input type="radio" name="wplc_find_us" id='other' value='other'>
            
            <label for='other'>
                <?php _e('Other', 'wplivechat'); ?>
            </label>
            <br/>
            
            <textarea placeholder="<?php _e('Please Explain', 'wplivechat'); ?>" style='margin-top:5px; margin-left: 23px; width: 100%' name='wplc_nl_findus_other_url'></textarea>
        </div>
        <div>
            
        </div>
        <div>
            
        </div>
        <div style='margin-top: 20px;'>
            <button name='action' value='wplc_submit_find_us' class="button-primary" style="font-size: 30px; line-height: 60px; height: 60px; margin-bottom: 10px;"><?php _e('Submit', 'wplivechat'); ?></button>
            <br/>
            <button name='action' value="wplc_skip_find_us" class="button"><?php _e('Skip', 'wplivechat'); ?></button>
        </div>
    </form> 
</center>

