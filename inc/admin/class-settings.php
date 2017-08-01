<?php
/**
 * Setting page for admin
 *
 * @package S7designFilter
 */

namespace S7designFilter\Admin;

use S7designFilter\Common\BaseController;

/**
 * Class Settings
 *
 * @package S7designFilter\Admin
 */
class Settings extends BaseController {

	/**
	 * Set admin menu page for plugin
	 *
	 * @wp-hook admin_menu
	 *
	 * @return void
	 */
	public function init_menu() {
		add_menu_page(
			__( 'Filter settings', 's7design-filter' ),
			__( 'Filter settings', 's7design-filter' ),
			'manage_options',
			's7design-posts-filter-settings',
			array( $this, 'display' ),
			'dashicons-filter',
			81
		);
	}

	/**
	 * Get all pages saved for filtering
	 *
	 * @wp-hook wp_ajax_ot_get_all_pages
	 *
	 * @return void
	 */
	public function get_all_pages() {

		$pages = get_option( 's7filter-settings' );

		wp_send_json( $pages );
	}

	/**
	 * Get pages for auto complete functionality on front end
	 *
	 * @wp-hook wp_ajax_ot_get_all_pages_autocomplete
	 *
	 * @return void
	 */
	public function get_pages_from_table_with_params() {

		$term    = $this->get( 'term' );
		$pages   = new \WP_Query(
			array(
				's'         => $term,
				'post_type' => 'page',
			)
		);
		$results = array();
		if ( $pages->have_posts() ) {
			while ( $pages->have_posts() ) {
				$pages->the_post();
				$results[] = array(
					'label' => get_the_title(),
					'value' => get_the_ID(),
				);
			}
		} else {
			wp_send_json_error();
		}

		wp_send_json( $results );
	}


	/**
	 * Save new pages to filter plugin options ( Save configuration )
	 *
	 * @wp-hook wp_ajax_ot_save_option_pages
	 *
	 * @return void
	 */
	public function save_option_pages() {

		$data        = (int) $this->get( 'page_id' );
		$page        = new \WP_Query(
			array(
				'page_id' => $data,
			)
		);
		$old_options = get_option( 's7filter-settings' );
		if ( $page->have_posts() ) {
			while ( $page->have_posts() ) {
				$page->the_post();
				$old_options[ $data ] = array(
					'title'    => get_the_title(),
					'settings' => array(),
				);
			}
		} else {
			wp_send_json_error();
		}

		if ( update_option( 's7filter-settings', $old_options ) ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * Save general settings to options table
	 *
	 * @return void
	 */
	public function save_general_settings() {

		update_option( $this->get( 'action' ), $this->get( 's7filter-settings-data' ) );
		wp_redirect( admin_url( 'admin.php?page=s7design-posts-filter-settings' ) );

	}

	/**
	 * Save settings for single page
	 *
	 * @return void
	 */
	public function save_page_settings() {

		$data                                        = get_option( 's7filter-settings' );
		$data[ $this->get( 'page-id' ) ]['settings'] = $this->get( 's7filter-settings-data' );
		update_option( 's7filter-settings', $data );
		wp_redirect( admin_url( 'admin.php?page=s7design-posts-filter-settings&settings_page=pages' ) );
	}

	/**
	 * Display admin page for plugin
	 *
	 * @return void
	 */
	public function display() {

		$pages = add_query_arg( 'settings_page', 'pages' );
		?>
		<h1><span class="dashicons dashicons-filter"></span><?php esc_html_e( 'Filter settings', 's7design-filter' ); ?>
		</h1>
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo esc_attr( $pages ); ?>"
			   class="nav-tab nav-tab-active"><?php esc_html_e( 'Pages settings', 's7design-filter' ); ?></a>
		</h2>
		<?php
		$this->render( 'pages-settings',
			array(
				'pages'      => get_option( 's7filter-settings' ),
				'categories' => get_categories(
					array(
						'hide_empty' => 0,
					)
				),
				'tags'       => get_tags(
					array(
						'hide_empty' => 0,
					)
				),
			)
		);
	}
}
