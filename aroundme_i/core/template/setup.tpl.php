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

<form action="index.php?t=setup" method="POST">

<div id="col_left_50">
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_webspace_information'); ?></h1>
		</div>

		<div class="box_body">
			<p>
				<label for="id_webspace_title"><?php $this->getLanguage('common_title');?></label>
				<input type="text" id="id_webspace_title" name="webspace_title" value="<?php if(isset($webspace['webspace_title'])) { echo $webspace['webspace_title'];}?>" />
			</p>

			<p>
				<label for="id_webspace_lock"><?php $this->getLanguage('core_lock_webspace');?></label>
				<input type="checkbox" name="webspace_locked" id="id_webspace_lock" value="1"<?php if (!empty($webspace['webspace_locked'])) { echo " checked=\"checked\"";}?> />
			</p>

			<p class="note">
				<?php $this->getLanguage('txt_input_locked');?>
			</p>
			
			<p class="buttons">
				<input type="submit" name="save_webspace" value="<?php $this->getLanguage('common_save');?>" />
			</p>
		</div>
	</div>
</div>

<div id="col_right_50">
	<?php
	if (isset($webpages)) {
	?>
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_webpages');?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php $this->getLanguage('core_webpages_intro');?>
			</p>
	
			<table cellspacing="0" cellpadding="2" border="0" width="100%">
				<tr>
					<td valign="top">
						<b><?php $this->getLanguage('common_name');?></b><br />
					</td>
					<td valign="top">
						<b><?php $this->getLanguage('core_html_tag');?></b><br />
					</td>
					<td align="center" valign="top">
						<b><?php $this->getLanguage('core_start');?></b><br />
					</td>
					<td align="center" valign="top">
						<b><?php $this->getLanguage('common_delete');?></b><br />
					</td>
				</tr>
				<?php
				foreach ($webpages as $key => $i):
				?>
				<tr>
					<td valign="top">
						<a href="index.php?wp=<?php echo $i['webpage_name'];?>"><?php echo $i['webpage_name'];?></a><br />
					</td>
					<td>
						<input type="text" name="show_tag" value='<a href="index.php?wp=<?php echo $i['webpage_name'];?>">link description</a>' onclick="javascript:this.focus();this.select();" readonly="true"/><br />
					</td>
					<td align="center" valign="top">
						<?php
						$checked = "";
						if (isset($webspace['default_webpage_id']) && $webspace['default_webpage_id'] == $i['webpage_id']) {
							$checked = " checked=\"checked\"";
						}
						?>
						<input type="radio" name="default_webpage_id" value="<?php echo $i['webpage_id'];?>" <?php echo $checked;?> /><br />
					</td>
					<td align="right" valign="top">
						<?php
						if (isset($webspace['default_webpage_id']) && $webspace['default_webpage_id'] != $i['webpage_id']) {
						?>
						<input type="checkbox" name="delete_webpage_ids[]" value="<?php echo $i['webpage_id'];?>" />
						<?php }?>
						<br />
					</td>
				</tr>
				<?php
				endforeach;
				?>
			</table>
	

			<p class="buttons">
				<input type="submit" name="set_default_webpage" value="<?php $this->getLanguage('core_set_start_page');?>" />
				<input type="submit" name="delete_webpages" value="<?php $this->getLanguage('core_delete_selected');?>" /><br />
			</p>
		</div>
	</div>
	<?php }?>


	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_plugin_blocks');?></h1>
		</div>

		<div class="box_body">
			<?php
			if (isset($blocks)) {
			?>
			<ul>
			<?php
			foreach ($blocks as $key => $i):

			unset($block_name);
		
			if (!empty($this->lang["plugin_"  . $i['block_plugin'] . "_block_" . $i['block_name']])) {
				$block_title = $this->lang["plugin_"  . $i['block_plugin'] . "_block_" . $i['block_name']];
			}
			elseif ($i['block_plugin'] == "custom") {
				$block_title = $this->lang['core_custom'];
				$block_name = $i['block_name'];
			}
			else {
				$block_title = $i['block_name'];
			}
			?>
			<li><a href="index.php?t=block_editor&amp;block_id=<?php echo $i['block_id'];?>"><?php echo $block_title;?></a><?php if (isset($block_name)) { echo " (" . $block_name . ")";}?></li>
			<?php
			endforeach;
			?>
			</ul>
			<?php }?>
		
		</div>	
		
		<div class="box_footer">
			<a href="index.php?t=block_editor&amp;add_block=1"><?php $this->getLanguage('core_add_custom_block');?></a>
		</div>
	</div>
</div>
</form>