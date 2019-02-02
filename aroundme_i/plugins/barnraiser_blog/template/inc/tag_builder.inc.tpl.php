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
if (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "entry") {
?>

	<p>
		This tag creates a blog entry. Each blog entry has comments so that others can contribute.
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnaiserBlogTag('entry');" />
	</p>
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "list") {
?>
	<p>
		This tag lists the latest blog entries.
	</p>
	
	<p>
		<label for="id_limit">limit</label>
		<input type="text" name="tag_builder_element_limit" id="id_limit" value="" />
	</p>

	<p>
		<label for="id_trim">trim</label>
		<input type="text" name="tag_builder_element_trim" id="id_trim" value="" />
	</p>
	
	<p>
		<label for="id_webpage">webpage</label>
		<select id="id_webpage" name="tag_builder_element_webpage">
			<option value="0">same webpage</option>
			<?php
			if (isset($webpages)) {
			foreach ($webpages as $key => $i):
			?>
			<option value="<?php echo $i;?>"><?php echo $i;?></option>
			<?php
			endforeach;
			}
			?>
		</select>
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnaiserBlogTag('list');" />
	</p>
<?php }?>
</form>