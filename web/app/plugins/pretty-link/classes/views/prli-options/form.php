<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<div class="wrap">
<?php echo PrliAppHelper::page_title(__('Options', 'pretty-link')); ?>
<br/>
<?php
$permalink_structure = get_option('permalink_structure');
if(!$permalink_structure or empty($permalink_structure))
{
?>
  <div class="error" style="padding-top: 5px; padding-bottom: 5px;"><strong><?php _e('WordPress Must be Configured:', 'pretty-link'); ?></strong> <?php _e("Pretty Link won't work until you select a Permalink Structure other than 'Default'", 'pretty-link'); ?> ... <a href="<?php echo $prli_siteurl; ?>/wp-admin/options-permalink.php"><?php _e('Permalink Settings', 'pretty-link'); ?></a></div>
<?php
}
?>
<?php do_action('prli-options-message'); ?>
<a href="<?php echo admin_url("admin.php?page=pretty-link"); ?>">&laquo; <?php _e('Pretty Link Admin', 'pretty-link'); ?></a>

<form name="form1" method="post" action="<?php echo admin_url("/admin.php?page=pretty-link/prli-options.php"); ?>">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<?php wp_nonce_field('update-options'); ?>

<h3><a class="toggle link-toggle-button"><?php _e('Link Options', 'pretty-link') ?> <span class="link-expand" style="display: none;">[+]</span><span class="link-collapse">[-]</span></a></h3>
<ul class="link-toggle-pane" style="list-style-type: none; padding-left: 10px;">
  <li>
    <h3><?php _e('Link Defaults:', 'pretty-link') ?></h3>
    <input type="checkbox" name="<?php echo $link_track_me; ?>" <?php echo (($prli_options->link_track_me != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Track Link', 'pretty-link'); ?>
    <br/><span class="description"><?php _e('Default all new links to be tracked.', 'pretty-link'); ?></span>
  </li>
  <li>
    <input type="checkbox" name="<?php echo $link_nofollow; ?>" <?php echo (($prli_options->link_nofollow != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Add <code>nofollow</code> to Link', 'pretty-link'); ?>
<br/><span class="description"><?php _e('Add the <code>nofollow</code> attribute by default to new links.', 'pretty-link'); ?></span>
  </li>
  <li>
    <input type="checkbox" name="<?php echo $link_prefix; ?>" <?php echo (($prli_options->link_prefix != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Use a prefix from your Permalink structure in your Pretty Links', 'pretty-link'); ?>
<br/><span class="description"><?php _e("This option should only be checked if you have elements in your permalink structure that must be present in any link on your site. For example, some WordPress installs don't have the benefit of full rewrite capabilities and in this case you'd need an index.php included in each link (http://example.com/index.php/mycoolslug instead of http://example.com/mycoolslug). If this is the case for you then check this option but the vast majority of users will want to keep this unchecked.", 'pretty-link'); ?></span>
  </li>
  <li>
    <span><strong><?php _e('Default Link Redirection Type:', 'pretty-link') ?> </strong></span>
    <select name="<?php echo $link_redirect_type; ?>">
        <option value="307" <?php echo (($prli_options->link_redirect_type == '307')?' selected="selected"':''); ?>><?php _e('Temporary (307)', 'pretty-link'); ?></option>
        <option value="301" <?php echo (($prli_options->link_redirect_type == '301')?' selected="selected"':''); ?>><?php _e('Permanent (301)', 'pretty-link'); ?></option>
        <?php do_action('prli_default_redirection_types',$prli_options->link_redirect_type); ?>
    </select>
    <br/><span class="description"><?php _e('Select the type of redirection you want your newly created links to have.', 'pretty-link'); ?></span>
  </li>
  <?php do_action('prli_custom_link_options'); ?>
  <li>
	<h3><?php _e('Advanced', 'pretty-link') ?></h3>
    <span><strong><?php _e('WordPress Redirection Action:', 'pretty-link') ?> </strong></span>
    <select name="<?php echo $link_redirect_action; ?>">
	  <option value="init" <?php echo (($prli_options->link_redirect_action == 'init')?' selected="selected"':''); ?>><?php _e('WordPress \'init\' Action', 'pretty-link') ?></option>
	  <option value="template_redirect" <?php echo (($prli_options->link_redirect_action == 'template_redirect')?' selected="selected"':''); ?>><?php _e('WordPress \'template_redirect\' Action', 'pretty-link') ?></option>
	</select>
    <br/><span class="description"><?php _e('Defaults to use WordPress\' \'init\' action. Init works more reliably for many users but the better option for performance and compatibility is to use the \'template_redirect\' action.', 'pretty-link') ?></span>
  </li>
</ul>
<?php do_action('prli_custom_option_pane'); ?>
<h3><a class="toggle reporting-toggle-button"><?php _e('Reporting Options', 'pretty-link'); ?> <span class="reporting-expand" style="display: none;">[+]</span><span class="reporting-collapse">[-]</span></a></h3>
<table class="reporting-toggle-pane form-table">
  <tr class="form-field">
    <td valign="top"><?php _e('Excluded IP Addresses:', 'pretty-link'); ?> </td>
    <td>
      <input type="text" name="<?php echo $prli_exclude_ips; ?>" value="<?php echo $prli_options->prli_exclude_ips; ?>"> 
      <br/><span class="description"><?php _e('Enter IP Addresses or IP Ranges you want to exclude from your Hit data and Stats. Each IP Address should be separated by commas. Example: <code>192.168.0.1, 192.168.2.1, 192.168.3.4 or 192.168.*.*</code>', 'pretty-link'); ?></span>
      <br/><span class="description" style="color: red;"><?php _e('Your Current IP Address is', 'pretty-link'); echo $_SERVER['REMOTE_ADDR']; ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="checkbox" class="filter-robots-checkbox" name="<?php echo $filter_robots; ?>" <?php echo (($prli_options->filter_robots != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Filter Robots', 'pretty-link'); ?>
      <br/><span class="description"><?php _e('Filter known Robots and unidentifiable browser clients from your hit data, stats and reports. <code>IMPORTANT: Any robot hits recorded with any version of Pretty Link before 1.4.22 won\'t be filtered by this setting.</code>', 'pretty-link'); ?></span>
      <table class="option-pane whitelist-ips">
        <tr class="form-field">
          <td valign="top"><?php _e('Whitelist IP Addresses:', 'pretty-link'); ?>&nbsp;</td>
          <td>
            <input type="text" name="<?php echo $whitelist_ips; ?>" value="<?php echo $prli_options->whitelist_ips; ?>"> 
            <br/><span class="description"><?php _e('Enter IP Addresses or IP Ranges you want to always include in your Hit data and Stats even if they are flagged as robots. Each IP Address should be separated by commas. Example: <code>192.168.0.1, 192.168.2.1, 192.168.3.4 or 192.168.*.*</code>', 'pretty-link'); ?></span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <h4><?php _e('Tracking Style:', 'pretty-link'); ?></h4><span class="description"><code><?php _e('Note: Changing your tracking style can affect the accuracy of your existing statistics.', 'pretty-link'); ?></code></span>
      <div id="option-pane">
        <ul style="list-style-type: none;" class="pane">
          <li>
            <input type="radio" name="<?php echo $extended_tracking; ?>" value="normal"<?php echo (($prli_options->extended_tracking == 'normal')?' checked="checked"':''); ?>/>&nbsp;<?php _e('Normal Tracking', 'pretty-link'); ?>
          </li>
          <li>
            <input type="radio" name="<?php echo $extended_tracking; ?>" value="extended"<?php echo (($prli_options->extended_tracking == 'extended')?' checked="checked"':''); ?>/>&nbsp;<?php _e('Extended Tracking (more stats / slower performance)', 'pretty-link'); ?>
          </li>
          <li>
            <input type="radio" name="<?php echo $extended_tracking; ?>" value="count"<?php echo (($prli_options->extended_tracking == 'count')?' checked="checked"':''); ?>/>&nbsp;<?php _e('Simple Click Count Tracking (less stats / faster performance)', 'pretty-link'); ?>
          </li>
        </ul>
      </div>
    </td>
  </tr>
</table>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'pretty-link') ?>" />
</p>


<h3><?php _e('Trim Hit Database', 'pretty-link'); ?></h3>

<?php if($prli_options->extended_tracking != 'count') { ?>
<p><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>&action=clear_30day_clicks" onclick="return confirm('<?php _e('***WARNING*** If you click OK you will delete ALL of the Hit data that is older than 30 days. Your data will be gone forever -- no way to retreive it. Do not click OK unless you are absolutely sure you want to delete this data because there is no going back!', 'pretty-link'); ?>');"><?php _e('Delete Hits older than 30 days', 'pretty-link'); ?></a>
<br/><span class="description"><?php _e('This will clear all hits in your database that are older than 30 days.', 'pretty-link'); ?></span></p>

<p><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>&action=clear_90day_clicks" onclick="return confirm('<?php _e('***WARNING*** If you click OK you will delete ALL of the Hit data that is older than 90 days. Your data will be gone forever -- no way to retreive it. Do not click OK unless you are absolutely sure you want to delete this data because there is no going back!', 'pretty-link'); ?>');"><?php _e('Delete Hits older than 90 days', 'pretty-link'); ?></a>
<br/><span class="description"><?php _e('This will clear all hits in your database that are older than 90 days.', 'pretty-link'); ?></span></p>
<?php } ?>

<p><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>&action=clear_all_clicks" onclick="return confirm('<?php _e('***WARNING*** If you click OK you will delete ALL of the Hit data in your Database. Your data will be gone forever -- no way to retreive it. Do not click OK unless you are absolutely sure you want to delete all your data because there is no going back!', 'pretty-link'); ?>');"><?php _e('Delete All Hits', 'pretty-link'); ?></a>
<br/><span class="description"><?php _e('Seriously, only click this link if you want to delete all the Hit data in your database.', 'pretty-link'); ?></span></p>

</form>
</div>
