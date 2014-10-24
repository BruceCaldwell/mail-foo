<?php

namespace mail_remix;

class admin_templates {
	public function __construct() {
	}

	public function maybe_save_opts() {
	}

	public function do_print() {
		?>
		<div class="wrap">
			<h2>Mail Remix | Templates</h2>

			<div class="remix-templates-editor">
				<h3>Config</h3>
				<table class="form-table">
					<tbody>

					<tr>
						<th scope="row">
							<label for="remix-templates-dropdown">Base Template</label>
						</th>
						<td>
							<select id="remix-templates-dropdown">
								<option>This Template</option>
								<option>That Template</option>
							</select>
							<p class="description">Check these additional processing options to perform custom operations within your custom emails.</p>
						</td>
					</tr>

					</tbody>
				</table>

				<h3>Editor</h3>
				<div class="remix-editor-colors">
					<table class="form-table">
						<tbody>

						<tr>
							<th scope="row">
								<label for="primary-color">Primary Color</label>
							</th>
							<td>
								<input type="color" value="#442244" id="primary-color" />
							</td>
						</tr>

						</tbody>
					</table>
				</div>
				<div class="remix-templates-preview"></div>
			</div>
	<?php
	}
}