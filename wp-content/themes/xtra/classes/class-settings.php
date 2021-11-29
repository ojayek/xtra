<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Cannot access pages directly.

/**
 * Import default theme options.
 * 
 * @link https://xtratheme.com/
 */

if ( ! class_exists( 'Xtra_Settings' ) ) {

	class Xtra_Settings {

		// Class instance.
		private static $instance = null;
		
		public function __construct() {
			
			add_action( 'init', [ $this, 'init' ] );

		}

		// Instance.
		public static function instance() {

			if ( self::$instance === null ) {

				self::$instance = new self();

			}
			
			return self::$instance;
		}
		
		// WordPress init.
		public function init() {

			// Get current settings.
			$settings = get_option( 'codevz_theme_options' );

			// Check current settings.
			if ( empty( $settings ) || ! isset( $settings['layout'] ) ) {

				// Set default settings.
				update_option( 'codevz_theme_options', $this->settings() );

			}

		}
		
		// Default theme settings.
		public function settings() {

			return array(
				'layout' => 'none',
				'primary' => 'primary',
				'secondary' => 'secondary',
				'responsive' => true,
				'css' => '',
				'site_color' => '#0045a0',
				'_css_widgets' => 'background-color:rgba(255,255,255,0.01);margin-bottom:35px;border-style:solid;border-width:1px;border-color:#d8d8d8;border-radius:6px;',
				'_css_widgets_headline' => 'font-size:20px;font-weight:700;',
				'_css_logo_css' => 'CDVZtext-transform:uppercase;CDVZ',
				'_css_menu_a_hover_before_header_1' => '_class_menu_fx:cz_menu_fx_left_to_right;',
				'menus_indicator_header_1' => 'fa fa-angle-down',
				'menus_indicator2_header_1' => 'fa fa-angle-right',
				'header_2_left' => 
				array(
					0 => 
					array(
						'element' => 'logo',
						'element_id' => 'header_2_left',
						'logo_width' => '140px',
						'margin' => 
						array(
							'top' => '25px',
							'right' => '',
							'bottom' => '25px',
							'left' => '',
						),
					),
				),
				'header_2_right' => 
				array(
					0 => 
					array(
						'element' => 'search',
						'element_id' => 'header_2_right',
						'margin' => 
						array(
							'top' => '34px',
							'right' => '',
							'bottom' => '34px',
							'left' => '10px',
						),
						'logo_width' => '',
						'menu_location' => '',
						'menu_type' => '',
						'menu_icon' => '',
						'menu_title' => '',
						'sk_menu_icon' => '',
						'image' => '',
						'image_width' => '',
						'image_link' => '',
						'it_text' => '',
						'it_link' => '',
						'sk_it' => '',
						'it_icon' => '',
						'sk_it_icon' => '',
						'search_type' => 'icon_dropdown',
						'search_placeholder' => 'Type a keyword ...',
						'sk_search_title' => '',
						'search_form_width' => '',
						'sk_search_con' => 'background-color:#0045a0;margin-left:-3px;',
						'sk_search_input' => '',
						'search_icon' => '',
						'sk_search_icon' => 'font-size:14px;color:#ffffff;background-color:#0045a0;padding:5px;border-radius:2px;',
						'sk_search_icon_in' => 'color:#000000;',
						'search_cpt' => '',
						'search_count' => '',
						'search_view_all_translate' => '',
						'sk_search_ajax' => 'margin-top:15px;border-style:none;border-radius:5px;box-shadow:none;',
						'inview_position_widget' => 'inview_left',
						'sk_offcanvas' => '',
						'offcanvas_icon' => '',
						'sk_offcanvas_icon' => '',
						'hf_elm_page' => '',
						'sk_hf_elm' => '',
						'hf_elm_icon' => '',
						'sk_hf_elm_icon' => '',
						'shopcart_type' => 'cart_1',
						'sk_shop_count' => '',
						'shopcart_icon' => '',
						'sk_shop_icon' => '',
						'sk_shop_content' => '',
						'line_type' => 'header_line_2',
						'sk_line' => '',
						'btn_title' => '',
						'btn_link' => '',
						'sk_btn' => '',
						'sk_btn_hover' => '',
						'wpml_title' => 'translated_name',
						'wpml_current_color' => '',
						'wpml_background' => '',
						'wpml_color' => '',
						'header_elements' => '',
						'custom' => '',
						'avatar_size' => '',
						'sk_avatar' => '',
						'avatar_link' => '',
						'elm_on_sticky' => '',
					),
					1 => 
					array(
						'element' => 'menu',
						'element_id' => 'header_2_right',
						'margin' => 
						array(
							'top' => '34px',
							'right' => '0px',
							'bottom' => '34px',
							'left' => '0px',
						),
						'logo_width' => '',
						'menu_location' => '',
						'menu_type' => '',
						'menu_icon' => '',
						'menu_title' => '',
						'sk_menu_icon' => '',
						'image' => '',
						'image_width' => '',
						'image_link' => '',
						'it_text' => '',
						'it_link' => '',
						'sk_it' => '',
						'it_icon' => '',
						'sk_it_icon' => '',
						'search_type' => 'icon_dropdown',
						'search_placeholder' => '',
						'sk_search_title' => '',
						'search_form_width' => '',
						'sk_search_con' => '',
						'sk_search_input' => '',
						'search_icon' => '',
						'sk_search_icon' => '',
						'sk_search_icon_in' => '',
						'search_cpt' => '',
						'search_count' => '',
						'search_view_all_translate' => '',
						'sk_search_ajax' => '',
						'inview_position_widget' => 'inview_left',
						'sk_offcanvas' => '',
						'offcanvas_icon' => '',
						'sk_offcanvas_icon' => '',
						'hf_elm_page' => '',
						'sk_hf_elm' => '',
						'hf_elm_icon' => '',
						'sk_hf_elm_icon' => '',
						'shopcart_type' => 'cart_1',
						'sk_shop_count' => '',
						'shopcart_icon' => '',
						'sk_shop_icon' => '',
						'sk_shop_content' => '',
						'line_type' => 'header_line_2',
						'sk_line' => '',
						'btn_title' => '',
						'btn_link' => '',
						'sk_btn' => '',
						'sk_btn_hover' => '',
						'wpml_title' => 'translated_name',
						'wpml_current_color' => '',
						'wpml_background' => '',
						'wpml_color' => '',
						'header_elements' => '',
						'custom' => '',
						'avatar_size' => '',
						'sk_avatar' => '',
						'avatar_link' => '',
						'elm_on_sticky' => '',
					),
				),
				'_css_container_header_2' => 'border-style:solid;border-bottom-width:1px;border-color:#cccccc;',
				'_css_menu_a_header_2' => 'padding:6px 15px;margin-right:0px;margin-left:10px;',
				'_css_menu_a_hover_header_2' => 'color:#ffffff;',
				'_css_menu_a_hover_before_header_2' => '_class_menu_fx:cz_menu_fx_fade_in;width:100%;border-width:0px;border-radius:2px;bottom:0px;left:0px;',
				'menus_indicator_header_2' => 'fa fa-angle-down',
				'_css_menu_ul_header_2' => 'background-color:#0045a0;padding-top:20px;padding-bottom:20px;margin-top:1px;margin-left:30px;border-radius:2px;box-shadow:0px 9px 20px rgba(0,0,0,0.13);',
				'_css_menu_ul_a_header_2' => 'font-size:14px;color:#cecece;',
				'_css_menu_ul_a_hover_header_2' => 'color:#ffffff;',
				'menus_indicator2_header_2' => 'fa fa-angle-right',
				'menus_indicator_header_3' => 'fa fa-angle-down',
				'menus_indicator2_header_3' => 'fa fa-angle-right',
				'smart_sticky' => true,
				'_css_container_header_5' => 'background-color:#ffffff;',
				'menus_indicator_header_5' => 'fa fa-angle-down',
				'menus_indicator2_header_5' => 'fa fa-angle-right',
				'header_4_left' => 
				array(
					0 => 
					array(
						'element' => 'logo',
						'element_id' => 'header_4_left',
						'logo_width' => '120px',
						'margin' => 
						array(
							'top' => '20px',
							'right' => '',
							'bottom' => '20px',
							'left' => '',
						),
					),
				),
				'header_4_right' => 
				array(
					0 => 
					array(
						'element' => 'menu',
						'element_id' => 'header_4_right',
						'logo_width' => '',
						'menu_location' => '',
						'menu_type' => 'offcanvas_menu_right',
						'menu_icon' => '',
						'menu_title' => '',
						'sk_menu_icon' => 'font-size:18px;color:#ffffff;background-color:#0045a0;padding:3px;border-radius:0px;',
						'image' => '',
						'image_width' => '',
						'image_link' => '',
						'it_text' => '',
						'it_link' => '',
						'sk_it' => '',
						'it_icon' => '',
						'sk_it_icon' => '',
						'search_type' => 'icon_dropdown',
						'search_placeholder' => '',
						'sk_search_title' => '',
						'search_form_width' => '',
						'sk_search_con' => '',
						'sk_search_input' => '',
						'search_icon' => '',
						'sk_search_icon' => '',
						'sk_search_icon_in' => '',
						'search_cpt' => '',
						'search_count' => '',
						'search_view_all_translate' => '',
						'sk_search_ajax' => '',
						'inview_position_widget' => 'inview_right',
						'sk_offcanvas' => '',
						'offcanvas_icon' => '',
						'sk_offcanvas_icon' => '',
						'hf_elm_page' => '',
						'sk_hf_elm' => '',
						'hf_elm_icon' => '',
						'sk_hf_elm_icon' => '',
						'shopcart_type' => 'cart_1',
						'sk_shop_count' => '',
						'shopcart_icon' => '',
						'sk_shop_icon' => '',
						'sk_shop_content' => '',
						'line_type' => 'header_line_2',
						'sk_line' => '',
						'btn_title' => '',
						'btn_link' => '',
						'sk_btn' => '',
						'sk_btn_hover' => '',
						'wpml_title' => 'translated_name',
						'wpml_current_color' => '',
						'wpml_background' => '',
						'wpml_color' => '',
						'header_elements' => '',
						'custom' => '',
						'avatar_size' => '',
						'sk_avatar' => '',
						'avatar_link' => '',
						'elm_on_sticky' => '',
						'margin' => 
						array(
							'top' => '28px',
							'right' => '',
							'bottom' => '',
							'left' => '',
						),
					),
				),
				'_css_container_header_4' => 'border-style:solid;border-width:0 0 1px;border-color:#f4f4f4;',
				'_css_menu_a_header_4' => 'color:rgba(0,0,0,0.6);',
				'_css_menu_a_hover_header_4' => 'color:#0045a0;',
				'menus_indicator_header_4' => 'fa fa-angle-down',
				'_css_menu_ul_a_header_4' => 'color:#606060;',
				'_css_menu_ul_a_hover_header_4' => 'color:#3f51b5;',
				'menus_indicator2_header_4' => 'fa fa-angle-down',
				'page_cover' => 'title',
				'page_title' => '6',
				'breadcrumbs_home_icon' => 'fa fa-home',
				'breadcrumbs_separator' => 'fa fa-angle-right',
				'_css_page_title' => 'background-color:#0045a0;padding-top:10px;padding-bottom:10px;border-style:solid;border-width:0 0 1px;border-color:#f4f4f4;',
				'_css_page_title_color' => 'font-size:24px;color:#ffffff;padding-bottom:10px;padding-top:10px;',
				'_css_page_title_breadcrumbs_color' => 'color:#e8e8e8;',
				'_css_breadcrumbs_inner_container' => 'margin-top:12px;margin-right:10px;',
				'_css_footer' => 'background-color:#0045a0;padding-top:60px;padding-bottom:50px;',
				'_css_footer_widget' => 'color:#ffffff;padding:10px 10px 10px 10px;',
				'_css_footer_a' => 'font-size:13px;color:#ffffff;line-height: 2;',
				'_css_footer_a_hover' => 'color:#c6c6c6;',
				'footer_2_center' => 
				array(
					0 => 
					array(
						'element' => 'icon',
						'element_id' => 'footer_2_center',
						'header_elements' => '',
						'custom' => '',
						'margin' => 
						array(
							'top' => '30px',
							'right' => '',
							'bottom' => '25px',
							'left' => '',
						),
						'logo_width' => '',
						'menu_location' => 'primary',
						'menu_type' => '',
						'menu_icon' => '',
						'menu_title' => '',
						'sk_menu_icon' => '',
						'image' => '',
						'image_width' => '',
						'image_link' => '',
						'it_text' => esc_html__( 'Copyright © All Rights Reserved.', 'xtra' ),
						'it_link' => '',
						'sk_it' => 'font-size:15px;color:rgba(255,255,255,0.8);',
						'it_icon' => '',
						'sk_it_icon' => '',
						'search_type' => 'form',
						'search_placeholder' => '',
						'sk_search_title' => '',
						'search_form_width' => '',
						'sk_search_con' => '',
						'sk_search_input' => '',
						'search_icon' => '',
						'sk_search_icon' => '',
						'sk_search_icon_in' => '',
						'search_cpt' => '',
						'search_count' => '',
						'search_view_all_translate' => '',
						'sk_search_ajax' => '',
						'inview_position_widget' => 'inview_left',
						'sk_offcanvas' => '',
						'offcanvas_icon' => '',
						'sk_offcanvas_icon' => '',
						'sk_offcanvas_icon_hover' => '',
						'hf_elm_page' => '',
						'sk_hf_elm' => '',
						'hf_elm_icon' => '',
						'sk_hf_elm_icon' => '',
						'shopcart_type' => 'cart_1',
						'sk_shop_count' => '',
						'shopcart_icon' => '',
						'sk_shop_icon' => '',
						'sk_shop_content' => '',
						'line_type' => 'header_line_2',
						'sk_line' => '',
						'btn_title' => '',
						'btn_link' => '',
						'sk_btn' => '',
						'sk_btn_hover' => '',
						'wpml_title' => 'translated_name',
						'wpml_current_color' => '',
						'wpml_background' => '',
						'wpml_color' => '',
						'avatar_size' => '',
						'sk_avatar' => '',
						'avatar_link' => '',
						'elm_on_sticky' => '',
					),
				),
				'_css_container_footer_2' => 'background-color:#0045a0;',
				'_css_backtotop' => 'color:#ffffff;background-color:#0045a0;border-style:none;border-width:0px;border-radius:10px;',
				'_css_cf7_beside_backtotop' => 'color:#0045a0;margin-right:3px;border-style:none;border-radius:50px 0 0 50px ;box-shadow:0px 0px 10px rgba(0,0,0,0.15);',
				'meta_data_post' => 
				array(
					0 => 'image',
					1 => 'mbot',
					2 => 'cats',
					3 => 'tags',
					4 => 'author_box',
					5 => 'next_prev',
				),
				'related_post' => 'You May Also Like ...',
				'slug_portfolio' => 'portfolio',
				'title_portfolio' => 'Portfolio',
				'cat_portfolio' => 'portfolio/cat',
				'tags_portfolio' => 'portfolio/tags',
				'tags_title_portfolio' => 'Tags',
				'meta_data_portfolio' => 
				array(
					0 => 'date',
					1 => 'cats',
					2 => 'tags',
				),
				'related_portfolio' => 'You May Also Like ...',
				'primary_portfolio' => 'primary',
				'secondary_portfolio' => 'secondary',
				'page_coverportfolio' => '1',
				'page_titleportfolio' => '1',
				'_css_footer_widget_headlines' => 'color:#ffffff;font-size:28px;font-weight:100;border-style:solid;border-width:0 0 1px;',
				'page_cover_portfolio' => '1',
				'_css_woo_products_thumbnails' => 'border-style:solid;border-color:rgba(0,0,0,0.27);border-radius:2px;',
				'page_title_portfolio' => '1',
				'page_cover_product' => '1',
				'page_title_product' => '1',
				'woo_col' => '4',
				'_css_woo_products_title' => 'margin-top:15px;',
				'_css_woo_products_stars' => 'display:none;',
				'_css_woo_products_add_to_cart' => 'font-size:14px;font-weight:400;background-color:#0045a0;border-radius:4px;position:absolute;bottom:100px;left:calc(50% - 75px);opacity:0;',
				'_css_woo_products_onsale' => 'font-size:10px;color:#ffffff;font-weight:400;background-color:#079700;top:10px;left:10px;',
				'_css_woo_products_price' => 'font-size:14px;color:#0045a0;background-color:rgba(255,255,255,0.01);top:5px;right:5px;',
				'_css_woo_product_price' => 'color:#0045a0;font-weight:700;',
				'_css_woo_buttons' => 'color:#ffffff;background-color:#0045a0;',
				'_css_woo_buttons_hover' => 'color:#0045a0;background-color:rgba(0,69,160,0.1);',
				'posts_per_page_portfolio' => '6',
				'cf7_beside_backtotop_icon' => 'fa fa-comments-o',
				'readmore' => 'Read More',
				'readmore_icon' => 'fa fa-angle-right',
				'_css_tags_categories_hover' => 'color:#ffffff;background-color:#0045a0;',
				'_css_pagination_li' => 'font-size:14px;color:#0045a0;font-weight:700;padding:0px;margin-right:5px;border-style:solid;border-width:1px;border-color:rgba(0,69,160,0.25);border-radius:4px;',
				'_css_pagination_hover' => 'color:#ffffff;',
				'_css_menu_ul_ul_header_2' => 'margin-top:-20px;margin-left:11px;',
				'related_post_col' => 's4',
				'_css_readmore' => 'color:rgba(255,255,255,0.8);border-radius:3px;',
				'_css_readmore_hover' => 'color:#ffffff;background-color:#0045a0;',
				'columns_portfolio' => '3',
				'template_style_portfolio' => '10',
				'related_portfolio_col' => 's4',
				'related_portfolio_ppp' => '3',
				'woo_template' => '1',
				'woo_related_col' => '3',
				'_css_post_avatar' => 'padding:2px;border-style:solid;border-width:1px;border-color:#cccccc;border-radius:5px;box-shadow:none;CDVZwidth:42pxCDVZ',
				'_css_post_author' => 'font-size:14px;color:#000370;font-weight:600;',
				'_css_post_date' => 'font-size:12px;font-style:italic;',
				'_css_post_title' => 'font-size:28px;font-weight:500;',
				'_css_menu_ul_indicator_a_header_2' => '_class_indicator:fa fa-angle-right;color:#ffffff;',
				'_css_menu_indicator_a_header_2' => '_class_indicator:fa fa-angle-down;',
				'_css_sticky_post' => 'background-color:rgba(167,167,167,0.1);margin-bottom:40px;border-style:solid;border-width:2px;border-color:#000370;border-radius:6px;',
				'_css_overall_post' => 'padding-bottom:40px;margin-bottom:40px;border-style:solid;',
				'_css_post_meta_overall' => 'border-width:0px 0px 0px 6px;border-color:#0045a0;display:inline-block;',
				'_css_related_posts_sec_title' => 'font-size:22px;',
				'_css_single_comments_title' => 'font-size:22px;',
				'_css_next_prev_con' => 'background-color:rgba(255,255,255,0.01);margin-bottom: 35px;border-style: solid;border-width:1px;border-color:#d8d8d8;border-radius:6px;padding:50px;',
				'_css_next_prev_icons' => 'color:#000000;border-style:solid;border-width:1px;border-color:#e5e5e5;border-radius:4px;',
				'_css_next_prev_icons_hover' => 'color:#ffffff;background-color:#0045a0;',
				'_css_next_prev_titles' => 'margin-right:8px;margin-left:8px;',
				'post_excerpt' => '-1',
				'prev_post' => 'Previous',
				'next_post' => 'Next',
				'related_posts_post' => 'Related Posts ...',
				'comments' => 'Comments',
				'cols_portfolio' => 's4',
				'related_posts_portfolio' => 'Related Posts ...',
				'_css_inner_title' => 'font-size:32px;',
				'_css_single_title' => 'font-size:32px;',
				'_css_single_mbot' => 'color:#727272;',
				'_css_single_mbot_i' => 'color:#000370;',
				'primary_buddypress' => 'primary',
				'secondary_buddypress' => 'secondary',
				'page_cover_buddypress' => '1',
				'page_title_buddypress' => '1',
				'lazyload' => true,
				'remove_query_args' => true,
				'vc_disable_modules' => 
				array(
					0 => 'vc_wp_search',
					1 => 'vc_wp_meta',
					2 => 'vc_wp_recentcomments',
					3 => 'vc_wp_calendar',
					4 => 'vc_wp_pages',
					5 => 'vc_wp_tagcloud',
					6 => 'vc_wp_custommenu',
					7 => 'vc_wp_text',
					8 => 'vc_wp_posts',
					9 => 'vc_wp_categories',
					10 => 'vc_wp_archives',
					11 => 'vc_wp_rss',
				),
				'css_out' => '

			/* Theme color */a:hover, .sf-menu > .cz.current_menu > a, .sf-menu > .cz > .current_menu > a, .sf-menu > .current-menu-parent > a {color: #0045a0} 
				button:not(.lg-icon):not(.customize-partial-edit-shortcut-button):not(.vc_general):not(.slick-arrow):not(.slick-dots-btn):not([role="presentation"]):not([aria-controls]),
				form button, button, .button,.sf-menu > .cz > a:before,.widget_product_search #searchsubmit, .post-password-form input[type="submit"], .wpcf7-submit, .submit_user, 
				#commentform #submit, .commentlist li.bypostauthor > .comment-body:after,.commentlist li.comment-author-admin > .comment-body:after, 
				.woocommerce input.button.alt.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,.woocommerce .woocommerce-error .button, .woocommerce .woocommerce-info .button, .woocommerce .woocommerce-message .button, .woocommerce-page .woocommerce-error .button, .woocommerce-page .woocommerce-info .button, .woocommerce-page .woocommerce-message .button,#add_payment_method table.cart input, .woocommerce-cart table.cart input:not(.input-text), .woocommerce-checkout table.cart input,.woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled],#add_payment_method table.cart input, #add_payment_method .wc-proceed-to-checkout a.checkout-button, .woocommerce-cart .wc-proceed-to-checkout a.checkout-button, .woocommerce-checkout .wc-proceed-to-checkout a.checkout-button,.woocommerce #payment #place_order, .woocommerce-page #payment #place_order,.woocommerce input.button.alt,
				.woocommerce #respond input#submit.alt:hover, .pagination .current, .pagination > b, .pagination a:hover, .page-numbers .current, .page-numbers a:hover, .pagination .next:hover, 
				.pagination .prev:hover, input[type=submit], .sticky:before, .commentlist li.comment-author-admin .fn, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce-MyAccount-navigation a:hover, .woocommerce-MyAccount-navigation .is-active a,
				input[type=submit],input[type=button],.cz_header_button,.cz_default_portfolio a, .dwqa-questions-footer .dwqa-ask-question a,
				.cz_readmore, .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, 
				.woocommerce nav.woocommerce-pagination ul li span.current, .cz_btn, 
				.woocommerce .widget_price_filter .ui-slider .ui-slider-range, 
				.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .cz_readmore, .more-link, .search-form .search-submit {background-color: #0045a0}
				textarea:focus, input:focus, .nice-select.open, .nice-select:active, .nice-select:hover, .nice-select:focus {border-color: #0045a0 !important}
				.cs_load_more_doing, div.wpcf7 .wpcf7-form .ajax-loader, .cz_ajax_loader {border-right-color: #0045a0}
				::selection {background-color: #0045a0;color: #fff}::-moz-selection {background-color: #0045a0;color: #fff}

			/* Custom */.home.blog .page_cover { display: none } .home.blog .page_content { margin-top: 40px }.cz_readmore{line-height:1}

			/* Dynamic  */.widget{background-color:rgba(255,255,255,0.01);margin-bottom:35px;border-style:solid;border-width:1px;border-color:#d8d8d8;border-radius:6px}.widget > h4{font-size:20px;font-weight:700}.logo > a, .logo > h1, .logo h2{text-transform:uppercase}.header_2{border-style:solid;border-bottom-width:1px;border-color:#cccccc}#menu_header_2 > .cz > a{padding:6px 15px;margin-right:0px;margin-left:10px}#menu_header_2 > .cz > a:hover,#menu_header_2 > .cz:hover > a,#menu_header_2 > .current_menu > a,#menu_header_2 > .current-menu-parent > a{color:#ffffff}#menu_header_2 > .cz > a:before{width:100%;border-width:0px;border-radius:2px;bottom:0px;left:0px}#menu_header_2 .cz .sub-menu:not(.cz_megamenu_inner_ul),#menu_header_2 .cz_megamenu_inner_ul .cz_megamenu_inner_ul{background-color:#0045a0;padding-top:20px;padding-bottom:20px;margin-top:1px;margin-left:30px;border-radius:2px;box-shadow:0px 9px 20px rgba(0,0,0,0.13)}#menu_header_2 .cz .cz a{font-size:14px;color:#cecece}#menu_header_2 .cz .cz a:hover,#menu_header_2 .cz .cz:hover > a,#menu_header_2 .cz .current_menu > a,#menu_header_2 .cz .current_menu > .current_menu{color:#ffffff}.onSticky{background-color:#ffffff}.header_4{border-style:solid;border-width:0 0 1px;border-color:#f4f4f4}#menu_header_4 > .cz > a{color:rgba(0,0,0,0.6)}#menu_header_4 > .cz > a:hover,#menu_header_4 > .cz:hover > a,#menu_header_4 > .current_menu > a,#menu_header_4 > .current-menu-parent > a{color:#0045a0}#menu_header_4 .cz .cz a{color:#606060}#menu_header_4 .cz .cz a:hover,#menu_header_4 .cz .cz:hover > a,#menu_header_4 .cz .current_menu > a,#menu_header_4 .cz .current_menu > .current_menu{color:#3f51b5}.page_title,.header_onthe_cover .page_title{background-color:#0045a0;padding-top:10px;padding-bottom:10px;border-style:solid;border-width:0 0 1px;border-color:#f4f4f4}.page_title .section_title{font-size:24px;color:#ffffff;padding-bottom:10px;padding-top:10px}.page_title a,.page_title a:hover,.page_title i{color:#e8e8e8}.breadcrumbs{margin-top:12px;margin-right:10px}.cz_middle_footer{background-color:#0045a0;padding-top:60px;padding-bottom:50px}.footer_widget{color:#ffffff;padding:10px 10px 10px 10px}.cz_middle_footer a{font-size:13px !important;color:#ffffff;line-height: 2}.cz_middle_footer a:hover{color:#c6c6c6}.footer_2{background-color:#0045a0}i.backtotop{color:#ffffff;background-color:#0045a0;border-style:none;border-width:0px;border-radius:10px}i.fixed_contact{color:#0045a0;margin-right:3px;border-style:none;border-radius:50px 0 0 50px ;box-shadow:0px 0px 10px rgba(0,0,0,0.15)}.footer_widget > h4{color:#ffffff;font-size:28px;font-weight:100;border-style:solid;border-width:0 0 1px}.woocommerce ul.products li.product a img{border-style:solid;border-color:rgba(0,0,0,0.27);border-radius:2px}.woocommerce ul.products li.product .woocommerce-loop-category__title, .woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3,.woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-category__title, .woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-product__title, .woocommerce.woo-template-2 ul.products li.product h3{margin-top:15px}.woocommerce ul.products li.product .star-rating{display:none}.woocommerce span.onsale, .woocommerce ul.products li.product .onsale{font-size:10px;color:#ffffff;font-weight:400;background-color:#079700;top:10px;left:10px}.woocommerce ul.products li.product .price{font-size:14px;color:#0045a0;background-color:rgba(255,255,255,0.01);top:5px;right:5px}.woocommerce div.product .summary p.price, .woocommerce div.product .summary span.price{color:#0045a0;font-weight:700}.tagcloud a:hover, .cz_post_cat a:hover{color:#ffffff;background-color:#0045a0}.pagination a, .pagination > b, .pagination span, .page-numbers a, .page-numbers span, .woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span{font-size:14px;color:#0045a0;font-weight:700;padding:0px;margin-right:5px;border-style:solid;border-width:1px;border-color:rgba(0,69,160,0.25);border-radius:4px}#menu_header_2 .sub-menu .sub-menu:not(.cz_megamenu_inner_ul){margin-top:-20px;margin-left:11px}.cz_readmore{color:rgba(255,255,255,0.8);border-radius:3px}.cz_readmore:hover{color:#ffffff;background-color:#0045a0}.cz_default_loop .cz_post_author_avatar img{padding:2px;border-style:solid;border-width:1px;border-color:#cccccc;border-radius:5px;box-shadow:none;width:42px}.cz_default_loop .cz_post_author_name{font-size:14px;color:#000370;font-weight:600}.cz_default_loop .cz_post_date{font-size:12px;font-style:italic}.cz_default_loop .cz_post_title h3{font-size:36px;font-weight:700}#menu_header_2 .cz .cz a .cz_indicator{color:#ffffff}.cz_default_loop.sticky > div{background-color:rgba(167,167,167,0.1);margin-bottom:40px;border-style:solid;border-width:2px;border-color:#000370;border-radius:6px}.cz_default_loop > div{padding-bottom:40px;margin-bottom:40px;border-style:solid}.cz_default_loop .cz_post_meta{border-width:0px 0px 0px 6px;border-color:#0045a0;display:inline-block}.single-post .cz_related_posts > h4{font-size:22px}.single-post #comments > h3{font-size:22px}.single-post .next_prev i{color:#000000;border-style:solid;border-width:1px;border-color:#e5e5e5;border-radius:4px}.single-post .next_prev li:hover i{color:#ffffff;background-color:#0045a0}.single-post .next_prev h4{margin-right:8px;margin-left:8px} .content > h3:first-child, .content .section_title{font-size:32px}.single-post h3.section_title{font-size:32px}.single-post .cz_top_meta_i{color:#727272}.single-post .cz_top_meta_i a, .single-post .cz_top_meta_i .cz_post_date{color:#000370}button:not(.lg-icon):not(.customize-partial-edit-shortcut-button):not(.vc_general):not(.slick-arrow):not(.slick-dots-btn):not([role="presentation"]):not([aria-controls]),.dwqa-questions-footer .dwqa-ask-question a,input[type=submit],input[type=button],.button,.cz_header_button,.woocommerce a.button,.woocommerce input.button,.woocommerce #respond input#submit.alt,.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt{border-radius:2px}input,textarea,select,.qty{border-radius:2px}.pagination .current, .pagination > b, .pagination a:hover, .page-numbers .current, .page-numbers a:hover, .pagination .next:hover, .pagination .prev:hover, .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current{color:#ffffff}#menu_header_2 .cz_parent_megamenu > [class^="cz_megamenu_"] > .cz, .cz_parent_megamenu > [class*=" cz_megamenu_"] > .cz{padding-right:10px;padding-left:10px;margin-top:10px;margin-bottom:10px;border-style:solid;border-color:rgba(255,255,255,0.1)}#menu_header_2 .cz .cz h6{color:#ffffff}.cz_default_loop .cz_post_image, .cz_post_svg{border-radius:4px}.cz_default_loop .cz_post_image, .cz_post_svg{border-radius:4px}

.cz_next_prev_posts {background-color:rgba(255,255,255,0.01);margin-bottom: 35px;border-style: solid;border-width:1px;border-color:#d8d8d8;border-radius:6px;padding:50px;}
footer .search-form {background: #fff;padding: 20px}
			/* Responsive */@media screen and (max-width:1170px){#layout{width:100%!important}#layout.layout_1,#layout.layout_2{width:95%!important}.row{width:90% !important;padding:0}blockquote{padding:20px}.slick-slide{margin:0!important}footer .elms_center,footer .elms_left,footer .elms_right,footer .have_center .elms_left, footer .have_center .elms_center, footer .have_center .elms_right{float:none;display:table;text-align:center;margin: 0 auto;flex:unset}}
				@media screen and (max-width:1025px){.header_1,.header_2,.header_3{width: 100%}#layout.layout_1,#layout.layout_2{width:94%!important}#layout.layout_1 .row,#layout.layout_2 .row{width:90% !important}}
				@media screen and (max-width:768px){.header_1,.header_2,.header_3,.header_5,.fixed_side{display: none !important}.header_4,.Corpse_Sticky.cz_sticky_corpse_for_header_4{display: block !important}.header_onthe_cover:not(.header_onthe_cover_dt):not(.header_onthe_cover_all){margin-top: 0 !important}body,#layout{padding: 0 !important;margin: 0 !important}body{overflow-x:hidden}.inner_layout,#layout.layout_1,#layout.layout_2,.col,.cz_five_columns > .wpb_column,.cz_five_columns > .vc_vc_column{width:100% !important;margin:0 !important;border-radius:0}.hidden_top_bar,.fixed_contact,.cz_process_road_a,.cz_process_road_b{display:none!important}.cz_parent_megamenu>.sub-menu{margin:0!important}.is_fixed_side{padding:0!important}.cz_tabs_is_v .cz_tabs_nav,.cz_tabs_is_v .cz_tabs_content{width: 100% !important;margin-bottom: 20px}.wpb_column {margin-bottom: 20px}.cz_fixed_footer {position: static !important}.Corpse_Sticky,.hide_on_tablet{display:none !important}header i.hide,.show_on_tablet{display:block}.cz_grid_item:not(.slick-slide){width:50% !important}.cz_grid_item img{width:auto !important}.cz_mobile_text_center{margin-right:auto;margin-left:auto}.cz_mobile_text_center, .cz_mobile_text_center *{text-align:center !important;float:none !important}.cz_mobile_btn_center{float:none !important;margin-left: auto !important;margin-right: auto !important;display: table !important;text-align: center !important}.vc_row[data-vc-stretch-content] .vc_column-inner[class^=\'vc_custom_\'],.vc_row[data-vc-stretch-content] .vc_column-inner[class*=\' vc_custom_\'] {padding:20px !important;}.wpb_column {margin-bottom: 0 !important;}.vc_row.no_padding .vc_column_container > .vc_column-inner, .vc_row.nopadding .vc_column_container > .vc_column-inner{padding:0 !important;}.cz_posts_container article > div{height: auto !important}.cz_split_box_left > div, .cz_split_box_right > div {width:100%;float:none}.woo-col-3.woocommerce ul.products li.product, .woo-col-3.woocommerce-page ul.products li.product, .woo-related-col-3.woocommerce ul.products .related li.product, .woo-related-col-3.woocommerce-page ul.products .related li.product {width: calc(100% / 2 - 2.6%)}.search_style_icon_full .search{width:86%;top:80px}.vc_row-o-equal-height .cz_box_front_inner, .vc_row-o-equal-height .cz_eqh, .vc_row-o-equal-height .cz_eqh > div, .vc_row-o-equal-height .cz_eqh > div > div, .vc_row-o-equal-height .cz_eqh > div > div > div, .cz_posts_equal > .clr{display:block !important}.cz_a_c.cz_timeline_container:before {left: 0}.cz_timeline-i i {left: 0;transform: translateX(-50%)}.cz_a_c .cz_timeline-content {margin-left: 50px;width: 70%;float: left}.cz_a_c .cz_timeline-content .cz_date{position: static;text-align: left}.cz_posts_template_13 article,.cz_posts_template_14 article{width:100%}}
				@media screen and (max-width:480px){.cz_grid_item img{width:auto !important}.hide_on_mobile,.show_only_tablet,.fixed_contact,.cz_cart_items{display:none}header i.hide,.show_on_mobile{display:block}.offcanvas_area{width:80%}.cz_tab_a,.cz_tabs_content,.cz_tabs_is_v .cz_tabs_nav{box-sizing:border-box;display: block;width: 100% !important;margin-bottom: 20px}.woocommerce ul.products li.product, .woocommerce-page ul.products li.product, .woocommerce-page[class*=columns-] ul.products li.product, .woocommerce[class*=columns-] ul.products li.product,.wpcf7-form p,.cz_default_loop,.cz_post_image,.cz_post_chess_content{width: 100% !important}.cz_post_chess_content{position:static;transform:none}.cz_post_image,.cz_default_grid{width: 100%;margin-bottom:30px !important}.wpcf7-form p {width: 100% !important;margin: 0 0 10px !important}[class^="cz_parallax_"],[class*=" cz_parallax_"]{transform:none !important}th, td {padding: 1px}dt {width: auto}dd {margin: 0}pre{width: 90%}.woocommerce .woocommerce-result-count, .woocommerce-page .woocommerce-result-count,.woocommerce .woocommerce-ordering, .woocommerce-page .woocommerce-ordering{float:none;text-align:center;width:100%}.woocommerce #coupon_code, .coupon input.button {width:100% !important;margin:0 0 10px !important}span.wpcf7-not-valid-tip{left:auto}.wpcf7-not-valid-tip:after{right:auto;left:-41px}.cz_video_popup div{width:fit-content}.cz_grid_item:not(.slick-slide){width:100% !important;margin: 0 !important}.cz_grid_item > div{margin:0 0 10px !important}.cz_grid{width:100% !important;margin:0 !important}.center_on_mobile,.center_on_mobile *{text-align:center !important;float:none !important}.center_on_mobile .cz_wh_left, .center_on_mobile .cz_wh_right {display:block}.center_on_mobile .item_small > a{display:inline-block;margin:2px 0}.center_on_mobile img,.center_on_mobile .cz_image > div{display:table !important;margin-left: auto !important;margin-right: auto !important}.tac_in_mobile{text-align:center !important;float:none !important;display:table;margin-left:auto !important;margin-right:auto !important}.next_prev li {float:none !important;width:100% !important;border: 0 !important;margin-bottom:30px !important}.services.left .service_custom,.services.right .service_custom,.services.left .service_img,.services.right .service_img{float:none;margin:0 auto 20px auto !important;display:table}.services div.service_text,.services.right div.service_text{padding:0 !important;text-align:center !important}.header_onthe_cover_dt{margin-top:0 !important}.alignleft,.alignright{float:none;margin:0 auto 30px}.woocommerce li.product{margin-bottom:30px !important}.woocommerce #reviews #comments ol.commentlist li .comment-text{margin:0 !important}#comments .commentlist li .avatar{left:-20px !important}.services .service_custom i{left: 50%;transform: translateX(-50%)}#commentform > p{display:block;width:100%}blockquote,.blockquote{width:100% !important;box-sizing:border-box;text-align:center;display:table !important;margin:0 auto 30px !important;float:none !important}.cz_related_post{margin-bottom: 30px !important}.right_br_full_container .lefter, .right_br_full_container .righter,.right_br_full_container .breadcrumbs{width:100%;text-align:center}a img.alignleft,a img.alignright{margin:0 auto 30px;display:block;float:none}.cz_popup_in{max-height:85%!important;max-width:90%!important;min-width:0;animation:none;box-sizing:border-box;left:5%;transform:translate(0,-50%)}.rtl .sf-menu > .cz{width:100%}.cz_2_btn a {box-sizing: border-box}.cz_has_year{margin-left:0 !important}.cz_history_1 > span:first-child{position:static !important;margin-bottom:10px !important;display:inline-block}.search-form .search-submit{margin: 0}.page_item_has_children .children, ul.cz_circle_list {margin: 8px 0 8px 10px}ul, .widget_nav_menu .sub-menu, .widget_categories .children, .page_item_has_children .children, ul.cz_circle_list{margin-left: 10px}.dwqa-questions-list .dwqa-question-item{padding: 20px 20px 20px 90px}.dwqa-question-content, .dwqa-answer-content{padding:0}.cz_subscribe_elm button{position:static !important}.cz_hexagon{position: relative;margin: 0 auto 30px}.cz_gallery_badge{right:-10px}.woocommerce table.shop_table_responsive tr td,.woocommerce-page table.shop_table_responsive tr td{display:flow-root !important}.quantity{float:right}}',
				'_css_buttons' => 'border-radius:2px;',
				'_css_input_textarea' => 'border-radius:2px;',
				'404_msg' => 'Page not found!',
				'404_btn' => 'Back to Homepage',
				'layout_post' => 'right',
				'template_style' => '3',
				'not_found' => 'Nothing found',
				'cm_disabled' => 'Comments are disabled.',
				'_css_pagination_li_hover' => 'color:#ffffff;',
				'no_comment' => 'No comment',
				'comment' => 'Comment',
				'search_title_prefix' => 'Search result for:',
				'layout_search' => '1',
				'layout_portfolio' => 'ws',
				'post_excerpt_portfolio' => '20',
				'readmore_portfolio' => 'Read More',
				'readmore_icon_portfolio' => 'fa fa-angle-right',
				'prev_portfolio' => 'Previous',
				'next_portfolio' => 'Next',
				'no_comment_portfolio' => 'No comment',
				'comment_portfolio' => 'Comment',
				'comments_portfolio' => 'Comments',
				'layout_product' => 'ws',
				'fonts_out' => 
				array(
					0 => 'font-family:Open Sans;',
				),
				'_css_menu_inner_megamenu_header_2' => 'padding-right:10px;padding-left:10px;margin-top:10px;margin-bottom:10px;border-style:solid;border-color:rgba(255,255,255,0.1);',
				'_css_menu_ul_a_h6_header_2' => 'color:#ffffff;',
				'_css_post_image' 			=> 'border-radius:4px;',
				'_css_portfolio_image' 		=> 'border-radius:4px;',
				'_css_post_excerpt' 		=> 'font-size:13px;line-height:24px;',
				'backtotop' 				=> '',
				'hover_icon_icon_post' 		=> 'fa fa-link',
				'related_post_ppp' 			=> '0',
				'hover_icon_icon_portfolio' => 'fa fa-link',
				'woo_cart' 					=> 'Cart',
				'woo_checkout' 				=> 'Checkout',
				'woo_no_products' 			=> 'No products in the cart',
				'footer_layout' 			=> 's3,s3,s3,s3'
			);

		}

	}

	// Run.
	Xtra_Settings::instance();

}
