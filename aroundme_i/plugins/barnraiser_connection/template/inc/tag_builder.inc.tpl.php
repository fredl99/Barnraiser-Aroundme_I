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

?>

<form id="tag_builder_form">

<?php
if (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "gallery") {
?>

	<p>
		This tag displays a gallery of avatars of people who have connected to your site. Clicking an avatar goes to their site.
	</p>
	
	<p>
		<label for="id_limit">limit</label>
		<input type="text" name="tag_builder_element_limit" id="id_limit" value="" />
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('gallery');" />
	</p>
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "inbound_list") {
?>
	<p>
		This tag displays a list of people who have connected to your site.
	</p>
	
	<p>
		<label for="id_limit">limit</label>
		<input type="text" name="tag_builder_element_limit" id="id_limit" value="" />
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('inbound_list');" />
	</p>
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "outbound_list") {
?>
	<p>
		This tag displays a list of websites that you have visited.
	</p>

	<p>
		<label for="id_limit">limit</label>
		<input type="text" name="tag_builder_element_limit" id="id_limit" value="" />
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('outbound_list');" />
	</p>
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "log") {
?>
	<p>
		Your poplog is a log of events which has happened on this site. This tag displays your poplog.
	</p>

	<p>
		<label for="id_limit">limit</label>
		<input type="text" name="tag_builder_element_limit" id="id_limit" value="" />
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserConnectionTag('log');" />
	</p>
<?php }?>


</form>