<section class="otw-twentyfour otw-columns" id="otw-bm-list-<?php echo $this->listOptions['id'];?>">

  <?php echo $this->getViewAll(); ?>

  <!-- Blog Timeline Itmes -->
  <div class="otw-row otw_blog_manager-blog-item-holder otw_blog_manager-blog-timeline with-heading bm_clearfix">

  <?php 
    $oldDate = '';
    foreach( $otw_bm_posts->posts as $post ):

  ?>

    <div class="otw-twelve otw-columns otw_blog_manager-blog-timeline-item">
      <?php if ( $oldDate != date('M-Y',strtotime( $post->post_date )) ) : $oldDate = date('M-Y', strtotime( $post->post_date )); ?>
      <h3 class="otw_blog_manager-timeline-title"><?php echo date('M Y', strtotime($post->post_date));?></h3>
      <?php endif;?>
      <article class="otw_blog_manager-blog-full <?php echo $this->containerBG; ?> <?php echo $this->containerBorder; ?>">
        <?php echo $this->buildInterfaceBlogItems( $post ); ?>
        <?php echo $this->getSocial( $post ); ?>
        <?php echo $this->getDelimiter( $post ); ?>
      </article>
    </div>

  <?php 
    endforeach;
  ?>

  </div>
  <!-- End Blog Timeline Itmes -->

<?php 
    $uniqueHash = wp_create_nonce("otw_bm_get_posts_nonce"); 
    $listID = $this->listOptions['id'];
    $page = 2;
    $ajaxURL = admin_url( 'admin-ajax.php?action=get_posts&post_id='. $listID .'&nonce='. $uniqueHash .'&page='. $page );
?>
<!-- Infinite Scroll -->
<div class="otw_blog_manager-pagination hide">
  <a href="<?php echo $ajaxURL;?>">2</a>
</div>
<!-- End Infinite Scroll -->


</section>