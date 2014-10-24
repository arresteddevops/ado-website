<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<div class="wrap">
  <?php echo PrliAppHelper::page_title(__('Link Reports', 'pretty-link')); ?>
  <div id="message" class="updated fade" style="padding:5px;"><?php echo $prli_message; ?></div> 
  <div id="search_pane" style="float: right;">
    <form class="form-fields" name="report_form" method="post" action="">
      <?php wp_nonce_field('prlipro-reports'); ?>
      <input type="hidden" name="sort" id="sort" value="<?php echo $sort_str; ?>" />
      <input type="hidden" name="sdir" id="sort" value="<?php echo $sdir_str; ?>" />
      <input type="text" name="search" id="search" value="<?php echo esc_attr($search_str); ?>" style="display:inline;"/>
      <div class="submit" style="display: inline;"><input type="submit" name="Submit" value="Search"/>
      <?php
      if(!empty($search_str))
      {
      ?>
      <?php _e('or', 'pretty-link'); ?> <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=list"><?php _e('Reset', 'pretty-link'); ?></a>
      <?php
      }
      ?>
      </div>
    </form>
  </div>
  <div id="button_bar">
    <p><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=new"><?php _e('Add a Pretty Link Report', 'pretty-link'); ?></a>&nbsp;|&nbsp;<a href="http://prettylinkpro.com/user-manual"><?php _e('User Manual', 'pretty-link'); ?></a></p>
  </div>

<?php
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>
<table class="widefat post fixed" cellspacing="0">
    <thead>
    <tr>
      <th class="manage-column" width="35%"><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=list&sort=name<?php echo (($sort_str == 'name' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Name', 'pretty-link'); ?><?php echo (($sort_str == 'name')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
      <th class="manage-column" width="35%"><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=list&sort=goal_link_name<?php echo (($sort_str == 'goal_link_name' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Goal Link', 'pretty-link'); ?><?php echo (($sort_str == 'goal_link_name')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
      <th class="manage-column" width="10%"><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=list&sort=link_count<?php echo (($sort_str == 'link_count' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Links', 'pretty-link'); ?><?php echo (($sort_str == 'link_count')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
      <th class="manage-column" width="20%"><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=list&sort=created_at<?php echo (($sort_str == 'created_at' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Created', 'pretty-link'); ?><?php echo ((empty($sort_str) or $sort_str == 'created_at')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.((empty($sort_str) or $sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
    </tr>
    </thead>
  <?php

  if($record_count <= 0)
  {
      ?>
    <tr>
      <td colspan="5"><?php _e('No Pretty Link Reports were found', 'pretty-link'); ?></td>
    </tr>
    <?php
  }
  else
  {
    foreach($reports as $report)
    {
      ?>
      <tr>
        <td class="edit_report">
        <a class="report_name" href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=edit&id=<?php echo $report->id; ?>" title="Edit <?php echo stripslashes($report->name); ?>"><?php echo stripslashes($report->name); ?></a>
          <br/>
          <div class="report_actions">
            <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=edit&id=<?php echo $report->id; ?>" title="Edit <?php echo $report->name; ?>"><?php _e('Edit', 'pretty-link'); ?></a>&nbsp;|
            <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=destroy&id=<?php echo $report->id; ?>"  onclick="return confirm('Are you sure you want to delete your <?php echo $report->name; ?> Pretty Link Report?');" title="Delete <?php echo $report->name; ?>"><?php _e('Delete', 'pretty-link'); ?></a>&nbsp;|
            <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=display-custom-report&id=<?php echo $report->id; ?>" title="View report for <?php echo $report->name; ?>"><?php _e('View', 'pretty-link'); ?></a>
          </div>
        </td>
        <td><?php echo $report->goal_link_name; ?></td>
        <td><?php echo $report->link_count; ?></td>
        <td><?php echo $report->created_at; ?></td>
      </tr>
      <?php
    }
  }
  ?>
    <tfoot>
    <tr>
      <th class="manage-column"><?php _e('Name', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Goal Link', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Links', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Created', 'pretty-link'); ?></th>
    </tr>
    </tfoot>
</table>
<?php
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>

</div>
