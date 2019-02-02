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

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'identity_field_options.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'identity_field_options.lang.php');
}

if (defined('AM_LANGUAGE_CODE') && is_readable(AM_LANGUAGE_PATH . 'identity_field_options.lang.php')) {
	include_once(AM_LANGUAGE_PATH . 'identity_field_options.lang.php');
}

class Plugin_barnraiser_openid {
	// storage and template instances should be passed by reference to this class
	
	var $level = 0; // the permission level requied to see an item
	var $attributes; // any block attributes passed to the class


	function block_connect () {
		if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
			// later we should fetch new connections and list some kind of summary here
			$this->am_template->set('is_me', 1);
		}
		elseif (isset($_SESSION['openid_identity']) && !empty($_SESSION['openid_identity'])) {
				
			$query = "
				SELECT *
				FROM " . $this->am_core->prefix . "_connection
				WHERE identity_id=" . AM_IDENTITY_ID . " AND
				connection_openid LIKE " . $this->am_core->qstr($_SESSION['openid_identity'])
			;
			
			$inbound_connection = $this->am_core->Execute($query);
			
			$query = "
				SELECT *
				FROM " . $this->am_core->prefix . "_site
				WHERE identity_id=" . AM_IDENTITY_ID
			;
			
			$outbound_connection = $this->am_core->Execute($query);

			$relation = "You have connected to " . AM_IDENTITY_NAME . " " . $inbound_connection[0]['connection_total'] . " times.";
			
			$this->am_template->set('relation', $relation);
		}
	}
	
	function block_card () {
		// level_id=64 = everyone
		// level_id=32 = connections
		// level_id=16 = friends
		// level_id=0 = only me

		$level_id = 64;

		if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
			$level_id = 16;
		}
		elseif (isset($_SESSION['trusted_connection'])) {
			$level_id = 16;
		}
		elseif (isset($_SESSION['connection_id'])) {
			$level_id = 32;
		}
		
		$identity_attributes = $this->am_identity->selConnectionAttributes(AM_OWNER_CONNECTION_ID, $level_id);
		
		if (!empty($identity_attributes)) {
			$this->am_template->set('barnraiser_openid_card_identity_attributes', $identity_attributes);
		}
	}
}

$plugin_barnraiser_openid = new Plugin_barnraiser_openid();
$plugin_barnraiser_openid->am_core = &$am_core;
$plugin_barnraiser_openid->am_identity = &$identity;
$plugin_barnraiser_openid->am_template = &$body;

$body->set('config_identity_attributes', $core_config['identity_attribute']);

?>