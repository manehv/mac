<?php

 
class arstylemodel{


    var $arfmainformwidth;
	var $form_width_unit;

    var $edit_msg;


    var $update_value;


    var $arfeditoroff;


    var $arfmaintemplatepath;


    var $csv_format;


    


    var $date_format;


    var $cal_date_format;


    


    var $arfcalthemecss;


    var $arfcalthemename;


    var $theme_nicename;


    


    var $permalinks;


    


    var $form_align;


    var $fieldset;


    var $arfmainfieldsetcolor;


    var $arfmainfieldsetpadding;

	var $arfmainfieldsetradius;
    


    var $font;

    var $font_other;

    var $font_size;


    var $label_color;


    var $weight;


    var $position;
	
	var $hide_labels;

    var $align;


    var $width;
	
	var $width_unit;

    var $arfdescfontsetting;


    var $arfdescfontsizesetting;


    var $arfdesccolorsetting;


    var $arfdescweightsetting;


    var $description_style;


    var $arfdescalighsetting;


    


    var $field_font_size;


    var $field_width;
	
	var $field_width_unit;


    var $auto_width;


    var $arffieldpaddingsetting;


    var $arffieldmarginssetting;


    


    var $bg_color;


    var $text_color;


    var $border_color;


    var $arffieldborderwidthsetting;


    var $arffieldborderstylesetting;


    var $arfbgactivecolorsetting;


    var $arfborderactivecolorsetting;


    


    var $arferrorbgcolorsetting;




    var $arferrorbordercolorsetting;


    var $arferrorborderwidthsetting;


    var $arferrorborderstylesetting;


    


    var $arfradioalignsetting;


    var $arfcheckboxalignsetting;


    var $check_font;

	var $check_font_other;

    var $arfcheckboxfontsizesetting;


    var $arfcheckboxlabelcolorsetting;


    var $check_weight;


    


    var $arfsubmitbuttonstylesetting;


    var $arfsubmitbuttonfontsizesetting;


    var $arfsubmitbuttonwidthsetting;


    var $arfsubmitbuttonheightsetting;


    var $submit_bg_color;

	var $arfsubmitbuttonbgcolorhoversetting;

    var $arfsubmitbgcolor2setting;


    var $arfsubmitbordercolorsetting;


    var $arfsubmitborderwidthsetting;


    var $arfsubmittextcolorsetting;


    var $arfsubmitweightsetting;


    var $arfsubmitborderradiussetting;


    var $submit_bg_img;

	var $submit_hover_bg_img;
	
    var $arfsubmitbuttonmarginsetting;


    var $arfsubmitbuttonpaddingsetting;


    var $arfsubmitshadowcolorsetting;


    


    var $border_radius;


    


    var $arferroriconsetting;


    var $arferrorbgsetting;


    var $arferrorbordersetting;


    var $arferrortextsetting;


    var $arffontsizesetting;


    
	var $arfsucessiconsetting;


    var $success_bg;


    var $success_border;


    var $success_text;


    var $arfsucessfontsizesetting;

	
	
	var $arftextareafontsizesetting;

	
	var $arftextareawidthsetting;
	
	var $arftextareawidthunitsetting;
	
	
	var $arftextareapaddingsetting;
	
	
	var $arftextareamarginsetting;
	
	
	var $arftextareabgcolorsetting;
	
	
	var $arftextareacolorsetting;
	
	
	var $arftextareabordercolorsetting;
	
	
	var $arftextareaborderwidthsetting;
	
	
	var $arftextareaborderstylesetting;
		
	var $text_direction;
	
	var $arffieldheightsetting;
		
	var $arfmainformtitlecolorsetting;
	
	var $form_title_font_size;
	
	var $error_font;
	
	var $error_font_other;
	
	

	var $arfactivebgcolorsetting;

	var $arfmainformbgcolorsetting;
	
	var $arfmainformtitleweightsetting;
	
	var $arfmainformtitlepaddingsetting;
	
	
	var $arfmainformbordershadowcolorsetting;
	
	var $form_border_shadow;
	
	var $arfsubmitalignsetting;
	
	var $checkbox_radio_style;
	
	var $bg_color_pg_break;
	
	var $bg_inavtive_color_pg_break;

	var $text_color_pg_break;
	
	var $arfmainform_bg_img;		
			
	var $arfmainform_opacity;		
	var $arfmainfield_opacity;
	
	var $arfsubmitfontfamily;
	
	var $arfmainfieldsetpadding_1;
	var $arfmainfieldsetpadding_2;
	var $arfmainfieldsetpadding_3;
	var $arfmainfieldsetpadding_4;
	
	var $arfmainformtitlepaddingsetting_1;
	var $arfmainformtitlepaddingsetting_2;
	var $arfmainformtitlepaddingsetting_3;
	var $arfmainformtitlepaddingsetting_4;
	
	var $arffieldinnermarginssetting_1;
	var $arffieldinnermarginssetting_2;
	var $arffieldinnermarginssetting_3;
	var $arffieldinnermarginssetting_4;
	
	var $arfsubmitbuttonmarginsetting_1;
	var $arfsubmitbuttonmarginsetting_2;
	var $arfsubmitbuttonmarginsetting_3;
	var $arfsubmitbuttonmarginsetting_4;
	
	var $arfcheckradiostyle;
	var $arfcheckradiocolor;
	
	var $arfformtitlealign;
	
	var $arferrorstyle;
	
	var $arferrorstylecolor;
	
	var $arferrorstylecolor2;
	
	var $arferrorstyleposition;
	
	var $arfsubmitautowidth;
	
	var $arftitlefontfamily;
	
	var $bar_color_survey;
	
	var $bg_color_survey;
	
	var $text_color_survey;
	
	var $prefix_suffix_bg_color;
	
	var $prefix_suffix_icon_color;
	var $arfsectionpaddingsetting_1;
	
	var $arfsectionpaddingsetting_2;
	
	var $arfsectionpaddingsetting_3;
	
	var $arfsectionpaddingsetting_4;
	
    function arstylemodel(){


        $this->set_default_options();


    }


    


    function default_options(){


        return array(



            'arfmaintemplatepath' => '',


            'arfeditoroff'   => false,


            'csv_format' => 'UTF-8',


            'arfcalthemecss' => 'default_theme', 


            'arfcalthemename' => 'default_theme_jquery-ui', 



            'arfmainformwidth' => '100',
			
			'form_width_unit' => '%',

            'form_align' => 'left', 


            'fieldset' => '2', 


            'arfmainfieldsetcolor' => 'd9d9d9',


            'arfmainfieldsetpadding' => '30px 45px 30px 45px',

			'arfmainfieldsetradius' => '6',
            


            'font' => 'Helvetica', 
			
			'font_other' => '',

            'font_size' => '16', 


            'label_color' => '706d70',


            'weight' => 'normal',


            'position' => 'top',

			'hide_labels' => false,
			
            'align' => 'left',


            'width' => '130',
			
			'width_unit' => 'px',

            'arfdescfontsetting' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',


            'arfdescfontsizesetting' => '12',


            'arfdesccolorsetting' => '666666',


            'arfdescweightsetting' => 'normal',


            'description_style' => 'normal',


            'arfdescalighsetting' => 'right',


            


            'field_font_size' => '14',


            'field_width' => '100',
			
			
			'field_width_unit' => '%',


            'auto_width' => false,


            'arffieldpaddingsetting' => '2',


            'arffieldmarginssetting' => '23',
			
			'arffieldinnermarginssetting' => '8px 10px 8px 10px',


            'text_color' => '17181c',


            'border_color' => 'b0b0b5',


            'arffieldborderwidthsetting' => '1',


            'arffieldborderstylesetting' => 'solid',


            


            'bg_color' => 'ffffff',


            'arfbgactivecolorsetting' => 'ffffff',


            'arfborderactivecolorsetting' => '087ee2',


            'arferrorbgcolorsetting' => 'ffffff',


            'arferrorbordercolorsetting' => 'ed4040',


            'arferrorborderwidthsetting' => '1',


            'arferrorborderstylesetting' => 'solid',


            


            'arfradioalignsetting' => 'inline',


            'arfcheckboxalignsetting' => 'block',


            'check_font' => 'Helvetica',
			
			'check_font_other' => '',

            'arfcheckboxfontsizesetting' => '12px',


            'arfcheckboxlabelcolorsetting' => '444444',


            'check_weight' => 'normal',


            


            'arfsubmitbuttonstylesetting' => false,


            'arfsubmitbuttonfontsizesetting' => '18',


            'arfsubmitbuttonwidthsetting' => '',


            'arfsubmitbuttonheightsetting' => '38',


            'submit_bg_color' => '077BDD',

			'arfsubmitbuttonbgcolorhoversetting' => '0b68b7',
			
            'arfsubmitbgcolor2setting' => '',


            'arfsubmitbordercolorsetting' => 'f6f6f8',


            'arfsubmitborderwidthsetting' => '0',


            'arfsubmittextcolorsetting' => 'ffffff',


            'arfsubmitweightsetting' => 'bold',


            'arfsubmitborderradiussetting' => '3',


            'submit_bg_img' => '',
			
			'submit_hover_bg_img' => '',


            'arfsubmitbuttonmarginsetting' => '10px 10px 0px 0px',


            'arfsubmitbuttonpaddingsetting' => '8',


            'arfsubmitshadowcolorsetting' => 'c6c8cc',

			'arfsubmitalignsetting' => 'fixed',
            


            'border_radius' => '3',


            'arferroriconsetting' => 'e1.png',


            'arferrorbgsetting' => 'F3CAC7',


            'arferrorbordersetting' => 'FA8B83',


            'arferrortextsetting' => '501411',


            'arffontsizesetting' => '14',


            
			'arfsucessiconsetting' => 's1.png',
			

            'arfsucessbgcolorsetting' => 'D8F7CF',


            'arfsucessbordercolorsetting' => '8CCF7A',


            'arfsucesstextcolorsetting' => '3B3B3B',


            'arfsucessfontsizesetting' => '14',
			
			
			'arftextareafontsizesetting' => '13px',
			
			
			'arftextareawidthsetting' => '400',
			
			'arftextareawidthunitsetting' => 'px',
			
			'arftextareapaddingsetting' => '2',
			
			
			'arftextareamarginsetting' => '20',
			
			
			'arftextareabgcolorsetting' => 'ffffff',
			
			
			'arftextareacolorsetting' => '444444',
			
			
			'arftextareabordercolorsetting' => 'dddddd',
			
			
			'arftextareaborderwidthsetting' => '1',
			
			
			'arftextareaborderstylesetting' => 'solid',
			
			
			'arfactivebgcolorsetting' => 'FFFF00',
			
			
			'text_direction' => '1',
			
			
			'arffieldheightsetting' => '24',
							
			
			'arfmainformtitlecolorsetting' => '4a494a',
	
	
			'form_title_font_size' => '28',
			
			
			'error_font' => 'Lucida Sans Unicode',
			


			
			'error_font_other' => '',
			
			
			'arfmainformbgcolorsetting' => 'ffffff',
						
			'arfmainformbordershadowcolorsetting' => 'f2f2f2',
			
			'form_border_shadow' => 'flat',
			
			'arfmainformtitleweightsetting' => 'normal',
			
			
			'arfmainformtitlepaddingsetting' => '0px 0px 20px 0px',
			
			
			'date_format' => 'M d, yy',

			'checkbox_radio_style' => '1',
			
			'bg_color_pg_break' => '087ee2',
			
			'bg_inavtive_color_pg_break' => '7ec3fc',		
		
			'text_color_pg_break' => 'ffffff',
			
			'arfmainform_bg_img' => '',		
			
			'arfmainform_opacity' => '1',		
			'arfmainfield_opacity' => '0',
			
			'arfsubmitfontfamily' => 'Helvetica',
			
			'arfmainfieldsetpadding_1' => '30',
			
			'arfmainfieldsetpadding_2' => '45',
			
			'arfmainfieldsetpadding_3' => '30',
			
			'arfmainfieldsetpadding_4' => '45',
			
			'arfmainformtitlepaddingsetting_1' => '0',
			
			'arfmainformtitlepaddingsetting_2' => '0',
			
			'arfmainformtitlepaddingsetting_3' => '20',
			
			'arfmainformtitlepaddingsetting_4' => '0',
			
			'arffieldinnermarginssetting_1' => '8',
			
			'arffieldinnermarginssetting_2' => '10',
			
			'arffieldinnermarginssetting_3' => '8',
			
			'arffieldinnermarginssetting_4' => '10',
			
			'arfsubmitbuttonmarginsetting_1' => '10',
			
			'arfsubmitbuttonmarginsetting_2' => '10',
			
			'arfsubmitbuttonmarginsetting_3' => '0',
			
			'arfsubmitbuttonmarginsetting_4' => '0',
			
			'arfformtitlealign' => 'left',
			
			'arfcheckradiostyle' => 'flat',
			
			'arfcheckradiocolor' => 'blue',
			
			'arferrorstyle' => 'advance',
			
			'arferrorstylecolor' => '#ed4040|#FFFFFF|#ed4040',
			
			'arferrorstylecolor2' => '#ed4040|#FFFFFF|#ed4040',
			
			'arferrorstyleposition' => 'bottom',
			
			'arfsubmitautowidth' => '100',
			
			'arftitlefontfamily' => 'Helvetica',
			
			'bar_color_survey' 	=> '#007ee4',
	
			'bg_color_survey'	=> '#dadde2',
	
			'text_color_survey'	=> '#333333',
			
			'prefix_suffix_bg_color' => '#e7e8ec',
			
			'prefix_suffix_icon_color' => '#808080',
			
			'arfsectionpaddingsetting_1' => '15',
			
			'arfsectionpaddingsetting_2' => '10',
			
			'arfsectionpaddingsetting_3' => '15',
			
			'arfsectionpaddingsetting_4' => '10',
	
        );


    }





    function set_default_options(){


        $this->edit_msg = __("Your submission was successfully saved.", 'ARForms');


        $this->update_value = __('Update', 'ARForms');


        


        if(!isset($this->date_format))


            $this->date_format = 'M d, yy';		


        if(!isset($this->cal_date_format))


            $this->cal_date_format = 'M d, yy';		


               


        $this->theme_nicename = sanitize_title_with_dashes($this->arfcalthemename);

            
        $this->permalinks = false;


        $settings = $this->default_options();


        


        foreach($settings as $setting => $default){


            if(!isset($this->{$setting})){


                


                if($setting == 'arfmainformwidth' and $settings['field_width'] == '100%')


                    $this->{$setting} = '100%';


                else


                    $this->{$setting} = $default;


            }


        }





        $this->font = stripslashes($this->font);


        $this->arfdescfontsetting = stripslashes($this->arfdescfontsetting);


    }


    function update($params){


        $this->date_format = $params['arffdaf'];


        switch($this->date_format){


            case 'yy/mm/dd':


                $this->cal_date_format = 'yy/mm/dd';


            break;
			
			
			case 'yy, M d':


                $this->cal_date_format = 'yy, M d';


            break;
			
			
			case 'yy, MM d':


                $this->cal_date_format = 'yy, MM d';


            break;


            case 'dd/mm/yy':


                $this->cal_date_format = 'dd/mm/yy';


            break;


            case 'd.m.Y':


                $this->cal_date_format = 'dd.mm.yy';


                break;


            case 'j/m/y':


                $this->cal_date_format = 'd/mm/y';


            break;
			
			
			case 'd MM, yy':
			
				$this->cal_date_format = 'd MM, yy';

            break;
			
			
			case 'd M, yy':
			
				$this->cal_date_format = 'd M, yy';

            break;


            case 'Y-m-d':


                $this->cal_date_format = 'yy-mm-dd';


            break;


            case 'j-m-Y':


                $this->cal_date_format = 'd-mm-yy';


            break;
			
			
			case 'M d, yy':


                $this->cal_date_format = 'M d, yy';


            break;
			
			
			case 'MM d, yy':


                $this->cal_date_format = 'MM d, yy';


            break;


            default:


                $this->cal_date_format = 'mm/dd/yy';


        }


        


        $this->permalinks = isset($params['frm_permalinks']) ? $params['frm_permalinks'] : 0;


        


        $settings = $this->default_options();


        


        foreach($settings as $setting => $default){


            if(isset($params['frm_'.$setting])){


                if(preg_match('/color/', $setting) or in_array($setting, array('arferrorbgsetting', 'arferrorbordersetting', 'arferrortextsetting'))) 

                    $this->{$setting} = str_replace('#', '', $params['frm_'.$setting]); 
                
				else

                    $this->{$setting} = $params['frm_'.$setting];


            }


        }

		
		
		$this->hide_labels = ($params['arfhidelabels']==1) ? $params['arfhidelabels'] : 0;


        $this->arfsubmitbuttonstylesetting = isset($params['arfsubmitbuttonsetting']) ? $params['arfsubmitbuttonsetting'] : 0;


        $this->auto_width = isset($params['arfautowidthsetting']) ? $params['arfautowidthsetting'] : 0;

		
    }





    function store(){
	
		global $arsettingcontroller;



        update_option( 'arfa_options', $this);


        


        delete_transient('arfa_options');


        set_transient('arfa_options', $this);


        
		$cssoptions = get_option("arfa_options");
		
		$new_values = array();
		
		foreach($cssoptions as $k => $v)
			$new_values[$k] = $v;
		

        $arfssl = (is_ssl()) ? 1 : 0;
        
        $filename = FORMPATH .'/core/css_create_main.php';
		

        if (is_file($filename)) {


            $uploads = wp_upload_dir();


            $target_path = $uploads['basedir'];





            wp_mkdir_p($target_path);





            $target_path .= "/arforms";


            wp_mkdir_p($target_path);


            


            if(!file_exists($target_path .'/index.php')){


                WP_Filesystem();
				global $wp_filesystem;
				$index = "<?php\n// Silence is golden.\n?>";
				$wp_filesystem->put_contents( $target_path .'/index.php',$index, 0777);


            }

            $target_path .= "/css";

            wp_mkdir_p($target_path);

            $use_saved = true; 
			
			$form_id = ''; 

            $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";

            $css .= "\n";

            ob_start();

            include $filename;

            $css .= ob_get_contents();

            ob_end_clean();

            $css .= "\n ". $warn;

            $css_file = $target_path .'/arforms.css';


            WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->put_contents( $css_file , $css , 0777);

            update_option('arfa_css', $css);

            delete_transient('arfa_css');


            set_transient('arfa_css', $css);


        }


    }



}


?>