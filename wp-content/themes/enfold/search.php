<?php
global $avia_config;
/*
* get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
*/
get_header();
$results = avia_which_archive();
echo avia_title(array('title' => $results ));
?>
<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>
<div class='container'>
<main class='content template-search <?php avia_layout_class( 'content' ); ?> units' <?php avia_markup_helper(array('context' => 'content'));?>>
<div class='page-heading-container clearfix'>
<section class="search_form_field">
<?php echo do_shortcode("[av_hr class='invisible' height='100' shadow='no-shadow' position='center']

[av_one_third first]

[av_textblock]
<b>
<p style='font-size: 45px; text-align: right; font-weight: 900; color: #5d5d5d;  line-height: 50px'>Resultados de tu búsqueda</p>

</b>
[/av_textblock]

[/av_one_third][av_one_fifth]
[av_textblock ]
<div style='border-left: 2px solid #ccc; width:3px; height:150px; margin:auto'></div>
[/av_textblock]
[/av_one_fifth]

[av_one_third]
[av_hr class='invisible' height='50' shadow='no-shadow' position='center']

[av_textblock]
Si no obtuviste los resultados esperados, prueba una nueva búsqueda con diferentes términos.
[/av_textblock]
[/av_one_third]

")?> 
    <div style=' width: 300px;
    margin: auto;
    height: auto;'>
    <?php
/*echo "<h4>".__('New Search','avia_framework')."</h4>";*/

get_search_form();
/*echo "<span class='author-extra-border'></span>";*/
?>
        </div>
</section>
</div>
<div style="min-height:250px">
<?php
if(!empty($_GET['s']))
{
echo "<h4 class='extra-mini-title widgettitle'>{$results}</h4>";
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
<div style="width:960px; margin:auto">
<?php echo do_shortcode("[av_three_fourth first][av_textblock][contact-form-7 id='4122' title='suscribe'][/av_textblock][/av_three_fourth]"); ?>
<?php echo do_shortcode("[av_one_fourth]
[av_hr class='invisible' height='45' shadow='shadow' position='center']
[av_font_icon icon='ue8f5' font='entypo-fontello' style='' caption='' link='manually,https://www.facebook.com/maccentercolombia' linktarget='_blank' color='#000000' size='19px' position='left'][/av_font_icon]
[av_font_icon icon='ue8bd' font='entypo-fontello' style='' caption='' link='' linktarget='_blank' color='#f7f7f7' size='40px' position='left'][/av_font_icon]
[av_font_icon icon='ue8f1' font='entypo-fontello' style='' caption='' link='manually,https://twitter.com/MacCenter' linktarget='_blank' color='#000000' size='19px' position='left'][/av_font_icon]
[av_font_icon icon='ue8bd' font='entypo-fontello' style='' caption='' link='' linktarget='_blank' color='#f7f7f7' size='40px' position='left'][/av_font_icon]
[av_font_icon icon='ue909' font='entypo-fontello' style='' caption='' link='manually,http://instagram.com/maccenter' linktarget='_blank' color='#000000' size='19px' position='left'][/av_font_icon]
[av_font_icon icon='ue8bd' font='entypo-fontello' style='' caption='' link='' linktarget='_blank' color='#f7f7f7' size='40px' position='left'][/av_font_icon]
[av_font_icon icon='ue921' font='entypo-fontello' style='' caption='' link='manually,https://www.youtube.com/user/MacCenterColombia' linktarget='_blank' color='#000000' size='17px' position='left'][/av_font_icon]
[/av_one_fourth]"); ?>
</div>
<?php echo do_shortcode("[av_hr class='full' height='50' shadow='no-shadow' position='center']"); ?>
<?php get_footer(); ?>
