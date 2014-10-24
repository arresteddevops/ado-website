<?php
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

$prlipro_url = PRLIPRO_URL;
$referral_url = preg_replace('#\?.*$#', '', $_SERVER['REQUEST_URI']);
$nonce = wp_nonce_field('update-options');

$target_url = (isset($_GET['url']))?$_GET['url']:'';

$errorhtml = '';
if(isset($_GET['errors']))
{
  $errors = unserialize(stripslashes($_GET['errors']));
  
  if( is_array($errors) and count($errors) > 0 )

$errorhtml = <<<ERRORHTML
<div class="error">
  <ul>
ERRORHTML;

foreach( $errors as $error )
  $errorhtml .= "<li><strong>".__('ERROR', 'pretty-link')."</strong>: $error</li>";
  
$errorhtml .= <<<ERRORHTML
  </ul>
</div>
ERRORHTML;
}

$formhtml = <<<FORMHTML
<div id="prli_create_public_link">
<form name="prli_public_form" class="prli_public_form" method="post" action="{$prlipro_url}/prlipro-create-public-link.php">
<input type="hidden" name="referral-url" value="{$referral_url}"/>
<input type="hidden" name="redirect_type" value="{$redirect_type}"/>
<input type="hidden" name="track" value="{$track}"/>
<input type="hidden" name="group" value="{$group}"/>
{$nonce}

{$errorhtml}

<p class="prli_create_link_fields">
<span>{$label}</span>
<input type="text" name="url" value="{$target_url}" />&nbsp;
FORMHTML;

if(!empty($button)) {
  $formhtml .= "<input type=\"submit\" name=\"Submit\" value=\"{$button}\" />";
}

$formhtml .= <<<FORMHTML
</p>
</form>
</div>
FORMHTML;
// you need a line under FORMHTML for it to work correctly
