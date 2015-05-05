<?php
/**
 * Thankyou page
 *
 * @author              WooThemes
 * @package     WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

global $woocommerce;

if ( $order ) : ?>
<?php 
// Hard coded
$url = "/comprar/" ; 

echo '<a href="'. $url .'" class="pull-right checkout-button button alt wc-forward continue-button">';
 _e( 'Continue Shopping', 'woocommerce' );
echo '</a>';
?>
        <div class="clsTopImg">
                <span>Resumen de orden</span>
                <span>Comprar</span>
                <span>Recibo</span>
                <img src="<?php echo get_template_directory_uri(); ?>/images/cart-line3.svg" />
        </div>
        <?php if ( in_array( $order->status, array( 'failed' ) ) || in_array( $order->status, array( 'cancelled' ) ) || in_array( $order->status, array( 'canceled' ) ) ) : ?>

                <p class="fakealert"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

                <p class="fakealert"><?php
                        if ( is_user_logged_in() )
                                _e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
                        else
                                _e( 'Please attempt your purchase again.', 'woocommerce' );
                ?></p>

                <p class="fakealert">
                        <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
                        <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
                        <?php endif; ?>
                </p>

        <?php else : ?>

                <p class="fakealert"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

                <ul class="order_details">
                        <li class="order">
                                <?php _e( 'Order Number:', 'woocommerce' ); ?>
                                <strong><?php echo $order->get_order_number(); ?></strong>
                        </li>
                        <li class="date">
                                <?php _e( 'Date:', 'woocommerce' ); ?>
                                <strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
                        </li>
                        <li class="total">
                                <?php _e( 'Total:', 'woocommerce' ); ?>
                                <strong><?php echo $order->get_formatted_order_total(); ?></strong>
                        </li>
                        <?php if ( $order->payment_method_title ) : ?>
                        <li class="method">
                                <?php _e( 'Payment Method:', 'woocommerce' ); ?>
                                <strong><?php echo $order->payment_method_title; ?></strong>
                        </li>
                        <?php endif; ?>
                </ul>
                <div class="clear"></div>

        <?php endif; ?>

        <?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
        <?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

        <p class="fakealert"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>
