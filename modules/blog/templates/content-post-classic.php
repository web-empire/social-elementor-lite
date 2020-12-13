<?php
/**
 * Social Elementor Post - Template.
 *
 * @package SOCIAL_ELEMENTOR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

// Ensure visibility.
if ( empty( $post ) ) {
	return;
}

?>

<?php do_action( 'social_elementor_single_post_before_wrap', get_the_ID(), $settings ); ?>

<div class="social-blog-post-wrapper <?php echo $this->get_masonry_classes(); ?> <?php echo ( $is_featured ) ? 'social-blog-post-wrapper-featured' : ''; ?>">
	<div class="social-blog-post-bg-wrapper">

		<?php if ( 'yes' === $this->get_instance_value( 'link_complete_box' ) ) { ?>
			<a href="<?php the_permalink(); ?>" target="<?php echo ( 'yes' === $this->get_instance_value( 'link_complete_box_tab' ) ) ? '_blank' : '_self'; ?>" class="social-blog-post-complete-box-overlay"></a>
		<?php } ?>
		<?php do_action( 'social_elementor_single_post_before_inner_wrap', get_the_ID(), $settings ); ?>

		<div class="social-blog-post-inner-wrapp">

		<?php $this->render_featured_image(); ?>

			<?php do_action( 'social_elementor_single_post_before_content_wrap', get_the_ID(), $settings ); ?>

			<div class="social-blog-post-content-wrapper">
			<?php
			if (
				'media' === $this->get_instance_value( 'terms_position' ) &&
				'background' === $this->get_instance_value( 'image_position' )
			) {
				?>
				<div class="social-blog-post-terms-wrap"><?php $this->render_terms( $this->get_instance_value( 'terms_position' ) ); ?></div>
				<?php
			}
			if ( 'above_content' === $this->get_instance_value( 'terms_position' ) ) {
				?>
				<div class="social-blog-post-terms-wrap"><?php $this->render_terms( 'above_content' ); ?></div>
				<?php
			}
			if ( $this->get_instance_value( 'show_title' ) ) {
				$this->render_title();
			}
			if ( $is_featured ) {
				$this->render_featured_meta_data();
			} else {

				if ( $this->get_instance_value( 'show_meta' ) ) {
					$this->render_meta_data();
				}
			}
			if ( $is_featured ) {
				$this->render_featured_excerpt();
			} else {

				if ( $this->get_instance_value( 'show_excerpt' ) ) {
					$this->render_excerpt();
				}
			}
			if ( $this->get_instance_value( 'show_cta' ) ) {
				$this->render_read_more();
			}
			?>
			</div>
			<?php do_action( 'social_elementor_single_post_after_content_wrap', get_the_ID(), $settings ); ?>

		</div>
		<?php do_action( 'social_elementor_single_post_after_inner_wrap', get_the_ID(), $settings ); ?>

	</div>

</div>
<?php do_action( 'social_elementor_single_post_after_wrap', get_the_ID(), $settings ); ?>
