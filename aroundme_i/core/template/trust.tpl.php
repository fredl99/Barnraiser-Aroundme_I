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

<script type="text/javascript">
	function viewSaveCheckbox() {
		document.getElementById('save_identity_box').style.visibility = "visible";
		document.getElementById('save_identity').checked = "checked";
	}
</script>

<form method="post" name="frm">

<div id="col_left_50">
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_trust_request_title');?></h1>
		</div>

		<div class="box_body">
			<?php
			if (empty($trusted_root_title)) {
				$trusted_root_title = $this->lang['core_trust_request_no_name'];
			}
			?>

			<p>
				<?php
				$lang_item = $this->lang['core_trust_request_intro'];
				$lang_item = str_replace('AM_KEYWORD_TRUSTED_ROOT_URL', $trusted_root, $lang_item);
				$lang_item = str_replace('AM_KEYWORD_TRUSTED_ROOT_TITLE', $trusted_root_title, $lang_item);
				echo $lang_item;
				?>
			</p>

			<?php
			if (!empty($_GET['openid_sreg_required'])) {
			foreach(explode(',', $_GET['openid_sreg_required']) as $i):
			?>

			<table cellspacing="0" cellpadding="4" border="0">
			<?php
			if (isset($config_openid_extension['sreg'][$i]) && $config_openid_extension['sreg'][$i] == 'text') {
			?>
			<tr>
				<td valign="top">
					<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
				</td>
				<td valign="top">
				<?php
				$value = "";
				foreach($identity_attribute as $key => $ival):
					if ($ival['attribute_name'] == $sreg2ax[$i]) {
						$value = $ival['attribute_value'];
					}
				endforeach;
				?>
					<input onKeyPress="viewSaveCheckbox();" type="text" name="<?php echo $i; ?>" id="<?php echo $i; ?>" value="<?php echo $value; ?>" <?php if (empty($value)) echo "disabled"; ?>/>
				</td>
				<td valign="top">
					<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($value)) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
					<label for="checkbox_<?php echo $i; ?>" style="float: none;font-weight:normal;">&#149;</label>
				</td>
			</tr>
			<?php
			}
			elseif (isset($config_openid_extension['sreg'][$i]) && $config_openid_extension['sreg'][$i] == 'select') {
			?>
			<tr>
				<td valign="top">
				<?php
				$value = "";
				foreach($identity_attribute as $key => $ival):
					if ($ival['attribute_name'] == $sreg2ax[$i]) {
						$value = $ival['attribute_value'];
					}
				endforeach;
				?>
					<label for="<?php echo $i; ?>" <?php if (empty($value)) echo "disabled"; ?>><?php echo $i; ?></label>
				</td>
				<td valign="top">
					<select name="<?php echo $i; ?>" onchange="viewSaveCheckbox();">
						<?php foreach($lang['arr_identity_field'][$i] as $k => $v) { ?>
							<option value="<?php echo $k; ?>" <?php if (isset($value) && $k == $value) echo "selected=\"selected\""; ?>><?php echo $v; ?></option>
						<?php } ?>
					</select>
				</td>
				<td valign="top">
					<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($value)) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
					<label for="checkbox_<?php echo $i; ?>" style="float: none;font-weight:normal;">&#149;</label>
				</td>
			</tr>
			<?php
			}
			endforeach;
			}
			?>

			<?php
			if (!empty($_GET['openid_sreg_optional'])) {
			foreach(explode(',', $_GET['openid_sreg_optional']) as $i):
			?>

			<?php
			if (isset($config_openid_extension['sreg'][$i]) && $config_openid_extension['sreg'][$i] == 'text') {
			?>
			<tr>
				<td valign="top">
					<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
				</td>
				<td valign="top">
				<?php
				$value = "";
				foreach($identity_attribute as $key => $ival):
					if ($ival['attribute_name'] == $sreg2ax[$i]) {
						$value = $ival['attribute_value'];
					}
				endforeach;
				?>
					<input onKeyPress="viewSaveCheckbox();" type="text" name="<?php echo $i; ?>" id="<?php echo $i; ?>" value="<?php echo $value; ?>"  <?php if (empty($value)) echo "disabled"; ?>/>
				</td>
				<td valign="top">
					<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($value)) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
					<label for="checkbox_<?php echo $i; ?>"></label>
				</td>
			</tr>
			<?php
			}
			elseif (isset($config_openid_extension['sreg'][$i]) && $config_openid_extension['sreg'][$i] == 'select') {
			?>
			<tr>
				<td valign="top">
					<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
				</td>
				<?php
				$value = "";
				foreach($identity_attribute as $key => $ival):
					if ($ival['attribute_name'] == $sreg2ax[$i]) {
						$value = $ival['attribute_value'];
					}
				endforeach;
				?>
				<td valign="top">
					<select name="<?php echo $i; ?>" <?php if (empty($value)) echo "disabled"; ?> onchange="viewSaveCheckbox();">
					<?php
					foreach($lang['arr_identity_field']['pref/' . $i] as $k => $v) {
						?>
						<option value="<?php echo $k; ?>" <?php if (isset($value) && $k == $value) echo "selected=\"selected\""; ?>><?php echo $v; ?></option>
					<?php } ?>
					</select>
				</td>
				<td valign="top">
					<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($value)) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
					<label for="checkbox_<?php echo $i; ?>"></label>
				</td>
			</tr>
			<?php
			}
			elseif (isset($config_openid_extension['sreg'][$i]) && $config_openid_extension['sreg'][$i] == 'radio') {
			?>
			<tr>
				<td valign="top">
					<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
				</td>
				<td valign="top">
				<?php
				$value = "";
				foreach($identity_attribute as $key => $ival):
					if ($ival['attribute_name'] == $sreg2ax[$i]) {
						$value = $ival['attribute_value'];
					}
				endforeach;
				?>
					<input onchange="viewSaveCheckbox();" type="radio" id="id_<?php echo $i;?>" name="<?php echo $i; ?>" <?php if (empty($value)) echo "checked=\"checked\""; ?> value="0" /><?php $this->getLanguage('common_none');?>&nbsp;

					<?php foreach($lang['arr_identity_field'][$i] as $k => $v) { ?>
						<input onchange="viewSaveCheckbox();" id="id_radio_<?php echo $k; ?>" type="radio" name="<?php echo $i; ?>" value="<?php echo $k; ?>"  <?php if (!empty($value) && $value == $k) echo "checked=\"checked\""; ?> />
						<label for="id_radio_<?php echo $k; ?>" style="float: none;font-weight:normal;"><?php echo $v; ?></label>
					<?php } ?>
				</td>
				<td valign="top">
					<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($value)) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
					<label for="checkbox_<?php echo $i; ?>"></label>
				</td>
			</tr>
			<?php }
			endforeach;
			}
			?>

			<?php
			$value = "";
			foreach($identity_attribute as $key => $ival):
				if ($ival['attribute_name'] == 'media/image/aspect11') {
					$value = $ival['attribute_value'];
				}
			endforeach;
			?>

			<?php
			if (!empty($value)) {
			?>
			<tr>
				<td valign="top">
					<label for="<?php echo $i; ?>"><?php echo $i; ?></label>
				</td>
				<td valign="top">
					<input id="id_avatar" type="hidden" name="<?php echo $i; ?>" value="<?php echo $value; ?>" checked="checked" />
					<label for="id_avatar" style="float: none;font-weight:normal; border:1px solid #333;"><img src="<?php echo $value; ?>" alt="avatar"/></label>
				</td>
				<td valign="top">
					<input type="checkbox" name="checkbox_<?php echo $i; ?>" id="checkbox_<?php echo $i; ?>" value="1" <?php if (!empty($value)) echo "checked=\"checked\""; ?> onchange="disable_field('<?php echo $i; ?>');"/>
					<label for="checkbox_<?php echo $i; ?>"></label>
				</td>
			</tr>
			<?php }?>

			<tr style="visibility: hidden;" id="save_identity_box">
				<td></td>
				<td valign="top" align="right">
					<label for="save_identity" style="float: none; width: auto;"><?php $this->getLanguage('core_save_changes');?></label>
				</td>
				<td valign="top">
					<input type="checkbox" name="save_identity" id="save_identity" value="1" />
				</td>
			</tr>
			</table>

		</div>
	</div>
</div>

<div id="col_right_50">
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_authorize_title');?></h1>
		</div>

		<div class="box_body">
			<p>
				<b><?php $this->getLanguage('core_authorize_intro');?></b>
			</p>

			<table cellspacing="0" cellpadding="4" border="0" width="100%">
				<tr>
					<td valign="top">
						<label for="trust_always" style="float: none;font-weight:normal; width:none;"><?php $this->getLanguage('core_authorize_trust_always');?></label><br />
					</td>
					<td valign="top" align="right">
						<input type="checkbox" name="trust_always" id="trust_always" value="1" checked="checked" />
					</td>
				</tr>
				<tr>
					<td valign="top">
						<label for="trust_always2" style="float: none;font-weight:normal; width:none;"><?php $this->getLanguage('core_authorize_trust_log');?></label><br />
					</td>
					<td valign="top" align="right">
						<input type="checkbox" name="trust_always2" id="trust_always2" value="1" checked="checked"/>
					</td>
				</tr>
			</table>

			<p align="right">
				<input type="submit" name="cancel" value="<?php $this->getLanguage('core_authorize_deny');?>" />
				<input type="submit" name="trust" class="trust_allow" value="<?php $this->getLanguage('core_authorize_allow');?>" />
			</p>
		</div>
	</div>
</div>

<script type="text/javascript">

function disable_field(id) { 
	var elem = document.getElementsByName(id);
	for(i = 0; i < elem.length; i++) {
		elem[i].disabled = !document.getElementById('checkbox_' + id).checked;
	}
}

</script>