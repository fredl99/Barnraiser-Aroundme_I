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


if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {

	if (isset($_POST['send_verify_email'])) {
		$_POST['identity_email'] = trim($_POST['identity_email']);
	
		if (!empty($_POST['identity_email'])) {
	
			$query = "
				UPDATE " . $am_core->prefix . "_identity
				SET identity_email=" . $am_core->qstr(trim($_POST['identity_email'])) . ",
				identity_email_verified=0,
				identity_email_key=''
				WHERE identity_id=" . AM_IDENTITY_ID
			;
			
			$am_core->Execute($query);
			$output_identity['identity_email'] = trim($_POST['identity_email']);
		}
	
		// send email with link to key to activate email.
		
		$key = md5 (time());
		$url_key = $output_identity['identity_openid'] . '/verify_email.php?key=' . $key;
		
		$query = "
			UPDATE " . $am_core->prefix . "_identity
			SET identity_email_key=" . $am_core->qstr($key) . "
			WHERE identity_id=" . AM_IDENTITY_ID
		;
		
		$am_core->Execute($query);
		
		// setup mail
		require_once('core/class/Mail/class.phpmailer.php');
		$mail->From = $core_config['mail']['email_address'];
		
		// email, subject, message
		$email_subject = $lang['core_account_email_subject'];
				
		$mail->Subject = $email_subject;
				
		$email_message = str_replace("SYS_KEYWORD_OPENID", $_SESSION['openid_identity'], $lang['core_account_email_message']);
		$email_message = str_replace("SYS_KEYWORD_URL", $url_key, $email_message);
		
		// HTML-version of the mail
		$html  = "<HTML><HEAD><TITLE></TITLE></HEAD>";
		$html .= "<BODY>";
		$html .= utf8_decode(nl2br($email_message));
		$html .= "</BODY></HTML>";
			
		$mail->Body = $html;

		$email_message = strip_tags($email_message);
		
		// non - HTML-version of the email
		$mail->AltBody   = utf8_decode($email_message);
		$mail->AddAddress($output_identity['identity_email']);
		
		if($mail->Send()) {
			// sent
			$body->set('email_sent', 1);
		}
	}

	// CUSTOMIZE LANGUAGE
	$openid_account = $_SERVER['SERVER_NAME'];
	$lang['core_identity_intro'] = str_replace('SYS_KEYWORD_OPENID', $openid_account, $lang['core_identity_intro']);
	
	$identity_attributes = $identity->selConnectionAttributes(AM_OWNER_CONNECTION_ID);
	
	if (!empty($identity_attributes)) {
		$body->set('identity_attributes', $identity_attributes);
	}
	
	$body->set('config_identity_attributes', $core_config['identity_attribute']);

	// GATHER STATISTICS
	$output_statistics = array();

	$output_statistics['total_inbound_connections'] = 0;
	
	$query = "SELECT count(connection_id) as total FROM " . $am_core->prefix . "_connection WHERE identity_id=" . AM_IDENTITY_ID;

	$result = $am_core->Execute($query);
	
	if (isset($result[0]['total'])) {
		$output_statistics['total_inbound_connections'] = $result[0]['total'];
	}

	$output_statistics['total_inbound_connections_trusted'] = 0;
	
	$query = "SELECT count(connection_id) as total FROM " . $am_core->prefix . "_connection WHERE connection_trusted=1 AND identity_id=" . AM_IDENTITY_ID;

	$result = $am_core->Execute($query);
	
	if (isset($result[0]['total'])) {
		$output_statistics['total_inbound_connections_trusted'] = $result[0]['total'];
	}

	$output_statistics['total_outbound_connections'] = 0;
	
	$query = "SELECT count(site_id) as total FROM " . $am_core->prefix . "_site WHERE identity_id=" . AM_IDENTITY_ID;

	$result = $am_core->Execute($query);
	
	if (isset($result[0]['total'])) {
		$output_statistics['total_outbound_connections'] = $result[0]['total'];
	}
	
	$output_statistics['total_outbound_connections_trusted'] = 0;
	
	$query = "SELECT count(site_id) as total FROM " . $am_core->prefix . "_site WHERE site_trusted=1 AND identity_id=" . AM_IDENTITY_ID;

	$result = $am_core->Execute($query);
	
	if (isset($result[0]['total'])) {
		$output_statistics['total_outbound_connections_trusted'] = $result[0]['total'];
	}

	// get plugin statistics
	foreach (glob('plugins/*/inc/statistics.inc.php') as $i):
		require($i);
	endforeach;

	$body->set('statistics', $output_statistics);
}
else {
	header("Location: index.php");
	exit;
}

?>