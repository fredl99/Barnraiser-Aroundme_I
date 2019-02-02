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

if (is_readable('plugins/barnraiser_blog/language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_common.lang.php')) {
	include_once('plugins/barnraiser_blog/language/' . AM_DEFAULT_LANGUAGE_CODE . '/plugin_common.lang.php');
}

if (defined('AM_LANGUAGE_CODE') && is_readable('plugins/barnraiser_blog/language/' . AM_LANGUAGE_CODE . '/plugin_common.lang.php')) {
	include_once('plugins/barnraiser_blog/language/' . AM_LANGUAGE_CODE . '/plugin_common.lang.php');
}

$output_statistics['barnraiser_blog_total_blogs'] = 0;

$query = "SELECT count(blog_id) as total FROM " . $am_core->prefix . "_plugin_blog_entry WHERE identity_id=" . AM_IDENTITY_ID;

$result = $am_core->Execute($query);

if (isset($result[0]['total'])) {
	$output_statistics['barnraiser_blog_total_blogs'] = $result[0]['total'];
}

$output_statistics['barnraiser_blog_total_comments'] = 0;

$query = "SELECT count(comment_id) as total FROM " . $am_core->prefix . "_plugin_blog_comment WHERE identity_id=" . AM_IDENTITY_ID;

$result = $am_core->Execute($query);

if (isset($result[0]['total'])) {
	$output_statistics['barnraiser_blog_total_comments'] = $result[0]['total'];
}

?>