<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<div class="wrap">
  <a href="http://blairwilliams.com/pretty-link"><img style="float: left; border: 0px;" src="<?php echo PRLI_IMAGES_URL . '/prettylink_logo_small.jpg'; ?>"/></a><div style="min-height: 48px;"><div style="min-height: 18px; margin-left: 137px; margin-top: 0px; padding-top: 0px; border: 1px solid #e5e597; background-color: #ffffa0; display: block;"><p style="font-size: 11px; margin:0px; padding: 0px; padding-left: 10px;"><?php echo prli_get_main_message(__("Add a Pretty Link from your Dashboard:", 'pretty-link')); ?></p></div></div>

<form name="form1" method="post" action="<?php echo admin_url("admin.php?page=pretty-link"); ?>">
<input type="hidden" name="action" value="quick-create">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
  <tr class="form-field">
    <td valign="top"><?php _e("Target URL", 'pretty-link'); ?></td>
    <td><input type="text" name="url" value="" size="75">
  </tr>
  <tr>
    <td valign="top"><?php _e("Pretty Link", 'pretty-link'); ?></td>
    <td><strong><?php echo esc_html($prli_blogurl); ?></strong>/<input type="text" name="slug" value="<?php echo $prli_link->generateValidSlug(); ?>">
  </tr>
</table>

<p class="submit">
<input type="submit" name="Submit" value="Create" />
</p>
</form>
</div>
