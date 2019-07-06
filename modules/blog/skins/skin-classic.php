<?php
/**
 * Social Elementor Grid Skin.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Blog\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use SocialElementor\Base\Common_Widget;
use SocialElementor\Modules\Blog\TemplateBlocks\Skin_Init;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Classic
 */
class Skin_Classic extends Skin_Base {

	/**
	 * Get Skin Slug.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_id() {

		return 'classic';
	}

	/**
	 * Get Skin Title.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_title() {

		return __( 'Classic Skin', 'social-elementor' );
	}

	/**
	 * Register Control Actions.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	protected function _register_controls_actions() {

		parent::_register_controls_actions();

		add_action( 'elementor/element/social-blog-posts/classic_section_design_blog/before_section_end', [ $this, 'update_blog_controls' ] );

		add_action( 'elementor/element/social-blog-posts/classic_section_general_field/before_section_end', [ $this, 'update_general_controls' ] );
	}

	/**
	 * Update Blog Design control.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function update_blog_controls() {

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'content_border',
				'selector' => '{{WRAPPER}} .social-blog-post-bg-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'classic_box_shadow',
				'selector' => '{{WRAPPER}} .social-blog-post-bg-wrapper',
			]
		);
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

