<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Cannot access pages directly.

/**
 * Accordion
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_accordion {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {

		add_shortcode( $this->name, [ $this, 'out' ] );
		add_shortcode( 'cz_acc_child', [ $this, 'acc_child' ] );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Accordion, Toggle', 'codevz' ), 
			'description'	=> esc_html__( 'Show/Hide large content', 'codevz' ),
			'icon'			=> 'czi',
			'is_container' 	=> true,
			'js_view' 		=> 'VcColumnView',
			'as_parent'		=> array( 'only' => 'cz_acc_child' ), 
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__( "Toggle mode?", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-6',
					"param_name"  	=> "toggle"
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__( "1st open?", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-6',
					"param_name"  	=> "first_open"
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__( "Icons before title?", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-6',
					"param_name"  	=> "icon_before"
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Inline subtitle?', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-6',
					'param_name' 	=> 'subtitle_inline'
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Default and activated icons', 'codevz' ),
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Default icon", 'codevz'),
					"description"   => esc_html__("When accordion is in close mode", 'codevz'),
					"param_name"  	=> "open_icon",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> 'fa fa-angle-down'
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_open_icon',
					"heading"     	=> esc_html__( "Icon styling", 'codevz'),
					'button' 		=> esc_html__( "Default icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding' ),
					'dependency'	=> array(
						'element'		=> 'open_icon',
						'not_empty'		=> true
					),
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Activated icon", 'codevz'),
					"description"   => esc_html__("When accordion is in open mode", 'codevz'),
					"param_name"  	=> "close_icon",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> 'fa fa-angle-up'
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_close_icon',
					"heading"     	=> esc_html__( "Icon styling", 'codevz'),
					'button' 		=> esc_html__( "Activated Icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding' ),
					'dependency'	=> array(
						'element'		=> 'close_icon',
						'not_empty'		=> true
					),
				),

				// Styling
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
					'param_name' 	=> 'sk_title',
					'hover_id'		=> 'sk_title_hover',
					"heading"     	=> esc_html__( "Titles", 'codevz'),
					'button' 		=> esc_html__( "Titles", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_active',
					"heading"     	=> esc_html__( "Active title", 'codevz'),
					'button' 		=> esc_html__( "Active title", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_active_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_active_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_subtitle',
					"heading"     	=> esc_html__( "Subtitle", 'codevz'),
					'button' 		=> esc_html__( "Subtitle", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_content',
					"heading"     	=> esc_html__( "Content", 'codevz'),
					'button' 		=> esc_html__( "Content", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_title_icons',
					'hover_id' 		=> 'sk_title_icons_hover',
					"heading"     	=> esc_html__( "Title icons", 'codevz'),
					'button' 		=> esc_html__( "Title icons", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_icons_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_icons_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_icons_hover' ),

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
			'base'			=> 'cz_acc_child',
			'name'			=> esc_html__( 'Accordion item', 'codevz' ), 
			'description'	=> esc_html__( 'Collapsible content, Accordion & Toggle', 'codevz' ),
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
					"type"        	=> "textarea",
					"heading"     	=> esc_html__("Title", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "title",
					"value"  		=> "Accordion title",
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "textarea",
					"heading"     	=> esc_html__("Subtitle", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "subtitle"
				),
				array(
					"type" 			=> "dropdown",
					"heading" 		=> esc_html__("Icon type","codevz"),
					"param_name" 	=> "icon_type",
					'edit_field_class' => 'vc_col-xs-99',
					"value" 	=> array(
						 esc_html__( 'Icon', "codevz")			=> 'icon',
						 esc_html__( 'Number', "codevz")		=> 'number',
						 esc_html__( 'Image', "codevz")			=> 'image'
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
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Number", 'codevz'),
					"param_name"  	=> "number",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'icon_type',
						'value'			=> array( 'number' )
					)
				),
				array(
					"type" 				=> "attach_image",
					"heading" 			=> esc_html__("Image","codevz"),
					"param_name" 		=> "image",
					'edit_field_class' 	=> 'vc_col-xs-99',
					'dependency' 		=> array(
						'element' 			=> 'icon_type',
						'value' 			=> array( 'image' )
					),
				),
				array(
					"type"        		=> "textfield",
					"heading"     		=> esc_html__("Image size", 'codevz'),
					"description"   	=> esc_html__('Enter image size (e.g: "thumbnail", "medium", "large", "full"), Alternatively enter size in pixels (e.g: 200x100 (Width x Height)).', 'codevz'),
					"value"  			=> "thumbnail",
					"param_name"  		=> "image_size",
					'edit_field_class' 	=> 'vc_col-xs-99',
					'dependency' 		=> array(
						'element' 			=> 'icon_type',
						'value' 			=> array( 'image' )
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icon',
					"heading"     	=> esc_html__( "Icon styling", 'codevz'),
					'button' 		=> esc_html__( "Icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
			)

		);

		if ( $wpb ) {
			vc_map( $settings );
		}

	}

	/**
	 * Shortcode container output
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
				'sk_overall' 			=> array( $css_id, $custom ),
				'sk_brfx' 				=> $css_id . ':before',
				'sk_title' 				=> $css_id . ' .cz_acc_child',
				'sk_title_hover' 		=> $css_id . ' .cz_acc_child:hover',
				'sk_subtitle' 			=> $css_id . ' .cz_acc_child small',
				'sk_active' 			=> $css_id . ' .cz_isOpen .cz_acc_child',
				'sk_content' 			=> $css_id . ' .cz_acc_child_content',
				'sk_open_icon' 			=> $css_id . ' .cz_acc_open_icon',
				'sk_close_icon' 		=> $css_id . ' .cz_acc_close_icon',
				'sk_title_icons' 		=> $css_id . ' .cz-acc-i',
				'sk_title_icons_hover' 	=> $css_id . ' .cz_isOpen .cz-acc-i',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

		} else {
			Codevz_Plus::load_font( $atts['sk_title'] );
			Codevz_Plus::load_font( $atts['sk_subtitle'] );
		}

		// Arrows
		$arrows = array( 'open'	=> $atts['open_icon'], 'close'	=> $atts['close_icon'] );

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_acc clr';
		$classes[] = $atts['subtitle_inline'] ? 'cz_acc_subtitle_inline' : '';
		$classes[] = $atts['toggle'] ? 'cz_acc_toggle' : '';
		$classes[] = $atts['icon_before'] ? 'cz_acc_icon_before' : '';
		$classes[] = $atts['first_open'] ? 'cz_acc_first_open' : '';

		// Out
		$out = '<div id="' . $atts['id'] . '" data-arrows=\'' . json_encode( $arrows ) . '\'' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div>' . do_shortcode( $content ) . '</div></div>';

		return Codevz_Plus::_out( $atts, $out, 'accordion', $this->name );
	}

	/**
	 * Shortcode inner ( children ) output
	 */
	public function acc_child( $atts, $content = '' ) {
		$atts = vc_map_get_attributes( 'cz_acc_child', $atts );

		$atts['id'] = isset( $atts['id'] ) ? $atts['id'] : Codevz_Plus::uniqid();

		// Icon
		$icon = '';
		$css = $atts['sk_icon'] ? ' style="' . $atts['sk_icon'] . '"' : '';
		if ( $atts['icon_type'] === 'number' ) {
			$icon = '<i class="cz-acc-i cz-acc-number"' . $css . '>' . $atts['number'] . '</i>';
		} else if ( $atts['icon_type'] === 'image' ) {
			$img = Codevz_Plus::get_image( $atts['image'], $atts['image_size'] );
			$icon = '<i class="cz-acc-i cz-acc-image"' . $css . '>' . $img . '</i>';
		} else if ( $atts['icon'] ) {
			$icon = '<i class="cz-acc-i cz-acc-icon ' . $atts['icon'] . '"' . $css . '></i>';
		}

		// Subtitle
		$subtitle = $atts['subtitle'] ? '<small>' . $atts['subtitle'] . '</small>' : '';

		// Out
		$out = '<div id="' . esc_attr( $atts['id'] ) . '"><span class="cz_acc_child">' . $icon . '<div>' . $atts['title'] . $subtitle . '</div>' . '</span><div class="cz_acc_child_content clr">' . do_shortcode( $content ) . '</div></div>';

		return Codevz_Plus::_out( $atts, $out, 'accordion( true )' );
	}

}