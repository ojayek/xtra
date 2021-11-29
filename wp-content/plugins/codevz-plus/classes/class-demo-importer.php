<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

/**
 * Demo importer
 * 
 * @author XtraTheme
 * @link https://xtratheme.com
 */

class Codevz_Demo_Importer {

	public function __construct() {

		add_action( 'wp_ajax_attachment_importer_upload', [ $this, 'attachment_importer_uploader' ] );

		// Enable upload webp for importer, limited time.
		if ( get_transient( 'xtra_importer_webp' ) ) {

			add_filter( 'upload_mimes', [ $this, 'upload_mimes' ] );
			add_filter( 'wp_check_filetype_and_ext', [ $this, 'wp_check_filetype_and_ext' ], 10, 4 );

		}

	}

	/**
	 * Save requested demo data to database for future unistall.
	 * 
	 * @since 4.3.0
	 */
	public static function save( $key, $value ) {

		$demo = get_option( 'xtra-downloaded-demo' );

		$option = (array) get_option( 'xtra_uninstall_' . $demo );

		if ( $key && $value ) {

			$option[ $key ][] = $value;

			update_option( 'xtra_uninstall_' . $demo, $option );

		}

	}

	/**
	 * Replace demo URL with site home URL.
	 * 
	 * @since 4.3.0
	 * @return array
	 */
	public static function replace_demo_link( $data = '', $encoded = false, $only_urls = false, $custom_folder = '' ) {

		$demo = get_option( 'xtra-downloaded-demo' );
		$folder = get_option( 'xtra-downloaded-folder' );
		$folder = $folder ? $folder . '/' : $custom_folder;

		if ( $folder === 'rtl/' ) {
			$folder = 'arabic/';
		} else if ( $folder === 'rtl-elementor/' ) {
			$folder = 'arabic-elementor/';
		}

		if ( strpos( $data, 'http' ) === 0 ) {

			if ( ! Codevz_Plus::contains( $data, [ '.jpg', '.webp', '.png', '.gif', '.jpeg', '.mp4', '.mp3' ] ) ) {

				$data = trailingslashit( $data );

			}

		}

		$demo_url = str_replace( '/api', '', Codevz_Plus::$api ) . $folder . $demo . '/';

		$site_url = trailingslashit( get_home_url() );

		if ( $encoded ) {

			$demo_url = str_replace( '/', '\/', $demo_url );
			$site_url = str_replace( '/', '\/', $site_url );

			$folder = $folder ? '\/' . str_replace( '/', '', $folder ) : str_replace( '/', '', $custom_folder );

		} else {
			$folder = $folder ? '/' . str_replace( '/', '', $folder ) : str_replace( '/', '', $custom_folder );
		}

		if ( $only_urls ) {
			return [ $demo_url, $site_url ];
		}

		$dm = $demo_url;
		$dmf = str_replace( $folder, '', $dm );
		$dmp = str_replace( 'https', 'http', $dm );
		$dmpf = str_replace( $folder, '', $dmp );

		// HTTPS
		$data = str_replace( $dm, $site_url, $data );
		$data = str_replace( $dmf, $site_url, $data );

		// HTTP
		$data = str_replace( $dmp, $site_url, $data );
		$data = str_replace( $dmpf, $site_url, $data );

		// WPBakery links.
		$data = str_replace( urlencode( $dm ), urlencode( $site_url ), $data );
		$data = str_replace( urlencode( $dmf ), urlencode( $site_url ), $data );
		$data = str_replace( urlencode( $dmp ), urlencode( $site_url ), $data );
		$data = str_replace( urlencode( $dmpf ), urlencode( $site_url ), $data );

		return $data;

	}

	public static function replace_upload_url( $data, $encoded = true ) {

		$baseurl = wp_upload_dir();
		$baseurl = empty( $baseurl['baseurl'] ) ? 0 : $baseurl['baseurl'];

		if ( $encoded ) {

			$baseurl = str_replace( '/', '\/', $baseurl );

			$pattern = '~https?:\/\/[a-zA-Z\-\.\/0-9]*sites\/\d{1,}|https?:\\\/\\\/[a-zA-Z\-\.\\\/0-9]*sites\\\/\d{1,}~';

		} else {

			$pattern = '~https?://[a-zA-Z\-\./0-9]*sites\/\d{1,}|https?://[a-zA-Z\-\./0-9]*sites/\d{1,}~';

		}

		return preg_replace( $pattern, $baseurl, $data );

	}

	/**
	 * Replace colors in theme options for duplicated different demos.
	 * 
	 * @return array
	 */
	private static function replace_colors( $data, $o = '', $n = '' ) {

		$old_rgb = Codevz_Options::hex2rgb( $o );
		$new_rgb = Codevz_Options::hex2rgb( $n );
		$old_rgb_s = Codevz_Options::hex2rgb( $o, 1 );
		$new_rgb_s = Codevz_Options::hex2rgb( $n, 1 );

		// Theme options.
		return str_replace( array( $o, $old_rgb, $old_rgb_s ), array( $n, $new_rgb, $new_rgb_s ), $data );

	}

	/**
	 * Importer Process
	 * 
	 * @return array
	 */
	public static function import_process( $args = false ) {

		$code = get_option( 'codevz_theme_activation', 'unknown' );

		if ( isset( $code['purchase_code'] ) ) {
			$code = $code['purchase_code'];
		}

		// Get local demo path
		$path = get_option( 'codevz_demo_path' );

		// Start Importing
		foreach( $args['features'] as $i => $key ) {

			if ( $key === 'images' ) {

				// WooCommerce thumbnails size.
				update_option( 'woocommerce_thumbnail_image_width', '600' );

				// XML.
				return [

					'status' 	=> '200',
					'message' 	=> 'XML file',
					'xml' 		=> file_get_contents( $path . 'content.xml' )

				];

			} else if ( $key === 'options' ) {

				$options = $path . $key . '.txt';
				$options = file_get_contents( $options );
				$options = unserialize( gzuncompress( stripslashes( call_user_func( 'base' . '64' . '_decode', rtrim( strtr( $options, '-_', '+/' ), '=' ) ) ) ) );

				// Site colors.
				$op_color = get_option( 'codevz_primary_color' );
				$os_color = get_option( 'codevz_secondary_color' );
				$np_color = isset( $options['site_color'] ) ? $options['site_color'] : '';
				$ns_color = isset( $options['site_color_sec'] ) ? $options['site_color_sec'] : '';

				// Replace URL's
				$options = json_encode( $options );

				$options = self::replace_upload_url( $options );

				$options = self::replace_demo_link( $options, true );

				// Replace colors.
				if ( $op_color ) {
					$options = self::replace_colors( $options, $np_color, $op_color );
					$np_color = $op_color;
				}
				if ( $os_color ) {
					$options = self::replace_colors( $options, $ns_color, $os_color );
					$ns_color = $os_color;
				}

				// Convert to array.
				$options = json_decode( $options, true );

				// Set logo from old options.
				if ( Codevz_Plus::contains( Codevz_Plus::option( 'logo' ), 'http' ) ) {
					$options[ 'logo' ] = Codevz_Plus::option( 'logo' );
				}

				// Remove unnecessary values.
				$options[ 'seo_meta_tags' ] = '';
				$options[ 'seo_desc' ] = '';
				$options[ 'seo_keywords' ] = '';

				// Import theme options.
				update_option( 'codevz_theme_options', $options );

				// Update private options
				if ( $np_color ) {
					update_option( 'codevz_primary_color', $np_color );
				}
				if ( $ns_color ) {
					update_option( 'codevz_secondary_color', $ns_color );
				}

				// Update new post types
				if ( isset( $options['add_post_type'] ) ) {
					$new_cpt = $options['add_post_type'];
					if ( is_array( $new_cpt ) ) {
						$post_types = array();
						foreach ( $new_cpt as $cpt ) {
							if ( isset( $cpt['name'] ) ) {
								$post_types[] = strtolower( $cpt['name'] );
							}
						}
						update_option( 'codevz_css_selectors', '' );
						update_option( 'codevz_post_types', $post_types );
						update_option( 'codevz_post_types_org', $new_cpt );
					}
				}

				// Save imported options.
				self::save( 'options', [ 'options' => true ] );

				return [

					'status' 	=> '200',
					'message' 	=> 'Options imported successfully.'

				];
				
			} else if ( $key === 'widgets' ) {

				// Delete old widgets
				update_option( 'sidebars_widgets', array() );

				// Import new widgets
				$widgets = $path . $key . '.wie';
				$widgets = file_get_contents( $widgets );

				// Replace URL's
				$widgets = self::replace_upload_url( $widgets );
				$widgets = self::replace_demo_link( $widgets, true );

				if ( $widgets ) {

					self::import_widgets( json_decode( $widgets ) );

				}

				return [

					'status' 	=> '200',
					'message' 	=> 'Widgets imported successfully.'

				];

			} else if ( $key === 'content' && ! empty( $args['posts'] ) ) {

				// Delete woo default pages.
				if ( get_option( 'woocommerce_shop_page_id' ) ) {
					wp_delete_post( get_option( 'woocommerce_shop_page_id' ), true );
					update_option( 'woocommerce_shop_page_id', '' );
				}
				if ( get_option( 'woocommerce_cart_page_id' ) ) {
					wp_delete_post( get_option( 'woocommerce_cart_page_id' ), true );
					update_option( 'woocommerce_cart_page_id', '' );
				}
				if ( get_option( 'woocommerce_checkout_page_id' ) ) {
					wp_delete_post( get_option( 'woocommerce_checkout_page_id' ), true );
					update_option( 'woocommerce_checkout_page_id', '' );
				}
				if ( get_option( 'woocommerce_myaccount_page_id' ) ) {
					wp_delete_post( get_option( 'woocommerce_myaccount_page_id' ), true );
					update_option( 'woocommerce_myaccount_page_id', '' );
				}
				if ( get_option( 'woocommerce_placeholder_image' ) ) {
					wp_delete_attachment( get_option( 'woocommerce_placeholder_image' ), true );
					update_option( 'woocommerce_placeholder_image', '' );
				}

				$refund = get_page_by_path( 'refund_returns' );

				if ( ! empty( $refund->ID ) ) {
					wp_delete_post( $refund->ID, true );
				}

				// Elementor before import.
				if ( get_option( 'elementor_active_kit' ) ) {
					wp_delete_post( get_option( 'elementor_active_kit' ), true );
					update_option( 'elementor_active_kit', '' );
				}

				// CF7 before import.
				if ( ! get_option( 'xtra_importer_deleted_cf7' ) ) {
					
					update_option( 'xtra_importer_deleted_cf7', true );

					$cf7 = get_page_by_title( 'Contact form 1', OBJECT, 'wpcf7_contact_form' );

					if ( ! empty( $cf7->ID ) ) {
						wp_delete_post( $cf7->ID, true );
					}

				}

				// Delete old menus if exists ( FIX duplicated menus )
				$menus = array( 'Primary', 'One Page', 'Secondary', 'Footer', 'Mobile', 'Custom 1', 'Custom 2', 'Custom 3', 'Custom 4', 'Custom 5' );
				if ( $args['posts'] == 1 ) {
					foreach( $menus as $menu ) {
						wp_delete_nav_menu( $menu );
					}
				}

				// Import
				$posts = self::import_content( $path . 'content.xml', (int) $args[ 'posts' ] );

				if ( $posts != 'done' ) {

					return [

						'status' 	=> '200',
						'message' 	=> 'Content imported successfully.',
						'posts' 	=> (int) $posts,
						'xml' 		=> ( (int) $args[ 'posts' ] === 1 ) ? file_get_contents( $path . 'content.xml' ) : false

					];

				}

				// Menus to import and assign
				$locations = get_theme_mod( 'nav_menu_locations' );
				foreach ( $menus as $menu ) {
					$menu_slug = str_replace( ' ', '-', strtolower( $menu ) );
					$menu = get_term_by( 'slug', $menu_slug, 'nav_menu' );
					if ( isset( $menu->term_id ) ) {
						$locations[ $menu_slug ] = $menu->term_id;
					}
				}
				set_theme_mod( 'nav_menu_locations', $locations );

				// Set menus meta's
				$menus = $path . 'menus.txt';
				
				if ( file_exists( $menus ) ) {

					$menus = file_get_contents( $menus );

					$menus = self::replace_upload_url( $menus );

					$menus = json_decode( $menus, true );

					foreach( $menus as $location => $menu ) {

						if ( $location == 'demo_config' ) {
							continue;
						}

						$location = wp_get_nav_menu_items( $location );

						foreach( (array) $location as $item ) {
							if ( isset( $item->title ) && isset( $menu[ $item->title ] ) ) {
								foreach( $menu[ $item->title ] as $key => $value ) {
									update_post_meta( $item->ID, $key, $value );
								}
							}
						}

					}

				}

				// Set home page
				$homepage = get_page_by_title( 'Home' );
				if ( ! empty( $homepage->ID ) ) {
					update_option( 'page_on_front', $homepage->ID );
					update_option( 'show_on_front', 'page' );
				}

				// Set woocommerce shop page
				if ( ! empty( $menus[ 'demo_config'][ 'shop' ] ) ) {
					$shop = get_page_by_title( $menus[ 'demo_config'][ 'shop' ] );
				} else if ( get_page_by_title( 'Shop' ) ) {
					$shop = get_page_by_title( 'Shop' );
				} else if ( get_page_by_title( 'Products' ) ) {
					$shop = get_page_by_title( 'Products' );
				} else if ( get_page_by_title( 'Order' ) ) {
					$shop = get_page_by_title( 'Order' );
				} else if ( get_page_by_title( 'Store' ) ) {
					$shop = get_page_by_title( 'Store' );
				} else if ( get_page_by_title( 'Market' ) ) {
					$shop = get_page_by_title( 'Market' );
				} else if ( get_page_by_title( 'Marketplace' ) ) {
					$shop = get_page_by_title( 'Marketplace' );
				} else if ( get_page_by_title( 'Buy' ) ) {
					$shop = get_page_by_title( 'Buy' );
				} else if ( get_page_by_title( 'Buy Now' ) ) {
					$shop = get_page_by_title( 'Buy Now' );
				} else if ( get_page_by_title( 'Buy Ticket' ) ) {
					$shop = get_page_by_title( 'Buy Ticket' );
				}
				if ( ! empty( $shop->ID ) ) {
					update_option( 'woocommerce_shop_page_id', $shop->ID );
				}

				// Set woocommerce cart page
				$cart = get_page_by_title( ! empty( $menus[ 'demo_config'][ 'cart' ] ) ? $menus[ 'demo_config'][ 'cart' ] : 'Cart' );
				if ( ! empty( $cart->ID ) ) {
					update_option( 'woocommerce_cart_page_id', $cart->ID );
				}

				// Set woocommerce checkout page
				$checkout = get_page_by_title( ! empty( $menus[ 'demo_config'][ 'checkout' ] ) ? $menus[ 'demo_config'][ 'checkout' ] : 'Checkout' );
				if ( ! empty( $checkout->ID ) ) {
					update_option( 'woocommerce_checkout_page_id', $checkout->ID );
				}

				// Set woocommerce my account page
				$my_account = get_page_by_title( ! empty( $menus[ 'demo_config'][ 'account' ] ) ? $menus[ 'demo_config'][ 'account' ] : 'My account' );
				if ( ! empty( $my_account->ID ) ) {
					update_option( 'woocommerce_myaccount_page_id', $my_account->ID );
				}

				// Set elementor kit.
				$kit = get_page_by_title( 'Default Kit', OBJECT, 'elementor_library' );
				if ( ! empty( $kit->ID ) ) {
					update_option( 'elementor_active_kit', $kit->ID );
				}
				$kit = get_page_by_title( 'Xtra Kit', OBJECT, 'elementor_library' );
				if ( ! empty( $kit->ID ) ) {
					update_option( 'elementor_active_kit', $kit->ID );
				}

				// Set homepage.
				if ( ! get_option( 'page_on_front' ) ) {

					if ( ! empty( $menus[ 'demo_config'][ 'home' ] ) ) {
						$homepage = get_page_by_title( $menus[ 'demo_config'][ 'home' ] );
					} else {
						$homepage = get_page_by_path( 'home', OBJECT, 'page' );
					}

					if ( ! empty( $homepage->ID ) ) {
						update_option( 'page_on_front', $homepage->ID );
						update_option( 'show_on_front', 'page' );
					}

				}

				// Set blog page
				if ( ! empty( $menus[ 'demo_config'][ 'blog' ] ) ) {
					$blog = get_page_by_title( $menus[ 'demo_config'][ 'blog' ] );
				} else if ( get_page_by_title( 'Blog' ) ) {
					$blog = get_page_by_title( 'Blog' );
				} else if ( get_page_by_title( 'News' ) ) {
					$blog = get_page_by_title( 'News' );
				} else if ( get_page_by_title( 'Posts' ) ) {
					$blog = get_page_by_title( 'Posts' );
				} else if ( get_page_by_title( 'Article' ) ) {
					$blog = get_page_by_title( 'Article' );
				} else if ( get_page_by_title( 'Articles' ) ) {
					$blog = get_page_by_title( 'Articles' );
				} else if ( get_page_by_title( 'Journal' ) ) {
					$blog = get_page_by_title( 'Journal' );
				}
				if ( ! empty( $blog->ID ) ) {
					update_option( 'page_for_posts', $blog->ID );
				}

				if ( ! get_option( 'page_for_posts' ) ) {

					if ( ! empty( $menus[ 'demo_config'][ 'blog' ] ) ) {
						$blog = get_page_by_title( $menus[ 'demo_config'][ 'blog' ] );
					} else {
						$blog = get_page_by_path( 'blog', OBJECT, 'page' );
					}

					if ( empty( $blog->ID ) ) {

						$blog = get_page_by_path( 'news', OBJECT, 'page' );

						if ( empty( $blog->ID ) ) {

							$blog = get_page_by_path( 'posts', OBJECT, 'page' );

							if ( empty( $blog->ID ) ) {
								
								$blog = get_page_by_path( 'article', OBJECT, 'page' );

								if ( empty( $blog->ID ) ) {
									
									$blog = get_page_by_path( 'articles', OBJECT, 'page' );

									if ( empty( $blog->ID ) ) {
										
										$blog = get_page_by_path( 'journal', OBJECT, 'page' );

									}
								}
							}
						}
						
					}

					if ( ! empty( $blog->ID ) ) {
						update_option( 'page_for_posts', $blog->ID );
					}

				}

				// Update number of posts per page
				update_option( 'posts_per_page', '4' );

				// Fix cf7 for the next time.
				delete_option( 'xtra_importer_deleted_cf7' );


				return [

					'status' 	=> '200',
					'message' 	=> 'Content imported successfully and done.'

				];

			} else if ( $key === 'slider' ) {

				self::import_revslider( $args['demo'], $path );

				return [

					'status' 	=> '200',
					'message' 	=> 'Sliders imported successfully.'

				];

			} else {

				return [

					'status' 	=> '202',
					'message' 	=> 'Feature name error ...'

				];

			}

		}

	}

	/**
	 * Import Content
	 * 
	 * @return array
	 */
	public static function import_content( $file, $posts = 0 ) {
		
		if ( ! defined('WP_LOAD_IMPORTERS') ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

	    require_once ABSPATH . 'wp-admin/includes/import.php';
	    $importer_error = false;

	    if ( ! class_exists( 'WP_Importer' ) ) {

	        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

	        if ( file_exists( $class_wp_importer ) ){
	            require_once( $class_wp_importer );
	        } else {
	            $importer_error = true;
	        }
	    }

		if ( ! class_exists( 'XTRA_WP_Import' ) ) {
			$class_wp_import = dirname( __FILE__ ) .'/class-wp-importer.php';
			if ( file_exists( $class_wp_import ) ) {
				require_once( $class_wp_import );
			} else {
				$importer_error = true;
			}
		}

		if ( $importer_error ) {
			return 'error';
		} else {
			if( ! is_file( $file ) ) {
				return 'error';
			} else {
				$wp_import = new XTRA_WP_Import();
				$wp_import->fetch_attachments = false;
				return $wp_import->import( $file, $posts );
			}
		}

	}

	/**
	 * Save slider images ID durring import.
	 * 
	 * @return -
	 */
	public static function revslider_add_attachment( $post_id ) {

		self::save( 'attachments', [ 'id' => $post_id, 'title' => sanitize_title_with_dashes( get_the_title( $post_id ) ) ] );

	}

	/**
	 * Importing Revolution Slider
	 * 
	 * @return string
	 */
	public static function import_revslider( $demo, $path ) {

		add_action( 'add_attachment', [ 'Codevz_Demo_Importer', 'revslider_add_attachment' ] );

		ob_start();

		$sliders = [];

		foreach( glob( $path . '*.zip' ) as $slider ) {

			if ( class_exists( 'RevSliderSliderImport' ) ) {

				$rs = new RevSliderSliderImport();
				$id = $rs->import_slider( true, $slider );

				if ( isset( $id[ 'sliderID' ] ) ) {

					$slider = pathinfo( $slider );

					$sliders[] = $id[ 'sliderID' ];

				}

			}

		}

		// Save imported sliders.
		self::save( 'sliders', $sliders );

		// Replace links.
		$urls = Codevz_Demo_Importer::replace_demo_link( false, true, true );

		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE " . $wpdb->prefix . "revslider_slides SET layers = replace(layers, '%s', '%s' )",
				$urls[ 0 ],
				$urls[ 1 ]
			)
		);

		// Fix echo.
		$msg = ob_get_clean();

	}

	/**
	 * Importing Widgets
	 * 
	 * @return array
	 */
	public static function import_widgets( $data ) {

		global $wp_registered_sidebars;

		if ( empty( $data ) || ! is_object( $data ) ) {
			return;
		}

		$available_widgets = self::available_widgets();

		// Get all existing widget instances
		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		// Begin results
		$results = array();

		// Loop import data's sidebars
		foreach ( $data as $sidebar_id => $widgets ) {

			// Skip inactive widgets
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			// Check if sidebar is available on this site
			// Otherwise add widgets to inactive, and say so
			if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
				$sidebar_available = true;
				$use_sidebar_id = $sidebar_id;
			} else {
				$sidebar_available = false;
				$use_sidebar_id = 'wp_inactive_widgets';
			}

			// Result for sidebar
			$results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
			$results[$sidebar_id]['widgets'] = array();

			// Loop widgets
			foreach ( $widgets as $widget_instance_id => $widget ) {

				$fail = false;

				// Get id_base (remove -# from end) and instance ID number
				$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				// Does site support this widget?
				if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
					$fail = true;
				}

				// Does widget with identical settings already exist in same sidebar?
				if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

					// Get existing widgets in this sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

					// Loop widgets with ID base
					$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {

						// Is widget in same sidebar and has identical settings?
						if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

							$fail = true;

							break;

						}

					}

				}

				// No failure
				if ( ! $fail ) {

					// Add widget instance
					$single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
					$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
					$single_widget_instances[] = (array) $widget; // add it

					// Get the key it was given
					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );

					// If key is 0, make it 1
					// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number = 1;
						$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}

					// Move _multiwidget to end of array for uniformity
					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}

					// Update option with new widget
					update_option( 'widget_' . $id_base, $single_widget_instances );

					// Assign widget instance to sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
					$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
					$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
					update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

				}

				// Result for widget instance
				$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
				$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = isset( $widget->title ) ? $widget->title : esc_html__( 'No Title', 'codevz' );

			}
		}
	}

	/**
	 * Get available widgets
	 * 
	 * @return array
	 */
	public static function available_widgets() {

		global $wp_registered_widget_controls;
		$widget_controls = $wp_registered_widget_controls;
		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {
			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

				$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
				$available_widgets[$widget['id_base']]['name'] = $widget['name'];

			}
		}

		return $available_widgets;
	}

	/**
	 * Enable webp for demo importer.
	 * 
	 * @return array
	 */
	public function upload_mimes( $mimes ) {

		$mimes['webp'] = 'image/webp';

		return $mimes;

	}

	/**
	 * Enable webp for demo importer.
	 * 
	 * @return array
	 */
	public function wp_check_filetype_and_ext( $types, $file, $filename, $mimes ) {

		// Enable .webp
		if ( strpos( $filename, '.webp' ) !== false ) {

			$types['ext'] = 'webp';
			$types['type'] = 'image/webp';

		}

		return $types;

	}

	/**
	 * Check if file downlaoded successfully and have length
	 * 
	 * @return string
	 */
	public static function file_length( $zip = '' ) {
		return strlen( @file_get_contents( $zip ) ) > 10000;
	}

	/**
	 * Download and extract demo data
	 * 
	 * @return string
	 */
	public static function download( $demo, $folder = '', $try = 1 ) {

		// Enable upload webp for importer, 60 mins.
		if ( ! get_transient( 'xtra_importer_webp' ) ) {
			set_transient( 'xtra_importer_webp', true, 3600 );
		}

		// Save requested demo name.
		update_option( 'xtra-downloaded-demo', $demo );
		update_option( 'xtra-downloaded-folder', $folder );

		// License code.
		$code = get_option( 'codevz_theme_activation' );

		if ( isset( $code['purchase_code'] ) ) {
			$code = $code['purchase_code'];
		}

		// Import stats.
		if ( function_exists( 'wp_get_theme' ) ) {

			$theme = wp_get_theme();

			if ( empty( $theme->parent() ) ) {

				$name 	= $theme->get( 'Name' );
				$ver 	= $theme->get( 'Version' );

			} else {

				$name 	= $theme->parent()->Name;
				$ver 	= $theme->parent()->Version;

			}

			$prms = [

				'api' 		=> Codevz_Plus::$api,
				'plugin' 	=> Codevz_Plus::$ver,
				'theme' 	=> $name,
				'version' 	=> $ver,
				'code' 		=> $code,
				'demo' 		=> $demo,
				'builder' 	=> $folder ? $folder : 'wpbakery',
				'domain' 	=> home_url()

			];

			$data = wp_remote_get( 'http://theme.support/importer-stats/?import=' . json_encode( $prms ) );
			
		}

		// Download URL.
		$remote = Codevz_Plus::$api . '?' . http_build_query(
			[
				'demo' 		=> $demo,
				'code' 		=> $code,
				'folder' 	=> $folder,
				'domain' 	=> $_SERVER[ 'SERVER_NAME' ]
			]
		);

		// Upload directory
		$dir = '/codevz_demo_data/';
		$up = wp_upload_dir();

		// Run wp_filesystem.
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		// Create directory
		if ( isset( $up['basedir'] ) ) {
			$dir = $up['basedir'] . $dir;
			if ( ! file_exists( $dir ) ) {
				wp_mkdir_p( $dir );
			}
			$zip = $dir . $demo . '.zip';
		}

		// Check permission.
		if ( ! is_writable( $dir ) ) {

			return [

				'status' 	=> '202',
				'message' 	=> esc_html__( 'Your uploads folder is not writable, please change it\'s permission to 0777', 'codevz' )

			];

		}

		// Check directory
		if ( Codevz_Plus::contains( $dir, 'codevz_demo_data' ) ) {

			// Download via file_get_contents
			if ( ini_get( 'allow_url_fopen' ) ) {

				$download = file_get_contents( $remote );
				$response = json_decode( $download, true );

				if ( isset( $response['type'] ) && $response['type'] === 'error' ) {

					return [

						'status' 	=> '202',
						'message' 	=> $response['message']

					];

				} else {

					file_put_contents( $zip, $download );

					// Check file, if failed, try copy
					if ( ! self::file_length( $zip ) ) {
						$file = copy( $download, $zip );

						// Check file, if failed, try $wp_filesystem
						if ( ! self::file_length( $zip ) ) {
							$file = $wp_filesystem->copy( $download, $zip, true );
						}
					}

				}

			}

			// Alternative solution, Check and download via cURL
			if ( ! self::file_length( $zip ) ) {

				if ( function_exists( 'curl_init' ) ) {

					$ch = curl_init();
					curl_setopt( $ch, CURLOPT_HEADER, 0 );
					curl_setopt( $ch, CURLOPT_TIMEOUT, 120 );
					curl_setopt( $ch, CURLOPT_URL, $remote );
					curl_setopt( $ch, CURLOPT_FAILONERROR, true );
					curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
					curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
					curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
					curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

					$opt_file = fopen( $zip, "w" );
					curl_setopt( $ch, CURLOPT_FILE, $opt_file );

					curl_exec( $ch );
					curl_close( $ch );

					// Check errors.
					if ( ! self::file_length( $zip ) ) {

						$ch = curl_init();
						curl_setopt( $ch, CURLOPT_URL, $remote );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $ch, CURLOPT_HEADER, false );
						$download = curl_exec( $ch );
						curl_close( $ch );

						$response = json_decode( $download, true );

						if ( isset( $response['type'] ) && $response['type'] === 'error' ) {

							return $response['message'];

						}

					}

				} else {

					return [

						'status' 	=> '202',
						'message' 	=> esc_html__( 'Your server PHP cURL is not enable, Please contact with your hosting support.', 'codevz' )

					];

				}

			}

			// Ensure file fuly downloaded
			if ( ! self::file_length( $zip ) ) {

				return [

					'status' 	=> '202',
					'message' 	=> esc_html__( 'Download failed, Please makes sure your theme and plugins are up to date and If still get the same error, your server blocked PHP for downloading ZIP files, Please contact with your server support.', 'codevz' )

				];

			}

			// Unzip file via $wp_filesystem
			if ( file_exists( $zip ) ) {

				unzip_file( $zip, $dir );
				unlink( $zip );
				update_option( 'codevz_demo_path', $dir . $demo . '/' );

				return [

					'status' 	=> '200',
					'message' 	=> esc_html__( 'Demo file downloaded successfully.', 'codevz' )

				];

			} else {

				if ( $try ) {

					self::download( $demo, $remote, 0 ); // If file doesn't exist, try again

				} else {

					return [

						'status' 	=> '202',
						'message' 	=> esc_html__( 'Something went wrong, Please contact with theme support.', 'codevz' )

					];

				}

			}

		} else if ( $try ) {

			self::download( $demo, $remote, 0 ); // If directory doesn't exist, try again

		}

		return [

			'status' 	=> '202',
			'message' 	=> esc_html__( 'Download demo failed, importer could not create demo folder.', 'codevz' )

		];

	}

	public function attachment_importer_uploader() {

		check_ajax_referer( 'xtra-wizard', 'nonce' );

		if ( empty( $_POST[ 'attachment' ] ) ) {
			wp_die( 'Attachment not found ...' );
		}

		wp_die( json_encode( $this->process_attachment( $_POST[ 'attachment' ], $_POST[ 'attachment' ]['url'] ) ) );

	}

	/**
	 * If fetching attachments is enabled then attempt to create a new attachment
	 *
	 * @param array $post Attachment post details from WXR
	 * @param string $url URL to fetch attachment from
	 * @return int|WP_Error Post ID on success, WP_Error otherwise
	 */
	public function process_attachment( $post, $url ) {

		$pre_process = $this->pre_process_attachment( $post, $url );

		if ( is_wp_error( $pre_process ) ) {
			return array(
				'fatal' => false,
				'type' => 'error',
				'code' => $pre_process->get_error_code(),
				'message' => $pre_process->get_error_message(),
				'text' => sprintf( esc_html__( '%1$s was not uploaded. (%2$s: %3$s)', 'codevz' ), $post['post_title'], $pre_process->get_error_code(), $pre_process->get_error_message() )
			);
		}

		// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
		if ( preg_match( '|^/[\w\W]+$|', $url ) ) {
			$url = rtrim( $this->base_url, '/' ) . $url;
		}

		$upload = $this->fetch_remote_file( $url, $post );

		if ( is_wp_error( $upload ) ) {
			return array(
				'fatal' => ( $upload->get_error_code() == 'upload_dir_error' && $upload->get_error_message() != 'Invalid file type' ? true : false ),
				'type' => 'error',
				'code' => $upload->get_error_code(),
				'message' => $upload->get_error_message(),
				'text' => sprintf( esc_html__( '%1$s could not be uploaded because of an error. (%2$s: %3$s)', 'codevz' ), $post['post_title'], $upload->get_error_code(), $upload->get_error_message() )
			);
		}

		if ( $info = wp_check_filetype( $upload['file'] ) ) {
			$post['post_mime_type'] = $info['type'];
		} else {
			$upload = new WP_Error( 'attachment_processing_error', esc_html__('Invalid file type', 'codevz') );
			return array(
				'fatal' => false,
				'type' => 'error',
				'code' => $upload->get_error_code(),
				'message' => $upload->get_error_message(),
				'text' => sprintf( esc_html__( '%1$s could not be uploaded because of an error. (%2$s: %3$s)', 'codevz' ), $post['post_title'], $upload->get_error_code(), $upload->get_error_message() )
			);
		}

		$post['guid'] = $upload['url'];

		// as per wp-admin/includes/upload.php
		$post_id = wp_insert_attachment( $post, $upload['file'] );
		wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

		// Save imported image.
		self::save( 'attachments', [ 'id' => $post_id, 'title' => sanitize_title_with_dashes( $post['post_title'] ) ] );

		// Set woocommerce placeholder.
		if ( Codevz_Plus::contains( $post['post_title'], 'woocommerce-placeholder' ) ) {
			update_option( 'woocommerce_placeholder_image', $post_id );
		}

		// remap image URL's
		$this->backfill_attachment_urls( $url, $upload['url'] );

		return array(
			'fatal' => false,
			'type' => 'updated',
			'text' => sprintf( esc_html__( '%s was uploaded successfully', 'codevz' ), $post['post_title'] )
		);
	}

	public function pre_process_attachment( $post, $url ){

		global $wpdb;

		$imported = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT post_date_gmt FROM $wpdb->posts WHERE post_type = 'attachment' AND post_title = %s",
				$post[ 'post_title' ]
			)
		);

		if ( $imported ) {

			foreach( $imported as $attachment ) {

				if ( $post[ 'post_date_gmt' ] == $attachment->post_date_gmt ) {

					return new WP_Error( 'duplicate_file_notice', esc_html__( 'File already exists', 'codevz' ) . ' ' . esc_html( $post[ 'post_title' ] ) );

				}

			}

		}

		return false;

	}

	/**
	 * Attempt to download a remote file attachment
	 *
	 * @param string $url URL of item to fetch
	 * @param array $post Attachment details
	 * @return array|WP_Error Local file location details on success, WP_Error otherwise
	 */
	public function fetch_remote_file( $url, $post ) {
		// Extract the file name from the URL.
		$file_name = basename( parse_url( $url, PHP_URL_PATH ) );

		if ( ! $file_name ) {
			$file_name = md5( $url );
		}

		$tmp_file_name = wp_tempnam( $file_name );
		if ( ! $tmp_file_name ) {
			return new WP_Error( 'import_no_file', esc_html__( 'Could not create temporary file.', 'codevz' ) );
		}

		// Fetch the remote URL and write it to the placeholder file.
		$remote_response = wp_safe_remote_get( $url, array(
			'timeout'    => 300,
			'stream'     => true,
			'filename'   => $tmp_file_name,
			'headers'    => array(
				'Accept-Encoding' => 'identity',
			),
		) );

		if ( is_wp_error( $remote_response ) ) {
			@unlink( $tmp_file_name );
			return new WP_Error(
				'import_file_error',
				sprintf(
					/* translators: 1: The WordPress error message. 2: The WordPress error code. */
					esc_html__( 'Request failed due to an error: %1$s (%2$s)', 'codevz' ),
					esc_html( $remote_response->get_error_message() ),
					esc_html( $remote_response->get_error_code() )
				)
			);
		}

		$remote_response_code = (int) wp_remote_retrieve_response_code( $remote_response );

		// Make sure the fetch was successful.
		if ( 200 !== $remote_response_code ) {
			@unlink( $tmp_file_name );
			return new WP_Error(
				'import_file_error',
				sprintf(
					/* translators: 1: The HTTP error message. 2: The HTTP error code. */
					esc_html__( 'Remote server returned the following unexpected result: %1$s (%2$s)', 'codevz' ),
					get_status_header_desc( $remote_response_code ),
					esc_html( $remote_response_code )
				)
			);
		}

		$headers = wp_remote_retrieve_headers( $remote_response );

		// Request failed.
		if ( ! $headers ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', esc_html__('Remote server did not respond', 'codevz') );
		}

		$filesize = (int) filesize( $tmp_file_name );

		if ( 0 === $filesize ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', esc_html__('Zero size file downloaded', 'codevz') );
		}

		if ( ! isset( $headers['content-encoding'] ) && isset( $headers['content-length'] ) && $filesize !== (int) $headers['content-length'] ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', esc_html__('Downloaded file has incorrect size', 'codevz' ) );
		}

		$max_size = (int) apply_filters( 'import_attachment_size_limit', 0 );
		if ( ! empty( $max_size ) && $filesize > $max_size ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', sprintf(esc_html__('Remote file is too large, limit is %s', 'codevz' ), size_format($max_size) ) );
		}

		// Override file name with Content-Disposition header value.
		if ( ! empty( $headers['content-disposition'] ) ) {
			$file_name_from_disposition = $this->get_filename_from_disposition( (array) $headers['content-disposition'] );
			if ( $file_name_from_disposition ) {
				$file_name = $file_name_from_disposition;
			}
		}

		// Set file extension if missing.
		$file_ext = pathinfo( $file_name, PATHINFO_EXTENSION );
		if ( ! $file_ext && ! empty( $headers['content-type'] ) ) {
			$extension = $this->get_file_extension_by_mime_type( $headers['content-type'] );
			if ( $extension ) {
				$file_name = "{$file_name}.{$extension}";
			}
		}

		// Handle the upload like _wp_handle_upload() does.
		$wp_filetype     = wp_check_filetype_and_ext( $tmp_file_name, $file_name );
		$ext             = empty( $wp_filetype['ext'] ) ? '' : $wp_filetype['ext'];
		$type            = empty( $wp_filetype['type'] ) ? '' : $wp_filetype['type'];
		$proper_filename = empty( $wp_filetype['proper_filename'] ) ? '' : $wp_filetype['proper_filename'];

		// Check to see if wp_check_filetype_and_ext() determined the filename was incorrect.
		if ( $proper_filename ) {
			$file_name = $proper_filename;
		}

		if ( ( ! $type || ! $ext ) && ! current_user_can( 'unfiltered_upload' ) ) {
			return new WP_Error( 'import_file_error', esc_html__( 'Sorry, this file type is not permitted for security reasons.', 'codevz' ) );
		}

		$uploads = wp_upload_dir( $post['post_date'] );
		if ( ! ( $uploads && false === $uploads['error'] ) ) {
			return new WP_Error( 'upload_dir_error', $uploads['error'] );
		}

		// Move the file to the uploads dir.
		$file_name     = wp_unique_filename( $uploads['path'], $file_name );
		$new_file      = $uploads['path'] . "/$file_name";
		$move_new_file = copy( $tmp_file_name, $new_file );

		if ( ! $move_new_file ) {
			@unlink( $tmp_file_name );
			return new WP_Error( 'import_file_error', esc_html__( 'The uploaded file could not be moved', 'codevz' ) );
		}

		// Set correct file permissions.
		$stat  = stat( dirname( $new_file ) );
		$perms = $stat['mode'] & 0000666;
		chmod( $new_file, $perms );

		$upload = array(
			'file'  => $new_file,
			'url'   => $uploads['url'] . "/$file_name",
			'type'  => $wp_filetype['type'],
			'error' => false,
		);

		// keep track of the old and new urls so we can substitute them later
		$this->url_remap[$url] = $upload['url'];
		$this->url_remap[$post['guid']] = $upload['url']; // r13735, really needed?
		// keep track of the destination if the remote url is redirected somewhere else
		if ( isset($headers['x-final-location']) && $headers['x-final-location'] != $url )
			$this->url_remap[$headers['x-final-location']] = $upload['url'];

		return $upload;
	}

	/**
	 * Retrieves file extension by mime type.
	 *
	 * @since 0.7.0
	 *
	 * @param string $mime_type Mime type to search extension for.
	 * @return string|null File extension if available, or null if not found.
	 */
	protected function get_file_extension_by_mime_type( $mime_type ) {
		static $map = null;

		if ( is_array( $map ) ) {
			return isset( $map[ $mime_type ] ) ? $map[ $mime_type ] : null;
		}

		$mime_types = wp_get_mime_types();
		$map        = array_flip( $mime_types );

		// Some types have multiple extensions, use only the first one.
		foreach ( $map as $type => $extensions ) {
			$map[ $type ] = strtok( $extensions, '|' );
		}

		return isset( $map[ $mime_type ] ) ? $map[ $mime_type ] : null;
	}

	/**
	 * Parses filename from a Content-Disposition header value.
	 *
	 * As per RFC6266:
	 *
	 *     content-disposition = "Content-Disposition" ":"
	 *                            disposition-type *( ";" disposition-parm )
	 *
	 *     disposition-type    = "inline" | "attachment" | disp-ext-type
	 *                         ; case-insensitive
	 *     disp-ext-type       = token
	 *
	 *     disposition-parm    = filename-parm | disp-ext-parm
	 *
	 *     filename-parm       = "filename" "=" value
	 *                         | "filename*" "=" ext-value
	 *
	 *     disp-ext-parm       = token "=" value
	 *                         | ext-token "=" ext-value
	 *     ext-token           = <the characters in token, followed by "*">
	 *
	 * @since 0.7.0
	 *
	 * @see WP_REST_Attachments_Controller::get_filename_from_disposition()
	 *
	 * @link http://tools.ietf.org/html/rfc2388
	 * @link http://tools.ietf.org/html/rfc6266
	 *
	 * @param string[] $disposition_header List of Content-Disposition header values.
	 * @return string|null Filename if available, or null if not found.
	 */
	protected function get_filename_from_disposition( $disposition_header ) {
		// Get the filename.
		$filename = null;

		foreach ( $disposition_header as $value ) {
			$value = trim( $value );

			if ( strpos( $value, ';' ) === false ) {
				continue;
			}

			list( $type, $attr_parts ) = explode( ';', $value, 2 );

			$attr_parts = explode( ';', $attr_parts );
			$attributes = array();

			foreach ( $attr_parts as $part ) {
				if ( strpos( $part, '=' ) === false ) {
					continue;
				}

				list( $key, $value ) = explode( '=', $part, 2 );

				$attributes[ trim( $key ) ] = trim( $value );
			}

			if ( empty( $attributes['filename'] ) ) {
				continue;
			}

			$filename = trim( $attributes['filename'] );

			// Unquote quoted filename, but after trimming.
			if ( substr( $filename, 0, 1 ) === '"' && substr( $filename, -1, 1 ) === '"' ) {
				$filename = substr( $filename, 1, -1 );
			}
		}

		return $filename;
	}

	/**
	 * Use stored mapping information to update old attachment URLs
	 */
	public function backfill_attachment_urls( $from_url, $to_url ) {

		global $wpdb;

		$from_url = self::replace_demo_link( $from_url );

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)",
				$from_url, $to_url
			)
		);

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s)",
				$from_url, $to_url
			)
		);

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s)",
				str_replace( '/', '\/', $from_url ), $to_url
			)
		);

	}

}

new Codevz_Demo_Importer;
