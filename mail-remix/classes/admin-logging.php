<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class admin_logging {
	public function __construct() {
	}

	public function maybe_save_opts() {
	}

	public function do_print() {
		?>
		<div class="wrap">
			<h2>Mail Remix | Logging</h2>

			<h3>Config</h3>
			<table class="form-table">
				<tbody>

				<tr>
					<th scope="row">
						Enable Logging?
					</th>
					<td>
						<label for="mail_remix_enable_logging">
							<input id="mail_remix_enable_logging" type="checkbox" /> Yes, log all outbound emails via <code>wp_mail()</code>.
						</label>
					</td>
				</tr>

				</tbody>
			</table>
		</div>
	<?php
	}
}