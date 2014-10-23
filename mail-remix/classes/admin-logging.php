<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class admin_logging {
	public function __construct() {
	}

	public function maybe_save_opts() {
		if(!isset($_POST[__NAMESPACE__.'_admin_logging_nonce']) || !wp_verify_nonce($_POST[__NAMESPACE__.'_admin_logging_nonce'], __NAMESPACE__.'_save_logging'))
			return;

		$_p = plugin()->utils->clean_request_vars($_POST);

		$opts = plugin()->opts();

		if(isset($_p['logging']) && $_p['logging'])
			$opts['logging'] = TRUE;
		else $opts['logging'] = FALSE;

		update_site_option(__NAMESPACE__.'_options', $opts);
	}

	public function do_print() {
		?>
		<div class="wrap">
			<h2>Mail Remix | Logging</h2>

			<h3>Config</h3>
			<form method="post" action="">
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

				<?php wp_nonce_field(__NAMESPACE__.'_save_logging', __NAMESPACE__.'_admin_logging_nonce'); ?>
				<button type="submit" class="button button-primary">Save Option</button>
			</form>

			<h3>Logs</h3>
			<div class="mail-remix-logs">
				<span class="special-label">Select Log File</span>
				<select style="width: 100%;">
					<option>This Log File</option>
					<option>That Log File</option>
				</select>

				<span class="special-label">Details</span>
				<textarea onchange="return false;" style="width: 100%; max-width: 100%; min-width: 500px; min-height: 500px;"></textarea>
			</div>
		</div>
	<?php
	}
}