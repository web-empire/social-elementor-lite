<?php
/**
 * Social Elementor Base Skin.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Blog\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;

use SocialElementor\Classes\Social_Helper;
use SocialElementor\Classes\Social_Posts_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Base
 */
abstract class Skin_Base extends Elementor_Skin_Base {

	/**
	 * Query object
	 *
	 * @since 1.7.0
	 * @var object $query
	 */
	public static $query;

	/**
	 * Register controls on given actions.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	protected function _register_controls_actions() {

		add_action( 'elementor/element/social-blog-posts/section_filter_field/after_section_end', array( $this, 'register_sections' ) );

		add_action( 'elementor/element/social-blog-posts/section_layout/after_section_end', array( $this, 'register_sections_before' ) );
	}

	/**
	 * Register controls callback.
	 *
	 * @param Widget_Base $widget Current Widget object.
	 * @since 1.7.0
	 * @access public
	 */
	public function register_sections( Widget_Base $widget ) {

		$this->parent = $widget;

		// Content Controls.
		$this->register_content_slider_controls();
		$this->register_content_image_controls();
		$this->register_content_title_controls();
		$this->register_content_meta_controls();
		$this->register_content_badge_controls();
		$this->register_content_excerpt_controls();
		$this->register_content_cta_controls();

		// Style Controls.
		$this->register_style_layout_controls();
		$this->register_style_blog_controls();
		$this->register_style_title_controls();
		$this->register_style_meta_controls();
		$this->register_style_term_controls();
		$this->register_style_excerpt_controls();
		$this->register_style_cta_controls();
		$this->register_style_navigation_controls();

	}

	/**
	 * Register controls callback.
	 *
	 * @param Widget_Base $widget Current Widget object.
	 * @since 1.7.0
	 * @access public
	 */
	public function register_sections_before( Widget_Base $widget ) {

		$this->parent = $widget;

		// Content Controls.
		$this->register_content_general_controls();
	}

	/**
	 * Register Posts General Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_content_general_controls() {

		$this->start_controls_section(
			'section_general_field',
			array(
				'label' => __( 'General', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'post_structure',
				array(
					'label'   => __( 'Layout', 'social-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'masonry',
					'options' => array(
						'masonry' => __( 'Masonry', 'social-elementor' ),
						'normal'  => __( 'Grid', 'social-elementor' ),
					),
				)
			);

			$this->add_control(
				'posts_per_page',
				array(
					'label'       => __( 'Posts Per Page', 'social-elementor' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => '6',
					'label_block' => false,
					'condition'   => array(
						'query_type' => 'custom',
					),
				)
			);

			$this->add_responsive_control(
				'slides_to_show',
				array(
					'label'          => __( 'Columns', 'social-elementor' ),
					'type'           => Controls_Manager::NUMBER,
					'default'        => 3,
					'tablet_default' => 2,
					'mobile_default' => 1,
					'min'            => 1,
					'max'            => 8,
				)
			);

			$this->add_control(
				'featured_post',
				array(
					'label'     => __( 'Featured Post', 'social-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'inline',
					'options'   => array(
						'inline' => __( 'Inline', 'social-elementor' ),
						'stack'  => __( 'Stack', 'social-elementor' ),
					),
					'condition' => array(
						$this->get_control_id( 'post_structure' ) => 'featured',
					),
				)
			);

			$this->add_control(
				'link_complete_box',
				array(
					'label'        => __( 'Link Complete Post Box', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => '',
					'prefix_class' => 'social-blog-post-link-complete-',
				)
			);

			$this->add_control(
				'link_complete_box_tab',
				array(
					'label'        => __( 'Open in New Tab', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'social-elementor' ),
					'label_off'    => __( 'No', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => array(
						$this->get_control_id( 'link_complete_box' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'equal_height',
				array(
					'label'        => __( 'Equal Posts Height', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => array(
						$this->get_control_id( 'post_structure' ) => 'carousel',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register Posts Featured Image Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_content_image_controls() {

		$this->start_controls_section(
			'section_image_field',
			array(
				'label' => __( 'Image', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'image_position',
				array(
					'label'       => __( 'Image Position', 'social-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT,
					'default'     => 'top',
					'options'     => array(
						'top'        => __( 'Top', 'social-elementor' ),
						'background' => __( 'Background', 'social-elementor' ),
						'none'       => __( 'None', 'social-elementor' ),
					),
				)
			);

			$this->add_control(
				'image_size',
				array(
					'label'       => __( 'Image Size', 'social-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT,
					'default'     => 'full',
					'options'     => Social_Posts_Helper::get_image_sizes(),
					'condition'   => array(
						$this->get_control_id( 'image_position' ) => array( 'top', 'background' ),
					),
				)
			);

			$this->add_control(
				'image_custom_dimension',
				array(
					'label'       => __( 'Image Dimension', 'social-elementor' ),
					'type'        => Controls_Manager::IMAGE_DIMENSIONS,
					'description' => __( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'social-elementor' ),
					'default'     => array(
						'width'  => '',
						'height' => '',
					),
					'condition'   => array(
						$this->get_control_id( 'image_size' ) => 'custom',
					),
				)
			);

			$this->add_control(
				'image_background_color',
				array(
					'label'     => __( 'Overlay Color', 'social-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-img-background .social-blog-post-thumbnail::before' => 'background-color: {{VALUE}};',
					),
					'default'   => 'rgba(246,246,246,0.8)',
					'condition' => array(
						$this->get_control_id( 'image_position' ) => 'background',
					),
				)
			);

			$this->add_control(
				'heading_image_hover_options',
				array(
					'label'     => __( 'On Hover', 'social-elementor' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						$this->get_control_id( 'image_position' ) => array( 'top', 'background' ),
					),
				)
			);

			$this->add_control(
				'image_scale_hover',
				array(
					'label'     => __( 'Scale', 'social-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min'  => 1,
							'max'  => 2,
							'step' => 0.01,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-thumbnail:hover img' => 'transform: scale({{SIZE}});',
						'{{WRAPPER}}.social-blog-post-link-complete-yes .social-blog-post-img-background .social-blog-post-complete-box-overlay:hover + .social-blog-post-inner-wrapper .social-blog-post-thumbnail img' => 'transform: translate(-50%,-50%) scale({{SIZE}});',
						'{{WRAPPER}}.social-blog-post-link-complete-yes .social-blog-post-complete-box-overlay:hover + .social-blog-post-inner-wrapper .social-blog-post-thumbnail img' => 'transform: scale({{SIZE}});',
						'{{WRAPPER}}.social-blog-post-equal-height-yes .social-blog-post-img-background .social-blog-post-inner-wrapper:hover img' => 'transform: translate(-50%,-50%) scale({{SIZE}});',
					),
					'condition' => array(
						$this->get_control_id( 'image_position' ) => array( 'top', 'background' ),
					),
				)
			);

			$this->add_control(
				'image_opacity_hover',
				array(
					'label'     => __( 'Opacity (%)', 'social-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => array(
						'size' => 1,
					),
					'range'     => array(
						'px' => array(
							'max'  => 1,
							'min'  => 0,
							'step' => 0.01,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-thumbnail:hover img' => 'opacity: {{SIZE}};',
						'{{WRAPPER}}.social-blog-post-link-complete-yes .social-blog-post-complete-box-overlay:hover + .social-blog-post-inner-wrapper .social-blog-post-thumbnail img' => 'opacity: {{SIZE}};',
						'{{WRAPPER}}.social-blog-post-equal-height-yes .social-blog-post-img-background .social-blog-post-inner-wrapper:hover img' => 'opacity: {{SIZE}};',
					),
					'condition' => array(
						$this->get_control_id( 'image_position' ) => array( 'top', 'background' ),
					),
				)
			);

			$this->add_control(
				'link_img',
				array(
					'label'        => __( 'Link Image', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'social-elementor' ),
					'label_off'    => __( 'No', 'social-elementor' ),
					'description'  => __( 'Uncheck this option, to make the image clickable.', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'separator'    => 'before',
					'condition'    => array(
						$this->get_control_id( 'image_position' ) => array( 'top', 'background' ),
					),
				)
			);

			$this->add_control(
				'link_new_tab',
				array(
					'label'        => __( 'Open in New Tab', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'social-elementor' ),
					'label_off'    => __( 'No', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => array(
						$this->get_control_id( 'link_img' ) => 'yes',
						$this->get_control_id( 'image_position' ) => array( 'top', 'background' ),
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register Slider Controls.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	protected function register_content_slider_controls() {

		$this->start_controls_section(
			'section_slider_options',
			array(
				'label'     => __( 'Carousel', 'social-elementor' ),
				'type'      => Controls_Manager::SECTION,
				'condition' => array(
					$this->get_control_id( 'post_structure' ) => 'carousel',
				),
			)
		);

			$this->add_control(
				'navigation',
				array(
					'label'   => __( 'Navigation', 'social-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'both',
					'options' => array(
						'both'   => __( 'Arrows and Dots', 'social-elementor' ),
						'arrows' => __( 'Arrows', 'social-elementor' ),
						'dots'   => __( 'Dots', 'social-elementor' ),
						'none'   => __( 'None', 'social-elementor' ),
					),
				)
			);

			$this->add_control(
				'pause_on_hover',
				array(
					'label'        => __( 'Pause on Hover', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'autoplay',
				array(
					'label'        => __( 'Autoplay', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'autoplay_speed',
				array(
					'label'     => __( 'Autoplay Speed', 'social-elementor' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 5000,
					'condition' => array(
						'autoplay' => 'yes',
					),
					'selectors' => array(
						'{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
					),
				)
			);

			$this->add_control(
				'infinite',
				array(
					'label'        => __( 'Infinite Loop', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'transition_speed',
				array(
					'label'       => __( 'Transition Speed (ms)', 'social-elementor' ),
					'type'        => Controls_Manager::NUMBER,
					'label_block' => true,
					'default'     => 500,
				)
			);

			$this->add_responsive_control(
				'slides_to_scroll',
				array(
					'label'          => __( 'Slides to Scroll', 'social-elementor' ),
					'type'           => Controls_Manager::NUMBER,
					'default'        => 1,
					'tablet_default' => 1,
					'mobile_default' => 1,
					'min'            => 1,
					'max'            => 6,
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register Taxonomy Badge Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_content_badge_controls() {

		$this->start_controls_section(
			'section_terms_field',
			array(
				'label' => __( 'Taxonomy Badge', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'terms_position',
				array(
					'label'   => __( 'Display Position', 'social-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'media'         => __( 'On Media', 'social-elementor' ),
						'above_content' => __( 'Above Content', 'social-elementor' ),
						''              => __( 'None', 'social-elementor' ),
					),
					'default' => '',
				)
			);

			$this->add_control(
				'terms_to_show',
				array(
					'label'     => __( 'Select Taxonomy', 'social-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'category' => __( 'Category', 'social-elementor' ),
						'post_tag' => __( 'Tag', 'social-elementor' ),
					),
					'condition' => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'terms_position!' ) => '',
					),
					'default'   => 'category',
				)
			);

			$this->add_control(
				'max_terms',
				array(
					'label'       => __( 'Max Terms to Show', 'social-elementor' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 1,
					'condition'   => array(
						$this->get_control_id( 'terms_position!' ) => '',
					),
					'label_block' => false,
				)
			);

		if ( Social_Helper::is_elementor_updated() ) {

			$this->add_control(
				'new_show_term_icon',
				array(
					'label'            => __( 'Term Icon', 'social-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => $this->get_control_id( 'show_term_icon' ),
					'condition'        => array(
						$this->get_control_id( 'terms_position!' ) => '',
					),
					'render_type'      => 'template',
				)
			);
		} else {

			$this->add_control(
				'show_term_icon',
				array(
					'type'      => Controls_Manager::ICON,
					'label'     => __( 'Term Icon', 'social-elementor' ),
					'default'   => 'fa fa-tag',
					'condition' => array(
						$this->get_control_id( 'terms_position!' ) => '',
					),
				)
			);
		}

			$this->add_control(
				'term_divider',
				array(
					'label'     => __( 'Term Divider', 'social-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => ',',
					'selectors' => array(
						'{{WRAPPER}} a.social-blog-listing-term-link:not(:last-child):after' => 'content: "{{VALUE}}"; margin: 0 0.4em;',
					),
					'condition' => array(
						$this->get_control_id( 'terms_position!' ) => '',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register Posts Title Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_content_title_controls() {

		$this->start_controls_section(
			'section_title_field',
			array(
				'label' => __( 'Title', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
			$this->add_control(
				'show_title',
				array(
					'label'        => __( 'Title', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'social-elementor' ),
					'label_off'    => __( 'Hide', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'link_title',
				array(
					'label'        => __( 'Link Title', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'social-elementor' ),
					'description'  => __( 'Uncheck this option, to make the title clickable.', 'social-elementor' ),
					'label_off'    => __( 'No', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => array(
						$this->get_control_id( 'show_title' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'link_title_new',
				array(
					'label'        => __( 'Open in New Tab', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'social-elementor' ),
					'label_off'    => __( 'No', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => array(
						$this->get_control_id( 'show_title' ) => 'yes',
						$this->get_control_id( 'link_title' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'title_tag',
				array(
					'label'     => __( 'HTML Tag', 'social-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'h1' => __( 'H1', 'social-elementor' ),
						'h2' => __( 'H2', 'social-elementor' ),
						'h3' => __( 'H3', 'social-elementor' ),
						'h4' => __( 'H4', 'social-elementor' ),
						'h5' => __( 'H5', 'social-elementor' ),
						'h6' => __( 'H6', 'social-elementor' ),
					),
					'default'   => 'h3',
					'condition' => array(
						$this->get_control_id( 'show_title' ) => 'yes',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register Posts meta Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_content_meta_controls() {

		$this->start_controls_section(
			'section_meta_field',
			array(
				'label' => __( 'Meta', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'show_meta',
				array(
					'label'        => __( 'Meta', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'social-elementor' ),
					'label_off'    => __( 'Hide', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'meta_tag',
				array(
					'label'     => __( 'HTML Tag', 'social-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'h1'   => __( 'H1', 'social-elementor' ),
						'h2'   => __( 'H2', 'social-elementor' ),
						'h3'   => __( 'H3', 'social-elementor' ),
						'h4'   => __( 'H4', 'social-elementor' ),
						'h5'   => __( 'H5', 'social-elementor' ),
						'h6'   => __( 'H6', 'social-elementor' ),
						'div'  => __( 'DIV', 'social-elementor' ),
						'span' => __( 'SPAN', 'social-elementor' ),
					),
					'default'   => 'div',
					'condition' => array(
						$this->get_control_id( 'show_meta' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'link_meta',
				array(
					'label'        => __( 'Link Meta', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'social-elementor' ),
					'label_off'    => __( 'No', 'social-elementor' ),
					'description'  => __( 'Uncheck this option, to make the meta clickable.', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => array(
						$this->get_control_id( 'show_meta' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'show_author',
				array(
					'label'        => __( 'Show Post Author', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'social-elementor' ),
					'label_off'    => __( 'Hide', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'separator'    => 'before',
					'condition'    => array(
						$this->get_control_id( 'show_meta' ) => 'yes',
					),
				)
			);

		if ( Social_Helper::is_elementor_updated() ) {

			$this->add_control(
				'new_show_author_icon',
				array(
					'label'            => __( 'Author Icon', 'social-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => $this->get_control_id( 'show_author_icon' ),
					'default'          => array(
						'value'   => 'fa fa-user',
						'library' => 'fa-solid',
					),
					'render_type'      => 'template',
					'condition'        => array(
						$this->get_control_id( 'show_meta' )   => 'yes',
						$this->get_control_id( 'show_author' ) => 'yes',
					),
				)
			);
		} else {

			$this->add_control(
				'show_author_icon',
				array(
					'type'      => Controls_Manager::ICON,
					'label'     => __( 'Author Icon', 'social-elementor' ),
					'default'   => 'fa fa-user',
					'condition' => array(
						$this->get_control_id( 'show_meta' )   => 'yes',
						$this->get_control_id( 'show_author' ) => 'yes',
					),
				)
			);
		}

			$this->add_control(
				'show_date',
				array(
					'label'        => __( 'Show Post Date', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'social-elementor' ),
					'label_off'    => __( 'Hide', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'separator'    => 'before',
					'condition'    => array(
						$this->get_control_id( 'show_meta' ) => 'yes',
					),
				)
			);

		if ( Social_Helper::is_elementor_updated() ) {

			$this->add_control(
				'new_show_date_icon',
				array(
					'type'             => Controls_Manager::ICONS,
					'label'            => __( 'Date Icon', 'social-elementor' ),
					'fa4compatibility' => $this->get_control_id( 'show_date_icon' ),
					'default'          => array(
						'value'   => 'fa fa-calendar',
						'library' => 'fa-solid',
					),
					'render_type'      => 'template',
					'condition'        => array(
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_date' ) => 'yes',
					),
				)
			);
		} else {

			$this->add_control(
				'show_date_icon',
				array(
					'type'      => Controls_Manager::ICON,
					'label'     => __( 'Date Icon', 'social-elementor' ),
					'default'   => 'fa fa-calendar',
					'condition' => array(
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_date' ) => 'yes',
					),
				)
			);
		}

			$this->add_control(
				'show_comments',
				array(
					'label'        => __( 'Show Post Comments', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'social-elementor' ),
					'label_off'    => __( 'Hide', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'separator'    => 'before',
					'condition'    => array(
						$this->get_control_id( 'show_meta' ) => 'yes',
					),
				)
			);

		if ( Social_Helper::is_elementor_updated() ) {

			$this->add_control(
				'new_show_comments_icon',
				array(
					'type'             => Controls_Manager::ICONS,
					'label'            => __( 'Comments Icon', 'social-elementor' ),
					'fa4compatibility' => $this->get_control_id( 'show_comments_icon' ),
					'default'          => array(
						'value'   => 'fa fa-comments',
						'library' => 'fa-solid',
					),
					'render_type'      => 'template',
					'condition'        => array(
						$this->get_control_id( 'show_meta' )     => 'yes',
						$this->get_control_id( 'show_comments' ) => 'yes',
					),
				)
			);
		} else {

			$this->add_control(
				'show_comments_icon',
				array(
					'type'      => Controls_Manager::ICON,
					'label'     => __( 'Comments Icon', 'social-elementor' ),
					'default'   => 'fa fa-comments',
					'condition' => array(
						$this->get_control_id( 'show_meta' )     => 'yes',
						$this->get_control_id( 'show_comments' ) => 'yes',
					),
				)
			);
		}

			$this->add_control(
				'show_categories',
				array(
					'label'        => __( 'Show Categories', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'social-elementor' ),
					'label_off'    => __( 'Hide', 'social-elementor' ),
					'return_value' => 'yes',
					'separator'    => 'before',
					'default'      => '',
					'condition'    => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'cat_meta_max_terms',
				array(
					'label'     => __( 'Max Categories', 'social-elementor' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 1,
					'condition' => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_categories' ) => 'yes',
					),
				)
			);

		if ( Social_Helper::is_elementor_updated() ) {

			$this->add_control(
				'new_cat_meta_show_term_icon',
				array(
					'label'            => __( 'Category Icon', 'social-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => $this->get_control_id( 'cat_meta_show_term_icon' ),
					'condition'        => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_categories' ) => 'yes',
					),
					'render_type'      => 'template',
				)
			);
		} else {

			$this->add_control(
				'cat_meta_show_term_icon',
				array(
					'type'      => Controls_Manager::ICON,
					'label'     => __( 'Category Icon', 'social-elementor' ),
					'condition' => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_categories' ) => 'yes',
					),
				)
			);
		}

			$this->add_control(
				'cat_meta_term_divider',
				array(
					'label'     => __( 'Category Divider', 'social-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '|',
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-terms-meta-cat a.social-blog-listing-term-link:not(:last-child):after' => 'content: "{{VALUE}}"; margin: 0 0.4em;',
					),
					'condition' => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_categories' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'show_tags',
				array(
					'label'        => __( 'Show Tags', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'social-elementor' ),
					'label_off'    => __( 'Hide', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'separator'    => 'before',
					'condition'    => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'tag_meta_max_terms',
				array(
					'label'     => __( 'Max Tags', 'social-elementor' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 1,
					'condition' => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_tags' ) => 'yes',
					),
				)
			);

		if ( Social_Helper::is_elementor_updated() ) {

			$this->add_control(
				'new_tag_meta_show_term_icon',
				array(
					'label'            => __( 'Tag Icon', 'social-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => $this->get_control_id( 'tag_meta_show_term_icon' ),
					'condition'        => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_tags' ) => 'yes',
					),
					'render_type'      => 'template',
				)
			);
		} else {

			$this->add_control(
				'tag_meta_show_term_icon',
				array(
					'type'      => Controls_Manager::ICON,
					'label'     => __( 'Tag Icon', 'social-elementor' ),
					'condition' => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_tags' ) => 'yes',
					),
				)
			);
		}

			$this->add_control(
				'tag_meta_term_divider',
				array(
					'label'     => __( 'Tag Divider', 'social-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '|',
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-terms-meta-tag a.social-blog-listing-term-link:not(:last-child):after' => 'content: "{{VALUE}}"; margin: 0 0.4em;',
					),
					'condition' => array(
						'post_type_filter' => 'post',
						$this->get_control_id( 'show_meta' ) => 'yes',
						$this->get_control_id( 'show_tags' ) => 'yes',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register Posts Excerpt Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_content_excerpt_controls() {

		$this->start_controls_section(
			'section_excerpt_field',
			array(
				'label' => __( 'Excerpt', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'show_excerpt',
				array(
					'label'        => __( 'Short Excerpt', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'social-elementor' ),
					'label_off'    => __( 'Hide', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'excerpt_length',
				array(
					'label'     => __( 'Excerpt Length', 'social-elementor' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => apply_filters( 'social_blog_post_excerpt_length', 20 ),
					'condition' => array(
						$this->get_control_id( 'show_excerpt' ) => 'yes',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register Posts call to action Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_content_cta_controls() {

		$this->start_controls_section(
			'section_cta_field',
			array(
				'label' => __( 'Call To Action', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'show_cta',
				array(
					'label'        => __( 'Call To Action', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'social-elementor' ),
					'label_off'    => __( 'Hide', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'cta_new_tab',
				array(
					'label'        => __( 'Open in New Tab', 'social-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'social-elementor' ),
					'label_off'    => __( 'No', 'social-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => array(
						$this->get_control_id( 'show_cta' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'cta_text',
				array(
					'label'     => __( 'Text', 'social-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Read More', 'social-elementor' ),
					'condition' => array(
						$this->get_control_id( 'show_cta' ) => 'yes',
					),
				)
			);

		if ( Social_Helper::is_elementor_updated() ) {

			$this->add_control(
				'new_cta_icon',
				array(
					'label'            => __( 'Icon', 'social-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => $this->get_control_id( 'cta_icon' ),
					'condition'        => array(
						$this->get_control_id( 'show_cta' ) => 'yes',
					),
					'render_type'      => 'template',
				)
			);
		} else {

			$this->add_control(
				'cta_icon',
				array(
					'label'     => __( 'Icon', 'social-elementor' ),
					'type'      => Controls_Manager::ICON,
					'default'   => 'fa fa-angle-double-right',
					'condition' => array(
						$this->get_control_id( 'show_cta' ) => 'yes',
					),
				)
			);
		}

			$this->add_control(
				'cta_icon_align',
				array(
					'label'     => __( 'Icon Position', 'social-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'left',
					'options'   => array(
						'left'  => __( 'Before', 'social-elementor' ),
						'right' => __( 'After', 'social-elementor' ),
					),
					'condition' => array(
						$this->get_control_id( 'show_cta' ) => 'yes',
					),
				)
			);

			$this->add_control(
				'cta_icon_indent',
				array(
					'label'     => __( 'Icon Spacing', 'social-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'max' => 50,
						),
					),
					'condition' => array(
						$this->get_control_id( 'show_cta' ) => 'yes',
					),
					'selectors' => array(
						'{{WRAPPER}} .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Style Tab
	 */

	/**
	 * Register Style Layout Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_style_layout_controls() {

		$this->start_controls_section(
			'section_design_layout',
			array(
				'label' => __( 'Layout', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => __( 'Columns Gap', 'social-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-grid-layout .social-blog-post-wrapper' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .social-blog-post-grid-layout .social-blog-post-grid-inner' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				),
				'condition' => array(
					$this->get_control_id( 'slides_to_show' ) => array( 2, 3, 4, 5, 6, 7, 8 ),
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => __( 'Rows Gap', 'social-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-grid-layout .social-blog-post-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'post_structure' ) => array( 'normal', 'featured', 'masonry' ),
				),
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'       => __( 'Alignment', 'social-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'social-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'social-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'social-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .social-blog-post-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Blog Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_style_blog_controls() {

		$this->start_controls_section(
			'section_design_blog',
			array(
				'label' => __( 'Blog', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'blog_bg_color',
				array(
					'label'     => __( 'Background Color', 'social-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#f4f9f9',
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-bg-wrapper' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'blog_padding',
				array(
					'label'      => __( 'Padding', 'social-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'default'    => array(
						'top'    => '20',
						'bottom' => '20',
						'right'  => '20',
						'left'   => '20',
						'unit'   => 'px',
					),
					'selectors'  => array(
						'{{WRAPPER}} .social-blog-post-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Style Tab
	 */
	/**
	 * Register Style Title Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_style_title_controls() {

		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => __( 'Title', 'social-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3a3a3a',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-title, {{WRAPPER}} .social-blog-post-title a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => __( 'Hover Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-title:hover, {{WRAPPER}} .social-blog-post-title a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}.social-blog-post-link-complete-yes .social-blog-post-complete-box-overlay:hover + .social-blog-post-inner-wrapper .social-blog-post-title a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .social-blog-post-title',
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_spacing',
			array(
				'label'     => __( 'Bottom Spacing', 'social-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Meta Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_style_meta_controls() {

		$this->start_controls_section(
			'section_meta_style',
			array(
				'label'     => __( 'Meta', 'social-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => __( 'Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#adadad',
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-meta-data' => 'color: {{VALUE}};',
					'{{WRAPPER}} .social-blog-post-meta-data svg' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_link_color',
			array(
				'label'     => __( 'Link Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-meta-data a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-meta-data a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}.social-blog-post-link-complete-yes .social-blog-post-complete-box-overlay:hover + .social-blog-post-inner-wrapper .social-blog-post-meta-data a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .social-blog-post-meta-data span',
			)
		);

		$this->add_control(
			'meta_spacing',
			array(
				'label'     => __( 'Bottom Spacing', 'social-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-meta-data' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'intermeta_spacing',
			array(
				'label'     => __( 'Inter Meta Spacing', 'social-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-meta-data span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .social-blog-post-meta-data span:last-child' => 'margin-right: 0;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Taxonomy Badge Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_style_term_controls() {

		$this->start_controls_section(
			'section_term_style',
			array(
				'label'     => __( 'Taxonomy Badge', 'social-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'terms_position' ) => array( 'media', 'above_content' ),
				),
			)
		);

			$this->add_control(
				'term_padding',
				array(
					'label'      => __( 'Padding', 'social-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'default'    => array(
						'top'    => '5',
						'bottom' => '5',
						'left'   => '10',
						'right'  => '10',
						'unit'   => 'px',
					),
					'selectors'  => array(
						'{{WRAPPER}} .social-blog-post-terms' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						$this->get_control_id( 'terms_position' ) => array( 'media', 'above_content' ),
					),
				)
			);

			$this->add_control(
				'term_border_radius',
				array(
					'label'      => __( 'Border Radius', 'social-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .social-blog-post-terms' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						$this->get_control_id( 'terms_position' ) => array( 'media', 'above_content' ),
					),
				)
			);

			$this->add_control(
				'term_alignment',
				array(
					'label'       => __( 'Alignment', 'social-elementor' ),
					'type'        => Controls_Manager::CHOOSE,
					'label_block' => false,
					'options'     => array(
						'left'   => array(
							'title' => __( 'Left', 'social-elementor' ),
							'icon'  => 'fa fa-align-left',
						),
						'center' => array(
							'title' => __( 'Center', 'social-elementor' ),
							'icon'  => 'fa fa-align-center',
						),
						'right'  => array(
							'title' => __( 'Right', 'social-elementor' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .social-blog-post-terms-wrap' => 'text-align: {{VALUE}};',
					),
					'condition'   => array(
						$this->get_control_id( 'terms_position' ) => 'media',
						$this->get_control_id( 'image_position' ) => 'background',
					),
				)
			);

			$this->add_control(
				'term_alignment_media',
				array(
					'label'       => __( 'Alignment', 'social-elementor' ),
					'type'        => Controls_Manager::CHOOSE,
					'label_block' => false,
					'options'     => array(
						'left'  => array(
							'title' => __( 'Left', 'social-elementor' ),
							'icon'  => 'fa fa-align-left',
						),
						'right' => array(
							'title' => __( 'Right', 'social-elementor' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'default'     => 'left',
					'selectors'   => array(
						'{{WRAPPER}} .social-blog-post-terms' => 'right:auto; left:auto; {{VALUE}} :0;',
					),
					'condition'   => array(
						$this->get_control_id( 'terms_position' )  => 'media',
						$this->get_control_id( 'image_position' ) => 'top',
					),
				)
			);

			$this->add_control(
				'term_color',
				array(
					'label'     => __( 'Color', 'social-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => array(
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					),
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-terms' => 'color: {{VALUE}};',
					),
					'condition' => array(
						$this->get_control_id( 'terms_position' ) => array( 'media', 'above_content' ),
					),
				)
			);

			$this->add_control(
				'term_hover_color',
				array(
					'label'     => __( 'Hover Color', 'social-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => array(
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					),
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-terms a:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}}.social-blog-post-link-complete-yes .social-blog-post-complete-box-overlay:hover + .social-blog-post-inner-wrapper .social-blog-post-terms a' => 'color: {{VALUE}};',
					),
					'condition' => array(
						$this->get_control_id( 'terms_position' ) => array( 'media', 'above_content' ),
					),
				)
			);

			$this->add_control(
				'term_bg_color',
				array(
					'label'     => __( 'Background Color', 'social-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#e4e4e4',
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-terms' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'term_typography',
					'scheme'    => Scheme_Typography::TYPOGRAPHY_2,
					'selector'  => '{{WRAPPER}} .social-blog-post-terms',
					'condition' => array(
						$this->get_control_id( 'terms_position' ) => array( 'media', 'above_content' ),
					),
				)
			);

			$this->add_control(
				'term_spacing',
				array(
					'label'     => __( 'Bottom Spacing', 'social-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'max' => 100,
						),
					),
					'default'   => array(
						'size' => 20,
						'unit' => 'px',
					),
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-terms-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						$this->get_control_id( 'terms_position' ) => array( 'media', 'above_content' ),
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register Style Excerpt Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_style_excerpt_controls() {

		$this->start_controls_section(
			'section_excerpt_style',
			array(
				'label'     => __( 'Excerpt', 'social-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => __( 'Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-excerpt' => 'color: {{VALUE}};',
				),
				'default'   => '#5b5b5b',
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'excerpt_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'selector'  => '{{WRAPPER}} .social-blog-post-excerpt',
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_spacing',
			array(
				'label'     => __( 'Bottom Spacing', 'social-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style CTA Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_style_cta_controls() {

		$this->start_controls_section(
			'section_cta_style',
			array(
				'label'     => __( 'Call To Action', 'social-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_cta' ) => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'cta_tabs_style' );

			$this->start_controls_tab(
				'cta_normal',
				array(
					'label'     => __( 'Normal', 'social-elementor' ),
					'condition' => array(
						$this->get_control_id( 'show_cta' ) => 'yes',
					),
				)
			);

				$this->add_control(
					'cta_color',
					array(
						'label'     => __( 'Text Color', 'social-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'scheme'    => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						),
						'selectors' => array(
							'{{WRAPPER}} a.social-blog-post-read-more-btn' => 'color: {{VALUE}};',
						),
						'condition' => array(
							$this->get_control_id( 'show_cta' ) => 'yes',
						),
					)
				);

				$this->add_control(
					'cta_background_color',
					array(
						'label'     => __( 'Background Color', 'social-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#8141bb',
						'selectors' => array(
							'{{WRAPPER}} a.social-blog-post-read-more-btn' => 'background-color: {{VALUE}};',
						),
						'scheme'    => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						),
						'condition' => array(
							$this->get_control_id( 'show_cta' ) => 'yes',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'      => 'cta_border',
						'label'     => __( 'Border', 'social-elementor' ),
						'selector'  => '{{WRAPPER}} a.social-blog-post-read-more-btn',
						'condition' => array(
							$this->get_control_id( 'show_cta' ) => 'yes',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'cta_hover',
				array(
					'label'     => __( 'Hover', 'social-elementor' ),
					'condition' => array(
						$this->get_control_id( 'show_cta' ) => 'yes',
					),
				)
			);

				$this->add_control(
					'cta_hover_color',
					array(
						'label'     => __( 'Text Hover Color', 'social-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} a.social-blog-post-read-more-btn:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}}.social-blog-post-link-complete-yes .social-blog-post-complete-box-overlay:hover + .social-blog-post-inner-wrapper a.social-blog-post-read-more-btn' => 'color: {{VALUE}};',
						),
						'condition' => array(
							$this->get_control_id( 'show_cta' ) => 'yes',
						),
					)
				);

				$this->add_control(
					'cta_background_hover_color',
					array(
						'label'     => __( 'Background Hover Color', 'social-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#6912ba',
						'selectors' => array(
							'{{WRAPPER}} a.social-blog-post-read-more-btn:hover' => 'background-color: {{VALUE}};',
							'{{WRAPPER}}.social-blog-post-link-complete-yes .social-blog-post-complete-box-overlay:hover + .social-blog-post-inner-wrapper a.social-blog-post-read-more-btn' => 'background-color: {{VALUE}};',
						),
						'condition' => array(
							$this->get_control_id( 'show_cta' ) => 'yes',
						),
					)
				);

				$this->add_control(
					'cta_hover_border_color',
					array(
						'label'     => __( 'Border Hover Color', 'social-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} a.social-blog-post-read-more-btn:hover' => 'border-color: {{VALUE}};',
							'{{WRAPPER}}.social-blog-post-link-complete-yes .social-blog-post-complete-box-overlay:hover + .social-blog-post-inner-wrapper a.social-blog-post-read-more-btn' => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							$this->get_control_id( 'show_cta' ) => 'yes',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'cta_border_radius',
			array(
				'label'      => __( 'Border Radius', 'social-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} a.social-blog-post-read-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => 4,
					'bottom' => 4,
					'left'   => 4,
					'right'  => 4,
					'unit'   => 'px',
				),
				'separator'  => 'before',
				'condition'  => array(
					$this->get_control_id( 'show_cta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'cta_padding',
			array(
				'label'      => __( 'Padding', 'social-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} a.social-blog-post-read-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => 12,
					'bottom' => 12,
					'left'   => 12,
					'right'  => 12,
					'unit'   => 'px',
				),
				'condition'  => array(
					$this->get_control_id( 'show_cta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'cta_full_width',
			array(
				'label'        => __( 'Full Width', 'social-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'social-elementor' ),
				'label_off'    => __( 'No', 'social-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'social-blog-post-cta-fullwidth-',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cta_typography',
				'selector'  => '{{WRAPPER}} a.social-blog-post-read-more-btn',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => array(
					$this->get_control_id( 'show_cta' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Navigation Controls.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_style_navigation_controls() {

		$this->start_controls_section(
			'section_style_navigation',
			array(
				'label'     => __( 'Navigation', 'social-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'navigation' ) => array( 'arrows', 'dots', 'both' ),
					$this->get_control_id( 'post_structure' ) => 'carousel',
				),
			)
		);

			$this->add_control(
				'heading_style_arrows',
				array(
					'label'     => __( 'Arrows', 'social-elementor' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
					),
				)
			);

			$this->add_control(
				'arrows_position',
				array(
					'label'        => __( 'Position', 'social-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'outside',
					'options'      => array(
						'inside'  => __( 'Inside', 'social-elementor' ),
						'outside' => __( 'Outside', 'social-elementor' ),
					),
					'prefix_class' => 'social-blog-post-arrow-',
					'condition'    => array(
						$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
					),
				)
			);

			$this->add_control(
				'arrows_size',
				array(
					'label'     => __( 'Arrows Size', 'social-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 20,
							'max' => 60,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-prev i, {{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-next i' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
					),
				)
			);

			$this->start_controls_tabs( 'arrow_tabs_style' );
				$this->start_controls_tab(
					'arrow_style_normal',
					array(
						'label'     => __( 'Normal', 'social-elementor' ),
						'condition' => array(
							$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
						),
					)
				);
					$this->add_control(
						'arrows_color',
						array(
							'label'     => __( 'Arrows Color', 'social-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-prev:before, {{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-next:before' => 'color: {{VALUE}};',
								'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-arrow' => 'border-color: {{VALUE}}; border-style: solid;',
								'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-arrow i' => 'color: {{VALUE}};',
							),
							'scheme'    => array(
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							),
							'condition' => array(
								$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
							),
						)
					);
					$this->add_control(
						'arrows_bg_color',
						array(
							'label'     => __( 'Background Color', 'social-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-arrow' => 'background-color: {{VALUE}};',
							),
							'condition' => array(
								$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
							),
						)
					);
				$this->end_controls_tab();

				$this->start_controls_tab(
					'arrow_style_hover',
					array(
						'label'     => __( 'Hover', 'social-elementor' ),
						'condition' => array(
							$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
						),
					)
				);
					$this->add_control(
						'arrows_hover_color',
						array(
							'label'     => __( 'Hover Color', 'social-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-prev:before:hover, {{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-next:before:hover' => 'color: {{VALUE}};',
								'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-arrow:hover' => 'border-color: {{VALUE}}; border-style: solid;',
								'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-arrow:hover i' => 'color: {{VALUE}};',
							),
							'condition' => array(
								$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
							),
						)
					);
					$this->add_control(
						'arrows_hover_bg_color',
						array(
							'label'     => __( 'Background Hover Color', 'social-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-arrow:hover' => 'background-color: {{VALUE}};',
							),
							'condition' => array(
								$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
							),
						)
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_control(
				'arrows_border_size',
				array(
					'label'     => __( 'Arrows Border Size', 'social-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
						),
					),
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-arrow' => 'border-width: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
					),
				)
			);

			$this->add_control(
				'arrow_border_radius',
				array(
					'label'      => __( 'Border Radius', 'social-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( '%' ),
					'default'    => array(
						'top'    => '50',
						'bottom' => '50',
						'left'   => '50',
						'right'  => '50',
						'unit'   => '%',
					),
					'selectors'  => array(
						'{{WRAPPER}} .social-blog-post-grid-layout .slick-slider .slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						$this->get_control_id( 'navigation' ) => array( 'arrows', 'both' ),
					),
				)
			);

			$this->add_control(
				'heading_style_dots',
				array(
					'label'     => __( 'Dots', 'social-elementor' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						$this->get_control_id( 'navigation' ) => array( 'dots', 'both' ),
					),
				)
			);

			$this->add_control(
				'dots_size',
				array(
					'label'     => __( 'Dots Size', 'social-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 5,
							'max' => 15,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-grid-layout .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						$this->get_control_id( 'navigation' ) => array( 'dots', 'both' ),
					),
				)
			);

			$this->add_control(
				'dots_color',
				array(
					'label'     => __( 'Dots Color', 'social-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .social-blog-post-grid-layout .slick-dots li button:before' => 'color: {{VALUE}};',
					),
					'condition' => array(
						$this->get_control_id( 'navigation' ) => array( 'dots', 'both' ),
					),
				)
			);

		$this->end_controls_section();
	}
}
