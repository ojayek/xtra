/* Codevz customize controls JS */
( function( $ ) {
  'use strict';

	if ( wp.customize ) {

		wp.customize.bind( 'ready', function() {

			// Section focus.
			wp.customize.previewer.bind( 'xtra-section', function( args ) {

				if ( args.section === 'header_4' ) {
					args.section = 'mobile_header';
				}

				// Focus.
				wp.customize.section( 'codevz_theme_options-' + args.section ).focus();

				// Find element.
				if ( args.index ) {

					var optionName = '#customize-control-codevz_theme_options-',
							accordion = $( optionName + args.section + '_left' );

					if ( args.index.indexOf( 'center' ) >= 0 ) {
						accordion = $( optionName + args.section + '_center' );

					} else if ( args.index.indexOf( 'right' ) >= 0 ) {
						accordion = $( optionName + args.section + '_right' );

					}

					// Open accordion.
					setTimeout( function() {

						var index = parseInt( args.index.replace( /[^0-9]/g, '' ) ),
							title = accordion.find( '.csf-cloneable-wrapper > div' ).eq( index ).find( '> h4' );

						!title.hasClass( 'ui-state-active' ) && title.trigger( 'click' );

						$( '#customize-controls .wp-full-overlay-sidebar-content' ).scrollTop( title.offset().top - 150 );

					}, 1000 );

				}

			} );

		} );

	}

})( jQuery );