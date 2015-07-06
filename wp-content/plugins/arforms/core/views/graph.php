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
?>
<?php
global $wpdb, $arformhelper;

for($i=1; $i <= 12; $i++) {

$month[$i] = $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) AS num FROM '.$wpdb->prefix.'arf_entries WHERE form_id = %d AND MONTH(created_date) = %d AND YEAR(created_date) = %d', $form, $i, date('Y', current_time('timestamp')) ), 'ARRAY_A');
$month[$i] = $month[$i][0];

}

$max = 0;
foreach ($month as $key => $val) {
    $max = max( $max, $val[ 'num' ] );
}

if( $max < 5 ) $max = 5;
?>
<script type="text/javascript" language="javascript">

$(document).ready(function(){
        $.jqplot.config.enablePlugins = true;
        
		var s1 = [<?php echo $month[1]['num'];?>, <?php echo $month[2]['num'];?>, <?php echo $month[3]['num'];?>, <?php echo $month[4]['num'];?>, <?php echo $month[5]['num'];?>, <?php echo $month[6]['num'];?>, <?php echo $month[7]['num'];?>, <?php echo $month[8]['num'];?>, <?php echo $month[9]['num'];?>, <?php echo $month[10]['num'];?>, <?php echo $month[11]['num'];?>, <?php echo $month[12]['num'];?>];
        
		var ticks = ['Jan <?php echo date('Y', current_time('timestamp'));?>', 'Feb <?php echo date('Y', current_time('timestamp'));?>', 'Mar <?php echo date('Y', current_time('timestamp'));?>', 'Apr <?php echo date('Y', current_time('timestamp'));?>', 'Jun <?php echo date('Y', current_time('timestamp'));?>', 'Jul <?php echo date('Y', current_time('timestamp'));?>', 'Aug <?php echo date('Y', current_time('timestamp'));?>', 'Sep <?php echo date('Y', current_time('timestamp'));?>', 'Oct <?php echo date('Y', current_time('timestamp'));?>', 'Nov <?php echo date('Y', current_time('timestamp'));?>', 'Dec <?php echo date('Y', current_time('timestamp'));?>'];
         
        plot1 = $.jqplot('chart1', [s1], {
           
            animate: !$.jqplot.use_excanvas,
			seriesColors:['#0384AE', '#0384AE', '#0384AE', '#0384AE', '#0384AE', '#0384AE', '#0384AE', '#0384AE', '#0384AE', '#0384AE', '#0384AE', '#0384AE'],
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
					<?php if($max == 5){ echo 'max : 6, numberTicks: 4,'; } ?>
        			
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
                	var re_var = '<table class="jqplot-highlighter"><tr><td class="tooltip_title">Month:</td><td>&nbsp;'+ticks[pointIndex]+'</td></tr><tr><td class="tooltip_title">Entries:</td><td>&nbsp;'+s1[pointIndex]+'</td></tr></table>';
								  
					return re_var;
            	}
            }
			
        });
     
        $('#chart1').bind('jqplotDataClick',
            function (ev, seriesIndex, pointIndex, data) {
                $('#info1').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
            }
        );
    });
</script>
    
<?php
$date = date('Y', current_time( 'timestamp' )).'-'.date('m', current_time( 'timestamp' )).'-05';

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

$day_array = makeDayArray( date('Y', current_time( 'timestamp' )).'-'.date('m', current_time( 'timestamp' )).'-'.$day_first, date('Y', current_time( 'timestamp' )).'-'.date('m', current_time( 'timestamp' )).'-'.$day_last );



foreach($day_array as $day) {
	$day_arr[$day] = $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) AS num FROM '.$wpdb->prefix.'arf_entries WHERE form_id = %d AND CAST(created_date AS DATE) = %s', $form, $day), 'ARRAY_A');
	$day_arr[$day] = $day_arr[$day][0];
		
}

$day_var  = '[';
$val_var  = '[';
$col_var  = '[';

foreach( $day_arr as $day => $val ) {
	$day_var  .= "'".date('d', strtotime($day) )."-".date('M', strtotime($day) )."', ";
	$val_var  .= $val['num'].', ';
	$col_var  .= "'#0384AE', ";
}

$day_var .= ']'; 
$val_var .= ']';
$col_var .= ']';

$max_day = 0;
foreach ($day_arr as $key => $val) {
    $max_day = max( $max_day, $val[ 'num' ] );
}

if( $max_day < 5 ) $max_day = 5;
?>

<script type="text/javascript" language="javascript">

$(document).ready(function(){
        $.jqplot.config.enablePlugins = true;
        
		var s1 = <?php echo $val_var; ?>;
        
		var ticks = <?php echo $day_var; ?>;
         
        plot1 = $.jqplot('chart2', [s1], {
            
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
		                angle:-30
        		    },
					tickRenderer:$.jqplot.CanvasAxisTickRenderer,
                    
					ticks: ticks
                },
				yaxis: {
            		
					min : 0,
					<?php if($max_day == 5){ echo 'max : 6, numberTicks: 4,'; } ?>
        			
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
                	var re_var = '<table class="jqplot-highlighter"><tr><td class="tooltip_title">Date:</td><td>&nbsp;'+ticks[pointIndex]+'</td></tr><tr><td class="tooltip_title">Entries:</td><td>&nbsp;'+s1[pointIndex]+'</td></tr></table>';
								  
					return re_var;
            	}
            }	
		
        });
   				
		  
        $('#chart2').bind('jqplotDataClick',
            function (ev, seriesIndex, pointIndex, data) {
                $('#info2').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
            }
        );
									
    });
		
</script>

<div id="chart_div">

<label class="lbltitle">Graph for form entries:</label> <div class="sltstandard" style="float:none;"><?php $arformhelper->forms_dropdown('arfredirecttolist3', $form, __('Select Form', 'ARForms'), false,  "change_graph()"); ?></div>
&nbsp;&nbsp;&nbsp;&nbsp;View:&nbsp;
<div class="sltstandard" style="float:none;">
<select name="chart_type" id="chart_type" style="width:100px;" onChange="change_graph();">
	<option value="daily" selected="selected">Daily</option>
    <option value="monthly">Monthly</option>
</select>
</div>
<br /><br />

<style type="text/css">
.jqplot-xaxis { font-weight:bold; }
.jqplot-yaxis { font-weight:bold; }
.jqplot-highlighter { border:solid 1px #0384AE; background-color:#eee; }
.jqplot-highlighter .tooltip_title {font-weight:bold; }
</style>


<div id="daily" style="padding:15px;">
<label class="lbltitle">Daily chart</label><br />

	<div id="chart2" style="width:100%;height:300px;" ></div>

</div>


<div id="monthly" style="padding:15px; display:none;">
<label class="lbltitle">Month chart</label><br />

	<div id="chart1" style="width:100%;height:300px;" ></div>

</div>

</div>