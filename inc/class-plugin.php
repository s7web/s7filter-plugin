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
use S7designFilter\Front\FrontController;
use S7designFilter\Assets\AssetsLoad;

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
	 * Asset loader
	 *
	 * @var AssetsLoad
	 */
	private $asset_loader;

	/**
	 * Plugin constructor.
	 *
	 * @param \stdClass $config Configuration object.
	 */
	public function __construct( \stdClass $config ) {

		$this->config = $config;
		$this->asset_loader = new AssetsLoad( $this->config );
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

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_end_scripts' ) );
		$front_controller = new FrontController( $this->config );
		add_action( 'template_include', array( $front_controller, 'template_redirect' ), 99 );
		add_action( 'wp_ajax_ot_api_data', array( $front_controller, 'provide_data' ) );
		add_action( 'wp_ajax_nopriv_ot_api_data', array( $front_controller, 'provide_data' ) );
	}

	/**
	 * Add scripts for handling UI
	 *
	 * @return void
	 */
	public function admin_scripts() {

		$this->asset_loader->load_admin_assets();

		$is_pages_setting = ( isset( $_REQUEST['settings_page'] ) && 'pages' === $_REQUEST['settings_page'] ) ? true : false;
		wp_localize_script(
			's7_interface_handler',
			's7_interface',
			array(
				'no_pages'   => __( 'Sorry but there is no configured pages right now!', 'otrs-filter' ),
				'is_pages'   => $is_pages_setting,
				'empty_name' => __( 'Page name can not be empty', 'otrs-filter' ),
				'less_name'  => __( 'This must be page id, start typing then select result from autocomplete', 'otrs-filter' ),
			)
		);
	}

	/**
	 * Load front end scripts
	 *
	 * @return void
	 */
	public function front_end_scripts() {
		wp_enqueue_script(
			'react-js',
			$this->config->js_path . 'react.js',
			false,
			'1',
			false
		);
		wp_enqueue_script(
			'ot_filter_component',
			$this->config->js_path . 'filter_component_js/filter_component.js',
			array( 'react-js', 'jquery', 'underscore' ),
			'1',
			true
		);
		wp_localize_script( 'ot_filter_component',
			'filter_objects',
			array(
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'page_id'      => get_the_ID(),
				'title_cat'    => __( 'Countries', 'otrs-filter' ),
				'title_tag'    => __( 'Sectors', 'otrs-filter' ),
				'used_filters' => __( 'Used filters', 'otrs-filter' ),
			)
		);
	}

	/**
	 * Add styles for admin UI
	 *
	 * @return void
	 */
	public function admin_styles() {

		wp_enqueue_style( 'otrs-filter-style', $this->config->css_path . 'admin_interface.css' );
	}

}
