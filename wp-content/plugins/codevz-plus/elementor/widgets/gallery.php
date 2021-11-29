<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_gallery extends Widget_Base {

	protected $id = 'cz_gallery';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Gallery', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-gallery';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Images', 'codevz' ),
			esc_html__( 'Gallery', 'codevz' ),
			esc_html__( 'Photos', 'codevz' ),
			esc_html__( 'Carousel', 'codevz' ),
			esc_html__( 'Grid', 'codevz' ),
			esc_html__( 'Masonry', 'codevz' ),
			esc_html__( 'Isotope', 'codevz' ),

		];

	}
	
	public function get_style_depends() {

		$array = [ $this->id, 'codevz-tilt', 'cz_carousel', 'cz_parallax' ];

		if ( Codevz_Plus::$is_rtl ) {
			$array[] = $this->id . '_rtl';
		}

		return $array;

	}

	public function get_script_depends() {
		return [ $this->id, 'cz_carousel', 'cz_parallax', 'codevz-tilt' ];
	}

	public function register_controls() {

		$this->start_controls_section(
			'section_gallery',
			[
				'label' => esc_html__( 'Gallery', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Gallery type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'gallery',
				'options' => [
					'gallery'  => esc_html__( 'Photo Gallery', 'codevz' ),
					'gallery2' => esc_html__( 'Linkable Gallery', 'codevz' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Codevz_Plus::$url . 'assets/img/p.svg',
				],
			]
		);

		$repeater->add_control(
			'title', 
			[
				'label' => esc_html__( 'Title', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'info', 
			[
				'label' => esc_html__( 'Caption', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'codevz' ),
				'description'  => esc_html__( 'For opening in lightbox use #', 'codevz' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://xtratheme.com',
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$repeater->add_control(
			'class', 
			[
				'label' => esc_html__( 'Filter(s)', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'badge', 
			[
				'label' => esc_html__( 'Badge', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_responsive_control(
			'sk_badge',
			[
				'label' 	=> esc_html__( 'Badge', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '{{CURRENT_ITEM}} .cz_gallery_badge' ),
			]
		);

		$this->add_control(
			'gallery2',
			[
				'label' => esc_html__( 'Add images', 'codevz' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'condition' => [
					'type' => 'gallery2',
				],
			]
		);

		$this->add_control(
			'target',
			[
				'label' => esc_html__( 'Click mode', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Open in new tab', 'codevz' ),
					'1' => esc_html__( 'Open in same tab', 'codevz' ),
				],
				'condition' => [
					'type' => 'gallery2',
				]
			]
		);

		$this->add_control(
			'images',
			[
				'label' => __( 'Images', 'codevz' ),
				'type' => Controls_Manager::GALLERY,
				'condition' => [
					'type' => 'gallery',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'codevz_600_600',
			]
		);

		$this->add_control(
			'two_columns_on_mobile',
			[
				'label' => esc_html__( 'Two columns on mobile?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout' , 'codevz' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'codevz' ),
				'type' => 'image_select',
				'label_block' => true,
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
				),
				'default' => 'cz_grid_c4',
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
			'section_settings',
			[
				'label' => esc_html__( 'Settings' , 'codevz' ),
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

		$this->add_responsive_control(
			'gap',
			[
				'label' => esc_html__( 'Images gap', 'codevz' ),
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
					'{{WRAPPER}} .cz_grid' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2);margin-right: calc(-{{SIZE}}{{UNIT}} / 2);margin-bottom: -{{SIZE}}{{UNIT}};width: calc(100% + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .cz_grid .cz_grid_item > div' => 'margin:0 calc({{SIZE}}{{UNIT}} / 2) {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-slide' => 'margin:0 calc({{SIZE}}{{UNIT}} / 2);',
				]
			]
		);

		$this->add_control(
			'hover',
			[
				'label' => esc_html__( 'Hover Style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_grid_1_no_title',
				'options' => [
					'cz_grid_1_no_hover'  => esc_html__( 'No hover', 'codevz' ),
					'cz_grid_1_no_title' => esc_html__( 'Overlay only icon', 'codevz' ),
					'cz_grid_1_no_desc' => esc_html__( 'Overlay icon and title', 'codevz' ),
					'cz_grid_1_yes_all' => esc_html__( 'Overlay icon, title and description', 'codevz' ),
					'cz_grid_1_no_title cz_grid_1_w_info' => esc_html__( 'Overlay icon and description', 'codevz' ),
					'cz_grid_1_no_icon cz_grid_1_no_desc' => esc_html__( 'Overlay title', 'codevz' ),
					'cz_grid_1_w_info cz_grid_1_no_icon cz_grid_1_no_title' => esc_html__( 'Overlay description', 'codevz' ),
					'cz_grid_1_no_icon' => esc_html__( 'Overlay title and description', 'codevz' ),
					'cz_grid_1_title_sub_after cz_grid_1_no_hover' => esc_html__( 'No hover, title and description after image', 'codevz' ),
					'cz_grid_1_title_sub_after' => esc_html__( 'Overlay icon - title and description after image', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'grid_disable_links',
			[
				'label' => esc_html__( 'Disable links?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title Tag', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'h3' 			=> 'h3',
					'h4' 			=> 'h4',
					'h5' 			=> 'h5',
					'h6' 			=> 'h6',
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
				'label' => esc_html__( 'Description position?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Description after title', 'codevz' ),
					'cz_grid_1_title_rev' => esc_html__( 'Description before title', 'codevz' ),
				],
				'condition' => [
					'hover' => [
						'cz_grid_1_yes_all',
						'cz_grid_1_no_title cz_grid_1_w_info',
						'cz_grid_1_title_sub_after',
						'cz_grid_1_title_sub_after cz_grid_1_no_hover',
						'cz_grid_1_no_icon',
						'cz_grid_1_w_info cz_grid_1_no_icon cz_grid_1_no_title ',
					],
				],
			]
		);

		$this->add_control(
			'hover_pos',
			[
				'label' => esc_html__( 'Details align', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_grid_1_mid tac',
				'options' => [
					'cz_grid_1_top tal'  => esc_html__( 'Top Left', 'codevz' ),
					'cz_grid_1_top tac' => esc_html__( 'Top Center', 'codevz' ),
					'cz_grid_1_top tar' => esc_html__( 'Top Right', 'codevz' ),
					'cz_grid_1_mid tal' => esc_html__( 'Middle Left', 'codevz' ),
					'cz_grid_1_mid tac' => esc_html__( 'Middle Center', 'codevz' ),
					'cz_grid_1_mid tar' => esc_html__( 'Middle Right', 'codevz' ),
					'cz_grid_1_bot tal' => esc_html__( 'Bottom Left', 'codevz' ),
					'cz_grid_1_bot tac' => esc_html__( 'Bottom Center', 'codevz' ),
					'cz_grid_1_bot tar' => esc_html__( 'Bottom Right', 'codevz' ),
				],
				'condition' => [
					'hover!' => [
						'cz_grid_1_no_hover',
						'cz_grid_1_title_sub_after cz_grid_1_no_hover',
					],
				],
			]
		);

		$this->add_control(
			'hover_vis',
			[
				'label' => esc_html__( 'Hover visibility', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__( 'Show overlay on hover', 'codevz' ),
					'cz_grid_1_hide_on_hover' => esc_html__( 'Hide overlay on hover', 'codevz' ),
					'cz_grid_1_always_show' => esc_html__( 'Always show overlay details', 'codevz' ),
				],
				'condition' => [
					'hover!' => [
						'cz_grid_1_no_hover',
						'cz_grid_1_title_sub_after cz_grid_1_no_hover',
					],
				],
			]
		);

		$this->add_control(
			'hover_fx',
			[
				'label' => esc_html__( 'Hover effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Fade In Top', 'codevz' ),
					'cz_grid_fib' => esc_html__( 'Fade In Bottm', 'codevz' ),
					'cz_grid_fil' => esc_html__( 'Fade In Left', 'codevz' ),
					'cz_grid_fir' => esc_html__( 'Fade In Right', 'codevz' ),
					'cz_grid_zin' => esc_html__( 'Zoom In', 'codevz' ),
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
					],
				],
			]
		);

		$this->add_control(
			'img_fx',
			[
				'label' => esc_html__( 'Hover image effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_grid_inset_clip_1x' => esc_html__( 'Inset Mask 1x', 'codevz' ),
					'cz_grid_inset_clip_2x' => esc_html__( 'Inset Mask 2x', 'codevz' ),
					'cz_grid_inset_clip_3x' => esc_html__( 'Inset Mask 3x', 'codevz' ),
					'cz_grid_zoom_mask' => esc_html__( 'Zoom Mask', 'codevz' ),
					'cz_grid_scale' => esc_html__( 'Scale', 'codevz' ),
					'cz_grid_scale2' => esc_html__( 'Scale 2', 'codevz' ),
					'cz_grid_rhombus' => esc_html__( 'Rhombus', 'codevz' ),
					'cz_grid_rhombus_hover' => esc_html__( 'Rhombus on hover', 'codevz' ),
					'cz_grid_grayscale' => esc_html__( 'Grayscale', 'codevz' ),
					'cz_grid_grayscale_on_hover' => esc_html__( 'Grayscale on hover', 'codevz' ),
					'cz_grid_grayscale_remove' => esc_html__( 'Remove Grayscale', 'codevz' ),
					'cz_grid_blur' => esc_html__( 'Blur', 'codevz' ),
					'cz_grid_blur_others' => esc_html__( 'Blur others', 'codevz' ),
					'cz_grid_zoom_in' => esc_html__( 'ZoomIn', 'codevz' ),
					'cz_grid_zoom_out' => esc_html__( 'ZoomOut', 'codevz' ),
					'cz_grid_zoom_rotate' => esc_html__( 'Zoom Roate', 'codevz' ),
					'cz_grid_zoom_rotate' => esc_html__( 'Flash', 'codevz' ),
					'cz_grid_zoom_rotate' => esc_html__( 'Shine', 'codevz' ),
				],
				'condition' => [
					'hover!' => [
						'cz_grid_1_no_hover', 
						'cz_grid_1_title_sub_after cz_grid_1_no_hover',
					],
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fa fa-search',
					'library' => 'solid',
				],
				'condition' => [
					'hover' => [
						'cz_grid_1_no_title',
						'cz_grid_1_no_desc',
						'cz_grid_1_yes_all',
						'cz_grid_1_title_sub_after',
						'cz_grid_1_no_title cz_grid_1_w_info',
					],
				],
			]
		);

		$this->add_control(
			'title_limit',
			[
				'label' => esc_html__( 'Limit title words', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'range' => [
					'' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'hover!' => [
						'cz_grid_1_no_hover',
						'cz_grid_1_no_title',
						'cz_grid_1_no_title cz_grid_1_w_info',
						'cz_grid_1_w_info cz_grid_1_no_icon cz_grid_1_no_title'
					],
				],
			]
		);

		$this->add_control(
			'overlay_outer_space',
			[
				'label' => esc_html__( 'Overlay scale', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( '~ Default ~', 'codevz' ),
					'cz_grid_overlay_5px' 	=> '1',
					'cz_grid_overlay_10px'  => '2',
					'cz_grid_overlay_15px'  => '3',
					'cz_grid_overlay_20px'  => '4',
				],
			]
		);

		$this->end_controls_section();

		//General
		$this->start_controls_section(
			'section_carousel',
			[
				'label' => esc_html__( 'Carousel', 'codevz' ),
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'slidestoshow',
			[
				'label' => esc_html__( 'Slides to show', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'default' => 3,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'slidestoshow_tablet',
			[
				'label' => esc_html__( 'Slides on Tablet', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'default' => 2,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'slidestoshow_mobile',
			[
				'label' => esc_html__( 'Slides on Mobile', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'default' => 1,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_responsive_control(
			'slidestoscroll',
			[
				'label' => esc_html__( 'Slides to scroll', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'default' => 1,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' 	=> esc_html__( 'Infinite?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
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

		$this->add_responsive_control(
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

		//Arrows
		$this->start_controls_section(
			'section_arrows',
			[
				'label' => esc_html__( 'Carousel arrows', 'codevz' ),
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
					'arrows_position' => [
						'arrows_tl',
						'arrows_tc',
						'arrows_tr',
						'arrows_tlr',
						'arrows_mlr',
						'arrows_blr',
						'arrows_bl',
						'arrows_bc',
						'arrows_br',
					],
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
					'arrows_position' => [
						'arrows_tl',
						'arrows_tc',
						'arrows_tr',
						'arrows_tlr',
						'arrows_mlr',
						'arrows_blr',
						'arrows_bl',
						'arrows_bc',
						'arrows_br',
					],
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'prev_icon',
			[
				'label' => esc_html__( 'Previous icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fa fa-chevron-left',
					'library' => 'solid',
				],
				'condition' => [
					'arrows_position' => [
						'arrows_tl',
						'arrows_tc',
						'arrows_tr',
						'arrows_tlr',
						'arrows_mlr',
						'arrows_blr',
						'arrows_bl',
						'arrows_bc',
						'arrows_br',
					],
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'next_icon',
			[
				'label' => esc_html__( 'Next icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fa fa-chevron-right',
					'library' => 'solid',
				],
				'condition' => [
					'arrows_position' => [
						'arrows_tl',
						'arrows_tc',
						'arrows_tr',
						'arrows_tlr',
						'arrows_mlr',
						'arrows_blr',
						'arrows_bl',
						'arrows_bc',
						'arrows_br',
					],
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->end_controls_section();

		//Dots
		$this->start_controls_section(
			'section_dots',
			[
				'label' => esc_html__( 'Carousel dots', 'codevz' ),
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
					'dots_position' => [
						'dots_tl',
						'dots_tc',
						'dots_tr',
						'dots_bl',
						'dots_bc',
						'dots_br',
						'dots_vtl',
						'dots_vml',
						'dots_vbl',
						'dots_vtr',
						'dots_vmr',
						'dots_vbr',
					],
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
					'dots_position' => [
						'dots_tl',
						'dots_tc',
						'dots_tr',
						'dots_bl',
						'dots_bc',
						'dots_br',
						'dots_vtl',
						'dots_vml',
						'dots_vbl',
						'dots_vtr',
						'dots_vmr',
						'dots_vbr',
					],
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
					'dots_position' => [
						'dots_tl',
						'dots_tc',
						'dots_tr',
						'dots_bl',
						'dots_bc',
						'dots_br',
						'dots_vtl',
						'dots_vml',
						'dots_vbl',
						'dots_vtr',
						'dots_vmr',
						'dots_vbr',
					],
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->end_controls_section();

		// Column
		$this->start_controls_section(
			'section_more_carousel_settings',
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
				'description' => esc_html__('Only works when slide to show is 1', 'codevz'),
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

		$this->add_control(
			'selector', [
				'label' => esc_html__( 'Sync class', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'layout' => 'cz_grid_carousel',
				]
			]
		);

		$this->add_control(
			'sync', [
				'label' => esc_html__( 'Sync class 2', 'codevz' ),
				'type' => Controls_Manager::TEXT,
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
					'0' => esc_html__( '~ Default ~', 'codevz' ),
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

		//Filter
		$this->start_controls_section(
			'section_filter',
			[
				'label' 	=> esc_html__( 'Filter & Search', 'codevz' ),
				'condition' => [
					'type' 		=> 'gallery2',
				],
			]
		);

		$this->add_control(
			'filters_pos',
			[
				'label' 	=> esc_html__( 'Position', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> '',
				'options' 	=> [
					''  		=> esc_html__( '~ Default ~', 'codevz' ),
					'hidden' 	=> esc_html__( 'None', 'codevz' ),
					'tal' 		=> esc_html__( 'Left', 'codevz' ),
					'tac' 		=> esc_html__( 'Center', 'codevz' ),
					'tar' 		=> esc_html__( 'Right', 'codevz' ),
				],
				'condition' => [
					'type' 		=> 'gallery2',
				],
			]
		);

		$this->add_control(
			'browse_all',
			[
				'label' => esc_html__( 'Show all', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Show all', 'codevz' ),
				'condition' => [
					'type' => 'gallery2',
				],
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
				],
				'condition' => [
					'type' => 'gallery2',
				],
			]
		);

		$this->add_control(
			'search',
			[
				'label' 	=> esc_html__( 'Search input', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'condition' => [
					'type' 		=> 'gallery2',
				],
			]
		);

		$this->add_control(
			'search_placeholder',
			[
				'label' 	=> esc_html__( 'Search placeholder', 'codevz' ),
				'type' 		=> Controls_Manager::TEXT,
				'Default' 	=> esc_html__( 'Search', 'codevz' ),
				'condition' => [
					'type' 		=> 'gallery2',
				],
			]
		);

		$this->end_controls_section();

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
					'tilt' => 'on'
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

		//Style
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_p' ),
			]
		);

		$this->add_responsive_control(
			'sk_overall',
			[
				'label' 	=> esc_html__( 'Gallery items', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
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
					],
				],
			]
		);

		$this->add_responsive_control(
			'sk_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_grid_icon' ),
				'condition' => [
					'hover' => [
						'cz_grid_1_no_title',
						'cz_grid_1_no_desc',
						'cz_grid_1_yes_all',
						'cz_grid_1_title_sub_after',
						'cz_grid_1_no_title cz_grid_1_w_info',
					],
				],
			]
		);

		$this->add_responsive_control(
			'sk_title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background', 'padding' ],
				'selectors' => Xtra_Elementor::sk_selectors(
					'.cz_grid .cz_grid_details h3,.cz_grid .cz_grid_details h4,.cz_grid .cz_grid_details h5,.cz_grid .cz_grid_details h6',
					'.cz_grid .cz_grid_item:hover .cz_grid_details h3,.cz_grid .cz_grid_item:hover .cz_grid_details h4,.cz_grid .cz_grid_item:hover .cz_grid_details h5,.cz_grid .cz_grid_item:hover .cz_grid_details h6'
				),
			]
		);

		$this->add_responsive_control(
			'sk_subtitle',
			[
				'label' 	=> esc_html__( 'Description', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'text-transform', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_grid_details small', 'cz_grid .cz_grid_item:hover .cz_grid_details small' ),
				'condition' => [
					'hover' => [
						'cz_grid_1_yes_all',
						'cz_grid_1_no_title cz_grid_1_w_info',
						'cz_grid_1_title_sub_after',
						'cz_grid_1_title_sub_after cz_grid_1_no_hover',
						'cz_grid_1_no_icon', 
						'cz_grid_1_w_info cz_grid_1_no_icon cz_grid_1_no_title ',
					],
				],
			]
		);

		$this->add_responsive_control(
			'sk_badge',
			[
				'label' 	=> esc_html__( 'All badges', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'text-transform', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid .cz_gallery_badge', '.cz_grid_item:hover .cz_gallery_badge' ),
				'condition' => [
					'type' => 'gallery2',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_filters',
			[
				'label' => esc_html__( 'Filters & Search', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'type' => 'gallery2',
				],
			]
		);

		$this->add_responsive_control(
			'sk_filters_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_filters' ),
				'condition' => [
					'type' => 'gallery2',
				],
			]
		);

		$this->add_responsive_control(
			'sk_filters',
			[
				'label' 	=> esc_html__( 'Filters', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_filters li' ),
				'condition' => [
					'type' => 'gallery2',
				],
			]
		);

		$this->add_responsive_control(
			'sk_filter_active',
			[
				'label' 	=> esc_html__( 'Active filter', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_filters .cz_active_filter' ),
				'condition' => [
					'type' => 'gallery2',
				],
			]
		);

		$this->add_responsive_control(
			'sk_filters_sep',
			[
				'label' 	=> esc_html__( 'Filters delimiter', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'content', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_filters li:after' ),
				'condition' => [
					'type' => 'gallery2',
				],
			]
		);

		$this->add_responsive_control(
			'sk_search',
			[
				'label' 	=> esc_html__( 'Search', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_grid_search' ),
				'condition' => [
					'type' => 'gallery2',
				],
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
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		// Layout
		$layout = $settings['layout'];
		$carousel = Codevz_Plus::contains( $layout, 'carousel' );

		// Attributes
		$data = empty( $settings['height'][ 'size' ] ) 	? '' : ' data-height="' . $settings['height'][ 'size' ] . '"';
		$data .= empty( $settings['gap'][ 'size' ] ) 	? '' : ' data-gap="' . $settings['gap'][ 'size' ] . '"';

		// Animation data
		$data .= ( $settings['animation'] && ! Codevz_Plus::contains( $layout, 'carousel' ) ) ? ' data-animation="' . $settings['animation'] . '"' : '';

		// Out
		$out = '<div class="cz_grid_p">';

		// Tilt items
		$settings['tilt_data'] = Codevz_Plus::tilt( $settings );

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
		$classes[] = $settings['two_columns_on_mobile'] ? 'cz_grid_two_columns_on_mobile' : '';
		$classes[] = $settings['grid_disable_links'] ? 'cz_grid_disable_links' : '';
		$classes[] = $settings['img_fx'] === 'cz_grid_blur_others' ? 'cz_grid_blur_others' : '';
		
		if ( isset( $settings['sk_overlay']['normal'] ) ) {
			$classes[] = Codevz_Plus::contains( $settings['sk_overlay']['normal'], 'border-color' ) ? 'cz_grid_overlay_border' : '';
		}

		$custom_items = [];

		if ( is_array( $settings[ 'custom_items' ] ) ) {

			foreach( $settings[ 'custom_items' ] as $item ) {

				$custom_items[ $item[ 'position' ] - 1 ] = $item;

			}

		}

		$nn = 0;

		// Gallery 2 foreach
		if ( $settings['type'] === 'gallery2' ) {

			$gallery2_out = '';
			$filters = array();

			foreach ( $settings['gallery2'] as $i ) {

				if ( isset( $custom_items[ $nn ] ) ) {

					$out .= '<div class="cz_grid_item elementor-repeater-item-' . esc_attr( $custom_items[ $nn ][ '_id' ] ) . '"><div class="clr">';

					if ( $custom_items[ $nn ][ 'type' ] === 'template' ) {

						$out .= Codevz_Plus::get_page_as_element( $custom_items[ $nn ][ 'xtra_elementor_template' ] );

					} else {

						$out .= do_shortcode( $custom_items[ $nn ][ 'content' ] );

					}

					$out .= '</div></div>';

				}

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
				$link = isset( $i['link']['url'] ) ? $i['link']['url'] : '';
				$link = ( ! $link || $link === '#' ) ? $i[ 'image' ][ 'url' ] : $link;

				$new_settings = wp_parse_args( [ 'image' => $i[ 'image' ] ], $settings );

				$gallery2_out .= self::get_gallery_item(
					Group_Control_Image_Size::get_attachment_image_html( $new_settings ), 
					$link, 
					Codevz_Plus::limit_words( ( isset( $i['title'] ) ? $i['title'] : '' ), $settings['title_limit'] ? $settings['title_limit'] : 999 ), 
					( isset( $i['info'] ) ? $i['info'] : '' ), 
					$settings, 'elementor-repeater-item-' . esc_attr( $i[ '_id' ] ) . ' ' . $cls, $settings['img_fx'], $badge, $sk_badge
				);

				$nn++;
			}

			// Filters
			if ( ! empty( $filters ) && ! $carousel ) {
				$settings['filters_pos'] .= $settings['filters_items_count'] ? ' cz_grid_filters_count ' . $settings['filters_items_count'] : '';
				$out .= '<ul class="cz_grid_filters clr ' . $settings['filters_pos'] . '">';
				$out .= $settings['browse_all'] ? '<li class="cz_active_filter" data-filter=".cz_grid_item">' . $settings['browse_all'] . '</li>' : '';
				foreach ( $filters as $a => $b ) {
					$out .= '<li data-filter=".' . $b . '">' . ucfirst( str_replace( array( '_', '-' ), ' ', $b ) ) . '</li>';
				}
				$out .= '</ul>';
			}
		}

		// Search data
		$data .= $settings['search'] ? ' data-search="' . $settings['search_placeholder'] . '"' : '';

		// Items
		$out .= '<div' . Codevz_Plus::classes( [], $classes ) . $data . '>';
		$out .= ( $layout !== 'cz_justified' ) ? '<div class="cz_grid_item cz_grid_first"></div>' : '';

		if ( $settings['type'] === 'gallery2' ) {

			$out .= $gallery2_out;

		} else {

			$images = $settings['images'];
			foreach( $images as $image ) {

				if ( isset( $custom_items[ $nn ] ) ) {

					$out .= '<div class="cz_grid_item elementor-repeater-item-' . esc_attr( $custom_items[ $nn ][ '_id' ] ) . '"><div class="clr">';

					if ( $custom_items[ $nn ][ 'type' ] === 'template' ) {

						$out .= Codevz_Plus::get_page_as_element( $custom_items[ $nn ][ 'xtra_elementor_template' ] );

					} else {

						$out .= do_shortcode( $custom_items[ $nn ][ 'content' ] );

					}

					$out .= '</div></div>';

				}

				if ( empty( $image[ 'id' ] ) ) {
					continue;
				}

				if ( function_exists( 'icl_object_id' ) ) {
					$image = icl_object_id( $image[ 'id' ], 'attachment', true, ICL_LANGUAGE_CODE );
				}

				$title = get_post( $image[ 'id' ] );
				$class = '';

				if ( is_object( $title ) ) {

					$new_settings = wp_parse_args( [ 'image' => $image ], $settings );

					$out .= self::get_gallery_item(
						Group_Control_Image_Size::get_attachment_image_html( $new_settings ), 
						$image[ 'url' ], 
						Codevz_Plus::limit_words( $title->post_title, $settings['title_limit'] ? $settings['title_limit'] : 999 ), 
						$title->post_content, 
						$settings, 
						$class, 
						$settings['img_fx']
					);
				}

				$nn++;
			}

		}

		$out .= '</div>';
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

		$badge = $badge ? '<div class="cz_gallery_badge">' . $badge . '</div>' : '';
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

		// Icon.
		ob_start();
		Icons_Manager::render_icon( $atts['icon'], [ 'class' => 'cz_grid_icon' ] );
		$icon = ob_get_clean();

		if ( Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_title_sub_after' ) ) {
			if ( Codevz_Plus::contains( $atts['hover'], 'cz_grid_1_subtitle_on_img' ) ) {
				$out .= '<div class="cz_grid_details">' . $small_a . $small_b . '</div>';
				$small_a = $small_b = '';
			} else {
				$out .= '<div class="cz_grid_details">' . $icon . '</div>';
			}

			$det = '<div class="cz_grid_details cz_grid_details_outside">' . $small_a . '<a class="cz_grid_title" href="' . $bi . '">' . $t . '</a>' . $small_b . '</div>';
		} else {
			$out .= '<div class="cz_grid_details">' . $icon . $small_a . $t . $small_b . '</div>';
		}
		$out .= '</a>'. $det . '</div></div>';

		return $out;
	}

}