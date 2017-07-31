<?php
/**
 *
 * Plugin Name:     Filter Plugin
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     filter-plugin
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Filter_Plugin
 */

use S7designFilter\Autoload\Autoload;
use S7designFilter\Plugin;

add_action( 'plugins_loaded', 'setup' );

/**
 * Setup plugin
 */
function setup() {

	$plugin = init();

	$plugin->boot();
}

/**
 * Autoload classes return base Plugin class
 *
 * @return Plugin
 */
function init() {
	require_once 'inc/autoload/class-autoload.php';
	$autoloader = new Autoload( __DIR__ );
	$autoloader->load();

	$config = (object) get_config();

	return new Plugin( $config );
}

/**
 * Configuration of plugin
 *
 * Returns current configuration of plugin
 *
 * @return array
 */
function get_config() {

	$plugin_uri = plugin_dir_url( __FILE__ );

	return array(
		'version' => 0.1,
		'base_path' => __DIR__,
		'js_path'   => $plugin_uri . 'assets/js/',
		'css_path'  => $plugin_uri . 'assets/css/',
		'img_path'  => $plugin_uri . 'assets/img/',
		);
}
