! function( $ ) {
	"use strict";

	Codevz_Plus.tabs = function( id, wpb ) {

		$( '.cz_tabs' ).codevzPlus( 'tabs', function( x ) {

			wpb && x.find( '.cz_tabs' ).html( x.find( '.cz_tabs_org' ).html() );

			// Convert tabs nav
			if ( ! x.find( '.cz_tabs_nav' ).length ) {

				x[ x.hasClass( 'cz_tabs_nav_after' ) ? 'append' : 'prepend' ]( '<div class="cz_tabs_nav clr"><div class="clr"></div></div>' );

			}

			x.find( '.cz_tabs_nav div' ).html( '' );

			x.find( '.cz_tab_a' ).each( function() {
				x.find( '.cz_tabs_nav div' ).prepend( $( this ).removeClass( 'vc_empty-element' ).clone() );
			});

			// Mobile dropdown.
			if ( ! x.find( '> select' ).length ) {
				x.prepend( '<select />' );
			} else {
				x.find( '> select' ).html( '' );
			}

			x.find( '.cz_tabs_nav div a' ).each( function() {
				x.find( '> select' ).append( $( '<option />' ).attr( 'value', $(this).data( 'tab' ) ).html( $(this).text() ) );
			});
			
			x.find( '> select' ).on( 'change', function() {
				x.find( 'a[data-tab="' + this.value + '"]' ).trigger( 'click' );
			});

			// onClick tabs nav
			x.find( '.cz_tab_a' ).on( ( x.hasClass( 'cz_tabs_on_hover' ) ? 'mouseenter click' : 'click' ), function() {

				var en  = $( this ),
					id  = en.data( 'tab' ),
					par = en.closest('.cz_tabs'),
					tab = $( '#' + id, par );

				if ( tab.is(':visible') && en.attr( 'href' ) && en.attr( 'href' ).length < 2 ) {
					return false;
				}

				// Fix carousel.
				if ( par.find( '.slick' ).length ) {

					if ( ! tab.find( '.xtra-slick-done' ).length ) {
						setTimeout(function() {
							tab.find( '.slick' ).slick( 'reinit' );
						}, 10 );
					} else {
						par.find( '.slick' ).removeClass( 'xtra-slick-done' );
					}

				}

				// Set tab active class.
				en.addClass('active cz_active').siblings().removeClass('active cz_active');

				if ( wpb ) {
					$( '.cz_tab', par ).closest( '.vc_cz_tab' ).hide();
					tab.closest( '.vc_cz_tab' ).show();
				} else {
					$( '.cz_tab', par ).hide();
					tab.show();
				}

				setTimeout( function() {

					// Fix grid.
					if ( tab.find( '.cz_grid' ).data( 'isotope' ) ) {
						tab.find( '.cz_grid' ).isotope( 'layout' );
					}

					// Fix carousel.
					if ( tab.find( '.slick' ).length ) {
						$( window ).trigger( 'resize.slick' );
					}

					// Working hours line.
					if ( tab.find( '.cz_wh_line_between .cz_wh_line' ).length ) {
						Codevz_Plus.working_hours();
					}
				
				}, 100 );

				if ( en.attr( 'href' ) && en.attr( 'href' ).length < 2 ) {
					return false;
				}
			});

			// Active tab.
			x.find( '.cz_tabs_nav a' ).removeClass( 'hide active cz_active' );

			x.find( '.cz_tabs_nav a' + ( id ? '[data-tab="' + id + '"]' : ':first-child' ) ).addClass( 'active cz_active' ).trigger( 'click' );

		});

	};

	Codevz_Plus.tabs();

}( jQuery );