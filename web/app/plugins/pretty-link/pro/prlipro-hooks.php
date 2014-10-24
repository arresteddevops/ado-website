<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

// add hooks & filters
add_action('admin_menu', 'prlipro_load');
add_action('save_post', 'prlipro_save_postdata', 10, 2); // Manual update post
add_action('transition_post_status', 'prlipro_transition_post_status', 10, 3); // Publishing Scheduled content, etc.
add_action('xmlrpc_publish_post', 'prlipro_xmlrpc_publish_post', 10, 1); // Publishing Via XML-RPC

function prlipro_load()
{
  global $prlipro_options;

  $role = 'administrator';
  if(isset($prlipro_options->min_role))
    $role = $prlipro_options->min_role;

  add_submenu_page('pretty-link', __('Pretty Link Pro | Reports', 'pretty-link'), __('<code>Pro</code> Reports', 'pretty-link'), $role, PRLIPRO_PATH.'/prlipro-reports.php');
  add_submenu_page('pretty-link', __('Pretty Link Pro | Import/Export', 'pretty-link'), __('<code>Pro</code> Import/Export', 'pretty-link'), $role, PRLIPRO_PATH.'/prlipro-import-export.php');
  add_submenu_page('pretty-link', __('Pretty Link Pro | Pro Options', 'pretty-link'), __('<code>Pro</code> Options', 'pretty-link'), $role, PRLIPRO_PATH.'/prlipro-options.php');

  // Show the meta box on post edit pages for auto generated pretty links
  if($prlipro_options->posts_auto)
    add_meta_box("prlipro", __("Pretty Link Pro", 'pretty-link'), "prlipro_post_sidebar", "post", "side", "high");

  if( $prlipro_options->twitter_posts_button or 
      $prlipro_options->twitter_posts_comments or
      $prlipro_options->social_posts_buttons or
      $prlipro_options->keyword_replacement_is_on)
    add_meta_box("prlipro_options", __("Pretty Link Pro Options", 'pretty-link'), "prlipro_post_options", "post", "normal");

  // Show the meta box on page edit pages for auto generated pretty links
  if($prlipro_options->pages_auto)
    add_meta_box("prlipro", __("Pretty Link Pro", 'pretty-link'), "prlipro_post_sidebar", "page", "side", "high");

  if( $prlipro_options->twitter_pages_button or 
      $prlipro_options->twitter_pages_comments or
      $prlipro_options->social_pages_buttons or
      $prlipro_options->keyword_replacement_is_on)
    add_meta_box("prlipro_options", __("Pretty Link Pro Options", 'pretty-link'), "prlipro_post_options", "page", "normal");

  add_action('admin_head-pretty-link/pro/prlipro-options.php', 'prlipro_options_admin_header');
  add_action('admin_head-pretty-link/pro/prlipro-reports.php', 'prlipro_reports_admin_header');
  //add_action('admin_head-toplevel_page_pretty-link', 'prlipro_link_header');
  //add_action('admin_head-pretty-link/prli-add-link.php', 'prlipro_link_header');
  add_action('admin_head-pretty-link/prli-tools.php', 'prlipro_bookmarklet_generator_head' );
  add_action('admin_head-post.php', 'prlipro_post_header' );
  add_action('admin_head-post-new.php', 'prlipro_post_header' );
  add_action('admin_head-page.php', 'prlipro_post_header' );
  add_action('admin_head-page-new.php', 'prlipro_post_header' );
}

add_action('init', 'prlipro_route_standalone_request');

add_filter('the_content', 'prlipro_add_tweet_button_to_content', 10);
add_shortcode('tweetbadge', 'prlipro_tweet_badge');

add_filter('the_content', 'prlipro_add_tweet_comments_to_content', 12);
add_shortcode('tweet_comments', 'prlipro_tweet_comments');

add_filter('the_content', 'prlipro_add_social_buttons_to_content', 11);
add_shortcode('social_buttons_bar', 'prlipro_social_buttons_bar');

add_action('wp_enqueue_scripts', 'prlipro_post_formatting');

// Removes tweet this from excerpts
function prlipro_excerpt_remove_tweet_button($excerpt)
{
  if(!is_feed())
  {
    remove_filter('the_content', 'prlipro_add_tweet_button_to_content', 10);
    remove_filter('the_content', 'prlipro_add_social_buttons_to_content', 11);
  }
  remove_filter('the_content', 'prlipro_add_tweet_comments_to_content', 12);
  remove_filter('the_content', 'prlipro_replace_keywords', 1);
  return $excerpt;
}

add_filter('get_the_excerpt', 'prlipro_excerpt_remove_tweet_button', 1); 

add_action( 'prli_list_icon',     'prlipro_link_display_icon' );
add_action( 'prli_link_fields',   'prlipro_display_link_options' );
add_action( 'prli_record_click',  'prlipro_record_rotation_click' );
add_action( 'prli_update_link',   'prlipro_update_link_options' );
add_filter( 'prli_validate_link', 'prlipro_validate_link_options' );
add_filter( 'prli_target_url',    'prlipro_rotate_target_url', 99 );

add_filter('the_content', 'prlipro_replace_keywords', 1);

if($prlipro_options->replace_keywords_in_feeds)
  add_filter('the_content_feed', 'prlipro_replace_keywords', 1);

if($prlipro_options->replace_keywords_in_comments)
  add_filter('comment_text', 'prlipro_replace_keywords_in_comments', 1);

if( $prlipro_options->replace_keywords_in_feeds and
    $prlipro_options->replace_keywords_in_comments)
  add_filter('comment_text_rss', 'prlipro_replace_keywords_in_comments', 1);

add_action('wp_head', 'prlipro_keyword_link_style');

add_action('prli_link_column_header','prlipro_keyword_link_column_header');
add_action('prli_link_column_footer','prlipro_keyword_link_column_header');

add_action('prli_link_column_row','prlipro_keyword_link_column_row');

add_action('prli-link-action','prlipro_split_test_link');

add_action('prli-link-message','prlipro_check_for_install_errors');
add_action('prli-options-message','prlipro_check_for_install_errors');
add_action('prlipro-options-message','prlipro_check_for_install_errors');
add_action('prlipro_sidebar_top','prlipro_check_for_install_errors');

add_action('prli-link-message','prlipro_check_twitter_creds');
add_action('prli-options-message','prlipro_check_twitter_creds');
add_action('prlipro-options-message','prlipro_check_twitter_creds');
add_action('prlipro_sidebar_top','prlipro_check_twitter_creds');

add_shortcode('prli_create_form', 'prlipro_public_create_form');

add_shortcode('prli_create_display', 'prlipro_public_link_display');
add_shortcode('prli_public_link_url', 'prlipro_public_link_display');
add_shortcode('prli_public_link_title', 'prlipro_public_link_title_display');
add_shortcode('prli_public_link_target_url', 'prlipro_public_link_target_url_display');
add_shortcode('prli_public_link_social_buttons', 'prlipro_public_link_social_buttons_display');

add_shortcode('post-pretty-link', 'prlipro_pretty_link');

if($prlipro_options->allow_public_link_creation and class_exists('WP_Widget'))
{
  require_once( PRLIPRO_PATH . '/prlipro-create-public-link-widget.php' );
  add_action('widgets_init', create_function('', 'return register_widget("PrliCreatePublicLinkWidget");'));
}

add_action('wp_head','prlipro_shorturl_autodiscover');

add_action('prli-prettybar-options', 'prlipro_prettybar_options');
add_filter('prli-validate-options', 'prlipro_validate_options');
add_action('prli-store-options', 'prlipro_store_options');
add_action('prli-options-head', 'prlipro_options_head');
add_filter('prli-display-attrib-link', 'prlipro_display_attrib_link');

add_action('prli-add-tools', 'prlipro_bookmarklet_generator');
add_action('init', 'prlipro_twitter_oath_endpoints');
