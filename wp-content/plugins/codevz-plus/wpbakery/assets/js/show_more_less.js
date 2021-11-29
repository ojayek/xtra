! function( $ ) {
	"use strict";

	Codevz_Plus.show_more_less = function() {

		$( '.cz_sml' ).codevzPlus( 'sml', function( x ) {

			var h = x.find( '.cz_sml_inner' ).css( 'height' );

			x.find( '> a' ).on( 'click', function( e ) {

				e.preventDefault();

				x.toggleClass( 'cz_sml_open' ).find( '.cz_sml_inner' ).css(
					{
						'height': x.hasClass( 'cz_sml_open' ) ? x.find( '.cz_sml_inner div:first-child' ).outerHeight( true ) : h
					}
				);

			});

		});

	};

	Codevz_Plus.show_more_less();

}( jQuery );