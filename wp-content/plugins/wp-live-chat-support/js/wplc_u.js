/*
 * Cookie Status
 * 
 * 1 - complete - user has left site
 * 2 - pending - user waiting for chat to be answered by admin
 * 3 - active chat - user and admin are chatting
 * 4 - deleted
 * 5 - browsing - no data has been inputted
 * 6 - requesting chat - admin has requested a chat with user
 * 7 - timed out - visitor has timed out 
 * 8 - complete but now browsing again
 * 9 - user closed chat before starting chat
 * 10 - user minimized active chat
 * 11 - user moved on to another page (session variable is different)
 * 
 */

jQuery(document).ready(function() {        
    var wplc_session_variable = new Date().getTime();
    var wplc_cid;
    var wplc_check_hide_cookie;
    var wplc_chat_status = "";
    var wplc_cookie_name = "";
    var wplc_cookie_email = "";
    var wplc_init_chat_box_check = true;
    var wplc_cid = null;
    
    wplc_cid = jQuery.cookie('wplc_cid');
    
    if(typeof wplc_cid === 'undefined'){
        wplc_cid = null;
    } else {
        wplc_cid = jQuery.cookie('wplc_cid');
    }
    
    wplc_check_hide_cookie = jQuery.cookie('wplc_hide');
    wplc_check_minimize_cookie = jQuery.cookie('wplc_minimize');
    wplc_chat_status = jQuery.cookie('wplc_chat_status');
    wplc_cookie_name = jQuery.cookie('wplc_name');
    wplc_cookie_email = jQuery.cookie('wplc_email');
    // Always start on 5 - ajax will then return chat status if active
    jQuery.cookie('wplc_chat_status', 5, { expires: 1, path: '/' });
    wplc_chat_status = 5;
    
//    var wplc_details = 1;
//    console.log(wplc_details);

    var data = {
        action: 'wplc_call_to_server_visitor',
        security: wplc_nonce,
        cid:wplc_cid,
        wplc_name: wplc_cookie_name,
        wplc_email: wplc_cookie_email,
        status:wplc_chat_status,
        wplcsession:wplc_session_variable,
    };
    // ajax long polling function
    wplc_call_to_server_chat(data);
    if(wplc_cid !== null   && wplc_init_chat_box_check == true){
        wplc_init_chat_box(wplc_cid,wplc_chat_status);
    }
 
    var wplc_run = true;
    function wplc_call_to_server_chat(data) {        
        jQuery.ajax({
            url: wplc_ajaxurl,
            data:data,
            type:"POST",
            success: function(response) {
                
                if(response){
//                    console.log(response);
                    response = JSON.parse(response);
//                    console.log(response);
                    // set vars and cookies
                    data['wplc_name'] = response['wplc_name'];
                    data['wplc_email'] = response['wplc_email'];
                    data['action_2'] = "";
                    data['cid'] = response['cid'];
                    jQuery.cookie('wplc_cid', response['cid'], { expires: 1, path: '/' });
                    jQuery.cookie('wplc_name', response['wplc_name'], { expires: 1, path: '/' });
                    jQuery.cookie('wplc_email', response['wplc_email'], { expires: 1, path: '/' });

                    wplc_cid = jQuery.trim(response['cid']);
//                    console.log(wplc_cid);
                    wplc_chat_status = response['status'];
//                    console.log(wplc_chat_status);
//                    console.log(jQuery.cookie('wplc_chat_status'));
//                    console.log('1 setting wplc_chat_stauts to '+wplc_chat_status);
                    jQuery.cookie('wplc_chat_status', null, { path: '/' });
//                    console.log(jQuery.cookie('wplc_chat_status'));
                    jQuery.cookie('wplc_chat_status', wplc_chat_status, { expires: 1, path: '/' });
//                    console.log(jQuery.cookie('wplc_chat_status'));
                    // handle response
                    if(data['status'] == response['status']){
                        if(data['status'] == 5 && wplc_init_chat_box_check == true){ // open chat box on load
                            wplc_init_chat_box(data['cid'], data['status']);
                        } 
                        if(response['status'] == 3 && response['data'] != null){ // if active and data is returned
                            jQuery("#wplc_chatbox").append(response['data']);
                            if(response['data']){
                                var height = jQuery('#wplc_chatbox')[0].scrollHeight;
                                jQuery('#wplc_chatbox').scrollTop(height);
                                new Audio(wplc_plugin_url+'/wp-live-chat-support/ding.mp3').play()                               
                            } 
                        }
                    } else {
                        
                        data['status'] = wplc_chat_status;
                        jQuery.cookie('wplc_chat_status', wplc_chat_status, { expires: 1, path: '/' });
                        if(response['status'] == 0){ // no answer from admin
                            jQuery("#wp-live-chat-3").hide();
                            jQuery("#wp-live-chat-react").show().empty().append("<center>"+response['data']+"</center>");
                        }
                        else if(response['status'] == 8){ // chat has been ended by admin
                            var height = jQuery('#wplc_chatbox')[0].scrollHeight;
                            jQuery('#wplc_chatbox').scrollTop(height);
                            jQuery("#wp-live-chat-minimize").hide();
                            document.getElementById('wplc_chatmsg').disabled = true;
                            jQuery("#wplc_chatbox").append("<em>"+response['data']+"</em><br />");
                        }
                        else if(parseInt(response['status']) == 11){ /* use moved on to another page (perhaps in another tab so close this instance */
                            jQuery("#wp-live-chat").css({ "display" : "none" });
                            wplc_run = false;
                        }
                        else if(response['status'] == 3 || response['status'] == 10){ // re-initialize chat
                            jQuery("#wplc_cid").val(wplc_cid);
                            if(response['status'] == 3){ // only if not minimized open aswell
                                open_chat();
                                if(jQuery('#wp-live-chat').hasClass('wplc_left') === true || jQuery('#wp-live-chat').hasClass('wplc_right') === true){
                                    jQuery('#wp-live-chat').height("400px");
                                }
                            }
                            if(response['data'] != null){ // append messages to chat area
                                jQuery("#wplc_chatbox").append(response['data']);
                                if(response['data']){
                                    var height = jQuery('#wplc_chatbox')[0].scrollHeight;
                                    jQuery('#wplc_chatbox').scrollTop(height);
                                    
                                } 
                            }
                        }
                        

                        
                    }  
                }
            },
            error: function(jqXHR, exception) {
                    if (jqXHR.status == 404) {
                        console.log('Requested page not found. [404]');
			wplc_run = false;
                    } else if (jqXHR.status == 500) {
                        console.log('Internal Server Error [500].');
			wplc_run = false;
                    } else if (exception === 'parsererror') {
                        console.log('Requested JSON parse failed.');
			wplc_run = false;
                    } else if (exception === 'abort') {
                        console.log('Ajax request aborted.');
			wplc_run = false;
                    } else {
                        console.log('Uncaught Error.\n' + jqXHR.responseText);
			wplc_run = false;
                    }                    
                },
                complete: function(response){
                    if (wplc_run) { 
                        setTimeout(function() { wplc_call_to_server_chat(data); }, 1500);
                        
                    }
            },
            timeout: 120000            
        });
        
    };  
    
    function wplc_init_chat_box(cid, status){
        
        if(wplc_chat_status == 9 && wplc_check_hide_cookie == "yes"){
            
        } else {
            if(wplc_check_hide_cookie != "yes"){
                wplc_dc = setTimeout(function (){
                    jQuery("#wp-live-chat").css({ "display" : "block" });
                    if(jQuery("#wp-live-chat").attr('wplc-auto-pop-up') == true){
                        jQuery("#wp-live-chat-2").css({ "display" : "block" });
                        jQuery("#wp-live-chat-minimize").show();
                        jQuery("#wp-live-chat-close").show();
                        jQuery('#wp-live-chat').removeClass("wplc_close");
                        jQuery('#wp-live-chat').addClass("wplc_open");
                    }
                }, window.wplc_delay);
            }
        }
        wplc_init_chat_box = false;
    }


    function wplc_sound(source,volume,loop)
    {
        this.source=source;
        this.volume=volume;
        this.loop=loop;
        var son;
        this.son=son;
        this.finish=false;
        this.stop=function()
        {
            document.body.removeChild(this.son);
        }
        this.start=function()
        {
            if(this.finish)return false;
            this.son=document.createElement("embed");
            this.son.setAttribute("src",this.source);
            this.son.setAttribute("hidden","true");
            this.son.setAttribute("volume",this.volume);
            this.son.setAttribute("autostart","true");
            this.son.setAttribute("loop",this.loop);
            document.body.appendChild(this.son);
        }
        this.remove=function()
        {
            document.body.removeChild(this.son);
            this.finish=true;
        }
        this.init=function(volume,loop)
        {
            this.finish=false;
            this.volume=volume;
            this.loop=loop;
        }
    }
    
    
     
    //placeholder text fix for IE
    jQuery('#wp-live-chat [placeholder]').focus(function() {
        var input = jQuery(this);
        if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
        }
    }).blur(function() {
        var input = jQuery(this);
        if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
        }
    }).blur();
        
   
        /* minimize chat window */
        jQuery("#wp-live-chat-minimize").on("click", function() {
            jQuery('#wp-live-chat').height("");
            if(jQuery("#wp-live-chat").attr("original_pos") === "bottom_right"){
                jQuery("#wp-live-chat").css("left", "");
                jQuery("#wp-live-chat").css("bottom", "0");
                jQuery("#wp-live-chat").css("right", "100px");
            } else if(jQuery("#wp-live-chat").attr("original_pos") === "bottom_right"){
                jQuery("#wp-live-chat").css("left", "");
                jQuery("#wp-live-chat").css("bottom", "0");
                jQuery("#wp-live-chat").css("right", "100px");
            } else if(jQuery("#wp-live-chat").attr("original_pos") === "left"){
                jQuery("#wp-live-chat").css("left", "0");
                jQuery("#wp-live-chat").css("bottom", "100px");
            } else if(jQuery("#wp-live-chat").attr("original_pos") === "right"){
                jQuery("#wp-live-chat").css("left", "");
                jQuery("#wp-live-chat").css("right", "0");
                jQuery("#wp-live-chat").css("bottom", "100px");
            }
            jQuery('#wp-live-chat').addClass("wplc_close");
            jQuery('#wp-live-chat').removeClass("wplc_open");
            //jQuery("#wp-live-chat").css(jQuery("#wp-live-chat").attr("original_pos"), "100px");
            jQuery("#wp-live-chat").css("top", "");
            wplc_chat_status = jQuery.cookie('wplc_chat_status');
            jQuery("#wp-live-chat-1").show();
            jQuery("#wp-live-chat-1").css('cursor', 'pointer');
            jQuery("#wp-live-chat-2").hide();
            jQuery("#wp-live-chat-3").hide();
            jQuery("#wp-live-chat-4").hide();
            jQuery("#wp-live-chat-react").hide();
            jQuery("#wp-live-chat-minimize").hide();
            jQuery.cookie('wplc_minimize', "yes", { expires: 1, path: '/' });
            if(wplc_chat_status != 5 && wplc_chat_status != 10 && wplc_chat_status != 9 && wplc_chat_status != 8){
                var data = {
                    action: 'wplc_user_minimize_chat',
                    security: wplc_nonce,
                    cid: wplc_cid
                };
                
                jQuery.post(wplc_ajaxurl, data, function(response) {
                        //console.log(wplc_cid);
                });
            }
            
        });
         /* close chat window */
        jQuery("#wp-live-chat-close").on("click", function() {
            
            jQuery("#wp-live-chat").hide();
            jQuery("#wp-live-chat-1").hide();
            jQuery("#wp-live-chat-2").hide();
            jQuery("#wp-live-chat-3").hide();
            jQuery("#wp-live-chat-4").hide();
            jQuery("#wp-live-chat-react").hide();
            jQuery("#wp-live-chat-minimize").hide();
            jQuery.cookie('wplc_hide', wplc_hide_chat , { expires: 1, path: '/' });
            var data = {
                action: 'wplc_user_close_chat',
                security: wplc_nonce,
                cid: wplc_cid,
                status: wplc_chat_status
            };
            jQuery.post(wplc_ajaxurl, data, function(response) {
                   //console.log(response);

            });            
        });  
        //open chat window function
         
        function open_chat(){
            jQuery('#wp-live-chat').removeClass("wplc_close");
            jQuery('#wp-live-chat').addClass("wplc_open");
            //jQuery("#wp-live-chat-1").hide();
            jQuery("#wp-live-chat-react").hide();
            jQuery("#wp-live-chat-header").css('cursor', 'all-scroll');
            jQuery("#wp-live-chat-1").css('cursor', 'all-scroll');
            jQuery.cookie('wplc_hide', "", { expires: 1, path: '/' });
            jQuery("#wp-live-chat-minimize").show();
            jQuery("#wp-live-chat-close").show();
            jQuery(function() {
                jQuery( "#wp-live-chat" ).draggable({ 
                    handle: "#wp-live-chat-header",
                    drag: function( event, ui ) {
                        jQuery(this).css("right","");
                        jQuery(this).css("bottom","inherit");
                    }
                });
            });
            
            wplc_chat_status = jQuery.cookie('wplc_chat_status');
            if (wplc_chat_status == 3 || wplc_chat_status == 10) {
                jQuery("#wp-live-chat-4").show();
                jQuery("#wplc_chatmsg").focus();
                jQuery("#wp-live-chat-2").hide();
                jQuery("#wp-live-chat-3").hide();
                jQuery.cookie('wplc_minimize', "", { expires: 1, path: '/' });
                
                var data = {
                    action: 'wplc_user_maximize_chat',
                    security: wplc_nonce,
                    cid: wplc_cid
                };
                jQuery.post(wplc_ajaxurl, data, function(response) {
                    
                        //log("user maximized chat success");
                });
            }
            
            else if (wplc_chat_status == 5 || wplc_chat_status == 9 || wplc_chat_status == 8){
               
                if(jQuery("#wp-live-chat-2").is(":visible") === false && jQuery("#wp-live-chat-4").is(":visible") === false){
                    jQuery("#wp-live-chat-2").show();         
                    var wplc_visitor_name = jQuery.cookie('wplc_name');            
                    if(jQuery.cookie('wplc_email') !== "no email set" && wplc_visitor_name.indexOf("user") >= 0){
                        jQuery("#wplc_name").val(jQuery.cookie('wplc_name'));
                        jQuery("#wplc_email").val(jQuery.cookie('wplc_email'));
                    }
                }
            }
            else if (wplc_chat_status == 2){
                jQuery("#wp-live-chat-3").show();
            } 
            else if(wplc_chat_status == 1){
                jQuery("#wp-live-chat-4").show();
                jQuery("#wplc_chatbox").append("The chat has been ended by the operator.<br />");
                var height = jQuery('#wplc_chatbox')[0].scrollHeight;
                jQuery('#wplc_chatbox').scrollTop(height);
                jQuery("#wp-live-chat-minimize").hide();
                document.getElementById('wplc_chatmsg').disabled = true;
            }  
            
        }
        //opens chat when clicked on top bar
        jQuery("#wp-live-chat-1").on("click", function() {
            open_chat();
        });
        //allows for a class to open chat window now
        jQuery(".wp-live-chat-now").on("click", function() {
            open_chat();
        });
        
        
       

        jQuery("#wplc_start_chat_btn").on("click", function() {
            var wplc_name = jQuery("#wplc_name").val();
            var wplc_email = jQuery("#wplc_email").val(); 

            if (wplc_name.length <= 0) { alert("Please enter your name"); return false; }
            if (wplc_email.length <= 0) { alert("Please enter your email address"); return false; }

            if(jQuery("#wplc_email").attr('wplc_hide') !== "1"){
                var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                if (!testEmail.test(wplc_email)){
                    alert("Please Enter a Valid Email Address"); return false;
                }
            }
            jQuery("#wp-live-chat-2").hide();
            jQuery("#wp-live-chat-3").show();
            
            var date = new Date();
            date.setTime(date.getTime() + (2 * 60 * 1000));
            
            wplc_cid = jQuery.cookie('wplc_cid');
            
            if (typeof wplc_cid !== "undefined" && wplc_cid !== null) { // we've already recorded a cookie for this person
                var data = {
                        action: 'wplc_start_chat',
                        security: wplc_nonce,
                        name: wplc_name,
                        email: wplc_email,
                        cid: wplc_cid,
                        wplcsession: wplc_session_variable
                };
            } else { // no cookie recorded yet for this visitor
                var data = {
                        action: 'wplc_start_chat',
                        security: wplc_nonce,
                        name: wplc_name,
                        email: wplc_email,
                        wplcsession: wplc_session_variable
                };
            }
            //changed ajax url so wp_mail function will work and not stop plugin from alerting admin there is a pending chat
            jQuery.post(wplc_ajaxurl, data, function(response) {
                    jQuery.cookie('wplc_chat_status', 2, { expires: date, path: '/' });

                    jQuery.cookie('wplc_name', wplc_name, { path: '/' } );
                    jQuery.cookie('wplc_email', wplc_email, { path: '/' } );
                    //console.log("wplc_start_chat");
                    wplc_cid = jQuery.trim(response);
            });
        });

        

        function wplc_strip(str) {
            str=str.replace(/<br>/gi, "\n");
            str=str.replace(/<p.*>/gi, "\n");
            str=str.replace(/<a.*href="(.*?)".*>(.*?)<\/a>/gi, " $2 ($1) ");
            str=str.replace(/<(?:.|\s)*?>/g, "");
            return str;
        }


        jQuery("#wplc_chatmsg").keyup(function(event){
            if(event.keyCode === 13){
                jQuery("#wplc_send_msg").trigger("click");
            }
        });
        jQuery("#wplc_send_msg").on("click", function() {
            var wplc_cid = jQuery("#wplc_cid").val();
            var wplc_chat = wplc_strip(document.getElementById('wplc_chatmsg').value);
            
            var wplc_name = jQuery("#wplc_name").val();
            if (typeof wplc_name == "undefined" || wplc_name == null || wplc_name == "") {
                wplc_name = jQuery.cookie('wplc_name');
            }
            jQuery("#wplc_chatmsg").val('');
                        
            if(wplc_display_name == 'display'){
                jQuery("#wplc_chatbox").append("<span class='wplc-user-message'>"+wplc_gravatar_image+" <strong>"+wplc_name+"</strong>: "+wplc_chat+"</span><br /><div class='wplc-clear-float-message'></div>");
            } else {
                jQuery("#wplc_chatbox").append("<span class='wplc-user-message'>"+wplc_chat+"</span><div class='wplc-clear-float-message'></div>");
            }
            
            var height = jQuery('#wplc_chatbox')[0].scrollHeight;
            jQuery('#wplc_chatbox').scrollTop(height);

            var data = {
                    action: 'wplc_user_send_msg',
                    security: wplc_nonce,
                    cid: wplc_cid,
                    msg: wplc_chat
            };
            jQuery.post(wplc_ajaxurl, data, function(response) {
                    //console.log("wplc_user_send_msg");
            });

        });      
        
    });


