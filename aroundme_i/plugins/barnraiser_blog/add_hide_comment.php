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

// START SESSION -----------------------------------------------------------
include ("../../core/config/core.config.php");
include ("../../core/inc/functions.inc.php");


session_name($core_config['php']['session_name']);
session_start();


// SETUP DATABASE ------------------------------------------------------
require('../../core/class/Db.class.php');
$am_core = new Database($core_config['db']);


if (isset($_POST['insert_comment'])) {


	// SETUP IDENTITY --------------------------------------------
	require_once('../../core/class/Identity.class.php');
	$identity = new Identity($am_core);
	
	
	// OBTAIN IDENTITY ACCOUNT NAME
	$identity->identity_name = $identity->getIdentityName($core_config['am']['domain_preg_pattern']);
	
	if (!empty($identity->identity_name)) {
		define('AM_IDENTITY_NAME', $identity->identity_name);
		
		$output_identity = $identity->selIdentity();
	
		if (!empty($output_identity)) {
			
			if (isset($_SESSION['connection_id']) && isset($_POST['blog_id'])) {
				// INSERT COMMENT
				$rec = array();
				$rec['identity_id'] = $output_identity['identity_id'];
				$rec['blog_id'] = $_POST['blog_id'];
				$rec['connection_id'] = $_SESSION['connection_id'];
				
				$rec['comment_body'] = am_parse(stripslashes($_POST['comment_body']));
				$rec['comment_create_datetime'] = time();
		
				$table = $am_core->prefix . "_plugin_blog_comment";
				
				$am_core->insertDb($rec, $table);
	
				$comment_id = $am_core->insertID();
				
				// Append log
				$log_entry = array();
				$log_entry['title'] = 'blog comment added';
				$log_entry['body'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . '</a> added a <a href="index.php?wp=' . $_REQUEST['wp'] . '&amp;blog_id=' . $_POST['blog_id'] . '#comment_id' . $comment_id . '">blog comment</a>.';
				$log_entry['link'] = "index.php?wp=" . $_REQUEST['wp'] . "&amp;blog_id=" . $_POST['blog_id'] . "#comment_id" . $comment_id;
				$identity->appendLog($log_entry);
			
				session_write_close();
			}
		}
	}
}
elseif (isset($_POST['hide_comment']) && !empty($_SESSION['identity_id']) && !empty($_POST['comment_id'])) {

	$query = "
		UPDATE " . $am_core->prefix . "_plugin_blog_comment
		SET comment_hidden=1
		WHERE
		comment_id=" . $_POST['comment_id'] . " AND
		identity_id=" . $_SESSION['identity_id']
	;
	
	$am_core->Execute($query);

}
elseif (isset($_POST['show_comment']) && !empty($_SESSION['identity_id']) && !empty($_POST['comment_id'])) {

	$query = "
		UPDATE " . $am_core->prefix . "_plugin_blog_comment
		SET comment_hidden=NULL
		WHERE
		comment_id=" . $_POST['comment_id'] . " AND
		identity_id=" . $_SESSION['identity_id']
	;
	
	$am_core->Execute($query);

}


header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>