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

include_once ("core/config/core.config.php");
include_once ("core/inc/functions.inc.php");

// SESSION HANDLER ----------------------------------------------------------------------------
// sets up all session and global vars 
session_name($core_config['php']['session_name']);
session_start();


// ERROR HANDLING ----------------------------------------------------------------------------
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


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('core/class/Db.class.php');
$am_core = new Database($core_config['db']);

if (!empty($_GET['key'])) {
	$query = "
		SELECT
		identity_id
		FROM " . $am_core->prefix . "_identity
		WHERE identity_email_key=" . $am_core->qstr($_GET['key']) . ""
	;
	
	$result = $am_core->Execute($query);
	
	if (isset($result[0]['identity_id'])) {
		$query = "
			UPDATE " . $am_core->prefix . "_identity
			SET identity_email_verified=1
			WHERE identity_id=" . $result[0]['identity_id']
		;
		
		$am_core->Execute($query);
	}
}

header('location: index.php?t=identity');
exit;

?>