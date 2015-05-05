<?php

/* TODO

attributes
variations

upsell / crosssells

*/

class woocsvExportVariation extends woocsvExportProduct
{
	
	function __construct($id = null) {
		parent::__construct();
		$this->ID = $id;
		if ($this->ID) {
			$this->fillHeader();
			$this->fillProduct();
			$this->handleVariation();
		}	
	}
		
	public function handleVariation() {
		$this->body['product_type'] = $this->body['post_type'];
		foreach ($this->attribute_values as $key=>$value){
			$this->attribute_values[$key] = get_post_meta($this->ID, 'attribute_'.$key, true);
		}
	}
	
}
