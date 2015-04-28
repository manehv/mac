<?php
/*
Plugin Name: ARForms
Description: Exclusive Wordpress Form Builder Plugin With Seven Most Popular E-Mail Marketing Tools Integration
Version: 2.7
Plugin URI: http://www.arformsplugin.com/
Author: Repute InfoSystems
Author URI: http://reputeinfosystems.com/
Text Domain: ARForms
*/

global $arformhelper;

$actions['bulk_delete'] = __('Delete', 'ARForms');
if( isset($_REQUEST['err']) and $_REQUEST['err'] == 1 )			
	$errors[] = __('This form is already deleted.', 'ARForms');	
?>
<script type="text/javascript" language="javascript">
	var height = jQuery(window).height();
	document.cookie = 'height='+height;
	
	var width = jQuery(window).width();
	document.cookie = 'width='+width;
	

function change_title(val)
{
	val.title = '<span class="tb_liev_prev"><img style="vertical-align: middle;padding-bottom: 3px;" align="absmiddle" src="<?php echo ARFIMAGESURL;?>/preview-icon.png">&nbsp;Form Preview</span><div align="right" class="tb_go_back"><button onClick="javascript:CloseWindow();" type="button" class="btn_3"><img style="vertical-align: middle;" src="<?php echo ARFIMAGESURL;?>/back_icon.png">&nbsp;&nbsp;Back To Editor</button></div>';
	
	jQuery('#TB_window').html('');
	
	jQuery('#TB_title').html('');
	
	jQuery('#TB_ajaxContent').html('');
	
}

function removeVariableFromURL(url_string, variable_name) {
    var URL = String(url_string);
    var regex = new RegExp( "\\?" + variable_name + "=[^&]*&?", "gi");
    URL = URL.replace(regex,'?');
    regex = new RegExp( "\\&" + variable_name + "=[^&]*&?", "gi");
    URL = URL.replace(regex,'&');
    URL = URL.replace(/(\?|&)$/,'');
    regex = null;
    return URL;
}

var pageurl = removeVariableFromURL(document.URL, 'err');
if( window.history.pushState )
	window.history.pushState({path:pageurl},'',pageurl);
</script>
<?php 
$width = @$_COOKIE['width'] * 0.80;
$width_new = '&width='.$width;
?>
<style type="text/css" title="currentStyle">
	@import "<?php echo ARFURL; ?>/datatables/media/css/demo_page.css";
	@import "<?php echo ARFURL; ?>/datatables/media/css/demo_table_jui.css";
	@import "<?php echo ARFURL; ?>/datatables/media/css/jquery-ui-1.8.4.custom.css";
	@import "<?php echo ARFURL; ?>/datatables/media/css/ColVis.css";

.paginate_page a{
display:none;
}
#poststuff #post-body {
    margin-top: 32px;
}
	.delete_box{
		float:left;
	}
	
	
    </style>

<?php

$default_hide = array(
				'0' => '',
				'1' => 'ID',
				'2'	=> 'Name',
				'3' => 'Key',
				'4' => 'Entries',
				'5' => 'Shortcodes',
				'6' => 'Create Date',
				'7' => 'Action',
				);

$columns_list = maybe_unserialize(get_option('arfformcolumnlist'));
$is_colmn_array = is_array($columns_list);

$exclude = '';

	if( count($columns_list) > 0 and $columns_list != '' ) {
	
		foreach($default_hide as $key => $val ){
		
			foreach($columns_list as $column){
			
				if($column == $val){
					$exclude .= $key.', ';
				}
			}
		
		}
	}

if( $exclude=="" and !$is_colmn_array )
	$exclude .= '6, ';
else if( $exclude and !strpos($exclude, '6,') and !$is_colmn_array )	
	$exclude .= '6, ';		
?>
<script type="text/javascript" charset="utf-8">
// <![CDATA[
	jQuery(document).ready( function () {
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
			
			nNext.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/next_normal-icon.png')";
			nNext.style.backgroundRepeat = "no-repeat";
			nNext.style.backgroundPosition = "center";
			nNext.title = "Next";
			
			nLast.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/last_normal-icon.png')";
			nLast.style.backgroundRepeat = "no-repeat";
			nLast.style.backgroundPosition = "center";
			nLast.title = "Last";
			
			nFirst.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/first_normal-icon.png')";
			nFirst.style.backgroundRepeat = "no-repeat";
			nFirst.style.backgroundPosition = "center";
			nFirst.title = "First";
			
			nPrevious.style.backgroundImage = "url('<?php echo ARFURL; ?>/images/previous_normal-icon.png')";
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
		 		
				if(document.getElementById('of_grid'))
					of_grid = document.getElementById('of_grid').value;
				else
					of_grid = 'of';
				
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
		
	jQuery('#example').dataTable( {
		"sDom": '<"H"lCfr>t<"footer"ip>',
		"sPaginationType": "four_button",
		"bJQueryUI": true,
		"bPaginate": true,
		"bAutoWidth" : false,					
		"aoColumnDefs": [
			{ "bVisible": false, "aTargets": [<?php if($exclude!='') echo $exclude;?>] },
			{ "bSortable": false, "aTargets": [ 0, 7 ] }
		],
		"oColVis": {
		   "aiExclude": [ 0, 7 ]
		},
		
		
		});
});
			
		
		jQuery('.ColVis_Button:not(.ColVis_MasterButton)').live('click',function()
		{
		
     		var colsArray= jQuery('.ColVis_Button :checkbox').map(function(){
			
				return [[jQuery(this).parent().next('.ColVis_title').text(), this.checked ? 'visibile':'hidden']];
				
			
    		}).get();
			
			
			jQuery.ajax({type:"POST",url:ajaxurl,data:"action=change_show_hide_column&colsArray="+colsArray,
	
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
							
</script>
<style>
body{
padding:0px !important;
margin:0px !important;
}
#poststuff
{
	clear:both;
}
#poststuff #post-body {
	background:none;
	border:none;
	clear:both;
	margin-top: 0px !important;
}
.page_title
{
	color:#353942;
	font-weight:normal;
	font-family:'open_sansregular', Arial, Helvetica, Verdana, sans-serif;
	line-height:normal;
	font-size:30px;
	/*padding-left:60px;
	padding-top:20px; */
	padding-bottom:10px;
	<?php
		if(is_rtl())
		{
	?>
		float:right;
	<?php
		}
		else
		{
	?>
	float:left;
	<?php
		}
	?>
	clear:both;
}
.wrap_content 
{ 
	clear:both;
	margin-top:0px !important;
	margin-left:0px;
	margin-right:0px; 
	padding:25px; 
	background-color:#FFFFFF;
	border:none; 
	border-radius:0px; 
}
.addnewbutton
{
	height:45px;
}	
</style>
<div class="wrap arfforms_page">
	
	<div id="poststuff" class="metabox-holder">
    	<div id="post-body">
		<div class="wrap_content">
			
            <div class="page_title"><?php _e('Manage Forms', 'ARForms');?></div>
            
                <div style="clear:both;"></div>
                <div style="clear:both; margin-top:15px;">
                <form method="get" id="arfmainformnewlist" class="data_grid_list" onsubmit="return apply_bulk_action_form();">
                
                <input type="hidden" name="page" value="<?php echo $_GET['page'] ?>" />
                <input type="hidden" name="arfaction" value="list" />
                
                <div id="arfmainformnewlist">
                
                <?php do_action('arfbeforelistingforms'); ?>
                
				<?php require(VIEWS_PATH.'/shared_errors.php'); ?>  
                <?php
					if(is_rtl())
					{
						$add_new_form_btn = 'position:absolute;left:50px;';
					}
					else
					{
						$add_new_form_btn = 'position:absolute;right:50px;';
					}
				?>
                <div style=" <?php echo $add_new_form_btn; ?>">
                	<button class="greensavebtn" type="button" onclick="location.href='<?php echo admin_url('admin.php?page=ARForms&arfaction=new&isp=1');?>';" style="width:160px; border:0px; color:#FFFFFF; height:40px; border-radius:3px;"><img align="absmiddle" src="<?php echo ARFIMAGESURL ?>/plus-icon.png">&nbsp;&nbsp;<?php _e('Add New Form', 'ARForms') ?></button>
                </div>
                
                <div class="alignleft actions">
                        <?php 
                        $two = '1';
                        echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two'>\n";
                        echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms') . "</option>\n";
                
                        foreach ( $actions as $name => $title ) {
                            $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';
                
                            echo "\t<option value='$name'$class>$title</option>\n";
                        }
                
                        echo "</select></div>\n";
                		
						echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__('Apply','ARForms').'" />';
                        echo "\n";
                        
                        ?>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
                            <th class="center" style="width:50px;"><div style="display:inline-block; position:relative;"><input id="cb-select-all-1" type="checkbox" class="chkstanard"><label for="cb-select-all-1" class="cb-select-all"><span></span></label></div></th>
                            <th style="width:80px;"><?php _e('ID','ARForms');?></th>
                            <th><?php _e('Name','ARForms');?></th>
                            <th style="width:100px;"><?php _e('Key','ARForms');?></th>
                            <th class="center" style="width:90px;"><?php _e('Entries','ARForms');?></th>
                            <th><?php _e('Shortcodes','ARForms');?></th>
                            <th style="width:100px;"><?php _e('Create Date','ARForms');?></th>
                            <th class="col_action" style="width:230px;"><?php _e('Action','ARForms');?></th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                global $wpdb, $db_record;
                
                $form_result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."arf_forms WHERE is_template = %d AND (status is NULL OR status = '' OR status = 'published') order by id desc", 0), OBJECT_K );
                
                foreach($form_result as $key => $val) {
                            $res = $wpdb->get_results( $wpdb->prepare( "SELECT is_enable FROM ".$wpdb->prefix."arf_forms WHERE id = %d", $val->id ), 'ARRAY_A' );
                ?>    
                        <tr class="<?php if( $res[0]['is_enable'] == 0 ) {echo 'grid_disable_row';}else{echo '';}?>">
                            <td class="center"><input id="cb-item-action-<?php echo $val->id;?>" class="chkstanard" type="checkbox" value="<?php echo $val->id;?>" name="item-action[]"><label for="cb-item-action-<?php echo $val->id;?>"><span></span></label></td>
                            <td><?php echo $val->id;?></td>
                            <td class="form_name"><?php 
                            $edit_link = "?page=ARForms&arfaction=edit&id={$val->id}";
                            
                                    
                            if( $res[0]['is_enable'] == 0 )  
                                echo '<a class="row-title" href="'.$edit_link.'">'. stripslashes($val->name) .'</a><br /><span style="color:#FF0000;">(Disabled)</span>';					
                            else
                                echo '<a class="row-title" href="'.$edit_link.'">'. stripslashes($val->name) .'</a> ';
                                        
                            ?></td>
                            <td><?php echo $val->form_key;?></td>
                            <td class="form_entries center"><?php
                            $entries = $db_record->getRecordCount($val->id);
                            echo (current_user_can('arfviewentries')) ? '<a href="'. esc_url(admin_url('admin.php') .'?page=ARForms-entries&form='. $val->id ) .'">'. $entries.' '.__('Entries', 'ARForms').'</a>' : $entries.' '.__('Entries', 'ARForms');
                            ?></td>
                            <td><input type="text" class="shortcode_textfield" readonly="true" onclick="this.select();" onfocus="this.select();" value="[ARForms id=<?php echo $val->id; ?>]" /><br/>
                            <input type="text" class="shortcode_textfield" readonly="true" onclick="this.select();" onfocus="this.select();" value="[ARForms_popup id=<?php echo $val->id; ?> desc='Click here to open Form' type='link' height='540' width='800']" /></td>
                            <td><?php 
							$wp_format_date = get_option('date_format');
					
							if( $wp_format_date == 'F j, Y' || $wp_format_date =='m/d/Y' ) {
								$date_format_new = 'M d, Y';
							} else if( $wp_format_date == 'd/m/Y' ) {
								$date_format_new = 'd M, Y';
							} else if( $wp_format_date == 'Y/m/d' ) {
								$date_format_new = 'Y, M d';
							} else {
								$date_format_new = 'M d, Y';
							}
							
							echo date($date_format_new, strtotime($val->created_date));?></td>
                            <td class="col_action">
                            <div class="row-actions">
                            
                                <?php if(current_user_can('arfeditforms')){ 
                                
                                $edit_link = "?page=ARForms&arfaction=edit&id={$val->id}";
                
                                echo "<a href='" . wp_nonce_url( $edit_link ) . "'><img src='".ARFIMAGESURL."/edit-icon22.png' onmouseover=\"this.src='".ARFIMAGESURL."/edit-icon_hover22.png';\" class='arfhelptip' title='".__('Edit Form','ARForms')."' onmouseout=\"this.src='".ARFIMAGESURL."/edit-icon22.png';\" /></a>";
                
                                $duplicate_link = "?page=ARForms&arfaction=duplicate&id={$val->id}";
                
                                 }  
                                 
                                 if(current_user_can('arfeditforms')){ 
                
                                 echo "<a href='" . wp_nonce_url( "?page=ARForms-entries&arfaction=list&form={$val->id}" ) . "'><img src='".ARFIMAGESURL."/listing_icon22.png' title='".__('Form Entry','ARForms')."' class='arfhelptip' onmouseover=\"this.src='".ARFIMAGESURL."/listing_icon_hover22.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/listing_icon22.png';\" /></a>";
                            
                                echo "<a href='" . wp_nonce_url( $duplicate_link ) . "'><img src='".ARFIMAGESURL."/duplicate-icon22.png' title='".__('Duplicate Form','ARForms')."' class='arfhelptip' onmouseover=\"this.src='".ARFIMAGESURL."/duplicate-icon_hover22.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/duplicate-icon22.png';\" /></a>";
                				
								echo "<a href='javascript:void(0);' onclick='arfaction_func(\"export_csv\", \"{$val->id}\");'><img src='".ARFIMAGESURL."/export.png' class='arfhelptip' title='".__('Export To CSV','ARForms')."' onmouseover=\"this.src='".ARFIMAGESURL."/export_hover.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/export.png';\" /></a>";
								
                                }
                				
								do_action('arf_additional_action_formlisting',$val->id);
                
                                if(current_user_can('arfdeleteforms')){ 
                                
                                
                                    $delete_link = "?page=ARForms&arfaction=destroy&id={$val->id}";
                
                                 
										
									$id = $val->id;
										echo "<img src='".ARFIMAGESURL."/delete_icon222.png' class='arfhelptip' title='".__('Delete','ARForms')."' onmouseover=\"this.src='".ARFIMAGESURL."/delete_icon2_hover222.png';\" onmouseout=\"this.src='".ARFIMAGESURL."/delete_icon222.png';\" data-toggle='arfmodal' href='#delete_form_message' onclick=\"ChangeID({$id});arfchangedeletemodalwidth('arfdeletemodabox');\"  style='cursor:pointer'/></a>";	
									
									
                                }
                                
								global $style_settings;
	
								
																
                                $target_url = $arformhelper->get_direct_link($val->form_key);
                                
								$target_url = $target_url.'&ptype=list';	
								
								$width = @$_COOKIE['width'] * 0.80;
								
								if(isset($_COOKIE['width']) and $_COOKIE['width'] != '')
									$tb_width = '&width='.$width;
								else
									$tb_width = '';
																
								if(isset($_COOKIE['height']) and $_COOKIE['height']!='') 
									$tb_height = '&height='.($_COOKIE['height']-100);
								else
									$tb_height = '';

								echo "<a class='openpreview' href='#' data-url='".$target_url.$tb_width.$tb_height."&whichframe=preview&TB_iframe=true'><img src='".ARFIMAGESURL."/view_icon22.png' onmouseover=\"this.src='".ARFIMAGESURL."/view_icon_hover22.png';\" title='".__('Preview','ARForms')."' class='arfhelptip' onmouseout=\"this.src='".ARFIMAGESURL."/view_icon22.png';\" /></a>";	?>
                
                            </div>
                            </td>
                        </tr>
                        

                <?php } ?>  
                     
                    </tbody>
                </table>                
                <div class="clear"></div>
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
                
                <div class="alignleft actions2">
                        <?php 
                        $two = '2';
                        echo "<div class='sltstandard'><select style='width:120px;' data-width='120px' name='action$two'>\n";
                        echo "<option value='-1' selected='selected'>" . __('Bulk Actions','ARForms') . "</option>\n";
                
                        foreach ( $actions as $name => $title ) {
                            $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';
                
                            echo "\t<option value='$name'$class>$title</option>\n";
                        }
                
                        echo "</select></div>\n";
                		
						echo '<input type="submit" id="doaction'.$two.'" class="arfbulkbtn arfemailaddbtn" value="'.__('Apply','ARForms').'" />'; 
                        echo "\n";
                        
                        ?>
                </div>
             </div>
                                    
                <div class="footer_grid"></div>
                
                <?php do_action('arfafterlistingforms'); ?>
                
                </form>
                </div>
                <div id="arfupdateformbulkoption_div"></div>
        	</div>
            
            <div id="delete_form_message" style="display:none; left:35%;" class="arfmodal arfhide arffade arfdeletemodabox">
            	<div class="arfnewmodalclose" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/close-button.png';?>" align="absmiddle" /></div>
                <input type="hidden" value="" id="delete_id"/>
                <div class="arfdelete_modal_title"><img src="<?php echo ARFIMAGESURL.'/delete-field-icon.png';?>" align="absmiddle" style="margin-top:-5px;" />&nbsp;<?php _e('DELETE FORM','ARForms');?></div>
                <div class="arfdelete_modal_msg"><?php _e('Are you sure you want to delete this form?', 'ARForms');?></div>
                <div class="arf_delete_modal_row">
                    <div class="arf_delete_modal_left" onclick="arfaction_func('delete','')"><img src="<?php echo ARFIMAGESURL.'/okay-icon.png';?>" align="absmiddle" style="margin-right:10px;" />&nbsp;<?php _e('Okay','ARForms');?></div>
                    <div class="arf_delete_modal_right" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/cancel-btnicon.png';?>" align="absmiddle" style="margin-right:10px;" />&nbsp;<?php _e('Cancel','ARForms');?></div>
                </div>
            </div>
            
            <div id="delete_bulk_form_message" style="display:none; left:35%;" class="arfmodal arfhide arffade arfdeletemodabox">
                <div class="arfnewmodalclose" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/close-button.png';?>" align="absmiddle" /></div>
                <input type="hidden" value="false" id="bulk_delete_flag"/>  
                <div class="arfdelete_modal_title"><img src="<?php echo ARFIMAGESURL.'/delete-field-icon.png';?>" align="absmiddle" style="margin-top:-5px;" />&nbsp;<?php _e('DELETE FORM','ARForms');?></div>
                <div class="arfdelete_modal_msg"><?php _e('Are you sure you want to delete this form(s)?', 'ARForms');?></div>
                <div class="arf_delete_modal_row">
                    <div class="arf_delete_modal_left" onclick="arf_delete_bulk_form('true');"><img src="<?php echo ARFIMAGESURL.'/okay-icon.png';?>" align="absmiddle" />&nbsp;<?php _e('Okay','ARForms');?></div>
                    <div class="arf_delete_modal_right" data-dismiss="arfmodal"><img src="<?php echo ARFIMAGESURL.'/cancel-btnicon.png';?>" align="absmiddle" />&nbsp;<?php _e('Cancel','ARForms');?></div>
                </div>
            </div>
            
            <div id="form_preview_modal" class="arfmodal arfhide arffade" style="display:none;left:15%; width:1074px; height:480px;">
                <div class="arfmodal-header">
                        <div style="padding-top:10px;font-size:24.5px; color:#3E6289; float:left;">
                            
                            <div onclick="arflistchangedevice('computer');" title="<?php _e('Computer View', 'ARForms');?>" class="arfdevicesbg arfhelptip"><div id="arfcomputer" class="arfdevices arfactive"></div></div>
                            <div onclick="arflistchangedevice('tablet');" title="<?php _e('Tablet View', 'ARForms');?>" class="arfdevicesbg arfhelptip"><div id="arftablet" class="arfdevices"></div></div>
                            <div onclick="arflistchangedevice('mobile');" title="<?php _e('Mobile View', 'ARForms');?>" class="arfdevicesbg arfhelptip"><div id="arfmobile" class="arfdevices"></div></div>                
                        </div>
                        <div style="float:right; padding-top:20px; cursor:pointer;" data-dismiss="arfmodal"><img src="<?php echo ARFURL.'/images/close-button2.png'; ?>" align="absmiddle" /></div>
                </div>
                <div class="arfmodal-body" style="height:355px; overflow:hidden; clear:both;">
                   <div class="iframe_loader" align="center"><img src="<?php echo ARFURL.'/images/ajax-loading-teal.gif'; ?>" /></div>	
                  <iframe id="arfdevicepreview" src="" frameborder="0" height="100%" width="100%"></iframe>
                </div>
            </div>
        </div>
	</div>
    
    <div style="clear:both;"></div>
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
    <div class="documentation_link" style="background:none; background:none; padding-top:40px;" align="<?php echo $doc_link_align; ?>"><a href="<?php echo ARFURL;?>/documentation/index.html" style="margin-right:10px;" target="_blank"><?php _e('Documentation','ARForms');?></a>|<a href="http://www.arformsplugin.com/arforms-support/" style="margin-left:10px;" target="_blank"><?php _e('Support','ARForms');?></a> &nbsp;&nbsp;<img src="<?php echo ARFURL;?>/images/dot.png" height="10" width="10" onclick="javascript:OpenInNewTab('<?php echo ARFURL;?>/documentation/assets/sysinfo.php');" /></div>

</div>
<script type="text/javascript">
function ChangeID(id)
{
	document.getElementById('delete_id').value = id;
}
</script>