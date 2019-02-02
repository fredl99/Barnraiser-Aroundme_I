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

$contact_msg = "";

include ("../../core/config/core.config.php");
include ("../../core/inc/functions.inc.php");


// START SESSION
session_name($core_config['php']['session_name']);
session_start();


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('../../core/class/Db.class.php');
$am_core = new Database($core_config['db']);


// obtain the owner email address
require_once('../../core/class/Identity.class.php');
$identity = new Identity($am_core);

if (isset($_POST['send_email']) && isset($_SESSION['connection_id'])) {
	
	
	
	// OBTAIN IDENTITY ACCOUNT NAME
	$identity->identity_name = $identity->getIdentityName($core_config['am']['domain_preg_pattern']);
	
	if (!empty($identity->identity_name)) {
		define('AM_IDENTITY_NAME', $identity->identity_name);
		
		$output_identity = $identity->selIdentity();

		if (!empty($output_identity)) {
			$owner_attributes = $identity->selConnectionAttributes($output_identity['owner_connection_id']);

			if (!empty($owner_attributes['contact/email'])) {

				if (!empty($owner_attributes['namePerson/friendly'])) {
					$nickname = $owner_attributes['namePerson/friendly'];
				}
				else {
					$nickname = $identity->identity_name;
				}
				
				// setup mail
				require_once('../../core/class/Mail/class.phpmailer.php');
				$mail = new PHPMailer();
				$mail->Host = 		$core_config['mail']['host'];
				$mail->Port = 		$core_config['mail']['port'];
				$mail->Mailer = 	$core_config['mail']['mailer'];
				
				if (isset($core_config['mail']['smtp']['username'])) {
					$mail->SMTPAuth = true;
					$mail->Username = $core_config['mail']['smtp']['username'];
					$mail->Password = $core_config['mail']['smtp']['password'];
				}
						
				$mail->From = 		$core_config['mail']['email_address'];
				$mail->AddReplyTo	($_POST['email'], $nickname);
				$mail->FromName = 	$_SESSION['openid_nickname'];
				$mail->WordWrap = 	$core_config['mail']['wordwrap'];
				$mail->Priority = 			3;
				$mail->Encoding = 			"8bit";
				$mail->CharSet = 			"iso-8859-1";
				$mail->SMTPKeepAlive =      true;
				$mail->IsHTML(true);

	
	
				// email, subject, message
				$email_subject = stripslashes(htmlspecialchars($_POST['subject']));
				
				$mail->Subject = $email_subject;
				
				$email_message = stripslashes(htmlspecialchars($_POST['message']));
				
				$email_message .= "\n\nThis mail was sent from your OpenID account from " . $_SESSION['openid_identity'];
				
				
				// HTML-version of the mail
				$html  = "<HTML><HEAD><TITLE></TITLE></HEAD>";
				$html .= "<BODY>";
				$html .= utf8_decode(nl2br($email_message));
				$html .= "</BODY></HTML>";
				
				$mail->Body = $html;
				// non - HTML-version of the email
				$mail->AltBody   = utf8_decode($email_message);
				
				$mail->ClearAddresses();
				$mail->AddAddress($owner_attributes['contact/email'], '');
				
				if($mail->Send()) {
					// sent
					$contact_msg = 1;
				}
			}
		}
	}
}

if (isset($contact_msg)) {
	header("Location: " . $_SERVER['HTTP_REFERER'] . "?contact_msg=1");
}
else {
	header("Location: " . $_SERVER['HTTP_REFERER']);
}
exit;

?>