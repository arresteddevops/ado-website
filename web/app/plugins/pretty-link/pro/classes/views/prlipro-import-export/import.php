<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<div class="wrap">
<?php echo PrliAppHelper::page_title(__('Import Results', 'pretty-link')); ?>
<p><?php _e('Total Rows:', 'pretty-link'); echo $total_row_count; ?></p>

<p><?php echo $successful_create_count; _e('Pretty Links were Successfully Created', 'pretty-link'); ?></p>
<p><?php echo $successful_update_count; _e('Pretty Links were Successfully Updated', 'pretty-link'); ?></p>

<?php
if(count($creation_errors) > 0)
{
?>
  <p><?php echo count($creation_errors); _e('Pretty Links were unable to be Created:', 'pretty-link'); ?></p>
<?php
  foreach($creation_errors as $creation_error)
  {
    ?>
    <p class="wp-error"><?php _e('Error(s) for Pretty Link with Slug:', 'pretty-link'); ?> <?php echo $creation_error['slug']; ?><br/>
    <?php
      foreach( $creation_error['errors'] as $error )
      {
        ?>
        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $error; ?><br/>
        <?php
      }
    ?>
    </p> 
    <?php
  }
}

if(count($update_errors) > 0)
{
?>
  <p><?php echo count($update_errors); _e('Pretty Links were unable to be Updated:', 'pretty-link'); ?></p>
<?php
  foreach($update_errors as $update_error)
  {
    ?>
    <p class="wp-error"><?php _e('Error(s) for Pretty Link with id:', 'pretty-link'); ?> <?php echo $update_error['id']; ?><br/>
    <?php
      foreach( $update_error['errors'] as $error )
      {
        ?>
        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $error; ?><br/>
        <?php
      }
    ?>
    </p> 
    <?php
  }
}
?>

<a href="<?php echo $prli_blogurl; ?>/wp-admin/admin.php?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-import-export.php">&laquo; <?php _e('Back', 'pretty-link'); ?></a>
</div>
