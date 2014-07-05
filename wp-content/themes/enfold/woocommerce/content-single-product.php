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
	<?php do_action('woocommerce_variable_add_to_cart' ); ?>
    </div>
    <div class="col-lg-3">
	<!-- for sidebar -->
    </div>
</div>
