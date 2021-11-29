<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Popup Modal Box
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_popup {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {

		add_shortcode( $this->name, [ $this, 'out' ] );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Popup Modal Box', 'codevz' ),
			'description'	=> esc_html__( 'Content medium window', 'codevz' ),
			'icon'			=> 'czi',
			'is_container' 	=> true,
			'js_view'		=> 'VcColumnView',
			'content_element'=> true,
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__( 'Unique ID', 'codevz' ),
					'description'  	=> esc_html__( 'You can call this popup with this ID, Example if ID is cz_popup_1, anywhere in page you can add #cz_popup_1 instead button URL', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> 'cz_popup_1',
					'param_name' 	=> 'id_popup',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Visibility mode?', 'codevz' ),
					'description'  	=> esc_html__( 'You can enable this option for auto load popup on page', 'codevz' ),
					'param_name' => 'visibility',
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Select', 'codevz' ) 								=> '',
						esc_html__( 'Once open on page start loading', 'codevz' ) 		=> 'cz_popup_page_start cz_popup_show_once',
						esc_html__( 'Everytime open on page start loading', 'codevz' ) 	=> 'cz_popup_page_start cz_popup_show_always',
						esc_html__( 'Once open when page fully loaded', 'codevz' ) 		=> 'cz_popup_page_loaded cz_popup_show_once',
						esc_html__( 'Everytime open when page fully loaded', 'codevz' ) => 'cz_popup_page_loaded cz_popup_show_always',
					),
				),
				array(
					'type' 			=> 'cz_slider',
					'heading' 		=> esc_html__( 'Show popup after specific seconds', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 60 ),
					'param_name' 	=> 'settimeout'
				),
				array(
					'type' 			=> 'cz_slider',
					'heading' 		=> esc_html__( 'Show popup after scrolling page', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '%', 'step' => 1, 'min' => 1, 'max' => 100 ),
					'param_name' 	=> 'after_scroll'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' => 'class',
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Close icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "icon"
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__( 'Overlay background', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' => 'overlay_bg',
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_popup',
					"heading"     	=> esc_html__( "Popup", 'codevz'),
					'button' 		=> esc_html__( "Popup", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'background', 'padding', 'width', 'height' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_popup_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icon',
					'hover_id' 		=> 'sk_icon_hover',
					"heading"     	=> esc_html__( "Close icon", 'codevz'),
					'button' 		=> esc_html__( "Close icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'box-shadow', 'top', 'right' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_hover' ),

			)

		);

		return $wpb ? vc_map( $settings ) : $settings;
	}

	/**
	 *
	 * Shortcode output
	 * 
	 * @return string
	 * 
	 */
	public function out( $atts, $content = '' ) {
		$atts = Codevz_Plus::shortcode_atts( $this, $atts );

		// ID
		if ( ! $atts['id'] ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];

			$css_array = array(
				'sk_popup' 		=> $css_id . ' .cz_popup_in',
				'sk_icon' 		=> $css_id . ' .cz_close_popup',
				'sk_icon_hover' => $css_id . ' .cz_close_popup:hover'
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );
		}

		// Icon
		$close = $atts['icon'] ? $atts['icon'] : 'fa czico-198-cancel';

		// Data.
		$data = $atts['settimeout'] 	? ' data-settimeout="' . $atts['settimeout'] . '000"' : '';
		$data .= $atts['after_scroll'] 	? ' data-after-scroll="' . str_replace( '%', '', $atts['after_scroll'] ) . '"' : '';
		$data .= $atts['overlay_bg'] 	? ' data-overlay-bg="' . $atts['overlay_bg'] . '"' : '';

		// Classes
		$classes = array();
		$classes[] = 'cz_popup_modal clr';
		$classes[] = $atts['visibility'];

		// Out
		$out = '<div id="' . $atts['id'] . '" class="' . $atts['id'] . '"><div id="' . $atts['id_popup'] . '"' . Codevz_Plus::classes( $atts, $classes ) . $data . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div class="cz_popup_in"><div>' . do_shortcode( $content ) . '</div></div><i class="' . $close . ' cz_close_popup"></i><div class="cz_overlay"></div></div></div>';

		return Codevz_Plus::_out( $atts, $out, [ 'popup( true )' ], $this->name );
	}

}