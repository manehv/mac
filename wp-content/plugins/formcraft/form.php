<?php 

require('../../../wp-blog-header.php');
global $wpdb, $table_subs, $table_builder, $table_info, $ppage;

$id = intval(preg_replace("/[^0-9,.]/", "", $_GET['id']));

$myrows = $wpdb->get_results( "SELECT * FROM $table_builder WHERE id=$id", "ARRAY_A" );
$con = stripslashes($myrows[0]['con']);
$con = json_decode($con, 1);

$table_info = $wpdb->prefix . "formcraft_info";


if ( (!is_user_logged_in() || $_GET['preview']!=true) )
{

	if (!$con[0]['formpage']=='1')
	{
		exit;
	}
}
if ($_GET['preview']=='true')
{
	$ppage = true;
}

?>


<!DOCTYPE html>


<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	<title><?php echo $myrows[0]['name']; ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php 

wp_head();

?>
<style>
	html
	{
		height: 100%;
		background: #f9f9f9;
background: -moz-radial-gradient(center, ellipse cover, rgba(255,255,255,1) 0%, rgba(240,240,240,0.3) 100%);
background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(240,240,240,0.3)));
background: -webkit-radial-gradient(center, ellipse cover, rgba(255,255,255,1) 0%,rgba(240,240,240,0.3) 100%);
background: -o-radial-gradient(center, ellipse cover, rgba(255,255,255,1) 0%,rgba(240,240,240,0.3) 100%);
background: -ms-radial-gradient(center, ellipse cover, rgba(255,255,255,1) 0%,rgba(240,240,240,0.3) 100%);
background: radial-gradient(ellipse at center, rgba(255,255,255,1) 0%,rgba(240,240,240,0.3) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#4df0f0f0',GradientType=1 );
	}
	body
	{
		background: transparent;
		background-color: transparent;
	}
	.nform
	{
		margin-right: auto;
		margin-left: auto;
		box-shadow: 0px 0px 2px #777 !important;
		-moz-box-shadow: 0px 0px 2px #777 !important;
		-webkit-box-shadow: 0px 0px 2px #777 !important;
		border: 0px !important;
	}
	.nform.noframe
	{
		box-shadow: none !important;
		-moz-box-shadow: none !important;
		-webkit-box-shadow: none !important;
	}
</style>
</head>



<body>

	<?php 


	$image = $con[0]['formpage_image'];

	if ($image)
	{
		echo "<img class='logo_form' src='".$image."' style='margin: auto auto; display: block'/><br><br>";
	}
	else
	{
		echo '<div style="height: 40px"></div>';
	}
	
	$id = intval(preg_replace("/[^0-9,.]/", "", $_GET['id']));
	formcraft($id);
	do_action('wp_head');

	?>
	<br><br>
</body>
</html>