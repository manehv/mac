<!-- Social Share Buttons -->
<?php 
  ( !empty( $post->post_excerpt ) )? $postContentFull = $post->post_excerpt : $postContentFull = $post->post_content;
  
  $socialLink         = get_permalink($post->ID);
  $socialTitle        = $post->post_title;
  if( !empty( $this->listOptions['excerpt_length'] ) ){
	$socialDescription  = $this->excerptLength( wp_strip_all_tags( $postContentFull ), $this->listOptions['excerpt_length'] );
  }else{
	$socialDescription  = wp_strip_all_tags( $postContentFull );
  }
  $socialAsset        = $this->getPostAsset( $post );

  $class = '';
  if( $this->listOptions['show-social-icons'] == 'share_btn_small' ) {
    $class = 'small-style';
  }
?>
<div 
  class="otw_blog_manager-social-share-buttons-wrapper otw_blog_manager-social-wrapper <?php echo $class;?> bm_clearfix" 
  data-title="<?php echo $socialTitle;?>"
  data-description="<?php echo $socialDescription;?>"
  data-image="<?php echo $socialAsset; ?>"
  data-url="<?php echo $socialLink;?>">

  <?php if( $this->listOptions['show-social-icons'] == 'share_icons' ) : ?>
  <a class="otw_blog_manager-social-item otw-facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo $socialLink;?>"><i class="icon-facebook" href=""></i></a>
  <a class="otw_blog_manager-social-item otw-twitter" target="_blank" href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=<?php echo $socialTitle;?>&amp;url=<?php echo $socialLink;?>"><i class="icon-twitter" href=""></i></a>
  <a class="otw_blog_manager-social-item otw-google_plus" target="_blank" href="https://plus.google.com/share?url=<?php echo $socialLink;?>"><i class="icon-google-plus" href=""></i></a>
  <a class="otw_blog_manager-social-item otw-linkedin" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $socialLink;?>&amp;title=<?php echo $socialTitle;?>&amp;summary=<?php echo $socialDescription;?>"><i class="icon-linkedin" href=""></i></a>
  <a class="otw_blog_manager-social-item otw-pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo $socialLink;?>&amp;media=<?php echo $socialAsset; ?>&amp;description=<?php echo $socialDescription;?>&amp;title=<?php echo $socialTitle;?>"><i class="icon-pinterest" href=""></i></a>
  <?php endif;?>

  <?php if( $this->listOptions['show-social-icons'] == 'share_btn_small' || $this->listOptions['show-social-icons'] == 'share_btn_large') : ?>
  <div class="otw_blog-manager-share-button-boxy">
    <a class="otw_blog-manager-social-share otw-facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo $socialLink;?>"><i class="icon-facebook-sign"></i></a>
  </div>

  <div class="otw_blog-manager-share-button-boxy">
    <a class="otw_blog-manager-social-share otw-twitter" target="_blank" href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=<?php echo $socialTitle;?>&amp;url=<?php echo $socialLink;?>"><i class="icon-twitter-sign"></i></a>
  </div>

  <div class="otw_blog-manager-share-button-boxy">
    <a class="otw_blog-manager-social-share otw-google_plus" target="_blank" href="https://plus.google.com/share?url=<?php echo $socialLink;?>"><i class="icon-google-plus-sign"></i></a>
  </div>

  <div class="otw_blog-manager-share-button-boxy">
    <a class="otw_blog-manager-social-share otw-linkedin" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $socialLink;?>&amp;title=<?php echo $socialTitle;?>&amp;summary=<?php echo $socialDescription;?>"><i class="icon-linkedin-sign"></i></a>
  </div>

  <div class="otw_blog-manager-share-button-boxy">
    <a class="otw_blog-manager-social-share otw-pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo $socialLink;?>&amp;media=<?php echo $socialAsset; ?>&amp;description=<?php echo $socialDescription;?>&amp;title=<?php echo $socialTitle;?>"><i class="icon-pinterest-sign"></i></a>
  </div>
  <?php endif;?>


  <?php if( $this->listOptions['show-social-icons'] == 'like_buttons' ) : ?>
    
  <div class="otw_blog-manager-like-button-boxy">
    <iframe src="//www.facebook.com/plugins/like.php?href=<?php echo $socialLink;?>&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=426590060736305" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
  </div>

  <div class="otw_blog-manager-like-button-boxy">
    <a href="https://twitter.com/share" class="twitter-share-button" data-text="<?php echo $socialTitle;?>" data-url="<?php echo $socialLink;?>">Tweet</a>  
  </div>

  <div class="otw_blog-manager-like-button-boxy">
    <!-- Place this tag where you want the +1 button to render. -->
    <div class="g-plusone" data-size="medium" data-href="<?php echo $socialLink;?>"></div>
    <!-- Scrip has been moved into scripts.js - because JS reponse thru ajax will not fire the inline JS -->
    <!-- “Scripts in the resulting document tree will not be executed, resources referenced will not be 
          loaded and no associated XSLT will be applied.” -->

  </div>
    
  <?php endif;?>


</div>
<!-- End Social Share Buttons  -->