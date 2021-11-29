<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_carousel extends Widget_Base {

	protected $id = 'cz_carousel';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Carousel', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-carousel';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Carousel', 'codevz' ),
			esc_html__( 'Slider', 'codevz' ),

		];

	}

	public function get_style_depends() {
		return [ $this->id, 'cz_parallax' ];
	}

	public function get_script_depends() {
		return [ $this->id, 'cz_parallax' ];
	}

	protected function register_controls() {

		//General
		$this->start_controls_section(
			'section_carousel',
			[
				'label' => esc_html__( 'Carousel', 'codevz' ),
			]
		);

		$repeater = new Repeater();

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

		$repeater->add_responsive_control(
			'sk_slide',
			[
				'label' 	=> esc_html__( 'Slide Style', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '{{CURRENT_ITEM}}' )
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

		$this->add_control(
			'items',
			[
				'label' => esc_html__( 'Add Slide(s)', 'codevz' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls()
			]
		);

		$this->add_responsive_control(
			'slidestoshow',
			[
				'label' => esc_html__( 'Slides to show', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'default' => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
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
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => esc_html__( 'Slides gap', 'codevz' ),
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
						'step' => 1
					]
				],
				'selectors' => [
					'{{WRAPPER}} .slick-list' 	=> 'margin: 0 -calc({{SIZE}}{{UNIT}} / 2);clip-path:inset(0 calc({{SIZE}}{{UNIT}} / 2) 0 calc({{SIZE}}{{UNIT}} / 2));',
					'{{WRAPPER}} .slick-slide' 	=> 'margin: 0 calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' 	=> esc_html__( 'Infinite?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' 	=> esc_html__( 'Auto play?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
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
					'autoplay!' => ''
				]
			]
		);

		$this->add_control(
			'centermode',
			[
				'label' 	=> esc_html__( 'Center mode?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'centerpadding',
			[
				'label' => esc_html__( 'Center padding', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'centermode!' => ''
				]
			]
		);

		$this->end_controls_section();

		//Arrows
		$this->start_controls_section(
			'section_arrows',
			[
				'label' => esc_html__( 'Arrows', 'codevz' ),
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
				],
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
				],
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
				],
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
				],
			]
		);
		
		$this->end_controls_section();

		// Dots
		$this->start_controls_section(
			'section_counts',
			[
				'label' => esc_html__( 'Counts', 'codevz' ),
			]
		);

		$this->add_control(
			'counts',
			[
				'label' => esc_html__( 'Counts', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);
		
		$this->end_controls_section();

		// Dots
		$this->start_controls_section(
			'section_dots',
			[
				'label' => esc_html__( 'Dots', 'codevz' ),
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
			]
		);

		$this->add_control(
			'dots_style',
			[
				'label' => esc_html__( 'Predefined style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
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
				],
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
				],
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
				],
			]
		);

		$this->end_controls_section();

		//Column
		$this->start_controls_section(
			'section_more_settings',
			[
				'label' => esc_html__( 'More settings', 'codevz' ),
			]
		);

		$this->add_control(
			'overflow_visible',
			[
				'label' => esc_html__( 'Overflow visible?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'fade',
			[
				'label' => esc_html__( 'Fade mode?', 'codevz' ),
				'description' => esc_html__('Only works when slide to show is 1', 'codevz'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'mousewheel',
			[
				'label' => esc_html__( 'MouseWheel?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'disable_links',
			[
				'label' => esc_html__( 'Disable slides links?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'variablewidth',
			[
				'label' => esc_html__( 'Auto width detection?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'vertical',
			[
				'label' => esc_html__( 'Vertical?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
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
			]
		);

		$this->add_control(
			'even_odd',
			[
				'label' => esc_html__( 'Custom position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'even_odd' => esc_html__( 'Even / Odd', 'codevz' ),
					'odd_even' => esc_html__( 'Odd / Even', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'selector', [
				'label' => esc_html__( 'Sync class', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'sync', [
				'label' => esc_html__( 'Sync class 2', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_overall',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.slick' ),
			]
		);

		$this->add_responsive_control(
			'sk_slides',
			[
				'label' 	=> esc_html__( 'Slides', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'grayscale', 'blur', 'background', 'opacity', 'z-index', 'padding', 'margin', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( 'div.slick-slide' ),
			]
		);

		$this->add_responsive_control(
			'sk_center',
			[
				'label' 	=> esc_html__( 'Center slide', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'grayscale', 'background', 'opacity', 'z-index', 'padding', 'margin', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( 'div.slick-center' ),
			]
		);

		$this->add_responsive_control(
			'sk_prev_icon',
			[
				'label' 	=> esc_html__( 'Previous icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.slick-prev' )
			]
		);

		$this->add_responsive_control(
			'sk_next_icon',
			[
				'label' 	=> esc_html__( 'Next icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.slick-next' )
			]
		);

		$this->add_responsive_control(
			'sk_dots_container',
			[
				'label' 	=> esc_html__( 'Dots container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.slick-dots' ),
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
				],
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
				],
			]
		);

		$this->add_responsive_control(
			'sk_counts',
			[
				'label' 	=> esc_html__( 'Counts', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.xtra-slick-counts' ),
				'condition' => [
					'counts!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'sk_counts_numbers',
			[
				'label' 	=> esc_html__( 'Counts numbers', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.xtra-slick-counts .xtra-slick-current, .xtra-slick-counts .xtra-slick-all' ),
				'condition' => [
					'counts!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'sk_counts_seperator',
			[
				'label' 	=> esc_html__( 'Counts seperator', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.xtra-slick-counts .xtra-slick-seperator' ),
				'condition' => [
					'counts!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		Xtra_Elementor::carousel_elementor( $settings );

	}

}