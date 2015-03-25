<?php  
    /* 
    Plugin Name: FormCraft Add-Ons
    Description: A collection of add-ons for FormCraft (MailChimp, AWeber and Campaign Monitor)
    Author: nCrafts
    Author URI: http://nCrafts.net/
    Plugin URI: http://codecanyon.net/item/formcraft-premium-wordpress-form-builder/5335056?rel=ncrafts
    Version: 1.1.3
    */

    error_reporting(0);

    if (!isset($_SESSION)) {
        session_start();
    }
    define('FORMCRAFT_ADD','1');
    global $wpdb, $table_add;
    $table_add = $wpdb->prefix . "formcraft_add";

    add_action('wp_ajax_formcraft_add_update', 'formcraft_add_update');
    add_action('wp_ajax_nopriv_formcraft_add_update', 'formcraft_add_update');


    function mailchimp_fc($emails, $custom, $list_id, $double, $welcome){
        global $wpdb, $table_add;
        $api = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'mailchimp'");

        if($emails==null){ return false; } 
        require_once('MCAPI.class.php');
        $api = new MCAPI($api);

        foreach($emails as $email)
        {
            if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $email)) {
                $api->listSubscribe($list_id, $email, $custom,'',$double,true,'',$welcome);
            }
        }
    }

    function campaign_fc($emails, $custom, $list_id){
        global $wpdb, $table_add;
        $api = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'campaign'");


        if($emails==null){ return false; } 

        require_once('campaign/csrest_subscribers.php');

        $auth = array('api_key' => $api);
        $wrap = new CS_REST_Subscribers($list_id, $auth);

        foreach($emails as $email)
        {
            $params = '';
            if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $email)) 
            {
                $params['EmailAddress'] = $email;
                foreach ($custom as $key => $val)
                {
                    $params[$key] = $val;
                }
                $result = $wrap->add($params);
            }
        }
    }

    function gr_fc($emails, $custom, $list_id){
        global $wpdb, $table_add;
        $api = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'gr'");
        if($emails==null){ return false; }
        require_once('getresponse.php');
        $api_url = 'http://api2.getresponse.com';
        $client = new jsonRPCClient($api_url);

        $campaigns = $client->get_campaigns(
            $api,
            array (
                'name' => array ( 'EQUALS' => $list_id )
                )
            );
        if (count($campaigns)==0){return false;}
        $CAMPAIGN_ID = array_pop(array_keys($campaigns));        

        foreach ($emails as $email) {
            $custom = is_array($custom) ? $custom : array();            
            $data = array_merge(array('campaign'=>$CAMPAIGN_ID,'email'=>$email), $custom);
            try {
                $result = $client->add_contact($api,$data);
            }
            catch(Exception $exc) {

            }
        }
    }    

    function aweber_fc($emails, $custom, $list_id){
        global $wpdb, $table_add;
        $key = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'aweber'");
        $key = json_decode($key);


        if($emails==null){ return false; } 
        require_once('aweber/aweber_api/aweber_api.php');

        foreach($emails as $email)
        { 
            $params = '';
            if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $email)) 
            {


                $aweber = new AWeberAPI($key[0], $key[1]);
                $account = $aweber->getAccount($key[2], $key[3]);

                $lists = $account->lists->find(array('name' => $listName));

                foreach ($lists as $list)
                {
                    if ($list_id == $list->name || $list_id == $list->id)
                    {
                        $listID = $list->id;
                    }
                }


                $listUrl = "/accounts/$account->id/lists/$listID";
                $list = $account->loadFromUrl($listUrl);
                if ($list==false){return false;}

                $params['email'] = $email;

                foreach ($custom as $key => $val)
                {
                    $params[$key] = $val;
                }

                try {
                    $list->subscribers->create($params);
                }
                catch(Exception $exc) {
                    // print $exc;
                }

            }
        }
    }


    function formcraft_add_update()
    {
        global $wpdb, $table_add;


        // MailChimp
        if ($_POST['app']=='mailchimp')
        {

            require_once('MCAPI.class.php');
            $api = new MCAPI($_POST['code']);

            if ($api->ping())
            {


                $res = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = '$_POST[app]'");
                if ($res==$_POST['code'])
                {
                    echo 'saved';
                    die();
                }

                $result = $wpdb->query( "UPDATE $table_add SET
                    code1 = '$_POST[code]'
                    WHERE application = '$_POST[app]'" );
                if ($result)
                {
                    echo "saved";
                }

                die();

            }
            else
            {
                echo 'Connection failed';
                if ($_POST['code']==null)
                {
                    $result = $wpdb->query( "UPDATE $table_add SET
                        code1 = null
                        WHERE application = '$_POST[app]'" );
                }
                die();
            }

        }


        if ($_POST['app']=='gr')
        {    
            $gr = $wpdb->get_results("SELECT * FROM $table_add WHERE application = 'gr'");
            if (count($gr)==0)
            {
                $sql = "INSERT INTO $table_add (`id`, `application`, `code1`, `code2`) VALUES (NULL, 'gr', NULL, NULL)";
                dbDelta($sql);
            }
            $_POST['code'] = addslashes($_POST['code']);

            if ($_POST['code']!=null)
            {
                $result = $wpdb->query( "UPDATE $table_add SET
                    code1 = '$_POST[code]'
                    WHERE application = '$_POST[app]'" );
                echo 'saved';
                die();
            }
            echo 'Key removed';
            die();
        }        


        // Campaign Monitor
        if ($_POST['app']=='campaign')
        {
            if ($_POST['code']==null)
            {
                $result = $wpdb->query( "UPDATE $table_add SET
                    code1 = null
                    WHERE application = '$_POST[app]'" );
            }
            else
            {
                require_once('campaign/csrest_general.php');
                $auth = array('api_key' => $_POST['code']);
                $wrap = new CS_REST_General($auth);

                $result = $wrap->get_clients();
                if($result->was_successful()) {
                    $temp = $wpdb->query( "UPDATE $table_add SET
                        code1 = '$_POST[code]'
                        WHERE application = '$_POST[app]'" );
                    echo "saved";
                }
                else 
                {
                    echo $result->response->Message;
                }
            }
        }


        if ($_POST['app']=='aweber')
        {
            require('aweber/aweber_api/aweber_api.php');

            try {
                $authorization_code = $_POST['code'];
                $auth = AWeberAPI::getDataFromAweberID($authorization_code);
                list($consumerKey, $consumerSecret, $accessKey, $accessSecret) = $auth;
                $size = sizeof($auth);

                if ($size==4)
                {

                    $auth = json_encode($auth);
                    $temp = $wpdb->query( "UPDATE $table_add SET
                      code1 = '$auth'
                      WHERE application = 'aweber'" );
                    if ($temp)
                    {
                        echo "saved";
                        die();
                    }
                }
                else
                {
                    echo 'Unidentified token response';
                }
                if ($_POST['code']==null)
                {
                    $result = $wpdb->query( "UPDATE $table_add SET
                        code1 = null
                        WHERE application = '$_POST[app]'" );
                }
                die();
            }
            catch(AWeberAPIException $exc) {
                echo "<strong>Error</strong><br>
                Type: $exc->type<br>
                Message: $exc->message <br>          
                Regenerate the code and try again.";
                die();
            }

        }

        die();


    }




    function formcraft_add_activate()
    {

        global $wpdb, $table_add;

        $sql = "CREATE TABLE $table_add ( id mediumint(9) NOT NULL AUTO_INCREMENT, application text CHARACTER SET utf8 COLLATE utf8_general_ci NULL, code1 text CHARACTER SET utf8 COLLATE utf8_general_ci NULL, code2 text CHARACTER SET utf8 COLLATE utf8_general_ci NULL, UNIQUE KEY id (id) ) CHARACTER SET utf8 COLLATE utf8_general_ci";


        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $myrows = $wpdb->get_results( "SELECT * FROM $table_add WHERE application='mailchimp'" );
        if (!$myrows)
        {
            $sql = "INSERT INTO $table_add (`id`, `application`, `code1`, `code2`) VALUES (NULL, 'mailchimp', NULL, NULL), (NULL, 'aweber', NULL, NULL), (NULL, 'campaign', NULL, NULL)";
            dbDelta($sql);
        }


    }
    register_activation_hook( __FILE__, 'formcraft_add_activate' );

    function formcraft_add_builder()
    {

        global $wpdb, $table_add;
        $table_add = $wpdb->prefix . "formcraft_add";

        if (defined('FORMCRAFT_ADD'))
        {
            $mc = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'mailchimp'");
            $aw = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'aweber'");
            $campaign = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'campaign'");
            $gr = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'gr'");
        }

        if ($mc)
        {
            ?>
            <Style>
                .mc_show
                {
                    display: block !important;
                }
            </Style>

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_fo" href="#form_options_mailchimp" style='color: green'>
                        Integration: MailChimp
                    </a>
                </div>

                <div id="form_options_mailchimp" class="accordion-body collapse">
                    <div class="accordion-inner l2">

                        <div class='global_holder'>
                            <div class='gh_head'>
                                <img src='<?php echo plugins_url().'/formcraft/images/mc.png'; ?>' alt='MailChimp' style='width: 150px'>
                            </div>
                            <span class='settings_desc'>You can use the forms to easily add to your list of subscribers on MailChimp.</span><br>

                            <p>Step 1 of 2<span class='settings_desc'>
                                Enter your List ID below. Click <a href='http://kb.mailchimp.com/article/how-can-i-find-my-list-id' target='_blank'>here</a> to know how to get the List ID.</span></p>
                                <input ng-model='con[0].mc_list' style='width: 100%' type='text'>
                                <br>
                                <label class='label_radio circle-ticked'>
                                    <input type='checkbox' ng-model='con[0].mc_double' ng-true-value='true'>
                                    <div class='label_div' style='background: #f3f3f3'>Double Opt-In</div>
                                </label>
                                <label class='label_radio circle-ticked'>
                                    <input type='checkbox' ng-model='con[0].mc_welcome' ng-true-value='true'>
                                    <div class='label_div' style='background: #f3f3f3'>Send Welcome Email</div>
                                </label>
                                <br><br>
                                <p>Step 2 of 2<span class='settings_desc'>
                                    Now add an email field and check the option 'Add to MailChimp'.<br>That's it!</span>
                                </p>

                            </div>

                        </div>
                    </div>
                </div>

                <?php
            }

            if ($campaign)
            {
                ?>
                <Style>
                    .cm_show
                    {
                        display: block !important;
                    }
                </Style>

                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_fo" href="#form_options_campaign" style='color: green'>
                            Integration: Campaign Monitor
                        </a>
                    </div>

                    <div id="form_options_campaign" class="accordion-body collapse">
                        <div class="accordion-inner l2">

                            <div class='global_holder'>
                                <div class='gh_head'>
                                    <img src='<?php echo plugins_url().'/formcraft/images/cm.png'; ?>' alt='Campaign Monitor' style='width: 150px'>
                                </div>
                                <span class='settings_desc'>You can use the forms to easily add to your list of subscribers on Campaign Monitor.</span><br>

                                <p>Step 1 of 2<span class='settings_desc'>
                                    Enter your List ID below.</span></p>
                                    <input ng-model='con[0].campaign_list' style='width: 100%' type='text'>
                                    <br>
                                    <p>Step 2 of 2<span class='settings_desc'>
                                        Now add an email field and check the option 'Add to Campaign Monitor'.<br>That's it!</span>
                                    </p>

                                </div>

                            </div>
                        </div>
                    </div>

                    <?php

                }

                if ($gr)
                {
                    ?>
                    <Style>
                        .gr_show
                        {
                            display: block !important;
                        }
                    </Style>

                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_fo" href="#form_options_campaign" style='color: green'>
                                Integration: GetResponse
                            </a>
                        </div>

                        <div id="form_options_campaign" class="accordion-body collapse">
                            <div class="accordion-inner l2">

                                <div class='global_holder'>
                                    <div class='gh_head'>
                                        <img src='<?php echo plugins_url().'/formcraft/images/gr.png'; ?>' alt='Campaign Monitor' style='width: 150px'>
                                    </div>
                                    <span class='settings_desc'>You can use the forms to easily add to your campaign on GetResponse.</span><br>

                                    <p>Step 1 of 2<span class='settings_desc'>
                                        Enter your Campaign Name below.</span></p>
                                        <input ng-model='con[0].gr_list' style='width: 100%' type='text'>
                                        <br>
                                        <p>Step 2 of 2<span class='settings_desc'>
                                            Now add an email field and check the option 'Add to GetResponse'.<br>That's it!</span>
                                        </p>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <?php

                    }                

                    if ($aw)
                    {

                        ?>
                        <Style>
                            .aw_show
                            {
                                display: block !important;
                            }
                        </Style>

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_fo" href="#form_options_aweber" style='color: green'>
                                    Integration: AWeber
                                </a>
                            </div>

                            <div id="form_options_aweber" class="accordion-body collapse">
                                <div class="accordion-inner l2">

                                    <div class='global_holder'>
                                        <div class='gh_head'>
                                            <img src='<?php echo plugins_url().'/formcraft/images/aweber.png'; ?>' alt='AWeber' style='width: 150px; margin-left: -12px'>
                                        </div>
                                        <span class='settings_desc'>You can use the forms to easily add to your list of subscribers on AWeber.</span><br>

                                        <p>Step 2 of 3<span class='settings_desc'>
                                            Enter your List ID below. Click <a href='http://snibz.uservoice.com/knowledgebase/articles/93851-finding-your-aweber-id' target='_blank'>here</a> to know how to get the List ID.</span></p>
                                            <input ng-model='con[0].aw_list' style='width: 100%' type='text'>
                                            <br><br>
                                            <p>Step 3 of 3<span class='settings_desc'>
                                                Now add an email field and check the option 'Add to AWeber'.<br>That's it!</span>
                                            </p>

                                        </div>

                                    </div>
                                </div>
                            </div>


                            <?php



                        }




                    }

                    function formcraft_add_content()
                    {
                        global $wpdb, $table_add;
                        $table_add = $wpdb->prefix . "formcraft_add";

                        if (defined('FORMCRAFT_ADD'))
                        {
                            $mc = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'mailchimp'");
                            $aw = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'aweber'");
                            $campaign = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'campaign'");
                            $gr = $wpdb->get_var("SELECT code1 FROM $table_add WHERE application = 'gr'");
                        }
                        ?>
                        <script>
                            jQuery(document).ready(function()
                            {
                                jQuery('.fc_addbtn').click(function(){
                                    var temp = this.id.split('_');
                                    var temp_val = jQuery('#add_'+temp[1]).val();
                                    var temp_app = jQuery('#add_'+temp[1]).attr('app');
                                    var temp_id = this.id;
                                    var id = jQuery('#'+temp_id).parents('.add_span_cover').attr('id');
                                    jQuery('#'+id+' .response').slideUp('slow');

                                    jQuery(this).text(' . . . ');
                                    jQuery.ajax({
                                        url: ajaxurl,
                                        type: "POST",
                                        data: 'action=formcraft_add_update&app='+temp_app+'&code='+temp_val,
                                        success: function (response) {
                                            if (response=='saved')
                                            {
                                              jQuery('#'+temp_id).text('Save');
                                              var id = jQuery('#'+temp_id).parents('.add_span_cover').attr('id');
                                              jQuery('#'+id+' .op_div').css({'opacity':'.3'});
                                              jQuery('#'+id+' .addon_nc').slideUp('slow');
                                              jQuery('#'+id+' .addon_c').slideDown('slow');
                                              jQuery('#'+id+' .response').slideUp('slow');
                                              jQuery('#'+id+' .response').html('');
                                          }
                                          else
                                          {
                                              jQuery('#'+temp_id).text('Retry');
                                              var id = jQuery('#'+temp_id).parents('.add_span_cover').attr('id');
                                              jQuery('#'+id+' .op_div').css({'opacity':'1'});
                                              jQuery('#'+id+' .addon_nc').slideDown('slow');
                                              jQuery('#'+id+' .addon_c').slideUp('slow');
                                              jQuery('#'+id+' .response').html(response);
                                              jQuery('#'+id+' .response').slideDown('slow');
                                          }
                                      },
                                      error: function (response) {
                                        if (response=='saved')
                                        {
                                          jQuery('#'+temp_id).text('Save');
                                      }
                                      else
                                      {
                                          jQuery('#'+temp_id).text('Retry');
                                      }
                                  }
                              });
});
});
</script>
<form>

    <div class='add_span_cover' id='addcover_1'>
        <div class='add_span'>
            <div class='as_img'>
                <img src='<?php echo plugins_url().'/formcraft/images/mc.png'; ?>' alt='MailChimp' style='width: 160px'>
            </div>

            <?php if ($mc)
            {
                ?>
                <div class='addon_c' style='color: green; font-weight: bold'>Connected</div>
                <div class='addon_nc' style='color: red; font-weight: bold; display: none'>Not Connected</div>
                <?php
            }
            else
            {
                ?>
                <div class='addon_c' style='color: green; font-weight: bold; display: none'>Connected</div>
                <div class='addon_nc' style='color: red; font-weight: bold'>Not Connected</div>
                <?php
            }
            ?>

            <div class='op_div' style='opacity: <?php if ($mc) {echo '.3';} else {echo '1';}?>'>

                <label>Enter the API Key</label>
                <input type='text' style='width: 270px' id='add_1' app='mailchimp'>
                <br>
                <p>Click <a href='http://kb.mailchimp.com/article/where-can-i-find-my-api-key' target='_blank'>here</a> to know how to get an API key.
                </p>
                <button id='addbtn_1' type='button' class='fc_addbtn fc-btn'>Save</button>
                <div class='response' style='margin-top: 10px; font-size: 13px'></div>

            </div>
        </div>
    </div>




    <div class='add_span_cover' id='addcover_2'>
        <div class='add_span'>
         <div class='as_img'>
           <img src='<?php echo plugins_url().'/formcraft/images/cm.png'; ?>' alt='MailChimp' style='width: 160px'>
       </div>

       <?php if ($campaign)
       {
        ?>
        <div class='addon_c' style='color: green; font-weight: bold'>Connected</div>
        <div class='addon_nc' style='color: red; font-weight: bold; display: none'>Not Connected</div>
        <?php
    }
    else
    {
        ?>
        <div class='addon_c' style='color: green; font-weight: bold; display: none'>Connected</div>
        <div class='addon_nc' style='color: red; font-weight: bold'>Not Connected</div>
        <?php
    }
    ?>

    <div class='op_div' style='opacity: <?php if ($campaign) {echo '.3';} else {echo '1';}?>'>

        <label>Enter the API Key</label>
        <input type='text' style='width: 270px' id='add_2' app='campaign'>
        <br>
        <p>Click <a href='http://help.campaignmonitor.com/topic.aspx?t=206' target='_blank'>here</a> to know how to get an API key.
        </p>
        <button id='addbtn_2' type='button' class='fc_addbtn fc-btn'>Save</button>
        <div class='response' style='margin-top: 10px; font-size: 13px'></div>

    </div>
</div>
</div>



<div class='add_span_cover' id='addcover_3'>
    <div class='add_span'>
        <div class='as_img'>
            <img src='<?php echo plugins_url().'/formcraft/images/aweber.png'; ?>' alt='MailChimp' style='width: 160px'>
        </div>

        <?php if ($aw)
        {
            ?>
            <div class='addon_c' style='color: green; font-weight: bold'>Connected</div>
            <div class='addon_nc' style='color: red; font-weight: bold; display: none'>Not Connected</div>
            <?php
        }
        else
        {
            ?>
            <div class='addon_c' style='color: green; font-weight: bold; display: none'>Connected</div>
            <div class='addon_nc' style='color: red; font-weight: bold'>Not Connected</div>
            <?php
        }
        ?>

        <div class='op_div' style='opacity: <?php if ($aw) {echo '.3';} else {echo '1';}?>'>

            <label>Enter the Authorization Code</label>
            <textarea style='width: 270px' rows='4' id='add_3' app='aweber'></textarea>
            <br>
            <p>Click <a href='https://auth.aweber.com/1.0/oauth/authorize_app/a908ab91' target='_blank'>here</a> to get the code.
            </p>
            <button id='addbtn_3' type='button' class='fc_addbtn fc-btn' style='vertical-align: top'>Save</button>
            <div class='response' style='margin-top: 10px; font-size: 13px'></div>

        </div>
    </div>
</div>

<div class='add_span_cover' id='addcover_4'>
    <div class='add_span'>
        <div class='as_img'>
            <img src='<?php echo plugins_url().'/formcraft/images/gr.png'; ?>' alt='GetResponse' style='width: 160px'>
        </div>

        <?php 
        if ($gr)
        {
            ?>
            <div class='addon_c' style='color: green; font-weight: bold'>Connected</div>
            <div class='addon_nc' style='color: red; font-weight: bold; display: none'>Not Connected</div>
            <?php
        }
        else
        {
            ?>
            <div class='addon_c' style='color: green; font-weight: bold; display: none'>Connected</div>
            <div class='addon_nc' style='color: red; font-weight: bold'>Not Connected</div>
            <?php
        }
        ?>

        <div class='op_div' style='opacity: <?php if ($gr) {echo '.3';} else {echo '1';}?>'>

            <label>Enter the API Key</label>
            <input type='text' style='width: 270px' id='add_4' app='gr'>
            <br>
            <p>Click <a href='http://support.getresponse.com/faq/where-i-find-api-key' target='_blank'>here</a> to find the API.
            </p>            
            <button id='addbtn_4' type='button' class='fc_addbtn fc-btn'>Save</button>
            <div class='response' style='margin-top: 10px; font-size: 13px'></div>

        </div>
    </div>
</div>

</form>

<?php
}



?>