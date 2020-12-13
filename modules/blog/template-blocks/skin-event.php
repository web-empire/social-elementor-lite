<?php
/**
 * Social Elementor Event Skin.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Blog\TemplateBlocks;

use SocialElementor\Modules\Blog\TemplateBlocks\Skin_Style;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Event
 */
class Skin_Event extends Skin_Style {


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
	 * Get featured image.
	 *
	 * Returns the featured image HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_featured_image() {

		$settings          = self::$settings;
		$settings['image'] = array(
			'id' => get_post_thumbnail_id(),
		);

		$settings['image_size'] = $this->get_instance_value( 'image_size' );

		$settings['image_custom_dimension'] = $this->get_instance_value( 'image_custom_dimension' );

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings );

		if ( 'none' === $this->get_instance_value( 'image_position' ) ) {
			$thumbnail_html = '';
		}

		do_action( 'social_elementor_single_post_before_thumbnail', get_the_ID(), $settings );

		if ( 'yes' === $this->get_instance_value( 'link_img' ) ) {
			$href   = apply_filters( 'social_single_post_permalink', get_the_permalink(), get_the_ID(), $settings );
			$target = ( 'yes' === $this->get_instance_value( 'link_new_tab' ) ) ? '_blank' : '_self';
			$this->add_render_attribute( 'img_link' . get_the_ID(), 'target', $target );
		} else {
			$href = 'javascript:void(0);';
		}

		$this->add_render_attribute( 'img_link' . get_the_ID(), 'href', $href );
		$this->add_render_attribute( 'img_link' . get_the_ID(), 'title', get_the_title() );
		?>
		<div class="social-blog-post-thumbnail">
			<a <?php echo $this->get_render_attribute_string( 'img_link' . get_the_ID() ); ?>><?php echo $thumbnail_html; ?></a>
			<div class="social-blog-post-datebox <?php echo $this->get_no_image_class(); ?>">
				<div class="social-blog-post-date-wrap">
					<?php
					$date  = "<span class='social-blog-post-date-month'>";
					$date .= date_i18n( 'M', strtotime( get_the_date() ) );
					$date .= '</span>';
					$date .= "<span class='social-blog-post-date-day'>";
					$date .= date_i18n( 'd', strtotime( get_the_date() ) );
					$date .= '</span>';
					?>
					<?php echo apply_filters( 'social_blog_post_event_date', $date, get_the_ID(), get_option( 'date_format' ), '', '' ); ?>
				</div>				
			</div>
		</div>
		<?php
		do_action( 'social_elementor_single_post_after_thumbnail', get_the_ID(), $settings );
	}

	/**
	 * Get Classes array for outer wrapper class.
	 *
	 * Returns the array for outer wrapper class.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_outer_wrapper_classes() {

		$classes = array(
			'social-blog-post-grid-layout',
			'social-blog-posts',
		);

		return $classes;
	}
}

