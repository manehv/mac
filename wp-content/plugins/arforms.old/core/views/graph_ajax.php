<?php 
global $wpdb;

if(isset($_REQUEST['calculate']) && $_REQUEST['calculate']=='pre')
{
	if(isset($_REQUEST['new_year']) && $_REQUEST['new_year']!='')
	{
		$year_diff = date('Y', current_time('timestamp')) - $_REQUEST['new_year'];
		$new_year = $_REQUEST['new_year'];
	}
	elseif(isset($_REQUEST['new_month']) && $_REQUEST['new_month']!='')
	{
		$month_diff = date('m', current_time('timestamp')) - $_REQUEST['new_month'];
		$new_month = $_REQUEST['new_month'];
		$new_month_year = $_REQUEST['new_month_year'];
	}	
	elseif(isset($_REQUEST['new_week']) && $_REQUEST['new_week']!='')
	{
		$duedt = explode("-",date('Y-m-d'));
		$week_diff = (int)date('W',mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0])) - $_REQUEST['new_week'];
		$new_week = $_REQUEST['new_week'];
		$new_week_year = $_REQUEST['new_week_year'];
	}
	elseif(isset($_REQUEST['new_day']) && $_REQUEST['new_day']!='')
	{
		$new_diff = date('d') - $_REQUEST['new_day'];
		$new_day = $_REQUEST['new_day'];
		$new_day_month = $_REQUEST['new_day_month'];
		$new_day_year = $_REQUEST['new_day_year'];
	}
}
elseif(isset($_REQUEST['calculate']) && $_REQUEST['calculate']=='next')
{
	if(isset($_REQUEST['new_year']) && $_REQUEST['new_year']!='')
	{
		$year_diff = date('Y', current_time('timestamp')) + $_REQUEST['new_year'];
		$new_year  = $_REQUEST['new_year'];
	}
	elseif(isset($_REQUEST['new_month']) && $_REQUEST['new_month']!='')
	{
		$month_diff = date('m', current_time('timestamp')) + $_REQUEST['new_month'];
		$new_month  = $_REQUEST['new_month'];
		$new_month_year = $_REQUEST['new_month_year'];
	}
	elseif(isset($_REQUEST['new_week']) && $_REQUEST['new_week']!='')
	{
		$duedt = explode("-",date('Y-m-d'));	
		$week_diff = (int)date('W', mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0])) + $_REQUEST['new_week'];
		$new_week = $_REQUEST['new_week'];
		$new_week_year = $_REQUEST['new_week_year'];
	}
	elseif(isset($_REQUEST['new_day']) && $_REQUEST['new_day']!='')
	{
		$new_diff = date('d') + $_REQUEST['new_day'];
		$new_day = $_REQUEST['new_day'];
		$new_day_month = $_REQUEST['new_day_month'];
		$new_day_year = $_REQUEST['new_day_year'];
	}
}
else
{
	$year_diff = 0;
	$new_year  = date('Y', current_time('timestamp'));
	$month_diff = 0;
	$new_month = date('m', current_time('timestamp'));
	
	$new_month_year = date('Y', current_time( 'timestamp' ));
	$week_diff = 0;
	$duedt = explode("-",date('Y-m-d'));	
	$new_week = (int)date('W', mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]));
	$new_week_year = date('Y');
	$new_day = date('d');
	$new_day_month = date('m');
	$new_day_year = date('Y');
}

$allYear = $wpdb->get_results('SELECT YEAR(created_date) AS YEAR,MAX(MONTH(created_date)) AS MONTH , MAX(DAY(created_date)) AS DAY  FROM '.$wpdb->prefix.'arf_entries group by YEAR(created_date),MONTH(created_date),DAY(created_date)', 'ARRAY_A');
$numYear = $wpdb->num_rows;

$Years = "";
$Months = "";
$Dates = "";
if($numYear > 0)
{
	foreach($allYear as $newyear)
	{
		$Years[] = $newyear['YEAR'];
		$Months[] = $newyear['MONTH'];
		$Dates[] = $newyear['DAY'];		
	}
}
$min_year=date("Y");
$max_year=date("Y");
$min_month=date("m");
$max_month=date("m");
$max_date=date("d");
$min_date=date("d");
if(is_array($Years) || is_array($Months) || is_array($Dates))
{ 
	$min_year = min($Years);
	$max_year = max($Years);
	$min_month = min($Months);
	$max_month = max($Months);
	$max_date =  max($Dates);
	$min_date =  min($Dates);
}
if($type == "yearly")
{	
	$from_year = $new_year.'-01-01 00:00:00';
	$end_year = $new_year.'-12-31 23:59:59';
		
	if($form == '')
	{
		$sqlMonth = $wpdb->get_results($wpdb->prepare("SELECT YEAR(created_date) AS Year, MONTH(created_date) AS Month,COUNT(*) AS num from ".$wpdb->prefix."arf_entries WHERE created_date >= %s AND created_date <= %s Group By YEAR(created_date),  MONTH(created_date)",$from_year,$end_year),'ARRAY_A'); 
		$totalYear = $wpdb->num_rows;
		
		$sqlViewMonth = $wpdb->get_results($wpdb->prepare("SELECT YEAR(added_date) AS Year, MONTH(added_date) AS Month,COUNT(*) AS num from ".$wpdb->prefix."arf_views WHERE added_date >= %s AND added_date <= %s Group By YEAR(added_date),  MONTH(added_date)",$from_year,$end_year),'ARRAY_A'); 
		$totalViewYear = $wpdb->num_rows;
	}
	else
	{
		$sqlMonth = $wpdb->get_results($wpdb->prepare("SELECT YEAR(created_date) AS Year, MONTH(created_date) AS Month,COUNT(*) AS num from ".$wpdb->prefix."arf_entries WHERE form_id = %d AND created_date >= %s AND created_date <= %s Group By YEAR(created_date),  MONTH(created_date)",$form,$from_year,$end_year),'ARRAY_A'); 
		$totalYear = $wpdb->num_rows;
		
		$sqlViewMonth = $wpdb->get_results($wpdb->prepare("SELECT YEAR(added_date) AS Year, MONTH(added_date) AS Month,COUNT(*) AS num from ".$wpdb->prefix."arf_views WHERE form_id = %d AND added_date >= %s AND added_date <= %s Group By YEAR(added_date),  MONTH(added_date)",$form,$from_year,$end_year),'ARRAY_A'); 
		$totalViewYear = $wpdb->num_rows;
	}
	$arf_max_year_entry = 0;
	if($totalYear > 0)
	{
		foreach($sqlMonth as $arr_month)
		{
			$month[$arr_month['Month']] = $arr_month['num'];
		}
		
		$arf_max_year_entry = 0;		
		foreach ($month as $key => $val) {
			$arf_max_year_entry = max( $arf_max_year_entry, $val);
		}
		if( $arf_max_year_entry < 5 ) $arf_max_year_entry = $arf_max_year_entry;
	}
	
	if($totalViewYear > 0)
	{
		foreach($sqlViewMonth as $arr_view_month)
		{
			$view_month[$arr_view_month['Month']] = $arr_view_month['num'];
		}
		
		$arf_max_year_view = 0;
		if($view_month)
		{
			foreach ($view_month as $key => $val) {
				$arf_max_year_view = max( $arf_max_year_view, $val);
			}
		}
		if( $arf_max_year_view < 5 ) $arf_max_year_view = $arf_max_year_view;
	}
	for($i=1; $i<=12; $i++)
	{
		if( empty($month[$i]) ){	
			if($i==12)
				@$monthToDisplay .= 0;
			else
				@$monthToDisplay .= "0,";	
		}else{
			if($i==12)
				@$monthToDisplay .= $month[$i];	
			else
				@$monthToDisplay .= $month[$i].",";	
		}
		
		if( empty($view_month[$i]) ){
			if($i==12)
				@$viewMonthToDisplay .= 0;
			else
				@$viewMonthToDisplay .= "0,";
		}else{
			if($i==12)
				@$viewMonthToDisplay .= $view_month[$i];
			else
				@$viewMonthToDisplay .= $view_month[$i].",";
		}
	}
	
	$arf_max_year = 0;
	if( $arf_max_year_entry < 5 && $arf_max_year_view < 5) 
		$arf_max_year = 5;
	
	?>
    <script type="text/javascript" language="javascript">
	jQuery.noConflict();
	jQuery(document).ready(function($){
		var line1 = [<?php echo $monthToDisplay;?>];
		
		var line2 = [<?php echo $viewMonthToDisplay?>];
		
		var ticks = ['Jan', 'Feb', 'Mar', 'Apr', 'May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
		
           var plot2 = $.jqplot('chart1', [line1, line2], {
			 animate: !$.jqplot.use_excanvas,
			 seriesColors:['#FF5959','#00CCFF'],
               
                seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					pointLabels: { show: true }
				},
                axes: {
					xaxis: {
						
						renderer: $.jqplot.CategoryAxisRenderer,
						ticks: ticks
					},
					yaxis: {
						
						min : 0,
						<?php if(isset($arf_max_year) and $arf_max_year == 5){ echo 'max : 6, numberTicks: 4,'; } ?>
						
					},
				},
                highlighter: { 
                    show: true, 
                    showTooltip: true,      
                    tooltipLocation: 'nw', 
                    tooltipAxes: 'both',    
                    showMarker: true,
                    useAxesFormatters: false,
                    
                    tooltipContentEditor: function(str, seriesIndex, pointIndex, jqPlot) {
                        var re_var = '<table class="jqplot-highlighter"><tr><td class="tooltip_title"><?php _e('Month','ARForms');?> :</td><td class="tooltip_title1">&nbsp;'+ticks[pointIndex]+'</td></tr><tr><td class="tooltip_title"><?php _e('Entries','ARForms');?> :</td><td class="tooltip_title1">&nbsp;'+line1[pointIndex]+'</td></tr><tr><td class="tooltip_title"><?php _e('Views','ARForms');?> :</td><td class="tooltip_title1">&nbsp;'+line2[pointIndex]+'</td></tr></table>';
                        return re_var;
                    }
                }
            });
	});
	</script>
	<?php
}

$date = $new_month_year.'-'.$new_month.'-'.date('m', current_time( 'timestamp' ));

$day_first = date('01', strtotime($date));


$day_last  = date('t', strtotime($date));


function makeDayArray( $startDate , $endDate ){
 
  $startDate = strtotime( $startDate );
  $endDate   = strtotime( $endDate );

 
  $currDate  = $startDate;
  $dayArray  = array();


  do{
    $dayArray[] = date( 'Y-m-d' , $currDate );
    $currDate = strtotime( '+1 day' , $currDate );
  } while( $currDate<=$endDate );


  return $dayArray;
}

$day_array = makeDayArray( $new_month_year.'-'.$new_month.'-'.$day_first, $new_month_year.'-'.$new_month.'-'.$day_last );

if($form == '')
{
	foreach($day_array as $day) {
		$day_arr[$day] = $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) AS num FROM '.$wpdb->prefix.'arf_entries WHERE CAST(created_date AS DATE) = %s', $day), 'ARRAY_A');
		$day_arr[$day] = $day_arr[$day][0];			
		
		$day_view_arr[$day] = $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) AS num FROM '.$wpdb->prefix.'arf_views WHERE CAST(added_date AS DATE) = %s', $day), 'ARRAY_A');
		$day_view_arr[$day] = $day_view_arr[$day][0];
	}
}
else
{
	foreach($day_array as $day) {
		$day_arr[$day] = $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) AS num FROM '.$wpdb->prefix.'arf_entries WHERE form_id = %d AND CAST(created_date AS DATE) = %s', $form, $day), 'ARRAY_A');
		$day_arr[$day] = $day_arr[$day][0];		
		
		$day_view_arr[$day] = $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) AS num FROM '.$wpdb->prefix.'arf_views WHERE form_id = %d AND CAST(added_date AS DATE) = %s', $form, $day), 'ARRAY_A');
		$day_view_arr[$day] = $day_view_arr[$day][0];			
	}
}

$day_var  = '[';
$val_var  = '[';
$col_var  = '[';
$day_view_var = '[';

foreach( $day_arr as $day => $val ) {
	$day_var  .= "'".date('d', strtotime($day) )."-".date('M', strtotime($day) )."', ";
	$val_var  .= $val['num'].', ';
	$col_var  .= "'#00CCFF', ";
}

foreach( $day_view_arr as $dayView => $valView ) {
	$day_view_var  .= $valView['num'].', ';
}

$day_var .= ']'; 
$val_var .= ']';
$col_var .= ']';
$day_view_var .= ']';

$max_day = 0;
foreach ($day_arr as $key => $val) {
    $max_day = max( @$max_day, $val['num']);
}

foreach ($day_view_arr as $key => $val) {
    $max_view_day = max( @$max_view_day, $val['num']);
}

$max_day_mnth = '';
if( $max_day < 5 && $max_view_day < 5) 
	$max_day_mnth = 5;

?>
<script type="text/javascript" language="javascript">
jQuery.noConflict();
jQuery(document).ready(function($){

		var s1 = <?php echo $val_var; ?>;

		var s2 = <?php echo $day_view_var; ?>;
		        
		var ticks_month = <?php echo $day_var; ?>;
		
           var plot2 = $.jqplot('chart2', [s1, s2], {
			 animate: !$.jqplot.use_excanvas,
			 seriesColors:['#FF5959','#00CCFF'],
               
                seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					rendererOptions: {
						barPadding: 2,
						barWidth:14
					},
					pointLabels: { show: true }
				},
                axes: {
					xaxis: {
						
						renderer: $.jqplot.CategoryAxisRenderer,
						tickOptions:{
							angle:-90
						},
						tickRenderer:$.jqplot.CanvasAxisTickRenderer,
						ticks: ticks_month
					},
					yaxis: {
						
						min : 0,
						<?php if($max_day_mnth == 5){ echo 'max : 6, numberTicks: 4,'; } ?>
						
					},
				},
                highlighter: { 
                    show: true, 
                    showTooltip: true,      
                    tooltipLocation: 'nw',  
                    tooltipAxes: 'both',    
                    showMarker: true,
                    useAxesFormatters: false,
                    
                    tooltipContentEditor: function(str, seriesIndex, pointIndex, jqPlot) {
                        var re_var = '<table class="jqplot-highlighter"><tr><td class="tooltip_title"><?php _e('Day','ARForms');?> :</td><td class="tooltip_title1">&nbsp;'+ticks_month[pointIndex]+'</td></tr><tr><td class="tooltip_title"><?php _e('Entries','ARForms');?> :</td><td class="tooltip_title1">&nbsp;'+s1[pointIndex]+'</td></tr><tr><td class="tooltip_title"><?php _e('Views','ARForms');?> :</td><td class="tooltip_title1">&nbsp;'+s2[pointIndex]+'</td></tr></table>';
                        return re_var;
                    }
                }
            });
	});
</script>
<?php

if($type == "weekly")
{
$dt = strtotime(date('Y-m-d'));
$start_day_of_week = $res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
$end_day_of_week = $res['end'] = date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));

$year = $new_week_year;	
$duedt = explode("-",$year.'-'.date('m-d'));
$date = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);

$week = $new_week;

$duedt1 = explode("-",$year."-".date('12-d'));	
$last_week = (int)date('W', mktime(0, 0, 0, $duedt1[1], $duedt1[2], $duedt1[0]));

if($form == '')
{
	$form_id_qry = '';
}
else
{
	$form_id_qry = 'AND form_id ='.$form;
}

for($i=1; $i <= 7; $i++) {
	$added_date = date( "Y-m-d", strtotime($year."W".$week.$i) ); // First day of week
	$start_range = $added_date." 00:00:00";
	$end_range = $added_date." 23:59:59";
	
	$sql = "SELECT created_date as dt ,count(id) as total_visitor FROM ".$wpdb->prefix."arf_entries WHERE created_date >= '".$start_range."' AND  created_date <= '".$end_range."'".$form_id_qry;
	
	$visitor = $wpdb->get_row($sql,ARRAY_A);
	$visitor_val[] =$visitor['total_visitor'];
	
	if($visitor['total_visitor'] <= 8)
		$max_limit = "1";
}
	
	
$max_day = 0;
$arf_max_day_week = 0;
if(is_array($visitor_val))
{
	foreach ($visitor_val as $key => $val) {
	   $max_day = max( $max_day, $val);
	}	
	if( $max_day < 5 ) $arf_max_day_week = 5;
	
	$visitor = implode(',',$visitor_val);
}
?>

<script type="text/javascript" language="javascript">
jQuery.noConflict();
jQuery(document).ready(function($){
        $.jqplot.config.enablePlugins = true;
        
		var s1 = [<?php echo $visitor; ?>];
        
		var ticks = ['Mon', 'Tue', 'Wed', 'Thur', 'Fri','Sat','San'];
         
        plot1 = $.jqplot('chart3', [s1], {
           
            animate: !$.jqplot.use_excanvas,
			seriesColors:<?php echo $col_var; ?>,
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
				pointLabels: { show: true }
            },
            axes: {
                xaxis: {
					
                    renderer: $.jqplot.CategoryAxisRenderer,
					
					tickOptions:{
		                angle:-90
        		    },
					tickRenderer:$.jqplot.CanvasAxisTickRenderer,
                    
					ticks: ticks
                },
				yaxis: {
            		
					min : 0,
					<?php if($arf_max_day_week == 5){ echo 'max : 6, numberTicks: 4,'; } ?>
        			
        		},
            },

           
			highlighter: { 
				show: true, 
				showTooltip: true,      
				tooltipLocation: 'nw',  
				tooltipAxes: 'both',    
				showMarker: false,
				useAxesFormatters: false,
				
				tooltipContentEditor: function(str, seriesIndex, pointIndex, jqPlot) {
                	var re_var = '<table class="jqplot-highlighter"><tr><td class="tooltip_title">Date:</td><td class="tooltip_title1">&nbsp;'+ticks[pointIndex]+'</td></tr><tr><td class="tooltip_title">Entries:</td><td class="tooltip_title1">&nbsp;'+s1[pointIndex]+'</td></tr></table>';
								  
					return re_var;
            	}
            }	
		
        });
   				
		  
        $('#chart3').bind('jqplotDataClick',
            function (ev, seriesIndex, pointIndex, data) {
                $('#info3').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
            }
        );
									
    });
		
</script>
<?php
}
elseif($type == "daily")
{
$fdate = $new_day_year."-".$new_day_month."-".$new_day." 00:00:00";
$ldate = $new_day_year."-".$new_day_month."-".$new_day." 23:59:59";

if($form == '')
{
	$getDailyRecords = $wpdb->get_results($wpdb->prepare("SELECT Hour(created_date) AS hour,count(id) AS record FROM ".$wpdb->prefix."arf_entries where created_date >= %s and created_date <= %s  GROUP BY Day(created_date), Hour(created_date) ORDER BY Day(created_date), Hour(created_date)",$fdate,$ldate),'ARRAY_A');
	$totalDailyRecord = $wpdb->num_rows;
	
	$getDailyViewRecords = $wpdb->get_results($wpdb->prepare("SELECT Hour(added_date) AS hour,count(id) AS record FROM ".$wpdb->prefix."arf_views where added_date >= %s and added_date <= %s  GROUP BY Day(added_date), Hour(added_date) ORDER BY Day(added_date), Hour(added_date)",$fdate,$ldate),'ARRAY_A');
	$totalDailyViewRecord = $wpdb->num_rows;
}
else
{
	$getDailyRecords = $wpdb->get_results($wpdb->prepare("SELECT Hour(created_date) AS hour,count(id) AS record FROM ".$wpdb->prefix."arf_entries where created_date >= %s and created_date <= %s AND form_id = %d  GROUP BY Day(created_date), Hour(created_date) ORDER BY Day(created_date), Hour(created_date)",$fdate,$ldate,$form),'ARRAY_A');
	$totalDailyRecord = $wpdb->num_rows;
	
	
	$getDailyViewRecords = $wpdb->get_results($wpdb->prepare("SELECT Hour(added_date) AS hour,count(id) AS record FROM ".$wpdb->prefix."arf_views where added_date >= %s and added_date <= %s AND form_id = %d GROUP BY Day(added_date), Hour(added_date) ORDER BY Day(added_date), Hour(added_date)",$fdate,$ldate,$form),'ARRAY_A');
	$totalDailyViewRecord = $wpdb->num_rows;	
}
$max_day = 0;
$max_day_d = 0;
$max_day_tick = ''; 
$new_arr = array();
$newViewArr = array();
if($totalDailyViewRecord > 0)
{
	foreach($getDailyRecords as $dailyRecord)
	{		
		$hour[] = $dailyRecord['hour'];
		$record[] = $dailyRecord['record'];
		$new_arr[$dailyRecord['hour']] = $dailyRecord['record'];
	}
	
	foreach($getDailyViewRecords as $dailyViewRecord)
	{
		$newViewArr[$dailyViewRecord['hour']] = $dailyViewRecord['record'];
	}
}
$new_array = array();
$newViewArray = array();
for($z=0;$z<=23;$z+=2)
{
	if( (isset($new_arr[$z]) and $new_arr[$z]!="") || (isset($new_arr[$z+1]) and $new_arr[$z+1]!="") )	
	{
		$new_array[$z] = ( isset($new_arr[$z]) ? $new_arr[$z] : '' ) + ( isset($new_arr[$z+1]) ? $new_arr[$z+1] : '' );	
	}
	if( (isset($newViewArr[$z]) and $newViewArr[$z]!="") || (isset($newViewArr[$z+1]) and $newViewArr[$z+1]!="") )		
	{
		$newViewArray[$z] = ( isset($newViewArr[$z]) ? $newViewArr[$z] : '' ) + ( isset($newViewArr[$z+1]) ? $newViewArr[$z+1] : '' );	
	}
}

for($i = 1; $i<=24; $i+=2)
{
	if(array_key_exists($i-1,$new_array))
	{
		$record[$i] =  $new_array[$i-1];	
	}
	else{
		$record[$i] = 0;
	}
	
	if($i == 24) 				
		@$dailyline1 .= $record[$i];
	else
		@$dailyline1 .= $record[$i].',';
		
	
	if(array_key_exists($i-1,$newViewArray))
		$viewRecord[$i] =  $newViewArray[$i-1];	
	else
		$viewRecord[$i] = 0;
			
	if($i == 24) 				
		@$dailyline2 .= $viewRecord[$i];
	else
		@$dailyline2 .= $viewRecord[$i].',';
	
	$max_day = max( $max_day, $record[$i]);
	$max_view_day = max( $max_view_day, $viewRecord[$i]);
	
}
if( $max_day < 5 && $max_view_day <=5) 
	$max_day_tick = 5;

?>
<script type="text/javascript" language="javascript">
jQuery.noConflict();
jQuery(document).ready(function($){

		var s1 = [<?php echo $dailyline1; ?>];

		var s2 = [<?php echo $dailyline2; ?>];
		        
		var ticks_daily = ['00:00', '02:00', '04:00', '06:00', '08:00','10:00','12:00','14:00','16:00', '18:00', '20:00', '22:00'];
		
           var plot2 = $.jqplot('chart4', [s1, s2], {
			 animate: !$.jqplot.use_excanvas,
			 seriesColors:[ '#FF5959','#00CCFF'],
                
                seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					pointLabels: { show: true },
					
				},
                axes: {
					xaxis: {
						
						renderer: $.jqplot.CategoryAxisRenderer,
						tickOptions:{
							angle:0
						},
						tickRenderer:$.jqplot.CanvasAxisTickRenderer,
						ticks: ticks_daily
					},
					yaxis: {
						
						min : 0,
						<?php if($max_day_tick == 5){ echo 'max : 6, numberTicks: 4,'; } ?>
						
					},
				},
                highlighter: { 
                    show: true, 
                    showTooltip: true,      
                    tooltipLocation: 'nw',  
                    tooltipAxes: 'both',    
                    showMarker: true,
                    useAxesFormatters: false,
                    
                    tooltipContentEditor: function(str, seriesIndex, pointIndex, jqPlot) {
                        var re_var = '<table class="jqplot-highlighter"><tr><td class="tooltip_title"><?php _e('Hour','ARForms');?> :</td><td class="tooltip_title1">&nbsp;'+ticks_daily[pointIndex]+'</td></tr><tr><td class="tooltip_title"><?php _e('Entries','ARForms');?> :</td><td class="tooltip_title1">&nbsp;'+s1[pointIndex]+'</td></tr><tr><td class="tooltip_title"><?php _e('Views','ARForms');?> :</td><td class="tooltip_title1">&nbsp;'+s2[pointIndex]+'</td></tr></table>';
                        return re_var;
                    }
                }
            });
	});	
</script>
<?php } ?>
<div id="chart_div">
<div id="daily" style="padding:15px; <?php if($type == 'daily' ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">
	
    <div class="arlinks link_align">
	<?php 
	
	$date_limit = date('Y-m-d',mktime(0, 0, 0, $new_day_month, $new_day, $new_day_year));
	$min_date_limit = date('Y-m-d',mktime(0, 0, 0, $min_month, $min_date, $min_year));
	
	if($min_date_limit >= $date_limit)
	{ ?>
    	<div class="prev_div">
 	    <div class="prev_inner_div"><img src="<?php echo ARFURL;?>/images/prev-btn.png" height="41" width="31" border="0" /></div> 
        </div>
    <?php
	}
	else
	{ ?>
    	<div class="prev_div">
    	<div class="prev_inner_div"><a  href="javascript:void(0)" class="next_chart link_enable" onclick="change_graph_pre('<?php echo $type?>')"><img src="<?php echo ARFURL;?>/images/prev-btn_hover.png" height="41" width="31" border="0" /></a></div> 
        </div>
    <?php 
	} 
	$max_date_limit = date('Y-m-d',mktime(0, 0, 0, $new_day_month, $new_day, $new_day_year));
	if($max_date_limit >= date('Y-m-d'))
	{ ?>
    	<div class="next_div">
		<div class="next_inner_div"><img src="<?php echo ARFURL;?>/images/next-btn.png" height="41" width="31" border="0" /></div>	
        </div>
     <?php
	}
    else
	{ ?> 
    <div class="next_div">   
    <div class="next_inner_div"><a  href="javascript:void(0)" class="next_chart link_enable" onclick="change_graph_next('<?php echo $type?>')"><img src="<?php echo ARFURL;?>/images/next-btn_hover.png" height="41" width="31" border="0" /></a></div>
    </div>
	<?php } ?>
    </div>
    <div id="chart4" style="width:100%;height:300px;" ></div>
	<br /><br />
    <div style="width:100%; text-align:left;"><div class="arflbtitlebig"><?php echo date(get_option('date_format'), strtotime($new_day.'-'.$new_day_month.'-'.$new_day_year));?></div></div>
    <div style="width:100%; text-align:right; margin-top:-20px;">
    	<div><img src="<?php echo ARFURL;?>/images/entries.png" width="95" height="32" border="0" /></div>
        <div><img src="<?php echo ARFURL;?>/images/views.png" width="95" height="32" border="0" /></div>
    </div>
    <input type="hidden" value="<?php echo  $new_day;?>" name="current_day" id="current_day" />
    <input type="hidden" value="<?php echo  $new_day_month;?>" name="current_day_month" id="current_day_month" />
    <input type="hidden" value="<?php echo  $new_day_year;?>" name="current_day_year" id="current_day_year" />
</div>

<div id="weekly" style="padding:15px; <?php if($type == 'weekly' ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">
<label class="lbltitle">Weekly chart</label><br />

	<div class="arlinks link_align">
	<?php 
	if(isset($new_week) and $new_week == '01')
	{ ?>
    	<div class="prev_div">
 	    <div class="prev_inner_div"><img src="<?php echo ARFURL;?>/images/prev-btn.png" height="41" width="31" border="0" /></div> 
        </div>
    <?php
	}
	else
	{ ?>
    	<div class="prev_div">
    	<div class="prev_inner_div"><a  href="javascript:void(0)" class="next_chart link_enable" onclick="change_graph_pre('<?php echo $type?>')"><img src="<?php echo ARFURL;?>/images/prev-btn_hover.png" height="41" width="31" border="0"/></a></div> 
        </div>
    <?php 
	} 
	if(isset($new_week) and isset($last_week) and $new_week == $last_week)
	{ ?>
    	<div class="next_div">
		<div class="next_inner_div"><img src="<?php echo ARFURL;?>/images/next-btn.png" height="41" width="31" border="0" /></div>	
        </div>
     <?php
	}
    else
	{ ?>
    <div class="next_div">    
    <div class="next_inner_div"><a  href="javascript:void(0)" class="next_chart link_enable" onclick="change_graph_next('<?php echo $type?>')"><img src="<?php echo ARFURL;?>/images/next-btn_hover.png" height="41" width="31" border="0" /></a></div>
    </div>
	<?php } ?>
    	</div>
	<div id="chart3" style="width:100%;height:300px;" ></div>
	<br /><Br />
    <input type="hidden" value="<?php echo  $new_week;?>" name="current_week" id="current_week" />
    <input type="hidden" value="<?php echo  $new_week_year;?>" name="current_week_year" id="current_week_year" />
    
</div>

<div id="monthly" style="padding:15px; <?php if($type == 'monthly' ) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">
<?php
$monthName = date("F", mktime(0, 0, 0, $new_month, 10));
?>
	<div class="arlinks link_align">
	<?php 
	
	$month_limit = date('Y-m-d',mktime(0, 0, 0, $new_month, 1, $new_month_year));
	$min_month_limit = date('Y-m-d',mktime(0, 0, 0, $min_month, 1, $min_year));
	
	if($min_month_limit >= $month_limit)
	{ ?>
    	<div class="prev_div">
 	    <div class="prev_inner_div"><img src="<?php echo ARFURL;?>/images/prev-btn.png" height="41" width="31" border="0" /></div> 
        </div>
    <?php
	}
	else
	{ ?>
    	<div class="prev_div">
    	<div class="prev_inner_div"><a href="javascript:void(0)" class="next_chart link_enable" onclick="change_graph_pre('<?php echo $type?>')"><img src="<?php echo ARFURL;?>/images/prev-btn_hover.png" height="41" width="31" border="0" /></a></div> 
        </div>
    <?php 
	} 
	$max_month_limit = date('Y-m',mktime(0, 0, 0, $new_month,1 , $new_month_year));
	if($max_month_limit >= date('Y-m'))
	{ ?>
    	<div class="next_div">
		<div class="next_inner_div"><img src="<?php echo ARFURL;?>/images/next-btn.png" height="41" width="31" border="0" /></div>	
        </div>
     <?php
	}
    else
	{ ?> 
    <div class="next_div">   
    <div class="next_inner_div"><a  href="javascript:void(0)" class="next_chart link_enable" onclick="change_graph_next('<?php echo $type?>')"><img src="<?php echo ARFURL;?>/images/next-btn_hover.png" height="41" width="31" border="0" /></a></div>
    </div>
	<?php } ?>
    </div>
	<div id="chart2" style="width:100%;height:300px;" ></div>
	<br /><br />
    <div style="width:100%; text-align:left;"><label class="arflbtitlebig"><?php echo  $monthName."-".$new_month_year;?></label></div>
    <div style="width:100%; text-align:right; margin-top:-20px;">
    	<div><img src="<?php echo ARFURL;?>/images/entries.png" width="95" height="32" border="0" /></div>
        <div><img src="<?php echo ARFURL;?>/images/views.png" width="95" height="32" border="0" /></div>
    </div>
    <input type="hidden" value="<?php echo  $new_month;?>" name="current_month" id="current_month" />
    <input type="hidden" value="<?php echo  $new_month_year;?>" name="current_month_year" id="current_month_year" />
</div>

<div id="yearly" style="padding:15px; <?php if($type == 'yearly' ) { echo 'display:block;'; } else { echo 'display:none;';}  ?>">

	<div class="arlinks link_align">
    <?php 
	if($new_year <= $min_year)
	{ ?>
    	<div class="prev_div">
 	    <div class="prev_inner_div"><img src="<?php echo ARFURL;?>/images/prev-btn.png" height="41" width="31" border="0" /></div> 
        </div>
    <?php
	}
	else
	{ ?>
    	<div class="prev_div">
     	<div class="prev_inner_div"><a  href="javascript:void(0)" class="next_chart link_enable" onclick="change_graph_pre('<?php echo $type?>')"><img src="<?php echo ARFURL;?>/images/prev-btn_hover.png" height="41" width="31" border="0" /></a></div> 
        </div>
      <?php 
	} 
	if($new_year >= date('Y', current_time('timestamp')))
	{ ?>
    	<div class="next_div">
		<div class="next_inner_div"><img src="<?php echo ARFURL;?>/images/next-btn.png" height="41" width="31" border="0" /></div>	
        </div>
     <?php
	}
    else
	{ ?> 
    <div class="next_div">   
    <div class="next_inner_div"><a class="next_chart link_enable " href="javascript:void(0)" onclick="change_graph_next('<?php echo $type?>');"><img src="<?php echo ARFURL;?>/images/next-btn_hover.png" height="41" width="31" border="0" /></a></div>
    </div>
	<?php } ?>
     </div>
	<div id="chart1" style="width:100%;height:300px;" ></div>
    <br /><br />
    <div style="width:100%; text-align:left;"><label class="arflbtitlebig"><?php echo $new_year;?></label></div>
    <div style="width:100%; text-align:right; margin-top:-20px;">
    	<div><img src="<?php echo ARFURL;?>/images/entries.png" width="95" height="32" border="0" /></div>
        <div><img src="<?php echo ARFURL;?>/images/views.png" width="95" height="32" border="0" /></div>
    </div>
   	<input type="hidden" value="<?php echo  $new_year;?>" name="current_year" id="current_year" />
</div>