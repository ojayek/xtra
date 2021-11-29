jQuery( function( $ ) {
	'use strict';

	var h_top_bar = $( '.hidden_top_bar' );

	if ( h_top_bar.length ) {

		h_top_bar.on( 'click', function( e ) {
			e.stopPropagation();
		});

		$( '> i', h_top_bar ).on( 'click', function( e ) {
			$( '.cz_overlay' ).fadeToggle( 'fast' );
			$( this ).toggleClass( 'fa-angle-down fa-angle-up' );
			h_top_bar.toggleClass( 'show_hidden_top_bar' );
			e.stopPropagation();
		});

		$( document.body ).on( 'click', function( e ) {
			if ( $( '.show_hidden_top_bar' ).length ) {
				$( '> i', h_top_bar ).addClass( 'fa-angle-down' ).removeClass( 'fa-angle-up' );
				h_top_bar.removeClass( 'show_hidden_top_bar' );
				$( '.cz_overlay' ).fadeOut( 'fast' );
			}
		});

	}

});