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


// SETUP IMAGE ----------------------------------------------
include_once('class/Image.class.php');
$image = new Image($core_config['file']);
$image->path = 'images/' . AM_IDENTITY_ID;
$image->thumbnail_width = array(90, 35);
$image->thumbnail_height = array(90, 35);
$image->normal_upload = 1;
$image->db = &$am_core;


if (isset($_POST['submit_file_upload'])) {

	if (!empty($_POST['file_width'])) {
		$image->width = $_POST['file_width'];
	}
	
	if (!empty($_POST['frm_file_title'])) {
		$image->filetitle = $_POST['frm_file_title'];
	}
	
	$image->uploadImage();
}
elseif (isset($_POST['delete_file']) && !empty($_POST['file_to_delete'])) {
	$image->path = 'images';
	
	$tmp = explode('.', $_POST['file_to_delete']);
	
	$image->deleteImages(array($tmp[0]));
	
	$tmp = explode('/', $_POST['file_to_delete']);
	
	$query = "
		DELETE
		FROM " . $am_core->prefix . "_file
		WHERE file_name=" . $am_core->qstr($tmp[1]) . "
		AND identity_id=" . AM_IDENTITY_ID
	;

	$am_core->Execute($query);
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
			$file_image['thumb_35'] = AM_IDENTITY_ID . '/' . $tmp[0] . '_35.' . $tmp[1];
			$file_image['thumb_90'] = AM_IDENTITY_ID . '/' . $tmp[0] . '_90.' . $tmp[1];
		}
		array_push($output_images, $file_image);
	endforeach;
	$body->set('files', $output_images);
}

if (isset($_REQUEST['file_id'])) {
	$query = "
		SELECT file_id, file_type, file_size, file_name, 
		identity_id, file_create_datetime, 
		file_title, file_is_avatar
		FROM " . $am_core->prefix . "_file
		WHERE identity_id=" . AM_IDENTITY_ID . " AND 
		file_is_avatar=0 AND file_id=" . $_REQUEST['file_id']
	;

	$result = $am_core->Execute($query);
	
	if (isset($result[0]) && (!empty($result[0]))) {
		$output_file = $result[0];
		$tmp = explode('.', $output_file['file_name']);
		$output_file['file_name'] = AM_IDENTITY_ID . '/' . $output_file['file_name'];
		
		if (isset($tmp[0], $tmp[1])) {
			$output_file['thumb_35'] = AM_IDENTITY_ID . '/' . $tmp[0] . '_35.' . $tmp[1];
			$output_file['thumb_90'] = AM_IDENTITY_ID . '/' . $tmp[0] . '_90.' . $tmp[1];
		}
	}
	
	if (!empty($output_file)) {
		$body->set('file', $output_file);
		
		if (!empty($result)) {
			$body->set('in_use', $result);
		}
	}
}

?>