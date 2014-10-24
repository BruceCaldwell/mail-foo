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
		$page = add_menu_page(__('Mail Remix', __NAMESPACE__), __('Mail Remix', __NAMESPACE__), 'manage_options', 'mail-remix', array($this, 'main'), plugins_url('', plugin()->file).'/client-s/icon.png');
		add_submenu_page('mail-remix', __('Mail Remix | Config', __NAMESPACE__), __('Config', __NAMESPACE__), 'manage_options', 'mail-remix', array($this, 'main'));

		$templates_page = add_submenu_page('mail-remix', __('Mail Remix | Templates', __NAMESPACE__), __('Templates', __NAMESPACE__), 'manage_options', 'remix-templates', array($this, 'templates'));
		$logging_page   = add_submenu_page('mail-remix', __('Mail Remix | Logging', __NAMESPACE__), __('Logging', __NAMESPACE__), 'manage_options', 'remix-logging', array($this, 'logging'));

		// Scripts
		add_action('load-'.$page, array($this, 'init_scripts'));
		add_action('load-'.$templates_page, array($this, 'init_scripts'));
		add_action('load-'.$logging_page, array($this, 'init_scripts'));
	}

	public function init_scripts() {
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
	}

	public function enqueue_scripts() {
		wp_enqueue_script(__NAMESPACE__.'_admin_js', plugins_url('', plugin()->file).'/client-s/admin.js', array('jquery'));
		wp_enqueue_style(__NAMESPACE__.'_admin_css', plugins_url('', plugin()->file).'/client-s/admin.css');

		wp_enqueue_script('spectrum', plugins_url('', plugin()->file).'/client-s/spectrum/spectrum.min.js', array('jquery'));
		wp_enqueue_style('spectrum', plugins_url('', plugin()->file).'/client-s/spectrum/spectrum.min.css');
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