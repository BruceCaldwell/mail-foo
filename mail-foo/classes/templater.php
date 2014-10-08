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
		// We hook in at the last available hook so we can catch if other plugins already switch to the HTML content type
		add_filter('wp_mail', array($this, 'filter'), 1, PHP_INT_MAX);
	}

	public function filter($args) {
		$new_args = array(
			'to'          => $args['to'],
			'subject'     => $args['subject'],
			'attachments' => $args['attachments']
		);

		$do_html        = TRUE;
		$headers_parsed = array();

		if(is_array($args['headers'])) $args['headers'] = implode("\r\n", $args['headers']);

		if(is_string($args['headers']) && !empty($args['headers'])) {
			$headers = http_parse_headers($args['headers']);

			foreach($headers as $i => $h)
				if(stripos($i, 'Content-Type') && stripos($h, 'text/html'))
					$do_html = FALSE;

			if($do_html) {
				foreach($headers as $i => $h) {
					if(is_array($h))
						foreach($h as $he)
							$headers_parsed[] = $i.': '.trim($he);
					else
						$headers_parsed[] = $i.': '.trim($h);
				}

				$headers_parsed[] = 'Content-Type: text/html; charset=UTF-8';

				$new_args['headers'] = $headers_parsed;
			}
			else $new_args['headers'] = $args['headers'];
		}

		if($do_html) {
			// TODO
		}
		else $new_args['message'] = $args['message'];

		return $new_args;
	}
}