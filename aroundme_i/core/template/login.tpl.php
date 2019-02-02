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

<form method="post">
<input type="hidden" name="data" value="<?php echo urlencode(serialize($_GET)); ?>" />

<div id="col_left_50">
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_security');?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php $this->getLanguage('core_security_intro1');?>
			</p>
			
			<p>
				<img src="<?php echo AM_TEMPLATE_PATH;?>img/browser_warning.png" alt="picture of a browsers url field" style="border: 1px solid #eee;" />
			</p>
			
			<p>
				<?php $this->getLanguage('core_security_intro2');?>
			</p>
			
			<p>
				<img src="<?php echo AM_TEMPLATE_PATH;?>img/password_warning.png" alt="picture of a browsers url field" style="border: 1px solid #eee;" />
			</p>
			
			<p>
				<?php $this->getLanguage('core_security_intro3');?>
			</p>
			
		</div>
	</div>
</div>
<div id="col_right_50">
	<div class="login">
		<div class="box">
			<div class="box_header">
				<h1><?php $this->getLanguage('core_openid_login');?></h1>
			</div>
		
			<div class="box_body">
				<p>
					<label for="id_username" style="font-weight: bold;"><?php $this->getLanguage('core_username');?></label>
					<input type="text" id="id_username" name="username" value="<?php if (isset($identity_name)) echo $identity_name; ?>" style="font-weight: bold; border: 1px solid black;" title="enter your username here" <?php if (isset($identity_name)) echo "disabled"; ?>/>
					<?php
					if (isset($identity_name)) {
					?>
					<input type="hidden" name="username" value="<?php if (isset($identity_name)) echo $identity_name; ?>" />
					<?php }?>
					<label for="id_password" style="font-weight: bold;"><?php $this->getLanguage('core_password');?></label>
					<input type="password" id="id_password" name="passwd" value="" style="font-weight: bold; border: 1px solid black;" title="enter your password here"/>
	
					<input type="submit" name="login" value="<?php $this->getLanguage('common_login');?>" style="font-weight: bold; border: 1px solid black; cursor: pointer;" title="click to login"/>
				</p>
				
				<?php 
				if (!empty($_REQUEST['openid_mode'])) {
				?>
				<p>	
					<label for="id_reset_trust"><?php $this->getLanguage('core_reset_trust');?></label>
					<input id="id_reset_trust" name="reset_trust" type="checkbox" value="1"/>
				</p>
				<?php }?>
				<p>
					<label for="id_remember_me"><?php $this->getLanguage('core_remember_me'); ?></label>
					<input type="checkbox" id="id_remember_me" name="remember_me" type="checkbox" value="1"/>
				</p>
				<?php 
				if (empty($_REQUEST['openid_mode'])) {
				?>
					<p>
						<a href="new_password.php"><?php $this->getLanguage('core_new_password');?></a>
					</p>
				<?php }?>
			</div>
		</div>
	</div>
</div>
</div>
</form>