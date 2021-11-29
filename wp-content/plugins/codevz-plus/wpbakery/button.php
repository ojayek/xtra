<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Cannot access pages directly.

/**
 * Button
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_button {

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
			'name'			=> esc_html__( 'Button', 'codevz' ),
			'description'	=> esc_html__( 'Fully customizable', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Title", 'codevz'),
					"param_name"  	=> "title",
					"value"			=> "Button title",
					'edit_field_class' => 'vc_col-xs-99',
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Subtitle", 'codevz'),
					"param_name"  	=> "subtitle",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					"type"        	=> "vc_link",
					"heading"     	=> esc_html__("Link", 'codevz'),
					"param_name"  	=> "link",
					'edit_field_class' 	=> 'vc_col-xs-99',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Position', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'btn_position',
					'value'			=> array(
						esc_html__( "Select", 'codevz') 		=> '',
						esc_html__( "Inline", 'codevz') 		=> 'cz_btn_inline',
						esc_html__( "Block", 'codevz') 			=> 'cz_btn_block',
						( Codevz_Plus::$is_rtl ? esc_html__( "Right", 'codevz') : esc_html__( "Left", 'codevz') ) 	=> 'cz_btn_left',
						esc_html__( "Center", 'codevz') 		=> 'cz_btn_center',
						( Codevz_Plus::$is_rtl ? esc_html__( "Left", 'codevz') : esc_html__( "Right", 'codevz') ) 	=> 'cz_btn_right',
						( Codevz_Plus::$is_rtl ? esc_html__( "Right", 'codevz') : esc_html__( "Left", 'codevz') ) . ' ' . esc_html__( '(Center in mobile)', 'codevz') 	=> 'cz_btn_left cz_mobile_btn_center',
						( Codevz_Plus::$is_rtl ? esc_html__( "Left", 'codevz') : esc_html__( "Right", 'codevz') ) . ' ' . esc_html__( '(Center in mobile)', 'codevz') 	=> 'cz_btn_right cz_mobile_btn_center',
						( Codevz_Plus::$is_rtl ? esc_html__( "Right", 'codevz') : esc_html__( "Left", 'codevz') ) . ' ' . esc_html__( '(Block in mobile)', 'codevz') 	=> 'cz_btn_left cz_mobile_btn_block',
						( Codevz_Plus::$is_rtl ? esc_html__( "Left", 'codevz') : esc_html__( "Right", 'codevz') ) . ' ' . esc_html__( '(Block in mobile)', 'codevz') 	=> 'cz_btn_right cz_mobile_btn_block',
					)
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_button',
					'hover_id'		=> 'sk_hover',
					"heading"     	=> esc_html__( "Button styling", 'codevz'),
					'button' 		=> esc_html__( "Button", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_button_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_button_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_subtitle',
					'hover_id'		=> 'sk_subtitle_hover',
					"heading"     	=> esc_html__( "Subtitle styling", 'codevz'),
					'button' 		=> esc_html__( "Subtitle", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'subtitle',
						'not_empty'		=> true
					),
					'settings' 		=> array( 'color', 'font-size', 'background' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_hover' ),

				// Icon
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Icon', 'codevz' ),
				),
				array(
					"type" 			=> "dropdown",
					"holder" 		=> "div",
					"heading" 		=> esc_html__("Icon type","codevz"),
					"param_name" 	=> "icon_type",
					'edit_field_class' => 'vc_col-xs-99',
					"value" 		=> array(
						esc_html__( 'Icon', 'codevz' )			=> 'icon',
						esc_html__( 'Image', 'codevz' )			=> 'image',
					),
					'std' 			=> 'icon'
				),
				array(
					"type"        		=> "cz_icon",
					"heading"     		=> esc_html__("Select Icon", 'codevz'),
					"param_name"  		=> "icon",
					'edit_field_class' 	=> 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'icon_type',
						'value'			=> [ 'icon' ]
					)
				),
				array(
					"type" 				=> "attach_image",
					"heading" 			=> esc_html__( "Image","codevz" ),
					"param_name" 		=> "image",
					'edit_field_class' 	=> 'vc_col-xs-99',
					'dependency' 		=> array(
						'element' 			=> 'icon_type',
						'value' 			=> [ 'image' ]
					),
				),
				array(
					"type" 				=> "attach_image",
					"heading" 			=> esc_html__( "Hover Image","codevz" ),
					"param_name" 		=> "hover_image",
					'edit_field_class' 	=> 'vc_col-xs-99',
					'dependency' 		=> array(
						'element' 			=> 'icon_type',
						'value' 			=> [ 'image' ]
					),
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Image size", 'codevz'),
					"description"   => esc_html__('Enter image size (e.g: "thumbnail", "medium", "large", "full"), Alternatively enter size in pixels (e.g: 200x100 (Width x Height)).', 'codevz'),
					"param_name"  	=> "image_size",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency' 	=> array(
						'element' 		=> 'icon_type',
						'value' 		=> [ 'image' ]
					),
				),
				array(
					'type'				=> 'dropdown',
					'heading'			=> esc_html__('Icon position', 'codevz'),
					'edit_field_class' 	=> 'vc_col-xs-99',
					'param_name'		=> 'icon_position',
					'value'				=> array(
						'Before title' 	=> 'before',
						'After title' 	=> 'after',
					)
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icon',
					'hover_id'		=> 'sk_icon_hover',
					"heading"     	=> esc_html__( "Icon styling", 'codevz'),
					'button' 		=> esc_html__( "Icon", 'codevz'),
					'edit_field_class' 	=> 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background' ),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_hover' ),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Hover effect', 'codevz' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Button Effect', 'codevz'),
					'param_name'	=> 'btn_effect',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( "Select", 'codevz') 			=> 'cz_btn_no_fx',
						esc_html__( "Move Up", 'codevz') 			=> 'cz_btn_move_up',
						esc_html__( "Zoom In", 'codevz') 			=> 'cz_btn_zoom_in',
						esc_html__( 'Zoom Out', 'codevz') 			=> 'cz_btn_zoom_out',
						esc_html__( 'Winkle', 'codevz') 			=> 'cz_btn_winkle',
						esc_html__( 'Absorber', 'codevz') 			=> 'cz_btn_absorber',
						esc_html__( 'Low to Fill', 'codevz') 		=> 'cz_btn_half_to_fill',
						esc_html__( 'Low to Fill Vertical', 'codevz') => 'cz_btn_half_to_fill_v',
						esc_html__( 'Fill Up', 'codevz') 			=> 'cz_btn_fill_up',
						esc_html__( 'Fill Down', 'codevz')			=> 'cz_btn_fill_down',
						esc_html__( 'Fill Left', 'codevz') 			=> 'cz_btn_fill_left',
						esc_html__( 'Fill Right', 'codevz') 		=> 'cz_btn_fill_right',
						esc_html__( 'Single Hard Beat', 'codevz') 	=> 'cz_btn_beat',
						esc_html__( 'Flash', 'codevz') 				=> 'cz_btn_flash',
						esc_html__( 'Shine', 'codevz') 				=> 'cz_btn_shine',
						esc_html__( 'Circle Fade', 'codevz') 		=> 'cz_btn_circle_fade',
						esc_html__( 'Blur', 'codevz') 				=> 'cz_btn_blur',
						esc_html__( 'Unroll Vertical', 'codevz') 	=> 'cz_btn_unroll_v',
						esc_html__( 'Unroll Horizontal', 'codevz')	=> 'cz_btn_unroll_h',
					)
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Text Effect', 'codevz'),
					'param_name'	=> 'text_effect',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( "Select", 'codevz') 			=> 'cz_btn_txt_no_fx',
						esc_html__( 'Simple Fade', 'codevz') 		=> 'cz_btn_txt_fade',
						esc_html__( 'Text Move Up', 'codevz') 		=> 'cz_btn_txt_move_up',
						esc_html__( 'Text Move Down', 'codevz') 	=> 'cz_btn_txt_move_down',
						esc_html__( 'Text Move Right', 'codevz') 	=> 'cz_btn_txt_move_right',
						esc_html__( 'Text Move Left', 'codevz') 	=> 'cz_btn_txt_move_left',
						esc_html__( 'Move Up Show Icon', 'codevz')  => 'cz_btn_move_up_icon',
						esc_html__( 'Show Hidden Icon', 'codevz') 	=> 'cz_btn_show_hidden_icon',
						esc_html__( 'Ghost Icon', 'codevz') 		=> 'cz_btn_ghost_icon',
						esc_html__( 'Zoom Out In', 'codevz') 		=> 'cz_btn_zoom_out_in',
					)
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Alternative title", 'codevz'),
					"param_name"  	=> "alt_title",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'				=> 'text_effect',
						'value_not_equal_to'	=> array( 'cz_btn_txt_no_fx' )
					),
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Alternative Subtitle", 'codevz'),
					"param_name"  	=> "alt_subtitle",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'				=> 'text_effect',
						'value_not_equal_to'	=> array( 'cz_btn_txt_no_fx' )
					),
				),

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
						esc_html__( 'Select', 'codevz' )						=> '',
						esc_html__( 'Disable', 'codevz' )						=> 'o',
						esc_html__( 'Vertical', 'codevz' )						=> 'v',
						esc_html__( 'Vertical + Mouse parallax', 'codevz' )		=> 'vmouse',
						esc_html__( 'Horizontal', 'codevz' )					=> 'true',
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
	 * Shortcode output
	 */
	public function out( $atts, $content = '' ) {
		$atts = Codevz_Plus::shortcode_atts( $this, $atts );

		// ID
		if ( ! $atts['id'] ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}
		$parent = $atts['id'] . '_p';

		// Icon
		$icon = $icon_after = '';
		if( $atts['icon_type'] === 'icon' && $atts['icon'] ) {

			$icon = '<i class="' . $atts['icon'] . '"></i>';

		} else if( $atts['icon_type'] === 'image' && $atts['image'] ) {

			$atts['hover_image'] = $atts['hover_image'] ? $atts['hover_image'] : $atts['image'];

			$icon = '<i>' . Codevz_Plus::get_image( $atts['image'], $atts['image_size'] ) . Codevz_Plus::get_image( $atts['hover_image'], $atts['image_size'] ) . '</i>';

		}

		// Icon position.
		if( $atts['icon_position'] === 'after' ) {
			$icon_after = $icon;
			$icon = '';
			$atts['btn_effect'] .= ' cz_btn_icon_after';
		}

		// Styles
		if( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];
			
			$css_array = array(
				'sk_button' 		=> $css_id . ', ' . $css_id . ':before',
				'sk_hover' 			=> $css_id . ':hover, ' . $css_id . ':after',
				'sk_icon' 			=> $css_id . ' i',
				'sk_icon_hover' 	=> $css_id . ':hover i',
				'sk_subtitle' 		=> $css_id . ' small',
				'sk_subtitle_hover' => $css_id . ':hover small',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );
			
			$css .= $atts['anim_delay'] ? '.' . $parent . '{animation-delay:' . $atts['anim_delay'] . '}' : '';

			$css .= Codevz_Plus::sk_style( $atts, array( 'sk_brfx' => $css_id . '_p:before' ) );
		} else {
			Codevz_Plus::load_font( $atts['sk_button'] );
			Codevz_Plus::load_font( $atts['sk_subtitle'] );
		}

		// Subtitle
		$subtitle = $atts['subtitle'] ? '<small>' . $atts['subtitle'] . '</small>' : '';
		$alt_subtitle = $atts['alt_subtitle'] ? '<small>' . $atts['alt_subtitle'] . '</small>' : $subtitle;

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_btn';
		$classes[] = $subtitle ? 'cz_btn_subtitle' : '';
		$classes[] = $atts['text_effect'];
		$classes[] = $atts['btn_effect'];
		$classes[] = empty( $atts['btn_position'] ) ? 'cz_mobile_btn_center' : '';
		$classes[] = $atts['icon_type'] === 'image' ? 'cz_btn_has_image' : '';

		// Animation fix.
		if ( ! empty( $atts['css_animation'] ) ) {
			
			// WPBakery old versions
			wp_enqueue_script( 'waypoints' );
			wp_enqueue_style( 'animate-css' );

			// WPBakery after v6.x
			wp_enqueue_script( 'vc_waypoints' );
			wp_enqueue_style( 'vc_animate-css' );

			$parent .= ' clr wpb_animate_when_almost_visible ' . $atts['css_animation'];
			$atts['css_animation'] = '';
		}

		// Include extra class to parent div
		if ( $atts['class'] ) {
			$atts['btn_position'] .= ' ' . $atts['class'];
			$atts['class'] = '';
		}

		// Clear div
		$clr = Codevz_Plus::contains( $atts['btn_position'], array( 'btn_left', 'btn_right' ) ) ? '<div class="clr"></div>' : '';

		// Out
		$out = '<div class="' . $atts['btn_position'] . '"' . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div class="' . $parent . '"><a id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::link_attrs( $atts['link'] ) .'><span>' . $icon . '<strong>' . $atts['title'] . $subtitle . '</strong>' . $icon_after . '</span><b class="cz_btn_onhover">' . $icon . '<strong>' . ( $atts['alt_title'] ? $atts['alt_title'] : $atts['title'] ) . $alt_subtitle . '</strong>' . $icon_after . '</b></a></div></div>' . $clr;

		return Codevz_Plus::_out( $atts, $out, false, $this->name );
	}

}