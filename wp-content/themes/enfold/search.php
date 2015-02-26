<?php
global $avia_config;
/*
* get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
*/
get_header();
$results = avia_which_archive();
echo avia_title(array('title' => $results ));
?>

<div style="background-color:#d7d7d7 !important; width:100% !important; height: auto;">
<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>
<div class='container'>
<main class='content template-search <?php avia_layout_class( 'content' ); ?> units' <?php avia_markup_helper(array('context' => 'content'));?>>
<div class='page-heading-container clearfix'>
<section class="search_form_field">
<?php echo do_shortcode("[av_hr class='invisible' height='40' shadow='no-shadow' position='center']
[av_one_third first]
[av_textblock]
<b>
<p style='font-size: 45px; text-align: right; font-weight: 900; color: #717171;  line-height: 50px'>Resultados de tu búsqueda</p>
</b>
[/av_textblock]
[/av_one_third]
[av_one_fifth]
[av_textblock ]
<div style='border-left: 2px solid #ccc; width:3px; height:150px; margin:auto'></div>
[/av_textblock]
[/av_one_fifth]
[av_one_third]

[av_textblock]
Si no obtuviste los resultados esperados, prueba una nueva búsqueda con diferentes términos.
[/av_textblock]
[/av_one_third]
[av_one_half first]
[/av_one_half]")?> 

<div style="width:170px; float: right; margin-right: 27% ;margin-top:-160px; z-index:8000"> 
<?php
get_search_form(buscar);
?>
</div>     
</section>
</div>
<div style="min-height:50px;">
<?php
if(!empty($_GET['s']))
{
//echo "<h4 class='extra-mini-title widgettitle'>{$results}</h4>";
/* Run the loop to output the posts.
* If you want to overload this in a child theme then include a file
* called loop-search.php and that will be used instead.
*/
$more = 0;
get_template_part( 'includes/loop', 'search' );
}
?>
</div>
<!--end content-->
</main>
<?php
//get the sidebar
$avia_config['currently_viewing'] = 'page';
get_sidebar();
?>
</div><!--end container-->
</div><!-- close default .container_wrap element -->
</div>
<?php echo do_shortcode("[av_hr class='full' height='50' shadow='no-shadow' position='center']"); ?>
<div style="width:960px; margin:auto">
<?php echo do_shortcode("[av_hr class='invisible' height='20' shadow='no-shadow' position='center']

[av_partner columns='3' heading='' size='featured_large' border='' type='slider' animation='slide' navigation='no' autoplay='false' interval='5']
[av_partner_logo id='11661' attachment='' hover='' link='manually,http://mc.arbolnaranja.com/mac/' linktitle='' link_target='']
[av_partner_logo id='8550' attachment='' hover='' link='manually,http://mc.arbolnaranja.com/accesorios/' linktitle='' link_target='']
[av_partner_logo id='13275' attachment='' hover='' link='manually,http://mc.arbolnaranja.com/training-room/' linktitle='' link_target='']
[/av_partner]");?>
</div>
<?php get_footer(); ?>
