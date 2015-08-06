<?php
/**
 * Class Used to interface with the DB
 */

if( !class_exists('OTWBMQuery') ) {

class OTWBMQuery {

  public $listOptions = null;

  public function __construct() {}

  /**
   * Get POSTS based on the selected options for a list
   * @param $options array()
   * @param $page - int - Used for paged results
   * @return array()
   */
  public function getPosts ( $options = array(), $page = null ) {
    $otw_bm_posts = array();

    if( !empty( $options ) ) {
      
      $this->listOptions = $options;

      $template   = $options['template'];
      $categories = $options['categories'];
      $tags       = $options['tags'];
      $authors    = $options['users'];
      $order      = explode('_', $options['posts_order']);

      ( !empty( $categories ) )? $categoriesArray = explode(',', $categories) : $categoriesArray = '';
      ( !empty( $tags ) )? $tagsArray = explode(',', $tags) : $tagsArray = '';
      ( empty($options['cat-tag-relation']) )? $catTagRelation = 'OR' : $catTagRelation = $options['cat-tag-relation'];

      // Code Use to get only posts with attachment
      $metaQuery = null;
      $sliderArray = array(
        'slider', '3-column-carousel', '4-column-carousel', '5-column-carousel',
        '2-column-carousel-wid', '3-column-carousel-wid', '4-column-carousel-wid',
        '1-3-mosaic', '1-4-mosaic', 'horizontal-layout'
      );
      if( in_array( $options['template'] , $sliderArray) ) {
        $metaQuery = array(
            'relation' => 'OR',
            array(
              'key' => '_thumbnail_id'
            ),
            array(
              'key'     => 'otw_bm_meta_data',
              'compare' => 'EXISTS'
            )
        );
      }

      // Used for pagination
      $currentPage = ( !empty($page) ) ? $page : 1;

      if( $template == 'timeline' ) {
        // If we have a timeline Layout we need to ignore the selected Order Options and use custom ones.
        $order[0] = 'date';
        $order[1] = 'DESC';
      }

      if( !empty($options['posts_limit']) ) {
	    //add_filter( 'post_limits', array($this, 'filterLimit'), 10, 2);
	    //$options['posts_limit_page'] = $this->listOptions['posts_limit'];
      }

      // For more information about the query, visit: http://codex.wordpress.org/Class_Reference/WP_Query
      $queryBM = array(
        'category__in'    => $categoriesArray, // OR category__in
        'tag__in'         => $tagsArray,
        'meta_query'      => $metaQuery,
        'post_status'     => 'publish',
        'posts_per_page'  => $options['posts_limit_page'],
        'paged'           => $currentPage,
        'orderby'         => $order[0], //Order Field
        'order'           => $order[1], // Order Value (ASC, DESC)
        'tax_query'       => array(
          'relation'      => $catTagRelation,
        ),
      );
      
	if( empty( $options['author-relation'] ) || ( $options['author-relation'] !== 'or' ) ){
		$queryBM['author'] = $authors;
	}else{
		add_filter( 'posts_where', array( $this, 'addORAuthors' ) );
	}

      if( !empty($options['posts_limit_skip']) ){
        
        $querySKIP = $queryBM;
        unset( $querySKIP['posts_per_page'] );
        unset( $querySKIP['paged'] );
        add_filter( 'post_limits', array($this, 'filterSkipLimit'), 10, 2);
		
        wp_reset_query();

        $otw_bm_post_skip_ids = new WP_Query( $querySKIP );

        remove_filter('post_limits', array($this, 'filterSkipLimit'), 10, 2);

        if( isset( $otw_bm_post_skip_ids->posts ) && count( $otw_bm_post_skip_ids->posts ) ) {
          $skip_post_ids = array();

          foreach( $otw_bm_post_skip_ids->posts as $skip_post_data ):
            $skip_post_ids[ $skip_post_data->ID ] = $skip_post_data->ID;
          endforeach;
        
          $queryBM['post__not_in'] = $skip_post_ids;
        }
		
      }
        
      if( !empty($options['posts_limit']) ) {
		
        $queryID = $queryBM;
        unset( $queryID['posts_per_page'] );
        unset( $queryID['paged'] );
        add_filter( 'post_limits', array($this, 'filterLimit'), 10, 2);
		
        wp_reset_query();
        $otw_bm_post_ids = new WP_Query( $queryBM );
        remove_filter('post_limits', array($this, 'filterLimit'), 10, 2);

        if( isset( $otw_bm_post_ids->posts ) && count( $otw_bm_post_ids->posts ) ) {
          $post_ids = array();
			
          foreach( $otw_bm_post_ids->posts as $post_data ):
            $post_ids[ $post_data->ID ] = $post_data->ID;
          endforeach;

          $queryBM['post__in'] = $post_ids;
			
          wp_reset_query();

          $otw_bm_posts = new WP_Query( $queryBM );
			
        } else {
          $otw_bm_posts = $otw_bm_post_ids;
        }

      } else {
        wp_reset_query();
        $otw_bm_posts = new WP_Query( $queryBM );
      }
      
    }

  	if( !empty( $options['author-relation'] ) && ( $options['author-relation'] === 'or' ) ){
  		remove_filter( 'posts_where', array( $this, 'addORAuthors' ) );
  	}

    return $otw_bm_posts;
    
  }

	public function addORAuthors( $query ){
		
		global $wpdb;
		
		if( !empty( $this->listOptions['users'] ) ){
			if( preg_match( "/AND (\(.*term_taxonomy_id.*\)) AND/", $query, $matches ) ){
			
				$query = str_replace( $matches[1], '('.$matches[1]." OR {$wpdb->posts}.post_author IN (".$this->listOptions['users'].') ) ', $query );
			}else{
				$query .= " AND {$wpdb->posts}.post_author IN (".$this->listOptions['users'].") ";
			}
		}
		
		return $query;
	}
  public function filterLimit( $limit, $query ) {
     return 'LIMIT 0, '. $this->listOptions['posts_limit'];
  }
  
  public function filterSkipLimit( $limit, $query ) {
     return 'LIMIT 0, '. $this->listOptions['posts_limit_skip'];
  }

  /**
   * Get a list of all the item in the DB
   * @return array()
   */
  public function getLists () {
    $otw_lists = get_option( 'otw_bm_lists' );

    return $otw_lists;
  }

  /**
   * Get a specific item based on it's ID
   * @param $id - int
   * @return array()
   */
  public function getItemById ( $id = null ) {
    $otw_lists = get_option( 'otw_bm_lists' );

    if( !empty( $otw_lists['otw-bm-list']['otw-bm-list-'.$id] ) ) {
      $otw_list = $otw_lists['otw-bm-list']['otw-bm-list-'.$id];
      return $otw_list; 
    }

    return null;
  }

  /**
   * Get All Categories That have content and prepare the content for Select2 jQuery plugin use
   * @return array()
   */
  public function select2Categories () {
    $categories = get_categories( array( 'hide_empty' => 0 ) );
    $catCount = 0;
    $categoriesData = '';
    foreach( $categories as $category ):
      $categoriesData[$catCount]['id'] = $category->term_id;
      $categoriesData[$catCount]['text'] = $category->name;
      $catCount++;
    endforeach;

    return array(
      'categories'  => $categoriesData,
      'count'       => $catCount
    );
  }

  /**
   * Get All Tags That have content and prepare the content for Select2 jQuery plugin use
   * @return array()
   */
  public function select2Tags () {
    $tags = get_tags( array( 'hide_empty' => 0 ) );
    $tagCount = 0;
    $tagsData = '';
    foreach( $tags as $tag ):
      $tagsData[$tagCount]['id'] = $tag->term_id;
      $tagsData[$tagCount]['text'] = $tag->name;
      $tagCount++;
    endforeach;

    return array(
      'tags'  => $tagsData,
      'count' => $tagCount
    );
  }

  /**
   * Get All Users and prepare the content for Select2 jQuery plugin use
   * @return array()
   */
  public function select2Users () {
    $users = get_users();
    $userCount = 0;
    $usersData = '';
    foreach( $users as $user):
      $usersData[$userCount]['id'] = $user->data->ID;
      $usersData[$userCount]['text'] = $user->data->user_login;
      $userCount++;
    endforeach;

    return array(
      'users' => $usersData,
      'count' => $userCount
    );
  }

  /**
   * Get All Page and prepare the content for Select2 jQuery plugin use
   * @return array()
   */
  public function select2Pages () {
    $pages = get_pages();
    $pageCount = 0;
    $pagesData = '';
    foreach( $pages as $page ):
      $pagesData[$pageCount]['id'] = $page->ID;
      $pagesData[$pageCount]['text'] = $page->post_title;
      $pageCount++;
    endforeach;

    return array(
      'pages' => $pagesData,
      'count' => $pageCount
    );
  }


}

} // End if class exists