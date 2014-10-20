<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class admin {

	private $plugin;

	public function __construct() {
		$this->plugin = plugin();
	}

	public function add_pages() {
		add_menu_page(__('Mail Remix', __NAMESPACE__), __('Email Config', __NAMESPACE__), 'manage_options', 'mail-remix', array($this, 'main'));
		add_submenu_page('mail-remix', __('Mail Remix | Templates', __NAMESPACE__), __('Templates', __NAMESPACE__), 'manage_options', 'remix-templates', array($this, 'templates'));
		add_submenu_page('mail-remix', __('Mail Remix | Logging', __NAMESPACE__), __('Logging', __NAMESPACE__), 'manage_options', 'remix-logging', array($this, 'logging'));
	}

	public function main() {
		$page = new admin_main;
		$page->maybe_save_opts();
		$page->do_print();
	}

	public function templates() {
		$page = new admin_templates;
		$page->maybe_save_opts();
		$page->do_print();
	}

	public function logging() {
		$page = new admin_logging;
		$page->maybe_save_opts();
		$page->do_print();
	}
}