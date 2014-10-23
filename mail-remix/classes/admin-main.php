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

		$checkboxes = array('enabled', 'parse_shortcodes', 'parse_markdown', 'exec_php');

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
							<p class="description">Check these additional processing options to perform custom operations within your custom emails.</p>
						</td>
					</tr>
					</tbody>
				</table>

				<h3>Templates</h3>
				<table class="form-table">
					<tbody>

					<tr>
						<th scope="row">
							Current Template
						</th>
						<td>
							<select>
								<option>This Template</option>
								<option>That Template</option>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row">
							Template Colors
						</th>
						<td>
							<input type="text" />
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