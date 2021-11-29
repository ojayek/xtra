<?php if ( ! defined( 'ABSPATH' ) ) { exit; } // Cannot access pages directly.

/**
 * Posts Grid
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_posts {

	public function __construct( $name ) {
		$this->name = $name;
		add_action( 'wp_ajax_cz_ajax_posts', array( $this, 'get_posts' ) );
		add_action( 'wp_ajax_nopriv_cz_ajax_posts', array( $this, 'get_posts' ) );
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Posts Grid', 'codevz' ),
			'description'	=> esc_html__( 'Display post types posts', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'cz_hidden',
					'param_name' 	=> 'query',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "cz_image_select",
					"heading"     	=> esc_html__( 'Layout', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "layout",
					'options'			=> array(
						'cz_justified'				=> Codevz_Plus::$url . 'assets/img/gallery_1.png',
						'cz_grid_c1 cz_grid_l1'		=> Codevz_Plus::$url . 'assets/img/gallery_2.png',
						'cz_grid_c2 cz_grid_l2'		=> Codevz_Plus::$url . 'assets/img/gallery_3.png',
						'cz_grid_c2'				=> Codevz_Plus::$url . 'assets/img/gallery_4.png',
						'cz_grid_c3'				=> Codevz_Plus::$url . 'assets/img/gallery_5.png',
						'cz_grid_c4'				=> Codevz_Plus::$url . 'assets/img/gallery_6.png',
						'cz_grid_c5'				=> Codevz_Plus::$url . 'assets/img/gallery_7.png',
						'cz_grid_c6'				=> Codevz_Plus::$url . 'assets/img/gallery_8.png',
						'cz_grid_c7'				=> Codevz_Plus::$url . 'assets/img/gallery_9.png',
						'cz_grid_c8'				=> Codevz_Plus::$url . 'assets/img/gallery_10.png',
						'cz_hr_grid cz_grid_c2'		=> Codevz_Plus::$url . 'assets/img/gallery_11.png',
						'cz_hr_grid cz_grid_c3'		=> Codevz_Plus::$url . 'assets/img/gallery_12.png',
						'cz_hr_grid cz_grid_c4'		=> Codevz_Plus::$url . 'assets/img/gallery_13.png',
						'cz_hr_grid cz_grid_c5'		=> Codevz_Plus::$url . 'assets/img/gallery_14.png',
						'cz_masonry cz_grid_c2'		=> Codevz_Plus::$url . 'assets/img/gallery_15.png',
						'cz_masonry cz_grid_c3'		=> Codevz_Plus::$url . 'assets/img/gallery_16.png',
						'cz_masonry cz_grid_c4'		=> Codevz_Plus::$url . 'assets/img/gallery_17.png',
						'cz_masonry cz_grid_c4 cz_grid_1big' => Codevz_Plus::$url . 'assets/img/gallery_18.png',
						'cz_masonry cz_grid_c5'		=> Codevz_Plus::$url . 'assets/img/gallery_19.png',
						'cz_metro_1 cz_grid_c4'		=> Codevz_Plus::$url . 'assets/img/gallery_20.png',
						'cz_metro_2 cz_grid_c4'		=> Codevz_Plus::$url . 'assets/img/gallery_21.png',
						'cz_metro_3 cz_grid_c4'		=> Codevz_Plus::$url . 'assets/img/gallery_22.png',
						'cz_metro_4 cz_grid_c4'		=> Codevz_Plus::$url . 'assets/img/gallery_23.png',
						'cz_metro_5 cz_grid_c3'		=> Codevz_Plus::$url . 'assets/img/gallery_24.png',
						'cz_metro_6 cz_grid_c3'		=> Codevz_Plus::$url . 'assets/img/gallery_25.png',
						'cz_metro_7 cz_grid_c7'		=> Codevz_Plus::$url . 'assets/img/gallery_26.png',
						'cz_metro_8 cz_grid_c4'		=> Codevz_Plus::$url . 'assets/img/gallery_27.png',
						'cz_metro_9 cz_grid_c6'		=> Codevz_Plus::$url . 'assets/img/gallery_28.png',
						'cz_metro_10 cz_grid_c6'	=> Codevz_Plus::$url . 'assets/img/gallery_29.png',
						'cz_grid_carousel'			=> Codevz_Plus::$url . 'assets/img/gallery_30.png',
						'cz_posts_list_1'			=> Codevz_Plus::$url . 'assets/img/posts_list_1.png',
						'cz_posts_list_2'			=> Codevz_Plus::$url . 'assets/img/posts_list_2.png',
						'cz_posts_list_3'			=> Codevz_Plus::$url . 'assets/img/posts_list_3.png',
						'cz_posts_list_4'			=> Codevz_Plus::$url . 'assets/img/posts_list_4.png',
						'cz_posts_list_5'			=> Codevz_Plus::$url . 'assets/img/posts_list_5.png',
					),
					'std'			=> 'cz_grid_c4',
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Custom size", 'codevz'),
					"description"   => esc_html__('Enter image size (e.g: "thumbnail", "medium", "large", "full"), Alternatively enter size in pixels (e.g: 200x100 (Width x Height)).', 'codevz'),
					"param_name"  	=> "custom_size",
					"edit_field_class" => 'vc_col-xs-99'
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title_op',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_slider',
					'heading' 		=> esc_html__('Posts count', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'posts_per_page',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 30 )
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Posts gap", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "gap",
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Two columns on mobile?", 'codevz'),
					"param_name"  	=> "two_columns_on_mobile",
					"edit_field_class" => 'vc_col-xs-99'
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Posts details style", 'codevz'),
					"param_name"  	=> "hover",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						'No hover details' 										=> 'cz_grid_1_no_hover',
						'Only icon on hover' 									=> 'cz_grid_1_no_title cz_grid_1_no_desc',
						'Icon & Title on hover' 								=> 'cz_grid_1_no_desc',
						'Icon & Title & Meta on hover' 							=> 'cz_grid_1_yes_all',
						'Title on hover' 										=> 'cz_grid_1_no_icon cz_grid_1_no_desc',
						'Title & Meta on hover' 								=> 'cz_grid_1_no_icon',
						'Title & Excerpt on hover' 								=> 'cz_grid_1_no_icon cz_grid_1_has_excerpt cz_grid_1_no_desc',
						'Title & Meta & Excerpt on hover' 						=> 'cz_grid_1_no_icon cz_grid_1_has_excerpt',
						'No hover details, Title & Meta after Image' 			=> 'cz_grid_1_title_sub_after cz_grid_1_no_hover',
						'Icon on hover, Title & Meta after Image' 				=> 'cz_grid_1_title_sub_after',
						'Icon on hover, Title & Meta & Excerpt after Image' 	=> 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt',
						'No Icon, Title & Meta & Excerpt after Image' 			=> 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_icon',
						'Meta on image, Title after image' 						=> 'cz_grid_1_title_sub_after cz_grid_1_subtitle_on_img',
						'Meta on image, Title & Excerpt after image' 			=> 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_subtitle_on_img',
						'No image, Title & Meta' 								=> 'cz_grid_1_title_sub_after cz_grid_1_no_image',
						'No image, Title & Meta & Excerpt' 						=> 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image',
					),
					'std'			=> 'cz_grid_1_no_icon',
					'dependency'	=> array(
						'element'				=> 'layout',
						'value_not_equal_to'	=> array( 'cz_posts_list_1','cz_posts_list_2','cz_posts_list_3','cz_posts_list_4','cz_posts_list_5' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Title Tag", 'codevz'),
					"param_name"  	=> "title_tag",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						'H1' 			=> 'h1',
						'H2' 			=> 'h2',
						'H3' 			=> 'h3',
						'H4' 			=> 'h4',
						'H5' 			=> 'h5',
						'H6' 			=> 'h6',
						'p' 			=> 'p',
					),
					'std'			=> 'h3',
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__( "Intro animation", "codevz" ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "animation",
					"value"			=> array(
						esc_html__( "Select", "codevz" )		=> '',
						esc_html__( "Fade In", "codevz" )		=> 'cz_grid_anim_fade_in',
						esc_html__( "Move Up", "codevz" )		=> 'cz_grid_anim_move_up',
						esc_html__( "Move Down", "codevz" )		=> 'cz_grid_anim_move_down',
						esc_html__( "Move Right", "codevz" )	=> 'cz_grid_anim_move_right',
						esc_html__( "Move Left", "codevz" )		=> 'cz_grid_anim_move_left',
						esc_html__( "Zoom In", "codevz" )		=> 'cz_grid_anim_zoom_in',
						esc_html__( "Zoom Out", "codevz" )		=> 'cz_grid_anim_zoom_out',
						esc_html__( "Slant", "codevz" ) 		=> 'cz_grid_anim_slant',
						esc_html__( "Helix", "codevz" ) 		=> 'cz_grid_anim_helix',
						esc_html__( "Fall Perspective", "codevz" ) 		=> 'cz_grid_anim_fall_perspective',
						esc_html__( "Block reveal right", "codevz" ) 	=> 'cz_grid_brfx_right',
						esc_html__( "Block reveal left", "codevz" ) 	=> 'cz_grid_brfx_left',
						esc_html__( "Block reveal up", "codevz" ) 		=> 'cz_grid_brfx_up',
						esc_html__( "Block reveal down", "codevz" ) 	=> 'cz_grid_brfx_down',
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_brfx',
					"heading"     	=> esc_html__( "Block Reveal", 'codevz'),
					'button' 		=> esc_html__( "Block Reveal", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99 hidden',
					'settings' 		=> array( 'background' ),
					'dependency'	=> array(
						'element'		=> 'animation',
						'value'			=> array( 'cz_grid_brfx_right', 'cz_grid_brfx_left', 'cz_grid_brfx_up', 'cz_grid_brfx_down' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Meta position?", 'codevz'),
					"param_name"  	=> "subtitle_pos",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						'Select' 			=> '',
						'Before title' 		=> 'cz_grid_1_title_rev',
						'After Excerpt' 	=> 'cz_grid_1_sub_after_ex',
					),
					'dependency'	=> array(
						'element'			=> 'hover',
						'value_not_equal_to'=> array( 'cz_grid_1_no_hover', 'cz_grid_1_no_title', 'cz_grid_1_no_desc', 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_subtitle_on_img', 'cz_grid_1_title_sub_after cz_grid_1_subtitle_on_img', 'cz_grid_1_no_icon cz_grid_1_no_desc' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Details align", 'codevz'),
					"param_name"  	=> "hover_pos",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						'Top Left' 		=> 'cz_grid_1_top tal',
						'Top Center' 	=> 'cz_grid_1_top tac',
						'Top Right' 	=> 'cz_grid_1_top tar',
						'Middle Left' 	=> 'cz_grid_1_mid tal',
						'Middle Center' => 'cz_grid_1_mid tac',
						'Middle Right' 	=> 'cz_grid_1_mid tar',
						'Bottom Left' 	=> 'cz_grid_1_bot tal',
						'Bottom Center' => 'cz_grid_1_bot tac',
						'Bottom Right' 	=> 'cz_grid_1_bot tar',
					),
					'std'			=> Codevz_Plus::$is_rtl ? 'cz_grid_1_bot tar' : 'cz_grid_1_bot tal'
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Hover visibility?", 'codevz'),
					"param_name"  	=> "hover_vis",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						'Show overlay on hover' => '',
						'Hide overlay on hover' => 'cz_grid_1_hide_on_hover',
						'Always show overlay' 	=> 'cz_grid_1_always_show',
					),
					'dependency'	=> array(
						'element'			=> 'hover',
						'value_not_equal_to'=> array( 'cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_image', 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Hover effect?", 'codevz'),
					"param_name"  	=> "hover_fx",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'Fade in Top', 'codevz') 		=> '',
						esc_html__( 'Fade in Bottom', 'codevz') 	=> 'cz_grid_fib',
						esc_html__( 'Fade in Left', 'codevz') 		=> 'cz_grid_fil',
						esc_html__( 'Fade in Right', 'codevz') 		=> 'cz_grid_fir',
						esc_html__( 'Zoom in', 'codevz') 			=> 'cz_grid_zin',
						esc_html__( 'Zoom Out', 'codevz') 			=> 'cz_grid_zou',
						esc_html__( 'Opening Vertical', 'codevz') 	=> 'cz_grid_siv',
						esc_html__( 'Opening Horizontal', 'codevz') => 'cz_grid_sih',
						esc_html__( 'Slide in Left', 'codevz') 		=> 'cz_grid_sil',
						esc_html__( 'Slide in Right', 'codevz') 	=> 'cz_grid_sir',
					),
					'dependency'	=> array(
						'element'			=> 'hover',
						'value_not_equal_to'=> array( 'cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_image', 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Hover image effect?", 'codevz'),
					"param_name"  	=> "img_fx",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'Select', 'codevz') 			=> '',
						esc_html__( 'Inset Mask 1x', 'codevz') 		=> 'cz_grid_inset_clip_1x',
						esc_html__( 'Inset Mask 2x', 'codevz') 		=> 'cz_grid_inset_clip_2x',
						esc_html__( 'Inset Mask 3x', 'codevz') 		=> 'cz_grid_inset_clip_3x',
						esc_html__( 'Zoom Mask', 'codevz') 			=> 'cz_grid_zoom_mask',
						esc_html__( 'Scale', 'codevz') 				=> 'cz_grid_scale',
						esc_html__( 'Scale 2', 'codevz') 			=> 'cz_grid_scale2',
						esc_html__( 'Grayscale', 'codevz') 			=> 'cz_grid_grayscale',
						esc_html__( 'Grayscale on hover', 'codevz') => 'cz_grid_grayscale_on_hover',
						esc_html__( 'Remove Grayscale', 'codevz') 	=> 'cz_grid_grayscale_remove',
						esc_html__( 'Blur', 'codevz') 				=> 'cz_grid_blur',
						esc_html__( 'ZoomIn', 'codevz') 			=> 'cz_grid_zoom_in',
						esc_html__( 'ZoomOut', 'codevz') 			=> 'cz_grid_zoom_out',
						esc_html__( 'Zoom Roate', 'codevz') 		=> 'cz_grid_zoom_rotate',
						esc_html__( 'Flash', 'codevz') 				=> 'cz_grid_flash',
						esc_html__( 'Shine', 'codevz') 				=> 'cz_grid_shine',
					),
					'dependency'	=> array(
						'element'			=> 'hover',
						'value_not_equal_to'=> array( 'cz_grid_1_title_sub_after cz_grid_1_no_image', 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image' )
					),
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Ideal height", 'codevz'),
					"description"   => esc_html__("Only works on layout 1", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 10, 'min' => 80, 'max' => 700 ),
					'dependency'	=> array(
						'element'		=> 'layout',
						'value'			=> array( 'cz_justified' )
					),
					"param_name"  	=> "height"
				),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_container',
					'hover_id' 		=> 'sk_container_hover',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'group' 		=> esc_html__( "Styling", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_container_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_container_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_overall',
					'hover_id' 		=> 'sk_overall_hover',
					"heading"     	=> esc_html__( "All posts", 'codevz'),
					'button' 		=> esc_html__( "All posts", 'codevz'),
					'group' 		=> esc_html__( "Styling", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_img',
					'hover_id' 		=> 'sk_img_hover',
					"heading"     	=> esc_html__( "Images", 'codevz'),
					'button' 		=> esc_html__( "Images", 'codevz'),
					'group' 		=> esc_html__( "Styling", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'margin', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_img_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_img_hover' ),

				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Overlay scale', 'codevz' ),
					'group' 		=> esc_html__( "Styling", 'codevz'),
					'param_name' 	=> 'overlay_outer_space',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						'Default'		=> '',
						'#1'			=> 'cz_grid_overlay_5px',
						'#2'			=> 'cz_grid_overlay_10px',
						'#3'			=> 'cz_grid_overlay_15px',
						'#4'			=> 'cz_grid_overlay_20px',
					),
					'dependency'	=> array(
						'element'				=> 'hover',
						'value_not_equal_to'	=> array( 'cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_image', 'cz_grid_1_title_sub_after cz_grid_1_no_image', 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image' )
					)
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_overlay',
					'hover_id'	 	=> 'sk_overlay_hover',
					"heading"     	=> esc_html__( "Overlay", 'codevz'),
					'button' 		=> esc_html__( "Overlay", 'codevz'),
					'group' 		=> esc_html__( "Styling", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'border' ),
					'dependency'	=> array(
						'element'				=> 'hover',
						'value_not_equal_to'	=> array( 'cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_image', 'cz_grid_1_title_sub_after cz_grid_1_no_image', 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image' )
					)
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overlay_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icon',
					'hover_id' 		=> 'sk_icon_hover',
					"heading"     	=> esc_html__( "Icon", 'codevz'),
					'button' 		=> esc_html__( "Icon", 'codevz'),
					'group' 		=> esc_html__( "Styling", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
					'dependency'	=> array(
						'element'	=> 'hover',
						'value'		=> array( 'cz_grid_1_no_title', 'cz_grid_1_no_desc', 'cz_grid_1_yes_all', 'cz_grid_1_title_sub_after', 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_content',
					'hover_id' 		=> 'sk_content_hover',
					"heading"     	=> esc_html__( "Out content", 'codevz'),
					'button' 		=> esc_html__( "Out content", 'codevz'),
					'group' 		=> esc_html__( "Styling", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_title',
					'hover_id' 		=> 'sk_title_hover',
					"heading"     	=> esc_html__( "Title", 'codevz'),
					'button' 		=> esc_html__( "Title", 'codevz'),
					'group' 		=> esc_html__( "Styling", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-family', 'font-size' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_hover' ),

				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__( 'Title length', 'codevz' ),
					'param_name'	=> 'title_lenght',
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 100 ),
					'group' 		=> esc_html__( "Styling", 'codevz' ),
				),
				array(
					'type'			=> 'checkbox',
					'heading'		=> esc_html__( 'Single line title', 'codevz' ),
					'param_name'	=> 'single_line_title',
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( "Styling", 'codevz'),
				),

				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Icon", 'codevz'),
					"param_name"  	=> "icon",
					"value"  		=> "fa fa-search",
					'edit_field_class' => 'vc_col-xs-99',
				),

				// Meta
				array(
					'type' 			=> 'param_group',
					'heading' 		=> esc_html__( 'Posts meta', 'codevz' ),
					'param_name' 	=> 'subtitles',
					'params' 		=> array(
						array(
							'type' 				=> 'dropdown',
							'heading' 			=> esc_html__( 'Type', 'codevz' ),
							'param_name' 		=> 't',
							'edit_field_class' 	=> 'vc_col-xs-99',
							'value'				=> array(
								esc_html__( 'Date', 'codevz' ) 				=> 'date',
								esc_html__( 'Categories', 'codevz' ) 		=> 'cats',
								esc_html__( 'Categories', 'codevz' ) . ' 2' => 'cats_2',
								esc_html__( 'Categories', 'codevz' ) . ' 3' => 'cats_3',
								esc_html__( 'Categories', 'codevz' ) . ' 4' => 'cats_4',
								esc_html__( 'Categories', 'codevz' ) . ' 5' => 'cats_5',
								esc_html__( 'Categories', 'codevz' ) . ' 6' => 'cats_6',
								esc_html__( 'Categories', 'codevz' ) . ' 7' => 'cats_7',
								esc_html__( 'Tags', 'codevz' ) 				=> 'tags',
								esc_html__( 'Author', 'codevz' ) 			=> 'author',
								esc_html__( 'Author Avatar', 'codevz' ) 	=> 'author_avatar',
								esc_html__( 'Avatar + Author & Date', 'codevz' ) => 'author_full_date',
								esc_html__( 'Icon + Author & Date', 'codevz' ) 	=> 'author_icon_date',
								esc_html__( 'Comments', 'codevz' ) 				=> 'comments',
								esc_html__( 'Product Price', 'codevz' ) 		=> 'price',
								esc_html__( 'Product add to cart', 'codevz' ) 	=> 'add_to_cart',
								esc_html__( 'Custom Text', 'codevz' ) 			=> 'custom_text',
								esc_html__( 'Custom Meta', 'codevz' ) 			=> 'custom_meta',
							),
							'std' 				=> 'date',
							'admin_label'		=> true
						),
						array(
							'type' 				=> 'dropdown',
							'heading' 			=> esc_html__( 'Position', 'codevz' ),
							'param_name' 		=> 'r',
							'edit_field_class' 	=> 'vc_col-xs-99',
							'value'				=> array(
								esc_html__( '~ Default ~', 'codevz' ) 	=> '',
								esc_html__( 'Inverted', 'codevz' ) 		=> 'cz_post_data_r',
							),
							'std'				=> Codevz_Plus::$is_rtl ? 'cz_post_data_r' : ''
						),
						array(
							'type'				=> 'cz_icon',
							'heading'			=> esc_html__('Icon', 'codevz'),
							'param_name'		=> 'i',
							'edit_field_class' 	=> 'vc_col-xs-99',
							'dependency'	=> array(
								'element'			=> 't',
								'value_not_equal_to'=> array( 'author_avatar', 'author_full_date' )
							)
						),
						array(
							'type'				=> 'textfield',
							'heading'			=> esc_html__('Prefix', 'codevz'),
							'param_name'		=> 'p',
							'edit_field_class' 	=> 'vc_col-xs-99',
							'dependency'	=> array(
								'element'		=> 't',
								'value'			=> array( 'date', 'cats', 'tags', 'author', 'comments' )
							)
						),
						array(
							'type'				=> 'textfield',
							'heading'			=> esc_html__('Custom text', 'codevz'),
							'param_name'		=> 'ct',
							'edit_field_class' 	=> 'vc_col-xs-99',
							'dependency'	=> array(
								'element'		=> 't',
								'value'			=> array( 'custom_text' )
							)
						),
						array(
							'type'				=> 'textfield',
							'heading'			=> esc_html__('Custom meta name', 'codevz'),
							'param_name'		=> 'cm',
							'edit_field_class' 	=> 'vc_col-xs-99',
							'dependency'	=> array(
								'element'		=> 't',
								'value'			=> array( 'custom_meta' )
							)
						),
						array(
							'type'			=> 'cz_slider',
							'heading'		=> esc_html__('Count', 'codevz'),
							'param_name'	=> 'tc',
							'edit_field_class' => 'vc_col-xs-99',
							'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ),
							'dependency'	=> array(
								'element'		=> 't',
								'value'			=> array( 'cats_2', 'cats_3', 'cats_4', 'cats_5', 'cats_6', 'cats_7', 'tags' )
							)
						),
					),
					'group' 			=> esc_html__( 'Meta', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_meta',
					'hover_id'	 	=> 'sk_meta_hover',
					"heading"     	=> esc_html__( "Meta", 'codevz'),
					'button' 		=> esc_html__( "Meta", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Meta', 'codevz' ),
					'settings' 		=> array( 'position', 'left', 'top', 'bottom', 'right', 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_meta_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_meta_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_meta_icons',
					'hover_id'	 	=> 'sk_meta_icons_hover',
					"heading"     	=> esc_html__( "Meta icons", 'codevz'),
					'button' 		=> esc_html__( "Meta icons", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Meta', 'codevz' ),
					'settings' 		=> array( 'color', 'font-size', 'background' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_meta_icons_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_meta_icons_hover' ),

				// Excerpt
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__( 'Excerpt lenght', 'codevz' ),
					'param_name'	=> 'el',
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 200 ),
					'group' 		=> esc_html__( 'Excerpt', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_excerpt',
					'hover_id'	 	=> 'sk_excerpt_hover',
					"heading"     	=> esc_html__( "Excerpt", 'codevz'),
					'button' 		=> esc_html__( "Excerpt", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Excerpt', 'codevz' ),
					'settings' 		=> array( 'color', 'text-align', 'font-size', 'margin' ),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_excerpt_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_excerpt_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_excerpt_hover' ),

				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Read more?', 'codevz' ),
					'param_name' => 'excerpt_rm',
					'edit_field_class' => 'vc_col-xs-99',
					'group' => esc_html__( 'Excerpt', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_readmore',
					'hover_id' 		=> 'sk_readmore_hover',
					"heading"     	=> esc_html__( "Read more", 'codevz'),
					'button' 		=> esc_html__( "Read more", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Excerpt', 'codevz' ),
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_readmore_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_readmore_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_readmore_hover' ),

				// Load More
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Type", 'codevz'),
					"param_name"  	=> "loadmore",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Select', 'codevz') 			=> '',
						esc_html__( 'Load More', 'codevz') 	=> 'loadmore',
						esc_html__( 'Infinite Scroll', 'codevz') 	=> 'infinite',
						esc_html__( 'Pagination', 'codevz') => 'pagination',
						esc_html__( 'Older / Newer', 'codevz') 		=> 'older',
					),
					'group' 		=> esc_html__( 'Pagination', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Position", 'codevz'),
					"param_name"  	=> "loadmore_pos",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Select', 'codevz') 	=> '',
						esc_html__( 'Left', 'codevz') 		=> 'tal',
						esc_html__( 'Center', 'codevz') 	=> 'tac',
						esc_html__( 'Right', 'codevz') 		=> 'tar',
						esc_html__( 'Block', 'codevz') 		=> 'cz_loadmore_block',
					),
					'std' 			=> 'tac',
					'group' 		=> esc_html__( 'Pagination', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'loadmore',
						'value'			=> array( 'loadmore', 'infinite' )
					),
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Title", 'codevz'),
					"param_name"  	=> "loadmore_title",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> 'Load More',
					'group' 		=> esc_html__( 'Pagination', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'loadmore',
						'value'			=> array( 'loadmore', 'infinite' )
					),
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("End", 'codevz'),
					"param_name"  	=> "loadmore_end",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> 'Not found more posts',
					'group' 		=> esc_html__( 'Pagination', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'loadmore',
						'value'			=> array( 'loadmore', 'infinite' )
					),
				),
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__('Posts count', 'codevz'),
					'param_name'	=> 'loadmore_lenght',
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ),
					'group' 		=> esc_html__( 'Pagination', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'loadmore',
						'value'			=> array( 'loadmore', 'infinite' )
					),
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_titles_pagi',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
					'group' 		=> esc_html__( 'Pagination', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'loadmore',
						'value'			=> array( 'loadmore', 'infinite' )
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_load_more',
					'hover_id' 		=> 'sk_load_more_hover',
					"heading"     	=> esc_html__( "Load more", 'codevz'),
					'button' 		=> esc_html__( "Load more", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Pagination', 'codevz' ),
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
					'dependency'	=> array(
						'element'		=> 'loadmore',
						'value'			=> array( 'loadmore', 'infinite' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_load_more_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_load_more_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_load_more_active',
					"heading"     	=> esc_html__( "Active mode", 'codevz'),
					'button' 		=> esc_html__( "Active mode", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Pagination', 'codevz' ),
					'settings' 		=> array( 'border-right-color', 'background' ),
					'dependency'	=> array(
						'element'		=> 'loadmore',
						'value'			=> array( 'loadmore', 'infinite' )
					),
				),

				// Filter
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Choose filters', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'filters',
					'group' 		=> esc_html__( 'Filter', 'codevz' )
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__("Taxonomy", "codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "filters_tax",
					"value"			=> get_taxonomies(),
					"std"			=> 'category',
					'group' 		=> esc_html__( 'Filter', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Position", 'codevz'),
					"param_name"  	=> "filters_pos",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'Select' 	=> '',
						'Left' 		=> 'tal',
						'Center' 	=> 'tac',
						'Right' 	=> 'tar',
					),
					'group' 		=> esc_html__( 'Filter', 'codevz' ),
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__('Show All', 'codevz'),
					"value"   		=> 'Show All',
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'browse_all',
					'group' 		=> esc_html__( 'Filter', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Filters items count?", 'codevz'),
					"param_name"  	=> "filters_items_count",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Select', 'codevz' ) 					=> '',
						esc_html__( 'Above filters', 'codevz' ) 			=> 'cz_grid_filters_count_a',
						esc_html__( 'Above filters on hover', 'codevz' ) 	=> 'cz_grid_filters_count_ah',
						esc_html__( 'Inline beside filters', 'codevz' ) 	=> 'cz_grid_filters_count_i',
					),
					'group' 		=> esc_html__( 'Filter', 'codevz' ),
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_titles',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
					'group' 		=> esc_html__( 'Filter', 'codevz' )
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filters_con',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'border', 'padding' ),
					'group' 		=> esc_html__( 'Filter', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_con_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filters',
					"heading"     	=> esc_html__( "Filters", 'codevz'),
					'button' 		=> esc_html__( "Filters", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Filter', 'codevz' ),
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filters_separator',
					"heading"     	=> esc_html__( "Filters delimiter", 'codevz'),
					'button' 		=> esc_html__( "Filters delimiter", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Filter', 'codevz' ),
					'settings' 		=> array( 'content', 'color', 'font-size', 'margin' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_separator_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filter_active',
					"heading"     	=> esc_html__( "Active Filter", 'codevz'),
					'button' 		=> esc_html__( "Active Filter", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Filter', 'codevz' ),
					'settings' 		=> array( 'color', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filter_active_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filters_items_count',
					'hover_id' 		=> 'sk_filters_items_count_hover',
					"heading"     	=> esc_html__( "Filter items count", 'codevz'),
					'button' 		=> esc_html__( "Filter items count", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Filter', 'codevz' ),
					'settings' 		=> array( 'font-size', 'color', 'background', 'border', 'padding', 'margin' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_items_count_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_items_count_hover' ),

				// WP_Query
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Post type(s)', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'post_type',
					'std'			=> 'post',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__("Orderby", "codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "orderby",
					"value"			=> array(
						__("Date", "codevz")	=> 'date',
						__("ID", "codevz")		=> 'ID',
						__("Random", "codevz") => 'rand',
						__("Author", "codevz") => 'author',
						__("Title", "codevz")	=> 'title',
						__("Name", "codevz")	=> 'name',
						__("Type", "codevz")	=> 'type',
						__("Modified", "codevz") => 'modified',
						__("Parent ID", "codevz") => 'parent',
						__("Comment Count", "codevz") => 'comment_count',
					),
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__("Order", "codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "order",
					"value"			=> array(
						__("Descending", "codevz") => 'DESC',
						__("Ascending", "codevz") => 'ASC',
					),
					'group' 		=> esc_html__( 'Query', 'codevz' )
				), 
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__("Category Taxonomy", "codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "cat_tax",
					"value"			=> get_taxonomies(),
					"std"			=> 'category',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Category(s)', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'cat',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Exclude Category(s)', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'cat_exclude',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__("Tags Taxonomy", "codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "tag_tax",
					"value"			=> get_taxonomies(),
					"std"			=> 'post_tag',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Tag', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> false,
						'save_always'	=> true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'tag_id',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Exclude Tag', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> false,
						'save_always'	=> true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'tag_exclude',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__( 'Filter by posts', 'codevz' ),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'post__in',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__( 'Filter by authors', 'codevz' ),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'author__in',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__('Search keyword', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 's',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),

				// Carousel
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__('Slides to show', 'codevz'),
					'param_name'	=> 'slidestoshow',
					'value'			=> '3',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ),
					'edit_field_class' => 'vc_col-xs-99',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__('Slides to scroll', 'codevz'),
					'param_name'	=> 'slidestoscroll',
					'value'			=> '1',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ),
					'edit_field_class' => 'vc_col-xs-99',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__('Slides on Tablet', 'codevz'),
					'param_name'	=> 'slidestoshow_tablet',
					'value'			=> '2',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__('Slides on Mobile', 'codevz'),
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ),
					'param_name'	=> 'slidestoshow_mobile',
					'value'			=> '1',
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Infinite?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'infinite',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Auto play?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'autoplay',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__('Autoplay delay (ms)', 'codevz'),
					'param_name'	=> 'autoplayspeed',
					'value'			=> '4000',
					'options' 		=> array( 'unit' => '', 'step' => 500, 'min' => 1000, 'max' => 6000 ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
					'group' => esc_html__( 'Carousel', 'codevz' ),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Center mode?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'centermode',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__('Center padding', 'codevz'),
					'param_name'	=> 'centerpadding',
					'options' 		=> array( 'unit' => 'px', 'step' => 1, 'min' => 1, 'max' => 100 ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
					'group' => esc_html__( 'Carousel', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_slides',
					"heading"     	=> esc_html__( "Slides styling", 'codevz'),
					'button' 		=> esc_html__( "Slides", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'settings' 		=> array( 'grayscale', 'blur', 'background', 'opacity', 'z-index', 'padding', 'margin', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_slides_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_center',
					"heading"     	=> esc_html__( "Center slide styling", 'codevz'),
					'button' 		=> esc_html__( "Center slide", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'settings' 		=> array( 'grayscale', 'background', 'opacity', 'z-index', 'padding', 'margin', 'border', 'box-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_center_mobile' ),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title_arrows',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Arrows', 'codevz' ),
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Arrows position", 'codevz'),
					"param_name"  	=> "arrows_position",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'None', 'codevz' ) => 'no_arrows',
						esc_html__( 'Both top left', 'codevz' ) => 'arrows_tl',
						esc_html__( 'Both top center', 'codevz' ) => 'arrows_tc',
						esc_html__( 'Both top right', 'codevz' ) => 'arrows_tr',
						esc_html__( 'Top left / right', 'codevz' ) => 'arrows_tlr',
						esc_html__( 'Middle left / right', 'codevz' ) => 'arrows_mlr',
						esc_html__( 'Bottom left / right', 'codevz' ) => 'arrows_blr',
						esc_html__( 'Both bottom left', 'codevz' ) => 'arrows_bl',
						esc_html__( 'Both bottom center', 'codevz' ) => 'arrows_bc',
						esc_html__( 'Both bottom right', 'codevz' ) => 'arrows_br',
					),
					'std' => 'arrows_mlr',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Arrows inside carousel?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'arrows_inner',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Show on hover?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'arrows_show_on_hover',
					'default'		=> false,
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Previous icon", 'codevz'),
					"param_name"  	=> "prev_icon",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> 'fa fa-chevron-left',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Next icon", 'codevz'),
					"param_name"  	=> "next_icon",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> 'fa fa-chevron-right',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_prev_icon',
					'hover_id' 		=> 'sk_prev_icon_hover',
					"heading"     	=> esc_html__( "Previous icon styling", 'codevz'),
					'button' 		=> esc_html__( "Previous icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin', 'border' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_prev_icon_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_prev_icon_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_next_icon',
					'hover_id' 		=> 'sk_next_icon_hover',
					"heading"     	=> esc_html__( "Next icon styling", 'codevz'),
					'button' 		=> esc_html__( "Next icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin', 'border' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_next_icon_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_next_icon_hover' ),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title_dots',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Dots', 'codevz' ),
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Dots position", 'codevz'),
					"param_name"  	=> "dots_position",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'None', 'codevz' ) 					=> 'no_dots',
						esc_html__( 'Top left', 'codevz' ) 				=> 'dots_tl',
						esc_html__( 'Top center', 'codevz' ) 			=> 'dots_tc',
						esc_html__( 'Top right', 'codevz' ) 			=> 'dots_tr',
						esc_html__( 'Bottom left', 'codevz' ) 			=> 'dots_bl',
						esc_html__( 'Bottom center', 'codevz' ) 		=> 'dots_bc',
						esc_html__( 'Bottom right', 'codevz' ) 			=> 'dots_br',
						esc_html__( 'Vertical top left', 'codevz' ) 	=> 'dots_vtl',
						esc_html__( 'Vertical middle left', 'codevz' ) 	=> 'dots_vml',
						esc_html__( 'Vertical bottom left', 'codevz' ) 	=> 'dots_vbl',
						esc_html__( 'Vertical top right', 'codevz' ) 	=> 'dots_vtr',
						esc_html__( 'Vertical middle right', 'codevz' ) => 'dots_vmr',
						esc_html__( 'Vertical bottom right', 'codevz' ) => 'dots_vbr',
					),
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Predefined style", 'codevz'),
					"param_name"  	=> "dots_style",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( '~ Default ~', 'codevz' ) 		=> '',
						esc_html__( 'Circle', 'codevz' ) 		=> 'dots_circle',
						esc_html__( 'Circle 2', 'codevz' ) 		=> 'dots_circle dots_circle_2',
						esc_html__( 'Circle outline', 'codevz' ) => 'dots_circle_outline',
						esc_html__( 'Square', 'codevz' ) 		=> 'dots_square',
						esc_html__( 'Lozenge', 'codevz' ) 		=> 'dots_lozenge',
						esc_html__( 'Tiny line', 'codevz' ) 	=> 'dots_tiny_line',
						esc_html__( 'Drop', 'codevz' ) 			=> 'dots_drop',
					),
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Dots inside carousel?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'dots_inner',
					'default'		=> false,
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Show on hover?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'dots_show_on_hover',
					'default'		=> false,
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_dots_container',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'settings' 		=> array( 'color', 'background', 'padding', 'margin', 'border' ),
					'dependency'	=> array(
						'element'				=> 'dots_position',
						'value_not_equal_to'	=> array( 'no_dots' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_dots_container_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_dots',
					'hover_id' 		=> 'sk_dots_hover',
					"heading"     	=> esc_html__( "Dots styling", 'codevz'),
					'button' 		=> esc_html__( "Dots styling", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' => esc_html__( 'Carousel', 'codevz' ),
					'settings' 		=> array( 'color', 'background', 'padding', 'margin', 'border' ),
					'dependency'	=> array(
						'element'				=> 'dots_position',
						'value_not_equal_to'	=> array( 'no_dots' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_dots_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_dots_hover' ),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title_advanced_crousel',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Advanced', 'codevz' ),
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type'			=> 'checkbox',
					'heading'		=> esc_html__('Overflow visible?', 'codevz'),
					'param_name'	=> 'overflow_visible',
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Fade mode?', 'codevz'),
					'description' 	=> esc_html__('Only works when slide to show is 1', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'fade',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('MouseWheel?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'mousewheel',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Disable slides links?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'disable_links',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Auto width detection?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'variablewidth',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type'			=> 'checkbox',
					'heading'		=> esc_html__('Vertical?', 'codevz'),
					'param_name'	=> 'vertical',
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__('Number of rows', 'codevz'),
					'param_name'	=> 'rows',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 5 ),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Custom position', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'even_odd',
					'value'			=> array(
						'Select' 			=> '',
						'Even / Odd' 		=> 'even_odd',
						'Odd / Even' 		=> 'odd_even'
					),
					'group' 		=> esc_html__( 'Carousel', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'layout',
						'value'		=> array( 'cz_grid_carousel' )
					),
				),
				// Carousel
				
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
					'content' 		=> esc_html__( 'Hover cursor', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "attach_image",
					"heading"     	=> esc_html__( "Cursor image", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "cursor",
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Size & Position", 'codevz'),
					"param_name"  	=> "cursor_size",
					"edit_field_class" => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( '~ Default ~', 'codevz' ) 	=> '0',
						'32x32' 							=> '32',
						'36x36' 							=> '36',
						'48x48' 							=> '48',
						'64x64' 							=> '64',
						'80x80' 							=> '80',
						'128x128' 							=> '128',
					),
					'dependency'	=> array(
						'element' 		=> 'cursor',
						'not_empty'		=> true
					),
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
					'content' 		=> esc_html__( 'Extra Class', 'codevz' ),
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

		// Layout
		$layout = $atts['layout'];
		$carousel = Codevz_Plus::contains( $layout, 'carousel' );

		// List
		$is_list = 0;
		if ( Codevz_Plus::contains( $layout, 'cz_posts_list_' ) ) {
			$atts['hover'] = 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt';
			$is_list = 1;
		}

		// Image size
		if ( ! empty( $atts['custom_size'] ) ) {
			$image_size = $atts['custom_size'];
			$svg_sizes = array( '0', '0' );
		} else if ( Codevz_Plus::contains( $layout, 'masonry' ) || $layout === 'cz_justified' ) {
			$image_size = 'codevz_600_9999';
			$svg_sizes = array( '600', '600' );
		} else if ( Codevz_Plus::contains( $layout, 'cz_hr_grid' ) ) {
			$image_size = 'codevz_600_1000';
			$svg_sizes = array( '600', '1000' );
		} else if ( Codevz_Plus::contains( $layout, 'cz_grid_l' ) || $layout === 'cz_posts_list_4' ) {
			$image_size = 'codevz_1200_500';
			$svg_sizes = array( '1200', '500' );
		} else if ( Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_small_image' ) ) {
			$image_size = 'thumbnail';
			$svg_sizes = array( '80', '80' );
		} else if ( $is_list ) {
			$image_size = 'codevz_360_320';
			$svg_sizes = array( '360', '320' );
		} else {
			$image_size = 'codevz_600_600';
			$svg_sizes = array( '600', '600' );
		}

		$atts['image_size'] = $image_size;
		$atts['svg_sizes'] = $svg_sizes;

		// Fix gap
		$atts['gap'] = ( $atts['gap'] === '0' ) ? '0px' : $atts['gap'];

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];

			$css_array = array(
				'sk_container' 			=> 'div' . $css_id,
				'sk_overall' 			=> $css_id . ' .cz_grid_item > div',
				'sk_brfx' 				=> $css_id . ' .cz_grid_item > div:before',
				'sk_overall_hover' 		=> $css_id . ' .cz_grid_item > div:hover',
				'sk_img' 				=> $css_id . ' .cz_grid_link',
				'sk_img_hover' 			=> $css_id . ' .cz_grid_item:hover .cz_grid_link',
				'sk_overlay' 			=> $css_id . ' .cz_grid_link:before',
				'sk_overlay_hover' 		=> $css_id . ' .cz_grid_item:hover .cz_grid_link:before',
				'sk_filters_con' 		=> $css_id . ' .cz_grid_filters',
				'sk_filters' 			=> $css_id . ' .cz_grid_filters li',
				'sk_filter_active' 		=> $css_id . ' .cz_grid_filters .cz_active_filter',
				'sk_filters_separator' 	=> $css_id . ' .cz_grid_filters li:after',
				'sk_filters_items_count' => $css_id . ' .cz_grid_filters li span',
				'sk_filters_items_count_hover' => $css_id . ' .cz_grid_filters_count_a li span,' . $css_id . ' .cz_grid_filters_count li:hover span,' . $css_id . ' li.cz_active_filter span',
				'sk_icon' 				=> $css_id . ' .cz_grid_icon',
				'sk_icon_hover' 		=> $css_id . ' .cz_grid_item:hover .cz_grid_icon',
				'sk_content' 			=> $css_id . ' div > .cz_grid_details',
				'sk_content_hover' 		=> $css_id . ' .cz_grid_item:hover div > .cz_grid_details',
				'sk_title' 				=> $css_id . ' .cz_grid_details ' . esc_attr( $atts[ 'title_tag' ] ),
				'sk_title_hover' 		=> $css_id . ' .cz_grid_item:hover .cz_grid_details ' . esc_attr( $atts[ 'title_tag' ] ),
				'sk_meta' 				=> $css_id . ' .cz_grid_details small',
				'sk_meta_hover' 		=> $css_id . ' .cz_grid_item:hover .cz_grid_details small',
				'sk_meta_icons' 		=> $css_id . ' .cz_sub_icon',
				'sk_meta_icons_hover' 	=> $css_id . ' .cz_grid_item:hover .cz_sub_icon',
				'sk_excerpt' 			=> $css_id . ' .cz_post_excerpt',
				'sk_excerpt_hover' 		=> $css_id . ' .cz_grid_item:hover .cz_post_excerpt',
				'sk_readmore' 			=> $css_id . ' .cz_post_excerpt .cz_readmore',
				'sk_readmore_hover' 	=> $css_id . ' .cz_post_excerpt .cz_readmore:hover',
				'sk_load_more' 			=> $css_id . ' .cz_ajax_pagination a',
				'sk_load_more_hover' 	=> $css_id . ' .cz_ajax_pagination a:hover',
				'sk_load_more_active' 	=> $css_id . ' .cz_ajax_pagination .cz_ajax_loading',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			// Meta colors
			if ( Codevz_Plus::contains( $atts['sk_meta'], 'color:' ) ) {
				$css .= $css_id . ' .cz_grid_details small a {color:' . Codevz_Plus::get_string_between( $atts['sk_meta'], 'color:', ';' ) . '}';
			}

			// Gap
			if ( $atts['gap'] && ! $carousel ) {
				$gap = preg_split( '/(?<=[0-9])(?=[^0-9]+)/i', $atts['gap'] );
				$gap_int = ( (int) $gap[0] / 2 );
				$gap_unit = $gap[1];

				$css .= $css_id . '{margin-left: -' . $gap_int . $gap_unit . ';margin-right: -' . $gap_int . $gap_unit . ';margin-bottom: -' . $atts['gap'] . '}' . $css_id . ' .cz_grid_item > div{margin:0 ' . $gap_int . $gap_unit . ' ' . $atts['gap'] . '}';
			}

			// Cursor
			$css .= $atts['cursor'] ? $css_id . ' .cz_grid_link{cursor: url("' . Codevz_Plus::get_image( $atts['cursor'], ( $atts['cursor_size'] ? $atts['cursor_size'] . 'x'. $atts['cursor_size'] : 0 ), 1 ) . '") ' . ( $atts['cursor_size'] / 2 . ' ' . $atts['cursor_size'] / 2 ) . ', auto}' : '';

		} else {
			Codevz_Plus::load_font( $atts['sk_filters'] );
			Codevz_Plus::load_font( $atts['sk_title'] );
			Codevz_Plus::load_font( $atts['sk_meta'] );
			Codevz_Plus::load_font( $atts['sk_excerpt'] );
			Codevz_Plus::load_font( $atts['sk_load_more'] );
		}

		// Attributes
		$data = $atts['height'] ? ' data-height="' . $atts['height'] . '"' : '';
		$data .= $atts['gap'] ? ' data-gap="' . (int) $atts['gap'] . '"' : '';

		// Others var's
		$atts['post_class'] = 'cz_grid_item';
		$atts['post__in'] = $atts['post__in'] ? explode( ',', $atts['post__in'] ) : null;
		$atts['author__in'] = $atts['author__in'] ? explode( ',', $atts['author__in'] ) : null;

		// Tilt items
		$atts['tilt_data'] = Codevz_Plus::tilt( $atts );

		// Ajax data
		$ajax = array(
			'action'				=> 'cz_ajax_posts',
			'post_class'			=> $atts['post_class'],
			'post__in'				=> $atts['post__in'],
			'author__in'			=> $atts['author__in'],
			'nonce'					=> wp_create_nonce( $atts['id'] ),
			'nonce_id'				=> $atts['id'],
			'loadmore_end'			=> $atts['loadmore_end'],
			'layout'				=> $atts['layout'],
			'hover'					=> $atts['hover'],
			'image_size'			=> $image_size,
			'subtitles'				=> $atts['subtitles'],
			'subtitle_pos'			=> $atts['subtitle_pos'],
			'icon'					=> $atts['icon'],
			'el'					=> $atts['el'],
			'title_lenght'			=> $atts['title_lenght'],
			'cat_tax'				=> $atts['cat_tax'],
			'cat'					=> $atts['cat'],
			'cat_exclude'			=> $atts['cat_exclude'],
			'tag_tax'				=> $atts['tag_tax'],
			'tag_id'				=> $atts['tag_id'],
			'tag_exclude'			=> $atts['tag_exclude'],
			'post_type'				=> $atts['post_type'],
			'posts_per_page'		=> $atts['loadmore_lenght'] ? $atts['loadmore_lenght'] : $atts['posts_per_page'],
			'order'					=> $atts['order'],
			'orderby'				=> $atts['orderby'],
			'tilt_data'				=> $atts['tilt_data'],
			'svg_sizes' 			=> $atts['svg_sizes'],
			'img_fx' 				=> $atts['img_fx'],
			'custom_size' 			=> $atts['custom_size'],
			'excerpt_rm' 			=> $atts['excerpt_rm'],
			'title_tag' 			=> $atts[ 'title_tag' ]
		);

		// Search
		$atts['s'] = $ajax['s'] = isset( $_GET['s'] ) ? $_GET['s'] : $atts['s'];

		// Archive
		global $wp_query;
		$query_vars = isset( $wp_query->query_vars ) ? $wp_query->query_vars : 0;
		$query_vars = is_array( $query_vars ) ? $query_vars : 0;
		$is_query = ( ! is_singular() && $query_vars );
		if ( $is_query ) {
			$cpt = get_post_type();
			$query_vars['post_type'] = $cpt;

			if ( isset( $query_vars['taxonomy'] ) && Codevz_Plus::contains( $query_vars['taxonomy'], '_cat' ) ) {
				$atts['cat_tax'] = $ajax['cat_tax'] = $query_vars['taxonomy'];
				$term = get_term_by( 'slug', $query_vars['term'], $query_vars['taxonomy'] );
				$atts['cat'] = $ajax['cat'] = isset( $term->term_id ) ? $term->term_id : 0;
			} else if ( isset( $query_vars['taxonomy'] ) && Codevz_Plus::contains( $query_vars['taxonomy'], '_tags' ) ) {
				$atts['tag_tax'] = $ajax['tag_tax'] = $query_vars['taxonomy'];
				$term = get_term_by( 'slug', $query_vars['term'], $query_vars['taxonomy'] );
				$atts['tag_id'] = $ajax['tag_id'] = isset( $term->term_id ) ? $term->term_id : 0;
			}

			$ajax = wp_parse_args( array_filter( $query_vars ), $ajax );
		}

		// Ajax data
		$data .= " data-atts='" . json_encode( $ajax, JSON_HEX_APOS ) . "'";

		// Animation data
		$data .= $atts['animation'] ? ' data-animation="' . $atts['animation'] . '"' : '';

		// Out
		$out = '<div id="' . $atts['id'] . '" class="' . $atts['id'] . '"' . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>';

		// Filters
		if ( $atts['filters'] && ! $carousel ) {
			$atts['filters_pos'] .= $atts['filters_items_count'] ? ' cz_grid_filters_count ' . $atts['filters_items_count'] : '';
			$out .= '<ul class="cz_grid_filters clr ' . $atts['filters_pos'] . '">';
			$out .= $atts['browse_all'] ? '<li class="cz_active_filter" data-filter=".cz_grid_item">' . $atts['browse_all'] . '</li>' : '';
			$filters = explode( ',', str_replace( ' ', '', $atts['filters'] ) );

			foreach ( $filters as $filter ) {
				$cat = ( $atts['post_type'] === 'post' ) ? 'category' : $atts['post_type'] . '_cat';
				$tag = ( $atts['post_type'] === 'post' ) ? 'post_tag' : $atts['post_type'] . '_tags';

				if ( isset( $atts[ 'filters_tax' ] ) && $atts[ 'filters_tax' ] !== 'category' ) {
					$cat = $atts[ 'filters_tax' ];
					$tag = $atts[ 'filters_tax' ];
				}

				$term = get_term_by( 'id', $filter, $cat );
				$term = $term ? $term : get_term_by( 'id', $filter, $tag );

				if ( ! empty( $term->slug ) ) {
					$term_slug = Codevz_Plus::contains( $term->slug, '%d' ) ? $term->term_id : $term->slug;
				} else {
					$term_slug = '';
				}

				$out .= is_object( $term ) ? '<li data-filter=".' . $term->taxonomy . '-' . $term_slug . '">' . ucwords( $term->name ) . '</li>' : '';
			}
			$out .= '</ul>';
		}

		// Classes
		$classes = array();
		$classes[] = 'cz_grid cz_grid_1 clr';
		$classes[] = $layout;
		$classes[] = $atts['hover'];
		$classes[] = $atts['hover_pos'];
		$classes[] = $atts['hover_vis'];
		$classes[] = $atts['hover_fx'];
		$classes[] = $atts['overlay_outer_space'];
		$classes[] = $atts['subtitle_pos'];
		$classes[] = $atts['tilt_data'] ? 'cz_grid_tilt' : '';
		$classes[] = $atts['single_line_title'] ? 'cz_single_line_title' : '';
		$classes[] = $atts['two_columns_on_mobile'] ? 'cz_grid_two_columns_on_mobile' : '';
		$classes[] = Codevz_Plus::contains( $atts['sk_overlay'], 'border-color' ) ? 'cz_grid_overlay_border' : '';
		$classes[] = Codevz_Plus::contains( $atts['hover_pos'], 'tac' ) ? 'cz_meta_all_center' : '';

		// Posts
		$out .= '<div' . Codevz_Plus::classes( $atts, $classes ) . $data . '>';
		$out .= ( $layout !== 'cz_justified' ) ? '<div class="cz_grid_item cz_grid_first"></div>' : '';
		if ( $is_query ) {
			$atts['wp_query'] = 1;
			$atts = wp_parse_args( array_filter( $query_vars ), $atts );
		}

		$out .= self::get_posts( $atts );
		$out .= '</div>';

		// Ajax pagination
		if ( $atts['layout'] !== 'cz_grid_carousel' && $atts['loadmore'] && $atts['loadmore'] !== 'pagination' && $atts['loadmore'] !== 'older' ) {
			$out .= '<div class="cz_ajax_pagination clr cz_ajax_' . $atts['loadmore'] . ' ' . $atts['loadmore_pos'] . '"><a href="#">' . $atts['loadmore_title'] . '</a></div>';
		}

		$out .= '</div>'; // ID

		// Carousel mode
		if ( $carousel ) {

			$c = array();
			if ( $atts['slidestoshow'] ) { $c[] = 'slidestoshow="' . $atts['slidestoshow'] . '"'; }
			if ( $atts['slidestoshow_tablet'] ) { $c[] = 'slidestoshow_tablet="' . $atts['slidestoshow_tablet'] . '"'; }
			if ( $atts['slidestoshow_mobile'] ) { $c[] = 'slidestoshow_mobile="' . $atts['slidestoshow_mobile'] . '"'; }
			if ( $atts['slidestoscroll'] ) { $c[] = 'slidestoscroll="' . $atts['slidestoscroll'] . '"'; }
			$c[] = 'gap="' . ( $atts['gap'] ? $atts['gap'] : '10px' ) . '"';
			if ( $atts['infinite'] ) { $c[] = 'infinite="' . $atts['infinite'] . '"'; }
			if ( $atts['autoplay'] ) { $c[] = 'autoplay="' . $atts['autoplay'] . '"'; }
			if ( $atts['autoplayspeed'] ) { $c[] = 'autoplayspeed="' . $atts['autoplayspeed'] . '"'; }
			if ( $atts['centermode'] ) { $c[] = 'centermode="' . $atts['centermode'] . '"'; }
			if ( $atts['centerpadding'] ) { $c[] = 'centerpadding="' . $atts['centerpadding'] . '"'; }
			if ( $atts['sk_slides'] ) { $c[] = 'sk_slides="' . $atts['sk_slides'] . '"'; }
			if ( $atts['sk_slides_mobile'] ) { $c[] = 'sk_slides_mobile="' . $atts['sk_slides_mobile'] . '"'; }
			if ( $atts['sk_center'] ) { $c[] = 'sk_center="' . $atts['sk_center'] . '"'; }
			if ( $atts['sk_center_mobile'] ) { $c[] = 'sk_center_mobile="' . $atts['sk_center_mobile'] . '"'; }
			if ( $atts['arrows_position'] ) { $c[] = 'arrows_position="' . $atts['arrows_position'] . '"'; }
			if ( $atts['arrows_inner'] ) { $c[] = 'arrows_inner="' . $atts['arrows_inner'] . '"'; }
			if ( $atts['arrows_show_on_hover'] ) { $c[] = 'arrows_show_on_hover="' . $atts['arrows_show_on_hover'] . '"'; }
			if ( $atts['prev_icon'] ) { $c[] = 'prev_icon="' . $atts['prev_icon'] . '"'; }
			if ( $atts['next_icon'] ) { $c[] = 'next_icon="' . $atts['next_icon'] . '"'; }
			if ( $atts['sk_prev_icon'] ) { $c[] = 'sk_prev_icon="' . $atts['sk_prev_icon'] . '"'; }
			if ( $atts['sk_prev_icon_hover'] ) { $c[] = 'sk_prev_icon_hover="' . $atts['sk_prev_icon_hover'] . '"'; }
			if ( $atts['sk_prev_icon_mobile'] ) { $c[] = 'sk_prev_icon_mobile="' . $atts['sk_prev_icon_mobile'] . '"'; }
			if ( $atts['sk_next_icon'] ) { $c[] = 'sk_next_icon="' . $atts['sk_next_icon'] . '"'; }
			if ( $atts['sk_next_icon_hover'] ) { $c[] = 'sk_next_icon_hover="' . $atts['sk_next_icon_hover'] . '"'; }
			if ( $atts['sk_next_icon_mobile'] ) { $c[] = 'sk_next_icon_mobile="' . $atts['sk_next_icon_mobile'] . '"'; }
			if ( $atts['dots_position'] ) { $c[] = 'dots_position="' . $atts['dots_position'] . '"'; }
			if ( $atts['dots_style'] ) { $c[] = 'dots_style="' . $atts['dots_style'] . '"'; }
			if ( $atts['dots_inner'] ) { $c[] = 'dots_inner="' . $atts['dots_inner'] . '"'; }
			if ( $atts['dots_show_on_hover'] ) { $c[] = 'dots_show_on_hover="' . $atts['dots_show_on_hover'] . '"'; }

			if ( $atts['sk_dots_container'] ) { $c[] = 'sk_dots_container="' . $atts['sk_dots_container'] . '"'; }
			if ( $atts['sk_dots_container_mobile'] ) { $c[] = 'sk_dots_container_mobile="' . $atts['sk_dots_container_mobile'] . '"'; }

			if ( $atts['sk_dots'] ) { $c[] = 'sk_dots="' . $atts['sk_dots'] . '"'; }
			if ( $atts['sk_dots_hover'] ) { $c[] = 'sk_dots_hover="' . $atts['sk_dots_hover'] . '"'; }
			if ( $atts['sk_dots_mobile'] ) { $c[] = 'sk_dots_mobile="' . $atts['sk_dots_mobile'] . '"'; }

			if ( $atts['overflow_visible'] ) { $c[] = 'overflow_visible="' . $atts['overflow_visible'] . '"'; }
			if ( $atts['fade'] ) { $c[] = 'fade="' . $atts['fade'] . '"'; }
			if ( $atts['mousewheel'] ) { $c[] = 'mousewheel="' . $atts['mousewheel'] . '"'; }
			if ( $atts['disable_links'] ) { $c[] = 'disable_links="' . $atts['disable_links'] . '"'; }
			if ( $atts['variablewidth'] ) { $c[] = 'variablewidth="' . $atts['variablewidth'] . '"'; }
			if ( $atts['vertical'] ) { $c[] = 'vertical="' . $atts['vertical'] . '"'; }
			if ( $atts['rows'] ) { $c[] = 'rows="' . $atts['rows'] . '"'; }
			if ( $atts['even_odd'] ) { $c[] = 'even_odd="' . $atts['even_odd'] . '"'; }

			$out = do_shortcode( '[cz_carousel ' . implode( ' ', $c ) . ']' . $out . '[/cz_carousel]' );
		}

		return Codevz_Plus::_out( $atts, $out, array( 'grid( true )', 'tilt' ), $this->name, 'cz_gallery' );
	}

	/**
	 * Ajax query get posts
	 * @return string
	 */
	public static function get_posts( $atts = '', $out = '' ) {
		if ( ! empty( $_GET['nonce_id'] ) ) {
			check_ajax_referer( $_GET['nonce_id'], 'nonce' );
			$atts = $_GET;
		}

		// Tax query
		$tax_query = array();

		// Categories
		if ( $atts['cat'] && ! empty( $atts['cat_tax'] ) ) {
			$tax_query[] = array(
				'taxonomy'  => $atts['cat_tax'],
				'field'     => 'term_id',
				'terms'     => explode( ',', str_replace( ', ', ',', $atts['cat'] ) )
			);
		}

		// Exclude Categories
		if ( $atts['cat_exclude'] && ! empty( $atts['cat_tax'] ) ) {
			$tax_query[] = array(
				'taxonomy'  => $atts['cat_tax'],
				'field'     => 'term_id',
				'terms'     => explode( ',', str_replace( ', ', ',', $atts['cat_exclude'] ) ),
				'operator' 	=> 'NOT IN',
			);
		}

		// Tags
		if ( $atts['tag_id'] && ! empty( $atts['tag_tax'] ) ) {
			$tax_query[] = array(
				'taxonomy'  => $atts['tag_tax'],
				'field'     => 'term_id',
				'terms'     => explode( ',', str_replace( ', ', ',', $atts['tag_id'] ) )
			);
		}

		// Exclude Tags
		if ( $atts['tag_exclude'] && ! empty( $atts['tag_tax'] ) ) {
			$tax_query[] = array(
				'taxonomy'  => $atts['tag_tax'],
				'field'     => 'term_id',
				'terms'     => explode( ',', str_replace( ', ', ',', $atts['tag_exclude'] ) ),
				'operator' 	=> 'NOT IN',
			);
		}

		// Post types.
		$atts['post_type'] = $atts['post_type'] ? explode( ',', str_replace( ', ', ',', $atts['post_type'] ) ) : 'post';
		
		// Query args.
		$query = array(
			'post_type' 		=> $atts['post_type'],
			'post_status' 		=> 'publish',
			's' 				=> $atts['s'],
			'posts_per_page' 	=> $atts['posts_per_page'],
			'order' 			=> $atts['order'],
			'orderby' 			=> $atts['orderby'],
			'post__in' 			=> $atts['post__in'],
			'author__in' 		=> $atts['author__in'],
			'tax_query' 		=> $tax_query,
			'paged'				=> get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' )
		);

		// Exclude loaded IDs.
		if ( isset( $atts['ids'] ) && $atts['ids'] !== '0' ) {
			$query['post__not_in'] = explode( ',', $atts['ids'] );
		}

		if ( isset( $atts['category_name'] ) ) {
			$query['category_name'] = $atts['category_name'];
		}
		if ( isset( $atts['tag'] ) ) {
			$query['tag'] = $atts['tag'];
		}
		if ( isset( $atts['s'] ) ) {
			$query['s'] = $atts['s'];
		}

		// Anniversary posts on current day.
		if ( ! empty( $atts['class'] ) && Codevz_Plus::contains( $atts['class'], 'anniversary' ) ) {

			$current_timestamp = current_time( 'timestamp' );

			$query['date_query'] = array(
				'month' => date( 'm', $current_timestamp ),
				'day'   => date( 'j', $current_timestamp )
			);

		}

		// Generate query.
		$query = isset( $atts['wp_query'] ) ? $GLOBALS['wp_query'] : new WP_Query( $query );

		// Get default sizes before query
		$default_size = $atts['image_size'];
		$default_svg = $atts['svg_sizes'];

		// Loop
		if ( $query->have_posts() ) {
			$nn = 0;
			while ( $query->have_posts() ) {
				$query->the_post();

				$custom_class = '';
				if ( empty( $_GET['nonce_id'] ) && $atts['layout'] === 'cz_posts_list_5' && $nn === 0 ) {
					$custom_class .= ' cz_posts_list_first';
					$atts['image_size'] = 'codevz_1200_500';
					$atts['svg_sizes'] = array( 1200, 500 );
				} else {
					$atts['image_size'] = $default_size;
					$atts['svg_sizes'] = $default_svg;
				}

				// Var's
				$id = get_the_id();
				$thumb = Codevz_Plus::get_image( get_post_thumbnail_id( $id ), $atts['image_size'] );
				$issvg = $thumb ? '' : ' cz_grid_item_svg';
				$thumb = $thumb ? $thumb : '<img src="data:image/svg+xml,%3Csvg%20xmlns%3D&#39;http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg&#39;%20width=&#39;' . $atts['svg_sizes'][0] . '&#39;%20height=&#39;' . $atts['svg_sizes'][1] . '&#39;%20viewBox%3D&#39;0%200%20' . $atts['svg_sizes'][0] . '%20' . $atts['svg_sizes'][1] . '&#39;%2F%3E" alt="Placeholder" />';
				$no_link = ( Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_subtitle_on_img' ) || ! Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_title_sub_after' ) ) ? 1 : 0;
				$img_fx = empty( $atts['img_fx'] ) ? '' : ' ' . $atts['img_fx'];

				// Excerpt
				if ( $atts['el'] == '-1' ) {

					if ( Codevz_Plus::contains( $atts['hover'], 'excerpt' ) ) {

						$excerpt = '<div class="cz_post_excerpt cz_post_full_content">';

						ob_start();
						the_content();
						$excerpt .= ob_get_clean();

						$excerpt .= '</div>';

					}

				} else {

					if ( $atts['el'] && Codevz_Plus::option( 'post_excerpt' ) < $atts['el'] ) {
						add_action( 'excerpt_length', [ __CLASS__, 'excerpt_length' ], 999 );
					}

					$excerpt = Codevz_Plus::contains( $atts['hover'], 'excerpt' ) ? '<div class="cz_post_excerpt">' . Codevz_Plus::limit_words( get_the_excerpt(), $atts['el'], ( ! empty( $atts['excerpt_rm'] ) ? $atts['excerpt_rm'] : '' ) ) . '</div>' : '';

				}

				// Even & odd
				$custom_class .= ( $nn % 2 == 0 ) ? ' cz_posts_list_even' : ' cz_posts_list_odd';
				$nn++;

				// Template
				$out .= '<div data-id="' . get_the_ID() . '" class="' . $atts['post_class'] . ' ' . $custom_class . ' ' . implode( ' ', get_post_class() ) . '"><div class="clr">';

				$add_to_cart = Codevz_Plus::contains( $atts['subtitles'], 'add_to_cart' );

				$out .= '<a class="cz_grid_link' . $img_fx . $issvg . '" href="' . get_the_permalink() . '"' . $atts['tilt_data'] . '>';
				$out .= Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_no_image' ) ? '' : $thumb;

				if ( $add_to_cart ) {
					$out .= '</a>';
				}

				// Subtitle
				$subs = json_decode( urldecode( $atts[ 'subtitles' ] ), true );
				$subtitle = '';
				foreach ( (array) $subs as $i ) {
					if ( empty( $i['t'] ) ) {
						continue;
					}

					$i['p'] = isset( $i['p'] ) ? $i['p'] : '';
					$i['i'] = isset( $i['i'] ) ? $i['i'] : '';
					$i['tc'] = isset( $i['tc'] ) ? $i['tc'] : 10;
					$i['t'] .= empty( $i['r'] ) ? '' : ' ' . $i['r'];
					$i['ct'] = isset( $i['ct'] ) ? $i['ct'] : '';
					$i['cm'] = isset( $i['cm'] ) ? $i['cm'] : '';

					if ( Codevz_Plus::contains( $i['t'], 'author' ) ) {
						$subtitle .= Codevz_Plus::get_post_data( get_the_author_meta( 'ID' ), $i['t'], $no_link, $i['p'], $i['i'] );
					} else if ( $i['t'] === 'custom_text' || $i['t'] === 'readmore' ) {
						$subtitle .= Codevz_Plus::get_post_data( $id, $i['t'], $i['ct'], '', $i['i'], 0, $i );
					} else if ( $i['t'] === 'custom_meta' ) {
						$subtitle .= Codevz_Plus::get_post_data( $id, $i['t'], $i['cm'], '', $i['i'] );
					} else {
						$subtitle .= Codevz_Plus::get_post_data( $id, $i['t'], $no_link, $i['p'], $i['i'], $i['tc'] );
					}
				}

				// Subtitle b4 or after title
				$small_a = $small_b = $small_c = $det = '';
				if ( $subtitle ) {
					if ( $atts['subtitle_pos'] === 'cz_grid_1_title_rev' ) {
						$small_a = '<small class="clr">' . $subtitle . '</small>';
					} else if ( $atts['subtitle_pos'] === 'cz_grid_1_sub_after_ex' ) {
						$small_c = '<small class="clr">' . $subtitle . '</small>';
					} else {
						$small_b = '<small class="clr">' . $subtitle . '</small>';
					}
				}

				// Post title
				$post_title = $atts['title_lenght'] ? Codevz_Plus::limit_words( get_the_title(), $atts['title_lenght'], '' ) : get_the_title();
				$post_title = '<' . esc_attr( $atts[ 'title_tag' ] ) . '>' . $post_title . '</' . esc_attr( $atts[ 'title_tag' ] ) . '>';

				// Details after title
				if ( Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_title_sub_after' ) ) {

					if ( Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_subtitle_on_img' ) ) {
						$out .= '<div class="cz_grid_details">' . $small_a . $small_b . $small_c . '</div>';
						$small_a = $small_b = $small_c = '';
					} else {
						$out .= '<div class="cz_grid_details"><i class="' . $atts['icon'] . ' cz_grid_icon"></i></div>';
					}

					$det = '<div class="cz_grid_details cz_grid_details_outside">' . $small_a . '<a class="cz_grid_title" href="' . get_the_permalink() . '">' . $post_title . '</a>' . $small_b . $excerpt . $small_c . '</div>';
				} else {
					$out .= '<div class="cz_grid_details"><i class="' . $atts['icon'] . ' cz_grid_icon"></i>' . $small_a . $post_title . $small_b . $excerpt . $small_c . '</div>';
				}
				
				if ( ! $add_to_cart ) {
					$out .= '</a>';
				}

				$out .= isset( $det ) ? $det : '';
				$out .= '</div></div>';
			}
		}

		$atts['loadmore'] = isset( $atts['loadmore'] ) ? $atts['loadmore'] : 0;

		if ( $atts['loadmore'] === 'pagination' ) {
			ob_start();
			$total = $GLOBALS['wp_query']->max_num_pages;
			$GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;

			if ( isset( $GLOBALS['wp_query']->query['paged'] ) ) {
				$current = $GLOBALS['wp_query']->query['paged'];
			//} else if ( isset( $GLOBALS['wp_query']->query['page'] ) ) {
			//	$current = $GLOBALS['wp_query']->query['page'];
			} else {
				$current = 1;
			}

			the_posts_pagination(
				[
					'current'			 => $current,
					'prev_text'          => Codevz_Plus::$is_rtl ? '<i class="fa fa-angle-double-right mr4"></i>' : '<i class="fa fa-angle-double-left mr4"></i>',
					'next_text'          => Codevz_Plus::$is_rtl ? '<i class="fa fa-angle-double-left ml4"></i>' : '<i class="fa fa-angle-double-right ml4"></i>',
					'before_page_number' => ''
				]
			);
			
			$GLOBALS['wp_query']->max_num_pages = $total;
			$out .= '<div class="tac mt40 cz_no_grid">' . ob_get_clean() . '</div>';
		} else if ( $atts['loadmore'] === 'older' ) {
			ob_start();
			$total = $GLOBALS['wp_query']->max_num_pages;
			$GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;
			previous_posts_link();
			next_posts_link();
			$GLOBALS['wp_query']->max_num_pages = $total;
			$out .= '<div class="tac mt40 pagination pagination_old cz_no_grid">' . ob_get_clean() . '</div>';
		}

		// Reset query/postdata
		wp_reset_postdata();
		wp_reset_query();

		// Out
		if ( ! empty( $_GET['nonce_id'] ) ) {
			wp_die( $out );
		} else {
			return $out;
		}
	}

	// Fix custom excerpt length
	public static function excerpt_length() {
		return 99;
	}
}