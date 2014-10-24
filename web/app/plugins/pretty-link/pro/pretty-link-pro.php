<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

require_once('prlipro-config.php');
require_once('prlipro-hooks.php');

/* Add header to prli-options page */
function prlipro_options_admin_header()
{
  require_once PRLIPRO_PATH . '/classes/views/prlipro-options/head.php';
}

/* Add header to prli-reports page */
function prlipro_reports_admin_header()
{
  if(isset($_GET['action']) and $_GET['action'] == 'display-custom-report')
    prlipro_custom_report_admin_header();
  else if(isset($_GET['action']) and $_GET['action'] == 'display-split-test-report')
    prlipro_split_test_report_admin_header();
  else
    require_once PRLIPRO_PATH . '/classes/views/prli-reports/head.php';
}

/* Add header to prli-custom-report page */
function prlipro_custom_report_admin_header()
{
  global $prli_siteurl, $prli_report, $prli_utils;

  $params = $prli_report->get_params_array();
  $first_click = $prli_utils->getFirstClickDate();

  // Adjust for the first click
  if(isset($first_click))
  {
    $min_date = (int)((time()-$first_click)/60/60/24);

    if($min_date < 30)
      $start_timestamp = $prli_utils->get_start_date($params,$min_date);
    else
      $start_timestamp = $prli_utils->get_start_date($params,30);

    $end_timestamp = $prli_utils->get_end_date($params);
  }
  else
  {
    $min_date = 0;
    $start_timestamp = time();
    $end_timestamp = time();
  }

  $id = $params['id'];
  $report = $prli_report->getOne($id);

  require_once PRLIPRO_PATH . '/classes/views/prli-reports/custom-head.php';
}

/* Add header to prli-split-test-report page */
function prlipro_split_test_report_admin_header()
{
  global $prli_siteurl, $prli_link, $prli_report, $prli_utils;

  $params = $prli_report->get_params_array();
  $first_click = $prli_utils->getFirstClickDate();

  // Adjust for the first click
  if(isset($first_click))
  {
    $min_date = (int)((time()-$first_click)/60/60/24);

    if($min_date < 30)
      $start_timestamp = $prli_utils->get_start_date($params,$min_date);
    else
      $start_timestamp = $prli_utils->get_start_date($params,30);

    $end_timestamp = $prli_utils->get_end_date($params);
  }
  else
  {
    $min_date = 0;
    $start_timestamp = time();
    $end_timestamp = time();
  }

  $id = $params['id'];
  $link = $prli_link->getOne($id);

  require_once PRLIPRO_PATH . '/classes/views/prli-reports/split-test-head.php';
}

function prlipro_post_options($post)
{
  global $prlipro_options;

  $prlipro_post_options = PrliProPostOptions::get_options($post->ID);
  
  ?>
  <!-- The NONCE below prevents post meta from being blanked on move to trash -->
  <input type="hidden" name="plp_nonce" value="<?php echo wp_create_nonce('plp_nonce'.wp_salt()); ?>" />
  <?php
  
  if( (($post->post_type == 'page') and $prlipro_options->twitter_pages_button) OR
      (($post->post_type == 'post') and $prlipro_options->twitter_posts_button) )
  {
    $checked = (($prlipro_post_options->hide_twitter_button)?' checked="checked"':'');
    ?>
      <span><input type="checkbox" name="hide_twitter_button" id="hide_twitter_button"<?php echo $checked ?> />&nbsp;<?php _e('Hide the Twitter Badge on this post.', 'pretty-link'); ?></span><br/>
    <?php
  }
  
  if( (($post->post_type == 'page') and $prlipro_options->social_pages_buttons) OR
      (($post->post_type == 'post') and $prlipro_options->social_posts_buttons) )
  {
    $checked = (($prlipro_post_options->hide_social_buttons)?' checked="checked"':'');
    ?>
      <span><input type="checkbox" name="hide_social_buttons" id="hide_social_buttons"<?php echo $checked ?> />&nbsp;<?php _e('Hide Social Buttons on this post.', 'pretty-link'); ?></span><br/>
    <?php
  }
  
  if( (($post->post_type == 'page') and $prlipro_options->twitter_pages_comments) OR
      (($post->post_type == 'post') and $prlipro_options->twitter_posts_comments) )
  {
    $checked = (($prlipro_post_options->hide_twitter_comments)?' checked="checked"':'');
    ?>
      <span><input type="checkbox" name="hide_twitter_comments" id="hide_twitter_comments"<?php echo $checked ?> />&nbsp;<?php _e('Hide Twitter Comments on this post.', 'pretty-link'); ?></span><br/>
    <?php
  }

  if((($post->post_type == 'page') or ($post->post_type == 'post')) and $prlipro_options->keyword_replacement_is_on )
  {
    $checked = (($prlipro_post_options->disable_replacements)?' checked="checked"':'');
    ?>
      <span><input type="checkbox" name="disable_replacements" id="disable_replacements"<?php echo $checked ?> />&nbsp;<?php _e('Disable Keyword Replacements on this post.', 'pretty-link'); ?></span><br/>
    <?php
  }
  
}

function prlipro_post_sidebar($post)
{
  global $prli_blogurl, $prlipro_options, $prli_link, $prli_link_meta;

  $prlipro_post_options = PrliProPostOptions::get_options($post->ID);

  do_action('prlipro_sidebar_top');

  // Make sure the prli process routines are called on submit
  ?><input type="hidden" name="prli_process_tweet_form" id="prli_process_tweet_form" value="Y" /><?php

  if ($post->post_status != "publish")
  {
  ?>
    <p><?php _e('A Pretty Link will be created on Publish', 'pretty-link'); ?></p>
    <p><strong><?php echo $prli_blogurl . PrliUtils::get_permalink_pre_slug_uri(); ?></strong><input type="text" style="width: 100px;" name="prli_req_slug" id="prli_req_slug" value="<?php echo ((!empty($prlipro_post_options->requested_slug))?$prlipro_post_options->requested_slug:$prli_link->generateValidSlug()); ?>" />
    </p>
  <?php
  }
  else
  {
    $pretty_link_id = PrliUtils::get_prli_post_meta($post->ID,"_pretty-link",true);
    $pretty_link = $prli_link->getOne($pretty_link_id, OBJECT, true);

    if(!empty($pretty_link) and $pretty_link)
    {
      $pretty_link_url = "$prli_blogurl".PrliUtils::get_permalink_pre_slug_uri()."{$pretty_link->slug}";
      $twitter_message = $prli_link_meta->get_link_meta($pretty_link_id,'twitter_message',true);
      $twitter_message = ((empty($twitter_message))?$prlipro_post_options->requested_twitter_message:$twitter_message);
      ?>
      <p><span style="font-size: 24px;"><?php echo (empty($pretty_link->clicks) or $pretty_link->clicks===false)?0:$pretty_link->clicks; ?></span> <?php _e('Hits', 'pretty-link'); ?>&nbsp;&nbsp;&nbsp;<span style="font-size: 24px;"><?php echo (empty($pretty_link->uniques) or $pretty_link->uniques===false)?0:$pretty_link->uniques; ?></span> <?php _e('Uniques', 'pretty-link'); ?></p>    
      <p><?php _e('Pretty Link:', 'pretty-link'); ?><br/>
      <strong><?php echo $pretty_link_url; ?></strong><br/><a href="<?php echo admin_url("admin.php?page=pretty-link&action=edit&id={$pretty_link->id}"); ?>"><?php _e('edit', 'pretty-link'); ?></a>&nbsp;|&nbsp;<a href="<?php echo $pretty_link_url; ?>" target="_blank" title="<?php _e('Visit Pretty Link:', 'pretty-link'); echo $pretty_link_url; _e('in a New Window', 'pretty-link'); ?>"><?php _e('visit', 'pretty-link'); ?></a></p>
      <?php

      if(($post->post_type == 'page') and (($prlipro_options->pages_auto == 0) or ($prlipro_options->twitter_auto_post_page == 0)))
        return;

      if(($post->post_type == 'post') and (($prlipro_options->posts_auto == 0) or ($prlipro_options->twitter_auto_post_post == 0)))
        return;

      ?>
        <div style="margin: 0px; margin-left: -6px; margin-right: -6px; padding: 0px; border-top: 1px solid #eeeeee; line-height: 1px;">&nbsp;</div>
      <?php

      if($prli_link_meta->get_link_meta($pretty_link->id,'pretty-link-posted-to-twitter'))
        echo "<p><img src=\"".PRLI_IMAGES_URL."/twitter.png\" style=\"float:left;\" width=\"18px\" height=\"18px\" />&nbsp;<span class=\"tweet-status\">".__('Has already been tweeted', 'pretty-link')."</span>";
      else
        echo "<p><img src=\"".PRLI_IMAGES_URL."/twitter.png\" style=\"float:left;\" width=\"18px\" height=\"18px\" />&nbsp;<span class=\"tweet-status\">".__("Hasn't been Tweeted yet", 'pretty-link')."</span>";
      
      if(!empty($prlipro_options->twitter_handle) and !empty($prlipro_options->twitter_password))
      {
        ?>
        &ndash <a href="#" class="tweet-toggle-button"><?php _e('Tweet It', 'pretty-link'); ?></a>
        <div class="tweet-toggle-pane">
        <br/><strong><?php _e('Twitter Message Format:', 'pretty-link'); ?> <small><a href="http://prettylinkpro.com/modifying-the-tweet-message-format/"><?php _e('(help)', 'pretty-link'); ?></a></small></strong>
          <textarea name="prli-twitter-message-link" id='tweet-message' class='tweet-message' style="width: 100%;"><?php echo $twitter_message; ?></textarea>
          <a href="#" class="tweet-button button" style="line-height: 30px;"><?php _e('Post to Twitter', 'pretty-link'); ?></a>
          &nbsp;<div class="tweet-response"></div>
          <br/><div class="tweet-message-display"></div>
        </div>
        </p>
        <?php
      }
      else
        echo "</p>";

      return;
    }
    else
    {
      ?>
        <p><?php _e('A Pretty Link hasn\'t been generated for this entry yet. Click "Update Post" to generate.', 'pretty-link'); ?></p>
        <p><strong><?php echo $prli_blogurl . PrliUtils::get_permalink_pre_slug_uri(); ?></strong><input type="text" style="width: 100px;" name="prli_req_slug" id="prli_req_slug" value="<?php echo ((!empty($prlipro_post_options->requested_slug))?$prlipro_post_options->requested_slug:$prli_link->generateValidSlug()); ?>" />
        </p>
      <?php
    }
  }
?>
<?php

  if(($post->post_type == 'page') and (!$prlipro_options->pages_auto or !$prlipro_options->twitter_auto_post_page))
    return;

  if(($post->post_type == 'post') and (!$prlipro_options->posts_auto or !$prlipro_options->twitter_auto_post_post))
    return;

?>
  <p>
    <input class="pretty-link-auto-twitter-post" type="checkbox" name="pretty-link-auto-twitter-post"<?php echo checked((($prlipro_options->posts_auto && $prlipro_options->twitter_auto_post_post) || ($prlipro_options->pages_auto && $prlipro_options->twitter_auto_post_page))); ?>/>&nbsp;<?php _e('Auto Tweet on Publish', 'pretty-link'); ?> &ndash; <a href="#" class="tweet-toggle-button"><?php _e('Customize Tweet', 'pretty-link'); ?></a>
  <div class="tweet-toggle-pane">
  <br/><textarea name="prli-twitter-message-post" style="width: 100%;"><?php echo $prlipro_post_options->requested_twitter_message; ?></textarea></div></p>
<?php
}

function prlipro_route_standalone_request()
{
  $plugin     = (isset($_REQUEST['plugin'])?$_REQUEST['plugin']:'');
  $controller = (isset($_REQUEST['controller'])?$_REQUEST['controller']:'');
  $action     = (isset($_REQUEST['action'])?$_REQUEST['action']:'');

  if( $plugin and
      $plugin=='pretty-link-pro' and
      $controller and
      $action )
  {
    if($controller and $controller=='tweet')
    {
      if($action and $action=='show_tweetbadge')
        echo prlipro_render_tweetbadge();
    }
    else if($controller and $controller=='links')
    {
      if($action and $action=='prettybar')
        echo prlipro_render_prettybar(esc_html($_REQUEST['s']));
    }
    exit;
  }
  else if( $action == 'prli_endpoint_url' )
  {
    global $prli_options;

    $key = $_REQUEST['k'];
    $url = $_REQUEST['url'];

    if($key == $prli_options->bookmarklet_auth)
    {
      $pretty_link_id = prli_create_pretty_link( $url );
      if( $pretty_link = prli_get_pretty_link_url( $pretty_link_id ) )
        echo $pretty_link; 
      else
        _e('ERROR: Your Pretty Link was unable to be created', 'pretty-link');
    }
    else
      _e('Unauthorized', 'pretty-link');
  
    exit;
  }
}

function prlipro_render_tweetbadge( $pretty_link_id='', $badge_style="" )
{
  global $prlipro_utils, $prlipro_options, $prli_blogurl, $prli_link, $prli_link_meta;

  if(empty($pretty_link_id))
  {
    if( !isset($_GET['pid']) or
        empty($_GET['pid']) or
        !$_GET['pid'] )
      return '';

    $pretty_link_id = $_GET['pid']; 
  }

  $pretty_link = $prli_link->getOne($pretty_link_id);
  $shorturl = "{$prli_blogurl}".PrliUtils::get_permalink_pre_slug_uri()."{$pretty_link->slug}";
  $big_url = $pretty_link->url;

  $message_format = $prli_link_meta->get_link_meta($pretty_link_id,'twitter_message',true);

  // If there's no short url then don't show the badge
  if(empty($shorturl))
    return '';

  $prlipro_utils->update_tweet_count_cron($pretty_link_id);

  $tweet_message = $prlipro_utils->get_twitter_status_message($shorturl,$pretty_link->name,$message_format);
  $tweet_message = trim(strip_tags($tweet_message));

  //$tweet_count = $prlipro_utils->get_tweet_count($pretty_link_id);
  //data-related="blairwilli:Blair Williams" below REMOVED BY PAUL CARTER issue #33

  $tweet_button = <<<TWEET
  <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
  <div style="{$badge_style}">
    <a href="http://twitter.com/share" class="twitter-share-button"
             data-url="{$big_url}"
             data-via="{$prlipro_options->twitter_handle}"
             data-text="{$tweet_message}"
             data-count="vertical">Tweet</a>
  </div>
TWEET;

  return $tweet_button;
}

function prlipro_tweet_badge($position_badge = false)
{
  global $post, $prlipro_options, $prli_blogurl, $prli_link, $wp_query, $prli_link_meta;
  
  $prlipro_post_options = PrliProPostOptions::get_options($post->ID);

  // Don't show until published
  if(get_post_status($post->ID) != 'publish')
    return '';
  
  // only show button if enabled and links are being generated
  if(is_page() and (!$prlipro_options->pages_auto or !$prlipro_options->twitter_pages_button)) 
    return '';

  if((is_single() or is_archive() or $wp_query->is_posts_page ) and (!$prlipro_options->posts_auto or !$prlipro_options->twitter_posts_button)) 
    return '';
    
  if(is_home() and !is_page() and (!$prlipro_options->posts_auto or !$prlipro_options->twitter_posts_button))
    return '';
  
  if($prlipro_options->twitter_badge_hidden_on_homepage and is_home())
    return '';
  
  if($prlipro_post_options->hide_twitter_button)
    return '';

  if( is_feed() and !$prlipro_options->twitter_badge_show_in_feed )
    return '';

  $pretty_link_id = PrliUtils::get_prli_post_meta($post->ID,"_pretty-link",true);

  $badge_style = 'display: inline-block;';

  if($position_badge)
  {
    $badge_style = 'display: block; clear: both;';

    if($prlipro_options->twitter_badge_placement == 'top-left-with-wrap')
      $badge_style = 'float: left; margin: 0 12px 6px 0;';
    else if($prlipro_options->twitter_badge_placement == 'top-right-with-wrap')
      $badge_style = 'float: right; margin: 0 0 6px 12px;';
  }
  else
    $badge_style="";

  $tweet_button = prlipro_render_tweetbadge( $pretty_link_id, $badge_style );

  return $tweet_button;
}

// Template Tag for Tweet Badge
function the_tweetbadge()
{
  echo prlipro_tweet_badge();
}

// Puts a tweet this button on each post
function prlipro_add_tweet_button_to_content($content)
{
  global $prlipro_options;

  $tweet_button = prlipro_tweet_badge(true);

  if(!empty($tweet_button))
  {
    if($prlipro_options->twitter_badge_placement == 'none')
      return $content;
    else if($prlipro_options->twitter_badge_placement == 'bottom')
      return "{$content}{$tweet_button}";
    else
      return "{$tweet_button}{$content}";
  }
  else
    return $content;
}

function prlipro_tweet_comments()
{
  global $post, $prlipro_utils, $prli_tweet, $prlipro_options, $wp_query, $prli_link_meta;
  
  $prlipro_post_options = PrliProPostOptions::get_options($post->ID);

  // Don't show until published
  if(get_post_status($post->ID) != 'publish')
    return '';
  
  // only show button if enabled and links are being generated
  if(is_page() and (!$prlipro_options->pages_auto or !$prlipro_options->twitter_pages_comments)) 
    return '';

  if((is_single() or is_archive() or $wp_query->is_posts_page ) and (!$prlipro_options->posts_auto or !$prlipro_options->twitter_posts_comments)) 
    return '';
    
  if(is_feed())
    return '';
  
  if(is_home())
    return '';

  if($prlipro_post_options->hide_twitter_comments)
    return '';
  
  $pretty_link_id = PrliUtils::get_prli_post_meta($post->ID,"_pretty-link",true);
  $shorturl = prli_get_pretty_link_url($pretty_link_id);
  $tweets = $prli_tweet->get_tweets($pretty_link_id);

  // If there's no short url then don't show the badge
  if(empty($shorturl) or !($tweets))
    return '';

  if(!empty($prlipro_options->twitter_comments_height) and is_numeric($prlipro_options->twitter_comments_height))
    $conv_style = "height: {$prlipro_options->twitter_comments_height}px; min-height: {$prlipro_options->twitter_comments_height}px; overflow: auto;";

  $tweet_conv = $prlipro_options->twitter_comments_header;
  $tweet_conv .= "<div class=\"prli-twitter-conversations\" style=\"{$conv_style}\">";
  $first_tweet = ' prli-first-tweet';
  foreach($tweets as $tweet)
  {
    $retweet_msg = urlencode("RT @{$tweet->tw_from_user}:{$tweet->tw_text}");
    $tweet_conv .=<<<TWEET
      <div id="prli-tweet-{$tweet->twid}" class="prli-tweet{$first_tweet}">
        <table>
          <tr>
            <td rowspan="2">
              <a href="http://twitter.com/{$tweet->tw_from_user}" rel="nofollow" target="_blank"><img src="{$tweet->tw_profile_image_url}" class="prli-tweet-image" width="48px" height="48px"></a>
            </td>
            <td>
              <span class="prli-tweet-message"><a href="http://twitter.com/{$tweet->tw_from_user}" rel="nofollow" target="_blank">{$tweet->tw_from_user}</a>&nbsp;{$tweet->tw_text}</span>
            </td>
          </tr>
          <tr>
            <td>
              <a href="http://twitter.com/home?status={$retweet_msg}" rel="nofollow" target="_blank">Re-Tweet</a>&nbsp;|&nbsp;<a href="http://twitter.com/?status=@{$tweet->tw_from_user}%20&in_reply_to_status_id={$tweet->twid}&in_reply_to={$tweet->tw_from_user}" rel="nofollow" target="_blank">Reply</a>&nbsp;|&nbsp;<a href="http://twitter.com/{$tweet->tw_from_user}/statuses/{$tweet->twid}" rel="nofollow" target="_blank">View Tweet</a>
            </td>
          </tr>
        </table>
      </div>
TWEET;
    $first_tweet = '';
  }

  $tweet_conv .= '</div>';

  return $tweet_conv;
}

// Template Tag for Tweet Badge
function the_tweet_comments()
{
  echo prlipro_tweet_comments();
}

// Puts a tweet this button on each post
function prlipro_add_tweet_comments_to_content($content)
{
  global $prlipro_options;

  $tweet_comments = prlipro_tweet_comments();

  if(!empty($tweet_comments))
    return "{$content}{$tweet_comments}";
  else
    return $content;
}

function prlipro_social_buttons_bar()
{
  global $post, $prlipro_options, $wp_query;
    
  $prlipro_post_options = PrliProPostOptions::get_options($post->ID);

  // Don't show until published
  if(get_post_status($post->ID) != 'publish')
    return '';

  // only show button if enabled and links are being generated
  if(is_page() and (!$prlipro_options->pages_auto or !$prlipro_options->social_pages_buttons)) 
    return '';

  if((is_single() or is_archive() or $wp_query->is_posts_page ) and (!$prlipro_options->posts_auto or !$prlipro_options->social_posts_buttons)) 
    return '';
    
  if(is_home() and !is_page() and (!$prlipro_options->posts_auto or !$prlipro_options->social_posts_buttons))
    return '';

  if($prlipro_post_options->hide_social_buttons)
    return '';

  if( is_feed() and !$prlipro_options->social_buttons_show_in_feed )
    return '';
  
  $pretty_link_id = PrliUtils::get_prli_post_meta($post->ID,"_pretty-link",true);

  return prlipro_get_social_buttons_bar($pretty_link_id);
}

function prlipro_get_social_buttons_bar($pretty_link_id)
{
  global $prli_blogurl, $prlipro_utils, $prlipro_options, $prli_link, $prli_link_meta;

  $pretty_link = $prli_link->getOne($pretty_link_id);

  if(is_object($pretty_link))
    $shorturl = "{$prli_blogurl}".PrliUtils::get_permalink_pre_slug_uri()."{$pretty_link->slug}";
  else
    return '';

  $message_format = $prli_link_meta->get_link_meta($pretty_link_id,'twitter_message',true);

  // If there's no short url then don't show the badge
  if(empty($shorturl))
    return '';

  $twitter_user  = "@{$prlipro_options->twitter_handle}";
  $tweet_message = "RT {$twitter_user}: " . $prlipro_utils->get_twitter_status_message($shorturl,$pretty_link->name,$message_format);
  $tweet_message = urlencode(trim(strip_tags($tweet_message)));

  $encoded_url = urlencode($shorturl);
  $encoded_title = urlencode($pretty_link->name);
  $button_image_path = PRLI_IMAGES_URL;

  $buttons_array = array(
    'delicious'   => array( 'url'   => "http://del.icio.us/post?url={$encoded_url}&title={$encoded_title}",
                            'image' => "{$button_image_path}/delicious_32.png",
                            'title' => 'Delicious' ),
    'stumbleupon' => array( 'url'   => "http://www.stumbleupon.com/submit?url={$encoded_url}&title={$encoded_title}",
                            'image' => "{$button_image_path}/stumbleupon_32.png",
                            'title' => 'StumbleUpon' ),
    'digg'        => array( 'url'   => "http://digg.com/submit?phase=2&url={$encoded_url}&title={$encoded_title}",
                            'image' => "{$button_image_path}/digg_32.png",
                            'title' => "Digg" ),
    'twitter'     => array( 'url'   => "http://twitter.com/home?status={$tweet_message}",
                            'image' => "{$button_image_path}/twitter_32.png",
                            'title' => "Twitter" ),
    'facebook'    => array( 'url' => "http://www.facebook.com/sharer.php?u={$encoded_url}&t={$encoded_title}",
                            'image' => "{$button_image_path}/facebook_32.png",
                            'title' => "Facebook" ),
    'reddit'      => array( 'url' => "http://reddit.com/submit?url={$encoded_url}&title={$encoded_title}",
                            'image' => "{$button_image_path}/reddit_32.png",
                            'title' => "Reddit" ),
    'linkedin'    => array( 'url' => "http://www.linkedin.com/shareArticle?mini=true&url={$encoded_url}&title={$encoded_title}",
                            'image' => "{$button_image_path}/linkedin_32.png",
                            'title' => "LinkedIn" ),
    'hyves'       => array( 'url' => "http://www.hyves.nl/profilemanage/add/tips/?name={$encoded_title}&text={$encoded_title}+{$encoded_url}&rating=5",
                            'image' => "{$button_image_path}/hyves_32.png",
                            'title' => "Hyves" ),
    'email'       => array( 'url'   => "mailto:?subject={$pretty_link->name}&body={$pretty_link->name}%20{$encoded_url}",
                            'image' => "{$button_image_path}/email_32.png",
                            'title' => 'Email' ),
  );

  $social_buttons = '<div class="prli-social-buttons-bar">';
  foreach($prlipro_options->social_buttons as $button_name => $setting)
  {
    $button_url   = isset($buttons_array[$button_name]['url']) ? $buttons_array[$button_name]['url'] : '';
    $button_image = isset($buttons_array[$button_name]['image']) ? $buttons_array[$button_name]['image'] : '';
    $button_title = isset($buttons_array[$button_name]['title']) ? $buttons_array[$button_name]['title'] : '';

    if( !empty($button_url) ) {
      $social_buttons .= <<<SOCIALBUTTON
<a href="{$button_url}" rel="nofollow" target="_blank"><img src="{$button_image}" alt="{$button_title}" title="{$button_title}" border="0" style="padding: 0 {$prlipro_options->social_buttons_padding}px 0 0;" /></a>
SOCIALBUTTON;
    }
  }

  $social_buttons .= "</div>";

  return $social_buttons;
}

// Puts a tweet this button on each post
function prlipro_add_social_buttons_to_content($content)
{
  global $prlipro_options;

  if($prlipro_options->social_buttons_placement == 'none')
    return $content;

  $social_buttons = prlipro_social_buttons_bar();

  if(!empty($social_buttons))
  {
    if($prlipro_options->social_buttons_placement == 'bottom')
      return "{$content}{$social_buttons}";
    else if($prlipro_options->social_buttons_placement == 'top')
      return "{$social_buttons}{$content}";
    else if($prlipro_options->social_buttons_placement == 'top-and-bottom')
      return "{$social_buttons}{$content}{$social_buttons}";
  }
  else
    return $content;
}

function the_social_buttons_bar()
{
  echo prlipro_social_buttons_bar();
}

function prlipro_post_formatting()
{
  global $prlipro_options;
  
  if( is_single() and
      !$prlipro_options->twitter_posts_button and 
      !$prlipro_options->twitter_posts_comments and 
      !$prlipro_options->social_posts_buttons)
    return;
  
  if( is_page() and
      !$prlipro_options->twitter_pages_button and 
      !$prlipro_options->twitter_pages_comments and 
      !$prlipro_options->social_pages_buttons)
    return;

  wp_enqueue_style( 'prlipro-post', PRLIPRO_CSS_URL . '/prlipro-post.css' );
}

function prlipro_post_header()
{
  global $post;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery('.tweet-toggle-pane').hide();

  jQuery('.tweet-toggle-button').click(function() {
    jQuery('.tweet-toggle-pane').toggle();
  });

  jQuery('.tweet-button').click(function() {
    jQuery.ajax( {
       type: "POST",
       url: "<?php echo PRLIPRO_URL; ?>/prlipro-tweet.php",
       data: "action=tweet-post&post=<?php echo $post->ID; ?>&message="+document.getElementById('tweet-message').value,
       success: function(msg){
         jQuery('.tweet-response').replaceWith('Tweet Successful:');
         jQuery('.tweet-status').replaceWith('Has already been tweeted');
         jQuery('.tweet-message-display').replaceWith('<blockquote>'+msg+'</blockquote>');
       }
    });
  });
});
</script>
<?php
}

function prlipro_transition_post_status($new_status, $old_status, $post)
{
  if($old_status != $new_status && $new_status == 'publish')
    prlipro_publish_post($post->ID, 'auto');
}

function prlipro_xmlrpc_publish_post($post_id)
{
  prlipro_publish_post($post_id, 'auto');
}

function prlipro_publish_post($post_id, $type = 'manual')
{
  $post = get_post($post_id);
  
  prlipro_save_postdata($post_id, $post, $type);
  
  $permalink = get_permalink($post_id); //Not sure what this is for?
}

function prlipro_save_postdata($post_id, $post, $type = 'manual')
{
  if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    return $post_id;
  
  if(defined('DOING_AJAX'))
    return $post_id;
  
  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  global $prlipro_options;
  
  // Especially in lieu of new custom post types -- we need to make sure this is a 
  // page or a post -- no other types are supported or will be for a while -- if ever
  $okay_post_types = array('page', 'post');
  if(!in_array($post->post_type, $okay_post_types))
    return $post_id;
  
  if( 'page' == $post->post_type and !current_user_can( 'edit_page', $post_id ) and $type != 'auto' )
    return $post_id;
  
  if( 'post' == $post->post_type and !current_user_can( 'edit_post', $post_id ) and $type != 'auto' )
    return $post_id;
  
  $prlipro_post_options = PrliProPostOptions::get_options($post_id);
  
  //Handle twitter_auto_post
  if($post->post_type == 'page')
    $prlipro_post_options->send_auto_tweet = (isset($_REQUEST['pretty-link-auto-twitter-post']))?true:$prlipro_options->twitter_auto_post_page;
  elseif($post->post_type == 'post')
    $prlipro_post_options->send_auto_tweet = (isset($_REQUEST['pretty-link-auto-twitter-post']))?true:$prlipro_options->twitter_auto_post_post;;
  
  //Set requested slug if any
  $prlipro_post_options->requested_slug = isset($_REQUEST['prli_req_slug'])?$_REQUEST['prli_req_slug']:$prlipro_post_options->requested_slug;
  
  //Make sure a nonce is set so we don't wipe these options out when the post is being bulk edited
  if(wp_verify_nonce((isset($_POST['plp_nonce']))?$_POST['plp_nonce']:'', 'plp_nonce'.wp_salt())) {
    $prlipro_post_options->hide_twitter_button   = isset($_REQUEST['hide_twitter_button']);
    $prlipro_post_options->hide_social_buttons   = isset($_REQUEST['hide_social_buttons']);
    $prlipro_post_options->hide_twitter_comments = isset($_REQUEST['hide_twitter_comments']);
    $prlipro_post_options->disable_replacements  = isset($_REQUEST['disable_replacements']);
  }
  
  if(isset($_POST['prli-twitter-message-post']))
    $prlipro_post_options->requested_twitter_message = $_REQUEST['prli-twitter-message-post'];
  
  $prlipro_post_options->store($post_id);
  
  prlipro_auto_create_pretty_link($post_id, $post);
}

function prlipro_auto_create_pretty_link($post_id, $post)
{
  global $prli_link, $prli_utils, $prlipro_utils, $prlipro_options, $prli_link_meta;
  
  if('page' == $post->post_type and !$prlipro_options->pages_auto)
    return;
  
  if('post' == $post->post_type and !$prlipro_options->posts_auto)
    return;
  
  if($post and $post->post_status == "publish")
  {
    $default_group = $prlipro_options->posts_group;
    $default_group = (is_numeric($default_group)?$default_group:0);

    $prlipro_post_options = PrliProPostOptions::get_options($post_id);

    $pretty_link_id = PrliUtils::get_prli_post_meta($post_id,"_pretty-link",true);

    $new_link = false;
    // Try to find a pretty link that is using this link already
    if(!$pretty_link_id)
    {
      $new_link = true;
      $pretty_link_id = $prli_link->find_first_target_url(get_permalink($post_id));
    }

    $pretty_link = $prli_link->getOne($pretty_link_id);
    
    if(empty($pretty_link) or !$pretty_link)
    {
      $slug = (($prli_utils->slugIsAvailable($prlipro_post_options->requested_slug))?$prlipro_post_options->requested_slug:'');
      $pl_insert_id = prli_create_pretty_link( get_permalink($post_id), 
                                               $slug, // slug should be default?
                                               addslashes($post->post_title),
                                               addslashes($post->post_excerpt),
                                               $default_group // Default Group
                                             );
                                               
      $new_pretty_link = $prli_link->getOne($pl_insert_id);

      if(isset($post->ID) and !empty($post->ID) and $post->ID)
        PrliUtils::update_prli_post_meta($post->ID,'_pretty-link',$new_pretty_link->id,true);

      $prli_link_meta->update_link_meta($new_pretty_link->id,'twitter_message',$prlipro_post_options->requested_twitter_message);
    
      // Post to twitter if auto posts is available
      if($prlipro_post_options->send_auto_tweet)
        $prlipro_utils->post_post_to_twitter($post_id, $prlipro_post_options->requested_twitter_message);
    }
    else
    {
      prli_update_pretty_link( $pretty_link_id,
                               get_permalink($post_id), 
                               $pretty_link->slug,
                               addslashes($post->post_title),
                               addslashes($post->post_excerpt),
                               $default_group
                             );

      // Still update the post meta
      if(isset($post_id) and !empty($post_id) and $post_id)
        PrliUtils::update_prli_post_meta($post_id,'_pretty-link',$pretty_link_id,true);

      $prli_link_meta->update_link_meta($pretty_link_id,'twitter_message',$prlipro_post_options->requested_twitter_message);

      // Post to twitter if auto posts is available
      if($new_link and $prlipro_post_options->send_auto_tweet)
        $prlipro_utils->post_post_to_twitter($post_id, $prlipro_post_options->requested_twitter_message);
    }
  }
}

/************ DISPLAY & UPDATE PRO LINK OPTIONS ************/

function prlipro_display_link_options($link_id)
{
  global $prli_link, $prli_link_meta, $prli_keyword, $prli_link_rotation, $prlipro_options;

  if( $prlipro_options->keyword_replacement_is_on )
  {
    if(empty($_POST['keywords']) and $link_id)
      $keywords = $prli_keyword->getTextByLinkId( $link_id );
    else
      $keywords = isset($_POST['keywords'])?$_POST['keywords']:'';

    if(empty($_POST['url_replacements']) and $link_id)
    {
      $url_replacements = $prli_link_meta->get_link_meta( $link_id, 'prli-url-replacements' );

      if(is_array($url_replacements))
        $url_replacements = implode(', ', $url_replacements);
      else
        $url_replacements = '';
    }
    else
      $url_replacements = isset($_POST['url_replacements'])?$_POST['url_replacements']:'';
  }

  if(empty($_POST['url_rotations']) and $link_id)
  {
    $url_rotations = $prli_link_rotation->get_rotations( $link_id );
    $url_rotation_weights   = $prli_link_rotation->get_weights( $link_id );

    if(!is_array($url_rotations))
      $url_rotations = array('','','','');
    
    if(!is_array($url_rotation_weights))
      $url_rotation_weights = array('','','','');
  }
  else
  {
    $url_rotations = isset($_POST['url_rotations'])?$_POST['url_rotations']:'';
    $url_rotation_weights = isset($_POST['url_rotation_weights'])?$_POST['url_rotation_weights']:'';
  }

  if(empty($_POST['url']) and $link_id)
  {
    $link = $prli_link->getOne($link_id);
    $target_url = $link->url;
  }
  else
    $target_url = isset($_POST['url'])?$_POST['url']:'';

  if(!$link_id or !($target_url_weight = $prli_link_meta->get_link_meta($link_id, 'prli-target-url-weight', true)))
    $target_url_weight = 0;

  if(!empty($_POST) and !isset($_POST['enable_split_test']) or (empty($link_id) or !$link_id))
    $enable_split_test = isset($_POST['enable_split_test']);
  else
    $enable_split_test = $prli_link_meta->get_link_meta($link_id, 'prli-enable-split-test', true);

  if(isset($_POST['split_test_goal_link']) or (empty($link_id) or !$link_id))
    $split_test_goal_link = isset($_POST['split_test_goal_link'])?$_POST['split_test_goal_link']:'';
  else
    $split_test_goal_link = $prli_link_meta->get_link_meta($link_id, 'prli-split-test-goal-link', true);
  
  $double_redirect = "";
  if(isset($_POST['double_redirect']))
    $double_redirect = " checked=checked";
  else {
    if( $link_id and $double_redirect = $prli_link_meta->get_link_meta($link_id, 'double_redirect', true))
      $double_redirect = " checked=checked";
  }

  $links = $prli_link->getAll('',' ORDER BY gr.name,li.name');

  require_once PRLIPRO_PATH . '/classes/views/prli-links/form.php';
}

function prlipro_rotation_weight_select_helper($rotation_weight, $select_name="url_rotation_weights[]")
{
?>
  <select name="<?php echo $select_name; ?>">
  <?php for($p=0; $p<=100; $p+=5) { ?>
    <option value="<?php echo $p; ?>"<?php echo (((int)$p == (int)$rotation_weight)?' selected="true"':''); ?>><?php echo $p; ?>%&nbsp;</option>
  <?php } ?>
  </select>
<?php
}

function prlipro_validate_link_options($errors)
{
  global $prli_link_meta, $prlipro_options;

  if( $prlipro_options->keyword_replacement_is_on )
  {
    if( !empty($_POST[ 'url_replacements' ]) )
    {
      $replacements = explode(',', $_POST['url_replacements']);
      foreach($replacements as $replacement)
      {
        if(!preg_match( "#^http[s]?://.*?$#", trim($replacement) ) )
        {
          $errors[] = __('Your URL Replacements must be formatted as a comma separated list of properly formatted URLs (http[s]://example.com/whatever)', 'pretty-link');
          break;
        }
      }
    }

    if( !empty($_POST[ 'url_rotations' ]) )
    {
      $num_active_links = 0;
      $weight_sum = (int)$_POST['target_url_weight'];
      foreach($_POST['url_rotations'] as $i => $rotation)
      {
        if(!empty($rotation))
        {
          $num_active_links++;
          $weight_sum += (int)$_POST['url_rotation_weights'][$i];
        }
      }

      if($num_active_links > 0 and $weight_sum != 100)
        $errors[] = __('Your Link Rotation Weights must add up to 100%', 'pretty-link');
    }

    if(isset($_POST['delay']) and !empty($_POST['delay'])):
      if(!is_numeric($_POST['delay']))
        $errors[] = __('Delay Redirect must be a number', 'pretty-link');
    endif;
  }

  return $errors;
}

function prlipro_update_link_options($link_id)
{
  global $prli_link_meta, $prli_link_rotation, $prli_keyword, $prlipro_options;

  if( $prlipro_options->keyword_replacement_is_on )
    $prli_keyword->updateLinkKeywords($link_id, stripslashes($_POST['keywords']));

  if( $prlipro_options->keyword_replacement_is_on )
  {
    $replacements = explode(',',$_POST['url_replacements']);
    for($i=0; $i < count($replacements); $i++)
      $replacements[$i] = trim(stripslashes($replacements[$i]));
    if(empty($_POST['keywords']))
      $keywords = $prli_keyword->getTextByLinkId( $link_id );
    else
      $keywords = $_POST['keywords'];

    $prli_link_meta->update_link_meta($link_id, 'prli-url-replacements', $replacements);
  }

  $prli_link_meta->update_link_meta($link_id, 'prli-target-url-weight', $_POST['target_url_weight']);
  $prli_link_rotation->updateLinkRotations($link_id,$_POST['url_rotations'],$_POST['url_rotation_weights']);

  $prli_link_meta->update_link_meta($link_id, 'prli-enable-split-test', isset($_POST['enable_split_test']));
  $prli_link_meta->update_link_meta($link_id, 'prli-split-test-goal-link', isset($_POST['split_test_goal_link'])?$_POST['split_test_goal_link']:'');
  $prli_link_meta->update_link_meta($link_id, 'google_tracking', isset($_POST['google_tracking']));
  $prli_link_meta->update_link_meta($link_id, 'double_redirect', isset($_POST['double_redirect']));
  $prli_link_meta->update_link_meta($link_id, 'delay', empty($_POST['delay'])?0:$_POST['delay']);
}

function prlipro_rotate_target_url($target)
{
  global $prli_link_rotation;

  if($prli_link_rotation->there_are_rotations_for_this_link($target['link_id']))
    return array('url' => $prli_link_rotation->get_target_url($target['link_id']), 'link_id' => $target['link_id']);
  else
    return $target;
}

function prlipro_record_rotation_click($args)
{
  $link_id    = $args['link_id'];
  $click_id   = $args['click_id'];
  $target_url = $args['url'];

  global $prli_link_rotation;
  if($prli_link_rotation->there_are_rotations_for_this_link($link_id))
    $prli_link_rotation->record_click($click_id,$link_id,$target_url);
}

function prlipro_link_display_icon($link_id)
{
  global $prli_link_rotation;
  
  if($prli_link_rotation->there_are_rotations_for_this_link($link_id)) { ?>
  <img src="<?php echo PRLIPRO_IMAGES_URL; ?>/rotate_link.png" width="13px" height="13px" title="<?php _e('This Link Has additional Target URL rotations', 'pretty-link'); ?>" alt="<?php _e('This Link Has additional Target URL rotations', 'pretty-link'); ?>">&nbsp;
<?php }
}

/*************** REPLACE KEYWORDS & URLs AND CACHE POSTS & PAGES ***********************/
function prlipro_replace_keywords($content, $request_uri='')
{
  global $post, $prli_link, $prli_blogurl, $prli_keyword, $prli_url_replacement, $prlipro_options, $prlipro_utils;
  
  if( $prlipro_options->keyword_replacement_is_on )
  {
    $prlipro_post_options = PrliProPostOptions::get_options($post->ID);

    // Make sure keyword replacements haven't beeen disabled on this page / post
    if( !$prlipro_post_options->disable_replacements )
    {
      // If post password required and it doesn't match the cookie.
      // Just return the content unaltered -- we don't want to cache the password form.
      if( post_password_required($post) )
        return $content;

      // do a keyword replacement per post and per request_uri
      // so we can handle <!--more--> tags, feeds, etc.
      if($request_uri == '')
        $request_uri = $_SERVER['REQUEST_URI'];
      
      // URL Replacements go first
      if($urls_to_links = $prli_url_replacement->getURLToLinksArray())
      {
        foreach($urls_to_links as $url => $links)
        {
          $urlrep = $links[0]; // array_rand($links)];
      
          // if the url is blank then skip it
          if(preg_match("#^\s*$#",$url))
            continue;
      
          $content = preg_replace( '#'.preg_quote($url,'#').'#', $urlrep, $content );
        }
      }
      
      // Grab keywords to links list
      if( $keyword_to_links = $prli_keyword->getKeywordToLinksArray() )
      {
        // Pull out issue prone html code that keywords could appear in
        $keyword_ignores = array();
        $anchor_ignore = '#(\<a.*?\>.*?\</a\>)#';
        $gen_ignore = '#(\</?.*?/?\>)#';
        $shortcode_ignore = '#(\[.*?\])#';
      
        // Pull out full links first then any html tags
        $i = 0;
      
        // Pull shortcodes
        preg_match_all($shortcode_ignore,$content,$shortcode_matches);
      
        foreach($shortcode_matches[1] as $shortcode_match)
        {
          $placeholder = "||!prliignore".$i++."||";
          $keyword_ignores[] = array('html' => $shortcode_match, 'placeholder' => $placeholder);
          $content = preg_replace($shortcode_ignore,$placeholder,$content,1);
        }
        
        // Pull anchors
        preg_match_all($anchor_ignore,$content,$anchor_matches);
                
        foreach($anchor_matches[1] as $anchor_match)
        {
          $placeholder = "||!prliignore".$i++."||";
          $keyword_ignores[] = array('html' => $anchor_match, 'placeholder' => $placeholder);
          $content = preg_replace($anchor_ignore,$placeholder,$content,1);
        }
      
        // Pull other html tags
        preg_match_all($gen_ignore,$content,$gen_matches);
      
        foreach($gen_matches[1] as $gen_match)
        {
          $placeholder = "||!prliignore".$i++."||";
          $keyword_ignores[] = array('html' => $gen_match, 'placeholder' => $placeholder);
          $content = preg_replace($gen_ignore,$placeholder,$content,1);
        }
      
        // Now sort through keyword array and do the actual replacements
        $keywords = array_keys($keyword_to_links);
      
        // Sort by stringlength so larger words get replaced first and we get our counts right
        $keywords = $prlipro_utils->sort_by_stringlen($keywords,'DESC');
      
        // Set the keyword links per page to unlimited if we're not using thresholds
        $keyword_links_per_page = (($prlipro_options->set_keyword_thresholds)?$prlipro_options->keyword_links_per_page:-1);
        $keywords_per_page      = (($prlipro_options->set_keyword_thresholds)?$prlipro_options->keywords_per_page:-1);
      
        $i = 0;
        $keyword_count = 0;
        $keyword_matches = array();
      
        // First, see what keywords match in the post
        foreach($keywords as $keyword) 
        {
          // if the keyword is blank then skip it
          if(preg_match("#^\s*$#",$keyword))
            continue;
      
          $regex = '/\b('.preg_quote($keyword,'/').')\b/i';
          
          $keyword_instances = array();
          if(preg_match_all($regex,$content,$keyword_instances))
          {
            $key_rep_count = 0;
            $url_index = 0; // array_rand($keyword_to_links[$keyword]);
            $kw_obj = $keyword_to_links[$keyword][$url_index];
            $url = $kw_obj->url;
            $title = htmlentities($kw_obj->title, ENT_QUOTES);
      
            // Determine which keyword instances will be replaced
            $keyword_instance_count = count($keyword_instances[1]);
            $instance_indices = array();
            for($ind = 0; $ind < $keyword_instance_count; $ind++)
              $instance_indices[] = $ind;
      
            // Randomize the replacement indices if thresholds are set
            // This only works because in the instance_indices array
            // the keys are the same as the values (0=>0,1=>1,2=>2,etc.)
            if($keyword_links_per_page != -1 and ($keyword_instance_count > $keyword_links_per_page))
              $instance_indices = array_slice(array_keys($instance_indices), 0, $keyword_links_per_page); // array_rand($instance_indices, $keyword_links_per_page);
      
            // Force this to be an array ... even though array_rand will sometimes return a scalar var
            if(!is_array($instance_indices))
              $instance_indices = array($instance_indices);
      
            $index = 0;
            foreach($keyword_instances[1] as $keyword_instance)
            {
              $placeholder = "||!prlikeyword".$i++."||";
      
              // if we're replacing this index with a link then do it -- but
              // if not, then just replace it with itself later on. :)
              if(in_array($index,$instance_indices))
              {
                $link_html = "<a href=\"$url\" title=\"$title\" class=\"pretty-link-keyword\"".(($prlipro_options->keyword_links_nofollow)?" rel=\"nofollow\"":'').(($prlipro_options->keyword_links_open_new_window)?" target=\"_blank\"":'').">$keyword_instance</a>";
                $keyword_matches[] = array('html' => $link_html, 'placeholder' => $placeholder);
                $content = preg_replace($regex, $placeholder, $content, 1, $key_rep_count);
              }
              else
              {
                $keyword_matches[] = array('html' => $keyword_instance, 'placeholder' => $placeholder);
                $content = preg_replace($regex, $placeholder, $content, 1, $key_rep_count);
              }
      
              $index++;
            }
      
            $keyword_count++;
          }
      
          // Short circuit once we've reached the keywords_per_page
          if($keywords_per_page != -1 and $keyword_count >= $keywords_per_page)
            break;
        }
      
        $regexes = array();
        // Put back the ignores putting the onion back together in reverse order
        foreach(array_reverse($keyword_ignores) as $keyword_ignore)
        {
          // Replace $'s so pcre doesn't think we've got back references
          $ignore_text = str_replace('$','\$',$keyword_ignore['html']);
          $ignores_regex = '#'. preg_quote($keyword_ignore['placeholder'], '#') . '#';
          $regexes[] = $ignores_regex;
          $content = preg_replace($ignores_regex,$ignore_text,$content);
        }
      
        // Put back the matches putting the onion back together in reverse order
        foreach(array_reverse($keyword_matches) as $keyword_match)
        {
          // Replace $'s so pcre doesn't think we've got back references
          $keyword_text = str_replace('$','\$',$keyword_match['html']);
          $matches_regex = '#'. preg_quote($keyword_match['placeholder'], '#') . '#';
          $regexes[] = $matches_regex;
          $content = preg_replace($matches_regex,$keyword_text,$content);
        }
      }
      
      // Any remaining non-pretty links will now be pretty linked if url/pretty link
      // replacement has been enabled on this blog
      if($prlipro_options->replace_urls_with_pretty_links)
      {
        preg_match_all('#<a.*?href\s*?=\s*?[\'"](https?://.*?)[\'"]#mi', $content, $matches);
      
        $prli_lookup = $prli_link->get_target_to_pretty_urls( $matches[1], true );

        if($prli_lookup !== false and is_array($prli_lookup)) {
          $url_patterns = array_map( create_function( '$target_url', 'return "#" . preg_quote($target_url, "#") . "#";' ), array_keys($prli_lookup) );
          $url_replacements = array_values(array_map( create_function( '$pretty_urls', 'return $pretty_urls[0];' ), $prli_lookup ));
      
          $content = preg_replace( $url_patterns, $url_replacements, $content );
        }
      }
    }
  }
    
  return $content;
}

function prlipro_replace_keywords_in_comments( $content )
{
  global $comment;

  // We don't care if it's a real uri -- it's used as an index
  $request_uri = "#prli-comment-{$comment->comment_ID}";

  return prlipro_replace_keywords( $content, $request_uri );
}

function prlipro_replace_keywords_in_sidebars( $sidebars )
{
  print_r($sidebars);
}

function prlipro_keyword_link_style()
{
  global $prlipro_options;

  if( $prlipro_options->keyword_replacement_is_on and
      ( !empty($prlipro_options->keyword_link_custom_css) or
        !empty($prlipro_options->keyword_link_hover_custom_css) ) )
  {
?>
<style type="text/css"> a.pretty-link-keyword { <?php echo $prlipro_options->keyword_link_custom_css; ?> } a.pretty-link-keyword:hover { <?php echo $prlipro_options->keyword_link_hover_custom_css; ?> } </style>
<?php
  }
}

function prlipro_keyword_link_column_header()
{
  global $prlipro_options;

  if( $prlipro_options->keyword_replacement_is_on )
  {
?>
      <th class="manage-column" width="20%"><?php _e('Keywords <code>(PRO)</code>', 'pretty-link'); ?></th>
<?php
  }
}

function prlipro_keyword_link_column_row($link_id)
{
  global $prli_keyword, $prlipro_options;

  if( $prlipro_options->keyword_replacement_is_on )
  {
?>
      <td><?php echo $prli_keyword->getTextByLinkId( $link_id ); ?></td>
<?php
  }
}

function prlipro_split_test_link($link_id)
{
  global $prli_link, $prli_link_meta;

  $link = $prli_link->getOne($link_id);
  if($prli_link_meta->get_link_meta($link_id, 'prli-enable-split-test', true))
  {
?>
|&nbsp;<a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/pro/prlipro-reports.php&action=display-split-test-report&id=<?php echo $link->id; ?>" title="View the Split Test Report for <?php echo stripslashes(htmlspecialchars($link->name)); ?>"><?php _e('Split-Test Report', 'pretty-link'); ?></a>
<?php
  }
}

function prlipro_check_for_install_errors()
{
  // We no longer require curl
  /*
  if(!function_exists('curl_init'))
  {
  ?>
    <div class="error" style="padding-top: 5px; padding-bottom: 5px;"><strong>PHP-Curl Isn't Installed on your Web Server:</strong> Auto-Posting to Twitter with Pretty Link Pro won't work until you talk with your webhost to have it installed.</div>
  <?php
  }
  */
}

function prlipro_check_twitter_creds()
{
  global $prlipro_options;

  if( isset($prlipro_options->twitter_password) and 
      !empty($prlipro_options->twitter_password) )
  {
    ?>
      <div class="error" style="padding-top: 5px; padding-bottom: 5px;"><strong><?php _e('Twitter has changed the way it authenticates users:</strong> Auto-Posting to Twitter with Pretty Link Pro will not work until you update your <a href="admin.php?page=pretty-link/pro/prlipro-options.php">Twitter Credentials Here</a>', 'pretty-link'); ?></div>
    <?php
  }
}

/**************** PUBLIC FACING URL CREATION **********************/
function prlipro_public_create_form($atts)
{
  require_once(PRLIPRO_PATH.'/prlipro-public.php');
  extract(shortcode_atts(array(
    'label' => 'Enter a URL:&nbsp;',
    'button' => 'Shrink',
    'redirect_type' => '-1',
    'track' => '-1',
    'group' => '-1',
  ), $atts));

  return prlipro_display_public_form($label,$button,$redirect_type,$track,$group);
}

function prlipro_public_link_display()
{
  if(isset($_GET['slug']))
  {
    $slug = $_GET['slug'];
    $link = prli_get_link_from_slug($slug);
    $url  = prli_get_pretty_link_url($link->id);
    return "<a href=\"$url\">$url</a>";
  }
}

function prlipro_public_link_title_display()
{
  if(isset($_GET['slug']))
  {
    $slug = $_GET['slug'];
    $link = prli_get_link_from_slug($slug);
    return $link->name;
  }
}

function prlipro_public_link_target_url_display()
{
  if(isset($_GET['slug']))
  {
    $slug = $_GET['slug'];
    $link = prli_get_link_from_slug($slug);
    return $link->url;
  }
}

function prlipro_public_link_social_buttons_display()
{
  if(isset($_GET['slug']))
  {
    $slug = $_GET['slug'];
    $link = prli_get_link_from_slug($slug);
    return prlipro_get_social_buttons_bar($link->id);
  }
}

/***** ADD SHORTLINK AUTO-DISCOVERY *****/
function prlipro_shorturl_autodiscover()
{
  global $post;

  if(!is_object($post)) { return; }

  $pretty_link_id = PrliUtils::get_prli_post_meta($post->ID,"_pretty-link",true);

  if($pretty_link_id and (is_single() or is_page()))
  {
    $shorturl = prli_get_pretty_link_url($pretty_link_id);

    if($shorturl and !empty($shorturl))
    {
    ?>
      <link rel="shorturl" href="<?php echo $shorturl; ?>" />
    <?php
    }
  }
}

/***** ADD PRO OPTIONS TO STANDARD PRETTY-BAR *****/
function prlipro_validate_options($errors)
{
  if( !isset($_POST['prettybar_hide_attrib_link']) and !empty($_POST['prlipro-attrib-url']) and !preg_match('/^http.?:\/\/.*\..*$/', $_POST['prlipro-attrib-url'] ) )
    $errors[] = __("Pretty Bar Attribution URL must be a correctly formatted URL", 'pretty-link');

  return $errors;
}

function prlipro_store_options()
{
  global $prlipro_options;
  $prlipro_options->prettybar_hide_attrib_link = (int)isset($_POST[ 'prettybar_hide_attrib_link' ]);
  $prlipro_options->prettybar_attrib_url       = $_POST[ 'prettybar_attrib_url' ];

  // Save the posted value in the database
  $prlipro_options->store();
}

function prlipro_prettybar_options()
{
  global $prlipro_options;
?>
  <tr>
    <td colspan="2">
      <input type="checkbox" name="prettybar_hide_attrib_link" class="prettybar-hide-attrib-link-checkbox" <?php echo (($prlipro_options->prettybar_hide_attrib_link != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Hide Pretty Bar Attribution Link', 'pretty-link'); ?>
      <br/><span class="description"><?php _e('Check this to hide the pretty link attribution link on the pretty bar.', 'pretty-link'); ?> <strong><?php _e('Wait, before you do this, you might want to leave this un-checked and set the alternate URL of this link to your <em>Pretty Link Pro</em> <a href="http://prettylinkpro.com/amember/aff_member.php">Affiliate URL</a> to earn a few bucks while you are at it.', 'pretty-link'); ?></strong></span>
      <table class="option-pane prettybar-attrib-url">
        <tr class="form-field">
          <td valign="top" width="15%"><?php _e("Alternate Pretty Bar Attribution URL:", 'prettybar_attrib_url' , 'pretty-link'); ?> </td>
          <td width="85%">
            <input type="text" name="prettybar_attrib_url" value="<?php echo $prlipro_options->prettybar_attrib_url; ?>"/>
            <br/><span class="description"><?php _e('If set, this will replace the PrettyBars attribution URL. This is a very good place to put your <em>Pretty Link Pro</em> <a href="http://prettylinkpro.com/amember/aff_member.php">Affiliate Link</a>.', 'pretty-link'); ?></span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php
}

function prlipro_options_head()
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
  if (jQuery('.prettybar-hide-attrib-link-checkbox').is(':checked')) {
    jQuery('.prettybar-attrib-url').hide();
  }
  else {
    jQuery('.prettybar-attrib-url').show();
  }

  jQuery('.prettybar-hide-attrib-link-checkbox').change(function() {
    if (jQuery('.prettybar-hide-attrib-link-checkbox').is(':checked')) {
      jQuery('.prettybar-attrib-url').hide();
    }
    else {
      jQuery('.prettybar-attrib-url').show();
    }
  });
});
</script>
<?php
}

function prlipro_display_attrib_link($link_html)
{
  global $prlipro_options;

  if( $prlipro_options->prettybar_hide_attrib_link == 1 )
    return '';

  if( !empty($prlipro_options->prettybar_attrib_url) )
    $link_html = preg_replace("#http://blairwilliams.com/pl#",$prlipro_options->prettybar_attrib_url,$link_html);

  return $link_html;
}

/***** BOOKMARKLET GENERATOR CODE *****/
function prlipro_bookmarklet_generator_head()
{
  global $prli_options;
?>

<script type="text/javascript">
function toggle_tweetdeck_instructions()
{
  jQuery('.tweetdeck_instructions').slideToggle();
}

function toggle_twitter_iphone_instructions()
{
  jQuery('.twitter_iphone_instructions').slideToggle();
}

jQuery(document).ready(function() {
  jQuery('#prlipro-custom-bookmarklet-form').change(function() {
    var redirect_type = jQuery('#prlipro-bookmarklet-redirect-type').val();
    var track = jQuery('#prlipro-bookmarklet-track').val();
    var group = jQuery('#prlipro-bookmarklet-group').val();
    var label = jQuery('#prlipro-bookmarklet-label').val();

    var link = '<span class="bookmarklet-updated"><a href="javascript:location.href=\'<?php echo site_url(); ?>/index.php?action=prli_bookmarklet&k=<?php echo $prli_options->bookmarklet_auth; ?>&rt=' + redirect_type + '&trk=' + track + '&grp=' + group + '&target_url=\'+escape(location.href);">' + label + '</a></span>';
    jQuery('#prlipro-custom-bookmarklet-link').html(link);
    jQuery('#prlipro-custom-bookmarklet-link').hide();
    jQuery('#prlipro-custom-bookmarklet-link').fadeIn('slow');
  });
});
</script>
<style type="text/css">
.bookmarklet-updated {
  background-color: #ffffa0;
}
</style>
<?php
}

function prlipro_bookmarklet_generator() {
  global $prli_blogurl, $prli_options;
  ?>
  <h3><?php _e('End-Point URL:', 'pretty-link'); ?></h3>
  <p><span class="description"><?php _e('This can be used to integrate with your twitter client.', 'pretty-link'); ?></span>
    <pre><?php echo $prli_blogurl; ?>/index.php?action=prli_endpoint_url&k=<?php echo $prli_options->bookmarklet_auth; ?>&url=</pre>
    <br/>
    <a href="javascript:toggle_tweetdeck_instructions();"><strong><?php _e('Show TweetDeck Integration Instructions', 'pretty-link'); ?></strong></a>
    <div class="tweetdeck_instructions" style="display: none">
      <span class="description"><?php _e('Follow the', 'pretty-link'); ?> <a href="http://support.tweetdeck.com/entries/132632-add-a-custom-url-shortener" target="_blank"><?php _e('TweetDeck Custom URL Instructions', 'pretty-link'); ?></a><?php _e(' and add the following URL to TweetDeck', 'pretty-link'); ?></span>
      <pre><?php echo $prli_blogurl; ?>/index.php?action=prli_endpoint_url&k=<?php echo $prli_options->bookmarklet_auth; ?>&url=%@</pre>
    </div>
    <br/>
    <a href="javascript:toggle_twitter_iphone_instructions();"><strong><?php _e('Show Twitter for iPhone Integration Instructions', 'pretty-link'); ?></strong></a>
    <div class="twitter_iphone_instructions" style="display: none">
      <span class="description"><?php _e('Follow the', 'pretty-link'); ?> <a href="http://developer.atebits.com/tweetie-iphone/custom-shortening/" target="_blank"><?php _e('Twitter for iPhone Custom URL Instructions', 'pretty-link'); ?></a><?php _e(' and add the following URL to Twitter for iPhone', 'pretty-link'); ?></span>
      <pre><?php echo $prli_blogurl; ?>/index.php?action=prli_endpoint_url&k=<?php echo $prli_options->bookmarklet_auth; ?>&url=%@</pre>
    </div>
  </p>
<h3><?php _e('Custom Bookmarklet (Pro):', 'pretty-link'); ?></h3>
<p><strong><span id="prlipro-custom-bookmarklet-link"><a href="<?php echo PrliLink::bookmarklet_link(); ?>"><?php _e('Get PrettyLink', 'pretty-link'); ?></a></span></strong><br/>
<span class="description"><?php _e('Alter the options below to customize this Bookmarklet. As you modify the label, redirect type, tracking and group, you will see this bookmarklet update -- when the settings are how you want them, drag the bookmarklet into your toolbar. You can create as many bookmarklets as you want each with different settings.', 'pretty-link'); ?></span>
<p><strong><?php _e('Pretty Link Options', 'pretty-link'); ?></strong></p>
<form id="prlipro-custom-bookmarklet-form">
<p>
  <label for="prlipro-bookmarklet-label"><?php _e('Label:', 'pretty-link'); ?>
    <input id="prlipro-bookmarklet-label" type="text" size="25" value="Get PrettyLink"/>
  </label>
</p>
<p>
  <label for="prlipro-bookmarklet-redirect-type"><?php _e('Redirection:', 'pretty-link'); ?>
    <select id="prlipro-bookmarklet-redirect-type" name="prlipro-bookmarklet-redirect-type?>">
      <option value="-1"><?php _e('Default', 'pretty-link'); ?>&nbsp;</option>
      <option value="301"><?php _e('Permanent/301', 'pretty-link'); ?>&nbsp;</option>
      <option value="307"><?php _e('Temporary/307', 'pretty-link'); ?>&nbsp;</option>
      <option value="prettybar"><?php _e('PrettyBar', 'pretty-link'); ?>&nbsp;</option>
      <option value="cloak"><?php _e('Cloak', 'pretty-link'); ?>&nbsp;</option>
    </select>
  </label>
</p>
<p>
  <label for="prlipro-bookmarklet-track"><?php _e('Tracking Enabled:', 'pretty-link'); ?>
    <select id="prlipro-bookmarklet-track" name="prlipro-bookmarklet-track?>">
      <option value="-1"><?php _e('Default', 'pretty-link'); ?>&nbsp;</option>
      <option value="1"><?php _e('Yes', 'pretty-link'); ?>&nbsp;</option>
      <option value="0"><?php _e('No', 'pretty-link'); ?>&nbsp;</option>
    </select>
  </label>
</p>
<p>
  <label for="prlipro-bookmarklet-group"><?php _e('Group:', 'pretty-link'); ?>
    <select id="prlipro-bookmarklet-group" name="prlipro-bookmarklet-group?>">
      <option value="-1"><?php _e('None', 'pretty-link'); ?>&nbsp;</option>
      <?php
      $groups = prli_get_all_groups();
      foreach($groups as $g)
      {
      ?>
      <option value="<?php echo $g['id']; ?>"><?php echo $g['name']; ?>&nbsp;</option>
      <?php
      }
      ?>
    </select>
  </label>
</p>
</form>

  <?php
}

// shortcode for displaying the pretty link for the post/page
function prlipro_pretty_link()
{
  global $post, $prlipro_utils, $prlipro_options, $prli_blogurl, $prli_link, $wp_query, $prli_link_meta;
      
  // Don't show until published
  if(get_post_status($post->ID) != 'publish')
    return '';
      
  // only show button if enabled and links are being generated
  if(is_page() and !$prlipro_options->pages_auto) 
    return '';

  if((is_single() or is_archive() or $wp_query->is_posts_page) and !$prlipro_options->posts_auto) 
    return '';
            
  if(is_home() and !is_page() and !$prlipro_options->posts_auto)
    return '';
            
  $pretty_link_id = PrliUtils::get_prli_post_meta($post->ID,"_pretty-link",true);
  $pretty_link = $prli_link->getOne($pretty_link_id);
  $shorturl = "{$prli_blogurl}".PrliUtils::get_permalink_pre_slug_uri()."{$pretty_link->slug}";

  return $shorturl;
}

// Template Tag for displaying the pretty link for the post/page
function the_prettylink()
{
  echo prlipro_pretty_link();
}

function prlipro_twitter_oath_endpoints()
{
  global $prli_blogurl;
  $request_uri = $_SERVER['REQUEST_URI'];
  
//  if(preg_match('#^/prli-twitter-oauth/redirect[?]?.*?$#', $request_uri))
//  REMOVED BY PAUL CAETER FOR SUB-DIRECTORY FIX ISSUE #16
  if(strpos($request_uri, 'prli-twitter-oauth/redirect') > 0)
  {
    session_start();

    /* Clean up any request tokens laying around so we don't screw this up */
    unset($_SESSION['oauth_token']);
    unset($_SESSION['oauth_token_secret']);

    /* Build TwitterOAuth object with client credentials. */
    $connection = new PrliTwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

    /* Get temporary credentials. */
    $request_token = $connection->getRequestToken(OAUTH_CALLBACK);

    
    /* Save temporary credentials to session. */

    $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

    /* If last connection failed don't display authorization link. */
    switch ($connection->http_code) {
      case 200:
        /* Build authorize URL and redirect user to Twitter. */
        $url = $connection->getAuthorizeURL($token);
        header('Location: ' . $url);
        break;
      default:
        /* Show notification if something went wrong. */
        echo __('Could not connect to Twitter. Refresh the page or try again later.', 'pretty-link');
    }
    
    exit;
  }
//  else if(preg_match('#^/prli-twitter-oauth/callback[?]?.*?$#', $request_uri))
//  REMOVED BY PAUL CARTER FOR SUB DIRECTORY FIX ISSUE #16
  else if(strpos($request_uri, 'prli-twitter-oauth/callback') > 0)
  {
    global $prlipro_options;

    session_start();

    /* If the oauth_token is old redirect to the connect page. */
    if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
      $_SESSION['oauth_status'] = 'oldtoken';
      //header('Location: ./clearsessions.php');
      session_destroy();
      $message = __('There was an error saving your Twitter account.', 'pretty-link');
      header('Location: ' . admin_url() . '/admin.php?page=pretty-link/pro/prlipro-options.php&message=' . $message);
      exit;
    }

    /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
    $connection = new PrliTwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

    /* Request access tokens from twitter */
    $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
    
    //Array ( [oauth_token] => ### [oauth_token_secret] => ### [user_id] => ### [screen_name] => ### )
    $already_authenticated = false;
    foreach($prlipro_options->twitter_oauth_tokens as $tokindex => $existing_token)
    {
      if($existing_token['screen_name'] == $access_token['screen_name'])
      {
        $prlipro_options->twitter_oauth_tokens[$tokindex] = $access_token;
        $already_authenticated = true;
      }
    }
    
    if(!$already_authenticated)
      $prlipro_options->twitter_oauth_tokens[] = $access_token;
    

    /* Remove no longer needed request tokens */
    unset($_SESSION['oauth_token']);
    unset($_SESSION['oauth_token_secret']);

    /* If HTTP response is 200 continue otherwise send to connect page to retry */
    if (200 == $connection->http_code) {
      /* The user has been verified and the access tokens can be saved for future use */
      
      // Store this ish
      $prlipro_options->store();
      $message = __('Your Twitter Account was successfully saved.', 'pretty-link');

    } else {
      /* Save HTTP status for error dialog on connnect page.*/
      $message = __('There was an error saving your Twitter account.', 'pretty-link');
    }

    session_destroy();
    header('Location: ' . admin_url() . '/admin.php?page=pretty-link/pro/prlipro-options.php&message=' . $message);
    exit;
  }
}

/***************** ADD PRETTY BAR, PIXEL and CLOAKED REDIRECTION *********************/
add_action('prli_redirection_types', 'prlipro_redirection_types');
function prlipro_redirection_types($values)
{
?>
  <option value="prettybar"<?php echo $values['redirect_type']['prettybar']; ?>><?php _e('Pretty Bar', 'pretty-link'); ?>&nbsp;</option>
  <option value="cloak"<?php echo $values['redirect_type']['cloak']; ?>><?php _e('Cloaked', 'pretty-link'); ?>&nbsp;</option>
  <option value="pixel"<?php echo $values['redirect_type']['pixel']; ?>><?php _e('Pixel', 'pretty-link'); ?>&nbsp;</option>
  <option value="metarefresh"<?php echo $values['redirect_type']['metarefresh']; ?>><?php _e('Meta Refresh', 'pretty-link'); ?>&nbsp;</option>
  <option value="javascript"<?php echo $values['redirect_type']['javascript']; ?>><?php _e('Javascript', 'pretty-link'); ?>&nbsp;</option>
<?php
}

add_action('prli_issue_cloaked_redirect', 'prlipro_issue_cloaked_redirect', 10, 4);
function prlipro_issue_cloaked_redirect($redirect_type, $pretty_link, $pretty_link_url, $param_string)
{
  global $prli_blogurl, $prli_link_meta, $prli_blogname;

  $delay = $prli_link_meta->get_link_meta($pretty_link->id, 'delay', true);
  $google_tracking = $prli_link_meta->get_link_meta($pretty_link->id, 'google_tracking', true);
  $double_redirect = $prli_link_meta->get_link_meta($pretty_link->id, 'double_redirect', true);

  if($double_redirect and !isset($_REQUEST['dblrdct'])) {
    $delay = 0; // first redirect should be set to zero
    $google_tracking = false; // don't track with google on the first redirect
  }

  switch($redirect_type)
  {
    case 'pixel':
      header("HTTP/1.1 200 OK");
      break;
    case 'prettybar':
      // In a double redirect, the second redirect should be a simple
      // temporary redirect... This is so we don't get a frame within a frame
      if( $double_redirect and isset( $_REQUEST['dblrdct'] ) ) {
        if($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.0')
          header("HTTP/1.1 302 Found");
        else
          header("HTTP/1.1 307 Temporary Redirect");
        header('Location: '.$pretty_link_url.$param_string);
      }
      else {
        header("HTTP/1.1 200 OK");
        require_once PRLIPRO_VIEWS_PATH . '/prli-links/prettybar-redirect.php';
      }
      break;
    case 'cloak':
      // In a double redirect, the second redirect should be a simple
      // temporary redirect... This is so we don't get a frame within a frame
      if( $double_redirect and isset($_REQUEST['dblrdct'] ) ) {
        if($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.0')
          header("HTTP/1.1 302 Found");
        else
          header("HTTP/1.1 307 Temporary Redirect");
        header('Location: '.$pretty_link_url.$param_string);
      }
      else {
        header("HTTP/1.1 200 OK");
        require_once PRLIPRO_VIEWS_PATH . '/prli-links/cloaked-redirect.php';
      }
      break;
    case 'metarefresh':
      header("HTTP/1.1 200 OK");
      require_once PRLIPRO_VIEWS_PATH . '/prli-links/metarefresh-redirect.php';
      break;
    case 'javascript':
      header("HTTP/1.1 200 OK");
      require_once PRLIPRO_VIEWS_PATH . '/prli-links/javascript-redirect.php';
      break;
    default:
      if($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.0')
        header("HTTP/1.1 302 Found");
      else
        header("HTTP/1.1 307 Temporary Redirect");
      header('Location: '.$pretty_link_url.$param_string);
  }
}

add_action('prli_default_redirection_types','prlipro_default_redirection_options');
function prlipro_default_redirection_options($link_redirect_type)
{
?>
  <option value="prettybar" <?php echo (($link_redirect_type == 'prettybar')?' selected="selected"':''); ?>/><?php _e('Pretty Bar', 'pretty-link'); ?></option>
  <option value="cloak" <?php echo (($link_redirect_type == 'cloak')?' selected="selected"':''); ?>/><?php _e('Cloak', 'pretty-link'); ?></option>
  <option value="pixel" <?php echo (($link_redirect_type == 'pixel')?' selected="selected"':''); ?>/><?php _e('Pixel', 'pretty-link'); ?></option>
  <option value="metarefresh" <?php echo (($link_redirect_type == 'metarefresh')?' selected="selected"':''); ?>/><?php _e('Meta Refresh', 'pretty-link'); ?></option>
  <option value="javascript" <?php echo (($link_redirect_type == 'javascript')?' selected="selected"':''); ?>/><?php _e('Javascript', 'pretty-link'); ?></option>
<?php
}

add_action('prli_custom_option_pane', 'prlipro_display_prettybar_options');
function prlipro_display_prettybar_options()
{
  global $prli_options;

  $prettybar_image_url  = 'prli_prettybar_image_url';
  $prettybar_background_image_url  = 'prli_prettybar_background_image_url';
  $prettybar_color  = 'prli_prettybar_color';
  $prettybar_text_color  = 'prli_prettybar_text_color';
  $prettybar_link_color  = 'prli_prettybar_link_color';
  $prettybar_hover_color  = 'prli_prettybar_hover_color';
  $prettybar_visited_color  = 'prli_prettybar_visited_color';
  $prettybar_show_title  = 'prli_prettybar_show_title';
  $prettybar_show_description  = 'prli_prettybar_show_description';
  $prettybar_show_share_links  = 'prli_prettybar_show_share_links';
  $prettybar_show_target_url_link  = 'prli_prettybar_show_target_url_link';
  $prettybar_title_limit = 'prli_prettybar_title_limit';
  $prettybar_desc_limit = 'prli_prettybar_desc_limit';
  $prettybar_link_limit = 'prli_prettybar_link_limit';

?>
<h3><a class="toggle prettybar-toggle-button"><?php _e('PrettyBar Options', 'pretty-link'); ?> <span class="prettybar-expand" style="display: none;">[+]</span><span class="prettybar-collapse">[-]</span></a></h3>
<table class="prettybar-toggle-pane form-table">
  <tr class="form-field">
    <td valign="top" width="15%"><?php _e("Image URL:", 'pretty-link'); ?> </td>
    <td width="85%">
      <input type="text" name="<?php echo $prettybar_image_url; ?>" value="<?php echo $prli_options->prettybar_image_url; ?>"/>
      <br/><span class="description"><?php _e('If set, this will replace the logo image on the PrettyBar. The image that this URL references should be 48x48 Pixels to fit.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr class="form-field">
    <td valign="top" width="15%"><?php _e("Background Image URL:", 'pretty-link'); ?> </td>
    <td width="85%">
      <input type="text" name="<?php echo $prettybar_background_image_url; ?>" value="<?php echo $prli_options->prettybar_background_image_url; ?>"/>
      <br/><span class="description"><?php _e('If set, this will replace the background image on PrettyBar. The image that this URL references should be 65px tall - this image will be repeated horizontally across the bar.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td valign="top" width="15%"><?php _e("Background Color:", 'pretty-link'); ?> </td>
    <td width="85%">
      #<input type="text" name="<?php echo $prettybar_color; ?>" value="<?php echo $prli_options->prettybar_color; ?>" size="6"/>
      <br/><span class="description"><?php _e('This will alter the background color of the PrettyBar if you haven\'t specified a PrettyBar background image.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td valign="top" width="15%"><?php _e("Text Color:", 'pretty-link'); ?> </td>
    <td width="85%">
      #<input type="text" name="<?php echo $prettybar_text_color; ?>" value="<?php echo $prli_options->prettybar_text_color; ?>" size="6"/>
      <br/><span class="description"><?php _e('If not set, this defaults to black (RGB value <code>#000000</code>) but you can change it to whatever color you like.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td valign="top" width="15%"><?php _e("Link Color:", 'pretty-link'); ?> </td>
    <td width="85%">
      #<input type="text" name="<?php echo $prettybar_link_color; ?>" value="<?php echo $prli_options->prettybar_link_color; ?>" size="6"/>
      <br/><span class="description"><?php _e('If not set, this defaults to blue (RGB value <code>#0000ee</code>) but you can change it to whatever color you like.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td valign="top" width="15%"><?php _e("Link Hover Color:", 'pretty-link'); ?> </td>
    <td width="85%">
      #<input type="text" name="<?php echo $prettybar_hover_color; ?>" value="<?php echo $prli_options->prettybar_hover_color; ?>" size="6"/>
      <br/><span class="description"><?php _e('If not set, this defaults to RGB value <code>#ababab</code> but you can change it to whatever color you like.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td valign="top" width="15%"><?php _e("Visited Link Color:", 'pretty-link'); ?> </td>
    <td width="85%">
      #<input type="text" name="<?php echo $prettybar_visited_color; ?>" value="<?php echo $prli_options->prettybar_visited_color; ?>" size="6"/>
      <br/><span class="description"><?php _e('If not set, this defaults to RGB value <code>#551a8b</code> but you can change it to whatever color you like.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td valign="top" width="15%"><?php _e("Title Char Limit*:", 'pretty-link'); ?> </td>
    <td width="85%">
      <input type="text" name="<?php echo $prettybar_title_limit; ?>" value="<?php echo $prli_options->prettybar_title_limit; ?>" size="4"/>
      <br/><span class="description"><?php _e('If your Website has a long title then you may need to adjust this value so that it will all fit on the PrettyBar. It is recommended that you keep this value to <code>30</code> characters or less so the PrettyBar\'s format looks good across different browsers and screen resolutions.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td valign="top" width="15%"><?php _e("Description Char Limit*:", 'pretty-link'); ?> </td>
    <td width="85%">
      <input type="text" name="<?php echo $prettybar_desc_limit; ?>" value="<?php echo $prli_options->prettybar_desc_limit; ?>" size="4"/>
      <br/><span class="description"><?php _e('If your Website has a long Description (tagline) then you may need to adjust this value so that it will all fit on the PrettyBar. It is recommended that you keep this value to <code>40</code> characters or less so the PrettyBar\'s format looks good across different browsers and screen resolutions.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td valign="top" width="15%"><?php _e("Target URL Char Limit*:", 'pretty-link'); ?> </td>
    <td width="85%">
      <input type="text" name="<?php echo $prettybar_link_limit; ?>" value="<?php echo $prli_options->prettybar_link_limit; ?>" size="4"/>
      <br/><span class="description"><?php _e('If you link to a lot of large Target URLs you may want to adjust this value. It is recommended that you keep this value to <code>40</code> or below so the PrettyBar\'s format looks good across different browsers and URL sizes', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="checkbox" name="<?php echo $prettybar_show_title; ?>" <?php echo (($prli_options->prettybar_show_title != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Show Pretty Bar Title', 'pretty-link'); ?>
      <br/><span class="description"><?php _e('Make sure this is checked if you want the title of your blog (and link) to show up on the PrettyBar.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="checkbox" name="<?php echo $prettybar_show_description; ?>" <?php echo (($prli_options->prettybar_show_description != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Show Pretty Bar Description', 'pretty-link'); ?>
      <br/><span class="description"><?php _e('Make sure this is checked if you want your site description to show up on the PrettyBar.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="checkbox" name="<?php echo $prettybar_show_share_links; ?>" <?php echo (($prli_options->prettybar_show_share_links != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Show Pretty Bar Share Links', 'pretty-link'); ?>
      <br/><span class="description"><?php _e('Make sure this is checked if you want "share links" to show up on the PrettyBar.', 'pretty-link'); ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="checkbox" name="<?php echo $prettybar_show_target_url_link; ?>" <?php echo (($prli_options->prettybar_show_target_url_link != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Show Pretty Bar Target URL', 'pretty-link'); ?>
      <br/><span class="description"><?php _e('Make sure this is checked if you want a link displaying the Target URL to show up on the PrettyBar.', 'pretty-link'); ?></span>
    </td>
  </tr>

  <?php do_action('prli-prettybar-options'); ?>
</table>
<?php
}

add_filter('prli-validate-options', 'prlipro_validate_prettybar_options');
function prlipro_validate_prettybar_options($errors)
{
  global $prli_options;

  $prettybar_image_url  = 'prli_prettybar_image_url';
  $prettybar_background_image_url  = 'prli_prettybar_background_image_url';
  $prettybar_color  = 'prli_prettybar_color';
  $prettybar_text_color  = 'prli_prettybar_text_color';
  $prettybar_link_color  = 'prli_prettybar_link_color';
  $prettybar_hover_color  = 'prli_prettybar_hover_color';
  $prettybar_visited_color  = 'prli_prettybar_visited_color';
  $prettybar_show_title  = 'prli_prettybar_show_title';
  $prettybar_show_description  = 'prli_prettybar_show_description';
  $prettybar_show_share_links  = 'prli_prettybar_show_share_links';
  $prettybar_show_target_url_link  = 'prli_prettybar_show_target_url_link';
  $prettybar_title_limit = 'prli_prettybar_title_limit';
  $prettybar_desc_limit = 'prli_prettybar_desc_limit';
  $prettybar_link_limit = 'prli_prettybar_link_limit';

  if( !empty($_POST[$prettybar_image_url]) and !preg_match('/^http.?:\/\/.*\..*$/', $_POST[$prettybar_image_url] ) )
    $errors[] = __("Logo Image URL must be a correctly formatted URL", 'pretty-link');

  if( !empty($_POST[$prettybar_background_image_url]) and !preg_match('/^http.?:\/\/.*\..*$/', $_POST[$prettybar_background_image_url] ) )
    $errors[] = __("Background Image URL must be a correctly formatted URL", 'pretty-link');

  if( !empty($_POST[ $prettybar_color ]) and !preg_match( "#^[0-9a-fA-F]{6}$#", $_POST[ $prettybar_color ] ) )
    $errors[] = __("PrettyBar Background Color must be an actual RGB Value", 'pretty-link');

  if( !empty($_POST[ $prettybar_text_color ]) and !preg_match( "#^[0-9a-fA-F]{6}$#", $_POST[ $prettybar_text_color ] ) )
    $errors[] = __("PrettyBar Text Color must be an actual RGB Value", 'pretty-link');

  if( !empty($_POST[ $prettybar_link_color ]) and !preg_match( "#^[0-9a-fA-F]{6}$#", $_POST[ $prettybar_link_color ] ) )
    $errors[] = __("PrettyBar Link Color must be an actual RGB Value", 'pretty-link');

  if( !empty($_POST[ $prettybar_hover_color ]) and !preg_match( "#^[0-9a-fA-F]{6}$#", $_POST[ $prettybar_hover_color ] ) )
    $errors[] = __("PrettyBar Hover Color must be an actual RGB Value", 'pretty-link');

  if( !empty($_POST[ $prettybar_visited_color ]) and !preg_match( "#^[0-9a-fA-F]{6}$#", $_POST[ $prettybar_visited_color ] ) )
    $errors[] = __("PrettyBar Hover Color must be an actual RGB Value", 'pretty-link');

  if( empty($_POST[ $prettybar_title_limit ]) )
    $errors[] = __("PrettyBar Title Character Limit must not be blank", 'pretty-link');

  if( empty($_POST[ $prettybar_desc_limit ]) )
    $errors[] = __("PrettyBar Description Character Limit must not be blank", 'pretty-link');

  if( empty($_POST[ $prettybar_link_limit ]) )
    $errors[] = __("PrettyBar Link Character Limit must not be blank", 'pretty-link');

  if( !empty($_POST[ $prettybar_title_limit ]) and !preg_match( "#^[0-9]*$#", $_POST[ $prettybar_title_limit ] ) )
    $errors[] = __("PrettyBar Title Character Limit must be a number", 'pretty-link');

  if( !empty($_POST[ $prettybar_desc_limit ]) and !preg_match( "#^[0-9]*$#", $_POST[ $prettybar_desc_limit ] ) )
    $errors[] = __("PrettyBar Description Character Limit must be a number", 'pretty-link');

  if( !empty($_POST[ $prettybar_link_limit ]) and !preg_match( "#^[0-9]*$#", $_POST[ $prettybar_link_limit ] ) )
    $errors[] = __("PrettyBar Link Character Limit must be a number", 'pretty-link');

  return $errors;
}

add_action('prli-store-options', 'prlipro_store_prettybar_options');
function prlipro_store_prettybar_options($errors)
{
  global $prli_options;

  $prettybar_image_url  = 'prli_prettybar_image_url';
  $prettybar_background_image_url  = 'prli_prettybar_background_image_url';
  $prettybar_color  = 'prli_prettybar_color';
  $prettybar_text_color  = 'prli_prettybar_text_color';
  $prettybar_link_color  = 'prli_prettybar_link_color';
  $prettybar_hover_color  = 'prli_prettybar_hover_color';
  $prettybar_visited_color  = 'prli_prettybar_visited_color';
  $prettybar_show_title  = 'prli_prettybar_show_title';
  $prettybar_show_description  = 'prli_prettybar_show_description';
  $prettybar_show_share_links  = 'prli_prettybar_show_share_links';
  $prettybar_show_target_url_link  = 'prli_prettybar_show_target_url_link';
  $prettybar_title_limit = 'prli_prettybar_title_limit';
  $prettybar_desc_limit = 'prli_prettybar_desc_limit';
  $prettybar_link_limit = 'prli_prettybar_link_limit';

  $prli_options->prettybar_image_url = stripslashes($_POST[ $prettybar_image_url ]);
  $prli_options->prettybar_background_image_url = stripslashes($_POST[ $prettybar_background_image_url ]);
  $prli_options->prettybar_color = stripslashes($_POST[ $prettybar_color ]);
  $prli_options->prettybar_text_color = stripslashes($_POST[ $prettybar_text_color ]);
  $prli_options->prettybar_link_color = stripslashes($_POST[ $prettybar_link_color ]);
  $prli_options->prettybar_hover_color = stripslashes($_POST[ $prettybar_hover_color ]);
  $prli_options->prettybar_visited_color = stripslashes($_POST[ $prettybar_visited_color ]);
  $prli_options->prettybar_show_title = (int)isset($_POST[ $prettybar_show_title ]);
  $prli_options->prettybar_show_description = (int)isset($_POST[ $prettybar_show_description ]);
  $prli_options->prettybar_show_share_links = (int)isset($_POST[ $prettybar_show_share_links ]);
  $prli_options->prettybar_show_target_url_link = (int)isset($_POST[ $prettybar_show_target_url_link ]);
  $prli_options->prettybar_title_limit = stripslashes($_POST[ $prettybar_title_limit ]);
  $prli_options->prettybar_desc_limit = stripslashes($_POST[ $prettybar_desc_limit ]);
  $prli_options->prettybar_link_limit = stripslashes($_POST[ $prettybar_link_limit ]);
}

add_action('prli_options_js', 'prlipro_options_js');
function prlipro_options_js()
{
?>
  jQuery('.prettybar-expand').show();
  jQuery('.prettybar-collapse').hide();
  jQuery('.prettybar-toggle-pane').hide();
  jQuery('.prettybar-toggle-button').click(function() {
    jQuery('.prettybar-toggle-pane').toggle();
    jQuery('.prettybar-expand').toggle();
    jQuery('.prettybar-collapse').toggle();
  });
<?php
}

add_action('prli_delete_link', 'prlipro_delete_link');
/** Deletes all the pro-specific meta about a link right before the link is deleted.
  * TODO: When refactoring occurs, move this to PrliProLink or the link model for pro
  */
function prlipro_delete_link($id)
{
  global $wpdb, $prli_tweet, $prli_keyword, $prli_report, $prli_link_rotation;
  $query = $wpdb->prepare("DELETE FROM {$prli_tweet->table_name} WHERE link_id=%d", $id);
  $wpdb->query($query);

  $query = $wpdb->prepare("DELETE FROM {$prli_keyword->table_name} WHERE link_id=%d", $id);
  $wpdb->query($query);

  $query = $wpdb->prepare("UPDATE {$prli_report->table_name} SET goal_link_id=NULL WHERE goal_link_id=%d", $id);
  $wpdb->query($query);

  $query = $wpdb->prepare("DELETE FROM {$prli_report->links_table_name} WHERE link_id=%d", $id);
  $wpdb->query($query);

  $query = $wpdb->prepare("DELETE FROM {$prli_link_rotation->table_name} WHERE link_id=%d", $id);
  $wpdb->query($query);

  $query = $wpdb->prepare("DELETE FROM {$prli_link_rotation->cr_table_name} WHERE link_id=%d", $id);
  $wpdb->query($query);

  $query = $wpdb->prepare("DELETE FROM {$wpdb->postmeta} WHERE meta_key=%s AND meta_value=%s", '_pretty-link', $id);
  $wpdb->query($query);

  // Reset the whole keyword cache
  $prli_keyword->deleteContentCache();
}

add_action('prli_custom_link_options', 'prlipro_custom_link_options');
function prlipro_custom_link_options() {
  global $prlipro_options;
  require( PRLIPRO_VIEWS_PATH . '/prli-links/link-options.php');
}

add_action('prli-store-options', 'prlipro_store_link_options');
function prlipro_store_link_options() {
  global $prlipro_options;

  $prlipro_options->google_tracking = (int)isset($_REQUEST[ $prlipro_options->google_tracking_str ]);
  $prlipro_options->generate_qr_codes = (int)isset($_REQUEST[ $prlipro_options->generate_qr_codes_str ]);
  $prlipro_options->qr_code_links = (int)isset($_REQUEST[ $prlipro_options->qr_code_links_str ]);
  
  // Save the posted value in the database
  $prlipro_options->store();
}

add_action('prli-create-link', 'prlipro_create_link', 10, 2);
function prlipro_create_link( $link_id, $values ) {
  global $prlipro_options, $prli_link_meta;
  
  if(!isset($values['google_tracking'])) {
    $prli_link_meta->update_link_meta($link_id, 'google_tracking', $prlipro_options->google_tracking);
  }
}

add_filter( 'prli_target_url', 'prlipro_double_redirect', 10 );
function prlipro_double_redirect( $target ) {
  global $prli_link_meta;
  $double_redirect = $prli_link_meta->get_link_meta($target['link_id'], 'double_redirect', true);

  if( $double_redirect ) {
    if(isset($_REQUEST['dblrdct'])) {
      if(!wp_verify_nonce($_REQUEST['dblrdct'], 'prli-double-redirect'))
        wp_die(__('You are unauthorized to view this resource', 'pretty-link'));
    }
    else { // if we're doing a double redirect then redirect back to this same link but with nonce in place
      add_filter('prli_track_link', create_function( '', 'return false;' )); // don't use native tracking on the first redirect
	  $target['url'] = prli_get_pretty_link_url($target['link_id']);
	  $target['url'] .= "?dblrdct=" . wp_create_nonce('prli-double-redirect');
    }
  }
  
  return $target;
}

add_filter('prli_redirect_params', 'prlipro_dblrdct_param', 10, 2);
// We want to get rid of the double redirect parameter here
function prlipro_dblrdct_param($params) {
  $params = preg_replace( '#dblrdct=[^&]*&?#', '', $params );

  if($params=='?')
    return '';
  else
    return $params;
}

add_action('prli-special-link-action', 'prlipro_qr_code_icon',10,1);
function prlipro_qr_code_icon($pretty_link_id) {
  global $prlipro_options;
  $pretty_link_url = prli_get_pretty_link_url($pretty_link_id);

  if($prlipro_options->qr_code_links):
  ?>
  <a href="<?php echo $pretty_link_url; ?>/qr.png" title="<?php printf(__('View QR Code for this link: %s', 'pretty-link'), $pretty_link_url); ?>" target="_blank">
	<img src="<?php echo PRLIPRO_IMAGES_URL; ?>/qr_code_icon.gif" width="13px" height="13px" />
  </a>
  <?php
  endif;
  
  if($prlipro_options->generate_qr_codes):
  ?>
  <a href="<?php echo $pretty_link_url; ?>/qr.png?download=<?php echo wp_create_nonce('prli-generate-qr-code'); ?>" title="<?php printf(__('Download QR Code for this link: %s', 'pretty-link'), $pretty_link_url); ?>">
	<img src="<?php echo PRLIPRO_IMAGES_URL; ?>/download_qr_code_icon.gif" width="13px" height="13px" />
  </a>
  <?php
  endif;
}

add_filter('prli-check-if-slug', 'prlipro_generate_qr_code',10,2);
function prlipro_generate_qr_code($pretty_link_id, $slug) {
  global $prli_link, $prlipro_options;

  if( $prlipro_options->qr_code_links or 
	  ( $prlipro_options->generate_qr_codes and 
		isset($_REQUEST['download']) and
		wp_verify_nonce($_REQUEST['download'], 'prli-generate-qr-code') ) ) {
  
    $qr_regexp = '#/qr\.png$#';
    
    if(!$pretty_link_id and preg_match($qr_regexp, $slug)) {
      $slug_sans_qr = preg_replace($qr_regexp, '', $slug);
      
      if($pretty_link = $prli_link->getOneFromSlug( $slug_sans_qr )) {
        $pretty_link_url = prli_get_pretty_link_url($pretty_link->id);
        
        $google_crt_url = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($pretty_link_url);
        
        $response = wp_remote_get( $google_crt_url, array( 'sslverify' => $_SERVER['HTTPS'] ) );
        
        if( isset($response) and is_array($response) and // if response is an error then WP_Error will be returned
            isset($response['body']) and !empty($response['body'])) {
          
      	  header("Content-Type: image/png");
      	
          if(isset($_REQUEST['download']) and wp_verify_nonce($_REQUEST['download'], 'prli-generate-qr-code')) {
      	    header("HTTP/1.1 200 OK"); // Have to hard code this for some reason?
            header("Content-Disposition: attachment;filename=\"" . $slug_sans_qr . "_qr.png\"");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: public");
          }
          
          echo $response['body'];
        }
        
        exit;
      }
    }
  }

  return $pretty_link_id;
}

function prlipro_render_prettybar($slug) {
  global $prli_blogurl, $prli_link, $prli_options, $prli_blogname, $prli_blogdescription, $target_url;
  if($link = $prli_link->getOneFromSlug( $slug )) {
    $bar_image = $prli_options->prettybar_image_url;
    $bar_background_image = $prli_options->prettybar_background_image_url;
    $bar_color = $prli_options->prettybar_color;
    $bar_text_color = $prli_options->prettybar_text_color;
    $bar_link_color = $prli_options->prettybar_link_color;
    $bar_visited_color = $prli_options->prettybar_visited_color;
    $bar_hover_color = $prli_options->prettybar_hover_color;
    $bar_show_title = $prli_options->prettybar_show_title;
    $bar_show_description = $prli_options->prettybar_show_description;
    $bar_show_share_links = $prli_options->prettybar_show_share_links;
    $bar_show_target_url_link = $prli_options->prettybar_show_target_url_link;
    $bar_title_limit = (int)$prli_options->prettybar_title_limit;
    $bar_desc_limit = (int)$prli_options->prettybar_desc_limit;
    $bar_link_limit = (int)$prli_options->prettybar_link_limit;
    
    $target_url = $link->url;
    
    $shortened_title = stripslashes(substr($prli_blogname,0,$bar_title_limit));
    $shortened_desc  = stripslashes(substr($prli_blogdescription,0,$bar_desc_limit));
    $shortened_link  = stripslashes(substr($target_url,0,$bar_link_limit));
    
    if(strlen($prli_blogname) > $bar_title_limit)
      $shortened_title .= "...";
    
    if(strlen($prli_blogdescription) > $bar_desc_limit)
      $shortened_desc .= "...";
    
    if(strlen($target_url) > $bar_link_limit)
      $shortened_link .= "...";

    require PRLIPRO_VIEWS_PATH . "/prli-links/prettybar.php";
  }
}

add_action('prli_list_end_icon','prlipro_link_list_end_icons');
function prlipro_link_list_end_icons($link) {
  global $prli_link_meta;
  if($double_redirect = $prli_link_meta->get_link_meta($link->id, 'double_redirect', true)):
    ?>
    <span title="<?php _e('Double Redirection Enabled', 'pretty-link') ?>" style="font-size: 14px; line-height: 14px; padding: 0px; margin: 0px; color: green;"><strong>D</strong></span>&nbsp;
    <?php
  endif;
}

add_action('prli_bulk_action_right_col', 'prlipro_bulk_actions');
function prlipro_bulk_actions() {
  require PRLIPRO_VIEWS_PATH . '/prli-links/bulk-edit.php';
}

add_action('prli-bulk-action-update','prlipro_update_bulk_actions',10,2);
function prlipro_update_bulk_actions($ids,$params) {
  global $prli_link_meta, $prli_keyword;

  $ids_array = explode(',', $ids);

  foreach($ids_array as $id) {
    /*
    if(isset($params['keywords']) and !empty($params['keywords'])) {
      $keywords = $prli_keyword->getTextByLinkId( $link_id );
      if(isset($keyword) and !empty($keyword))
        $keywords = $keywords . ',' . $params['keywords'];
      else
        $keywords = $params['keywords'];
      $prli_keyword->updateLinkKeywords($id, stripslashes($keywords));
    }
    if(isset($params['url_replacements']) and !empty($params['url_replacements'])) {
      $url_replacements = $prli_link_meta->get_link_meta($link_id, 'prli-url-replacements', true);
      if(isset($url_replacements) and !empty($url_replacements))
        $url_replacements = $url_replacements . ',' . $params['url_replacements'];
      else
        $url_replacements = $params['url_replacements'];
      $prli_link_meta->update_link_meta($link_id, 'prli-url-replacements', $url_replacements);
    }
    */
    
    if(isset($params['double_redirect']))
      $prli_link_meta->update_link_meta( $id, 'double_redirect', (strtolower($params['double_redirect'])=='on') );
    
    if(isset($params['google_tracking']))
      $prli_link_meta->update_link_meta( $id, 'google_tracking', (strtolower($params['google_tracking'])=='on') );
  }
}
