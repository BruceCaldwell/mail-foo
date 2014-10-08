<?php
namespace mail_foo;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class smtp {

	private $plugin;

	public function __construct() {
		$this->plugin = plugin();
	}
}