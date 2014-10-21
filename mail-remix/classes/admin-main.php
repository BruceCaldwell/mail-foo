<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class admin_main {
	public function __construct() {
	}

	public function maybe_save_opts() {
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
								<input type="checkbox" id="mail_remix_enable" /> Enable Parsing to HTML, Templating, Replacement Codes, and more
							</label>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="mail_remix_parsing">Do Special Processing?</label>
						</th>
						<td>
							<label for="mail_remix_parse_shortcodes" style="display: block;">
								<input type="checkbox" id="mail_remix_parse_shortcodes" /> Parse Shortcodes
							</label>

							<label for="mail_remix_parse_markdown" style="display: block;">
								<input type="checkbox" id="mail_remix_parse_markdown" /> Parse Markdown
							</label>

							<label for="mail_remix_exec_php" style="display: block;">
								<input type="checkbox" id="mail_remix_exec_php" /> Execute PHP
							</label>
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

				<button class="button button-primary">Save All Changes</button>
			</form>
		</div>
	<?php
	}
}