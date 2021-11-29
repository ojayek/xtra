! function( $ ) {
	"use strict";

	Codevz_Plus.progress_bar = function() {

		$( '.progress_bar' ).codevzPlus( 'pbar', function( x, i ) {

			var dis = $( this ),
				num = $( 'b', dis ).html(),
				per = parseInt( num ).toLocaleString() == 'NaN' ? true : false,
				lan = Codevz_Plus.language( num.replace( '%', '' ) ),
				num = per ? Codevz_Plus.convertNumbers( num, false, lan ) : num,
				del = i * 200;

			if ( $( window ).width() <= 768 ) {

				num = parseInt( num ) + '%';

				dis.addClass( 'done' ).find( 'span' ).css( 'width', num ).find( 'b' ).show().html( num );

				return;

			}

			$( window ).on( 'scroll.cz_progress', function() {

				if ( Codevz_Plus.inview( dis ) && ! dis.hasClass( 'done' ) ) {

					dis.addClass('done').find('span').delay( del ).animate({width: parseInt( num ) + '%'}, 400 );

					$( 'b', dis ).delay( del ).prop( 'Counter', 0 ).animate({ Counter: parseInt( num ) }, {
						duration: 2000,
						easing: 'swing',
						step: function() {
							$( 'b', dis ).show().text( Codevz_Plus.convertNumbers( Math.ceil( this.Counter ).toLocaleString(), per ? true : false, lan ) + '%' );
						}
					});

				}

				if ( ! $( '.progress_bar:not(.done)' ).length ) {
					$( window ).off( 'scroll.cz_progress' );
				}

			}).trigger( 'scroll.cz_progress' );
		});

		$( '.cz_progress_bar_icon' ).codevzPlus( 'pbar_icon', function() {
		
			var dis = $( this ),
				num = dis.data('number');

			$( window ).on( 'scroll.cz_pbar_icon', function() {
				
				if ( Codevz_Plus.inview( dis ) && ! dis.hasClass( 'done' ) ) {
					dis.addClass('done').find('> div').animate({width: parseInt( num ) + '%'}, 400 );
				}

				if ( ! $( '.cz_progress_bar_icon:not(.done)' ).length ) {
					$( window ).off( 'scroll.cz_pbar_icon' );
				}

			}).trigger( 'scroll.cz_pbar_icon' );

		});

	};

	Codevz_Plus.progress_bar();

}( jQuery );