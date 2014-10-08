<?php
namespace mail_foo;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class templater {

	private $plugin;

	public function __construct() {
		$this->plugin = plugin();
	}

	public function add_actions() {
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
			$headers = $this->parse_headers($args['headers']);

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
			$opts     = $this->plugin->opts();
			$template = file_get_contents($this->plugin->tmlt_dir.'/'.$opts['template']);

			$new_args['message'] = $template;
		}
		else $new_args['message'] = $args['message'];

		var_dump($new_args);
		exit();

		return $new_args;
	}

	private function parse_headers($headers) {
		if(function_exists('http_parse_headers')) return http_parse_headers($headers);

		$func = function ($raw) { // Adopted from @url{http://php.net/manual/en/function.http-parse-headers.php#112986}
			$headers = array();
			$key     = '';

			foreach(explode("\n", $raw) as $i => $h) {
				$h = explode(':', $h, 2);

				if(isset($h[1])) {
					if(!isset($headers[$h[0]]))
						$headers[$h[0]] = trim($h[1]);
					elseif(is_array($headers[$h[0]]))
						$headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
					else
						$headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));

					$key = $h[0];
				}
				else {
					if(substr($h[0], 0, 1) == "\t")
						$headers[$key] .= "\r\n\t".trim($h[0]);
					elseif(!$key)
						$headers[0] = trim($h[0]);
					trim($h[0]);
				}
			}

			return $headers;
		};

		return $func($headers);
	}
}