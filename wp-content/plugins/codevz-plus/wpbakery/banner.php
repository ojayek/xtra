<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Banner
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_banner {

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
			'name'			=> esc_html__( 'Banner', 'codevz' ),
			'description'	=> esc_html__( 'Image box with hover FX', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"heading" => esc_html__("Style","codevz"),
					"param_name" => "style",
					'edit_field_class' => 'vc_col-xs-99',
					"value" => array(
						esc_html__( "Style", 'codevz') . ' #1'=>'style1',
						esc_html__( "Style", 'codevz') . ' #2'=>'style2',
						esc_html__( "Style", 'codevz') . ' #3'=>'style3',
						esc_html__( "Style", 'codevz') . ' #4'=>'style4',
						esc_html__( "Style", 'codevz') . ' #5'=>'style5',
						esc_html__( "Style", 'codevz') . ' #6'=>'style6',
						esc_html__( "Style", 'codevz') . ' #7'=>'style7',
						esc_html__( "Style", 'codevz') . ' #8'=>'style8',
						esc_html__( "Style", 'codevz') . ' #9'=>'style9',
						esc_html__( "Style", 'codevz') . ' #10'=>'style10',
						esc_html__( "Style", 'codevz') . ' #11'=>'style11',
						esc_html__( "Style", 'codevz') . ' #12'=>'style12',
						esc_html__( "Style", 'codevz') . ' #13'=>'style13',
						esc_html__( "Style", 'codevz') . ' #14'=>'style14',
						esc_html__( "Style", 'codevz') . ' #15'=>'style15',
						esc_html__( "Style", 'codevz') . ' #16'=>'style16',
						esc_html__( "Style", 'codevz') . ' #17'=>'style17',
						esc_html__( "Style", 'codevz') . ' #18'=>'style18',
						esc_html__( "Style", 'codevz') . ' #19'=>'style19',
						esc_html__( "Style", 'codevz') . ' #20'=>'style20',
						esc_html__( "Style", 'codevz') . ' #21'=>'style21',
						esc_html__( "Style", 'codevz') . ' #22'=>'style22',
					),
				),
				array(
					"type" 			=> "textfield",
					"heading" 		=> esc_html__("Title","codevz"),
					"param_name" 	=> "title",
					'edit_field_class' => 'vc_col-xs-99',
					"value" 		=> "Your Title"
				 ),
				array(
					"type" 			=> "textarea_html",
					"heading" 		=> esc_html__("Caption","codevz"),
					"param_name" 	=> "content",
					"value" 		=> "Lorem ipsum dolor sit...",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					"type"        	=> "vc_link",
					"heading"     	=> esc_html__("Link", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "link"
				),

				// Image
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Image', 'codevz' )
				),
				array(
					"type" 			=> "attach_image",
					"heading" 		=> esc_html__("Image","codevz"),
					"param_name" 	=> "image",
					"edit_field_class" => 'vc_col-xs-99',
					"value" 		=> "",
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Image size", 'codevz'),
					"description"   => esc_html__('Enter image size (e.g: "thumbnail", "medium", "large", "full"), Alternatively enter size in pixels (e.g: 200x100 (Width x Height)).', 'codevz'),
					"value"  		=> "full",
					"edit_field_class" => 'vc_col-xs-99',
					"param_name"  	=> "size",
					'dependency'	=> array(
						'element'		=> 'image',
						'not_empty'		=> true
					),
				),
				array(
					"type" => "dropdown",
					"holder" => "div",
					"heading" => esc_html__("Image opacity","codevz"),
					"param_name" => "image_opacity",
					"edit_field_class" => 'vc_col-xs-99',
					"value" => array('1','0.9','0.8','0.7','0.6','0.5','0.4','0.3','0.2','0.1','0'),
					'dependency'	=> array(
						'element'		=> 'image',
						'not_empty'		=> true
					),
				),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"heading" => esc_html__("Image hover opacity","codevz"),
					"param_name" => "image_hover_opacity",
					"edit_field_class" => 'vc_col-xs-99',
					"value" => array('1','0.9','0.8','0.7','0.6','0.5','0.4','0.3','0.2','0.1','0'),
					'dependency'	=> array(
						'element'		=> 'image',
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
					'param_name' 	=> 'sk_box',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_box_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_box_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_title',
					"heading"     	=> esc_html__( "Title", 'codevz'),
					'button' 		=> esc_html__( "Title", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-family', 'font-size' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_caption',
					"heading"     	=> esc_html__( "Caption", 'codevz'),
					'button' 		=> esc_html__( "Caption", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'style',
						'value'			=> array( 'style4' )
					),
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_caption_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_caption_mobile' ),
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

	public function out( $atts, $content = '' ) {
		$atts = Codevz_Plus::shortcode_atts( $this, $atts );

		// ID
		if ( ! $atts['id'] ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}

		// Link
		$link = $atts['link'] ? '<a'. Codevz_Plus::link_attrs( $atts['link'] ) . '> </a>' : '';

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_banner clr';
		$classes[] = $atts['svg_bg'] ? 'cz_svg_bg' : '';
		$classes[] = $atts['text_center'] ? 'cz_mobile_text_center' : '';

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];

			$css_array = array(
				'svg_bg' 	=> $css_id . '.cz_svg_bg:before',
				'sk_brfx' 	=> $css_id . ':before',
				'sk_title' 	=> $css_id . ' h4',
				'sk_box' 	=> $css_id . ' figure',
				(( $atts['style'] === 'style4' ) ? 'sk_caption' : 'x') => $css_id . ' figcaption'
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			$css .= $atts['anim_delay'] ? $css_id . '{animation-delay:' . $atts['anim_delay'] . '}' : '';
			$css .= $atts['image_opacity'] ? $css_id . ' img{opacity:' . $atts['image_opacity'] . '}' : '';
			$css .= ($atts['image_hover_opacity'] || $atts['image_hover_opacity']==='0') ? $css_id . ':hover img{opacity:' . $atts['image_hover_opacity'] . '}' : '';

		} else {
			Codevz_Plus::load_font( $atts['sk_title'] );
		}

		// Image
		$image = Codevz_Plus::get_image( $atts['image'], $atts['size'] );

		$content = do_shortcode( Codevz_Plus::fix_extra_p( $content ) );
		$content = $content ? '<p class="cz_wpe_content">' . $content . '</p>' : '';

		// Out
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>
			<figure class="effect-' . $atts['style'] . '"' . Codevz_Plus::tilt( $atts ) . '>
				' . $image . '
				<figcaption><div><h4>' . $atts['title'] . '</h4>' . $content . '</div>' . $link . '</figcaption>			
			</figure>
		</div>';

		return Codevz_Plus::_out( $atts, $out, 'tilt', $this->name );
	}
}