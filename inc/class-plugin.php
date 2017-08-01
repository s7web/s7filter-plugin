<?php
/**
 * Plugin class
 *
 * Base class for running and invoking plugin actions
 *
 * @package S7designFilter
 */

namespace S7designFilter;

use S7designFilter\Admin\Settings;

/**
 * Class Plugin
 *
 * @package S7designFilter
 */
class Plugin {

	/**
	 * Configuration container
	 *
	 * @var \stdClass
	 */
	private $config;

	/**
	 * Plugin constructor.
	 *
	 * @param \stdClass $config Configuration object.
	 */
	public function __construct( \stdClass $config ) {
		$this->config = $config;
	}

	/**
	 * Boot plugin actions
	 */
	public function boot() {

		if ( is_admin() ) {
			$admin_settings = new Settings( $this->config );
			add_action( 'admin_menu', array( $admin_settings, 'init_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_ajax_ot_get_all_pages', array( $admin_settings, 'get_all_pages' ) );
			add_action( 'wp_ajax_ot_get_all_pages_autocomplete', array( $admin_settings, 'get_pages_from_table_with_params' ) );
			add_action( 'wp_ajax_ot_save_option_pages', array( $admin_settings, 'save_option_pages' ) );

			add_action( 'admin_post_otrs_post_settings', array( $admin_settings, 'save_general_settings' ) );
			add_action( 'admin_post_otrs_style_settings', array( $admin_settings, 'save_general_settings' ) );
			add_action( 'admin_post_otrs_save_page_settings', array( $admin_settings, 'save_page_settings' ) );
		}
	}

	/**
	 * Add scripts for handling UI
	 *
	 * @return void
	 */
	public function admin_scripts() {

		wp_enqueue_script(
			'ot_interface_handler',
			$this->config->js_path . 'admin_interface.js',
			array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-autocomplete', 'jquery-ui-accordion' ),
			'1',
			true
		);
		wp_enqueue_style(
			'jquery-ui-css',
			'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css',
			false,
			'1',
			false
		);
		$is_pages_setting = ( isset( $_REQUEST['settings_page'] ) && 'pages' === $_REQUEST['settings_page'] ) ? true : false;
		wp_localize_script(
			'ot_interface_handler',
			'ot_interface',
			array(
				'no_pages' => __( 'Sorry but there is no configured pages right now!', 'otrs-filter' ),
				'is_pages' => $is_pages_setting,
				'empty_name' => __( 'Page name can not be empty', 'otrs-filter' ),
				'less_name' => __( 'This must be page id, start typing then select result from autocomplete', 'otrs-filter' ),
			)
		);
	}

}
