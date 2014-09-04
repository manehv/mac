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
    var product_variations_<?php echo $post->ID; ?> = <?php echo json_encode( $available_variations ) ?>;
</script>
<?php $mymeta = get_post_meta( get_the_ID(), 'carousel' );
echo do_shortcode($mymeta[0]); ?>
<?php //do_action('woocommerce_before_add_to_cart_form'); ?>
<div class="row">
  <div class="col-md-9">
			<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>">
				<table class="variations" cellspacing="0">
					<tbody>
						<?php $show_image=1; $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
							<tr>
								<td class="label"><label for="<?php echo sanitize_title($name); ?>"><?php echo $woocommerce->attribute_label($name); ?></label></td>
								<td class="value"><fieldset>
									<strong>Choose An Option for...</strong><br />
									<?php
										if ( is_array( $options ) ) {

											if ( empty( $_POST ) )
												$selected_value = ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) ? $selected_attributes[ sanitize_title( $name ) ] : '';
											else
												$selected_value = isset( $_POST[ 'attribute_' . sanitize_title( $name ) ] ) ? $_POST[ 'attribute_' . sanitize_title( $name ) ] : '';

											// Get terms if this is a taxonomy - ordered
											if ( taxonomy_exists( sanitize_title( $name ) ) ) {

												$terms = get_terms( sanitize_title($name), array('menu_order' => 'ASC') );

												foreach ( $terms as $term ) {
													if ( ! in_array( $term->slug, $options ) ) continue;
													echo '<input type="radio"  value="' . strtoupper($term->slug) . '" ' . checked( $selected_value, $term->slug, false ) . ' id="'. esc_attr( sanitize_title($name) ) .'" name="attribute_'. sanitize_title($name).'">' . apply_filters( 'woocommerce_variation_option_name', $term->name ).'<br />';
												}
											} else {
												foreach ( $options as $option )
													echo '<input type="radio"  value="' . strtoupper($option) . '" ' . checked( $selected_value, $option, false ) . ' id="'. esc_attr( sanitize_title($name) ) .'" name="attribute_'. sanitize_title($name).'">' . apply_filters( 'woocommerce_variation_option_name', $option ) . '<br />';
											}
										}
									?>
								</fieldset>
								<?php
								
										 if($show_image==1)
										{
											//	do_action( 'woocommerce_before_single_product_summary' ); 
											?>
											
											<td><img class="variation_image" src=""></td>
												
												<?php
												$show_image=0;
										}
									if ( sizeof($attributes) == $loop )
										echo '<a class="reset_variations" href="#reset">'.__('Clear selection', 'woocommerce').'</a>';
								?></td>
							</tr>
								<?php endforeach;?>
					</tbody>
				</table>

				<?php do_action('woocommerce_before_add_to_cart_button'); ?>

			
				<div>
					<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
				<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" /></div>

				<?php do_action('woocommerce_after_add_to_cart_button'); ?>
                
			</form>
 			<?php the_content(); ?>
  </div>
  <div class="col-md-3">
      	<div id="idSticky" class="clsSticky">
				<div class="clsSidebar">
				<h3><?php _e('Abstract','woocommerce'); ?></h3>
				 <div class="row clshide">  
					<div class="clsDetails clsDetailsImg">
						<img class="variation_image v-image" src=""/>
					</div>
					 <div class="clsDetails">
							<div class="clsDetails" id="prodtitle" title="<?php the_title(); ?>"><?php the_title(); ?></div>	
					 </div>
					 <div class="clsDetails" id="sku"></div>
					 <div class="clsDetails">
							<div class="clsDetails" id="price"></div>	
					 </div>
			
					
					<div class="clsDetails hideform">
						<form  method="post"  enctype='multipart/form-data'>
 						  <input type="hidden" id="addtocart" value="" name="add-to-cart"/>
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

 
 