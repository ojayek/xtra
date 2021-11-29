<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Cannot access pages directly.

/**
 * Theme core class and functions
 * If you want to override functions, Please read theme documentation
 * 
 * @link https://xtratheme.com/
 */

if ( ! class_exists( 'Codevz_Theme' ) ) {

	class Codevz_Theme {

		// Server API address.
		public static $api = 'https://xtratheme.ir/api/';

		// Check core plugin.
		public static $plugin;

		// Cache post query.
		public static $post;

		// Get home URL.
		public static $home_url;

		// Header element ID.
		private static $element = 0;

		// Check RTL mode.
		public static $is_rtl = false;

		// Check preview.
		public static $preview = false;

		// Theme folder URL.
		public static $url = false;

		// Instance of this class.
		private static $instance = null;

		// Core functionality.
		public function __construct() {

			self::$post 	= &$GLOBALS['post'];
			self::$plugin 	= class_exists( 'Codevz_Plus' );
			self::$preview 	= is_customize_preview();
			self::$home_url = esc_url( home_url( '/' ) );
			self::$url 		= trailingslashit( get_template_directory_uri() );

			// After loaded.
			add_action( 'wp', [ $this, 'wp' ] );

			// Include files.
			get_template_part( 'classes/class-settings' );
			get_template_part( 'classes/class-dashboard' );

			// Actions.
			add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
			add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'load_assets' ] );
			add_action( 'wp_head', [ $this, 'load_dynamic_css' ], 99 );
			add_action( 'nav_menu_css_class', [ $this, 'menu_current_class' ], 10, 2 );
			add_action( 'wp_ajax_codevz_selective_refresh', [ $this, 'row_inner' ] );
			add_action( 'wp_head', [ $this, 'wp_head' ] );
			add_action( 'customize_save_after', [ $this, 'white_label' ] );

			// Filters.
			add_filter( 'excerpt_more', [ $this, 'excerpt_more' ] );
			add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 99 );
			add_filter( 'get_the_excerpt', [ $this, 'get_the_excerpt' ], 21 );
			add_filter( 'the_content_more_link', [ $this, 'the_content_more_link' ] );
			add_filter( 'wp_list_categories', [ $this, 'wp_list_categories' ] );
			add_filter( 'get_archives_link',  [ $this, 'get_archives_link' ] );

		}

		/**
		 * Instance
		 */
		public static function instance() {

			if ( self::$instance === null ) {
				self::$instance = new self();
			}
			
			return self::$instance;

		}

		public function wp() {

			self::$is_rtl = ( self::option( 'rtl' ) || is_rtl() || isset( $_GET['rtl'] ) );

		}

		/**
		 * Get page settings
		 * 
		 * @var $id = post id
		 * @var $key = array key
		 * 
		 * @return array|string|null
		 */
		public static function meta( $id = null, $key = null ) {

			if ( ! $id && isset( self::$post->ID ) ) {
				$id = self::$post->ID;
			}

			$meta = get_post_meta( $id, 'codevz_page_meta', true );

			if ( $key ) {
				return isset( $meta[ $key ] ) ? $meta[ $key ] : 0;
			} else {
				return $id ? $meta : '';
			}

		}

		/**
		 * Get theme options
		 * 
		 * @var 	$key = option name
		 * @var 	$default = default value
		 * 
		 * @return 	array|string|null
		 */
		public static function option( $key = '', $default = '' ) {

			$options = get_option( 'codevz_theme_options' );

			return empty( $key ) ? $options : apply_filters( 'xtra/option/' . $key, ( empty( $options[ $key ] ) ? $default : $options[ $key ] ) );

		}

		/**
		 * After setup theme
		 */
		public static function theme_setup() {

			// Activation check.
			$activation = get_option( 'codevz_theme_activation' );

			if ( ! isset( $activation['support_until'] ) ) {
				delete_option( 'codevz_theme_activation' );
			}

			// Menu location.
			register_nav_menus( [ 'primary' => esc_html__( 'Primary', 'xtra' ) ] );

			// Theme Supports.
			add_theme_support( 'html5' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'automatic-feed-links' );

			// Thumbnails and featured image.
			add_theme_support( 'post-thumbnails' );

			// Post formats.
			add_theme_support( 'post-formats', [ 'gallery', 'video', 'audio', 'quote' ] );

			// Add theme support for selective refresh for widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );

			// Add support for Block Styles.
			add_theme_support( 'wp-block-styles' );

			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );

			// Add support for editor styles.
			add_theme_support( 'editor-styles' );
			add_editor_style( self::$url . 'assets/css/editor-style.css' );

			// Responsive embedded content.
			add_theme_support( 'responsive-embeds' );
			add_theme_support( 'jetpack-responsive-videos' );

			// Add support for plugins.
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
			add_theme_support( 'bbpress' );

			// Temporary fix.
			remove_theme_support( 'widgets-block-editor' );

			// Disable Woocommerce features.
			$disable_woo = (array) self::option( 'woo_gallery_features' );
			foreach( $disable_woo as $f ) {
				remove_theme_support( 'wc-product-gallery-' . $f );
			}

			// Images.
			add_image_size( 'codevz_360_320', 360, 320, true ); 	// Medium
			add_image_size( 'codevz_600_600', 600, 600, true ); 	// Square
			add_image_size( 'codevz_1200_200', 1200, 200, true ); 	// CPT Full 1
			add_image_size( 'codevz_1200_500', 1200, 500, true ); 	// CPT Full 2
			add_image_size( 'codevz_600_1000', 600, 1000, true ); 	// Vertical
			add_image_size( 'codevz_600_9999', 600, 9999 ); 		// Masonry

			// Content width.
			if ( ! isset( $content_width ) ) {
				$content_width = apply_filters( 'codevz_content_width', (int) self::option( 'site_width', 1280 ) );
			}

			// Language.
			load_textdomain( 'xtra', trailingslashit( get_template_directory() ) . 'languages/xtra-' . get_locale() . '.mo' );

		}

		/**
		 * Front-end assets
		 * @return string
		 */
		public static function load_assets() {

			if ( ! isset( $_POST['vc_inline'] ) ) {

				// Path.
				$uri = self::$url;

				// Get theme version.
				$theme = wp_get_theme();
				$ver = empty( $theme->parent() ) ? $theme->get( 'Version' ) : $theme->parent()->Version;

				$name = self::option( 'white_label_theme_name', 'xtra' );

				// Core styles.
				//wp_enqueue_style( 'theme', get_stylesheet_uri(), [], $ver );
				wp_enqueue_style( $name, $uri . 'assets/css/core.css', [], $ver );

				if ( ! self::option( 'disable_responsive' ) ) {

					wp_enqueue_style( $name . '-laptop', self::$url . 'assets/css/core-laptop.css', [ $name ], $ver, 'screen and (max-width: 1024px)' );
					wp_enqueue_style( $name . '-tablet', self::$url . 'assets/css/core-tablet.css', [ $name ], $ver, 'screen and (max-width: 768px)' );
					wp_enqueue_style( $name . '-mobile', self::$url . 'assets/css/core-mobile.css', [ $name ], $ver, 'screen and (max-width: 480px)' );

				}

				if ( ! self::$plugin ) {

					//wp_enqueue_style( 'xtra-font-awesome', $uri .'assets/font-awesome/font-awesome.min.css', array(), '5.11.2', 'all' );
					wp_enqueue_style( 'xtra-font-awesome-shims', $uri .'assets/font-awesome/font-awesome/css/v4-shims.min.css', array(), '5.11.2', 'all' );
					wp_enqueue_style( 'xtra-font-awesome-all', $uri .'assets/font-awesome/font-awesome/css/all.min.css', array(), '5.11.2', 'all' );

				}

				// Error page.
				if ( is_404() ) {
					wp_enqueue_style( 'xtra-404', 		$uri . 'assets/css/404.css', [ $name ], $ver );
				}

				// RTL mode.
				if ( self::$is_rtl ) {
					wp_enqueue_style( 'xtra-rtl', 		$uri . 'assets/css/core.rtl.css', [ $name ], $ver );
				}

				// Fixed side.
				if ( self::option( 'fixed_side' ) ) {
					wp_enqueue_style( 'xtra-fixed-side', 		$uri . 'assets/css/fixed_side.css', [ $name ], $ver );
					if ( self::$is_rtl ) {
						wp_enqueue_style( 'xtra-fixed-side-rtl', $uri . 'assets/css/fixed_side.rtl.css', [ $name ], $ver );
					}	
				}

				// Single CSS.
				if ( is_single() ) {

					wp_enqueue_style( 'xtra-single', 	$uri . 'assets/css/single.css', [ $name ], $ver );

					if ( self::$is_rtl ) {
						wp_enqueue_style( 'xtra-single-rtl', $uri . 'assets/css/single.rtl.css', [ $name ], $ver );
					}

				}

				// Custom.js
				wp_enqueue_script( $name, $uri . 'assets/js/custom.js', [ 'jquery' ], $ver, true );

				// Comments.
				if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {

					wp_enqueue_script( 'comment-reply' );
					wp_enqueue_style( 'xtra-comments', 	$uri . 'assets/css/comments.css', [ $name ], $ver );

					if ( self::$is_rtl ) {
						wp_enqueue_style( 'xtra-comments-rtl', $uri . 'assets/css/comments.rtl.css', [ $name ], $ver );
					}

				}

				// Header sticky.
				if ( self::option( 'sticky_header', self::option( 'mobile_sticky' ) ) ) {
					wp_enqueue_script( 'xtra-sticky', 	$uri . 'assets/js/sticky.js', [ $name ], $ver, true );
				}

				// Page loading.
				if ( self::option( 'pageloader' ) ) {
					wp_enqueue_style( 'xtra-loading', 	$uri . 'assets/css/loading.css', [ $name ], $ver );
					wp_enqueue_script( 'xtra-loading', 	$uri . 'assets/js/loading.js', [ $name ], $ver, true );
				}

				// WPML.
				if ( function_exists( 'icl_object_id' ) ) {
					wp_enqueue_script( 'xtra-wpml', 	$uri . 'assets/js/wpml.js', [ $name ], $ver, true );
				}

				// Register JS.
				wp_register_script( 'xtra-search', 		$uri . 'assets/js/search.js' );
				wp_register_script( 'xtra-extra-panel', $uri . 'assets/js/extra_panel.js' );
				wp_register_script( 'xtra-icon-text', 	$uri . 'assets/js/icon_text.js' );

				// Only on preview.
				if ( self::$preview ) {
					wp_enqueue_script( 'xtra-search' );
				}

				// Fonts
				foreach( (array) self::option( 'fonts_out' ) as $font ) {
					self::enqueue_font( $font );
				}

			}

		}

		/**
		 * Load dynamic style as a file or inline
		 * @return string
		 */
		public static function load_dynamic_css() {

			if ( ! isset( $_POST['vc_inline'] ) ) {

				// Custom styles
				$name = self::option( 'white_label_theme_name', 'xtra' );
				$handle = wp_style_is( 'codevz-plugin' ) ? 'codevz-plugin' : $name;
				$extra_css = '';

				// Dark
				if ( self::option( 'dark' ) ) {
					$extra_css .= "/* Dark */" . 'body{background-color:#171717;color:#fff}.layout_1,.layout_2{background:#191919}a,.woocommerce-error, .woocommerce-info, .woocommerce-message{color:#fff}.sf-menu li li a,.sf-menu .cz > h6{color: #000}.cz_quote_arrow blockquote{background:#272727}.search_style_icon_dropdown .outer_search, .cz_cart_items {background: #000;color: #c0c0c0 !important}.woocommerce div.product .woocommerce-tabs ul.tabs li.active a {color: #111}#bbpress-forums li{background:none!important}#bbpress-forums li.bbp-header,#bbpress-forums li.bbp-header,#bbpress-forums li.bbp-footer{background:#141414!important;color:#FFF;padding:10px 20px!important}.bbp-header a{color:#fff}.subscription-toggle,.favorite-toggle{padding: 1px 20px !important;}span#subscription-toggle{color: #000}#bbpress-forums #bbp-single-user-details #bbp-user-navigation li.current a{background:#1D1E20!important;color:#FFF;opacity:1}#bbpress-forums li.bbp-body ul.forum,#bbpress-forums li.bbp-body ul.topic{padding:10px 20px!important}.bbp-search-form{margin:0 0 12px!important}.bbp-form .submit{margin:0 auto 20px}div.bbp-breadcrumb,div.bbp-topic-tags{line-height:36px}.bbp-breadcrumb-sep{padding:0 6px}#bbpress-forums li.bbp-header ul{font-size:14px}.bbp-forum-title,#bbpress-forums .bbp-topic-title .bbp-topic-permalink{font-size:16px;font-weight:700}#bbpress-forums .bbp-topic-started-by{display:inline-block}#bbpress-forums p.bbp-topic-meta a{margin:0 4px 0 0;display:inline-block}#bbpress-forums p.bbp-topic-meta img.avatar,#bbpress-forums ul.bbp-reply-revision-log img.avatar,#bbpress-forums ul.bbp-topic-revision-log img.avatar,#bbpress-forums div.bbp-template-notice img.avatar,#bbpress-forums .widget_display_topics img.avatar,#bbpress-forums .widget_display_replies img.avatar{margin-bottom:-2px;border:0}span.bbp-admin-links{color:#4F4F4F}span.bbp-admin-links a{color:#7C7C7C}.bbp-topic-revision-log-item *{display:inline-block}#bbpress-forums .bbp-topic-content ul.bbp-topic-revision-log,#bbpress-forums .bbp-reply-content ul.bbp-topic-revision-log,#bbpress-forums .bbp-reply-content ul.bbp-reply-revision-log{border-top:1px dotted #474747;padding:10px 0 0;color:#888282}.bbp-topics,.bbp-replies,.topic{position:relative}#subscription-toggle,#favorite-toggle{float:right;line-height:34px;color:#DFDFDF;display:block;border:1px solid #DFDFDF;padding:0;margin:0;font-size:12px;border:0!important}.bbp-user-subscriptions #subscription-toggle,.bbp-user-favorites #favorite-toggle{position:absolute;top:0;right:0;line-height:20px}.bbp-reply-author br{display:none}#bbpress-forums li{text-align:left}li.bbp-forum-freshness,li.bbp-topic-freshness{width:23%}.bbp-topics-front ul.super-sticky,.bbp-topics ul.super-sticky,.bbp-topics ul.sticky,.bbp-forum-content ul.sticky{background-color:#2C2C2C!important;border-radius:0!important;font-size:1.1em}#bbpress-forums div.odd,#bbpress-forums ul.odd{background-color:#0D0D0D!important}div.bbp-template-notice a{display:inline-block}div.bbp-template-notice a:first-child,div.bbp-template-notice a:last-child{display:inline-block}#bbp_topic_title,#bbp_topic_tags{width:400px}#bbp_stick_topic_select,#bbp_topic_status_select,#display_name{width:200px}#bbpress-forums #bbp-your-profile fieldset span.description{color:#FFF;border:#353535 1px solid;background-color:#222!important;margin:16px 0}#bbpress-forums fieldset.bbp-form{margin-bottom:40px}.bbp-form .quicktags-toolbar{border:1px solid #EBEBEB}.bbp-form .bbp-the-content,#bbpress-forums #description{border-width:1px!important;height:200px!important}#bbpress-forums #bbp-single-user-details{width:100%;float:none;border-bottom:1px solid #080808;box-shadow:0 1px 0 rgba(34,34,34,0.8);margin:0 0 20px;padding:0 0 20px}#bbpress-forums #bbp-user-wrapper h2.entry-title{margin:-2px 0 20px;display:inline-block;border-bottom:1px solid #FF0078}#bbpress-forums #bbp-single-user-details #bbp-user-navigation a{padding:2px 8px}#bbpress-forums #bbp-single-user-details #bbp-user-navigation{display:inline-block}#bbpress-forums #bbp-user-body,.bbp-user-section p{margin:0}.bbp-user-section{margin:0 0 30px}#bbpress-forums #bbp-single-user-details #bbp-user-avatar{margin:0 20px 0 0;width:auto;display:inline-block}#bbpress-forums div.bbp-the-content-wrapper input{width:auto!important}input#bbp_topic_subscription{width:auto;display:inline-block;vertical-align:-webkit-baseline-middle}.widget_display_replies a,.widget_display_topics a{display:inline-block}.widget_display_replies li,.widget_display_forums li,.widget_display_views li,.widget_display_topics li{display:block;border-bottom:1px solid #282828;line-height:32px;position:relative}.widget_display_replies li div,.widget_display_topics li div{font-size:11px}.widget_display_stats dt{display:block;border-bottom:1px solid #282828;line-height:32px;position:relative}.widget_display_stats dd{float:right;margin:-40px 0 0;color:#5F5F5F}#bbpress-forums div.bbp-topic-content code,#bbpress-forums div.bbp-reply-content code,#bbpress-forums div.bbp-topic-content pre,#bbpress-forums div.bbp-reply-content pre{background-color:#FFF;padding:12px 20px;max-width:96%;margin-top:0}#bbpress-forums div.bbp-forum-author img.avatar,#bbpress-forums div.bbp-topic-author img.avatar,#bbpress-forums div.bbp-reply-author img.avatar{border-radius:100%}#bbpress-forums li.bbp-header,#bbpress-forums li.bbp-footer,#bbpress-forums li.bbp-body ul.forum,#bbpress-forums li.bbp-body ul.topic,div.bbp-forum-header,div.bbp-topic-header,div.bbp-reply-header{border-top:1px solid #252525!important}#bbpress-forums ul.bbp-lead-topic,#bbpress-forums ul.bbp-topics,#bbpress-forums ul.bbp-forums,#bbpress-forums ul.bbp-replies,#bbpress-forums ul.bbp-search-results,#bbpress-forums fieldset.bbp-form,#subscription-toggle,#favorite-toggle{border:1px solid #252525!important}#bbpress-forums div.bbp-forum-header,#bbpress-forums div.bbp-topic-header,#bbpress-forums div.bbp-reply-header{background-color:#1A1A1A!important}#bbpress-forums div.even,#bbpress-forums ul.even{background-color:#161616!important}.bbp-view-title{display:block}div.fixed_contact,i.backtotop,i.fixed_contact,.ajax_search_results{background:#151515}.nice-select{background-color:#fff;color:#000}.nice-select .list{background:#fff}.woocommerce div.product .woocommerce-tabs ul.tabs li.active a,.woocommerce div.product .woocommerce-tabs ul.tabs li a{color: inherit}.woocommerce #reviews #comments ol.commentlist li .comment-text{border-color:rgba(167, 167, 167, 0.2) !important}.woocommerce div.product .woocommerce-tabs ul.tabs li.active{background:rgba(167, 167, 167, 0.2)}.woocommerce div.product .woocommerce-tabs ul.tabs li::before,.woocommerce div.product .woocommerce-tabs ul.tabs li::after{display:none!important}#comments .commentlist li .avatar{box-shadow: 1px 10px 10px rgba(167, 167, 167, 0.1) !important}.cz_line{background:#fff}.xtra-post-title span{color:rgba(255, 255, 255, 0.6)}.woocommerce div.product div.images .woocommerce-product-gallery__wrapper .zoomImg{background-color:#0b0b0b}.cz_popup_in{background:#171717;color:#fff}';
				}

				// Category page custom background
				if ( is_category() || is_tag() || is_tax() ) {

					global $wp_query;

					if ( ! empty( $wp_query->queried_object->term_id ) ) {

						$tax_meta = get_term_meta( $wp_query->queried_object->term_id, 'codevz_cat_meta', true );

						if ( ! empty( $tax_meta['_css_page_title'] ) ) {

							$extra_css .= '.page_title{' . str_replace( ';', ' !important;', $tax_meta['_css_page_title'] ) . '}';
						
						}

					}

				}

				// Theme styles
				if ( self::$preview ) {

					//wp_add_inline_style( $handle, $extra_css );

					echo '<style id="xtra-inline-css" data-noptimize>' . $extra_css . '</style>';

				}

				if ( ! self::$plugin ) {
					$extra_css .= '.xtra-section-focus{display:none}';
				}

				if ( ! self::$preview || ! self::$plugin ) {

					$ts = self::option( 'css_out' );

					// Fix.
					if ( self::$plugin && ( ! $ts || get_option( 'xtra_generate_css_out' ) ) ) {

						$options = self::option();

						$options[ 'css_out' ] = Codevz_Options::css_out();

						update_option( 'codevz_theme_options', $options );

						update_option( 'xtra_generate_css_out', false );

					}

					// Admin bar. 
					$extra_css .= '.admin-bar .cz_fixed_top_border{top:32px}.admin-bar i.offcanvas-close {top: 32px}.admin-bar .offcanvas_area, .admin-bar .hidden_top_bar{margin-top: 32px}.admin-bar .header_5,.admin-bar .onSticky{top: 32px}@media screen and (max-width:768px) {.admin-bar .header_5,.admin-bar .onSticky,.admin-bar .cz_fixed_top_border,.admin-bar i.offcanvas-close {top: 46px}.admin-bar .onSticky {top: 0}.admin-bar .offcanvas_area,.admin-bar .offcanvas_area,.admin-bar .hidden_top_bar{margin-top:46px;height:calc(100% - 46px);}}';

					// Add styles
					//wp_add_inline_style( $handle, $extra_css . $ts );

					echo '<style id="xtra-inline-css" data-noptimize>' . $extra_css . $ts . '</style>';

				}

			}

		}

		/**
		 * Register theme sidebars
		 * @return object
		 */
		public static function register_sidebars() {

			if ( self::$plugin ) {
				$sides = [ 'primary', 'secondary', 'footer-1', 'footer-2', 'footer-3', 'footer-4', 'footer-5', 'footer-6', 'offcanvas_area' ];
			} else {
				$sides = [ 'primary', 'footer-1', 'footer-2', 'footer-3', 'footer-4' ];
			}

			foreach( (array) self::option( 'sidebars' ) as $i ) {
				if ( ! empty( $i['id'] ) ) {
					$id = strtolower( $i['id'] );
					$sides[] = sanitize_title_with_dashes( $id );
				}
			}

			if ( self::$plugin ) {

				// Woocommerce
				if ( function_exists( 'is_woocommerce' ) ) {
					$sides[] = 'product-primary';
					$sides[] = 'product-secondary';
				}

				if ( function_exists( 'dwqa' ) ) {
					$sides[] = 'dwqa-question-primary';
					$sides[] = 'dwqa-question-secondary';
				}

				if ( function_exists( 'is_bbpress' ) ) {
					$sides[] = 'bbpress-primary';
					$sides[] = 'bbpress-secondary';
				}
				
				if ( function_exists( 'is_buddypress' ) ) {
					$sides[] = 'buddypress-primary';
					$sides[] = 'buddypress-secondary';
				}
				
				if ( function_exists( 'EDD' ) ) {
					$sides[] = 'download-primary';
					$sides[] = 'download-secondary';
				}

			}

			$titles = [
				'primary' 		=> esc_html__( 'Primary', 'xtra' ),
				'secondary' 	=> esc_html__( 'Secondary', 'xtra' ),
				'footer-1' 		=> esc_html__( 'Footer 1', 'xtra' ),
				'footer-2' 		=> esc_html__( 'Footer 2', 'xtra' ),
				'footer-3' 		=> esc_html__( 'Footer 3', 'xtra' ),
				'footer-4' 		=> esc_html__( 'Footer 4', 'xtra' ),
				'footer-5' 		=> esc_html__( 'Footer 5', 'xtra' ),
				'footer-6' 		=> esc_html__( 'Footer 6', 'xtra' ),
				'offcanvas_area' => esc_html__( 'Offcanvas', 'xtra' ),
				'product-primary' => esc_html__( 'Shop primary', 'xtra' ),
				'product-secondary' => esc_html__( 'Shop secondary', 'xtra' ),
				'portfolio-primary' => esc_html__( 'Portfolio primary', 'xtra' ),
				'portfolio-secondary' => esc_html__( 'Portfolio secondary', 'xtra' ),
			];
			
			// Post types
			$cpt = (array) get_option( 'codevz_post_types' );

			if ( self::$plugin ) {

				$cpt['portfolio'] = self::option( 'slug_portfolio', 'portfolio' );

			}

			// Custom post type UI
			if ( function_exists( 'cptui_get_post_type_slugs' ) ) {
				$cptui = cptui_get_post_type_slugs();
				if ( is_array( $cptui ) ) {
					$cpt = wp_parse_args( $cptui, $cpt );
				}
			}

			// All CPT
			foreach( $cpt as $key => $value ) {
				if ( $value ) {
					if ( $key === 'portfolio' ) {
						$sides[ 'portfolio-primary' ] = $value . '-primary';
						$sides[ 'portfolio-secondary' ] = $value . '-secondary';
					} else {
						$sides[] = $value . '-primary';
						$sides[] = $value . '-secondary';
					}
				}
			}

			// Custom sidebars
			$move_sidebars = get_option( 'codevz_move__custom_sidebars_to_options' );
			if ( empty( $move_sidebars ) ) {
				$custom_s = (array) get_option( 'codevz_custom_sidebars' );
				$sides = wp_parse_args( $custom_s, $sides );

				$options = (array) get_option( 'codevz_theme_options' );
				$options['custom_sidebars'] = $custom_s;
				update_option( 'codevz_theme_options', $options );
				update_option( 'codevz_move__custom_sidebars_to_options', 1 );
			} else {
				$custom_s = (array) self::option( 'custom_sidebars' );
				$sides = wp_parse_args( $custom_s, $sides );
			}

			foreach( $sides as $key => $id ) {
				if ( $id ) {
					$id = esc_html( $id );

					if ( isset( $titles[ $id ] ) ) {
						$name = $titles[ $id ];
					} else {
						$name = ucwords( str_replace( [ 'cz-custom-', '-' ], ' ', $id ) );
					}

					$class 	= self::contains( $id, 'footer' ) ? 'footer_widget' : 'widget';

					if ( $key === 'portfolio-primary' ) {
						$id = 'portfolio-primary';
					} else if ( $key === 'portfolio-secondary' ) {
						$id = 'portfolio-secondary';
					}

					register_sidebar([ 
						'name'			=> $name,
						'id'			=> $id,
						'description'   => esc_html__( 'Add widgets here to appear in your', 'xtra' ) . ' ' . $name,
						'before_widget'	=> '<div id="%1$s" class="' . esc_attr( $class ) . ' clr %2$s">',
						'after_widget'	=> '</div>',
						'before_title'	=> '<h4>',
						'after_title'	=> '</h4>'
					]);
				}
			}
		}

		/**
		 * WP head
		 * @return string
		 */
		public static function wp_head() {
			if ( is_singular() && pings_open() ) {
				$url = get_bloginfo( 'pingback_url' );
				printf( '<link rel="pingback" href="%s">' . "\n", $url );
			}
		}

		/**
		 * WP Menu current class
		 * @return string
		 */
		public static function menu_current_class( $classes, $item ) {

			$url = trailingslashit( $item->url );
			$base = basename( $url );

			// Default.
			$classes[] = 'cz';

			// Fix anchor links
			if ( self::contains( $url, '/#' ) || is_page_template() ) {
				return $classes;
			}

			// Find parent menu
			$in_array = in_array( 'current_page_parent', $classes );

			// Current menu
			if ( in_array( 'current-menu-ancestor', $classes ) || in_array( 'current-menu-item', $classes ) || ( $in_array && get_post_type() === 'post' ) ) {
				$classes[] = 'current_menu';
			}

			// Current menu parent.
			if ( have_posts() ) { 

				$c = get_post_type_object( get_post_type( self::$post->ID ) );

				if ( ! empty( $c ) ) {

					// Check custom link of post or page in menu.
					$con1 = ( is_singular() && $url === trailingslashit( get_the_permalink( self::$post->ID ) ) );

					// Check post type slug changes.
					$con2 = ( isset( $c->rewrite['slug'] ) && self::contains( $base, $c->rewrite['slug'] ) && $in_array );

					// Check with post type name.
					$con3 = ( $base === strtolower( urlencode( html_entity_decode( $c->name ) ) ) );

					// Fix multisite same name as post type name conflict.
					if ( $con3 && trailingslashit( self::$home_url ) === $url ) {
						$con3 = false;
					}

					// Check with post type label.
					$con4 = ( $base === strtolower( urlencode( html_entity_decode( $c->label ) ) ) );
					
					// Check if CPT name is different in menu URL and fix also for non-english lang.
					$con5 = ( $base === strtolower( urlencode( html_entity_decode( $c->has_archive ) ) ) );

					if ( $con1 || $con2 || $con3 || $con4 || $con5 ) {
						$classes[] = 'current_menu';
					}

				}

			}

			// Fix: single post with category in menu.
			if ( in_array( 'menu-item-object-category', $classes ) && is_single() ) {

				$key = array_search( 'current-menu-parent', $classes );

				if ( isset( $classes[ $key ] ) ) {
					unset( $classes[ $key ] );
				}

			}

			return $classes;
		}

		/**
		 * Get page post type name.
		 * 
		 * @var Post id
		 * @return String
		 */
		public static function get_post_type( $id = '', $page = false ) {

			if ( self::$plugin ) {

				return Codevz_Plus::get_post_type( $id, $page );

			} else {

				return get_post_type( $id );

			}

		}

		/**
		 * Get page content and generate styles.
		 * 
		 * @var page ID or title.
		 * @return String
		 */
		public static function get_page_as_element( $id = '', $query = 0 ) {

			if ( self::$plugin ) {

				return Codevz_Plus::get_page_as_element( $id, $query );

			}

		}

		/**
		 * Get required data attributes for body
		 * 
		 * @return string
		 */
		public static function intro_attrs() {
			$i = ' data-ajax="' . admin_url( 'admin-ajax.php' ) . '"';

			// Theme colors for live
			if ( self::$preview ) {
				$i .= ' data-primary-color="' . esc_attr( self::option( 'site_color', '#4e71fe' ) ) . '"';
				$i .= ' data-primary-old-color="' . esc_attr( get_option( 'codevz_primary_color', self::option( 'site_color', '#4e71fe' ) ) ) . '"';
				$i .= ' data-secondary-color="' . esc_attr( self::option( 'site_color_sec', 0 ) ) . '"';
				$i .= ' data-secondary-old-color="' . esc_attr( get_option( 'codevz_secondary_color', 0 ) ) . '"';
			}

			return $i;
		}

		/**
		 * Filter WordPress excerpt length
		 * 
		 * @return string
		 */
		public static function excerpt_length() {
			
			$cpt = self::get_post_type();

			$default = 20;

			if ( $cpt && $cpt !== 'post' ) {
				return self::option( 'post_excerpt_' . $cpt, $default );
			}

			return self::option( 'post_excerpt', $default );

		}

		/**
		 * Excerpt read more button
		 * 
		 * @return string
		 * @since 1.0
		 */
		public static function excerpt_more( $more ) {
			return '';
		}

		public static function get_the_excerpt( $excerpt ) {

			$cpt = self::get_post_type();

			if ( $cpt && $cpt !== 'post' && self::contains( $excerpt, ' ' ) ) {
				$excerpt = implode( ' ', array_slice( explode( ' ', $excerpt ), 0, self::option( 'post_excerpt' . ( ( $cpt && $cpt !== 'post' ) ? '_' . $cpt : '' ), 10 ) + 1 ) );
			}

			// Read more title & icon
			if ( $cpt && $cpt !== 'post' ) {
				$title = esc_html( self::option( 'readmore_' . $cpt ) );
				$icon = esc_attr( self::option( 'readmore_icon_' . $cpt ) );
			} else {
				$title = esc_html( self::option( 'readmore' ) );
				$icon = esc_attr( self::option( 'readmore_icon' ) );
			}

			$icon = $icon ? '<i class="' . $icon . '"></i>' : '';
			$button = ( $title || $icon ) ? '<a class="cz_readmore' . ( $title ? '' : ' cz_readmore_no_title' ) . ( $icon ? '' : ' cz_readmore_no_icon' ) . '" href="' . esc_url( get_the_permalink( self::$post->ID ) ) . '">' . $icon . '<span>' . do_shortcode( $title ) . '</span></a>' : '';

			$excerpt_char = self::option( 'post_excerpt_type', false );

			if ( $excerpt_char === '2' ) {
				$excerpt = substr( $excerpt, 0, self::option( 'post_excerpt' . ( ( $cpt && $cpt !== 'post' ) ? '_' . $cpt : '' ), 20 ) );
			}

			return $excerpt ? $excerpt . wp_kses_post( self::option( $cpt . '_excerpt_dots', ' ... ' ) ) . $button : '';

		}

		/**
		 * More tag read more button
		 * 
		 * @return string
		 * @since 2.6
		 */
		public static function the_content_more_link() {
			$cpt = self::get_post_type();

			if ( $cpt && $cpt !== 'post' ) {
				$title = esc_html( self::option( 'readmore_' . $cpt ) );
				$icon = esc_attr( self::option( 'readmore_icon_' . $cpt ) );
			} else {
				$title = esc_html( self::option( 'readmore' ) );
				$icon = esc_attr( self::option( 'readmore_icon' ) );
			}
			
			$icon = $icon ? '<i class="' . $icon . '"></i>' : '';

			$more = '';
			if ( strpos( self::$post->post_content, '<!--more-->' ) ) {
				$more = '#more-' . esc_attr( self::$post->ID );
			}

			return ( $title || $icon ) ? '<a class="cz_readmore' . ( $title ? '' : ' cz_readmore_no_title' ) . ( $icon ? '' : ' cz_readmore_no_icon' ) . '" href="' . esc_url( get_the_permalink( self::$post->ID ) ) . esc_attr( $more ) . '">' . $icon . '<span>' . $title . '</span></a>' : '';
		}

		/**
		 * Get next|prev posts for single post page
		 * 
		 * @return string
		 */
		public static function next_prev_item() {
			$cpt = self::get_post_type();
			$tax = ( $cpt === 'post' ) ? 'category' : $cpt . '_cat';
			$prevPost = get_previous_post( true, '', $tax ) ? get_previous_post( true, '', $tax ) : get_previous_post();
			$nextPost = get_next_post( true, '', $tax ) ? get_next_post( true, '', $tax ) : get_next_post();

			ob_start();
			if ( $prevPost || $nextPost ) { ?>
				<ul class="next_prev clr">
					<?php if( $prevPost ) { ?>
						<li class="previous">
							<?php $prevthumbnail = get_the_post_thumbnail( $prevPost->ID, 'thumbnail' ); ?>
							<?php previous_post_link( '%link', '<i class="fa fa-angle-' . ( self::$is_rtl ? 'right' : 'left' ) . '"></i><h4><small>' . esc_html( do_shortcode( self::option( 'prev_' . $cpt, self::option( 'prev_post'  ) ) ) ) . '</small>%title</h4>' ); ?>
						</li>
					<?php } if( $nextPost ) { ?>
						<li class="next">
							<?php $nextthumbnail = get_the_post_thumbnail( $nextPost->ID, 'thumbnail' ); ?>
							<?php next_post_link( '%link', '<h4><small>' . esc_html( do_shortcode( self::option( 'next_' . $cpt, self::option( 'next_post' ) ) ) ) . '</small>%title</h4><i class="fa fa-angle-' . ( self::$is_rtl ? 'left' : 'right' ) . '"></i>' ); ?>
						</li>
					<?php } 

						$archive_icon = false; //self::option( 'next_prev_archive_icon' );
						if ( $archive_icon ) {
					?>
					<li class="cz-next-prev-archive">
						<a href="<?php echo esc_url( get_post_type_archive_link( $cpt ) ); ?>" title="<?php echo ucwords( $cpt ); ?>"><i class="<?php echo esc_attr( $archive_icon ); ?>"></i></a>
					</li>
					<?php 
						}
					?>
				</ul>
			<?php 
			}

			return ob_get_clean();
		}

		/**
		 * Modify category widget output
		 * 
		 * @return string
		 */
		public static function wp_list_categories( $i ) {
			$i = preg_replace( '/cat-item\scat-item-(.?[0-9])\s/', '', $i );
			$i = preg_replace( '/current-cat/', 'current', $i );
			$i = preg_replace( '/\sclass="cat-item\scat-item-(.?[0-9])"/', '', $i );
			$i = preg_replace( '/\stitle="(.*?)"/', '', $i );
			$i = preg_replace( '/\sclass=\'children\'/', '', $i );
			$i = str_replace( '</a> (', '</a><span>(', $i );

			return str_replace( ')', ')</span>', $i );
		}

		/**
		 * Modify archive widget output
		 * 
		 * @return string
		 */
		public static function get_archives_link( $i ) {
			$i = str_replace( '</a>&nbsp;(', '</a><span>(', $i );

			return str_replace( ')', ')</span>', $i );
		}

		/**
		 * Enqueue google font
		 * 
		 * @return string|null
		 */
		public static function enqueue_font( $f = '' ) {

			if ( ! $f || self::contains( $f, 'custom_' ) ) {
				return;
			} else {
				$f = self::contains( $f, ';' ) ? self::get_string_between( $f, 'font-family:', ';' ) : $f;
				$f = str_replace( '=', ':', $f );
			}

			$defaults = apply_filters( 'csf/field/fonts/websafe', array(
				'inherit' 			=> 'inherit', 
				'initial' 			=> 'initial', 
				'FontAwesome' 		=> 'FontAwesome', 
				'Font Awesome 5 Free' => 'Font Awesome 5 Free', 
				'czicons' 			=> 'czicons', 
				'fontelo' 			=> 'fontelo',
				'Arial' 			=> 'Arial',
				'Arial Black' 		=> 'Arial Black',
				'Comic Sans MS' 	=> 'Comic Sans MS',
				'Impact' 			=> 'Impact',
				'Lucida Sans Unicode' => 'Lucida Sans Unicode',
				'Tahoma' 			=> 'Tahoma',
				'Trebuchet MS' 		=> 'Trebuchet MS',
				'Verdana' 			=> 'Verdana',
				'Courier New' 		=> 'Courier New',
				'Lucida Console' 	=> 'Lucida Console',
				'Georgia, serif' 	=> 'Georgia, serif',
				'Palatino Linotype' => 'Palatino Linotype',
				'Times New Roman' 	=> 'Times New Roman'
			));

			// Custom fonts
			$custom_fonts = (array) self::option( 'custom_fonts' );
			foreach ( $custom_fonts as $a ) {
				if ( ! empty( $a['font'] ) ) {
					$defaults[ $a['font'] ] = $a['font'];
				}
			}

			$f = self::contains( $f, ':' ) ? $f : $f . ':300,400,700';
			$f = explode( ':', $f );
			$p = empty( $f[1] ) ? '' : ':' . trim( $f[1] );

			$font = isset( $f[0] ) ? trim( $f[0] ) : '';

			if ( $font && ! isset( $defaults[ $font ] ) ) {
				wp_enqueue_style( 'google-font-' . sanitize_title_with_dashes( $font ), 'https://fonts.googleapis.com/css?family=' . str_replace( [ '"', "'" ], '', str_replace( ' ', '+', ucfirst( $font ) ) ) . $p );
			}

		}

		/**
		 * SK Style + load font
		 * 
		 * @return string
		 */
		public static function sk_inline_style( $sk = '', $important = false ) {

			$sk = str_replace( 'CDVZ', '', $sk );

			if ( self::contains( $sk, 'font-family' ) ) {
				self::enqueue_font( $sk );

				// Extract font + params && Fix font for CSS
				$font = $o_font = self::get_string_between( $sk, 'font-family:', ';' );
				$font = str_replace( '=', ':', $font );
				
				if ( self::contains( $font, ':' ) ) {
					$font = explode( ':', $font );
					if ( ! empty( $font[0] ) ) {
						$sk = str_replace( $o_font, "'" . $font[0] . "'", $sk );
					}
				} else {
					$sk = str_replace( $font, "'" . $font . "'", $sk );
				}
			}

			if ( $important ) {
				$sk = str_replace( ';', ' !important;', $sk );
			}

			if ( self::$is_rtl ) {
				return str_replace( 'RTL', '', $sk );
			} else if ( self::contains( $sk, 'RTL' ) ) {
				return strstr( $sk, 'RTL', true );
			} else {
				return $sk;
			}

		}

		/**
		 * Get element for row builder 
		 * 
		 * @return string
		 */
		public static function get_row_element( $i, $m = [] ) {

			// Check element
			if ( empty( $i['element'] ) ) {
				return;
			}

			// Check user login
			$is_user_logged_in = is_user_logged_in();

			// Element visibility for users
			if ( ! empty( $i['elm_visibility'] ) ) {
				$v = $i['elm_visibility'];
				if ( ( $v === '1' && ! $is_user_logged_in ) || ( $v === '2' && $is_user_logged_in ) ) {
					return;
				}
			}

			// Element margin
			$style = '';
			if ( ! empty( $i['margin'] ) ) {
				foreach( $i['margin'] as $key => $val ) {
					$style .= $val ? 'margin-' . esc_attr( $key ) . ':' . esc_attr( $val ) . ';' : '';
				}
			}

			// Cutstom page width
			if ( ! empty( $i['header_elements_width'] ) ) {
				$style .= 'width:' . esc_attr( $i['header_elements_width'] ) . ';';
			}

			// Classes of element
			$elm_class = empty( $i['vertical'] ) ? '' : ' cz_vertical_elm';
			$elm_class .= empty( $i['elm_on_sticky'] ) ? '' : ' ' . $i['elm_on_sticky'];
			$elm_class .= empty( $i['hide_on_mobile'] ) ? '' : ' hide_on_mobile';
			$elm_class .= empty( $i['hide_on_tablet'] ) ? '' : ' hide_on_tablet';
			$elm_class .= empty( $i['elm_center'] ) ? '' : ' cz_elm_center';

			// Start element
			$elm = $i['element'];
			$element_id = esc_attr( $elm . '_' . $m['id'] );
			$element_uid = esc_attr( $element_id . $m['depth'] );
			$data_settings = self::$preview ? " data-settings='" . json_encode( $i, JSON_HEX_APOS ) . "'" : '';
			echo '<div class="cz_elm ' . esc_attr( $element_id . $m['depth'] . ' inner_' . $element_id . $m['inner_depth'] . $elm_class ) . '" style="' . esc_attr( $style ) . '"' . wp_kses_post( $data_settings ) . '>';

			if ( self::$preview ) {

				echo '<i class="xtra-section-focus fas fa-cog" data-section="' . esc_attr( $m['id'] ) . '" data-id="' . esc_attr( $m['inner_depth'] ) . '"></i>';

				if ( $elm === 'logo' || $elm === 'logo_2' ) {
					echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-image" data-section="header_logo"></i>';
				}

				if ( $elm === 'social' ) {
					echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-pen" data-section="header_social"></i>';
				}

			}

			// Check element
			if ( $elm === 'logo' || $elm === 'logo_2' ) {

				$logo = do_shortcode( self::option( $elm ) );

				if ( $logo ) {

					$sizes = method_exists( 'Codevz_Plus', 'getimagesize' ) ? Codevz_Plus::getimagesize( $logo ) : '';

					if ( $sizes ) {

						list( $lw, $lh ) = $sizes;

						if ( ! empty( $i[ 'logo_width' ] ) ) {

							$nw = preg_replace( '/[^0-9]/', '', $i[ 'logo_width' ] );
							$lp = preg_replace( '/[0-9]/', '', $i[ 'logo_width' ] );

							$lh = (integer) round( ( $lh * $nw ) / $lw, 0 );
							$lw = (integer) $nw;

						}

					} else {

						$lw = empty( $i[ 'logo_width' ] ) ? 'auto' : (integer) $i[ 'logo_width' ];
						$lh = 'auto';

					}

					if ( empty( $lp ) ) {
						$lp = 'px';
					}

					$escaped_size_on_sticky = empty( $i[ 'logo_width_sticky' ] ) ? '' : ' data-cz-style=".onSticky .' . esc_attr( $element_id . $m['depth'] ) . ' .logo_is_img img{width:' . esc_attr( $i[ 'logo_width_sticky' ] ) . ' !important}"';

					$logo_html = '<div class="logo_is_img ' . esc_attr( $elm ) . '"><a href="' . self::$home_url . '" title="' . esc_html( get_bloginfo( 'description' ) ) . '"><img src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="' . esc_attr( $lw ) . '" height="' . esc_attr( $lh ) . '" style="width: ' . esc_attr( $lw . $lp ) . '"' . $escaped_size_on_sticky . '></a>';

				} else {

					$logo_html = '<div class="logo_is_text ' . esc_attr( $elm ) . '"><a href="' . self::$home_url . '" title="' . esc_html( get_bloginfo( 'description' ) ) . '"><h1>' . esc_html( get_bloginfo( 'name' ) ) . '</h1></a>';
				
				}

				// Lazyload logo.
				echo self::$plugin ? Codevz_Plus::lazyload( $logo_html ) : $logo_html;

				$logo_tooltip = self::option( 'logo_hover_tooltip' );
				if ( $logo_tooltip && $logo_tooltip !== 'none' && $m['id'] !== 'header_4' && $m['id'] !== 'header_5' ) {
					echo '<div class="logo_hover_tooltip" data-cz-style=".logo_hover_tooltip{position:absolute;left:0;opacity:0;z-index:2;width:500px;padding:30px;margin:10px 0 0;background:#fff;border-radius:2px;visibility:hidden;box-sizing:border-box;box-shadow:0 8px 40px rgba(17,17,17,.1);transition:all .2s ease-in-out}.rtl .logo_hover_tooltip{left:0;right:0}.logo:hover .logo_hover_tooltip{margin:0;visibility:visible;opacity:1}footer .logo_hover_tooltip,footer .logo:hover .logo_hover_tooltip{display:none;visibility:hidden;opacity:0}">' . self::get_page_as_element( esc_html( $logo_tooltip ) ) . '</div>';
				}

				echo '</div>';

			} else if ( $elm === 'menu' ) {

				$type = empty( $i['menu_type'] ) ? 'cz_menu_default' : $i['menu_type'];
				if ( $type === 'offcanvas_menu_left' ) {
					$type = 'offcanvas_menu inview_left';
				} else if ( $type === 'offcanvas_menu_right' ) {
					$type = 'offcanvas_menu inview_right';
				}

				$elm_uniqid = 'cz_mi_' . rand( 11111, 99999 );

				$menu_title = isset( $i['menu_title'] ) ? do_shortcode( $i['menu_title'] ) : '';
				$menu_icon = empty( $i['menu_icon'] ) ? 'fa fa-bars' : $i['menu_icon'];
				$icon_style = empty( $i['sk_menu_icon'] ) ? '' : self::sk_inline_style( $i['sk_menu_icon'] );

				$data_style = empty( $i['sk_menu_title'] ) ? '' : '.' . $elm_uniqid . ' span{' . self::sk_inline_style( $i['sk_menu_title'] ) . '}';
				$data_style .= empty( $i['sk_menu_title_hover'] ) ? '' : '.' . $elm_uniqid . ':hover span{' . self::sk_inline_style( $i['sk_menu_title_hover'] ) . '}';
				$data_style .= empty( $i['sk_menu_icon_hover'] ) ? '' : '.' . $elm_uniqid . ':hover{' . self::sk_inline_style( $i['sk_menu_icon_hover'], true ) . '}';
				$data_style = $data_style ? ' data-cz-style="' . $data_style . '"' : '';

				$menu_icon_class = $menu_title ? ' icon_plus_text' : '';
				$menu_icon_class .= ' ' . $elm_uniqid;

				// Add icon and mobile menu
				if ( $type && $type !== 'offcanvas_menu' && $type !== 'cz_menu_default' ) {
					echo '<i class="' . esc_attr( $menu_icon . ' icon_' . $type . $menu_icon_class ) . '" style="' . esc_attr( $icon_style ) . '"' . $data_style . '><span>' . esc_html( $menu_title ) . '</span></i>';
				}
				echo '<i class="' . esc_attr( $menu_icon . ' hide icon_mobile_' . $type . $menu_icon_class ) . '" style="' . esc_attr( $icon_style ) . '"' . $data_style . '><span>' . esc_html( $menu_title ) . '</span></i>';

				// Default
				if ( empty( $i['menu_location'] ) ) {
					$i['menu_location'] = 'primary';
				}

				// Check for meta box and set one page instead primary
				$page_menu = self::meta( 0, 'one_page' );
				if ( $page_menu ) {
					$i['menu_location'] = ( $page_menu === '1' ) ? 'one-page' : $page_menu;
				}

				// Disable three dots auto responsive
				$type .= empty( $i['menu_disable_dots'] ) ? '' : ' cz-not-three-dots';

				// Indicators
				$indicator  = self::get_string_between( self::option( '_css_menu_indicator_a_' . $m['id'] ), '_class_indicator:', ';' );
				$indicator2 = self::get_string_between( self::option( '_css_menu_ul_indicator_a_' . $m['id'] ), '_class_indicator:', ';' );

				// Menu
				wp_nav_menu(
					apply_filters( 'xtra_nav_menu',
						[
							'theme_location' 	=> esc_attr( $i['menu_location'] ),
							'cz_row_id' 		=> esc_attr( $m['id'] ),
							'cz_indicator' 		=> $indicator,
							'container' 		=> false,
							'fallback_cb' 		=> false,
							'walker' 			=> class_exists( 'Codevz_Walker_nav' ) ? new Codevz_Walker_nav() : false,
							'items_wrap' 		=> '<ul id="' . esc_attr( $element_id ) . '" class="sf-menu clr ' . esc_attr( $type ) . '" data-indicator="' . esc_attr( $indicator ) . '" data-indicator2="' . esc_attr( $indicator2 ) . '">%3$s</ul>'
						]
					)
				);

				$iconx = self::$plugin ? 'fa czico-198-cancel' : 'fa fa-times';

				echo '<i class="' . esc_attr( $iconx ) . ' cz_close_popup xtra-close-icon hide"></i>';

				$mobile_menu_social = self::option( 'mobile_menu_social' );
				$mobile_menu_text = self::option( 'mobile_menu_text' );

				// Mobile menu additional.
				if ( $element_id === 'menu_header_4' && ( $mobile_menu_social || $mobile_menu_text || self::$preview ) ) {

					echo '<div class="xtra-mobile-menu-additional hide">';

					if ( $mobile_menu_social && self::$plugin ) {
						echo Codevz_Plus::social(
							[
								'color_mode' => self::option( 'mobile_menu_social_color_mode' )
							]
						);
					}

					if ( $mobile_menu_text || self::$preview ) {
						echo '<div class="xtra-mobile-menu-text">' . do_shortcode( wp_kses_post( $mobile_menu_text ) ) . '</div>';
					}

					echo '</div>';

				}

			} else if ( $elm === 'social' && self::$plugin ) {

				$social = Codevz_Plus::social(
					[
						'type' 		=> isset( $i[ 'social_type' ] ) ? $i[ 'social_type' ] : '',
						'columnar' 	=> isset( $i[ 'social_columnar' ] ) ? $i[ 'social_columnar' ] : ''
					]
				);

				if ( ! empty( $i[ 'social_type' ] ) ) {

					$icon = empty( $i['social_icon'] ) ? 'fas fa-share-alt' : $i['social_icon'];
					
					$icon_style = empty( $i['sk_social_icon'] ) ? '' : ' style="' . self::sk_inline_style( $i['sk_social_icon'] ) . '"';
					$hover_css = empty( $i['sk_social_icon_hover'] ) ? '' : ' data-cz-style=".' . $element_id . $m['depth'] . ' .xtra-social-icon-trigger:hover {' . self::sk_inline_style( $i['sk_social_icon_hover'], true ) . '}"';

					$icon = '<i class="xtra-social-icon-trigger ' . $icon . '"' . $icon_style . $hover_css . '></i>';
					
					$container = empty( $i['sk_social_container'] ) ? '' : self::sk_inline_style( $i['sk_social_container'] );

					if ( $i[ 'social_type' ] === 'popup' ) {

						echo '<a href="#xtra-social-popup">' . wp_kses_post( $icon ) . '</a>';

						$iconx = self::$plugin ? 'fa czico-198-cancel' : 'fa fa-times';

						echo do_shortcode( '[cz_popup id_popup="xtra-social-popup" icon="' . esc_attr( $iconx ) . '" sk_popup="' . wp_kses_post( $container ) . '" sk_icon="color:#fff;"]' . wp_kses_post( $social ) . '[/cz_popup]' );

					} else {

						echo wp_kses_post( $icon ) . '<div class="xtra-social-dropdown" style="' . wp_kses_post( $container ) . '">' . wp_kses_post( $social ) . '</div>';

					}

				} else {

					echo wp_kses_post( $social );

				}

			} else if ( $elm === 'image' && isset( $i['image'] ) ) {

				$link = empty( $i['image_link'] ) ? '' : do_shortcode( $i['image_link'] );
				$width = empty( $i['image_width'] ) ? 'auto' : $i['image_width'];
				$new_tab = empty( $i['image_new_tab'] )? '' : 'rel="noopener noreferrer" target="_blank"' ;

				if ( $link ) {
					echo '<a class="elm_h_image" href="' . esc_url( $link ) . '" ' . $new_tab . '><img src="' . esc_url( do_shortcode( $i['image'] ) ) . '" alt="image" style="width:' . esc_attr( $width ) . '" width="' . esc_attr( $width ) . '" height="auto" /></a>';
				} else {
					echo '<img src="' . esc_url( do_shortcode( $i['image'] ) ) . '" alt="#" width="' . esc_attr( $width ) . '" height="auto" style="width:' . esc_attr( $width ) . '" />';
				}

			} else if ( $elm === 'icon' ) {

				$link = isset( $i['it_link'] ) ? do_shortcode( $i['it_link'] ) : '';
				$target = empty( $i['it_link_target'] ) ? '' : ' target="_blank"';

				$text_style = empty( $i['sk_it'] ) ? '' : self::sk_inline_style( $i['sk_it'] );
				$icon_style = empty( $i['sk_it_icon'] ) ? '' : self::sk_inline_style( $i['sk_it_icon'] );

				$hover_css = empty( $i['sk_it_hover'] ) ? '' : '.' . $element_id . $m['depth'] . ' .elm_icon_text:hover .it_text {' . self::sk_inline_style( $i['sk_it_hover'], true ) . '}';
				$hover_css .= empty( $i['sk_it_icon_hover'] ) ? '' : '.' . $element_id . $m['depth'] . ' .elm_icon_text:hover > i {' . self::sk_inline_style( $i['sk_it_icon_hover'], true ) . '}';
				$hover_css = $hover_css ? ' data-cz-style="' . $hover_css . '"' : '';

				if ( $link ) {
					echo '<a class="elm_icon_text" href="' . esc_attr( $link ) . '"' . $target . $hover_css . '>';
				} else {
					echo '<div class="elm_icon_text"' . $hover_css . '>';
				}

				if ( ! empty( $i['it_icon'] ) ) {
					echo '<i class="' . esc_attr( $i['it_icon'] ) . '" style="' . esc_attr( $icon_style ) . '"></i>';
				}

				if ( ! empty( $i['it_text'] ) ) {
					echo '<span class="it_text ' . esc_attr( ( empty( $i['it_icon'] ) ? '' : 'ml10' ) ) . '" style="' . esc_attr( $text_style ) . '">' . do_shortcode( wp_kses_post( str_replace( '%year%', current_time( 'Y' ), $i['it_text'] ) ) ) . '</span>';
				} else {
					echo '<span class="it_text"></span>';
				}
				
				if ( $link ) {
					echo '</a>';
				} else {
					echo '</div>';
				}

			} else if ( $elm === 'icon_info' ) {

				wp_enqueue_script( 'xtra-icon-text' );

				$link = isset( $i['it_link'] ) ? do_shortcode( $i['it_link'] ) : '';
				$target = empty( $i['it_link_target'] ) ? '' : ' target="_blank"';

				$text_style 	= empty( $i['sk_it'] ) ? '' : self::sk_inline_style( $i['sk_it'] );
				$text_2_style 	= empty( $i['sk_it_2'] ) ? '' : self::sk_inline_style( $i['sk_it_2'] );
				$icon_style 	= empty( $i['sk_it_icon'] ) ? '' : self::sk_inline_style( $i['sk_it_icon'] );

				$wrap_style = empty( $i['sk_it_wrap'] ) ? '' : self::sk_inline_style( $i['sk_it_wrap'] );
				$wrap_hover = empty( $i['sk_it_wrap_hover'] ) ? '' : '.' . $element_id . $m['depth'] . ' .cz_elm_info_box:hover {' . self::sk_inline_style( $i['sk_it_wrap_hover'], true ) . '}';
				$wrap_hover .= empty( $i['sk_it_hover'] ) ? '' : '.' . $element_id . $m['depth'] . ' .cz_elm_info_box:hover .cz_info_1 {' . self::sk_inline_style( $i['sk_it_hover'], true ) . '}';
				$wrap_hover .= empty( $i['sk_it_2_hover'] ) ? '' : '.' . $element_id . $m['depth'] . ' .cz_elm_info_box:hover .cz_info_2 {' . self::sk_inline_style( $i['sk_it_2_hover'], true ) . '}';
				$wrap_hover = $wrap_hover ? ' data-cz-style="' . $wrap_hover . '"' : '';

				if ( $link ) {
					echo '<a class="cz_elm_info_box" href="' . esc_url( $link ) . '" style="' . $wrap_style . '"' . $target . $wrap_hover . '>';
				} else {
					echo '<div class="cz_elm_info_box" style="' . $wrap_style . '"' . $wrap_hover . '>';
				}

				if ( ! empty( $i['it_icon'] ) ) {
					$it_hover = empty( $i['sk_it_icon_hover'] ) ? '' : ' data-cz-style=".' . $element_id . $m['depth'] . ' .cz_elm_info_box:hover i {' . self::sk_inline_style( $i['sk_it_icon_hover'], true ) . '}"';
					echo '<i class="cz_info_icon ' . esc_attr( $i['it_icon'] ) . '" style="' . esc_attr( $icon_style ) . '"' . $it_hover . '></i>';
				}

				echo '<div class="cz_info_content">';
				if ( ! empty( $i['it_text'] ) ) {
					echo '<span class="cz_info_1" style="' . esc_attr( $text_style ) . '">' . do_shortcode( wp_kses_post( $i['it_text'] ) ) . '</span>';
				}
				if ( ! empty( $i['it_text_2'] ) ) {
					echo '<span class="cz_info_2" style="' . esc_attr( $text_2_style ) . '">' . do_shortcode( wp_kses_post( $i['it_text_2'] ) ) . '</span>';
				}
				echo '</div>';
				
				if ( $link ) {
					echo '</a>';
				} else {
					echo '</div>';
				}

			} else if ( $elm === 'search' ) {

				wp_enqueue_script( 'xtra-search' );

				$icon_style = empty( $i['sk_search_icon'] ) ? '' : self::sk_inline_style( $i['sk_search_icon'] );
				$icon_style_hover = empty( $i['sk_search_icon_hover'] ) ? '' : ' data-cz-style=".' . $element_uid . ' .xtra-search-icon:hover{' . self::sk_inline_style( $i['sk_search_icon_hover'], true ) . '}"';
				$icon_in_style = empty( $i['sk_search_icon_in'] ) ? '' : self::sk_inline_style( $i['sk_search_icon_in'] );
				$input_style = empty( $i['sk_search_input'] ) ? '' : self::sk_inline_style( $i['sk_search_input'] );
				$outer_style = empty( $i['sk_search_con'] ) ? '' : self::sk_inline_style( $i['sk_search_con'] );
				$ajax_style = empty( $i['sk_search_ajax'] ) ? '' : self::sk_inline_style( $i['sk_search_ajax'] );
				$icon = empty( $i['search_icon'] ) ? 'fa fa-search' : $i['search_icon'];
				$ajax = empty( $i['ajax_search'] ) ? '' : ' cz_ajax_search';

				$form_style = empty( $i['search_form_width'] ) ? '' : 'width: ' . esc_attr( $i['search_form_width'] );

				$i['search_type'] = empty( $i['search_type'] ) ? 'form' : $i['search_type'];
				$i['search_placeholder'] = empty( $i['search_placeholder'] ) ? '' : do_shortcode( $i['search_placeholder'] );

				echo '<div class="search_with_icon search_style_' . esc_attr( $i['search_type'] . $ajax ) . '">';
				echo self::contains( esc_attr( $i['search_type'] ), 'form' ) ? '' : '<i class="xtra-search-icon ' . esc_attr( $icon ) . '" style="' . esc_attr( $icon_style ) . '"' . $icon_style_hover . '></i>';

				$iconx = self::$plugin ? 'fa czico-198-cancel' : 'fa fa-times';

				echo '<i class="' . esc_attr( $iconx ) . ' cz_close_popup xtra-close-icon hide"></i>';
				
				echo '<div class="outer_search" style="' . esc_attr( $outer_style ) . '"><div class="search" style="' . esc_attr( $form_style ) . '">'; ?>
					<form method="get" action="<?php echo self::$home_url; ?>" autocomplete="off">
						<?php 
							if ( $i['search_type'] === 'icon_full' ) {
								echo '<span' . ( empty( $i['sk_search_title'] ) ? '' : ' style="' . esc_attr( self::sk_inline_style( $i['sk_search_title'] ) ) . '"' ) . '>' . esc_html( $i['search_placeholder'] ) . '</span>';
								$i['search_placeholder'] = '';
							}

							if ( $ajax ) {
								echo '<input name="nonce" type="hidden" value="' . wp_create_nonce( 'ajax_search_nonce' ) . '" />';
							}

							if ( ! empty( $i[ 'search_only_products' ] ) ) {
								echo '<input name="post_type" type="hidden" value="product" />';
							}

							if ( ! empty( $i[ 'search_no_thumbnail' ] ) ) {
								echo '<input name="no_thumbnail" type="hidden" value="' . esc_attr( $i['search_no_thumbnail'] ) . '" />';
							}

							if ( ! empty( $i[ 'search_post_icon' ] ) ) {
								echo '<input name="search_post_icon" type="hidden" value="' . esc_attr( $i['search_post_icon'] ) . '" />';
							}

							if ( ! empty( $i[ 'sk_search_post_icon' ] ) ) {
								echo '<input name="sk_search_post_icon" type="hidden" value="' . esc_attr( $i['sk_search_post_icon'] ) . '" />';
							}

							if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
								echo '<input name="lang" type="hidden" value="' . ICL_LANGUAGE_CODE . '" />';
							}

						?>

						<input class="ajax_search_input" name="s" type="text" placeholder="<?php echo esc_attr( $i['search_placeholder'] ); ?>" style="<?php echo esc_attr( $input_style ); ?>">
						<button type="submit"><i class="<?php echo wp_kses_post( $icon ); ?>" data-xtra-icon="<?php echo wp_kses_post( $icon ); ?>" style="<?php echo esc_attr( $icon_in_style ); ?>"></i></button>
					</form>
					<div class="ajax_search_results" style="<?php echo esc_attr( $ajax_style ); ?>"></div>
				</div><?php
				echo '</div></div>';

			} else if ( $elm === 'widgets' ) {

				$elm_uniqid = 'cz_ofc_' . rand( 11111, 99999 );
				$con_style = empty( $i['sk_offcanvas'] ) ? '' : self::sk_inline_style( $i['sk_offcanvas'] );
				$icon_style = empty( $i['sk_offcanvas_icon'] ) ? '' : 'i.' . $elm_uniqid . '{' . self::sk_inline_style( $i['sk_offcanvas_icon'] ) . '}';
				$icon_style .= empty( $i['sk_offcanvas_icon_hover'] ) ? '' : 'i.' . $elm_uniqid . ':hover{' . self::sk_inline_style( $i['sk_offcanvas_icon_hover'] ) . '}';
				$icon = empty( $i['offcanvas_icon'] ) ? 'fa fa-bars' : $i['offcanvas_icon'];

				$menu_title = isset( $i['menu_title'] ) ? $i['menu_title'] : '';
				$icon .= $menu_title ? ' icon_plus_text' : '';

				$icon_style .= empty( $i['sk_menu_title'] ) ? '' : '.' . $elm_uniqid . ' span{' . self::sk_inline_style( $i['sk_menu_title'] ) . '}';
				$icon_style .= empty( $i['sk_menu_title_hover'] ) ? '' : '.' . $elm_uniqid . ':hover span{' . self::sk_inline_style( $i['sk_menu_title_hover'] ) . '}';

				echo '<div class="offcanvas_container"><i class="' . esc_attr( $icon . ' ' . $elm_uniqid ) . '" data-cz-style="' . esc_attr( $icon_style ) . '"><span>' . esc_html( $menu_title ) . '</span></i><div class="offcanvas_area offcanvas_original ' . ( empty( $i['inview_position_widget'] ) ? 'inview_left' : esc_attr( $i['inview_position_widget'] ) ) . '" style="' . esc_attr( $con_style ) . '">';

				if ( is_active_sidebar( 'offcanvas_area' ) ) {

					ob_start();
					dynamic_sidebar( 'offcanvas_area' );
					$offcanvas = ob_get_clean();

					if ( self::$plugin && self::option( 'lazyload' ) ) {
						echo Codevz_Plus::lazyload( $offcanvas );
					} else {
						echo do_shortcode( $offcanvas );
					}

				}

				echo '</div></div>';

			} else if ( $elm === 'hf_elm' ) {

				$con_style = empty( $i['sk_hf_elm'] ) ? '' : self::sk_inline_style( $i['sk_hf_elm'] );

				$elm_uniqid = 'cz_hf_' . rand( 11111, 99999 );
				$icon_style = empty( $i['sk_hf_elm_icon'] ) ? '' : 'i.' . $elm_uniqid . '{' . self::sk_inline_style( $i['sk_hf_elm_icon'] ) . '}';
				$icon_style .= empty( $i['sk_hf_elm_icon_hover'] ) ? '' : 'i.' . $elm_uniqid . ':hover{' . self::sk_inline_style( $i['sk_hf_elm_icon_hover'] ) . '}';

				$icon = empty( $i['hf_elm_icon'] ) ? 'fa fa-bars' : $i['hf_elm_icon'];

				echo '<i class="hf_elm_icon ' . esc_attr( $icon . ' ' . $elm_uniqid ) . '" data-cz-style="' . esc_attr( $icon_style ) . '"></i><div class="hf_elm_area" style="' . esc_attr( $con_style ) . '"><div class="row clr">' . ( empty( $i['hf_elm_page'] ) ? '' : self::get_page_as_element( esc_html( $i['hf_elm_page'] ) ) ) . '</div></div>';

			} else if ( $elm === 'shop_cart' ) {

				$shop_plugin = ( empty( $i['shop_plugin'] ) || $i['shop_plugin'] === 'woo' ) ? 'woo' : 'edd';

				$container = empty( $i['sk_shop_container'] ) ? '' : self::sk_inline_style( $i['sk_shop_container'] );

				$icon_style = empty( $i['sk_shop_icon'] ) ? '' : self::sk_inline_style( $i['sk_shop_icon'] );
				$icon = empty( $i['shopcart_icon'] ) ? 'fa fa-shopping-basket' : $i['shopcart_icon'];

				$shop_style = empty( $i['sk_shop_count'] ) ? '' : '.' . $element_uid . ' .cz_cart_count, .' . $element_uid . ' .cart_1 .cz_cart_count{' . esc_attr( self::sk_inline_style( $i['sk_shop_count'] ) ) . '}';
				$shop_style .= empty( $i['sk_shop_content'] ) ? '' : '.' . $element_uid . ' .cz_cart_items{' . esc_attr( self::sk_inline_style( $i['sk_shop_content'] ) ) . '}';

				$cart_url = $cart_content = '';

				if ( $shop_plugin === 'woo' && function_exists( 'is_woocommerce' ) ) {
					$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#';
					$cart_content = '<div class="cz_cart">' . ( self::$preview ? '<span class="cz_cart_count">2</span><div class="cz_cart_items cz_cart_dummy"><div><div class="cart_list"><div class="item_small"><a href="#"></a><div class="cart_list_product_title cz_tooltip_up"><h3><a href="#">XXX</a></h3><div class="cart_list_product_quantity">1 x <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>32.00</span></div><a href="#" class="remove" data-product_id="1066" data-title="' . esc_html__( 'Remove', 'xtra' ) . '"><i class="fa fa-trash"></i></a></div></div><div class="item_small"><a href="#"></a><div class="cart_list_product_title"><h3><a href="#">XXX</a></h3><div class="cart_list_product_quantity">1 x <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>32.00</span></div><a href="#" class="remove" data-product_id="1066" data-title="' . esc_html__( 'Remove', 'xtra' ) . '"><i class="fa fa-trash"></i></a></div></div></div><div class="cz_cart_buttons clr"><a href="#">XXX, <span><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>64.00</span></span></a><a href="#">XXX</a></div></div></div>' : '' ) . '</div>';
				} else if ( function_exists( 'EDD' ) ) {
					$cart_url = function_exists( 'edd_get_checkout_uri' ) ? edd_get_checkout_uri() : '#';
					$cart_content = '<div class="cz_cart_edd"><span class="cz_cart_count edd-cart-quantity">' . wp_kses_post( edd_get_cart_quantity() ) . '</span><div class="cz_cart_items"><div><div class="cart_list">' . str_replace( "&nbsp;", '', do_shortcode( '[download_cart]' ) ) . '</div></div></div></div>';
				}

				$shopcart_title = empty( $i['shopcart_title'] ) ? '' : $i['shopcart_title'];

				echo '<div class="elms_shop_cart" data-cz-style="' . esc_attr( $shop_style ) . '">';
				echo '<a class="shop_icon noborder" href="' . esc_url( $cart_url ) . '" aria-label="' . esc_html( self::option( 'woo_cart', 'Cart' ) ) . '" style="' . esc_attr( $container ) . '"><i class="' . esc_attr( $icon ) . '" style="' . esc_attr( $icon_style ) . '"></i><span>' . do_shortcode( esc_html( $shopcart_title ) ) . '</span></a>';
				echo wp_kses_post( $cart_content );
				echo '</div>';

			} else if ( $elm === 'wishlist' ) {

				$container = empty( $i['sk_shop_container'] ) ? '' : self::sk_inline_style( $i['sk_shop_container'] );
				$icon_style = empty( $i['sk_shop_icon'] ) ? '' : self::sk_inline_style( $i['sk_shop_icon'] );
				$icon = empty( $i['shopcart_icon'] ) ? 'fa fa-heart-o' : $i['shopcart_icon'];

				$shopcart_title = empty( $i['shopcart_title'] ) ? '' : $i['shopcart_title'];

				$i['wishlist_page'] = self::option( 'woo_wishlist_page', 'Wishlist' );

				$page = get_page_by_title( $i['wishlist_page'], 'object', 'page' );
				if ( ! empty( $page->ID ) ) {
					$link = get_permalink( $page->ID );
				} else {
					$link = get_site_url() . '/wishlist';
				}
				
				$wishlist_title = $i['wishlist_page'];

				$shop_style = empty( $i['sk_shop_count'] ) ? '' : '.cz_wishlist_count{' . esc_attr( self::sk_inline_style( $i['sk_shop_count'] ) ) . '}';

				echo '<div class="elms_wishlist" data-cz-style="' . esc_attr( $shop_style ) . '">';
				echo '<a class="wishlist_icon" href="' . esc_url( $link ) . '" title="' . $wishlist_title . '" style="' . esc_attr( $container ) . '"><i class="' . esc_attr( $icon ) . '" style="' . esc_attr( $icon_style ) . '"></i><span>' . do_shortcode( esc_html( $shopcart_title ) ) . '</span></a>';
				echo '<span class="cz_wishlist_count"></span>';
				echo '</div>';

			} else if ( $elm === 'line' && isset( $i['line_type'] ) ) {

				$line = empty( $i['sk_line'] ) ? '' : self::sk_inline_style( $i['sk_line'] );
				echo '<div class="' . esc_attr( $i['line_type'] ) . '" style="' . esc_attr( $line ) . '">&nbsp;</div>';

			} else if ( $elm === 'button' ) {

				$elm_uniqid = 'cz_btn_' . rand( 11111, 99999 );
				$btn_css = empty( $i['sk_btn'] ) ? '' : self::sk_inline_style( $i['sk_btn'] );
				$btn_hover = empty( $i['sk_btn_hover'] ) ? '' : '.' . esc_attr( $elm_uniqid ) . ':hover{' . self::sk_inline_style( $i['sk_btn_hover'], true ) . '}';
				
				$btn_hover .= empty( $i['sk_hf_elm_icon'] ) ? '' : '.' . esc_attr( $elm_uniqid ) . ' i {' . self::sk_inline_style( $i['sk_hf_elm_icon'] ) . '}';
				$btn_hover .= empty( $i['sk_hf_elm_icon_hover'] ) ? '' : '.' . esc_attr( $elm_uniqid ) . ':hover i {' . self::sk_inline_style( $i['sk_hf_elm_icon_hover'] ) . '}';

				$icon_before = $icon_after = '';
				if ( ! empty( $i['hf_elm_icon'] ) ) {
					if ( empty( $i['btn_icon_pos'] ) ) {
						$icon_before = '<i class="' . $i['hf_elm_icon'] . ' cz_btn_header_icon_before"></i>';
					} else {
						$icon_after = '<i class="' . $i['hf_elm_icon'] . ' cz_btn_header_icon_after"></i>';
					}
				}

				$target = empty( $i['btn_link_target'] ) ? '' : ' target="_blank"';
				echo '<a class="cz_header_button ' . esc_attr( $elm_uniqid ) . '" href="' . ( empty( $i['btn_link'] ) ? '' : do_shortcode( esc_url( $i['btn_link'] ) ) ) . '" style="' . esc_attr( $btn_css ) . '" data-cz-style="' . esc_attr( $btn_hover ) . '"' . $target . '>' . wp_kses_post( $icon_before ) . '<span>' . esc_html( empty( $i['btn_title'] ) ? 'Button' : do_shortcode( $i['btn_title'] ) ) . '</span>' . wp_kses_post( $icon_after ) . '</a>';

			// Custom shortcode or HTML codes
			} else if ( $elm === 'custom' && isset( $i['custom'] ) ) {

				echo do_shortcode( wp_kses_post( $i['custom'] ) );

			// WPML Switcher
			} else if ( $elm === 'wpml' && function_exists( 'icl_get_languages' ) ) {

				$wpml = icl_get_languages( 'skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str' );

				$wpml_opposite = empty( $i['wpml_opposite'] ) ? '' : ' data-cz-style=".cz_language_switcher a { display: none } .cz_language_switcher div { display: block; position: static; transform: none; } .cz_language_switcher div a { display: block; }"';

				if ( is_array( $wpml ) ) {
					$bg = empty( $i['wpml_background'] ) ? '' : 'background: ' . esc_attr( $i['wpml_background'] ) . '';
					echo '<div class="cz_language_switcher"' . $wpml_opposite . '><div style="' . esc_attr( $bg ) . '">';
					foreach( $wpml as $lang => $vals ) {
						if ( ! empty( $vals ) ) {

							$class = $vals['active'] ? 'cz_current_language' : '';
							if ( empty( $i['wpml_title'] ) ) {
								$title = $vals['translated_name'];
							} else if ( $i['wpml_title'] !== 'no_title' ) {
								$title = ucwords( $vals[ $i['wpml_title'] ] );
							} else {
								$title = '';
							}

							$color = '';
							if ( $class && ! empty( $i['wpml_color'] ) ) {
								$color = 'color: ' . esc_attr( $i['wpml_current_color'] );
							} else if ( ! $class && ! empty( $i['wpml_color'] ) ) {
								$color = 'color: ' . esc_attr( $i['wpml_color'] );
							}

							if ( !empty( $i['wpml_flag'] ) ) {
								echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $vals['url'] ) . '" style="' . esc_attr( $color ) . '"><img src="' . esc_url( $vals['country_flag_url'] ) . '" alt="#" width="200" height="200" class="' . esc_attr( $title ? 'mr8' : '' ) . '" />' . esc_html( $title ) . '</a>';
							} else {
								echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $vals['url'] ) . '" style="' . esc_attr( $color ) . '">' . esc_html( $title ) . '</a>';
							}

						}
					}
					echo '</div></div>';
				}

			// Custom page as element
			} else if ( $elm === 'custom_element' && ! empty( $i['header_elements'] ) && $i['header_elements'] !== 'none' ) {

				echo self::get_page_as_element( esc_html( $i['header_elements'] ) );
				//echo preg_replace( '#\[[^\]]+\]#', '', self::get_page_as_element( esc_html( $i['header_elements'] ) ) );

			// Current user avatar
			} else if ( $elm === 'avatar' ) {

				$sk_avatar = empty( $i['sk_avatar'] ) ? '' : $i['sk_avatar'];
				$link = empty( $i['avatar_link'] ) ? '' : $i['avatar_link'];
				$size = empty( $i['avatar_size'] ) ? '' : $i['avatar_size'];

				echo '<a class="cz_user_gravatar" href="' . esc_url( $link ) . '" style="' . esc_attr( $sk_avatar ) . '">';
				if ( $is_user_logged_in ) {
					global $current_user;
					echo wp_kses_post( get_avatar( esc_html( $current_user->user_email ), esc_attr( $size ) ) );
				} else {
					echo wp_kses_post( get_avatar( 'xxx@xxx.xxx', esc_attr( $size ) ) );
				}
				echo '</a>';
			}

			// Close element
			echo '</div>';
		}

		/**
		 * Generate inner row elements positions
		 * 
		 * @return string
		 */
		public static function row_inner( $id = 0, $pos = 0, $out = '' ) {
			if ( isset( $_POST['id'] ) && isset( $_POST['pos'] ) ) {
				$ajax = 1;
				$id = $_POST['id'];
				$pos = $_POST['pos'];
			}

			$elms = self::option( $id . $pos );
			if ( $elms ) {

				$shape = self::get_string_between( self::option( '_css_' . $id . $pos ), '_class_shape:', ';' );

				if ( $shape ) {
					$shape = ' ' . $shape;
				}

				$center = self::contains( $pos, 'center' );

				echo '<div class="elms' . esc_attr( $pos . ' ' . $id . $pos . ( $shape ? ' ' . $shape : '' ) ) . '">';
				if ( $center ) {
					echo '<div>';
				}
				$inner_id = 0;
				foreach( (array) $elms as $v ) {
					if ( empty( $v['element'] ) ) {
						continue;
					}
					$more = [];
					$more['id'] = $id;
					$more['depth'] = $pos . '_' . self::$element++;
					$more['inner_depth'] = $pos . '_' . $inner_id++;

					self::get_row_element( $v, $more );
				}
				if ( $center ) {
					echo '</div>';
				}
				echo '</div>';
			}

			if ( isset( $ajax ) ) {
				wp_die();
			}
		}

		/**
		 * Generate header|footer|side row elements
		 * 
		 * @return string
		 */
		public static function row( $args ) {

			ob_start();
			foreach( $args['nums'] as $num ) {
				$id = esc_attr( $args['id'] );

				// Check if sticky header is not custom
				if ( $num === '5' && ! self::option( 'sticky_header' ) ) {
					continue;
				}

				// Columns
				$left = self::option( $id . $num . $args['left'] );
				$right = self::option( $id . $num . $args['right'] );
				$center = self::option( $id . $num . $args['center'] );

				// Row Shape
				$shape = self::get_string_between( self::option( '_css_row_' . $id . $num ), '_class_shape:', ';' );
				$shape = $shape ? ' ' . $shape : '';

				// Menu FX
				$menufx = self::get_string_between( self::option( '_css_menu_a_hover_before_' . $id . $num ), '_class_menu_fx:', ';' );
				$menufx = $menufx ? ' ' . $menufx : '';

				// Menu FX
				$submenufx = self::get_string_between( self::option( '_css_menu_ul_' . $id . $num ), '_class_submenu_fx:', ';' );
				$submenufx = $submenufx ? ' ' . $submenufx : '';

				// Check sticky header
				$sn = self::option( 'sticky_header' );
				$sticky = ( self::contains( $sn, $num ) && $id !== 'footer_' ) ? ' header_is_sticky' : '';
				$sticky .= ( self::option( 'smart_sticky' ) && ( $sn === '1' || $sn === '2' || $sn === '3' || $sn === '5' ) ) ? ' smart_sticky' : '';
				$sticky .= ( self::option( 'mobile_sticky' ) && $id . $num === 'header_4' ) ? ' ' . self::option( 'mobile_sticky' ) : '';

				// Before mobile header
				$bmh = self::option( 'b_mobile_header' );
				if ( $num === '4' && $bmh && $bmh !== 'none' ) {
					echo '<div class="row clr cz_before_mobile_header">' . self::get_page_as_element( self::option( 'b_mobile_header' ) ) . '</div>';
				}

				// Start
				if ( $left || $center || $right ) {

					echo '<div class="' . esc_attr( $id . $num . ( $center ? ' have_center' : '' ) . $shape . $sticky . $menufx . $submenufx ) . '">';
					if ( $args['row'] ) {
						echo '<div class="row elms_row"><div class="clr">';
					}

					self::row_inner( $id . $num, $args['left'] );
					self::row_inner( $id . $num, $args['center'] );
					self::row_inner( $id . $num, $args['right'] );

					if ( $args['row'] ) {
						echo '</div></div>';
					}
					echo '</div>';

				}

				// After mobile header
				$amh = self::option( 'a_mobile_header' );
				if ( $num === '4' && $amh && $amh !== 'none' ) {
					echo '<div class="row clr cz_after_mobile_header">' . self::get_page_as_element( self::option( 'a_mobile_header' ) ) . '</div>';
				}
			}
			echo ob_get_clean();
		}

		/**
		 * Generate page
		 * 
		 * @return string
		 */
		public static function generate_page( $page = '' ) {
			get_header();

			global $wp_query;

			// Settings
			$cpt = self::get_post_type( '', true );
			$is_search = is_search();
			if ( $is_search ) {
				$option_cpt = '_search';
			} else if ( is_home() || is_category() || is_tag() || $cpt === 'post' ) {
				$option_cpt = '_post';
			} else {
				$option_cpt = ( $cpt === 'post' || $cpt === 'page' || empty( $cpt ) ) ? '' : '_' . $cpt;
			}
			$title = self::option( 'page_title' . $option_cpt );
			$title = ( ! $title || $title === '1' ) ? self::option( 'page_title' ) : $title;
			$page_title_tag = self::option( 'page_title_tag', 'h1' );
			$layout = self::option( 'layout' . $option_cpt );

			if ( ! $cpt || $cpt === 'post' || $cpt === 'page' ) {
				$primary = 'primary';
				$secondary = 'secondary';
			} else {
				$cpt_slug = get_post_type_object( $cpt );
				$cpt_slug = isset( $cpt_slug->name ) ? $cpt_slug->name : $cpt;
				$primary = $cpt_slug . '-primary';
				$secondary = $cpt_slug . '-secondary';
			}

			$layout = ( ! $layout || $layout === '1' ) ? self::option( 'layout' ) : $layout;

			// Woo general single layout
			$woo_single_layout = self::option( 'layout_single_product' );
			if ( $page === 'woocommerce' && $woo_single_layout && $woo_single_layout !== '1' && is_single() ) {
				$layout = $woo_single_layout;
			}

			$blank = ( $layout === 'bpnp' || $layout === 'ws' ) ? 1 : 0;
			$is_404 = ( is_404() || $page === '404' );
			$current_id = $is_404 ? '404' : ( isset( self::$post->ID ) ? self::$post->ID : 0 );
			
			if ( is_singular() || $cpt === 'page' || $is_404 ) {

				// Single post layout.
				$single_layout = self::option( 'layout_single_post' );
				if ( $cpt === 'post' && $single_layout && $single_layout != '1' ) {
					$layout = self::option( 'layout_single_post' );
				}

				// Default meta
				$single_meta_cpt = ( $cpt === 'page' || empty( $cpt ) ) ? 'post' : $cpt;
				$single_meta = (array) self::option( 'meta_data_' . $single_meta_cpt );
				$single_meta = array_flip( $single_meta );

				// Post meta
				$meta = self::meta( $current_id );

				// Set
				if ( ! empty( $meta['layout'] ) && $meta['layout'] != '1' ) {
					$layout = $meta['layout'];
					$blank = ( $meta['layout'] === 'none' || $meta['layout'] === 'bpnp' ) ? 1 : 0;
					
					if ( ! empty( $meta['primary'] ) ) {
						$primary = $meta['primary'];
					}
					if ( ! empty( $meta['secondary'] ) ) {
						$secondary = $meta['secondary'];
					}
				}

				$featured_image = 1;
				if ( ! empty( $meta['hide_featured_image'] ) ) {
					if ( $meta['hide_featured_image'] === '1' ) {
						$featured_image = 0;
					} else {
						$featured_image = 2;
					}
				} else if ( ! isset( $single_meta['image'] ) ) {
					$featured_image = 0;
				}
			}

			// Start page content
			$bpnp = ( $layout === 'bpnp' ) ? ' cz_bpnp' : '';
			$bpnp .= empty( $meta['page_content_margin'] ) ? '' : ' ' . $meta['page_content_margin'];
			echo '<div id="page_content" class="page_content' . esc_attr( $bpnp ) . '"><div class="row clr">';

			// Before content
			if ( $is_404 || ! is_active_sidebar( $primary ) ) {
				echo '<section class="s12 clr">';
			} else if ( $layout === 'both-side' ) {
				echo '<aside class="col s3 sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );  
				}
				echo '</div></aside><section class="col s6">';
			} else if ( $layout === 'both-side2' ) {
				echo '<aside class="col s2 sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );
				}
				echo '</div></aside><section class="col s8">';
			} else if ( $layout === 'both-right' ) {
				echo '<section class="col s6">';
			} else if ( $layout === 'both-right2' ) {
				echo '<section class="col s7">';
			} else if ( $layout === 'right' ) {
				echo '<section class="col s8">';
			} else if ( $layout === 'right-s' ) {
				echo '<section class="col s9">';
			} else if ( $layout === 'center' ) {
				echo '<aside class="col s2">&nbsp</aside>';
				echo '<section class="col s8">';
			} else if ( $layout === 'both-left' ) {
				echo '<section class="col s6 col_not_first righter">';
			} else if ( $layout === 'both-left2' ) {
				echo '<section class="col s7 col_not_first righter">';
			} else if ( $layout === 'left' ) {
				echo '<section class="col s8 col_not_first righter">';
			} else if ( $layout === 'left-s' ) {
				echo '<section class="col s9 col_not_first righter">';
			} else {
				echo '<section class="s12 clr">';
			}

			$single_classes = '';

			if ( is_single() ) {
				$single_classes = ' ' . implode( ' ', get_post_class() );
				$single_classes .= self::contains( $single_classes, ' product ' ) ? '' : ' single_con';
			}

			echo '<div class="' . esc_attr( ( $blank ? 'cz_is_blank' : 'content' ) . $single_classes ) . ' clr">';

			// Action fire before content.
			do_action( 'xtra_before_archive_content' );

			if ( $is_404 ) {

				$page_404 = get_page_by_path( 'page-404' );

				if ( $page_404 ) {

					echo do_shortcode( self::get_page_as_element( $page_404->ID ) );

				} else {

					echo '<h2 class="xtra-404"><span>' . do_shortcode( self::option( '404_title', '404' ) ) . '</span><small>' . do_shortcode( self::option( '404_msg', 'How did you get here?! It’s cool. We’ll help you out.' ) ) . '</small></h2>';

					echo self::option( 'disable_search_404' ) ? '' : '<form class="search_404" method="get" action="' . self::$home_url . '">
						<input id="inputhead" name="s" type="text" value="">
						<button type="submit"><i class="fa fa-search"></i></button>
					</form>';

					echo '<a class="button" href="' . self::$home_url . '" style="margin: 80px auto 0;display:table">' . do_shortcode( self::option( '404_btn', 'Back to Homepage' ) ) . '</a>';

				}

			} else if ( $page === 'page' || $page === 'single' ) {

				if ( have_posts() ) {
					the_post();

					$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );

					if ( $page === 'single' && self::$preview ) {

						if ( $cpt === 'post' ) {
							echo '<i class="xtra-section-focus fas fa-cog" data-section="single_settings"></i>';
							echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="single_styles"></i>';
						} else {
							echo '<i class="xtra-section-focus fas fa-cog" data-section="' . esc_attr( $cpt ) . '_single_settings"></i>';
							echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="' . esc_attr( $cpt ) . '_single_styles"></i>';
						}

					}

					// Post title and date.
					if ( $cpt !== 'elementor_library' && $page !== 'page' && ! $blank && ( $title === '1' || $title === '2' || $title === '8' ) ) {

						echo '<' . esc_attr( $page_title_tag ) . ' class="xtra-post-title section_title">' . get_the_title() . '</' . esc_attr( $page_title_tag ) . '>';

						echo '<span class="xtra-post-title-date"><a href="' . self::$home_url . get_post_time( 'Y/m/d' ) . '"><time datetime="' . esc_attr( get_the_date( 'c' ) ) . '" itemprop="datePublished"><i class="far fa-clock mr8"></i>' . esc_html( get_the_date() ) . '</time></a></span>';

					}

					// Single post
					if ( $page === 'single' ) {

						// Featured image
						$featured_image_out = '';
						if ( $featured_image && has_post_thumbnail() ) {
							ob_start();
							echo '<div class="cz_single_fi">';
							the_post_thumbnail( 'full' );
							$cap = get_the_post_thumbnail_caption();
							if ( $cap ) {
								echo '<p class="wp-caption-text">' . $cap . '</p>';
							}
							echo'</div><br />';
							$featured_image_out = ob_get_clean();
						}

						// Post format
						if ( ! empty( $meta['post_format'] ) ) {

							$get_post_format = get_post_format();

							if ( $meta['post_format'] === 'gallery' && ! empty( $meta['gallery_layout'] ) ) {

								$post_format_out = '[cz_gallery images="' . $meta['gallery'] . '" layout="' . $meta['gallery_layout'] . '" gap="' . $meta['gallery_gap'] . '" slidestoshow="' . $meta['gallery_slides_to_show'] . '"]';
								$featured_image_out = null;

							} else if ( $meta['post_format'] === 'video' ) {

								$video_type = isset( $meta['video_type'] ) ? $meta['video_type'] : '';
								$featured_image_out = null;

								if ( $video_type === 'url' ) {

									$video_url = empty( $meta['video_url'] ) ? 'https://www.youtube.com/watch?v=FyS_zcvmUr4' : $meta['video_url'];

									if ( self::contains( $video_url, 'vimeo' ) || is_numeric( $video_url ) ) {

										if ( ! self::contains( $video_url, '/video/' ) ) {
											preg_match( '/[0-9]{6,11}/', $video_url, $match );
											$video_url = empty( $match[0] ) ? '' : 'https://player.vimeo.com/video/' . $match[0];
										}

									} else if ( ! self::contains( $video_url, '/embed/' ) ) {

										preg_match( '/^(embed\/|.*?^v=)|[\w+]{11,20}/', $video_url, $match );
										$video_url = empty( $match[0] ) ? '' : 'https://www.youtube.com/embed/' . $match[0];

									}

									$post_format_out = '<' . 'if' . 'rame src="' . $video_url . '" width="800" height="500"></if' . 'rame' . '>';

								} else if ( $video_type === 'selfhost' ) {

									$video_file = isset( $meta['video_file'] ) ? $meta['video_file'] : '';
									$post_format_out = do_shortcode( '[video width="800" height="500" mp4="' . $video_file . '"]' );

								} else if ( $video_type === 'embed' ) {

									$video_embed = isset( $meta['video_embed'] ) ? $meta['video_embed'] : '';
									$post_format_out = do_shortcode( $video_embed );

								}

							} else if ( $meta['post_format'] === 'audio' ) {

								$audio_file = isset( $meta['audio_file'] ) ? $meta['audio_file'] : '';
								$post_format_out = do_shortcode( '[audio mp3="' . $audio_file . '"]' );

							} else if ( $meta['post_format'] === 'quote' ) {

								$quote = isset( $meta['quote'] ) ? $meta['quote'] : '';
								$cite = isset( $meta['cite'] ) ? $meta['cite'] : '';
								$post_format_out = '<blockquote>' . $quote . '<cite>' . $cite . '</cite></blockquote>';
								$featured_image_out = null;

							}

							// Echo post format
							if ( $post_format_out ) {
								$post_format_out = '<div class="cz_single_post_format mb30">' . $post_format_out . '</div>';
							}

						}

						// Image and format
						if ( isset( $post_format_out ) ) {
							$fpf = do_shortcode( $featured_image_out . $post_format_out );

							if ( self::$plugin && self::option( 'lazyload' ) ) {
								echo Codevz_Plus::lazyload( do_shortcode( $fpf ) );
							} else {
								echo do_shortcode( $fpf );
							}
						} else {
							echo do_shortcode( $featured_image_out );
						}
					}

					// Content
					echo '<div class="cz_post_content clr">';
					the_content();
					echo '</div>';

					// Pagination
					wp_link_pages( [
						'before'=>'<div class="pagination mt20 clr">', 
						'after'=>'</div>', 
						'link_after'=>'</b>', 
						'link_before'=>'<b>'
					] );

					// Single post type meta
					if ( $page === 'single' && empty( $wp_query->queried_object->taxonomy ) ) {

						echo '<div class="clr mt40 relative">';

						if ( self::$preview ) {
							if ( $cpt === 'post' ) {
								echo '<i class="xtra-section-focus fas fa-cog" data-section="single_settings"></i>';
								echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="single_styles"></i>';
							} else {
								echo '<i class="xtra-section-focus fas fa-cog" data-section="' . esc_attr( $cpt ) . '_single_settings"></i>';
								echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="' . esc_attr( $cpt ) . '_single_styles"></i>';
							}
						}

						if ( isset( $single_meta['author'] ) ) {
							echo '<p class="cz_post_author cz_post_cat mr10">';
							echo '<a href="#"><i class="fas fa-user"></i></a>';
							echo '<a href="' . esc_url( $author_url ) . '">' . ucwords( get_the_author() ) . '</a>';
							echo '</p>';
							
						}

						if ( isset( $single_meta['date'] ) ) {
							echo '<p class="cz_post_date cz_post_cat mr10">';
							echo '<a href="#"><i class="fas fa-clock"></i></a>';
							echo '<a href="' . self::$home_url . get_post_time( 'Y/m/d' ) . '"><span class="cz_post_date"><time datetime="' . get_the_date( 'c' ) . '" itemprop="datePublished">' . esc_html( get_the_date() ) . '</time></span></a>';
							echo '</p>';
							
						}

						if ( isset( $single_meta['cats'] ) ) {
							
							echo '<p class="cz_post_cat mr10">';

							$cats = [];
							$tax = ( $cpt === 'post' ) ? 'category' : $cpt . '_cat';

							$terms = (array) get_the_terms( get_the_id(), $tax );
							foreach( $terms as $term ) {
								if ( isset( $term->term_id ) ) {
									$cats[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
								}
							}

							$cats = implode( '', $cats );
							$pre = '<a href="#"><i class="fa fa-folder-open"></i></a>';

							echo wp_kses_post( $cats ? $pre . $cats : '' );

							echo '</p>';
							
						}

						if ( isset( $single_meta['tags'] ) ) {
							$tags = '';
							$tax = get_object_taxonomies( $cpt, 'objects' );

							foreach( $tax as $tax_slug => $taks ) {
								$terms = get_the_terms( get_the_id(), $tax_slug );

							    if ( ! empty( $terms ) && self::contains( $taks->name, 'tag' ) ) {
							        $tags .= '<p class="tagcloud"><a href="#"><i class="fa fa-tags"></i></a>';
							        foreach( $terms as $term ) {
							            $tags .= '<a href="' . esc_url( get_term_link( $term->slug, $tax_slug ) ) . '">' . esc_html( $term->name ) . '</a>';
							        }
							        $tags .= "</p>";
							    }
							}

							echo wp_kses_post( $tags );
						}
						
						echo '</div>';
						
						do_action( 'xtra_share' ); // Share icons.

						if ( isset( $single_meta['next_prev'] ) && self::next_prev_item() ) {
							
							echo '</div><div class="content cz_next_prev_posts clr">' . self::next_prev_item();

							if ( self::$preview ) {
								if ( $cpt === 'post' ) {
									echo '<i class="xtra-section-focus fas fa-cog" data-section="single_settings"></i>';
									echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="single_styles"></i>';
								} else {
									echo '<i class="xtra-section-focus fas fa-cog" data-section="' . esc_attr( $cpt ) . '_single_settings"></i>';
									echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="' . esc_attr( $cpt ) . '_single_styles"></i>';
								}
							}

						}

						if ( $cpt === 'post' && self::author_box() ) {
							
							echo '</div><div class="content cz_author_box clr">';
							echo '<h4>' . esc_html( ucfirst( get_the_author_meta('display_name') ) ) . '<small class="righter cz_view_author_posts"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' .  esc_html__( 'Author posts', 'xtra' ) . ' <i class="fa fa-angle-double-' . ( self::$is_rtl ? 'left' : 'right' ) . ' ml4"></i></a></small></h4>';
							echo self::author_box();

							if ( self::$preview ) {
								if ( $cpt === 'post' ) {
									echo '<i class="xtra-section-focus fas fa-cog" data-section="single_settings"></i>';
									echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="single_styles"></i>';
								} else {
									echo '<i class="xtra-section-focus fas fa-cog" data-section="' . esc_attr( $cpt ) . '_single_settings"></i>';
									echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="' . esc_attr( $cpt ) . '_single_styles"></i>';
								}
							}

						}

						$related_ppp = self::option( 'related_' . $single_meta_cpt . '_ppp' );
						if ( $related_ppp && $cpt !== 'page' && $cpt !== 'product' && $cpt !== 'download' ) {
							echo self::related( [
								'posts_per_page' 	=> esc_attr( $related_ppp ),
								'related_columns' 	=> esc_attr( self::option( 'related_' . $single_meta_cpt . '_col', 's4' ) ),
								'section_title' 	=> esc_html( do_shortcode( self::option( 'related_posts_' . $single_meta_cpt, 'Related Posts ...' ) ) )
							] );
						}
					} else {

						do_action( 'xtra_share' ); // Share icons.
						
					}
				}

			// Woocommerce shop
			} else if ( $page === 'woocommerce' ) {

				if ( self::$preview ) {
					if ( is_single() ) {
						echo '<i class="xtra-section-focus fas fa-cog" data-section="product"></i>';
						echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="product_sk"></i>';
					} else {
						echo '<i class="xtra-section-focus fas fa-cog" data-section="products"></i>';
						echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="products_sk"></i>';
					}
				}

				woocommerce_content();

			// Easy digital download
			} else if ( $cpt === 'download' ) {
				
				if ( have_posts() ) {
					echo '<div class="cz_edd_container"><div class="clr mb30">';

					$edd_col = self::option( 'edd_col', '3' );
					if ( $edd_col === '2' ) {
						$edd_col_class = 's6';
					} else if ( $edd_col === '4' ) {
						$edd_col_class = 's3';
					} else if ( $edd_col === '3' ) {
						$edd_col_class = 's4';
					}

					$i = 1;
					while ( have_posts() ) {
						the_post();
						$id = get_the_ID();
						$link = get_the_permalink();
						$title = get_the_title();
						$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );

						echo '<div class="' . esc_attr( implode( ' ', get_post_class( 'cz_edd_item col ' . $edd_col_class ) ) ) . '"><article>';
						if ( has_post_thumbnail() ) {
							echo '<a class="cz_edd_image" href="' . esc_url( $link ) . '">';
							the_post_thumbnail( 'codevz_600_600' );
							echo edd_price( $id );
							echo '</a>';
						}
						echo '<a class="cz_edd_title" href="' . esc_url( $link ) . '"><h3>' . $title . '</h3></a>';
						echo do_shortcode( '[purchase_link id="' . $id . '"]' );
						echo '</article></div>';

						// Clearfix
						if ( $i % $edd_col === 0 ) {
							echo '</div><div class="clr mb30">';
						}

						$i++;
					}

					echo '</div></div>'; // row

					// Pagination
					echo '<div class="clr tac">';
					the_posts_pagination( [
						'prev_text'          => self::$is_rtl ? '<i class="fa fa-angle-double-right mr4"></i>' : '<i class="fa fa-angle-double-left mr4"></i>',
						'next_text'          => self::$is_rtl ? '<i class="fa fa-angle-double-left ml4"></i>' : '<i class="fa fa-angle-double-right ml4"></i>',
						'before_page_number' => ''
					] );
					echo '</div>';
				}

			// Archive posts
			} else if ( have_posts() ) {

				// Archive title
				if ( ! is_home() && ! is_post_type_archive() && ( $title === '2' || $title === '8' ) ) {

					self::page_title( $page_title_tag );

				}

				$description = '';

				if ( is_category() && category_description() ) {

					$description = category_description();

				} else if ( is_tag() && tag_description() ) {

					$description = tag_description();

				} else if ( is_tax() && term_description( get_query_var('term_id'), get_query_var( 'taxonomy' ) ) ) {

					$description = term_description( get_query_var('term_id'), get_query_var( 'taxonomy' ) );

				}

				if ( $description ) {

					echo '<div class="xtra-archive-desc mb50">' . wp_kses_post( $description ) . '</div>';

				}

				// Author box
				if ( is_author() && self::author_box() ) {
					echo '<h3>' . esc_html( ucfirst( get_the_author_meta('display_name') ) ) . '<small class="righter cz_view_author_posts"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html__( 'View all posts', 'xtra' ) . ' <i class="fa fa-angle-double-right ml4"></i></a></small></h3>';
					echo self::author_box();
					echo '</div><div class="content clr">';
				}

				// Archive title
				$archive_desc = self::option( 'desc_' . $cpt );
				if ( $archive_desc ) {
					echo '<p>' . do_shortcode( wp_kses_post( $archive_desc ) ) . '</p>';
				}

				// Template
				$template = self::option( 'template_style' );

				if ( $cpt && $cpt !== 'post' && $cpt !== 'page' ) {
					$template = self::option( 'template_style_' . $cpt, $template );
					$x_height = self::option( '2x_height_image_' . $cpt );
					$excerpt = self::option( 'post_excerpt_' . $cpt, 20 );
				} else {
					$cpt = 'post';
					$x_height = self::option( '2x_height_image' );
					$excerpt = self::option( 'post_excerpt', 20 );
				}

				$custom_template = self::option( 'template_' . $cpt );

				if ( $template === 'x' && $custom_template && $custom_template !== 'none' ) {

					echo self::get_page_as_element( esc_html( $custom_template ), 1 );

				} else {

					$gallery_mode = '';
					if ( $template === '9' || $template === '10' || $template === '11' ) {
						$gallery_mode = ' cz_posts_gallery_mode';
					}

					$post_class = '';
					$svg = self::option( 'default_svg_post' ) ? 'cz_post_svg' : '';

					// Sizes
					$image_size = 'codevz_360_320';
					$svg_w = '360';
					$svg_h = '320';
					if ( $template == '2' ) {
						$post_class .= ' cz_default_loop_right';
					} else if ( $template == '3' ) {
						$post_class .= ' cz_default_loop_full';
						$image_size = 'codevz_1200_500';
						$svg_w = '1200';
						$svg_h = '500';
					} else if ( $template == '4' || $template == '9' ) {
						$post_class .= ' cz_default_loop_grid col s6';
					} else if ( $template == '5' || $template == '10' ) {
						$post_class .= ' cz_default_loop_grid col s4';
					} else if ( $template == '7' || $template == '11' ) {
						$post_class .= ' cz_default_loop_grid col s3';
					} else if ( $template == '8' ) {
						$post_class .= ' cz_default_loop_full';
						$image_size = 'codevz_1200_200';
						$svg_w = '1200';
						$svg_h = '200';
					}

					// Square size
					if ( $template === '4' || $template === '12' ) {
						$image_size = 'codevz_600_600';
						$svg_w = $svg_h = '600';
					}

					// Square size
					if ( $template === '9' || $template === '10' || $template === '11' ) {
						$post_class .= ' cz_default_loop_square';
						$image_size = 'codevz_600_600';
						$svg_w = $svg_h = '600';
					}

					// Vertical size
					if ( $x_height && $template !== '3' ) {
						$image_size = 'codevz_600_1000';
						$svg_w = '600';
						$svg_h = '1000';

						if ( $template === '8' ) {
							$image_size = 'codevz_1200_500';
							$svg_w = '1200';
							$svg_h = '500';
						}
					}

					// Clearfix
					$clr = 999;
					if ( $template === '4' || $template === '9' ) {
						$clr = 2;
					} else if ( $template === '5' || $template === '10' ) {
						$clr = 3;
					} else if ( $template === '7' || $template === '11' ) {
						$clr = 4;
					}

					// Post hover icon
					if ( self::contains( self::option( 'hover_icon_' . $cpt ), [ 'image', 'imhoh', 'iasi' ] ) ) {
						$post_hover_icon = '<i class="cz_post_icon"><img src="' . self::option( 'hover_icon_image_' . $cpt ) . '" /></i>';
					} else if ( self::option( 'hover_icon_' . $cpt ) === 'none' ) {
						$post_hover_icon = '';
					} else {
						$post_hover_icon = '<i class="cz_post_icon ' . self::option( 'hover_icon_icon_' . $cpt, 'fa czico-109-link-symbol-1' ) . '"></i>';
					}
					if ( self::option( 'hover_icon_' . $cpt ) === 'ihoh' || self::option( 'hover_icon_' . $cpt ) === 'imhoh' ) {
						$gallery_mode .= ' cz_post_hover_icon_hoh';
					} else if ( self::option( 'hover_icon_' . $cpt ) === 'asi' || self::option( 'hover_icon_' . $cpt ) === 'iasi' ) {
						$gallery_mode .= ' cz_post_hover_icon_asi';
					}

					echo '<div class="cz_posts_container cz_posts_template_' . $template . $gallery_mode . '">';

					if ( self::$preview ) {
						if ( $cpt === 'post' ) {
							echo '<i class="xtra-section-focus fas fa-cog" data-section="blog_settings"></i>';
							echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="blog_styles"></i>';
						} else {
							echo '<i class="xtra-section-focus fas fa-cog" data-section="' . esc_attr( $cpt ) . '_settings"></i>';
							echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="' . esc_attr( $cpt ) . '_styles"></i>';
						}
					}

					echo '<div class="clr mb30">';

					// Chess style
					$chess = 0;
					if ( self::contains( $template, [ '12', '13', '14' ] ) ) {
						$chess = 1;
					}

					$i = 1;
					while ( have_posts() ) {
						the_post();
						$link = get_the_permalink();
						$title = get_the_title();
						$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
						$even_odd = '';
						if ( $template === '6' ) {
							$even_odd = ( $i % 2 == 0 ) ? ' cz_post_even cz_default_loop_right' : ' cz_post_odd';
						}

						echo '<article class="' . esc_attr( implode( ' ', get_post_class( 'cz_default_loop clr' . $post_class . $even_odd ) ) ) . '"><div class="clr">';

						// Thumbnail.
						if ( has_post_thumbnail() ) {
							echo '<a class="cz_post_image" href="' . esc_url( $link ) . '">';
							the_post_thumbnail( $image_size );
							echo wp_kses_post( $post_hover_icon ) . '</a>';
						} else if ( $svg ) {
							echo '<a class="cz_post_image ' . $svg . '" href="' . esc_url( $link ) . '">';
							echo '<img src="data:image/svg+xml,%3Csvg%20xmlns%3D&#39;http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg&#39;%20width=&#39;' . $svg_w . '&#39;%20height=&#39;' . $svg_h . '&#39;%20viewBox%3D&#39;0%200%20' . $svg_w . '%20' . $svg_h . '&#39;%2F%3E" alt="Placeholder" />';
							echo wp_kses_post( $post_hover_icon ) . '</a>';
						}
						
						if ( $chess ) {
							echo '<div class="cz_post_chess_content cz_post_con">';
							echo '<a class="cz_post_title" href="' . esc_url( $link ) . '"><h3>' . $title . '</h3><small><span class="cz_post_date"><time datetime="' . get_the_date( 'c' ) . '" itemprop="datePublished">' . esc_html( get_the_date() ) . '</time></span></small></a>';
							echo self::excerpt_more( 1 );
							echo '</div>';
						} else {
							echo '<div class="cz_post_con">';
							echo '<a class="cz_post_title" href="' . esc_url( $link ) . '"><h3>' . $title . '</h3></a>';
							$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
							echo '<span class="cz_post_meta mt10 mb10">';
							echo '<a class="cz_post_author_avatar" href="' . esc_url( $author_url ) . '">' . get_avatar( get_the_author_meta( 'ID' ), 40 ) . '</a>';
							echo '<span class="cz_post_inner_meta">';
							echo '<a class="cz_post_author_name" href="' . esc_url( $author_url ) . '">' . esc_html( ucwords( get_the_author() ) ) . '</a>';
							echo '<span class="cz_post_date"><time datetime="' . get_the_date( 'c' ) . '" itemprop="datePublished">' . esc_html( get_the_date() ) . '</time></span>';
							echo '</span></span>';

							if ( empty( $template ) || self::contains( $template, [ '1', '2', '3', '4', '5', '6', '7' ] ) ) {

								if ( $excerpt !== '-1' ) {
									$ex = get_the_excerpt();
								} else {
									ob_start();
									the_content();
									$ex = ob_get_clean();
								}

								echo '<div class="cz_post_excerpt">' . $ex . '</div>';

							}

							echo '</div>';
						}

						if ( ! $title ) {
							echo self::the_content_more_link();
						}

						echo '</div>';
						echo '</article>';

						// Clearfix
						if ( $i % $clr === 0 ) {
							echo '</div><div class="clr mb30">';
						}

						$i++;
					}

					echo '</div></div>'; // row

					// Pagination
					echo '<div class="clr tac relative">';

					if ( self::$preview ) {
						echo '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="blog_styles"></i>';
					}

					the_posts_pagination( [
						'prev_text'          => self::$is_rtl ? '<i class="fa fa-angle-double-right mr4"></i>' : '<i class="fa fa-angle-double-left mr4"></i>',
						'next_text'          => self::$is_rtl ? '<i class="fa fa-angle-double-left ml4"></i>' : '<i class="fa fa-angle-double-right ml4"></i>',
						'before_page_number' => ''
					] );

					echo '</div>';
				}

			} else {
				echo '<h3>' . esc_html( do_shortcode( self::option( 'not_found', esc_html__( 'Nothing Found', 'xtra' ) ) ) ) . '</h3><p>' . esc_html( do_shortcode( self::option( 'not_found_msg', esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'xtra' ) ) ) ) . '</p>';
				echo '<form class="search_404 search_not_found" method="get" action="' . self::$home_url . '">
					<input id="inputhead" name="s" type="text" value="" placeholder="' . esc_html__( 'Search', 'xtra' ) . '">
					<button type="submit"><i class="fa fa-search"></i></button>
				</form>';
			}

			// Action fire after content.
			do_action( 'xtra_after_archive_content' );

			echo '</div>'; // content

			// Comments.
			if ( is_single() || is_page() ) {
				
				if ( ! $is_404 && comments_open() ) {
					
					echo '<div id="comments" class="content xtra-comments clr">';

					if ( self::$preview ) {
						echo '<i class="xtra-section-focus fas fa-paint-brush" data-section="single_styles"></i>';
					}

					comments_template();
					echo '</div>';

				} else if ( isset( $wp_query->queried_object->post_type ) && $wp_query->queried_object->post_type == 'post' ) {
					echo '<p class="cz_nocomment mb10" style="opacity:.4"><i>' . esc_html( do_shortcode( self::option( 'cm_disabled' ) ) ) . '</i></p>';
				}
			}

			echo '</section>';

			// After content
			if ( $is_404 || ! is_active_sidebar( $primary ) ) {
				// ...
			//} else if ( $is_404 ) {
			//	echo '<section class="s12 clr">';
			} else if ( $layout === 'right' ) {
				echo '<aside class="col s4 sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );  
				}
				echo '</div></aside>';
			} else if ( $layout === 'right-s' ) {
				echo '<aside class="col s3 sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );  
				}
				echo '</div></aside>';
			} else if ( $layout === 'left' ) {
				echo '<aside class="col s4 col_first sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );  
				}
				echo '</div></aside>';
			} else if ( $layout === 'left-s' ) {
				echo '<aside class="col s3 col_first sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );  
				}
				echo '</div></aside>';
			} else if ( $layout === 'center' ) {
				echo '<aside class="col s2">&nbsp</aside>';
			} else if ( $layout === 'both-side' ) {
				echo '<aside class="col s3 righter sidebar_secondary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $secondary ) ) {
					dynamic_sidebar( $secondary );  
				}
				echo '</div></aside>';
			} else if ( $layout === 'both-side2' ) {
				echo '<aside class="col s2 righter sidebar_secondary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $secondary ) ) {
					dynamic_sidebar( $secondary );  
				}
				echo '</div></aside>';
			} else if ( $layout === 'both-right' ) {
				echo '<aside class="col s3 sidebar_secondary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $secondary ) ) {
					dynamic_sidebar( $secondary );  
				}
				echo '</div></aside><aside class="col s3 sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );  
				}
				echo '</div></aside>';
			} else if ( $layout === 'both-right2' ) {
				echo '<aside class="col s2 sidebar_secondary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $secondary ) ) {
					dynamic_sidebar( $secondary );  
				}
				echo '</div></aside><aside class="col s3 sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );  
				}
				echo '</div></aside>';
			} else if ( $layout === 'both-left' ) {
				echo '<aside class="col s3 col_first sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );  
				}
				echo '</div></aside><aside class="col s3 sidebar_secondary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $secondary ) ) {
					dynamic_sidebar( $secondary );  
				}
				echo '</div></aside>';
			} else if ( $layout === 'both-left2' ) {
				echo '<aside class="col s3 col_first sidebar_primary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $primary ) ) {
					dynamic_sidebar( $primary );  
				}
				echo '</div></aside><aside class="col s2 sidebar_secondary"><div class="sidebar_inner">';
				if ( is_active_sidebar( $secondary ) ) {
					dynamic_sidebar( $secondary );  
				}
				echo '</div></aside>';
			}

			echo '</div></div>'; // row, page_content
			get_footer();
		}

		/**
		 * Get related posts for single post page
		 * 
		 * @return string
		 */
		public static function related( $args = [] ) {

			$id = self::$post->ID;
			$cpt = get_post_type( $id );
			$meta = self::meta();

			// Settings
			$args = wp_parse_args( $args, [
				'extra_class'		=> '',
				'post_type'			=> $cpt,
				'post__not_in'		=> [ $id ],
				'posts_per_page'	=> 3,
				'related_columns'	=> 's4'
			] );

			// By tags
			$args['tax_query'] = [ 'relation' => 'OR' ];
			$tax = ( $cpt === 'post' ) ? '_tag' : '_tags';
			$tags = wp_get_post_terms( $id, $cpt . $tax, [] );
			$args['tax_query'][] = [
				'taxonomy' 	=> $cpt . $tax,
				'field' 	=> 'slug',
				'terms' 	=> 'fix-query-by-tags'
			];

			if ( is_array( $tags ) ) {
				foreach ( $tags as $tag ) {
					if ( ! empty( $tag->slug ) ) {
						$args['tax_query'][] = [
							'taxonomy' 	=> $cpt . $tax,
							'field' 	=> 'slug',
							'terms' 	=> $tag->slug
						];
					}
				}
			}

			// Generate query
			$query = new WP_Query( $args );

			// If posts not found, try categories
			if ( empty( $query->post_count ) ) {
				if ( $cpt === 'post' ) {
					$args['category__in'] = wp_get_post_categories( $id, [ 'fields'=>'ids' ] );
				} else {
					$taxonomy = $cpt . '_cat';
					$get_cats = get_the_terms( $id, $taxonomy );
					if ( ! empty( $get_cats ) ) {
						$tax = [ 'relation' => 'OR' ];
						foreach( $get_cats as $key ) {
							if ( is_object( $key ) ) {
								$tax[] = [
									'taxonomy' 	=> $taxonomy,
									'terms' 	=> $key->term_id
								];
							}
						}
						$args['tax_query'] = $tax;
					}
				}

				// Regenerate query
				wp_reset_postdata();
				$query = new WP_Query( $args );
			}

			// Output
			ob_start();
			echo '<div class="clr">';
			if ( $query->have_posts() ): 
				$i = 1;
				$col = ( $args['related_columns'] === 's6' ) ? 2 : ( ( $args['related_columns'] === 's4' ) ? 3 : 4 );
				while ( $query->have_posts() ) : $query->the_post();
				$cats = ( ! $cpt || $cpt === '' || $cpt === 'post' ) ? 'category' : $cpt . '_cat';	
			?>
				<article id="post-<?php the_ID(); ?>" class="cz_related_post col <?php echo esc_attr( $args['related_columns'] ); ?>"><div>
					<?php 

					// Post hover icon
					if ( self::contains( self::option( 'hover_icon_' . $cpt ), [ 'image', 'imhoh', 'iasi' ] ) ) {
						$post_hover_icon = '<i class="cz_post_icon"><img src="' . self::option( 'hover_icon_image_' . $cpt ) . '" /></i>';
					} else if ( self::option( 'hover_icon_' . $cpt ) === 'none' ) {
						$post_hover_icon = '';
					} else {
						$post_hover_icon = '<i class="cz_post_icon ' . self::option( 'hover_icon_icon_' . $cpt, 'fa czico-109-link-symbol-1' ) . '"></i>';
					}

					if ( has_post_thumbnail() ) { ?>
						<a class="cz_post_image" href="<?php echo esc_url( get_the_permalink() ); ?>">
							<?php 
								the_post_thumbnail( 'codevz_360_320' );
								echo wp_kses_post( $post_hover_icon );
							?>
						</a>
					<?php } ?>
					<a class="cz_post_title mt10 block" href="<?php echo esc_url( get_the_permalink() ); ?>">
						<h3><?php the_title(); ?></h3>
					</a>
					<?php 
						$cats = get_the_term_list( get_the_id(), $cats, '<small class="cz_related_post_date mt10"><i class="fa fa-folder-open mr10"></i>', ', ', '</small>' );
						if ( ! is_wp_error( $cats ) ) {
							echo wp_kses_post( $cats );
						}
					?>
				</div></article>
			<?php 
				if ( $i % $col === 0 ) {
					echo '</div><div class="clr">';
				}

				$i++;
				endwhile;
			endif;
			echo '</div>';
			wp_reset_postdata();

			$related = ob_get_clean();

			if ( $related && $related !== '<div class="clr"></div>' ) {

				if ( self::$preview ) {
					if ( $cpt === 'post' ) {
						$related .= '<i class="xtra-section-focus fas fa-cog" data-section="single_settings"></i>';
						$related .= '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="single_styles"></i>';
					} else {
						$related .= '<i class="xtra-section-focus fas fa-cog" data-section="' . esc_attr( $cpt ) . '_single_settings"></i>';
						$related .= '<i class="xtra-section-focus xtra-section-focus-second fas fa-paint-brush" data-section="' . esc_attr( $cpt ) . '_single_styles"></i>';
					}
				}

				return '</div><div class="content cz_related_posts clr"><h4>' . esc_html( $args['section_title'] ) . '</h4>' . $related;

			}
		}

		/**
		 * Get string between two string
		 * 
		 * @return string
		 */
		public static function get_string_between( $c = '', $s = '', $e = '', $m = false ) {
			if ( $c ) {
				if ( $m ) {
					preg_match_all( '~' . preg_quote( $s, '~' ) . '(.*?)' . preg_quote( $e, '~' ) . '~s', $c, $matches );
					return $matches[0];
				}

				$r = explode( $s, $c );
				if ( isset( $r[1] ) ) {
					$r = explode( $e, $r[1] );
					return $r[0];
				}
			}

			return;
		}

		/**
		 * Check if string contains specific value(s)
		 * 
		 * @return string
		 */
		public static function contains( $v = '', $a = [] ) {
			if ( $v ) {
				foreach( (array) $a as $k ) {
					if ( $k && strpos( (string) $v, (string) $k ) !== false ) {
						return 1;
						break;
					}
				}
			}
			
			return null;
		}
		
		/**
		 * Get current page title
		 * 
		 * @return string
		 */
		public static function page_title( $tag = 'h3', $class = '' ) {

			if ( is_404() ) {
				$i = '404';

			} else if ( is_search() ) {
				$i = do_shortcode( self::option( 'search_title_prefix', 'Search result for:' ) ) . ' ' . get_search_query();

			} else if ( is_post_type_archive() ) {
				ob_start();
				post_type_archive_title();
				$i = ob_get_clean();

			} else if ( is_archive() ) {
				$i = get_the_archive_title();
				if ( self::contains( $i, ':' ) ) {
					$i = substr( $i, strpos( $i, ': ' ) + 1 );
				}

			} else if ( is_single() ) {
				//$i = single_post_title( '', false );
				//$i = $i ? $i : get_the_title();
				$i = get_the_title();

			} else if ( is_home() ) {
				$i = get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : get_bloginfo( 'name' );
				
			} else {
				$i = get_the_title();
			}

			echo '<' . esc_attr( $tag ) . ' class="section_title ' . esc_attr( $class ) . '">' . do_shortcode( wp_kses_post( $i ) ) . '</' . esc_attr( $tag ) . '>';

		}

		/**
		 * Get author box
		 * 
		 * @return string
		 */
		public static function author_box() {
			return get_the_author_meta( 'description' ) ? '<div class="clr"><div class="lefter mr20 mt10">' . get_avatar( get_the_author_meta( 'user_email' ), '100' ) . '</div><p>' . get_the_author_meta('description') . '</p></div>' : '';
		}

		/**
		 * Get breadcrumbs
		 * 
		 * @return string
		 */
		public static function breadcrumbs( $is_right = '' ) {

			if ( is_front_page() ) {
				return;
			}

			$out = [];
			$bc = (array) self::breadcrumbs_array();
			$count = count( $bc );
			$i = 1;

			if ( self::option( 'page_title_hide_breadcrumbs' ) && $count < 3 ) {
				return;
			}

			foreach( $bc as $ancestor ) {

				if ( $i === $count ) {
					global $wp;
					$out[] = '<b itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="inactive_l"><a class="cz_br_current" href="' . self::$home_url . $wp->request . '" onclick="return false;" itemprop="url"><span itemprop="title">' . wp_kses_post( $ancestor['title'] ) . '</span></a></b>';
				} else {
					$out[] = '<b itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . esc_url( $ancestor['link'] ) . '" itemprop="url"><span itemprop="title">' . wp_kses_post( $ancestor['title'] ) . '</span></a></b>';
				}

				$i++;

			}

			echo '<div class="breadcrumbs clr' . esc_attr( $is_right ) . '">';
			$separator = self::option( 'breadcrumbs_separator', 'fa fa-long-arrow-right' );
			$separator = self::$is_rtl ? str_replace( '-right', '-left', $separator ) : $separator;
			echo wp_kses_post( implode( ' <i class="' . esc_attr( $separator ) . '"></i> ', $out ) );
			echo '</div>';
		}

		public static function breadcrumbs_array() {
			global $post;

			$bc = [];
			$bc[] = [ 'title' => '<i class="fa fa-home cz_breadcrumbs_home"></i>', 'link' => self::$home_url ];
			$bc = self::add_posts_page_array( $bc );
			if ( is_404() ) {
				$bc[] = [ 'title' => '404', 'link' => false ];
			} else if ( is_search() ) {
				$bc[] = [ 'title' => get_search_query(), 'link' => false ];
			} else if ( is_tax() ) {
				$taxonomy = get_query_var( 'taxonomy' );
				$term = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );

				if ( ! empty( $term->taxonomy ) && get_taxonomy( $term->taxonomy ) ) {
					$ptn = get_taxonomy( $term->taxonomy )->object_type[0];
					$label = get_post_type_object( $ptn );
					$label = empty( $label->label ) ? $ptn : $label->label;
					$bc[] = [ 'title' => ucwords( $label ), 'link' => get_post_type_archive_link( $ptn ) ];
				}

				if ( ! empty( $term->parent ) ) {
					$parent = get_term_by( 'term_id', $term->parent, $taxonomy );
					$bc[] = [ 'title' => sprintf( '%s', $parent->name ), 'link' => get_term_link( $parent->term_id, $taxonomy ) ];
				}

				if ( ! empty( $term->name ) && ! empty( $term->term_id ) ) {
					$bc[] = [ 'title' => sprintf( '%s', $term->name ), 'link' => get_term_link( $term->term_id, $taxonomy ) ];
				}

			} else if ( is_attachment() ) {
				if ( $post->post_parent ) {
					$parent_post = get_post( $post->post_parent );
					if ( $parent_post ) {
						$singular_bread_crumb_arr = self::singular_breadcrumbs_array( $parent_post );
						$bc = array_merge( $bc, $singular_bread_crumb_arr );
					}
				}
				if ( isset( $parent_post->post_title ) ) {
					$bc[] = [ 'title' => $parent_post->post_title, 'link' => get_permalink( $parent_post->ID ) ];
				}
				$bc[] = [ 'title' => sprintf( '%s', $post->post_title ), 'link' => get_permalink( $post->ID ) ];
			} else if ( ( is_singular() || is_single() ) && ! is_front_page() ) {
				$singular_bread_crumb_arr = self::singular_breadcrumbs_array( $post );
				$bc = array_merge( $bc, $singular_bread_crumb_arr );
				$bc[] = [ 'title' => $post->post_title, 'link' => get_permalink( $post->ID ) ];
			} else if ( is_category() ) {
				global $cat;

				$category = get_category( $cat );
				if ( $category->parent != 0 ) {
					$ancestors = array_reverse( get_ancestors( $category->term_id, 'category' ) );
					foreach( $ancestors as $ancestor_id ) {
						$ancestor = get_category( $ancestor_id );
						$bc[] = [ 'title' => $ancestor->name, 'link' => get_category_link( $ancestor->term_id ) ];
					}
				}
				$bc[] = [ 'title' => sprintf( '%s', $category->name ), 'link' => get_category_link( $cat ) ];
			} else if ( is_tag() ) {
				global $tag_id;
				$tag = get_tag( $tag_id );
				$bc[] = [ 'title' => sprintf( '%s', $tag->name ), 'link' => get_tag_link( $tag_id ) ];
			} else if ( is_author() ) {
				$author = get_query_var( 'author' );
				$bc[] = [ 'title' => sprintf( '%s', get_the_author_meta( 'display_name', get_query_var( 'author' ) ) ), 'link' => get_author_posts_url( $author ) ];
			} else if ( is_day() ) {
				$m = get_query_var( 'm' );
				if ( $m ) {
					$year = substr( $m, 0, 4 );
					$month = substr( $m, 4, 2 );
					$day = substr( $m, 6, 2 );
				} else {
					$year = get_query_var( 'year' );
					$month = get_query_var( 'monthnum' );
					$day = get_query_var( 'day' );
				}
				$month_title = self::get_month_title( $month );
				$bc[] = [ 'title' => sprintf( '%s', $year ), 'link' => get_year_link( $year ) ];
				$bc[] = [ 'title' => sprintf( '%s', $month_title ), 'link' => get_month_link( $year, $month ) ];
				$bc[] = [ 'title' => sprintf( '%s', $day ), 'link' => get_day_link( $year, $month, $day ) ];
			} else if ( is_month() ) {
				$m = get_query_var( 'm' );
				if ( $m ) {
					$year = substr( $m, 0, 4 );
					$month = substr( $m, 4, 2 );
				} else {
					$year = get_query_var( 'year' );
					$month = get_query_var( 'monthnum' );
				}
				$month_title = self::get_month_title( $month );
				$bc[] = [ 'title' => sprintf( '%s', $year ), 'link' => get_year_link( $year ) ];
				$bc[] = [ 'title' => sprintf( '%s', $month_title ), 'link' => get_month_link( $year, $month ) ];
			} else if ( is_year() ) {
				$m = get_query_var( 'm' );
				if ( $m ) {
					$year = substr( $m, 0, 4 );
				} else {
					$year = get_query_var( 'year' );
				}
				$bc[] = [ 'title' => sprintf( '%s', $year ), 'link' => get_year_link( $year ) ];
			} else if ( is_post_type_archive() ) {
				$post_type = get_post_type_object( get_query_var( 'post_type' ) );
				$bc[] = [ 'title' => sprintf( '%s', $post_type->label ), 'link' => get_post_type_archive_link( $post_type->name ) ];
			}

			return $bc;
		}

		public static function singular_breadcrumbs_array( $post ) {
			$bc = [];
			$post_type = get_post_type_object( $post->post_type );

			if ( $post_type && $post_type->has_archive ) {
				if ( $post_type->name === 'topic' ) {
					$ppt = get_post_type_object( 'forum' );
					$bc[] = [ 'title' => sprintf( '%s', $ppt->label ), 'link' => get_post_type_archive_link( $ppt->name ) ];
				}
				$bc[] = [ 'title' => sprintf( '%s', $post_type->label ), 'link' => get_post_type_archive_link( $post_type->name ) ];
			}

			if ( is_post_type_hierarchical( $post_type->name ) ) {
				$ancestors = array_reverse( get_post_ancestors( $post ) );
				if ( count( $ancestors ) ) {
					$ancestor_posts = get_posts( 'post_type=' . $post_type->name . '&include=' . implode( ',', $ancestors ) );
					foreach( (array) $ancestors as $ancestor ) {
						foreach( (array) $ancestor_posts as $ancestor_post ) {
							if ( $ancestor === $ancestor_post->ID ) {
								$bc[] = [ 'title' => $ancestor_post->post_title, 'link' => get_permalink( $ancestor_post->ID ) ];
							}
						}
					}
				}
			} else {
				$post_type_taxonomies = get_object_taxonomies( $post_type->name, true );
				if ( is_array( $post_type_taxonomies ) && count( $post_type_taxonomies ) ) {
					foreach( $post_type_taxonomies as $tax_slug => $taxonomy ) {
						if ( $taxonomy->hierarchical && $tax_slug !== 'post_tag' ) {
							
							if ( $post_type && $post_type->name === 'product' && is_single() ) {
								$tax_slug = 'product_cat';
							}

							$terms = get_the_terms( self::$post->ID, $tax_slug );
							if ( $terms ) {
								$term = array_shift( $terms );
								if ( $term->parent != 0 ) {
									$ancestors = array_reverse( get_ancestors( $term->term_id, $tax_slug ) );
									foreach( $ancestors as $ancestor_id ) {
										$ancestor = get_term( $ancestor_id, $tax_slug );
										$bc[] = [ 'title' => $ancestor->name, 'link' => get_term_link( $ancestor, $tax_slug ) ];
									}
								}
								$bc[] = [ 'title' => $term->name, 'link' => get_term_link( $term, $tax_slug ) ];

								foreach( $terms as $t ) {
									if ( $term->term_id == $t->parent ) {
										$bc[] = [ 'title' => $t->name, 'link' => get_term_link( $t, $tax_slug ) ];
										break;
									}
								}
								break;
							}
						}
					}
				}
			}

			return $bc;
		}

		public static function add_posts_page_array( $bc ) {
			if ( is_page() || is_front_page() || is_author() || is_date() ) {
				return $bc;
			} else if ( is_category() ) {
				$tax = get_taxonomy( 'category' );
				if ( count( $tax->object_type ) != 1 || $tax->object_type[0] != 'post' ) {
					return $bc;
				}
			} else if ( is_tag() ) {
				$tax = get_taxonomy( 'post_tag' );
				if ( count( $tax->object_type ) != 1 || $tax->object_type[0] != 'post' ) {
					if ( isset( $_GET['post_type'] ) ) {
						$bc[] = [ 'title' => get_post_type_object( $_GET['post_type'] )->labels->name, 'link' => get_post_type_archive_link( $_GET['post_type'] ) ];
					}
					return $bc;
				}
			} else if ( is_tax() ) {
				$tax = get_taxonomy( get_query_var( 'taxonomy' ) );
				if ( count( $tax->object_type ) != 1 || $tax->object_type[0] != 'post' ) {
					return $bc;
				}
			} else if ( is_home() && ! get_query_var( 'pagename' ) ) {
				return $bc;
			} else {
				$post_type = get_query_var( 'post_type' ) ? get_query_var( 'post_type' ) : 'post';
				if ( $post_type != 'post' ) {
					return $bc;
				}
			}
			if ( get_option( 'show_on_front' ) === 'page' && get_option( 'page_for_posts' ) && ! is_404() ) {
				$posts_page = get_post( get_option( 'page_for_posts' ) );
				$bc[] = [ 'title' => $posts_page->post_title, 'link' => get_permalink( $posts_page->ID ) ];
			}

			return $bc;
		}

		public static function get_month_title( $monthnum = 0 ) {
			global $wp_locale;
			$monthnum = (int) $monthnum;
			$date_format = get_option( 'date_format' );
			if ( in_array( $date_format, [ 'DATE_COOKIE', 'DATE_RFC822', 'DATE_RFC850', 'DATE_RFC1036', 'DATE_RFC1123', 'DATE_RFC2822', 'DATE_RSS' ] ) ) {
				$month_format = 'M';
			} else if ( in_array( $date_format, [ 'DATE_ATOM', 'DATE_ISO8601', 'DATE_RFC3339', 'DATE_W3C' ] ) ) {
				$month_format = 'm';
			} else {
				preg_match( '/(^|[^\\\\]+)(F|m|M|n)/', str_replace( '\\\\', '', $date_format ), $m );
				$month_format = empty( $m[2] ) ? 'F' : $m[2];
			}

			if ( $month_format === 'F' ) {
				return $wp_locale->get_month( $monthnum );
			} else if ( $month_format === 'M' ) {
				return $wp_locale->get_month_abbrev( $wp_locale->get_month( $monthnum ) );
			} else {
				return $monthnum;
			}
		}

		/**
		 * Theme automatic update
		 * 
		 * @since 2.7.0
		 */
		public static function update( $transient ) {

			// Get new versions
			$versions = get_transient( 'codevz_versions' );
			if ( empty( $versions ) ) {
				$request = wp_remote_get( self::$api . 'versions.json' );
				if ( ! is_wp_error( $request ) ) {
					$body = wp_remote_retrieve_body( $request );
					$versions = json_decode( $body, true );
					set_transient( 'codevz_versions', $versions, 60 );
				}
			}

			// There is no new update
			if ( ! isset( $versions['themes']['xtra'] ) ) {
				return $transient;
			}

			// Current theme
			$theme = wp_get_theme();
			if ( ! empty( $theme->parent() ) ) {
				$old_version = $theme->parent()->Version;
			} else {
				$old_version = $theme->get( 'Version' );
			}
			$new_version = $versions['themes']['xtra']['version'];

			// Check theme slug and fix white label.
			if ( ! self::contains( strtolower( $theme->get( 'Name' ) ), 'xtra' ) && ! self::contains( strtolower( $theme->get( 'Name' ) ), strtolower( self::option( 'white_label_theme_name', 'xtra' ) ) ) ) {
				return $transient;
			}

			// Compate versions and inform WordPress about new update
			if ( $old_version != $new_version && version_compare( $old_version, $new_version, '<' ) ) {

				$transient->response['xtra'] = [
					'theme' 		=> 'xtra',
					'new_version' 	=> $versions['themes']['xtra']['version'],
					'url' 			=> str_replace( 'api/', '', self::$api ),
					'package' 		=> self::$api . 'xtra.zip',
				];

			} else if ( isset( $transient->response['xtra'] ) ) {
				unset( $transient->response['xtra'] );
			}

			return $transient;
		}

		/**
		 * Theme white label
		 * 
		 * @since 3.2.0
		 */
		public static function white_label() {

			$dir 			= trailingslashit( get_template_directory() );
			$basename 		= basename( $dir );
			$name 			= self::option( 'white_label_theme_name' );
			$slug 			= sanitize_title_with_dashes( $name );
			$screenshot 	= self::option( 'white_label_theme_screenshot', self::$url . 'assets/img/screenshot.png' );
			$description 	= self::option( 'white_label_theme_description', esc_html__( 'Multipurpose Theme', 'xtra' ) );
			$author 		= self::option( 'white_label_author', esc_html__( 'XtraTheme', 'xtra' ) );
			$link 			= self::option( 'white_label_link', 'https://xtratheme.com/' );
			$is_child_theme = is_child_theme();

			if ( empty( $name ) ) {
				return;
			}

			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require ABSPATH . 'wp-admin/includes/file.php';
			}

			global $wp_filesystem;
			WP_Filesystem();

			// Get theme version.
			$theme = wp_get_theme();
			$ver = empty( $theme->parent() ) ? $theme->get( 'Version' ) : $theme->parent()->Version;

			$information = '/*
		Theme Name:   ' . $name . '
		Theme URI:    ' . $link . '
		Description:  ' . $description . '
		Version:      ' . $ver . '
		Author:       ' . $author . '
		Author URI:   ' . $link . '
		License:      GPLv2
		License URI:  http://gnu.org/licenses/gpl-2.0.html
		Tags:         one-column, two-columns, right-sidebar, custom-menu, rtl-language-support, sticky-post, translation-ready
	*/

	/*
		PLEASE DO NOT edit this file, if you want add custom CSS go to Theme Options > Additional CSS
	*/';

			// Save style.css
			$result = $wp_filesystem->put_contents( $dir . 'style.css', $information, FS_CHMOD_FILE );

			// Replace image.
			$new_image = $wp_filesystem->get_contents( $screenshot );
			$result = $wp_filesystem->put_contents( $dir . 'screenshot.png', $new_image, FS_CHMOD_FILE );
			$result = $wp_filesystem->put_contents( str_replace( '/' . $basename . '/', '/' . $slug . '-child/screenshot.png', $dir ), $new_image, FS_CHMOD_FILE );

			// Rename folder name.
			$new_name = str_replace( '/' . $basename . '/', '/' . $slug . '/', $dir );
			rename( $dir, $new_name );

			// Check child theme.
			if ( $is_child_theme ) {

				// Child theme.
				$child = '/*
			Theme Name:	' . $name . ' Child
			Theme URI:	' . $link . '
			Description:' . $description . '
			Author:		' . $author . '
			Author URI:	' . $link . '
			Template:	' . strtolower( $name ) . '
			Version:	1.0
		*/

		/*
			PLEASE DO NOT edit this file, if you want add custom CSS go to Theme Options > Additional CSS
		*/';

				$new_name = str_replace( '/' . $basename . '/', '/' . $slug . '-child/', $dir );
				$child_dir = str_replace( '/' . $basename . '/', '/' . $basename . '-child/', $dir );
				rename( $child_dir, $new_name );

				$result = $wp_filesystem->put_contents( str_replace( '/' . $basename . '/', '/' . $slug . '-child/style.css', $dir ), $child, FS_CHMOD_FILE );

				// Activate child theme.
				switch_theme( $slug . '-child' );

			} else {

				// Theme activate.
				switch_theme( $slug );
			}

		}

	}

	// Run theme class.
	Codevz_Theme::instance();

}