<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_free_position_element extends Widget_Base {

	protected $id = 'cz_free_position_element';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Free Position Element', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-free-position-element';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Position', 'codevz' ),
			esc_html__( 'Place', 'codevz' ),
			esc_html__( 'Location', 'codevz' ),
			esc_html__( 'Absolute', 'codevz' ),

		];

	}

	public function get_style_depends() {
		return [ $this->id, 'cz_parallax' ];
	}

	public function get_script_depends() {
		return [ $this->id, 'cz_parallax' ];
	}

	public function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'css_top',
			[
				'label' => esc_html__( 'Top offset', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_free_position_element' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'css_left',
			[
				'label' => esc_html__( 'Left offset', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_free_position_element' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'css_width',
			[
				'label' => esc_html__( 'Custom Width', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_free_position_element' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'type', [
				'label' 	=> esc_html__( 'Content type', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> [
					'' 			=> esc_html__( 'Content', 'codevz' ),
					'template' 	=> esc_html__( 'Saved template', 'codevz' ),
				]
			]
		);

		$this->add_control(
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

		$this->add_responsive_control(
			'css_transform',
			[
				'label' => esc_html__( 'Custom Rotate', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'deg',
				],
				'size_units' => [ 'deg' ],
				'range' => [
					'deg' => [
						'min' => 0,
						'max' => 360,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_free_position_element > div' => 'transform: rotate( {{SIZE}}{{UNIT}} );',
				],
			]
		);

		$this->add_control(
			'css_z-index',
			[
				'label' => esc_html__( 'Layer Priority', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'-2'  => '-2',
					'-1'  => '-1',
					'0'  => '0',
					'1'  => '1',
					'2'  => '2',
					'3'  => '3',
					'4'  => '4',
					'5'  => '5',
					'6'  => '6',
					'7'  => '7',
					'8'  => '8',
					'9'  => '9',
					'10'  => '10',
					'99'  => '99',
					'999'  => '999',
				],
				'selectors' => [
					'{{WRAPPER}} .cz_free_position_element' => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'onhover',
			[
				'label' => esc_html__( 'Hover', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_hide_onhover'  => esc_html__( 'Hide on parent hover', 'codevz' ),
					'cz_show_onhover'  => esc_html__( 'Show on parent hover FadeIn', 'codevz' ),
					'cz_show_onhover cz_show_fadeup'  => esc_html__( 'Show on parent hover FadeUp', 'codevz' ),
					'cz_show_onhover cz_show_fadedown'  => esc_html__( 'Show on parent hover FadeDown', 'codevz' ),
					'cz_show_onhover cz_show_fadeleft'  => esc_html__( 'Show on parent hover FadeLeft', 'codevz' ),
					'cz_show_onhover cz_show_faderight'  => esc_html__( 'Show on parent hover FadeRight', 'codevz' ),
					'cz_show_onhover cz_show_zoomin'  => esc_html__( 'Show on parent hover ZoomIn', 'codevz' ),
					'cz_show_onhover cz_show_zoomout'  => esc_html__( 'Show on parent hover ZoomOut', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'animation',
			[
				'label' => esc_html__( 'Loop animation', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_infinite_anim_1'  => esc_html__( 'Animation 1', 'codevz' ),
					'cz_infinite_anim_2'  => esc_html__( 'Animation 2', 'codevz' ),
					'cz_infinite_anim_3'  => esc_html__( 'Animation 3', 'codevz' ),
					'cz_infinite_anim_4'  => esc_html__( 'Animation 4', 'codevz' ),
					'cz_infinite_anim_5'  => esc_html__( 'Animation 5', 'codevz' ),
				],
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );
	}

	public function render() {

		$atts = $this->get_settings_for_display();

		// Classes
		$classes = array();
		$classes[] = 'cz_free_position_element';
		$classes[] = $atts['animation'];
		$classes[] = $atts['onhover'];

		if ( $atts[ 'type' ] === 'template' ) {
			$content = Codevz_Plus::get_page_as_element( $settings[ 'xtra_elementor_template' ] );
		} else {
			$content = do_shortcode( $atts[ 'content' ] );
		}

		echo '<div' . Codevz_Plus::classes( [], $classes ) . '><div>' . $content . '</div></div>';

	}

}