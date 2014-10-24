<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>
<div class="wrap">
<?php
  require(PRLI_VIEWS_PATH.'/shared/nav.php');
?>
  <?php echo PrliAppHelper::page_title(__('Groups', 'pretty-link')); ?>
  <div id="message" class="updated fade" style="padding:5px;"><?php echo $prli_message; ?></div> 
  <div id="search_pane" style="float: right;">
    <form class="form-fields" name="group_form" method="post" action="">
      <?php wp_nonce_field('prli-groups'); ?>
      <input type="hidden" name="sort" id="sort" value="<?php echo $sort_str; ?>" />
      <input type="hidden" name="sdir" id="sort" value="<?php echo $sdir_str; ?>" />
      <input type="text" name="search" id="search" value="<?php echo esc_attr($search_str); ?>" style="display:inline;"/>
      <div class="submit" style="display: inline;"><input type="submit" name="Submit" value="Search"/>
      <?php
      if(!empty($search_str))
      {
      ?>
      <?php _e('or', 'pretty-link'); ?> <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php"><?php _e('Reset', 'pretty-link'); ?></a>
      <?php
      }
      ?>
      </div>
    </form>
  </div>
  <div id="button_bar">
    <p><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php&action=new"><?php _e('Add a Pretty Link Group', 'pretty-link'); ?></a></p>
  </div>

<?php
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>
<table class="widefat post fixed" cellspacing="0">
    <thead>
    <tr>
      <th class="manage-column" width="50%"><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php&sort=name<?php echo (($sort_str == 'name' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Name', 'pretty-link'); echo (($sort_str == 'name')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
      <th class="manage-column" width="20%"><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php&sort=link_count<?php echo (($sort_str == 'link_count' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Links', 'pretty-link'); echo (($sort_str == 'link_count')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
      <th class="manage-column" width="30%"><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php&sort=created_at<?php echo (($sort_str == 'created_at' and $sdir_str == 'asc')?'&sdir=desc':''); ?>"><?php _e('Created', 'pretty-link'); echo ((empty($sort_str) or $sort_str == 'created_at')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.((empty($sort_str) or $sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a></th>
    </tr>
    </thead>
  <?php

  if($record_count <= 0)
  {
      ?>
    <tr>
      <td colspan="5"><?php _e('No Pretty Link Groups were found', 'pretty-link'); ?></td>
    </tr>
    <?php
  }
  else
  {
    foreach($groups as $group)
    {
      ?>
      <tr>
        <td class="edit_group">
        <a class="group_name" href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php&action=edit&id=<?php echo $group->id; ?>" title="Edit <?php echo htmlspecialchars(stripslashes($group->name)); ?>"><?php echo htmlspecialchars(stripslashes($group->name)); ?></a>
          <br/>
          <div class="group_actions">
            <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php&action=edit&id=<?php echo $group->id; ?>" title="Edit <?php echo htmlspecialchars(stripslashes($group->name)); ?>"><?php _e('Edit', 'pretty-link'); ?></a>&nbsp;|
            <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-groups.php&action=destroy&id=<?php echo $group->id; ?>"  onclick="return confirm('Are you sure you want to delete your <?php echo htmlspecialchars(stripslashes($group->name)); ?> Pretty Link Group?');" title="Delete <?php echo htmlspecialchars(stripslashes($group->name)); ?>"><?php _e('Delete', 'pretty-link'); ?></a>&nbsp;|
            <a href="<?php echo admin_url("admin.php?page=pretty-link&group={$group->id}"); ?>" title="View links in <?php echo htmlspecialchars(stripslashes($group->name)); ?>"><?php _e('Links', 'pretty-link'); ?></a>&nbsp;|
            <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php&group=<?php echo $group->id; ?>" title="View hits in <?php echo htmlspecialchars(stripslashes($group->name)); ?>"><?php _e('Hits', 'pretty-link'); ?></a>
          </div>
        </td>
        <td><a href="<?php echo admin_url("admin.php?page=pretty-link&group={$group->id}"); ?>" title="View links in <?php echo htmlspecialchars(stripslashes($group->name)); ?>"><?php echo $group->link_count; ?></a></td>
        <td><?php echo $group->created_at; ?></td>
      </tr>
      <?php
    }
  }
  ?>
    <tfoot>
    <tr>
      <th class="manage-column"><?php _e('Name', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Links', 'pretty-link'); ?></th>
      <th class="manage-column"><?php _e('Created', 'pretty-link'); ?></th>
    </tr>
    </tfoot>
</table>
<?php
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>

</div>
