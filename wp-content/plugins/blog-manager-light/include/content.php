<?php
/**
 * File Used to predefine variables 
 * $content - used on Add New List - preset default values
 * $widgets - used on Add New List - determin if a list is a widget or not
 */

  // Default Form Values
  $content = array(
    'list_name'               => '',
    'template'                => 0,
    'categories'              => '',
    'select_categories'       => '',
    'all_categories'          => '',
    'tags'                    => '',
    'select_tags'             => '',
    'all_tags'                => '',
    'users'                   => '',
    'select_users'            => '',
    'all_users'               => '',
    'blog-items'              => 'media,title,meta,description,continue-reading',
    'meta-items'              => 'author,date,category,tags,comments',
    'posts_limit'             => '',
    'posts_limit_skip'        => '',
    'posts_order'             => 'created_asc',
    'blog_list_title'         => '',
    'view_all_page'           => '',
    'view_all_page_text'      => '',
    'continue_reading'        => '',
    'posts_limit_page'        => 10,
    'excerpt_length'          => 20,
    'image_link'              => 'single',
    'title_link'              => 'single',
    'image_hover'             => 'hover-style-1-full',
    'icon_hover'              => 0, 
    'show-pagination'         => 1,
    'show-post-icon'          => 0,
    'show-delimiter'          => 0,
    'show-border'             => 0,
    'show-background'         => 0,
    'show-social-icons'       => 1,
    'view_all_page'           => '',
    'view_all_page_link'      => '',
    'view_all_target'         => '_self',
    'meta_type_align'         => 'horizontal',
    'meta_type'               => 'full',
    'meta_icons'              => 0,
    'date_created'            => '',
    'date_modified'           => '',
    'show-slider-title'       => 1,
    'space-tiles'             => 0,
    'title-color'             => '',
    'title_font'              => '',
    'title-font-size'         => '',
    'title-font-style'        => '',
    'meta-color'              => '',
    'meta_font'               => '',
    'meta-font-size'          => '',
    'meta-font-style'         => '',
    'excpert-font-size'       => '',
    'excpert-font-style'      => '',
    'excpert-color'           => '',
    'excpert_font'            => '',
    'read-more-color'         => '',
    'read-more_font'          => '',
    'read-more-font-size'     => '',
    'read-more-font-style'    => '',
    'custom_css'              => '',
    'slider_title_alignment'  => '',
    'slider_border'           => 0,
    'slider_title_bg'         => 1,
    'slider_nav'              => 1,
    'media_width'             => '',
    'cat-tag-relation'        => 'OR',
    'show-news-cat-filter'    => 1,
    'show-news-sort-filter'   => 1,
    'show-carousel-nav'       => 1,
    'slider-auto-scroll'      => 1,
    'show-mosaic-cat-filter'  => 1,
    'show-mosaic-sort-filter' => 1,
    'mosaic-content'          => 1,
    'horizontal-space-tiles'  => 0,
    'horizontal-content'      => 1
  );

  $widgets = array(
    'widget-lft', 'widget-rght', 'widget-top',
    '2-column-carousel-wid', '3-column-carousel-wid', '4-column-carousel-wid'
  );

  $templateOptions = array(
    array(
      'name'    => '1-column',
      'width'   => 740,
      'height'  => 340,
      'crop'    => false
    ),
    array(
      'name'    => '2-column',
      'width'   => 460,
      'height'  => 250,
      'crop'    => false
    ),
    array(
      'name'    => '3-column',
      'width'   => 300,
      'height'  => 160,
      'crop'    => true
    ),
    array(
      'name'    => '4-column',
      'width'   => 220,
      'height'  => 120,
      'crop'    => false
    ),
    array(
      'name'    => '1-column-lft-img',
      'width'   => 300,
      'height'  => 220,
      'crop'    => false
    ),
    array(
      'name'    => '2-column-lft-img',
      'width'   => 200,
      'height'  => 200,
      'crop'    => false
    ),
    array(
      'name'    => '1-column-rght-img',
      'width'   => 300,
      'height'  => 220,
      'crop'    => false
    ),
    array(
      'name'    => '2-column-rght-img',
      'width'   => 200,
      'height'  => 200,
      'crop'    => false
    ),
    array(
      'name'    => '2-column-news',
      'width'   => 460,
      'height'  => 0,
      'crop'    => false
    ),
    array(
      'name'    => '3-column-news',
      'width'   => 350,
      'height'  => 0,
      'crop'    => false
    ),
    array(
      'name'    => '4-column-news',
      'width'   => 300,
      'height'  => 0,
      'crop'    => false
    ),
    array(
      'name'    => '1-3-mosaic',
      'width'   => 600,
      'height'  => 1400,
      'crop'    => true
    ),
    array(
      'name'    => '1-4-mosaic',
      'width'   => 600,
      'height'  => 1400,
      'crop'    => false
    ),
    array(
      'name'    => 'timeline',
      'width'   => 460,
      'height'  => 0,
      'crop'    => false
    ),
    array( 
      'name'    => 'widget-lft', 
      'width'   => 60,
      'height'  => 60,
      'crop'    => false
    ),
    array( 
      'name'    => 'widget-rght', 
      'width'   => 60,
      'height'  => 60,
      'crop'    => false
    ),
    array( 
      'name'    => 'widget-top', 
      'width'   => 250,
      'height'  => 150,
      'crop'    => false
    ),
    array( 
      'name'    => 'slider', 
      'width'   => 960,
      'height'  => 300,
      'crop'    => false
    ),
    array( 
      'name'    => '3-column-carousel', 
      'width'   => 250,
      'height'  => 150,
      'crop'    => false
    ),
    array( 
      'name'    => '4-column-carousel', 
      'width'   => 120,
      'height'  => 80,
      'crop'    => false
    ),
    array( 
      'name'    => '5-column-carousel', 
      'width'   => 100,
      'height'  => 50,
      'crop'    => false
    ),
    array( 
      'name'    => '2-column-carousel-wid', 
      'width'   => 80,
      'height'  => 80,
      'crop'    => false
    ),
    array( 
      'name'    => '3-column-carousel-wid', 
      'width'   => 60,
      'height'  => 60,
      'crop'    => false
    ),
    array( 
      'name'    => '4-column-carousel-wid', 
      'width'   => 40,
      'height'  => 40,
      'crop'    => false
    ),
    array( 
      'name'    => 'horizontal-layout', 
      'width'   => 740,
      'height'  => 230,
      'crop'    => true
    ),
  );