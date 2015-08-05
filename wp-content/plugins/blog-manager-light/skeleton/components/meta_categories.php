<?php 
  //Get Categories for Current Post
  $catArray = wp_get_post_categories( $post->ID );
  if( is_array( $catArray ) ) : 
?>
<!-- Post Categories -->
<div class="otw_blog_manager-blog-category">
  <?php if( !$this->listOptions['meta_icons'] ) : ?>
  <span class="head"><?php _e('Category:', 'otw_bml');?></span>
  <?php else: ?>
  <span class="head"><i class="icon-folder-open-alt"></i></span>
  <?php endif; ?>

  <?php 
    foreach( $catArray as $index => $cat ):
      $category = get_category($cat);
      $catUrl = get_category_link( $category->term_id );
  ?>
  <a href="<?php echo esc_url($catUrl);?>" rel="category" title="<?php _e('View all posts in ', 'otw_bml'); echo $category->name;?>">
    <?php echo $category->name;?>
  </a>
  <?php if( $index < count( $catArray ) - 1 ) { echo ', '; }?>
  <?php
    endforeach;
  ?>
</div>
<!-- END Post Categories -->
<?php endif; ?>