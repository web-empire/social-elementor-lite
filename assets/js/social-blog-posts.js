( function( $ ) {

	"use strict";

	var loadStatus = true;
	var count = 1;
	var loader = '';
	var total = 0;

	function _equal_height( slider_wrapper ) {

		var post_wrapper = slider_wrapper.find('.social-blog-post-wrapper'),
            post_active = slider_wrapper.find('.slick-active'),
            max_height = -1,
            wrapper_height = -1,
            equal_height = slider_wrapper.data( 'equal-height' ),
            post_active_height = -1;

        if ( 'yes' != equal_height ) {
        	return;
        }

        post_active.each( function( i ) {

            var this_height = $( this ).outerHeight(),
                blog_post = $( this ).find( '.social-blog-post-bg-wrapper' ),
                blog_post_height = blog_post.outerHeight();

            if( max_height < blog_post_height ) {
                max_height = blog_post_height;
                post_active_height = max_height + 15;
            }

            if ( wrapper_height < this_height ) {
                wrapper_height = this_height
            }
        });

        post_active.each( function( i ) {
            var selector = $( this ).find( '.social-blog-post-bg-wrapper' );
            selector.animate({ height: max_height }, { duration: 200, easing: 'linear' });
        });

        slider_wrapper.find('.slick-list.draggable').animate({ height: post_active_height }, { duration: 200, easing: 'linear' });

        max_height = -1;
        wrapper_height = -1;

        post_wrapper.each(function() {

            var $this = jQuery( this ),
                selector = $this.find( '.social-blog-post-bg-wrapper' ),
                blog_post = $this.find( '.social-blog-post-inner-wrapper' ),
                blog_post_height = selector.outerHeight();

            if ( $this.hasClass('slick-active') ) {
                return true;
            }

            selector.css( 'height', blog_post_height );
        });

	}

	var WidgetSocialPostGridHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope ) {
			return;
		}

		var selector = $scope.find( '.social-blog-post-grid-inner' );

		loader = $scope.find( '.social-blog-posts-infinite-loader' );

		var $tabs_dropdown = $scope.find('.social-blog-post-dropdown-filters-list');

		if ( selector.length < 1 ) {
			return;
		}

		$('html').click(function() {
			$tabs_dropdown.removeClass( 'show-list' );
		});

		$scope.on( 'click', '.social-blog-post-dropdown-filters-button', function(e) {
			e.stopPropagation();
			$tabs_dropdown.addClass( 'show-list' );
		});

		var layout = $scope.find( '.social-blog-post-grid-layout' ).data( 'layout' ),
			structure = $scope.find( '.social-blog-post-grid-layout' ).data( 'structure' );

		if ( 'masonry' == structure ) {

			$scope.imagesLoaded( function(e) {

				selector.isotope({
					layoutMode: layout,
					itemSelector: '.social-blog-post-wrapper',
				});

			});
		}

		$scope.find( '.social-blog-post-header-filter' ).off( 'click' ).on( 'click', function() {
			$( this ).siblings().removeClass( 'social-blog-post-active-filter' );
			$( this ).addClass( 'social-blog-post-active-filter' );
			count = 1;

			_SocialBlogPostAjax( $scope, $( this ) );

		});

		if ( $scope.find( '.social-blog-post-header' ).children().length > 0 ) {

			var default_filter = $scope.find( '.social-blog-post-grid-layout' ).data( 'default-filter' );
			var hashval 	   = window.location.hash;
			var cat_id 		   = hashval.split( '#' ).pop();
			var cat_filter 	   = $scope.find( '.social-blog-post-header-filters' );


			if( '' !== cat_id ) {

				$scope.find( '.social-blog-post-header-filter' ).each( function( key, value ) {
					var current_filter = $( this ).attr('data-filter');
					if ( cat_id == current_filter.split('.').join("") ) {
						$( this ).trigger( 'click' );
					}
				});
			}

			if ( 'undefined' != typeof default_filter && '' != default_filter ) {

				$scope.find( '.social-blog-post-header-filter' ).each( function( key, value ) {
					
					if ( default_filter == $( this ).html() ) {
						$( this ).trigger( 'click' );
					}
				} );
			}
		}

		if ( 'carousel' == structure ) {

			var slider_wrapper 	= $scope.find( '.social-blog-post-grid-layout' ),
				slider_selector = slider_wrapper.find( '.social-blog-post-grid-inner' ),
				slider_options 	= slider_wrapper.data( 'post_slider' );

			$scope.imagesLoaded( function() {

				slider_selector.slick( slider_options );
				_equal_height( slider_wrapper );
			});

			slider_wrapper.on( 'afterChange', function() {
				_equal_height( slider_wrapper );
			} );


			$( window ).resize(function() {
				$( "#log" ).append( "<div>Handler for .resize() called.</div>" );
			});
		}

		if ( selector.hasClass( 'social-blog-post-infinite-scroll' ) && selector.hasClass( 'social-blog-post-infinite__event-scroll' ) ) {

			if ( 'main' == $scope.find( '.social-blog-post-grid-layout' ).data( 'query-type' ) ) {
				return;
			}

			var windowHeight50 = jQuery( window ).outerHeight() / 1.25;

			$( window ).scroll( function () {

				if( elementorFrontend.isEditMode() ) {
					loader.show();
					return false;
				}


				if( ( $( window ).scrollTop() + windowHeight50 ) >= ( $scope.find( '.social-blog-post-wrapper:last' ).offset().top ) ) {

					var $args = {
						'page_id' : $scope.find( '.social-blog-post-grid-layout' ).data('page'),
						'widget_id' : $scope.data( 'id' ),
						'filter' : $scope.find( '.social-blog-post-active-filter' ).data( 'filter' ),
						'skin' : $scope.find( '.social-blog-post-grid-layout' ).data( 'skin' ),
						'page_number' : $scope.find( '.social-blog-post-grid-pagination .current' ).next( 'a' ).html()
					};

					total = $scope.find( '.social-blog-post-footer .social-blog-post-grid-pagination' ).data( 'total' );

					if( true == loadStatus ) {

						if ( count < total ) {
							loader.show();
							_callAjax( $scope, $args, true );
							count++;
							loadStatus = false;
						}

					}
				}
			} );
		}

	}

	$( document ).on( 'click', '.social-blog-posts-loader', function( e ) {

		$scope = $( this ).closest( '.elementor-widget-social-blog-posts' );

		if ( 'main' == $scope.find( '.social-blog-post-grid-layout' ).data( 'query-type' ) ) {
			return;
		}

		e.preventDefault();

		if( elementorFrontend.isEditMode() ) {
			loader.show();
			return false;
		}

		var $args = {
			'page_id' : $scope.find( '.social-blog-post-grid-layout' ).data('page'),
			'widget_id' : $scope.data( 'id' ),
			'filter' : $scope.find( '.social-blog-post-active-filter' ).data( 'filter' ),
			'skin' : $scope.find( '.social-blog-post-grid-layout' ).data( 'skin' ),
			'page_number' : ( count + 1 )
		};

		total = $scope.find( '.social-blog-post-footer .social-blog-post-grid-pagination' ).data( 'total' );

		if( true == loadStatus ) {

			if ( count < total ) {
				loader.show();
				$( this ).hide();
				_callAjax( $scope, $args, true );
				count++;
				loadStatus = false;
			}

		}
	} );

	$( 'body' ).delegate( '.social-blog-post-grid-pagination .page-numbers', 'click', function( e ) {

		$scope = $( this ).closest( '.elementor-widget-social-blog-posts' );

		if ( 'main' == $scope.find( '.social-blog-post-grid-layout' ).data( 'query-type' ) ) {
			return;
		}

		e.preventDefault();

		$scope.find( '.social-blog-post-grid-layout .social-blog-post-wrapper' ).last().after( '<div class="social-blog-post-loader"><div class="social-blog-loader"></div><div class="social-blog-loader-overlay"></div></div>' );

		var page_number = 1;
		var curr = parseInt( $scope.find( '.social-blog-post-grid-pagination .page-numbers.current' ).html() );

		if ( $( this ).hasClass( 'next' ) ) {
			page_number = curr + 1;
		} else if ( $( this ).hasClass( 'prev' ) ) {
			page_number = curr - 1;
		} else {
			page_number = $( this ).html();
		}

		$scope.find( '.social-blog-post-grid-layout .social-blog-post-wrapper' ).last().after( '<div class="social-blog-post-loader"><div class="social-blog-loader"></div><div class="social-blog-loader-overlay"></div></div>' );

		var $args = {
			'page_id' : $scope.find( '.social-blog-post-grid-layout' ).data('page'),
			'widget_id' : $scope.data( 'id' ),
			'filter' : $scope.find( '.social-blog-post-active-filter' ).data( 'filter' ),
			'skin' : $scope.find( '.social-blog-post-grid-layout' ).data( 'skin' ),
			'page_number' : page_number
		};

		$('html, body').animate({
			scrollTop: ( ( $scope.find( '.social-blog-post-body' ).offset().top ) - 30 )
		}, 'slow');

		_callAjax( $scope, $args );

	} );

	var _SocialBlogPostAjax = function( $scope, $this ) {

		$scope.find( '.social-blog-post-grid-layout .social-blog-post-wrapper' ).last().after( '<div class="social-blog-post-loader"><div class="social-blog-loader"></div><div class="social-blog-loader-overlay"></div></div>' );

		var $args = {
			'page_id' : $scope.find( '.social-blog-post-grid-layout' ).data('page'),
			'widget_id' : $scope.data( 'id' ),
			'filter' : $this.data( 'filter' ),
			'skin' : $scope.find( '.social-blog-post-grid-layout' ).data( 'skin' ),
			'page_number' : 1
		};

		_callAjax( $scope, $args );
	}

	var _callAjax = function( $scope, $obj, $append ) {

		$.ajax({
			url: social_elementor.ajax_url,
			data: {
				action: 'social_elementor_get_blog_posts',
				page_id : $obj.page_id,
				widget_id: $obj.widget_id,
				category: $obj.filter,
				skin: $obj.skin,
				page_number : $obj.page_number
			},
			dataType: 'json',
			type: 'POST',
			success: function( data ) {

				$scope.find( '.social-blog-post-loader' ).remove();

				var sel = $scope.find( '.social-blog-post-grid-inner' );


				if( 'news' == $obj.skin ) {
					sel = $scope.find( '.social-blog-post-grid-layout' );
				}

				if ( true == $append ) {

					var html_str = data.data.html;
					html_str = html_str.replace( 'social-blog-post-wrapper-featured', '' );
					sel.append( html_str );
				} else {
					sel.html( data.data.html );
				}

				$scope.find( '.social-blog-post-footer' ).html( data.data.pagination );

				var layout = $scope.find( '.social-blog-post-grid-layout' ).data( 'layout' ),
					structure = $scope.find( '.social-blog-post-grid-layout' ).data( 'structure' );
					selector = $scope.find( '.social-blog-post-grid-inner' );

				if (
					( 'normal' == structure || 'masonry' == structure ) &&
					'' != layout
				) {

					$scope.imagesLoaded( function() {
						selector.isotope( 'destroy' );
						selector.isotope({
							layoutMode: layout,
							itemSelector: '.social-blog-post-wrapper',
						});
					});
				}

				//	Complete the process 'loadStatus'
				loadStatus = true;
				if ( true == $append ) {
					loader.hide();
					$scope.find( '.social-blog-posts-loader' ).show();
				}

				if( count == total ) {
					$scope.find( '.social-blog-posts-loader' ).hide();
				}
			}
		});
	}

	$( window ).on( 'elementor/frontend/init', function () {

		elementorFrontend.hooks.addAction( 'frontend/element_ready/social-blog-posts.classic', WidgetSocialPostGridHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/social-blog-posts.event', WidgetSocialPostGridHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/social-blog-posts.card', WidgetSocialPostGridHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/social-blog-posts.feed', WidgetSocialPostGridHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/social-blog-posts.news', WidgetSocialPostGridHandler );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/social-blog-posts.business', WidgetSocialPostGridHandler );

	});

} )( jQuery );
