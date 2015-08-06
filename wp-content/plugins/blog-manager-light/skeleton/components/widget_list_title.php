<?php if( !empty($this->listOptions['blog_list_title']) ) : ?>
<!-- Widget List Title -->
  <h3 class="widget-title"><?php echo $this->listOptions['blog_list_title'];?></h3>
<!-- End Widget List Title -->
<?php endif; ?>

<?php 
if( !empty($this->listOptions['view_all_page']) || !empty($this->listOptions['view_all_page_link']) ) : 
  
  ( empty($this->listOptions['view_all_page_text']) )? $view_all_msg = __('View All', 'otw_bml') : $view_all_msg = $this->listOptions['view_all_page_text'];
  ( !empty($this->listOptions['view_all_page']) )? $page_link = get_page_link($this->listOptions['view_all_page']) : $page_link = $this->listOptions['view_all_page_link'];

?>
<a href="<?php echo $page_link;?>" target="<?php echo $this->listOptions['view_all_target'];?>" class="bm_clearfix otw_blog_manager-view-all-widget"><?php echo $view_all_msg; ?></a>
<?php endif;?>