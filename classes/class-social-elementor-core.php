<?php
/**
 * Social Elementor Core Plugin.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor;

use SocialElementor\Classes\Social_Helper;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Social_Elementor_Core.
 *
 * @package SOCIAL_ELEMENTOR
 */
class Social_Elementor_Core {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Member Variable
	 *
	 * @var Modules Manager
	 */
	public $modules_manager;

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
	 * Constructor
	 */
	public function __construct() {

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->includes();

		$this->setup_actions_filters();
	}

	/**
	 * AutoLoad
	 *
	 * @since 1.0.0
	 * @param string $class class.
	 */
	public function autoload( $class ) {

		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$class_to_load = $class;

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = SOCIAL_ELEMENTOR_DIR . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}
	}

	/**
	 * Includes.
	 *
	 * @since 1.0.0
	 */
	private function includes() {

		require SOCIAL_ELEMENTOR_DIR . 'classes/class-social-admin.php';
		require SOCIAL_ELEMENTOR_DIR . 'includes/manager/modules-manager.php';
	}

	/**
	 * Setup Actions Filters.
	 *
	 * @since 1.0.0
	 */
	private function setup_actions_filters() {

		add_action( 'elementor/init', array( $this, 'elementor_init' ) );

		add_action( 'elementor/elements/categories_registered', array( $this, 'widget_category' ) );

		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_widget_scripts' ) );

		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_widget_styles' ) );

		add_filter( 'body_class', array( $this, 'body_classes' ), 10, 1 );
	}

	/**
	 * Add Body Classes
	 *
	 * @param  array $classes Body Class Array.
	 * @return array
	 */
	function body_classes( $classes ) {

		$classes[] = 'social-elementor-lite-' . SOCIAL_ELEMENTOR_VER . '';
		return $classes;
	}

	/**
	 * Elementor Init.
	 *
	 * @since 1.0.0
	 */
	public function elementor_init() {

		$this->modules_manager = new Module_Manager();

		$this->init_category();

		do_action( 'social_elementor/init' );
	}

	/**
	 * Sections init
	 *
	 * @since 1.0.0
	 * @param object $this_cat class.
	 */
	public function widget_category( $this_cat ) {

		$this_cat->add_category(
			'social-elements',
			[
				'title' => SOCIAL_ELEMENTOR_CATEGORY,
				'icon'  => 'eicon-font',
			]
		);

		return $this_cat;
	}


	/**
	 * Sections init
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function init_category() {

		\Elementor\Plugin::instance()->elements_manager->add_category(
			'social-elements',
			[
				'title' => SOCIAL_ELEMENTOR_CATEGORY,
			],
			1
		);
	}

	/**
	 * Register module required js on elementor's action.
	 *
	 * @since 1.0.0
	 */
	function register_widget_scripts() {

		$js_files    = Social_Helper::get_widget_script();

		wp_localize_script(
			'jquery',
			'social_elementor_script',
			array(
				'post_loader'        => SOCIAL_ELEMENTOR_URL . 'assets/img/post-loader.gif',
				'url'                => admin_url( 'admin-ajax.php' ),
				'search_str'         => esc_html__( 'Search:', 'social-elementor' ),
			)
		);

		foreach ( $js_files as $handle => $data ) {

			wp_register_script( $handle, SOCIAL_ELEMENTOR_URL . $data['path'], $data['dep'], SOCIAL_ELEMENTOR_VER, $data['in_footer'] );
		}

		$social_elementor_localize = apply_filters(
			'social_js_localize',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);

		wp_localize_script( 'jquery', 'social_elementor', $social_elementor_localize );
	}

	/**

	 * Enqueue module required styles.
	 *
	 * @since 1.0.0
	 */
	function enqueue_widget_styles() {

		$css_files = Social_Helper::get_widget_style();

		if ( ! empty( $css_files ) ) {
			foreach ( $css_files as $handle => $data ) {

				wp_register_style( $handle, SOCIAL_ELEMENTOR_URL . $data['path'], $data['dep'], SOCIAL_ELEMENTOR_VER );
				wp_enqueue_style( $handle );
			}
		}
	}
}

/**
 *  Prepare if class 'Social_Elementor_Core' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
Social_Elementor_Core::get_instance();
