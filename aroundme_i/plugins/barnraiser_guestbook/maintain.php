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

if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
	
	if (is_readable('plugins/' . AM_PLUGIN_NAME . '/language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_common.lang.php')) {
		include_once('plugins/' . AM_PLUGIN_NAME . '/language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_common.lang.php');
	}
		
	if (is_readable('plugins/' . AM_PLUGIN_NAME . '/language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_manage.lang.php')) {
		include_once('plugins/' . AM_PLUGIN_NAME . '/language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_manage.lang.php');
	}

	// we overwrite any default array keys with the webspace language keys
	if (defined('AM_LANGUAGE_CODE')) {
		if (is_readable('plugins/' . AM_PLUGIN_NAME . '/language/' . AM_LANGUAGE_CODE . '/plugin_common.lang.php')) {
			include_once('plugins/' . AM_PLUGIN_NAME . '/language/' . AM_LANGUAGE_CODE . '/plugin_common.lang.php');
		}
		
		if (is_readable('plugins/' . AM_PLUGIN_NAME . '/language/' . AM_LANGUAGE_CODE . '/plugin_manage.lang.php')) {
			include_once('plugins/' . AM_PLUGIN_NAME . '/language/' . AM_LANGUAGE_CODE . '/plugin_manage.lang.php');
		}
	}
	
	if (isset($_POST['delete_guestbook_entries'])) {
		if (!empty($_POST['delete_guestbook_entry_id'])) {
		
			$query = "
				DELETE
				FROM " . $am_core->prefix . "_plugin_guestbook
				WHERE guestbook_id IN (" . implode(',', $_POST['delete_guestbook_entry_id']) . ")
				AND identity_id=" . AM_IDENTITY_ID
			;

			$am_core->Execute($query);
		}
	}
	
	// we get the guestbook entries
	$query = "
		SELECT g.guestbook_id, g.connection_id,
		g.guestbook_body, g.guestbook_create_datetime, 
		c.connection_openid 
		FROM " . $am_core->prefix . "_plugin_guestbook g
		INNER JOIN " . $am_core->prefix . "_connection c
		ON g.connection_id=c.connection_id
		WHERE g.identity_id=" . AM_IDENTITY_ID . "
		ORDER BY guestbook_create_datetime DESC"
	;
	
	$guestbook_entries = $am_core->Execute($query);
	
	if (!empty($guestbook_entries)) {
		foreach($guestbook_entries as $key => $i):
			$guestbook_entries[$key]['attributes'] = $identity->selConnectionAttributes($i['connection_id']);
		endforeach;

		$body->set('guestbook_entries', $guestbook_entries);
	}
}
else {
	header("Location: index.php");
	exit;
}

?>