<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); } ?>

<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("#sdate").datepicker({ dateFormat: 'yy-mm-dd', defaultDate: -30, minDate: -<?php echo $min_date; ?>, maxDate: 0 });
    jQuery("#edate").datepicker({ dateFormat: 'yy-mm-dd', minDate: -<?php echo $min_date; ?>, maxDate: 0 });
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

</style>

<!-- Open Flash Chart Includes -->
<script type="text/javascript" src="<?php echo PRLI_URL; ?>/pro/includes/version-2-kvasir/js/swfobject.js"></script>

<script type="text/javascript">
swfobject.embedSWF( "<?php echo PRLI_URL; ?>/pro/includes/version-2-kvasir/open-flash-chart.swf", "clicks_chart", "100%", "300", "9.0.0", "expressInstall.swf", {"data-file":"<?php echo urlencode(PRLIPRO_VIEWS_URL."/prli-reports/custom-clicks-chart-data.php?sdate=$start_timestamp&edate=$end_timestamp&id=$id"); ?>"} );

</script>
