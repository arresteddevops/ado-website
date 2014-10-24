<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

class PrliKeyword
{
  var $table_name;

  function PrliKeyword()
  {
    global $wpdb;
    $this->table_name = "{$wpdb->prefix}prli_keywords";
  }

  function create( $keyword, $link_id )
  {
    global $wpdb;

    $query_str = "INSERT INTO {$this->table_name} " . 
                 '(text,' .
                  'link_id,' .
                  'created_at) ' .
                  'VALUES ' .
                  '(%s,%d,NOW())';

    $query = $wpdb->prepare( $query_str,
                             $keyword,
                             $link_id );
                          
    $query_results = $wpdb->query($query);

    if($query_results)
      return $wpdb->insert_id;
    else
      return false;
  }

  function updateLinkKeywords($link_id,$keywords)
  {
    // TODO: In the future we'll want to record the post ids that the keywords
    // have been replaced on and we'll just delete the meta from those posts
    // For now we'll just delete the entire cache
    $this->deleteContentCache();

    // Get rid of the old keywords 
    $this->destroyByLinkId( $link_id );

    // Create the new keywords
    $keywords = explode(',',$keywords);
    foreach($keywords as $keyword)
      $this->create(trim($keyword), $link_id);
  }

  function destroy( $id )
  {
    global $wpdb;
    $query_str = "DELETE FROM {$this->table_name} WHERE id=%d";
    $query = $wpdb->prepare($query_str,$id);
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
    $query_str = "SELECT * FROM {$this->table_name} WHERE link_id=%d ORDER BY text";
    $query = $wpdb->prepare($query_str,$link_id);
    return $wpdb->get_results($query, $return_type);
  }

  function getTextByLinkId( $link_id )
  {
    $keywords = $this->getAllByLinkId( $link_id );

    $keywords_array = array();
    foreach($keywords as $keyword)
      $keywords_array[] = stripslashes(htmlspecialchars($keyword->text));

    return implode( ', ', $keywords_array );
  }

  function getAllUniqueKeywordsText()
  {
    global $wpdb;
    $query = "SELECT DISTINCT text FROM {$this->table_name}";
    return $wpdb->get_col($query, 0);
  }

  function getAll($where = '', $return_type = OBJECT)
  {
    global $wpdb, $prli_utils;
    $query_str = "SELECT * FROM {$this->table_name}" . $prli_utils->prepend_and_or_where(' WHERE', $where) . " ORDER BY text";
    return $wpdb->get_results($query_str, $return_type);
  }

  // Returns an array of links that have this keyword
  function getLinksByKeyword($keyword)
  {
    global $wpdb;
    $query_str = "SELECT link_id FROM {$this->table_name} WHERE text=%s";
    $query = $wpdb->prepare($query_str,$keyword);
    return $wpdb->get_col($query,0);
  }

  function getKeywordToLinksArray()
  {
    global $wpdb, $prli_link, $prli_keyword, $prli_blogurl;
    $struct = PrliUtils::get_permalink_pre_slug_uri();
    $query = "SELECT kw.text as keyword, li.name as title, " .
	                "CONCAT(%s,li.slug) AS url " .
	           "FROM {$prli_link->table_name} li " .
	           "JOIN {$prli_keyword->table_name} kw ON li.id=kw.link_id " .
	          "WHERE kw.text IN ( SELECT DISTINCT kw2.text " .
	                               "FROM {$prli_keyword->table_name} kw2 " .
	                               "WHERE kw2.text <> %s ) " .
	          "ORDER BY kw.text";
    $query = $wpdb->prepare( $query, $prli_blogurl . $struct, '');
    $keywords = $wpdb->get_results( $query);

    $links_array = array();
    
    foreach($keywords as $keyword)
    {
      if(isset($links_array[$keyword->keyword]))
        $links_array[$keyword->keyword][] = (object)array('url' => $keyword->url, 'title' => stripslashes($keyword->title));
      else
        $links_array[$keyword->keyword] = array((object)array('url' => $keyword->url, 'title' => stripslashes($keyword->title)));
    }
    
    return $links_array;
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
                   " ORDER BY text" . 
                   " LIMIT {$start_index},{$p_size}";
    $results = $wpdb->get_results($query, $return_type);
    return $results;
  }

  function deleteContentCache($id = 0)
  {
    global $wpdb;
    if($id > 0)
      PrliUtils::delete_prli_post_meta($id, '_prli-keyword-cached-content');
    else
    {
      $query = $wpdb->prepare("DELETE FROM {$wpdb->postmeta} WHERE meta_key=%s OR meta_key=%s", '_prli-keyword-cached-content', 'prli-keyword-cached-content');
      $wpdb->query($query);

        /*
      $allposts = get_posts('numberposts=0&post_type=post&post_status=');
      $allpages = get_posts('numberposts=0&post_type=page&post_status=');

      foreach( $allposts as $postinfo )
        delete_post_meta($postinfo->ID, '_prli-keyword-cached-content');

      foreach( $allpages as $pageinfo )
        delete_post_meta($pageinfo->ID, '_prli-keyword-cached-content');
        */
    }
  }
}
