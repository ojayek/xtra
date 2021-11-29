<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_gap extends Widget_Base {

	protected $id = 'cz_gap';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Gap', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-gap';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Gap', 'codevz' ),
			esc_html__( 'Height', 'codevz' ),
			esc_html__( 'Space', 'codevz' ),

		];

	}

	public function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => 'Gap Settings',
				'tab' => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' 	=> esc_html__( 'Height', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'default' 	=> [
					'unit' 		=> 'px',
					'size' 		=> 30,
				],
				'size_units' => [ 'px', 'vh' ],
				'range' 	=> [
					'px' 		=> [
						'min' 		=> 0,
						'max' 		=> 500,
						'step' 		=> 1,
					],
					'vh' 	=> [
						'min' 	=> 0,
						'max' 	=> 500,
						'step' 	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_gap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		?><div class="cz_gap clr"></div><?php

	}

	public function content_template() {

		?><div class="cz_gap clr"></div><?php

	}
}