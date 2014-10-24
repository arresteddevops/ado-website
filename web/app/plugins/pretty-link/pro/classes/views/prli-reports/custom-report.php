<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<div class="wrap">
<?php echo PrliAppHelper::page_title(__('Link Report', 'pretty-link')); ?>
  <h3><?php _e('Report:', 'pretty-link'); ?> "<?php echo $report->name; ?>"</h3>
  <?php if( !empty($report->goal_link_id) ) { ?>
    <h4><?php _e('For Goal Link:', 'pretty-link'); ?> "<?php echo $goal_link->name; ?>"</h4>
  <?php } ?>
  <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=list">&laquo <?php _e('Back to Link Reports', 'pretty-link'); ?></a>&nbsp;|&nbsp;<a href="#" style="display:inline;" class="filter_toggle"><?php _e('Customize Report', 'pretty-link'); ?></a>
  <div class="filter_pane">
    <form class="form-fields" name="form2" method="post" action="">
      <?php wp_nonce_field('prli-reports'); ?>
      <span><?php _e('Date Range:', 'pretty-link'); ?></span>
      <div id="dateselectors" style="display: inline;">
        <input type="text" name="sdate" id="sdate" value="<?php echo $params['sdate']; ?>" style="display:inline;"/>&nbsp;to&nbsp;<input type="text" name="edate" id="edate" value="<?php echo $params['edate']; ?>" style="display:inline;"/>
      </div>
      <br/>
      <br/>
      <div class="submit" style="display: inline;"><input type="submit" name="Submit" value="Customize"/> <?php _e('or', 'pretty-link'); ?> <a href="#" class="filter_toggle"><?php _e('Cancel', 'pretty-link'); ?></a></div>
    </form>
  </div>
  <div id="clicks_chart"></div>
  <br/><br/>
<table class="widefat post fixed" cellspacing="0">
    <thead>
    <tr>
      <th class="manage-column" width="40%"><?php _e('Link Name', 'pretty-link'); ?></th>
      <th class="manage-column" width="15%"><?php _e('Hits', 'pretty-link'); ?></th>
      <th class="manage-column" width="15%"><?php _e('Uniques', 'pretty-link'); ?></th>
      <?php if( !empty($report->goal_link_id) ) { ?>
      <th class="manage-column" width="15%"><?php _e('Conversions', 'pretty-link'); ?></th>
      <th class="manage-column" width="15%"><?php _e('Conv Rate', 'pretty-link'); ?></th>
      <?php } ?>
    </tr>
    </thead>
  <?php

  for($i=0;$i<count($links);$i++)
  {
    $label        = stripslashes($labels[$i]);
    $hit_count    = $hits[$i];
    $unique_count = $uniques[$i];
    $conv_count   = $conversions[$i];
    $conv_rate    = $conv_rates[$i];
    ?>
    <tr>
      <td><?php echo "<strong>{$label}</strong>"; ?></td>
      <td<?php echo (((float)$hit_count == (float)$top_hits)?' style="font-weight: bold;"':'') ?>><?php echo $hit_count; ?></td>
      <td<?php echo (((float)$unique_count == (float)$top_uniques)?' style="font-weight: bold;"':'') ?>><?php echo $unique_count; ?></td>
    <?php if( !empty($report->goal_link_id) ) { ?>
      <td<?php echo (((float)$conv_count == (float)$top_conversions)?' style="font-weight: bold;"':'') ?>><?php echo $conv_count; ?></td>
      <td<?php echo (((float)$conv_rate == (float)$top_conv_rate)?' style="font-weight: bold;"':'') ?>><?php echo $conv_rate; ?>%</td>
    <?php } ?>
    </tr>
    <?php
  }
  ?>
    <tfoot>
    <tr>
      <th class="manage-column"><?php _e('Link Name', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Hits', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Uniques', 'pretty-link'); ?></th>
      <?php if( !empty($report->goal_link_id) ) { ?>
      <th class="manage-column"><?php _e('Conversions', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Conv Rate', 'pretty-link'); ?></th>
      <?php } ?>
    </tr>
    </tfoot>
</table>
</div>
