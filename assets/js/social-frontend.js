( function( $ ) {

	var isElEditMode = false;

	$( window ).on( 'elementor/frontend/init', function () {

		if ( elementorFrontend.isEditMode() ) {
			isElEditMode = true;
		}

		if( isElEditMode ) {

		}
	});

} )( jQuery );
