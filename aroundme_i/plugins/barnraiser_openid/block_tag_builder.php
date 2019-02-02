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


include ("../../core/config/core.config.php");
include ("../../core/inc/functions.inc.php");


// START SESSION -----------------------------------------------------------
session_name($core_config['php']['session_name']);
session_start();


// SETUP STORAGE ---------------------------------------------------------------------------
require_once('../../core/class/Db.class.php');
$db = new Database($core_config['db']);


// SETUP IDENTITY --------------------------------------------
require_once('../../core/class/Identity.class.php');
$identity = new Identity($db);


// OBTAIN IDENTITY ACCOUNT NAME
$identity->identity_name = $identity->getIdentityName($core_config['am']['domain_preg_pattern']);

if (!empty($identity->identity_name)) {
	define('AM_IDENTITY_NAME', $identity->identity_name);
	
	$output_identity = $identity->selIdentity();

	if (!empty($output_identity)) {
		// SETUP TEMPLATE -------------------------------------------
		define("AM_TEMPLATE_PATH", "template/");
		
		require_once('../../core/class/Template.class.php');
		$tpl = new Template();
		
		
		// SETUP LANGUAGE ------------------------------------------------------
		define("AM_DEFAULT_LANGUAGE_CODE", $core_config['language']['default']);
	
		if (isset($output_identity['language_code']) && $output_identity['language_code'] != AM_DEFAULT_LANGUAGE_CODE) {
			define("AM_LANGUAGE_CODE", $output_identity['language_code']);
		}
	
		// set locale
		if (defined('AM_LANGUAGE_CODE') && array_key_exists(AM_LANGUAGE_CODE, $core_config['language']['pack'])) {
			setlocale(LC_ALL, $core_config['language']['pack'][AM_LANGUAGE_CODE]);
		}
		else {
			setlocale(LC_ALL, $core_config['language']['pack'][AM_DEFAULT_LANGUAGE_CODE]);
		}

		$lang = array();

		if (is_readable('language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_manage.lang.php')) {
			include_once('language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_manage.lang.php');
		}
	
		// we overwrite any default array keys with the webspace language keys
		if (defined('AM_LANGUAGE_CODE')) {
			if (is_readable('language/' . AM_LANGUAGE_CODE . '/plugin_manage.lang.php')) {
				include_once('language/' . AM_LANGUAGE_CODE . '/plugin_manage.lang.php');
			}
		}

		$tpl->lang = $lang;

		echo $tpl->fetch(AM_TEMPLATE_PATH . 'inc/tag_builder.inc.tpl.php');
	}
}

?>