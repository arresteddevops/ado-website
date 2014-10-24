<?php
  $root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
  if (file_exists($root.'/wp-load.php')) 
    require_once($root.'/wp-load.php');
  else
    require_once($root.'/wp-config.php');

  require_once 'prlipro-config.php';

  if($prlipro_options->allow_public_link_creation)
  {
    $_POST['slug'] = (isset($_POST['slug']) && !empty($_POST['slug']))?$_POST['slug']:$prli_link->generateValidSlug();
    
    $errors = array();
    $errors = $prli_link->validate($_POST);
   
    if( count($errors) > 0 )
    {
      $url_param = ((!empty($url))?"&url=".urlencode($_POST['url']):'');
      header("Location: {$_POST['referral-url']}?errors=" . urlencode(serialize($errors)).$url_param);
    }
    else
    {
      $redirect_type = $_POST['redirect_type'];
      $track = $_POST['track'];
      $group = $_POST['group'];
   
      $_POST['param_forwarding'] = 'off';
      $_POST['param_struct'] = '';
      $_POST['name'] = '';
      $_POST['description'] = '';
   
      if($redirect_type == '-1')
        $_POST['redirect_type'] = $prli_options->link_redirect_type;
   
      if($track == '-1')
      {
        if( $prli_options->link_track_me )
          $_POST['track_me'] = 'on';
      }
      else if( $track == '1' )
        $_POST['track_me'] = 'on';
   
      if($group != '-1')
        $_POST['group_id'] = $group;
   
      if( $prli_options->link_nofollow )
        $_POST['nofollow'] = 'on';
   
      $record = $prli_link->create( $_POST );
      $link = $prli_link->getOne($record);
   
      if($prlipro_options->use_public_link_display_page)
        header("Location: {$prlipro_options->public_link_display_page}?slug=".urlencode($link->slug));
      else
      {
        $pretty_link      = prli_get_pretty_link_url($link->id);
        $target_url       = $link->url;
        $target_url_title = $link->name;
        $pretty_link_id   = $link->id;

        require_once(PRLIPRO_VIEWS_PATH . '/prlipro-public/display.php');
      }
    }
  }
  else
    wp_redirect($prli_blogurl);