<!-- Widget Content -->
<p class="otw-widget-content">
  <?php 
    ( !empty( $widgetPost->post_excerpt ) )? $postContentFull = $widgetPost->post_excerpt : $postContentFull = $widgetPost->post_content;

    $postContent = $postContentFull;
    if( !empty($this->listOptions['excerpt_length']) ) {
      $postContent = $this->excerptLength( $postContentFull, $this->listOptions['excerpt_length'] );
    } 

    echo $postContent;
  ?>
</p>
<!-- End Widget Content -->