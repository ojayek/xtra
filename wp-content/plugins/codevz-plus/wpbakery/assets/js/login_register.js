! function( $ ) {
	"use strict";

	Codevz_Plus.login = function() {

		// Forms slide.
		$( document.body ).on( 'click', '.cz_lrpr a[href*="#"]', function( e ) {

			$( this.hash ).slideDown().siblings().slideUp();

			e.preventDefault();

			return false;

		// AJAX request.
		}).on( 'submit', '.cz_lrpr form', function( e ) {

			var form = $( this ), 
				check = false;

			form.find( 'input' ).css( 'animation', 'none' ).each(function() {
				if ( ! $( this ).val() ) {
					$( this ).select().css( 'animation', 'xtraLrpAnimation .8s forwards' );
					check = false;
					return false;
				} else {
					check = true;
				}
			});

			if ( $( '.cz_loader', form ).length ) {
				return false;
			}

			if ( check ) {
				var btn = form.find( 'input[type="submit"]' ),
					ajaxurl = $( document.body ).data( 'ajax' ) || ajaxurl;

				btn.attr( 'disabled', 'disabled' ).addClass( 'cz_loader' );
				form.find( '.cz_msg' ).slideUp( 100 );

				$.post( ajaxurl, form.serialize(), function( msg ) {
					if ( msg ) {
						form.find( '.cz_msg' ).html( msg ).slideDown( 100 );
						btn.removeClass( 'cz_loader' );
					} else {
						var redirect = form.closest( '.cz_lrpr' ).data( 'redirect' );
						if ( redirect ) {
							window.location = redirect;
						} else {
							window.location.reload( true );
						}
					}
					btn.removeAttr( 'disabled' );
				});
			}

			return false;

		});

	};

	Codevz_Plus.login();

}( jQuery );