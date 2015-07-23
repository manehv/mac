<?php 
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7.3
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/
if (isset($message) && $message != ''){ if(is_admin()){ ?><div id="success_message" style="margin-bottom:0px; margin-top:15px; width:95%;"><div class="arfsuccessmsgicon"></div><div class="arf_success_message"><?php } echo $message; if(is_admin()){ ?></div></div><?php } }

if (isset($message_notRquireFeild) && $message_notRquireFeild!= ''){ if(is_admin()){ ?><div id="warning_message" class="warning_message" style="padding:5px; margin-bottom:0px; margin-top:15px;float:left;display:block !important;"><?php } echo $message_notRquireFeild; if(is_admin()){ ?></div><?php } } ?>

<?php if( isset($errors) && is_array($errors) && count($errors) > 0 ){ ?>

    <div style="margin-bottom:0px; margin-top:8px;">

        <ul id="frm_errors" style="margin-bottom: 0px; margin-top: 0px;">

            <?php foreach( $errors as $error )

                echo '<li><div class="arferrmsgicon"></div><div id="error_message">' . stripslashes($error) . '</div></li>';

            ?>

        </ul>

    </div>

<?php } ?>