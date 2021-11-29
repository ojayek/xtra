<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_text_marquee extends Widget_Base {

	protected $id = 'cz_text_marquee';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Text Marquee', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-text-marque';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Scroll', 'codevz' ),
			esc_html__( 'Marquee', 'codevz' ),
			esc_html__( 'Text', 'codevz' ),
			esc_html__( 'Slider', 'codevz' ),
			esc_html__( 'Rotate', 'codevz' ),

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
				'label' => esc_html__( "Settings", 'codevz'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__( "Text", 'codevz'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'I am marquee text, you can edit me and insert your text ...', 'codevz' ),
				'placeholder' => esc_html__( 'I am marquee text, you can edit me and insert your text ...', 'codevz' ),
			]
		);

		
		$this->add_control(
			'duration',
			[
				'label' => __( 'Duration', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 20,
				'step' => 1,
				'default' => 8,
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => esc_html__( 'Left', 'codevz' ),
					'right' => esc_html__( 'Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'duplicate',
			[
				'label' => esc_html__( 'Duplicate', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => [
					'false'  => esc_html__( 'No', 'codevz' ),
					'true' => esc_html__( 'Yes', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'stop_on_hover',
			[
				'label' => esc_html__( 'Stop on hover?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'gap',
			[
				'label' => esc_html__( 'Gap', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 50,
				'max' => 1200,
				'step' => 5,
				'default' => 100
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
			'sk_marquee',
			[
				'label' 	=> esc_html__( 'Styling', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_text_marquee' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Classes
		$classes = array();
		$classes[] = 'cz_text_marquee';
		$classes[] = $settings['stop_on_hover'] ? 'cz_text_marquee_soh' : '';

		// Data
		$data = ' data-duration="' . $settings['duration'] . '000"';
		$data .= ' data-direction="' . $settings['direction'] . '"';
		$data .= ' data-duplicated="' . $settings['duplicate'] . '"';
		$data .= ' data-gap="' . $settings['gap'] . '"';  

		Xtra_Elementor::parallax( $settings );
		
		?>
		<div<?php echo Codevz_Plus::classes( [], $classes ); ?> <?php echo wp_kses_post( $data ); ?>>
			<?php echo do_shortcode( wp_kses_post( $settings['content'] ) ); ?>
		</div>

		<?php

		Xtra_Elementor::parallax( $settings, true );

		// Fix live preivew.
		Xtra_Elementor::render_js( 'text_marquee' );

	}

	public function content_template() {
		?>
		<#

		var classes = 'cz_text_marquee', 
			classes = settings.stop_on_hover ? classes + ' cz_text_marquee_soh' : classes,

			data = ' data-duration="' + settings.duration + '000"',
			data = data + ' data-direction="' + settings.direction + '"',
			data = data + ' data-duplicated="' + settings.duplicate + '"',
			data = data + ' data-gap="' + settings.gap + '"',

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );

		#>

		{{{ parallaxOpen }}}

		<div class="{{{classes}}}" {{{data}}}>{{{settings.content}}}</div>

		{{{ parallaxClose }}}
		<?php
	}

}