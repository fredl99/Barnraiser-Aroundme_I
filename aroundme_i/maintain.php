<?php

// -----------------------------------------------------------------------
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
// -----------------------------------------------------------------------

// CHECK INSTALLED
if (is_readable("installer.php")) {
	header("Location: installer.php");
	exit;
}


// MAIN INCLUDES
include ("core/config/core.config.php");
include ("core/inc/functions.inc.php");


// SESSION HANDLER -------------------------------------------------------
// sets up all session and global vars 
session_name($core_config['php']['session_name']);
session_start();


// ERROR HANDLING
// this is accessed and updated with all errors thoughtout this build
// processing regularly checks if empty before continuing
$GLOBALS['am_error_log'] = array();


if (isset($_REQUEST['disconnect'])) {
	session_unset();
	session_destroy();
	session_write_close();
	header("Location: index.php");
	exit;
}


// SETUP DATABASE ------------------------------------------------------
require_once('core/class/Db.class.php');
$am_core = new Database($core_config['db']);


// SETUP IDENTITY --------------------------------------------
require_once('core/class/Identity.class.php');
$identity = new Identity($am_core);


// SETUP TEMPLATE -------------------------------------------
define("AM_TEMPLATE_PATH", "core/template/");
require_once('core/class/Template.class.php');
$tpl = new Template();


// SETUP LANGUAGE --------------------------------------------
if (!isset($core_config['language']['default'])) {
	die ('Default language pack not set correctly or cannot be read.');
}

define("AM_DEFAULT_LANGUAGE_CODE", $core_config['language']['default']);
define("AM_DEFAULT_LANGUAGE_PATH", "core/language/" . AM_DEFAULT_LANGUAGE_CODE . "/");
setlocale(LC_ALL, $core_config['language']['pack'][AM_DEFAULT_LANGUAGE_CODE]);


$lang = array();

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php');
}
else {
	die ('Default language pack not set correctly.');
}


if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'maintain.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'maintain.lang.php');
}


if (isset($_POST['connect'])) {
	if (isset($_POST['username'], $_POST['password'])) {
		if ($_POST['username'] == $core_config['maintainer']['username']) {
			if (md5($_POST['password']) == $core_config['maintainer']['password']) {
				$_SESSION['am_maintainer'] = 1;
			}
		}
	}
}

// SETUP WEBSPACE --------------------------------------------
require_once('core/class/Webspace.class.php');
$ws = new Webspace($am_core);


if (isset($_POST['save_patterns'])) {
	
	backupConfig();
	
	$core_config['am']['domain_preg_pattern'] = stripslashes($_POST['domain_preg_pattern']);
	$core_config['am']['domain_replace_pattern'] = stripslashes($_POST['domain_replace_pattern']);

	writeToConfig('$core_config[\'am\'][\'domain_preg_pattern\']', $core_config['am']['domain_preg_pattern']);
	writeToConfig('$core_config[\'am\'][\'domain_replace_pattern\']', $core_config['am']['domain_replace_pattern']);
	
	$_REQUEST['v'] = "config";
	
}
elseif (isset($_POST['save_email'])) {

	backupConfig();

	$core_config['mail']['host'] = 	$_POST['email_host'];
	$core_config['mail']['email_address'] = $_POST['email_address'];
	$core_config['mail']['from_name'] = $_POST['email_from_name'];

	writeToConfig('$core_config[\'mail\'][\'host\']', $core_config['mail']['host']);
	writeToConfig('$core_config[\'mail\'][\'email_address\']', $core_config['mail']['email_address']);
	writeToConfig('$core_config[\'mail\'][\'from_name\']', $core_config['mail']['from_name']);

	if (!empty($_POST['smtp_user'])) {
		$core_config['mail']['smtp']['username'] = $_POST['smtp_user'];
		$core_config['mail']['smtp']['password'] = $_POST['smtp_password'];

		writeToConfig('$core_config[\'mail\'][\'smtp\'][\'username\']', $core_config['mail']['smtp']['username']);
		writeToConfig('$core_config[\'mail\'][\'smtp\'][\'password\']', $core_config['mail']['smtp']['password']);
	}


	$_REQUEST['v'] = "config";
}
elseif (isset($_POST['save_config'])) {

	backupConfig();

	$core_config['file']['default_allocation'] = $_POST['file_default_allocation'];

	writeToConfig('$core_config[\'file\'][\'default_allocation\']', $core_config['file']['default_allocation']);

	$core_config['display']['max_list_rows'] = $_POST['display_max_list_rows'];

	writeToConfig('$core_config[\'display\'][\'max_list_rows\']', $core_config['display']['max_list_rows']);

	if ($_POST['identity_creation_type'] == 2) {
		writeToConfig('$core_config[\'am\'][\'identity_creation_type\']', 2);
	}
	elseif ($_POST['identity_creation_type'] == 1) {
		writeToConfig('$core_config[\'am\'][\'identity_creation_type\']', 1);
	}
	else {
		writeToConfig('$core_config[\'am\'][\'identity_creation_type\']', 0);
	}

	$core_config['am']['reserved_identity_names'] = $_POST['reserved_identity_names'];

	writeToConfig('$core_config[\'am\'][\'reserved_identity_names\']', $core_config['am']['reserved_identity_names']);

	$_REQUEST['v'] = "config";
}
elseif (isset($_POST['update_identity'])) {

	if (!empty($_POST['status_id'])) {
		$query = "UPDATE " . $am_core->prefix . "_identity SET status_id=" . $_POST['status_id'] . " WHERE identity_id=" . $_POST['identity_id'];

		$am_core->Execute($query);
	}
	
	if (!empty($_POST['webspace_allocation'])) {
		$query = "UPDATE " . $am_core->prefix . "_webspace SET webspace_allocation=" . $_POST['webspace_allocation'] . " WHERE identity_id=" . $_POST['identity_id'];

		$am_core->Execute($query);
	}
}


if (isset($_SESSION['am_maintainer']) && $_SESSION['am_maintainer'] == 1) {
	if (!empty($_REQUEST['identity_id'])) {
		$query = "
			SELECT i.identity_id, i.identity_name, i.owner_connection_id, i.status_id, 
			i.language_code, UNIX_TIMESTAMP(i.identity_create_datetime) AS identity_create_datetime,
			w.webspace_id, w.webspace_allocation 
			FROM " . $am_core->prefix . "_identity i
			LEFT JOIN " . $am_core->prefix . "_webspace w ON i.identity_id=w.identity_id 
			WHERE 
			i.identity_id=" . $_REQUEST['identity_id']
		;
	
		$result = $am_core->Execute($query);
	
		if (isset($result[0])) {
			$result[0]['attributes'] = $identity->selConnectionAttributes($result[0]['owner_connection_id']);
			$result[0]['identity_url'] = str_replace('REPLACE', $result[0]['identity_name'], $core_config['am']['domain_replace_pattern']);
			
			$tpl->set('maintain_identity', $result[0]);
		}

		$_REQUEST['v'] = "list";
	}
	elseif (isset($_REQUEST['v']) && $_REQUEST['v'] == "config") {
		$tpl->set('domain_preg_pattern', $core_config['am']['domain_preg_pattern']);
		$tpl->set('domain_replace_pattern', $core_config['am']['domain_replace_pattern']);
	}
	elseif (isset($_REQUEST['v']) && $_REQUEST['v'] == "list") {
		
		$query = "
			SELECT i.identity_id, i.identity_name, i.owner_connection_id, i.status_id, 
			i.language_code, UNIX_TIMESTAMP(i.identity_create_datetime) AS identity_create_datetime,
			w.webspace_id, w.webspace_allocation 
			FROM " . $am_core->prefix . "_identity i
			LEFT JOIN " . $am_core->prefix . "_webspace w ON i.identity_id=w.identity_id 
			ORDER BY i.identity_name"
		;
		
		$result = $am_core->Execute($query);
		
		if (!empty($result)) {
			foreach($result as $key => $i):
				$result[$key]['attributes'] = $identity->selConnectionAttributes($i['owner_connection_id']);
				$result[$key]['identity_url'] = str_replace('REPLACE', $i['identity_name'], $core_config['am']['domain_replace_pattern']);
			endforeach;
			
			$tpl->set('identities', $result);
		}
	}
	else { // splash page

		// Gather statistics
		$query = "SELECT status_id, count(identity_id) as total FROM " . $am_core->prefix . "_identity i GROUP BY status_id";

		$result = $am_core->Execute($query);

		if (isset($result)) {
			$tpl->set('statistics', $result);
		}
	}
}
else {
// 	session_unset();
// 	session_destroy();
// 	session_write_close();
}

$tpl->lang = $lang;
$tpl->set('lang', $lang);
$tpl->set('core_config', $core_config);

echo $tpl->fetch(AM_TEMPLATE_PATH . 'maintain.tpl.php');

function writeToConfig($where, $what) {
	$config = file('core/config/core.config.php');
	foreach($config as $key => $val) {
		if (strstr($val, $where)) {
			$config[$key] = $where . ' = "' . $what . "\";\n";
			file_put_contents('core/config/core.config.php', implode($config));
			break;
		}
	}
}

function backupConfig() {

	$name = "~core.config_" . time() . ".php";

	$config = file_get_contents('core/config/core.config.php');

	file_put_contents('core/config/' . $name , $config);
}

?>