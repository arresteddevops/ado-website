<li>
  <label><input type="checkbox" name="<?php echo $prlipro_options->google_tracking_str; ?>" id="<?php echo $prlipro_options->google_tracking_str; ?>" <?php echo $prlipro_options->google_tracking?'checked=checked':''; ?>/>&nbsp; <?php _e('Enable Google Analytics', 'pretty-link') ?></label>
  <p class="description"><?php _e('Requires the Google Analyticator, Google Analytics for WordPress or Google Analytics Plugin installed and configured for this to work.', 'pretty-link') ?></p>
</li>
<li>
  <label><input type="checkbox" name="<?php echo $prlipro_options->generate_qr_codes_str; ?>" id="<?php echo $prlipro_options->generate_qr_codes_str; ?>" <?php echo $prlipro_options->generate_qr_codes?'checked=checked':''; ?>/>&nbsp; <?php printf(__('Generate Downloadable %sQR Codes%s for Pretty Links', 'pretty-link'), '<a href="http://en.wikipedia.org/wiki/QR_code">', '</a>'); ?></label>
  <p class="description"><?php printf(__('This will enable a link in your pretty link admin that will allow you to automatically download a %sQR Code%s for each individual Pretty Link.', 'pretty-link'), '<a href="http://en.wikipedia.org/wiki/QR_code">', '</a>'); ?></p>	
</li>
<?php /*
<li>
  <label><input type="checkbox" name="<?php echo $prlipro_options->qr_code_links_str; ?>" id="<?php echo $prlipro_options->qr_code_links_str; ?>" <?php echo $prlipro_options->qr_code_links?'checked=checked':''; ?>/>&nbsp; <?php printf(__('Enable Public %sQR Codes%s Links for Pretty Links'), '<a href="http://en.wikipedia.org/wiki/QR_code">', '</a>'); ?></label>
  <p class="description"><?php printf(__('If this is enabled then if you append \'/qr.png\' to any pretty link then a %sQR Code%s for that link will be displayed. This link can be used the same as any standard, static image.'), '<a href="http://en.wikipedia.org/wiki/QR_code">', '</a>'); ?></p>
</li>
*/ ?>
