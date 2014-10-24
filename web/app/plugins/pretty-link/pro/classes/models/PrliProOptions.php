<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

class PrliProOptions
{
  public $pages_auto;
  public $posts_auto;
  public $pages_group;
  public $posts_group;

  public $twitter_handle;
  public $twitter_password;  // deprecated
  public $twitter_alt_creds; // deprecated
  public $twitter_oauth_tokens;
  public $twitter_hash_tags;
  public $twitter_posts_button;
  public $twitter_pages_button;
  public $twitter_posts_comments;
  public $twitter_pages_comments;
  public $twitter_auto_post_post;
  public $twitter_auto_post_page;

  public $twitter_badge_style;
  public $twitter_badge_placement;
  public $twitter_badge_hidden;
  public $twitter_badge_hidden_on_homepage;
  public $twitter_badge_show_in_feed;

  public $twitter_comments_header;
  public $twitter_comments_height;

  public $social_buttons;
  public $social_buttons_placement;
  public $social_buttons_show_in_feed;
  public $social_buttons_padding;
  public $social_posts_buttons;
  public $social_pages_buttons;

  public $keyword_replacement_is_on;
  public $keywords_per_page;
  public $keyword_links_per_page;
  public $keyword_links_open_new_window;
  public $keyword_links_nofollow;
  public $keyword_link_custom_css;
  public $keyword_link_hover_custom_css;
  public $set_keyword_thresholds;
  public $keyword_enable_content_cache; // DEPRECATED
  public $replace_urls_with_pretty_links;
  public $replace_keywords_in_comments;
  public $replace_keywords_in_feeds;

  public $use_prettylink_url;
  public $prettylink_url;
  
  public $min_role;

  public $allow_public_link_creation;
  public $use_public_link_display_page;
  public $public_link_display_page;

  public $prettybar_hide_attrib_link;
  public $prettybar_attrib_url;
  
  public $google_tracking;
  public $google_tracking_str;
  
  public $generate_qr_codes_str;
  public $generate_qr_codes;
  
  public $qr_code_links_str;
  public $qr_code_links;

  function __construct($options_array=array())
  {
    // Set values from array
    foreach($options_array as $key => $value)
      $this->{$key} = $value;
    
    $this->set_default_options();
  }

  function set_default_options()
  {
    if(!isset($this->pages_auto))
      $this->pages_auto = 0;

    if(!isset($this->posts_auto))
      $this->posts_auto = 0;

    if(!isset($this->pages_group))
      $this->pages_group = '';

    if(!isset($this->posts_group))
      $this->posts_group = '';

    if(!isset($this->twitter_handle))
      $this->twitter_handle = 'prettylink';

    if(isset( $this->twitter_oauth_tokens ))
    {
      // Deprecated!
      if(!isset($this->twitter_password))
        $this->twitter_password = '';
      else
        unset($this->twitter_password);

      // Deprecated
      if(!isset($this->twitter_alt_creds))
        $this->twitter_alt_creds = array();
      else
        unset($this->twitter_alt_creds);
    }
      
    if(!isset($this->twitter_oauth_tokens))
      $this->twitter_oauth_tokens = array();
      
    if(!isset($this->twitter_hash_tags))
      $this->twitter_hash_tags = '(via @prettylink)';  

    if(!isset($this->twitter_posts_button))
      $this->twitter_posts_button = 0;

    if(!isset($this->twitter_pages_button))
      $this->twitter_pages_button = 0;

    if(!isset($this->twitter_posts_comments))
      $this->twitter_posts_comments = 0;

    if(!isset($this->twitter_pages_comments))
      $this->twitter_pages_comments = 0;

    if(!isset($this->twitter_auto_post_post))
      $this->twitter_auto_post_post = 0;

    if(!isset($this->twitter_auto_post_page))
      $this->twitter_auto_post_page = 0;

    if(!isset($this->twitter_badge_style))
      $this->twitter_badge_style = 'yellow-and-blue-with-count';

    if(!isset($this->twitter_badge_placement))
      $this->twitter_badge_placement = 'top-left-with-wrap';
      
    if(!isset($this->twitter_badge_hidden))
      $this->twitter_badge_hidden = '';

    /*
    if(!isset($this->twitter_badge_hidden_on_homepage))
      $this->twitter_badge_hidden_on_homepage = 0;
    */

    $this->twitter_badge_hidden_on_homepage = 1;

    if(!isset($this->twitter_badge_show_in_feed))
      $this->twitter_badge_show_in_feed = 0;

    if(!isset($this->twitter_comments_header))
      $this->twitter_comments_header = '<h3>Twitter Comments</h3>';

    if(!isset($this->twitter_comments_height))
      $this->twitter_comments_height = '200';

    if(!isset($this->social_buttons))
      $this->social_buttons = array( 'delicious'   => 'on',
                                     'stumbleupon' => 'on',
                                     'digg'        => 'on',
                                     'twitter'     => 'on',
                                     'mixx'        => 'on',
                                     'technorati'  => 'on',
                                     'facebook'    => 'on',
                                     'newsvine'    => 'on',
                                     'reddit'      => 'on',
                                     'linkedin'    => 'on',
                                     'yahoo'       => 'on' );

    if(!isset($this->social_buttons_placement))
      $this->social_buttons_placement = 'bottom';

    if(!isset($this->social_buttons_show_in_feed))
      $this->social_buttons_show_in_feed = 0;

    if(!isset($this->social_buttons_padding))
      $this->social_buttons_padding = '10';

    if(!isset($this->social_posts_buttons))
      $this->social_posts_buttons = 0;

    if(!isset($this->social_pages_buttons))
      $this->social_pages_buttons = 0;

    if(!isset($this->keyword_replacement_is_on))
      $this->keyword_replacement_is_on = 1;

    if(!isset($this->keywords_per_page))
      $this->keywords_per_page = 3;

    if(!isset($this->keyword_links_per_page))
      $this->keyword_links_per_page = 2;

    if(!isset($this->keyword_links_open_new_window))
      $this->keyword_links_open_new_window = 0;

    if(!isset($this->keyword_links_nofollow))
      $this->keyword_links_nofollow = 0;

    if(!isset($this->keyword_link_custom_css))
      $this->keyword_link_custom_css = '';

    if(!isset($this->keyword_link_hover_custom_css))
      $this->keyword_link_hover_custom_css = '';

    if(!isset($this->set_keyword_thresholds))
      $this->set_keyword_thresholds = 0;

    // DEPRECATED
    $this->keyword_enable_content_cache = 0;

    if(!isset($this->replace_urls_with_pretty_links))
      $this->replace_urls_with_pretty_links = 0;
    if(!isset($this->replace_keywords_in_comments))
      $this->replace_keywords_in_comments = 0;
    if(!isset($this->replace_keywords_in_feeds))
      $this->replace_keywords_in_feeds = 0;

    if(!isset($this->use_prettylink_url))
      $this->use_prettylink_url = 0;

    if(!isset($this->prettylink_url))
      $this->prettylink_url = '';

    //add_users = ADMIN
    //delete_pages = EDITOR
    //publish_posts = AUTHOR
    //edit_posts = CONTRIBUTOR
    //read = SUBSCRIBER
    if(!isset($this->min_role))
      $this->min_role = 'add_users';

    if(!isset($this->allow_public_link_creation))
      $this->allow_public_link_creation = 0;

    if(!isset($this->use_public_link_display_page))
      $this->use_public_link_display_page = 0;

    if(!isset($this->public_link_display_page))
      $this->public_link_display_page = '';

    if(!isset($this->prettybar_hide_attrib_link))
      $this->prettybar_hide_attrib_link = 0;

    if(!isset($this->prettybar_attrib_url))
      $this->prettybar_attrib_url = '';

	$this->google_tracking_str = 'prlipro-google-tracking';
    if(!isset($this->google_tracking))
      $this->google_tracking = 0;

    $this->generate_qr_codes_str = 'prlipro-generate-qr-codes';
    if(!isset($this->generate_qr_codes))
      $this->generate_qr_codes = 0;

    $this->qr_code_links_str = 'prlipro-code-links';
    $this->qr_code_links = 0;
    /* TODO: We're going to just comment this out for now
    if(!isset($this->qr_code_links))
      $this->qr_code_links = 0;
    */
  }

  public function store() {
    $storage_array = (array)$this;
    update_option( 'prlipro_options', $storage_array );
  }

  public static function get_options() {
    $prlipro_options = get_option('prlipro_options');
    
    if($prlipro_options) {
      if(is_string($prlipro_options))
        $prlipro_options = unserialize($prlipro_options);
      
      if(is_object($prlipro_options) and is_a($prlipro_options,'PrliProOptions')) {
        $prlipro_options->set_default_options();
        $prlipro_options->store(); // store will convert this back into an array
      }
      else if(is_array($prlipro_options))
        $prlipro_options = new PrliProOptions($prlipro_options);
      else
        $prlipro_options = new PrliProOptions();
    }
    else
      $prlipro_options = new PrliProOptions();

    return $prlipro_options;
  }
}
