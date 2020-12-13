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
use Elementor\Group_Control_Box_Shadow;

use SocialElementor\Base\Common_Widget;
use SocialElementor\Modules\Blog\TemplateBlocks\Skin_Init;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Card
 */
class Skin_Card extends Skin_Base {

	/**
	 * Get Skin Slug.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_id() {
		return 'card';
	}

	/**
	 * Get Skin Title.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Card Skin', 'social-elementor' );
	}

	/**
	 * Register controls on given actions.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	protected function _register_controls_actions() {

		parent::_register_controls_actions();

		add_action( 'elementor/element/social-blog-posts/card_section_title_field/before_section_end', array( $this, 'register_update_title_controls' ) );

		add_action( 'elementor/element/social-blog-posts/card_section_general_field/before_section_end', array( $this, 'register_update_general_controls' ) );

		add_action( 'elementor/element/social-blog-posts/card_section_image_field/before_section_end', array( $this, 'register_update_image_controls' ) );

		add_action( 'elementor/element/social-blog-posts/card_section_design_blog/before_section_end', array( $this, 'register_blog_design_controls' ) );

		add_action( 'elementor/element/social-blog-posts/card_section_design_layout/before_section_end', array( $this, 'register_update_layout_controls' ) );
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
		$this->register_style_title_controls();
		$this->register_style_meta_controls();
		$this->register_style_excerpt_controls();
		$this->register_style_cta_controls();
		$this->register_style_navigation_controls();
	}

	/**
	 * Update General control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function register_update_general_controls() {

		$this->update_control(
			'post_structure',
			array(
				'default' => 'masonry',
			)
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
			array(
				'default' => 'top',
				'options' => array(
					'top'  => __( 'Top', 'social-elementor' ),
					'none' => __( 'None', 'social-elementor' ),
				),
			)
		);
		$this->remove_control( 'image_background_color' );
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
			array(
				'default'   => 'h4',
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);
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
			array(
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-wrapper' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .social-blog-post-separator-wrap' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_title',
			array(
				'label'     => __( 'Separator', 'social-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'card_separator_height',
			array(
				'label'      => __( 'Separator Width', 'social-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 1,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .social-blog-post-separator' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_separator_width',
			array(
				'label'      => __( 'Separator Length ( In Percentage )', 'social-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .social-blog-post-separator' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'separator_spacing',
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
					'{{WRAPPER}} .social-blog-post-separator' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_separator_color',
			array(
				'label'     => __( 'Separator Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8141bb',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-separator' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_alignment',
			array(
				'label'        => __( 'Separator Alignment', 'social-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => true,
				'options'      => array(
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
				'prefix_class' => 'social-blog-post-separator-',
			)
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
			'blog_bg_color',
			array(
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-content-wrapper' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .social-blog-post-content-wrapper',
			)
		);

		$this->add_control(
			'card_max_width',
			array(
				'label'      => __( 'Box Max Width', 'social-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'size' => 92,
					'unit' => '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 90,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .social-blog-post-content-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_lift_up',
			array(
				'label'      => __( 'Lift Up Box by', 'social-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 50,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 90,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .social-blog-post-inner-wrapper:not(.social-blog-post-no-image) .social-blog-post-content-wrapper' => 'margin-top: -{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_bottom_margin',
			array(
				'label'      => __( 'Box Bottom Spacing', 'social-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .social-blog-post-content-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .social-blog-post-inner-wrapper.social-blog-post-no-image' => 'padding-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wrap_blog_bg_color',
			array(
				'label'     => __( 'Wrap Background Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f4ff9f9',
				'selectors' => array(
					'{{WRAPPER}} .social-blog-post-inner-wrapper' => 'background-color: {{VALUE}};',
				),
			)
		);
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
