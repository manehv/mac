<?php
if (!isset($_SESSION)) {
	session_start();
}

error_reporting(-1);

$captcha_w = 220;
$captcha_h = 60;
$min_font_size = 22;
$max_font_size = 28;
$angle = 10;
putenv('GDFONTPATH=' . realpath('.'));
$font_path = 'source.ttf';

$operators = array('+','-','*');
$first_num = rand(1,9);
$second_num = rand(1,12);

if ($_GET['type']=='number')
{

	shuffle($operators);
	$session_var = $second_num.$operators[0].$first_num;
	if ($operators[0]=='+')
	{
		$session_var2 = $second_num+$first_num;
	}
	if ($operators[0]=='-')
	{
		$session_var2 = $second_num-$first_num;
	}
	if ($operators[0]=='*')
	{
		$session_var2 = $second_num*$first_num;
	}
	$_SESSION["security_number_new"] = isset($_SESSION["security_number_new"]) ? $_SESSION["security_number_new"] : array();
	$_SESSION["security_number_new"][] = $session_var2;
}
else
{
	$temp = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
	shuffle($temp);
	$temp = array_slice($temp, 0, 5);
	$session_var = implode('', $temp);
	$_SESSION["security_number_new"] = isset($_SESSION["security_number_new"]) ? $_SESSION["security_number_new"] : array();
	$_SESSION["security_number_new"][] = $session_var;
}




$img = imagecreate( $captcha_w, $captcha_h );
$black = imagecolorallocate($img,170,170,170);
$line_col = imagecolorallocate($img,200,200,200);
$bg_color = imagecolorallocatealpha($img, 0, 0, 0, 255);
$background = imagecolorallocate($img,255,255,255);

imagefill( $img, 0, 0, $bg_color );	
imagesavealpha($img, TRUE);
imagefilledrectangle($img, 0, 0, 400, 60, $background);
$k = 0;
while ($k<40)
{
	imageline($img, 220, $k*6, 0, $k*6, $line_col);
	imageline($img, $k*6, 0, $k*6, 60, $line_col);
	$k++;
}


$a = 1;
$len = strlen($session_var);
$space = ($captcha_w-20)/$len;
$space_h = $captcha_h-10;

while ($a<=$len)
{

	imagettftext(
		$img,
		rand(
			$min_font_size,
			$max_font_size
			),
		rand( -$angle , $angle ),
		rand( ($space*($a-1))+10, ($space*$a)-10 ),
		rand( $space_h, $space_h/2 ),
		$black,
		$font_path,
		substr($session_var,$a-1,1));

	$a++;

}

header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

header("Content-type:image/jpeg");
header("Content-Disposition:inline ; filename=secure.jpg");
imagepng($img,NULL,0);
?>