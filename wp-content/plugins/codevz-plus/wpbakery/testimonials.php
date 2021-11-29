<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Testimonials
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_testimonials {

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
			'name'			=> esc_html__( 'Testimonials', 'codevz' ),
			'description'	=> esc_html__( 'Clients testimonials', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 				=> 'cz_image_select',
					'heading' 			=> esc_html__( 'Template', 'codevz' ),
					'edit_field_class'  => 'vc_col-xs-99',
					'param_name' 		=> 'style',
					'options' 			=> array(
						'1'		=> Codevz_Plus::$url . 'assets/img/testimonial_1.jpg',
						'2'		=> Codevz_Plus::$url . 'assets/img/testimonial_2.jpg',
						'3'		=> Codevz_Plus::$url . 'assets/img/testimonial_3.jpg',
						'4'		=> Codevz_Plus::$url . 'assets/img/testimonial_4.jpg',
						'5'		=> Codevz_Plus::$url . 'assets/img/testimonial_5.jpg',
						'6'		=> Codevz_Plus::$url . 'assets/img/testimonial_6.jpg',
						'7'		=> Codevz_Plus::$url . 'assets/img/testimonial_7.jpg',
					),
					'value'				=> '1'
				),
				array(
					"type"        	=> "textarea_html",
					"heading"     	=> esc_html__("Content", 'codevz'),
					'edit_field_class'  => 'vc_col-xs-99',
					"param_name"  	=> "content",
					"value"  		=> "Your company have been great at keeping me in work, they always line something else up.",
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "attach_image",
					"heading"     	=> esc_html__("Image", 'codevz'),
					'edit_field_class'  => 'vc_col-xs-99',
					"param_name"  	=> "avatar",
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Name", 'codevz'),
					'edit_field_class'  => 'vc_col-xs-99',
					"param_name"  	=> "name",
					"value"  		=> "John Carter",
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Sub name", 'codevz'),
					'edit_field_class'  => 'vc_col-xs-99',
					"param_name"  	=> "subname",
					"value"  		=> "Businessman",
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Arrow', 'codevz' ),
					'param_name' 	=> 'arrow',
					'edit_field_class'  => 'vc_col-xs-99',
					'value'			=> array(
						'Select'		=> '',
						'Bottom Left'	=> 'cz_testimonials_bottom_arrow',
						'Top Left'		=> 'cz_testimonials_top_arrow',
					)
				),
				array(
					'type' 			=> 'colorpicker',
					'heading' 		=> esc_html__( 'Arrow color', 'codevz' ),
					'edit_field_class'  => 'vc_col-xs-99',
					'param_name' 	=> 'arrow_background',
					'value' 		=> 'rgba(167,167,167,0.1)',
					'dependency'	=> array(
						'element'		=> 'arrow',
						'not_empty'		=> true
					),
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_content',
					'hover_id'	 	=> 'sk_content_hover',
					"heading"     	=> esc_html__( "Content", 'codevz'),
					'button' 		=> esc_html__( "Content", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_avatar',
					'hover_id'	 	=> 'sk_avatar_hover',
					"heading"     	=> esc_html__( "Image", 'codevz'),
					'button' 		=> esc_html__( "Image", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border', 'box-shadow' ),
					'dependency'	=> array(
						'element'		=> 'avatar',
						'not_empty'		=> true
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_avatar_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_avatar_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_avatar_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_name',
					'hover_id'	 	=> 'sk_name_hover',
					"heading"     	=> esc_html__( "Name", 'codevz'),
					'button' 		=> esc_html__( "Name", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'background' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_name_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_name_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_name_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_subname',
					'hover_id'	 	=> 'sk_subname_hover',
					"heading"     	=> esc_html__( "Sub name", 'codevz'),
					'button' 		=> esc_html__( "Sub name", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subname_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subname_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subname_hover' ),

				// Advanced
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
					'heading' 		=> esc_html__( 'Center on mobile?', 'codevz' ),
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
	public function out( $atts, $content = 'Your company have been great at keeping me in work, they always line something else up.' ) {
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
				'sk_brfx' 			=> $css_id . ':before',
				'sk_content' 		=> $css_id . ' .cz_testimonials_content',
				'sk_content_hover' 	=> $css_id . ':hover .cz_testimonials_content',
				'sk_avatar' 		=> $css_id . ' .cz_testimonials_avatar',
				'sk_avatar_hover' 	=> $css_id . ':hover .cz_testimonials_avatar',
				'sk_name' 			=> $css_id . ' .cz_testimonials_name',
				'sk_name_hover' 	=> $css_id . ':hover .cz_testimonials_name',
				'sk_subname' 		=> $css_id . ' .cz_testimonials_subname',
				'sk_subname_hover' 	=> $css_id . ':hover .cz_testimonials_subname',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );
			
			if ( $atts['arrow_background'] ) {
				$css .= $css_id . '.cz_testimonials_bottom_arrow .cz_testimonials_content:after{border-top-color: ' . $atts['arrow_background'] . '}' . $css_id . '.cz_testimonials_top_arrow .cz_testimonials_content:after{border-left-color: ' . $atts['arrow_background'] . '}';
			}
			$css .= $atts['anim_delay'] ? $css_id . '{animation-delay:' . $atts['anim_delay'] . '}' : '';
		} else {
			Codevz_Plus::load_font( $atts['sk_name'] );
			Codevz_Plus::load_font( $atts['sk_subname'] );
		}

		// Avatar & Name
		$inner_meta_after = $inner_meta_before = $outer_meta_after = $outer_meta_before = $name = '';
		$avatar = $atts['avatar'] ? '<div class="cz_testimonials_avatar">' . Codevz_Plus::get_image( $atts['avatar'], 'thumbnail' ) . '</div>' : '';
		if ( $atts['name'] ) {
			$subname = $atts['subname'] ? '<div class="cz_testimonials_subname">' . $atts['subname'] . '</div>' : '';
			$name = '<div class="cz_testimonials_name">' . $atts['name'] . '</div>';
			$name = '<div class="cz_testimonials_name_subname">' . $name . $subname . '</div>';
		}

		// Meta position
		$meta = '<div class="cz_testimonials_meta">' . $avatar . $name . '</div>';
		if ( $atts['style'] == '1' ) {
			$outer_meta_after = $meta;
		} else if ( $atts['style'] == '2' ) {
			$outer_meta_before = $meta;
		} else if ( $atts['style'] == '3' || $atts['style'] == '5' ) {
			$inner_meta_after = $meta;
		} else if ( $atts['style'] == '4' || $atts['style'] == '6' ) {
			$inner_meta_before = $meta;
		} else if ( $atts['style'] == '7' ) {
			$inner_meta_before = '<div class="cz_testimonials_meta cz_tes_only_meta mb20">' . $avatar . '</div>';
			$inner_meta_after = '<div class="cz_testimonials_meta cz_tes_only_meta mt20">' . $name . '</div>';
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_testimonials';
		$classes[] = 'cz_testimonials_s' . $atts['style'];
		$classes[] = $atts['arrow'];
		$classes[] = $atts['text_center'] ? 'cz_mobile_text_center' : '';

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>' . $outer_meta_before . '<div class="cz_testimonials_content">' . $inner_meta_before . '<div class="cz_wpe_content">' . Codevz_Plus::fix_extra_p( do_shortcode( $content ) ) . '</div>' . $inner_meta_after . '</div>' . $outer_meta_after . '</div>';

		return Codevz_Plus::_out( $atts, $out, false, $this->name );
	}

}