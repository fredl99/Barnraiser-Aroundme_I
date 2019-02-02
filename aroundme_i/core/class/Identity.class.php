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

class Identity {

	// sebastian öblom, 13:th december 2007
	function Identity($db) {
		$this->db = $db;
	}
	
	function selIdentity() {
		$query = "
			SELECT i.identity_id, i.identity_name, i.identity_create_datetime, i.identity_email, i.identity_email_verified,
			i.status_id, i.owner_connection_id, i.language_code, w.webspace_id
			FROM " . $this->db->prefix . "_identity i 
			LEFT JOIN " . $this->db->prefix . "_webspace w ON i.identity_id=w.identity_id 
			WHERE i.identity_name=" . $this->db->qstr($this->identity_name)
		;
		
		$result = $this->db->Execute($query);
		
		if (isset($result[0]) && !empty($result[0])) {
			$result[0]['identity_openid'] = rtrim('http://' . $_SERVER['SERVER_NAME'], '/');
			$this->identity_id = $result[0]['identity_id'];
			$_SESSION['identity_id'] = $this->identity_id;
			return $result[0];
		}
		else {
			return 0;
		}
	}
	
	function getIdentityName($pattern) {

		preg_match ($pattern, $_SERVER['HTTP_HOST'], $matches);

		if (!empty($matches[1])) {
			return $matches[1];
		}
	}
	
	function selConnectionAttributes($connection_id, $level_id = null) {
		
		$query = "
			SELECT attribute_name, attribute_value, level_id 
			FROM " . $this->db->prefix . "_connection_attribute  
			WHERE 
			connection_id=" . $connection_id
		;

		if (isset($level_id)) {
			$query .= " AND level_id>=" . $level_id;
		}
		
		$result = $this->db->Execute($query);
		
		if (!empty($result)) {
			$attributes = array();
				
			foreach($result as $key => $i):
				$attributes[$i['attribute_name']] = $i['attribute_value'];
			endforeach;
			
			return $attributes;
		}
	}

	function appendLog ($log_entry) {

		$log_body = trim($log_entry['body']);

		if (!empty($log_body)) {

			$rec = array();
 			$rec['identity_id'] = $_SESSION['identity_id'];
			$rec['log_title'] = $log_entry['title'];
			$rec['log_body'] = $log_body;
			$rec['log_link'] = $log_entry['link'];
			$rec['log_create_datetime'] = time();

			$table = $this->db->prefix . "_log";

			$this->db->insertDb($rec, $table);
		}
	}
}

?>