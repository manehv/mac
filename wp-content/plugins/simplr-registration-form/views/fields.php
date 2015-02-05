<?php if(!isset($_GET['action']) || $_GET['action'] == 'delete') { ?>
<div id="simplr-sub-nav">
	<div class="add-field-button">
	<a href="?page=simplr_reg_set&regview=fields&action=add" class="button">Add Field</a>
	</div>
</div>
<?php $table = new SREG_Fields_Table(); ?>
<form id="mass-edit" action="?page=simplr_reg_set&regview=fields" method="post">
<?php
echo $table->header(); 
echo $table->rows();
echo $table->footer();
?>
<p>
<div class="ajaxloading" >Saving sort <img class="waiting" src="<?php echo admin_url('/images/wpspin_light.gif',__FILE__); ?>" alt=""></div>
<?php wp_nonce_field(-1,'_mass_edit'); ?>
<input type="submit" class="button" name="mass-submit" value="Delete Selected" onclick="return confirm('Are you sure you want to delete all the selected fields?')">
</p>
</form>
<script type="text/javascript">
jQuery(document).ready(function() {
	
	function update_field_sort(event,ui) {
		var sort = {};
		jQuery('table#fields-table tbody#the-list tr').each(function(i) {
			sort[i] = jQuery(this).find('.key').text();
		});
		jQuery.post(ajaxurl,{action:'simplr-save-sort',sort:sort});
		jQuery('.ajaxloading').toggle();
	}
		
	jQuery('table#fields-table tbody#the-list').sortable({stop:function() { 
		jQuery('.ajaxloading').toggle();
		update_field_sort(); } 
	}); 
		
});
</script>
<?php
} else { 
?>
<div id="simplr-sub-nav">
<div class="add-field-button">
<a href="?page=simplr_reg_set&regview=fields" class="button">Back to Field List</a>
</div>
</div>
<p>Use the form below to add a registration field. These fields can then be selected on any registration form on the site.</p>
<?php 
}

if(@$_GET['action'] == 'edit' OR @$_GET['action'] == 'add') { 
	if($_GET['action'] == 'edit') {
		$field = new SREG_Fields();
		$field = (object) $field->custom_fields->{$_GET['key']};
	}
?>
<script>
jQuery.noConflict();
jQuery(document).ready(function() {
	jQuery('#choices').find('.form-comment').each(function() {
		jQuery(this).hide();
		jQuery('input[name="options_array"]').after('<div class="info"><a id="show-info">What are my options?</a></div>');
	});
	
	jQuery('#show-info').live('click',function(e) {
		e.preventDefault();
		jQuery('#choices').find('.form-comment').toggle();
	});
});
</script>
<div class="inner">
<form action="<?php echo add_query_arg(array('action'=>'add')); ?>" method="post" id="add-field">
<?php SREG_Form::text(array('name'=>'label','label'=>'Field Label','required'=>true,'comment'=>'Human readable name for display to users'),esc_attr(@$field->label),'wide'); ?>
<?php SREG_Form::text(array('name'=>'key','label'=>'Field Key','required'=>true,'comment'=>'Machine readable name to represent this field in the Database'),esc_attr(@$field->key),'wide'); ?>

<?php SREG_Form::radio( array('name'=>'custom_column','label'=>'Show this field on user admin screens?', 'default'=>'no') , @esc_attr($field->custom_column), 'wide', array('yes'=>'Yes','no'=>'No') ); ?>

<?php SREG_Form::radio(array('name'=>'required','label'=>'Is this field required?','default'=>'yes'),esc_attr(@$field->required),'wide',array('yes'=>'Yes','no'=>'No')); ?>
<?php SREG_Form::radio(array('name'=>'show_in_profile','label'=>'Show this field in user profile?','default'=>'yes'),esc_attr(@$field->show_in_profile),'wide',array('yes'=>'Yes','no'=>'No')); ?>
<?php SREG_Form::select(array('name'=>'type','label'=>'Type','default'=>'text'),esc_attr($field->type?$field->type:'text'),'wide',array('text'=>'Text Field','textarea'=>'Textarea','select'=>'Multiple Choice: Select Box', 'radio'=>'Multiple Choice: Radio Buttons','date'=>'Date Field','checkbox'=>'Checkbox','hidden'=>'Hidden Field','callback'=>'Callback Function'),'type-select'); ?>

<div id="choices">
<?php $comment = "<strong>Checkbox: </strong> Option not used<br/><strong>Text Field: </strong>Option is not used.<br/><strong>Multiple Choice:</strong> Separate multiple options with a comma. (i.e. yes,no)<br/><strong>Date</strong>: Option is used to determine range of available dates. Enter two years separated by commas. i.e. 2000,2011.<br/>
<strong>Textarea:</strong> Option is used to determine height and width of text box. Enter dimensions width them height, separated by a comma. (i.e. <em>300px,100px</em> would generate a box that is 300 pixels wide and 100 pixels tall.)<br/>
<strong>Hidden Field: </strong> Option determines the value that will be passed to the hidden field."; ?>
<?php $values = (isset($field->options_array)) ? implode(',',@$field->options_array): null; ?>
<?php SREG_Form::text(array('name'=>'options_array','label'=>'Options','comment'=>$comment), $values,''); ?>
<br class="clear"/>
</div>


<?php echo wp_nonce_field(-1,"reg-field"); ?>
<p>
<?php $submit_value = ($_GET['action'] == 'edit') ? 'Save Changes' : 'Add Field'; ?>
<input type="submit" name="submit-field" value="<?php echo $submit_value; ?>" class="button-primary"/>
</p>
</form>
</div>
<?php } ?>
