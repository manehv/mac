<!-- Content -->
<section class="otw-twentyfour otw-columns" id="otw-bm-list-<?php echo $this->listOptions['id'];?>">
  <!-- OTW ROW -->
  <div class="otw-row">
    
    <!-- Blog 1 Column -->
    <div class="otw-twentyfour otw-columns">
      
      <?php echo $this->getViewAll(); ?>

      <div class="<?php echo $this->getInfiniteScrollGrid();?>">

        <div class="otw-row otw_blog_manager-blog-item-holder">
          <?php  foreach( $otw_bm_posts->posts as $post ): ?>
          <div class="otw-twentyfour otw-columns">
            
            <article class="otw_blog_manager-blog-full <?php echo $this->containerBG; ?> <?php echo $this->containerBorder; ?>">
              <?php echo $this->buildInterfaceBlogItems( $post ); ?>
              <?php echo $this->getSocial( $post ); ?>
              <?php echo $this->getDelimiter( $post ); ?>
            </article>

          </div>
          <?php endforeach; ?>
        </div>

      </div> <!-- End Pagination Holder -->

    </div>
    <!-- End Blog 1 Column -->

  </div>
  <!-- END OTW ROW -->
  
  <?php echo $this->getPagination( $otw_bm_posts ); ?>

</section>
<!-- End Content -->