<!-- Blog Media -->
<?php
  $imageHover = $this->listOptions['image_hover'];

  $sliderTemplates = array(
    '3-column-carousel', 
    '4-column-carousel', 
    '5-column-carousel',
    '2-column-carousel-wid',
    '3-column-carousel-wid',
    '4-column-carousel-wid',
    'slider',
    // 'widget-top',
    // 'widget-lft',
    // 'widget-rght',
    '1-3-mosaic',
    '1-4-mosaic'
  );

  // Do not show hover for Sliders, Widget Carousel and Carousel
  if( in_array($this->listOptions['template'], $sliderTemplates) ) {
    $imageHover = '';
  }
?>
<div class="otw_blog_manager-blog-media-wrapper <?php echo $this->mediaContainer;?> <?php echo $imageHover;?>">
<?php
$hoverIconSupported = array(
  'hover-style-4-slidetop',
  'hover-style-5-slideright',
  'hover-style-8-slidedown',
  'hover-style-9-slideleft'
);

$icon = 'icon-camera';
$iconHover = '';
$imgLink = $this->getLink( $post, 'media' );
  
if( in_array($this->listOptions['image_hover'], $hoverIconSupported) && $this->listOptions['icon_hover'] ) {
  // We do show and Icon and the Image Hover Selected is supported
  $iconHover = '<span class="theHoverBorder"><i class="'.$this->listOptions['icon_hover'].'"></i></span>';
} elseif ( $this->listOptions['image_hover'] ) {
  // We do not show an Hover Icon but the Image Hover is supported
  $iconHover = '<span class="theHoverBorder"></span>';
}

// Do not show hover for Sliders, Widget Carousel and Carousel
if( in_array($this->listOptions['template'], $sliderTemplates) ) {
  $iconHover = '';
}

if( !isset( $this->imageWidth ) || !strlen( $this->imageWidth ) ) { $this->imageWidth = 250; }
if( !isset( $this->imageHeight ) || !strlen( $this->imageHeight ) ) { $this->imageHeight = 150; }

// If we have custom POST Meta info, display it, otherwise use featured image
switch( $postMetaData['media_type'] ) {

  case 'youtube':
    // Custom YouTube URL has been added to the post using OTW BM Custom Post Type
    $icon = 'icon-facetime-video';
    $media_item = $this->otwImageCrop->embed_resize( wp_oembed_get($postMetaData['youtube_url'], array('width' => $this->imageWidth)), $this->imageWidth, $this->imageHeight, $this->imageCrop );
  break;


  case 'vimeo':
    // Custom Vimeo URL has been added to the post using OTW BM Custom Post Type
    $icon = 'icon-facetime-video';
    $media_item = $this->otwImageCrop->embed_resize( wp_oembed_get($postMetaData['vimeo_url'], array('width' => $this->imageWidth)), $this->imageWidth, $this->imageHeight, $this->imageCrop );
  break;


  case 'soundcloud':
    // Custom SoundCloud URL has been added to the post using OTW BM Custom Post Type
    $icon = 'icon-music';
    $media_item = $this->otwImageCrop->embed_resize( wp_oembed_get($postMetaData['soundcloud_url'], array('width' => $this->imageWidth, 'height' => 166 )), $this->imageWidth, $this->imageHeight, $this->imageCrop );
  break;

  case 'slider':
    // Custom Slider (Images) has been added to the post using OTW BM Custom Post Type
    $mainSlider = array(
      '3-column-carousel', '4-column-carousel', '5-column-carousel', 
      '1-3-mosaic', '1-4-mosaic', 
      'horizontal-layout', 'slider',
      'widget-top', 'widget-lft', 'widget-rght'
    );
    
    if( !in_array( $this->listOptions['template'], $mainSlider ) ) {

      $icon = 'icon-picture';
      $sliderImages = explode(',', $postMetaData['slider_url']);
      $media_item = '<div class="flex-viewport" data-animation="slide"> <ul class="slides">';
      foreach( $sliderImages as $sliderImage ):
        $imagePath = parse_url($sliderImage);
        $media_item .= '
        <li>
          <img src="'.$this->otwImageCrop->resize( $imagePath['path'], $this->imageWidth, $this->imageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground ).'" alt="" data-item="media">
        </li>';
      endforeach;
      $media_item .= '</ul></div>';

    } else {

      $sliderImages = explode(',', $postMetaData['slider_url']);
      $imagePath = parse_url($sliderImages[0]);
      // We have the Carousel template selected, and within it we have slider meta box
      if( !empty($imgLink) ) {

        $media_item = '
        <a href="'. $imgLink .'" class="otw-slider-image otw-media-container" data-width="'.$this->imageWidth.'">
          <img src="'.$this->otwImageCrop->resize( $imagePath['path'], $this->imageWidth, $this->imageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground ).'" alt="">
          '.$iconHover.'
        </a>';

      } else {

        $media_item = '
          <span class="otw-media-container">
            <img src="'.$this->otwImageCrop->resize( $imagePath['path'], $this->imageWidth, $this->imageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground ).'" alt="" data-item="media" data-width="'.$this->imageWidth.'">
            '.$iconHover.'
          </span>
        ';

      } // End Link Selection

    }
  break;


  case 'img':
    // Custom Image has been added to the post using OTW BM Custom Post Type
    $imagePath = parse_url($postMetaData['img_url']);
    if( !empty($imgLink) ) {
      $media_item = '
      <a href="'. $imgLink .'" class="otw-media-container" data-width="'.$this->imageWidth.'">
        <img src="'.$this->otwImageCrop->resize( $imagePath['path'], $this->imageWidth, $this->imageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground ).'" alt="">
        '.$iconHover.'
      </a>';
    } else {
      $media_item = '
        <span class="otw-media-container">
          <img src="'.$this->otwImageCrop->resize( $imagePath['path'], $this->imageWidth, $this->imageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground ).'" alt="">
          '.$iconHover.'
        </span>
      ';
    }
  break;


  case 'wp-native';
    // Featured Image has been used for this post.
    $imagePath = parse_url($postMetaData['featured_img']);
    if( !empty($imgLink) ) {
      $media_item = '
      <a href="'. $imgLink .'" class="otw-media-container" data-width="'.$this->imageWidth.'">
        <img src="'.$this->otwImageCrop->resize( $imagePath['path'], $this->imageWidth, $this->imageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground ).'" alt="">
        '.$iconHover.'
      </a>';
    } else {
      $media_item = '
        <span class="otw-media-container">
          <img src="'.$this->otwImageCrop->resize( $imagePath['path'], $this->imageWidth, $this->imageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground ).'" alt="">
          '.$iconHover.'
        </span>
      ';
    }
  break;
}

echo $media_item;
?>
  <?php if( !empty( $this->listOptions['show-post-icon'] ) ): ?>
  <!-- Blog Type -->
  <div class="otw_blog_manager-blog-type">
    <i class="<?php echo $icon;?>"></i>
  </div>
  <!-- End Blog Type -->
  <?php endif; ?>
</div>
<!-- End Blog Media -->