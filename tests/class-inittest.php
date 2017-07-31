<?php

/**
 * Class SampleTest
 *
 * @package Filter_Plugin
 */

/**
 * Sample test case.
 */
class InitTest extends WP_UnitTestCase {

	/**
	 * Covers configuration in filter-plugin.php file
	 */
	function test_config() {

		$config = get_config();


		$this->assertEquals( array(
			'version' => 0.1,
		), $config, 'Config is not right' );
	}
}
