<?php
/**
 * Plugin Name: Mail Remix
 * Plugin URI: http://mail-remix.com/
 * Author: Bruce Caldwell
 * Author URI: http://brucecaldwell.github.io/
 * Description: Bring your WordPress emails to the next level with HTML Templates. Replacement Codes, Shortcodes, Markdown, Inline PHP, and more!
 * Version: 000000-dev
 */

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

if(require(dirname(__FILE__).'/wp-php53.php'))
	require_once dirname(__FILE__).'/remix.inc.php';
else wp_php53_notice('Mail Remix');