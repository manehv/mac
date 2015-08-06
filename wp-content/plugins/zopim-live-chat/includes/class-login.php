<?php

class Zopim_Login extends Zopim_Base_View
{
  protected function set_messages()
  {
    $this->_messages = array(
      'login-fail'            => __( 'Could not log in to Zopim. Please check your login details.', 'zopim' ),
      'wp-login-error'        => __( 'Could not log in to Zopim. We were unable to contact Zopim servers. Please check with your server administrator to ensure that <a href="http://www.php.net/manual/en/book.curl.php">PHP Curl</a> is installed and permissions are set correctly', 'zopim' ),
      'setup-your-account'    => __( 'Set up your Zopim Account', 'zopim' ),
      'congratulations'       => __( 'Congratulations on successfully installing the Zopim WordPress plugin!', 'zopim' ),
      'link-up-title'         => __( 'Link up with your Zopim account', 'zopim' ),
      'username'              => __( 'Zopim Username (E-mail)', 'zopim' ),
      'password'              => __( 'Zopim Password', 'zopim' ),
      'widget-display-notice' => __( 'The Zopim chat widget will display on your blog after your account is linked up.', 'zopim' ),
      'link-up-button'        => __( 'Link Up', 'zopim' ),
      'sign-up-link'          => __( 'Sign up now', 'zopim' ),
    );
  }

  public function do_login()
  {
    $admin = Zopim_Admin::get_instance();
    $notices = Zopim_Notices::get_instance();

    if ( $_POST[ Zopim_Options::ZOPIM_OPTION_USERNAME ] != '' && $_POST[ 'zopimPassword' ] != '' ) {

      $logindata = array( 'email' => $_POST[ Zopim_Options::ZOPIM_OPTION_USERNAME ], 'password' => $_POST[ 'zopimPassword' ] );
      $loginresult = json_decode( $admin->zopim_post_request( ZOPIM_LOGIN_URL, $logindata ) );

      if ( isset( $loginresult->error ) ) {
        $notices->add_notice( 'login_form', $this->get_message( 'login-fail' ), 'error' );

        update_option( Zopim_Options::ZOPIM_OPTION_SALT, 'wronglogin' );
      } else if ( isset( $loginresult->salt ) ) {
        update_option( Zopim_Options::ZOPIM_OPTION_USERNAME, $_POST[ Zopim_Options::ZOPIM_OPTION_USERNAME ] );
        update_option( Zopim_Options::ZOPIM_OPTION_SALT, $loginresult->salt );
        $account = $admin->zopim_get_account_details( get_option( Zopim_Options::ZOPIM_OPTION_SALT ) );

        if ( isset( $account ) ) {
          update_option( Zopim_Options::ZOPIM_OPTION_CODE, $account->account_key );

          if ( get_option( 'zopimGreetings' ) == '' ) {
            $jsongreetings = json_encode( $account->settings->greetings );

            update_option( 'zopimGreetings', $jsongreetings );
          }
        }
      } else {
        update_option( Zopim_Options::ZOPIM_OPTION_SALT, '' );
        $notices->add_notice( 'login_form', $this->get_message( 'wp-login-error' ), 'error' );
      }
    } else {
      update_option( Zopim_Options::ZOPIM_OPTION_SALT, "wronglogin" );
      $notices->add_notice( 'login_form', $this->get_message( 'login-fail' ), 'error' );
    }
  }

  /**
   *
   */
  public function display_login_form()
  {
    $notices = Zopim_Notices::get_instance();
    Zopim_Template::load_template( 'login-form', array( 'notices' => $notices, 'messages' => $this->_messages ) );
  }
}
