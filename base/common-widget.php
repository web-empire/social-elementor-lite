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
		return array( 'social-elements' );
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

	/**
	 * Override from addon to add custom wrapper class.
	 *
	 * @return string
	 */
	protected function get_custom_wrapper_class() {
		return '';
	}

	/**
	 * Overriding default function to add custom classes as per functionality.
	 *
	 * @return string
	 * @since x.x.x
	 */
	public function get_html_wrapper_class() {
		$html_class  = parent::get_html_wrapper_class();
		$html_class .= ' social-addon';
		$html_class .= ' ' . $this->get_name();
		$html_class .= ' ' . $this->get_custom_wrapper_class();
		return rtrim( $html_class );
	}
}
