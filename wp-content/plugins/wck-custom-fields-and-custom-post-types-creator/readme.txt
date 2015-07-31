=== WCK - Custom Fields and Custom Post Types Creator === 

Contributors: reflectionmedia, madalin.ungureanu, sareiodata
Donate link: http://www.cozmoslabs.com/wordpress-creation-kit/
Tags: custom fields, custom field, wordpress custom fields, advanced custom fields, custom post type, custom post types, post types, cpt, post type, repeater fields, repeater, repeatable fields, meta box, metabox, taxonomy, taxonomies, custom taxonomy, custom taxonomies, custom, custom fields creator, post meta, meta, get_post_meta, post creator, cck, content types, types

Requires at least: 3.1
Tested up to: 4.1.1
Stable tag: 1.1.5

A must have tool for creating custom fields, custom post types and taxonomies, fast and without any programming knowledge.


== Description ==

**WordPress Creation Kit** consists of three tools that can help you create and maintain custom post types, custom taxonomies and most importantly, custom fields and metaboxes for your posts, pages or CPT's.

**WCK Custom Fields Creator** offers an UI for setting up custom meta boxes with custom fields for your posts, pages or custom post types. Uses standard custom fields to store data.

**WCK Custom Post Type Creator** facilitates creating custom post types by providing an UI for most of the arguments of register_post_type() function.

**WCK Taxonomy Creator** allows you to easily create and edit custom taxonomies for WordPress without any programming knowledge. It provides an UI for most of the arguments of register_taxonomy() function.

[youtube http://www.youtube.com/watch?v=_ueYKlP_i7w]

= Custom Fields =
* Custom fields types: wysiwyg editor, upload, text, textarea, select, checkbox, radio
* Easy to create custom fields for any post type.
* Support for **Repeater Fields** and **Repeater Groups** of custom fields.
* Drag and Drop to sort the Repeater Fields.
* Support for all input custom fields: text, textarea, select, checkbox, radio.
* Image / File upload supported via the WordPress Media Uploader.
* Possibility to target only certain page-templates, target certain custom post types and even unique ID's.
* All data handling is done with Ajax
* Data is saved as postmeta

= Custom Post Types and Taxonomy =
* Create and edit Custom Post Types from the Admin UI
* Advanced Labeling Options
* Attach built in or custom taxonomies to post types
* Create and edit Custom Taxonomy from the Admin UI
* Attach the taxonomy to built in or custom post types

= WCK PRO =
  The [WCK PRO version](http://www.cozmoslabs.com/wck-custom-fields-custom-post-types-plugin/) offers:
  
* **Swift Templates** - Build your front-end templates directly from the WordPress admin UI, without writing any PHP code. Easily display registered custom post types, custom fields and taxonomies in your current theme.
* Front-end Posting - form builder for content creation and editing
* Options Page Creator - create option pages for your theme or your plugin
* More field types: Date-picker, Country Select, User Select, CPT Select
* Premium Email Support for your project
  
 [See complete list of PRO features](http://www.cozmoslabs.com/wck-custom-fields-custom-post-types-plugin/)

= Website =
http://www.cozmoslabs.com/wck-custom-fields-custom-post-types-plugin/

= Announcement Post and Video =
http://www.cozmoslabs.com/3747-wordpress-creation-kit-a-sparkling-new-custom-field-taxonomy-and-post-type-creator/

= Documentation =
http://www.cozmoslabs.com/docs/wordpress-creation-kit-documentation/

= Bug Submission and Forum Support =
http://www.cozmoslabs.com/forums/forum/wordpresscreationkit/

== Installation ==

1. Upload the wordpress-creation-kit folder to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Then navigate to WCK => Custom Fields Creator tab and start creating your custom fields, or navigate to WCK => Post Type Creator tab and start creating your custom post types or navigate to WCK => Taxonomy Creator tab and start creating your taxonomies.

== Frequently Asked Questions ==

= How do I display my custom fields in the front end? =

Let's consider we have a meta box with the following arguments:
- Meta name: books
- Post Type: post
And we also have two fields defined:
- A text custom field with the Field Title: Book name
- And another text custom field with the Field Title: Author name

You will notice that slugs will automatically be created for the two text fields. For 'Book name' the slug will be 'book-name' and for 'Author name' the slug will be 'author-name'

Let's see what the code for displaying the meta box values in single.php of your theme would be:

`<?php $books = get_post_meta( $post->ID, 'books', true ); 
foreach( $books as $book){
	echo $book['book-name'] . '<br/>';
	echo $book['author-name'] . '<br/>';
}?>`

So as you can see the Meta Name 'books' is used as the $key parameter of the function get_post_meta() and the slugs of the text fields are used as keys for the resulting array. Basically CFC stores the entries as custom fields in a multidimensional array. In our case the array would be:

`<?php array( array( "book-name" => "The Hitchhiker's Guide To The Galaxy", "author-name" => "Douglas Adams" ),  array( "book-name" => "Ender's Game", "author-name" => "Orson Scott Card" ) );?>`

This is true even for single entries.

= How to query by post type in the front-end? =

You can create new queries to display posts from a specific post type. This is done via the 'post_type' parameter to a WP_Query.

Example:

`<?php $args = array( 'post_type' => 'product', 'posts_per_page' => 10 );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
	the_title();
	echo '<div class="entry-content">';
	the_content();
	echo '</div>';
endwhile;?>`

This simply loops through the latest 10 product posts and displays the title and content of them. 

= How do I list the taxonomies in the front-end? =

If you want to have a custom list in your theme, then you can pass the taxonomy name into the the_terms() function in the Loop, like so:

`<?php the_terms( $post->ID, 'people', 'People: ', ', ', ' ' ); ?>`

That displays the list of People attached to each post.

= How do I query by taxonomy in the front-end? =

Creating a taxonomy generally automatically creates a special query variable using WP_Query class, which we can use to retrieve posts based on. For example, to pull a list of posts that have 'Bob' as a 'person' taxomony in them, we will use:

`<?php $query = new WP_Query( array( 'person' => 'bob' ) ); ?>`

==Screenshots==
1. Creating custom post types and taxonomies
2. Creating custom fields and meta boxes
3. Custom Fields Creator - list of Meta boxes
4. Meta box with custom fields
5. Defined custom fields
6. Custom Fields Creator - Meta box arguments
7. Post Type Creator UI
8. Post Type Creator UI and listing
9. Taxonomy Creator UI
10. Taxonomy listing

== Changelog ==
= 1.1.5 =
* Fixed major issue that prevented publishing new metaboxes (CFC)
* Added a footer message asking users to leave a review if they enjoyed WCK

= 1.1.4 =
* Changed the way Single Forms are displayed and saved.
* Added 'slug' parameter to API and we use it so we can translate labels
* Added filter for taxonomy term name
* Added support for search in media library for the upload field
* Add support for the link in the listed upload fields
* Add support for link on image/icon that points to attachement page in backend
* Changed the order of the CKEDITOR.config.allowedContent = true to be above the call to initialized the textarea
* Now metaboxes or pages don't appear for users that shouldn't

= 1.1.3 =
* Wysiwyg editor fields no longer strips html tags
* Changes to WCK deactivate function so it doesn't throw notices

= 1.1.2 =
* Added filters which we can use to modify the text on metabox buttons in the backend (ex. Add Entry)
* Fixed a bug that when we had unserialized fields enabled and we deleted some items in the metabox they still remained in the database
* Fixed some PHP Warnings and Notices

= 1.1.1 =
* Now we can add the same metabox from CFC on multiple ids
* Added filter for the arguments passed to the register_taonomy() funtion when creating a Custom Taxonomy. ( "wck_ctc_register_taxonomy_args" )
* Fixed bug that was executing  shortcodes inside escaped shortcodes [[shortcode]]
* Fixed problem in CPTC that was setting the 'publicly_queryable' argument as true

= 1.1.0 =
* Added filter for the arguments passed to the register_post_type() funtion when creating a Custom Post Type. ( "wck_cptc_register_post_type_args" )
* Fixed the missing datepicker css 404 error. 
* Removed notices  
* Fixed "Attach upload to post" option for the upload field.

= 1.0.9 =
* Replaced wysiwyg editor from tinymce to ckeditor to fix compatibility issues with WordPress 3.9

= 1.0.8 =
* Upload Field now uses the media manager added in WP 3.5
* Now we prevent "Meta Field" and "Field Title" to be named "content" or "action" in Custom Fields Creator to prevent conflicts with existing WordPress Fields
* Fixed bug in Custom Fields Creator that didn't display "0" values
* Added Spanish translation ( thanks to Andrew Kurtis for providing the translation files )


= 1.0.7 =
* Small compatibility tweaks for WordPress 3.8

= 1.0.6 =
* WCK menu now only appears for Administrator role only
* Minor fixes and improvements

= 1.0.5 =
* Fixed error from 1.0.4 require_once

= 1.0.4 =
* Added Custom Fields Api
* Added option to enable/disable WCK tools(CFC, CPTC, FEP...) that you want/don't want to use 
* Labels of required custom fields turn red when empty 
* Added in Custom Taxonomy Creator support for show_admin_column argument that allows automatic creation of taxonomy columns on associated post-types
* Improved visibility of WCK Help tab
* We no longer get js error when deregistering wysiwig init script

= 1.0.3 =
* Removed all notices and warnings from the code

= 1.0.2 =
* Fixed bug when arguments contained UTF8 characters ( like hebrew, chirilic... )
* Fixed Sortable field in Custom Fields Creator that wasn't clickable

= 1.0.1 =
* Fixed Menu Position argument for Custom Post Type Creator.
* Added filter for default_value.
* Fixed Template Select dropdown for Custom Fields Creator.
* Fixed a bug in Custom Fields Creator that prevented Options field in the process of creating custom fields from appearing.