<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Stylish list
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_stylish_list {

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
			'name'			=> esc_html__( 'Stylish List', 'codevz' ),
			'description'	=> esc_html__( 'Custom list with icon', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' => 'param_group',
					'heading' => esc_html__( 'Lists', 'codevz' ),
					'param_name' => 'items',
					'params' => array(
						array(
							'type' 			=> 'textfield',
							'heading' 		=> esc_html__( 'Title', 'codevz' ),
							'param_name' 	=> 'title',
							'value'			=> 'This is list item',
							'edit_field_class' => 'vc_col-xs-99',
							'admin_label'	=> true
						),
						array(
							'type' 			=> 'textfield',
							'heading' 		=> esc_html__( 'Subtitle', 'codevz' ),
							'edit_field_class' => 'vc_col-xs-99',
							'param_name' 	=> 'subtitle',
						),
						array(
							"type" 			=> "dropdown",
							"holder" 		=> "div",
							"heading" 		=> esc_html__("Icon type","codevz"),
							"param_name" 	=> "icon_type",
							'edit_field_class' => 'vc_col-xs-99',
							"value" 	=> array(
								esc_html__( "Icon","codevz" ) => 'icon',
								esc_html__( "Image","codevz" ) => 'image',
								esc_html__( "Number","codevz" ) => 'number',
							),
						),
						array(
							"type"        	=> "cz_icon",
							"heading"     	=> esc_html__("Icon", 'codevz'),
							'edit_field_class' => 'vc_col-xs-99',
							"param_name"  	=> "icon",
							'dependency'	=> array(
								'element'		=> 'icon_type',
								'value'			=> array( 'icon' )
							)
						),
						array(
							"type"        	=> "colorpicker",
							"heading"     	=> esc_html__("Icon Color", 'codevz'),
							'edit_field_class' => 'vc_col-xs-99',
							"param_name"  	=> "icon_color",
							'dependency'	=> array(
								'element'		=> 'icon_type',
								'value'			=> array( 'icon' )
							)
						),
						array(
							"type" => "attach_image",
							"heading" => esc_html__("Image","codevz"),
							"param_name" => "image",
							'edit_field_class' => 'vc_col-xs-99',
							'dependency' => array(
								'element' 	=> 'icon_type',
								'value' 	=> array( 'image' )
							),
						),
						array(
							"type" => "textfield",
							"heading" => esc_html__("Number","codevz"),
							"param_name" => "number",
							'edit_field_class' => 'vc_col-xs-99',
							'dependency' => array(
								'element' 	=> 'icon_type',
								'value' 	=> array( 'number' )
							),
						),
						array(
							'type' 			=> 'vc_link',
							'heading' 		=> esc_html__( 'Link', 'codevz' ),
							'edit_field_class' => 'vc_col-xs-99',
							'param_name' 	=> 'link',
						),
					),
				),
				array(
					"type" 			=> "dropdown",
					"holder" 		=> "div",
					"heading" 		=> esc_html__("Default icon","codevz"),
					"param_name" 	=> "icon_type",
					'edit_field_class' => 'vc_col-xs-99',
					"value" 	=> array(
						esc_html__( "Icon","codevz" ) => 'icon',
						esc_html__( "Image","codevz" ) => 'image',
					),
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "default_icon",
					'dependency' => array(
						'element' 	=> 'icon_type',
						'value' 	=> array( 'icon' )
					),
				),
				array(
					"type" => "attach_image",
					"heading" => esc_html__("Image","codevz"),
					"param_name" => "image",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency' => array(
						'element' 	=> 'icon_type',
						'value' 	=> array( 'image' )
					),
				),
				array(
					'type' 				=> 'checkbox',
					'heading' 			=> esc_html__( 'Center on mobile?', 'codevz' ),
					'param_name' 		=> 'text_center',
					'edit_field_class' 	=> 'vc_col-xs-99',
					'group' 			=> esc_html__( 'Advanced', 'codevz' )
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
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_lists',
					'hover_id'	 	=> 'sk_lists_hover',
					"heading"     	=> esc_html__( "List items", 'codevz'),
					'button' 		=> esc_html__( "List items", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'width', 'float', 'display', 'font-size' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_lists_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_lists_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_lists_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_subtitle',
					"heading"     	=> esc_html__( "Subtitle", 'codevz'),
					'button' 		=> esc_html__( "Subtitle", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icons',
					'hover_id'	 	=> 'sk_icons_hover',
					"heading"     	=> esc_html__( "Icons", 'codevz'),
					'button' 		=> esc_html__( "Icons", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icons_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icons_hover' ),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__( "Icons hover FX", 'codevz' ),
					"param_name"  	=> "icon_hover_fx",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'None', 'codevz' ) 	=> 'cz_sl_icon_hover_none',
						esc_html__( 'Zoom in', 'codevz' ) 	=> 'cz_sl_icon_hover_zoom_in',
						esc_html__( 'Zoom out', 'codevz' ) 	=> 'cz_sl_icon_hover_zoom_out',
						esc_html__( 'Blur', 'codevz' ) 		=> 'cz_sl_icon_hover_blur',
						esc_html__( 'Flash', 'codevz' ) 	=> 'cz_sl_icon_hover_flash',
						esc_html__( 'Absorber', 'codevz' ) 	=> 'cz_sl_icon_hover_absorber',
						esc_html__( 'Wobble', 'codevz' ) 	=> 'cz_sl_icon_hover_wobble',
						esc_html__( 'Zoom in fade', 'codevz' ) 	=> 'cz_sl_icon_hover_zoom_in_fade',
						esc_html__( 'Zoom out fade', 'codevz' ) 	=> 'cz_sl_icon_hover_zoom_out_fade',
						esc_html__( 'Push in', 'codevz' ) 	=> 'cz_sl_icon_hover_push_in'
					),
					"std"  		=> 'cz_sl_icon_hover_zoom_in'
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
			$css = $custom ? $css_id . '{' . $custom . '}' : '';

			$css_array = array(
				'sk_overall' 		=> $css_id,
				'sk_brfx' 			=> $css_id . ':before',
				'sk_lists' 			=> $css_id . ' li',
				'sk_lists_hover' 	=> $css_id . ' li:hover',
				'sk_subtitle' 		=> $css_id . ' small',
				'sk_icons' 			=> $css_id . ' i',
				'sk_icons_hover' 	=> $css_id . ' li:hover i'
			);

			$css 	.= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			// Fix align
			if ( Codevz_Plus::contains( $atts['sk_lists'], 'text-align:center' ) ) {
				$css .= $css_id . ' li > div {display: inline-block}';
			}

			// Custom icon rotate
			if ( Codevz_Plus::contains( $atts['sk_icons'], 'rotate(45deg)' ) ) {
				$css .= $css_id . ' i:before{transform:rotate(-45deg)}';
			} else if ( Codevz_Plus::contains( $atts['sk_icons'], 'rotate(-45deg)' ) ) {
				$css .= $css_id . ' i:before{transform:rotate(45deg)}';
			}

		} else {
			Codevz_Plus::load_font( $atts['sk_lists'] );
			Codevz_Plus::load_font( $atts['sk_subtitle'] );
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_stylish_list clr';
		$classes[] = $atts['text_center'] ? 'center_on_mobile' : '';
		$classes[] = $atts['icon_hover_fx'];

		// Icon
		$default_icon = '';
		if ( $atts['icon_type'] === 'image' && $atts['image'] ) {
			$image = Codevz_Plus::get_image( $atts['image'], 'full', 0 );
			$default_icon = '<div class="cz_sl_icon cz_sl_image"><i class="mr10">' . $image . '</i></div>';
		} else if ( $atts['default_icon'] ) {
			$default_icon = '<div class="cz_sl_icon"><i class="' . $atts['default_icon'] . ' mr10" data-icon-color></i></div>';
		}

		// Description.
		$content = $content ? '<p class="xtra-stylish-list-content">' . do_shortcode( $content ) . '</p>' : '';

		// Out
		$out = $content . '<ul id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>';
		$items = json_decode( urldecode( $atts[ 'items' ] ), true );
		foreach ( $items as $i ) {

			$icon_color = empty( $i['icon_color'] ) ? '' : ' style="color:' . $i['icon_color'] . '"';

			if ( isset( $i['icon_type'] ) && $i['icon_type'] === 'image' && ! empty( $i['image'] ) ) {
				$image = Codevz_Plus::get_image( $i['image'], 'full', 0 );
				$ico = '<div class="cz_sl_icon cz_sl_image"><i class="mr10">' . $image . '</i></div>';
			} else if ( isset( $i['icon_type'] ) && $i['icon_type'] === 'number' && ! empty( $i['number'] ) ) {
				$ico = '<div class="cz_sl_icon"><i class="xtra-sl-number mr10"' . $icon_color . '>' . $i['number'] . '</i></div>';
			} else if ( ! empty( $i['icon'] ) ) {
				$ico = '<div class="cz_sl_icon"><i class="' . $i['icon'] . ' mr10"' . $icon_color . '></i></div>';
			} else {
				$ico = str_replace( 'data-icon-color', $icon_color, $default_icon );
			}
			$sub = empty( $i['subtitle'] ) ? '' : '<small>' . $i['subtitle'] . '</small>';
			$link = empty( $i['link'] ) ? '' : '<a' . Codevz_Plus::link_attrs( $i['link'] ) . '>';
			$link = Codevz_Plus::contains( $link, 'href' ) ? $link : '';
			$out .= empty( $i['title'] ) ? '' : '<li class="clr">' . $link . $ico . '<div><span>' . $i['title'] . $sub . '</span></div>' . ( $link ? '</a>' : '' ) . '</li>';
		}
		$out .= '</ul>';

		return Codevz_Plus::_out( $atts, $out, false, $this->name );
	}
}