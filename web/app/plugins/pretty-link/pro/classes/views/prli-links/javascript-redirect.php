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
  <script type="text/javascript">
    setTimeout( function() {
                  window.location='<?php echo $pretty_link_url . $param_string; ?>';
                }, <?php echo esc_html($delay * 1000); ?> );
  </script>
</head>
<body>

</body>
</html>
