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

<form name="upload_file" action="index.php?t=file<?php if (isset($_REQUEST['view']) && $_REQUEST['view'] == 'list') echo '&view=list';?>" method="POST" enctype="multipart/form-data">
<input type="hidden" name="webpage_id" value="<?php if (isset($webpage['webpage_id'])) { echo $webpage['webpage_id'];}?>" />


<?php
if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
?>

<div class="box">
	<div class="box_header">
		<h1><?php $this->getLanguage('core_file_upload');?></h1>
	</div>
	
	<div class="box_body">
		<p>
			<?php $this->getLanguage('core_file_upload_intro');?>
		</p>

		<p>
			<label for="frm_file"><?php $this->getLanguage('core_file_upload_file');?></label>
			<input type="file" name="frm_file" id="frm_file" />
		</p>
	
		<p>
			<label for="frm_file_title"><?php $this->getLanguage('common_title');?></label>
			<input type="text" name="frm_file_title" id="frm_file_title" value=""/>
		</p>
		
		<p class="note">
			<i><?php $this->getLanguage('core_file_width_intro');?></i>
		</p>
		
		<p>
			<label for="frm_file_name"><?php $this->getLanguage('core_width');?></label>
			<input type="text" name="file_width" size="4" value=""/>
			&nbsp;<img src="<?php echo AM_TEMPLATE_PATH;?>img/measure.png" width="150" height="12" border="0" alt="" />
			&nbsp;<?php $this->getLanguage('core_pixels');?>
		</p>
	
		<p class="buttons">
			<input type="submit" name="submit_file_upload" value="<?php $this->getLanguage('common_upload');?>" />
		</p>
	</div>
</div>
<?php }?>

<?php
if (isset($file)) {
?>
<div class="box" id="id_selected_file">
	<div class="box_header">
		<h1><?php $this->getLanguage('core_file_selected_file');?></h1>
	</div>
	<div class="box_body">
		<table>
			<tr>
				<td align="left" valign="top">
					<?php
					if (isset($file['thumb_90'])) {
					?>
					<table width="100%" cellspacing="4" cellpadding="2" border="0">
						<tr>
							<td align="left" valign="top" colspan="2">
								<div style="width: 360px; height: 300px; overflow: auto;">
								<img id="id_file_1" src="core/get_file.php?file=<?php echo $file['file_name']; ?>" class="picture" style="cursor: pointer;" title="<?php $this->getLanguage('core_file_view_hint');?>" onclick="viewTag('id_file_1', 1);"/><br />
								</div>
							</td>
						</tr>
						<tr>
							<td align="right" valign="top">
								<img id="id_file_2" src="core/get_file.php?file=<?php echo $file['thumb_90']; ?>" class="picture" style="cursor: pointer;" title="<?php $this->getLanguage('core_file_view_hint');?>" onclick="viewTag('id_file_2', 1);"/>
							</td>
							<td align="left" valign="top">
								<img id="id_file_3" src="core/get_file.php?file=<?php echo $file['thumb_35']; ?>" class="picture" style="cursor: pointer;" title="<?php $this->getLanguage('core_file_view_hint');?>" onclick="viewTag('id_file_3', 1);"/>
							</td>
						</tr>
					</table>
					<?php
					}
					else {
					?>
						<img id="id_file_1" style="border: 1px solid black; cursor: pointer;" src="<?php echo AM_TEMPLATE_PATH; ?><?php echo $core_config['file']['type'][$file['file_type']]['image'][1];?>" />
					<?php }?>
				</td>
				<td valign="top" align="left">
					<b><?php $this->getLanguage('common_title'); ?></b>: <?php echo $file['file_title'];?><br />
					<b><?php $this->getLanguage('core_file_uploaded');?></b>: <?php echo $file['file_create_datetime'];?><br />
					<b><?php $this->getLanguage('common_size');?></b>: <?php echo $file['file_size'];?> kb<br />
					<b><?php $this->getLanguage('core_type'); ?></b>: <?php echo $file['file_type'];?><br />
					<b><?php $this->getLanguage('core_file_tag'); ?></b>: <input type="text" id="file_tag" onclick="javascript:this.focus();this.select();" readonly="true" value="<img src='core/get_file.php?file=<?php echo $file['file_name']; ?>' alt='<?php echo $file['file_title']; ?>' />"/><br />
					<b><?php $this->getLanguage('core_file_view'); ?></b>: <a href="core/get_file.php?file=<?php echo $file['file_name'];?>"><?php echo $file['file_title']; ?></a><br />

					<input type="hidden" name="file_to_delete" value="<?php echo $file['file_name'];?>"/>
					<input type="submit" name="delete_file" value="<?php $this->getLanguage('common_delete');?>" />
				</td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript">
	function viewTag(id, t) {
		if (t == 1) {
			path = document.getElementById(id).src;
			document.getElementById('file_tag').value = "<img src=\"" + path + "\" alt="<?php echo $file['file_title']; ?>" />";
		}
		else {
			document.getElementById('file_tag').value = "<a href=\"core/get_file.php?file=<?php echo $file['file_name']; ?>\"><?php echo $file['file_title']; ?></a>";
		}
	}
	<?php
	if (isset($file['thumb_90'])) {
	?>
		viewTag('id_file_1', '1');
	<?php
	}
	else {
	?>
		viewTag('id_file_1', '0');
	<?php }?>
</script>
<?php } ?>

<div class="box">
	<div class="box_header">
		<h1><?php $this->getLanguage('core_files');?></h1>
		<div style="text-align: right;">
   			<?php
			if (isset($_REQUEST['view']) && $_REQUEST['view'] == 'list') {
			?>
				<?php $this->getLanguage('common_view');?> <b><a href="index.php?t=file#files"><?php $this->getLanguage('core_icon');?></a> / <?php $this->getLanguage('core_list');?></b>
			<?php
			}
			else {
			?>
				<?php $this->getLanguage('common_view');?> <b><?php $this->getLanguage('core_icon');?> / <a href="index.php?t=file&amp;view=list#files"><?php $this->getLanguage('core_list');?></a></b>
			<?php }?>
		</div>
	</div>
	<div class="box_body">
		<?php
		if (isset($files)) {
		?>
			<?php
			if (isset($_REQUEST['view']) && $_REQUEST['view'] == 'list') {
			?>
				<table width="100%" border="0">
					<tr>
						<th></th>
						<th align="left"><?php $this->getLanguage('common_name');?></th>
						<th align="left"><?php $this->getLanguage('common_size');?></th>
						<th align="left"><?php $this->getLanguage('core_type');?></th>
						<th align="left"><?php $this->getLanguage('core_upload_date');?></th>
					</tr>
				<?php
				foreach($files as $i):
				?>
					<tr>
					<?php
					if (isset($i['thumb_35'])) {
					?>
						<td><a href="index.php?t=file&amp;file_id=<?php echo $i['file_id']; ?>&amp;view=list"><img src="core/get_file.php?file=<?php echo $i['thumb_35']; ?>" style="border: none;"/></a></td>
						<td><a href="index.php?t=file&amp;file_id=<?php echo $i['file_id']; ?>&amp;view=list"><?php echo wordwrap($i['file_title'], 20,"<br />\n", 1); ?></a></td>
						<td><?php echo $i['file_type'];?></td>
						<td><?php echo $i['file_size'];?></td>
						<td><?php echo $i['file_create_datetime'];?></td>
					<?php
					}
					else {
					?>
						<td><img src="<?php echo AM_TEMPLATE_PATH; ?><?php echo $core_config['file']['type'][$i['file_type']]['image'][2];?>" style="border: none;"/></td>
						<td><a href="index.php?t=file&amp;file_id=<?php echo $i['file_id']; ?>"><?php echo wordwrap($i['file_title'], 20,"<br />\n", 1); ?></a></td>
						<td><?php echo $i['file_type'];?></td>
						<td><?php echo $i['file_size'];?></td>
						<td><?php echo $i['file_create_datetime'];?></td>
					<?php } ?>
					</tr>
				<?php
				endforeach;
				?>
				</table>
			<?php
			}
			else {
			?>
				<?php
				foreach($files as $i):
				?>
					<div style="float: left; padding-right: 10px; padding-bottom: 10px;">
						<a href="index.php?t=file&amp;file_id=<?php echo $i['file_id']; ?>">
						<?php
						if (isset($i['thumb_90'])) {
						?>
							<img style="border: 1px solid black; cursor: pointer;" src="core/get_file.php?file=<?php echo $i['thumb_90']; ?>" title="<?php echo $i['file_title']; ?>. <?php $this->getLanguage('core_file_uploaded');?> <?php echo $i['file_create_datetime']; ?>. <?php $this->getLanguage('core_click_view');?>" />
						<?php
						}
						else {
						?>
							<img style="border: 1px solid black; cursor: pointer;" src="<?php echo AM_TEMPLATE_PATH; ?><?php echo $core_config['file']['type'][$i['file_type']]['image'][1];?>" title="<?php echo $i['file_title']; ?>. <?php $this->getLanguage('core_file_uploaded');?> <?php echo $i['file_create_datetime']; ?>. <?php $this->getLanguage('core_click_view');?>" />
						<?php }?>
						</a>
						<br />
						<a href="index.php?t=file&amp;file_id=<?php echo $i['file_id']; ?>"><?php echo wordwrap($i['file_title'], 11,"<br />\n", 1); ?></a>
					</div>
				<?php
				endforeach;
				?>
			<?php }?>
		<?php }?>
		<div style="clear: both;"></div>
	</div>
</div>
</form>