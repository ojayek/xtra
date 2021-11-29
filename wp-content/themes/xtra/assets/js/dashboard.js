/*jQuery(document).ready(function(){
	var sd_pointers = xtraPointers;
	setTimeout( init_sd_pointers, 800 );
	function init_sd_pointers() {
		jQuery.each( sd_pointers.pointers, function( i ) {
			show_sd_pointer( i );
			return false;
		});
	}
	function show_sd_pointer( id ) {
		var pointer = sd_pointers.pointers[ id ];
		if( undefined == pointer ){
			return;
		}
		var options = jQuery.extend( pointer.options, {
			pointerClass: 'wp-pointer wc-pointer',
			close: function() {
				
				if ( pointer.next && 'publish-deal' != pointer.next ) {
					show_sd_pointer( (id+1) );
					jQuery('html, body').animate({
					    scrollTop: jQuery("#wp-pointer-"+(id+1) ).offset().top
					}, 1000);
				} else {

	                $.get(ajaxurl, {
	                    notice_name: index,
	                    _ajax_nonce: wp_reset_pointers._nonce_dismiss_pointer,
	                    action: 'wp_reset_dismiss_notice'
	                });

				}
			},
			buttons: function( event, t ) {
				var close   = 'Dismiss',
					next    = 'Next',
					button = jQuery( '<a class=\"button button-primary\" style=\"margin-left: 15px;margin-top: -5px;\" href=\"#\">' + next + '</a>' ),
					button2  = jQuery( '<a class=\"close\" href=\"#\">' + close + '</a>' ),
					wrapper = jQuery( '<div class=\"wc-pointer-buttons\" />' );

				button.bind( 'click.pointer', function(e) {
					e.preventDefault();
					t.element.pointer('destroy');
				});

				button2.bind( 'click.pointer', function(e) {
					e.preventDefault();
					t.element.pointer('close');
				});

				wrapper.append( button );
				wrapper.append( button2 );

				return wrapper;
			},
		} );
		var this_pointer = jQuery( pointer.target ).pointer( options );
		this_pointer.pointer( 'open' );

		if ( pointer.next_trigger ) {
			jQuery( pointer.next_trigger.target ).on( pointer.next_trigger.event, function() {
				setTimeout( function() { this_pointer.pointer( 'close' ); }, 400 );
			});
		}
	}
});*/

jQuery( function( $ ) {

	var args 		= {},
		allPv 		= 0,
		nonce 		= $( '.xtra-wizard' ).attr( 'data-nonce' ),
		progress 	= $( '.xtra-wizard-progress div' ),
		modalBox 	= $( '.xtra-modal' ),
		rtlOption 	= $( '.xtra-rtl' ),
		importerAJAX = null,
		timeout 	= 0,
		importerDone = function( hasError ) {

			if ( ! hasError ) {
				progress.css( 'width', '99%' ).find( 'span' ).html( '99%' );
			}

			setTimeout( function() {

				$( 'body' ).removeClass( 'xtra-importing' );
				$( '.xtra-demo-image' ).css( 'opacity', '1' );
				$( '.xtra-wizard-next' ).trigger( 'click' );

				if ( ! hasError ) {
					$( '.xtra-demo-error' ).hide();
					$( '.xtra-demo-success' ).show();
				}

				$( '.xtra-wizard-progress, .xtra-back, .xtra-importer-spinner, .xtra-wizard-footer' ).hide();

			}, 1500 );

		},
		importerError = function( message ) {

			importerDone( true );

			$( '.xtra-demo-success' ).hide();
			$( '.xtra-demo-error' ).show().find( 'p' ).html( message );

		},
		inViewport = function( e, offset ) {

			var offset 			= offset || 0,
				docViewTop 		= $( window ).scrollTop(),
				docViewBottom 	= docViewTop + $( window ).height(),
				elemTop 		= e.offset().top,
				elemBottom 		= elemTop + e.height();

			return ( ( elemTop <= docViewBottom + offset ) && ( elemBottom >= docViewTop - offset ) );

		},
		progressBar = function( li, allPv, images ) {

			var current = parseFloat( progress.attr( 'data-current' ) );

			if ( images ) {
				var value = ( current + ( ( 100 - allPv ) / images ) );
			} else {
				var value = ( current + ( parseFloat( li.attr( 'data-pv' ) ) * ( 100 / allPv ) ) );
			}

			if ( value > 99 ) {
				value = 99;
			}

			progress.css( 'width', Math.round( value ) + '%' ).attr( 'data-current', value ).find( 'span' ).html( Math.round( value ) + '%' );

		};

		function attachment_importer( xml, li, startCurrent ) {

			var number = 0,
				attachments = {},
				failedAttachments = 0,
				failedAttachments2 = 0,
				importedNumber = 0,
				imageName = $( 'li[data-name="images"] b' );

			$( $.parseXML( xml ) ).find( 'item' ).each( function( k, v ) {

				var $this = $( this ),
					post_type = $this.find( 'wp\\:post_type, post_type' ).text();

				// We're only looking for images.
				if ( post_type == 'attachment' ) {

					attachments[ number++ ] = {

						url: $this.find( 'wp\\:attachment_url, attachment_url' ).text(),
						post_title: $this.find( 'title' ).text(),
						link: $this.find( 'link' ).text(),
						pubDate: $this.find( 'pubDate' ).text(),
						guid: $this.find( 'guid' ).text(),
						import_id: $this.find( 'wp\\:post_id, post_id' ).text(),
						post_date: $this.find( 'wp\\:post_date, post_date' ).text(),
						post_date_gmt: $this.find( 'wp\\:post_date_gmt, post_date_gmt' ).text(),
						post_name: $this.find( 'wp\\:post_name, post_name' ).text(),
						post_status: $this.find( 'wp\\:status, status' ).text(),
						post_parent: $this.find( 'wp\\:post_parent, post_parent' ).text(),
						post_type: post_type,

					};

				}

			});

			var max = Object.keys( attachments ).length;

			function import_attachments( i ) {

				imageName.html( '(' + ( i + 1 ) + ' ' + xtraWizard.of + ' ' + max + ')' );

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'attachment_importer_upload',
						nonce: nonce,
						attachment: attachments[i]
					}
				}).done( function( data, status, xhr ) {

					var obj = JSON.parse( data );

					//console.log( obj );

					// If error shows the server did not respond, try again.
					if( obj.message == "Remote server did not respond" && failedAttachments < 3 ){

						failedAttachments++;

						imageName.html( '(' + ( i + 1 ) + ' ' + xtraWizard.of + ' ' + max + ')' );

						setTimeout( function() {
							import_attachments( i );
						}, 5000 );

					// If a non-fatal error occurs, note it and move on.
					} else if( obj.type == "error" && !obj.fatal ) {

						progressBar( li, startCurrent, max );

						next_image( i );

					// Fatal error.
					} else if( obj.fatal ) {

						importerError( obj.text );

						return false;

					} else {

						progressBar( li, startCurrent, max );

						importedNumber = i + 1;

						next_image( i );

					}

				} ).fail( function( xhr, status, error ) {

					failedAttachments2++;

					if ( failedAttachments2 < 20 ) {

						import_attachments( importedNumber );

					} else if ( xhr.status == 500 ) {

						importerError( xtraWizard.error_500 );

					} else if ( xhr.status == 503 ) {

						importerError( xtraWizard.error_503 );

					} else {

						importerError( error || xtraWizard.ajax_error );

					}

					console.error( xhr, status, error );

				} );
			}

			function next_image( i ) {

				i++;
				failedAttachments = 0;

				var listX = $( '.xtra-list' );

				// Continue next image.
				if ( attachments[i] ) {

					import_attachments( i );

				// Import sldier.
				} else if ( listX.find( 'li[data-name="slider"]' ).length ) {

					var liLast = $( '.xtra-list li' ).length;

					listX.find( 'li:nth-child(' + ( liLast - 1 ) + ')' ).removeClass( 'xtra-current' ).addClass( 'xtra-done' ).prepend( '<span class="checkmark"></span>' );

					importerAJAX( liLast, 'slider', 'import', false );

				} else {

					importerDone();

				}

			}

			if ( attachments[0] ) {

				import_attachments( 0 );

			} else {

				importerError( 'There were no attachment files found in the XML file.' );

			}

		}

	// Lazyload demos.
	$( window ).on( 'scroll.xtra', function() {

		$( '.xtra-lazyload [data-src]' ).each( function() {

			var $this = $( this );

			if ( inViewport( $this, 100 ) ) {

				$this.attr( 'src', $this.attr( 'data-src' ) ).addClass( 'lazyDone' );

				if ( ! $( '.xtra-demos img' ).not( '.lazyDone' ).length ) {

					$( window ).off( 'scroll.xtra' );

				}

			}

		});

	}).trigger( 'scroll.xtra' );

	// Make external link target _blank.
	$( 'a[href*="xtra-docs"],a[href*="xtra-videos"],a[href*="xtra-faq"],a[href*="xtra-support"],a[href*="xtra-changelog"]' ).attr( 'target', '_blank' );

	// Search in demos.
	var searchDemos = function( value ) {

		var timeOut = 0;

		$( '.xtra-demos > div' ).each( function() {

			var $this = $( this );

			if ( $this.text().search( new RegExp( value, 'i' ) ) < 0 ) {
				$this.hide();
			} else {
				$this.show();
			}

			clearTimeout( timeOut );

			timeOut = setTimeout( function() {
				$( window ).trigger( 'scroll.xtra' );
			}, 250 );

		});

	};

	// Search demos.
	$( 'body' ).on( 'keyup', '.xtra-filters [name="search"]', function( e ) {

		searchDemos( $( this ).val() );

		e.preventDefault();

	// Filters.
	}).on( 'click', '.xtra-filters a', function( e ) {

		$( this ).addClass( 'xtra-current' ).siblings().removeClass( 'xtra-current' );

		searchDemos( $( this ).attr( 'data-filter' ) );

		e.preventDefault();

	// Demo importer wizard start.
	}).on( 'click', '.xtra-demos a[data-args]', function( e ) {

		args = JSON.parse( $( this ).attr( 'data-args' ) ),
		rtlImage = args.image.replace( args.demo, 'rtl/' + args.demo );

		// Scroll to top.
		$( 'html, body' ).animate({ scrollTop: $( '.xtra-dashboard-main' ).offset().top - 100 }, 1000 );

		// Show wizard.
		$( '.xtra-demo-importer' ).slideUp( 'normal', function() {
			$( '.xtra-wizard' ).slideDown( 'normal' );
		});

		// Reset progress.
		progress.css( 'width', '0%' ).find( 'span' ).html( '' );

		// Opacity wizard buttons.
		$( '.xtra-wizard-footer > a' ).css( 'opacity', '1' );

		// Reset to step 1.
		$( '[data-step="1"]' ).addClass( 'xtra-current' ).siblings().removeClass( 'xtra-current' );

		// Show footer.
		$( '.xtra-wizard-footer' ).show();

		// Set image.
		$( '.xtra-demo-image' ).attr( 'src', args.image );

		// Set title.
		$( '.xtra-wizard-selected strong' ).html( args.title ? args.title : args.demo.replace( /-/g, ' ' ) );

		// Set live preview.
		$( '.xtra-live-preview' ).attr( 'href', args.preview );

		if ( args.preview.indexOf( 'arabic' ) >= 0 ) {
			$( '.xtra-live-preview-elementor' ).attr( 'href', args.preview.replace( '/' + args.demo, '-elementor/' + args.demo ) );
		} else {
			$( '.xtra-live-preview-elementor' ).attr( 'href', args.preview.replace( args.demo, 'elementor/' + args.demo ) );
		}

		// Hide prev step button.
		$( '.xtra-wizard-footer .xtra-wizard-prev' ).attr( 'disabled', 'disabled' );

		// Check Elementor.
		$( '[name="pagebuilder"][value="elementor"]' )[ ! args.elementor ? 'attr' : 'removeAttr' ]( 'disabled', 'disabled' );

		// Check WPBakery js_composer.
		$( '[name="pagebuilder"][value="js_composer"]' ).trigger( 'click' );

		// RTL checkbox.
		rtlOption[ args.rtl ? 'removeAttr' : 'attr' ]( 'disabled', 'disabled' ).find( '[name="rtl"]' ).prop( 'checked', false );

		var lang = $( 'html' ).attr( 'lang' );

		if ( args.rtl && $( 'body' ).hasClass( 'rtl' ) && ( lang === 'ar' || lang === 'ary' ) ) {

			rtlOption.find( '[name="rtl"]' ).trigger( 'click' );

		}

		// Check if demo have slider.
		$( '[name="slider"]' ).parent()[ ( args.plugins && args.plugins.revslider == false ) ? 'hide' : 'show' ]();

		e.preventDefault();

	// RTL demo preview.
	}).on( 'click', '[name="pagebuilder"]', function( e ) {

		args.rtl && rtlOption.removeAttr( 'disabled' );

		if ( $( this ).val() === 'elementor' ) {

			if ( args.rtl && ! args.rtl.elementor ) {

				rtlOption.attr( 'disabled', 'disabled' );

				if ( rtlOption.find( '[name="rtl"]' ).is( ':checked' ) ) {
					rtlOption.find( '[name="rtl"]' ).trigger( 'click' );
				}

			}

		}

	// Tooltip.
	}).on( 'mouseenter', '[data-tooltip]', function( e ) {

		var $this = $( this );

		if ( ! $this.find( '.xtra-tooltip' ).length ) {

			$this.append( '<div class="xtra-tooltip">' + $this.attr( 'data-tooltip' ) + '</div>' );

		}

	// RTL demo preview.
	}).on( 'click', '[name="rtl"]', function( e ) {

		var checked = $( this ).is( ':checked' );

		if ( ! checked && ( ( ! args.elementor && args.rtl.elementor ) || ! args.elementor ) ) {

			$( '[name="pagebuilder"][value="elementor"]' ).attr( 'disabled', 'disabled' );
			$( '[name="pagebuilder"][value="js_composer"]' ).trigger( 'click' );

		} else if ( ( checked && args.rtl.elementor ) || ( ! checked && ! args.rtl.elementor ) ) {

			$( '[name="pagebuilder"][value="elementor"]' ).removeAttr( 'disabled' );

		} else if ( checked && ! args.rtl.elementor ) {

			$( '[name="pagebuilder"][value="elementor"]' ).attr( 'disabled', 'disabled' );

		}

		$( '.xtra-demo-image' ).attr( 'src', checked ? args.image.replace( 'rtl/', '' ).replace( args.demo, 'rtl/' + args.demo ) : args.image.replace( 'rtl/', '' ) );

		$( '.xtra-live-preview' ).attr( 'href', checked ? args.preview.replace( 'arabic/', '' ).replace( args.demo, 'arabic/' + args.demo ) : args.preview.replace( 'arabic/', '' ) );

	// Import full or custom.
	}).on( 'click', '[name="config"]', function( e ) {

		$( '.xtra-checkboxes' )[ $( this ).val() === 'custom' ? 'removeAttr' : 'attr' ]( 'disabled', 'disabled' );
	
	// Custom import activate.
	}).on( 'click', '.xtra-checkboxes', function( e ) {

		$( '[name="config"][value="custom"]' ).trigger( 'click' );

	// Next and prev steps buttons.
	}).on( 'click', '.xtra-wizard-prev, .xtra-wizard-next', function( e ) {

		if ( $( 'body' ).hasClass( 'xtra-importing' ) ) {
			return false;
		}

		var isNext 	= $( this ).hasClass( 'xtra-wizard-next' ),
			current = parseInt( $( '.xtra-wizard-steps .xtra-current' ).attr( 'data-step' ) ),
			step 	= ( isNext ? current + 1 : current - 1 ),
			isFull 	= $( '[name="config"]:checked' ).val() === 'full';

		if ( step >= 1 && step <= 5 ) {

			$( '.xtra-wizard-footer .xtra-wizard-prev' )[ step !== 1 ? 'removeAttr' : 'attr' ]( 'disabled', 'disabled' );

			// Validate check list.
			if ( isNext && step === 4 && ! isFull ) {

				var list = $( '.xtra-checkboxes input:checkbox:checked' ).map( function() {
						return this.value;
					}).get();

				if ( ! list.length ) {

					alert( xtraWizard.features );

					return false;

				}

			}

			// Set step.
			$( '.xtra-wizard-steps li[data-step="' + step + '"]' ).addClass( 'xtra-current' ).siblings().removeClass( 'xtra-current' );

			// Change content step.
			$( '.xtra-wizard-content [data-step="' + step + '"]' ).addClass( 'xtra-current' ).siblings().removeClass( 'xtra-current' );

			// Start importing.
			if ( step === 4 ) {

				// Disable all links and only wait for import.
				$( 'body' ).addClass( 'xtra-importing' );

				// Hide back to demos.
				$( '.xtra-back' ).hide();

				// Opacity preview image.
				$( '.xtra-demo-image' ).css( 'opacity', '.2' );

				// Opacity wizard buttons.
				$( '.xtra-wizard-footer > a' ).css( 'opacity', '.3' );

				// Show progress bar.
				$( '.xtra-wizard-progress, .xtra-importer-spinner' ).show();

				// Checks.
				var list = $( '.xtra-list' ),
					pagebuilder = $( '[name="pagebuilder"]:checked' ).val(),
					isPluginInactive = function( slug ) {
						return xtraWizard.plugins[ slug ];
					},
					pluginBefore = '<span class="xtra-list-before">' + xtraWizard.plugin_before + '</span>',
					pluginAfter = '<span class="xtra-list-after">' + xtraWizard.plugin_after + '</span>',
					importBefore = '<span class="xtra-list-before">' + xtraWizard.import_before + '</span>',
					importAfter = '<span class="xtra-list-after">' + xtraWizard.import_after + '</span>';

				list.empty();

				// Plugins.
				if ( isPluginInactive( 'codevz-plus' ) ) {
					list.append( '<li data-name="codevz-plus" data-type="plugin" data-pv="5" class="xtra-current">' + pluginBefore + xtraWizard.codevz_plus + pluginAfter + '</li>' );
				}

				if ( pagebuilder === 'js_composer' && isPluginInactive( 'js_composer' ) ) {
					list.append( '<li data-name="js_composer" data-type="plugin" data-pv="7">' + pluginBefore + xtraWizard.js_composer + pluginAfter + '</li>' );
				} else if ( pagebuilder === 'elementor' && isPluginInactive( 'elementor' ) ) {
					list.append( '<li data-name="elementor" data-type="plugin" data-pv="5">' + pluginBefore + xtraWizard.elementor + pluginAfter + '</li>' );
				}

				if ( ( ! args.plugins || ( args.plugins && args.plugins.revslider != false ) ) && isPluginInactive( 'revslider' ) && ( isFull || $( '[name="slider"]' ).is( ':checked' ) ) ) {
					list.append( '<li data-name="revslider" data-type="plugin" data-pv="7">' + pluginBefore + xtraWizard.revslider + pluginAfter + '</li>' );
				}

				if ( isPluginInactive( 'contact-form-7' ) ) {
					list.append( '<li data-name="contact-form-7" data-type="plugin" data-pv="3">' + pluginBefore + xtraWizard.cf7 + pluginAfter + '</li>' );
				}

				if ( isPluginInactive( 'woocommerce' ) && ( isFull || $( '[name="woocommerce"]' ).is( ':checked' ) ) ) {
					list.append( '<li data-name="woocommerce" data-type="plugin" data-pv="4">' + pluginBefore + xtraWizard.woocommerce + pluginAfter + '</li>' );
				}

				// Additional Plugins.
				args.plugins && $.each( args.plugins, function( plugin, value ) {

					value && isPluginInactive( plugin ) && list.append( '<li data-name="' + plugin + '" data-type="plugin" data-pv="5">' + pluginBefore + plugin + pluginAfter + '</li>' );

				});

				// Download demo file.
				list.append( '<li data-name="download" data-type="download" data-pv="8"><span class="xtra-list-before">' + xtraWizard.downloading + '</span>' + xtraWizard.demo_files + '<span class="xtra-list-after">' + xtraWizard.downloaded + '</span></li>' );

				// Demo features.
				if ( isFull || $( '[name="options"]' ).is( ':checked' ) ) {
					list.append( '<li data-name="options" data-type="import" data-pv="2">' + importBefore + xtraWizard.options + importAfter + '</li>' );
				}
				if ( isFull || $( '[name="widgets"]' ).is( ':checked' ) ) {
					list.append( '<li data-name="widgets" data-type="import" data-pv="1">' + importBefore + xtraWizard.widgets + importAfter + '</li>' );
				}
				if ( isFull || $( '[name="content"]' ).is( ':checked' ) ) {
					list.append( '<li data-name="content" data-type="import" data-pv="15">' + importBefore + xtraWizard.posts + importAfter + '<b></b></li>' );
				}
				if ( isFull || $( '[name="images"]' ).is( ':checked' ) ) {
					list.append( '<li data-name="images" data-type="import" data-pv="80">' + importBefore + xtraWizard.images + importAfter + '<b></b></li>' );
				}
				if ( isFull || ( ! args.plugins || ( args.plugins && args.plugins.revslider != false ) ) && $( '[name="slider"]' ).is( ':checked' ) ) {
					list.append( '<li data-name="slider" data-type="import" data-pv="3">' + importBefore + xtraWizard.slider + importAfter + '</li>' );
				}

				var failedAjax = 0,
					folder = '';

				// Change API to RTL.
				if ( $( '[name="rtl"]' ).is( ':checked' ) && args.rtl && args.rtl[ pagebuilder ] ) {
					folder = 'rtl';
				}

				// Change API to elementor.
				if ( pagebuilder === 'elementor' ) {

					folder = folder ? folder + '-' : '';
					folder = folder + 'elementor';

				}

				list.find( 'li' ).each( function() {
					allPv += parseInt( $( this ).attr( 'data-pv' ) );
				});

				// Wizard AJAX function.
				importerAJAX = function( step, name, type, posts ) {

					var li = list.find( 'li:nth-child(' + step + ')' );

					// Add loading spinner.
					if ( ! li.find( '.xtra-loading' ).length ) {

						li.prepend( '<i class="xtra-loading" aria-hidden="true"></i>' );

					}

					// Start.
					li.addClass( 'xtra-current' ).siblings().removeClass( 'xtra-current' );

					// Send.
					$.ajax(
						{
							type: 'POST',
							url: ajaxurl + '?force_delete_kit',
							data: {
								action: 'xtra_wizard',
								demo: args.demo,
								step: step,
								name: name,
								type: type,
								posts: posts,
								nonce: nonce,
								folder: folder
							},
							success: function( obj ) {

								//console.log( obj );

								if ( ! obj ) {

									importerError( '1. ' + xtraWizard.ajax_error );

									return false;

								}

								if ( typeof obj !== 'object' ) {

									// Fix redirects after plugin install.
									if ( obj.indexOf( '<body' ) >= 0 ) {

										importerAJAX( step, 'redirect' );

										return false;

									}

									// Sanitize response and extract object.
									obj = JSON.parse( '{' + obj.substring( obj.lastIndexOf( '{' ) + 1, obj.lastIndexOf( '}' ) ) + '}' );

								}

								// Failed step.
								if ( failedAjax == 3 ) {

									importerError( obj.message || xtraWizard.ajax_error );

									return false;

								// Automatic try again upto 3 times.
								} else if ( ! obj || obj.status === '202' || obj.nonce ) {

									failedAjax++;

									importerAJAX( step, name, type );

									return false;
								}

								// Continue content.
								if ( obj.posts ) {

									importerAJAX( step, name, type, obj.posts );

									//var contentB = $( 'li[data-name="content"] b' );

									//if ( ! contentB.attr( 'data-max' ) && obj.xml ) {

									//	var max = 0;

									//	$( $.parseXML( obj.xml ) ).find( 'item' ).each( function( k, v ) {

									//		max++;

									//	});

									//	contentB.attr( 'data-max', max );

									//}

									// Set X of max.
									//contentB.html( '(' + obj.posts + ' ' + xtraWizard.of + ' ' + contentB.attr( 'data-max' ) + ')' );

									// Progress bar.
									var current = parseInt( progress.text() ) + ( Math.floor( Math.random() * 2 ) + 1 );

									progress.css( 'width', current + '%' ).attr( 'data-current', current ).find( 'span' ).html( current + '%' );

									return false;

								// Import images.
								} else if ( obj.xml ) {

									attachment_importer( obj.xml, li, parseInt( progress.text() ) + 4 );

									return false;

								}

								// Progress bar.
								progressBar( li, allPv );

								// Add checkmark.
								li.removeClass( 'xtra-current' ).addClass( 'xtra-done' ).prepend( '<span class="checkmark"></span>' );

								// Next item.
								if ( step < list.find( 'li' ).length ) {

									var next = li.next().addClass( 'xtra-current' );

									importerAJAX( ++step, next.attr( 'data-name' ), next.attr( 'data-type' ) );

								} else {

									importerDone();

								}

							},
							error: function( xhr, type, message ) {

								if ( xhr.status == 500 ) {

									importerError( xtraWizard.error_500 );

								} else if ( xhr.status == 503 ) {

									importerError( xtraWizard.error_503 );

								} else {
									
									importerError( message || xtraWizard.ajax_error );

								}

								console.log( xhr, type, message );

							}
						}
					);

				};

				var li = list.find( 'li:nth-child(1)' );

				importerAJAX( 1, li.attr( 'data-name' ), li.attr( 'data-type' ) );

			}

		}

		e.preventDefault();

	// Back to demos.
	}).on( 'click', '.xtra-back, .xtra-back-to-demos', function( e ) {

		if ( $( 'body' ).hasClass( 'xtra-importing' ) ) {
			return false;
		}

		// Hide wizard.
		$( '.xtra-wizard' ).slideUp( 'normal', function() {
			$( '.xtra-demo-importer' ).slideDown( 'normal' );
		});

		e.preventDefault();

	// Uninstall demo.
	}).on( 'click', '.xtra-uninstall-button', function( e ) {

		if ( $( '.xtra-uninstall' ).length ) {

			modalBox.attr( 'data-demo', $( this ).attr( 'data-demo' ) ).fadeIn();

			e.preventDefault();

		}

	// Uninstalled reload button.
	}).on( 'click', '.xtra-reload', function( e ) {

		window.location.reload( true );

		e.preventDefault();

	// Uninstall demo after confirm.
	}).on( 'click', '.xtra-modal .xtra-button-primary', function( e ) {

		var $this = $( this ),
			title = $this.html(),
			demo = modalBox.attr( 'data-demo' ),
			done = modalBox.find( '.xtra-uninstalled h2' );

		done.html( done.html().replace( 'DEMONAME', demo ).replace( /-/g, ' ' ) );

		$this.html( $this.attr( 'data-title' ) );

		modalBox.addClass( 'xtra-current' ).find( '.xtra-uninstall-msg .xtra-button-secondary' ).hide();

		$.ajax(
			{
				type: 'POST',
				url: ajaxurl + '?force_delete_kit',
				data: {
					action: 'xtra_wizard',
					nonce: modalBox.attr( 'data-nonce' ),
					demo: demo,
					name: 'uninstall',
					type: 'uninstall'
				},
				success: function( obj ) {

					modalBox.removeClass( 'xtra-current' );

					$( '.xtra-uninstalled' ).show();
					$( '.xtra-uninstall-msg' ).hide();

					$( '.xtra-demo .xtra-button-primary[data-demo="' + demo + '"]' ).closest( '.xtra-demo' ).remove();

				//	console.log( obj );

				},
				error: function( xhr, type, message ) {

					if ( xhr.status == 500 ) {

						modalBox.find( 'p' ).html( xtraWizard.error_500 );

					} else if ( xhr.status == 503 ) {

						modalBox.find( 'p' ).html( xtraWizard.error_503 );

					} else {

						modalBox.find( 'p' ).html( message || xtraWizard.ajax_error );

					}

					console.log( xhr, type, message );

					$this.html( title );

					modalBox.removeClass( 'xtra-current' ).find( '.xtra-uninstall-msg .xtra-button-secondary' ).show();

				}
			}
		);

		e.preventDefault();

	// Modal close
	}).on( 'click', '.xtra-modal .xtra-button-secondary:not(.xtra-reload)', function( e ) {

		modalBox.fadeOut();

		e.preventDefault();

	// Plugins installation error close icon.
	}).on( 'click', '.xtra-error-close', function( e ) {

		$( this ).parent().remove();

		e.preventDefault();

	// Plugins installation.
	}).on( 'click', '.xtra-plugin-footer a', function( e ) {

		var $this = $( this ),
			title = $this.html(),
			pluginError = function( message ) {

				$this.closest( '.xtra-plugin' ).append( '<div class="xtra-dashboard-error"><i class="dashicons dashicons-no-alt"></i><span>' + message + '</span><a href="#" class="xtra-button-secondary xtra-error-close">' + xtraWizard.close + '</a></div>' );

			};

		$this.addClass( 'xtra-button-secondary xtra-current' ).attr( 'disabled', 'disabled' );
		$this.find( 'span' ).html( $this.attr( 'data-title' ) );

		$this.closest( '.xtra-plugin' ).removeClass( 'xtra-plugin-done' ).addClass( 'xtra-plugin-doing' );

		$.ajax(
			{
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'xtra_wizard',
					demo: null,
					type: 'plugin',
					name: $this.attr( 'data-plugin' ),
					nonce: $this.closest( '.xtra-plugins' ).attr( 'data-nonce' )
				},
				success: function( obj ) {

					// Sanitize response.
					if ( typeof obj !== 'object' ) {
						obj = JSON.parse( '{' + obj.substring( obj.lastIndexOf( '{' ) + 1, obj.lastIndexOf( '}' ) ) + '}' );
					}

					// Check errors.
					if ( obj.status == 202 ) {

						pluginError( obj.message );

						$this.html( title ).removeClass( 'xtra-button-secondary xtra-current' ).removeAttr( 'disabled' );

					} else {

						// Plugin installed successfully.
						$this.html( title ).addClass( 'hidden' ).next().removeClass( 'hidden' );

					}

					$this.closest( '.xtra-plugin' ).addClass( 'xtra-plugin-done' );

				},
				error: function( xhr, type, message ) {

					if ( xhr.status == 500 ) {

						pluginError( xtraWizard.error_500 );

					} else {

						pluginError( message || xtraWizard.ajax_error );

					}

					console.log( xhr, type, message );

					$this.closest( '.xtra-plugin' ).removeClass( 'xtra-plugin-done xtra-plugin-doing' );

					$this.html( title ).removeClass( 'xtra-button-secondary xtra-current' ).removeAttr( 'disabled' );

				}
			}
		);

		e.preventDefault();

	// Feedback form submission.
	}).on( 'click', '.xtra-feedback-form a', function( e ) {

		var $this 	= $( this ),
			message = tinymce.activeEditor.getContent(),
			messageSpan = $( '.xtra-feedback-message' );

		messageSpan.hide();

		if ( ! message ) {

			messageSpan.html( xtraWizard.feedback_empty ).show();

			return false;

		}

		$this.addClass( 'xtra-current' ).attr( 'disabled', 'disabled' );

		$.ajax(
			{
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'xtra_feedback',
					message: message,
					nonce: $this.attr( 'data-nonce' )
				},
				success: function( obj ) {

					messageSpan.html( obj.message ).show();

					$this.removeClass( 'xtra-current' ).removeAttr( 'disabled' );

				},
				error: function( xhr, type, message ) {

					if ( xhr.status == 500 ) {

						messageSpan.html( xtraWizard.error_500 ).show();

					} else {

						messageSpan.html( message || xtraWizard.ajax_error ).show();

					}

					console.log( xhr, type, message );

					$this.removeClass( 'xtra-current' ).removeAttr( 'disabled' );

				}
			}
		);

		e.preventDefault();

	// Single page importer.
	}).on( 'click', '.xtra-page-importer-form .xtra-button-primary', function( e ) {

		var $this 		= $( this ),
			input 		= $( '.xtra-page-importer-form input' ).val(),
			messageSpan = $( '.xtra-page-importer-message' );

		messageSpan.hide();

		if ( ! input ) {

			messageSpan.html( xtraWizard.page_importer_empty ).show();

			return false;

		}

		$this.addClass( 'xtra-current' ).attr( 'disabled', 'disabled' );

		$.ajax(
			{
				type: 'POST',
				url: ajaxurl,
				data: {
					url: input,
					action: 'xtra_page_importer',
					nonce: $this.attr( 'data-nonce' )
				},
				success: function( obj ) {

					if ( obj.link ) {

						obj.message += '<br /><br /><a href="' + obj.link + '" class="xtra-dashboard-icon-box xtra-dashboard-icon-box-info" target="_blank"><i class="dashicons dashicons-admin-links"></i><div>' + obj.link + '</div></a>';

					}

					messageSpan.html( obj.message ).show();

					$this.removeClass( 'xtra-current' ).removeAttr( 'disabled' );

					$( '.xtra-page-importer-form input' ).val( '' );

				},
				error: function( xhr, type, message ) {

					if ( xhr.status == 500 ) {

						messageSpan.html( xtraWizard.error_500 ).show();

					} else {

						messageSpan.html( message || xtraWizard.ajax_error ).show();

					}

					console.log( xhr, type, message );

					$this.removeClass( 'xtra-current' ).removeAttr( 'disabled' );

				}
			}
		);

		e.preventDefault();

	});

});