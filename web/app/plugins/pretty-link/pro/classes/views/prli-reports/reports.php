<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<div class="wrap">
  <?php echo PrliAppHelper::page_title(__('Reports', 'pretty-link')); ?>
  <br/>
  <ul style="list-style-type: none;">
    <li><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&action=list"><?php _e('Link Reports', 'pretty-link'); ?></a></li>
  </ul>
</div>
