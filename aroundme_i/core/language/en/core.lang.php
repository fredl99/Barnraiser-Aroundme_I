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

// STATISTICS
$lang['arr_statistics']['total_inbound_connections'] = "Total inbound connections";
$lang['arr_statistics']['total_inbound_connections_trusted'] = "Total trusted inbound connections";
$lang['arr_statistics']['total_outbound_connections'] = "Total outbound connections";
$lang['arr_statistics']['total_outbound_connections_trusted'] = "Total outbound trusted connections";


// WEBSPACE
$lang['core_webspace_unclaimed'] = 							"The identity of 'AM_KEYWORD_IDENTITY' is unclaimed.";
$lang['core_webspace_claim'] = 								"claim this openid";
$lang['core_welcome_title'] = 								"Welcome";
$lang['core_welcome_intro'] = 								"This is an <a href='http://www.openid.net/'>OpenID</a> based identity service. This service is made with <a href='http://www.barnraiser.org/'>AROUNDMe identity server</a> which is free software from which you can install and create your own identity service. If you find this useful please consider supporting us by donating from the <a href='http://www.barnraiser.org/'>Barnraiser</a> website.";
$lang['core_openid_title'] = 								"What is OpenID?";
$lang['core_openid_intro'] = 								"Ever get bored with filling in registration forms? Ever lost your username or password for a website? If you think about it it's the wrong way around. It's like having a separate ID card for every shop when in reality we just have one ID card that works everywhere. OpenID gives you just that; a way to login to many websites using a single identity with a single password. You control what identity information you give to the website and you control which websites you trust and which you don't - OpenID puts you in control with a single password to remember. OpenID is an open specification for the web maintained by the OpenID foundation.";
$lang['core_identity_page_intro'] = 						"This is the identity page for '<b>AM_KEYWORD_IDENTITY</b>'";
$lang['core_service_title'] = 								"What is this service?";
$lang['core_service_intro'] = 								"This is an <a href='http://www.openid.net/'>OpenID</a> based identity service. This service is made with <a href='http://www.barnraiser.org/'>AROUNDMe identity server</a> which is free software from which you can install and create your own identity service. If you find this useful please consider supporting us by donating from the <a href='http://www.barnraiser.org/'>Barnraiser</a> website.";
$lang['core_identity_title'] = 								"OpenID";


// WEBPAGE
$lang['core_webpage_edit_title'] = 							"Edit your webpage";
$lang['core_body'] = 										"body";
$lang['core_webpage_goto'] = 								"view webpage";
$lang['core_helper_add_plugin'] = 							"Add a plugin item";
$lang['core_helper_add_link'] = 							"Add links to webpages";
$lang['core_helper_add_layout'] = 							"Add a webpage layout";
$lang['core_helper_add_file'] = 							"Add a picture";
$lang['core_webpage_layouts_title'] = 						"Webpage layouts";
$lang['core_webpage_layouts_intro'] = 						"Select the layout that you want to use in your webpage.";
$lang['core_webpage_layouts_instruction'] = 				"Copy and paste the code below into your webpage.";
$lang['core_webpage_tag_builder_title'] = 					"Tag builder";
$lang['core_webpage_tag_builder_intro'] = 					"Copy the above tag into either a block or a webpage to activate the plugin.";
$lang['error']['body_forbidden_tokens'] = 					"The webpage body contains forbidden tokens. Please remove them";

// TRUST
$lang['core_trust_request_title'] = 						"Identity information request";
$lang['core_trust_request_intro'] = 						"The site called '<a href=\"AM_KEYWORD_TRUSTED_ROOT_URL\">AM_KEYWORD_TRUSTED_ROOT_TITLE</a>' (AM_KEYWORD_TRUSTED_ROOT_URL) has requested the following identity information from you:";
$lang['core_trust_request_no_name'] = 						"no name given";
$lang['core_save_changes'] = 								"Save changes";
$lang['core_authorize_title'] = 							"Authorize";
$lang['core_authorize_intro'] = 							"I authorize this site to use the identity information marked.";
$lang['core_authorize_trust_always'] = 						"Save this information and auto-connect to this site in the future";
$lang['core_authorize_trust_log'] = 						"Add that i have connected to my log";
$lang['core_authorize_deny'] = 								"deny";
$lang['core_authorize_allow'] = 							"ALLOW";
$lang['core_reset_trust'] =									"reset trust";

// STYLESHEET EDITOR
$lang['core_styles'] = 										"styles";
$lang['core_stylesheet'] = 									"Stylesheet";
$lang['core_simple_editor_intro'] = 						"Listed below are your stylesheets. You can set one of them to be your current webspace style.";
$lang['core_current'] = 									"Current";
$lang['core_delete_selected'] = 							"delete checked styles";
$lang['core_set_default'] = 								"set current style";
$lang['core_add_stylesheet'] = 								"Add a stylesheet";


// SETUP
$lang['core_lock_webspace'] = 								"Lock it";
$lang['txt_input_locked'] = 								"Lock this webspace so that only connections that are invited in can see it.";
$lang['core_webpages_intro'] = 								"You can copy the HTML tag into any web page to create a link to your chosen web page.";
$lang['core_webpages'] = 									"Web pages";
$lang['core_html_tag'] = 									"HTML Tag";
$lang['core_set_start_page'] = 								"set start page";
$lang['core_start'] = 										"start";
$lang['core_delete_selected'] = 							"delete selected";
$lang['core_plugin_blocks'] = 								"Plugin blocks";
$lang['core_add_custom_block'] = 							"Add custom block";
$lang['core_custom'] = 										"custom";
$lang['core_webspace_information'] =						"Webspace information";



// PROFILE
$lang['core_edit_profile'] = 								"Edit your profile";
$lang['core_avatar_intro'] = 								"These are the avatars you have available. You can check the checkboxes to delete unwanted avatars and you can click the radio button to select the avatar that you want to associate with your identity card.";
$lang['core_delete_avatar'] = 								"delete avatar";
$lang['core_set_avatar'] = 									"set as current avatar";
$lang['core_upload_avatar'] = 								"Upload an avatar";
$lang['core_upload_avatar_intro'] = 						"An avatar is a small picture of yourself which can be given to web sites so that your picture appears against your contributions. You can upload pictures and set on to be your avatar.";
$lang['core_upload_file'] = 								"Upload an avatar";
$lang['core_avatar'] = 										"Select your avatar";


// NETWORK
$lang['core_connections_outbound_intro'] = 					"You can connect to websites. These are called outbound connections. You can trust the site with the profile information so that you can auto-login in the future. If you want to change the information a website holds against you, uncheck and save the trust then log into the website again.";
$lang['core_connections_outbound'] = 						"Outbound";
$lang['core_websites'] = 									"websites";
$lang['core_trust'] = 										"trust";
$lang['core_set_trust'] = 									"set trust";
$lang['core_connections_inbound_intro'] = 					"People can connect to your webspace. These are called 'inbound connections'. You can 'trust' friends so that they can see more information in your webspace.";
$lang['core_connections_view_all'] = 						"view all active connections";
$lang['core_connections_view_trusted'] = 					"view trusted connections only";
$lang['core_connections_barr_intro'] = 						"You may want to deny someone the ability to connect to your webspace. This is called 'barring' then. Select a connection to see their details and optionally barr them.";
$lang['core_connections_barred'] = 							"view barred connections";
$lang['core_profile'] = 									"Identity profile";
$lang['core_connected_since'] = 							"Connected since";
$lang['core_connected_last'] = 								"Last connected";
$lang['core_manage'] = 										"Manage";
$lang['core_barr_connection'] = 							"Barr them";
$lang['core_trust_connection'] = 							"Trust them";
$lang['core_connections_inbound'] = 						"Inbound";
$lang['core_connections'] = 								"Connections";
$lang['core_connection'] = 									"connection";
$lang['common_openid'] =									"OpenID";

// LOGIN
$lang['core_security_intro1'] = 							"1. Check your OpenID account matches the first part of the URL given in your browser! If it does not then NEVER enter your password.";
$lang['core_security_intro2'] = 							"2. Only ever type your password into this screen. NEVER type it into any other screen! If you are asked for your OpenID password anywhere else then you risk compromising your OpenID account.";
$lang['core_security_intro3'] = 							"3. Never give your password to anyone. Even the makers of this software do not need when giving you technical support. NEVER write down or give your password away.";
$lang['core_security'] = 									"SECURITY";
$lang['core_openid_login'] = 								"OpenID login";
$lang['core_remember_me'] =									"keep me connected";
$lang['core_new_password'] =								"forgot your password? Request a new one";
$lang['error']['account_informatio_missing'] = 				"please fill in your account information: ";

// IDENTITY
$lang['core_identity_intro'] = 								"Your OpenID is <b>SYS_KEYWORD_OPENID</b>";
$lang['core_identity_profile'] = 							"Your identity profile";
$lang['core_identity'] = 									"Your identity";
$lang['core_create_webspace'] = 							"Create your webspace";
$lang['core_create_webspace_intro'] = 						"A webspace is your personal website in which you can blog, create a wall and present yourself to the world!";
$lang['core_view_inbound'] = 								"view inbound connections";
$lang['core_view_outbound'] = 								"view outbound connections";
$lang['core_statistics'] = 									"Statistics";
$lang['core_account_email'] =		 						"Account Email";
$lang['core_no_account_email'] =							"You have not given us an email yet. In case you lose you password we need to contact you to give you a new one. Therefore we strongly recommend that you to enter an email here.";
$lang['core_label_email'] =									"email";
$lang['core_update_email'] =								"update email";
$lang['core_email_verified'] =								"This email has been verified. You can change this email and verify an new one if you would like to.";
$lang['core_account_email_not_verified'] =					"This email has not yet been verified. Please verfy the email to enable the ability for you to recieve a new password from us in case you forget your old one.";
$lang['core_account_email_sent'] = 							"A new verification email was sent. Please click the link in that email to verify.";
$lang['core_account_email_send_email'] = 					"send me a verification email";
$lang['core_account_email_subject'] = 						"Verify email";
$lang['core_account_email_message'] = 						"Please click on this link: <a href='SYS_KEYWORD_URL'>SYS_KEYWORD_URL</a>\n\nThis mail was sent from your OpenID account from <a href='SYS_KEYWORD_OPENID'>SYS_KEYWORD_OPENID</a>";

// FILE
$lang['core_file_upload'] = 								"Upload a file";
$lang['core_file_upload_intro'] = 							"You can upload pictures saved in JPG, GIF or PNG format, sound files saved in MP3 format, movie files saved in MPEG format, text files or PDF files. Remember you must have permission to publish a file for which you do not hold the copyright.";
$lang['core_file_width_intro'] = 							"If you are uploading a picture you can set the width to be the width in pixels that you want the image to be";
$lang['core_width'] = 										"width";
$lang['core_pixels'] = 										"pixels";
$lang['core_file_selected_file'] = 							"Your selected file";
$lang['core_file_view_hint'] = 								"click to view img tag";
$lang['core_uploaded'] = 									"Uploaded";
$lang['core_icon'] = 										"icon";
$lang['core_list'] = 										"list";
$lang['core_type'] = 										"type";
$lang['core_upload_date'] = 								"Date uploaded";
$lang['core_file_upload_file'] = 							"file";
$lang['core_file_uploaded'] =								"uploaded";
$lang['core_click_view'] =									"click to view";
$lang['core_file_tag'] =									"tag";
$lang['core_file_view'] = 									"view";
$lang['core_files'] =										"files";

// BLOCK
$lang['core_label_block_body'] =							"block body";
$lang['core_label_block_name'] =							"block name";
$lang['core_hdr_edit_plugin'] = 							"Edit your plugin block";
$lang['core_delete_block'] =		 						"Delete block";
$lang['core_reset_block'] = 								"Reset block";
$lang['core_add_links_webpages'] = 							"Add links to webpages";
$lang['core_add_picture'] = 								"Add a picture";
$lang['error']['forbidden_tokens'] =						"'The webpage body contains forbidden tokens. Please remove them'";
$lang['error']['name_not_ok'] =								"The name can only include characters of type a-z, A-Z or 0-9";

// STYLESHEET
$lang['core_styles'] = 										"styles";

// CONNECT
$lang['core_username'] = 									"username";
$lang['core_password'] = 									"password";
$lang['core_connect'] = 									"Connect";
$lang['core_connect_intro'] = 								"Connect to contribute! To connect enter your OpenID.";

// CREATE WEBSPACE

$lang['core_layout_blog'] = 								"<h3>Blog</h3><p>A blog is a 2 column grid with the right column containing selected (or latest) blog entry. On the left is your identity card, a list of your latest blog entries and a tagcloud.</p>";
$lang['core_layout_smorgardsbord'] = 						"<h3>Smorgasbord</h3><p>A smorgasbord is a variety of things spread over a 3 column grid. In the left colum is your identity card and your activity log. In the middle column is guestbook. In the right hand column is your network.</p>";
$lang['core_layout_minimalist'] = 							"<h3>Minimalist</h3><p>A simple profile page containing only your identity card and a connect box.</p>";
$lang['core_layout_expert'] = 								"<h3>Expert mode</h3><p>A blank home page is set and no CSS added. You should be competent with both HTML and CSS to choose this option!</p>";
$lang['core_label_title'] = 								"title";
$lang['core_choose_and_feel'] = 							"Choose look and feel";
$lang['core_webspace_profile_page'] = 						"Your webspace is your profile page, or presentation. You can customise it to include all sorts of stuff including a blog and a wall. To start with pick the webspace that most closely matches what you want.";
$lang['core_choose'] = 										"choose";
$lang['core_layout'] = 										"layout";
$lang['core_title_blog'] = 									"blog";
$lang['core_title_smorgasbord'] = 							"smorgasbord";
$lang['core_title_minimalist'] = 							"minimalist";
$lang['core_title_expert'] = 								"expert";
$lang['core_style'] = 										"style";
$lang['core_style_light'] = 								"light";
$lang['core_style_dark'] = 									"dark";
$lang['error']['title_not_set'] = 							"Webspace title not set";
$lang['error']['css_not_set'] = 							"Webspace css not set";
$lang['error']['layout_not_set'] = 							"Webspace layout not set";

?>