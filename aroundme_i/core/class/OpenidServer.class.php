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

include_once 'OpenidCommon.class.php';

class OpenidServer extends OpenidCommon {

	function OpenidServer($core_config = null) {
		if (DEBUG) {
			$this->_debug();
		}
		
		if (isset($_SESSION['logged_in'])) {
			$this->login_ok = "1";
		}
		
		if (isset($core_config)) {
			$this->core_config = $core_config;
		}
		parent::OpenidCommon($core_config);
	}

	// see 8.1 of specification
	function associate() {
	
		$data_to_send = array();
		
		if (!empty($_POST['openid_ns']) && $_POST['openid_ns'] == 'http://specs.openid.net/auth/2.0') {
			$this->openid_version = 2;
			$data_to_send['ns'] = 'http://specs.openid.net/auth/2.0';
		}
		
		if (empty($_POST['openid_session_type'])) {
			$data_to_send['session_type'] = 'no-encryption';
		}
		else {
			$data_to_send['session_type'] = $_POST['openid_session_type'];
		}
		
		if (empty($_POST['openid_assoc_type'])) {
			$this->association_type = $_POST['openid_assoc_type'];
			$data_to_send['assoc_type'] = $_POST['openid_assoc_type'];
		}
		else {
			$this->association_type = 'HMAC-SHA1';
			$data_to_send['assoc_type'] = 'HMAC-SHA1';
		}
		
		$data_to_send['assoc_handle'] = $this->assoc_handle();
		$data_to_send['expires_in'] = OPENID_EXPIRES_IN;
		
		if ($data_to_send['session_type'] != 'no-encryption') {
		
			if (!empty($_POST['openid_dh_modulus'])) {
				// we decode openid_dh_modulus to 'a real' integer
				$this->_openid_dh_modulus = $this->btwocDecode(base64_decode($_POST['openid_dh_modulus']));
			}
			else {
				$this->_openid_dh_modulus = OPENID_DH_MODULUS;
			}
			
			if (!empty($_POST['openid_dh_gen'])) {
				$this->_openid_dh_gen = $this->btwocDecode(base64_decode($_POST['openid_dh_gen']));
			}
			else {
				$this->_openid_dh_gen = OPENID_DH_GEN;
			}
		
			$data_to_send['dh_server_public'] = $this->dh_public();
			$data_to_send['enc_mac_key'] = $this->enc_mac_key($_POST['openid_dh_consumer_public']);
		}
		else {
			$data_to_send['mac_key'] = $this->mac_key();
		}

		$this->storage->writeSession($data_to_send);
		$this->direct_response($data_to_send);
		return 1;
	}
	
	// see section 10 of specification
	function checkid_setup($type = null) {
	
		if (isset($type)) {
			
			if (isset($_POST['remember_me']) && !empty($_POST['remember_me'])) {
				$_SESSION['remember_me'] = 1;
			}
			
			if (isset($_POST['login'])) {
				if ($this->storage->selLogin($_POST['username'], $_POST['passwd'])) {
					$_SESSION['logged_in'] = 1;
					$this->login_ok = "1";
					if ($this->is_trusted()) {
						$trust = 1;
						// here we need to fetch the data that we sent before
						$openid_trusted_root = isset($_GET['openid_trust_root']) ? $this->normalize($_GET['openid_trust_root']) : $this->normalize($_GET['openid_return_to']);
						$data_to_send = $this->storage->selDataToSend($openid_trusted_root);
					}
				}
				else {
					$GLOBALS['am_error_log'][] = array('The password was incorrect.');
				}
			}
			elseif (isset($_POST['trust']) && !$this->is_trusted()) {
				$trust = 1;
				
				$openid_sreg_required = isset($_GET['openid_sreg_required']) ? explode(',', $_GET['openid_sreg_required']) : 0;
				$openid_sreg_optional = isset($_GET['openid_sreg_optional']) ? explode(',', $_GET['openid_sreg_optional']) : 0;
				
				if ($openid_sreg_required) {
					foreach($openid_sreg_required as $key => $v) {
						if (isset($_POST['checkbox_' . $v]) && !empty($_POST['checkbox_' . $v])) {
							if (isset($_POST[$v]) && !empty($_POST[$v]) && $_POST[$v] != '0') {
								$data_to_send['openid.sreg.' . $v] = $_POST[$v];
							}
							else {
								$GLOBALS['am_error_log'][] = array('Required field not set. Please fill in the field.', $v);
								unset($trust);
							}
						}
						else {
							unset($trust);
						}
					}
				}
				
				if ($openid_sreg_optional) {
					foreach($openid_sreg_optional as $key => $v) {
						if (isset($_POST['checkbox_' . $v]) && !empty($_POST['checkbox_' . $v])) {
							if (isset($_POST[$v]) && !empty($_POST[$v]) && $_POST[$v] != '0') {
								$data_to_send['openid.sreg.' . $v] = $_POST[$v];
							}
							else {
								$GLOBALS['am_error_log'][] = array('Optional field not set. Please either fill in the field or uncheck it so that nothing is sent.', $v);
								unset($trust);
							}
						}
					}
				}
				
				if (isset($trust)) {
					if (isset($data_to_send)) {
						$this->trust(isset($_POST['trust_always']), $data_to_send);
					}
					else {
						$this->trust(isset($_POST['trust_always']));
					}
				}
			}
			elseif ($this->is_trusted()) {
				$trust = 1;
				// here we need to fetch the data that we sent before
				$openid_trusted_root = isset($_GET['openid_trust_root']) ? $this->normalize($_GET['openid_trust_root']) : $this->normalize($_GET['openid_return_to']);
				$data_to_send = $this->storage->selDataToSend($openid_trusted_root);
			}
		}
		elseif ($this->is_trusted()) {
			$trust = 1;
			// here we need to fetch the data that we sent before
			$openid_trusted_root = isset($_GET['openid_trust_root']) ? $this->normalize($_GET['openid_trust_root']) : $this->normalize($_GET['openid_return_to']);
			$data_to_send = $this->storage->selDataToSend($openid_trusted_root);
		}

		if (!empty($_SESSION['logged_in']) && isset($trust)) {
			
			$openid_identity = isset($_GET['openid_identity']) ? $_GET['openid_identity'] : '';
			$openid_return_to = isset($_GET['openid_return_to']) ? $_GET['openid_return_to'] : '';
			
			if (!empty($_GET['openid_ns']) && $_GET['openid_ns'] == 'http://specs.openid.net/auth/2.0') {
				$data_to_send['openid.ns'] = 'http://specs.openid.net/auth/2.0';
				$this->openid_version = 2;
			}
			
			$data_to_send['openid.mode'] = 'id_res';
			
			if (isset($this->openid_version) && $this->openid_version == 2) {
				$data_to_send['openid.op_endpoint'] = $this->server_url();
				$data_to_send['openid.claimed_id'] = $openid_identity;
				$data_to_send['openid.response_nonce'] = date('Y-m-d') . 'T' . date('H:i:s') . 'ZUNIQUE';
			}
			
			$data_to_send['openid.identity'] = $openid_identity;
			$data_to_send['openid.return_to'] = $openid_return_to;
			
			if (!empty($_GET['openid_assoc_handle'])) {
				$data_to_send['openid.assoc_handle'] = $_GET['openid_assoc_handle'];
			}
			else {
				// we had to do this for bloggr. is ok?
				$data_to_send['openid.assoc_handle'] = $this->assoc_handle();
			}
			
			$signed = '';
			foreach($data_to_send as $key => $v) {
				$signed .= substr($key, 7) . ',';
			}
			
			$data_to_send['openid.signed'] = $signed . 'signed';
	
			$tokens = '';
			foreach($data_to_send as $key => $value) {
				$tokens .= substr($key, 7) . ':' . $value . "\n";
			}
			
			$openid_session = $this->storage->selSession($data_to_send['openid.assoc_handle']);
			
			if (isset($openid_session['assoc_handle'])) {
				$this->association_type = $openid_session['assoc_type'];
			}
			
			$data_to_send['openid.sig'] = base64_encode($this->hmac($data_to_send['openid.assoc_handle'], $tokens));
		
			$s = '?';
			if (strpos($openid_return_to, $s)) {
				$s = '&';
			}
		
			if (!empty($_SESSION['remember_me'])) {
				session_destroy ();
				unset ($_SESSION);
			}
		
			// send us back to the consumer
			header('location: ' . $openid_return_to . $s . http_build_query($data_to_send));
			exit;
		}
	}
	
	// this method is not yet implemented.
	function check_authentication() {
		unset($_SESSION);
		session_destroy();
		header('Content-Type: text/plain; charset=UTF-8');
		echo 'is_valid:true'; exit; // this is a hack...
	}
	
	// see section 9.3 of specification
	function checkid_immediate() {
		
	}
	
	// see section 5.1.2 of specification
	function direct_response($data) {
		header('Content-Type: text/plain; charset=UTF-8');
		foreach($data as $key => $value) {
 			echo $key . ':' . $value . "\n";
		}
		if (DEBUG) {
			$this->_debug($data);
		}
 		exit;
	}
	
	// creates a random key
	// see section 8.2.1 of specification
	// this whole thing should be rewritten.
	// instead of using this as the 'secret', we should init a new session poitning to this.
	// the session then owns a 'real secret' of 20 chars.
	// that is, assoc_handle (is a session) -> (decides) secret
	// in that way we can use many connections at the same time.
	function assoc_handle() {
		
		unset($_SESSION['openid_assoc_handle']);
		
		switch($this->association_type) {
			case 'HMAC-SHA256':
				$limit = 32;
			break;
			case 'HMAC-SHA1':
				$limit = 20;
			break;
			default:
				$limit = 20;
		}

		$_SESSION['openid_assoc_handle'] = "";
		for($i = 0; $i < $limit; $i++){
			$_SESSION['openid_assoc_handle'] .= chr(rand(97, 122));
		}

		return $_SESSION['openid_assoc_handle'];
	}
	
	// generates the enc_mac_key.
	// decodes $n to 'real integer' and then calculates g^xy mod p (= (g^y mod p)^x mod p), where
	// x= secret at server and y=secret at consumer.
	// Uses that number and the assoc_handle to calculate the enc_mac_key.
	// method was moced from OpenidCommon to OpenidServer 070815
	function enc_mac_key($n) {
	
		switch($this->association_type) {
			case 'HMAC-SHA256':
				$hash_function = 'sha256';
			break;
			case 'HMAC-SHA1':
				$hash_function = 'sha1';
			break;
			default:
				$hash_function = 'sha1';
		}
		$y = $this->btwocDecode(base64_decode($n));
		$x = bcpowmod($y, $_SESSION['openid_secret_key'], $this->_openid_dh_modulus);

		$enc_mac_key = base64_encode($this->_xor(hash($hash_function, $this->btwocEncode($x), true), $_SESSION['openid_assoc_handle']));
		return $enc_mac_key;
	}
	
	function mac_key() {
		$mac_key = base64_encode($_SESSION['openid_assoc_handle']);
		return $mac_key;
	}
	
	// checks if the site you are entring already is trusted
	function is_trusted() {
		$openid_trusted_root = isset($_GET['openid_trust_root']) ? $this->normalize($_GET['openid_trust_root']) : $this->normalize($_GET['openid_return_to']);

		return $this->storage->selIsTrusted($openid_trusted_root);
	}
	
	// makes the trust.
	function trust($always = 0/* assume we only trust this for this session */, $trusted_data_arr=null) {
	
		$openid_trusted_root = isset($_GET['openid_trust_root']) ? $this->normalize($_GET['openid_trust_root']) : $this->normalize($_GET['openid_return_to']);
		
		// we need to use curl instead of file_get_contents because of https://pibb.com
		$openid_trusted_root_title = $this->_send(array(), 'POST', $openid_trusted_root);

		if (isset($openid_trusted_root_title)) {
		
			if (preg_match("/<link rel=\"openid.server\" href=\"(.*?)\"/", $openid_trusted_root_title, $matches)) { // is this a 'human'?
				$openid_is_human = "1";
			}
			else {
				$openid_is_human = "0";
			}
		
			if (preg_match("/<title>(.*?)<\/title>/s", $openid_trusted_root_title, $matches)) {
				$openid_trusted_root_title = $matches[1];
			}
			else {
				$openid_trusted_root_title = 'no title';
			}
		}
		$data = array();
		$data['always'] = $always;
		$data['openid_trusted_root_title'] = $openid_trusted_root_title;
		$data['openid_trusted_root'] = $openid_trusted_root;
		$data['openid_is_human'] = $openid_is_human;
		
		if (isset($trusted_data_arr)) {
			$data['trusted_data_arr'] = $trusted_data_arr;
		}
		
		$this->storage->saveTrust($data);
	}
}

?>
