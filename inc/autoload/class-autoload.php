<?php
/**
 * Autoloader for plugin
 *
 * @package OtrsFilter\Autoloader
 */
namespace S7designFilter\Autoload;
/**
 * Class Autoloader
 *
 * @package S7licence\Autoloader
 */
class Autoload {
	/**
	 * Path
	 * @var string
	 */
	private $dir;
	/**
	 * Set up class properties
	 *
	 * @param string $dir Root dir of project.
	 */
	public function __construct( $dir ) {
		$this->dir = $dir;
	}
	/**
	 * Run autoload
	 *
	 * @return void
	 */
	public function load() {
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Autoload classes
	 *
	 * @param string $cls Class for autoload.
	 *
	 * @return void
	 */
	public function autoload( $cls ) {
		$cls = ltrim( $cls, '\\' );
		$cls = str_replace( __NAMESPACE__, '', $cls );
		$cls = str_replace( '\\', '/', $cls );
		$cls = explode( '/', $cls );
		if ( isset( $cls[0] ) && 'S7designFilter' === $cls[0] ) {
			array_shift( $cls );
		}
		$class_name = 'class-' . array_pop( $cls );
		$cls[]      = $class_name;
		$cls        = implode( '/', $cls );
		$path       = $this->dir . '/inc/' .
		              strtolower( $cls ) . '.php';
		if ( is_readable( $path ) ) {
			require( $path );
		}
	}
}