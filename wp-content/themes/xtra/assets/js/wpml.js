jQuery( function( $ ) {

	// WPML selector.
	$( '.cz_language_switcher' ).codevz( 'wpml', function( x ) {

		x.find( '.cz_current_language' ).prependTo( x );

	});

	// Fix WPML widgets.
	$( '.widget, .footer_widget' ).codevz( 'wpml-widget', function( x ) {

		x.find( '> .clr' ).html() === '' && x.remove();

	});

});