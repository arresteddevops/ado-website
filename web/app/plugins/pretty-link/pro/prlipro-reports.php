<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

require_once 'prlipro-config.php';

$params = $prli_report->get_params_array();

//if(empty($params['action']))
//  require_once('classes/views/prli-reports/reports.php');
//else if($params['action'] == 'list')
if(empty($params['action']) or ($params['action'] == 'list'))
{
  $prli_message = __("Create a custom link report and analyze your data.", 'pretty-link');
  prli_display_reports_list($params, $prli_message);
}
else if($params['action'] == 'new')
{
  $links = $prli_link->getAll('',' ORDER BY group_name, li.name');
  $report_links = array();
  require_once 'classes/views/prli-reports/new.php';
}
else if($params['action'] == 'create')
{
  $errors = $prli_report->validate($_POST);
  if( count($errors) > 0 )
  {
    $links = $prli_link->getAll('',' ORDER BY group_name, li.name');
    $report_links = array();
    require_once 'classes/views/prli-reports/new.php';
  }
  else
  {
    $insert_id = $prli_report->create( $_POST );
    $prli_report->update_report_links($insert_id, array_keys($_POST['link']));
    $prli_message = __("Your Pretty Link Report was Successfully Created", 'pretty-link');
    prli_display_reports_list($params, $prli_message, '', 1);
  }
}
else if($params['action'] == 'edit')
{
  $record = $prli_report->getOne( $params['id'] );
  $id = $params['id'];
  $links = $prli_link->getAll('',' ORDER BY group_name, li.name');
  $report_links = $prli_report->get_report_links_array($id);
  require_once 'classes/views/prli-reports/edit.php';
}
else if($params['action'] == 'update')
{
  $errors = $prli_report->validate($_POST);
  $id = $_POST['id'];
  if( count($errors) > 0 )
  {
    $links = $prli_link->getAll('',' ORDER BY group_name, li.name');
    $report_links = $prli_report->get_report_links_array($id);
    require_once 'classes/views/prli-reports/edit.php';
  }
  else
  {
    $record = $prli_report->update( $id, $_POST );
    $prli_report->update_report_links($id, array_keys($_POST['link']));
    $prli_message = __("Your Pretty Link Report was Successfully Updated", 'pretty-link');
    prli_display_reports_list($params, $prli_message, '', 1);
  }
}
else if($params['action'] == 'destroy')
{
  $prli_report->destroy( $params['id'] );
  $prli_message = __("Your Pretty Link Report was Successfully Deleted", 'pretty-link');
  prli_display_reports_list($params, $prli_message, '', 1);
}
else if($params['action'] == 'display-custom-report')
{
  $id = $params['id'];
  
  $start_timestamp = $prli_utils->get_start_date($params);
  $end_timestamp   = $prli_utils->get_end_date($params);
  
  $start_timestamp = mktime(0, 0, 0, date('n', $start_timestamp), date('j', $start_timestamp), date('Y', $start_timestamp));
  $end_timestamp   = mktime(0, 0, 0, date('n', $end_timestamp),   date('j', $end_timestamp),   date('Y', $end_timestamp)  );
  
  $report = $prli_report->getOne($id);
  
  $links   = $prli_report->get_report_links_array($id);
  $labels  = $prli_report->get_labels_by_links($start_timestamp,$end_timestamp,$links);
  $hits    = $prli_report->get_clicks_by_links($start_timestamp,$end_timestamp,$links);
  $uniques = $prli_report->get_clicks_by_links($start_timestamp,$end_timestamp,$links,true);
  
  $top_hits    = $prli_utils->getTopValue($hits);
  $top_uniques = $prli_utils->getTopValue($uniques);
  
  if( !empty($report->goal_link_id) )
  {
    $goal_link = $prli_link->getOne($report->goal_link_id);
    $conversions = $prli_report->get_conversions_by_links($start_timestamp,$end_timestamp,$links,$report->goal_link_id);
    $conv_rates = array();
    for($i=0; $i<count($links); $i++)
      $conv_rates[] = (($hits[$i] > 0)?sprintf( "%0.2f", (float)($conversions[$i] / $hits[$i] * 100.0) ):'0.00');
    $top_conversions = $prli_utils->getTopValue(array_values($conversions));
    $top_conv_rate   = $prli_utils->getTopValue(array_values($conv_rates));
  }
  
  require_once PRLIPRO_PATH . '/classes/views/prli-reports/custom-report.php';
}
else if($params['action'] == 'display-split-test-report')
{
  $link_id = $params['id'];
  
  $goal_link_id = $prli_link_meta->get_link_meta($link_id, 'prli-split-test-goal-link', true);
  
  $link = $prli_link->getOne($link_id);
  
  $start_timestamp = $prli_utils->get_start_date($params);
  $end_timestamp   = $prli_utils->get_end_date($params);
  
  $start_timestamp = mktime(0, 0, 0, date('n', $start_timestamp), date('j', $start_timestamp), date('Y', $start_timestamp));
  $end_timestamp   = mktime(0, 0, 0, date('n', $end_timestamp),   date('j', $end_timestamp),   date('Y', $end_timestamp)  );
  
  $links   = $prli_report->get_split_report_links_array($link_id);
  $labels  = $links;
  $hits_array    = $prli_report->get_split_clicks($start_timestamp,$end_timestamp,$link_id);
  $uniques_array = $prli_report->get_split_clicks($start_timestamp,$end_timestamp,$link_id,true);

  $hits = array();
  $uniques = array();
  for($i=0;$i<count($links);$i++)
  {
    $hits[$i]    = ((is_array($hits_array) and isset($hits_array[$links[$i]]) and !empty($hits_array[$links[$i]]))?$hits_array[$links[$i]]:0);
    $uniques[$i] = ((is_array($uniques_array) and isset($uniques_array[$links[$i]]) and !empty($uniques_array[$links[$i]]))?$uniques_array[$links[$i]]:0);
  }

  $top_hits    = (($hits and is_array($hits))?$prli_utils->getTopValue($hits):0);
  $top_uniques = (($uniques and is_array($uniques))?$prli_utils->getTopValue($uniques):0);
  
  if( !empty($goal_link_id) and $goal_link_id )
  {
    $goal_link   = $prli_link->getOne($goal_link_id);
    $conversions_array = $prli_report->get_split_conversions($start_timestamp,$end_timestamp,$link_id,$goal_link_id);

    $conversions = array();
    for($i=0;$i<count($links);$i++)
      $conversions[$i] = ((is_array($conversions_array) and isset($conversions_array[$links[$i]]) and !empty($conversions_array[$links[$i]]))?$conversions_array[$links[$i]]:0);

    $conv_rates = array();
    for($i=0; $i<count($links); $i++)
      $conv_rates[] = (($hits[$i] > 0)?sprintf( "%0.2f", (float)($conversions[$i] / $hits[$i] * 100.0) ):'0.00');
    $top_conversions = $prli_utils->getTopValue(array_values($conversions));
    $top_conv_rate   = $prli_utils->getTopValue(array_values($conv_rates));
  }
  
  require_once PRLIPRO_PATH . '/classes/views/prli-reports/split-test-report.php';
}

// Helpers
function prli_display_reports_list($params, $prli_message, $page_params_ov = false, $current_page_ov = false)
{
  global $wpdb, $prli_utils, $prli_report, $page_size;

  $controller_file = 'pro/'.basename(__FILE__);

  $report_vars = prli_get_report_sort_vars($params);

  if($current_page_ov)
    $current_page = $current_page_ov;
  else
    $current_page = $params['paged'];

  $page_params = '&action=list';

  if($page_params_ov)
    $page_params .= $page_params_ov;
  else
    $page_params .= $report_vars['page_params'];

  $sort_str = $report_vars['sort_str'];
  $sdir_str = $report_vars['sdir_str'];
  $search_str = $report_vars['search_str'];

  $record_count = $prli_report->getRecordCount($report_vars['where_clause']);
  $page_count = $prli_report->getPageCount($page_size,$report_vars['where_clause']);
  $reports = $prli_report->getPage($current_page,$page_size,$report_vars['where_clause'],$report_vars['order_by']);
  $page_last_record = $prli_utils->getLastRecordNum($record_count,$current_page,$page_size);
  $page_first_record = $prli_utils->getFirstRecordNum($record_count,$current_page,$page_size);

  require_once 'classes/views/prli-reports/list.php';
}

function prli_get_report_sort_vars($params,$where_clause = '')
{
  $order_by = '';
  $page_params = '';

  // These will have to work with both get and post
  $sort_str = $params['sort'];
  $sdir_str = $params['sdir'];
  $search_str = $params['search'];

  // Insert search string
  if(!empty($search_str))
  {
    $search_params = explode(" ", $search_str);

    foreach($search_params as $search_param)
    {
      if(!empty($where_clause))
        $where_clause .= " AND";

      $where_clause .= " (name like '%$search_param%' OR goal_link_name like '%$search_param%' OR created_at like '%$search_param%')";
    }

    $page_params .="&search=$search_str";
  }

  // make sure page params stay correct
  if(!empty($sort_str))
    $page_params .="&sort=$sort_str";

  if(!empty($sdir_str))
    $page_params .= "&sdir=$sdir_str";

  // Add order by clause
  switch($sort_str)
  {
    case "name":
    case "goal_link_name":
    case "link_count":
      $order_by .= " ORDER BY $sort_str";
      break;
    default:
      $order_by .= " ORDER BY created_at";
  }

  // Toggle ascending / descending
  if((empty($sort_str) and empty($sdir_str)) or $sdir_str == 'desc')
  {
    $order_by .= ' DESC';
    $sdir_str = 'desc';
  }
  else
    $sdir_str = 'asc';

  return array('order_by' => $order_by,
               'sort_str' => $sort_str, 
               'sdir_str' => $sdir_str, 
               'search_str' => $search_str, 
               'where_clause' => $where_clause, 
               'page_params' => $page_params);
}
