<?php

// ---------------------------------------------------------------------
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
// --------------------------------------------------------------------

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'core.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'core.lang.php');
}

if (defined('AM_LANGUAGE_CODE') && is_readable(AM_LANGUAGE_PATH . 'core.lang.php')) {
	include_once(AM_LANGUAGE_PATH . 'core.lang.php');
}

// If we are connected to check all required fields; if empty we prompt to fill
// If not connected we present login box

if (isset($_POST['update_connection'])) {
	
	$connection_attributes = array();
	
	if (!empty($_POST['connection_required_fields'])) {
		foreach($_POST['connection_required_fields'] as $key => $i):
			$i = trim ($i);
			if (empty($i)) {
				$GLOBALS['am_error_log'][] = array($lang['error']['account_informatio_missing'] . $key);
			}
			else {
				$_SESSION['openid_' . $ax2sreg[$key]] = $i;
				$connection_attributes[] = array('attribute_name' => $key, 'attribute_value' => $i);
			}
		endforeach;
	}

	if (!empty($_POST['connection_optional_fields'])) {
		foreach ($_POST['connection_optional_fields'] as $key => $i):
			if (!empty($i)) {
				$_SESSION['openid_' . $ax2sreg[$key]] = $i;
				$connection_attributes[] = array('attribute_name' => $key, 'attribute_value' => $i);
			}
		endforeach;
	}
		
	if (empty($GLOBALS['am_error_log'])) {
		
		$query = "
			DELETE FROM " . $am_core->prefix . "_connection_attribute
			WHERE connection_id=" . $_SESSION['connection_id']
		;
		
		$am_core->Execute($query);
		
		$rec = array();
		$rec['connection_id'] = $_SESSION['connection_id'];
		$rec['level_id'] = 0;
		$table = $am_core->prefix . '_connection_attribute';
		
		foreach($connection_attributes as $key => $value):
			$rec['attribute_name'] = $value['attribute_name'];
			$rec['attribute_value'] = $value['attribute_value'];
			$am_core->insertDb($rec, $table);
		endforeach;
		
		if (isset($output_webspace)) {
			// append log
			$log_entry = array();
			$log_entry['title'] = 'someone connected';
			$log_entry['body'] = '<a href="index.php?t=network&amp;connection_id=' . $_SESSION['connection_id'] . '">' . $_SESSION['openid_nickname'] . '</a> connected.';
			$log_entry['link'] = $_SESSION['openid_identity'];
			$identity->appendLog($log_entry);
		}


		if (isset($_GET['return_to'])) { 
			header("Location: " . $_GET['return_to']);
			exit;
		}
		else {
			header("Location: index.php");
			exit;
		}
	}


	if (!empty($GLOBALS['am_error_log'])) {
		$body->set('display', 'append_connection');
	}
}
elseif (isset($_SESSION['connection_id']) && isset($_REQUEST['no_sreg'])) {
	$query = "
		SELECT *
		FROM " . $am_core->prefix . "_connection_attribute
		WHERE
		connection_id=" . $_SESSION['connection_id']
	;

	$result = $am_core->Execute($query, 1);

	if (isset($result)) {
		$body->set('connection_attribute', $result);
	}

	$body->set('display', 'append_connection');
}
elseif (isset($_SESSION['connection_id'])) {
	header("Location: index.php");
	exit;
}

$body->set('config_identity_attributes', $core_config['identity_attribute']);

// fields to view
if (isset($openid_consumer->required_fields)) {
	$body->set('openid_required_fields', $openid_consumer->required_fields);
}
if (isset($openid_consumer->optional_fields)) {
	$body->set('openid_optional_fields', $openid_consumer->optional_fields);
}
?>