<?php
/*
Plugin Name: Woocommerce CSV Import Attributes add-on
Description: Import attributes
Version: 2.0.0
Author: Allaerd Mensonides
License: GPLv2 or later
Author URI: http://allaerd.org
parent: woocommerce
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//check if class exists somehow somewhere
if (class_exists('woocsvAttributes') != true) {

	class woocsvAttributes
	{

		public function __construct()
		{

			//add the hook to add variable data!
			add_action('woocsv_product_before_meta_save' , array( $this, 'saveAttributes' ),100 );

			//create a submenu for the add-on
			add_action('admin_menu', array($this, 'adminMenu'));

			//populate the fields for the dropdowns in the header section
			$this->addToFields();
		}

		public function adminMenu()
		{
			add_submenu_page( 'woocsv_import', 'Attributes', 'Attributes', 'manage_options', 'woocsvAttributes', array($this, 'addToAdmin'));
		}

		public function addToFields()
		{
			global $wpdb, $woocsvImport;
			
			//get the attributes
			$attributes = $wpdb->get_results ("select * from {$wpdb->prefix}woocommerce_attribute_taxonomies");
			if ($attributes) {
				foreach ($attributes as $attribute) {
					//add them to the fields list
					$woocsvImport->fields[] = 'pa_'.$attribute->attribute_name;
				}
			}
			
			//attribute field
			$woocsvImport->fields[] = 'attributes';
		}

		
		public function saveAttributes() {
			global $wooProduct;

			$product_attributes = '';			
			
			//check if there is a variation column
				
				$key = array_search('attributes', $wooProduct->header );
				
				//check if it has values
				if ( $key > 0 && !empty($wooProduct->rawData[$key] ) ) {
					
					//split the attributes if there are multiple. att1|att2|att3
					$attributes = explode('|', $wooProduct->rawData[$key]);
					
					//reset the array and the position
					if (!empty($wooProduct->meta['_product_attributes']))
						$product_attributes = $wooProduct->meta['_product_attributes'];
						
					$pos = 0;
					
					//set the postition to the right value if variations allready toke some places
					if ($product_attributes) {
						foreach ($product_attributes as $x) {
							if ($x['position'] > $pos) $pos = $x['position'];
						}
					}					

					
					//loop through the variations
					foreach ($attributes as $attribute) {

						//get the values for visible and is variation else assume it's 1
						list($attribute, $is_visible) = array_pad(explode('->', $attribute), 2, 1);

						//fill in the array
						$product_attributes['pa_'.$attribute] = array
						(
							'name' => 'pa_'.$attribute,
							'value' => '',
							'position' => "$pos",
							'is_visible' => (int)$is_visible,
							'is_variation' => 0,
							'is_taxonomy' => 1,
						);
						
						//increase the position for the next one	
						$pos ++;
						
					//now get the values of the product attribute
					$key = null;

					//check if attribute is in the header
					$key = array_search('pa_'.$attribute, $wooProduct->header );
					if (!empty($key)){
						
						//check if the attribute as values and if there are multiple like value1|value2|value3
						$terms = explode('|', $wooProduct->rawData[$key]);
						if (!empty($terms)) {

							//link the values of the attrbutes to the product
							foreach ($terms as $term) {
								wp_set_object_terms( $wooProduct->body['ID'],$term,'pa_'.$attribute, true );
							}
						}
					}			
					//save the attributes
					$wooProduct->meta['_product_attributes']=  $product_attributes;				
				}				
			}
		}
		

		function addToAdmin()
		{
			global $wpdb, $woocommerce;
			$attributes = $wpdb->get_results ("select * from {$wpdb->prefix}woocommerce_attribute_taxonomies");

			//create attribute url
			if ( str_replace('.', '', $woocommerce->version) >= 210) 
				$attr_url = get_admin_url() . 'edit.php?post_type=product&page=product_attributes';
			else
				$attr_url = get_admin_url() . 'edit.php?post_type=product&page=woocommerce_attributes';
?>

		<div class="wrap">
		<div id="icon-themes" class="icon32"><br></div>
		<h2 class="nav-tab-wrapper">
			<a href="#" class="nav-tab nav-tab-active">Import product attributes</a>
		</h2>
		<?php if (empty($attributes) ) : ?>
			<div class="error"><p>There are no attributes yet. Please goto the  <a href="<?php echo $attr_url;?>">attribute screen</a> to create them.</p>
			</div>
		<?php else: ?>
			<h2>You have the following attributes:</h2>
			<ul>
			<?php foreach ($attributes as $attribute)
						echo '<li>Attribute: <b><i>'.$attribute->attribute_name.',</i></b> use this header tag in your CSV: <code>pa_'. $attribute->attribute_name.' </code></li>';
?>
			</ol>
			<p>
				Goto the  <a href="<?php echo $attr_url;?>">attribute screen</a> to create more!
			</p>
		<?php endif; ?>
		<h2>Usage</h2>
		<p>
			There are several new fields available for you when you create a header:
			<h4>attributes</h4>
			In here you fill in the attributes you want to use. You can have multiple and have them visible or not. 
			<code>color->1|size->0</code> Buy default all attributes are visible. So <code>color|size</code> would be enough.
			Attributes can have multiple values, use the pipe to separate them. <code>red|green|blue</code>
			The most basic product could look like:<br/>
			<code>
			sku;post_title;attributes;pa_color;pa_size;pa_brand</br>
			1;product 1;color|size;red;medium;</br>
			2;product 2;size|brand;;large;nike</br>
			3;product 3;color->0|size|brand->0;red;small|adidas</br>
			</code>
		</p>
		<p>If you are not sure how to import attributes products, read the <a target="_blank" href="http://allaerd.org/documentation/">documentation</a> Or you can try the example <a href="<?php echo plugin_dir_url(__file__);?>/example.csv">CSV</a></p>
		</div>
		<?php


		}}
}