<?php
/**
 * Social Elementor Blog Abstract Base Class.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Blog\Widgets;

use Elementor\Controls_Manager;
use SocialElementor\Base\Common_Widget;

use SocialElementor\Classes\Social_Posts_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Blog_Base
 */
abstract class Blog_Base extends Common_Widget {

	/**
	 * Query object
	 *
	 * @since 1.7.0
	 * @var object $query
	 */
	protected $query = null;

	/**
	 * Query object
	 *
	 * @since 1.7.0
	 * @var boolean $_has_template_content
	 */
	protected $_has_template_content = false;

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {

		return array(
			'imagesloaded',
			'jquery-slick',
			'social-elementor-isotope',
			'social-blog-posts',
			'social-elementor-element-resize',
		);
	}

	/**
	 * Render current query.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	public function get_query() {

		return $this->query;
	}

	/**
	 * Render output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	public function render() {}

	/**
	 * Set current query.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	abstract public function query_posts();

	/**
	 * Register controls.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->end_controls_section();

		$this->register_content_query_controls();
	}

	/**
	 * Register Posts Query Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_content_query_controls() {

		$this->start_controls_section(
			'section_filter_field',
			array(
				'label' => __( 'Query', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'query_type',
				array(
					'label'       => __( 'Query Type', 'social-elementor' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'custom',
					'label_block' => true,
					'options'     => array(
						'main'   => __( 'Main Query', 'social-elementor' ),
						'custom' => __( 'Custom Query', 'social-elementor' ),
					),
				)
			);

			$post_types = Social_Posts_Helper::get_post_types();

			$this->add_control(
				'post_type_filter',
				array(
					'label'       => __( 'Post Type', 'social-elementor' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'post',
					'label_block' => true,
					'options'     => $post_types,
					'separator'   => 'after',
					'condition'   => array(
						'query_type' => 'custom',
					),
				)
			);

		foreach ( $post_types as $key => $type ) {

			// Get all the taxanomies associated with the post type.
			$taxonomy = Social_Posts_Helper::get_taxonomy( $key );

			if ( ! empty( $taxonomy ) ) {

				// Get all taxonomy values under the taxonomy.
				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					$related_tax = array();

					if ( ! empty( $terms ) ) {

						foreach ( $terms as $t_index => $t_obj ) {

							$related_tax[ $t_obj->slug ] = $t_obj->name;
						}
						$this->add_control(
							$index . '_' . $key . '_filter_rule',
							array(
								/* translators: %s Label */
								'label'       => sprintf( __( '%s Filter Rule', 'social-elementor' ), $tax->label ),
								'type'        => Controls_Manager::SELECT,
								'default'     => 'IN',
								'label_block' => true,
								'options'     => array(
									/* translators: %s label */
									'IN'     => sprintf( __( 'Match %s', 'social-elementor' ), $tax->label ),
									/* translators: %s label */
									'NOT IN' => sprintf( __( 'Exclude %s', 'social-elementor' ), $tax->label ),
								),
								'condition'   => array(
									'post_type_filter' => $key,
									'query_type'       => 'custom',
								),
							)
						);

						// Add control for all taxonomies.
						$this->add_control(
							'tax_' . $index . '_' . $key . '_filter',
							array(
								/* translators: %s label */
								'label'       => sprintf( __( '%s Filter', 'social-elementor' ), $tax->label ),
								'type'        => Controls_Manager::SELECT2,
								'multiple'    => true,
								'default'     => '',
								'label_block' => true,
								'options'     => $related_tax,
								'condition'   => array(
									'post_type_filter' => $key,
									'query_type'       => 'custom',
								),
								'separator'   => 'after',
							)
						);

					}
				}
			}
		}
			$this->add_control(
				'author_filter_rule',
				array(
					'label'       => __( 'Author Filter Rule', 'social-elementor' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'author__in',
					'label_block' => true,
					'options'     => array(
						'author__in'     => __( 'Match Author', 'social-elementor' ),
						'author__not_in' => __( 'Exclude Author', 'social-elementor' ),
					),
					'condition'   => array(
						'query_type' => 'custom',
					),
				)
			);

			$this->add_control(
				'author_filter',
				array(
					'label'       => __( 'Author Filter', 'social-elementor' ),
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'default'     => '',
					'label_block' => true,
					'options'     => Social_Posts_Helper::get_users(),
					'separator'   => 'after',
					'condition'   => array(
						'query_type' => 'custom',
					),
				)
			);

			$this->add_control(
				'post_filter_rule',
				array(
					'label'       => __( 'Post Filter Rule', 'social-elementor' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'post__in',
					'label_block' => true,
					'options'     => array(
						'post__in'     => __( 'Match Posts', 'social-elementor' ),
						'post__not_in' => __( 'Exclude Posts', 'social-elementor' ),
					),
					'condition'   => array(
						'query_type' => 'custom',
					),
				)
			);

			$this->add_control(
				'post_filter',
				array(
					'label'       => __( 'Post Filter', 'social-elementor' ),
					'type'        => 'social-blog-posts-query',
					'post_type'   => 'all',
					'multiple'    => true,
					'label_block' => true,
					'separator'   => 'after',
					'condition'   => array(
						'query_type' => 'custom',
					),
				)
			);

			$this->add_control(
				'orderby_heading',
				array(
					'label'     => __( 'Post Order', 'social-elementor' ),
					'type'      => Controls_Manager::HEADING,
					'condition' => array(
						'query_type' => 'custom',
					),
					'separator' => 'before',
				)
			);

			$this->add_control(
				'orderby',
				array(
					'label'     => __( 'Order by', 'social-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'date',
					'options'   => array(
						'date'       => __( 'Date', 'social-elementor' ),
						'title'      => __( 'Title', 'social-elementor' ),
						'rand'       => __( 'Random', 'social-elementor' ),
						'menu_order' => __( 'Menu Order', 'social-elementor' ),
					),
					'condition' => array(
						'query_type' => 'custom',
					),
				)
			);

			$this->add_control(
				'order',
				array(
					'label'     => __( 'Order', 'social-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'desc',
					'options'   => array(
						'desc' => __( 'Descending', 'social-elementor' ),
						'asc'  => __( 'Ascending', 'social-elementor' ),
					),
					'condition' => array(
						'query_type' => 'custom',
					),
				)
			);

			$this->add_control(
				'noposts_heading',
				array(
					'label'     => __( 'If Posts Not Found', 'social-elementor' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'no_results_text',
				array(
					'label'       => __( 'Display Message', 'social-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => __( 'Sorry, we couldn\'t find any posts. Please try a different search.', 'social-elementor' ),
				)
			);

			$this->add_control(
				'show_search_box',
				array(
					'label'        => __( 'Search Box', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'social-elementor' ),
					'label_off'    => __( 'No', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => 'no',
				)
			);

		$this->end_controls_section();
	}
}
