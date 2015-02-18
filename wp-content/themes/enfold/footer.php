		<?php
		global $avia_config;
		$blank = isset($avia_config['template']) ? $avia_config['template'] : "";

		//reset wordpress query in case we modified it
		wp_reset_query();


		//get footer display settings
		$the_id 				= avia_get_the_id(); //use avia get the id instead of default get id. prevents notice on 404 pages
		$footer 				= get_post_meta($the_id, 'footer', true);
		$footer_widget_setting 	= !empty($footer) ? $footer : avia_get_option('display_widgets_socket');


		//check if we should display a footer
		if(!$blank && $footer_widget_setting != 'nofooterarea' )
		{
			if( $footer_widget_setting != 'nofooterwidgets' )
			{
				//get columns
				$columns = avia_get_option('footer_columns');
		?>
				

					<div class='container'>

					<?php echo do_shortcode("[av_section color='main_color' custom_bg='#f7f7f7' src='' attachment='' attach='scroll' position='top left' repeat='no-repeat' video='' video_ratio='16:9' min_height='' padding='no-padding' shadow='no-shadow' id='']
[av_hr class='invisible' height='30' shadow='no-shadow' position='center']

[av_one_fifth first]

[/av_one_fifth][av_one_fifth]

[av_font_icon icon='ue823' font='entypo-fontello' style='' caption='' link='' linktarget='' color='#000000' size='20px' position='center'][/av_font_icon]

[av_hr class='invisible' height='-30' shadow='no-shadow' position='center']

[av_heading tag='h3' padding='0' heading='Más.' color='' style='blockquote modern-quote modern-centered' custom_font='' size='' subheading_active='' subheading_size='15'][/av_heading]

[av_hr class='invisible' height='-30' shadow='no-shadow' position='center']

[av_heading tag='h6' padding='0' heading='<a href='http://mc.arbolnaranja.com/accesorios/'>Accesorios <strong>›</strong></a>' color='custom-color-heading' style='blockquote modern-quote modern-centered' custom_font='#0088cc' size='' subheading_active='' subheading_size='15'][/av_heading]

[/av_one_fifth][av_one_fifth]

[av_font_icon icon='u5555' font='misiconos' style='' caption='' link='' linktarget='' color='#000000' size='20px' position='center'][/av_font_icon]

[av_hr class='invisible' height='-30' shadow='no-shadow' position='center']

[av_heading tag='h3' padding='0' heading='Visítanos.' color='' style='blockquote modern-quote modern-centered' custom_font='' size='' subheading_active='' subheading_size='15'][/av_heading]

[av_hr class='invisible' height='-30' shadow='no-shadow' position='center']

[av_heading tag='h6' padding='0' heading='<a href='http://mc.arbolnaranja.com/tiendas'>Tiendas <strong>›</strong></a>' color='custom-color-heading' style='blockquote modern-quote modern-centered' custom_font='#0088cc' size='' subheading_active='' subheading_size='15'][/av_heading]

[/av_one_fifth][av_one_fifth]

[av_font_icon icon='u7777' font='misiconos' style='' caption='' link='' linktarget='' color='#000000' size='18px' position='center'][/av_font_icon]

[av_hr class='invisible' height='-30' shadow='no-shadow' position='center']

[av_heading tag='h3' padding='0' heading='Contáctanos.' color='' style='blockquote modern-quote modern-centered' custom_font='' size='' subheading_active='' subheading_size='15'][/av_heading]

[av_hr class='invisible' height='-30' shadow='no-shadow' position='center']

[av_heading tag='h6' padding='0' heading='<a href='http://mc.arbolnaranja.com/soporte/'>Información <strong>›</strong></a>' color='custom-color-heading' style='blockquote modern-quote modern-centered' custom_font='#0088cc' size='' subheading_active='' subheading_size='15'][/av_heading]

[/av_one_fifth][av_hr class='invisible' height='35' shadow='no-shadow' position='center']
[/av_section]

[av_section color='footer_color' custom_bg='' src='' attachment='' attach='scroll' position='top center' repeat='no-repeat' video='' video_ratio='16:9' min_height='' padding='no-padding' shadow='no-shadow' id='']
[av_one_fifth first]

[av_textblock ]<strong>Mac Center</strong>

<small><a href='http://mc.arbolnaranja.com/quienes-somos/' target='blank'>Compañía</a>
<a href='http://mc.arbolnaranja.com/soporte/'>Contáctanos</a>
</small>

[/av_textblock]

[/av_one_fifth][av_one_fifth]

[av_textblock]
<strong>Comprar </strong>

<small><a href='http://mc.arbolnaranja.com/imac-5k/'>Comprar Mac</a>
<a href='http://mc.arbolnaranja.com/ipod-touch/'>Comprar iPod</a>
<a href='http://mc.arbolnaranja.com/iphone-6/'>Comprar iPhone</a>
<a href='http://mc.arbolnaranja.com/ipad-air-2/'>Comprar iPad</a>
<a href='http://mc.arbolnaranja.com/comprar/accesorios/'>Comprar accesorios</a></small>
[/av_textblock]

[/av_one_fifth][av_one_fifth]

[av_textblock]
<strong>Comparar </strong>

<small><a href='http://mc.arbolnaranja.com/comparar-mac/'>Comparar Mac</a>
<a href='http://mc.arbolnaranja.com/comparar/'>Comparar iPhone</a>
<a href='http://mc.arbolnaranja.com/comparar-ipad/'>Comparar iPad</a>
</small>
[/av_textblock]

[/av_one_fifth][av_one_fifth]

[av_textblock]
<strong>Soporte</strong>

<small><a href='http://mc.arbolnaranja.com/mi-cuenta/view-order/ 'target='_blank'>Estado del pedido
</a><a title='Preguntas frecuentes' href='http://mc.arbolnaranja.com/preguntas-frecuentes/'target='_blank'>Preguntas frecuentes</a>
<a href='http://mc.arbolnaranja.com/condiciones-garantia-apple/' title='Condiciones Garantía Apple 'target='_blank'>Condiciones garantía Apple</a>
</small>
[/av_textblock]

[/av_one_fifth][av_one_fifth]

[av_textblock]
<strong>Tiendas </strong>

<small><a title='Tiendas' href='http://mc.arbolnaranja.com/tiendas/'>Encuentra una Tienda</a>
<a title='Entrenamiento' href='http://mc.arbolnaranja.com/training-room/'>Training Room</a>
</small>
[/av_textblock]

[/av_one_fifth]
[/av_section]"); ?> 


					</div>


				<!-- ####### END FOOTER CONTAINER ####### -->
			

	<?php   } //endif nofooterwidgets ?>



			<!-- end main -->
			</div>

			<?php

			//copyright
			$copyright = do_shortcode( avia_get_option('copyright', "&copy; ".__('Copyright','avia_framework')."  - <a href='".home_url('/')."'>".get_bloginfo('name')."</a>") );

			// you can filter and remove the backlink with an add_filter function
			// from your themes (or child themes) functions.php file if you dont want to edit this file
			// you can also just keep that link. I really do appreciate it ;)
			//$kriesi_at_backlink =	apply_filters("kriesi_backlink", " - <a href='http://www.kriesi.at'>Enfold Theme by Kriesi</a>");


			//you can also remove the kriesi.at backlink by adding [nolink] to your custom copyright field in the admin area
			if($copyright && strpos($copyright, '[nolink]') !== false)
			{
				$kriesi_at_backlink = "";
				$copyright = str_replace("[nolink]","",$copyright);
			}

			if( $footer_widget_setting != 'nosocket' )
			{

			?>

				<footer class='container_wrap socket_color' id='socket' <?php avia_markup_helper(array('context' => 'footer')); ?>>
                    <div class='container'>

                        <span class='copyright'><?php echo $copyright . $kriesi_at_backlink; ?></span>

                        <?php
                            echo "<nav class='sub_menu_socket' ".avia_markup_helper(array('context' => 'nav', 'echo' => false)).">";
                                $avia_theme_location = 'avia3';
                                $avia_menu_class = $avia_theme_location . '-menu';

                                $args = array(
                                    'theme_location'=>$avia_theme_location,
                                    'menu_id' =>$avia_menu_class,
                                    'container_class' =>$avia_menu_class,
                                    'fallback_cb' => '',
                                    'depth'=>1
                                );

                                wp_nav_menu($args);
                            echo "</nav>";
                        ?>

                    </div>

	            <!-- ####### END SOCKET CONTAINER ####### -->
				</footer>


			<?php
			} //end nosocket check


		}
		else
		{
			echo "<!-- end main --></div>";
		} //end blank & nofooterarea check

		//display link to previeous and next portfolio entry
		echo avia_post_nav();

		echo "<!-- end wrap_all --></div>";


		if(isset($avia_config['fullscreen_image']))
		{ ?>
			<!--[if lte IE 8]>
			<style type="text/css">
			.bg_container {
			-ms-filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $avia_config['fullscreen_image']; ?>', sizingMethod='scale')";
			filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $avia_config['fullscreen_image']; ?>', sizingMethod='scale');
			}
			</style>
			<![endif]-->
		<?php
			echo "<div class='bg_container' style='background-image:url(".$avia_config['fullscreen_image'].");'></div>";
		}
	?>


<?php




	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */


	wp_footer();


?>
<a href='#top' title='<?php _e('Scroll to top','avia_framework'); ?>' id='scroll-top-link' <?php echo av_icon_string( 'scrolltop' ); ?>></a>
<div id="fb-root"></div>
</body>
</html>
