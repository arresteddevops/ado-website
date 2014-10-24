<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

require_once('prlipro-config.php');

if(is_user_logged_in() and current_user_can('level_10'))
{
  $filename = date("ymdHis",time()) . '_pretty_link_links.csv';
  header("Content-Type: text/x-csv");
  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")+2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
  header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
    
  if($links[0])
  {
    // print the header
    echo '"'.implode('","',array_keys($links[0]))."\"\n";
  }

  foreach($links as $link)
  {
    $first = true;
    foreach($link as $value)
    {
      if($first)
      {
        echo '"';
        $first = false;
      }
      else
        echo '","';

      echo preg_replace('/\"/', '""', stripslashes($value));
    }
    echo "\"\n";
  }
}
else
  header("Location: " . $prli_blogurl);
  