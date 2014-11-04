<?php

namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

/**
 * Class admin_templates
 *
 * @package mail_remix
 */
class admin_templates {
	public function __construct() {
	}

	public function maybe_save_opts() {
	}

	public function do_print() {
		?>
		<div class="wrap">
		<h2>Mail Remix | Templating</h2>

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
							<?php
							foreach(plugin()->utils->get_templates() as $name => $file)
								echo '<option value="'.$file.'">'.$name.'</option>';
							?>
						</select>
						<p class="description">Customize your selected theme below.</p>
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
		<button class="button button-primary">Save All Changes</button>
	<?php
	}
}