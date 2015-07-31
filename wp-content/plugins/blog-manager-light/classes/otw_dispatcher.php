<?php
if( !class_exists('OTWDispatcher') ) {

class OTWDispatcher {

  // Blog Specific Items (Title, Media, Meta, Excerpt, Continue Read)
  public $blogItems = null;

  // Meta Items Speicif (Author, Comments, Tags, Categories, Date)
  public $metaItems = null;

  // List Options
  public $listOptions = null;

  // Image Crop Class
  public $otwImageCrop = null;

  public $mediaContainer = 'otw_blog_manager-format-image';

  public $containerBG = null;

  public $containerBorder = null;

  public $postIcon = null;

  // Different image resolutions based on template selection
  private $templateOptions = null;

  private $ajaxPageNo = null;

  // Width - used by templates for image resize
  public $imageWidth  = 250;
  // Height - used by templates for image resize
  public $imageHeight = 340;
  // Add white spaces to images when their size is smaller that the required thumbnail
  public $imageWhiteSpaces = true;
  //default background for white spaces of the thumbs
  public $imageBackground = '#FFFFFF';
  //the type of image croping
  public $imageCrop = 'center_center';


  public function __construct() {}

  /**
   * generateTemplate - Get all the components into one big HTML chuck and output them based on filter (raw or normal)
   * @param $bm_options - array() - full list o list options
   * @param $bm_results - array() - list of posts that are used as content providers
   * @return mixed
   */
  public function generateTemplate ( $bm_options = null, $bm_results = null, $templateMediaOptions = null, $ajax = false, $ajaxPage = null ) {

    if( empty( $bm_options ) || empty( $bm_results ) ) {
      throw new Exception(_e('There was an error in OTWDispatcher: $results or $options is missing', OTW_BML_TRANSLATION), 1);
    }
    $this->otwImageCrop = new OTWBMLImageCrop();
    
    $this->blogItems        = $bm_options['blog-items'];
    $this->metaItems        = 'author,date,category,tags,comments';//$bm_options['meta-items'];
    $this->listOptions      = $bm_options;
    $this->templateOptions  = $templateMediaOptions;
    $this->ajaxPageNo       = $ajaxPage;
    
    $this->containerBG      = null;
    $this->containerBorder  = null;

    if( !empty( $this->listOptions['show-background'] ) ){
      $this->containerBG = 'with-bg';
    }

    if( !empty( $this->listOptions['show-border'] ) ){
      $this->containerBorder = 'with-border';
    }
    
	    $this->listOptions['meta_icons'] = '';
    
    $outputHtml = $this->loadTemplate( $bm_options['template'], $bm_results );
    $outputHtml = '<div class="otw-row">'. $outputHtml . '</div>';
    // Hack to solve some idiotic themes that use remove_filters for wpautop
    if( !has_filter( 'the_content', 'wpautop' ) && !$ajax && !$bm_options['widget']) {
      return '[raw]'.$outputHtml.'[/raw]';
    } else {
      return $outputHtml; 
    }

  }

  /**
   * Get Blog Items in the specific order
   * @param $templateItems - string (format: title,media,meta,description,continue-reading)
   * @return void()
   */
  private function buildInterfaceBlogItems ( $post ) {
    if( empty( $post ) ) {
      throw new Exception(_e('There was an error in OTWDispatcher -> buildInterfaceBlogItems ', OTW_BML_TRANSLATION), 1);
    }

    $items = explode(',', $this->blogItems);
    $postMetaData = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
    
    $interfaceHTML = '';

    foreach( $items as $item ): 
      switch ( $item ) {
        case 'title':
          $interfaceHTML .= $this->getTitle( $post );
        break;
        case 'media':
          $interfaceHTML .= $this->getMedia( $post, $postMetaData );
        break;
        case 'meta':
          $interfaceHTML .= $this->buildInterfaceMetaItems( $this->metaItems, $post );
        break;
        case 'description':
          $interfaceHTML .= $this->getContent( $post );
        break;
        case 'continue-reading':
          $interfaceHTML .= $this->getContinueRead( $post );
        break;
      }
    endforeach;

    return $interfaceHTML;

  }

  /**
   * Get Meta Items in the specific order
   * @param $metaItems - string (format: author,date,category,tags,comments)
   * @return void()
   */
  private function buildInterfaceMetaItems ( $metaItems = null, $post ) {

    $items = explode(',', $this->metaItems);
    
    $metaHTML = '';
    foreach( $items as $item ) :
      switch ( $item ) {
        case 'author':
          $metaHTML .= $this->loadComponent( 'meta_authors', $post );
        break;
        case 'date':
          $metaHTML .= $this->loadComponent( 'meta_date', $post );
        break;
        case 'category':
          $metaHTML .= $this->loadComponent( 'meta_categories', $post );
        break;
        case 'tags':
          $metaHTML .= $this->loadComponent( 'meta_tags', $post );
        break;
        case 'comments':
          $metaHTML .= $this->loadComponent( 'meta_comments', $post );
        break;
      }
    endforeach;

    return $this->loadWrapper('meta', $metaHTML);

  }

  /**
   * getTitle - Get Item (Post) Title
   * @param $post - array
   * @return mixed
   */
  private function getTitle ( $post ) {
    return $this->loadComponent( 'title', $post );
  }

  /**
   * getMedia - Get Item's Media. Featured Image, Custom Post Data [Image, Slider, Vimeo, YouTube, SoundCloud]
   * @param $post - array
   * @param $postMetaData - array
   * @return mixed
   */
  private function getMedia ( $post, $postMetaData = null ) {

    if( empty( $postMetaData ) ) {
      $postMetaData = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
    }

    // Get Featured Image
    $postAttachement = $this->getPostAsset( $post );

    // If we don't have an asset or media item (Vimeo, YouTube, etc). return null
    if( empty( $postMetaData ) && empty( $postAttachement ) ) {
      return null;
    }
    
    // Post that has no Meta Data - Image Attached Via OTW Meta Box, Vimeo, YouTube, etc
    if( empty( $postMetaData ) && !empty( $postAttachement ) ) {
      $postMetaData['media_type']   = 'wp-native';
      $postMetaData['featured_img'] = $postAttachement;
    }
    // Set Class For Sliders
    $this->mediaContainer = 'otw_blog_manager-format-image';
    if( !empty( $postMetaData ) && !empty($postMetaData['slider_url']) ) {
      $this->mediaContainer = 'otw_blog_manager-format-gallery';
    }

    // Set Width and Height of the Media Item
    $this->getMediaProportions();

    return $this->loadComponent( 'media', $post, $postMetaData );
  }

  /**
   * getContent - Get Post Content. Strip Tags, get Content or Excpert. Word count output
   * @param $post - array
   * @return mixed
   */
  private function getContent ( $post ) {
    return $this->loadComponent( 'content', $post );
  }

  /**
   * getContinueRead - Get Post Link. Create link with custom text
   * @param $post - array
   * @return mixed
   */
  private function getContinueRead ( $post ) {
    return $this->loadComponent( 'continue_read', $post );
  }

  /**
   * getSocial - Get Social Links for a specific Post
   * @param $post - array
   * @return mixed
   */
  private function getSocial ( $post ) {
    if( !empty( $this->listOptions['show-social-icons'] ) ) {
      return $this->loadComponent( 'social', $post );
    }
  }

  /**
   * getDelimiter - Get Post delimiter
   * @param $post - array
   * @return array
   */
  private function getDelimiter ( $post ) {
    if( $this->listOptions['show-delimiter'] ) {
      return $this->loadComponent( 'delimiter', $post );
    }
  }

  /**
   * getPagination - Get Pagination HTML based on the selection made. Standard, Load Mode, Infinite Scroll
   * @param $otw_bm_posts - array
   * @return mixed
   */
  private function getPagination( $otw_bm_posts ) {
    if( !empty( $this->listOptions['show-pagination'] ) ) {

      if( !empty( $this->listOptions['posts_limit'] ) && ( $this->listOptions['posts_limit'] <= $this->listOptions['posts_limit_page'] ) ) {
        return;
      }
      return $this->loadComponent( 'pagination', null, null, $otw_bm_posts );
    }
  }

  /**
   * getWidgetPagination - Widget Will only support Load More pagination
   * @param $otw_bm_posts
   * @return mixed
   */
  private function getWidgetPagination( $otw_bm_posts ) {
    if( !empty( $this->listOptions['show-pagination'] ) && $this->listOptions['show-pagination'] == 'load-more' ) {

      if( !empty( $this->listOptions['posts_limit'] ) && ( $this->listOptions['posts_limit'] <= $this->listOptions['posts_limit_page'] ) ) {
        return;
      }
      return $this->loadComponent( 'widget_pagination', null, null, $otw_bm_posts );
    }
  }

  /**
   * getInfiniteScroll - Get Infinite Scroll options
   * @return string
   */
  private function getInfiniteScroll() {
    $infinitScroll = '';
    if( !empty($this->listOptions['show-pagination']) && $this->listOptions['show-pagination'] == 'infinit-scroll' ) {
      $infinitScroll = 'otw_blog_manager-infinite-scroll';
    }

    return $infinitScroll;
  }

  /**
   * getInfiniteScrollGrid - Get Infinite Scroll for Grid Templates
   * @return string
   */
  public function getInfiniteScrollGrid() {
    $infinitScroll = '';

    if( !empty($this->listOptions['show-pagination']) && $this->listOptions['show-pagination'] == 'infinit-scroll' ) {
      $infinitScroll = 'otw_blog_manager-infinite-pagination-holder';
    }

    return $infinitScroll;
  }

  /**
   * getInfiniteScrollHorizontal - Get Infinite Scroll For Horizontal Layout
   * @return string
   */
  public function getInfiniteScrollHorizontal() {
    $infinitScroll = '';
    if( !empty($this->listOptions['show-pagination']) && ($this->listOptions['show-pagination'] == 'infinit-scroll') ) {
      $infinitScroll = 'otw_blog_manager-horizontal-layout-items-infinite-scroll';
    }

    return $infinitScroll;
  }

  /**
   * getNewsFilter - Get Filter for news
   * @return mixed
   */
  private function getNewsFilter () {
    
    if( empty( $this->ajaxPageNo ) ) {
      if( !empty( $this->listOptions['show-news-cat-filter'] ) ) {
        return $this->loadComponent( 'news_filter' );  
      }
    }
  }

  private function getMosaicFilter () {
    if( empty( $this->ajaxPageNo ) ) {
      if( !empty( $this->listOptions['show-mosaic-cat-filter'] ) ) {
        return $this->loadComponent( 'news_filter' );  
      }
    }
  }

  /**
   * getNewsSort - Get News Sort Options
   * @return mixed
   */
  private function getNewsSort () {
    if( empty( $this->ajaxPageNo ) ) {
      if( !empty( $this->listOptions['show-news-sort-filter'] ) ){
        return $this->loadComponent( 'news_sort' );  
      }
    }
  }

  private function getMosaicSort () {
    if( empty( $this->ajaxPageNo ) ) {
      if( $this->listOptions['show-mosaic-sort-filter'] ){
        return $this->loadComponent( 'news_sort' );  
      }
    }
  }

  /**
   * getViewAll - Get View All link
   * @return mixed
   */
  private function getViewAll() {
    if ( 
        !empty($this->listOptions['blog_list_title']) ||
        ( !empty($this->listOptions['view_all_page']) || !empty($this->listOptions['view_all_page_link']) ) 
        && empty( $this->ajaxPageNo )
    ) {
      return $this->loadComponent('view_all');
    }
  }

  /**
   * getLink - get link for title or media items
   * @param $post - array - post info
   * @param $type - string - title or media item for getLink
   */
  private function getLink ( $post , $type = null ) {
    if( !empty($type) ) {
      switch ( $type ) {
        case 'media':
          switch ( $this->listOptions['image_link'] ) {
            case 'single':
              return get_permalink( $post->ID );
            break;
            case 'lightbox':
              return $this->getPostAsset( $post );
            break;
            default:
              return null;
            break;
          }
        break;
        case 'title':
          switch ( $this->listOptions['title_link'] ) {
            case 'single':
              return get_permalink( $post->ID );
            break;
            case 'lightbox':
              return $this->getPostAsset( $post );
            break;
            default:
              return null;
            break;
          }
        break;
      }
    }
  }

  /**
   * excerptLength - Get content based on word count.
   * @return string
   */
  private function excerptLength($content, $count) {
    $content = strip_tags($content);
    $content = str_replace('&nbsp;', ' ', $content);
    $content = explode(" ", $content);

    if( $count == 0 ) {
      $count = 1;
    }

    if ($count < count($content) ) {
      $content = array_slice($content, 0, $count);
      // array_push($content, "...");
    }

    $content = join(" ", $content);

    return $content;
  }

  /**
   * getMediaProportions - Get Media Proportions for the specific layout
   * @return null
   */
  public function getMediaProportions() {

    if( empty( $this->templateOptions ) ) {
      // Load $templateOptions - array
      include( dirname( __FILE__ ) . '/../include' . DS . 'content.php');
      $this->templateOptions = $templateOptions;
    }
    
    foreach ( $this->templateOptions as $key => $value):
      if( $value['name'] == $this->listOptions['template'] ) {
        $optionIndex = $key;
      }
    endforeach;
    
    $this->imageWidth   = $this->templateOptions[$optionIndex]['width'];
    $this->imageHeight  = $this->templateOptions[$optionIndex]['height'];
    $this->imageCrop    = $this->templateOptions[$optionIndex]['crop'];
	
	if( isset( $this->listOptions['thumb_width'] ) && preg_match( "/^\d+$/", $this->listOptions['thumb_width'] ) ){
		
		$this->imageWidth = $this->listOptions['thumb_width'];
	}
	
	if( isset( $this->listOptions['thumb_height'] ) && preg_match( "/^\d+$/", $this->listOptions['thumb_height'] ) ) {
		
		$this->imageHeight = $this->listOptions['thumb_height'];
	}
	
	if( isset( $this->listOptions['white_spaces'] ) ){
		
		switch( $this->listOptions['white_spaces'] ){
			case 'no':
					$this->imageWhiteSpaces = false;
				break;
			default:
					$this->imageWhiteSpaces = true;
				break;
		}
	}else{
		$this->imageWhiteSpaces = true;
	}
	
	if( isset( $this->listOptions['white_spaces_color'] ) && preg_match( "/^\#[a-zA-Z0-9]{6}$/", $this->listOptions['white_spaces_color'] ) ){
		
		$this->imageBackground = $this->listOptions['white_spaces_color'];
	}else{
		$this->imageBackground = '#ffffff';
	}
	
	$this->imageCrop = 'center_center';
	if( isset( $this->listOptions['thumb_crop'] ) ){
		
		if( $this->listOptions['thumb_crop'] == 'no' ){
			$this->imageCrop = false;
		}elseif( preg_match( "/^([a-z]+)_([a-z]+)$/", $this->listOptions['thumb_crop'], $crop_matches ) ){
			$this->imageCrop = $this->listOptions['thumb_crop'];
		}
	}
  }

  /**
   * Get Post Assets - First Look For OTW Meta Box Content (img), if no Meta Box content has been found,
   * use featured image
   * @param $post - array()
   * @return string
   */
  private function getPostAsset ( $post ) {
    $postMetaData = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
    if( !empty( $postMetaData ) && !empty( $postMetaData['img_url'] ) ) {
      return $postMetaData['img_url'];
    } elseif ( !empty( $postMetaData ) && !empty( $postMetaData['slider_url'] ) ){
      $sliderImages = explode(',', $postMetaData['slider_url']);
      return $sliderImages[0];
    }

    $postAsset = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );

    if( !empty( $postAsset ) ) {
      return $postAsset;
    }

    return null;
  } 

  /**
   * loadComponent - Loads components found in /plugin_name/skeleton/components/*.php
   * @param $componentName - string
   * @param $post - array() - Post Data
   * @return mixed
   */
  private function loadComponent( $componentName, $post = null, $postMetaData = null, $otw_bm_posts = null ) {
    ob_start();
    $paginationPage = $this->ajaxPageNo;
    include( OTW_BML_SERVER_PATH . DS . 'skeleton' . DS . 'components' . DS . $componentName . '.php' );  
    return ob_get_clean();
  }

  /**
   * loadWidgetComp - Loads widget components found in /plugin_name/skeleton/components/*.php
   * @param $componentName - string
   * @param $widgetPost - array() - Post Data
   * @return mixed
   */
  private function loadWidgetComp( $componentName, $widgetPost = null, $postMetaData = null ) {
    ob_start();
    include( OTW_BML_SERVER_PATH . DS . 'skeleton' . DS . 'components' . DS . 'widget_'.$componentName . '.php' );  
    return ob_get_clean();
  }

  /**
   * loadTemplate - Loads components found in /plugin_name/skeleton/*.php
   * @param $templateName - string
   * @param $otw_bm_posts - array() - Array of Posts to be used in the template
   * @return mixed
   */
  private function loadTemplate ( $templateName, $otw_bm_posts ) {
    ob_start();
    include( OTW_BML_SERVER_PATH . DS . 'skeleton' . DS . $templateName . '.php' );
    return ob_get_clean();
  } 

  private function loadWrapper( $wrapperName, $metaData ) {
    ob_start();
    include( OTW_BML_SERVER_PATH . DS . 'skeleton' . DS . 'wrappers' . DS . $wrapperName . '.php' );
    return ob_get_clean();
  }

} // End OTWDispatcher Class

} // End IF Class Exists