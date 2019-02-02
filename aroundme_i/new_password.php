<?php

// ---------------------------------------------------------------------------------------------
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
// ---------------------------------------------------------------------------------------------

include_once ("core/config/core.config.php");
include_once ("core/inc/functions.inc.php");


// SESSION HANDLER ----------------------------------------------------------------------------
// sets up all session and global vars 
session_name($core_config['php']['session_name']);
session_start();


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('core/class/Db.class.php');
$am_core = new Database($core_config['db']);


// SETUP TEMPLATES ---------------------------------------------------------------------------
define("AM_TEMPLATE_PATH", "core/template/");
require_once('core/class/Template.class.php');
$tpl = new Template(); // outer template

// SETUP LANGUAGE --------------------------------------------
if (!isset($core_config['language']['default'])) {
	die ('Default language pack not set correctly.');
}

// we check to see if the webspace language is not the same as the default
define("AM_DEFAULT_LANGUAGE_CODE", $core_config['language']['default']);
define("AM_DEFAULT_LANGUAGE_PATH", "core/language/" . AM_DEFAULT_LANGUAGE_CODE . "/");

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php');
}

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'new_password.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'new_password.lang.php');
}

// SETUP IDENTITY --------------------------------------------
require_once('core/class/Identity.class.php');
$identity = new Identity($am_core);

$identity->identity_name = $identity->getIdentityName($core_config['am']['domain_preg_pattern']);

if (!empty($identity->identity_name)) {
	$output_identity = $identity->selIdentity();
	
	if (!empty($output_identity['identity_email_verified'])) {
		$tpl->set('display', 'new_password_step_1');
	}
	else {
		$tpl->set('display', 'email_not_verified');
	}
}

if (isset($_POST['request_email'])) {
	$identity_new_password_key = md5(time ());
	$url_key = $output_identity['identity_openid'] . '/new_password.php?request_email_key=' . $identity_new_password_key;

	$query = "
		UPDATE " . $am_core->prefix . "_identity
		SET identity_new_password_key=" . $am_core->qstr($identity_new_password_key) . "
		WHERE identity_id=" . $output_identity['identity_id']
	;
	
	$am_core->Execute($query);
	
	// send the email with the new key
	// setup mail
	require_once('core/class/Mail/class.phpmailer.php');
	
	// email, subject, message
	$email_subject = $lang['new_password_email_subject'];
	
	$mail->Subject = $email_subject;
	
	$email_message = str_replace("SYS_KEYWORD_IDENTITY_OPENID", $output_identity['identity_openid'], $lang['new_password_email_1_message']);
	$email_message = str_replace("SYS_KEYWORD_LINK", $url_key, $email_message);
	
	// HTML-version of the mail
	$html  = "<HTML><HEAD><TITLE></TITLE></HEAD>";
	$html .= "<BODY>";
	$html .= utf8_decode(nl2br($email_message));
	$html .= "</BODY></HTML>";
	
	$mail->Body = $html;
	// non - HTML-version of the email
	$mail->AltBody = utf8_decode(strip_tags($email_message));
	$mail->AddAddress($output_identity['identity_email']);
		
	if($mail->Send()) {
		// sent
		$tpl->set('email_sent', 1);
	}
	
}
elseif (isset($_GET['request_email_key'])) {

	$tpl->set('display', 'new_password_step_2');

	$query = "
		SELECT identity_new_password_key
		FROM " . $am_core->prefix . "_identity
		WHERE  identity_id=" . $output_identity['identity_id'] . "
		AND identity_new_password_key=" . $am_core->qstr($_GET['request_email_key'])
	;
	
	$result = $am_core->Execute($query);
	
	$new_password = substr(md5(time()), 0, 5);
	
	if (isset($result[0]['identity_new_password_key'])) {
		$query = "
			UPDATE " . $am_core->prefix . "_identity
			SET identity_password=" . $am_core->qstr(md5($new_password)) . "
			WHERE identity_id=" . $output_identity['identity_id']
		;
		//echo $query; exit;
		$am_core->Execute($query);
		
		// send email with new password
		// send the email with the new key
		// setup mail
		require_once('core/class/Mail/class.phpmailer.php');
	
		// email, subject, message
		$email_subject = $lang['new_password_email_subject'];
		
		$mail->Subject = $email_subject;
		
		$email_message = str_replace("SYS_KEYWORD_IDENTITY_OPENID", $output_identity['identity_openid'], $lang['new_password_email_2_message']);
		$email_message = str_replace("SYS_KEYWORD_PASSWORD", $new_password, $email_message);
	
		// HTML-version of the mail
		$html  = "<HTML><HEAD><TITLE></TITLE></HEAD>";
		$html .= "<BODY>";
		$html .= utf8_decode(nl2br($email_message));
		$html .= "</BODY></HTML>";
	
		$mail->Body = $html;
		// non - HTML-version of the email
		$mail->AltBody   = utf8_decode(strip_tags($email_message));
		$mail->AddAddress($output_identity['identity_email']);
		
		if($mail->Send()) {
			// sent
			$tpl->set('new_password', 1);
		}
		
	}
}

$tpl->lang = $lang;
echo $tpl->fetch(AM_TEMPLATE_PATH . 'new_password.tpl.php');

?>