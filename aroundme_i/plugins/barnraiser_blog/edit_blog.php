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
	
	if (isset($_POST['save_blog']) || isset($_POST['save_go_blog'])) {
		if (empty($_POST['blog_title'])) {
			$GLOBALS['am_error_log'][] = array('title_empty');
		}

		if (empty($_POST['blog_body'])) {
			$GLOBALS['am_error_log'][] = array('body_empty');
		}

		if (empty($GLOBALS['am_error_log'])) {
			$_POST['blog_title'] = strip_tags($_POST['blog_title']);

			$_POST['blog_body'] = $am_core->am_parse($_POST['blog_body']);
		
			if (!empty($_POST['blog_id'])) { // we update the page
				
				if (!isset($_POST['blog_allow_comment'])) {
					$allow_comment = "null";
				}
				else {
					$allow_comment = 1;
				}
				
				$query = "
					UPDATE " . $am_core->prefix . "_plugin_blog_entry 
					SET
					blog_title=" . $am_core->qstr($_POST['blog_title']) . ",
					blog_body=" . $am_core->qstr($_POST['blog_body']) . ",
					blog_allow_comment=" . $allow_comment . ",
					blog_edit_datetime=" . $am_core->qstr(date('Y-m-d H:i:s')) . "
					WHERE
					blog_id=" . $_POST['blog_id']
				;
				
				$result = $am_core->Execute($query);
			}
			else { // we insert
		
				$rec = array();
				$rec['identity_id'] = AM_IDENTITY_ID;
				$rec['blog_title'] = $_POST['blog_title'];
				$rec['blog_body'] = $_POST['blog_body'];
				$rec['connection_id'] = $_SESSION['connection_id'];
				$rec['blog_create_datetime'] = time();
				
				if (!isset($_POST['blog_allow_comment'])) {
					$rec['blog_allow_comment'] = "null";
				}
				
				$table = $am_core->prefix . "_plugin_blog_entry";
				
				$am_core->insertDb($rec, $table);
		
				$_REQUEST['blog_id'] = $am_core->insertID();

				// Append log
				$log_entry = array();
				$log_entry['title'] = 'blog entry added';
				$log_entry['body'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . '</a> added a <a href="index.php?wp=' . $_REQUEST['wp'] . '&amp;blog_id=' . $_REQUEST['blog_id'] . '">blog entry</a>.';
				$log_entry['link'] = "index.php?wp=" . $_REQUEST['wp'] . "&amp;blog_id=" . $_REQUEST['blog_id'];
				$identity->appendLog($log_entry);
			}

			if (isset($_POST['save_go_blog'])) {
				header("Location: index.php?wp=" . $_REQUEST['wp'] . "&blog_id=" . $_REQUEST['blog_id']);
				exit;
			}
		}
		else {
			$_POST['blog_body'] = stripslashes($_POST['blog_body']);
			$_POST['blog_title'] = htmlspecialchars($_POST['blog_title']);
			$_POST['blog_title'] = stripslashes($_POST['blog_title']);
			
			$body->set('blog', $_POST);
			unset($_REQUEST['blog_id']);
		}
	}
	
	
	if (!empty($_REQUEST['blog_id'])) { // we are editing a page
		$query = "
			SELECT blog_id, blog_title, blog_body, blog_allow_comment
			FROM " . $am_core->prefix . "_plugin_blog_entry 
			WHERE blog_id=" . $_REQUEST['blog_id'] . " AND
			identity_id=" . AM_IDENTITY_ID
		;
		
		$result = $am_core->Execute($query);
		
		if (isset($result[0])) {
			$output_blog = $result[0];
			
			$output_blog['blog_body'] = $body->am_render($output_blog['blog_body']);
			
			$body->set('blog', $output_blog);
		}
	}
	
	// get webpages
	$output_webpages = $ws->selWebPages();

	if (!empty($output_webpages)) {
		$body->set('webpages', $output_webpages);
	}
	
	$query = "
		SELECT file_id, file_type, file_size, file_name, 
		identity_id, file_create_datetime, 
		file_title, file_is_avatar
		FROM " . $am_core->prefix . "_file
		WHERE identity_id=" . AM_IDENTITY_ID . " AND 
		file_is_avatar=0"
	;

	$result = $am_core->Execute($query);

	if (!empty($result)) {
	
		$output_images = array();
		foreach($result as $key => $i):
			$file_image = array();
			$file_image = $i;
		
			$tmp = explode('.', $i['file_name']);
		
			if (isset($tmp[0], $tmp[1])) {
				$file_image['thumb_90'] = AM_IDENTITY_ID . '/' . $tmp[0] . '_90.' . $tmp[1];
				$file_image['thumb_35'] = AM_IDENTITY_ID . '/' . $tmp[0] . '_35.' . $tmp[1];
				$file_image['src'] = AM_IDENTITY_ID . '/' . $i['file_name'];
			}
			array_push($output_images, $file_image);
		endforeach;
		$body->set('pictures', $output_images);
	}

}
else { // no permission to be here
	header("Location: index.php");
	exit;
}
?>