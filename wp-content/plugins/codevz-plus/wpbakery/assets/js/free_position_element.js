! function( $ ) {
	"use strict";

	Codevz_Plus.free_position_element = function( selector ) {

		$( selector ).each( function() {

			var en 		= $( this ),
				inner 	= $( '.cz_free_position_element, .cz_hotspot', en );

			en.css(
				{
					'top': inner.data( 'top' ),
					'left': inner.data( 'left' )
				}
			).draggable(
				{
					drag: function() {
						var pos = $( this ).position(),
							col = $( this ).closest(".wpb_column");

						if ( ! $( ".ui-draggable", parent.document.body ).hasClass( 'vc_active' ) ) {
							$( '> .vc_controls .vc-c-icon-mode_edit', en ).trigger( 'click' );
						}

						$( ".css_top", parent.document.body ).val( parseFloat( ( pos.top / col.height() * 100 ).toFixed( 2 ) ) + "%" );
						$( ".css_left", parent.document.body ).val( parseFloat( ( pos.left / col.width() * 100 ).toFixed( 2 ) ) + "%" );
					}
				}
			);

			inner.css({'top': 'auto', 'left': 'auto'});

		});

	};

}( jQuery );