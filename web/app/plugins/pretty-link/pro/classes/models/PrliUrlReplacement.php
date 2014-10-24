<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

class PrliUrlReplacement
{
  function getURLToLinksArray()
  {
    global $wpdb, $prli_blogurl, $prli_link, $prli_link_meta;
    
    $struct = PrliUtils::get_permalink_pre_slug_uri();
    $query = "SELECT plm.meta_value as replacement_url, CONCAT(%s,li.slug) AS url " .
	           "FROM {$prli_link->table_name} li " .
	           "JOIN {$prli_link_meta->table_name} plm " .
	             "ON li.id = plm.link_id " .
	          "WHERE plm.meta_key='prli-url-replacements' " .
	            "AND plm.meta_value IN ( SELECT DISTINCT plm2.meta_value " .
	                                      "FROM {$prli_link_meta->table_name} plm2 " .
	                                     "WHERE meta_key='prli-url-replacements' " .
	                                       "AND plm2.meta_value <> %s ) " .
	       "ORDER BY plm.meta_value";
    
    $query = $wpdb->prepare( $query, $prli_blogurl . $struct, '');
    $replacement_urls = $wpdb->get_results( $query);

    $links_array = array();
    
    foreach($replacement_urls as $replacement_url)
    {
	  if(isset($links_array[$replacement_url->replacement_url]))
        $links_array[$replacement_url->replacement_url][] = $replacement_url->url;
      else
        $links_array[$replacement_url->replacement_url] = array($replacement_url->url);
    }
    
    return $links_array;
  }
}
