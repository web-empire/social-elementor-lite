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
				'Blog'            => array(
					'slug'      => 'social-blog-posts',
					'title'     => esc_html__( 'Blog' , 'social-elementor' ),
					'keywords'  => [ 'social-elementor', 'post', 'grid', 'masonry', 'carousel', 'content grid', 'content' ],
					'icon'      => 'social-icon-post-grid',
					'title_url' => 'https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin',
					'doc_url'	=> 'https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin',
					'default'   => true,
					'is_pro'	=> false,
					'features'	=> array (
						'first'		=>	__( '<b class="feature"> 01. </b> 3+ Skin Layouts', 'social-elementor' ),
						'second'	=>	__( '<b class="feature"> 02. </b> Custom Post Query', 'social-elementor' ),
						'third'		=>	__( '<b class="feature"> 03. </b> Grid, Masonry Effect', 'social-elementor' ),
						'fourth'	=>	__( '<b class="feature"> 04. </b> Post Sorting Feature', 'social-elementor' ),
						'fifth'		=>	__( '<b class="feature"> 05. </b> Multiple Design Options', 'social-elementor' ),
						'sixth'		=>	__( '<b class="feature"> 06. </b> Meta, Excerpt, CTA Support', 'social-elementor' ),
						'seventh'	=>	__( '<b class="feature"> </b> And Much More... <a href="https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin" target="_blank"> See Here » </a>', 'social-elementor' ),
					),
				),
				'Blog-Pro'      => array(
					'slug'      => 'social-blog-pro',
					'title'     => esc_html__( 'Blog Pro', 'social-elementor' ),
					'title_url' => 'https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin',
					'doc_url'	=> 'https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin',
					'default'   => true,
					'is_pro'	=> true,
					'features'	=> array (
						'first'		=>	__( '<b class="feature"> 01. </b> Infinite Load Event', 'social-elementor' ),
						'second'	=>	__( '<b class="feature"> 02. </b> Pagination Feasibility', 'social-elementor' ),
						'third'		=>	__( '<b class="feature"> 03. </b> Filterable Tabs Options', 'social-elementor' ),
						'fourth'	=>	__( '<b class="feature"> 04. </b> Advanced Skin Layouts', 'social-elementor' ),
						'fifth'		=>	__( '<b class="feature"> 05. </b> Infinite Scroll / Button AJAX Events', 'social-elementor' ),
						'sixth'		=>	__( '<b class="feature"> 06. </b> Advanced and Extra Customization options', 'social-elementor' ),
						'seventh'	=>	__( '<b class="feature"> </b> And Much More... <a href="https://webempire.org.in/docs/blog-elementor-widget/?utm_source=google&utm_medium=social-post&utm_campaign=social-elementor-plugin" target="_blank"> See Here » </a>', 'social-elementor' ),
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
			'social-frontend-script'  => array(
				'path'      => 'assets/' . $folder . '/social-frontend' . $suffix . '.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
			'social-elementor-cookie-lib'       => array(
				'path'      => 'assets/' . $folder . '/js_cookie' . $suffix . '.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
			'social-blog-posts'            => array(
				'path'      => 'assets/' . $folder . '/social-blog-posts' . $suffix . '.js',
				'dep'       => [ 'jquery', 'imagesloaded' ],
				'in_footer' => true,
			),
			/* Libraries */
			'social-elementor-element-resize'   => array(
				'path'      => 'assets/lib/jquery-element-resize/jquery_resize.min.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
			'social-elementor-isotope'          => array(
				'path'      => 'assets/lib/isotope/isotope.min.js',
				'dep'       => [ 'jquery' ],
				'in_footer' => true,
			),
			'social-elementor-infinitescroll'   => array(
				'path'      => 'assets/lib/infinitescroll/jquery.infinitescroll.min.js',
				'dep'       => [ 'jquery' ],
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
				'social-blog-post'             => array(
					'path' => 'assets/css/modules/post.css',
					'dep'  => [],
				),
				'social-blog-post-card'        => array(
					'path' => 'assets/css/modules/post-card.css',
					'dep'  => [],
				),
				'social-blog-post-event'       => array(
					'path' => 'assets/css/modules/post-event.css',
					'dep'  => [],
				),
				'social-blog-post-feed'        => array(
					'path' => 'assets/css/modules/post-feed.css',
					'dep'  => [],
				),
				'social-blog-post-news'        => array(
					'path' => 'assets/css/modules/post-news.css',
					'dep'  => [],
				),
				'social-blog-post-carousel'    => array(
					'path' => 'assets/css/modules/post-carousel.css',
					'dep'  => [],
				),
				'social-blog-post-business'    => array(
					'path' => 'assets/css/modules/post-business.css',
					'dep'  => [],
				),
				'social-blog-post-common'           => array(
					'path' => 'assets/css/modules/common.css',
					'dep'  => [],
				),
			);
		} else {
			$css_files = array(
				'social-frontend' => array(
					'path' => 'assets/min-css/social-frontend.min.css',
					'dep'  => [],
				),
			);
		}

		if ( is_rtl() ) {
			$css_files = array(
				'social-frontend' => array(
					// This is autogenerated rtl file.
					'path' => 'assets/min-css/social-frontend-rtl.min.css',
					'dep'  => [],
				),
			);
		}

		return $css_files;
	}
}
