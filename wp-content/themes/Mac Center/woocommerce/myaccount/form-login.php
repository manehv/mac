<?php
/**
 * Login Form
 *
 * @author              WooThemes
 * @package     WooCommerce/Templates
 * @version     2.1.0    
 */
// Explicitly moved to login page
wp_redirect( '/login/');
exit;

get_header();
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="col-lg-12 ">
<?php wc_print_notices(); ?>
<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class=" " id="customer_login">
        <div class="">
                <h2><?php// _e( 'Login', 'woocommerce' ); ?></h2>

                <form method="post" class="login col-lg-12 ">

                        <?php do_action( 'woocommerce_login_form_start' ); ?>
<h2><?php _e( 'Login', 'woocommerce' ); ?></h2>
                        <p class="form-row col-lg-6 col-sm-12 col-xs-12 form-row-wide" >
                                <label class="col-lg-12 col-sm-12 col-xs-12 " for="username"><?php _e( 'Usuario', 'woocommerce' ); ?> <span class="required">*</span></label>
                                <input type="text" class="input-text col-lg-8"  name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                        </p>
                        <p class="form-row col-lg-6 col-sm-12 col-xs-12 form-row-wide">
                                <label class="col-lg-12 col-sm-12 col-xs-12 " for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                                <input class="input-text " style="margin-left:5px;" type="password" name="password" id="password" />
                        </p>
                        <?php do_action( 'woocommerce_login_form' ); ?>
                        <p class="form-row">
                                <?php wp_nonce_field( 'woocommerce-login' ); ?>
                                <input type="submit" class="button pull-right" name="login" value="Ingresar" />

                        </p>
                        <label for="rememberme" class="inline">
                                        <input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'woocommerce' ); ?>
                                </label>
                        <p class="lost_password" style="margin:0;">
                                <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>
                        </p>
                        <?php do_action( 'woocommerce_login_form_end' ); ?>
                </form>
        </div>
</div>
</div>
<?php get_footer();?>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>