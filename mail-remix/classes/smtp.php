<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

/**
 * Class smtp
 *
 * @package mail_remix
 */
class smtp {

	private $plugin;

	public function __construct() {
		$this->plugin = plugin();

		$opts = $this->plugin->opts();

		if($opts['smtp']) $this->force_smtp();
	}

	// TODO Authentication types
	private function force_smtp() {
		add_action('phpmailer_init', function ($mailer) {
			$opts = $this->plugin->opts();

			$mailer->IsSMTP();

			if($opts['smtp_auth_mode'] !== 'plaintext' && !empty($opts['smtp_auth_mode']))
				$mailer->SMTPSecure = $opts['smtp_auth_mode'];

			$mailer->Host = $opts['smtp_host'];
			$mailer->Port = $opts['smtp_port'];

			if($opts['smtp_auth']) {
				$mailer->SMTPAuth = TRUE;
				$mailer->Username = $opts['smtp_user'];
				$mailer->Password = $opts['smtp_pass'];
			}
		}, 1, 1);
	}
}