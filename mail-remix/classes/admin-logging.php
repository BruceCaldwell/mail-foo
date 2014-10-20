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
		</div>
	<?php
	}
}