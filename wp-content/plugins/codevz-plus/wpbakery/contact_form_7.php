<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Stylish Contact Form 7
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_contact_form_7 {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );

		$cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
		$contact_forms = array( esc_html__( 'Select Contact Form', 'codevz' ) => 0 );
		if ( $cf7 ) {
			foreach ( $cf7 as $cform ) {
				$contact_forms[ $cform->post_title ] = $cform->post_title;
			}
		} else {
			$contact_forms[ esc_html__( 'No contact forms found', 'codevz' ) ] = 0;
		}

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Contact Form 7 Pro', 'codevz' ),
			'description'	=> esc_html__( 'Stylish contact form 7', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Contact Form', 'codevz' ),
					'description' 	=> esc_html__( 'This element required plugin Contact Form 7, You can install it via Dashboard > Plugins > Add New', 'codevz' ),
					'param_name' 	=> 'cf7',
					'value' 		=> $contact_forms,
					'edit_field_class' => 'vc_col-xs-99',
					'admin_label' 	=> true,
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_con',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_label',
					"heading"     	=> esc_html__( "Labels", 'codevz'),
					'button' 		=> esc_html__( "Labels", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'text-align', 'font-size', 'margin' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_label_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_input',
					"heading"     	=> esc_html__( "Inputs", 'codevz'),
					'button' 		=> esc_html__( "Inputs", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'text-align', 'font-size', 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_input_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_input_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_button',
					'hover_id'	 	=> 'sk_button_hover',
					"heading"     	=> esc_html__( "Button", 'codevz'),
					'button' 		=> esc_html__( "Button", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'float', 'width', 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_button_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_button_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_button_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_messages',
					"heading"     	=> esc_html__( "Messages", 'codevz'),
					'button' 		=> esc_html__( "Messages", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_button_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_button_mobile' ),

				// Row Design
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling each row', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p',
					"heading"     	=> esc_html__( "All rows", 'codevz'),
					'button' 		=> esc_html__( "All rows", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_1',
					"heading"     	=> esc_html__( "Field 1", 'codevz'),
					'button' 		=> esc_html__( "Field 1", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'float', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_1_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_2',
					"heading"     	=> esc_html__( "Field 2", 'codevz'),
					'button' 		=> esc_html__( "Field 2", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'float', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_2_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_3',
					"heading"     	=> esc_html__( "Field 3", 'codevz'),
					'button' 		=> esc_html__( "Field 3", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'float', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_3_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_4',
					"heading"     	=> esc_html__( "Field 4", 'codevz'),
					'button' 		=> esc_html__( "Field 4", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'float', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_4_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_5',
					"heading"     	=> esc_html__( "Field 5", 'codevz'),
					'button' 		=> esc_html__( "Field 5", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'float', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_5_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_6',
					"heading"     	=> esc_html__( "Field 6", 'codevz'),
					'button' 		=> esc_html__( "Field 6", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'float', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_6_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_7',
					"heading"     	=> esc_html__( "Field 7", 'codevz'),
					'button' 		=> esc_html__( "Field 7", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'float', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_7_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_8',
					"heading"     	=> esc_html__( "Field 8", 'codevz'),
					'button' 		=> esc_html__( "Field 8", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'float', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_8_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_9',
					"heading"     	=> esc_html__( "Field 9", 'codevz'),
					'button' 		=> esc_html__( "Field 9", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'float', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_9_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_10',
					"heading"     	=> esc_html__( "Field 10", 'codevz'),
					'button' 		=> esc_html__( "Field 10", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'background', 'padding', 'margin', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_10_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_11',
					"heading"     	=> esc_html__( "Field 11", 'codevz'),
					'button' 		=> esc_html__( "Field 11", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'background', 'padding', 'margin', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_11_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_12',
					"heading"     	=> esc_html__( "Field 12", 'codevz'),
					'button' 		=> esc_html__( "Field 12", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'background', 'padding', 'margin', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_12_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_13',
					"heading"     	=> esc_html__( "Field 13", 'codevz'),
					'button' 		=> esc_html__( "Field 13", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'background', 'padding', 'margin', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_13_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_14',
					"heading"     	=> esc_html__( "Field 14", 'codevz'),
					'button' 		=> esc_html__( "Field 14", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'background', 'padding', 'margin', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_14_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_p_15',
					"heading"     	=> esc_html__( "Field 15", 'codevz'),
					'button' 		=> esc_html__( "Field 15", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'background', 'padding', 'margin', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_p_15_mobile' ),

				// Advanced
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hide on Desktop?', 'codevz' ),
					'param_name' 	=> 'hide_on_d',
					'edit_field_class' => 'vc_col-xs-4',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				), 
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hide on Tablet?', 'codevz' ),
					'param_name' 	=> 'hide_on_t',
					'edit_field_class' => 'vc_col-xs-4',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				), 
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hide on Mobile?', 'codevz' ),
					'param_name' 	=> 'hide_on_m',
					'edit_field_class' => 'vc_col-xs-4',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Parallax', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__( "Parallax", 'codevz' ),
					"param_name"  	=> "parallax_h",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Select', 'codevz' )					=> '',
						
						esc_html__( 'Vertical', 'codevz' )					=> 'v',
						esc_html__( 'Vertical + Mouse parallax', 'codevz' )		=> 'vmouse',
						esc_html__( 'Horizontal', 'codevz' )				=> 'true',
						esc_html__( 'Horizontal + Mouse parallax', 'codevz' )	=> 'truemouse',
						esc_html__( 'Mouse parallax', 'codevz' )				=> 'mouse',
					),
					"group"  		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__( "Parallax speed", 'codevz' ),
					"description"   => esc_html__( "Parallax is according to page scrolling", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "parallax",
					"value"  		=> "0",
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => -50, 'max' => 50 ),
					'dependency'	=> array(
						'element'		=> 'parallax_h',
						'value'			=> array( 'v', 'vmouse', 'true', 'truemouse' )
					),
					"group"  		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Stop when done', 'codevz' ),
					'param_name' 	=> 'parallax_stop',
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'parallax_h',
						'value'			=> array( 'v', 'vmouse', 'true', 'truemouse' )
					),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				), 
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Mouse speed", 'codevz'),
					"description"   => esc_html__( "Mouse parallax is according to mouse move", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "mparallax",
					"value"  		=> "0",
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => -30, 'max' => 30 ),
					'dependency'	=> array(
						'element'		=> 'parallax_h',
						'value'			=> array( 'vmouse', 'truemouse', 'mouse' )
					),
					"group"  		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Animation & Class', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				Codevz_Plus::wpb_animation_tab( false ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_brfx',
					"heading"     	=> esc_html__( "Block Reveal", 'codevz'),
					'button' 		=> esc_html__( "Block Reveal", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99 hidden',
					'group' 	=> esc_html__( 'Advanced', 'codevz' ),
					'settings' 		=> array( 'background' )
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Animation Delay", 'codevz'),
					"description" 	=> 'e.g. 500ms',
					"param_name"  	=> "anim_delay",
					'options' 		=> array( 'unit' => 'ms', 'step' => 100, 'min' => 0, 'max' => 5000 ),
					'edit_field_class' => 'vc_col-xs-6',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__( "Extra Class", 'codevz' ),
					"param_name"  	=> "class",
					'edit_field_class' => 'vc_col-xs-6',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),

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
			$custom = $atts['anim_delay'] ? 'animation-delay:' . $atts['anim_delay'] . ';' : '';

			$css_array = array(
				'sk_con' 			=> array( $css_id, $custom ),
				'sk_brfx' 			=> $css_id . ':before',
				'sk_label' 			=> $css_id . ' label',
				'sk_input' 			=> $css_id . ' input:not([type="submit"]), ' . $css_id . ' input[type="date"], ' . $css_id . ' textarea, ' . $css_id . ' select',
				'sk_button' 		=> $css_id . ' input[type="submit"], ' . $css_id . ' button',
				'sk_button_hover' 	=> $css_id . ' input[type="submit"]:hover, ' . $css_id . ' button:hover',
				'sk_messages' 		=> $css_id . ' .wpcf7-response-output',
				'sk_p' 				=> $css_id . ' p',
				'sk_p_1' 			=> $css_id . ' p:nth-child(2)',
				'sk_p_2' 			=> $css_id . ' p:nth-child(3)',
				'sk_p_3' 			=> $css_id . ' p:nth-child(4)',
				'sk_p_4' 			=> $css_id . ' p:nth-child(5)',
				'sk_p_5' 			=> $css_id . ' p:nth-child(6)',
				'sk_p_6' 			=> $css_id . ' p:nth-child(7)',
				'sk_p_7' 			=> $css_id . ' p:nth-child(8)',
				'sk_p_8' 			=> $css_id . ' p:nth-child(9)',
				'sk_p_9' 			=> $css_id . ' p:nth-child(10)',
				'sk_p_10' 			=> $css_id . ' p:nth-child(11)',
				'sk_p_11' 			=> $css_id . ' p:nth-child(12)',
				'sk_p_12' 			=> $css_id . ' p:nth-child(13)',
				'sk_p_13' 			=> $css_id . ' p:nth-child(14)',
				'sk_p_14' 	=> $css_id . ' p:nth-child(15)',
				'sk_p_15' 	=> $css_id . ' p:nth-child(16)',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );
		} else {
			Codevz_Plus::load_font( $atts['sk_label'] );
			Codevz_Plus::load_font( $atts['sk_input'] );
			Codevz_Plus::load_font( $atts['sk_button'] );
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_cf7 clr';

		// If plugin not installed
		if ( ! class_exists( 'WPCF7' ) ) {
			return '<pre>' . esc_html__( 'Plugin "Contact Form 7" not installed or activated', 'codevz' ) . '</pre>';
		}

		$cf7 = get_page_by_title( $atts['cf7'], 'object', 'wpcf7_contact_form' );

		// If not exists
		if ( ! $cf7 ) {
			$cf7 = get_posts(array(
				'numberposts' 	=> 1, 
				'post_type' 	=> 'wpcf7_contact_form'
			));
			$cf7 = isset( $cf7[0]->ID ) ? $cf7[0]->ID : 0;
		} else if ( isset( $cf7->ID ) ) {
			$cf7 = $cf7->ID;
		}

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div>' . do_shortcode( '[contact-form-7 id="' . $cf7 . '"]' ) . '</div></div>';

		return Codevz_Plus::_out( $atts, $out, false, $this->name );
	}

}