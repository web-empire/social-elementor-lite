<?php
/**
 * Social Elementor Base Skin.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Modules\Blog\TemplateBlocks;

use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Base
 */
abstract class Skin_Style {


	/**
	 * Query object
	 *
	 * @since 1.7.0
	 * @var object $query
	 */
	public static $query;

	/**
	 * Query object
	 *
	 * @since 1.7.0
	 * @var object $query_obj
	 */
	public static $query_obj;

	/**
	 * Settings
	 *
	 * @since 1.7.0
	 * @var object $settings
	 */
	public static $settings;

	/**
	 * Skin
	 *
	 * @since 1.7.0
	 * @var object $skin
	 */
	public static $skin;

	/**
	 * Node ID of element
	 *
	 * @since 1.7.0
	 * @var object $node_id
	 */
	public static $node_id;

	/**
	 * Rendered Settings
	 *
	 * @since 1.7.0
	 * @var object $_render_attributes
	 */
	public $_render_attributes;

	/**
	 * Get post title.
	 *
	 * Returns the post title HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_featured_title() {

		$settings = self::$settings;

		do_action( 'social_elementor_single_post_before_title', get_the_ID(), $settings );
		?>
		<h3 class="social-blog-post-title">

		<?php if ( $this->get_instance_value( 'link_title' ) ) { ?>

			<?php $target = ( 'yes' == $this->get_instance_value( 'link_title_new' ) ) ? '_blank' : '_self'; ?>
			<a href="<?php echo apply_filters( 'social_single_post_permalink', get_the_permalink(), get_the_ID(), $settings ); ?>" target="<?php echo $target; ?>">
				<?php the_title(); ?>
			</a>

		<?php } else { ?>
			<?php the_title(); ?>
		<?php } ?>
		</h3>
		<?php

		do_action( 'social_elementor_single_post_after_title', get_the_ID(), $settings );
	}

	/**
	 * Get post meta.
	 *
	 * Returns the post meta HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_featured_meta_data() {

		$settings = self::$settings;

		if ( 'yes' != $this->get_instance_value( 'show_meta' ) ) {
			return;
		}

		do_action( 'social_elementor_single_post_before_meta', get_the_ID(), $settings );

		$_f_meta = $this->get_instance_value( '_f_meta' );

		$sequence = apply_filters( 'social_blog_post_meta_sequence', array( 'author', 'date', 'comments', 'cat', 'tag' ) );
		?>

		<<?php echo $this->get_instance_value( 'meta_tag' ); ?> class="social-blog-post-meta-data">

		<?php
		foreach ( $sequence as $key => $seq ) {

			$post_type = $settings['post_type_filter'];

			switch ( $seq ) {
				case 'author':
					if ( in_array( 'author', $_f_meta ) ) {
						$this->render_author();
					}
					break;

				case 'date':
					if ( in_array( 'date', $_f_meta ) ) {
						$this->render_date();
					}
					break;

				case 'comments':
					if ( in_array( 'comment', $_f_meta ) ) {
						$this->render_comments();
					}
					break;

				case 'cat':
					if ( 'custom' == $settings['query_type'] ) {
						if ( 'post' != $post_type ) {
							break;
						}
					}

					if ( in_array( 'category', $_f_meta ) ) {
						$terms  = wp_get_post_terms( get_the_ID(), 'category' );
						$prefix = 'cat';
						$this->get_meta_html_by_prefix( $terms, $prefix );
					}
					break;

				case 'tag':
					if ( 'custom' == $settings['query_type'] ) {
						if ( 'post' != $post_type ) {
							break;
						}
					}

					if ( in_array( 'tag', $_f_meta ) ) {
						$terms  = wp_get_post_terms( get_the_ID(), 'post_tag' );
						$prefix = 'tag';
						$this->get_meta_html_by_prefix( $terms, $prefix );
					}
					break;
			}
		}
		?>

		</<?php echo $this->get_instance_value( 'meta_tag' ); ?>>

		<?php

		do_action( 'social_elementor_single_post_after_meta', get_the_ID(), $settings );
	}

	/**
	 * Get post excerpt length.
	 *
	 * Returns the length of post excerpt.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function social_posts_featured_excerpt_length_filter() {
		return $this->get_instance_value( '_f_excerpt_length' );
	}

	/**
	 * Get post excerpt.
	 *
	 * Returns the post excerpt HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_featured_excerpt() {

		$settings          = self::$settings;
		$_f_excerpt_length = $this->get_instance_value( '_f_excerpt_length' );

		if ( 0 == $_f_excerpt_length ) {
			return;
		}

		add_filter( 'excerpt_length', [ $this, 'social_posts_featured_excerpt_length_filter' ], 20 );
		add_filter( 'excerpt_more', [ $this, 'social_posts_excerpt_more_filter' ], 20 );

		do_action( 'social_elementor_single_post_before_excerpt', get_the_ID(), $settings );
		?>
		<div class="social-blog-post-excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php

		remove_filter( 'excerpt_length', [ $this, 'social_posts_featured_excerpt_length_filter' ], 20 );
		remove_filter( 'excerpt_more', [ $this, 'social_posts_excerpt_more_filter' ], 20 );

		do_action( 'social_elementor_single_post_after_excerpt', get_the_ID(), $settings );
	}

	/**
	 * Get no image class.
	 *
	 * Returns the no image class.
	 *
	 * @since 1.7.2
	 * @access public
	 */
	public function get_no_image_class() {

		if ( 'none' == $this->get_instance_value( 'image_position' ) ) {
			return 'social-blog-post-no-image';
		}

		return ( ! get_the_post_thumbnail_url() ) ? 'social-blog-post-no-image' : '';
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

		$settings = self::$settings;

		if ( 'none' == $this->get_instance_value( 'image_position' ) ) {
			return;
		}

		$settings['image'] = [
			'id' => get_post_thumbnail_id(),
		];

		$settings['image_size'] = $this->get_instance_value( 'image_size' );

		$settings['image_custom_dimension'] = $this->get_instance_value( 'image_custom_dimension' );

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings );

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		do_action( 'social_elementor_single_post_before_thumbnail', get_the_ID(), $settings );

		if ( 'yes' == $this->get_instance_value( 'link_img' ) ) {
			$href   = apply_filters( 'social_single_post_permalink', get_the_permalink(), get_the_ID(), $settings );
			$target = ( 'yes' == $this->get_instance_value( 'link_new_tab' ) ) ? '_blank' : '_self';
			$this->add_render_attribute( 'img_link' . get_the_ID(), 'target', $target );
		} else {
			$href = 'javascript:void(0);';
		}

		$this->add_render_attribute( 'img_link' . get_the_ID(), 'href', $href );
		$this->add_render_attribute( 'img_link' . get_the_ID(), 'title', get_the_title() );
		?>
		<div class="social-blog-post-thumbnail">
			<a <?php echo $this->get_render_attribute_string( 'img_link' . get_the_ID() ); ?>><?php echo $thumbnail_html; ?></a>
			<?php
			if ( 'background' != $this->get_instance_value( 'image_position' ) ) {
				$this->render_terms( 'media' );
			}
			?>
		</div>
		<?php
		do_action( 'social_elementor_single_post_after_thumbnail', get_the_ID(), $settings );
	}

	/**
	 * Get post title.
	 *
	 * Returns the post title HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_title() {

		$settings = self::$settings;
		do_action( 'social_elementor_single_post_before_title', get_the_ID(), $settings );
		?>
		<<?php echo $this->get_instance_value( 'title_tag' ); ?> class="social-blog-post-title">

		<?php if ( $this->get_instance_value( 'link_title' ) ) { ?>

			<?php $target = ( 'yes' == $this->get_instance_value( 'link_title_new' ) ) ? '_blank' : '_self'; ?>
			<a href="<?php echo apply_filters( 'social_single_post_permalink', get_the_permalink(), get_the_ID(), $settings ); ?>" target="<?php echo $target; ?>">
				<?php the_title(); ?>
			</a>

		<?php } else { ?>
			<?php the_title(); ?>
		<?php } ?>
		</<?php echo $this->get_instance_value( 'title_tag' ); ?>>
		<?php

		do_action( 'social_elementor_single_post_after_title', get_the_ID(), $settings );
	}

	/**
	 * Get post meta.
	 *
	 * Returns the post meta HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_meta_data() {

		$settings = self::$settings;

		if ( 'yes' === $this->get_instance_value( 'show_meta' ) ) {

			do_action( 'social_elementor_single_post_before_meta', get_the_ID(), $settings );

			$sequence = apply_filters( 'social_blog_post_meta_sequence', array( 'author', 'date', 'comments', 'cat', 'tag' ) );
			?>
			<<?php echo $this->get_instance_value( 'meta_tag' ); ?> class="social-blog-post-meta-data">
			<?php
			if ( $this->get_instance_value( 'show_meta' ) ) {

				foreach ( $sequence as $key => $seq ) {

					$post_type = $settings['post_type_filter'];

					switch ( $seq ) {
						case 'author':
							if ( $this->get_instance_value( 'show_author' ) ) {
								$this->render_author();
							}
							break;

						case 'date':
							if ( $this->get_instance_value( 'show_date' ) ) {
								$this->render_date();
							}
							break;

						case 'comments':
							if ( $this->get_instance_value( 'show_comments' ) ) {
								$this->render_comments();
							}
							break;

						case 'cat':
							if ( 'custom' == $settings['query_type'] ) {
								if ( 'post' != $post_type ) {
									break;
								}
							}

							if ( $this->get_instance_value( 'show_categories' ) == 'yes' ) {
								$terms  = wp_get_post_terms( get_the_ID(), 'category' );
								$prefix = 'cat';
								$this->get_meta_html_by_prefix( $terms, $prefix );
							}
							break;

						case 'tag':
							if ( 'custom' == $settings['query_type'] ) {
								if ( 'post' != $post_type ) {
									break;
								}
							}

							if ( $this->get_instance_value( 'show_tags' ) == 'yes' ) {
								$terms  = wp_get_post_terms( get_the_ID(), 'post_tag' );
								$prefix = 'tag';
								$this->get_meta_html_by_prefix( $terms, $prefix );
							}
							break;
					}
				}
			}
		}
		?>
		</<?php echo $this->get_instance_value( 'meta_tag' ); ?>>
		<?php
		do_action( 'social_elementor_single_post_after_meta', get_the_ID(), $settings );
	}

	/**
	 * Get post author.
	 *
	 * Returns the post author HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_author() {

		$settings = self::$settings;

		$unlink_meta = $this->get_instance_value( 'link_meta' );

		$icon = $this->get_instance_value( 'show_author_icon' );
		do_action( 'social_elementor_single_post_before_author', get_the_ID(), $settings );
		?>
		<span class="social-blog-post-author">
			<?php if ( '' != $icon ) { ?>
			<i class="<?php echo $icon; ?>"></i>
			<?php } ?>
			<?php
			if ( 'yes' == $this->get_instance_value( 'link_meta' ) ) {
				the_author_posts_link();
			} else {
				the_author();
			}
			?>
		</span>
		<?php
		do_action( 'social_elementor_single_post_after_author', get_the_ID(), $settings );
	}

	/**
	 * Get post published date.
	 *
	 * Returns the post published date HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_date() {

		$settings = self::$settings;

		$icon = $this->get_instance_value( 'show_date_icon' );
		do_action( 'social_elementor_single_post_before_date', get_the_ID(), $settings );
		?>
		<span class="social-blog-post-date">
			<?php if ( '' != $icon ) { ?>
			<i class="<?php echo $icon; ?>"></i>
			<?php } ?>
			<?php echo apply_filters( 'social_blog_post_date_format', get_the_date(), get_the_ID(), get_option( 'date_format' ), '', '' ); ?>
		</span>
		<?php
		do_action( 'social_elementor_single_post_after_date', get_the_ID(), $settings );
	}

	/**
	 * Get post related comments.
	 *
	 * Returns the post related comments HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_comments() {

		$settings = self::$settings;

		$icon = $this->get_instance_value( 'show_comments_icon' );
		do_action( 'social_elementor_single_post_before_comments', get_the_ID(), $settings );
		?>
		<span class="social-blog-post-comments">
			<?php if ( '' != $icon ) { ?>
			<i class="<?php echo $icon; ?>"></i>
			<?php } ?>
			<?php comments_number(); ?>
		</span>
		<?php
		do_action( 'social_elementor_single_post_after_comments', get_the_ID(), $settings );
	}

	/**
	 * Get post related terms.
	 *
	 * Returns the post related terms HTML wrap.
	 *
	 * @param string $position Position value of term.
	 * @since 1.7.0
	 * @access public
	 */
	public function render_terms( $position ) {

		$settings = self::$settings;

		if ( $position != $this->get_instance_value( 'terms_position' ) ) {
			return;
		}

		$this->render_term_html();
	}

	/**
	 * Get post related terms html.
	 *
	 * Returns the post related terms HTML wrap.
	 *
	 * @param array  $terms Terms array.
	 * @param string $prefix Prefix cat/tag.
	 * @since 1.7.0
	 * @access public
	 */
	public function get_meta_html_by_prefix( $terms, $prefix ) {

		$settings = self::$settings;

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		$num = $this->get_instance_value( $prefix . '_meta_max_terms' );

		if ( '' != $num ) {
			$terms = array_slice( $terms, 0, $num );
		}

		$icon = $this->get_instance_value( $prefix . '_meta_show_term_icon' );

		$link_meta = apply_filters( 'social_posts_taxomony_badge_link', $this->get_instance_value( 'link_meta' ) );

		if ( 'yes' == $link_meta ) {
			$format = '<a href="%2$s" class="social-blog-listing-term-link">%1$s</a>';
		} else {
			$format = '<span class="social-blog-listing-term-link">%1$s</span>';
		}

		$result = '';

		if ( '' != $icon ) {
			$result .= '<i class="' . $icon . '"></i> ';
		}

		foreach ( $terms as $term ) {
			$result .= sprintf( $format, $term->name, get_term_link( (int) $term->term_id ) );
		}

		do_action( 'social_elementor_single_post_before_content_terms', get_the_ID(), $settings );

		printf( '<span class="social-blog-post-terms-meta social-blog-post-terms-meta-%s">%s</span>', $prefix, $result );

		do_action( 'social_elementor_single_post_after_content_terms', get_the_ID(), $settings );
	}



	/**
	 * Get post related terms.
	 *
	 * Returns the post related terms HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_term_html() {

		$settings   = self::$settings;
		$skin       = self::$skin;
		$terms_show = '';

		if ( 'feed' == $skin || 'news' == $skin ) {
			if ( 'yes' != $this->get_instance_value( 'show_taxonomy' ) ) {
				return;
			}
		}

		if ( 'main' == $settings['query_type'] || 'post' == $settings['post_type_filter'] ) {
			$terms_show = $this->get_instance_value( 'terms_to_show' );
		} else {
			$terms_show = get_taxonomies( '', 'names' );
		}

		$terms = wp_get_post_terms( get_the_ID(), $terms_show );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		$num = $this->get_instance_value( 'max_terms' );

		if ( '' != $num ) {
			$terms = array_slice( $terms, 0, $num );
		}

		$terms = apply_filters( 'social_posts_tax_filters', $terms );

		$icon = $this->get_instance_value( 'show_term_icon' );

		$link_meta = apply_filters( 'social_posts_taxomony_badge_link', $this->get_instance_value( 'link_meta' ) );

		if ( 'yes' == $link_meta ) {
			$format = '<a href="%2$s" class="social-blog-listing-term-link">%1$s</a>';
		} else {
			$format = '<span class="social-blog-listing-term-link">%1$s</span>';
		}

		$result = '';

		if ( '' != $icon ) {
			$result .= '<i class="' . $icon . '"></i> ';
		}

		foreach ( $terms as $term ) {
			$result .= sprintf( $format, $term->name, get_term_link( (int) $term->term_id ) );
		}
		do_action( 'social_elementor_single_post_before_terms', get_the_ID(), $settings );

		printf( '<span class="social-blog-post-terms">%s</span>', $result );

		do_action( 'social_elementor_single_post_after_terms', get_the_ID(), $settings );
	}

	/**
	 * Get post excerpt length.
	 *
	 * Returns the length of post excerpt.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function social_posts_excerpt_length_filter() {
		return $this->get_instance_value( 'excerpt_length' );
	}

	/**
	 * Get post excerpt end text.
	 *
	 * Returns the string to append to post excerpt.
	 *
	 * @param string $more returns string.
	 * @since 1.7.0
	 * @access public
	 */
	public function social_posts_excerpt_more_filter( $more ) {
		return ' ...';
	}

	/**
	 * Get post excerpt.
	 *
	 * Returns the post excerpt HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_excerpt() {

		$settings        = self::$settings;
		$_excerpt_length = $this->get_instance_value( 'excerpt_length' );

		if ( 0 == $_excerpt_length ) {
			return;
		}

		add_filter( 'excerpt_length', [ $this, 'social_posts_excerpt_length_filter' ], 20 );
		add_filter( 'excerpt_more', [ $this, 'social_posts_excerpt_more_filter' ], 20 );

		do_action( 'social_elementor_single_post_before_excerpt', get_the_ID(), $settings );
		?>

		<div class="social-blog-post-excerpt">
			<?php the_excerpt(); ?>
		</div>

		<?php

		remove_filter( 'excerpt_length', [ $this, 'social_posts_excerpt_length_filter' ], 20 );
		remove_filter( 'excerpt_more', [ $this, 'social_posts_excerpt_more_filter' ], 20 );

		do_action( 'social_elementor_single_post_after_excerpt', get_the_ID(), $settings );
	}

	/**
	 * Get post call to action.
	 *
	 * Returns the post call to action HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_read_more() {

		$settings = self::$settings;

		if ( 'yes' === $this->get_instance_value( 'show_cta' ) ) {

			do_action( 'social_elementor_single_post_before_cta', get_the_ID(), $settings );

			$this->add_render_attribute(
				'icon' . get_the_ID(),
				'class',
				[
					'elementor-button-icon',
					'elementor-align-icon-' . $this->get_instance_value( 'cta_icon_align' ),
				]
			);

			$this->add_render_attribute(
				'cta_link' . get_the_ID(),
				[
					'class'  => [
						'social-blog-post-read-more-btn',
						'elementor-button',
					],
					'href'   => apply_filters( 'social_single_post_permalink', get_the_permalink(), get_the_ID(), $settings ),
					'target' => ( 'yes' == $this->get_instance_value( 'cta_new_tab' ) ) ? '_blank' : '_self',
				]
			);

			?>
				<a <?php echo $this->get_render_attribute_string( 'cta_link' . get_the_ID() ); ?>>

					<?php if ( ! empty( $this->get_instance_value( 'cta_icon' ) ) ) : ?>

					<span <?php echo $this->get_render_attribute_string( 'icon' . get_the_ID() ); ?>>

						<i class="<?php echo esc_attr( $this->get_instance_value( 'cta_icon' ) ); ?>" aria-hidden="true"></i>

					</span>

					<?php endif; ?>

					<span><?php echo $this->get_instance_value( 'cta_text' ); ?></span>
				</a>
			<?php
			do_action( 'social_elementor_single_post_after_cta', get_the_ID(), $settings );
		}
	}

	/**
	 * Get masonry script.
	 *
	 * Returns the post masonry script.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_masonry_script() {

		$structure = $this->get_instance_value( 'post_structure' );

		if ( 'masonry' != $structure ) {
			return;
		}

		$layout = 'masonry';

		?>
		<script type="text/javascript">

			jQuery( document ).ready( function( $ ) {

				$( '.social-blog-post-grid-inner' ).each( function() {

					var	scope 		= $( '[data-id="<?php echo self::$node_id; ?>"]' );
					var selector 	= $(this);

					if ( selector.closest( scope ).length < 1 ) {
						return;
					}

					selector.imagesLoaded( function() {

						$isotopeObj = selector.isotope({
							layoutMode: '<?php echo $layout; ?>',
							itemSelector: '.social-blog-post-wrapper',
						});

						selector.find( '.social-blog-post-wrapper' ).resize( function() {
							$isotopeObj.isotope( 'layout' );
						});
					});
				});
			});

		</script>
		<?php
	}

	/**
	 * Get Footer.
	 *
	 * Returns the Pagination HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_footer() {

		$this->render_masonry_script();
	}

	/**
	 * Render output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_header() {

		$this->render_filters();
	}

	/**
	 * Get Filter taxonomy array.
	 *
	 * Returns the Filter array of objects.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_filter_values() {

		$settings = self::$settings;
		$skin     = self::$skin;

		$post_type = $settings['post_type_filter'];

		$filter_by = $this->get_instance_value( 'tax_masonry_' . $post_type . '_filter' );

		$filter_type = $settings[ $filter_by . '_' . $post_type . '_filter_rule' ];

		$filters = $settings[ 'tax_' . $filter_by . '_' . $post_type . '_filter' ];

		// Get the categories for post types.
		$taxs = get_terms( $filter_by );

		$filter_array = array();

		if ( is_wp_error( $taxs ) ) {
			return array();
		}

		if ( empty( $filters ) || '' == $filters ) {

			$filter_array = $taxs;
		} else {

			foreach ( $taxs as $key => $value ) {

				if ( 'IN' == $filter_type ) {

					if ( in_array( $value->slug, $filters ) ) {

						$filter_array[] = $value;
					}
				} else {

					if ( ! in_array( $value->slug, $filters ) ) {

						$filter_array[] = $value;
					}
				}
			}
		}

		return $filter_array;
	}

	/**
	 * Get Masonry classes array.
	 *
	 * Returns the Masonry classes array.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_masonry_classes() {

		$settings = self::$settings;

		$post_type = $settings['post_type_filter'];

		$filter_by = $this->get_instance_value( 'tax_masonry_' . $post_type . '_filter' );

		$taxonomies = wp_get_post_terms( get_the_ID(), $filter_by );
		$class      = array();

		if ( count( $taxonomies ) > 0 ) {

			foreach ( $taxonomies as $taxonomy ) {

				if ( is_object( $taxonomy ) ) {

					$class[] = $taxonomy->slug;
				}
			}
		}

		return implode( ' ', $class );
	}

	/**
	 * Get Wrapper Classes.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_slider_attr() {

		if ( 'carousel' !== $this->get_instance_value( 'post_structure' ) ) {
			return;
		}

		$is_rtl      = is_rtl();
		$direction   = $is_rtl ? 'rtl' : 'ltr';
		$show_dots   = ( in_array( $this->get_instance_value( 'navigation' ), [ 'dots', 'both' ] ) );
		$show_arrows = ( in_array( $this->get_instance_value( 'navigation' ), [ 'arrows', 'both' ] ) );

		$slick_options = [
			'slidesToShow'   => ( $this->get_instance_value( 'slides_to_show' ) ) ? absint( $this->get_instance_value( 'slides_to_show' ) ) : 4,
			'slidesToScroll' => ( $this->get_instance_value( 'slides_to_scroll' ) ) ? absint( $this->get_instance_value( 'slides_to_scroll' ) ) : 1,
			'autoplaySpeed'  => ( $this->get_instance_value( 'autoplay_speed' ) ) ? absint( $this->get_instance_value( 'autoplay_speed' ) ) : 5000,
			'autoplay'       => ( 'yes' === $this->get_instance_value( 'autoplay' ) ),
			'infinite'       => ( 'yes' === $this->get_instance_value( 'infinite' ) ),
			'pauseOnHover'   => ( 'yes' === $this->get_instance_value( 'pause_on_hover' ) ),
			'speed'          => ( $this->get_instance_value( 'transition_speed' ) ) ? absint( $this->get_instance_value( 'transition_speed' ) ) : 500,
			'arrows'         => $show_arrows,
			'dots'           => $show_dots,
			'rtl'            => $is_rtl,
			'prevArrow'      => '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="fa fa-angle-left"></i></button>',
			'nextArrow'      => '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="fa fa-angle-right"></i></button>',
		];

		if ( $this->get_instance_value( 'slides_to_show_tablet' ) || $this->get_instance_value( 'slides_to_show_mobile' ) ) {

			$slick_options['responsive'] = [];

			if ( $this->get_instance_value( 'slides_to_show_tablet' ) ) {

				$tablet_show   = absint( $this->get_instance_value( 'slides_to_show_tablet' ) );
				$tablet_scroll = ( $this->get_instance_value( 'slides_to_scroll_tablet' ) ) ? absint( $this->get_instance_value( 'slides_to_scroll_tablet' ) ) : $tablet_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 1024,
					'settings'   => [
						'slidesToShow'   => $tablet_show,
						'slidesToScroll' => $tablet_scroll,
					],
				];
			}

			if ( $this->get_instance_value( 'slides_to_show_mobile' ) ) {

				$mobile_show   = absint( $this->get_instance_value( 'slides_to_show_mobile' ) );
				$mobile_scroll = ( $this->get_instance_value( 'slides_to_scroll_mobile' ) ) ? absint( $this->get_instance_value( 'slides_to_scroll_mobile' ) ) : $mobile_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 767,
					'settings'   => [
						'slidesToShow'   => $mobile_show,
						'slidesToScroll' => $mobile_scroll,
					],
				];
			}
		}

		$this->add_render_attribute(
			'social-post-slider',
			[
				'data-post_slider'  => wp_json_encode( $slick_options ),
				'data-equal-height' => $this->get_instance_value( 'equal_height' ),
			]
		);

		return $this->get_render_attribute_string( 'social-post-slider' );
	}

	/**
	 * Get Filters.
	 *
	 * Returns the Filter HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_filters() {

		$settings       = self::$settings;
		$skin           = self::$skin;
		$tab_responsive = '';

		if ( 'yes' == $this->get_instance_value( 'tabs_dropdown' ) ) {
			$tab_responsive = ' social-blog-posts-tabs-dropdown';
		}

		if ( 'yes' != $this->get_instance_value( 'show_filters' ) || 'main' == $settings['query_type'] ) {
			return;
		}

		if ( ! in_array( $this->get_instance_value( 'post_structure' ), [ 'masonry', 'normal' ] ) ) {
			return;
		}

		$filters = $this->get_filter_values();
		$filters = apply_filters( 'social_posts_filterable_tabs', $filters, $settings );
		$all     = $this->get_instance_value( 'filters_all_text' );

		?>
		<div class="social-blog-post-header-filters-wrap<?php echo $tab_responsive; ?>">
			<ul class="social-blog-post-header-filters">
				<li class="social-blog-post-header-filter social-blog-post-active-filter" data-filter="*"><?php echo ( 'All' == $all || '' == $all ) ? __( 'All', 'social-elementor' ) : $all; ?></li>
				<?php foreach ( $filters as $key => $value ) { ?>
				<li class="social-blog-post-header-filter" data-filter="<?php echo '.' . $value->slug; ?>"><?php echo $value->name; ?></li>
				<?php } ?>
			</ul>

			<?php if ( 'yes' == $this->get_instance_value( 'tabs_dropdown' ) ) { ?>
				<div class="social-blog-post-dropdown-filters">
					<div class="social-blog-post-dropdown-filters-button"><?php echo ( 'All' == $all || '' == $all ) ? __( 'All', 'social-elementor' ) : $all; ?><i class="fa fa-angle-down"></i></div>

					<ul class="social-blog-post-dropdown-filters-list social-blog-post-header-filters">
						<li class="social-blog-post-dropdown-filters-item social-blog-post-header-filter social-blog-post-active-filter" data-filter="*"><?php echo ( 'All' == $all || '' == $all ) ? __( 'All', 'social-elementor' ) : $all; ?></li>
						<?php foreach ( $filters as $key => $value ) { ?>
						<li class="social-blog-post-dropdown-filters-item social-blog-post-header-filter" data-filter="<?php echo '.' . $value->slug; ?>"><?php echo $value->name; ?></li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Get Search Box HTML.
	 *
	 * Returns the Search Box HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_search() {
		$settings = self::$settings;
		?>
		<div class="social-blog-post-grid-empty">
			<p><?php echo $settings['no_results_text']; ?></p>
			<?php if ( 'yes' == $settings['show_search_box'] ) { ?>
				<?php get_search_form(); ?>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Get Classes array for wrapper class.
	 *
	 * Returns the array for wrapper class.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_wrapper_classes() {

		$classes = [
			'social-blog-post-grid-inner',
			'social-blog-post-cols-' . $this->get_instance_value( 'slides_to_show' ),
			'social-blog-post-cols-tablet-' . $this->get_instance_value( 'slides_to_show_tablet' ),
			'social-blog-post-cols-mobile-' . $this->get_instance_value( 'slides_to_show_mobile' ),
		];

		if ( 'masonry' === $this->get_instance_value( 'post_structure' ) ) {
			$classes[] = 'social-blog-post-masonry';
		}

		if ( 'infinite' === $this->get_instance_value( 'pagination' ) ) {
			$classes[] = 'social-blog-post-infinite-scroll';
			$classes[] = 'social-blog-post-infinite__event-' . $this->get_instance_value( 'infinite_event' );
		}

		return apply_filters( 'social_posts_wrapper_classes', $classes );
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

		$classes = [
			'social-blog-post-terms-position-' . $this->get_instance_value( 'terms_position' ),
			'social-blog-post-img-' . $this->get_instance_value( 'image_position' ),
			'social-blog-post-grid-layout',
			'social-blog-posts',
		];

		return apply_filters( 'social_posts_outer_wrapper_classes', $classes );
	}

	/**
	 * Get body.
	 *
	 * Returns body.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_body() {

		global $post;

		$settings      = self::$settings;
		$query         = self::$query;
		$skin          = self::$skin;
		$count         = 0;
		$is_featured   = false;
		$wrapper       = $this->get_wrapper_classes();
		$outer_wrapper = $this->get_outer_wrapper_classes();
		$structure     = $this->get_instance_value( 'post_structure' );
		$layout        = '';
		$page_id       = '';

		if ( null != \Elementor\Plugin::$instance->documents->get_current() ) {
			$page_id = \Elementor\Plugin::$instance->documents->get_current()->get_main_id();
		}

		if ( 'masonry' == $structure ) {

			$layout = 'masonry';
		}

		$this->add_render_attribute( 'wrapper', 'class', $wrapper );
		$this->add_render_attribute( 'outer_wrapper', 'class', $outer_wrapper );
		$this->add_render_attribute( 'outer_wrapper', 'data-query-type', $settings['query_type'] );
		$this->add_render_attribute( 'outer_wrapper', 'data-structure', $structure );
		$this->add_render_attribute( 'outer_wrapper', 'data-layout', $layout );
		$this->add_render_attribute( 'outer_wrapper', 'data-page', $page_id );
		$this->add_render_attribute( 'outer_wrapper', 'data-skin', $skin );

		if (
			'yes' == $this->get_instance_value( 'default_filter_switch' ) &&
			'' != $this->get_instance_value( 'default_filter' )
		) {
			$this->add_render_attribute( 'outer_wrapper', 'data-default-filter', $this->get_instance_value( 'default_filter' ) );
		}

		?>

		<?php do_action( 'social_posts_before_outer_wrapper', $settings ); ?>

		<div <?php echo $this->get_render_attribute_string( 'outer_wrapper' ); ?> <?php echo $this->get_slider_attr(); ?>>

			<?php do_action( 'social_posts_before_wrapper', $settings ); ?>
				<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php

			while ( $query->have_posts() ) {

				$is_featured = false;

				if ( 0 == $count && 'featured' === $this->get_instance_value( 'post_structure' ) ) {
					$is_featured = true;
				}

				$query->the_post();

				include SOCIAL_ELEMENTOR_MODULE_DIR . 'blog/templates/content-post-' . $skin . '.php';

				$count++;
			}

			wp_reset_postdata();
			?>
				</div>
			<?php if ( 'infinite' == $this->get_instance_value( 'pagination' ) ) { ?>
			<div class="social-blog-posts-infinite-loader">
				<div class="social-blog-post-infinity-1"></div>
				<div class="social-blog-post-infinity-2"></div>
				<div class="social-blog-post-infinity-3"></div>
			</div>
			<?php } ?>
			<?php do_action( 'social_posts_after_wrapper', $settings ); ?>

		</div>

		<?php do_action( 'social_posts_after_outer_wrapper', $settings ); ?>
		<?php
	}

	/**
	 * Render output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @param string $style Skin ID.
	 * @param array  $settings Settings Object.
	 * @param string $node_id Node ID.
	 * @since 1.7.0
	 * @access public
	 */
	public function render( $style, $settings, $node_id ) {

		self::$settings  = $settings;
		self::$skin      = $style;
		self::$node_id   = $node_id;
		self::$query_obj = new Build_Post_Query( $style, $settings, '' );

		self::$query_obj->query_posts();

		self::$query = self::$query_obj->get_query();

		// Get search box.
		if ( ! self::$query->have_posts() ) {

			$this->render_search();
			return;
		}

		?>
		<div class="social-blog-post-header">
			<?php $this->get_header(); ?>
		</div>
		<div class="social-blog-post-body">
			<?php $this->get_body(); ?>
		</div>
		<div class="social-blog-post-footer">
			<?php $this->get_footer(); ?>
		</div>
		<?php
	}

	/**
	 * Render settings array for selected skin
	 *
	 * @since 1.7.0
	 * @param string $control_base_id Skin ID.
	 * @access public
	 */
	public function get_instance_value( $control_base_id ) {
		if ( isset( self::$settings[ self::$skin . '_' . $control_base_id ] ) ) {
			return self::$settings[ self::$skin . '_' . $control_base_id ];
		} else {
			return null;
		}
	}

	/**
	 * Add render attribute.
	 *
	 * Used to add attributes to a specific HTML element.
	 *
	 * The HTML tag is represented by the element parameter, then you need to
	 * define the attribute key and the attribute key. The final result will be:
	 * `<element attribute_key="attribute_value">`.
	 *
	 * Example usage:
	 *
	 * `$this->add_render_attribute( 'wrapper', 'class', 'custom-widget-wrapper-class' );`
	 * `$this->add_render_attribute( 'widget', 'id', 'custom-widget-id' );`
	 * `$this->add_render_attribute( 'button', [ 'class' => 'custom-button-class', 'id' => 'custom-button-id' ] );`
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element   The HTML element.
	 * @param array|string $key       Optional. Attribute key. Default is null.
	 * @param array|string $value     Optional. Attribute value. Default is null.
	 * @param bool         $overwrite Optional. Whether to overwrite existing
	 *                                attribute. Default is false, not to overwrite.
	 *
	 * @return Element_Base Current instance of the element.
	 */
	public function add_render_attribute( $element, $key = null, $value = null, $overwrite = false ) {
		if ( is_array( $element ) ) {
			foreach ( $element as $element_key => $attributes ) {
				$this->add_render_attribute( $element_key, $attributes, null, $overwrite );
			}

			return $this;
		}

		if ( is_array( $key ) ) {
			foreach ( $key as $attribute_key => $attributes ) {
				$this->add_render_attribute( $element, $attribute_key, $attributes, $overwrite );
			}

			return $this;
		}

		if ( empty( $this->_render_attributes[ $element ][ $key ] ) ) {
			$this->_render_attributes[ $element ][ $key ] = [];
		}

		settype( $value, 'array' );

		if ( $overwrite ) {
			$this->_render_attributes[ $element ][ $key ] = $value;
		} else {
			$this->_render_attributes[ $element ][ $key ] = array_merge( $this->_render_attributes[ $element ][ $key ], $value );
		}

		return $this;
	}

	/**
	 * Get render attribute string.
	 *
	 * Used to retrieve the value of the render attribute.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element The element.
	 *
	 * @return string Render attribute string, or an empty string if the attribute
	 *                is empty or not exist.
	 */
	public function get_render_attribute_string( $element ) {
		if ( empty( $this->_render_attributes[ $element ] ) ) {
			return '';
		}

		$render_attributes = $this->_render_attributes[ $element ];

		$attributes = [];

		foreach ( $render_attributes as $attribute_key => $attribute_values ) {
			$attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( implode( ' ', $attribute_values ) ) );
		}

		return implode( ' ', $attributes );
	}

	/**
	 * Render post HTML via AJAX call.
	 *
	 * @param array|string $style_id  The style ID.
	 * @param array|string $widget    Widget object.
	 * @since 1.7.0
	 * @access public
	 */
	public function inner_render( $style_id, $widget ) {

		ob_start();

		$category = ( isset( $_POST['category'] ) ) ? sanitize_key( $_POST['category'] ) : '';

		self::$settings  = $widget->get_settings();
		self::$query_obj = new Build_Post_Query( $style_id, self::$settings, $category );
		self::$query_obj->query_posts();
		self::$query = self::$query_obj->get_query();
		self::$skin  = $style_id;
		$query       = self::$query;
		$settings    = self::$settings;
		$is_featured = false;
		$count       = 0;

		while ( $query->have_posts() ) {

			$is_featured = false;

			if ( 0 == $count && 'featured' === $this->get_instance_value( 'post_structure' ) ) {
				$is_featured = true;
			}

			$query->the_post();
			include SOCIAL_ELEMENTOR_MODULE_DIR . 'blog/templates/content-post-' . $style_id . '.php';

			$count++;
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Render post pagination HTML via AJAX call.
	 *
	 * @param array|string $style_id  The style ID.
	 * @param array|string $widget    Widget object.
	 * @since 1.7.0
	 * @access public
	 */
	public function page_render( $style_id, $widget ) {

		ob_start();

		$category = ( isset( $_POST['category'] ) ) ? sanitize_key( $_POST['category'] ) : '';

		self::$settings  = $widget->get_settings();
		self::$query_obj = new Build_Post_Query( $style_id, self::$settings, $category );
		self::$query_obj->query_posts();
		self::$query = self::$query_obj->get_query();
		self::$skin  = $style_id;
		$query       = self::$query;
		$settings    = self::$settings;
		$is_featured = false;

		return ob_get_clean();
	}
}
