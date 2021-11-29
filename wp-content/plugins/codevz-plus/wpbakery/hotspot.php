<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Draggable Hotspot
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_hotspot {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * List of pages
	 */
	public function pages() {
		$pages = get_posts(array(
			'post_type' 		=> 'page',
			'orderby' 			=> 'title',
			'posts_per_page' 	=> -1 
		));
		$out = array( esc_html__( 'Select', 'codevz' ) => '' );

		foreach ( $pages as $page ) {
			$out[$page->post_title] = $page->ID;
		}

		return $out;
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Hotspot', 'codevz' ),
			'description'	=> esc_html__( 'Content on any places', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' => 'cz_slider',
					'heading' => esc_html__( 'Top offset', 'codevz' ),
					'description' => esc_html__( 'Drag element or manually set e.g. 10%', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '%', 'step' => 1, 'min' => 0, 'max' => 100 ),
					'param_name' => 'css_top'
				),
				array(
					'type' => 'cz_slider',
					'heading' => esc_html__( 'Left offset', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '%', 'step' => 1, 'min' => 0, 'max' => 100 ),
					'param_name' => 'css_left'
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Icon', 'codevz' )
				),
				array(
					"type" 			=> "dropdown",
					"heading" 		=> esc_html__("Icon type","codevz"),
					"param_name" 	=> "icon_type",
					'edit_field_class' => 'vc_col-xs-99',
					"value" 	=> array(
						'Icon'		=> 'icon',
						'Number'	=> 'number',
						'Image'		=> 'image'
					),
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Icon", 'codevz'),
					"param_name"  	=> "icon",
					'value'			=> 'fa fa-plus',
					'edit_field_class' => 'vc_col-xs-99',
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
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Icon style', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'icon_transform',
					'value'		=> array(
						'Standard'		=> 'scale(1, 1)',
						'Medium'		=> 'scale(1.2, 1.2)',
						'Large'			=> 'scale(1.4, 1.4)',
						'XLarge'		=> 'scale(1.8, 1.8)',

						'Standard Square'	=> 'scale(1, 1);border-radius: 0',
						'Medium Square'		=> 'scale(1.2, 1.2);border-radius: 0',
						'Large Square'		=> 'scale(1.4, 1.4);border-radius: 0',
						'XLarge Square'		=> 'scale(1.8, 1.8);border-radius: 0',

						'Standard Rotated'	=> 'scale(1, 1) rotate(45deg);border-radius: 0',
						'Medium Rotated'	=> 'scale(1.2, 1.2) rotate(45deg);border-radius: 0',
						'Large Rotated'		=> 'scale(1.4, 1.4) rotate(45deg);border-radius: 0',
						'XLarge Rotated'	=> 'scale(1.8, 1.8) rotate(45deg);border-radius: 0',

						'Standard Rotated Radius'	=> 'scale(1, 1) rotate(45deg);border-radius: 8px',
						'Medium Rotated Radius'	=> 'scale(1.2, 1.2) rotate(45deg);border-radius: 8px',
						'Large Rotated Radius'		=> 'scale(1.4, 1.4) rotate(45deg);border-radius: 8px',
						'XLarge Rotated Radius'	=> 'scale(1.8, 1.8) rotate(45deg);border-radius: 8px',
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
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_mobile' ),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Icon effect', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'icon_effect',
					'value'		=> array(
						esc_html__( '~ Default ~', 'codevz')	=> '',
						esc_html__( 'Pulse', 'codevz')		=> 'cz_hotspot_pulse',
						esc_html__( 'Waves', 'codevz')		=> 'cz_hotspot_waves',
						esc_html__( 'Ripple', 'codevz')		=> 'cz_hotspot_ripple',
						esc_html__( 'Bob', 'codevz')		=> 'cz_hotspot_bob',
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Effect duration', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'icon_animation-duration',
					'value'		=> array(
						'0.4s'		=> '0.4s',
						'0.6s'		=> '0.6s',
						'0.8s'		=> '0.8s',
						'1s'		=> '1s',
						'1.2s'		=> '1.2s',
						'1.4s'		=> '1.4s',
						'1.6s'		=> '1.6s',
						'1.8s'		=> '1.8s',
						'2s'		=> '2s',
					),
					'std' => '1s'
				),

				// Tooltip content
				array(
					"type"        	=> "textarea_html",
					"heading"     	=> esc_html__("Content", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "content",
					'value'			=> 'Hello World ...',
					'group' 		=> esc_html__( 'Content', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'content_type',
						'value'			=> array('1' )
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Choose', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'model',
					'value'		=> array(
						'Default'		=> 'default',
						'Animated 1'	=> 'cora',
						'Animated 2'	=> 'smaug',
						'Animated 3'	=> 'uldor',
						'Animated 4'	=> 'dori',
						'Animated 5'	=> 'gram',
						'Animated 6'	=> 'indis',
						'Animated 7'	=> 'narvi',
						'Animated 8'	=> 'amras',
						'Animated 9'	=> 'hador',
						'Animated 10'	=> 'malva',
						'Animated 11'	=> 'sadoc',
					),
					'group' 		=> esc_html__( 'Content', 'codevz' ),
					'std'			=> 'default'
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__( 'Background', 'codevz' ),
					'param_name' => 'content_svg_background',
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'model',
						'value'			=> array('cora', 'smaug', 'uldor', 'dori', 'gram', 'indis', 'narvi', 'amras', 'hador', 'malva', 'sadoc' )
					),
					'group' => esc_html__( 'Content', 'codevz' ),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Position', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'position',
					'value'		=> array(
						'Top Center'	=> 'cz_hotspot_top',
						'Top Right'		=> 'cz_hotspot_top cz_hotspot_tr',
						'Top Left'		=> 'cz_hotspot_top cz_hotspot_tl',
						'Bottom Center'	=> 'cz_hotspot_bottom',
						'Bottom Right'	=> 'cz_hotspot_bottom cz_hotspot_br',
						'Bottom Left'	=> 'cz_hotspot_bottom cz_hotspot_bl',
						'Right Center'	=> 'cz_hotspot_right',
						'Left Center'	=> 'cz_hotspot_left',
					),
					'dependency'	=> array(
						'element'		=> 'model',
						'value'			=> array('default' )
					),
					'group' 		=> esc_html__( 'Content', 'codevz' )
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Always open ?", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "always_open",
					'dependency'	=> array(
						'element'		=> 'model',
						'value'			=> array('default' )
					),
					'group' 		=> esc_html__( 'Content', 'codevz' )
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Content Type', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'content_type',
					'value'			=> array(
						'Direct Content'	=> '1',
						'Page as Content'	=> '2',
					),
					'group' 		=> esc_html__( 'Content', 'codevz' )
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Select page', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'page',
					'value'			=> $this->pages(),
					'group' 		=> esc_html__( 'Content', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'content_type',
						'value'			=> array('2' )
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_content',
					"heading"     	=> esc_html__( "Contnet styling", 'codevz'),
					'button' 		=> esc_html__( "Contnet", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Content', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'model',
						'value'			=> array('default' )
					),
					'settings' 		=> array( 'display', 'width', 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' )
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
			$custom = $atts['css_left'] ? 'left:' . $atts['css_left'] . ';' : '';
			$custom .= $atts['css_top'] ? 'top:' . $atts['css_top'] . ';' : '';
			$custom .= $atts['anim_delay'] ? 'animation-delay:' . $atts['anim_delay'] . ';' : '';

			$css_array = array(
				'sk_zzz' 		=> array( $css_id, $custom ),
				'sk_brfx' 		=> $css_id . ':before',
				'sk_content' 	=> $css_id . ' .cz_wpe_content',
				'sk_icon' 		=> array( $css_id . ' .cz_hotspot_circle', 'animation-duration: ' . $atts['icon_animation-duration'] . ';transform: ' . $atts['icon_transform'] . ';' )
			);

			$css = Codevz_Plus::sk_style( $atts, $css_array );
			$css_t = Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m = Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			if ( Codevz_Plus::contains( $atts['icon_transform'], '45' ) ) {
				$css = '.cz_hotspot_circle i {transform: rotate(-45deg)}';
			}
		}

		// Icon
		$icon = '';
		if ( $atts['icon_type'] === 'number' ) {
			$icon = '<i class="cz_hotspot_number">' . $atts['number'] . '</i>';
		} else if ( $atts['icon_type'] === 'image' ) {
			$img = Codevz_Plus::get_image( $atts['image'], $atts['image_size'] );
			$icon = '<i class="cz_hotspot_image">' . $img . '</i>';
		} else if ( $atts['icon'] ) {
			$icon = '<i class="' . $atts['icon'] . '"></i>';
		}
		$circle_parent = ' style="animation-duration: ' . $atts['icon_animation-duration'] . '"';

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_hotspot';
		$classes[] = $atts['always_open'] ? 'cz_hotspot_always_open' : '';

		// Data
		$data = Codevz_Plus::$vc_editable ? ' data-top="' . $atts['css_top'] . '" data-left="' . $atts['css_left'] . '"' : '';

		// Styles.
		wp_enqueue_script( 'cz_free_position_element' );

		// Out
		$contente = '';
		if ( ! empty( $atts['content_type'] ) && ( ! empty( $content ) || ! empty( $atts['page'] ) ) ) {
			$contente = ( $atts['content_type'] === '1' ) ? do_shortcode( Codevz_Plus::fix_extra_p( str_replace( '&nbsp;', '', $content ) ) ) : do_shortcode( get_post_field( 'post_content', $atts['page'] ) );
		}
		if ( empty( $atts['model'] ) || $atts['model'] === 'default' ) {
			$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . $data . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div class="' . $atts['icon_effect'] . '"' . $circle_parent . '><div class="cz_hotspot_circle">' . $icon . '</div></div><div class="cz_hotspot_content cz_wpe_content ' . $atts['position'] . '">' . $contente . '</div></div>';
		} else {
			wp_enqueue_script( 'codevz-tooltip' );

			$name = $atts['model'];
			$svg_fill = $atts['content_svg_background'] ? ' style="fill: ' . $atts['content_svg_background'] . '"' : '';
			$classes[] = 'tooltip tooltip--' . $name;
			$out = '<div id="' . $atts['id'] . '" data-type="' . $name . '"' . Codevz_Plus::classes( $atts, $classes ) . $data . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>
				<div class="tooltip__trigger" role="tooltip" aria-describedby="info-' . $name . '">
					<span class="tooltip__trigger-text"><div class="' . $atts['icon_effect'] . '"' . $circle_parent . '><div class="cz_hotspot_circle">' . $icon . '</div></div></span>
				</div>
				<div class="tooltip__base">
					<svg class="tooltip__shape" width="100%" height="100%" viewBox="0 0 400 300"' . $svg_fill . '>' . self::path( $atts['model'] ) . '</svg>
					<div class="tooltip__content cz_wpe_content id="info-' . $name . '">' . $contente . '</div>
				</div>
			</div>';
		}

		return Codevz_Plus::_out( $atts, $out, 'free_position_element( ".vc_cz_hotspot" )', $this->name );
	}

	/**
	 *
	 * SVG Path
	 * 
	 * @return string
	 * 
	 */
	public static function path( $name = '' ) {
		if ( $name === 'cora' ) {
			return '<path d="M 199,21.9 C 152,22.2 109,35.7 78.8,57.4 48,79.1 29,109 29,142 29,172 45.9,201 73.6,222 101,243 140,258 183,260 189,270 200,282 200,282 200,282 211,270 217,260 261,258 299,243 327,222 354,201 371,172 371,142 371,109 352,78.7 321,57 290,35.3 247,21.9 199,21.9 Z"/>';
		} else if ( $name === 'smaug' ) {
			return '<path d="M 314,100 C 313,100 312,100 311,100 L 89.5,100 C 55.9,100 29.1,121 29.1,150 29.1,178 53.1,201 89.5,201 L 184,201 200,223 217,201 311,201 C 344,201 371,178 371,150 371,122 346,99 314,100 Z"/>';
		} else if ( $name === 'uldor' ) {
			return '<path d="M 79.5,66 C 79.5,66 128,106 202,105 276,104 321,66 321,66 321,66 287,84 288,155 289,226 318,232 318,232 318,232 258,198 200,198 142,198 80.5,230 80.5,230 80.5,230 112,222 111,152 110,82 79.5,66 79.5,66 Z"/>';
		} else if ( $name === 'dori' ) {
			return '<path d="M 22,108 22,236 C 22,236 64,216 103,212 142,208 184,212 184,212 L 200,229 216,212 C 216,212 258,207 297,212 336,217 378,236 378,236 L 378,108 C 378,108 318,83.7 200,83.7 82,83.7 22,108 22,108 Z"/>';
		} else if ( $name === 'gram' ) {
			return '<path d="M 92.4,79 C 136,79 154,115 200,116 246,117 263,80.4 308,79 353,77.6 381,111 381,150 381,189 346,220 308,221 270,222 236,188 200,188 164,188 130,222 92.4,221 54.4,220 19,189 19,150 19,111 48.6,79 92.4,79 Z"/>';
		} else if ( $name === 'indis' ) {
			return '<path d="M 44.5,24 C 138,4.47 246,-6.47 356,24 367,26.9 376,32.9 376,44 L 376,256 C 376,267 367,279 356,276 231,240 168,241 44.5,276 33.8,279 24.5,267 24.5,256 L 24.5,44 C 24.5,32.9 33.6,26.3 44.5,24 Z"/>';
		} else if ( $name === 'narvi' ) {
			return '<path class="path-narvi" d="M 307,150 199,212 92.5,274 92.7,150 92.5,26.2 200,88.4 Z"/>';
		} else if ( $name === 'amras' ) {
			return '<path class="path-amras-2" d="M 293,106 A 90.1,90.1 0 0 1 203,197 90.1,90.1 0 0 1 112,106 90.1,90.1 0 0 1 203,16.2 90.1,90.1 0 0 1 293,106 Z"/>
					<path class="path-amras-3" d="M 324,66.2 A 46.9,46.9 0 0 1 277,113 46.9,46.9 0 0 1 230,66.2 46.9,46.9 0 0 1 277,19.3 46.9,46.9 0 0 1 324,66.2 Z"/>
					<path class="path-amras-1" d="M 180,111 A 67.2,67.2 0 0 1 112,178 67.2,67.2 0 0 1 45.9,111 67.2,67.2 0 0 1 112,43.5 67.2,67.2 0 0 1 180,111 Z"/>
					<path class="path-amras-4" d="M 371,98.6 A 52.7,52.7 0 0 1 318,152 52.7,52.7 0 0 1 266,98.6 52.7,52.7 0 0 1 318,45.9 52.7,52.7 0 0 1 371,98.6 Z"/>
					<path class="path-amras-9" d="M 375,167 A 66.8,55.1 0 0 1 308,222 66.8,55.1 0 0 1 241,167 66.8,55.1 0 0 1 308,112 66.8,55.1 0 0 1 375,167 Z"/>
					<path class="path-amras-5" d="M 187,199 A 52,52 0 0 1 136,251 52,52 0 0 1 84.1,199 52,52 0 0 1 136,147 52,52 0 0 1 187,199 Z"/>
					<path class="path-amras-6" d="M 287,217 A 66.8,66.8 0 0 1 221,284 66.8,66.8 0 0 1 154,217 66.8,66.8 0 0 1 221,150 66.8,66.8 0 0 1 287,217 Z"/>
					<path class="path-amras-7" d="M 132,168 A 53.9,53.9 0 0 1 78.7,222 53.9,53.9 0 0 1 24.8,168 53.9,53.9 0 0 1 78.7,114 53.9,53.9 0 0 1 132,168 Z"/>
					<path class="path-amras-8" d="M 343,211 A 48.7,48.7 0 0 1 295,260 48.7,48.7 0 0 1 246,211 48.7,48.7 0 0 1 295,163 48.7,48.7 0 0 1 343,211 Z"/>';
		} else if ( $name === 'hador' ) {
			return '<path class="path-hador-1" d="M 154,283 A 6.12,6.12 0 0 1 149,290 6.12,6.12 0 0 1 142,286 6.12,6.12 0 0 1 146,278 6.12,6.12 0 0 1 154,283 Z"/>
					<path class="path-hador-2" d="M 167,265 A 7.83,7.83 0 0 1 162,276 7.83,7.83 0 0 1 152,270 7.83,7.83 0 0 1 157,261 7.83,7.83 0 0 1 167,265 Z"/>
					<path class="path-hador-3" d="M 183,244 A 11.9,11.9 0 0 1 174,258 11.9,11.9 0 0 1 160,250 11.9,11.9 0 0 1 168,235 11.9,11.9 0 0 1 183,244 Z"/>
					<path class="path-hador-4" d="M 327,120 A 127,111 0 0 1 200,231 127,111 0 0 1 72.9,120 127,111 0 0 1 200,9.44 127,111 0 0 1 327,120 Z"/>';
		} else if ( $name === 'malva' ) {
			return '<path d="M 94.9,90.2 101,30.7 163,72.3 229,17.7 263,68.2 319,55.9 315,102 375,144 316,175 340,228 265,220 251,263 180,233 143,282 98.9,218 57.5,236 82,189 25,170 82.8,141 48.7,93.7 Z"/>';
		} else if ( $name === 'sadoc' ) {
			return '<path d="M 32.1,42.7 54.5,257 185,257 193,269 200,282 207,269 214,257 342,257 368,23.9 Z"/>';
		}
	}
}