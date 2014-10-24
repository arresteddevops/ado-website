<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'delete-cache')
{
  $prli_keyword->deleteContentCache();
?>
<div class="updated"><p><strong><?php _e('Your Keyword Replacement Cache was successfully deleted', 'pretty-link'); ?></strong></p></div>
<?php
}
else if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'trim_dup_tweets')
{
  global $wpdb;

  $block_size = 2000;
  $upper_limit = $block_size - 1;
  $tweet_table = "{$wpdb->prefix}prli_tweets";

  $query = $wpdb->prepare("SELECT count(DISTINCT twid) FROM %s", $tweet_table);
  $twid_count = $wpdb->get_var($query);

  for($offset=0; $offset < $twid_count; $offset += $block_size)
  {
    $limit = $offset + $upper_limit;
    $query = $wpdb->prepare("SELECT id FROM {$tweet_table} GROUP BY twid LIMIT %d,%d",$offset,$limit);
    $tweet_ids = $wpdb->get_col($query);

    if(is_array($tweet_ids) and count($tweet_ids) > 0)
    {
      $query = "DELETE FROM {$tweet_table} WHERE id not in (" . implode(',', $tweet_ids) . ")";
      $wpdb->query($query);
    }
  }
?>
<div class="updated"><p><strong><?php _e('Your Duplicate Tweets were Successfully Trimmed' , 'pretty-link'); ?></strong></p></div>
<?php
}

$errors = array();

$hidden_field_name = 'prlipro_update_options';

// Set variable names
$pages_auto  = 'prli_pages_auto';
$posts_auto  = 'prli_posts_auto';
$pages_group = 'prli_pages_group';
$posts_group = 'prli_posts_group';

$twitter_handle       = 'prli_twitter_handle';
$twitter_password     = 'prli_twitter_password';  // Deprecated
$twitter_alt_creds    = 'prli_twitter_alt_creds'; // Deprecated
$twitter_oauth_tokens = 'prli_twitter_oauth_tokens';
$twitter_hash_tags    = 'prli_twitter_hash_tags';
$twitter_posts_button = 'prli_twitter_posts_button';
$twitter_pages_button = 'prli_twitter_pages_button';
$twitter_posts_comments = 'prli_twitter_posts_comments';
$twitter_pages_comments = 'prli_twitter_pages_comments';
$twitter_auto_post_post = 'prli_twitter_auto_post_post';
$twitter_auto_post_page = 'prli_twitter_auto_post_page';
$twitter_badge_placement = 'prli_twitter_badge_placement';
$twitter_badge_hidden_on_homepage = 'prli_twitter_badge_hidden_on_homepage';
$twitter_badge_show_in_feed = 'prli_twitter_badge_show_in_feed';
$twitter_comments_header = 'prli_twitter_comments_header';
$twitter_comments_height = 'prli_twitter_comments_height';

$social_buttons           = 'prli_social_buttons';
$social_buttons_placement = 'prli_social_buttons_placement';
$social_buttons_padding   = 'prli_social_buttons_padding';
$social_buttons_show_in_feed = 'prli_social_buttons_show_in_feed';
$social_posts_buttons     = 'prli_social_posts_buttons';
$social_pages_buttons     = 'prli_social_pages_buttons';

$keyword_replacement_is_on      = 'prli_keyword_replacement_is_on';
$keywords_per_page              = 'prli_keywords_per_page';
$keyword_links_per_page         = 'prli_keyword_links_per_page';
$keyword_links_open_new_window  = 'prli_keyword_links_open_new_window';
$keyword_links_nofollow         = 'prli_keyword_links_nofollow';
$keyword_link_custom_css        = 'prli_keyword_link_custom_css';
$keyword_link_hover_custom_css  = 'prli_keyword_link_hover_custom_css';
$set_keyword_thresholds         = 'prli_set_keyword_thresholds';
$replace_urls_with_pretty_links = 'prli_replace_urls_with_pretty_links';
$replace_keywords_in_comments   = 'prli_replace_keywords_in_comments';
$replace_keywords_in_feeds      = 'prli_replace_keywords_in_feeds';

$use_prettylink_url = 'prli_use_prettylink_url';
$prettylink_url = 'prli_prettylink_url';

$minimum_access_role = 'prli_min_role';

$allow_public_link_creation = 'prli_allow_public_link_creation';
$use_public_link_display_page = 'prli_use_public_link_display_page';
$public_link_display_page = 'prli_public_link_display_page';

// See if the user has posted us some information
// If they did, this hidden field will be set to 'Y'
if( isset($_REQUEST[ $hidden_field_name ]) and $_REQUEST[ $hidden_field_name ] == 'Y' ) 
{
  // Validate This
  //if( !empty($_POST[ $prettybar_link_limit ]) and !preg_match( "#^[0-9]*$#", $_POST[ $prettybar_link_limit ] ) )
  //  $errors[] = __("PrettyBar Link Character Limit must be a number", 'pretty-link');

  if( isset($_POST[$set_keyword_thresholds]) and empty($_POST[ $keywords_per_page ]) )
    $errors[] = __("Keywords Per Page is required", 'pretty-link');

  if( isset($_POST[$set_keyword_thresholds]) and empty($_POST[ $keyword_links_per_page ]) )
    $errors[] = __("Keyword Links Per Page is required", 'pretty-link');
  
  if( isset($_POST[ $use_prettylink_url ]) and !preg_match('/^http.?:\/\/.*\..*[^\/]$/', $_POST[ $prettylink_url ] ))
    $errors[] = __("You need to enter a valid Pretty Link Base URL now that you have selected \"Use an alternate base url for your Pretty Links\"", 'pretty-link');

  if( isset($_POST[ $use_public_link_display_page ]) and !preg_match('/^http.?:\/\/.*\..*[^\/]$/', $_POST[ $public_link_display_page ] ) )
    $errors[] = __("You need to enter a valid Public Link Display URL now that you have selected \"Use a custom public link display page\"", 'pretty-link');

  if( isset($_POST[ $twitter_comments_height ]) and !empty($_POST[ $twitter_comments_height ]) and !is_numeric($_POST[$twitter_comments_height]) )
    $errors[] = __("Twitter Comment Height must either be blank or a number.", 'pretty-link');

  // Read their posted value
  $prlipro_options->pages_auto = (int)isset($_POST[ $pages_auto ]);
  $prlipro_options->posts_auto = (int)isset($_POST[ $posts_auto ]);
  $prlipro_options->pages_group = $_POST[ $pages_group ];
  $prlipro_options->posts_group = $_POST[ $posts_group ];

  $prlipro_options->twitter_posts_button = (int)isset($_POST[ $twitter_posts_button ]);
  $prlipro_options->twitter_pages_button = (int)isset($_POST[ $twitter_pages_button ]);
  $prlipro_options->twitter_posts_comments = (int)isset($_POST[ $twitter_posts_comments ]);
  $prlipro_options->twitter_pages_comments = (int)isset($_POST[ $twitter_pages_comments ]);
  $prlipro_options->twitter_auto_post_post = (int)isset($_POST[ $twitter_auto_post_post ]);
  $prlipro_options->twitter_auto_post_page = (int)isset($_POST[ $twitter_auto_post_page ]);
  $prlipro_options->twitter_badge_placement = $_POST[ $twitter_badge_placement ];
  $prlipro_options->twitter_badge_hidden_on_homepage = (int)isset($_POST[ $twitter_badge_hidden_on_homepage ]);
  $prlipro_options->twitter_badge_show_in_feed = (int)isset($_POST[ $twitter_badge_show_in_feed ]);
  $prlipro_options->twitter_hash_tags = $_POST[ $twitter_hash_tags ];

  $prlipro_options->twitter_comments_header = $_POST[ $twitter_comments_header ];
  $prlipro_options->twitter_comments_height = $_POST[ $twitter_comments_height ];

  $prlipro_options->twitter_handle = $_POST[ $twitter_handle ];
  $prlipro_options->twitter_oauth_tokens = isset($_POST[ $twitter_oauth_tokens ])?$_POST[ $twitter_oauth_tokens ]:'';
  unset($prlipro_options->twitter_password); // Deprecated
  unset($prlipro_options->twitter_alt_creds); // Deprecated

  $prlipro_options->social_buttons = $_POST[ $social_buttons ];
  $prlipro_options->social_buttons_placement = $_POST[ $social_buttons_placement ];
  $prlipro_options->social_buttons_show_in_feed = (int)isset($_POST[ $social_buttons_show_in_feed ]);
  $prlipro_options->social_buttons_padding = $_POST[ $social_buttons_padding ];
  $prlipro_options->social_posts_buttons = (int)isset($_POST[ $social_posts_buttons ]);
  $prlipro_options->social_pages_buttons = (int)isset($_POST[ $social_pages_buttons ]);

  $prlipro_options->keyword_replacement_is_on = (int)isset($_POST[ $keyword_replacement_is_on ]);
  $prlipro_options->keyword_links_open_new_window = (int)isset($_POST[ $keyword_links_open_new_window ]);
  $prlipro_options->keyword_links_nofollow = (int)isset($_POST[ $keyword_links_nofollow ]);
  $prlipro_options->keyword_link_custom_css = $_POST[ $keyword_link_custom_css ];
  $prlipro_options->keyword_link_hover_custom_css = $_POST[ $keyword_link_hover_custom_css ];
  $prlipro_options->replace_urls_with_pretty_links = (int)isset($_POST[ $replace_urls_with_pretty_links ]);
  $prlipro_options->replace_keywords_in_comments = (int)isset($_POST[ $replace_keywords_in_comments ]);
  $prlipro_options->replace_keywords_in_feeds = (int)isset($_POST[ $replace_keywords_in_feeds ]);

  // If these 2 values have changed then refresh all the replacements site wide
  if( ( $prlipro_options->keywords_per_page != $_POST[ $keywords_per_page ] ) or
      ( $prlipro_options->keyword_links_per_page != $_POST[ $keyword_links_per_page ] ) or
      ( isset($_POST[ $set_keyword_thresholds ]) and ( $prlipro_options->set_keyword_thresholds != $_POST[ $set_keyword_thresholds ] ) ) )
    $prli_keyword->deleteContentCache();

  $prlipro_options->set_keyword_thresholds = (int)isset($_POST[ $set_keyword_thresholds ]);
  $prlipro_options->keywords_per_page = $_POST[ $keywords_per_page ];
  $prlipro_options->keyword_links_per_page = $_POST[ $keyword_links_per_page ];

  $prlipro_options->use_prettylink_url = (int)isset($_POST[ $use_prettylink_url ]);
  $prlipro_options->prettylink_url = $_POST[ $prettylink_url ];
  
  $prlipro_options->min_role = $_POST[ $minimum_access_role ];

  $prlipro_options->allow_public_link_creation = (int)isset($_POST[ $allow_public_link_creation ]);
  $prlipro_options->use_public_link_display_page = (int)isset($_POST[ $use_public_link_display_page ]);
  $prlipro_options->public_link_display_page = $_POST[ $public_link_display_page ];
  
  if( count($errors) > 0 )
    require(PRLI_VIEWS_PATH.'/shared/errors.php');
  else
  {
    // Save the posted value in the database
    $prlipro_options->store();

    // Create pretty links for posts
    if($prlipro_options->posts_auto)
      prlipro_create_post_and_page_pretty_links($prlipro_options->posts_group, 'posts');

    if($prlipro_options->pages_auto)
      prlipro_create_post_and_page_pretty_links($prlipro_options->pages_group, 'pages');

    // Put an options updated message on the screen
?>

<div class="updated"><p><strong><?php _e('Options saved.', 'pretty-link'); ?></strong></p></div>
<?php
  }
}

// Move this to a helper at some point
function prlipro_create_post_and_page_pretty_links($group = '',$type = 'posts')
{
  global $post, $prli_utils, $prli_link, $prli_link_meta;

  if($type == 'posts')
    $postslist = get_posts('showposts=-1');
  else if($type == 'pages')
    $postslist = get_pages('showposts=-1');

  foreach($postslist as $post)
  {
    if( $post->post_status == "publish" )
    {
      $post_id = $post->ID;

      $prlipro_post_options = PrliProPostOptions::get_options($post_id);
    
      $pretty_link_id = PrliUtils::get_prli_post_meta($post->ID,'_pretty-link',true);

      // Try to find a pretty link that is using this link already
      if(!$pretty_link_id)
        $pretty_link_id = $prli_link->find_first_target_url(get_permalink($post_id));
      
      $pretty_link = $prli_link->getOne($pretty_link_id);

      if(empty($pretty_link) or !$pretty_link)
      {
        $slug = (($prli_utils->slugIsAvailable($prlipro_post_options->requested_slug))?$prlipro_post_options->requested_slug:'');
        $pl_insert_id = prli_create_pretty_link( get_permalink(), 
                                                 $slug,
                                                 addslashes($post->post_title),
                                                 addslashes($post->post_excerpt),
                                                 $group
                                               );
        $new_pretty_link = $prli_link->getOne($pl_insert_id);

        if(isset($post->ID) and !empty($post->ID) and $post->ID)
          PrliUtils::update_prli_post_meta($post->ID,'_pretty-link',$new_pretty_link->id,true);

        $prli_link_meta->update_link_meta($new_pretty_link->id,'twitter_message',$prlipro_post_options->requested_twitter_message);
      }
      else
      {
        prli_update_pretty_link( $pretty_link_id,
                                 get_permalink(), 
                                 $pretty_link->slug,
                                 addslashes($post->post_title),
                                 addslashes($post->post_excerpt),
                                 $group
                               );

        if(isset($post->ID) and !empty($post->ID) and $post->ID)
          PrliUtils::update_prli_post_meta($post->ID,'_pretty-link',$pretty_link_id,true);

        $prli_link_meta->update_link_meta($pretty_link_id,'twitter_message',$prlipro_post_options->requested_twitter_message);
      }
    }
  }
}

require_once PRLIPRO_PATH . '/classes/views/prlipro-options/form.php';
