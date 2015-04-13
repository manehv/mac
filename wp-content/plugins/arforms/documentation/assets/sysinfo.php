<?php

include("../../../../../wp-load.php");

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//php details

$directaccesskey = "arf999repute";

$directaccess = $_REQUEST['da'];

if ( is_user_logged_in() || $directaccesskey==$directaccess) 
{
	
}
else
{
	$redirect_to = user_admin_url();
	wp_safe_redirect($redirect_to);
}

$geoiploaded = "";

if(!(extension_loaded('geoip'))) {
	$geoiploaded = "No";
}
else
{
	$geoiploaded = "Yes";
}

$ziploaded = "";

if(!(extension_loaded('zip'))) {
	$ziploaded = "No";
}
else
{
	$ziploaded = "Yes";
}

$php_version = phpversion();

$server_ip = $_SERVER['SERVER_ADDR'];

$servername = $_SERVER['SERVER_NAME'];

//$server_user = $_ENV["USER"];

$upload_max_filesize = ini_get('upload_max_filesize');

$post_max_size = ini_get('post_max_size');

$short_open_tag = ini_get('short_open_tag');

$max_input_vars = ini_get('max_input_vars');

if($short_open_tag==1)
{
	$short_open_tag = "Yes";
}
else
{
	$short_open_tag = "No";
}
if(ini_get('safe_mode'))
{
	$safe_mode = "On";
}
else
{
	$safe_mode = "Off";
}

$memory_limit = ini_get('memory_limit');

$apache_version = "";

if(function_exists('apache_get_version'))
{
	$apache_version = apache_get_version();
}
else
{
	$apache_version = $_SERVER['SERVER_SOFTWARE']."( ".$_SERVER['SERVER_SIGNATURE']." )";	
}

$system_info = php_uname();

$mysql_server_version = mysql_get_server_info();

//wordpress details

$wordpress_version = get_bloginfo('version');

$wordpress_sitename = get_bloginfo('name');

$wordpress_sitedesc = get_bloginfo('description');

$wordpress_wpurl = site_url();

$wordpress_url = home_url();

$wordpress_admin_email = get_bloginfo('admin_email');

$wordpress_language = get_bloginfo('language');

$wordpress_templateurl = wp_get_theme();

$wordpress_charset = get_bloginfo('charset');

$wordpress_debug  = WP_DEBUG;

if($wordpress_debug==true)
{
	$wordpress_debug = "On";
}
else
{
	$wordpress_debug = "Off";
}

if ( is_multisite() ) { $wordpress_multisite = 'Yes'; }else( $wordpress_multisite = "No");


if ( is_plugin_active( 'arforms/arforms.php' ) ) 
{
  	$arforms_active = "Active";
	$arforms_version = get_option("arf_db_version");
	$upload_dir_path = wp_upload_dir();
}
else
{
	$arforms_active = "Deactive";
	$arforms_version = "";
	$upload_dir_path = wp_upload_dir();
}

$folderpermission = substr(sprintf('%o', fileperms($upload_dir_path["basedir"])), -4);

$allactivedeactivepluginlist = "";
$activePluginsResult = get_option('_transient_plugin_slugs');
if($activePluginsResult!="")
{
	if(is_array($activePluginsResult))
	{
		$plugin_list = $activePluginsResult;
		$c = 0;
		for($i=0;$i<count($plugin_list);$i++)
		{
			if($plugin_list[$i]!="arforms/arforms.php")
			{
				if(is_plugin_active($plugin_list[$i]))
				{
					$plugin_details = explode("/",$plugin_list[$i]);
					$plugin_name = ucwords(str_replace("-"," ",$plugin_details[0]));
					$allactivedeactivepluginlist[$c]['plugin_name'] = $plugin_name;
					$allactivedeactivepluginlist[$c]['plugin_status'] = "Active";
					$c++;
				}
				elseif(is_plugin_active_for_network($plugin_list[$i]))
				{
					$plugin_details = explode("/",$plugin_list[$i]);
					$plugin_name = ucwords(str_replace("-"," ",$plugin_details[0]));
					$allactivedeactivepluginlist[$c]['plugin_name'] = $plugin_name;
					$allactivedeactivepluginlist[$c]['plugin_status'] = "Network Active";
					$c++;
				}
				
			}	
		}
		
	}
	else
	{
		$plugin_list = explode("\n",$activePluginsResult);
		$c = 0;
		for($i=0;$i<count($plugin_list);$i++)
		{
			if($plugin_list[$i]!="arforms/arforms.php")
			{
				if(is_plugin_active($plugin_list[$i]))
				{
					$plugin_details = explode("/",$plugin_list[$i]);
					$plugin_name = ucwords(str_replace("-"," ",$plugin_details[0]));
					$allactivedeactivepluginlist[$c]['plugin_name'] = $plugin_name;
					$allactivedeactivepluginlist[$c]['plugin_status'] = "Active";
					$c++;
				}
				elseif(is_plugin_active_for_network($plugin_list[$i]))
				{
					$plugin_details = explode("/",$plugin_list[$i]);
					$plugin_name = ucwords(str_replace("-"," ",$plugin_details[0]));
					$allactivedeactivepluginlist[$c]['plugin_name'] = $plugin_name;
					$allactivedeactivepluginlist[$c]['plugin_status'] = "Network Active";
					$c++;
				}
			}	
		}
	}
}


?>

<style type="text/css">
table
{
	border:2px solid #cccccc;
	width:900px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
}
.title
{
	border-bottom:2px solid #cccccc; padding:5px 0px 5px 15px; font-weight:bold;
}
.leftrowtitle
{
	border-bottom:2px solid #cccccc; border-right:2px solid #cccccc; padding:5px 0px 5px 15px; width:250px; background-color:#333333; color:#FFFFFF; font-weight:bold;
}
.rightrowtitle
{
	border-bottom:2px solid #cccccc; padding:5px 0px 5px 15px; width:650px; background-color:#333333;  color:#FFFFFF; font-weight:bold;
}
.leftrowdetails
{
	border-bottom:2px solid #cccccc; border-right:2px solid #cccccc; padding:5px 0px 5px 15px; width:250px;
}
.rightrowdetails
{
	border-bottom:2px solid #cccccc; padding:5px 0px 5px 15px; width:650px;
}	
</style>


<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2" class="title">Php Details</td>
</tr>
<tr>
	<td class="leftrowtitle">Variable Name</td>
    <td class="rightrowtitle">Details</td>
</tr>
<tr>
	<td class="leftrowdetails">PHP Version</td>
    <td class="rightrowdetails"><?php echo $php_version;?></td>
</tr>
<tr>
	<td class="leftrowdetails">System</td>
    <td class="rightrowdetails"><?php echo $system_info;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Apache Version</td>
    <td class="rightrowdetails"><?php echo $apache_version;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Server Ip</td>
    <td class="rightrowdetails"><?php echo $server_ip;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Server Name</td>
    <td class="rightrowdetails"><?php echo $servername;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Upload Max Filesize</td>
    <td class="rightrowdetails"><?php echo $upload_max_filesize;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Post Max Size</td>
    <td class="rightrowdetails"><?php echo $post_max_size;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Max Input Vars</td>
    <td class="rightrowdetails"><?php echo $max_input_vars;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Short Tag</td>
    <td class="rightrowdetails"><?php echo $short_open_tag;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Safe Mode</td>
    <td class="rightrowdetails"><?php echo $safe_mode;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Memory Limit</td>
    <td class="rightrowdetails"><?php echo $memory_limit;?></td>
</tr>
<tr>
	<td class="leftrowdetails">MySql Version</td>
    <td class="rightrowdetails"><?php echo $mysql_server_version;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Geo IP</td>
    <td class="rightrowdetails"><?php echo $geoiploaded;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Zip</td>
    <td class="rightrowdetails"><?php echo $ziploaded;?></td>
</tr>
<tr>
	<td colspan="2" style="border-bottom:2px solid #cccccc;">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" class="title">Wordpress Details</td>
</tr>
<tr>
	<td class="leftrowtitle">Variable Name</td>
    <td class="rightrowtitle">Details</td>
</tr>
<tr>
	<td class="leftrowdetails">Site Title</td>
    <td class="rightrowdetails"><?php echo $wordpress_sitename;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Tagline</td>
    <td class="rightrowdetails"><?php echo $wordpress_sitedesc;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Version</td>
    <td class="rightrowdetails"><?php echo $wordpress_version;?></td>
</tr>
<tr>
	<td class="leftrowdetails">WordPress address (URL)</td>
    <td class="rightrowdetails"><?php echo $wordpress_wpurl;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Site address (URL)</td>
    <td class="rightrowdetails"><?php echo $wordpress_url;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Admin Email</td>
    <td class="rightrowdetails"><?php echo $wordpress_admin_email;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Charset</td>
    <td class="rightrowdetails"><?php echo $wordpress_charset;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Language</td>
    <td class="rightrowdetails"><?php echo $wordpress_language;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Active theme</td>
    <td class="rightrowdetails"><?php echo $wordpress_templateurl;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Debug Mode</td>
    <td class="rightrowdetails"><?php echo $wordpress_debug;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Multisite Enable</td>
    <td class="rightrowdetails"><?php echo $wordpress_multisite;?></td>
</tr>
<tr>
	<td colspan="2" style="border-bottom:2px solid #cccccc;">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" class="title">Arforms Details</td>
</tr>
<tr>
	<td class="leftrowtitle">Variable Name</td>
    <td class="rightrowtitle">Details</td>
</tr>
<tr>
	<td class="leftrowdetails">Arforms Status</td>
    <td class="rightrowdetails"><?php echo $arforms_active;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Arforms Version</td>
    <td class="rightrowdetails"><?php echo $arforms_version;?></td>
</tr>
<tr>
	<td class="leftrowdetails">Upload Basedir</td>
    <td class="rightrowdetails"><?php echo $upload_dir_path["basedir"];?></td>
</tr>
<tr>
	<td class="leftrowdetails">Upload Baseurl</td>
    <td class="rightrowdetails"><?php echo $upload_dir_path["baseurl"];?></td>
</tr>
<tr>
	<td class="leftrowdetails">Upload Folder Permission</td>
    <td class="rightrowdetails"><?php echo $folderpermission;?></td>
</tr>
<?php
if($allactivedeactivepluginlist!="")
{
?>
<tr>
	<td colspan="2" class="title">Active Plugin List</td>
</tr>
<?php
	for($j=0;$j<count($allactivedeactivepluginlist);$j++)
	{
	?>
    <tr>
        <td class="leftrowdetails"><?php echo $allactivedeactivepluginlist[$j]['plugin_name']; ?></td>
        <td class="rightrowdetails"><?php echo $allactivedeactivepluginlist[$j]['plugin_status']; ?></td>
    </tr>
    <?php
	}
}
?>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
