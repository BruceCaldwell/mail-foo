<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

/**
 * Class utils
 *
 * @package mail_remix
 */
class utils {
	public function __construct() {
	}

	public function clean_request_vars($req = array()) {
		if(empty($req)) $req = $_REQUEST;
		return array_map(array($this, 'clean_string'), $req);
	}

	private function clean_string($str) {
		if(!$str) return $str;
		return esc_html(stripslashes($str));
	}
}