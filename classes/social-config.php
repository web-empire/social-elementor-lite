<?php
/**
 * Social Elementor Config.
 *
 * @package SOCIAL_ELEMENTOR
 */

namespace SocialElementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use SocialElementor\Classes\Social_Helper;

/**
 * Class Social_Config.
 */
class Social_Config {


	/**
	 * Widget List
	 *
	 * @var widget_list
	 */
	public static $widget_list = null;

	/**
	 * Get Widget List.
	 *
	 * @since 1.0.0
	 *
	 * @return array The Widget List.
	 */
	public static function get_widget_list() {
		if ( null === self::$widget_list ) {
			self::$widget_list = array(
				'Blog'                     => array(
					'slug'      => 'social-blog-posts',
					'title'     => esc_html__( 'Blog', 'social-elementor' ),
					'keywords'  => array( 'social-elementor', 'post', 'grid', 'masonry', 'carousel', 'content grid', 'content' ),
					'icon'      => 'social-icon-post-grid',
					'title_url' => 'https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin',
					'doc_url'   => 'https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin',
					'default'   => true,
					'is_pro'    => false,
					'features'  => array(
						'first'   => __( '<b class="feature"> 01. </b> 3+ Skin Layouts', 'social-elementor' ),
						'second'  => __( '<b class="feature"> 02. </b> Custom Post Query', 'social-elementor' ),
						'third'   => __( '<b class="feature"> 03. </b> Grid, Masonry Effect', 'social-elementor' ),
						'fourth'  => __( '<b class="feature"> 04. </b> Post Sorting Feature', 'social-elementor' ),
						'fifth'   => __( '<b class="feature"> 05. </b> Multiple Design Options', 'social-elementor' ),
						'sixth'   => __( '<b class="feature"> 06. </b> Meta, Excerpt, CTA Support', 'social-elementor' ),
						'seventh' => __( '<b class="feature"> </b> And Much More... <a href="https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin" target="_blank"> See Here » </a>', 'social-elementor' ),
					),
				),
				'Icons'                    => array(
					'slug'      => 'social-icons',
					'title'     => esc_html__( 'Social Icons', 'social-elementor' ),
					'keywords'  => array( 'social-icons', 'icons', 'social' ),
					'icon'      => 'social-icon-post-grid',
					'title_url' => '#',
					'doc_url'   => '#',
					'default'   => true,
					'is_pro'    => false,
					'features'  => array(
						'first'   => __( '<b class="feature"> 01. </b> Choose Icon & Text Combinations', 'social-elementor' ),
						'second'  => __( '<b class="feature"> 02. </b> Official / Custom Color Styles', 'social-elementor' ),
						'third'   => __( '<b class="feature"> 03. </b> Separator in Between 2 Social', 'social-elementor' ),
						'fourth'  => __( '<b class="feature"> 04. </b> Hover Animations', 'social-elementor' ),
						'fifth'   => __( '<b class="feature"> 05. </b> Custom Stylings', 'social-elementor' ),
						'sixth'   => __( '<b class="feature"> 06. </b> Sticky Effect', 'social-elementor' ),
						'seventh' => __( '<b class="feature"> </b> And Much More... <a href="#" target="_blank"> See Here » </a>', 'social-elementor' ),
					),
				),
				'Blog_Pro'                 => array(
					'slug'      => 'social-blog-pro',
					'title'     => esc_html__( 'Blog Pro', 'social-elementor' ),
					'title_url' => 'https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin',
					'doc_url'   => 'https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin',
					'default'   => true,
					'is_pro'    => true,
					'features'  => array(
						'first'   => __( '<b class="feature"> 01. </b> Infinite Load Event', 'social-elementor' ),
						'second'  => __( '<b class="feature"> 02. </b> Pagination Feasibility', 'social-elementor' ),
						'third'   => __( '<b class="feature"> 03. </b> Filterable Tabs Options', 'social-elementor' ),
						'fourth'  => __( '<b class="feature"> 04. </b> Advanced Skin Layouts', 'social-elementor' ),
						'fifth'   => __( '<b class="feature"> 05. </b> Infinite Scroll / Button AJAX Events', 'social-elementor' ),
						'sixth'   => __( '<b class="feature"> 06. </b> Advanced and Extra Customization options', 'social-elementor' ),
						'seventh' => __( '<b class="feature"> </b> And Much More... <a href="https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin" target="_blank"> See Here » </a>', 'social-elementor' ),
					),
				),
				'Social_Business_Reviews'  => array(
					'slug'      => 'social-business-reviews',
					'title'     => esc_html__( 'Business Reviews', 'social-elementor' ),
					'title_url' => 'https://webempire.org.in/docs/social-business-reviews-widget/?utm_source=google&utm_medium=social-business-reviews&utm_campaign=social-elementor-plugin',
					'doc_url'   => 'https://webempire.org.in/docs/social-business-reviews-widget/?utm_source=google&utm_medium=social-business-reviews&utm_campaign=social-elementor-plugin',
					'default'   => true,
					'is_pro'    => true,
					'features'  => array(
						'first'   => __( '<b class="feature"> 01. </b> Google Place, Yelp Reviews', 'social-elementor' ),
						'second'  => __( '<b class="feature"> 02. </b> Built-in Google Schema Support', 'social-elementor' ),
						'third'   => __( '<b class="feature"> 03. </b> 3 Elegant Default Skins', 'social-elementor' ),
						'fourth'  => __( '<b class="feature"> 04. </b> Filterable Reviews Options', 'social-elementor' ),
						'fifth'   => __( '<b class="feature"> 05. </b> Multiple Design Options', 'social-elementor' ),
						'sixth'   => __( '<b class="feature"> 06. </b> Grid, Carousel Support', 'social-elementor' ),
						'seventh' => __( '<b class="feature"> 07. </b> Reviewer Meta Handler', 'social-elementor' ),
						'seventh' => __( '<b class="feature"> </b> And Much More... <a href="https://webempire.org.in/docs/social-business-reviews-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin" target="_blank"> See Here » </a>', 'social-elementor' ),
					),
				),
				'Upcoming_Social_Elements' => array(
					'slug'      => 'social-upcoming-elements',
					'title'     => esc_html__( 'Upcoming Social Elements', 'social-elementor' ),
					'title_url' => 'https://webempire.org.in/our-products/social-add-ons-for-elementor/?utm_source=google&utm_medium=social-business-reviews&utm_campaign=social-elementor-plugin',
					'doc_url'   => 'https://webempire.org.in/our-products/social-add-ons-for-elementor/?utm_source=google&utm_medium=social-business-reviews&utm_campaign=social-elementor-plugin',
					'default'   => true,
					'is_pro'    => true,
					'features'  => array(
						'first'   => __( '<b class="feature"> 01. </b> Social Share', 'social-elementor' ),
						'second'  => __( '<b class="feature"> 02. </b> Facebook Posts', 'social-elementor' ),
						'third'   => __( '<b class="feature"> 03. </b> Instagram Posts', 'social-elementor' ),
						'fourth'  => __( '<b class="feature"> 04. </b> Twitter Posts', 'social-elementor' ),
						'fifth'   => __( '<b class="feature"> 05. </b> Social Feeds', 'social-elementor' ),
						'sixth'   => __( '<b class="feature"> 06. </b> Much More with Improvements', 'social-elementor' ),
						'seventh' => __( '<b class="feature"> 07. </b> Reviewer Meta Handler', 'social-elementor' ),
						'seventh' => __( '<b class="feature"> </b> And Much More... <a href="https://webempire.org.in/contact/?utm_campaign=web-agency&utm_medium=email&utm_source=google" target="_blank"> Send a Suggession » </a>', 'social-elementor' ),
					),
				),
			);
		}

		return self::$widget_list;
	}

	/**
	 * Returns Script array.
	 *
	 * @return array()
	 * @since 1.0.0
	 */
	public static function get_widget_script() {
		$folder = Social_Helper::get_js_folder();
		$suffix = Social_Helper::get_js_suffix();

		$js_files = array(
			'social-frontend-script'          => array(
				'path'      => 'assets/' . $folder . '/social-frontend' . $suffix . '.js',
				'dep'       => array( 'jquery' ),
				'in_footer' => true,
			),
			'social-elementor-cookie-lib'     => array(
				'path'      => 'assets/' . $folder . '/js_cookie' . $suffix . '.js',
				'dep'       => array( 'jquery' ),
				'in_footer' => true,
			),
			'social-blog-posts'               => array(
				'path'      => 'assets/' . $folder . '/social-blog-posts' . $suffix . '.js',
				'dep'       => array( 'jquery', 'imagesloaded' ),
				'in_footer' => true,
			),
			/* Libraries */
			'social-elementor-element-resize' => array(
				'path'      => 'assets/lib/jquery-element-resize/jquery_resize.min.js',
				'dep'       => array( 'jquery' ),
				'in_footer' => true,
			),
			'social-elementor-isotope'        => array(
				'path'      => 'assets/lib/isotope/isotope.min.js',
				'dep'       => array( 'jquery' ),
				'in_footer' => true,
			),
			'social-elementor-infinitescroll' => array(
				'path'      => 'assets/lib/infinitescroll/jquery.infinitescroll.min.js',
				'dep'       => array( 'jquery' ),
				'in_footer' => true,
			),
		);

		return $js_files;
	}

	/**
	 * Returns Style array.
	 *
	 * @return array()
	 * @since 1.0.0
	 */
	public static function get_widget_style() {
		$folder = Social_Helper::get_css_folder();
		$suffix = Social_Helper::get_css_suffix();

		if ( Social_Helper::is_script_debug() ) {
			$css_files = array(
				'social-blog-post'       => array(
					'path' => 'assets/css/modules/post.css',
					'dep'  => array(),
				),
				'social-blog-post-card'  => array(
					'path' => 'assets/css/modules/post-card.css',
					'dep'  => array(),
				),
				'social-blog-post-event' => array(
					'path' => 'assets/css/modules/post-event.css',
					'dep'  => array(),
				),
				'social-icons'           => array(
					'path' => 'assets/css/modules/icons.css',
					'dep'  => array(),
				),
				'social-hover-animation' => array(
					'path' => 'assets/css/modules/hover.css',
					'dep'  => array(),
				),
			);
		} else {
			$css_files = array(
				'social-frontend' => array(
					'path' => 'assets/min-css/social-frontend.min.css',
					'dep'  => array(),
				),
			);
		}

		if ( is_rtl() ) {
			$css_files = array(
				'social-frontend' => array(
					// This is autogenerated rtl file.
					'path' => 'assets/min-css/social-frontend-rtl.min.css',
					'dep'  => array(),
				),
			);
		}

		return $css_files;
	}
}
