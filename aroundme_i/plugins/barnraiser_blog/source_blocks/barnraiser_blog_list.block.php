<div class="barnraiser_blog_list">
	<div class="block">
		<div class="block_body">
			<?php
			if (isset($barnraiser_blog_list)){
			?>
			<ul>
				<?php
				foreach ($barnraiser_blog_list as $key => $i):
				$link_css = "";
	
				if (isset($_REQUEST['blog_id']) && $_REQUEST['blog_id'] == $i['blog_id']) {
					$link_css = " class=\"highlight\"";
				}
				?>
				<li>
					<div class="li_avatar">
						<?php
						if (!empty($i['attributes']['media/image/aspect11'])) {
						?>
							<a href="<?php echo $i['connection_openid'];?>" class="avatar"><img src="<?php echo $i['attributes']['media/image/aspect11'];?>" style="width:40px; height:40px;" width="40" height="40" alt="" border="" /></a><br />
						<?php
						}
						else {
						?>
							<a href="<?php echo $i['connection_openid'];?>" class="no_avatar"><div style="width:40px; height:40px;" title="<?php echo $i['attributes']['namePerson/friendly'];?>"></div></a>
						<?php }?>
					</div>
					<div class="li_content">
						<a href="index.php?wp=<?php echo $i['webpage'];?>&amp;blog_id=<?php echo $i['blog_id'];?>"<?php echo $link_css;?> class="datetime"><?php echo strftime("%d %b %G %H:%M", $i['blog_create_datetime']);?></a><br />
						<span class="title"><?php echo $i['blog_title'];?></span>
					</div>
				</li>
				<?php
				endforeach;
				?>
			</ul>
			<?php
			}
			else {
			?>
			<p>
				AM_BLOCK_LANGUAGE_NO_ITEMS
			</p>
			<?php }?>
		</div>
		
		<div class="block_footer">
			<?php
			if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
			?>
				<a href="index.php?p=barnraiser_blog&amp;t=edit_blog&amp;wp=<?php echo $barnraiser_blog_list_wp;?>" class="add">AM_BLOCK_LANGUAGE_ADD</a>
			<?php
			}
			else {
			?>
			<span class="disabled_link" onclick="javascript:showInterfaceSystemMessage(event, 'no_subject_add_title', 'no_subject_add_message');">AM_BLOCK_LANGUAGE_ADD</span>
			<span style="display:none;">
				<span id="no_subject_add_title">AM_BLOCK_LANGUAGE_PERMISSION_PROBLEM</span>
				<span id="no_subject_add_message">
					AM_BLOCK_LANGUAGE_PERMISSION_PROBLEM_INTRO
					<?php
					if (isset($_SESSION['connection_id'])) {
						$connection_txt = 'AM_BLOCK_LANGUAGE_ACCOUNT_LINK_ADD';
						$connection_txt = str_replace('SYS_KEYWORD_CONNECTION_ID', $_SESSION['connection_id'], $connection_txt);
						echo $connection_txt;
					}
					else {
					?>
					AM_BLOCK_LANGUAGE_CONNECT_FIRST
					<?php }?>
				</span>
			</span>
			<?php }?>
			
			<?php
        	if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
        	?>
				<a href="index.php?p=barnraiser_blog&amp;t=maintain&amp;wp=<?php echo $barnraiser_blog_list_wp;?>" class="maintain">AM_BLOCK_LANGUAGE_MAINTAIN</a>
        	<?php }?>

			<a href="plugins/barnraiser_blog/feed/rss.php?wp=<?php echo $barnraiser_blog_list_wp;?>&amp;k=<?php echo md5($identity['identity_create_datetime']); ?>" class="rss_button">AM_BLOCK_LANGUAGE_RSS</a>
		</div>
	</div>
</div>