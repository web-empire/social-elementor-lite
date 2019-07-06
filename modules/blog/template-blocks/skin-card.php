<?php
/**
 * Social Elementor Card Skin.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Blog\TemplateBlocks;

use SocialElementor\Modules\Blog\TemplateBlocks\Skin_Style;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Card
 */
class Skin_Card extends Skin_Style {


	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Render Separator HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_separator() {

		$settings = self::$settings;

		do_action( 'social_elementor_single_post/skin_card/before_separator', get_the_ID(), $settings );

		printf( '<div class="social-blog-post-separator"></div>' );

		do_action( 'social_elementor_single_post/skin_card/after_separator', get_the_ID(), $settings );
	}

	/**
	 * Get Classes array for outer wrapper class.
	 *
	 * Returns the array for outer wrapper class.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_outer_wrapper_classes() {

		$classes = [
			'social-blog-post-grid-layout',
			'social-blog-posts',
		];

		return $classes;
	}
}

