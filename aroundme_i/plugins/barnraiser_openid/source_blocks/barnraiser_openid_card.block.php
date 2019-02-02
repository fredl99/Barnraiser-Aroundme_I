<div class="barnraiser_openid_card">
    <div class="block">	
 		<div class="block_body">
 			<p>
				<?php
				if (isset($barnraiser_openid_card_identity_attributes)) {
				foreach($barnraiser_openid_card_identity_attributes as $key => $i):

				if ($key == 'media/image/aspect11') {
					$identity_profile_avatar = $i;
					break;
				}
				?>
				<span class="profile_title"><?php $this->getLanguage('txt_' . $key);?></span>
				<span class="profile_value">
					<?php
					if (isset($this->lang['arr_identity_field'][$key][$i])) {
						echo $this->lang['arr_identity_field'][$key][$i];
					}
					else {
						echo $i;
					}
					?>
				</span>
				<br />
				<?php
				endforeach;

				if (isset($identity_profile_avatar)) {
				?>
					<span class="profile_title"><?php $this->getLanguage('txt_media/image/aspect11');?></span>
					<span class="profile_value"><img src="<?php echo $identity_profile_avatar; ?>" border="0" /></span>
					<br />
				<?php
				}
				}
				?>
			</p>
 
            <ul>
                <li><a href="plugins/barnraiser_openid/vcard.php">AM_BLOCK_LANGUAGE_VCARD</a></li>
            </ul>
        </div>

        <?php
        if (isset($_SESSION['connection_id']) && $_SESSION['connection_id'] == AM_OWNER_CONNECTION_ID) {
        ?>
        <div class="block_footer">
            <a href="index.php?p=barnraiser_openid&amp;t=maintain">AM_BLOCK_LANGUAGE_MAINTAIN</a>
        </div>
        <?php }?>
    </div>
</div>