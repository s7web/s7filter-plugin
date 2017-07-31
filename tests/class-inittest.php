<?php
/**
 * Class SampleTest
 *
 * @package Filter_Plugin
 */

use S7designFilter\Plugin;
/**
 * Sample test case.
 */
class InitTest extends WP_UnitTestCase {

	/**
	 * Covers configuration in filter-plugin.php file
	 */
	function test_config() {

		$config = get_config();

		$plugin_uri = plugin_dir_url( __DIR__ . '../filter-plugin.php' );

		$this->assertArrayHasKey( 'version', $config, 'Config has no version' );
		$this->assertArrayHasKey( 'base_path', $config, 'Config has no base path' );
		$this->assertArrayHasKey( 'js_path', $config, 'Config has no js path' );
		$this->assertArrayHasKey( 'css_path', $config, 'Config has no css_path' );
		$this->assertArrayHasKey( 'img_path', $config, 'Config has no img_path' );
	}

	/**
	 * Covers init and Plugin class creation in filter-plugin.php file
	 */
	public function test_plugininstance() {

		$this->assertInstanceOf( Plugin::class, init() );
	}
}
