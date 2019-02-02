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

<div id="col_left_50">
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_identity');?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php $this->getLanguage('core_identity_intro');?><br />
			</p>
		</div>
	</div>

	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_account_email');?></h1>
		</div>
		<div class="box_body">
				<?php
				if (empty($identity['identity_email'])) {
				?>
					<div style="border: 2px solid red; background-color: #f8f8f8; padding: 5px;">
					<p>
						<?php $this->getLanguage('core_no_account_email');?>
					</p>
					<p>
						<form method="post">
							<label><?php $this->getLanguage('core_label_email');?></label>
							<input type="text" name="identity_email" value=""/>
							<input type="submit" name="send_verify_email" value="<?php $this->getLanguage('core_update_email'); ?>"/>
							<input type="hidden" name="send_verify_email" value="1"/>
						</form>
					</p>
					</div>
				<?php
				}
				else {
				?>
					<?php
					if (empty($identity['identity_email_verified'])) {
					?>
					<div style="border: 2px solid red; background-color: #f8f8f8; padding: 5px;">
					<form method="post">
					<p>
							<label><?php $this->getLanguage('core_label_email');?></label>
							<input type="text" name="identity_email" value="<?php echo $identity['identity_email']; ?>"/>
					</p>
					<p>
						<?php $this->getLanguage('core_account_email_not_verified');?>
					</p>
					<?php
					if (isset($email_sent)) {
					?>
						<p>
							<?php $this->getLanguage('core_account_email_sent');?>
						</p>
					<?php }?>
					<p>
						<input type="submit" name="send_verify_email" value="<?php $this->getLanguage('core_account_email_send_email');?>"/>
					</p>
					</form>
					</div>
					<?php 
					}
					else {
					?>
						<p>
							<form method="post">
								<label><?php $this->getLanguage('core_label_email');?></label>
								<input type="text" name="identity_email" value="<?php echo $identity['identity_email']; ?>"/>
								<input type="submit" name="update_identity_email" value="<?php $this->getLanguage('core_update_email'); ?>"/>
								<input type="hidden" name="update_identity_email" value="1"/>
							</form>
						</p>
						<p>
							<?php $this->getLanguage('core_email_verified'); ?>
						</p>
					<?php }?>
				<?php }?>
		</div>
	</div>
	

	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_identity_profile');?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php
				if (isset($identity_attributes)) {
				foreach($identity_attributes as $key => $i):

				if ($key == 'media/image/aspect11') {
					$identity_profile_avatar = $i;
					break;
				}
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

			<p>
				<a href="index.php?t=profile"><?php $this->getLanguage('common_edit');?></a>
			</p>
		</div>
	</div>
</div>
	
<div id="col_right_50">
	<?php
	if(!defined('AM_WEBSPACE_ID')){
	?>
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_create_webspace');?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php $this->getLanguage('core_create_webspace_intro');?>
			</p>

			<p>
				<a href="index.php?t=create_webspace"><?php $this->getLanguage('common_create');?></a>
			</p>
		</div>
	</div>
	<?php }?>
	

	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_connections');?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php $this->getLanguage('core_connections_inbound_intro');?>
			</p>

			<ul>
				<li><a href="index.php?t=network"><?php $this->getLanguage('core_view_inbound');?></a></li>
				<li><a href="index.php?t=network&amp;v=outbound"><?php $this->getLanguage('core_view_outbound');?></a></li>
			</ul>
		</div>
	</div>


	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_statistics');?></h1>
		</div>

		<div class="box_body">
			<?php
			if (isset($statistics)) {
			?>
			<table cellspacing="0" cellpadding="2" border="0" width="100%">
			<?php
			foreach ($statistics as $key => $i):
			?>
			<tr>
				<td valign="top">
					<?php echo  $this->lang['arr_statistics'][$key];?>
				</td>
				<td valign="top">
					<?php echo $i;?>
				</td>
			</tr>
			<?php
			endforeach;
			?>
			</table>
			<?php }?>
		</div>
	</div>
</div>