<?php 
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/

global $arfsettings; 

if (isset($message) && $message != ''){ 

    if(is_admin()){ 

        ?>
<div id="message" class="updated" style="padding:5px;"><?php echo $message ?></div>
<?php // change for ajax_submission in preview

    } else { 

        echo $message; 

    }

} 



if( isset($errors) && is_array($errors) && !empty($errors) ){

    global $arfsettings;

?>
<div class="<?php echo (is_admin()) ? 'error' : 'frm_error_style' ?>">
  <?php 

if(!is_admin()){ 

	$img = '';
    if($img and !empty($img)){

    }

} 

    

if(empty($arfsettings->invalid_msg)){

    $show_img = false;

    foreach( $errors as $error ){

        if($show_img and isset($img) and !empty($img)){ 
 

        }else{

            $show_img = true;

        }

		echo '<div class="msg-detail">
			<div class="msg-title-error">Error</div>
			<div class="msg-description-error">'.stripslashes($error).'</div>
		  </div>';

    }

}else{

    echo '<div class="msg-detail">
			<div class="msg-title-error">Error</div>
			<div class="msg-description-error">'.$arfsettings->invalid_msg.'</div>
		  </div>';



    $show_img = true;

    foreach( $errors as $err_key => $error ){

        if(!is_numeric($err_key) and ($err_key == 'cptch_number' or $err_key == 'form' or strpos($err_key, 'field') === 0 or strpos($err_key, 'captcha') === 0 ))

            continue;

          

        echo '<br/>'; 

        if($show_img and $img and !empty($img)){ 

           

        }else{

            $show_img = true;

        }

		echo '<div class="msg-detail">
			<div class="msg-title-error">Error</div>
			<div class="msg-description-error">'.stripslashes($error).'</div>
		  </div>';

    }

} ?>
</div>
<div style="clear:both;"></div>
<?php } ?>
