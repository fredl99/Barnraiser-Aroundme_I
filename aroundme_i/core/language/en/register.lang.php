<?php
// -----------------------------------------------------------------------
// This file is part of AROUNDMe
//
// Copyright (C) 2003 - 2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
//
// This program is free software; you can redistribute it and/or modify it
// under the terms of the GNU General Public License as published by the
// Free Software Foundation; either version 2, or (at your option) any
// later version.
//
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
// General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with program; see the file COPYING. If not, write to the Free
// Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA
// 02110-1301, USA.
// -----------------------------------------------------------------------


// REGISTER
$lang['register_create_intro'] = 								"Create your identity in 5 simple steps.";
$lang['register_create'] = 										"Create";
$lang['register_agree_terms'] = 								"Agree terms";
$lang['register_username_openid'] = 							"Username and OpenID";
$lang['register_username'] = 									"username:";
$lang['register_openid'] = 										"OpenID:";
$lang['register_choose_password'] =								"Choose password";
$lang['register_challenge_intro'] = 							"Please type the characters exactly as you see them below.  You can also use the <a href=\"core/get_captcha.php?audio=1\">Audio version</a>";
$lang['register_challenge'] =									"The challange";
$lang['register_disagree'] =									"I disagree";
$lang['register_agree'] =										"I agree";
$lang['register_username_intro'] =								"You can use any characters (a-z or A-Z) with no spaces. You can also use a dot. Many people prefer firstname.surname as their identity name. Find out if the name you want to use if free.";
$lang['register_check_availability'] =							"Check for availability";
$lang['register_username_available'] = 							"Congratulations, 'SYS_KEYWORD_NAME' is free!";
$lang['register_username_taken'] = 								"Sorry, 'SYS_KEYWORD_NAME' already is taken. Please try another name.";
$lang['register_openid_preview'] = 								"Your OpenID will be SYS_KEYWORD_OPENID";
$lang['register_choose_name'] =									"Would you like to choose it?";
$lang['register_choose_again'] =								"Choose again";
$lang['register_use_name'] =									"Use this name";
$lang['register_name_taken'] = 									"Sorry, 'SYS_KEYWORD_NAME' already is taken. Please try another name.";
$lang['register_confirm_password'] = 							"Confirm Password";
$lang['register_password_hint'] = 								"Your password must be at least 2 characters long";
$lang['register_password'] = 									"password";
$lang['register_set_password'] = 								"Set password";
$lang['register_submit_response'] =								"Submit response";
$lang['register_response'] = 									"Response";
$lang['register_finish_intro'] = 								"Congratulations! You now own this OpenID: <b><a href=\"AM_KEYWORD_OPENID\">AM_KEYWORD_OPENID</a></b>";
$lang['register_finish_intro2'] = 								"Your are done! Your OpenID is <b>AM_KEYWORD_OPENID</b>.";
$lang['register_finish'] = 										"You are done!";
$lang['register_logging_in_help1'] = 							"Click the link to visit your OpenID page.";
$lang['register_logging_in_help2'] = 							"Press 'Connect' to login.";
$lang['register_logging_in_help3'] = 							"Enter your OpenID in the box provided and press 'GO'.";
$lang['register_logging_in_help4'] = 							"Enter the password that you gave here.";
$lang['register_logging_in_help5'] = 							"Press 'go'. You will be taken to your OpenID account and asked to login (use the password that you have just typed in here). Then you will be asked what identity information you want to give to this website, then you will be logged in.";
$lang['register_logging_in'] = 									"Logging in";
$lang['register_reset_trust'] = 								"reset trust";
$lang['register_select_none'] =									"none";
$lang['register_identity_information'] = 						"Profile information";
$lang['register_identity_optional'] = 							"All options are optional, but we advise you to fill them in. No one will see them unless you specifically choose to let them see it.";
$lang['register_profile_information'] = 						"Profile information";
$lang['register_password_retrieval_email'] = 					"Password retrieval email";
$lang['register_password_retrieval_email_intro'] = 				"Please give us an email address so that we can send you a new password if you request one. This will only be used to contact you with system updates and emails you directly request.";
$lang['register_welcome'] =										"Welcome!";
$lang['register_welcome_message'] =								"<p>Welcome to this installation of AROUNDMe Identity Server!</p>\n\n<p>Your OpenID is <b>SYS_KEYWORD_OPENID</b> - hint: remember this;)</p>\n\n<p>Please verify this email address in your identity page so that you enable us to contact you if for instance, you forget your password.</p>";
$lang['register_challange_example'] = 							"example: 2 * 2 = <b>4</b> and 0 - 9 = <b>-9</b>";

// errors
$lang['error']['password_not_match'] = 							"Passwords must match.";
$lang['error']['password_short'] = 								"Password must be at least 2 characters long.";
$lang['error']['captcha_not_match'] = 							"Security code does not match image. Please try again.";
$lang['error']['identity_not_ok'] = 							"Sorry, you can only use a-z, A-Z characters, 0-9 numericals or . (dot) in your name.";
$lang['error']['identity_short'] = 								"Identity name must be at least 2 characters long.";
$lang['error']['identity_long'] = 								"Identity name can most be 30 characters long";
$lang['error']['identity_reserved'] = 							"Sorry, this identity name is reserved.";

?>