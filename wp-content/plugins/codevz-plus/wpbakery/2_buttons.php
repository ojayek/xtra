<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

/**
 * 2 Buttons
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_2_buttons {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 *  Element settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( '2 Buttons', 'codevz' ),
			'description'	=> esc_html__( 'Buttons stick together', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Button 1", 'codevz'),
					"param_name"  	=> "title",
					"value"			=> "Button 1 title",
					'edit_field_class' => 'vc_col-xs-99',
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "vc_link",
					"heading"     	=> esc_html__("Link 1", 'codevz'),
					"param_name"  	=> "link",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Button 2", 'codevz'),
					"param_name"  	=> "title2",
					"value"			=> "Button 2 title",
					'edit_field_class' => 'vc_col-xs-99',
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "vc_link",
					"heading"     	=> esc_html__("Link 2", 'codevz'),
					"param_name"  	=> "link2",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Position', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'css_position',
					'value'			=> array(
						esc_html__( "Left", 'codevz' ) 			=> 'cz_2_btn_left',
						esc_html__( "Center", 'codevz' ) 		=> 'cz_2_btn_center',
						esc_html__( "Right", 'codevz' ) 		=> 'cz_2_btn_right',
						( Codevz_Plus::$is_rtl ? esc_html__( "Right", 'codevz') : esc_html__( "Left", 'codevz') ) . ' ' . esc_html__( '(Center in mobile)', 'codevz') 	=> 'cz_2_btn_left cz_mobile_btn_center',
						( Codevz_Plus::$is_rtl ? esc_html__( "Left", 'codevz') : esc_html__( "Right", 'codevz') ) . ' ' . esc_html__( '(Center in mobile)', 'codevz') 	=> 'cz_2_btn_right cz_mobile_btn_center',
					),
					'std'			=> 'cz_2_btn_center'
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_btn1',
					'hover_id' 		=> 'sk_btn1_hover',
					"heading"     	=> esc_html__( "Button 1", 'codevz'),
					'button' 		=> esc_html__( "Button 1", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_btn1_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_btn1_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_btn1_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_btn2',
					'hover_id' 		=> 'sk_btn2_hover',
					"heading"     	=> esc_html__( "Button 2", 'codevz'),
					'button' 		=> esc_html__( "Button 2", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_btn2_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_btn2_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_btn2_hover' ),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Separator', 'codevz'),
					'param_name'	=> 'separator',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'Text', 'codevz' ) 	=> 'text',
						esc_html__( 'Icon', 'codevz' ) 	=> 'icon',
					),
					'group' 		=> esc_html__( 'Separator', 'codevz' ),
				),

				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Icon", 'codevz'),
					"param_name"  	=> "icon",
					'value'			=> 'fa fa-check',
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Separator', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'separator',
						'value'			=> array( 'icon')
					)
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Text", 'codevz'),
					"param_name"  	=> "sep_text",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=>'OR',
					'group' 		=> esc_html__( 'Separator', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'separator',
						'value'			=> array( 'text')
					)
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Style', 'codevz'),
					'param_name'	=> 'style',
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Separator', 'codevz' ),
					'value'			=> array(
						esc_html__( 'Style', 'codevz' ) . ' 1' 	=> 'style1',
						esc_html__( 'Style', 'codevz' ) . ' 2' 	=> 'style2',
						esc_html__( 'Style', 'codevz' ) . ' 3' 	=> 'style3',
						esc_html__( 'Style', 'codevz' ) . ' 4' 	=> 'style4',
						esc_html__( 'Style', 'codevz' ) . ' 5' 	=> 'style5',
						esc_html__( 'Style', 'codevz' ) . ' 6' 	=> 'style6',
						esc_html__( 'Style', 'codevz' ) . ' 7' 	=> 'style7',
						esc_html__( 'Style', 'codevz' ) . ' 8' 	=> 'style8',
						esc_html__( 'Style', 'codevz' ) . ' 9' 	=> 'style9',
					)
				),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icon',
					"heading"     	=> esc_html__( "Separator", 'codevz'),
					'button' 		=> esc_html__( "Separator", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Separator', 'codevz' ),
					'settings' 		=> array( 'color', 'font-size', 'background' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_sep',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Separator', 'codevz' ),
					'settings' 		=> array( 'background', 'padding', 'margin' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_sep_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_sep_mobile' ),

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
				Codevz_Plus::wpb_animation_tab( true ),
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
	 *  Element output
	 */
	public function out( $atts, $content = '' ) {
		$atts = Codevz_Plus::shortcode_atts( $this, $atts );

		// ID
		if ( ! $atts['id'] ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}

		// Buttons
		$btn1 = $atts['title'] ? '<a class="cz_btn btn1" ' . Codevz_Plus::link_attrs( $atts['link'] ) . '><strong>' . $atts['title'] . '</strong></a>' : '';
		$btn2 = $atts['title2'] ? '<a class="cz_btn btn2" ' . Codevz_Plus::link_attrs( $atts['link2'] ) . '><strong>' . $atts['title2'] . '</strong></a>' : '';

		// Separator
		if ( $atts['separator'] === 'icon' ) {
			$sep = '<i class="' . $atts['icon'] . '"></i>';
		} else {
			$sep = '<i><span>'.$atts['sep_text'].'</span></i>';
		}
		
		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {

			$css_id = '#' . $atts['id'];

			$custom = $atts['anim_delay'] ? 'animation-delay:' . $atts['anim_delay'] . ';' : '';

			$css_array = array(
				'sk_xxx'		=> array( $css_id, $custom ),
				'sk_brfx' 		=> $css_id . ':before',
				'sk_icon'		=> $css_id . ' i',
				'sk_sep'		=> $css_id . ' .cz_2_btn_sep',
				'sk_btn1'		=> $css_id . ' .btn1',
				'sk_btn2'		=> $css_id . ' .btn2',
				'sk_btn1_hover'	=> $css_id . ' .btn1:hover',
				'sk_btn2_hover'	=> $css_id . ' .btn2:hover',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

		} else {
			Codevz_Plus::load_font( $atts['sk_icon'] );
			Codevz_Plus::load_font( $atts['sk_btn1'] );
			Codevz_Plus::load_font( $atts['sk_btn2'] );
		}

		// Classes.
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_2_btn';
		$classes[] = Codevz_Plus::contains( $atts['css_position'], 'cz' ) ? $atts['css_position'] : '';

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>' . $btn1 . '<div class="cz_2_btn_sep ' . $atts['style'] . '">' . $sep . '</div>' . $btn2 . '</div>';

		return Codevz_Plus::_out( $atts, $out, false, $this->name, 'cz_button' );
	}

}