<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

class PrliProPostOptions
{
  public $send_auto_tweet;
  public $requested_slug;
  public $requested_twitter_message;
  
  public $hide_twitter_button;
  public $hide_social_buttons;
  public $hide_twitter_comments;
  public $disable_replacements;

  function __construct($options_array = array())
  {
    // Set values from array
    foreach($options_array as $key => $value)
      $this->{$key} = $value;
    
    $this->set_default_options();
  }

  function set_default_options()
  {
    if(!isset($this->send_auto_tweet))
      $this->send_auto_tweet = 1;

    if(!isset($this->requested_slug))
      $this->requested_slug = '';

    if(!isset($this->requested_twitter_message))
      $this->requested_twitter_message = '{%title%} {%url%} {%hash%}';
      
    if(!isset($this->hide_twitter_button))
      $this->hide_twitter_button = 0;

    if(!isset($this->hide_social_buttons))
      $this->hide_social_buttons = 0;

    if(!isset($this->hide_twitter_comments))
      $this->hide_twitter_comments = 0;

    if(!isset($this->disable_replacements))
      $this->disable_replacements = 0;
  }

  function validate()
  {
    global $prli_utils;
    $errors = array();

    if(!empty($this->requested_slug) and !$prli_utils->slugIsAvailable($this->requested_slug) )
      $errors[] = __("This pretty link slug is already taken, please choose a different one", 'pretty-link');

    return $errors;
  }

  // Just here as an alias for reverse compatibility
  function get_stored_object($post_id)
  {
    return PrliProOptions::get_options($post_id);
  }
  
  function store($post_id)
  {
    if(!empty($post_id) and $post_id) {
      $storage_array = (array)$this;
      PrliUtils::update_prli_post_meta($post_id, '_prlipro-post-options', $storage_array);
    }
  }

  public static function get_options($post_id) {
    if(!empty($post_id) and $post_id) {
      $prlipro_post_options = PrliUtils::get_prli_post_meta($post_id,"_prlipro-post-options",true);
      
      if($prlipro_post_options) {
        if(is_string($prlipro_post_options))
          $prlipro_post_options = unserialize($prlipro_post_options);
        
        if(is_a($prlipro_post_options,'PrliProPostOptions')) {
          $prlipro_post_options->set_default_options();
          $prlipro_post_options->store($post_id); // store will convert this back into an array
        }
        else if(is_array($prlipro_post_options))
          $prlipro_post_options = new PrliProPostOptions($prlipro_post_options);
        else
          $prlipro_post_options = new PrliProPostOptions();
      }
      else
        $prlipro_post_options = new PrliProPostOptions();
    }
    else
      $prlipro_post_options = new PrliProPostOptions();

    return $prlipro_post_options;
  }
}
