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

	
<form action="index.php?t=network" method="POST">

<?php
if (isset($_REQUEST['v']) && $_REQUEST['v'] == 'outbound') {
?>

<div id="col_left_50">
		<div class="box">
			<div class="box_header">
				<h1><?php $this->getLanguage('core_connections');?></h1>
			</div>

			<div class="box_body">
				<p>
					<?php $this->getLanguage('core_connections_outbound_intro');?>
				</p>
			</div>
		</div>
</div>

<div id="col_right_50">
		<div class="box">
			<div class="box_header">
				<h1><?php $this->getLanguage('core_connections_outbound');?></h1>
			</div>

			<div class="box_body">
				<?php
				if (isset($sites)) {
				?>
				<table cellspacing="0" cellpadding="2" border="0" width="100%">
					<tr>
						<th><?php $this->getLanguage('core_websites');?></th>
						<th><?php $this->getLanguage('core_trust');?></th>
					</tr>
				<?php
				foreach ($sites as $key => $i):
				?>
				<tr>
					<td valign="top">
						<a href="<?php echo $i['site_realm']; ?>"><?php echo $i['site_title']; ?></a>
					</td>
					<td valign="top" align="right">
						<input type="checkbox" name="site_trusted[]" value="<?php echo $i['site_id']; ?>"<?php if (!empty($i['site_trusted'])) { echo "checked=\"checked\"";}?> />
					</td>
				</tr>
				<?php
				endforeach;
				?>
				</table>

				<p align="right">
					<input type="submit" name="update_outbound_trust" value="<?php $this->getLanguage('core_set_trust');?>" />
				</p>
				<?php
				}
				else {
				?>
				<p>
					<?php $this->getLanguage('common_no_list_items');?>
				</p>
				<?php }?>
			</div>
		</div>
</div>
<?php
}
else {
?>
	<div id="col_left_50">
		<div class="box">
			<div class="box_header">
				<h1><?php $this->getLanguage('core_connections');?></h1>
			</div>

			<div class="box_body">
				<p>
					<?php $this->getLanguage('core_connections_inbound_intro');?>
				</p>
	
				<ul>
					<li><a href="index.php?t=network"><?php $this->getLanguage('core_connections_view_all');?></a></li>
					<li><a href="index.php?t=network&amp;v=trusted"><?php $this->getLanguage('core_connections_view_trusted');?></a></li>
				</ul>

				<p>
					<?php $this->getLanguage('core_connections_barr_intro');?>
				</p>
	
				<ul>
					<li><a href="index.php?t=network&amp;v=barred"><?php $this->getLanguage('core_connections_barred');?></a></li>
				</ul>
				
			</div>
		</div>
	</div>

	<div id="col_right_50">
		<?php
		if (isset($connection)) {

		if (isset($connection['attributes'])) {
		foreach($connection['attributes'] as $key => $i):
			if ($key == 'media/image/aspect11') {
				$identity_profile_avatar = $i;
				unset ($connection['attributes'][$key]);
			}
			elseif ($key == 'namePerson/friendly') {
				$identity_nickname = $i;
				unset ($connection['attributes'][$key]);
			}
		endforeach;
		}
		?>
		<div class="box">
			<div class="box_header">
				<h1><?php echo $identity_nickname;?></h1>
			</div>

			<div class="box_body">
				<h2><?php $this->getLanguage('core_profile');?></h2>

				<p>
					<?php
					if (isset($connection['attributes'])) {
					foreach($connection['attributes'] as $key => $i):
					?>
					<span class="profile_title"><?php $this->getLanguage('txt_' . $key);?></span>
					<span class="profile_value"><?php echo $i; ?></span>
					<br />
					<?php
					endforeach;
					
					if (isset($identity_profile_avatar)) {
					?>
						<span class="profile_title"><?php $this->getLanguage('txt_media/image/aspect11');?></span>
						<span class="profile_value"><img src="<?php echo $identity_profile_avatar; ?>" border="0" /></span>
						<br />
					<?php	
					}
					}
					?>
				</p>

				<h2><?php $this->getLanguage('core_connection');?></h2>
				<p>
					<span class="profile_title"><?php $this->getLanguage('common_openid');?></span>
					<span class="profile_value"><?php echo $connection[0]['connection_openid'];?></span>
				</p>

				<p>
					<span class="profile_title"><?php $this->getLanguage('core_connected_since');?></span>
					<span class="profile_value"><?php echo strftime("%d %b %G %H:%M", $connection[0]['connection_create_datetime']);?></span>
				</p>
				
				<p>
					<span class="profile_title"><?php $this->getLanguage('core_connected_last');?></span>
					<span class="profile_value"><?php echo strftime("%d %b %G %H:%M", $connection[0]['connection_last_datetime']);?></span>
				</p>
				
				<p>
					<span class="profile_title"><?php $this->getLanguage('common_total');?></span>
					<span class="profile_value"><?php echo $connection[0]['connection_total'];?></span>
				</p>

				<h2><?php $this->getLanguage('core_manage');?></h2>
				<p>
					<input type="checkbox" name="status_id" value="1"<?php if (isset($connection[0]['status_id']) && $connection[0]['status_id'] == 1) { echo "checked=\"checked\"";}?> /> <?php $this->getLanguage('core_barr_connection');?>
				</p>

				<p>
					<input type="checkbox" name="connection_trust" value="1"<?php if (isset($connection[0]['connection_trusted']) && $connection[0]['connection_trusted'] == 1) { echo "checked=\"checked\"";}?> /> <?php $this->getLanguage('core_trust_connection');?>
				</p>

				<p align="right">
					<input type="hidden" name="connection_id" value="<?php echo $connection[0]['connection_id'];?>" />
					<a href="index.php?t=network"><?php $this->getLanguage('core_connections_view_all');?></a>
					<input type="submit" name="update_connection" value="<?php $this->getLanguage('common_save');?>" />
				</p>
			</div>
		</div>
		<?php
		}
		else {
		?>
		<div class="box">
			<div class="box_header">
				<h1><?php $this->getLanguage('core_connections_inbound');?></h1>
			</div>

			<div class="box_body">
				
				<?php
				if (isset($connections)) {
				?>
				<table cellspacing="0" cellpadding="2" border="0" width="100%">
					<tr>
						<th colspan="2"><?php $this->getLanguage('core_connection');?></th>
						<th><?php $this->getLanguage('core_trust');?></th>
					</tr>
					<?php
					foreach ($connections as $key => $i):
					?>
					<tr>
						<td valign="top">
							<?php
							if (!empty($i['attributes']['media/image/aspect11'])) {
							?>
							<a href="index.php?t=network&amp;connection_id=<?php echo $i['connection_id']; ?>" title="<?php echo $i['attributes']['namePerson/friendly']; ?> - <?php echo $i['connection_openid']; ?>"><img src="<?php echo $i['attributes']['media/image/aspect11'];?>" style="border: solid 1px #000;margin-bottom:3px;" width="40" height="40" alt="" border="" /></a><br />
							<?php
							}
							else {
							?>
							<a href="index.php?t=network&amp;connection_id=<?php echo $i['connection_id']; ?>" title="<?php echo $i['attributes']['namePerson/friendly']; ?> - <?php echo $i['connection_openid']; ?>"><img src="<?php echo AM_TEMPLATE_PATH;?>img/no_avatar.png" width="40" height="40" style="border: solid 1px #000;margin-bottom:3px;" alt="" border="" /></a><br />
							<?php }?>
						</td>
						<td valign="top">
							<a href="index.php?t=network&amp;connection_id=<?php echo $i['connection_id']; ?>"><b><?php echo $i['attributes']['namePerson/friendly'];?></b></a><br />
							<?php $this->getLanguage('core_connected_since');?> <?php echo strftime("%d %b %G %H:%M", $i['connection_create_datetime']);?>
							(<?php $this->getLanguage('core_connected_last');?> <?php echo strftime("%d %b %G %H:%M", $i['connection_last_datetime']);?>).
						</td>
						<td valign="top">
							<input type="checkbox" name="connection_trusted[]" value="<?php echo $i['connection_id']; ?>"<?php if (!empty($i['connection_trusted'])) { echo "checked=\"checked\"";}?> />
						</td>
					</tr>
					<?php
					endforeach;
					?>
				</table>

				<p align="right">
					<input type="submit" name="update_inbound_trust" value="<?php $this->getLanguage('core_set_trust');?>" />
				</p>
				<?php
				}
				else {
				?>
				<p>
					<?php $this->getLanguage('common_no_list_items');?>
				</p>
				<?php }?>
			</div>
		</div>
		<?php }?>
	</div>
<?php }?>
</form>