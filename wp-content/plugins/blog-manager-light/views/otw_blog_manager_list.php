<?php
	$_wp_column_headers['toplevel_page_otw-bm'] = array(
		'list_name'			=> __( 'Blog List Name', OTW_BML_TRANSLATION ),
		'shortcode'			=> __( 'Short Code', OTW_BML_TRANSLATION ),
		'user_id'				=> __( 'Author', OTW_BML_TRANSLATION ),
		'date_created'	=> __( 'Created', OTW_BML_TRANSLATION ),
		'date_modified'	=> __( 'Modified', OTW_BML_TRANSLATION ),
	);
?>
<div class="wrap">
	<div id="icon-edit" class="icon32"></div>
	<h2>
		<?php _e('Blog Lists', OTW_BML_TRANSLATION); ?>
		<a class="add-new-h2" href="admin.php?page=otw-bml-add"><?php _e('Add List', OTW_BML_TRANSLATION);?></a>
	</h2>
	<?php include_once( 'otw_blog_manager_help.php' );?>
	<?php
		if( !empty( $action['success'] ) && $action['success'] == 'true' ) {
			$message = __('Item was saved.', OTW_BML_TRANSLATION);
			echo '<div class="updated"><p>'.$message.'</p></div>';
		}

		if( !empty( $action['success_css'] ) && $action['success_css'] == 'true' ) {
			$message = __('Custom CSS file has been updated.', OTW_BML_TRANSLATION);
			echo '<div class="updated"><p>'.$message.'</p></div>';
		}

			if( $writableError ) {
				$message = __('The folder \'wp-content/uploads/\' is not writable. Please make sure you add read/write permissions to this folder.', OTW_BML_TRANSLATION);
				echo '<div class="error"><p>'.$message.'</p></div>';
			}

			if( $writableCssError ) {
				$message = __('The file \''.SKIN_BML_PATH.'\' is not writable. Please make sure you add read/write permissions to this file.', OTW_BML_TRANSLATION);
				echo '<div class="error"><p>'.$message.'</p></div>';
			}
	?>

	<?php 
		if( !empty( $otw_bm_lists['otw-bm-list'] ) || $otw_bm_lists['otw-bm-list'] == false ) :
		
		if( is_array(  $otw_bm_lists['otw-bm-list'] ) ){
			$arraySearch = array_keys( $otw_bm_lists['otw-bm-list'] );
		}else if( !isset( $arraySearch ) ){
			$arraySearch = array();
		}
		
		if( preg_grep('/^otw-bm-list-.*/', $arraySearch) ) {
	?>

	<table class="widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<?php foreach( $_wp_column_headers['toplevel_page_otw-bm'] as $key => $name ){?>
					<th><?php echo $name?></th>
				<?php }?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<?php foreach( $_wp_column_headers['toplevel_page_otw-bm'] as $key => $name ){?>
					<th><?php echo $name?></th>
				<?php }?>
			</tr>
		</tfoot>
		<tbody>
			<?php 
				$index = 0;
				foreach( $otw_bm_lists['otw-bm-list'] as $otw_bm_item ): 
					
					if( is_array($otw_bm_item) ) {

						$user_info = get_userdata( $otw_bm_item['user_id'] );

						//Used to add color to even rows
						$alternate = '';
						if( $index % 2 == 0 ) {
							$alternate = 'class="alternate"';	
						}

						$edit_link = admin_url( 'admin.php?page=otw-bml-add&amp;action=edit&amp;otw-bm-list-id='.$otw_bm_item['id'] );
						$delete_link = admin_url( 'admin.php?page=otw-bml&amp;action=delete&amp;otw-bm-list-id='.$otw_bm_item['id'] );
			?>
			<tr <?php echo $alternate;?> >
				<td>
					<?php echo '<a href="'.$edit_link.'">' . $otw_bm_item['list_name'] . '</a>'; ?>
					<div class="row-actions">
					<?php
						echo '<a href="'.$edit_link.'">' . __('Edit', OTW_BML_TRANSLATION) . '</a>';
						echo ' | <a href="'.$delete_link.'" data-name="'. $otw_bm_item['list_name'] .'" class="js-delete-item">' . __('Delete', OTW_BML_TRANSLATION). '</a>';
					?>
					</div>
				</td>
				<td><?php echo '[otw-bm-list id="'.$otw_bm_item['id'].'"]'; ?></td>
				<td><?php echo $user_info->display_name;?></td>
				<td><?php echo $otw_bm_item['date_created'];?></td>
				<td><?php echo $otw_bm_item['date_modified'];?></td>
			</tr>
			<?php 
				$index++;
				} //End if Array item
				endforeach; 
			?>
		</tbody>
	</table>

	<?php }else{ ?>
		<?php 
			$add_link = $edit_link = admin_url( 'admin.php?page=otw-bml-add' );
		?>
		<p>
			<strong><?php _e('No custom blog list found.', OTW_BML_TRANSLATION)?></strong>
			<?php echo '<a href="'.$add_link.'">' . __('Add a list', OTW_BML_TRANSLATION) . '</a>'; ?>
		</p>

	<?php } ?>
	<?php endif; ?>

</div>