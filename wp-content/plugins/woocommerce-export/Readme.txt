=== Woocommerce Smart Export ===
Contributors: themology
Donate link: http://themology.net/donate
Tags: woocommerce, export, wooCommerce export, csv export, orders export, customer export, product export, schudle export, wordpress export
Requires at least: 3.7
Tested up to: 4.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Gives you the ability to export into CSV file all your woocommerce customers and orders in one click.

== Description ==

WooCommerce Smart Export Plugin gives you the ability to export into CSV file all your WooCommerce customers and WooCommerce orders in one click. A great way to analyse more easily your store activity.

This plugin takes advantage of the new "Date Query" introduced with WordPress version 3.7, To use this with your Woocommerce store, make sure your installed WordPress version 3.7+


Just another handy tools for Woocommerce from <a href="http://themology.net">Themology</a> Team.

**Some Features**

* Great tool to analyse store activity.
* Export Customer in a CSV file.
* Export Orders in a CSV file.
* Export Coupons in a CSV file.
* Schedule Export
* Schedule Imports
* and more.

**Language File**

This plugin is fully translatebale, if you want to help us by translating it in our own language, then you are most welcome.

Already translated:

* English
* France
* China
* Taiwan

**If You like this Plugin, please rate with 5 Star, and If you need any assist, dont forget to contact with us.**

== Installation ==


Just like every plugins installing Woocommerce Smart Export pluign is also very easy.

1. Login in your Admin and upload "woocommerce-smart-export.zip" from Pluigns => Add New
2. Install and active, and then access the plugin Dashboard from the WooCommerce > Reports menu.
3. Thats it.

**Note:** WooCommerce Smart Export Plugin requires WordPress 3.7+ and support Woocommerce 2.0, 2.1+ and 2.3+


== Screenshots ==

1. Setting Options for Schedule Export
2. Setting Option for Smart Export

== Frequently Asked Questions ==

= Is it compatible with Woocommerce Shipment Tracking =

Yes. All you need to do is add a filter in your functions.php theme file. The following snippet will add the four extra columns to your CSV file : http://pastebin.com/yS1GFW9E

= Who can access to the export module =

By default, the smart export function is only available for admin. But you can modify the requested capability/role using a filter. Here is a snippet you can use : http://pastebin.com/vptW0WzK

= Were can I find the order meta and product meta I can use using filters ? (Since 2.0.2) =

In the back-end, when you are to the Smart Export panel, in the “help” tab top right, there is a Smart export tab with the all the post meta you can use.

= How do I add a custom time interval I can choose when I set up the scheduled export ? =

By adding a small piece of code in the functions.php file in the theme. See the sample code here : http://pastebin.com/sDtVF3mK

= Have a blank screen when I try do an export… =

It’s because php memory limit is exceeded. You may have a lot of orders to export or a low php memory limit.

Two options :

1. Increase the php limit you allow to php in your WordPress back-end. (But your hosting may not allow you as much memory as you want.)

Just add this line to your wp_config.php file (replace 1024M by any value you want) :

define( 'WP_MAX_MEMORY_LIMIT', '1024M' );

2. Reduce the number of orders to export by filtering by date.


= Can I remove some columns from the order export =

Yes, you can using filters.

To remove columns related to the order data, please see this example : http://pastebin.com/duwVwWwp

To remove columns related to the product data, please see this example : http://pastebin.com/3izfzEpw

= What is a filter and how do I use it ? =

A filter is a small piece of code you add to your functions.php theme file. It allows you (if the plugin is designed for using filters) to modify the behavior of the plugin, without modifying it.

Smart export plugin offers a lot of filters you can use this way to customize your export. Most of the filters are illustrated with a pasteBin example, that you can find in the FAQ.

(Be careful, if you use a premium theme, if you update it it will probably erase the you you add in the functions.php file. But most of times there are specific file in your theme you can edit, which are update-proof.)

= How may I add custom data in the order export ? =

You can using a filter.

To add a column related to the order, see exemple here : http://pastebin.com/itLY3VAb

To add a column related to the products (for example, color or size…), see exemple here : http://pastebin.com/nEs8RNu0

= In the order export, I need a totally custom column, which is not stored as as a post meta… Like : empty column / column with always the same data / concatenating two order meta / anything fancy… =

There is a filter for that : http://pastebin.com/n4Mizs96

You get the order object in parameter… so it’s up to you doing whatever you want ! (Requires some development skills)

(Be careful, if the treatment you do to get the data for each order is too complicated, with a lot of order, you could run out php memory)

= Can I order the fields ? How ? =

It’s possible to order some fields, but not all.

1. What can be ordered :

Those can be ordered therebetween :

<code>

//general ‘id’, ‘status’, ‘order_date’,

//billing infos ‘billing_first_name’, ‘billing_last_name’, ‘billing_company’, ‘billing_address_1’, ‘billing_address_2’,’billing_city’, ‘billing_postcode’, ‘billing_country’, ‘billing_state’, ‘billing_email’, ‘billing_phone’,

//shipping infos ‘shipping_first_name’, ‘shipping_last_name’, ‘shipping_company’, ‘shipping_address_1’, ‘shipping_address_2’, ‘shipping_city’, ‘shipping_postcode’, ‘shipping_state’, ‘shipping_country’,

//note ‘customer_note’,

//payment, shipping and total ‘shipping_method_title’, ‘payment_method_title’, ‘order_discount’, ‘cart_discount’, ‘order_tax’, ‘order_shipping’, ‘order_shipping_tax’, ‘order_total’, ‘order_tax_detail’, ‘completed_date’,
</code>

But after this columns, you have those data that can’t be reordered :

<code>
//others ‘number_of_different_items’, ‘total_number_of_items’,
</code>

Products informations :

<code>‘used_coupons’, ‘coupon_name’, ‘coupon_discount’</code>


Standard data that can be reordered therebetween :

‘sku’ ‘name’ ‘quantity’ ‘line_price_without_taxes’ ‘line_price_with_taxes’

Then, come the custom product informations in the order you set them.

**2. How**

Using filters : For order informations : http://pastebin.com/0DnazUP1

For standart product informations : the filter name is : wcse_included_order_default_product_keys_filter

= I use WooCommerce Checkout Field Editor / WooCommerce Products Add-Ons, can I export the data added by those plugins ? =

Yes you can, but using a filter (small piece of code to put into your functions.php theme file).

For fields related to the order (WooCommerce Checkout Field Editor), you have to use this filter : http://pastebin.com/itLY3VAb

For fields related to the products (WooCommerce Products Add-Ons), you have to use this filter : http://pastebin.com/nEs8RNu0

Then you have to customize this code with your own keys that you want to export. You can find them in the back-end, when you are to the Smart Export panel, in the “help” tab top right, there is a Smart export tab with the all the keys you can use.

= Can I rename the columns ? =

You can rename the columns using a filter (small piece of code to put into your functions.php theme file) : http://pastebin.com/bbAjTrGU

== Changelog ==

= 1.1.0 =

* Show order item meta in the help tab
* Allow serialized data to be exported via filter
* Allow total custom filter for product
* Update notification in back-end
* New filter swep_data_to_repeat
* Remove line breaks in the order note
* Filters to translate column names
* Support of multiple shipping address
* improve memory usage in panel display, that caused some blank screens
* Filter on the customer role : http://pastebin.com/v9NqWxtn
* WC 2.2 : correct display of the order status


== Documentation ==

= Exporting Customers options =

* **User data**

There are four pre-configured sets of data when exporting customers. You need to select at least one. For each sets of data their are corresponding columns for your upcoming csv file. Here's the list :

User identity will extract the following fields :

<code>user_registered, user_login, user_email</code>

Billing informations will extract the following fields :

<code>
billing_first_name, billing_last_name, billing_company, billing_address_1,
billing_address_2, billing_city, billing_postcode, billing_country,
billing_state, billing_email, billing_phone
</code>

Shipping informations will extract the following fields :

<code>
shipping_first_name, shipping_last_name, shipping_company, shipping_address_1,
shipping_address_2, shipping_city, shipping_postcode, shipping_country,
shipping_state
</code>

Sales statistics will compute and add to your csv file the following columns :

<code>nb_order*, amount_total**</code>

*nb_order is total number of completed orders made by the customer.
**amount_total sums each completed orders made by the customer.

* **Date range**

If you want to export customers data only for a specific period of time, you can specify start and end date using the two dedicated field. To select the desired date just click on the field to popup the calendar and select one day. You can't select a day in the future. You don't have to specify two dates.

During customers export, the date filter filters the data based on registration date of users. Orders data concerning each customers will be selected within these two bounds.


= Exporting Customers options =

* **User data**

There are four pre-configured sets of data when exporting customers. You need to select at least one. For each sets of data their are corresponding columns for your upcoming csv file. Here's the list :

1. User identity will extract the following fields :

<code>user_registered, user_login, user_email</code>

2. Billing informations will extract the following fields :

<code>
billing_first_name, billing_last_name, billing_company, billing_address_1,
billing_address_2, billing_city, billing_postcode, billing_country,
billing_state, billing_email, billing_phone
</code>

3. Shipping informations will extract the following fields :

<code>
shipping_first_name, shipping_last_name, shipping_company, shipping_address_1,
shipping_address_2, shipping_city, shipping_postcode, shipping_country,
shipping_state
</code>

4. Sales statistics will compute and add to your csv file the following columns :

<code>nb_order*, amount_total**</code>

*nb_order is total number of completed orders made by the customer.
**amount_total sums each completed orders made by the customer.

* **Date range**

If you want to export customers data only for a specific period of time, you can specify start and end date using the two dedicated field. To select the desired date just click on the field to popup the calendar and select one day. You can't select a day in the future. You don't have to specify two dates.

During customers export, the date filter filters the data based on registration date of users. Orders data concerning each customers will be selected within these two bounds.


= Exporting Orders options =

* **order status**

The plugin will automatically lists all available order status in your WooCommerce installation. Native status and optionnaly extra status developped by yourself or third-party plugins. Note that status without any order won't be displayed. The number beside each status represents the actual number of orders stored in the database.

You may choose if the products will be displayed on line or on column. If you choose to display on line, columns will be added to fit the maximum number of items for one order in the exported set. If you choose columns, a with the product informations will be added under the order infos for each order's product.

Almost every fields from each order will be available in the CSV files :

<code>
			//general
			'id', 'status', 'order_date',
			
			//billing infos
			'billing_first_name', 'billing_last_name', 
			'billing_company', 'billing_address_1', 'billing_address_2','billing_city',  
			'billing_postcode', 'billing_country', 'billing_state', 'billing_email', 
			'billing_phone',
			
			//shipping infos
			'shipping_first_name', 'shipping_last_name', 
			'shipping_company', 'shipping_address_1', 'shipping_address_2', 
			'shipping_city', 'shipping_postcode', 'shipping_state', 'shipping_country',
			
			//note
			'customer_note', 
			
			//payment, shipping and total
			'shipping_method_title', 'payment_method_title', 'order_discount', 
			'cart_discount', 'order_tax', 'order_shipping', 'order_shipping_tax', 
			'order_total', 'order_tax_detail', 'completed_date',
			
			//others
			'number_of_different_items',
			'total_number_of_items',
			'used_coupons',
			'coupon_name',
			'coupon_discount'

</code>

* **Date range**

If you want to export orders data only for a specific period of time, you can specify start and end date using the two dedicated field. To select the desired date just click on the field to popup the calendar and select one day. You can't select a day in the future. You don't have to specify two dates.


= Exporting Coupons options =

* **Exported fields**

Here are the exported fields :

	* Order id
	* Order date
	* Number of used coupons

And for each used coupons :

	* Coupon name
	* Discount


* **Date range**

If you want to export orders data only for a specific period of time, you can specify start and end date using the two dedicated field. To select the desired date just click on the field to popup the calendar and select one day. You can't select a day in the future. You don't have to specify two dates.


= Important notes for scheduled orders export =

* If there is no new orders since last email, no email will be send.
* The scheduled events rely on WP crons. This ones are triggered by visitors coming on your site. In case of low traffic websites, emails can be postpone.
* If you deactivated the WP crons with DISABLE_WP_CRON in you config.php file, make sure you call the URL /wp-cron.php?doing_wp_cron regularly.


= Storage & Security =

This plugin is not storing any data in your WordPress database. For security reasons, the plugin & the export option is only available for administrators.

When the plugin is deactivated, all the cron are removed.


= Known issues =

* WooCommerce Smart Export Plugin takes advantage of the new "Date Query" introduced with WordPress 3.7. You need to upgrade WordPress if you want to use the plugin.
* WooCommerce Smart Export Plugin exported data (csv files) have been tested with a lot of differents configurations (OS and Tools). You should be able to open and read it with the defaults CSV files options. If you don't have any software capable of reading CSV files you can use Google Drive. It's online and free.
* If you have trouble exporting data, try diffents settings to limit the script duration. For example, you can reduce date range and/or exclude sales data when dealing with customers.


= Notes for developpers =

* **Availables filters:** Several filters are availables for developpers to manipulate default behaviour of the plugin :

* wcse_included_user_identity_keys_filter (an array of keys used against the customer object). These fields will be used and included in your csv files when "User identity" is checked in the Customer Export Screen.

* wcse_included_billing_information_keys_filter (an array of keys used against the customer object). These fields will be used and included in your csv files when "Billing Informations" is checked in the Customer Export Screen.

* wcse_included_shipping_information_keys_filter (an array of keys used against the customer object). These fields will be used and included in your csv files when "Shipping Informations" is checked in the Customer Export Screen.

* wcse_status_for_user_activity_filter (an array of order_status used when calculating user activity in the export users panel). Default is array('completed').

* wcse_included_order_keys_filter (an array of keys used against the order object). These fields will be used and included in your csv files when you are exporting orders.

* wcse_included_order_product_keys_filter : additionnal postmeta value of the product to get for each product. Default : empty array.

* wcse_included_order_default_product_keys_filter : array af the data retrieved for each product. Default : array('sku', 'name', 'quantity', 'line_price_without_taxes', 'line_price_with_taxes').

* wcse_caps : the ability required to do the export. Default : 'administrator'


= Usage =

**Adding a custom field to the orders export (field added by a plugin for instance) :**

<code>
// functions.php

function wcse_included_order_keys_filter_custom($keys){
        
		    array_push($keys, '_follow_colissimo');
		    return $keys;

}

add_filter( 'wwcse_included_order_keys_filter', 'wcse_included_order_keys_filter_custom' );
</code>


**Removing billing_company from the customer export files.**

<code>
// functions.php

function wcse_included_billing_information_keys_filter_custom($keys){
	
	unset($key['billing_company'])
    return $key;

}

add_filter( 'wcse_included_billing_information_keys_filter', 'wcse_included_billing_information_keys_filter_custom' );
</code>


**Adding a product post meta to the order export. (In this exemple, add the dimensions information)**

<code>
// functions.php

function custom_product_meta($array)
{
	$array[] = '_length';
	$array[] = '_width';
	$array[] = '_height';
	
	return $array;
}
add_filter('wcse_included_order_product_keys_filter', 'custom_product_meta');
</code>


**Default product data : keep just name and quantity**

<code>
// functions.php

function my_order_default_product_keys_filter($key)
{
	return array('name', 'quantity');
}
add_filter('wcse_included_order_default_product_keys_filter', 'my_order_default_product_keys_filter');
</code>


**Adding a column with fistname and lastname in the same column.**

<code>
// functions.php

function add_complete_name($keys){
	array_push($keys, 'billing_complete_name');
	array_push($keys, 'shipping_complete_name');
	return $keys;
}

add_filter( 'wcse_included_order_keys_filter', 'add_complete_name' );
</code>


**Adding a custom time interval**

(See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules)

<code>
// functions.php

function my_add_weekly( $schedules ) {
	// add a 'weekly' schedule to the existing set
	$schedules['weekly'] = array(
		'interval' => 604800, //interval of one week in seconds
		'display' => __('Once Weekly') //name of the interval
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'my_add_weekly' );
</code>