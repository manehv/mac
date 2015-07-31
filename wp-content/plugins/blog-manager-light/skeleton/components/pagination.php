<?php 

if( $this->listOptions['show-pagination'] == 'pagination' && !empty( $this->listOptions['posts_limit_page'] ) ) :
  // If we have more then one Page, show pagination
  if( $otw_bm_posts->max_num_pages > 1 ) :

    $big = 99999;
    $currentPage = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;

    $pagedArgs = array(
      'base'          => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
      'format'        => '?paged=%#%',
      'current'       => max( 1, get_query_var('paged') ),
      'total'         => $otw_bm_posts->max_num_pages,
      'show_all'      => false,
      'end_size'      => 2,
      'mid_size'      => 3,
      'prev_next'     => true,
      'prev_text'     => __('« Previous', 'otw_bml'),
      'next_text'     => __('Next »', 'otw_bml'),
      'type'          => 'array',
      'add_args'      => false,
      'add_fragment'  => ''
    );  

    $pages = paginate_links( $pagedArgs );
?>
<div class="otw-row">
 <div class="otw-twentyfour otw-columns">
    <div class="otw_blog_manager-pagination">
      <span class="pages">
        <?php
          _e('Page', 'otw_bml');
          echo ' '.$currentPage.' ';
          _e('of', 'otw_bml');
          echo ' '.$otw_bm_posts->max_num_pages;
        ?>
      </span>
      <?php 
        foreach( $pages as $page ): 
          echo $page;
        endforeach;
      ?>
    </div>
  </div>
</div>
<?php 
  endif;
endif;
?>

<!-- Load More Pagination -->
<?php 
  $newsArray = array( '2-column-news', '3-column-news', '4-column-news', '1-3-mosaic', '1-4-mosaic' );

  $paginationClass = 'otw_blog_manager-pagination';
  $paginationLoadMore = 'otw_blog_manager-load-more';

  if( in_array( $this->listOptions['template'], $newsArray ) ) {
    $paginationClass = 'otw_blog_manager-load-more-newspapper';
    $paginationLoadMore = 'otw_blog_manager-load-more-newspapper';
  }
  
  if( $this->listOptions['show-pagination'] == 'load-more' && !empty( $this->listOptions['posts_limit_page'] ) ) :

    $uniqueHash = wp_create_nonce("otw_bm_get_posts_nonce"); 
    $listID = $this->listOptions['id'];
    // $paginationPage is set from the otw_blog_manager.php
    ( !isset($paginationPage) )? $page = 2 : $page = $paginationPage;

    $ajaxURL = admin_url( 'admin-ajax.php?action=get_posts&post_id='. $listID .'&nonce='. $uniqueHash .'&page='. $page );
?>
<div class="otw-row">
 <div class="otw-twentyfour otw-columns">

    <div class="js-pagination_container">
      <div class="<?php echo $paginationClass;?> hide">
        <a href="<?php echo $ajaxURL;?>" class="js-pagination-no"><?php echo $page;?></a>
      </div>
      <div class="<?php echo $paginationLoadMore;?> js-otw_blog_manager-load-more">
        <a href="<?php echo $ajaxURL;?>" data-empty="No more posts to load." data-isotope="true"><?php _e('Load More...', 'otw_bml');?></a>
      </div>
    </div> <!-- End Pagination -->

  </div><!-- End Cols -->
</div><!-- End Rows -->

<?php endif; ?>
<!-- End Load More Pagination -->

<?php 
  if( $this->listOptions['show-pagination'] == 'infinit-scroll' && !empty( $this->listOptions['posts_limit_page'] )) : 
    $uniqueHash = wp_create_nonce("otw_bm_get_posts_nonce"); 
    $listID = $this->listOptions['id'];

    // $paginationPage is set from the otw_blog_manager.php
    ( !isset($paginationPage) )? $page = 2 : $page = $paginationPage;
    $ajaxURL = admin_url( 'admin-ajax.php?action=get_posts&post_id='. $listID .'&nonce='. $uniqueHash .'&page='. $page );
?>

<!-- Infinite Scroll -->
<div class="otw_blog_manager-pagination hide">
  <a href="<?php echo $ajaxURL;?>" data-empty="No more posts to load." data-isotope="true">2</a>
</div>
<!-- End Infinite Scroll -->
<?php endif; ?>