<?php
namespace mail_foo;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class admin {

	private $plugin;

	public function __construct() {
		$this->plugin = plugin();
	}

	public function add_pages() {
		add_menu_page(__('Mail Foo', __NAMESPACE__), __('Email Config', __NAMESPACE__), 'manage_options', 'mail-foo', array($this, 'page'));
	}

	public function page() {
		echo 'hello';
	}
}