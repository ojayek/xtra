! function( $ ) {
	"use strict";

	Codevz_Plus.popup = function( wpb ) {

		var body = $( document.body );

		// Reset wpb bar.
		wpb && $( '.cz_edit_popup_link', parent.document.body ).remove();

		body.off( 'click.popupe' ).on( 'click.popupe', '.xtra-popup', function() {

			var $this = $( this ).parent().find( '.cz_popup_modal' );

			$( 'html, body' ).addClass( 'no-scroll' );

			$this.fadeIn( 'fast' ).delay( 1000 ).addClass( 'cz_show_popup' );

			$( '.cz_overlay' ).fadeIn().css( 'background', $this.data( 'overlay-bg' ) || '' );

			if ( $this.find( '.slick' ).length && typeof Codevz_Plus.slick != 'undefined' ) {
				Codevz_Plus.slick();
			}

		});

		// Close popup.
		body.on( 'click', ".cz_close_popup, #cz_close_popup, .cz_overlay, a[href*='#cz_close_popup']", function( e ) {

			var $this = $( this ),
				popup = $this.closest( '.cz_popup_modal' );

			$( '.vc_cz_popup, .vc_cz_popup, .cz_popup_modal' ).hide().removeClass( 'cz_show_popup' );

			$( '.cz_overlay' ).fadeOut( 'fast' ).css( 'background', '' );

			$( 'html, body' ).removeClass( 'no-scroll' );

			if ( popup.hasClass( 'cz_popup_show_once' ) ) {
				localStorage.setItem( popup.attr( 'id' ), 1 );
			}

			e.preventDefault();

		});

		// Popup.
		$( '.cz_popup_modal' ).each( function( i, x ) {

			var $this 		= $( x ),
				popup 		= $this.parent(),
				popupID 	= $this.attr( 'id' ),
				parentX 	= $this.closest( '.vc_cz_popup' ),
				showPopup 	= function() {

					// Elementor classes.
					if ( $this.closest( '.elementor-element' ).length ) {

						var eID = $this.closest( '[data-elementor-id]' ).attr( 'data-elementor-id' );

						if ( eID ) {
							popup.addClass( 'elementor-'  + eID );
						}

						popup.find( '> div' ).addClass( $this.closest( '.elementor-element' ).attr( 'class' ) );

					}

					// Append to body.
					if ( ! wpb && ! $( '.elementor-element-edit-mode' ).length && ! popup.hasClass( 'xyz' ) ) {

						popup.addClass( 'xyz' ).appendTo( 'body' );

					}

					$( 'html, body' ).addClass( 'no-scroll' );

					parentX.fadeIn( 'fast' );

					$( '.vc_cz_popup, #' + popupID ).fadeIn( 'fast' ).delay( 1000 ).addClass( 'cz_show_popup' );

					$( '.cz_overlay' ).fadeIn().css( 'background', $this.data( 'overlay-bg' ) || '' );

					if ( $this.find( '.slick' ).length && typeof Codevz_Plus.slick != 'undefined' ) {
						Codevz_Plus.slick();
					}

				};

			// Frontend.
			if ( wpb ) {

				// Add popup link to wpb bar
				$this.each( function() {

					var vc_nav = $( '.vc_navbar-nav', parent.document.body );

					if ( ! vc_nav.find( '.edit_' + popupID ).length ) {
						vc_nav.append( '<li class="vc_pull-right cz_edit_popup_link"><a class="vc_icon-btn vc_post-settings edit_' + popupID + '" data-id="' + popupID + '" href="#' + popupID + '" title="Popup: ' + popupID + '"><i class="vc-composer-icon far fa-window-restore" style="font-family: \'Font Awesome 5 Free\' !important;font-weight:400"></i></li>' );
					}

				});

				// Set popup styling
				parentX.attr( 'style', $this.attr( 'style' ) );				

				// Open popup
				$( '.edit_' + popupID, parent.document.body ).on( 'click', function(e) {
					showPopup();
					e.preventDefault();
				});

				// Delete popup
				$( "#" + popupID + " .cz_close_popup, #cz_close_popup, a[href*='#cz_close_popup']" ).off();
				
				$( '> .vc_controls .vc_control-btn-delete', parentX ).on('click', function() {

					$( '.edit_' + popupID, parent.document.body ).closest( 'li' ).remove();

					$( '.cz_overlay' ).fadeOut( 'fast' ).css( 'background', '' );

				});

			}

			// Open popup.
			$( "a[href*='#" + popupID + "']" ).off().on( 'click', function( e ) {

				// Move popup to footer.
				if ( popup.length ) {

					showPopup();

					// Fix CF7 Pro inside Popup
					if ( ! parentX.length && typeof wpcf7 != 'undefined' && $this.find( '.wpcf7' ).length ) {

						$this.find( 'div.wpcf7 > form' ).each( function() {

							var $this = $( this );

							if ( $.fn.initForm ) {
								wpcf7.initForm( $this );

								if ( wpcf7.cached ) {
									wpcf7.refill( $this );
								}
							}

						} );

					}

					// Update lightbox.
					Codevz_Plus.lightGallery( $( '#' + popupID ) );

					// Fix multiple same popup
					$this.attr( 'data-popup', popupID );

				}

				e.preventDefault();

			});

			// If popup is always show, then remove session
			if ( $this.hasClass( 'cz_popup_show_always' ) && localStorage.getItem( popupID ) ) {
				localStorage.removeItem( popupID );
			}

			// Check visibility mode on page load
			if ( $this.hasClass( 'cz_popup_page_start' ) && ! localStorage.getItem( popupID ) ) {
				showPopup();
			} else if ( $this.hasClass( 'cz_popup_page_loaded' ) && ! localStorage.getItem( popupID ) ) {
				$( window ).on( 'load', function() {
					showPopup();
				});
			}

			var dly = $this.data( 'settimeout' ),
				scr = $this.data( 'after-scroll' );

			// Auto open after delay.
			if ( dly ) {
				setTimeout(function() {
					showPopup();
				}, dly );
			}

			// Auto open after specific scroll position.
			if ( scr ) {

				$( window ).on( 'scroll.popup_scroll', function() {

					var $this = $( this );

					var scrollPercent = 100 * $this.scrollTop() / ( $( document ).height() - $this.height() );

					if ( scrollPercent >= scr ) {

						showPopup();

						$this.off( 'scroll.popup_scroll' );

					}

				});

			}

		});

	};

	Codevz_Plus.popup();

}( jQuery );