/**
 * StyleKit for elementor.
 *
 * @since 4.2.0
 */
jQuery( function( $ ) {

	window[ 'xtraElementor' ] = {};

	// StyleKit live control view.
	var controlView = elementor.modules.controls.BaseMultiple.extend( {

		// Control on ready.
		onReady: function() {

			var api = this;

			setTimeout( function() {

				var sk = $( 'a[data-sk="elementor-control-sk-' + api.model.cid + '"]' ),
					ss = sk.attr( 'data-name' );

				sk.parent().csf_reload_script( false, api );

				window[ 'xtraElementor' ][ ss ] = api;

			}, 250 );

		},

		// Destroy.
		onBeforeDestroy: function() {
			$( '.ui-dialog-titlebar-close' ).trigger( 'click' );
		},

	});

	elementor.addControlView( 'stylekit', controlView );

});