<?php
/**
 * Cart Page
 *
 * @author              WooThemes
 * @package     WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
global $woocommerce;
?>
<div class="col-lg-offset-3 col-lg-6 offset-right col-xs-12 col-sm-6 clearfix" >
<?php wc_print_notices(); ?>
</div>
<?php
do_action( 'woocommerce_before_cart' ); ?>

<div class="clsTopImg clearfix">
        <span>Resumen de orden</span>
        <span>Comprar</span>
        <span>Recibo</span>
        <img src="<?php echo get_template_directory_uri(); ?>/images/cart-line1.svg" />
</div>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>
    
    <div class="modificacion1">
        <?php 
        // Hard coded
        $url = "/comprar/" ; 
        echo '<a href="'. $url .'" class="pull-right checkout-button button alt wc-forward continue-button">';
        _e( 'Continue Shopping', 'woocommerce' );
        echo '</a>';
        ?>
        <tr class="clsActions">
            <td colspan="6" class="actions">
                <input type="submit" class="checkout-button button alt wc-forward" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" /><input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />
                ?php do_action( 'woocommerce_cart_actions' ); ?>
                <?php wp_nonce_field( 'woocommerce-cart' ); ?>
            </td>
        </tr>
    </div>

<table class="custom_table shop_table cart" cellspacing="0">
        <thead class="clsMainTable">
                <tr>
                        <th class="product-remove">&nbsp;</th>
                        <th class="product-thumbnail">&nbsp;</th>
                        <th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
                        <th class="product-price"><?php _e( 'Price', 'woocommerce' ); ?></th>
                        <th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
                        <th class="product-subtotal"><?php _e( 'Total', 'woocommerce' ); ?></th>
                </tr>
        </thead>
        <tbody>
                <tr class="clsBlank">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                </tr>
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                ?>
                                <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                                        <td class="product-remove">
                                                <?php
                                                        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
                                                ?>
                                        </td>

                                        <td class="product-thumbnail">
                                                <?php
                                                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                                        if ( ! $_product->is_visible() )
                                                                echo $thumbnail;
                                                        else
                                                                printf( '<a href="%s">%s</a>', $_product->get_permalink( $cart_item ), $thumbnail );
                                                ?>
                                        </td>

                                        <td class="product-name">
                                                <?php
                                                        if ( ! $_product->is_visible() )
                                                                echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
                                                        else
                                                                echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink( $cart_item ), $_product->get_title() ), $cart_item, $cart_item_key );

                                                        // Meta data
                                                        echo WC()->cart->get_item_data( $cart_item );

                                        // Backorder notification
                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
                                                echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
                                                ?>
                                        </td>

                                        <td class="product-price">
                                                <?php
                                                        echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                                ?>
                                        </td>

                                        <td class="product-quantity">
                                                <?php
                                                        if ( $_product->is_sold_individually() ) {
                                                                $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                        } else {
                                                                $product_quantity = woocommerce_quantity_input( array(
                                                                        'input_name'  => "cart[{$cart_item_key}][qty]",
                                                                        'input_value' => $cart_item['quantity'],
                                                                        'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                                                        'min_value'   => '0'
                                                                ), $_product, false );
                                                        }

                                                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
                                                ?>
                                        </td>

                                        <td class="product-subtotal">
                                                <?php
                                                        echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                                ?>
                                        </td>
                                </tr>
                                <?php
                        }
                }

                do_action( 'woocommerce_cart_contents' );
                ?>
                <tr class="clsCartTotals">
                <td class="clsCartTotTD" colspan="4">
										<?php if ( WC()->cart->coupons_enabled() ) { ?>
														<div class="coupon">
																		<br/>		
<!-- 																		<label for="coupon_code" ><?php _e( 'Cupon de descuento', 'woocommerce' ); ?>:</label>  -->
																		
																		<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" /> 
																		<input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />			
														</div>
														<br/>
														<?php add_action( 'woocommerce_cart_coupon', 'add_login_notice' );
																		function add_login_notice() {
																		if (! is_user_logged_in() ) {
																			echo '<div id="registro" class="c-login" style="display:none;">Debes estar registrado para acceder al cupón <a href='. site_url('/login/').'>ingresar</a></div>' ; }
																		}
																		?>
														<?php do_action( 'woocommerce_cart_coupon' ); ?>
										<?php } ?>

                </td>
                <td class="clsCartTotTD" colspan="2">
                        <div class="cart-collaterals">

                                <?php do_action( 'woocommerce_cart_collaterals' ); ?>

                                <?php //woocommerce_shipping_calculator(); ?>

                        </div>
                </td>
                </tr>

                <tr class="clsActions">
                        <td colspan="6" class="actions">



                                <input type="submit" class="checkout-button button alt wc-forward" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />

                                <?php do_action( 'woocommerce_cart_actions' ); ?>

                                <?php wp_nonce_field( 'woocommerce-cart' ); ?>
                        </td>
                </tr>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
        </tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<!--<div class="cart-collaterals">

        <?php //do_action( 'woocommerce_cart_collaterals' ); ?>

        <?php //woocommerce_cart_totals(); ?>

</div>-->

<?php do_action( 'woocommerce_after_cart' ); ?>
