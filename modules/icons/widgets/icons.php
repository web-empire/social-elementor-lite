<?php
/**
 * Social Icons.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Icons\Widgets;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;

use SocialElementor\Base\Common_Widget;
use SocialElementor\Classes\Social_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Social Icons.
 */
class Icons extends Common_Widget {

	/**
	 * Retrieve Social Icons Widget name.
	 *
	 * @since x.x.x
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'Icons' );
	}

	/**
	 * Retrieve Social Icons Widget title.
	 *
	 * @since x.x.x
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Icons' );
	}

	/**
	 * Retrieve Social Icons Widget icon.
	 *
	 * @since x.x.x
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-social-icons';
	}

	/**
	 * Retrieve Widget Keywords.
	 *
	 * @since x.x.x
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'social', 'icons', 'facebook', 'twitter', 'google' );
	}

	/**
	 * Register Social Icons controls.
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore 

		$this->register_general_content_controls();
		$this->register_style_content_controls();
	}

	/**
	 * Register Social Icons General Controls.
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_icons_contents',
			array(
				'label' => __( 'Social Icons', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'se_social_icon',
			array(
				'label'                  => __( 'Icon', 'social-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'default'                => array(
					'value'   => 'fab fa-wordpress',
					'library' => 'fa-brands',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'svg' ),
				'recommended'            => array(
					'fa-brands' => array(
						'behance',
						'bitbucket',
						'codepen',
						'dribbble',
						'facebook',
						'flickr',
						'foursquare',
						'free-code-camp',
						'github',
						'gitlab',
						'globe',
						'google-plus',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'meetup',
						'mixcloud',
						'pinterest',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'spotify',
						'stack-overflow',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitter',
						'viber',
						'vimeo',
						'vk',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					),
				),
			)
		);

		$repeater->add_control(
			'se_social_icon_link',
			array(
				'label'       => __( 'Link', 'social-elementor' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url' => '#',
				),
				'placeholder' => __( 'https://example.com', 'social-elementor' ),
			)
		);

		$repeater->add_control(
			'se_social_icon_custom_text',
			array(
				'label'          => __( 'Custom Text', 'social-elementor' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Yes', 'social-elementor' ),
				'label_off'      => __( 'No', 'social-elementor' ),
				'return_value'   => 'yes',
				'style_transfer' => true,
				'separator'      => 'before',
			)
		);

		$repeater->add_control(
			'se_social_icon_name',
			array(
				'label'     => __( 'Text', 'social-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'se_social_icon_custom_text' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'customize',
			array(
				'label'          => __( 'Enable Custom Styles', 'social-elementor' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Yes', 'social-elementor' ),
				'label_off'      => __( 'No', 'social-elementor' ),
				'return_value'   => 'yes',
				'style_transfer' => true,
				'separator'      => 'before',
			)
		);

		$repeater->start_controls_tabs(
			'se_social_icon_colors',
			array(
				'condition' => array( 'customize' => 'yes' ),
			)
		);
		$repeater->start_controls_tab(
			'se_social_icon_normal',
			array(
				'label' => __( 'Normal', 'social-elementor' ),
			)
		);

		$repeater->add_control(
			'se_social_icon_color',
			array(
				'label'          => __( 'Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,

				'selectors'      => array(
					'{{WRAPPER}} .se-social-icons-wrapper > {{CURRENT_ITEM}}.se-social-icon' => 'color: {{VALUE}};',
				),
				'condition'      => array( 'customize' => 'yes' ),
				'style_transfer' => true,
			)
		);
		$repeater->add_control(
			'se_social_icon_bg_color',
			array(
				'label'          => __( 'Background Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,

				'selectors'      => array(
					'{{WRAPPER}} .se-social-icons-wrapper > {{CURRENT_ITEM}}.se-social-icon' => 'background-color: {{VALUE}};',
				),
				'condition'      => array( 'customize' => 'yes' ),
				'style_transfer' => true,
			)
		);

		$repeater->add_control(
			'se_social_icon_border_color',
			array(
				'label'          => __( 'Border Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'condition'      => array( 'customize' => 'yes' ),
				'style_transfer' => true,
				'selectors'      => array(
					'{{WRAPPER}} .se-social-icons-wrapper {{CURRENT_ITEM}}' => 'border-color: {{VALUE}};',
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->start_controls_tab(
			'se_social_icon_hover',
			array(
				'label' => __( 'Hover', 'social-elementor' ),
			)
		);

		$repeater->add_control(
			'se_social_icon_h_color',
			array(
				'label'          => __( 'Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => array(
					'{{WRAPPER}} .se-social-icons-wrapper > {{CURRENT_ITEM}}.se-social-icon:hover'     => 'color: {{VALUE}};',
				),
				'condition'      => array( 'customize' => 'yes' ),
				'style_transfer' => true,
			)
		);
		$repeater->add_control(
			'se_social_icon_h_bg_color',
			array(
				'label'          => __( 'Background Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => array(
					'{{WRAPPER}} .se-social-icons-wrapper > {{CURRENT_ITEM}}.se-social-icon:hover' => 'background-color: {{VALUE}};',
				),
				'condition'      => array( 'customize' => 'yes' ),
				'style_transfer' => true,
			)
		);
		$repeater->add_control(
			'social_icon_hover_border_color',
			array(
				'label'          => __( 'Border Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'condition'      => array( 'customize' => 'yes' ),
				'style_transfer' => true,
				'selectors'      => array(
					'{{WRAPPER}} .se-social-icons-wrapper {{CURRENT_ITEM}}.se-social-icon:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'se_social_icon_total',
			array(
				'label'       => __( 'Icons', 'social-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'se_social_icon'      => array(
							'value'   => 'fab fa-facebook',
							'library' => 'fa-brands',
						),
						'se_social_icon_link' => array(
							'url' => '#',
						),
					),
					array(
						'se_social_icon'      => array(
							'value'   => 'fab fa-twitter',
							'library' => 'fa-brands',
						),
						'se_social_icon_link' => array(
							'url' => '#',
						),
					),
					array(
						'se_social_icon'      => array(
							'value'   => 'fab fa-github',
							'library' => 'fa-brands',
						),
						'se_social_icon_link' => array(
							'url' => '#',
						),
					),
				),
				'title_field' => '<# print(elementor.helpers.getSocialNetworkNameFromIcon( se_social_icon ) || se_social_icon_name); #>',
			)
		);

		$this->add_control(
			'se_social_icon_need_sep',
			array(
				'label'        => __( 'Show Separator', 'social-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'se_social_icon_sep_type',
			array(
				'label'        => __( 'Type', 'social-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'pipe'   => __( 'Pipe', 'social-elementor' ),
					'custom' => __( 'Custom', 'social-elementor' ),
				),
				'default'      => 'pipe',
				'condition'    => array(
					'se_social_icon_need_sep' => 'yes',
				),
				'prefix_class' => 'se-social-separator--',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'se_social_icon_default_sep',
			array(
				'label'       => __( 'Pipe Size', 'social-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'condition'   => array(
					'se_social_icon_need_sep' => 'yes',
					'se_social_icon_sep_type' => 'pipe',
				),
				'size_units'  => array( 'px', 'em' ),
				'selectors'   => array(
					'{{WRAPPER}}.se-social-separator--pipe .se-social-icon-sep' => 'width: {{SIZE}}{{UNIT}} !important;',
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'se_social_icons_sep_color',
			array(
				'label'     => __( 'Pipe Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'se_social_icon_need_sep' => 'yes',
					'se_social_icon_sep_type' => 'pipe',
				),
				'selectors' => array(
					'{{WRAPPER}}.se-social-separator--pipe .se-social-icon-sep' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'custom_separator',
			array(
				'label'       => __( 'Custom Character', 'social-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'se_social_icon_need_sep' => 'yes',
					'se_social_icon_sep_type' => 'custom',
				),
				'render_type' => 'template',
			)
		);

		$this->add_responsive_control(
			'se_social_icon_alignment',
			array(
				'label'       => __( 'Alignment', 'social-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'social-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'social-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'social-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
				'separator'   => 'before',
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'se_social_icon_is_sticky',
			array(
				'label'        => __( 'Enable Sticky', 'social-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Social Icons Style Controls.
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function register_style_content_controls() {

		$this->start_controls_section(
			'se_social_icons_common_style',
			array(
				'label' => __( 'General', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'se_social_icons_colors_tab' );

		$this->start_controls_tab(
			'label_normal_social_color',
			array(
				'label' => __( 'Normal', 'social-elementor' ),
			)
		);

		$this->add_control(
			'se_social_icons_color',
			array(
				'label'          => __( 'Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => array(
					'{{WRAPPER}} .se-social-icons-wrapper > .se-social-icon'       => 'color: {{VALUE}};',
					'{{WRAPPER}}.se-social-separator--pipe .se-social-icon-sep'   => 'background: {{VALUE}};',
					'{{WRAPPER}}.se-social-separator--custom .se-social-icon-sep'   => 'color: {{VALUE}};',
				),
				'style_transfer' => true,
			)
		);
		$this->add_control(
			'se_social_icons_bg_color',
			array(
				'label'          => __( 'Background Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,

				'selectors'      => array(
					'{{WRAPPER}} .se-social-icons-wrapper .se-social-icon' => 'background-color: {{VALUE}};',
				),
				'style_transfer' => true,
			)
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'se_social_icons_hover_tab',
			array(
				'label' => __( 'Hover', 'social-elementor' ),
			)
		);

		$this->add_control(
			'social_icons_h_color',
			array(
				'label'          => __( 'Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => array(
					'{{WRAPPER}} .se-social-icons-wrapper > .se-social-icon:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}}.se-social-separator--pipe .se-social-icon-sep'       => 'background: {{VALUE}};',
					'{{WRAPPER}}.se-social-separator--custom .se-social-icon-sep'       => 'color: {{VALUE}};',
				),
				'style_transfer' => true,
			)
		);
		$this->add_control(
			'social_icons_h_bg_color',
			array(
				'label'          => __( 'Background Color', 'social-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => array(
					'{{WRAPPER}} .se-social-icon:hover' => 'background-color: {{VALUE}};',
				),
				'style_transfer' => true,
			)
		);

		$this->add_control(
			'social_icon_global_h_border_color',
			array(
				'label'     => __( 'Border Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .se-social-icons-wrapper .se-social-icon:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'social_icon_padding_control',
			array(
				'label'          => __( 'Padding', 'social-elementor' ),
				'type'           => Controls_Manager::DIMENSIONS,
				'selectors'      => array(
					'{{WRAPPER}} .se-social-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'size_units'     => array( 'px', 'em' ),
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'min' => 20,
						'max' => 300,
					),
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
			)
		);

		$icon_spacing = is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};';

		$this->add_responsive_control(
			'social_icon_spacing_control',
			array(
				'label'     => __( 'Social Spacing', 'social-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .se-social-icon:not(:last-child)' => $icon_spacing,
					'{{WRAPPER}} .se-social-icon-sep' => $icon_spacing,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'social_icon_border',
				'selector'  => '{{WRAPPER}} .se-social-icons-wrapper .se-social-icon',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'social_icon_round_corners',
			array(
				'label'      => __( 'Border Radius', 'social-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .se-social-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'social_icons_box_shadow',
				'selector' => '{{WRAPPER}} .se-social-icon',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'social_icon_style_section',
			array(
				'label' => __( 'Icon', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'se_social_icon_size',
			array(
				'label'     => __( 'Size', 'social-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 20,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .se-social-icon.se-social-icon-trigger i'   => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'se_social_icon_padding',
			array(
				'label'          => __( 'Padding', 'social-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'selectors'      => array(
					'{{WRAPPER}} .se-social-icon.se-social-icon-trigger' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'default'        => array(
					'unit' => 'em',
				),
				'tablet_default' => array(
					'unit' => 'em',
				),
				'mobile_default' => array(
					'unit' => 'em',
				),
				'range'          => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
			)
		);

		$this->add_control(
			'social_icon_hover_animation',
			array(
				'label'   => __( 'Hover Animation', 'social-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'                   => __( 'None', 'social-elementor' ),
					'2d-transition'          => __( '2D Animation', 'social-elementor' ),
					'background-transition'  => __( 'Background Animation', 'social-elementor' ),
					'shadow-glow-transition' => __( 'Shadow and Glow Animation', 'social-elementor' ),
				),
				'default' => 'none',
			)
		);

		$this->add_control(
			'social_icon_hover_2d_animation',
			array(
				'label'     => __( 'Animation', 'social-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'hvr-grow'                   => __( 'Grow', 'social-elementor' ),
					'hvr-shrink'                 => __( 'Shrink', 'social-elementor' ),
					'hvr-pulse'                  => __( 'Pulse', 'social-elementor' ),
					'hvr-pulse-grow'             => __( 'Pulse Grow', 'social-elementor' ),
					'hvr-pulse-shrink'           => __( 'Pulse Shrink', 'social-elementor' ),
					'hvr-push'                   => __( 'Push', 'social-elementor' ),
					'hvr-pop'                    => __( 'Pop', 'social-elementor' ),
					'hvr-bounce-in'              => __( 'Bounce In', 'social-elementor' ),
					'hvr-bounce-out'             => __( 'Bounce Out', 'social-elementor' ),
					'hvr-rotate'                 => __( 'Rotate', 'social-elementor' ),
					'hvr-grow-rotate'            => __( 'Grow Rotate', 'social-elementor' ),
					'hvr-float'                  => __( 'Float', 'social-elementor' ),
					'hvr-sink'                   => __( 'Sink', 'social-elementor' ),
					'hvr-bob'                    => __( 'Bob', 'social-elementor' ),
					'hvr-hang'                   => __( 'Hang', 'social-elementor' ),
					'hvr-wobble-vertical'        => __( 'Wobble Vertical', 'social-elementor' ),
					'hvr-wobble-horizontal'      => __( 'Wobble Horizontal', 'social-elementor' ),
					'hvr-wobble-to-bottom-right' => __( 'Wobble To Bottom Right', 'social-elementor' ),
					'hvr-wobble-to-top-right'    => __( 'Wobble To Top Right', 'social-elementor' ),
					'hvr-buzz'                   => __( 'Buzz', 'social-elementor' ),
					'hvr-buzz-out'               => __( 'Buzz Out', 'social-elementor' ),
				),
				'default'   => 'hvr-grow',
				'condition' => array(
					'social_icon_hover_animation' => '2d-transition',
				),
			)
		);

		$this->add_control(
			'social_icon_hover_bg_animation',
			array(
				'label'     => __( 'Animation', 'social-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'hvr-fade'                   => __( 'Fade', 'social-elementor' ),
					'hvr-back-pulse'             => __( 'Back Pulse', 'social-elementor' ),
					'hvr-sweep-to-right'         => __( 'Sweep To Right', 'social-elementor' ),
					'hvr-sweep-to-left'          => __( 'Sweep To Left', 'social-elementor' ),
					'hvr-sweep-to-bottom'        => __( 'Sweep To Bottom', 'social-elementor' ),
					'hvr-sweep-to-top'           => __( 'Sweep To Top', 'social-elementor' ),
					'hvr-bounce-to-right'        => __( 'Bounce To Right', 'social-elementor' ),
					'hvr-bounce-to-left'         => __( 'Bounce To Left', 'social-elementor' ),
					'hvr-bounce-to-bottom'       => __( 'Bounce To Bottom', 'social-elementor' ),
					'hvr-bounce-to-top'          => __( 'Bounce To Top', 'social-elementor' ),
					'hvr-radial-out'             => __( 'Radial Out', 'social-elementor' ),
					'hvr-radial-in'              => __( 'Radial In', 'social-elementor' ),
					'hvr-rectangle-in'           => __( 'Rectangle In', 'social-elementor' ),
					'hvr-rectangle-out'          => __( 'Rectangle Out', 'social-elementor' ),
					'hvr-shutter-in-horizontal'  => __( 'Shutter In Horizontal', 'social-elementor' ),
					'hvr-shutter-out-horizontal' => __( 'Shutter Out Horizontal', 'social-elementor' ),
					'hvr-shutter-in-vertical'    => __( 'Shutter In Vertical', 'social-elementor' ),
					'hvr-shutter-out-vertical'   => __( 'Shutter Out Vertical', 'social-elementor' ),
				),
				'default'   => 'hvr-fade',
				'condition' => array(
					'social_icon_hover_animation' => 'background-transition',
				),
			)
		);

		$this->add_control(
			'social_icon_hover_glowing_shadow_animation',
			array(
				'label'     => __( 'Animation', 'social-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'hvr-glow'              => __( 'Glow', 'social-elementor' ),
					'hvr-shadow'            => __( 'Shadow', 'social-elementor' ),
					'hvr-grow-shadow'       => __( 'Grow Shadow', 'social-elementor' ),
					'hvr-box-shadow-outset' => __( 'Box Shadow Outset', 'social-elementor' ),
					'hvr-box-shadow-inset'  => __( 'Box Shadow Inset', 'social-elementor' ),
					'hvr-float-shadow'      => __( 'Float Shadow', 'social-elementor' ),
					'hvr-shadow-radial'     => __( 'Shadow Radial', 'social-elementor' ),
				),
				'default'   => 'hvr-glow',
				'condition' => array(
					'social_icon_hover_animation' => 'shadow-glow-transition',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'se_section_custom_label_style',
			array(
				'label' => __( 'Social Text', 'social-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'social_custom_title_typo',
				'label'    => __( 'Typography', 'social-elementor' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .se-social-icon-label',
			)
		);

		$this->add_control(
			'social_name_spacing',
			array(
				'label'     => __( 'Spacing', 'social-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .se-social-icon:not(.elementor-social-icon-label) .se-social-icon-label' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'se_social_icon_separator_section',
			array(
				'label'     => __( 'Separator', 'social-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'se_social_icon_need_sep' => 'yes',
				),
			)
		);

		$this->add_control(
			'se_social_icon_separator_color',
			array(
				'label'     => __( 'Color', 'social-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'se_social_icon_need_sep' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}}.se-social-separator--pipe .se-social-icon-sep' => 'background: {{VALUE}} !important;',
					'{{WRAPPER}}.se-social-separator--custom .se-social-icon-sep' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'se_social_separator_typography',
				'label'     => __( 'Typography', 'social-elementor' ),
				'selector'  => '{{WRAPPER}} .se-social-icon-sep',
				'condition' => array(
					'se_social_icon_need_sep' => 'yes',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render Info Box output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function render() {
		$html     = '';
		$settings = $this->get_settings_for_display();
		$node_id  = $this->get_id();
		ob_start();
		include 'template.php';
		$html = ob_get_clean();
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
