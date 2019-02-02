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
		
		if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == $output_identity['owner_connection_id']) {
			$log_entry = array();
			$log_entry['identity_id'] = $output_identity['identity_id'];
			$log_entry['log_title'] = 'someone says something';
			$log_entry['log_body'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . "</a> says: " . $_POST['log_entry'];
			$log_entry['log_link'] = $_SESSION['openid_identity'];
			$log_entry['log_create_datetime'] = time();
	
			$table = $am_core->prefix . '_log';
	
			$am_core->insertDb($log_entry, $table);
		}
		
	}
	
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>