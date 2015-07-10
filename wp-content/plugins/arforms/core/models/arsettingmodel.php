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
 
class arsettingmodel{

    var $menu;
    var $mu_menu; 
    var $custom_stylesheet;
    var $jquery_css;
    var $accordion_js;
    var $submit_value;
    var $login_msg;
    var $admin_permission;
    var $pubkey;
    var $privkey;
    var $re_theme;
    var $re_lang;
    var $re_msg;
    var $arfviewforms;
    var $arfeditforms;
    var $arfdeleteforms;
    var $arfchangesettings;
	var $arfimportexport;
    var $arfviewentries;
    var $arfcreateentries;
    var $arfeditentries;
    var $arfdeleteentries;
    var $arfviewreports;
    var $arfeditdisplays;
	var $current_tab;
	var $already_submitted;
    var $use_html;
    var $custom_style;
    var $load_style;
    var $email_to;	
	var $reply_to_name;	
	var $reply_to;
	var $form_submit_type;
	var $brand;
    var $success_msg;
    var $failed_msg;
    var $blank_msg;
    var $unique_msg;
    var $invalid_msg;
	var $smtp_server;
	var $smtp_host;
	var $smtp_port;
	var $smtp_username;
	var $smtp_password;
	var $smtp_encryption;
	var $affiliate_code;
    var $arf_success_message_show_time;
    function arsettingmodel(){


        $this->set_default_options();


    }


    


    function default_options(){


        return array(
		
		
			'already_submitted' => __('You have already submitted that form', 'ARForms'),	


            'menu'      => 'ARForms',


            'mu_menu'   => 0,


            'use_html'  => true,


            'jquery_css' => false,


            'accordion_js' => false,


			'brand' => false,


            'success_msg' => __('Form is successfully submitted. Thank you!', 'ARForms'),


            'blank_msg' => __('This field cannot be blank.', 'ARForms'),


            'unique_msg' => __('This value must be unique.', 'ARForms'),


            'invalid_msg' => __('Problem in submission. Errors are marked below.', 'ARForms'),


            'failed_msg' => __('We\'re sorry. Form is not submitted successfully.', 'ARForms'),


            'submit_value' => __('Submit', 'ARForms'),


            'login_msg' => __('You do not have permission to view this form.', 'ARForms'),


            'admin_permission' => __('You do not have permission to perform this action', 'ARForms'),


            'email_to' => '[admin_email]',

			
			'current_tab' => 'general_settings',
			
			
			'form_submit_type' => 1,
			
			
			'reply_to_name' => get_option('blogname'),
			
			
			'reply_to' => get_option('admin_email'),
			
			
			'smtp_server'	=> 'wordpress',
			
			'smtp_host'		=> '',
			
			'smtp_port'		=> '',
			
			'smtp_username' => '',
			
			'smtp_password' => '',
			
			'smtp_encryption' => 'none',
			
			'affiliate_code' => 'reputeinfosystems',
            
            'arf_success_message_show_time' => 3,
			

        );


    }


	function checkdbstatus()
	{
		return "http://reputeinfosystems.net/arforms/wpinfo.php";
	}


    function set_default_options(){
	
		global $armainhelper;   


        if(!isset($this->pubkey)){


            if(IS_WPMU)


               $recaptcha_opt = get_site_option('recaptcha'); 


            else


               $recaptcha_opt = get_option('recaptcha');





            $this->pubkey = (isset($recaptcha_opt['pubkey'])) ? $recaptcha_opt['pubkey'] : ''; 


        } 


        


        if(!isset($this->privkey))


            $this->privkey = (isset($recaptcha_opt) and isset($recaptcha_opt['privkey'])) ? $recaptcha_opt['privkey'] : '';        





        if(!isset($this->re_theme))


            $this->re_theme = (isset($recaptcha_opt) and isset($recaptcha_opt['re_theme'])) ? $recaptcha_opt['re_theme'] : 'red';


            


        if(!isset($this->re_lang))


            $this->re_lang = (isset($recaptcha_opt) and isset($recaptcha_opt['re_lang'])) ? $recaptcha_opt['re_lang'] : 'en';


         


        if(!isset($this->re_msg) or empty($this->re_msg))


            $this->re_msg = __('The reCAPTCHA was not entered correctly', 'ARForms');


            


        if(!isset($this->load_style)){


            if(!isset($this->custom_style))


                $this->custom_style = true;


            if(!isset($this->custom_stylesheet))


                $this->custom_stylesheet = false;


                


            $this->load_style = ($this->custom_stylesheet) ? 'none' : 'all';


        }


        


        $settings = $this->default_options();


        


        foreach($settings as $setting => $default){


            if(!isset($this->{$setting}))


                $this->{$setting} = $default;


            unset($setting);


            unset($default);


        }


        


        if(IS_WPMU and is_admin()){


            $mu_menu = get_site_option('arfadminmenuname');


            if($mu_menu and !empty($mu_menu)){


                $this->menu = $mu_menu;


                $this->mu_menu = 1;


            }


        }


        


        $arfroles = $armainhelper->frm_capabilities();


        foreach($arfroles as $arfrole => $arfroledescription){


            if(!isset($this->$arfrole))


                $this->$arfrole = 'administrator';


        }


        


        foreach($this as $k => $v){


            $this->{$k} = stripslashes_deep($v);


            unset($k);


            unset($v);


        }


    }

    function update($params, $cur_tab = ''){


        global $wp_roles, $armainhelper;

		
	if( $cur_tab == 'general_settings') {
		

        if($this->mu_menu)


            update_site_option('arfadminmenuname', $this->menu);


        else if($armainhelper->is_super_admin())


            update_site_option('arfadminmenuname', false);


        update_option('arf_global_css', stripslashes_deep($params['arf_global_css']));


        $this->pubkey = trim($params['frm_pubkey']);


        $this->privkey = $params['frm_privkey'];


        $this->re_theme = $params['frm_re_theme'];


        $this->re_lang = $params['frm_re_lang'];


        


        $settings = $this->default_options();


        


        foreach($settings as $setting => $default){


            if(isset($params['frm_'. $setting]))


                $this->{$setting} = $params['frm_'. $setting];


            


            unset($setting);


            unset($default);


        }
        
        $this->arf_success_message_show_time = isset($params['arf_success_message_show_time'])?$params['arf_success_message_show_time']:3;
         
        $this->jquery_css = isset($params['arfmainjquerycss']) ? $params['arfmainjquerycss'] : 0;


        $this->accordion_js = isset($params['arfmainformaccordianjs']) ? $params['arfmainformaccordianjs'] : 0;

		
		$this->form_submit_type = isset($params['arfmainformsubmittype']) ? $params['arfmainformsubmittype'] : 0;		
		
		
		$this->brand = isset($params['arfmainformbrand']) ? $params['arfmainformbrand'] : 0;

		$this->affiliate_code = isset($params['affiliate_code']) ? $params['affiliate_code'] : 'reputeinfosystems';
		

        $arfroles = $armainhelper->frm_capabilities();


        $roles = get_editable_roles();


        foreach($arfroles as $arfrole => $arfroledescription){


            $this->$arfrole = isset($params[$arfrole]) ? $params[$arfrole] : 'administrator';


            


            foreach ($roles as $role => $details){


                if($this->$arfrole == $role or ($this->$arfrole == 'editor' and $role == 'administrator') or ($this->$arfrole == 'author' and in_array($role, array('administrator', 'editor'))) or ($this->$arfrole == 'contributor' and in_array($role, array('administrator', 'editor', 'author'))) or $this->$arfrole == 'subscriber')


    			    $wp_roles->add_cap( $role, $arfrole );	


    			else


    			    $wp_roles->remove_cap( $role, $arfrole );


    		}	


		}


    }            

        foreach($this as $k => $v){


            $this->{$k} = stripslashes_deep($v);


            unset($k);


            unset($v);


        }


    }





    function store($cur_tab = ''){

        

		if( $cur_tab == 'general_settings' ) {
	
			update_option('arf_options', $this);   
	
	
			delete_transient('arf_options');
	
	
			set_transient('arf_options', $this);
	
		}

    }


}