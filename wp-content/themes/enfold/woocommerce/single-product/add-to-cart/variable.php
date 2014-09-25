<?php
/**
 * Variable product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product, $post;
?>

<script type="text/javascript">
    var product_variations_<?php echo $post->ID; ?> = <?php echo json_encode( $available_variations ) ?>;
</script>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<?php if ( ! empty( $available_variations ) ) : ?>
	   
		<table class="variations" cellspacing="0" border=1 cellspacing=0>
			<tbody>
				<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
					<tr>
						<td class="label"><label for="<?php echo sanitize_title($name); ?>"><?php echo wc_attribute_label( $name ); ?></label></td>
						<td class="value"><fieldset>
                        <strong>Choose An Option...</strong><br />
                        <?php
                            if ( is_array( $options ) ) {
 
                                if ( empty( $_POST ) )
                                    $selected_value = ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) ? $selected_attributes[ sanitize_title( $name ) ] : '';
                                else
                                    $selected_value = isset( $_POST[ 'attribute_' . sanitize_title( $name ) ] ) ? $_POST[ 'attribute_' . sanitize_title( $name ) ] : '';
								//echo   $selected_value;
                                // Get terms if this is a taxonomy - ordered
                                if ( taxonomy_exists( sanitize_title( $name ) ) ) {
 
                                    $terms = get_terms( sanitize_title($name), array('menu_order' => 'ASC') );
									
                                    foreach ( $terms as $term ) {
                                        if ( ! in_array( $term->slug, $options ) ) continue;
                                        echo '<input type="radio" value="' . strtolower($term->slug) . '" ' . checked( strtolower ($selected_value), strtolower ($term->slug), false ) . ' id="'. esc_attr( sanitize_title($name) ) .'" name="attribute_'. sanitize_title($name).'">' . apply_filters( 'woocommerce_variation_option_name', $term->name ).'<br />';
                                    }
                                } else {
                                    foreach ( $options as $option )
                                        echo '<input type="radio" value="' .esc_attr( sanitize_title( $option ) ) . '" ' . checked( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . ' id="'. esc_attr( sanitize_title($name) ) .'" name="attribute_'. sanitize_title($name).'">' . apply_filters( 'woocommerce_variation_option_name', $option ) . '<br />';
                                }
                            }
                        ?>
                    </fieldset> <?php
							if ( sizeof($attributes) == $loop )
								//echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'woocommerce' ) . '</a>';
						?></td>
						
					</tr>
		        <?php endforeach;?>
			</tbody>
		</table>
		<div class='col-md-3'>
			<div id="idSticky" class="clsSticky">
				<div class="clsSidebar">
				<h3><?php _e('Abstract','woocommerce'); ?></h3>
				 <div class="row clshide">  
					<div class="clsDetails clsDetailsImg">
						<img class="variation_image v-image" src=""/>
					</div>
					<div class="clsDetails">
						<div id="prodtitle" title="<?php the_title(); ?>"><?php the_title(); ?></div>	
					</div>
					<div class="clsDetails" id="sku"></div>
					<div class="clsDetails" id="shipping"></div>
					<div class="clsDetails" id="price"></div>	
				<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

				<div class="single_variation_wrap" style="display:none;">
					<?php do_action( 'woocommerce_before_single_variation' ); ?>

					<div class="single_variation"></div>

					<div class="variations_button">
						<?php woocommerce_quantity_input(); ?>
						<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>
					</div>

					<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
					<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
					<input type="hidden" name="variation_id" value="" />

					<?php do_action( 'woocommerce_after_single_variation' ); ?>
				</div>

				<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
				</div> <!-- clsSideBar-->
				<div class="clsSidebar">
					<p class="clsBotDetails clsBotTitle"><?php _e('More information on how to buy your ','woocommerce').the_title(); ?></p>
					<p class="clsBotDetails" id="scroll-top"><a href="#"><?php _e('General description','woocommerce'); ?></a></p>
					<p class="clsBotDetails" id="des-top"><a href="#" ><?php _e('Technical specifications','woocommerce'); ?></a></p>
					<p class="clsBotDetails">
				
					<a href="#" Id="showImage"><?php _e('View Gallery','woocommerce'); ?></a>
								
					</p>
					<?php echo do_shortcode( "[av_sidebar widget_area='Single Product Contact']" ) ?>
				</div> <!-- clsSidebar -->				
			</div> <!-- clsSticky -->
		</div> <!-- col-md-3'>
	<?php else : ?>

		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

