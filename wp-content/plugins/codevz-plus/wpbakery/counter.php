<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Cannot access pages directly.

/**
 * Counter
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_counter {

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
			'name'			=> esc_html__( 'Counter', 'codevz' ),
			'description'	=> esc_html__( 'Count to certain number', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Prefix", 'codevz'),
					"description"   => esc_html__("Word or text before number", 'codevz'),
					"param_name"  	=> "before",
					'edit_field_class' => 'vc_col-xs-99',
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Number", 'codevz'),
					"param_name"  	=> "number",
					"value"  		=> "500",
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 500 ),
					'edit_field_class' => 'vc_col-xs-99',
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Symbol", 'codevz'),
					"param_name"  	=> "symbol",
					"value"  		=> "",
					'edit_field_class' => 'vc_col-xs-99',
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Suffix", 'codevz'),
					"description"   => esc_html__("Word or text after number", 'codevz'),
					"param_name"  	=> "after",
					"value"  		=> "Projects",
					'edit_field_class' => 'vc_col-xs-99',
					'admin_label' 	=> true
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Position', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'position',
					'value'			=> array(
						esc_html__( 'Inline | Left', 'codevz' ) 	=> 'tal cz_1row',
						esc_html__( 'Inline | Center', 'codevz' ) 	=> 'tac cz_1row',
						esc_html__( 'Inline | Right', 'codevz' ) 	=> 'tar cz_1row',
						esc_html__( 'Block | Left', 'codevz' ) 		=> 'tal cz_2rows',
						esc_html__( 'Block | Center', 'codevz' ) 	=> 'tac cz_2rows',
						esc_html__( 'Block | Right', 'codevz' ) 	=> 'tar cz_2rows'
					)
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Duration seconds", 'codevz'),
					"value"  		=> "4",
					"param_name"  	=> "duration",
					'admin_label' 	=> true,
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ),
					'edit_field_class' => 'vc_col-xs-99'
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Delay seconds", 'codevz'),
					"value"  		=> "0",
					"param_name"  	=> "delay",
					'admin_label' 	=> true,
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 0, 'max' => 10 ),
					'edit_field_class' => 'vc_col-xs-99'
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Disable comma?", 'codevz'),
					"param_name"  	=> "comma",
					'edit_field_class' => 'vc_col-xs-99'
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
				),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_overall',
					'hover_id' 		=> 'sk_overall_hover',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_num',
					'hover_id' 		=> 'sk_num_hover',
					"heading"     	=> esc_html__( "Number", 'codevz'),
					'button' 		=> esc_html__( "Number", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin' ),
					'dependency'	=> array(
						'element'		=> 'number',
						'not_empty'		=> true
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_num_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_num_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_num_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_symbol',
					'hover_id' 		=> 'sk_symbol_hover',
					"heading"     	=> esc_html__( "Symbol", 'codevz'),
					'button' 		=> esc_html__( "Symbol", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin' ),
					'dependency'	=> array(
						'element'		=> 'symbol',
						'not_empty'		=> true
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_symbol_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_symbol_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_symbol_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_ba',
					'hover_id' 		=> 'sk_ba_hover',
					"heading"     	=> esc_html__( "Prefix", 'codevz'),
					'button' 		=> esc_html__( "Prefix", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin' ),
					'dependency'	=> array(
						'element'		=> 'before',
						'not_empty'		=> true
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_ba_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_ba_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_ba_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_after',
					'hover_id' 		=> 'sk_after_hover',
					'heading' 		=> esc_html__( "Suffix", 'codevz'),
					'button' 		=> esc_html__( "Suffix", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin' ),
					'dependency'	=> array(
						'element'		=> 'after',
						'not_empty'		=> true
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_after_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_after_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_after_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'svg_bg',
					"heading"     	=> esc_html__( "Background layer", 'codevz'),
					'button' 		=> esc_html__( "Background layer", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'svg', 'background', 'top', 'left', 'width', 'height' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'svg_bg_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'svg_bg_mobile' ),

				// Advanced
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title_r',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Responsive', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hide on Desktop?', 'codevz' ),
					'param_name' 	=> 'hide_on_d',
					'edit_field_class' => 'vc_col-xs-3',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				), 
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hide on Tablet?', 'codevz' ),
					'param_name' 	=> 'hide_on_t',
					'edit_field_class' => 'vc_col-xs-3',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				), 
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hide on Mobile?', 'codevz' ),
					'param_name' 	=> 'hide_on_m',
					'edit_field_class' => 'vc_col-xs-3',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Center on Mobile?', 'codevz' ),
					'param_name' 	=> 'text_center',
					'edit_field_class' => 'vc_col-xs-3',
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
				'sk_overall' 		=> array( $css_id, $custom ),
				'sk_overall_hover' 	=> $css_id . ':hover',
				'svg_bg' 			=> $css_id . ' .cz_svg_bg:before',
				'sk_brfx' 			=> $css_id . ':before',
				'sk_num' 			=> $css_id . ' .cz_counter_num_wrap',
				'sk_num_hover' 		=> $css_id . ':hover .cz_counter_num_wrap',
				'sk_ba' 			=> $css_id . ' .cz_counter_before',
				'sk_ba_hover' 		=> $css_id . ':hover .cz_counter_before',
				'sk_after' 			=> $css_id . ' .cz_counter_after',
				'sk_after_hover' 	=> $css_id . ':hover .cz_counter_after',
				'sk_symbol'			=> $css_id . ' .cz_counter_num_wrap i',
				'sk_symbol_hover'	=> $css_id . ':hover .cz_counter_num_wrap i',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

		} else {
			Codevz_Plus::load_font( $atts['sk_num'] );
			Codevz_Plus::load_font( $atts['sk_ba'] );
			Codevz_Plus::load_font( $atts['sk_after'] );
			Codevz_Plus::load_font( $atts['sk_symbol'] );
		}

		// Classe
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_counter clr';
		$classes[] = $atts['position'];
		$classes[] = $atts['text_center'] ? 'cz_mobile_text_center' : '';

		// Out
		$before = $atts['before'] ? '<span class="cz_counter_before">' . $atts['before'] . '</span>' : '';
		$after  = $atts['after'] ? '<span class="cz_counter_after">' . $atts['after'] . '</span>' : '';
		$symbol = $atts['symbol'] ? '<i>' . $atts['symbol'] . '</i>' : '';
		$number = $atts['number'] ? $atts['number'] : '0';
		$number = '<span class="cz_counter_num_wrap"><span class="cz_counter_num">' . $number . '</span>' . $symbol . '</span>';

		$out = '<div id="' . $atts['id'] . '" data-disable-comma="' . $atts['comma'] . '" data-duration="' . $atts['duration'] . '000" data-delay="' . $atts['delay'] . '000"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div class="' . ( $atts['svg_bg'] ? 'cz_svg_bg' : '' ) . '">' . $before . $number . $after . '</div></div>';

		return Codevz_Plus::_out( $atts, $out, 'counter', $this->name );

	}

}