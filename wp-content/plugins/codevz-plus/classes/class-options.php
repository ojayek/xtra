<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

/**
 * Options and page settings
 * 
 * @author XtraTheme
 * @link https://xtratheme.com/
 */

class Codevz_Options {

	private static $trasnlation;

	public function __construct() {

		// Regenerate css_out.
		if ( get_option( 'xtra_generate_css_out_version', 0 ) < 4416 ) {

			update_option( 'xtra_generate_css_out', true );
			update_option( 'xtra_generate_css_out_version', 4416 );

		}

		// Translate positions.
		self::$trasnlation = [
			'left' 		=> esc_html__( 'Left', 'codevz' ),
			'center' 	=> esc_html__( 'Center', 'codevz' ),
			'right' 	=> esc_html__( 'Right', 'codevz' ),
			'top' 		=> esc_html__( 'Top', 'codevz' ),
			'middle' 	=> esc_html__( 'Middle', 'codevz' ),
			'bottom' 	=> esc_html__( 'Bottom', 'codevz' ),
		];

		// Options & Metabox
		add_action( 'init', [ $this, 'init' ], 999 );

		// Save customize settings
		add_action( 'customize_save_after', [ $this, 'customize_save_after' ], 10, 2 );

		// Enqueue inline styles
		if ( ! isset( $_POST['vc_inline'] ) ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ], 999 );
		}

		// Update single page CSS
		add_action( 'save_post', [ $this, 'save_post' ], 9999 );
	}

	/**
	 * Initial theme options
	 */
	public function init() {

		if ( class_exists( 'CSF' ) ) {

			CSF_Customize::instance( self::options(), 'codevz_theme_options' );

			CSF_Metabox::instance( self::metabox() );

			// Taxonomy Meta
			$tax_meta = [];
			foreach ( [ 'post', 'portfolio', 'product' ] as $cpt ) {
				$tax_meta[] = [
					'id'       	=> 'codevz_cat_meta',
					'taxonomy' 	=> ( $cpt === 'post' ) ? 'category' : $cpt . '_cat',
					'fields' 	=> [
					  array(
						'id'        => '_css_page_title',
						'type'      => 'cz_sk',
						'title'     => esc_html__( 'Title Background', 'codevz' ),
						'button'    => esc_html__( 'Title Background', 'codevz' ),
						'settings'  => array( 'background' )
					  ),
					]
				];
			}
			CSF_Taxonomy::instance( $tax_meta );
		}

	}

	/**
	 *
	 * Add inline styles to front-end
	 * 
	 * @return string
	 *
	 */
	public function wp_enqueue_scripts() {

		// Single page dynamic CSS
		if ( is_singular() && isset( Codevz_Plus::$post->ID ) ) {
			$meta = get_post_meta( Codevz_Plus::$post->ID, 'codevz_single_page_css', 1 );

			if ( ! Codevz_Plus::contains( $meta, '.cz-page-' . Codevz_Plus::$post->ID ) ) {
				self::save_post( Codevz_Plus::$post->ID );
				$meta = get_post_meta( Codevz_Plus::$post->ID, 'codevz_single_page_css', 1 );
			}

			wp_add_inline_style( 'codevz-plugin', str_replace( 'Array', '', $meta ) );
		}

		// Options json for customize preview
		if ( is_customize_preview() ) {
			wp_add_inline_style( 'codevz-plugin', self::css_out( 1 ) );
			self::codevz_wp_footer_options_json();
		}
	}

	/**
	 * Get list of post types created via customizer
	 * 
	 * @return array
	 */
	public static function post_types( $a = array() ) {
		$a = array_merge( $a, (array) get_option( 'codevz_post_types' ) );
		$a[] = 'portfolio';

		// Custom post type UI
		if ( function_exists( 'cptui_get_post_type_slugs' ) ) {
			$cptui = cptui_get_post_type_slugs();
			if ( is_array( $cptui ) ) {
				$a = wp_parse_args( $cptui, $a );
			}
		}

		return $a;
	}

	public static function share_post_types() {

		$out = [];

		foreach ( self::post_types( array( 'post', 'page', 'product', 'download' ) ) as $cpt ) {

			if ( $cpt ) {

				$out[ $cpt ] = ucwords( $cpt );

			}
			
		}

		return $out;
	}

	/**
	 * Update single page CSS as metabox 'codevz_single_page_css'
	 * 
	 * @return string
	 */
	public function save_post( $post_id = '' ) {
		if ( empty( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		delete_post_meta( $post_id, 'codevz_single_page_css' );
		$meta = self::css_out( 0, (array) get_post_meta( $post_id, 'codevz_page_meta', true ), $post_id );
		if ( $meta ) {
			update_post_meta( $post_id, 'codevz_single_page_css', $meta );
		}
	}

	/**
	 * Get post type in admin area
	 * 
	 * @return string
	 */
	public static function get_post_type_admin() {
		global $post, $typenow, $pagenow, $current_screen;

		if ( $post && $post->post_type ) {
			return $post->post_type;
		} else if ( $typenow ) {
			return $typenow;
		} else if ( $current_screen && $current_screen->post_type ) {
			return $current_screen->post_type;
		} else if ( isset( $_REQUEST['post_type'] ) ) {
			return sanitize_key( $_REQUEST['post_type'] );
		} else if ( isset( $_REQUEST['post'] ) ) {
			return get_post_type( $_REQUEST['post'] );
		} else if ( 'post-new.php' === $pagenow ) {
			if ( isset( $_GET['post_type'] ) ) {
				return $_GET['post_type'];
			} else {
				return 'post';
			}
		}

		return null;
	}

	/**
	 *
	 * Generate styles when customizer saves
	 * 
	 * @return array
	 *
	 */
	public static function css_out( $is_customize_preview = 0, $single_page = 0, $post_id = '' ) {
		$out = $dynamic = $dynamic_tablet = $dynamic_mobile = '';
		$fonts = [];

		// Options
		$opt = $single_page ? (array) $single_page : (array) get_option( 'codevz_theme_options' );

		// Generating styles
		foreach ( $opt as $id => $val ) {
			if ( $val && Codevz_Plus::contains( $id, '_css_' ) ) {
				if ( is_array( $val ) || Codevz_Plus::contains( $val, '[' ) ) {
					continue;
				}

				// Temp fix for live customizer fonts generation
				if ( $is_customize_preview ) {
					if ( Codevz_Plus::contains( $val, 'font-family' ) ) {
						$fonts[]['font'] = $val;
					}
					continue;
				}

				// CSS Selector
				$selector = Codevz_Plus::contains( $id, '_css_page_body_bg' ) ? 'html,body' : self::get_selector( $id );
				if ( $single_page ) {
					$page_id = '.cz-page-' . $post_id;
					$selector = ( $selector === 'html,body' ) ? 'body' . $page_id : $page_id . ' ' . $selector;
					if ( Codevz_Plus::contains( $selector, ',' ) ) {
						$selector = str_replace( ',', ',' . $page_id . ' ', $selector );
					}
				}

				// Fix custom css
				$val = str_replace( 'CDVZ', '', $val );

				// RTL
				if ( Codevz_Plus::contains( $val, 'RTL' ) ) {
					$rtl = Codevz_Plus::get_string_between( $val, 'RTL', 'RTL' );
					$val = str_replace( array( $rtl, 'RTL' ), '', $val );
				}

				// Set font family
				if ( Codevz_Plus::contains( $val, 'font-family' ) ) {

					$fonts[]['font'] = $val;

					// Extract font + params && Fix font for CSS
					$font = $o_font = Codevz_Plus::get_string_between( $val, 'font-family:', ';' );
					$font = str_replace( '=', ':', $font );
					$font = str_replace( "''", "", $font );
					
					if ( Codevz_Plus::contains( $font, ':' ) ) {

						$font = explode( ':', $font );

						if ( ! empty( $font[0] ) ) {

							if ( ! Codevz_Plus::contains( $font[0], "'" ) ) {
								$font[0] = "'" . $font[0] . "'";
							}

							$val = str_replace( $o_font, $font[0], $val );

							if ( $id === '_css_body_typo' ) {
								$dynamic .= '[class*="cz_tooltip_"] [data-title]:after{font-family:' . $font[0] . '}';
							}

						}

					} else {

						if ( ! Codevz_Plus::contains( $font, "'" ) ) {
							$font = "'" . $font . "'";
						}

						$val = str_replace( $o_font, $font, $val );

						if ( $id === '_css_body_typo' ) {
							$dynamic .= '[class*="cz_tooltip_"] [data-title]:after{font-family:' . $font . '}';
						}

					}

				}

				// Remove unwanted in css
				if ( Codevz_Plus::contains( $val, '_class_' ) ) {
					$val = preg_replace( '/_class_[\s\S]+?;/', '', $val );
				}

				// Fix sticky styles priority and :focus
				if ( $id === '_css_container_header_5' || $id === '_css_row_header_5' || Codevz_Plus::contains( $selector, 'input:focus' ) ) {
					$val = str_replace( '!important', '', $val );
					$val = str_replace( ';', ' !important;', $val );
				}

				// Append to out
				if ( ! empty( $val ) && ! empty( $selector ) ) {
					if ( Codevz_Plus::contains( $id, '_tablet' ) ) {
						$dynamic_tablet .= $selector . '{' . $val . '}';
					} else if ( Codevz_Plus::contains( $id, '_mobile' ) ) {
						$dynamic_mobile .= $selector . '{' . $val . '}';
					} else {
						$dynamic .= $selector . '{' . $val . '}';
					}
				}

				// RTL
				if ( ! empty( $rtl ) && $selector ) {
					$sp = Codevz_Plus::contains( $selector, array( '.cz-cpt-', '.cz-page-', '.home', 'body', '.woocommerce' ) ) ? '' : ' ';
					$dynamic .= '.rtl' . $sp . preg_replace( '/,\s+|,/', ',.rtl' . $sp, $selector ) . '{' . $rtl . '}';
				}
				$rtl = 0;
			}
		}

		// Single title color
		$page_title_color = Codevz_Plus::meta( get_the_id(), 'page_title_color' );
		if ( $single_page && $page_title_color ) {
			$dynamic .= '.page_title .section_title,.page_title a,.page_title a:hover,.page_title i {color: ' . $page_title_color . '}';
		}

		// Final out
		if ( ! $is_customize_preview ) {
			$dynamic = $dynamic ? "\n\n/* Dynamic " . ( $single_page ? 'Single' : '' ) . " */" . $dynamic : '';
			if ( $single_page ) {
				$dynamic .= $dynamic_tablet ? '@media screen and (max-width:' . Codevz_Plus::option( 'tablet_breakpoint', '768px' ) . '){' . $dynamic_tablet . '}' : '';
				$dynamic .= $dynamic_mobile ? '@media screen and (max-width:' . Codevz_Plus::option( 'mobile_breakpoint', '480px' ) . '){' . $dynamic_mobile . '}' : '';
			}
		}

		$dynamic = str_replace( ';}', '}', $dynamic );

		// Single pages
		if ( $single_page ) {
			return $dynamic;
		}

		// Site Width & Boxed
		$site_width = empty( $opt['site_width'] ) ? 0 : $opt['site_width'];
		if ( $site_width ) {
			if ( empty( $opt['boxed'] ) ) {
				$out .= '.row,section.elementor-section.elementor-section-boxed>.elementor-container{width: ' . $site_width . '}';
			} else if ( $opt['boxed'] == '2' ) {
				$out .= '.layout_2,.layout_2 .cz_fixed_footer{width: ' . $site_width . '}.layout_2 .row{width: calc(' . $site_width . ' - 10%)}';
			} else {
				$out .= '.layout_1,.layout_1 .cz_fixed_footer{width: ' . $site_width . '}.layout_1 .row{width: calc(' . $site_width . ' - 10%)}';
			}
		}

		// Responsive CSS
		$bxw = empty( $opt['boxed'] ) ? '1240px' : '1300px';
		$rs1 = empty( $opt['site_width'] ) ? $bxw : ( Codevz_Plus::contains( $opt['site_width'], '%' ) ? '5000px' : $opt['site_width'] );

		// Responsive.
		$dynamic .= "\n\n/* Responsive */" . '@media screen and (max-width:' . $rs1 . '){#layout{width:100%!important}#layout.layout_1,#layout.layout_2{width:95%!important}.row{width:90% !important;padding:0}blockquote{padding:20px}footer .elms_center,footer .have_center .elms_left, footer .have_center .elms_center, footer .have_center .elms_right{float:none;display:table;text-align:center;margin:0 auto;flex:unset}}';

		// 768px.
		$dynamic .= '@media screen and (max-width:' . Codevz_Plus::option( 'tablet_breakpoint', '768px' ) . '){' . $dynamic_tablet . '}';

		// 480px.
		$dynamic .= '@media screen and (max-width:' . Codevz_Plus::option( 'mobile_breakpoint', '480px' ) . '){' . $dynamic_mobile . '}';

		// Fixed Border for Body
		if ( ! empty( $opt['_css_body'] ) && Codevz_Plus::contains( $opt['_css_body'], 'border-width' ) && Codevz_Plus::contains( $opt['_css_body'], 'border-color' ) ) {
			$out .= '.cz_fixed_top_border, .cz_fixed_bottom_border {border-top: ' . Codevz_Plus::get_string_between( $opt['_css_body'], 'border-width:', ';' ) . ' solid ' . Codevz_Plus::get_string_between( $opt['_css_body'], 'border-color:', ';' ) . '}';
		}

		// Site Colors
		if ( ! empty( $opt['site_color'] ) ) {
			$site_color = $opt['site_color'];

			$woo_bg = function_exists( 'is_woocommerce' ) ? ',.woocommerce input.button.alt.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,.woocommerce .woocommerce-error .button,.woocommerce .woocommerce-info .button, .woocommerce .woocommerce-message .button, .woocommerce-page .woocommerce-error .button, .woocommerce-page .woocommerce-info .button, .woocommerce-page .woocommerce-message .button,#add_payment_method table.cart input, .woocommerce-cart table.cart input:not(.input-text), .woocommerce-checkout table.cart input,.woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled],#add_payment_method table.cart input, #add_payment_method .wc-proceed-to-checkout a.checkout-button, .woocommerce-cart .wc-proceed-to-checkout a.checkout-button, .woocommerce-checkout .wc-proceed-to-checkout a.checkout-button,.woocommerce #payment #place_order, .woocommerce-page #payment #place_order,.woocommerce input.button.alt,.woocommerce #respond input#submit.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce-MyAccount-navigation a:hover, .woocommerce-MyAccount-navigation .is-active a,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current, .widget_product_search #searchsubmit,.woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce div.product form.cart .button, .xtra-product-icons,.woocommerce button.button.alt' : '';

			$out .= "\n\n/* Theme color */" . 'a:hover, .sf-menu > .cz.current_menu > a, .sf-menu > .cz .cz.current_menu > a,.sf-menu > .current-menu-parent > a,.comment-text .star-rating span,.xtra-404 span {color: ' . $site_color . '} 
form button, .button, #edd-purchase-button, .edd-submit, .edd-submit.button.blue, .edd-submit.button.blue:hover, .edd-submit.button.blue:focus, [type=submit].edd-submit, .sf-menu > .cz > a:before,.sf-menu > .cz > a:before,
.post-password-form input[type="submit"], .wpcf7-submit, .submit_user, 
#commentform #submit, .commentlist li.bypostauthor > .comment-body:after,.commentlist li.comment-author-admin > .comment-body:after, 
 .pagination .current, .pagination > b, .pagination a:hover, .page-numbers .current, .page-numbers a:hover, .pagination .next:hover, 
.pagination .prev:hover, input[type=submit], .sticky:before, .commentlist li.comment-author-admin .fn,
input[type=submit],input[type=button],.cz_header_button,.cz_default_portfolio a,
.cz_readmore, .more-link, a.cz_btn, div.cz_btn ' . $woo_bg . ' {background-color: ' . $site_color . '}
.cs_load_more_doing, div.wpcf7 .wpcf7-form .ajax-loader {border-right-color: ' . $site_color . '}
input:focus,textarea:focus,select:focus {border-color: ' . $site_color . ' !important}
::selection {background-color: ' . $site_color . ';color: #fff}
::-moz-selection {background-color: ' . $site_color . ';color: #fff}';
		} // Primary Color

		$out .= empty( $opt['lazyload_alter'] ) ? '' : '[data-src]{background-image:url(' . $opt['lazyload_alter'] . ')}';
		$out .= empty( $opt['lazyload_size'] ) ? '' : '[data-src]{background-size:' . $opt['lazyload_size'] . '}';

		// Custom CSS
		$out .= empty( $opt['css'] ) ? '' : "\n\n/* Custom */" . $opt['css'];

		// Enqueue Google Fonts
		if ( ! isset( $opt['_css_body_typo'] ) || ! Codevz_Plus::contains( $opt['_css_body_typo'], 'font-family' ) ) {
			$fonts[]['font'] = Codevz_Plus::$is_rtl ? '' : 'font-family:Open Sans;';
		}

		$fonts = wp_parse_args( (array) Codevz_Plus::option( 'wp_editor_fonts' ), $fonts );
		$final_fonts = array();
		foreach ( $fonts as $font ) {
			if ( isset( $font['font'] ) ) {
				$final_fonts[] = $font['font'];
				Codevz_Plus::load_font( $font['font'] );
			}
		}

		// Generated fonts
		update_option( 'codevz_fonts_out', $final_fonts );

		// Output
		return $out . $dynamic;
	}

	/**
	 *
	 * Get RGB numbers of HEX color
	 * 
	 * @var Hex color code
	 * @return string
	 *
	 */
	public static function hex2rgb( $c = '', $s = 0 ) {
		if ( empty( $c[0] ) ) {
			return '';
		}
		
		$c = substr( $c, 1 );
		if ( strlen( $c ) == 6 ) {
			list( $r, $g, $b ) = array( $c[0] . $c[1], $c[2] . $c[3], $c[4] . $c[5] );
		} elseif ( strlen( $c ) == 3 ) {
			list( $r, $g, $b ) = array( $c[0] . $c[0], $c[1] . $c[1], $c[2] . $c[2] );
		} else {
			return false;
		}
		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );

		return implode( $s ? ', ' : ',', array( $r, $g, $b ) );
	}

	/**
	 * Update database, options for site colors changes
	 * 
	 * @var Old string and New string
	 */
	public static function updateDatabase( $o = '', $n = '', $custom_content ='' ) {

		if ( $o ) {

			$old_rgb = self::hex2rgb( $o );
			$new_rgb = self::hex2rgb( $n );
			$old_rgb_s = self::hex2rgb( $o, 1 );
			$new_rgb_s = self::hex2rgb( $n, 1 );

			if ( $custom_content ) {

				return str_replace( array( $o, $old_rgb, $old_rgb_s ), array( $n, $new_rgb, $new_rgb_s ), $custom_content );

			}

			global $wpdb;

			// Posts and meta box.
			$wpdb->query( "UPDATE " . $wpdb->prefix . "posts SET post_content = replace(replace(replace(post_content, '" . $old_rgb_s . "','" . $new_rgb_s . "' ), '" . $old_rgb . "','" . $new_rgb . "' ), '" . $o . "','" . $n . "')" );
			$wpdb->query( "UPDATE " . $wpdb->prefix . "postmeta SET meta_value = replace(replace(meta_value, '" . strtoupper( $o ) . "','" . strtoupper( $n ) . "' ), '" . $o . "','" . $n . "' )" );
			$wpdb->query( "UPDATE " . $wpdb->prefix . "postmeta SET meta_value = replace(replace(meta_value, '" . $old_rgb_s . "','" . $new_rgb_s . "' ), '" . $old_rgb . "','" . $new_rgb . "' ) WHERE meta_key = '_elementor_data' AND meta_value LIKE '%rgba(%'" );

			// Widgets.
			$wpdb->query( "UPDATE " . $wpdb->prefix . "options SET option_value = replace(option_value, '" . $o . "','" . $n . "' ) WHERE option_name LIKE ('widget_%')" );

			// RevSlider.
			$wpdb->query( "UPDATE " . $wpdb->prefix . "revslider_slides SET layers = replace(replace(replace(layers, '" . $old_rgb_s . "','" . $new_rgb_s . "' ), '" . $old_rgb . "','" . $new_rgb . "' ), '" . $o . "','" . $n . "')" );
			$wpdb->query( "UPDATE " . $wpdb->prefix . "revslider_sliders SET params = replace(replace(replace(params, '" . $old_rgb_s . "','" . $new_rgb_s . "' ), '" . $old_rgb . "','" . $new_rgb . "' ), '" . $o . "','" . $n . "')" );

			// Theme options.
			$all = json_encode( Codevz_Plus::option() );
			$all = str_replace( array( $o, $old_rgb, $old_rgb_s ), array( $n, $new_rgb, $new_rgb_s ), $all );
			update_option( 'codevz_theme_options', json_decode( $all, true ) );

			// Elementor.
			if ( did_action( 'elementor/loaded' ) ) {

				\Elementor\Plugin::$instance->files_manager->clear_cache();

			}

		}

	}

	/**
	 *  Action after customizer saved
	 */
	public static function customize_save_after( $manage, $old = '' ) {

		$custom_stylekits_arr = json_encode( Codevz_Plus::option( 'custom_stylekits', [] ) );

		if ( $custom_stylekits_arr !== get_option( 'xtra_custom_stylekits' ) ) {

			update_option( 'xtra_cache_selectors', false );
			update_option( 'xtra_custom_stylekits', $custom_stylekits_arr );

		}

		// Update new post types
		$new_cpt = Codevz_Plus::option( 'add_post_type' );
		if ( is_array( $new_cpt ) && json_encode( $new_cpt ) !== json_encode( get_option( 'codevz_post_types_org' ) ) ) {
			$post_types = array();
			foreach ( $new_cpt as $cpt ) {
				if ( isset( $cpt['name'] ) ) {
					$post_types[] = strtolower( $cpt['name'] );
				}
			}
			update_option( 'codevz_css_selectors', '' );
			update_option( 'codevz_post_types', $post_types );
			update_option( 'codevz_post_types_org', $new_cpt );
		} else if ( empty( $new_cpt ) ) {
			delete_option( 'codevz_post_types' );
		}

		// Update Google Fonts for WP editor
		$fonts = Codevz_Plus::option( 'wp_editor_fonts' );
		if ( json_encode( $fonts ) !== json_encode( get_option( 'codevz_wp_editor_google_fonts_org' ) ) ) {
			$wp_editor_fonts = '';
			$fonts = wp_parse_args( $fonts, array(
				array( 'font' => 'inherit' ),
				array( 'font' => 'Arial' ),
				array( 'font' => 'Arial Black' ),
				array( 'font' => 'Comic Sans MS' ),
				array( 'font' => 'Impact' ),
				array( 'font' => 'Lucida Sans Unicode' ),
				array( 'font' => 'Tahoma' ),
				array( 'font' => 'Trebuchet MS' ),
				array( 'font' => 'Verdana' ),
				array( 'font' => 'Courier New' ),
				array( 'font' => 'Lucida Console' ),
				array( 'font' => 'Georgia, serif' ),
				array( 'font' => 'Palatino Linotype' ),
				array( 'font' => 'Times New Roman' )
			));

			// Custom fonts
			$custom_fonts = Codevz_Plus::option( 'custom_fonts' );
			if ( ! empty( $custom_fonts ) ) {
				$fonts = wp_parse_args( $custom_fonts, $fonts );
			}

			foreach ( $fonts as $font ) {
				if ( ! empty( $font['font'] ) ) {
					$font = $font['font'];
					if ( Codevz_Plus::contains( $font, ':' ) ) {
						$value = explode( ':', $font );
						$font = empty( $value[0] ) ? $font : $value[0];
						$wp_editor_fonts .= $font . '=' . $font . ';';
					} else {
						$title = ( $font === 'inherit' ) ? esc_html__( 'Inherit', 'codevz' ) : $font;
						$wp_editor_fonts .= $title . '=' . $font . ';';
					}
				}
			}
			update_option( 'codevz_wp_editor_google_fonts', $wp_editor_fonts );
			update_option( 'codevz_wp_editor_google_fonts_org', $fonts );
		}

		// Update primary theme color
		$primary = Codevz_Plus::option( 'site_color' );
		$primary = str_replace( '#000000', '#000001', $primary );
		$primary = str_replace( '#ffffff', '#fffffe', $primary );
		$primary = str_replace( '#222222', '#222223', $primary );
		if ( $primary && $primary !== get_option( 'codevz_primary_color' ) ) {
			self::updateDatabase( get_option( 'codevz_primary_color', '#0045a0' ), $primary );
		}
		update_option( 'codevz_primary_color', $primary );

		// Update secondary theme color
		$secondary = Codevz_Plus::option( 'site_color_sec' );
		$secondary = str_replace( '#000000', '#000001', $secondary );
		$secondary = str_replace( '#ffffff', '#fffffe', $secondary );
		$secondary = str_replace( '#222222', '#222223', $secondary );
		if ( $secondary && $secondary !== get_option( 'codevz_secondary_color' ) ) {
			self::updateDatabase( get_option( 'codevz_secondary_color' ), $secondary );
		}
		update_option( 'codevz_secondary_color', $secondary );

		// Fix and new generated CSS
		$options = get_option( 'codevz_theme_options' );

		$options['css_out'] = self::css_out();
		$options['site_color'] = $primary;
		$options['site_color_sec'] = $secondary;

		// Fix fonts
		$options['fonts_out'] = get_option( 'codevz_fonts_out' );

		// Fix custom sidebars
		$options['custom_sidebars'] = ( isset( $old['custom_sidebars'] ) && is_array( $old['custom_sidebars'] ) ) ? $old['custom_sidebars'] : Codevz_Plus::option( 'custom_sidebars', [] );

		// Create wishlist page.
		if ( ! get_option( 'xtra_woo_create_wishlist' ) && ! empty( $options['woo_wishlist'] ) ) {

			if ( ! post_exists( 'Wishlist' ) ) {

				$options['woo_wishlist'] = wp_insert_post(
					[
						'post_title'    => 'Wishlist',
						'post_content'  => '[cz_wishlist]',
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_type' 	=> 'page'
					]
				);
				
			}

			update_option( 'xtra_woo_create_wishlist', 1 );
		}

		// Reset white label.
		update_option( 'xtra_white_label', false );

		// Update new options
		update_option( 'codevz_theme_options', $options );
	}

	/**
	 * List of custom sidebars
	 * 
	 * @return array list of available sidebars
	 */
	public static function custom_sidebars() {
		$out = [
			'primary' 	=> esc_html__( 'Primary', 'codevz' ),
			'secondary' => esc_html__( 'Secondary', 'codevz' ),
		];

		// Portfolio 
		$cpt = get_post_type_object( 'portfolio' );
		if ( ! empty( $cpt->labels->singular_name ) ) {
			$out[ 'portfolio-primary' ] = $cpt->labels->singular_name . ' ' . esc_html__( 'Primary', 'codevz' );
			$out[ 'portfolio-secondary' ] = $cpt->labels->singular_name . ' ' . esc_html__( 'Secondary', 'codevz' );
		}

		// Products 
		$cpt = get_post_type_object( 'product' );
		if ( ! empty( $cpt->labels->singular_name ) ) {
			$out[ 'product-primary' ] = $cpt->labels->singular_name . ' ' . esc_html__( 'Primary', 'codevz' );
			$out[ 'product-secondary' ] = $cpt->labels->singular_name . ' ' . esc_html__( 'Secondary', 'codevz' );
		}

		// Custom sidebars.
		$all = Codevz_Plus::option( 'custom_sidebars', [] );
		foreach( $all as $sidebar ) {
			if ( $sidebar ) {
				$out[ $sidebar ] = ucwords( str_replace( [ 'cz-custom-', '-' ], ' ', $sidebar ) );
			}
		}

		return $out;
	}

	/**
	 * Meta box for pages, posts, port types
	 * @return array
	 */
	public static function metabox() {

		// Add one-page menu option for pages only
		add_filter( 'xtra_metabox', function( $a ) {

			if ( self::get_post_type_admin() === 'page' ) {

				$a[0]['fields'][] = array(
					'id' 		=> 'one_page',
					'type' 		=> 'select',
					'title' 	=> esc_html__( 'Custom Menu', 'codevz' ),
					'desc' 		=> esc_html__( 'To add or edit menus go to Appearance > Menus', 'codevz' ),
					'options' 	=> array(
						'' 			=> esc_html__( '~ Default ~', 'codevz' ), 
						'primary' 	=> esc_html__( 'Primary', 'codevz' ), 
						'secondary' => esc_html__( 'Secondary', 'codevz' ), 
						'1'  		=> esc_html__( 'One Page', 'codevz' ), 
						'footer'  	=> esc_html__( 'Footer', 'codevz' ),
						'mobile'  	=> esc_html__( 'Mobile', 'codevz' ),
						'custom-1' 	=> esc_html__( 'Custom', 'codevz' ) . ' 1', 
						'custom-2' 	=> esc_html__( 'Custom', 'codevz' ) . ' 2', 
						'custom-3' 	=> esc_html__( 'Custom', 'codevz' ) . ' 3',
						'custom-4' 	=> esc_html__( 'Custom', 'codevz' ) . ' 4',
						'custom-5' 	=> esc_html__( 'Custom', 'codevz' ) . ' 5'
					)
				);

			} else {
				$a[0]['fields'][] = array(
					'id' 		=> 'hide_featured_image',
					'type' 		=> 'select',
					'title' 	=> esc_html__( 'Featured Image', 'codevz' ),
					'options' 	=> array(
						''  		=> esc_html__( '~ Default ~', 'codevz' ),
						'1'  		=> esc_html__( 'Hide', 'codevz' ),
						'2'  		=> esc_html__( 'Show', 'codevz' ),
					)
				);
			}

			return $a;
		}, 999 );

		// SEO options
		$seo = Codevz_Plus::option( 'seo_meta_tags' ) ? array(
			array(
				'id' 		=> 'seo_desc',
				'type' 		=> 'textarea',
				'title' 	=> esc_html__( 'Short Description', 'codevz' ),
			),
			array(
				'id' 		=> 'seo_keywords',
				'type' 		=> 'textarea',
				'title' 	=> esc_html__( 'Keywords', 'codevz' ),
				'desc'		=> esc_html__( 'Separate with comma', 'codevz' ),
			),
		) : array(
				array(
					'type'    => 'content',
					'content' => esc_html__( 'Please first enable SEO options from Theme Options > General > SEO', 'codevz' )
				),
		);
		$seo = array(
			  'name'   => 'page_seo_settings',
			  'title'  => esc_html__( 'SEO Settings', 'codevz' ),
			  'icon'   => 'fa fa-search',
			  'fields' => $seo
		);

		// Post formats
		$post_formats = null;
		$pta = self::get_post_type_admin();
		if ( $pta === 'post' || post_type_supports( $pta, 'post-formats' ) ) {
			$post_formats = array(
				'name'   => 'post_format_settings',
				'title'  => esc_html__( 'Post Format', 'codevz' ),
				'icon'   => 'fa fa-cube',
				'fields' => array(
					array(
						'id' 		=> 'post_format',
						'type' 		=> 'codevz_image_select',
						'title' 	=> esc_html__( 'Post Format', 'codevz' ),
						'options' 		=> [
							'0' 				=> [ esc_html__( 'Standard', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/post-standard.png' ],
							'gallery'			=> [ esc_html__( 'Gallery', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/post-gallery.png' ],
							'video'				=> [ esc_html__( 'Video', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/post-video.png' ],
							'audio'				=> [ esc_html__( 'Audio', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/post-audio.png' ],
							'quote'				=> [ esc_html__( 'Quote', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/post-quote.png' ],
						],
						'attributes' => array(
							'class' => 'post-formats-select'
						)
					),

					// Gallery format
					array(
						'id' 			=> 'gallery',
						'type' 			=> 'gallery',
						'title' 		=> esc_html__( 'Images', 'codevz' ),
						'dependency' 	=> array( 'post_format', '==', 'gallery' ),
					),
					array(
						'id' 			=> 'gallery_layout',
						'type' 			=> 'codevz_image_select',
						'title' 		=> esc_html__( 'Layout', 'codevz' ),
						'options' 		=> [
							'cz_grid_c1 cz_grid_l1'		=> [ '1 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_2.png' ],
							'cz_grid_c2 cz_grid_l2'		=> [ '2 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_3.png' ],
							'cz_grid_c2'				=> [ '2 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_4.png' ],
							'cz_grid_c3'				=> [ '3 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_5.png' ],
							'cz_grid_c4'				=> [ '4 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_6.png' ],
							'cz_grid_c5'				=> [ '5 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_7.png' ],
							'cz_grid_c6'				=> [ '6 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_8.png' ],
							'cz_grid_c7'				=> [ '7 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_9.png' ],
							'cz_grid_c8'				=> [ '8 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_10.png' ],
							'cz_hr_grid cz_grid_c2'		=> [ '2 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_11.png' ],
							'cz_hr_grid cz_grid_c3'		=> [ '3 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_12.png' ],
							'cz_hr_grid cz_grid_c4'		=> [ '4 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_13.png' ],
							'cz_hr_grid cz_grid_c5'		=> [ '5 ' . esc_html__( 'Columns', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_14.png' ],
							'cz_masonry cz_grid_c2'		=> [ '2 ' . esc_html__( 'Columns', 'codevz' ) . ' ' . esc_html__( 'Masonry', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_15.png' ],
							'cz_masonry cz_grid_c3'		=> [ '3 ' . esc_html__( 'Columns', 'codevz' ) . ' ' . esc_html__( 'Masonry', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_16.png' ],
							'cz_masonry cz_grid_c4'		=> [ '4 ' . esc_html__( 'Columns', 'codevz' ) . ' ' . esc_html__( 'Masonry', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_17.png' ],
							'cz_masonry cz_grid_c4 cz_grid_1big' => [ '3 ' . esc_html__( 'Columns', 'codevz' ) . ' ' . esc_html__( 'Masonry', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_18.png' ],
							'cz_masonry cz_grid_c5'		=> [ '5 ' . esc_html__( 'Columns', 'codevz' ) . ' ' . esc_html__( 'Masonry', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_19.png' ],
							'cz_metro_1 cz_grid_c4'		=> [ esc_html__( 'Metro', 'codevz' ) . ' 1' 	, Codevz_Plus::$url . 'assets/img/gallery_20.png' ],
							'cz_metro_2 cz_grid_c4'		=> [ esc_html__( 'Metro', 'codevz' ) . ' 2' 	, Codevz_Plus::$url . 'assets/img/gallery_21.png' ],
							'cz_metro_3 cz_grid_c4'		=> [ esc_html__( 'Metro', 'codevz' ) . ' 3' 	, Codevz_Plus::$url . 'assets/img/gallery_22.png' ],
							'cz_metro_4 cz_grid_c4'		=> [ esc_html__( 'Metro', 'codevz' ) . ' 4' 	, Codevz_Plus::$url . 'assets/img/gallery_23.png' ],
							'cz_metro_5 cz_grid_c3'		=> [ esc_html__( 'Metro', 'codevz' ) . ' 5' 	, Codevz_Plus::$url . 'assets/img/gallery_24.png' ],
							'cz_metro_6 cz_grid_c3'		=> [ esc_html__( 'Metro', 'codevz' ) . ' 6' 	, Codevz_Plus::$url . 'assets/img/gallery_25.png' ],
							'cz_metro_7 cz_grid_c7'		=> [ esc_html__( 'Metro', 'codevz' ) . ' 7' 	, Codevz_Plus::$url . 'assets/img/gallery_26.png' ],
							'cz_metro_8 cz_grid_c4'		=> [ esc_html__( 'Metro', 'codevz' ) . ' 8' 	, Codevz_Plus::$url . 'assets/img/gallery_27.png' ],
							'cz_metro_9 cz_grid_c6'		=> [ esc_html__( 'Metro', 'codevz' ) . ' 9' 	, Codevz_Plus::$url . 'assets/img/gallery_28.png' ],
							'cz_metro_10 cz_grid_c6'	=> [ esc_html__( 'Metro', 'codevz' ) . ' 10' 	, Codevz_Plus::$url . 'assets/img/gallery_29.png' ],
							'cz_grid_carousel'			=> [ esc_html__( 'Carousel Slider', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/gallery_30.png' ],
						],
						'default' 		=> 'cz_grid_c3',
						'attributes' 	=> [ 'data-depend-id' => 'gallery_layout' ],
						'dependency' 	=> array( 'post_format', '==', 'gallery' ),
					),
					array(
						'id'        	=> 'gallery_gap',
						'type'      	=> 'slider',
						'title'     	=> esc_html__( 'Gap', 'codevz' ),
						'options' 		=> array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 100 ),
						'default' 		=> '20px',
						'dependency' 	=> array( 'post_format', '==', 'gallery' ),
					),
					array(
						'id'        	=> 'gallery_slides_to_show',
						'type'      	=> 'slider',
						'title'     	=> esc_html__( 'Slides', 'codevz' ),
						'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 0, 'max' => 100 ),
						'default' 		=> '1',
						'dependency' 	=> array( 'post_format|gallery_layout', '==|==', 'gallery|cz_grid_carousel' ),
					),

					// Video format
					array(
						'id' 		=> 'video_type',
						'type' 		=> 'select',
						'title' 	=> esc_html__( 'Type', 'codevz' ),
						'options' 	=> array(
							'url'  		=> esc_html__( 'Youtube or Vimeo', 'codevz' ),
							'selfhost'  => esc_html__( 'Self hosted', 'codevz' ),
							'embed'  	=> esc_html__( 'Embed', 'codevz' ),
						),
						'dependency' 	=> array( 'post_format', '==', 'video' ),
					),
					array(
						'id' 		=> 'video_url',
						'type' 		=> 'text',
						'title' 	=> esc_html__( 'Video URL', 'codevz' ),
						'dependency' 	=> array( 'post_format|video_type', '==|==', 'video|url' ),
					),
					array(
						'id'          => 'video_file',
						'type'        => 'upload',
						'title'       => esc_html__( 'MP4', 'codevz' ),
						'settings'   => array(
							'upload_type'  => 'video/mp4',
							'insert_title' => esc_html__( 'Insert', 'codevz' ),
						),
						'dependency' 	=> array( 'post_format|video_type', '==|==', 'video|selfhost' ),
					),
					array(
						'id' 		=> 'video_embed',
						'type' 		=> 'textarea',
						'title' 	=> esc_html__( 'Embed Code', 'codevz' ),
						'dependency' 	=> array( 'post_format|video_type', '==|==', 'video|embed' ),
					),

					// Audio format
					array(
						'id'          => 'audio_file',
						'type'        => 'upload',
						'title'       => esc_html__('MP3 or Stream URL', 'codevz'),
						'settings'   => array(
							'upload_type'  => 'audio/mpeg',
							'insert_title' => esc_html__( 'Insert', 'codevz' ),
						),
						'dependency' 	=> array( 'post_format', '==', 'audio' ),
					),

					// Quote format
					array(
						'id' 		=> 'quote',
						'type' 		=> 'textarea',
						'title' 	=> esc_html__( 'Quote', 'codevz' ),
						'dependency' 	=> array( 'post_format', '==', 'quote' ),
					),
					array(
						'id' 		=> 'cite',
						'type' 		=> 'text',
						'title' 	=> esc_html__( 'Cite', 'codevz' ),
						'dependency' 	=> array( 'post_format', '==', 'quote' ),
					),
				)
			);
		}

		$pTypes = array_flip( wp_parse_args( get_post_types(), array( 'post', 'page', 'product', 'download', 'forum', 'topic', 'reply' ) ) );

		// Return meta box
		return array(array(
			'id'           => 'codevz_page_meta',
			'title'        => esc_html__( 'Page Settings', 'codevz' ),
			'post_type'    => self::post_types( $pTypes ),
			'context'      => 'normal',
			'priority'     => 'default',
			'show_restore' => true,
			'sections'     => apply_filters( 'xtra_metabox', array(

				array(
				  'name'   => 'page_general_settings',
				  'title'  => esc_html__( 'General Settings', 'codevz' ),
				  'icon'   => 'fa fa-cog',
				  'fields' => array(
					array(
						'id' 			=> 'layout',
						'type' 			=> 'codevz_image_select',
						'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
						'desc'  		=> esc_html__( 'The default sidebar position is set from Theme Options > General > Sidebar position', 'codevz' ),
						'options' 		=> [
							'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
							'none' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
							'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
							'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
							'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
							'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
							'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
							'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
							'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
							'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
							'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
							'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
							'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
							'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
						],
						'default' 		=> ( self::get_post_type_admin() === 'page' ) ? 'none' : '1',
						'attributes' 	=> [ 'data-depend-id' => 'layout' ]
					),
					array(
						'id'      			=> 'primary',
						'type'    			=> 'select',
						'title'   			=> esc_html__( 'Primary Sidebar', 'codevz' ),
						'desc'    			=> esc_html__( 'You can create custom sidebar from Appearance > Widgets then select it here.', 'codevz' ),
						'options' 			=> self::custom_sidebars(),
						'default_option' 	=> esc_html__( '~ Default ~', 'codevz' ),
						'dependency' 		=> array( 'layout', 'any', 'right,right-s,left,left-s,both-side,both-side2,both-right,both-right2,both-left,both-left2' ),
					),
					array(
						'id'      			=> 'secondary',
						'type'    			=> 'select',
						'title'   			=> esc_html__( 'Secondary Sidebar', 'codevz' ),
						'desc'    			=> esc_html__( 'You can create custom sidebar from Appearance > Widgets then select it here.', 'codevz' ),
						'options' 			=> self::custom_sidebars(),
						'default_option' 	=> esc_html__( '~ Default ~', 'codevz' ),
						'dependency' 		=> array( 'layout', 'any', 'both-side,both-side2,both-right,both-right2,both-left,both-left2' ),
					),
					array(
						'id' 			=> 'page_content_margin',
						'type' 			=> 'codevz_image_select',
						'title' 		=> esc_html__( 'Page Content Gap', 'codevz' ),
						'desc'    		=> esc_html__( 'The gap between header, content and footer', 'codevz' ),
						'options' 		=> [
							'' 				=> [ esc_html__( '~ Default ~', 'codevz' ) 							, Codevz_Plus::$url . 'assets/img/content-gap-1.png' ],
							'mt0' 			=> [ esc_html__( 'No gap between header and content', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/content-gap-2.png' ],
							'mb0' 			=> [ esc_html__( 'No gap between content and footer', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/content-gap-3.png' ],
							'mt0 mb0' 		=> [ esc_html__( 'No gap between header, content and footer', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/content-gap-4.png' ],
						],
					),
		  array(
			'id'        	=> '_css_page_body_bg',
			'type'      	=> 'cz_sk',
			'title'     	=> esc_html__( 'Page Background', 'codevz' ),
			'button'    	=> esc_html__( 'Page Background', 'codevz' ),
			'settings'    	=> array( 'background' ),
			'selector'    	=> '',
			'desc'   	=> esc_html__( 'Color or Image', 'codevz' ),
		  ),
		  array('id' => '_css_page_body_bg_tablet','type' => 'cz_sk_hidden','selector' => ''),
		  array('id' => '_css_page_body_bg_mobile','type' => 'cz_sk_hidden','selector' => ''),

			array(
				'id'  		=> 'hide_header',
				'type'  	=> 'switcher',
				'title' 	=> esc_html__( 'Hide Header', 'codevz' ),
				'desc'   	=> esc_html__( 'Hide it only on this page', 'codevz' ),
			),
			array(
				'id'  		=> 'hide_footer',
				'type' 		=> 'switcher',
				'title' 	=> esc_html__( 'Hide Footer', 'codevz' ),
				'desc'   	=> esc_html__( 'Hide it only on this page', 'codevz' ),
			),

		)
	  ), // page_general_settings

	  array(
		'name'   => 'page_header',
		'title'  => esc_html__( 'Header Settings', 'codevz' ),
		'icon'   => 'fa fa-paint-brush',
		'fields' => array(
			array(
				'id' 			=> 'cover_than_header',
				'type' 			=> 'codevz_image_select',
				'title' 		=> esc_html__( 'Header Position', 'codevz' ),
				'desc'      	=> esc_html__( 'The default option is set from Theme Options > Header > Title & Breadcrumbs', 'codevz' ),
				'options' 		=> [
					'd' 					=> [ esc_html__( '~ Default ~', 'codevz' ) 						, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
					'header_top' 			=> [ esc_html__( 'Header before title', 'codevz' ) 						, Codevz_Plus::$url . 'assets/img/header-before-title.png' ],
					'header_after_cover' 	=> [ esc_html__( 'Header after title', 'codevz' ) 						, Codevz_Plus::$url . 'assets/img/header-after-title.png' ],
					'header_onthe_cover' 	=> [ esc_html__( 'Overlay only on desktop', 'codevz' ) 				, Codevz_Plus::$url . 'assets/img/header-overlay-desktop.png' ],
					'header_onthe_cover header_onthe_cover_dt' 		=> [ esc_html__( 'Overlay only on desktop & tablet', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/header-overlay-desktop-tablet.png' ],
					'header_onthe_cover header_onthe_cover_all' 	=> [ esc_html__( 'Overlay on all devices', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/header-overlay-all.png' ],
				],
				'default'   => 'd',
			),
		  array(
			'id' 			=> 'page_cover',
			'type' 			=> 'codevz_image_select',
			'title' 		=> esc_html__( 'Title Type', 'codevz' ),
			'options' 		=> [
				'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
				'none' 			=> [ esc_html__( '~ Disable ~', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/off.png' ],
				'title' 		=> [ esc_html__( 'Title & Breadcrumbs', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/header-before-title.png' ],
				'rev'			=> [ esc_html__( 'Revolution Slider', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/title-slider.png' ],
				'image' 		=> [ esc_html__( 'Custom Image', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/title-image.png' ],
				'custom' 		=> [ esc_html__( 'Custom Shortcode', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/title-custom-code.png' ],
				'page' 			=> [ esc_html__( 'Custom Page Content', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/title-custom-page.png' ],
			],
			'default' 		=> '1',
			'desc' 			=> esc_html__( 'If you want to learn more about how title section works, set this to default then go to Theme Options > Header > Title & Breadcrumbs and change settings.', 'codevz' ),
			'help' 			=> esc_html__( 'Title and breadcrumbs only can be set from Theme Options > Header > Title & Breadcrumbs', 'codevz' ),
		  ),
		  array(
			'id'    		=> 'page_cover_image',
			'type'    		=> 'image',
			'title'   		=> esc_html__( 'Image', 'codevz' ),
			'dependency' 	=> array( 'page_cover', '==', 'image' ),
		  ),
		  array(
			'id'    		=> 'page_cover_page',
			'type'    		=> 'select',
			'title'   		=> esc_html__( 'Content', 'codevz' ),
			'desc'   		=> esc_html__( 'You can create custom page from Dashboard > Pages and assign it here, This will show instead title section for this page.', 'codevz' ),
			'options'   	=> Codevz_Plus::$array_pages,
			'dependency' 	=> array( 'page_cover', '==', 'page' ),
		  ),
		  array(
			'id'    		=> 'page_cover_custom',
			'type'    		=> 'textarea',
			'title'   		=> esc_html__( 'Custom Shortcode', 'codevz' ),
			'desc' 			=> esc_html__( 'Shortcode or custom HTML codes allowed, This will show instead title section.', 'codevz' ),
			'dependency' 	=> array( 'page_cover', '==', 'custom' )
		  ),
		  array(
			'id'    		=> 'page_cover_rev',
			'type'    		=> 'select',
			'title'   		=> esc_html__( 'Select Slider', 'codevz' ),
			'desc' 			=> esc_html__( 'You can create slider from Dashboard > Revolution Slider then assign it here.', 'codevz' ),
			'options'   	=> self::revSlider(),
			'dependency' 	=> array( 'page_cover', '==', 'rev' ),
			'default_option' => esc_html__( '~ Select ~', 'codevz'),
		  ),
		  array(
			'id'    		=> 'page_show_br',
			'type'    		=> 'switcher',
			'title'   		=> esc_html__( 'Title & Breadcrumbs', 'codevz' ),
			'desc'   		=> esc_html__( 'Showing title and breadcrumbs section after above option', 'codevz' ),
			'dependency' 	=> array( 'page_cover', 'any', 'rev,image,custom,page' )
		  ),
			array(
				'id'        => 'page_title_color',
				'type'      => 'color_picker',
				'title'     => esc_html__( 'Title Color', 'codevz' ),
			), 
		  array(
			'id'        => '_css_page_title',
			'type'      => 'cz_sk',
			'title'     => esc_html__( 'Title Background', 'codevz' ),
			'button'    => esc_html__( 'Title Background', 'codevz' ),
			'settings'  => array( 'background', 'padding', 'border' ),
			'selector'  => ''
		  ),
		  array('id' => '_css_page_title_tablet','type' => 'cz_sk_hidden','selector' => ''),
		  array('id' => '_css_page_title_mobile','type' => 'cz_sk_hidden','selector' => ''),

		  array(
			'id'      	=> '_css_container_header_1',
			'type'      => 'cz_sk',
			'title'    	=> esc_html__( 'Header Top Bar', 'codevz' ),
			'button'    => esc_html__( 'Header Top Bar', 'codevz' ),
			'settings' 	=> array( 'background', 'padding', 'border' ),
			'selector' 	=> ''
		  ),
		  array('id' => '_css_container_header_1_tablet','type' => 'cz_sk_hidden','selector' => ''),
		  array('id' => '_css_container_header_1_mobile','type' => 'cz_sk_hidden','selector' => ''),

		  array(
			'id'      => '_css_container_header_2',
			'type'      => 'cz_sk',
			'title'    => esc_html__( 'Header', 'codevz' ),
			'button'    => esc_html__( 'Header', 'codevz' ),
			'settings'    => array( 'background', 'padding', 'border' ),
			'selector'    => ''
		  ),
		  array('id' => '_css_container_header_2_tablet','type' => 'cz_sk_hidden','selector' => ''),
		  array('id' => '_css_container_header_2_mobile','type' => 'cz_sk_hidden','selector' => ''),

		  array(
			'id'      => '_css_container_header_3',
			'type'      => 'cz_sk',
			'title'    => esc_html__( 'Header Bottom Bar', 'codevz' ),
			'button'    => esc_html__( 'Header Bottom Bar', 'codevz' ),
			'settings'    => array( 'background', 'padding', 'border' ),
			'selector'    => ''
		  ),
		  array('id' => '_css_container_header_3_tablet','type' => 'cz_sk_hidden','selector' => ''),
		  array('id' => '_css_container_header_3_mobile','type' => 'cz_sk_hidden','selector' => ''),

		  array(
			'id'        => '_css_header_container',
			'type'      => 'cz_sk',
			'title'     => esc_html__( 'Overall Header', 'codevz' ),
			'button'    => esc_html__( 'Overall Header', 'codevz' ),
			'settings'  => array( 'background', 'padding', 'border' ),
			'selector'  => ''
		  ),
		  array('id' => '_css_header_container_tablet','type' => 'cz_sk_hidden','selector' => ''),
		  array('id' => '_css_header_container_mobile','type' => 'cz_sk_hidden','selector' => ''),

		  array(
			'id'        => '_css_fixed_side_style',
			'type'      => 'cz_sk',
			'title'     => esc_html__( 'Fixed Side', 'codevz' ),
			'desc'      => esc_html__( 'You can enable Fixed Side from Theme Options > Header > Fixed Side', 'codevz' ),
			'button'    => esc_html__( 'Fixed Side', 'codevz' ),
			'settings'  => array( 'background', 'width', 'border' ),
			'selector'  => ''
		  ),
		  array('id' => '_css_fixed_side_style_tablet','type' => 'cz_sk_hidden','selector' => ''),
		  array('id' => '_css_fixed_side_style_mobile','type' => 'cz_sk_hidden','selector' => ''),

		)
	  ), // page_header_settings
				$seo,
				$post_formats
			))
		));
	}

	/**
	 *
	 * Breadcrumbs and title options
	 * 
	 * @var post type name, CSS selector
	 * @return array
	 *
	 */
	public static function title_options( $i = '', $c = '' ) {


		if ( $i ) {
			return array(
				array(
					'id' 	=> 'page_cover' . $i,
					'type' 	=> 'codevz_image_select',
					'title' => esc_html__( 'Title Type', 'codevz' ),
					'options' 		=> [
						( $i ? '1' : '' ) => [ esc_html__( '~ Default ~', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
						'none' 			=> [ esc_html__( '~ Disable ~', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/off.png' ],
						'title' 		=> [ esc_html__( 'Title & Breadcrumbs', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/header-before-title.png' ],
						'rev'			=> [ esc_html__( 'Revolution Slider', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/title-slider.png' ],
						'image' 		=> [ esc_html__( 'Custom Image', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/title-image.png' ],
						'custom' 		=> [ esc_html__( 'Custom Shortcode', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/title-custom-code.png' ],
						'page' 			=> [ esc_html__( 'Custom Page Content', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/title-custom-page.png' ],
					],
					'help'  	=> esc_html__( 'The default option for all pages', 'codevz' ),
					'default' 	=> $i ? '1' : 'none'
				),
				array(
					'id'    		=> 'page_cover_image' . $i,
					'type'    		=> 'image',
					'title'   		=> esc_html__( 'Image', 'codevz' ),
					'dependency' 	=> array( 'page_cover' . $i, '==', 'image' ),
				),
				array(
					'id'            => 'page_cover_page' . $i,
					'type'          => 'select',
					'title'         => esc_html__( 'Content', 'codevz' ),
					'help'   		=> esc_html__( 'You can create custom page from Dashboard > Pages and assign it here, This will show instead title section.', 'codevz' ),
					'options'       => Codevz_Plus::$array_pages,
					'dependency' 	=> array( 'page_cover' . $i, '==', 'page' )
				),
				array(
					'id' 		=> 'page_cover_custom' . $i,
					'type' 		=> 'textarea',
					'title' 	=> esc_html__( 'Custom Shortcode', 'codevz' ),
					'help' 		=> esc_html__( 'Shortcode or custom HTML allowed', 'codevz' ),
					'dependency' => array( 'page_cover' . $i, '==', 'custom' )
				),
				array(
					'id' 			=> 'page_cover_rev' . $i,
					'type' 			=> 'select',
					'title' 		=> esc_html__( 'Select Slider', 'codevz' ),
					'help' 			=> esc_html__( 'You can create slider from Dashboard > Revolution Slider then assign it here.', 'codevz' ),
					'options' 		=> self::revSlider(),
					'dependency' 	=> array( 'page_cover' . $i, '==', 'rev' ),
					'default_option' => esc_html__( '~ Select ~', 'codevz'),
				),
				array(
					'id' 			=> '_css_page_title' . $i,
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Container Background', 'codevz' ),
					'button' 		=> esc_html__( 'Container Background', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'selector' 		=> $c . '.page_title,' . $c . '.header_onthe_cover .page_title',
					'dependency' 	=> array( 'page_cover' . $i, '==', 'title' )
				),
				array(
					'id' 			=> '_css_page_title' . $i . '_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title,' . $c . '.header_onthe_cover .page_title'
				),
				array(
					'id' 			=> '_css_page_title' . $i . '_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title,' . $c . '.header_onthe_cover .page_title'
				),
			);
		} else {
			return array(
				array(
					'id' 			=> 'cover_than_header',
					'type' 			=> 'codevz_image_select',
					'title' 		=> esc_html__( 'Header Position', 'codevz' ),
					'options' 		=> [
						'' 						=> [ esc_html__( '~ Default ~', 'codevz' ) 						, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
						'header_top' 			=> [ esc_html__( 'Header before title', 'codevz' ) 						, Codevz_Plus::$url . 'assets/img/header-before-title.png' ],
						'header_after_cover' 	=> [ esc_html__( 'Header after title', 'codevz' ) 						, Codevz_Plus::$url . 'assets/img/header-after-title.png' ],
						'header_onthe_cover' 	=> [ esc_html__( 'Overlay on desktop', 'codevz' ) 				, Codevz_Plus::$url . 'assets/img/header-overlay-desktop.png' ],
						'header_onthe_cover header_onthe_cover_dt' 		=> [ esc_html__( 'Overlay on desktop & tablet', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/header-overlay-desktop-tablet.png' ],
						'header_onthe_cover header_onthe_cover_all' 	=> [ esc_html__( 'Overlay on all devices', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/header-overlay-all.png' ],
					],
				),
				array(
					'id' 	=> 'page_cover',
					'type' 	=> 'codevz_image_select',
					'title' => esc_html__( 'Title Type', 'codevz' ),
					'options' 		=> [
						'' 				=> [ esc_html__( '~ Default ~', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
						'none' 			=> [ esc_html__( '~ Disable ~', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/off.png' ],
						'title' 		=> [ esc_html__( 'Title & Breadcrumbs', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/header-before-title.png' ],
						'rev'			=> [ esc_html__( 'Revolution Slider', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/title-slider.png' ],
						'image' 		=> [ esc_html__( 'Custom Image', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/title-image.png' ],
						'custom' 		=> [ esc_html__( 'Custom Shortcode', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/title-custom-code.png' ],
						'page' 			=> [ esc_html__( 'Custom Page Content', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/title-custom-page.png' ],
					],
					'help'  	=> esc_html__( 'The default option for all pages', 'codevz' ),
					'default' 	=> ''
				),
				array(
					'id'    		=> 'page_cover_image',
					'type'    		=> 'image',
					'title'   		=> esc_html__( 'Image', 'codevz' ),
					'dependency' 	=> array( 'page_cover', '==', 'image' ),
				),
				array(
					'id'            => 'page_cover_page',
					'type'          => 'select',
					'title'         => esc_html__( 'Content', 'codevz' ),
					'help'   		=> esc_html__( 'You can create custom page from Dashboard > Pages and assign it here, This will show instead title section.', 'codevz' ),
					'options'       => Codevz_Plus::$array_pages,
					'dependency' 	=> array( 'page_cover', '==', 'page' )
				),
				array(
					'id' 		=> 'page_cover_custom',
					'type' 		=> 'textarea',
					'title' 	=> esc_html__( 'Custom Shortcode', 'codevz' ),
					'help' 		=> esc_html__( 'Shortcode or custom HTML allowed', 'codevz' ),
					'dependency' => array( 'page_cover', '==', 'custom' )
				),
				array(
					'id' 			=> 'page_cover_rev',
					'type' 			=> 'select',
					'title' 		=> esc_html__( 'Select Slider', 'codevz' ),
					'help' 			=> esc_html__( 'You can create slider from Dashboard > Revolution Slider then assign it here.', 'codevz' ),
					'options' 		=> self::revSlider(),
					'dependency' 	=> array( 'page_cover', '==', 'rev' ),
					'default_option' => esc_html__( '~ Select ~', 'codevz'),
				),

				array(
					'id' 			=> 'page_title',
					'type' 			=> 'select',
					'title' 		=> esc_html__( 'Position', 'codevz' ),
					'options' 		=> array(
						'1' 	=> esc_html__( '~ Default ~', 'codevz' ),
						'3' 	=> esc_html__( 'Page title', 'codevz' ),
						'2' 	=> esc_html__( 'Page title above content', 'codevz' ),
						'4' 	=> esc_html__( 'Title and Breadcrumbs', 'codevz' ),
						'5' 	=> esc_html__( 'Breadcrumbs and Title', 'codevz' ),
						'6' 	=> esc_html__( 'Title left and Breadcrumbs right', 'codevz' ),
						'7' 	=> esc_html__( 'Breadcrumbs', 'codevz' ),
						'9' 	=> esc_html__( 'Breadcrumbs right', 'codevz' ),
						'8' 	=> esc_html__( 'Breadcrumbs and Title above content', 'codevz' ),
					),
					'dependency' 	=> array( 'page_cover', '==', 'title' ),
					'default' 		=> '1'
				),
				array(
					'id'      		=> 'page_title_hide_breadcrumbs',
					'type'      	=> 'switcher',
					'title'   		=> esc_html__( 'Hide Breadcrumbs', 'codevz' ),
					'help'   		=> esc_html__( 'Hide breadcrumbs if it is less than 3 level', 'codevz' ),
					'dependency'  	=> array( 'page_cover|page_title', 'any|any', 'title|4,5,6,7,8,9' )
				),
				array(
					'id'      		=> 'page_title_center',
					'type'      	=> 'switcher',
					'title'   		=> esc_html__( 'Center Mode', 'codevz' ),
					'dependency'  	=> array( 'page_cover|page_title', 'any|any', 'title|3,4,5,7,8,9' )
				),
				array(
					'id'    		=> 'breadcrumbs_separator',
					'type'  		=> 'icon',
					'title' 		=> esc_html__( 'Delimiter', 'codevz' ),
					'dependency' 	=> array( 'page_cover|page_title', '==|any', 'title|4,5,6,7,8,9' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ]
				),
				array(
					'id' 			=> '_css_page_title',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Container', 'codevz' ),
					'button' 		=> esc_html__( 'Container', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'selector' 		=> $c . '.page_title,' . $c . '.header_onthe_cover .page_title',
					'dependency' 	=> array( 'page_cover|page_title', '==|any', 'title|2,3,4,5,6,7,8,9' )
				),
				array(
					'id' 			=> '_css_page_title_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title,' . $c . '.header_onthe_cover .page_title'
				),
				array(
					'id' 			=> '_css_page_title_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title,' . $c . '.header_onthe_cover .page_title'
				),
				array(
					'id' 			=> '_css_page_title_inner_row',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Inner Row', 'codevz' ),
					'button' 		=> esc_html__( 'Inner Row', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'width', 'padding' ),
					'selector' 		=> $c . '.page_title .row',
					'dependency' 	=> array( 'page_cover|page_title', '==|any', 'title|3,4,5,6,7,8,9' )
				),
				array(
					'id' 			=> '_css_page_title_inner_row_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title .row',
				),
				array(
					'id' 			=> '_css_page_title_inner_row_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title .row',
				),
				array(
					'id' 			=> '_css_page_title_color',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Title', 'codevz' ),
					'button' 		=> esc_html__( 'Title', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-size', 'padding' ),
					'selector' 		=> $c . '.page_title .section_title',
					'dependency' 	=> array( 'page_cover|page_title', '==|any', 'title|3,4,5,6' )
				),
				array(
					'id' 			=> '_css_page_title_color_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title .section_title',
				),
				array(
					'id' 			=> '_css_page_title_color_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title .section_title',
				),
				array(
					'id' 			=> '_css_inner_title',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Inner Title', 'codevz' ),
					'button' 		=> esc_html__( 'Inner Title', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-size', 'padding' ),
					'selector' 		=> $c . ' .content .xtra-post-title',
					'dependency' 	=> array( 'page_cover|page_title', '==|any', 'title|2,8' )
				),
				array(
					'id' 			=> '_css_inner_title_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . ' .content .xtra-post-title'
				),
				array(
					'id' 			=> '_css_inner_title_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . ' .content .xtra-post-title'
				),
				array(
					'id' 			=> '_css_page_title_breadcrumbs_color',
					'hover_id' 		=> '_css_page_title_breadcrumbs_color_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Breadcrumbs', 'codevz' ),
					'button' 		=> esc_html__( 'Breadcrumbs', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-size' ),
					'selector' 		=> $c . '.page_title a,' . $c . '.page_title i',
					'dependency' 	=> array( 'page_cover|page_title', '==|any', 'title|4,5,6,7,8,9' )
				),
				array(
					'id' 			=> '_css_page_title_breadcrumbs_color_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title a,' . $c . '.page_title i',
				),
				array(
					'id' 			=> '_css_page_title_breadcrumbs_color_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title a,' . $c . '.page_title i',
				),
				array(
					'id' 			=> '_css_page_title_breadcrumbs_color_hover',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.page_title a:hover',
				),
				array(
					'id' 			=> '_css_breadcrumbs_inner_container',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'BR Inner', 'codevz' ),
					'button' 		=> esc_html__( 'BR Inner', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'width', 'padding' ),
					'selector' 		=> $c . '.breadcrumbs',
					'dependency' 	=> array( 'page_cover|page_title', '==|any', 'title|4,5,6,7,8,9' )
				),
				array(
					'id' 			=> '_css_breadcrumbs_inner_container_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.breadcrumbs',
				),
				array(
					'id' 			=> '_css_breadcrumbs_inner_container_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $c . '.breadcrumbs',
				),
			);
		}
	}

	/**
	 *
	 * Customize page options
	 * 
	 * @return array
	 *
	 */
	public static function options( $all = false ) {

		$options = array();

		// Custom SK options.
		$custom_stylekits = [];

		$custom_stylekits[] = array(
			'type'    => 'notice',
			'class'   => 'info xtra-notice',
			'content' => esc_html__( 'Custom StyleKits', 'codevz' )
		);

		$custom_stylekits[] = array(
			'id' 			=> 'custom_stylekits',
			'type' 			=> 'group',
			'title' 		=> esc_html__( 'Add Custom StyleKit', 'codevz' ),
			'help' 			=> esc_html__( 'You can add custom StyleKit for any CSS selectors', 'codevz' ),
			'desc' 			=> esc_html__( 'Save and refresh is required', 'codevz' ),
			'button_title' 	=> esc_html__( 'Add', 'codevz' ),
			'fields' 		=> [
				[
					'id'          => 'title',
					'type'        => 'text',
					'title'       => esc_html__( 'Title', 'codevz' ),
					'setting_args'=> [ 'transport' => 'postMessage' ],
				],
				[
					'id'          => 'selector',
					'type'        => 'text',
					'title'       => esc_html__( 'Selector', 'codevz' ),
					'setting_args'=> [ 'transport' => 'postMessage' ],
					'attributes'  => [ 'placeholder' => '.my-class' ],
				],
				[
					'id'          => 'hover',
					'type'        => 'text',
					'title'       => esc_html__( 'Hover', 'codevz' ),
					'setting_args'=> [ 'transport' => 'postMessage' ],
					'attributes'  => [ 'placeholder' => '.my-class:hover' ],
				],
			],
			'setting_args' 	=> [ 'transport' => 'postMessage' ]
		);

		$custom_stylekits_arr = Codevz_Plus::option( 'custom_stylekits', [] );

		foreach( $custom_stylekits_arr as $sk ) {

			if ( isset( $sk[ 'title' ] ) ) {

				$id = sanitize_title_with_dashes( $sk[ 'selector' ] );

				$custom_stylekits[] = array(
					'id' 			=> '_css_custom_sk_' . $id,
					'hover_id' 		=> '_css_custom_sk_' . $id . '_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> $sk[ 'title' ],
					'button' 		=> $sk[ 'title' ],
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'border' ),
					'selector' 		=> $sk[ 'selector' ]
				);
				$custom_stylekits[] = array(
					'id' 			=> '_css_custom_sk_' . $id . '_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $sk[ 'selector' ]
				);
				$custom_stylekits[] = array(
					'id' 			=> '_css_custom_sk_' . $id . '_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $sk[ 'selector' ]
				);
				$custom_stylekits[] = array(
					'id' 			=> '_css_custom_sk_' . $id . '_hover',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> $sk[ 'hover' ]
				);

			}

		}

		$options[] = [
			'name' 		=> 'demos',
			'title' 	=> esc_html__( 'Demo Importer', 'codevz' ),
			'fields' 	=> [
				array(
					'type'    		=> 'content',
					'content' 		=> '<a href="' . esc_url( get_admin_url() ) . 'admin.php?page=xtra-importer" target="_blank" class="button">' . esc_html__( 'Go to new Demo Importer', 'codevz' ) . '</a>',
				)
			]
		];

		// General Options.
		$options[]   = array(
			'name' 		=> 'general',
			'title' 	=> esc_html__( 'General', 'codevz' ),
			'sections' => array(

				array(
					'name'   => 'layout',
					'title'  => esc_html__( 'Layout', 'codevz' ),
					'fields' => array(
						array(
							'id' 			=> 'layout',
							'type' 			=> 'codevz_image_select',
							'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
							'help'  		=> esc_html__( 'The default option for all pages', 'codevz' ),
							'options' 		=> [
								'none' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
								'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
								'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
								'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
								'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
								'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
								'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
								'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
								'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
								'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
								'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
								'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
								'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
							],
							'default' 		=> 'none',
							'attributes' 	=> [ 'data-depend-id' => 'layout' ]
						),
						array(
							'id' 			=> 'boxed',
							'type' 			=> 'codevz_image_select',
							'title' 		=> esc_html__( 'Layout', 'codevz' ),
							'options' 		=> [
								'' 				=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/layout-1.png' ],
								'1'				=> [ esc_html__( 'Boxed', 'codevz' ) 				, Codevz_Plus::$url . 'assets/img/layout-2.png' ],
								'2'				=> [ esc_html__( 'Boxed Margin', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/layout-3.png' ],
							],
							'setting_args'  => [ 'transport' => 'postMessage' ]
						),
						array(
							'id'        => 'site_width',
							'type'      => 'slider',
							'title'     => esc_html__( 'Site Width', 'codevz' ),
							'options' 	=> array( 'unit' => 'px', 'step' => 10, 'min' => 1024, 'max' => 1400 ),
							'setting_args' 	  => [ 'transport' => 'postMessage' ]
						),
						array(
							'id'        => 'tablet_breakpoint',
							'type'      => 'slider',
							'title'     => esc_html__( 'On tablet', 'codevz' ),
							'help'      => '768px',
							'options' 	=> array( 'unit' => 'px', 'step' => 1, 'min' => 481, 'max' => 1024 ),
							'dependency' => [ 'xxx', 'xxx', 'xxx' ]
						),
						array(
							'id'        => 'mobile_breakpoint',
							'type'      => 'slider',
							'title'     => esc_html__( 'On mobile', 'codevz' ),
							'help'      => '480px',
							'options' 	=> array( 'unit' => 'px', 'step' => 1, 'min' => 280, 'max' => 767 ),
							'dependency' => [ 'xxx', 'xxx', 'xxx' ]
						),
						array(
							'id' 		=> 'sticky',
							'type' 		=> 'switcher',
							'title' 	=> esc_html__( 'Sticky Sidebar', 'codevz' )
						),
						array(
							'id'            => 'rtl',
							'type'          => 'switcher',
							'title'         => esc_html__( 'RTL Mode', 'codevz' )
						),
						array(
							'id'            => 'disable_responsive',
							'type'          => 'switcher',
							'title'         => esc_html__( 'Disable Responsive', 'codevz' )
						),
					)
				),

				array(
					'name'   => 'styling',
					'title'  => esc_html__( 'Colors & Styling', 'codevz' ),
					'fields' => wp_parse_args( $custom_stylekits, array(
						array(
							'id'        => 'site_color',
							'type'      => 'color_picker',
							'title'     => esc_html__( 'Accent Color', 'codevz' ),
							'desc'      => esc_html__( 'All primary colors will replace.', 'codevz' ),
							'setting_args' => [ 'transport' => 'postMessage' ]
						),
						array(
							'id'        => 'site_color_sec',
							'type'      => 'color_picker',
							'title'     => esc_html__( 'Secondary Color', 'codevz' ),
							'desc'      => esc_html__( 'All secondary colors will replace.', 'codevz' ) . ' ' . esc_html__( 'This color should be different from accent color.', 'codevz' ),
							'setting_args' => [ 'transport' => 'postMessage' ]
						),
						array(
							'id' 	=> 'dark',
							'type' 	=> 'switcher',
							'title' => esc_html__( 'Dark Mode', 'codevz' ),
							'help'  => esc_html__( 'Some sections have dynamic colors and it may you see them still in light mode, So you need to find and edit each settings manually.', 'codevz' )
						),
						array(
							'type'    => 'notice',
							'class'   => 'info',
							'content' => esc_html__( 'Styling', 'codevz' )
						),
						array(
							'id' 			=> '_css_body',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Body', 'codevz' ),
							'button' 		=> esc_html__( 'Body', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background' ),
							'selector' 		=> 'html,body',
						),
						array(
							'id' 			=> '_css_layout_1',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Boxed', 'codevz' ),
							'button' 		=> esc_html__( 'Boxed', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background' ),
							'selector' 		=> '#layout'
						),
						array(
							'id' 			=> '_css_buttons',
							'hover_id' 		=> '_css_buttons_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Buttons', 'codevz' ),
							'button' 		=> esc_html__( 'Buttons', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
							'selector' 		=> 'form button,.comment-form button,a.cz_btn,div.cz_btn,a.cz_btn_half_to_fill:before,a.cz_btn_half_to_fill_v:before,a.cz_btn_half_to_fill:after,a.cz_btn_half_to_fill_v:after,a.cz_btn_unroll_v:before, a.cz_btn_unroll_h:before,a.cz_btn_fill_up:before,a.cz_btn_fill_down:before,a.cz_btn_fill_left:before,a.cz_btn_fill_right:before,.wpcf7-submit,input[type=submit],input[type=button],.button,.cz_header_button,.woocommerce a.button,.woocommerce input.button,.woocommerce #respond input#submit.alt,.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt,.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, #edd-purchase-button, .edd-submit, [type=submit].edd-submit, .edd-submit.button.blue,.woocommerce #payment #place_order, .woocommerce-page #payment #place_order,.woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce a.button.wc-forward'
						),
						array(
							'id' 			=> '_css_buttons_mobile', 'type' => 'cz_sk_hidden', 'setting_args' => [ 'transport' => 'postMessage' ],
							'selector' 		=> 'form button,.comment-form button,a.cz_btn,div.cz_btn,a.cz_btn_half_to_fill:before,a.cz_btn_half_to_fill_v:before,a.cz_btn_half_to_fill:after,a.cz_btn_half_to_fill_v:after,a.cz_btn_unroll_v:before, a.cz_btn_unroll_h:before,a.cz_btn_fill_up:before,a.cz_btn_fill_down:before,a.cz_btn_fill_left:before,a.cz_btn_fill_right:before,.wpcf7-submit,input[type=submit],input[type=button],.button,.cz_header_button,.woocommerce a.button,.woocommerce input.button,.woocommerce #respond input#submit.alt,.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt,.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, #edd-purchase-button, .edd-submit, [type=submit].edd-submit, .edd-submit.button.blue,.woocommerce #payment #place_order, .woocommerce-page #payment #place_order,.woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce a.button.wc-forward'
						),
						array(
							'id' 			=> '_css_buttons_hover', 'type' => 'cz_sk_hidden', 'setting_args' => [ 'transport' => 'postMessage' ],
							'selector' 		=> 'form button:hover,.comment-form button:hover,a.cz_btn:hover,div.cz_btn:hover,a.cz_btn_half_to_fill:hover:before, a.cz_btn_half_to_fill_v:hover:before,a.cz_btn_half_to_fill:hover:after, a.cz_btn_half_to_fill_v:hover:after,a.cz_btn_unroll_v:after, a.cz_btn_unroll_h:after,a.cz_btn_fill_up:after,a.cz_btn_fill_down:after,a.cz_btn_fill_left:after,a.cz_btn_fill_right:after,.wpcf7-submit:hover,input[type=submit]:hover,input[type=button]:hover,.button:hover,.cz_header_button:hover,.woocommerce a.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit.alt:hover,.woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, #edd-purchase-button:hover, .edd-submit:hover, [type=submit].edd-submit:hover, .edd-submit.button.blue:hover, .edd-submit.button.blue:focus,.woocommerce #payment #place_order:hover, .woocommerce-page #payment #place_order:hover,.woocommerce div.product form.cart .button:hover,.woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce a.button.wc-forward:hover'
						),
						array(
							'id' 			=> '_css_all_img_tags',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Images', 'codevz' ),
							'button' 		=> esc_html__( 'Images', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector'    	=> '.page_content img, a.cz_post_image img, footer img, .cz_image_in, .wp-block-gallery figcaption, .cz_grid .cz_grid_link'
						),
						array(
							'id' 			=> '_css_social_tooltip',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Tooltips', 'codevz' ),
							'button' 		=> esc_html__( 'Tooltips', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'font-weight', 'letter-spacing', 'line-height', 'padding', 'margin', 'border' ),
							'selector' 		=> '[class*="cz_tooltip_"] [data-title]:after'
						),
						array(
							'id' 			=> '_css_input_textarea',
							'hover_id' 		=> '_css_input_textarea_focus',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Inputs', 'codevz' ),
							'button' 		=> esc_html__( 'Inputs', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
							'selector' 		=> 'input,textarea,select,.qty,.woocommerce-input-wrapper .select2-selection--single'
						),
						array(
							'id' 			=> '_css_input_textarea_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'input,textarea,select,.qty,.woocommerce-input-wrapper .select2-selection--single'
						),
						array(
							'id' 			=> '_css_input_textarea_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'input,textarea,select,.qty,.woocommerce-input-wrapper .select2-selection--single'
						),
						array(
							'id' 			=> '_css_input_textarea_focus',
							'type' 			=> 'cz_sk_hidden',
							'title' 		=> esc_html__( 'Focus', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'input:focus,textarea:focus,select:focus'
						),

						array(
							'type'    => 'notice',
							'class'   => 'info xtra-notice',
							'content' => esc_html__( 'Sidebar & Widgets', 'codevz' )
						),
						array(
							'id' 			=> '_css_sidebar_primary',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
							'button' 		=> esc_html__( 'Sidebar', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'border' ),
							'selector' 		=> '.sidebar_inner'
						),
						array(
							'id' 			=> '_css_widgets',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Widgets', 'codevz' ),
							'button' 		=> esc_html__( 'Widgets', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
							'selector' 		=> '.widget'
						),
						array(
							'id' 			=> '_css_widgets_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.widget'
						),
						array(
							'id' 			=> '_css_widgets_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.widget'
						),
						array(
							'id' 			=> '_css_widgets_headline',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Titles', 'codevz' ),
							'button' 		=> esc_html__( 'Titles', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'margin', 'width', 'height', 'border', 'top', 'left', 'bottom', 'right' ),
							'selector' 		=> '.widget > h4'
						),
						array(
							'id' 			=> '_css_widgets_headline_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.widget > h4'
						),
						array(
							'id' 			=> '_css_widgets_headline_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.widget > h4'
						),
						array(
							'id' 			=> '_css_widgets_links',
							'hover_id' 		=> '_css_widgets_links_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Links', 'codevz' ),
							'button' 		=> esc_html__( 'Links', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color' ),
							'selector' 		=> '.widget a'
						),
						array(
							'id' 			=> '_css_widgets_links_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.widget a:hover'
						),
						array(
							'id' 			=> '_css_widgets_headline_before',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Title Shape 1', 'codevz' ),
							'button' 		=> esc_html__( 'Title Shape 1', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'margin', 'width', 'height', 'border', 'top', 'left', 'bottom', 'right' ),
							'selector' 		=> '.widget > h4:before'
						),
						array(
							'id' 			=> '_css_widgets_headline_before_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.widget > h4:before'
						),
						array(
							'id' 			=> '_css_widgets_headline_before_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.widget > h4:before'
						),
						array(
							'id' 			=> '_css_widgets_headline_after',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Title Shape 2', 'codevz' ),
							'button' 		=> esc_html__( 'Title Shape 2', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'text-align', 'border' ),
							'selector' 		=> '.widget > h4:after'
						),
						array(
							'id' 			=> '_css_widgets_headline_after_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.widget > h4:after'
						),
						array(
							'id' 			=> '_css_widgets_headline_after_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.widget > h4:after'
						),
					) )
				),

				// Share
				array(
					'name'   => 'share',
					'title'  => esc_html__( 'Share Icons', 'codevz' ),
					'fields' => array(
						array(
							'id' 		=> 'post_type',
							'type' 		=> 'checkbox',
							'title' 	=> esc_html__( 'Post Type', 'codevz' ),
							'options' 	=> self::share_post_types()
						),

						array(
							'id' 		=> 'share',
							'type' 		=> 'checkbox',
							'title' 	=> esc_html__( 'Share Icons', 'codevz' ),
							'options' 	=> array(
								'facebook'	=> esc_html__( 'Facebook', 'codevz' ),
								'twitter'	=> esc_html__( 'Twitter', 'codevz' ),
								'pinterest'	=> esc_html__( 'Pinterest', 'codevz' ),
								'reddit'	=> esc_html__( 'Reddit', 'codevz' ),
								'delicious'	=> esc_html__( 'Delicious', 'codevz' ),
								'linkedin'	=> esc_html__( 'Linkedin', 'codevz' ),
								'whatsapp'	=> esc_html__( 'Whatsapp', 'codevz' ),
								'telegram'	=> esc_html__( 'Telegram', 'codevz' ),
								'envelope'	=> esc_html__( 'Email', 'codevz' ),
								'print'		=> esc_html__( 'Print', 'codevz' ),
								'copy'		=> esc_html__( 'Shortlink', 'codevz' ),
							)
						),

						array(
							'id' 		=> 'share_box_title',
							'type' 		=> 'text',
							'title' 	=> esc_html__( 'Title', 'codevz' ),
						),
						array(
							'id' 		=> 'share_color',
							'type' 		=> 'select',
							'title' 	=> esc_html__( 'Color Mode', 'codevz' ),
							'options' 	=> array(
								'cz_social_colored' 		=> esc_html__( 'Brand Colors', 'codevz' ),
								'cz_social_colored_hover' 	=> esc_html__( 'Brand Colors on Hover', 'codevz' ),
								'cz_social_colored_bg' 		=> esc_html__( 'Brand Background', 'codevz' ),
								'cz_social_colored_bg_hover' => esc_html__( 'Brand Background on Hover', 'codevz' ),
							),
							'default_option' => esc_html__( '~ Disable ~', 'codevz'),
						),

						array(
							'id' 		=> 'share_tooltip',
							'type' 		=> 'switcher',
							'title' 	=> esc_html__( 'Tooltip', 'codevz' ),
							'help' 		=> esc_html__( 'StyleKit located in Theme Options > General > Colors & Styling', 'codevz' )
						),

						array(
							'id' 		=> 'share_title',
							'type' 		=> 'switcher',
							'title' 	=> esc_html__( 'Inline Title', 'codevz' )
						),

						array(
							'id' 			=> '_css_share',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Container', 'codevz' ),
							'button' 		=> esc_html__( 'Container', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'margin', 'border' ),
							'selector' 		=> 'div.xtra-share'
						),
						array(
							'id' 			=> '_css_share_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share'
						),
						array(
							'id' 			=> '_css_share_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share'
						),

						array(
							'id' 			=> '_css_share_title',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Title', 'codevz' ),
							'button' 		=> esc_html__( 'Title', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'margin', 'border' ),
							'selector' 		=> 'div.xtra-share:before'
						),
						array(
							'id' 			=> '_css_share_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share:before'
						),
						array(
							'id' 			=> '_css_share_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share:before'
						),

						array(
							'id' 			=> '_css_share_a',
							'hover_id' 		=> '_css_share_a_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Icons', 'codevz' ),
							'button' 		=> esc_html__( 'Icons', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'opacity', 'font-size', 'padding', 'margin', 'border' ),
							'selector' 		=> 'div.xtra-share a'
						),
						array(
							'id' 			=> '_css_share_a_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share a'
						),
						array(
							'id' 			=> '_css_share_a_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share a'
						),
						array(
							'id' 			=> '_css_share_a_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share a:hover'
						),

						array(
							'id' 			=> '_css_share_inline_title',
							'hover_id' 		=> '_css_share_inline_title_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Inline Title', 'codevz' ),
							'button' 		=> esc_html__( 'Inline Title', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'margin', 'border' ),
							'selector' 		=> 'div.xtra-share a span',
							'dependency' 	=> [ 'share_title', '!=', '' ]
						),
						array(
							'id' 			=> '_css_share_inline_title_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share a span'
						),
						array(
							'id' 			=> '_css_share_inline_title_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share a span'
						),
						array(
							'id' 			=> '_css_share_inline_title_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-share a:hover span'
						),

					)
				),

				// SEO
				array(
					'name'   => 'general_seo',
					'title'  => esc_html__( 'SEO', 'codevz' ),
					'fields' => array(
						array(
							'id'            => 'page_title_tag',
							'type'          => 'select',
							'title'         => esc_html__( 'Title Tag', 'codevz' ),
							'options'       => array(
								'' 		=> 'H1',
								'h2' 	=> 'H2',
								'h3' 	=> 'H3',
								'h4' 	=> 'H4',
								'h5' 	=> 'H5',
								'h6' 	=> 'H6',
								'p' 	=> 'p',
								'div' 	=> 'div',
							),
						),
						array(
							'id' 			  => 'seo_meta_tags',
							'type' 			  => 'switcher',
							'title' 		  => esc_html__( 'Meta Tags', 'codevz' ),
							'help' 			  => esc_html__( 'If you are not using any SEO plugin, So turn this option ON, This will automatically add meta tags to all pages according to page title, content and kewords.', 'codevz' ),
							'setting_args' 	  => [ 'transport' => 'postMessage' ]
						),
						array(
							'id' 			  => 'seo_desc',
							'type' 			  => 'textarea',
							'title' 		  => esc_html__( 'Short Description', 'codevz' ),
							'setting_args' 	  => [ 'transport' => 'postMessage' ],
							'dependency' 	  => array( 'seo_meta_tags', '==', 'true' )
						),
						array(
							'id' 			  => 'seo_keywords',
							'type' 			  => 'textarea',
							'title' 		  => esc_html__( 'Keywords', 'codevz' ),
							'help' 			  => esc_html__( 'Separate words with comma', 'codevz' ),
							'setting_args' 	  => [ 'transport' => 'postMessage' ],
							'dependency' 	  => array( 'seo_meta_tags', '==', 'true' )
						),
					),
				),

				array(
					'name'   => 'loading',
					'title'  => esc_html__( 'Loading', 'codevz' ),
					'fields' => array(
						array(
							'id'			=> 'pageloader',
							'type'			=> 'switcher',
							'title'			=> esc_html__( 'Loading', 'codevz' ),
						),
						array(
							'id'            => 'loading_out_fx',
							'type'          => 'select',
							'title'         => esc_html__( 'Effect', 'codevz' ),
							'options'       => array(
								''						=> esc_html__( 'Fade', 'codevz' ),
								'pageloader_down'		=> esc_html__( 'Down', 'codevz' ),
								'pageloader_up'			=> esc_html__( 'Up', 'codevz' ),
								'pageloader_left'		=> esc_html__( 'Left', 'codevz' ),
								'pageloader_right'		=> esc_html__( 'Right', 'codevz' ),
								'pageloader_circle'		=> esc_html__( 'Circle', 'codevz' ),
								'pageloader_center_h'	=> esc_html__( 'Center horizontal', 'codevz' ),
								'pageloader_center_v'	=> esc_html__( 'Center vertical', 'codevz' ),
								'pageloader_pa'			=> esc_html__( 'Polygon 1', 'codevz' ),
								'pageloader_pb'			=> esc_html__( 'Polygon 2', 'codevz' ),
								'pageloader_pc'			=> esc_html__( 'Polygon 3', 'codevz' ),
								'pageloader_pd'			=> esc_html__( 'Polygon 4', 'codevz' ),
								'pageloader_pe'			=> esc_html__( 'Polygon 5', 'codevz' ),
							),
							'dependency'  	=> array( 'pageloader', '==|!=', 'true' ),
						),
						array(
							'id'            => 'preloader_type',
							'type'          => 'select',
							'title'         => esc_html__( 'Type', 'codevz' ),
							'options'       => array(
								''				=> esc_html__( 'Image', 'codevz' ),
								'percentage'	=> esc_html__( 'Percentage', 'codevz' ),
								'custom'		=> esc_html__( 'Custom Code', 'codevz' ),
							),
							'dependency'    => array( 'pageloader', '==', true ),
							'setting_args'  => [ 'transport' => 'postMessage' ]
						),
						array(
							'id'			=> 'pageloader_img',
							'type'			=> 'upload',
							'title'			=> esc_html__('Image', 'codevz'),
							'preview'       => 1,
							'dependency'  	=> array( 'pageloader|preloader_type|preloader_type', '==|!=|!=', 'true|custom|percentage' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ]
						),
						array(
							'id'			=> 'pageloader_custom',
							'type'			=> 'textarea',
							'title'			=> esc_html__('Custom Code', 'codevz'),
							'preview'       => 1,
							'dependency'  	=> array( 'pageloader|preloader_type', '==|==', 'true|custom' )
						),
						array(
							'id' 			=> '_css_preloader',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Background', 'codevz' ),
							'button' 		=> esc_html__( 'Background', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background' ),
							'selector' 		=> '.pageloader',
							'dependency' 	=> array( 'pageloader', '==', true )
						),
						array(
							'id' 			=> '_css_preloader_percentage',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Loading', 'codevz' ),
							'button' 		=> esc_html__( 'Loading', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'border' ),
							'selector' 		=> '.pageloader > *',
							'dependency' 	=> array( 'pageloader', '==', true )
						),
					),
				),

				array(
					'name'    => 'page_404',
					'title'   => esc_html__( 'Page 404', 'codevz' ),
					'fields'  => array(
						array(
							'id'            => '404_title',
							'type'          => 'text',
							'title'         => esc_html__( 'Title', 'codevz' ),
							'default'       => '404',
							//'setting_args'  => array( 'transport' => 'postMessage' )
						),
						array(
							'id'            => '404_msg',
							'type'          => 'textarea',
							'title'         => esc_html__( 'Description', 'codevz' ),
							'default'       => esc_html__( 'How did you get here?! It’s cool. We’ll help you out.', 'codevz' ),
							//'setting_args'  => array('transport' => 'postMessage')
						),
						array(
							'id' 			=> 'disable_search_404',
							'type' 			=> 'switcher',
							'title' 		=> esc_html__( 'Disable Search', 'codevz' ),
						),
						array(
							'id'            => '404_btn',
							'type'          => 'text',
							'title'         => esc_html__( 'Button', 'codevz' ),
							'default'       => esc_html__( 'Back to Homepage', 'codevz' )
						),
						array(
							'type'    		=> 'notice',
							'class'   		=> 'info',
							'content' 		=> esc_html__( 'If you want to have custom page 404, Create a new page from Dashboard > Pages > Add New, set title to 404 and change slug to page-404 or not-found then save it as draft', 'codevz' )
						),
					)
				),

				array(
					'name'   => 'custom_codes',
					'title'  => esc_html__( 'Custom Codes', 'codevz' ),
					'fields' => array(
						array(
							'id'		=> 'css',
							'type'		=> 'textarea',
							'title'		=> esc_html__('Custom CSS', 'codevz'),
							'help'		=> esc_html__('Insert codes without style tag', 'codevz'),
							'attributes' => array(
								'placeholder' => ".selector {font-size: 20px}",
			  					'style'       => "direction: ltr",
							),
							'setting_args' 	=> [ 'transport' => 'postMessage' ]
						),
						array(
							'id'		=> 'js',
							'type'		=> 'textarea',
							'title'		=> esc_html__('Custom JS', 'codevz'),
							'help'		=> esc_html__('Insert codes without script tag', 'codevz'),
							'attributes' => array(
								'placeholder' => "jQuery('.selector').addClass('class');",
			  					'style'       => "direction: ltr",
							)
						),
						array(
							'id'		=> 'head_codes',
							'type'		=> 'textarea',
							'title'		=> esc_html__('Before closing &lt;/head&gt;', 'codevz'),
							'attributes' => [ 'style' => "direction: ltr" ],
						),
						array(
							'id'		=> 'body_codes',
							'type'		=> 'textarea',
							'title'		=> esc_html__('After opening &lt;body&gt;', 'codevz'),
							'attributes' => array(
							  'style'       => "direction: ltr",
							),
							'dependency' 	=> array( 'xxx', '==', 'xxx' )
						),
						array(
							'id'		=> 'foot_codes',
							'type'		=> 'textarea',
							'title'		=> esc_html__('Before closing &lt;/body&gt;', 'codevz'),
							'attributes' => array(
							  'style'       => "direction: ltr",
							),
						),
					),
				),

				array(
					'name'    => 'white_label',
					'title'   => esc_html__( 'White Label', 'codevz' ),
					'fields'  => [
						[
							'id' 			=> 'disable',
							'type' 			=> 'checkbox',
							'title' 		=> esc_html__( 'Disable Features', 'codevz' ),
							'options' 		=> [
								'menu'			=> esc_html__( 'Hide XTRA menu', 'codevz' ),
								'activation'	=> esc_html__( 'Hide Activation menu', 'codevz' ),
								'videos'		=> esc_html__( 'Hide Elements videos', 'codevz' ),
								'importer'		=> esc_html__( 'Hide Demo importer menu', 'codevz' ),
								'options' 		=> esc_html__( 'Hide Theme options menus', 'codevz' ),
								'envato' 		=> esc_html__( 'Hide Dashboard Envato logo', 'codevz' ),
								'presets'		=> esc_html__( 'Hide Page builder presets', 'codevz' ),
								'templates'		=> esc_html__( 'Hide Page builder templates', 'codevz' ),
							],
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
						],
						[
							'id' 			=> 'white_label_menu_icon',
							'type' 			=> 'upload',
							'title' 		=> esc_html__( 'Menu Icon', 'codevz' ),
							'help' 			=> '20x20 PX',
							'preview' 		=> true,
							'setting_args'  => [ 'transport' => 'postMessage' ],
						],
						[
							'id' 			=> 'white_label_welcome_page_logo',
							'type' 			=> 'upload',
							'title' 		=> esc_html__( 'Welcome Page Logo', 'codevz' ),
							'help' 			=> '90x90 PX',
							'preview' 		=> true,
							'setting_args'  => [ 'transport' => 'postMessage' ],
						],
						[
							'type' 			=> 'notice',
							'class' 		=> 'info',
							'content' 		=> esc_html__( 'Warning: If you change below options, your style.css for both parent and child theme will reset and override.', 'codevz' ) . ' ' . esc_html__( 'Automatic updates will be disabled.', 'codevz' )
						],
						[
							'id' 			=> 'white_label_theme_name',
							'type' 			=> 'text',
							'title' 		=> esc_html__( 'Theme Name', 'codevz' ),
							'setting_args'  => [ 'transport' => 'postMessage' ]
						],
						[
							'id' 			=> 'white_label_theme_description',
							'type' 			=> 'text',
							'title' 		=> esc_html__( 'Description', 'codevz' ),
							'setting_args'  => [ 'transport' => 'postMessage' ]
						],
						[
							'id' 			=> 'white_label_theme_screenshot',
							'type' 			=> 'upload',
							'title' 		=> esc_html__( 'Screenshot', 'codevz' ),
							'preview' 		=> true,
							'help' 			=> '1200x900 PX',
							'setting_args'  => [ 'transport' => 'postMessage' ]
						],
						[
							'type' 			=> 'notice',
							'class' 		=> 'info',
							'content' 		=> esc_html__( 'Plugin', 'codevz' )
						],
						[
							'id' 			=> 'white_label_plugin_name',
							'type' 			=> 'text',
							'title' 		=> esc_html__( 'Name', 'codevz' ),
							'setting_args'  => [ 'transport' => 'postMessage' ]
						],
						[
							'id' 			=> 'white_label_plugin_description',
							'type' 			=> 'text',
							'title' 		=> esc_html__( 'Description', 'codevz' ),
							'setting_args'  => [ 'transport' => 'postMessage' ]
						],
						[
							'type' 			=> 'notice',
							'class' 		=> 'info',
							'content' 		=> esc_html__( 'Author and link', 'codevz' )
						],
						[
							'id' 			=> 'white_label_author',
							'type' 			=> 'text',
							'title' 		=> esc_html__( 'Name', 'codevz' ),
							'setting_args'  => [ 'transport' => 'postMessage' ]
						],
						[
							'id' 			=> 'white_label_link',
							'type' 			=> 'text',
							'title' 		=> esc_html__( 'Link', 'codevz' ),
							'setting_args'  => [ 'transport' => 'postMessage' ]
						],
					]
				),

				array(
					'name'   => 'cookie',
					'title'  => esc_html__( 'Cookie Notice', 'codevz' ),
					'fields' => array(
						array(
							'id'            => 'cookie',
							'type'          => 'select',
							'title'         => esc_html__( 'Simple Cookie', 'codevz' ),
							'options'       => [
								'' 					=> esc_html__( '~ Disable ~', 'codevz' ),
								'xtra-cookie-bl' 	=> esc_html__( 'Bottom left', 'codevz' ),
								'xtra-cookie-br' 	=> esc_html__( 'Bottom right', 'codevz' ),
								'xtra-cookie-tl' 	=> esc_html__( 'Top left', 'codevz' ),
								'xtra-cookie-tr' 	=> esc_html__( 'Top right', 'codevz' ),
							],
							'setting_args'  => [ 'transport' => 'postMessage' ]
						),
						array(
							'id'		=> 'cookie_content',
							'type'		=> 'textarea',
							'title'		=> esc_html__( 'Content', 'codevz' ),
							'help' 		=> esc_html__( 'Shortcode or custom HTML allowed', 'codevz' ),
							'default' 	=> esc_html__( 'We use cookies from third party services for marketing activities to offer you a better experience.' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'dependency' 	=> [ 'cookie', '!=', '' ]
						),
						array(
							'id'		=> 'cookie_button',
							'type'		=> 'text',
							'title'		=> esc_html__( 'Button', 'codevz' ),
							'default'	=> esc_html__( 'Accept and close', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'dependency' 	=> [ 'cookie', '!=', '' ]
						),
						array(
							'id' 			=> '_css_cookie',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Container', 'codevz' ),
							'button' 		=> esc_html__( 'Container', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'margin', 'border' ),
							'selector' 		=> 'div.xtra-cookie',
							'dependency' 	=> [ 'cookie', '!=', '' ]
						),
						array(
							'id' 			=> '_css_cookie_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-cookie'
						),
						array(
							'id' 			=> '_css_cookie_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'div.xtra-cookie'
						),
						array(
							'id' 			=> '_css_cookie_button',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Button', 'codevz' ),
							'button' 		=> esc_html__( 'Button', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'margin', 'border' ),
							'selector' 		=> 'a.xtra-cookie-button',
							'dependency' 	=> [ 'cookie', '!=', '' ]
						),
						array(
							'id' 			=> '_css_cookie_button_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'a.xtra-cookie-button'
						),
						array(
							'id' 			=> '_css_cookie_button_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'a.xtra-cookie-button'
						),
					),
				),

				array(
					'name'   => 'general_more',
					'title'  => esc_html__( 'Advanced Settings', 'codevz' ),
					'fields' => array(
						array(
							'id'            => 'popup',
							'type'          => 'select',
							'title'         => esc_html__( 'Popup', 'codevz' ),
							'options'       => Codevz_Plus::$array_pages,
							'dependency' 	=> [ 'xxx', '==', 'xxx' ]
						),
						array(
							'id'            => 'lazyload',
							'type'          => 'radio',
							'title'         => esc_html__( 'Lazyload Images', 'codevz' ),
							'help'          => esc_html__( 'Speed up your site by loading images on page scrolling', 'codevz' ),
							'setting_args'  => array( 'transport' => 'postMessage' ),
							'options' 		=> [
								'' 				=> esc_html__( 'Disable', 'codevz' ),
								'true' 			=> esc_html__( 'jQuery Lazyload', 'codevz' ),
								'wp' 			=> esc_html__( 'WordPress Lazyload', 'codevz' ),
							],
							'attributes' 	=> [ 'data-depend-id' => 'lazyload' ]
						),
						array(
							'id'            => 'lazyload_alter',
							'type'          => 'upload',
							'preview'       => true,
							'title'         => esc_html__( 'Custom Lazyload', 'codevz' ),
							'help'          => 'GIF or SVG',
							'setting_args'  => [ 'transport' => 'postMessage' ],
							'dependency' 	=> [ 'lazyload', '==', 'true' ]
						),
						array(
							'id' 			=> 'lazyload_size',
							'type' 			=> 'slider',
							'title' 		=> esc_html__( 'Lazyload Size', 'codevz' ),
							'options' 		=> array( 'unit' => 'px', 'step' => 5, 'min' => 30, 'max' => 500 ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'dependency' 	=> [ 'lazyload', '==', 'true' ]
						),
						array(
							'id'            => 'maintenance_mode',
							'type'          => 'select',
							'title'         => esc_html__( 'Maintenance', 'codevz' ),
							'help'          => esc_html__( 'You can create a coming soon or maintenance mode page then assign it here. All your website visitors will redirect to this page.', 'codevz' ),
							//'options'       => Codevz_Plus::$array_pages,
							'options'       => wp_parse_args( Codevz_Plus::$array_pages, [
								'' 				=> esc_html__( '~ Disable ~', 'codevz' ),
								'simple' 		=> esc_html__( '~ Simple ~', 'codevz' )
							]),
							'setting_args'  => [ 'transport' => 'postMessage' ]
						),
						array(
							'id'			=> 'maintenance_message',
							'type'			=> 'textarea',
							'title'			=> esc_html__( 'Maintenance Message', 'codevz' ),
							'attributes' 	=> [
								'placeholder' 	=> esc_html__( 'We are under maintenance mode, We will back soon.', 'codevz' )
							],
							'dependency' 	=> [ 'maintenance_mode', '==', 'simple' ]
						),
						array(
							'id'            => 'force_disable_comments',
							'type'          => 'switcher',
							'title'         => esc_html__( 'Disable Comments', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
						),
						array(
							'id'            => 'disable_lightbox',
							'type'          => 'switcher',
							'title'         => esc_html__( 'Disable Lightbox', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
						),
						array(
							'id' 			=> 'add_post_type',
							'type' 			=> 'group',
							'title' 		=> esc_html__( 'Add', 'codevz' ),
							'button_title' 	=> esc_html__( 'Add', 'codevz' ),
							'fields' 		=> array(
								array(
									'id'          => 'name',
									'type'        => 'text',
									'title'       => esc_html__('Name', 'codevz'),
									'desc' 		  => 'e.g. cz_projects or cz_movies',
									'setting_args'=> [ 'transport' => 'postMessage' ],
								),
							),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'dependency' 	=> [ 'xxx', '==', 'xxx' ]
						),

					),

				),
			),
		);

		$options[]   = array(
			'name' 		=> 'typography',
			'title' 	=> esc_html__( 'Typography', 'codevz' ),
			'fields' => array(

				array(
					'id' 			=> '_css_body_typo',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Body', 'codevz' ),
					'button' 		=> esc_html__( 'Body', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'line-height' ),
					'selector' 		=> 'body, body.rtl, .rtl form'
				),
				array(
					'id' 			=> '_css_body_typo_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body, body.rtl, .rtl form'
				),
				array(
					'id' 			=> '_css_body_typo_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body, body.rtl, .rtl form'
				),
				array(
					'id' 			=> '_css_menu_nav_typo',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Menu', 'codevz' ),
					'button' 		=> esc_html__( 'Menu', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-family' ),
					'selector' 		=> '.sf-menu, .sf-menu > .cz > a'
				),
				array(
					'id' 			=> '_css_all_headlines',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Headlines', 'codevz' ),
					'button' 		=> esc_html__( 'Headlines', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-family', 'line-height' ),
					'selector' 		=> 'h1,h2,h3,h4,h5,h6'
				),
				array(
					'id' 			=> '_css_all_headlines_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'h1,h2,h3,h4,h5,h6'
				),
				array(
					'id' 			=> '_css_all_headlines_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'h1,h2,h3,h4,h5,h6'
				),
				array(
					'id' 			=> '_css_h1',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'H1', 'codevz' ),
					'button' 		=> esc_html__( 'H1', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'line-height' ),
					'selector' 		=> 'body h1'
				),
				array(
					'id' 			=> '_css_h1_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h1'
				),
				array(
					'id' 			=> '_css_h1_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h1'
				),
				array(
					'id' 			=> '_css_h2',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'H2', 'codevz' ),
					'button' 		=> esc_html__( 'H2', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'line-height' ),
					'selector' 		=> 'body h2'
				),
				array(
					'id' 			=> '_css_h2_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h2'
				),
				array(
					'id' 			=> '_css_h2_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h2'
				),
				array(
					'id' 			=> '_css_h3',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'H3', 'codevz' ),
					'button' 		=> esc_html__( 'H3', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'line-height' ),
					'selector' 		=> 'body h3'
				),
				array(
					'id' 			=> '_css_h3_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h3'
				),
				array(
					'id' 			=> '_css_h3_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h3'
				),
				array(
					'id' 			=> '_css_h4',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'H4', 'codevz' ),
					'button' 		=> esc_html__( 'H4', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'line-height' ),
					'selector' 		=> 'body h4'
				),
				array(
					'id' 			=> '_css_h4_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h4'
				),
				array(
					'id' 			=> '_css_h4_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h4'
				),
				array(
					'id' 			=> '_css_h5',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'H5', 'codevz' ),
					'button' 		=> esc_html__( 'H5', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'line-height' ),
					'selector' 		=> 'body h5'
				),
				array(
					'id' 			=> '_css_h5_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h5'
				),
				array(
					'id' 			=> '_css_h5_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h5'
				),
				array(
					'id' 			=> '_css_h6',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'H6', 'codevz' ),
					'button' 		=> esc_html__( 'H6', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'line-height' ),
					'selector' 		=> 'body h6'
				),
				array(
					'id' 			=> '_css_h6_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h6'
				),
				array(
					'id' 			=> '_css_h6_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'body h6'
				),
				array(
					'id' 			=> '_css_p',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Paragraphs', 'codevz' ),
					'button' 		=> esc_html__( 'Paragraphs', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-size', 'line-height', 'margin' ),
					'selector' 		=> 'p'
				),
				array(
					'id' 			=> '_css_p_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'p'
				),
				array(
					'id' 			=> '_css_p_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'p'
				),
				array(
					'id' 			=> '_css_a',
					'hover_id' 		=> '_css_a_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Links', 'codevz' ),
					'button' 		=> esc_html__( 'Links', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-weight', 'font-style', 'text-decoration' ),
					'selector' 		=> 'a'
				),
				array(
					'id' 			=> '_css_a_tablet',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'a'
				),
				array(
					'id' 			=> '_css_a_mobile',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'a'
				),
				array(
					'id' 			=> '_css_a_hover',
					'type' 			=> 'cz_sk_hidden',
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'selector' 		=> 'a:hover'
				),

				array(
					'id'              => 'wp_editor_fonts',
					'type'            => 'group', 
					'title' 		  => esc_html__( 'Fonts for WP Editor', 'codevz' ),
					'help' 			  => esc_html__( 'You can add custom google fonts and use them inside WP Editor in posts or page builder elements', 'codevz' ),
					'desc' 			  => esc_html__( 'Maximum add 2 fonts', 'codevz' ),
					'button_title'    => esc_html__( 'Add', 'codevz' ),
					'fields'          => array(
						array(
							'id' 		     => 'font',
							'type' 		     => 'select_font',
							'title' 	     => esc_html__('Font family', 'codevz')
						),
					),
					'setting_args' 	  => [ 'transport' => 'postMessage' ]
				),
				array(
					'id'              => 'custom_fonts',
					'type'            => 'group', 
					'title' 		  => esc_html__( 'Add Custom Font', 'codevz' ),
					'help' 			  => esc_html__( 'You can add your own custom font name and access it from fonts library and WP Editor, You should upload font files and add font CSS via child theme or other way by yourself', 'codevz' ),
					'desc' 			  => esc_html__( 'Save and refresh is required', 'codevz' ),
					'button_title'    => esc_html__( 'Add', 'codevz' ),
					'fields'          => array(
						array(
							'id' 		     => 'font',
							'type' 		     => 'text',
							'title' 	     => esc_html__('Font Name', 'codevz')
						),
					),
					'setting_args' 	  => [ 'transport' => 'postMessage' ],
					'dependency' 	  => [ 'xxx', '==', 'xxx' ],
				),
				array(
					'type'    => 'notice',
					'class'   => 'info',
					'content' => esc_html__( 'If you want to add custom font, You can install and use Add any Font plugin from WordPress.', 'codevz' )
				),
			),
		);

		$options[] = array(
			'name' 		=> 'header',
			'title' 	=> esc_html__( 'Header', 'codevz' ),
			'sections' => array(
				array(
					'name'   => 'header_elementor',
					'title'  => esc_html__( 'Custom Header Template', 'codevz' ),
					'fields' => array(
						array(
							'id'    	=> 'header_elementor',
							'type'  	=> 'select',
							'title' 	=> esc_html__( 'Select Header', 'codevz' ),
							'options' 	=> Codevz_Plus::$array_pages
						),
					),
				),

			  array(
				'name'   => 'header_logo',
				'title'  => esc_html__( 'Logo', 'codevz' ),
				'fields' => array(
						array(
							'id' 			=> 'logo',
							'type' 			=> 'upload',
							'title' 		=> esc_html__( 'Logo', 'codevz' ),
							'preview'       => 1,
							'setting_args' 	=> array('transport' => 'postMessage')
						),
						array(
							'id' 			=> 'logo_2',
							'type' 			=> 'upload',
							'title' 		=> esc_html__( 'Logo 2', 'codevz' ),
							'help' 			=> esc_html__( 'Useful for sticky header or footer', 'codevz' ),
							'preview'       => 1,
							'setting_args' 	=> array('transport' => 'postMessage')
						),
						array(
							'id'            => 'logo_hover_tooltip',
							'type'          => 'select',
							'title'         => esc_html__( 'Tooltip', 'codevz' ),
							'help' 			=> esc_html__( '[Deprecated]', 'codevz' ),
							'options'       => Codevz_Plus::$array_pages
						),
						array(
							'type'    => 'notice',
							'class'   => 'info xtra-notice',
							'content' => esc_html__( 'Styling', 'codevz' )
						),
						array(
							'id'            => '_css_logo_css',
							'type'          => 'cz_sk',
							'title'        => esc_html__( 'Logo', 'codevz' ),
							'button'        => esc_html__( 'Logo', 'codevz' ),
							'setting_args'  => [ 'transport' => 'postMessage' ],
							'settings'      => array( 'color', 'background', 'font-family', 'font-size', 'border' ),
							'selector'      => '.logo > a, .logo > h1, .logo h2',
						),
						array(
							'id' 			=> '_css_logo_css_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.logo > a, .logo > h1, .logo h2',
						),
						array(
							'id' 			=> '_css_logo_css_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.logo > a, .logo > h1, .logo h2',
						),
						array(
							'id' 			=> '_css_logo_2_css',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Logo 2', 'codevz' ),
							'button' 		=> esc_html__( 'Logo 2', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings'      => array( 'color', 'background', 'font-family', 'font-size', 'border' ),
							'selector' 		=> '.logo_2 > a, .logo_2 > h1'
						),
						array(
							'id' 			=> '_css_logo_2_css_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.logo_2 > a, .logo_2 > h1'
						),
						array(
							'id' 			=> '_css_logo_2_css_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.logo_2 > a, .logo_2 > h1'
						),

						array(
							'id' 			=> '_css_logo_hover_tooltip',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Tooltip', 'codevz' ),
							'button' 		=> esc_html__( 'Tooltip', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'width', 'border' ),
							'selector' 		=> 'div.logo_hover_tooltip',
							'dependency' 	=> array( 'logo_hover_tooltip', '!=', '' )
						),
					)
				),

				array(
					'name'   => 'header_social',
					'title'  => esc_html__( 'Social Icons', 'codevz' ),
					'fields' => array(
						array(
							'id'              => 'social',
							'type'            => 'group',
							'title'           => esc_html__( 'Social Icons', 'codevz' ),
							'button_title'    => esc_html__( 'Add', 'codevz' ),
							'accordion_title' => esc_html__( 'Add', 'codevz' ),
							'fields'          => array(
								array(
									'id'    	=> 'title',
									'type'  	=> 'text',
									'title' 	=> esc_html__( 'Title', 'codevz' )
								),
								array(
									'id'    	=> 'icon',
									'type'  	=> 'icon',
									'title' 	=> esc_html__( 'Icon', 'codevz' ),
									'default' 	=> 'fa fa-facebook'
								),
								array(
									'id'    	=> 'link',
									'type'  	=> 'text',
									'title' 	=> esc_html__( 'Link', 'codevz' )
								),
							),
							'setting_args' 	     => [ 'transport' => 'postMessage' ],
							'selective_refresh'  => array(
								'selector' 			=> '.elms_row .cz_social',
								'settings' 			=> 'codevz_theme_options[social]',
								'render_callback'  	=> function() {
									return Codevz_Plus::social();
								},
								'container_inclusive' => true
							),
						),
						array(
							'id'            => 'social_hover_fx',
							'type'          => 'select',
							'title'         => esc_html__( 'Icons Hover', 'codevz' ),
							'options'       => array(
								'cz_social_fx_0' => esc_html__( 'ZoomIn', 'codevz' ),
								'cz_social_fx_1' => esc_html__( 'ZoomOut', 'codevz' ),
								'cz_social_fx_2' => esc_html__( 'Bottom to Top', 'codevz' ),
								'cz_social_fx_3' => esc_html__( 'Top to Bottom', 'codevz' ),
								'cz_social_fx_4' => esc_html__( 'Left to Right', 'codevz' ),
								'cz_social_fx_5' => esc_html__( 'Right to Left', 'codevz' ),
								'cz_social_fx_6' => esc_html__( 'Rotate', 'codevz' ),
								'cz_social_fx_7' => esc_html__( 'Infinite Shake', 'codevz' ),
								'cz_social_fx_8' => esc_html__( 'Infinite Wink', 'codevz' ),
								'cz_social_fx_9' => esc_html__( 'Quick Bob', 'codevz' ),
								'cz_social_fx_10'=> esc_html__( 'Flip Horizontal', 'codevz' ),
								'cz_social_fx_11'=> esc_html__( 'Flip Vertical', 'codevz' ),
							),
							'default_option' => esc_html__( '~ Disable ~', 'codevz'),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selective_refresh' => array(
								'selector' 			=> '.elms_row .cz_social',
								'settings' 			=> 'codevz_theme_options[social_hover_fx]',
								'render_callback' 	=> function() {
									return Codevz_Plus::social();
								},
								'container_inclusive' => true
							),
						),
						array(
							'id'            => 'social_color_mode',
							'type'          => 'select',
							'title'         => esc_html__( 'Color Mode', 'codevz' ),
							'options'       => array(
								'cz_social_colored' 		=> esc_html__( 'Brand Colors', 'codevz' ),
								'cz_social_colored_hover' 	=> esc_html__( 'Brand Colors on Hover', 'codevz' ),
								'cz_social_colored_bg' 		=> esc_html__( 'Brand Background', 'codevz' ),
								'cz_social_colored_bg_hover' => esc_html__( 'Brand Background on Hover', 'codevz' ),
							),
							'default_option' => esc_html__( '~ Default ~', 'codevz'),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selective_refresh' => array(
								'selector' 			=> '.elms_row .cz_social',
								'settings' 			=> 'codevz_theme_options[social_color_mode]',
								'render_callback' 	=> function() {
									return Codevz_Plus::social();
								},
								'container_inclusive' => true
							),
						),
						array(
							'id'            => 'social_tooltip',
							'type'          => 'select',
							'title'         => esc_html__( 'Tooltip', 'codevz' ),
							'help'          => esc_html__( 'StyleKit located in Theme Options > General > Colors & Styling', 'codevz' ),
							'options'       => array(
								'cz_tooltip cz_tooltip_up'    => esc_html__( 'Up', 'codevz' ),
								'cz_tooltip cz_tooltip_down'  => esc_html__( 'Down', 'codevz' ),
								'cz_tooltip cz_tooltip_right' => esc_html__( 'Right', 'codevz' ),
								'cz_tooltip cz_tooltip_left'  => esc_html__( 'Left', 'codevz' ),
							),
							'default_option' => esc_html__( '~ Default ~', 'codevz'),
							'setting_args'  => [ 'transport' => 'postMessage' ],
							'selective_refresh' => array(
								'selector'      => '.elms_row .cz_social',
								'settings'      => 'codevz_theme_options[social_tooltip]',
								'render_callback'   => function() {
									return Codevz_Plus::social();
								},
									'container_inclusive' => true
							),
						),

						array(
							'id' 			=> '_css_social',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Container', 'codevz' ),
							'button' 		=> esc_html__( 'Container', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'margin', 'border' ),
							'selector' 		=> '.elms_row .cz_social, .fixed_side .cz_social, #xtra-social-popup [class*="xtra-social-type-"]'
						),
						array(
							'id' 			=> '_css_social_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.elms_row .cz_social, .fixed_side .cz_social, #xtra-social-popup [class*="xtra-social-type-"]'
						),
						array(
							'id' 			=> '_css_social_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.elms_row .cz_social, .fixed_side .cz_social, #xtra-social-popup [class*="xtra-social-type-"]'
						),
						array(
							'id' 			=> '_css_social_a',
							'hover_id' 		=> '_css_social_a_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Icons', 'codevz' ),
							'button' 		=> esc_html__( 'Icons', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'margin', 'border' ),
							'selector' 		=> '.elms_row .cz_social a, .fixed_side .cz_social a, #xtra-social-popup [class*="xtra-social-type-"] a'
						),
						array(
							'id' 			=> '_css_social_a_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.elms_row .cz_social a, .fixed_side .cz_social a, #xtra-social-popup [class*="xtra-social-type-"] a'
						),
						array(
							'id' 			=> '_css_social_a_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.elms_row .cz_social a, .fixed_side .cz_social a, #xtra-social-popup [class*="xtra-social-type-"] a'
						),
					  array(
						'id' 				=> '_css_social_a_hover',
						'type' 				=> 'cz_sk_hidden',
						'setting_args' 		=> [ 'transport' => 'postMessage' ],
						'selector' 			=> '.elms_row .cz_social a:hover, .fixed_side .cz_social a:hover, #xtra-social-popup [class*="xtra-social-type-"] a:hover'
					  ),

					),
				),
				array(
					'name'   => 'header_1',
					'title'  => esc_html__( 'Header top bar', 'codevz' ),
					'fields' => self::row_options( 'header_1' )
				),
				array(
					'name'   => 'header_2',
					'title'  => esc_html__( 'Header', 'codevz' ),
					'fields' => self::row_options( 'header_2' )
				),
				array(
					'name'   => 'header_3',
					'title'  => esc_html__( 'Header bottom bar', 'codevz' ),
					'fields' => self::row_options( 'header_3' )
				),
				array(
					'name'   => 'header_5',
					'title'  => esc_html__( 'Sticky Header', 'codevz' ),
					'fields' => self::row_options( 'header_5' )
				),
				array(
					'name'   => 'mobile_header',
					'title'  => esc_html__( 'Mobile Header', 'codevz' ),
					'fields' => self::row_options( 'header_4' )
				),
				array(
					'name'   => 'mobile_fixed_navigation',
					'title'  => esc_html__( 'Mobile Fixed Navigation', 'codevz' ),
					'fields' => [

						array(
							'id'    	=> 'mobile_fixed_navigation',
							'type'  	=> 'switcher',
							'title' 	=> esc_html__( 'Mobile Fixed Nav', 'codevz' )
						),
						array(
							'id'              => 'mobile_fixed_navigation_items',
							'type'            => 'group',
							'title'           => esc_html__( 'Items', 'codevz' ),
							'button_title'    => esc_html__( 'Add', 'codevz' ),
							'accordion_title' => esc_html__( 'Add', 'codevz' ),
							'fields'          => array(
								array(
									'id'    	=> 'title',
									'type'  	=> 'text',
									'title' 	=> esc_html__( 'Title', 'codevz' )
								),
								array(
									'id'    	=> 'icon_type',
									'type'  	=> 'select',
									'title' 	=> esc_html__( 'Type', 'codevz' ),
									'options' 	=> [

										'icon' 		=> esc_html__( 'Icon', 'codevz' ),
										'image' 	=> esc_html__( 'Image', 'codevz' ),

									],
								),
								array(
									'id'    	=> 'icon',
									'type'  	=> 'icon',
									'title' 	=> esc_html__( 'Icon', 'codevz' ),
									'default' 	=> 'fas fa-home',
									'dependency' => array( 'icon_type', '!=', 'image' ),
								),
								array(
									'id'    	=> 'image',
									'type'  	=> 'upload',
									'title' 	=> esc_html__( 'Image', 'codevz' ),
									'preview' 	=> 1,
									'dependency' => array( 'icon_type', '==', 'image' ),
								),
								array(
									'id' 		=> 'image_size',
									'type' 		=> 'slider',
									'title' 	=> esc_html__( 'Size', 'codevz' ),
									'options'	=> array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 500 ),
									'dependency' => array( 'icon_type', '==', 'image' ),
								),
								array(
									'id'    	=> 'link',
									'type'  	=> 'text',
									'title' 	=> esc_html__( 'Link', 'codevz' )
								),
							),
							'setting_args' 	     => [ 'transport' => 'postMessage' ],
							'selective_refresh'  => array(
								'selector' 			=> '.xtra-fixed-mobile-nav',
								'settings' 			=> 'codevz_theme_options[mobile_fixed_navigation_items]',
								'render_callback'  	=> function() {
									return Codevz_Plus::mobile_fixed_navigation();
								},
								'container_inclusive' => true
							),
							'dependency' 	=> array( 'mobile_fixed_navigation', '!=', '' )
						),
						array(
							'id'    	=> 'mobile_fixed_navigation_title',
							'type'  	=> 'select',
							'title' 	=> esc_html__( 'Show Title', 'codevz' ),
							'options' 	=> [

								'' => esc_html__( '~ Disable ~', 'codevz' ),
								'xtra-fixed-mobile-nav-title-column' 	=> esc_html__( 'Block', 'codevz' ),
								'xtra-fixed-mobile-nav-title-row' 		=> esc_html__( 'Inline', 'codevz' ),

							],
							'setting_args' 	     => [ 'transport' => 'postMessage' ],
							'dependency' 	=> array( 'mobile_fixed_navigation', '!=', '' )
						),
						array(
							'id' 			=> '_css_mfn',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Container', 'codevz' ),
							'button' 		=> esc_html__( 'Container', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'border', 'box-shadow' ),
							'selector' 		=> '.xtra-fixed-mobile-nav',
							'dependency' 	=> array( 'mobile_fixed_navigation', '!=', '' )
						),
						array(
							'id' 			=> '_css_mfn_a',
							'hover_id' 		=> '_css_mfn_a_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Links', 'codevz' ),
							'button' 		=> esc_html__( 'Links', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
							'selector' 		=> '.xtra-fixed-mobile-nav a',
							'dependency' 	=> array( 'mobile_fixed_navigation', '!=', '' )
						),
						array(
							'id' 			=> '_css_mfn_a_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.xtra-fixed-mobile-nav a:hover,.xtra-fixed-mobile-nav .xtra-active',
							'dependency' 	=> array( 'mobile_fixed_navigation', '!=', '' )
						),
						array(
							'id' 			=> '_css_mfn_i',
							'hover_id' 		=> '_css_mfn_i_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Icons', 'codevz' ),
							'button' 		=> esc_html__( 'Icons', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
							'selector' 		=> '.xtra-fixed-mobile-nav a i, .xtra-fixed-mobile-nav a img',
							'dependency' 	=> array( 'mobile_fixed_navigation', '!=', '' )
						),
						array(
							'id' 			=> '_css_mfn_i_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.xtra-fixed-mobile-nav a:hover i, .xtra-fixed-mobile-nav a:hover img, .xtra-fixed-mobile-nav .xtra-active i, .xtra-fixed-mobile-nav .xtra-active img',
							'dependency' 	=> array( 'mobile_fixed_navigation', '!=', '' )
						),
						array(
							'id' 			=> '_css_mfn_title',
							'hover_id' 		=> '_css_mfn_title_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Title', 'codevz' ),
							'button' 		=> esc_html__( 'Title', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
							'selector' 		=> '.xtra-fixed-mobile-nav a span',
							'dependency' 	=> array( 'mobile_fixed_navigation', '!=', '' )
						),
						array(
							'id' 			=> '_css_mfn_title_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.xtra-fixed-mobile-nav a:hover span, .xtra-fixed-mobile-nav .xtra-active span',
							'dependency' 	=> array( 'mobile_fixed_navigation', '!=', '' )
						),

					]
				),
				array(
					'name'   => 'fixed_side_1',
					'title'  => esc_html__( 'Fixed Side', 'codevz' ),
					'fields' => self::row_options( 'fixed_side_1', array('top','middle','bottom') )
				),
				array(
					'name'   => 'title_br',
					'title'  => esc_html__( 'Title & Breadcrumbs', 'codevz' ),
					'fields' => self::title_options()
				),
				array(
					'name'   => 'header_more',
					'title'  => esc_html__( 'More', 'codevz' ),
					'fields' => array(
						array(
							'id' 			=> '_css_header_container',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Header Container', 'codevz' ),
							'button' 		=> esc_html__( 'Header Container', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'border' ),
							'selector' 		=> '.page_header'
						),
						array(
							'id' 			=> '_css_header_container_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.page_header'
						),
						array(
							'id' 			=> '_css_header_container_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.page_header'
						),
						array(
							'id'            => 'hidden_top_bar',
							'type'          => 'select',
							'title'         => esc_html__( 'Extra Panel', 'codevz' ),
							'options'       => Codevz_Plus::$array_pages
						),
						array(
							'id'            => 'hidden_top_bar_icon',
							'type'          => 'icon',
							'title'         => esc_html__( 'Icon', 'codevz' ),
							'dependency' 	=> array( 'hidden_top_bar', '!=', '' )
						),
						array(
							'id' 			=> '_css_hidden_top_bar',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Panel', 'codevz' ),
							'button' 		=> esc_html__( 'Panel', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'padding' ),
							'selector' 		=> '.hidden_top_bar',
							'dependency' 	=> array( 'hidden_top_bar', '!=', '' )
						),
						array(
							'id' 			=> '_css_hidden_top_bar_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.hidden_top_bar',
						),
						array(
							'id' 			=> '_css_hidden_top_bar_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.hidden_top_bar',
						),
						array(
							'id' 			=> '_css_hidden_top_bar_handle',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Icon', 'codevz' ),
							'button' 		=> esc_html__( 'Icon', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background' ),
							'selector' 		=> '.hidden_top_bar > i',
							'dependency' 	=> array( 'hidden_top_bar', '!=', '' )
						),
						array(
							'id' 			=> '_css_hidden_top_bar_handle_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.hidden_top_bar > i',
						),
						array(
							'id' 			=> '_css_hidden_top_bar_handle_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.hidden_top_bar > i',
						),
					),
				),

			),
		);

		$options[]   = array(
			'name' 		=> 'footer',
			'title' 	=> esc_html__( 'Footer', 'codevz' ),
			'sections' => array(

				array(
					'name'   => 'footer_elementor',
					'title'  => esc_html__( 'Custom Footer Template', 'codevz' ),
					'fields' => array(
						array(
							'id'    	=> 'footer_elementor',
							'type'  	=> 'select',
							'title' 	=> esc_html__( 'Select Footer', 'codevz' ),
							'options' 	=> Codevz_Plus::$array_pages
						),
					),
				),
				array(
					'name'   => 'footer_1',
					'title'  => esc_html__( 'Footer Top Bar', 'codevz' ),
					'fields' => self::row_options( 'footer_1' )
				),
				array(
					'name'   => 'footer_widgets',
					'title'  => esc_html__( 'Footer Widgets', 'codevz' ),
					'fields' => array(
						array(
							'id' 	=> 'footer_layout',
							'type' 	=> 'select',
							'title' => esc_html__( 'Columns', 'codevz' ),
							'help' 	=> esc_html__( 'Manage your footer widgets from Theme Options > Widgets', 'codevz' ),
							'options' => array(
								'' 					=> esc_html__( '~ Select ~', 'codevz' ),
								's12'				=> '1/1',
								's6,s6'				=> '1/2 1/2',
								's4,s8'				=> '1/3 2/3',
								's8,s4'				=> '2/3 1/3',
								's3,s9'				=> '1/4 3/4',
								's9,s3'				=> '3/4 1/4',
								's4,s4,s4'			=> '1/3 1/3 1/3',
								's3,s6,s3'			=> '1/4 2/4 1/4',
								's3,s3,s6'			=> '1/4 1/4 2/4',
								's6,s3,s3'			=> '2/4 1/4 1/4',
								's2,s2,s8'			=> '1/6 1/6 4/6',
								's2,s8,s2'			=> '1/6 4/6 1/6',
								's8,s2,s2'			=> '4/6 1/6 1/6',
								's3,s3,s3,s3'		=> '1/4 1/4 1/4 1/4',
								's55,s55,s55,s55,s55' => '1/5 1/5 1/5 1/5 1/5',
								's6,s2,s2,s2'		=> '3/6 1/6 1/6 1/6',
								's2,s2,s2,s6'		=> '1/6 1/6 1/6 3/6',
								's2,s2,s2,s2,s4'	=> '1/6 1/6 1/6 1/6 2/6',
								's4,s2,s2,s2,s2'	=> '2/6 1/6 1/6 1/6 1/6',
								's2,s2,s4,s2,s2'	=> '1/6 1/6 2/6 1/6 1/6',
								's2,s2,s2,s2,s2,s2'	=> '1/6 1/6 1/6 1/6 1/6 1/6',
							),
						),
						array(
							'id' 			=> '_css_footer',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Container', 'codevz' ),
							'button' 		=> esc_html__( 'Container', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '.cz_middle_footer',
							'dependency' 	=> array( 'footer_layout', '!=', '' )
						),
						array(
							'id' 			=> '_css_footer_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_middle_footer',
						),
						array(
							'id' 			=> '_css_footer_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_middle_footer',
						),
						array(
							'id' 			=> '_css_footer_row',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Row Inner', 'codevz' ),
							'button' 		=> esc_html__( 'Row Inner', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'width', 'background', 'border' ),
							'selector' 		=> '.cz_middle_footer > .row',
							'dependency' 	=> array( 'footer_layout', '!=', '' )
						),
						array(
							'id' 			=> '_css_footer_row_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_middle_footer > .row',
						),
						array(
							'id' 			=> '_css_footer_row_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_middle_footer > .row',
						),
						array(
							'id' 			=> '_css_footer_widget',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Widgets', 'codevz' ),
							'button' 		=> esc_html__( 'Widgets', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'border' ),
							'selector' 		=> '.footer_widget',
							'dependency' 	=> array( 'footer_layout', '!=', '' )
						),
						array(
							'id' 			=> '_css_footer_widget_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.footer_widget',
						),
						array(
							'id' 			=> '_css_footer_widget_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.footer_widget',
						),
						array(
							'id' 			=> '_css_footer_widget_headlines',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Titles', 'codevz' ),
							'button' 		=> esc_html__( 'Titles', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'line-height', 'padding', 'border' ),
							'selector' 		=> '.footer_widget > h4',
							'dependency' 	=> array( 'footer_layout', '!=', '' )
						),
						array(
							'id' 			=> '_css_footer_widget_headlines_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.footer_widget > h4',
						),
						array(
							'id' 			=> '_css_footer_widget_headlines_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.footer_widget > h4',
						),
						array(
							'id' 			=> '_css_footer_widget_headlines_before',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Shape 1', 'codevz' ),
							'button' 		=> esc_html__( 'Shape 1', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'margin', 'width', 'height', 'border', 'top', 'left', 'bottom', 'right' ),
							'selector' 		=> '.footer_widget > h4:before',
							'dependency' 	=> array( 'footer_layout', '!=', '' )
						),
						array(
							'id' 			=> '_css_footer_widget_headlines_before_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.footer_widget > h4:before',
						),
						array(
							'id' 			=> '_css_footer_widget_headlines_before_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.footer_widget > h4:before',
						),
						array(
							'id' 			=> '_css_footer_widget_headlines_after',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Shape 2', 'codevz' ),
							'button' 		=> esc_html__( 'Shape 2', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'margin', 'width', 'height', 'border', 'top', 'left', 'bottom', 'right' ),
							'selector' 		=> '.footer_widget > h4:after',
							'dependency' 	=> array( 'footer_layout', '!=', '' )
						),
						array(
							'id' 			=> '_css_footer_widget_headlines_after_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.footer_widget > h4:after',
						),
						array(
							'id' 			=> '_css_footer_widget_headlines_after_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.footer_widget > h4:after',
						),
						array(
							'id' 			=> '_css_footer_a',
							'hover_id' 		=> '_css_footer_a_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Links', 'codevz' ),
							'button' 		=> esc_html__( 'Links', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-style' ),
							'selector' 		=> '.cz_middle_footer a',
							'dependency' 	=> array( 'footer_layout', '!=', '' )
						),
						array(
							'id' 			=> '_css_footer_a_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_middle_footer a:hover',
						),
					),
				),
				array(
					'name'   => 'footer_2',
					'title'  => esc_html__( 'Footer Bottom Bar', 'codevz' ),
					'fields' => self::row_options( 'footer_2' )
				),
				array(
					'name'   => 'footer_more',
					'title'  => esc_html__( 'More', 'codevz' ),
					'fields' => array(
						array(
							'id' 			=> 'fixed_footer',
							'type' 			=> 'switcher',
							'title' 		=> esc_html__( 'Fixed Footer', 'codevz' ),
							'help'			=> esc_html__( 'Body background color is required for fixed footer. Go to General > Colors > Body', 'codevz' ),
						),
						array(
							'id'    		=> 'backtotop',
							'type'  		=> 'icon',
							'title' 		=> esc_html__( 'Back to top', 'codevz' ),
							'default'		=> 'fa fa-angle-up',
							'setting_args' 	=> [ 'transport' => 'postMessage' ]
						),
						array(
							'id' 			=> 'cf7_beside_backtotop',
							'type' 			=> 'select',
							'title' 		=> esc_html__( 'Contact Box', 'codevz' ),
							'help' 			=> esc_html__( 'Select page that contains contact form element.', 'codevz' ),
							'options'       => wp_parse_args( Codevz_Plus::$array_pages, [
								'' 				=> esc_html__( '~ Disable ~', 'codevz' ),
								'link' 			=> esc_html__( '~ Direct Link ~', 'codevz' )
							]),
						),
						array(
							'id'    		=> 'cf7_beside_backtotop_link',
							'type'  		=> 'text',
							'title' 		=> esc_html__( 'Direct Link', 'codevz' ),
							'dependency' 	=> array( 'cf7_beside_backtotop', '==', 'link' )
						),
						array(
							'id'    		=> 'cf7_beside_backtotop_icon',
							'type'  		=> 'icon',
							'title' 		=> esc_html__( 'Contact Icon', 'codevz' ),
							'default'		=> 'fa fa-envelope-o',
							'dependency' => array( 'cf7_beside_backtotop', '!=', '' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
						),
						array(
							'id' 			=> '_css_overal_footer',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Footer', 'codevz' ),
							'button' 		=> esc_html__( 'Footer', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '.page_footer'
						),
						array(
							'id' 			=> '_css_overal_footer_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.page_footer'
						),
						array(
							'id' 			=> '_css_overal_footer_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.page_footer'
						),
						array(
							'id' 			=> '_css_backtotop',
							'hover_id' 		=> '_css_backtotop_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Back to top', 'codevz' ),
							'button' 		=> esc_html__( 'Back to top', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
							'selector' 		=> 'i.backtotop'
						),
						array(
							'id' 			=> '_css_backtotop_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'i.backtotop'
						),
						array(
							'id' 			=> '_css_backtotop_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'i.backtotop'
						),
						array(
							'id' 			=> '_css_backtotop_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'i.backtotop:hover'
						),
						array(
							'id' 			=> '_css_cf7_beside_backtotop_container',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Contact', 'codevz' ),
							'button' 		=> esc_html__( 'Contact', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'border' ),
							'selector' 		=> 'div.fixed_contact',
							'dependency' 	=> array( 'cf7_beside_backtotop', '!=', '' ),
						),
						array(
							'id' 			=> '_css_cf7_beside_backtotop',
							'hover_id' 		=> '_css_cf7_beside_backtotop_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Contact Icon', 'codevz' ),
							'button' 		=> esc_html__( 'Contact Icon', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
							'selector' 		=> 'i.fixed_contact',
							'dependency' 	=> array( 'cf7_beside_backtotop', '!=', '' ),
						),
						array(
							'id' 			=> '_css_cf7_beside_backtotop_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> 'i.fixed_contact:hover',
						),
					),
				),
			),
		);

		$options[]   = array(
			'name' 		=> 'posts',
			'title' 	=> esc_html__( 'Blog', 'codevz' ),
			'sections' => array(

				array(
					'name'   => 'blog_settings',
					'title'  => esc_html__( 'Blog Settings', 'codevz' ),
					'fields' => array(

						array(
							'id' 			=> 'layout_post',
							'type' 			=> 'codevz_image_select',
							'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
							'help'  		=> esc_html__( 'Posts Archive and Single', 'codevz' ),
							'options' 		=> [
								'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
								'ws' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
								'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
								'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
								'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
								'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
								'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
								'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
								'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
								'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
								'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
								'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
								'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
								'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
							],
							'default' 		=> 'right',
							'attributes' 	=> [ 'data-depend-id' => 'layout_post' ]
						),
						array(
							'id' 			=> 'template_style',
							'type' 			=> 'codevz_image_select',
							'title' 		=> esc_html__( 'Template', 'codevz' ),
							'help'  		=> esc_html__( 'Posts Archive', 'codevz' ),
							'options' 		=> [
								'1' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 1' 	, Codevz_Plus::$url . 'assets/img/posts-1.png' ],
								'2' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 2' 	, Codevz_Plus::$url . 'assets/img/posts-2.png' ],
								'6' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 6' 	, Codevz_Plus::$url . 'assets/img/posts-1-2.png' ],
								'3' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 3' 	, Codevz_Plus::$url . 'assets/img/posts-3.png' ],
								'4' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 4' 	, Codevz_Plus::$url . 'assets/img/posts-4.png' ],
								'5' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 5' 	, Codevz_Plus::$url . 'assets/img/posts-5.png' ],
								'7' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 7' 		, Codevz_Plus::$url . 'assets/img/posts-7.png' ],
								'8' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 8' 		, Codevz_Plus::$url . 'assets/img/posts-8.png' ],
								'9' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 9' 		, Codevz_Plus::$url . 'assets/img/posts-9.png' ],
								'10' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 10' 	, Codevz_Plus::$url . 'assets/img/posts-10.png' ],
								'11' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 11' 	, Codevz_Plus::$url . 'assets/img/posts-11.png' ],
								'12' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 12' 	, Codevz_Plus::$url . 'assets/img/posts-12.png' ],
								'13' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 13' 	, Codevz_Plus::$url . 'assets/img/posts-13.png' ],
								'14' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 14' 	, Codevz_Plus::$url . 'assets/img/posts-14.png' ],
								'x' 			=> [ esc_html__( 'Custom Template', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/posts-x.png' ],
							],
							'default' 		=> '1',
							'attributes' 	=> [ 'data-depend-id' => 'template_style' ]
						),
						array(
							'id'    		=> 'template_post',
							'type'   		=> 'select',
							'title'   		=> esc_html__( 'Custom Page', 'codevz' ),
							'options'   	=> Codevz_Plus::$array_pages,
							'dependency'  	=> array( 'template_style', '==', 'x' ),
						),
						array(
							'id'    	=> '2x_height_image',
							'type'  	=> 'switcher',
							'title' 	=> esc_html__( '2x Height Image', 'codevz' ),
							'dependency'  => array( 'template_style|template_style', '!=|!=', 'x|3' )
						),
						array(
							'id'          => 'hover_icon_icon_post',
							'type'        => 'icon',
							'title'       => esc_html__('Hover Icon', 'codevz'),
							'default'	  => 'fa czico-109-link-symbol-1',
							'dependency'  	=> array( 'template_style', '!=', 'x' ),
						),
						array(
							'id'          => 'default_svg_post',
							'type'        => 'switcher',
							'title'       => esc_html__('Default Image', 'codevz'),
							'dependency'  	=> array( 'template_style', '!=', 'x' ),
						),
						array(
							'id'    	=> 'post_excerpt',
							'type'  	=> 'slider',
							'title'   	=> esc_html__( 'Excerpt', 'codevz' ),
							'help' 	  	=> esc_html__( 'If you want show full content set -1', 'codevz' ),
							'options'	=> array( 'unit' => '', 'step' => 1, 'min' => -1, 'max' => 50 ),
							'default' 	=> '20',
							'dependency'  => array( 'template_style|template_style|template_style|template_style', '!=|!=|!=|!=', 'x|12|13|14' )
						),
						array(
							'id'    	=> 'post_excerpt_type',
							'type'  	=> 'select',
							'title'   	=> esc_html__( 'Excerpt By', 'codevz' ),
							'options'	=> [
								'' 			=> esc_html__( 'Words', 'codevz' ),
								'2' 		=> esc_html__( 'Characters', 'codevz' ),
							],
							'dependency'  => array( 'template_style|template_style|template_style|template_style', '!=|!=|!=|!=', 'x|12|13|14' )
						),
						array(
							'id'    	=> 'post_excerpt_dots',
							'type'  	=> 'text',
							'title'   	=> esc_html__( 'Excerpt Dots', 'codevz' ),
							'default' 	=> ' ... ',
							'dependency'  => array( 'template_style|template_style|template_style|template_style', '!=|!=|!=|!=', 'x|12|13|14' )
						),
						array(
							'id'          => 'readmore_icon',
							'type'        => 'icon',
							'title'       => esc_html__( 'Read More', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'default'	  => 'fa fa-angle-right',
							'dependency'  => array( 'template_style|template_style|template_style|template_style|post_excerpt', '!=|!=|!=|!=|!=', 'x|12|13|14|-1' )
						),
						array(
							'id'          	=> 'readmore',
							'type'        	=> 'text',
							'title'       	=> esc_html__( 'Read More', 'codevz' ),
							'default'	    => 'Read More',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'dependency'  	=> [ 'post_excerpt|template_style', '!=|!=', '-1|x' ]
						),
						array(
							'id'          	=> 'not_found',
							'type'        	=> 'text',
							'title'       	=> esc_html__( 'Not found', 'codevz' ),
							'default'	  	=> esc_html__( 'Not found!', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ]
						),
					),
				),

				array(
					'name'   => 'blog_styles',
					'title'  => esc_html__( 'Blog Styling', 'codevz' ),
					'fields' => array(
						array(
							'id' 			=> '_css_sticky_post',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Sticky Post', 'codevz' ),
							'button' 		=> esc_html__( 'Sticky Post', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '.cz_default_loop.sticky > div',
							'dependency' 	=> [ 'xxx', '==', 'xxx' ]
						),
						array(
							'id' 			=> '_css_sticky_post_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_default_loop.sticky > div',
						),
						array(
							'id' 			=> '_css_overall_post',
							'hover_id' 		=> '_css_overall_post_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Posts', 'codevz' ),
							'button' 		=> esc_html__( 'Posts', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '.cz-cpt-post .cz_default_loop > div',
						),
						array(
							'id' 			=> '_css_overall_post_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop > div',
						),
						array(
							'id' 			=> '_css_overall_post_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop > div',
						),
						array(
							'id' 			=> '_css_overall_post_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop:hover > div',
						),
						array(
							'id' 			=> '_css_post_hover_icon',
							'hover_id' 		=> '_css_post_hover_icon_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Icon', 'codevz' ),
							'button' 		=> esc_html__( 'Icon', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'border' ),
							'selector' 		=> '.cz-cpt-post article .cz_post_icon',
						),
						array(
							'id' 			=> '_css_post_hover_icon_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post article .cz_post_icon:hover',
						),
						array(
							'id' 			=> '_css_post_image',
							'hover_id' 		=> '_css_post_image_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Image', 'codevz' ),
							'button' 		=> esc_html__( 'Image', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'opacity', 'background', 'padding', 'border' ),
							'selector' 		=> '.cz-cpt-post .cz_post_image, .cz-cpt-post .cz_post_svg',
						),
						array(
							'id' 			=> '_css_post_image_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_post_image, .cz-cpt-post .cz_post_svg',
						),
						array(
							'id' 			=> '_css_post_image_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_post_image, .cz-cpt-post .cz_post_svg',
						),
						array(
							'id' 			=> '_css_post_image_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post article:hover .cz_post_image,.cz-cpt-post article:hover .cz_post_svg',
						),
						array(
							'id' 			=> '_css_post_con',
							'hover_id' 		=> '_css_post_con_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Content', 'codevz' ),
							'button' 		=> esc_html__( 'Content', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'padding', 'border' ),
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_con',
						),
						array(
							'id' 			=> '_css_post_con_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_con',
						),
						array(
							'id' 			=> '_css_post_con_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_con',
						),
						array(
							'id' 			=> '_css_post_con_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop article:hover .cz_post_con',
						),
						array(
							'id' 			=> '_css_post_title',
							'hover_id' 		=> '_css_post_title_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Title', 'codevz' ),
							'button' 		=> esc_html__( 'Title', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'line-height', 'padding', 'border' ),
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_title h3',
						),
						array(
							'id' 			=> '_css_post_title_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_title h3',
						),
						array(
							'id' 			=> '_css_post_title_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_title h3',
						),
						array(
							'id' 			=> '_css_post_title_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_title h3:hover',
						),
						array(
							'id' 			=> '_css_post_meta_overall',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Meta', 'codevz' ),
							'button' 		=> esc_html__( 'Meta', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'float', 'background', 'padding', 'border' ),
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_meta',
						),
						array(
							'id' 			=> '_css_post_meta_overall_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_meta',
						),
						array(
							'id' 			=> '_css_post_meta_overall_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_meta',
						),
						array(
							'id' 			=> '_css_post_avatar',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Avatar', 'codevz' ),
							'button' 		=> esc_html__( 'Avatar', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'width', 'height', 'border' ),
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_author_avatar img',
						),
						array(
							'id' 			=> '_css_post_avatar_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_author_avatar img',
						),
						array(
							'id' 			=> '_css_post_avatar_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_author_avatar img',
						),
						array(
							'id' 			=> '_css_post_author',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Author', 'codevz' ),
							'button' 		=> esc_html__( 'Author', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'font-weight' ),
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_author_name',
						),
						array(
							'id' 			=> '_css_post_author_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_author_name',
						),
						array(
							'id' 			=> '_css_post_author_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_author_name',
						),
						array(
							'id' 			=> '_css_post_date',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Date', 'codevz' ),
							'button' 		=> esc_html__( 'Date', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'font-style' ),
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_date',
						),
						array(
							'id' 			=> '_css_post_date_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_date',
						),
						array(
							'id' 			=> '_css_post_date_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_date',
						),
						array(
							'id' 			=> '_css_post_excerpt',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Excerpt', 'codevz' ),
							'button' 		=> esc_html__( 'Excerpt', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'text-align', 'color', 'font-size', 'line-height' ),
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_excerpt',
						),
						array(
							'id' 			=> '_css_post_excerpt_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_excerpt',
						),
						array(
							'id' 			=> '_css_post_excerpt_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_default_loop .cz_post_excerpt',
						),

						array(
							'id' 			=> '_css_readmore',
							'hover_id' 		=> '_css_readmore_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Read more', 'codevz' ),
							'button' 		=> esc_html__( 'Read more', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'float', 'color', 'background', 'font-size', 'border' ),
							'selector' 		=> '.cz-cpt-post .cz_readmore, .cz-cpt-post .more-link'
						),
						array(
							'id' 			=> '_css_readmore_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_readmore, .cz-cpt-post .more-link'
						),
						array(
							'id' 			=> '_css_readmore_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_readmore, .cz-cpt-post .more-link'
						),
						array(
							'id' 			=> '_css_readmore_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_readmore:hover, .cz-cpt-post .more-link:hover'
						),
						array(
							'id' 			=> '_css_readmore_i',
							'hover_id' 		=> '_css_readmore_i_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Icon', 'codevz' ),
							'button' 		=> esc_html__( 'Icon', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size' ),
							'selector' 		=> '.cz-cpt-post .cz_readmore i, .cz-cpt-post .more-link i'
						),
						array(
							'id' 			=> '_css_readmore_i_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz-cpt-post .cz_readmore:hover i, .cz-cpt-post .more-link:hover i',
						),
						array(
							'id' 			=> '_css_pagination_li',
							'hover_id' 		=> '_css_pagination_li_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Pagination', 'codevz' ),
							'button' 		=> esc_html__( 'Pagination', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
							'selector' 		=> '.pagination a, .pagination > b, .pagination span, .page-numbers a, .page-numbers span, .woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span'
						),
						array(
							'id' 			=> '_css_pagination_li_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.pagination a, .pagination > b, .pagination span, .page-numbers a, .page-numbers span, .woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span'
						),
						array(
							'id' 			=> '_css_pagination_li_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.pagination a, .pagination > b, .pagination span, .page-numbers a, .page-numbers span, .woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span'
						),
						array(
							'id' 			=> '_css_pagination_li_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.pagination .current, .pagination > b, .pagination a:hover, .page-numbers .current, .page-numbers a:hover, .pagination .next:hover, .pagination .prev:hover, .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current'
						),

					),
				),

				array(
					'name'   => 'single_settings',
					'title'  => esc_html__( 'Single Settings', 'codevz' ),
					'fields' => array(
						array(
							'id' 			=> 'layout_single_post',
							'type' 			=> 'codevz_image_select',
							'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
							'help'  		=> esc_html__( 'Single Posts', 'codevz' ),
							'options' 		=> [
								'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
								'ws' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
								'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
								'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
								'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
								'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
								'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
								'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
								'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
								'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
								'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
								'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
								'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
								'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
							],
							'default' 		=> '1'
						),
						array(
							'id' 		=> 'meta_data_post',
							'type' 		=> 'checkbox',
							'title' 	=> esc_html__( 'Features', 'codevz' ),
							'options' 	=> array(
								'image'		=> esc_html__( 'Post Image', 'codevz' ),
								'author'	=> esc_html__( 'Author', 'codevz' ),
								'date'		=> esc_html__( 'Date', 'codevz' ),
								'cats'		=> esc_html__( 'Categories', 'codevz' ),
								'tags'		=> esc_html__( 'Tags', 'codevz' ),
								'next_prev' => esc_html__( 'Next Prev Posts', 'codevz' ),
							),
							'default' 	=> array( 'image','date','author','cats','tags','author_box', 'next_prev' )
						),
						array(
							'id' 			=> 'related_post_col',
							'type' 			=> 'codevz_image_select',
							'title' 		=> esc_html__( 'Related Columns', 'codevz' ),
							'options' 		=> [
								's6' 			=> [ '2 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-2.png' ],
								's4' 			=> [ '3 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-3.png' ],
								's3' 			=> [ '4 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-4.png' ],
							],
							'default' 		=> 's4',
							'dependency'  => array( 'related_post_ppp', '!=', '0' ),
						),
						array(
							'id'    	=> 'related_post_ppp',
							'type'  	=> 'slider',
							'title' 	=> esc_html__( 'Related Posts', 'codevz' ),
							'options'	=> array( 'unit' => '', 'step' => 1, 'min' => -1, 'max' => 100 ),
							'default' 	=> '3'
						),
						array(
							'id'          	=> 'related_posts_post',
							'type'        	=> 'text',
							'title'       	=> esc_html__('Related Title', 'codevz'),
							'default'		=> 'Related Posts ...',
							'setting_args' 	=> array('transport' => 'postMessage'),
							'dependency'  	=> array( 'related_post_ppp', '!=', '0' ),
						),
						array(
							'id' 			=> 'prev_post',
							'type' 			=> 'text',
							'title' 		=> esc_html__( 'Prev Surtitle', 'codevz' ),
							'default' 		=> 'Previous',
							'setting_args' 	=> array('transport' => 'postMessage')
						),
						array(
							'id' 			=> 'next_post',
							'type' 			=> 'text',
							'title' 		=> esc_html__( 'Next Surtitle', 'codevz' ),
							'default' 		=> 'Next',
							'setting_args' 	=> array('transport' => 'postMessage')
						),
						array(
							'id'    		=> 'no_comment',
							'type'  		=> 'text',
							'title' 		=> esc_html__( 'No comment', 'codevz' ),
							'default' 		=> 'No comment',
							'setting_args' 	=> [ 'transport' => 'postMessage' ]
						),
						array(
							'id'    		=> 'comment',
							'type'  		=> 'text',
							'title' 		=> esc_html__( 'Comment', 'codevz' ),
							'default' 		=> 'Comment',
							'setting_args' 	=> [ 'transport' => 'postMessage' ]
						),
						array(
							'id'    		=> 'comments',
							'type'  		=> 'text',
							'title' 		=> esc_html__( 'Comments', 'codevz' ),
							'default' 		=> 'Comments',
							'setting_args' 	=> [ 'transport' => 'postMessage' ]
						),
						array(
							'id'          	=> 'cm_disabled',
							'type'        	=> 'textarea',
							'title'       	=> esc_html__( 'Comments disable message', 'codevz' ),
							'default'	  	=> esc_html__( 'Comments are disabled.', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ]
						),
					),
				),

				array(
					'name'   => 'single_styles',
					'title'  => esc_html__( 'Single Styling', 'codevz' ),
					'fields' => array(
						array(
							'id' 			=> '_css_single_con',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Container', 'codevz' ),
							'button' 		=> esc_html__( 'Container', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'padding', 'border' ),
							'selector' 		=> '.single_con',
						),
						array(
							'id' 			=> '_css_single_con_tablet','type' => 'cz_sk_hidden','setting_args' => [ 'transport' => 'postMessage' ],
							'selector' 		=> '.single_con',
						),
						array(
							'id' 			=> '_css_single_con_mobile','type' => 'cz_sk_hidden','setting_args' => [ 'transport' => 'postMessage' ],
							'selector' 		=> '.single_con',
						),
						array(
							'id' 			=> '_css_single_title',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Title', 'codevz' ),
							'button' 		=> esc_html__( 'Title', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'line-height' ),
							'selector' 		=> '.single .content .xtra-post-title',
						),
						array(
							'id' 			=> '_css_single_title_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.single .content .xtra-post-title',
						),
						array(
							'id' 			=> '_css_single_title_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.single .content .xtra-post-title',
						),
						array(
							'id' 			=> '_css_single_title_date',
							'hover_id' 		=> '_css_single_title_date_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Date', 'codevz' ),
							'button' 		=> esc_html__( 'Date', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
							'selector' 		=> '.single .xtra-post-title-date a',
						),
						array(
							'id' 			=> '_css_single_title_date_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.single .xtra-post-title-date a:hover',
						),
						array(
							'id' 			=> '_css_single_fi',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Image', 'codevz' ),
							'button' 		=> esc_html__( 'Image', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'margin', 'border' ),
							'selector' 		=> '.single_con .cz_single_fi img',
						),
						array(
							'id' 			=> '_css_single_fi_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.single_con .cz_single_fi img',
						),
						array(
							'id' 			=> '_css_single_fi_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.single_con .cz_single_fi img',
						),
						array(
							'id' 			=> '_css_tags_categories',
							'hover_id' 		=> '_css_tags_categories_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Meta', 'codevz' ),
							'button' 		=> esc_html__( 'Meta', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
							'selector' 		=> '.tagcloud a, .cz_post_cat a'
						),
						array(
							'id' 			=> '_css_tags_categories_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.tagcloud a, .cz_post_cat a'
						),
						array(
							'id' 			=> '_css_tags_categories_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.tagcloud a, .cz_post_cat a'
						),
						array(
							'id' 			=> '_css_tags_categories_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.tagcloud a:hover, .cz_post_cat a:hover'
						),
						array(
							'id' 			=> '_css_tags_categories_icon',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Meta Icon', 'codevz' ),
							'button' 		=> esc_html__( 'Meta Icon', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
							'selector' 		=> '.single_con .tagcloud a:first-child, .single_con .cz_post_cat a:first-child'
						),
						array(
							'id' 			=> '_css_tags_categories_icon_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.single_con .tagcloud a:first-child, .single_con .cz_post_cat a:first-child'
						),
						array(
							'id' 			=> '_css_tags_categories_icon_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.single_con .tagcloud a:first-child, .single_con .cz_post_cat a:first-child'
						),
						array(
							'type'    => 'notice',
							'class'   => 'info xtra-notice',
							'content' => esc_html__( 'Next & Previous Posts', 'codevz' )
						),
						array(
							'id' 			=> '_css_next_prev_con',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Container', 'codevz' ),
							'button' 		=> esc_html__( 'Container', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '.next_prev'
						),
						array(
							'id' 			=> '_css_next_prev_con_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev'
						),
						array(
							'id' 			=> '_css_next_prev_con_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev'
						),
						array(
							'id' 			=> '_css_next_prev_icons',
							'hover_id' 		=> '_css_next_prev_icons_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Icons', 'codevz' ),
							'button' 		=> esc_html__( 'Icons', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'border' ),
							'selector' 		=> '.next_prev .previous i,.next_prev .next i'
						),
						array(
							'id' 			=> '_css_next_prev_icons_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev .previous i,.next_prev .next i'
						),
						array(
							'id' 			=> '_css_next_prev_icons_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev .previous i,.next_prev .next i'
						),
						array(
							'id' 			=> '_css_next_prev_icons_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev .previous:hover i,.next_prev .next:hover i'
						),
						array(
							'id' 			=> '_css_next_prev_titles',
							'hover_id' 		=> '_css_next_prev_titles_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Titles', 'codevz' ),
							'button' 		=> esc_html__( 'Titles', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'line-height' ),
							'selector' 		=> '.next_prev h4'
						),
						array(
							'id' 			=> '_css_next_prev_titles_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev h4'
						),
						array(
							'id' 			=> '_css_next_prev_titles_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev h4'
						),
						array(
							'id' 			=> '_css_next_prev_titles_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev li:hover h4'
						),
						array(
							'id' 			=> '_css_next_prev_surtitle',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Sur Titles', 'codevz' ),
							'button' 		=> esc_html__( 'Sur Titles', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'border' ),
							'selector' 		=> '.next_prev h4 small'
						),
						array(
							'id' 			=> '_css_next_prev_surtitle_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev h4 small'
						),
						array(
							'id' 			=> '_css_next_prev_surtitle_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.next_prev h4 small'
						),

						array(
							'type'    => 'notice',
							'class'   => 'info xtra-notice',
							'content' => esc_html__( 'Related Posts & Comments', 'codevz' )
						),
						array(
							'id' 			=> '_css_related_posts_con',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Container', 'codevz' ),
							'button' 		=> esc_html__( 'Container', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '.xtra-comments,.content.cz_related_posts,.cz_author_box,.related.products,.upsells.products,.up-sells.products'
						),
						array(
							'id' 			=> '_css_related_posts_con_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.xtra-comments,.content.cz_related_posts,.cz_author_box,.related.products,.upsells.products,.up-sells.products'
						),
						array(
							'id' 			=> '_css_related_posts_con_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.xtra-comments,.content.cz_related_posts,.cz_author_box,.related.products,.upsells.products,.up-sells.products'
						),
						array(
							'id' 			=> '_css_related_posts_sec_title',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Title', 'codevz' ),
							'button' 		=> esc_html__( 'Title', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'border' ),
							'selector' 		=> '#comments > h3,.content.cz_related_posts > h4,.cz_author_box h4,.related.products > h2,.upsells.products > h2,.up-sells.products > h2'
						),
						array(
							'id' 			=> '_css_related_posts_sec_title_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '#comments > h3,.content.cz_related_posts > h4,.cz_author_box h4,.related.products > h2,.upsells.products > h2,.up-sells.products > h2'
						),
						array(
							'id' 			=> '_css_related_posts_sec_title_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '#comments > h3,.content.cz_related_posts > h4,.cz_author_box h4,.related.products > h2,.upsells.products > h2,.up-sells.products > h2'
						),
						array(
							'id' 			=> '_css_related_posts_sec_title_before',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Title Shape 1', 'codevz' ),
							'button' 		=> esc_html__( 'Title Shape 1', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'margin', 'width', 'height', 'border', 'top', 'left', 'bottom', 'right' ),
							'selector' 		=> '#comments > h3:before,.content.cz_related_posts > h4:before,.cz_author_box h4:before,.related.products > h2:before,.upsells.products > h2:before,.up-sells.products > h2:before'
						),
						array(
							'id' 			=> '_css_related_posts_sec_title_before_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '#comments > h3:before,.content.cz_related_posts > h4:before,.cz_author_box h4:before,.related.products > h2:before,.upsells.products > h2:before,.up-sells.products > h2:before'
						),
						array(
							'id' 			=> '_css_related_posts_sec_title_before_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '#comments > h3:before,.content.cz_related_posts > h4:before,.cz_author_box h4:before,.related.products > h2:before,.upsells.products > h2:before,.up-sells.products > h2:before'
						),
						array(
							'id' 			=> '_css_related_posts_sec_title_after',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Title Shape 2', 'codevz' ),
							'button' 		=> esc_html__( 'Title Shape 2', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'font-size', 'text-align', 'border' ),
							'selector' 		=> '#comments > h3:after,.content.cz_related_posts > h4:after,.cz_author_box h4:after,.related.products > h2:after,.upsells.products > h2:after,.up-sells.products > h2:after'
						),
						array(
							'id' 			=> '_css_related_posts_sec_title_after_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '#comments > h3:after,.content.cz_related_posts > h4:after,.cz_author_box h4:after,.related.products > h2:after,.upsells.products > h2:after,.up-sells.products > h2:after'
						),
						array(
							'id' 			=> '_css_related_posts_sec_title_after_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '#comments > h3:after,.content.cz_related_posts > h4:after,.cz_author_box h4:after,.related.products > h2:after,.upsells.products > h2:after,.up-sells.products > h2:after'
						),
						array(
							'id' 			=> '_css_related_posts',
							'hover_id' 		=> '_css_related_posts_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Posts', 'codevz' ),
							'button' 		=> esc_html__( 'Posts', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '.cz_related_posts .cz_related_post > div'
						),
						array(
							'id' => '_css_related_posts_tablet',
							'type' => 'cz_sk_hidden',
							'setting_args' => [ 'transport' => 'postMessage' ],
							'selector' => '.cz_related_posts .cz_related_post > div'
						),
						array(
							'id' => '_css_related_posts_mobile',
							'type' => 'cz_sk_hidden',
							'setting_args' => [ 'transport' => 'postMessage' ],
							'selector' => '.cz_related_posts .cz_related_post > div'
						),
						array(
							'id' 			=> '_css_related_posts_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_related_posts .cz_related_post:hover > div'
						),
						array(
							'id'      	=> '_css_related_posts_img',
							'hover_id' 	=> '_css_related_posts_img_hover',
							'type'      => 'cz_sk',
							'title'    => esc_html__( 'Images', 'codevz' ),
							'button'    => esc_html__( 'Images', 'codevz' ),
							'setting_args'  => [ 'transport' => 'postMessage' ],
							'settings'    => array( 'background', 'padding', 'border' ),
							'selector'    => '.cz_related_posts .cz_related_post .cz_post_image'
						),
						array(
							'id' => '_css_related_posts_img_tablet',
							'type' => 'cz_sk_hidden',
							'setting_args' => [ 'transport' => 'postMessage' ],
							'selector'    => '.cz_related_posts .cz_related_post .cz_post_image'
						),
						array(
							'id' => '_css_related_posts_img_mobile',
							'type' => 'cz_sk_hidden',
							'setting_args' => [ 'transport' => 'postMessage' ],
							'selector'    => '.cz_related_posts .cz_related_post .cz_post_image'
						),
						array(
							'id' => '_css_related_posts_img_hover',
							'type' => 'cz_sk_hidden',
							'setting_args' => [ 'transport' => 'postMessage' ],
							'selector'    => '.cz_related_posts .cz_related_post:hover .cz_post_image'
						),
						array(
							'id' 			=> '_css_related_posts_title',
							'hover_id' 		=> '_css_related_posts_title_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Titles', 'codevz' ),
							'button' 		=> esc_html__( 'Titles', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size', 'line-height' ),
							'selector' 		=> '.cz_related_posts .cz_related_post h3'
						),
						array(
							'id' 			=> '_css_related_posts_title_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_related_posts .cz_related_post h3'
						),
						array(
							'id' 			=> '_css_related_posts_title_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_related_posts .cz_related_post h3'
						),
						array(
							'id' 			=> '_css_related_posts_title_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_related_posts .cz_related_post:hover h3'
						),
						array(
							'id' 			=> '_css_related_posts_meta',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Meta', 'codevz' ),
							'button' 		=> esc_html__( 'Meta', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size' ),
							'selector' 		=> '.cz_related_posts .cz_related_post_date'
						),
						array(
							'id' 			=> '_css_related_posts_meta_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_related_posts .cz_related_post_date'
						),
						array(
							'id' 			=> '_css_related_posts_meta_links',
							'hover_id' 		=> '_css_related_posts_meta_links_hover',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Meta Links', 'codevz' ),
							'button' 		=> esc_html__( 'Meta Links', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'font-size' ),
							'selector' 		=> '.cz_related_posts .cz_related_post_date a'
						),
						array(
							'id' 			=> '_css_related_posts_meta_links_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_related_posts .cz_related_post_date a'
						),
						array(
							'id' 			=> '_css_related_posts_meta_links_hover',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.cz_related_posts .cz_related_post_date a:hover'
						),
						array(
							'id' 			=> '_css_single_comments_li',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Comments', 'codevz' ),
							'button' 		=> esc_html__( 'Comments', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '.xtra-comments .commentlist li article'
						),
						array(
							'id' 			=> '_css_single_comments_li_tablet',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.xtra-comments .commentlist li article'
						),
						array(
							'id' 			=> '_css_single_comments_li_mobile',
							'type' 			=> 'cz_sk_hidden',
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'selector' 		=> '.xtra-comments .commentlist li article'
						),
					),
				),

			  array(
				'name'   => 'search_settings',
				'title'  => esc_html__( 'Search Page', 'codevz' ),
				'fields' => array(
					array(
						'id' 			=> 'layout_search',
						'type' 			=> 'codevz_image_select',
						'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
						'help'  		=> esc_html__( 'The default is from General > Layout', 'codevz' ),
						'options' 		=> [
							'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
							'ws' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
							'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
							'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
							'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
							'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
							'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
							'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
							'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
							'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
							'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
							'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
							'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
							'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
						],
						'default' 		=> 'right',
						'attributes' 	=> [ 'data-depend-id' => 'layout_search' ]
					),
					array(
						'id'      => 'search_title_prefix',
						'type'    => 'text',
						'title'   => esc_html__( 'Title Prefix', 'codevz' ),
						'default' => esc_html__( 'Search result for:', 'codevz' ),
					),
					array(
						'id' 		=> 'search_cpt',
						'type' 		=> 'text',
						'title'		=> esc_html__( 'Post Type(s)', 'codevz' ),
						'help'		=> 'e.g. post,portfolio,product'
					),
					array(
						'id' 		=> 'search_order',
						'type' 		=> 'select',
						'title' 	=> esc_html__( 'Posts Order', 'codevz' ),
						'options' 	=> [
							'' 				=> esc_html__( '~ Default ~', 'codevz' ),
							'ASC' 			=> esc_html__( 'ASC', 'codevz' ),
							'DESC' 			=> esc_html__( 'DESC', 'codevz' ),
						],
					),
					array(
						'id' 		=> 'search_orderby',
						'type' 		=> 'select',
						'title' 	=> esc_html__( 'Order By', 'codevz' ),
						'options' 	=> [
							'' 				=> esc_html__( '~ Default ~', 'codevz' ),
							'date' 			=> esc_html__( 'Date', 'codevz' ),
							'ID' 			=> esc_html__( 'ID', 'codevz' ),
							'title' 		=> esc_html__( 'Title', 'codevz' ),
							'rand' 			=> esc_html__( 'Random', 'codevz' ),
							'menu_order' 	=> esc_html__( 'Menu order', 'codevz' ),
							'comment_count' => esc_html__( 'Comments count', 'codevz' ),
						],
					),
					array(
						'id' 		=> 'search_count',
						'type' 		=> 'slider',
						'title'		=> esc_html__( 'Count', 'codevz' ),
						'options' 	=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 12 ),
					),
				),
			  ),

			),
		);

		$dynamic_ctp = (array) get_option( 'codevz_post_types' );

		// Generate options for each post types
		foreach( self::post_types() as $cpt ) {
			if ( empty( $cpt ) ) {
				continue;
			}

			$name = get_post_type_object( $cpt );
			$name = isset( $name->label ) ? $name->label : ucwords( str_replace( '_', ' ', $cpt ) );

			$portfolio_slug = ( $cpt === 'portfolio' || in_array( $cpt, $dynamic_ctp ) ) ? array(
				'name'   => $cpt . '_slug',
				'title'  => esc_html__( 'Slug and Title', 'codevz' ),
				'fields' => array(
					array(
						'id' 		=> 'disable_portfolio',
						'type' 		=> 'switcher',
						'title' 	=> esc_html__( 'Disable?', 'codevz' )
					),
					array(
						'id' 	=> 'slug_' . $cpt,
						'type' 	=> 'text',
						'title' => esc_html__( 'Slug', 'codevz' ),
						'attributes' => array( 'placeholder'	=> $cpt ),
						'setting_args' => array('transport' => 'postMessage')
					),
					array(
						'id' 	=> 'title_' . $cpt,
						'type' 	=> 'text',
						'title' => esc_html__( 'Archive title', 'codevz' ),
						'attributes' => array( 'placeholder'	=> $name ),
						'setting_args' => array('transport' => 'postMessage')
					),
					array(
						'id' 	=> 'cat_' . $cpt,
						'type' 	=> 'text',
						'title' => esc_html__( 'Category slug', 'codevz' ),
						'attributes' => array( 'placeholder'	=> $cpt . '/cat' ),
						'setting_args' => array('transport' => 'postMessage')
					),
					array(
						'id' 	=> 'cat_title_' . $cpt,
						'type' 	=> 'text',
						'title' => esc_html__( 'Category title', 'codevz' ),
						'attributes' => array( 'placeholder'	=> 'Categories' ),
						'setting_args' => array('transport' => 'postMessage')
					),
					array(
						'id' 	=> 'tags_' . $cpt,
						'type' 	=> 'text',
						'title' => esc_html__( 'Tags slug', 'codevz' ),
						'attributes' => array( 'placeholder'	=> $cpt . '/tags' ),
						'setting_args' => array('transport' => 'postMessage')
					),
					array(
						'id' 	=> 'tags_title_' . $cpt,
						'type' 	=> 'text',
						'title' => esc_html__( 'Tags title', 'codevz' ),
						'attributes' => array( 'placeholder'	=> 'Tags' ),
						'setting_args' => array('transport' => 'postMessage')
					),
					array(
						'type'    => 'notice',
						'class'   => 'info',
						'content' => esc_html__( 'After changing slug, you should save options then go to Dashboard > Settings > Permalinks and save your permalinks.', 'codevz' )
					),
				)
			) : null;

			$options[] = array(
				'name'   	=> 'post_type_' . $cpt,
				'title'  	=> $name,
				'sections' 	=> array(
					$portfolio_slug,

					array(
						'name'   => $cpt . '_settings',
						'title'  => $name . ' ' . esc_html__( 'Settings', 'codevz' ),
						'fields' => wp_parse_args( 
							array(
								array(
									'id' 			=> 'template_style_' . $cpt,
									'type' 			=> 'codevz_image_select',
									'title' 		=> esc_html__( 'Template', 'codevz' ),
									'help'  		=> $name . ' ' . esc_html__( 'archive page, category page, tags page, etc.', 'codevz' ),
									'options' 		=> [
										'1' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 1' 	, Codevz_Plus::$url . 'assets/img/posts-1.png' ],
										'1' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 2' 	, Codevz_Plus::$url . 'assets/img/posts-2.png' ],
										'6' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 6' 	, Codevz_Plus::$url . 'assets/img/posts-1-2.png' ],
										'3' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 3' 	, Codevz_Plus::$url . 'assets/img/posts-3.png' ],
										'4' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 4' 	, Codevz_Plus::$url . 'assets/img/posts-4.png' ],
										'5' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 5' 	, Codevz_Plus::$url . 'assets/img/posts-5.png' ],
										'7' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 7' 		, Codevz_Plus::$url . 'assets/img/posts-7.png' ],
										'8' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 8' 		, Codevz_Plus::$url . 'assets/img/posts-8.png' ],
										'9' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 9' 		, Codevz_Plus::$url . 'assets/img/posts-9.png' ],
										'10' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 10' 	, Codevz_Plus::$url . 'assets/img/posts-10.png' ],
										'11' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 11' 	, Codevz_Plus::$url . 'assets/img/posts-11.png' ],
										'12' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 12' 	, Codevz_Plus::$url . 'assets/img/posts-12.png' ],
										'13' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 13' 	, Codevz_Plus::$url . 'assets/img/posts-13.png' ],
										'14' 			=> [ esc_html__( 'Template', 'codevz' ) . ' 14' 	, Codevz_Plus::$url . 'assets/img/posts-14.png' ],
										'x' 			=> [ esc_html__( 'Custom Template', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/posts-x.png' ],
									],
									'default' 		=> '10',
									'attributes' 	=> [ 'data-depend-id' => 'template_style_' . $cpt ]
								),
								array(
									'id'    => 'template_' . $cpt,
									'type'    => 'select',
									'title'   => esc_html__( 'Custom Page', 'codevz' ),
									'options'   => Codevz_Plus::$array_pages,
									'dependency'  => array( 'template_style_' . $cpt, '==', 'x' ),
								),
								array(
									'id' 			=> 'layout_' . $cpt,
									'type' 			=> 'codevz_image_select',
									'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
									'help'  		=> $name . ' ' . esc_html__( 'archive and single pages', 'codevz' ),
									'options' 		=> [
										'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
										'ws' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
										'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
										'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
										'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
										'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
										'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
										'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
										'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
										'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
										'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
										'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
										'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
										'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
									],
									'default' 		=> '1',
									'attributes' 	=> [ 'data-depend-id' => 'layout_' . $cpt ]
								),
								array(
									'id' 	=> 'desc_' . $cpt,
									'type' 	=> 'textarea',
									'title' => $name . ' ' . esc_html__( 'Archive Description', 'codevz' ),
									'help'  => esc_html__( 'Text or shortcode are allowed', 'codevz' )
								),
								array(
									'id'    	=> 'posts_per_page_' . $cpt,
									'type'  	=> 'slider',
									'title' 	=> esc_html__( 'Posts', 'codevz' ),
									'options'	=> array( 'unit' => '', 'step' => 1, 'min' => -1, 'max' => 100 )
								),
								array(
									'id' 			=> 'order_' . $cpt,
									'type' 			=> 'select',
									'title' 		=> esc_html__( 'Order', 'codevz' ),
									'options' 		=> [
										'' 				=> esc_html__( '~ Default ~', 'codevz' ),
										'ASC' 			=> esc_html__( 'ASC', 'codevz' ),
										'DESC' 			=> esc_html__( 'DESC', 'codevz' ),
									]
								),
								array(
									'id' 			=> 'orderby_' . $cpt,
									'type' 			=> 'select',
									'title' 		=> esc_html__( 'Order by', 'codevz' ),
									'options' 		=> [
										'' 				=> esc_html__( '~ Default ~', 'codevz' ),
										'date' 			=> esc_html__( 'Date', 'codevz' ),
										'ID' 			=> esc_html__( 'ID', 'codevz' ),
										'title' 		=> esc_html__( 'Title', 'codevz' ),
										'rand' 			=> esc_html__( 'Random', 'codevz' ),
										'menu_order' 	=> esc_html__( 'Menu order', 'codevz' ),
										'comment_count' => esc_html__( 'Reviews count', 'codevz' ),
									]
								),
								array(
									'id'    	=> '2x_height_image_' . $cpt,
									'type'  	=> 'switcher',
									'title' 	=> esc_html__( '2x Height Image', 'codevz' ),
									'dependency'  => array( 'template_style_' . $cpt . '|template_style_' . $cpt, '!=|!=', 'x|3' )
								),
								array(
									'id' 		=> 'hover_icon_icon_' . $cpt,
									'type' 		=> 'icon',
									'title' 	=> esc_html__('Hover Icon', 'codevz'),
									'default' 	=> 'fa czico-109-link-symbol-1',
									'dependency'  	=> array( 'template_style_' . $cpt, '!=', 'x' ),
								),
								array(
									'id'    	=> 'post_excerpt_' . $cpt,
									'type'  	=> 'slider',
									'title'   	=> esc_html__( 'Excerpt', 'codevz' ),
									'help' 	  	=> esc_html__( '-1 means full content without readmore button', 'codevz' ),
									'options'	=> array( 'unit' => '', 'step' => 1, 'min' => -1, 'max' => 50 ),
									'default' 	=> '20',
									'dependency'  => array( 'template_style_' . $cpt . '|template_style_' . $cpt . '|template_style_' . $cpt . '|template_style_' . $cpt, '!=|!=|!=|!=', 'x|12|13|14' )
								),
								array(
									'id' 		=> $cpt . '_excerpt_dots',
									'type' 		=> 'text',
									'title' 	=> esc_html__( 'Excerpt Dots', 'codevz' ),
									'default' 	=> ' ... ',
									'dependency' => array( 'template_style_' . $cpt . '|template_style_' . $cpt . '|template_style_' . $cpt . '|template_style_' . $cpt, '!=|!=|!=|!=', 'x|12|13|14' )
								),
								array(
									'id'          => 'readmore_icon_' . $cpt,
									'type'        => 'icon',
									'title'       => esc_html__('Read More', 'codevz'),
									'default'	  => 'fa fa-angle-right',
									'dependency' => array( 'template_style_' . $cpt . '|template_style_' . $cpt . '|template_style_' . $cpt . '|template_style_' . $cpt . '|hover_icon_icon_' . $cpt, '!=|!=|!=|!=|!=', 'x|12|13|14|-1' )
								),
								array(
									'id'          => 'readmore_' . $cpt,
									'type'        => 'text',
									'title'       => esc_html__( 'Read More', 'codevz' ),
									'default'	    => 'Read More',
									'setting_args' => [ 'transport' => 'postMessage' ],
									'dependency'  => array( 'post_excerpt_' . $cpt, '!=', '-1' )
								),
							),
							self::title_options( '_' . $cpt, '.cz-cpt-' . $cpt . ' ' )
						)
					),

					array(
						'name'   => $cpt . '_styles',
						'title'  => $name . ' ' . esc_html__( 'Styling', 'codevz' ),
						'fields' => array(
							array(
								'id' 			=> '_css_overall_' . $cpt . '',
								'hover_id' 		=> '_css_overall_' . $cpt . '_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Posts', 'codevz' ),
								'button' 		=> esc_html__( 'Posts', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop > div',
							),
							array(
								'id' 			=> '_css_overall_' . $cpt . '_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop > div',
							),
							array(
								'id' 			=> '_css_overall_' . $cpt . '_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop > div',
							),
							array(
								'id' 			=> '_css_overall_' . $cpt . '_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop:hover > div',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_hover_icon',
								'hover_id' 		=> '_css_' . $cpt . '_hover_icon_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Icon', 'codevz' ),
								'button' 		=> esc_html__( 'Icon', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'border' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' article .cz_post_icon',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_hover_icon_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' article .cz_post_icon:hover',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_image',
								'hover_id' 		=> '_css_' . $cpt . '_image_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Image', 'codevz' ),
								'button' 		=> esc_html__( 'Image', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'opacity', 'background', 'padding', 'border' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_image, .cz-cpt-' . $cpt . ' .cz_post_svg',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_image_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_image, .cz-cpt-' . $cpt . ' .cz_post_svg',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_image_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_image, .cz-cpt-' . $cpt . ' .cz_post_svg',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_image_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop:hover .cz_post_image,.cz-cpt-' . $cpt . '  article:hover .cz_post_svg',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_title',
								'hover_id' 		=> '_css_' . $cpt . '_title_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Title', 'codevz' ),
								'button' 		=> esc_html__( 'Title', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'line-height', 'padding', 'border' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_title h3',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_title_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_title h3',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_title_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_title h3',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_title_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_title h3:hover',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_meta_overall',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Meta', 'codevz' ),
								'button' 		=> esc_html__( 'Meta', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'float', 'background', 'padding', 'border' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_meta',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_meta_overall_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_meta',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_meta_overall_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_meta',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_avatar',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Avatar', 'codevz' ),
								'button' 		=> esc_html__( 'Avatar', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'width', 'height', 'border' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_author_avatar img',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_avatar_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_author_avatar img',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_avatar_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_author_avatar img',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_author',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Author', 'codevz' ),
								'button' 		=> esc_html__( 'Author', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'font-weight' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_author_name',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_author_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_author_name',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_author_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_author_name',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_date',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Date', 'codevz' ),
								'button' 		=> esc_html__( 'Date', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'font-style' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_date',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_date_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_date',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_date_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_date',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_excerpt',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Excerpt', 'codevz' ),
								'button' 		=> esc_html__( 'Excerpt', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'text-align', 'color', 'font-size', 'line-height' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_excerpt',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_excerpt_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_excerpt',
							),
							array(
								'id' 			=> '_css_' . $cpt . '_excerpt_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_default_loop .cz_post_excerpt',
							),

							array(
								'id' 			=> '_css_readmore_' . $cpt,
								'hover_id' 		=> '_css_readmore_' . $cpt . '_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Read more', 'codevz' ),
								'button' 		=> esc_html__( 'Read more', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'float', 'color', 'background', 'font-size', 'border' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_readmore, .cz-cpt-' . $cpt . ' .more-link'
							),
							array(
								'id' 			=> '_css_readmore_' . $cpt . '_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_readmore, .cz-cpt-' . $cpt . ' .more-link'
							),
							array(
								'id' 			=> '_css_readmore_' . $cpt . '_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_readmore, .cz-cpt-' . $cpt . ' .more-link'
							),
							array(
								'id' 			=> '_css_readmore_' . $cpt . '_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_readmore:hover, .cz-cpt-' . $cpt . ' .more-link:hover'
							),
							array(
								'id' 			=> '_css_readmore_i_' . $cpt,
								'hover_id' 		=> '_css_readmore_i_' . $cpt . '_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Icon', 'codevz' ),
								'button' 		=> esc_html__( 'Icon', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size' ),
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_readmore i, .cz-cpt-' . $cpt . ' .more-link',
							),
							array(
								'id' 			=> '_css_readmore_i_' . $cpt . '_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz-cpt-' . $cpt . ' .cz_readmore:hover i, .cz-cpt-' . $cpt . ' .more-link:hover i',
							),

						),
					),

					array(
						'name'   => $cpt . '_single_settings',
						'title'  => esc_html__( 'Single Settings', 'codevz' ),
						'fields' => array(
							array(
								'id' 	=> 'meta_data_' . $cpt,
								'type' 	=> 'checkbox',
								'title' => esc_html__( 'Features', 'codevz' ),
									'options' => array(
									'image'		=> esc_html__( 'Post Image', 'codevz' ),
									'author'	=> esc_html__( 'Author', 'codevz' ),
									'date'		=> esc_html__( 'Date', 'codevz' ),
									'cats'		=> esc_html__( 'Categories', 'codevz' ),
									'tags'		=> esc_html__( 'Tags', 'codevz' ),
									'next_prev' => esc_html__( 'Next Prev Posts', 'codevz' ),
								),
								'default' => array( 'image','date','author','cats','tags','author_box', 'next_prev' )
							),
							array(
								'id' 			=> 'related_' . $cpt . '_col',
								'type' 			=> 'codevz_image_select',
								'title' 		=> esc_html__( 'Related Columns', 'codevz' ),
								'options' 		=> [
									's6' 			=> [ '2 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-2.png' ],
									's4' 			=> [ '3 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-3.png' ],
									's3' 			=> [ '4 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-4.png' ],
								],
								'default' 		=> 's4',
								'dependency'  => array( 'related_' . $cpt . '_ppp', '!=', '0' ),
							),
							array(
								'id'    		=> 'related_' . $cpt . '_ppp',
								'type'  		=> 'slider',
								'title' 		=> esc_html__( 'Related Posts', 'codevz' ),
								'options'		=> array( 'unit' => '', 'step' => 1, 'min' => -1, 'max' => 100 ),
								'default' 		=> '3'
							),
							array(
								'id'          	=> 'related_posts_' . $cpt,
								'type'        	=> 'text',
								'title'       	=> esc_html__('Related Title', 'codevz'),
								'default'		=> 'You may also like ...',
								'setting_args' 	=> array('transport' => 'postMessage'),
								'dependency'  	=> array( 'related_' . $cpt . '_ppp', '!=', '0' ),
							),
							array(
								'id' 			=> 'prev_' . $cpt,
								'type' 			=> 'text',
								'title' 		=> esc_html__( 'Prev Surtitle', 'codevz' ),
								'default' 		=> 'Previous',
							),
							array(
								'id' 			=> 'next_' . $cpt,
								'type' 			=> 'text',
								'title' 		=> esc_html__( 'Next Surtitle', 'codevz' ),
								'default' 		=> 'Next',
							),
						),
					),

					array(
						'name'   => $cpt . '_single_styles',
						'title'  => esc_html__( 'Single Styling', 'codevz' ),
						'fields' => array(
							[
								'type' 			=> 'notice',
								'class' 		=> 'info',
								'content' 		=> esc_html__( 'General styling for all post types single posts located in the ', 'codevz' ) . '<br /><a href="#" onclick="wp.customize.section( \'codevz_theme_options-single_styles\' ).focus()" style="color:white">' . esc_html__( 'Blog > Single Styling', 'codevz' ) . '</a>'
							],

							array(
								'id' 			=> $cpt . '_custom_single_sk',
								'type' 			=> 'switcher',
								'title' 		=> esc_html__( 'Custom Single Styles?', 'codevz' ),
								'button' 		=> esc_html__( 'Custom Single Styles?', 'codevz' )
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_con',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Container', 'codevz' ),
								'button' 		=> esc_html__( 'Container', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'padding', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .single_con',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_con_tablet','type' => 'cz_sk_hidden','setting_args' => [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .single_con',
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_con_mobile','type' => 'cz_sk_hidden','setting_args' => [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .single_con',
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_title',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Title', 'codevz' ),
								'button' 		=> esc_html__( 'Title', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'line-height' ),
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-post-title',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_title_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-post-title',
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_title_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-post-title',
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_title_date',
								'hover_id' 		=> '_css_single_' . $cpt . '_title_date_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Date', 'codevz' ),
								'button' 		=> esc_html__( 'Date', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-post-title-date a',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_title_date_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-post-title-date a:hover',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_fi',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Image', 'codevz' ),
								'button' 		=> esc_html__( 'Image', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'margin', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .single_con .cz_single_fi img',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_fi_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .single_con .cz_single_fi img',
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_fi_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .single_con .cz_single_fi img',
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_tags_categories',
								'hover_id' 		=> '_css_single_' . $cpt . '_tags_categories_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Meta', 'codevz' ),
								'button' 		=> esc_html__( 'Meta', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .tagcloud a, .single-' . $cpt . '-sk .cz_post_cat a',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_tags_categories_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .tagcloud a, .single-' . $cpt . '-sk .cz_post_cat a',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_tags_categories_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .tagcloud a, .single-' . $cpt . '-sk .cz_post_cat a'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_tags_categories_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .tagcloud a:hover, .single-' . $cpt . '-sk .cz_post_cat a:hover'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_tags_categories_icon',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Meta Icon', 'codevz' ),
								'button' 		=> esc_html__( 'Meta Icon', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .single_con .tagcloud a:first-child, .single-' . $cpt . '-sk .single_con .cz_post_cat a:first-child',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_tags_categories_icon_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .single_con .tagcloud a:first-child, .single-' . $cpt . '-sk .single_con .cz_post_cat a:first-child'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_tags_categories_icon_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .single_con .tagcloud a:first-child, .single-' . $cpt . '-sk .single_con .cz_post_cat a:first-child'
							),
							array(
								'type'    => 'notice',
								'class'   => 'info xtra-notice',
								'content' => esc_html__( 'Next & Previous Posts', 'codevz' ),
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_con',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Container', 'codevz' ),
								'button' 		=> esc_html__( 'Container', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_con_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_con_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_icons',
								'hover_id' 		=> '_css_single_' . $cpt . '_next_prev_icons_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Icons', 'codevz' ),
								'button' 		=> esc_html__( 'Icons', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev .previous i,.single-' . $cpt . '-sk .next_prev .next i',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_icons_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev .previous i,.single-' . $cpt . '-sk .next_prev .next i'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_icons_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev .previous i,.single-' . $cpt . '-sk .next_prev .next i'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_icons_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev .previous:hover i,.single-' . $cpt . '-sk .next_prev .next:hover i'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_titles',
								'hover_id' 		=> '_css_single_' . $cpt . '_next_prev_titles_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Titles', 'codevz' ),
								'button' 		=> esc_html__( 'Titles', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'line-height' ),
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev h4',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_titles_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev h4'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_titles_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev h4'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_titles_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev li:hover h4'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_surtitle',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Sur Titles', 'codevz' ),
								'button' 		=> esc_html__( 'Sur Titles', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev h4 small',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_surtitle_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev h4 small'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_next_prev_surtitle_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .next_prev h4 small'
							),

							array(
								'type'    => 'notice',
								'class'   => 'info xtra-notice',
								'content' => esc_html__( 'Related Posts & Comments', 'codevz' ),
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_con',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Container', 'codevz' ),
								'button' 		=> esc_html__( 'Container', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-comments,.single-' . $cpt . '-sk .content.cz_related_posts,.single-' . $cpt . '-sk .cz_author_box,.single-' . $cpt . '-sk .related.products,.single-' . $cpt . '-sk .upsells.products,.single-' . $cpt . '-sk .up-sells.products',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_con_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-comments,.single-' . $cpt . '-sk .content.cz_related_posts,.single-' . $cpt . '-sk .cz_author_box,.single-' . $cpt . '-sk .related.products,.single-' . $cpt . '-sk .upsells.products,.single-' . $cpt . '-sk .up-sells.products'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_con_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-comments,.single-' . $cpt . '-sk .content.cz_related_posts,.single-' . $cpt . '-sk .cz_author_box,.single-' . $cpt . '-sk .related.products,.single-' . $cpt . '-sk .upsells.products,.single-' . $cpt . '-sk .up-sells.products'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_sec_title',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Title', 'codevz' ),
								'button' 		=> esc_html__( 'Title', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk #comments > h3,.single-' . $cpt . '-sk .content.cz_related_posts > h4,.single-' . $cpt . '-sk .cz_author_box h4,.single-' . $cpt . '-sk .related.products > h2,.single-' . $cpt . '-sk .upsells.products > h2,.single-' . $cpt . '-sk .up-sells.products > h2',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_sec_title_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk #comments > h3,.single-' . $cpt . '-sk .content.cz_related_posts > h4,.single-' . $cpt . '-sk .cz_author_box h4,.single-' . $cpt . '-sk .related.products > h2,.single-' . $cpt . '-sk .upsells.products > h2,.single-' . $cpt . '-sk .up-sells.products > h2'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_sec_title_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk #comments > h3,.single-' . $cpt . '-sk .content.cz_related_posts > h4,.single-' . $cpt . '-sk .cz_author_box h4,.single-' . $cpt . '-sk .related.products > h2,.single-' . $cpt . '-sk .upsells.products > h2,.single-' . $cpt . '-sk .up-sells.products > h2'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_sec_title_before',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Title Shape 1', 'codevz' ),
								'button' 		=> esc_html__( 'Title Shape 1', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'margin', 'width', 'height', 'border', 'top', 'left', 'bottom', 'right' ),
								'selector' 		=> '.single-' . $cpt . '-sk #comments > h3:before,.single-' . $cpt . '-sk .content.cz_related_posts > h4:before,.single-' . $cpt . '-sk .cz_author_box h4:before,.single-' . $cpt . '-sk .related.products > h2:before,.single-' . $cpt . '-sk .upsells.products > h2:before,.single-' . $cpt . '-sk .up-sells.products > h2:before',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_sec_title_before_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk #comments > h3:before,.single-' . $cpt . '-sk .content.cz_related_posts > h4:before,.single-' . $cpt . '-sk .cz_author_box h4:before,.single-' . $cpt . '-sk .related.products > h2:before,.single-' . $cpt . '-sk .upsells.products > h2:before,.single-' . $cpt . '-sk .up-sells.products > h2:before'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_sec_title_before_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk #comments > h3:before,.single-' . $cpt . '-sk .content.cz_related_posts > h4:before,.single-' . $cpt . '-sk .cz_author_box h4:before,.single-' . $cpt . '-sk .related.products > h2:before,.single-' . $cpt . '-sk .upsells.products > h2:before,.single-' . $cpt . '-sk .up-sells.products > h2:before'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_sec_title_after',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Title Shape 2', 'codevz' ),
								'button' 		=> esc_html__( 'Title Shape 2', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'text-align', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk #comments > h3:after,.single-' . $cpt . '-sk .content.cz_related_posts > h4:after,.single-' . $cpt . '-sk .cz_author_box h4:after,.single-' . $cpt . '-sk .related.products > h2:after,.single-' . $cpt . '-sk .upsells.products > h2:after,.single-' . $cpt . '-sk .up-sells.products > h2:after',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_sec_title_after_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk #comments > h3:after,.single-' . $cpt . '-sk .content.cz_related_posts > h4:after,.single-' . $cpt . '-sk .cz_author_box h4:after,.single-' . $cpt . '-sk .related.products > h2:after,.single-' . $cpt . '-sk .upsells.products > h2:after,.single-' . $cpt . '-sk .up-sells.products > h2:after'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_sec_title_after_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk #comments > h3:after,.single-' . $cpt . '-sk .content.cz_related_posts > h4:after,.single-' . $cpt . '-sk .cz_author_box h4:after,.single-' . $cpt . '-sk .related.products > h2:after,.single-' . $cpt . '-sk .upsells.products > h2:after,.single-' . $cpt . '-sk .up-sells.products > h2:after'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts',
								'hover_id' 		=> '_css_single_' . $cpt . '_related_posts_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Posts', 'codevz' ),
								'button' 		=> esc_html__( 'Posts', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post > div',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' => '_css_single_' . $cpt . '_related_posts_tablet',
								'type' => 'cz_sk_hidden',
								'setting_args' => [ 'transport' => 'postMessage' ],
								'selector' => '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post > div'
							),
							array(
								'id' => '_css_single_' . $cpt . '_related_posts_mobile',
								'type' => 'cz_sk_hidden',
								'setting_args' => [ 'transport' => 'postMessage' ],
								'selector' => '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post > div'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post:hover > div',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id'      	=> '_css_single_' . $cpt . '_related_posts_img',
								'hover_id' 	=> '_css_single_' . $cpt . '_related_posts_img_hover',
								'type'      => 'cz_sk',
								'title'    => esc_html__( 'Images', 'codevz' ),
								'button'    => esc_html__( 'Images', 'codevz' ),
								'setting_args'  => [ 'transport' => 'postMessage' ],
								'settings'    => array( 'background', 'padding', 'border' ),
								'selector'    => '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post .cz_post_image',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' => '_css_single_' . $cpt . '_related_posts_img_tablet',
								'type' => 'cz_sk_hidden',
								'setting_args' => [ 'transport' => 'postMessage' ],
								'selector'    => '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post .cz_post_image'
							),
							array(
								'id' => '_css_single_' . $cpt . '_related_posts_img_mobile',
								'type' => 'cz_sk_hidden',
								'setting_args' => [ 'transport' => 'postMessage' ],
								'selector'    => '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post .cz_post_image'
							),
							array(
								'id' => '_css_single_' . $cpt . '_related_posts_img_hover',
								'type' => 'cz_sk_hidden',
								'setting_args' => [ 'transport' => 'postMessage' ],
								'selector'    => '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post:hover .cz_post_image',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_title',
								'hover_id' 		=> '_css_single_' . $cpt . '_related_posts_title_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Titles', 'codevz' ),
								'button' 		=> esc_html__( 'Titles', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'line-height' ),
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post h3',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_title_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post h3'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_title_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post h3'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_title_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post:hover h3'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_meta',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Meta', 'codevz' ),
								'button' 		=> esc_html__( 'Meta', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size' ),
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post_date',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_meta_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post_date'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_meta_links',
								'hover_id' 		=> '_css_single_' . $cpt . '_related_posts_meta_links_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Meta Links', 'codevz' ),
								'button' 		=> esc_html__( 'Meta Links', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size' ),
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post_date a',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_meta_links_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post_date a'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_related_posts_meta_links_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .cz_related_posts .cz_related_post_date a:hover'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_comments_li',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Comments', 'codevz' ),
								'button' 		=> esc_html__( 'Comments', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-comments .commentlist li article',
								'dependency' 	=> [ $cpt . '_custom_single_sk', '!=', '' ]
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_comments_li_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-comments .commentlist li article'
							),
							array(
								'id' 			=> '_css_single_' . $cpt . '_comments_li_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.single-' . $cpt . '-sk .xtra-comments .commentlist li article'
							),
						),
					),


				)
			);
		}

		// bbpress options
		if ( function_exists( 'is_bbpress' ) || $all ) {
			$options[] = array(
				'name'   => 'post_type_bbpress',
				'title'  => esc_html__( 'BBPress', 'codevz' ),
				'fields' => wp_parse_args( 
					array(
						array(
							'id' 			=> 'layout_bbpress',
							'type' 			=> 'codevz_image_select',
							'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
							'help'  		=> esc_html__( 'For all bbpress pages', 'codevz' ),
							'options' 		=> [
								'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
								'ws' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
								'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
								'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
								'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
								'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
								'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
								'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
								'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
								'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
								'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
								'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
								'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
								'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
							],
							'default' 		=> '1',
							'attributes' 	=> [ 'data-depend-id' => 'layout_bbpress' ]
						),
						array(
							'type'    => 'notice',
							'class'   => 'info',
							'content' => esc_html__( 'Styling', 'codevz' )
						),
						array(
							'id' 			=> '_css_bbpress_search_container',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Search', 'codevz' ),
							'button' 		=> esc_html__( 'Search', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '.bbp-search-form'
						),
						array(
							'id' 			=> '_css_bbpress_search_input',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Search Input', 'codevz' ),
							'button' 		=> esc_html__( 'Search Input', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'padding', 'border' ),
							'selector' 		=> '.bbp-search-form #bbp_search'
						),
						array(
							'id' 			=> '_css_bbpress_search_button',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Search Button', 'codevz' ),
							'button' 		=> esc_html__( 'Search Button', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'padding', 'border' ),
							'selector' 		=> '.bbp-search-form #bbp_search_submit'
						),
						array(
							'id' 			=> '_css_bbpress_forums_container',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Forums', 'codevz' ),
							'button' 		=> esc_html__( 'Forums', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'background', 'padding', 'border' ),
							'selector' 		=> '#bbpress-forums ul.bbp-lead-topic, #bbpress-forums ul.bbp-topics, #bbpress-forums ul.bbp-forums, #bbpress-forums ul.bbp-replies, #bbpress-forums ul.bbp-search-results'
						),
						array(
							'id' 			=> '_css_bbpress_forums_table_hf',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Table header, footer', 'codevz' ),
							'button' 		=> esc_html__( 'Table header, footer', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'color', 'background', 'padding', 'border' ),
							'selector' 		=> '#bbpress-forums li.bbp-header, #bbpress-forums li.bbp-footer'
						),
						array(
							'id' 			=> '_css_bbpress_forum_topic_title',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Topics Title', 'codevz' ),
							'button' 		=> esc_html__( 'Topics Title', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'font-size', 'color', 'background', 'padding', 'border' ),
							'selector' 		=> '.bbp-forum-title, li.bbp-topic-title > a'
						),
						array(
							'id' 			=> '_css_bbpress_forum_topic_subtitle',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Subtitle', 'codevz' ),
							'button' 		=> esc_html__( 'Subtitle', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'font-size', 'color', 'background', 'padding', 'border' ),
							'selector' 		=> '#bbpress-forums .bbp-forum-info .bbp-forum-content, #bbpress-forums p.bbp-topic-meta'
						),
						array(
							'id' 			=> '_css_bbpress_author_part',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Author', 'codevz' ),
							'button' 		=> esc_html__( 'Author', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'font-size', 'color', 'background', 'padding', 'border' ),
							'selector' 		=> '#bbpress-forums .status-publish .bbp-topic-author, #bbpress-forums .status-publish .bbp-reply-author'
						),
						array(
							'id' 			=> '_css_bbpress_reply_part',
							'type' 			=> 'cz_sk',
							'title' 		=> esc_html__( 'Content', 'codevz' ),
							'button' 		=> esc_html__( 'Content', 'codevz' ),
							'setting_args' 	=> [ 'transport' => 'postMessage' ],
							'settings' 		=> array( 'font-size', 'color', 'background', 'padding', 'border' ),
							'selector' 		=> '#bbpress-forums .status-publish .bbp-topic-content, #bbpress-forums .status-publish .bbp-reply-content'
						),
						array(
							'type'    => 'notice',
							'class'   => 'info xtra-notice',
							'content' => esc_html__( 'Title & Breadcrumbs', 'codevz' )
						),
					),
					self::title_options( '_bbpress', '.cz-cpt-bbpress ' )
				)
			);
		}

		// WooCommerce options
		if ( function_exists( 'is_woocommerce' ) || $all ) {
			$options[] = array(
				'name' 		=> 'post_type_product',
				'title' 	=> esc_html__( 'WooCommerce Pro', 'codevz' ),
				'sections'  => array(

					array(
						'name'   => 'products',
						'title'  => esc_html__( 'Products Settings', 'codevz' ),
						'fields' => wp_parse_args(
							array(
								array(
									'id' 			=> 'layout_product',
									'type' 			=> 'codevz_image_select',
									'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
									'help'  		=> esc_html__( 'For all products pages', 'codevz' ),
									'options' 		=> [
										'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
										'ws' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
										'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
										'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
										'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
										'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
										'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
										'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
										'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
										'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
										'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
										'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
										'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
										'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
									],
									'default' 		=> '1'
								),
								array(
									'id' 			=> 'woo_col',
									'type' 			=> 'codevz_image_select',
									'title' 		=> esc_html__( 'Shop Columns', 'codevz' ),
									'options' 		=> [
										'2' 			=> [ '2 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-2.png' ],
										'3' 			=> [ '3 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-3.png' ],
										'4' 			=> [ '4 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-4.png' ],
										'5' 			=> [ '5 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-5.png' ],
										'6' 			=> [ '6 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-6.png' ],
									],
									'default' 		=> '4'
								),
								array(
									'id'    		=> 'woo_two_col_mobile',
									'type'  		=> 'switcher',
									'title' 		=> esc_html__( '2 Columns on mobile', 'codevz' ),
								),
								array(
									'id'    		=> 'woo_items_per_page',
									'type'  		=> 'slider',
									'title' 		=> esc_html__( 'Products', 'codevz' ),
									'options'		=> array( 'unit' => '', 'step' => 1, 'min' => -1, 'max' => 100 ),
								),
								array(
									'id' 			=> 'woo_order',
									'type' 			=> 'select',
									'title' 		=> esc_html__( 'Order', 'codevz' ),
									'options' 		=> [
										'' 				=> esc_html__( '~ Default ~', 'codevz' ),
										'ASC' 			=> esc_html__( 'ASC', 'codevz' ),
										'DESC' 			=> esc_html__( 'DESC', 'codevz' ),
									]
								),
								array(
									'id' 			=> 'woo_orderby',
									'type' 			=> 'select',
									'title' 		=> esc_html__( 'Order by', 'codevz' ),
									'options' 		=> [
										'' 				=> esc_html__( '~ Default ~', 'codevz' ),
										'date' 			=> esc_html__( 'Date', 'codevz' ),
										'ID' 			=> esc_html__( 'ID', 'codevz' ),
										'title' 		=> esc_html__( 'Title', 'codevz' ),
										'rand' 			=> esc_html__( 'Random', 'codevz' ),
										'menu_order' 	=> esc_html__( 'Menu order', 'codevz' ),
										'comment_count' => esc_html__( 'Reviews count', 'codevz' ),
									]
								),
								array(
									'id' 			=> 'woo_hover_effect',
									'type' 			=> 'select',
									'title' 		=> esc_html__( 'Hover Effect', 'codevz' ),
									'options' 		=> [
										'' 				=> esc_html__( '~ Disable ~', 'codevz' ),
										'no_effect' 	=> esc_html__( 'No effect', 'codevz' ),
										'slow_fade' 	=> esc_html__( 'Slow Fade', 'codevz' ),
										'simple_fade' 	=> esc_html__( 'Fast Fade', 'codevz' ),
										'flip_h' 		=> esc_html__( 'Flip Horizontal', 'codevz' ),
										'flip_v' 		=> esc_html__( 'Flip Vertical', 'codevz' ),
										'fade_to_top' 	=> esc_html__( 'Fade To Top', 'codevz' ),
										'fade_to_bottom' => esc_html__( 'Fade To Bottom', 'codevz' ),
										'fade_to_left' 	=> esc_html__( 'Fade To Left', 'codevz' ),
										'fade_to_right' => esc_html__( 'Fade To Right', 'codevz' ),
										'zoom_in' 		=> esc_html__( 'Zoom In', 'codevz' ),
										'zoom_out' 		=> esc_html__( 'Zoom Out', 'codevz' ),
										'blurred' 		=> esc_html__( 'Blurred', 'codevz' ),
									]
								),
								array(
									'id'    		=> 'woo_wishlist',
									'type'  		=> 'switcher',
									'title' 		=> esc_html__( 'Wishlist', 'codevz' )
								),
								array(
									'id' 			=> 'woo_wishlist_page',
									'type' 			=> 'select',
									'title'			=> esc_html__( 'Wishlist Page', 'codevz' ),
									'help' 			=> esc_html__( 'You can edit Wishlist page from Dashboard > Pages and include wishlist shortcode [cz_wishlist] in page content.', 'codevz' ),
									'options' 		=> Codevz_Plus::$array_pages,
									'default' 		=> 'Wishlist',
									'dependency' 	=> array( 'woo_wishlist', '==', 'true' ),
								),
								array(
									'id'    		=> 'woo_quick_view',
									'type'  		=> 'switcher',
									'title' 		=> esc_html__( 'Quick View', 'codevz' )
								),
								array(
									'id'    		=> 'woo_wishlist_qv_center',
									'type'  		=> 'switcher',
									'title' 		=> esc_html__( 'Center Mode', 'codevz' )
								),
								array(
									'id'    		=> 'woo_cart',
									'type'  		=> 'text',
									'title' 		=> esc_html__( 'Cart', 'codevz' ),
									'default' 		=> esc_html__( 'Cart', 'codevz' ),
									'setting_args' 	=> [ 'transport' => 'postMessage' ],
								),
								array(
									'id'    		=> 'woo_checkout',
									'type'  		=> 'text',
									'title' 		=> esc_html__( 'Cart Checkout', 'codevz' ),
									'default' 		=> esc_html__( 'Checkout', 'codevz' ),
									'setting_args' 	=> [ 'transport' => 'postMessage' ],
								),
								array(
									'id'    		=> 'woo_continue_shopping',
									'type'  		=> 'textarea',
									'title' 		=> esc_html__( 'Continue Shopping', 'codevz' ),
									'default' 		=> esc_html__( 'Continue Shopping', 'codevz' ),
									'setting_args' 	=> [ 'transport' => 'postMessage' ],
								),
								array(
									'id'    		=> 'woo_no_products',
									'type'  		=> 'textarea',
									'title' 		=> esc_html__( 'Cart have no prodcuts', 'codevz' ),
									'default' 		=> 'No products in the cart',
									'setting_args' 	=> [ 'transport' => 'postMessage' ],
								),
							),
							self::title_options( '_product', '.cz-cpt-product ' )
						)
					),

					array(
						'name'   => 'products_sk',
						'title'  => esc_html__( 'Products Styling', 'codevz' ),
						'fields' => array(
							array(
								'id' 			=> '_css_woo_products_overall',
								'hover_id' 		=> '_css_woo_products_overall_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Product', 'codevz' ),
								'button' 		=> esc_html__( 'Product', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.woocommerce ul.products li.product .woocommerce-loop-product__link'
							),
							array(
								'id' 			=> '_css_woo_products_overall_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .woocommerce-loop-product__link'
							),
							array(
								'id' 			=> '_css_woo_products_overall_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .woocommerce-loop-product__link'
							),
							array(
								'id' 			=> '_css_woo_products_overall_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product:hover .woocommerce-loop-product__link'
							),
							array(
								'id' 			=> '_css_woo_products_thumbnails',
								'hover_id' 		=> '_css_woo_products_thumbnails_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Image', 'codevz' ),
								'button' 		=> esc_html__( 'Image', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border', 'border-radius' ),
								'selector' 		=> '.woocommerce ul.products li.product a img'
							),
							array(
								'id' 			=> '_css_woo_products_thumbnails_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product a img'
							),
							array(
								'id' 			=> '_css_woo_products_thumbnails_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product a img'
							),
							array(
								'id' 			=> '_css_woo_products_thumbnails_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product:hover a img'
							),
							array(
								'id' 			=> '_css_woo_products_title',
								'hover_id' 		=> '_css_woo_products_title_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Title', 'codevz' ),
								'button' 		=> esc_html__( 'Title', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-family', 'font-size', 'text-align', 'float' ),
								'selector' 		=> '.woocommerce ul.products li.product .woocommerce-loop-category__title, .woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3,.woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-category__title, .woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-product__title, .woocommerce.woo-template-2 ul.products li.product h3'
							),
							array(
								'id' 			=> '_css_woo_products_title_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .woocommerce-loop-category__title, .woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3,.woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-category__title, .woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-product__title, .woocommerce.woo-template-2 ul.products li.product h3'
							),
							array(
								'id' 			=> '_css_woo_products_title_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .woocommerce-loop-category__title, .woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3,.woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-category__title, .woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-product__title, .woocommerce.woo-template-2 ul.products li.product h3'
							),
							array(
								'id' 			=> '_css_woo_products_title_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product:hover .woocommerce-loop-category__title, .woocommerce ul.products li.product:hover .woocommerce-loop-product__title, .woocommerce ul.products li.product:hover h3,.woocommerce.woo-template-2 ul.products li.product:hover .woocommerce-loop-category__title, .woocommerce.woo-template-2 ul.products li.product:hover .woocommerce-loop-product__title, .woocommerce.woo-template-2 ul.products li.product:hover h3'
							),
							array(
								'id' 			=> '_css_woo_products_stars',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Rating Stars', 'codevz' ),
								'button' 		=> esc_html__( 'Rating Stars', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size' ),
								'selector' 		=> '.woocommerce ul.products li.product .star-rating'
							),
							array(
								'id' 			=> '_css_woo_products_stars_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .star-rating'
							),
							array(
								'id' 			=> '_css_woo_products_stars_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .star-rating'
							),
							array(
								'id' 			=> '_css_woo_products_onsale',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Sale Badge', 'codevz' ),
								'button' 		=> esc_html__( 'Sale Badge', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'line-height', 'width', 'height', 'color', 'background', 'font-family', 'font-size', 'top', 'left', 'border' ),
								'selector' 		=> '.woocommerce span.onsale, .woocommerce ul.products li.product .onsale,.woocommerce.single span.onsale, .woocommerce.single ul.products li.product .onsale'
							),
							array(
								'id' 			=> '_css_woo_products_onsale_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce span.onsale, .woocommerce ul.products li.product .onsale,.woocommerce.single span.onsale, .woocommerce.single ul.products li.product .onsale'
							),
							array(
								'id' 			=> '_css_woo_products_onsale_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce span.onsale, .woocommerce ul.products li.product .onsale,.woocommerce.single span.onsale, .woocommerce.single ul.products li.product .onsale'
							),
							array(
								'id' 			=> '_css_woo_products_price',
								'hover_id' 		=> '_css_woo_products_price_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Price', 'codevz' ),
								'button' 		=> esc_html__( 'Price', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-family', 'font-size', 'top', 'right' ),
								'selector' 		=> '.woocommerce ul.products li.product .price'
							),
							array(
								'id' 			=> '_css_woo_products_price_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .price'
							),
							array(
								'id' 			=> '_css_woo_products_price_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .price'
							),
							array(
								'id' 			=> '_css_woo_products_price_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product:hover .price'
							),

							array(
								'id' 			=> '_css_woo_products_sale',
								'hover_id' 		=> '_css_woo_products_sale_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Sale Price', 'codevz' ),
								'button' 		=> esc_html__( 'Sale Price', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size' ),
								'selector' 		=> '.woocommerce ul.products li.product .price del span'
							),
							array(
								'id' 			=> '_css_woo_products_sale_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .price del span'
							),
							array(
								'id' 			=> '_css_woo_products_sale_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .price del span'
							),
							array(
								'id' 			=> '_css_woo_products_sale_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product:hover .price del span'
							),

							array(
								'id' 			=> '_css_woo_products_add_to_cart',
								'hover_id' 		=> '_css_woo_products_add_to_cart_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Add to cart', 'codevz' ),
								'button' 		=> esc_html__( 'Add to cart', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-family', 'font-size', 'opacity', 'float', 'background', 'border' ),
								'selector' 		=> '.woocommerce ul.products li.product .button.add_to_cart_button, .woocommerce ul.products li.product .button[class*="product_type_"]'
							),
							array(
								'id' 			=> '_css_woo_products_add_to_cart_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .button.add_to_cart_button, .woocommerce ul.products li.product .button[class*="product_type_"]'
							),
							array(
								'id' 			=> '_css_woo_products_add_to_cart_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .button.add_to_cart_button, .woocommerce ul.products li.product .button[class*="product_type_"]'
							),
							array(
								'id' 			=> '_css_woo_products_add_to_cart_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce ul.products li.product .button.add_to_cart_button:hover, .woocommerce ul.products li.product .button[class*="product_type_"]:hover'
							),
							array(
								'id' 			=> '_css_woo_products_added_to_cart',
								'hover_id' 		=> '_css_woo_products_added_to_cart_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'View Cart', 'codevz' ),
								'button' 		=> esc_html__( 'View Cart', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'font-style' ),
								'selector' 		=> '.woocommerce a.added_to_cart'
							),
							array(
								'id' 			=> '_css_woo_products_added_to_cart_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce a.added_to_cart'
							),
							array(
								'id' 			=> '_css_woo_products_added_to_cart_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce a.added_to_cart'
							),
							array(
								'id' 			=> '_css_woo_products_added_to_cart_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce a.added_to_cart:hover'
							),
							array(
								'id' 			=> '_css_woo_products_result_count',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Result Count', 'codevz' ),
								'button' 		=> esc_html__( 'Result Count', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'border' ),
								'selector' 		=> '.woocommerce .woocommerce-result-count'
							),
							array(
								'id' 			=> '_css_woo_products_result_count_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .woocommerce-result-count'
							),
							array(
								'id' 			=> '_css_woo_products_result_count_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .woocommerce-result-count'
							),
							array(
								'id' 			=> '_css_woo_products_icons',
								'hover_id' 		=> '_css_woo_products_icons_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Icons', 'codevz' ),
								'button' 		=> esc_html__( 'Icons', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'border', 'box-shadow' ),
								'selector' 		=> '.products .product .xtra-product-icons'
							),
							array(
								'id' 			=> '_css_woo_products_icons_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.products .product .xtra-product-icons'
							),
							array(
								'id' 			=> '_css_woo_products_icons_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.products .product .xtra-product-icons'
							),
							array(
								'id' 			=> '_css_woo_products_icons_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.products .product:hover .xtra-product-icons'
							),
							array(
								'id' 			=> '_css_woo_products_wishlist',
								'hover_id' 		=> '_css_woo_products_wishlist_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Wishlist', 'codevz' ),
								'button' 		=> esc_html__( 'Wishlist', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'background', 'border', 'box-shadow' ),
								'selector' 		=> '.products .product .xtra-add-to-wishlist'
							),
							array(
								'id' 			=> '_css_woo_products_wishlist_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.products .product .xtra-add-to-wishlist'
							),
							array(
								'id' 			=> '_css_woo_products_wishlist_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.products .product .xtra-add-to-wishlist'
							),
							array(
								'id' 			=> '_css_woo_products_wishlist_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.products .product .xtra-add-to-wishlist:hover'
							),
							array(
								'id' 			=> '_css_woo_products_quick_view',
								'hover_id' 		=> '_css_woo_products_quick_view_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Quick View', 'codevz' ),
								'button' 		=> esc_html__( 'Quick View', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'background', 'border', 'box-shadow' ),
								'selector' 		=> '.products .product .xtra-product-quick-view'
							),
							array(
								'id' 			=> '_css_woo_products_quick_view_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.products .product .xtra-product-quick-view'
							),
							array(
								'id' 			=> '_css_woo_products_quick_view_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.products .product .xtra-product-quick-view'
							),
							array(
								'id' 			=> '_css_woo_products_quick_view_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.products .product .xtra-product-quick-view:hover'
							),
							array(
								'id' 			=> '_css_woo_products_quick_view_popup',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Popup', 'codevz' ),
								'button' 		=> esc_html__( 'Popup', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'background', 'border', 'box-shadow' ),
								'selector' 		=> '#xtra_quick_view .cz_popup_in'
							),
							array(
								'id' 			=> '_css_woo_products_quick_view_popup_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '#xtra_quick_view .cz_popup_in'
							),
							array(
								'id' 			=> '_css_woo_products_quick_view_popup_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '#xtra_quick_view .cz_popup_in'
							),
						)
					),

					array(
						'name'   => 'product',
						'title'  => esc_html__( 'Product Settings', 'codevz' ),
						'fields' => array(
							array(
								'id' 			=> 'layout_single_product',
								'type' 			=> 'codevz_image_select',
								'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
								'help'  		=> esc_html__( 'For all single product pages', 'codevz' ),
								'options' 		=> [
									'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
									'ws' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
									'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
									'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
									'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
									'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
									'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
									'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
									'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
									'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
									'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
									'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
									'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
									'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
								],
								'default' 		=> '1'
							),
							array(
								'id' 			=> 'woo_related_col',
								'type' 			=> 'codevz_image_select',
								'title' 		=> esc_html__( 'Related products', 'codevz' ),
								'options' 		=> [
									'0' 			=> [ esc_html__( 'Off', 'codevz' ) 					, Codevz_Plus::$url . 'assets/img/off.png' ],
									'2' 			=> [ '2 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-2.png' ],
									'3' 			=> [ '3 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-3.png' ],
									'4' 			=> [ '4 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-4.png' ],
								],
								'default' 		=> '3'
							),
							array(
								'id' 	=> 'woo_gallery_features',
								'type' 	=> 'checkbox',
								'title' => esc_html__( 'Disable Features', 'codevz' ),
								'options' => array(
									'zoom'		=> esc_html__( 'Hover zoom', 'codevz' ),
									'lightbox'	=> esc_html__( 'Lightbox', 'codevz' ),
									'slider'	=> esc_html__( 'Slider', 'codevz' ),
								),
							),
							array(
								'id' 	=> 'woo_product_tabs',
								'type' 	=> 'select',
								'title' => esc_html__( 'Product Tabs', 'codevz' ),
								'options' => array(
									''			=> esc_html__( '~ Default ~', 'codevz' ),
									'center'	=> esc_html__( 'Center', 'codevz' ),
									'vertical'	=> esc_html__( 'Vertical', 'codevz' ),
								),
							),
						),
					),

					array(
						'name'   => 'product_sk',
						'title'  => esc_html__( 'Product Styling', 'codevz' ),
						'fields' => array(

							array(
								'id' 			=> '_css_woo_product_container',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Container', 'codevz' ),
								'button' 		=> esc_html__( 'Container', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.woocommerce .xtra-single-product'
							),
							array(
								'id' 			=> '_css_woo_product_container_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .xtra-single-product'
							),
							array(
								'id' 			=> '_css_woo_product_container_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .xtra-single-product'
							),
							array(
								'id' 			=> '_css_woo_product_title',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Title', 'codevz' ),
								'button' 		=> esc_html__( 'Title', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'text-align', 'color', 'font-family', 'font-size' ),
								'selector' 		=> '.woocommerce div.product .product_title'
							),
							array(
								'id' 			=> '_css_woo_product_title_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .product_title'
							),
							array(
								'id' 			=> '_css_woo_product_title_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .product_title'
							),
							array(
								'id' 			=> '_css_woo_product_thumbnail',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Image', 'codevz' ),
								'button' 		=> esc_html__( 'Image', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.woocommerce div.product div.images img'
							),
							array(
								'id' 			=> '_css_woo_product_thumbnail_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product div.images img'
							),
							array(
								'id' 			=> '_css_woo_product_thumbnail_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product div.images img'
							),
							array(
								'id' 			=> '_css_woo_product_image_zoom',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Zoom Icon', 'codevz' ),
								'button' 		=> esc_html__( 'Zoom Icon', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.woocommerce div.product div.images .woocommerce-product-gallery__trigger'
							),
							array(
								'id' 			=> '_css_woo_product_image_zoom_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product div.images .woocommerce-product-gallery__trigger'
							),
							array(
								'id' 			=> '_css_woo_product_image_zoom_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product div.images .woocommerce-product-gallery__trigger'
							),
							array(
								'id' 			=> '_css_woo_product_stars',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Rating Stars', 'codevz' ),
								'button' 		=> esc_html__( 'Rating Stars', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'padding' ),
								'selector' 		=> '.woocommerce .woocommerce-product-rating .star-rating'
							),
							array(
								'id' 			=> '_css_woo_product_stars_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .woocommerce-product-rating .star-rating'
							),
							array(
								'id' 			=> '_css_woo_product_stars_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .woocommerce-product-rating .star-rating'
							),
							array(
								'id' 			=> '_css_woo_product_price',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Price', 'codevz' ),
								'button' 		=> esc_html__( 'Price', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-family', 'font-size' ),
								'selector' 		=> '.woocommerce div.product .summary p.price, .woocommerce div.product .summary span.price'
							),
							array(
								'id' 			=> '_css_woo_product_price_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .summary p.price, .woocommerce div.product .summary span.price'
							),
							array(
								'id' 			=> '_css_woo_product_price_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .summary p.price, .woocommerce div.product .summary span.price'
							),
							array(
								'id' 			=> '_css_woo_product_sale',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Sale Price', 'codevz' ),
								'button' 		=> esc_html__( 'Sale Price', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-family', 'font-size' ),
								'selector' 		=> '.woocommerce div.product .summary p.price del span, .woocommerce div.product .summary span.price del span'
							),
							array(
								'id' 			=> '_css_woo_product_sale_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .summary p.price del span, .woocommerce div.product .summary span.price del span'
							),
							array(
								'id' 			=> '_css_woo_product_sale_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .summary p.price del span, .woocommerce div.product .summary span.price del span'
							),
							array(
								'id' 			=> '_css_woo_product_onsale',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Sale Badge', 'codevz' ),
								'button' 		=> esc_html__( 'Sale Badge', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'line-height', 'width', 'height', 'color', 'background', 'font-size', 'top', 'left', 'border' ),
								'selector' 		=> '.woocommerce.single span.onsale'
							),
							array(
								'id' 			=> '_css_woo_product_onsale_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce.single span.onsale'
							),
							array(
								'id' 			=> '_css_woo_product_onsale_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce.single span.onsale'
							),
							array(
								'id' 			=> '_css_woo_product_oos',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Out of stock', 'codevz' ),
								'button' 		=> esc_html__( 'Out of stock', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size' ),
								'selector' 		=> '.woocommerce div.product .out-of-stock',
								'dependency' 	=> [ 'xxx', '==', 'xxx' ]
							),
							array(
								'id' 			=> '_css_woo_product_meta',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Meta text', 'codevz' ),
								'button' 		=> esc_html__( 'Meta text', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size' ),
								'selector' 		=> '.woocommerce div.product .posted_in'
							),
							array(
								'id' 			=> '_css_woo_product_meta_a',
								'hover_id' 		=> '_css_woo_product_meta_a_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Meta links', 'codevz' ),
								'button' 		=> esc_html__( 'Meta links', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size' ),
								'selector' 		=> '.woocommerce div.product .posted_in a'
							),
							array(
								'id' 			=> '_css_woo_product_meta_a_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .posted_in a:hover'
							),
							array(
								'id' 			=> '_css_woo_product_qty_down',
								'hover_id' 		=> '_css_woo_product_qty_down_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Qty Down', 'codevz' ),
								'button' 		=> esc_html__( 'Qty Down', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
								'selector' 		=> '.quantity-down'
							),
							array(
								'id' 			=> '_css_woo_product_qty_down_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.quantity-down'
							),
							array(
								'id' 			=> '_css_woo_product_qty_down_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.quantity-down'
							),
							array(
								'id' 			=> '_css_woo_product_qty_down_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.quantity-down:hover'
							),
							array(
								'id' 			=> '_css_woo_product_qty_up',
								'hover_id' 		=> '_css_woo_product_qty_up_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Qty Up', 'codevz' ),
								'button' 		=> esc_html__( 'Qty Up', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
								'selector' 		=> '.quantity-up'
							),
							array(
								'id' 			=> '_css_woo_product_qty_up_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.quantity-up'
							),
							array(
								'id' 			=> '_css_woo_product_qty_up_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.quantity-up'
							),
							array(
								'id' 			=> '_css_woo_product_qty_up_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.quantity-up:hover'
							),
							array(
								'id' 			=> '_css_woo_product_qty',
								'hover_id' 		=> '_css_woo_product_qty_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Qty Input', 'codevz' ),
								'button' 		=> esc_html__( 'Qty Input', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'input', 'background', 'border' ),
								'selector' 		=> '.woocommerce .quantity .qty'
							),
							array(
								'id' 			=> '_css_woo_product_qty_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .quantity .qty'
							),
							array(
								'id' 			=> '_css_woo_product_qty_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .quantity .qty'
							),
							array(
								'id' 			=> '_css_woo_product_qty_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .quantity .qty:hover'
							),
							array(
								'id' 			=> '_css_woo_product_add_to_cart',
								'hover_id' 		=> '_css_woo_product_add_to_cart_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Add to cart', 'codevz' ),
								'button' 		=> esc_html__( 'Add to cart', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'opacity', 'float', 'background', 'border' ),
								'selector' 		=> '.woocommerce div.product form.cart .button'
							),
							array(
								'id' 			=> '_css_woo_product_add_to_cart_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product form.cart .button'
							),
							array(
								'id' 			=> '_css_woo_product_add_to_cart_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product form.cart .button'
							),
							array(
								'id' 			=> '_css_woo_product_add_to_cart_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product form.cart .button:hover'
							),
							array(
								'id' 			=> '_css_woo_product_wishlist',
								'hover_id' 		=> '_css_woo_product_wishlist_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Wishlist', 'codevz' ),
								'button' 		=> esc_html__( 'Wishlist', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'font-size', 'background', 'border', 'box-shadow' ),
								'selector' 		=> '.woocommerce .cart .xtra-product-icons'
							),
							array(
								'id' 			=> '_css_woo_product_wishlist_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .cart .xtra-product-icons'
							),
							array(
								'id' 			=> '_css_woo_product_wishlist_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .cart .xtra-product-icons'
							),
							array(
								'id' 			=> '_css_woo_product_wishlist_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce .cart .xtra-product-icons:hover'
							),
							
							array(
								'id' 			=> '_css_woo_product_tabs',
								'hover_id' 		=> '_css_woo_product_tabs_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Tabs', 'codevz' ),
								'button' 		=> esc_html__( 'Tabs', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs ul.tabs li'
							),
							array(
								'id' 			=> '_css_woo_product_tabs_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs ul.tabs li:hover'
							),
							array(
								'id' 			=> '_css_woo_product_tabs_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs ul.tabs li'
							),
							array(
								'id' 			=> '_css_woo_product_tabs_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs ul.tabs li'
							),

							array(
								'id' 			=> '_css_woo_product_active_tab',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Active Tab', 'codevz' ),
								'button' 		=> esc_html__( 'Active Tab', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs ul.tabs li.active'
							),
							array(
								'id' 			=> '_css_woo_product_active_tab_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs ul.tabs li.active'
							),
							array(
								'id' 			=> '_css_woo_product_active_tab_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs ul.tabs li.active'
							),
							array(
								'id' 			=> '_css_woo_product_tab_content',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Tab Content', 'codevz' ),
								'button' 		=> esc_html__( 'Tab Content', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'font-size', 'border' ),
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs .panel'
							),
							array(
								'id' 			=> '_css_woo_product_tab_content_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs .panel'
							),
							array(
								'id' 			=> '_css_woo_product_tab_content_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.woocommerce div.product .woocommerce-tabs .panel'
							),
						)
					)
				)
			);
		}

		// BuddyPress options
		if ( function_exists( 'is_buddypress' ) || $all ) {
			$options[] = array(
				'name'   => 'post_type_buddypress',
				'title'  => esc_html__( 'Buddy Press', 'codevz' ),
				'fields' => wp_parse_args( 
					array(
						array(
							'id' 			=> 'layout_buddypress',
							'type' 			=> 'codevz_image_select',
							'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
							'options' 		=> [
								'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
								'ws' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
								'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
								'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
								'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
								'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
								'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
								'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
								'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
								'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
								'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
								'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
								'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
								'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
							],
							'default' 		=> '1'
						),
					),
					self::title_options( '_buddypress', '.cz-cpt-buddypress ' )
				)
			);
		}

		// EDD options
		if ( function_exists( 'EDD' ) || $all ) {
			$options[] = array(
				'name'   => 'post_type_download',
				'title'  => esc_html__( 'Easy Digital Download', 'codevz' ),
				'sections'  => array(

					array(
						'name'   => 'edd_settings',
						'title'  => esc_html__( 'EDD Settings', 'codevz' ),
						'fields' => wp_parse_args(
							array(
								array(
									'id' 			=> 'layout_download',
									'type' 			=> 'codevz_image_select',
									'title' 		=> esc_html__( 'Sidebar', 'codevz' ),
									'options' 		=> [
										'1' 			=> [ esc_html__( '~ Default ~', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-0.png' ],
										'ws' 			=> [ esc_html__( 'No Sidebar', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/off.png' ],
										'bpnp' 			=> [ esc_html__( 'Fullwidth', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-2.png' ],
										'center'		=> [ esc_html__( 'Center Mode', 'codevz' ) 			, Codevz_Plus::$url . 'assets/img/sidebar-13.png' ],
										'right' 		=> [ esc_html__( 'Right Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-3.png' ],
										'right-s' 		=> [ esc_html__( 'Right Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-4.png' ],
										'left' 			=> [ esc_html__( 'Left Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-5.png' ],
										'left-s' 		=> [ esc_html__( 'Left Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-6.png' ],
										'both-side' 	=> [ esc_html__( 'Both Sidebar', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/sidebar-7.png' ],
										'both-side2' 	=> [ esc_html__( 'Both Sidebar Small', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-8.png' ],
										'both-right' 	=> [ esc_html__( 'Both Sidebar Right', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-9.png' ],
										'both-right2' 	=> [ esc_html__( 'Both Sidebar Right 2', 'codevz' ) , Codevz_Plus::$url . 'assets/img/sidebar-10.png' ],
										'both-left' 	=> [ esc_html__( 'Both Sidebar Left', 'codevz' ) 	, Codevz_Plus::$url . 'assets/img/sidebar-11.png' ],
										'both-left2' 	=> [ esc_html__( 'Both Sidebar Left 2', 'codevz' )  , Codevz_Plus::$url . 'assets/img/sidebar-12.png' ],
									],
									'default' 		=> '1'
								),
								array(
									'id' 			=> 'edd_col',
									'type' 			=> 'codevz_image_select',
									'title' 		=> esc_html__( 'Columns', 'codevz' ),
									'options' 		=> [
										'2' 			=> [ '2 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-2.png' ],
										'3' 			=> [ '3 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-3.png' ],
										'4' 			=> [ '4 ' . esc_html__( 'Columns', 'codevz' ) 		, Codevz_Plus::$url . 'assets/img/cols-4.png' ],
									],
									'default' 		=> '3'
								),
							),
							self::title_options( '_download', '.cz-cpt-download ' )
						)
					),

					array(
						'name'   => 'edd_styles',
						'title'  => esc_html__( 'EDD Styling', 'codevz' ),
						'fields' => array(
							array(
								'id' 			=> '_css_edd_products',
								'hover_id' 		=> '_css_edd_products_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Product', 'codevz' ),
								'button' 		=> esc_html__( 'Product', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.cz_edd_item > article'
							),
							array(
								'id' 			=> '_css_edd_products_tablet',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz_edd_item > article'
							),
							array(
								'id' 			=> '_css_edd_products_mobile',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz_edd_item > article'
							),
							array(
								'id' 			=> '_css_edd_products_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz_edd_item > article:hover'
							),
							array(
								'id' 			=> '_css_edd_products_img',
								'hover_id' 		=> '_css_edd_products_img_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Image', 'codevz' ),
								'button' 		=> esc_html__( 'Image', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'background', 'padding', 'border' ),
								'selector' 		=> '.cz_edd_item .cz_edd_image'
							),
							array(
								'id' 			=> '_css_edd_products_img_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz_edd_item > article:hover .cz_edd_image'
							),
							array(
								'id' 			=> '_css_edd_products_price',
								'hover_id' 		=> '_css_edd_products_price_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Price', 'codevz' ),
								'button' 		=> esc_html__( 'Price', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'font-size', 'font-weight', 'color', 'background', 'padding', 'border' ),
								'selector' 		=> '.cz_edd_item .edd_price'
							),
							array(
								'id' 			=> '_css_edd_products_price_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz_edd_item > article:hover .edd_price'
							),
							array(
								'id' 			=> '_css_edd_products_title',
								'hover_id' 		=> '_css_edd_products_title_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Title', 'codevz' ),
								'button' 		=> esc_html__( 'Title', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'font-size', 'font-weight', 'color', 'background', 'padding', 'border' ),
								'selector' 		=> '.cz_edd_title h3'
							),
							array(
								'id' 			=> '_css_edd_products_title_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz_edd_title h3:hover'
							),
							array(
								'id' 			=> '_css_edd_products_button',
								'hover_id' 		=> '_css_edd_products_button_hover',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Button', 'codevz' ),
								'button' 		=> esc_html__( 'Button', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'font-size', 'font-weight', 'color', 'background', 'padding', 'border' ),
								'selector' 		=> '.cz_edd_item a.edd-submit, .cz_edd_item .edd-submit.button.blue'
							),
							array(
								'id' 			=> '_css_edd_products_button_hover',
								'type' 			=> 'cz_sk_hidden',
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'selector' 		=> '.cz_edd_item a.edd-submit:hover, .cz_edd_item .edd-submit.button.blue:hover, .edd-submit.button.blue:focus'
							),
							array(
								'id' 			=> '_css_edd_products_purchase_options',
								'type' 			=> 'cz_sk',
								'title' 		=> esc_html__( 'Options', 'codevz' ),
								'button' 		=> esc_html__( 'Options', 'codevz' ),
								'setting_args' 	=> [ 'transport' => 'postMessage' ],
								'settings' 		=> array( 'color', 'background', 'padding', 'border' ),
								'selector' 		=> '.cz_edd_container .edd_price_options'
							),

						)
					)
				)
			);
		}

		$options[] = array(
			'name'   => 'backup_section',
			'title'  => esc_html__( 'Backup / Reset', 'codevz' ),
			'priority' => 900,
			'fields' => array(
				array(
					'type' => 'backup'
				),
			)
		);

		/*
		$ids = array();
		foreach ( $options['header']['sections'] as $key ) {
			foreach ( $key['fields'] as $k ) {
				if ( ! empty( $k['id'] ) 
					&& $k['id'] !== 'logo' 
					&& $k['id'] !== '_css_logo_css' 
					&& $k['id'] !== '_css_logo_css_tablet' 
					&& $k['id'] !== '_css_logo_css_mobile' 
					&& $k['id'] !== 'logo_2' 
					&& $k['id'] !== '_css_logo_2_css' 
					&& $k['id'] !== '_css_logo_2_css_tablet' 
					&& $k['id'] !== '_css_logo_2_css_mobile' 
					&& $k['id'] !== 'logo_hover_tooltip' 
					&& $k['id'] !== '_css_logo_hover_tooltip' 
					&& $k['id'] !== '_css_logo_hover_tooltip_tablet' 
					&& $k['id'] !== '_css_logo_hover_tooltip_mobile' 
					&& $k['id'] !== 'social' 
				) {
					$ids[ $k['id'] ] = '';
				}
			}
		}
		var_export( $ids );
		*/

		return $options;
	}

	/**
	 *
	 * Get CSS selector via option ID
	 * 
	 * @return string
	 *
	 */
	public static function get_selector( $i = '', $s = [] ) {

		// Current file size.
		$filesize = filesize( __FILE__ );

		// selectors array.
		$s = get_option( 'xtra_cache_selectors' );

		// Cache selectors array as a option.
		if ( $filesize != get_option( 'xtra_size_selectors' ) || ! $s ) {

			// Generate ID's for live customizer JS
			foreach( self::options( true ) as $option ) {
				if ( ! empty( $option['sections'] ) ) {
					foreach ( $option['sections'] as $section ) {
						if ( ! empty( $section['fields'] ) ) {
							foreach( $section['fields'] as $field ) {
								if ( ! empty( $field['id'] ) && ! empty( $field['selector'] ) ) {
									$s[ $field['id'] ] = $field['selector'];
								}
							}
						}
					}
				} else {
					if ( ! empty( $option['fields'] ) ) {
						foreach( $option['fields'] as $field ) {
							if ( ! empty( $field['id'] ) && ! empty( $field['selector'] ) ) {
								$s[ $field['id'] ] =  $field['selector'];
							}
						}
					}
				}
			}

			update_option( 'xtra_cache_selectors', $s );

			update_option( 'xtra_size_selectors', $filesize );

		}

		return ( $i === 'all' ) ? $s : ( isset( $s[ $i ] ) ? $s[ $i ] : '' );
	}

	/**
	 *
	 * General help texts for options
	 * 
	 * @return array
	 *
	 */
	public static function help( $i ) {

		$o = array(
			'4'				=> 'e.g. 10px 10px 10px 10px',
			'px'			=> 'e.g. 30px',
			'padding'		=> esc_html__( 'Space around an element, INSIDE of any defined margins and borders. Can set using px, %, em, ...', 'codevz' ),
			'margin'		=> esc_html__( 'Space around an element, OUTSIDE of any defined borders. Can set using px, %, em, auto, ...', 'codevz' ),
			'border'		=> esc_html__( 'Lines around element, e.g. 2px or manually set with this four positions respectively: <br />Top Right Bottom Left <br/><br/>e.g. 2px 2px 2px 2px', 'codevz' ),
			'radius'		=> esc_html__( 'Generate the arc for lines around element, e.g. 10px or manually set with this four positions respectively: <br />Top Right Bottom Left <br/><br/>e.g. 10px 10px 10px 10px', 'codevz' ),
			'default'		=> esc_html__( 'Default option', 'codevz' ),
		);

		return isset( $o[ $i ] ) ? $o[ $i ] : '';
	}

	/**
	 *
	 * Header builder elements
	 * 
	 * @return array
	 *
	 */
	public static function elements( $id, $title, $dependency = array(), $pos = '' ) {

		$is_fixed_side = Codevz_Plus::contains( $id, 'side' );
		$is_1_2_3 = Codevz_Plus::contains( $id, array( 'header_1', 'header_2', 'header_3' ) );
		$is_footer = Codevz_Plus::contains( $id, 'footer' );

		return array(
			'id'              => $id,
			'type'            => 'group',
			'title'           => $title,
			'button_title'    => esc_html__( 'Add', 'codevz' ) . ' ' . ucwords( self::$trasnlation[ $pos ] ),
			'accordion_title' => esc_html__( 'Add', 'codevz' ) . ' ' . ucwords( self::$trasnlation[ $pos ] ),
			'dependency'	  => $dependency,
			'setting_args' 	  => [ 'transport' => 'postMessage' ],
			'fields'          => array(

				array(
					'id' 	=> 'element',
					'type' 	=> 'select',
					'title' => esc_html__( 'Element', 'codevz' ),
					'options' => array(
						'logo' 		=> esc_html__( 'Logo', 'codevz' ),
						'logo_2' 	=> esc_html__( 'Logo Alternative', 'codevz' ),
						'menu' 		=> esc_html__( 'Menu', 'codevz' ),
						'social' 	=> esc_html__( 'Social Icons', 'codevz' ),
						'icon' 		=> esc_html__( 'Icon and text', 'codevz' ),
						'icon_info' => esc_html__( 'Icon and text 2', 'codevz' ),
						'search' 	=> esc_html__( 'Search', 'codevz' ),
						'line' 		=> esc_html__( 'Line', 'codevz' ),
						'button' 	=> esc_html__( 'Button', 'codevz' ),
						'image' 	=> esc_html__( 'Image', 'codevz' ),
						'shop_cart' => esc_html__( 'Shopping cart', 'codevz' ),
						'wishlist'  => esc_html__( 'Wishlist', 'codevz' ),
						'wpml' 		=> esc_html__( 'WPML Selector', 'codevz' ),
						'widgets' 	=> esc_html__( 'Offcanvas Sidebar', 'codevz' ),
						'hf_elm' 	=> esc_html__( 'Dropdown Content', 'codevz' ),
						//'login' 	=> esc_html__( 'Login Box', 'codevz' ),
						'avatar' 	=> esc_html__( 'Logged-in user avatar', 'codevz' ),
						'custom' 	=> esc_html__( 'Custom Shortcode', 'codevz' ),
						'custom_element' => esc_html__( 'Custom Page', 'codevz' ),
					),
					'default_option' => esc_html__( '~ Select ~', 'codevz'),
				),

				// Element ID for live customize
				array(
					'id'   		 => 'element_id',
					'title'   	 => 'ID',
					'type'       => 'text',
					'default'    => $id,
					'dependency' => array( 'xxx', '==', 'xxx' ),
				),

				// Custom
				array(
					'id' 			=> 'header_elements',
					'type' 			=> 'select',
					'title'			=> esc_html__( 'Content', 'codevz' ),
					'options' 		=> Codevz_Plus::$array_pages,
					'dependency' 	=> array( 'element', '==', 'custom_element' ),
				),
				array(
					'id'    		=> 'header_elements_width',
					'type'  		=> 'slider',
					'title' 		=> esc_html__( 'Size', 'codevz' ),
					'options'		=> array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 800 ),
					'dependency' 	=> array( 'element', '==', 'custom_element' )
				),

				// Custom
				array(
					'id'    		=> 'custom',
					'type'  		=> 'textarea',
					'title' 		=> esc_html__( 'Custom Shortcode', 'codevz' ),
					'default' 		=> 'Insert shortcode or HTML',
					'dependency' 	=> array( 'element', '==', 'custom' ),
				),

				// Logo
				array(
					'id'    => 'logo_width',
					'type'  => 'slider',
					'title' => esc_html__( 'Size', 'codevz' ),
					'options'	=> array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 500 ),
					'dependency' => array( 'element', 'any', 'logo,logo_2' ),
				),
				array(
					'id'    => 'logo_width_sticky',
					'type'  => 'slider',
					'title' => esc_html__( 'Sticky Size', 'codevz' ),
					'options'	=> array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 500 ),
					'dependency' => array( 'element', 'any', 'logo,logo_2' ),
				),

				// Menu
				array(
					'id' 		=> 'menu_location',
					'type' 		=> 'select',
					'title' 	=> esc_html__( 'Menu', 'codevz' ),
					'help' 		=> esc_html__( 'To add or edit menus go to Appearance > Menus', 'codevz' ),
					'options' 	=> array(
						'' 			=> esc_html__( '~ Select ~', 'codevz' ), 
						'primary' 	=> esc_html__( 'Primary', 'codevz' ), 
						'secondary' => esc_html__( 'Secondary', 'codevz' ), 
						'one-page'  => esc_html__( 'One Page', 'codevz' ), 
						'footer'  	=> esc_html__( 'Footer', 'codevz' ),
						'mobile'  	=> esc_html__( 'Mobile', 'codevz' ),
						'custom-1' 	=> esc_html__( 'Custom 1', 'codevz' ), 
						'custom-2' 	=> esc_html__( 'Custom 2', 'codevz' ), 
						'custom-3' 	=> esc_html__( 'Custom 3', 'codevz' ),
						'custom-4' 	=> esc_html__( 'Custom 4', 'codevz' ),
						'custom-5' 	=> esc_html__( 'Custom 5', 'codevz' ),
						'custom-6' 	=> esc_html__( 'Custom 6', 'codevz' ),
						'custom-7' 	=> esc_html__( 'Custom 7', 'codevz' ),
						'custom-8' 	=> esc_html__( 'Custom 8', 'codevz' )
					),
					'dependency' => array( 'element', '==', 'menu' ),
				),
				array(
					'id'    => 'menu_type',
					'type'  => 'select',
					'title' => esc_html__( 'Type', 'codevz' ),
					'options' 	=> array(
						'' 							   => esc_html__( '~ Default ~', 'codevz' ),
						'offcanvas_menu_left' 		   => esc_html__( 'Offcanvas left', 'codevz' ),
						'offcanvas_menu_right' 		   => esc_html__( 'Offcanvas right', 'codevz' ),
						'fullscreen_menu' 			   => esc_html__( 'Full screen', 'codevz' ),
						'dropdown_menu' 			   => esc_html__( 'Dropdown', 'codevz' ),
						'open_horizontal inview_left'  => esc_html__( 'Sliding menu left', 'codevz' ),
						'open_horizontal inview_right' => esc_html__( 'Sliding menu right', 'codevz' ),
						'left_side_dots side_dots' 	   => esc_html__( 'Vertical dots left', 'codevz' ),
						'right_side_dots side_dots'    => esc_html__( 'Vertical dots right', 'codevz' ),
					),
					'dependency' => array( 'element', '==', 'menu' ),
				),
				array(
					'id'    		=> 'menu_icon',
					'type'  		=> 'icon',
					'title' 		=> esc_html__( 'Icon', 'codevz' ),
					'dependency' 	=> array( 'element|menu_type', '==|any', 'menu|offcanvas_menu_left,offcanvas_menu_right,fullscreen_menu,dropdown_menu,open_horizontal inview_left,open_horizontal inview_right' ),
				),
				array(
					'id'    		=> 'menu_title',
					'type'  		=> 'text',
					'title' 		=> esc_html__( 'Title', 'codevz' ),
					'dependency' 	=> array( 'element|menu_type', 'any|any', 'menu,widgets|offcanvas_menu_left,offcanvas_menu_right,fullscreen_menu,dropdown_menu,open_horizontal inview_left,open_horizontal inview_right' ),
				),
				array(
					'id' 			=> 'sk_menu_icon',
					'hover_id' 		=> 'sk_menu_icon_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'button' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-size', 'color', 'background', 'border' ),
					'dependency' 	=> array( 'element|menu_type', '==|any', 'menu|offcanvas_menu_left,offcanvas_menu_right,fullscreen_menu,dropdown_menu,open_horizontal inview_left,open_horizontal inview_right' ),
				),
				array( 'id' => 'sk_menu_icon_hover', 'type' => 'cz_sk_hidden' ),
				array(
					'id' 			=> 'sk_menu_title',
					'hover_id' 		=> 'sk_menu_title_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Title Style', 'codevz' ),
					'button' 		=> esc_html__( 'Title Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-size', 'font-family', 'color', 'background', 'border' ),
					'dependency' 	=> array( 'element|menu_title', 'any|!=', 'menu,widgets|' ),
				),
				array( 'id' => 'sk_menu_title_hover', 'type' => 'cz_sk_hidden' ),
				array(
					'id' 			=> 'menu_disable_dots',
					'type' 			=> 'switcher',
					'title'			=> esc_html__( 'Disable Dots', 'codevz' ),
					'dependency' 	=> array( 'element|menu_type', '==|!=', 'menu|offcanvas_menu_left,offcanvas_menu_right,fullscreen_menu,dropdown_menu,open_horizontal inview_left,open_horizontal inview_right,left_side_dots side_dots,right_side_dots side_dots' ),
				),

				// Social
				array(
					'type'    		=> 'content',
					'content' 		=> '<a href="#" onclick="wp.customize.section( \'codevz_theme_options-header_social\' ).focus()" class="button xtra-goto">' . esc_html__( 'Social Icons Manager', 'codevz' ) . '</a>',
					'dependency' 	=> array( 'element', '==', 'social' ),
				),
				array(
					'id'    => 'social_type',
					'type'  => 'select',
					'title' => esc_html__( 'Type', 'codevz' ),
					'options' 	=> array(
						'' 				=> esc_html__( '~ Default ~', 'codevz' ),
						'popup' 		=> esc_html__( 'Popup', 'codevz' ),
						'dropdown' 		=> esc_html__( 'Dropdown', 'codevz' ),
					),
					'dependency' => array( 'element', '==', 'social' ),
				),
				array(
					'id' 			=> 'social_columnar',
					'type' 			=> 'switcher',
					'title' 		=> esc_html__( 'Columnar', 'codevz' ),
					'dependency' 	=> array( 'element', '==', 'social' ),
				),
				array(
					'id' 			=> 'social_icon',
					'type' 			=> 'icon',
					'title' 		=> esc_html__( 'Icon', 'codevz' ),
					'dependency' 	=> [ 'element|social_type', '==|!=', 'social|' ],
				),
				array(
					'id' 			=> 'sk_social_icon',
					'hover_id' 		=> 'sk_social_icon_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Icon', 'codevz' ),
					'button' 		=> esc_html__( 'Icon', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> [ 'font-size', 'color', 'background', 'border' ],
					'dependency' 	=> [ 'element|social_type', '==|!=', 'social|' ],
				),
				array( 'id' => 'sk_social_icon_hover', 'type' => 'cz_sk_hidden' ),
				array(
					'id' 			=> 'sk_social_container',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Container', 'codevz' ),
					'button' 		=> esc_html__( 'Container', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'border' ),
					'dependency' 	=> array( 'element|social_type', '==|!=', 'social|' ),
				),

				// Image
				array(
					'id'    => 'image',
					'type'  => 'upload',
					'title' => esc_html__( 'Image', 'codevz' ),
					'preview'       => 1,
					'dependency' => array( 'element', '==', 'image' ),
					'attributes' => array(
						'style'		=> 'display: block'
					)
				),
				array(
					'id'    => 'image_width',
					'type'  => 'slider',
					'title' => esc_html__( 'Size', 'codevz' ),
					'options'	=> array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 800 ),
					'dependency' => array( 'element', '==', 'image' ),
				),
				array(
					'id'    => 'image_link',
					'type'  => 'text',
					'title' => esc_html__( 'Link', 'codevz' ),
					'dependency' => array( 'element', '==', 'image' ),
				),
				array(
					'id' 	=> 'image_new_tab',
					'type' 	=> 'switcher',
					'title' => esc_html__( 'New Tab?' ),
					'dependency' => array( 'element', '==', 'image' ),
				),

				// Icon & Text
				array(
					'id'    => 'it_icon',
					'type'  => 'icon',
					'title' => esc_html__( 'Icon', 'codevz' ),
					'dependency' => array( 'element', 'any', 'icon,icon_info' ),
				),
				array(
					'id'    		=> 'it_text',
					'type'  		=> 'textarea',
					'title' 		=> esc_html__( 'Text', 'codevz' ),
					'default'  		=> esc_html__( 'I am a text', 'codevz' ),
					'help'  		=> esc_html__( 'Instead current year you can use [codevz_year]', 'codevz' ),
					'dependency' 	=> array( 'element', 'any', 'icon,icon_info' ),
				),
				array(
					'id'    		=> 'it_text_2',
					'type'  		=> 'textarea',
					'title' 		=> esc_html__( 'Text 2', 'codevz' ),
					'default'  		=> esc_html__( 'I am text 2', 'codevz' ),
					'help'  		=> esc_html__( 'Instead current year you can use [codevz_year]', 'codevz' ),
					'dependency' 	=> array( 'element', '==', 'icon_info' ),
				),
				array(
					'id' 			=> 'it_link',
					'type' 			=> 'text',
					'title' 		=> esc_html__( 'Link', 'codevz' ),
					'dependency' 	=> array( 'element', 'any', 'icon,icon_info' ),
				),
				array(
					'id' 			=> 'it_link_target',
					'type' 			=> 'switcher',
					'title' 		=> esc_html__( 'New Tab?', 'codevz' ),
					'dependency' 	=> array( 'element', 'any', 'icon,icon_info' ),
				),
				array(
					'id' 			=> 'sk_it_wrap',
					'hover_id' 		=> 'sk_it_wrap_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Wrap Style', 'codevz' ),
					'button' 		=> esc_html__( 'Wrap Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'dependency' 	=> array( 'element', 'any', 'icon_info' )
				),
				array( 'id' => 'sk_it_wrap_hover', 'type' => 'cz_sk_hidden' ),
				array(
					'id' 			=> 'sk_it',
					'hover_id' 		=> 'sk_it_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Text Style', 'codevz' ),
					'button' 		=> esc_html__( 'Text Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-size', 'color', 'background' ),
					'dependency' 	=> array( 'element', 'any', 'icon,icon_info' )
				),
				array( 'id' => 'sk_it_hover', 'type' => 'cz_sk_hidden' ),
				array(
					'id' 			=> 'sk_it_2',
					'hover_id' 		=> 'sk_it_2_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Text 2 Style', 'codevz' ),
					'button' 		=> esc_html__( 'Text 2 Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-size', 'color' ),
					'dependency' 	=> array( 'element', '==', 'icon_info' )
				),
				array( 'id' => 'sk_it_2_hover', 'type' => 'cz_sk_hidden' ),
				array(
					'id' 			=> 'sk_it_icon',
					'hover_id' 		=> 'sk_it_icon_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'button' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-size', 'color', 'background', 'border' ),
					'dependency' 	=> array( 'element', 'any', 'icon,icon_info' )
				),
				array('id' => 'sk_it_icon_hover','type' => 'cz_sk_hidden'),

				// Search
				array(
					'id' 	=> 'search_type',
					'type' 	=> 'select',
					'title' => esc_html__( 'Type', 'codevz' ),
					'options' 	=> array(
						'icon_dropdown' => esc_html__( 'Dropdown', 'codevz' ),
						'form' 			=> esc_html__( 'Form', 'codevz' ),
						'form_2' 		=> esc_html__( 'Form', 'codevz' ) . ' 2',
						'icon_full' 	=> esc_html__( 'Full screen', 'codevz' ),
					),
					'dependency' => array( 'element', '==', 'search' ),
				),
				array(
					'id'    => 'search_icon',
					'type'  => 'icon',
					'title' => esc_html__( 'Icon', 'codevz' ),
					'dependency' => array( 'element', '==', 'search' ),
				),
				array(
					'id'    => 'search_placeholder',
					'type'  => 'text',
					'title' => esc_html__( 'Title', 'codevz' ),
					'dependency' => array( 'element', '==', 'search' ),
				),
				array(
					'id' 			=> 'sk_search_title',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Title Style', 'codevz' ),
					'button' 		=> esc_html__( 'Title Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-size', 'color' ),
					'dependency' 	=> array( 'element|search_type', '==|==', 'search|icon_full' )
				),
				array(
					'id'    => 'search_form_width',
					'type'  => 'slider',
					'title' => esc_html__( 'Size', 'codevz' ),
					'options' => array( 'unit' => 'px', 'step' => 1, 'min' => 100, 'max' => 500 ),
					'dependency' => array( 'element|search_type', '==|any', 'search|form,form_2' ),
				),
				array(
					'id' 			=> 'sk_search_con',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Search', 'codevz' ),
					'button' 		=> esc_html__( 'Search', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'dependency' 	=> array( 'element', '==', 'search' ),
				),
				array(
					'id' 			=> 'sk_search_input',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Search Input', 'codevz' ),
					'button' 		=> esc_html__( 'Search Input', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-size', 'color', 'background', 'border' ),
					'dependency' 	=> array( 'element', '==', 'search' )
				),
				array(
					'id' 			=> 'sk_search_icon',
					'hover_id' 		=> 'sk_search_icon_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Search Icon', 'codevz' ),
					'button' 		=> esc_html__( 'Search Icon', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-size', 'color', 'background', 'border' ),
					'dependency' 	=> array( 'element|search_type', '==|any', 'search|icon_dropdown,icon_full,icon_fullrow' ),
				),
				array( 'id' => 'sk_search_icon_hover','type' => 'cz_sk_hidden' ),
				array(
					'id' 			=> 'sk_search_icon_in',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Input Icon', 'codevz' ),
					'button' 		=> esc_html__( 'Input Icon', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'font-size', 'color', 'background', 'border' ),
					'dependency' 	=> array( 'element', '==', 'search' ),
				),
				array(
					'id' 		=> 'search_only_products',
					'type' 		=> 'switcher',
					'title'		=> esc_html__( 'Only products?', 'codevz' ),
					'dependency' => array( 'element', '==', 'search' ),
				),
				array(
					'id' 		=> 'ajax_search',
					'type' 		=> 'switcher',
					'title'		=> esc_html__( 'Ajax Search', 'codevz' ),
					'help'		=> esc_html__( 'Go to Blog > Search Settings and config search query', 'codevz' ),
					'dependency' => array( 'element', '==', 'search' ),
				),
				array(
					'id' 		=> 'search_no_thumbnail',
					'type' 		=> 'switcher',
					'title'		=> esc_html__( 'No Image', 'codevz' ),
					'dependency' => array( 'ajax_search|element', '!=|==', '|search' ),
				),
				array(
					'id' 		=> 'search_post_icon',
					'type' 		=> 'icon',
					'title'		=> esc_html__( 'Icon', 'codevz' ),
					'help'		=> esc_html__( 'Icon for posts without image', 'codevz' ),
					'dependency' => array( 'ajax_search|element', '!=|==', '|search' ),
				),
				array(
					'id' 			=> 'sk_search_ajax',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Container', 'codevz' ),
					'button' 		=> esc_html__( 'Container', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'dependency' => array( 'ajax_search|element', '!=|==', '|search' ),
				),
				array(
					'id' 			=> 'sk_search_post_icon',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Posts Icon', 'codevz' ),
					'button' 		=> esc_html__( 'Posts Icon', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'border' ),
					'dependency' => array( 'ajax_search|element', '!=|==', '|search' ),
				),

				// Offcanvas
				array(
					'id' 		=> 'inview_position_widget',
					'type' 		=> 'select',
					'title' 	=> esc_html__( 'Position', 'codevz' ),
					'help' 		=> esc_html__( 'For adding or changing widgets in offcanvas area, go to Appearance > Widgets > Offcanvas', 'codevz' ),
					'options' 	=> array(
						'inview_left' 	=> esc_html__( 'Left', 'codevz' ),
						'inview_right' => esc_html__( 'Right', 'codevz' ),
					),
					'dependency' => array( 'element', '==', 'widgets' ),
				),
				array(
					'id' 			=> 'sk_offcanvas',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Offcanvas', 'codevz' ),
					'button' 		=> esc_html__( 'Offcanvas', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'dependency' 	=> array( 'element', '==', 'widgets' )
				),
				array(
					'id'    => 'offcanvas_icon',
					'type'  => 'icon',
					'title' => esc_html__( 'Icon', 'codevz' ),
					'dependency' => array( 'element', '==', 'widgets' ),
				),
				array(
					'id' 			=> 'sk_offcanvas_icon',
					'hover_id' 		=> 'sk_offcanvas_icon_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'button' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
					'dependency' 	=> array( 'element', '==', 'widgets' )
				),
				array('id' => 'sk_offcanvas_icon_hover','type' => 'cz_sk_hidden'),

				// Button options
				array(
					'id'    	=> 'btn_title',
					'type'  	=> 'text',
					'title' 	=> esc_html__( 'Title', 'codevz' ),
					'default' 	=> esc_html__( 'Button title', 'codevz' ),
					'dependency' => array( 'element', '==', 'button' ),
				),
				array(
					'id'    => 'btn_link',
					'type'  => 'text',
					'title' => esc_html__( 'Link', 'codevz' ),
					'dependency' => array( 'element', '==', 'button' ),
				),
				array(
					'id' 			=> 'btn_link_target',
					'type' 			=> 'switcher',
					'title' 		=> esc_html__( 'New Tab?', 'codevz' ),
					'dependency' 	=> array( 'element', '==', 'button' ),
				),
				array(
					'id' 			=> 'sk_btn',
					'hover_id' 		=> 'sk_btn_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Button Style', 'codevz' ),
					'button' 		=> esc_html__( 'Button Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-size', 'font-family', 'font-weight', 'background', 'border' ),
					'dependency' 	=> array( 'element', '==', 'button' )
				),
				array('id' => 'sk_btn_hover','type' => 'cz_sk_hidden'),

				// Hidden fullwidth content area
				array(
					'id' 			=> 'hf_elm_page',
					'type' 			=> 'select',
					'title'			=> esc_html__( 'Content', 'codevz' ),
					'help' 			=> esc_html__( 'You can create a new page from Dashboard > Page and assign it here', 'codevz' ),
					'options' 		=> Codevz_Plus::$array_pages,
					'dependency' 	=> array( 'element', '==', 'hf_elm' ),
				),
				array(
					'id' 			=> 'sk_hf_elm',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Container', 'codevz' ),
					'button' 		=> esc_html__( 'Container', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'dependency' 	=> array( 'element', '==', 'hf_elm' )
				),
				array(
					'id'    => 'hf_elm_icon',
					'type'  => 'icon',
					'title' => esc_html__( 'Icon', 'codevz' ),
					'dependency' => array( 'element', 'any', 'hf_elm,button' ),
				),
				array(
					'id' 			=> 'sk_hf_elm_icon',
					'hover_id' 		=> 'sk_hf_elm_icon_hover',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'button' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'border' ),
					'dependency' 	=> array( 'element', 'any', 'hf_elm,button' ),
				),
				array( 'id' => 'sk_hf_elm_icon_hover', 'type' => 'cz_sk_hidden' ),

				// Button icon position
				array(
					'id' 		=> 'btn_icon_pos',
					'type' 		=> 'select',
					'title' 	=> esc_html__( 'Position', 'codevz' ),
					'options' 	=> array(
						'' 			=> esc_html__( 'Before Title', 'codevz' ),
						'after' 	=> esc_html__( 'After Title', 'codevz' ),
					),
					'dependency' => array( 'element', '==', 'button' ),
				),

				// Shop
				array(
					'id' 		=> 'shop_plugin',
					'type' 		=> 'select',
					'title' 	=> esc_html__( 'Plugin', 'codevz' ),
					'options' 	=> array(
						'woo' 		=> esc_html__( 'Woocommerce', 'codevz' ),
						'edd' 		=> esc_html__( 'Easy Digital Download', 'codevz' ),
					),
					'dependency' => array( 'element', '==', 'shop_cart' ),
				),

				array(
					'type'    		=> 'content',
					'content' 		=> '<a href="#" onclick="wp.customize.section( \'codevz_theme_options-products\' ).focus()" class="button xtra-goto">' . esc_html__( 'Social Icons Manager', 'codevz' ) . '</a>',
					'dependency' 	=> array( 'element', '==', 'social' ),
				),
				array(
					'id'    		=> 'shopcart_icon',
					'type'  		=> 'icon',
					'title' 		=> esc_html__( 'Icon', 'codevz' ),
					'dependency' => array( 'element', 'any', 'shop_cart,wishlist' ),
				),
				array(
					'id'    	=> 'shopcart_title',
					'type'  	=> 'text',
					'title' 	=> esc_html__( 'Title', 'codevz' ),
					'default' 	=> esc_html__( 'Title', 'codevz' ),
					'dependency' => array( 'element', 'any', 'shop_cart,wishlist' ),
				),
				array(
					'id' 			=> 'sk_shop_container',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Container', 'codevz' ),
					'button' 		=> esc_html__( 'Container', 'codevz' ),
					'settings' 		=> array( 'background', 'border' ),
					'dependency' 	=> array( 'element', 'any', 'shop_cart,wishlist' )
				),
				array(
					'id' 			=> 'sk_shop_icon',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'button' 		=> esc_html__( 'Icon Style', 'codevz' ),
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
					'dependency' 	=> array( 'element', 'any', 'shop_cart,wishlist' )
				),
				array(
					'id' 			=> 'sk_shop_count',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Count Style', 'codevz' ),
					'button' 		=> esc_html__( 'Count Style', 'codevz' ),
					'settings' 		=> array( 'top', 'right', 'color', 'font-size', 'background', 'border' ),
					'dependency' 	=> array( 'element', 'any', 'shop_cart,wishlist' )
				),
				array(
					'id' 			=> 'sk_shop_content',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Cart Style', 'codevz' ),
					'button' 		=> esc_html__( 'Cart Style', 'codevz' ),
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'dependency' 	=> array( 'element', '==', 'shop_cart' )
				),

				// Line
				array(
					'id' 	=> 'line_type',
					'type' 	=> 'select',
					'title' => esc_html__( 'Type', 'codevz' ),
					'help'  => esc_html__( 'Background color for line is important that you can change it from line stysle button.', 'codevz' ),
					'options' 	=> array(
		  				'header_line_2'   	=> esc_html__( '~ Default ~', 'codevz' ),
						'header_line_1' 	=> esc_html__( 'Full height', 'codevz' ),
						'header_line_3' 	=> esc_html__( 'Slash', 'codevz' ),
						'header_line_4' 	=> esc_html__( 'Horizontal', 'codevz' ),
					),
					'dependency' => array( 'element', '==', 'line' ),
				),
				array(
					'id' 			=> 'sk_line',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Line Style', 'codevz' ),
					'button' 		=> esc_html__( 'Line Style', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background' ),
					'dependency' 	=> array( 'element', '==', 'line' )
				),

				// WPML
				array(
					'id' 			=> 'wpml_title',
					'type' 			=> 'select',
					'title' 		=> esc_html__( 'Title', 'codevz' ),
					'options' 		=> array(
						'translated_name' 	=> esc_html__( 'Translated Name', 'codevz' ),
						'language_code' 	=> esc_html__( 'Language code', 'codevz' ),
						'native_name' 		=> esc_html__( 'Native name', 'codevz' ),
						'translated_name' 	=> esc_html__( 'Translated name', 'codevz' ),
						'no_title' 			=> esc_html__( 'No title', 'codevz' ),
					),
					'dependency' 	=> array( 'element', '==', 'wpml' ),
				),
				array(
					'id' 			=> 'wpml_flag',
					'type' 			=> 'switcher',
					'title' 		=> esc_html__( 'Flag', 'codevz' ),
					'dependency' 	=> array( 'element|wpml_title', '==|!=', 'wpml|country_flag_url' ),
				),
				array(
					'id'    		=> 'wpml_current_color',
					'type'  		=> 'color_picker',
					'title' 		=> esc_html__( 'Current Language', 'codevz' ),
					'dependency' 	=> array( 'element', '==', 'wpml' ),
				),
				array(
					'id'    		=> 'wpml_background',
					'type'  		=> 'color_picker',
					'title' 		=> esc_html__( 'Background', 'codevz' ),
					'dependency' 	=> array( 'element', '==', 'wpml' ),
				),
				array(
					'id'    		=> 'wpml_color',
					'type'  		=> 'color_picker',
					'title' 		=> esc_html__( 'Inner Color', 'codevz' ),
					'dependency' 	=> array( 'element', '==', 'wpml' ),
				),
				array(
					'id' 			=> 'wpml_opposite',
					'type' 			=> 'switcher',
					'title' 		=> esc_html__( 'Toggle Mode', 'codevz' ),
					'dependency' 	=> array( 'element', '==', 'wpml' ),
				),

				// Avatar
				array(
					'id'    => 'avatar_size',
					'type'  => 'slider',
					'title' => esc_html__( 'Size', 'codevz' ),
					'dependency' => array( 'element', '==', 'avatar' ),
					'default' => '40px'
				),
				array(
					'id' 			=> 'sk_avatar',
					'type' 			=> 'cz_sk',
					'title' 		=> esc_html__( 'Avatar', 'codevz' ),
					'button' 		=> esc_html__( 'Avatar', 'codevz' ),
					'setting_args' 	=> [ 'transport' => 'postMessage' ],
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'dependency' 	=> array( 'element', '==', 'avatar' )
				),
				array(
					'id'    => 'avatar_link',
					'type'  => 'text',
					'title' => esc_html__( 'Link', 'codevz' ),
					'dependency' => array( 'element', '==', 'avatar' ),
				),

				// Others
				array(
					'id' 		=> 'vertical',
					'type' 		=> 'switcher',
					'title'		=> esc_html__( 'Vertical', 'codevz' ),
					'dependency' => $is_fixed_side ? array( 'element', 'any', 'social,icon' ) : array( 'element', '==', 'xxx' )
				),
				array(
					'id' 	=> 'elm_visibility',
					'type' 	=> 'select',
					'title' => esc_html__( 'Visibility', 'codevz' ),
					'help'  => esc_html__( 'You can show or hide this element for logged in or non-logged in users', 'codevz' ),
					'options' 	=> array(
						'' 			=> esc_html__( '~ Default ~', 'codevz' ),
						'1' 		=> esc_html__( 'Show only for logged in users', 'codevz' ),
						'2' 		=> esc_html__( 'Show only for non-logged in users', 'codevz' ),
					),
					'dependency' => array( 'element', '!=', '' )
				),
				array(
					'id' 	=> 'elm_on_sticky',
					'type' 	=> 'select',
					'title' => esc_html__( 'On Sticky', 'codevz' ),
					'help'  => esc_html__( 'You can enable sticky mode from Theme Options > Header > Sticky Header', 'codevz' ),
					'options' 	=> array(
						'' 					=> esc_html__( '~ Default ~', 'codevz' ),
						'show_on_sticky' 	=> esc_html__( 'Show only on sticky', 'codevz' ),
						'hide_on_sticky' 	=> esc_html__( 'Hide only on sticky', 'codevz' ),
					),
					'dependency' => $is_1_2_3 ? array( 'element', '!=', '' ) : array( 'element', '==', 'xxx' )
				),
				array(
					'id' 		=> 'elm_center',
					'type' 		=> 'switcher',
					'title'		=> esc_html__( 'Center Mode', 'codevz' ),
					'dependency' => $is_fixed_side ? array( 'element', '!=', '' ) : array( 'element', '==', 'xxx' )
				),
				array(
					'id' 		=> 'hide_on_mobile',
					'type' 		=> 'switcher',
					'title'		=> esc_html__( 'Hide on Mobile', 'codevz' ),
					'dependency' => $is_footer ? array( 'element', '!=', '' ) : array( 'element', '==', 'xxx' )
				),
				array(
					'id' 		=> 'hide_on_tablet',
					'type' 		=> 'switcher',
					'title'		=> esc_html__( 'Hide on Tablet', 'codevz' ),
					'dependency' => $is_footer ? array( 'element', '!=', '' ) : array( 'element', '==', 'xxx' )
				),

				// Element margin
				array(
					'id'        => 'margin',
					'type'      => 'codevz_sizes',
					'title'     => esc_html__( 'Margin', 'codevz' ),
					'options'	=> array( 'unit' => 'px', 'step' => 1, 'min' => -20, 'max' => 100 ),
					'default'	=> array(
						'top' 		=> '20px',
						'right' 	=> '',
						'bottom' 	=> '20px',
						'left' 		=> '',
					),
					'help'		 => self::help( 'margin' ),
					'dependency' => array( 'element', '!=', '' )
				),

			)
		);
	}

	/**
	 *
	 * Header row builder options
	 * 
	 * @return array
	 *
	 */
	public static function row_options( $id, $positions = array('left', 'center', 'right') ) {

		$elm = '.' . $id;
		$out = array();

		// If is sticky so show dropdown option and create dependency
		if ( $id === 'header_5' ) {
			$elm = '.onSticky';
			$dependency = array( 'sticky_header', '==', '5' );
			
			$out[] = array(
				'id' 		=> 'sticky_header',
				'type' 		=> 'select',
				'title' 	=> esc_html__( 'Type', 'codevz' ),
				'options' 	=> array(
					''			=> esc_html__( '~ Disable ~', 'codevz' ),
					'1'			=> esc_html__( 'Sticky top bar', 'codevz' ),
					'2'			=> esc_html__( 'Sticky header', 'codevz' ),
					'3'     	=> esc_html__( 'Sticky bottom bar', 'codevz' ),
					'12'    	=> esc_html__( 'Header top bar + Header', 'codevz' ),
					'23'    	=> esc_html__( 'Header + Header bottom bar', 'codevz' ),
					'13'    	=> esc_html__( 'Header top bar + Header bottom bar', 'codevz' ),
					'123'	  	=> esc_html__( 'All Headers Sticky', 'codevz' ),
					'5'			=> esc_html__( 'Create custom sticky', 'codevz' ),
				)
			);
			$out[] = array(
				'id' 		=> 'smart_sticky',
				'type' 		=> 'switcher',
				'title' 	=> esc_html__( 'Smart Sticky', 'codevz' ),
				'dependency' => array( 'sticky_header', 'any', '1,2,3,5' )
			);
		} else {
			$dependency = array();
		}

		// Fixed position before elements
		if ( $id === 'fixed_side_1' ) {
			$out[] = array(
				'id' 			=> 'fixed_side',
				'type' 			=> 'codevz_image_select',
				'title' 		=> esc_html__( 'Fixed Side', 'codevz' ),
				'options' 		=> [
					'' 				=> [ esc_html__( '~ Disable ~', 'codevz' )	, Codevz_Plus::$url . 'assets/img/off.png' ],
					'left' 			=> [ esc_html__( 'Left', 'codevz' ) 		, ( Codevz_Plus::$is_rtl ? Codevz_Plus::$url . 'assets/img/sidebar-3.png' : Codevz_Plus::$url . 'assets/img/sidebar-5.png' ) ],
					'right' 		=> [ esc_html__( 'Right', 'codevz' ) 		, ( Codevz_Plus::$is_rtl ? Codevz_Plus::$url . 'assets/img/sidebar-5.png' : Codevz_Plus::$url . 'assets/img/sidebar-3.png' ) ],
				],
				'default' 		=> '',
				'attributes' => array( 'data-depend-id' => 'fixed_side' )
			);
			$dependency = array( 'fixed_side', 'any', 'left,right' );
		}

		// Tablet/Mobile header
		if ( $id === 'header_4' ) {
			$out[] = array(
				'id' 		=> 'mobile_sticky',
				'type' 		=> 'select',
				'title' 	=> esc_html__( 'Sticky Mode', 'codevz' ),
				'options' 	=> array(
					''								=> esc_html__( '~ Select ~', 'codevz' ),
					'header_is_sticky'				=> esc_html__( 'Sticky', 'codevz' ),
					'header_is_sticky smart_sticky'	=> esc_html__( 'Smart Sticky', 'codevz' ),
				)
			);
			$out[] = array(
			  'id'            => 'b_mobile_header',
			  'type'          => 'select',
			  'title'         => esc_html__( 'Before Header', 'codevz' ),
			  'options'       => Codevz_Plus::$array_pages,
			);
			$out[] = array(
			  'id'            => 'a_mobile_header',
			  'type'          => 'select',
			  'title'         => esc_html__( 'After Header', 'codevz' ),
			  'options'       => Codevz_Plus::$array_pages,
			);
		}

		// Left center right elements and style
		foreach( $positions as $num => $pos ) {
			$num++;
			$out[] = self::elements( $id . '_' . $pos, '', $dependency, $pos );
		}

		// If its fixed header so show dropdown option
		$out[] = array(
			'type'    => 'notice',
			'class'   => 'info',
			'content' => esc_html__( 'Row Styling', 'codevz' ),
			'dependency' => $dependency
		);
		if ( $id === 'fixed_side_1' ) {
			$out[] = array(
				'id' 			=> '_css_fixed_side_style',
				'type' 			=> 'cz_sk',
				'title' 		=> esc_html__( 'Container', 'codevz' ),
				'button' 		=> esc_html__( 'Container', 'codevz' ),
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'settings' 		=> array( 'background', 'width', 'border' ),
				'selector' 		=> '.fixed_side, .fixed_side .theiaStickySidebar'
			);
		} else {
			$f_dependency = ( $id === 'header_5' ) ? array( 'sticky_header', '!=', '' ) : array();
			$out[] = array(
				'id' 			=> '_css_container_' . $id,
				'type' 			=> 'cz_sk',
				'title' 		=> esc_html__( 'Container', 'codevz' ),
				'button' 		=> esc_html__( 'Container', 'codevz' ),
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'settings' 		=> array( 'background', 'padding', 'border' ),
				'selector' 		=> $elm,
				'dependency' 	=> $f_dependency
			);
			$out[] = array(
				'id' 			=> '_css_row_' . $id,
				'type' 			=> 'cz_sk',
				'title' 		=> esc_html__( 'Row inner', 'codevz' ),
				'button' 		=> esc_html__( 'Row inner', 'codevz' ),
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'settings' 		=> array( 'background', '_class_shape', 'width', 'padding', 'border' ),
				'selector' 		=> $elm . ' .row',
				'dependency' 	=> $f_dependency
			);
		}

		// Left center right elements and style
		foreach ( $positions as $num => $pos ) {
			$num++;
			$out[] = array(
				'id' 			=> '_css_' . $id . '_' . $pos,
				'type' 			=> 'cz_sk',
				'title' 		=> ucfirst( self::$trasnlation[ $pos ] ),
				'button' 		=> ucfirst( self::$trasnlation[ $pos ] ),
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'settings' 		=> array( 'background', '_class_shape', 'padding', 'border' ),
				'selector' 		=> $elm . ' .elms_' . $pos,
				'dependency' 	=> $dependency
			);
		}

		// Menus style for each row
		$menu_unique_id = '#menu_' . $id;
		$out[] = array(
			'type' 			=> 'notice',
			'class' 		=> 'info xtra-notice',
			'content' 		=> esc_html__( 'Menu Styling', 'codevz' ),
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_container_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Container', 'codevz' ),
			'button' 		=> esc_html__( 'Container', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'background', 'padding', 'border' ),
			'selector' 		=> $menu_unique_id,
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_li_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Menus li', 'codevz' ),
			'button' 		=> esc_html__( 'Menus li', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'float', 'text-align', 'padding', 'margin', 'border' ),
			'selector' 		=> $menu_unique_id . ' > .cz',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_a_' . $id,
			'hover_id' 		=> '_css_menu_a_hover_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Menus', 'codevz' ),
			'button' 		=> esc_html__( 'Menus', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'color', 'background', 'font-family', 'font-size', 'padding', 'margin', 'border' ),
			'selector' 		=> $menu_unique_id . ' > .cz > a',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_a_hover_' . $id,
			'type' 			=> 'cz_sk_hidden',
			'button' 		=> '',
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'selector' 		=> $menu_unique_id . ' > .cz > a:hover,' . $menu_unique_id . ' > .cz:hover > a,' . $menu_unique_id . ' > .cz.current_menu > a,' . $menu_unique_id . ' > .current-menu-parent > a',
			'dependency' 	=> $dependency
		);

		$out[] = array(
			'id' 			=> '_css_menu_a_hover_before_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Shape', 'codevz' ),
			'button' 		=> esc_html__( 'Shape', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( '_class_menu_fx', 'background', 'height', 'width', 'left', 'bottom', 'border' ),
			'selector' 		=> $menu_unique_id . ' > .cz > a:before',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_subtitle_' . $id,
			'hover_id' 		=> '_css_menu_subtitle_' . $id . '_hover',
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Subtitle', 'codevz' ),
			'button' 		=> esc_html__( 'Subtitle', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'margin' ),
			'selector' 		=> $menu_unique_id . ' > .cz > a > .cz_menu_subtitle',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_subtitle_' . $id . '_hover',
			'type' 			=> 'cz_sk_hidden',
			'button' 		=> '',
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'selector' 		=> $menu_unique_id . ' > .cz > a:hover > .cz_menu_subtitle,' . $menu_unique_id . ' > .cz:hover > a > .cz_menu_subtitle,' . $menu_unique_id . ' > .cz.current_menu > a > .cz_menu_subtitle,' . $menu_unique_id . ' > .current-menu-parent > a > .cz_menu_subtitle',
			'dependency' 	=> $dependency
		);

		$out[] = array(
			'id' 			=> '_css_menu_icon_' . $id,
			'hover_id' 		=> '_css_menu_icon_' . $id . '_hover',
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Icons', 'codevz' ),
			'button' 		=> esc_html__( 'Icons', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'color', 'background', 'font-size', 'padding', 'margin', 'border', 'position', 'top', 'left', 'opacity' ),
			'selector' 		=> $menu_unique_id . ' > .cz > a span i',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_icon_' . $id . '_hover',
			'type' 			=> 'cz_sk_hidden',
			'button' 		=> '',
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'selector' 		=> $menu_unique_id . ' > .cz > a:hover span i,' . $menu_unique_id . ' > .cz:hover > a span i,' . $menu_unique_id . ' > .cz.current_menu > a span i,' . $menu_unique_id . ' > .current-menu-parent > a span i',
			'dependency' 	=> $dependency
		);

		$out[] = array(
			'id' 			=> '_css_menu_indicator_a_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Indicator', 'codevz' ),
			'button' 		=> esc_html__( 'Indicator', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'color', 'font-size', '_class_indicator' ),
			'selector' 		=> $menu_unique_id . ' > .cz > a .cz_indicator',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menus_separator_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Delimiter', 'codevz' ),
			'button' 		=> esc_html__( 'Delimiter', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'content', 'rotate', 'color', 'font-size', 'margin' ),
			'selector' 		=> $menu_unique_id . ' > .cz:after',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_ul_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Dropdown', 'codevz' ),
			'button' 		=> esc_html__( 'Dropdown', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( '_class_submenu_fx', 'width', 'background', 'padding', 'margin', 'border' ),
			'selector' 		=> $menu_unique_id . ' .cz .sub-menu:not(.cz_megamenu_inner_ul),' . $menu_unique_id . ' .cz_megamenu_inner_ul .cz_megamenu_inner_ul',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_ul_a_' . $id,
			'hover_id' 		=> '_css_menu_ul_a_hover_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Inner Menus', 'codevz' ),
			'button' 		=> esc_html__( 'Inner Menus', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'color', 'background', 'font-family', 'text-align', 'font-size', 'padding', 'margin', 'border' ),
			'selector' 		=> $menu_unique_id . ' .cz .cz a',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_ul_a_hover_' . $id,
			'type' 			=> 'cz_sk_hidden',
			'button' 		=> '',
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'selector' 		=> $menu_unique_id . ' .cz .cz a:hover,' . $menu_unique_id . ' .cz .cz:hover > a,' . $menu_unique_id . ' .cz .cz.current_menu > a,' . $menu_unique_id . ' .cz .current_menu > .current_menu',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_ul_indicator_a_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Inner Idicator', 'codevz' ),
			'button' 		=> esc_html__( 'Inner Idicator', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'color', 'font-size', '_class_indicator' ),
			'selector' 		=> $menu_unique_id . ' .cz .cz a .cz_indicator',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_ul_ul_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( '3rd Level', 'codevz' ),
			'button' 		=> esc_html__( '3rd Level', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'margin' ),
			'selector' 		=> $menu_unique_id . ' .sub-menu .sub-menu:not(.cz_megamenu_inner_ul)',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_inner_megamenu_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Megamenu', 'codevz' ),
			'button' 		=> esc_html__( 'Megamenu', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'margin', 'padding', 'background', 'border' ),
			'selector' 		=> $menu_unique_id . ' .cz_parent_megamenu > [class^="cz_megamenu_"] > .cz, .cz_parent_megamenu > [class*=" cz_megamenu_"] > .cz',
			'dependency' 	=> $dependency
		);
		$out[] = array(
			'id' 			=> '_css_menu_ul_a_h6_' . $id,
			'type' 			=> 'cz_sk',
			'title' 		=> esc_html__( 'Title', 'codevz' ),
			'button' 		=> esc_html__( 'Title', 'codevz' ),
			'setting_args' 	=> [ 'transport' => 'postMessage' ],
			'settings' 		=> array( 'color', 'background', 'font-family', 'text-align', 'font-size', 'padding', 'margin', 'border' ),
			'selector' 		=> $menu_unique_id . ' .cz .cz h6',
			'dependency' 	=> $dependency
		);

		// Mobile additional
		if ( $id === 'header_4' ) {
			$out[] = array(
				'type' 			=> 'notice',
				'class' 		=> 'info xtra-notice',
				'content' 		=> esc_html__( 'Mobile Menu Additional', 'codevz' )
			);
			$out[] = array(
				'id' 			=> 'mobile_menu_social',
				'type' 			=> 'switcher',
				'title' 		=> esc_html__( 'Social Icons', 'codevz' ),
				'help' 			=> esc_html__( 'Go to Theme Options > Header > Social Icons for add or remove social icons', 'codevz' )
			);
			$out[] = array(
				'id' 			=> 'mobile_menu_social_color_mode',
				'type' 			=> 'select',
				'title' 		=> esc_html__( 'Color Mode', 'codevz' ),
				'options' 		=> array(
					'cz_social_no_colored' 		=> esc_html__( '~ Disable ~', 'codevz' ),
					'cz_social_colored' 		=> esc_html__( 'Brand Colors', 'codevz' ),
					'cz_social_colored_hover' 	=> esc_html__( 'Brand Colors on Hover', 'codevz' ),
					'cz_social_colored_bg' 		=> esc_html__( 'Brand Background', 'codevz' ),
					'cz_social_colored_bg_hover' => esc_html__( 'Brand Background on Hover', 'codevz' ),
				),
				'default_option' => esc_html__( '~ Default ~', 'codevz'),
			);
			$out[] = array(
				'id' 			=> 'mobile_menu_text',
				'type' 			=> 'textarea',
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'title' 		=> esc_html__( 'Custom Text', 'codevz' ),
				'help'  		=> esc_html__( 'Instead current year you can use [codevz_year]', 'codevz' ),
			);
			$out[] = array(
				'id' 			=> '_css_mm_additional',
				'type' 			=> 'cz_sk',
				'title' 		=> esc_html__( 'Container', 'codevz' ),
				'button' 		=> esc_html__( 'Container', 'codevz' ),
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'settings' 		=> array( 'text-align' ),
				'selector' 		=> 'li.xtra-mobile-menu-additional'
			);
			$out[] = array(
				'id' 			=> '_css_mm_text',
				'type' 			=> 'cz_sk',
				'title' 		=> esc_html__( 'Text style', 'codevz' ),
				'button' 		=> esc_html__( 'Text style', 'codevz' ),
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin', 'border' ),
				'selector' 		=> '.xtra-mobile-menu-text',
			);
			$out[] = array(
				'id' 			=> '_css_mms_container',
				'type' 			=> 'cz_sk',
				'title' 		=> esc_html__( 'Social', 'codevz' ),
				'button' 		=> esc_html__( 'Social', 'codevz' ),
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'settings' 		=> array( 'background', 'padding', 'margin', 'border' ),
				'selector' 		=> 'li.xtra-mobile-menu-additional .cz_social',
			);
			$out[] = array(
				'id' 			=> '_css_mms_icons',
				'hover_id' 		=> '_css_mms_icons_hover',
				'type' 			=> 'cz_sk',
				'title' 		=> esc_html__( 'Icons', 'codevz' ),
				'button' 		=> esc_html__( 'Icons', 'codevz' ),
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin', 'border' ),
				'selector' 		=> 'li.xtra-mobile-menu-additional .cz_social a',
			);
			$out[] = array(
				'id' 			=> '_css_mms_icons_hover',
				'type' 			=> 'cz_sk_hidden',
				'button' 		=> '',
				'setting_args' 	=> [ 'transport' => 'postMessage' ],
				'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin', 'border' ),
				'selector' 		=> 'li.xtra-mobile-menu-additional .cz_social a:hover',
			);
		}

		return $out;
	}

	/**
	 *
	 * Generate json of options for customize footer and live changes
	 * 
	 * @return string
	 *
	 */
	public static function codevz_wp_footer_options_json() {
		$out = [];

		foreach ( Codevz_Plus::option() as $id => $val ) {
			if ( ! empty( $val ) && Codevz_Plus::contains( $id, '_css_' ) ) {
				$out[ $id ] = $val;
			}
		}

		wp_add_inline_script( 'codevz-customize', 'var codevz_selectors = ' . json_encode( (array) self::get_selector( 'all' ) ) . ', codevz_customize_json = ' . json_encode( $out ) . ';', 'before' );
	}

	/**
	 *
	 * Get sidebars
	 * 
	 * @return string
	 *
	 */
	public static function sidebars() {
		$options = array( '' => esc_html__( '~ Default ~', 'codevz' ) );
		$sidebars = (array) get_option( 'sidebars_widgets' );
		foreach ( $sidebars as $i => $w ) {
			if ( isset( $i ) && ( $i !== 'array_version' && $i !== 'jr-insta-shortcodes' && $i !== 'wp_inactive_widgets' ) ) {
				$options[ $i ] = ucwords( $i );
			}
		}

		return $options;
	}

	/**
	 *
	 * Get list of Revolution Sliders
	 * 
	 * @return string
	 *
	 */
	public static function revSlider( $out = array() ) {

		if ( class_exists( 'RevSlider' ) ) {

			global $wpdb;
			$sliders = (object) $wpdb->get_results( $wpdb->prepare( "SELECT id, title, alias FROM " . $wpdb->prefix . "revslider_sliders WHERE `type` != 'folder' AND `type` != 'template' ORDER BY %s %s", [ 'id', 'ASC' ] ) );
			
			foreach (  $sliders as $slider ) {
				if ( isset( $slider->alias ) && isset( $slider->title ) ) {
					$out[ $slider->alias ] = $slider->title;
				}
			}

			if ( empty( $out ) ) {
				$out = array( esc_html__('Not found, Please create new from Revolution Slider menu', 'codevz') );
			}

		} else {

			$out = array( esc_html__('Sorry! Revolution Slider is not installed or activated', 'codevz') );

		}

		return $out;
	}

}

new Codevz_Options;