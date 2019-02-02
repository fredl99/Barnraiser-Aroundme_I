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

// CHECK INSTALLED
if (is_readable("installer.php")) {
	header("Location: installer.php");
	exit;
}

include_once ("core/config/core.config.php");
include_once ("core/inc/functions.inc.php");


// SESSION HANDLER ----------------------------------------------------------------------------
// sets up all session and global vars 
session_name($core_config['php']['session_name']);
session_start();


// ERROR HANDLING ----------------------------------------------------------------------------
// this is accessed and updated with all errors thoughtout this build
// processing regularly checks if empty before continuing
$GLOBALS['am_error_log'] = array();


if (isset($_REQUEST['disconnect'])) {
	session_unset();
	session_destroy();
	session_write_close();
	header("Location: index.php");
	exit;
}


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require_once('core/class/Db.class.php');
$am_core = new Database($core_config['db']);


// SETUP TEMPLATES ---------------------------------------------------------------------------
define("AM_TEMPLATE_PATH", "core/template/");
require_once('core/class/Template.class.php');
$tpl = new Template(); // outer template
$body = new Template(); // inner template


// SETUP IDENTITY --------------------------------------------
require_once('core/class/Identity.class.php');
$identity = new Identity($am_core);

$identity->identity_name = $identity->getIdentityName($core_config['am']['domain_preg_pattern']);

if (!empty($identity->identity_name)) {
	$output_identity = $identity->selIdentity();
}

if (!empty($output_identity['identity_id'])) {
	define('AM_IDENTITY_ID', $output_identity['identity_id']);
	define('AM_IDENTITY_NAME', $identity->identity_name);
	define('AM_OWNER_CONNECTION_ID', $output_identity['owner_connection_id']);
}


// SETUP LANGUAGE --------------------------------------------
if (!isset($core_config['language']['default'])) {
	die ('Default language pack not set correctly.');
}

// we check to see if the webspace language is not the same as the default
define("AM_DEFAULT_LANGUAGE_CODE", $core_config['language']['default']);
define("AM_DEFAULT_LANGUAGE_PATH", "core/language/" . AM_DEFAULT_LANGUAGE_CODE . "/");

if (isset($output_identity['language_code']) && $output_identity['language_code'] != AM_DEFAULT_LANGUAGE_CODE) {
	define("AM_LANGUAGE_CODE", $output_identity['language_code']);
	define("AM_LANGUAGE_PATH", "core/language/" . AM_LANGUAGE_CODE . "/");
}

// set locale
if (defined('AM_LANGUAGE_CODE') && array_key_exists(AM_LANGUAGE_CODE, $core_config['language']['pack'])) {
	setlocale(LC_ALL, $core_config['language']['pack'][AM_LANGUAGE_CODE]);
}
else {
	setlocale(LC_ALL, $core_config['language']['pack'][AM_DEFAULT_LANGUAGE_CODE]);
}

$lang = array();

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'common.lang.php');
}
else {
	die ('Default language pack not set correctly or cannot be read.');
}

if (is_readable(AM_DEFAULT_LANGUAGE_PATH . 'identity_field_options.lang.php')) {
	include_once(AM_DEFAULT_LANGUAGE_PATH . 'identity_field_options.lang.php');
}

// we overwrite any default array keys with the webspace language keys
if (defined('AM_LANGUAGE_CODE')) {
	if (is_readable(AM_LANGUAGE_PATH . 'common.lang.php')) {
		include_once(AM_LANGUAGE_PATH . 'common.lang.php');
	}
	
	if (is_readable(AM_LANGUAGE_PATH . 'identity_field_options.lang.php')) {
		include_once(AM_LANGUAGE_PATH . 'identity_field_options.lang.php');
	}
}


// INCLUDE OPENID CONSUMER -------------------------------------------------------------------
require_once('core/inc/consumer.inc.php');


if (!empty($identity->identity_name)) {
	if (!empty($output_identity)) {
		$lang['common_html_title'] = AM_IDENTITY_NAME;
		
		if (!empty($output_identity['webspace_id'])) {
			define('AM_WEBSPACE_ID', $output_identity['webspace_id']);
			
			// SETUP WEBSPACE --------------------------------------------
			require_once('core/class/Webspace.class.php');
			$ws = new Webspace($am_core);
			
			$output_webspace = $ws->selWebSpace ();
			
			// OBTAIN STYLESHEET
			$query = "
				SELECT stylesheet_id, stylesheet_body, stylesheet_name
				FROM " . $am_core->prefix . "_stylesheet
				WHERE webspace_id=" . AM_WEBSPACE_ID
			;
		
			$output_style = $am_core->Execute($query);
		
			if (!empty($output_style)) {
				$output_webspace['webspace_css'] = $output_style[0]['stylesheet_body'];
			}
		}
		
		
		// SETUP INNER TEMPLATE ----------------------------------------------------------------------
		// An innner template can be either a user created webpage ($_REQUEST['wp']) or 
		// from a plugin template ($_REQUEST['p'] / $_REQUEST['t'] or a core template 
		// $_REQUEST['t']. First we test that the received vars are actual files:
		
		if (isset($_REQUEST['t']) && $_REQUEST['t'] != "wrapper") {
			
			if (isset($_REQUEST['p'])) {
				// a plugin template (typically the plugin admin screen)
				if (is_file('plugins/' . $_REQUEST['p'] . '/template/' . $_REQUEST['t'] . '.tpl.php')) {
		
					define("AM_SCRIPT_NAME", $_REQUEST['t']);
					define("AM_PLUGIN_NAME", $_REQUEST['p']);
				
					// load script and template
					require_once('plugins/' . AM_PLUGIN_NAME . '/' . AM_SCRIPT_NAME . '.php');
		
					$inner_template_body = file_get_contents('plugins/' . AM_PLUGIN_NAME . '/template/' . AM_SCRIPT_NAME . '.tpl.php');
				}
			}
			else {
				// a core file (typically the connect or admin screens)
				if (is_file('core/template/' . $_REQUEST['t'] . '.tpl.php')) {
					define("AM_SCRIPT_NAME", $_REQUEST['t']);
				
					// load script  and template
					require_once('core/' . AM_SCRIPT_NAME . '.php');
		
					$inner_template_body = file_get_contents('core/template/' . AM_SCRIPT_NAME . '.tpl.php');
				}
			}
		}
		elseif (defined('AM_WEBSPACE_ID')) {
			
			if (isset($_REQUEST['wp'])) {
			
				$ws->webpage_name = $_REQUEST['wp'];
				$output_webpage = $ws->selWebPage();
				
				if (!empty($output_webpage['webpage_id'])) {
					$inner_template_body = $output_webpage['webpage_body'];
					define('AM_WEBPAGE_NAME', $_REQUEST['wp']);
				}
				elseif (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
					// We are the owner so we go to create the webpage
					header("Location: index.php?t=webpage&wp=" . $_REQUEST['wp']);
					exit;	
				}
			}
			elseif (!empty($output_webspace['default_webpage_id'])) {
				$query = "
					SELECT webpage_id, webpage_body, webpage_name, webpage_create_datetime
					FROM " . $am_core->prefix . "_webpage
					WHERE webpage_id=" . $output_webspace['default_webpage_id']
				;
			
				$result = $am_core->Execute($query);
			
				if (!empty($result)) {
					$inner_template_body = $result[0]['webpage_body'];
					define('AM_WEBPAGE_NAME', $result[0]['webpage_name']);
				}
			}
		
		
		
			// GET AND FORMAT WEBPAGE ----------------------------------------------------------
			// If we have a webpage defined we get it, parse it and apply blocks to it and create a CSS
			// example: <AM_BLOCK plugin="blog" name="list" limit="6" trim="300" />
			if (defined('AM_WEBPAGE_NAME')) {
		
				// INITIATE PLUGIN CLASSES ------------------
				$plugins = $am_core->amscandir('plugins');
		
				if (!empty($plugins)) {
					foreach ($plugins as $key => $i):
						if (is_file('plugins/' . $i. '/plugin.class.php')) {
							require('plugins/' . $i. '/plugin.class.php');
						}
					endforeach;
				}
		
				// OBTAIN BLOCKS AND RUN ASSOCIATED METHODS
				$pattern = "/<AM_BLOCK(.*?)\/>/";
			
				if (preg_match_all($pattern, $inner_template_body, $plugin_blocks)) {
				
					if (!empty($plugin_blocks[1])) {
					
						foreach ($plugin_blocks[1] as $key => $i):
						
							unset($block_html);
						
							$block = array();
						
							// get attributes
							$attribute_arr = trim($i);
						
							$attribute_pattern = '/(\w+)(\s*=\s*"(.*?)"|\s*=\s*\'(.*?)\'|(\s*=\s*\w+)|())/s';
		
							if(preg_match_all($attribute_pattern, $attribute_arr, $matches, PREG_PATTERN_ORDER)) {
		
								if (!empty($matches[1])) {
									foreach ($matches[1] as $key_attr => $at):
									
										if (!empty($matches[3][$key_attr])) {
											$block[$at] = $matches[3][$key_attr];
										}
										elseif (!empty($matches[4][$key_attr])) {
											$block[$at] = $matches[4][$key_attr];
										}
										elseif (!empty($matches[5][$key_attr])) {
											$block[$at] = $matches[5][$key_attr];
										}
									endforeach;
								}
						
								if (isset($block['plugin']) && isset($block['name'])) {
						
									unset($object_name, $method_name);
							
									// We include any language pack additions
									if (is_readable('plugins/' . $block['plugin'] . '/language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_common.lang.php')) {
										include_once('plugins/' . $block['plugin'] . '/language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_common.lang.php');
									}

									if (defined('AM_LANGUAGE_CODE')) {
										if (is_readable('plugins/' . $block['plugin'] . '/language/' . AM_LANGUAGE_CODE . '/plugin_common.lang.php')) {
											include_once('plugins/' . $block['plugin'] . '/language/' . AM_LANGUAGE_CODE . '/plugin_common.lang.php');
										}
									}
									
									if ($block['plugin'] == "custom") {
										// get the block
										$stored_block = $ws->selBlock($block['plugin'], $block['name']);
									
										if (isset($stored_block['block_body'])) {
											$block_html = $stored_block['block_body'];
										}
									}
									else {
										// we attempt to run the class instance method
										$object_name = "plugin_" . $block['plugin'];
										$method_name = "block_" . $block['name'];
										
										if (class_exists($object_name) && method_exists($$object_name,$method_name)) {
											// move all block declaration attributes to the instance of the plugin class
											$$object_name->attributes = $block;
		
											// run the method
											$returned_block = $$object_name->$method_name();

											if (isset($returned_block['block_body'])) {
												// new method based block building
												$block_html = $returned_block['block_body'];
											}
											else {
												// get the block - legacy block code
												$stored_block = $ws->selBlock($block['plugin'], $block['name']);
											
												if (isset($stored_block['block_body'])) {
													$block_html = $stored_block['block_body'];
												}
												else {
													// If there is no block we look for a source block in the plugin dir
													$block_name = $block['plugin'] . '_' . $block['name'] . '.block.php';
			
													$block_html = @file_get_contents('plugins/' . $block['plugin'] . '/source_blocks/'. $block_name);
			
													// compile language into block
													$block_lang = array();
			
													if (is_file('plugins/' . $block['plugin'] . '/language/'. AM_DEFAULT_LANGUAGE_CODE . '/block.lang.php')) {
														include('plugins/' . $block['plugin'] . '/language/'. AM_DEFAULT_LANGUAGE_CODE . '/block.lang.php');
													}
						
													if (defined('AM_LANGUAGE_CODE')) {
														if (is_file('plugins/' . $block['plugin'] . '/language/'. AM_LANGUAGE_CODE . '/block.lang.php')) {
															include('plugins/' . $block['plugin'] . '/language/'. AM_LANGUAGE_CODE . '/block.lang.php');
														}
													}
													
													foreach($block_lang as $lang_key => $lang_val):
														$block_key = "AM_BLOCK_LANGUAGE_" . strtoupper($lang_key);
														$block_html = str_replace($block_key, $lang_val, $block_html);
													endforeach;
			
													$ws->insertBlock($block['plugin'], $block['name'], $block_html);
												}
											}
										}
									}
								}
							}
							
							// replace the block
							if (isset($block_html)) {
								$inner_template_body = str_replace($plugin_blocks[0][$key], $block_html, $inner_template_body);
							}
							else {
								$inner_template_body = str_replace($plugin_blocks[0][$key], '', $inner_template_body);
							}
						endforeach;
					}
				}
			}
		}
	}
	elseif (!in_array($identity->identity_name, explode(', ', $core_config['am']['reserved_identity_names']))) { // no identity .. prompt to claim it
		define("AM_SCRIPT_NAME", "webspace");

		require_once('core/' . AM_SCRIPT_NAME . '.php');
		
		$inner_template_body = file_get_contents('core/template/' . AM_SCRIPT_NAME . '.tpl.php');
		
		$body->set('display', 'no_identity');
	}
	else {
		$target_url = 'http://' . substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.')+1);
		header('location: ' . $target_url);
	}
	
	$body->set('identity_openid', str_replace("REPLACE", $identity->identity_name, $core_config['am']['domain_replace_pattern']));
	$body->set('identity_account', $identity->identity_name);
	
	// if no body set then set the default
	if (!isset($inner_template_body)) {
		$default_webpage = 1;

		define("AM_SCRIPT_NAME", "webspace");
		
		require_once('core/' . AM_SCRIPT_NAME . '.php');
		$inner_template_body = file_get_contents('core/template/webspace.tpl.php');	
	}
}
else { // no identity - must be main domain
	define("AM_SCRIPT_NAME", "webspace");

	require_once('core/' . AM_SCRIPT_NAME . '.php');

	$inner_template_body = file_get_contents('core/template/' . AM_SCRIPT_NAME . '.tpl.php');

	$body->set('display', 'default_domain_name');
}

// OUTPUT TO TEMPLATE ------------------------------------------------------------------
$tpl->lang = $lang;
$body->lang = $lang;

if (!empty($output_identity)) {
	$tpl->set('identity', $output_identity);
	$body->set('identity', $output_identity);
}

if (!empty($output_webspace)) {
	$tpl->set('webspace', $output_webspace);
	$body->set('webspace', $output_webspace);
}

if (!empty($body->header_link_tag_arr)) { // move it to the outer template
	$tpl->header_link_tag_arr = $body->header_link_tag_arr;
}


// CONFIG VARS PASSED --------------------
// note: webspace designers can get to these vars - never pass a username / password
// from the config to the template!
$tpl->set('am_release', $core_config['release']);
$body->set('domain_replace_pattern', $core_config['am']['domain_replace_pattern']);
$body->set('max_list_rows', $core_config['display']['max_list_rows']);
$body->set('arr_language', $core_config['language']);
$body->set('identity_creation_type', $core_config['am']['identity_creation_type']);

// Remove any duplicate error messages
if (!empty($GLOBALS['am_error_log'])) {
	$GLOBALS['am_error_log'] = array_unique($GLOBALS['am_error_log']);
}

$tpl->set('content', $body->parse($inner_template_body));

echo $tpl->fetch(AM_TEMPLATE_PATH . 'wrapper.tpl.php');

?>