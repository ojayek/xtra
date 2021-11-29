! function( $ ) {
	"use strict";

	Codevz_Plus.google_map = function() {

		var gmap = $( '.gmap' ), timeout;

		if ( gmap.length ) {

			if ( ! $( '#cz_google_map_api_js' ).length ) {

				var sc = document.createElement( 'script' );
				sc.setAttribute( 'id','cz_google_map_api_js' );
				sc.setAttribute( 'src','https://maps.google.com/maps/api/js?key=' + gmap.data( "api-key" ) + '&callback=Codevz_Plus.google_map' );
				document.head.appendChild( sc );

			}

			$( window ).on( 'scroll.xtra_gmap', function() {

				clearTimeout( timeout );

				timeout = setTimeout( function() {

					if ( Codevz_Plus.inview( gmap, 750 ) ) {

						if ( typeof google != 'undefined' ) {

							gmap.not( '.done' ).html( '' ).each( function() {

								var dis = $( this ),
									fnc = 'mapfucntion_' + dis.attr( 'id' ) + '();';

								try {
									eval( fnc );
								} catch( e ) {
									console.log( e );
								}

								dis.addClass( 'done' );

							});

							if ( $( '.gmap.done' ).length === gmap.length ) {
								$( window ).trigger( 'resize' ).off( 'scroll.xtra_gmap' );
							}

						} else {

							$( window ).trigger( 'scroll' );

						}

					}

				}, 100 );

			}).trigger( 'scroll.xtra_gmap' );

		}

	};

	Codevz_Plus.google_map();

}( jQuery );