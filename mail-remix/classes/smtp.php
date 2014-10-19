<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class smtp {

	private $plugin;

	public function __construct() {
		$this->plugin = plugin();

		$opts = $this->plugin->opts();

		if($opts['smtp']) $this->force_smtp();
	}

	private function force_smtp() {
		add_action('phpmailer_init', function ($mailer) {
			$opts = $this->plugin->opts();

			$mailer->IsSMTP();
			$mailer->SMTPAuth = TRUE;
			$mailer->Host     = $opts['smtp_host'];
			$mailer->Port     = $opts['smtp_port'];
			$mailer->Username = $opts['smtp_user'];
			$mailer->Password = $opts['smtp_pass'];
		});
	}
}