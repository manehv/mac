<!-- Blog 2 Columns -->
<section class="otw-twentyfour otw-columns otw_blog_manager-infinite-scroll" id="otw-bm-list-<?php echo $this->listOptions['id'];?>">

  <?php echo $this->getViewAll(); ?>

  <div class="<?php echo $this->getInfiniteScrollGrid();?>">
  <?php 
    $items = array_chunk( $otw_bm_posts->posts , 2);
    foreach( $items as $postItem ):
      echo '<div class="otw-row otw_blog_manager-blog-item-holder">';
      foreach( $postItem as $index => $post ):

        $endClass = '';
        if( $index == count($postItem)-1 ) {
          $endClass = ' end';
        }
      ?>
      <div class="otw-twelve otw-columns <?php echo $endClass; ?>">

        <article class="otw_blog_manager-blog-full <?php echo $this->containerBG; ?> <?php echo $this->containerBorder; ?>">
          <?php echo $this->buildInterfaceBlogItems( $post ); ?>
          <?php echo $this->getSocial( $post ); ?>
          <?php echo $this->getDelimiter( $post ); ?>
        </article>
      </div>
      <?php
      endforeach;
      echo '</div>';
    endforeach;
  ?>
  </div>

  <?php echo $this->getPagination( $otw_bm_posts ); ?>

</section>
<!-- End Blog 2 Columns -->