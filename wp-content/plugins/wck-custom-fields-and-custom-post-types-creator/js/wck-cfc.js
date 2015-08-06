jQuery(function(){
	


	jQuery(document).on( 'change', '#wck_cfc_fields #field-type', function () {
		value = jQuery(this).val();
		
		if( value == 'select' || value == 'checkbox' || value == 'radio' ){
			jQuery( '#wck_cfc_fields .row-options' ).show();
		}
		else{
			jQuery( '#wck_cfc_fields .row-options' ).hide();
		}
		
		if( value == 'upload' ){
			jQuery( '#wck_cfc_fields .row-attach-upload-to-post' ).show();
		}
		else{
			jQuery( '#wck_cfc_fields .row-attach-upload-to-post' ).hide();
		}
		
		if( value == 'cpt select' ){
			jQuery( '#wck_cfc_fields .row-cpt' ).show();
		}
		else{
			jQuery( '#wck_cfc_fields .row-cpt' ).hide();
		}	});
	
	jQuery(document).on( 'change', '#container_wck_cfc_fields #field-type', function () {
		value = jQuery(this).val();
		if( value == 'select' || value == 'checkbox' || value == 'radio' ){
			jQuery(this).parent().parent().parent().children(".row-options").show();
		}
		else{
			jQuery(this).parent().parent().parent().children(".row-options").hide();
		}
		
		if( value == 'upload' ){
			jQuery(this).parent().parent().parent().children(".row-attach-upload-to-post").show();
		}
		else{
			jQuery(this).parent().parent().parent().children(".row-attach-upload-to-post").hide();
		}

		if( value == 'cpt select' ){
			jQuery(this).parent().parent().parent().children(".row-cpt").show();
		}
		else{
			jQuery(this).parent().parent().parent().children(".row-cpt").hide();
		}		
		
	});
});