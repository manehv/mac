<?php
/**
 * Textblock
 * Shortcode which creates a text element wrapped in a div
 */

if ( !class_exists( 'avia_sc_image' ) )
{
	class avia_sc_image extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['name']			= __('Image', 'avia_framework' );
				$this->config['tab']			= __('Media Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-image.png";
				$this->config['order']			= 100;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_image';
				$this->config['modal_data']     = array('modal_class' => 'mediumscreen');
				$this->config['tooltip'] 	    = __('Inserts an image of your choice', 'avia_framework' );
			}

			/**
			 * Popup Elements
			 *
			 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
			 * opens a modal window that allows to edit the element properties
			 *
			 * @return void
			 */
			function popup_elements()
			{
				$this->elements = array(

					array(
							"name" 	=> __("Choose Image",'avia_framework' ),
							"desc" 	=> __("Either upload a new, or choose an existing image from your media library",'avia_framework' ),
							"id" 	=> "src",
							"type" 	=> "image",
							"title" => __("Insert Image",'avia_framework' ),
							"button" => __("Insert",'avia_framework' ),
							"std" 	=> AviaBuilder::$path['imagesURL']."placeholder.jpg"),



					array(
							"name" 	=> __("Image Alignment", 'avia_framework' ),
							"desc" 	=> __("Choose here, how to align your image", 'avia_framework' ),
							"id" 	=> "align",
							"type" 	=> "select",
							"std" 	=> "center",
							"subtype" => array(
												__('Center',  'avia_framework' ) =>'center',
												__('Right',  'avia_framework' ) =>'right',
												__('Left',  'avia_framework' ) =>'left',
												__('No special alignment', 'avia_framework' ) =>'',
												)
							),

					array(
							"name" 	=> __("Image Fade in Animation", 'avia_framework' ),
							"desc" 	=> __("Add a small animation to the image when the user first scrolls to the image position. This is only to add some 'spice' to the site and only works in modern browsers", 'avia_framework' ),
							"id" 	=> "animation",
							"type" 	=> "select",
							"std" 	=> "no-animation",
							"subtype" => array(
												__('None',  'avia_framework' ) =>'no-animation',
												__('Pop up',  'avia_framework' ) =>'pop-up',
												__('Top to Bottom',  'avia_framework' ) =>'top-to-bottom',
												__('Bottom to Top',  'avia_framework' ) =>'bottom-to-top',
												__('Left to Right',  'avia_framework' ) =>'left-to-right',
												__('Right to Left',  'avia_framework' ) =>'right-to-left',
												)
							),

					 array(
							"name" 	=> __("Image Link?", 'avia_framework' ),
							"desc" 	=> __("Where should your image link to?", 'avia_framework' ),
							"id" 	=> "link",
							"type" 	=> "linkpicker",
							"fetchTMPL"	=> true,
							"subtype" => array(
												__('No Link', 'avia_framework' ) =>'',
												__('Lightbox', 'avia_framework' ) =>'lightbox',
												__('Set Manually', 'avia_framework' ) =>'manually',
												__('Single Entry', 'avia_framework' ) =>'single',
												__('Taxonomy Overview Page',  'avia_framework' )=>'taxonomy',
												),
							"std" 	=> ""),

                    array(
                        "name" 	=> __("Open new tab/window", 'avia_framework' ),
                        "desc" 	=> __("Do you want to open the link url in a new tab/window?", 'avia_framework' ),
                        "id" 	=> "target",
                        "type" 	=> "select",
                        "std"	=> "",
                        "required"=> array('link','not_empty_and','lightbox'),
                        "subtype" => AviaHtmlHelper::linking_options()
                        ),
						
						
					array(
							"name" 	=> __("Image Styling", 'avia_framework' ),
							"desc" 	=> __("Chose a styling variaton", 'avia_framework' ),
							"id" 	=> "styling",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array(
												__('Default',  'avia_framework' ) 	=>'',
												__('Circle (image height and width must be equal)',  'avia_framework' ) 	=>'circle',
												__('No Styling (no border, no border radius etc)',  'avia_framework' ) =>'no-styling',
												)
							),
							
					array(
						"name" 	=> __("Image Caption", 'avia_framework' ),
						"desc" 	=> __("Display a caption overlay?", 'avia_framework' ),
						"id" 	=> "caption",
						"type" 	=> "select",
						"std" 	=> "",
						"subtype" => array(
											__('No',  'avia_framework' ) 	=>'',
											__('Yes',  'avia_framework' ) 	=>'yes',
											)
						),
					
					array(
						"name" 		=> __("Caption", 'avia_framework' ),
						"id" 		=> "content",
						"type" 		=> "textarea",
                        "required"	=> array('caption','equals','yes'),
						"std" 		=> "",
						),
						
						
					array(	"name" 	=> __("Caption custom font size?", 'avia_framework' ),
							"desc" 	=> __("Size of your caption in pixel", 'avia_framework' ),
				            "id" 	=> "font_size",
				            "type" 	=> "select",
                        	"required"	=> array('caption','equals','yes'),
				            "subtype" => AviaHtmlHelper::number_array(10,40,1, array('Default' =>''),'px'),
				            "std" => ""),	
				            
					array(
						"name" 	=> __("Caption Appearance", 'avia_framework' ),
						"desc" 	=> __("When to display the caption?", 'avia_framework' ),
						"id" 	=> "appearance",
						"type" 	=> "select",
						"std" 	=> "",
                        "required"	=> array('caption','equals','yes'),
						"subtype" => array(
											__('Always display caption',  'avia_framework' ) 	=>'',
											__('Only display on hover',  'avia_framework' ) 	=>'on-hover',
											)
						),	
						);
						
						
						
						
			}

			/**
			 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
			 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
			 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
			 *
			 *
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_element($params)
			{
				$template = $this->update_template("src", "<img src='{{src}}' alt=''/>");
				$img	  = "";
				
				if(!empty($params['args']['attachment']) && !empty($params['args']['attachment_size']))
				{
					$img = wp_get_attachment_image($params['args']['attachment'],$params['args']['attachment_size']);
				}
				else if(isset($params['args']['src']) && is_numeric($params['args']['src']))
				{
					$img = wp_get_attachment_image($params['args']['src'],'large');
				}
				else if(!empty($params['args']['src']))
				{
					$img = "<img src='".$params['args']['src']."' alt=''  />";
				}


				$params['innerHtml']  = "<div class='avia_image avia_image_style avia_hidden_bg_box'>";
				$params['innerHtml'] .= "<div ".$this->class_by_arguments('align' ,$params['args']).">";
				$params['innerHtml'] .= "<div class='avia_image_container' {$template}>{$img}</div>";
				$params['innerHtml'] .= "</div>";
				$params['innerHtml'] .= "</div>";
				$params['class'] = "";

				return $params;
			}

			/**
			 * Frontend Shortcode Handler
			 *
			 * @param array $atts array of attributes
			 * @param string $content text within enclosing form of shortcode element
			 * @param string $shortcodename the shortcode found, when == callback name
			 * @return string $output returns the modified html string
			 */
			function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
			{
				$output = "";
				$class  = "";
				$alt 	= "";
				$title 	= "";

				extract(shortcode_atts(array('src'=>'', 'animation'=>'no-animation', 'link'=>'', 'attachment'=>'', 'attachment_size'=>'', 'target'=>'no', 'styling' =>'', 'caption'=>'', 'font_size'=>'', 'appearance'=>'' ), $atts, $this->config['shortcode']));

				if(!empty($attachment))
				{
					$attachment_entry = get_post( $attachment );
					
					if(!empty($attachment_entry))
					{
						$alt = get_post_meta($attachment_entry->ID, '_wp_attachment_image_alt', true);
	                	$alt = !empty($alt) ? esc_attr($alt) : '';
	                	$title = trim($attachment_entry->post_title) ? esc_attr($attachment_entry->post_title) : "";
	                	
	                	if(!empty($attachment_size))
						{
							$src = wp_get_attachment_image_src($attachment_entry->ID, $attachment_size);
							$src = !empty($src[0]) ? $src[0] : "";
						}
					}
				}
				else
				{
					$attachment = false;
				}

			

				if(!empty($src))
				{
					$class  = $animation == "no-animation" ? "" :"avia_animated_image avia_animate_when_almost_visible ".$animation;
					$class .= " av-styling-".$styling;
					
					if(is_numeric($src))
					{
						//$output = wp_get_attachment_image($src,'large');
						$output = wp_get_attachment_image($src,'large',false,array('class' => "avia_image $class " . $this->class_by_arguments('align' ,$atts, true)));
					}
					else
					{
						$link = aviaHelper::get_url($link, $attachment);

						$blank = (strpos($target, '_blank') !== false || $target == 'yes') ? ' target="_blank" ' : "";
						$blank .= strpos($target, 'nofollow') !== false ? ' rel="nofollow" ' : "";
						
						$overlay = "";
						$style = "";
						
						if($font_size)
						{
							$style = "style='font-size: {$font_size}px;'";
						}
						
						if($caption == "yes")
						{	
							$content = ShortcodeHelper::avia_apply_autop(ShortcodeHelper::avia_remove_autop($content));
							$overlay = "<div class='av-image-caption-overlay'><div class='av-image-caption-overlay-position'><div class='av-image-caption-overlay-center' {$style}>{$content}</div></div></div>";
							$class .= " noHover ";
							
							if($appearance) $class .= " av-overlay-".$appearance;
						}

                        $markup_url = avia_markup_helper(array('context' => 'image_url','echo'=>false, 'custom_markup'=>$meta['custom_markup']));
                        $markup = avia_markup_helper(array('context' => 'image','echo'=>false, 'custom_markup'=>$meta['custom_markup']));

                        $output .= "<div class='avia-image-container {$class} ".$meta['el_class']." ".$this->class_by_arguments('align' ,$atts, true)."' $markup >";
                        $output .= "<div class='avia-image-container-inner'>";
						if($link)
						{
							$output.= "<a href='{$link}' class='avia_image'  {$blank}>{$overlay}<img class='avia_image ' src='{$src}' alt='{$alt}' title='{$title}' $markup_url /></a>";
						}
						else
						{
							$output.= "{$overlay}<img class='avia_image ' src='{$src}' alt='{$alt}' title='{$title}'  $markup_url />";
						}
                        $output .= "</div>";
                        $output .= "</div>";
					}




				}

				return $output;
			}


	}
}









