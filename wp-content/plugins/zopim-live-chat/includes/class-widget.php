<?php

class Zopim_Widget
{
  // We need some CSS to position the paragraph
  public static function zopimme()
  {
    global $current_user;
    get_currentuserinfo();

    $code = get_option( Zopim_Options::ZOPIM_OPTION_CODE );

    if ( ( $code == "" || $code == "zopim" ) && !( isset( $_GET[ 'page' ] ) && preg_match( "/zopim/", $_GET[ 'page' ] ) ) && ( !preg_match( "/zopim/", $_SERVER[ "SERVER_NAME" ] ) ) ) {
      return;
    }

    echo "<!--Embed from Zopim Live Chat Wordpress Plugin v" . VERSION_NUMBER . "-->
  <!--Start of Zopim Live Chat Script-->
  <script type=\"text/javascript\">
  window.\$zopim||(function(d,s){var z=\$zopim=function(c){z._.push(c)},$=z.s=
  d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
  _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
  $.src='//v2.zopim.com/?" . $code . "';z.t=+new Date;$.
  type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
  </script>";

    echo '<script>';
    if ( isset( $current_user ) ):
      $firstname = $current_user->display_name;
      $useremail = $current_user->user_email;
      if ( $firstname != "" && $useremail != "" ):
        echo "\$zopim(function(){\$zopim.livechat.set({name: '$firstname', email: '$useremail'}); });";
      endif;
    endif;

    echo Zopim_Options::get_widget_options();
    echo '</script>';
    echo "<!--End of Zopim Live Chat Script-->";
  }
}
