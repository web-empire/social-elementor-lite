( function( $ ) {

	/**
	 * AJAX Request Queue
	 *
	 * - add()
	 * - remove()
	 * - run()
	 * - stop()
	 *
	 * @since 1.2.0.8
	 */
	var SocialAjaxQueue = (function() {

		var requests = [];

		return {

			/**
			 * Add AJAX request
			 *
			 * @since 1.2.0.8
			 */
			add:  function(opt) {
			    requests.push(opt);
			},

			/**
			 * Remove AJAX request
			 *
			 * @since 1.2.0.8
			 */
			remove:  function(opt) {
			    if( jQuery.inArray(opt, requests) > -1 )
			        requests.splice($.inArray(opt, requests), 1);
			},

			/**
			 * Run / Process AJAX request
			 *
			 * @since 1.2.0.8
			 */
			run: function() {
			    var self = this,
			        oriSuc;

			    if( requests.length ) {
			        oriSuc = requests[0].complete;

			        requests[0].complete = function() {
			             if( typeof(oriSuc) === 'function' ) oriSuc();
			             requests.shift();
			             self.run.apply(self, []);
			        };

			        jQuery.ajax(requests[0]);

			    } else {

			      self.tid = setTimeout(function() {
			         self.run.apply(self, []);
			      }, 1000);
			    }
			},

			/**
			 * Stop AJAX request
			 *
			 * @since 1.2.0.8
			 */
			stop:  function() {

			    requests = [];
			    clearTimeout(this.tid);
			}
		};

	}());

	SocialAdmin = {

		init: function() {
			/**
			 * Run / Process AJAX request
			 */
			SocialAjaxQueue.run();

			$( document ).delegate( ".social-activate-widget", "click", SocialAdmin._activate_widget );
			$( document ).delegate( ".social-deactivate-widget", "click", SocialAdmin._deactivate_widget );

			$( document ).delegate( ".social-activate-all", "click", SocialAdmin._bulk_activate_widgets );
			$( document ).delegate( ".social-deactivate-all", "click", SocialAdmin._bulk_deactivate_widgets );
		},

		/**
		 * Activate All Widgets.
		 */
		_bulk_activate_widgets: function( e ) {
			var button = $( this );

			var data = {
				action: 'social_bulk_activate_widgets',
				nonce: social.ajax_nonce,
			};

			if ( button.hasClass( 'updating-message' ) ) {
				return;
			}
			
			$( button ).addClass('updating-message');

			SocialAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){

					console.log( data );

					// Bulk add or remove classes to all modules.
					$('.social-widget-list').children( "li" ).addClass( 'activate' ).removeClass( 'deactivate' );
					$('.social-widget-list').children( "li" ).find('.social-activate-widget')
						.addClass('social-deactivate-widget')
						.text(social.deactivate)
						.removeClass('social-activate-widget');
						$( button ).removeClass('updating-message');
					}
			});
			e.preventDefault();
		},

		/**
		 * Deactivate All Widgets.
		 */
		_bulk_deactivate_widgets: function( e ) {
			var button = $( this );

			var data = {
				action: 'social_bulk_deactivate_widgets',
				nonce: social.ajax_nonce,
			};

			if ( button.hasClass( 'updating-message' ) ) {
				return;
			}
			$( button ).addClass('updating-message');

			SocialAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){

					console.log( data );
					// Bulk add or remove classes to all modules.
					$('.social-widget-list').children( "li" ).addClass( 'deactivate' ).removeClass( 'activate' );
					$('.social-widget-list').children( "li" ).find('.social-deactivate-widget')
						.addClass('social-activate-widget')
						.text(social.activate)
						.removeClass('social-deactivate-widget');
						$( button ).removeClass('updating-message');
					}
			});
			e.preventDefault();
		},

		/**
		 * Activate Module.
		 */
		_activate_widget: function( e ) {
			var button = $( this ),
				id     = button.parents('li').attr('id');

			var data = {
				module_id : id,
				action: 'social_activate_widget',
				nonce: social.ajax_nonce,
			};

			if ( button.hasClass( 'updating-message' ) ) {
				return;
			}

			$( button ).addClass('updating-message');

			SocialAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){

					// Add active class.
					$( '#' + id ).addClass('activate').removeClass( 'deactivate' );
					// Change button classes & text.
					$( '#' + id ).find('.social-activate-widget')
						.addClass('social-deactivate-widget')
						.text(social.deactivate)
						.removeClass('social-activate-widget')
						.removeClass('updating-message');
					}
			});

			e.preventDefault();
		},

		/**
		 * Deactivate Module.
		 */
		_deactivate_widget: function( e ) {
			var button = $( this ),
				id     = button.parents('li').attr('id');
			var data = {
				module_id: id,
				action: 'social_deactivate_widget',
				nonce: social.ajax_nonce,
			};
			
			if ( button.hasClass( 'updating-message' ) ) {
				return;
			}

			$( button ).addClass('updating-message');

			SocialAjaxQueue.add({
				url: ajaxurl,
				type: 'POST',
				data: data,
				success: function(data){

					// Remove active class.
					$( '#' + id ).addClass( 'deactivate' ).removeClass('activate');
					
					// Change button classes & text.
					$( '#' + id ).find('.social-deactivate-widget')
						.addClass('social-activate-widget')
						.text(social.activate)
						.removeClass('social-deactivate-widget')
						.removeClass('updating-message');
				}
			})
			e.preventDefault();
		}
	}

	$( document ).ready(function() {
		SocialAdmin.init();
	});
	

} )( jQuery ); 