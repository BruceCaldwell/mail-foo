<?php
namespace mail_foo;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class templater {

	private $plugin;

	public function __construct() {
		$this->plugin = plugin();
	}

	public function add_actions($template, $shortcodes = FALSE, $markdown = FALSE) {

	}
}