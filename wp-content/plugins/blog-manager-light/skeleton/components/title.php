<!-- Blog Title -->
<div class="otw_blog_manager-blog-title-wrapper otw_blog_manager-format-image">
  <?php 
    $titleLink = $this->getLink($post, 'title'); 
    if( !empty($titleLink) ) :
  ?>
  <h3 class="otw_blog_manager-blog-title"><a href="<?php echo $titleLink;?>"><?php echo $post->post_title;?></a></h3>
  <?php else: ?>
  <h3 class="otw_blog_manager-blog-title"><?php echo $post->post_title;?></h3>
  <?php endif; ?>
</div>
<!-- End Blog Title -->