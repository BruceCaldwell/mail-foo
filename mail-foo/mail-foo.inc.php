<?php
namespace mail_foo;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

/**
 * Class plugin
 *
 * @package mail_foo
 */
class plugin {

	/**
	 * @var string
	 */
	public $file, $dir;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->file = str_replace('.inc.php', '.php', __FILE__);
		$this->dir  = dirname(__FILE__);
		$this->url  = plugins_url('', $this->file);

		add_action('plugins_loaded', array($this, 'build'));
	}

	/**
	 * Build plugin basics
	 */
	public function build() {
		load_plugin_textdomain('mail-foo');

		if($this->opts()['enabled']) $this->init();
		if(is_admin()) $this->init_admin();
	}

	/**
	 * Initialize template and SMTP functionality
	 */
	public function init() {
		$templater = new templater();
		$templater->add_actions($this->opts()['template'], $this->opts()['parse_shortcodes'], $this->opts()['parse_markdown']);
	}

	/**
	 * Initialize WordPress Dashboard pages
	 */
	public function init_admin() {
	}

	/**
	 * Class autoloader
	 *
	 * @param $class
	 */
	public function autoload($class) {
		if(strpos($class, __NAMESPACE__.'\\') !== FALSE && ($filename = str_replace(array(__NAMESPACE__.'\\', '_'), array('', '-'), $class)))
			require_once($this->dir.'/class/'.$filename.'.php');
	}

	/**
	 * Plugin options
	 *
	 * @return array
	 */
	public function opts() {
		$defaults = array(
			'enabled'          => FALSE,

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