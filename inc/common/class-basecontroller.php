<?php
/**
 * Base controller
 *
 * Used to provide base functionality for most common controller operations
 *
 * @package S7designFilter
 */

namespace S7designFilter\Common;

/**
 * Class BaseController
 *
 * @package S7designFilter\Common
 */
class BaseController {

	/**
	 * Configuration paths
	 *
	 * @var object
	 */
	protected $config;

	/**
	 * Set up config for Controller
	 *
	 * @param object $config Configuration paths.
	 */
	public function __construct( $config ) {

		$this->config = $config;
	}

	/**
	 * Render view template
	 *
	 * @param string $view View name.
	 * @param array  $data Data to be passed to view.
	 *
	 * @return void
	 */
	protected function render( $view, $data = array() ) {

		extract( $data );
		include $this->config->base_path . '/inc/view/' . $view . '.php';
	}

	/**
	 * Get request variable
	 *
	 * @param string $key Key for value.
	 *
	 * @return string|false
	 */
	protected function get( $key ) {

		return isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : false;
	}
}
