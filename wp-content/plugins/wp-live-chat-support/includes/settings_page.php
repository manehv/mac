<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post">
        <br>
    </div>
    <h2><?php _e("WP Live Chat Support Settings","wplivechat")?></h2>
    <?php
        $wplc_settings = get_option("WPLC_SETTINGS");
        if ($wplc_settings["wplc_settings_align"]) { $wplc_settings_align[intval($wplc_settings["wplc_settings_align"])] = "SELECTED"; }
        if ($wplc_settings["wplc_settings_enabled"]) { $wplc_settings_enabled[intval($wplc_settings["wplc_settings_enabled"])] = "SELECTED"; }
        if ($wplc_settings["wplc_settings_fill"]) { $wplc_settings_fill = $wplc_settings["wplc_settings_fill"]; } else { $wplc_settings_fill = "ed832f"; }
        if ($wplc_settings["wplc_settings_font"]) { $wplc_settings_font = $wplc_settings["wplc_settings_font"]; } else { $wplc_settings_font = "FFFFFF"; }
        if(get_option("WPLC_HIDE_CHAT") == true) { $wplc_hide_chat = "checked"; } else { $wplc_hide_chat = ""; };
        
     ?>
    <form action='' name='wplc_settings' method='POST' id='wplc_settings'>
    
    <div id="wplc_tabs">
      <ul>
        <li><a href="#tabs-1"><?php _e("General Settings","wplivechat")?></a></li>
        <li><a href="#tabs-2"><?php _e("Chat Box","wplivechat")?></a></li>
        <li><a href="#tabs-3"><?php _e("Offline Messages","wplivechat")?></a></li>
        <li><a href="#tabs-4"><?php _e("Styling","wplivechat")?></a></li>
        <li><a href="#tabs-5"><?php _e("Agents", "wplivechat") ?></a></li>
        <li><a href="#tabs-6"><?php _e("Animations", "wplivechat") ?></a></li>
      </ul>
      <div id="tabs-1">
          <h3><?php _e("Main Settings",'wplivechat')?></h3>
          <table class='form-table' width='700'>
              <tr>
                  <td width='400' valign='top'><?php _e("Chat enabled","wplivechat")?>:</td>
                  <td>
                      <select id='wplc_settings_enabled' name='wplc_settings_enabled'>
                          <option value="1" <?php if (isset($wplc_settings_enabled[1])) { echo $wplc_settings_enabled[1]; } ?>><?php _e("Yes","wplivechat"); ?></option>
                          <option value="2" <?php if (isset($wplc_settings_enabled[2])) { echo $wplc_settings_enabled[2]; }?>><?php _e("No","wplivechat"); ?></option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td width='200' valign='top'><?php _e("Choose when I want to be online","wplivechat")?>:
                    <p class="description"><?php _e('Checking this will allow you to change your status to "Online" or "Offline" on the "Live Chat" page.', 'wplivechat'); ?></p>
                  </td>                  
                  <td>
                      <input type="checkbox" name="wplc_auto_online" disabled readonly="readonly"/>      
                      <small>
                        <i> 
                            <?php _e("available in the","wplivechat")?> 
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=change_status" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a> 
                            <?php _e("only","wplivechat")?>
                        </i>
                    </small>
                  </td>
              </tr>
              <tr>
                  <td width='200' valign='top'>
                      <?php _e("Hide Chat","wplivechat")?>:
                      <p class="description"><?php _e("Hides chat for 24hrs when user clicks X", "wplivechat") ?> </p>
                  </td>
                  <td valign='top'>
                      <input type="checkbox"  value="true" readonly disabled/>
                      <small>
                        <i> 
                            <?php _e("available in the","wplivechat")?> 
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=name" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a> 
                            <?php _e("only","wplivechat")?>.
                            $19.95 <?php _e(" once off. Updates included forever.","wplivechat"); ?>
                        </i>
                    </small>
                  </td>
              </tr>
              <tr>
                  <td width='200' valign='top'>
                      <?php _e("Require Name And Email","wplivechat")?>:
                      <p class="description"><?php _e("Users will have to enter their Name and Email Address when starting a chat", "wplivechat") ?></p>
                  </td>
                  <td valign='top'>
                      <input type="checkbox" value="1" name="wplc_require_user_info" <?php if(isset($wplc_settings['wplc_require_user_info'])  && $wplc_settings['wplc_require_user_info'] == 1 ) { echo "checked"; } ?> />                    
                  </td>
              </tr>
              <tr>
                  <td width='200' valign='top'>
                      <?php _e("Input Field Replacement Text","wplivechat")?>:
                      <p class="description"><?php _e("This is the text that will show in place of the Name And Email fields", "wplivechat") ?></p>
                  </td>
                  <td valign='top'>
                      <textarea cols="45" rows="5" name="wplc_user_alternative_text" ><?php if(isset($wplc_settings['wplc_user_alternative_text'])) { echo stripslashes($wplc_settings['wplc_user_alternative_text']); } ?></textarea>
                </td>
              </tr>
              <tr>
                  <td width='200' valign='top'>
                      <?php _e("Use Logged In User Details","wplivechat")?>:
                      <p class="description"><?php _e("A user's Name and Email Address will be used by default if they are logged in.", "wplivechat") ?></p>
                  </td>
                  <td valign='top'>
                      <input type="checkbox" value="1" name="wplc_loggedin_user_info" <?php if(isset($wplc_settings['wplc_loggedin_user_info'])  && $wplc_settings['wplc_loggedin_user_info'] == 1 ) { echo "checked"; } ?> />                      
                  </td>
              </tr>
              <tr>
                  <td width='200' valign='top'>
                      <?php _e("Enable On Mobile Devices","wplivechat"); ?>
                      <p class="description"><?php _e("Disabling this will mean that the Chat Box will not be displayed on mobile devices. (Smartphones and Tablets)", "wplivechat") ?></p>
                  </td>
                  <td valign='top'>
                      <input type="checkbox" value="1" name="wplc_enabled_on_mobile" <?php if(isset($wplc_settings['wplc_enabled_on_mobile'])  && $wplc_settings['wplc_enabled_on_mobile'] == 1 ) { echo "checked"; } ?> />                      
                  </td>
              </tr>
              <tr>
                  <td width='200' valign='top'>
                      <?php _e("Record a visitor's IP Address","wplivechat"); ?>
                      <p class="description"><?php _e("Disable this to enable anonymity for your visitors", "wplivechat") ?></p>
                  </td>
                  <td valign='top'>
                      <input type="checkbox" value="1" name="wplc_record_ip_address" <?php if(isset($wplc_settings['wplc_record_ip_address'])  && $wplc_settings['wplc_record_ip_address'] == 1 ) { echo "checked"; } ?> />                      
                  </td>
              </tr>
              <tr>
                  <td width='200' valign='top'>
                      <?php _e("Include chat window on the following pages:","wplivechat"); ?>
                      <p class="description"><?php _e("Show the chat window on the following pages. Leave blank to show on all. (Use comma-separated Page ID's)", "wplivechat") ?></p>
                  </td>
                  <td valign='top'>
                      <input type="text" readonly="readonly" />
                      <small>
                        <i> 
                            <?php _e("available in the","wplivechat")?> 
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=include_pages" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a> 
                            <?php _e("only","wplivechat")?>
                        </i>
                    </small>
                  </td>
              </tr>
              <tr>
                  <td width='200' valign='top'>
                      <?php _e("Exclude chat window on the following pages:","wplivechat"); ?>
                      <p class="description"><?php _e("Do not show the chat window on the following pages. Leave blank to show on all. (Use comma-separated Page ID's)", "wplivechat") ?></p>
                  </td>
                  <td valign='top'>
                      <input type="text" readonly="readonly"/>
                      <small>
                        <i> 
                            <?php _e("available in the","wplivechat")?> 
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=exclude_pages" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a> 
                            <?php _e("only","wplivechat")?>
                        </i>
                    </small>
                  </td>
              </tr>
              
          </table>

      </div>
      <div id="tabs-2">
          <h3><?php _e("Chat Window Settings",'wplivechat')?></h3>
          <table class='form-table' width='700'>
              <tr>
                  <td width='200' valign='top'><?php _e("Chat box alignment","wplivechat")?>:</td>
                  <td>
                      <select id='wplc_settings_align' name='wplc_settings_align'>
                          <option value="1" <?php if (isset($wplc_settings_align[1])) { echo $wplc_settings_align[1]; } ?>><?php _e("Bottom left","wplivechat"); ?></option>
                          <option value="2" <?php if (isset($wplc_settings_align[2])) { echo $wplc_settings_align[2]; } ?>><?php _e("Bottom right","wplivechat"); ?></option>
                          <option value="3" <?php if (isset($wplc_settings_align[3])) { echo $wplc_settings_align[3]; } ?>><?php _e("Left","wplivechat"); ?></option>
                          <option value="4" <?php if (isset($wplc_settings_align[4])) { echo $wplc_settings_align[4]; } ?>><?php _e("Right","wplivechat"); ?></option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td>
                      <?php _e("Auto Pop-up","wplivechat") ?>
                  </td>
                  <td>
                      <input type="checkbox" name="wplc_auto_pop_up" value="1" <?php if(isset($wplc_settings['wplc_auto_pop_up'])  && $wplc_settings['wplc_auto_pop_up'] == 1 ) { echo "checked"; } ?>/>
                      <p class="description"><small><?php _e("Expand the chat box automatically (prompts the user to enter their name and email address).","wplivechat") ?></small></p>
                  </td>
              </tr>
              <tr>
            <!-- Chat Name-->
                <td width='200' valign='top'>
                    <?php _e("Name","wplivechat")?>:
                </td>
                <td>
                    <input type='text' size='50' maxlength='50' disabled readonly value='admin' />
                    <small>
                        <i> 
                            <?php _e("available in the","wplivechat")?> 
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=name" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a> 
                            <?php _e("only","wplivechat")?>
                        </i>
                    </small>
                </td>
            </tr>
            <!-- Chat Pic-->
            <tr>
                <td width='200' valign='top'>
                    <?php _e("Picture","wplivechat")?>:
                </td>
                <td>
                    <input id="wplc_pro_pic_button" type="button" value="<?php _e("Upload Image","wplivechat")?>" readonly disabled />
                    <small>
                        <i>
                            <?php _e("available in the","wplivechat")?>
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=pic" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a>
                                <?php _e("only","wplivechat")?>
                        </i>
                    </small>
                </td>
            </tr>
            <!-- Chat Logo-->
             <tr>
                <td width='200' valign='top'>
                    <?php _e("Logo","wplivechat")?>:
                </td>
                <td>
                    <input id="wplc_pro_logo_button" type="button" value="<?php _e("Upload Image","wplivechat")?>" readonly disabled />
                    <small>
                        <i> 
                            <?php _e("available in the","wplivechat")?> 
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=pic" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a>
                            <?php _e("only","wplivechat")?>
                        </i>
                    </small>
                </td>
            </tr>
            <!-- Chat Delay-->
              <tr>
                <td width='200' valign='top'>
                    <?php _e("Chat delay (seconds)","wplivechat")?>:
                </td>
                <td>
                    <input type='text' size='50' maxlength='50' disabled readonly value='10' /> 
                    <small>
                        <i> 
                            <?php _e("available in the","wplivechat")?> 
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=delay" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a> 
                            <?php _e("only","wplivechat")?>    
                        </i>
                    </small>
                </td>
              </tr>
              <!-- Chat Notification if want to chat-->
              <tr>
                  <td width='200' valign='top'>
                      <?php _e("Chat notifications", "wplivechat") ?>:
                  </td>
                  <td>
                      <input id='wplc_pro_chat_notification' name='wplc_pro_chat_notification' type='checkbox' value='yes' disabled="disabled" readonly/>
                      <?php _e("Alert me via email as soon as someone wants to chat", "wplivechat") ?>
                      <small>
                          <i>
                              <?php _e("available in the", "wplivechat") ?>
                              <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=alert" title="<?php _e("Pro Add-on", "wplivechat") ?>" target="_BLANK"><?php _e("Pro Add-on", "wplivechat") ?></a> 
                              <?php _e("only", "wplivechat") ?>
                          </i>
                      </small>
                  </td>
              </tr>
              <tr>
                  <td>
                      <?php _e("Display name and avatar in chat", "wplivechat") ?>
                  </td>
                  <td>
                      <input type="checkbox" name="wplc_display_name" value="1" <?php if (isset($wplc_settings['wplc_display_name']) && $wplc_settings['wplc_display_name'] == 1) {
                          echo "checked";
                      } ?>/>
                      <p class="description"><small><?php _e("Display the agent and user name above each message in the chat window.", "wplivechat") ?></small></p>
                  </td>
              </tr>
              <tr>
                  <td>
                      <?php _e("Only show the chat window to users that are logged in", "wplivechat") ?>
                  </td>
                  <td>
                      <input type="checkbox" name="wplc_display_to_loggedin_only" value="1" <?php
                      if (isset($wplc_settings['wplc_display_to_loggedin_only']) && $wplc_settings['wplc_display_to_loggedin_only'] == 1) {
                          echo "checked";
                      }
                      ?>/>
                      <p class="description"><small><?php _e("By checking this, only users that are logged in will be able to chat with you.", "wplivechat") ?></small></p>
                  </td>
              </tr>              
          </table>

      </div>
      <div id="tabs-3">
          <h3><?php _e("Offline Messages",'wplivechat')?></h3>
          <table class='form-table' width='700'>
              <tr>
                  <td>
                      <?php _e("Do not allow users to send offline messages", "wplivechat") ?>
                  </td>
                  <td>
                      <input type="checkbox" name="wplc_hide_when_offline" value="1" readonly="readonly"/>
                      <p class="description"><small><?php _e("The chat window will be hidden when it is offline. Users will not be able to send offline messages to you", "wplivechat") ?></small></p>
                  </td>
              </tr>
              <tr>
                <td width='200' valign='top'>
                    <?php _e("Email Address","wplivechat")?>:
                </td>
                <td>
                    <input type='text' size='50' maxlength='150' class='regular-text' readonly value='' />
                    <small>
                        <i>
                            <?php _e("Get offline messages with the ","wplivechat")?>
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=offlinemessages" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a>.
                        </i>
                    </small>
                    <br />
                </td>
            </tr>
             <tr>
                <td width='200' valign='top'>
                    <?php _e("Offline text","wplivechat")?>:
                </td>
                <td>
                    <input type='text' size='50' maxlength='50' class='regular-text' readonly value='<?php _e('Chat offline. Leave a message', 'wplivechat'); ?>' />
                    <small>
                        <i> <?php _e("Edit these text fields using the ","wplivechat")?> 
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=textfields4" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a>
                        </i>
                    </small>
                    <br />
                </td>
            </tr>
            
          </table>
      </div>
      
      
      <div id="tabs-4">
          <style>
            .wplc_theme_block img{
                border: 1px solid #CCC;
                border-radius: 5px;
                padding: 5px;
                margin: 5px;
                box-shadow: 1px 1px 1px #CCC;
            }
          </style>
          <h3><?php _e("Styling",'wplivechat')?></h3>
          <table class='form-table' width='700'>
              
              <tr>
                  <td><label for=""><?php _e('Choose a colour scheme. Only available in the', 'sola_t'); ?> <a href='http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=themes' target='_BLANK'><?php _e('Pro add-on', 'wplivechat'); ?></a></label></td>
                    <td>    
                        <div class='wplc_theme_block'>
                            <div class='wplc_theme_image' id=''>
                                <img src='<?php echo plugins_url()."/wp-live-chat-support/images/themes/theme-1.png"; ?>' title="<?php _e('Colour Scheme 1', 'wplivechat'); ?>" alt="<?php _e('Colour Scheme 1', 'wplivechat'); ?>" />
                                <img src='<?php echo plugins_url()."/wp-live-chat-support/images/themes/theme-2.png"; ?>' title="<?php _e('Colour Scheme 2', 'wplivechat'); ?>" alt="<?php _e('Colour Scheme 2', 'wplivechat'); ?>" />
                                <img src='<?php echo plugins_url()."/wp-live-chat-support/images/themes/theme-3.png"; ?>' title="<?php _e('Colour Scheme 3', 'wplivechat'); ?>" alt="<?php _e('Colour Scheme 3', 'wplivechat'); ?>" />
                                <img src='<?php echo plugins_url()."/wp-live-chat-support/images/themes/theme-4.png"; ?>' title="<?php _e('Colour Scheme 4', 'wplivechat'); ?>" alt="<?php _e('Colour Scheme 4', 'wplivechat'); ?>" />
                                <img src='<?php echo plugins_url()."/wp-live-chat-support/images/themes/theme-5.png"; ?>' title="<?php _e('Colour Scheme 5', 'wplivechat'); ?>" alt="<?php _e('Colour Scheme 5', 'wplivechat'); ?>" />
                                <img src='<?php echo plugins_url()."/wp-live-chat-support/images/themes/theme-6.png"; ?>' title="<?php _e('Colour Scheme 6', 'wplivechat'); ?>" alt="<?php _e('Colour Scheme 6', 'wplivechat'); ?>" />
                            </div>
                        </div>
                    </td>
                </tr>
                
              <tr>
                  <td width='200' valign='top'><?php _e("Chat box fill color","wplivechat")?>:</td>
                  <td>
                      <input id="wplc_settings_fill" name="wplc_settings_fill" type="text" class="color" value="<?php echo $wplc_settings_fill;?>" />
                  </td>
              </tr>
              <tr>
                  <td width='200' valign='top'><?php _e("Chat box font color","wplivechat")?>:</td>
                  <td>
                      <input id="wplc_settings_font" name="wplc_settings_font" type="text" class="color" value="<?php echo $wplc_settings_font;?>" />
                  </td>
              </tr>
              <tr style='height:30px;'><td></td><td></td></tr>
            <tr>
                <td width='200' valign='top'><?php _e("First section text","wplivechat")?>:</td>
                <td>
                    <input type='text' size='50' maxlength='50' class='regular-text' readonly value='<?php _e("Questions?","wplivechat") ?>' /> <br />
                    <input type='text' size='50' maxlength='50' class='regular-text' readonly value='<?php _e("Chat with us","wplivechat") ?>' /> <br />
                </td>
            </tr>
            <tr>
                <td width='200' valign='top'><?php _e("Second section text","wplivechat") ?>:</td>
                <td>
                    <input type='text' size='50' maxlength='50' class='regular-text' readonly value='<?php _e("Start Chat","wplivechat") ?>' /> <br />
                    <input type='text' size='50' maxlength='50' class='regular-text' readonly value='<?php _e("Connecting you to a sales person. Please be patient.", "wplivechat") ?>' /> <br />


                </td>
            </tr>
            <tr>
                <td width='200' valign='top'><?php _e("Reactivate chat section text","wplivechat")?>:</td>
                <td>
                    <input type='text' size='50' maxlength='50' class='regular-text' readonly value='<?php _e("Reactivating your previous chat...", "wplivechat") ?>' />
                    <small>
                        <i>
                            <?php _e("Edit these text fields using the ","wplivechat")?>
                            <a href="http://www.wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=textfields3" title="<?php _e("Pro Add-on","wplivechat")?>" target="_BLANK"><?php _e("Pro Add-on","wplivechat")?></a>.
                        </i>
                    </small>
                    <br />


                </td>
            </tr>
          </table>
      </div>
        <div id="tabs-5">
            <h3><?php _e("Multiple Agents", "wplivechat") ?></h3>
            <p><?php _e("Get","wplivechat") ?> <a href="http://wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=multipleAgents" target="_BLANK"><?php _e("Multiple agent support", "wplivechat") ?></a></p>
        </div>
        <div id="tabs-6">
            <style>
                .wplc_animation_block div{
                    display: inline-block;
                    width: 150px;
                    height: 150px;
                    border: 1px solid #CCC;
                    border-radius: 5px;
                    text-align: center;  
                    margin: 10px;
                }
                .wplc_animation_block i{
                    font-size: 3em;
                    line-height: 150px;
                }
                .wplc_animation_block .wplc_red{
                    color: #E31230;
                }
                .wplc_animation_block .wplc_orange{
                    color: #EB832C;
                }
                .wplc_animation_active{
                    box-shadow: 2px 2px 2px #CCC;
                }
            </style>
            <h3><?php _e("Animations", "wplivechat") ?></h3>
            <p><?php _e("Get a variety of animations in the ","wplivechat") ?> <a href="http://wp-livechat.com/purchase-pro/?utm_source=plugin&utm_medium=link&utm_campaign=animations" target="_BLANK"><?php _e("Pro add-on", "wplivechat") ?></a></p>
            <table class='form-table'>
                <tr>
                    <th><label for=""><?php _e('Choose an animation (Pro Only)', 'sola_t'); ?></label></th>
                
                    <td>    
                        <div class='wplc_animation_block'>
                            <div class='wplc_animation_image'>
                                <i class="fa fa-arrow-circle-up wplc_orange"></i>
                                <p><?php _e('Slide Up', 'wplivechat'); ?></p>
                            </div>
                            <div class='wplc_animation_image'>
                                <i class="fa fa-arrows-h wplc_red"></i>
                                <p><?php _e('Slide From The Side', 'wplivechat'); ?></p>
                            </div>
                            <div class='wplc_animation_image'>
                                <i class="fa fa-arrows-alt wplc_orange"></i>
                                <p><?php _e('Fade In', 'wplivechat'); ?></p>
                            </div>
                            <div class='wplc_animation_image'>
                                <i class="fa fa-thumb-tack wplc_red"></i>
                                <p><?php _e('No Animation', 'wplivechat'); ?></p>
                            </div>
                        </div>  
                    </td>
                </tr>
            </table>       
        </div>
    </div>
    <p class='submit'><input type='submit' name='wplc_save_settings' class='button-primary' value='<?php _e("Save Settings","wplivechat")?>' /></p>
    </form>
    
    </div>
