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

<!-- Made with AROUNDMe identity server - http://www.barnraiser.org/ - Enjoy free software -->

<head>
	<?php
	if (defined('AM_IDENTITY_ID')) {
	?>
		<?php if (0) { ?><link rel="openid2.provider" href="<?php echo $openid['server']; ?>" /><?php }?>
		<link rel="openid.server" href="<?php echo $openid['server']; ?>" />
	<?php }?>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<?php
	if (isset($identity['language_code'])) {
	?>
	<meta http-equiv="Content-Language" content="<?php echo $identity['language_code'];?>" />
	<?php }?>

	<?php
	if (isset($webspace['webspace_title'])) {
	?>
	<title><?php echo $webspace['webspace_title'];?></title>
	<?php
	}
	else {
	?>
	<title><?php $this->getLanguage('common_html_title');?></title>
	<?php }?>
	
	
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

	<style type="text/css" id="css">
	<!--
	<?php
	if (isset($webspace['stylesheet_body'])) {
		echo $webspace['stylesheet_body'];
	}
	?>
	-->
	</style>

	<script type="text/javascript" src="<?php echo AM_TEMPLATE_PATH;?>js/functions.js"></script>

	<?php
	if (!empty($this->header_link_tag_arr)) {
	foreach ($this->header_link_tag_arr as $key => $i):
	?>
	<link rel="<?php echo $i[0];?>" type="<?php echo $i[1];?>" title="<?php echo $i[2];?>" href="<?php echo $i[3];?>" />
	<?php
	endforeach;
	}
	?>

	<link rel="icon" href="core/template/img/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="core/template/img/favicon.ico" type="image/x-icon">
</head>

<?php
if (!defined('AM_SCRIPT_NAME') && defined('AM_WEBPAGE_NAME')) {
?>	
<body id="am_webpage"  onload="checkImages();">
<?php
}
else {
?>
<body id="am_admin"  onload="checkImages();">
<?php }?>

	<div id="am_menu_container">
		<?php
		if (!defined('AM_IDENTITY_ID')) {
		?>
		<span id="am_menu_system">
			<ul>
				<li><a href="register.php"><?php $this->getLanguage('common_am_menu_register');?></a></li>
			</ul>
		</span>
		<?php }?>
	
		<?php
		if (defined('AM_OWNER_CONNECTION_ID')) {
		?>
		<span id="am_menu_webspace">
			<?php
			if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
			?>
				<?php
				if(defined('AM_WEBSPACE_ID')){
				?>
				<ul>
					<?php
					$link_css = "";
					if(!defined('AM_SCRIPT_NAME') && defined('AM_WEBSPACE_ID')){
						$link_css = " class=\"highlight\"";
					}
					?>
					<li><a href="index.php"<?php echo $link_css;?>><?php $this->getLanguage('common_am_menu_webspace');?></a></li>
	
					<?php
					$link_css = "";
					if (defined('AM_SCRIPT_NAME') && AM_SCRIPT_NAME == "setup") {
						$link_css = " class=\"highlight\"";
					}
					?>
					<li><a href="index.php?t=setup"<?php echo $link_css;?>><?php $this->getLanguage('common_am_menu_setup');?></a></li>
	
					<?php
					$link_css = "";
					if (defined('AM_SCRIPT_NAME') && AM_SCRIPT_NAME == "file") {
						$link_css = " class=\"highlight\"";
					}
					?>
					<li><a href="index.php?t=file"<?php echo $link_css;?>><?php $this->getLanguage('common_am_menu_files');?></a></li>
			
					<?php
					if(defined('AM_WEBPAGE_NAME')){
					?>
						<?php
						$link_css = "";
						if (defined('AM_SCRIPT_NAME') && AM_SCRIPT_NAME == "webpage") {
							$link_css = " class=\"highlight\"";
						}
						?>
						<li><a href="index.php?t=webpage&amp;wp=<?php echo AM_WEBPAGE_NAME;?>"<?php echo $link_css;?>><?php $this->getLanguage('common_am_menu_edit');?></a></li>
						<li><a href="#" onclick="javascript:launchPopupWindow('core/stylesheet_editor.php', '350', '550');"><?php $this->getLanguage('common_am_menu_style');?></a></li>
					<?php }?>
				</ul>
				<?php }?>
			<?php }?>
		</span>
		
		<span id="am_menu_identity">
			<ul>
				<?php
				if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
				?>
					<?php
					$link_css = "";
					if (defined('AM_SCRIPT_NAME') && AM_SCRIPT_NAME == "identity") {
						$link_css = " class=\"highlight\"";
					}
					?>
					<li><a href="index.php?t=identity"<?php echo $link_css;?>><?php $this->getLanguage('common_am_menu_identity');?></a></li>
				<?php }?>
		
				<?php
				if (defined('AM_IDENTITY_ID')) {
				if (!isset($_SESSION['connection_id'])) {
				?>
					<li><a href="index.php?t=connect"><?php $this->getLanguage('common_am_menu_connect');?></a></li>
				<?php
				}
				else {
				?>
					<li><a href="index.php?disconnect=1"><?php $this->getLanguage('common_am_menu_disconnect');?></a></li>
				<?php }}?>
			</ul>
		</span>
		<?php }?>
	</div>
	
	<?php
	if (!empty($GLOBALS['am_error_log'])) {
	?>
	<div id="error_container">
		<div class="content">
			<?php
			foreach($GLOBALS['am_error_log'] as $key => $i):
			?>
				<?php
				if (isset($this->lang['arr_am_error'][$i[0]])) {
					echo $this->lang['arr_am_error'][$i[0]];
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
	</div>
	<?php }?>
	
	<div id="body_container">
		<?php echo $content;?>
	</div>

	<div id="id_session_reload_image">
		<img name="session_reload_image" src="core/get_file.php?reloadsession=1" alt="" />
	</div>

	<div id="interface_system_message" style="display:none; z-index:500;">
		<div id="interface_system_message_header"></div>
		<div id="interface_system_message_body"></div>
		<div id="interface_system_message_footer" onclick="javascript:hideInterfaceSystemMessage();"><?php $this->getLanguage('common_close');?></div>
	</div>

	<!-- AROUNDMe identity server version <?php echo $am_release['version'];?> - Installed <?php echo $am_release['install_date'];?> -->
</body>
</html>