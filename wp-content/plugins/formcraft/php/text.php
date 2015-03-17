<?php
if (!isset($_SESSION)) {
	session_start();
}

error_reporting(0);

function color_name_to_hex($color_name)
{
	$colors  =  array('aliceblue'=>'F0F8FF','antiquewhite'=>'FAEBD7','aqua'=>'00FFFF','aquamarine'=>'7FFFD4','azure'=>'F0FFFF','beige'=>'F5F5DC','bisque'=>'FFE4C4','black'=>'000000','blanchedalmond '=>'FFEBCD','blue'=>'0000FF','blueviolet'=>'8A2BE2','brown'=>'A52A2A','burlywood'=>'DEB887','cadetblue'=>'5F9EA0','chartreuse'=>'7FFF00','chocolate'=>'D2691E','coral'=>'FF7F50','cornflowerblue'=>'6495ED','cornsilk'=>'FFF8DC','crimson'=>'DC143C','cyan'=>'00FFFF','darkblue'=>'00008B','darkcyan'=>'008B8B','darkgoldenrod'=>'B8860B','darkgray'=>'A9A9A9','darkgreen'=>'006400','darkgrey'=>'A9A9A9','darkkhaki'=>'BDB76B','darkmagenta'=>'8B008B','darkolivegreen'=>'556B2F','darkorange'=>'FF8C00','darkorchid'=>'9932CC','darkred'=>'8B0000','darksalmon'=>'E9967A','darkseagreen'=>'8FBC8F','darkslateblue'=>'483D8B','darkslategray'=>'2F4F4F','darkslategrey'=>'2F4F4F','darkturquoise'=>'00CED1','darkviolet'=>'9400D3','deeppink'=>'FF1493','deepskyblue'=>'00BFFF','dimgray'=>'696969','dimgrey'=>'696969','dodgerblue'=>'1E90FF','firebrick'=>'B22222','floralwhite'=>'FFFAF0','forestgreen'=>'228B22','fuchsia'=>'FF00FF','gainsboro'=>'DCDCDC','ghostwhite'=>'F8F8FF','gold'=>'FFD700','goldenrod'=>'DAA520','gray'=>'808080','green'=>'008000','greenyellow'=>'ADFF2F','grey'=>'808080','honeydew'=>'F0FFF0','hotpink'=>'FF69B4','indianred'=>'CD5C5C','indigo'=>'4B0082','ivory'=>'FFFFF0','khaki'=>'F0E68C','lavender'=>'E6E6FA','lavenderblush'=>'FFF0F5','lawngreen'=>'7CFC00','lemonchiffon'=>'FFFACD','lightblue'=>'ADD8E6','lightcoral'=>'F08080','lightcyan'=>'E0FFFF','lightgoldenrodyellow'=>'FAFAD2','lightgray'=>'D3D3D3','lightgreen'=>'90EE90','lightgrey'=>'D3D3D3','lightpink'=>'FFB6C1','lightsalmon'=>'FFA07A','lightseagreen'=>'20B2AA','lightskyblue'=>'87CEFA','lightslategray'=>'778899','lightslategrey'=>'778899','lightsteelblue'=>'B0C4DE','lightyellow'=>'FFFFE0','lime'=>'00FF00','limegreen'=>'32CD32','linen'=>'FAF0E6','magenta'=>'FF00FF','maroon'=>'800000','mediumaquamarine'=>'66CDAA','mediumblue'=>'0000CD','mediumorchid'=>'BA55D3','mediumpurple'=>'9370D0','mediumseagreen'=>'3CB371','mediumslateblue'=>'7B68EE','mediumspringgreen'=>'00FA9A','mediumturquoise'=>'48D1CC','mediumvioletred'=>'C71585','midnightblue'=>'191970','mintcream'=>'F5FFFA','mistyrose'=>'FFE4E1','moccasin'=>'FFE4B5','navajowhite'=>'FFDEAD','navy'=>'000080','oldlace'=>'FDF5E6','olive'=>'808000','olivedrab'=>'6B8E23','orange'=>'FFA500','orangered'=>'FF4500','orchid'=>'DA70D6','palegoldenrod'=>'EEE8AA','palegreen'=>'98FB98','paleturquoise'=>'AFEEEE','palevioletred'=>'DB7093','papayawhip'=>'FFEFD5','peachpuff'=>'FFDAB9','peru'=>'CD853F','pink'=>'FFC0CB','plum'=>'DDA0DD','powderblue'=>'B0E0E6','purple'=>'800080','red'=>'FF0000','rosybrown'=>'BC8F8F','royalblue'=>'4169E1','saddlebrown'=>'8B4513','salmon'=>'FA8072','sandybrown'=>'F4A460','seagreen'=>'2E8B57','seashell'=>'FFF5EE','sienna'=>'A0522D','silver'=>'C0C0C0','skyblue'=>'87CEEB','slateblue'=>'6A5ACD','slategray'=>'708090','slategrey'=>'708090','snow'=>'FFFAFA','springgreen'=>'00FF7F','steelblue'=>'4682B4','tan'=>'D2B48C','teal'=>'008080','thistle'=>'D8BFD8','tomato'=>'FF6347','turquoise'=>'40E0D0','violet'=>'EE82EE','wheat'=>'F5DEB3','white'=>'FFFFFF','whitesmoke'=>'F5F5F5','yellow'=>'FFFF00','yellowgreen'=>'9ACD32');

$color_name = strtolower($color_name);
if (isset($colors[$color_name]))
{
	return ($colors[$color_name]);
}
else
{
	return false;
}
}

function calculateTextBox($font_size, $font_angle, $font_file, $text)
{ 

	$box   = imagettfbbox($font_size, $font_angle, $font_file, $text); 
	if( !$box ) 
		return false; 
	$min_x = min( array($box[0], $box[2], $box[4], $box[6]) ); 
	$max_x = max( array($box[0], $box[2], $box[4], $box[6]) ); 
	$min_y = min( array($box[1], $box[3], $box[5], $box[7]) ); 
	$max_y = max( array($box[1], $box[3], $box[5], $box[7]) ); 
	$width  = ( $max_x - $min_x ); 
	$height = ( $max_y - $min_y ); 
	$left   = abs( $min_x ) + $width; 
	$top    = abs( $min_y ) + $height; 
	$img     = @imagecreatetruecolor( $width << 2, $height << 2 );
	$white   =  imagecolorallocate( $img, 255, 255, 255 ); 
	$black   =  imagecolorallocate( $img, 0, 0, 0 ); 
	imagefilledrectangle($img, 0, 0, imagesx($img), imagesy($img), $black); 
	imagettftext( $img, $font_size, 
		$font_angle, $left, $top, 
		$white, $font_file, $text); 
	$rleft  = $w4 = $width<<2; 
	$rright = 0; 
	$rbottom   = 0; 
	$rtop = $h4 = $height<<2; 
	for( $x = 0; $x < $w4; $x++ ) 
		for( $y = 0; $y < $h4; $y++ ) 
			if( imagecolorat( $img, $x, $y ) ){ 
				$rleft   = min( $rleft, $x ); 
				$rright  = max( $rright, $x ); 
				$rtop    = min( $rtop, $y ); 
				$rbottom = max( $rbottom, $y ); 
			} 
			imagedestroy( $img ); 
			return array( "left"   => $left - $rleft, 
				"top"    => $top  - $rtop, 
				"width"  => $rright - $rleft + 1, 
				"height" => $rbottom - $rtop + 1 ); 
		}


		if (!(isset($_GET['text'])))
		{
			die();
		}
		$size = 30;
		putenv('GDFONTPATH=' . realpath('.'));
		$font_path = 'source.ttf';
		$img = calculateTextBox($size, 0, $font_path, $_GET['text']);
		$image = imagecreatetruecolor($img['width']+70, $img['height']+45);

		if (isset($_GET['bg']) && color_name_to_hex($_GET['bg'])==true)
		{
			$temp = color_name_to_hex($_GET['bg']);
			$BG_color[0] = intval(hexdec(substr($temp, 0,2)));
			$BG_color[1] = intval(hexdec(substr($temp, 2,2)));
			$BG_color[2] = intval(hexdec(substr($temp, 4,2)));
		}
		else
		{
			$BG_color[0] = 50;
			$BG_color[1] = 138;
			$BG_color[2] = 238;
		}
		if ( isset($_GET['text_color']) && color_name_to_hex($_GET['text_color'])==true)
		{
			$temp = color_name_to_hex($_GET['text_color']);
			$TEXT_color[0] = intval(hexdec(substr($temp, 0,2)));
			$TEXT_color[1] = intval(hexdec(substr($temp, 2,2)));
			$TEXT_color[2] = intval(hexdec(substr($temp, 4,2)));
		}
		else
		{
			$TEXT_color[0] = 255;
			$TEXT_color[1] = 255;
			$TEXT_color[2] = 255;
		}

		$background = imagecolorallocate($image, $BG_color[0], $BG_color[1], $BG_color[2]);
		$color = imagecolorallocate($image, $TEXT_color[0], $TEXT_color[1], $TEXT_color[2]);

		imagefilledrectangle($image, 0, 0, $img['width']+70, $img['height']+45, $background);
		imagettftext($image, $size, 0, $img['left']+35, $img['top']+20, $color, $font_path, $_GET['text']);
		$image = imagerotate($image, 90, 0);
		header("Content-Type: image/png");				
		header("Cache-Control: public, max-age=2592000");

		imagepng($image,NULL,9);
		imagedestroy($image);
		?>