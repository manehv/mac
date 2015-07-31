<?php 
  ( !empty( $this->listOptions['show-slider-title'] ) )? $caption = 'has-caption' : $caption = '';
  ( !empty( $this->listOptions['slider_border'] ) )? $caption .= ' with-border' : $caption = $caption;
  ( !empty( $this->listOptions['slider_title_bg'] ) )? $cationBG = 'with-bg' : $cationBG = '';
  
	if( !empty( $this->listOptions['slider_title_alignment'] ) ){
		switch ( $this->listOptions['slider_title_alignment'] ) {
			case 'center':
					$caption .= ' caption-center';
				break;
			case 'right':
					$caption .= ' caption-right';
				break;
			default:
					$caption .= ' caption-left';
			break;
		 }
	}
?>

<section class="otw-twentyfour otw-columns" id="otw-bm-list-<?php echo $this->listOptions['id'];?>">
  
<!-- Slider without title & excpert -->
<div 
  class="otw_blog_manager-slider <?php echo $caption;?>"
  data-animation="slide"
  data-item-per-page="1"
  data-item-margin=""
  data-nav="<?php echo $this->listOptions['slider_nav'];?>"
  data-auto-slide="<?php echo $this->listOptions['slider-auto-scroll'];?>"
  > 
  <ul class="slides">
    <?php 
      $embededMediaTypes = array('soundcloud', 'vimeo', 'youtube');

      foreach( $otw_bm_posts->posts as $post ): 
        $postAsset  = $this->getPostAsset( $post );
        $asset      = parse_url( $postAsset );

        $metaBoxInfo = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
        ( !empty( $metaBoxInfo ) )? $postMetaData = $metaBoxInfo : $postMetaData = array('media_type' => '');

        $widgetPostLink = $this->getLink($post, 'media');
        $widgetTitleLink = $this->getLink($post, 'title');
    ?>
    <li>
      <?php echo $this->getMedia( $post ); ?>

      <?php if( !empty( $this->listOptions['show-slider-title'] ) && !in_array($postMetaData['media_type'], $embededMediaTypes)) : ?>
      <div class="otw_blog_manager-flex-caption otw_blog_manager-format-gallery <?php echo $cationBG;?>">
        
        <h3 class="otw_blog_manager-caption-title" data-item="title">
          <?php if( !empty($widgetTitleLink) ) : ?>
            <a href="<?php echo $widgetTitleLink;?>" class="otw-slider-image"><?php echo $post->post_title;?></a>
          <?php else: ?>
            <?php echo $post->post_title;?>
          <?php endif; ?>
        </h3>

        <div class="otw_blog_manager-caption-excpert">
          <?php 
            ( !empty( $post->post_excerpt ) )? $postContentFull = $post->post_excerpt : $postContentFull = $post->post_content;

            $postContent = $postContentFull;
            if( !empty($this->listOptions['excerpt_length']) ) {
              $postContent = $this->excerptLength( $postContentFull, $this->listOptions['excerpt_length'] );
            }
            echo strip_tags($postContent);
          ?>
        </div>

      </div> <!-- End Caption -->
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<!-- End Slider without title & excpert -->
</section>