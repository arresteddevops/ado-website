<?php
require_once(dirname(__FILE__) . '/../../../../../../../wp-config.php');
require_once(dirname(__FILE__) . '/../../../prlipro-config.php');


if(is_user_logged_in() and $current_user->user_level >= 8)
{
  header("Content-Type: application/json");
  if(isset($_GET['sdate']) and isset($_GET['edate']) and isset($_GET['id']))
    echo trim($prli_report->setupClicksByLinkBarGraph($_GET['sdate'],$_GET['edate'],$_GET['id']))."\0";
  else
    echo "{}";
}
else
  header("Location: " . $prli_blogurl);
