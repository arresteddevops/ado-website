<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<div class="wrap">
<?php echo PrliAppHelper::page_title(__('Pro Account Information', 'pretty-link')); ?>
<?php $this_uri = preg_replace('#&.*?$#', '', str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>
<h3><?php _e('Pretty Link Pro Account Information', 'pretty-link'); ?></h3>
<?php if($prli_update->pro_is_installed_and_authorized()) { ?>
  <p><a href="http://prettylinkpro.com/user-manual"><?php _e('User Manual', 'pretty-link'); ?></a></p>
<?php } ?>
<?php echo $prli_update->pro_cred_form(); ?>
<?php if($prli_update->pro_is_installed_and_authorized()) { ?>
  <div><p><strong><?php _e('Pretty Link Pro is Installed', 'pretty-link'); ?></strong></p><p><a href="<?php echo $this_uri; ?>&action=pro-uninstall" onclick="return confirm('<?php _e('Are you sure you want to Un-Install Pretty Link Pro? This will delete your pro username & password from your local database, remove all the pro software but will leave all your data intact incase you want to reinstall sometime :) ...', 'pretty-link'); ?>');" title="<?php _e('Downgrade to Pretty Link Standard', 'pretty-link'); ?>" ><?php _e('Downgrade to Pretty Link Standard', 'pretty-link'); ?></a></p><br/><p><strong><?php _e('Edit/Update Your Profile:', 'pretty-link'); ?></strong><br/><span class="description"><?php _e('Use your account username and password to log in to your Account and Affiliate Control Panel', 'pretty-link'); ?></span></p><p><a href="http://prettylinkpro.com/amember/member.php"><?php _e('Account', 'pretty-link'); ?></a>&nbsp;|&nbsp;<a href="http://prettylinkpro.com/amember/aff_member.php"><?php _e('Affiliate Control Panel', 'pretty-link'); ?></a></div>
  
<?php } else { ?>
  <p><strong><?php _e('Ready to take your marketing efforts to the next level?', 'pretty-link'); ?></strong><br/>
  <a href="http://prettylinkpro.com"><?php _e('Pretty Link Pro', 'pretty-link'); ?></a> <?php _e('will help you automate, share, test and get more clicks and conversions from your Pretty Links!', 'pretty-link'); ?><br/><br/><a href="http://prettylinkpro.com"><?php _e('Learn More', 'pretty-link'); ?> &raquo;</a></p>
<?php } ?>

</div>
