<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

class PrliProUtils
{
  /** Fetches the Tweet Count from Twitter using the search API. This function
    * basically does a count of hits from your url (which is unique).
    */
  function get_tweets($query,$twid = 0)
  {
    global $prli_utils;

    if($twid)
      $page_params = "?rpp=50&since_id={$twid}&q=" . urlencode($query);
    else
      $page_params = '?rpp=50&q=' . urlencode($query);

    $tweet_str = $this->get_twitter_json($page_params);
    $tweet_page = $prli_utils->prli_json_decode($tweet_str);
    $tweets = $tweet_page[1]['results'];
    
    while(!empty($tweet_page[1]['next_page']))
    {
      $page_params = $tweet_page[1]['next_page'];
      $tweet_str  = $this->get_twitter_json($page_params);
      $tweet_page = $prli_utils->prli_json_decode($tweet_str);

      if(is_array($tweet_page[1]['results']))
        $tweets = array_merge($tweets, $tweet_page[1]['results']);
    }

    return $tweets;
  }

  function get_twitter_json($params)
  {
	$url = 'http://search.twitter.com/search.json'.$params;

    $wp_http = new WP_Http;
    $result = $wp_http->request( $url, array( 'sslverify' => false, 
                                              'headers' => array( 'Content-type: application/json;charset="utf-8"' ) ) );

    if(!$result or is_a($result, 'WP_Error') or !isset($result['body']))
      return '';

    $response = $result['body'];

    return $response;
  }

  /* This is a fake cron ... it only updates this count when an elapsed time has passed */
  function update_tweet_count_cron($link_id, $interval = 900 /* defaults to 15 minutes */)
  {
    global $prli_link_meta;
    
    static $already_updated_tweet_count;
    
    if( !isset($already_updated_tweet_count) or
        empty($already_updated_tweet_count) or
        !$already_updated_tweet_count )
    {
      $last_update = $prli_link_meta->get_link_meta($link_id,'pretty-link-tweet-last-update',true);
      $now = time();
      
      if($last_update)
      {
        $time_lapse = $now - $last_update;
        if($time_lapse >= $interval)
        {
          $this->update_link_tweets($link_id);
          $prli_link_meta->update_link_meta($link_id,'pretty-link-tweet-last-update',$now);
          $already_updated_tweet_count = true;
        }
      }
      else
      {
        $this->update_link_tweets($link_id);
        $prli_link_meta->update_link_meta($link_id,'pretty-link-tweet-last-update',$now);
        $already_updated_tweet_count = true;
      }
    }
  }

  function update_link_tweets($link_id)
  {
    global $wpdb, $prli_link, $prli_tweet, $prli_blogurl, $prli_link_meta;

    $pretty_link = $prli_link->getOne($link_id);
    if(!$pretty_link)
      return false;

    $url = "{$prli_blogurl}".PrliUtils::get_permalink_pre_slug_uri()."{$pretty_link->slug}";
    $latest_tweet = $prli_tweet->getLatestTweet($link_id);

    $tweets = is_object($latest_tweet) ? $this->get_tweets($url,$latest_tweet->twid) : false;

    if(is_array($tweets))
    {
      foreach($tweets as $tweet)
      {
        // if this tweet is already in the database then let's not store it again ... okay?
        if( !$prli_tweet->getOneFromTwid( $tweet['id'] ) )
        {
          $new_tweet = array();
          $new_tweet['link_id']              = $link_id;
          $new_tweet['twid']                 = $tweet['id'];
          $new_tweet['tw_text']              = $tweet['text'];
          $new_tweet['tw_to_user_id']        = $tweet['to_user_id'];
          $new_tweet['tw_from_user']         = $tweet['from_user'];
          $new_tweet['tw_from_user_id']      = $tweet['from_user_id'];
          $new_tweet['tw_iso_language_code'] = $tweet['iso_language_code'];
          $new_tweet['tw_source']            = $tweet['source'];
          $new_tweet['tw_profile_image_url'] = $tweet['profile_image_url'];
          $new_tweet['tw_created_at']        = $tweet['created_at'];
          $prli_tweet->create($new_tweet);
        }
      }

      // Update the tweet count with the fastest and best data
      $tweet_count = $prli_tweet->get_tweet_record_count($link_id);

      $prli_link_meta->update_link_meta($link_id,'pretty-link-tweet-count',$tweet_count);
    }
    else
    {
      if(!$prli_link_meta->get_link_meta($link_id,'pretty-link-tweet-count',true))
      {
        // Update the tweet count
        $tweet_count = $prli_tweet->get_tweet_record_count($link_id);

        $prli_link_meta->update_link_meta($link_id,'pretty-link-tweet-count',$tweet_count);
      }
    }
  }

  function get_tweet_count($link_id)
  {
    global $prli_link_meta;

    $count = $prli_link_meta->get_link_meta($link_id,'pretty-link-tweet-count',true);

    if(!$count)
      return 0;
    else
      return $count;
  }

  function update_all_links_tweets()
  {
    global $prli_link;
    $links_list = $prli_link->getAll();

    foreach($links_list as $link)
      $this->update_link_tweets($link->id);
  }

  function get_twitter_status_message($pretty_link_url, $pretty_link_name, $tweet_format)
  {
    global $prlipro_options;
    
    //ADDED BY PAUL 1.5.5 - fix for $29.99 being in post title
    $pretty_link_name = str_replace("$", "%24", $pretty_link_name);
    
    if(empty($tweet_format))
      $tweet_format = "{%title%} {%url%} {%hash%}";

    $tweet_message = preg_replace("#\{\%title\%\}#", stripslashes($pretty_link_name), $tweet_format);
    $tweet_message = preg_replace("#\{\%url\%\}#", $pretty_link_url, $tweet_message);
    $tweet_message = preg_replace("#\{\%hash\%}#", stripslashes($prlipro_options->twitter_hash_tags), $tweet_message);

    // 120 to leave room for retweeting a 140 char tweet
    if(strlen($tweet_message) > 120)
    {
      $non_title_size = strlen($tweet_message) - strlen($pretty_link_name);
      $title_size = 120 - $non_title_size;
      $link_title = substr($pretty_link_name, 0, $title_size);
      $tweet_message = preg_replace("#\{\%title\%\}#", stripslashes($link_title), $tweet_format);
      $tweet_message = preg_replace("#\{\%url\%\}#", $pretty_link_url, $tweet_message);
      $tweet_message = preg_replace("#\{\%hash\%}#", stripslashes($prlipro_options->twitter_hash_tags), $tweet_message);
    }

    return $tweet_message;
  }

  function update_twitter_status($twitter_status)
  {
    global $prlipro_options;

    $twitter_status = stripslashes(trim(strip_tags($twitter_status)));

    // Tweet the alt usernames / passwords
    if(isset($prlipro_options->twitter_oauth_tokens) and is_array($prlipro_options->twitter_oauth_tokens))
    {
      foreach( $prlipro_options->twitter_oauth_tokens as $oauthtok )
        $response = $this->prli_twitter_oauth_tweet($twitter_status, $oauthtok);
    }
    else
      return false;

    return $response;
  }

  function post_post_to_twitter($post_id,$message='')
  {
    global $prlipro_options, $prli_link, $prli_blogurl, $prli_link_meta;
  
    // If twitter autoposting is off ... might as well return
    if(is_page() and (!$prlipro_options->pages_auto or !$prlipro_options->twitter_pages_button or !$prlipro_options->twitter_auto_post_page))
      return false;
  
    if(is_single() and (!$prlipro_options->posts_auto or !$prlipro_options->twitter_posts_button or !$prlipro_options->twitter_auto_post_post))
      return false;
  
    $pretty_link_id = PrliUtils::get_prli_post_meta($post_id,'_pretty-link',true);
    $pretty_link = $prli_link->getOne($pretty_link_id);
    $pretty_link_url = "{$prli_blogurl}".PrliUtils::get_permalink_pre_slug_uri()."{$pretty_link->slug}";
    //ADDED BY PAUL 1.5.5 - fix for $29.99 being in the post title
    //Basically all I'm doing is "urldecode"ing the %24 which I set two methods above
    $twitter_status = urldecode($this->get_twitter_status_message($pretty_link_url,$pretty_link->name,$message));

    if($this->update_twitter_status($twitter_status))
      $prli_link_meta->update_link_meta($pretty_link_id,'pretty-link-posted-to-twitter', 1);

    return $twitter_status;
  }

  function sort_by_stringlen($word_array,$dir = 'ASC')
  {
    if( $dir == "ASC" )
      uasort($word_array, 'prli_compare_stringlen_asc');
    else if( $dir == "DESC" )
      uasort($word_array, 'prli_compare_stringlen_desc');

    return $word_array;
  }

  /**
    * This function expects an array of weights in integer
    * form [ 35, 25, 15, 50 ] that add up to 100.
    */
  function w_rand($weights)
  {
    $r = mt_rand(1,1000);
    $offset = 0;
    foreach ($weights as $k => $w)
    {
      $offset += $w*10;
      if ($r <= $offset)
        return $k;
    }
  }

  function prli_twitter_oauth_tweet($message, $access_token)
  {
    /* Create a TwitterOauth object with consumer/user tokens. */
    $connection = new PrliTwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

    /* Get logged in user to help with tests. */
    $user = $connection->get('account/verify_credentials');

    $status = $connection->post('statuses/update', array('status' => $message));

    return $status;
  }
  
  public static function ga_installed() {
	if(!function_exists('is_plugin_active'))
	  require(ABSPATH . '/wp-admin/includes/plugin.php');
	
	if(is_plugin_active('google-analyticator/google-analyticator.php'))
	  return array('name' => __('Google Analyticator', 'pretty-link'), 'slug' => 'google-analyticator');
	else if(is_plugin_active('google-analytics-for-wordpress/googleanalytics.php'))
	  return array('name' => __('Google Analytics for WordPress', 'pretty-link'), 'slug' => 'google-analytics-for-wordpress');
	else if(is_plugin_active('googleanalytics/googleanalytics.php'))
	  return array('name' => __('Google Analytics', 'pretty-link'), 'slug' => 'google-analytics');
	else
	  return false;
  }

  public static function ga_tracking_code($ga_plugin_slug) {
	ob_start();
	
    if($ga_plugin_slug == 'google-analyticator')
      add_google_analytics();
    else if($ga_plugin_slug == 'google-analytics-for-wordpress') {
      $ga_filter = new GA_Filter();
      $ga_filter->spool_analytics();
    }
    else if($ga_plugin_slug == 'google-analytics')
      googleanalytics();
    
    $tracking_code_str = ob_get_contents();
    ob_end_clean();

    return $tracking_code_str;
  }
}

// Utility functions not part of this class //
function prli_compare_stringlen_asc($val_1, $val_2)
{
  // initialize the return value to zero
  $retVal = 0;

  // compare lengths
  $firstVal = strlen($val_1);
  $secondVal = strlen($val_2);

  if($firstVal > $secondVal)
    $retVal = 1;
  else if($firstVal < $secondVal)
    $retVal = -1;

  return $retVal;
}

function prli_compare_stringlen_desc($val_1, $val_2)
{
  // initialize the return value to zero
  $retVal = 0;

  // compare lengths
  $firstVal = strlen($val_1);
  $secondVal = strlen($val_2);

  if($firstVal > $secondVal)
    $retVal = -1;
  else if($firstVal < $secondVal)
    $retVal = 1;

  return $retVal;
}
