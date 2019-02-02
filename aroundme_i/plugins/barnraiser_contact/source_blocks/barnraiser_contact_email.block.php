<div class="barnraiser_contact_email">
    <div class="block">
    	<?php
    	if (isset($barnraiser_contact_email_address_set)) {
			if (isset($_SESSION['connection_id']) && isset($_REQUEST['contact_msg']) && $_REQUEST['contact_msg'] == 1) {
			?>
				<div class="block_body">
					<span class="interface_message">AM_BLOCK_LANGUAGE_EMAIL_SENT</span>
				</div>
			<?php
			}
			elseif (isset($_SESSION['connection_id'])) {
			?>
				<form action="plugins/barnraiser_contact/send_email.php" method="post">
				<div class="block_body">
					<p>
						AM_BLOCK_LANGUAGE_REPLY_TO_INTRO
					</p>
	
					<p>
						<label for="id_email">AM_BLOCK_LANGUAGE_REPLY_EMAIL</label>
						<input type="text" id="id_email" name="email" value="<?php if (isset($_SESSION['openid_email'])) { echo $_SESSION['openid_email'];}?>" />
					</p>
	
					<p>
						<label for="id_subject">AM_BLOCK_LANGUAGE_SUBJECT</label>
						<input type="text" id="id_subject" name="subject" value="" />
					</p>
	
					<p>
						<label for="id_message">AM_BLOCK_LANGUAGE_MESSAGE</label>
						<textarea name="message" id="id_message"></textarea>
					</p>
				</div>
	
				<div class="block_footer">
					<input type="submit" name="send_email" value="AM_BLOCK_LANGUAGE_SEND" />
				</div>
				</form>
			<?php
			}
			else {
			?>
			<div class="block_body">
				<p>
					AM_BLOCK_LANGUAGE_CONNECT_FIRST
				</p>
			</div>
			<?php }?>
		<?php
		}
		else {
		?>
		<div class="block_body">
			<p>
				AM_BLOCK_LANGUAGE_SETUP
			</p>
		</div>
		<?php }?>
    </div>
</div>