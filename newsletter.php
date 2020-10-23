<?php
/**
 * Plugin Name: Dornaweb Newsletter Lite
 * Description: Simple Newsletter Plugin to store email addresses from users
 * Plugin URI:  https://wwww.dornaweb.com
 * Version:     1.0
 * Author:      Dornaweb
 * Author URI:  https://wwww.dornaweb.com
 * License:     GPL
 * Text Domain: dwnlite
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

if (! defined('DW_NLITE_FILE')) {
	define('DW_NLITE_FILE', __FILE__);
}

/**
 * Load core packages and the autoloader.
 * The SPL Autoloader needs PHP 5.6.0+ and this plugin won't work on older versions
 */
if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
	require __DIR__ . '/includes/class-autoloader.php';
}

/**
 * Returns the main instance of PDF Gen.
 *
 * @since  1.0
 * @return DW_NLITE\App
 */
function dw_nlite() {
	return DW_NLITE\App::instance();
}

// Global for backwards compatibility.
$GLOBALS['dw_nlite'] = dw_nlite();
