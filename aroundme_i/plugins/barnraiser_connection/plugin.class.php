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


class Plugin_barnraiser_connection {
	// storage and template instances should be passed by reference to this class
	
	var $level = 0; // the permission level requied to see an item
	var $attributes; // any block attributes passed to the class


	function block_gallery () {
		// creates avatar gallery with links directly to persons site
		
		$query = "
			SELECT connection_id, connection_openid 
			FROM " . $this->am_core->prefix . "_connection
			WHERE
			connection_id!=" . AM_OWNER_CONNECTION_ID . " AND
			identity_id=" . AM_IDENTITY_ID
		;
		
		if (isset($this->attributes['limit'])) {
			$inbound_connections = $this->am_core->Execute($query, (int) $this->attributes['limit']);
		}
		else {
			$inbound_connections = $this->am_core->Execute($query);
		}
		
		if (!empty($inbound_connections)) {
			foreach($inbound_connections as $key => $i):
				$inbound_connections[$key]['attributes'] = $this->am_identity->selConnectionAttributes($i['connection_id']);
			endforeach;
			
			$this->am_template->set('barnraiser_connection_inbound_connections', $inbound_connections);
		}
	}
	
	function block_log () {
		
		$query = "
			SELECT log_id, identity_id, log_title, 
			log_body, log_link, UNIX_TIMESTAMP(log_create_datetime) AS log_create_datetime 
			FROM " . $this->am_core->prefix . "_log
			WHERE identity_id=" . AM_IDENTITY_ID . "
			ORDER BY log_create_datetime DESC"
		;
		
		$log = $this->am_core->Execute($query);

		if (!empty($log)) {
			$this->am_template->set('connection_log', $log);
		}
	}

	function block_outbound_list () {
		// a list of recently visited people
		$query = "
			SELECT site_id, site_title as title, site_realm as realm
			FROM " . $this->am_core->prefix . "_site
			WHERE 
			identity_id=" . AM_IDENTITY_ID
		;
		
		$outbound_connections = $this->am_core->Execute($query);

		if (!empty($outbound_connections)) {
			$this->am_template->set('barnraiser_connection_outbound_connections', $outbound_connections);
		}
	}

	function block_inbound_list () {
		
		$this->block_gallery();
	}
}

$plugin_barnraiser_connection = new Plugin_barnraiser_connection();
$plugin_barnraiser_connection->am_core = &$am_core;
$plugin_barnraiser_connection->am_identity = &$identity;
$plugin_barnraiser_connection->am_template = &$body;

?>