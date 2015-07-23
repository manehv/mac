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

global $armainhelper, $arformhelper, $arrecordhelper, $arrecordcontroller;
	
/*if (!isset($_GET['form'])){ 
	echo '<script>location.href="admin.php?page=ARForms-entries&form=-1";</script>';
}*/

$_GET['form'] = isset( $_GET['form'] ) ? $_GET['form'] : -1;

function getBrowser($user_agent) 
	{ 
		$u_agent = $user_agent; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
	
		//First get the platform?
		if (@preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (@preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (@preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		
		// Next get the name of the useragent yes seperately and for good reason
		if(@preg_match('/MSIE/i',$u_agent) && !@preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		} 
		elseif(@preg_match('/Firefox/i',$u_agent)) 
		{ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} 
		elseif(@preg_match('/Chrome/i',$u_agent)) 
		{ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} 
		elseif(@preg_match('/Safari/i',$u_agent)) 
		{ 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		} 
		elseif(@preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} 
		elseif(@preg_match('/Netscape/i',$u_agent)) 
		{ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
		
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!@preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
		
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	} 


$actions = array( 'bulk_delete' => __('Delete', 'ARForms'));
	      
$actions['bulk_csv'] = __('Export to CSV', 'ARForms');

?>
<style type="text/css" title="currentStyle">
	@import "<?php echo ARFURL; ?>/datatables/media/css/demo_page.css";
	@import "<?php echo ARFURL; ?>/datatables/media/css/demo_table_jui.css";
	@import "<?php echo ARFURL; ?>/datatables/media/css/jquery-ui-1.8.4.custom.css";
	@import "<?php echo ARFURL; ?>/datatables/media/css/ColVis.css";
	
</style>
<style>
.chart_previous{
	float:right;
	text-decoration:underline;
}
.chart_next{
	float:right;
	text-decoration:underline;
}
</style>
<style type="text/css">
	
	#poststuff #post-body {
		margin-top: 35px !important;
	}
	
	#post-body {
	background:none;
	}
    </style>
<?php

if( isset($form->id) &&  $form->id == '-1') {				
				$form_cols 	= array();
				$items		= array();
}

if(isset($form->id) and ($form->id != '-1' || $form->id !='') ){

$form_cols	= apply_filters('arfpredisplayformcols', $form_cols, $form->id);
$items		= apply_filters('arfpredisplaycolsitems', $items, $form->id);

$action_no = 0;  

$default_hide = array(
				'0' => '',
				'1' => 'ID',		
				);
		
		if( count($form_cols) > 0 ){
		
			for($i=2; 1+count($form_cols) >= $i; $i++){
				$j = $i-2;
				$default_hide[$i] = $armainhelper->truncate($form_cols[$j]->name, 40); 	
			}
			$default_hide[$i] = 'Entry Key';
			$default_hide[$i+1] = 'Entry creation date';
			$default_hide[$i+2] = 'Browser Name';
			$default_hide[$i+3] = 'IP Address';
			$default_hide[$i+4] = 'Country';
			$default_hide[$i+5] = 'Action';
			$action_no = $i+5;
		}
		else
		{
			$default_hide['2'] = 'Entry Key';
			$default_hide['3'] = 'Entry creation date';
			$default_hide['4'] = 'Browser Name';
			$default_hide['5'] = 'IP Address';
			$default_hide['6'] = 'Country';
			$default_hide['7'] = 'Action';
			$action_no = 7;
		}



global $wpdb;


	$page_params = "&action=0&arfaction=0&form=";


	$page_params .= ($form) ? $form->id : 0;



	if ( ! empty( $_REQUEST['fid'] ) )


		$page_params .= '&fid='. $_REQUEST['fid'];


	
		$item_vars = $this->get_sort_vars($params, $where_clause);
						

		$page_params .= ($page_params_ov) ? $page_params_ov : $item_vars['page_params'];



		if($form){

		}else{


			$form_cols = array();


			$record_where = $item_vars['where_clause'];


		}
		
$columns_list_res = $wpdb->get_results( $wpdb->prepare('SELECT columns_list FROM '.$wpdb->prefix.'arf_forms WHERE id = %d', $form->id), ARRAY_A);
$columns_list_res = $columns_list_res[0];

$columns_list = maybe_unserialize($columns_list_res['columns_list']);
$is_colmn_array = is_array($columns_list);

$exclude = '';

	
	$exclude_array = "";	
			if( count($columns_list) > 0 and $columns_list != '' ) {
			
				foreach($columns_list as $keys => $column){
				
					foreach($default_hide as $key => $val ){

						if($column == $val)
						{
							if($exclude_array=="")
							{
								$exclude_array[] = $key;
							}
							else
							{
								if(!in_array($key,$exclude_array)){
									$exclude_array[] = $key;
									
									$exclude_no++;
								}
							}
						}
					}
				
				}
			}
	
	
	$ipcolumn = ($action_no - 2);
	 
	if( $exclude_array=="" and !$is_colmn_array ){
		$exclude_array = array($ipcolumn);
	}else if( is_array($exclude_array) and !in_array($ipcolumn, $exclude_array) and !$is_colmn_array )	{
		array_push($exclude_array, $ipcolumn);
	}
} else {
	
	$action_no = 7;
	$exclude_array = array(5);
}

if(isset($exclude_array) and $exclude_array!="")
		{
			$exclude = implode(",",$exclude_array);
		}	



wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');

global $style_settings;

$wp_format_date = get_option('date_format');
					
							if( $wp_format_date == 'F j, Y' || $wp_format_date =='m/d/Y' ) {
								$date_format_new = 'mm/dd/yy';
							} else if( $wp_format_date == 'd/m/Y' ) {
								$date_format_new = 'dd/mm/yy';
							} else if( $wp_format_date == 'Y/m/d' ) {
								$date_format_new = 'dd/mm/yy';
							} else {
								$date_format_new = 'mm/dd/yy';
							}

							
	global $arf_entries_action_column_width;
?>

<script type="text/javascript" charset="utf-8">

// <![CDATA[
jQuery(document).ready( function () {

jQuery.datepicker.setDefaults(jQuery.datepicker.regional['']);

jQuery("#datepicker_from").datepicker(jQuery.extend(jQuery.datepicker.regional['<?php echo (isset($options['locale'])) ? $options['locale'] : ''; ?>'], {dateFormat:'<?php echo $date_format_new; ?>',changeMonth:false,changeYear:false,yearRange:'<?php echo '1970' .':'. '2050' ?>'}));

jQuery("#datepicker_to").datepicker(jQuery.extend(jQuery.datepicker.regional['<?php echo (isset($options['locale'])) ? $options['locale'] : ''; ?>'], {dateFormat:'<?php echo $date_format_new; ?>',changeMonth:false,changeYear:false,yearRange:'<?php echo '1970' .':'. '2050' ?>'}));



	jQuery.fn.dataTableExt.oPagination.four_button = {
		
		"fnInit": function ( oSettings, nPaging, fnCallbackDraw )
		{	
			nFirst = document.createElement( 'span' );
			nPrevious = document.createElement( 'span' );
			
			
			var nInput = document.createElement( 'input' );
			var nPage = document.createElement( 'span' );
			var nOf = document.createElement( 'span' );
			nOf.className = "paginate_of";
			nInput.className = "current_page_no";
			nPage.className = "paginate_page";
			nInput.type = "text";
			nInput.style.width = "40px";
			nInput.style.height = "26px";
			nInput.style.display = "inline";
			
			 
			nPaging.appendChild( nPage );
			
			 
			 
			jQuery(nInput).keyup( function (e) {
						 
				if ( e.which == 38 || e.which == 39 )
				{
					this.value++;
				}
				else if ( (e.which == 37 || e.which == 40) && this.value > 1 )
				{
					this.value--;
				}
	 
				if ( this.value == "" || this.value.match(/[^0-9]/) )
				{
					
					return;
				}
	 
				var iNewStart = oSettings._iDisplayLength * (this.value - 1);
				if ( iNewStart > oSettings.fnRecordsDisplay() )
				{
					
					oSettings._iDisplayStart = (Math.ceil((oSettings.fnRecordsDisplay()-1) /
						oSettings._iDisplayLength)-1) * oSettings._iDisplayLength;
					fnCallbackDraw( oSettings );
					return;
				}
	 
				oSettings._iDisplayStart = iNewStart;
				fnCallbackDraw( oSettings );
			} );
	 
			
			
			
			nNext = document.createElement( 'span' );
			nLast = document.createElement( 'span' );
			var nFirst = document.createElement( 'span' );
			var nPrevious = document.createElement( 'span' );
			var nPage = document.createElement( 'span' );
			var nOf = document.createElement( 'span' );
			
			<?php
				if(is_rtl())
				{
			?>
					nNext.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/next_normal-icon_rtl.png')";
			<?php
				}
				else
				{
			?>
					nNext.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/next_normal-icon.png')";
			<?php
				}
			?>
			nNext.style.backgroundRepeat = "no-repeat";
			nNext.style.backgroundPosition = "center";
			nNext.title = "Next";
			
			<?php
				if(is_rtl())
				{
			?>
					nLast.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/last_normal-icon_rtl.png')";
			<?php
				}
				else
				{
			?>
					nLast.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/last_normal-icon.png')";
			<?php
				}
			?>
			nLast.style.backgroundRepeat = "no-repeat";
			nLast.style.backgroundPosition = "center";
			nLast.title = "Last";
			
			<?php
				if(is_rtl())
				{
			?>
					nFirst.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/first_normal-icon_rtl.png')";
			<?php
				}
				else
				{
			?>
					nFirst.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/first_normal-icon.png')";
			<?php
				}
			?>
			nFirst.style.backgroundRepeat = "no-repeat";
			nFirst.style.backgroundPosition = "center";
			nFirst.title = "First";
			
			<?php
				if(is_rtl())
				{
			?>
					nPrevious.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/previous_normal-icon_rtl.png')";
			<?php
				}
				else
				{
			?>
					nPrevious.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/previous_normal-icon.png')";
			<?php
				}
			?>
			nPrevious.style.backgroundRepeat = "no-repeat";
			nPrevious.style.backgroundPosition = "center";
			nPrevious.title = "Previous";		
			
			
			nFirst.appendChild( document.createTextNode( ' ' ) );
			nPrevious.appendChild( document.createTextNode( ' ' ) );
			
			nLast.appendChild( document.createTextNode( ' ' ) );
			nNext.appendChild( document.createTextNode( ' ' ) );
			
			 
			
			nOf.className = "paginate_button nof";
			 
			nPaging.appendChild( nFirst );
			nPaging.appendChild( nPrevious );
			
			nPaging.appendChild( nInput );
			nPaging.appendChild( nOf );
			
			nPaging.appendChild( nNext );
			nPaging.appendChild( nLast );
			 
			jQuery(nFirst).click( function () {
				oSettings.oApi._fnPageChange( oSettings, "first" );
				fnCallbackDraw( oSettings );
			} );
			 
			jQuery(nPrevious).click( function() {
				oSettings.oApi._fnPageChange( oSettings, "previous" );
				fnCallbackDraw( oSettings );
			} );
			 
			jQuery(nNext).click( function() {
				oSettings.oApi._fnPageChange( oSettings, "next" );
				fnCallbackDraw( oSettings );
			} );
			 
			jQuery(nLast).click( function() {
				oSettings.oApi._fnPageChange( oSettings, "last" );
				fnCallbackDraw( oSettings );
			} );
			 
			
			jQuery(nFirst).bind( 'selectstart', function () { return false; } );
			jQuery(nPrevious).bind( 'selectstart', function () { return false; } );
			jQuery('span', nPaging).bind( 'mousedown', function () { return false; } );
			jQuery('span', nPaging).bind( 'selectstart', function () { return false; } );
			jQuery(nNext).bind( 'selectstart', function () { return false; } );
			jQuery(nLast).bind( 'selectstart', function () { return false; } );
		},
		 
		
		"fnUpdate": function ( oSettings, fnCallbackDraw )
		{
			if ( !oSettings.aanFeatures.p )
			{
				return;
			}
			 
			
			var an = oSettings.aanFeatures.p;
			for ( var i=0, iLen=an.length ; i<iLen ; i++ )
			{
				var buttons = an[i].getElementsByTagName('span');
				if ( oSettings._iDisplayStart === 0 )
				{
					
					buttons[1].className = "paginate_disabled_first arfhelptip";
					buttons[2].className = "paginate_disabled_previous arfhelptip";
				}
				else
				{
					
					buttons[1].className = "paginate_enabled_first arfhelptip";
					buttons[2].className = "paginate_enabled_previous arfhelptip";
				}
	
				if ( oSettings.fnDisplayEnd() == oSettings.fnRecordsDisplay() )
				{
					buttons[4].className = "paginate_disabled_next arfhelptip";
					buttons[5].className = "paginate_disabled_last arfhelptip";
				}
				else
				{
					
					buttons[4].className = "paginate_enabled_next arfhelptip";
					buttons[5].className = "paginate_enabled_last arfhelptip";
				}
				
				
				if ( !oSettings.aanFeatures.p )
				{
					return;
				}
				var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);
				
				var iCurrentPage = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;
		 		
				
				if(iPages == 0 && iCurrentPage == 1) { iPages = iPages + 1; }
				
				if(document.getElementById('of_grid')){
					of_grid = document.getElementById('of_grid').value;
				}else{
					of_grid = 'of';
				}
				var an = oSettings.aanFeatures.p;
				for ( var i=0, iLen=an.length ; i<iLen ; i++ )
				{
					var spans = an[i].getElementsByTagName('span');
					var inputs = an[i].getElementsByTagName('input');
					spans[spans.length-3].innerHTML =" "+of_grid+" "+iPages
					inputs[0].value = iCurrentPage;
				}
				
				
			}
		}
	}

	var oTables = jQuery('#example').dataTable( {
		"sDom": '<"H"lCfr>t<"footer"ip>',
		"sPaginationType": "four_button",
		"bJQueryUI": true,
		"bPaginate": true,
		"bAutoWidth": false,
		
		
		"sScrollX": "100%",
		
		"bScrollCollapse": true,
		"oColVis": {
		   "aiExclude": [ 0, <?php echo ( isset($action_no) ) ? $action_no : ''; ?> ]		
		},
		
		
		"aoColumnDefs": [
				{ "sType": "html", "bVisible": false, "aTargets": [<?php if(isset($exclude) and $exclude!='') echo $exclude; ?>] },
				{ "bSortable": false, "aTargets": [ 0, <?php echo ( isset($action_no) )? $action_no : ''; ?>] }
		],
		
	});
		
		new FixedColumns( oTables, {
			"iLeftColumns": 0,
			"iLeftWidth": 0,
			"iRightColumns": 1,
			"iRightWidth": <?php echo isset($arf_entries_action_column_width) ? $arf_entries_action_column_width : '120'; ?>,
		} ); 
});
			
	

jQuery('.ColVis_Button:not(.ColVis_MasterButton)').live('click',function(){
	var colsArray= jQuery('.ColVis_Button :checkbox').map(function(){
		
		return [[jQuery(this).parent().next('.ColVis_title').text(), this.checked ? 'visibile':'hidden']];
		
		
	}).get();
	
	var form = jQuery('#arfredirecttolist').val();
	
	if( form == '' ) return false;		
	
	jQuery.ajax({type:"POST",url:ajaxurl,data:"action=managecolumns&colsArray="+colsArray+"&form="+form,

	success:function(msg){ 
	
	}

	});
			
});

	
		
// ]]>

jQuery(document).ready( function () { 	
	
    jQuery("#cb-select-all-1").click(function () {
          jQuery('input[name="item-action[]"]').attr('checked', this.checked);
    });
 
    
    jQuery('input[name="item-action[]"]').click(function(){
 
        if(jQuery('input[name="item-action[]"]').length == jQuery('input[name="item-action[]"]:checked').length) {
            jQuery("#cb-select-all-1").attr("checked", "checked");
        } else {
            jQuery("#cb-select-all-1").removeAttr("checked");
        }
 
    });
	
});	

function show_form_settimgs(id1,id2)
{
	if(id1 == "analytics")
	{
		change_graph_new("monthly");
	}
	document.getElementById(id1).style.display = 'block';
	document.getElementById(id2).style.display = 'none';
	document.getElementById('arfcurrenttab').value = id1;
	
	jQuery('.'+id1).addClass('btn_sld').removeClass('tab-unselected');
	jQuery('#'+id1+'_img').attr('src', '<?php echo ARFIMAGESURL;?>/'+id1+'.png');
	jQuery('.'+id2).removeClass('btn_sld').addClass('tab-unselected');
	jQuery('#'+id2+'_img').attr('src', '<?php echo ARFIMAGESURL;?>/'+id2+'_hover.png');
}						
</script>

<div class="wrap frm_entries_page">

<span class="h2" style="padding-left:30px;padding-top:30px;position: absolute;"><?php _e('Form Entries', 'ARForms'); ?></span>

<div id="poststuff" class="metabox-holder">
	<div id="post-body">
		<div class="inside" style="background-color:#ffffff; margin-top:50px !important;">    
	       <div class="clear" style="height:30px;"></div>
           <div class="formsettings1" style="border-bottom-left-radius: 0px; border-bottom-right-radius: 0px; background-color:#ffffff;">
                <div class="setting_tabrow">
                    <div class="tab" style="background-color:#ffffff; padding-left:0px;">
                        <ul class="arfmainformnavigation" style="height:42px; padding-bottom:0px; margin-bottom:0px;">
                            <li class="form_entries btn_sld"> <a href="javascript:show_form_settimgs('form_entries','analytics');"><img id="form_entries_img" src="<?php echo ARFIMAGESURL;?>/form_entries.png" height="15" width="16" />&nbsp;&nbsp;<?php _e('Entries List', 'ARForms') ?></a></li>
                             <li class="analytics tab-unselected"> <a href="javascript:show_form_settimgs('analytics','form_entries');"><img id="analytics_img" src="<?php echo ARFIMAGESURL;?>/analytics_hover.png" height="15" width="16" />&nbsp;&nbsp;<?php _e('Analytics', 'ARForms') ?></a></li>
                        </ul>
                    </div>
                </div>
			</div>
            
            <div class="clear"></div>
			
            <div class="frm_settings_form">

				<input type="hidden" name="arfcurrenttab" id="arfcurrenttab" value="form_entries" />
        
    			<div id="form_entries" style="border-top:none; background-color:#FFFFFF; border-radius:5px 5px 5px 5px; padding-top:10px; padding-left:0px; padding-top: 30px; padding-bottom:1px;">

				<div class="arf_form_entry_select">
                <div class="arf_form_entry_select_sub">	
                <?php
					if(is_rtl())
					{
						$sel_frm_div = 'float:right;margin-top:15px;';
						$sel_frm_txt = 'float:right;text-align:right;width:65%;';
					}
					else
					{
						$sel_frm_div = 'float:left;margin-top:15px;';
						$sel_frm_txt = 'float:left;text-align:left;width:65%;';
					}
				?>
                    <div>
                        <div class="arf_form_entry_left"><?php _e('Select form','ARForms');?>:</div>
                        <div style=" <?php echo $sel_frm_txt; ?>" ><div class="sltstandard" style="float:none;"><?php $arformhelper->forms_dropdown('arfredirecttolist', $_GET['form'], __('Select Form', 'ARForms'), false,  "");?></div></div>
                    </div>
                    <?php
						if(is_rtl())
						{
							$sel_frm_date_wrap = 'float:right;text-align:right;width:65%';
							$sel_frm_sel_date = 'float:right;';
							$sel_frm_button = 'float:right;margin-top:15px;';
						}
						else
						{
							$sel_frm_date_wrap = 'float:left;text-align:left;width:65%';
							$sel_frm_sel_date = 'float:left;';
							$sel_frm_button = 'float:left;margin-top:15px;';
						}
					?>
                    <div style=" <?php echo $sel_frm_div; ?>">
                        <div class="arf_form_entry_left"><div><?php _e('Select date From','ARForms');?>:</div><div class="arf_form_entry_left_sub">(<?php _e('optional','ARForms');?>)</div></div>
                        <div style=" <?php echo $sel_frm_date_wrap; ?>">
                            <div style=" <?php echo $sel_frm_sel_date; ?>"><input type="text" class="txtstandardnew" id="datepicker_from" name="datepicker_from" style="vertical-align:middle; width:105px;" /></div> <div class="arfentrytitle"><?php _e('To','ARForms');?>:</div>&nbsp;&nbsp;<div style=" <?php echo $sel_frm_sel_date; ?>"><input type="text" class="txtstandardnew" id="datepicker_to" name="datepicker_to" style="vertical-align:middle;  width:105px;"/></div>
                       	
                    </div>
                            
                    <div style=" <?php echo $sel_frm_button; ?>">
                        <div class="arf_form_entry_left">&nbsp;</div>
                        <div style="float:left;text-align:left;"><button type="button" class="greensavebtn" style="width:103px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;" onclick="change_frm_entries();"><?php _e('Submit','ARForms');?></button></div>
                    </div>        
                       
                    <input type="hidden" name="please_select_form" id="please_select_form" value="<?php _e('Please select a form','ARForms');?>" />
                    </div>
                    <div style="clear:both;"></div>
                </div>    
                </div>
              <div style="clear:both; height:30px;"></div>
    				
              <?php do_action('arfbeforelistingentries'); ?>
                    
              <form method="get" id="list_entries_form" onsubmit="return apply_bulk_action();" style="float:left;width:100%;">
                    
                    <input type="hidden" name="page" value="ARForms-entries" />
                    
                    <input type="hidden" name="form" value="<?php echo ($form) ? $form->id : '-1'; ?>" />
                    
                    <input type="hidden" name="arfaction" value="list" />
                    
                    <input type="hidden" name="show_hide_columns" id="show_hide_columns" value="<?php _e('Show / Hide columns','ARForms');?>"/>
                    <input type="hidden" name="search_grid" id="search_grid" value="<?php _e('Search','ARForms');?>"/>
                    <input type="hidden" name="entries_grid" id="entries_grid" value="<?php _e('entries','ARForms');?>"/>
                    <input type="hidden" name="show_grid" id="show_grid" value="<?php _e('Show','ARForms');?>"/>
                    <input type="hidden" name="showing_grid" id="showing_grid" value="<?php _e('Showing','ARForms');?>"/>
                    <input type="hidden" name="to_grid" id="to_grid" value="<?php _e('to','ARForms');?>"/>
                    <input type="hidden" name="of_grid" id="of_grid" value="<?php _e('of','ARForms');?>"/>
                    <input type="hidden" name="no_match_record_grid" id="no_match_record_grid" value="<?php _e('No matching records found','ARForms');?>"/>
                    <input type="hidden" name="no_record_grid" id="no_record_grid" value="<?php _e('No data available in table','ARForms');?>"/>
                    <input type="hidden" name="filter_grid" id="filter_grid" value="<?php _e('filtered from','ARForms');?>"/>
	                <input type="hidden" name="totalwd_grid" id="totalwd_grid" value="<?php _e('total','ARForms');?>"/>
                    
                    <?php require(VIEWS_PATH.'/shared_errors.php'); ?>    
                    
                    <div class="alignleft actions">
                            <?php 
                            $two = '1';
                            echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two' id='action$two'>\n";
                            echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms') . "</option>\n";
                    
                            foreach ( $actions as $name => $title ) {
                                $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';
                    
                                echo "\t<option value='$name'$class>$title</option>\n";
                            }
                    
                            echo "</select></div>\n";
                    		echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__("Apply","ARForms").'" />';
                            echo "\n";
                            
                            ?>
                    </div>                                        
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                        <thead>
                            <tr> 
                                <th class="center" style="width:50px;"><div style="display:inline-block; position:relative;"><input id="cb-select-all-1" type="checkbox" class="chkstanard"><label for="cb-select-all-1"  class="cb-select-all"><span></span></label></div></th>
                                <th><?php _e('ID','ARForms');?></th>
                                <?php if(count($form_cols)>0){ foreach ($form_cols as $col){ ?> 	 
                                <th><?php echo $armainhelper->truncate($col->name, 40) ?></th>
                                <?php } } ?>
                                <th><?php _e('Entry Key','ARForms');?></th>
                                <th><?php _e('Entry creation date','ARForms');?></th>
                                <th><?php _e('Browser Name','ARForms');?></th>
                                <th><?php _e('IP Address','ARForms');?></th>
                                <th><?php _e('Country','ARForms');?></th>
                                <th class="col_action"><?php _e('Action','ARForms');?></th>  
                            </tr>
                        </thead>
                        <tbody>
                    <?php if(count($items) > 0) { foreach($items as $key => $item) {  ?>    
                            <tr>
                                <td class="center" style="width:50px;"><input id="cb-item-action-<?php echo $item->id;?>" class="chkstanard" type="checkbox" value="<?php echo $item->id;?>" name="item-action[]"><label for="cb-item-action-<?php echo $item->id;?>"><span></span></label></td>
                                <td><?php echo $item->id;?></td> 
                    <?php foreach ($form_cols as $col){ ?>
                    
                            <td>
                                <?php 
                    
                                $field_value = isset($item->metas[$col->id]) ? $item->metas[$col->id] : false;
                                
                    
                                $col->field_options = maybe_unserialize($col->field_options);
                                
                    
                                 echo $arrecordhelper->display_value($field_value, $col, array('type' => $col->type, 'truncate' => true, 'attachment_id' => $item->attachment_id, 'entry_id' => $item->id));  
                    
                                 ?>
                    
                            </td>
                    
                        <?php } ?>
                                <td><?php echo $item->entry_key;?></td>
                                <td><?php echo date(get_option('date_format'), strtotime($item->created_date));?></td>
                                <td><?php $browser_info = getBrowser($item->browser_info); echo $browser_info['name'].' (Version: '.$browser_info['version'].')'; ?></td>
                                <td><?php echo $item->ip_address;?></td>
                                <td><?php echo $item->country;?></td>		
                         	    <td class="col_action">
                                <div class="row-actions">  
                    
                                  <?php 
                                    
                                    if(is_rtl())
									{
										echo "<a href='javascript:void(0);' onclick='open_entry_thickbox({$item->id});'><img src='".ARFIMAGESURL."/view_icon23_rtl.png' class='arfhelptip' title=".__("View Entry","ARForms")." onmouseover=\"this.src='".ARFIMAGESURL."/view_icon23_hover_rtl.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/view_icon23_rtl.png';\" /></a>";
									}
									else
									{
									echo "<a href='javascript:void(0);' onclick='open_entry_thickbox({$item->id});'><img src='".ARFIMAGESURL."/view_icon23.png' class='arfhelptip' title=".__("View Entry","ARForms")." onmouseover=\"this.src='".ARFIMAGESURL."/view_icon23_hover.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/view_icon23.png';\" /></a>";
									}
                                    
                    			  do_action('arf_additional_action_entries',$item->id,$form->id);
								  
                                  $delete_link = "?page=ARForms-entries&arfaction=destroy&id={$item->id}";
                    
                    
                                  $delete_link .= "&form=".$params['form'];
                         
                    
                              
								
									if(is_rtl())
									{
										echo "<img src='".ARFIMAGESURL."/delete_icon223_rtl.png' class='arfhelptip' title=".__("Delete","ARForms")." onmouseover=\"this.src='".ARFIMAGESURL."/delete_icon223_hover_rtl.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/delete_icon223_rtl.png';\" onclick=\"ChangeID({$item->id}); arfchangedeletemodalwidth('arfdeletemodabox');\" data-toggle='arfmodal' href='#delete_form_message' style='cursor:pointer'/></a>";
									}
									else
									{
										echo "<img src='".ARFIMAGESURL."/delete_icon223.png' class='arfhelptip' title=".__("Delete","ARForms")." onmouseover=\"this.src='".ARFIMAGESURL."/delete_icon223_hover.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/delete_icon223.png';\" onclick=\"ChangeID({$item->id}); arfchangedeletemodalwidth('arfdeletemodabox');\" data-toggle='arfmodal' href='#delete_form_message' style='cursor:pointer'/></a>";
                    				}
                    				
									
									
									echo "<div id='view_entry_{$item->id}' style='display:none; max-height:540px; width:800px; left:20%;' class='arfviewentrymodal arfmodal arfhide arffade'>
										<div class='arfnewmodalclose' data-dismiss='arfmodal'><img src='".ARFIMAGESURL."/close-button.png' align='absmiddle' /></div>
    
                                        <div class='newform_modal_title_container'>
                                            <div class='newform_modal_title' style='text-align:center;'><img src='".ARFIMAGESURL."/view-entry-icon.png' align='absmiddle' />&nbsp;".__('VIEW ENTRY','ARForms')."</div>
                                        </div>	
    
								   		<div class='arfentry_modal_content'>".$arrecordcontroller->get_entries_list($item->id)."</div>
										
										<div style='clear:both;'></div>
										
										<div class='arfviewentryclose' data-dismiss='arfmodal'><img src='".ARFIMAGESURL."/close-btnicon.png' align='absmiddle' style='margin-right:10px;' />".__('Close', 'ARForms')."</div>
                                        
									</div>";
                                ?>
                    
                                </div>
                                </td>
                                  
                            </tr>
                    <?php } } ?>
                    <script type="text/javascript">
					function ChangeID(id)
					{
						document.getElementById('delete_entry_id').value = id;
					}
					</script>
                                   
                        </tbody>
                    </table>
                    
                    <div class="alignleft actions2">
                            <?php 
                            $two = '2';
                            echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two' id='action$two'>\n";
                            echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms') . "</option>\n";
                    
                            foreach ( $actions as $name => $title ) {
                                $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';
                    
                                echo "\t<option value='$name'$class>$title</option>\n";
                            }
                    
                            echo "</select></div>\n";
                    		
							echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__("Apply","ARForms").'" />';
                            echo "\n";
                            
                            ?>
                    </div>
                    <div class="footer_grid"></div> 
                    </form>
    				 
                    <?php do_action('arfafterlistingentries'); ?>
                                        
                    <div style="clear:both;"></div>
                    <br /><br />
				</div>
    
    	 		<div id="analytics" style="border-top:none; background-color:#FFFFFF; border-radius:5px 5px 5px 5px; padding-top:10px; padding-left:0px; padding-top: 30px; padding-bottom:1px;display:none;">
         			
                    <table width="100%" border="0" align="middle">
                    <tr>
                    	<td align="left" width="110px;" valign="middle"><div class="arfentrytitle" style="padding-top:0px;"><?php _e('Select form','ARForms');?> :</div></td>
                        <?php $form_id = isset($form_id) ? $form_id : ''; ?>
	                    <td align="left" width="100px;"><div class="sltstandard" style="float:left;"><?php $arformhelper->forms_dropdown('arfredirecttolist3', @$form_id, __('All Forms', 'ARForms'), false,  "change_graph_new('monthly')"); ?></div></td>
                        <?php
							if(is_rtl())
							{
								$analytic_time_label = 'float:left;';
							}
							else
							{
								$analytic_time_label = 'float:right;';
							}
						?>
                        <td align="right"><div class="sltstandard" style=" <?php echo $analytic_time_label; ?>">
                        	 <div style=" <?php echo $analytic_time_label; ?>">
                             <button id="yearly_unselected" onclick="javascript:change_graph_new('yearly');" class="btn_sld_yearly"><?php _e('Yearly','ARForms');?></button>
                            <button id="yearly_selected" onclick="javascript:change_graph_new('yearly');" class="btn_sld_yearly_selected"><?php _e('Yearly','ARForms');?></button>
                            </div>
                            <div style=" <?php echo $analytic_time_label; ?>">
                            
                            <button id="monthly_unselected" onclick="javascript:change_graph_new('monthly');" class="btn_sld_monthly"><?php _e('Monthly','ARForms');?></button>
                            <button id="monthly_selected" onclick="javascript:change_graph_new('monthly');" class="btn_sld_monthly_selected"><?php _e('Monthly','ARForms');?></button>
                            </div>
                            <div style=" <?php echo $analytic_time_label; ?>">
                        	
                            <button id="daily_unselected" onclick="javascript:change_graph_new('daily');" class="btn_sld_daily"><?php _e('Daily','ARForms');?></button>
                            <button id="daily_selected" onclick="javascript:change_graph_new('daily');" class="btn_sld_daily_selected"><?php _e('Daily','ARForms');?></button>
                            </div>
                        	
                    </div></td>
                    </tr>
					</table>    			 
                   <style type="text/css">
					.jqplot-xaxis { font-weight:bold; }
					.jqplot-yaxis { font-weight:bold; }
					.jqplot-highlighter { background-color:#333333; opacity:.70; filter:Alpha(Opacity=70); color:#FFFFFF; }
					.jqplot-highlighter .tooltip_title {font-weight:bold; color:#FFFFFF; width:50px; font-size:12px; }
					.jqplot-highlighter .tooltip_title1 {font-weight:bold; color:#FFFFFF; width:60px; font-size:12px; }
					</style>

                    
					<br /><br />
                     
                    <div id="chart_div">              
                    <div id="daily" style="padding:15px;">
                    <label class="lbltitle">Daily chart</label><br />
                    
                        <div id="chart2" style="width:100%;height:300px;" ></div>
                    
                    </div>                    
                    
                    <div id="monthly" style="padding:15px; display:none;">
                    <label class="lbltitle">Month chart</label><br />
                    
                        <div id="chart1" style="width:100%;height:300px;" ></div>
                    
                    </div>

					<div id="weekly" style="padding:15px; display:none;"} ?>">
                    <label class="lbltitle">Weekly chart</label><br />
                    
                        <div id="chart3" style="width:100%;height:300px;" ></div>
                    
                    </div>
                    
                    <div id="yearly" style="padding:15px; display:none;">
                    <label class="lbltitle">Yearly chart</label><br />
                    
                        <div id="chart4" style="width:100%;height:300px;" ></div>
                    
                    </div>					
                    <span class"lbltitle next_chart">Previous</span> <span class="lbltitle next_chart">Next</span>
					<br /><br />
                    
                    
               </div>
		</div>
		</div>
	</div>
	<?php
		if(is_rtl())
		{
			$doc_link_align = 'left';
		}
		else
		{
			$doc_link_align = 'right';
		}
    ?>
	<div class="documentation_link" style="background:none; background:none;"  align="<?php echo $doc_link_align; ?>"><a href="<?php echo ARFURL;?>/documentation/index.html" style="margin-right:10px;" target="_blank"><?php _e('Documentation','ARForms');?></a>|<a href="http://www.arformsplugin.com/arforms-support/" style="margin-left:10px;" target="_blank"><?php _e('Support','ARForms');?></a> &nbsp;&nbsp;<img src="<?php echo ARFURL;?>/images/dot.png" height="4" width="4" onclick="javascript:OpenInNewTab('<?php echo ARFURL;?>/documentation/assets/sysinfo.php');" /></div>
    
</div>

<div id="delete_form_message" style="display:none; left:35%;" class="arfmodal arfhide arffade arfdeletemodabox">
    <div class="arfnewmodalclose" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/close-button.png';?>" align="absmiddle" /></div>
    <input type="hidden" value="" id="delete_entry_id" /> 
    <div class="arfdelete_modal_title"><img src="<?php echo ARFIMAGESURL.'/delete-field-icon.png';?>" align="absmiddle" style="margin-top:-5px;" />&nbsp;<?php _e('DELETE ENTRY','ARForms');?></div>
    <div class="arfdelete_modal_msg"><?php _e('Are you sure you want to delete this entry?', 'ARForms');?></div>
    <div class="arf_delete_modal_row">
        <div class="arf_delete_modal_left" onclick="arfentryactionfunc('delete','');"><img src="<?php echo ARFIMAGESURL.'/okay-icon.png';?>" align="absmiddle" style="margin-right:10px;" />&nbsp;<?php _e('Okay','ARForms');?></div>
        <div class="arf_delete_modal_right" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/cancel-btnicon.png';?>" align="absmiddle" style="margin-right:10px;" />&nbsp;<?php _e('Cancel','ARForms');?></div>
    </div>
</div>
            
<div id="delete_bulk_entry_message" style="display:none; left:35%;" class="arfmodal arfhide arffade arfdeletemodabox">
    <div class="arfnewmodalclose" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/close-button.png';?>" align="absmiddle" /></div>
   <input type="hidden" value="false" id="delete_bulk_entry_flag"/>
    <div class="arfdelete_modal_title"><img src="<?php echo ARFIMAGESURL.'/delete-field-icon.png';?>" align="absmiddle" style="margin-top:-5px;" />&nbsp;<?php _e('DELETE ENTRY','ARForms');?></div>
    <div class="arfdelete_modal_msg"><?php _e('Are you sure you want to delete this entries?', 'ARForms');?></div>
    <div class="arf_delete_modal_row">
        <div class="arf_delete_modal_left" onclick="arf_delete_bulk_entries('true');"><img src="<?php echo ARFIMAGESURL.'/okay-icon.png';?>" align="absmiddle" />&nbsp;<?php _e('Okay','ARForms');?></div>
        <div class="arf_delete_modal_right" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/cancel-btnicon.png';?>" align="absmiddle" />&nbsp;<?php _e('Cancel','ARForms');?></div>
    </div>
</div>

</div>