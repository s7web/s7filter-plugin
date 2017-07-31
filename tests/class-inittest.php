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

		$this->assertInstanceOf( 'S7designFilter\Plugin', init() );
	}

	/**
	 * Test case when we don't have settings saved in wp-option for some page we should get a false
	 */
	public function test_getsettingspage_nosetting() {

		$this->assertFalse( get_page_settings_by_id( 1 ), 'Settings does not exist so we should not get them back' );
	}

	/**
	 * Case when we have setting for specific page, we should return that setting for given page
	 */
	public function test_getsettingspage_withsetting() {

		$this->factory->post->create(
			array(
				'post_title' => 'Test Post',
				'post_type'  => 'page',
			)
		);

		$page = get_page_by_title( 'Test Post' );

		$test_setting = array(
			$page->ID =>
			array(
				'title'    => 'Sample Page',
				'settings' =>
					array(
						'filter'        => 'both',
						'per_page'      => '10',
						'categories'    =>
							array(
								0 => '1',
							),
						'template'      => 'default-thumb-enabled',
						'dateformat'    => '',
						'heading'       => '',
						'heading_class' => '',
					),
			),
		);

		update_option( 's7filter-settings', $test_setting );

		$this->assertInternalType( 'array', get_page_settings_by_id( $page->ID ), 'Settings exists, and they should be returned back to caller' );
	}

	/**
	 * Test how settings are parsed, we should always get correct wp-query in this case both settings should be parsed
	 */
	public function test_parsesettings_both() {

		$test_setting = array(
			'title'    => 'Sample Page',
			'settings' =>
				array(
					'filter'        => 'both',
					'per_page'      => '10',
					'categories'    =>
						array(
							0 => '1',
						),
					'tags'          => array(
						0 => '1',
						1 => '2',
					),
					'template'      => 'default-thumb-enabled',
					'dateformat'    => '',
					'heading'       => '',
					'heading_class' => '',
				),
		);

		$args = array(
			array(
				'category__in' => array( 1 ),
				'post_type'    => array( 'post' ),
			),
			array(
				'tag__in'   => array( 1, 2 ),
				'post_type' => array( 'post' ),
			),
		);

		$this->assertInternalType( 'array', parse_settings( $test_setting ), 'Settings exists, they should be array of args' );
		$this->assertEquals( json_encode( $args ), json_encode( parse_settings( $test_setting ) ), 'Settings must be correctly parsed' );
	}

	/**
	 * Test how settings are parsed, we should always get correct wp-query in this case category should be parsed, tags avoided
	 */
	public function test_parsesettings_categories_only() {

		$test_setting = array(
			'title'    => 'Sample Page',
			'settings' =>
				array(
					'filter'        => 'categories',
					'per_page'      => '10',
					'categories'    =>
						array(
							0 => '1',
						),
					'tags'          => array(
						0 => '1',
						1 => '2',
					),
					'template'      => 'default-thumb-enabled',
					'dateformat'    => '',
					'heading'       => '',
					'heading_class' => '',
				),
		);

		$args = array(
				'category__in' => array( 1 ),
				'post_type'    => array( 'post' ),
		);

		$this->assertInternalType( 'array', parse_settings( $test_setting ), 'Settings exists, they should be array of args' );
		$this->assertEquals( json_encode( $args ), json_encode( parse_settings( $test_setting ) ), 'Settings must be correctly parsed' );
	}

	/**
	 * Test how settings are parsed, we should always get correct wp-query in this case tags should be parsed, categories avoided
	 */
	public function test_parsesettings_tags_only() {

		$test_setting = array(
			'title'    => 'Sample Page',
			'settings' =>
				array(
					'filter'        => 'tags',
					'per_page'      => '10',
					'categories'    =>
						array(
							0 => '1',
						),
					'tags'          => array(
						0 => '1',
						1 => '2',
					),
					'template'      => 'default-thumb-enabled',
					'dateformat'    => '',
					'heading'       => '',
					'heading_class' => '',
				),
		);

		$args = array(
			'tag__in' => array( 1, 2 ),
			'post_type'    => array( 'post' ),
		);

		$this->assertInternalType( 'array', parse_settings( $test_setting ), 'Settings exists, they should be array of args' );
		$this->assertEquals( json_encode( $args ), json_encode( parse_settings( $test_setting ) ), 'Settings must be correctly parsed' );
	}
}
