<?php
/*
Plugin Name: Pretty Link Pro
Plugin URI: http://blairwilliams.com/pretty-link
Description: Shrink, track and share any URL on the Internet from your WordPress website!
Version: 1.6.5
Author: Caseproof
Author URI: http://caseproof.com
Text Domain: pretty-link
Copyright: 2004-2012, Caseproof, LLC

GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
if(!defined('ABSPATH'))
  die('You are not allowed to call this page directly.');

require_once('prli-config.php');
require_once(PRLI_MODELS_PATH . '/models.inc.php');
require_once('prli-api.php'); // load api methods
require_once('prli-xmlrpc.php'); // load xml-rpc api methods

require_once(PRLI_PATH.'/prli-main.php');

// Provide Back End Hooks to the Pro version of Pretty Link
if($prli_update->pro_is_installed_and_authorized())
  require_once(PRLI_PATH.'/pro/pretty-link-pro.php');

// i18n
add_action('init', 'prli_load_textdomain');
function prli_load_textdomain()
{
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain('pretty-link', false, $plugin_dir.'/i18n/');
}
