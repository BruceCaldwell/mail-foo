<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class admin_main {
	public function __construct() {
	}

	public function maybe_save_opts() {
		if(!isset($_POST[__NAMESPACE__.'_admin_main_nonce']) || !wp_verify_nonce($_POST[__NAMESPACE__.'_admin_main_nonce'], __NAMESPACE__.'_save_config'))
			return;

		$_p = plugin()->utils->clean_request_vars($_POST);

		$opts = plugin()->opts();

		$checkboxes = array('enabled', 'parse_shortcodes', 'parse_markdown', 'exec_php', 'smtp', 'logging');

		foreach($checkboxes as $name) {
			if(isset($_p[$name]) && $_p[$name])
				$opts[$name] = TRUE;
			else $opts[$name] = FALSE;
		}

		update_site_option(__NAMESPACE__.'_options', $opts);
	}

	public function do_print() {
		?>
		<div class="wrap">
			<h2>Mail Remix | Config</h2>

			<form method="post" action="">
				<h3>Basic Config</h3>
				<table class="form-table">
					<tbody>

					<tr>
						<th scope="row">
							Enable Email Parsing?
						</th>
						<td>
							<label for="mail_remix_enable">
								<input type="checkbox" <?php if(plugin()->opts()['enabled']) echo 'checked="checked"'; ?> name="enabled" id="mail_remix_enable" />
								Enable Parsing to HTML, Templating, Replacement Codes, and more
							</label>
							<p class="description">Check box to enable both HTML templating and the additional processing items below.</p>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="mail_remix_parsing">Additional Processing</label>
						</th>
						<td>
							<label for="mail_remix_parse_shortcodes" style="display: block;">
								<input type="checkbox" <?php if(plugin()->opts()['parse_shortcodes']) echo 'checked="checked"'; ?> name="parse_shortcodes" id="mail_remix_parse_shortcodes" />
								Parse Shortcodes
							</label>

							<label for="mail_remix_parse_markdown" style="display: block;">
								<input type="checkbox" <?php if(plugin()->opts()['parse_markdown']) echo 'checked="checked"'; ?> name="parse_markdown" id="mail_remix_parse_markdown" />
								Parse Markdown
							</label>

							<label for="mail_remix_exec_php" style="display: block;">
								<input type="checkbox" <?php if(plugin()->opts()['exec_php']) echo 'checked="checked"'; ?> name="exec_php" id="mail_remix_exec_php" />
								Execute PHP
							</label>
							<p class="description">Check these additional processing options to perform custom operations within your emails.</p>
						</td>
					</tr>
					</tbody>
				</table>

				<h3>SMTP Emails</h3>
				<table class="form-table">
					<tbody>

					<tr>
						<th scope="row">
							Use SMTP Delivery
						</th>
						<td>
							<label for="mail_remix_smtp" style="display: block;">
								<input type="checkbox" <?php if(plugin()->opts()['smtp']) echo 'checked="checked"'; ?> name="smtp" id="mail_remix_smtp" />
								Send Mail via SMTP Server
							</label>
							<p class="description">Check this box to send emails via <a href="http://en.wikipedia.org/wiki/Simple_Mail_Transfer_Protocol">Simple Mail Transfer Protocol</a> server integration.</p>
						</td>
					</tr>

					</tbody>
				</table>

				<h3>Additional</h3>
				<table class="form-table">
					<tbody>

					<tr>
						<th scope="row">
							Enable Logging?
						</th>
						<td>
							<label for="mail_remix_enable_logging">
								<input name="logging" <?php if(plugin()->opts()['logging']) echo 'checked="checked"'; ?> id="mail_remix_enable_logging" type="checkbox" />
								Yes, log all outbound emails via <code>wp_mail()</code>.
							</label>
							<p class="description">Log files are stored in <code><?php echo plugin()->log_dir; ?></code>.</p>
						</td>
					</tr>

					</tbody>
				</table>

				<?php wp_nonce_field(__NAMESPACE__.'_save_config', __NAMESPACE__.'_admin_main_nonce'); ?>

				<button type="submit" class="button button-primary">Save All Changes</button>
			</form>
		</div>
	<?php
	}
}