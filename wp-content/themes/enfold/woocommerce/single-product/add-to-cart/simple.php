<?php
/**
 * Variable product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.5
 */

global $woocommerce, $product, $post;


?>
<script type="text/javascript">
    var product_variations_<?php echo $post->ID; ?> = <?php echo json_encode( $available_variations )?>;
</script>
<?php 

$mymeta = get_post_meta( get_the_ID(), 'carousel' );
echo do_shortcode($mymeta[0]);

$thumb_id = get_post_thumbnail_id();
$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);
?>
<?php //do_action('woocommerce_before_add_to_cart_form'); ?>
<div class="row">
	<div class="col-md-9 clsvari clsContent">
	<h1 class="clsBotTitle">
					<?php _e('Choose a ','woocommerce').the_title(); ?>
			</h1>
			<div class="clsDetails clsDetailsImg">
						<img class="variation_image v-image" src="<?php echo $thumb_url[0]; ?>"/>
					</div>
			<?php if( $post->post_excerpt ): ?>
				<div class="clsExc">
					<?php the_excerpt(); ?>
				</div>
			<?php endif; ?>
 			<?php the_content(); ?>
  </div>
  <div class="col-md-3">
      	<div id="idSticky" class="clsSticky">
				<div class="clsSidebar">
				<h3><?php _e('Abstract','woocommerce'); ?></h3>
				 <div class="row clshide">  
					<div class="clsDetails clsDetailsImg">
						<img class="variation_image v-image" src="<?php echo $thumb_url[0]; ?>"/>
					</div>
					 <div class="clsDetails">
							<div id="prodtitle" title="<?php the_title(); ?>"><?php the_title(); ?></div>	
					 </div>
					  <div>
					   <div class="clsDetails" id="sku"><?php echo $product->get_sku(); ?></div>
					  </div>
					  <div>
					   <div class="clsDetails" id="sku"><?php echo $product->get_price_html(); ?></div>
					  </div>
					  <div class="clsDetails hideform">
						 <form  method="post"  enctype='multipart/form-data'>
 						 <input type="hidden" id="addtocart" value="<?php echo get_the_ID(); ?>" name="add-to-cart"/>
						 <input id="set-aqy" class="qty" type="hidden" name="quantity" value="1" />
						  <div><?php woocommerce_quantity_input(); ?>
 						   <button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type); ?></button>
					   </form>
					</div>
					</div>
				</div>    			
				<?php
				
				?>
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
      		</div> <!-- clsSidebar --> 
  </div> 
</div>
<?php 
do_action('woocommerce_after_add_to_cart_form'); ?>

 
 