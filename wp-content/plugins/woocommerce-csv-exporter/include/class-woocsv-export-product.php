<?php

/* TODO

external
grouped
upsell / crosssells

*/

class woocsvExportProduct
{
	public $ID = '';
	
	public $body = array (
	 	"ID" 					=> '',
	 	"post_author" 			=> '',
	 	"post_date" 			=> '',
	 	"post_date_gmt" 		=> '',
	 	"post_content"			=> '',
	 	"post_title"			=> '',
		"post_excerpt"			=> '',
		"post_status"			=> '',
		"comment_status"		=> '',
		"ping_status"			=> '',
		"post_password"			=> '',
		"post_name"				=> '',
		"to_ping"				=> '',
		"pinged"				=> '',
		"post_modified"			=> '',
		"post_modified_gmt"		=> '',
		"post_content_filtered"	=> '',
		"post_parent"			=> '',
		"guid"					=> '',
		"menu_order"			=> '',
		"post_type"				=> '',
		"post_mime_type"		=> '',
		"comment_count"			=> '',
		"product_type"			=> '',
  );
		
	public $meta = array (
		'_backorders'			=> '',
		'_featured'				=> '',
		'_length'      			=> '',
		'_manage_stock'     	=> '',
		'_price'      			=> '',
		'_product_url'      	=> '',
		'_purchase_note'     	=> '',
		'_regular_price'     	=> '',
		'_sale_price'      		=> '',
		'_shipping_class'		=> '',
		'_sku'       	 		=> '',
		'_sold_individually'    => '',
		'_stock'       			=> '',
		'_stock_status'      	=> '',
		'_tax_class'      		=> '',
		'_tax_status'      		=> '',
		'total_sales'      		=> '',
		'_virtual'       		=> '',
		'_visibility'      		=> '',
		'_weight'       		=> '',
		'_height'       		=> '',
		'_width'       			=> '',
		'product_gallery' 		=> '',
		'product_gallery_name' 	=> '',
		'featured_image' 		=> '',
		'featured_image_name' 	=> '',
		'_default_attributes'	=> '',
	
	);
	
	public $category = array (
		'category' => '',
	);
	
	public $tags = array (
		'tags' => '',
	);

	public $attributes = array (
		'attributes' => '',
		'variations' => '',
	);
	
	public $attribute_values = array ();
	
	public $extra = array ();
	
	public function __construct($id = null) {
		$this->ID = $id;
		if ($this->ID) {
			$this->fillHeader();
			$this->fillProduct();
		}	
	}
	
	public function fillHeader() {
		global $wpdb;
		//fill in attributes
		$attributes = $wpdb->get_results ("select * from {$wpdb->prefix}woocommerce_attribute_taxonomies");
		if ($attributes) {
			foreach ($attributes as $attribute) {
				//add them to the fields list
				$this->attribute_values['pa_'.$attribute->attribute_name] = '';
			}
		}
		
		//add customfields to meta
		$this->customFields();
	}
	
	public function customFields() {
		// add fields to dropdown
		$customFields = get_option('woocsv-customfields');
		if ($customFields) {
			$customFields = explode(',', $customFields);
			foreach ($customFields as $key=>$value) {
				$this->meta[trim($value)] = '';
			}
		}
	}
	
	
	public function fillProduct(){

		//fill in the body
		$this->fillBody();
		
		//fill in the meta data
		$this->fillMeta();
		
		//fill in the categories
		$this->fillCategory();
		
		//fill in the tags
		$this->fillTags();
		
		//fill in the images
		$this->fillImages();
		
		//fill in the shipping class
		$this->fillShippingClass();
		
		//fill in the attributes
		$this->fillAttribites();
		
		//fill in attribute values
		$this->fillAttribitesValues();
		
		//fill in default attributes
		$this->fillDefaultAttributes();
		
		//hook to do extra stuff
		do_action ('woocsv-export-after-fill-product');
	}
	
	public function getHeader() {
		$this->fillHeader();
		$all_fields = 	array_merge($this->body,$this->meta,$this->category,$this->tags,$this->attributes,$this->attribute_values,$this->extra);
		$header = array ();
		foreach ($all_fields as $key=>$value) {
			$header[] = ltrim($key,'_');
		}
		
		return $header;
	}

	public function getProduct() {
		
		$products[] = array_merge($this->body,$this->meta,$this->category,$this->tags,$this->attributes,$this->attribute_values,$this->extra);
		
		if ( $this->body['product_type'] == 'variation_master' ) {
			$variations = $this->getVariationChilds();
			if ($variations) {
				foreach ($variations as $v) $products[] = $v; 
			}
		}
		
		return $products;
	}


	public function fillBody(){
		global $wpdb;
		$this->body = $wpdb->get_row( 
			$wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE ID = %d",$this->ID),ARRAY_A
		);

		//set post_parent to sku
		if ( !empty( $this->body['post_parent'] ) ) {
			$parent = get_post_meta($this->body['post_parent'], '_sku', true);
			$this->body['post_parent'] = $parent;
		}

		//set product_type
		$product_type = current(wp_get_object_terms ($this->ID,'product_type'));
		if (isset($product_type->name) && $product_type->name == 'variable') {
			$this->body['product_type'] = 'variation_master';
		} else {
			$this->body['product_type'] = 'simple';
		} 
	
	}
	
	
	public function fillMeta() {
		//get the meta
		$allmeta = get_post_meta($this->ID, '' ,true);
			
		//loop through meta
		foreach ($allmeta as $key=>$value) {
			//if key in field lists add it to row
			if ( array_key_exists($key, $this->meta) ) {
				$this->meta[$key] = $value[0];
			}
		}
	}
	
	public function fillShippingClass() {
	
		$shippingClass = get_the_terms( $this->ID, 'product_shipping_class' );
		if ( $shippingClass && ! is_wp_error( $shippingClass ) ) {
			$this->meta['_shipping_class'] = current($shippingClass)->slug;
		}
	}
	
	public function fillCategory() {
	
		$cats = array();
		
		$categories = wp_get_object_terms ($this->ID,'product_cat');		
		foreach ($categories as $category) {
			$cats[] =  $this->getCategory($category->term_id);
		}
		
		$this->category['category'] =  implode('|', $cats);
	}
	
	public function fillTags(){
		$tags = wp_get_object_terms ($this->ID,'product_tag',array('fields'=>'names'));
		
		if (is_wp_error( $tags )) {
			return false;
		}
		
		$this->tags['tags'] = implode('|',$tags);
	}
	
	public function fillImages(){
	
		//featured_image
		$featured_image = get_post_meta($this->ID, '_thumbnail_id',true);
		$this->meta['featured_image_name'] = basename(wp_get_attachment_url($featured_image));
		$this->meta['featured_image'] = wp_get_attachment_url($featured_image);
		
		//product_gallery
		$product_gallery = array();
		$allimages = get_post_meta($this->ID, '_product_image_gallery',true);
		$images = explode(',', $allimages);
		foreach ($images as $image) {
			$product_gallery[] = wp_get_attachment_url($image);
			$product_gallery_name[] = basename(wp_get_attachment_url($image));
		}
		
		$this->meta['product_gallery'] = implode('|', $product_gallery);
		$this->meta['product_gallery_name'] = implode('|', $product_gallery_name);	
		
	}
	
	
	public function getCategory($term_id){
		
		/* get the term */
		$term = get_term($term_id,'product_cat');
		
		/* does the term exists */
		if (is_wp_error( $term )) {
			return false;
		}
		
		/* does the term has a parent */
		if ($term->parent) {
			return  $this->getCategory($term->parent). '->' . $term->name;
		}
		
		/* it is just a term without parent */
		return $term->name;
	}
	
	public function fillAttribites() {
		$atts = array();
		$vars = array();
		
		$attributes = get_post_meta($this->ID, '_product_attributes',true);
		
		 if ($attributes) {
			foreach ($attributes as $attribute) {
				if  ($attribute['is_variation'] == 1) {
					$vars[] = substr ( $attribute['name'] , 3).'->'.$attribute['is_visible'].'->'.$attribute['is_variation'];
				} else {
					$atts[] = substr ( $attribute['name'] , 3).'->'.$attribute['is_visible'];
				}
				
			}
		}
		
		if ($atts) {
			$this->attributes['attributes'] = implode('|', $atts);
		}
	
		if ($vars) {
			$this->attributes['variations'] = implode('|', $vars);
		}
	}
	
	public function fillAttribitesValues() {
		$attributes = $this->attribute_values;
		
		foreach ($attributes as $key=>$value) {
			$cats = array();
			$categories = wp_get_object_terms ($this->ID,$key);		
			foreach ($categories as $category) {
				$cats[] =  $category->name;
			}
		
			$this->attribute_values[$key] =  implode('|', $cats);
				
		}
	}
	
	public function fillDefaultAttributes() {
		if ($this->meta['_default_attributes']){
			$atts = array ();
			$attributes = get_post_meta($this->ID,'_default_attributes',true);
			foreach ($attributes as $key=>$value) {
				$atts[] = substr( $key , 3).'->'.$value;
			}

		$this->meta['_default_attributes'] = implode($atts,'|');
		
		}
	}
	
	public function getVariationChilds () {
		/* !1.0.1 added post_per_page-1 */
		$variation_ids =  get_posts(array('posts_per_page'=>-1,'post_type' => 'product_variation','post_parent'=> $this->ID ,'fields'=>'ids'));
		
		foreach ($variation_ids as $variation_id) {
			$variation = new woocsvExportVariation($variation_id);
			$variations[] =  current ($variation->getProduct());
		}
		
		return $variations;
		
	}	
	
}
