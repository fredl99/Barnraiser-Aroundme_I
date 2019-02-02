<form action="plugins/barnraiser_connection/add_log.php" method="post">
<div class="barnraiser_connection_log">
    <div class="block">
        <div class="block_body">
            <?php
			if (isset($connection_log)) {
			?>
			<ul>
                <?php
                foreach($connection_log as $key => $i):
                ?>
                    <li><span class="datetime"><?php echo strftime("%d %b %G %H:%M", $i['log_create_datetime']);?>:</span> <span class="body"><?php echo $i['log_body'];?></span></li>
				
                <?php
                endforeach;
                ?>
            </ul>
            <?php }?>
			
			<?php
            if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
            ?>
			<div class="add">
	            <textarea name="log_entry" onFocus="this.value=''; return false;">AM_BLOCK_LANGUAGE_LOG_PROMPT</textarea>
	        </div>
	        <div class="block_footer">
	    	<input type="submit" name="insert_log_entry" value="AM_BLOCK_LANGUAGE_ADD" />
        </div>
		    <?php }?>
        </div>
    </div>
</div>
</form>