<!-- Blog Content -->
<div class="otw_blog_manager-blog-content">
  <p>
    <?php 
      ( !empty( $post->post_excerpt ) )? $postContentFull = $post->post_excerpt : $postContentFull = $post->post_content;
      
      $postContent = $postContentFull;
      if( !empty($this->listOptions['excerpt_length']) ) {
        $postContent = $this->excerptLength( $postContentFull, $this->listOptions['excerpt_length'] );
      }
      echo strip_tags($postContent);
    ?>
  </p>
</div>
<!-- End Blog Content -->