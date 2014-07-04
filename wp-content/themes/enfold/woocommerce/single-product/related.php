<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

$related = $product->get_related( $posts_per_page );

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters( 'woocommerce_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => $posts_per_page,
	'orderby'              => $orderby,
	'post__in'             => $related,
	'post__not_in'         => array( $product->id )
) );

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = $columns; 

if ( $products->have_posts() ) : ?>

	<div class="related products">

		<?php while ( $products->have_posts() ) : $products->the_post(); ?>
		    <div class="col-lg-3">
		      <a href="<?php the_permalink(); ?>">
			<div class="col-lg-6">
			  <?php  // wc_get_template_part( 'content', 'product' );
			    if ( has_post_thumbnail())
			    echo get_the_post_thumbnail( $post_id,'thumbnail', $attr );
			  ?>
			 </div>
			 <div class="col-lg-6 clsRelTitle">
			    Comprar
			    <?php
			      the_title();
			    ?>
			 </div>
		      </a>
		    </div>
		<?php endwhile; // end of the loop. ?>

	</div>

<?php endif;
wp_reset_postdata();
