<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Team
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_team {

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
			'name'			=> esc_html__( 'Team Member', 'codevz' ),
			'description'	=> esc_html__( 'Personal information', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type" 			=> "dropdown",
					"heading" 		=> esc_html__("Team style", 'codevz'),
					"param_name" 	=> "style",
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> array(
						esc_html__( 'No hover' , 'codevz' )								=> 'cz_team_1',
						esc_html__( 'Social icons on image', 'codevz' )					=> 'cz_team_2',
						esc_html__( 'Social icons on image 2', 'codevz' ) 				=> 'cz_team_4',
						esc_html__( 'Social and title on image', 'codevz' ) 			=> 'cz_team_3',
						esc_html__( 'Social and title on image 2', 'codevz' ) 			=> 'cz_team_5',
						esc_html__( 'Only title on mouse moves', 'codevz' ) 			=> 'cz_team_6',
						esc_html__( 'Title on mouse moves and social below image', 'codevz' ) 	=> 'cz_team_7',
					)
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Hover mode?", 'codevz'),
					"param_name"  	=> "hover_mode",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'Select' 				=> '',
						'Reverse hover mode' 	=> 'cz_team_rev_hover',
						'Always show details' 	=> 'cz_team_always_show',
					),
					//'dependency'	=> array(
					//	'element'				=> 'style',
					//	'value_not_equal_to'	=> array( 'cz_team_1', 'cz_team_6', 'cz_team_7' )
					//)
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Image', 'codevz' ),
					'param_name' => 'image',
					'edit_field_class' => 'vc_col-xs-99',
					'value' => 'https://xtratheme.com/img/450x450.jpg'
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Image size", 'codevz'),
					"description"   => esc_html__('Enter image size (e.g: "thumbnail", "medium", "large", "full"), Alternatively enter size in pixels (e.g: 200x100 (Width x Height)).', 'codevz'),
					"value"  		=> "full",
					"param_name"  	=> "size",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					"type"        	=> "textarea_html",
					"heading"     	=> esc_html__("Name and job title", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "content",
					'admin_label' 	=> true,
					'value'			=> '<h4><strong>John Carter</strong></h4><span style="color: #999999;">Developer</span>'
				),
				array(
					"type"        	=> "vc_link",
					"heading"     	=> esc_html__("Link", 'codevz'),
					"param_name"  	=> "link",
					'edit_field_class' => 'vc_col-xs-99',
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
					'hover_id'	 	=> 'sk_overall_hover',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_hover' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'svg_bg',
					"heading"     	=> esc_html__( "Background layer", 'codevz'),
					'button' 		=> esc_html__( "Background layer", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'svg', 'background', 'top', 'left', 'width', 'height' )
				),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_image_con',
					"heading"     	=> esc_html__( "Image container", 'codevz'),
					'button' 		=> esc_html__( "Image container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_image_con_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_image_con_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_image_img',
					'hover_id'	 	=> 'sk_image_img_hover',
					"heading"     	=> esc_html__( "Image", 'codevz'),
					'button' 		=> esc_html__( "Image", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'grayscale', 'opacity', 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_image_img_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_image_img_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_image_img_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_content',
					"heading"     	=> esc_html__( "Content", 'codevz'),
					'button' 		=> esc_html__( "Content", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_mobile' ),

				// Social
				array(
					'type' => 'param_group',
					'heading' => esc_html__( 'Social', 'codevz' ),
					'param_name' => 'social',
					'params' => array(
						array(
							"type"        	=> "cz_icon",
							"heading"     	=> esc_html__("Icon", 'codevz'),
							'edit_field_class' => 'vc_col-xs-99',
							"param_name"  	=> "icon"
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__("Title", 'codevz'),
							'edit_field_class' => 'vc_col-xs-99',
							"param_name"  	=> "title"
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__("Link", 'codevz'),
							"param_name"  	=> "link",
							'edit_field_class' => 'vc_col-xs-99'
						)
					),
					'group' 		=> esc_html__( 'Social', 'codevz' )
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Color mode?', 'codevz'),
					'param_name'	=> 'color_mode',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						__( 'Select', 'codevz' ) 						=> '',
						__( 'Original colors', 'codevz' ) 			=> 'cz_social_colored',
						__( 'Original colors on hover', 'codevz' ) 	=> 'cz_social_colored_hover',
						__( 'Original background', 'codevz' ) 		=> 'cz_social_colored_bg',
						__( 'Original background on hover', 'codevz' ) => 'cz_social_colored_bg_hover',
					),
					'group' 		=> esc_html__( 'Social', 'codevz' )
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Tooltip?', 'codevz'),
					'description' 	=> esc_html__( 'StyleKit located in Theme Options > General > Colors & Styles', 'codevz' ),
					'param_name'	=> 'social_tooltip',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						__( 'Select', 'codevz' ) 	=> '',
						__( 'Up', 'codevz' ) 		=> 'cz_tooltip cz_tooltip_up',
						__( 'Down', 'codevz' ) 	=> 'cz_tooltip cz_tooltip_down',
						__( 'Left', 'codevz' ) 	=> 'cz_tooltip cz_tooltip_left',
						__( 'Right', 'codevz' ) 	=> 'cz_tooltip cz_tooltip_right',
					),
					'group' 		=> esc_html__( 'Social', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Position?", 'codevz'),
					"param_name"  	=> "social_align",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'Default' 		=> '',
						'Left' 			=> 'tal',
						'Center' 		=> 'tac',
						'Right' 		=> 'tar',
					),
					'group' 		=> esc_html__( 'Social', 'codevz' )
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Hover effect?', 'codevz'),
					'param_name'	=> 'fx',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						__( 'Select', 'codevz' ) 			=> '',
						__( 'ZoomIn', 'codevz' ) 			=> 'cz_social_fx_0',
						__( 'ZoomOut', 'codevz' ) 		=> 'cz_social_fx_1',
						__( 'Bottom to Top', 'codevz' ) 	=> 'cz_social_fx_2',
						__( 'Top to Bottom', 'codevz' ) 	=> 'cz_social_fx_3',
						__( 'Left to Right', 'codevz' ) 	=> 'cz_social_fx_4',
						__( 'Right to Left', 'codevz' ) 	=> 'cz_social_fx_5',
						__( 'Rotate', 'codevz' ) 			=> 'cz_social_fx_6',
						__( 'Infinite Shake', 'codevz' )	=> 'cz_social_fx_7',
						__( 'Infinite Wink', 'codevz' ) 	=> 'cz_social_fx_8',
						__( 'Quick Bob', 'codevz' ) 		=> 'cz_social_fx_9',
						__( 'Flip Horizontal', 'codevz' ) => 'cz_social_fx_10',
						__( 'Flip Vertical', 'codevz' ) 	=> 'cz_social_fx_11',
					),
					'group' 		=> esc_html__( 'Social', 'codevz' )
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Vertical mode?", 'codevz'),
					"param_name"  	=> "social_v",
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Social', 'codevz' )
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
					'group' 		=> esc_html__( 'Social', 'codevz' )
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_social_con',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Social', 'codevz' ),
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_social_con_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icons',
					'hover_id' 		=> 'sk_icons_hover',
					"heading"     	=> esc_html__( "Icons", 'codevz'),
					'button' 		=> esc_html__( "Icons", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Social', 'codevz' ),
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icons_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icons_hover' ),

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
	public function out( $atts, $content = '<h4><strong>John Carter</strong></h4><span style="color: #999999;">Developer</span>' ) {
		$atts = Codevz_Plus::shortcode_atts( $this, $atts );

		// ID
		if ( ! $atts['id'] ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}

		// Link 
		$a_attr = $atts['link'] ? Codevz_Plus::link_attrs( $atts['link'] ) : '';

		// Image
		$img = Codevz_Plus::get_image( $atts['image'], $atts['size'] );
		if ( $a_attr ) {
			$img = '<a' . $a_attr . '>' . $img . '</a>';
		}

		// Title content
		$content = '<div class="cz_team_content cz_wpe_content">' . do_shortcode( Codevz_Plus::fix_extra_p( $content ) ) . '</div>';
		if ( $a_attr ) {
			$content = '<a' . $a_attr . '>' . $content . '</a>';
		}

		// Social
		$social_icons = json_decode( urldecode( $atts[ 'social' ] ), true );
		$social = '<div class="' . implode( ' ', array_filter( array( 'cz_team_social cz_social clr', $atts['color_mode'], $atts['fx'], $atts['social_align'], $atts['social_tooltip'] ) ) ) . '">';
		$social .= '<div class="cz_team_social_in">';
		foreach ( $social_icons as $i ) {
			if ( ! empty( $i['icon'] ) ) {
				$class = 'cz-' . str_replace( Codevz_Plus::$social_fa_upgrade, '', $i['icon'] );

				$link = ( empty( $i['link'] ) ? '#' : $i['link'] );

				$target = ( Codevz_Plus::contains( $link, [ $_SERVER['HTTP_HOST'], 'tel:', 'mailto:' ] ) || $link === '#' ) ? '' : ' target="_blank"';

				$social .= '<a href="' . esc_attr( $link ) . '" class="' . $class . '" ' . ( empty( $i['title'] ) ? '' : ( $atts['social_tooltip'] ? ' data-title' : ' title' ) . '="' . $i['title'] . '"' ) . $target . '><i class="' . $i['icon'] . '"></i></a>';
			}
		}
		$social .= '</div></div>';

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];
			$custom = $atts['anim_delay'] ? 'animation-delay:' . $atts['anim_delay'] . ';' : '';

			$css_array = array(
				'sk_overall' 	=> array( $css_id, $custom ),
				'sk_overall_hover' 	=> $css_id . ':hover',
				'sk_brfx' 		=> $css_id . ':before',
				'sk_social_con' => $css_id . ' .cz_team_social_in',
				'sk_icons' 		=> $css_id . ' .cz_team_social a',
				'sk_icons_hover'=> $css_id . ' .cz_team_social a:hover',
				'sk_image_con' 	=> $css_id . ' .cz_team_img',
				'sk_image_img' 	=> $css_id . ' .cz_team_img img',
				'sk_image_img_hover' 	=> $css_id . ':hover .cz_team_img img',
				'sk_content' 	=> $css_id . ' .cz_team_content',
				'svg_bg' 		=> $css_id . '.cz_svg_bg:before'
			);

			$css = Codevz_Plus::sk_style( $atts, $css_array );
			$css_t = Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m = Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_team mb30 clr';
		$classes[] = $atts['hover_mode'];
		$classes[] = $atts['svg_bg'] ? 'cz_svg_bg' : '';
		$classes[] = $atts['style'];
		$classes[] = $atts['social_v'] ? 'cz_social_v' : '';

		// Out
		$out = '<div id="' . $atts['id'] .'"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>';
		if ( empty( $atts['style'] ) || $atts['style'] === 'cz_team_1' ) {
			$out .= '<div class="cz_team_img"' . Codevz_Plus::tilt( $atts ) . '>' . $img . '</div>' . $content . $social;
		} else if ( $atts['style'] === 'cz_team_2' || $atts['style'] === 'cz_team_4' ) {
			$out .= '<div class="cz_team_img"' . Codevz_Plus::tilt( $atts ) . '>' . $img . $social . '</div>' . $content;
		} else if ( $atts['style'] === 'cz_team_3' || $atts['style'] === 'cz_team_5' ) {
			$out .= '<div class="cz_team_img"' . Codevz_Plus::tilt( $atts ) . '>' . $img . $content . $social . '</div>';
		} else if ( $atts['style'] === 'cz_team_6' ) {
			$out .= '<div class="cz_team_img"' . Codevz_Plus::tilt( $atts ) . '>' . $img . $content . '</div>';
		} else if ( $atts['style'] === 'cz_team_7' ) {
			$out .= '<div class="cz_team_img"' . Codevz_Plus::tilt( $atts ) . '>' . $img . $content . '</div>' . $social;
		}
		$out .= '</div>';

		return Codevz_Plus::_out( $atts, $out, array( 'tilt', 'team' ), $this->name );
	}

}