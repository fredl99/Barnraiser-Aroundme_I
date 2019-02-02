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


include ("../../../core/config/core.config.php");
include ("../../../core/inc/functions.inc.php");


// SESSION HANDLER ----------------------------------------------------------------------------
// sets up all session and global vars 
session_name($core_config['php']['session_name']);
session_start();


// SETUP AROUNDMe CORE -----------------------------------------------------------------------
require('../../../core/class/Db.class.php');
$db = new Database($core_config['db']);


// SETUP IDENTITY --------------------------------------------
require_once('../../../core/class/Identity.class.php');
$identity = new Identity($db);


// OBTAIN IDENTITY ACCOUNT NAME
$identity->identity_name = $identity->getIdentityName($core_config['am']['domain_preg_pattern']);

if (!empty($identity->identity_name)) {
	define('AM_IDENTITY_NAME', $identity->identity_name);
	
	$output_identity = $identity->selIdentity();

	if (!empty($output_identity)) {
	

		// the key operates to help stop people viewing the RSS feed
		// when they have just tried to guess the feed name
		// used for locked webspaces 
		$key = md5($output_identity['identity_create_datetime']);

		if (isset($_REQUEST['k']) && $_REQUEST['k'] == $key) {
			define("AM_IDENTITY_ID", $output_identity['identity_id']);
		}

	}
}

if(defined('AM_IDENTITY_ID')) {

	if(isset($_REQUEST['v']) && $_REQUEST['v'] == "comments") {

		$query = "
			SELECT 
			bc.comment_id, bc.comment_body as body, be.blog_title as title, 
			bc.comment_create_datetime as datetime, be.blog_id, 
			c.connection_nickname as publisher 
			FROM " . $db->prefix . "_plugin_blog_comment bc, " . $db->prefix . "_connection c, " . $db->prefix . "_plugin_blog_entry be
			WHERE
			bc.blog_id=be.blog_id AND 
			bc.connection_id=c.connection_id AND
			bc.identity_id=" . AM_IDENTITY_ID . " AND
			bc.comment_hidden IS NULL
			ORDER BY bc.comment_create_datetime DESC"
		;
		
		$result = $db->Execute($query);

		if (!empty($result)) {
			$ws_url = str_replace('REPLACE', $ws->webspace_unix_name, $core_config['am']['domain_replace_pattern']);
			$ws_url = rtrim($ws_url, '/') . '/';
			
			$feed_items = $result;
			
			foreach($feed_items as $key => $i):

				$feed_items[$key]['link'] = $ws_url . "index.php?wp=" . $_REQUEST['wp'] . "&amp;blog_id=" . $i['blog_id'] . "#comment_id" . $i['comment_id'];
				$feed_items[$key]['body'] = trim(strip_tags($i['body']));
				$feed_items[$key]['body'] = mb_substr($feed_items[$key]['body'], 0, 200, 'UTF-8');
				$feed_items[$key]['body'] = htmlspecialchars($feed_items[$key]['body']);

				$feed_items[$key]['title'] = $i['datetime'] . " (" . $i['publisher'] . ") " . $i['title'];

			endforeach;
		}
	}
	else {
		$query = "
			SELECT b.blog_id, b.blog_title as title, blog_body as body,
			b.blog_create_datetime as datetime 
			FROM " . $db->prefix . "_plugin_blog_entry b
			WHERE
			b.identity_id=" . AM_IDENTITY_ID . " AND
			b.blog_archived IS NULL
			ORDER BY b.blog_create_datetime DESC"
		;
			
		$result = $db->Execute($query);
	
		if (!empty($result)) {

			$ws_url = str_replace('REPLACE', AM_IDENTITY_NAME, $core_config['am']['domain_replace_pattern']);
			$ws_url = rtrim($ws_url, '/') . '/';

			$feed_items = $result;
			
			foreach($feed_items as $key => $i):

				$feed_items[$key]['link'] = $ws_url . "index.php?wp=" . $_REQUEST['wp'] . "&amp;blog_id=" . $i['blog_id'];
				$feed_items[$key]['body'] = trim(strip_tags($i['body']));
				$feed_items[$key]['body'] = mb_substr($feed_items[$key]['body'], 0, 200, 'UTF-8');
				$feed_items[$key]['body'] = htmlspecialchars($feed_items[$key]['body']);

			endforeach;
		}
	
	}

	// SELECT PREFERENCES
	$query = "
		SELECT rss_title, rss_title_comment, rss_description
		FROM " . $db->prefix . "_plugin_blog_preference
		WHERE
		identity_id=" . AM_IDENTITY_ID
	;

	$result = $db->Execute($query);

	if (!empty($result[0])) {
		$preferences = $result[0];
	}
	
	if (empty($preferences['rss_title'])) {
		$preferences['rss_title'] = "title";
	}

	if (empty($preferences['rss_title_comment'])) {
		$preferences['rss_title_comment'] = "title comments";
	}

	if (empty($preferences['rss_description'])) {
		$preferences['rss_description'] = "description";
	}

	
	$url = "http://" . $_SERVER['HTTP_HOST'];
	$url .= $_SERVER['PHP_SELF'];
	$url .= "?wp=" . $_REQUEST['wp'] . "&amp;k=" . $_REQUEST['k'];

	if (isset($_REQUEST['v']) && $_REQUEST['v'] == "comments") {
		$url .= "&amp;v=comments";

		$preferences['rss_title'] = $preferences['rss_title_comment'];
	}
	
	header("Content-Type: application/xml; charset=ISO-8859-1");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
	echo "<?xml-stylesheet title=\"XSL_formatting\" type=\"text/xsl\" href=\"nolsol.xsl\"?>\n";
	echo "<rss version=\"2.0\">\n";
	echo "<channel>\n";
	echo "<title>" . utf8_decode($preferences['rss_title']) . "</title>\n";
 	echo "<link>" . $url . "</link>\n";
 	echo "<description>" . utf8_decode($preferences['rss_description']) . "</description>\n";
 	echo "<language>" . $output_identity['language_code'] . "</language>\n";
 	echo "<lastBuildDate>" . date("r") . "</lastBuildDate>\n";
	
	if (!empty($feed_items)) {
		foreach ($feed_items as $key => $i):
			echo "<item>\n";
			echo "<title>" . utf8_decode($i['title']) . "</title>\n";
			echo "<description>" . utf8_decode($i['body']) . "</description>\n";
			echo "<link>" . $i['link'] . "</link>\n";
			echo "<author>" . utf8_decode(AM_IDENTITY_NAME) . "</author>\n";
			echo "<pubDate>" . date("r", strtotime($i['datetime'])) . "</pubDate>\n";
			echo "</item>";
		endforeach;
	}
	
	echo "</channel>\n";
	echo "</rss>";
}

?>