<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Qoute
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_quote {

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
			'name'			=> esc_html__( 'Quote', 'codevz' ),
			'description'	=> esc_html__( 'Quotation of someone', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Style", 'codevz'),
					"param_name"  	=> "style",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'Select' 				=> '',
						'Default center' 		=> 'cz_quote_center',
						'Arrow bottom' 			=> 'cz_quote_arrow',
						'Arrow bottom center' 	=> 'cz_quote_arrow cz_quote_center',
						'Arrow top' 			=> 'cz_quote_arrow cz_quote_top',
						'Arrow top center' 		=> 'cz_quote_arrow cz_quote_top cz_quote_center'
					)
				),
				array(
					"type"        	=> "textarea_html",
					"heading"     	=> esc_html__("Content", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "content",
					"value"  		=> "Great things in business are never done by one person. They are done by a team of people.",
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "attach_image",
					"heading"     	=> esc_html__("Image", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "image"
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__('Name', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> 'John Doe',
					'param_name' 	=> 'name'
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__('Sub name', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> 'Businessman',
					'param_name' 	=> 'subname'
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Stars rating?", 'codevz'),
					"param_name"  	=> "rating",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						'Select' 		=> '',
						'5' 	=> '5',
						'4.5' 	=> '4.5',
						'4' 	=> '4',
						'3.5'	=> '3.5',
						'3' 	=> '3',
						'2.5' 	=> '2.5',
						'2' 	=> '2',
						'1.5' 	=> '1.5',
						'1' 	=> '1',
						'0.5' 	=> '0.5',
						'Zero' 	=> '0',
					)
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__( 'Stars color', 'codevz' ),
					'param_name' => 'rating_color',
					'value'		=> '#efe121',
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'rating',
						'not_empty'		=> true
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Quote shape", 'codevz'),
					"param_name"  	=> "quote_position",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'None' 			=> '',
						'Top left' 		=> 'absolute;top: 10%;left: 10%',
						'Top center' 	=> 'absolute;top: 10%;left: calc(50% - 60px)',
						'Top right' 	=> 'absolute;top: 10%;right: 10%',
						'Bottom left' 	=> 'absolute;bottom: 10%;left: 10%',
						'Bottom center' => 'absolute;bottom: 10%;left: calc(50% - 60px)',
						'Bottom right' 	=> 'absolute;bottom: 10%;right: 10%',
						'Center center' => 'absolute;top: calc(50% - 60px);right: calc(50% - 60px)',
						'Left relative' => 'relative;margin: 0 0 20px;font-size: 40px;opacity: 1',
						'Center relative' => 'relative;margin: 0 auto 20px;font-size: 40px;text-align: center;opacity: 1',
						'Right relative' => 'relative;margin: 0 auto 20px;font-size: 40px;text-align: right;opacity: 1',
						'Top left + Bottom right' => 'absolute;top: 10%;left: 10%;',
					)
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__( 'Quote color', 'codevz' ),
					'param_name' => 'quote_color',
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'quote_position',
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
					'param_name' 	=> 'sk_overall',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_image',
					"heading"     	=> esc_html__( "Image", 'codevz'),
					'button' 		=> esc_html__( "Image", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'border', 'padding' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_image_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_name',
					"heading"     	=> esc_html__( "Name", 'codevz'),
					'button' 		=> esc_html__( "Name", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-family', 'font-size' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_name_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_subname',
					"heading"     	=> esc_html__( "Sub name", 'codevz'),
					'button' 		=> esc_html__( "Sub name", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subname_mobile' ),

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
	public function out( $atts, $content = 'Great things in business are never done by one person. They are done by a team of people.' ) {
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
				'sk_overall' 	=> $css_id . ' blockquote',
				'sk_image' 		=> $css_id . ' .cz_quote_info img',
				'sk_name' 		=> $css_id . ' h4',
				'sk_subname' 	=> $css_id . ' h4 small'
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			$css .= $atts['anim_delay'] ? $css_id . '{animation-delay:' . $atts['anim_delay'] . '}' : '';
		} else {
			Codevz_Plus::load_font( $atts['sk_name'] );
			Codevz_Plus::load_font( $atts['sk_subname'] );
		}

		// Variable's
		if ( $atts['rating'] !== '' ) {
			$rating_number = $atts['rating'];
			$half_rating = ( strpos( $rating_number, '.' ) !== false ) ? '<i class="fa fa-star-half-o"></i>' : '';
			$atts['rating'] = '<div class="cz_quote_rating" style="color: ' . $atts['rating_color'] . '">' . str_repeat( '<i class="fa fa-star"></i>', floor( $rating_number ) ) . $half_rating;
			$atts['rating'] .= str_repeat( '<i class="fa fa-star-o"></i>', ( 5 - round( $rating_number ) ) );
			$atts['rating'] .= '</div>';
		}
		$cite 	= $atts['name'] ? '<h4>' . $atts['name'] . '<small>' . $atts['subname'] . '</small></h4>' : '';
		$cite 	= $cite . $atts['rating'];
		$image 	= $atts['image'] ? Codevz_Plus::get_image( $atts['image'], 'thumbnail' ) : '';
		$text 	= '<div class="cz_quote_content cz_wpe_content">' . do_shortcode( Codevz_Plus::fix_extra_p( $content ) ) . '</div>';
		$quote_start_end = ( strpos( $atts['quote_position'], 'left' ) !== false ) ? 'left' : 'right';
		$icon_position = $atts['quote_position'] ? 'position:' . $atts['quote_position'] . ';' : '';
		$icon_color = $atts['quote_color'] ? 'color: ' . $atts['quote_color'] . ';' : '';
		$icon_tyle = ( $icon_color || $icon_position ) ? ' style="' . $icon_position . $icon_color . '"' : '';
		$icon 	= $atts['quote_position'] ? '<i class="fa fa-quote-' . $quote_start_end . ' cz_quote_shape"' . $icon_tyle . '></i>' : '';
		$sub 	= ( $image || $cite ) ? '<div class="cz_quote_info">' . $image . $cite . '</div>' : '';

		// Check if both quote selected
		if ( $atts['quote_position'] === 'absolute;top: 10%;left: 10%;' ) {
			$icon .= '<i class="fa fa-quote-right cz_quote_shape cz_quote_both_second"' . ( $icon_color ? ' style="' . $icon_color. '"' : '' ) . '></i>';
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_quote';
		$classes[] = $atts['style'];
		$classes[] = $atts['text_center'] ? 'cz_mobile_text_center' : '';
		
		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>';
		if ( strpos( $atts['style'], 'cz_quote_top' ) !== false ) {
			$out .= $sub . '<blockquote>' . $icon . $text . '</blockquote>';
		} elseif ( strpos( $atts['style'], 'cz_quote_arrow' ) !== false ) {
			$out .= '<blockquote>' . $icon . $text . '</blockquote>' . $sub;
		} else {
			$out .= '<blockquote>' . $icon . $text . $sub . '</blockquote>';
		}
		$out .= "</div>";

		return Codevz_Plus::_out( $atts, $out, false, $this->name );
	}

}