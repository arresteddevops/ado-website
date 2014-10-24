<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

require_once(PRLIPRO_MODELS_PATH.'/PrliProUtils.php');
require_once(PRLIPRO_MODELS_PATH.'/PrliProOptions.php');
require_once(PRLIPRO_MODELS_PATH.'/PrliProPostOptions.php');
require_once(PRLIPRO_MODELS_PATH.'/PrliTweet.php');
require_once(PRLIPRO_MODELS_PATH.'/PrliKeyword.php');
require_once(PRLIPRO_MODELS_PATH.'/PrliReport.php');
require_once(PRLIPRO_MODELS_PATH.'/PrliUrlReplacement.php');
require_once(PRLIPRO_MODELS_PATH.'/PrliLinkRotation.php');

global $prlipro_utils;
global $prli_tweet;
global $prli_keyword;
global $prli_report;
global $prli_url_replacement;
global $prli_link_rotation;

$prlipro_utils        = new PrliProUtils();
$prli_tweet           = new PrliTweet();
$prli_keyword         = new PrliKeyword();
$prli_report          = new PrliReport();
$prli_url_replacement = new PrliUrlReplacement();
$prli_link_rotation   = new PrliLinkRotation();

global $prlipro_options;
$prlipro_options = PrliProOptions::get_options();
