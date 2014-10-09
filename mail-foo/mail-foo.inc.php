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
	public $file, $dir, $tmlt_dir;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->file     = str_replace('.inc.php', '.php', __FILE__);
		$this->dir      = dirname(__FILE__);
		$this->tmlt_dir = $this->dir.'/templates';
		$this->url      = plugins_url('', $this->file);

		spl_autoload_register(array($this, 'autoload'));

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
		$templater->add_actions();
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
			require_once($this->dir.'/classes/'.$filename.'.php');
	}

	/**
	 * Plugin options
	 *
	 * @return array
	 */
	public function opts() {
		$defaults = array(
			'enabled'          => TRUE,

			'template'         => 'default.html',

			'parse_shortcodes' => FALSE,
			'parse_markdown'   => FALSE,
			'exec_php'         => FALSE,

			'smtp'             => FALSE,
			'smtp_port'        => 25,
			'smtp_host'        => '',
			'smtp_user'        => '',
			'smtp_pass'        => ''
		);

		$opts = get_site_option(__NAMESPACE__.'_options', FALSE);

		if(!$opts) return $defaults;
		return (array)maybe_unserialize($opts);
	}
}

/** @var plugin Class Instance */
$GLOBALS[__NAMESPACE__] = new plugin;

/** @return plugin Class Instance */
function plugin() {
	return $GLOBALS[__NAMESPACE__];
}