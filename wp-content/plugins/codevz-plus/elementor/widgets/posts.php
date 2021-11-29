<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_posts extends Widget_Base {

	protected $id = 'cz_posts';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Posts Grid', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-posts';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Grid', 'codevz' ),
			esc_html__( 'Post', 'codevz' ),
			esc_html__( 'Content', 'codevz' ),
			esc_html__( 'News', 'codevz' ),
			esc_html__( 'Magazine', 'codevz' ),

		];

	}

	public function get_style_depends() {

		$array = [ $this->id, 'cz_gallery', 'cz_carousel', 'cz_parallax' ];

		if ( Codevz_Plus::$is_rtl ) {
			$array[] = $this->id . '_rtl';
		}

		return $array;

	}

	public function get_script_depends() {
		return [ $this->id, 'cz_gallery', 'cz_carousel', 'cz_parallax' ];
	}

	public function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Layout', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'codevz' ),
				'type' => 'image_select',
				'label_block' => true,
				'default' => 'cz_grid_c4',
				'options' => array(
					'cz_justified' => [
						'title'=> 'Justified',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_1.png'
					],
					'cz_grid_c1 cz_grid_l1' => [
						'title'=> '3 rows',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_2.png'
					],
					'cz_grid_c2 cz_grid_l2' => [
						'title'=> '2 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_3.png'
					],
					'cz_grid_c2' => [
						'title'=> '2 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_4.png'
					],
					'cz_grid_c3' => [
						'title'=> '3 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_5.png'
					],
					'cz_grid_c4' => [
						'title'=> '4 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_6.png'
					],
					'cz_grid_c5' => [
						'title'=> '5 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_7.png'
					],
					'cz_grid_c6' => [
						'title'=> '6 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_8.png'
					],
					'cz_grid_c7' => [
						'title'=> '7 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_9.png'
					],
					'cz_grid_c8' => [
						'title'=> '8 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_10.png'
					],
					'cz_hr_grid cz_grid_c2' => [
						'title'=> '2 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_11.png'
					],
					'cz_hr_grid cz_grid_c3' => [
						'title'=> '3 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_12.png'
					],
					'cz_hr_grid cz_grid_c4' => [
						'title'=> '4 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_13.png'
					],
					'cz_hr_grid cz_grid_c5' => [
						'title'=> '5 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_14.png'
					],
					'cz_masonry cz_grid_c2' => [
						'title'=> 'Masonry 2 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_15.png'
					],
					'cz_masonry cz_grid_c3' => [
						'title'=> 'Masonry 3 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_16.png'
					],
					'cz_masonry cz_grid_c4' => [
						'title'=> 'Masonry 4 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_17.png'
					],
					'cz_masonry cz_grid_c4 cz_grid_1big' => [
						'title'=> '1 Big Masonry 4 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_18.png'
					],
					'cz_masonry cz_grid_c5' => [
						'title'=> 'Masonry 5 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_19.png'
					],
					'cz_metro_1 cz_grid_c4' => [
						'title'=> 'Metro1 4 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_20.png'
					],
					'cz_metro_2 cz_grid_c4' => [
						'title'=> 'Metro2 4 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_21.png'
					],
					'cz_metro_3 cz_grid_c4' => [
						'title'=> 'Metro3 4 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_22.png'
					],
					'cz_metro_4 cz_grid_c4' => [
						'title'=> 'Metro4 4 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_23.png'
					],
					'cz_metro_5 cz_grid_c3' => [
						'title'=> 'Metro5 3 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_24.png'
					],
					'cz_metro_6 cz_grid_c3' => [
						'title'=> 'Metro6 3 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_25.png'
					],
					'cz_metro_7 cz_grid_c7' => [
						'title'=> 'Metro7 7 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_26.png'
					],
					'cz_metro_8 cz_grid_c4' => [
						'title'=> 'Metro8 4 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_27.png'
					],
					'cz_metro_9 cz_grid_c6' => [
						'title'=> 'Metro9 6 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_28.png'
					],
					'cz_metro_10 cz_grid_c6' => [
						'title'=> 'Metro10 6 columns',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_29.png'
					],
					'cz_grid_carousel' => [
						'title'=> 'Carousel',
						'url'=> Codevz_Plus::$url . 'assets/img/gallery_30.png'
					],
					'cz_posts_list_1' => [
						'title'=> 'Posts List 1',
						'url'=> Codevz_Plus::$url . 'assets/img/posts_list_1.png'
					],
					'cz_posts_list_2' => [
						'title'=> 'Posts List 2',
						'url'=> Codevz_Plus::$url . 'assets/img/posts_list_2.png'
					],
					'cz_posts_list_3' => [
						'title'=> 'Posts List 3',
						'url'=> Codevz_Plus::$url . 'assets/img/posts_list_3.png'
					],
					'cz_posts_list_4' => [
						'title'=> 'Posts List 4',
						'url'=> Codevz_Plus::$url . 'assets/img/posts_list_4.png'
					],
					'cz_posts_list_5' => [
						'title'=> 'Posts List 5',
						'url'=> Codevz_Plus::$url . 'assets/img/posts_list_5.png'
					],
				),
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'custom_items_section',
			[
				'label' => esc_html__( 'Custom item(s)', 'codevz' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'position',
			[
				'label' 	=> esc_html__( 'Position', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> 1
			]
		);

		$repeater->add_responsive_control(
			'sk_item',
			[
				'label' 	=> esc_html__( 'Style', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '{{CURRENT_ITEM}} > div' ),
			]
		);

		$repeater->add_control(
			'type', [
				'label' 	=> esc_html__( 'Content type', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> [
					'' 			=> esc_html__( 'Content', 'codevz' ),
					'template' 	=> esc_html__( 'Saved template', 'codevz' ),
				]
			]
		);

		$repeater->add_control(
			'content', [
				'label' 	=> esc_html__( 'Content', 'codevz' ),
				'type' 		=> Controls_Manager::WYSIWYG,
				'default' 	=> 'Hello World ...',
				'placeholder' => 'Hello World ...',
				'condition' => [
					'type' 		=> ''
				],
			]
		);

		$repeater->add_control(
			'xtra_elementor_template',
			[
				'label' 	=> esc_html__( 'Select template', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> Xtra_Elementor::get_templates(),
				'condition' => [
					'type' => 'template'
				],
			]
		);

		$this->add_control(
			'custom_items',
			[
				'label' => esc_html__( 'Custom item(s)', 'codevz' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls()
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
			]
		);

		$this->add_control (
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts count', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => esc_html__( 'Gap', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_grid .slick-list' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2);margin-right: calc(-{{SIZE}}{{UNIT}} / 2);margin-bottom: -{{SIZE}}{{UNIT}};width: calc(100% + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .cz_grid .cz_grid_item > div' => 'margin:0 calc({{SIZE}}{{UNIT}} / 2) {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-slide' => 'margin:0 calc({{SIZE}}{{UNIT}} / 2);',
				]
			]
		);

		$this->add_control(
			'two_columns_on_mobile',
			[
				'label' => esc_html__( 'Two columns on mobile?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER 
			]
		);

		$this->add_control(
			'hover',
			[
				'label' => esc_html__( 'Posts details style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_grid_1_no_icon',
				'options' => [
					'cz_grid_1_no_hover'  => esc_html__( 'No hover details', 'codevz' ),
					'cz_grid_1_no_title cz_grid_1_no_desc' => esc_html__( 'Only icon on hover', 'codevz' ),
					'cz_grid_1_no_desc' => esc_html__( 'Icon & Title on hover', 'codevz' ),
					'cz_grid_1_yes_all' => esc_html__( 'Icon & Title & Meta on hover', 'codevz' ),
					'cz_grid_1_no_icon cz_grid_1_no_desc' => esc_html__( 'Title on hover', 'codevz' ),
					'cz_grid_1_no_icon' => esc_html__( 'Title & Meta on hover', 'codevz' ),
					'cz_grid_1_no_icon cz_grid_1_has_excerpt cz_grid_1_no_desc' => esc_html__( 'Title & Excerpt on hover', 'codevz' ),
					'cz_grid_1_no_icon cz_grid_1_has_excerpt' => esc_html__( 'Title & Meta & Excerpt on hover', 'codevz' ),
					'cz_grid_1_title_sub_after cz_grid_1_no_hover' => esc_html__( 'No hover details, Title & Meta after Image', 'codevz' ),
					'cz_grid_1_title_sub_after' => esc_html__( 'Icon on hover, Title & Meta after Image', 'codevz' ),
					'cz_grid_1_title_sub_after cz_grid_1_has_excerpt' => esc_html__( 'Icon on hover, Title & Meta & Excerpt after Image', 'codevz' ),
					'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_icon' => esc_html__( 'No Icon, Title & Meta & Excerpt after Image', 'codevz' ),
					'cz_grid_1_title_sub_after cz_grid_1_subtitle_on_img' => esc_html__( 'Meta on image, Title after image', 'codevz' ),
					'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_subtitle_on_img' => esc_html__( 'Meta on image, Title & Excerpt after image', 'codevz' ),
					'cz_grid_1_title_sub_after cz_grid_1_no_image' => esc_html__( 'No image, Title & Meta', 'codevz' ),
					'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image' => esc_html__( 'No image, Title & Meta & Excerpt', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'animation',
			[
				'label' => esc_html__( 'Intro animation', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_grid_anim_fade_in' => esc_html__( 'Fade In', 'codevz' ),
					'cz_grid_anim_move_up' => esc_html__( 'Move Up', 'codevz' ),
					'cz_grid_anim_move_down' => esc_html__( 'Move Down', 'codevz' ),
					'cz_grid_anim_move_right' => esc_html__( 'Move Right', 'codevz' ),
					'cz_grid_anim_move_left' => esc_html__( 'Move Left', 'codevz' ),
					'cz_grid_anim_zoom_in' => esc_html__( 'Zoom In', 'codevz' ),
					'cz_grid_anim_zoom_out' => esc_html__( 'Zoom Out', 'codevz' ),
					'cz_grid_anim_slant' => esc_html__( 'Slant', 'codevz' ),
					'cz_grid_anim_helix' => esc_html__( 'Helix', 'codevz' ),
					'cz_grid_anim_fall_perspective' => esc_html__( 'Fall Perspective', 'codevz' ),
					'cz_grid_brfx_right' => esc_html__( 'Block reveal right', 'codevz' ),
					'cz_grid_brfx_left' => esc_html__( 'Block reveal left', 'codevz' ),
					'cz_grid_brfx_up' => esc_html__( 'Block reveal up', 'codevz' ),
					'cz_grid_brfx_down' => esc_html__( 'Block reveal down', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'subtitle_pos',
			[
				'label' => esc_html__( 'Meta position?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_grid_1_title_rev' => esc_html__( 'Before title', 'codevz' ),
					'cz_grid_1_sub_after_ex' => esc_html__( 'After Excerpt', 'codevz' ),
				],
				'condition' => [
					'hover!' => [
						'cz_grid_1_no_hover', 
						'cz_grid_1_no_title', 
						'cz_grid_1_no_desc', 
						'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_subtitle_on_img', 
						'cz_grid_1_title_sub_after cz_grid_1_subtitle_on_img', 
						'cz_grid_1_no_icon cz_grid_1_no_desc',
					],
				],
			]
		);

		$this->add_control(
			'hover_pos',
			[
				'label' => esc_html__( 'Details align', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_grid_1_bot tal',
				'options' => [
					'cz_grid_1_top tal'  => esc_html__( 'Top Left', 'codevz' ),
					'cz_grid_1_top tac'  => esc_html__( 'Top Center', 'codevz' ),
					'cz_grid_1_top tar'  => esc_html__( 'Top Right', 'codevz' ),
					'cz_grid_1_mid tal'  => esc_html__( 'Middle Left', 'codevz' ),
					'cz_grid_1_mid tac'  => esc_html__( 'Middle Center', 'codevz' ),
					'cz_grid_1_mid tar'  => esc_html__( 'Middle Right', 'codevz' ),
					'cz_grid_1_bot tal'  => esc_html__( 'Bottom Left', 'codevz' ),
					'cz_grid_1_bot tac'  => esc_html__( 'Bottom Center', 'codevz' ),
					'cz_grid_1_bot tar'  => esc_html__( 'Bottom Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'hover_vis',
			[
				'label' => esc_html__( 'Hover visibility?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Show overlay on hover', 'codevz' ),
					'cz_grid_1_hide_on_hover' => esc_html__( 'Hide overlay on hover', 'codevz' ),
					'cz_grid_1_always_show' => esc_html__( 'Always show overlay', 'codevz' ),
				],
				'condition' => [
					'hover!' => [
						'cz_grid_1_no_hover', 
						'cz_grid_1_title_sub_after cz_grid_1_no_hover', 
						'cz_grid_1_title_sub_after cz_grid_1_no_image', 
						'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image',
					],
				],
			]
		);

		$this->add_control(
			'hover_fx',
			[
				'label' => esc_html__( 'Hover effect?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Fade in Top', 'codevz' ),
					'cz_grid_fib' => esc_html__( 'Fade in Bottom', 'codevz' ),
					'cz_grid_fil' => esc_html__( 'Fade in Left', 'codevz' ),
					'cz_grid_fir' => esc_html__( 'Fade in Right', 'codevz' ),
					'cz_grid_zin' => esc_html__( 'Zoom in', 'codevz' ),
					'cz_grid_zou' => esc_html__( 'Zoom Out', 'codevz' ),
					'cz_grid_siv' => esc_html__( 'Opening Vertical', 'codevz' ),
					'cz_grid_sih' => esc_html__( 'Opening Horizontal', 'codevz' ),
					'cz_grid_sil' => esc_html__( 'Slide in Left', 'codevz' ),
					'cz_grid_sir' => esc_html__( 'Slide in Right', 'codevz' ),
				],
				'condition' => [
					'hover!' => [
						'cz_grid_1_no_hover', 
						'cz_grid_1_title_sub_after cz_grid_1_no_hover', 
						'cz_grid_1_title_sub_after cz_grid_1_no_image', 
						'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image',
					],
				],
			]
		);

		$this->add_control(
			'img_fx',
			[
				'label' => esc_html__( 'Hover image effect?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_grid_inset_clip_1x' => esc_html__( 'Inset Mask 1x', 'codevz' ),
					'cz_grid_inset_clip_2x' => esc_html__( 'Inset Mask 2x', 'codevz' ),
					'cz_grid_inset_clip_3x' => esc_html__( 'Inset Mask 3x', 'codevz' ),
					'cz_grid_zoom_mask' => esc_html__( 'Zoom Mask', 'codevz' ),
					'cz_grid_scale' => esc_html__( 'Scale', 'codevz' ),
					'cz_grid_scale2' => esc_html__( 'Scale 2', 'codevz' ),
					'cz_grid_grayscale' => esc_html__( 'Grayscale', 'codevz' ),
					'cz_grid_grayscale_on_hover' => esc_html__( 'Grayscale on hover', 'codevz' ),
					'cz_grid_grayscale_remove' => esc_html__( 'Remove Grayscale', 'codevz' ),
					'cz_grid_blur' => esc_html__( 'Blur', 'codevz' ),
					'cz_grid_zoom_in' => esc_html__( 'ZoomIn', 'codevz' ),
					'cz_grid_zoom_out' => esc_html__( 'ZoomOut', 'codevz' ),
					'cz_grid_zoom_rotate' => esc_html__( 'Zoom Rotate', 'codevz' ),
					'cz_grid_flash' => esc_html__( 'Flash', 'codevz' ),
					'cz_grid_shine' => esc_html__( 'Shine', 'codevz' ),
				],
				'condition' => [
					'hover!' => [
						'cz_grid_1_title_sub_after cz_grid_1_no_image', 
						'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image',
					],
				],
			]
		);

		$this->add_control(
			'css_position',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_grid_inset_clip_1x' => esc_html__( 'Inset Mask 1x', 'codevz' ),
					'cz_grid_inset_clip_2x' => esc_html__( 'Inset Mask 2x', 'codevz' ),
					'cz_grid_inset_clip_3x' => esc_html__( 'Inset Mask 3x', 'codevz' ),
					'cz_grid_zoom_mask' => esc_html__( 'Zoom Mask', 'codevz' ),
					'cz_grid_scale' => esc_html__( 'Scale', 'codevz' ),
					'cz_grid_scale2' => esc_html__( 'Scale2', 'codevz' ),
					'cz_grid_grayscale' => esc_html__( 'Grayscale', 'codevz' ),
					'cz_grid_grayscale_on_hover' => esc_html__( 'Grayscale on hover', 'codevz' ),
					'cz_grid_grayscale_remove' => esc_html__( 'Remove Grayscale', 'codevz' ),
					'cz_grid_blur' => esc_html__( 'Blur', 'codevz' ),
					'cz_grid_zoom_in' => esc_html__( 'ZoomIn', 'codevz' ),
					'cz_grid_zoom_out' => esc_html__( 'ZoomOut', 'codevz' ),
					'cz_grid_zoom_rotate' => esc_html__( 'Zoom Roate', 'codevz' ),
					'cz_grid_flash' => esc_html__( 'Flash', 'codevz' ),
					'cz_grid_shine' => esc_html__( 'Shine', 'codevz' ),
				],
				'condition' => [
					'hover' => [
						'cz_grid_1_title_sub_after cz_grid_1_no_image', 
						'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image',
					]
				]
			]
		);

		$this->add_control(
			'height',
			[
				'label' => esc_html__( 'Ideal height', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 400,
						'step' => 1,
					],
				],
				'condition' => [
					'layout' => 'cz_justified',
				]
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' 			=> 'inline',
				'label_block' 	=> false,
				'default' => [
					'value' => 'fa fa-search',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'overlay_outer_space',
			[
				'label' => esc_html__( 'Overlay scale', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__( '~ Default ~', 'codevz' ),
					'cz_grid_overlay_5px' 	=> '1',
					'cz_grid_overlay_10px' 	=> '2',
					'cz_grid_overlay_15px' 	=> '3',
					'cz_grid_overlay_20px' 	=> '4',
				],
				'condition' => [
					'hover!' => [
						'cz_grid_1_no_hover', 
						'cz_grid_1_title_sub_after cz_grid_1_no_hover', 
						'cz_grid_1_title_sub_after cz_grid_1_no_image', 
						'cz_grid_1_title_sub_after cz_grid_1_no_image', 
						'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image',
					],
				],
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_container',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_p' ),
			]
		);

		$this->add_responsive_control(
			'sk_overall',
			[
				'label' 	=> esc_html__( 'All posts', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_grid_item > div' ),
			]
		);

		$this->add_responsive_control(
			'sk_img',
			[
				'label' 	=> esc_html__( 'Images', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_grid_link', '.cz_grid .cz_grid_item:hover .cz_grid_link' ),
			]
		);

		$this->add_responsive_control(
			'sk_overlay',
			[
				'label' 	=> esc_html__( 'Overlay', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_grid_link:before', '.cz_grid .cz_grid_item:hover .cz_grid_link:before' ),
				'condition' => [
					'hover!' => [
						'cz_grid_1_no_hover', 
						'cz_grid_1_title_sub_after cz_grid_1_no_hover', 
						'cz_grid_1_title_sub_after cz_grid_1_no_image', 
						'cz_grid_1_title_sub_after cz_grid_1_no_image', 
						'cz_grid_1_title_sub_after cz_grid_1_has_excerpt cz_grid_1_no_image',
					],
				],
			]
		);

		$this->add_responsive_control(
			'sk_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_grid_icon' ),
				'condition' => [
					'hover' => [
						'cz_grid_1_no_title', 
						'cz_grid_1_no_desc', 
						'cz_grid_1_yes_all', 
						'cz_grid_1_title_sub_after', 
						'cz_grid_1_title_sub_after cz_grid_1_has_excerpt',
					],
				],
			]
		);

		$this->add_responsive_control(
			'sk_content',
			[
				'label' 	=> esc_html__( 'Out Content', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid div > .cz_grid_details', '.cz_grid .cz_grid_item:hover div > .cz_grid_details' ),
			]
		);

		$this->add_responsive_control(
			'sk_title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background', 'padding' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_grid_details h3', '.cz_grid .cz_grid_item:hover .cz_grid_details h3' ),
			]
		);

		$this->add_responsive_control(
			'sk_meta',
			[
				'label' 	=> esc_html__( 'Meta', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'position', 'left', 'top', 'bottom', 'right', 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_grid_details small', '.cz_grid .cz_grid_item:hover .cz_grid_details small' ),
			]
		);

		$this->add_responsive_control(
			'sk_meta_icons',
			[
				'label' 	=> esc_html__( 'Meta icons', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_sub_icon', '.cz_grid .cz_grid_item:hover .cz_sub_icon' ),
			]
		);

		$this->add_responsive_control(
			'sk_excerpt',
			[
				'label' 	=> esc_html__( 'Excerpt', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-size', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_post_excerpt', '.cz_grid .cz_grid_item:hover .cz_post_excerpt' ),
			]
		);

		$this->add_responsive_control(
			'sk_readmore',
			[
				'label' 	=> esc_html__( 'Read more', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_post_excerpt .cz_readmore', '.cz_grid .cz_post_excerpt .cz_readmore:hover' ),
			]
		);

		$this->add_responsive_control(
			'sk_load_more',
			[
				'label' 	=> esc_html__( 'Load more', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_ajax_pagination a', '.cz_ajax_pagination a:hover' ),
			]
		);

		$this->add_responsive_control(
			'sk_load_more_active',
			[
				'label' 	=> esc_html__( 'Load more active', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'border-right-color', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_ajax_pagination .cz_ajax_loading' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_filters',
			[
				'label' => esc_html__( 'Filters', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'sk_filters_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border', 'padding' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_filters' ),
				'condition' => [
					'filters!' => '',
				]
			]
		);

		$this->add_responsive_control(
			'sk_filters',
			[
				'label' 	=> esc_html__( 'Filters', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_filters li' ),
				'condition' => [
					'filters!' => '',
				]
			]
		);

		$this->add_responsive_control(
			'sk_filters_separator',
			[
				'label' 	=> esc_html__( 'Filters delimiter', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'content', 'color', 'font-size', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_filters li:after' ),
				'condition' => [
					'filters!' => '',
				]
			]
		);

		$this->add_responsive_control(
			'sk_filter_active',
			[
				'label' 	=> esc_html__( 'Active Filter', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_filters .cz_active_filter' ),
				'condition' => [
					'filters!' => '',
				]
			]
		);

		$this->add_responsive_control(
			'sk_filters_items_count',
			[
				'label' 	=> esc_html__( 'Filter items count', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'font-size', 'color', 'background', 'border', 'padding', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_filters li span', '.cz_grid_filters_count_a li span, cz_grid .cz_grid_filters_count li:hover span, cz_grid li.cz_active_filter span' ),
				'condition' => [
					'filters!' => '',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_carousel',
			[
				'label' => esc_html__( 'Carousel', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_responsive_control(
			'sk_slides',
			[
				'label' 	=> esc_html__( 'Slides', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'grayscale', 'blur', 'background', 'opacity', 'z-index', 'padding', 'margin', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( 'div.slick-slide' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_responsive_control(
			'sk_center',
			[
				'label' 	=> esc_html__( 'Center slide', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'grayscale', 'background', 'opacity', 'z-index', 'padding', 'margin', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( 'div.slick-center' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_responsive_control(
			'sk_prev_icon',
			[
				'label' 	=> esc_html__( 'Previous icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.slick-prev' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);


		$this->add_responsive_control(
			'sk_next_icon',
			[
				'label' 	=> esc_html__( 'Next icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.slick-next' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_responsive_control(
			'sk_dots_container',
			[
				'label' 	=> esc_html__( 'Dots Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.slick-dots' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_responsive_control(
			'sk_dots',
			[
				'label' 	=> esc_html__( 'Dots', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.slick-dots li button' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->end_controls_section();

		// Meta.
		$this->start_controls_section(
			'section_meta',
			[
				'label' => esc_html__( 'Meta', 'codevz' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			't',
			[
				'label' => esc_html__( 'Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'  => esc_html__( 'Date', 'codevz' ),
					'cats' => esc_html__( 'Categories', 'codevz' ),
					'cats_2' => esc_html__( 'Categories', 'codevz' ) . ' 2',
					'cats_3' => esc_html__( 'Categories', 'codevz' ) . ' 3',
					'cats_4' => esc_html__( 'Categories', 'codevz' ) . ' 4',
					'cats_5' => esc_html__( 'Categories', 'codevz' ) . ' 5',
					'cats_6' => esc_html__( 'Categories', 'codevz' ) . ' 6',
					'cats_7' => esc_html__( 'Categories', 'codevz' ) . ' 7',
					'tags' => esc_html__( 'Tags', 'codevz' ),
					'author' => esc_html__( 'Author', 'codevz' ),
					'author_avatar' => esc_html__( 'Author Avatar', 'codevz' ),
					'author_full_date' => esc_html__( 'Avatar + Author & Date', 'codevz' ),
					'author_icon_date' => esc_html__( 'Icon + Author & Date', 'codevz' ),
					'comments' => esc_html__( 'Comments', 'codevz' ),
					'price' => esc_html__( 'Product Price', 'codevz' ),
					'add_to_cart' => esc_html__( 'Product add to cart', 'codevz' ),
					'custom_text' => esc_html__( 'Custom Text', 'codevz' ),
					'custom_meta' => esc_html__( 'Custom Meta', 'codevz' ),
				],
			]
		);

		$repeater->add_control(
			'r',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' 					=> esc_html__( '~ Default ~', 'codevz' ),
					'cz_post_data_r' 	=> esc_html__( 'Inverted', 'codevz' ),
				],
			]
		);

		$repeater->add_control(
			'i',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' 			=> 'inline',
				'label_block' 	=> false,
				'condition' => [
					't!' => [
						'author_avatar', 
						'author_full_date,'
					],
				],
			]
		);

		$repeater->add_control(
			'p', [
				'label' => esc_html__( 'Prefix', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					't' => [
						'date', 
						'cats', 
						'tags', 
						'author', 
						'comments',
					],
				],
			]
		);

		$repeater->add_control(
			'ct', [
				'label' => esc_html__( 'Custom text', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					't' => 'custom_text',
				],
			]
		);

		$repeater->add_control(
			'cm', [
				'label' => esc_html__( 'Custom meta name', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					't' => 'custom_meta',
				],
			]
		);

		$repeater->add_responsive_control (
			'tc',
			[
				'label' => esc_html__( 'Count', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'condition' => [
					't' => [
						'cats_2', 
						'cats_3', 
						'cats_4', 
						'cats_5', 
						'cats_6', 
						'cats_7', 
						'tags',
					],
				],
			]
		);
		
		$this->add_control(
			'subtitles',
			[
				'label' => esc_html__( 'Posts meta', 'codevz' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						't' => 'date',
						'r' => '',
						'p' => '',
					],
				],
			]
		);

		$this->end_controls_section();

		//Excerpt
		$this->start_controls_section(
			'section_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'codevz' ),
			]
		);

		$this->add_control (
			'title_lenght',
			[
				'label' => esc_html__( 'Title length', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
			]
		);

		$this->add_control(
			'single_line_title',
			[
				'label' => esc_html__( 'Single line title', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control (
			'el',
			[
				'label' => esc_html__( 'Excerpt lenght', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 200,
				'step' => 1,
			]
		);

		$this->add_control(
			'excerpt_rm',
			[
				'label' => esc_html__( 'Read more?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		

		$this->end_controls_section();

		//Load More
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => esc_html__( 'Pagination', 'codevz' ),
			]
		);

		$this->add_control(
			'loadmore',
			[
				'label' => esc_html__( 'Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'loadmore' => esc_html__( 'Load More', 'codevz' ),
					'infinite' => esc_html__( 'Infinite Scroll', 'codevz' ),
					'pagination' => esc_html__( 'Pagination', 'codevz' ),
					'older' => esc_html__( 'Older / Newer', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'loadmore_pos',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tac',
				'options' => [
					''  	=> esc_html__( 'Select', 'codevz' ),
					'tal' 	=> esc_html__( 'Left', 'codevz' ),
					'tac' 	=> esc_html__( 'Center', 'codevz' ),
					'tar' 	=> esc_html__( 'Right', 'codevz' ),
					'cz_loadmore_block' => esc_html__( 'Block', 'codevz' ),
				],
				'condition' => [
					'loadmore' => [
						'loadmore', 
						'infinite',
					],
				],
			]
		);

		$this->add_control(
			'loadmore_title', [
				'label' => esc_html__( 'Title', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Load More', 'codevz' ),
				'condition' => [
					'loadmore' => [
						'loadmore', 
						'infinite',
					],
				],
			]
		);

		$this->add_control(
			'loadmore_end', [
				'label' => esc_html__( 'End', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Not found more posts', 'codevz' ),
				'condition' => [
					'loadmore' => [
						'loadmore', 
						'infinite',
					],
				],
			]
		);

		$this->add_control (
			'loadmore_lenght',
			[
				'label' => esc_html__( 'Posts count', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'condition' => [
					'loadmore' => [
						'loadmore', 
						'infinite',
					],
				],
			]
		);

		$this->end_controls_section();

		// Filter
		$this->start_controls_section(
			'section_filter',
			[
				'label' 	=> esc_html__( 'Filter & Search', 'codevz' )
			]
		);

		$terms = [];

		foreach( get_terms() as $term ) {

			$taxonomy = get_taxonomy( $term->taxonomy );

			$terms[ $term->term_id ] = $term->name . ' (' . $taxonomy->object_type[ 0 ] . ')';

		}

		$this->add_control(
			'filters',
			[
				'label' 	=> esc_html__( 'Choose Filter', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT2,
				'multiple' 	=> true,
				'options' 	=> $terms
			]
		);

		$this->add_control(
			'filters_tax',
			[
				'label' => esc_html__( 'Taxonomy', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => get_taxonomies()
			]
		);

		$this->add_control(
			'filters_pos',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'tal' => esc_html__( 'Left', 'codevz' ),
					'tac' => esc_html__( 'Center', 'codevz' ),
					'tar' => esc_html__( 'Right', 'codevz' ),
				]
			]
		);

		$this->add_control(
			'browse_all',
			[
				'label' => esc_html__( 'Show All', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Show All', 'codevz' ),
				'placeholder' => esc_html__( 'Show All', 'codevz' )
			]
		);

		$this->add_control(
			'filters_items_count',
			[
				'label' => esc_html__( 'Filters items count?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_grid_filters_count_a' => esc_html__( 'Above filters', 'codevz' ),
					'cz_grid_filters_count_ah' => esc_html__( 'Above filters on hover', 'codevz' ),
					'cz_grid_filters_count_i' => esc_html__( 'Inline beside filters', 'codevz' ),
				]
			]
		);

		$this->end_controls_section();

		//Start WP_Query
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'WP Query', 'codevz' ),
			]
		);

		$this->add_control(
			'post_type', [
				'label' => esc_html__( 'Post type(s)', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => esc_html__( 'Orderby', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'codevz' ),
					'ID' => esc_html__( 'ID', 'codevz' ),
					'rand' => esc_html__( 'Random', 'codevz' ),
					'author' => esc_html__( 'Author', 'codevz' ),
					'title' => esc_html__( 'Title', 'codevz' ),
					'name' => esc_html__( 'Name', 'codevz' ),
					'type' => esc_html__( 'Type', 'codevz' ),
					'modified' => esc_html__( 'Modified', 'codevz' ),
					'parent' => esc_html__( 'Parent ID', 'codevz' ),
					'comment_count' => esc_html__( 'Comment Count', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' 	=> esc_html__( 'Order', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'DESC',
				'options' 	=> [
					'DESC' 		=> esc_html__( 'Descending', 'codevz' ),
					'ASC' 		=> esc_html__( 'Ascending', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'cat_tax',
			[
				'label' 	=> esc_html__( 'Category Taxonomy', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> get_taxonomies(),
				'default' 	=> 'category'
			]
		);

		$this->add_control(
			'cat', 
			[
				'label' 	=> esc_html__( 'Category(s)', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT2,
				'multiple' 	=> true,
				'options' 	=> $terms
			]
		);

		$this->add_control(
			'cat_exclude', 
			[
				'label' 	=> esc_html__( 'Exclude Category(s)', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT2,
				'multiple' 	=> true,
				'options' 	=> $terms
			]
		);

		$this->add_control(
			'tag_tax',
			[
				'label' 	=> esc_html__( 'Tags Taxonomy', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> get_taxonomies(),
				'default' 	=> 'post_tag'
			]
		);

		$this->add_control(
			'tag_id', 
			[
				'label' 	=> esc_html__( 'Tag ID', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT2,
				'multiple' 	=> true,
				'options' 	=> $terms
			]
		);

		$this->add_control(
			'tag_exclude', 
			[
				'label' 	=> esc_html__( 'Exclude Tag ID', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT2,
				'multiple' 	=> true,
				'options' 	=> $terms
			]
		);

		$this->add_control(
			'post__in', 
			[
				'label' 	=> esc_html__( 'Filter by posts', 'codevz' ),
				'type' 		=> Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'author__in', 
			[
				'label' 	=> esc_html__( 'Filter by authors', 'codevz' ),
				'type' 		=> Controls_Manager::TEXT
			]
		);

		$this->add_control(
			's', 
			[
				'label' 	=> esc_html__( 'Search keyword', 'codevz' ),
				'type' 		=> Controls_Manager::TEXT
			]
		);

		$this->end_controls_section();
		// End WP_Query

		// Carousel
		$this->start_controls_section(
			'section_carousel',
			[
				'label' => esc_html__( 'Carousel', 'codevz' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_responsive_control(
			'slidestoshow',
			[
				'label' 	=> esc_html__( 'Slides to show', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> 3,
				'min' 		=> 1,
				'max' 		=> 10,
				'step' 		=> 1,
				'condition' => [
					'layout' 	=> 'cz_grid_carousel',
				]
			]
		);

		$this->add_responsive_control(
			'slidestoscroll',
			[
				'label' 	=> esc_html__( 'Slides to scroll', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> 1,
				'min' 		=> 1,
				'max' 		=> 10,
				'step' 		=> 1,
				'condition' => [
					'layout' 	=> 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' => esc_html__( 'Infinite?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' 	=> esc_html__( 'Auto play?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'autoplayspeed',
			[
				'label' => esc_html__( 'Autoplay delay (ms)', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4000,
				'min' => 1000,
				'max' => 6000,
				'step' => 500,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
				
			]
		);

		$this->add_control(
			'centermode',
			[
				'label' 	=> esc_html__( 'Center mode?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_responsive_control(
			'centerpadding',
			[
				'label' => esc_html__( 'Center padding', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_arrows',
			[
				'label' => esc_html__( 'Arrows', 'codevz' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'arrows_position',
			[
				'label' => esc_html__( 'Arrows position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'arrows_mlr',
				'options' => [
					'no_arrows' => esc_html__( 'None', 'codevz' ),
					'arrows_tl' => esc_html__( 'Both top left', 'codevz' ),
					'arrows_tc' => esc_html__( 'Both top center', 'codevz' ),
					'arrows_tr' => esc_html__( 'Both top right', 'codevz' ),
					'arrows_tlr' => esc_html__( 'Top left / right', 'codevz' ),
					'arrows_mlr' => esc_html__( 'Middle left / right', 'codevz' ),
					'arrows_blr' => esc_html__( 'Bottom left / right', 'codevz' ),
					'arrows_bl' => esc_html__( 'Both bottom left', 'codevz' ),
					'arrows_bc' => esc_html__( 'Both bottom center', 'codevz' ),
					'arrows_br' => esc_html__( 'Both bottom right', 'codevz' ),
					
				],
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'arrows_inner',
			[
				'label' 	=> esc_html__( 'Arrows inside carousel?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'arrows_show_on_hover',
			[
				'label' 	=> esc_html__( 'Show on hover?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				],
			]
		);

		$this->add_control(
			'prev_icon',
			[
				'label' => esc_html__( 'Previous icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-chevron-left',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'next_icon',
			[
				'label' => esc_html__( 'Next icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-chevron-right',
					'library' => 'solid',
				],
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);
		
		$this->end_controls_section();

		 //Dots
		 $this->start_controls_section(
			'section_dots',
			[
				'label' => esc_html__( 'Dots', 'codevz' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'dots_position',
			[
				'label' => esc_html__( 'Dots position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no_dots',
				'options' => [
					'no_dots' => esc_html__( 'None', 'codevz' ),
					'dots_tl' => esc_html__( 'Top left', 'codevz' ),
					'dots_tc' => esc_html__( 'Top center', 'codevz' ),
					'dots_tr' => esc_html__( 'Top right', 'codevz' ),
					'dots_bl' => esc_html__( 'Bottom left', 'codevz' ),
					'dots_bc' => esc_html__( 'Bottom center', 'codevz' ),
					'dots_br' => esc_html__( 'Bottom right', 'codevz' ),
					'dots_vtl' => esc_html__( 'Vertical top left', 'codevz' ),
					'dots_vml' => esc_html__( 'Vertical middle left', 'codevz' ),
					'dots_vbl' => esc_html__( 'Vertical bottom left', 'codevz' ),
					'dots_vtr' => esc_html__( 'Vertical top right', 'codevz' ),
					'dots_vmr' => esc_html__( 'Vertical middle rigth', 'codevz' ),
					'dots_vbr' => esc_html__( 'Vertical bottom right', 'codevz' ),
				],
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'dots_style',
			[
				'label' => esc_html__( 'Predefined style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'arrows_mlr',
				'options' => [
					'' => esc_html__( '~ Default ~', 'codevz' ),
					'dots_circle' => esc_html__( 'Circle', 'codevz' ),
					'dots_circle dots_circle_2' => esc_html__( 'Circle 2', 'codevz' ),
					'dots_circle_outline' => esc_html__( 'Circle outline', 'codevz' ),
					'dots_square' => esc_html__( 'Square', 'codevz' ),
					'dots_lozenge' => esc_html__( 'Lozenge', 'codevz' ),
					'dots_tiny_line' => esc_html__( 'Tiny line', 'codevz' ),
					'dots_drop' => esc_html__( 'Drop', 'codevz' ),
				],
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'dots_inner',
			[
				'label' => esc_html__( 'Dots inside carousel?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'dots_show_on_hover',
			[
				'label' => esc_html__( 'Show on hover?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_advanced',
			[
				'label' => esc_html__( 'More carousel settings', 'codevz' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'overflow_visible',
			[
				'label' => esc_html__( 'Overflow visible?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'fade',
			[
				'label' => esc_html__( 'Fade mode?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'mousewheel',
			[
				'label' => esc_html__( 'MouseWheel?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'disable_links',
			[
				'label' => esc_html__( 'Disable slides links?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'variablewidth',
			[
				'label' => esc_html__( 'Auto width detection?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'vertical',
			[
				'label' => esc_html__( 'Vertical?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'rows',
			[
				'label' => esc_html__( 'Number of rows', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 5,
				'step' => 1,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'even_odd',
			[
				'label' => esc_html__( 'Custom position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'even_odd' => esc_html__( 'Even / Odd', 'codevz' ),
					'odd_even' => esc_html__( 'Odd / Even', 'codevz' ),
				],
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->end_controls_section();

		//Cursor
		$this->start_controls_section(
			'section_cursor',
			[
				'label' => esc_html__( 'Cursor', 'codevz' ),
			]
		);
		
		$this->add_control(
			'cursor',
			[
				'label' => esc_html__( 'Cursor', 'codevz' ),
				'type' => Controls_Manager::MEDIA
			]
		);
		
		$this->add_control(
			'cursor_size',
			[
				'label' => esc_html__( 'Size', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => 'Default',
					'32' => '32x32',
					'36' => '36x36',
					'48' => '48x48',
					'64' => '64x64',
					'80' => '80x80',
					'128' => '128x128'
				],
			]
		);

		$this->end_controls_section();

		// Tilt
		$this->start_controls_section(
			'cz_title',
			[
				'label' => esc_html__( 'Tilt effect on hover', 'codevz' )
			]
		);

		$this->add_control(
			'tilt',
			[
				'label' => esc_html__( 'Tilt effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Off', 'codevz' ),
					'on' => esc_html__( 'On', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'glare',
			[
				'label' => esc_html__( 'Glare', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' 	=> '0',
					'0.2' 	=> '0.2',
					'0.4' 	=> '0.4',
					'0.6' 	=> '0.6',
					'0.8' 	=> '0.8',
					'1' 	=> '1',
				],
				'condition' => [
					'tilt' 		=> 'on'
				],
			]
		);

		$this->add_control(
			'scale',
			[
				'label' => esc_html__( 'Scale', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'0.9' 	=> '0.9',
					'0.8' 	=> '0.8',
					'1' 	=> '1',
					'1.1' 	=> '1.1',
					'1.2' 	=> '1.2',
				],
				'condition' => [
					'tilt' => 'on'
				],
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Layout
		$layout = $settings['layout'];
		$carousel = Codevz_Plus::contains( $layout, 'carousel' );

		// List
		$is_list = 0;
		if ( Codevz_Plus::contains( $layout, 'cz_posts_list_' ) ) {
			$settings['hover'] = 'cz_grid_1_title_sub_after cz_grid_1_has_excerpt';
			$is_list = 1;
		}

		// Attributes
		$data = $settings['height'] ? ' data-height="' . $settings['height'] . '"' : '';
		$data .= isset( $settings['gap']['size'] ) ? ' data-gap="' . (int) $settings['gap']['size'] . '"' : '';

		// Others var's
		$settings['post_class'] = 'cz_grid_item';
		$settings['post__in'] = $settings['post__in'] ? explode( ',', $settings['post__in'] ) : null;
		$settings['author__in'] = $settings['author__in'] ? explode( ',', $settings['author__in'] ) : null;

		// Tilt items
		$settings['tilt_data'] = Codevz_Plus::tilt( $settings );

		// Ajax data
		$ajax = array(
			'action'				=> 'cz_ajax_elementor_posts',
			'post_class'			=> $settings['post_class'],
			'post__in'				=> $settings['post__in'],
			'author__in'			=> $settings['author__in'],
			'nonce'					=> wp_create_nonce( 'posts' ),
			'nonce_id'				=> 'posts',
			'loadmore_end'			=> $settings['loadmore_end'],
			'layout'				=> $settings['layout'],
			'hover'					=> $settings['hover'],
			'subtitles'				=> $settings['subtitles'],
			'subtitle_pos'			=> $settings['subtitle_pos'],
			'icon'					=> $settings['icon'],
			'el'					=> $settings['el'],
			'title_lenght'			=> $settings['title_lenght'],
			'cat_tax'				=> $settings['cat_tax'],
			'cat'					=> $settings['cat'],
			'cat_exclude'			=> $settings['cat_exclude'],
			'tag_tax'				=> $settings['tag_tax'],
			'tag_id'				=> $settings['tag_id'],
			'tag_exclude'			=> $settings['tag_exclude'],
			'post_type'				=> $settings['post_type'],
			'posts_per_page'		=> $settings['loadmore_lenght'] ? $settings['loadmore_lenght'] : $settings['posts_per_page'],
			'order'					=> $settings['order'],
			'orderby'				=> $settings['orderby'],
			'tilt_data'				=> $settings['tilt_data'],
			'img_fx' 				=> $settings['img_fx'],
			'custom_size' 			=> '',
			'excerpt_rm' 			=> $settings['excerpt_rm']
		);

		// Search
		$settings['s'] = $ajax['s'] = isset( $_GET['s'] ) ? $_GET['s'] : $settings['s'];

		// Archive
		global $wp_query;
		$query_vars = isset( $wp_query->query_vars ) ? $wp_query->query_vars : 0;
		$query_vars = is_array( $query_vars ) ? $query_vars : 0;
		$is_query = ( ! is_singular() && $query_vars );
		if ( $is_query ) {
			$cpt = get_post_type();
			$query_vars['post_type'] = $cpt;

			if ( isset( $query_vars['taxonomy'] ) && Codevz_Plus::contains( $query_vars['taxonomy'], '_cat' ) ) {
				$settings['cat_tax'] = $ajax['cat_tax'] = $query_vars['taxonomy'];
				$term = get_term_by( 'slug', $query_vars['term'], $query_vars['taxonomy'] );
				$settings['cat'] = $ajax['cat'] = isset( $term->term_id ) ? $term->term_id : 0;
			} else if ( isset( $query_vars['taxonomy'] ) && Codevz_Plus::contains( $query_vars['taxonomy'], '_tags' ) ) {
				$settings['tag_tax'] = $ajax['tag_tax'] = $query_vars['taxonomy'];
				$term = get_term_by( 'slug', $query_vars['term'], $query_vars['taxonomy'] );
				$settings['tag_id'] = $ajax['tag_id'] = isset( $term->term_id ) ? $term->term_id : 0;
			}

			$ajax = wp_parse_args( array_filter( $query_vars ), $ajax );
		}

		// Ajax data
		$data .= " data-atts='" . json_encode( $ajax, JSON_HEX_APOS ) . "'";

		// Animation data
		$data .= ( $settings['animation'] && ! Codevz_Plus::contains( $layout, 'carousel' ) ) ? ' data-animation="' . $settings['animation'] . '"' : '';

		// Out
		$out = '<div class="cz_grid_p">';

		// Filters
		if ( $settings['filters'] && ! $carousel ) {

			$settings['filters_pos'] .= $settings['filters_items_count'] ? ' cz_grid_filters_count ' . $settings['filters_items_count'] : '';
			$out .= '<ul class="cz_grid_filters clr ' . $settings['filters_pos'] . '">';
			$out .= $settings['browse_all'] ? '<li class="cz_active_filter" data-filter=".cz_grid_item">' . $settings['browse_all'] . '</li>' : '';

			foreach( $settings['filters'] as $filter ) {

				$cat = ( $settings['post_type'] === 'post' ) ? 'category' : $settings['post_type'] . '_cat';
				$tag = ( $settings['post_type'] === 'post' ) ? 'post_tag' : $settings['post_type'] . '_tags';

				if ( isset( $settings[ 'filters_tax' ] ) && $settings[ 'filters_tax' ] !== 'category' ) {
					$cat = $settings[ 'filters_tax' ];
					$tag = $settings[ 'filters_tax' ];
				}

				if ( $cat == '_cat' ) {
					$cat = 'category';
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
		$classes[] = $settings['hover'];
		$classes[] = $settings['hover_pos'];
		$classes[] = $settings['hover_vis'];
		$classes[] = $settings['hover_fx'];
		$classes[] = $settings['overlay_outer_space'];
		$classes[] = $settings['subtitle_pos'];
		$classes[] = $settings['tilt_data'] ? 'cz_grid_tilt' : '';
		$classes[] = $settings['single_line_title'] ? 'cz_single_line_title' : '';
		$classes[] = $settings['two_columns_on_mobile'] ? 'cz_grid_two_columns_on_mobile' : '';

		if ( isset( $settings['sk_overlay']['normal'] ) ) {
			$classes[] = Codevz_Plus::contains( $settings['sk_overlay']['normal'], 'border-color' ) ? 'cz_grid_overlay_border' : '';
		}

		$classes[] = Codevz_Plus::contains( $settings['hover_pos'], 'tac' ) ? 'cz_meta_all_center' : '';

		// Posts
		$out .= '<div' . Codevz_Plus::classes( [], $classes ) . $data . '>';
		$out .= ( $layout !== 'cz_justified' ) ? '<div class="cz_grid_item cz_grid_first"></div>' : '';
		if ( $is_query ) {
			$settings['wp_query'] = 1;
			$settings = wp_parse_args( array_filter( $query_vars ), $settings );
		}

		$out .= Xtra_Elementor::posts_grid_items( $settings );
		$out .= '</div>';

		// Ajax pagination
		if ( $settings['layout'] !== 'cz_grid_carousel' && $settings['loadmore'] && $settings['loadmore'] !== 'pagination' && $settings['loadmore'] !== 'older' ) {
			$out .= '<div class="cz_ajax_pagination clr cz_ajax_' . $settings['loadmore'] . ' ' . $settings['loadmore_pos'] . '"><a href="#">' . $settings['loadmore_title'] . '</a></div>';
		}

		$out .= '</div>'; // ID

		// Carousel mode
		if ( $carousel ) {

			Xtra_Elementor::carousel_elementor( $settings, $out );

		} else {

			echo $out;

		}

		if ( ! empty( $settings[ 'cursor' ][ 'id' ] ) ) {
			echo '<style>.cz_grid_link{cursor: url("' . Group_Control_Image_Size::get_attachment_image_src( $settings[ 'cursor' ][ 'id' ], 'cursor', $settings ) . '") ' . ( $settings[ 'cursor_size' ] / 2 . ' ' . $settings[ 'cursor_size' ] / 2 ) . ', auto}</style>';
		}

		Xtra_Elementor::render_js( 'grid' );

	}

}