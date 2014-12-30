<?php
/*
Plugin Name: Woocommerce CSV Import variable products add-on
Description: Import variable products
Version: 2.0.0
Author: Allaerd Mensonides
License: Copyright 2013
Author URI: http://allaerd.org
*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 

//check if class exists somehow somewhere
if (class_exists('woocsvVariableProducts') != true) {


	class woocsvVariableProducts
	{

		public function __construct()
		{
			//add the hook to add variable data!
			add_action('woocsv_after_fill_in_data' , array( $this, 'saveVariations' ),100 );

			//create a submenu for the add-on
			add_action('admin_menu', array($this, 'adminMenu'));

			//populate the fields for the dropdowns in the header section
			$this->addToFields();
		}

		public function saveVariations()
		{
			global $wooProduct,$woocsvImport ;

			//Do we have a variation master?
			$key = array_search('product_type', $wooProduct->header );
			//2.0.1 changed 0 to null because product type can be the first column and therefor the $key can be 0
			if ( !is_null ($key)   && $wooProduct->rawData[$key] == 'variation_master' ) {
				$woocsvImport->importLog[] = 'It is a variable master product';
								
				//set the master product to type variable
				$wooProduct->product_type = 'variable';

				//check for default attributes and add them to meta
				$key = array_search('default_attributes', $wooProduct->header );
				if ( $key != null && !empty($wooProduct->rawData[$key] ) ) {
					//handle the product default values

					$defaults = explode('|', $wooProduct->rawData[$key]);
					$product_attributes_default = array();
					//loop thorugh them, create them if necessary
					foreach ($defaults as $default) {
						list($key, $value) = explode('->', $default);

						//check if the taxonomy exists
						$taxonomy = taxonomy_exists ( 'pa_'.$key );

						//check if the term exists
						$term = term_exists( $value, 'pa_'.$key  );
						if (!$term) {
							//the term did not exists so create it
							$term =  wp_insert_term ($value, 'pa_'.$key);
						}

						//if both exists link the them to the product
						if ( $taxonomy && $term ) {
							$term = get_term( $term['term_id'], 'pa_'.$key );
							$product_attributes_default['pa_'.$key] = $term->slug;
						}

					}

					$wooProduct->meta['_default_attributes'] = $product_attributes_default;

				}//end default attributes

				//start variation

				//check if there is a variation column
				$key = array_search('variations', $wooProduct->header );

				//check if it has values
				if ( !is_null ($key) && !empty($wooProduct->rawData[$key] ) ) {
					
					//split the variations if there are multiple. var1|var2|var3
					$variations = explode('|', $wooProduct->rawData[$key]);
					
					//reset the array and the position
					$product_attributes = array ();
					$pos = 0;
					
					//loop through the variations
					foreach ($variations as $variation) {

						//get the values for visible and is variation else assume it's 1
						list($variation, $is_visible, $is_variation) = array_pad(explode('->', $variation), 3, 1);

						//fill in the array
						$product_attributes['pa_'.$variation] = array
						(
							'name' => 'pa_'.$variation,
							'value' => '',
							'position' => "$pos",
							'is_visible' => (int)$is_visible,
							'is_variation' => $is_variation,
							'is_taxonomy' => 1,
						);
						
						//increase the position for the next one	
						$pos ++;
					}

					//save the attributes
					$wooProduct->meta['_product_attributes'] =  $product_attributes;
				}



			}
			//end product master

			//begin product child
			
			//check if the row is an product_variation
			$key = array_search('product_type', $wooProduct->header );
			if ( !is_null ($key) && $wooProduct->rawData[$key] === 'product_variation' ) {
				//set the right post_type
				$wooProduct->body['post_type'] = 'product_variation';

				//set the right parent
				$key = array_search('post_parent', $wooProduct->header );
				if ( $key > 0 && !empty($wooProduct->rawData[$key])) {
					$parent_id = $wooProduct->getProductId($wooProduct->rawData[$key]);
					if ($parent_id) {
						$wooProduct->body['post_parent'] = $parent_id;
					}
				}
				
				$woocsvImport->importLog[] = 'It is a variation and the master product has ID: '.$parent_id;

				//get the parent attributes
				$product_attributes = get_post_meta($parent_id, '_product_attributes',true);
				
				//if there are no attributes return
				if (empty($product_attributes)) {
					$woocsvImport->importLog[] = 'Parent product does not have any attributes!!';
					return;
				}
						
				//loop through all attributes								
				foreach ($product_attributes as $product_attribute) {

					//check if it is an attribute used for variations else break...
					if  ($product_attribute['is_variation'] != 1)
						continue;
					
					//check if the atrribute is in the header
					$key = array_search( $product_attribute['name'], $wooProduct->header );
					
					if ( $key > 0 && !empty($wooProduct->rawData[$key])) {

						//check if the term exist
						$term =  term_exists($wooProduct->rawData[$key], $product_attribute['name']);

						if (empty($term)) {
							//the term did not exists so create it
							$term =  wp_insert_term ($wooProduct->rawData[$key], $product_attribute['name']);

							//something went wrong, proceed to the next step!
							if ( is_wp_error($term) )
								continue;
						}

						// the term is there, now get it so you have the slug etc
						$term = get_term( $term['term_id'], $product_attribute['name']);

						//the term is there add it to the parent
						wp_set_object_terms( $wooProduct->body['post_parent'], $term->slug, $product_attribute['name'], true );

						//set the right value to the child
						$wooProduct->meta['attribute_'.$product_attribute['name']] = $term->slug;
						
					}
				}

			}
			//end product child
		}

		public function adminMenu()
		{
			add_submenu_page( 'woocsv_import', 'Variations', 'Variations', 'manage_options', 'woocsvVariations', array($this, 'addToAdmin'));
		}

		public function addToFields()
		{
			global $wpdb, $woocsvImport;
			$attributes = $wpdb->get_results ("select * from {$wpdb->prefix}woocommerce_attribute_taxonomies");
			$woocsvImport->fields[] = 'product_type';
			$woocsvImport->fields[] = 'post_parent';
			$woocsvImport->fields[] = 'variations';
			$woocsvImport->fields[] = 'default_attributes';

			if ($attributes) {
				foreach ($attributes as $attribute) {
					$woocsvImport->fields[] = 'pa_'.$attribute->attribute_name;
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
			<a href="#" class="nav-tab nav-tab-active">Import Variable products</a>
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
			<h4>product_type</h4>
			The possible values are: <code>variation_master</code> for the master and <code>product_variation</code> for the product child
			<h4>post_parent</h4>
			In here you enter the SKU of the variation master.
			<h4>variations</h4>
			This field is meant to setup which variations are used and how the are used. The way to setup is <code>variation->is_visible->is_used_for_variation</code>. If you look at this example <code>color->1->1|size->1->0</code>, we have 2 attributes. Color is visible and is used for variations. And size is visible but not used for variations.
			<h4>default_attributes</h4>
			If you want the predefined values, you can add them using default attributes. If you want blue to be default for color and medium to be default for size you can set it up like this: <code>color->blue|size->medium</code>.
		</p>
		<p>If you are not sure how to import variable products, read the <a target="_blank" href="http://allaerd.org/documentation/import-product-variations-woocommerce/">tutorial</a> Or you can try the example <a href="<?php echo plugin_dir_url(__file__);?>/example.csv">CSV</a></p>
		</div>
		<?php


		}
	}
}