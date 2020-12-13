<?php
/**
 * Social Elementor Helper.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use SocialElementor\Classes\Social_Config;

/**
 * Class Social_Helper.
 */
class Social_Helper {

	/**
	 * CSS files folder
	 *
	 * @var script_debug
	 */
	private static $script_debug = null;

	/**
	 * CSS files folder
	 *
	 * @var css_folder
	 */
	private static $css_folder = null;

	/**
	 * CSS Suffix
	 *
	 * @var css_suffix
	 */
	private static $css_suffix = null;

	/**
	 * RTL CSS Suffix
	 *
	 * @var rtl_css_suffix
	 */
	private static $rtl_css_suffix = null;

	/**
	 * JS files folder
	 *
	 * @var js_folder
	 */
	private static $js_folder = null;

	/**
	 * JS Suffix
	 *
	 * @var js_suffix
	 */
	private static $js_suffix = null;

	/**
	 * Widget Options
	 *
	 * @var widget_options
	 */
	private static $widget_options = null;

	/**
	 * Widget List
	 *
	 * @var widget_list
	 */
	private static $widget_list = null;

	/**
	 * Provide General settings array().
	 *
	 * @return array()
	 * @since 1.0.0
	 */
	static public function get_widget_list() {

		self::$widget_list = Social_Config::get_widget_list();

		return apply_filters( 'social_elementor_widget_list', self::$widget_list );
	}

	/**
	 * Check is script debug enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return string The CSS suffix.
	 */
	public static function is_script_debug() {

		if ( null === self::$script_debug ) {

			self::$script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		}

		return self::$script_debug;
	}

	/**
	 * Get CSS Folder.
	 *
	 * @since 1.0.0
	 *
	 * @return string The CSS folder.
	 */
	public static function get_css_folder() {

		if ( null === self::$css_folder ) {

			self::$css_folder = self::is_script_debug() ? 'css' : 'min-css';
		}

		return self::$css_folder;
	}

	/**
	 * Get CSS suffix.
	 *
	 * @since 1.0.0
	 *
	 * @return string The CSS suffix.
	 */
	public static function get_css_suffix() {

		if ( null === self::$css_suffix ) {

			self::$css_suffix = self::is_script_debug() ? '' : '.min';
		}

		return self::$css_suffix;
	}

	/**
	 * Get JS Folder.
	 *
	 * @since 1.0.0
	 *
	 * @return string The JS folder.
	 */
	public static function get_js_folder() {

		if ( null === self::$js_folder ) {

			self::$js_folder = self::is_script_debug() ? 'js' : 'min-js';
		}

		return self::$js_folder;
	}

	/**
	 * Get JS Suffix.
	 *
	 * @since 1.0.0
	 *
	 * @return string The JS suffix.
	 */
	public static function get_js_suffix() {

		if ( null === self::$js_suffix ) {

			self::$js_suffix = self::is_script_debug() ? '' : '.min';
		}

		return self::$js_suffix;
	}

	/**
	 *  Get link rel attribute
	 *
	 *  @param string $target Target attribute to the link.
	 *  @param int    $is_nofollow No follow yes/no.
	 *  @param int    $echo Return or echo the output.
	 *  @since 1.0.0
	 *  @return string
	 */
	public static function get_link_rel( $target, $is_nofollow = 0, $echo = 0 ) {

		$attr = '';
		if ( '_blank' === $target ) {
			$attr .= 'noopener';
		}

		if ( 1 === $is_nofollow ) {
			$attr .= ' nofollow';
		}

		if ( '' === $attr ) {
			return;
		}

		$attr = trim( $attr );
		if ( ! $echo ) {
			return 'rel="' . $attr . '"';
		}
		echo 'rel="' . $attr . '"';
	}

	/**
	 * Returns an option from the database for
	 * the admin settings page.
	 *
	 * @param  string  $key     The option key.
	 * @param  mixed   $default Option default value if option is not available.
	 * @param  boolean $network_override Whether to allow the network admin setting to be overridden on subsites.
	 * @return string           Return the option value
	 */
	public static function get_admin_settings_option( $key, $default = false, $network_override = false ) {

		// Get the site-wide option if we're in the network admin.
		if ( $network_override && is_multisite() ) {
			$value = get_site_option( $key, $default );
		} else {
			$value = get_option( $key, $default );
		}

		return $value;
	}

	/**
	 * Updates an option from the admin settings page.
	 *
	 * @param string $key       The option key.
	 * @param mixed  $value     The value to update.
	 * @param bool   $network   Whether to allow the network admin setting to be overridden on subsites.
	 * @return mixed
	 */
	static public function update_admin_settings_option( $key, $value, $network = false ) {

		// Update the site-wide option since we're in the network admin.
		if ( $network && is_multisite() ) {
			update_site_option( $key, $value );
		} else {
			update_option( $key, $value );
		}

	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.0.0
	 */
	static public function get_widget_slug( $slug = '' ) {

		$widget_list = self::get_widget_list();

		$widget_slug = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_slug = $widget_list[ $slug ]['slug'];
		}

		return apply_filters( 'social_widget_slug', $widget_slug );
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.0.0
	 */
	static public function get_widget_title( $slug = '' ) {

		$widget_list = self::get_widget_list();

		$widget_name = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_name = $widget_list[ $slug ]['title'];
		}

		return apply_filters( 'social_widget_name', $widget_name );
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.0.0
	 */
	static public function get_widget_icon( $slug = '' ) {

		$widget_list = self::get_widget_list();

		$widget_icon = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_icon = $widget_list[ $slug ]['icon'];
		}

		return apply_filters( 'social_widget_icon', $widget_icon );
	}

	/**
	 * Provide Widget Keywords
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.5.1
	 */
	static public function get_widget_keywords( $slug = '' ) {

		$widget_list = self::get_widget_list();

		$widget_keywords = '';

		if ( isset( $widget_list[ $slug ] ) && isset( $widget_list[ $slug ]['keywords'] ) ) {
			$widget_keywords = $widget_list[ $slug ]['keywords'];
		}

		return apply_filters( 'social_widget_keywords', $widget_keywords );
	}

	/**
	 * Provide Widget settings.
	 *
	 * @return array()
	 * @since 1.0.0
	 */
	static public function get_widget_options() {

		if ( null === self::$widget_options ) {

			$widgets       = self::get_widget_list();
			$saved_widgets = self::get_admin_settings_option( 'social_widgets' );

			if ( is_array( $widgets ) ) {

				foreach ( $widgets as $slug => $data ) {

					if ( isset( $saved_widgets[ $slug ] ) ) {

						if ( 'disabled' === $saved_widgets[ $slug ] ) {
							$widgets[ $slug ]['is_activate'] = false;
						} else {
							$widgets[ $slug ]['is_activate'] = true;
						}
					} else {
						$widgets[ $slug ]['is_activate'] = ( isset( $data['default'] ) ) ? $data['default'] : false;
					}
				}
			}

			self::$widget_options = $widgets;
		}

		return apply_filters( 'social_active_widgets', self::$widget_options );
	}

	/**
	 * Widget Active.
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.0.0
	 */
	static public function is_widget_active( $slug = '' ) {

		$widgets     = self::get_widget_options();
		$is_activate = false;

		if ( isset( $widgets[ $slug ] ) ) {
			$is_activate = $widgets[ $slug ]['is_activate'];
		}

		return $is_activate;
	}

	/**
	 * Returns Script array.
	 *
	 * @return array()
	 * @since 1.0.0
	 */
	static public function get_widget_script() {

		return Social_Config::get_widget_script();
	}

	/**
	 * Returns Style array.
	 *
	 * @return array()
	 * @since 1.0.0
	 */
	static public function get_widget_style() {

		return Social_Config::get_widget_style();
	}

	/**
	 * Provide Image data based on id.
	 *
	 * @return array()
	 * @param int    $image_id Image ID.
	 * @param string $image_url Image URL.
	 * @param array  $image_size Image sizes array.
	 * @since 1.0.0
	 */
	static public function get_image_data( $image_id, $image_url, $image_size ) {

		if ( ! $image_id && ! $image_url ) {
			return false;
		}

		$data = array();

		if ( ! empty( $image_id ) ) { // Existing attachment.

			$attachment = get_post( $image_id );

			$data['id']      = $image_id;
			$data['url']     = $image_url;
			$data['image']   = wp_get_attachment_image( $attachment->ID, $image_size, true );
			$data['caption'] = $attachment->post_excerpt;

		} else { // Placeholder image, most likely.

			if ( empty( $image_url ) ) {
				return;
			}

			$data['id']      = false;
			$data['url']     = $image_url;
			$data['image']   = '<img src="' . $image_url . '" alt="" title="" />';
			$data['caption'] = '';
		}

		return $data;
	}

	/**
	 * Check if the Elementor is updated with FA & SVG icons supported.
	 *
	 * @since 1.1.0
	 *
	 * @return boolean if Elementor builder updated.
	 */
	static public function is_elementor_updated() {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return true;
		} else {
			return false;
		}
	}
}
