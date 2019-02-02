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
	
	

	if (isset($_POST['save_identity_levels'])) {

		$query = "
			UPDATE " . $am_core->prefix . "_connection_attribute
			SET
			level_id=0
			WHERE
			connection_id=" . AM_OWNER_CONNECTION_ID
		;

		$am_core->Execute($query);
		
		if (!empty($_POST['identity_level'])) {
			foreach ($_POST['identity_level'] as $key => $i):
				$query = "
					UPDATE " . $am_core->prefix . "_connection_attribute
					SET
					level_id=" . $i . " 
					WHERE
					attribute_name=" . $am_core->qstr($key) . " AND
					connection_id=" . AM_OWNER_CONNECTION_ID
				;

				$am_core->Execute($query);
			endforeach;
		}
	}

	$query = "
		SELECT attribute_name, attribute_value, level_id
		FROM " . $am_core->prefix . "_connection_attribute
		WHERE
		connection_id=" . AM_OWNER_CONNECTION_ID
	;

	$result = $am_core->Execute($query);

	if (!empty($result)) {
		$body->set('identity_profile_attributes', $result);
	}
}
else {
	header("Location: index.php");
	exit;
}

?>