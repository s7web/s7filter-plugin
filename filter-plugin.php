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

/**
 * Get page settings by ID
 *
 * @param int $id Id of page for settings.
 *
 * @return array|false
 */
function get_page_settings_by_id( $id ) {

	$settings = get_option( 's7filter-settings' );
	if ( isset( $settings[ $id ] ) ) {
		return $settings[ $id ];
	} else {
		return false;
	}
}

/**
 * Build query args array based on config
 *
 * @param array $settings Settings from database.
 *
 * @return array
 */
function parse_settings( array $settings ) {

	$args = array();
	if ( isset( $settings['settings']['filter'] ) ) {
		switch ( $settings['settings']['filter'] ) {
			case 'categories':
				if ( isset( $settings['settings']['categories'] ) ) {
					$categories           = array_map( 'intval', $settings['settings']['categories'] );
					$args['category__in'] = $categories;
				}
				$args['post_type'] = array( 'post' );
				break;
			case 'tags':
				if ( isset( $settings['settings']['tags'] ) ) {
					$tags      = array_map( 'intval', $settings['settings']['tags'] );
					$args['tag__in'] = $tags;
				}
				$args['post_type'] = array( 'post' );
				break;
			case 'both':
				if ( isset( $settings['settings']['categories'] ) ) {
					$categories           = array_map( 'intval', $settings['settings']['categories'] );
					$args[0]['category__in'] = $categories;
					$args[0]['post_type'] = array( 'post' );
				}
				if ( isset( $settings['settings']['tags'] ) ) {
					$tags      = array_map( 'intval', $settings['settings']['tags'] );
					$args[1]['tag__in'] = $tags;
					$args[1]['post_type'] = array( 'post' );
				}
				break;
			default:
				break;
		}
	}

	return $args;
}
