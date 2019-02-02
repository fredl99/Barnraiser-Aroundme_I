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
<form method="post">
<input type="hidden" name="return_to" value="<?php if (isset($_SERVER['HTTP_REFERER'])) { echo $_SERVER['HTTP_REFERER'];}?>" />
<?php
if (isset($display) && $display == 'append_connection') {
?>
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('hdr_append_connection');?></h1>
		</div>
		
		<div class="box_body">
			<p>
				<?php $this->getLanguage('txt_append_connection_intro');?>
			</p>

				<?php
				foreach ($config_identity_attributes as $key => $i):
				?>

				<?php
				if ($i == "text") {
				?>
				<?php
				if (isset($openid_required_fields) && isset($ax2sreg[$key]) && in_array($ax2sreg[$key], $openid_required_fields)) {
				?>
				<p>
				<?php
				$value = "";
				foreach($connection_attribute as $ikey => $ival):
					if ($ival['attribute_name'] == $key) {
						$value = $ival['attribute_value'];
					}
				endforeach;
				?>
					<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
					<input type="text" name="connection_required_fields[<?php echo $key;?>]" id="id_<?php echo $key;?>" value="<?php echo $value; ?>" />*
				</p>
				<?php }?>
				
				<?php
				}
				elseif ($i == "textarea") {
				?>
				<?php
				if (isset($openid_required_fields) && isset($ax2sreg[$key]) && in_array($ax2sreg[$key], $openid_required_fields)) {
				?>
				<p>
				<?php
				$value = "";
				foreach($connection_attribute as $ikey => $ival):
					if ($ival['attribute_name'] == $key) {
						$value = $ival['attribute_value'];
					}
				endforeach;
				?>
					<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
					<textarea cols="30" rows="6" name="connection_required_fields[<?php echo $key;?>]" id="id_<?php echo $key;?>"><?php echo $value; ?></textarea>
				</p>
				<?php }?>
			
				<?php
				}
				elseif ($i == "select") {
				?>
				<?php
				if (isset($openid_required_fields) && isset($ax2sreg[$key]) && in_array($ax2sreg[$key], $openid_required_fields)) {
				?>
				<p>
					<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
					
					<select id="id_<?php echo $key;?>" name="connection_required_fields[<?php echo $key;?>]">
						<option value="0" selected="selected"><?php $this->getLanguage('txt_select_none');?></option>
						<?php
						foreach ($lang['arr_identity_field'][$key] as $selectkey => $s):
						?>
						<option value="<?php echo $selectkey;?>"<?php echo $checked; ?>><?php echo $s;?></option>
						<?php
						endforeach;
						?>
					</select>
				</p>
				<?php }?>
				<?php
				}
				elseif ($i == "radio") {
				?>
				<?php
				if (isset($openid_required_fields) && isset($ax2sreg[$key]) && in_array($ax2sreg[$key], $openid_required_fields)) {
				?>
				<p>
					<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
					<input type="radio" id="id_<?php echo $key;?>" name="connection_required_fields[<?php echo $key;?>]" value="0" checked="checked" /><?php $this->getLanguage('common_none'); ?> &nbsp;
					
					<?php
					foreach ($lang['arr_identity_field'][$key] as $radiokey => $r):
					?>
					<input type="radio" id="id_<?php echo $r;?>" name="connection_required_fields[<?php echo $key;?>]" value="<?php echo $radiokey;?>"<?php if(isset($identity['connection_' . $key]) && $identity['connection_' . $key] == $radiokey) { echo " checked=\"checked\"";}?> /><label style="float: none; font-weight: normal;" for="id_<?php echo $r;?>"><?php echo $r;?></label> &nbsp;
					<?php
					endforeach;
					?>
				</p>
				<?php }?>
				<?php }?>
				
				<?php
				if ($i == "text") {
				?>
				<?php
				if (isset($openid_optional_fields) && isset($ax2sreg[$key]) && in_array($ax2sreg[$key], $openid_optional_fields)) {
				?>
				<p>
				<?php
				$value = "";
				foreach($connection_attribute as $ikey => $ival):
					if ($ival['attribute_name'] == $key) {
						$value = $ival['attribute_value'];
					}
				endforeach;
				?>
					<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
					<input type="text" name="connection_optional_fields[<?php echo $key;?>]" id="id_<?php echo $key;?>" value="<?php echo $value; ?>" />
				</p>
				<?php }?>
				
				<?php
				}
				elseif ($i == "textarea") {
				?>
				<?php
				if (isset($openid_optional_fields) && isset($ax2sreg[$key]) && in_array($ax2sreg[$key], $openid_optional_fields)) {
				?>
				<p>
				<?php
				$value = "";
				foreach($connection_attribute as $ikey => $ival):
					if ($ival['attribute_name'] == $key) {
						$value = $ival['attribute_value'];
					}
				endforeach;
				?>
					<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
					<textarea cols="30" rows="6" name="connection_optional_fields[<?php echo $key;?>]" id="id_<?php echo $key;?>"><?php echo $value; ?></textarea>
				</p>
				<?php }?>
			
				<?php
				}
				elseif ($i == "select") {
				?>
				<?php
				if (isset($openid_optional_fields) && isset($ax2sreg[$key]) && in_array($ax2sreg[$key], $openid_optional_fields)) {
				?>
				<p>
					<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
					
					<select id="id_<?php echo $key;?>" name="connection_optional_fields[<?php echo $key;?>]">
						<option value="0" selected="selected"><?php $this->getLanguage('txt_select_none');?></option>
						<?php
						foreach ($lang['arr_identity_field'][$key] as $selectkey => $s):
						?>
						<option value="<?php echo $selectkey;?>"<?php echo $checked; ?>><?php echo $s;?></option>
						<?php
						endforeach;
						?>
					</select>
				</p>
				<?php }?>
				<?php
				}
				elseif ($i == "radio") {
				?>
				<?php
				if (isset($openid_optional_fields) && isset($ax2sreg[$key]) && in_array($ax2sreg[$key], $openid_optional_fields)) {
				?>
				<p>
					<label for="id_<?php echo $key;?>"><?php $this->getLanguage('txt_' . $key);?></label>
					<input type="radio" id="id_<?php echo $key;?>" name="connection_optional_fields[<?php echo $key;?>]" value="0" checked="checked" /><?php $this->getLanguage('common_none'); ?> &nbsp;
					
					<?php
					foreach ($lang['arr_identity_field'][$key] as $radiokey => $r):
					?>
					<input type="radio" id="id_<?php echo $r;?>" name="connection_optional_fields[<?php echo $key;?>]" value="<?php echo $radiokey;?>"<?php if(isset($identity['connection_' . $key]) && $identity['connection_' . $key] == $radiokey) { echo " checked=\"checked\"";}?> /><label style="float: none; font-weight: normal;" for="id_<?php echo $r;?>"><?php echo $r;?></label> &nbsp;
					<?php
					endforeach;
					?>
				</p>
				<?php }?>
				<?php }?>
				
				<?php
				endforeach;
				?>

			<p align="right">
				<input type="submit" name="update_connection" value="<?php $this->getLanguage('sub_continue');?>" />
			</p>
		</div>
	</div>
<?php
}
else {
?>
<div class="box">
	<div class="box_header">
		<h1><?php $this->getLanguage('core_connect'); ?></h1>
	</div>
	
    <div class="box_body">
		 <p>
			<?php $this->getLanguage('core_connect_intro'); ?>
		</p>
		
		 <p>
			<label for="openid_login"><?php $this->getLanguage('common_openid'); ?></label>
			<input type="text" id="openid_login" name="openid_login" value="http://example.domain.org" onFocus="this.value=''; return false;" />
		</p>

		<p align="right">
			<input type="submit" name="connect" value="<?php $this->getLanguage('common_go'); ?>"/>
			<input type="hidden" name="connect" value="1"/>
		</p>
	
	</div>
</div>
<?php }?>
</form>