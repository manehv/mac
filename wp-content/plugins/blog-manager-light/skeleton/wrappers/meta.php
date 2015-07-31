<!-- Blog Info -->
<?php
	if( !empty( $this->listOptions['meta_type_align'] ) ){
		( $this->listOptions['meta_type_align'] == 'vertical' )? $blogInfoClass = 'few-lines' : $blogInfoClass = '';
	}else{
		$blogInfoClass = '';
	}
?>
<div class="otw_blog_manager-blog-meta-wrapper <?php echo $blogInfoClass;?>">
  <?php echo $metaData; ?>
</div>
<!-- End Blog Info -->