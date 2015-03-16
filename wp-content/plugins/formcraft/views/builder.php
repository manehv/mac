<?php 


/* Authenticate */
$verified = true;
if (!get_option( 'fc_license' ))
{
	echo '<input type="hidden" value="no-key" id="no-key">';
	$verified = false;  
}
else
{
	if ($_SERVER['HTTP_HOST']=='localhost')
	{
		$curlPath = 'localhost/ncrafts.net/license/verify-fc.php?get=true&domain='.$_SERVER['HTTP_HOST'].'&code='.get_option( 'fc_license' );
	}
	else
	{
		$curlPath = 'http://ncrafts.net/license/verify-fc.php?get=true&domain='.$_SERVER['HTTP_HOST'].'&code='.get_option( 'fc_license' );
	}
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $curlPath
		));
	$response = curl_exec($curl);
	curl_close($curl);
	$response = json_decode($response, 1);

	if (isset($response['failed']))
	{
		echo '<input type="hidden" value="no-key" id="no-key">';
		$verified = false;
	}
}

global $wpdb;
$table_builder = $wpdb->prefix . "formcraft_builder";
$table_subs = $wpdb->prefix . "formcraft_submissions";

if (defined('MYMAIL_VERSION'))
{
	?>
	<style>
		.mymail_email
		{
			display: block;
		}
	</style>
	<?php
}
else
{
	?>
	<style>
		.mymail_email
		{
			display: none !important;
		}
	</style>
	<?php
}



global $wpdb;
$id = intval($_GET['id']);

$qry = $wpdb->get_results( "SELECT * FROM $table_builder WHERE id = '$id'" );
if(count($qry)==0){echo "<h3>Invalid Form ID</h3>"; die();}

foreach ($qry as $row) {
	$build = stripcslashes($row->build);
	$options = stripcslashes($row->options);
	$con = stripcslashes($row->con);
	$rec = stripcslashes($row->recipients);
}
$conf = json_decode($con);

/////////////////// JavaScripts //////////////////////





?>
<script language="JavaScript">
	jQuery(document).ready(function() {

		var formfield;
		var formfield_url;

		jQuery('.cpicker').spectrum({
			showInput: true,
			showAlpha: true,
			clickoutFiresChange: true,
			preferredFormat: 'rgb',
			showButtons: false,
			change: function(color){
				jQuery(this).trigger('input');
			},
			move: function(color){
				jQuery(this).trigger('input');
			}
		});

		jQuery('.custom_css_text').keyup(function()
		{
			var abc = jQuery(this).val();
			jQuery('.custom_css_show').text('<style>'+abc+'</style>');
		})



		jQuery('body').on('click', '.upload_logo_formpage', function() {
			formfield = jQuery(this).prev('input');
			tb_show('','media-upload.php?TB_iframe=true');
			return false;
		});
		jQuery('body').on('click', '.upload_logo_formpage_url', function() {
			formfield_url = jQuery(this).prev('input');
			tb_show('','media-upload.php?TB_iframe=true');
			return false;
		});


		window.old_tb_remove = window.tb_remove;
		window.tb_remove = function() {
			window.old_tb_remove();
			formfield=null;
		};


		window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html){
			if (formfield) {
				fileurl = jQuery('img',html).attr('src');
				jQuery(formfield).val(fileurl);
				jQuery(formfield).trigger('input');
				tb_remove();
			} else if (formfield_url) {
				fileurl = "url("+jQuery('img',html).attr('src')+")";
				jQuery(formfield_url).val(fileurl);
				jQuery(formfield_url).trigger('input');
				tb_remove();
			}
			else {
				window.original_send_to_editor(html);
			}
		};

	});
</script>
<!--[if IE]>
<style>
.main_builder textarea, .main_builder input[type="text"], .main_builder input[type="password"], .main_builder input[type="date"], .main_builder input[type="month"], .main_builder input[type="week"], .main_builder input[type="number"], .main_builder input[type="email"], .main_builder input[type="url"], .main_builder input[type="search"], .main_builder input[type="tel"], .main_builder .uneditable-input
{
	min-height: 28px !important;
}
</style>
<![endif]-->

<div ng-app="compile" ng-controller="bob_the_builder" class="ffcover fc-common has-js">


	<table class='ff_c_t' cellspacing="0" cellpadding="0">
		<tr>
			<td style='width: 580px'>
				<div class="main_builder">
					<div class='build_affix' data-spy="affix" data-offset-top="0"><!-- Start of affixed Part -->

						<div class='head_holder'>
							<a class='fc-btn medium' href='?page=formcraft_admin' style='width: 100px'><i class='formcraft-left-open'></i>Dashboard</a>

							<a data-normal="<i class='formcraft-folder-open'></i>Save" data-loading="Saving.." data-error='Retry' class='fc-btn medium' ng-click='save()' id='save_form_btn' style='width: 80px'><i class='formcraft-folder-open'></i>Save</a>

							<a class="fc-btn medium btn-toggle" data-toggle="collapse" href="#collapseOne" style='width: 90px'><i class='formcraft-wrench'></i>Options</a>

							<a class="fc-btn medium btn-toggle" data-toggle="collapse"  href='#collapseTwo' style='width: 90px'><i class='formcraft-font'></i>Styling</a>

						</div>

						<div id="collapseOne" class="form_accordion accordion-body collapse">
							<span class='options_label'>Options</span>
							<div class="accordion-inner">


								<div class='accordion acl' id="accordion_fo">

									<div class="accordion-group">
										<div class="accordion-heading">
											<a class="accordion-toggle collapsed">
												1. General
											</a>
										</div>

										<div id="form_options_one" class="accordion-body collapse">
											<div class="accordion-inner l2">

												<div class='global_holder'>
													<span class='settings_desc' style='font-size: 14px'>Submit values from fields hidden by Conditional Laws?</span>

													<label class='label_radio'>
														<input type='radio' ng-model='con[0].cl_hidden_fields' value='submit_hidden' name='cl_hidden_fields'>
														<div class='label_div' style='background: #f3f3f3'>
															Yes, submit all fields, whether hidden or visible
														</div>
													</label>

													<label class='label_radio'>
														<input type='radio' ng-model='con[0].cl_hidden_fields' value='no_submit_hidden' name='cl_hidden_fields'>
														<div class='label_div' style='background: #f3f3f3'>
															No
														</div>
													</label>
												</div>

												<div class='global_holder'>

													<span class='settings_desc' style='font-size: 14px'>Show empty field values in emails?</span>

													<label class='label_radio'>
														<input type='radio' ng-model='con[0].cl_empty_fields' value='submit_empty' name='cl_empty_fields'>
														<div class='label_div' style='background: #f3f3f3'>
															Yes
														</div>
													</label>

													<label class='label_radio'>
														<input type='radio' ng-model='con[0].cl_empty_fields' value='no_submit_empty' name='cl_empty_fields'>
														<div class='label_div' style='background: #f3f3f3'>
															No
														</div>
													</label>
												</div>												

												<div class='global_holder'>
													<span class='settings_desc' style='font-size: 14px'>Save form data as the user types? When the user comes back to the page, he can continue with the auto saved form.</span>
													<label class='label_radio'>
														<input type='radio' ng-model='con[0].user_save_form' value='save_form' name='user_save_form'><div class='label_div' style='background: #f3f3f3'>
														Yes</div>
													</label>
													<label class='label_radio'>
														<input type='radio' ng-model='con[0].user_save_form' value='no_save_form' name='user_save_form'><div class='label_div' style='background: #f3f3f3'>
														No</div>
													</label>
												</div>

												<div class='global_holder'>
													<span class='settings_desc' style='font-size: 14px; font-weight: bold'>No Conflict Mode</span>
													<span class='settings_desc' style='font-size: 13px'>If the checkbox, or multi-choice field(s) used by FormCraft are having problems with your theme's styling, check <strong>yes</strong>. Else, ignore this.</span>
													<label class='label_radio'>
														<input type='radio' ng-model='con[0].check_no_conflict' value='check_no_conflict' name='check_no_conflict'><div class='label_div' style='background: #f3f3f3'>
														Yes</div>
													</label>
													<label class='label_radio'>
														<input type='radio' ng-model='con[0].check_no_conflict' value='check_conflict' name='check_no_conflict'><div class='label_div' style='background: #f3f3f3'>
														No</div>
													</label>
												</div>

												<div class='global_holder'>
													<span class='settings_desc' style='font-size: 14px;'>Use Number Spinning Effect for Math Results?</span>
													<label class='label_radio'>
														<input type='radio' ng-model='con[0].number_spin' value='spin' name='number_spin'><div class='label_div' style='background: #f3f3f3'>
														Yes</div>
													</label>
													<label class='label_radio'>
														<input type='radio' ng-model='con[0].number_spin' value='no_spin' name='number_spin'><div class='label_div' style='background: #f3f3f3'>
														No</div>
													</label>
												</div>																								

												<div class='global_holder'>
													<span class='settings_desc' style='font-size: 14px'>Allow multiple submissions from the same device?</span>
													<label class='label_radio'>
														<input type='radio' ng-model='con[0].allow_multi' value='allow_multi' name='allow_multi'><div class='label_div' style='background: #f3f3f3'>
														Yes</div>
													</label>
													<label class='label_radio'>
														<input type='radio' ng-model='con[0].allow_multi' value='no_allow_multi' name='allow_multi'><div class='label_div' style='background: #f3f3f3'>
														No</div>
													</label>
													<br>
													<div ng-class='"op_"+[con[0].allow_multi]'>
														<p>Error Message to Show<br><span class='settings_desc'>you can use HTML here</span></p>
														<textarea rows='4' ng-model='con[0].multi_error' style='width: 100%'></textarea>
													</div>
												</div>												

											</div>
										</div>
									</div>

									<div class="accordion-group">
										<div class="accordion-heading">
											<a class="accordion-toggle collapsed">
												2. Email Notifications
											</a>
										</div>

										<div id="form_options_two" class="accordion-body collapse">
											<div class="accordion-inner l2">

												<div class='global_holder'>

													<div class='gh_head'>Email Sending Method&nbsp;&nbsp;<button class='fc-btn small' id='test_email'>Send Test Email</button></div>

													read: <a style='display: inline-block; margin-bottom: 8px' target='_blank' href='http://ncrafts.net/formcraft/others/troubleshooting-email-notifications/'>troubleshooting emails notifications</a>

													<div><div id='test_response'>
														<ol>
															<li>Save the form before sending a test email
															</li>
															<li>The test email(s) will be sent to the list of recipients added below</li>
														</ol>
													</div></div>

													<label class='label_radio circle-ticked'><input type='radio' ng-model='con[0].mail_type' value='mail' name='type_email'><strong><div class='label_div' style='background: #f3f3f3'>Use PHP Mail Function (default)</div></strong>
													</label>
													<br>
													<div class='mail_type_div {{con[0].mail_type}}1'>

														<span style='width: 30%; display: inline-block; text-align: right; margin-right: 5%'> Sender Name: </span><input type='text' ng-model='con[0].from_name'><i class='ttip formcraft-help-circled' title='You can use values from the form, using labels like [Name]'></i><br>

														<span style='width: 30%; display: inline-block; text-align: right; margin-right: 5%'> Sender Email: </span><input type='text' ng-model='con[0].from_email'><i class='ttip formcraft-help-circled' title='You can use values from the form, using labels like [Email]'></i>
														<br><br>
													</div>

													<label class='label_radio circle-ticked'><input type='radio' ng-model='con[0].mail_type' name='type_email' value='smtp'><strong><div class='label_div' style='background: #f3f3f3'>Use SMTP Authentication (try if the above doesn't work)</div></strong></label>
													<br>

													<div class='mail_type_div {{con[0].mail_type}}'>

														<label><span style='width: 30%; margin-top: 5px; display: inline-block; text-align: right; margin-right: 5%'> Sender Name: </span><input type='text' ng-model='con[0].smtp_name' placeholder='No Reply'></label><i class='ttip formcraft-help-circled' title='You can use values from the form, using labels like [Name]'></i><br>
														<label><span style='width: 30%; margin-top: 5px; display: inline-block; text-align: right; margin-right: 5%'> Username: </span><input type='text' ng-model='con[0].smtp_username' placeholder='noreply'></label><br>
														<label><span style='width: 30%; margin-top: 5px; display: inline-block; text-align: right; margin-right: 5%'> Email: </span><input type='text' ng-model='con[0].smtp_email' placeholder='noreply@ncrafts.net'></label><i class='ttip formcraft-help-circled' title='You can use values from the form, using labels like [Email]'></i><br>
														<label><span style='width: 30%; margin-top: 5px; display: inline-block; text-align: right; margin-right: 5%'> Password: </span><input type='password' ng-model='con[0].smtp_pass'></label><br>
														<label><span style='width: 30%; margin-top: 5px; display: inline-block; text-align: right; margin-right: 5%'> Host: </span><input type='text' ng-model='con[0].smtp_host' placeholder='mail.ncrafts.net'></label>

														<label class='label_radio' style='margin-left: 35%'><input type='checkbox' ng-model='con[0].if_ssl' name='if_ssl' ng-true-value='ssl' ng-false-value='false'><div class='label_div' style='background: #f3f3f3'>Use SSL</div></label>

														<label class='label_radio' style='margin-left: 15px'><input type='checkbox' ng-model='con[0].if_ssl' name='if_ssl' ng-true-value='tls' ng-false-value='false'><div class='label_div' style='background: #f3f3f3'>Use TLS</div></label><br>

														<label><span style='width: 30%; margin-top: 5px; display: inline-block; text-align: right; margin-right: 5%'> Port: </span><input type='text' ng-model='con[0].smtp_port' placeholder='465'></label><br>


													</div>		
												</div>


												<div class='global_holder'>
													<div class='gh_head'>Add Email Recipients</div>
													<span class='settings_desc'>When the form is successfully submitted, the following people will get an email notification. Separate multiple emails with commas</span>
													<ul class='rec_ul'>
														<textarea style='width: 100%' rows='3' ng-model='recipients'></textarea>
													</ul>
												</div>												

												<div class='global_holder'>

													<div class='gh_head'>Email Content</div>

													<label style='width: 30%; display: inline-block; text-align: right; margin-right: 5%'>Email Subject</label>
													<input type='text' style='width: 60%' ng-model='con[0].email_sub'>

													<label style='width: 30%; display: inline-block; text-align: right; margin-right: 5%'>Email Body</label>
													<textarea rows='7' style='width: 60%' ng-model='con[0].email_body'></textarea>
													<div style="margin-left: 35%; margin-top: 5px; font-size: 12px" class='desc'>Use the label [Form Content] to insert the form data in the email body. You can also use [URL] to get the form page URL, and [IP] to get the IP address of the submitter.</div>

												</div>

											</div>
										</div>
									</div>



									<div class="accordion-group">
										<div class="accordion-heading">
											<a class="accordion-toggle collapsed">
												3. Form To Post
											</a>
										</div>

										<div id="form_options_two" class="accordion-body collapse">
											<div class="accordion-inner l2">

												<div class='global_holder'>
												<label class='label_radio circle-ticked'><input type='checkbox' ng-model='con[0].form_post' ng-true-value='enable' name='form_post'><strong><div class='label_div' style='background: #f3f3f3'>Enable</div></strong></label>

												<br>
												<div class='{{con[0].form_post}}-show' style='opacity: .5'>

												<label for='error_id_a' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Post Title</label>
												<input type='text' id='fp_title' style='width: 60%' ng-model='con[0].fp_title'>

												<label for='error_id_a' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Post Body</label>
												<input type='text' id='fp_body' style='width: 60%' ng-model='con[0].fp_body'>

												<label for='error_id_a' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Post Category</label>
												<input type='text' id='fp_category' style='width: 60%' ng-model='con[0].fp_category'>

												<label for='error_id_a' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Post Author Username</label>
												<input type='text' id='fp_author' style='width: 60%' ng-model='con[0].fp_author'>


												<label for='error_id_a' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Post Tags</label>
												<input type='text' id='fp_tags' style='width: 60%' ng-model='con[0].fp_tags'>												

												<label for='error_id_a' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Post Status</label>
												<div class='select-cover' style='width: 60%; margin: 0'>
												<select ng-model='con[0].fp_status' style='font-size: 12px'>
												<option value='draft'>Draft</option>
												<option value='pending'>Pending Review</option>
												<option value='publish'>Published</option>
												</select>
												</div>
												</div>

												</div>

											</div>
										</div>
									</div>




									<div class="accordion-group">
										<div class="accordion-heading">
											<a class="accordion-toggle collapsed">
												4. Email AutoResponders
											</a>
										</div>

										<div id="form_options_three" class="accordion-body collapse">
											<div class="accordion-inner l2">

												<div class='global_holder'>
													<div class='gh_head'>
														AutoReply Email Settings
													</div>
													<span class='settings_desc'>You can send autoreplies to emails entered by the user when they fill up the form. You can enable autoreplies for any email form field, by checking the option <strong>Send AutoReply to this Email</strong> in the field options.</span>
													<br>


													<div style='display: inline-block; width: 48%'>
														<p>Sender Name<br>
														</p>
														<input ng-model='con[0].autoreply_name' style='width: 100%' type='text'>
													</div>

													<div style='display: inline-block; width: 48%; float: right'>
														<p>Sender Email<br>
														</p>
														<input ng-model='con[0].autoreply_email' style='width: 100%' type='text'>
													</div>
													<br>

													<p>Subject of Email<br>
													</p>
													<input ng-model='con[0].autoreply_s' style='width: 100%' type='text'>
													<br><br>

													<p>Body of Email<br>
														<span class='settings_desc'>you can use HTML here</span>
													</p>
													<textarea ng-model='con[0].autoreply' rows='7' style='width: 100%'>
													</textarea>

												</div>

											</div>
										</div>
									</div>




									<div class="accordion-group">
										<div class="accordion-heading">
											<a class="accordion-toggle collapsed">
												5. Displaying the Form
											</a>
										</div>

										<div id="form_options_four" class="accordion-body collapse">
											<div class="accordion-inner l2">

												<div class='global_holder'>

													<div class='gh_head'>Dedicated Form Page &nbsp;&nbsp;
														<label class='label_check'>
															<input type='checkbox' ng-model='con[0].formpage'><div class='label_div' style='background: #f3f3f3'>Enabled</div>
														</label>
													</div>

													<div class='op_{{con[0].formpage}}'>
														Show a Logo On the Form Page
														<br>
														<input id="image_location" type="text" name="image_location" placeholder='Paste URL here or click Select' ng-model="con[0].formpage_image" style='width: 310px'/>
														<input  class="fc-btn upload_logo_formpage" type="button" value="Select Image" style='width: 25%; height: 28px'/>
														<br><br>
														The below URL links to page that contains the form. You can share this URL to allow people to fill this form / survey. This page also looks awesome on mobile devices.
														<br>
														<textarea onclick="select()" readonly="readonly" class="code code-inline" rows="2"><?php echo plugins_url().'/formcraft/form.php?id='.$id; ?></textarea>
													</div>

												</div>
												<div class='global_holder'>
													<div class='gh_head'>
														Other Methods
													</div>

													<strong>Shortcode</strong>
													<textarea onclick="select()" readonly="readonly" class="code code-inline" rows="1">[formcraft id='<?php echo $id; ?>']</textarea>
													<br><br>
													<strong>Widget</strong>
													<div>Go to <em class='surround'>Appearance -> Widgets -> formcraft</em></div>
													<br><strong>Use in Themes, and Other Places</strong>
													<textarea onclick="select()" readonly="readonly" class="code code-inline" rows="1">&lt;?php formcraft(<?php echo $id; ?>); ?&gt;</textarea>
													Please refer to the <a href='<?php echo plugins_url('formcraft/documentation.html#usage'); ?>' target='_blank' title='opens in a new tab / window'>documentation</a> for details on how to use the sticky, popup, or fly in forms
												</div>
											</div>
										</div>
									</div>

									<div class="accordion-group">
										<div class="accordion-heading">
											<a class="accordion-toggle collapsed">
												6. Form Error Messages
											</a>
										</div>

										<div id="form_options_five" class="accordion-body collapse">
											<div class="accordion-inner l2">

												<div class='global_holder'>


													<label for='error_id_a' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Common Message</label>
													<input type='text' id='error_id_a' style='width: 60%' ng-model='con[0].error_gen'>
													<hr>

													<label for='error_id_1' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Incorrect File Upload Type</label>
													<input type='text' id='error_id_1' style='width: 60%' ng-model='con[0].error_ftype'>														
													<br>
													<label for='error_id_112' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Incorrect File Upload Size (min)</label>
													<input type='text' id='error_id_112' style='width: 60%' ng-model='con[0].error_ftype1'>														
													<br>
													<label for='error_id_113' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Incorrect File Upload Size (max)</label>
													<input type='text' id='error_id_113' style='width: 60%' ng-model='con[0].error_ftype2'>														
													<br>
													<label for='error_id_115' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Uploaded max number of files</label>
													<input type='text' id='error_id_115' style='width: 60%' ng-model='con[0].error_ftype3'>														
													<br>
													<label for='error_id_114' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Incorrect Email</label>
													<input type='text' id='error_id_114' style='width: 60%' ng-model='con[0].error_email'>
													<br>
													<label for='error_id_2' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Incorrect URL</label>
													<input type='text' id='error_id_2' style='width: 60%' ng-model='con[0].error_url'>
													<br>
													<label for='error_id_3' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Incorrect Captcha</label>
													<input type='text' id='error_id_3' style='width: 60%' ng-model='con[0].error_captcha'>
													<br>
													<label for='error_id_4' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Integers Only</label>
													<input type='text' id='error_id_4' style='width: 60%' ng-model='con[0].error_only_integers'>
													<br>
													<label for='error_id_4' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Alphabets Only</label>
													<input type='text' id='error_id_4' style='width: 60%' ng-model='con[0].error_only_alpha'>
													<br>
													<label for='error_id_4' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Alpha-numeric Only</label>
													<input type='text' id='error_id_4' style='width: 60%' ng-model='con[0].error_only_alnum'>
													<br>
													<label for='error_id_5' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Compulsory Field</label>
													<input type='text' id='error_id_5' style='width: 60%' ng-model='con[0].error_required'>
													<br>
													<label for='error_id_6' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Min Characters</label>
													<input type='text' id='error_id_6' style='width: 60%' ng-model='con[0].error_min'>
													<br>
													<label for='error_id_7' style='width: 30%; display: inline-block; text-align: right; margin-right: 5%; font-size: 12px; margin-top: 6px'>Max Characters</label>
													<input type='text' id='error_id_7' style='width: 60%' ng-model='con[0].error_max'>
													<br>

												</div>
											</div>
										</div>
									</div>



									<div class="accordion-group">
										<div class="accordion-heading">
											<a class="accordion-toggle collapsed">
												7. On Form Submission
											</a>
										</div>

										<div id="form_options_six" class="accordion-body collapse">
											<div class="accordion-inner l2">

												<div class='global_holder'>
													<div class='gh_head'>
														Form Sent Message
													</div>

													<span class='settings_desc'>you can use HTML here</span>
												</p>
												<textarea style='width:96%; margin: 0' rows='4' ng-model='con[0].success_msg'></textarea>
												<div compile='con[0].success_msg' class='nform_res_sample' style='white-space: pre-line'></div>
											</div>
											<div class='global_holder'>

												<div class='gh_head'>
													Form Could Not be Sent Message
												</div>														<span class='settings_desc'>you can use HTML here</span>
											</p>
											<textarea style='width:96%;  margin: 0' rows='4' ng-model='con[0].failed_msg'></textarea>
											<div compile='con[0].failed_msg' class='nform_res_sample' style='white-space: pre-line'></div>

										</div>
										<div class='global_holder'>
											<div class='gh_head'>
												Redirection
											</div>

											<p>URL<br>
												<span class='settings_desc'>redirects the user in case of a successful form submission<br>(disabled in the form builder mode)</span>
											</p>
											<input type='text' style='width: 96%' ng-model='con[0].redirect'>

										</div>
									</div>
								</div>
							</div>



							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle collapsed">
										8. Support FormCrafts
									</a>
								</div>

								<div id="form_options_seven" class="accordion-body collapse">
									<div class="accordion-inner l2">

										<div class='global_holder'>

											<span style='color: #444; font-size: 15px;'>
												Display a referral link below the submit button
											</span>

											<br><br>
											<label style='display: inline-block; width: 120px'>Text</label>
											<input type='text' ng-model='con[0].rlink' placeholder='Powered by FormCrafts'>
											<br>

											<label style='display: inline-block; width: 120px'>Username</label>
											<input type='text' ng-model='con[0].ruser' placeholder='nCrafts'>
											<em style='font-size: 12px; color: #888; line-height: 14px;'> 
												<br>
												(Enter your marketplace username here. You will receive a 30% commission on the first deposit / purchase made by user who clicked on this link. Read more 
												<a href='http://codecanyon.net/make_money/affiliate_program' target="_blank">here</a>.)
											</em>


										</div>
									</div>
								</div>
							</div>


							<?php
							if (function_exists('formcraft_add_builder'))
							{
								formcraft_add_builder();
							}

							?>





							<?php
							if (defined('MYMAIL_VERSION'))
							{
								?>


								<div class="accordion-group">
									<div class="accordion-heading">
										<a class="accordion-toggle collapsed" style='color: green'>
											9. Integration: MyMail
										</a>
									</div>

									<div id="form_options_mymail" class="accordion-body collapse">
										<div class="accordion-inner l2">

											<div class='global_holder'>
												<div class='gh_head'>
													MyMail
												</div>
												<span class='settings_desc'>You can use the forms to easily add to your list of subscribers with MyMail.
													<br><br>
													<p>Step 1 of 2
														<span class='settings_desc'>
															Enter the name of the list you want to add the new email subscribers to</span>
														</p>
														<input ng-model='con[0].mm_list' style='width: 100%' type='text'><br><br>
														<p>Step 2 of 2
															<span class='settings_desc'>
																Now add an email field and check the option 'Add to MyMail'.<br>That's it!
															</span>
														</p>
													</div>
												</div>
											</div>
										</div>


										<?php } ?>



										<form id='export_form_form' name="myForm" action="<?php echo plugins_url('formcraft/php/export_form.php?id=').$_GET[id]; ?>" method="POST" target='_blank' style='width: 100%; margin: 0px; padding: 0px; display: inline-block'>

											<input type="hidden" id="export_build" name="build" value = "">
											<input type="hidden" id="export_option" name="options" value = "">
											<input type="hidden" id="export_con" name="con" value = "">
											<input type="hidden" id="export_rec" name="rec" value = "">
											<input type="hidden" id="export_dir" name="dir" value = "<?php echo plugins_url(); ?>">
											<input type="hidden" id="export_dir2" name="dir2" value = "<?php echo site_url(); ?>">

											<a id='export_form' export-link='<?php echo plugins_url('formcraft/php/export_form.php?id=').$_GET[id]; ?>' class='trans_btn' style='color: green; width: 100%' ng-click='export_form()'>
												Export Form
											</a>


										</form>


									</div>


								</div>
							</div>

							<div id="collapseTwo" class="form_accordion accordion-body collapse">
								<span class='options_label'>Styling</span>
								<div class="accordion-inner">

									<div class="accordion acl" id="accordion2">
										<div class="accordion-group">
											<div class="accordion-heading">
												<a class="accordion-toggle collapsed">
													1. Overall Form Styling
												</a>
											</div>

											<div id="globalOne" class="accordion-body collapse">
												<div class="accordion-inner l2">
													<div class='global_holder'>

														<label for='form_wd' class='option_text'>Form Width</label>
														<input id='form_wd' type='text' ng-model='con[0].fw' style='width: 60px'>
														<span class='description' style='width: 200px'>Enter a value like 400px, 500px or 70%.<br> Or leave it blank.</span>
														<br>
														<label class='option_text'>Rounded Corners</label>
														<div id='slider_rc' class='con_slider'></div>
														<span style='font-size: 15px'>{{con[0].fr}}px</span>
														<input id='slider_rc_v' type='number' min='0' ng-model='con[0].fr' style='display: none'>
														<br>
														<label class='option_text'>Form Frame</label>
														<label class='label_check'>
															<input type='checkbox' ng-model='con[0].frame' ng-true-value='noframe'><div class='label_div' style='background: #f3f3f3'> Remove Frame</div>

														</label>
														<br>

														<label class='option_text'>Form Layout</label>
														<label class='label_radio'>
															<input type='radio' ng-model='con[0].flayout' value='horizontal'>
															<div class='label_div' style='background: #f3f3f3'>Horizontal</div>
														</label>
														<label class='label_radio'>
															<input type='radio' ng-model='con[0].flayout' value='vertical'>
															<div class='label_div' style='background: #f3f3f3'>Vertical</div>
														</label>
														<br>




														<label class='option_text' style='width: 150px; display: inline-block'> Field Alignment </label>

														<label class='label_radio'>
															<input type='radio' ng-model='con[0].field_align' value='left' name='field_align'>
															<div class='label_div' style='background: #f3f3f3'>Left</div>
														</label>

														<label class='label_radio'>
															<input type='radio' ng-model='con[0].field_align' value='center' name='field_align'>
															<div class='label_div' style='background: #f3f3f3'>Center</div>
														</label>

														<label class='label_radio'>
															<input type='radio' ng-model='con[0].field_align' value='right' name='field_align'>
															<div class='label_div' style='background: #f3f3f3'>Right</div>
														</label>


														<label class='option_text' style='width: 150px; display: inline-block'> Direction </label>

														<label class='label_radio'>
															<input type='radio' ng-model='con[0].direction' value='ltr' name='direction'>
															<div class='label_div' style='background: #f3f3f3'>Left to Right</div>
														</label>

														<label class='label_radio'>
															<input type='radio' ng-model='con[0].direction' value='rtl' name='direction'>
															<div class='label_div' style='background: #f3f3f3'>Right to Left</div>
														</label>


													</div>

													<div class='global_holder'>

														<span class='option_text' style='vertical-align: top'>Form Background</span>
														<div style='display: inline-block; width: 260px'>
															<span class='fc-btn-group form-theme-bg' style='margin: 0px 0px 0 0'>
																<button type='button' class='fc-btn small tool' ng-click='con[0].bg_image="white"'>None</button>

																<button type='button' class='fc-btn small tool' ng-click='con[0].bg_image="transparent"'>Transparent</button>

																<button type='button' class='fc-btn small tool' ng-click='con[0].bg_image="url(<?php echo plugins_url('formcraft/images/wash.png') ?>)"'>Linen</button>

																<button type='button' class='fc-btn small tool' ng-click='con[0].bg_image="url(<?php echo plugins_url('formcraft/images/jean.png') ?>)"'>Jean</button>


															</span>
															<span class='fc-btn-group form-theme-bg' style='margin: 5px 0px 15px 0'>

																<button type='button' class='fc-btn small tool' ng-click='con[0].bg_image="url(<?php echo plugins_url('formcraft/images/debut.png') ?>)"'>Debut</button>

																<button type='button' class='fc-btn small tool' ng-click='con[0].bg_image="url(<?php echo plugins_url('formcraft/images/carbon.png') ?>)"'>Carbon</button>

																<button type='button' class='fc-btn small tool' ng-click='con[0].bg_image="url(<?php echo plugins_url('formcraft/images/denim.png') ?>)"'>Denim</button>

																<button type='button' class='fc-btn small tool' ng-click='con[0].bg_image="url(<?php echo plugins_url('formcraft/images/irongrip.png') ?>)"'>Iron</button>

															</span>
														</div>

														<br>												


														<span class='option_text' style='vertical-align: middle'>Custom Background
															<span style='color: #777; display: block; font-size: 12px'>enter color, or image URL</span>
														</span>
														<span class=''>
															<input id="image_location_2" type="text" name="image_location_2" placeholder='Paste URL here or click Select' ng-model="con[0].bg_image" style='width: 44%'/>
															<input style="vertical-align: top; height: 28px; width: 60px" class="fc-btn upload_logo_formpage_url" type="button" value="Select"/>
														</span>


														<label class='option_text'>Font Family</label>
														<div class='select-cover' style='margin-left: 0px; width: 252px'>
															<select ng-model='con[0].formfamily' class='font-change' style='font-size: 12px'>
																<optgroup label='Defaults'>
																	<option value='Helvetica Neue, Arial'>Helvetica</option>
																	<option value='Courier New'>Courier New</option>
																	<option value='Georgia'>Georgia</option>
																	<option value='Book Antiqua, Palatino Linotype'>Book Antiqua</option>
																	<option value='Geneva, Tahoma'>Geneva</option>
																	<option value='Times New Roman'>Times New Roman</option>
																	<option value='Trebuchet MS'>Trebuchet MS</option>
																</optgroup>
																<optgroup label='Google Fonts'>
																	<option value='Open Sans'>Open Sans</option>
																	<option value='Source Sans Pro'>Source Sans Pro</option>
																	<option value='Quicksand'>Quicksand</option>
																	<option value='Handlee'>Handlee</option>
																	<option value='Oswald'>Oswald</option>
																	<option value='Ubuntu'>Ubuntu</option>
																</optgroup>
															</select>

														</div>										
													</div>



												</div>

											</div>
										</div>
										<div class="accordion-group">
											<div class="accordion-heading">
												<a class="accordion-toggle collapsed">
													2. Input Fields
												</a>
											</div>
											<div id="globalTwo" class="accordion-body collapse">										
												<div class="accordion-inner l2">
													<div class='global_holder'>
														<label class='option_text' for='slider_fs_v'> Field Spacing </label>
														<div id='slider_fs' class='con_slider'></div>
														<span style='font-size: 15px'>{{con[0].space}}px</span>
														<input class='mr' type='number' min='0' id='slider_fs_v' ng-model='con[0].space' style='width: 48px; font-size: 13px; display: none'>


														<label class='option_text' for='slider_fs_v'> Font Size </label>
														<div id='slider_font' class='con_slider'></div>
														<span style='font-size: 15px'>{{con[0].field_font}}px</span>
														<input class='mr' type='number' min='0' id='slider_font_v' ng-model='con[0].field_font' style='width: 48px; font-size: 13px; display: none'>


														<label class='option_text'>Font Color</label>
														<input type='text' ng-model='con[0].input_color' style='width: 168px' class='cpicker'>
													</div>

													<div class='global_holder'>
														<label class='option_text' for='form_title_px'> Full Length Labels</label>

														<label class='label_radio'><input type='radio' ng-model='con[0].block_label' name='select_block_label' value='no_block_label'><div class='label_div' style='background: #f3f3f3'>No</div></label>
														<label class='label_radio'><input type='radio' ng-model='con[0].block_label' name='select_block_label' value='block_label'><div class='label_div' style='background: #f3f3f3'>Yes</div></label>

														<br>
														<span class='description' style='width: 100%; margin: 0px'>Yes: input fields will appear below the labels, instead of sitting next to them.</span>
														<br>
													</div>


													<div class='global_holder'>
														<span class='option_text'>Validation Style</span>

														<label class='label_radio'><input type='radio' ng-model='con[0].themev' name='select_themev' value='one'><div class='label_div' style='background: #f3f3f3'>Right</div></label>
														<label class='label_radio'><input type='radio' ng-model='con[0].themev' name='select_themev' value='three'><div class='label_div' style='background: #f3f3f3'>Top</div></label>

														<br>
														<label class='label_check'>

															<input type='checkbox' ng-model='con[0].show_star_validation'>
															<div class='label_div' style='background: #f3f3f3'>Don't Show 
																<span class='show_1_sample'>*</span>
																for Required Fields</div>

															</label>
														</div>


														<div class='global_holder'>
															<span class='option_text'>Input Fields Theme</span>

															<label class='label_radio'><input type='radio' ng-model='con[0].themef' name='select_themef' value=''><div class='label_div' style='background: #f3f3f3'>None</div></label>
															<label class='label_radio'><input type='radio' ng-model='con[0].themef' name='select_themef' value='transparent'><div class='label_div' style='background: #f3f3f3'>Transparent</div></label>
														</div>

													</div>
												</div>
											</div>




											<div class="accordion-group">
												<div class="accordion-heading">
													<a class="accordion-toggle collapsed">
														3. Labels and Sub-Labels
													</a>
												</div>
												<div id="globalThree" class="accordion-body collapse">										
													<div class="accordion-inner l2">

														<div class='global_holder'>
															<div class='gh_head'>General</div> 


															<label class='option_text' style='width: 150px; display: inline-block'> Label Placement </label>


															<label class='label_radio'><input ng-click='$scope.$apply()' class='ph_change' type='radio' ng-model='con[0].placeholder' name='radio_placeholder' value='placeholder'>
																<div class='label_div' style='background: #f3f3f3'>Inside / Hidden</div>
															</label>
															<label class='label_radio'><input ng-click='$scope.$apply()' class='ph_change' type='radio' ng-model='con[0].placeholder' name='radio_placeholder' value='no_placeholder'>
																<div class='label_div' style='background: #f3f3f3'>Outside
																</div>
															</label>



														</div>

														<div class='global_holder'>
															<div class='gh_head'>Main Labels</div> 
															<span class='option_text'>
																Width of input labels</span>

																<label class='label_radio'><input type='radio' ng-model='con[0].cap_width' name='radio_cap_width' value='cap_width'>
																	<div class='label_div' style='background: #f3f3f3'>Fixed</div>
																</label>
																<label class='label_radio'><input type='radio' ng-model='con[0].cap_width' name='radio_cap_width' value='relative'>
																	<div class='label_div' style='background: #f3f3f3'>Relative</div>
																</label>

																<div class='brk'></div>

																<label for='lfs' class='option_text'>Font Size</label>
																<input type='number' min='1' id='lfs' ng-model='con[0].lfs' style='width: 60px'>
																<label for='lfc' class='option_text'>Font Color</label>
																<input class='cpicker' id='lfc' ng-model='con[0].lfc' style='width: 40px'>
															</div>


															<div class='global_holder'>
																<div class='gh_head'>Sub Labels</div> 
																<span class='option_text'>Show sub-labels?</span>

																<label class='label_radio'><input type='radio' ng-model='con[0].subl' name='radio_subl' value=''>
																	<div class='label_div' style='background: #f3f3f3'>Yes</div>
																</label>
																<label class='label_radio'><input type='radio' ng-model='con[0].subl' name='radio_subl' value='subl'>
																	<div class='label_div' style='background: #f3f3f3'>No</div>
																</label>


																<div class='brk'></div>

																<label for='slfs' class='option_text'>Font Size</label>
																<input type='number' min='1' id='slfs' ng-model='con[0].slfs' style='width: 60px'>
																<label for='slfc' class='option_text'>&nbsp; &nbsp;Font Color</label>
																<input class='cpicker' id='slfc' ng-model='con[0].slfc' style='width: 40px'>
															</div>

														</div>
													</div>
												</div>


												<div class="accordion-group">
													<div class="accordion-heading">
														<a class="accordion-toggle collapsed">
															4. Custom CSS
														</a>
													</div>


													<div id="globalFour" class="accordion-body collapse">	
														<div class="accordion-inner l2">

															<div class='global_holder'>
																<div class='gh_head'>Enter Custom CSS</div> 
																<textarea style='width: 100%' ng-model='con[0].custom_css' rows='8' class='custom_css_text'></textarea>

															</div>
														</div>

													</div>									

												</div>

											</div>


										</div>




									</div>



									<div class='choose_from_main'>

										<span class='choose_from fc-btn-group'>
											<button type="button" class="fc-btn small light" ng-click="addEl('email')">Email</button>
											<button type="button" class="fc-btn small light" ng-click="addEl('text')">One Line Text</button>
											<button type="button" class="fc-btn small light" ng-click="addEl('para')">Multi Line</button>
											<button type="button" class="fc-btn small light" ng-click="addEl('dropdown')">Dropdown</button>
											<button type="button" class="fc-btn small light" ng-click="addEl('date')">Date</button>
											<button type="button" class="fc-btn small light" ng-click="addEl('custom')" style="width: 52px">Text</button>
										</span>

										<span class='choose_from fc-btn-group'>
											<button type="button" class="fc-btn small light" ng-click="addEl('divider')">Divider</button>
											<ul class="dropdown-menu">
												<li><a ng-click="addEl('time12')">TimePicker (12H)</a></li>
												<li><a ng-click="addEl('time24')">TimePicker (24H)</a></li>
												<li><a ng-click="addEl('hidden')">Hidden Field</a></li>
												<li><a ng-click="addEl('password')">Password</a></li>
												<li><a ng-click="addEl('captcha')">Captcha</a></li>
												<li><a ng-click="addEl('image')">Image</a></li>
											</ul>												
											<button class="fc-btn small light dropdown-toggle" data-toggle="dropdown" href="#" style='width: 64px'>
												Other
												<span class="caret"></span>
											</button>
										</span>

										<span class="choose_from main fc-btn-group">
											<button type="button" class="fc-btn small light" ng-click="addEl('stars')">Star</button>
											<button type="button" class="fc-btn small light" ng-click="addEl('smiley')">Smiley</button>
											<button type="button" class="fc-btn small light" ng-click="addEl('thumbs')">Thumb</button>
											<ul class="dropdown-menu">
												<li><a ng-click="addEl('matrix2')">Two Column</a></li>
												<li><a ng-click="addEl('matrix3')">Three Column</a></li>
												<li><a ng-click="addEl('matrix4')">Four Column</a></li>
												<li><a ng-click="addEl('matrix5')">Five Column</a></li>
											</ul>
											<button class="fc-btn small light dropdown-toggle" data-toggle="dropdown" href="#">
												Choice Matrix
											</button>
										</span>
										<span class='choose_from fc-btn-group'>

											<button type="button" class="fc-btn small light" ng-click="addEl('upload')" style='width: 52px'>File</button>
											<button type="button" class="fc-btn small light" ng-click="addEl('check')" title='CheckBoxes'>CheckBox</button>
											<button type="button" class="fc-btn small light" ng-click="addEl('radio')" title='Radios'>MultiChoice</button>
											<ul class="dropdown-menu">
												<li><a ng-click="addEl('slider')">Slider</a></li>
												<li><a ng-click="addEl('slider-range')">Range</a></li>
											</ul>												
											<button class="fc-btn small light dropdown-toggle" data-toggle="dropdown" href="#" style='width: 58px'>
												Slider
											</button>
										</span>




									</div>
									<ul id='well' class='accordion'>


										<div style='position: relative; margin: 0' id='edit_title' class='nform_edit_div'>
											<li class='accordion-group'>
												<div class="accordion-heading">
													<div class="edit_head" href="#collapse_title" id='acc_title' data-parent='#well_accordion'>Form Title</div>
												</div>
												<div id="collapse_title" class="accordion-body">
													<div class="accordion-inner">
														<div class='opt_cl' style='padding-top: 12px; padding-left: 20px'>
															<label class='option_text' for='form_title_field' style='width: 150px; display: inline-block'>Form Title </label>
															<input class='mr' type='text' id='form_title_field' ng-model='con[0].form_title'>

															<br>

															<label class='option_text' for='form_title_px' style='width: 150px; display: inline-block'> Font Size </label>
															<input class='mr' type='number' min='1' id='form_title_px' ng-model='con[0].ft_px' style='width: 50px'>
															&nbsp;&nbsp;

														<!--<label for='title134' style='width: 150px; display: inline-block'>Font Style</label>
														<div class='select-cover' style='width: 150px'><select id='title134' ng-model='con[0].tfamily'>
															<option></option>
															<option>Arial</option>
															<option>Arial Black</option>
															<option>Courier New</option>
															<option>Times New Roman</option>
															<option>Trebuchet MS</option>
															<option>Verdana</option>
														</select></div>&nbsp;&nbsp;&nbsp;-->


														<label class='label_radio' for='tbold_1'><input id='tbold_1' type='radio' ng-model='con[0].tbold' value='bold' name='tbold'>
															<div class='label_div' style='background: #fff'>Bold</div>
														</label>
														<label class='label_radio' for='tbold_2'><input id='tbold_2' type='radio' ng-model='con[0].tbold' value='normal' name='tbold'>
															<div class='label_div' style='background: #fff'>Normal</div>
														</label>

														<br>

														<label class='option_text' style='width: 150px; display: inline-block'> Font Alignment </label>

														<label class='label_radio'>
															<input type='radio' ng-model='con[0].ftalign' value='left' name='ftalign'>
															<div class='label_div' style='background: #fff'>Left</div>
														</label>

														<label class='label_radio'>
															<input type='radio' ng-model='con[0].ftalign' value='center' name='ftalign'>
															<div class='label_div' style='background: #fff'>Center</div>
														</label>

														<label class='label_radio'>
															<input type='radio' ng-model='con[0].ftalign' value='right' name='ftalign'>
															<div class='label_div' style='background: #fff'>Right</div>
														</label>
														<br>

														<label class='option_text' style='width: 150px; display: inline-block'> Font Color </label>
														<input class='cpicker mr' ng-model='con[0].ft_co' style='width: 30px'>

														<br>

														<label class='option_text' style='width: 150px; display: inline-block'> Background Color </label>
														<input class='cpicker mr' ng-model='con[0].ft_bg' style='width: 30px'>
														<span class='description' style='width: 100px'>Set the form theme to <strong>none</strong> before using this.</span>


													</div>

												</div>
											</div>
										</li>
									</div>

									<div ng-repeat="el in build" style='position: relative; margin: 0' id='edit_{{$index}}' class='nform_edit_div'>
										<li id='{{$index}}' class='accordion-group'>
											<div class="accordion-heading" style='height: 38px'>

												<div class="edit_head" href="#collapse{{$index}}" compile="el.el_b" id='acc{{$index}}' data-parent='#well_accordion'></div>

												<button class='min-btn' id='{{$index}}' title='Minimize'><i class='formcraft-minus'></i></button>

												<button class='del-btn' id='db_{{$index}}' ng-click='remEl($index)' title='Delete Field'  ng-disabled="el.isDisabled"><i class='formcraft-trash'></i></button>
											</div>
											<div id="collapse{{$index}}" class="accordion-body">
												<div class="accordion-inner" compile="el.el_b2"></div>
											</div>
										</li>

									</div>
								</ul>
							</div><!-- End of affixed Part -->
						</div><!-- End of Left Part -->
					</td>
					<td style='vertical-align: top'>
						<div class="preview_form">

							<h4>You can <strong>drag</strong> and <strong>drop</strong> the fields to change their order.<br>Click to edit them.
								<a id='lp-button' href='<?php echo plugins_url().'/formcraft/form.php?id='.$id.'&preview=true'; ?>' target='_blank'>Live Preview (new window)</a>
							</h4>
							<?php
							$form_uniq = "fcSTARTID".substr(rand(),0,5)."ENDID_$id";
							$rand2 = "anchor_".substr(rand(),0,5);
							?>
							<div class='html_here'><?php $form_id = "123$id"; ?><form id='<?php echo $form_uniq; ?>' class="a_<?php echo $form_id; ?> nform {{con[0].cl_hidden_fields}} {{con[0].user_save_form}} {{con[0].placeholder}} {{con[0].frame}} {{con[0].direction}} {{con[0].theme}} {{con[0].number_spin}} {{con[0].check_no_conflict}} {{con[0].allow_multi}} {{con[0].themev}} {{con[0].themef}} {{con[0].block_label}} star_{{con[0].show_star_validation}} {{con[0].flayout}} fc-common" ng-style='{background: con[0].bg_image, width: con[0].fw, borderRadius: con[0].fr+"px", MozBorderRadius: con[0].fr+"px", WebkitBorderRadius: con[0].fr+"px", fontFamily: con[0].formfamily}'><input type='hidden' class='form_id' val='<?php echo $id; ?>' ng-model='con[0].form_main_id'><input type='hidden' class='getlocation' val='' name='location_hidden__0_0_1000_'><a id='<?php echo $rand2; ?>_anchor'></a><a href='#<?php echo $rand2; ?>_anchor' class='anchor_trigger'></a><div id='fe_title' class='nform_li form_title {{con[0].theme}}' ng-style='{fontSize: con[0].ft_px+"px", borderTopLeftRadius: con[0].fr+"px", MozBorderTopLeftRadius: con[0].fr+"px", WebkitBorderTopLeftRadius: con[0].fr+"px", borderTopRightRadius: con[0].fr+"px", MozBorderTopRightRadius: con[0].fr+"px", WebkitBorderTopRightRadius: con[0].fr+"px", color: con[0].ft_co, backgroundColor: con[0].ft_bg, fontFamily: con[0].tfamily, fontWeight: con[0].tbold, backgroundImage: con[0].bg_image, textAlign: con[0].ftalign}'>{{con[0].form_title}}</div><ul class='form_ul {{con[0].theme}}' id='form_ul'><li ng-repeat="el in build" ng-style='{paddingBottom: con[0].space+"px", paddingTop: con[0].space+"px"}' id='fe_{{$index}}_<?php echo $form_id; ?>' class='nform_li' ng-class='[el.default, el.inline, con[0].field_align, el.li_class, "fe_"+$index, "required-"+el.req]' scale><div compile="el.el_f" ng-style='{marginBottom: el.divspa, marginTop: el.divspa}'></div><span class='element_id'>{{$index}}</span></ul><div id='fe_submit' class='form_submit' style='position: relative'><div class='res_div'><span class='nform_res'></span></div><span style='text-align: center; display: inline; padding-top: 8px; padding-bottom: 4px'><a class='ref_link' ng-href='http://codecanyon.net/item/formcraft-premium-wordpress-form-builder/5335056?ref={{con[0].ruser}}' compile="con[0].rlink" target='_blank' title='Opens in a new tab'></a><!--START--><!--END--></span></div><input type='text' name='name' id='waspnet' value=''></form>
						</div>
					</div><!-- End of Right Part -->
				</td>
			</tr>
		</table>
	</div><!-- End of Cover -->
