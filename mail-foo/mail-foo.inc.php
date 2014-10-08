<?php
namespace mail_foo;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class plugin {

	public $file, $dir;

	public function __construct() {
		$this->file = str_replace('.inc.php', '.php', __FILE__);
		$this->dir  = dirname(__FILE__);
		$this->url  = plugins_url('', $this->file);

		add_action('plugins_loaded', array($this, 'init'));
	}

	public function init() {
		// Get Template
		// Styles

		// wp_mail Content Wrapper
		// wp_mail Content-Type HTTP Header
	}

	public function opts() {
		$defaults = array(
			'template'         => 'default.html',

			'parse_shortcodes' => FALSE,
			'parse_markdown'   => FALSE,

			'smtp'             => FALSE,
			'smtp_host'        => '',
			'smtp_user'        => '',
			'smtp_password'    => ''
		);

		$opts = get_site_option(__NAMESPACE__.'_options', FALSE);

		if(!$opts) return $defaults;
		return (array)maybe_unserialize($opts);
	}
}

$GLOBALS[__NAMESPACE__] = new plugin;

function plugin() {
	return $GLOBALS[__NAMESPACE__];
}