<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("#sdate").datepicker({ dateFormat: 'yy-mm-dd', defaultDate: -30, minDate: -<?php echo esc_js($min_date); ?>, maxDate: 0 });
    jQuery("#edate").datepicker({ dateFormat: 'yy-mm-dd', minDate: -<?php echo esc_js($min_date); ?>, maxDate: 0 });
  });
</script>

<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery(".filter_pane").hide();
  jQuery(".filter_toggle").click( function () {
      jQuery(".filter_pane").slideToggle("slow");
  });
});
</script>

<style type="text/css">
.filter_toggle {
  line-height: 34px;
  font-size: 14px;
  font-weight: bold;
  padding-bottom: 10px;
}

.filter_pane {
  background-color: white;
  border: 2px solid #777777;
  height: 275px;
  width: 600px;
  padding-left: 20px;
  padding-top: 10px;
}

div#my_chart {
  height:300px;
  margin-bottom:15px;
}

</style>

<!-- GOOGLE CHARTS STUFF -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
  //Hits Chart
  var hitsChartJsonData = <?php echo $prli_click->setupClickLineGraph($start_timestamp, $end_timestamp, $link_id, $type, $group); ?>;
  
  var hitsChartData = new google.visualization.DataTable(hitsChartJsonData);
  
  var hitsChart = new google.visualization.AreaChart(document.getElementById('my_chart'));
  
  hitsChart.draw(hitsChartData, {height: '300', title: "<?php echo $prli_click->setupClickLineGraph($start_timestamp, $end_timestamp, $link_id, $type, $group, true); ?>"});
}
</script>
