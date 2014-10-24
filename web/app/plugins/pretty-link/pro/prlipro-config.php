<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

define('PRLIPRO_PATH',PRLI_PATH.'/pro');
define('PRLIPRO_MODELS_PATH',PRLIPRO_PATH.'/classes/models');
define('PRLIPRO_VIEWS_PATH',PRLIPRO_PATH.'/classes/views');
define('PRLIPRO_CSS_PATH',PRLIPRO_PATH.'/css');
define('PRLIPRO_IMAGES_PATH',PRLIPRO_PATH.'/images');
define('PRLIPRO_JS_PATH',PRLIPRO_PATH.'/js');
define('PRLIPRO_INCLUDES_PATH',PRLIPRO_PATH.'/includes');

define('PRLIPRO_URL',PRLI_URL.'/pro');
define('PRLIPRO_MODELS_URL',PRLIPRO_URL.'/classes/models');
define('PRLIPRO_VIEWS_URL',PRLIPRO_URL.'/classes/views');
define('PRLIPRO_CSS_URL',PRLIPRO_URL.'/css');
define('PRLIPRO_IMAGES_URL',PRLIPRO_URL.'/images');
define('PRLIPRO_JS_URL',PRLIPRO_URL.'/js');
define('PRLIPRO_INCLUDES_URL',PRLIPRO_URL.'/includes');

require_once(PRLIPRO_MODELS_PATH . '/models.inc.php');

// Modify for blogurl customization
$prli_blogurl = (($prlipro_options->use_prettylink_url)?$prlipro_options->prettylink_url:$prli_blogurl);
