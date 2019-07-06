<?php
/**
 * Social Elementor Blog Module.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Blog;

use SocialElementor\Base\Module_Base;
use Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module.
 */
class Module extends Module_Base {

	/**
	 * Module should load or not.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @return bool true|false.
	 */
	public static function is_enable() {
		return true;
	}

	/**
	 * Get Module Name.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'posts';
	}

	/**
	 * Get Widgets.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @return array Widgets.
	 */
	public function get_widgets() {
		return [
			'Blog' ,
		];
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		/**
		 * Pagination Break.
		 *
		 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
		 */
		add_action( 'pre_get_posts', [ $this, 'fix_query_offset' ], 1 );
		add_filter( 'found_posts', [ $this, 'fix_query_found_posts' ], 1, 2 );
	}

	/**
	 * Get Widget Setting data.
	 *
	 * @since 1.7.0
	 * @access public
	 * @param array  $elements Element array.
	 * @param string $form_id Element ID.
	 * @return Boolean True/False.
	 */
	public function find_element_recursive( $elements, $form_id ) {

		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = $this->find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	/**
	 * Query Offset Fix.
	 *
	 * @since 1.8.4
	 * @access public
	 * @param object $query query object.
	 */
	public function fix_query_offset( &$query ) {
		if ( ! empty( $query->query_vars['offset_to_fix'] ) ) {
			if ( $query->is_paged ) {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + ( ( $query->query_vars['paged'] - 1 ) * $query->query_vars['posts_per_page'] );
			} else {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
			}
		}
	}

	/**
	 * Query Found Posts Fix.
	 *
	 * @since 1.8.4
	 * @access public
	 * @param int    $found_posts found posts.
	 * @param object $query query object.
	 * @return int string
	 */
	public function fix_query_found_posts( $found_posts, $query ) {
		$offset_to_fix = $query->get( 'offset_to_fix' );

		if ( $offset_to_fix ) {
			$found_posts -= $offset_to_fix;
		}

		return $found_posts;
	}
}
