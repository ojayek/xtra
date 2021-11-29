<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Text Marquee
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_text_marquee {

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
			'name'			=> esc_html__( 'Text Marquee', 'codevz' ),
			'description'	=> esc_html__( 'Automatic scroll the text', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Text", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__( 'I am marquee text, you can edit me and insert your text ...', 'codevz' ),
					"param_name"  	=> "marquee"
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Duration", 'codevz'),
					"param_name"  	=> "duration",
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 20 ),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> '8',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Direction', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'direction',
					'value'		=> array(
						esc_html__( 'Left', 'codevz' ) => 'left',
						esc_html__( 'Right', 'codevz' ) => 'right',
					)
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Duplicate', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'duplicate',
					'value'		=> array(
						esc_html__( 'No', 'codevz' ) => 'false',
						esc_html__( 'Yes', 'codevz' ) => 'true',
					)
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Stop on hover?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'stop_on_hover'
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Gap", 'codevz'),
					"param_name"  	=> "gap",
					'options' 		=> array( 'unit' => '', 'step' => 10, 'min' => 0, 'max' => 300 ),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> '100',
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_marquee',
					"heading"     	=> esc_html__( "Styling", 'codevz'),
					'button' 		=> esc_html__( "Marquee", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_marquee_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_marquee_mobile' ),

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
				'sk_marquee' 		=> array( $css_id, $custom )
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

		} else {
			Codevz_Plus::load_font( $atts['sk_marquee'] );
		}

		// Classes
		$classes = array();
		$classes[] = 'cz_text_marquee';
		$classes[] = $atts['stop_on_hover'] ? 'cz_text_marquee_soh' : '';

		// Data
		$data = ' data-duration="' . $atts['duration'] . '000"';
		$data .= ' data-direction="' . $atts['direction'] . '"';
		$data .= ' data-duplicated="' . $atts['duplicate'] . '"';
		$data .= ' data-gap="' . $atts['gap'] . '"';

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . $data . '>' . do_shortcode( $atts['marquee'] ) . '</div><div aria-hidden="true"' . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '></div>';

		return Codevz_Plus::_out( $atts, $out, 'text_marquee', $this->name );
	}

}