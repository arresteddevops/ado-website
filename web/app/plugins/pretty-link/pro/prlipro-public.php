<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

function prlipro_display_public_form($label = "Enter a URL: ", $button = "Shrink", $redirect_type = "-1", $track = "-1", $group = "-1")
{
  $formhtml = '';

  require_once(PRLIPRO_VIEWS_PATH . '/prlipro-public/form.php');

  return $formhtml;
}
