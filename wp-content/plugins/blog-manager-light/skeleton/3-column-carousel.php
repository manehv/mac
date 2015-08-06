<!-- Carousel 3 Columns -->
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
<div 
  class="otw_blog_manager-slider otw_blog_manager-carousel <?php echo $caption;?>" 
  id="otw-bm-list-<?php echo $this->listOptions['id'];?>" 
  data-animation="slide" 
  data-item-per-page="3" 
  data-item-margin="20"
  data-nav="<?php echo $this->listOptions['slider_nav'];?>" 
  data-auto-slide="<?php echo $this->listOptions['slider-auto-scroll'];?>">
  <ul class="slides">
    <?php 
      $embededMediaTypes = array('soundcloud', 'vimeo', 'youtube');
      foreach( $otw_bm_posts->posts as $post ):
        $widgetTitleLink = $this->getLink($post, 'title'); 

        $metaBoxInfo = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
        ( !empty( $metaBoxInfo ) )? $postMetaData = $metaBoxInfo : $postMetaData = array('media_type' => '');
    ?>
    <li>
      <?php echo $this->getMedia( $post ); ?>

      <?php if( !empty( $this->listOptions['show-slider-title'] ) && !in_array($postMetaData['media_type'], $embededMediaTypes)) : ?>
      <div class="otw_blog_manager-flex-caption otw_blog_manager-flex-caption--small <?php echo $cationBG;?>">
        
        <h3 class="otw_blog_manager-caption-title otw_blog_manager--small-title" data-item="title">
          <?php if( !empty($widgetTitleLink) ) : ?>
            <a href="<?php echo $widgetTitleLink;?>" class="otw-slider-image"><?php echo $post->post_title;?></a>
          <?php else: ?>
            <?php echo $post->post_title;?>
          <?php endif; ?>
        </h3>
        
      </div> <!-- End Caption -->
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<!-- Carousel 3 Columns -->