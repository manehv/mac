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

if (is_array($field['options'])){

    foreach($field['options'] as $opt_key => $opt){

        $field_val = apply_filters('arfdisplaysavedfieldvalue', $opt, $opt_key, $field);

        $opt = apply_filters('show_field_label', $opt, $opt_key, $field);
		
		if(is_array($opt)) {
			$opt = $opt['label'];
			$field_val = ($field['separate_value']) ? $field_val['value'] : $opt;
		}
									
        $checked = (isset($field['value']) and ((!is_array($field['value']) && $field['value'] == $field_val ) || (is_array($field['value']) && in_array($field_val, $field['value'])))) ? ' checked="true"':'';

        require(VIEWS_PATH .'/optionsingle.php');

        

        unset($checked);

    }  

}

?>