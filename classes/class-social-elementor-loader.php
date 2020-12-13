<?php
/**
 * Social Elementor Loader.
 *
 * @package SOCIAL_ELEMENTOR
 */

if ( ! class_exists( 'Social_Elementor_Loader' ) ) {

	/**
	 * Class Social_Elementor_Loader.
	 */
	final class Social_Elementor_Loader {

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
		 * Constructor
		 */
		public function __construct() {

			$this->define_constants();

			// Activation hook.
			register_activation_hook( SOCIAL_ELEMENTOR_FILE, array( $this, 'activation_reset' ) );

			// deActivation hook.
			register_deactivation_hook( SOCIAL_ELEMENTOR_FILE, array( $this, 'deactivation_reset' ) );

			add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @param   mixed $links Plugin Action links.
		 * @return  array
		 */
		public function action_links( $links = array() ) {

			$slug = 'social-elementor';

			$action_links = array(
				'settings' => '<a href="' . esc_url( admin_url( 'options-general.php?page=' . $slug ) ) . '" aria-label="' . esc_attr__( 'View Social Elementor Settings', 'social-elementor' ) . '">' . esc_html__( 'Configure', 'social-elementor' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}

		/**
		 * Defines all constants
		 *
		 * @since 1.0.0
		 */
		public function define_constants() {
			define( 'SOCIAL_ELEMENTOR_BASE', plugin_basename( SOCIAL_ELEMENTOR_FILE ) );
			define( 'SOCIAL_ELEMENTOR_ROOT', dirname( SOCIAL_ELEMENTOR_BASE ) );
			define( 'SOCIAL_ELEMENTOR_DIR', plugin_dir_path( SOCIAL_ELEMENTOR_FILE ) );
			define( 'SOCIAL_ELEMENTOR_URL', plugins_url( '/', SOCIAL_ELEMENTOR_FILE ) );
			define( 'SOCIAL_ELEMENTOR_VER', '1.2.0' );
			define( 'SOCIAL_ELEMENTOR_MODULE_DIR', SOCIAL_ELEMENTOR_DIR . 'modules/' );
			define( 'SOCIAL_ELEMENTOR_MODULE_URL', SOCIAL_ELEMENTOR_URL . 'modules/' );
			define( 'SOCIAL_ELEMENTOR_SLUG', 'social-elementor' );
			define( 'SOCIAL_ELEMENTOR_CATEGORY', 'Social Addons' );
		}

		/**
		 * Loads plugin files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		function load_plugin() {

			if ( ! did_action( 'elementor/loaded' ) ) {
				/* TO DO */
				add_action( 'admin_notices', array( $this, 'social_elementor_fails_to_load' ) );
				return;
			}

			// Action Links.
			add_action( 'plugin_action_links_' . SOCIAL_ELEMENTOR_BASE, array( $this, 'action_links' ) );

			$this->load_textdomain();

			require_once SOCIAL_ELEMENTOR_DIR . 'classes/class-social-elementor-core.php';
		}

		/**
		 * Load Social Elementor Text Domain.
		 * This will load the translation textdomain depending on the file priorities.
		 *      1. Global Languages /wp-content/languages/social-elementor-lite/ folder
		 *      2. Local dorectory /wp-content/plugins/social-elementor-lite/languages/ folder
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			/**
			 * Filters the languages directory path to use for Social Elementor.
			 *
			 * @param string $lang_dir The languages directory path.
			 */
			$lang_dir = apply_filters( 'social_elementor_domain_loader', SOCIAL_ELEMENTOR_ROOT . '/languages/' );
			load_plugin_textdomain( 'social-elementor', false, $lang_dir );
		}
		/**
		 * Fires admin notice when Elementor is not installed and activated.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function social_elementor_fails_to_load() {

			$notice_css_style = '.social-elementor-notice.notice { padding: 12px; }
			.social-elementor-notice.notice img { float: left; margin-top: 4px; }		
			.social-elementor-notice.notice img, .social-elementor-notice.notice p,
			.social-elementor-notice.notice p span { margin-right: 20px; }
			.social-elementor-notice.notice p span:last-child a { float: right; background-color: #8141bb; margin-top: -14px; }';

			$class = 'social-elementor-notice notice notice-error';
			/* translators: %s: html tags */

			$site_icon        = esc_url( SOCIAL_ELEMENTOR_URL . 'admin/assets/images/Siteicon.png' );
			$site_icon_markup = '<img src="' . $site_icon . '" class="social-elementor-notice-icon" alt="WebEmpire" title="WebEmpire" >';
			$message          = sprintf( /* translators: %1$s: html tag, %2$s: html tag, %1$s: html tag */ esc_html__( '%1$s Thanks for choosing Social Addon for Elementor plugin!!! %2$s %3$s', 'social-elementor' ), '<strong>', '</strong>', '<br/>' );
			$message         .= sprintf( esc_html__( 'Please install and activate the Elementor plugin, to explore the features of this plugin.', 'social-elementor' ), '<strong>', '</strong>' );

			$plugin = 'elementor/elementor.php';

			if ( _is_elementor_installed() ) {
				if ( ! current_user_can( 'activate_plugins' ) ) {
					return;
				}

				$action_url   = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
				$button_label = esc_html__( 'Activate Elementor Now', 'social-elementor' );

			} else {
				if ( ! current_user_can( 'install_plugins' ) ) {
					return;
				}

				$action_url   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
				$button_label = esc_html__( 'Install Elementor', 'social-elementor' );
			}

			$button = '<span> <a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></span>';

			printf( '<style> %1$s </style> <div class="%2$s"> %3$s <p> <span> %4$s </span> %5$s </p> </div>', $notice_css_style, esc_attr( $class ), $site_icon_markup, $message, $button );
		}

		/**
		 * Activation Reset
		 */
		function activation_reset() {
		}

		/**
		 * Deactivation Reset
		 */
		function deactivation_reset() {
		}
	}

	/**
	 *  Prepare if class 'Social_Elementor_Loader' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Social_Elementor_Loader::get_instance();
}

/**
 * Is elementor plugin installed.
 */
if ( ! function_exists( '_is_elementor_installed' ) ) {

	/**
	 * Check if Elementor Pro is installed
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	function _is_elementor_installed() {
		$path    = 'elementor/elementor.php';
		$plugins = get_plugins();

		return isset( $plugins[ $path ] );
	}
}
