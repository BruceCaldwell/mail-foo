<?php

namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class admin_transport {
	public function __construct() {
	}

	public function maybe_save_opts() {
		if(!isset($_POST[__NAMESPACE__.'_admin_smtp_nonce']) || !wp_verify_nonce($_POST[__NAMESPACE__.'_admin_smtp_nonce'], __NAMESPACE__.'_save_config'))
			return;

		$_p = plugin()->utils->clean_request_vars($_POST);

		$opts = plugin()->opts();

		$checkboxes = array('smtp', 'smtp_auth');

		foreach($checkboxes as $name) {
			if(isset($_p[$name]) && $_p[$name])
				$opts[$name] = TRUE;
			else $opts[$name] = FALSE;
		}

		$text_forms = array('smtp_host', 'smtp_user', 'smtp_pass', 'smtp_port');

		foreach($text_forms as $name) {
			if(isset($_p[$name]) && $_p[$name])
				$opts[$name] = sanitize_text_field($_p[$name]);
			else $opts[$name] = '';
		}

		if(isset($_p['smtp_con_mode']))
			$opts['smtp_con_mode'] = sanitize_text_field($_p['smtp_con_mode']);

		$opts['smtp_port'] = (int)$opts['smtp_port'];

		update_site_option(__NAMESPACE__.'_options', $opts);
	}

	public function do_print() {
		$opts = plugin()->opts();
		?>
		<div class="wrap">
			<h2>Mail Remix | Transport</h2>
			<form method="post" action="">
				<h3>SMTP</h3>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							Use SMTP Delivery
						</th>
						<td>
							<label for="mail_remix_smtp">
								<input type="checkbox" <?php if($opts['smtp']) echo 'checked="checked"'; ?> name="smtp" id="mail_remix_smtp" />
								Send Mail via SMTP Server
							</label>
							<p class="description">Check this box to send emails via <a href="http://en.wikipedia.org/wiki/Simple_Mail_Transfer_Protocol">Simple Mail Transfer Protocol</a> server integration.</p>
						</td>
					</tr>

					<tr>
						<th scope="row">
							SMTP Host
						</th>
						<td>
							<label for="mail_remix_smtp_host">
								<input type="text" name="smtp_host" value="<?php echo $opts['smtp_host']; ?>" placeholder="test.example.com" id="mail_remix_smtp_host" />
							</label>
						</td>
					</tr>

					<tr>
						<th scope="row">
							SMTP Port
						</th>
						<td>
							<label for="mail_remix_smtp_port">
								<input type="number" name="smtp_port" value="<?php echo (string)$opts['smtp_port']; ?>" placeholder="25" id="mail_remix_smtp_port" />
							</label>
						</td>
					</tr>

					<tr>
						<th scope="row">
							Connection Type
						</th>
						<td>
							<select name="smtp_con_mode">
								<?php $opt = $opts['smtp_con_mode']; ?>
								<option <?php if($opt === 'plaintext') echo 'selected="selected"'; ?> value="plaintext">Plain Text</option>
								<option <?php if($opt === 'ssl') echo 'selected="selected"'; ?> value="ssl">SSL</option>
								<option <?php if($opt === 'tls') echo 'selected="selected"'; ?> value="tls">TLS</option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>

				<h3>SMTP Auth</h3>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							Authenticate
						</th>
						<td>
							<label for="mail_remix_smtp_auth">
								<input type="checkbox" <?php if($opts['smtp_auth']) echo 'checked="checked"'; ?> name="smtp_auth" id="mail_remix_smtp_auth" />
								Use Authentication when directing mail via SMTP
							</label>
						</td>
					</tr>

					<tr>
						<th scope="row">
							SMTP Username
						</th>
						<td>
							<label for="mail_remix_smtp_user">
								<input type="text" autocomplete="off" name="smtp_user" value="<?php echo $opts['smtp_user']; ?>" id="mail_remix_smtp_user" />
							</label>
						</td>
					</tr>

					<tr>
						<th scope="row">
							SMTP Password
						</th>
						<td>
							<label for="mail_remix_smtp_pass">
								<input type="password" autocomplete="off" name="smtp_pass" value="<?php echo $opts['smtp_pass']; ?>" id="mail_remix_smtp_pass" />
							</label>
						</td>
					</tr>
					</tbody>
				</table>

				<?php wp_nonce_field(__NAMESPACE__.'_save_config', __NAMESPACE__.'_admin_smtp_nonce'); ?>

				<button type="submit" class="button button-primary">Save All Changes</button>
			</form>
		</div>
	<?php
	}
}
