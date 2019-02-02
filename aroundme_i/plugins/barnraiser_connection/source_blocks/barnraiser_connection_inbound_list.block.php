<div class="barnraiser_connection_inbound_list">
    <div class="block">
        <div class="block_body">
            <?php
            if(isset($barnraiser_connection_inbound_connections)) {
            ?>
            <ul>
            <?php
            foreach ($barnraiser_connection_inbound_connections as $key => $i):
            ?>
            <li><a href="<?php echo $i['connection_openid'];?>"><?php echo $i['attributes']['namePerson/friendly'];?></a></li>
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
    </div>
</div>