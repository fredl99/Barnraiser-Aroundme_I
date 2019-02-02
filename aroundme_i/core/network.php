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


if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'core.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'core.lang.php');
}

if (defined('AM_LANGUAGE_CODE') && is_readable(AM_LANGUAGE_PATH . 'core.lang.php')) {
	include_once(AM_LANGUAGE_PATH . 'core.lang.php');
}


if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {

	if (isset($_POST['update_connection'])) {
		if (!empty($_POST['status_id'])) {
			$status_id = 1; // barred
		}
		else {
			$status_id = 2; // active
		}

		if (!empty($_POST['connection_trust'])) {
			$connection_trust = 1;
		}
		else {
			$connection_trust = "null";
		}

		$query = "
			UPDATE " . $am_core->prefix . "_connection
			SET
			connection_trusted=" .$connection_trust. ", 
			status_id=" .$status_id. "
			WHERE
			identity_id=" . AM_IDENTITY_ID . " AND
			connection_id=" . $_POST['connection_id']
		;
		//echo $query; exit;
		$am_core->Execute($query);

		$_REQUEST['connection_id'] = $_POST['connection_id'];
	}
	elseif (isset($_POST['update_inbound_trust'])) {
		$query = "
			UPDATE " . $am_core->prefix . "_connection
			SET
			connection_trusted=NULL
			WHERE
			identity_id=" . AM_IDENTITY_ID
		;

		$am_core->Execute($query);
		
		if (!empty($_POST['connection_trusted'])) {
			foreach($_POST['connection_trusted'] as $key => $i):
				$query = "
					UPDATE " . $am_core->prefix . "_connection
					SET
					connection_trusted=1 
					WHERE
					identity_id=" . AM_IDENTITY_ID . " AND
					connection_id=" . $i
				;

				$am_core->Execute($query);
			endforeach;
		}
	}
	elseif (isset($_POST['update_outbound_trust'])) {
		$query = "
			UPDATE " . $am_core->prefix . "_site
			SET
			site_trusted=NULL
			WHERE
			identity_id=" . AM_IDENTITY_ID
		;

		$am_core->Execute($query);
		
		if (!empty($_POST['site_trusted'])) {
			foreach($_POST['site_trusted'] as $key => $i):
				$query = "
					UPDATE " . $am_core->prefix . "_site 
					SET
					site_trusted=1
					WHERE
					identity_id=" . AM_IDENTITY_ID . " AND
					site_id=" . $i
				;

				$am_core->Execute($query);
			endforeach;
		}

		$_REQUEST['v'] = 'outbound';
	}

	if (isset($_REQUEST['connection_id'])) {
		$query = "
			SELECT connection_id, connection_openid,
			UNIX_TIMESTAMP(connection_create_datetime) as connection_create_datetime,
			UNIX_TIMESTAMP(connection_last_datetime) as connection_last_datetime,
			connection_total, status_id, connection_trusted  
			FROM " . $am_core->prefix . "_connection 
			WHERE
			identity_id=" . AM_IDENTITY_ID . " AND
			connection_id=" . $_REQUEST['connection_id']
		;
		
		$output_connection = $am_core->Execute($query);
		
		if (!empty($output_connection[0])) {
			$output_connection['attributes'] = $identity->selConnectionAttributes($output_connection[0]['connection_id']);
			
			$body->set('connection', $output_connection);
		}
	}
	elseif (isset($_REQUEST['v']) && $_REQUEST['v'] == 'outbound') {
		$query = "
			SELECT site_id, site_title, site_trusted, site_datetime_first_visit, 
			site_datetime_last_visit, site_realm, site_connections,
			site_data_sent 
			FROM " . $am_core->prefix . "_site
			WHERE identity_id=" . $_SESSION['identity_id']
		;
	
		$output_sites = $am_core->Execute($query);
	
		if (!empty($output_sites)) {
			$body->set('sites', $output_sites);
		}
	}
	else {
		$status_id = 2; // live

		if (isset($_REQUEST['v']) && $_REQUEST['v'] == "barred") {
			$status_id = 1; // barred
		}
		
		$query = "
			SELECT connection_id, connection_openid,
			UNIX_TIMESTAMP(connection_create_datetime) as connection_create_datetime,
			UNIX_TIMESTAMP(connection_last_datetime) as connection_last_datetime,
			connection_total, status_id, connection_trusted  
			FROM " . $am_core->prefix . "_connection 
			WHERE "
		;

		if (isset($_REQUEST['v']) && $_REQUEST['v'] == "trusted") {
			$query .= "connection_trusted=1 AND ";
		}

		$query .= "
			connection_id !=" . AM_OWNER_CONNECTION_ID . " AND
			status_id=" . $status_id . " AND 
			identity_id=" . AM_IDENTITY_ID . "
			ORDER BY connection_last_datetime desc";
		;
		
		$output_connections = $am_core->Execute($query);
		
		if (!empty($output_connections)) {
			foreach($output_connections as $key => $i):
				$output_connections[$key]['attributes'] = $identity->selConnectionAttributes($i['connection_id']);
			endforeach;
			
			$body->set('connections', $output_connections);
		}
	}
}
else {
	header("Location: index.php");
	exit;
}

?>