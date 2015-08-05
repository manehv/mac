<div class="otw_blog_manager-blog-content">
<a href="<?php echo get_permalink($post->ID);?>" class="otw_blog_manager-blog-continue-reading">
  <?php 
    (!empty($this->listOptions['continue_reading']))?  $read_link = $this->listOptions['continue_reading'] : $read_link = _e('Continue reading →', 'otw_bml');
    echo $read_link;
  ?>
</a>
</div>