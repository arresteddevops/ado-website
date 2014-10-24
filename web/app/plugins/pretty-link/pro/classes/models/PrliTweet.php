<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

require_once(PRLIPRO_INCLUDES_PATH . '/php/abraham-twitteroauth/config.php');
require_once(PRLIPRO_INCLUDES_PATH . '/php/abraham-twitteroauth/PrliTwitterOAuth.php');
class PrliTweet
{
  var $table_name;

  function PrliTweet()
  {
    global $wpdb;
    $this->table_name = "{$wpdb->prefix}prli_tweets";
  }

  function create( $values )
  {
    global $wpdb;

    $query_str = "INSERT INTO {$this->table_name} " . 
                 '(twid,' .
                  'tw_text,' .
                  'tw_to_user_id,' .
                  'tw_from_user,' .
                  'tw_from_user_id,' .
                  'tw_iso_language_code,' .
                  'tw_source,' .
                  'tw_profile_image_url,' .
                  'tw_created_at,' .
                  'link_id,' .
                  'created_at) ' .
                  'VALUES ' .
                  '(%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,NOW())';

    $query = $wpdb->prepare( $query_str,
                             $values['twid'],
                             $values['tw_text'],
                             $values['tw_to_user_id'],
                             $values['tw_from_user'],
                             $values['tw_from_user_id'],
                             $values['tw_iso_language_code'],
                             $values['tw_source'],
                             $values['tw_profile_image_url'],
                             $values['tw_created_at'],
                             $values['link_id']);
                          
    $query_results = $wpdb->query($query);

    if($query_results)
      return $wpdb->insert_id;
    else
      return false;
  }

  function destroy( $id )
  {
    global $wpdb;
    $query_str = "DELETE FROM {$this->table_name} WHERE id=%d";
    $query = $wpdb->prepare($query_str,$id);
    return $wpdb->query($query);
  }

  function getOne( $id )
  {
    global $wpdb, $prli_click;
    $query_str = "SELECT * FROM {$this->table_name} WHERE id=%d LIMIT 1";
    $query = $wpdb->prepare($query_str,$id);
    return $wpdb->get_row($query);
  }

  function getOneFromTwid( $twid )
  {
    global $wpdb, $prli_click;
    $query_str = "SELECT * FROM {$this->table_name} WHERE twid=%s LIMIT 1";
    $query = $wpdb->prepare($query_str,$twid);
    return $wpdb->get_row($query);
  }

  // Gets latest tweet for this link
  function getLatestTweet( $pretty_link_id )
  {
    global $wpdb, $prli_click;
    $query_str = "SELECT * FROM {$this->table_name} WHERE link_id=%d ORDER BY twid DESC LIMIT 1";
    $query = $wpdb->prepare($query_str,$pretty_link_id);
    return $wpdb->get_row($query);
  }

  function getAll($where = '', $return_type = OBJECT)
  {
    global $wpdb, $prli_utils;
    $query_str = "SELECT * FROM {$this->table_name}" . $prli_utils->prepend_and_or_where(' WHERE', $where) . " ORDER BY tw_created_at DESC";
    return $wpdb->get_results($query_str, $return_type);
  }

  function get_tweet_record_count($pretty_link_id)
  {
    global $wpdb, $prli_utils, $prli_link_meta;

    $pretty_link_url = prli_get_pretty_link_url($pretty_link_id);

    // Gotta add the "tw_text like" to the where clause 
    // because Twitter sometimes returns additional, bizzare tweets
    $query_str = "SELECT COUNT(DISTINCT twid) FROM {$this->table_name} WHERE link_id=%d AND ( tw_text like %s )";
    $query = $wpdb->prepare( $query_str, $pretty_link_id, "%{$pretty_link_url}%" );

    return $wpdb->get_var($query);
  }
  
  function get_tweets($pretty_link_id)
  {
    global $wpdb, $prli_utils, $prli_link_meta;

    $pretty_link_url = prli_get_pretty_link_url($pretty_link_id);

    // Gotta add the "tw_text like" to the where clause 
    // because Twitter sometimes returns additional, bizzare tweets
    $query_str = "SELECT * ".
                 "FROM {$this->table_name} ".
                 "WHERE link_id=%d AND ( tw_text like %s ) ".
                 "GROUP BY twid ORDER BY twid DESC";
    $query = $wpdb->prepare( $query_str, $pretty_link_id, "%{$pretty_link_url}%" );

    return $wpdb->get_results($query);
  }

  // Pagination Methods
  function getRecordCount($where="")
  {
    global $wpdb, $prli_utils;
    $query = "SELECT COUNT(*) FROM {$this->table_name}" . $prli_utils->prepend_and_or_where(' WHERE', $where);
    return $wpdb->get_var($query);
  }

  function getPageCount($p_size, $where="")
  {
    return ceil((int)$this->getRecordCount($where) / (int)$p_size);
  }

  function getPage($current_p, $p_size, $where = "", $return_type = OBJECT)
  {
    global $wpdb, $prli_click, $prli_utils, $prli_group;
    $end_index = $current_p * $p_size;
    $start_index = $end_index - $p_size;
    $query_str = "SELECT * FROM {$this->table_name}" . 
                   $prli_utils->prepend_and_or_where(' WHERE', $where) . 
                   " ORDER BY tw_created_at DESC" . 
                   " LIMIT {$start_index},{$p_size}";
    $results = $wpdb->get_results($query, $return_type);
    return $results;
  }
}
