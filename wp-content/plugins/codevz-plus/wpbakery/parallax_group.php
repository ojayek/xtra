<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Parallax Group
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_parallax_group {

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
			'name'			=> esc_html__( 'Parallax Layers', 'codevz' ),
			'description'	=> esc_html__( 'Group of layered items', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' => 'param_group',
					'heading' => esc_html__( 'Layers', 'codevz' ),
					'param_name' => 'items',
					'params' => array(
						array(
							"type"        	=> "attach_image",
							"heading"     	=> esc_html__( "Image", 'codevz'),
							"param_name"  	=> "image",
							'edit_field_class' => 'vc_col-xs-99',
							'admin_label'	=> true
						),
						array(
							'type' 			=> 'cz_sk',
							'param_name' 	=> 'sk_pos',
							"heading"     	=> esc_html__( "Position", 'codevz'),
							'button' 		=> esc_html__( "Position", 'codevz'),
							'edit_field_class' => 'vc_col-xs-99',
							'settings' 		=> array( 'top', 'left', 'right', 'bottom' )
						),
						array(
							'type' 			=> 'cz_sk',
							'param_name' 	=> 'sk_item',
							"heading"     	=> esc_html__( "Item styling", 'codevz'),
							'button' 		=> esc_html__( "Item styling", 'codevz'),
							'edit_field_class' => 'vc_col-xs-99',
							'settings' 		=> array( 'rotate', 'blur', 'opacity', 'width', 'height', 'background', 'padding', 'margin', 'border', 'box-shadow' )
						),
						array(
							'type' 			=> 'checkbox',
							'heading' 		=> esc_html__( 'Hide on mobile?', 'codevz' ),
							'param_name' 	=> 'hide_on_mobile',
							'edit_field_class' => 'vc_col-xs-99'
						), 
						array(
							'type' 			=> 'cz_title',
							'param_name' 	=> 'cz_title',
							'class' 		=> '',
							'content' 		=> esc_html__( 'Parallax', 'codevz' )
						),
						array(
							"type"        	=> "dropdown",
							"heading"     	=> esc_html__( "Parallax", 'codevz' ),
							"param_name"  	=> "h",
							'edit_field_class' => 'vc_col-xs-99',
							'value'		=> array(
								esc_html__( 'Select', 'codevz' )					=> '',
								
								esc_html__( 'Vertical', 'codevz' )					=> 'v',
								esc_html__( 'Vertical + Mouse parallax', 'codevz' )		=> 'vmouse',
								esc_html__( 'Horizontal', 'codevz' )				=> 'true',
								esc_html__( 'Horizontal + Mouse parallax', 'codevz' )	=> 'truemouse',
								esc_html__( 'Mouse parallax', 'codevz' )				=> 'mouse',
							),
						),
						array(
							"type"        	=> "cz_slider",
							"heading"     	=> esc_html__( "Parallax speed", 'codevz' ),
							"description"   => esc_html__( "Parallax is according to page scrolling", 'codevz' ),
							'edit_field_class' => 'vc_col-xs-99',
							"param_name"  	=> "p",
							"value"  		=> "0",
							'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => -50, 'max' => 50 ),
							'dependency'	=> array(
								'element'		=> 'h',
								'value'			=> array( 'v', 'vmouse', 'true', 'truemouse' )
							),
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
							"param_name"  	=> "m",
							"value"  		=> "0",
							'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => -30, 'max' => 30 ),
							'dependency'	=> array(
								'element'		=> 'h',
								'value'			=> array( 'vmouse', 'truemouse', 'mouse' )
							),
						),
						array(
							'type' 			=> 'cz_title',
							'param_name' 	=> 'cz_title',
							'class' 		=> '',
							"content"     	=> esc_html__("Animation", 'codevz'),
						),
						Codevz_Plus::wpb_animation_tab( false ),
						array(
							"type"        	=> "cz_slider",
							"heading"     	=> esc_html__("Animation Delay", 'codevz'),
							"description" 	=> 'e.g. 500ms',
							"param_name"  	=> "anim_delay",
							'options' 		=> array( 'unit' => 'ms', 'step' => 100, 'min' => 0, 'max' => 5000 ),
						),
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_con',
					"heading"     	=> esc_html__( "Container styling", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'height', 'background', 'border', 'overflow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_mobile' ),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Extra class", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "class"
				)
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

		// Layers
		$layers = '';
		$items = json_decode( urldecode( $atts[ 'items' ] ), true );
		foreach ( $items as $i ) {
			$image = empty( $i['image'] ) ? '' : Codevz_Plus::get_image( $i['image'] );

			$delay = empty( $i['anim_delay'] ) ? '' : 'animation-delay:' . $i['anim_delay'] . ';';
			$style = Codevz_Plus::sk_style( $i, array( 'sk_pos' => array( '.xxx', $delay ) ) );
			$style = $style ? ' style="' . Codevz_Plus::get_string_between( $style, '{', '}' ) . '"' : '';

			$inner = Codevz_Plus::sk_style( $i, array( 'sk_item' => '.xxx' ) );
			$inner = $inner ? ' style="' . Codevz_Plus::get_string_between( $inner, '{', '}' ) . '"' : '';

			$i['class'] = '';
			$i['class'] .= empty( $i['hide_on_mobile'] ) ? '' : 'hide_on_mobile';
			$layers .= '<div' . Codevz_Plus::classes( $i, array( 'cz_layer_parallax' ) ) . $style . '>' . Codevz_Plus::_out(array(
				'parallax' 		=> isset( $i['p'] ) ? $i['p'] : '',
				'mparallax' 	=> isset( $i['m'] ) ? $i['m'] : '',
				'parallax_h' 	=> isset( $i['h'] ) ? $i['h'] : '',
			), '<div' . $inner . '>' . $image . '</div>' ) . '</div>';
		}

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];
			$css_array = array(
				'sk_con' => $css_id
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );
		}

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, array( $atts['id'], 'cz_group_parallax' ) ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>' . $layers . '</div>';

		return Codevz_Plus::_out( $atts, $out, [ 'parallax' ], $this->name, 'cz_parallax' );
	}

}