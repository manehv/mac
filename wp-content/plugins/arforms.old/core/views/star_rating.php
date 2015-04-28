<?php

global $arfieldhelper;

if (is_array($field['options'])){

    if (!isset($field['value']))

        $field['value'] = maybe_unserialize($field['default_value']);

?>
<div class="controls">      
<?php
if(!is_admin() && apply_filters('arf_check_for_draw_outside',false,$field))
{
	do_action('arf_drawthisfieldfromoutside',$field);
}
else
{
?><div class="rate_widget_div">
    <span id="<?php echo $field['field_key'] ?>" class="rate_widget">
    	<?php 
		$small_star_size = "";
		if($field['star_size']=="small") {
			$small_star_size = "_small";
		}
		?>
    	<span class="star_0 ratings_stars ratings_stars_<?php echo $field['star_color'].$small_star_size;?>" data-val="0" data-size="<?php echo $small_star_size;?>" style="width:7px; padding:0; margin-left:0; margin-right:-2px; opacity: 0 !important; filter:alpha(opacity=0);" data-color="<?php echo $field['star_color'];?>"></span>	
<?php
    foreach($field['options'] as $opt_key => $opt){


		if( isset($field['star_val']) and ($field['star_val'] >= $opt) )
		{ ?>
		<span class="star_<?php echo esc_attr($opt) ?> ratings_stars ratings_stars_<?php echo $field['star_color'].$small_star_size;?> ratings_vote_<?php echo $field['star_color'].$small_star_size;?>" data-size="<?php echo $small_star_size;?>" data-val="<?php echo esc_attr($opt) ?>" data-color="<?php echo $field['star_color'];?>"></span> <?php
		} else {
		?><span class="star_<?php echo esc_attr($opt) ?> ratings_stars ratings_stars_<?php echo $field['star_color'].$small_star_size;?>" data-size="<?php echo $small_star_size;?>" data-val="<?php echo esc_attr($opt) ?>" data-color="<?php echo $field['star_color'];?>"></span>
        <?php } ?>

<?php } ?>
        
	</span>
<input type="text" class="rating" style="padding:0 !important; width:0 !important; margin:0 !important; height:0 !important;" name="<?php echo $field_name ?>" id="field_<?php echo $field['field_key'] ?>" data-color="<?php echo $field['star_color'];?>" data-size="<?php echo $small_star_size;?>" value="<?php echo $field['star_val']; ?>" <?php if(isset($field['required']) and $field['required']){ echo 'data-validation-required-message="'.esc_attr($field['blank']).'"'; } echo $arfieldhelper->get_onchage_func($field); ?> />
</div><?php if( is_admin() ){ 
?><input type="hidden" name="field_options[star_val_<?php echo $field['id'] ?>]" id="star_val_<?php echo $field['id'] ?>" value="<?php echo $field['star_val']; ?>" /><?php 
}  
}  
if( !is_admin() ){ echo $arfieldhelper->replace_description_shortcode($field); } 
?></div>
<div style="clear:both;"></div>
<?php } 
if( is_admin() ){
?>
<script type="application/javascript" language="javascript">

	widget_id = '<?php echo $field['field_key'] ?>';

	jQuery('#<?php echo $field['field_key'] ?> .ratings_stars').hover(
		function() {
			var color = jQuery(this).attr('data-color');
			var datasize = jQuery(this).attr('data-size');
			jQuery(this).prevAll().andSelf().addClass('ratings_over_'+color+datasize);
			jQuery(this).nextAll().removeClass('ratings_vote_'+color+datasize);
		},
		function() {
			var color = jQuery(this).attr('data-color');
			var datasize = jQuery(this).attr('data-size');
			jQuery(this).prevAll().andSelf().removeClass('ratings_over_'+color+datasize);
			set_votes(jQuery(this).parent(), widget_id);
		}
	);
			
	jQuery('#<?php echo $field['field_key'] ?> .ratings_stars').bind('click', function() {
		var star = this;
		var widget = jQuery(this).parent();
		
		var clicked_data = {
			clicked_on : jQuery(star).attr('data-val'),
			widget_id : jQuery(star).parent().attr('id')
		};
		jQuery('#star_val_<?php echo $field['id'] ?>').val(clicked_data.clicked_on);
		jQuery('#field_'+widget_id).val(clicked_data.clicked_on);
		jQuery('#field_'+widget_id).trigger('click');
		set_votes(widget, widget_id); 
	});
	
</script>
<?php
}