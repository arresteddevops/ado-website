<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<div class="wrap">
  <?php echo PrliAppHelper::page_title(__('Import / Export Links', 'pretty-link')); ?>
  <p><a href="http://prettylinkpro.com/user-manual"><?php _e('User Manual', 'pretty-link'); ?></a></p>
  <h3><?php _e('Export Pretty Links', 'pretty-link'); ?></h3>
  <a href="<?php echo PRLIPRO_URL; ?>/prlipro-import-export.php?action=export"><?php _e('Export', 'pretty-link'); ?></a>
  <br/><span class="description"><?php _e('Export Links into a CSV File', 'pretty-link'); ?></span>
  <br/><br/>
  <h3><?php _e('Import Pretty Links', 'pretty-link'); ?></h3>
  <form enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">
    <?php wp_nonce_field('update-options'); ?>
    <input type="hidden" name="action" value="import">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
    <?php _e('Choose a file to import:', 'pretty-link'); ?> <input name="importedfile" type="file" /><br />
    <span class="description"><?php _e('Select a file that has been formatted as a Pretty Link CSV import file.', 'pretty-link'); ?></span>
    <br/><input type="submit" value="<?php _e('Import File', 'pretty-link'); ?>" />
  </form>
  
  <p><?php _e('Note: There are two ways to import a file. 1) Importing to update existing links and 2) Importing to generate new links. When Importing to generate new links, you must delte the "id" column from the CSV before importing. If the "id" column is present, Pretty Link Pro will attempt to update existing links.', 'pretty-link'); ?></p>
</div>
