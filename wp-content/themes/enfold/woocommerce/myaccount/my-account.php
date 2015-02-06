<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

wc_print_notices(); ?>
<h2 class='clsMyAcc'>Mi Cuenta</h2>
<p class="myaccount_user">
	Desde la página de cuenta puedes ver pedidos recientes, gestionar tu dirección de envío, dirección de facturación y cambiar tu contraseña.
</p>

<?php do_action( 'woocommerce_before_my_account' ); ?>

<?php wc_get_template( 'myaccount/my-downloads.php' ); ?>

<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

<div class="avia-button" style="color:#ffffff;background-color:#3D89CC;margin-bottom:20px"><a href="/" style="color:#ffffff"><?php echo __("Ir a la tienda") ?></a></div>


<?php wc_get_template( 'myaccount/my-address.php' ); ?>

<?php do_action( 'woocommerce_after_my_account' ); ?>
