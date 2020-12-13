<?php
/**
 * Social Elementor Module Base.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Base;

use Elementor\Element_Base;
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
	protected static $instances = array();

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
		$class_name = static::class_name();
		if ( empty( static::$instances[ $class_name ] ) ) {
			static::$instances[ $class_name ] = new static();
		}

		return static::$instances[ $class_name ];
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->reflection = new \ReflectionClass( $this );

		add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );
		add_action( 'elementor/frontend/before_render', array( $this, 'custom_widget_render_attributes' ) );
	}

	/**
	 * Update global widget attributes as per convinience & to make it easier.
	 *
	 * @param object $widget of Element_Base.
	 * @since x.x.x
	 */
	public function custom_widget_render_attributes( Element_Base $widget ) {
		if ( $widget->get_data( 'widgetType' ) === 'global' && method_exists( $widget, 'get_original_element_instance' ) ) {
			$original_instance = $widget->get_original_element_instance();
			if ( method_exists( $original_instance, 'get_html_wrapper_class' ) && strpos( $original_instance->get_data( 'widgetType' ), 'social-' ) !== false ) {
				$widget->add_render_attribute(
					'_wrapper',
					array(
						'class' => $original_instance->get_html_wrapper_class(),
					)
				);
			}
		}
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
		return array();
	}
}
