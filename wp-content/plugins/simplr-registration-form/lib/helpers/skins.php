<?php 
$dir = SIMPLR_DIR;
$skins = array();

foreach( scandir($dir.'/assets/skins') as $skin ) {
	if( $skin != '.' AND $skin != '..' ) {
		$skins[$skin] = $skin;
	}
}



