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

<form action="index.php?t=profile" method="POST" enctype="multipart/form-data">

<div id="col_left_50">
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_edit_profile');?></h1>
		</div>
			
		<div class="box_body">
			<?php
			foreach ($config_identity_attributes as $key => $i):
			?>
			
			<?php
			if ($i == "text") {
			?>
	
			<?php 
			$value = "";
			if (isset($identity_attributes)) {
				foreach($identity_attributes as $ikey => $ival):
					if ($ival['attribute_name'] == $key) {
						$value = $ival['attribute_value'];
						$level_id = $ival['level_id'];
						break;
					}
				endforeach;
			}
			?>
			<p>
				<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
				<input type="text" name="identity_profile[<?php echo $key;?>]" id="id_<?php echo $key;?>" value="<?php echo $value; ?>" />
				<input type="hidden" name="identity_profile_level[<?php echo $key; ?>]" value="<?php echo $level_id; ?>" />
			</p>
					
			<?php
			}
			elseif ($i == "textarea") {
			?>
					
			<?php 
			$value = "";
			if (isset($identity_attributes)) {
				foreach($identity_attributes as $ikey => $ival):
					if ($ival['attribute_name'] == $key) {
						$value = $ival['attribute_value'];
						$level_id = $ival['level_id'];
						break;
					}
				endforeach;
			}
			?>
			<p>
				<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
				<textarea cols="30" rows="6" name="identity_profile[<?php echo $key;?>]" id="id_<?php echo $key;?>"><?php echo $value; ?></textarea>
				<input type="hidden" name="identity_profile_level[<?php echo $key; ?>]" value="<?php echo $level_id; ?>" />
			</p>
				
			<?php
			}
			elseif ($i == "select") {
			?>
					
			<p>
				<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
				
				<select id="id_<?php echo $key;?>" name="identity_profile[<?php echo $key;?>]">
					<option value="0" selected="selected"><?php $this->getLanguage('common_none');?></option>
					<?php
					foreach ($this->lang['arr_identity_field'][$key] as $selectkey => $s):
					?>
					<?php 
					$checked = "";
					if (isset($identity_attributes)) {
						foreach($identity_attributes as $ikey => $ival):
							if ($ival['attribute_name'] == $key) {
								if ($ival['attribute_value'] == $selectkey) {
									$checked = " selected=\"selected\"";
									$level_id = $ival['level_id'];
									break;
								}
							}
						endforeach;
					}
					?>
					<option value="<?php echo $selectkey;?>"<?php echo $checked; ?>><?php echo $s;?></option>
					<?php
					endforeach;
					?>
				</select>
				<input type="hidden" name="identity_profile_level[<?php echo $key; ?>]" value="<?php echo $level_id; ?>" />
			
			</p>
			
			<?php
			}
			elseif ($i == "radio") {
			?>
			
			<p>
				<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
				<input type="radio" id="id_<?php echo $key;?>" name="identity_profile[<?php echo $key;?>]" value="0" checked="checked" /><?php $this->getLanguage('common_none');?> &nbsp;
				
				<?php
				foreach ($lang['arr_identity_field'][$key] as $radiokey => $r):
				?>
				<input type="radio" id="id_<?php echo $r;?>" name="identity_profile[<?php echo $key;?>]" value="<?php echo $radiokey;?>"<?php if(isset($identity['connection_' . $key]) && $identity['connection_' . $key] == $radiokey) { echo " checked=\"checked\"";}?> /><label style="float: none; font-weight: normal;" for="id_<?php echo $r;?>"><?php echo $r;?></label> &nbsp;
				<?php
				endforeach;
				?>
				<input type="hidden" name="identity_profile_level[<?php echo $key; ?>]" value="<?php echo $level_id; ?>" />
			</p>
			<?php }?>
			
			<?php
			endforeach;
			?>
		
			<p align="right">
				<input type="submit" name="save_identity_profile" value="<?php $this->getLanguage('common_save');?>" />
			</p>
		</div>
	</div>
</div>

<div id="col_right_50">
	<?php
	if (isset($avatars)) {
	?>
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_avatar');?></h1>
		</div>
	
		<div class="box_body">
			<p>
				<?php $this->getLanguage('core_avatar_intro');?>
			</p>
	
			<p>
				<?php
				foreach($avatars as $key => $i):
				?>
				<div class="avatar_gallery">
					<label for="<?php echo $i['file_name']; ?>" style="float: none; cursor: pointer;"><img src="core/get_file.php?avatar=<?php echo $i['file_name'];?>" alt="avatar" /></label><br />
	
					<p>
						<?php 
						$checked = "";
						$hide = 0;
						if (isset($identity_attributes)) {
							foreach($identity_attributes as $ikey => $ival):
								if ($ival['attribute_name'] == 'media/image/aspect11') {
									if ($ival['attribute_value'] == 'http://' . rtrim($_SERVER['SERVER_NAME'], '/') . '/core/get_file.php?avatar=' . $i['file_name']) {
										$checked = " checked=\"checked\"";
										$level_id = $ival['level_id'];
										$hide = 1;
										$avatar_in_use = 1;
									}
								}
							endforeach;
						}
						?>
						<input id="<?php echo $i['file_name']; ?>" type="radio" name="current_avatar_name" value="<?php echo $i['file_name'];?>"<?php echo $checked;?> />
						<?php 
						if (!$hide) {
						?>
						<input type="checkbox" name="delete_avatar_name[]" value="<?php echo $i['file_name'];?>" />
						<input type="hidden" name="delete_avatar_id[]" value="<?php echo $i['file_id'];?>" />
						<input type="hidden" name="identity_profile_level[<?php echo $key; ?>]" value="<?php echo $level_id; ?>" />
						<?php }?>
					</p>
							
				</div>
				<?php
				endforeach;
				?>
				<div class="avatar_gallery">
					<label for="id_avatar_none" style="float: none; cursor: pointer;"><img src="<?php echo AM_TEMPLATE_PATH;?>img/no_avatar.png" alt="no avatar icon" /></label><br />
					<p>
						<input type="radio" id="id_avatar_none" name="current_avatar_name" value="0" <?php if (!isset($avatar_in_use)) echo "checked=\"checked\""; ?>/>
					</p>
				</div>
			</p>
			<div style="clear: both;"></div>
	
			<p align="right">
				<input type="submit" name="submit_delete_avatar" value="<?php $this->getLanguage('core_delete_avatar');?>" />
				<input type="submit" name="submit_set_avatar" value="<?php $this->getLanguage('core_set_avatar');?>" />
			</p>
		</div>
		
	</div>
	<?php }?>
			
			
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_upload_avatar');?></h1>
		</div>
	
		<div class="box_body">
			<p>
				<?php $this->getLanguage('core_upload_avatar_intro');?>
			</p>
	
			<p>
				<label for="frm_file"><?php $this->getLanguage('core_upload_file');?></label>
				<input type="file" name="frm_file" id="frm_file" />
			</p>
	
			<p align="right">
				<input type="submit" name="submit_upload_avatar" value="<?php $this->getLanguage('common_upload');?>" />
			</p>
		</div>
	</div>
</div>
</form>