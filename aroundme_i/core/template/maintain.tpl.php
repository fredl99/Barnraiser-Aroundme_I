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

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title><?php $this->getLanguage('common_html_title');?></title>
	
	<style type="text/css">
	<!--
	@import url(<?php echo AM_TEMPLATE_PATH;?>css/aroundme.css);
	-->
	</style>
	
	<!--[if IE]>
	<style type="text/css">
	@import url(<?php echo AM_TEMPLATE_PATH;?>css/aroundme-IE.css);
	</style>
	<![endif]-->
</head>

<body id="am_admin">
	<?php
	if (isset($_SESSION['am_maintainer'])) {
	?>
	<div id="am_menu_container">
		<ul>
			<li><a href="maintain.php"><?php $this->getLanguage('maintain_menu_home');?></a></li>
			<li><a href="maintain.php?v=list"><?php $this->getLanguage('maintain_menu_accounts');?></a></li>
			<li><a href="maintain.php?v=config"><?php $this->getLanguage('maintain_menu_configure');?></a></li>
			<li><a href="maintain.php?disconnect=1"><?php $this->getLanguage('common_am_menu_disconnect');?></a></li>
		</ul>
	</div>
	<?php }?>
	
	<?php
	if (!empty($GLOBALS['am_error_log'])) {
	?>
	<div id="error_container">
		<?php
		foreach($GLOBALS['am_error_log'] as $key => $i):
		?>
			<?php
			if (isset($lang['arr_am_error'][$i[0]])) {
				echo $lang['arr_am_error'][$i[0]];
			}
			else {
				echo $i[0];
			}
	
			if (!empty($i[1])) {
				echo ": " . $i[1];
			}?>
			<br />
		<?php
		endforeach;
		?>
	</div>
	<?php }?>
	
	<div id="body_container">
		<form action="maintain.php" method="POST">
		<input type="hidden" name="identity_id" value="<?php if (isset($maintain_identity['identity_id'])) { echo $maintain_identity['identity_id'];}?>" />

		<?php
		if (isset($_SESSION['am_maintainer']) && isset($_REQUEST['v']) && $_REQUEST['v'] == "list") {
		?>
			<div class="box">
				<?php
				if (isset($maintain_identity)) {
				?>
				<div class="box_header">
					<h1><?php $this->getLanguage('maintain_manage_identities');?></h1>
				</div>

				<div class="box_body">
					<p>
						<b><?php $this->getLanguage('common_name');?></b><br />
						<a href="<?php echo $maintain_identity['identity_url'];?>"><?php echo $maintain_identity['identity_name'];?></a>
					</p>
		
					<p>
						<b><?php $this->getLanguage('common_nickname');?></b><br />
						<?php echo $maintain_identity['attributes']['namePerson/friendly'];?>
					</p>
		
					<?php
					if (isset($maintain_identity['attributes']['contact/email'])) {
					?>
					<p>
						<b><?php $this->getLanguage('common_email');?></b><br />
						<a href="mailto:<?php echo $maintain_identity['attributes']['contact/email'];?>"><?php echo $maintain_identity['attributes']['contact/email'];?></a>
					</p>
					<?php }?>
					
					<p>
						<b><?php $this->getLanguage('maintain_created');?></b><br />
						<?php echo strftime("%d %b %G", $maintain_identity['identity_create_datetime']);?>
					</p>
		
					<p>
						<b><?php $this->getLanguage('common_language');?></b><br />
						<?php echo $maintain_identity['language_code'];?>
					</p>

					<p>
						<label for="id_status"><?php $this->getLanguage('common_status');?></label><br />
						<select name="status_id" id="id_status">
							<?php
							$selected = "";
		
							if ($maintain_identity['status_id'] == 1) {
								$selected = " selected=\"selected\"";
							}
							?>
							<option value="1"<?php echo $selected ;?>><?php echo $this->lang['arr_identity_status'][1];?></option>
							<?php
							$selected = "";
		
							if ($maintain_identity['status_id'] == 2) {
								$selected = " selected=\"selected\"";
							}
							?>
							<option value="2"<?php echo $selected ;?>><?php echo $this->lang['arr_identity_status'][2];?></option>
							<?php
							$selected = "";
		
							if ($maintain_identity['status_id'] == 3) {
								$selected = " selected=\"selected\"";
							}
							?>
							<option value="3"<?php echo $selected ;?>><?php echo $this->lang['arr_identity_status'][3];?></option>
						</select>
					</p>

					<p>
						<label for="id_webspace_allocation"><?php $this->getLanguage('maintain_allocation');?></label><br />
						<input id="id_webspace_allocation" size="5" name="webspace_allocation" value="<?php echo $maintain_identity['webspace_allocation'];?>" />&nbsp;kb
					</p>
		
					<p align="right">
						<input type="submit" name="update_identity" value="<?php $this->getLanguage('common_save');?>" />
					</p>
				</div>
			</div>

			<?php
			}
			elseif (isset($identities)) {
			?>
			<div class="box">
				<div class="box_header">
					<h1><?php $this->getLanguage('maintain_manage_webspace');?></h1>
				</div>

				<div class="box_body">
					<table cellspacing="2" cellpadding="2" border="0" width="100%">
						<tr>
							<th><?php $this->getLanguage('common_edit');?></th>
							<th><?php $this->getLanguage('maintain_owner');?></th>
							<th><?php $this->getLanguage('maintain_created');?></th>
							<th><?php $this->getLanguage('maintain_status');?></th>
							<th><?php $this->getLanguage('common_language');?></th>
							<th><?php $this->getLanguage('common_webspace');?></th>
							<th><?php $this->getLanguage('maintain_allocation');?></th>
							<th><?php $this->getLanguage('common_view');?></th>
						</tr>
						<?php
						foreach ($identities as $key => $i):
						?>
						<tr>
							<td valign="top">
								<a href="maintain.php?identity_id=<?php echo $i['identity_id'];?>"><?php echo $i['identity_name'];?></a>
							</td>
							<td valign="top">
								<?php
								if (isset($i['attributes']['contact/email'])) {
								?>
									<a href="mailto:<?php echo $i['attributes']['contact/email'];?>"><?php echo $i['attributes']['namePerson/friendly'];?></a>
								<?php
								}
								else {
									echo $i['attributes']['namePerson/friendly'];
								}
								?>
							</td>
							<td valign="top">
								<?php echo strftime("%d %b %G", $i['identity_create_datetime']);?>
							</td>
							<td valign="top">
								<?php
								if (isset($lang['arr_identity_status'][$i['status_id']])) {
									echo $lang['arr_identity_status'][$i['status_id']];
								}?>
							</td>
							<td valign="top">
								<?php echo $i['language_code'];?>
							</td>
							<td valign="top">
								<?php
								if (isset($i['webspace_id'])) {
									$this->getLanguage('common_yes');
								}
								?>
							</td>
							<td valign="top">
								<?php
								if (isset($i['webspace_allocation'])) {
									echo $i['webspace_allocation'];
								}
								?>
							</td>
							<td valign="top">
								<a href="<?php echo $i['identity_url'];?>"><?php $this->getLanguage('common_view');?></a>
							</td>
						</tr>
						<?php
						endforeach;
						?>
					</table>
		
					<p class="buttons">
						<a href="register.php"><?php $this->getLanguage('maintain_create');?></a>
					</p>
				</div>
			</div>
			
			<?php
			}
			else {
			?>
			<h1><?php $this->getLanguage('maintain_manage_identities');?></h1>

			<p>
				<?php $this->getLanguage('common_no_list_items');?>
			</p>

			<p class="buttons">
				<a href="register.php"><?php $this->getLanguage('maintain_create');?></a>
			</p>
			<?php }?>
			
		<?php
		}
		elseif (isset($_SESSION['am_maintainer']) && isset($_REQUEST['v']) && $_REQUEST['v'] == "config") {
		?>

			<div id="col_left_50">
				<div class="box">
					<div class="box_header">
						<h1><?php $this->getLanguage('maintain_configure_webspace');?></h1>
					</div>

					<div class="box_body">
						<p>
							<label for="id_file_default_allocation"><?php $this->getLanguage('maintain_allocation');?></label>
							<input type="text" name="file_default_allocation" id="id_file_default_allocation" value="<?php echo $core_config['file']['default_allocation'];?>" />
						</p>

						<p class="note">
							<?php $this->getLanguage('maintain_allocation_hint');?>
						</p>

						<p>
							<label for="id_display_max_list_rows"><?php $this->getLanguage('maintain_list_length');?></label>
							<input type="text" name="display_max_list_rows" id="id_display_max_list_rows" value="<?php echo $core_config['display']['max_list_rows'];?>" />
						</p>

						<p class="note">
							<?php $this->getLanguage('maintain_list_length_hint');?>
						</p>

						<p>
							<label for="id_openid0"><?php $this->getLanguage('maintain_create_type_label');?></label><br />
							<input type="radio" name="identity_creation_type" id="id_openid0" value="0" checked="checked" /><label for="id_openid0" style="width: auto; float: none; font-weight: normal;"><?php $this->getLanguage('maintain_create_type_maintainer');?></label><br />
							<input type="radio" name="identity_creation_type" id="id_openid1" value="1"<?php if ($core_config['am']['identity_creation_type'] == 1) { echo " checked=\"checked\"";}?> /><label for="id_openid1" style="width: auto; float: none; font-weight: normal;"><?php $this->getLanguage('maintain_create_type_approve');?></label><br />
							<input type="radio" name="identity_creation_type" id="id_openid2" value="2"<?php if ($core_config['am']['identity_creation_type'] == 2) { echo " checked=\"checked\"";}?> /><label for="id_openid2" style="width: auto; float: none; font-weight: normal;"><?php $this->getLanguage('maintain_create_type_auto');?></label>
						</p>

						<p>
							<label for="id_reserved_identity_names"><?php $this->getLanguage('maintain_reserved_names');?></label>
							<input type="text" name="reserved_identity_names" id="id_reserved_identity_names" value="<?php echo $core_config['am']['reserved_identity_names'];?>" />
						</p>

						<p class="note">
							<?php $this->getLanguage('maintain_exclusions_hint');?>
						</p>

						<p class="buttons">
							<input type="submit" name="save_config" value="<?php $this->getLanguage('common_save');?>" />
						</p>
					</div>
				</div>
			</div>
					
			<div id="col_right_50">
				<div class="box">
					<div class="box_header">
						<h1><?php $this->getLanguage('maintain_configure_email');?></h1>
					</div>

					<div class="box_body">
						<p>
							<label for="id_email_address"><?php $this->getLanguage('maintain_email_default');?></label>
							<input type="text" name="email_address" id="id_email_address" value="<?php echo $core_config['mail']['email_address'];?>" />
						</p>

						<p class="note">
							<?php $this->getLanguage('maintain_default_email_hint');?>
						</p>

						<p>
							<label for="id_email_from_name"><?php $this->getLanguage('maintain_email_from_name');?></label>
							<input type="text" name="email_from_name" id="id_email_from_name" value="<?php echo $core_config['mail']['from_name']?>" />
						</p>

						<p class="note">
							<?php $this->getLanguage('maintain_email_from_name_hint');?>
						</p>

						<p>
							<label for="id_email_host"><?php $this->getLanguage('maintain_email_host');?></label>
							<input type="text" name="email_host" id="id_email_host" value="<?php echo $core_config['mail']['host']?>" />
						</p>

						<p class="note">
							<?php $this->getLanguage('maintain_email_host_hint');?>
						</p>

						<p>
							<label for="id_email_smtp_user"><?php $this->getLanguage('common_username');?></label>
							<input type="text" name="smtp_user" id="id_email_smtp_user" value="<?php if (isset($core_config['mail']['smtp']['username'])) { echo $core_config['mail']['smtp']['username'];}?>" />
						</p>

						<p>
							<label for="id_email_smtp_password"><?php $this->getLanguage('common_password');?></label>
							<input type="text" name="smtp_password" id="id_email_smtp_password" value="<?php if (isset($core_config['mail']['smtp']['password'])) { echo $core_config['mail']['smtp']['password'];}?>" />
						</p>
						
						<p class="note">
							<?php $this->getLanguage('maintain_email_smtp_user_pass');?>
						</p>

						<p class="buttons">
							<input type="submit" name="save_email" value="<?php $this->getLanguage('common_save');?>" />
						</p>
					</div>
				</div>


				<div class="box">
					<div class="box_header">
						<h1><?php $this->getLanguage('maintain_configure_domain');?></h1>
					</div>

					<div class="box_body">
						<p>
							<?php $this->getLanguage('maintain_configure_domain_intro');?>
						</p>

						</p>

						<p>
							<label for="id_domain_preg_pattern"><?php $this->getLanguage('maintain_pattern_parse');?></label>
							<input type="text" name="domain_preg_pattern" id="domain_preg_pattern" value="<?php echo $domain_preg_pattern;?>" />
						</p>

						<p>
							<label for="id_domain_replace_pattern"><?php $this->getLanguage('maintain_pattern_render');?></label>
							<input type="text" name="domain_replace_pattern" id="id_domain_replace_pattern" value="<?php echo $domain_replace_pattern;?>" />
						</p>

						<p class="buttons">
							<input type="submit" name="save_patterns" value="<?php $this->getLanguage('common_save');?>" />
						</p>
					</div>
				</div>
			</div>
		<?php
		}
		elseif (isset($_SESSION['am_maintainer'])) {
		?>
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
					<td valign="top" width="50%">
						<?php
						if (isset($_REQUEST['install']) && $_REQUEST['install'] == "complete") {
						?>
						<div class="box">
							<div class="box_header">
								<h1><?php $this->getLanguage('maintain_installation_complete');?></h1>
							</div>
			
							<div class="box_body">
								<p>
									<?php $this->getLanguage('maintain_installation_complete_intro');?>
								</p>
							</div>
						</div>
						<?php }?>
						
						<div class="box">
							<div class="box_header">
								<h1><?php $this->getLanguage('maintain_statistics');?></h1>
							</div>
			
							<div class="box_body">
								<?php
								if (isset($statistics) && !empty($statistics)) {
								?>
								<table cellspacing="4" cellpadding="2" border="0">
								<?php
								foreach ($statistics as $key => $i):
								?>
								<tr>
									<td>
										<?php echo $lang['arr_identity_status'][$i[0]];?>
									</td>
									<td align="right">
										<?php echo $i[1];?>
									</td>
								</tr>
								<?php
								endforeach;
								?>
								</table>
								<?php }?>
							</div>
						</div>
					</td>

					<td valign="top" width="50%">
						<div class="box">
							<div class="box_header">
								<h1><?php $this->getLanguage('maintain_create_account');?></h1>
							</div>
			
							<div class="box_body">
								<p>
									<?php $this->getLanguage('maintain_create_account_intro');?>
								</p>
								
								<ul>
									<li><a href="register.php"><?php $this->getLanguage('maintain_create');?></a></li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
		<?php
		}
		else {
		?>
			<div class="box" style="text-align:left;margin-left:auto;margin-right:auto; width:380px;">
				<h1><?php $this->getLanguage('maintain_access');?></h1>
			
				<p>
					<label for="id_username"><?php $this->getLanguage('common_username');?></label>
					<input type="text" name="username" id="id_username"/>
				</p>

				<p>
					<label for="id_password"><?php $this->getLanguage('common_password');?></label>
					<input type="password" name="password" id="id_password"/>
				</p>

				<p>
					<input name="connect" type="submit" value="<?php $this->getLanguage('common_login');?>" />
				</p>
			</div>
		<?php }?>
	</form>
	</body>
</html>