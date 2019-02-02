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

<div id="col_left_50">
	<div class="box">
		<div class="box_header">
			<h1><?php $this->getLanguage('core_create_webspace');?></h1>
		</div>
	
		<div class="box_body">
			<label for="id_title"><?php $this->getLanguage('core_label_title');?></label>
			<input type="text" id="id_title" name="webspace_title" value=""/>
			
			<input type="hidden" id="theme_name" name="theme_name" value="" />
			<input type="hidden" id="theme_css" name="theme_css" value="" />
	
			<p>
				<?php $this->getLanguage('core_webspace_profile_page');?>
			</p>
	
			<?php
			if (isset($themes)) {
			?>
			<script type="text/javascript">
	
				function viewThumbs(theme) {
					var v = document.getElementById('thumbs').getElementsByTagName('div');
					for(i=0;i<v.length;i++) {
						v[i].style.display = "none";
					}
					document.getElementById('output_thumb').innerHTML = "";
					document.getElementById(theme+'_thumb').style.display = "block";
					document.getElementById('id_layout').value = theme;
				}
		
				function viewThumb(path, css, theme) {
					document.getElementById('output_thumb').innerHTML = "<img src=\""+path+"\"/>";
					document.getElementById('theme_name').value = theme;
					document.getElementById('theme_css').value = css;
				}
	
			</script>
	
			<div id="theme_names">
			
				<?php
				foreach($themes as $key => $v) {
				?>
					<h3><?php echo $lang['arr_theme'][$key]['name'];?></h3>
					<p>
						<?php echo $lang['arr_theme'][$key]['description'];?>
					</p>
		
					<ul>
						<?php foreach($v['thumb'] as $t) { ?>
							<?php
							$tmp = explode('/', $t);
							$tmp = explode('.', $tmp[count($tmp)-1]);
							?>
		
							<li><a href="#" onclick="viewThumb('<?php echo $t; ?>', '<?php echo $tmp[0];?>', '<?php echo $key;?>');"><?php echo $lang['arr_theme'][$key]['style'][$tmp[0]];?></a></li>
						<?php } ?>
					</ul>
				<?php } ?>
			</div>
		
			
		<?php }?>
		</div>
	</div>
</div>
<div id="col_right_50">
	<div id="output_thumb"></div>
	
			<script type="text/javascript">
				viewThumb('webspace/basic/thumb/yellow.png', 'yellow', 'basic');
			</script>
			<p align="right">
			<input type="submit" name="create_webspace" value="<?php $this->getLanguage('core_choose');?>" />
		</p>
</div>
</form>