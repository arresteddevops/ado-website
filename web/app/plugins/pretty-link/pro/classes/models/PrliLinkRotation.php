<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

class PrliLinkRotation
{
  var $table_name;
  var $cr_table_name;

  function PrliLinkRotation()
  {
    global $wpdb;
    $this->table_name    = "{$wpdb->prefix}prli_link_rotations";
    $this->cr_table_name = "{$wpdb->prefix}prli_clicks_rotations";
  }

  function create( $url, $weight, $r_index, $link_id )
  {
    global $wpdb;

    $query_str = "INSERT INTO {$this->table_name} " . 
                 '(url,' .
                  'weight,' .
                  'r_index,' .
                  'link_id,' .
                  'created_at) ' .
                  'VALUES ' .
                  '(%s,%d,%d,%d,NOW())';

    $query = $wpdb->prepare( $query_str,
                             $url,
                             $weight,
                             $r_index,
                             $link_id );
                          
    $query_results = $wpdb->query($query);

    if($query_results)
      return $wpdb->insert_id;
    else
      return false;
  }

  function update( $url, $weight, $r_index, $link_id )
  {
    global $wpdb;

    $query_str = "UPDATE {$this->table_name} SET " . 
                  'url=%s, ' .
                  'weight=%d ' .
                 'WHERE ' .
                  'link_id=%d AND ' .
                  'r_index=%d';

    $query = $wpdb->prepare( $query_str,
                             $url,
                             $weight,
                             $link_id,
                             $r_index );
                          
    $query_results = $wpdb->query($query);

    return $query_results;
  }

  function record_click( $click_id, $link_id, $url )
  {
    global $wpdb;

    $query_str = "INSERT INTO {$this->cr_table_name} " . 
                 '(click_id,' .
                  'link_id,' .
                  'url) ' .
                  'VALUES ' .
                  '(%d,%d,%s)';

    $query = $wpdb->prepare( $query_str,
                             $click_id,
                             $link_id,
                             $url );
                          
    $query_results = $wpdb->query($query);

    if($query_results)
      return $wpdb->insert_id;
    else
      return false;
  }

  function updateLinkRotations($link_id,$link_rotations,$link_weights)
  {
    $existing_rotations = $this->getAllByLinkId( $link_id );

    $max_count = ((count($existing_rotations) > count($link_rotations))?count($existing_rotations):count($link_rotations));
    for($i=0;$i<$max_count;$i++)
    {
      if(isset($existing_rotations[$i]) and isset($link_rotations[$i]))
      {
        if(empty($link_rotations[$i]) or preg_match("#^\s*$#",$link_rotations[$i]))
          $this->destroy($link_id,$i);
        else
          $this->update(trim($link_rotations[$i]), trim($link_weights[$i]), $i, $link_id);
      }
      else if(isset($link_rotations[$i]) and !preg_match("#^\s*$#",$link_rotations[$i]))
        $this->create(trim($link_rotations[$i]), trim($link_weights[$i]), $i, $link_id);
      else if(isset($existing_rotations[$i]))
        $this->destroy($link_id,$i);
    }
  }

  function destroy( $link_id, $r_index )
  {
    global $wpdb;
    $query_str = "DELETE FROM {$this->table_name} WHERE link_id=%d AND r_index=%d";
    $query = $wpdb->prepare($query_str,$link_id,$r_index);
    return $wpdb->query($query);
  }

  function destroyByLinkId( $link_id )
  {
    global $wpdb;
    $query_str = "DELETE FROM {$this->table_name} WHERE link_id=%d";
    $query = $wpdb->prepare($query_str,$link_id);
    return $wpdb->query($query);
  }

  function getOne( $id, $return_type = OBJECT )
  {
    global $wpdb;
    $query_str = "SELECT * FROM {$this->table_name} WHERE id=%d";
    $query = $wpdb->prepare($query_str,$id);
    return $wpdb->get_row($query, $return_type);
  }

  function getAllByLinkId( $link_id, $return_type = OBJECT )
  {
    global $wpdb;
    $query_str = "SELECT * FROM {$this->table_name} WHERE link_id=%d ORDER BY r_index";
    $query = $wpdb->prepare($query_str,$link_id);
    return $wpdb->get_results($query, $return_type);
  }

  function getAll($where = '', $return_type = OBJECT)
  {
    global $wpdb, $prli_utils;
    $query_str = "SELECT * FROM {$this->table_name}" . $prli_utils->prepend_and_or_where(' WHERE', $where) . " ORDER BY link_id,r_index";
    return $wpdb->get_results($query_str, $return_type);
  }

  function get_rotations($link_id)
  {
    global $wpdb;
    $query_str = "SELECT url FROM {$this->table_name} WHERE link_id=%d ORDER BY r_index";
    $query = $wpdb->prepare($query_str,$link_id);
    return $wpdb->get_col($query, 0);
  }

  function get_weights($link_id)
  {
    global $wpdb;
    $query_str = "SELECT weight FROM {$this->table_name} WHERE link_id=%d ORDER BY r_index";
    $query = $wpdb->prepare($query_str,$link_id);
    return $wpdb->get_col($query, 0);
  }

  function get_target_url($link_id)
  {
    global $prlipro_utils, $prli_link, $prli_link_meta;

    $link = $prli_link->getOne($link_id);

    $rotation_urls = $this->get_rotations($link_id);
    $rotation_urls[] = $link->url;

    $weights = $this->get_weights($link_id);
    $weights[] = $prli_link_meta->get_link_meta($link_id,'prli-target-url-weight',true);

    $index = $prlipro_utils->w_rand($weights);

    // Just double check that we aren't returning an empty URL ...
    // At the very least we can return the target url.
    $target_url = (empty($rotation_urls[$index])?$link->url:$rotation_urls[$index]);

    return $target_url;
  }

  function there_are_rotations_for_this_link($link_id)
  {
    global $wpdb;
    $query_str = "SELECT * FROM {$this->table_name} WHERE link_id=%d";
    $query = $wpdb->prepare($query_str,$link_id);
    $url_rotations = $wpdb->get_results($query);

    foreach($url_rotations as $rot)
    {
      if(!preg_match('#^/s*?#',$rot->url))
        return true; // short circuit when we find the first rotation
    }

    return false;
  }

  // Pagination Methods
  function getRecordCount($where="")
  {
    global $wpdb, $prli_utils;
    $query_str = "SELECT COUNT(*) FROM {$this->table_name}" . $prli_utils->prepend_and_or_where(' WHERE', $where);
    return $wpdb->get_var($query_str);
  }

  function getPageCount($p_size, $where="")
  {
    return ceil((int)$this->getRecordCount($where) / (int)$p_size);
  }

  function getPage($current_p, $p_size, $where = "", $return_type = OBJECT)
  {
    global $wpdb, $prli_utils;
    $end_index = $current_p * $p_size;
    $start_index = $end_index - $p_size;
    $query_str = "SELECT * FROM {$this->table_name}" . 
                   $prli_utils->prepend_and_or_where(' WHERE', $where) . 
                   " ORDER BY url" . 
                   " LIMIT {$start_index},{$p_size}";
    $results = $wpdb->get_results($query, $return_type);
    return $results;
  }
}
