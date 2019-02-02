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

if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == $output_identity['owner_connection_id']) {
	
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
	
	if (isset($_POST['save_preferences'])) {
		if (!empty($_POST['preference_id'])) {
			$query = "
				UPDATE " . $am_core->prefix . "_plugin_blog_preference
				SET
				default_webpage_id=" . $_POST['default_webpage_id'] . ",
				rss_title=" . $am_core->qstr($_POST['rss_title']) . ",
				rss_title_comment=" . $am_core->qstr($_POST['rss_title_comment']) . ",
				rss_description=" . $am_core->qstr($_POST['rss_description']) . "
				WHERE
				preference_id=" . $_POST['preference_id']
			;
				
			$result = $am_core->Execute($query);
		}
		else {
			$rec = array();
			$rec['identity_id'] = $_SESSION['identity_id'];
			$rec['default_webpage_id'] = $_POST['default_webpage_id'];
			$rec['rss_title'] = $_POST['rss_title'];
			$rec['rss_title_comment'] = $_POST['rss_title_comment'];
			$rec['rss_description'] = $_POST['rss_description'];
			
			$table = $am_core->prefix . "_plugin_blog_preference";
				
			$am_core->insertDb($rec, $table);
		}
	}
	elseif (isset($_POST['update_blogs'])) {
		if (!empty($_POST['blog_ids'])) {
			foreach ($_POST['blog_ids'] as $key => $i):
				
				$query = "
					UPDATE " . $am_core->prefix . "_plugin_blog_entry 
					SET "
				;
				
				if (!empty($_POST['blog_archived'][$i])) {
					$query .= "blog_archived=1";
				}
				else {
					$query .= "blog_archived=NULL";
				}

				$query .= " WHERE blog_id=" . $i;

				$result = $am_core->Execute($query);
			endforeach;
		}
	}
	
	$query = "
		SELECT b.blog_id, b.blog_title, b.blog_archived, 
		UNIX_TIMESTAMP(b.blog_create_datetime) as blog_create_datetime
		FROM " . $am_core->prefix . "_plugin_blog_entry b
		WHERE 
		b.identity_id=" . $_SESSION['identity_id'] . "
		ORDER BY b.blog_create_datetime"
	;

	if (isset($attributes['limit'])) {
		$result = $am_core->Execute($query, (int) $attributes['limit']);
	}
	else {
		$result = $am_core->Execute($query);
	}

	if (!empty($result)) {
		$body->set('blogs', $result);
	}

	// SELECT WEBPAGES
	$query = "
		SELECT webpage_id, webpage_name
		FROM " . $am_core->prefix . "_webpage
		WHERE
		webspace_id=" . AM_WEBSPACE_ID
	;

	$result = $am_core->Execute($query);

	if (!empty($result)) {
		$body->set('webpages', $result);
	}

	// SELECT PREFERENCES
	$query = "
		SELECT preference_id, default_webpage_id, rss_title, rss_title_comment, rss_description
		FROM " . $am_core->prefix . "_plugin_blog_preference
		WHERE
		identity_id=" . AM_IDENTITY_ID
	;

	$result = $am_core->Execute($query);

	if (!empty($result[0])) {
		$preferences = $result[0];
	}

	if (empty($preferences['rss_title'])) {
		$preferences['rss_title'] = $lang['manage_rss_title'];
	}

	if (empty($preferences['rss_title_comment'])) {
		$preferences['rss_title_comment'] = $lang['manage_rss_title_comments'];
	}

	if (empty($preferences['rss_description'])) {
		$preferences['rss_description'] = $lang['manage_rss_description'];
	}

	if (empty($preferences['default_webpage_id']) && isset($_REQUEST['wp'])) {
		$preferences['default_webpage_id'] = $_REQUEST['wp'];
	}
		
	$body->set('preferences', $preferences);
}
else {
	header("Location: index.php");
	exit;
}
?>