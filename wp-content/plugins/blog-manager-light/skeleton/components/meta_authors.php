<!-- Post Author -->
<div class="otw_blog_manager-blog-author">
  <?php if( !$this->listOptions['meta_icons'] ) : ?>
  <span class="head"><?php _e('By:', 'otw_bml');?></span>
  <?php else: ?>
  <span class="head"><i class="icon-user"></i></span>
  <?php endif; ?>

  <a href="<?php echo get_author_posts_url( $post->post_author ); ?>" title="<?php _e('Posts by ', 'otw_bml'); the_author_meta('display_name', $post->post_author);?>" rel="author">
    <?php the_author_meta('display_name', $post->post_author); ?>
  </a>
</div>
<!-- End Post Author -->