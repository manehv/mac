<?php
class Simplr_Facebook extends Facebook {

	public $options;
	public $fb;
	

		function __construct() {
			$this->options = get_option('simplr_reg_options');
		}
		
		static function get_fb_info() {
			$options = get_option('simplr_reg_options');
			$fb = array(
				'appId'  => $options->fb_app_id,  
				'secret' => $options->fb_app_secret,  
				'cookie' => true  
			);
			return $fb;
		}
}


?>