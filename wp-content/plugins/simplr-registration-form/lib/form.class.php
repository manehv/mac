<?php
/**
**
** Form Class
**	-This class contains what are essentially helper functions to make form building a little easier and more consistent. 
**
**/

class SREG_Form {
	
	// static functions that handle form fields
	
	static function text($option, $vals, $class = 'wide') { 
	?>	
		<div class="option-field <?php echo apply_filters($option['name'].'_error_class',''); ?>">
			<label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
			<input type="text" name="<?php echo $option['name']; ?>" id="<?php echo $option['name']; ?>" value="<?php echo esc_attr($vals); ?>" class="<?php echo @$class; ?> <?php echo @$class; ?>"/>	
			<?php if(isset($option['comment'])) { echo '<div class="form-comment">'.$option['comment'].'</div>'; } ?>
		</div>
		<div class="simplr-clr"></div>
	<?php
	}
	
	static function hidden($option, $vals, $class, $options_array) { ?>
		<?php $vals = ($options_array) ? $options_array[0] : $vals; ?>
		<input type="hidden" name="<?php echo $option['name']; ?>" id="<?php echo $option['name']; ?>" value="<?php echo esc_attr($vals); ?>"/>	
	<?php } 
	
	static function select($option, $vals, $class, $options_array) { ?>
	<div class="option-field select <?php echo apply_filters($option['name'].'_error_class',''); ?>">
	<?php $vals = (!is_array($vals)) ? array($vals) : $vals; ?>
	<label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
		<select name="<?php echo $option['name']; ?>" class="<?php echo @$class; ?>" <?php if(@$option['multiple']) { echo 'multiple'; } ?>>
			<option value="">Select ... </option>
			<?php $options_array = (!$options_array) ? SREG_Form::helper($option['helper']) : $options_array; ?>
			<?php foreach($options_array as $k => $v): ?>
				<?php if(is_numeric($k)) { $k = $v; } ?>
 				<?php if(!empty($vals)) { ?>
 					<?php $sel = (in_array($k,$vals)) ? 'selected' : ''; ?>
 				<?php } else { ?>
 					<?php $sel = ($k == $option['default'])?'selected':''; ?>
 				<?php } ?>
				<option value="<?php echo $k; ?>" <?php echo  $sel; ?>><?php echo $v; ?></option>
			<?php endforeach; ?>
		</select>
		<?php if(isset($option['comment'])) { echo '<div class="form-comment">'.$option['comment'].'</div>'; } ?>
	</div>
 	<div class="simplr-clr"></div>
	<?php
	}
	
	static function textarea($option, $vals, $class, $size) { ?>
	<?php if(@$option['tiny_mce'] == true) { $div_id = 'markItUp'; } else { $div_id = $option['name']; } ?>
	<?php $size = (!empty($size)) ? $size : $option['size']; ?>
	<div class="option-field textarea <?php echo apply_filters($option['name'].'_error_class',''); ?>">
		<label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
		<textarea name="<?php echo $option['name']; ?>" id="<?php echo $div_id; ?>" value="" style="width:<?php echo $size[0]; ?>; height: <?php echo $size[1]; ?>;" class="<?php echo $class; ?>"><?php echo esc_attr($vals); ?></textarea> <?php if(isset($option['comment'])): ?><div class="comment"><?php echo $option['comment']; ?></div><?php endif; ?>
	</div>
	<div class="simplr-clr"></div>
	<?php
	}
	
	static function related_select($option, $vals, $class) { ?>
	<div class="option-field page_select <?php echo apply_filters($option['name'].'_error_class',''); ?>">
	<label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
		<select name="<?php echo $option['name']; ?>" class="<?php echo $class; ?>">
			<option value="">Select <?php echo @$option['options']['post_type']; ?>... </option>
			<?php $posts = get_posts(array('post_type'=>$option['post_type'],'orderby'=>'post_title','order'=>'ASC','showposts'=>-1)); ?>
			<?php foreach($posts as $post): ?>
				<?php if(!empty($vals)) { ?><?php $sel = ($post->ID == $vals) ? 'selected' : ''; ?><?php } ?>
				<option value="<?php echo $post->ID; ?>" <?php echo $sel; ?>><?php echo $post->post_title; ?></option>
			<?php endforeach; ?>
		</select>
			<?php if(isset($option['comment'])) { echo '<div class="form-comment">'.$option['comment'].'</div>'; } ?>
	</div>
	<div class="simplr-clr"></div>
	<?php
	}
	
	static function checkbox($option, $vals, $class) {
		?>
		<div class="option-field checkbox <?php echo apply_filters($option['name'].'_error_class',''); ?>">
		<?php $sel = ('on' == $vals)?'checked':''; ?>
		<label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
			<input type="checkbox" name="<?php echo $option['name']; ?>" id="<?php echo $option['name']; ?>" value="on" class="<?php echo @$class; ?>" <?php echo $sel; ?>/>	
		<?php if(isset($option['comment'])) { echo '<div class="form-comment">'.$option['comment'].'</div>'; } ?>
		</div>
		<div class="simplr-clr"></div>
		<?php
	}
	
	static function checkbox_group($option,$vals,$class,$options_array) {
		?>
		<?php $vals = (!is_array($vals)) ? array($vals) : $vals; ?>
		<div class="option-field checkgroup">
		<label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
			<div class="checkbox-wrapper">
				<?php $options_array = (!$options_array) ? SREG_Form::helper($option['helper']) : $options_array; ?>
				<?php foreach($options_array as $k => $v): ?>
					<div class="perm"><input type="checkbox" name="<?php echo $option['name']; ?>[]" value="<?php echo esc_attr($k); ?>" class="checkbox" <?php echo (in_array($k,$vals))? 'checked': ''; ?>> <?php echo $v; ?></input></div>
					<?php endforeach; ?>
			</div>
		</div>
		<div class="simplr-clr"></div>
		<?php
	}
	
	static function media_uploader($option, $vals, $class) { ?>
	<script>
		jQuery.noConflict(); 
		jQuery.get('<?php echo BSD_URL; ?>/library/js/options.js'); 
	</script>
	<div class="option-field page_select <?php echo apply_filters($option['name'].'_error_class',''); ?>">
			<div id="preview" style="<?php if(!$vals) { ?>display:none;<?php } ?>"><?php if($vals) { ?><img src="<?php echo $vals; ?>"/><?php } ?></div>
			<input id="upload_image" type="text" size="36" name="<?php echo $option['name']; ?>" value="<?php echo esc_attr($vals); ?>" class="<?php echo @$class; ?> <?php echo $class; ?>"/>
			<input id="upload_image_button" class="button" type="button" value="Upload Image" />
			<span class="comment">Enter a URL or upload an image for your custom logo.</span>
	</div>
	<div class="simplr-clr"></div>
	<?
	}
	
	static function category_select($option, $vals, $class) { ?>
		<div class="option-field select <?php echo apply_filters($option['name'].'_error_class',''); ?>">
	<label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
		<?php 
		wp_dropdown_categories(array(
			'name' => $option['name'], 
			'id'=> $option['name'] , 
			'hide_empty'=> 0,
			'show_count'=>1,
			'selected' =>$vals,
			'taxonomy' => $option['options']['taxonomy'])
			); 
		?>
		<?php if(isset($option['comment'])) : ?><span class="comment"><?php echo $option['comment']; ?></span><?php endif; ?>
	</div>
	<div class="simplr-clr"></div>
	<?php
	}
	
	static function radio($option, $vals, $class,$options_array) {
		$default = (isset($option['default'])) ? $option['default'] : '';
		$vals = ($vals != '') ? array($vals) : array($default) ;
		?>
		<div class="option-field radio <?php echo apply_filters($option['name'].'_error_class',''); ?>">
		<?php $vals = (!is_array($vals)) ? array($vals) : $vals; ?>
		<label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
			<?php foreach($options_array as $k => $v): ?>
			<?php if(is_numeric($k)) { $k = $v; } ?>
				<?php $sel = (in_array($k,$vals)) ? 'checked' : ''; ?>
				<?php echo $v; ?> <input type="radio" name="<?php echo $option['name']; ?>" value="<?php echo esc_attr($k); ?>" <?php echo $sel; ?>/>
			<?php endforeach; ?>
		</div>
		<div class="simplr-clr"></div>
		<?php
	}
	
	static function date($option, $vals, $class, $options_array) { 
		//set up variables
		$list = $vals; 
		if(!empty($list)) {
			$list = explode('-',$list);
			$lmo = $list[1];
			$ldy = $list[2];
			$lyr = $list[0];
		}
		$years = $options_array;
		?>
		<div class="option-field date <?php echo apply_filters($option['name'].'_error_class',''); ?>">
		<label for="<?php echo $option['name']; ?>"><?php echo $option['label'] . show_required($option); ?></label>
		<div class="simplr-clr"></div>
		<select name="<?php echo $option['name']. '-mo'; ?>" id="<?php echo $option['name']. '-mo'; ?>" class="<?php echo @$class; ?>">
		<option value="">Select Month ...</option>
		<option value="01" <?php if($lmo == '01') {echo 'selected'; } ?>>Jan</option>
		<option value="02" <?php if($lmo == '02') {echo 'selected'; } ?>>Feb</option>
		<option value="03" <?php if($lmo == '03') {echo 'selected'; } ?>>Mar</option>
		<option value="04" <?php if($lmo == '04') {echo 'selected'; } ?>>Apr</option>
		<option value="05" <?php if($lmo == '05') {echo 'selected'; } ?>>May</option>
		<option value="06" <?php if($lmo == '06') {echo 'selected'; } ?>>Jun</option>
		<option value="07" <?php if($lmo == '07') {echo 'selected'; } ?>>Jul</option>
		<option value="08" <?php if($lmo == '08') {echo 'selected'; } ?>>Aug</option>
		<option value="09" <?php if($lmo == '09') {echo 'selected'; } ?>>Sep</option>
		<option value="10" <?php if($lmo == '10') {echo 'selected'; } ?>>Oct</option>
		<option value="11" <?php if($lmo == '11') {echo 'selected'; } ?>>Nov</option>
		<option value="12" <?php if($lmo == '12') {echo 'selected'; } ?>>Dec</option>
		</select>
		<select name="<?php echo $option['name'] . '-dy'; ?>" id="<?php echo $option['name']. '-dy'; ?>" class="<?php echo @$class; ?>">
		<option value="">Select Day ...</option>
		<?php $i = 1; while($i <= 31) {
		if($i == "$ldy") { $selected = 'selected'; } else { $selected = '';}
		if($i < 10) { 
		$y = sprintf('%02d',$i); 
		echo '<option value="' .$y .'" ' .$selected .'>'.$y.'</option>';
		} else {
		echo '<option value="'.$i .'" ' .$selected .'>' .$i .'</option>'; 
		}
		$i++;
		} 
		?>
		</select>
		<select name="<?php echo $option['name'] . '-yr'; ?>" id="<?php echo $option['name']. '-yr'; ?>" class="<?php echo @$class; ?>">
		<option value="">Select Year ...</option>
		<?php $i = $years[0]; while($i >= $years[0] && $i <= $years[1]) { 
		if($i == "$lyr") { $selected = 'selected'; } else { $selected = '';}
		echo '<option value="'.$i .'" ' .$selected .'>' .$i .'</option>'; $i++;} ?>
		</select>
		</div>
		<div class="simplr-clr"></div>
		<?php
	}
	
	static function callback($option, $vals, $class, $args) {
		if(empty($args) OR !function_exists( $args[0] ) ) {
			return true;
		} else {
			@call_user_func_array($args[0], $args[1]);	
		}
	}
	
	static function helper($name) {
	 	include(dirname(__FILE__).'/helpers/'.$name .'.php');
	 	return ${$name};
	}
	
	static function submit($args = array()) {
		$defaults = array(
			'name'=>'submit',
			'style'=>'',
			'id'=>'submit',
			'class'=>'button-primary',
			'value'=>'Submit'
		);
		$args = array_merge($defaults,$args);
 		extract($args);
		?>
		<p>
		<input type="submit" class="<?php echo $class; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo esc_attr($value); ?>" style="<?php echo $style; ?>" />
		</p>
		<?php
	}	
	
}

//misc 
function show_required($option) {
		if(@$option['required'] == 'yes') {
			$out = '<span class="required">*</span>';
		} else {
			$out = '';
		}
		return $out;
}

?>
