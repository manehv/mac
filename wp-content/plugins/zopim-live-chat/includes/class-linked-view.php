<?php

class Zopim_Linked_View extends Zopim_Base_View
{
  protected function set_messages()
  {
    $this->_messages = array(
      'options-updated'        => __( 'Widget options updated.', 'zopim' ),
      'trial'                  => __( 'Trial Plan with 14 Days Full-features', 'zopim' ),
      'plan'                   => __( ' Plan', 'zopim' ),
      'deactivate'             => __( 'Deactivate', 'zopim' ),
      'current-account-label'  => __( 'Currently Activated Account', 'zopim' ),
      'dashboard-access-label' => __( 'To start using Zopim chat, launch our dashboard for access to all features, including widget customization!', 'zopim' ),
      'launch-dashboard'       => __( 'Launch Dashboard', 'zopim' ),
      'open-tab-label'         => __( 'This will open up a new browser tab', 'zopim' ),
      'textarea-label'         => __( 'Optional code for customization with Zopim API:', 'zopim' ),
      'page-header'            => __( 'Set up your Zopim Account', 'zopim' ),
    );
  }

  /**
   * Handles POST request when saving the Widget Options form.
   */
  public function update_widget_options()
  {
    $notices = Zopim_Notices::get_instance();
    $opts = $_POST[ 'widget-options' ];
    update_option( Zopim_Options::ZOPIM_OPTION_WIDGET, $opts );
    $notices->add_notice( 'before_udpate_widget_textarea', '<i>' . $this->get_message( 'options-updated' ) . '<br/></i>', 'notice' );
  }

  /**
   * Renders the Zopim update options form.
   *
   * @param object Account details retrieved from the Zopim API
   */
  public function display_linked_view( $accountDetails )
  {
    if ( $accountDetails->package_id == 'trial' ) {
      $accountDetails->package_id = $this->get_message( 'trial' );
    } else {
      $accountDetails->package_id .= $this->get_message( 'plan' );
    }

    Zopim_Template::load_template( 'linked-view', array_merge( array( 'messages' => $this->_messages ), (array)$accountDetails ) );
  }
}
