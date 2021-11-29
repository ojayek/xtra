! function( $ ) {
	"use strict";

	Codevz_Plus.image_zoom = function() {

		$( document.body ).on( 'mouseenter', '.cz_image_hover_zoom a', function() {

			var x = $( this );

			if ( ! x.find( '.cz_img_for_zoom' ).length ) {

				x.find( '.cz_img_for_zoom' ).detach();
				x.find( 'img' ).addClass( 'cz_dimg' );
				x.append('<img class="cz_img_for_zoom" src="' + x.attr( 'href' ) + '" />');

			}

			x.find( '.cz_img_for_zoom' ).fadeIn( 'fast' );

		}).on( 'mouseleave', '.cz_image_hover_zoom a', function() {

			$( this ).find( '.cz_img_for_zoom' ).fadeOut( 'fast' );

		}).on( 'mousemove', '.cz_image_hover_zoom a', function( e ) {

			var big = $( this ).find( '.cz_img_for_zoom' ),
				ey = e.pageY - $( e.currentTarget ).offset().top,
				ex = e.pageX - $( e.currentTarget ).offset().left,
				ii = $( this ).find( '.cz_dimg' ),
				yy = ( big.height() - ii.height() ) / ii.height(),
				xx = ( big.width() - ii.width() ) / ii.width();

			big.css({
				'top': - ( ey * yy ),
				'left': - ( ex * xx )
			});

		});

	};

	Codevz_Plus.image_zoom();

}( jQuery );