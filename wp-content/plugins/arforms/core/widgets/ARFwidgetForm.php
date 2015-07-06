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
class ARFwidgetForm extends WP_Widget {

	function ARFwidgetForm() {
		$widget_ops = array( 'description' => __( "Display Form of ARForms", 'ARForms') );
		$this->WP_Widget('arforms_widget_form', __('ARForms Form', 'ARForms'), $widget_ops);
                
                add_action('load-widgets.php',array(&$this,'arf_load_wiget_colorpicker'));
                
        }
        
        function arf_load_wiget_colorpicker(){
            wp_enqueue_style( 'iris' );        
            wp_enqueue_script( 'iris' );
            
        }
        
	function form( $instance ) { 
		$instance = wp_parse_args( (array) $instance, array('title' => false, 'form' => false, 'widget_type' =>'normal', 'link_type'=> 'link', 'link_position'=>'top', 'link_position_fly'=>'top', 'height'=>'540', 'width' => '800', 'desc' => 'Click here to open Form', 'button_angle' => '0') );
                
                echo "<style type='text/css'>";
                    echo ".wp-picker-container, .wp-picker-container:active{ position:relative; top:15px;left:10px; }";
                echo "</style>";
?>

	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'ARForms') ?>:</label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( stripslashes($instance['title']) ); ?>" /></p>

	<p><label for="<?php echo $this->get_field_id('form'); ?>"><?php _e('Form', 'ARForms') ?>:</label>


	    <?php global $arformhelper; $arformhelper->forms_dropdown_widget( $this->get_field_name('form'), $instance['form'], false, $this->get_field_id('form') )?>
	</p>
    
    <p><label for=""><?php _e('Form Type', 'ARForms') ?>:</label>
    	<br /><input type="radio" class="rdomodal" <?php checked($instance['widget_type'], 'normal'); ?> name="<?php echo $this->get_field_name('widget_type'); ?>" value="normal" id="<?php echo $this->get_field_id('widget_type'); ?>_type_normal" onchange="arf_change_type('<?php echo $this->get_field_name('widget_type'); ?>', '<?php echo $this->get_field_id('link_type'); ?>', '<?php echo $this->get_field_id('link_position'); ?>', '<?php echo $this->get_field_id('link_position_fly'); ?>','<?php echo $this->get_field_id('arf_fly_modal_btn_bgcol'); ?>','<?php echo $this->get_field_id('arf_fly_modal_btn_txtcol'); ?>','<?php echo $this->get_field_id('button_angle'); ?>');" /><label for="<?php echo $this->get_field_id('widget_type'); ?>_type_normal"><span></span>&nbsp;<?php _e('Internal','ARForms');?></label>
      	&nbsp;&nbsp;&nbsp;&nbsp;
      	<input type="radio" class="rdomodal" <?php checked($instance['widget_type'], 'popup'); ?> name="<?php echo $this->get_field_name('widget_type'); ?>" value="popup" id="<?php echo $this->get_field_id('widget_type'); ?>_type_popup" onchange="arf_change_type('<?php echo $this->get_field_name('widget_type'); ?>', '<?php echo $this->get_field_id('link_type'); ?>', '<?php echo $this->get_field_id('link_position'); ?>', '<?php echo $this->get_field_id('link_position_fly'); ?>','<?php echo $this->get_field_id('arf_fly_modal_btn_bgcol'); ?>','<?php echo $this->get_field_id('arf_fly_modal_btn_txtcol'); ?>','<?php echo $this->get_field_id('button_angle'); ?>');" /><label for="<?php echo $this->get_field_id('widget_type'); ?>_type_popup"><span></span>&nbsp;<?php _e('External popup window','ARForms');?></label>
    </p>
    
    <p id="<?php echo $this->get_field_id('link_type'); ?>_label" <?php if($instance['widget_type'] != 'popup'){ ?> style="display:none;"<?php } ?> ><label for="<?php echo $this->get_field_id('desc'); ?>"><?php _e('Label', 'ARForms'); ?></label>
    	<input type="text" style="width:220px;" name="<?php echo $this->get_field_name('desc'); ?>" id="<?php echo $this->get_field_id('desc'); ?>" value="<?php echo $instance['desc']; ?>" />
    </p>
    
    <p id="<?php echo $this->get_field_id('link_type').'_div'; ?>" <?php if($instance['widget_type'] != 'popup'){ ?>style="display:none;"<?php } ?>><label for="<?php echo $this->get_field_id('link_type'); ?>"><?php _e('Link Type ?', 'ARForms') ?>:</label>
    	<select onchange="arf_change_link_type('<?php echo $this->get_field_id('link_type'); ?>', '<?php echo $this->get_field_id('link_position'); ?>', '<?php echo $this->get_field_id('link_position_fly'); ?>','<?php echo $this->get_field_id('button_angle'); ?>','<?php echo $this->get_field_id('arf_fly_modal_btn_bgcol') ?>','<?php echo $this->get_field_id('arf_fly_modal_btn_txtcol') ?>');" name="<?php echo $this->get_field_name('link_type'); ?>" id="<?php echo $this->get_field_id('link_type'); ?>" data-width="150px">
            <option value="link" <?php selected($instance['link_type'], 'link'); ?>><?php _e('Link','ARForms');?></option>
            <option value="button" <?php selected($instance['link_type'], 'button'); ?>><?php _e('Button','ARForms');?></option>
            <option value="sticky" <?php selected($instance['link_type'], 'sticky'); ?>><?php _e('Sticky','ARForms');?></option>
            <option value="fly" <?php selected($instance['link_type'], 'fly'); ?>><?php _e('Fly','ARForms');?></option>
            <option value="onload" <?php selected($instance['link_type'], 'onload'); ?>><?php _e('On Page Load','ARForms');?></option>
        </select>
    </p>
    
    <p id="<?php echo $this->get_field_id('link_position').'_div'; ?>" <?php if($instance['widget_type'] == 'popup' and $instance['link_type'] == 'sticky' ){ } else { ?>style="display:none;"<?php } ?>><label for="<?php echo $this->get_field_id('link_position'); ?>"><?php _e('Link Position?', 'ARForms') ?>:</label>
    	<select name="<?php echo $this->get_field_name('link_position'); ?>"  id="<?php echo $this->get_field_id('link_position'); ?>" data-width="150px">
            <option value="top" <?php selected($instance['link_position'], 'top'); ?>><?php _e('Top','ARForms');?></option>
            <option value="bottom" <?php selected($instance['link_position'], 'bottom'); ?>><?php _e('Bottom','ARForms');?></option>
            <option value="left" <?php selected($instance['link_position'], 'left'); ?>><?php _e('Left','ARForms');?></option>
            <option value="right" <?php selected($instance['link_position'], 'right'); ?>><?php _e('Right','ARForms');?></option>
        </select>
    </p>
    
    <p id="<?php echo $this->get_field_id('link_position_fly').'_div'; ?>" <?php if($instance['widget_type'] == 'popup' and $instance['link_type'] == 'fly' ){ } else { ?>style="display:none;"<?php } ?>><label style="text-align:left;"><?php _e('Link Position?', 'ARForms') ?>:</label>
    	<select name="<?php echo $this->get_field_name('link_position_fly'); ?>" id="<?php echo $this->get_field_id('link_position'); ?>" data-width="150px" ><label for="<?php echo $this->get_field_id('link_position_fly'); ?>">
            <option value="left" <?php selected($instance['link_position_fly'], 'left'); ?>><?php _e('Left','ARForms');?></option>
            <option value="right" <?php selected($instance['link_position_fly'], 'right'); ?>><?php _e('Right','ARForms');?></option>
        </select>
    </p>
    
    <?php
        $arf_fly_sticky_btn_val = ( isset($instance['arf_fly_modal_btn_bgcol']) and !empty( $instance['arf_fly_modal_btn_bgcol']) ) ? $instance['arf_fly_modal_btn_bgcol'] : '#8ccf7a';
    ?>
    
    <p id="<?php echo $this->get_field_id('arf_fly_modal_btn_bgcol').'_div'; ?>" <?php if($instance['widget_type'] == 'popup' and ($instance['link_type'] == 'fly' or $instance['link_type'] == 'sticky')){ } else { ?> style="display:none;" <?php } ?>>
        <label style="text-align:left;"><?php _e('Background Color','ARForms'); ?>: </label>
        <input type="text" name="<?php echo $this->get_field_name('arf_fly_modal_btn_bgcol'); ?>" class="arf_fly_modal_btn_style" value="<?php echo $arf_fly_sticky_btn_val; ?>">
    </p>
    
    <?php
        $arf_fly_sticky_btn_txtval = (isset($instance['arf_fly_modal_btn_txtcol']) and !empty($instance['arf_fly_modal_btn_txtcol']) ) ? $instance['arf_fly_modal_btn_txtcol'] : '#ffffff';
    ?>
    
    <p id="<?php echo $this->get_field_id('arf_fly_modal_btn_txtcol').'_div'; ?>" <?php if($instance['widget_type'] == 'popup' and ($instance['link_type'] == 'fly' or $instance['link_type'] == 'sticky')){ } else { ?> style="display:none;" <?php } ?>>
        <label style="text-align:left;"><?php _e('Text Color','ARForms'); ?>:</label>
        <input type="text" name="<?php echo $this->get_field_name('arf_fly_modal_btn_txtcol'); ?>" class="arf_fly_modal_btn_style" value="<?php echo $arf_fly_sticky_btn_txtval; ?>">
    </p>
    
    <p id="<?php echo $this->get_field_id('link_type').'_height'; ?>" <?php if($instance['widget_type'] != 'popup'){ ?>style="display:none;"<?php } ?>>
    		<label style="text-align:left;"><?php _e('Height :', 'ARForms'); ?></label>&nbsp;&nbsp;<input type="text" class="txtmodal" name="<?php echo $this->get_field_name('height'); ?>" id="<?php echo $this->get_field_id('height'); ?>" value="<?php echo $instance['height']; ?>" style="width:70px;" />&nbsp;<?php _e('px', 'ARForms');?>
    	</p> 
                       
     <p id="<?php echo $this->get_field_id('link_type').'_width'; ?>" <?php if($instance['widget_type'] != 'popup'){ ?>style="display:none;"<?php } ?>>
     <label style="text-align:left;"><?php _e('Width :', 'ARForms'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="txtmodal" name="<?php echo $this->get_field_name('width'); ?>" id="<?php echo $this->get_field_id('width'); ?>" value="<?php echo $instance['width']; ?>" style="width:70px;" />&nbsp;<?php _e('px', 'ARForms');?>
    </p>
    
     <p id="<?php echo $this->get_field_id('button_angle').'_div'; ?>" <?php if($instance['widget_type'] == 'popup' and $instance['link_type'] == 'fly' ){ } else { ?>style="display:none;"<?php } ?>><label for="<?php echo $this->get_field_id('button_angle'); ?>"><?php _e('Button Angle', 'ARForms') ?>:</label>
    	
        <select name="<?php echo $this->get_field_name('button_angle'); ?>" class="txtmodal" id="<?php echo $this->get_field_id('button_angle'); ?>" style="width:70px;" >
                		<option value="0" <?php if($instance['button_angle'] == 0) { echo "selected=selected"; } ?> >0</option>
                        <option value="90" <?php if($instance['button_angle'] == 90) { echo "selected=selected"; } ?>>90</option>
                        <option value="-90" <?php if($instance['button_angle'] == -90) { echo "selected=selected"; } ?>>-90</option>
                </select>
    </p>
    
    <script type="text/javascript">
	function arf_change_type( name, id, link_position, link_position_fly,btn_bg_col,btn_txt_col,angle_div ){
		var type_val = jQuery('input[name="'+name+'"]:checked').val();
		if( type_val == 'popup' ){
			jQuery('#'+id+'_div').show();
			jQuery('#'+id+'_label').show();
			jQuery('#'+id+'_height').show();
			jQuery('#'+id+'_width').show();
                        jQuery('select#'+id).trigger('change');
		} else if( type_val == 'normal' ) {
			jQuery('#'+id+'_div').hide();
			jQuery('#'+id+'_label').hide();
			jQuery('#'+id+'_height').hide();
			jQuery('#'+id+'_width').hide();
			jQuery('#'+link_position+'_div').hide();
			jQuery('#'+link_position_fly+'_div').hide();
                        jQuery('#'+btn_bg_col+'_div').hide();
                        jQuery('#'+btn_txt_col+'_div').hide();
                        jQuery('#'+angle_div+'_div').hide();
		}
		
	}
	
    function arf_change_link_type(id, link_position, link_position_fly,button_angle,btn_bg_col,btn_txt_col){
		var link_type = jQuery('#'+id).val();
	
		if( link_type == 'sticky' ){
			jQuery('#'+link_position_fly+'_div').hide();
			jQuery('#'+link_position+'_div').show();
			jQuery('#'+button_angle+'_div').hide();
                        jQuery('#'+btn_bg_col+'_div').show();
                        jQuery('#'+btn_txt_col+'_div').show();
		} else if( link_type == 'fly' ) {
			jQuery('#'+link_position+'_div').hide();
			jQuery('#'+link_position_fly+'_div').show();
			jQuery('#'+button_angle+'_div').show();
                        jQuery('#'+btn_bg_col+'_div').show();
                        jQuery('#'+btn_txt_col+'_div').show();
		} else {
			jQuery('#'+link_position+'_div').hide();
			jQuery('#'+link_position_fly+'_div').hide();
			jQuery('#'+button_angle+'_div').hide();
                        jQuery('#'+btn_bg_col+'_div').hide();
                        jQuery('#'+btn_txt_col+'_div').hide();
		}
		
    }
    
    jQuery(document).ready(function() {
        jQuery('.arf_fly_modal_btn_style').iris();
    });
    
    </script>
<?php
	}
	
	function update( $new_instance, $old_instance ) {
                return $new_instance;
	}
	
	function widget( $args, $instance ) {
        global $arfform;
        extract($args);
		
		?>
		<style>
.ar_main_div_<?php echo $instance['form'];?> .arf_submit_div.left_container { text-align:center !important; clear:both !important; margin-left:auto !important; margin-right:auto !important; }
.ar_main_div_<?php echo $instance['form'];?> .arf_submit_div.right_container { text-align:center !important; clear:both !important; margin-left:auto !important; margin-right:auto !important; }
.ar_main_div_<?php echo $instance['form'];?> .arf_submit_div.top_container,
.ar_main_div_<?php echo $instance['form'];?> .arf_submit_div.none_container { text-align:center !important; clear:both !important; margin-left:auto !important; margin-right:auto !important; }

.ar_main_div_<?php echo $instance['form'];?> #brand-div { font-size: 10px; color: #444444; }
.ar_main_div_<?php echo $instance['form'];?> #brand-div.left_container { text-align:center !important; margin-left:auto !important; margin-right:auto !important; }
.ar_main_div_<?php echo $instance['form'];?> #brand-div.right_container { text-align:center !important; margin-left:auto !important; margin-right:auto !important; }
.ar_main_div_<?php echo $instance['form'];?> #brand-div.top_container,
.ar_main_div_<?php echo $instance['form'];?> #brand-div.none_container { text-align:center !important; clear:both !important; margin-left:auto !important; margin-right:auto !important; }

.ar_main_div_<?php echo $instance['form'];?> #hexagon.left_container { text-align:center !important; margin-left:auto !important; margin-right:auto !important; }
.ar_main_div_<?php echo $instance['form'];?> #hexagon.right_container { text-align:center !important; margin-left:auto !important; margin-right:auto !important; }
.ar_main_div_<?php echo $instance['form'];?> #hexagon.top_container, 
.ar_main_div_<?php echo $instance['form'];?> #hexagon.none_container { text-align:center !important; margin-left:auto !important; margin-right:auto !important; }

.ar_main_div_<?php echo $instance['form'];?> .arfsubmitbutton .arf_submit_btn { margin: 10px 0 0 0 !important; } 

</style>
        <?php
        $form_name = $arfform->getName( $instance['form'] );
		global $wpdb;
		$form_data  	= $wpdb->get_row( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $instance['form']) );
		if( $form_data )
		{
			$formoptions = maybe_unserialize( $form_data->options );
			if( isset($formoptions['display_title_form']) and $formoptions['display_title_form'] == '1') {	
				$is_title = true; 
				$is_description = true;
			} else {
				$is_title = false; 	
				$is_description = false;
			}
		}
		 
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		echo $before_widget;
		echo '<div class="arf_widget_form">';
		if ( $title )
			echo $before_title . stripslashes($title) . $after_title;
		$wp_upload_dir 	= wp_upload_dir();
		if(is_ssl())
		{
			$upload_main_url = 	str_replace("http://","https://",$wp_upload_dir['baseurl'].'/arforms/maincss');
		}
		else
		{
			$upload_main_url = 	$wp_upload_dir['baseurl'].'/arforms/maincss';
		}	

		global $armainhelper, $arrecordcontroller;	
		$fid = $upload_main_url.'/maincss_'.$instance['form'].'.css';
		wp_register_style('arfformscss'.$instance['form'],$fid);
		$armainhelper->load_styles(array('arfformscss'.$instance['form']));
		
		if( $instance['widget_type'] == 'popup' ) {
			if( $instance['link_type'] == 'sticky' )
				$arf_position = $instance['link_position'];
			else if( $instance['link_type'] == 'fly' )
				$arf_position = $instance['link_position_fly'];
			else
				$arf_position = '';
							
			echo $arrecordcontroller->show_form_popup($instance['form'], '', $is_title, $is_description, $instance['desc'], $instance['link_type'], $instance['height'], $instance['width'], $arf_position,$instance['button_angle'],$instance['arf_fly_modal_btn_bgcol'],$instance['arf_fly_modal_btn_txtcol']);
        } else {
			echo $arrecordcontroller->show_form($instance['form'], '', $is_title, $is_description, false, true);		
		}
		
		$arfsidebar_width = '';
		echo '</div>';
		echo $after_widget;
	}
}
?>