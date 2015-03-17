<?php

global $wpdb, $fc_mse;


/* Authenticate */
$verified = true;
if (!get_option( 'fc_license' ))
{
	echo '<h3 class="fc-notice">Purchase Key Does not Exist. Add one using <strong>Others -> Purchase Code</strong></h3>';
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
		echo '<h3 class="fc-notice">'.$response['failed'].'</h3>';
		$verified = false;
	}
}

if (function_exists('is_multisite') && is_multisite() && $fc_mse!=true)
{
	echo "<h3 class='fc-notice'>FormCraft is not compatible with WordPress Multi Site. Please <a href='mailto:nish@ncrafts.net'>contact us</a> if you are looking for a multi-site deployment.</h3>";
}
if(!function_exists('curl_version'))
{
	echo "<h3 class='fc-notice'>Please install / enable cURL on your PHP installation.<br>If you don't know how to do this, please contact your web host.</h3>";
	die();
}


$table_builder = $wpdb->prefix . "formcraft_builder";
$table_subs = $wpdb->prefix . "formcraft_submissions";

$today_is = date('d M Y ');
$month_is = date('M Y ');


if (site_url()=='http://ncrafts.net/formcraft')
{
$myrows = $wpdb->get_results( "SELECT id,name,description,added,views,submits FROM $table_builder ORDER BY id LIMIT 0,50" );
}
else
{
$myrows = $wpdb->get_results( "SELECT id,name,description,added,views,submits FROM $table_builder ORDER BY id" );	
}

$totalSubAll = $wpdb->get_results( "SELECT COUNT(*) FROM $table_subs", 'ARRAY_A' );
$totalSubAllToday = $wpdb->get_results( "SELECT COUNT(*) FROM $table_subs WHERE added LIKE '$today_is%' ", 'ARRAY_A' );
$totalSubAllMonth = $wpdb->get_results( "SELECT COUNT(*) FROM $table_subs WHERE added LIKE '%$month_is%' ", 'ARRAY_A' );
$totalSubSeen = $wpdb->get_results( "SELECT COUNT(*) FROM $table_subs WHERE seen='1'", 'ARRAY_A' );

$totalSubAll = intval($totalSubAll[0]['COUNT(*)']);
$totalSubAllToday = intval($totalSubAllToday[0]['COUNT(*)']);
$totalSubAllMonth = intval($totalSubAllMonth[0]['COUNT(*)']);
$totalSubSeen = intval($totalSubSeen[0]['COUNT(*)']);


$mysub = $wpdb->get_results( "SELECT * FROM $table_subs ORDER BY id LIMIT 0,10", 'ARRAY_A' );
$mysubr = $wpdb->get_results( "SELECT * FROM $table_subs WHERE seen='1'", 'ARRAY_A' );


?>

<script>
	function PrintElem(elem)
	{
		Popup(jQuery(elem).html());
	}

	function Popup(data) 
	{
		var mywindow = window.open('', 'my div', 'height=400,width=600');
		mywindow.document.write('<html><title>FormCraft Submission</title>');
		mywindow.document.write('<body>');
		mywindow.document.write(data);
		mywindow.document.write('</body></html>');
		mywindow.document.close();
		mywindow.focus();
		mywindow.print();
		mywindow.close();
		return true;
	}
</script>

<script>
	var previousPoint = null;

	function showTooltip(x, y, contents) {

		jQuery("<div id='tooltip'>" + contents + "</div>").css({
			position: "absolute",
			display: "none",
			top: y - 10,
			left: x + 10,
			padding: "3px 4px",
			fontSize: "11px",
			fontWeight: "300",
			border: "1px solid #3970C4",
			"background-color": "#4488ee",
			"border-radius": "2px",
			"-moz-border-radius": "2px",
			"-webkit-border-radius": "2px",
			"color": "#fff",
			opacity: 0.90
		}).appendTo("body").fadeIn(200);
	}


	jQuery(document).ready(function(){




	});
</script>

<script>

	jQuery(document).ready(function () {

		if ((document.domain=='ncrafts.net') || (document.domain=='www.ncrafts.net'))
		{

			setTimeout(function() 
			{
				jQuery('#new_form_pop').trigger('click');
			},10);
		}

	});


</script>

<!--
<a data-target="#fd1f3" data-toggle="fcmodal">Inline</a>
<div class="fcmodal fcfade" id="fd1f3" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="fcmodal-dialog" style="width: 440px;">
		<div class="fcmodal-content">
			<div class="fcclose">×</div>	
			<div class="fcmodal-body" style="padding: 30px 60px 60px 60px">
				sas
			</div>
		</div>
	</div>
</div>
-->

<div class="ffcover_add fc-common">


	<div id="title_div">	
		<h1>FormCraft <span>2.0</span></h1>
		<a class='docs_title' href='http://ncrafts.net/formcraft/docs/table-of-contents/' target='_blank'>Complete Online Guide</a>
		<a class='docs_title' href='<?php echo plugins_url('formcraft/documentation.html'); ?>' target='_blank'>Documentation</a>
		<a class='docs_title' href='http://ncrafts.net' target='_blank'>nCrafts</a>
	</div>





	<div class="fcmodal fcfade" id="new_form" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="fcmodal-dialog" style="width: 640px;">
			<form class="fcmodal-content" id='new_form' style='width: 640px; top: 20%'>
				<div class="fcclose">×</div>	
				<div class="fcmodal-header">
					Add Form
				</div>
				<div class="fcmodal-body">

					<div style='margin-bottom: 12px'>
						<label class='label_radio circle-ticked'  style='width: 100px'>
							<input type='radio' value='new' checked name='type_form'><div class='label_div' style='background: #fff'>
							New Form</div>
						</label>
					</div>

					
					<div style='margin-bottom: 12px'>
						<label class='label_radio circle-ticked' style='width: 100px'>
							<input type='radio' value='duplicate' name='type_form' id='rand_aa'><div class='label_div' style='background: #fff'>Duplicate</div>
						</label>
						<div class='select-cover' style='margin-left: 0px'>
						<select name='duplicate' style='min-width: 200px' id='rand_a'>
							<?php foreach ($myrows as $row)
							{
								?>
								<option value='<?php echo $row->id; ?>'><?php echo $row->name; ?></option>
								<?php
							}
							?>
						</select>
						</div>
					</div>

					<div style='position: relative'>
						<label class='label_radio circle-ticked' style='width: 100px'>
							<input type='radio' value='import' name='type_form' id='rand_b'><div class='label_div' style='background: #fff'>Import</div>
						</label>

						<span class='fileupload-cover fc-btn'>
							<input id='import' type="file" name="files[]" data-url="<?php echo plugins_url('formcraft/file-upload/server/content/upload.php'); ?>">
							<span id='fu-label'><i class='formcraft-upload'></i>Upload Template</span>
						</span>
						<input type='hidden' id='import_form' name='import_form' val=''>

						&nbsp;<a style='font-size: 12px; text-decoration: none' href='http://ncrafts.net/formcraft/uncategorized/download-form-templates/' target='_blank'>get form templates</a>

					</div>
				</div>
				<hr>
				<div class='fcmodal-body'>
					<input name='name' id='new_name' type='text' autofocus placeholder='Form Name' style='width: 220px'>
					<br>
					<textarea name='desc' id='new_desc' rows='4' placeholder='Description' style='width: 220px'></textarea>
				</div>
				<div class="fcmodal-footer">
					<span class='response_ajax'></span>
					<button type="submit" id='submit_new_btn' class="fc-btn"><i class='icon-plus icon-white'></i> Add Form</button>
				</div>
			</form>
		</div>
	</div>




	<?php 
	$saw['today'] = 0;
	$saw['month'] = 0;

	foreach ($mysub as $key => $row) 
	{

		$dt = date_parse($row['added']);
		$date = date_parse(date('d M Y (H:m)'));

		if ($dt['month']==$date['month'] && $dt['day']==$date['day'] && $dt['year']==$date['year'])
		{
			$saw['today']++;
		}
		if ($dt['month']==$date['month'] && $dt['year']==$date['year'])
		{
			$saw['month']++;
		}
	} 

	?>



	<ul class='nav nav-main'>
		<table cellspacing='0' cellpadding='0'><tr>
			<td><li class='active'>
				<a><i class="formcraft-chart-bar" style='font-size: 1.2em'></i> Dashboard</a>
			</li></td>
			<td><li>
				<a><i class="formcraft-edit" style='font-size: 1em'></i> Forms</a>
			</li></td>
			<td><li>
				<a id='stab'><i class="formcraft-list" style='font-size: 1em'></i> Submissions <span style='color: green'>(<?php echo $totalSubAll-$totalSubSeen; ?>)</span></a>
			</li></td>
			<td><li>
				<a><i class="formcraft-upload" style='font-size: 1em'></i> File Manager</a>
			</li></td>
			<td><li>
				<a><i class="formcraft-puzzle" style='font-size: 1em'></i> Add-Ons</a>
			</li></td>
			<td><li>
				<a><i class="formcraft-plus" style='font-size: 1em'></i> Others</a>
			</li></td>			
		</tr></table>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="home">
			<div class='charts'>

				<div style='height: 50px; margin: 30px 0 15px 0'>
				<div style='float: right; margin-right: 2%'>
					show from <input type='text' class='datepicker-field' value='<?php echo date('Y/m/d', strtotime('-15days')); ?>' data-date-format='yyyy/mm/dd' id='chart-from'> to 
					<input type='text' class='datepicker-field' value='<?php echo date('Y/m/d', strtotime('now')); ?>' data-date-format='yyyy/mm/dd' id='chart-to'> for 
					<div class='select-cover' style='vertical-align: middle'>
						<select id='stats_select'>
							<option value='all'>All Forms</option>
							<?php foreach ($myrows as $row) { ?>
							<option value='<?php echo $row->id; ?>'><?php echo $row->name; ?></option>
							<?php } ?>
						</select>
						</div>
					</div>
				<div class='spin-cover'>
					<span class='spin-row'><span class='number' id='tvs'>99</span><span> views</span></span> 
					<span class='spin-row'><span class='number' id='tss'>123</span><span> submissions</span></span> 
					<span class='spin-row'><span class='number' id='tcs'>12.12%</span><span> conversion</span></span>
				</div>
				</div>

				<div id='chart-cover'><div id='chart-inner'></div></div>
			</div>
		</div>
		<div class="tab-pane" id="forms">		

			<div class='group_cover'>

				<a class='fc-btn large' style='margin-left: 10px; margin-bottom: 10px' data-target="#new_form" data-toggle="fcmodal"><i class='icon-plus icon-white'></i> Add Form</a>						

				<div id='existing_forms'>
					<div class='subs_wrapper'>
						<table style='' class='table' id='ext' cellspacing="0" cellpadding="0">
							<thead>
								<tr>
									<th width='1%' style='text-align: center; width: 5px'>ID</th>
									<th width='29%'>Name of Form</th>
									<th width='22%'>Description</th>
									<th width='14%' style='text-align: center'>Shortcode</th>
									<th width='7%' style='text-align: center'>Views</th>
									<th width='10%' style='text-align: center'>Submissions</th>
									<th width='13%' style='text-align: center'>Date Added</th>
									<th width='5%' style='text-align: center'></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($myrows as $row) {
									?>
									<tr id='<?php echo $row->id; ?>'>
										<td class='row_click' style='text-align: center'><?php echo $row->id; ?></td>

										<td class='row_click'><a class='rand' href='admin.php?page=formcraft_admin&id=<?php echo $row->id; ?>'><?php echo $row->name; ?></a><input class="rand2" style="width: 110px; display:none; margin-right: 6px" type="text" value="<?php echo $row->name; ?>"><a class='btn edit_btn' title='Edit Form Name' id='edit_<?php echo $row->id; ?>'>edit name</a><a class='btn save_btn' id='edit_<?php echo $row->id; ?>'>save</a></td>

										<td class='row_click row_description'><a  class='rand'><?php echo $row->description; ?></a></td>

										<td style='text-align: center; border-right: 1px solid #eee; padding: 0'><textarea class='tread' rows='1' onclick="this.focus();this.select()" readonly="readonly">[formcraft id='<?php echo $row->id; ?>']</textarea></td>
										<td class='row_click' style='text-align: center'><?php echo $row->views; ?></td>
										<td class='row_click' style='text-align: center'><?php echo $row->submits; ?></td>
										<td class='row_click'><?php echo $row->added; ?></td>
										<td style='text-align: center; border-right: 1px solid #eee'>

											<a class='delete-row btn-danger' data-loading='...' data-complete="<i class='formcraft-ok'></i>" data-reset="<i class='formcraft-trash'></i>" id='delete_<?php echo $row->id; ?>' title='Delete this form'><i class='formcraft-trash'></i>
											</a>

										</td>
									</tr>
									<?php } ?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="submissions">			
				<div class='group_cover'>
					<div style='border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 10px'>
						<span class='stat'>
							<span class='unr_msg' id='unr_ind'><?php echo $totalSubAll-$totalSubSeen; ?>
							</span> unread&nbsp;&nbsp;
							<span class='tot_msg' id='tot_ind'><?php echo $totalSubAll; ?>
							</span> total	
						</span>
						<span class='stat'>
							<span class='unr_msg'><?php echo $totalSubAllToday; ?>
							</span> new today&nbsp;&nbsp;
							<span class='tot_msg'><?php echo $totalSubAllMonth; ?>
							</span> new this month
						</span>

						<form id='subs_search'>
							<input type='text' id='search_query' name='search' placeholder='Search Submissions'>
						</form>	

						<span style='display: inline-block'>
							<strong>Export Submissions for </strong>
							<div class='select-cover' style='vertical-align: middle'>
							<select id='export_select' style='height: 33px; min-width: 140px'>
								<option value='0'>All Forms</option>
								<?php foreach ($myrows as $row) { ?>
								<option value='<?php echo $row->id; ?>'><?php echo $row->name; ?></option>
								<?php } ?>
							</select>
							</div>
							<a target='_blank' id='export_url' href='<?php echo plugins_url('/formcraft/php/export.php?id=0'); ?>' class='fc-btn'>export</a>					
						</span>
					</div>

					<div id='subs_c' >

						<div class='fc_pagination'>

							<?php

							$pages = ceil($totalSubAll / 10);
							$i = 1;

							while ($i<=$pages)
							{
								echo "<span class='page' id='fc-page-$i'>$i</span>" ;
								$i++;
							}

							?>

						</div>

						<table style='' class='table' id='subs' cellspacing="0" cellpadding="0">
							<thead>
								<tr>
									<th width="10%" title='Click to sort'>ID</th>
									<th width="10%" title='Click to sort'>Read</th>
									<th width="20%" title='Click to sort'>Date</th>
									<th width="30%" title='Click to sort'>Form Name</th>
									<th width="20%" title='Click to sort'>Message</th>
									<th width="10%" title='Click to sort'>Options</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="files">				
				<?php
				
				if (function_exists('is_multisite') && is_multisite())
				{
					$url = plugins_url("formcraft/file-upload/server/content/files/".$wpdb->blogid."/info.txt");					
				}
				else
				{
					$url = plugins_url("formcraft/file-upload/server/content/files/info.txt");					
				}

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$read = curl_exec($ch);
				curl_close($ch);
				$read = json_decode($read, 1);

				$dir = get_home_path().'wp-content/plugins/formcraft/file-upload/server/php/files/';
				$otherFiles = scandir($dir);
				$extra=array();
				$i = 1;
				foreach ($otherFiles as $key => $value)
				{
					if(substr($value, 0,1)=='.')continue;
					if($value=='info.txt')continue;
					if($value=='thumbnail')continue;
					$extra[$i]['name'] = $value;
					$extra[$i]['url'] = plugins_url("formcraft/file-upload/server/php/files/").$value;
					$i++;
				}
				unset($i);

				?>

				<div class='group_cover'>

					<span class='stat' style='border: none'>
						<span class='unr_msg' id='unr_ind'><?php echo sizeof($read['files'])?>
						</span> files&nbsp;&nbsp;
					</span>

					<div id='files_c' >
						<div class='subs_wrapper'>

							<table cellpadding='0' cellspacing='0' style='' class='table' id='files_manager_table'>
								<thead>
									<tr>
										<th width="20%">Name</th>
										<th width="10%">Size</th>
										<th width="59%">Url</th>
										<th width="6%">Delete</th>
									</tr>
								</thead>
								<?php
								foreach ($read as $key => $value) 
								{
									$value = json_decode($value,1);

									?>
									<tr>
										<td><?php echo $value['name']; ?></td>
										<td><?php echo round(($value['size']/1024),2); ?> KB</td>
										<td><a href='<?php echo $value['full-url']; ?>' target='_blank'><?php echo $value['full-url']; ?></a></td>
										<td><a class='btn-danger delete_from_manager' style='width: 38px' data-loading='...' data-key='<?php echo $value['new_name']; ?>' data-complete='<i class="formcraft-ok"></i>' id='del_fm_<?php echo $key ?>'><i class='formcraft-trash'></i></a></td>
									</tr>
									<?php } ?>

									<?php
									foreach ($extra as $key => $value) 
									{
										if ($value['name']=='index.php') { continue; }
										?>
										<tr>
											<td><?php echo $value['name']; ?></td>
											<td>?? KB</td>
											<td><a href='<?php echo $value['url']; ?>' target='_blank'><?php echo $value['url']; ?></a></td>
											<td><a class='btn-danger delete_from_manager' style='width: 38px' data-loading='...' data-name='<?php echo $value['name']; ?>' data-complete='<i class="formcraft-ok"></i>' id='del_fm_<?php echo $key ?>'><i class='formcraft-trash'></i></a></td>
										</tr>
										<?php } ?>									

									</table>
								</div>
							</div>
						</div>
					</div>



					<div class="tab-pane" id="add">
						<?php

						if (defined('FORMCRAFT_ADD'))
						{
							formcraft_add_content();
						}
						else
						{
							?>
							<br>
							<div style='width: 100%; margin: auto auto; text-align: center; font-size: 24px; color: #666; font-weight: 300; line-height: 132%'>
								Install free <a href='http://wordpress.org/plugins/formcraft-add-on-pack/' target='_blank'>FormCraft Add-On Pack</a><br>
								<span style='font-size: 18px'>
									(MailChimp, AWeber and Campaign Monitor integration)</span>
								</div>

								<?php
							}

							?>


						</div>
						<div class='tab-pane'>
							<form id='fc-pk'>
								<input type='text' class='general' id='fc-pk-input' value=''>
								<button class='fc-btn medium' type='submit' style='margin-top: 0px'>Verify</button>
								<div class='response' style='display: block'></div>
								<p><br>
								<a style='font-size: 12px' target='_blank' href='http://ncrafts.net/blog/2014/05/where-to-find-the-purchase-code-of-items/'>Where to find the Purchase Key?</a>
								<br>
								<a style='font-size: 12px' target='_blank' href='http://ncrafts.net/formcraft/docs/purchase-key/'>read more</a>
								</p>
							</form>							
						</div>

					</div>

				</div><!-- End of Cover -->

				<div class="fcmodal fcfade" id="view_modal" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="fcmodal-dialog" style="width: 600px;">
						<div class="fcmodal-content">
							<div class="fcclose">×</div>	
							<div id='print_area'>
							</div>
							<div class="fcmodal-footer" style="margin-top: 0px">
								<button value="Print Div" class='fc-btn' onclick="PrintElem('#print_area')" />Print
							</button>
						</div>							
					</div>
				</div>
