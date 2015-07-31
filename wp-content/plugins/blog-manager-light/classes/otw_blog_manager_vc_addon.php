<?php

class OTWBMLVCExtendAddonClass{

	private $has_vc = false;
	
	function __construct(){
		
		$this->otwBMQuery = new OTWBMQuery();
		$this->otwDispatcher = new OTWDispatcher();
		$this->otwCSS = new OTWCss();
		
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		
		add_shortcode( 'otw_bm_vc', array( $this, 'renderShortcode' ) );
		
	}
	
	public function renderShortcode( $params ){
	
		if( isset( $params['otw_blog_list'] ) && intval( $params['otw_blog_list'] ) ){
			echo do_shortcode( '[otw-bm-list id="'.$params['otw_blog_list'].'"]' );
		}
	}
	
	public function integrateWithVC(){
		// Check if Visual Composer is installed
		if ( defined( 'WPB_VC_VERSION' ) ) {
			$this->has_vc = true;
		}
		
		if( $this->has_vc ){
			
			$lists = $this->otwBMQuery->getLists();
			
			$options = array();
			
			if( isset($lists['otw-bm-list'] ) && is_array($lists['otw-bm-list'] ) ){
				foreach( $lists['otw-bm-list'] as $optionData ){
					if( isset( $optionData['id'] ) ){
						$options[ $optionData['list_name'] ] = $optionData['id'];
					}
				}
			}
			
			vc_map( array(
				"name" => __("Blog Manager", 'otw_bml'),
				"description" => __("Select a post list created with Blog Manager plugin", 'otw_bml'),
				"base" => "otw_bm_vc",
				"class" => "",
				"controls" => "full",
				"icon" => WP_PLUGIN_URL . DS . OTW_BML_PATH . DS .'assets'. DS .'img'. DS .'menu_icon.png', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
				"category" => __('Blog Manager', 'otw_bml'),
				"params" => array(
					array(
						'type' => 'dropdown',
						'holder' => 'div',
						'class' => '',
						'heading' => __( 'Blog list', 'otw_bml'),
						'param_name' => 'otw_blog_list',
						'value' => $options,
						'description' => __( 'Description for blog list.', 'otw_bml')
					)
				)
			) );
		}
	}
} 
new OTWBMLVCExtendAddonClass();