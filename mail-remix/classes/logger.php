<?php
namespace mail_remix;

class logger {
	public function __construct() {
		if(!$this->check_logs_dir()) return;

		add_filter('wp_mail', array($this, 'filter'));
	}

	public function filter($args) {
		$log = array();

		$log[] = '=== '.date('Y-m-d H:i:s').' ===';
		$log[] = '```';
		$log[] = print_r($args, TRUE);
		$log[] = '```';

		@file_put_contents(plugin()->log_dir.'/'.date('Y-m').'.log', implode("\r\n", $log)."\r\n\r\n", FILE_APPEND);

		return $args;
	}

	private function check_logs_dir() {
		$dir = plugin()->log_dir;

		if(!file_exists($dir) || !is_dir($dir)) {
			@mkdir($dir, 0775);
			@file_put_contents($dir.'/.htaccess', 'deny from all');
		}

		if(!file_exists($dir) || !is_writable($dir) || !is_dir($dir)) {
			add_action('all_admin_notices', array($this, 'print_write_error'));
			return FALSE;
		}

		return TRUE;
	}

	public function print_write_error() {
		if(current_user_can('activate_plugins'))
			echo '<div class="error"><p>'
			     .'<strong>Mail Remix: Error</strong> - We\'re unable to access the <code>'.plugin()->log_dir.'</code> directory in write mode. Logging will be unavailable.'
			     .'</p></div>';
	}
}