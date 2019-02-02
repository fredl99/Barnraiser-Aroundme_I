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

$lang['installer_start'] = 									"AROUNDMe identity server installer";
$lang['installer_start_intro'] = 							"This installer will install version 'AM_SYS_KEYWORD_VERSION' of Barnraisers AROUNDMe identity server. The installation process contains a few simple steps; setup your domain, configure your MySQL database, add a maintainer account and setup. After setup you will be taken to the maintainers area where you can proceed to create your first identity account.";
$lang['installer_system_check'] = 							"system check";
$lang['installer_fail'] = 									"FAILED";
$lang['installer_pass'] = 									"PASSED";
$lang['installer_start_installation'] = 					"start installation";

$lang['installer_configure_database'] = 					"Configure database";
$lang['installer_database_host'] = 							"Host";
$lang['installer_database_host_example'] = 					"Example: localhost";
$lang['installer_database_name'] = 							"Database name";
$lang['installer_database_name_example'] = 					"Example: aroundme_identity_server";
$lang['installer_database_create'] = 						"create database";

$lang['installer_setup_domain'] =							"Setup domain";
$lang['installer_setup_domain_intro'] =						"The domain you try to install at is ";
$lang['installer_setup_domain_identity_intro'] =			"This implies your identity urls will look something like this";
$lang['installer_setup_domain_correct'] =					"Is this correct?";
$lang['installer_setup_domain_yes'] =						"yes";
$lang['installer_setup_domain_no'] =						"no";
$lang['installer_setup_domain_example'] =					"Give an example of how an identity should look like.";

$lang['installer_maintainer'] = 							"Maintainer";
$lang['installer_maintainer_intro'] = 						"Set up the master administrator ('the maintainer') username and password.";
$lang['installer_verify_password'] = 						"Verify password";
$lang['installer_setup_maintainer'] = 						"setup maintainer";
$lang['installer_setup_email'] = 							"Setup email";
$lang['installer_default_email_hint'] =						"This is the default address from which emails are sent.";
$lang['installer_smtp_user_pass_hint'] =					"If you need a username and password to access SMTP type them above otherwise leave them empty.";
$lang['installer_email_default'] =							"email";
$lang['installer_email_host'] = 							"host";
$lang['installer_email_host_hint'] = 						"This is your SMTP server. Look in your email preferences and see what the address of the server used to send your emails is.";

$lang['installer_setup_registration'] = 					"Setup registration";
$lang['installer_setup_account_all'] = 						"Anyone can create an OpenID account and automatically start using it.";
$lang['installer_setup_account_maintainer'] = 				"Only the maintainer can create an OpenID account.";

$lang['installer_perform_intall'] = 						"perform installation";

$lang['installer_chmod_error'] =							"The installer did not manage to change permission to this file. You need to manually change permissons to unreadable to the file <b>installer.php</b>. After that you will be able to access the maintain area by following this link <a href=\"maintain.php?install=complete\">maintain.php?install=complete</a>";

$lang['error']['username_not_set'] = 						"Maintainer username not set";
$lang['error']['password_not_set'] = 						"Maintainer password not set";
$lang['error']['password_not_verified'] =					"Maintainer password not verified";
$lang['error']['installer_host_empty'] =					"Host is not set";
$lang['error']['installer_user_empty'] =					"Username is not set";
$lang['error']['installer_db_empty'] =						"Database is not set";

// AM SYSTEM CHECKS
$lang['arr_am_sys_check']['php_mysql_exists']['name'] =		"PHP MySQL exists";
$lang['arr_am_sys_check']['php_mysql_exists']['error'] =	"AROUNDMe identity server needs MySQL. Please add curl to PHP";
$lang['arr_am_sys_check']['php_version']['name'] =			"PHP version > 5.0";
$lang['arr_am_sys_check']['php_version']['error'] =			"AROUNDMe identity server needs PHP 5.0 or greater. Your PHP version is ";
$lang['arr_am_sys_check']['curl_exists']['name'] =			"CURL exists";
$lang['arr_am_sys_check']['curl_exists']['error'] =			"AROUNDMe identity server needs curl. Please add CURL to PHP";
$lang['arr_am_sys_check']['bcmath_exists']['name'] =		"BCMath exists";
$lang['arr_am_sys_check']['bcmath_exists']['error'] =		"AROUNDMe identity server needs MySQL. Please add MySQL to PHP";
$lang['arr_am_sys_check']['gd_version']['name'] =			"GD library version > 2.0";
$lang['arr_am_sys_check']['gd_version']['error'] =			"AROUNDMe identity server needs GD library. Please add GD library to PHP";
$lang['arr_am_sys_check']['directory_structure']['name'] =	"Directory structure";
$lang['arr_am_sys_check']['directory_structure']['error'] =	"Directory structure not intact. You need to upload the entire release directory structure";
$lang['arr_am_sys_check']['config_writable']['name'] =		"Config file writable";
$lang['arr_am_sys_check']['config_writable']['error'] =		"AROUNDMe identity server cannot write to its config file. Please check your permissions";

?>