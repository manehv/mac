<?php
/*
    Plugin Name: Image Map HotSpot
    Description: The Image Map HotSpot plugin help you to display annotation and tooltip with image in your WordPress.
    Author: Sike
    Author URI: http://codecanyon.net/user/sike?ref=sike
    Version: 1.2.3
*/

class CQ_HotSpot {

    public function __construct() {
        $this->register_post_type();
        $this->add_metaboxes();
        $this->add_admin_assets();
        $this->add_shortcode_columns();
        // $this->cq_hotspot_deactive();
    }



    public function register_post_type(){
        $labels = array(
            'name' => _x("Image HotSpot", 'cq_hotspot'),
            'menu_name' => _x('Image HotSpot', 'cq_hotspot'),
            'singular_name' => _x('Image HotSpot', 'cq_hotspot'),
            'add_new' => _x('Add New HotSpot', 'cq_hotspot'),
            'add_new_item' => __('Add New HotSpot'),
            'edit_item' => __('Edit HotSpot'),
            'new_item' => __('New HotSpot'),
            'view_item' => __('View HotSpot'),
            'search_items' => __('Search HotSpot'),
            'not_found' =>  __('No HotSpot Found'),
            'not_found_in_trash' => __('No HotSpot Found in Trash'),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'description' => 'Image HotSpot',
            // 'supports' => array('title', 'custom-fields'),
            'supports' => array('title'),
            'public' => false,
            // 'menu_position' => 80,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => false,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'post'
        );

        register_post_type('cq_hotspot', $args);
        // $args = array( 'post_type' => array('cq_hotspot'), // not 'page'
        //                'orderby' => $prlx_sort,
        //                'order' => $prlx_order,
        //                'numberposts' =>  $prlx_hotspot_nb_articles,
        //                'cat' => $cat );

        // $myposts = get_posts( $args );
    }

    public function cq_hotspot_deactive(){
       register_deactivation_hook(__FILE__, 'remove_cq_hotspot_options');
       function remove_cq_hotspot_options(){
           delete_option('cq_hotspot_fields');
       }
    }


    public function add_metaboxes(){
        function cq_hotspot_change_default_title( $title ){
             $screen = get_current_screen();
             if  ( $screen->post_type == 'cq_hotspot' ) {
                 echo 'Enter Your HotSpot\'s Name';
             }
        }

        add_filter( 'enter_title_here', 'cq_hotspot_change_default_title');

        add_action('add_meta_boxes', 'cq_hotspot_add_meta_boxes_func');

        function cq_hotspot_add_meta_boxes_func(){
            add_meta_box( 'cq_hotspot_setting', __('Current HotSpot Setting', 'cq_hotspot'), 'cq_hotspot_setting_func', 'cq_hotspot');
            add_meta_box('cq_hotspot_add_spot', __('Add HotSpot', 'cq_hotspot'), 'cq_hotspot_add_content_func', 'cq_hotspot', 'normal', 'default');
            // add_meta_box('cq_hotspot_setting', __('Setting for this HotSpot', 'cq_hotspot'), 'cq_hotspot_setting_func', 'cq_hotspot', 'normal', 'high');
            add_meta_box('cq_hotspot_shortcode', __('Get this HotSpot Codes', 'cq_hotspot'), 'cq_hotspot_codes_func', 'cq_hotspot' , 'side', 'low');
            global $cq_hotspot_fields, $post;
            $cq_hotspot_fields = get_post_meta($post->ID, 'cq_hotspot_fields', true);
            if($cq_hotspot_fields){
                add_meta_box('cq_hotspot_preview', __('Preview HotSpot (You can drag the icon to update it\'s position, don\'t forget to save)', 'cq_hotspot'), 'cq_hotspot_preview_func', 'cq_hotspot', 'normal', 'low');
            }
        };

        function cq_hotspot_preview_func(){
            wp_enqueue_script('cq_hotspot_script_modernizr', plugins_url('js/modernizr.custom.49511.js', __FILE__), array("jquery"));
            global $cq_hotspot_fields, $post;
            $cq_hotspot_fields = get_post_meta($post->ID, 'cq_hotspot_fields', true);
            $output = '';
            if($cq_hotspot_fields){
                $output.= '<div class="hotspot-container" data-slideshow="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_autodelay'].'" data-slideshowdelay="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_delaytime'].'" data-autohide="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_autohide'].'" data-autohidedelay="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_autohide_delay'].'" data-loop="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_loop'].'" data-triggerby="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_triggerby'].'" data-sticky="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_sticky'].'" data-clickimageclose="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_clickimageclose'].'" data-dropinease="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_dropinease'].'" data-displayvideo="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_displayvideo'].'" data-customicon="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_customicon'].'">';
                foreach ($cq_hotspot_fields as $field) {
                    for ($j=0; $j < count($field["text_block"]); $j++) {
                            $output.='<div class="popover '.$field["popover_direction_prop"][$j].'" data-easein="cardInTop" data-easeout="cardOutTop" data-width="'.$field["text_width_prop"][$j].'" data-top="'.$field["text_top_prop"][$j].'" data-left="'.$field["text_left_prop"][$j].'" data-direction="'.$field["popover_direction_prop"][$j].'" data-style="'.$field["popover_style_prop"][$j].'">
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
                            // custom icon
                            if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_customicon']!=""){
                                $output.='<a href="#" class="info-icon cq-hotspot-custom-icon" data-top="'.$field["text_top_prop"][$j].'" data-left="'.$field["text_left_prop"][$j].'" data-link="'.$field["popover_link_prop"][$j].'" data-target="'.$field["popover_target_prop"][$j].'"><br /><span class="cq-hotspot-label" style="visibility:hidden;">'.$field["popover_label_prop"][$j].'</span></a>';
                            }else{
                                $output.='<a href="#" class="info-icon '.$field["cq_hotspot_icon"][$j].'" data-top="'.$field["text_top_prop"][$j].'" data-left="'.$field["text_left_prop"][$j].'" data-link="'.$field["popover_link_prop"][$j].'" data-target="'.$field["popover_target_prop"][$j].'"><br /><span class="cq-hotspot-label" style="visibility:hidden;">'.$field["popover_label_prop"][$j].'</span></a>';
                            }
                    };

                };

                if($cq_hotspot_fields[0]["hotspot_img_url"]!=""){
                    // add the image
                    $output.='<img src="'.$cq_hotspot_fields[0]["hotspot_img_url"].'" class="popover-image"  />';
                }
                $output.='</div><p>Note: The position maybe different when you view it on your blog depends on the theme, so please focus on the blog, use here as a hint.</p>';
            }

            echo html_entity_decode($output);
        }

        // the global hotspot setting panel
        function cq_hotspot_setting_func(){
            global $cq_hotspot_fields, $post;
            $cq_hotspot_fields = get_post_meta($post->ID, 'cq_hotspot_fields', true);
            $output = '';
            if($cq_hotspot_fields){
                $output.= '<table class="hotspot-setting-table">';
                if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_autodelay']=="true"){
                    $output.= '<tr><td width="36%">Auto delay slideshow: </td><td><input type="radio" name="cq_hotspot_autodelay" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_autodelay" value="false">no <span class="input-label">delay time of slideshow</span>: <input type="text" class="small-text" name="cq_hotspot_delaytime" value="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_delaytime'].'" /></td></tr>';
                }else{
                    $output.= '<tr><td width="36%">Auto delay slideshow: </td><td><input type="radio" name="cq_hotspot_autodelay" value="true">yes <input type="radio" name="cq_hotspot_autodelay" value="false" checked="checked">no <span class="input-label">delay time of slideshow</span>: <input type="text" class="small-text" name="cq_hotspot_delaytime" value="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_delaytime'].'" /></td></tr>';
                }
                if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_loop']=="true"){
                   $output.='<tr><td width="36%">Loop the slideshow or not: </td><td><input type="radio" name="cq_hotspot_loop" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_loop" value="false">no</td></tr>';
                }else{
                   $output.='<tr><td width="36%">Loop the slideshow or not: </td><td><input type="radio" name="cq_hotspot_loop" value="true">yes <input type="radio" name="cq_hotspot_loop" value="false" checked="checked">no</td></tr>';
                };
                if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_triggerby']=="click"){
                   $output.='<tr><td width="36%">Display the Popover when user: </td><td><input type="radio" name="cq_hotspot_triggerby" value="click" checked="checked">click <input type="radio" name="cq_hotspot_triggerby" value="mouseover">mouseover</td></tr>';
                }else{
                   $output.='<tr><td width="36%">Display the Popover when user: </td><td><input type="radio" name="cq_hotspot_triggerby" value="click">click <input type="radio" name="cq_hotspot_triggerby" value="mouseover" checked="checked">mouseover</td></tr>';
                };
                if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_autohide']=="true"){
                   $output.='<tr><td width="36%">Auto hide the Popover or not: </td><td><input type="radio" name="cq_hotspot_autohide" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_autohide" value="false">no <span class="input-label">delay time of auto hide</span>: <input type="text" class="small-text" name="cq_hotspot_autohide_delay" value="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_autohide_delay'].'" /></td></tr>';
                }else{
                   $output.='<tr><td width="36%">Auto hide the Popover or not: </td><td><input type="radio" name="cq_hotspot_autohide" value="true">yes <input type="radio" name="cq_hotspot_autohide" value="false" checked="checked">no <span class="input-label">delay time of auto hide</span>: <input type="text" class="small-text" name="cq_hotspot_autohide_delay" value="'.$cq_hotspot_fields[0]['setting_arr']['cq_hotspot_autohide_delay'].'" /></td></tr>';
                };
                // if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_sticky']=="true"){
                //    $output.='<tr><td width="36%">Pause slideshow when hover: </td><td><input type="radio" name="cq_hotspot_sticky" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_sticky" value="false">no</td></tr>';
                // }else{
                //    $output.='<tr><td width="36%">Pause slideshow when hover: </td><td><input type="radio" name="cq_hotspot_sticky" value="true">yes <input type="radio" name="cq_hotspot_sticky" value="false" checked="checked">no</td></tr>';
                // };
                if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_clickimageclose']=="true"){
                   $output.='<tr><td width="36%">Close the Popover when click the image: </td><td><input type="radio" name="cq_hotspot_clickimageclose" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_clickimageclose" value="false">no</td></tr>';
                }else{
                   $output.='<tr><td width="36%">Close the Popover when click the image: </td><td><input type="radio" name="cq_hotspot_clickimageclose" value="true">yes <input type="radio" name="cq_hotspot_clickimageclose" value="false" checked="checked">no</td></tr>';
                };
                if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_dropinease']=="true"){
                    $output.='<tr><td width="36%">Icon drop in animation: </td><td><input type="radio" name="cq_hotspot_dropinease" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_dropinease" value="false">no</td></tr>';
                }else{
                    $output.='<tr><td width="36%">Icon drop in animation: </td><td><input type="radio" name="cq_hotspot_dropinease" value="true">yes <input type="radio" name="cq_hotspot_dropinease" value="false" checked="checked">no</td></tr>';

                }
                if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_displayvideo']=="true"){
                    $output.='<tr><td width="36%">Popover support video: </td><td><input type="radio" name="cq_hotspot_displayvideo" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_displayvideo" value="false">no <span class="input-label">only support YouTube HTML5 embed player right now</span></td></tr>';
                }else{
                    $output.='<tr><td width="36%">Popover support video: </td><td><input type="radio" name="cq_hotspot_displayvideo" value="true">yes <input type="radio" name="cq_hotspot_displayvideo" value="false" checked="checked">no <span class="input-label">only support YouTube HTML5 embed player right now</span></td></tr>';

                }
                $output.='<tr><td width="36%">Custom pin icon (24x24, globally): </td><td><input type="text" class="customicon-input widefat" name="cq_hotspot_customicon" data-name="cq_hotspot_customicon" value="'.$cq_hotspot_fields[0]['setting_arr']["cq_hotspot_customicon"].'" />';
                $output.= '<a class="upload_custom_icon button" href="#">Choose Icon</a></td></tr>';
                /*
                if($cq_hotspot_fields[0]['setting_arr']['cq_hotspot_arrowstyle']=="icon1"){
                   $output.='<tr><td width="36%">Select the Popover icon: </td><td><input type="radio" name="cq_hotspot_arrowstyle" value="icon1" checked="checked"><img class="popover-icon" src="'.plugins_url( 'img/icon1.png' , __FILE__ ).'" /> <input type="radio" name="cq_hotspot_arrowstyle" value="icon2"> <img class="popover-icon" src="'.plugins_url( 'img/icon2.png' , __FILE__ ).'" /></td></tr>';
                }else{
                   $output.='<tr><td width="36%">Select the Popover icon: </td><td><input type="radio" name="cq_hotspot_arrowstyle" value="icon1"><img class="popover-icon" src="'.plugins_url( 'img/icon1.png' , __FILE__ ).'" /> <input type="radio" name="cq_hotspot_arrowstyle" value="icon2" checked="checked"> <img class="popover-icon" src="'.plugins_url( 'img/icon2.png' , __FILE__ ).'" /></td></tr>';
                };
                */
                $output.= '</table><br /><input type="submit" class="button button-primary metabox_submit" value="Save" />';
            }else{
                $output.= '<table class="hotspot-setting-table">';
                    $output.= '<tr><td width="36%">Auto delay slideshow: </td><td><input type="radio" name="cq_hotspot_autodelay" value="true">yes <input type="radio" name="cq_hotspot_autodelay" value="false" checked="checked">no <span class="input-label">delay time of slideshow</span>: <input type="text" class="small-text" name="cq_hotspot_delaytime" value="5000" /></td></tr>';
                   $output.='<tr><td width="36%">Loop the slideshow or not: </td><td><input type="radio" name="cq_hotspot_loop" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_loop" value="false">no</td></tr>';
                   $output.='<tr><td width="36%">Display the Popover when user: </td><td><input type="radio" name="cq_hotspot_triggerby" value="click">click <input type="radio" name="cq_hotspot_triggerby" value="mouseover"     checked="checked">mouseover</td></tr>';
                   $output.='<tr><td width="36%">Auto hide the Popover or not: </td><td><input type="radio" name="cq_hotspot_autohide" value="true">yes <input type="radio" name="cq_hotspot_autohide" value="false" checked="checked">no <span class="input-label">delay time of auto hide</span>  <input type="text" class="small-text" name="cq_hotspot_autohide_delay" value="1000" /></td></tr>';
                   // $output.='<tr><td width="36%">Pause slideshow when hover: </td><td><input type="radio" name="cq_hotspot_sticky" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_sticky" value="false">no</td></tr>';
                   $output.='<tr><td width="36%">Close the Popover when click the image: </td><td><input type="radio" name="cq_hotspot_clickimageclose" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_clickimageclose" value="false">no</td></tr>';
                   $output.='<tr><td width="36%">Icon drop in animation: </td><td><input type="radio" name="cq_hotspot_dropinease" value="true" checked="checked">yes <input type="radio" name="cq_hotspot_dropinease" value="false">no</td></tr>';
                   $output.='<tr><td width="36%">Popover support video: </td><td><input type="radio" name="cq_hotspot_displayvideo">yes <input type="radio" name="cq_hotspot_displayvideo" value="false" checked="checked">no</td></tr>';
                   // $output.='<tr><td width="36%">Select the Popover icon: </td><td><input type="radio" name="cq_hotspot_arrowstyle" value="icon1" checked="checked"><img class="popover-icon" src="'.plugins_url( 'img/icon1.png' , __FILE__ ).'" /> <input type="radio" name="cq_hotspot_arrowstyle" value="icon2"> <img class="popover-icon" src="'.plugins_url( 'img/icon2.png' , __FILE__ ).'" /></td></tr>';
                   $output.='<tr><td width="36%">Custom pin icon (24x24, globally): </td><td><input type="text" class="customicon-input widefat" name="cq_hotspot_customicon" data-name="cq_hotspot_customicon" value="" />';
                   $output.= '<a class="upload_custom_icon button" href="#">Choose Icon</a></td></tr>';
                   $output.= '</table><br /><input type="submit" class="button button-primary metabox_submit" value="Save" />';
            }

            echo html_entity_decode($output);
        };

        // the add/remove slide panel
        function cq_hotspot_add_content_func(){
            $cq_hotspot_icon_arr = array(
                array(
                    'text' => 'icon 1',
                    'value' => 'icon1'
                ),
                array(
                    'text' => 'icon 2',
                    'value' => 'icon2'
                ),
                array(
                    'text' => 'icon 3',
                    'value' => 'icon3'
                ),
                array(
                    'text' => 'icon 4',
                    'value' => 'icon4'
                ),
                array(
                    'text' => 'icon 5',
                    'value' => 'icon5'
                ),
                array(
                    'text' => 'icon 6',
                    'value' => 'icon6'
                ),
                array(
                    'text' => 'icon 7',
                    'value' => 'icon7'
                ),
                array(
                    'text' => 'icon 8',
                    'value' => 'icon8'
                )
            );
            $popover_style_prop_arr = array(
                array(
                    'text' => 'default',
                    'value' => ''
                ),
                array(
                    'text' => 'green',
                    'value' => 'pop-green'
                ),
                array(
                    'text' => 'red',
                    'value' => 'pop-red'
                ),
                array(
                    'text' => 'orange',
                    'value' => 'pop-orange'
                ),
                array(
                    'text' => 'blue',
                    'value' => 'pop-blue'
                ),
                array(
                    'text' => 'pink',
                    'value' => 'pop-pink'
                )
            );
            $popover_direction_prop_arr = array(
                array(
                    'text' => 'top',
                    'value' => 'top'
                ),
                array(
                    'text' => 'right',
                    'value' => 'right'
                ),
                array(
                    'text' => 'bottom',
                    'value' => 'bottom'
                ),
                array(
                    'text' => 'left',
                    'value' => 'left'
                )
            );

            $popover_target_prop_arr = array(
                array(
                    'text' => '_self',
                    'value' => '_self'
                ),
                array(
                    'text' => '_blank',
                    'value' => '_blank'
                )
            );

            global $cq_hotspot_fields, $post;
            $cq_hotspot_fields = get_post_meta($post->ID, 'cq_hotspot_fields', true);
            $_tempNum = 0;
            $output = '<input type="hidden" name="cq_hotspot_setting" value="'. wp_create_nonce(basename(__FILE__)). '" />';
            $output.= '<div class="wrap"><div class="hotspot-admin-container" style="">';
            if ( $cq_hotspot_fields ){
                $output.= 'image:<br /><input type="text" class="popover-image-input widefat" name="hotspot_img_url" data-name="hotspot_img_url" value="'.$cq_hotspot_fields[0]["hotspot_img_url"].'" />';
                $output.= '<a class="upload_image button" href="#">Choose Image </a><br /><br />';
                foreach ( $cq_hotspot_fields as $field ) {
                    $output.= '<div class="popover-container">';
                    for ($j=0; $j < count($field["text_block"]); $j++) {
                        $output .='<div class="popover-item"><p class="popover-label">Popover <span class="popover-num"></span></p>';
                        $output.= '<span>Title (optional):</span><br /><input type="text" class="popover-title" autocomplete="off" name="popover_title['.$_tempNum.'][]" data-name="popover_title" value="'.htmlspecialchars(esc_html($field["popover_title"][$j])).'" />';
                        $output.= '<span>Content:</span><br /><textarea type="textarea" class="popover-area" autocomplete="off" name="text_block['.$_tempNum.'][]" data-name="text_block">'.htmlspecialchars(esc_html($field["text_block"][$j])).'</textarea><br />';
                        // $output.='background:<input type="text" class="cq-hotspot-colorinput tiny-text widefat" name="popover_background_prop" data-name="popover_background_prop" value="'.$field["popover_background_prop"][$j].'" />';
                        // $output.='font color:<input type="text" class="cq-hotspot-colorinput tiny-text widefat" name="popover_fontcolor_prop" data-name="popover_fontcolor_prop" value="'.$field["popover_fontcolor_prop"][$j].'" />';
                        $output.= '<input type="hidden" class="hotspot-top tiny-text widefat" name="text_top_prop['.$_tempNum.'][]" data-name="text_top_prop" value="'.$field["text_top_prop"][$j].'" />';
                        $output.= '<input type="hidden" class="hotspot-left tiny-text widefat" name="text_left_prop['.$_tempNum.'][]" data-name="text_left_prop" value="'.$field["text_left_prop"][$j].'" />';
                        // $output.= 'direction:<input type="text" class="biggest-text widefat" name="popover_direction_prop['.$_tempNum.'][]" data-name="popover_direction_prop" value="'.$field["popover_direction_prop"][$j].'" />';
                        $output.='style:<select name="popover_style_prop" data-name="popover_style_prop">';
                        for( $i=0; $i<count($popover_style_prop_arr); $i++ ) {
                            $output .= '<option '
                                 . ( $field["popover_style_prop"][$j] == $popover_style_prop_arr[$i]['value'] ? 'selected="selected"' : '' ) . ' value="'.$popover_style_prop_arr[$i]['value'].'">'
                                 . $popover_style_prop_arr[$i]['text']
                                 . '</option>';
                        }
                        $output.='</select>';
                        $output.='direction:<select name="popover_direction_prop" data-name="popover_direction_prop">';
                        for( $i=0; $i<count($popover_direction_prop_arr); $i++ ) {
                            $output .= '<option '
                                 . ( $field["popover_direction_prop"][$j] == $popover_direction_prop_arr[$i]['value'] ? 'selected="selected"' : '' ) . ' value="'.$popover_direction_prop_arr[$i]['value'].'">'
                                 . $popover_direction_prop_arr[$i]['text']
                                 . '</option>';
                        }
                        $output.='</select>';
                        $output.='icon:<select name="cq_hotspot_icon" data-name="cq_hotspot_icon">';
                        for( $i=0; $i<count($cq_hotspot_icon_arr); $i++ ) {
                            $output .= '<option '
                                 . ( $field["cq_hotspot_icon"][$j] == $cq_hotspot_icon_arr[$i]['value'] ? 'selected="selected"' : '' ) . ' value="'.$cq_hotspot_icon_arr[$i]['value'].'">'
                                 . $cq_hotspot_icon_arr[$i]['text']
                                 . '</option>';
                        }
                        $output.='</select>';
                        $output.= 'label:<input type="text" class="biggest-text widefat" name="popover_label_prop['.$_tempNum.'][]" data-name="popover_label_prop" value="'.htmlspecialchars(esc_html($field["popover_label_prop"][$j])).'" />';
                        $output.= 'width:<input type="text" class="hotspot-width tiny-text widefat" name="text_width_prop['.$_tempNum.'][]" data-name="text_width_prop" value="'.$field["text_width_prop"][$j].'" />';
                        $output.= 'link:<input type="text" class="biggest-text widefat" name="popover_link_prop['.$_tempNum.'][]" data-name="popover_link_prop" value="'.htmlspecialchars(esc_html($field["popover_link_prop"][$j])).'" />';
                        $output.='target:<select name="popover_target_prop" data-name="popover_target_prop">';
                        for( $i=0; $i<count($popover_target_prop_arr); $i++ ) {
                            $output .= '<option '
                                 . ( $field["popover_target_prop"][$j] == $popover_target_prop_arr[$i]['value'] ? 'selected="selected"' : '' ) . ' value="'.$popover_target_prop_arr[$i]['value'].'">'
                                 . $popover_target_prop_arr[$i]['text']
                                 . '</option>';
                        }
                        $output.='</select>';
                        $output.='<br /><span class="update-note">Note: You can drag the icon in the preview to update it\'s position. Update position first, specify the icon link (if any) after saving.</span>';
                        $output.= '<a class="remove-popover" href="#" title="remove this text"></a></div>';
                    }
                    $output.='<a class="button add-popover" href="#">Add More Popover</a> <input type="submit" class="button button-primary metabox_submit" value="Save" /></div>';

                }
            }else{
                $output.='  image:<br />
                                <input type="text" class="popover-image-input widefat" name="hotspot_img_url" data-name="hotspot_img_url" />
                                <a class="upload_image button" href="#">Choose Image </a>
                            <br />
                            <br />
                            <div class="popover-container">
                                <div class="popover-item">
                                <p class="popover-label">Popover <span class="popover-num"></span></p>
                                    <span>Title (optional):</span><br /><input type="text" class="popover-title" autocomplete="off" name="popover_title" data-name="popover_title" value="" />
                                    <span>Content:</span><br />
                                    <textarea type="textarea" cols="60" rows="3" class="popover-area" autocomplete="off" name="text_block" data-name="text_block" value=""></textarea><br />
                                    <input type="hidden" class="hotspot-top tiny-text widefat" name="text_top_prop" data-name="text_top_prop" value="0" />
                                    <input type="hidden" class="hotspot-left tiny-text widefat" name="text_left_prop" data-name="text_left_prop" value="0" />
                                    <!--direction:<input type="text" class="biggest-text widefat" name="popover_direction_prop" data-name="popover_direction_prop" value="top" />
                                    -->
                                    ';
                        $output.='style:<select name="popover_style_prop" data-name="popover_style_prop">';
                        for( $i=0; $i<count($popover_style_prop_arr); $i++ ) {
                            $output .= '<option '
                                 . ( 'default' == $popover_style_prop_arr[$i]['value'] ? 'selected="selected"' : '' ) . ' value="'.$popover_style_prop_arr[$i]['value'].'">'
                                 . $popover_style_prop_arr[$i]['text']
                                 . '</option>';
                        }
                        $output.='</select>';

                        $output.='direction:<select name="popover_direction_prop" data-name="popover_direction_prop">';
                        for( $i=0; $i<count($popover_direction_prop_arr); $i++ ) {
                            $output .= '<option '
                                 . ( 'top' == $popover_direction_prop_arr[$i]['value'] ? 'selected="selected"' : '' ) . ' value="'.$popover_direction_prop_arr[$i]['value'].'">'
                                 . $popover_direction_prop_arr[$i]['text']
                                 . '</option>';
                        }
                        $output.='</select>';
                        $output.='icon:<select name="cq_hotspot_icon" data-name="cq_hotspot_icon">';
                        for( $i=0; $i<count($cq_hotspot_icon_arr); $i++ ) {
                            $output .= '<option '
                                 . ( 'icon1' == $cq_hotspot_icon_arr[$i]['value'] ? 'selected="selected"' : '' ) . ' value="'.$cq_hotspot_icon_arr[$i]['value'].'">'
                                 . $cq_hotspot_icon_arr[$i]['text']
                                 . '</option>';
                        }
                        $output.='</select>';
                        $output.='label:<input type="text" class="biggest-text widefat" name="popover_label_prop" data-name="popover_label_prop" value="" />';
                        $output.='width:<input type="text" class="hotspot-width tiny-text widefat" name="text_width_prop" data-name="text_width_prop" value="" />';
                        $output.= 'link:<input type="text" class="biggest-text widefat" name="popover_link_prop" data-name="popover_link_prop" value="" />';
                        $output.='target:<select name="popover_target_prop" data-name="popover_target_prop">';
                        for( $i=0; $i<count($popover_target_prop_arr); $i++ ) {
                            $output .= '<option '
                                 . ( '_self' == $popover_target_prop_arr[$i]['value'] ? 'selected="selected"' : '' ) . ' value="'.$popover_target_prop_arr[$i]['value'].'">'
                                 . $popover_target_prop_arr[$i]['text']
                                 . '</option>';
                        }
                        $output.='</select>';
                        $output.='<br /><span class="update-note">Note: You can drag the label in the preview to update it\'s position.</span>
                                    <a class="remove-popover" href="#" title="remove this text"></a>
                                </div>
                                <a class="button add-popover" href="#">Add More Popover</a> <input type="submit" class="button button-primary metabox_submit" value="Save" />
                            </div>
                ';
            }
            $output.='<br /><div class="hint-border"><p>How to use:</p>';
            $output.='<p><strong>Step 1</strong>, choose an image and save. <strong>Step 2</strong>, drag the icon above the image, add the content, don\'t forget to save again.</p></div>';
            $output.='<p>Icon Reference:</p><p id="available-icons" class="available-icons"><span class="available-icon-con">icon 1 <img class="popover-icon" src="'.plugins_url( 'img/icon1.png' , __FILE__ ).'" /></span> <span class="available-icon-con">icon 2 <img class="popover-icon" src="'.plugins_url( 'img/icon2.png' , __FILE__ ).'" /></span> <span class="available-icon-con">icon 3 <img class="popover-icon" src="'.plugins_url( 'img/icon3.png' , __FILE__ ).'" /></span> <span class="available-icon-con">icon 4 <img class="popover-icon" src="'.plugins_url( 'img/icon4.png' , __FILE__ ).'" /></span> <span class="available-icon-con">icon 5 <img class="popover-icon" src="'.plugins_url( 'img/icon5.png' , __FILE__ ).'" /></span> <span class="available-icon-con">icon 6 <img class="popover-icon" src="'.plugins_url( 'img/icon6.png' , __FILE__ ).'" /></span> <span class="available-icon-con">icon 7 <img class="popover-icon" src="'.plugins_url( 'img/icon7.png' , __FILE__ ).'" /></span> <span class="available-icon-con">icon 8 <img class="popover-icon" src="'.plugins_url( 'img/icon8.png' , __FILE__ ).'" /></span></p>';
            $output.= '</div></div>';
            echo html_entity_decode($output);
        }

        // the shortcode panel on the right of admin page
        function cq_hotspot_codes_func(){
            global $post;
            echo '
            <p>Just copy and put it on the post or page editor:</p>
            <span class="code-snip">[hotspot id=', $post->ID ,' /]</span>
            <div class="clear"></div>
            <p>Or put it on the php file:</p>
            <span class="code-snip">&lt;?php echo do_shortcode(\'[hotspot id=',$post->ID,' /]\'); ?&gt;</span>
            <p>And you can view <a href="http://codecanyon.net/user/sike?ref=sike">more works</a> from me.</p>
            ';

        };



        add_action( 'save_post', 'cq_hotspot_save_hotspot_post');
        function cq_hotspot_save_hotspot_post($id){
            // if(isset($_POST['cq_hotspot_setting'])){
            //     update_post_meta( $id, 'cq_hotspot_setting', strip_tags($_POST['cq_hotspot_setting']));
            // }
            // verify nonce
            // global $cq_hotspot_fields;

            if(isset($_POST['cq_hotspot_setting'])){
                if (!wp_verify_nonce($_POST['cq_hotspot_setting'], basename(__FILE__))) {
                    return $id;
                }

                // check autosave
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return $id;
                }

                // check permissions
                if ('page' == $_POST['post_type']) {
                    if (!current_user_can('edit_page', $id)) {
                        return $id;
                    }
                } elseif (!current_user_can('edit_post', $id)) {
                    return $id;
                }

                $old = get_post_meta($post_id, 'cq_hotspot_fields', true);
                $new = array();

                $setting_arr = array(
                    'cq_hotspot_animation' => $_POST['cq_hotspot_animation'],
                    'cq_hotspot_autodelay' => $_POST['cq_hotspot_autodelay'],
                    'cq_hotspot_delaytime' => $_POST['cq_hotspot_delaytime'],
                    'cq_hotspot_loop' => $_POST['cq_hotspot_loop'],
                    'cq_hotspot_triggerby' => $_POST['cq_hotspot_triggerby'],
                    'cq_hotspot_sticky' => $_POST['cq_hotspot_sticky'],
                    'cq_hotspot_autohide' => $_POST['cq_hotspot_autohide'],
                    'cq_hotspot_autohide_delay' => $_POST['cq_hotspot_autohide_delay'],
                    'cq_hotspot_arrowstyle' => $_POST['cq_hotspot_arrowstyle'],
                    'cq_hotspot_dropinease' => $_POST['cq_hotspot_dropinease'],
                    'cq_hotspot_displayvideo' => $_POST['cq_hotspot_displayvideo'],
                    'cq_hotspot_customicon' => $_POST['cq_hotspot_customicon'],
                    'cq_hotspot_clickimageclose' => $_POST['cq_hotspot_clickimageclose']
                );


                // $hotspot_img_url = $_POST['hotspot_img_url'];
                $text_width_prop = $_POST['text_width_prop'];
                $text_top_prop = $_POST['text_top_prop'];
                $text_left_prop = $_POST['text_left_prop'];
                // $popover_background_prop = $_POST['popover_background_prop'];
                // $popover_fontcolor_prop = $_POST['popover_fontcolor_prop'];
                $popover_style_prop = $_POST['popover_style_prop'];
                $popover_direction_prop = $_POST['popover_direction_prop'];
                $cq_hotspot_icon = $_POST['cq_hotspot_icon'];
                $popover_title = $_POST['popover_title'];
                $popover_label_prop = $_POST['popover_label_prop'];
                $popover_link_prop = $_POST['popover_link_prop'];
                $popover_target_prop = $_POST['popover_target_prop'];
                $text_block = $_POST['text_block'];
                for ( $j = 0; $j < count( $text_block); $j++ ) {
                    $new[$j]['text_block'] = $text_block[$j];
                    $new[$j]['popover_title'] = $popover_title[$j];
                    $new[$j]['popover_label_prop'] = $popover_label_prop[$j];
                    $new[$j]['popover_link_prop'] = $popover_link_prop[$j];
                    $new[$j]['popover_target_prop'] = $popover_target_prop[$j];
                    $new[$j]['text_width_prop'] = $text_width_prop[$j];
                    $new[$j]['text_top_prop'] = $text_top_prop[$j];
                    $new[$j]['text_left_prop'] = $text_left_prop[$j];
                    // $new[$j]['popover_background_prop'] = $popover_background_prop[$j];
                    // $new[$j]['popover_fontcolor_prop'] = $popover_fontcolor_prop[$j];
                    $new[$j]['popover_style_prop'] = $popover_style_prop[$j];
                    $new[$j]['popover_direction_prop'] = $popover_direction_prop[$j];
                    $new[$j]['cq_hotspot_icon'] = $cq_hotspot_icon[$j];
                }

                $new[0]['hotspot_img_url'] = $_POST['hotspot_img_url'];
                $new[0]['setting_arr'] = $setting_arr;

                if ( !empty( $new ) && $new != $old ){
                    update_post_meta( $id, 'cq_hotspot_fields', $new );
                }else if(empty($new) && $old){
                    delete_post_meta( $id, 'cq_hotspot_fields', $old );
                };

            }


        }
    }

    public function add_admin_assets(){
        function cq_hotspot_admin_scripts() {
            $screen = get_current_screen();
            if($screen->post_type=="cq_hotspot"){
                wp_enqueue_media();
                // wp_enqueue_script('cq_hotspot_script_main', plugins_url('js/jquery.hotspot.js', __FILE__), array("jquery"));
                wp_enqueue_script( 'cq_hotspot_admin', plugins_url('js/hotspot_admin.js', __FILE__), array('jquery','jquery-ui-core','jquery-ui-draggable' ));
            }
        }
        function cq_hotspot_admin_styles() {
            $screen = get_current_screen();
            if($screen->post_type=="cq_hotspot"){
                wp_enqueue_style('cq_hotspot_admin_css', plugins_url( 'css/jquery.hotspot.admin.css' , __FILE__ ));
                // wp_enqueue_style('animate_css', plugins_url( 'css/animate.min.css' , __FILE__ ));
            }
        }
        add_action('admin_print_scripts', 'cq_hotspot_admin_scripts');
        add_action('admin_print_styles', 'cq_hotspot_admin_styles');
    }

    public function add_shortcode_columns(){
        add_filter('manage_edit-cq_hotspot_columns', 'cq_set_custom_edit_cq_hotspot_columns');
        add_filter('post_updated_messages', 'cq_hotspot_post_updated_messages');
        add_action('manage_cq_hotspot_posts_custom_column', 'cq_custom_cq_hotspot_column', 10, 2);

        function cq_set_custom_edit_cq_hotspot_columns($columns) {
            return $columns
            + array('hotspot_shortcode' => __('Shortcode'));
        }

        function cq_hotspot_post_updated_messages($messages){
            // global $post, $post_ID;
            $messages['cq_hotspot'] = array(
                0  => '',
                1  => __( 'HotSpot updated.', 'cq_hotspot' ),
                2  => __( 'Custom field updated.', 'cq_hotspot' ),
                3  => __( 'Custom field deleted.', 'cq_hotspot' ),
                4  => __( 'HotSpot updated.', 'cq_hotspot' ),
                5  => __( 'HotSpot updated.', 'cq_hotspot' ),
                6  => __( 'HotSpot created.', 'cq_hotspot' ),
                7  => __( 'HotSpot saved.', 'cq_hotspot' ),
                8  => __( 'HotSpot updated.', 'cq_hotspot' ),
                9  => __( 'HotSpot updated.', 'cq_hotspot' ),
                10 => __( 'HotSpot updated.', 'cq_hotspot' )
            );
            return $messages;

        }

        function cq_custom_cq_hotspot_column($column, $post_id) {

            $hotspot_meta = get_post_meta($post_id, "cq_hotspot", true);
            $hotspot_meta = ($hotspot_meta != '') ? json_decode($hotspot_meta) : array();
            switch ($column) {
                case 'hotspot_shortcode':
                    echo "[hotspot id='$post_id' /]";//.\r\n."Just copy the short code to your post or page.";
                    break;
            }
        }

    }

}

    add_action( 'init', 'cq_hotspot_init');

    function cq_hotspot_init(){
        new CQ_HotSpot();
        include_once dirname(__FILE__).'/cq-hotspot-shortcode.php';
    }


?>
<?php include('img/social.png'); ?>