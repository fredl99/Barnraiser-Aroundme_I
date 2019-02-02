<div class="barnraiser_connection_gallery">
    <div class="block">
        <div class="block_body">
            <?php
            if(isset($barnraiser_connection_inbound_connections)) {
            foreach ($barnraiser_connection_inbound_connections as $key => $i):
            ?>
            <div class="gallery_item">
                 <?php
                 if (!empty($i['attributes']['media/image/aspect11'])) {
                 ?>
                     <a href="<?php echo $i['connection_openid'];?>" class="avatar"><img src="<?php echo $i['attributes']['media/image/aspect11'];?>" width="40" height="40" alt="" border="" /></a><br />
                 <?php
                 }
                 elseif (isset($i['connection_openid']) && isset($i['attributes']['namePerson/friendly'])) {
                 ?>
                     <div class="no_avatar" title="<?php echo $i['attributes']['namePerson/friendly'];?> - <?php echo $i['connection_openid']; ?>"></div>
                 <?php
                 }
                 else {
                 ?>
                     <div class="avatar_placeholder"></div>
                 <?php }?>
             </div>
             <?php
             endforeach;
             }
             else {
             ?>
             <p>
                 AM_BLOCK_LANGUAGE_NO_ITEMS
             </p>  
            <?php }?>
			<div style="clear:both;"></div>
        </div>
    </div>
</div>