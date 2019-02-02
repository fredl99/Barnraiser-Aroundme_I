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


// PHP ERROR REPORTING -----------------------------------------------
//error_reporting(E_ALL); // error handling in development environment.
error_reporting(0);	// error handling in production environment


// RELEASE NOTES --------------------------------------------------
$core_config['release']['version'] = 					"1.2";
$core_config['release']['release_date'] = 				"04-01-2008"; // MM-DD-YYYY
$core_config['release']['install_date'] = 				"";


//DATABASE CONFIGURATION -------------------------------------------------
$core_config['db']['host'] = "localhost";
$core_config['db']['user'] = "root";
$core_config['db']['pass'] = "";
$core_config['db']['db'] = "";
$core_config['db']['prefix'] =	 						"am";
$core_config['db']['collate'] =	 						""; // utf8_swedish_ci


// LANGUAGE CONFIGURATION -----------------------------------------------------
// debian note: go to aptitude and install -language-pack-*-base, the restart webserver
// locale -a to display list of installed packs. Key entries must be lowercase
$core_config['language']['pack']['en'] = 			"en_US";
// default language key
$core_config['language']['default'] = 				"en";


// AROUNDMe CONFIGURATION ---------------------------------------------
$core_config['am']['domain_preg_pattern'] = 			"/(.*?)\.localhost/";
$core_config['am']['domain_replace_pattern'] = 			"http://REPLACE.localhost";
// debug level
$core_config['db']['am']['debug_level'] = 				0;
// allow people to create identities (maintainers always have ability to create)
// 0=maintainer only, 2=automatic (1=approval_required in future release)
$core_config['am']['identity_creation_type'] = 			2;
// list reserved webspace names
$core_config['am']['reserved_identity_names'] = 		"www, ftp, mail";
// default permission setting for new webspace
$core_config['am']['webspace_default_permission'] = 	1;
// we can access the maintainer.php page?
$core_config['maintainer']['username'] = 				"";
$core_config['maintainer']['password'] = 				"";


// OpenID CONFIGURATION ----------------------------------------------------
$core_config['identity_attribute']['namePerson/friendly'] = 		"text";
$core_config['identity_attribute']['namePerson'] = 					"text";
$core_config['identity_attribute']['birthDate'] = 					"text";
$core_config['identity_attribute']['company/name'] = 				"text";
$core_config['identity_attribute']['company/title'] = 				"text";
$core_config['identity_attribute']['contact/phone/default'] = 		"text";
$core_config['identity_attribute']['contact/phone/home'] = 			"text";
$core_config['identity_attribute']['contact/phone/business'] = 		"text";
$core_config['identity_attribute']['contact/phone/cell'] = 			"text";
$core_config['identity_attribute']['contact/phone/fax'] = 			"text";
$core_config['identity_attribute']['contact/postalAddress/home'] = 	"textarea";
$core_config['identity_attribute']['contact/city/home'] = 			"text";
$core_config['identity_attribute']['contact/state/home'] = 			"text";
$core_config['identity_attribute']['contact/country/home'] = 		"text";
$core_config['identity_attribute']['contact/postalCode/home'] = 	"text";
$core_config['identity_attribute']['contact/postalAddress/business'] = "textarea";
$core_config['identity_attribute']['contact/city/business'] = 		"text";
$core_config['identity_attribute']['contact/state/business'] = 		"text";
$core_config['identity_attribute']['contact/country/business'] = 	"text";
$core_config['identity_attribute']['contact/postalCode/business'] = "text";
$core_config['identity_attribute']['contact/email'] = 				"text";
$core_config['identity_attribute']['contact/IM/AIM'] = 				"text";
$core_config['identity_attribute']['contact/IM/ICQ'] = 				"text";
$core_config['identity_attribute']['contact/IM/MSN'] = 				"text";
$core_config['identity_attribute']['contact/IM/Yahoo'] = 			"text";
$core_config['identity_attribute']['contact/IM/Jabber'] = 			"text";
$core_config['identity_attribute']['contact/IM/Skype'] = 			"text";
$core_config['identity_attribute']['contact/web/default'] = 		"text";
$core_config['identity_attribute']['contact/web/blog'] = 			"text";
$core_config['identity_attribute']['media/image/aspect11'] = 		"hidden";
$core_config['identity_attribute']['pref/language'] = 				"select";
$core_config['identity_attribute']['pref/timezone'] = 				"select";
$core_config['identity_attribute']['person/gender'] = 				"text";

$core_config['openid_extension']['sreg']['nickname'] = 				$core_config['identity_attribute']['namePerson/friendly'];
$core_config['openid_extension']['sreg']['fullname'] =				$core_config['identity_attribute']['namePerson'];
$core_config['openid_extension']['sreg']['email'] = 				$core_config['identity_attribute']['contact/email'];
$core_config['openid_extension']['sreg']['postcode'] = 				$core_config['identity_attribute']['contact/postalCode/home'];
$core_config['openid_extension']['sreg']['country'] = 				$core_config['identity_attribute']['contact/country/home'];
$core_config['openid_extension']['sreg']['timezone'] = 				$core_config['identity_attribute']['pref/timezone'];
$core_config['openid_extension']['sreg']['language'] = 				$core_config['identity_attribute']['pref/language'];
$core_config['openid_extension']['sreg']['dob'] =					$core_config['identity_attribute']['birthDate'];
$core_config['openid_extension']['sreg']['gender'] =				$core_config['identity_attribute']['person/gender'];

$core_config['ax2sreg']['namePerson/friendly'] = 					'nickname';
$core_config['ax2sreg']['namePerson'] = 							'fullname';
$core_config['ax2sreg']['contact/email'] = 							'email';
$core_config['ax2sreg']['birthDate'] = 								'dob';
$core_config['ax2sreg']['contact/postalCode/home'] = 				'postcode';
$core_config['ax2sreg']['contact/country/home'] = 					'country';
$core_config['ax2sreg']['pref/timezone'] = 							'timezone';
$core_config['ax2sreg']['pref/language'] = 							'language';
$core_config['ax2sreg']['media/image/aspect11'] = 					'avatar';
$core_config['sreg2ax']['nickname'] = 								'namePerson/friendly';
$core_config['sreg2ax']['fullname'] = 								'namePerson';
$core_config['sreg2ax']['email'] = 									'contact/email';
$core_config['sreg2ax']['dob'] = 									'birthDate';
$core_config['sreg2ax']['postcode'] = 								'contact/postalCode/home';
$core_config['sreg2ax']['country'] = 								'contact/country/home';
$core_config['sreg2ax']['timezone'] = 								'pref/timezone';
$core_config['sreg2ax']['language'] = 								'pref/language';
$core_config['sreg2ax']['avatar'] = 								'media/image/aspect11';


// EMAIL CONFIGURATION ---------------------------------------
$core_config['mail']['host'] = "";
$core_config['mail']['port'] = 							25;
$core_config['mail']['email_address'] = 				"you@your-domain.org";
$core_config['mail']['mailer'] = 						"smtp";
$core_config['mail']['from_name'] = 					"service name";
$core_config['mail']['wordwrap'] = 						80;
//if you need a username and password to access SMTP then uncomment these
// and add your username and password
//$mail_config['mail']['smtp']['username'] = 			"your_mailserver_username";
//$mail_config['mail']['smtp']['password'] = 			"your_mailserver_password";


// FILE CONFIGURATION ----------------------------------------------------
$core_config['file']['default_allocation'] = 			200;
$core_config['file']['dir'] =							"../asset/";


// DISPLAY CONFIGURATION ---------------------------------------------------
$core_config['display']['max_list_rows'] = 				50;


// PHP CONFIGURATION -----------------------------------------------------
// PHP keeps data in a session. The session is called "PHPSESSID" as standard. If you
// have more than one instance of this software you should create a unique session name.
// recomended is characters A-Z (uppercase),0-9 with no spaces. DO NOT use a dot (.).
$core_config['php']['session_name'] = 					"PHPSESSIDAMI";

// tokens that are not accepted by the editor
$core_config['invalid_tokens'][] = 						'exec';
$core_config['invalid_tokens'][] = 						'passthru';
$core_config['invalid_tokens'][] = 						'shell_exec';
$core_config['invalid_tokens'][] = 						'system';
$core_config['invalid_tokens'][] = 						'proc_terminate';
$core_config['invalid_tokens'][] = 						'proc_open';
$core_config['invalid_tokens'][] = 						'time';


// END OF CONFIG FILE ----------------------------------------------------

?>