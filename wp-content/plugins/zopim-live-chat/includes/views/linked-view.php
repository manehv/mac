<div id="icon-options-general" class="icon32"><br/></div>
<h2><?php echo $messages[ 'page-header' ] ?></h2>
<br/>
<div class="zopim-linked-view-wrapper">
    <span>
      <a
        href="admin.php?page=zopim_account_config&action=deactivate"><?php echo $messages[ 'deactivate' ] ?></a>
    </span>
  <?php echo $messages[ 'current-account-label' ] ?> &rarr;
  <b><?php echo get_option( Zopim_Options::ZOPIM_OPTION_USERNAME ); ?></b>

  <div class="zopim-package-title"><?php echo ucwords( $package_id ); ?></div>

  <br><br><?php echo $messages[ 'dashboard-access-label' ]; ?>
  <br><br>
  <a class="no-underline" href="<?php echo ZOPIM_DASHBOARD_LINK . "&username=" . get_option( Zopim_Options::ZOPIM_OPTION_USERNAME ); ?>"
     target="_blank"
     data-popup="true">
    <div class="zopim_btn_orange"><?php echo $messages[ 'launch-dashboard' ]; ?></div>
  </a>
  &nbsp;&nbsp;(<?php echo $messages[ 'open-tab-label' ]; ?>)


  <form method="post" action="admin.php?page=zopim_account_config">
    <?php
    $notices = Zopim_Notices::get_instance();
    $notices->do_notices( 'before_udpate_widget_textarea' );
    ?>
    <p>
      <?php echo $messages[ 'textarea-label' ]; ?>
      <br/>
      <textarea name="widget-options"><?php echo esc_textarea( Zopim_Options::get_widget_options() ); ?></textarea>
      <br/>
      <input class="button-primary" type="submit" value="Update widget options"/>
    </p>
  </form>

</div>

