<?php
	$selectOptionData = array(
		array(
			'value' => 0,
			'text'	=> '------'
		),
		array(
			'value'	=> 'youtube',
			'text'	=> __('YouTube', 'otw_bml')
		),
		array(
			'value'	=> 'vimeo',
			'text'	=> __('Vimeo', 'otw_bml')
		),
		array(
			'value'	=> 'soundcloud',
			'text'	=> __('Sound Cloud', 'otw_bml')
		),
		// array(
		// 	'value'	=> 'media',
		// 	'text'	=> __('Video', 'otw_bml')
		// ),
		// array(
		// 	'value'	=> 'audio',
		// 	'text'	=> __('Audio', 'otw_bml')
		// ),

		array(
			'value'	=> 'img',
			'text'	=> __('Image', 'otw_bml')
		),
		array(
			'value'	=> 'slider',
			'text'	=> __('Slider', 'otw_bml')
		)
	);

	$imgTag 	= ''; // Image placeholder
	$img 			= 'otw-admin-hidden';
	$youtube 	= 'otw-admin-hidden';
	$vimeo 		= 'otw-admin-hidden';
	$soundcloud = 'otw-admin-hidden';
	$slider 	= 'otw-admin-hidden';
	$media 		= 'otw-admin-hidden';
	$audio 		= 'otw-admin-hidden';

	// Selected Media Type (YouTube, Vimeo, SoundCloud, Image, Slider (multiple images), Audio (WP uploaded audio), Video)
	( !empty( $otw_bm_meta_data['media_type'] ) )? $media_type = $otw_bm_meta_data['media_type'] : $media_type = 0;

	// Single Image Url
	( !empty( $otw_bm_meta_data['img_url'] ) )? $img_url = $otw_bm_meta_data['img_url'] : $img_url = '';

	// YouTube URL
	( !empty( $otw_bm_meta_data['youtube_url'] ) )? $youtube_url = $otw_bm_meta_data['youtube_url'] : $youtube_url = '';

	// Vimeo URL
	( !empty( $otw_bm_meta_data['vimeo_url'] ) )? $vimeo_url = $otw_bm_meta_data['vimeo_url'] : $vimeo_url = '';

	// SoundCloud URL
	( !empty( $otw_bm_meta_data['soundcloud_url'] ) )? $soundcloud_url = $otw_bm_meta_data['soundcloud_url'] : $soundcloud_url = '';

	// Uploaded Video URL - WP MP4
	( !empty( $otw_bm_meta_data['video_url'] ) )? $video_url = $otw_bm_meta_data['video_url'] : $video_url = '';

	// Uploaded Audio URL - WP MP3
	( !empty( $otw_bm_meta_data['audio_url'] ) )? $audio_url = $otw_bm_meta_data['audio_url'] : $audio_url = '';

	// ( !empty( $otw_bm_meta_data['video_url'] ) )? $video_url = $otw_bm_meta_data['video_url'] : $video_url = '';
	// ( !empty( $otw_bm_meta_data['media_url'] ) )? $media_url = $otw_bm_meta_data['media_url'] : $media_url = '';
	

	if( !empty( $otw_bm_meta_data['slider_url'] ) ) {
		$slider_url = $otw_bm_meta_data['slider_url'];
		$slider_imgs = explode(',', $slider_url);
	} else {
		$slider_url = '';
		$slider_imgs = array();
	}

	switch( $media_type ) {

		case ($media_type == 'img') :
			$img = '';
			$imgTag = '<img src="'.$img_url.'" width="150" />';
		break;
		case ( $media_type == 'youtube' ) :
			$youtube = '';
		break;
		case ( $media_type == 'vimeo' ) :
			$vimeo = '';
		break;
		case ( $media_type == 'soundcloud' ) :
			$soundcloud = '';
		break;
		case ( $media_type == 'slider' ) :
			$slider = '';
		break;

	}
?>
<table class="form-table">
	<tbody>
		<!-- Select Drop Down -->
		<tr valign="top">
			<th scope="row"><label for="media_type"><?php _e('Choose Media Type', 'otw_bml');?></label></th>
			<td>
				<select id="media_type" name="otw-bm-list-media_type" class="js-otw-media-type">
					<?php 
					foreach( $selectOptionData as $optionData ): 
						$selected = '';
						if( $optionData['value'] === $media_type ) {
							$selected = 'selected="selected"';
						}
						echo "<option value=\"".$optionData['value']."\" ".$selected.">".$optionData['text']."</option>";
						
					endforeach;
					?>	
				</select>
			</td>
		</tr>
		<!-- Select Drop Down -->

		<!-- YouTube URL -->
		<tr valign="top" class="js-meta-youtube <?php echo $youtube;?>">
			<th scope="row"><label for="youtube_url"><?php _e('Enter YouTube URL', 'otw_bml');?></label></th>
			<td>
				<input type="text" id="youtube_url" name="otw-bm-list-youtube_url" class="js-otw-youtube-url" value="<?php echo $youtube_url; ?>" size="53"/>
			</td>
		</tr>
		<!-- YouTube URL -->

		<!-- Vimeo URL -->
		<tr valign="top" class="js-meta-vimeo <?php echo $vimeo;?>">
			<th scope="row"><label for="vimeo_url"><?php _e('Enter Viemo URL', 'otw_bml');?></label></th>
			<td>
				<input type="text" id="vimeo_url" name="otw-bm-list-vimeo_url" class="js-otw-vimeo-url" value="<?php echo $vimeo_url; ?>" size="53"/>
			</td>
		</tr>
		<!-- Vimeo URL -->

		<!-- ScoundCloud URL -->
		<tr valign="top" class="js-meta-soundcloud <?php echo $soundcloud;?>">
			<th scope="row"><label for="soundcloud_url"><?php _e('Enter SoundCloud URL', 'otw_bml');?></label></th>
			<td>
				<input type="text" id="soundcloud_url" name="otw-bm-list-soundcloud_url" class="js-otw-soundcloud-url" value="<?php echo $soundcloud_url; ?>" size="53"/>
			</td>
		</tr>
		<!-- ScoundCloud URL -->

<!-- 		<tr valign="top" class="js-meta-media <?php echo $media_display;?>">
			<th scope="row"><label for="media_url"><?php _e('Select Video File', 'otw_bml');?></label></th>
			<td>
				<input type="hidden" id="media_url" name="otw-bm-list-media_url" class="js-otw-media-url" value="<?php echo $media_url; ?>" size="53"/>
				<a href="#" class="js-add-media"><?php _e('Select Audio File', 'otw_bml');?></a>
				<p class="description"><?php _e('Note: At this time WordPress will support only MP4 files for native embededing', 'otw_bml');?></p>
			</td>
		</tr> -->

		<!-- Single Image -->
		<tr valign="top" class="js-meta-image <?php echo $img;?>">
			<th scope="row"><label for="img_upload"><?php _e('Select File', 'otw_bml');?></label></th>
			<td>
				<a href="#" class="js-add-image"><?php _e('Add File', 'otw_bml');?></a>
				<input type="hidden" name="otw-bm-list-img_url" class="js-img-url" value="<?php echo $img_url; ?>" />
				<div class="js-img-preview"><?php echo $imgTag;?></div>
			</td>
		</tr>
		<!-- Single Image -->

		<!-- Slider -->
		<tr valign="top" class="js-meta-slider <?php echo $slider;?>">
			<th scope="row"><label for="slider"><?php _e('Slider Images', 'otw_bml');?></label></th>
			<td>
				<a href="#" class="js-add-image left"><?php _e('Add File', 'otw_bml');?></a><br/>
				<input type="hidden" name="otw-bm-list-slider_url" class="js-img-slider-url" value="<?php echo $slider_url; ?>" size="53"/>
				<!-- Preview Items will be appended here -->
				<ul class="b-slider-preview left js-meta-slider-preview">
					<?php 

					foreach( $slider_imgs as $image ):
						?>
						<li class="b-slider__item" data-src="<?php echo $image ?>">
							<a href="#" class="b-delete_btn"></a>
							<img src="<?php echo $image ?>" width="100" />
						</li>
						<?php
					endforeach;
					?>
				</ul>
			</td>
		</tr>
		<!-- Slider -->

	</tbody>
	
</table>