<?php
namespace mail_remix;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

/**
 * Class templater
 *
 * @package mail_remix
 */
class templater {
	/**
	 * Class constructor
	 */
	public function __construct() {
	}

	/**
	 * Adds `$this->mailer` to the `phpmailer_init` filter hook
	 */
	public function add_actions() {
		// We hook in at the last available hook so we can catch if other plugins already switch to the HTML content type
		//add_filter('wp_mail', array($this, 'filter'), 1, PHP_INT_MAX);
		add_filter('phpmailer_init', array($this, 'mailer'), 1, PHP_INT_MAX);
	}

	/**
	 * Creates HTML emails and does replacement codes via the PHPMailer Object created by WordPress
	 *
	 * @param $mailer \PHPMailer object
	 */
	public function mailer($mailer) {
		$plaintext_content = $mailer->Body;

		$mailer->MsgHTML($this->parse($plaintext_content));
		$mailer->AltBody = $this->parse($plaintext_content, TRUE); // This must be AFTER the `MsgHTML` call. Otherwise it's overridden.
	}

	/**
	 * Parses plaintext email to spec
	 *
	 * @param string    $text
	 * @param bool      $plaintext
	 * @param bool|null $shortcodes
	 * @param bool|null $markdown
	 * @param bool|null $php
	 *
	 * @return mixed|void
	 */
	private function parse($text, $plaintext = FALSE, $shortcodes = NULL, $markdown = NULL, $php = NULL) {
		if($shortcodes === NULL) $shortcodes = plugin()->opts()['parse_shortcodes'];
		if($markdown === NULL) $markdown = plugin()->opts()['parse_markdown'];
		if($php === NULL) $php = plugin()->opts()['exec_php'];

		// TODO more
		$vars = array(
			'site_url'         => site_url(),
			'site_name'        => get_bloginfo('name'),
			'site_description' => get_bloginfo('description'),
			'admin_email'      => get_bloginfo('admin_email'),
			'year'             => date('Y')
		);

		// Replacement Codes
		foreach($vars as $_replace => $_value) $text = str_ireplace('%%'.$_replace.'%%', $_value, $text);

		// Shortcodes
		if($shortcodes) $text = do_shortcode($text);
		// PHP Execution
		if($php) $text = $this->exec_php($text);

		// Leaving autop to Markdown causes problems with single-line breaks. Let WordPress handle that.
		if(!$plaintext) $text = wpautop($text);
		if(!$plaintext && $markdown) $text = $this->do_markdown($text);

		if(!$plaintext) $text = $this->templatize($text); // Wraps template

		// Do replacement codes a second time for template
		foreach($vars as $_replace => $_value) $text = str_ireplace('%%'.$_replace.'%%', $_value, $text);

		$text = trim($text); // Important

		if($plaintext)
			return apply_filters(__NAMESPACE__.'_plaintext_message_parsed', $text);
		return apply_filters(__NAMESPACE__.'_html_message_parsed', $text);
	}

	/**
	 * Wraps target text with the currently selected template
	 *
	 * @param $text string
	 *
	 * @return mixed|void
	 */
	private function templatize($text) {
		$template = file_get_contents(plugin()->tmlt_dir.'/'./*plugin()->opts()['template']*/
		                              'clean/index.html');

		return apply_filters(__NAMESPACE__.'_after_templated', str_replace('%%content%%', $text, $template));
	}

	/**
	 * Markdown Parsing. The function used to accomplish the parsing can be filtered via `add_filter` => `mail_remix_markdown_function`
	 *
	 * @param $str string
	 *
	 * @return string
	 */
	private function do_markdown($str) {
		if(!class_exists('\\Michelf\\Markdown')) require(plugin()->dir.'/md/Markdown.inc.php');

		$md = apply_filters(__NAMESPACE__.'_markdown_function', array('\\Michelf\\Markdown', 'defaultTransform'));
		return (string)call_user_func($md, $str);
	}

	/**
	 * PHP Execution and Collection
	 *
	 * @param string $code
	 * @param array  $vars
	 *
	 * @return string
	 */
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