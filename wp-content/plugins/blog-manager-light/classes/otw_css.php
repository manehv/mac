<?php
if( !class_exists('OTWCss') ) {

class OTWCss {

  public $contentCss = '';

  public $googleFontsString = null;

  public function __construct() {}

  /**
   * Build Custom Css and write it to a file
   * @param $style - array
   * @param $filePath - string
   * @return void
   */
  public function buildCSS( $style = array(), $filePath = null) {
    $this->contentCss = '';
    if( !empty( $style['font'] ) ) {
      $this->contentCss .= 'font-family: "'. $style['font'] .'" !important;';
    }

    if( !empty( $style['color'] ) ) {
      $this->contentCss .= 'color:'. $style['color'] .' !important;';
    }

    if( !empty( $style['size'] ) ) {
      $this->contentCss .= 'font-size:'. $style['size'] .'px !important;';
    }

    if( !empty( $style['font-style'] ) ) {
      if( $style['font-style'] == 'bold' ) {
        $this->contentCss .= 'font-weight: bold !important; ';
        $this->contentCss .= 'font-style: normal !important; ';
      } elseif( $style['font-style'] == 'italic' ) {
        $this->contentCss .= 'font-style: italic !important; ';
      } elseif( $style['font-style'] == 'bold_italic' ) {
        $this->contentCss .= 'font-weight: bold !important; ';
        $this->contentCss .= 'font-style: italic !important; ';
      } elseif( $style['font-style'] == 'regular' ) {
        $this->contentCss .= 'font-style: normal !important; ';
      }
    }

    if( !empty( $style['container'] ) && !empty( $filePath ) ) {
      // Get Current Css From File
      $customContentCss = '';
      if( file_exists( $filePath ) ) {
        $customContentCss = file_get_contents( $filePath );
      }

      $customBuildCss = ' '.$style['container'] . '{' . $this->contentCss . '}' . $customContentCss;

      str_replace('\\', '', $customBuildCss);

      file_put_contents( $filePath , $customBuildCss);

      $this->contentCss = '';
    }

  }

  /**
   * Write raw css (from an textarea) to a file
   * @param $rawCSS - string
   * @param $filePath - string
   * @return void
   */
  public function writeCSS ( $rawCSS = null, $filePath = null ) {
    
    if( !empty( $rawCSS ) && !empty( $filePath ) ) {
      // Get Current Css From File
      $customContentCss = '';
      if( file_exists( $filePath ) ) {
        $customContentCss = file_get_contents( $filePath );
      }

      $rawBuildCSS = $customContentCss . $rawCSS;

      // $rawBuildCSS = str_replace('\\', '', $rawBuildCSS);
      
      // print_r( $rawBuildCSS );
      // die;
      file_put_contents( $filePath , $rawBuildCSS);
    }

  }

  public function getGoogleFonts ( $fonts = null, $fontList = array() ) {
    if( empty( $fonts ) || empty($fontList) || !is_array($fonts)) {
      return null;
    }

    foreach( $fonts as $font ):
      // Font ID is from the Google Fonts
      if( $font > 9 ) {
        $this->googleFontsString .= urlencode($fontList[ $font ]->text).'|';
      }
    endforeach;

    return $this->googleFontsString;
  }


} // End OTWCss Class

} // End IF class exists