<?php
/**
 * Social Elementor Card Skin.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Blog\Skins;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

use SocialElementor\Base\Common_Widget;
use SocialElementor\Modules\Blog\TemplateBlocks\Skin_Init;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Event
 */
class Skin_Event extends Skin_Base {

	/**
	 * Get Skin Slug.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_id() {
		return 'event';
	}

	/**
	 * Get Skin Title.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Event Skin', 'social-elementor' );
	}

	/**
	 * Register Control Actions.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	protected function _register_controls_actions() {

		parent::_register_controls_actions();

		add_action( 'elementor/element/social-blog-posts/event_section_title_field/before_section_end', [ $this, 'register_update_title_controls' ] );

		add_action( 'elementor/element/social-blog-posts/event_section_design_layout/before_section_end', [ $this, 'register_update_layout_controls' ] );

		add_action( 'elementor/element/social-blog-posts/event_section_image_field/before_section_end', [ $this, 'register_update_image_controls' ] );

		add_action( 'elementor/element/social-blog-posts/event_section_featured_field/before_section_end', [ $this, 'register_update_meta_controls' ] );

		add_action( 'elementor/element/social-blog-posts/event_section_design_blog/before_section_end', [ $this, 'register_blog_design_controls' ] );

		add_action( 'elementor/element/social-blog-posts/event_section_meta_field/before_section_end', [ $this, 'register_meta_controls' ] );

		add_action( 'elementor/element/social-blog-posts/event_section_meta_style/before_section_end', [ $this, 'register_meta_style_controls' ] );

		add_action( 'elementor/element/social-blog-posts/event_section_general_field/before_section_end', [ $this, 'update_general_controls' ] );
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
		$this->register_content_excerpt_controls();
		$this->register_content_cta_controls();

		// Style Controls.
		$this->register_style_layout_controls();
		$this->register_style_blog_controls();
		$this->register_style_datebox_controls();
		$this->register_style_title_controls();
		$this->register_style_meta_controls();
		$this->register_style_excerpt_controls();
		$this->register_style_cta_controls();
		$this->register_style_navigation_controls();
	}

	/**
	 * Update General control.
	 *
	 * @since 1.7.1
	 * @access public
	 */
	public function update_general_controls() {

		$this->add_control(
			'equal_grid_height',
			[
				'label'        => __( 'Equal Posts Height', 'social-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'label_off'    => __( 'No', 'social-elementor' ),
				'label_on'     => __( 'Yes', 'social-elementor' ),
				'prefix_class' => 'social-blog-post-equal-height-',
				'condition'    => [
					$this->get_control_id( 'post_structure' ) => [ 'normal' ],
				],
			]
		);
	}

	/**
	 * Update Blog Design control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_meta_controls() {

		$this->update_control(
			'show_meta',
			[
				'default' => '',
			]
		);
	}

	/**
	 * Update Blog Design control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_meta_style_controls() {

		$this->update_control(
			'section_meta_style',
			[
				'condition' => [
					$this->get_control_id( 'show_meta' ) => 'yes',
				],
			]
		);
	}

	/**
	 * Update Blog Design control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_blog_design_controls() {

		$this->update_control(
			'blog_padding',
			[
				'label'   => __( 'Content Padding', 'social-elementor' ),
				'default' => [
					'top'    => '25',
					'bottom' => '30',
					'right'  => '30',
					'left'   => '30',
					'unit'   => 'px',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'event_box_shadow',
				'selector' => '{{WRAPPER}} .social-blog-post-bg-wrapper',
			]
		);
	}

	/**
	 * Update Meta control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_update_meta_controls() {

		$this->update_control(
			'_f_meta',
			[
				'condition' => [
					$this->get_control_id( 'show_meta' ) => 'yes',
				],
			]
		);
	}

	/**
	 * Update Image control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_update_image_controls() {

		$this->update_control(
			'image_position',
			[
				'default' => 'top',
				'options' => array(
					'top'  => __( 'Top', 'social-elementor' ),
					'none' => __( 'None', 'social-elementor' ),
				),
			]
		);
		$this->remove_control( 'image_background_color' );
	}

	/**
	 * Update Layout control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_update_layout_controls() {

		$this->update_control(
			'alignment',
			[
				'default' => 'center',
			]
		);
	}

	/**
	 * Update Title control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_update_title_controls() {

		$this->update_control(
			'title_tag',
			[
				'default'   => 'h4',
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);
	}

	/**
	 * Register Datebox control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_style_datebox_controls() {

		$this->start_controls_section(
			'section_datebox_style',
			[
				'label' => __( 'Date Box', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'datebox_size',
				[
					'label'     => __( 'Date Box Size', 'social-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 50,
							'max' => 100,
						],
					],
					'default'   => [
						'size' => 60,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .social-blog-post-datebox:not(.social-blog-post-no-image)' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; margin-top: calc(-{{SIZE}}{{UNIT}}/2);',
						'{{WRAPPER}} .social-blog-post-datebox.social-blog-post-no-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; margin-top: {{event_blog_padding.top}}{{event_blog_padding.unit}};',
					],
				]
			);

			$this->add_control(
				'datebox_color',
				[
					'label'     => __( 'Color', 'social-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffffff',
					'selectors' => [
						'{{WRAPPER}} .social-blog-post-datebox' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'datebox_bg_color',
				[
					'label'     => __( 'Background Color', 'social-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .social-blog-post-datebox' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'datebox_typography',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
					'selector' => '{{WRAPPER}} .social-blog-post-datebox',
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Render Main HTML.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	public function render() {

		$settings = $this->parent->get_settings_for_display();

		$skin = Skin_Init::get_instance( $this->get_id() );

		echo $skin->render( $this->get_id(), $settings, $this->parent->get_id() );
	}

}
