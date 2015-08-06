<?php
/**
 * Plugin Name: Blog Manager Light
 * Plugin URI: http://OTWthemes.com 
 * Description: Blog Manager for WordPress adds tons of blog functionality to your WordPress based website.
 * Author: OTWthemes.com
 * Version: 1.1
 * Author URI: http://themeforest.net/user/OTWthemes
 */

/**
 * Global Constants that are need for this plugin
 */
 
  // Directory Separator
	if( !defined( 'DS' ) ){
		if( defined( 'DIRECTORY_SEPARATOR' ) && DIRECTORY_SEPARATOR ){
			define( 'DS', DIRECTORY_SEPARATOR );
		}else{
			define( 'DS', '/' );
		}
	}
  // Plugin Folder Name
  if( function_exists( 'plugin_basename' ) ){
	define( 'OTW_BML_PATH', preg_replace( "/\/otw\_blog\_manager\_light\.php$/", '', plugin_basename( __FILE__ ) ) );
  }else{
	define( 'OTW_BML_PATH', 'otw-blog-manager-light' );
  }
  // Full map 
  define( 'OTW_BML_SERVER_PATH', dirname(__FILE__) );
  // Namespace for translation
  define( 'OTW_BML_TRANSLATION', 'otw_bml' );
	
	$upload_dir = wp_upload_dir();
	
	define( 'SKIN_BML_URL', $upload_dir['baseurl'].DS.'otwbm'.DS.'skins'.DS );
	define( 'SKIN_BML_PATH', $upload_dir['basedir'].DS.'otwbm'.DS.'skins'.DS );
	define( 'UPLOAD_BML_PATH', $upload_dir['basedir'].DS );
	
	$otw_bm_image_component = false;
	$otw_bm_image_object = false;
	$otw_bm_image_profile = false;
	
	//load core component functions
	@include_once( 'include/otw_components/otw_functions/otw_functions.php' );
	
	if( !function_exists( 'otw_register_component' ) ){
		wp_die( 'Please include otw components' );
	}
	
	//register image component
	otw_register_component( 'otw_image', dirname( __FILE__ ).'/include/otw_components/otw_image/', '/include/otw_components/otw_image/' );


if( !class_exists('OTWBlogManagerLight') ) {

class OTWBlogManagerLight {

  // Query Class Instance
  public $otwBMQuery = null;
  
  // CSS Class Instance
  public $otwCSS = null;

  // Tempalte Dispatcher
  public $otwDispatcher = null;

  public $fontsArray = null;

  // Validation errors array
  public $errors = null;

  // Form data on error
  public $errorData = null;

  /**
   * Initialize plugin
   */
  public function __construct() {
    
    // Create an instance of the OTWBMQuery Class
    $this->otwBMQuery = new OTWBMQuery();

    $this->otwCSS = new OTWCss();

    $this->otwDispatcher = new OTWDispatcher();

    include( 'include' . DS . 'fonts.php' );
    
    $this->fontsArray = json_decode($allFonts);

    // Add Admin Menu only if role is Admin
    if( is_admin() ) {
      
      // Save and redirect are done before any headers are loaded
      $this->saveAction();

      // Add Admin Assets
      add_action( 'admin_init', array($this, 'register_resources') );
      // Add Admin menu
      add_action( 'admin_menu', array($this, 'register_menu') );
      // Add Meta Box 
      add_action( 'add_meta_boxes', array($this, 'bm_meta_boxes'), 10, 2 );
      // Save Meta Box Data
      add_action( 'save_post', array($this, 'bm_save_meta_box') );
    }
    
    add_action('init', array($this, 'load_resources') );
    
    // Load Short Code
    add_shortcode( 'otw-bm-list', array($this, 'bm_list_shortcode') );

    // Include Widgets Functionality
    add_action( 'widgets_init', array($this, 'bm_register_widgets') );

    /**
     * Init Front End (template) functions
     */

    // Enque template JS and CSS files
    add_action( 'wp_enqueue_scripts', array($this, 'register_fe_resources') );

    // Ajax FE Actions - Load More Pagination
    add_action( 'wp_ajax_get_posts', array($this, 'otw_bm_get_posts') );
    add_action( 'wp_ajax_nopriv_get_posts', array($this, 'otw_bm_get_posts') );
    
    // Ajax FE Social Share
    add_action( 'wp_ajax_social_share', array($this, 'otw_bm_social_share') );
    add_action( 'wp_ajax_nopriv_social_share', array($this, 'otw_bm_social_share') );
  }

  /**
   * Add Menu To WP Backend
   * This menu will be available only for Admin users
   */
  public function register_menu() {

    add_menu_page( 
      __('Blog Manager Light', OTW_BML_TRANSLATION),  
      __('Blog Manager Light', OTW_BML_TRANSLATION), 
      'manage_options', 
      'otw-bml', 
      array( $this , 'bml_list' ),
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'assets'. DS .'img'. DS .'menu_icon.png' 
    );

    add_submenu_page( 
      'otw-bml', 
      __('Blog Manager Lists', OTW_BML_TRANSLATION), 
      __('Blog Lists', OTW_BML_TRANSLATION), 
      'manage_options', 
      'otw-bml', 
      array( $this , 'bml_list' )
    );

    add_submenu_page( 
      'otw-bml', 
      __('Blog Manager | Add Lists', OTW_BML_TRANSLATION), 
      __('Add Lists', OTW_BML_TRANSLATION), 
      'manage_options', 
      'otw-bml-add', 
      array( $this , 'bml_add' )
    );

    add_submenu_page( 
      'otw-bml', 
      __('Blog Manager | Options', OTW_BML_TRANSLATION), 
      __('Options', OTW_BML_TRANSLATION), 
      'manage_options', 
      'otw-bml-settings', 
      array( $this , 'bml_settings' )
    );
  }
  
/**
  * Add components
  */
public function load_resources(){
	
	global $otw_bm_image_component, $otw_bm_image_profile, $otw_bm_image_object;
	
	$otw_bm_image_component = otw_load_component( 'otw_image' );
	
	$otw_bm_image_object = otw_get_component( $otw_bm_image_component );
	
	$otw_bm_image_object->init();
	
	$img_location = wp_upload_dir();
	
	$otw_bm_image_profile = $otw_bm_image_object->add_profile( $img_location['basedir'].'/', $img_location['baseurl'].'/', 'otwbm' );

}

  /**
   * Add Styles and Scripts needed by the Admin interface
   */
  public function register_resources () {
    // Get ALL categories to be used in SELECT 2
    $categoriesSelect2  = $this->otwBMQuery->select2Categories();
    $categoriesData     = $categoriesSelect2['categories'];
    $catCount           = $categoriesSelect2['count'];

    // Get ALL tags to be used in SELECT 2
    $tagsSelect2        = $this->otwBMQuery->select2Tags();
    $tagsData           = $tagsSelect2['tags'];
    $tagCount           = $tagsSelect2['count'];

    // Get ALL users (Authors)
    $usersSelect2       = $this->otwBMQuery->select2Users();
    $usersData          = $usersSelect2['users'];
    $userCount          = $usersSelect2['count'];

    $pagesSelect2       = $this->otwBMQuery->select2Pages();
    $pagesData          = $pagesSelect2['pages'];
    $pageCount          = $pagesSelect2['count'];

    // Custom Messages that are required in JS
    // Added here because of translation
    $messages = array(
      'delete_confirm'  => __('Are you sure you want to delete ', OTW_BML_TRANSLATION),
      'modal_title'     => __('Select Images', OTW_BML_TRANSLATION),
      'modal_btn'       => __('Add Image', OTW_BML_TRANSLATION)
    );

    if( !function_exists( 'wp_enqueue_media' ) ) {
      wp_enqueue_media(); //WP 3.5 media uploader
    }
	//check the sskin folder
	$upload_dir = wp_upload_dir();
	
	if( isset( $upload_dir['basedir'] ) && is_writable( $upload_dir['basedir'] ) && !is_dir( SKIN_BML_PATH ) ){
		
		if( !is_dir( $upload_dir['basedir'].DS.'otwbm' ) ){
			mkdir( $upload_dir['basedir'].DS.'otwbm' );
		}
		if( is_dir( $upload_dir['basedir'].DS.'otwbm' ) && !is_dir( SKIN_BML_PATH ) ){
			mkdir( SKIN_BML_PATH );
		}
	}
    
    wp_register_script( 
      'otw-admin-colorpicker', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS . 'assets'.DS.'js'.DS.'plugins'.DS.'colorpicker.js', array('jquery') 
    );
    wp_register_script( 
      'otw-admin-select2', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS . 'assets'.DS.'js'.DS.'plugins'.DS.'select2.js', array('jquery') 
    );

    wp_register_script( 
      'otw-admin-variables', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS . 'assets'.DS.'js'.DS.'otw-admin-bm-variables.js'
    );
    wp_register_script( 
      'otw-admin-functions', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS . 'assets'.DS.'js'.DS.'otw-admin-bm-functions.js'
    );
    wp_register_script( 
      'otw-admin-fonts', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS . 'assets'.DS.'js'.DS.'fonts.js'
    );

    // Custom Scripts + Plugins
    wp_enqueue_script( 'otw-admin-colorpicker' );
    wp_enqueue_script( 'otw-admin-select2' );
    wp_enqueue_script( 'otw-admin-otwpreview' );
    wp_enqueue_script( 'otw-admin-fonts');
    wp_enqueue_script( 'otw-admin-functions');
    wp_enqueue_script( 'otw-admin-variables');

    // Core Scripts
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'jquery-ui-droppable' );
    wp_enqueue_script( 'jquery-ui-accordion' );
    wp_enqueue_script( 'jquery-ui-sortable' );

    wp_localize_script( 'otw-admin-functions', 'categories', json_encode( $categoriesData ) );
    wp_localize_script( 'otw-admin-functions', 'tags', json_encode( $tagsData ) );
    wp_localize_script( 'otw-admin-functions', 'users', json_encode( $usersData ) );
    wp_localize_script( 'otw-admin-functions', 'pages', json_encode( $pagesData ) );
    wp_localize_script( 'otw-admin-functions', 'messages', json_encode( $messages ) );

    wp_localize_script( 'otw-admin-functions', 'frontendURL', WP_PLUGIN_URL . DS . OTW_BML_PATH . DS . 'frontend/' );

    wp_register_style( 
      'otw-admin-color-picker', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS . 'assets'.DS.'css'.DS.'colorpicker.css' 
    );
    wp_register_style( 'otw-admin-bm-default', WP_PLUGIN_URL . DS . OTW_BML_PATH . DS . 'assets'.DS.'css'.DS.'otw-blog-list-default.css' );
    wp_register_style( 'otw-admin-bm-select2', WP_PLUGIN_URL . DS . OTW_BML_PATH . DS . 'assets'.DS.'css'.DS.'select2.css' );

    wp_enqueue_style( 'otw-admin-color-picker' );
    wp_enqueue_style( 'otw-admin-bm-default' );
    wp_enqueue_style( 'otw-admin-bm-select2' );

  }

  /**
   * Add Meta Boxes 
   */
  public function bm_meta_boxes () {
    // Add Support for POSTS
    add_meta_box(
      'otw-bm-meta-box', 
      __('OTW Blog Manager Media Item', OTW_BML_TRANSLATION), 
      array($this, 'otw_blog_manager_media_meta_box'), 
      'post', 
      'normal', 
      'default'
    );
  }

  /**
   * Add Custom HTML Meta Box on POSTS and PAGES 
   */
  public function otw_blog_manager_media_meta_box ( $post ) {

    $otw_bm_meta_data = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
    require_once( 'views'. DS .'otw_blog_manager_meta_box.php' );
  }

  /**
   * Save Meta Box Data
   * @param $post_id - int - Current POST ID beeing edited
   */
  function bm_save_meta_box ( $post_id ) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
      return;
    }

    if( !empty( $_POST ) && !empty( $_POST['otw-bm-list-media_type']) ) {


      $otw_meta_data = array(
        'media_type'      => $_POST['otw-bm-list-media_type'],
        'youtube_url'     => $_POST['otw-bm-list-youtube_url'],
        'vimeo_url'       => $_POST['otw-bm-list-vimeo_url'],
        'soundcloud_url'  => $_POST['otw-bm-list-soundcloud_url'],
        'img_url'         => $_POST['otw-bm-list-img_url'],
        'slider_url'      => $_POST['otw-bm-list-slider_url'],
        // Audio and Video - WP uploaded      
        // 'media_url'      => $_POST['otw-bm-list-media_url'],
        // 'audio_url'      => $_POST['otw-bm-list-audio_url'],
      );

      /**
       * Add Custom POST Meta Data
       * If POST is found in the DB it will just be ignored and return FALSE
       */

      add_post_meta($post_id, 'otw_bm_meta_data', $otw_meta_data, true);

      // If POST is in the DB update it
      update_post_meta($post_id, 'otw_bm_meta_data', $otw_meta_data);
    }
  }

  /**
   * OTW Blog Manager List Page
   */
  public function bml_list () {
    $action = $_GET;

    // Check if writing permissions
    $writableCssError = $this->check_writing( SKIN_BML_PATH );
    $writableError    = $this->check_writing( UPLOAD_BML_PATH );

    $otw_bm_lists = get_option( 'otw_bm_lists' );

    if( !empty( $action['action'] ) && $action['action'] === 'delete' ) {
      $list_id = $_GET['otw-bm-list-id'];
      $item = 'otw-bm-list-'.$list_id;
      
      unset( $otw_bm_lists['otw-bm-list'][ $item ] );

      update_option( 'otw_bm_lists', $otw_bm_lists );

    }
    require_once('views' . DS . 'otw_blog_manager_list.php');
  }

  /**
   * OTW Blog Manager Add / Edit Page
   */
  public function bml_add () {

    // Default Values 
    // $content and $widgets
    include( 'include' . DS . 'content.php' );

    // Edit field - used to determin if we are on an edit or add action
    $edit = false;

    // Reload $_POST data on error
    if( !empty( $this->errors ) ) {
      $content = $this->errorData;
    }

    // Edit - Load Values for current list
    if( !empty($_GET['otw-bm-list-id']) ) {
      
      $listID = (int) $_GET['otw-bm-list-id'];
      $nextID = $listID;
      $edit = true;
      $content = $this->otwBMQuery->getItemById( $listID );
    }

    // Make manipulations to the $content in order to be used in the UI
    if( !empty( $content ) ) {
      // Replace escaping \ in order to display in textarea
      $content['custom_css'] = str_replace('\\', '', $content['custom_css']);

      // Select All functionality, remove all items from the list if Select All is used
      // We use this approach in order not to show any items in the text field if select all is used
      if( !empty( $content['all_categories'] ) ) { $content['categories'] = ''; }
      if( !empty( $content['all_tags'] ) ) { $content['tags'] = ''; }
      if( !empty( $content['all_users'] ) ) { $content['users'] = ''; }

      if( !array_key_exists('select_categories' , $content ) ) { $content['select_categories'] = ''; }
      if( !array_key_exists('select_tags' , $content ) ) { $content['select_tags'] = ''; }
      if( !array_key_exists('select_users' , $content ) ) { $content['select_users'] = ''; }
    }

    require_once('views' . DS . 'otw_blog_manager_add_list.php');
  }

  /**
   * saveAction - Validate form and save + redirect
   * @return void
   */
  public function saveAction() {
    if( !empty( $_POST ) && isset($_POST['submit-otw-bm']) ){
      
      $this->errors = null;

      // Check if Blog List Name is present
      if( empty( $_POST['list_name'] ) ) {
        $this->errors['list_name'] = __('Blog List Name is Required', OTW_BML_TRANSLATION);
      }

      // Check if Blog List Template is present
      if( empty( $_POST['template'] ) || $_POST['template'] === 0 ) {
        $this->errors['template'] = __('Please select a Blog List Template', OTW_BML_TRANSLATION);
      }

      //Check Selection of content: Category OR Tag OR Author
      if( 
          ( empty( $_POST['categories'] ) && empty( $_POST['tags'] ) && empty( $_POST['users'] ) ) &&
          ( empty( $_POST['all_categories'] ) && empty( $_POST['all_tags'] ) && empty( $_POST['all_users'] ) )
        ) {
        $this->errors['content'] = __('Please select a Category or Tag or Author.', OTW_BML_TRANSLATION);
      }

      // Add dates ( created / modified ) to current post
      if( empty( $_POST['date_created'] ) && empty( $this->errors ) ) {
        $_POST['date_created'] = $_POST['date_modified'] = date('Y/m/d');
      }

      // Update modified if post is edited
      if( !empty( $_POST['id'] ) ) {
        // Inject Date Modified into $_POST
        $_POST['date_modified'] = date('Y/m/d');
      }

      /** 
       * If select All functionality is used, adjust the POST
       */
      if( !empty( $_POST['all_categories'] ) ) {
        $_POST['categories'] = $_POST['all_categories'];
      }
      if( !empty( $_POST['all_tags'] ) ) {
        $_POST['tags'] = $_POST['all_tags'];
      }
      if( !empty( $_POST['all_users'] ) ) {
        $_POST['users'] = $_POST['all_users'];
      }

      // Errors have been detected persist data
      if( !empty( $this->errors ) ) {
        $this->errorData = $_POST;
        return null;
      }

      // This is a new list get the ID
      if( empty( $_POST['edit'] ) &&  empty( $this->errors ) ) {
        $otw_bm_lists = $this->otwBMQuery->getLists();

        // This is the first list generated
        if( empty( $otw_bm_lists ) ) {
          $_POST['id'] = 1;
        } else {
          $_POST['id'] = $otw_bm_lists['otw-bm-list']['next_id'];
        }
      }

      // Assign $_POST to variable in order to fill form on error / edit
      $content = $_POST;

      /**
      * Create Custom CSS file for inline styles such as: Title, Meta Items, Excpert, Continue Reading
      */
      $customCssFile = SKIN_BML_PATH . 'otw-bm-list-'.$_POST['id'].'-custom.css';

      // Make sure all the older CSS rules are deleted in order for a fresh save
      if( file_exists( $customCssFile ) ) {
        file_put_contents( $customCssFile, '');
      }

      // Write Custom CSS
      $this->otwCSS->writeCSS( str_replace('\\', '', $_POST['custom_css']),  $customCssFile );

      $metaStyles = array(
        'font'        => (!empty($_POST['meta_font']))? $this->fontsArray[ $_POST['meta_font'] ]->text : '',
        'color'       => (!empty($_POST['meta-color']))? $_POST['meta-color'] : '',
        'size'        => (!empty($_POST['meta-font-size']))? $_POST['meta-font-size'] : '',
        'font-style'  => (!empty($_POST['meta-font-style']))? $_POST['meta-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw_blog_manager-blog-meta-wrapper'
      );

      $this->otwCSS->buildCSS( $metaStyles, $customCssFile );

      $metaLinkStyles = array(
        'font'        => (!empty($_POST['meta_font']))? $this->fontsArray[ $_POST['meta_font'] ]->text : '',
        'size'        => (!empty($_POST['meta-font-size']))? $_POST['meta-font-size'] : '',
        'font-style'  => (!empty($_POST['meta-font-style']))? $_POST['meta-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw_blog_manager-blog-meta-wrapper a'
      );

      $this->otwCSS->buildCSS( $metaLinkStyles, $customCssFile );

      $metaLabelStyles = array(
        'font'        => (!empty($_POST['meta_font']))? $this->fontsArray[ $_POST['meta_font'] ]->text : '',
        'color'       => (!empty($_POST['meta-color']))? $_POST['meta-color'] : '',
        'size'        => (!empty($_POST['meta-font-size']))? $_POST['meta-font-size'] : '',
        'font-style'  => (!empty($_POST['meta-font-style']))? $_POST['meta-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw_blog_manager-blog-meta-wrapper .head'
      );

      $this->otwCSS->buildCSS( $metaLabelStyles, $customCssFile );

      $titleNoLinkStyles = array(
        'font'        => (!empty($_POST['title_font']))? $this->fontsArray[ $_POST['title_font'] ]->text : '',
        'color'       => (!empty($_POST['title-color']))? $_POST['title-color'] : '',
        'size'        => (!empty($_POST['title-font-size']))? $_POST['title-font-size'] : '',
        'font-style'  => (!empty($_POST['title-font-style']))? $_POST['title-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw_blog_manager-blog-title'
      );

      $this->otwCSS->buildCSS( $titleNoLinkStyles, $customCssFile );

      $titleWidgetStyles = array(
        'font'        => (!empty($_POST['title_font']))? $this->fontsArray[ $_POST['title_font'] ]->text : '',
        'color'       => (!empty($_POST['title-color']))? $_POST['title-color'] : '',
        'size'        => (!empty($_POST['title-font-size']))? $_POST['title-font-size'] : '',
        'font-style'  => (!empty($_POST['title-font-style']))? $_POST['title-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw-widget-title'
      );

      $this->otwCSS->buildCSS( $titleWidgetStyles, $customCssFile );

      $titleWLinkStyles = array(
        'font'        => (!empty($_POST['title_font']))? $this->fontsArray[ $_POST['title_font'] ]->text : '',
        'color'       => (!empty($_POST['title-color']))? $_POST['title-color'] : '',
        'size'        => (!empty($_POST['title-font-size']))? $_POST['title-font-size'] : '',
        'font-style'  => (!empty($_POST['title-font-style']))? $_POST['title-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw_blog_manager-blog-title a'
      );

      $this->otwCSS->buildCSS( $titleWLinkStyles, $customCssFile );

      $excpertStyles = array(
        'font'        => (!empty($_POST['excpert_font']))? $this->fontsArray[ $_POST['excpert_font'] ]->text : '',
        'color'       => (!empty($_POST['excpert-color']))? $_POST['excpert-color'] : '',
        'size'        => (!empty($_POST['excpert-font-size']))? $_POST['excpert-font-size'] : '',
        'font-style'  => (!empty($_POST['excpert-font-style']))? $_POST['excpert-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw_blog_manager-blog-content p'
      );

      $this->otwCSS->buildCSS( $excpertStyles, $customCssFile );

      $excpertWidgetStyles = array(
        'font'        => (!empty($_POST['excpert_font']))? $this->fontsArray[ $_POST['excpert_font'] ]->text : '',
        'color'       => (!empty($_POST['excpert-color']))? $_POST['excpert-color'] : '',
        'size'        => (!empty($_POST['excpert-font-size']))? $_POST['excpert-font-size'] : '',
        'font-style'  => (!empty($_POST['excpert-font-style']))? $_POST['excpert-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw-widget-content'
      );

      $this->otwCSS->buildCSS( $excpertWidgetStyles, $customCssFile );

      $linkStyles = array(
        'font'        => (!empty($_POST['read-more_font']))? $this->fontsArray[ $_POST['read-more_font'] ]->text : '',
        'color'       => (!empty($_POST['read-more-color']))? $_POST['read-more-color'] : '',
        'size'        => (!empty($_POST['read-more-font-size']))? $_POST['read-more-font-size'] : '',
        'font-style'  => (!empty($_POST['read-more-font-style']))? $_POST['read-more-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw_blog_manager-blog-continue-reading'
      );

      $this->otwCSS->buildCSS( $linkStyles, $customCssFile );

      $titleSliderStyles = array(
        'font'        => (!empty($_POST['title_font']))? $this->fontsArray[ $_POST['title_font'] ]->text : '',
        'color'       => (!empty($_POST['title-color']))? $_POST['title-color'] : '',
        'size'        => (!empty($_POST['title-font-size']))? $_POST['title-font-size'] : '',
        'font-style'  => (!empty($_POST['title-font-style']))? $_POST['title-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw_blog_manager-caption-title a'
      );

      $this->otwCSS->buildCSS( $titleSliderStyles, $customCssFile );

      $excpertSliderStyles = array(
        'font'        => (!empty($_POST['excpert_font']))? $this->fontsArray[ $_POST['excpert_font'] ]->text : '',
        'color'       => (!empty($_POST['excpert-color']))? $_POST['excpert-color'] : '',
        'size'        => (!empty($_POST['excpert-font-size']))? $_POST['excpert-font-size'] : '',
        'font-style'  => (!empty($_POST['excpert-font-style']))? $_POST['excpert-font-style'] : '',
        'container'   => '#otw-bm-list-'.$_POST['id'].' .otw_blog_manager-caption-excpert'
      );

      $this->otwCSS->buildCSS( $excpertSliderStyles, $customCssFile );

      // Get Current Items in the DB
      $otw_bm_list = $this->otwBMQuery->getLists();

      // Create new entry 
      $otw_bm_list_data['otw-bm-list'][ 'otw-bm-list-' . $_POST['id'] ] = $_POST;


      // We setup the next_id value. This will apply to the first save only
      if( empty($otw_bm_list['otw-bm-list']['next_id']) && empty( $_POST['edit'] ) ) {
        // We assume this is the first save with ID = 1, next ID has to be 2. Count starts from 1 because of short-code
        $otw_bm_list_data['otw-bm-list']['next_id'] = 2;      
      } elseif ( empty( $_POST['edit'] ) ) {
        $otw_bm_list['otw-bm-list']['next_id'] = $otw_bm_list['otw-bm-list']['next_id'] + 1;
        $otw_bm_list_data['otw-bm-list']['next_id'] =  $otw_bm_list['otw-bm-list']['next_id'];
      }

      // Merge the 2 arrays
      if ( $otw_bm_list === false || empty( $otw_bm_list ) ) {
        $listData = $otw_bm_list_data;
      } elseif ( !empty($otw_bm_list) ) {
        // Do not remove the ['otw-bm-list'] from they array_merge. There is a strange behavior related to this
        $listData['otw-bm-list'] = array_merge( $otw_bm_list['otw-bm-list'], $otw_bm_list_data['otw-bm-list'] );
      }

      // Update
      if( empty($this->errors) ) {
        
        // Get $widget from included file
        include( 'include' . DS . 'content.php' );

        if( in_array( $_POST['template'], $widgets) ) {
          // It's a widget
          $listData['otw-bm-list'][ 'otw-bm-list-' . $_POST['id'] ]['widget'] = 1;
        } else {
          // It's NOT a Widget
          $listData['otw-bm-list'][ 'otw-bm-list-' . $_POST['id'] ]['widget'] = 0;
        }

        update_option( 'otw_bm_lists', $listData );
        
        wp_redirect('admin.php?page=otw-bml-add&action=edit&otw-bm-list-id='.$_POST['id'].'&success=true');
        exit;
        
      } // End update

    } // End if (!empty($_POST))
  }

  /**
   * OTW Blog Manager Settings Page
   */
  public function bml_settings () {
    $customCss = '';
    $cssPath = SKIN_BML_PATH . 'custom.css';

    // Check if writing permissions
    $writableCssError = $this->check_writing( SKIN_BML_PATH );
    
    // Open File for edit
    if( empty( $_POST ) && !$writableCssError  ) {
	if( file_exists( $cssPath ) ){
    		$customCss = file_get_contents( $cssPath );
        }else{
    		$customCss = '';
    	}
        
    }

    // Save File on disk and redirect.
    if( !empty( $_POST ) ) {
      $customCSS = str_replace('\\', '', $_POST['otw_css']);
      file_put_contents( $cssPath, $customCSS );

      echo "<script>window.location = 'admin.php?page=otw-bml-settings&success_css=true';</script>";
      die;
    }
    require_once('views' . DS . 'otw_blog_manager_settings.php');
  }

  /**
   * Check Writing Permissions
   */
  public function check_writing( $path ) {
    
    $writableCssError = false;
    if( !is_writable( $path ) ) {
      $writableCssError = true;
    }

    return $writableCssError;
  }


  /*****
    Front End Related Actions
   ****/

  /**
   * Load Lists on the Front End using short code
   * @param $attr - array
   */
  public function bm_list_shortcode( $attr ) {
  
    $listID = $attr['id'];

    // Get Current Items in the DB
    $otw_bm_options = $this->otwBMQuery->getItemById( $listID );
    
    if( !empty( $otw_bm_options ) ) {

      // Enqueue Custom Styles CSS
      if( file_exists(SKIN_BML_PATH .'otw-bm-list-'.$listID.'-custom.css') ) {
        wp_register_style( 'otw-bm-custom-css-'.$listID, SKIN_BML_URL.'otw-bm-list-'.$listID.'-custom.css' );
        wp_enqueue_style( 'otw-bm-custom-css-'.$listID );

      }
    
	if( !empty( $otw_bm_options['title_font'] ) ){
		$customFonts = array(
			'title'         => $otw_bm_options['title_font'],
			'meta'          => $otw_bm_options['meta_font'],
			'excpert'       => $otw_bm_options['excpert_font'],
			'continue_read' => $otw_bm_options['read-more_font']
			);
			
		$googleFonts = $this->otwCSS->getGoogleFonts( $customFonts, $this->fontsArray  );
		
		if( !empty( $googleFonts ) ) {
			$httpFonts = (!empty($_SERVER['HTTPS'])) ? "https" : "http";
			$url = $httpFonts.'://fonts.googleapis.com/css?family='.$googleFonts.'&variant=italic:bold';
			wp_enqueue_style('otw-bm-googlefonts',$url, null, null);
		}
	}
      
      // Load $templateOptions - array
      include('include' . DS . 'content.php');

      $currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

      $otw_posts_result = $this->otwBMQuery->getPosts( $otw_bm_options, $currentPage );

      return $this->otwDispatcher->generateTemplate( $otw_bm_options, $otw_posts_result, $templateOptions );

    } else {
      $errorMsg = '<p>';
      $errorMsg .= __('Woops, we have encountered an error. The List you are trying to use can not be found: ', OTW_BML_TRANSLATION);
      $errorMsg .= 'otw-bm-list-'.$attr['id'].'<br/>';
      $errorMsg .= '</p>';

      return $errorMsg;
    }
  }

  /**
   * Load Widget Class
   * Init Widget Class
   */
  public function bm_register_widgets () {
    register_widget( 'OTWBML_Widget' );
  }

  /**
   * Load Resources for FE - CSS and JS
   */
  public function register_fe_resources () {
    $uniqueHash = wp_create_nonce("otw_bm_social_share"); 
    $socialShareLink = admin_url( 'admin-ajax.php?action=social_share&nonce='. $uniqueHash );

    wp_register_script( 
      'otw-bm-flexslider', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'frontend'. DS .'js'. DS .'jquery.flexslider.min.js', 
      array( 'jquery' )
    );
    wp_register_script( 
      'otw-bm-infinitescroll', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'frontend'. DS .'js'. DS .'jquery.infinitescroll.min.js', 
      array( 'jquery' )
    );
    wp_register_script( 
      'otw-bm-pixastic', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'frontend'. DS .'js'. DS .'pixastic.custom.min.js', 
      array( 'jquery' )
    );
    wp_register_script( 
      'otw-bm-fitvid',
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'frontend'. DS .'js'. DS .'jquery.fitvids.js', 
      array( 'jquery' )
    );
    wp_register_script( 
      'otw-bm-main-script', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'frontend'. DS .'js'. DS .'script.js', 
      array( 'jquery' ), '', true
    );

    // Custom Scripts + Plugins
    wp_enqueue_script( 'otw-bm-flexslider' );
    wp_enqueue_script( 'otw-bm-infinitescroll' );
    wp_enqueue_script( 'otw-bm-pixastic' );
    wp_enqueue_script( 'otw-bm-fitvid' );
    wp_enqueue_script( 'otw-bm-main-script' );

    wp_localize_script( 'otw-bm-main-script', 'socialShareURL', $socialShareLink ); 

    wp_register_style( 
      'otw-bm-default', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'frontend'. DS .'css'. DS .'default.css' 
    );
    wp_register_style( 
      'otw-bm-font-awesome', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'frontend'. DS .'css'. DS .'font-awesome.min.css' 
    );
    wp_register_style( 
      'otw-bm-bm', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'frontend'. DS .'css'. DS .'otw-blog-manager.css' 
    );
    wp_register_style( 
      'otw-bm-grid', 
      WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'frontend'. DS .'css'. DS .'otw-grid.css' 
    );
    wp_register_style( 
      'otw-bm-custom', 
      SKIN_BML_URL.'custom.css' 
    );

    wp_enqueue_style( 'otw-bm-default' );
    wp_enqueue_style( 'otw-bm-font-awesome' );
    wp_enqueue_style( 'otw-bm-bm' );
    wp_enqueue_style( 'otw-bm-grid' );
    wp_enqueue_style( 'otw-bm-custom' );

  }

  public function otw_bm_get_posts () {
    // Load $templateOptions - array
    include('include' . DS . 'content.php');

    $otw_bm_options = $this->otwBMQuery->getItemById( $_GET['post_id'] );
    $otw_bm_results = $this->otwBMQuery->getPosts( $otw_bm_options, $_GET['page'] );
    $paginationPageNo = (int) $_GET['page'] + 1;

    if( !empty($otw_bm_results->posts) ) {
      echo $this->otwDispatcher->generateTemplate( $otw_bm_options, $otw_bm_results, $templateOptions, true, $paginationPageNo );
    } else {
      echo ' ';  
    }
    exit;
  }

  public function otw_bm_social_share () {
    include( 'social-shares.php' );

    if(isset($_POST['url']) && $_POST['url'] != '' && filter_var($_POST['url'], FILTER_VALIDATE_URL)){
      $url = $_POST['url'];
      $otw_social_shares = new otw_social_shares($url);
      
      echo $otw_social_shares->otw_get_shares();
    } else {
      echo json_encode(array('info' => 'error', 'msg' => 'URL is not valid!'));
    }
    exit;
  }

} // End OTWBlogManager Class

} // End IF Class Exists

// Required in order to have access to wp_redirect.
require_once( ABSPATH . WPINC . '/pluggable.php' );

// DB Query
require_once( 'classes' . DS . 'otw_bm_query.php' );

// Template Dispatcher
require_once( 'classes' . DS . 'otw_dispatcher.php' );

// Custom CSS
require_once( 'classes' . DS . 'otw_css.php' );

// Add Image Crop Functionality
require_once( 'classes' . DS . 'otw_image_crop.php' );

// Register Widgets
require_once( 'classes' . DS . 'otw_blog_manager_widgets.php' );

// Register VC add on
require_once( 'classes' . DS . 'otw_blog_manager_vc_addon.php' );

$otwBlogMangerPlugin = new OTWBlogManagerLight();

?>