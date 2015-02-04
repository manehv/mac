<?php
class SREG_Fields {
	public $fields;
	public $custom_fields;
	
	function __construct() {
		$this->fields = (object) get_option('simplr_reg_fields');
		$this->custom_fields = $this->get_custom();
	}
	
	function set_default() {
		return $default;
	}
	
	function get_all() {
		if($this->fields) {
			return $this->fields; 
		}
	}
	
function get_custom() {
	    $obj = (object) $this->fields->custom;
	    $fields = new stdClass;
	    $order = get_option('simplr_field_sort');
	    if( !empty($order) ) {
	        foreach($order as $field) {
						if($field != '') {
	            $fields->$field = $obj->$field;
						}
	        }
	    } else {
	        $fields = $obj;
	    }
		//clean up and empty fields
		foreach( $fields as $key => $field ) {
			if( empty($field) ) {
				unset($fields->$key);
			}
		}

	    return $fields;
    }
	
	function save_custom($data) {
	 	$name = sanitize_key($data['key']);
	 	$custom = (array) $this->custom_fields;
	 	$order = get_option('simplr_field_sort');
		foreach($data as $key => $value):
			if($key == 'options_array') 
			{
				$custom[$name][$key] = explode(',',$value);
			} 
			elseif($key == 'key') 
			{
				$value = sanitize_key($value);
				$order[] = $value;
				$custom[$name][$key] = $value;
			} 
			else 
			{
				$custom[$name][$key] = $value;
			}
		endforeach;		
		$this->fields->custom = $custom;

		update_option('simplr_reg_fields',$this->fields);
		update_option('simplr_field_sort',$order);
		$this->custom_fields = $this->get_custom();
	}
	
	function delete_field($key) {
		$custom = (array) $this->custom_fields;
		$order = get_option('simplr_field_sort');
		unset($custom[$key]);
		unset($order[array_search($key,$order)]);
		update_option('simplr_field_sort',$order);
		$this->fields->custom = $custom;
		update_option('simplr_reg_fields',$this->fields);
	}
	
}
?>
