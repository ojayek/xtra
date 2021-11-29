<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Content Box
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_content_box {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );
		add_shortcode( $this->name . '_2', array( $this, 'out' ) );
		
		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Content Box', 'codevz' ),
			'description'	=> esc_html__( 'Customizable box', 'codevz' ),
			'icon'			=> 'czi',
			'is_container' 	=> true,
			'js_view'		=> 'VcColumnView',
			'content_element'=> true,
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Box type', 'codevz' ),
					'param_name' 		=> 'type',
					'value'				=> array(
						esc_html__( '~ Default ~', 'codevz' )				=> '1',
						esc_html__( 'Split box with image', 'codevz' )	=> '2',
					),
					'std' 				=> '1',
					'save_always' 		=> true,
					'edit_field_class' 	=> 'vc_col-xs-99',
				),
				array(
					"type"        	=> "vc_link",
					"heading"     	=> esc_html__("Clickable box?", 'codevz'),
					"param_name"  	=> "link",
					'edit_field_class' 	=> 'vc_col-xs-99',
				),
				array(
					"type"        	=> "attach_image",
					"heading"     	=> esc_html__("Image", 'codevz'),
					"param_name"  	=> "split_box_image",
					'edit_field_class' 	=> 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '2' )
					),
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Image position', 'codevz' ),
					'param_name' 	=> 'split_box_position',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'Right', 'codevz' )			=> 'cz_split_box_right',
						esc_html__( 'Left', 'codevz' )			=> 'cz_split_box_left',
						esc_html__( 'Top', 'codevz' )			=> 'cz_split_box_top',
						esc_html__( 'Bottom', 'codevz' )		=> 'cz_split_box_bottom',
						esc_html__( 'Right one third', 'codevz' )	=> 'cz_split_box_right cz_split_box_q',
						esc_html__( 'Left one third', 'codevz' )	=> 'cz_split_box_left cz_split_box_q',
					),
					'std'			=> 'cz_split_box_right',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array('2')
					),
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Hide box arrow?", 'codevz'),
					"param_name"  	=> "split_box_hide_arrow",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array('2')
					),
				),

				// FX
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title_fx',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Hover Effect', 'codevz' )
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Normal', 'codevz' ),
					'param_name' => 'fx',
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> Codevz_Plus::fx()
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Hover', 'codevz' ),
					'param_name' => 'fx_hover',
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> Codevz_Plus::fx( '_hover' )
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' )
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_wrap',
					'hover_id'	 	=> 'sk_wrap_hover',
					"heading"     	=> esc_html__( "Wrap", 'codevz'),
					'button' 		=> esc_html__( "Wrap", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border', 'box-shadow' ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '2' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_wrap_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_wrap_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_wrap_hover' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_overall',
					'hover_id'	 	=> 'sk_hover',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'background', 'padding', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_hover' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_image',
					'hover_id'	 	=> 'sk_image_hover',
					"heading"     	=> esc_html__( "Image", 'codevz'),
					'button' 		=> esc_html__( "Image", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'margin', 'border', 'box-shadow' ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '2' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_image_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_image_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_image_hover' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'svg_bg',
					"heading"     	=> esc_html__( "Background layer", 'codevz'),
					'button' 		=> esc_html__( "Background layer", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'svg', 'background', 'top', 'left', 'rotate' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'svg_bg_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'svg_bg_mobile' ),

				// Flip
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Back box?', 'codevz' ),
					'param_name' 	=> 'back_box',
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array(
					'type' 			=> 'textfield',
					'param_name' 	=> 'back_title',
					'heading'		=> esc_html__( 'Title', 'codevz' ),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array(
					'type' 			=> 'textarea',
					'param_name' 	=> 'back_content',
					'heading'		=> esc_html__( 'Content', 'codevz' ),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array(
					'type' 			=> 'textfield',
					'param_name' 	=> 'back_btn_title',
					'heading'		=> esc_html__( 'Button Title', 'codevz' ),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array(
					'type' 			=> 'vc_link',
					'param_name' 	=> 'back_btn_link',
					'heading'		=> esc_html__( 'Button Link', 'codevz' ),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Content position', 'codevz' ),
					'param_name' => 'back_content_position',
					'value'		=> array(
						esc_html__( 'Top Left', 'codevz' )		=> 'cz_box_back_pos_tl',
						esc_html__( 'Top Center', 'codevz' )	=> 'cz_box_back_pos_tc',
						esc_html__( 'Top Right', 'codevz' )		=> 'cz_box_back_pos_tr',
						esc_html__( 'Middle Left', 'codevz' )	=> 'cz_box_back_pos_ml',
						esc_html__( 'Middle Center', 'codevz' )	=> 'cz_box_back_pos_mc',
						esc_html__( 'Middle Right', 'codevz' )	=> 'cz_box_back_pos_mr',
						esc_html__( 'Bottom Left', 'codevz' )	=> 'cz_box_back_pos_bl',
						esc_html__( 'Bottom Center', 'codevz' )	=> 'cz_box_back_pos_bc',
						esc_html__( 'Bottom Right', 'codevz' )	=> 'cz_box_back_pos_br',
					),
					'std' 			=> 'cz_box_back_pos_mc',
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Hover effect', 'codevz' ),
					'param_name' 	=> 'fx_backed',
					'value'			=> array(
						esc_html__( 'Flip Horizontal', 'codevz') 	=> 'fx_flip_h',
						esc_html__( 'Flip Vertical', 'codevz') 		=> 'fx_flip_v',
						esc_html__( 'Fade Out/In', 'codevz') 		=> 'fx_backed_fade_out_in',
						esc_html__( 'Fade To Top', 'codevz') 		=> 'fx_backed_fade_to_top',
						esc_html__( 'Fade To Bottom', 'codevz') 	=> 'fx_backed_fade_to_bottom',
						esc_html__( 'Fade To Left', 'codevz') 		=> 'fx_backed_fade_to_left',
						esc_html__( 'Fade To Right', 'codevz') 		=> 'fx_backed_fade_to_right',
						esc_html__( 'Zoom In', 'codevz') 			=> 'fx_backed_zoom_in',
						esc_html__( 'Zoom Out', 'codevz') 			=> 'fx_backed_zoom_out',
						esc_html__( 'Bend In', 'codevz') 			=> 'fx_backed_bend_in',
						esc_html__( 'Blurred', 'codevz') 			=> 'fx_backed_blurred',
					),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title_bkstyles',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_back',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'border', 'box-shadow' ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_back_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_back_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_back_in',
					"heading"     	=> esc_html__( "Content", 'codevz'),
					'button' 		=> esc_html__( "Content", 'codevz'),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background' ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_back_in_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_back_in_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_back_title',
					"heading"     	=> esc_html__( "Title", 'codevz'),
					'button' 		=> esc_html__( "Title", 'codevz'),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'font-family' ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_back_title_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_back_title_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_back_btn',
					'hover_id'	 	=> 'sk_back_btn_hover',
					"heading"     	=> esc_html__( "Button", 'codevz'),
					'button' 		=> esc_html__( "Button", 'codevz'),
					'group' 		=> esc_html__( 'Back box', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'border' ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( '1' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_back_btn_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_back_btn_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_back_btn_hover' ),

				// Advanced
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Background stretch', 'codevz' ),
					'description' 		=> esc_html__( 'This option only works with container background color', 'codevz' ),
					'param_name' 		=> 'shape',
					'value'				=> array(
						esc_html__( 'Select', 'codevz' )			=> '',
						esc_html__( 'Stretch full', 'codevz' )		=> 'cz_content_box_full_stretch',
						esc_html__( 'Stretch to left', 'codevz' )	=> 'cz_content_box_full_before',
						esc_html__( 'Stretch to right', 'codevz' )	=> 'cz_content_box_full_after'
					),
					'edit_field_class' 	=> 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Responsive', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
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
					'content' 		=> esc_html__( 'Tilt effect on hover', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Tilt effect", 'codevz'),
					"param_name"  	=> "tilt",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'Off'	=> '',
						'On'	=> 'on',
					),
					"group"  		=> esc_html__( 'Advanced', 'codevz' )
				),
				 array(
					"type" => "dropdown",
					"heading" => esc_html__("Glare","codevz"),
					"param_name" => "glare",
					"edit_field_class" => 'vc_col-xs-99',
					"value" => array( '0','0.2','0.4','0.6','0.8','1' ),
					'dependency'	=> array(
						'element'		=> 'tilt',
						'value'			=> array( 'on')
					),
					"group"  		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Scale","codevz"),
					"param_name" => "scale",
					"edit_field_class" => 'vc_col-xs-99',
					"value" 	=> array('0.9','0.8','1','1.1','1.2'),
					"std" 		=> '1',
					'dependency'	=> array(
						'element'		=> 'tilt',
						'value'			=> array( 'on')
					),
					"group"  		=> esc_html__( 'Advanced', 'codevz' )
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

		$split_before = $split_after = $split_pos = '';

		// ID
		if ( empty( $atts['id'] ) ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}

		// Front
		$front = '<div class="cz_box_front clr"><div class="cz_box_front_inner clr ' . $atts['shape'] . '">' .( $atts['split_box_hide_arrow'] ? '' : '<span></span>' ) . '<div>' . do_shortcode( $content ) . '</div></div></div>';

		// Split box
		if ( $atts['type'] === '2' ) {
			$split_pos = $atts['split_box_position'];
			$split_img = Codevz_Plus::get_image( $atts['split_box_image'], 0, 1 );
			if ( $split_pos === 'cz_split_box_top' || $split_pos === 'cz_split_box_bottom' ) {
				$split = '<div class="cz_split_box"><img src="' . $split_img . '" alt="#" /></div>';
			} else {
				$split = '<div class="cz_split_box" style="background-image: url(' . $split_img . ')"></div>';
			}

			if ( Codevz_Plus::contains( $split_pos, array( 'cz_split_box_right', 'cz_split_box_bottom' ) ) ) {
				$split_after = $split;
			} else {
				$split_before = $split;
			}
		}

		// Backed
		$backed = '';
		if ( $atts['back_box'] && ! $split_pos ) {
			$backed_btn = $atts['back_btn_title'] ? '<a class="cz_box_back_btn"' . Codevz_Plus::link_attrs( $atts['back_btn_link'] ) . '>' . $atts['back_btn_title'] . '</a>' : '';
			$backed = '<div class="cz_box_back clr">
				<div class="cz_box_back_inner clr">
					<div>
						<div class="cz_box_back_inner_position">
							<div class="cz_box_back_title">' . $atts['back_title'] . '</div>
							<div class="cz_box_back_content">' . Codevz_Plus::fix_extra_p( $atts['back_content'] ) . '</div>
							' . $backed_btn .'
						</div>
					</div>
				</div>
			</div>';
		}

		// Parent box classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_content_box clr';
		$classes[] = $atts['svg_bg'] ? 'cz_svg_bg' : '';
		$classes[] = $split_pos;
		$classes[] = $atts['split_box_hide_arrow'] ? 'cz_box_hide_arrow' : '';

		if ( $backed ) {
			$classes[] = $atts['fx_backed'];
			$classes[] = $atts['back_content_position'];
			$classes[] = 'cz_box_backed';
		}

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];

			$css_array = array(
				'sk_wrap' 			=> $css_id,
				'sk_brfx' 			=> $css_id . ':before',
				'sk_overall' 		=> $css_id . ' .cz_box_front_inner',
				'sk_image' 			=> $css_id . ' .cz_split_box,' . $css_id . ' .cz_split_box img',
				'sk_hover' 			=> $css_id . ':hover .cz_box_front_inner',
				'sk_back' 			=> $css_id . ' .cz_box_back_inner',
				'sk_back_in' 		=> $css_id . ' .cz_box_back_inner_position',
				'sk_back_title' 	=> $css_id . ' .cz_box_back_title',
				'sk_back_btn' 		=> $css_id . ' .cz_box_back_btn',
				'sk_back_btn_hover' => $css_id . ' .cz_box_back_btn:hover',
				'svg_bg' 			=> $css_id . '.cz_svg_bg:before'
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			$css .= $atts['anim_delay'] ? $css_id . '{animation-delay:' . $atts['anim_delay'] . '}' : '';
		}

		// All Contents
		$final_content = '<div class="cz_eqh cz_content_box_parent_fx ' . $atts['fx'] . ' ' . $atts['class'] . '">';
		$final_content .= $atts['fx_hover'] ? '<div class="' . $atts['fx_hover'] . '">' : '';
		$final_content .= '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes, 1 ) . Codevz_Plus::tilt( $atts ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>' . $split_before . $front . $backed . $split_after . '</div>';
		$final_content .= $atts['fx_hover'] ? '</div>' : '';
		$final_content .= '</div>';

		// Out
		$link = Codevz_Plus::link_attrs( $atts['link'] );
		$out = ( Codevz_Plus::contains( $link, 'href' ) && ! Codevz_Plus::contains( $link, '"#"' ) && ! $atts['back_btn_link'] ) ? '<a class="cz_content_box_link"' . $link . '>' . str_replace( array( '<a ', '</a>' ), array( '<div ', '</div>' ), $final_content ) . '</a>' : $final_content;

		return Codevz_Plus::_out( $atts, $out, array( 'content_box( true )', 'tilt' ), $this->name );
	}

}