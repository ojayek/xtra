<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_animated_text extends Widget_Base {

	protected $id = 'cz_animated_text';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Animated Text', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-animated-text';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Animated', 'codevz' ),
			esc_html__( 'Animation', 'codevz' ),
			esc_html__( 'Slide', 'codevz' ),
			esc_html__( 'Rotate', 'codevz' ),
			esc_html__( 'Type', 'codevz' ),
			esc_html__( 'Fade', 'codevz' ),
			esc_html__( 'Text', 'codevz' ),

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
				'label' => esc_html__('Settings','codevz'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'before_text',
			[
				'label' => esc_html__('Prefix','codevz'),
				'type' => Controls_Manager::TEXT,
				'default' => 'This is'
			]
		);

		$this->add_control(
			'words',
			[
				'label' => esc_html__('Words','codevz'),
				'type' => Controls_Manager::TEXT,
				'default' => 'Awesome,Fantastic,Wonderful',
			]
		);

		$this->add_control(
			'after_text',
			[
				'label' => esc_html__('Suffix','codevz'),
				'type' => Controls_Manager::TEXT,
				'default' => 'Theme!'
			]
		);

		$this->add_control(
			'fx',
			[
				'label' => esc_html__( 'Effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'rotate-1',
				'options' => [
					'rotate-1'          => esc_html__( 'Rotate 1', 'codevz' ),
					'letters_type'      => esc_html__( 'Type', 'codevz' ),
					'letters_rotate-2'  => esc_html__( 'Rotate 2', 'codevz' ),
					'loading-bar'       => esc_html__( 'Bar', 'codevz' ),
					'slide'             => esc_html__( 'Slide', 'codevz' ),
					'clip_is-full-width' => esc_html__( 'Clip', 'codevz' ),
					'zoom'              => esc_html__( 'Zoom', 'codevz' ),
					'letters_rotate-3'  => esc_html__( 'Rotate 3', 'codevz' ),
					'letters_scale'     => esc_html__( 'Scale', 'codevz' ),
					'push'              => esc_html__( 'Push', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'tag',
			[
				'label' => esc_html__( 'Html tag', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'span' => 'Span',
					'div' => 'Div',
					'p' => 'P',
				],
			]
		);

		$this->add_control(
			'time',
			[
				'label' => esc_html__( 'Animation Delay', 'codevz' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => '3000',
				'min' => 0,
				'step' => 500,
				'max' => 10000
			]
		);
		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );
		
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_overall',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'text-align', 'font-family', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_headline', '.cz_headline:before' ),
			]
		);

		$this->add_responsive_control(
			'sk_words',
			[
				'label' 	=> esc_html__( 'Animated Words', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_headline .cz_words-wrapper' ),
			]
		);

		$this->add_responsive_control(
			'sk_before',
			[
				'label' 	=> esc_html__( 'Prefix', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_headline .cz_before_text' ),
			]
		);

		$this->add_responsive_control(
			'sk_after',
			[
				'label' 	=> esc_html__( 'Suffix', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_headline .cz_after_text' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Classes
		$classes = [];
		$classes[] = 'cz_headline';
		$classes[] = str_replace( '_', ' ', $settings['fx'] );

		// Parallax.
		Xtra_Elementor::parallax( $settings );
		?>

		<<?php echo esc_attr( $settings['tag'] ); ?> data-time="<?php echo esc_attr( $settings['time'] ); ?>"<?php echo Codevz_Plus::classes( [], $classes ); ?>>

			<span class="cz_before_text">
				<?php echo do_shortcode( wp_kses_post( $settings['before_text'] ) ); ?>	
			</span>

			<span class="cz_words-wrapper"> 

				<?php 

					$i = 1;
					$words = (array) explode( ',', $settings['words'] );

					foreach ( $words as $word ) {

						$visible = ( $i !== 1 ) ? 'class="is-hidden"' : 'class="is-visible"';

						echo '<b ' . wp_kses_post( $visible ) . '>' . do_shortcode( wp_kses_post( $word ) ) . '</b>';

						$i++;

					}
				?>

			</span>

			<span class="cz_after_text">
				<?php echo do_shortcode( wp_kses_post( $settings['after_text'] ) ); ?>
			</span>

		</<?php echo esc_attr( $settings['tag'] ); ?>>

		<?php

		// Close parallax.
		Xtra_Elementor::parallax( $settings, true );

	}

	public function content_template_() {
		?>
		<#
			var classes = 'cz_headline', 
				classes = classes + ' ' + settings.fx;

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );
		#>

		{{{ parallaxOpen }}}

		<{{{settings.tag}}} class="{{{classes}}}" data-time="{{{settings.time}}}">

			<span class="cz_before_text">
				{{{settings.before_text}}}
			</span>

			<span class="cz_words-wrapper"> 
				<?php $i = 1; ?>
				{{{settings.words}}}
				<b>{{{settings.words}}}</b>
				<?php $i++; ?>
			</span>

			<span class="cz_after_text">
				{{{settings.after_text}}}
			</span>

		</{{{settings.tag}}}>

		{{{ parallaxClose }}}

		<?php

	}
}