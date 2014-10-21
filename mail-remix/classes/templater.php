<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class templater {

	private $plugin, $boundary;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->plugin = plugin();
	}

	/**
	 * Adds `$this->filter` to the `wp_mail` filter hook
	 */
	public function add_actions() {
		// We hook in at the last available hook so we can catch if other plugins already switch to the HTML content type
		//add_filter('wp_mail', array($this, 'filter'), 1, PHP_INT_MAX);
		add_filter('phpmailer_init', array($this, 'mailer'), 1, PHP_INT_MAX);
	}

	public function mailer($mailer) {
		$plaintext_content = $mailer->Body;

		$mailer->AltBody = $this->parse($plaintext_content, TRUE);
		$mailer->MsgHTML($this->parse($plaintext_content));
	}

	private function parse($text, $plaintext = FALSE, $shortcodes = NULL, $markdown = NULL, $php = NULL) {
		if($shortcodes === NULL) $shortcodes = plugin()->opts()['parse_shortcodes'];
		if($markdown === NULL) $markdown = plugin()->opts()['parse_markdown'];
		if($php === NULL) $php = plugin()->opts()['exec_php'];

		$vars = array(
			'site_url'         => site_url(),
			'site_name'        => get_bloginfo('name'),
			'site_description' => get_bloginfo('description'),
			'admin_email'      => get_bloginfo('admin_email'),
		);

		// Replacement Codes
		foreach($vars as $_replace => $_value) $text = str_ireplace('%%'.$_replace.'%%', $_value, $text);

		// Shortcodes
		if($shortcodes) $text = do_shortcode($text);
		// PHP Execution
		if($php) $text = $this->exec_php($text);

		// wpautop vs markdown
		if(!$plaintext && !$markdown) $text = wpautop($text);
		else $text = $this->do_markdown($text);

		if(!$plaintext) $text = $this->templatize($text);

		// Do replacement codes a second time for template
		foreach($vars as $_replace => $_value) $text = str_ireplace('%%'.$_replace.'%%', $_value, $text);

		if($plaintext)
			return apply_filters(__NAMESPACE__.'_plaintext_message_parsed', $text);
		return apply_filters(__NAMESPACE__.'_html_message_parsed', $text);
	}

	private function templatize($text) {
		$template = file_get_contents(plugin()->tmlt_dir.'/'.plugin()->opts()['template']);

		return apply_filters(__NAMESPACE__.'_after_templated', str_replace('%%content%%', $text, $template));
	}

	private function do_markdown($str) {
		if(!class_exists('\\Michelf\\Markdown')) require($this->plugin->dir.'/md/Markdown.inc.php');

		$md = apply_filters(__NAMESPACE__.'_markdown_function', array('\\Michelf\\Markdown', 'defaultTransform'));
		return call_user_func($md, $str);
	}

	private function exec_php($code, $vars = array()) {
		if(!function_exists('eval')) return $code;

		if(is_array($vars) && !empty($vars))
			extract($vars, EXTR_PREFIX_SAME, '_extract_');

		ob_start(); // Output buffer.
		$ev = eval ("?>".trim($code));

		if($ev !== FALSE) return ob_get_clean();
		return $code;
	}
}