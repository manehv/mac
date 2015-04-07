/*
filedrag.js - HTML5 File Drag & Drop demonstration
Featured on SitePoint.com
Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
*/
jQuery(document).ready(function($){
	// getElementById
	function $id(id) {
		return document.getElementById(id);
	}


	// output information
	function Output(msg) {
		var m = $id("messages");
	}


	// file selection
	function FileSelectHandler(e) {

		var files = e.target.files || e.dataTransfer.files;

		
		for (var i = 0, f; f = files[i]; i++) {
			
			var field_key_arr = jQuery(this).attr('id').split('field_');
			
			var field_key = field_key_arr[1];
			
			
			var field_name_arr = jQuery(this).attr('name').split('file');
			var field_name = field_name_arr[1];
			jQuery('input[name="item_meta['+field_name+']"]').val(f.name);
						
			
			var field_type = jQuery('#file_types_'+field_key).val();
			
			var frmid =jQuery(this).attr('form-id');
			
			var field_name_id = field_name;
			UploadFile(f,field_key,field_type,frmid, field_name_id);			
		}

	}


	// upload JPEG files
	function UploadFile(file,field_id_val,field_type,frmid, field_name_id) {
		
		// following line is not necessary: prevents running on SitePoint servers
		if (location.host.indexOf("sitepointstatic") >= 0) return

		var xhr = new XMLHttpRequest();
		
		var file_index = file.name.lastIndexOf('.');// .xml

		var file_extension = file.name.substring(file_index+1); // xml
		
		file_extension = file_extension.toLowerCase();
		
		types_arr = field_type.split(",");		
		
		//types_arr.indexOf(file.type)>=0
		
		var arf_file_validation = ( field_type.indexOf(file_extension) >= 0 ) ? true : false;		
		
		if (arf_file_validation && (file_extension!="php" && file_extension!="php3" && file_extension!="php4" && file_extension!="php5" && file_extension!="pl" && file_extension!="py" && file_extension!="jsp" && file_extension!="asp" && file_extension!="exe" && file_extension!="cgi")) {
				
			var seconds = new Date().getTime();
			
			var index = file.name.lastIndexOf('.');// .xml

			var extension = file.name.substring(index+1); // xml

			var filename = /.*(?=\.)/.exec(file.name);
			
			var fname1 = frmid+"_"+field_name_id+"_"+seconds+"_"+filename;
			
			var fname = fname1.replace(/[^\w\s]/gi, '').replace(/ /g,"")+'.'+extension;


			var field_id = field_id_val;
			
			var is_preview = jQuery('#is_form_preview_'+frmid).val();
			
			var arfmainformurl = jQuery('#arfmainformurl').val();
			
			// for progress bar hide
			jQuery('#progress_'+field_id_val).removeClass('progress');
			jQuery('#progress_'+field_id_val+' div.bar').css('width',"0%");			
			jQuery('#info_'+field_id_val).hide();			
			jQuery('#info_'+field_id_val+' #percent').html('0');
			
			//setTimeout(function(){
				jQuery('#div_'+field_id_val).css('margin-top',"-4px");					  
				jQuery('#progress_'+field_id_val).addClass('progress');
				jQuery('#progress_'+field_id_val).addClass('active').show();
			
				jQuery('#info_'+field_id_val).css('display', 'inline-block');
				jQuery('#info_'+field_id_val+' #file_name').html(file.name);
			
				// uploading.......
				xhr.upload.addEventListener("progress", function(e) {
					var pc = parseFloat(0 + (e.loaded / e.total * 100));
						pc = Math.round(pc);
					 jQuery('#progress_'+field_id_val+' div.bar').css('width', pc + "%");
					 jQuery('#info_'+field_id_val+' #percent').html(pc);
				}, false);
				//completed			
				xhr.addEventListener("load", function(e){												  
					setTimeout(function(){ 
						jQuery('#div_'+field_id_val).hide();
						jQuery('#remove_'+field_id_val).show();
						jQuery('#info_'+field_id_val+' #percent').html('100'); 
						jQuery('#progress_'+field_id_val+' div.bar').css('width', "100%"); 
						jQuery('#progress_'+field_id_val).removeClass('active');																	  
						
						jQuery('#div_'+field_id_val).hide();
						jQuery('#remove_'+field_id_val).css('display', 'block');
						jQuery('#div_'+field_id_val).css('margin-top',"0px");
						jQuery('#remove_'+field_id_val).css('margin-top',"-4px");	
					},300);																	 
					
				}, false);
				
			arfmainformurl = is_ssl_replace(arfmainformurl);
			xhr.open("POST", arfmainformurl+"/js/filedrag/upload_front.php?frm="+frmid+"&field_id="+field_id+"&file_type="+file.type+"&types_arr="+types_arr+"&is_preview="+is_preview, true);
			
			//change end here
			xhr.setRequestHeader("X_FILENAME", fname);
			xhr.setRequestHeader("X-FILENAME", fname);
			xhr.send(file);
			
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState == 4) {
					if(xhr.status == 200)
					{
						var data = xhr.responseText;
						var response = data.split("|");
						
						jQuery('input[name="file'+field_name_id+'"]').attr('file-valid', 'true');  // set false
						
						var images = document.getElementById('imagename_'+frmid).value;
						
						var upload_file_key = document.getElementById('upload_field_id_'+frmid).value;
						if(images!='')
						{
							document.getElementById('imagename_'+frmid).value = images+','+response[1];
							document.getElementById('upload_field_id_'+frmid).value = upload_file_key+','+response[2];
						}
						else
						{
							document.getElementById('imagename_'+frmid).value = response[1];
							document.getElementById('upload_field_id_'+frmid).value = response[2];
						}
					}
				}
			};
		}
		else
		{
			jQuery('#div_'+field_id_val).css('margin-top',"0px");	
			jQuery('#progress_'+field_id_val).hide();
			jQuery('#info_'+field_id_val).hide();
			
			if( typeof(__ARFERR) !== undefined )
				var file_error = __ARFERR;
			else
				var file_error = 'Sorry, this file type is not permitted for security reasons.';
			
			if( jQuery('#field_'+field_id_val).attr('data-invalid-message') !== undefined && jQuery('#field_'+field_id_val).attr('data-invalid-message') !='' )
				var arf_invalid_file_message = jQuery('#field_'+field_id_val).attr('data-invalid-message');
			else
				var arf_invalid_file_message = file_error;
			
			var field_id_arr= jQuery('#field_'+field_id_val).attr('name').split('file');
			var field_id 	= field_id_arr[1];
			
			jQuery('input[name="file'+field_id+'"]').attr('file-valid', 'false');  // set false
			
			setTimeout(function(){
				jQuery('#arf_field_'+field_id+'_container').removeClass('arf_success');
				
				var $this = jQuery('#arf_field_'+field_id+'_container .controls');
				var	$controlGroup = $this.parents(".control-group").first();
				var	$helpBlock = $controlGroup.find(".help-block").first();	
				
				var form_id = $this.closest('form').find('#form_id').val();					
				var error_type =  ( jQuery('#form_tooltip_error_'+form_id).val() == 'advance' ) ? 'advance' : 'normal';
					
				if( error_type == 'advance' )
				{
					arf_show_tooltip($controlGroup, $helpBlock, arf_invalid_file_message);
				} else {
					if(!$helpBlock.length) {
						$helpBlock = jQuery('<div class="help-block"><ul><li>'+arf_invalid_file_message+'</li></ul></div>');
						$controlGroup.find('.controls').append($helpBlock);
						$controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
						//$helpBlock.removeClass('animated bounceInDown').addClass('animated bounceInDown');
					}
					else
					{
						$helpBlock = jQuery('<ul role="alert"><li>'+arf_invalid_file_message+'</li></ul>');
						$controlGroup.find('.controls .help-block').append($helpBlock);
						$controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
						//$helpBlock.removeClass('animated bounceInDown').addClass('animated bounceInDown');
					}	
				}
			}, 100);
			
		}
	}


	// initialize
	function Init() {
		
		var fileselect = $id("field_w4ge3s");
		
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
		var field_key = jQuery(this).attr('id').replace('field_', '');
		
		var submit_type = jQuery('#type_'+field_key).val();

		if( submit_type == '0' ){
			fileselect.addEventListener("change", FileSelectHandler, false);
			var field_key_arr1 = jQuery(this).attr('id').split('field_');
		}
		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {
		}							   
   	});
	
	//remove file 
	jQuery('.ajax-file-remove').click(function(i){
											   
		var field_key 	= jQuery(this).attr('id').replace('remove_', '');
		var field_id  	= jQuery(this).attr('data-id');
		var form_id   	= jQuery(this).attr('form-id');
		var string 		= jQuery('#imagename_'+form_id).val();
		
		if( string.indexOf(',') >= 0 ){
			
			var array_val = string.split(",");
			var file_name = '';
			
			for( key in array_val ){
				if( array_val[key].indexOf(field_id) >= 0 )
					file_name =  array_val[key];
			}
			
		} else {
			file_name = string;
		}
		
		
		if( file_name == '' )
			return;
			
		if( typeof(__ARFAJAXURL) === undefined )
			return;
		
		jQuery.ajax({
				
			type:"POST",url: is_ssl_replace(__ARFAJAXURL),

			data:"action=arf_delete_file&file_name="+file_name,
			
			success: function(msg){  
				
				var string1 		= jQuery('#imagename_'+form_id).val();
				var field_ids 		= jQuery('#upload_field_id_'+form_id).val();
				
				if( string1.indexOf(file_name+',') >=0 )
					var string1_new = string1.replace(file_name+',', '');
				else if( string1.indexOf(file_name) >=0 )	
					var string1_new = string1.replace(file_name, '');
				
				if( field_ids.indexOf(field_key+',') >=0 )
					var field_ids_new = field_ids.replace(field_key+',', '');
				else if( field_ids.indexOf(field_key) >=0 )	
					var field_ids_new = field_ids.replace(field_key, '');					
				
				jQuery('#imagename_'+form_id).val(string1_new);
				jQuery('#upload_field_id_'+form_id).val(field_ids_new);
				
				jQuery('#field_' +field_key ).val('');
				//
				jQuery('#remove_'+field_key).hide();
				jQuery('#div_'+field_key).css('display', 'block');
				jQuery('#remove_'+field_key).css('margin-top',"0px");
				jQuery('#div_'+field_key).css('margin-top',"0px");	
				//
				
				jQuery('#progress_'+field_key).hide();
				jQuery('#info_'+field_key).hide();
			}

		});
		
	});
	//remove file end
	
	// upload using iframe 
	jQuery('.original_btn').click(function(e){ 
										   
		var field_key = jQuery(this).attr('id').replace('div_', '');
		
		jQuery('#'+field_key+'_iframe').contents().find("#fileselect").click();
		
		var field_name = jQuery(this).attr('data-id');
		
		jQuery('#arf_field_'+field_name+'_container').find('.help-block').empty();
		
		jQuery('#arf_field_'+field_name+'_container').find('.popover').remove();		
		
		jQuery('#progress_'+field_key).hide();
					
		var result = jQuery('#'+field_key+'_iframe').contents().find("#fileselect").val();						
				
					
		if ( result !='') {
								
			var field_type = jQuery('#file_types_'+field_key).val();
			
			types_arr = field_type.split(",");
			
				var field_name = jQuery(this).attr('data-id');
				
				var file_name = result.replace(/C:\\fakepath\\/i, '');
				
				var frmid = jQuery(this).attr('form-id');
			
				var seconds = new Date().getTime();
				
				var index = file_name.lastIndexOf('.');// .xml
	
				var extension = file_name.substring(index+1); // xml
	
				var filename = /.*(?=\.)/.exec(file_name);
				
				var fname1 = frmid+"_"+field_name+"_"+seconds+"_"+filename;
				
				var fname = fname1.replace(/[^\w\s]/gi, '').replace(' ','')+'.'+extension;
				
				var field_id = field_key;
				
				var is_preview = jQuery('#is_form_preview_'+frmid).val();
				
				var arfmainformurl = jQuery('#arfmainformurl').val();
				
				var file_type = '';
					
				var ie_version = jQuery('#arf_browser_name').attr('data-version');
				
				var browser = jQuery('#arf_browser_name').val();
				
					arfmainformurl = is_ssl_replace(arfmainformurl);
					// for progress bar
					jQuery('#'+field_key+'_iframe').contents().find('form').attr('action', arfmainformurl+"/js/filedrag/upload_front.php?frm="+frmid+"&field_id="+field_id+"&fname="+fname+"&file_type="+file_type+"&types_arr="+types_arr+"&is_preview="+is_preview+"&ie_version="+ie_version+"&browser="+browser);
					
					jQuery('#'+field_key+'_iframe').contents().find('form').submit();
					
					jQuery('#div_'+field_key).css('margin-top',"-4px");	
					jQuery('#progress_'+field_key).show();
					jQuery('#info_'+field_key).css('display', 'inline-block');
					jQuery('#info_'+field_key+' #file_name').html(result.replace(/C:\\fakepath\\/i, ''));
					jQuery('#info_'+field_key+' .percent').html('').show();
					jQuery('#info_'+field_key+' #percent').html('Uploading...');
					jQuery('#progress_'+field_key+' div.bar').animate({ width: "100%" }, 'slow');
						
					var myInterval = setInterval(function(){
						if(jQuery('#'+field_key+'_iframe').contents())								  
						{	
							jQuery('#'+field_key+'_iframe').load(function() {
							var uploaded = jQuery('#'+field_key+'_iframe').contents().find('.uploaded').length;
							if(uploaded > 0){
								clearInterval(myInterval);
								jQuery('#progress_'+field_key).removeClass('active');
								
								//
								jQuery('#div_'+field_key).hide();
								jQuery('#remove_'+field_key).css('display', 'block');
								jQuery('#div_'+field_key).css('margin-top',"0px");
								jQuery('#remove_'+field_key).css('margin-top',"-4px");
								//
								
								jQuery('#info_'+field_key+' #percent').html('File Uploaded');
								jQuery('input[name="file'+field_name+'"]').val(filename);
								jQuery('input[name="item_meta['+field_name+']"]').val(filename);
								
								jQuery('input[name="file'+field_name+'"]').attr('file-valid', 'true');  // set true
								
								var data = jQuery('#'+field_key+'_iframe').contents().find('.uploaded').html();
								var response = data.split("|");
								
								var images = document.getElementById('imagename_'+frmid).value;
								
								var upload_file_key = document.getElementById('upload_field_id_'+frmid).value;
								if(images!='')
								{
									document.getElementById('imagename_'+frmid).value = images+','+response[1];
									document.getElementById('upload_field_id_'+frmid).value = upload_file_key+','+response[2];
								}
								else
								{
									document.getElementById('imagename_'+frmid).value = response[1];
									document.getElementById('upload_field_id_'+frmid).value = response[2];
								}
								
								jQuery('input[name="file'+field_name+'"]').attr('aria-invalid', 'false');
								jQuery('input[name="item_meta['+field_name+']"]').attr('aria-invalid', 'false');
								
								jQuery('input[name="item_meta['+field_name+']"]').trigger('change');
								
								jQuery('#'+field_key+'_iframe_div').html(' ').append('<iframe id="'+field_key+'_iframe" src="'+arfmainformurl+'/core/views/iframe.php"></iframe>');
								
							}
							
							var error_upload = jQuery('#'+field_key+'_iframe').contents().find('.error_upload').length;
							if(error_upload > 0){
								clearInterval(myInterval);
								var field_id 	= field_name;
								
								jQuery('input[name="file'+field_id+'"]').attr('file-valid', 'false');  // set false							
								
								jQuery('#info_'+field_key+' .percent').html('').hide();
								jQuery('#info_'+field_key+' #percent').html('');
								jQuery('#info_'+field_key).hide();
								jQuery('#progress_'+field_key+' div.bar').css({ width: "100%" });
								jQuery('#progress_'+field_key).hide();
								
								//for error display
								if(typeof(__ARFERR)!='undefined')
									var file_error = __ARFERR;
								else
									var file_error = 'Sorry, this file type is not permitted for security reasons.';
								
								if( jQuery('#field_'+field_key).attr('data-invalid-message') !== undefined && jQuery('#field_'+field_key).attr('data-invalid-message') !='' )
									var arf_invalid_file_message = jQuery('#field_'+field_key).attr('data-invalid-message');
								else
									var arf_invalid_file_message = file_error;
					
								jQuery('#arf_field_'+field_id+'_container').removeClass('arf_success');
								var $this = jQuery('#arf_field_'+field_id+'_container .controls');
								var	$controlGroup = $this.parents(".control-group").first();
								var	$helpBlock = $controlGroup.find(".help-block").first();	
								
								var form_id = $this.closest('form').find('#form_id').val();					
								var error_type =  ( jQuery('#form_tooltip_error_'+form_id).val() == 'advance' ) ? 'advance' : 'normal';
									
								if( error_type == 'advance' )
								{
									arf_show_tooltip($controlGroup, $helpBlock, arf_invalid_file_message);
								} else {
									if(!$helpBlock.length) {
										$helpBlock = jQuery('<div class="help-block"><ul><li>'+arf_invalid_file_message+'</li></ul></div>');
										$controlGroup.find('.controls').append($helpBlock);
										$controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
										//$helpBlock.removeClass('animated bounceInDown').addClass('animated bounceInDown');
									}
									else
									{
										$helpBlock = jQuery('<ul role="alert"><li>'+arf_invalid_file_message+'</li></ul>');
										$controlGroup.find('.controls .help-block').append($helpBlock);
										$controlGroup.find('.controls .help-block').removeClass('animated bounceInDownNor').addClass('animated bounceInDownNor');
										//$helpBlock.removeClass('animated bounceInDown').addClass('animated bounceInDown');
									}	
								}								  
								//for error display end
								
								jQuery('#'+field_key+'_iframe_div').html(' ').append('<iframe id="'+field_key+'_iframe" src="'+arfmainformurl+'/core/views/iframe.php"></iframe>');
							}
							});
						}
					},1000);
					
			//} //end if invalid			
		}
		
						
	});	
	// upload using iframe end
	
});