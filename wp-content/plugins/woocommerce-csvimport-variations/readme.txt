Add-on Name: Import variable products
Author: Allaerd Mensonides
URL: http://allaerd.org 
Copyright © 2012

1. Install
* Make sure you have the free Woocommerce CSV import installed and active (you can find it here: http://wordpress.org/plugins/woocommerce-csvimport/)
* Upload the ZIP file like a regular plugin. (plugins->add new->upload) or copy the directory to the plugin directory in FTP.

2. Usage
* create the attributes in advance! The values of the attributes will be created for you!
* check the CSV Import->variations screen to see what attributes are available
* create a new header with all the right fields.
* check the example CSV included if you do not know hwere to start.
* check the tutorial when you are stuck

3. Fields and descriptions
* product_type
The possible values are: <code>variation_master</code> for the master and <code>product_variation</code> for the product child

* post_parent
In here you enter the SKU of the variation master.

* variation
This field is meant to setup which variations are used and how the are used. The way to setup is variation->is_visible->is_used_for_variation. If you look at this example color->1->1|size->1->0, we have 2 attributes. Color is visible and is used for variations. And size is visible but not used for variations.

* default_attributes
If you want the predefined values, you can add them using default attributes. If you want blue to be default for color and medium to be default for size you can set it up like this: <code>color->blue|size->medium</code>.

