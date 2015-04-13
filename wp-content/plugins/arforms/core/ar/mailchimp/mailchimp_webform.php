<?php
	$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "arf_autoresponder WHERE responder_id='1'");$res = $res[0];
	
	$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."arf_ar WHERE frm_id = %d", $fid), 'ARRAY_A' );
			
   	$arr_mailchimp 	= unserialize( $data[0]['mailchimp'] );
	
	$responder_api_key = @stripslashes( stripslashes_deep( str_replace( '/amp;/', '&', $arr_mailchimp['type_val'] ) ) );
		
	$formdata = str_replace('"',"'",$responder_api_key);
	
	$formdata_hiddn = explode("type='hidden'",$formdata);
	$count_formdata_hiddn = count($formdata_hiddn);
	
	$fields = array();
	for($i=1;$i<count($formdata_hiddn);$i++)
	{
		$findhiddn_name = strpos($formdata_hiddn[$i],"/>");
		$formdata_h=trim(substr($formdata_hiddn[$i],0,$findhiddn_name));
		
		$formdata_h_name = explode("name='",$formdata_h);
		$formdata_h_name_f = strpos($formdata_h_name[1],"'");
		$formdata_h_name_f1 = substr($formdata_h_name[1], 0, $formdata_h_name_f);
		
		$formdata_h_value = explode("value='",$formdata_h);
		$formdata_h_value_f = strpos($formdata_h_value[1],"'");
		$formdata_h_value_f1 = substr($formdata_h_value[1], 0, $formdata_h_value_f);
		$fields[$formdata_h_name_f1] = $formdata_h_value_f1;
	}

	$formdata_n = explode("name='",$formdata);
	$count_formdata_n = count($formdata_n);
	
	for($i=1;$i<$count_formdata_n;$i++)
	{
		$findvarname = strpos($formdata_n[$i],"'");
		$formdata_n[$i] = substr($formdata_n[$i], 0, $findvarname);
	}
	
	if(in_array("EMAIL",$formdata_n))
	{
		$fields["EMAIL"] = $email;
	}
	if(in_array("FNAME",$formdata_n))
	{
		$fields["FNAME"] = $fname;
	}
	if(in_array("LNAME",$formdata_n))
	{
		$fields["LNAME"] = $lname;
	}
	
	$get_urlexp = @explode("action='",$formdata);	
	$get_url_pos = @strpos($get_urlexp[1],"'");		
	
	$url = @substr($get_urlexp[1],0,$get_url_pos);	
	
	if( false === strpos($url, 'http:') )
		$url = "http:".$url;
			
	$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'sslverify' => false,
			'headers' => array(),
			'body' => $fields,
			'cookies' => array()
			)
		);
?>