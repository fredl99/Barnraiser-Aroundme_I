<div class="barnraiser_connection_sites">
    <div class="block">
        <div class="block_body">
            <?php
			if (isset($barnraiser_connection_outbound_connections)) {
			?>
			<ul>
                <?php
                foreach ($barnraiser_connection_outbound_connections as $key => $i):
                ?>
                <li><a href="<?php echo $i['realm'];?>"><?php echo $i['title'];?></a></li>
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
    
        <?php
        if (isset($_SESSION['connection_permission']) && $_SESSION['connection_permission'] == 64) {
        ?>
        <div class="block_footer">
            <a href="index.php?t=network">AM_BLOCK_LANGUAGE_MAINTAIN</a>
        </div>
        <?php }?>
    </div>
</div>