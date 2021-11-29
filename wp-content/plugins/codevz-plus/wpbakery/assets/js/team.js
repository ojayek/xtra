! function( $ ) {
	"use strict";

	Codevz_Plus.team = function() {

		$( document.body ).on( 'mouseenter mousemove mouseleave', '.cz_team_6, .cz_team_7', function( e ) {

			if ( e.type === 'mouseleave' ) {

				$( this ).find( '.cz_team_content' ).fadeOut( 100 );

			} else {

				$( this ).find( '.cz_team_content' ).fadeIn( 100 ).css(
					{
						top: e.offsetY + 30,
						left: e.offsetX
					}
				);

			}

		});

	};

	Codevz_Plus.team();

}( jQuery );