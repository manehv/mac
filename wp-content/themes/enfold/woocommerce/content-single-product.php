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
	 * @hooked wc_print_notices - 10
	 */
	
	 do_action( 'woocommerce_before_single_product' );
	 
	 
	 /**
		 * woocommerce_before_single_product_summary hook
		 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
		 */
//		do_action( 'woocommerce_before_single_product_summary' );



	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div class="row clsSinProd">
	<ul class="clsTopList">
		<li>
			<?php 
				if ( has_post_thumbnail())
					echo get_the_post_thumbnail( $post_id, 'thumbnail');
			?>
			<span class="clsRelTitle">
				<?php
					the_title(); 
				?>
			</span>
		</li>
		<?php woocommerce_upsell_display(4,4); ?>
	</ul>
</div>


<div class="row">
		<?php if( $product->has_child() ): ?>
    <div class="col-lg-9 clsContent">
			<h1 class="clsBotTitle">
					<?php _e('Choose a ','woocommerce').the_title(); ?>
			</h1>
			<?php do_action('woocommerce_variable_add_to_cart' ); ?>
			<div id="desc"></div>
			<?php the_content(); ?> 
    </div>
    <div class="col-lg-3">
			<div id="idSticky" class="clsSticky">
				<div class="clsSidebar">
				<h3><?php _e('Abstract','woocommerce'); ?></h3>
				<?php 
				global $product, $post;
				
				$variations = $product->get_available_variations();
				
				foreach ($variations as $key => $value)
				{ 
					?>
				<div class="row clshide <?php echo $value['variation_id']?>">  
					<div class="clsDetails clsDetailsImg">
						<img class="v-image" src="<?php echo $value['image_src']?>"/>
					</div>
					<div class="clsDetails">
							<?php 
								the_title();
								_e(' of ','woocommerce');
								echo $value['attributes']['attribute_model'];
								echo '<div>'.$value['attributes']['attribute_color'].'</div>';
								echo '<div>';
								_e('Unlocked','woocommerce');
								echo '</div>';
							?>
					</div>
					<div class="clsDetails">
							<?php echo $value['sku']; ?>
					</div>
					<div class="clsDetails">
							<?php echo $value['price_html']; ?>
					</div>
					<div class="clsDetails">
						<?php  echo get_post_meta($value['variation_id'], '_textarea', true );?>
					</div>
					<div class="clsDetails">
						<form  method="post"  enctype='multipart/form-data'>
							<input type="hidden" id="addtocart" value="<?php echo $value['variation_id'] ?>" name="add-to-cart"/>
							<input id="set-aqy" class="qty" type="hidden" name="quantity" value="1" />
							<div><?php woocommerce_quantity_input(); ?></div>
							<button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?></button>
						
						</form>
						
					</div>
				</div>    			
				<?php
				}
				?>
				</div> <!-- clsSidebar -->
				<div class="clsSidebar">
					<p class="clsBotDetails clsBotTitle"><?php _e('More information on how to buy your ','woocommerce').the_title(); ?></p>
					<p class="clsBotDetails" id="scroll-top"><a href="#"><?php _e('General description','woocommerce'); ?></a></p>
					<p class="clsBotDetails" id="des-top"><a href="#" ><?php _e('Technical specifications','woocommerce'); ?></a></p>
					<p class="clsBotDetails">
				
					<a href="#" Id="showImage"><?php _e('View Gallery','woocommerce'); ?></a>
									<?php woocommerce_show_product_images(); ?>
					</p>
					<?php echo do_shortcode( "[av_sidebar widget_area='Single Product Contact']" ) ?>
				</div> <!-- clsSidebar -->
			</div> <!-- clsSticky -->
			<?php else: ?>
			
			<div class="col-lg-9 clsContent">
			<h1 class="clsBotTitle">
					<?php _e('Choose a ','woocommerce').the_title(); ?>
			</h1>
			<div class="row">
				<div class="col-lg-6">
					<?php echo get_the_post_thumbnail( $post_id, 'medium', $attr ); ?>
				</div>
			</div> <!-- row -->
			<div id="desc"></div>
			<?php the_content(); ?> 
		</div>
			<div class="col-lg-3">
				<div id="idSticky" class="clsSticky">
					<div class="clsSidebar">
					<h3><?php _e('Abstract','woocommerce'); ?></h3>
					<div class="row">  
						<div class="clsDetails clsDetailsImg">
							<?php echo get_the_post_thumbnail( $post_id, 'medium', $attr ); ?>
						</div>
						<div class="clsDetails">
								<?php 
									the_title();
								?>
						</div>
						<div class="clsDetails">
								<?php echo $product->get_sku(); ?>
						</div>
						<div class="clsDetails">
								<?php echo $product->get_price_html(); ?>
						</div>
						<div class="clsDetails">
							<form  method="post"  enctype='multipart/form-data'>
								<input type="hidden" id="addtocart" value="<?php echo $post->ID;  ?>" name="add-to-cart"/>
								<input id="set-aqy" class="qty" type="hidden" name="quantity" value="1" />
								<div><?php woocommerce_quantity_input(); ?>	</div>
								<button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?></button>
							</form>
							
						</div>
					</div>
					</div> <!-- clsSidebar -->
					<div class="clsSidebar">
						<p class="clsBotDetails clsBotTitle"><?php _e('More information on how to buy your ','woocommerce').the_title(); ?></p>
						<p class="clsBotDetails" id="scroll-top"><a href="#"><?php _e('General description','woocommerce'); ?></a></p>
						<p class="clsBotDetails" id="des-top"><a href="#" ><?php _e('Technical specifications','woocommerce'); ?></a></p>
						<p class="clsBotDetails">
					
						<a href="#" Id="showImage"><?php _e('View Gallery','woocommerce'); ?></a>
										<?php woocommerce_show_product_images(); ?>
						</p>
						<?php echo do_shortcode( "[av_sidebar widget_area='Single Product Contact']" ) ?>
					</div> <!-- clsSidebar -->
				</div> <!-- clsSticky -->
			<?php endif; ?>
		</div> <!-- col-lg-3 -->
  
</div> <!--row -->

</div> <!-- main -->
