<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<div class="wrap">
<?php
  require(PRLI_VIEWS_PATH.'/shared/nav.php');
?>
  <?php echo PrliAppHelper::page_title(__('Hits', 'pretty-link')); ?>
  <span style="font-size: 14px; font-weight: bold;"><?php echo __('For', 'pretty-link').' '.stripslashes($link_name); ?>: </span>
  <?php
  // Don't show this sheesh if we're displaying the vuid or ip grouping
  if(empty($params['ip']) and empty($params['vuid']))
  {
  ?>
  <a href="#" style="display:inline;" class="filter_toggle"><?php _e('Customize Report', 'pretty-link'); ?></a>
  <?php
  }
  ?>
<?php
  if(!empty($params['l']) and $params['l'] != 'all')
    echo '<br/><a href="'.admin_url("admin.php?page=pretty-link").'">&laquo '.__("Back to Links", 'pretty-link').'</a>';
  else if(!empty($params['ip']) or !empty($params['vuid']))
    echo '<br/><a href="?page='. PRLI_PLUGIN_NAME .'/prli-clicks.php">&laquo '.__("Back to Hits", 'pretty-link').'</a>';

  if(empty($params['ip']) and empty($params['vuid']))
  {
?>


<div class="filter_pane">
  <form class="form-fields" name="form2" method="post" action="">
    <?php wp_nonce_field('prli-reports'); ?>
    <span><?php _e('Type:', 'pretty-link'); ?></span>&nbsp;
    <select id="type" name="type" style="display: inline;">
      <option value="all"<?php print ((empty($params['type']) or $params['type'] == "all")?" selected=\"true\"":""); ?>><?php _e('All Hits', 'pretty-link'); ?>&nbsp;</option>
      <option value="unique"<?php print (($params['type'] == "unique")?" selected=\"true\"":""); ?>><?php _e('Unique Hits', 'pretty-link'); ?>&nbsp;</option>
    </select>
    <br/>
    <br/>
    <span><?php _e('Date Range:', 'pretty-link'); ?></span>
    <div id="dateselectors" style="display: inline;">
      <input type="text" name="sdate" id="sdate" value="<?php echo $params['sdate']; ?>" style="display:inline;"/>&nbsp;<?php _e('to', 'pretty-link'); ?>&nbsp;<input type="text" name="edate" id="edate" value="<?php echo $params['edate']; ?>" style="display:inline;"/>
    </div>
    <br/>
    <br/>
    <div class="submit" style="display: inline;"><input type="submit" name="Submit" value="Customize"/> <?php _e('or', 'pretty-link'); ?> <a href="#" class="filter_toggle"><?php _e('Cancel', 'pretty-link'); ?></a></div>
  </form>
</div>

<div id="my_chart"></div>

<?php
  }
  $navstyle = "float: right;";
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>

  <div id="search_pane" style="padding-top: 5px;">
    <form class="form-fields" name="click_form" method="post" action="">
      <?php wp_nonce_field('prli-clicks'); ?>

      <input type="hidden" name="sort" id="sort" value="<?php echo $sort_str; ?>" />
      <input type="hidden" name="sdir" id="sort" value="<?php echo $sdir_str; ?>" />
      <input type="text" name="search" id="search" value="<?php echo esc_attr($search_str); ?>" style="display:inline;"/>
      <div class="submit" style="display: inline;"><input type="submit" name="Submit" value="Search Hits"/>
      <?php
      if(!empty($search_str))
      {
      ?>
      <?php _e('or', 'pretty-link'); ?> <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo (!empty($params['l'])?'&l='.$params['l']:''); ?>"><?php _e('Reset', 'pretty-link'); ?></a>
      <?php
      }
      ?>
      </div>
    </form>
  </div>
<table class="widefat post fixed" cellspacing="0">
    <thead>
    <tr>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column" width="5%"><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=btype<?php echo (($sort_str == 'btype' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Browser', 'pretty-link'); echo (($sort_str == 'btype')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php } ?>
      <th class="manage-column" width="12%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=ip<?php echo (($sort_str == 'ip' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('IP', 'pretty-link'); echo (($sort_str == 'ip')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column" width="12%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=vuid<?php echo (($sort_str == 'vuid' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Visitor', 'pretty-link'); echo (($sort_str == 'vuid')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php } ?>
      <th class="manage-column" width="13%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=created_at<?php echo (($sort_str == 'created_at' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Timestamp', 'pretty-link'); echo ((empty($sort_str) or $sort_str == 'created_at')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.((empty($sort_str) or $sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column" width="16%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=host<?php echo (($sort_str == 'host' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Host', 'pretty-link'); echo (($sort_str == 'host')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php } ?>
      <th class="manage-column" width="16%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=uri<?php echo (($sort_str == 'uri' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('URI', 'pretty-link'); echo (($sort_str == 'uri')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
      <th class="manage-column" width="16%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=referer<?php echo (($sort_str == 'referer' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Referrer', 'pretty-link'); echo (($sort_str == 'referer')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
      <th class="manage-column" width="13%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=link<?php echo (($sort_str == 'link' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Link', 'pretty-link'); echo (($sort_str == 'link')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    </tr>
    </thead>
  <?php

  if(count($clicks) <= 0)
  {
      ?>
    <tr>
      <td colspan="7"><?php _e('No Hits have been recorded yet', 'pretty-link'); ?></td>
    </tr>
    <?php
  }
  else
  {
    foreach($clicks as $click)
    {
      ?>
      <tr>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
        <td><img src="<?php echo esc_html(PRLI_BROWSER_URL); ?>/<?php echo prli_browser_image($click->btype); ?>" alt="<?php echo $click->btype . " v" . $click->bversion; ?>" title="<?php echo $click->btype . " v" . $click->bversion; ?>"/>&nbsp;<img src="<?php echo esc_html(PRLI_OS_URL); ?>/<?php echo prli_os_image($click->os); ?>" alt="<?php echo $click->os; ?>" title="<?php echo $click->os; ?>"/></td>
    <?php } ?>
        <td><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php&ip=<?php echo $click->ip; ?>" title="View All Activity for IP Address: <?php echo $click->ip; ?>"><?php echo $click->ip; ?> (<?php echo $click->ip_count; ?>)</a></td>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
        <td><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php&vuid=<?php echo $click->vuid; ?>" title="View All Activity for Visitor: <?php echo $click->vuid; ?>"><?php echo $click->vuid; ?><?php echo (($click->vuid != null)?" ($click->vuid_count)":''); ?></a></td>
    <?php } ?>
        <td><?php echo $click->created_at; ?></td>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
        <td><?php echo $click->host; ?></td>
    <?php } ?>
        <td><?php echo $click->uri; ?></td>
        <td><a href="<?php echo $click->referer; ?>"><?php echo $click->referer; ?></a></td>
        <td><a href="?page=<?php print PRLI_PLUGIN_NAME; ?>/prli-clicks.php&l=<?php echo $click->link_id; ?>" title="View clicks for <?php echo stripslashes($click->link_name); ?>"><?php echo stripslashes($click->link_name); ?></a></td>
      </tr>
      <?php
    }
  }
  ?>
    <tfoot>
    <tr>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column"><?php _e('Browser', 'pretty-link'); ?></th>
    <?php } ?>
      <th class="manage-column"><?php _e('IP', 'pretty-link'); ?></th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column"><?php _e('Visitor', 'pretty-link'); ?></th>
    <?php } ?>
      <th class="manage-column"><?php _e('Timestamp', 'pretty-link'); ?></th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column"><?php _e('Host', 'pretty-link'); ?></th>
    <?php } ?>
      <th class="manage-column"><?php _e('URI', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Referrer', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Link', 'pretty-link'); ?></th>
    </tr>
    </tfoot>
</table>

<a href="?page=pretty-link/prli-clicks.php&action=csv<?php echo $page_params; ?>"><?php _e('Download CSV', 'pretty-link'); ?> (<?php echo stripslashes($link_name); ?>)</a>

<?php
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>

</div>
