<?php
/**
 * Created by PhpStorm.
 * User: nenadpaic
 * Date: 8/1/17
 * Time: 11:32 AM
 */

namespace S7designFilter\Front;


use S7designFilter\Common\BaseController;

class FrontController extends BaseController {

	/**
	 * Change template if is page from settings
	 *
	 * @param string $template Current template.
	 *
	 * @return string
	 */
	public function template_redirect( $template ) {
		$current_post_id = get_the_ID();
		$settings        = get_page_settings_by_id( $current_post_id );
		if ( $settings ) {
			$path_template = 'filter-template.php';
			$new_template  = locate_template( array( $path_template ) );
			if ( '' !== $new_template ) {
				return $new_template;
			} else {
				return $this->config->base_path . '/inc/templates/filter-template.php';
			}
		}

		return $template;
	}

	public function provide_data() {

		$page_id  = (int) $this->get( 'page_id' );
		$args     = array();
		$settings = get_page_settings_by_id( $page_id );
		$params   = $this->get( 'params' );
		if ( false !== $params && (isset($params['categories']) || isset($params['tags'])) ) {

			if ( isset( $settings[ 'settings' ][ 'filter' ] ) ) {
				switch ( $settings[ 'settings' ][ 'filter' ] ) {
					case 'tags':
					case 'categories':
					case 'both':
						if ( isset( $params['categories'] ) ) {
							$args['category__in'] = array_map( function ( $cat ) {
								return get_cat_ID( $cat );
							}, $params['categories'] );
						}
						if ( isset( $params['tags'] ) ) {
							$args['tag__in'] = array_map( function ( $tag ) {
								$tag_obj = get_term_by( 'name', $tag, 'post_tag' );

								return $tag_obj->term_id;
							}, $params['tags'] );
						}
						$args['post_type'] = array( 'post' );
						break;
					default:
						$args['post_type'] = array( 'post' );
						break;
				}
			}
		} else {
			$current_page = (isset($params['current_page'])) ? $params['current_page'] : 1;
			$args         = parse_settings( $settings );
			if(isset($args[0]) && isset($args[1])){
				$query1 = new \WP_Query($args[0]);
				$query2 = new \WP_Query($args[1]);
				$ids_of_queries = array_merge( array_map( function( $post ) { return $post->ID; }, $query1->posts ), array_map( function( $post ) { return $post->ID; }, $query2->posts ) );
				$args = array('post__in' => $ids_of_queries);
				$args['post_type'] = array( 'post' );
			}
			$per_page = (isset($settings['settings']['per_page'])) ? (int) $settings['settings']['per_page'] : 10;
			$args['offset'] = ($current_page - 1) * $per_page;
			$args['posts_per_page'] = $per_page;
		}
		$query  = new \WP_Query( $args );
		$output = array();
		$output['selected'] = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$output['selected'][] = array(
					'post_id'   => get_the_ID(),
					'post_name' => get_the_title(),
					'excerpt'   => get_the_excerpt(),
					'thumbnail' => get_the_post_thumbnail(),
				);
				$output['settings'] = $settings['settings'];
			}
		}
		if ( isset( $settings['settings']['filter'] ) ) {
			$output['categories'] = $this->filter_check( $settings['settings'] );
			$output['tags']       = $this->filter_check( $settings['settings'], true );
		}
		wp_send_json_success( $output );
	}

	/**
	 * Return results based on the selected filter
	 *
	 * @param  string $settings
	 * @param  bool   [$tags]
	 *
	 * @return array|null
	 */
	private function filter_check( $settings, $tags = false ) {

		$filter = $settings[ 'filter' ];

		if ( $tags ) {
			switch ( $filter ) {
				default:
					break;
				case 'both':case 'tags':

				return array_map( function ($tag) {
					$tag_obj = get_tag( $tag );

					return $tag_obj->name;
				}, $settings[ 'tags' ] );
			}

			return null;
		}

		switch ( $filter ) {
			default:
				break;
			case 'both':case 'categories':

			return array_map( function ($cat) {
				return get_cat_name( $cat );
			}, $settings['categories'] );
		}

		return null;
	}
}