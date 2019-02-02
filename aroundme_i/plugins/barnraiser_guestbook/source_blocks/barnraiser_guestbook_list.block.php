<script type="text/javascript">
	function clean() {
		if (document.getElementById('id_guestbook_body').value == 'AM_BLOCK_LANGUAGE_PROMPT') {
			document.getElementById('id_guestbook_body').value = '';
			return false;
		}
	}
</script>

<form action="plugins/barnraiser_guestbook/add_guestbook.php?wp=<?php echo AM_WEBPAGE_NAME;?>" method="post">
<div class="barnraiser_guestbook_list">
    <div class="block">
        <div class="block_body">
            <?php
            if (isset($barnraiser_guestbook_entries)){
            ?>
            <ul>
                <?php
                foreach ($barnraiser_guestbook_entries as $key => $i):
                ?>
               <li>
                    <div class="li_avatar">
						<?php
						if (!empty($i['attributes']['media/image/aspect11'])) {
						?>
							<a href="<?php echo $i['connection_openid'];?>" class="avatar"><img src="<?php echo $i['attributes']['media/image/aspect11'];?>" width="40" height="40" alt="" border="" /></a>
						<?php
						}
						else {
						?>
							<a href="<?php echo $i['connection_openid'];?>" class="no_avatar"><div title="<?php echo $i['attributes']['namePerson/friendly'];?>"></div></a>
						<?php }?>
					</div>
					<div class="li_content">
                        <a href="<?php echo $i['connection_openid'];?>" class="connection"><?php echo $i['attributes']['namePerson/friendly'];?></a><br />
                        <span class="datetime"><?php echo strftime("%d %b %G %H:%M", $i['guestbook_create_datetime']);?></span>
                        <span class="body"><?php echo $i['guestbook_body']?></span>
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
  			
			<?php
            if (isset($_SESSION['connection_id'])) {
            ?>
			<div class="add">
                <textarea rows="2" cols="34" name="guestbook_body" id="id_guestbook_body" onFocus="clean();">AM_BLOCK_LANGUAGE_PROMPT</textarea><br />
			</div>
			<?php }?>
        </div>

		<div class="block_footer">
			
			
			<?php
			if (!isset($_SESSION['connection_id'])) {
			?>
			<span class="disabled_link" onclick="javascript:showInterfaceSystemMessage(event, 'no_guestbook_add_title', 'no_guestbook_add_message');">add comment</span>&nbsp;
			<span style="display:none;">
				<span id="no_guestbook_add_title">AM_BLOCK_LANGUAGE_PERMISSION_PROBLEM</span>
				<span id="no_guestbook_add_message">
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
			if (isset($_SESSION['connection_id'])) {
        	?>
				<input type="submit" name="insert_guestbook" value="AM_BLOCK_LANGUAGE_ADD" onClick="clean();" />
            	<a href="index.php?p=barnraiser_guestbook&amp;t=maintain&amp;wp=<?php echo AM_WEBPAGE_NAME;?>" class="maintain">AM_BLOCK_LANGUAGE_MAINTAIN</a>
        	<?php }?>
        </div>
    </div>
</div>
</form>