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

?>

<form action="index.php?p=barnraiser_openid&amp;t=maintain" method="POST">

	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('manage_displayed_items'); ?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php $this->getLanguage('manage_profile_intro'); ?>
			</p>

			<ul>
				<li><?php $this->getLanguage('manage_profile_friends'); ?></li>
				<li><?php $this->getLanguage('manage_profile_connections'); ?></li>
				<li><?php $this->getLanguage('manage_profile_everyone'); ?></li>
				<li><?php $this->getLanguage('manage_profile_me'); ?></li>
			</ul>
			
			<?php
			if (isset($identity_profile_attributes)) {
			?>
			<table cellspacing="0" cellpadding="2" border="0" width="100%">
				<?php
				foreach ($identity_profile_attributes as $key => $i):
				?>
				<tr>
					<td valign="top">
						<label for="id_<?php echo $key;?>_level"><?php echo $this->lang['txt_' . $i['attribute_name']];?></label>
					</td>
					<td valign="top">
						<?php
						if ($i['attribute_name'] == 'media/image/aspect11') {
						?>
							<img src="<?php echo $i['attribute_value'];?>"/>
						<?php
						}
						else {
						?>

							<?php
							if (isset($this->lang['arr_identity_field'][$i['attribute_name']][$i['attribute_value']])) {
								echo $this->lang['arr_identity_field'][$i['attribute_name']][$i['attribute_value']];
							}
							else {
								echo $i['attribute_value'];
							}
							?>
						<?php }?>
					</td>
					<td valign="top" align="right">
						<select id="id_<?php echo $key;?>_level" name="identity_level[<?php echo $i['attribute_name'];?>]">
							<option value="0" selected="selected"><?php echo $this->lang['arr_level'][0];?></option>
							<option value="16"<?php if(isset($i['level_id']) && $i['level_id'] == 16) { echo " selected=\"selected\"";}?>><?php echo $this->lang['arr_level'][16];?></option>
							<option value="32"<?php if(isset($i['level_id']) && $i['level_id'] == 32) { echo " selected=\"selected\"";}?>><?php echo $this->lang['arr_level'][32];?></option>
							<option value="64"<?php if(isset($i['level_id']) && $i['level_id'] == 64) { echo " selected=\"selected\"";}?>><?php echo $this->lang['arr_level'][64];?></option>
						</select>
					</td>
				</tr>
				<?php
				endforeach;
				?>
			</table>

			<p align="right">
				<input type="submit" value="<?php $this->getLanguage('manage_save'); ?>" name="save_identity_levels" />
			</p>
			<?php }?>
		</div>
	</div>
</form>