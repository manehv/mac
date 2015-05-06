<div class="wrap woocommerce advanced_notifications">
	<div id="icon-woocommerce" class="icon32 icon32-woocommerce-email"></div>
	<h2>
    	<?php _e('Notifications', 'wc_adv_notifications'); ?>

    	<a href="<?php echo admin_url( 'admin.php?page=advanced-notifications&amp;add=true' ); ?>" class="add-new-h2"><?php _e('Add notification', 'wc_adv_notifications'); ?></a>
    </h2><br/>
    
    <form method="post">
    <?php
	    $table = new WC_Advanced_Notifications_Table();
	    $table->prepare_items();
	    $table->display()
    ?>
    </form>
</div>
<script type="text/javascript">
	
	jQuery('a.submitdelete').live('click', function(){
		var answer = confirm('<?php _e( 'Are you sure you want to delete this notification?', 'wc_adv_notifications' ); ?>');
		if (answer){
			return true;
		}
		return false;
	});
	
</script>