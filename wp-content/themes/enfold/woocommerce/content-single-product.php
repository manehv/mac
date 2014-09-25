<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


global $post, $woocommerce, $product;

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
?>
<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?> class="single-product">
	<?php 
		$mymeta = get_post_meta( get_the_ID(), 'carousel' );
		echo do_shortcode($mymeta[0]);
	?>
	
	<div class="row clsContent">
		<div class="col-md-9">
			<h1 class="clsBotTitle">
				<?php _e('Choose a ','woocommerce').the_title(); ?>
			</h1>
			<div class='row'>
					<div class="entry-summary col-md-6">
						<?php
							/**
							* woocommerce_single_product_summary hook
							*
							* @hooked woocommerce_template_single_title - 5
							* @hooked woocommerce_template_single_price - 10
							* @hooked woocommerce_template_single_excerpt - 20
							* @hooked woocommerce_template_single_add_to_cart - 30
							* @hooked woocommerce_template_single_meta - 40
							* @hooked woocommerce_template_single_sharing - 50		 
							*/
							remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
							remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
							remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
							do_action('woocommerce_single_product_summary');
						?>
					</div><!-- .summary -->	
					<div class='col-md-4'>
					<?php
						/**
						* woocommerce_show_product_images hook
						*
						* @hooked woocommerce_show_product_sale_flash - 10
						* @hooked woocommerce_show_product_images - 20
						*/
						do_action( 'woocommerce_before_single_product_summary' );
					?>
					</div> <!-- col-md-4' -->
			</div> <!-- row -->
			<?php
				/**
				* woocommerce_after_single_product_summary hook
				*
				* @hooked woocommerce_output_product_data_tabs - 10
				* @hooked woocommerce_output_related_products - 20
				*/
				//do_action( 'woocommerce_after_single_product_summary' );
			?>
			</div>
			<?php if( $post->post_excerpt ): ?>
				<div class="clsExc">
					<?php the_excerpt(); ?>
				</div>
			<?php endif; ?>
			<?php the_content(); ?>
		</div><!-- end of col-md-9 -->
		<div class="col-md-3">
			
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
					  <div>
					   <div class="clsDetails" id="sku"></div>
					  </div> 
					  <div>
					   <div class="clsDetails" id="shipping"></div>
					  </div> 
						<div>
							<div class="clsDetails" id="price"></div>	
						</div>
						<div class="clsDetails hideform">
							<form  method="post"  enctype='multipart/form-data'>
								<input type="hidden" id="addtocart" value="" name="add-to-cart"/>
								<input id="set-aqy" class="qty" type="hidden" name="quantity" value="1" />
								<div>
									<?php woocommerce_quantity_input(); ?>
									<button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?></button>
								</div>
							</form>
						</div>
					</div>
				</div>
				
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
			
		</div> <!-- end of col-md-3 -->
	</div> <!-- end of row -->
</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
