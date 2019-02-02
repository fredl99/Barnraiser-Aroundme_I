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
	
	// SETUP IMAGE ----------------------------------------------
	include_once('class/Image.class.php');
	$image = new Image($core_config['file']);
	$image->path = 'avatars/' . AM_IDENTITY_ID;
	$image->db = &$am_core;
	$image->is_avatar = 1;

	if (isset($_POST['save_identity_profile'])) {
		
		$table = $am_core->prefix . '_connection_attribute';
		$rec = array();
		$rec['connection_id'] = AM_OWNER_CONNECTION_ID;
		
		$query = "
			DELETE
			FROM " . $table . "
			WHERE connection_id=" . AM_OWNER_CONNECTION_ID
		;
		
		$am_core->Execute($query);
		
		foreach($_POST['identity_profile'] as $key => $value):
			
			$value = trim($value);
			
			if (!empty($value)) {
				$rec['attribute_name'] = $key;
				$rec['attribute_value'] = $value;
				if (isset($_POST['identity_profile_level'][$key])) {
					$rec['level_id'] = $_POST['identity_profile_level'][$key];
				}
				else {
					$rec['level_id'] = 0;
				}
				$am_core->insertDb($rec, $table);
			}
		endforeach;
		
		header("Location: index.php?t=profile");
		exit;
	}
	elseif (isset($_POST['submit_upload_avatar'])) {

		if (isset($_FILES['frm_file']) && !empty($_FILES['frm_file']['tmp_name'])) {
			
			$image->uploadImage();

			$current_avatar_name = 'http://' . rtrim($_SERVER['SERVER_NAME'], '/') . '/core/get_file.php?avatar=' . AM_IDENTITY_ID . '/' . $image->filename;
			
			$tmp = strrpos($current_avatar_name, '.');
			if ($tmp) {
				$prefix = substr($current_avatar_name, 0, $tmp);
				$suffix = substr($current_avatar_name, $tmp);
				$filename_new = $prefix . '_100' . $suffix;
			}
			else {
				$filename_new = $current_avatar_name . '_' . $t;
			}
			$current_avatar_name = $filename_new;

			$query = "
				DELETE
				FROM " . $am_core->prefix . "_connection_attribute 
				WHERE connection_id=" . AM_OWNER_CONNECTION_ID . " AND
				attribute_name=" . $am_core->qstr('media/image/aspect11')
			;
			
			$am_core->Execute($query);
			
			$table = $am_core->prefix . '_connection_attribute';
			
			$rec = array();
			$rec['connection_id'] = AM_OWNER_CONNECTION_ID;
			$rec['attribute_value'] = $current_avatar_name;
			$rec['attribute_name'] = 'media/image/aspect11';
			$rec['level_id'] = 0;
			
			$am_core->insertDb($rec, $table);

			header("Location: index.php?t=profile");
			exit;
		}
	}
	elseif (isset($_POST['submit_set_avatar'])) {
	
		if (!empty($_POST['current_avatar_name'])) {
			$current_avatar_name = 'http://' . rtrim($_SERVER['SERVER_NAME'], '/') . '/core/get_file.php?avatar=' . $_POST['current_avatar_name'];
			
			$query = "
				DELETE
				FROM " . $am_core->prefix . "_connection_attribute 
				WHERE connection_id=" . AM_OWNER_CONNECTION_ID . " AND
				attribute_name=" . $am_core->qstr('media/image/aspect11')
			;
			
			$am_core->Execute($query);
			
			$table = $am_core->prefix . '_connection_attribute';
			
			$rec = array();
			$rec['connection_id'] = AM_OWNER_CONNECTION_ID;
			$rec['attribute_value'] = $current_avatar_name;
			$rec['attribute_name'] = 'media/image/aspect11';
			$rec['level_id'] = 0;
			
			$am_core->insertDb($rec, $table);
		}
		else {
			$query = "
				DELETE
				FROM " . $am_core->prefix . "_connection_attribute 
				WHERE connection_id=" . AM_OWNER_CONNECTION_ID . " AND
				attribute_name=" . $am_core->qstr('media/image/aspect11')
			;
			
			$am_core->Execute($query);
		}
	
		header("Location: index.php?t=profile");
		exit;
	}
	elseif (isset($_POST['submit_delete_avatar'])) {

		if (!empty($_POST['delete_avatar_name'])) {

			$image->path = 'avatars';

			foreach($_POST['delete_avatar_name'] as $key => $i):
				$tmp = explode('.', $i);
	
				$image->deleteImages(array($tmp[0]));
	
				$tmp = explode('/', $i);
			
				$query = "
					DELETE
					FROM " . $am_core->prefix . "_file
					WHERE file_id IN(" . implode(', ', $_POST['delete_avatar_id']) . ")
					AND identity_id=" . AM_IDENTITY_ID
				;

				$am_core->Execute($query);
			endforeach;
		}

		header("Location: index.php?t=profile");
		exit;
	}
	
	$query = "
		SELECT attribute_name, attribute_value, level_id
		FROM " . $am_core->prefix . "_connection_attribute
		WHERE connection_id=" . AM_OWNER_CONNECTION_ID
	;
	
	$result = $am_core->Execute($query);
	if (!empty($result)) {
		$body->set('identity_attributes', $result);
	}
	
	// SETUP AVATARS ------------------------------------------------------
	
	$query = "
		SELECT f.file_id, f.file_type, f.file_size, 
		f.file_name, f.identity_id, 
		f.file_create_datetime, f.file_title, f.file_is_avatar
		FROM am_file f
		WHERE f.identity_id=" . AM_IDENTITY_ID . " AND
		f.file_is_avatar=1"
	;
	
	$result = $am_core->Execute($query);
	$avatars = array();
	
	if (!empty($result)) {
		foreach($result as $key => $val):
		
			$suffix = substr($val['file_name'], strrpos($val['file_name'], '.'));
			if ($suffix) {
				$avatar_thumb = substr($val['file_name'], 0, strrpos($val['file_name'], '.')) . '_100' . $suffix;
			}
			else {
				$avatar_thumb .= '_100';
			}
			$val['file_name'] = AM_IDENTITY_ID . '/' . $avatar_thumb;
			array_push($avatars, $val);
		endforeach;
	}
	
	if (!empty($avatars)) {
		$body->set('avatars', $avatars);
	}
}
else {
	header("Location: index.php");
	exit;
}

$body->set('config_identity_attributes', $core_config['identity_attribute']);

?>