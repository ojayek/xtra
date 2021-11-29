<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

/**
 * Plugin Name: Codevz Plus
 * Plugin URI: https://xtratheme.com/
 * Description: StyleKit, custom post types, options and page builder elements.
 * Version: 4.4.2
 * Author: XtraTheme
 * Author URI: https://xtratheme.com/
 * Text Domain: codevz
 * Copyright: XtraTheme
*/

class Codevz_Plus {

	// Plugin version.
	public static $ver = '4.4.2';

	// Server API address.
	public static $api = 'https://xtratheme.ir/api/';

	// Plugin title.
	public static $title;

	// Directory.
	public static $dir;

	// Plugin URL.
	public static $url;

	// Cache post query.
	public static $post;

	// RTL mode.
	public static $is_rtl = false;

	// Check admin pages.
	public static $is_admin = false;

	// Check WPBakery frontend.
	public static $vc_editable = false;

	// Get array list of pages.
	public static $array_pages = [];

	public static $social_fa_upgrade = [];

	// Instance of this class.
	protected static $instance = null;

	// Core functionality.
	protected function __construct() {

		// Define
		self::$post 		= &$GLOBALS['post'];
		self::$vc_editable 	= ( isset( $_GET['vc_editable'] ) || isset( $_GET['preview_id'] ) || get_option( 'wpm_languages' ) );
		self::$is_admin 	= is_admin();

		self::$title 		= esc_html__( 'XTRA', 'codevz' );
		self::$dir 			= trailingslashit( plugin_dir_path( __FILE__ ) );
		self::$url 			= trailingslashit( plugin_dir_url( __FILE__ ) );

		// After plugin loaded.
		add_action( 'wp', [ $this, 'wp' ] );

		// Get list of all pages as array.
		if( self::$is_admin || is_customize_preview() ) {

			self::$array_pages = [
				'' => esc_html__( '~ Disable ~', 'codevz' )
			];

			$pages = get_posts( [
				'post_type' 		=> [ 'page', 'elementor_library' ],
				'posts_per_page' 	=> -1,
			] );

			foreach( $pages as $page ) {
				if ( isset( $page->post_title ) && $page->post_title ) {
					self::$array_pages[ $page->post_title ] = $page->post_title;
				}
			}

		}

		// Fix font awesome upgrade.
		self::$social_fa_upgrade = [ 'fa ', 'far ', 'fas ', 'fab ', 'fa-', 'fas-', 'far-', 'fab-', 'czico-', '-square', '-official', '-circle' ];

		// Include files.
		require_once( self::$dir . 'admin/csf.php' );
		require_once( self::$dir . 'classes/class-options.php' );
		require_once( self::$dir . 'classes/class-widgets.php' );
		require_once( self::$dir . 'classes/class-menu-walker.php' );
		require_once( self::$dir . 'classes/class-woocommerce.php' );
		require_once( self::$dir . 'classes/class-wpbakery.php' );
		require_once( self::$dir . 'elementor/elementor.php' );
		require_once( self::$dir . 'rtl-xtra/rtl-xtra.php' );

		// Check features.
		$disable = array_flip( (array) self::option( 'disable' ) );

		// Demo importer.
		if( ! isset( $disable['importer'] ) ) {
			require_once( self::$dir . 'classes/class-demo-importer.php' );
		}

		// Presets.
		if( ! isset( $disable['presets'] ) ) {
			require_once( self::$dir . 'classes/class-presets.php' );
		}

		// Templates.
		if( ! isset( $disable['templates'] ) ) {
			require_once( self::$dir . 'classes/class-templates.php' );
		}

		// Lazyload
		$lazyload = self::option( 'lazyload' );

		// WP Lazyload 5.5.x
		if ( $lazyload !== 'wp' ) {
			add_filter( 'wp_lazy_loading_enabled', '__return_false', 999 );
		}

		// jQuery lazyload
		if ( ! self::$vc_editable && $lazyload == 'true' ) {

			$lazyload = [ $this, 'lazyload' ];

			add_filter( 'the_content', $lazyload, 999 );
			add_filter( 'widget_text', $lazyload, 999 );
			add_filter( 'wp_nav_menu_items', $lazyload, 999 );
			add_filter( 'post_thumbnail_html', $lazyload, 999 );
			add_filter( 'woocommerce_product_get_image', $lazyload, 999 );
			add_filter( 'woocommerce_single_product_image_thumbnail_html', $lazyload, 999 );

		}

		// Force disable comments.
		if ( self::option( 'force_disable_comments' ) ) {
			add_filter( 'comments_open', '__return_false' );
		}
		
		// do_shortcode
		add_filter( 'widget_text', 'do_shortcode' );
		
		// Custom sidebars
		add_action( 'wp_ajax_codevz_custom_sidebars', [ $this, 'custom_sidebars' ] );

		// Custom default colors to WP Colorpicker
		add_action( 'admin_footer', [ $this, 'wp_color_palettes' ] );
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'wp_color_palettes' ] );

		// Redirect maintenance page.
		add_filter( 'template_redirect', [ $this, 'maintenance_mode' ] );

		// Ajax search result
		add_action( 'wp_ajax_codevz_ajax_search', [ $this, 'ajax_search' ] );
		add_action( 'wp_ajax_nopriv_codevz_ajax_search', [ $this, 'ajax_search' ] );

		// Post types query settings
		add_action( 'pre_get_posts', [ $this, 'action_pre_get_posts' ], 99 );

		// Actions and filters
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'save_post', array( $this, 'save_post' ), 9999 );

		// Body custom classes
		add_filter( 'body_class', array( $this, 'body_class' ) );

		// Head and footer
		add_action( 'wp_head', array( $this, 'wp_head' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );

		if( ! isset( $disable['options'] ) ) {
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 99 );
		}

		// Share icons.
		add_action( 'xtra_share', [ $this, 'share' ] );
		add_action( 'woocommerce_share', [ $this, 'share' ] );

		// Plugin white label.
		add_filter( 'all_plugins', [ $this, 'white_label' ] );

		// Disable autoptimize on page builder.
		add_filter( 'autoptimize_filter_noptimize', [ $this, 'vc_autoptimize' ] );

		// Disable wp-optimize on page builder.
		add_filter( 'wpo_minify_run_on_page', [ $this, 'wpo_minify_run_on_page' ], 11 );

		// Admin notice for theme and plugin version.
		add_action( 'admin_notices', [ $this, 'admin_notices' ] );

	}

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
	 * @return array|string|null
	 */
	public static function option( $key = '', $default = '' ) {

		$options = get_option( 'codevz_theme_options' );

		return empty( $key ) ? $options : apply_filters( 'xtra/option/' . $key, ( empty( $options[ $key ] ) ? $default : $options[ $key ] ) );

	}

	public static function sendMail( $email = '', $subject = '', $message = '', $headers = '' ) {

		return wp_mail( $email, $subject, $message, $headers );

	}

	/**
	 * Add social share icons to post, page and products.
	 * 
	 * @return String
	 */
	public function share() {

		$share = self::option( 'share' );
		$post_type = array_flip( (array) self::option( 'post_type' ) );

		if ( empty( $share ) || ! isset( $post_type[ self::get_post_type() ] ) ) {
			return false;
		}

		$classes = 'cz_social xtra-share';
		$classes .= self::option( 'share_color' ) ? ' ' . self::option( 'share_color' ) : '';
		$classes .= self::option( 'share_title' ) ? ' cz_social_inline_title' : '';

		$tooltip = self::option( 'share_tooltip' );
		$classes .= $tooltip ? ' cz_tooltip cz_tooltip_up' : '';

		$post_title = get_the_title();
		$post_link  = get_the_permalink();
		$post_link  = get_the_permalink();

		$url = [

			'facebook-f' => [
				'title' => esc_html__( 'Facebook', 'codevz' ),
				'url' 	=> 'https://facebook.com/share.php?u=' . $post_link . '&title=' . $post_title
			],
			'twitter' => [
				'title' => esc_html__( 'Twitter', 'codevz' ),
				'url' 	=> 'https://twitter.com/intent/tweet?text=' . $post_title . '+' . $post_link
			],
			'pinterest' => [
				'title' => esc_html__( 'Pinterest', 'codevz' ),
				'url' 	=> 'https://pinterest.com/pin/create/bookmarklet/?media=' . get_the_post_thumbnail_url( get_the_id(), 'full' ) . '&url=' . $post_link . '&is_video=false&description=' . $post_title
			],
			'reddit' => [
				'title' => esc_html__( 'Reddit', 'codevz' ),
				'url' 	=> 'https://reddit.com/submit?url=' . $post_link . '&title=' . $post_title
			],
			'delicious' => [
				'title' => esc_html__( 'Delicious', 'codevz' ),
				'url' 	=> 'https://del.icio.us/post?url=' . $post_link . '&title=' . $post_title . '&notes=' . strip_tags( get_the_excerpt() )
			],
			'linkedin' => [
				'title' => esc_html__( 'Linkedin', 'codevz' ),
				'url' 	=> 'https://linkedin.com/shareArticle?mini=true&url=' . $post_link . '&title=' . $post_title . '&source=' . $post_link
			],
			'whatsapp' => [
				'title' => esc_html__( 'Whatsapp', 'codevz' ),
				'url' 	=> 'whatsapp://send?text=' . $post_title . ' ' . $post_link
			],
			'telegram' => [
				'title' => esc_html__( 'Telegram', 'codevz' ),
				'url' 	=> 'https://telegram.me/share/url?url=' . $post_link . '&text=' . $post_title
			],
			'envelope' => [
				'title' => esc_html__( 'Email', 'codevz' ),
				'url' 	=> 'mailto:?body=' . $post_title . ' ' . $post_link
			],
			'print' => [
				'title' => esc_html__( 'Print', 'codevz' ),
				'url' 	=> '#'
			],
			'copy' => [
				'title' => esc_html__( 'Shortlink', 'codevz' ),
				'url' 	=> wp_get_shortlink( get_the_id() )
			],

		];

		echo '<div class="clr mb10"></div>';

		$data = self::option( 'share_box_title' ) ? ' data-title="' . esc_attr( do_shortcode( self::option( 'share_box_title' ) ) ) . '"' : '';

		echo '<div class="' . esc_attr( $classes ) . '"' . wp_kses_post( $data ) . '>';

		if ( is_customize_preview() ) {
			echo '<i class="xtra-section-focus fas fa-cog" data-section="share"></i>';
		}

		// Echo share icons.
		foreach( $share as $name ) {

			$name = ( $name === 'facebook' ) ? 'facebook-f' : $name;

			if ( isset( $url[ $name ] ) ) {

				$title_prefix = ( self::contains( $name, [ 'envelope', 'whatsapp', 'telegram' ] ) ) ? esc_html__( 'Share by', 'codevz' ) : esc_html__( 'Share on', 'codevz' );
				$title_prefix = ( self::contains( $name, [ 'copy' ] ) ) ? esc_html__( 'Copy', 'codevz' ) . ' ' : $title_prefix;
				$title_prefix = ( $name === 'print' ) ? '' : $title_prefix;
				$icon_prefix = ( $name === 'envelope' || $name === 'print' ) ? 'fa' : 'fab';
				$icon_prefix = ( $name === 'copy' ) ? 'far' : $icon_prefix;
				$custom_data = ( $name === 'copy' ) ? ' data-copied="' . esc_html__( 'Link copied', 'codevz' ) . '"' : '';

				echo '<a href="' . $url[ $name ]['url'] . '" rel="noopener noreferrer nofollow" class="cz-' . $name . '" ' . ( $tooltip ? 'data-' : '' ) . 'title="' . esc_attr( $title_prefix . ' ' . $url[ $name ]['title'] ) . '"' . $custom_data . '><i class="' . $icon_prefix . ' fa-' . $name . '"></i><span>' . esc_html( $url[ $name ]['title'] ) . '</span></a>';

			}

		}

		echo '</div>';

	}

	/**
	 * Disable autoptimize on page builder.
	 * 
	 * @return boolean
	 */
	public function vc_autoptimize() {

		return isset( $_GET['vc_editable'] );

	}

	public function wpo_minify_run_on_page( $value ) {

		if ( self::$vc_editable ) {

			return false;

		}

		return $value;

	}

	/**
	 * New shortcut menus to WP admin bar
	 * @var object of WP admin bar
	 * @return object
	 */
	public static function admin_bar_menu( $i ) {
		$admin = get_admin_url();
		$customize = $admin . 'customize.php?url=' . urlencode( esc_url( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) ) . '&';
		
		$i->add_node(array(
			'id' 	=> 'codevz_menu',
			'title' => esc_html__( 'Theme Options', 'codevz' ), 
			'href' 	=> $customize
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_demos',
			'title' => esc_html__( 'Demo Importer', 'codevz' ), 
			'href' 	=> $admin . 'admin.php?page=xtra-importer',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_favicon',
			'title' => esc_html__( 'Site Favicon', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=title_tagline',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_layout',
			'title' => esc_html__( 'Layout', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-layout',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_colors',
			'title' => esc_html__( 'Theme Colors', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-styling',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_typography',
			'title' => esc_html__( 'Typography', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-typography',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_logo',
			'title' => esc_html__( 'Site Logo', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[control]=codevz_theme_options[logo]',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_header',
			'title' => esc_html__( 'Header', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-header',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_mobile_header',
			'title' => esc_html__( 'Mobile Header', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-mobile_header',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_title',
			'title' => esc_html__( 'Title & Breadcrumbs', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-title_br',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_back_to_top',
			'title' => esc_html__( 'Back to top icon', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-footer_more',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_footer',
			'title' => esc_html__( 'Footer', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-footer',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_copyright',
			'title' => esc_html__( 'Copyright text', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-footer_2',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_posts',
			'title' => esc_html__( 'Blog Settings', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-posts',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_portfolio',
			'title' => esc_html__( 'Portfolio Settings', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-portfolio',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_product',
			'title' => esc_html__( 'WooCommerce', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=codevz_theme_options-product',
		));
		$i->add_node(array(
			'parent'=> 'codevz_menu',
			'id' 	=> 'codevz_menu_custom_css',
			'title' => esc_html__( 'Additional CSS', 'codevz' ), 
			'href' 	=> $customize . 'autofocus[section]=custom_css',
		));

		$mt = self::option( 'maintenance_mode' );
		if ( $mt && $mt !== 'none' ) {
			$i->add_node(array(
				'id' 	=> 'codevz_menu_maintenance',
				'title' => esc_html__( 'Maintenance mode is ON', 'codevz' ), 
				'href' 	=> $customize . 'autofocus[control]=codevz_theme_options[maintenance_mode]',
			));
		}
		
		$i->remove_menu( 'customize' );
	}

	/**
	 * Body Classes
	 * 
	 * @return string
	 */
	public static function body_class( $c = [] ) {

		// Post type class
		$cpt = self::get_post_type();
		$cpt = $cpt ? $cpt : get_post_type();
		$cpt = ( ! $cpt || $cpt === 'page' || is_search() ) ? 'post' : $cpt;
		if ( $cpt ) {

			$c[] = 'cz-cpt-' . $cpt;

			// Woo single
			if ( $cpt === 'product' && is_single() ) {

				$tabs = self::option( 'woo_product_tabs' );
				if ( $tabs ) {
					$c[] = 'woo-product-tabs-' . $tabs;
				}

				if ( in_array( 'lightbox', (array) self::option( 'woo_gallery_features' ) ) ) {
					$c[] = 'woo-disable-lightbox';
				}

			}

			if ( self::option( $cpt . '_custom_single_sk' ) ) {
				$c[] = 'single-' . $cpt . '-sk';
			}

		}

		// Two columns products on mobile.
		$c[] = self::option( 'woo_two_col_mobile' ) ? 'xtra-woo-two-col-mobile' : '';

		// RTL
		$c[] = self::$is_rtl ? 'rtl' : '';

		// Sticky
		$c[] = self::option( 'sticky' ) ? 'cz_sticky' : '';

		// Disable lightbox
		$c[] = self::option( 'disable_lightbox' ) ? 'no_lightbox' : '';

		// Theme version.
		$theme = wp_get_theme();
		$c[] = 'xtra-' . ( empty( $theme->parent() ) ? $theme->get( 'Version' ) : $theme->parent()->Version );

		// Plugins version.
		$c[] = 'codevz-plus-' . self::$ver;

		// Fix
		$c[] = 'clr';

		// Page ID
		if ( get_the_id() ) {
			$c[] = 'cz-page-' . get_the_id();
		}

		return $c;
	}

	/**
	 * wp_head
	 * 
	 * @return string
	 */
	public static function wp_head() {

		// Disable automatic telephone link for mobile.
		echo apply_filters( 'xtra_telephone_meta', '<meta name="format-detection" content="telephone=no">' . "\n" );

		// SEO meta tags
		if ( ! self::$vc_editable && self::option( 'seo_meta_tags' ) && ! defined( 'WPSEO_VERSION' ) ) {

			$title = $desc = $tags = '';

			if ( is_single() || is_page() ) {
				$url = get_the_permalink();
				$title = get_the_title();
				$desc = self::meta( false, 'seo_desc' );
				if ( ! $desc ) {
					$desc = apply_filters( 'the_content', self::$post->post_content );
					$desc = $desc ? wp_trim_words( do_shortcode( strip_tags( $desc ) ), 30 ) : $title;
				}
				$tags = self::meta( false, 'seo_keywords' );
				$tags = $tags ? $tags : rtrim( strip_tags( str_replace( '</a>', ',', get_the_tag_list() ) ), ',' );
				$image = get_the_post_thumbnail_url();
				echo $image ? '<meta property="og:image" content="' . $image . '" />' . "\n" : '';
			} else {
				global $wp;
				$url = home_url( $wp->request );
			}

			$title = $title ? $title : get_bloginfo( 'name' );

			if ( is_front_page() || ! $desc ) {
				$desc = self::option( 'seo_desc', get_bloginfo( 'description' ) );
			}

			$desc = trim( preg_replace( '/\s+/', ' ', strip_tags( $desc ) ) );
			$tags = $tags ? $tags : self::option( 'seo_keywords' );

			echo '<meta property="og:title" content="' . strip_tags( $title ) . '" />' . "\n";
			echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
			echo '<meta name="description" content="' . $desc . '">' . "\n";
			echo $tags ? '<meta name="keywords" content="' . strip_tags( $tags ) . '">' . "\n" : '';
			echo '<meta property="og:description" content="' . $desc . '" />' . "\n";
			echo '<meta property="og:type" content="website" />' . "\n";

		}

		// Custom header codes
		echo str_replace( '&', '&amp;', do_shortcode( self::option( 'head_codes' ) ) );
	}

	/**
	 * Site footer.
	 * 
	 * @return string
	 */
	public function wp_footer() {

		// Back to top
		echo self::option( 'backtotop' ) ? '<i class="' . esc_attr( self::option( 'backtotop' ) ) . ' backtotop">' . ( is_customize_preview() ? '<i class="xtra-section-focus fas fa-cog" data-section="footer_more"></i>' : '' ) . '</i>' : '';

		// Quick contact
		$cf7 = self::option( 'cf7_beside_backtotop' );

		if ( $cf7 ) {

			$cf7 = self::get_page_as_element( esc_html( $cf7 ) );

			$cf7_link = self::option( 'cf7_beside_backtotop_link' );

			$icon = '<i class="' . esc_attr( self::option( 'cf7_beside_backtotop_icon', 'fa fa-envelope-o' ) ) . ' fixed_contact">' . ( is_customize_preview() ? '<i class="xtra-section-focus fas fa-cog" data-section="footer_more"></i>' : '' ) . '</i>';

			if ( $cf7_link && ! $cf7 ) {

				echo '<a href="' . $cf7_link . '" target="_blank">' . wp_kses_post( $icon ) . '</a>';

			} else if ( $cf7 ) {

				echo wp_kses_post( $icon );

			}

			if ( $cf7 ) {
				echo '<div class="fixed_contact">' . $cf7 . '</div>';
			}
		}

		// Popup
		$popup = self::get_page_as_element( esc_html( self::option( 'popup' ) ) );

		if ( $popup ) {
			echo '<div class="cz-pages-popup hidden">' . $popup . '</div>';
		}

		echo '<div class="cz_fixed_top_border"></div><div class="cz_fixed_bottom_border"></div>';

		// Fixed mobile HTML.
		self::mobile_fixed_navigation();

		// Cookie notice.
		if ( empty( $_COOKIE[ 'xtra_cookie' ] ) ) {

			$custom_cookie = empty( $GLOBALS[ 'xtra_cookie' ] ) ? false : $GLOBALS[ 'xtra_cookie' ];

			$cookie = self::option( 'cookie', esc_html( $custom_cookie ) );

			if ( $cookie ) {

				if ( $custom_cookie && $GLOBALS[ 'xtra_cookie_content' ] ) {

					$content = wp_kses_post( $GLOBALS[ 'xtra_cookie_content' ] );

				} else {

					$content = self::option( 'cookie_content', esc_html__( 'We use cookies from third party services for marketing activities to offer you a better experience.' ) );

				}

				$button = self::option( 'cookie_button', esc_html__( 'Accept and close', 'codevz' ) );

				echo '<div class="xtra-cookie ' . esc_attr( $cookie ) . '">';

				if ( is_customize_preview() ) {
					echo '<i class="xtra-section-focus fas fa-cog" data-section="cookie"></i>';
				}

				echo '<span>' . do_shortcode( wp_kses_post( $content ) ) . '</span>';
				echo '<a href="#" class="cz_btn xtra-cookie-button">' . do_shortcode( wp_kses_post( $button ) ) . '</a>';
				echo '</div>';

			}

		}

		// Custom footer codes.
		echo str_replace( '&', '&amp;', do_shortcode( self::option( 'foot_codes' ) ) );

	}

	/**
	 * Mobile fixed navigation icons.
	 * 
	 * @return string
	 */
	public static function mobile_fixed_navigation() {

		if ( self::option( 'mobile_fixed_navigation' ) ) {

			echo '<div class="xtra-fixed-mobile-nav ' . esc_attr( self::option( 'mobile_fixed_navigation_title' ) ) . '">';

			if ( is_customize_preview() ) {
				echo '<i class="xtra-section-focus fas fa-cog" data-section="mobile_fixed_navigation"></i>';
			}

			$items = self::option( 'mobile_fixed_navigation_items', [] );

			foreach ( $items as $item ) {

				$item = wp_parse_args( $item,
					[
						'title' 		=> '',
						'icon_type' 	=> '',
						'icon' 			=> '',
						'image' 		=> '',
						'image_size' 	=> '',
						'link' 			=> '',
					]
				);

				if ( isset( $item['icon'] ) ) {

					if ( $item[ 'icon_type' ] === 'icon' ) {

						$icon = '<i class="' . do_shortcode( esc_attr( $item['icon'] ) ) . '"></i>';

					} else {

						$size = $item[ 'image_size' ] ? ' style="width: ' . esc_attr( $item[ 'image_size' ] ) . '"' : '';
						$icon = '<img src="' . do_shortcode( esc_attr( $item['image'] ) ) . '" alt="mobile-nav"' . $size . ' />';

					}

					echo '<a href="' . do_shortcode( esc_url( $item['link'] ) ) . '" title="' . do_shortcode( esc_attr( $item['title'] ) ) . '">' . wp_kses_post( $icon ) . '<span>' . do_shortcode( wp_kses_post( $item['title'] ) ) . '</span></a>';

				}

			}

			echo '</div>';

		}

	}

	/**
	 * Get shortcode from page ID + Generate styles
	 * 
	 * @var post ID
	 * @return string
	 */
	public static function get_page_as_element( $id = '', $query = 0 ) {

		// Escape
		$id = esc_html( $id );

		// Check
		if ( ! $id ) {
			return;
		}

		// Check number and 404.
		if ( ! is_numeric( $id ) || $id === '404' ) {

			$page = get_page_by_title( $id );

			if ( empty( $page->ID ) ) {
				$page = get_page_by_title( $id, true, 'elementor_library' );
			}

			if ( isset( $page->ID ) && ! is_page( $page->ID ) ) {
				$id = $page->ID;
			} else {
				return;
			}

		}

		$status = get_post_status( $id );

		// If post not exist or its same page.
		if ( ! $status || $status === 'inherit' || is_page( $id ) ) {
			return;
		}

		// WPML compatible
		if ( function_exists( 'icl_object_id' ) ) {
			$id = icl_object_id( $id, 'page', true, ICL_LANGUAGE_CODE );
		}

		// Elementor.
		if ( get_post_meta( $id, '_elementor_edit_mode', true ) ) {

			return \Elementor\Plugin::instance()->frontend->get_builder_content( $id, true );

		}

		// Get post content by ID
		$o = get_post_field( 'post_content', $id );

		// Fix posts grid
		if ( $query ) {
			$o = str_replace( 'query=""', 'query="1"', $o );
		}
		
		// Get post meta
		$s = get_post_meta( $id, '_wpb_shortcodes_custom_css', 1 ) . get_post_meta( $id, 'cz_sc_styles', 1 ) . get_post_meta( $id, 'codevz_single_page_css', 1 );

		// Responsive page builder tablet styles
		$tablet = get_post_meta( $id, 'cz_sc_styles_tablet', 1 );
		if ( $tablet ) {
			if ( substr( $tablet, 0, 1 ) === '@' ) {
				$s .= $tablet;
			} else {
				$s .= '@media screen and (max-width:' . self::option( 'tablet_breakpoint', '768px' ) . '){' . $tablet . '}';
			}
		}

		// Responsive page builder mobile styles
		$mobile = get_post_meta( $id, 'cz_sc_styles_mobile', 1 );
		if ( $mobile ) {
			if ( substr( $mobile, 0, 1 ) === '@' ) {
				$s .= $mobile;
			} else {
				$s .= '@media screen and (max-width:' . self::option( 'mobile_breakpoint', '480px' ) . '){' . $mobile . '}';
			}
		}

		// Output
		if ( ! is_page( $id ) ) {
			$o = "<div data-cz-style='" . esc_attr( preg_replace( "/(.cz-page-)(.*)[{]/", "{", $s ) ) . "'>" . do_shortcode( $o ) . "</div>";
		} else {
			return;
		}

		return $o;
	}

	/**
	 * Get current post type name
	 * 
	 * @return string
	 */
	public static function get_post_type( $id = '', $page = false ) {

		if ( is_search() || is_tag() || is_404() ) {
			$cpt = '';
		} else if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
			$cpt = 'bbpress';
		} else if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_woocommerce() ) ) {
			$cpt = 'product';
		} else if ( function_exists( 'is_buddypress' ) && is_buddypress() ) {
			$cpt = 'buddypress';
		} else if ( ( ! $page && get_post_type( $id ) ) || is_singular() ) {
			$cpt = get_post_type( $id );
		} else if ( is_tax() ) {
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			if ( get_taxonomy( $term->taxonomy ) ) {
				$cpt = get_taxonomy( $term->taxonomy )->object_type[0];
			}
		} else if ( is_post_type_archive() ) {
			$cpt = get_post_type_object( get_query_var( 'post_type' ) )->name;
		} else {
			$cpt = 'post';
		}

		return $cpt;
	}

	/**
	 * WPBakery animation settings to elements.
	 * 
	 * @return object
	 */
	public static function wpb_animation_tab( $setting = false ) {
		return class_exists( 'WPBakeryShortCodesContainer' ) ? vc_map_add_css_animation( $setting ) : false;
	}

	/**
	 * WordPress init
	 * 
	 * @return object
	 */
	public function init() {

		// Menu locations.
		register_nav_menus(
			[
				'primary' 	=> esc_html__( 'Primary', 'codevz' ), 
				'one-page' 	=> esc_html__( 'One Page', 'codevz' ), 
				'secondary' => esc_html__( 'Secondary', 'codevz' ), 
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
			]
		);

		// Register CPTs
		self::post_types();

		// Enqueue and register plugin assets
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		if ( ! isset( $_POST['vc_inline'] ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts_page_builder' ), 999 );
		}

		// Admin assets for Presets, StyleKit and Theme colors for palettes
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Custom JS/CSS for VC popup box
		add_filter( 'vc_edit_form_fields_after_render', array( $this, 'vc_edit_form_fields_after_render' ) );

		// Enable some features for WP Editor
		add_filter( 'mce_buttons_2', array( $this, 'mce_buttons_2' ) );

		// Customize some features of WP Editor.
		add_filter( 'tiny_mce_before_init', array( $this, 'tiny_mce_before_init' ) );

		// New Params for WPBalery.
		if ( function_exists( 'vc_add_shortcode_param' ) ) {

			vc_add_shortcode_param( 'cz_title', array( $this, 'vc_param_cz_title' ) );
			vc_add_shortcode_param( 'cz_sc_id', array( $this, 'vc_param_cz_sc_id' ) );
			vc_add_shortcode_param( 'cz_hidden', array( $this, 'vc_param_cz_hidden' ) );
			vc_add_shortcode_param( 'cz_presets', array( $this, 'vc_param_cz_presets' ) );
			vc_add_shortcode_param( 'cz_sk', array( $this, 'vc_param_cz_sk' ) );
			vc_add_shortcode_param( 'cz_upload', array( $this, 'vc_param_cz_upload' ) );
			vc_add_shortcode_param( 'cz_icon', array( $this, 'vc_param_cz_icon' ) );
			vc_add_shortcode_param( 'cz_image_select', array( $this, 'vc_param_image_select' ) );
			vc_add_shortcode_param( 'cz_slider', array( $this, 'vc_param_cz_slider' ) );

		} else {

			// For non-wpbakery page builders, add shortcodes to WordPress.
			$elements = [ 'button', 'social_icons', 'stylish_list', 'popup', 'working_hours', 'gallery', 'carousel', 'subscribe' ];

			foreach( $elements as $i ) {
				require_once( Codevz_Plus::$dir . 'wpbakery/' . $i . '.php' );
				$class = 'Codevz_WPBakery_' . $i;
				$new_class = new $class( 'cz_' . $i );
				$new_class->in();
			}

		}

		// Filter for moving animation param into new tab Animation.
		add_filter( 'vc_map_add_css_animation', array( $this, 'vc_map_add_css_animation' ) );

		// Useful shortcodes
		add_shortcode( 'br', array( $this, 'br' ) );
		add_shortcode( 'cz_lang', array( $this, 'shortcode_translate' ) );
		add_shortcode( 'codevz_year', array( $this, 'shortcode_get_current_year' ) );
		add_shortcode( 'cz_current_year', array( $this, 'shortcode_get_current_year' ) );
		add_shortcode( 'cz_google_font', array( $this, 'shortcode_google_font' ) );

		// Add loop animations to vc animations list
		add_filter( 'vc_param_animation_style_list', array( $this, 'vc_param_animation_style_list' ) );

		// Plugin Languages
		load_textdomain( 'codevz', self::$dir . 'languages/codevz-' . get_locale() . '.mo' );

	}

	/**
	 * Extract shortcode atts according to shortcode_atts and vc_map function.
	 * 
	 * @return array
	 */
	public static function shortcode_atts( $element, $atts = [] ) {

		$params = [];
		$vc_map = $element->in();

		foreach ( $vc_map[ 'params' ] as $param ) {

			if ( isset( $param['param_name'] ) && 'content' !== $param['param_name'] ) {

				$value = '';

				if ( isset( $param['type'] ) && 'checkbox' === $param['type'] ) {

					$value = false;

				} else if ( isset( $param['std'] ) ) {

					$value = $param['std'];

				} elseif ( isset( $param['value'] ) ) {

					if ( is_array( $param['value'] ) ) {

						$value = current( $param['value'] );

						if ( is_array( $value ) ) {

							$value = current( $value );
						}

					} else {

						$value = $param['value'];

					}

				}

				$params[ $param['param_name'] ] = $value;

			}

		}

		return shortcode_atts( $params, $atts, $element->name );
	}

	/**
	 * WPBakery custom params
	 */
	public static function vc_param_cz_title( $s, $v ) {
		$c = empty( $s['class'] ) ? '' : ' class="' . $s['class'] . '"';
		$u = empty( $s['url'] ) ? '' : '<a href="' . $s['url'] . '" target="_blank">';
		return $u . '<h4' . $c . '>' . $s['content'] . '</h4>' . ( $u ? '</a>' : '' ) . '<input type="hidden" name="' . $s['param_name'] . '" class="wpb_vc_param_value ' . $s['param_name'] . ' '.$s['type'].'_field" value="'.$v.'" />';
	}

	public static function vc_param_cz_sc_id( $s, $v ) {
		return '<input type="hidden" name="' . $s['param_name'] . '" class="wpb_vc_param_value ' . $s['param_name'] . ' '.$s['type'].'_field" value="'.$v.'" />';
	}

	public static function vc_param_cz_hidden( $s, $v ) {
		return '<input type="hidden" name="' . $s['param_name'] . '" class="wpb_vc_param_value ' . $s['param_name'] . ' '.$s['type'].'_field" value="'.$v.'" />';
	}

	public static function vc_param_cz_presets( $s, $v ) {
		return '<div class="cz_presets clr ' . $s['class'] . '" data-presets="' . $s['param_name'] . '"><div class="cz_presets_loader"></div></div>';
	}

	public static function vc_param_cz_sk( $s, $v ) {
		$hover = isset( $s['hover_id'] ) ? ' data-hover_id="' . $s['hover_id'] . '"' : '';
		$out = '<div class="cz_sk clr"><input type="hidden" name="'. $s['param_name'] . '"' . $hover . ' value="' . $v . '" class="csf-onload wpb_vc_param_value ' . esc_attr( $s['param_name'] ) .' '. esc_attr( $s['type'] ) . '" data-selector="' . ( isset( $s['selector'] ) ? $s['selector'] : '' ) . '" data-fields="' . implode( ' ', $s['settings'] ) . '" />';
		
		$is_active = $v ? ' active_stylekit' : '';
		
		$bg = '';
		if ( self::contains( $v, 'http' ) ) {
			preg_match_all( '/(http|https):\/\/[^ ]+(\.gif|\.jpg|\.jpeg|\.png)/', $v, $img );
			$bg = isset( $img[0][0] ) ? ' style="background-image:url(' . $img[0][0] . ')"' : '';
		}

		$out .= '<a href="#" class="button cz_sk_btn' . $is_active . '"><span class="cz_skico cz_skico_vc"></span>' . $s['button'] . '</a><div class="sk_btn_preview_image"' . $bg . '></div></div>';


		return $out;
	}

	public static function vc_param_cz_upload( $s, $v ) {
		$f = array(
			'id'    => esc_attr( $s['param_name'] ),
			'name'  => esc_attr( $s['param_name'] ),
			'type'  => 'upload',
			'title' => '',
			'attributes' => array(
				'class' => 'csf-onload wpb_vc_param_value '.esc_attr( $s['param_name'] ) .' '. esc_attr( $s['type'] ).''
			),
			'settings'   => array(
				'upload_type'  => esc_attr( $s['upload_type'] ),
				'frame_title'  => 'Upload / Select',
				'insert_title' => 'Insert',
			),
		);

		if ( function_exists('csf_add_field') ) {
			return '<div class="csf-onload">' . csf_add_field( $f, $v ) . '</div>';
		} else {
			return '<div class="my_param_block">'
				.'<input name="' . esc_attr( $s['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
				esc_attr( $s['param_name'] ) . ' ' .
				esc_attr( $s['type'] ) . '_field" type="text" value="' . esc_attr( $v ) . '" />' .
				'</div>';
		}
	}

	public static function vc_param_cz_icon( $s, $v ) {
		$f = array(
			'id'    => esc_attr( $s['param_name'] ),
			'name'  => esc_attr( $s['param_name'] ),
			'type'  => 'icon',
			'title' => '',
			'after'	=> '<input type="hidden" name="'.$s['param_name'].'" class="wpb_vc_param_value '.$s['param_name'].' '.$s['type'].'_field" value="'.$v.'" />',
			'attributes' => array(
				'class' => 'csf-onload wpb_vc_param_value '.esc_attr( $s['param_name'] ) .' '. esc_attr( $s['type'] ).''
			),
		);

		if ( function_exists('csf_add_field') ) {
			return '<div class="csf-onload">' . csf_add_field( $f, $v ) . '</div>';
		} else {
			return '<div class="my_param_block">'
				.'<input name="' . esc_attr( $s['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
				esc_attr( $s['param_name'] ) . ' ' .
				esc_attr( $s['type'] ) . '_field" type="text" value="' . esc_attr( $v ) . '" />' .
				'</div>';
		}
	}

	public static function vc_param_image_select( $s, $v ) {
		$f = array(
			'id'    => esc_attr( $s['param_name'] ),
			'name'  => esc_attr( $s['param_name'] ),
			'type'  => 'image_select',
			'options' => isset( $s['options'] ) ? $s['options'] : [],
			'radio' => true,
			'title' => '',
			'after'	=> '<input type="hidden" name="' . $s['param_name'] . '" class="wpb_vc_param_value ' . $s['param_name'] . ' '.$s['type'].'_field" value="'.$v.'" />',
			'attributes' => array(
				'class' 			=> 'csf-onload',
				'data-depend-id' 	=> esc_attr( $s['param_name'] )
			),
		);

		if ( function_exists('csf_add_field') ) {
			return '<div class="csf-onload">' . csf_add_field( $f, $v ) . '</div>';
		} else {
			return '<div class="my_param_block">'
				.'<input name="' . esc_attr( $s['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
				esc_attr( $s['param_name'] ) . ' ' .
				esc_attr( $s['type'] ) . '_field" type="text" value="' . esc_attr( $v ) . '" />' .
				'</div>';
		}
	}

	public static function vc_param_cz_slider( $s, $v ) {
		$f = array(
			'id'    => esc_attr( $s['param_name'] ),
			'name'  => esc_attr( $s['param_name'] ),
			'type'  => 'slider',
			'options' => isset( $s['options'] ) ? $s['options'] : array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 120 ),
			'title' => '',
			'after'	=> '<input type="hidden" name="'.$s['param_name'].'" class="wpb_vc_param_value '.$s['param_name'].' '.$s['type'].'_field" value="'.$v.'" />',
			'attributes' => array(
				'class' => 'csf-onload wpb_vc_param_value '.esc_attr( $s['param_name'] ) .' '. esc_attr( $s['type'] ).''
			),
		);

		if ( function_exists('csf_add_field') ) {
			return '<div class="csf-onload">' . csf_add_field( $f, $v ) . '</div>';
		} else {
			return '<div class="my_param_block">'
				.'<input name="' . esc_attr( $s['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
				esc_attr( $s['param_name'] ) . ' ' .
				esc_attr( $s['type'] ) . '_field" type="text" value="' . esc_attr( $v ) . '" />' .
				'</div>';
		}
	}

	/**
	 * Enqueue and register plugin assets
	 * 
	 * @return string
	 */
	public static function wp_enqueue_scripts() {

		// Update plugin version.
		if ( version_compare( get_option( 'xtra_plugin_version' ), self::$ver, '<' ) ) {

			// Autoptimize fix.
			if ( class_exists( 'autoptimizeCache' ) ) {
				autoptimizeCache::clearall();
			}

			update_option( 'xtra_plugin_version', 	self::$ver );
		}

		// Plugin JS.
		wp_enqueue_script( 'codevz-plugin', self::$url . 'assets/js/codevzplus.js', [ 'jquery' ], self::$ver, true );

		if ( self::$is_rtl ) {
			wp_enqueue_script( 'codevz-plugin-rtl', self::$url . 'assets/js/codevzplus.rtl.js', [ 'jquery' ], self::$ver, true );
		}

		// Wishlist.
		$link = home_url( 'wishlist' );
		$wishlist_page = self::option( 'woo_wishlist_page', 'Wishlist' );

		$wishlist_page = get_page_by_title( $wishlist_page, 'object', 'page' );
		if ( isset( $wishlist_page->ID ) ) {
			$link = get_permalink( $wishlist_page->ID );
		}

		wp_localize_script( 'codevz-plugin', 'xtra_strings', array(
			'wishlist_url' 		=> $link,
			'add_wishlist' 		=> esc_html__( 'Add to wishlist', 'codevz' ),
			'added_wishlist' 	=> esc_html__( 'Browse wishlist', 'codevz' )
		) );

		// Woocommerce.
		if ( function_exists( 'is_woocommerce' ) ) {

			wp_enqueue_style( 'xtra-woocommerce', self::$url . 'assets/css/woocommerce.css', [], self::$ver );
			wp_enqueue_script( 'xtra-woocommerce', self::$url . 'assets/js/woocommerce.js', [ 'codevz-plugin' ], self::$ver, true );

			if ( self::$is_rtl ) {
				wp_enqueue_style( 'xtra-woocommerce-rtl', self::$url . 'assets/css/woocommerce.rtl.css', [ 'xtra-woocommerce' ], self::$ver );
			}

		}

		// bbpress.
		if ( function_exists( 'is_bbpress' ) ) {
			wp_enqueue_style( 'xtra-bbpress', self::$url . 'assets/css/bbpress.css', [], self::$ver );
		}

		// EDD.
		if ( function_exists( 'EDD' ) ) {

			wp_enqueue_style( 'xtra-edd', self::$url . 'assets/css/edd.css', [], self::$ver );

			if ( self::$is_rtl ) {
				wp_enqueue_style( 'xtra-edd-rtl', self::$url . 'assets/css/edd.rtl.css', [ 'xtra-edd' ], self::$ver );
			}

		}

		// Soundmanager.
		wp_register_script( 'codevz-soundmanager', 	self::$url . 'assets/soundmanager/script/soundmanager.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'codevz-bar-ui', 		self::$url . 'assets/soundmanager/script/bar-ui.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_style(  'codevz-bar-ui', 		self::$url . 'assets/soundmanager/css/bar-ui.css' );

		// Titl.
		wp_register_script( 'codevz-tilt', 			self::$url . 'assets/js/tilt.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_style(  'codevz-tilt', 			self::$url . 'assets/css/tilt.css', [], self::$ver );

		// Share.
		if ( self::option( 'share' ) ) {
			wp_enqueue_script( 'codevz-share', 		self::$url . 'assets/js/share.js', [ 'codevz-plugin' ], self::$ver, true );
			wp_enqueue_style(  'codevz-share', 		self::$url . 'assets/css/share.css', [], self::$ver );
		}

		// Mobile fixed nav.
		if ( self::option( 'mobile_fixed_navigation' ) ) {
			wp_enqueue_script( 'codevz-mobile-fixed-nav', self::$url . 'assets/js/mobile-nav.js', [ 'codevz-plugin' ], self::$ver, true );
			wp_enqueue_style(  'codevz-mobile-fixed-nav', self::$url . 'assets/css/mobile-nav.css', [], self::$ver );
		}

		// Parallax.
		wp_register_script( 'cz_parallax', 		self::$url . 'assets/js/parallax.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_style(  'cz_parallax', 		self::$url . 'assets/css/parallax.css', [], self::$ver );

		// Elements scripts.
		wp_register_script( 'codevz-tooltip', 		self::$url . 'assets/js/tooltips.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'codevz-modernizer', 	self::$url . 'assets/js/modernizer.js', [ 'codevz-plugin' ], self::$ver, true );

		wp_register_script( 'cz_text_marquee', 		self::$url . 'wpbakery/assets/js/text_marquee.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_working_hours', 	self::$url . 'wpbakery/assets/js/working_hours.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_before_after', 		self::$url . 'wpbakery/assets/js/before_after.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_tabs', 				self::$url . 'wpbakery/assets/js/tabs.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_show_more_less', 	self::$url . 'wpbakery/assets/js/show_more_less.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_counter', 			self::$url . 'wpbakery/assets/js/counter.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_progress_bar', 		self::$url . 'wpbakery/assets/js/progress_bar.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_accordion', 		self::$url . 'wpbakery/assets/js/accordion.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_google_map', 		self::$url . 'wpbakery/assets/js/google_map.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_image_hover_zoom', 	self::$url . 'wpbakery/assets/js/image_hover_zoom.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_content_box', 		self::$url . 'wpbakery/assets/js/content_box.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_countdown', 		self::$url . 'wpbakery/assets/js/countdown.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_video_popup', 		self::$url . 'wpbakery/assets/js/video_popup.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_team', 				self::$url . 'wpbakery/assets/js/team.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_login_register', 	self::$url . 'wpbakery/assets/js/login_register.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_separator', 		self::$url . 'wpbakery/assets/js/separator.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_popup', 			self::$url . 'wpbakery/assets/js/popup.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_free_position_element', self::$url . 'wpbakery/assets/js/free_position_element.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_360_degree', 		self::$url . 'wpbakery/assets/js/360_degree.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_animated_text', 	self::$url . 'wpbakery/assets/js/animated_text.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_particles', 		self::$url . 'wpbakery/assets/js/particles.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_carousel', 			self::$url . 'wpbakery/assets/js/carousel.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_gallery', 			self::$url . 'wpbakery/assets/js/gallery.js', [ 'codevz-plugin' ], self::$ver, true );
		wp_register_script( 'cz_image', 			self::$url . 'wpbakery/assets/js/image.js', [ 'codevz-plugin' ], self::$ver, true );

		// Elements styles.
		wp_register_style( 'cz_button', 			self::$url . 'wpbakery/assets/css/button.css', [], self::$ver );
		wp_register_style( 'cz_button_rtl', 		self::$url . 'wpbakery/assets/css/button.rtl.css', [], self::$ver );
		wp_register_style( 'cz_testimonials', 		self::$url . 'wpbakery/assets/css/testimonials.css', [], self::$ver );
		wp_register_style( 'cz_testimonials_rtl', 	self::$url . 'wpbakery/assets/css/testimonials.rtl.css', [], self::$ver );
		wp_register_style( 'cz_progress_bar', 		self::$url . 'wpbakery/assets/css/progress_bar.css', [], self::$ver );
		wp_register_style( 'cz_progress_bar_rtl', 	self::$url . 'wpbakery/assets/css/progress_bar.rtl.css', [], self::$ver );
		wp_register_style( 'cz_working_hours', 		self::$url . 'wpbakery/assets/css/working_hours.css', [], self::$ver );
		wp_register_style( 'cz_tabs', 				self::$url . 'wpbakery/assets/css/tabs.css', [], self::$ver );
		wp_register_style( 'cz_tabs_rtl', 			self::$url . 'wpbakery/assets/css/tabs.rtl.css', [], self::$ver );
		wp_register_style( 'cz_team', 				self::$url . 'wpbakery/assets/css/team.css', [], self::$ver );
		wp_register_style( 'cz_before_after', 		self::$url . 'wpbakery/assets/css/before_after.css', [], self::$ver );
		wp_register_style( 'cz_counter', 			self::$url . 'wpbakery/assets/css/counter.css', [], self::$ver );
		wp_register_style( 'cz_countdown', 			self::$url . 'wpbakery/assets/css/countdown.css', [], self::$ver );
		wp_register_style( 'cz_video_popup', 		self::$url . 'wpbakery/assets/css/video_popup.css', [], self::$ver );
		wp_register_style( 'cz_hotspot', 			self::$url . 'wpbakery/assets/css/hotspot.css', [], self::$ver );
		wp_register_style( 'cz_process_road', 		self::$url . 'wpbakery/assets/css/process_road.css', [], self::$ver );
		wp_register_style( 'cz_attribute_box', 		self::$url . 'wpbakery/assets/css/attribute_box.css', [], self::$ver );
		wp_register_style( 'cz_banner_group', 		self::$url . 'wpbakery/assets/css/banner_group.css', [], self::$ver );
		wp_register_style( 'cz_banner', 			self::$url . 'wpbakery/assets/css/banner.css', [], self::$ver );
		wp_register_style( 'cz_banner_rtl', 		self::$url . 'wpbakery/assets/css/banner.rtl.css', [], self::$ver );
		wp_register_style( 'cz_timeline', 			self::$url . 'wpbakery/assets/css/timeline.css', [], self::$ver );
		wp_register_style( 'cz_2_buttons', 			self::$url . 'wpbakery/assets/css/2_buttons.css', [], self::$ver );
		wp_register_style( 'cz_360_degree', 		self::$url . 'wpbakery/assets/css/360_degree.css', [], self::$ver );
		wp_register_style( 'cz_quote', 				self::$url . 'wpbakery/assets/css/quote.css', [], self::$ver );
		wp_register_style( 'cz_quote_rtl', 			self::$url . 'wpbakery/assets/css/quote.rtl.css', [], self::$ver );
		wp_register_style( 'cz_title', 				self::$url . 'wpbakery/assets/css/title.css', [], self::$ver );
		wp_register_style( 'cz_title_rtl', 			self::$url . 'wpbakery/assets/css/title.rtl.css', [], self::$ver );
		wp_register_style( 'cz_svg', 				self::$url . 'wpbakery/assets/css/svg.css', [], self::$ver );
		wp_register_style( 'cz_gradient_title', 	self::$url . 'wpbakery/assets/css/gradient_title.css', [], self::$ver );
		wp_register_style( 'cz_show_more_less', 	self::$url . 'wpbakery/assets/css/show_more_less.css', [], self::$ver );
		wp_register_style( 'cz_news_ticker', 		self::$url . 'wpbakery/assets/css/news_ticker.css', [], self::$ver );
		wp_register_style( 'cz_animated_text', 		self::$url . 'wpbakery/assets/css/animated_text.css', [], self::$ver );
		wp_register_style( 'cz_free_line', 			self::$url . 'wpbakery/assets/css/free_line.css', [], self::$ver );
		wp_register_style( 'cz_free_position_element', self::$url . 'wpbakery/assets/css/free_position_element.css', [], self::$ver );
		wp_register_style( 'cz_music_player', 		self::$url . 'wpbakery/assets/css/music_player.css', [], self::$ver );
		wp_register_style( 'cz_subscribe', 			self::$url . 'wpbakery/assets/css/subscribe.css', [], self::$ver );
		wp_register_style( 'cz_image_hover_zoom', 	self::$url . 'wpbakery/assets/css/image_hover_zoom.css', [], self::$ver );
		wp_register_style( 'cz_google_map', 		self::$url . 'wpbakery/assets/css/google_map.css', [], self::$ver );
		wp_register_style( 'cz_login_register', 	self::$url . 'wpbakery/assets/css/login_register.css', [], self::$ver );
		wp_register_style( 'cz_separator', 			self::$url . 'wpbakery/assets/css/separator.css', [], self::$ver );
		wp_register_style( 'cz_popup', 				self::$url . 'wpbakery/assets/css/popup.css', [], self::$ver );
		wp_register_style( 'cz_service_box', 		self::$url . 'wpbakery/assets/css/service_box.css', [], self::$ver );
		wp_register_style( 'cz_service_box_rtl', 	self::$url . 'wpbakery/assets/css/service_box.rtl.css', [], self::$ver );
		wp_register_style( 'cz_history_line', 		self::$url . 'wpbakery/assets/css/history_line.css', [], self::$ver );
		wp_register_style( 'cz_history_line_rtl', 	self::$url . 'wpbakery/assets/css/history_line.rtl.css', [], self::$ver );
		wp_register_style( 'cz_process_line_vertical', self::$url . 'wpbakery/assets/css/process_line_vertical.css', [], self::$ver );
		wp_register_style( 'cz_stylish_list', 		self::$url . 'wpbakery/assets/css/stylish_list.css', [], self::$ver );
		wp_register_style( 'cz_carousel', 			self::$url . 'wpbakery/assets/css/carousel.css', [], self::$ver );
		wp_register_style( 'cz_particles', 			self::$url . 'wpbakery/assets/css/particles.css', [], self::$ver );
		wp_register_style( 'cz_accordion', 			self::$url . 'wpbakery/assets/css/accordion.css', [], self::$ver );
		wp_register_style( 'cz_accordion_rtl', 		self::$url . 'wpbakery/assets/css/accordion.rtl.css', [], self::$ver );
		wp_register_style( 'cz_text_marquee', 		self::$url . 'wpbakery/assets/css/text_marquee.css', [], self::$ver );
		wp_register_style( 'cz_content_box', 		self::$url . 'wpbakery/assets/css/content_box.css', [], self::$ver );
		wp_register_style( 'cz_gallery', 			self::$url . 'wpbakery/assets/css/gallery.css', [], self::$ver );
		wp_register_style( 'cz_gallery_rtl', 		self::$url . 'wpbakery/assets/css/gallery.rtl.css', [], self::$ver );
		wp_register_style( 'cz_logo', 				self::$url . 'wpbakery/assets/css/logo.css', [], self::$ver );

		// Cookie.
		if ( empty( $_COOKIE[ 'xtra_cookie' ] ) ) {

			if ( self::option( 'cookie', ( ! empty( $GLOBALS[ 'xtra_cookie' ] ) ? $GLOBALS[ 'xtra_cookie' ] : '' ) ) ) {
				wp_enqueue_style( 'cz_button' );
				wp_enqueue_style( 'xtra-cookie', 	self::$url . 'assets/css/cookie.css', [], self::$ver );
				wp_enqueue_script( 'xtra-cookie', 	self::$url . 'assets/js/cookie.js', [ 'codevz-plugin' ], self::$ver, true );
			}

		}

		// Plugin CSS.
		wp_enqueue_style( 'codevz-plugin', self::$url . 'assets/css/codevzplus.css', [], self::$ver );

		if ( ! self::option( 'disable_responsive' ) ) {

			wp_enqueue_style( 'codevz-plugin-tablet', self::$url . 'assets/css/codevzplus-tablet.css', [ 'codevz-plugin' ], self::$ver, 'screen and (max-width: 768px)' );
			wp_enqueue_style( 'codevz-plugin-mobile', self::$url . 'assets/css/codevzplus-mobile.css', [ 'codevz-plugin' ], self::$ver, 'screen and (max-width: 480px)' );

		}

		// Custom JS
		$js = self::option( 'js' );
		if ( $js ) {
			wp_add_inline_script( 'codevz-plugin', 'jQuery( function( $ ) {' . $js . '});' );
		}

	}

	public static function wp_enqueue_scripts_page_builder( $css = '' ) {

		// Edit preview elements.
		if ( is_customize_preview() ) {
			$css .= '.customize-partial-edit-shortcut button,.customize-partial-edit-shortcut button:hover, .widget .customize-partial-edit-shortcut button {color: #fff !important;border-color: #fff !important;background-color: #434343 !important}i.xtra-section-focus {display: none;color: #fff !important;top: -14px !important;left: -14px !important;width: 1em !important;height: 1em !important;padding: 7px !important;font-size: 12px !important;line-height: 1em !important;border: 2px solid #fff !important;background-color: #434343 !important;border-radius: 100px !important;outline: 0 !important;position: absolute;z-index: 999999999;cursor: pointer;box-sizing: content-box;transition: all .2s ease-in-out;box-shadow: 0 2px 1px rgba(46,68,83,.15)}i.xtra-section-focus:hover {color: #ffbb00 !important;border-color: #ffbb00 !important}.rtl i.xtra-section-focus {left: auto !important;right: -14px !important}i.xtra-section-focus-second {left: 20px !important}.rtl i.xtra-section-focus-second {left: auto !important;right: 20px !important}.page_footer > i.xtra-section-focus {margin: 20px}.customize-partial-edit-shortcuts-shown i:hover > i.xtra-section-focus,.customize-partial-edit-shortcuts-shown footer:hover > i.xtra-section-focus,.customize-partial-edit-shortcuts-shown div:hover > i.xtra-section-focus {display: block}.sidebar_offcanvas_area i.xtra-section-focus {margin: 18px;display: block}.customize-partial-edit-shortcut, .widget .customize-partial-edit-shortcut {margin: -15px}.sidebar_offcanvas_area .widget .customize-partial-edit-shortcut {display: none}.customize-partial-edit-shortcuts-shown i.backtotop, .customize-partial-edit-shortcuts-shown i.fixed_contact {overflow: visible}';

		// Admin style.
		} else if ( is_user_logged_in() ) {
			$css .= '#wp-admin-bar-codevz_menu > a.ab-item{color: #00cbff !important}#wp-admin-bar-codevz_menu_maintenance > a{color: #ff6262 !important}#wpadminbar a img{display: inline-block}';
		}

		$post_id = isset( self::$post->ID ) ? self::$post->ID : get_the_id();

		if ( $post_id && ! self::$vc_editable ) {

			// Page builder styles.
			$styles = get_post_meta( $post_id, 'cz_sc_styles', 1 );

			// Empty styles, Regenerate.
			if ( ! $styles && is_page() ) {

				$content = get_post_field( 'post_content', $post_id );

				if ( self::contains( $content, 'sk_' ) ) {
				
					// Regenrate dynamic styles.
					self::$vc_editable = true;
					self::save_post( $post_id );
					
					$styles = get_post_meta( $post_id, 'cz_sc_styles', 1 );

				}

			}

			// Responsive page builder tablet styles
			$tablet = get_post_meta( $post_id, 'cz_sc_styles_tablet', 1 );
			if ( $tablet && ! self::option( 'disable_responsive' ) ) {
				if ( self::contains( $tablet, '@media' ) ) {
					$styles .= $tablet;
				} else {
					$styles .= '@media screen and (max-width:' . self::option( 'tablet_breakpoint', '768px' ) . '){' . $tablet . '}';
				}
			}

			// Responsive page builder mobile styles
			$mobile = get_post_meta( $post_id, 'cz_sc_styles_mobile', 1 );
			if ( $mobile && ! self::option( 'disable_responsive' ) ) {
				if ( self::contains( $mobile, '@media' ) ) {
					$styles .= $mobile;
				} else {
					$styles .= '@media screen and (max-width:' . self::option( 'mobile_breakpoint', '480px' ) . '){' . $mobile . '}';
				}
			}

			$css .= $styles;

		}

		wp_add_inline_style( 'codevz-plugin', $css );

	}

	/**
	 *
	 * Custom JS/CSS for VC popup box
	 * 
	 * @return string
	 * 
	 */
	public static function vc_edit_form_fields_after_render( $output = '' ) {

		$echo = $output ? false : true;

		$body_font = self::option( '_css_body_typo' );
		
		$body_font = empty( $body_font[0]['font-family'] ) ? '' : $body_font[0]['font-family'];
		
		$body_font = explode( ':', $body_font );
		
		ob_start(); ?>

		<script type="text/javascript">

			jQuery( function( $ ) {

				$( '.wpb_edit_form_elements' ).csf_reload_script();

				$( '.vc_param_group-list' ).on( 'click', function() {
					var en = $( this );
					setTimeout(function() {
						$( '.vc_param', en ).each(function() {
							$( this ).csf_reload_script();
						});
					}, 4000 );
				});

				setTimeout(function() {

					$( '#wpb_tinymce_content_ifr' ).contents().find( 'body' ).css({
						'background': 'rgba(167, 167, 167, 0.25)',
						'font-family': '<?php echo empty( $body_font[0] ) ? 'Open Sans' : $body_font[0]; ?>'
					});

					<?php 

						$disable = array_flip( (array) self::option( 'disable' ) );

						if ( ! isset( $disable['videos'] ) ) {
					?>

					// Elements video turoials
					var el = $( '[data-vc-shortcode]' ).attr( 'data-vc-shortcode' );
					var videos = {
						cz_2_buttons: 'FFCoaubH34M',
						cz_360_degree: 'AQTj8-bSHnI',
						cz_accordion:'VYzFWA_4iCM',
						cz_animated_text:'qbclDC43uS8',
						cz_banner:'l3ee8IIXbzA',
						cz_before_after:'cQCRTkNsB9I',
						cz_button:'TWkG6HtdSoo',
						cz_carousel:'R_iFLdOv2E8',
						cz_contact_form_7:'eIZa-QfOPWo',
						cz_content_box:'t26HZ_9tJ2c',
						cz_countdown:'R20yLL03jQI',
						cz_counter:'I9-Rjkygpmw',
						cz_free_line:'B3PyMvibmvA',
						cz_free_position_element:'0js4hNd-kh8',
						cz_gallery:'j5tD0NRSw7g',
						cz_gap:'s4M2nD2Pq9M',
						cz_gradient_title:'S5fzvQ3wO0g',
						cz_history_line:'n-p0416Qtnw',
						cz_hotspot:'QDPdMrVP0WA',
						cz_image:'Tw8SfSGRQdY',
						cz_image_hover_zoom:'yk05SzAovfM',
						cz_login_register:'t2K2Jp8LbHA',
						cz_music_player:'ajdB15T7Eos',
						cz_news_ticker:'wK3G2RtnAl8',
						cz_parallax_group:'MApojPfkwXk',
						cz_particles:'4Fxr4fAKYmM',
						cz_popup:'5QL5_EGEMTE',
						cz_posts:'lU0gjnueZDI',
						cz_process_line_vertical:'EE8MZbbJixw',
						cz_process_road:'eY5UM0ucfOE',
						cz_progress_bar:'XDoUabdAVn0',
						cz_quote:'nSRgDyiMm0U',
						cz_separator:'UzVfzx1w75M',
						cz_service_box:'biplj6KgTrU',
						cz_show_more_less:'4CeGd5Z-oZs',
						cz_social_icons:'kmJ82T9TISk',
						cz_stylish_list:'ANbqrPdkj1o',
						cz_svg:'aNgPan2wmHk',
						cz_tabs:'7PmbBFXMi6A',
						cz_team:'_94XN1VnYMA',
						cz_testimonials:'IeCYG7y3fUk',
						cz_timeline:'7ZPnUppKEi0',
						cz_title:'NRMXChwRxto',
						cz_video_popup:'ugEf_JIY6JY',
						cz_working_hours:'JQm3m71pTr0',
					};

					if ( videos[ el ] != 'undefined' && videos[ el ] ) {
						if ( ! $( '.cz_video_tutorial' ).length ) {
							$( '.vc_ui-dropdown-trigger' ).before( '<a class="cz_video_tutorial" target="_blank" href="https://www.youtube.com/watch?v=' + videos[ el ] + '"><i class="fa fa-play"></i> <?php esc_html_e( 'Video Tutorial', 'codevz' ); ?></a>' );
						} else {
							$( '.cz_video_tutorial' ).attr( 'href', 'https://www.youtube.com/watch?v=' + videos[ el ] );
						}
					}

					<?php } ?>

				}, 200 );

			});

		</script>

	<?php 

		$output .= ob_get_clean();

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 *
	 * Enable some features for WP Editor
	 * 
	 * @param $i is array of default WP Editor features
	 * @return array
	 * 
	 */ 
	public static function mce_buttons_2( $i ) {
		array_shift( $i );
		array_unshift( $i, 'styleselect', 'fontselect', 'fontsizeselect', 'backcolor' );

		return $i;
	}

	/**
	 *
	 * Customize some features of WP Editor
	 * 
	 * @param $i is array of default WP Editor features values
	 * @return array
	 * 
	 */
	public static function tiny_mce_before_init( $i ) {
		$i['fontsize_formats'] = '6px 7px 8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px 42px 44px 46px 48px 50px 52px 54px 56px 58px 60px 62px 64px 66px 68px 70px 72px 74px 76px 78px 80px 82px 84px 86px 88px 90px 92px 94px 96px 98px 100px 102px 104px 106px 108px 110px 120px 130px 140px 150px 160px 170px 180px 190px 200px 1em 2em 3em 4em 5em 6em 7em 8em 9em 10em 11em 12em 13em 14em 15em 16em 17em 18em 19em 20em';

		$primary_color = self::option( 'site_color', '#4e71fe' );
		$secondary_color = self::option( 'site_color_sec' );

			$colors = '"000000", "Black",
              "993300", "Burnt orange",
              "333300", "Dark olive",
              "003300", "Dark green",
              "003366", "Dark azure",
              "000080", "Navy Blue",
              "333399", "Indigo",
              "333333", "Very dark gray",
              "800000", "Maroon",
              "FF6600", "Orange",
              "808000", "Olive",
              "008000", "Green",
              "008080", "Teal",
              "0000FF", "Blue",
              "666699", "Grayish blue",
              "666666", "Gray",
              "FF0000", "Red",
              "FF9900", "Amber",
              "99CC00", "Yellow green",
              "339966", "Sea green",
              "33CCCC", "Turquoise",
              "3366FF", "Royal blue",
              "800080", "Purple",
              "AAAAAA", "Medium gray",
              "FF00FF", "Magenta",
              "FFCC00", "Gold",
              "FFFF00", "Yellow",
              "00FF00", "Lime",
              "00FFFF", "Aqua",
              "00CCFF", "Sky blue",
              "993366", "Red violet",
              "FFFFFF", "White",
              "FF99CC", "Pink",
              "FFCC99", "Peach",
              "FFFF99", "Light yellow",
              "CCFFCC", "Pale green",
              "CCFFFF", "Pale cyan"';

		$colors .= ',"' . $primary_color . '", "Primary Color"';
		$colors .= $secondary_color ? ',"' . $secondary_color . '", "Secondary Color"' : '';

		// Build colour grid default+custom colors
		$i['textcolor_map'] = '[' . str_replace( '#', '', $colors ) . ']';
		$i['textcolor_rows'] = 6;

		// Fonts for WP Editor from theme options
		$i['font_formats'] = get_option( 'codevz_wp_editor_google_fonts' );

		// New style_formats
		$style_formats = array(
			array(
				'title' => esc_html__( '100 | Thin', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'font-weight' => '100' ),
				'wrapper' => false
			),
			array(
				'title' => esc_html__( '200 | Extra Light', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'font-weight' => '200' ),
				'wrapper' => false
			),
			array(
				'title' => esc_html__( '300 | Light', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'font-weight' => '300' ),
				'wrapper' => false
			),
			array(
				'title' => esc_html__( '400 | Normal', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'font-weight' => '400' ),
				'wrapper' => false
			),
			array(
				'title' => esc_html__( '500 | Medium', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'font-weight' => '500' ),
				'wrapper' => false
			),
			array(
				'title' => esc_html__( '600 | Semi Bold', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'font-weight' => '600' ),
				'wrapper' => false
			),
			array(
				'title' => esc_html__( '700 | Bold', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'font-weight' => '700' ),
				'wrapper' => false
			),
			array(
				'title' => esc_html__( '800 | Extra Bold', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'font-weight' => '800' ),
				'wrapper' => false
			),
			array(
				'title' => esc_html__( '900 | High Bold', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'font-weight' => '900' ),
				'wrapper' => false
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 0.6',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '0.6' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 0.8',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '0.8' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1.1',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1.1' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1.2',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1.2' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1.3',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1.3' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1.4',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1.4' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1.5',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1.5' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1.6',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1.6' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1.7',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1.7' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1.8',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1.8' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 1.9',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '1.9' )
			),
			array(
				'title' => esc_html__( 'Line height', 'codevz' ) . ' 2',
				'block' => 'div',
				'wrapper' => false,
				'styles' => array( 'line-height' => '2' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' -2px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '-2px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' -1px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '-1px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 0px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '0px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 1px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '1px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 2px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '2px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 3px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '3px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 4px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '4px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 5px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '5px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 6px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '6px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 7px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '7px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 8px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '8px' )
			),
			array(
				'title' => esc_html__( 'Letter Spacing', 'codevz' ) . ' 10px',
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'letter-spacing' => '10px' )
			),
			array(
				'title' => esc_html__( 'Margin 0px', 'codevz' ),
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'margin' => '0px' )
			),
			array(
				'title' => esc_html__( 'Margin top 10px', 'codevz' ),
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'margin-top' => '10px', 'display' => 'inline-block' )
			),
			array(
				'title' => esc_html__( 'Margin top 20px', 'codevz' ),
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'margin-top' => '20px', 'display' => 'inline-block' )
			),
			array(
				'title' => esc_html__( 'Margin top 30px', 'codevz' ),
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'margin-top' => '30px', 'display' => 'inline-block' )
			),
			array(
				'title' => esc_html__( 'Margin bottom 10px', 'codevz' ),
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'margin-bottom' => '10px', 'display' => 'inline-block' )
			),
			array(
				'title' => esc_html__( 'Margin bottom 20px', 'codevz' ),
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'margin-bottom' => '20px', 'display' => 'inline-block' )
			),
			array(
				'title' => esc_html__( 'Margin bottom 30px', 'codevz' ),
				'inline' => 'span',
				'wrapper' => false,
				'styles' => array( 'margin-bottom' => '30px', 'display' => 'inline-block' )
			),
			array(
				'title'  => esc_html__( 'Highlight', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_highlight',
				'styles' => array(
					'margin' 		=> '0 2px',
					'padding' 		=> '1px 7px 2px',
					'background' 	=> 'rgba(167, 167, 167, 0.26)',
					'border-radius' => '2px',
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Border solid', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_brsolid',
				'styles' => array(
					'margin' 		=> '0 2px',
					'padding' 		=> '4px 8px 5px',
					'border' 		=> '1px solid',
					'border-radius' => '2px',
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Border dotted', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_brdotted',
				'styles' => array(
					'margin' 		=> '0 2px',
					'padding' 		=> '4px 8px 5px',
					'border' 		=> '1px dotted',
					'border-radius' => '2px',
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Border dashed', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_brdashed',
				'styles' => array(
					'margin' 		=> '0 2px',
					'padding' 		=> '4px 8px 5px',
					'border' 		=> '1px dashed',
					'border-radius' => '2px',
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Underline', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_underline',
				'styles' => array(
					'margin' 		=> '0 2px',
					'padding' 		=> '1px 0 2px',
					'border-bottom' => '1px solid'
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Underline Dashed', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_underline cz_underline_dashed',
				'styles' => array(
					'margin' 		=> '0 2px',
					'padding' 		=> '1px 0 2px',
					'border-bottom' => '1px dashed'
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Topline', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_topline',
				'styles' => array(
					'margin' 		=> '0 2px',
					'padding' 		=> '1px 0 2px',
					'border-top' 	=> '1px solid'
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Topline Dashed', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_topline cz_topline_dashed',
				'styles' => array(
					'margin' 		=> '0 2px',
					'padding' 		=> '1px 0 2px',
					'border-top' 	=> '1px dashed'
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Blockquote Center', 'codevz' ),
				'inline' => 'span',
				'classes' => 'blockquote',
				'styles' => array(
					'width' 		=> '75%',
					'margin' 		=> '0 auto',
					'display' 		=> 'table',
					'text-align' 	=> 'center',
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Blockquote Left', 'codevz' ),
				'inline' => 'span',
				'classes' => 'blockquote',
				'styles' => array(
					'float' 		=> 'left',
					'width' 		=> '40%',
					'margin' 		=> '0 20px 20px 0',
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Blockquote Right', 'codevz' ),
				'inline' => 'span',
				'classes' => 'blockquote',
				'styles' => array(
					'float' 		=> 'right',
					'width' 		=> '40%',
					'margin' 		=> '0 0 20px 20px',
				),
				'wrapper' => false
			),	
			array(
				'title'  => esc_html__( 'Float Right', 'codevz' ),
				'inline' => 'span',
				'styles' => array( 'float' => 'right' ),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Dropcap', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_dropcap',
				'styles' => array(
					'float' 		=> self::$is_rtl ? 'right' : 'left',
					'margin' 		=> self::$is_rtl ? '5px 0 0 12px' : '5px 12px 0 0',
					'width' 		=> '2em',
					'height' 		=> '2em',
					'line-height' 	=> '2em',
					'text-align' 	=> 'center',
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Dropcap Border', 'codevz' ),
				'inline' => 'span',
				'classes' => 'cz_dropcap',
				'styles' => array(
					'float' 		=> self::$is_rtl ? 'right' : 'left',
					'margin' 		=> self::$is_rtl ? '5px 0 0 12px' : '5px 12px 0 0',
					'width' 		=> '2em',
					'height' 		=> '2em',
					'line-height' 	=> '2em',
					'text-align' 	=> 'center',
					'border' 		=> '2px solid',
				),
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Sup', 'codevz' ),
				'inline' => 'sup',
				'styles' => [],
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Sub', 'codevz' ),
				'inline' => 'sub',
				'styles' => [],
				'wrapper' => false
			),
			array(
				'title'  => esc_html__( 'Small', 'codevz' ),
				'inline' => 'small',
				'styles' => [],
				'wrapper' => false
			),
		);
		$i['style_formats'] = json_encode( $style_formats );

		return $i;
	}

	/**
	 *
	 * Filter for moving animation param into new tab Advanced
	 * 
	 * @param $i is default css_animation settings
	 * @return array
	 * 
	 */
	public static function vc_map_add_css_animation( $i ) {
		$i['group'] = esc_html__( 'Advanced', 'codevz' );
		return $i;
	}

	/**
	 *
	 * Useful shortcodes
	 * 
	 * @return string
	 * 
	 */
	public function br( $a, $c = '' ) {
		return '<br class="clr" />';
	}

	public function shortcode_get_current_year( $a, $c = '' ) {
		return current_time( 'Y' );
	}

	public function shortcode_google_font( $a, $c = '' ) {

		if ( isset( $a['font'] ) ) {

			self::load_font( do_shortcode( wp_kses_post( $a['font'] ) ) );

		}

	}

	public function shortcode_translate( $a, $c = '' ) {
		if ( isset( $a['lang'] ) ) {

			$lang = get_locale();

			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				$lang = ICL_LANGUAGE_CODE;

			} else if ( function_exists( 'pll_current_language' ) ) {
				$lang = pll_current_language();

			} else if ( class_exists( 'qtrans_getSortedLanguages' ) ) {
				global $q_config;
				$lang = isset( $q_config['language'] ) ? $q_config['language'] : $lang;
			}

			if ( self::contains( $lang, $a['lang'] ) ) {
				return $c;
			}
		}
	}

	/**
	 *
	 * Add loop animations to vc animations list
	 * 
	 * @return string
	 * 
	 */
	public static function vc_param_animation_style_list( $i ) {
		return wp_parse_args( array(
			array(
				'label' => esc_html__( 'Loop Animations', 'codevz' ),
				'values' => array(
					esc_html__( 'Fast Spinner', 'codevz' ) => array(
						'value' => 'cz_loop_spinner',
						'type' => 'in',
					),
					esc_html__( 'Normal Spinner', 'codevz' ) => array(
						'value' => 'cz_loop_spinner_normal',
						'type' => 'in',
					),
					esc_html__( 'Slow Spinner', 'codevz' ) => array(
						'value' => 'cz_loop_spinner_slow',
						'type' => 'in',
					),
					esc_html__( 'Pulse', 'codevz' ) => array(
						'value' => 'cz_loop_pulse',
						'type' => 'in',
					),
					esc_html__( 'Tada', 'codevz' ) => array(
						'value' => 'cz_loop_tada',
						'type' => 'in',
					),
					esc_html__( 'Flash', 'codevz' ) => array(
						'value' => 'cz_loop_flash',
						'type' => 'in',
					),
					esc_html__( 'Swing', 'codevz' ) => array(
						'value' => 'cz_loop_swing',
						'type' => 'in',
					),
					esc_html__( 'Jello', 'codevz' ) => array(
						'value' => 'cz_loop_jello',
						'type' => 'in',
					),
					esc_html__( 'Animation 1', 'codevz' ) => array(
						'value' => 'cz_infinite_anim_1',
						'type' => 'in',
					),
					esc_html__( 'Animation 2', 'codevz' ) => array(
						'value' => 'cz_infinite_anim_2',
						'type' => 'in',
					),
					esc_html__( 'Animation 3', 'codevz' ) => array(
						'value' => 'cz_infinite_anim_3',
						'type' => 'in',
					),
					esc_html__( 'Animation 4', 'codevz' ) => array(
						'value' => 'cz_infinite_anim_4',
						'type' => 'in',
					),
					esc_html__( 'Animation 5', 'codevz' ) => array(
						'value' => 'cz_infinite_anim_5',
						'type' => 'in',
					),
				),
			),
			array(
				'label' => esc_html__( 'Block Reveal', 'codevz' ),
				'values' => array(
					esc_html__( 'Right', 'codevz' ) => array(
						'value' => 'cz_brfx_right',
						'type' => 'in',
					),
					esc_html__( 'Left', 'codevz' ) => array(
						'value' => 'cz_brfx_left',
						'type' => 'in',
					),
					esc_html__( 'Up', 'codevz' ) => array(
						'value' => 'cz_brfx_up',
						'type' => 'in',
					),
					esc_html__( 'Down', 'codevz' ) => array(
						'value' => 'cz_brfx_down',
						'type' => 'in',
					),
				),
			),
		), $i );
	}

	/**
	 * Required for admin
	 * 
	 * @return string
	 */
	public static function admin_enqueue_scripts() {

		wp_add_inline_script( 'iris', 'var xtraColor = window.Color;' );

		wp_add_inline_script( 'csf', 'var codevz_primary_color = "' . self::option( 'site_color', '#4e71fe' ) . '", codevz_secondary_color = "' . self::option( 'site_color_sec' ) . '";', 'before' );

	}

	/**
	 * Add/Remove custom sidebar
	 * 
	 * @return string
	 */
	public static function custom_sidebars() {

		if ( ! empty( $_GET['sidebar_name'] ) ) {

			$name 		= sanitize_title_with_dashes( $_GET['sidebar_name'] );
			$options 	= get_option( 'codevz_theme_options' );
			$sidebars 	= is_array( $options['custom_sidebars'] ) ? $options['custom_sidebars'] : [];

			if ( isset( $_GET['add_sidebar'] ) ) {

				$sidebars[] = 'cz-custom-' . $name;
				$options['custom_sidebars'] = $sidebars;
				update_option( 'codevz_theme_options', $options );

				echo 'done';
			
			} else if ( isset( $_GET['remove_sidebar'] ) ) {

				foreach ( $sidebars as $key => $sidebar ) {
					if ( $sidebar == $name ) {
						unset( $sidebars[ $key ] );
					}
				}

				$options['custom_sidebars'] = $sidebars;
				update_option( 'codevz_theme_options', $options );

				echo 'done';
			}
		}

		wp_die();
	}

	/**
	 * Generates unique ID
	 * 
	 * @return string
	 */
	public static function uniqid( $prefix = 'cz' ) {
		return $prefix . rand( 1111, 9999 );
	}

	/**
	 * Check if string contains specific value(s)
	 * 
	 * @return string
	 */
	public static function contains( $v = '', $a = [] ) {
		if ( $v ) {
			foreach ( (array) $a as $k ) {
				if ( $k && strpos( (string) $v, (string) $k ) !== false ) {
					return 1;
					break;
				}
			}
		}
		
		return null;
	}

	/**
	 * Shortcode output
	 * 
	 * @param $atts, content and live js functions names
	 * @return string|null
	 */
	public static function _out( $a, $c = '', $s = '', $enqueue = '', $enqueue_extra = '' ) {

		// Element assets.
		wp_enqueue_style( $enqueue );
		wp_enqueue_script( $enqueue );

		if ( $enqueue_extra ) {
			wp_enqueue_style( $enqueue_extra );
			wp_enqueue_script( $enqueue_extra );
		}

		if ( self::$is_rtl ) {
			wp_enqueue_style( $enqueue . '_rtl' );
			wp_enqueue_style( $enqueue_extra . '_rtl' );
		}

		$m = $p = $o = '';

		// Parallax
		$ph = empty( $a['parallax_h'] ) ? '' : $a['parallax_h'];
		$pp = empty( $a['parallax'] ) ? '' : $a['parallax'];
		$pp .= empty( $a['parallax_stop'] ) ? '' : ' cz_parallax_stop';

		if ( ! empty( $a['mparallax'] ) && self::contains( $ph, 'mouse' ) ) {
			$m = '<div class="cz_mparallax_' . $a['mparallax'] . '">';
		}

		if ( $pp ) {

			$d = ( $ph == 'true' || $ph === 'truemouse' ) ? 'h' : 'v';
			$p = '<div class="clr cz_parallax_' . $d . '_' . $pp . '">';

			wp_enqueue_style( 'cz_parallax' );
			wp_enqueue_script( 'cz_parallax' );

			$o .= 'Codevz_Plus.parallax();';

		}

		// Front-end JS.
		if ( self::$vc_editable ) {

			$c .= '<script type="text/javascript">if( typeof Codevz_Plus !== "undefined" ) {';

			$c .= 'setTimeout( function() {';

			foreach ( (array) $s as $v ) {
				$c .= self::contains( $v, ')' ) ? 'Codevz_Plus.' . $v . ';' : ( $v ? 'if( typeof Codevz_Plus.' . $v . ' !== "undefined" ) {Codevz_Plus.' . $v . '();}' : '' );
			}

			$c .= $o . 'jQuery( window ).trigger( "scroll.codevz" ).trigger( "scroll.lazyload" );';

			$c .= '}, 10 );';

			$c .= '}</script>';

			$p = $p ? $p : '<div class="cz_wrap clr">';
		}

		return $m . $p . $c . ( $p ? '</div>' : '' ) . ( $m ? '</div>' : '' );

	}

	/**
	 * Generate inline data style or style tag depend on define
	 * 
	 * @param CSS
	 * @return string|null
	 */
	public static function data_stlye( &$d = '', &$t = '', &$m = '' ) {
		$out = '';

		// Page builder styles
		$d = empty( $d ) ? '' : $d;

		// Page builder tablet styles
		if ( ! empty( $t ) && substr( $t, 0, 1 ) !== '@' ) {
			$t = '@media screen and (max-width:' . self::option( 'tablet_breakpoint', '768px' ) . '){' . $t . '}';
		}

		// Page builder mobile styles
		if ( ! empty( $m ) && substr( $m, 0, 1 ) !== '@' ) {
			$m = '@media screen and (max-width:' . self::option( 'mobile_breakpoint', '480px' ) . '){' . $m . '}';
		}

		if ( ! self::$is_admin && ! self::$vc_editable && ! is_customize_preview() ) {
			$out .= ( $d || $t || $m ) ? " data-cz-style='" . str_replace( "'", '"', $d . $t . $m ) . "'" : '';
		} else {
			$out .= $d ? '><style class="cz_d_css" data-noptimize>' . $d . "</style" : '';
			$out .= $t ? '><style class="cz_t_css" data-noptimize>' . $t . "</style" : '';
			$out .= $m ? '><style class="cz_m_css" data-noptimize>' . $m . "</style" : '';
		}

		return $out;
	}

	/**
	 *
	 * Generate titl data attributes for shortcode
	 * 
	 * @param $atts array
	 * @return string|null
	 * 
	 */
	public static function tilt( $atts ) {

		if ( ! empty( $atts['tilt'] ) ) {

			wp_enqueue_style( 'codevz-tilt' );
			wp_enqueue_script( 'codevz-tilt' );

			$out = ' data-tilt';
			$out .= ( $atts['glare'] != '0' ) ? ' data-tilt-maxGlare="' . $atts['glare'] . '" data-tilt-glare="true"' : '';
			$out .= ( $atts['scale'] != '1' ) ? ' data-tilt-scale="' . $atts['scale'] . '"' : '';

			return $out;

		}

	}

	/**
	 *
	 * Generate class attribute for element related to $atts
	 * 
	 * @param $atts array and classes array
	 * @return string|null
	 * 
	 */
	public static function classes( $a, $o = [], $i = 0 ) {
		$o[] = $i ? '' : ( isset( $a['class'] ) ? esc_attr( $a['class'] ) : '' );

		$hod = !empty( $a['hide_on_d'] );
		$hot = !empty( $a['hide_on_t'] );
		$hom = !empty( $a['hide_on_m'] );

		$o[] = $hod ? 'hide_on_desktop' : '';
		$o[] = $hot ? 'hide_on_tablet' : '';
		$o[] = $hom ? 'hide_on_mobile' : '';

		if ( $hod && $hot ) {
			$o[] = 'show_on_mobile';
		} else if ( $hod && $hom ) {
			$o[] = 'show_on_tablet';
		} else if ( $hot && $hom ) {
			$o[] = 'show_on_desktop';
		}

		// Check animation name
		if ( ! empty( $a['css_animation'] ) && $a['css_animation'] !== 'none' ) {
			
			// WPBakery old versions
			wp_enqueue_script( 'waypoints' );
			wp_enqueue_style( 'animate-css' );

			// WPBakery after v6.x
			wp_enqueue_script( 'vc_waypoints' );
			wp_enqueue_style( 'vc_animate-css' );

			// Classes
			$o[] = 'wpb_animate_when_almost_visible ' . $a['css_animation'];
		}

		return ' class="' . implode( ' ', array_filter( $o ) ) . '"';
	}

	/**
	 *
	 * Generate link attributes for element according to vc_build_link
	 * 
	 * @param 	$a = encoded link attributes
	 * @return 	String
	 * 
	 */
	public static function link_attrs( $a = '' ) {

		if ( $a ) {

			$params_pairs = explode( '|', $a );
			$a = array(
				'url' => '',
				'title' => '',
				'target' => '',
				'rel' => '',
			);
			if ( ! empty( $params_pairs ) ) {
				foreach ( $params_pairs as $pair ) {
					$param = preg_split( '/\:/', $pair );
					if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
						$a[ $param[0] ] = trim( rawurldecode( $param[1] ) );
					}
				}
			}

			if ( empty( $a['url'] ) ) {
				return '';
			}

			$url = ' href="' . do_shortcode( $a['url'] ) . '"';
			$rel = empty( $a['rel'] ) ? '' : ' rel="nofollow"';
			$title = empty( $a['title'] ) ? '' : ' title="' . do_shortcode( esc_attr( $a['title'] ) ) . '"';
			$target = empty( $a['target'] ) ? '' : ' target="_blank"';

			return $url . $rel . $title . $target;

		} else {

			return ' href="#"';

		}

	}

	/**
	 *
	 * Lazyload src attributes
	 * 
	 * @return string
	 *
	 */
	public static function lazyload( $c ) {

		$is_cart = ( function_exists( 'is_cart' ) && is_cart() );

		// Skip feeds, previews, mobile, done before
		if ( self::$is_admin || ( function_exists( 'is_feed' ) && is_feed() ) || is_preview() || $is_cart || ! empty( $_GET ) ) {
			return $c;
		}

		if ( self::option( 'lazyload' ) == 'true' ) {

			preg_match_all( '/<img(.*?)>/', $c, $matches, PREG_SET_ORDER, 0);
			foreach ( $matches as $key ) {
				if ( isset( $key[0] ) && ! self::contains( $key[0], [ 'data:image', 'data-thumb', 'data-bg', 'data-ww=', 'rev-slide' ] ) ) {

					$new = preg_replace( '/ src=/', ' src="data:image/svg+xml,%3Csvg%20xmlns%3D&#39;http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg&#39;%20width=&#39;_w_&#39;%20height=&#39;_h_&#39;%20viewBox%3D&#39;0%200%20_w_%20_h_&#39;%2F%3E" data-czlz data-src=', $key[0] );

					preg_match_all( '/(?<=width="|height="|width=\'|height=\')(\d*)/', $new, $matches );
					if ( isset( $matches[0][0] ) && isset( $matches[0][1] ) ) {
						$new = str_replace( array( '_w_', '_h_' ), array( $matches[0][0], $matches[0][1] ), $new );
					}

					$c = str_replace( $key[0], $new, $c );

				}
			}

			preg_match_all( '/<(\w+)(?=.*?data-vc-parallax-image)[^>]*?>/', $c, $matches, PREG_SET_ORDER, 0);
			foreach ( $matches as $key ) {
				if ( isset( $key[0] ) ) {

					$new = preg_replace( '/ data-vc-parallax-image=/', ' data-vc-parallax-image="#" data-vc-parallax-image-lazyload=', $key[0] );

					$c = str_replace( $key[0], $new, $c );

				}
			}

			return str_replace( 'srcset', 'data-srcset', str_replace( 'sizes=', 'data-sizes=', $c ) );

		} else {

			return $c;

		}
	}

	/**
	 * Custom default colors for WP Colorpicker
	 * 
	 * @return string
	 */
	public static function wp_color_palettes() {
		if ( wp_script_is( 'wp-color-picker', 'enqueued' ) ) {
	?>
		<script type="text/javascript">

			jQuery( function( $ ) {

				var primary_color = typeof codevz_primary_color == 'string' ? codevz_primary_color : '',
					secondary_color = typeof codevz_secondary_color == 'string' ? codevz_secondary_color : '',
					palettes = ['#000000','#FFFFFF','#E53935','#FF5722','#FFEB3B','#8BC34A','#3F51B5','#9C27B0',primary_color];

				if ( secondary_color ) {
					palettes.push( secondary_color );
				}

				jQuery.wp.wpColorPicker.prototype.options = {
					hide: true,
					palettes: palettes
				};

			});

		</script>
	<?php
		}
	}

	/**
	 * Set settings for post types
	 * 
	 * @var  $query current page/post query
	 * @return array
	 */
	public static function action_pre_get_posts( $query ) {

		if ( is_admin() || empty( $query ) ) {
			return $query;
		}

		$query->query[ 'post_type' ] = isset( $query->query[ 'post_type' ] ) ? $query->query[ 'post_type' ] : 'post';

		// Set new settings for post types
		$cpt = (array) get_option( 'codevz_post_types' );
		$cpt[] = 'portfolio';

		// Custom post type UI
		if ( function_exists( 'cptui_get_post_type_slugs' ) ) {
			$cptui = cptui_get_post_type_slugs();
			if ( is_array( $cptui ) ) {
				$cpt = wp_parse_args( $cptui, $cpt );
			}
		}
		
		foreach ( $cpt as $name ) {

			$ppp = self::option( 'posts_per_page_' . $name );
			$order = self::option( 'order_' . $name );
			$orderby = self::option( 'orderby_' . $name );

			$is_cpt = ( is_post_type_archive( $name ) && $query->query[ 'post_type' ] === $name );

			// Tax
			$is_tax = false;

			if ( ! empty( $query->tax_query->queries[0]['taxonomy'] ) ) {

				$taxonomy = $query->tax_query->queries[0]['taxonomy'];

				if ( isset( $query->query[ $taxonomy ] ) && self::contains( $taxonomy, $name ) ) {
					$is_tax = true;
				}
			}

			if ( ! is_admin() && ( $is_cpt || $is_tax ) ) {

				if ( $ppp ) {
					$query->set( 'posts_per_page', $ppp );
				}

				if ( $order ) {
					$query->set( 'order', $order );
				}

				if ( $orderby ) {
					$query->set( 'orderby', $orderby );
				}
				
			}
		}

		// Search
		if ( $query->is_main_query() && $query->is_search() ) {

			$search_cpt = self::option( 'search_cpt' );

			if ( $search_cpt ) {
				$query->set( 'post_type', explode( ',', str_replace( ' ', '', $search_cpt ) ) );
			}

			$query->set( 'order', self::option( 'search_order' ) );
			$query->set( 'orderby', self::option( 'search_orderby' ) );
			$query->set( 'posts_per_page', self::option( 'search_count' ) );

		}

		return $query;
	}

	public static function fgc( $url = '' ) {
		return file_get_contents( $url );
	}

	/**
	 * Get image sizes.
	 * 
	 * @return string
	 */
	public static function getimagesize( $url = '' ) {

		// Skip SVG.
		if ( self::contains( $url, '.svg' ) ) {

			return false;

		}

		// Get object.
		global $wpdb;

		// Get attachment ID from database.
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ) ); 

		if ( isset( $attachment[ 0 ] ) ) {

			$meta = wp_get_attachment_metadata( $attachment[ 0 ] );

			if ( isset( $meta[ 'width' ] ) ) {

				return [ $meta[ 'width' ], $meta[ 'height' ] ];

			}

		}

		// cURL method.
		if ( function_exists( 'curl_init' ) ) {

			// Alternative way.
			// Check file exists.
			$ch = curl_init( $url );
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ( $retcode == 200 ) {

				// Get remote image.
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $url );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
				$data = curl_exec( $ch );
				curl_close( $ch );

				if ( $data && function_exists( 'imagecreatefromstring' ) && function_exists( 'imagesx' ) && function_exists( 'imagesy' ) && function_exists( 'imagedestroy' ) ) {

					// Process image.
					$image = imagecreatefromstring( $data );
					$dims = [ imagesx( $image ), imagesy( $image ) ];

					imagedestroy( $image );

					return $dims;

				}

			}

		}

		return false;

	}

	/**
	 * Maintenance mode redirect
	 * 
	 * @return string
	 */
	public static function maintenance_mode( $i ) {

		// Get option.
		$mt = self::option( 'maintenance_mode' );

		// Simple.
		if ( $mt === 'simple' && ! is_user_logged_in() ) {

			wp_die( self::option( 'maintenance_message', esc_html__( 'We are under maintenance mode, We will back soon.', 'codevz' ) ) );

		// Custom page.
		} else if ( $mt && $mt !== 'none' ) {

			$mt = get_page_by_title( $mt, 'object', 'page' );

			if ( ! is_user_logged_in() && ! is_page( $mt ) ) {

				wp_redirect( get_the_permalink( $mt ) );
				exit;

			}

		}

		return $i;
	}

	/**
	 * Ajax search process
	 * 
	 * @return string
	 */
	public static function ajax_search() {

		check_ajax_referer( 'ajax_search_nonce', 'nonce' );

		$l = empty( $_GET['posts_per_page'] ) ? 4 : (int) esc_attr( $_GET['posts_per_page'] );
		$s = sanitize_text_field( $_GET['s'] );
		$c = empty( $_GET['post_type'] ) ? [ 'any' ] : explode( ',', str_replace( ' ', '', esc_attr( $_GET['post_type'] ) ) );
		$o = empty( $_GET['orderby'] ) ? '' : esc_attr( $_GET['orderby'] );
		$ob = empty( $_GET['orderby'] ) ? '' : esc_attr( $_GET['orderby'] );
		$nt = empty( $_GET['no_thumbnail'] ) ? 0 : 1;
		$pi = empty( $_GET['search_post_icon'] ) ? 0 : esc_attr( $_GET['search_post_icon'] );
		$pis = empty( $_GET['sk_search_post_icon'] ) ? 0 : self::sk_inline_style( esc_attr( $_GET['sk_search_post_icon'] ) );

		$posts = get_posts(
			[
				's'              => $s,
				'post_type' 	 => $c,
				'order' 		 => $o,
				'orderby' 		 => $ob,
				'posts_per_page' => $l,
				'fields'         => 'ids'
			]
		);

		if ( ! empty( $posts ) ) {

			$i = 0;
			foreach( $posts as $post_id ) {

				$post = get_post( $post_id );

				$cpt = self::get_post_type( $post_id );
				if ( $cpt === 'page' || $cpt === 'dwqa-answer' ) {
					continue;
				}

				echo '<div id="post-' . esc_attr( $post_id ) . '" class="item_small">';

				if ( has_post_thumbnail( $post_id ) && ! $nt ) {
					echo '<a class="theme_img_hover" href="' . esc_url( get_the_permalink( $post_id ) ) . '"><img src="' . esc_url( get_the_post_thumbnail_url( $post_id, 'thumbnail' ) ) . '" width="80" height="80" /></a>';
				} else if ( $pi ) {
					echo '<a class="xtra-ajax-search-post-icon" href="' . esc_url( get_the_permalink( $post_id ) ) . '" style="' . esc_html( $pis ) . '"><span class="' . esc_attr( $pi ) . '"></span></a>';
				}

				echo apply_filters( 'cz_ajax_search_instead_img', '' );
				echo '<div class="item-details">';
				echo '<h3><a href="' . esc_url( get_the_permalink( $post_id ) ) . '" rel="bookmark">' . $post->post_title . '</a></h3>';

				echo '<span class="cz_search_item_cpt mr4"><i class="fa fa-folder-o mr4"></i>' . ucwords( ( $cpt === 'dwqa-question' ) ? 'Questions' : $cpt ) . '</span><span><i class="fa fa-clock-o mr4"></i>' . esc_html( get_the_date( false, $post_id ) ) . '</span>';

				echo '</div></div>';

				$i++;
			}

			if ( $i === 0 ) {

				echo '<b class="ajax_search_error">' . esc_html( self::option( 'not_found', 'Not found!' ) ) . '</b>';
			
			} else if ( count( $posts ) >= $l ) {

				unset( $_GET['action'] );
				unset( $_GET['nonce'] );
				echo '<a class="va_results" href="' . esc_url( home_url( '/' ) ) . '?' . http_build_query( $_GET ) . '"> ... </a>';

			}

		} else {

			echo '<b class="ajax_search_error">' . esc_html( self::option( 'not_found', 'Not found!' ) ) . '</b>';

		}

		do_action( 'xtra_ajax_search_after', $s );

		wp_die();
	}

	/**
	 * Generate social icons
	 * @return string
	 */
	public static function social( $args = [], $out = '' ) {

		$social = self::option( 'social' );

		if ( is_array( $social ) ) {

			$tooltip = self::option( 'social_tooltip' );

			$classes = [];
			$classes[] = 'cz_social';
			$classes[] = empty( $args[ 'color_mode' ] ) ? self::option( 'social_color_mode' ) : $args[ 'color_mode' ];
			$classes[] = self::option( 'social_hover_fx' );
			$classes[] = empty( $args[ 'type' ] ) ? '' : 'xtra-social-type-' . esc_attr( $args[ 'type' ] );
			$classes[] = empty( $args[ 'columnar' ] ) ? '' : 'xtra-social-columnar';
			$classes[] = self::option( 'social_inline_title' ) ? 'cz_social_inline_title' : '';
			$classes[] = $tooltip;

			$out .= '<div class="' . esc_attr( implode( ' ', array_filter( $classes ) ) ) . '">';

			foreach( $social as $soci ) {
				
				$soci[ 'icon' ] = empty( $soci[ 'icon' ] ) ? '' : $soci[ 'icon' ];
				$soci[ 'link' ] = empty( $soci[ 'link' ] ) ? '' : $soci[ 'link' ];
				$soci[ 'title' ] = empty( $soci[ 'title' ] ) ? '' : $soci[ 'title' ];

				$social_link_class = 'cz-' . str_replace( self::$social_fa_upgrade, '', esc_attr( $soci['icon'] ) );

				$target = ( Codevz_Plus::contains( $soci['link'], [ $_SERVER['HTTP_HOST'], 'tel:', 'mailto:' ] ) || $soci['link'] === '#' ) ? '' : ' target="_blank" rel="noopener noreferrer nofollow"';

				$out .= do_shortcode( '<a class="' . esc_attr( $social_link_class ) . '" href="' . esc_attr( $soci['link'] ) . '" ' . ( $tooltip ? 'data-' : '' ) . 'title="' . esc_attr( $soci['title'] ) . '"' . ( $soci['title'] ? ' aria-label="' . esc_attr( $soci['title'] ) . '"' : '' ) . $target . '><i class="' . esc_attr( $soci['icon'] ) . '"></i><span>' . esc_html( $soci['title'] ) . '</span></a>' );
			
			}

			$out .= '</div>';

		}

		return $out;
	}

	/**
	 * Content box effects
	 * @return array
	 */
	public static function fx( $hover = '' ) {
		$i = array(
			esc_html__( '~ Select ~', 'codevz' ) 		=> '',
			esc_html__( 'Zoom 1', 'codevz') 		=> 'fx_zoom_0' . $hover,
			esc_html__( 'Zoom 2', 'codevz') 		=> 'fx_zoom_1' . $hover,
			esc_html__( 'Zoom 3', 'codevz') 		=> 'fx_zoom_2' . $hover,
			esc_html__( 'Move up', 'codevz') 		=> 'fx_up' . $hover,
			esc_html__( 'Move right', 'codevz') 	=> 'fx_right' . $hover,
			esc_html__( 'Move down', 'codevz') 		=> 'fx_down' . $hover,
			esc_html__( 'Move left', 'codevz') 		=> 'fx_left' . $hover,
			esc_html__( 'Border inner', 'codevz') 	=> 'fx_inner_line' . $hover,
			esc_html__( 'Grayscale', 'codevz') 		=> 'fx_grayscale' . $hover,
			esc_html__( 'Remove Grayscale', 'codevz') => 'fx_remove_grayscale' . $hover,
			esc_html__( 'Skew left', 'codevz') 		=> 'fx_skew_left' . $hover,
			esc_html__( 'Skew right', 'codevz') 	=> 'fx_skew_right' . $hover,
			esc_html__( 'Bob loop', 'codevz') 		=> 'fx_bob' . $hover,
			esc_html__( 'Low opacity', 'codevz') 	=> 'fx_opacity' . $hover,
		);

		if ( $hover ) {
			$i = array_merge( $i, array(
				esc_html__( 'Full opacity', 'codevz') 		=> 'fx_full_opacity',
				esc_html__( '360° Z', 'codevz') 			=> 'fx_z_hover',
				esc_html__( 'Bounce', 'codevz') 			=> 'fx_bounce_hover',
				esc_html__( 'Shine', 'codevz') 				=> 'fx_shine_hover',
				esc_html__( 'Grow rotate right', 'codevz') 	=> 'fx_grow_rotate_right_hover',
				esc_html__( 'Grow rotate left', 'codevz') 	=> 'fx_grow_rotate_left_hover',
				esc_html__( 'Wobble skew', 'codevz') 		=> 'fx_wobble_skew_hover',
			) );
		}

		return $i;
	}
	
	/**
	 * Get RGB numbers of HEX color
	 * @var Hex color code
	 * @return string
	 */
	public static function hex2rgba( $c = '', $o = '1' ) {
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

		return 'rgba(' . implode( ',', array( $r, $g, $b ) ) . ',' . $o . ')';
	}

	/**
	 *
	 * Enqueue google font
	 * 
	 * @return string|null
	 * 
	 */
	public static function load_font( $f = '' ) {
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
	 *
	 * SK Style + load font
	 * 
	 * @return string
	 *
	 */
	public static function sk_inline_style( $sk = '', $important = false ) {
		$sk = str_replace( 'CDVZ', '', $sk );
		
		if ( self::contains( $sk, 'font-family' ) ) {

			self::load_font( $sk );

			// Extract font + params && Fix font for CSS
			$font = $o_font = self::get_string_between( $sk, 'font-family:', ';' );
			$font = str_replace( '=', ':', $font );
			$font = str_replace( "''", "", $font );
			
			if ( self::contains( $font, ':' ) ) {

				$font = explode( ':', $font );

				if ( ! empty( $font[0] ) ) {

					if ( ! self::contains( $font[0], "'" ) ) {
						$font[0] = "'" . $font[0] . "'";
					}

					$sk = str_replace( $o_font, $font[0], $sk );

				}

			} else {

				if ( ! self::contains( $font, "'" ) ) {
					$font = "'" . $font . "'";
				}

				$sk = str_replace( $o_font, $font, $sk );

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
	 *
	 * Return full CSS with selector and fixes plus loading fonts
	 * 
	 * @return string|null
	 * 
	 */
	public static function sk_style( $atts = [], $ids = [], $device = '' ) {
		$out = $rtl = '';
		foreach ( (array) $ids as $id => $selector ) {
			$is_array = is_array( $selector );
			$val = empty( $atts[ $id . $device ] ) ? '' : str_replace( "``", '"', $atts[ $id . $device ] );

			if ( $val ) {
				$val = str_replace( 'CDVZ', '', $val );

				// RTL
				if ( self::contains( $val, 'RTL' ) ) {
					$rtl = self::get_string_between( $val, 'RTL', 'RTL' );
					$val = str_replace( array( $rtl, 'RTL' ), '', $val );
				}

				// Fix and load google font
				if ( self::contains( $val, 'font-family' ) ) {
					self::load_font( $val ); // Enqueue font

					// Extract font + params && Fix font for CSS
					$font = $o_font = self::get_string_between( $val, 'font-family:', ';' );
					$font = str_replace( '=', ':', $font );
					$font = str_replace( 'custom_', '', $font );
					$font = str_replace( "''", "", $font );

					if ( self::contains( $font, ':' ) ) {
						$font = explode( ':', $font );
						if ( ! empty( $font[0] ) ) {
							$val = str_replace( $o_font, $font[0], $val );
						}
					} else {
						$val = str_replace( $font, $font, $val );
					}
				}

				if ( $is_array ) {
					if ( ! $device ) {
						$val .= $selector[1];
					}
					$selector = $selector[0];
				}

				// SVG background layer
				if ( self::contains( $id, 'svg_bg' ) ) {
					$type = self::contains( $val, '_class_svg_type' ) ? self::get_string_between( $val, '_class_svg_type:', ';' ) : '';
					$size = ( $type === 'circle' ) ? '3' : '1';
					$size = self::contains( $val, '_class_svg_size' ) ? self::get_string_between( $val, '_class_svg_size:', ';' ) : '1';
					$color = self::contains( $val, '_class_svg_color' ) ? self::get_string_between( $val, '_class_svg_color:', ';' ) : '#222';
					$color = self::contains( $color, 'rgba' ) ? $color : self::hex2rgba( $color );

					if ( $type === 'circle' ) {
						$base = base64_encode( "<svg xmlns='http://www.w3.org/2000/svg' width='20' height='24'><circle cx='6' cy='6' r='" . $size . "' fill='none' stroke='" . $color . "' stroke-width='1' /></svg>" );
						$val .= 'background-image: url("data:image/svg+xml;base64,' . $base . '");';
					} else if ( $type === 'dots' ) {
						$base = base64_encode( "<svg xmlns='http://www.w3.org/2000/svg' width='20' height='24'><circle cx='6' cy='6' r='" . $size . "' fill='" . $color . "' /></svg>" );
						$val .= 'background-image: url("data:image/svg+xml;base64,' . $base . '");';
					} else if ( $type === 'x' ) {
						$base = base64_encode( "<svg width='24' height='24' xmlns='http://www.w3.org/2000/svg'><path d='M4.01,15.419L15.419,4.01l0.57,0.57L4.581,15.99Z' stroke='" . $color . "' stroke-width='" . $size . "'></path><path d='M15.419,15.99L4.01,4.581l0.57-.57L15.99,15.419Z' stroke='" . $color . "' stroke-width='" . $size . "'></path></svg>" );
						$val .= 'background-image: url("data:image/svg+xml;base64,' . $base . '");';
					} else if ( $type === 'line' ) {
						$base = base64_encode( "<svg width='24' height='24' xmlns='http://www.w3.org/2000/svg'><path d='M4.01,15.419L15.419,4.01l0.57,0.57L4.581,15.99Z' stroke='" . $color . "' stroke-width='" . $size . "'></path></svg>" );
						$val .= 'background-image: url("data:image/svg+xml;base64,' . $base . '");';
					}

					// Remove unwanted in css
					if ( self::contains( $val, '_class_' ) ) {
						$val = preg_replace( '/_class_[\s\S]+?;/', '', $val );
					}
				}

				// Append CSS
				$out .= $selector . '{' . $val . '}';

				// RTL
				if ( $rtl ) {
					$sp = self::contains( $selector, array( '.cz-cpt-', '.cz-page-', '.home', 'body', '.woocommerce' ) ) ? '' : ' ';
					$out .= '.rtl' . $sp . preg_replace( '/,\s+|,/', ',.rtl' . $sp, $selector ) . '{' . $rtl . '}';
				}
				$rtl = 0;

			} else if ( $is_array && ! $device && $selector[1] ) {
				$out .= $selector[0] . '{' . $selector[1] . '}';
			}
		}

		return str_replace( ';}', '}', $out );
	}

	/**
	 * Fix: Remove extra <p> and </p> from content of elements
	 * 
	 * @return string
	 */
	public static function fix_extra_p( $content = '' ) {
		return preg_replace( '/^<\/p>\n|<p>$/', '', $content );
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
	 * Get image by id or url
	 * 
	 * @var $i image ID or image url
	 * @var $s image size
	 * @var $url only return url of image
	 * @var $c custom class for image
	 * @return string
	 */
	public static function get_image( $i = '', $s = 0, $url = 0, $c = '' ) {

		if ( function_exists( 'wpb_getImageBySize' ) && ! self::contains( $i, '.' ) ) {

			$wpb_image = wpb_getImageBySize(
				[
					'attach_id' 	=> empty( $i ) ? 1 : $i,
					'thumb_size' 	=> empty( $s ) ? 'full' : $s,
					'class' 		=> $c
				]
			);

			if ( self::contains( $wpb_image['thumbnail'], 'src=""' ) ) {
				$i = wp_get_attachment_image( $i, $s );
			} else {
				$i = $wpb_image['thumbnail'];
			}

		} else if ( is_numeric( $i ) ) {

			$i = wp_get_attachment_image( $i, $s );

		}

		if ( empty( $i ) ) {
		
			$i = '<img src="' . self::$url . 'assets/img/p.svg' . '" class="xtra-placeholder ' . $c . '" width="1000" height="1000" alt="image" />';
		
		} else if( ! self::contains( $i, 'src' ) ) {
		
			$i = '<img src="' . $i . '" class="' . $c . '" width="500" height="500" alt="image" />';
		
		}

		return $url ? self::get_string_between( $i, 'src="', '"' ) : $i;
	}

	/**
	 * Get post data
	 * 
	 * @return string
	 */
	public static function get_post_data( $id, $w = 'date', $s = 0, $c = '', $ic = '', $tc = '' ) {

		$cls = $w;
		$w = self::contains( $w, ' ' ) ? substr( $w, 0, strpos( $w, ' ' ) ) : $w;

		if ( $w === 'date' || $w === 'date_1' ) {

			$date = get_the_date();
			$out = $s ? $date : '<a href="' . get_the_permalink( $id ) . '">' . $date . '</a>';

		} else if ( $w === 'cats' || $w === 'cats_1' ) {

			$cpt = get_post_type( $id );
			$tax = ( empty( $cpt ) || $cpt === 'post' ) ? 'category' : $cpt . '_cat';
			$cats = get_the_term_list( $id, $tax, '', ', ', '');
			if ( is_string( $cats ) ) {
				$out = $s ? strip_tags( $cats ) : $cats;
			}
			
		} else if ( self::contains( $w, array( 'cats_2', 'cats_3', 'cats_4', 'cats_5', 'cats_6', 'cats_7' ) ) ) {

			$out = self::get_cats( $id, $w, $s, $tc );

		} else if ( $w === 'tags' ) {

			$out = self::get_tags( $id, $s, $tc );
			
		} else if ( $w === 'author' ) {

			$author = get_the_author_meta( 'display_name', $id );
			$out = $s ? $author : '<a href="' . get_author_posts_url( $id ) . '">' . $author . '</a>';
			
		} else if ( $w === 'author_avatar' || $w === 'author_full_date' || $w === 'author_icon_date' ) {

			$author = get_the_author_meta( 'display_name', $id );
			$avatar = ( $ic && $w === 'author_icon_date' ) ? '<i class="cz_sub_icon fa ' . $ic . '"></i>' : get_avatar( $id, 30 );
			$link = get_author_posts_url( $id );

			if ( $s ) {
				$out = '<span class="cz_post_author_avatar">' . $avatar . '</span>';
				$out .= '<span class="cz_post_inner_meta">';
				$out .= '<span class="cz_post_author_name">' . $author . '</span>';
				if ( $w === 'author_full_date' || $w === 'author_icon_date' ) {
					$out .= '<span class="cz_post_date">' . get_the_date() . '</span>';
				}
				$out .= '</span>';
			} else {
				$out = '<a class="cz_post_author_avatar" href="' . $link . '">' . $avatar . '</a>';
				$out .= '<span class="cz_post_inner_meta">';
				$out .= '<a class="cz_post_author_name" href="' . $link . '">' . $author . '</a>';
				if ( $w === 'author_full_date' || $w === 'author_icon_date' ) {
					$out .= '<span class="cz_post_date">' . get_the_date() . '</span>';
				}
				$out .= '</span>';
			}

		} else if ( $w === 'price' ) {

			if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {

				global $woocommerce;

				$cx = get_woocommerce_currency_symbol();
				$price = get_post_meta( $id, '_regular_price', true );
				$sale = get_post_meta( $id, '_sale_price', true );

				$out = $sale ? '<del><span>' . $cx . '</span>' . $price . '</del> ' . '<span>' . $cx . '</span>' . $sale : '<span>' . $cx . '</span>' . $price;

			} else {
				$out = '---';
			}

		} else if ( $w === 'add_to_cart' ) {

			$out = do_shortcode( '[add_to_cart id="' . $id . '" style=""]' );

		} else if ( $w === 'comments' ) {

			$cm = number_format_i18n( get_comments_number( $id ) );
			$out = $s ? $cm . ' ' . $c : '<a href="' . get_the_permalink( $id ) . '#comments">' . $cm . ' ' . $c . '</a>';
			
		} else if ( $w === 'custom_text' ) {
			
			$out = do_shortcode( $s );
		
		} else if ( $w === 'custom_meta' ) {
			
			$out = (string) get_post_meta( $id, $s, true );
		
		}

		// Icon.
		$ic = is_array( $ic ) ? $ic[ 'value' ] : $ic;

		// Prefix
		$pre = ( $ic && ! self::contains( $w, 'author_' ) ) ? '<i class="cz_sub_icon mr8 fa ' . $ic . '"></i>' : '';
		$pre .= ( $c && $w !== 'comments' ) ? '<span class="cz_data_sub_prefix mr4">' . $c . '</span>' : '';

		// Out
		return isset( $out ) ? '<span class="cz_post_data cz_data_' . $cls . '">' . $pre . $out . '</span>' : '';

	}

	/**
	 * Get post categories include colors
	 * 
	 * @return string
	 */
	public static function get_cats( $id, $style = '', $no_link = 0, $l = 10, $out = [] ) {

		$cpt = get_post_type( $id );
		$tax = ( empty( $cpt ) || $cpt === 'post' ) ? 'category' : $cpt . '_cat';

		$terms = get_the_terms( $id, $tax );

		if ( $terms ) {

			foreach( $terms as $term ) {

				if ( isset( $term->term_id ) ) {

					$color = get_term_meta( $term->term_id, 'codevz_cat_meta', true );
					$opacity = self::contains( $style, array( '6', '7' ) ) ? '1' : '0.1';
					$color = empty( $color['color'] ) ? '' : ' style="color:' . $color['color'] . ';border-color:' . $color['color'] . ';background: ' . self::hex2rgba( $color['color'], $opacity ) . '"';
					$out[] = $no_link ? '<span' . $color . '>' . $term->name . '</span>' : '<a href="' . get_term_link( $term ) . '"' . $color . '>' . $term->name . '</a>';

				}

			}

		}

		$out = implode( '', array_slice( $out, 0, $l ) );

		return $out ? '<span class="cz_cats_2 cz_' . $style . '">' . $out . '</span>' : '';
	}

	/**
	 * Get post tags
	 * 
	 * @return string
	 */
	public static function get_tags( $id, $no_link = 0, $l = 10, $out = [] ) {
		$tax = get_object_taxonomies( get_post_type( $id ), 'objects' );

		foreach ( $tax as $tax_slug => $taks ) {
			$terms = get_the_terms( $id, $tax_slug );

			if ( ! empty( $terms ) && self::contains( $taks->label, 'Tags' ) ) {
				foreach ( $terms as $term ) {
					$out[] = $no_link ? '#' . esc_html( $term->name ) . ' ' : '<a href="' . esc_url( get_term_link( $term->slug, $tax_slug ) ) . '">#' . esc_html( $term->name ) . '</a> ';
				}
			}
		}

		$out = implode( '', array_slice( $out, 0, $l ) );

		return $out ? '<span class="cz_ptags">' . $out . '</span>' : '';
	}

	/**
	 * Limit words of string
	 * 
	 * @return string
	 */
	public static function limit_words( $string = '', $length = 12, $read_more = null ) {

		// Get read more
		$read_more_a = self::get_string_between( $string, '<a', '</a>', 1 );
		if ( isset( $read_more_a[0] ) ) {
			$read_more_a = $read_more_a[0];
			$string = str_replace( $read_more_a, '', $string );
		}
		
		$count = count( (array) preg_split( '~[^\p{L}\p{N}\']+~u', $string ) ) - 1;

		// String length
		$length--;
		if ( $count > $length ) {
			$string = strip_tags( $string );
			$string = preg_replace( '/((\w+\W*){' . $length . '}(\w+))(.*)/u', '${1}', $string ) . ' ...';
		}

		// Add read more
		if ( $read_more ) {
			$string .= $read_more_a;
		}

		// Out
		return str_replace( [ '... ', 'Array' ], '', $string );
	}

	/**
	 * Register new Post types
	 * 
	 * @return object
	 */
	public static function post_types() {

		// Other post types.
		$options 	= (array) self::option();
		$post_types = (array) get_option( 'codevz_post_types' );

		$post_types[99] = 'portfolio';

		if ( self::option( 'disable_portfolio' ) ) {
			unset( $post_types[99] );
		}

		foreach ( $post_types as $cpt ) {

			if ( empty( $cpt ) ) {
				continue;
			}

			$cpt = strtolower( str_replace( ' ', '_', $cpt ) );

			$opt = array(
				'slug' 			=> empty( $options[ 'slug_' . $cpt ] ) ? $cpt : $options[ 'slug_' . $cpt ], 
				'title' 		=> empty( $options[ 'title_' . $cpt ] ) ? ucwords( $cpt ) : $options[ 'title_' . $cpt ], 
				'cat_slug' 		=> empty( $options[ 'cat_' . $cpt ] ) ? $cpt . '/cat' : $options[ 'cat_' . $cpt ], 
				'cat_title' 	=> empty( $options[ 'cat_title_' . $cpt ] ) ? 'Categories' : $options[ 'cat_title_' . $cpt ], 
				'tags_slug' 	=> empty( $options[ 'tags_' . $cpt ] ) ? $cpt . '/tags' : $options[ 'tags_' . $cpt ], 
				'tags_title' 	=> empty( $options[ 'tags_title_' . $cpt ] ) ? 'Tags' : $options[ 'tags_title_' . $cpt ]
			);

			register_taxonomy( $cpt . '_cat', $cpt, 
				array(
					'hierarchical'		=> true,
					'labels'			=> array(
						'name'				=> $opt['cat_title'],
						'menu_name'			=> $opt['cat_title']
					),
					'show_ui'			=> true,
					'show_admin_column'	=> true,
					'show_in_rest'		=> true,
					'query_var'			=> true,
					'rewrite'			=> array( 'slug' => $opt['cat_slug'], 'with_front' => false ),
				)
			);

			register_taxonomy( $cpt . '_tags', $cpt, 
				array(
					'hierarchical'		=> false,
					'labels'			=> array(
						'name'				=> $opt['tags_title'],
						'menu_name'			=> $opt['tags_title']
					),
					'show_ui'			=> true,
					'show_admin_column'	=> true,
					'show_in_rest'		=> true,
					'query_var'			=> true,
					'rewrite'			=> array( 'slug' => $opt['tags_slug'], 'with_front' => false ),
				)
			);

			$icon = $cpt === 'portfolio' ? 'dashicons-format-gallery' : 'dashicons-admin-post';

			$cpt_label = str_replace( '_', ' ', $opt['title'] );

			register_post_type( $cpt, 
				array(
					'labels'		=> array(
						'name'			=> $cpt_label,
						'menu_name'		=> $cpt_label
					),
					'public'			=> true,
					'menu_icon'		=> $icon,
					'supports'			=> array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments', 'author', 'post-formats', 'elementor' ),
					'has_archive'		=> true,
					'show_in_rest'		=> true,
					'rewrite'			=> array( 'slug' => $opt['slug'], 'with_front' => false )
			 	)
			);
		}
	}

	/**
	 *
	 * Set short codes ID and extract styles then update post meta 'cz_sc_styles'
	 * 
	 * @return string
	 * 
	 */
	public static function save_post( $post_id = '' ) {
		if ( empty( $post_id ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		// Get content
		$content = get_post_field( 'post_content', $post_id );

		// Extract Short codes
		$shortcodes = (array) self::get_string_between( $content, '[cz_', ']', 1 );
		if ( ! empty( $shortcodes ) ) {
			$styles = $tablet = $mobile = '';
			foreach ( $shortcodes as $sc ) {
				if ( ! empty( $sc ) ) {
					$do_shortcode = do_shortcode( $sc );
					$styles .= self::get_string_between( $do_shortcode, '<style class="cz_d_css" data-noptimize>', '</style>' );
					$tablet .= self::get_string_between( $do_shortcode, '<style class="cz_t_css" data-noptimize>', '</style>' );
					$mobile .= self::get_string_between( $do_shortcode, '<style class="cz_m_css" data-noptimize>', '</style>' );
				}
			}

			// Update meta box for new styles
			delete_post_meta( $post_id, 'cz_sc_styles' );
			update_post_meta( $post_id, 'cz_sc_styles', $styles );
			if ( $tablet ) {
				delete_post_meta( $post_id, 'cz_sc_styles_tablet' );
				update_post_meta( $post_id, 'cz_sc_styles_tablet', $tablet );
			}
			if ( $mobile ) {
				delete_post_meta( $post_id, 'cz_sc_styles_mobile' );
				update_post_meta( $post_id, 'cz_sc_styles_mobile', $mobile );
			}
			
		}
	}

	/**
	 * Admin notices.
	 * 
	 * @since 3.2.0
	 */
	public function admin_notices() {

		$option_name = 'xtra_notice_dismiss_' . str_replace( '.', '', self::$ver );

		if ( get_option( $option_name ) == true ) {

			return;

		}

		if ( isset( $_GET[ 'xtra_notice_dismiss' ] ) ) {

			update_option( $option_name, true );

			return;

		}

		$theme 	= wp_get_theme();

		if ( empty( $theme->parent() ) ) {

			$name 	= $theme->get( 'Name' );
			$ver 	= $theme->get( 'Version' );

		} else {

			$name 	= $theme->parent()->Name;
			$ver 	= $theme->parent()->Version;

		}

		if ( $name === 'XTRA' && self::$ver != $ver ) {

			if ( version_compare( $ver, self::$ver, '<' ) ) {

				?>
					<div class="notice notice-warning">
						<p><?php echo esc_html__( 'New update is available for your theme', 'codevz' ) . ' <a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">' . esc_html__( 'Please click here', 'codevz' ) . '</a>'; ?></p>
					</div>
				<?php

			} else {

				?>
					<div class="notice notice-warning">
						<p><?php echo esc_html__( 'New update is available for "Codevz Plus" plugin,', 'codevz' ) . ' <a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">' . esc_html__( 'Please click here', 'codevz' ) . '</a>'; ?></p>
					</div>
				<?php

			}

		}

	}

	/**
	 * Plugin white label.
	 * 
	 * @since 3.2.0
	 */
	public static function white_label( $plugins ) {

		if ( isset( $plugins['codevz-plus/codevz-plus.php']['Name'] ) ) {

			$name 			= self::option( 'white_label_plugin_name' );
			$description 	= self::option( 'white_label_plugin_description' );
			$author 		= self::option( 'white_label_author' );
			$link 			= self::option( 'white_label_link' );

			if ( $name ) {
				$plugins['codevz-plus/codevz-plus.php']['Name'] = $name;
				$plugins['codevz-plus/codevz-plus.php']['Title'] = $name;
			}
			
			if ( $description ) {
				$plugins['codevz-plus/codevz-plus.php']['Description'] = $description;
			}
			
			if ( $author ) {
				$plugins['codevz-plus/codevz-plus.php']['Author'] = $author;
			}
			
			if ( $link ) {
				$plugins['codevz-plus/codevz-plus.php']['PluginURI'] = $link;
				$plugins['codevz-plus/codevz-plus.php']['AuthorURI'] = $link;
			}

		}

		return $plugins;
	}

} // Codevz_Plus

// Run
Codevz_Plus::instance();


/**
 * VC initial action
 * @return object
 */
function codevz_vc_before_init() {

	// Codevz Elements
	foreach( glob( Codevz_Plus::$dir . 'wpbakery/*.php' ) as $i ) {
		require_once( $i );
		$name = str_replace( '.php', '', basename( $i ) );
		$class = 'Codevz_WPBakery_' . $name;
		$new_class = new $class( 'cz_' . $name );
		$new_class->in( true );
	}

	// Elements container
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_cz_acc_child extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_accordion extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_carousel extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_content_box extends WPBakeryShortCodesContainer {}  
		class WPBakeryShortCode_cz_free_position_element extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_history_line extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_parallax extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_popup extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_process_line_vertical extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_show_more_less extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_speech_bubble extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_tab extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_tabs extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_timeline extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_timeline_item extends WPBakeryShortCodesContainer {}
		class WPBakeryShortCode_cz_particles extends WPBakeryShortCodesContainer {}
	}

	// Activate VC for post types
	$vc_cpts = (array) get_option( 'codevz_post_types' );
	$vc_cpts[] = 'page';
	$vc_cpts[] = 'post';
	$vc_cpts[] = 'portfolio';
	$vc_cpts[] = 'product';
	vc_set_default_editor_post_types( $vc_cpts );
}
add_action( 'vc_before_init', 'codevz_vc_before_init' );
