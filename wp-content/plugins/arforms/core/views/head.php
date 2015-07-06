<?php 
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7.3
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/
if (isset($css_file)){ 

    if (is_array($css_file)){
		$i = 1;
        foreach ($css_file as $file) {

			wp_register_style('arfformheadcss-'.$i, $file);
			wp_print_styles('arfformheadcss-'.$i);
			$i++;
		}	
			
    }else{?>

<?php
wp_register_style('arf-formheadcss', $css_file);
wp_print_styles('arf-formheadcss');
?>
<?php } 

}



if (isset($js_file)){ 

    if (is_array($js_file)){
		$i = 1;
        foreach ($js_file as $file) {

			wp_register_script('arf-arformsjs-'.$i, $file);
			wp_print_scripts('arf-arformsjs-'.$i);
			$i++;
 		}
    }else{?>

<?php
wp_register_script('arf-arformsjs', $js_file);
wp_print_scripts('arf-arformsjs');
?>
<?php 

    }

}

?>