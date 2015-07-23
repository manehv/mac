/*
filedrag.js - HTML5 File Drag & Drop demonstration
Featured on SitePoint.com
Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
*/
(function() {

	// getElementById
	function $id(id) {
		return document.getElementById(id);
	}


	// output information
	function Output(msg) {
		var m = $id("messages");
		//m.innerHTML = msg + m.innerHTML;
	}



	// file selection
	function FileSelectHandler2(e) {
		
		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;

		// process all File objects
		for (var i = 0, f; f = files[i]; i++) {
			
			var field_key = jQuery(this).attr('data-val');
			
			UploadFile(f, field_key);
		}

	}


	// upload JPEG files
	function UploadFile(file, field_key) {		

		if(file.type.indexOf('image') < 0)
		{
			alert("Please upload image files only");
			return false;
		}
		
		if(field_key == 'form_bg') {			
			jQuery('#ajax_form_loader').show();
		} else if(field_key == 'submit_hover_bg') {
			jQuery('#ajax_submit_hover_loader').show();
		} else {
			jQuery('#ajax_submit_loader').show();
		}


		if (location.host.indexOf("sitepointstatic") >= 0) return

		var xhr = new XMLHttpRequest();
			
			var seconds = new Date().getTime();
			
			var index = file.name.lastIndexOf('.');// .xml

			var extension = file.name.substring(index+1); // xml
			
			var filename = /.*(?=\.)/.exec(file.name);
			
			var fname1 = seconds+"_"+filename;
			
			fname = fname1.replace(/[^\w\s]/gi, '').replace(/ /g,"")+'.'+extension;
			
			var arfmainformurl = jQuery('#arfmainformurl').val();
			
			xhr.open("POST", arfmainformurl+"/js/filedrag/upload.php", true);
			
			xhr.setRequestHeader("X_FILENAME", fname);
			xhr.setRequestHeader("X-FILENAME", fname);
			xhr.send(file);
			
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState == 4) {
					if(xhr.status == 200)
					{
						var data = xhr.responseText;
						
						if(field_key == 'form_bg' && field_key !='undefined') {
							document.getElementById('imagename_form').value = data;
							change_form_bg_img();	
						} else if(field_key == 'submit_hover_bg' && field_key != 'undefined') { 
							document.getElementById('imagename_submit_hover').value = data;
							change_submit_hover_img();							
						}  else {
							document.getElementById('imagename').value = data;
							change_submit_img();							
						}
						
						
					}
				}
			};

	}


	// initialize
	function Init() {

		
		
		var fileselect = $id("field_w4ge3s");
			

		// file select
		fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {

			
		}

	}

	// call initialization file
	if (window.File && window.FileList && window.FileReader) {
		//Init();
	}
	
	jQuery('.original').click(function(){
					
		var fileselect = $id(jQuery(this).attr('id'));	
		fileselect.addEventListener("change", FileSelectHandler2, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {
		}							   
   });
	
	
	// upload using iframe 
	jQuery('.iframe_original_btn').click(function(e){ 
		var fieldmainname_list = jQuery('#arfmainform_bg_img').val();
		if(fieldmainname_list!='')
		{
			return false;
		}
		var field_key = jQuery(this).attr('data-id').replace('div_', '');
		jQuery('#'+field_key+'_iframe').contents().find("#fileselect").click();
		
		var field_name = jQuery(this).attr('data-id');
		
		jQuery('#arf_field_'+field_name+'_container').find('.help-block').empty();
		
		jQuery('#progress_'+field_key).hide();
					
		var result = jQuery('#'+field_key+'_iframe').contents().find("#fileselect").val();						
					
		if ( result !== undefined && result !='' ) {
								
			var field_type = jQuery('#file_types_'+field_key).val();
			
			types_arr = field_type.split(",");
			
				var field_name = jQuery(this).attr('data-id');
				
				var file_name = result.replace(/C:\\fakepath\\/i, '');
				var frmid = jQuery(this).attr('form-id');
				var seconds = new Date().getTime();
				var index = file_name.lastIndexOf('.');// .xml
	
				var extension = file_name.substring(index+1); // xml
				var filename = /.*(?=\.)/.exec(file_name);
				var fname1 = seconds+"_"+filename;
				var fname = fname1.replace(/[^\w\s]/gi, '').replace(' ','')+'.'+extension;
				var field_id = field_key;
				var is_preview = 0;
				var arfmainformurl = jQuery('#arfmainformurl').val();
				var file_type = '';
					
				var ie_version = 9;
				var browser = "Internet Explorer";
				
				
				if(field_key == 'arfmfbi') {			
					jQuery('#ajax_form_loader').show();
				} else
					jQuery('#ajax_submit_loader').show();
			
					jQuery('#'+field_key+'_iframe').contents().find('form').attr('action', arfmainformurl+"/js/filedrag/upload.php?frm="+frmid+"&field_id="+field_id+"&fname="+fname+"&file_type="+file_type+"&types_arr="+types_arr+"&is_preview="+is_preview+"&ie_version="+ie_version+"&browser="+browser);
					
					jQuery('#'+field_key+'_iframe').contents().find('form').submit();
					
					jQuery('#div_'+field_key).css('margin-top',"-4px");	
					jQuery('#info_'+field_key).css('display', 'inline-block');
					jQuery('#info_'+field_key+' #file_name').html(result.replace(/C:\\fakepath\\/i, ''));
					jQuery('#info_'+field_key+' .percent').html('').show();
					jQuery('#info_'+field_key+' #percent').html('Uploading...');
					jQuery('#progress_'+field_key+' div.bar').animate({ width: "100%" }, 'slow');
					
					var myInterval = setInterval(function(){
						
						if( jQuery('#'+field_key+'_iframe').contents() )								  
						{
							var uploaded = ( jQuery('#'+field_key+'_iframe').contents() ) ? jQuery('#'+field_key+'_iframe').contents().find('.uploaded').length : 0;
							if(uploaded > 0){
								clearInterval(myInterval);
								jQuery('#progress_'+field_key).removeClass('active');
								jQuery('#imagename_form').val(fname);
								jQuery('#form_bg_img_div').css('background','none');
								jQuery('#form_bg_img_div').css('border','0px');
								jQuery('#form_bg_img_div').css('padding','0px');
								jQuery('#form_bg_img_div').css('box-shadow','none');
								
								change_form_bg_img();
								
								jQuery('#div_'+field_key).hide();
								jQuery('#remove_'+field_key).css('display', 'block');
								jQuery('#div_'+field_key).css('margin-top',"0px");
								jQuery('#remove_'+field_key).css('margin-top',"-4px");
								
								jQuery('#'+field_key+'_iframe_div').html(' ').append('<iframe id="'+field_key+'_iframe" src="'+arfmainformurl+'/core/views/iframe.php"></iframe>');
								
							}
							
							var error_upload = ( jQuery('#'+field_key+'_iframe').contents() ) ? jQuery('#'+field_key+'_iframe').contents().find('.error_upload').length : 0;
							
							if(error_upload > 0){
								clearInterval(myInterval);
								var field_id 	= field_name;
								
								jQuery('input[name="file'+field_id+'"]').attr('file-valid', 'false');  // set false							
								
								//for error display
								if(typeof(__ARFERR)!='undefined')
									var file_error = __ARFERR;
								else
									var file_error = 'Sorry, this file type is not permitted for security reasons.';
								
								if( jQuery('#field_'+field_key).attr('data-invalid-message') !== undefined && jQuery('#field_'+field_key).attr('data-invalid-message') !='' )
									var arf_invalid_file_message = jQuery('#field_'+field_key).attr('data-invalid-message');
								else
									var arf_invalid_file_message = file_error;
					
								jQuery('#arf_field_'+field_id+'_container').removeClass('success');
								var $this = jQuery('#arf_field_'+field_id+'_container .controls');
								var	$controlGroup = $this.parents(".control-group").first();
								var	$helpBlock = $controlGroup.find(".help-block").first();	
								
								var form_id = $this.closest('form').find('#form_id').val();					
								var error_type =  ( jQuery('#form_tooltip_error_'+form_id).val() == 'advance' ) ? 'advance' : 'normal';
									
								if( error_type == 'advance' )
								{
									arf_show_tooltip($controlGroup, $helpBlock, arf_invalid_file_message);
								} else {
								
								  if (!$helpBlock.length) {
									  $helpBlock = jQuery('<div class="help-block"><ul><li>'+arf_invalid_file_message+'</li></ul></div>');
									  $controlGroup.find('.controls').append($helpBlock);
								  }
								  else
								  {
									  $helpBlock = jQuery('<ul><li>'+arf_invalid_file_message+'</li></ul>');
									  $controlGroup.find('.controls .help-block').append($helpBlock);
								  }
								}
								  
								//for error display end
								jQuery('#'+field_key+'_iframe_div').html(' ').append('<iframe id="'+field_key+'_iframe" src="'+arfmainformurl+'/core/views/iframe.php"></iframe>');
							}
						
						}
						
					},100);
					
		}
		
						
	});	
	
	
	
	
	// submit btn img upload using iframe 
	jQuery('.iframe_submit_original_btn').click(function(e){ 
		var fieldmainname_list = jQuery('#arfsubmitbuttonimagesetting').val();
		if(fieldmainname_list!='')
		{
			return false;
		}
		var field_key = jQuery(this).attr('data-id').replace('div_', '');
		jQuery('#'+field_key+'_iframe').contents().find("#fileselect").click();
		
		var field_name = jQuery(this).attr('data-id');
		
		jQuery('#arf_field_'+field_name+'_container').find('.help-block').empty();
						
		var result = jQuery('#'+field_key+'_iframe').contents().find("#fileselect").val();	
					
		if ( result !='' && result !== undefined ) {
								
			var field_type = jQuery('#file_types_'+field_key).val();
			
			types_arr = field_type.split(",");
			
				var field_name = jQuery(this).attr('data-id');
				
				var file_name = result.replace(/C:\\fakepath\\/i, '');
				var frmid = jQuery(this).attr('form-id');
				var seconds = new Date().getTime();
				var index = file_name.lastIndexOf('.');// .xml
	
				var extension = file_name.substring(index+1); // xml
				var filename = /.*(?=\.)/.exec(file_name);
				var fname1 = seconds+"_"+filename;
				var fname = fname1.replace(/[^\w\s]/gi, '').replace(' ','')+'.'+extension;
				var field_id = field_key;
				var is_preview = 0;
				var arfmainformurl = jQuery('#arfmainformurl').val();
				var file_type = '';
				var ie_version = 9;
				var browser = "Internet Explorer";
				
					jQuery('#'+field_key+'_iframe').contents().find('form').attr('action', arfmainformurl+"/js/filedrag/upload.php?frm="+frmid+"&field_id="+field_id+"&fname="+fname+"&file_type="+file_type+"&types_arr="+types_arr+"&is_preview="+is_preview+"&ie_version="+ie_version+"&browser="+browser);
					
					jQuery('#'+field_key+'_iframe').contents().find('form').submit();
					
					jQuery('#div_'+field_key).css('margin-top',"-4px");	
					//jQuery('#progress_'+field_key).show();
					jQuery('#info_'+field_key).css('display', 'inline-block');
					jQuery('#info_'+field_key+' #file_name').html(result.replace(/C:\\fakepath\\/i, ''));
					jQuery('#info_'+field_key+' .percent').html('').show();
					jQuery('#info_'+field_key+' #percent').html('Uploading...');
					jQuery('#progress_'+field_key+' div.bar').animate({ width: "100%" }, 'slow');
					
					var myInterval = setInterval(function(){
						if(jQuery('#'+field_key+'_iframe').contents())								  
						{	
							//jQuery('#'+field_key+'_iframe').load(function() {
							var uploaded = jQuery('#'+field_key+'_iframe').contents().find('.uploaded').length;
							if(uploaded > 0){
								clearInterval(myInterval);
								jQuery('#progress_'+field_key).removeClass('active');
								jQuery('#imagename').val(fname);
								jQuery('#submit_btn_img_div').css('background','none');
								jQuery('#submit_btn_img_div').css('border','0px');
								jQuery('#submit_btn_img_div').css('padding','0px');
								jQuery('#submit_btn_img_div').css('box-shadow','none');
								
								change_submit_img();
								
								jQuery('#div_'+field_key).hide();
								jQuery('#remove_'+field_key).css('display', 'block');
								jQuery('#div_'+field_key).css('margin-top',"0px");
								jQuery('#remove_'+field_key).css('margin-top',"-4px");
								
								jQuery('#info_'+field_key+' #percent').html('File Uploaded');
								jQuery('input[name="file'+field_name+'"]').val(filename);
								jQuery('input[name="item_meta['+field_name+']"]').val(filename);
								
								jQuery('input[name="file'+field_name+'"]').attr('file-valid', 'true');  // set true
								
								//var data = jQuery('#'+field_key+'_iframe').contents().find('.uploaded').html();
								//var response = data.split("|");
								
								jQuery('#'+field_key+'_iframe_div').html(' ').append('<iframe id="'+field_key+'_iframe" src="'+arfmainformurl+'/core/views/iframe.php"></iframe>');
								
							}
							
							var error_upload = jQuery('#'+field_key+'_iframe').contents().find('.error_upload').length;
							if(error_upload > 0){
								clearInterval(myInterval);
								var field_id 	= field_name;
								
								jQuery('input[name="file'+field_id+'"]').attr('file-valid', 'false');  // set false							
								
								if(typeof(__ARFERR)!='undefined')
									var file_error = __ARFERR;
								else
									var file_error = 'Sorry, this file type is not permitted for security reasons.';
								
								if( jQuery('#field_'+field_key).attr('data-invalid-message') !== undefined && jQuery('#field_'+field_key).attr('data-invalid-message') !='' )
									var arf_invalid_file_message = jQuery('#field_'+field_key).attr('data-invalid-message');
								else
									var arf_invalid_file_message = file_error;
					
								jQuery('#arf_field_'+field_id+'_container').removeClass('success');
								var $this = jQuery('#arf_field_'+field_id+'_container .controls');
								var	$controlGroup = $this.parents(".control-group").first();
								var	$helpBlock = $controlGroup.find(".help-block").first();	
								
								
								var form_id = $this.closest('form').find('#form_id').val();					
								var error_type =  ( jQuery('#form_tooltip_error_'+form_id).val() == 'advance' ) ? 'advance' : 'normal';
									
								if( error_type == 'advance' )
								{
									arf_show_tooltip($controlGroup, $helpBlock, arf_invalid_file_message);
								} else {
								  if (!$helpBlock.length) {
									  $helpBlock = jQuery('<div class="help-block"><ul><li>'+arf_invalid_file_message+'</li></ul></div>');
									  $controlGroup.find('.controls').append($helpBlock);
								  }
								  else
								  {
									  $helpBlock = jQuery('<ul><li>'+arf_invalid_file_message+'</li></ul>');
									  $controlGroup.find('.controls .help-block').append($helpBlock);
								  }
								}
								  
								//for error display end
								
								jQuery('#'+field_key+'_iframe_div').html(' ').append('<iframe id="'+field_key+'_iframe" src="'+arfmainformurl+'/core/views/iframe.php"></iframe>');
							}
							//});
						}
					},100);
		}
	});	
	
	
	
	// submit btn img upload using iframe 
	jQuery('.iframe_submit_hover_original_btn').click(function(e){ 
		var fieldmainname_list = jQuery('#arfsubmithoverbuttonimagesetting').val();
		if(fieldmainname_list!='')
		{
			return false;
		}
		var field_key = jQuery(this).attr('data-id').replace('div_', '');
		jQuery('#'+field_key+'_iframe').contents().find("#fileselect").click();
		
		var field_name = jQuery(this).attr('data-id');
		
		jQuery('#arf_field_'+field_name+'_container').find('.help-block').empty();
						
		var result = jQuery('#'+field_key+'_iframe').contents().find("#fileselect").val();	
					
		if ( result !='' && result !== undefined ) {
								
			var field_type = jQuery('#file_types_'+field_key).val();
			
			types_arr = field_type.split(",");
			
				var field_name = jQuery(this).attr('data-id');
				
				var file_name = result.replace(/C:\\fakepath\\/i, '');
				var frmid = jQuery(this).attr('form-id');
				var seconds = new Date().getTime();
				var index = file_name.lastIndexOf('.');// .xml
	
				var extension = file_name.substring(index+1); // xml
				var filename = /.*(?=\.)/.exec(file_name);
				var fname1 = seconds+"_"+filename;
				var fname = fname1.replace(/[^\w\s]/gi, '').replace(' ','')+'.'+extension;
				var field_id = field_key;
				var is_preview = 0;
				var arfmainformurl = jQuery('#arfmainformurl').val();
				var file_type = '';
				var ie_version = 9;
				var browser = "Internet Explorer";
				
					jQuery('#'+field_key+'_iframe').contents().find('form').attr('action', arfmainformurl+"/js/filedrag/upload.php?frm="+frmid+"&field_id="+field_id+"&fname="+fname+"&file_type="+file_type+"&types_arr="+types_arr+"&is_preview="+is_preview+"&ie_version="+ie_version+"&browser="+browser);
					
					jQuery('#'+field_key+'_iframe').contents().find('form').submit();
					
					jQuery('#div_'+field_key).css('margin-top',"-4px");	
					//jQuery('#progress_'+field_key).show();
					jQuery('#info_'+field_key).css('display', 'inline-block');
					jQuery('#info_'+field_key+' #file_name').html(result.replace(/C:\\fakepath\\/i, ''));
					jQuery('#info_'+field_key+' .percent').html('').show();
					jQuery('#info_'+field_key+' #percent').html('Uploading...');
					jQuery('#progress_'+field_key+' div.bar').animate({ width: "100%" }, 'slow');
					
					var myInterval = setInterval(function(){
						if(jQuery('#'+field_key+'_iframe').contents())								  
						{	
							//jQuery('#'+field_key+'_iframe').load(function() {
							var uploaded = jQuery('#'+field_key+'_iframe').contents().find('.uploaded').length;
							if(uploaded > 0){
								clearInterval(myInterval);
								jQuery('#progress_'+field_key).removeClass('active');
								jQuery('#imagename').val(fname);
								jQuery('#submit_hover_btn_img_div').css('background','none');
								jQuery('#submit_hover_btn_img_div').css('border','0px');
								jQuery('#submit_hover_btn_img_div').css('padding','0px');
								jQuery('#submit_hover_btn_img_div').css('box-shadow','none');
								
								change_submit_hover_img();
								
								jQuery('#div_'+field_key).hide();
								jQuery('#remove_'+field_key).css('display', 'block');
								jQuery('#div_'+field_key).css('margin-top',"0px");
								jQuery('#remove_'+field_key).css('margin-top',"-4px");
								
								jQuery('#info_'+field_key+' #percent').html('File Uploaded');
								jQuery('input[name="file'+field_name+'"]').val(filename);
								jQuery('input[name="item_meta['+field_name+']"]').val(filename);
								
								jQuery('input[name="file'+field_name+'"]').attr('file-valid', 'true');  // set true
								
								//var data = jQuery('#'+field_key+'_iframe').contents().find('.uploaded').html();
								//var response = data.split("|");
								
								jQuery('#'+field_key+'_iframe_div').html(' ').append('<iframe id="'+field_key+'_iframe" src="'+arfmainformurl+'/core/views/iframe.php"></iframe>');
								
							}
							
							var error_upload = jQuery('#'+field_key+'_iframe').contents().find('.error_upload').length;
							if(error_upload > 0){
								clearInterval(myInterval);
								var field_id 	= field_name;
								
								jQuery('input[name="file'+field_id+'"]').attr('file-valid', 'false');  // set false							
								
								if(typeof(__ARFERR)!='undefined')
									var file_error = __ARFERR;
								else
									var file_error = 'Sorry, this file type is not permitted for security reasons.';
								
								if( jQuery('#field_'+field_key).attr('data-invalid-message') !== undefined && jQuery('#field_'+field_key).attr('data-invalid-message') !='' )
									var arf_invalid_file_message = jQuery('#field_'+field_key).attr('data-invalid-message');
								else
									var arf_invalid_file_message = file_error;
					
								jQuery('#arf_field_'+field_id+'_container').removeClass('success');
								var $this = jQuery('#arf_field_'+field_id+'_container .controls');
								var	$controlGroup = $this.parents(".control-group").first();
								var	$helpBlock = $controlGroup.find(".help-block").first();	
								
								
								var form_id = $this.closest('form').find('#form_id').val();					
								var error_type =  ( jQuery('#form_tooltip_error_'+form_id).val() == 'advance' ) ? 'advance' : 'normal';
									
								if( error_type == 'advance' )
								{
									arf_show_tooltip($controlGroup, $helpBlock, arf_invalid_file_message);
								} else {
								  if (!$helpBlock.length) {
									  $helpBlock = jQuery('<div class="help-block"><ul><li>'+arf_invalid_file_message+'</li></ul></div>');
									  $controlGroup.find('.controls').append($helpBlock);
								  }
								  else
								  {
									  $helpBlock = jQuery('<ul><li>'+arf_invalid_file_message+'</li></ul>');
									  $controlGroup.find('.controls .help-block').append($helpBlock);
								  }
								}
								  
								//for error display end
								
								jQuery('#'+field_key+'_iframe_div').html(' ').append('<iframe id="'+field_key+'_iframe" src="'+arfmainformurl+'/core/views/iframe.php"></iframe>');
							}
							//});
						}
					},100);
					
		
		}
		
						
	});	
	
	

})();