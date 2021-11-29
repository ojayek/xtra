! function( $ ) {
	"use strict";

	Codevz_Plus.before_after = function() {

		$( '.cz_image_container' ).codevzPlus( 'b4a', function( x ) {

			var de 		= x.find( '.cz_handle' ), 
				re 		= x.find( '.cz_resize_img' ),
				cz_1 	= 'mousedown vmousedown touchstart', 
				cz 		= 'mousemove vmousemove touchmove',
				cz_2 	= 'mouseup vmouseup touchend',
				pageX, lv, wv;

			de.on( cz_1, function( e ) {

				pageX = ( e.type == 'touchstart' ) ? e.originalEvent.touches[0].pageX : e.pageX;

				de.addClass( 'draggable' );
				re.addClass( 'resizable' );

				var dw = de.outerWidth(),
					xp = de.offset().left + dw - pageX,
					co = x.offset().left,
					cw = x.outerWidth(),
					minLeft = co + 10,
					maxLeft = co + cw - dw - 10;

				de.parents().on( cz, function( e ) {

					pageX = ( e.type == 'touchmove' ) ? e.originalEvent.touches[0].pageX : e.pageX, 
					lv = pageX + xp - dw;

					if ( lv < minLeft ) {
						lv = minLeft;
					} else if ( lv > maxLeft) {
						lv = maxLeft;
					}

					wv = (lv + dw/2 - co)*100/cw+'%';

					x.find( '.draggable' ).css('left', wv).on( cz_2, function() {
						$(this).removeClass( 'draggable' );
						re.removeClass( 'resizable' );
						de.parents().off( cz );
					});

					x.find( '.resizable' ).css('width', wv); 

				}).on( cz_2, function(e){
					de.removeClass( 'draggable' );
					re.removeClass( 'resizable' );
					de.parents().off( cz ).off( cz_2 );
				});

				e.preventDefault();

			}).on( cz_2, function(e) {

				de.removeClass( 'draggable' );
				re.removeClass( 'resizable' );
				de.parents().off( cz );

			});

		});

	};

	Codevz_Plus.before_after();

}( jQuery );