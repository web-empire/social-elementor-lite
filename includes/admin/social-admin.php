<?php
/**
 * Social Elementor Admin HTML.
 *
 * @package SOCIAL_ELEMENTOR
 */

use SocialElementor\Classes\Social_Helper;
?>

<div class="social-menu-page-wrapper">
	<div id="social-menu-page">
		<div class="social-menu-page-header <?php echo esc_attr( implode( ' ', $social_admin_header_wrapper_class ) ); ?>">
			<div class="social-container social-flex">
				<div class="social-title">
					<a href="<?php echo esc_url( $social_elementor_visit_site_url ); ?>" target="_blank" rel="noopener" >
						<img src="<?php echo esc_url( SOCIAL_ELEMENTOR_URL . 'admin/assets/images/WebEmpire.png' ); ?>" class="social-header-icon" alt="<?php echo SOCIAL_ELEMENTOR_PLUGIN_NAME; ?> " >
					<span class="social-plugin-version"><?php echo SOCIAL_ELEMENTOR_VER; ?></span>
					<?php do_action( 'social_header_title' ); ?>
					</a>
				</div>
				<div class="social-top-links">
					<?php
						esc_attr_e( 'Let\'s get connected socially with Elementor!', 'social-elementor' );
					?>
				</div>
			</div>
		</div>

		<?php
		// Settings update message.
		if ( isset( $_REQUEST['message'] ) && ( 'saved' == $_REQUEST['message'] || 'saved_ext' == $_REQUEST['message'] ) ) {
			?>
				<div id="message" class="notice notice-success is-dismissive social-notice"><p> <?php esc_html_e( 'Settings saved successfully.', 'social-elementor' ); ?> </p></div>
			<?php
		}
		do_action( 'social_render_admin_content' );
		?>
	</div>
</div>
