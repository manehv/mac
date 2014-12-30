<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class woocsvPremium
{

	public $extraFields = array (
		'product_type', // simple, grouped, external
		'post_parent', // SKU of group parent
		'button_text', // button text for external product
		'product_url', // URL for external product
		'virtual', // yes,no
		'sold_individually', //yes,no
		'sale_price_dates_to', // YYYYMMDD
		'sale_price_dates_from', // YYYYMMDD
		'downloadable', // yes,no
		'download_limit', //number of downloads
		'download_expiry', // number of days download link is available
		'crosssell_sku', // a:2:{i:0;s:5:"28643";i:1;s:5:"28644";}
		'upsell_sku', // a:2:{i:0;s:5:"28643";i:1;s:5:"28644";}
		'purchase_note',
		'menu_order',
		/* Woo 2.1 has different way of downloadable products */
		'file_names', //file name
		'file_urls', //absolute or URL
		/* DEPRECIATED */
		'file_path', //path to the file

	);

	public function __construct()
	{

		add_action('woocsv_after_save', array($this, 'saveExtraFields'));
		add_action('admin_menu', array($this, 'adminMenu'));
		$this->addToFields();
	}

	public function adminMenu()
	{
		add_submenu_page( 'woocsv_import', 'Premium', 'Premium', 'manage_options', 'woocsvExtraFields', array($this, 'addToAdmin'));
	}

	public function addToFields()
	{
		global $woocsvImport;

		foreach ($this->extraFields as $value) {
			$woocsvImport->fields[] = $value;
		}
	}

	public function saveExtraFields($product)
	{
		global $wpdb;
		//downloadable
		if ( !empty($product->meta['_downloadable']) ) {
			if ( $product->meta['_downloadable'] <> 'yes' ) {

				update_post_meta($product->body['ID'], '_downloadable', 'no');
				update_post_meta($product->body['ID'], '_download_expiry', '');						
				// used in version 2.0 and lower
				update_post_meta($product->body['ID'], '_file_path', ''); 
				// used in version 2.1 and higher
				update_post_meta($product->body['ID'], '_downloadable_files', '');
		
			} else {
				// used in version 2.0 and lower
				$_file_paths = array();
				$file_paths = str_replace( "\r\n", "\n", esc_attr( $product->meta['_file_path'] ) );
				$file_paths = trim( preg_replace( "/\n+/", "\n", $file_paths ) );
				if ( $file_paths ) {
					$file_paths = explode( "\n", $file_paths );

					foreach ( $file_paths as $file_path ) {
						$file_path = trim( $file_path );
						$_file_paths[ md5( $file_path ) ] = $file_path;
					}
				}
				update_post_meta( $product->body['ID'], '_file_paths', $_file_paths );
				
				// used in version 2.1 and higher
				$files = array();
				
				$file_names    = explode('|',$product->rawData[array_search('file_names', $product->header)]);
				$file_urls    = explode('|',$product->rawData[array_search('file_urls', $product->header)]);
				$loop_count =  ( count( $file_names ) <= count ( $file_urls ) ) ? count($file_names) : count ( $file_urls );
				
				for ( $i = 0; $i < $loop_count; $i ++ ) {
					if ( ! empty( $file_urls[ $i ] ) )
						$files[ md5( $file_urls[ $i ] ) ] = array(
							'name' => $file_names[ $i ],
							'file' => $file_urls[ $i ]
						);
				}
				
				update_post_meta( $product->body['ID'], '_downloadable_files', $files );
			
			}
		}

		//sales dates
		if ( !empty($product->meta['_sale_price_dates_from']))
			update_post_meta( $product->body['ID'], '_sale_price_dates_from', @strtotime( $product->meta['_sale_price_dates_from'] ) );

		if ( !empty($product->meta['_sale_price_dates_to']))
			update_post_meta( $product->body['ID'], '_sale_price_dates_to', @strtotime( $product->meta['_sale_price_dates_to'] ) );

		//grouped master
		if (in_array('product_type', $product->header ) &&
			$product->rawData[array_search('product_type', $product->header)] === 'grouped_master') {
			wp_set_object_terms( $product->body['ID'], null , 'product_type');
			wp_set_object_terms( $product->body['ID'], 'grouped' , 'product_type', true );
		}

		//grouped child
		if (
			in_array('product_type', $product->header ) &&
			in_array('post_parent', $product->header ) &&
			$product->rawData[array_search('product_type', $product->header)] === 'grouped_child' &&
			!empty($product->rawData[array_search('post_parent', $product->header)])
		) {
			$parent_sku = $product->rawData[array_search('post_parent', $product->header)];
			$parent_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id
						FROM $wpdb->postmeta
						WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $parent_sku )
			);
			if ($parent_id) {
				$product->body['post_parent'] = $parent_id;
				wp_update_post($product->body);
			}
		}
		
		//external product
		if (in_array('product_type', $product->header ) &&
			$product->rawData[array_search('product_type', $product->header)] == 'external') 
		{
			wp_set_object_terms( $product->body['ID'], null , 'product_type');
			wp_set_object_terms( $product->body['ID'], 'external' , 'product_type', true );
		}
		
		//button text
		if (in_array('product_type', $product->header ) &&
			in_array('button_text', $product->header ) &&
			$product->rawData[array_search('product_type', $product->header)] == 'external' &&
			!empty($product->rawData[array_search('button_text', $product->header)]))			 
		{
			update_post_meta( $product->body['ID'],'_button_text',$product->rawData[array_search('button_text', $product->header)]);
				
		}
		
		//product URL
		if (in_array('product_type', $product->header ) &&
			in_array('product_url', $product->header ) &&
			$product->rawData[array_search('product_type', $product->header)] == 'external' &&
			!empty($product->rawData[array_search('product_url', $product->header)]))			 
		{
			update_post_meta( $product->body['ID'],'_product_url',$product->rawData[array_search('product_url', $product->header)]);
				
		}
		
		//cross sell
		if (in_array('crosssell_sku', $product->header ) &&
			!empty($product->rawData[array_search('crosssell_sku', $product->header)]))			 
		{
			$cross_skus = explode ('|',$product->rawData[array_search('crosssell_sku', $product->header)]);
			$cross_ids = array();
			foreach ($cross_skus as $cross_sku) {
				$parent_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id
						FROM $wpdb->postmeta
						WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $cross_sku )
						);
				if ($parent_id)
					$cross_ids[] = $parent_id;
			}
			update_post_meta( $product->body['ID'],'_crosssell_ids',$cross_ids);
		}

		//up sell
		if (in_array('upsell_sku', $product->header ) &&
			!empty($product->rawData[array_search('upsell_sku', $product->header)]))			 
		{
			$upsell_skus = explode ('|',$product->rawData[array_search('upsell_sku', $product->header)]);
			$upsell_ids = array();
			foreach ($upsell_skus as $upsell_sku) {
				$parent_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id
						FROM $wpdb->postmeta
						WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $upsell_sku )
						);
				if ($parent_id)
					$upsell_ids[] = $parent_id;
			}
			update_post_meta( $product->body['ID'],'_upsell_ids',$upsell_ids);
		}
		
		//other fields purchase_note, menu_order
		if (in_array('purchase_note', $product->header ) &&
			!empty($product->rawData[array_search('purchase_note', $product->header)]))			 
		{
			update_post_meta( $product->body['ID'],'_purchase_note',$product->rawData[array_search('purchase_note', $product->header)]);
		}
		
		if (in_array('menu_order', $product->header ) &&
			!empty($product->rawData[array_search('menu_order', $product->header)]))			 
		{
			update_post_meta( $product->body['ID'],'_menu_order',$product->rawData[array_search('menu_order', $product->header)]);
		}
		
		if (in_array('sold_individually', $product->header ) &&
			!empty($product->rawData[array_search('sold_individually', $product->header)]))			 
		{
			update_post_meta( $product->body['ID'],'_sold_individually',$product->rawData[array_search('sold_individually', $product->header)]);
		}
		

	}

	function addToAdmin()
	{
?>
		<div class="wrap">
		<div id="icon-themes" class="icon32"><br></div>
		<h2 class="nav-tab-wrapper">
			<a href="#" class="nav-tab nav-tab-active">Woocommerce CSV importer premium</a>
		</h2>
		<p>
		With this add-on you can import additional fields and product types. The fields and product types you can import are:
		</p>
		
		<dl>
		<dt><h3>Downloadeble products</h3></dt>
			<dd>downloadable <code>yes,no</code></dd>
			<dd>download_limit, number of times the user can download the file</dd>
			<dd>download_expiry, number of days the download link is valid</dd>
			<dd>file_names, pipe separated list of filenames <code>name1|name2</code> works together with file_urls</dd>
			<dd>file_urls, pipe separated list of file paths, may contain urls's or absolute paths <code>/var/www/path1/file1.zip|http://www.example.nl/file2.zip</code></dd>
			<dd>&nbsp;</dd>
			<dd>** DEPRECIATED as of Woocommerce verion 2.1 **</dd>
			<dd>file_path (path to file on server)</dd>
		<dt><h3>Grouped Products</h3></dt>
			<dd>product_type, the product types used for grouped products <code>grouped_master,grouped_child</code></dd>
			<dd>post_parent, the sku of the grouped master</dd>
		<dt><h3>External/Affiliate products</h3></dt>
			<dd>product_type the product type for external products<code>external</code></dd>
			<dd>button_text, button text for external product</dd>
			<dd>product_url, URL for external product</dd>
		<dt><h3>Other fields</h3></dt>
			<dd>virtual, if the product is virtual or not <code>yes,no</code></dd>
			<dd>sold_individually, if the product is sold individual or not <code>yes,no</code></dd>
			<dd>sale_price_dates_to, the from date for sales <code>20140115 (YYYYMMDD)</code></dd>
			<dd>sale_price_dates_from, the till date for sales <code>20140130 (YYYYMMDD)</code></dd>
			<dd>crossell_ids, pipe separated list of SKU's of the cros sell products <code>sku1|sku2|sku3</code></dd>
			<dd>upsell_ids pipe separated list of SKU's of the upsell products <code>sku1|sku2|sku3</code></dd>
			<dd>purchase_note, custom purchase note for this product</dd>
			<dd>menu_order, the order of the product in the menu</dd>
		</dl>
		<p>For more documentation see: <a target="_blank" href="http://allaerd.org/documentation/">allaerd.org</a></p>
		<p>Or you can try the example <a href="<?php echo plugin_dir_url(__file__);?>premium.csv">CSV</a></p>
		</div>

		<?php
	}
}