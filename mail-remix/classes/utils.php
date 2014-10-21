<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class utils {
	public function __construct() {
	}

	public function clean_request_vars($req = FALSE) {
		if(!$req) $req = $_REQUEST;
	}
}