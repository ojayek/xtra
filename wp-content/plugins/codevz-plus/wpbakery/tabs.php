<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Tabs
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_tabs {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );
		add_shortcode( 'cz_tab', array( $this, 'tab_child' ) );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Tabs', 'codevz' ),
			'description'	=> esc_html__( 'Unlimited tabbed content', 'codevz' ),
			'icon'			=> 'czi',
			'is_container' 	=> true,
			'js_view'		=> 'VcColumnView',
			'content_element'=> true,
			'as_parent'		=> array( 'only' => 'cz_tab' ), 
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Style', 'codevz'),
					'param_name' 	=> 'style',
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'Horizontal Top Left' 		=> 'cz_tabs_htl',
						'Horizontal Top Center' 	=> 'cz_tabs_htc',
						'Horizontal Top Right' 		=> 'cz_tabs_htr',
						'Horizontal Bottom Left' 	=> 'cz_tabs_hbl',
						'Horizontal Bottom Center' 	=> 'cz_tabs_hbc',
						'Horizontal Bottom Right' 	=> 'cz_tabs_hbr',
						'Vertical Left' 			=> 'cz_tabs_vl cz_tabs_is_v',
						'Vertical Right' 			=> 'cz_tabs_vr cz_tabs_is_v',
					)
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Effect', 'codevz'),
					'param_name' 	=> 'fx',
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Select', 'codevz' ) 		=> '',
						esc_html__( 'Blur', 'codevz' ) 			=> 'cz_tabs_blur',
						esc_html__( 'Flash', 'codevz' ) 		=> 'cz_tabs_flash',
						esc_html__( 'Zoom in', 'codevz' ) 		=> 'cz_tabs_zoom_in',
						esc_html__( 'Zoom out', 'codevz' ) 		=> 'cz_tabs_zoom_out',
						esc_html__( 'From Down', 'codevz' ) 	=> 'cz_tabs_fade_in_up',
						esc_html__( 'From Up', 'codevz' ) 		=> 'cz_tabs_fade_in_down',
						esc_html__( 'From Left', 'codevz' ) 	=> 'cz_tabs_fade_in_right',
						esc_html__( 'From Right', 'codevz' ) 	=> 'cz_tabs_fade_in_left',
						esc_html__( 'Rotate', 'codevz' ) 		=> 'cz_tabs_rotate',
						esc_html__( 'Right then Left', 'codevz' ) => 'cz_tabs_right_left',
						esc_html__( 'Swing', 'codevz' ) 		=> 'cz_tabs_swing',
						esc_html__( 'Bounce', 'codevz' ) 		=> 'cz_tabs_bounce',
						esc_html__( 'Wobble skew', 'codevz' ) 	=> 'cz_tabs_wobble_skew',
					)
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hover instead click?', 'codevz' ),
					'param_name' 	=> 'on_hover',
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
					'param_name' 	=> 'sk_con',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_row',
					"heading"     	=> esc_html__( "Tabs Row", 'codevz'),
					'button' 		=> esc_html__( "Tabs Row", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_row_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_row_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_tabs',
					'hover_id' 		=> 'sk_tabs_hover',
					"heading"     	=> esc_html__( "All tabs", 'codevz'),
					'button' 		=> esc_html__( "All tabs", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'color', 'text-align', 'font-size', 'background', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_tabs_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_tabs_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_tabs_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_active',
					"heading"     	=> esc_html__( "Active Tab", 'codevz'),
					'button' 		=> esc_html__( "Active Tab", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_active_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_active_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_tabs_i',
					"heading"     	=> esc_html__( "Icons", 'codevz'),
					'button' 		=> esc_html__( "Icons", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_tabs_i_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_tabs_i_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_content',
					"heading"     	=> esc_html__( "Content", 'codevz'),
					'button' 		=> esc_html__( "Content", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_mobile' ),

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

		if ( $wpb ) {
			vc_map( $settings );
		}

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> 'cz_tab',
			'name'			=> esc_html__( 'Tab item', 'codevz' ),
			'description'	=> esc_html__( 'Unlimited tabbed content', 'codevz' ),
			'icon'			=> 'czi',
			'is_container' 	=> true,
			'js_view'		=> 'VcColumnView',
			'content_element'=> true,
			'as_child'		=> array( 'only' => $this->name ), 
			'params'		=> array(
				array(
					'type' 				=> 'cz_sc_id',
					'param_name' 		=> 'id',
					'save_always' 		=> true
				),
				array(
					"type"        		=> "textfield",
					"heading"     		=> esc_html__("Tab title", 'codevz'),
					"value"  			=> "Tab title",
					'edit_field_class' 	=> 'vc_col-xs-99',
					"param_name"  		=> "title",
					'admin_label' 		=> true
				),
				array(
					"type"        		=> "textfield",
					"heading"     		=> esc_html__("Sub title", 'codevz'),
					'edit_field_class' 	=> 'vc_col-xs-99',
					"param_name"  		=> "subtitle"
				),
				array(
					'type'				=> 'dropdown',
					'heading'			=> esc_html__('Icon type', 'codevz'),
					'param_name'		=> 'icon_type',
					'edit_field_class' 	=> 'vc_col-xs-99',
					'value'				=> array(
						esc_html__( 'Icon', 'codevz' ) 	=> 'icon',
						esc_html__( 'Image', 'codevz' ) => 'image',
					)
				),
				array(
					"type"        		=> "cz_icon",
					"heading"     		=> esc_html__("Icon", 'codevz'),
					'edit_field_class' 	=> 'vc_col-xs-99',
					"param_name"  		=> "icon",
					'dependency'	=> array(
						'element'		=> 'icon_type',
						'value'			=> array( 'icon')
					)
				),
				array(
					"type"        	=> "attach_image",
					"heading"     	=> esc_html__("Image", 'codevz'),
					"param_name"  	=> "image",
					"edit_field_class" => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'icon_type',
						'value'			=> array( 'image')
					)
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Image size", 'codevz'),
					"description"   => esc_html__('Enter image size (e.g: "thumbnail", "medium", "large", "full"), Alternatively enter size in pixels (e.g: 200x100 (Width x Height)).', 'codevz'),
					"value"  		=> "full",
					"param_name"  	=> "size",
					"edit_field_class" => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'icon_type',
						'value'			=> array( 'image')
					)
				),
				array(
					'type' 				=> 'cz_sk',
					'param_name' 		=> 'sk_icon',
					"heading"     		=> esc_html__( "Icon styling", 'codevz'),
					'button' 			=> esc_html__( "Icon styling", 'codevz'),
					'edit_field_class' 	=> 'vc_col-xs-99',
					'settings' 			=> array( 'color', 'font-size', 'background' )
				),
				array(
					"type"        		=> "vc_link",
					"heading"     		=> esc_html__("Custom link", 'codevz'),
					"param_name"  		=> "link",
					'edit_field_class' 	=> 'vc_col-xs-99'
				),
			)
		);

		if ( $wpb ) {
			vc_map( $settings );
		}
	}

	/**
	 * Shortcode output
	 */
	public function out( $atts, $content = '' ) {
		$atts = vc_map_get_attributes( $this->name, $atts );

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
				'sk_con' 		=> array( $css_id, $custom ),
				'sk_brfx' 		=> $css_id . ':before',
				'sk_row' 		=> $css_id . ' .cz_tabs_nav div',
				'sk_tabs' 		=> $css_id . ' .cz_tab_a',
				'sk_tabs_hover' => $css_id . ' .cz_tab_a:hover',
				'sk_active' 	=> $css_id . ' .cz_tab_a.active, ' . $css_id . ' .cz_tab_a.cz_active',
				'sk_tabs_i' 	=> $css_id . ' .cz_tab_a i',
				'sk_content' 	=> $css_id . ' .cz_tab'
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );
		} else {
			Codevz_Plus::load_font( $atts['sk_tabs'] );
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_tabs clr';
		$classes[] = $atts['style'];
		$classes[] = $atts['fx'];
		$classes[] = $atts['on_hover'] ? 'cz_tabs_on_hover' : '';
		$classes[] = ( strpos( $atts['style'], 'hb' ) !== false ) ? 'cz_tabs_nav_after' : '';

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div class="cz_tabs_content">' . do_shortcode( $content ) . '</div></div>';

		$out .= Codevz_Plus::$vc_editable? '<div class="cz_tabs_org hide">' . $out . '</div>' : '';

		return Codevz_Plus::_out( $atts, $out, 'tabs( false, true )', $this->name );
	}

	/**
	 * Shortcode output of single tabs
	 */
	public function tab_child( $atts, $content = '' ) {
		$atts = vc_map_get_attributes( 'cz_tab', $atts );

		// ID
		$atts['id'] = $atts['id'] ? $atts['id'] : Codevz_Plus::uniqid();

		// Icon Style
		$icon_css = $atts['sk_icon'] ? ' style="' . $atts['sk_icon'] . '"' : '';
		$icon_class = ( $atts['title'] ? ' mr8' : '' );

		// Icon
		if ( $atts['icon_type'] === 'image' ) {
			$icon = $atts['image'] ? '<i class="cz_tab_image ' . $icon_class . '"' . $icon_css . '>' . Codevz_Plus::get_image( $atts['image'], $atts['size'] ). '</i>' : '';
		} else {
			$icon = $atts['icon'] ? '<i class="cz_tab_icon ' . $atts['icon'] . $icon_class . '"' . $icon_css . '></i>' : '';
		}

		// Subtitle
		$atts['title'] .= $atts['subtitle'] ? '<small>' . $atts['subtitle'] . '</small>' : '';
		$atts['title'] = $atts['title'] ? '<span class="cz_tab_in_title">' . $atts['title'] . '</span>' : '';

		// Out
		$out = '<a class="cz_tab_a hide" data-tab="' . $atts['id'] . '"'. Codevz_Plus::link_attrs( $atts['link'] ) . '>' . $icon . $atts['title'] . '</a><div id="' . $atts['id'] . '" class="cz_tab"><div>' . do_shortcode( $content ) . '</div></div>';

		return Codevz_Plus::_out( $atts, $out, 'tabs( "' . $atts['id'] . '", true )' );
	}

}