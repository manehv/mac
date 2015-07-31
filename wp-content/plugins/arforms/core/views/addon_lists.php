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
.page_desc
{
	color:#353942;
	font-weight:normal;
	font-family:'open_sansregular', Arial, Helvetica, Verdana, sans-serif;
	line-height:normal;
	font-size:19px;
	padding-top:5px;
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
<script type="application/javascript" language="javascript">jQuery('body').append('<div id="arfsaveformloader"></div>');</script>
<div class="wrap arfforms_page">
	<div id="poststuff" class="metabox-holder">
    	<div id="post-body">
        	<div class="wrap_content">
            	<div class="page_title"> <?php _e('ARForms Add-Ons','ARForms'); ?></div>
                <div class="page_desc"> <?php _e('Add more features to ARForms using Add-Ons','ARForms'); ?></div>
                <div style="clear:both; margin-top:30px;">
                	<?php
						global $arsettingcontroller;
						$arsettingcontroller->addons_page();
					?>
                </div>
            </div>
        </div>    	
    </div>
</div>
