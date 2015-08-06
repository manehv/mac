<?php 
/**
 * Crop Images on the Fly
 * Create an unique Image Name for cropped images.
 * If cropped image is on the server return the existing one
 */
class OTWBMLImageCrop {

  /**
   * Image extensions accepted for resize
   */
  private $imgTypes = array("gif", "jpeg", "jpg", "png", "wbmp"); // used to determine image type

  /**
   * Image Paths used for resize
   * $imgBaseDir: /var/www/folder/wp-content/uploads
   * $imgBaseUrl: http://example.com/wp-content/uploads
   */
  private $imgBaseDir = ''; 
  private $imgBaseUrl = '';

  /**
   * Cache folder
   * This folder will be created within the $imgPath
   * In the default case this is going to result in: wp-content/uploads/cache/
   */
  private $cacheFolder = 'cache';

  /**
   * Image filename sent for resize;
   * $filename = abstract_image_name.jpg - actual image name
   * $ext = .jpg - actual image extension
   * $baseDir = /var/www/folder/wp-content/uploads/2014/03/  - actual image folder upload location
   */
  private $filename = '';
  private $ext = '';
  private $baseDir = '';
  private $currentImage = '';


  /**
   * Constructor
   * We will check if the cache folder is writtable.
   */
  public function OTWBMLImageCrop() {
    // Large Image may require more memory for crop
    ini_set('memory_limit', '128M');

    $imgPaths = wp_upload_dir();

    $this->imgBaseUrl = $imgPaths['baseurl'].'/';
    $this->imgBaseDir = $imgPaths['basedir'].'/';

    // Check if Uploads directory is writtable
    if( !is_writable( $this->imgBaseDir ) ) {
      // If Uploads directory is NOT writtable, throw exception
      throw new Exception('Folder:'. $this->imgBaseDir .' is not writtable. Make sure you have read/write permissions.');
      return;

    } elseif( !file_exists( $this->imgBaseDir.$this->cacheFolder ) ) {
      // If Uploads directory is writtable, create cache folder
      mkdir( $this->imgBaseDir.$this->cacheFolder );
    }

  }

  /**
   * Resize Images and save them into the cache folder
   * @param $imgData string - current image path
   * @param $resizeWidth int - new image width
   * @param $resizeHeight int - new image height
   * @param $crop boolean - crop image, or just resize
   * @return string - cropped image url
   */
  public function resize( $imgData = null, $resizeWidth, $resizeHeight, $crop = false, $white_spaces = true, $background = false ){
	
	global $otw_bm_image_object, $otw_bm_image_profile;
	
	return $otw_bm_image_object->resize( $otw_bm_image_profile, $imgData, $resizeWidth, $resizeHeight, $crop , false, $white_spaces, $background );
  } 
  
  public function embed_resize( $html, $resizeWidth, $resizeHeight, $crop = false ){
	
	global $otw_bm_image_object, $otw_bm_image_profile;
	
	return $otw_bm_image_object->embed_resize( $otw_bm_image_profile, $html, $resizeWidth, $resizeHeight, $crop );
  }
	
   
  public function resize_old ( $imgData = null, $resizeWidth, $resizeHeight, $crop = false) {
    
    if( empty( $imgData ) || empty( $resizeWidth ) || empty( $resizeHeight ) ) {
      return;
    }

    $imageInfo = pathinfo( $imgData );
    // $wpUploadPath = getcwd();
    $wpUploadPath = $_SERVER['DOCUMENT_ROOT'];

    $this->filename = $imageInfo['filename'];
    $this->ext = $imageInfo['extension'];
    $this->baseDir = $wpUploadPath.$imageInfo['dirname'].'/';
    
    $this->currentImage = $this->baseDir . $this->filename . '.' . $this->ext;
    
    // Check if file is image
    if( !in_array($this->ext, $this->imgTypes) ) {
      // throw new Exception('Accepted extensions are: .gif, .jpg, .jpeg, .bmp, .png, .wbmp');
      return;
    }

    // Cache Folder Name
    $cacheFolder = substr( md5($this->filename), 0, 4 );

    // Verify if cache subfolder exists, if not create it. Return the name for future use
    $fileTarget = $this->cacheDir( $cacheFolder );

    // New Filename - Resized and cached version
    $newFilename = $this->filename.'_'. $cacheFolder .'_'.$resizeWidth.'x'.$resizeHeight.'.'.$this->ext;

    if( file_exists( $fileTarget . '/' . $newFilename ) ) {
      return $this->imgBaseUrl . $this->cacheFolder . '/' . $cacheFolder . '/' . $newFilename;
    }

    $imgInfo = getimagesize( $this->baseDir . $this->filename .'.'. $this->ext );
    $originalWidth = $imgInfo[0];
    $originalHeight = $imgInfo[1];
    $originalMime = $imgInfo['mime'];

    if ( extension_loaded('imagick') ) {
      // If Image Magick is installed, resieze using ImageMagick
      $imgMagick = new imagick( $this->currentImage );

      if( $crop ){
        $imgMagick->cropThumbnailImage( $resizeWidth, $resizeHeight );
      } else {
        $imgMagick->resizeImage( $resizeWidth, $resizeHeight,  imagick::FILTER_LANCZOS, 0.9, true);  
      }

      $imgMagick->writeImage( $fileTarget . '/' . $newFilename );
      $imgMagick->removeImage();

      $savedImage = $this->imgBaseUrl . $this->cacheFolder .'/'. $cacheFolder .'/'. $newFilename;
      return $savedImage;

    } elseif( extension_loaded('gd') ) {
      // If GD is installed, resize using GD
      switch ( $originalMime ) {
        case 'image/jpeg':
          $tmpImg = imagecreatefromjpeg( $this->currentImage ); 
        break;
        case 'image/png':
          $tmpImg = imagecreatefrompng( $this->currentImage );
        break;
        case 'image/bmp':
        case 'image/x-windows-bmp':
          $tmpImg = imagecreatefromxbm( $this->currentImage );
        break;
        case 'image/gif':
          $tmpImg = imagecreatefromgif( $this->currentImage );
        break;
      }
      $savedImage = $this->imgBaseUrl . $this->cacheFolder .'/'. $cacheFolder .'/'. $newFilename;

      $source_aspect_ratio = $originalWidth / $originalHeight;
      $desired_aspect_ratio = $resizeWidth / $resizeHeight;

      if ($source_aspect_ratio > $desired_aspect_ratio) {
          /*
           * Triggered when source image is wider
           */
          $temp_height = $resizeHeight;
          $temp_width = ( int ) ($resizeHeight * $source_aspect_ratio);
      } else {
          /*
           * Triggered otherwise (i.e. source image is similar or taller)
           */
          $temp_width = $resizeWidth;
          $temp_height = ( int ) ($resizeWidth / $source_aspect_ratio);
      }

      
      $thumb = imagecreatetruecolor( $temp_width, $temp_height );
      imagecopyresampled($thumb, $tmpImg, 0, 0, 0, 0, $temp_width, $temp_height, $originalWidth, $originalHeight);

      if ( $crop ) {
        /*
         * Copy cropped region from temporary image into the desired GD image
         */
        $x0 = ($temp_width - $resizeWidth) / 2;
        $y0 = ($temp_height - $resizeHeight) / 2;
        $thumb_result = imagecreatetruecolor($resizeWidth, $resizeHeight);
        imagecopy(
            $thumb_result,
            $thumb,
            0, 0,
            $x0, $y0,
            $resizeWidth, $resizeHeight
        );

        imagejpeg($thumb_result, $fileTarget . '/' . $newFilename, 90);
        imagedestroy( $thumb_result );
      } else {
        imagejpeg($thumb, $fileTarget . '/' . $newFilename, 90);
        imagedestroy( $thumb );
      }
      
      return $savedImage;

    } else {
      //@todo: search for string comparison
      return 'http://'.$_SERVER['SERVER_NAME'].$imgData;

    }


  }

  /**
   * Create cache directory.
   * @param $directory string - Cache directory
   * @return cache folder path
   */
  public function cacheDir ( $directory ) {

    $baseDirectory = $this->imgBaseDir.$this->cacheFolder.'/'.$directory;

    if ( !is_dir($baseDirectory) ) {
      mkdir( $baseDirectory );
    }

    return $baseDirectory;

  }


}
?>