<?php
$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
if (file_exists($root.'/wp-load.php')) 
  require_once($root.'/wp-load.php');
else
  require_once($root.'/wp-config.php');

require_once 'prlipro-config.php';

if(is_user_logged_in() and $current_user->user_level >= 8)
{
  if(isset($_POST['action']) and $_POST['action'] == 'tweet-post')
  {
    header("Content-Type: text/plain; charset=utf-8");

    $post_id = $_POST['post'];
    
    if($post_id)
      $twitter_status = $prlipro_utils->post_post_to_twitter($post_id,$_POST['message']);

    $pretty_link_id = PrliUtils::get_prli_post_meta($post_id,'_pretty-link',true);
    if($pretty_link_id)
      $prli_link_meta->update_link_meta($pretty_link_id,'twitter_message',$_POST['message']);

    echo $twitter_status;
  }
}
else
  wp_redirect("/");
