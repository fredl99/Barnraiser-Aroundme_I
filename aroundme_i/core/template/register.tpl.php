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
	<?php 
	if (empty($_REQUEST['stylesheet'])) {
	?>
	@import url(<?php echo AM_TEMPLATE_PATH;?>css/aroundme.css);
	<?php
	}
	else {
	?>
	@import url(<?php echo $_REQUEST['stylesheet']; ?>);
	<?php }?>
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
	if (!empty($GLOBALS['am_error_log'])) {
	?>
	<div id="error_container">
		<?php
		foreach($GLOBALS['am_error_log'] as $key => $i):
		?>
			<?php
			if (isset($lang['error'][$i[0]])) {
				echo $lang['error'][$i[0]];
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
	
	<?php
	if (isset($stage) && $stage < 6) {
	?>
	<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username'];?>" />
	<input type="hidden" name="pass1" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" />
	<input type="hidden" name="identity_email" value="<?php if (isset($_POST['identity_email'])) echo $_POST['identity_email']; ?>" />
	
	<?php
	if (empty($_REQUEST['remote'])) {
	?>
	<div id="col_left_50">
		<div class="box">
			<div class="box_header">
				<h1><?php $this->getLanguage('register_create');?></h1>
			</div>
					
			<div class="box_body">
				<p>
					<?php $this->getLanguage('register_create_intro');?>
				</p>
						
				<ol>
					<?php
					if (isset($stage) && $stage > 1) {
					?>
					<li><del><?php $this->getLanguage('register_agree_terms');?></del></li>
					<?php
					}
					else {
					?>
					<li><?php $this->getLanguage('register_agree_terms');?></li>
					<?php }?>
							
					<?php
					if (isset($stage) && $stage > 2) {
					?>
					<li><del><?php $this->getLanguage('register_username_openid');?></del></li>
					<ul>
						<li><?php $this->getLanguage('register_username');?><b> <?php echo $username; ?></b></li>
						<li><?php $this->getLanguage('register_openid');?><b> <?php echo $openid; ?></b></li>
					</ul>
					<?php
					}
					else {
					?>
					<li><?php $this->getLanguage('register_username_openid');?></li>
					<?php }?>
							
					<?php
					if (isset($stage) && $stage > 3) {
					?>
					<li><del><?php $this->getLanguage('register_choose_password');?></del></li>
					<?php
					}
					else {
					?>
					<li><?php $this->getLanguage('register_choose_password');?></li>
					<?php }?>
					
					<?php
					if (isset($stage) && $stage > 4) {
					?>
					<li><del><?php $this->getLanguage('register_challenge');?></li>
					<?php
					}
					else {
					?>
					<li><?php $this->getLanguage('register_challenge');?></li>
					<?php }?>
					
					<?php
					if (isset($stage) && $stage > 5) {
					?>
					<li><del><?php $this->getLanguage('register_profile_information');?></del></li>
					<?php
					}
					else {
					?>
					<li><?php $this->getLanguage('register_profile_information');?></li>
					<?php }?>
				</ol>
			</div>
		</div>
	</div>
	<?php }?>
	
	<div id="col_right_50">
	
	<?php
	if (isset($stage) && $stage == 1) {
	?>
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('register_agree_terms');?></h1>
		</div>
	
		<div class="box_body">
			<div style="width:100%; height:380px; overflow:auto;">
				<?php include('core/language/en/terms_of_use.lang.php');?>
			</div>

			<p align="right">
				<input type="submit" name="reject_terms" value="<?php $this->getLanguage('register_disagree');?>" />
				<input type="submit" name="accept_terms" value="<?php $this->getLanguage('register_agree');?>" />
			</p>
		</div>
	</div>
			
	<?php
	}
	elseif (isset($stage) && $stage == 2) {
	?>
					
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('register_username_openid');?></h1>
		</div>

		<div class="box_body">
	
			<?php 
			if (!isset($name_ok) && !isset($name_in_use)) { 
			?>
			<p>
				<?php $this->getLanguage('register_username_intro');?>
			</p>
				
			<p>
				<label for="id_username"><?php $this->getLanguage('common_name');?></label>
				<input type="text" name="username" id="id_username" value="" />
			</p>

			<p align="right">
				<input type="submit" value="<?php $this->getLanguage('register_check_availability');?>" name="check_username" />
			</p>
			<?php }?>
		
			<?php 
			if (isset($name_ok)) { 
			?>
		
			<p>
				<?php $this->getLanguage('register_username_available');?>
				<input type="hidden" name="username" id="id_username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" />
			</p>

			<p>
				<?php
				$lang_item = $this->lang['register_openid_preview'];
				$openid = str_replace("REPLACE", $_POST['username'], $domain_replace_pattern);
				$lang_item = str_replace("SYS_KEYWORD_OPENID", $openid, $lang_item);
				echo $lang_item;
				?>
			</p>

			<p>
				<?php $this->getLanguage('register_choose_name');?>
			</p>
		
			<p align="right">
				<input type="submit" value="<?php $this->getLanguage('register_choose_again');?>" name="choose_again" />
				<input type="submit" value="<?php $this->getLanguage('register_use_name');?>" name="choose_username" />
			</p>
			<?php }?>
		
			<?php 
			if (isset($name_in_use)) { 
			?>
			<p>
				<?php
				$lang_item = $this->lang['register_name_taken'];
				$lang_item = str_replace("SYS_KEYWORD_NAME", $_POST['username'], $lang_item);
				echo $lang_item;
				?>
			</p>
			
			<p>
				<label for="id_username"><?php $this->getLanguage('common_name');?></label>
				<input type="text" name="username" id="id_username" value="" />
			</p>

			<p align="right">
				<input type="submit" value="<?php $this->getLanguage('register_check_availability');?>" name="check_username" />
			</p>
			<?php }?>
		</div>
	</div>

	<?php
	}
	elseif (isset($stage) && $stage == 3) {
	?>
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('register_choose_password');?></h1>
		</div>

		<div class="box_body">
			<p>
				<label for="id_pass1"><?php $this->getLanguage('register_password');?></label>
				<input type="password" name="pass1" id="id_pass1" value="" />
			</p>
	
			<p>
				<label for="id_pass2"><?php $this->getLanguage('register_confirm_password');?></label>
				<input type="password" name="pass2" id="id_pass2" value="" />
			</p>
			
			<p>
				<i><?php $this->getLanguage('register_password_hint');?></i>
			</p>

			<h3><?php $this->getLanguage('register_password_retrieval_email'); ?></h3>

			<p>
				<?php $this->getLanguage('register_password_retrieval_email_intro'); ?>
			</p>
			
			<p>
				<label for="id_identity_email"><?php $this->getLanguage('common_email'); ?></label>
				<input type="text" name="identity_email" id="id_identity_email" value=""/>
			</p>
					
			<p align="right">
				<input type="submit" value="<?php $this->getLanguage('register_set_password');?>" name="register_password" class="input_submit" style="margin:2px;" /><br />
			</p>
					
		</div>
	</div>

	<?php
	}
	elseif (isset($stage) && $stage == 4) {
	?>

	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('register_challenge');?></h1>
		</div>

		<div class="box_body">
			
			<p>
				<?php echo $maptcha; ?>
			</p>
			<p>
				<label for="id_captcha"><?php $this->getLanguage('register_response');?></label>
				<input type="text" name="maptcha_text" id="id_captcha" value="" />
			</p>

			<p class="note"><br />
				<?php $this->getLanguage('register_challange_example'); ?>
			</p>
			
			<p align="right">
				<input type="submit" value="<?php $this->getLanguage('register_submit_response');?>" name="register_challange" style="margin:2px;" /><br />
			</p>
		</div>
	</div>
	<?php 
	}
	elseif (isset($stage) && $stage == 5) {
	?>
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('register_identity_information');?></h1>
		</div>
		
		<div class="box_body">
			<p>
				<?php $this->getLanguage('register_identity_optional');?>
			</p>
		
			<p>
				<label for="id_nickname"><?php $this->getLanguage('txt_namePerson/friendly');?></label>
				<input type="text" name="identity[nickname]" id="id_nickname" />
			</p>
			<p>
				<label for="id_email"><?php $this->getLanguage('txt_contact/email'); ?></label>
				<input type="text" name="identity[email]" id="id_email" />
			</p>
			<p>
				<label for="id_country"><?php $this->getLanguage('txt_contact/country/home'); ?></label> 
				<select id="id_country" name="identity[country]">
					<option value="0" selected="selected"><?php $this->getLanguage('common_none'); ?></option>
					<?php
					foreach($this->lang['arr_identity_field']['country'] as $key => $value):
					?>
						<option value="<?php echo $key ?>"><?php echo $value; ?></option>
					<?php
					endforeach; 
					?>
				</select>
			</p>
			<p>
				<label for="id_timezone"><?php $this->getLanguage('txt_pref/timezone'); ?></label> 
				<select id="id_timezone" name="identity[timezone]">
					<option value="0" selected="selected"><?php $this->getLanguage('common_none'); ?></option>
					<?php
					foreach($this->lang['arr_identity_field']['pref/timezone'] as $key => $value):
					?>
						<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
					<?php 
					endforeach;
					?>
				</select>
			</p>
			<p>
				<label for="id_language"><?php $this->getLanguage('txt_pref/language'); ?></label> 
				<select id="id_language" name="identity[language]">
					<option value="0" selected="selected"><?php $this->getLanguage('common_none'); ?></option>
					<?php
					foreach($this->lang['arr_identity_field']['pref/language'] as $key => $value):
					?>
						<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
					<?php
					endforeach;
					?>
				</select>
			</p>
			<p>
				<label for="frm_file"><?php $this->getLanguage('core_upload_file'); ?></label>
				<input type="file" name="frm_file" id="frm_file" /><br />
			</p>
		</div>
	</div>

	<p align="right">
		<input type="submit" name="save_profile_information" value="save profile information"/>
	</p>
	<?php }?>
	</form>
	<?php
	}
	elseif (isset($stage) && $stage == 6) {
	?>
			
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('register_finish');?></h1>
		</div>
		<?php
		if (empty($_REQUEST['remote'])) {
		?>
		<div class="box_body">
			<p>
				<?php 
				$lang_item = $this->lang['register_finish_intro'];
				$lang_item = str_replace('AM_KEYWORD_OPENID', $openid, $lang_item);
				echo $lang_item;
				?>
			</p>

			<h3><?php $this->getLanguage('register_logging_in');?></h3>
			<ol>
				<li><?php $this->getLanguage('register_logging_in_help1');?></li>
				<li><?php $this->getLanguage('register_logging_in_help2');?></li>
				<li><?php $this->getLanguage('register_logging_in_help3');?></li>
				<li><?php $this->getLanguage('register_logging_in_help4');?></li>
			</ol>
		</div>
		<?php
		}
		else {
		?>
		<div class="box_body">
		<form method="post" action="<?php echo $_REQUEST['return_to']; ?>">
			<p>
				<?php 
				$lang_item = $this->getLanguage('register_finish_intro2');
				$lang_item = str_replace('AM_KEYWORD_OPENID', $openid, $lang_item);
				echo $lang_item;
				?>
			</p>
			
			<p>
				<label for="openid_login"><?php $this->getLanguage('common_openid');?></label>	
				<input type="text" id="openid_login" name="openid_login" value="<?php echo $openid;?>" onFocus="this.value=''; return false;" />
			</p>
			
			<p>
				<?php $this->getLanguage('register_logging_in_help5');?>
			</p>
			
			<p align="right">
				<input type="submit" name="connect"  value="<?php $this->getLanguage('common_go');?>" />
			</p>
		</form>
		</div>
		<?php }?>
	</div>
	<?php }?>
	</div>
</body>
</html>