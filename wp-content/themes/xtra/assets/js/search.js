jQuery( function( $ ) {

	if ( $( '.outer_search' ).length ) {

		var body = $( document.body );

		// Input changes.
		body.on( 'keyup', '.cz_ajax_search [name="s"]', function( e, time ) {

			clearTimeout( time );

			var form    = $( this ).parent(),
				results = form.next( '.ajax_search_results' ),
				icon 	= $( 'button i', form ),
				org 	= 'fa ' + icon.data( 'xtra-icon' ),
				iLoader = 'fa fa-superpowers fa-pulse';

			// Check input length.
			if ( $( this ).val().length < 3 ) {
				$( '.ajax_search_results' ).slideUp( 'fast' );
				icon.removeClass( iLoader ).addClass( org );
				return;
			} else {
				icon.removeClass( org ).addClass( iLoader );
			}

			// Send request
			time = setTimeout(
				function() {
					$.ajax({
						type: "GET",
						url: body.data( 'ajax' ) || ajaxurl,
						dataType: 'html',
						data: "action=codevz_ajax_search&" + form.serialize(),
						success: function( data ) {
							results.html( data ).slideDown( 'fast' );
							icon.removeClass( iLoader ).addClass( org );
						},
						error: function( xhr, status, error ) {
							results.html( '<b class="ajax_search_error">' + error + '</b>' ).slideDown( 'fast' );
							icon.removeClass( iLoader ).addClass( org );
							console.log( xhr, status, error );
						}
					});
				},
				1000
			);

		// Search icon
		}).on( 'click', '.search_with_icon', function( e ) {

			e.stopPropagation();

		}).on( 'click', '.search_with_icon [name="s"]', function() {

			if ( $( this ).val() ) {
				$( '.ajax_search_results' ).slideDown( 'fast' );
			}

		// Search dropdown.
		}).on( 'click', '.search_style_icon_dropdown .xtra-search-icon', function() {

			var x 		= $( this ),
				outer 	= x.parent().find('.outer_search'),
				row_h 	= x.closest('.row').height(),
				clr 	= x.closest('.clr');

			if ( outer.is( ':visible' ) ) {
				outer.fadeOut( 'fast' );
			} else {
				outer.fadeIn( 'fast' ).find('input').trigger( 'focus' );
			}

		// Auto x-position search in header.
		}).on( 'mouseenter', '.search_style_icon_dropdown', function() {

			var x 			= $( this ),
				iconX 		= x.find( '.xtra-search-icon' ),
				iconWidth 	= iconX.outerWidth(),
				dropdown  	= x.find( '.outer_search' );

			if ( ( $( window ).width() / 2 ) > ( x.offset().left + 100 ) ) {

				x.addClass( 'inview_right' );

				var iconMl = parseFloat( iconX.css( 'marginLeft' ) );

				if ( body.hasClass( 'rtl' ) ) {
					dropdown.css( 'left', ( ( iconWidth / 2 ) - 38 + iconMl ) );
				} else {
					dropdown.css( 'left', -( ( iconWidth / 2 ) - 36 + iconMl ) );
				}

			} else {

				dropdown.css( 'right', ( ( iconWidth / 2 ) - 36 + parseFloat( iconX.css( 'marginRight' ) ) ) );

			}

		// Search fullscreen.
		}).on( 'click', '.search_style_icon_full .xtra-search-icon', function( e ) {

			var x = $( this );

			x.parent().find( '.outer_search' ).fadeIn( 'fast' ).find( 'input' ).trigger( 'focus' );

			$( window ).on( 'resize.search', function() {

				var t = $( this ),
					w = t.width(),
					h = t.height(),
					s = t.find('.outer_search .search');

				s.css({
					'top': h / 4 - s.height() / 2,
					'left': w / 2 - s.width() / 2
				});

			});

			x.parent().find( '.xtra-close-icon' ).toggleClass( 'hide' ).off().on( 'click', function( e ) {
				$( this ).addClass( 'hide' ).parent().find('.outer_search').fadeOut( 'fast' );
			});

		}).on( 'click', '#layout, .outer_search', function( e ) {

			if ( $( e.target ).closest( '.outer_search .search' ).length ) {
				return;
			}

			body.find( '.ajax_search_results' ).fadeOut( 'fast' );
			body.find( '.search_style_icon_dropdown, .search_style_icon_full' ).find( '.outer_search' ).fadeOut( 'fast' );
			body.find( '.search_style_icon_full .xtra-close-icon' ).addClass( 'hide' );

		});

	}

});