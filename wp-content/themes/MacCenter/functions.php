<?php
error_reporting(E_ALL ^ E_NOTICE);

define("PLUGIN_DIR", ABSPATH . 'wp-content/plugins/simplr-registration-form' );
include_once(PLUGIN_DIR.'/lib/form.class.php');
class SimplrExt extends SREG_Form{
        static function text($option, $vals, $class = 'wide') {
        ?>
                <div class="option-field col-lg-6 col-sm-6 col-xs-12<?php echo apply_filters($option['name'].'_error_class',''); ?>">
                        <label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
                        <input type="text" name="<?php echo $option['name']; ?>" id="<?php echo $option['name']; ?>" value="<?php echo esc_attr($vals); ?>" class="<?php echo @$class; ?> <?php echo @$class; ?>"/>
                        <?php if(isset($option['comment'])) { echo '<div class="form-comment">'.$option['comment'].'</div>'; } ?>
                </div>
        <?php
        }

}

add_shortcode('register', 'sreg_figure1');
remove_action( 'admin_notices', 'woothemes_updater_notice' );

add_filter( 'woocommerce_email_headers', 'mycustom_headers_filter_function', 10, 2);

function mycustom_headers_filter_function( $headers, $object ) {
    if ($object == 'customer_completed_order') {
        $headers .= 'BCC: Nuevo MC <consultas@mac-center.com>' . "\r\n";
    }

    return $headers;
}

//add action give it the name of our function to run
add_action( 'woocommerce_after_shop_loop_item_title', 'wcs_stock_text_shop_page', 25 );

//create our function
function wcs_stock_text_shop_page() {
    //returns an array with 2 items availability and class for CSS
    global $product;
    $availability = $product->get_availability();

    //check if availability in the array = string 'Out of Stock'
    //if so display on page.//if you want to display the 'in stock' messages as well just leave out this, == 'Out of stock'
    // if ( $availability['availability'] == 'Out of stock') {
        echo apply_filters( 'woocommerce_stock_html', '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>', $availability['availability'] );
    // }
}

//this function determines which version of the registration to call
function sreg_figure1($atts) {
        global $options;
        extract(shortcode_atts(array(
        'role' => 'subscriber',
        'from' => get_option('sreg_admin_email'),
        'message' => 'Thank you for registering',
        'notify' => get_option('sreg_email'),
        'fields' => null,
        'fb' => false,
        ), $atts));
                if($role != 'admin') {
                        $function = sreg_basic1($atts);
                } else {
                        $function = 'You should not register admin users via a public form';
                }
        return $function;
}//End Function

function sreg_basic1($atts) {

        require_once PLUGIN_DIR .'/lib/sreg.class.php';
        //Check if the user is logged in, if so he doesn't need the registration page
        if ( is_user_logged_in() AND !current_user_can('administrator') ) {
                global $user_ID;
                $first_visit = get_user_meta($user_ID, 'first_visit',true);
                if(empty($first_visit)) {

                        $message = !empty($atts['message'])?$atts['message']:"Thank you for registering.";
                        update_user_meta($user_ID,'first_visit',date('Y-m-d'));
                        echo '<div id="message" class="success"><p>'.$message.'</p></div>';
                } else {
                        echo "You are already registered for this site!!!";
                }
        } else {
                //Then check to see whether a form has been submitted, if so, I deal with it.
                global $sreg;
                if( !is_object($sreg) ) $sreg = new Sreg_Submit();
                $out = '';
                if(isset($sreg->success)) {
                        return $sreg->output;
                } elseif( isset($sreg->errors) AND is_array($sreg->errors)) {
                        foreach($sreg->errors as $mes) {
                                $out .= '<div class="simplr-message error">'.$mes .'</div>';
                        }
                } elseif(is_string($sreg->errors)) {
                        $out = '<div class="simplr-message error">'.$message .'</div>';
                }
                return $out.simplr_build_form1($_POST,$atts);

        } //Close LOGIN Conditional

} //END FUNCTION

function simplr_build_form1($data,$atts) {
        include_once(PLUGIN_DIR.'/lib/form.class.php');
        if(get_option('users_can_register') != '1') { print('Registrations have been disabled');
        } else {
        // retrieve fields and options
        $custom = new SREG_Fields();
        $soptions = get_option('simplr_reg_options');

        $fb_user = sreg_fb_connect();
        if( isset($fb_user) && is_array(@$fb_user))  {
                $fb_button = '<span="fb-window">Connected via Facebook as <fb:name useyou="false" uid="'.$fb_user['id'].'" /></span>';
                $data['username'] = $fb_user['username'];
        } elseif( isset($fb_user) && is_string($fb_user)) {
                $fb_button = $fb_user;
                $fb_user = null;
        }

        $label_email = apply_filters('simplr_label_email', __('E-mail','woocommerce') );
        $label_confirm_email = apply_filters('simplr_label_confirm_email', __('Confirmar email','woocommerce') );
        $label_username = apply_filters('simplr_label_username', __('Username','woocommerce') );
        $label_pass = apply_filters('simplr_label_password', __('Password','woocommerce'));
        $label_confirm = apply_filters('simplr_label_confirm', __('Confirm New Password','woocommerce'));

        //POST FORM
        $form = '';
        $form .= apply_filters('simplr-reg-instructions', __('', 'simplr-reg'));
        $form .=  '<div id="simplr-form">';
        if(isset($fb_button)) {
                $form .= '<div class="fb-button">'.$fb_button.'</div>';
        }

        $fields = explode(',',@$atts['fields']);
        $form .=  '<form class="col-lg-6 col-lg-offset-3" method="post" action="" id="simplr-reg">';
        $form .= '<h2>'. __('Registrarme','simpr-reg')  .'<span class="pull-right">* Información requerida</span></h2>';
        $form .= '<hr/>';
        $form .= apply_filters('simplr-reg-first-form-elem','');
        $form .= '<div >';
        
        $i = 0 ;
        foreach(@$fields as $field):
                $i++;
                if($i % 2 == 0){
                        $form .= '</div><div >';
                }
                if ( preg_match("#^\{(.*)\}#",$field, $matches) ) {
                        $form .= "<h2 class='registration'>".$matches[1]."</h2>";
                }
                $cf = @$custom->fields->custom[$field];

                $out = '';
                if($cf['key'] != '') {
                        if($fb_user != null) {
                                $key_val = (array_key_exists($cf['key'],$fb_user)) ? $fb_user[$cf['key']] : $data[$cf['key']];
                        }
                        $args = array(
                                'name'          =>$cf['key'],
                                'label'         =>$cf['label'],
                                'required'      => $cf['required']
                        );

                        ob_start();
                        //setup specific field values for date and callback
                        if(isset($data[$cf['key']])) {
                                if($cf['type'] == 'date') {
                                        $key_val = implode('-',array($data[$cf['key'].'-yr'],$data[$cf['key'].'-mo'],$data[$cf['key'].'-dy']));
                                } elseif($cf['key'] != 'user_login' AND $cf['key'] != 'user_password' AND $cf['key'] != 'user_email') {
                                        $key_val = $data[$cf['key']];
                                }
                        }

                        if($cf['type'] == 'callback') {
                                $cf['options_array'][1] = array( @$data[$cf['key']] );
                        }

                        // do field
                        if($cf['type'] != '') {
                                SimplrExt::$cf['type']($args, @esc_attr($key_val), '', $cf['options_array']);
                        }
                        //Find the checkboxes fields and rearrange as per choices
                        if($cf['type'] == 'checkbox')
                        {
                                //save this fields in different variable 
                                //let say 
                                $form_chkBox .= ob_get_contents();

                        }else{
                        
                        }
                        //------------
                        if(!($cf['type'] == 'checkbox')){
                        $form .= ob_get_contents();
                        }
                        ob_end_clean();
                }
        endforeach;
        $form .=  '</div>';
        $form = apply_filters('simplr-add-personal-fields', $form);
        //if the user has not added their own user name field lets force one
        if( !in_array('username',$fields) OR empty($custom->fields->custom['username']) ) {
                $form .=  '<div class="option-field col-lg-6 col-sm-6 col-xs-12 '.apply_filters('username_error_class','') .'">';
                $form .=  '<label for="username" class="left">' .@esc_attr($label_username ).' <span class="required">*</span></label>';
                $form .=  '<input type="text" name="username" class="right" value="'.@esc_attr($data['username']) .'" />';
                $form .=  '</div>';
        }
                $form .= '<div >';
        //only insert the email fields if the user hasn't specified them.
        if( !in_array('email',$fields) ) {
                $form .=  '<div class="option-field col-lg-6 col-sm-6 col-xs-12 email-field '.apply_filters('email_error_class','').'">';
                $form .=  '<label for="email" class="left">' .$label_email .' <span class="required">*</span></label>';
                $form .=  '<input type="text" name="email" class="right" value="'.esc_attr(@$data['email']) .'" />';
                $form .=  '</div>';
        }
                
        if( !in_array('email_confirm', $fields) ) {
                $form .=  '<div class="simplr-field col-lg-6 col-sm-6 col-xs-12 email-field '.apply_filters('email_error_class','').'">';
                $form .=  '<label for="email" class="left">' .$label_confirm_email .' <span class="required">*</span></label>';
                $form .=  '<input type="text" name="email_confirm" class="right" value="'.esc_attr(@$data['email_confirm']) .'" />';
                $form .=  '</div>';
        }
        $form .= $form_chkBox ;
        
        $form .= '</div>';
        $form = apply_filters('simplr-add-contact-fields', $form);


        if('yes' == @$atts['password'])
        {
                $form .= '<div >';
                $form .=  '<div class="simplr-field col-lg-6 col-sm-6 col-xs-12 '.apply_filters('password_error_class','').'">';
                $form .=  '<label for="password" class="left">' .$label_pass .'</label>';
                $form .=  '<input type="password" name="password" class="right" value="'.esc_attr(@$data['password']) .'"/>';
                $form .=  '</div>';

                $form .=  '<div class="option-field col-lg-6 col-sm-6 col-xs-12'.apply_filters('password_error_class','').'">';
                $form .=  '<label for="password-confirm" class="left">' .$label_confirm .'</label>';
                $form .=  '<input type="password" name="password_confirm" class="right" value="'.esc_attr(@$data['password_confirm']) .'"/>';
                $form .=  '</div>';
                $form .= '</div>';
        }

        //filter for adding profile fields
        $form = apply_filters('simplr_add_form_fields', $form);
        if( isset( $soptions->recap_on ) AND $soptions->recap_on == 'yes') {
                $form .= sreg_recaptcha_field();
        }

        //add attributes to form
        if(!empty($atts)) {
                foreach($atts as $k=>$v)
                {
                        $form .= '<input type="hidden" name="atts['.$k.']" value="'.$v.'" />';
                }
        }

        //submission button. Use filter to custommize
        $form .=  apply_filters('simplr-reg-submit', '<div class="col-lg-12 col-sm-11"><input type="submit" name="submit-reg" value="Registrarse" class="submit button btnGradient"></div>');

        //wordress nonce for security
        $nonce = wp_create_nonce('simplr_nonce');
        $form .= '<input type="hidden" name="simplr_nonce" value="' .$nonce .'" />';

        if(!empty($fb_user)) {
                $form .= '<input type="hidden" name="fbuser_id" value="'.$fb_user['id'].'" />';
        }

        $form .= '<div style="clear:both;"></div>';
        $form .=  '</form>';
        $form .=  '</div>';
        if( isset($options->fb_connect_on) AND $soptions->fb_connect_on == 'yes') {
                $form .= sreg_load_fb_script();
        }
        return $form;
        }
}





global $avia_config;


/*
 * if you run a child theme and dont want to load the default functions.php file
 * set the global var below in you childthemes function.php to true:
 *
 * example: global $avia_config; $avia_config['use_child_theme_functions_only'] = true;
 * The default functions.php file will then no longer be loaded. You need to make sure then
 * to include framework and functions that you want to use by yourself.
 *
 * This is only recommended for advanced users
 */



add_action('admin_menu','wphidenag');
function wphidenag() {
        remove_action( 'admin_notices', 'update_nag', 3 );
}

function loadMyScripts5(){
wp_enqueue_script( 'lock_fixed', get_template_directory_uri() . '/js/jquery.lockfixed.js', array(), '1.0.0', true );
wp_enqueue_script( 'slim_scroll', get_template_directory_uri() . '/js/perfect-scrollbar/jquery.slimscroll.min.js', array(), '1.0.0', true );
wp_enqueue_script( 'custom_js', get_template_directory_uri() . '/js/custom.js', array(), '1.0.0', true );
}
	add_action( 'wp_enqueue_scripts','loadMyScripts5' );
	
if(isset($avia_config['use_child_theme_functions_only'])) return;
add_theme_support('avia_conditionals_for_mega_menu');
//set builder mode to debug
add_action('avia_builder_mode', "builder_set_debug");
function builder_set_debug()
{
        return "debug";
}

/*
 * create a global var which stores the ids of all posts which are displayed on the current page. It will help us to filter duplicate posts
 */
$avia_config['posts_on_current_page'] = array();


/*
 * wpml multi site config file
 * needs to be loaded before the framework
 */

require_once( 'config-wpml/config.php' );


/*
 * These are the available color sets in your backend.
 * If more sets are added users will be able to create additional color schemes for certain areas
 *
 * The array key has to be the class name, the value is only used as tab heading on the styling page
 */


$avia_config['color_sets'] = array(
    'header_color'      => 'Logo Area',
    'main_color'        => 'Main Content',
    'alternate_color'   => 'Alternate Content',
    'footer_color'      => 'Footer',
    'socket_color'      => 'Socket'
 );



/*
 * add support for responsive mega menus
 */

add_theme_support('avia_mega_menu');


/*
 * deactivates the default mega menu and allows us to pass individual menu walkers when calling a menu
 */

add_filter('avia_mega_menu_walker', '__return_false');


/*
 * adds support for the new avia sidebar manager
 */

add_theme_support('avia_sidebar_manager');

/*
 * Filters for post formats etc
 */
//add_theme_support('avia_queryfilter');


/*
 * Register theme text domain
 */
if(!function_exists('avia_lang_setup'))
{
        add_action('after_setup_theme', 'avia_lang_setup');
        
        function avia_lang_setup()
        {
                $lang = apply_filters('ava_theme_textdomain_path', get_template_directory()  . '/lang');
                load_theme_textdomain('avia_framework', $lang);
        }
        
        avia_lang_setup();
}



##################################################################
# AVIA FRAMEWORK by Kriesi

# this include calls a file that automatically includes all
# the files within the folder framework and therefore makes
# all functions and classes available for later use

require_once( 'framework/avia_framework.php' );

##################################################################


/*
 * Register additional image thumbnail sizes
 * Those thumbnails are generated on image upload!
 *
 * If the size of an array was changed after an image was uploaded you either need to re-upload the image
 * or use the thumbnail regeneration plugin: http://wordpress.org/extend/plugins/regenerate-thumbnails/
 */

$avia_config['imgSize']['widget'] 			 	= array('width'=>36,  'height'=>36);						// small preview pics eg sidebar news
$avia_config['imgSize']['square'] 		 	    = array('width'=>180, 'height'=>180);		                 // small image for blogs
$avia_config['imgSize']['featured'] 		 	= array('width'=>1500, 'height'=>430 );						// images for fullsize pages and fullsize slider
$avia_config['imgSize']['featured_large'] 		= array('width'=>1500, 'height'=>630 );						// images for fullsize pages and fullsize slider
$avia_config['imgSize']['extra_large']                  = array('width'=>1500, 'height'=>1500 , 'crop' => false);       // images for fullscrren slider
$avia_config['imgSize']['portfolio']                    = array('width'=>495, 'height'=>400 );                                          // images for portfolio entries (2,3 column)
$avia_config['imgSize']['portfolio_small']              = array('width'=>260, 'height'=>185 );                                          // images for portfolio 4 columns
$avia_config['imgSize']['gallery']                              = array('width'=>710, 'height'=>575 );                                          // images for portfolio entries (2,3 column)
$avia_config['imgSize']['masonry']                              = array('width'=>705, 'height'=>705 , 'crop' => false);         // images for fullscreen masonry
$avia_config['imgSize']['entry_with_sidebar']   = array('width'=>710, 'height'=>270);                            // big images for blog and page entries
$avia_config['imgSize']['entry_without_sidebar']= array('width'=>1030, 'height'=>360 );                                         // images for fullsize pages and fullsize slider

//overwrite blog and fullwidth image on extra large layouts
if(avia_get_option('responsive_layout') == "responsive responsive_large")
{
        $avia_config['imgSize']['gallery']                              = array('width'=>845, 'height'=>684 );                                  // images for portfolio entries (2,3 column)
$avia_config['imgSize']['magazine']                     = array('width'=>710, 'height'=>375 );                                          // images for magazines
$avia_config['imgSize']['masonry']                              = array('width'=>705, 'height'=>705 , 'crop' => false);         // images for fullscreen masonry
        $avia_config['imgSize']['entry_with_sidebar']   = array('width'=>845, 'height'=>321);                       // big images for blog and page entries
        $avia_config['imgSize']['entry_without_sidebar']= array('width'=>1210, 'height'=>423 );                                 // images for fullsize pages and fullsize slider
}



$avia_config['selectableImgSize'] = array(
	'square' 				=> __('Square','avia_framework'),
	'featured'  			=> __('Featured Thin','avia_framework'),
	'featured_large'  		=> __('Featured Large','avia_framework'),
	'portfolio' 			=> __('Portfolio','avia_framework'),
	'gallery' 				=> __('Gallery','avia_framework'),
	'entry_with_sidebar' 	=> __('Entry with Sidebar','avia_framework'),
	'entry_without_sidebar'	=> __('Entry without Sidebar','avia_framework'),
	'extra_large' 			=> __('Fullscreen Sections/Sliders','avia_framework'),

);

avia_backend_add_thumbnail_size($avia_config);

if ( ! isset( $content_width ) ) $content_width = $avia_config['imgSize']['featured']['width'];




/*
 * register the layout classes
 *
 */

$avia_config['layout']['fullsize']              = array('content' => 'av-content-full alpha', 'sidebar' => 'hidden',              'meta' => '','entry' => '');
$avia_config['layout']['sidebar_left']  = array('content' => 'av-content-small',          'sidebar' => 'alpha' ,'meta' => 'alpha', 'entry' => '');
$avia_config['layout']['sidebar_right'] = array('content' => 'av-content-small alpha','sidebar' => 'alpha', 'meta' => 'alpha', 'entry' => 'alpha');





/*
 * These are some of the font icons used in the theme, defined by the entypo icon font. the font files are included by the new aviaBuilder
 * common icons are stored here for easy retrieval
 */

 $avia_config['font_icons'] = apply_filters('avf_default_icons', array(

    //post formats +  types
    'standard'          => array( 'font' =>'entypo-fontello', 'icon' => 'ue836'),
    'link'              => array( 'font' =>'entypo-fontello', 'icon' => 'ue822'),
    'image'             => array( 'font' =>'entypo-fontello', 'icon' => 'ue80f'),
    'audio'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue801'),
    'quote'             => array( 'font' =>'entypo-fontello', 'icon' => 'ue833'),
    'gallery'           => array( 'font' =>'entypo-fontello', 'icon' => 'ue80e'),
    'video'             => array( 'font' =>'entypo-fontello', 'icon' => 'ue80d'),
    'portfolio'         => array( 'font' =>'entypo-fontello', 'icon' => 'ue849'),
    'product'           => array( 'font' =>'entypo-fontello', 'icon' => 'ue859'),

    //social
    'behance'           => array( 'font' =>'entypo-fontello', 'icon' => 'ue915'),
	'dribbble' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8fe'),
	'facebook' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8f3'),
	'flickr' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8ed'),
	'gplus' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8f6'),
	'linkedin' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8fc'),
	'instagram' 	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue909'),
	'pinterest' 	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8f8'),
	'skype' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue90d'),
	'tumblr' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8fa'),
	'twitter' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8f1'),
	'vimeo' 		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8ef'),
	'rss' 			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue853'),
	'youtube'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue921'),
        'xing'                  => array( 'font' =>'entypo-fontello', 'icon' => 'ue923'),
        'soundcloud'    => array( 'font' =>'entypo-fontello', 'icon' => 'ue913'),
        'five_100_px'   => array( 'font' =>'entypo-fontello', 'icon' => 'ue91d'),
        'vk'                    => array( 'font' =>'entypo-fontello', 'icon' => 'ue926'),  
        'reddit'                => array( 'font' =>'entypo-fontello', 'icon' => 'ue927'),  
        'digg'                  => array( 'font' =>'entypo-fontello', 'icon' => 'ue928'),  
        'delicious'             => array( 'font' =>'entypo-fontello', 'icon' => 'ue929'),  
        'mail'                  => array( 'font' =>'entypo-fontello', 'icon' => 'ue805'),

        //woocomemrce
	'cart' 			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue859'),
	'details'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue84b'),

	//bbpress
	'supersticky'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue808'),
	'sticky'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue809'),
	'one_voice'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue83b'),
	'multi_voice'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue83c'),
	'closed'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue824'),
	'sticky_closed' => array( 'font' =>'entypo-fontello', 'icon' => 'ue808\ue824'),
	'supersticky_closed' => array( 'font' =>'entypo-fontello', 'icon' => 'ue809\ue824'),

	//navigation, slider & controls
	'play' 			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue897'),
	'pause'			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue899'),
	'next'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue879'),
    'prev'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue878'),
    'next_big'  	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue87d'),
    'prev_big'  	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue87c'),
	'close'			=> array( 'font' =>'entypo-fontello', 'icon' => 'ue814'),
	'reload'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue891'),
	'mobile_menu'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue8a5'),

	//image hover overlays
    'ov_external'	=> array( 'font' =>'entypo-fontello', 'icon' => 'ue832'),
    'ov_image'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue869'),
    'ov_video'		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue897'),


	//misc
    'search'  		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue803'),
    'info'    		=> array( 'font' =>'entypo-fontello', 'icon' => 'ue81e'),
        'clipboard'     => array( 'font' =>'entypo-fontello', 'icon' => 'ue8d1'),
        'scrolltop'     => array( 'font' =>'entypo-fontello', 'icon' => 'ue876'),
        'scrolldown'    => array( 'font' =>'entypo-fontello', 'icon' => 'ue877'),
        'bitcoin'               => array( 'font' =>'entypo-fontello', 'icon' => 'ue92a'),

));






add_theme_support( 'automatic-feed-links' );

##################################################################
# Frontend Stuff necessary for the theme:
##################################################################
/*
 * Register theme text domain
 */
if(!function_exists('avia_lang_setup'))
{
        add_action('after_setup_theme', 'avia_lang_setup');
        function avia_lang_setup()
        {
                $lang = get_template_directory()  . '/lang';
                load_theme_textdomain('avia_framework', $lang);
        }
}


/*
 * Register frontend javascripts:
 */
if(!function_exists('avia_register_frontend_scripts'))
{
	if(!is_admin()){
		add_action('wp_enqueue_scripts', 'avia_register_frontend_scripts');
	}

	function avia_register_frontend_scripts()
	{
		$template_url = get_template_directory_uri();
                $child_theme_url = get_stylesheet_directory_uri();

                //register js
                wp_enqueue_script( 'avia-compat', $template_url.'/js/avia-compat.js', array('jquery'), 1, false ); //needs to be loaded at the top to prevent bugs
                wp_enqueue_script( 'avia-default', $template_url.'/js/avia.js', array('jquery'), 1, true );
                wp_enqueue_script( 'avia-shortcodes', $template_url.'/js/shortcodes.js', array('jquery'), 1, true );
                wp_enqueue_script( 'avia-prettyPhoto',  $template_url.'/js/prettyPhoto/js/jquery.prettyPhoto.js', 'jquery', "3.1.5", true);
								 wp_deregister_script('jquery-blockui');
								 wp_register_script( 'jquery-blockui', $template_url.'/js/jquery-blockui/jquery.blockUI.js', array('jquery'), 1, true );
                wp_enqueue_script( 'jquery.blockui', $template_url.'/js/jquery-blockui/jquery.blockUI.min.js', array('jquery'), 1, true );
        // wp_dequeue_script('wc-add-to-cart-variation');
//   wp_register_script( 'wc-add-to-cart-variation',$template_url.'/js/custom_variation.js',true);

    wp_deregister_script('wc-add-to-cart-variation');
    //wp_register_script('wc-add-to-cart-variation', get_bloginfo( 'stylesheet_directory' ). '/woocommerce/assets/js/frontend/add-to-cart-variation.min.js',array( 'jquery' ), WC_VERSION, true);
                wp_register_script('wc-add-to-cart-variation', $template_url . '/woocommerce/assets/js/frontend/add-to-cart-variation.min.js', WC_VERSION, true);
                
                wp_deregister_script('woocommerce');
                wp_register_script('woocommerce', $template_url . '/woocommerce/assets/js/frontend/woocommerce.min.js', WC_VERSION, true);
                
                //wp_deregister_script('wc-checkout');
                //wp_register_script('wc-checkout', $template_url . '/woocommerce/assets/js/frontend/checkout.js', WC_VERSION, true);
               
               wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'wp-mediaelement' );


		if ( is_singular() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }


                //register styles
                wp_register_style( 'avia-style' ,  $child_theme_url."/style.css", array(),              '2', 'all' ); //register default style.css file. only include in childthemes. has no purpose in main theme
                wp_register_style( 'avia-custom',  $template_url."/css/custom.css", array(),    '2', 'all' );
                wp_enqueue_style( 'bootstrap',  $template_url."/css/bootstrap.css" );                                                                                                                                                                 
                wp_enqueue_style( 'avia-grid' ,   $template_url."/css/grid.css", array(),               '2', 'all' );
                wp_enqueue_style( 'avia-base' ,   $template_url."/css/base.css", array(),               '2', 'all' );
                wp_enqueue_style( 'avia-layout',  $template_url."/css/layout.css", array(),     '2', 'all' );
                wp_enqueue_style( 'avia-scs',     $template_url."/css/shortcodes.css", array(), '2', 'all' );
                wp_enqueue_style( 'avia-popup-css', $template_url."/js/aviapopup/magnific-popup.css", array(), '1', 'screen' );
                wp_enqueue_style( 'avia-media'  , $template_url."/js/mediaelement/skin-1/mediaelementplayer.css", array(), '1', 'screen' );
                wp_enqueue_style( 'avia-print' ,  $template_url."/css/print.css", array(), '1', 'print' );
								

                if ( is_rtl() ) {
                        wp_enqueue_style(  'avia-rtl',  $template_url."/css/rtl.css", array(), '1', 'screen' );
                }


        global $avia;
		$safe_name = avia_backend_safe_string($avia->base_data['prefix']);

        if( get_option('avia_stylesheet_exists'.$safe_name) == 'true' )
        {
            $avia_upload_dir = wp_upload_dir();
            if(is_ssl()) $avia_upload_dir['baseurl'] = str_replace("http://", "https://", $avia_upload_dir['baseurl']);

           // $avia_dyn_stylesheet_url = $avia_upload_dir['baseurl'] . '/dynamic_avia/'.$safe_name.'.css';
            $avia_dyn_stylesheet_url = $avia_upload_dir['baseurl'] . '/dynamic_avia/enfold.css';
                        $version_number = get_option('avia_stylesheet_dynamic_version'.$safe_name);
                        if(empty($version_number)) $version_number = '1';
            
            wp_enqueue_style( 'avia-dynamic', $avia_dyn_stylesheet_url, array(), $version_number, 'all' );
        }

                wp_enqueue_style( 'avia-custom');


		if($child_theme_url !=  $template_url)
		{
			wp_enqueue_style( 'avia-style');
		}

	}
}


if(!function_exists('avia_remove_default_video_styling'))
{
	if(!is_admin()){
		add_action('wp_footer', 'avia_remove_default_video_styling', 1);
	}

	function avia_remove_default_video_styling()
        {
                //remove default style for videos
                wp_dequeue_style( 'mediaelement' );
                // wp_dequeue_script( 'wp-mediaelement' );
                //wp_dequeue_style( 'wp-mediaelement' );
        }
}




/*
 * Activate native wordpress navigation menu and register a menu location
 */
if(!function_exists('avia_nav_menus'))
{
        function avia_nav_menus()
        {
                global $avia_config, $wp_customize;

                add_theme_support('nav_menus');
                
                foreach($avia_config['nav_menus'] as $key => $value)
                {
                        //wp-admin\customize.php does not support html code in the menu description - thus we need to strip it
                        $name = (!empty($value['plain']) && !empty($wp_customize)) ? $value['plain'] : $value['html'];
                        register_nav_menu($key, THEMENAME.' '.$name);
                }
        }

        $avia_config['nav_menus'] = array(      'avia' => array('html' => __('Main Menu', 'avia_framework')),
                                                                                'avia2' => array(
                                                                                                        'html' => __('Secondary Menu <br/><small>(Will be displayed if you selected a header layout that supports a submenu <a target="_blank" href="'.admin_url('?page=avia#goto_header').'">here</a>)</small>', 'avia_framework'),
                                                                                                        'plain'=> __('Secondary Menu - will be displayed if you selected a header layout that supports a submenu', 'avia_framework')),
                                                                                'avia3' => array(
                                                                                                        'html' => __('Footer Menu <br/><small>(no dropdowns)</small>', 'avia_framework'),
                                                                                                        'plain'=> __('Footer Menu (no dropdowns)', 'avia_framework'))
                                                                        );

        avia_nav_menus(); //call the function immediatly to activate
}









/*
 *  load some frontend functions in folder include:
 */

require_once( 'includes/admin/register-portfolio.php' );		// register custom post types for portfolio entries
require_once( 'includes/admin/register-widget-area.php' );		// register sidebar widgets for the sidebar and footer
require_once( 'includes/loop-comments.php' );					// necessary to display the comments properly
require_once( 'includes/helper-template-logic.php' ); 			// holds the template logic so the theme knows which tempaltes to use
require_once( 'includes/helper-social-media.php' ); 			// holds some helper functions necessary for twitter and facebook buttons
require_once( 'includes/helper-post-format.php' ); 				// holds actions and filter necessary for post formats
require_once( 'includes/helper-markup.php' ); 					// holds the markup logic (schema.org and html5)
require_once( 'includes/admin/register-plugins.php');			// register the plugins we need

if(current_theme_supports('avia_conditionals_for_mega_menu'))
{
	require_once( 'includes/helper-conditional-megamenu.php' );  // holds the walker for the responsive mega menu
}

require_once( 'includes/helper-responsive-megamenu.php' ); 		// holds the walker for the responsive mega menu




//adds the plugin initalization scripts that add styles and functions

if(!current_theme_supports('deactivate_layerslider')) require_once( 'config-layerslider/config.php' );//layerslider plugin

require_once( 'config-bbpress/config.php' );					//compatibility with  bbpress forum plugin
require_once( 'config-templatebuilder/config.php' );			//templatebuilder plugin
require_once( 'config-gravityforms/config.php' );                               //compatibility with gravityforms plugin
require_once( 'config-woocommerce/config.php' );                                //compatibility with woocommerce plugin
require_once( 'config-wordpress-seo/config.php' );                              //compatibility with Yoast WordPress SEO plugin
require_once( 'config-events-calendar/config.php' );                    //compatibility with the Events Calendar plugin


if(is_admin())
{
	require_once( 'includes/admin/helper-compat-update.php');	// include helper functions for new versions
}




/*
 *  dynamic styles for front and backend
 */
if(!function_exists('avia_custom_styles'))
{
	function avia_custom_styles()
	{
		require_once( 'includes/admin/register-dynamic-styles.php' );	// register the styles for dynamic frontend styling
		avia_prepare_dynamic_styles();
	}

	add_action('init', 'avia_custom_styles', 20);
	add_action('admin_init', 'avia_custom_styles', 20);
}




/*
 *  activate framework widgets
 */
if(!function_exists('avia_register_avia_widgets'))
{
	function avia_register_avia_widgets()
	{
		register_widget( 'avia_newsbox' );
		register_widget( 'avia_portfoliobox' );
		register_widget( 'avia_socialcount' );
                register_widget( 'avia_combo_widget' );
                register_widget( 'avia_partner_widget' );
                register_widget( 'avia_google_maps' );
                register_widget( 'avia_fb_likebox' );
                
                
        }

        avia_register_avia_widgets(); //call the function immediatly to activate
}



/*
 *  add post format options
 */
add_theme_support( 'post-formats', array('link', 'quote', 'gallery','video','image','audio' ) );



/*
 *  Remove the default shortcode function, we got new ones that are better ;)
 */
add_theme_support( 'avia-disable-default-shortcodes', true);


/*
 * compat mode for easier theme switching from one avia framework theme to another
 */
add_theme_support( 'avia_post_meta_compat');


/*
 * make sure that enfold widgets dont use the old slideshow parameter in widgets, but default post thumbnails
 */
add_theme_support('force-post-thumbnails-in-widget');





require_once( 'functions-enfold.php');



//code for adding custom fields in variation box
        //Display Fields
        add_action( 'woocommerce_product_after_variable_attributes', 'variable_fields', 10, 2 );
        //JS to add fields for new variations
        add_action( 'woocommerce_product_after_variable_attributes_js', 'variable_fields_js' );
        //Save variation fields
        add_action( 'woocommerce_process_product_meta_variable', 'save_variable_fields', 10, 1 );

        /**
        * Create new fields for variations
        *
        */
        function variable_fields( $loop, $variation_data ) {
        ?>
         <tr>
            <td>
                <?php
                  // Textarea
                  woocommerce_wp_textarea_input(
                  array(
                    'id' => '_textarea['.$loop.']',
                    'name'=>'shipping notes',
                    'label' => __( 'Shipping Notes', 'woocommerce' ),
                    'placeholder' => '',
                    'description' => __( 'Enter the custom value here.', 'woocommerce' ),
                    'value' => $variation_data['_textarea'][0],
                    )
                    );
                  // Textarea
                  woocommerce_wp_textarea_input(
                  array(
                    'id' => '_description['.$loop.']',
                    'name'=>'Description',
                    'label' => __( 'Model Description', 'woocommerce' ),
                    'placeholder' => '',
                    'description' => __( 'Enter the description here.', 'woocommerce' ),
                    'value' => $variation_data['_description'][0],
                    )
                    );

                ?>
            </td>
        </tr>
        <?php
        }




/**
* Save new fields for variations
*
*/
        function save_variable_fields( $post_id ) {
                if (isset( $_POST['variable_sku'] ) ) :
             $variable_sku = $_POST['variable_sku'];
             $variable_post_id = $_POST['variable_post_id'];
            // print_r($variable_post_id);die; // Array ( [0] => 2207 [1] => 2208 [2] => 2209 [3] => 2210 [4] => 3657 [5] => 3660 )
                                // Textarea
                                        $_textarea = $_POST['_textarea'];
                                        $_description = $_POST['_description'];
                                        for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
                                                        $variation_id = (int) $variable_post_id[$i];
                                                if ( isset( $_textarea[$i] ) ) {
                                                update_post_meta( $variation_id, '_textarea', stripslashes( $_textarea[$i] ) );
                                                }
                                                if ( isset( $_textarea[$i] ) ) {
                                                update_post_meta( $variation_id, '_description', stripslashes( $_description[$i] ) );
                                                }

                                        endfor;
endif;

}


/* add symbol for colombiam peso */
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol( $currency_symbol, $currency ) {
switch( $currency ) {
case 'COP': $currency_symbol = '$'; break;
}
return $currency_symbol;
}

//svg image support
function cc_mime_types( $mimes ){
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

//display svg images on admin side
function custom_admin_head() {
  $css = '';

  $css = 'td.media-icon img[src$=".svg"] { width: 100% !important; height: auto !important; }';

  echo '<style type="text/css">'.$css.'</style>';
}

add_filter( 'woocommerce_available_variation', 'fetch_custom_product_meta', 10, 3);
//This will be used to pull custom fields which we have added for that product
function fetch_custom_product_meta( $data, $product, $variation){
        $data['shipping_notes'] = get_post_meta($variation->variation_id,'_textarea',true); // This will be shipping details
        $data['description'] = get_post_meta($variation->variation_id,'_description',true) ; // This will be model description
        if ($data['price_html'] == '') {
                $data['price_html'] = '<span class="price">' . $variation->get_price_html() . '</span>';
        }
        return $data ;
}


add_filter( 'wp_nav_menu_items', 'my_nav_menu_profile_link',10,2);

function my_nav_menu_profile_link($menu, $args) {

                        if (!is_user_logged_in()){
                                                        return $menu;
                }

                else if($args->theme_location=='avia'){

                                                                 $logout_url= home_url();
                                                                                                //  $items .= '<li><a href="'. wp_logout() .'">Click Here (Log Out)</a></li>';
                                                                 $current_user = wp_get_current_user();
                                                                 $user_name =$current_user->user_firstname;
                                                                 $user_name =SUBSTR($user_name,0,15);
                                                           $title="Hola ".$user_name.".";

                                                           $items.= '<span class="adminset">';
                                                           $items .= '<span class="nameset">'.$title.'</span> ';
                                                     $items .= '<span class="linkcolor"><a href="'.wp_logout_url($logout_url).'">'.__('¿no eres ').$user_name.__('? ( Salir )').'</a></span></span>';
                                                                                                        return $menu.$items;
                        }
                        else
                        {
                                                                return $menu;
                        }
}

add_action('wp_logout','go_home');
function go_home(){
  $logout_url= home_url();
  wp_redirect($logout_url);
  exit();
}


/*
function custom_override_checkout_fields( $fields ) {
  unset($fields['billing']['billing_address_2']);
  unset($fields['billing']['billing_email']);
  unset($fields['billing']['billing_state']);
  unset($fields['shipping']['billing_state']);
  unset($fields['shipping']['billing_address_2']);
  unset($fields['shipping']['billing_email']);
  $fields['billing']['billing_address_1']['placeholder'] = 'My new placeholder';

   $fields['billing']['billing_first_name'] = array(
        'label'     => __('Name', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-quarter')
     );

     $fields['billing']['billing_last_name'] = array(
        'label'     => __('Last Name', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-quarter')
     );

     $fields['billing']['billing_email'] = array(
        'label'     => __('Email', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-quarter')
     );

     $fields['billing']['billing_phone'] = array(
        'label'     => __('Telephone', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-quarter', 'form-row-quarter-last ')
     );

     $fields['billing']['billing_mobile_phone'] = array(
        'label'     => __('Mobile', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-quarter')
     );

     $fields['billing']['billing_nit'] = array(
        'label'     => __('N.I.T', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => false,
                                'class'     => array('form-row-quarter')
     );

     $fields['billing']['billing_company'] = array(
        'label'     => __('Company Name', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => false,
                                'class'     => array('form-row-half', 'form-row-half-last')
     );

     $fields['billing']['billing_country'] = array(
                                'type'     => 'country',
        'label'     => __('Country', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-half', 'address-field', 'update_totals_on_change')
     );

     $fields['billing']['billing_state'] = array(
                                'type'     => 'state',
        'label'     => __('State', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-quarter', 'address-field'),
                                'validate'    => array( 'state' ),
                                'clear'    => false
     );

     $fields['billing']['billing_city'] = array(
        'label'     => __('City', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-quarter', 'address-field'),
                                'clear'    => false
     );

     $fields['billing']['billing_address_1'] = array(
        'label'     => __('Address', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-half', 'address-field')
     );

     $fields['billing']['billing_postcode'] = array(
        'label'     => __('Zip Code', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-quarter', 'address-field'),
                                'validate'    => array( 'postcode' ),
                                'clear'    => false
     );

  return $fields;  
} 
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' ); */


add_filter( 'woocommerce_billing_fields' , 'custom_override_billing_fields' );
add_filter( 'woocommerce_shipping_fields' , 'custom_override_shipping_fields' );

function getstate(){
global $wpdb;
$result = $wpdb->get_results ( "SELECT distinct state_name FROM wp_states order by state_name" );
 foreach($result as $val){
   $states[$val->state_name] = $val->state_name ;
 }
 return $states ;
}

function city_ajax_shipping() {
	global $wpdb;
	$c=$_POST['state'];
	$sql = "select distinct c.city from wp_states s , wp_cities c where c.state_id = s.state_code
					and state_name = '".$c."' order by city";
 $cities = $wpdb->get_results ($sql);
 //$cities = array_reverse($cities);

 foreach($cities as $val){
   $city[$val->city]=$val->city;
	}
	echo json_encode(array('cities' => $city));
	wp_die();
	//echo '<option value="-1" selected="selected">Selected.</option>'.$option;
  //return json_encode($city) ;
}
add_action('wp_ajax_cities_ajax_call', 'city_ajax_shipping');
add_action('wp_ajax_nopriv_cities_ajax_call', 'city_ajax_shipping');



function custom_override_billing_fields( $fields ) {
  
  unset($fields['billing_address_2']);
  unset($fields['billing_email']);
  //unset($fields['billing_phone']);
 // unset($fields['billing_state']);
  unset($fields['billing']);
 


  
   $fields['billing_first_name'] = array(
      
        'label'     => __('Nombre', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-first',' col-lg-4')
     );

     $fields['billing_last_name'] = array(
        'label'     => __('Apellido', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-last',' col-lg-4')
     );
      $fields['billing_Cédula'] = array(
        'label'     => __('Cédula', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-last',' col-lg-4')
     );

         $fields['billing_country'] = array(
         'type' => 'country',
        'label'     => __('Country', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-first', 'address-field', 'update_totals_on_change',' col-lg-4')
     ); 
    
				$fields['billing_state'] = array(
                               'label'     => __('Departamento', 'woocommerce'),
                                'placeholder'   => ('select state'),
                                'required'  => true,
                                'type' => 'select',
                                'class'  => array('form-row-last', 'address-field','update_totals_on_change',' col-lg-4'),
                                'options' => getstate(),
     );
			$fields['billing_states'] = array(
                               'label'     => __('Departamento', 'woocommerce'),
                                'placeholder'   => ('select state'),
                                'required'  => true,
                                'type' => 'select',
                                'class'  => array('form-row-last', 'address-field','update_totals_on_change',' col-lg-4'),
                                'options' => getstate(),
     );

      $fields['billing_city'] = array(
        'label'     => __('Ciudad/Localidad', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'type' => 'select',
                                'class'     => array( 'form-row-last','address-field',' col-lg-4'),
                                'clear'    => false,
                                'options' => array(
                                 'select'=>__('select','woocomemrce'),
                                )

     );   
/*//       $fields['billing_company'] = array(
        'label'     => __('Departamento', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-last',' col-lg-4')
     );
   */  
       $fields['billing_postcode'] = array(
        'label'     => __('Barrio *', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-first', 'address-field',' col-lg-4'),
                                'validate'    => array( 'postcode' ),
                                'clear'    => false
     );
     
      $fields['billing_address_1'] = array(
        'label'     => __('Dirección Completa', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-last', 'address-field',' col-lg-4')
     );
     
      $fields['billing_phone'] = array(
        'label'     => __('Celular', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-last',' col-lg-4')
     );
//      $fields['billing_email'] = array(
//         'label'     => __('E-mail *', 'woocommerce'),
//         'placeholder'   => (''),
//                                 'required'  => true,
//                                 'class'     => array('form-row-last','address-field',' col-lg-4')
//      );
  //print_r($fields);
  return $fields;
}

        
function custom_override_shipping_fields( $fields ) {
  unset($fields['shipping_address_2']);
  unset($fields['shipping_email']);
 // unset($fields['shipping_phone']);
 // unset($fields['shipping_state']);
  unset($fields['shipping']);

   $fields['shipping_first_name'] = array(
        'label'     => __('Nombre', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-first',' col-lg-4','col-xs-12')
     );

     $fields['shipping_last_name'] = array(
        'label'     => __('Apellido', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-last',' col-lg-4')
     );
      $fields['shipping_Cédula'] = array(
        'label'     => __('Cédula', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-last',' col-lg-4')
     );

         $fields['shipping_country'] = array(
         'type' => 'country',
        'label'     => __('Country', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-first', 'address-field', 'update_totals_on_change',' col-lg-4')
     );
//          $fields['shipping_state'] = array(
//         'label'     => __('Departamento', 'woocommerce'),
//         'placeholder'   => (''),
//                                 'required'  => true,
//                                 'type' => 'select',
//                                 'class'     => array('form-row-last', 'address-field', 'update_totals_on_change',' col-lg-4'),
//                                  'options'   => getstate(),
//      );

    $fields['shipping_state'] = array(
        'label'     => __('Departamento', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                 'type' => 'select',
                                 'class'  => array('form-row-last', 'address-field','update_totals_on_change',' col-lg-4'),
                                 'options' => getstate(),
     );   
     $fields['shipping_states'] = array(
        'label'     => __('Departamento', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                 'type' => 'select',
                                 'class'  => array('form-row-last', 'address-field','update_totals_on_change',' col-lg-4'),
                                 'options' => getstate(),
     ); 

      $fields['shipping_city'] = array(
        'label'     => __('Ciudad/Localidad', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                 'type' => 'select',
                                'class'     => array('form-row-last', 'address-field',' col-lg-4'),
                                'clear'    => false,
                                 'options'     => array(
																									'select' => __('select', 'woocommerce' ),
																									)

     );
                   

//        $fields['shipping_postcode'] = array(
//         'label'     => __('Código postal/Zip *', 'woocommerce'),
//         'placeholder'   => (''),
//                                 'required'  => true,
//                                 'class'     => array('form-row-first', '',' col-lg-4'),
//                                 'validate'    => array( 'postcode' ),
//                                 'clear'    => false
// 			);

      $fields['shipping_address_1'] = array(
        'label'     => __('Dirección Completa', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-first', 'address-field',' col-lg-4')
     );

      $fields['shipping_phone'] = array(
        'label'     => __('Celular', 'woocommerce'),
        'placeholder'   => (''),
                                'required'  => true,
                                'class'     => array('form-row-last',' col-lg-4')
     );

  return $fields;
}


//Reordering of woocommerce billing fields

//add_filter( 'woocommerce_shipping_fields','reorder_woocommerce_fields');
add_filter( 'woocommerce_billing_fields','reorder_woocommerce_fields');

function reorder_woocommerce_fields($fields) {

        $fields2['billing_first_name'] = $fields['billing_first_name'];
        $fields2['billing_last_name'] = $fields['billing_last_name'];
        $fields2['billing_Cédula'] = $fields['billing_Cédula'];
        $fields2['billing_country'] = $fields['billing_country'];
        $fields2['billing_states'] = $fields['billing_states'];
       // $fields2['billing_company'] = $fields['billing_company'];
        $fields2['billing_city'] = $fields['billing_city'];
         $fields2['billing_state'] = $fields['billing_state'];
        $fields2['billing_postcode'] = $fields['billing_postcode'];
        $fields2['billing_address_1'] = $fields['billing_address_1'];
        $fields2['billing_phone'] = $fields['billing_phone'];
        $fields2['billing_email'] = array(
            'label'     => __('E-mail *', 'woocommerce'),
            'placeholder'   => (''),
            'required'  => false,
            'class'     => array('form-row-first')
         );
      

//      $fields2['billing']['billing_first_name'] = $fields['billing']['billing_first_name'];
//         $fields2['billing']['billing_last_name'] = $fields['billing']['billing_last_name'];
//         $fields2['billing']['billing_phone'] = $fields['billing']['billing_phone'];
//         $fields2['billing']['billing_mobile_phone'] = $fields['billing']['billing_mobile_phone'];
//         $fields2['billing']['billing_phone'] = $fields['billing']['billing_phone'];
//         $fields2['billing']['billing_nit'] = $fields['billing']['billing_nit'];
//         $fields2['billing']['billing_company'] = $fields['billing']['billing_company'];       
//         $fields2['billing']['billing_city'] = $fields['billing']['billing_city'];
//         $fields2['billing']['billing_state'] = $fields['billing']['billing_state'];         
//         $fields2['shipping'] = $fields['shipping'];
//         $fields2['account'] = $fields['account'];
//         $fields2['order'] = $fields['order'];
//               $fields2['billing']['billing_phone']['label'] = 'Teléfono';
//               $fields2['billing']['billing_mobile_phone']['label'] = 'Celular';  

        return $fields2; 
}

add_filter("woocommerce_shipping_fields", "order_fields");

function order_fields($fields) {

        $fields2['shipping_first_name'] = $fields['shipping_first_name'];
        $fields2['shipping_last_name'] = $fields['shipping_last_name'];
        $fields2['shipping_Cédula'] = $fields['shipping_Cédula'];
        $fields2['shipping_country'] = $fields['shipping_country'];
        $fields2['shipping_state'] = $fields['shipping_state'];
        $fields2['shipping_states'] = $fields['shipping_states'];
        //$fields2['shipping_company'] = $fields['shipping_company'];
        $fields2['shipping_city'] = $fields['shipping_city'];
        //$fields2['shipping_postcode'] = $fields['shipping_postcode'];
        $fields2['shipping_address_1'] = $fields['shipping_address_1'];
        $fields2['shipping_phone'] = $fields['shipping_phone'];
    return $fields2;

}
//echo '<div "><a></a></div>'
// if()
// $checked = $checkout->get_value( 'my_checkbox1' ) ? $checkout->get_value( 'my_checkbox1' ) : 1;
// 
// woocommerce_form_field( 'my_checkbox1', array( 
//   'type' => 'checkbox', 
//   'class' => array('input-checkbox'), 
//   'label' => __('Standard Shipping (2–7 Days, FREE!) <span>Most items are shipped FREE OF CHARGE within Thailand.</span>'), 
//   'required' => false,
// ), $checked );
		
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
 
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['billing_Cédula'] ) ) {
        update_post_meta( $order_id, 'billing_Cédula', sanitize_text_field( $_POST['billing_Cédula'] ) );
    }
    if ( ! empty( $_POST['shipping_Cédula'] ) ) {
        update_post_meta( $order_id, 'shipping_Cédula', sanitize_text_field( $_POST['shipping_Cédula'] ) );
    }
    if ( ! empty( $_POST['shipping_phone'] ) ) {
        update_post_meta( $order_id, 'shipping_phone', sanitize_text_field( $_POST['shipping_phone'] ) );
    }
}

 add_action('woocommerce_admin_order_data_after_billing_address', 'my_custom_billing_fields_display_admin_order_meta', 10, 1);
  function my_custom_billing_fields_display_admin_order_meta($order) {
  echo '<p><strong>' . __('Billing Cédula') . ':</strong><br> ' . get_post_meta($order->id, '_billing_Cédula', true) . '</p>';
  }

 add_action('woocommerce_admin_order_data_after_shipping_address', 'my_custom_shipping_fields_display_admin_order_meta', 10, 1);
  function my_custom_shipping_fields_display_admin_order_meta($order) {
echo '<p><strong>' . __('Shipping Cédula') . ':</strong><br> ' . get_post_meta($order->id, '_shipping_Cédula', true) . '</p>';
echo '<p><strong>' . __('Shipping Phone') . ':</strong><br> ' . get_post_meta($order->id, '_shipping_phone', true) . '</p>';
}

function wc_cart_totals_coupon( $coupon ) {
	if ( is_string( $coupon ) ) {
		$coupon = new WC_Coupon( $coupon );
    }

	$value  = array();

	if ( $amount = WC()->cart->get_coupon_discount_amount( $coupon->code, WC()->cart->display_cart_ex_tax ) ) {
		$discount_html = '<span class="dash">-</span>' . wc_price( $amount );
	} else {
		$discount_html = '';
	}

	$value[] = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_html, $coupon );

//  	if ( $coupon->enable_free_shipping() ) {
//  		$value[] = __( 'Free shipping coupon', 'woocommerce' );
//     }

    // get rid of empty array elements
    $value = array_filter( $value );
	$value = implode( ', ', $value ); //. ' <a href="' . esc_url( add_query_arg( 'remove_coupon', urlencode( $coupon->code ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? WC()->cart->get_checkout_url() : WC()->cart->get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->code ) . '">' . __( '[Remove]', 'woocommerce' ) . '</a>';

	echo apply_filters( 'woocommerce_cart_totals_coupon', $value, $coupon );
}

//hides the shipping label on cart and checkout page
add_filter( 'woocommerce_cart_shipping_method_full_label', 'wc_custom_shipping_labels', 10, 2 );
function wc_custom_shipping_labels( $label, $method ) {
    if ( $method->cost > 0 ) {
        if ( WC()->cart->tax_display_cart == 'excl' ) {
            $label = wc_price( $method->cost );
            if ( $method->get_shipping_tax() > 0 && WC()->cart->prices_include_tax ) {
                $label .= ' <small>' . WC()->countries->ex_tax_or_vat() . '</small>';
            }
        } else {
            $label = wc_price( $method->cost + $method->get_shipping_tax() );
            if ( $method->get_shipping_tax() > 0 && ! WC()->cart->prices_include_tax ) {
                $label = ' <small>' . WC()->countries->inc_tax_or_vat() . '</small>';
            }
        }
    }

    return $label;
}

//checkout country field
add_filter( 'woocommerce_form_field_country', 'wc_custom_field_country', 10, 4 );
function wc_custom_field_country( $field, $key, $args, $value ) {
    // Custom attribute handling
    $custom_attributes = array();
    if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
        foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
            $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
        }
    }

    $countries = $key == 'shipping_country' ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();
    if ( sizeof( $countries ) == 1 ) {
        $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">';
        if ( $args['label'] ) {
            $field .= '<label class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label']  . '</label>';
        }
                    //$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';
        $field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" placeholder="' . esc_attr( $args['placeholder'] ) . '" '.$args['maxlength'].' value="' . current( array_values( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' readonly />';
        $field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys($countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" />';
        if ( $args['description'] ) {
            $field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
        }
        $field .= '</p>' . $after;
    } else {
        $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $args['id'] ) . '_field">'
        . '<label for="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required  . '</label>'
        . '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" ' . implode( ' ', $custom_attributes ) . '>'
        . '<option value="">'.__( 'Select a country&hellip;', 'woocommerce' ) .'</option>';
        foreach ( $countries as $ckey => $cvalue ) {
            $field .= '<option value="' . esc_attr( $ckey ) . '" '.selected( $value, $ckey, false ) .'>'.__( $cvalue, 'woocommerce' ) .'</option>';
        }
        $field .= '</select>';
        $field .= '<noscript><input type="submit" name="woocommerce_checkout_update_totals" value="' . __( 'Update country', 'woocommerce' ) . '" /></noscript>';
        if ( $args['description'] ) {
            $field .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';
        }
        $field .= '</p>' . $after;
    }

    return $field;
}