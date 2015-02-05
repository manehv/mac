<?php
class SREG_Fields_Table extends SREG_Fields {
	public $cols;

	function __construct() {
		parent::__construct();
		$this->cols = $this->cols();
	}

	function cols() {
		$cols = array(
			array('name'=>'label','label'=>'Field Name'),
			array('name'=>'key','label'=>'Field Key'),
			array('name'=>'required','label'=>'Required'),
			array('name'=>'type','label'=>'Field Type'),
			array('name'=>'show_in_profile','label'=>'Profile?')
		);
		return $cols;
	}

	function header() {
	?>
	<script>
	jQuery.noConflict();
	jQuery(document).ready(function() {
		jQuery('#delete-all').change(function(){
			check = jQuery(this).attr('checked');
			if(check == false) {
				jQuery('table.wp-list-table').find('#delete-field').attr('checked',false);
			} else {
				jQuery('table.wp-list-table').find('#delete-field').attr('checked',true);
			}
			
		});
	});
	</script>
	<table id="fields-table" class="wp-list-table widefat" cellspacing="0"> 
	<thead> 
	<tr> 
		<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
		 <input type="checkbox" id="delete-all" /> 
		</th>
		<?php foreach($this->cols as $col): $col = (object) $col; ?>
		<?php 
		if(isset($_GET['order'])):
			$sort = ($_GET['order'] == 'asc') ? 'desc' : 'asc'; 
		endif; 
		?>
		<th scope='col' id='<?php echo $col->key; ?>' class='manage-column column-title sortable <?php echo $sort; ?>'  style="">
		<?php echo $col->label; ?>
		</th>
		<?php endforeach; ?>
		<th scope="col" id="action" class="action-column" style="">Action</th>
		</tr>
	</thead> 
	<?php
	}
	
	function footer() {
		?>
		</table>
		<?php
	}
	
	function rows($type = null) { ?>
	<?php echo '<tbody id="the-list">'; ?>
	<?php if($this->get_custom()): ?>
	<?php foreach($this->get_custom() as $field): ?>
			<tr scope="row" class="format-default iedit <?php echo $field['key']; ?>">
			<td>
				<input type="checkbox" name="field_to_delete[]" value="<?php echo $field['key']; ?>" id="delete-field" />
			</td>
			<?php foreach($this->cols as $col): $col = (object) $col; ?>
				<td class="<?php echo $col->name; ?>"><?php echo @$field[$col->name]; ?></td>
			<?php endforeach; ?>
			<td>
			<?php $dnonce = wp_create_nonce('delete'); ?>
				<a href="?page=simplr_reg_set&regview=fields&action=edit&key=<?php echo $field['key']; ?>" class="button">Edit</a>   <a href="?page=simplr_reg_set&regview=fields&action=delete&_wpnonce=<?php echo $dnonce; ?>&key=<?php echo $field['key']; ?>" onclick="return confirm('Are you sure you want to do this?')" class="button">Delete</a>
			</td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
	</tbody>
	<?php }
}
?>
