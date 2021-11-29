! function( $ ) {
	"use strict";

	Codevz_Plus.inline_video = function( wpb ) {

		// Reinit lightGallery in page builder.
		wpb && Codevz_Plus.lightGallery( $( '.cz_video_popup' ) );

		// Inline video.
		$( '.cz_video_inline' ).codevzPlus( 'video', function( x ) {

			x.find( 'a' ).on( 'click', function( e ) {

				if ( ! x.find( 'iframe' ).length ) {

					var url = $( this ).attr( 'href' ),
						src = url.substr( url.indexOf( "=" ) + 1 ),
						src = url.indexOf( "youtube" ) > 0 ? 'https://www.youtube-nocookie.com/embed/' + src + '?autoplay=1&amp;rel=0&amp;showinfo=0&amp;mute=1' : 'https://player.vimeo.com/video/' + url.match( /\d+/ ) + '?autoplay=1',
						src = url.indexOf( "youtube" ) > 0 || url.indexOf( "vimeo" ) > 0 ? src : url,
						iframe = '<iframe src="' + src + '" allowfullscreen></iframe>';

					$( this ).fadeOut( 'fast' ).css( 'position','absolute' );

					x.append( iframe ).find( 'iframe' ).css(
						{
							'position': 'relative',
							'width': '100%',
							'height': x.find( 'img' ).height()
						}
					);

				}

				if ( ! $( this ).parent().find('.close_inline_video').length ) {

					x.append('<i class="fa fa-remove close_inline_video"></i>');

					$( '.close_inline_video' ).on('click', function( e ) {
						$( this ).parent().find('iframe').detach();
						$( this ).parent().find('a').fadeIn( 'fast' ).css('position','relative');
						$( this ).detach();
					});

				}

				e.preventDefault();

			});

		});

	};

	Codevz_Plus.inline_video();

}( jQuery );