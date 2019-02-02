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

include ("../../core/config/core.config.php");
include ("../../core/inc/functions.inc.php");


// START SESSION
session_name($core_config['php']['session_name']);
session_start();


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('../../core/class/Db.class.php');
$am_core = new Database($core_config['db']);


// SETUP IDENTITY --------------------------------------------
require_once('../../core/class/Identity.class.php');
$identity = new Identity($am_core);


// OBTAIN IDENTITY ACCOUNT NAME
$identity->identity_name = $identity->getIdentityName($core_config['am']['domain_preg_pattern']);

if (!empty($identity->identity_name)) {
	define('AM_IDENTITY_NAME', $identity->identity_name);
	
	$output_identity = $identity->selIdentity();

	if (!empty($output_identity)) {

		// INSERT GUESTBOOK ENTRY
		$rec = array();
		$rec['connection_id'] = $_SESSION['connection_id'];
		$rec['guestbook_body'] = $am_core->am_parse($_POST['guestbook_body']);
		$rec['identity_id'] = $output_identity['identity_id'];
		$rec['guestbook_create_datetime'] = time();
	
		$table = $am_core->prefix . "_plugin_guestbook";
		
		$am_core->insertDb($rec, $table);
		
		// Append log
		$log_entry = array();
		$log_entry['identity_id'] = $output_identity['identity_id'];
		$log_entry['title'] = 'guestbook entry added';
		$log_entry['body'] = '<a href="index.php?t=network&amp;connection_id' . $_SESSION['connection_id'] . '">' . $_SESSION['openid_nickname'] . '</a> added a <a href="index.php?wp=' . $_REQUEST['wp'] . '">guestbook entry</a>.';
		$log_entry['link'] = "index.php?wp=" . $_REQUEST['wp'];
		$identity->appendLog($log_entry);
	
		session_write_close();
	}
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>