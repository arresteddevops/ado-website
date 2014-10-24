<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="description" content="<?php echo stripslashes($pretty_link->description); ?>" />
    <title><?php echo htmlspecialchars(stripslashes($pretty_link->name)); ?></title>
    <?php
      if(!empty($google_tracking) and $ga_info = PrliProUtils::ga_installed())
        echo PrliProUtils::ga_tracking_code($ga_info['slug']);
      
      do_action('prli-redirect-header');
    ?>
  </head>
  <frameset rows="66,*" framespacing=0 frameborder=0>
    <frame src="<?php echo site_url("/index.php?plugin=pretty-link-pro&controller=links&action=prettybar&s={$pretty_link->slug}"); ?>" noresize frameborder=0 scrolling=no marginwidth=0 marginheight=0 style="">
    <frame src="<?php echo $pretty_link_url.$param_string; ?>" frameborder=0 marginwidth=0 marginheight=0>
    <noframes>Your browser does not support frames. Click <a href="<?php echo $pretty_link_url.$param_string; ?>">here</a> to view the page.</noframes>
  </frameset>
</html>
