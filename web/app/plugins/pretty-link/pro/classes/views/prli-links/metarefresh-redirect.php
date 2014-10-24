<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?php echo esc_html($prli_blogname) ?></title>
  <?php
    if(!empty($google_tracking) and $ga_info = PrliProUtils::ga_installed())
      echo PrliProUtils::ga_tracking_code($ga_info['slug']);
    
    do_action('prli-redirect-header');
  ?>
  <meta http-equiv="refresh" content="<?php echo esc_html($delay); ?>; URL=<?php echo esc_html($pretty_link_url.$param_string) ?>">
</head>
<body>

</body>
</html>
