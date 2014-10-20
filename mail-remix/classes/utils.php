<?php

namespace mail_remix;

class utils {
	public function __construct() {
	}

	public function clean_request_vars($req = FALSE) {
		if(!$req) $req = $_REQUEST;


	}
}