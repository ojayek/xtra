<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Working Hours List
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_working_hours {

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
			'name'			=> esc_html__( 'Working Hours', 'codevz' ),
			'description'	=> esc_html__( 'Working preset time list', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' => 'param_group',
					'heading' => esc_html__( 'Items', 'codevz' ),
					'param_name' => 'items',
					'params' => array(
						array(
							'type' 			=> 'textfield',
							'heading' 		=> esc_html__( 'Left text', 'codevz' ),
							'param_name' 	=> 'left_text',
							'value' 		=> 'Monday',
							'edit_field_class' => 'vc_col-xs-99',
							'admin_label'	=> true
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__("Right text", 'codevz'),
							"param_name"  	=> "right_text",
							'value' 		=> '9:00 to 16:30',
							'edit_field_class' => 'vc_col-xs-99'
						),
						array(
							'type' 			=> 'textfield',
							'heading' 		=> esc_html__( 'Subtitle', 'codevz' ),
							'param_name' 	=> 'sub',
							'edit_field_class' => 'vc_col-xs-99'
						),
						array(
							'type' 			=> 'textfield',
							'heading' 		=> esc_html__( 'Badge', 'codevz' ),
							'param_name' 	=> 'badge',
							'edit_field_class' => 'vc_col-xs-99'
						),
						array(
							"type"        	=> "cz_icon",
							"heading"     	=> esc_html__("Icon", 'codevz'),
							"param_name"  	=> "icon",
							'edit_field_class' => 'vc_col-xs-99'
						),
					),
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Line between texts?", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "between_texts"
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_con',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_line',
					"heading"     	=> esc_html__( "Line", 'codevz'),
					'button' 		=> esc_html__( "Line", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_line_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_line_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_left',
					"heading"     	=> esc_html__( "Left text", 'codevz'),
					'button' 		=> esc_html__( "Left", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'background', 'padding' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_left_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_left_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_right',
					"heading"     	=> esc_html__( "Right text", 'codevz'),
					'button' 		=> esc_html__( "Right", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'background', 'padding' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_right_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_right_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_badge',
					"heading"     	=> esc_html__( "Badge", 'codevz'),
					'button' 		=> esc_html__( "Badge", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'background' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_badge_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_badge_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_sub',
					"heading"     	=> esc_html__( "Sub title", 'codevz'),
					'button' 		=> esc_html__( "Sub title", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_sub_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_sub_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icon',
					"heading"     	=> esc_html__( "Icon", 'codevz'),
					'button' 		=> esc_html__( "Icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_mobile' ),

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
				'sk_con' 	=> $css_id,
				'sk_brfx' 	=> $css_id . ':before',
				'sk_left' 	=> $css_id . ' .cz_wh_left',
				'sk_sub' 	=> $css_id . ' .cz_wh_sub',
				'sk_right' 	=> $css_id . ' .cz_wh_right',
				'sk_badge' 	=> $css_id . ' small',
				'sk_icon' 	=> $css_id . ' i',
				'sk_line' 	=> $css_id . ' .cz_wh_line',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			$css .= $atts['anim_delay'] ? $css_id . '{animation-delay:' . $atts['anim_delay'] . '}' : '';
		} else {
			Codevz_Plus::load_font( $atts['sk_left'] );
			Codevz_Plus::load_font( $atts['sk_right'] );
			Codevz_Plus::load_font( $atts['sk_badge'] );
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_wh';
		$classes[] = $atts['between_texts'] ? 'cz_wh_line_between' : '';

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>';
		
		// Content.
		$out .= $content ? '<p>' . do_shortcode( $content ) . '</p>' : '';

		// Description.
		$content = $content ? '<p class="xtra-working-hours-content">' . do_shortcode( $content ) . '</p>' : '';

		// Group.
		$items = json_decode( urldecode( $atts[ 'items' ] ), true );
		foreach ( (array) $items as $i ) {
			$icon 	= empty( $i['icon'] ) ? '' : '<i class="' . $i['icon'] . ' mr8"></i>';
			$badge 	= empty( $i['badge'] ) ? '' : '<small>' . $i['badge'] . '</small>';
			$sub 	= empty( $i['sub'] ) ? '' : '<span class="cz_wh_sub">' . $i['sub'] . '</span>';
			$left 	= empty( $i['left_text'] ) ? '' : '<span class="cz_wh_left">' . $icon . '<b>' . $i['left_text'] . '</b>' . $badge . $sub . '</span>';
			$right 	= empty( $i['right_text'] ) ? '' : '<span class="cz_wh_right">' . $i['right_text'] . '</span>';

			$out .= '<div class="mb10 last0 clr"><div class="clr">' . $left . $right . '</div><div class="cz_wh_line"></div></div>';
		}
		$out .= '</div>';

		return Codevz_Plus::_out( $atts, $out, 'working_hours', $this->name );
	}

}