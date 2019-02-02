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
<div id="col_left_50">
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('new_password_intro_title'); ?></h1>
		</div>

		<div class="box_body">
			<?php $this->getLanguage('new_password_intro'); ?>
		</div>
	</div>
</div>
<div id="col_right_50">
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('new_password_process_title'); ?></h1>
		</div>
		
		<div class="box_body">
			<?php
			if (isset($display) && $display == 'new_password_step_1') {
			?>
				<p>
					<?php $this->getLanguage('new_password_request_password_intro'); ?>
					<form method="post">
						<input type="submit" name="request_email" value="<?php $this->getLanguage('new_password_sub'); ?>"/>
					</form>
				</p>
				<?php
				if (isset($email_sent)) {
				?>
					<p>
						<?php $this->getLanguage('new_password_email_1_sent'); ?>
					</p>
				<?php }?>
				
				<?php
				if (isset($new_password)) {
				?>
					<p>
						<?php $this->getLanguage('new_password_email_2_sent'); ?>
					</p>
				<?php }?>
			<?php
			}
			elseif (isset($display) && $display == 'email_not_verified') {
			?>
				<p>
					<?php $this->getLanguage('new_password_email_not_verified'); ?>
				</p>
			<?php 
			}
			elseif (isset($display) && $display == 'new_password_step_2') {
			?>
				<p>
					<?php $this->getLanguage('new_password_sent'); ?>
				</p>
			<?php }?>
		</div>
	</div>

	<!-- AROUNDMe identity server version <?php echo $am_release['version'];?> - Installed <?php echo $am_release['install_date'];?> -->
</div>
</body>