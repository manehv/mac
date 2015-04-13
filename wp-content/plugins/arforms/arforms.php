<?php @session_start();
@error_reporting(E_ERROR | E_WARNING | E_PARSE);
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/

if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        	header('X-UA-Compatible: IE=edge,chrome=1');
			
define('ARFPLUGINTITLE', 'ARForms');
define('ARFPLUGINNAME', 'ARForms');
define('FORMPATH', WP_PLUGIN_DIR.'/arforms');
define('MODELS_PATH', FORMPATH.'/core/models');
define('VIEWS_PATH', FORMPATH.'/core/views');
define('HELPERS_PATH', FORMPATH.'/core/helpers');
define('CONTROLLERS_PATH', FORMPATH.'/core/controllers');
define('TEMPLATES_PATH', FORMPATH.'/core/templates');
define('AUTORESPONDER_PATH', FORMPATH.'/core/ar/');
define('WP_FB_SESSION',session_id());
define('FS_METHOD','direct');

global $arfsiteurl;
$arfsiteurl = home_url();
if(is_ssl() and (!preg_match('/^https:\/\/.*\..*$/', $arfsiteurl) or !preg_match('/^https:\/\/.*\..*$/', WP_PLUGIN_URL))){
    $arfsiteurl = str_replace('http://', 'https://', $arfsiteurl);
    define('ARFURL', str_replace('http://', 'https://', WP_PLUGIN_URL.'/arforms'));
}else
    define('ARFURL', WP_PLUGIN_URL.'/arforms');
	

define('ARFSCRIPTURL', $arfsiteurl . (is_admin() ? '/wp-admin' : '') .'/index.php?plugin=ARForms');
define('ARFIMAGESURL', ARFURL.'/images');
define('ARFAWEBERURL',ARFURL.'/core/ar/aweber/configuration.php');
require_once(FORMPATH.'/core/wp_ar_auto_update.php');
require_once(MODELS_PATH.'/arsettingmodel.php');
require_once(MODELS_PATH.'/arstylemodel.php');

load_plugin_textdomain('ARForms', false, 'arforms/languages/' );


$wp_upload_dir 	= wp_upload_dir();
$imageupload_dir = $wp_upload_dir['basedir'].'/arforms/userfiles/';
$imageupload_dir_sub = $wp_upload_dir['basedir'].'/arforms/userfiles/thumbs/';

if(!is_dir($imageupload_dir))
	wp_mkdir_p($imageupload_dir);	

if(!is_dir($imageupload_dir_sub))
	wp_mkdir_p($imageupload_dir_sub);	

if (!defined ('IS_WPMU')){
   global $wpmu_version;
    $is_wpmu = ((function_exists('is_multisite') and is_multisite()) or $wpmu_version) ? 1 : 0;
    define('IS_WPMU', $is_wpmu);
}

global $arfversion, $arfdbversion, $arfadvanceerrcolor;
$arfversion = '2.7';
$arfdbversion = '2.7';

global $arfajaxurl;
$arfajaxurl = admin_url('admin-ajax.php');

global $arformsplugin;

global $arfloadcss, $arfforms_loaded, $arfcssloaded, $arfsavedentries;
$arfloadcss = $arfcssloaded = false;
$arfforms_loaded = $arfsavedentries = array();

require_once(HELPERS_PATH. '/armainhelper.php');
global $armainhelper;
$armainhelper = new armainhelper();

require_once(MODELS_PATH.'/arinstallermodel.php');  
require_once(MODELS_PATH.'/arfieldmodel.php');
require_once(MODELS_PATH.'/arformmodel.php');
require_once(MODELS_PATH.'/arrecordmodel.php');
require_once(MODELS_PATH.'/arrecordmeta.php');

global $MdlDb;
global $arffield;
global $arfform;
global $db_record;
global $arfrecordmeta;

global $arfsettings;
global $style_settings;
global $arsettingmodel;

$MdlDb              = new arinstallermodel();
$arffield          	= new arfieldmodel();
$arfform           	= new arformmodel();
$db_record          = new arrecordmodel();
$arfrecordmeta     	= new arrecordmeta();
$arsettingmodel		= new arsettingmodel();

require_once(CONTROLLERS_PATH . '/maincontroller.php');
require_once(CONTROLLERS_PATH . '/arformcontroller.php');

global $maincontroller;
global $arformcontroller;

$maincontroller         = new maincontroller();
$arformcontroller       = new arformcontroller();

require_once(HELPERS_PATH. '/arrecordhelper.php');
require_once(HELPERS_PATH. '/arformhelper.php');
require_once(MODELS_PATH.'/arnotifymodel.php');
	
	global $arnotifymodel;
	$arnotifymodel = new arnotifymodel();
	
	require_once(CONTROLLERS_PATH . "/arrecordcontroller.php");
	require_once(CONTROLLERS_PATH . "/arfieldcontroller.php");
	require_once(CONTROLLERS_PATH . "/arsettingcontroller.php");
	
	global $arrecordcontroller;
	global $arfieldcontroller;
	global $arsettingcontroller;
	
	$arrecordcontroller     = new arrecordcontroller();
	$arfieldcontroller      = new arfieldcontroller();
	$arsettingcontroller    = new arsettingcontroller();
	
	require_once(HELPERS_PATH. "/arfieldhelper.php");
	global $arfieldhelper;
	global $arrecordhelper;
	global $arformhelper;
	$arfieldhelper  = new arfieldhelper();
	$arrecordhelper = new arrecordhelper();
	$arformhelper	= new arformhelper();

	global $arfnextpage, $arfprevpage;
	$arfnextpage = $arfprevpage = array();
	
	global $arfmediaid;
	$arfmediaid = array();

	global $arfreadonly;
	$arfreadonly = false;
	
	global $arfshowfields, $arfrtloaded, $arfdatepickerloaded;
	global $arftimepickerloaded, $arfhiddenfields, $arfcalcfields, $arfinputmasks;

	$arfshowfields = $arfrtloaded = $arfdatepickerloaded = $arftimepickerloaded = array();
	$arfhiddenfields = $arfcalcfields = $arfinputmasks = array();

global $arfpagesize;
$arfpagesize = 20;
global $arfsidebar_width;
$arfsidebar_width = '';

global $arf_column_classes, $arf_column_classes_edit;
$arf_column_classes = $arf_column_classes_edit = array();
global $arf_page_number;
$arf_page_number = 0;
global $submit_ajax_page;
$submit_ajax_page = 0;
global $arf_section_div;
$arf_section_div = 0;
global $arf_captcha_loaded, $arf_file_loaded, $arf_modal_form_loaded;
$arf_captcha_loaded = $arf_file_loaded = $arf_modal_form_loaded = 0;

global $arf_slider_loaded;
$arf_slider_loaded = array();

global $arfmsgtounlicop;
$arfmsgtounlicop = '';

global $arf_password_loaded;
$arf_password_loaded = array();

global $arf_previous_label; 
$arf_previous_label = array();

global $arf_selectbox_loaded; 
$arf_selectbox_loaded = array();

global $arf_radio_checkbox_loaded;
$arf_radio_checkbox_loaded = array();

global $arf_conditional_logic_loaded;
$arf_conditional_logic_loaded = array();

global $arf_inputmask_loaded; 
$arf_inputmask_loaded = array();

global $arfcolorpicker_loaded; 
$arfcolorpicker_loaded = array();

global $arfcolorpicker_basic_loaded; 
$arfcolorpicker_basic_loaded = array();

global $arf_wizard_form_loaded;
$arf_wizard_form_loaded = array();

global $arf_survey_form_loaded;
$arf_survey_form_loaded = array();

global $arf_entries_action_column_width;
$arf_entries_action_column_width = 120;

global $is_multi_column_loaded;
$is_multi_column_loaded = array();
 
if(class_exists('WP_Widget')){
    require_once(FORMPATH . '/core/widgets/ARFwidgetForm.php');
    add_action('widgets_init', create_function('', 'return register_widget("ARFwidgetForm");'));
}



if( file_exists( FORMPATH.'/core/vc/class_vc_extend.php' )){
	require_once( ( FORMPATH.'/core/vc/class_vc_extend.php' ) );
	global $arforms_vdextend;
	$arforms_vdextend = new ARForms_VCExtendArp();	
}




function pluginUninstall() {
  	global $wpdb, $arsettingcontroller;
	
	if (IS_WPMU) {
	
		$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
		if ($blogs) {
			foreach($blogs as $blog) {
				switch_to_blog($blog['blog_id']);
				
				$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_autoresponder');
				$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_fields');
				$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_forms');
				$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_entries');
				$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_entry_values');
				$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_ar');
				$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_views');
				$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_ref_forms');
				
				delete_option('_transient_arf_options');
				delete_option('_transient_arfa_options');
				delete_option('arfa_css');
				delete_option('_transient_arfa_css');
				delete_option('arf_options');
				delete_option('arf_db_version');
				delete_option('arf_ar_type');
				delete_option('arf_current_tab');
				delete_option('arfdefaultar');
				delete_option('arfa_options');
				delete_option('arf_global_css');
				delete_option('widget_arforms_widget_form');
								
				delete_option("arfIsSorted");
				delete_option("arfSortOrder");
				delete_option("arfSortId");	
				
			}
			restore_current_blog();
		}
		
	} else {
		
		$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_autoresponder');
		$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_fields');
		$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_forms');
		$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_entries');
		$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_entry_values');
		$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_ar');
		$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_views');
		$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'arf_ref_forms');
		

		delete_option('_transient_arf_options');
		delete_option('_transient_arfa_options');
		delete_option('arfa_css');
		delete_option('_transient_arfa_css');
		delete_option('arf_options');
		delete_option('arf_db_version');
		delete_option('arf_ar_type');
		delete_option('arf_current_tab');
		delete_option('arfdefaultar');
		delete_option('arfa_options');
		delete_option('arf_global_css');
		delete_option('widget_arforms_widget_form');
		
		delete_option("arfIsSorted");
		delete_option("arfSortOrder");
		delete_option("arfSortId");

	}
	$arsettingcontroller->arfreqlicdeactuninst();
}
register_uninstall_hook( __FILE__, 'pluginUninstall' );
?>