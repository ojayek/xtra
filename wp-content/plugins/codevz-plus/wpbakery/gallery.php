<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Cannot access pages directly.

/**
 * Gallery & Instagram
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_gallery {

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
			'name'			=> esc_html__( 'Gallery', 'codevz' ),
			'description'	=> esc_html__( 'Unlimited gallery styles', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Gallery type", 'codevz'),
					"param_name"  	=> 'type',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'Photo Gallery', 'codevz' ) 	=> 'gallery',
						esc_html__( 'Linkable Gallery', 'codevz' ) 	=> 'gallery2',
						esc_html__( 'Instagram', 'codevz' ) . '  ' . esc_html__( '[Deprecated]', 'codevz' ) => 'instagram',
					),
					'std'			=> 'gallery',
					'admin_label' 	=> true
				),
				array(
					'type' 			=> 'param_group',
					'heading' 		=> esc_html__( 'Add images', 'codevz' ),
					'param_name' 	=> 'gallery2',
					'params' 		=> array(
						array(
							"type"        	=> "attach_image",
							"heading"     	=> esc_html__( "Image", 'codevz' ),
							"param_name"  	=> "image"
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__( "Title", 'codevz' ),
							'edit_field_class' => 'vc_col-xs-6',
							"param_name"  	=> "title",
							'admin_label'	=> true
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__( "Description", 'codevz' ),
							'edit_field_class' => 'vc_col-xs-6',
							"param_name"  	=> "info"
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__("Link", 'codevz'),
							"description"   => esc_html__("For opening in lightbox use #", 'codevz'),
							'edit_field_class' => 'vc_col-xs-6',
							"param_name"  	=> "link"
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__( "Filter(s)", 'codevz'),
							"description"   => "e.g. business,art,news",
							'edit_field_class' => 'vc_col-xs-6',
							"param_name"  	=> "class"
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__( "Badge", 'codevz'),
							'edit_field_class' => 'vc_col-xs-6',
							"param_name"  	=> "badge"
						),
						array(
							'type' 			=> 'cz_sk',
							'param_name' 	=> 'sk_badge',
							"heading"     	=> esc_html__( "Badge styling", 'codevz'),
							'button' 		=> esc_html__( "Badge", 'codevz'),
							'edit_field_class' => 'vc_col-xs-6',
							'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin' )
						),
					),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( 'gallery2' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__( "Click mode", 'codevz' ),
					"param_name"  	=> 'target',
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'Open in new tab', 'codevz' )  => '',
						esc_html__( 'Open in same tab', 'codevz' ) => '1'
					),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( 'gallery2', 'instagram' )
					),
				),
				array(
					"type"        	=> "attach_images",
					"heading"     	=> esc_html__("Images", 'codevz'),
					"param_name"  	=> "images",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( 'gallery' )
					),
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Username or Hashtag", 'codevz'),
					"description"   => esc_html__("For hashtag # is required before word", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> 'xtratheme',
					"param_name"  	=> "insta_username",
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( 'instagram' )
					),
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Count", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> '6',
					"param_name"  	=> "insta_count",
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 12 ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( 'instagram' )
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Update cache', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'insta_update',
					'value'			=> array(
						'12 ' . esc_html__('Hours', 'codevz') 		=> '12',
						'24 ' . esc_html__('Hours', 'codevz') 		=> '24',
						'36 ' . esc_html__('Hours', 'codevz') 		=> '36',
						'48 ' . esc_html__('Hours', 'codevz') 		=> '48',
						'72 ' . esc_html__('Hours', 'codevz') 		=> '72',
						'96 ' . esc_html__('Hours', 'codevz') 		=> '96',
						'120 ' . esc_html__('Hours', 'codevz') 		=> '120',
						esc_html__( 'Store data once', 'codevz' ) 	=> '18000',
					),
					'std'			=> '72',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( 'instagram' )
					),
				),
				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__('Images size', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'insta_size',
					'value'			=> array(
						esc_html__('Thumbnail', 'codevz') 	=> 'thumbnail',
						esc_html__('Medium', 'codevz') 		=> 'large',
						esc_html__('Large', 'codevz') 		=> 'original',
					),
					'std'			=> 'large',
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( 'instagram' )
					),
				),
				array(
					"type"        	=> "cz_image_select",
					"heading"     	=> esc_html__('Layout', 'codevz'),
					"param_name"  	=> "layout",
					'edit_field_class' => 'vc_col-xs-99',
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
					),
					'std'			=> 'cz_grid_c4'
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Custom size", 'codevz'),
					"description"   => esc_html__('Enter image size (e.g: "thumbnail", "medium", "large", "full"), Alternatively enter size in pixels (e.g: 200x100 (Width x Height)).', 'codevz'),
					"param_name"  	=> "custom_size",
					"edit_field_class" => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'			=> 'type',
						'value_not_equal_to'=> array( 'instagram' )
					),
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Two columns on mobile?", 'codevz'),
					"param_name"  	=> "two_columns_on_mobile",
					"edit_field_class" => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'			=> 'type',
						'value_not_equal_to'=> array( 'instagram' )
					),
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Images gap", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "gap",
					'admin_label' 	=> true,
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__( "Ideal height", 'codevz' ),
					"description"   => esc_html__( "Only works for gallery layout 1", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 10, 'min' => 80, 'max' => 700 ),
					"param_name"  	=> "height",
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
					'dependency'	=> array(
						'element'		=> 'layout',
						'value'			=> array( 'cz_justified' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Hover style", 'codevz'),
					"param_name"  	=> "hover",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'No hover', 'codevz') 										=> 'cz_grid_1_no_hover',
						esc_html__( 'Overlay only icon', 'codevz') 								=> 'cz_grid_1_no_title',
						esc_html__( 'Overlay icon and title', 'codevz') 						=> 'cz_grid_1_no_desc',
						esc_html__( 'Overlay icon, title and description', 'codevz') 				=> 'cz_grid_1_yes_all',
						esc_html__( 'Overlay icon and description', 'codevz') 						=> 'cz_grid_1_no_title cz_grid_1_w_info',
						esc_html__( 'Overlay title', 'codevz') 									=> 'cz_grid_1_no_icon cz_grid_1_no_desc',
						esc_html__( 'Overlay description', 'codevz') 								=> 'cz_grid_1_w_info cz_grid_1_no_icon cz_grid_1_no_title ',
						esc_html__( 'Overlay title and description', 'codevz') 						=> 'cz_grid_1_no_icon',
						esc_html__( 'No hover, title and description after image', 'codevz') 		=> 'cz_grid_1_title_sub_after cz_grid_1_no_hover',
						esc_html__( 'Overlay icon - title and description after image', 'codevz')	=> 'cz_grid_1_title_sub_after',
					),
					'std'			=> 'cz_grid_1_no_title',
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
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
					),
					'std'			=> 'h3',
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__('Disable links?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'grid_disable_links',
					'group' 		=> esc_html__( 'Settings', 'codevz' )
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
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_brfx',
					"heading"     	=> esc_html__( "Block Reveal", 'codevz'),
					'button' 		=> esc_html__( "Block Reveal", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99 hidden',
					'group' 	=> esc_html__( 'Settings', 'codevz' ),
					'settings' 		=> array( 'background' ),
					'dependency'	=> array(
						'element'		=> 'animation',
						'value'			=> array( 'cz_grid_brfx_right', 'cz_grid_brfx_left', 'cz_grid_brfx_up', 'cz_grid_brfx_down' )
					),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Description position?", 'codevz'),
					"param_name"  	=> "subtitle_pos",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'Description after title', 'codevz') 		=> '',
						esc_html__( 'Description before title', 'codevz') 		=> 'cz_grid_1_title_rev',
					),
					'dependency'	=> array(
						'element'		=> 'hover',
						'value'			=> array( 'cz_grid_1_yes_all', 'cz_grid_1_no_title cz_grid_1_w_info', 'cz_grid_1_title_sub_after', 'cz_grid_1_title_sub_after cz_grid_1_no_hover', 'cz_grid_1_no_icon', 'cz_grid_1_w_info cz_grid_1_no_icon cz_grid_1_no_title ' )
					),
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Details align", 'codevz'),
					"param_name"  	=> "hover_pos",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'Top Left', 'codevz') 		=> 'cz_grid_1_top tal',
						esc_html__( 'Top Center', 'codevz') 	=> 'cz_grid_1_top tac',
						esc_html__( 'Top Right', 'codevz') 		=> 'cz_grid_1_top tar',
						esc_html__( 'Middle Left', 'codevz') 	=> 'cz_grid_1_mid tal',
						esc_html__( 'Middle Center', 'codevz')  => 'cz_grid_1_mid tac',
						esc_html__( 'Middle Right', 'codevz') 	=> 'cz_grid_1_mid tar',
						esc_html__( 'Bottom Left', 'codevz') 	=> 'cz_grid_1_bot tal',
						esc_html__( 'Bottom Center', 'codevz')  => 'cz_grid_1_bot tac',
						esc_html__( 'Bottom Right', 'codevz') 	=> 'cz_grid_1_bot tar',
					),
					'std'			=> 'cz_grid_1_mid tac',
					'dependency'	=> array(
						'element'			=> 'hover',
						'value_not_equal_to'=> array( 'cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_hover' )
					),
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Hover visibility", 'codevz'),
					"param_name"  	=> "hover_vis",
					'edit_field_class' => 'vc_col-xs-99',
					'value'			=> array(
						esc_html__( 'Show overlay on hover', 'codevz' ) 		=> '',
						esc_html__( 'Hide overlay on hover', 'codevz' ) 		=> 'cz_grid_1_hide_on_hover',
						esc_html__( 'Always show overlay details', 'codevz' )	=> 'cz_grid_1_always_show',
					),
					'dependency'	=> array(
						'element'			=> 'hover',
						'value_not_equal_to'=> array( 'cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_hover' )
					),
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Hover effect", 'codevz'),
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
						'value_not_equal_to'=> array( 'cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_hover' )
					),
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Hover image effect", 'codevz'),
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
						esc_html__( 'Rhombus', 'codevz') 			=> 'cz_grid_rhombus',
						esc_html__( 'Rhombus on hover', 'codevz')   => 'cz_grid_rhombus_hover',
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
						'value_not_equal_to'=> array( 'cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_hover' )
					),
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					"type"        	=> "cz_icon",
					"heading"     	=> esc_html__("Icon", 'codevz'),
					"param_name"  	=> "icon",
					"value"  		=> "fa fa-search",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'	=> 'hover',
						'value'		=> array( 'cz_grid_1_no_title', 'cz_grid_1_no_desc', 'cz_grid_1_yes_all', 'cz_grid_1_title_sub_after', 'cz_grid_1_no_title cz_grid_1_w_info' )
					),
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),
				array(
					'type'			=> 'cz_slider',
					'heading'		=> esc_html__('Limit title words', 'codevz'),
					'param_name'	=> 'title_limit',
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 100 ),
					'dependency'	=> array(
						'element'			=> 'hover',
						'value_not_equal_to'=> array( 'cz_grid_1_no_hover', 'cz_grid_1_no_title', 'cz_grid_1_no_title cz_grid_1_w_info', 'cz_grid_1_w_info cz_grid_1_no_icon cz_grid_1_no_title' )
					),
					'group' 		=> esc_html__( 'Settings', 'codevz' ),
				),

				// Styling
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_con',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border', 'box-shadow' ),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_overall',
					'hover_id' 		=> 'sk_overall_hover',
					"heading"     	=> esc_html__( "Gallery items", 'codevz'),
					'button' 		=> esc_html__( "Gallery items", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border', 'box-shadow' ),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overall_hover' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_img',
					'hover_id' 		=> 'sk_img_hover',
					"heading"     	=> esc_html__( "Images", 'codevz'),
					'button' 		=> esc_html__( "Images", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_img_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_img_hover' ),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Overlay scale', 'codevz' ),
					'param_name' => 'overlay_outer_space',
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						'Default'	=> '',
						'#1'		=> 'cz_grid_overlay_5px',
						'#2'		=> 'cz_grid_overlay_10px',
						'#3'		=> 'cz_grid_overlay_15px',
						'#4'		=> 'cz_grid_overlay_20px',
					),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_overlay',
					'hover_id'	 	=> 'sk_overlay_hover',
					"heading"     	=> esc_html__( "Overlay", 'codevz'),
					'button' 		=> esc_html__( "Overlay", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'border' ),
					'dependency'	=> array(
						'element'			=> 'hover',
						'value_not_equal_to'=> array( 'cz_grid_1_no_hover', 'cz_grid_1_title_sub_after cz_grid_1_no_hover' )
					),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overlay_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_overlay_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_icon',
					'hover_id'	 	=> 'sk_icon_hover',
					"heading"     	=> esc_html__( "Icon", 'codevz'),
					'button' 		=> esc_html__( "Icon", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'border' ),
					'dependency'	=> array(
						'element'	=> 'hover',
						'value'		=> array( 'cz_grid_1_no_title', 'cz_grid_1_no_desc', 'cz_grid_1_yes_all', 'cz_grid_1_title_sub_after', 'cz_grid_1_no_title cz_grid_1_w_info' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_icon_hover' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_title',
					'hover_id'	 	=> 'sk_title_hover',
					"heading"     	=> esc_html__( "Title", 'codevz'),
					'button' 		=> esc_html__( "Title", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
					'settings' 		=> array( 'color', 'font-family', 'font-size', 'background', 'padding' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_title_hover' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_subtitle',
					'hover_id'	 	=> 'sk_subtitle_hover',
					"heading"     	=> esc_html__( "Description", 'codevz'),
					'button' 		=> esc_html__( "Description", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
					'settings' 		=> array( 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'text-transform', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ),
					'dependency'	=> array(
						'element'	=> 'hover',
						'value'			=> array( 'cz_grid_1_yes_all', 'cz_grid_1_no_title cz_grid_1_w_info', 'cz_grid_1_title_sub_after', 'cz_grid_1_title_sub_after cz_grid_1_no_hover', 'cz_grid_1_no_icon', 'cz_grid_1_w_info cz_grid_1_no_icon cz_grid_1_no_title ' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_subtitle_hover' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_badge',
					'hover_id'	 	=> 'sk_badge_hover',
					"heading"     	=> esc_html__( "All badges", 'codevz'),
					'button' 		=> esc_html__( "Badge", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
					'settings' 		=> array( 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'text-transform', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ),
					'dependency'	=> array(
						'element'		=> 'type',
						'value'			=> array( 'gallery2' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_badge_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_badge_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_badge_hover' ),

				// Filter
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Position", 'codevz'),
					"param_name"  	=> "filters_pos",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Select', 'codevz' ) 	=> '',
						esc_html__( 'None', 'codevz' ) 		=> 'hidden',
						esc_html__( 'Left', 'codevz' ) 		=> 'tal',
						esc_html__( 'Center', 'codevz' ) 	=> 'tac',
						esc_html__( 'Right', 'codevz' ) 	=> 'tar',
					),
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__('Show all', 'codevz'),
					"value"   		=> 'Show All',
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'browse_all',
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
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
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filters_con',
					"heading"     	=> esc_html__( "Container styling", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ),
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_con_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_con_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filters',
					"heading"     	=> esc_html__( "Filters styling", 'codevz'),
					'button' 		=> esc_html__( "Filters", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ),
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filter_active',
					"heading"     	=> esc_html__( "Active filter", 'codevz'),
					'button' 		=> esc_html__( "Active filter", 'codevz'),
					'group' 			=> esc_html__( 'Filter & Search', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
					'settings' 		=> array( 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filter_active_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filter_active_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filters_sep',
					"heading"     	=> esc_html__( "Filters delimiter", 'codevz'),
					'button' 		=> esc_html__( "Filters delimiter", 'codevz'),
					'group' 			=> esc_html__( 'Filter & Search', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
					'settings' 		=> array( 'color', 'content', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_sep_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_sep_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_filters_items_count',
					'hover_id' 		=> 'sk_filters_items_count_hover',
					"heading"     	=> esc_html__( "Filter items count", 'codevz'),
					'button' 		=> esc_html__( "Filter items count", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'settings' 		=> array( 'font-size', 'color', 'background', 'border', 'padding', 'margin' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_items_count_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_items_count_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_filters_items_count_hover' ),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title_search',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Search input', 'codevz' ),
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Search input", 'codevz'),
					"param_name"  	=> "search",
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Search placeholder", 'codevz'),
					"param_name"  	=> "search_placeholder",
					'edit_field_class' => 'vc_col-xs-99',
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_search',
					"heading"     	=> esc_html__( "Search styling", 'codevz'),
					'button' 		=> esc_html__( "Search", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'padding', 'margin', 'border', 'box-shadow' ),
					'group' 		=> esc_html__( 'Filter & Search', 'codevz' ),
					'dependency'	=> array(
						'element'	=> 'type',
						'value'		=> array( 'gallery2' )
					),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_search_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_search_mobile' ),

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
				array( 'type' => 'cz_hidden','param_name' => 'sk_prev_icon_tablet' ),
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
				array( 'type' => 'cz_hidden','param_name' => 'sk_next_icon_tablet' ),
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
						'element'		=> 'layout',
						'value'			=> array( 'cz_grid_carousel' )
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
						'element'		=> 'layout',
						'value'			=> array( 'cz_grid_carousel' )
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
					'content' 		=> esc_html__( 'Cursor', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "attach_image",
					"heading"     	=> esc_html__( "Cursor", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "cursor",
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__("Size", 'codevz'),
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
					'content' 		=> esc_html__( 'Extra Class', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__( "Extra Class", 'codevz' ),
					"param_name"  	=> "class",
					"edit_field_class" => 'vc_col-xs-99',
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

		// Image size
		if ( ! empty( $atts['custom_size'] ) ) {
			$image_size = $atts['custom_size'];
		} else if ( Codevz_Plus::contains( $layout, 'masonry' ) || $layout === 'cz_justified' ) {
			$image_size = 'codevz_600_9999';
		} else if ( Codevz_Plus::contains( $layout, 'cz_hr_grid' ) ) {
			$image_size = 'codevz_600_1000';
		} else if ( Codevz_Plus::contains( $layout, 'cz_grid_l' ) || Codevz_Plus::contains( $layout, 'cz_grid_l2' ) ) {
			$image_size = 'codevz_1200_500';
		} else if ( Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_small_image' ) ) {
			$image_size = 'thumbnail';
		} else {
			$image_size = 'codevz_600_600';
		}
		$atts['image_size'] = $image_size;

		// Fix gap
		$atts['gap'] = ( $atts['gap'] === '0' ) ? '0px' : $atts['gap'];

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];

			$css_array = array(
				'sk_con' 			=> 'div' . $css_id,
				'sk_overall' 		=> $css_id . ' .cz_grid_item > div',
				'sk_brfx' 			=> $css_id . ' .cz_grid_item > div:before',
				'sk_overall_hover' 	=> $css_id . ' .cz_grid_item > div:hover',
				'sk_img' 			=> $css_id . ' .cz_grid_link',
				'sk_img_hover' 		=> $css_id . ' .cz_grid_item:hover .cz_grid_link',
				'sk_overlay' 		=> $css_id . ' .cz_grid_link:before',
				'sk_overlay_hover' 	=> $css_id . ' .cz_grid_item:hover .cz_grid_link:before',
				'sk_filters_con' 	=> $css_id . ' .cz_grid_filters',
				'sk_search' 		=> $css_id . ' .cz_grid_search',
				'sk_filters' 		=> $css_id . ' .cz_grid_filters li',
				'sk_filter_active' 	=> $css_id . ' .cz_grid_filters .cz_active_filter',
				'sk_filters_sep' 	=> $css_id . ' .cz_grid_filters li:after',
				'sk_filters_items_count' => $css_id . ' .cz_grid_filters li span',
				'sk_filters_items_count_hover' => $css_id . ' .cz_grid_filters_count_a li span,' . $css_id . ' .cz_grid_filters_count li:hover span,' . $css_id . ' li.cz_active_filter span',
				'sk_icon' 				=> $css_id . ' .cz_grid_icon',
				'sk_icon_hover' 	=> $css_id . ' .cz_grid_item:hover .cz_grid_icon',
				'sk_title' 			=> $css_id . ' .cz_grid_details ' . esc_attr( $atts[ 'title_tag' ] ),
				'sk_title_hover' 	=> $css_id . ' .cz_grid_item:hover .cz_grid_details ' . esc_attr( $atts[ 'title_tag' ] ),
				'sk_subtitle' 		=> $css_id . ' .cz_grid_details small',
				'sk_subtitle_hover' => $css_id . ' .cz_grid_item:hover .cz_grid_details small',
				'sk_badge' 			=> $css_id . ' .cz_gallery_badge',
				'sk_badge_hover' 	=> $css_id . ':hover .cz_gallery_badge',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			// Caption colors
			$icl = Codevz_Plus::get_string_between( $atts['sk_subtitle'], ';color:', ';' );
			$css .= $icl ? $css_id . ' .cz_grid_details small a{color:' . $icl . '}' : '';

			// Gap
			if ( $atts['gap'] && ! $carousel ) {
				$gap = preg_split( '/(?<=[0-9])(?=[^0-9]+)/i', $atts['gap'] );
				$gap_int = ( (int) $gap[0] / 2 );
				$gap_unit = $gap[1];

				//$css .= $css_id . '{margin: 0 -' . $gap_int . $gap_unit . '}' . $css_id . ' .cz_grid_item > div{margin:0 ' . $gap_int . $gap_unit . ' ' . $atts['gap'] . '}';
				$css .= $css_id . '{margin-left: -' . $gap_int . $gap_unit . ';margin-right: -' . $gap_int . $gap_unit . ';margin-bottom: -' . $atts['gap'] . '}' . $css_id . ' .cz_grid_item > div{margin:0 ' . $gap_int . $gap_unit . ' ' . $atts['gap'] . '}';
			}

			// Cursor
			$css .= $atts['cursor'] ? $css_id . ' .cz_grid_link{cursor: url("' . Codevz_Plus::get_image( $atts['cursor'], ( $atts['cursor_size'] ? $atts['cursor_size'] . 'x'. $atts['cursor_size'] : 0 ), 1 ) . '") ' . ( $atts['cursor_size'] / 2 . ' ' . $atts['cursor_size'] / 2 ) . ', auto}' : '';

		} else {
			Codevz_Plus::load_font( $atts['sk_filters'] );
			Codevz_Plus::load_font( $atts['sk_filter_active'] );
			Codevz_Plus::load_font( $atts['sk_title'] );
			Codevz_Plus::load_font( $atts['sk_subtitle'] );
		}

		// Attributes
		$data = $atts['height'] ? ' data-height="' . $atts['height'] . '"' : '';
		$data .= $atts['gap'] ? ' data-gap="' . (int) $atts['gap'] . '"' : '';

		// Animation data
		$data .= ( $atts['animation'] && ! Codevz_Plus::contains( $atts['layout'], 'carousel' ) ) ? ' data-animation="' . $atts['animation'] . '"' : '';

		// Out
		$out = '<div id="' . $atts['id'] . '" class="cz_grid_p ' . $atts['id'] . '"' . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>';

		// Tilt items
		$atts['tilt_data'] = Codevz_Plus::tilt( $atts );

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
		$classes[] = $atts['two_columns_on_mobile'] ? 'cz_grid_two_columns_on_mobile' : '';
		$classes[] = $atts['grid_disable_links'] ? 'cz_grid_disable_links' : '';
		$classes[] = Codevz_Plus::contains( $atts['sk_overlay'], 'border-color' ) ? 'cz_grid_overlay_border' : '';

		// Gallery 2 foreach
		if ( $atts['type'] === 'gallery2' ) {
			$gallery2_out = '';
			$filters = array();
			$gallery2 = json_decode( urldecode( $atts[ 'gallery2' ] ), true );
			foreach ( $gallery2 as $i ) {
				$cls = 'cz_gallery2';
				if ( ! empty( $i['class'] ) ) {
					$fils = (array) explode( ',', $i['class'] );
					foreach ( $fils as $v ) {
						$v = str_replace( ' ', '-', $v );
						if ( ! isset( $filters[ $v ] ) ) {
							$filters[ $v ] = $v;
						}
						$cls .= ' ' . $v;
					}
				}
				$i['image'] = isset( $i['image'] ) ? $i['image'] : '';
				$badge = isset( $i['badge'] ) ? $i['badge'] : '';
				$sk_badge = isset( $i['sk_badge'] ) ? $i['sk_badge'] : '';
				$link = isset( $i['link'] ) ? $i['link'] : '';
				$link = ( ! $link || $link === '#' ) ? Codevz_Plus::get_image( $i['image'], 0, 1 ) : $link;

				$gallery2_out .= self::get_gallery_item(
					Codevz_Plus::get_image( $i['image'], $image_size ), 
					$link, 
					Codevz_Plus::limit_words( ( isset( $i['title'] ) ? $i['title'] : '' ), ( ! empty( $atts['title_limit'] ) ? $atts['title_limit'] : 999 ) ), 
					( isset( $i['info'] ) ? $i['info'] : '' ), 
					$atts, $cls, $atts['img_fx'], $badge, $sk_badge
				);
			}

			// Filters
			if ( ! empty( $filters ) && ! $carousel ) {
				$atts['filters_pos'] .= $atts['filters_items_count'] ? ' cz_grid_filters_count ' . $atts['filters_items_count'] : '';
				$out .= '<ul class="cz_grid_filters clr ' . $atts['filters_pos'] . '">';
				$out .= $atts['browse_all'] ? '<li class="cz_active_filter" data-filter=".cz_grid_item">' . $atts['browse_all'] . '</li>' : '';
				foreach ( $filters as $a => $b ) {
					$out .= '<li data-filter=".' . $b . '">' . ucfirst( str_replace( array( '_', '-' ), ' ', $b ) ) . '</li>';
				}
				$out .= '</ul>';
			}
		}

		// Search data
		$data .= $atts['search'] ? ' data-search="' . $atts['search_placeholder'] . '"' : '';

		// Items
		$out .= '<div' . Codevz_Plus::classes( $atts, $classes ) . $data . '>';
		$out .= ( $layout !== 'cz_justified' ) ? '<div class="cz_grid_item cz_grid_first"></div>' : '';

		if ( $atts['type'] === 'instagram' ) {

			$query = self::scrape_instagram( $atts['insta_username'], $atts['insta_update'] );

			if ( empty( $query ) || ! is_array( $query ) ) {
				delete_transient( 'codevz-instagram-' . sanitize_title_with_dashes( $atts['insta_username'] ) );
				$query = self::scrape_instagram( $atts['insta_username'], $atts['insta_update'] );
			}

			$i = 0;
			foreach ( (array) $query as $q ) {
				$info = empty( $q['likes'] ) ? '' : '<i class="fa fa-heart mr8"></i>' . number_format( $q['likes'] );
				$info .= empty( $q['comments'] ) ? '' : '<i class="fa fa-comment ml8 mr8"></i>' . number_format( $q['comments'] );
				$out .= self::get_gallery_item( Codevz_Plus::get_image( @$q[ $atts['insta_size'] ] ), @$q['link'], Codevz_Plus::limit_words( @$q['description'], ( ! empty( $atts['title_limit'] ) ? $atts['title_limit'] : 999 ) ), $info, $atts, '', $atts['img_fx'] );

				$i++;
				if ( $i == $atts['insta_count'] ) {
					break;
				}
			}

		} else if ( $atts['type'] === 'gallery2' ) {

			$out .= $gallery2_out;

		} else {

			$images = $atts['images'] ? explode( ',', $atts['images'] ) : array( 1,1,1,1,1,1,1,1 );
			foreach ( $images as $image ) {

				if ( function_exists( 'icl_object_id' ) ) {
					$image = icl_object_id( $image, 'attachment', true, ICL_LANGUAGE_CODE );
				}
				$title = get_post( $image );
				$class = '';

				if ( is_object( $title ) ) {
					$info = Codevz_Plus::contains( $title->post_content, 'vc_row' ) ? '' : $title->post_content;
					$out .= self::get_gallery_item( Codevz_Plus::get_image( $image, $image_size ), Codevz_Plus::get_image( $image, 0, 1 ), Codevz_Plus::limit_words( $title->post_title, ( ! empty( $atts['title_limit'] ) ? $atts['title_limit'] : 999 ) ), $info, $atts, $class, $atts['img_fx'] );
				}
			}

		}

		$out .= '</div>';
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
			if ( $atts['sk_prev_icon_tablet'] ) { $c[] = 'sk_prev_icon_tablet="' . $atts['sk_prev_icon_tablet'] . '"'; }
			if ( $atts['sk_prev_icon_mobile'] ) { $c[] = 'sk_prev_icon_mobile="' . $atts['sk_prev_icon_mobile'] . '"'; }
			if ( $atts['sk_next_icon'] ) { $c[] = 'sk_next_icon="' . $atts['sk_next_icon'] . '"'; }
			if ( $atts['sk_next_icon_hover'] ) { $c[] = 'sk_next_icon_hover="' . $atts['sk_next_icon_hover'] . '"'; }
			if ( $atts['sk_next_icon_tablet'] ) { $c[] = 'sk_next_icon_tablet="' . $atts['sk_next_icon_tablet'] . '"'; }
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

		return Codevz_Plus::_out( $atts, $out, array( 'grid( true )', 'tilt' ), $this->name );
	}

	/**
	 *
	 * Ajax query get posts
	 * 
	 * @return string
	 * 
	 */
	public static function get_gallery_item( $i = '', $bi = '', $t = '', $s = '', $atts = '', $cls = '', $fx = '', $badge = '', $sk_badge = '' ) {

		$out = $target = '';

		if ( $atts['type'] === 'gallery' ) {
			$target = ' data-xtra-lightbox';
		} else if ( $atts['type'] === 'instagram' || $atts['type'] === 'gallery2' ) {
			$target = $atts['target'] ? '' : ' target="_blank"';
		}

		$badge = $badge ? '<div class="cz_gallery_badge"' . ( $sk_badge ? ' style="' . $sk_badge . '"' : '' ) . '>' . $badge . '</div>' : '';
		if ( Codevz_Plus::contains( $bi, [ '#', 'youtube.com/?watch', 'youtu.be/?watch', 'vimeo', 'mp4', '.jpg', '.png', '.gif', '.jpeg', '.webp' ] )  ) {
			$target .= ' data-xtra-lightbox';
		}
		$out .= '<div class="cz_grid_item ' . $cls . '"><div>' . $badge . '<a class="cz_grid_link ' . $fx . '" href="' . $bi . '"' . $target . $atts['tilt_data'] . '>' . $i;

		// Info
		$small_a = $small_b = $det = '';
		if ( $s && ( Codevz_Plus::contains( $atts['hover'], array( 'all', 'after', 'w_info' ) ) || $atts['hover'] === 'cz_grid_1_no_icon' ) ) {
			if ( $atts['subtitle_pos'] === 'cz_grid_1_title_rev' ) {
				$small_a = '<small class="clr">' . $s . '</small>';
			} else {
				$small_b = '<small class="clr">' . $s . '</small>';
			}
		}

		// Title.
		if ( Codevz_Plus::contains( $atts[ 'hover' ], [ 'no_desc', 'all', 'no_icon', 'title_sub_after' ] ) ) {
			$t = '<' . esc_attr( $atts[ 'title_tag' ] ) . '>' . wp_kses_post( $t ) . '</' . esc_attr( $atts[ 'title_tag' ] ) . '>';
		} else {
			$t = '';
		}

		if ( Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_title_sub_after' ) ) {
			if ( Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_subtitle_on_img' ) ) {
				$out .= '<div class="cz_grid_details">' . $small_a . $small_b . '</div>';
				$small_a = $small_b = '';
			} else {
				$out .= '<div class="cz_grid_details"><i class="' . $atts['icon'] . ' cz_grid_icon"></i></div>';
			}

			$det = '<div class="cz_grid_details cz_grid_details_outside">' . $small_a . '<a class="cz_grid_title" href="' . $bi . '">' . $t . '</a>' . $small_b . '</div>';
		} else {
			$out .= '<div class="cz_grid_details"><i class="' . $atts['icon'] . ' cz_grid_icon"></i>' . $small_a . $t . $small_b . '</div>';
		}
		$out .= '</a>'. $det . '</div></div>';

		return $out;
	}

	/**
	 *
	 * Scrape instagram data via wp_remote_get
	 * 
	 * @var username or hashtag, updating transient time
	 * @return array
	 * 
	 */
	public static function scrape_instagram( $username, $tt = 72 ) {

		return [];

	}

}