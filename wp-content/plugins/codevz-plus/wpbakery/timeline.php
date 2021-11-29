<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Tabs
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_timeline {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Shortcode settings ( vc_map )
	 * 
	 * @return array
	 */
	public function in( $wpb = false ) {

		add_shortcode( $this->name, [ $this, 'out' ] );
		add_shortcode( $this->name . '_item', array( $this, 'timeline_item' ) );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Timeline', 'codevz' ),
			'description'	=> esc_html__( 'Vertical timeline blocks', 'codevz' ),
			'icon'			=> 'czi',
			'is_container' 	=> true,
			'js_view'		=> 'VcColumnView',
			'content_element'=> true,
			'as_parent'		=> array( 'only' => $this->name . '_item' ), 
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Style', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'style',
					'value'		=> array(
						'Style 1' 		=> 'cz_tl_1',
						'Style 2' 		=> 'cz_tl_2',
						'Style 3' 		=> 'cz_tl_3',
						'Style 4' 		=> 'cz_tl_4',
						'Style 5' 		=> 'cz_tl_5',
					)
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Align', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'align',
					'value'		=> array(
						'Center' 	=> 'cz_a_c',
						'Left' 		=> 'cz_a_l',
						'Right' 	=> 'cz_a_r',
					)
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_vline',
					"heading"     	=> esc_html__( "Line styling", 'codevz'),
					'button' 		=> esc_html__( "Line", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_vline_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_vline_mobile' ),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Extra class", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "class",
				),
			)
		);

		if ( $wpb ) {
			vc_map( $settings );
		}

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name . '_item',
			'name'			=> esc_html__( 'Timeline Item', 'codevz' ), 
			'description'	=> esc_html__( 'Adding unlimited content as timeline', 'codevz' ),
			'icon'			=> 'czi',
			'is_container' 	=> true,
			'js_view'		=> 'VcColumnView',
			'content_element'=> true,
			'as_child'		=> array( 'only' => $this->name ), 
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Block Position', 'codevz'),
					'param_name' 	=> 'position',
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'Left' 		=> 'cz_tl_left',
						'Full Center' 		=> 'cz_tl_center',
						'Right' 		=> 'cz_tl_right',
					)
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Content Width", 'codevz'),
					"param_name"  	=> "content_width",
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => 'px', 'step' => 1, 'min' => 50, 'max' => 500 ),
					"dependency"	=> array(
						'element'		=> 'position',
						'value'			=> array('cz_tl_center')
					)
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "icon"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Date", 'codevz'),
					"param_name"  	=> "date",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icon',
					'hover_id'	 	=> 'sk_icon_hover',
					"heading"     	=> esc_html__( "Icon styling", 'codevz'),
					'button' 		=> esc_html__( "Icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'text-align', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_content',
					"heading"     	=> esc_html__( "Content styling", 'codevz'),
					'button' 		=> esc_html__( "Content", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_date',
					"heading"     	=> esc_html__( "Date styling", 'codevz'),
					'button' 		=> esc_html__( "Date", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_date_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_date_mobile' ),

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
	}

	/**
	 * Shortcode output
	 * 
	 * @return string
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

			$css_array = array(
				'sk_vline' => $css_id . ':before'
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_timeline_container';
		$classes[] = $atts['style'];
		$classes[] = $atts['align'];

		// Out
		$out = '<section id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div>' . do_shortcode( $content ) . '</div></section>';

		return Codevz_Plus::_out( $atts, $out, false, $this->name );
	}

	/**
	 *
	 * Shortcode output of Timeline Item
	 * 
	 * @return string
	 * 
	 */
	public function timeline_item( $atts, $content = '' ) {
		$atts = vc_map_get_attributes( $this->name . '_item', $atts );

		// ID
		if ( ! $atts['id'] ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}
		$css_id = '#' . $atts['id'];

		// Icon and Button
		$icon = '<i class="cz_tl_icon ' . $atts['icon'] .'"></i>';

		// Position
		$border_pos = 'left';
		$center_content_width='';
		if ( $atts['position'] === 'cz_tl_center' ) {
			$border_pos = 'bottom';
			$center_content_width=$atts['content_width'] ? $css_id .'.cz_timeline-block.cz_tl_center .cz_timeline-content{width:'.$atts['content_width'].'}':'';
		} else if ( $atts['position'] === 'cz_tl_right' ) {
			$border_pos = 'right';
		}

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {

			$css_array = array(
				'sk_brfx' 		=> $css_id . ':before',
				'sk_icon' 		=> $css_id . ' .cz_tl_icon',
				'sk_icon_hover' => $css_id . ':hover .cz_tl_icon',
				'sk_content' 	=> $css_id . ' .cz_timeline-content',
				'sk_date' 		=> $css_id . ' .cz_date',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			$css .= $center_content_width;
			$css .= $atts['anim_delay'] ? $css_id . '{animation-delay:' . $atts['anim_delay'] . '}' : '';

			//$css .= $center_content_width.'@media only screen and (min-width: 100px) and (max-width: 1170px) {'.$css_id.' .cz_timeline-content:before{border-color:transparent;border-right-color:'.$atts['content_background-color'].'}}.cz_a_l '.$css_id.' .cz_timeline-content:before{border-color:transparent;border-right-color:'.$atts['content_background-color'].'}.cz_a_r '.$css_id.' .cz_timeline-content:before{border-color:transparent;border-left-color:'.$atts['content_background-color'].'}';
		}

		// Classes
		$classes = array();
		$classes[] = 'cz_timeline-block';
		$classes[] = $atts['position'];

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div class="cz_timeline-i" >' . $icon . '</div><div class="cz_timeline-content">' . do_shortcode( $content ) . '<span class="cz_date">'. $atts['date'] .'</span></div></div>';

		return Codevz_Plus::_out( $atts, $out );
	}

}