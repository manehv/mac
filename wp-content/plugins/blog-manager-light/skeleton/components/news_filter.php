<!-- Blog Newspaper Filter -->
<div class="otw-row">
  <div class="otw-twentyfour otw-columns">

    <div class="otw_blog_manager-blog-newspaper-filter">
      <ul class="option-set otw_blog_manager-blog-filter bm_clearfix">
        <li><a href="#" data-filter="*" class="selected">All</a></li>
        <?php 
          $filterCategories = explode(',', $this->listOptions['categories']);
          foreach( $filterCategories as $filterCategory ):
            $cat = get_category( $filterCategory );
        ?>
        <li><a href="#" data-filter=".<?php echo $cat->slug;?>"><?php echo $cat->name;?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>
</div>
<!-- End Blog Newspaper Filter -->