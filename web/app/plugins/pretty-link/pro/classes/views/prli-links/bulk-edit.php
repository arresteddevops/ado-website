<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>
<div class="inline-edit-group advanced-link-options">
  <h4><?php _e('Advanced Link Options', 'pretty-link'); ?></h4>
  <?php PrliLinksHelper::bulk_action_checkbox_dropdown('bu[double_redirect]', __('Double Redirect', 'pretty-link'), 'bulk-edit-select'); ?>
  <div id="prli_google_analytics" style="display: none;">
  <?php
    global $prli_update;
    if($ga_info = PrliProUtils::ga_installed()):
      PrliLinksHelper::bulk_action_checkbox_dropdown('bu[google_tracking]', __('Google Analytics', 'pretty-link'), 'bulk-edit-select'); ?>
    <?php endif; ?>
  </div>
</div>
<?php /*
<div class="inline-edit-group keyword-replacements">
  <?php global $prlipro_options; ?>
  <?php if( $prlipro_options->keyword_replacement_is_on ): ?>
    <h4><?php _e('Keyword Replacements'); ?></h4>
    <?php global $prlipro_options; ?>
    <input type="text" name="bu[keywords]" class="bulk-edit-text" />
    <br/>
    <h4><?php _e('URL Replacements'); ?></h4>
    <input type="text" name="bu[url_replacements]" class="bulk-edit-text" />
    <br/>
  <?php endif; ?>
  <div id="prli_time_delay" style="display: none;">
    <h4><?php _e('Redirect Delay'); ?></h4>
    <input type="text" name="delay" value="0" />
  </div>
</div>
  */ ?>
