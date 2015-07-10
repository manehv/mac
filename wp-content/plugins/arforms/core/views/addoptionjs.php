<?php
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7.3
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/
?>
<script type="text/javascript">


jQuery(document).ready(function(){

jQuery(".arfoptioneditorfield, .arfoptioneditorfield_key").editInPlace({url:"<?php echo $arfajaxurl ?>",params:"action=arfeditorfieldoption",default_text:"<?php _e('(Blank)', 'ARForms') ?>" ,success:function(res){ 
	arf_change_opt_val( jQuery(this).attr('data-fid'), jQuery(this).attr('id'), jQuery(this).text(), jQuery(this).attr('data-original') ); 
	arf_change_opt_label( jQuery(this).attr('data-fid'), jQuery(this).attr('id'), jQuery(this).text() );		
} });
 

jQuery("#arffielddelete_<?php echo $field['id']; ?>-<?php echo $opt_key ?>_container , #arffielddelete_<?php echo $field['id']; ?>-<?php echo $opt_key ?>_container .arfoptioneditorfield_key").blur({


url:"<?php echo $arfajaxurl ?>",params:"action=arfeditorfieldoption",default_text:"<?php _e('(Blank)', 'ARForms') ?>",success:function(){return false;}
});

});

</script>