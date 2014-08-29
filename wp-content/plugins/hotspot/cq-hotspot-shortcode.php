<?php
	add_shortcode( 'hotspot', 'cq_hotspot_shortcode_func');
	function cq_hotspot_shortcode_func($attr){
        wp_enqueue_script('cq_hotspot_script_touch', plugins_url('js/jquery.hotspot.min.js', __FILE__), array("jquery"));
        wp_register_script('modernizr_css3', plugins_url('js/modernizr.custom.49511.js', __FILE__), array("jquery"));
        wp_enqueue_script('modernizr_css3');
        wp_enqueue_style('cq_hotspot_css', plugins_url('css/jquery.hotspot.min.css', __FILE__));
		extract(shortcode_atts(array( // a few default values
			'post_type' => 'cq_hotspot',
			'id' => '',
			'orderby' => 'title'
		   	)
		, $attr));
		// $loop = new WP_Query($attr);
		$_tempNum = 0;
		// if($loop->have_posts()){
			$id = (NULL === $id) ? $post->ID : $id;
			// $loop->the_post();
			$meta = get_post_meta( $id, '');
			$cq_hotspot_fields = unserialize($meta['cq_hotspot_fields'][0]);
			$setting_arr = $cq_hotspot_fields[0]['setting_arr'];
			$output = '<div class="hotspot-container" data-slideshow="'.$setting_arr['cq_hotspot_autodelay'].'" data-slideshowdelay="'.$setting_arr['cq_hotspot_delaytime'].'" data-autohide="'.$setting_arr['cq_hotspot_autohide'].'" data-autohidedelay="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_autohide_delay'].'" data-loop="'.$setting_arr['cq_hotspot_loop'].'" data-triggerby="'.$setting_arr['cq_hotspot_triggerby'].'" data-sticky="'.$setting_arr['cq_hotspot_sticky'].'" data-clickimageclose="'.$setting_arr['cq_hotspot_clickimageclose'].'" data-dropinease="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_dropinease'].'" data-displayvideo="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_displayvideo'].'" data-customicon="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_customicon'].'">';


			foreach ($cq_hotspot_fields as $field) {
                for ($j=0; $j < count($field["text_block"]); $j++) {
                	// if($field["text_block"][$j]!=""){
						$output.='<div class="popover '.$field["popover_direction_prop"][$j].'" data-easein="cardInTop" data-easeout="cardOutTop" data-width="'.$field["text_width_prop"][$j].'" data-top="'.$field["text_top_prop"][$j].'" data-left="'.$field["text_left_prop"][$j].'" data-direction="'.$field["popover_direction_prop"][$j].'" style="display:none" data-style="'.$field["popover_style_prop"][$j].'">
						    <div class="cq-arrow"></div>
						    <div class="popover-inner">';
						    if(htmlspecialchars($field["popover_title"][$j])!=""){
								$output.= '<h4 class="popover-title">'.htmlspecialchars($field["popover_title"][$j]).'</h4>';
						    };
						      $output.= '<div class="popover-content">
						        <p>'.do_shortcode(htmlspecialchars($field["text_block"][$j])).'</p>
						      </div>
						    </div>
						  </div>';

                            if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_customicon']!=""){
								$output.='<a href="#" class="info-icon cq-hotspot-custom-icon" data-top="'.$field["text_top_prop"][$j].'" data-left="'.$field["text_left_prop"][$j].'" data-link="'.$field["popover_link_prop"][$j].'" data-target="'.$field["popover_target_prop"][$j].'" style="display:none;top:-1000px;"><br /><span class="cq-hotspot-label" style="">'.$field["popover_label_prop"][$j].'</span></a>';
                            }else{
								$output.='<a href="#" class="info-icon '.$field["cq_hotspot_icon"][$j].'" data-top="'.$field["text_top_prop"][$j].'" data-left="'.$field["text_left_prop"][$j].'" data-link="'.$field["popover_link_prop"][$j].'" data-target="'.$field["popover_target_prop"][$j].'" style="display:none;top:-1000px;"><br /><span class="cq-hotspot-label" style="">'.$field["popover_label_prop"][$j].'</span></a>';
                            }
                };

				if($cq_hotspot_fields[0]["hotspot_img_url"]!=""||$cq_hotspot_fields["text_block"][0]!=""){
					// add the image
	            	if($field["hotspot_img_url"][0]!=""){
	                	$output.='<img src="'.$cq_hotspot_fields[0]["hotspot_img_url"].'" class="popover-image"  />';
	            	};
				}
				$_tempNum++;
			};
			$output.='</div>';
		// }
		return html_entity_decode($output);
	};

?>
