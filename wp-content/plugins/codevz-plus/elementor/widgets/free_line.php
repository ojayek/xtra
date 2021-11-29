<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_free_line extends Widget_Base {

	protected $id = 'cz_free_line';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Free Line', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-free-line';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Line', 'codevz' ),
			esc_html__( 'Separator', 'codevz' ),

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
				'tab' => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_control(
			'position',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tal',
				'options' => [
					'tal' => esc_html__( 'Left', 'codevz' ),
					'tac' => esc_html__( 'Center', 'codevz' ),
					'tar' => esc_html__( 'Right', 'codevz' ),
					'tal tac_in_mobile' => esc_html__( 'Left (Center in Small Devices)', 'codevz' ),
					'tar tac_in_mobile' => esc_html__( 'Right (Center in Small Devices)', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'circles',
			[
				'label' => esc_html__( 'Circles Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'cz_line_before_circle' => esc_html__( 'Before', 'codevz' ),
					'cz_line_after_circle' => esc_html__( 'After', 'codevz' ),
					'cz_line_before_circle cz_line_after_circle' => esc_html__( 'Both Sides', 'codevz' ),
				],
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
			'sk_line',
			[
				'label' 	=> esc_html__( 'Line', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'width', 'height', 'transform', 'top', 'right', 'bottom', 'left', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_line' ),
			]
		);

		$this->add_responsive_control(
			'sk_circles',
			[
				'label' 	=> esc_html__( 'Circles', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'width', 'height', 'top', 'right', 'bottom', 'left', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_line:before, .cz_line:after' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Classes
		$classes = [];
		$classes[] = 'cz_line';
		$classes[] = $settings['circles'];
		$classes[] = $settings['position'];

		Xtra_Elementor::parallax( $settings );
		?>
		<div class="relative">
			<div <?php echo Codevz_Plus::classes( [], $classes );  ?>></div>
		</div>
		<div class="clr"></div>
		<?php

		Xtra_Elementor::parallax( $settings, true );
	}

	public function content_template() {
		?>
		<#
		var classes = 'cz_line', 
			classes = classes + ( settings.road ? ' ' + settings.road : '' );
			classes = classes + ( settings.circles ? ' ' + settings.circles : '' );
			classes = classes + ( settings.position ? ' ' + settings.position : '' );

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );
		#>

		{{{ parallaxOpen }}}
		
		<div class="relative">
			<div class="{{{classes}}}"></div>
		</div>
		<div class="clr"></div>

		{{{ parallaxClose }}}
		<?php
	}
}