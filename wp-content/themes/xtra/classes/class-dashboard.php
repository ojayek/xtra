<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

/**
 * Theme dashboard, activtion, importer, plugins, system status, etc.
 * 
 * @since  4.3.0
 * @author https://codevz.com/
 * @link https://xtratheme.com/
 */

if ( ! class_exists( 'Xtra_Dashboard' ) ) {

	class Xtra_Dashboard {

		private static $instance = null;

		private function __construct() {

			add_action( 'init', [ $this, 'init' ] );

		}

		public function init() {

			// Check features.
			$this->disable = array_flip( (array) Codevz_Theme::option( 'disable' ) );

			// Check white label for menu.
			if ( ! isset( $this->disable['menu'] ) ) {

				// Theme info.
				$this->theme = wp_get_theme();
				$this->theme->version = empty( $this->theme->parent() ) ? $this->theme->get( 'Version' ) : $this->theme->parent()->Version;

				// IDs.
				$this->slug 	= 'xtra-activation';
				$this->option 	= 'codevz_theme_activation';

				// Admin menus.
				$this->menus 	= [

					'activation' 	=> esc_html__( 'Activation', 'xtra' ),
					'importer' 		=> esc_html__( 'Demo Importer', 'xtra' ),
					'importer_page' => esc_html__( 'Page Importer', 'xtra' ),
					'plugins' 		=> esc_html__( 'Install Plugins', 'xtra' ),
					'options' 		=> esc_html__( 'Theme Options', 'xtra' ),
					'status' 		=> esc_html__( 'System Status', 'xtra' ),
					'uninstall' 	=> esc_html__( 'Uninstall Demo', 'xtra' ),
					'feedback' 		=> esc_html__( 'Feedback', 'xtra' ),

				];

				// White label check activation.
				if ( isset( $this->disable[ 'activation' ] ) ) {

					unset( $this->menus[ 'activation' ] );

				}

				// White label check importer.
				if ( isset( $this->disable[ 'importer' ] ) ) {

					unset( $this->menus[ 'importer' ] );
					unset( $this->menus[ 'uninstall' ] );

				}

				// White label check theme options.
				if ( isset( $this->disable[ 'options' ] ) ) {

					unset( $this->menus[ 'options' ] );

				}

				// White label check videos.
				if ( isset( $this->disable[ 'videos' ] ) ) {

					unset( $this->menus[ 'videos' ] );

				}

				// Theme plugins.
				$this->plugins 	= apply_filters( 'xtra_plugins_list', [
					'codevz-plus' 	=> [
						'name' 				=> esc_html__( 'Codevz Plus', 'xtra' ),
						'source' 			=> Codevz_Theme::$api . 'codevz-plus.zip',
						'required' 			=> true,
						'class_exists' 		=> 'Codevz_Plus'
					],
					'elementor' 	=> [
						'name' 				=> esc_html__( 'Elementor Page Builder', 'xtra' ),
						'recommended' 		=> true,
						'function_exists' 	=> 'elementor_load_plugin_textdomain'
					],
					'js_composer' 	=> [
						'name' 				=> esc_html__( 'WPBakery Page Builder', 'xtra' ),
						'source' 			=> Codevz_Theme::$api . 'js_composer.zip',
						'recommended' 		=> true,
						'class_exists' 		=> 'Vc_Manager'
					],
					'revslider' 	=> [
						'name' 				=> esc_html__( 'Revolution Slider', 'xtra' ),
						'source' 			=> Codevz_Theme::$api . 'revslider.zip',
						'recommended' 		=> true,
						'function_exists' 	=> 'get_rs_plugin_url'
					],
					'woocommerce' 	=> [
						'name' 				=> esc_html__( 'Woocommerce', 'xtra' ),
						'recommended' 		=> true,
						'class_exists' 		=> 'WooCommerce'
					],
					'contact-form-7' => [
						'name' 				=> esc_html__( 'Contact Form 7', 'xtra' ),
						'recommended' 		=> true,
						'class_exists' 		=> 'WPCF7'
					],
					'autoptimize' 	=> [
						'name' 				=> esc_html__( 'Autoptimize', 'xtra' ),
						'recommended' 		=> true,
						'class_exists' 		=> 'autoptimizeExtra'
					],
					'ewww-image-optimizer' => [
						'name' 				=> esc_html__( 'EWWW Image Optimizer', 'xtra' ),
						'recommended' 		=> true,
						'function_exists' 	=> 'ewww_image_optimizer_unsupported_php'
					],
					//'wp-optimize' 	=> [
					//	'name' 				=> esc_html__( 'WP-Optimize', 'xtra' ),
					//	'recommended' 		=> true,
					//	'class_exists' 		=> 'WP_Optimize'
					//],
				] );

				// List of demos.
				$this->demos = apply_filters( 'xtra_demos_list', [

					//'xxx' 	=> [

					//	'js_composer' 	=> true,
					//	'elementor' 	=> false,
					//	'free' 			=> true,
					//	'rtl' 			=> [ 'js_composer' = true, 'elementor' = true ],
					//	'plugins' 		=> [ 'revslider' => false, 'bbpress' => true ],

					//],

					'factory-2' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'beauty-salon-2' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'music-and-band' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'nail-salon' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'perfume-shop' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'watch-shop' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'book-shop' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'flower-shop' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'architect-2' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'agency-2' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'photographer' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'elderly-care' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'investment' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'dance' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'cryptocurrency-2' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'business-5' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'construction-2' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'advisor' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'seo-2' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'portfolio' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'personal-blog-2' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'insurance' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'corporate-2' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'business-4' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'startup' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'medical' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'factory' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'furniture' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'carwash' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'rims' 				=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'jewelry' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'church' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'yoga' 				=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'moving' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'plumbing' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'travel' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'beauty-salon'      => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'home-renovation' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'creative-business' => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'mechanic'        	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'lawyer'         	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'web-agency'        => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'gardening'         => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'corporate'         => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'business-3'        => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'digital-marketing' => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'business-classic'  => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'charity'        	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'creative-studio'   => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'kids'      	    => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'smart-home'        => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'logistic'          => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'industrial'      	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'tattoo'      		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'personal-blog'    	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'cleaning'      	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'metro-blog'      	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'parallax'      	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'3d-portfolio'      => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'agency'            => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'photography3'      => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'spa'               => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'app'               => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'architect'         => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'barber'            => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'building'          => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'business'          => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'camping-adventures'=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'coffee'            => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'conference' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'business-2' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'construction' 		=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'cryptocurrency' 	=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'cv-resume'         => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'dentist'           => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'fashion-shop'      => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'fast-food'         => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'finance'           => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'game'              => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'gym'               => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'hosting'           => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'hotel' 			=> [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'interior'          => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'lawyers'           => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'logo-portfolio'    => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'music'             => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'photography'       => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'photography2'      => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'plastic-surgery'   => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'restaurant'        => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'dubai-investment'  => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'seo'               => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'single-shop'       => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true, 'elementor' => true ]

					],
					'wedding'           => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],
					'winery'            => [

						'elementor' 		=> true,
						'rtl' 				=> [ 'js_composer' => true ]

					],

				] );

				// Actions.
				add_action( 'admin_init', [ $this, 'admin_init' ] );
				add_action( 'admin_menu', [ $this, 'admin_menu' ] );

				add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );

				add_action( 'wp_ajax_xtra_wizard', [ $this, 'wizard' ] );
				add_action( 'wp_ajax_xtra_feedback', [ $this, 'feedback_submit' ] );
				add_action( 'wp_ajax_xtra_page_importer', [ $this, 'xtra_page_importer' ] );

			}

		}

		public static function instance() {

			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;

		}

		/**
		 * Redirect to dashboard after theme activated.
		 * 
		 * @return -
		 */
		public function admin_init() {

			global $pagenow;

			// Redirect after theme activation.
			if ( isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {

				wp_redirect( esc_url_raw( admin_url( 'admin.php?page=' . $this->slug ) ) );

			}

		}

		/**
		 * Load admin dashboard assets
		 * 
		 * @return -
		 */
		public function enqueue( $hook ) {

			if ( ! Codevz_Theme::contains( $hook, 'xtra' ) ) {
				return false;
			}

			// Dashboard pointers.
			if ( 1 == 2 && ! get_user_meta( get_current_user_id(), 'xtra_dashboard_pointers' ) ) {

				//if ( 'show' == $_GET['tutorial'] ) {
				//	update_user_meta( $user_id, 'not_show_tutorial', true );
				//}

				$pointers = [];

				$pointers['title'] = array(
					'target'       => ".xtra-dashboard-activation-form",
					'next'         => 'content',
					'next_trigger' => array(
						'target' => '.xtra-dashboard-header',
						'event'  => 'input',
					),
					'options'      => array(
						'content'  => '<h3>' . esc_html__( 'Title', 'xtra' ) . '</h3><p>' . esc_html__( 'XTRA', 'xtra' ) . '</p>',
						'position' => array(
							'edge'  => 'top',
							'align' => 'left',
						),
					),
				);

				$pointers['content'] = array(
					'target'       => ".xtra-dashboard-header",
					'next'         => 'content',
					'next_trigger' => array(),
					'options'      => array(
						'content'  => '<h3>' . esc_html__( 'Description', 'xtra' ) . '</h3><p>' . esc_html__( 'XTRA', 'xtra' ) . '</p>',
						'position' => array(
							'edge'  => 'bottom',
							'align' => 'middle',
						),
					),
				);

				$valid_pointers = array();

				// Check pointers and remove dismissed ones.
				foreach ( $pointers as $pointer_id => $pointer ) {

					$pointer['pointer_id'] = $pointer_id;

					$valid_pointers['pointers'][] =  $pointer;
				}

				// Add pointers style to queue.
				wp_enqueue_style( 'wp-pointer' );
				wp_enqueue_script( 'wp-pointer' );

				// Add pointer options to script.
				wp_localize_script( 'wp-pointer', 'xtraPointers', $valid_pointers );

			} // end pointers.

			// Assets.
			wp_enqueue_style( 'xtra-font', 'https://fonts.googleapis.com/css?family=Poppins:400,500,600,700' );
			wp_enqueue_style( 'xtra', Codevz_Theme::$url . 'assets/css/dashboard.css', [], $this->theme->version, 'all' );
			wp_enqueue_script( 'xtra', Codevz_Theme::$url . 'assets/js/dashboard.js', [], $this->theme->version, true );

			// RTL styles.
			if ( is_rtl() ) {
				wp_enqueue_style( 'xtra-rtl', Codevz_Theme::$url . 'assets/css/dashboard.rtl.css', [], $this->theme->version, 'all' );
			}

			$plugins = [];

			// List of inactive plugins.
			foreach( $this->plugins as $slug => $plugin ) {

				if ( ! $this->plugin_is_active( $slug ) ) {

					$plugins[ $slug ] = true;

				}

			}

			// Translations for scripts.
			wp_localize_script( 'xtra', 'xtraWizard', [

				'plugins' 			=> $plugins,
				'of' 				=> esc_html__( 'of', 'xtra' ),
				'close' 			=> esc_html__( 'Close', 'xtra' ),
				'plugin_before' 	=> esc_html__( 'Installing', 'xtra' ),
				'plugin_after' 		=> esc_html__( 'Activated', 'xtra' ),
				'import_before' 	=> esc_html__( 'Importing', 'xtra' ),
				'import_after' 		=> esc_html__( 'Imported', 'xtra' ),
				'codevz_plus' 		=> esc_html__( 'Codevz Plus', 'xtra' ),
				'js_composer' 		=> esc_html__( 'WPBakery Page Builder', 'xtra' ),
				'elementor' 		=> esc_html__( 'Elementor Page Builder', 'xtra' ),
				'revslider' 		=> esc_html__( 'Revolution Slider', 'xtra' ),
				'cf7' 				=> esc_html__( 'Contact Form 7', 'xtra' ),
				'woocommerce' 		=> esc_html__( 'WooCommerce', 'xtra' ),
				'downloading' 		=> esc_html__( 'Downloading', 'xtra' ),
				'demo_files' 		=> esc_html__( 'Demo Files', 'xtra' ),
				'downloaded' 		=> esc_html__( 'Downloaded', 'xtra' ),
				'options' 			=> esc_html__( 'Theme Options', 'xtra' ),
				'widgets' 			=> esc_html__( 'Widgets', 'xtra' ),
				'slider' 			=> esc_html__( 'Revolution Slider', 'xtra' ),
				'posts' 			=> esc_html__( 'Pages & Posts', 'xtra' ),
				'images' 			=> esc_html__( 'Images', 'xtra' ),
				'error_500' 		=> esc_html__( 'PHP error 500, Internal server error, Please check your server error log file or contact with support.', 'xtra' ),
				'error_503' 		=> esc_html__( 'PHP error 503, Internal server error, Please try again with same import demo.', 'xtra' ),
				'ajax_error' 		=> esc_html__( 'An error has occured, Please deactivate all plugins except theme plugins and try again, If still have same issue, Please submit ticket to theme author.', 'xtra' ),
				'features' 			=> esc_html__( 'Choose at least one feature to import.', 'xtra' ),
				'feedback_empty' 	=> esc_html__( 'Message box is empty, Please fill the box then submit.', 'xtra' ),
				'page_importer_empty' 	=> esc_html__( 'URL input is empty, Please fill the input then submit.', 'xtra' )
			]);

		}

		/**
		 * Add admin menus.
		 * 
		 * @return array
		 */
		public function admin_menu() {

			// Icon.
			$icon = 'data:image/svg+xml;bas'.'e6'.'4,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMTEiIGhlaWdodD0iMjEzIiB2aWV3Qm94PSIwIDAgMjExIDIxMyI+IDxkZWZzPiA8c3R5bGU+IC5jbHMtMSB7IGZpbGw6ICNmZmY7IGZpbGwtcnVsZTogZXZlbm9kZDsgfSA8L3N0eWxlPiA8L2RlZnM+IDxwYXRoIGlkPSJDb2xvcl9GaWxsXzEiIGRhdGEtbmFtZT0iQ29sb3IgRmlsbCAxIiBjbGFzcz0iY2xzLTEiIGQ9Ik01Mi41MzMsMTYuMDI4Qzg2LjUyLDE1LjIxMSwxMTMuMDQ2LDQyLjYyLDk3LjgsNzcuMTM4Yy01LjcxNSwxMi45NDQtMTkuMDU0LDIwLjQ1LTMxLjk1NiwyMy45MTMtOS40NTIsMi41MzctMTkuMjY2LTEuNzQzLTIzLjk2Ny00LjQyOC0zLjM5NC0xLjkzOS02Ljk1LTIuMDI2LTkuNzY0LTQuNDI4LTguODQ0LTcuNTUtMjAuODIxLTI2Ljk1Ni0xNC4yLTQ2LjA1NGE0OC41NjEsNDguNTYxLDAsMCwxLDIzLjA4LTI2LjU3QzQ0Ljc1NywxNy42NTMsNDkuMTkzLDE4LjIxNyw1Mi41MzMsMTYuMDI4Wm05NC4wOTQsMGMxMS45MjItLjIxLDIyLjAyMS43MywyOS4yOTMsNS4zMTQsMTQuODkxLDkuMzg2LDI4LjYwNSwzNy45NDQsMTUuMDkxLDU5LjMzOS01Ljk2LDkuNDM2LTE3LjAxMiwxNy4yNjMtMjkuMjkzLDIwLjM3SDE0MS4zYy02LjYwOSwxLjYzOC0xNS40OTUsNC45NDktMjAuNDE3LDguODU3LTEwLjI0Niw4LjEzNi0xNi4wMjgsMjAuNS0xOS41MjgsMzUuNDI2djE5LjQ4NWMtNS4wMzYsMTguMDY4LTIzLjkxNywzOC45MTEtNDkuNzEsMzIuNzY5LTQuNzI0LTEuMTI0LTExLjA1Mi0yLjc3OC0xNS4wOS01LjMxMy01LjcxNC0zLjU4OC05LjU2LTkuMzgyLTEzLjMxNS0xNS4wNTdhNDUuMTUzLDQ1LjE1MywwLDAsMS02LjIxNC0xNC4xN2MtMS45LTcuODkzLjQ5NC0xNS4zNjgsMi42NjMtMjEuMjU2LDMuOTM5LTEwLjY5Myw5LjgyMi0yMC4yOTEsMTkuNTI5LTI0LjgsOC4zNTctMy44ODEsMTguMTcyLTIuNDgxLDI4LjQwNi01LjMxNCwxMi40NjYtMy40NTEsMjUuOTctMTAuMjYzLDMyLjg0NC0xOS40ODRBNjkuMTM5LDY5LjEzOSwwLDAsMCwxMTEuMTIsNjkuMTY3VjU0LjExMWMxLjQ2My02LjM1NywyLjk4NC0xMy42NzcsNi4yMTQtMTguNkMxMjIuMSwyOC4yNTYsMTMxLjEsMjEuMzE5LDEzOS41MjYsMTcuOCwxNDEuOTIsMTYuOCwxNDQuNzQ1LDE3LjI3MiwxNDYuNjI3LDE2LjAyOFptNTEuNDg1LDU0LjAyNWMwLjcxNCwwLjkuMzE1LDAuMjQzLDAuODg4LDEuNzcxaC0wLjg4OFY3MC4wNTNabS00Ni4xNTksNDIuNTEyYzI5LjMzMSwxLjM3OCw1Mi4xNjEsMjQuNjIsNDEuNzIxLDU1LjgtMS4zNTksNC4wNTgtMS4xMjIsOC40MzMtMy41NTEsMTEuNTEzLTYuNDI1LDguMTUyLTE4LjYsMTUuODM4LTMwLjE4MSwxOC42LTcuNzQ3LDEuODQ4LTE1LjE3LTEuNzM5LTE5LjUyOS0zLjU0My0zLjIzNi0xLjMzOS02LC4wNzktOC44NzYtMS43NzEtMTMuNC04LjYyNy0yNi4xMjktMzEuMTQ3LTE3Ljc1NC01My4xNCw0LjA4My0xMC43MjEsMTMuNzItMjAuMjY0LDIzLjk2Ny0yNC44QzE0MS43NDQsMTEzLjQ1NSwxNDguMiwxMTQuNzk0LDE1MS45NTMsMTEyLjU2NVoiLz4gPC9zdmc+';
			$icon = Codevz_Theme::option( 'white_label_menu_icon', $icon );

			// Add welcome theme menu.
			$title = Codevz_Theme::option( 'white_label_theme_name', esc_html__( 'XTRA', 'xtra' ) );
			add_menu_page( $title, $title, 'manage_options', $this->slug, [ $this, 'activation' ], $icon, 2 );

			// Sub menus.
			foreach( $this->menus as $slug => $title ) {

				if ( $slug === 'uninstall' && ! get_option( 'xtra-downloaded-demo' ) ) {
					continue;
				}

				if ( $slug === 'options' ) {
					add_submenu_page( $this->slug, $title, $title, 'manage_options', 'customize.php', null );
				} else {
					add_submenu_page( $this->slug, $title, $title, 'manage_options', 'xtra-' . $slug, [ $this, $slug ] );
				}

			}

		}

		/**
		 * Render before any tab content.
		 * 
		 * @return string.
		 */
		private function render_before( $active = null ) {

			echo '<div class="wrap xtra-dashboard-' . esc_attr( $active ) . '">';

			echo '<div class="xtra-dashboard">';

			echo '<div class="xtra-dashboard-header">';

				$title = Codevz_Theme::option( 'white_label_theme_name', esc_html__( 'XTRA', 'xtra' ) );

				echo '<img class="xtra-dashboard-logo" src="' . esc_html( Codevz_Theme::option( 'white_label_welcome_page_logo', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAACRNJREFUeNrsXQuwTlUUXpfcrvK+aSRJD1IjKkwKPWQKka6GSSKRSapRU4nJTJqhGpRmxDCFHhTNeERJSUxPKhQpVIo8MvIWueG2vvkX3Tn3/P59zt7nnGXmfDPf8J/z/+fuvb/9WHvvtfbJKykpoRR6kHdCkNl5aWkkjaISKpeWgi6kgqSCpEgFSQVJERanRfjsAuY1zBbMS5h1mNXk3iHmDuZ65vfMJcxtCsrjHOYNzCbMBsyazIpybw9zM3MdcynzK+Y/2gWB7dyO2Y/ZXkQxxUrmm8wpkvm4gEpyL7Mn88oAv4MYHzBflX+dTOhczkOKmMOYjS2fs4/5MvM55oEIhajEHMJ8iFnF8lmrJO+zbechLgSpy5woLcMltjIHMN+NQIzOzPHM2o6fu4B5P3NTUhNDdEsrIhCDpLDmMMcyKzh6ZgV53pwIxCAphxVSLrFbWX2Z85iFEffx6FLmM8900EXNl+dFiUIpl75xCoJm+QqzfEwDb1vmXGZ+yN/nS9fXNqb0lpfy6R+HIGiO48SiihNtmJNC/naS/D5O5Ilx0j5KQeqKaVqeksHdMtAHwQD5XRIoL+VVNypBJsQwZuTCaOYFht/F90YlnN5CsUKdC1JkYz04BGbOLxl+dwzzDAVpbifl52xiiJvfOZj0uURz5rcnud80x/24sVqWZEpczENuUSYG8Ljl/bhxuUkPYypIb9KH25nVs9yrbtpFxIx7XAiCPvs2hZk7ndkpy72Ocl8bOtH/q8ehBWmV6yEJ4qaTTCQ1oqKUp5UgzUgvWgS8rgHNbAVpqDhzF1LZhccKcl0rGtoKcp7izGGDrZbnWi2KdifUFnVtBalMulHN87mq8vRWshUkX3kG83J81oZ8W0H2Kc/gQc/nQuXp3WcryA7lGfzrFOuydtgKsl5x5rZTWQ+VOsoFWW8ryCrFmfNL26XKBVllK8hixZn7xOdaI+WCLLYVBB6Fy5Vm7j2fpQnNs/TllMND03S19w2FmcP+zA+eay1J56KicTkGEWSvssyN9bnWRbm560wQWDLjFGXud+ZUn+7qTsWCjCMDn+Uge+rPM7coyRx2A4s91+AwXV2pGFuk/MilIPspeq8/E8BhbqbnGlZ4ByluHQ+T4YpHUDegOVn67riwUVqCF/C9Ol+pGHCWM/aKD+P9jsWxWcxbY84YjIrWlPHeKA0E2awl+5CCKABf4iKf7tUfIb3f8fBuMU8YdzM7+IgBTFAqBsqnq7EYIbus48AKK1xapseQMcRaXMf80ufeo6TTAWO6lM/BoD+0CUc4zOwuA9ahCAfwq3wmgCRCjFImxCEpj+5SPhSnIKUHrUZSeC4Hb8wpEOm00+c+vEpmUHJO39kqTyMpj9BwFRa9QQoP7p3vhK0dshzShzIRsDOyDX2UCYgpUCDCYclvc8n/BtsHRnX4DCZocAqD39S1zHrk73iAzRqEgC1hvp9l0D6RVplrjEiwZRyRVQKMZ4sos7i5y9nTHQV9mprKiOmrLP/fXYomgCfJJLG0oqztS2W8WivGxG4ZmItlYrw1qNUUVJC43GWKpWYFBWbgiHkfHtGyyF6ZU73N/CKMVeQaWv2XKsqM/Anp7lwDlQOBP5MjtBBPeUEgQksxGe+gaJwV0AUNpUxA5r8aa2JcgqDrOZcyTmL5QowLcEhoKOYidvqi3FyC0XAf809D67O2pBfh2PuE209VQUpbWVczL0qwNR5lPsUcSdmjl1AR2sn8ppVUkoIsYw4G/U8pc74Jxp1jLhPr2spqKqZpZ9KxlYoDYu6i7KutWCF+jNmDWSPksg4COseRix1Vh2Yvol1fpExUkxbAjEXgzsdZWvBwseBcHNsBMZ6hzNbEERtBXMzUsRexRpkY6Ka6ZRHjZuaPkm5XZ6hUlQr5NVmGb9gIgoF5mjRXbRFWg8h/bW2I9P21Ivq7OG/rG7JYgQ4rCGK/50v/rA1Yzhjjs+yC7uRZiv5Yw0oy2ewdlyBoGdMpe3xfkoBp2t/HmoIQcfoDYK0NJ80VxSHIaMoe/Zo0nqaynjE452RwAmmBKG8xr4jS7O0sJqTGoBjsodT3zMCxjL+C7M/assE6mQ787drKqkzJHMtkipE+yyHjExYDwImsw6LosgbL8odGYI1qik9r1jLODSTDyGBTQRBY+SDpBXYXvau2QxWlr4LpOGYqSE/SHSo2y/MZBzhrO/CgBxlENJsK0kuxGFgi+dynAmkD5m5dXQhSi3Qfr7HMp7vqqDStHV0I0oZ0w+sYcTHpPX3i+lxWqokgjZULstbzuYnitNbIVVlMBGmgXJA/fOx+zahvK0hN5Rn0Hth/lvL0nm0rSBXlGfTu1FVSnt7KtoIUK8+g1231iPL0FtsKsv8UayHa03vAVpDNijOH1uANxN+kXJBNtoL8pDhz8Db3rvCuUy6I9eEzyxVnbqnPNZxmfVRpen+hHLHqJoJ8Rsr8X0thkc+1PaTriPHSWJjrCyaCQIy5CjMH62pelnszlQoyw4UggMbDZxAzny2+ZBrpc6b+lTIuqE4EgS/TamUZHH2SewismaosvS+QwbsOTQXBg4Ypytw8g3FiBIWPdYzC1J1s8sUge+rYlVugIHMY0wYadhFawqYfMa0cQf2y8Ha2nQlnDlFVvxl+Fw7VKxNO7+sU4KyTciGaXq8E7XzEAgY5twu1sluClQjjbqCXmIXxXDz+csaSmDOHAy97h5yM4aCcuAM60Yo7BP27YR2PceBLvxhbCsIK4GcVduV5mYgS1ynd2MW8kUKsA9p4giNuvFMM3cEEKUzbN0cvoUxQ6c8Rp/cjyoTFbQzzY1vXfMxPcDjMhxFkDHMJROM+QO72ZBAf2FREPuY4vfDdfZIypwCFrqQuYiUw0CNgsoujySO6FbxLvSGVdYBzgf0iMpzpFjp43hGxpC6jjH+xldCugz7zpHvpIzUlyAExME/xmtLXyPzIDRdoJuY8YjmCvFkBXRLiZCYGMMNPjojPOimQWgjCEwQx6cfdUREdi4NnsDewSiyobQnPFxDPgZC01tI661HGbQcFg3WxXWKxrZHxyP1LCmI8fCaFoSDl0lLQhVSQVJAUqSCpICns5yEpUkFSlMV/AgwAF6roGQN4BTIAAAAASUVORK5CYII=' ) ) . '" alt="XTRA Theme" />';

				echo '<div class="xtra-dashboard-title">' . sprintf( esc_html__( 'Welcome to %s WordPress Theme', 'xtra' ), $title ) . '<small>' . esc_html__( 'Current version:', 'xtra' ) . ' <strong>' . esc_html( $this->theme->version ) . '</strong></small></div>';

				// White label check videos.
				if ( ! isset( $this->disable[ 'envato' ] ) ) {

					echo apply_filters( 'xtra_buy_market', '<a href="https://1.envato.market/xtra" class="xtra-market" target="_blank"><img src="' . Codevz_Theme::$url . 'assets/img/envato.png" /></a>' );

				}

			echo '</div>';

			echo '<div class="xtra-dashboard-content">';

			echo '<div class="xtra-dashboard-menus">';

			$activation = get_option( $this->option );
			$activation = ( empty( $activation['purchase_code'] ) || ! empty( $_POST['deregister'] ) );

			foreach( $this->menus as $slug => $title ) {

				if ( $slug === 'uninstall' && ! get_option( 'xtra-downloaded-demo' ) ) {
					continue;
				}

				$link = ( $slug === 'options' ) ? 'customize.php' : 'admin.php?page=xtra-' . $slug;

				$img = ( $slug === 'activation' && ! $activation ) ? 'activated' : $slug;

				$additional = '';

				if ( $slug === 'feedback' && ! get_option( 'xtra_awaiting_seen_feedback_1' ) ) {

					$additional = '<span class="xtra-awaiting"><span aria-hidden="true" style="margin: 0;">1</span></span>';

				}

				echo '<a href="' . esc_url( admin_url( $link ) ) . '" class="' . esc_attr( $active === $slug ? 'xtra-current' : '' ) . '"><img src="' . Codevz_Theme::$url . 'assets/img/' . esc_attr( $img ) . '.png" /><span>' . esc_html( $title ) . '</span>' . $additional . '</a>';

			}

			echo '<div class="xtra-dashboard-menus-separator"></div>';

			echo '<a href="' . esc_html__( 'https://xtratheme.com/docs', 'xtra' ) . '" target="_blank"><img src="' . Codevz_Theme::$url . 'assets/img/docs.png" /><span>' . esc_html__( 'Documentation', 'xtra' ) . '</span></a>';
			echo '<a href="' . esc_html__( 'https://www.youtube.com/channel/UCrS1L4oeTRfU1hvIo1gJGjg/videos', 'xtra' ) . '" target="_blank"><img src="' . Codevz_Theme::$url . 'assets/img/videos.png" /><span>' . esc_html__( 'Video Tutorials', 'xtra' ) . '</span></a>';
			echo '<a href="' . esc_html__( 'https://xtratheme.com/changelog', 'xtra' ) . '" target="_blank"><img src="' . Codevz_Theme::$url . 'assets/img/changelog.png" /><span>' . esc_html__( 'Change Log', 'xtra' ) . '</span></a>';
			echo '<a href="' . esc_html__( 'https://codevz.ticksy.com', 'xtra' ) . '" target="_blank"><img src="' . Codevz_Theme::$url . 'assets/img/support.png" /><span>' . esc_html__( 'Support', 'xtra' ) . '</span></a>';
			echo '<a href="' . esc_html__( 'https://xtratheme.com/faqs', 'xtra' ) . '" target="_blank"><img src="' . Codevz_Theme::$url . 'assets/img/faq.png" /><span>' . esc_html__( 'F.A.Q', 'xtra' ) . '</span></a>';

			echo '</div>';

			echo '<div class="xtra-dashboard-main">';

		}

		/**
		 * Activation tab content.
		 * 
		 * @return string.
		 */
		private function render_after() {

			echo '</div>'; // main.

			echo '</div>'; // content.

			echo '</div>'; // Dashboard.

			echo '</div>'; // Wrap.

		}

		/**
		 * Showing error or success message anywhere.
		 * 
		 * @return string.
		 */
		private function message( $type, $message ) {

			$icon = $type === 'error' ? 'no-alt' : ( $type === 'info' ? 'info-outline' : 'saved' );

			if ( $type === 'warning' ) {
				$icon = 'megaphone';
			}

			echo '<div class="xtra-dashboard-' . esc_attr( $type ) . '"><i class="dashicons dashicons-' . esc_attr( $icon ) . '"></i><span>' . wp_kses_post( $message ) . '</span></div>';

		}

		/**
		 * Showing icon and text with custom style.
		 * 
		 * @return string.
		 */
		private function icon_box( $icon, $title, $link, $class = '' ) {

			if ( $class ) {
				$class = ' xtra-dashboard-icon-box-' . $class;
			}

			echo '<a href="' . esc_url( $link ) . '" class="xtra-dashboard-icon-box' . esc_attr( $class ) . '" target="_blank"><i class="dashicons dashicons-' . esc_attr( $icon ) . '"></i><div>' . wp_kses_post( $title ) . '</div></a>';

		}

		/**
		 * Show activation successful message.
		 * 
		 * @return string.
		 */
		private function activated_successfully() {

			$activation = get_option( $this->option );

			if ( empty( $activation['purchase_code'] ) ) {

				delete_option( $this->option );

				header( "Refresh:0" );

			}
			
			$expired = current_time( 'timestamp' ) > strtotime( $activation['support_until'] );

			echo '<div class="xtra-certificate">';

				echo '<div class="xtra-certificate-title">' . esc_html__( 'Activation Certificate', 'xtra' );

				echo '<form method="post"><input type="hidden" name="deregister" value="1"><input type="submit" value="' . esc_html__( 'Deregister License', 'xtra' ) . '"></form>';

				echo '</div>';

				echo '<div class="xtra-purchase-code">' . esc_html__( 'Your Purchase Code', 'xtra' ) . '<div>' . str_replace( substr( $activation['purchase_code'], -12, 10 ), '************', $activation['purchase_code'] ) . '</div></div>';

				echo '<div class="xtra-purchase-details">';

				$this->icon_box( 'calendar', '<b>' . esc_html__( 'Purchase date:', 'xtra' ) . '</b><span>' . date( 'd F Y', strtotime( esc_html( $activation['purchase_date'] ) ) ) . '</span>', '#', 'info' );

				$this->icon_box( 'sos', '<b>' . esc_html__( 'Support until:', 'xtra' ) . '</b><span>' . date( 'd F Y', strtotime( esc_html( $activation['support_until'] ) ) ) . '</span>', '#', ( $expired ? 'error' : 'info' ) );

				echo '</div>';

			echo '</div>';

			if ( $expired ) {

				$this->message( 'error', esc_html__( 'Your support has been expired, Click on below link and extend your support.', 'xtra' ) );

			}

			$this->icon_box( 'sos', esc_html__( 'Buy extended support or new license', 'xtra' ), 'https://1.envato.market/xtratheme', 'info' );

		}

		/**
		 * Activation tab content.
		 * 
		 * @return string.
		 */
		public function activation() {

			$this->render_before( 'activation' );

			ob_start();
			do_action( 'xtra_dashboard_activation_before' );
			$action = ob_get_clean();

			if ( $action ) {

				echo wp_kses_post( $action );

				$this->render_after();

			} else {

				// Get saved activation.
				$activation = get_option( $this->option );

				// Purchase code.
				$purchase_code = isset( $activation[ 'purchase_code' ] ) ? $activation[ 'purchase_code' ] : null;

				// Deregister license.
				if ( ! empty( $_POST['deregister'] ) ) {

					$deregister = $this->deregister( $purchase_code, strlen( $purchase_code ) < 40 );

					$purchase_code = null;

				}

				echo '<div class="xtra-dashboard-section-title">' . esc_html__( 'License Activation', 'xtra' ) . '</div>';

				// Activated theme.
				if ( $purchase_code ) {

					$this->activated_successfully();

				} else {

					if ( isset( $deregister ) ) {

						$this->message( 'success', esc_html__( 'Your license code on this website deregistered successfully.', 'xtra' ) );

					} else {

						if ( isset( $_POST[ 'register' ] ) ) {

							if ( empty( $_POST[ 'register' ] ) ) {

								$this->message( 'error', esc_html__( 'Please insert a valid license code.', 'xtra' ) );

							} else {

								$code = esc_html( $_POST['register'] );

								$register = $this->register( $code, strlen( $code ) < 40 );

								if ( is_bool( $register ) ) {

									$this->message( 'success', esc_html__( 'Congratulation', 'xtra' ) . ', ' . esc_html__( 'Your theme has been activated successfully.', 'xtra' ) );

									$this->activated_successfully();

									$hide_form = true;

								} else {

									$this->message( 'error', $register );

								}

							}

						}

					}

					if ( ! isset( $hide_form ) ) {

						echo '<p>' . esc_html__( 'Please activate your theme via purchase code to access theme features, updates and demo importer.', 'xtra' ) . '</p>';

						echo '<form class="xtra-dashboard-activation-form" method="post"><input type="text" name="register" placeholder="' . esc_html__( 'Please insert purchase code ...', 'xtra' ) . '" required><input type="submit" value="' . esc_html__( 'Activate', 'xtra' ) . '"></form>';

						$this->icon_box( 'editor-help', esc_html__( 'How to find purchase code?', 'xtra' ), 'https://xtratheme.com/docs/getting-started/how-to-activate-theme-with-license-code/', 'info' );

						$this->icon_box( 'cart', esc_html__( 'Buy new license', 'xtra' ), 'https://1.envato.market/xtratheme', 'success' );

					}

				}

				$this->render_after();

			}

		}

		/**
		 * Plugins installation tab content.
		 * 
		 * @return string.
		 */
		public function plugins() {

			$this->render_before( 'plugins' );

			echo '<div class="xtra-dashboard-section-title">' . esc_html__( 'Install Plugins', 'xtra' ) . '</div>';

			echo '<div class="xtra-plugins" data-nonce="' . wp_create_nonce( 'xtra-wizard' ) . '">';

			$plugins = 0;

			foreach( $this->plugins as $slug => $plugin ) {

				// Check plugin.
				if ( $this->plugin_is_active( $slug ) ) {
					continue;
				}

				echo '<div class="xtra-plugin">';

					echo '<div class="xtra-plugin-header">';

					echo '<img src="' . Codevz_Theme::$url . 'assets/img/' . esc_attr( $slug ) . '.jpg" alt="' . esc_attr( $plugin[ 'name' ] ) . '" />';
					
					if ( isset( $plugin[ 'required' ] ) ) {

						$plugin[ 'name' ] .= '<small>' . esc_html__( 'Required', 'xtra' ) . '</small>';

					} else if ( isset( $plugin[ 'recommended' ] ) ) {

						$plugin[ 'name' ] .= '<small>' . esc_html__( 'Recommended', 'xtra' ) . '</small>';

					}

					echo '<span>' . $plugin[ 'name' ] . '</span>';

					echo '</div>';

					echo '<div class="xtra-plugin-footer">';

						echo '<div class="xtra-plugin-details">';

						if ( isset( $plugin[ 'source' ] ) ) {
							echo esc_html__( 'Private Repository', 'xtra' ) . '<br /><span>' . esc_html__( 'Premium', 'xtra' ) . '</span>';
						} else {
							echo esc_html__( 'WordPress Repository', 'xtra' ) . '<br /><span>' . esc_html__( 'Free version', 'xtra' ) . '</span>';
						}

						echo '</div>';

						if ( file_exists( $this->plugin_file( $slug, true ) ) ) {

							$title = esc_html__( 'Activate', 'xtra' );

							$activated = esc_html__( 'Activated successfully', 'xtra' );

						} else {

							$title = esc_html__( 'Install & Activate', 'xtra' );

							$activated = esc_html__( 'Installed & Activated', 'xtra' );

						}

						echo '<a href="#" class="xtra-button-primary" data-plugin="' . esc_attr( $slug ) . '" data-title="' . esc_html__( 'Please wait', 'xtra' ) . '"><span>' . esc_html( $title ) . '</span><i class="xtra-loading" aria-hidden="true"></i></a>';

						echo '<div class="xtra-plugin-activated hidden"><i class="dashicons dashicons-yes"></i> ' . esc_html( $activated ) . '</div>';

					echo '</div>';

					echo '<div class="xtra-plugin-progress"></div>';

				echo '</div>';

				$plugins++;

			}

			echo '</div>';

			if ( ! $plugins ) {

				$this->message( 'success', esc_html__( 'You have installed all the plugins and there is no any plugin to install.', 'xtra' ) );

			}

			$this->render_after();

		}

		/**
		 * Demo importer tab content.
		 * 
		 * @return string.
		 */
		public function importer() {

			$this->render_before( 'importer' );
					
			$activation = get_option( $this->option );

			echo '<div class="xtra-demo-importer">';

			echo '<div class="xtra-filters">';

				echo '<div class="xtra-filters-title">' . esc_html__( 'Fitlers:', 'xtra' ) . '</div>';

				//echo '<a href="#" class="xtra-current">' . esc_html__( 'All', 'xtra' ) . '</a>';
				echo '<a href="#" data-filter="js_composer" class="xtra-current">' . esc_html__( 'WPBakery', 'xtra' ) . '</a>';
				echo '<a href="#" data-filter="elementor">' . esc_html__( 'Elementor', 'xtra' ) . '</a>';
				//echo '<a href="#" data-filter="arabic">' . esc_html__( 'Arabic', 'xtra' ) . '</a>';

				echo '<input type="search" name="search" placeholder="' . esc_html__( 'e.g. business, rtl, elementor, service', 'xtra' ) . '" />';

				echo '<i class="dashicons dashicons-search"></i>';

			echo '</div>';

			echo '<div class="xtra-demos xtra-lazyload clearfix">';

			foreach( $this->demos as $demo => $args ) {

				$rtl 	= is_rtl() && isset( $args[ 'rtl' ] ) ? 'rtl/' : '';
				$folder = apply_filters( 'xtra_rtl_checker', $rtl );

				$preview = $rtl ? 'arabic/' : '';
				$preview = str_replace( 'api', $preview . esc_attr( $demo ), Codevz_Theme::$api );
				$preview = apply_filters( 'xtra_rtl_preview', $preview );

				$args[ 'demo' ] = $demo;
				$args[ 'image' ] = Codevz_Theme::$api . 'demos/' . $folder . esc_attr( $demo ) . '.jpg';
				$args[ 'preview' ] = $preview;

				echo '<div class="xtra-demo">';

					$keywords = isset( $args[ 'keywords' ] ) ? $args[ 'keywords' ] : '';

					$keywords .= empty( $args[ 'rtl' ] ) ? '' : ' rtl arabic';
					$keywords .= empty( $args[ 'free' ] ) ? '' : ' free';
					$keywords .= empty( $args[ 'js_composer' ] ) ? ' js_composer wpbakery' : '';
					if ( ! empty( $args[ 'elementor' ] ) || ! empty( $args[ 'rtl' ][ 'elementor' ] ) ) {
						$keywords .= ' elementor';
					}
					$keywords .= ' ' . $demo;

					// Keywords.
					echo '<div class="hidden">' . esc_html( $keywords ) . '</div>';

					// Preview image.
					echo '<img data-src="' . esc_url( $args[ 'image' ] ) . '" />';

					// Demo title.
					echo '<div class="xtra-demo-title">' . ucwords( str_replace( '-', ' ', esc_html( isset( $args[ 'title' ] ) ? $args[ 'title' ] : $args[ 'demo' ] ) ) ) . '</div>';

					// Buttons.
					echo '<div class="xtra-demo-buttons">';

						if ( empty( $activation['purchase_code'] ) && empty( $args[ 'free' ] ) ) {

							echo '<a href="' . esc_url( get_admin_url() ) . 'admin.php?page=xtra-activation" class="xtra-button-primary">' . esc_html__( 'Unlock', 'xtra' ) . '</a>';

						} else {

							echo '<a href="#" class="xtra-button-primary" data-args=\'' . json_encode( $args ) . '\'>' . esc_html__( 'Import', 'xtra' ) . '</a>';

						}

						if ( get_option( 'xtra_uninstall_' . $demo ) ) {

							echo '<a href="' . esc_url( get_admin_url() ) . 'admin.php?page=xtra-uninstall" class="xtra-button-secondary xtra-uninstall-button">' . esc_html__( 'Uninstall', 'xtra' ) . '</a>';

						} else {

							if ( Codevz_Theme::contains( $args[ 'preview' ], 'arabic' ) ) {

								$args[ 'preview' ] = str_replace( '/' . $demo, '-elementor/' . $demo, $args[ 'preview' ] );

							} else {

								$args[ 'preview' ] = str_replace( $demo, 'elementor/' . $demo, $args[ 'preview' ] );

							}

							echo '<a href="' . esc_url( $args[ 'preview' ] ) . '" class="xtra-button-secondary" target="_blank">' . esc_html__( 'Preview', 'xtra' ) . '</a>';

						}

					echo '</div>';

				echo '</div>';

			}

			echo '</div>';

			echo '</div>';

			// Wizard.
			echo '<div class="xtra-wizard hidden" data-nonce="' . wp_create_nonce( 'xtra-wizard' ) . '">';

				echo '<i class="xtra-back dashicons dashicons-arrow-left-alt"><span>' . esc_html__( 'Back to demos', 'xtra' ) . '</span></i>';

				echo '<div class="xtra-wizard-main">';

					echo '<div class="xtra-wizard-preview">';

						// Demo image.
						echo '<img class="xtra-demo-image" src="#" alt="Demo preview" />';

						// Progress bar.
						echo '<img class="xtra-importer-spinner" src="' . Codevz_Theme::$url . 'assets/img/importing.png" />';
						echo '<div class="xtra-wizard-progress"><div data-current="0"><span></span></div></div>';

					echo '</div>';

					echo '<div class="xtra-wizard-content">';

						// Step 1.
						echo '<div data-step="1" class="xtra-current">';

							echo '<div class="xtra-wizard-welcome"><span>' . esc_html__( 'Welcome to', 'xtra' ) . '</span><strong>' . esc_html__( 'Xtra Demo Importer Wizard', 'xtra' ) . '</strong></div>';

							echo '<div class="xtra-wizard-selected"><span>' . esc_html__( 'Selected demo:', 'xtra' ) . '</span><strong>...</strong></div>';

							echo '<div class="xtra-wizard-selected"><span>' . esc_html__( 'Live preview:', 'xtra' ) . '</span><br /><br />';

								echo '<a href="#" class="xtra-live-preview xtra-live-preview-elementor xtra-button-secondary" target="_blank">' . esc_html__( 'Elementor', 'xtra' ) . '</a>';

								echo '<a href="#" class="xtra-live-preview xtra-live-preview-wpbakery xtra-button-secondary" target="_blank">' . esc_html__( 'WPBakery', 'xtra' ) . '</a>';

							echo '</div>';

						echo '</div>'; // step 1.

						// Step 2.
						echo '<div data-step="2">';

							echo '<div class="xtra-step-title">' . esc_html__( 'Choose page builder:', 'xtra' ) . '</div>';

							echo '<div class="xtra-image-radios">';
								echo '<label class="xtra-image-radio"><input type="radio" name="pagebuilder" value="js_composer" checked /><span><img src="' . Codevz_Theme::$url . 'assets/img/js_composer.jpg"><b>' . esc_html__( 'WPBakery', 'xtra' ) . '</b></span></label>';
								echo '<label class="xtra-image-radio"><input type="radio" name="pagebuilder" value="elementor" /><span data-tooltip="' . esc_html__( 'Elementor version will be available soon.', 'xtra' ) . '"><img src="' . Codevz_Theme::$url . 'assets/img/elementor.jpg"><b>' . esc_html__( 'Elementor', 'xtra' ) . '</b></span></label>';
							echo '</div>';

							echo apply_filters( 'xtra_rtl_checkbox', '<label class="xtra-checkbox xtra-rtl" data-tooltip="' . esc_html__( 'By checking this field, wizard will import Arabic version of current demo that you have selected.', 'xtra' ) . '">' . esc_html__( 'RTL version?', 'xtra' ) . '<input type="checkbox" name="rtl" ' . ( is_rtl() ? 'checked' : '' ) . ' /><span class="checkmark"></span></label>' );

						echo '</div>'; // step 2.

						// Step 3.
						echo '<div data-step="3">';

							echo '<label class="xtra-radio"><input type="radio" name="config" value="full" checked /><b>' . esc_html__( 'Full Import', 'xtra' ) . '</b><span class="checkmark"></span></label>';
							echo '<label class="xtra-radio"><input type="radio" name="config" value="custom" /><b>' . esc_html__( 'Custom Import', 'xtra' ) . '</b><span class="checkmark"></span></label>';

							echo '<div class="xtra-checkboxes clearfix" disabled>';
								echo '<label class="xtra-checkbox">' . esc_html__( 'Theme Options', 'xtra' ) . '<input type="checkbox" name="options" checked /><span class="checkmark"></span></label>';
								echo '<label class="xtra-checkbox">' . esc_html__( 'Widgets', 'xtra' ) . '<input type="checkbox" name="widgets" checked /><span class="checkmark"></span></label>';
								echo '<label class="xtra-checkbox">' . esc_html__( 'Pages & Posts', 'xtra' ) . '<input type="checkbox" name="content" checked /><span class="checkmark"></span></label>';
								echo '<label class="xtra-checkbox">' . esc_html__( 'Images & Media', 'xtra' ) . '<input type="checkbox" name="images" checked /><span class="checkmark"></span></label>';
								echo '<label class="xtra-checkbox">' . esc_html__( 'WooCommerce', 'xtra' ) . '<input type="checkbox" name="woocommerce" checked /><span class="checkmark"></span></label>';
								echo '<label class="xtra-checkbox">' . esc_html__( 'Revolution Slider', 'xtra' ) . '<input type="checkbox" name="slider" checked /><span class="checkmark"></span></label>';
							echo '</div>';

						echo '</div>'; // step 3.

						// Step 4.
						echo '<div data-step="4"><ul class="xtra-list"></ul></div>';

						// Step 5.
						echo '<div data-step="5">';

							// Success.
							echo '<div class="xtra-importer-done xtra-demo-success">';

								echo '<img src="' . Codevz_Theme::$url . 'assets/img/tick.png" />';
								echo '<span>' . esc_html__( 'Congratulation', 'xtra' ) . '</span>';
								echo '<p>' . esc_html__( 'Your website has been imported successfully.', 'xtra' ) . '</p>';

								echo '<a href="' . esc_url( get_home_url() ) . '" class="xtra-button-primary" target="_blank">' . esc_html__( 'View your website', 'xtra' ) . '</a>';
								echo '<a href="' . esc_url( get_admin_url() ) . 'customize.php" class="xtra-button-secondary" target="_blank">' . esc_html__( 'Customize webiste', 'xtra' ) . '</a>';

							echo '</div>';

							// Error.
							echo '<div class="xtra-importer-done xtra-demo-error hidden">';

								echo '<img src="' . Codevz_Theme::$url . 'assets/img/error.png" />';
								echo '<span>' . esc_html__( 'Error!', 'xtra' ) . '</span>';
								echo '<p>' . esc_html__( 'An error has occured, Please try again.', 'xtra' ) . '</p>';

								echo '<a href="' . esc_html__( 'https://xtratheme.com/docs', 'xtra' ) . '" class="xtra-button-primary" target="_blank">' . esc_html__( 'Troubleshooting', 'xtra' ) . '</a>';
								echo '<a href="#" class="xtra-button-secondary xtra-back-to-demos">' . esc_html__( 'Back to demos', 'xtra' ) . '</a>';

							echo '</div>';

						echo '</div>'; // step 5.

					echo '</div>';

				echo '</div>';

				// Wizard footer.
				echo '<div class="xtra-wizard-footer">';

					echo '<a href="#" class="xtra-button-secondary xtra-wizard-prev">' . esc_html__( 'Prev Step', 'xtra' ) . '</a>';

					echo '<ul class="xtra-wizard-steps clearfix">';
						echo '<li data-step="1" class="xtra-current"><span>' . esc_html__( 'Getting Started', 'xtra' ) . '</span></li>';
						echo '<li data-step="2"><span>' . esc_html__( 'Choose Builder', 'xtra' ) . '</span></li>';
						echo '<li data-step="3"><span>' . esc_html__( 'Configuration', 'xtra' ) . '</span></li>';
						echo '<li data-step="4"><span>' . esc_html__( 'Please wait, Importing', 'xtra' ) . '</span></li>';
						//echo '<li data-step="5"><span>' . esc_html__( 'Ready to go!', 'xtra' ) . '</span></li>';
					echo '</ul>';

					echo '<a href="#" class="xtra-button-primary xtra-wizard-next">' . esc_html__( 'Next Step', 'xtra' ) . '</a>';

				echo '</div>';

			echo '</div>';

			$this->render_after();

		}

		/**
		 * Page importer.
		 * 
		 * @return string.
		 */
		public function importer_page() {

			$this->render_before( 'importer_page' );

			echo '<div class="xtra-dashboard-section-title">' . esc_html__( 'Single Page Importer', 'xtra' ) . '</div>';

			if ( ! Codevz_Theme::option( 'site_color_sec' ) ) {

				$this->message( 'warning', esc_html__( 'The demo page you want to import may have a second color, To avoid the color problem, set a second color for your site from Theme Options > General > Colors', 'xtra' ) );

			}

			echo '<p style="font-size:14px;color:#7e7e7e;">' . esc_html__( 'Insert a demo page URL and click on import button then wait for the process to complete.', 'xtra' ) . '</p>';

			echo '<br /><form class="xtra-page-importer-form">';

				echo '<input type="url" placeholder="' . esc_html__( 'Insert the demo link ...', 'xtra' ) . '" />';

				echo '<a href="#" class="xtra-button-primary" data-nonce="' . wp_create_nonce( 'xtra-page-importer' ) . '"><span>' . esc_html__( 'Import', 'xtra' ) . '</span><i class="xtra-loading" aria-hidden="true"></i></a>';

				echo '<br /><br /><br /><span class="xtra-page-importer-message"></span>';

			echo '</form>';

			$this->render_after();

		}

		/**
		 * Single page importer AJAX request.
		 * 
		 * @return JSON
		 */
		public function xtra_page_importer() {

			check_ajax_referer( 'xtra-page-importer', 'nonce' );

			if ( ! empty( $_POST[ 'url' ] ) ) {

				if ( filter_var( $_POST[ 'url' ], FILTER_VALIDATE_URL ) === FALSE || ! Codevz_Theme::contains( $_POST[ 'url' ], 'xtratheme' ) ) {

					wp_send_json(
						[
							'status' 	=> '202',
							'message' 	=> esc_html__( 'Please insert a valid URL', 'xtra' )
						]
					);

				}

				$url = esc_url( $_POST[ 'url' ] );

				$response = wp_remote_get( $url . '?export_single_page=' . $url );

				if ( empty( $response['body'] ) && method_exists( 'Codevz_Plus', 'fgc' ) ) {

					$response = Codevz_Plus::fgc( $url . '?export_single_page=' . $url );

				}

				if ( empty( $response['body'] ) && ! ini_get( 'allow_url_fopen' ) ) {

					wp_send_json(
						[
							'status' 	=> '202',
							'message' 	=> esc_html__( 'Enable allow_url_fopen on your server then you can import page.', 'xtra' )
						]
					);

				}

				if ( ! empty( $response[ 'body' ] ) ) {

					$response = json_decode( $response['body'], true );

					if ( ! empty( $response[ 'page' ] ) ) {

						$page = json_decode( $response[ 'page' ] );

						$page->ID = null;

						$page_exist = get_page_by_path( $page->post_name );

						if ( ! empty( $page_exist->ID ) ) {
							$page->post_name = $page->post_name . rand( 111, 999 );
						}

						$page->post_title = $page->post_title . ' (Imported)';

						// Replace colors.
						if ( $page->post_content ) {

							if ( $response[ 'color2' ] ) {
								$color2 = Codevz_Theme::option( 'site_color_sec' ) ? Codevz_Theme::option( 'site_color_sec' ) : $response[ 'color1' ];
								$page->post_content = Codevz_Options::updateDatabase( $response[ 'color2' ], $color2, $page->post_content );
							}

							if ( $response[ 'color1' ] ) {
								$page->post_content = Codevz_Options::updateDatabase( $response[ 'color1' ], Codevz_Theme::option( 'site_color' ), $page->post_content );
							}

						}

						$post_id = wp_insert_post( $page );

						if ( $post_id && ! empty( $response[ 'meta' ] ) ) {

							$meta = json_encode( $response[ 'meta' ] );

							if ( $response[ 'color2' ] ) {
								$color2 = Codevz_Theme::option( 'site_color_sec' ) ? Codevz_Theme::option( 'site_color_sec' ) : $response[ 'color1' ];
								$meta = Codevz_Options::updateDatabase( $response[ 'color2' ], $color2, $meta );
								$meta = Codevz_Options::updateDatabase( strtoupper( $response[ 'color2' ] ), strtoupper( $color2 ), $meta );
							}

							if ( $response[ 'color1' ] ) {
								$meta = Codevz_Options::updateDatabase( $response[ 'color1' ], Codevz_Theme::option( 'site_color' ), $meta );
								$meta = Codevz_Options::updateDatabase( strtoupper( $response[ 'color1' ] ), strtoupper( Codevz_Theme::option( 'site_color' ) ), $meta );
							}

							$meta = Codevz_Demo_Importer::replace_upload_url( $meta, true );

							$meta = Codevz_Demo_Importer::replace_demo_link( $meta, false, false, 'elementor/' );
							$meta = Codevz_Demo_Importer::replace_demo_link( $meta, true, false, 'elementor/' );

							update_post_meta( $post_id, '_elementor_data', wp_slash_strings_only( $meta ) );
							update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
							update_post_meta( $post_id, '_elementor_template_type', 'wp-page' );
							update_post_meta( $post_id, '_elementor_version', '3.4.3' );

						}

						wp_send_json(
							[
								'status' 	=> '200',
								'message' 	=> esc_html__( 'Page imported successfully.', 'xtra' ),
								'link' 		=> get_permalink( $post_id )
							]
						);

					} else if ( ! empty( $response[ 'message' ] ) ) {

						wp_send_json(
							[
								'status' 	=> '202',
								'message' 	=> $response[ 'message' ]
							]
						);

					} else if ( is_wp_error( $response ) ) {

						wp_send_json(
							[
								'status' 	=> '202',
								'message' 	=> $response->get_error_message()
							]
						);

					} else {

						wp_send_json(
							[
								'status' 	=> '202',
								'message' 	=> esc_html__( 'Error, Please try again ...', 'xtra' )
							]
						);

					}

				}

				wp_send_json(
					[
						'status' 	=> '202',
						'message' 	=> esc_html__( 'Server not responding, Please make sure your link is valid.', 'xtra' )
					]
				);

			}

			wp_send_json(
				[
					'status' 	=> '202',
					'message' 	=> esc_html__( 'Something went wrong, Please try again ...', 'xtra' )
				]
			);

		}

		/**
		 * System status item content.
		 * 
		 * @return string.
		 */
		private function status_item( $type, $title, $value, $badge ) {

			echo '<div class="xtra-ss-item xtra-dashboard-' . ( $type === 'error' ? 'error' : 'success' ) . '">';

				echo '<img src="' . Codevz_Theme::$url . 'assets/img/' . ( $type === 'error' ? 'error' : 'tick' ) . '.png" />';

				echo '<b>' . esc_html( $title ) . '</b>';

				echo '<span>' . wp_kses_post( $value ) . '<i>' . wp_kses_post( $badge ) . '</i></span>';

			echo '</div>';

		}

		/**
		 * System status tab content.
		 * 
		 * @return string.
		 */
		public function status() {

			$this->render_before( 'status' );

			echo '<div class="xtra-dashboard-section-title">' . esc_html__( 'System Status', 'xtra' ) . '</div>';

			echo '<div class="xtra-system-status">';

				// PHP version.
				if ( version_compare( phpversion(), '7.0.0', '>=' ) ) {

					$this->status_item( 'success', esc_html__( 'Server PHP Version', 'xtra' ), phpversion(), esc_html__( 'Good', 'xtra' ) );

				} else {

					$this->status_item( 'error', esc_html__( 'Server PHP Version', 'xtra' ), phpversion(), esc_html__( 'PHP 8.0 or above recommended', 'xtra' ) );

				}

				// PHP Memory limit.
				$memory_limit = ini_get( 'memory_limit' );
				if ( (int) $memory_limit >= 128 || (int) $memory_limit < 0 ) {

					$this->status_item( 'success', esc_html__( 'Server PHP Memory Limit', 'xtra' ), $memory_limit, esc_html__( 'Good', 'xtra' ) );

				} else {

					$this->status_item( 'error', esc_html__( 'Server PHP Memory Limit', 'xtra' ), $memory_limit, esc_html__( '128M recommended', 'xtra' ) );

				}

				// PHP post max size.
				$pms = ini_get( 'post_max_size' );
				if ( (int) $pms >= 8 ) {

					$this->status_item( 'success', esc_html__( 'Server PHP Post Max Size', 'xtra' ), $pms, esc_html__( 'Good', 'xtra' ) );

				} else {

					$this->status_item( 'error', esc_html__( 'Server PHP Post Max Size', 'xtra' ), $pms, esc_html__( '8 recommended', 'xtra' ) );

				}

				// PHP max execution time.
				$met = ini_get( 'max_execution_time' );
				if ( (int) $met >= 30 ) {

					$this->status_item( 'success', esc_html__( 'Server PHP Max Execution Time', 'xtra' ), $met, esc_html__( 'Good', 'xtra' ) );

				} else {

					$this->status_item( 'error', esc_html__( 'PHP Max Execution Time', 'xtra' ), $met, esc_html__( '30 recommended', 'xtra' ) );

				}

				// cURL or fopen.
				if ( ini_get( 'allow_url_fopen' ) ) {

					$this->status_item( 'success', esc_html__( 'Server PHP', 'xtra' ) . ' allow_url_fopen', esc_html__( 'Active', 'xtra' ), esc_html__( 'Good', 'xtra' ) );

				} else if ( function_exists( 'curl_version' ) ) {

					$this->status_item( 'success', esc_html__( 'Server PHP', 'xtra' ) . ' cURL', esc_html__( 'Active', 'xtra' ), esc_html__( 'Good', 'xtra' ) );

				} else {

					$this->status_item( 'error', esc_html__( 'PHP cURL or allow_url_fopen is required.', 'xtra' ), '', esc_html__( 'Contact with your server support.', 'xtra' ) );

				}

			echo '</div>';

			$this->render_after();

		}

		/**
		 * Feedback tab content.
		 * 
		 * @return string.
		 */
		public function feedback() {

			$this->render_before( 'feedback' );

			echo '<div class="xtra-dashboard-section-title">' . esc_html__( 'Feedback', 'xtra' ) . '</div>';

			if ( ! get_option( 'xtra_awaiting_seen_feedback_1' ) ) {

				$this->message( 'warning', esc_html__( 'Please help us improve the XTRA theme, we have added a feedback form, you can send us your comments and criticisms.', 'xtra' ) );

				update_option( 'xtra_awaiting_seen_feedback_1', true );

			}

			echo '<p style="font-size:14px;color:#7e7e7e;">' . esc_html__( 'Thanks for purchasing the XTRA theme; to improve the XTRA theme, through the following form, you can send your feedback such as report a bug, request a feature, request a demo, ask non-support questions, etc.', 'xtra' ) . '</p>';

			echo '<br /><form class="xtra-feedback-form">';

				wp_editor( false, 'xtra-feedback', [ 'media_buttons' => true, 'textarea_rows' => 10 ] );

				echo '<br /><br /><a href="#" class="xtra-button-primary" data-nonce="' . wp_create_nonce( 'xtra-feedback' ) . '"><span>' . esc_html__( 'Submit', 'xtra' ) . '</span><i class="xtra-loading" aria-hidden="true"></i></a>';

				echo '<br /><br /><br /><span class="xtra-feedback-message"></span>';

			echo '</form>';

			$this->render_after();

		}

		/**
		 * AJAX process feedback form message send to email.
		 * 
		 * @return string.
		 */
		public function feedback_submit() {

			check_ajax_referer( 'xtra-feedback', 'nonce' );

			if ( ! empty( $_POST[ 'message' ] ) ) {

				// Form.
				$from = get_option( 'admin_email' ); 
				$subject = 'XTRA Feedback';
				$sender = 'From: ' . get_bloginfo( 'name' ) . ' <' . $from . '>' . "\r\n";

				$message = wp_kses_post( $_POST[ 'message' ] ) . '<br /><br />' . get_home_url();

				$headers[] = 'MIME-Version: 1.0' . "\r\n";
				$headers[] = 'Content-type: text/html; charset=UTF-8' . "\r\n";
				$headers[] = "X-Mailer: PHP \r\n";
				$headers[] = $sender;

				$mail = '';

				if ( method_exists( 'Codevz_Plus', 'sendMail' ) ) {

					$mail = Codevz_Plus::sendMail( 'xtratheme.com@gmail.com', $subject, $message, $headers );

				}

				if ( $mail ) {

					wp_send_json(
						[
							'status' 	=> '200',
							'message' 	=> esc_html__( 'Your message has been sent successfully.', 'xtra' )
						]
					);

				} else {

					wp_send_json(
						[
							'status' 	=> '202',
							'message' 	=> esc_html__( 'Could not send your message, Please try again.', 'xtra' )
						]
					);

				}

			}

			wp_send_json(
				[
					'status' 	=> '202',
					'message' 	=> esc_html__( 'There is no message to send, Please try again.', 'xtra' )
				]
			);

		}

		/**
		 * Uninstall demo tab content.
		 * 
		 * @return string.
		 */
		public function uninstall() {

			$this->render_before( 'uninstall' );

			echo '<div class="xtra-demos xtra-uninstall xtra-lazyload clearfix">';

			echo '<div class="xtra-dashboard-section-title">' . esc_html__( 'Uninstall Demo(s)', 'xtra' ) . '</div>';

			echo '<p class="xtra-uninstall-p">' . esc_html__( 'In this list you can see demos imported on your site previously, You can uninstall any demo data.', 'xtra' ) . '</p>';

			$has_demo = false;

			foreach ( $this->demos as $demo => $args ) {

				if ( get_option( 'xtra_uninstall_' . $demo ) ) {

					$has_demo = true;

					$rtl 	= is_rtl() && isset( $args[ 'rtl' ] ) ? 'rtl/' : '';
					$folder = apply_filters( 'xtra_rtl_checker', $rtl );

					echo '<div class="xtra-demo">';

						echo '<img data-src="' . esc_url( Codevz_Theme::$api . 'demos/' . $folder . esc_attr( $demo ) . '.jpg' ) . '" />';

						echo '<div class="xtra-demo-title">' . ucwords( str_replace( '-', ' ', esc_html( isset( $args[ 'title' ] ) ? $args[ 'title' ] : $demo ) ) ) . '</div>';

						echo '<div class="xtra-demo-buttons">';

							echo '<a href="#" class="xtra-button-primary xtra-uninstall-button" data-demo="' . esc_html( $demo ) . '" data-title="' . esc_html__( 'Please wait', 'xtra' ) . '"><span>' . esc_html__( 'Uninstall', 'xtra' ) . '</span></a>';

						echo '</div>';

					echo '</div>';

				}

			}

			if ( ! $has_demo ) {

				$this->message( 'info', esc_html__( 'You have not imported any demo yet.', 'xtra' ) );

			}

			echo '</div>';

			echo '<div class="xtra-modal" data-nonce="' . wp_create_nonce( 'xtra-wizard' ) . '">';

				echo '<div class="xtra-modal-inner">';

					echo '<div class="xtra-uninstall-msg">';

						echo '<div class="xtra-dashboard-section-title"><img src="' . Codevz_Theme::$url . 'assets/img/error.png" />' . esc_html__( 'Are you sure for this?', 'xtra' ) . '</div>';

						echo '<p>' . esc_html__( 'This will be deleted all your website data such as posts, pages, attachments, theme options, sliders, etc. and there is no undo button for this action.', 'xtra' ) . '</p>';

						echo '<img class="xtra-importer-spinner" src="' . Codevz_Theme::$url . 'assets/img/importing.png" />';

						echo '<a href="#" class="xtra-button-secondary">' . esc_html__( 'No, never mind', 'xtra' ) . '</a>';
						echo '<a href="#" class="xtra-button-primary" data-title="' . esc_html__( 'Uninstalling, Please wait', 'xtra' ) . '">' . esc_html__( 'Yes please', 'xtra' ) . '</a>';

					echo '</div>';

					// Done message.
					echo '<div class="xtra-uninstalled hidden">';
						echo '<img src="' . Codevz_Theme::$url . 'assets/img/tick.png" />';
						echo '<h2>' . esc_html__( 'Demo "DEMONAME" uninstalled successfully.', 'xtra' ) . '</h2>';
						echo '<a href="#" class="xtra-button-primary xtra-reload">' . esc_html__( 'Reload page', 'xtra' ) . '</a>';
						//echo '<a href="#" class="xtra-button-secondary">' . esc_html__( 'Close', 'xtra' ) . '</a>';
					echo '</div>';

				echo '</div>';

			echo '</div>';

			$this->render_after();

		}

		/**
		 * Deregister license and delete activation option.
		 * 
		 * @return -
		 */
		public function deregister( $code, $envato ) {

			if ( ! $envato ) {
				$verify = wp_remote_get( 'https://xtratheme.com?type=deregister&domain=' . $this->get_host_name() . '&code=' . $code );
			}

			delete_option( $this->option );

			return true;

		}

		/**
		 * Register license and add activation option to database.
		 * 
		 * @return -
		 */
		public function register( $code, $envato ) {

			if ( $envato ) {

				$item_id 		= '20715590';
				$personalToken 	= "ZMdAZMzRH8IUvopEsOv5jb9hgVfczMQf";
				$userAgent 		= "Purchase code verification on " . $this->get_host_name();

				// Surrounding whitespace can cause a 404 error, so trim it first
				$code = trim( $code );

				// Make sure the code looks valid before sending it to Envato
				if ( ! preg_match( "/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $code ) ) {

					return esc_html__( 'Envato error: Your license code is invalid.', 'xtra' );

				}

				// Build the request
				$response = wp_remote_get( "https://api.envato.com/v3/market/author/sale?code={$code}", [
					'headers' => [
						'Authorization' => "Bearer {$personalToken}",
						'User-Agent' 	=> "{$userAgent}",
					],
				]);

				// Handle connection errors (such as an API outage)
				// You should show users an appropriate message asking to try again later
				if ( is_wp_error( $response ) ) { 
				    return esc_html__( 'Envato API error:', 'xtra' ) . ' ' . $response->get_error_message();
				}

				// If we reach this point in the code, we have a proper response!
				// Let's get the response code to check if the purchase code was found
				$responseCode = wp_remote_retrieve_response_code( $response );

				// HTTP 404 indicates that the purchase code doesn't exist
				if ( $responseCode === 404 ) {

				    return esc_html__( 'Envato error: The purchase code does not exist.', 'xtra' );

				}

				// Anything other than HTTP 200 indicates a request or API error
				// In this case, you should again ask the user to try again later
				if ( $responseCode !== 200 ) {
				    return esc_html__( 'Envato error: Failed to validate code due to an HTTP error', 'xtra' ) . ' ' . $responseCode;
				}

				$response = wp_remote_retrieve_body( $response );

				// Parse the response into an object with warnings supressed
				$body = @json_decode( $response , true );

				if ( ! isset( $body[ 'sold_at' ] ) ) {
					return esc_html__( 'Envato error: Please try again in 10 seconds.', 'xtra' );
				}

				// Check for errors while decoding the response (PHP 5.3+)
				if ( $body === false && json_last_error() !== JSON_ERROR_NONE ) {
					return esc_html__( 'Envato error: Parsing response.', 'xtra' );
				}

				// If item id is wrong
				if ( isset( $body['item']['id'] ) && $body['item']['id'] != $item_id ) {
					return esc_html__( 'Envato error: Your purchase code is valid but it seems its for another item, Please add correct purchase code.', 'xtra' );
				}

				// Compatibility with envato plugin.
				update_option( 'envato_purchase_code_' . $body['item']['id'], $code );

				// Save data for codevz.
				update_option( $this->option, [
					'type'			=> 'success',
					'themeforest'	=> true,
					'item_id' 		=> $body['item']['id'],
					'purchase_code' => $code,
					'purchase_date' => $body[ 'sold_at' ],
					'support_until' => $body[ 'supported_until' ]
				] );

				return true;

			} else {

				// XtraTheme verify purchase
				$verify = wp_remote_get( 'https://xtratheme.com?type=register&domain=' . $this->get_host_name() . '&code=' . $code );

				if ( is_wp_error( $verify ) ) {

					return $verify->get_error_message();

				} else if ( ! isset( $verify['body'] ) ) {

					return esc_html__( 'Something went wrong, Please try again in 10 seconds.', 'xtra' );

				} else {

					$verify = json_decode( $verify['body'], true );

					if ( isset( $verify['type'] ) && $verify['type'] === 'error' ) {
						return $verify['message'];
					}

					if ( ! isset( $verify['purchase_code'] ) ) {

						return esc_html__( 'Your license not found in our database, Please check your license and try again ...', 'xtra' );

					}

				}

				// Registered successfully.
				update_option( $this->option, $verify );

				return true;

			}

		}

		/**
		 * Get current site host name.
		 * 
		 * @return string
		 */
		public function get_host_name( $url = '' ) {

			$pieces = parse_url( $url ? $url : get_site_url() );

			$domain = isset( $pieces['host'] ) ? $pieces['host'] : '';

			if ( preg_match( '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs ) ) {
				return $regs['domain'];
			}

			return $domain;

		}

		/**
		 * Plugin installation and importer AJAX function.
		 * @return string
		 */
		public function wizard() {

			check_ajax_referer( 'xtra-wizard', 'nonce' );

			// Import posts meta.
			if ( ! empty( $_POST[ 'meta' ] ) ) {

				wp_send_json(
					Codevz_Demo_Importer::import_process(
						[ 'meta' => 1 ]
					)
				);

			}

			// Check name.
			if ( empty( $_POST[ 'name' ] ) ) {

				wp_send_json(
					[
						'status' 	=> '202',
						'message' 	=> esc_html__( 'AJAX requested name is empty, Please try again.', 'xtra' )
					]
				);

			}

			// Fix redirects after plugin installation.
			if ( $_POST[ 'name' ] === 'redirect' ) {

				wp_send_json(
					[
						'status' 	=> '200',
						'message' 	=> esc_html__( 'Redirected successfully.', 'xtra' )
					]
				);

			}

			// Vars.
			$data = [];
			$name = $_POST[ 'name' ];
			$type = $_POST[ 'type' ];
			$demo = $_POST[ 'demo' ];

			// Install & activate plugin.
			if ( $type === 'plugin' ) {

				$data = $this->install_plugin( $name );

				if ( is_string( $data ) ) {

					$data = [

						'status' 	=> '202',
						'message' 	=> sprintf( esc_html__( 'Could not find plugin "%s" API, Please refresh page and try again.', 'xtra' ), $name )

					];

				}

			// Download demo files.
			} else if ( $type === 'download' ) {

				// Check codevz plus.
				if ( ! class_exists( 'Codevz_Demo_Importer' ) ) {

					wp_send_json(
						[
							'status' 	=> '202',
							'message' 	=> esc_html__( 'Codevz plus plugin is not installed or activated.', 'xtra' )
						]
					);

				}

				$data = Codevz_Demo_Importer::download( $demo, $_POST[ 'folder' ] );

			// Import demo data.
			} else if ( $type === 'import' ) {

				$data = Codevz_Demo_Importer::import_process(
					[
						'demo' 			=> $demo,
						'features' 		=> [ $name ],
						'posts' 		=> empty( $_POST[ 'posts' ] ) ? 1 : $_POST[ 'posts' ]
					]
				);

			// Uninstall demo data.
			} else if ( $type === 'uninstall' ) {

				$data = $this->uninstall_demo( $demo );

			} else {

				$data = [
					'status' 	=> '202',
					'message' 	=> esc_html__( 'An error has occured, Please try again.', 'xtra' )
				];

			}

			wp_send_json( $data );

		}

		/**
		 * Plugin installation and activation process.
		 * 
		 * @return array
		 */
		protected function install_plugin( $plugin = '' ) {

			// Plugin slug.
			$slug = esc_html( urldecode( $plugin ) );

			// Check plugin inside plugins.
			if ( ! isset( $this->plugins[ $slug ] ) ) {

				return [

					'status' 	=> '202',
					'message' 	=> sprintf( esc_html__( 'Plugin "%s" is no listed as a valid plugin.', 'xtra' ), $slug )

				];

			}

			// Pass necessary information via URL if WP_Filesystem is needed.
			$url = wp_nonce_url(
				add_query_arg(
					array(
						'plugin' 	=> urlencode( $slug )
					),
					admin_url( 'admin-ajax.php' )
				),
				'xtra-wizard',
				'nonce'
			);

			if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), '', false, false, [] ) ) ) {

				return [

					'status' 	=> '202',
					'message' 	=> esc_html__( 'WP_Filesystem required FTP login details', 'xtra' )

				];

			}

			// Setup WP_Filesystem.
			if ( ! WP_Filesystem( $creds ) ) {

				request_filesystem_credentials( esc_url_raw( $url ), '', true, false, [] );

				return [

					'status' 	=> '202',
					'message' 	=> esc_html__( 'Could not connect to server FTP with WP_Filesystem function.', 'xtra' )

				];

			}

			// Prep variables for Plugin_Installer_Skin class.
			if ( isset( $this->plugins[ $slug ][ 'source' ] ) ) {
				$api = null;
				$source = $this->plugins[ $slug ][ 'source' ];
			} else {
				$api = $this->plugins_api( $slug );
				if ( is_string( $api ) ) {
					return [

						'status' 	=> '202',
						'message' 	=> esc_html__( 'WordPress API Error:', 'xtra' ) . ' ' . $api

					];
				}
				$source = isset( $api->download_link ) ? $api->download_link : '';
			}

			// Check ZIP file.
			if ( ! $source ) {

				return [

					'status' 	=> '202',
					'message' 	=> sprintf( esc_html__( 'Could not download "%s" plugin ZIP file, Please go to Appearance > Install Plugins and install it manually, and try again demo importer.', 'xtra' ), $slug )

				];

			}

			$url = add_query_arg(
				array(
					'plugin' => urlencode( $slug )
				),
				'update.php'
			);

			if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}

			$skin_args = array(
				'type'   => 'web',
				'title'  => $this->plugins[ $slug ]['name'],
				'url'    => esc_url_raw( $url ),
				'nonce'  => 'xtra-wizard',
				'plugin' => '',
				'api'    => $source ? null : $api,
				'extra'  => [ 'slug' => $slug ]
			);

			$skin = new Plugin_Installer_Skin( $skin_args );

			// Create a new instance of Plugin_Upgrader.
			$upgrader = new Plugin_Upgrader( $skin );

			// File path.
			$file = $this->plugin_file( $slug, true );

			// Install plugin.
			if ( ! file_exists( $file ) ) {

				$upgrader->install( $source );

			}

			// Install plugin manually.
			if ( ! file_exists( $file ) ) {

				$plugin_dir = dirname( $file );

				// Final check if plugin installed?
				if ( ! file_exists( $file ) || is_dir( $plugin_dir ) ) {

					return [

						'status' 	=> '202',
						'message' 	=> sprintf( esc_html__( 'Error, Through FTP delete plugins > "%s" folder & increase PHP max_execution_time to 300 then try again.', 'xtra' ), $slug )

					];

				}

			}

			if ( ! $this->plugin_is_active( $slug ) ) {

				// Activate plugin.
				$activate = activate_plugin( $this->plugin_file( $slug ) );

				// Check activation error.
				if ( is_wp_error( $activate ) ) {

					return [

						'status' 	=> '202',
						'message' 	=> esc_html__( 'Plugin activation error, ', 'xtra' ) . $activate->get_error_message()

					];

				}

			}

			return [

				'status' 	=> '200',
				'message' 	=> sprintf( esc_html__( 'Plugin "%s" installed and activated successfully.', 'xtra' ), $slug )

			];

		}

		/**
		 * Try to grab information from WordPress API.
		 *
		 * @param string $slug Plugin slug.
		 * @return object Plugins_api response object on success, WP_Error on failure.
		 */
		protected function plugins_api( $slug ) {

			static $api = [];

			if ( ! isset( $api[ $slug ] ) ) {

				if ( ! function_exists( 'plugins_api' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				}

				$response = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );

				$api[ $slug ] = false;

				if ( is_wp_error( $response ) ) {
					return esc_html__( 'Plugin API error:', 'xtra' ) . ' ' . $response->get_error_message();
				} else {
					$api[ $slug ] = $response;
				}

			}

			return $api[ $slug ];

		}

		/**
		 * Check if plugin is active with file_exists function.
		 *
		 * @param string $slug Plugin slug.
		 * @return bool
		 */
		private function plugin_file( $slug, $full_path = false ) {

			if ( $slug === 'contact-form-7' ) {
				$file = 'wp-contact-form-7';
			} else {
				$file = $slug;
			}

			return $full_path ? WP_PLUGIN_DIR . '/' . $slug . '/' . $file . '.php' : $slug . '/' . $file . '.php';

		}

		/**
		 * Check if plugin is active with file_exists function.
		 *
		 * @param string $slug Plugin slug.
		 * @return bool
		 */
		private function plugin_is_active( $slug ) {

			if ( isset( $this->plugins[ $slug ][ 'class_exists' ] ) && class_exists( $this->plugins[ $slug ][ 'class_exists' ] ) ) {

				return true;

			} else if ( isset( $this->plugins[ $slug ][ 'function_exists' ] ) && function_exists( $this->plugins[ $slug ][ 'function_exists' ] ) ) {

				return true;

			}

			return false;

		}

		/**
		 * Retrieves the attachment ID from the file URL
		 * 
		 * @return array
		 */
		private function get_attachment_id_by_url( $url ) {

			global $wpdb;

			$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ) ); 

			return isset( $attachment[ 0 ] ) ? $attachment[ 0 ] : false; 
		}

		/**
		 * Uninstall imported demo data.
		 * 
		 * @return array
		 */
		private function uninstall_demo( $demo ) {

			$data = get_option( 'xtra_uninstall_' . $demo );

			if ( is_array( $data ) ) {

				foreach( $data as $type => $items ) {

					switch( $type ) {

						case 'options':

							delete_option( 'codevz_theme_options' );

							break;

						case 'posts':

							// Delete posts.
							foreach( $items as $item ) {

								if ( ! empty( $item[ 'id' ] ) && sanitize_title_with_dashes( get_the_title( $item[ 'id' ] ) ) === sanitize_title_with_dashes( $item[ 'title' ] ) ) {

									wp_delete_post( $item[ 'id' ], true );

								}

							}

							break;

						case 'attachments':

							foreach( $items as $item ) {

								if ( ! empty( $item[ 'id' ] ) && sanitize_title_with_dashes( get_the_title( $item[ 'id' ] ) ) === sanitize_title_with_dashes( $item[ 'title' ] ) ) {

									wp_delete_attachment( $item[ 'id' ], true );

								}

							}

							break;

						case 'terms':

							foreach( $items as $item ) {

								if ( ! empty( $item[ 'id' ] ) ) {

									wp_delete_term( $item[ 'id' ], $item[ 'taxonomy' ] );

								}

							}

							break;

						case 'sliders':

							if ( class_exists( 'RevSliderSlider' ) ) {

								foreach( $items as $item ) {

									$slider	= new RevSliderSlider();
									$slider->init_by_id( $item[ 0 ] );
									$slider->delete_slider();

								}

							}

							break;

					}

				}

				delete_option( 'xtra_uninstall_' . $demo );

				// Reset colors.
				delete_option( 'codevz_primary_color' );
				delete_option( 'codevz_secondary_color' );

				// Reset widgets.
				update_option( 'sidebars_widgets', [] );

				// Success.
				wp_send_json(
					[
						'status' 	=> '200',
						'message' 	=> sprintf( esc_html__( 'Demo "%s" uninstalled successfully.', 'xtra' ), $demo )
					]
				);

			} else {

				wp_send_json(
					[
						'status' 	=> '202',
						'message' 	=> sprintf( esc_html__( 'Could not uninstall "%s" demo.', 'xtra' ), $demo )
					]
				);

			}

		}

	}

	// Run dashboard.
	Xtra_Dashboard::instance();

}
