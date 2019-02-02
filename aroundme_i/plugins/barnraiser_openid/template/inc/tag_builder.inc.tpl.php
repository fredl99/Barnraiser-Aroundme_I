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
if (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "card") {
?>

	<p>
		This tag adds your identity card.
	</p>

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserOpenIDTag('card');" />
	</p>
<?php
}
elseif (isset($_REQUEST['tag']) && $_REQUEST['tag'] == "connect") {
?>
	<p>
		This tag adds a OpenID connection box to your webspace.
	</p>	

	<p align="right">
		<input type="button" value="Create tag" onClick="javascript:buildPluginBarnraiserOpenIDTag('connect');" />
	</p>

<?php }?>
</form>