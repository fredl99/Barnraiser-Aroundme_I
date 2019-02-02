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

if (isset($_POST['create_webspace'])) {
	// we update title and stuff to the webspace here
	// we also insert chosen css and default webpages
	
	if (empty($_POST['webspace_title'])) {
		$GLOBALS['am_error_log'][] = array($lang['error']['title_not_set']);
	}
	
	if (empty($_POST['theme_css'])) {
		$GLOBALS['am_error_log'][] = array($lang['error']['css_not_set']);
	}
	
	if (empty($_POST['theme_name'])) {
		$GLOBALS['am_error_log'][] = array($lang['error']['layout_not_set']);
	}
	
	if (empty($GLOBALS['am_error_log'])) {
		// create the webspace
		$rec = array();
		$rec['identity_id'] = $_SESSION['identity_id'];
		$rec['webspace_title'] = $_POST['webspace_title'];
		$rec['default_permission'] = $core_config['am']['webspace_default_permission'];
		$rec['webspace_allocation'] = $core_config['file']['default_allocation'];
		
		$table = $am_core->prefix . '_webspace';
		
		$am_core->insertDb($rec, $table);
		
		$webspace_id = $am_core->insertID();
/*
		$rec = array();
		$rec['stylesheet_name'] = 'barnraiser_' . $_POST['css'];
		$rec['webspace_id'] = $webspace_id;
// 		$rec['stylesheet_body'] = '';
// 		$rec['stylesheet_body'] = @file_get_contents('webspace/css/barnraiser_' . $_POST['css'] . '.css');
	
		$table = $am_core->prefix . '_stylesheet';
		
		$am_core->insertDb($rec, $table);
		
		$stylesheet_id = $am_core->insertID();
		
		$rec = array();
		$rec['webpage_name'] = $_POST['layout'];
		$rec['webspace_id'] = $webspace_id;
		$rec['webpage_create_datetime'] = time();
// 		$rec['webpage_body'] = '';
// 		$rec['webpage_body'] = @file_get_contents('webspace/layouts/' . $_POST['layout'] . '.wp.php');
		
// 		if (get_magic_quotes_gpc()) {
// 			$rec['webpage_body'] = addslashes($rec['webpage_body']);
// 		}

		$table = $am_core->prefix . '_webpage';
		
		$am_core->insertDb($rec, $table);
		
		$webpage_id = $am_core->insertID();
		
		$query = "
			UPDATE " . $am_core->prefix . "_webspace
			SET default_webpage_id=" . $webpage_id . ",
			stylesheet_id=" . $stylesheet_id . " 
			WHERE webspace_id=" . $webspace_id
		;

		$am_core->Execute($query);
*/
		
		
		//------------------------------------------------------------------------
		// INSERT PAGES
		$webpages = glob('webspace/' . $_POST['theme_name'] . '/webpage/*.php');
	
		$rec = array();
		$rec['webspace_id'] = $webspace_id;
		$rec['webpage_create_datetime'] = time();
	
		$table = $am_core->prefix . '_webpage';
	
		foreach($webpages as $w) {
			$tmp = explode('/', $w);
			$tmp = explode('.', $tmp[count($tmp)-1]);
	
			$webpage_contents = "";
			$webpage_contents .= @file_get_contents($w);

			if (get_magic_quotes_gpc()) {
				$webpage_contents = addslashes($webpage_contents);
			}
			
			if (substr($tmp[0], -8) == '_default') {
				$rec['webpage_name'] = str_replace('_default', '', $tmp[0]);
			}
			else {
				$rec['webpage_name'] = $tmp[0];
			}

			$rec['webpage_body'] = $webpage_contents;
	
			$am_core->insertDb($rec, $table);
	
			// if the filename ends with _default then we record this as the default page
			if (substr($tmp[0], -8) == '_default') {
				$webpage_id = $am_core->insertID();
			}
		}
	
		if (!isset($webpage_id)) {
			$webpage_id = $am_core->insertID();
		}
			
	
		// INSERT BLOCKS
		$blocks = glob('webspace/' . $_POST['theme_name'] . '/block/*.php');
	
		$rec = array();
		$rec['webspace_id'] = $webspace_id;
		$rec['block_plugin'] = 'custom';
		
		$table = $am_core->prefix . '_block';
	
		foreach($blocks as $b) {
			$tmp = explode('/', $b);
			$tmp = explode('.', $tmp[count($tmp)-1]);
			
			$block_contents = "";
			$block_contents .= @file_get_contents($b);

			if (get_magic_quotes_gpc()) {
				$block_contents = addslashes($block_contents);
			}
			
			$rec['block_name'] = $tmp[0];
			$rec['block_body'] = $block_contents;
	
			$am_core->insertDb($rec, $table);
		}


		// INSERT CSS'S
		$stylesheets = glob('webspace/' . $_POST['theme_name'] . '/css/*.css');
	
		$rec = array();
		$rec['webspace_id'] = $webspace_id;
		
		$table = $am_core->prefix . '_stylesheet';
	
		foreach($stylesheets as $s) {
			$tmp = explode('/', $s);
			$tmp = explode('.', $tmp[count($tmp)-1]);
			
			$stylesheet_contents = "";
			$stylesheet_contents .= @file_get_contents($s);

			if (get_magic_quotes_gpc()) {
				$stylesheet_contents = addslashes($stylesheet_contents);
			}
			
			$rec['stylesheet_name'] = $tmp[0];
			$rec['stylesheet_body'] = $stylesheet_contents;
	
			$am_core->insertDb($rec, $table);

			if ($tmp[0] == $_POST['theme_css']) {
				$stylesheet_id = $am_core->insertID();
			}
		}

		if (!isset($stylesheet_id)) {
			$stylesheet_id = $am_core->insertID();
		}
		
		
		// UPDATE WEBSPACE
		$query = "
			UPDATE " . $am_core->prefix . "_webspace
			SET
			stylesheet_id=" . $stylesheet_id . ",
			default_webpage_id=" . $webpage_id . "
			WHERE webspace_id=" . $webspace_id
		;
		
		$am_core->Execute($query);
		//-----------------------------------------------------------------------------------
		
		
		
		
		
		header('Location: index.php');
		exit;
	}
}

// THEME SETUP -------------------------------------------------
$themes = selThemes();



// TEMPLATE SEUP -----------------------------------------------
$body->set('themes', $themes);

$body->set('arr_language', $core_config['language']);
$body->set('lang', $lang);
$body->lang = $lang;


// FUNCTIONS ---------------------------------------------------
function selThemes () {

	global $lang;
	
	$thumbs = glob('webspace/*/thumb/*.png');
	$css = glob('webspace/*/css/*.css');
	$webpages = glob('webspace/*/webpage/*.php');
	$themes = array();
	
	foreach($thumbs as $key => $t):
		$tmp = explode('/', $t);
		$themes[$tmp[1]]['thumb'][] = $t;
	endforeach;
	
	foreach($css as $key => $t):
		$tmp = explode('/', $t);
		$themes[$tmp[1]]['css'][] = $t;
	endforeach;
	
	foreach($webpages as $key => $t):
		$tmp = explode('/', $t);
		$themes[$tmp[1]]['webpage'][] = $t;
	endforeach;
	
	// LOAD UP THE LANGUAGE FILES
	foreach($themes as $key => $t):
		if (is_readable('webspace/' . $key . '/language/en/theme.lang.php')) {
			include('webspace/' . $key . '/language/en/theme.lang.php');
		}
	endforeach;
	
	return $themes;
}

?>