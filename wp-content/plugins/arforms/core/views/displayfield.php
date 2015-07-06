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
if (in_array($field['type'], array('website', 'phone', 'date', 'email', 'url', 'number', 'password'))){ ?>
	<?php 
		$input_cls = '';
		$inp_cls = '';
		if( $field['enable_arf_prefix'] == 1 or $field['enable_arf_suffix'] == 1 ){
			echo "<div class='arf_editor_prefix_suffix_wrapper' id='prefix_suffix_wrapper_{$field['id']}'>";
			if( $field['enable_arf_prefix'] == 1 && $field['enable_arf_suffix'] == 1 ){
				$inp_cls = 'arf_both_pre_suffix';
			} else if ( $field['enable_arf_prefix'] == 1 ){
				$inp_cls = 'arf_prefix_only';
			} else if ( $field['enable_arf_suffix'] == 1 ){
				$inp_cls = 'arf_suffix_only';
			}
			
			if( $field['enable_arf_prefix'] == 1 ){
				echo "<span class='arf_editor_prefix' id='arf_editor_prefix_{$field['id']}'><i class='fa ".$field['arf_prefix_icon']."'></i></span>";
			}
			$input_cls = 'arf_prefix_suffix';
			
		}
	?>
    <input type="text" class="<?php echo $input_cls.' '.$inp_cls; ?>" name="<?php echo $field_name ?>" id="itemmeta_<?php echo $field['id'];?>" onkeyup="arfchangeitemmeta('<?php echo $field['id'];?>');" value="<?php echo esc_attr($field['default_value']); ?>" />

		<?php
		if( $field['enable_arf_prefix'] == 1 or $field['enable_arf_suffix'] == 1 ){
			if( $field['enable_arf_suffix'] == 1 ){
				echo "<span class='arf_editor_suffix' id='arf_editor_suffix_{$field['id']}'><i class='fa ".$field['arf_suffix_icon']."'></i></span>";
			}
			echo "</div>";
		}
		?>
<?php }else if ($field['type'] == 'hidden'){ ?>


    <input type="text" name="<?php echo $field_name ?>" id="itemmeta_<?php echo $field['id'];?>" onkeyup="arfchangeitemmeta('<?php echo $field['id'];?>');" value="<?php echo esc_attr($field['default_value']); ?>"/> 


    <p class="howto clear"><?php _e('Note: This field will not show in the form. Enter the value to be hidden.', 'ARForms') ?></p>


<?php }else if($field['type'] == 'time'){ ?>

<div  style="float:left" id="field_default_hour_<?php echo $field['field_key'] ?>" class="arf_field_default_time_element">
<select name="field_options[default_hour_<?php echo $field['id'] ?>]" id="field_<?php echo $field['field_key'] ?>" >
	<?php 
	for($i=0; $i<=$field['clock']; $i++) {?>
    <option value="<?php echo $i;?>" <?php if($i == $field['default_hour']) { echo "selected=selected";} ?>><?php echo $i; ?></option>
	<?php } ?>
</select>
<br /> <div class="howto">&nbsp;(HH)</div>
</div>

    <div style="float:left" class="arf_field_default_time_element">
<select name="field_options[default_minutes_<?php echo $field['id'] ?>]" id="field_<?php echo $field['field_key'] ?>" >
	<?php for($j=0; $j<=59; $j++) {?>
    <option value="<?php echo $j;?>" <?php if($j == $field['default_minutes']) { echo "selected=selected";} ?>><?php echo $j; ?></option>
	<?php } ?>
</select>
<br /> <div class="howto">&nbsp;(MM)</div>
</div> 

<?php }else if ($field['type'] == 'image'){ ?>

	<?php 
		$input_cls = '';
		$inp_cls = '';
		if( $field['enable_arf_prefix'] == 1 or $field['enable_arf_suffix'] == 1 ){
			echo "<div class='arf_editor_prefix_suffix_wrapper' id='prefix_suffix_wrapper_{$field['id']}'>";
			if( $field['enable_arf_prefix'] == 1 && $field['enable_arf_suffix'] == 1 ){
				$inp_cls = 'arf_both_pre_suffix';
			} else if ( $field['enable_arf_prefix'] == 1 ){
				$inp_cls = 'arf_prefix_only';
			} else if ( $field['enable_arf_suffix'] == 1 ){
				$inp_cls = 'arf_suffix_only';
			}
			
			if( $field['enable_arf_prefix'] == 1 ){
				echo "<span class='arf_editor_prefix' id='arf_editor_prefix_{$field['id']}'><i class='fa ".$field['arf_prefix_icon']."'></i></span>";
			}
			$input_cls = 'arf_prefix_suffix';
			
		}
	?>
    <input type="text" name="<?php echo $field_name ?>" id="itemmeta_<?php echo $field['id'];?>" onkeyup="arfchangeitemmeta('<?php echo $field['id'];?>');" value="<?php echo $field['default_value'] ?>" class="<?php echo $input_cls.' '.$inp_cls; ?>" />
    <?php
		if( $field['enable_arf_prefix'] == 1 or $field['enable_arf_suffix'] == 1 ){
			if( $field['enable_arf_suffix'] == 1 ){
				echo "<span class='arf_editor_suffix' id='arf_editor_suffix_{$field['id']}'><i class='fa ".$field['arf_suffix_icon']."'></i></span>";
			}
			echo "</div>";
		}
	?>

<?php } else if ($field['type'] == 'scale') {

        require(VIEWS_PATH .'/star_rating.php');

} else if ($field['type'] == 'html'){ ?>

<p class="howto clear"><?php _e('Note: Set your custom html content', 'ARForms') ?></p>

<?php }else if ($field['type'] == 'file'){ ?>


    <input type="file" name="<?php echo $field_name ?>" />


<?php }else if($field['type'] == 'form'){


    echo "FORM";


} ?>