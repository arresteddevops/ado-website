<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title><?php _e('Here is your Pretty Link', 'pretty-link'); ?></title>
     <style type="text/css">
       body {
         font-family: Arial;
         text-align: center;
         margin-top: 25px;
       }
       
       h4 {
         font-size: 18px;
         color: #aaaaaa;
       }

       h2 {
         font-size: 24px;
         font-weight: bold;
       }

       h2 a {
         text-decoration: none;
         color: #1f487e;
       }

       h2 a:hover {
         text-decoration: none;
         color: blue;
       }
     </style>
   </head>
   <body>
     <p><img src="<?php echo PRLI_IMAGES_URL; ?>/prettylink_logo.jpg" /></p>
     <h4><em><?php _e('here is your pretty link for:', 'pretty-link'); ?></em><br/><?php echo $target_url_title; ?><br/>(<span title="<?php echo $target_url; ?>"><?php echo substr($target_url,0,50) . ((strlen($target_url)>50)?"...":''); ?></span>)</h4>
     <h2><a href="<?php echo $pretty_link; ?>"><?php echo $pretty_link; ?></a></h2>
     <p><?php _e('send this link to:', 'pretty-link'); ?><br/>
     <?php echo prlipro_get_social_buttons_bar($pretty_link_id); ?>
     <p><a href="<?php echo $prli_blogurl; ?>">&laquo; <?php _e('home', 'pretty-link'); ?></a></p>
   </body>
 </html>
 