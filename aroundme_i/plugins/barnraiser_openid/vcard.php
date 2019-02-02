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


include '../../core/config/core.config.php';
include '../../core/class/Db.class.php';

// SESSION HANDLER ----------------------------------------------------------------------------
// sets up all session and global vars 
session_name($core_config['php']['session_name']);
session_start();

// add stuff to this array to extend vcard.
$card_transforms = array();
$card_transforms['namePerson'] = 'FN';
$card_transforms['contact/postalAddress/home'] = 'ADR';
$card_transforms['birthDate'] = 'BDAY';


$am_core = new Database($core_config['db']);

$query = "
	SELECT connection_id, attribute_name, 
	attribute_value, level_id
	FROM " . $am_core->prefix . "_connection_attribute
	INNER JOIN " . $am_core->prefix . "_identity
	ON owner_connection_id=connection_id
	WHERE identity_id=" . $_SESSION['identity_id']
;

$result = $am_core->Execute($query);

$vcard = "BEGIN:vcard\n";
$vcard .= "VERSION:3.0\n";

if (!empty($result)) {
	foreach($result as $key => $i):
		foreach($card_transforms as $ikey => $ii):
			if ($i['attribute_name'] == $ikey) {
				$vcard .= $ii . ":" . strip_tags($i['attribute_value']) . "\n";
			}
		endforeach;
	endforeach;
}

$vcard .= "URL:http://" . $_SERVER['SERVER_NAME'] . "\n";
$vcard .= "END:vcard\n";

header("Content-type: text/directory");
header("Content-Disposition: attachment; filename=vcard.vcf");
header("Pragma: public");
print $vcard;

?>

