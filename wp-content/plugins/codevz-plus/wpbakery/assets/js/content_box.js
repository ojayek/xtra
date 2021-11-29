! function( $ ) {
	"use strict";

	Codevz_Plus.content_box = function( wpb ) {

		if ( wpb ) {

			var pdb = $( parent.document.body );

			// Flip box live
			if ( $( '.cz_box_backed' ).length && ! pdb.find( '.cz_vc_disable_flipbox' ).length ) {

				pdb.find( '.cz_vc_preview' ).after( '<li class="vc_pull-right cz_vc_disable_flipbox"><a href="javascript:;"><i class="fas fa-cube"></i> Disable flip box</a></li>' );

				pdb.find( '.cz_vc_disable_flipbox' ).on( 'click', function() {
					$( this ).toggleClass( 'cz_vc_disable_flipbox_disabled' );
					$( '.cz_box_backed, .cz_box_backed_disabled' ).toggleClass( 'cz_box_backed cz_box_backed_disabled' );
				});

			}

		}

	};

	Codevz_Plus.content_box();

}( jQuery );