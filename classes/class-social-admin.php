<?php
/**
 * Social Elementor Admin.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Classes;

use SocialElementor\Classes\Social_Helper;

if ( ! class_exists( 'Social_Admin' ) ) {

	/**
	 * Class Social_Admin.
	 */
	final class Social_Admin {

		/**
		 * Calls on initialization
		 *
		 * @since 1.0.0
		 */
		public static function init() {

			self::initialize_ajax();
			self::initialise_plugin();
			add_action( 'after_setup_theme', __CLASS__ . '::init_hooks' );
			add_action( 'elementor/init', __CLASS__ . '::load_admin', 0 );
		}

		/**
		 * Defines all constants
		 *
		 * @since 1.0.0
		 */
		public static function load_admin() {
			add_action( 'elementor/editor/after_enqueue_styles', __CLASS__ . '::social_elementor_admin_enqueue_scripts' );
		}

		/**
		 * Enqueue admin scripts
		 *
		 * @since 1.0.0
		 * @param string $hook Current page hook.
		 * @access public
		 */
		public static function social_elementor_admin_enqueue_scripts( $hook ) {

			// Register styles.
			wp_register_style(
				'social-elementor-style',
				SOCIAL_ELEMENTOR_URL . 'editor-assets/css/style.css',
				[],
				SOCIAL_ELEMENTOR_VER
			);

			wp_enqueue_style( 'social-elementor-style' );

			$social_localize = apply_filters(
				'social_admin_js_localize',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				)
			);

			wp_localize_script( 'jquery', 'social_admin', $social_localize );
		}

		/**
		 * Adds the admin menu and enqueues CSS/JS if we are on
		 * the builder admin settings page.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static public function init_hooks() {
			if ( ! is_admin() ) {
				return;
			}

			// Add Social Elementor menu option to admin.
			add_action( 'network_admin_menu', __CLASS__ . '::menu' );
			add_action( 'admin_menu', __CLASS__ . '::menu' );

			add_action( 'social_render_admin_content', __CLASS__ . '::render_content' );

			// Enqueue admin scripts.
			if ( isset( $_REQUEST['page'] ) && SOCIAL_ELEMENTOR_SLUG == $_REQUEST['page'] ) {

				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );

				self::save_settings();
			}
		}

		/**
		 * Initialises the Plugin Name.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static public function initialise_plugin() {

			$name = 'Social & Blog Posts Addons for Elementor';

			$short_name = 'Social Elementor';

			define( 'SOCIAL_ELEMENTOR_PLUGIN_NAME', $name );

			define( 'SOCIAL_PLUGIN_SHORT_NAME', $short_name );
		}

		/**
		 * Renders the admin settings menu.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static public function menu() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			add_submenu_page(
				'options-general.php',
				SOCIAL_PLUGIN_SHORT_NAME,
				SOCIAL_PLUGIN_SHORT_NAME,
				'manage_options',
				SOCIAL_ELEMENTOR_SLUG,
				__CLASS__ . '::render'
			);
		}

		/**
		 * Renders the admin settings.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static public function render() {
			$action = ( isset( $_GET['action'] ) ) ? esc_attr( $_GET['action'] ) : '';
			$action = ( ! empty( $action ) && '' != $action ) ? $action : 'general';
			$action = str_replace( '_', '-', $action );

			// Enable header icon filter below.
			$social_icon = apply_filters( 'social_elementor_admin_header_logo', true );
			$social_elementor_visit_site_url = apply_filters( 'social_elementor_site_url', 'https://webempire.org.in/?utm_campaign=web-agency&utm_medium=website&utm_source=google' );
			$social_admin_header_wrapper_class = apply_filters( 'social_admin_header_wrapper_class', array( $action ) );

			include_once SOCIAL_ELEMENTOR_DIR . 'includes/admin/social-admin.php';
		}

		/**
		 * Renders the admin settings content.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static public function render_content() {

			$action = ( isset( $_GET['action'] ) ) ? esc_attr( $_GET['action'] ) : '';
			$action = ( ! empty( $action ) && '' != $action ) ? $action : 'general';
			$action = str_replace( '_', '-', $action );

			$social_admin_header_wrapper_class = apply_filters( 'social_admin_header_wrapper_class', array( $action ) );

			include_once SOCIAL_ELEMENTOR_DIR . 'includes/admin/social-' . $action . '.php';
		}

		/**
		 * Enqueues the needed CSS/JS for the builder's admin settings page.
		 *
		 * @since 1.0
		 */
		static public function styles_scripts() {

			// Styles.
			wp_enqueue_style( 'social-admin-settings', SOCIAL_ELEMENTOR_URL . 'admin/assets/admin-menu-settings.css', array(), SOCIAL_ELEMENTOR_VER );
			// Script.
			wp_enqueue_script( 'social-admin-settings', SOCIAL_ELEMENTOR_URL . 'admin/assets/admin-menu-settings.js', array( 'jquery', 'wp-util', 'updates' ), SOCIAL_ELEMENTOR_VER );

			$localize = array(
				'ajax_nonce'   => wp_create_nonce( 'social-widget-nonce' ),
				'activate'     => esc_html__( 'Activate', 'social-elementor' ),
				'deactivate'   => esc_html__( 'Deactivate', 'social-elementor' ),
			);

			wp_localize_script( 'social-admin-settings', 'social', apply_filters( 'social_js_localize', $localize ) );
		}

		/**
		 * Save All admin settings here
		 */
		static public function save_settings() {

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Let extensions hook into saving.
			do_action( 'social_admin_settings_save' );
		}

		/**
		 * Initialize Ajax
		 */
		static public function initialize_ajax() {
			// Ajax requests.
			add_action( 'wp_ajax_social_activate_widget', __CLASS__ . '::activate_widget' );
			add_action( 'wp_ajax_social_deactivate_widget', __CLASS__ . '::deactivate_widget' );

			add_action( 'wp_ajax_social_bulk_activate_widgets', __CLASS__ . '::bulk_activate_widgets' );
			add_action( 'wp_ajax_social_bulk_deactivate_widgets', __CLASS__ . '::bulk_deactivate_widgets' );
		}

		/**
		 * Activate module
		 */
		static public function activate_widget() {

			check_ajax_referer( 'social-widget-nonce', 'nonce' );

			$module_id             = sanitize_text_field( $_POST['module_id'] );
			$widgets               = Social_Helper::get_admin_settings_option( 'social_widgets', array() );
			$widgets[ $module_id ] = $module_id;
			$widgets               = array_map( 'esc_attr', $widgets );

			// Update widgets.
			Social_Helper::update_admin_settings_option( 'social_widgets', $widgets );

			echo $module_id;

			die();
		}

		/**
		 * Deactivate module
		 */
		static public function deactivate_widget() {

			check_ajax_referer( 'social-widget-nonce', 'nonce' );

			$module_id             = sanitize_text_field( $_POST['module_id'] );
			$widgets               = Social_Helper::get_admin_settings_option( 'social_widgets', array() );
			$widgets[ $module_id ] = 'disabled';
			$widgets               = array_map( 'esc_attr', $widgets );

			// Update widgets.
			Social_Helper::update_admin_settings_option( 'social_widgets', $widgets );

			echo $module_id;

			die();
		}

		/**
		 * Activate all module
		 */
		static public function bulk_activate_widgets() {

			check_ajax_referer( 'social-widget-nonce', 'nonce' );

			// Get all widgets.
			$all_widgets = Social_Helper::get_widget_list();
			$new_widgets = array();

			// Set all extension to enabled.
			foreach ( $all_widgets  as $slug => $value ) {
				$new_widgets[ $slug ] = $slug;
			}

			// Escape attrs.
			$new_widgets = array_map( 'esc_attr', $new_widgets );

			// Update new_extensions.
			Social_Helper::update_admin_settings_option( 'social_widgets', $new_widgets );

			echo 'success';

			die();
		}

		/**
		 * Deactivate all module
		 */
		static public function bulk_deactivate_widgets() {

			check_ajax_referer( 'social-widget-nonce', 'nonce' );

			// Get all extensions.
			$old_widgets = Social_Helper::get_widget_list();
			$new_widgets = array();

			// Set all extension to enabled.
			foreach ( $old_widgets as $slug => $value ) {
				$new_widgets[ $slug ] = 'disabled';
			}

			// Escape attrs.
			$new_widgets = array_map( 'esc_attr', $new_widgets );

			// Update new_extensions.
			Social_Helper::update_admin_settings_option( 'social_widgets', $new_widgets );

			echo 'success';

			die();
		}
	}

	Social_Admin::init();

}

