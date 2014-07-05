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
<h1 class="clsTopTitle">
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
    </div>
    <div class="col-lg-3">
	<!-- for sidebar -->
    </div>
</div>



<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	

		/**
		 * woocommerce_before_single_product_summary hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */

		//do_action( 'woocommerce_before_single_product_summary' );

	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
	//		do_action( 'woocommerce_single_product_summary' );
		do_action('woocommerce_variable_add_to_cart');
		?>

	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );

	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
