<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Process Road
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_process_road {

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
			'name'			=> esc_html__( 'Process Road', 'codevz' ),
			'description'	=> esc_html__( 'Grid regular work process', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Road", 'codevz'),
					"param_name"  	=> "road",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Center to Right', 'codevz') 		=> 'cz_road_cr',
						esc_html__( 'Center to Bottom', 'codevz') 		=> 'cz_road_cb',
						esc_html__( 'Center to Left', 'codevz') 		=> 'cz_road_tl cz_road_cl',
						esc_html__( 'Center to Top', 'codevz') 			=> 'cz_road_tl cz_road_ct',
						esc_html__( 'Right to bottom ( ┌ )', 'codevz') 	=> 'cz_road_rb',
						esc_html__( 'Top to bottom ( │ )', 'codevz') 	=> 'cz_road_tb',
						esc_html__( 'Top to right ( └ )', 'codevz') 	=> 'cz_road_tr',
						esc_html__( 'Top to left ( ┘ )', 'codevz') 		=> 'cz_road_tl',
						esc_html__( 'Left to right ( ─ )', 'codevz') 	=> 'cz_road_lr',
						esc_html__( 'Left to bottom ( ┐ )', 'codevz') 	=> 'cz_road_lb',
						esc_html__( 'Vertical & Right ( ├ )', 'codevz') => 'cz_road_vr',
						esc_html__( 'Vertical & Left ( ┤ )', 'codevz') 	=> 'cz_road_lb cz_road_vl',
						esc_html__( 'Horizontal & Up ( ┴ )', 'codevz') 	=> 'cz_road_tr cz_road_hu',
						esc_html__( 'Horizontal & Down ( ┬ )', 'codevz') => 'cz_road_hd',
						esc_html__( 'Cross ( ┼ )', 'codevz') 			=> 'cz_road_crs',
					),
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Height", 'codevz'),
					"value"  		=> "300px",
					'options' 		=> array( 'unit' => 'px', 'step' => 10, 'min' => 100, 'max' => 500 ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "line_height"
				),
				array(
					"type"        	=> "colorpicker",
					"heading"     	=> esc_html__("Line color", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "line_border-color"
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Lines style", 'codevz'),
					"param_name"  	=> "line_border-style",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'Solid' => 'solid',
						'Dotted' => 'dotted',
						'Dashed' => 'dashed',
					)
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Line size", 'codevz'),
					"param_name"  	=> "line_size",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Select', 'codevz' ) => '',
						'0px' 				=> 'cz_road_0px',
						'1px' 				=> 'cz_road_1px',
						'2px' 				=> 'cz_road_2px',
						'3px' 				=> 'cz_road_3px',
						'4px' 				=> 'cz_road_4px',
						'5px' 				=> 'cz_road_5px',
					),
					'std' => 'cz_road_1px'
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Indicator', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Type", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "type",
					'value'			=> array(
						esc_html__('Icon', 'codevz') 		=> 'icon',
						esc_html__('Number', 'codevz') 		=> 'number',
						esc_html__('Image', 'codevz') 		=> 'image'
					),
					'std'			=> 'icon'
				),
				array(
					"type"        	=> "attach_image",
					"heading"     	=> esc_html__("Image", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "image",
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> 'image'
					),
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "icon",
					'value'			=> 'fa fa-map-marker',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> 'icon'
					),
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Number", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "number",
					'value'			=> '1',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> 'number'
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Style', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'icon_style',
					'value'			=> array(
						esc_html__('Rhombus', 'codevz') 		=> 'cz_road_icon_rhombus',
						esc_html__('Rhombus Radius', 'codevz')	=> 'cz_road_icon_rhombus_2',
						esc_html__('Pin', 'codevz') 			=> 'cz_road_icon_rhombus_3',
						esc_html__('Custom', 'codevz') 			=> 'cz_road_icon_custom',
					),
				),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icon',
					'hover_id'	 	=> 'sk_icon_hover',
					"heading"     	=> esc_html__( "Icon styling", 'codevz'),
					'button' 		=> esc_html__( "Icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'background', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_hover' ),
				
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

			$css_array = array(
				'sk_brfx' 		=> $css_id . ':before',
				'sk_icon' 		=> $css_id . ' i',
				'sk_icon_hover' => '.vc_column-inner:hover ' . $css_id . ' i',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			$custom = $atts['line_height'] ? 'height:' . $atts['line_height'] . ';' : '';
			$custom .= $atts['line_border-color'] ? 'border-color:' . $atts['line_border-color'] . ';' : '';
			$custom .= $atts['line_border-style'] ? 'border-style:' . $atts['line_border-style'] . ';' : '';
			$custom .= $atts['anim_delay'] ? 'animation-delay:' . $atts['anim_delay'] . ';' : '';
			$css .= $custom ? $css_id . '{' . $custom . '}' : '';

		} else {
			Codevz_Plus::load_font( $atts['sk_icon'] );
		}

		// Size-width
		$size = Codevz_Plus::get_string_between( $atts['sk_icon'], 'font-size:', ';' );
		$size = $size ? ' style="width:' . $size . '"' : '';

		// Icon
		$icon = ( $atts['type'] === 'icon' ) ? '<i class="' . $atts['icon'] . '"></i>' : ( ( $atts['type'] === 'image' ) ? '<i><b>' . str_replace( '/>', $size . '>', Codevz_Plus::get_image( $atts['image'] ) ) . '</b></i>' : '<i><span>' . $atts['number'] . '</span></i>' );

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_process_road';
		$classes[] = $atts['road'];
		$classes[] = $atts['icon_style'];
		$classes[] = $atts['line_size'];

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>' . $icon . '<div class="cz_process_road_a"></div><div class="cz_process_road_b"></div></div>';

		return Codevz_Plus::_out( $atts, $out, false, $this->name );
	}

}