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

<?php
if (isset($display) && $display == "no_identity") {
?>
	<div class="box">
		<div class="box_body">
			<p>
				<?php
				$lang_item = $this->lang['core_webspace_unclaimed'];
				$lang_item = str_replace('AM_KEYWORD_IDENTITY', $identity_openid, $lang_item);
				echo $lang_item;
				?>
			</p>

			<p>
				<a href="register.php?account=<?php echo $identity_account;?>"><?php $this->getLanguage('core_webspace_claim');?></a>
			</p>
		</div>
	</div>
<?php
}
elseif (isset($display) && $display == "default_domain_name") {
?>
	<div id="col_left_50">
		<div class="box">
			<div class="box_header">
				<h1><?php $this->getLanguage('core_welcome_title');?></h1>
			</div>

			<div class="box_body">
				<p>
					<?php $this->getLanguage('core_welcome_intro');?>
				</p>
			</div>
		</div>
	</div>

	<div id="col_right_50">
		<div class="box">
			<div class="box_header">
				<h1><?php $this->getLanguage('core_openid_title');?></h1>
			</div>

			<div class="box_body">
				<p>
					<?php $this->getLanguage('core_openid_intro');?>
				</p>
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
				<h1><?php $this->getLanguage('core_identity_title');?></h1>
			</div>
			<div class="box_body">
				<p>
					<?php
					$lang_item = $this->lang['core_identity_page_intro'];
					$lang_item = str_replace('AM_KEYWORD_IDENTITY', $identity_openid, $lang_item);
					echo $lang_item;
					?>
				</p>
			</div>
		</div>
	</div>
	
	<div id="col_right_50">
		<div class="box">
			<div class="box_header">
				<h1><?php $this->getLanguage('core_service_title');?></h1>
			</div>
			<div class="box_body">
				<p>
					<?php $this->getLanguage('core_service_intro');?>
				</p>
			</div>
		</div>
	</div>
<?php }?>