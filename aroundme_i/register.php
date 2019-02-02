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

// MAIN INCLUDES 
include ("core/config/core.config.php");
include ("core/inc/functions.inc.php");


// SESSION HANDLER ------------------------------------------------------
// sets up all session and global vars
session_name($core_config['php']['session_name']);
session_start();


// SECURITY CHECK --------------------------------------------------------
if (!isset($_SESSION['am_maintainer']) && $core_config['am']['identity_creation_type'] != 1 && $core_config['am']['identity_creation_type'] != 2) {
	exit;
}


// ERROR HANDLING
// this is accessed and updated with all errors thoughtout this build
// processing regularly checks if empty before continuing
$GLOBALS['am_error_log'] = array();


// SETUP DATABASE ------------------------------------------------------
require_once('core/class/Db.class.php');
$db = new Database($core_config['db']);


// SETUP TEMPLATE -------------------------------------------
define("AM_TEMPLATE_PATH", "core/template/");
require_once('core/class/Template.class.php');
$tpl = new Template();


// SETUP LANGUAGE --------------------------------------------
if (!isset($core_config['language']['default'])) {
	die ('Default language pack not set correctly or cannot be read.');
}

define("AM_DEFAULT_LANGUAGE_CODE", $core_config['language']['default']);
define("AM_DEFAULT_LANGUAGE_PATH", "core/language/" . AM_DEFAULT_LANGUAGE_CODE . "/");
setlocale(LC_ALL, $core_config['language']['pack'][AM_DEFAULT_LANGUAGE_CODE]);


$lang = array();

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php');
}
else {
	die ('Default language pack not set correctly.');
}

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'core.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'core.lang.php');
}

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'register.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'register.lang.php');
}

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'identity_field_options.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'identity_field_options.lang.php');
}


if (isset($_POST['reject_terms'])) {
	$tpl->set('stage', 1);
}
elseif (isset($_POST['accept_terms'])) {
	$tpl->set('stage', 2);
}
elseif(isset($_POST['check_username'])) {
	$username = trim($_POST['username']);

	if (empty($username)) {
		$GLOBALS['am_error_log'][] = array('identity name empty');
	}
	else {
		$username = formatIdentityName($username);
	}

	if (empty($GLOBALS['am_error_log'])) {
		if (testIdentityName($db, $username, $core_config['am']['reserved_identity_names'])) {

			$tpl->set("name_ok", 1);

			$lang['register_username_available'] = str_replace("SYS_KEYWORD_NAME", $username, $lang['register_username_available']);
		}
		else {
			$tpl->set("name_in_use", 1);

			$lang['register_username_taken'] = str_replace("SYS_KEYWORD_NAME", $username, $lang['register_username_taken']);
		}
	}

	$tpl->set('stage', 2);
}
elseif (isset($_POST['choose_again'])) {
	$tpl->set('stage', 2);
}
elseif (isset($_POST['choose_username'])) {
	$tpl->set('stage', 3);

	$username = trim($_POST['username']);
	$openid = str_replace('REPLACE', $username, $core_config['am']['domain_replace_pattern']);
	$tpl->set('username', $username);
	$tpl->set('openid', $openid);
}
elseif (isset($_POST['register_password'])) {

	if ($_POST['pass1'] != $_POST['pass2']) {
        $GLOBALS['am_error_log'][] = array($lang['error']['password_not_match'] );
    }

    if (strlen($_POST['pass1']) < 2) {
        $GLOBALS['am_error_log'][] = array($lang['error']['password_short']);
    }
    
    if (empty($GLOBALS['am_error_log'])) {
    	$tpl->set('stage', 4);
    	$maptcha = gen_maptcha();
    }
    else {
    	$tpl->set('stage', 3);
    }
    
    $username = trim($_POST['username']);
	$openid = str_replace('REPLACE', $username, $core_config['am']['domain_replace_pattern']);
	$tpl->set('username', $username);
	$tpl->set('openid', $openid);
}
elseif (isset($_POST['register_challange'])) {

	if (!match_maptcha($_POST['maptcha_text'])) {
		$GLOBALS['am_error_log'][] = array($lang['error']['captcha_not_match']);
		$maptcha = gen_maptcha();
	}

	if (empty($GLOBALS['am_error_log'])) {
		$username = trim($_POST['username']);
		$password = md5(trim($_POST['pass1']));

		$rec = array();
		$rec['identity_name'] = $username;
		$rec['identity_create_datetime'] = time();
		$rec['status_id'] = $core_config['am']['identity_creation_type'];
		$rec['identity_password'] = $password;
		
		if (!empty($_POST['identity_email'])) {
			$rec['identity_email'] = $_POST['identity_email'];
		}

		$table = $db->prefix . "_identity";

		$db->insertDb($rec, $table);

		$identity_id = $db->insertID();

		// insert connection
		$rec = array();
		$rec['identity_id'] = $identity_id;
		$rec['connection_openid'] = str_replace('REPLACE', $username, $core_config['am']['domain_replace_pattern']);
		$rec['connection_openid'] = rtrim($rec['connection_openid'], '/');
		$rec['connection_total'] = 1;
		$rec['connection_create_datetime'] = time();
		$rec['connection_last_datetime'] = time();

		$table = $db->prefix . '_connection';

		$db->insertDb($rec, $table);

		$connection_id = $db->insertID();

		if (!empty($connection_id)) {

			$query = "
				UPDATE " . $db->prefix . "_identity
				SET owner_connection_id=" . $connection_id . "
				WHERE identity_id=" . $identity_id
			;

			$db->Execute($query);
			
			$_SESSION['connection_id'] = $connection_id;
			$_SESSION['identity_id'] = $identity_id;
		}

		$tpl->set('stage', 5);
	}
	else {
		$tpl->set('stage', 4);
	}

	$username = trim($_POST['username']);
	$openid = str_replace('REPLACE', $username, $core_config['am']['domain_replace_pattern']);
	$tpl->set('username', $username);
	$tpl->set('openid', $openid);
	$_SESSION['username'] = $username;
}
elseif (isset($_POST['save_profile_information'])) {
	$tpl->set('stage', 6);
	
	$username = trim($_POST['username']);
	$openid = str_replace('REPLACE', $username, $core_config['am']['domain_replace_pattern']);
	$tpl->set('username', $username);
	$tpl->set('openid', $openid);
	
	if (!empty($_POST['identity'])) {
		
		foreach($_POST['identity'] as $key => $value):
			if (!empty($value)) {
				$rec = array();
				$rec['connection_id'] = $_SESSION['connection_id'];
				$rec['attribute_name'] = $core_config['sreg2ax'][$key];
				$rec['attribute_value'] = $value;

				$table = $db->prefix . '_connection_attribute';

				$db->insertDb($rec, $table);
			}
		endforeach;
		
		if (empty($_POST['identity']['nickname'])) {
			// Insert nickname as attribute
			$rec = array();
			$rec['connection_id'] = $_SESSION['connection_id'];
			$rec['attribute_name'] = "namePerson/friendly";
			$rec['attribute_value'] = $_SESSION['username'];

			$table = $db->prefix . '_connection_attribute';

			$db->insertDb($rec, $table);
		}
		
		if (isset($_FILES['frm_file']) && !empty($_FILES['frm_file']['tmp_name'])) {
		
			define ("AM_IDENTITY_ID", $_SESSION['identity_id']);
		
			// SETUP IMAGE ----------------------------------------------
			include_once('core/class/Image.class.php');
			$image = new Image($core_config['file']);
			$image->path = 'avatars/' . AM_IDENTITY_ID;
			$image->db = &$db;
			$image->is_avatar = 1;
			
			$image->uploadImage();

			$current_avatar_name = str_replace('REPLACE', $_SESSION['username'], $core_config['am']['domain_replace_pattern']) . '/core/get_file.php?avatar=' . AM_IDENTITY_ID . '/' . $image->filename;
			
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
			
			$table = $db->prefix . '_connection_attribute';
			
			$rec = array();
			$rec['connection_id'] = $_SESSION['connection_id'];
			$rec['attribute_value'] = $current_avatar_name;
			$rec['attribute_name'] = 'media/image/aspect11';
			$rec['level_id'] = 0;
			
			$db->insertDb($rec, $table);
		}
		// IF we have default connection, we include them here.
		if (is_file ('core/inc/default_connections.inc.php')) {
			include_once 'core/inc/default_connections.inc.php';
			if (isset($default_connections) && !empty($default_connections)) {
				foreach($default_connections as $key => $val):
					$rec = array();
					$rec['identity_id'] = $_SESSION['identity_id'];
					$rec['connection_openid'] = $val['connection_openid'];
					$rec['connection_create_datetime'] = time ();
					$rec['connection_last_datetime'] = time ();
					$rec['connection_total'] = 1;
					$rec['status_id'] = 2;
					
					$table = $db->prefix . '_connection';
					
					$db->insertDb($rec, $table);
					$tmp_connection_id = $db->insertID();
					
					foreach($val as $rkey => $rval):
						if ($rkey != 'connection_openid') {
							$rec = array();
							$rec['connection_id'] = $tmp_connection_id;
							$rec['attribute_name'] = $rkey;
							$rec['attribute_value'] = $rval;
							$rec['level_id'] = 0;
							
							$table = $db->prefix . '_connection_attribute';
							
							$db->insertDb($rec, $table);
						}
					endforeach;
				endforeach;
			}
		}
		
		// If an account email has been set, we now send out the 
		// welcome email.
		$query = "
			SELECT identity_email
			FROM " . $db->prefix . "_identity
			WHERE identity_id=" . $_SESSION['identity_id']
		;
		
		$result = $db->Execute($query);
		
		if (isset($result[0]['identity_email'])) {
		
			$key = md5 (time());
			
			$prefix = 'http://';
			if (isset($_SERVER['HTTPS'])) {
				if (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1) {
					$prefix = 'https://';
				}
			}
			
			$openid_identity = $prefix . $_SESSION['username'] . '.' . rtrim($_SERVER['SERVER_NAME'], '/') . '/';
		
			// setup mail
			require_once('core/class/Mail/class.phpmailer.php');
			$mail->From = $core_config['mail']['email_address'];
			
			// email, subject, message
			$email_subject = $lang['register_welcome'];
					
			$mail->Subject = $email_subject;
			
			
			$email_message = str_replace("SYS_KEYWORD_OPENID", $openid_identity, $lang['register_welcome_message']);

			$email_message_html = $email_message;
			$email_message_text = strip_tags($email_message);
			
			// HTML-version of the mail
			$html  = "<HTML><HEAD><TITLE></TITLE></HEAD>";
			$html .= "<BODY>";
			$html .= utf8_decode($email_message_html);
			$html .= "</BODY></HTML>";
					
			$mail->Body = $html;
			// non - HTML-version of the email
			$mail->AltBody   = utf8_decode($email_message_text);
			$mail->AddAddress($result[0]['identity_email']);
			
			$mail->Send();
		}
	}
}
else {
	$tpl->set('stage', 1);
}

$tpl->lang = $lang;
$tpl->set('domain_replace_pattern', $core_config['am']['domain_replace_pattern']);
$tpl->set('sreg', $core_config['openid_extension']['sreg']);
$tpl->set('sreg2ax', $core_config['sreg2ax']);

if (isset($maptcha)) {
	$tpl->set('maptcha', $maptcha);
}

echo $tpl->fetch(AM_TEMPLATE_PATH . 'register.tpl.php');


function formatIdentityName($id) {
	$id = strtolower($id);
	global $lang;

	if (!preg_match('/^[a-zA-Z0-9.~]+$/', $id)) {
		$GLOBALS['am_error_log'][] = array($lang['error']['identity_not_ok']);
	}

	if (strlen($id) < 2) {
        $GLOBALS['am_error_log'][] = array($lang['error']['identity_short']);
    }

	if (strlen($id) > 30) { // link too long
		$GLOBALS['am_error_log'][] = array($lang['error']['identity_long']);
	}
	return $id;
}


function testIdentityName ($db, $name, $excluded) {
	// create excluded array
	$excluded = explode(',',$excluded);

	foreach($excluded as $key => $i):
		$excluded[$key] = trim($i);
	endforeach;

	// Test that name is not in excluded list
	if (in_array($name, $excluded)) {
		$GLOBALS['am_error_log'][] = array($lang['error']['identity_reserved']);
		return 0;

	}

	// Test the name is not already in use
	$query = "
		SELECT identity_name
		FROM " . $db->prefix . "_identity
		WHERE
		identity_name=" . $db->qstr($name)
	;

	$result = $db->Execute($query);

	if (!empty($result)) {
		return 0;
	}
	else {
		return 1;
	}
}

?>