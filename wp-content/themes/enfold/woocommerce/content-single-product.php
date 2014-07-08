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
	 

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>
<h1 class="clsTopTitle" id="TopTitle">
  Comprar <?php the_title(); ?>
</h1>
<div class="row clsSinProd">
    <div class="col-lg-3 clsTitleCon">
	<div class="row">
	    <div class="col-lg-6">
		<?php 
		      if ( has_post_thumbnail())
			echo get_the_post_thumbnail( $post_id, 'thumbnail');
		?>
	    </div>
	    <div class="col-lg-6 clsTitle">
		<?php the_title(); ?>
	    </div>
	</div>
    </div>
    <div class="col-lg-9">
	<?php woocommerce_upsell_display(4,4); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-9 clsContent">
	<h2 class="clsBotTitle">
	  Selecciona un <?php the_title(); ?>
	</h2>
	<?php do_action('woocommerce_variable_add_to_cart' ); ?>
	<?php the_content(); ?> 
    </div>
    <div class="col-lg-3">
			<div class="clsSidebar">
			<h3>Resumen</h3>
			<?php 
			global $product, $post;
			$variations = $product->get_available_variations();
			foreach ($variations as $key => $value) 
			{ ?>
			<div class="row clshide <?php echo $value['variation_id']?>">  
				<div class="clsDetails">
					<img class="v-image" src="<?php echo $value['image_src']?>"/> 
				</div>
				<div class="clsDetails">
						<?php 
							the_title();
							echo (' de ');
							echo $value['attributes']['attribute_model'];
							echo '<div>'.$value['attributes']['attribute_color'].'</div>';
						?>
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
						<button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?></button>
					</form>
				</div>
			</div>    			
			<?php
			}
			?>
			</div> <!-- clsSidebar -->
			
			<div class="clsSidebar">
				<p class="clsBotDetails clsBotTitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit <?php the_title(); ?></p>
				<p class="clsBotDetails" id="scroll-top"><a href="#">Description general</a></p>
				<p class="clsBotDetails" id="des-top"><a href="#">Specifications Information</a></p>
				<p class="clsBotDetails"><a href="#">Gallery Images</a>
								<?php woocommerce_show_product_images(); ?>
				</p>
				<?php echo do_shortcode( "[av_sidebar widget_area='Single Product Contact']" ) ?>
			</div> <!-- clsSidebar -->
			
		</div> <!-- col-lg-3 -->
  
    </div>
</div>
