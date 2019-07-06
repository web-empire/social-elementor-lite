<?php
/**
 * Social Elementor Skin Init.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Blog\TemplateBlocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Init
 */
class Skin_Init {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $skin_instance;

	/**
	 * Initiator
	 *
	 * @param string $style Skin.
	 */
	public static function get_instance( $style ) {

		$skin_class = 'SocialElementor\\Modules\\Blog\\TemplateBlocks\\Skin_' . ucfirst( $style );

		if ( class_exists( $skin_class ) ) {

			self::$skin_instance[ $style ] = new $skin_class( $style );
		}

		return self::$skin_instance[ $style ];
	}
}
