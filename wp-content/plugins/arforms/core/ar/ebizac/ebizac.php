<?php
	$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "arf_autoresponder WHERE responder_id='6'");$res = $res[0];

	$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = %d", $fid), 'ARRAY_A' );
			
   	$arr_ebizac 	= unserialize( $data[0]['ebizac'] );
	
	$responder_api_key = @stripslashes( stripslashes_deep( $arr_ebizac['type_val'] ) );

	$formdata = str_replace('"',"'",$responder_api_key);

	$formdata_n = explode("name='",$formdata);

	$count_formdata_n = count($formdata_n);

	$j=0;

	$fields = array();

	for($i=2;$i<$count_formdata_n;$i++)

	{

		$findvarname = strpos($formdata_n[$i],"type=");

		$formdata_n_both = trim(substr($formdata_n[$i], 0, $findvarname));

		

		$formdata_n_name = strpos($formdata_n_both,"'");

		$formdata_n_name_f = substr($formdata_n_both,0,$formdata_n_name);

		

		$formdata_n_value_ex = @explode("value='",$formdata_n_both);

		$formdata_n_value = @str_replace("'","",$formdata_n_value_ex[1]);

		

		if($formdata_n_name_f=="email")

		{

			$formdata_n_value = $email;

		}elseif($formdata_n_name_f=="fname" || $formdata_n_name_f=="lname" || $formdata_n_name_f=="fullname")

		{

			$formdata_n_value = $fname." ".$lname;

		}

		$fields[$formdata_n_name_f] = $formdata_n_value;



	}
		
	$get_urlexp = @explode("action='",$formdata);
	$get_url_pos = @strpos($get_urlexp[1],"'");	
	
	$url = @substr($get_urlexp[1],0,$get_url_pos);
		
	$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'sslverify' => false,
			'body' => $fields
			)
		);

?>