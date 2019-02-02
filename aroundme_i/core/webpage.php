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
	
	// check that our webpage name is valid ------------------------------
	if (isset($_REQUEST['wp'])) {
	
		$pattern = "/^[a-zA-Z0-9]*$/";
	
		if (!preg_match($pattern, $_REQUEST['wp'])) {
			header("Location: index.php");
			exit;
		}
	
		if (strlen($_REQUEST['wp']) > 30) { // link too long
			header("Location: index.php");
			exit;
		}
	
		define("AM_WEBPAGE_NAME", $_REQUEST['wp']);
	}
	else {
		// no webpage - we error
		header("Location: index.php");
		exit;
	}


	// update webpage ----------------------------------------------------
	if (isset($_POST['save_webpage']) || isset($_POST['save_go_webpage'])) {
	
		if (preg_match_all("/\<\?(.*)\?\>/", $_POST['webpage_body'], $matches)) { 
			foreach($matches[1] as $m) {
				if (!$am_core->check_tokens($m, $core_config['invalid_tokens'])) {
					$GLOBALS['am_error_log'][] = array($lang['error']['body_forbidden_tokens']);
					break;
				}
			}
		}
		
		if (empty($GLOBALS['am_error_log'])) {
			$query = "
				SELECT webpage_id, webpage_body, webpage_name, webpage_create_datetime
				FROM " . $am_core->prefix . "_webpage
				WHERE webpage_name=" . $am_core->qstr(AM_WEBPAGE_NAME) . " AND 
				webspace_id=" . AM_WEBSPACE_ID
			;
		
			$result = $am_core->Execute($query);

			if (!empty($result[0])) { // we update the page
				
				$query = "
					UPDATE " . $am_core->prefix . "_webpage
					SET  
					webpage_body=" . $am_core->qstr($_POST['webpage_body']) . " 
					WHERE
					webpage_id=" . $result[0]['webpage_id'] . " AND 
					webspace_id=" . AM_WEBSPACE_ID
				;
			
				$result = $am_core->Execute($query);
				
			}
			else { // we insert a new page
				
				$rec = array();
				$rec['webpage_body'] = $_POST['webpage_body'];
				$rec['webpage_name'] = AM_WEBPAGE_NAME;
				$rec['webspace_id'] = AM_WEBSPACE_ID;
				$rec['webpage_create_datetime'] = time();
	
				$table = $am_core->prefix . "_webpage";
			
				$am_core->insertDb($rec, $table);
			}

			if (isset($_POST['save_go_webpage'])) {
				header("Location: index.php?wp=" . AM_WEBPAGE_NAME);
				exit;
			}
		}
	}
	
	$ws->webpage_name = AM_WEBPAGE_NAME;
	$output_webpage = $ws->selWebPage();

	$body->set('webpage', $output_webpage);



	// BUILD EDITOR HELPERS
	// SELECT PLUGINS
	$plugins = $am_core->amscandir('plugins');



	if (!empty($plugins)) {
		foreach ($plugins as $key => $i):
			if (is_readable('plugins/' . $i . '/language/' . AM_DEFAULT_LANGUAGE_PATH . '/plugin_common.lang.php')) {
				include_once('plugins/' . $i . '/language/' . AM_DEFAULT_LANGUAGE_PATH . '/plugin_common.lang.php');
			}

			if (defined('AM_LANGUAGE_CODE') && is_readable('plugins/' . $i . '/language/' . AM_LANGUAGE_PATH . '/plugin_common.lang.php')) {
				include_once('plugins/' . $i . '/language/' . AM_LANGUAGE_PATH . '/plugin_common.lang.php');
			}

		endforeach;
	}
	
	$body->set('plugins', $plugins);

	$output_webpages = $ws->selWebPages();

	if (!empty($output_webpages)) {
		foreach ($output_webpages as $key => $i):
			$output_webpages[$key] = str_replace('.wp.php', '', $i);
		endforeach;
		
		$body->set('webpages', $output_webpages);
	}
	
	$webpage_layouts = $am_core->amscandir('layouts');
	
	if (!empty($webpage_layouts)) {
		$body->set('webpage_layouts', $webpage_layouts);
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
	
	
	// GET CUSTOM BLOCKS
	$query = "
		SELECT block_name 
		FROM " . $am_core->prefix . "_block
		WHERE
		webspace_id=" . AM_WEBSPACE_ID . " AND 
		block_plugin='custom' 
		ORDER BY block_name"
	;

	$result = $am_core->Execute($query);

	if (!empty($result)) {
		$body->set('blocks', $result);
	}
}
else {
	header("Location: index.php");
	exit;
}

?>