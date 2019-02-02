<?php

// ---------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// --------------------------------------------------------------------

include ("core/config/core.config.php");
include ("core/inc/functions.inc.php");

session_name($core_config['php']['session_name']);
session_start();


// ERROR HANDLING ----------------------------------------------------------------------------
// this is accessed and updated with all errors thoughtout this build
// processing regularly checks if empty before continuing
$GLOBALS['am_error_log'] = array();

define("AM_TEMPLATE_PATH", "core/template/");

// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('core/class/Db.class.php');
$am_core = new Database($core_config['db']);

require_once('core/class/OpenidServer.class.php');
require_once('core/class/Template.class.php');
$tpl = new Template(); // outer template
$body = new Template(); // inner template


// SETUP LANGUAGE --------------------------------------------
if (!isset($core_config['language']['default'])) {
	die ('Default language pack not set correctly.');
}

// we check to see if the webspace language is not the same as the default
define("AM_DEFAULT_LANGUAGE_CODE", $core_config['language']['default']);
define("AM_DEFAULT_LANGUAGE_PATH", "core/language/" . AM_DEFAULT_LANGUAGE_CODE . "/");

// set locale
setlocale(LC_ALL, $core_config['language']['pack'][AM_DEFAULT_LANGUAGE_CODE]);


$lang = array();

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php');
}
else {
	die ('Default language pack not set correctly or cannot be read.');
}

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'core.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'core.lang.php');
}

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'identity_field_options.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'identity_field_options.lang.php');
}
$tpl->lang = $lang;
$body->lang = $lang;

// SETUP IDENTITY --------------------------------------------
require_once('core/class/Identity.class.php');
$identity = new Identity($am_core);

// OBTAIN IDENTITY ACCOUNT NAME
$identity->identity_name = $identity->getIdentityName($core_config['am']['domain_preg_pattern']);

if (!empty($identity->identity_name)) {
	define('AM_IDENTITY_NAME', $identity->identity_name);
	
	$body->set('identity_name', $identity->identity_name);
	
	$output_identity = $identity->selIdentity();
	define('AM_OWNER_CONNECTION_ID', $output_identity['owner_connection_id']);
	define('AM_IDENTITY_ID', $output_identity['identity_id']);
	
	$query = "
		SELECT connection_id, attribute_name, 
		attribute_value, level_id
		FROM " . $am_core->prefix . "_connection_attribute
		INNER JOIN " . $am_core->prefix . "_identity
		ON owner_connection_id=connection_id
		WHERE identity_id=" . AM_IDENTITY_ID
	;
	
	$result = $am_core->Execute($query);
	
	if (!empty($result)) {
		$body->set('identity_attribute', $result);
	}
}

// SETUP WEBSPACE --------------------------------------------
require_once('core/class/Webspace.class.php');
$ws = new Webspace($am_core);

$template = 'login.tpl.php';
if (isset($_POST['login']) || isset($_POST['trust'])) {

	$server = new OpenidServer($core_config);

	// we enforce trust screen
	if (!empty($_POST['reset_trust']) && !empty($_REQUEST['openid_trust_root'])) {
		$query = "
			UPDATE " . $am_core->prefix . "_site
			SET site_trusted=0
			WHERE identity_id=" . AM_IDENTITY_ID . " AND 
			site_realm=" . $am_core->qstr($server->normalize($_REQUEST['openid_trust_root'])) . ""
		;
		$am_core->Execute ($query);
	}
	
	if (!empty($_POST['save_identity'])) {
		// this is a temporary solution.
		
		$query = "
			SELECT *
			FROM " . $am_core->prefix . "_connection_attribute
			WHERE connection_id=" . AM_OWNER_CONNECTION_ID
		;
		
		$result = $am_core->Execute($query);
		
		if (!empty($result)) {
			foreach($_POST as $key => $value): 
				if (isset($core_config['sreg2ax'][$key])) {
					foreach($result as $rkey => $rvalue):
						if ($core_config['sreg2ax'][$key] == $rvalue['attribute_name']) {
							// update
							$query = "
								UPDATE " . $am_core->prefix . "_connection_attribute
								SET attribute_value=" . $am_core->qstr($value) . "
								WHERE attribute_name=" . $am_core->qstr($rvalue['attribute_name']) . "
								AND connection_id=" . AM_OWNER_CONNECTION_ID
							;

							$am_core->Execute($query);
							
							$updated = 1;
						}
					endforeach;
					
					if (!isset($updated)) { 
						// insert
						$rec = array();
						$table = $am_core->prefix . "_connection_attribute";
						$rec['connection_id'] = AM_OWNER_CONNECTION_ID;
						$rec['attribute_name'] = $core_config['sreg2ax'][$key];
						$rec['attribute_value'] = $value;
						$rec['level_id'] = 0;
						
						$am_core->insertDb($rec, $table);
					}
					unset($updated);
				}
			endforeach;
		}
	}

	$server->checkid_setup(1);

	$template = isset($server->login_ok) ? 'trust.tpl.php' : 'login.tpl.php';
}

if (isset($_POST['openid_mode'])) {
	$openid_mode = $_POST['openid_mode'];
}
elseif (isset($_GET['openid_mode']) && !isset($_POST['login'])) {
	$openid_mode = $_GET['openid_mode'];
}

require_once('core/class/OpenidServer.class.php');
if (isset($openid_mode) && !isset($_POST['login']) && !isset($_POST['trust'])) {

	$server = new OpenidServer($core_config);

	switch($openid_mode) {
		case 'associate':
			$server->associate();
		break;
		case 'checkid_setup':
			$server->checkid_setup();
			$template = isset($server->login_ok) ? 'trust.tpl.php' : 'login.tpl.php';
		break;
		case 'check_authentication':
			$server->check_authentication();
		break;
		case 'checkid_immediate':
			$server->checkid_immediate();
		break;
		default:
	}
}


$openid_trusted_root = "";
if (isset($_GET['openid_trust_root'])) {
	$openid_trusted_root = $_GET['openid_trust_root'];
}
elseif (isset($_GET['openid_return_to'])) {
	$openid_trusted_root = $_GET['openid_return_to'];
}

// we need to use curl instead of file_get_contents because of https://pibb.com
$openid_trusted_root_title = $server->_send(array(), 'POST', $openid_trusted_root);

if (isset($openid_trusted_root_title)) {
	if (preg_match("/<title>(.*?)<\/title>/s", $openid_trusted_root_title, $matches)) {
		$openid_trusted_root_title = $matches[1];
	}
	else {
		$openid_trusted_root_title = 'no title';
	}
}

if (isset($output_webspace)) {
	// GET STYLESHEET ----------------------------------------------------------------------
	$query = "
		SELECT stylesheet_id, stylesheet_body, stylesheet_name
		FROM " . $am_core->prefix . "_stylesheet
		WHERE webspace_id=" . $output_identity['webspace_id']
	;
	
	$result = $am_core->Execute($query);
}

if (!empty($result[0])) {
	$output_style = $result[0];
}
else {
	$output_webspace['webspace_css'] = "";
}

$body->set('config_openid_extension', $core_config['openid_extension']);
$body->set('config_identity_attributes', $core_config['identity_attribute']);
$body->set('trusted_root', $openid_trusted_root);
$body->set('trusted_root_title', $openid_trusted_root_title);

$output_openid['server'] = 'http://' . $_SERVER['SERVER_NAME'] . '/op.php';
$output_openid['delegate'] = 'http://' . $_SERVER['SERVER_NAME'] . '/op.php';


$tpl->set('openid', $output_openid);

if (isset($output_webspace)) {
	$tpl->set('webspace', $output_webspace);
}
$tpl->set('lang', $lang);
$body->set('lang', $lang);
$body->set('sreg2ax', $core_config['sreg2ax']);
$tpl->set('openid', $output_openid);

$tpl->set('content', $body->fetch(AM_TEMPLATE_PATH . $template));
echo $tpl->fetch(AM_TEMPLATE_PATH . 'wrapper.tpl.php');
?>