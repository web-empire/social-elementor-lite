<?php
/**
 * Social Elementor Module Base.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Base;

use SocialElementor\Classes\Social_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module Base
 *
 * @since 1.0.0
 */
abstract class Module_Base {

	/**
	 * Reflection
	 *
	 * @var reflection
	 */
	private $reflection;

	/**
	 * Reflection
	 *
	 * @var instances
	 */
	protected static $instances = [];

	/**
	 * Get Name
	 *
	 * @since 1.0.0
	 */
	abstract public function get_name();

	/**
	 * Class name to Call
	 *
	 * @since 1.0.0
	 */
	public static function class_name() {
		return get_called_class();
	}

	/**
	 * Check if this is a widget.
	 *
	 * @since 1.12.0
	 * @access public
	 *
	 * @return bool true|false.
	 */
	public function is_widget() {
		return true;
	}

	/**
	 * Class instance
	 *
	 * @since 1.0.0
	 *
	 * @return static
	 */
	public static function instance() {
		if ( empty( static::$instances[ static::class_name() ] ) ) {
			static::$instances[ static::class_name() ] = new static();
		}

		return static::$instances[ static::class_name() ];
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->reflection = new \ReflectionClass( $this );

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
	}

	/**
	 * Init Widgets
	 *
	 * @since 1.0.0
	 */
	public function init_widgets() {

		$widget_manager = \Elementor\Plugin::instance()->widgets_manager;

		foreach ( $this->get_widgets() as $widget ) {
			if ( Social_Helper::is_widget_active( $widget ) ) {
				$class_name = $this->reflection->getNamespaceName() . '\Widgets\\' . $widget;

				if ( $this->is_widget() ) {
					$widget_manager->register_widget_type( new $class_name() );
				}
			}
		}
	}

	/**
	 * Get Widgets
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_widgets() {
		return [];
	}
}
