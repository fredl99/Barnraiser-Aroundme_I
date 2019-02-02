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

// DEFAULT HTML PAGE TITLE
$lang['common_html_title'] = 								"AROUNDMe identity server";


// LANGUAGE PACK NAMES
$lang['arr_language']['en'] = 								"english";


// AM ERRORS (business logic layer)
//$lang['arr_am_error']['db_connect_error'] = 				"There is a problem with connecting to the database";
$lang['arr_am_error']['openid_discovery_failed'] = 			"Your OpenID appears to be wrong or your OpenID server is down. Check the formatting of your OpenID. They normally look something like name.service.org";
$lang['arr_am_error']['db_error'] = 						"Database error";


// AM MAIN MENU
$lang['common_am_menu_register'] = 							"Register";
$lang['common_am_menu_webspace'] = 							"Webspace";
$lang['common_am_menu_setup'] = 							"Manage";
$lang['common_am_menu_files'] = 							"Files";
$lang['common_am_menu_edit'] = 								"Edit";
$lang['common_am_menu_style'] = 							"Style";
$lang['common_am_menu_identity'] = 							"Identity";
$lang['common_am_menu_connect'] = 							"Connect";
$lang['common_am_menu_disconnect'] = 						"Disconnect";


// TEXTS
// All lowercase unless specific to brand [example: OpenID]
$lang['common_save'] = 										"save";
$lang['common_save_go'] = 									"save and go";
$lang['common_close'] = 									"close";
$lang['common_none'] = 										"none";
$lang['common_name'] = 										"name";
$lang['common_nickname'] =									"nickname";
$lang['common_preview'] = 									"preview";
$lang['common_delete'] = 									"delete";
$lang['common_title'] = 									"title";
$lang['common_go'] = 										"go";
$lang['common_upload'] = 									"upload";
$lang['common_email'] =										"email";
$lang['common_language'] =									"language";
$lang['common_webspace'] =									"webspace";
$lang['common_login'] =										"login";
$lang['common_total'] =										"total";
$lang['common_create'] =									"create";
$lang['common_size'] = 										"size";
$lang['common_edit'] = 										"edit";
$lang['common_view'] = 										"view";
$lang['common_yes'] =										"yes";
$lang['common_no'] =										"no";
$lang['common_username'] =									"username";
$lang['common_password'] = 									"password";
$lang['common_no_list_items'] = 							"Sorry, there are no items to list.";
$lang['common_update'] =									"update";


$lang['arr_identity_status'][1] = 							"pending";
$lang['arr_identity_status'][2] = 							"live";
$lang['arr_identity_status'][3] = 							"barred";


// ARRAYS
$lang['arr_level'][64] = 									"Everyone";
$lang['arr_level'][32] = 									"Connections";
$lang['arr_level'][16] = 									"Friends";
$lang['arr_level'][0] = 									"Only me";

?>