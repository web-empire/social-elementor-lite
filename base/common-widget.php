<?php
/**
 * Social Elementor Common Widget.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Base;

use Elementor\Widget_Base;
use SocialElementor\Classes\Social_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Common Widget
 *
 * @since 1.0.0
 */
abstract class Common_Widget extends Widget_Base {

	/**
	 * Get categories
	 *
	 * @since 1.0.0
	 */
	public function get_categories() {
		return [ 'social-elements' ];
	}

	/**
	 * Get widget slug
	 *
	 * @param string $slug Module slug.
	 * @since 1.0.0
	 */
	public function get_widget_slug( $slug = '' ) {
		return Social_Helper::get_widget_slug( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module slug.
	 * @since 1.0.0
	 */
	public function get_widget_title( $slug = '' ) {
		return Social_Helper::get_widget_title( $slug );
	}

	/**
	 * Get widget icon
	 *
	 * @param string $slug Module slug.
	 * @since 1.0.0
	 */
	public function get_widget_icon( $slug = '' ) {
		return Social_Helper::get_widget_icon( $slug );
	}

	/**
	 * Get widget keywords
	 *
	 * @param string $slug Module slug.
	 * @since 1.5.1
	 */
	public function get_widget_keywords( $slug = '' ) {
		return Social_Helper::get_widget_keywords( $slug );
	}
}
