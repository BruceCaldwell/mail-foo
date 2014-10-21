<?php
/**
 * Plugin Name: Mail Remix
 * Plugin URI: http://mail-remix.com/
 * Author: WebSharks, Inc.
 * Author URI: http://websharks-inc.com/
 * Description: Bring your WordPress emails to the next level with HTML Templates. Replacement Codes, Shortcodes, Markdown, Inline PHP, and more!
 * Version: 000000-dev
 *
 * Copyright: © 2014 WebSharks, Inc.
 * License: GNU General Public License Version 2
 * Contributors: WebSharks
 */

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

if(require(dirname(__FILE__).'/wp-php53.php'))
	require_once dirname(__FILE__).'/remix.inc.php';
else wp_php53_notice('Mail Remix');