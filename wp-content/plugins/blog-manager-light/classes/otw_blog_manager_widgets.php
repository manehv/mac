<?php
/**
 * Create / Update / Display OTW Blog Manager Widgets
 */
class OTWBML_Widget extends WP_Widget {

	private $otwBMQuery = null;
	private $otwDispatcher = null;
	private $otwCSS = null;
	// Contructor 
	function OTWBML_Widget () {

		parent::WP_Widget(
			'otw_bm_widget_items', 
			'OTW Blog Manager Widget', 
			array(
				'description' => __('With the help of this widget you can add your created Widget Lists to the sidebars', 'otw_bml')
			)
		);

		$this->otwBMQuery = new OTWBMQuery();
		$this->otwDispatcher = new OTWDispatcher();
		$this->otwCSS = new OTWCss();
	}

	/** 
	 * Widget Form Creation
	 * Form used to input the List ID.
	 * Once a List is created using the plugin there is going to be and ID supplied
	 * User will need to insert that ID within this FORM
	 */
	function form ( $instance ) {

		( !empty( $instance['id'] ) )? $currentWidgetID = $instance['id'] : $currentWidgetID = null;
		( !empty( $instance['title'] ) )? $currentWidgetTitle = $instance['title'] : $currentWidgetTitle = null;

		$field_id = $this->get_field_id( 'id' );
		$field_idName = $this->get_field_name( 'id' );
		$field_title = $this->get_field_id( 'title' );
		$field_titleName = $this->get_field_name( 'title' );

		$lists = $this->otwBMQuery->getLists();
		
		$htmlForm  = '<p>';
		$htmlForm .= '<label for="'.$field_title.'">'. __('Title:', 'otw_bml') .'</label><br>';
		$htmlForm .= '<input type="text" id="'.$field_title.'" name="'.$field_titleName.'" value="'.$currentWidgetTitle.'" class="widefat"><br><br>';

		$htmlForm .= '<label for="'.$field_id.'">'. __('OTW Blog List Widget:', 'otw_bml') .'</label><br>';
		$htmlForm .= '<select id="'.$field_id.'" name="'.$field_idName.'">';
		$htmlForm .= '<option value="0"> ---'.__('Select Widget', 'otw_bml').'--- </option>';

		foreach( $lists['otw-bm-list'] as $optionData ): 
			if( $optionData['widget'] ) {		
				
				$selected = '';
				if( $optionData['id'] == $currentWidgetID ) {
					$selected = 'selected="selected" ';
				}
				$htmlForm .= "<option value=\"".$optionData['id']."\" ".$selected.">".$optionData['list_name']."</option>";
			}
		endforeach;

		$htmlForm .= '</select>';
		$htmlForm .= '</p>';
 		
 		echo $htmlForm;

	}

	// Update widget
	function update ( $new_instance, $old_instance ) {
		return $new_instance;
	}

	// Display Widget
	function widget ( $args, $instance ) {

		$widgetID = $instance['id'];

		if( !empty( $widgetID ) ) {

			// Get Current Items in the DB
			$otw_bm_options = $this->otwBMQuery->getItemById( $widgetID );

			if ( !empty( $otw_bm_options ) ) {

				$otw_posts_result = $this->otwBMQuery->getPosts( $otw_bm_options );

				$templateResult = $this->otwDispatcher->generateTemplate( $otw_bm_options, $otw_posts_result );

				$widgetOutput = $templateResult;

				if( !empty( $instance['title'] ) ) {
					$widgetOutput  = $args['before_title'] . $instance['title'] . $args['after_title'];
					$widgetOutput .= $templateResult;

				}

				if( !empty( $args['before_widget'] ) && !empty( $args['after_widget'] ) ) {
					$widgetOutput = $args['before_widget'] . $widgetOutput . $args['after_widget'];
				}

	      // Enqueue Custom Styles CSS
	      if( file_exists(SKIN_BML_PATH . 'otw-bm-list-'.$widgetID.'-custom.css') ) {
	        wp_register_style( 'otw-bm-custom-widget-'.$widgetID.'-css', SKIN_BML_URL.'otw-bm-list-'.$widgetID.'-custom.css' );
	        wp_enqueue_style( 'otw-bm-custom-widget-'.$widgetID.'-css' );
	      }

		    include( dirname( __FILE__ ) . '/../include' . DS . 'fonts.php' );
		    $googleFontsArray = json_decode($allFonts);

	      $customFonts = array(
	        'title'         => $otw_bm_options['title_font'],
	        'meta'          => $otw_bm_options['meta_font'],
	        'excpert'       => $otw_bm_options['excpert_font'],
	        'continue_read' => $otw_bm_options['read-more_font']
	      );

	      $googleWidgetFonts = $this->otwCSS->getGoogleFonts( $customFonts, $googleFontsArray  );
	      
	      if( !empty( $googleWidgetFonts ) ) {
	        $httpFonts = (!empty($_SERVER['HTTPS'])) ? "https" : "http";
	        $url = $httpFonts.'://fonts.googleapis.com/css?family='.$googleWidgetFonts.'&variant=italic:bold';
	        wp_enqueue_style('otw-bm-widget-googlefonts',$url, null, null);
	      }

				echo $widgetOutput;
			}
			 
		}

	}

}
?>