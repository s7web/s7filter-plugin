<?php
/**
 * Asset loader for filter plugin
 *
 * @package S7designFilter
 */

namespace S7designFilter\Assets;

/**
 * Class AssetsLoad
 *
 * @package S7designFilter\Assets
 */
class AssetsLoad {

	/**
	 * Configuration
	 *
	 * @var \stdClass
	 */
	private $config;

	/**
	 * Class constructor
	 *
	 * @param \stdClass $config Plugin configuration.
	 */
	public function __construct( \stdClass $config ) {
		$this->config = $config;
	}

	/**
	 * Define and return scripts that should be loaded in admin
	 *
	 * @return array
	 */
	public function get_admin_scripts() {

		return array(
			array(
				'handler'		 => 's7_interface_handler',
				'src'			 => $this->config->js_path . 'admin_interface.js',
				'dependencies'	 => array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-autocomplete', 'jquery-ui-accordion' ),
				'version'		 => '1',
				'footer'		 => true,
				'script'		 => true,
			),
		);
	}

	/**
	 * Define and return styles that should be loaded in admin
	 *
	 * @return array
	 */
	public function get_admin_styles() {

		return array(
			array(
				'handler'		 => 'jquery-ui-css',
				'src'			 => 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css',
				'dependencies'	 => array(),
				'version'		 => '1',
				'media'			 => false,
				'script'		 => false,
			),
		);
	}

	/**
	 * Define and return front-end scripts
	 *
	 * @return array
	 */
	public function get_front_scripts() {
		return array(
			array(
				'handler'		 => 'react-js',
				'src'			 => $this->config->js_path . 'react.js',
				'dependencies'	 => array(),
				'version'		 => '1',
				'footer'		 => false,
				'script'		 => true,
			),
			array(
				'handler'		 => 's7_filter_component',
				'src'			 => $this->config->js_path . 'filter_component_js/filter_component.js',
				'dependencies'	 => array( 'react-js', 'jquery', 'underscore' ),
				'version'		 => '1',
				'footer'		 => true,
				'script'		 => true,
			),
		);
	}

	/**
	 * Load assets action
	 *
	 * Load previously defined front-end scripts
	 *
	 * @return void
	 */
	public function load_front_assets() {

		foreach ( $this->get_front_scripts() as $asset ) {
			wp_enqueue_script( $asset['handler'], $asset['src'], $asset['dependencies'], $asset['version'], $asset['footer'] );
		}
	}

	/**
	 * Load assets action
	 *
	 * Load previously defined scripts and styles
	 *
	 * @return void
	 */
	public function load_admin_assets() {

		foreach ( array_merge( $this->get_admin_scripts(), $this->get_admin_styles() ) as $asset ) {

			if ( $asset['script'] ) {
				wp_enqueue_script( $asset['handler'], $asset['src'], $asset['dependencies'], $asset['version'], $asset['footer'] );
			} else {
				wp_enqueue_style( $asset['handler'], $asset['src'], $asset['dependencies'], $asset['version'], $asset['media'] );
			}
		}
	}

}
