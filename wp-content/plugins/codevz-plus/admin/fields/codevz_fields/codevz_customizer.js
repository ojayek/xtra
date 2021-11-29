/**
 * Codevz live customizer
 * 
 * @author Codevz
 * @link http://codevz.com
 */
jQuery( function( $ ) {
	'use strict';

	var body = $( document.body ),
		color_timeout,

		// Hex to RGBA numbers
		hexToRgbA = function( hex, space ) {
		    var c;
		    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
		        c= hex.substring(1).split('');
		        if(c.length== 3){
		            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
		        }
		        c= '0x'+c.join('');

		        return [(c>>16)&255, (c>>8)&255, c&255].join( ( space ? ', ' : ',' ) );
		    }
		    //throw new Error('--Bad--Hex--');
		    return hex;
		},

		// Object or inner key equality
		unEqual = function( o1, o2, key ) {

			if ( ! key ) {
				return JSON.stringify( o1 ) != JSON.stringify( o2 );

			} else if ( typeof o1[key] == 'object' && typeof o2[key] == 'object' ) {
				return JSON.stringify( o1[key] ) != JSON.stringify( o2[key] );

			} else if ( Array.isArray( key ) ) {
				var r = 0;
				$.each( key, function( i, v ) {
					if ( o1[v] != o2[v] ) {
						r = 1;
					}
				});

				return r;
			} else {
				return o1[key] != o2[key];
			}

		},

		// Find & replace theme colors
		findReplaceColors = function( w, old_color, new_color ) {
			clearTimeout( color_timeout );
			color_timeout = setTimeout(function() {

				old_color = old_color.replace( /"|'/g, '' );
				new_color = new_color.replace( /"|'/g, '' );

				if ( ! old_color ) {
					old_color = new_color;
					body.attr( 'data-' + w + '-old-color', new_color );
				}

				if ( new_color.length <= 6 ) {
					return;
				}

				if ( old_color && new_color && new_color != '0' && old_color != '0' && old_color != new_color && ( ( new_color.indexOf( '#' ) >= 0 && new_color.length >= 4 ) || ( new_color.indexOf( '(' ) >= 0 && new_color.indexOf( 'rgb' ) >= 0 && new_color.indexOf( ',' ) >= 0 && new_color.indexOf( ')' ) >= 0 ) ) ) {
					var old_regexp_color 	= new RegExp( old_color, "gi" ),
						old_regexp_rgb 		= new RegExp( hexToRgbA( old_color ), "gi" ),
						old_regexp_rgb2 	= new RegExp( hexToRgbA( old_color, 1 ), "gi" );

					$( 'style' ).each(function() {
						var en = $( this );
						en.html( en.html().replace( old_regexp_color, new_color ).replace( old_regexp_rgb, hexToRgbA( new_color ) ).replace( old_regexp_rgb2, hexToRgbA( new_color, 1 ) ) );
					});
					$( '[style]' ).each(function() {
						var en = $( this );
						en.attr( 'style', en.attr( 'style' ).replace( old_regexp_color, new_color ).replace( old_regexp_rgb, hexToRgbA( new_color ) ).replace( old_regexp_rgb2, hexToRgbA( new_color, 1 ) ) );
					});
					$( '[fill]' ).each(function() {
						var en = $( this );
						en.attr( 'fill', en.attr( 'fill' ).replace( old_regexp_color, new_color ).replace( old_regexp_rgb, hexToRgbA( new_color ) ).replace( old_regexp_rgb2, hexToRgbA( new_color, 1 ) ) );
					});
					$( '#customize-controls input, #cz_modal_kit input', parent.document ).each(function() {
						var en = $( this );
						en.attr( 'value', en.val().replace( old_regexp_color, new_color ).replace( old_regexp_rgb, hexToRgbA( new_color ) ).replace( old_regexp_rgb2, hexToRgbA( new_color, 1 ) ) );
						en.trigger( 'change' );
					});
					$( '.wp-color-result', parent.document ).each(function() {
						var en = $( this );
						en.css( 'background', en.css( 'background' ).replace( old_regexp_color, new_color ).replace( old_regexp_rgb, hexToRgbA( new_color ) ).replace( old_regexp_rgb2, hexToRgbA( new_color, 1 ) ) );
					});

					body.attr( 'data-' + w + '-old-color', new_color );
				}
			}, 250 );
		},

		codevzFixSkFontFamily = function( e ) {
			if ( e && e.indexOf( 'font-family' ) >= 0 ) {
				return e.replace(/=(.*?);/g, ';' );
			} else {
				return e;
			}
		};

	// Live changes
	function codevzLiveChanges( id, to ) {
		id = id.indexOf( '[' ) >= 0 ? id.substring( id.lastIndexOf( '[' ) + 1, id.lastIndexOf( ']' ) ) : id;
		var selector = codevz_selectors[id];

		// Live CSS
		if ( to ) {
			if ( to.indexOf( 'font-family' ) >= 0 ) {
				var font = to.match( /font-family:(.*?);/ );
				if ( font && font[1] ) {
					$( 'head' ).append( "<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=" + font[1].replace( '=', ':' ).replace( / /g, '+' ).replace( /"|'/g, '' ) + "' type='text/css' media='all' />" );
				}
			}

			to = to.replace( /=(.*?);/g, ';' ).replace( /CDVZ/g, '' ).replace( /RTL(.*?)RTL/g, '' ).replace( /RTL/g, '' );
			to = selector + '{' + to + '}';

			if ( ! $( '#' + id ).length ) {
				body.append( '<style id="' + id + '" type="text/css"></style>' );
			}
			if ( id ) {
				if ( id === '_css_container_header_5' || id === '_css_row_header_5' ) {
					to = to.replace( /!important/g, '' ).replace( /;/g, ' !important;' );
				}

				if ( id.indexOf( '_tablet' ) >= 0 ) {
					to = '@media screen and (max-width:768px){' + to + '}';
				} else if ( id.indexOf( '_mobile' ) >= 0 ) {
					to = '@media screen and (max-width:480px){' + to + '}';
				}

				$( '#' + id ).html( to );
			}
		} else {
			$( '#' + id ).detach();
		}

		// Row shape
		if ( to.indexOf( '_class_shape' ) >= 0 ) {
			var shape_s = ( selector.indexOf('elms') >= 0 ) ? $( selector ) : $( selector ).parent();
			if ( shape_s.length ) {
				shape_s.removeClass(function( i, c ) {
					return (c.match(/(^|\s)cz_row_shape_\S+/g) || []).join(' ');
				}).addClass( to.split('_class_shape:')[1].split(';')[0] );

				var class_name = shape_s.attr( 'class' ) || 'cz_no_class',
					class_name = '.' + class_name.replace(/  /g, '.').replace(/ /g, '.'),
					hei = shape_s.height() + 37;

				!shape_s.find('> style').length && shape_s.append('<style></style>');
				shape_s.find('> style').html( class_name + ' .row:before,' + class_name + ' .row:after{width:' + hei + 'px}.elms_row ' + class_name + ':before, .elms_row ' + class_name + ':after{width:' + hei + 'px}' );
			}
		}

		// Menu hover fx
		if ( to.indexOf( '_class_menu_fx' ) >= 0 ) {
			var menufx_s = id.split('_');
			menufx_s.reverse();
			if ( menufx_s[1] == 'side' ) {
				menufx_s[1] = 'fixed_' + menufx_s[1];
			}
			var m_selector = '.' + menufx_s[1] + '_' + menufx_s[0];

			$( m_selector ).removeClass(function( i, c ) {
				return (c.match(/(^|\s)cz_menu_fx_\S+/g) || []).join(' ');
			}).addClass( to.split('_class_menu_fx:')[1].split(';')[0] );
		}

		// SubMenu fx
		if ( to.indexOf( '_class_submenu_fx' ) >= 0 ) {
			var menufx_s = id.split('_');
			menufx_s.reverse();
			if ( menufx_s[1] == 'side' ) {
				menufx_s[1] = 'fixed_' + menufx_s[1];
			}
			var m_selector = '.' + menufx_s[1] + '_' + menufx_s[0];

			$( m_selector ).removeClass(function( i, c ) {
				return (c.match(/(^|\s)cz_submenu_fx_\S+/g) || []).join(' ');
			}).addClass( to.split('_class_submenu_fx:')[1].split(';')[0] );
		}

		// Menu indicator icon
		var mi_sel = $( selector );
		if ( to.indexOf( '_class_indicator' ) >= 0 && mi_sel.hasClass( 'cz_indicator' ) ) {
			mi_sel.attr( 'class', 'cz_indicator ' + to.split('_class_indicator:')[1].split(';')[0] );
		} else if ( id.indexOf( '_indicator_a' ) >= 0 ) {
			mi_sel.attr( 'class', 'cz_indicator' );
		}

		// Body fixed border
		var body_border = '.cz_fixed_top_border, .cz_fixed_bottom_border';
		if ( id === '_css_body' && to.indexOf( 'border-style' ) >= 0 && to.indexOf( 'border-width' ) >= 0 && to.indexOf( 'border-color' ) >= 0 ) {
			$( body_border ).css( 'border-style', to.split('border-style:')[1].split(';')[0] );
			$( body_border ).css( 'border-width', to.split('border-width:')[1].split(';')[0] );
			$( body_border ).css( 'border-color', to.split('border-color:')[1].split(';')[0] );
		} else {
			$( body_border ).css( 'border-width', '0px' );
		}

	} // End codevzLiveChanges

	// Selective Refresh
	function codevzSelectiveRefresh( id, to ) {

		var rgx = id.match( /[^\[]\w+\d{1}|\_\w+[^\]]/g ),
			_id = rgx[1], 
			_pos = rgx[2],
			container = $( '.' + _id + _pos ),
			e_name, elm, s, time, is_new,
			to_length = to.length,
			in_length = container.find( '> div' ).length;

		// Refresh if container not found
		if ( ! container.length || ! rgx ) {
			wp.customize.preview.send( 'refresh' );
		}

		// Each elements
		$.each( to, function( i, e ) {
			is_new = 0;

			// Check length and empty
			if ( to_length != in_length ) {
				if ( typeof e.element == 'string' && ! e.element ) {
					return;
				}
				is_new = 1;
			}

			// Element
			e_name = e.element, elm = $( '.inner_' + e_name + '_' + _id + _pos + '_' + i );

			// Old Settings
			s = elm.data( 'settings' ) || {};

			// Check equality
			if ( unEqual( e, s ) ) {

				// Skip to next
				if ( ! elm.length ) {

					is_new = 1;

				// Element margin
				} else if ( unEqual( e, s, 'margin' ) ) {

					$.each( e['margin'], function( p, v ) {
						elm.css( 'margin-' + p, v );
					});

				// Element on sticky
				} else if ( unEqual( e, s, 'elm_on_sticky' ) ) {

					if ( e['elm_on_sticky'] === 'show_on_sticky' ) {
						elm.addClass( 'show_on_sticky' ).removeClass( 'hide_on_sticky' );
					} else if ( e['elm_on_sticky'] === 'hide_on_sticky' ) {
						elm.addClass( 'hide_on_sticky' ).removeClass( 'show_on_sticky' );
					} else {
						elm.removeClass( 'show_on_sticky hide_on_sticky' );
					}

				// Hide on mobile / tablet
				} else if ( unEqual( e, s, ['hide_on_mobile', 'hide_on_tablet', 'elm_center'] ) ) {
					
					elm[( e['hide_on_mobile'] ? 'addClass' : 'removeClass' )]( 'hide_on_mobile' );
					elm[( e['hide_on_tablet'] ? 'addClass' : 'removeClass' )]( 'hide_on_tablet' );
					elm[( e['elm_center'] ? 'addClass' : 'removeClass' )]( 'cz_elm_center' );

				// Image and Logo
				} else if ( ( e_name == 'logo' || e_name == 'logo_2' || e_name == 'image' ) && unEqual( e, s, ['logo_width', 'image_width'] ) ) {

					if ( ! elm.find( 'img' ).length ) {
						wp.customize.preview.send( 'refresh' );
					} else {
						elm.find( 'img' ).css( 'width', ( ( e_name == 'image' ) ? e['image_width'] : e['logo_width'] ) );
					}

				// Menu
				} else if ( e_name == 'menu' && unEqual( e, s, ['sk_menu_icon', 'sk_menu_dropdown', 'sk_menu_title', 'menu_icon'] ) ) {

					elm.find( '> i' ).attr( 'style', e['sk_menu_icon'] );
					elm.find( '> i span' ).attr( 'style', e['sk_menu_title'] );

					e['menu_icon'] && elm.find( '> i' ).removeClass(function( i, c ) {
						return (c.match(/(^|\s)fa-\S+|(^|\s)czico-\S+/g) || []).join(' ');
					}).addClass( e['menu_icon'] );

					if ( e['menu_title'] ) {
						elm.find( 'i' ).addClass( 'icon_plus_text' ).find( 'span' ).html( e['menu_title'] );
					} else {
						elm.find( 'i' ).removeClass( 'icon_plus_text' );
					}

					if ( e['menu_type'] == 'dropdown_menu' ) {
						elm.find( '.sf-menu' ).attr( 'style', e['sk_menu_dropdown'] );
					}

				// Social
				} else if ( e_name == 'social' && unEqual( e, s, 'vertical' ) ) {
					
					elm[( e['vertical'] ? 'addClass' : 'removeClass' )]( 'cz_vertical_elm' );

				// Icon & Text
				} else if ( ( e_name == 'icon' || e_name == 'icon_info' ) && ! unEqual( e, s, ['it_link', 'sk_it_wrap_hover', 'it_icon', 'sk_it_icon_hover', 'sk_it_hover', 'sk_it_2_hover'] ) ) {

					elm.find( 'i' ).attr( 'style', e['sk_it_icon'] );
					elm.find( '.cz_elm_info_box' ).attr( 'style', e['sk_it_wrap'] );
					elm.find( '.it_text,.cz_info_1' ).html( e['it_text'] ).attr( 'style', codevzFixSkFontFamily( e['sk_it'] ) );
					elm.find( '.cz_info_2' ).html( e['it_text_2'] ).attr( 'style', codevzFixSkFontFamily( e['sk_it_2'] ) );
					elm[( e['vertical'] ? 'addClass' : 'removeClass' )]( 'cz_vertical_elm' );

				// Search
				} else if ( e_name == 'search' && ! unEqual( e, s, ['search_type', 'search_cpt', 'ajax_search', 'search_icon', 'sk_search_icon_hover'] ) ) {

					var show_search = ( elm.find( '.outer_search' ).css( 'display' ) != 'none' ) ? 1 : 0;

					elm.find( '.search' ).css( 'width', e['search_form_width'] );
					elm.find( 'input[name="s"]' ).attr( 'placeholder', e['search_placeholder'] ).attr( 'style', e['sk_search_input'] );
					elm.find( '.outer_search' ).attr( 'style', e['sk_search_con'] + ( show_search && 'display:block;' ) );
					elm.find( '.ajax_search_results' ).attr( 'style', e['sk_search_ajax'] );

					if ( elm.find( '.search_style_icon_full' ).length ) {
						elm.find( 'form span' ).html( e['search_placeholder'] ).attr( 'style', e['sk_search_title'] );
						elm.find( 'input[name="s"] ' ).removeAttr( 'placeholder' );
					}

					elm.find( '.xtra-search-icon' ).attr( 'style', e['sk_search_icon'] );
					elm.find( 'button i' ).attr( 'style', e['sk_search_icon_in'] );

				// Social Icons element.
				} else if ( e_name == 'social' && ! unEqual( e, s, ['social_type', 'social_columnar', 'sk_social_icon_hover'] ) ) {

					elm.find( '> i' ).attr( 'style', e['sk_social_icon'] );

				// Offcanvas
				} else if ( e_name == 'widgets' && ! unEqual( e, s, ['inview_position_widget', 'sk_offcanvas_icon_hover', 'sk_menu_title_hover'] ) ) {

					elm.find( 'i' ).attr( 'style', e['sk_offcanvas_icon'] );
					elm.find( 'i span' ).attr( 'style', e['sk_menu_title'] );
					e['offcanvas_icon'] && elm.find( 'i' ).attr( 'class', e['offcanvas_icon'] );
					
					if ( e['menu_title'] ) {
						elm.find( 'i span' ).addClass( 'icon_plus_text' ).html( e['menu_title'] );
					} else {
						elm.find( 'i' ).removeClass( 'icon_plus_text' );
					}
					
					$( '.offcanvas_area.offcanvas_original' ).attr( 'style', e['sk_offcanvas'] );

				// Hidden fullwidth content
				} else if ( e_name == 'hf_elm' && ! unEqual( e, s, ['hf_elm_page', 'sk_hf_elm_icon_hover'] ) ) {
					
					elm.find( '> i' ).attr( 'style', e['sk_hf_elm_icon'] ).attr( 'class', 'hf_elm_icon ' + e['hf_elm_icon'] );
					elm.find( '.hf_elm_area' ).attr( 'style', e['sk_hf_elm'] );


				// Shop Cart
				} else if ( ( e_name == 'shop_cart' || e_name == 'wishlist' ) && ! unEqual( e, s, ['shop_plugin', 'shopcart_icon', 'shopcart_title'] ) ) {
					
					elm.find( '.shop_icon, .wishlist_icon' ).attr( 'style', e['sk_shop_container'] );

					elm.find( '.shop_icon > span' ).html( e['shopcart_title'] );

					elm.find( '.shop_icon > i' ).attr( 'style', e['sk_shop_icon'] );
					elm.find( '.cz_cart_count' ).attr( 'style', e['sk_shop_count'] );
					elm.find( '.cz_cart_items' ).attr( 'style', e['sk_shop_content'] );

					elm.find( '.wishlist_icon > span' ).html( e['shopcart_title'] );

					elm.find( '.wishlist_icon > i' ).attr( 'style', e['sk_shop_icon'] );
					elm.find( '.cz_wishlist_count' ).attr( 'style', e['sk_shop_count'] + 'display:block;' );


				// Button
				} else if ( e_name == 'button' && ! unEqual( e, s, ['btn_link', 'sk_btn_hover', 'btn_icon_pos', 'hf_elm_icon', 'sk_hf_elm_icon_hover'] ) ) {
					
					elm.find( 'a' ).attr( 'style', e['sk_btn'] );
					elm.find( 'span' ).html( e['btn_title'] );
					elm.find( 'i' ).attr( 'style', e['sk_hf_elm_icon'] );


				// Line
				} else if ( e_name == 'line' && unEqual( e, s, 'sk_line' ) ) {
					
					var h = elm.find( '> div' ).css( 'height' );
					elm.find( '> div' ).attr( 'style', e['sk_line'] ).height( h );


				// WPML
				} else if ( e_name == 'wpml' && unEqual( e, s, ['wpml_color', 'wpml_background'] ) ) {

					elm.find( '.cz_language_switcher a' ).css( 'color', e['wpml_color'] );
					elm.find( '.cz_language_switcher > div' ).css( 'background', e['wpml_background'] );

				// Others e.g. do_shortcode required
				} else if ( e_name == 'custom' || e_name == 'custom_element' ) {
					wp.customize.preview.send( 'refresh' );

				// New
				} else {
					is_new = 1;
				}

				// Update data-settings
				if ( ! is_new ) {
					elm.data( 'settings', e );
				}

			} // Equality

			// SelectiveRefresh
			if ( is_new ) {
				clearTimeout( time );
				container.addClass( 'cz_selective_refresh' ).find( '> div' ).css( 'opacity', '0.4' );

				// Send request
				time = setTimeout(
					function() {
						$.ajax({
							type: "POST",
							url: body.data( 'ajax' ),
							data: "action=codevz_selective_refresh&id=" + _id + "&pos=" + _pos,
							success: function( data ) {
								container.removeClass( 'cz_selective_refresh' ).html( $( data ).html() );
								setTimeout(function() {
									if ( typeof Codevz != 'undefined' ) {
										Codevz.init();
									}
									if ( typeof Codevz_Plus != 'undefined' ) {
										Codevz_Plus.init();
									}
								}, 500 );
							},
							error: function( xhr, status, error ) {
								container.html( '<p style="color: red">' + error + '</p>' );
								console.log( xhr, status, error );
							}
						});
					},
					1000
				);
			}

		}); // to.each

	} // Selective Refresh

	// Start new customize changes
	if ( typeof wp != 'undefined' && wp && wp.customize && parent._wpCustomizeSettings ) {

		// Send element details to customize panel.
		body.on( 'click', '.xtra-section-focus', function( e ) {

			wp.customize.preview.send( 'xtra-section',
				{
					section: $( this ).attr( 'data-section' ),
					index: $( this ).attr( 'data-id' )
				}
			);

		});

		// First, Do changes on page load
		$.each( codevz_customize_json, function( id, to ) {
			codevzLiveChanges( id, to );
		});

		// First, live colors
		setTimeout(function() {
			findReplaceColors( 'primary', body.attr( 'data-primary-old-color' ), $( '[name="codevz_theme_options[site_color]"]', parent.document ).val() );
		}, 2000 );
		setTimeout(function() {
			findReplaceColors( 'secondary', body.attr( 'data-secondary-old-color' ), $( '[name="codevz_theme_options[site_color_sec]"]', parent.document ).val() );
		}, 2500 );

		// Get option ID
		var is = function(o,n){
				return o == 'codevz_theme_options[' + n + ']';
			},
			rendered = 0,
			refresh = 0,
			timeout;

		// Return each options
		$.each( parent._wpCustomizeSettings.settings, function( opt, torf ) {

			wp.customize( opt, function( value ) {

				// Bind live changes
				value.bind(function( to ) {

					// Call JS after partial content rendered
					if ( wp.customize.selectiveRefresh ) {
					
						wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( p ) {

							clearTimeout( rendered );

							rendered = setTimeout(function(){

								if ( typeof Codevz != 'undefined' ) {
									Codevz.init();
								}

								if ( typeof Codevz_Plus != 'undefined' ) {
									Codevz_Plus.init();
								}

								$( window ).trigger( 'scroll.codevz' );

							}, 500 );

						});
					}

					// Live widget changes
					if ( to && typeof to.encoded_serialized_instance === 'string' ) {
						return;
					}

					// Changes when user changed group
					if ( opt && opt.indexOf( '_css_' ) >= 0 ) {
						codevzLiveChanges( opt, to );

					// no action
					} else if ( is( opt, 'social' ) || is( opt, 'mobile_fixed_navigation_items' ) || is( opt, 'social_tooltip' ) || is( opt, 'social_inline_title' ) || is( opt, 'social_hover_fx' ) || is( opt, 'social_color_mode' ) || is( opt, 'vc_disable_templates' ) ||  is( opt, 'wp_login_logo' ) ||  is( opt, 'sidebars' ) ||  is( opt, 'vc_disable_modules' ) || is( opt, 'zip_icons' ) || is( opt, 'wp_editor_fonts' ) || is( opt, 'add_post_type' ) || is( opt, 'custom_fonts' ) ) {
						
					// Selective refresh
					} else if ( opt && typeof to == 'object' ) {
						codevzSelectiveRefresh( opt, to );

					// Color Secondary
					} else if ( to && is( opt, 'site_color_sec' ) ) {
						if (  to == '#ffffff' ) {
							to = '#fffffe';
						}
						if (  to == '#000000' ) {
							to = '#000001';
						}
						if ( to == '#222222' ) {
							to = '#222223';
						}
						if ( to == $( '[name="codevz_theme_options[site_color]"]', parent.document ).val() ) {
							alert( 'Warning: Primary and secondary color can not be same, Please change it to another color.' );
							return;
						}
						$( '[name="codevz_theme_options[site_color_sec]"]', parent.document ).val( to ).trigger( 'change' );
						findReplaceColors( 'secondary', body.attr( 'data-secondary-old-color' ), to );

					// Color primary
					} else if ( to && is( opt, 'site_color' ) ) {
						if ( to == '#ffffff' ) {
							to = '#fffffe';
						}
						if ( to == '#000000' ) {
							to = '#000001';
						}
						if ( to == '#222222' ) {
							to = '#222223';
						}
						if ( to == $( '[name="codevz_theme_options[site_color_sec]"]', parent.document ).val() ) {
							alert( 'Warning: Primary and secondary color can not be same.' );
							return;
						}
						$( '[name="codevz_theme_options[site_color]"]', parent.document ).val( to ).trigger( 'change' );
						findReplaceColors( 'primary', body.attr( 'data-primary-old-color' ), to );

					// Theme width
					} else if ( to && is( opt, 'site_width' ) ) {
						setTimeout(function() {
							if ( ! $( '.is_fixed_side' ).length ) {
								if ( $( '.layout_1' ).length || $( '.layout_2' ).length ) {
									$( '.layout_1,.layout_1 .cz_fixed_footer,.layout_1 .header_is_sticky' ).css( 'width', to ).find( '.row' ).css( 'width', 'calc(' + to + ' - 10%)' );
									$( '.layout_2,.layout_2 .cz_fixed_footer,.layout_2 .header_is_sticky' ).css( 'width', to ).find( '.row' ).css( 'width', 'calc(' + to + ' - 10%)' );
								} else {
									$( '.row,section.elementor-section.elementor-section-boxed>.elementor-container' ).css( 'width', to );
								}
							} else {
								$( '.row,section.elementor-section.elementor-section-boxed>.elementor-container' ).css( 'width', to );
							}
							$( '.cz_fixed_footer' ).length ? body.css( 'margin-bottom', $( '.cz_fixed_footer' ).height() ) : null;
						}, 250 );

					// Loading out links
					} else if ( to && is( opt, 'out_loading' ) ) {
						$( '.pageloader' ).data( 'out', '1' );

					// Loading out links
					} else if ( is( opt, 'mobile_menu_text' ) ) {
						$( '.xtra-mobile-menu-text' ).html( to );

					// Loading image
					} else if ( to && is( opt, 'pageloader_img' ) ) {
						$( '.pageloader img' ).attr( 'src', to );

					// Loading time
					} else if ( is( opt, 'pageloader_time' ) ) {
						$( '.pageloader' ).data( 'time', to );

					// Mobile nav title.
					} else if ( is( opt, 'mobile_fixed_navigation_title' ) ) {
						$( '.xtra-fixed-mobile-nav' ).removeClass().addClass( 'xtra-fixed-mobile-nav ' + to );

					// Next/prev archive icon
					} else if ( to && is( opt, 'next_prev_archive_icon' ) ) {
						if ( $( '.cz-next-prev-archive i' ).length ) {
							$( '.cz-next-prev-archive i' ).attr( 'class', to );
						} else {
							refresh = 1;
						}

					// Boxed layout
					} else if ( is( opt, 'boxed' ) ) {
						var layout = $( '#layout' );
						if ( to == '2' ) {
							layout.removeClass( 'layout_ layout_1' ).addClass( 'layout_2' );
						} else if ( to ) {
							layout.removeClass( 'layout_ layout_2' ).addClass( 'layout_1' );
						} else {
							layout.removeClass( 'layout_1 layout_2' ).addClass( 'layout_' );
						}

					// RTL
					} else if ( is( opt, 'rtl' ) ) {
						body.toggleClass( 'rtl' );

					// Dev CSS
					} else if ( is( opt, 'dev_css' ) ) {
						if ( to ) {
							$( 'head' ).append( '<style class="cz_custom_css1">' + to + '</style>' );
						} else {
							$( '.cz_custom_css1' ).remove();
						}

					// Custom CSS
					} else if ( is( opt, 'css' ) ) {
						if ( to ) {
							$( 'head' ).append( '<style class="cz_custom_css2">' + to + '</style>' );
						} else {
							$( '.cz_custom_css2' ).remove();
						}

					// Read more
					} else if ( to && is( opt, 'readmore' ) ) {
						$( '.cz_readmore span' ).html( to );

					// Read more icon
					} else if ( to && is( opt, 'readmore_icon' ) ) {
						if ( $( '.cz_readmore i' ).length ) {
							$( '.cz_readmore i' ).attr( 'class', to );
						} else {
							refresh = 1;
						}

					// Logo
					} else if ( to && is( opt, 'logo' ) ) {
						if ( $( '.logo img' ).length ) {
							$( '.logo img' ).attr( 'src', to );
						} else {
							refresh = 1;
						}

					// Logo alt
					} else if ( to && is( opt, 'logo_2' ) ) {
						if ( $( '.logo_2 img' ).length ) {
							$( '.logo_2 img' ).attr( 'src', to );
						} else {
							refresh = 1;
						}

					// Breadcrumbs separator icon
					} else if ( to && is( opt, 'breadcrumbs_separator' ) && $( '.breadcrumbs > i' ).length ) {
						$( '.breadcrumbs > i' ).attr( 'class', to );

					// Back to top
					} else if ( to && is( opt, 'backtotop' ) && $( '.backtotop' ).length ) {
						$( '.backtotop' ).removeClass(function( i, c ) {
							return (c.match(/(^|\s)fa-\S+|(^|\s)czico-\S+/g) || []).join(' ');
						}).addClass( to );

					// Fixed CF7 icon
					} else if ( to && is( opt, 'cf7_beside_backtotop_icon' ) && $( 'i.fixed_contact' ).length ) {
						$( 'i.fixed_contact' ).removeClass(function( i, c ) {
							return (c.match(/(^|\s)fa-\S+|(^|\s)czico-\S+/g) || []).join(' ');
						}).addClass( to );

					// Prev Posts
					} else if ( is( opt, 'prev_post' ) ) {
						$( '.next_prev .previous small' ).html( to );

					// Next Posts
					} else if ( is( opt, 'next_post' ) ) {
						$( '.next_prev .next small' ).html( to );

					// empty
					} else if ( ! to ) {
						refresh = 1;
					}

					// Refresh preview
					if ( refresh ) {
						wp.customize.preview.send( 'refresh' );
					}

					// Temp: Fixed side changes
					if ( $( '.fixed_side' ).length ) {
						$( '.fixed_side' ).css( 'height', $(window).height() - parseInt( $( '#layout' ).css( 'marginTop' ) + body.css( 'marginTop' ) ) );
						$( '.inner_layout' ).css( 'width', '100%' ).css( 'width', '-=' + $( '.fixed_side .theiaStickySidebar' ).outerWidth() );
					}

					// Fix page and header sizes
					clearTimeout( timeout );
					timeout = setTimeout(function() {
						$( window ).trigger( 'resize' );
					}, 200 );

				}); // End bind live changes

			}); // End wp.customize option

		}); // End each options

	} // End check wp.customize

}); // End jquery