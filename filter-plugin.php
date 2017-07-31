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

add_action( 'plugins_loaded', __NAMESPACE__ . '\setup' );

/**
 * Setup plugin
 */
function setup() {

}

/**
 * Configuration of plugin
 *
 * Returns current configuration of plugin
 *
 * @return array
 */
function get_config() {
	return array(
		'version' => 0.1,
		);
}
