<?php

// ---------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2007 Barnraiser
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

$sreg2ax = $core_config['sreg2ax'];
$ax2sreg = $core_config['ax2sreg'];

require_once ('core/class/OpenidConsumer.class.php');

$openid_consumer = new OpenidConsumer;
$openid_consumer->required_fields = array('nickname');
$openid_consumer->optional_fields = array('fullname', 'email', 'dob', 'postcode', 'country', 'timezone', 'language'); // add to optional fields and required fields
$openid_consumer->optional_fields[] = 'avatar';

if (isset($_POST['login'])) {

	$query = "
		SELECT i.identity_id, i.owner_connection_id 
		FROM " . $am_core->prefix . "_identity i
		WHERE i.identity_name=" . $am_core->qstr($_POST['username']) . "
		AND i.identity_password=" . $am_core->qstr(md5($_POST['passwd']))
	;
	
	$result = $am_core->Execute($query);
	
	if (!empty($result)) { // verify password
		$_SESSION['connection_id'] = $result[0]['owner_connection_id'];
		$_SESSION['openid_identity'] = $openid_consumer->normalize('http://' . $_SERVER['SERVER_NAME']);
		
		// Set stuff from identity_attribute
		$query = "
			SELECT ca.attribute_name, ca.attribute_value, ca.level_id
			FROM " . $am_core->prefix . "_connection_attribute ca
			WHERE ca.connection_id=" . $result[0]['owner_connection_id']
		;
		
		$result = $am_core->Execute($query);
		
		if (!empty($result)) {
			$output_identity_attribute = $result;
		}
		
		if (isset($output_identity_attribute) && !empty($output_identity_attribute)) {
			foreach($output_identity_attribute as $key => $val):
				if (isset($ax2sreg[$val['attribute_name']])) {
					$_SESSION['openid_' . $ax2sreg[$val['attribute_name']]] = $val['attribute_value']; // map to sreg format
				}
			endforeach;
		}
		
		if (isset($output_webspace)) {
			$log_entry = array();
			$log_entry['title'] = 'someone connected';
			$log_entry['body'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . '</a> connected.';
			$log_entry['link'] = $_SESSION['openid_identity'];
			$ws->appendLog($log_entry);
		}

		header('Location: index.php?t=identity');
		exit;
	}
	else {
		// log error here
		$GLOBALS['am_error_log'][] = array('Your password appears incorrect');
		$_REQUEST['t'] = 'login'; //still display the login-screen
	}
}
elseif (isset($_POST['connect'])) { // we connect

	$_POST['openid_login'] = trim($_POST['openid_login']);
	$_POST['openid_login'] = $openid_consumer->normalize($_POST['openid_login']);
	
	unset($_SESSION['openid_login']);
	$_SESSION['openid_login'] = $_POST['openid_login'];

	if ($_POST['openid_login'] == $openid_consumer->normalize('http://' . $_SERVER['SERVER_NAME'])) {
		//local login
		$_REQUEST['t'] = 'login'; // we display login-box
	}
	else {
		
// 		$openid_consumer->required_fields = array('nickname');
// 		$openid_consumer->optional_fields = array('fullname', 'email', 'dob', 'postcode', 'country', 'timezone', 'language'); // add to optional fields and required fields
// 		$openid_consumer->optional_fields[] = 'avatar';
		
		if ($openid_consumer->discover($_POST['openid_login'])) { // we did discover a server
			if($openid_consumer->associate()) { // association is ok
				$openid_consumer->checkid_setup(); // do the setup
			}
			else {
				$GLOBALS['am_error_log'][] = array('Failed to associate with server');
			}
		}
		else {
			$GLOBALS['am_error_log'][] = array('Failed to localize openid server');
		}
	}
}
elseif (isset($_GET['openid_mode']) && $_GET['openid_mode'] == 'id_res') { // we get data back from the server
	if ($openid_consumer->id_res()) { // was the result ok?
		
		// SET CONNECTION
		// Create session vars
		if (isset($_GET['openid_sreg_nickname'])) {
			$_SESSION['openid_nickname'] = $_GET['openid_sreg_nickname'];
		}
		
		//$_SESSION['openid_identity'] = $_GET['openid_identity'];

		$openid = $_GET['openid_identity'];
			
		if(substr($openid,-1,1) == '/'){
			$openid = substr($openid, 0, strlen($openid)-1);
		}
			
		$_SESSION['openid_identity'] = $openid;


		if (!empty($_GET['openid_sreg_email'])) {
			$_SESSION['openid_email'] = $_GET['openid_sreg_email'];
		}

		if (!empty($_GET['openid_sreg_fullname'])) {
			$_SESSION['openid_fullname'] = $_GET['openid_sreg_fullname'];
		}

		if (!empty($_GET['openid_sreg_country'])) {
			$_SESSION['openid_country'] = $_GET['openid_sreg_country'];
		}

		if (!empty($_GET['openid_sreg_language'])) {
			$_GET['openid_sreg_language'] = strtolower($_GET['openid_sreg_language']);
			$_SESSION['language_code'] = $_GET['openid_sreg_language'];
		}
		
		if (!empty($_GET['openid_sreg_avatar'])) {
			if (substr($_GET['openid_sreg_avatar'], 0,4) != "http") {
				$_GET['openid_sreg_avatar'] = $_SESSION['openid_identity'] . "/" . $_GET['openid_sreg_avatar'];
			}
			
			$_SESSION['openid_avatar'] = $_GET['openid_sreg_avatar'];
		}

		$query = "
			SELECT identity_id, connection_id, connection_openid, 
			connection_create_datetime, connection_last_datetime, 
			connection_total, status_id
			FROM " . $am_core->prefix . "_connection
			WHERE connection_openid=" . $am_core->qstr($_SESSION['openid_identity']) . " AND
			identity_id=" . $_SESSION['identity_id']
		;
		
		$result = $am_core->Execute($query, 1);
		
		if (isset($result[0])) { // I have previously connected
			if ($result[0]['status_id'] != 2) {  // 1=barred,2=active
				header("Location: index.php?t=lock");
				exit;
			}
				
			$connection = $result[0];

			$_SESSION['connection_id'] =  $connection['connection_id'];
			$_SESSION['connection_total'] =  $connection['connection_total']+1;

			// we update the connection
			$query = "
				UPDATE " . $am_core->prefix . "_connection
				SET connection_last_datetime=" . time() . ", 
				connection_total=" . $_SESSION['connection_total'] . "
				WHERE connection_id=" . $_SESSION['connection_id']
			;
			
			$am_core->Execute($query);

			if (isset($_GET['openid_sreg_nickname']) || isset($_GET['openid_sreg_email']) || isset($_GET['openid_sreg_fullname']) || isset($_GET['openid_sreg_country']) || isset($_GET['openid_sreg_language']) || isset($_GET['openid_sreg_avatar'])) {
				// we delete old attributes
				$query = "
					DELETE
					FROM " . $am_core->prefix . "_connection_attribute
					WHERE connection_id=" . $_SESSION['connection_id']
				;
			
				$am_core->Execute($query);
			}
			
			// and insert the new ones...
			$table = $am_core->prefix . '_connection_attribute';
			$rec = array();
			$rec['connection_id'] = $_SESSION['connection_id'];
			
			if (!empty($_GET['openid_sreg_nickname'])) {
				$rec['attribute_name'] = $sreg2ax['nickname'];
				$rec['attribute_value'] = $_GET['openid_sreg_nickname'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_email'])) {
				$rec['attribute_name'] = $sreg2ax['email'];
				$rec['attribute_value'] = $_GET['openid_sreg_email'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_fullname'])) {
				$rec['attribute_name'] = $sreg2ax['fullname'];
				$rec['attribute_value'] = $_GET['openid_sreg_fullname'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_country'])) {
				$rec['attribute_name'] = $sreg2ax['country'];
				$rec['attribute_value'] = $_GET['openid_sreg_country'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_language'])) {
				$rec['attribute_name'] = $sreg2ax['language'];
				$rec['attribute_value'] = $_GET['openid_sreg_language'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_timezone'])) {
				$rec['attribute_name'] = $sreg2ax['timezone'];
				$rec['attribute_value'] = $_GET['openid_sreg_timezone'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_avatar'])) {
				$rec['attribute_name'] = $sreg2ax['avatar'];
				$rec['attribute_value'] = $_GET['openid_sreg_avatar'];
					
				$am_core->insertDb($rec, $table);
			}
		}
		elseif (!empty($output_webspace['webspace_locked'])) { // locked space
			header('location: index.php?t=lock');
			exit;
		}
		else { // webspace virgin - We insert them
			
			$table = $am_core->prefix . '_connection';
			$rec = array();
			$rec['identity_id'] = $_SESSION['identity_id'];
			$rec['connection_openid'] = $_SESSION['openid_identity'];
			$rec['connection_create_datetime'] = time();
			$rec['connection_last_datetime'] = time();
			$rec['connection_total'] = 1;
			$rec['status_id'] = 2;

			$am_core->insertDB($rec, $table);

			$_SESSION['connection_id'] = $am_core->insertID();
			
			// insert attributes here
			$table = $am_core->prefix . '_connection_attribute';
			$rec = array();
			$rec['connection_id'] = $_SESSION['connection_id'];
			
			if (!empty($_GET['openid_sreg_nickname'])) {
				$rec['attribute_name'] = $sreg2ax['nickname'];
				$rec['attribute_value'] = $_GET['openid_sreg_nickname'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_email'])) {
				$rec['attribute_name'] = $sreg2ax['email'];
				$rec['attribute_value'] = $_GET['openid_sreg_email'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_fullname'])) {
				$rec['attribute_name'] = $sreg2ax['fullname'];
				$rec['attribute_value'] = $_GET['openid_sreg_fullname'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_country'])) {
				$rec['attribute_name'] = $sreg2ax['country'];
				$rec['attribute_value'] = $_GET['openid_sreg_country'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_language'])) {
				$rec['attribute_name'] = $sreg2ax['language'];
				$rec['attribute_value'] = $_GET['openid_sreg_language'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_timezone'])) {
				$rec['attribute_name'] = $sreg2ax['timezone'];
				$rec['attribute_value'] = $_GET['openid_sreg_timezone'];
					
				$am_core->insertDb($rec, $table);
			}
			
			if (!empty($_GET['openid_sreg_avatar'])) {
				$rec['attribute_name'] = $sreg2ax['avatar'];
				$rec['attribute_value'] = $_GET['openid_sreg_avatar'];
					
				$am_core->insertDb($rec, $table);
			}
		}

		// append log
		if (!empty($_SESSION['connection_id'])) {
			if (isset($output_webspace)) {
				$log_entry = array();
				$log_entry['title'] = 'someone connected';
				$log_entry['body'] = '<a href="' . $_SESSION['openid_identity'] . '">' . $_SESSION['openid_nickname'] . '</a> connected.';
				$log_entry['link'] = $_SESSION['openid_identity'];
				$ws->appendLog($log_entry);
			}
			
			// check if required fields are set ------------------------------------------
			// if not we request them from the login form --------------------------------
			if (!empty($openid_consumer->required_fields)) {
				foreach ($openid_consumer->required_fields as $key => $i):
					if (empty($_GET['openid_sreg_' . $i])) {
						if (!empty($_GET['openid_return_to'])) {
							header("Location: index.php?t=connect&no_sreg=1&return_to=" . urlencode($_GET['openid_return_to']));
						}
						else {
							header("Location: index.php?t=connect&no_sreg=1");
						}
						exit;
					}
				endforeach;
			}
		}
		
		if (!empty($_GET['openid_return_to'])) {
			header("Location: " . $_GET['openid_return_to']);
			exit;
		}
	}
	else {
		// error-log here
	}
	unset($_SESSION['openid_login']);
}
$output_openid['server'] = $openid_consumer->server_url();
$output_openid['delegate'] = $openid_consumer->server_url();

if (preg_match($core_config['am']['domain_preg_pattern'], $_SERVER['SERVER_NAME'], $matches)) {
	$body->set('identity_name', $matches[1]);
}


$body->set('sreg2ax', $sreg2ax);
$body->set('ax2sreg', $ax2sreg);
$tpl->set('openid', $output_openid);

?>