<?php
/**
 * Social Elementor Module Manager.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor;

use SocialElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module_Manager.
 */
class Module_Manager {


	/**
	 * Member Variable
	 *
	 * @var modules.
	 */
	private $_modules = [];

	/**
	 * Register Modules.
	 *
	 * @since 1.0.0
	 */
	public function register_modules() {
		$all_modules = [
			/* Control */
			'query-post',
			/* Widgets */
			'blog',
		];

		foreach ( $all_modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );

			$class_name = str_replace( ' ', '', ucwords( $class_name ) );

			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			if ( $class_name::is_enable() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * Get Modules.
	 *
	 * @param string $module_name Module Name.
	 *
	 * @since 1.0.0
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name = null ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}
			return null;
		}

		return $this->_modules;
	}

	/**
	 * Required Files.
	 *
	 * @since 1.0.0
	 */
	private function require_files() {
		require( SOCIAL_ELEMENTOR_DIR . 'base/module-base.php' );
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->require_files();
		$this->register_modules();
	}
}
