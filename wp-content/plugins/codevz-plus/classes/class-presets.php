<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Elements prests.
 * 
 * @author XtraTheme
 * @link https://xtratheme.com/
 */

if ( ! class_exists( 'Codevz_Presets' ) ) {

	class Codevz_Presets {

		// Instance of this class.
		private static $instance = null;

		// Core functionality.
		public function __construct() {

			add_action( 'wp_ajax_cz_presets', [ $this, 'presets' ] );
			add_action( 'vc_before_init', [ $this, 'append_presets' ] );

		}

		public static function instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Append presets to WPBakery elements as custom tab.
		 * 
		 * @return object
		 */
		public function append_presets() {

			// Presets tab for VC elements
			foreach ( $this->presets_list() as $n => $v ) {

				vc_add_param( $n, [
					'type' 			=> 'cz_presets',
					'param_name' 	=> $n,
					'group' 		=> esc_html__( 'Presets', 'codevz' ),
					'weight'		=> -1,
					'class'			=> ( isset( $v[0] ) && is_string( $v[0] ) ) ? 'czcol_' . $v[0] : ''
				] );

			}

		}

		/**
		 * Presets content by AJAX.
		 * 
		 * @return string
		 */
		public function presets() {

			if ( ! isset( $_GET['type'] ) ) {

				esc_html_e( 'Not found', 'codevz' );

			} else {

				$t = $_GET['type'];

				echo "<link rel='stylesheet' href='" . Codevz_Plus::$url . "assets/css/codevzplus.css' media='all' />";

				echo "<link rel='stylesheet' href='" . Codevz_Plus::$url . "wpbakery/assets/css/" . str_replace( 'cz_', '', $t ) . ".css' media='all' />";

				if ( Codevz_Plus::$is_rtl ) {
					echo "<link rel='stylesheet' href='" . Codevz_Plus::$url . "wpbakery/assets/css/" . str_replace( 'cz_', '', $t ) . ".rtl.css' media='all' />";
				}

				if ( $t === 'cz_2_buttons' || $t === 'cz_service_box' ) {

					echo "<link rel='stylesheet' href='" . Codevz_Plus::$url . "wpbakery/assets/css/button.css' media='all' />";

					if ( Codevz_Plus::$is_rtl ) {
						echo "<link rel='stylesheet' href='" . Codevz_Plus::$url . "wpbakery/assets/css/button.rtl.css' media='all' />";
					}

				}

				foreach( $this->presets_list( $t ) as $p ) {

					if ( ! is_array( $p ) || ! isset( $p['n'] ) || ( isset( $p['n'] ) && $p['n'] == '0' ) ) {
						continue;
					}

					// Format
					$p['e'] = isset( $p['e'] ) ? $p['e'] : 'jpg';

					// Add content and convert all to array
					$c = Codevz_Plus::get_string_between( $p['s'], ']', '[/' );
					$v = shortcode_parse_atts( Codevz_Plus::get_string_between( $p['s'], '[', ']' ) );
					if ( ! empty( $c ) ) {
						$v['content'] = $c;
					}

					// remove shortcode name
					unset( $v[0] );

					// Output
					echo "<div data-num='" . $p['n'] . "' data-shortcode='" . json_encode( $v, JSON_HEX_APOS ) . "'>";

					if ( $p['e'] === 'con' ) {
						echo "<div class='cz_pre_in'>" . do_shortcode( $p['s'] ) . "</div>";
					} else {
						echo "<img src='https://xtratheme.com/img/presets/" . $t . "_" . $p['n'] . "." . $p['e'] . "' />";
					}
					echo "</div>";
				}
			}

			wp_die();
		}

		/**
		 * Premium wpbakery elements presets list.
		 * 
		 * @return string
		 */
		public function presets_list( $n = 'x' ) {

			/* Set theme default color */
			if ( Codevz_Plus::option( 'site_color' ) ) {
				$clr = Codevz_Plus::option( 'site_color' );
			} else {
				$clr = '#000000';
			}

			$p = array(
				'cz_button' => array(
					'2 cz_prevent_title_subtitle_link_icon',
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_show_hidden_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="color:' . $clr . ';font-size:13px;background-color:#ffffff;"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_move_down" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-bottom-style:solid;border-color:' . $clr . ';border-bottom-width:3px;color:' . $clr . ';font-size:13px;background-color:#ffffff;" sk_hover="border-bottom-style:solid;border-color:' . $clr . ';border-bottom-width:8px;"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_move_up" btn_effect="cz_btn_no_fx" scale="1" sk_button="color:' . $clr . ';font-size:13px;background-color:#ffffff;" sk_hover="border-bottom-style:solid;border-color:' . $clr . ';border-bottom-width:3px;margin-top:-3px;"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_center" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_ghost_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="color:' . $clr . ';font-size:18px;background-color:#ffffff;" sk_hover="color:#ffffff;" sk_icon="color:rgba(0,0,0,0.35);font-size:32px;" sk_icon_hover="color:' . $clr . ';"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 12px;color:' . $clr . ';background-color:#ffffff;" sk_hover="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 22px;"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-left-width:12px;color:' . $clr . ';background-color:' . Codevz_Plus::hex2rgba($clr,0.1) . ';" sk_hover="border-left-style:solid;border-color:' . $clr . ';border-left-width:22px;"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="background-color:' . $clr . ';" sk_hover="color:#ffffff;" sk_icon="padding:10px 10px 10px 10px;margin-left:-7px;margin-right:12px;color:' . $clr . ';background-color:#ffffff;"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:100px;background-color:' . $clr . ';" sk_hover="color:#ffffff;" sk_icon="border-radius:50px;padding:10px 10px 10px 10px;margin-left:-7px;margin-right:12px;color:' . $clr . ';background-color:#ffffff;"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_button title="Add to cart" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-shopping-cart" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="color:#ffffff;background-color:' . $clr . ';" sk_hover="" sk_icon="border-right-style:solid;border-color:rgba(255,255,255,0.2);padding-bottom:8px;padding-right:15px;padding-top:8px;border-right-width:1px;margin-right:15px;color:rgba(255,255,255,0.5);" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now" subtitle="From iTunse $0.99" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_out" scale="1" sk_button="border-radius:5px;text-align:left;line-height:16px;background-color:' . $clr . ';" sk_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_icon="border-radius:4px;padding:10px 10px 10px 10px;margin-left:-8px;margin-right:12px;color:' . $clr . ';background-color:#ffffff;" sk_subtitle="color:rgba(255,255,255,0.6);font-size:10px;font-weight:400;"]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_button title="Sign Up Now!" subtitle="Its Lifetime FREE" link="url:%23|||" btn_position="cz_btn_center" icon_position="before" text_effect="cz_btn_txt_move_down" btn_effect="cz_btn_no_fx" alt_title="Register" alt_subtitle="FREE FREE FREE" scale="1" sk_button="border-radius:5px;padding-left:30px;padding-right:30px;text-align:center;font-size:24px;line-height:20px;background-color:unset !important;background-image:linear-gradient(0deg,' . self::adjustBrightness($clr,-95) . ',' . $clr . ');" sk_hover="color:#ffffff;" sk_icon="border-radius:4px;padding:10px 10px 10px 10px;margin-left:-8px;margin-right:12px;background-color:#ffffff;" sk_subtitle="color:rgba(255,255,255,0.6);font-size:12px;font-weight:400;"]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now!" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:70px;padding-left:15px;padding-right:25px;color:' . $clr . ';font-size:18px;box-shadow:0px 8px 20px rgba(0,0,0,0.25) ;background-color:#ffffff;" sk_hover="color:#0d47a1;" sk_icon="border-radius:50px;padding:10px 10px 10px 10px;margin-right:10px;color:' . $clr . ';box-shadow:0px 0px 13px rgba(0,0,0,0.26) inset ;"]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_zoom_in" scale="1" sk_button="font-size:13px;background-color:' . $clr . ';" sk_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_move_up" btn_effect="cz_btn_zoom_in" scale="1" sk_button="background-color:' . $clr . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_ghost_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="font-size:18px;background-color:' . $clr . ';" sk_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_icon="color:rgba(255,255,255,0.41);font-size:46px;" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '16',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="background-color:' . $clr . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '17',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:4px;font-size:13px;background-color:unset !important;background-image:linear-gradient(0deg,' . self::adjustBrightness($clr,-95) . ',' . $clr . ');" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '18',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:4px;font-size:13px;background-color:' . $clr . ';" sk_hover="color:#ffffff;" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '19',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-chevron-right" icon_position="before" text_effect="cz_btn_show_hidden_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:7px;font-size:22px;background-color:unset !important;background-image:linear-gradient(0deg,' . self::adjustBrightness($clr,-95) . ',' . $clr . ',' . self::adjustBrightness($clr,-95) . ');" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '20',
					  'e'  => 'con',
					  's'  => '[cz_button title="Contact Us" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-phone" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="padding-left:30px;padding-right:30px;font-size:22px;background-color:unset !important;background-image:linear-gradient(135deg,#1e73bb,#b40ed6);" sk_hover="color:#ffffff;" sk_icon="margin-right:20px;font-size:24px;"]',
					),
					array(
					  'n'  => '21',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_beat" scale="1" sk_button="border-radius:4px;font-size:13px;background-color:' . $clr . ';" sk_hover="color:#ffffff;" sk_icon="" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '22',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_show_hidden_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:5px;background-color:unset !important;background-image:linear-gradient(0deg,' . $clr . ',' . Codevz_Plus::hex2rgba($clr,0.6) . ');" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '23',
					  'e'  => 'con',
					  's'  => '[cz_button title="Contact Us" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-phone-square" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:7px;padding-left:30px;padding-right:30px;font-size:22px;background-color:unset !important;background-image:linear-gradient(45deg,#1e73bb,#8224e3);" sk_hover="color:#ffffff;" sk_icon="margin-right:15px;font-size:24px;"]',
					),
					array(
					  'n'  => '24',
					  'e'  => 'con',
					  's'  => '[cz_button title="Buy it Now" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-shopping-cart" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_up" scale="1" sk_button="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;color:' . $clr . ';font-size:18px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '25',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_down" scale="1" sk_button="border-bottom-style:solid;border-color:' . self::adjustBrightness($clr,65) . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . self::adjustBrightness($clr,-95) . ';font-size:13px;background-color:#ffffff;" sk_hover="background-color:' . self::adjustBrightness($clr,65) . ';"]',
					),
					array(
					  'n'  => '26',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_right" scale="1" sk_button="border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';background-color:#ffffff;" sk_hover="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '27',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;color:' . $clr . ';font-size:18px;background-color:#ffffff;"]',
					),
					array(
					  'n'  => '28',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_show_hidden_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';font-size:13px;background-color:#ffffff;" sk_hover="color:' . $clr . ';" sk_icon_hover="color:' . $clr . ';"]',
					),
					array(
					  'n'  => '29',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_right" scale="1" sk_button="border-radius:4px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';font-size:13px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '30',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_up" scale="1" sk_button="border-radius:5px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';background-color:#ffffff;" sk_hover="border-radius:50px;color:#ffffff;background-color:unset !important;background-image:linear-gradient(0deg,' . self::adjustBrightness($clr,-95) . ',' . $clr . ');" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '31',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_absorber" scale="1" sk_button="border-radius:6px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;color:' . $clr . ';font-size:18px;background-color:#ffffff;"]',
					),
					array(
					  'n'  => '32',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_ghost_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:6px;border-bottom-style:solid;border-color:' . self::adjustBrightness($clr,-95) . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;color:' . self::adjustBrightness($clr,-95) . ';font-size:18px;background-color:#ffffff;" sk_hover="border-bottom-style:solid;border-color:' . self::adjustBrightness($clr,-95) . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_icon="color:' . Codevz_Plus::hex2rgba($clr,0.2) . ';font-size:46px;" sk_icon_hover="color:' . self::adjustBrightness($clr,65) . ';"]',
					),
					array(
					  'n'  => '33',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:4px;border-color:' . $clr . ';border-width:1px 1px 1px 1px;color:' . $clr . ';font-size:13px;background-color:#ffffff;"]',
					),
					array(
					  'n'  => '34',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_move_down" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';background-color:#ffffff;"]',
					),
					array(
					  'n'  => '35',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_half_to_fill_v" scale="1" sk_button="border-radius:4px;color:' . $clr . ';font-size:13px;background-color:' . $clr . ';" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon="color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '36',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_half_to_fill" scale="1" sk_button="border-radius:0px;color:' . self::adjustBrightness($clr,-95) . ';font-size:13px;background-color:' . Codevz_Plus::hex2rgba($clr,0.4) . ';" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon="color:' . self::adjustBrightness($clr,-95) . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '37',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:40px;font-size:13px;background-color:' . $clr . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '38',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:50px;border-color:' . $clr . ';padding-left:20px;padding-right:20px;border-width:2px 2px 2px 2px;font-size:13px;background-color:' . $clr . ';" sk_hover="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;color:' . $clr . ';background-color:#ffffff;" sk_icon_hover="color:' . $clr . ';"]',
					),
					array(
					  'n'  => '39',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:50px;font-size:18px;background-color:' . $clr . ';" sk_hover="border-radius:3px;color:#ffffff;"]',
					),
					array(
					  'n'  => '40',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_show_hidden_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:50px;background-color:unset !important;background-image:linear-gradient(180deg,' . Codevz_Plus::hex2rgba($clr,0.6) . ',' . $clr . ');" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '41',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_beat" scale="1" sk_button="border-radius:40px;font-size:13px;background-color:unset !important;background-image:linear-gradient(0deg,' . self::adjustBrightness($clr,-95) . ',' . $clr . ');" sk_hover="color:#ffffff;" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '42',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:50px;border-color:#999999;padding-left:25px;padding-right:25px;border-bottom-width:4px;background-color:' . $clr . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '43',
					  'e'  => 'con',
					  's'  => '[cz_button title="Buy it now!" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-shopping-cart" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:50px;padding-left:30px;padding-right:30px;color:' . $clr . ';font-size:18px;box-shadow:0px 8px 20px rgba(0,0,0,0.25) ;background-color:#ffffff;" sk_hover="color:#7100e2;" sk_icon="margin-right:10px;color:' . $clr . ';"]',
					),
					array(
					  'n'  => '44',
					  'e'  => 'con',
					  's'  => '[cz_button title="Contact Us" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-phone-square" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:70px;padding-left:30px;padding-right:30px;font-size:22px;background-color:unset !important;background-image:linear-gradient(135deg,#1e73bb,#b40ed6);" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '45',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_up" scale="1" sk_button="border-radius:40px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';font-size:13px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '46',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:50px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:25px;padding-right:25px;border-width:1px 1px 4px 1px;color:#333333;background-color:#ffffff;" sk_hover="color:#000000;" sk_icon_hover="color:#000000;"]',
					),
					array(
					  'n'  => '47',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_right" scale="1" sk_button="border-radius:50px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:25px;padding-right:25px;border-width:2px 2px 2px 2px;color:' . $clr . ';font-size:18px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '48',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_ghost_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:50px;border-bottom-style:solid;border-color:#000000;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;color:#000000;font-size:18px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:#000000;" sk_icon="margin-top:-3px;color:rgba(0,0,0,0.39);font-size:46px;" sk_icon_hover="color:' . $clr . ';"]',
					),
					array(
					  'n'  => '49',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_right" scale="1" sk_button="border-radius:40px;border-color:' . $clr . ';border-width:1px 1px 1px 1px;color:' . $clr . ';font-size:13px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon="" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '50',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:50px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';background-color:#ffffff;"]',
					),
					array(
					  'n'  => '51',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now!" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:7px;padding-left:25px;padding-right:25px;color:' . $clr . ';font-size:18px;box-shadow:0px 8px 20px rgba(0,0,0,0.25) ;background-color:#ffffff;" sk_hover="color:#0d47a1;" sk_icon="margin-right:10px;color:' . self::adjustBrightness($clr,115) . ';" sk_icon_hover="color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '52',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now!" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:0px;padding-left:25px;padding-right:25px;color:' . $clr . ';font-size:16px;box-shadow:0px 8px 26px rgba(0,0,0,0.2) ;background-color:#ffffff;" sk_hover="color:#000000;" sk_icon="margin-right:10px;color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '53',
					  'e'  => 'con',
					  's'  => '[cz_button title="Join Us!" subtitle="We Are in Facebook" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-facebook" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_absorber" scale="1" sk_button="border-radius:6px;text-align:left;line-height:16px;background-color:#3b5998;" sk_hover="color:#ffffff;" sk_icon="border-radius:4px;padding:10px 14px 10px 14px;margin-left:-7px;margin-right:12px;color:#3b5998;background-color:#ffffff;" sk_subtitle="color:rgba(255,255,255,0.6);font-size:10px;font-weight:400;"]',
					),
					array(
					  'n'  => '54',
					  'e'  => 'con',
					  's'  => '[cz_button title="Follow Us!" subtitle="We Are in Twitter" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-twitter" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_absorber" scale="1" sk_button="border-radius:6px;text-align:left;line-height:16px;background-color:#1dcaff;" sk_hover="color:#ffffff;" sk_icon="border-radius:4px;padding:10px 12px 10px 12px;margin-left:-7px;margin-right:12px;color:#1dcaff;background-color:#ffffff;" sk_subtitle="color:rgba(255,255,255,0.6);font-size:10px;font-weight:400;"]',
					),
					array(
					  'n'  => '55',
					  'e'  => 'con',
					  's'  => '[cz_button title="Find Us!" subtitle="We Are in Linkedin" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-linkedin" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_absorber" scale="1" sk_button="border-radius:6px;text-align:left;line-height:16px;background-color:#0077b5;" sk_hover="color:#ffffff;" sk_icon="border-radius:4px;padding:10px 12px 10px 12px;margin-left:-7px;margin-right:12px;color:#0077b5;background-color:#ffffff;" sk_subtitle="color:rgba(255,255,255,0.6);font-size:10px;font-weight:400;"]',
					),
					array(
					  'n'  => '56',
					  'e'  => 'con',
					  's'  => '[cz_button title="Follow Us!" subtitle="We Are in Pinterest" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-pinterest-p" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_absorber" scale="1" sk_button="border-radius:6px;text-align:left;line-height:16px;background-color:#bd081c;" sk_hover="color:#ffffff;" sk_icon="border-radius:4px;padding:10px 12px 10px 12px;margin-left:-7px;margin-right:12px;color:#bd081c;background-color:#ffffff;" sk_subtitle="color:rgba(255,255,255,0.6);font-size:10px;font-weight:400;"]',
					),
					array(
					  'n'  => '57',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_show_hidden_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:100%;background-color:' . $clr . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '58',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_move_up" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:100%;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '59',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_beat" scale="1" sk_button="border-radius:100%;padding-left:30px;padding-right:30px;background-color:' . $clr . ';" sk_hover="color:#ffffff;background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '60',
					  'e'  => 'con',
					  's'  => '[cz_button title="Contact Us" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-phone-square" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:100%;padding-left:50px;padding-right:50px;font-size:22px;background-color:unset !important;background-image:linear-gradient(90deg,#1a2a6c,#b21f1f,#fdbb2d);" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '61',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:100%;font-size:13px;background-color:' . $clr . ';" sk_hover="color:#ffffff;" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '62',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_down" scale="1" sk_button="border-radius:100%;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '63',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_move_down" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:100%;padding-left:25px;padding-right:25px;background-color:' . $clr . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '64',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_show_hidden_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:100%;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '65',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_ghost_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:30px 0 30px 0;font-size:18px;background-color:unset !important;background-image:linear-gradient(90deg,#ee0979,#ff6a00);" sk_hover="color:#ffffff;" sk_icon="color:rgba(255,255,255,0.41);font-size:46px;" sk_icon_hover="color:' . $clr . ';"]',
					),
					array(
					  'n'  => '66',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_move_up" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:15px 0 15px 0;background-color:' . $clr . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '67',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:15px 0 15px 0;font-size:18px;background-color:unset !important;background-image:linear-gradient(45deg,' . self::adjustBrightness($clr,-95) . ',' . $clr . ',' . self::adjustBrightness($clr,-95) . ');" sk_hover="border-radius:0 15px 0 15px;color:#ffffff;"]',
					),
					array(
					  'n'  => '68',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_out" scale="1" sk_button="border-radius:30px 0 30px 0;font-size:22px;background-color:' . $clr . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '69',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_up" scale="1" sk_button="border-radius:10px 0 10px 0;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';font-size:13px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '70',
					  'e'  => 'con',
					  's'  => '[cz_button title="Download Now" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-cloud-download" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:15px 0 15px 0;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '71',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_right" scale="1" sk_button="border-radius:15px 0 15px 0;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;color:' . $clr . ';font-size:18px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '72',
					  'e'  => 'con',
					  's'  => '[cz_button title="Contact Us" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-phone-square" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:30px 0 30px 0;padding-left:30px;padding-right:30px;font-size:22px;background-color:unset !important;background-image:linear-gradient(135deg,#1e73bb,#b40ed6);" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '73',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:0 10px 0 10px!important;font-size:13px;background-color:' . $clr . ';" sk_hover="color:#ffffff;" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '74',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_move_up" btn_effect="cz_btn_shine" scale="1" sk_button="border-radius:0 15px 0 15px;background-color:' . $clr . ';" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '75',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_show_hidden_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:0 15px 0 15px;font-size:13px;background-color:unset !important;background-image:linear-gradient(45deg,#3a6186,#89253e);" sk_hover="color:#ffffff;" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '76',
					  'e'  => 'con',
					  's'  => '[cz_button title="Contact Us" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-phone-square" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_zoom_in" scale="1" sk_button="border-radius:0 30px 0 30px;padding-left:30px;padding-right:30px;font-size:22px;background-color:unset !important;background-image:linear-gradient(135deg,#1e73bb,#b40ed6);" sk_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '77',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon_position="before" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_fill_up" scale="1" sk_button="border-radius:0 10px 0 10px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;color:' . $clr . ';font-size:13px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '78',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-angle-double-right" icon_position="after" text_effect="cz_btn_txt_move_right" btn_effect="cz_btn_fill_right" scale="1" sk_button="border-radius:0 15px 0 15px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;color:' . $clr . ';font-size:18px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '79',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_block" icon="fa fa-chevron-right" icon_position="before" text_effect="cz_btn_show_hidden_icon" btn_effect="cz_btn_no_fx" scale="1" sk_button="border-radius:0 30px 0 30px!important;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:3px 3px 3px 3px;color:' . $clr . ';font-size:22px;background-color:#ffffff;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_icon_hover="color:#ffffff;"]',
					),
					array(
					  'n'  => '80',
					  'e'  => 'con',
					  's'  => '[cz_button title="Read More" link="url:%23|||" btn_position="cz_btn_inline" icon="fa fa-long-arrow-right" icon_position="after" text_effect="cz_btn_txt_no_fx" btn_effect="cz_btn_blur" scale="1" sk_button="border-radius:0 10px 0 10px;border-color:' . $clr . ';border-width:1px 1px 1px 1px;color:' . $clr . ';font-size:13px;background-color:#ffffff;"]',
					),
				),
				

				/* Social Icons*/
				'cz_social_icons' => array(
					'1 cz_prevent_icon_link_title',
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="color:' . $clr . ';" sk_hover="" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="color:' . $clr . ';font-size:24px;" sk_hover="" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="color:' . $clr . ';font-size:32px;" sk_hover="" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored" position="tac" fx="cz_social_fx_6" sk_icons="color:#666666;" sk_hover="" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored" position="tac" fx="cz_social_fx_6" sk_icons="color:#666666;font-size:24px;" sk_hover="" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored" position="tac" fx="cz_social_fx_6" sk_icons="color:#666666;font-size:36px;" sk_hover="" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_1" sk_icons="border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_1" sk_icons="border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_1" sk_icons="border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_4" sk_icons="border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:2px 2px 2px 2px;margin-right:-2px;color:#666666;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_4" sk_icons="border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:2px 2px 2px 2px;margin-right:-2px;color:#666666;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_4" sk_icons="border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:3px 3px 3px 3px;margin-right:-3px;color:#666666;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_2" sk_icons="border-bottom-style:solid;border-color:#bbbbbb;padding:5px 5px 5px 5px;border-bottom-width:4px;margin-right:10px;color:#666666;font-size:16px;" sk_hover="color:#ffffff;background-color:#666666;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_2" sk_icons="border-bottom-style:solid;border-color:#bbbbbb;padding:5px 5px 5px 5px;border-bottom-width:4px;margin-right:10px;color:#666666;font-size:22px;" sk_hover="color:#ffffff;background-color:#666666;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_2" sk_icons="border-bottom-style:solid;border-color:#bbbbbb;padding:5px 5px 5px 5px;border-bottom-width:4px;margin-right:10px;color:#666666;font-size:32px;" sk_hover="color:#ffffff;background-color:#666666;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '16',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_0" sk_icons="padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '17',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_0" sk_icons="padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '18',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_0" sk_icons="padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '19',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:16px;background-color:#666666;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '20',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:22px;background-color:#666666;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '21',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:32px;background-color:#666666;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '22',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_0" sk_icons="padding:5px 15px 5px 15px;color:#ffffff;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '23',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_0" sk_icons="padding:5px 20px 5px 20px;color:#ffffff;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '24',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_0" sk_icons="padding:5px 25px 5px 25px;color:#ffffff;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '25',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_1" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '26',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_1" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '27',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_1" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '28',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_1" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:15px 0px 0px 15px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '29',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_1" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:15px 0px 0px 15px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '30',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_1" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:15px 0px 0px 15px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '31',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="padding:15px 0px 0px 15px;margin-right:10px;color:#ffffff;font-size:14px;background-color:unset !important;background-image:linear-gradient(135deg,' . $clr . ',' . self::adjustBrightness($clr,-95) . ');" sk_hover="color:#ffffff;background-color:unset !important;background-image:linear-gradient(135deg,' . $clr . ',' . self::adjustBrightness($clr,-95) . ',' . self::adjustBrightness($clr,-95) . ');" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '32',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="padding:18px 0px 0px 18px;margin-right:10px;color:#ffffff;font-size:18px;background-color:unset !important;background-image:linear-gradient(135deg,' . $clr . ',' . self::adjustBrightness($clr,-95) . ');" sk_hover="color:#ffffff;background-color:unset !important;background-image:linear-gradient(135deg,' . $clr . ',' . self::adjustBrightness($clr,-95) . ',' . self::adjustBrightness($clr,-95) . ');" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '33',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="padding:20px 0px 0px 20px;margin-right:10px;color:#ffffff;font-size:24px;background-color:unset !important;background-image:linear-gradient(135deg,' . $clr . ',' . self::adjustBrightness($clr,-95) . ');" sk_hover="color:#ffffff;background-color:unset !important;background-image:linear-gradient(135deg,' . $clr . ',' . self::adjustBrightness($clr,-95) . ',' . self::adjustBrightness($clr,-95) . ');" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '34',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_4" sk_icons="border-radius:5px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '35',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_4" sk_icons="border-radius:5px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '36',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_4" sk_icons="border-radius:5px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '37',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:16px;background-color:#666666;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '38',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:22px;background-color:#666666;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '39',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:32px;background-color:#666666;" sk_hover="color:#ffffff;background-color:' . $clr . ';" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '40',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:16px;background-color:unset !important;background-image:linear-gradient(225deg,#ac00e0,#004aba);" sk_hover="color:#ffffff;background-color:#000000;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '41',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:22px;background-color:unset !important;background-image:linear-gradient(225deg,#ac00e0,#004aba);" sk_hover="color:#ffffff;background-color:#000000;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '42',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:32px;background-color:unset !important;background-image:linear-gradient(225deg,#ac00e0,#004aba);" sk_hover="color:#ffffff;background-color:#000000;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '43',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_3" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '44',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_3" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '45',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_3" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '46',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" inline_title="true" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 15px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:16px;" sk_hover="color:#cc0000;" sk_title="font-family:Poppins;font-weight:300;"]',
					),
					array(
					  'n'  => '47',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_9" inline_title="true" sk_icons="border-radius:0px;padding:5px 10px 5px 20px;margin-right:1px;color:#ffffff;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Poppins;"]',
					),
					array(
					  'n'  => '48',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg_hover" position="tac" fx="cz_social_fx_3" inline_title="true" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 15px;border-width:1px 1px 1px 1px;margin-right:10px;color:#666666;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Poppins;font-weight:200;"]',
					),
					array(
					  'n'  => '49',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_9" inline_title="true" sk_icons="border-radius:50px;padding:5px 10px 5px 20px;margin-right:10px;color:#ffffff;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Poppins;"]',
					),
					array(
					  'n'  => '50',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_9" sk_icons="border-radius:50px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '51',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_9" sk_icons="border-radius:50px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '52',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_9" sk_icons="border-radius:50px;padding:5px 5px 5px 5px;margin-right:10px;color:#ffffff;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '53',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_4" sk_icons="border-radius:50px;padding:3px 20px 3px 20px;margin-right:10px;color:#ffffff;font-size:16px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '54',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_4" sk_icons="border-radius:50px;padding:3px 25px 3px 25px;margin-right:10px;color:#ffffff;font-size:22px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '55',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_bg" position="tac" fx="cz_social_fx_4" sk_icons="border-radius:50px;padding:0px 35px 0px 35px;margin-right:10px;color:#ffffff;font-size:32px;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '56',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_4" sk_icons="border-radius:50px;padding:3px 20px 3px 20px;margin-right:10px;color:#ffffff;font-size:16px;background-color:unset !important;background-image:linear-gradient(0deg,#8500d8,#1e73be);" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '57',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_4" sk_icons="border-radius:50px;padding:3px 25px 3px 25px;margin-right:10px;color:#ffffff;font-size:22px;background-color:unset !important;background-image:linear-gradient(0deg,#8500d8,#1e73be);" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '58',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_4" sk_icons="border-radius:50px;padding:0px 35px 0px 35px;margin-right:10px;color:#ffffff;font-size:32px;background-color:unset !important;background-image:linear-gradient(0deg,#8500d8,#1e73be);" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '59',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#c1c1c1;padding:5px 5px 5px 5px;border-bottom-width:3px;margin-right:10px;color:#666666;font-size:16px;box-shadow:0px 0px 10px rgba(0,0,0,0.15) ;background-color:#ffffff;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '60',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#c1c1c1;padding:5px 5px 5px 5px;border-bottom-width:3px;margin-right:15px;color:#666666;font-size:22px;box-shadow:0px 0px 15px rgba(0,0,0,0.15) ;background-color:#ffffff;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '61',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#c1c1c1;padding:5px 5px 5px 5px;border-bottom-width:4px;margin-right:20px;color:#666666;font-size:32px;box-shadow:0px 0px 20px rgba(0,0,0,0.15) ;background-color:#ffffff;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '62',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#bbbbbb;padding:5px 5px 5px 5px;border-bottom-width:3px;margin-right:10px;color:#666666;font-size:16px;background-color:#eeeeee;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '63',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#bbbbbb;padding:5px 5px 5px 5px;border-bottom-width:3px;margin-right:10px;color:#666666;font-size:22px;background-color:#eeeeee;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '64',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#bbbbbb;padding:5px 5px 5px 5px;border-bottom-width:4px;margin-right:10px;color:#666666;font-size:32px;background-color:#eeeeee;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '65',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#c1c1c1;padding:5px 5px 5px 5px;border-bottom-width:3px;margin-right:10px;color:#666666;font-size:16px;box-shadow:0px 0px 10px rgba(0,0,0,0.15) ;background-color:#ffffff;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '66',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#c1c1c1;padding:5px 5px 5px 5px;border-bottom-width:3px;margin-right:15px;color:#666666;font-size:22px;box-shadow:0px 0px 15px rgba(0,0,0,0.15) ;background-color:#ffffff;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '67',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:10px;border-bottom-style:solid;border-color:#c1c1c1;padding:5px 5px 5px 5px;border-bottom-width:4px;margin-right:20px;color:#666666;font-size:32px;box-shadow:0px 0px 20px rgba(0,0,0,0.15) ;background-color:#ffffff;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '68',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#bbbbbb;padding:5px 5px 5px 5px;border-bottom-width:3px;margin-right:10px;color:#666666;font-size:16px;background-color:#eeeeee;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '69',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#bbbbbb;padding:5px 5px 5px 5px;border-bottom-width:3px;margin-right:10px;color:#666666;font-size:22px;background-color:#eeeeee;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
					array(
					  'n'  => '70',
					  'e'  => 'con',
					  's'  => '[cz_social_icons social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22Facebook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-pinterest-p%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Pinterest%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" color_mode="cz_social_colored_hover" position="tac" fx="cz_social_fx_0" sk_icons="border-radius:10px;border-bottom-style:solid;border-color:#bbbbbb;padding:5px 5px 5px 5px;border-bottom-width:4px;margin-right:10px;color:#666666;font-size:32px;background-color:#eeeeee;" sk_hover="color:#ffffff;" sk_title="font-family:Tahoma;"]',
					),
				),


				/* Team Member*/
				'cz_team' => array(
					'2 cz_prevent_image_icon_link_title',
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook-square%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter-square%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin-square%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-envelope-square%22%2C%22title%22%3A%22Email%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="" sk_social_con="padding-top:160px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:-4px;margin-right:-4px;color:' . $clr . ';font-size:24px;" sk_icons_hover="color:#000000;" sk_image_con="border-radius:5px;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 34px; font-weight: 200; display: block;">John Carter</span> <span style="color: ' . $clr . ';">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" hover_mode="cz_team_always_show" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:0.4" sk_image_img_hover="opacity:1" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook-square%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter-square%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin-square%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-envelope-square%22%2C%22title%22%3A%22Email%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="" sk_social_con="padding-top:160px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:-4px;margin-right:-4px;color:' . $clr . ';font-size:24px;" sk_icons_hover="color:#000000;" sk_image_con="border-radius:5px;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 32px; font-weight: bold; display: block; color: #000000;">John Carter</span><span style="color: ' . $clr . '; font-weight: 300;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="background-color:#222222;padding-bottom:20px;" sk_social_con="padding-top:160px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:3px;margin-right:3px;color:#ffffff;font-size:18px;background-color:#222222;" sk_icons_hover="color:#ff9900;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 200; display: block; color: #ffffff;">John Carter</span> <span style="color: #ff9900; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_2" sk_overall="border-radius:6px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-bottom:20px;border-width:1px 1px 1px 1px;" sk_social_con="padding-top:160px;" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:1px 1px 1px 1px;margin-left:3px;margin-right:3px;color:#222222;font-size:18px;" sk_icons_hover="border-bottom-style:solid;border-color:#222222;border-top-style:solid;border-right-style:solid;border-left-style:solid;background-color:#222222;border-width:1px 1px 1px 1px;color:#ffffff;" sk_image_con="border-radius:5px 5px 0 0;background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3; font-family: Dosis;"><span style="font-size: 32px; font-weight: 400; display: block; color: #000000;">John Carter</span><span style="color: #999999; font-size: 16px;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tar" social_v="true" fx="cz_social_fx_2" sk_overall="border-radius:6px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-bottom:20px;border-width:1px 1px 1px 1px;" sk_social_con="padding-right:15px;" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:1px 1px 1px 1px;margin-left:3px;margin-bottom:5px;color:#222222;font-size:18px;" sk_icons_hover="border-bottom-style:solid;border-color:#222222;border-top-style:solid;border-right-style:solid;border-left-style:solid;background-color:#222222;border-width:1px 1px 1px 1px;color:#ffffff;" sk_image_con="border-radius:5px 5px 0 0;background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3; font-family: Playfair Display;"><span style="font-size: 32px; font-weight: 400; display: block; color: #000000;">John Carter</span><span style="color: #999999; font-size: 16px;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tal" social_v="true" fx="cz_social_fx_0" sk_overall="background-color:#000000;padding-bottom:20px;" sk_social_con="padding-left:15px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:3px;margin-right:3px;color:#ffffff;font-size:18px;background-color:#222222;" sk_icons_hover="color:#cc99ff;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 900; display: block; color: #ffffff;">John Carter</span><span style="color: #cc99ff; font-size: 14px; font-weight: 300;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" hover_mode="cz_team_always_show" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:0.4" sk_image_img_hover="opacity:1" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook-square%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter-square%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin-square%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-envelope-square%22%2C%22title%22%3A%22Email%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tar" social_v="true" fx="cz_social_fx_0" sk_overall="" sk_social_con="padding-right:10px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:-4px;margin-right:-4px;color:#800080;font-size:24px;" sk_icons_hover="color:#000000;" sk_image_con="border-radius:5px;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 32px; font-weight: bold; display: block; color: #000000;">John Carter</span><span style="color: #800080; font-weight: 300;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook-square%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter-square%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin-square%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-envelope-square%22%2C%22title%22%3A%22Email%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tar" social_v="true" fx="cz_social_fx_0" sk_overall="border-radius:5px;background-color:#eeee22;padding-bottom:20px;" sk_social_con="padding-right:10px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:-4px;margin-right:-4px;color:#000000;font-size:24px;" sk_icons_hover="color:#000000;" sk_image_con="border-radius:5px;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 34px; font-weight: bold; display: block; font-family: Oswald; color: #000000;">John Carter</span> Developer</span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" class="cz_slanted_bl_div" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="border-radius:7px;background-color:#4e342e;padding-bottom:10px;" sk_social_con="padding-top:95px;" sk_icons="border-radius:5px;padding-left:0px;padding-right:0px;margin-left:3px;margin-right:3px;color:#ffffff;font-size:18px;background-color:#4e342e;" sk_icons_hover="color:#ffffff;" sk_image_con="background-color:#ffffff;" sk_content="padding-bottom:15px;margin-top:10px;"]
													<p style="text-align: center;"><span style="line-height: 1.3; color: #ffffff;"><span style="font-size: 30px; font-weight: 400; display: block; font-family: Oswald;">John Carter</span><span style="font-size: 14px; font-weight: 300;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" class="cz_slanted_br_div" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="background-color:' . $clr . ';padding-bottom:10px;" sk_social_con="padding-top:95px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:3px;margin-right:3px;color:#ffffff;font-size:18px;background-color:' . $clr . ';" sk_icons_hover="color:#ffffff;" sk_image_con="background-color:#ffffff;" sk_content="padding-bottom:15px;margin-top:10px;"]
													<p style="text-align: center;"><span style="line-height: 1.3; color: #ffffff;"><span style="font-size: 30px; font-weight: 400; display: block; font-family: Raleway;">John Carter</span><span style="font-size: 14px;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" class="cz_slanted_bl_div" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_2" sk_overall="border-radius:6px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-bottom:20px;border-width:1px 1px 1px 1px;" sk_social_con="padding-top:95px;" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:1px 1px 1px 1px;margin-left:3px;margin-right:3px;color:#222222;font-size:18px;" sk_icons_hover="border-bottom-style:solid;border-color:#222222;border-top-style:solid;border-right-style:solid;border-left-style:solid;background-color:#222222;border-width:1px 1px 1px 1px;color:#ffffff;" sk_image_con="border-radius:5px 5px 0 0;background-color:#ffffff;" sk_content="padding-bottom:15px;margin-top:10px;"]
													<p style="text-align: center;"><span style="line-height: 1.3; font-family: Dosis;"><span style="font-size: 32px; font-weight: bold; display: block; color: #000000; font-family: Playfair Display;">John Carter</span><span style="color: #999999; font-size: 16px;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" class="cz_slanted_br_div" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_2" sk_overall="border-radius:6px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-bottom:20px;border-width:1px 1px 1px 1px;" sk_social_con="padding-top:95px;" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:1px 1px 1px 1px;margin-left:3px;margin-right:3px;color:#222222;font-size:18px;" sk_icons_hover="border-bottom-style:solid;border-color:#222222;border-top-style:solid;border-right-style:solid;border-left-style:solid;background-color:#222222;border-width:1px 1px 1px 1px;color:#ffffff;" sk_image_con="border-radius:5px 5px 0 0;background-color:#ffffff;" sk_content="padding-bottom:15px;margin-top:10px;"]
													<p style="text-align: center;"><span style="line-height: 1.3; font-family: Dosis;"><span style="font-size: 32px; font-weight: 400; display: block; color: #000000;">John Carter</span><span style="color: #999999; font-size: 16px;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" hover_mode="cz_team_always_show" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook-square%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter-square%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin-square%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-envelope-square%22%2C%22title%22%3A%22Email%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="border-radius:15px;background-color:' . $clr . ';padding-bottom:20px;" sk_social_con="padding-top:170px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:-4px;margin-right:-4px;color:' . $clr . ';font-size:24px;" sk_icons_hover="color:#ffffff;" sk_image_con="border-radius:10px 10px 0 0;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3; font-family: Poppins;"><span style="font-size: 28px; font-weight: 500; display: block; color: #ffffff;">John Carter</span><span style="color: #ffffff; font-weight: 200;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook-square%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter-square%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin-square%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-envelope-square%22%2C%22title%22%3A%22Email%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="" sk_social_con="padding-top:160px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:-4px;margin-right:-4px;color:#607d8b;font-size:24px;" sk_icons_hover="color:#000000;" sk_image_con="border-radius:5px;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3; font-family: Quicksand;"><span style="font-size: 32px; font-weight: bold; display: block; color: #000000;">John Carter</span><span style="color: #808080; font-weight: 400;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.6" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="background-color:#000000;padding-bottom:25px;" sk_social_con="padding-top:160px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:3px;margin-right:3px;color:#ffffff;font-size:18px;background-color:#000000;" sk_icons_hover="color:#ffffff;" sk_image_con="background-color:#ffffff;margin-bottom:30px;margin-top:10px;" sk_content=""]
													<p style="text-align: center;"><span style="font-family: Playfair Display; line-height: 1;"><span style="font-size: 30px;"><span style="display: block; color: #ffffff;">John Carter</span></span><span style="color: #999999; font-size: 14px;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '16',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_2" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook-square%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter-square%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin-square%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-envelope-square%22%2C%22title%22%3A%22Email%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="border-radius:15px;background-color:#90a4ae;padding-bottom:20px;" sk_social_con="padding-top:170px;" sk_icons="padding-left:0px;padding-right:0px;margin-left:-4px;margin-right:-4px;color:#546e7a;font-size:24px;" sk_icons_hover="color:#ffffff;" sk_image_con="border-radius:10px 10px 0 0;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3; font-family: Poppins;"><span style="font-size: 36px; font-weight: 500; display: block; color: #ffffff; font-family: Arizonia;">John Carter</span><span style="color: #ffffff; font-weight: 200;"> Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '17',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" hover_mode="cz_team_always_show" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:0.3" sk_image_img_hover="opacity:1" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="background-color:#000000;padding:5px 5px 5px 5px;" sk_social_con="padding-top:160px;" sk_icons="border-bottom-style:solid;border-color:#000000;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:2px 2px 2px 2px;margin-left:3px;margin-right:3px;color:#000000;font-size:16px;" sk_icons_hover="color:#000000;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 300; display: block; color: #000000;">John Carter</span> <span style="color: #808080; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '18',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.2" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="background-color:#000000;padding:5px 5px 5px 5px;" sk_icons="border-bottom-style:solid;border-color:#ffffff;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:2px 2px 2px 2px;margin-left:3px;margin-right:3px;color:#ffffff;font-size:16px;" sk_icons_hover="color:#ffffff;" sk_image_con="" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3; color: #ffffff;"><span style="font-size: 30px; font-weight: 300; display: block;">John Carter</span> <span style="font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '19',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" hover_mode="cz_team_always_show" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:0.3" sk_image_img_hover="opacity:0.6" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="border-radius:500px;background-color:#000000;padding:5px 5px 5px 5px;" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#ffffff;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:2px 2px 2px 2px;margin-left:3px;margin-right:3px;color:#ffffff;font-size:16px;" sk_icons_hover="color:#ffffff;" sk_image_con="" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3; color: #ffffff;"><span style="font-size: 30px; font-weight: 300; display: block;">John Carter</span> <span style="font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '20',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.2" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="border-radius:500px;background-color:#000000;padding:5px 5px 5px 5px;" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#ffffff;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:2px 2px 2px 2px;margin-left:3px;margin-right:3px;color:#ffffff;font-size:16px;" sk_icons_hover="color:#ffffff;" sk_image_con="" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3; color: #ffffff;"><span style="font-size: 30px; font-weight: 300; display: block;">John Carter</span> <span style="font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '21',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" hover_mode="cz_team_always_show" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:0.3" sk_image_img_hover="opacity:1" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_9" sk_overall="border-bottom-style:solid;border-color:#ffffff;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:4px 4px 4px 4px;box-shadow:0px 9px 24px rgba(0,0,0,0.26) ;" sk_icons="padding-left:0px;padding-right:0px;margin-left:3px;margin-right:3px;color:#000000;font-size:16px;box-shadow:0px 5px 14px rgba(0,0,0,0.22) ;background-color:#ffffff;" sk_icons_hover="color:#000000;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 300; display: block; color: #333333;">John Carter</span> <span style="color: #808080; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '22',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_9" sk_overall="border-bottom-style:solid;border-color:#ffffff;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:4px 4px 4px 4px;box-shadow:0px 9px 24px rgba(0,0,0,0.26) ;" sk_icons="padding-left:0px;padding-right:0px;margin-left:3px;margin-right:3px;color:#000000;font-size:16px;box-shadow:0px 5px 14px rgba(0,0,0,0.22) ;background-color:#ffffff;" sk_icons_hover="color:#000000;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 300; display: block; color: #333333;">John Carter</span> <span style="color: #808080; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '23',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" hover_mode="cz_team_always_show" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:0.3" sk_image_img_hover="opacity:1" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_9" sk_overall="border-radius:500px;border-bottom-style:solid;border-color:#ffffff;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:4px 4px 4px 4px;box-shadow:0px 9px 24px rgba(0,0,0,0.26) ;" sk_icons="border-radius:50px;padding-left:0px;padding-right:0px;margin-left:3px;margin-right:3px;color:#333333;font-size:16px;box-shadow:0px 5px 14px rgba(0,0,0,0.22) ;background-color:#ffffff;" sk_icons_hover="color:#000000;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 300; display: block;">John Carter</span> <span style="color: #808080; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '24',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_9" sk_overall="border-radius:500px;border-bottom-style:solid;border-color:#ffffff;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:4px 4px 4px 4px;box-shadow:0px 9px 24px rgba(0,0,0,0.26) ;" sk_icons="border-radius:50px;padding-left:0px;padding-right:0px;margin-left:3px;margin-right:3px;color:#000000;font-size:16px;box-shadow:0px 5px 14px rgba(0,0,0,0.22) ;background-color:#ffffff;" sk_icons_hover="color:#000000;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 300; display: block; color: #333333;">John Carter</span> <span style="color: #808080; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '25',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="border-radius:7px;border-bottom-style:solid;border-color:#999999;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;" sk_social_con="padding-top:160px;" sk_icons="border-radius:4px;border-bottom-style:solid;border-color:#999999;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:1px 1px 1px 1px;margin-left:3px;margin-right:3px;color:#000000;font-size:16px;" sk_icons_hover="border-bottom-style:solid;border-color:#000000;border-top-style:solid;border-right-style:solid;border-left-style:solid;background-color:#000000;border-width:1px 1px 1px 1px;color:#ffffff;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 300; display: block; color: #333333;">John Carter</span> <span style="color: #808080; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '26',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" hover_mode="cz_team_always_show" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:0.3" sk_image_img_hover="opacity:1" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-instagram%22%2C%22title%22%3A%22Instagram%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="border-radius:7px;border-bottom-style:solid;border-color:#999999;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;" sk_social_con="padding-top:160px;" sk_icons="border-radius:4px;border-bottom-style:solid;border-color:#999999;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:1px 1px 1px 1px;margin-left:3px;margin-right:3px;color:#000000;font-size:16px;" sk_icons_hover="border-bottom-style:solid;border-color:#000000;border-top-style:solid;border-right-style:solid;border-left-style:solid;background-color:#000000;border-width:1px 1px 1px 1px;color:#ffffff;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 300; display: block; color: #333333;">John Carter</span> <span style="color: #808080; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '27',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:1" sk_image_img_hover="opacity:0.4" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="border-radius:500px;border-bottom-style:solid;border-color:#999999;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;" sk_social_con="padding-top:160px;" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#999999;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:1px 1px 1px 1px;margin-left:3px;margin-right:3px;color:#000000;font-size:16px;" sk_icons_hover="border-bottom-style:solid;border-color:#000000;border-top-style:solid;border-right-style:solid;border-left-style:solid;background-color:#000000;border-width:1px 1px 1px 1px;color:#ffffff;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 300; display: block; color: #333333;">John Carter</span> <span style="color: #808080; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),
					array(
					  'n'  => '28',
					  'e'  => 'con',
					  's'  => '[cz_team style="cz_team_5" hover_mode="cz_team_always_show" cbg_size="1" cbg_color="#111111" image="http://www.xtratheme.com/img/450x450.jpg" size="full" sk_image_img="opacity:0.3" sk_image_img_hover="opacity:1" social="%5B%7B%22icon%22%3A%22fa%20fa-facebook%22%2C%22title%22%3A%22FaceBook%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-twitter%22%2C%22title%22%3A%22Twitter%22%2C%22link%22%3A%22%23%22%7D%2C%7B%22icon%22%3A%22fa%20fa-linkedin%22%2C%22title%22%3A%22Linkedin%22%2C%22link%22%3A%22%23%22%7D%5D" social_align="tac" fx="cz_social_fx_0" sk_overall="border-radius:500px;border-bottom-style:solid;border-color:#999999;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;" sk_social_con="padding-top:160px;" sk_icons="border-radius:50px;border-bottom-style:solid;border-color:#999999;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:0px;padding-right:0px;border-width:1px 1px 1px 1px;margin-left:3px;margin-right:3px;color:#000000;font-size:16px;" sk_icons_hover="border-bottom-style:solid;border-color:#000000;border-top-style:solid;border-right-style:solid;border-left-style:solid;background-color:#000000;border-width:1px 1px 1px 1px;color:#ffffff;" sk_image_con="background-color:#ffffff;" sk_content=""]
													<p style="text-align: center;"><span style="line-height: 1.3;"><span style="font-size: 30px; font-weight: 300; display: block; color: #333333;">John Carter</span> <span style="color: #808080; font-size: 14px;">Developer</span></span></p>
													[/cz_team]',
					),

				),


				/* Subscribe */
				'cz_subscribe' => array(
					'1 cz_prevent_action_placeholder_name_attr_btn_title',
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="420px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:5px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;" sk_button="border-radius:0 5px 5px 0;color:#ffffff;font-size:14px;letter-spacing:1px;font-weight:700;background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="300px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-left:-56px;" sk_button="border-radius:5px;padding-left:25px;padding-right:25px;margin-right:-56px;color:#ffffff;font-size:13px;letter-spacing:1px;font-weight:700;background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="420px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." icon="fa fa-paper-plane" sk_input="border-radius:10px;border-bottom-style:solid;border-color:' . Codevz_Plus::hex2rgba($clr,0.2) . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;padding-bottom:14px;padding-top:14px;border-width:2px 2px 2px 2px;color:' . self::adjustBrightness($clr,-95) . ';" sk_button="border-radius:6px;padding:10px 20px 10px 20px;margin-right:6px;margin-top:6px;color:#ffffff;font-size:13px;letter-spacing:1px;font-weight:700;background-color:unset !important;background-image:linear-gradient(0deg,' . self::adjustBrightness($clr,-95) . ',' . $clr . ');"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="420px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="NEWSLETTER" sk_input="border-radius:10px;border-bottom-style:solid;border-color:' . self::adjustBrightness($clr,-45) . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;padding-bottom:15px;padding-top:14px;border-width:2px 2px 2px 2px;" sk_button="border-radius:6px;padding:10px 20px 10px 20px;margin-right:6px;margin-top:6px;color:#ffffff;font-size:13px;letter-spacing:1px;font-weight:700;background-color:unset !important;background-image:linear-gradient(0deg,' . self::adjustBrightness($clr,-45) . ',' . $clr . ');"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="420px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:100px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;padding-bottom:14px;padding-top:14px;border-width:2px 2px 2px 2px;color:#ffffff;background-color:' . $clr . ';" sk_button="border-radius:100px;padding:10px 20px 10px 20px;margin-right:6px;margin-top:6px;color:' . $clr . ';font-size:13px;letter-spacing:1px;font-weight:700;box-shadow:0px 0px 10px rgba(0,0,0,0.35) inset ;background-color:#ffffff;"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="360px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" icon="fa fa-plus" sk_input="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;" sk_button="border-radius:5px;padding-left:20px;padding-right:20px;margin-top:48px;color:#ffffff;font-size:14px;letter-spacing:1px;font-weight:700;background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="320px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." icon="fa fa-check-circle" sk_input="border-radius:100px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;padding-bottom:18px;padding-top:18px;border-width:2px 2px 2px 2px;" sk_button="border-radius:100px;padding:8px 13px 10px 14px;margin-right:7px;margin-top:6px;color:#ffffff;font-size:20px;letter-spacing:1px;font-weight:700;background-color:unset !important;background-image:linear-gradient(45deg,#8224e3,' . $clr . ');"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="320px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." icon="fa fa-check" sk_input="border-radius:100px;border-bottom-style:solid;border-color:#ffffff;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;padding-bottom:18px;padding-top:18px;border-width:2px 2px 2px 2px;box-shadow:0px 9px 20px rgba(0,0,0,0.15) ;background-color:#ffffff;" sk_button="border-radius:100px;padding:8px 12px 10px 13px;margin-right:7px;margin-top:6px;color:#ffffff;font-size:20px;letter-spacing:1px;font-weight:700;background-color:unset !important;background-image:linear-gradient(45deg,#8224e3,' . $clr . ');"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_subscribe style="cz_subscribe_round" position="center" width="420px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:100px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;border-width:2px 2px 2px 2px;" sk_button="border-radius:0 100px 100px 0;color:#ffffff;font-size:14px;letter-spacing:1px;font-weight:700;background-color:unset !important;background-image:linear-gradient(0deg,' . $clr . ',' . self::adjustBrightness($clr,-45) . ',' . $clr . ');"]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_subscribe style="cz_subscribe_relative cz_subscribe_round" position="center" width="400px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Now" sk_input="border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;" sk_button="background-color:unset !important;background-image:linear-gradient(180deg,' . $clr . ',' . self::adjustBrightness($clr,-45) . ');"]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="420px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:100px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;border-width:2px 2px 2px 2px;" sk_button="border-radius:0 100px 100px 0;color:#ffffff;font-size:14px;letter-spacing:1px;font-weight:700;background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="300px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:0px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-left:-56px;" sk_button="border-radius:0px;padding:13px 25px 12px 25px;margin-right:-56px;color:#ffffff;font-size:13px;letter-spacing:2px;font-weight:300;background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="380px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:100px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;padding-bottom:14px;padding-top:14px;border-width:2px 2px 2px 2px;" sk_button="border-radius:100px;padding:10px 20px 10px 20px;margin-right:6px;margin-top:6px;color:#ffffff;font-size:13px;letter-spacing:1px;font-weight:700;background-color:unset !important;background-image:linear-gradient(225deg,' . $clr . ',#8224e3);"]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="420px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Now" sk_input="border-radius:10px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;padding-bottom:16px;padding-top:14px;border-width:1px 1px 1px 1px;" sk_button="border-radius:6px;padding:10px 20px 10px 20px;margin-right:6px;margin-top:6px;color:#ffffff;font-size:13px;letter-spacing:1px;font-weight:700;background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="420px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us!" sk_input="border-radius:10px;padding-left:20px;padding-bottom:16px;padding-top:16px;color:#eeeeee;font-size:14px;letter-spacing:1px;background-color:' . $clr . ';" sk_button="border-radius:6px;padding:10px 20px 10px 20px;margin-right:7px;margin-top:6px;color:' . $clr . ';font-size:13px;letter-spacing:1px;font-weight:700;box-shadow:0px 0px 10px rgba(0,0,0,0.4) inset ;background-color:#ffffff;"]',
					),
					array(
					  'n'  => '16',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="300px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:0px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:0px 0px 3px 0px;margin-left:-50px;" sk_button="border-radius:0px;border-bottom-style:solid;border-color:' . self::adjustBrightness($clr,-95) . ';padding-left:10px;padding-bottom:10px;padding-right:10px;border-bottom-width:3px;margin-right:-50px;color:' . self::adjustBrightness($clr,-95) . ';font-size:14px;letter-spacing:1px;font-weight:700;background-color:#ffffff;"]',
					),
					array(
					  'n'  => '17',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="420px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." icon="fa fa-plus-circle" sk_input="border-radius:100px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-left:20px;padding-bottom:18px;padding-top:18px;border-width:2px 2px 2px 2px;" sk_button="border-radius:100px;padding:8px 13px 10px 14px;margin-right:7px;margin-top:6px;color:#ffffff;font-size:20px;letter-spacing:1px;font-weight:700;background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '18',
					  'e'  => 'con',
					  's'  => '[cz_subscribe style="cz_subscribe_relative " position="center" width="400px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Now" sk_input="border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;" sk_button="background-color:' . $clr . ';"]',
					),
					array(
					  'n'  => '19',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="280px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:0px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-left:-55px;color:#ffffff;background-color:' . $clr . ';" sk_button="border-radius:0px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:11px 20px 11px 20px;border-width:2px 2px 2px 2px;margin-right:-55px;color:' . $clr . ';font-size:13px;letter-spacing:2px;font-weight:700;background-color:rgba(255,255,255,0.14);"]',
					),
					array(
					  'n'  => '20',
					  'e'  => 'con',
					  's'  => '[cz_subscribe position="center" width="280px" action="http://feedburner.google.com/codevz" placeholder="Enter your email ..." btn_title="Join Us" sk_input="border-radius:5px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-left:-55px;color:#ffffff;background-color:' . $clr . ';" sk_button="border-radius:5px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:11px 19px 11px 19px;border-width:2px 2px 2px 2px;margin-right:-55px;color:' . $clr . ';font-size:13px;letter-spacing:2px;font-weight:700;background-color:rgba(255,255,255,0.14);"]',
					),
				),


				/* Title */
				'cz_title' => array(
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_after_title" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:5px;top:5px;" sk_lines="background-color:' . self::adjustBrightness($clr,-65) . ';width:95px;height:5px;top:-20px;left:1px;"]<span style="font-size: 42px; font-family: Montserrat; color: ' . $clr . ';"><span style="font-weight: 200;">OUR</span> <span style="font-weight: 500;">SERVICES</span></span>[/cz_title]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_after_title" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:5px;top:5px;" sk_lines="background-color:' . self::adjustBrightness($clr,-65) . ';width:95px;height:5px;top:-20px;left:1px;"]
													<p style="text-align: center;"><span style="font-size: 42px; font-family: Montserrat; color: ' . $clr . ';"><span style="font-weight: 200;">OUR</span> <span style="font-weight: 500;">SERVICES</span></span></p>
													[/cz_title]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_both_side" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . $clr . ';width:8px;height:8px;top:10px;left:1px;"]
													<p style="text-align: center;"><span style="font-family: inherit; font-size: 38px;"><span style="font-weight: 900; color: ' . $clr . ';">Our </span><span style="font-weight: 200; color:' . self::adjustBrightness($clr,-65) . ';">Happy Clients</span>
													</span>

													[/cz_title]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_right_side" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . $clr . ';width:8px;height:8px;top:10px;left:1px;"]

													<p style="text-align: left;"><span style="font-family: inherit; font-size: 38px;"><span style="font-weight: 900; color: ' . $clr . ';">Our </span><span style="font-weight: 200; color:' . self::adjustBrightness($clr,-65) . ';">Happy Clients</span>
													</span>

													[/cz_title]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_both_side" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . Codevz_Plus::hex2rgba($clr,0.3) . ';width:30px;height:4px;top:0px;left:1px;" sk_icon_before="border-radius:3px;background-color:#eeeeee;margin-right:10px;margin-top:4px;" sk_icon_after=""]

													<p style="text-align: center;"><span style="font-size: 32px; color:' . $clr . ';"><strong><span style="font-family: Raleway;">How We Work?</span></strong></span></p>
													[/cz_title]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_right_side" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . Codevz_Plus::hex2rgba($clr,0.3) . ';width:30px;height:4px;top:0px;left:1px;" sk_icon_before="border-radius:3px;background-color:#eeeeee;margin-right:10px;margin-top:4px;" sk_icon_after=""]
													<p style="text-align: left;"><span style="font-size: 32px; color: ' . $clr . ';"><strong><span style="font-family: Raleway;">How We Work?</span></strong></span></p>
													[/cz_title]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_left_side" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . self::adjustBrightness($clr,-95) . ';width:5px;height:52px;top:-1px;left:0px;"]
													<h3 style="text-align: left;"><span style="color: ' . $clr . ';">About Us</span></h3>
													<p style="text-align: left;"><span style="color: #999999;">Who We Are?</span></p>
													[/cz_title]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_after_title" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . self::adjustBrightness($clr,-95) . ';width:50px;height:5px;top:-75px;left:1px;"]
													<h3 style="text-align: center;"><span style="line-height: 1.5; color: ' . $clr . ';">About Us</span></h3>
													&nbsp;
													<p style="text-align: center;"><span style="color: #999999; line-height: 1.5;">Who We Are?</span></p>
													[/cz_title]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_after_title" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:10px;" sk_lines="background-color:#cccccc;width:50px;height:6px;top:-10px;left:1px;"]
													<h2><span style="color: #000000; font-family: Playfair Display;"><span style="color: ' . $clr . ';">About Me</span>
													</span></h2>
													[/cz_title]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_after_title" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:10px;" sk_lines="background-color:#cccccc;width:50px;height:6px;top:-10px;left:1px;"]
													<h2 style="text-align: center;"><span style="color: #000000; font-family: Playfair Display;"><span style="color: ' . $clr . ';">About Me</span>
													</span></h2>
													[/cz_title]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_before_title" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . $clr . ';width:20px;height:20px;top:-40px;left:1px;"]
													<p style="text-align: left;"><span style="line-height: 1;"><span style="font-weight: 500;">
													<span style="font-weight: bold; font-size: 30px;"><span style="color: #999999;">WHAT WE DO?</span> </span></span><span style="display: block; font-weight: 200; font-size: 7em; color: ' . $clr . ';">SERVICE</span></span>

													[/cz_title]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_before_title" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . $clr . ';width:20px;height:20px;top:-45px;left:1px;"]

													<p style="text-align: center;"><span style="line-height: 1;"><span style="font-weight: 500;">
													<span style="font-weight: bold; font-size: 30px;"><span style="color: #999999;">WHO WE ARE?</span> </span></span><span style="display: block; font-weight: 200; font-size: 7em;"><span style="color: ' . $clr . ';">ABOUT</span>
													</span></span>

													[/cz_title]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:#111111;width:50px;height:4px;top:4px;left:1px;"]
													<h3 style="text-align: left;color: ' . $clr . ';"><span style="font-size: 38px;"><strong>How It <span style="color:' . self::adjustBrightness($clr,-65) . ';">Works</span> ?</strong></span></h3>
													<p style="text-align: left;"><span style="color: #999999;">Get better result of your site</span></p>
													[/cz_title]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:#111111;width:50px;height:4px;top:4px;left:1px;"]
													<h3 style="color: ' . $clr . '; text-align: center;"><span style="font-size: 38px;"><strong>How It <span style="color:' . self::adjustBrightness($clr,-65) . ';">Works</span> ?</strong></span></h3>
													<p style="text-align: center;"><span style="color: #999999;">Get better result of your site</span></p>
													[/cz_title]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_after_title" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . $clr . ';width:500px;height:4px;top:-85px;left:1px;"]
													<p style="text-align: center;"><span style="line-height: 1;"><span style="font-weight: 500;">
													<span style="font-weight: bold; font-size: 30px;"><span style="color: #999999;">WHO WE ARE?</span> </span></span><span style="display: block; font-weight: 200; font-size: 7em;"><span style="color: #cccccc;">ABOUT</span>
													</span></span>

													[/cz_title]',
					),
					array(
					  'n'  => '16',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_after_title" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . $clr . ';width:460px;height:4px;top:-85px;left:1px;"]

													<p style="text-align: left;"><span style="line-height: 1;"><span style="font-weight: 500;">
													<span style="font-weight: bold; font-size: 30px;"><span style="color: #999999;">WHAT WE DO?</span> </span></span><span style="display: block; font-weight: 200; font-size: 7em;"><span style="color: #cccccc;">SERVICE</span>
													</span></span>

													[/cz_title]',
					),
					array(
					  'n'  => '17',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_both_side" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" shape="text" shape_text="OUR SERVICES" shape_position="tac" sk_overall="" sk_shape="font-size:70px;color:#f5f5f5;font-family:Roboto;font-weight:700;z-index:-1;top:40px;" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . $clr . ';width:150px;height:4px;top:4px;left:1px;"]

													<p style="text-align: center;"><span style="font-family: Roboto; font-size: 36px; color: #666666; font-weight: 400;">Our Services</span></p>
													[/cz_title]',
					),
					array(
					  'n'  => '18',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" shape="text" shape_text="ABOUT US" shape_position="tac" sk_overall="" sk_shape="font-size:70px;color:#f5f5f5;font-family:Montserrat;font-weight:900;z-index:-1;top:36px;" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:#111111;width:50px;height:4px;top:4px;left:1px;"]

													<p style="text-align: center;"><span style="font-family: Montserrat; font-size: 36px; color: #666666; font-weight: 300;"><span style="color: ' . $clr . ';">ABOUT US</span>
													</span>

													[/cz_title]',
					),
					array(
					  'n'  => '19',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" bline="cz_line_both_side" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" shape="text" shape_text="LOVE STORY" sk_overall="" sk_shape="font-size:80px;color:rgba(0,0,0,0.05);font-family:Ubuntu;font-weight:100;top:40px;" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:' . $clr . ';width:30px;height:4px;top:2px;left:1px;"]
													<h2 style="text-align: center;"><span style="font-family: Arizonia; color: ' . $clr . '; font-size: 56px;">Story</span></h2>
													[/cz_title]',
					),
					array(
					  'n'  => '20',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" shape="text" shape_text="ABOUT US" sk_overall="" sk_shape="font-size:85px;color:' . Codevz_Plus::hex2rgba($clr,0.1) . ';font-family:Playfair Display;letter-spacing:3px;font-weight:100;top:-2px;" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:#111111;width:50px;height:4px;top:4px;left:1px;"]
													<h2 style="text-align: center;"><span style="font-family: Playball; color: ' . $clr . '; font-size: 46px;">Our History</span></h2>
													[/cz_title]',
					),
					array(
					  'n'  => '21',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" css_right_line_left="1px" icon_before_type="icon" icon="fa fa-dot-circle-o" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="margin-left:-20px;" sk_shape="color:rgba(10,10,10,0.1);" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:#111111;width:50px;height:4px;top:4px;left:1px;" sk_icon_before="color:' . $clr . ';font-size:28px;" sk_icon_after=""]
													<h3><span style="font-family: Quicksand; color: #666666;">Our Services
													</span></h3>
													[/cz_title]',
					),
					array(
					  'n'  => '22',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" shape="icon" shape_icon="fa fa-bullseye" shape_position="tac" sk_overall="" sk_shape="font-size:98px;color:' . Codevz_Plus::hex2rgba($clr,0.2) . ';z-index:-1;top:34px;" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:#111111;width:50px;height:4px;top:4px;left:1px;"]

													<p style="text-align: center;"><span style="font-family: Montserrat; font-size: 36px; color: #666666; font-weight: 300;"><span style="color: ' . self::adjustBrightness($clr,-95) . ';">ABOUT <span style="font-weight: 500;">US</span></span>
													</span>

													[/cz_title]',
					),
					array(
					  'n'  => '23',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" css_right_line_left="1px" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" shape="icon" shape_icon="fa fa-chevron-down" sk_overall="" sk_shape="font-size:30px;color:' . $clr . ';z-index:-1;top:-10px;" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:#111111;width:50px;height:4px;top:4px;left:1px;"]

													<p style="text-align: center;"><span style="font-family: Montserrat; font-size: 36px; font-weight: 300; letter-spacing: 10px; color: #999999;"><span style="font-family: Poppins; font-size: 40px; font-weight: bold;">SERVICES</span></span><span style="font-family: Montserrat; font-size: 36px; color: #666666; font-weight: 300; letter-spacing: 10px;">
													</span>

													[/cz_title]',
					),
					array(
					  'n'  => '24',
					  'e'  => 'con',
					  's'  => '[cz_title title_pos="cz_title_pos_inline" css_right_line_left="1px" icon_before_type="icon" icon="fa fa-chevron-right" image_as_icon_size="thumbnail" image_as_icon_after_size="thumbnail" sk_overall="" sk_shape="font-size:30px;color:' . $clr . ';" sk_lines_con="height:4px;top:5px;" sk_lines="background-color:#111111;width:50px;height:4px;top:4px;left:1px;" sk_icon_before="color:' . $clr . ';font-size:30px;" sk_icon_after=""]

													<p style="text-align: left;"><span style="font-family: Montserrat; font-size: 36px; font-weight: 300; letter-spacing: 10px; color: #999999;"><span style="font-family: Poppins; font-size: 40px; font-weight: bold;">SERVICES</span></span><span style="font-family: Montserrat; font-size: 36px; color: #666666; font-weight: 300; letter-spacing: 10px;">
													</span>

													[/cz_title]',
					),
				),


				/* 2 Buttons */
				'cz_2_buttons' => array(
					'1 cz_prevent_title_title2_link_link2',
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style4" sk_icon="color:#ffffff;font-size:14px;border-width:0px 0px 0px 0px;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_btn1="padding:5px 40px 5px 25px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="padding:5px 25px 5px 40px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style5" sk_icon="color:#ffffff;font-size:16px;width:34px;height:34px;border-width:4px 4px 4px 4px;background-color:' . self::adjustBrightness($clr,-45) . ';border-color:#ffffff;" sk_btn1="border-radius:10px 0 0 10px;padding:7px 40px 7px 25px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="border-radius:0 10px 10px 0;padding:7px 25px 7px 40px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="icon" icon="fa fa-check" sep_text="OR" style="style1" sk_icon="color:#ffffff;font-size:22px;border-width:6px 6px 6px 6px;background-color:' . self::adjustBrightness($clr,-45) . ';border-color:#ffffff;" sk_btn1="border-radius:100px 0 0 100px;padding:7px 35px 7px 25px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="border-radius:0 100px 100px 0;padding:7px 25px 7px 35px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="icon" icon="fa fa-diamond" sep_text="OR" style="style5" sk_icon="color:#ffffff;font-size:24px;width:38px;height:38px;border-width:0px 0px 0px 0px;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_btn1="border-radius:10px 0 0 10px;border-bottom-style:solid;border-color:' . self::adjustBrightness($clr,-45) . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:7px 40px 7px 25px;border-width:2px 2px 2px 2px;color:' . self::adjustBrightness($clr,-45) . ';background-color:#ffffff;" sk_btn2="border-radius:0 10px 10px 0;border-bottom-style:solid;border-color:' . self::adjustBrightness($clr,-45) . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:7px 25px 7px 40px;border-width:2px 2px 2px 2px;color:' . self::adjustBrightness($clr,-45) . ';background-color:#ffffff;" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-45) . ';"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style6" sk_icon="color:' . self::adjustBrightness($clr,-45) . ';font-size:14px;height:24px;border-width:0px 0px 0px 0px;background-color:#ffffff;" sk_btn1="padding:5px 60px 5px 25px;margin-right:-10px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="padding:5px 25px 5px 60px;margin-left:-10px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style8" sk_icon="color:#ffffff;font-size:18px;width:40px;height:40px;border-width:0px 0px 0px 0px;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_btn1="border-radius:0 15px 0 15px;padding:7px 40px 7px 25px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="border-radius:0 15px 0 15px;padding:7px 25px 7px 40px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style7" sk_icon="color:' . self::adjustBrightness($clr,-45) . ';font-size:14px;height:24px;border-width:0px 0px 0px 0px;background-color:#ffffff;" sk_btn1="padding:5px 60px 5px 25px;margin-right:-10px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="padding:5px 25px 5px 60px;margin-left:-10px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style9" sk_icon="color:#ffffff;font-size:18px;width:40px;height:40px;border-width:0px 0px 0px 0px;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_btn1="border-radius:15px 0 15px 0;padding:7px 40px 7px 25px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="border-radius:15px 0 15px 0;padding:7px 25px 7px 40px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style2" sk_icon="color:#ffffff;font-size:12px;width:30px;height:27px;border-width:6px 6px 6px 6px;background-color:' . self::adjustBrightness($clr,-45) . ';border-color:#ffffff;" sk_btn1="border-radius:100px 0 0 100px;padding:7px 35px 7px 25px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="border-radius:0 100px 100px 0;padding:7px 25px 7px 35px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="icon" icon="fa fa-heart-o" sep_text="OR" style="style1" sk_icon="color:#ffffff;font-size:22px;width:46px;height:46px;border-width:0px 0px 0px 0px;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_btn1="border-radius:100px 0 0 100px;border-bottom-style:solid;border-color:' . self::adjustBrightness($clr,-45) . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:7px 30px 7px 25px;border-width:2px 2px 2px 2px;color:' . self::adjustBrightness($clr,-45) . ';background-color:#ffffff;" sk_btn2="border-radius:0 100px 100px 0;border-bottom-style:solid;border-color:' . self::adjustBrightness($clr,-45) . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:7px 25px 7px 30px;border-width:2px 2px 2px 2px;color:' . self::adjustBrightness($clr,-45) . ';background-color:#ffffff;" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-45) . ';"]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="icon" icon="fa fa-heart" sep_text="OR" style="style4" sk_icon="color:#ffffff;font-size:16px;border-width:6px 6px 6px 6px;background-color:' . self::adjustBrightness($clr,-45) . ';border-color:#ffffff;" sk_btn1="padding:5px 40px 5px 25px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="padding:5px 25px 5px 40px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style1" sk_icon="color:' . $clr . ';font-size:11px;width:16px;height:16px;border-width:0px 0px 0px 0px;background-color:#ffffff;border-color:#ffffff;" sk_btn1="border-radius:100px 0 0 100px;padding:7px 38px 7px 25px;margin-right:-6px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="border-radius:0 100px 100px 0;padding:7px 25px 7px 38px;margin-left:-6px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style7" sk_icon="color:#ffffff;font-size:14px;height:28px;background-color:' . self::adjustBrightness($clr,-45) . ';" sk_btn1="padding:5px 60px 5px 25px;margin-right:-10px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="padding:5px 25px 5px 60px;margin-left:-10px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'con',
					  's'  => '[cz_2_buttons title="TITLE ONE" title2="TITLE TWO" link="url:%23|||" link2="url:%23|||" css_position="relative;display: table;margin:0 auto" separator="text" sep_text="OR" style="style3" sk_icon="color:#ffffff;font-size:14px;width:36px;height:36px;border-width:4px 4px 4px 4px;background-color:' . self::adjustBrightness($clr,-45) . ';border-color:#ffffff;" sk_btn1="border-radius:10px 0 0 10px;padding:7px 40px 7px 25px;color:#ffffff;background-color:' . $clr . ';" sk_btn2="border-radius:0 10px 10px 0;padding:7px 25px 7px 40px;color:#ffffff;background-color:' . $clr . ';" sk_btn1_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';" sk_btn2_hover="color:#ffffff;background-color:' . self::adjustBrightness($clr,-95) . ';"]',
					),
				),


				/* Counter */
				'cz_counter' => array(
					'1 cz_prevent_before_number_symbol_after',
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_counter number="125" symbol="+" after="CLIENTS" position="tac cz_2rows" duration="3" sk_num="color:' . self::adjustBrightness($clr,-65) . ';font-size:80px;font-weight:200;" sk_ba="color:#333333;font-size:18px;font-weight:300;" sk_after="color:#333333;font-size:18px;font-weight:300;" sk_symbol="color:' . $clr . ';"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_counter number="1248" after="PROJECTS" position="tac cz_2rows" duration="3" sk_num="color:' . $clr . ';font-size:48px;font-weight:700;" sk_ba="color:#777777;font-size:16px;font-weight:300;" sk_after="color:#777777;font-size:16px;font-weight:300;"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_counter number="745" symbol="+" after="CLIENTS" position="tac cz_2rows" duration="3" sk_num="border-bottom-style:solid;border-color:' . $clr . ';padding-bottom:5px;border-bottom-width:6px;margin-bottom:15px;color:' . self::adjustBrightness($clr,-65) . ';font-size:80px;font-weight:500;width:220px;" sk_ba="color:#333333;font-size:18px;font-weight:300;" sk_after="color:#333333;font-size:18px;font-weight:300;" sk_symbol="color:' . $clr . ';"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_counter before="We Saved" number="15" symbol="M" after="Dollars" position="tac cz_1row" duration="2" sk_num="margin-bottom:-20px;margin-top:20px;color:' . $clr . ';font-size:48px;font-weight:700;" sk_ba="color:#333333;font-size:16px;font-weight:300;" sk_after="color:#333333;font-size:16px;font-weight:300;"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_counter number="19" after="YEARS" position="tac cz_2rows" duration="1" sk_num="border-radius:200px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:25px 25px 25px 25px;border-width:2px 2px 2px 2px;margin-bottom:10px;color:' . $clr . ';font-size:40px;font-weight:400;width:52px;" sk_ba="color:#000000;font-size:18px;" sk_after="color:#000000;font-size:18px;"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_counter number="1570000" after="lines of code" position="tac cz_1row" duration="4" sk_num="margin-bottom:-20px;margin-top:20px;color:' . $clr . ';font-family:Six Caps;font-size:60px;letter-spacing:2px;font-weight:700;" sk_ba="color:#606060;font-family:Six Caps;font-size:36px;letter-spacing:1px;font-weight:400;" sk_after="color:#606060;font-family:Six Caps;font-size:36px;letter-spacing:1px;font-weight:400;"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_counter number="200" symbol="+" after="CLIENTS" position="tac cz_2rows" duration="3" sk_num="border-radius:5px;padding:5px 25px 5px 25px;margin-bottom:10px;color:#ffffff;font-size:80px;font-weight:400;width:200px;background-color:unset !important;background-image:linear-gradient(0deg,' . self::adjustBrightness($clr,-65) . ',' . $clr . ');" sk_ba="color:' . $clr . ';font-size:18px;" sk_after="color:' . $clr . ';font-size:18px;"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_counter number="37" after="CLIENTS" position="tac cz_2rows" duration="3" sk_num="padding:10px 10px 10px 10px;margin-bottom:10px;color:#ffffff;font-size:42px;font-weight:700;width:100px;background-color:' . $clr . ';" sk_ba="color:#333333;font-size:16px;letter-spacing:1px;font-weight:300;" sk_after="color:#333333;font-size:16px;letter-spacing:1px;font-weight:300;"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_counter number="19" after="YEARS" position="tac cz_2rows" duration="1" sk_num="border-radius:200px;padding:25px 25px 25px 25px;margin-bottom:10px;color:#ffffff;font-size:80px;font-weight:400;width:100px;background-color:unset !important;background-image:linear-gradient(180deg,' . self::adjustBrightness($clr,-65) . ',' . $clr . ');" sk_ba="color:#777777;font-size:18px;" sk_after="color:#777777;font-size:18px;"]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_counter before="More Than" number="35" symbol="K" after="Followers" position="tac cz_1row" duration="2" sk_num="margin-bottom:-20px;margin-top:20px;color:' . $clr . ';font-family:Dosis;font-size:48px;font-weight:700;" sk_ba="color:#333333;font-family:Dosis;font-size:16px;font-weight:300;" sk_after="color:#333333;font-family:Dosis;font-size:16px;font-weight:300;"]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_counter number="1200" after="Projects" position="tac cz_1row" duration="2" sk_num="margin-bottom:-20px;margin-top:20px;color:' . $clr . ';font-family:Playfair Display;font-size:70px;letter-spacing:1px;font-weight:700;" sk_ba="color:#8e8e8e;font-family:Playfair Display;font-size:24px;letter-spacing:1px;font-weight:400;" sk_after="color:#8e8e8e;font-family:Playfair Display;font-size:24px;letter-spacing:1px;font-weight:400;"]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_counter number="19" after="YEARS" position="tac cz_2rows" duration="1" sk_num="border-radius:200px;padding:15px 20px 15px 20px;margin-bottom:10px;color:#ffffff;font-family:Impact;font-size:28px;font-weight:400;width:150px;background-color:' . $clr . ';" sk_ba="color:#666666;font-family:Impact;font-size:16px;letter-spacing:5px;" sk_after="color:#666666;font-family:Impact;font-size:16px;letter-spacing:5px;"]',
					),
				),


				/* cz_progress_bar */
				'cz_progress_bar' => array(
					'1 cz_prevent_title_number_icon',
					array(
					  'n'  => '1',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="Progress Title" number="88%" style="pbar3" sk_bar="height:4px;border-radius:5px;" sk_title="font-family:Montserrat;font-size:18px;" sk_num="color:#dd0000;font-family:Montserrat;font-size:16px;" sk_progress="background-color:#dd0000;"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="Progress Title" number="88%" icon="fa fa-check" style="pbar1" sk_bar="height:8px;border-radius:5px;" sk_icon="color:#6dc10d;" sk_title="color:#666666;font-family:Montserrat;font-size:18px;" sk_num="margin-top:3px;color:#2f7700;font-family:Montserrat;font-size:42px;" sk_progress="background-color:unset !important;background-image:linear-gradient(90deg,#72c117,#2f7700,#2f7700);"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="Progress Title" number="88%" style="pbar2" sk_bar="height:40px;border-radius:3px;" sk_title="margin-left:15px;margin-top:41px;color:#000000;font-family:Montserrat;font-size:18px;letter-spacing:1px;font-weight:700;" sk_num="border-radius:3px;padding:0px 9px 0px 9px;margin-top:2px;color:#ffffff;font-family:Montserrat;font-size:11px;background-color:#000000;" sk_progress="background-color:#aaaaaa;"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="Progress Title" number="67%" icon="fa fa-chevron-right" style="pbar2" sk_bar="height:46px;" sk_icon="color:#8224e3;" sk_title="margin-left:15px;margin-top:44px;color:#000000;font-family:Montserrat;font-size:20px;font-weight:700;" sk_num="border-radius:0px;padding:0px 9px 0px 9px;margin-top:2px;color:#ffffff;font-family:Montserrat;font-size:11px;background-color:unset !important;background-image:linear-gradient(45deg,#1e73be,#8224e3);" sk_progress="background-color:#dddddd;"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="Progress Title" number="67%" icon="fa fa-chevron-circle-right" style="pbar2" sk_bar="height:46px;border-radius:4px;" sk_icon="color:#ffffff;" sk_title="margin-left:15px;margin-top:44px;color:#ffffff;font-family:Montserrat;font-size:20px;letter-spacing:1px;font-weight:500;" sk_num="border-radius:4px;padding:0px 9px 0px 9px;margin-top:2px;color:#ffffff;font-family:Montserrat;font-size:11px;background-color:#ff0000;" sk_progress="background-color:unset !important;background-image:linear-gradient(0deg,#444444,#000000,#444444);"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="Progress Title" number="88%" style="pbar2" sk_bar="height:5px;border-radius:5px;" sk_title="color:#666666;font-family:Montserrat;font-size:20px;font-weight:500;" sk_num="border-radius:3px;color:#ffffff;font-family:Montserrat;font-size:14px;background-color:#0066bf;" sk_progress="background-color:#0066bf;"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="Progress Title" number="88%" style="pbar2" sk_bar="height:6px;border-radius:5px;" sk_icon="color:#ff0000;" sk_title="color:#444444;font-family:Montserrat;font-size:18px;letter-spacing:1px;font-weight:700;" sk_num="border-radius:35px;padding-left:12px;padding-right:12px;color:#ffffff;font-family:Montserrat;font-size:13px;background-color:#ff9800;" sk_progress="background-color:unset !important;background-image:linear-gradient(90deg,#ff0000,#ff9800);"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="Progress Title" number="88%" style="pbar2" sk_bar="height:40px;border-radius:100px;" sk_title="margin-left:20px;margin-top:40px;color:#006064;font-family:Montserrat;font-size:18px;letter-spacing:1px;font-weight:700;" sk_num="border-radius:35px;padding:0px 12px 0px 12px;margin-top:2px;color:#ffffff;font-family:Montserrat;font-size:11px;background-color:#0097a7;" sk_progress="background-color:#b2ebf2;"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="PROGRESS TITLE" number="88%" icon="fa fa-square-o" style="pbar3" sk_bar="height:5px;border-radius:5px;" sk_icon="color:#283593;" sk_title="color:#666666;font-family:Montserrat;font-size:20px;font-weight:300;" sk_num="border-radius:4px;border-bottom-style:solid;border-color:#283593;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:0px 8px 0px 8px;border-width:2px 2px 2px 2px;margin-top:-5px;color:#283593;font-family:Montserrat;font-size:12px;letter-spacing:1px;font-weight:300;" sk_progress="background-color:#283593;"]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'jpg',
					  's'  => '[cz_progress_bar title="Progress Title" number="67%" icon="fa fa-long-arrow-right" style="pbar2" sk_bar="height:36px;border-radius:0px;" sk_icon="color:#ffffff;" sk_title="margin-left:12px;margin-top:39px;color:#ffffff;font-family:Montserrat;font-size:14px;letter-spacing:1px;font-weight:500;" sk_num="border-radius:0px;padding:5px 12px 5px 12px;margin-top:-3px;color:#ffffff;font-family:Montserrat;font-size:11px;background-color:#0585c7;" sk_progress="background-color:#4d4d4d;"]',
					),
				),


				/* cz_stylish_list */
				'cz_stylish_list' => array(
					'2 cz_prevent_title_link_subtitle_icon',
					array(
					  'n'  => '1',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-check%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-check%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-check%22%7D%5D" list_style="none;margin: 0" sk_lists="margin-bottom:0px;" sk_icons="color:#64dd17;font-size:16px;"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%5D" list_style="none;margin: 0" sk_lists="font-weight:700;" sk_icons="color:#8224e3;font-size:14px;"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%5D" list_style="none;margin: 0" sk_lists="font-size:16px;font-weight:500;" sk_icons="border-radius:50px;padding:3px 1px 1px 3px;margin-right:10px;color:#ffffff;font-size:12px;background-color:unset !important;background-image:linear-gradient(45deg,#8224e3,#1e73be);"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%22%2C%22subtitle%22%3A%22males%20uada%20fames%20ac%20turpis%20egestas%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20%22%2C%22subtitle%22%3A%22ac%20turpis%20egestas%20males%20uada%20fames%20%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmo%22%2C%22subtitle%22%3A%22Donec%20dictum%20lectus%20non%20odio%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%5D" list_style="none;margin: 0" sk_lists="margin-bottom:0px;font-size:18px;font-weight:600;" sk_subtitle="margin-bottom:5px;margin-top:0px;color:#888888;font-size:13px;font-weight:300;" sk_icons="border-radius:5px;padding:3px 1px 1px 3px;margin-right:10px;color:#ffffff;font-size:22px;background-color:unset !important;background-image:linear-gradient(45deg,#8224e3,#1e73be);"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-angle-right%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-angle-right%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-angle-right%22%7D%5D" list_style="none;margin: 0" sk_lists="font-size:16px;font-weight:500;" sk_icons="border-radius:50px;padding:1px 0px 0px 1px;margin-right:10px;color:#000000;font-size:14px;box-shadow:4px -7px 11px rgba(0,0,0,0.28) inset ;background-color:#ffffff;"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%22%2C%22subtitle%22%3A%22males%20uada%20fames%20ac%20turpis%20egestas%22%2C%22icon%22%3A%22fa%20fa-check-circle%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20%22%2C%22subtitle%22%3A%22ac%20turpis%20egestas%20males%20uada%20fames%20%22%2C%22icon%22%3A%22fa%20fa-check-circle%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmo%22%2C%22subtitle%22%3A%22Donec%20dictum%20lectus%20non%20odio%22%2C%22icon%22%3A%22fa%20fa-check-circle%22%7D%5D" list_style="none;margin: 0" sk_lists="margin-bottom:0px;font-size:18px;font-weight:600;" sk_subtitle="margin-bottom:15px;margin-top:0px;color:#888888;font-size:13px;font-weight:300;" sk_icons="border-radius:50px;padding:4px 4px 4px 4px;margin-right:10px;color:#ffffff;font-size:22px;background-color:unset !important;background-image:linear-gradient(225deg,#ff2828,#b50000);"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-circle%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%5D" list_style="none;margin: 0" sk_lists="font-size:16px;font-weight:300;" sk_icons="border-radius:100px 100px 0 100px;margin-right:10px;margin-top:-2px;color:#1e73be;font-size:11px;background-color:#1e73be;"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%22%2C%22subtitle%22%3A%22males%20uada%20fames%20ac%20turpis%20egestas%22%2C%22icon%22%3A%22fa%20fa-file-o%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20%22%2C%22subtitle%22%3A%22ac%20turpis%20egestas%20males%20uada%20fames%20%22%2C%22icon%22%3A%22fa%20fa-file-text-o%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmo%22%2C%22subtitle%22%3A%22Donec%20dictum%20lectus%20non%20odio%22%2C%22icon%22%3A%22fa%20fa-file-code-o%22%7D%5D" list_style="none;margin: 0" sk_lists="margin-bottom:0px;font-size:18px;font-weight:600;" sk_subtitle="margin-bottom:15px;margin-top:0px;color:#888888;font-size:13px;font-weight:300;" sk_icons="border-radius:0px;padding:2px 2px 2px 2px;margin-right:15px;color:#ffffff;font-size:22px;background-color:#000000;"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-angle-right%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-angle-right%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-angle-right%22%7D%5D" list_style="none;margin: 0" sk_lists="font-size:16px;font-weight:500;" sk_icons="border-radius:50px;padding:1px 0px 0px 1px;margin-right:10px;color:#000000;font-size:14px;box-shadow:0px 4px 9px rgba(0,0,0,0.19) ;background-color:#ffffff;"]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-check%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-times%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-exclamation%22%7D%5D" list_style="none;margin: 0" sk_lists="" sk_icons="border-radius:50px;margin-bottom:2px;margin-top:2px;color:#ffffff;font-size:14px;background-color:unset !important;background-image:linear-gradient(180deg,#ff0000,#d10000);"]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-check%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-times%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-exclamation%22%7D%5D" list_style="none;margin: 0" sk_lists="" sk_icons="border-radius:4px;margin-bottom:2px;margin-right:10px;margin-top:2px;color:#ffffff;font-size:14px;background-color:unset !important;background-image:linear-gradient(180deg,#ff0000,#d10000);"]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-angle-double-right%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-angle-double-right%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-angle-double-right%22%7D%5D" list_style="none;margin: 0" sk_lists="margin-bottom:4px;font-size:14px;font-weight:300;" sk_icons="color:#1e73be;font-size:16px;"]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-angle-right%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-angle-right%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-angle-right%22%7D%5D" list_style="none;margin: 0" sk_lists="font-size:16px;font-weight:300;" sk_icons="border-radius:50px;padding:2px 0px 0px 2px;margin-bottom:2px;margin-top:2px;color:#ffffff;font-size:13px;background-color:unset !important;background-image:linear-gradient(180deg,#ffa726,#e65100);"]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%20sit%20amet%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20sicing%20elit%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmod%20tempor%20inc%22%2C%22icon%22%3A%22fa%20fa-chevron-right%22%7D%5D" list_style="none;margin: 0" sk_lists="font-size:16px;font-weight:500;" sk_icons="border-radius:4px;padding:3px 1px 1px 3px;margin-right:10px;color:#ffffff;font-size:12px;background-color:unset !important;background-image:linear-gradient(45deg,#8224e3,#1e73be);"]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'jpg',
					  's'  => '[cz_stylish_list items="%5B%7B%22title%22%3A%22Lorem%20ipsum%20dolor%22%2C%22subtitle%22%3A%22males%20uada%20fames%20ac%20turpis%20egestas%22%2C%22icon%22%3A%22fa%20fa-check%22%7D%2C%7B%22title%22%3A%22Cons%20ectetur%20adipi%20%22%2C%22subtitle%22%3A%22ac%20turpis%20egestas%20males%20uada%20fames%20%22%2C%22icon%22%3A%22fa%20fa-check%22%7D%2C%7B%22title%22%3A%22Sed%20doeiu%20esmo%22%2C%22subtitle%22%3A%22Donec%20dictum%20lectus%20non%20odio%22%2C%22icon%22%3A%22fa%20fa-check%22%7D%5D" list_style="none;margin: 0" sk_lists="margin-bottom:0px;font-size:18px;font-weight:600;" sk_subtitle="margin-bottom:15px;margin-top:0px;color:#888888;font-size:13px;font-weight:300;" sk_icons="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:3px 1px 1px 3px;border-width:1px 1px 1px 1px;margin-right:15px;color:#81d742;font-size:22px;"]',
					),
				),


				/* cz_working_hours */
				'cz_working_hours' => array(
					'2 cz_prevent_left_text_right_text_sub_badge_icon',
					array(
					  'n'  => '1',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Tuesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Wednesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Thursday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Friday%22%2C%22badge%22%3A%2220%25%20OFF%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%22%2C%22right_text%22%3A%2211%3A00%20~%2015%3A00%22%7D%2C%7B%22left_text%22%3A%22Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="margin-bottom:-5px;margin-top:5px;font-size:18px;" sk_right="margin-bottom:-7px;margin-top:12px;font-size:13px;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#cccccc;border-style:solid;"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Tuesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Wednesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Thursday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%22%2C%22right_text%22%3A%2211%3A00%20~%2015%3A00%22%7D%2C%7B%22left_text%22%3A%22Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="padding:2px 10px 2px 10px;margin-bottom:-12px;margin-top:-5px;color:#01579b;font-size:18px;letter-spacing:1px;font-weight:400;" sk_right="border-radius:4px;background-color:#e1f5fe;padding:2px 10px 2px 10px;margin-bottom:-7px;margin-top:7px;color:#01579b;font-size:11px;font-weight:300;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="margin-left:8px;border-color:#eeeeee;border-style:solid;"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Tuesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Wednesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Thursday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%22%2C%22right_text%22%3A%2211%3A00%20~%2015%3A00%22%7D%2C%7B%22left_text%22%3A%22Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="margin-bottom:-10px;margin-top:-10px;color:#455a64;font-family:Playfair Display;font-size:24px;" sk_right="border-radius:3px;background-color:#455a64;padding:1px 10px 3px 10px;margin-bottom:-7px;margin-top:7px;color:#ffffff;font-family:Playfair Display;font-size:13px;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#eeeeee;border-style:solid;"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%20~%20Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%20%26%20Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="margin-bottom:-15px;margin-top:-5px;color:#000000;font-family:Playfair Display;font-size:24px;letter-spacing:-1px;font-weight:700;" sk_right="margin-bottom:-3px;margin-top:12px;color:#999999;font-size:15px;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#cccccc;border-style:solid;"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Tuesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Wednesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Thursday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%22%2C%22right_text%22%3A%2211%3A00%20~%2015%3A00%22%7D%2C%7B%22left_text%22%3A%22Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" sk_left="background-color:#000000;padding:2px 10px 2px 10px;margin-top:-6px;color:#ffffff;font-size:16px;letter-spacing:1px;font-weight:700;" sk_right="background-color:#000000;padding:0px 7px 0px 7px;margin-top:7px;color:#ffffff;font-size:12px;font-weight:300;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#000000;border-style:solid;" sk_line="margin-top:-1px"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%20~%20Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%20%26%20Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="margin-bottom:-15px;margin-top:5px;color:#000000;font-family:Montserrat;font-size:22px;letter-spacing:-1px;font-weight:500;" sk_right="margin-bottom:-3px;margin-top:12px;color:#999999;font-size:15px;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#cccccc;border-style:solid;"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Tuesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Wednesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Thursday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%22%2C%22right_text%22%3A%2211%3A00%20~%2015%3A00%22%7D%2C%7B%22left_text%22%3A%22Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="margin-bottom:-20px;margin-top:2px;font-size:16px;font-weight:700;" sk_right="margin-bottom:-7px;margin-top:7px;font-size:12px;font-weight:300;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#eeeeee;border-style:solid;"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Tuesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Wednesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Thursday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%22%2C%22right_text%22%3A%2211%3A00%20~%2015%3A00%22%7D%2C%7B%22left_text%22%3A%22Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="border-radius:4px;background-color:#039be5;padding:2px 10px 2px 10px;margin-bottom:-7px;margin-top:2px;color:#ffffff;font-size:16px;letter-spacing:1px;font-weight:700;" sk_right="border-radius:4px;background-color:#01579b;padding:2px 10px 2px 10px;margin-bottom:-7px;margin-top:7px;color:#ffffff;font-size:11px;font-weight:300;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#eeeeee;border-style:solid;"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Tuesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Wednesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Thursday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%22%2C%22right_text%22%3A%2211%3A00%20~%2015%3A00%22%7D%2C%7B%22left_text%22%3A%22Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="margin-bottom:-10px;margin-top:-3px;font-size:22px;font-weight:200;" sk_right="border-radius:50px;background-color:#666666;padding:2px 10px 2px 10px;margin-bottom:-7px;margin-top:7px;color:#ffffff;font-size:12px;font-weight:300;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#eeeeee;border-style:solid;"]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Tuesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Wednesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Thursday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%22%2C%22right_text%22%3A%2211%3A00%20~%2015%3A00%22%7D%2C%7B%22left_text%22%3A%22Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="margin-bottom:-10px;margin-top:-3px;color:#000000;font-family:Playfair Display;font-size:22px;letter-spacing:-1px;font-weight:700;" sk_right="border-radius:3px;margin-bottom:-7px;margin-top:7px;color:#999999;font-family:Roboto;font-size:13px;font-weight:700;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#eeeeee;border-style:solid;"]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Tuesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Wednesday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Thursday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%22%2C%22right_text%22%3A%2211%3A00%20~%2015%3A00%22%7D%2C%7B%22left_text%22%3A%22Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" sk_left="padding:2px 10px 2px 0px;margin-top:-4px;color:#000000;font-size:18px;letter-spacing:1px;font-weight:300;" sk_right="background-color:#000000;padding:0px 7px 0px 7px;margin-top:3px;color:#ffffff;font-size:12px;font-weight:300;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#000000;border-style:solid;"]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'png',
					  's'  => '[cz_working_hours items="%5B%7B%22left_text%22%3A%22Monday%20~%20Friday%22%2C%22right_text%22%3A%229%3A00%20~%2017%3A00%22%7D%2C%7B%22left_text%22%3A%22Saturday%20%26%20Sunday%22%2C%22right_text%22%3A%22Closed%22%7D%5D" between_texts="true" sk_left="margin-bottom:-15px;margin-top:5px;color:#263238;font-family:Montserrat;font-size:22px;letter-spacing:-1px;font-weight:500;" sk_right="border-radius:35px;background-color:#546e7a;padding:2px 12px 2px 12px;margin-bottom:-3px;margin-top:12px;color:#ffffff;font-size:14px;" sk_badge="padding:0px 5px 0px 5px;margin-left:10px;margin-top:17px;font-size:10px;" sk_icon="" sk_line="border-color:#cccccc;border-style:solid;"]',
					),
				),


				/* cz_banner */
				'cz_banner' => array(
					'1 cz_prevent_title_link',
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Constructions" style="style1" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.5" image_hover_opacity="0.2" cbg_size="1" cbg_color="#111111" scale="1" sk_title="text-align:center;color:#ffffff;font-family:Montserrat;font-size:36px;font-weight:700;text-shadow:-1px 1px 1px rgba(0,0,0,0.59) ;" sk_box="background-color:#000000;border-radius:5px;"]
													<p style="text-align: center;"><span style="font-size: 14px; font-weight: 300;">Click here for more information</span></p>
													[/cz_banner]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Cooperation" style="style5" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.4" image_hover_opacity="0.2" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:25%;color:#ffffff;font-family:Raleway;font-size:40px;font-weight:200;text-shadow:-1px 1px 1px rgba(0,0,0,0.59) ;" sk_box="background-color:#000000;border-radius:5px;"]
													<p style="text-align: center;"><span style="font-size: 14px; font-weight: 300;">Click here for more information</span></p>
													[/cz_banner]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Music Festival" style="style9" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.5" image_hover_opacity="0.2" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#ffffff;font-family:Playfair Display;font-size:36px;font-weight:700;text-shadow:-1px 1px 1px rgba(0,0,0,0.59) ;" sk_box="background-color:#6e1bc1;border-radius:5px;"]
													<p style="text-align: center;"><span style="font-size: 14px; font-weight: 300;">Click here for more information</span></p>
													[/cz_banner]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_banner title="MARKETING" style="style16" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.2" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:16%;color:#ffffff;font-family:Montserrat;font-size:40px;letter-spacing:0px;font-weight:200;text-shadow:-1px 1px 1px rgba(0,0,0,0.4) ;" sk_box="background-color:#003468;"]<span style="font-size: 14px; font-weight: 300;">Learn More ...</span>[/cz_banner]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_banner title="BUSINESS" style="style16" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.2" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:16%;color:#000000;font-family:Montserrat;font-size:40px;font-weight:200;text-shadow:-1px 1px 1px rgba(0,0,0,0.4) ;" sk_box="background-color:#ffffff;"]<span style="font-size: 14px; font-weight: 300; color: #000000;">Learn More ...</span>[/cz_banner]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_banner title="SOLUTION" style="style14" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.2" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:22%;color:#ffffff;font-family:Montserrat;font-size:40px;font-weight:200;text-shadow:-1px 1px 1px rgba(0,0,0,0.4) ;" sk_box="background-color:#000000;"]<span style="font-size: 14px; font-weight: 300;">Learn More ...</span>[/cz_banner]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_banner title="PERSONAL" style="style19" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.3" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:23%;color:#000000;font-family:Playfair Display;font-size:36px;letter-spacing:12px;font-weight:100;" sk_box="background-color:#ffffff;"]<span style="font-size: 14px; font-weight: 300; color: #000000;">Learn More ...</span>[/cz_banner]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Fashion" style="style18" image="852" size="full" image_opacity="0.3" image_hover_opacity="0.5" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:21%;color:#ffffff;font-family:Playfair Display;font-size:52px;letter-spacing:-1px;font-weight:700;" sk_box="background-color:#000000;"]<span style="font-size: 14px; font-weight: 300; color: #ffffff;">Learn More ...</span>[/cz_banner]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Classic Photos" style="style6" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.3" image_hover_opacity="0.1" cbg_size="1" cbg_color="#111111" scale="1" sk_title="text-align:center;color:#000000;font-family:Playfair Display;font-size:38px;letter-spacing:-1px;font-weight:400;" sk_box="background-color:#eeee22;"]
													<p style="text-align: center;"><span style="font-size: 13px; font-weight: 300; color: #000000; line-height: 1;">Lorem ipsum dolor sit amet, conse ctetur adipis icing elit.</span></p>
													[/cz_banner]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_banner title="CHICKEN BURGER" style="style22" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.3" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#eded00;font-family:Luckiest Guy;font-size:32px;" sk_box="background-color:#000000;"]<span style="font-size: 32px; color: #ffffff; font-weight: bold;"> $9.99</span>[/cz_banner]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_banner title="DOUBLE BURGER" style="style4" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.9" image_hover_opacity="0.5" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#ffffff;font-family:Luckiest Guy;font-size:24px;" sk_box="background-color:#000000;" sk_caption="background-color:#bc0000;"]<span style="color: #ffffff; font-size: 24px;">$11.99</span>[/cz_banner]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_banner title="FRIED BURGER" style="style21" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.9" image_hover_opacity="0.5" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#bc0000;font-family:Luckiest Guy;font-size:32px;text-shadow:-1px 1px 25px #ffffff ;" sk_box="background-color:#000000;"]<span style="font-size: 24px; color: #ffffff;"><strong>$9.99</strong></span>[/cz_banner]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'con',
					  's'  => '[cz_banner title="CHEESE BURGER" style="style17" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.9" image_hover_opacity="0.5" cbg_size="1" cbg_color="#111111" scale="1" sk_title="text-align:center;color:#000000;font-family:Luckiest Guy;font-size:24px;text-shadow:-1px 1px 25px #ffffff ;" sk_box="background-color:#000000;"]<span style="color: #ffffff; font-size: 24px;">$7.99</span>[/cz_banner]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'con',
					  's'  => '[cz_banner title="MODERN" style="style22" image="http://www.xtratheme.com/img/1000x1000.jpg" size="full" image_opacity="0.2" image_hover_opacity="0.5" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#eded00;font-family:Playfair Display;font-size:48px;letter-spacing:-2px;font-weight:700;" sk_box="background-color:#1a237e;"]<span style="color: #ffffff;">Lear More ...</span>[/cz_banner]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Classic Architecture Festival" style="style2" image="http://www.xtratheme.com/img/1000x1000.jpg" size="full" image_opacity="0.2" image_hover_opacity="0.5" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#64dd17;font-family:Playfair Display;font-size:46px;letter-spacing:-2px;font-weight:700;" sk_box="background-color:#000000;"]<span style="color: #ffffff; font-family: Playfair Display; font-size: 16px;">Lear More ...</span>[/cz_banner]',
					),
					array(
					  'n'  => '16',
					  'e'  => 'con',
					  's'  => '[cz_banner title="AWESOME TOWER" style="style7" image="http://www.xtratheme.com/img/1000x1000.jpg" size="full" image_opacity="0.2" image_hover_opacity="0.5" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#7100e2;font-family:Playfair Display;font-size:48px;letter-spacing:-2px;font-weight:700;" sk_box="background-color:#ffffff;"]<span style="color: #7100e2; font-family: Playfair Display; font-size: 16px;">Learn More ...</span>[/cz_banner]',
					),
					array(
					  'n'  => '17',
					  'e'  => 'con',
					  's'  => '[cz_banner title="CREATIVE AGENCY" style="style14" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.2" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:32%;color:#ffffff;font-family:Montserrat;font-size:42px;letter-spacing:-2px;font-weight:700;" sk_box="background-color:#421174;"]<span style="color: #8224e3; font-size: 14px;">VIEW ALL PROJECTS</span>[/cz_banner]',
					),
					array(
					  'n'  => '18',
					  'e'  => 'con',
					  's'  => '[cz_banner title="BUSINESS SOLUTION" style="style15" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.2" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#ffffff;font-family:Impact;font-size:46px;letter-spacing:2px;font-weight:200;text-shadow:-1px 1px 1px rgba(0,0,0,0.58) ;" sk_box="background-color:#1a237e;"]<span style="color: #ffffff; font-size: 14px;">VIEW ALL PROJECTS</span>[/cz_banner]',
					),
					array(
					  'n'  => '19',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Air Max" style="style17" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.9" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="text-align:left;color:#013a89;font-family:Baloo;font-size:36px;font-weight:700;" sk_box="background-color:#013a89;box-shadow:-10px 10px #013a89 ;"]<span style="font-size: 14px; color: #ffffff;">Buy Now!</span>[/cz_banner]',
					),
					array(
					  'n'  => '20',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Maxxx" style="style11" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.9" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="text-align:left;color:#d61900;font-family:Baloo;font-size:36px;font-weight:700;" sk_box="background-color:#d61900;"]<span style="font-size: 14px; color: #ffffff;">Buy Now!</span>[/cz_banner]',
					),
					array(
					  'n'  => '21',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Sport" style="style20" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.9" image_hover_opacity="0.6" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#01a1ac;font-family:Baloo;font-size:36px;font-weight:700;" sk_box="background-color:#01a1ac;box-shadow:10px 10px #01a1ac ;"]<span style="font-size: 14px; color: #ffffff;">Buy Now!</span>[/cz_banner]',
					),
					array(
					  'n'  => '22',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Journey" style="style5" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.7" image_hover_opacity="0.3" cbg_size="1" cbg_color="#111111" scale="1" sk_title="color:#ffffff;font-family:Playball;font-size:54px;" sk_box="background-color:#000000;"]<span style="font-size: 13px;">View More</span>[/cz_banner]',
					),
					array(
					  'n'  => '23',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Natural" style="style12" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.3" image_hover_opacity="0.2" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:23%;color:#1b5e20;font-family:Arizonia;font-size:70px;" sk_box="background-color:#ffffff;"]',
					),
					array(
					  'n'  => '24',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Consultation" style="style8" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.7" image_hover_opacity="0.3" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:44%;color:#ffffff;font-family:Ubuntu;font-size:34px;font-weight:700;text-shadow:-1px 1px 1px rgba(0,0,0,0.32) ;" sk_box="background-color:#000000;"]',
					),
					array(
					  'n'  => '25',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Modeling" style="style5" image="http://www.xtratheme.com/img/600x800.jpg" size="full" image_opacity="0.3" image_hover_opacity="0.1" cbg="cz_bg_dots cz_bg_bl" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:87%;color:#ffffff;font-family:Playfair Display;font-size:42px;letter-spacing:-3px;" sk_box="background-color:#000000;"]<span style="font-size: 13px;">View More</span>[/cz_banner]',
					),
					array(
					  'n'  => '26',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Architecture" style="style6" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.3" image_hover_opacity="0.1" cbg_size="1" cbg_color="#111111" scale="1" sk_title="text-align:center;color:#ffffff;font-family:Playfair Display;font-size:42px;letter-spacing:-3px;" sk_box="background-color:#000000;"]<p style="text-align: center; line-height: 1;"><span style="font-size: 13px;">Lorem ipsum dolor sit amet, consec tetur adipis icing elit</span></p>[/cz_banner]',
					),
					array(
					  'n'  => '27',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Modeling" style="style16" image="http://www.xtratheme.com/img/600x800.jpg" size="full" image_opacity="0.4" cbg_size="1" cbg_color="#111111" class="cz_slanted_br" scale="1" sk_title="padding-top:66%;color:#ffffff;font-family:Playfair Display;font-size:42px;letter-spacing:-3px;" sk_box="background-color:#000000;"]<span style="font-size: 13px;">View More</span>[/cz_banner]',
					),
					array(
					  'n'  => '28',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Modeling" style="style16" image="http://www.xtratheme.com/img/600x800.jpg" size="full" image_opacity="0.4" cbg="cz_bg_border cz_bg_bl" cbg_size="1" cbg_color="#bc0000" scale="1" sk_title="padding-top:84%;color:#bc0000;font-family:Poppins;font-size:42px;letter-spacing:-3px;font-weight:900;" sk_box="background-color:#000000;"]<span style="font-size: 13px; color: #000000;"><span style="color: #999999;">View Gallery</span>
													</span>[/cz_banner]',
					),
					array(
					  'n'  => '29',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Nature" style="style16" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.4" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:18%;color:#ffffff;font-family:Sarina;font-size:42px;letter-spacing:0px;font-weight:900;" sk_box="background-color:#607d8b;"]<span style="font-size: 13px; color: #000000;"><span style="color: #ffffff;">E X P L O R E</span>
													</span>[/cz_banner]',
					),
					array(
					  'n'  => '30',
					  'e'  => 'con',
					  's'  => '[cz_banner title="Martial Art" style="style16" image="http://www.xtratheme.com/img/800x600.jpg" size="full" image_opacity="0.3" cbg_size="1" cbg_color="#111111" scale="1" sk_title="padding-top:17%;color:#bc0000;font-family:Poppins;font-size:42px;letter-spacing:-3px;font-weight:900;" sk_box="background-color:#ffffff;"]<span style="font-size: 13px; color: #000000;">View Gallery
													</span>[/cz_banner]',
					),
				),


				/* cz_countdown */ 
				'cz_countdown' => array(
					'1 cz_prevent_type_loop_date_year_day_hour_minute_second_plus_expire',
					array(
					  'n'  => '1',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:10px;border-bottom-style:solid;border-color:#aaaaaa;border-bottom-width:3px;margin-left:10px;margin-right:10px;width:95px;background-color:#eeeeee;" sk_title="font-size:13px;"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:10px;margin-left:10px;margin-right:10px;width:120px;background-color:unset !important;background-image:linear-gradient(0deg,#1d3dba,#9523e0);" sk_nums="color:#ffffff;font-size:42px;" sk_title="color:rgba(255,255,255,0.75);font-size:14px;"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:3px;margin-left:10px;margin-right:10px;width:140px;background-color:unset !important;background-image:linear-gradient(180deg,#727272,#474747,#000000);" sk_nums="color:#ffffff;font-family:Montserrat;font-size:48px;font-weight:200;text-shadow:1px 1px 1px rgba(0,0,0,0.74) ;" sk_title="color:rgba(255,255,255,0.75);font-size:14px;"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:200px;border-bottom-style:solid;border-color:#aaaaaa;border-bottom-width:3px;margin-left:10px;margin-right:10px;width:95px;background-color:#eeeeee;" sk_title="font-size:12px;font-weight:400;"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:200px;padding-left:2px;padding-right:2px;margin-left:15px;margin-right:15px;width:95px;box-shadow:0px 0px 24px rgba(0,0,0,0.14) ;background-color:#ffffff;" sk_nums="font-size:28px;" sk_title="font-size:11px;"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:200px;padding:25px 20px 25px 20px;margin-left:15px;margin-right:15px;width:100px;box-shadow:0px 14px 25px rgba(0,0,0,0.33) inset ;background-color:#eeee22;" sk_nums="font-size:38px;" sk_title="font-size:13px;"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:35px;padding:25px 20px 25px 20px;margin-left:15px;margin-right:15px;width:100px;box-shadow:0px 0px 25px rgba(0,0,0,0.24) inset ;background-color:#ffffff;" sk_nums="font-family:Montserrat;font-size:46px;font-weight:200;" sk_title="font-size:13px;"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:0px;padding-left:40px;padding-bottom:25px;padding-right:40px;margin-left:-15px;margin-right:-15px;width:100px;background-color:#013e8e;" sk_nums="color:#ffffff;font-family:Montserrat;font-size:48px;font-weight:200;text-shadow:1px 1px 1px rgba(0,0,0,0.74) ;" sk_title="color:rgba(255,255,255,0.75);font-size:14px;"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:10px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;margin-left:10px;margin-right:10px;width:115px;background-color:#ffffff;" sk_nums="font-size:36px;" sk_title="font-size:13px;"]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:10px;margin-left:30px;margin-right:30px;background-color:#ffffff;" sk_nums="font-family:Montserrat;font-size:70px;font-weight:200;" sk_title="font-size:13px;"]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:10px;padding-bottom:25px;padding-top:0px;margin-left:15px;margin-right:15px;width:180px;background-color:unset !important;background-image:linear-gradient(180deg,#1e73be,#142193);" sk_nums="color:#ffffff;font-family:Dosis;font-size:70px;font-weight:700;text-shadow:2px 2px 2px rgba(0,0,0,0.3) ;" sk_title="color:#ffffff;font-size:16px;"]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:10px;margin-left:30px;margin-right:30px;background-color:#ffffff;" sk_nums="color:#1e73be;font-family:Play;font-size:70px;" sk_title="font-size:13px;"]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:10px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-left:15px;margin-right:15px;width:180px;background-color:#ffffff;" sk_nums="color:#dd0000;font-family:Impact;font-size:70px;" sk_title="font-size:13px;"]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-right-style:solid;border-color:#dddddd;border-left-style:solid;padding-bottom:0px;padding-top:0px;border-left-width:2px;border-right-width:2px;margin-left:0px;margin-right:-2px;width:180px;background-color:#ffffff;" sk_nums="color:#7cc12c;font-family:Impact;font-size:70px;" sk_title="font-size:13px;"]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 20:23" pos="tac cz_countdown_center_v" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:200px;border-bottom-style:solid;border-color:#aaaaaa;border-bottom-width:3px;margin-bottom:10px;width:95px;background-color:#eeeeee;" sk_title="font-size:12px;font-weight:400;"]',
					),
					array(
					  'n'  => '16',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 21:13" pos="tac cz_countdown_center_v" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="border-radius:5px;border-bottom-style:solid;border-color:#cccccc;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding-bottom:5px;padding-top:5px;border-width:1px 1px 1px 1px;margin-bottom:10px;width:62px;background-color:#ffffff;" sk_nums="font-size:18px;" sk_title="font-size:10px;"]',
					),
					array(
					  'n'  => '17',
					  'e'  => 'jpg',
					  's'  => '[cz_countdown type="down" loop="120" date="2018/10/1 21:13" pos="tac cz_countdown_center_v" day="Day" hour="Hour" minute="Minute" second="Second" plus="s" expire="This event has been expired" sk_cols="padding-bottom:5px;padding-top:3px;margin-bottom:10px;width:62px;background-color:#f5f5f5;" sk_nums="font-size:18px;" sk_title="font-size:10px;"]',
					),
				),


				/* cz_gradient_title */
				'cz_gradient_title' => array(
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(90deg,#1a2a6c,#b21f1f,#fdbb2d);"]<span style="font-family: Impact; font-size: 90px;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#11998e,#38ef7d);"]<span style="font-family: Oswald; font-size: 90px; font-weight: bold;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#0f1c5b,#db2525);"]<span style="font-family: Montserrat; font-size: 74px; font-weight: bold;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(90deg,#ba0000,#fdbb2d,#ba0000);"]<span style="font-family: Impact; font-size: 90px;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(180deg,#ff00cc,#333399);"]<span style="font-family: Oswald; font-size: 90px; font-weight: bold;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#C33764,#1d2671);"]<span style="font-family: Montserrat; font-size: 74px; font-weight: bold;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(180deg,#fc4a1a,#f7b733);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#070000,#4c0001,#070000);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#000000,#0f9b0f);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(225deg,#34e89e,#0f3443);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(225deg,#e8cbc0,#636fa4);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(90deg,#ee0979,#ff6a00);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#2c3e50,#bdc3c7,#2c3e50);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(90deg,#5614b0,#dbd65c);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(90deg,#833ab4,#fd1d1d,#fcb045);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '16',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(180deg,#ffa17f,#00223e);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '17',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#000000,#9e9e9e);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '18',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#000000,#15aed8);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '19',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#000000,#ffc107);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '20',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="color" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#000000,#9d12ed);"]<span style="font-family: Poppins; font-size: 90px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
					array(
					  'n'  => '21',
					  'e'  => 'con',
					  's'  => '[cz_gradient_title bg_mode="image" sk_css="background-color:unset !important;background-image:linear-gradient(0deg,#1a34b2,#28cdff);"]<span style="font-family: Poppins; font-size: 110px; font-weight: 900;">TITLE</span>[/cz_gradient_title]',
					),
				),


				/* cz_service_box */
				'cz_service_box' => array(
					'2 cz_prevent_icon_number_title',
					array(
					  'n'  => '1',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="left" icon="fa fa-check-circle" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size: 2em;padding:5px;border-style:solid;border-width:1px;border-color:#dddddd;border-radius:100%;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="right" icon="fa fa-check-circle" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-radius:100%;border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-top:10px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="left" icon="fa fa-check-square" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-radius:5px;border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-top:8px;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="right" icon="fa fa-check-square" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-radius:5px;border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-top:8px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="left" icon="fa fa-check" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-top:8px;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="right" icon="fa fa-check" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;padding:5px 5px 5px 5px;border-width:1px 1px 1px 1px;margin-top:8px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="left" icon="fa fa-check-circle" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;border-radius:100%;background-color:' . $clr . ';padding:5px 5px 5px 5px;margin-top:10px;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="right" icon="fa fa-check-circle" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;border-radius:100%;background-color:' . $clr . ';padding:5px 5px 5px 5px;margin-top:10px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="left" icon="fa fa-check-square" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;border-radius:5px;background-color:' . $clr . ';padding:5px 5px 5px 5px;margin-top:8px;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '10',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="right" icon="fa fa-check-square" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;border-radius:5px;background-color:' . $clr . ';padding:5px 5px 5px 5px;margin-top:8px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '11',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="left" icon="fa fa-check" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;background-color:' . $clr . ';padding:5px 5px 5px 5px;margin-top:8px;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '12',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="right" icon="fa fa-check" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;background-color:' . $clr . ';padding:5px 5px 5px 5px;margin-top:8px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '13',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="left" icon="fa fa-globe" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:37px;margin-left:20px;margin-top:-12px;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;"><span style="font-size: 12px; color: #999999;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl.</span></div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '14',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="horizontal" align="right" icon="fa fa-globe" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:37px;margin-right:20px;margin-top:-12px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;"><span style="font-size: 12px; color: #999999;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl.</span></div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '15',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style9" type="horizontal" align="left" icon="fa fa-star" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;background-color:' . $clr . ';border-color:' . $clr . ';"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '16',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style9" type="horizontal" align="right" icon="fa fa-star" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;background-color:' . $clr . ';border-color:' . $clr . ';"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '17',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style9" type="horizontal" align="left" icon="fa fa-star-o" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-color:' . $clr . ';"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '18',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style9" type="horizontal" align="right" icon="fa fa-star-o" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-color:' . $clr . ';"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '19',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:100%;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-top:8px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '20',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="right" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:100%;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-top:8px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '21',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:5px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-top:8px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '22',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="right" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:5px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-top:8px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '23',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-top:8px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '24',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="right" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;margin-top:8px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '25',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:100%;background-color:' . $clr . ';margin-top:8px;font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '26',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="right" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:100%;background-color:' . $clr . ';margin-top:8px;font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '27',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:5px;background-color:' . $clr . ';margin-top:8px;font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '28',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="right" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:5px;background-color:' . $clr . ';margin-top:8px;font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '29',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="background-color:' . $clr . ';margin-top:8px;font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; text-align: justify; font-size: 13px; color: #666666; font-weight: 300;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '30',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="horizontal" align="right" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="background-color:' . $clr . ';margin-top:8px;font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: right;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '31',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="vertical" align="left" icon="fa fa-check-circle" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-radius:100%;border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '32',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="vertical" align="left" icon="fa fa-check-circle" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;border-radius:100%;background-color:' . $clr . ';"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '33',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="vertical" align="left" icon="fa fa-check-square" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-radius:10px;border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '34',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="vertical" align="left" icon="fa fa-check-square" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;border-radius:10px;background-color:' . $clr . ';"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '35',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="vertical" align="left" icon="fa fa-check" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '36',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="vertical" align="left" icon="fa fa-check" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;background-color:' . $clr . ';"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '37',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="vertical" align="left" icon="fa fa-rocket" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;border-radius:20px 0;border-bottom-style:solid;border-color:#dddddd;border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:1px 1px 1px 1px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '38',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="vertical" align="left" icon="fa fa-rocket" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;border-radius:20px 0;background-color:' . $clr . ';"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '39',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style1" type="vertical" align="left" icon="fa fa-rocket" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:3em;margin-bottom:0px;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '40',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="vertical" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="margin-bottom:5px;margin-top:5px;font-size:54px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '41',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style9" type="vertical" align="left" icon="fa fa-star" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:#ffffff;font-size:2em;background-color:' . $clr . ';border-color:' . $clr . ';"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '42',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style9" type="vertical" align="left" icon="fa fa-star" size="thumbnail" number="1" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="font-size:48px;" sk_line="width:50px;height:4px;" sk_icon="color:' . $clr . ';font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '43',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="vertical" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:100%;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '44',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="vertical" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:100%;background-color:' . $clr . ';font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '45',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="vertical" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:10px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '46',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="vertical" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:10px;background-color:' . $clr . ';font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '47',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="vertical" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '48',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="vertical" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="background-color:' . $clr . ';font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '49',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="vertical" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:0 20px;border-bottom-style:solid;border-color:' . $clr . ';border-top-style:solid;border-right-style:solid;border-left-style:solid;border-width:2px 2px 2px 2px;font-size:48px;font-weight:700;color:' . $clr . ';" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
					array(
					  'n'  => '50',
					  'e'  => 'con',
					  's'  => '[cz_service_box style="style10" type="vertical" align="left" icon="fa fa-bolt" size="thumbnail" number="3" title="Best Service" separator="off" scale="1" sk_overall="" sk_title="color:' . self::adjustBrightness($clr,-65) . ';font-size:24px;border-bottom-style:solid;" sk_num="border-radius:0 20px;background-color:' . $clr . ';font-size:48px;font-weight:700;color:#ffffff;" sk_line="width:50px;height:4px;" sk_icon="font-size:2em;"]
													<div style="line-height: 1.5; font-size: 13px; color: #666666; font-weight: 300; text-align: center;">Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl tinci dunt eturn sed molis velit.</div>
													[/cz_service_box]',
					),
				),


				/* cz_accordion */
				'cz_accordion' => array(
					'2',
					array(
					  'n'  => '1',
					  'e'  => 'jpg',
					  's'  => '[cz_accordion close_icon="fa fa-caret-down" open_icon="fa fa-caret-right" first_open="true" id="cz_86515" sk_active="color:#ffffff;background-color:#000000;" sk_content="color:#ffffff;background-color:#000000;"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'jpg',
					  's'  => '[cz_accordion close_icon="fa fa-angle-double-down" open_icon="fa fa-angle-double-right" toggle="true" first_open="true" id="cz_41622" sk_title="background:#fff;font-size:18px;padding-top:12px;padding-bottom:12px;border-style:solid;border-width:1px;border-color:#e0e0e0;border-radius:4px;" sk_active="border-style:solid;border-color:#ff5722;border-radius:4px 4px 0 0;" sk_content="color:#ffffff;background-color:#ff5722;border-style:solid;border-width:1px;border-color:#ff5722;border-radius:0 0 4px 4px;" sk_close_icon="color:#ffffff;background-color:#ff5722;padding:5px;border-radius:4px;box-shadow:1px 10px 21px rgba(0,0,0,0.15);" sk_open_icon="color:#0a0a0a;background-color:#f7f7f7;padding:5px;border-radius:4px;"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'jpg',
					  's'  => '[cz_accordion close_icon="fa fa-chevron-down" open_icon="fa fa-chevron-right" toggle="true" first_open="true" id="cz_13502" sk_active="color:#ffffff;background-color:transparent;background-image:linear-gradient(90deg,#daa64a,#323e75,#00ffe8);border-style:solid;border-color:#ffffff;box-shadow:1px 10px 34px rgba(0,0,0,0.1);" sk_title="font-size:16px;color:#6d6d6d;padding-top:10px;padding-bottom:10px;border-style:solid;border-color:#d8d8d8;border-radius:6px;" sk_content="border-style:solid;border-width:0px;" sk_close_icon="color:#0a0a0a;"]',
					),
					array(
					  'n'  => '4',
					  'e'  => 'jpg',
					  's'  => '[cz_accordion first_open="true" id="cz_60695" sk_title="font-size:18px;padding-top:12px;padding-bottom:12px;border-style:solid;border-width:1px;border-color:#848484;border-radius:30px;" sk_active="border-radius:30px 30px 0 0;" sk_content="border-style:solid;border-width:1px;border-color:#828282;border-radius:0 0 30px 30px;"]',
					),
					array(
					  'n'  => '5',
					  'e'  => 'jpg',
					  's'  => '[cz_accordion close_icon="fa fa-minus" open_icon="fa fa-plus" first_open="true" id="cz_77081" sk_active="color:#0a0a0a;border-style:solid;border-color:#0a0a0a;" sk_title="font-size:16px;color:#6d6d6d;padding-top:10px;padding-bottom:10px;padding-left:0px;border-style:solid;border-width:0px 0px 1px;border-color:#878787;" sk_content="padding-right:0px;padding-left:0px;border-style:solid;border-width:0px;"]',
					),
					array(
					  'n'  => '6',
					  'e'  => 'jpg',
					  's'  => '[cz_accordion close_icon="fa fa-angle-double-down" open_icon="fa fa-angle-double-right" toggle="true" first_open="true" id="cz_60310" sk_title="background:#282828;font-size:18px;color:#ffffff;padding-top:12px;padding-bottom:12px;border-style:solid;border-width:1px;border-color:#e0e0e0;border-radius:4px;" sk_active="color:#000000;background-color:#ffffff;border-radius:4px 4px 0 0;" sk_content="background:#282828;color:#ffffff;border-style:solid;border-width:1px;border-color:#e0e0e0;border-radius:0 0 4px 4px;" sk_close_icon="color:#ffffff;background-color:#000000;padding:5px;border-radius:4px;box-shadow:1px 10px 21px rgba(0,0,0,0.15);" sk_open_icon="color:#0a0a0a;background-color:#f7f7f7;padding:5px;border-radius:4px;"]',
					),
					array(
					  'n'  => '7',
					  'e'  => 'jpg',
					  's'  => '[cz_accordion close_icon="fa fa-long-arrow-down" open_icon="fa fa-long-arrow-right" toggle="true" first_open="true" icon_before="true" id="cz_83718" sk_active="color:#ffffff;border-style:solid;border-color:#ffffff;box-shadow:1px 10px 34px rgba(0,0,0,0.1);" sk_title="background:#282828;font-size:16px;color:#8e8e8e;padding-top:10px;padding-bottom:10px;padding-left:60px;border-style:solid;border-width:2px;border-color:#424242;" sk_content="background-color:#ffffff;border-style:solid;border-width:0px;"]',
					),
					array(
					  'n'  => '8',
					  'e'  => 'jpg',
					  's'  => '[cz_accordion close_icon="fa fa-minus-circle" open_icon="fa fa-plus-circle" toggle="true" first_open="true" icon_before="true" id="cz_53969" sk_title="font-size:18px;color:#727272;padding-top:4px;padding-bottom:4px;border-style:none;" sk_active="color:#0a0a0a;border-style:none;" sk_content="border-style:none;" sk_overall="margin-right:-20px;margin-left:-20px;"]',
					),
					array(
					  'n'  => '9',
					  'e'  => 'jpg',
					  's'  => '[cz_accordion first_open="true" id="cz_95818" sk_active="color:#0a0a0a;background-color:#ffffff;border-style:solid;border-color:#ffffff;box-shadow:1px 10px 34px rgba(0,0,0,0.1);" sk_title="font-size:16px;color:#8e8e8e;padding-top:10px;padding-bottom:10px;border-style:solid;border-color:#353535;" sk_content="color:#0a0a0a;background-color:#ffffff;border-style:solid;border-width:0px;" sk_close_icon="color:#0a0a0a;" sk_overall="background-color:#000000;padding:20px;"]',
					),
				),


				/* cz_tabs */
				'cz_tabs' => array(
					'2',
					array(
					  'n'  => '1',
					  'e'  => 'jpg',
					  's'  => '[cz_tabs id="cz_43825" sk_tabs="color:#727272;background-color:rgba(0,0,0,0.06);padding:10px 20px;margin-right:2px;border-style:none;" sk_active="color:#ffffff;background-color:#000000;" sk_content="color:#ffffff;background-color:#000000;padding:30px;margin-top:0px;"]',
					),
					array(
					  'n'  => '2',
					  'e'  => 'jpg',
					  's'  => '[cz_tabs style="cz_tabs_htc" id="cz_94252" sk_tabs="color:#727272;padding:10px 20px;margin-right:2px;border-style:none;border-radius:50px;" sk_active="color:#ffffff;background-color:#ff5722;" sk_content="background-color:rgba(255,87,34,0.05);padding:30px;margin-top:10px;border-radius:30px;" sk_row="background-color:rgba(255,87,34,0.05);border-radius:50px;display:inline-block;"]',
					),
					array(
					  'n'  => '3',
					  'e'  => 'jpg',
					  's'  => '[cz_tabs style="cz_tabs_vl cz_tabs_is_v" id="cz_91552" sk_tabs="color:#727272;background-color:rgba(0,0,0,0.06);padding:10px 20px;margin-right:0px;margin-bottom:1px;border-style:none;" sk_active="color:#ffffff;background-color:#3f51b5;" sk_content="color:#ffffff;background-color:#3f51b5;padding:30px;margin-top:0px;margin-left:0px;"]',
					),
				),

			);

			$p = apply_filters( 'xtra_presets', $p, $clr );

			return isset( $p[ $n ] ) ? $p[ $n ] : $p;
		}

		/**
		 * Adjust color 
		 * 
		 * @return string
		 */
		public static function adjustBrightness($hex, $steps) {
		    // Steps should be between -255 and 255. Negative = darker, positive = lighter
		    $steps = max(-255, min(255, $steps));

		    // Normalize into a six character long hex string
		    $hex = str_replace('#', '', $hex);
		    if (strlen($hex) == 3) {
		        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
		    }

		    // Split into three parts: R, G and B
		    $color_parts = str_split( $hex, 2 );
		    $return = '#';

		    foreach ( $color_parts as $color ) {
		        $color   = hexdec( $color ); // Convert to decimal
		        $color   = max( 0, min( 255, $color + $steps ) ); // Adjust color
		        $color   = dechex( $color );
		        $return .= str_pad( $color, 2, '0', STR_PAD_LEFT ); // Make two char hex code
		    }

		    return $return;
		}
		
	}

	// Run.
	Codevz_Presets::instance();

}