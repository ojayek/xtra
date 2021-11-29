<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_counter extends Widget_Base {

	protected $id = 'cz_counter';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Counter', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-counter';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Counter', 'codevz' ),
			esc_html__( 'Count', 'codevz' ),
			esc_html__( 'Countup', 'codevz' ),
			esc_html__( 'Number', 'codevz' ),
			esc_html__( 'Num', 'codevz' ),

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
			'settings',
			[
				'label' 	=> esc_html__( 'Settings','codevz' ),
				'tab' 		=> Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'before',
			[
				'label' 	=> esc_html__('Prefix','codevz'),
				'type' 		=> Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'number',
			[
				'label' 	=> esc_html__( 'Number', 'codevz' ),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> '500'
			]
		);

		$this->add_control(
			'symbol',
			[
				'label' 	=> esc_html__('Symbol','codevz'),
				'type' 		=> Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'after',
			[
				'label' 	=> esc_html__( 'Suffix','codevz'),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> esc_html__( 'Projects','codevz'),
			]
		);

		$this->add_control(
			'position',
			[
				'label' 	=> esc_html__( 'Position', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'tal cz_1row',
				'options' 	=> [
					'tal cz_1row' 	=> esc_html__( 'Inline | Left', 'codevz' ),
					'tac cz_1row' 	=> esc_html__( 'Inline | Center', 'codevz' ),
					'tar cz_1row' 	=> esc_html__( 'Inline | Right', 'codevz' ),
					'tal cz_2rows' 	=> esc_html__( 'Block | Left', 'codevz' ),
					'tac cz_2rows' 	=> esc_html__( 'Block | Center', 'codevz' ),
					'tar cz_2rows' 	=> esc_html__( 'Block | Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'duration',
			[
				'label' 	=> esc_html__( 'Duration', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> '4',
				'min' 		=> 1,
				'step' 		=> 1,
				'max' 		=> 20
			]
		);

		$this->add_control(
			'delay',
			[
				'label' 	=> esc_html__( 'Delay Seconds', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> '0',
				'min' 		=> 0,
				'step' 		=> 1,
				'max' 		=> 20,
			]
		);

		$this->add_control(
			'comma',
			[
				'label' 	=> esc_html__( 'Disable comma?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'text_center',
			[
				'label' 	=> esc_html__( 'Center on mobile?', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		// StyleKit.
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
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_counter', '.cz_counter:hover' ),
			]
		);

		$this->add_responsive_control(
			'sk_num',
			[
				'label' 	=> esc_html__( 'Number', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_counter_num_wrap', '.cz_counter:hover .cz_counter_num_wrap' ),
			]
		);

		$this->add_responsive_control(
			'sk_symbol',
			[
				'label' 	=> esc_html__( 'Symbol', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_counter_num_wrap i', '.cz_counter:hover .cz_counter_num_wrap i' ),
			]
		);

		$this->add_responsive_control(
			'sk_ba',
			[
				'label' 	=> esc_html__( 'Prefix', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_counter_before', '.cz_counter:hover .cz_counter_before' ),
			]
		);

		$this->add_responsive_control(
			'sk_after',
			[
				'label' 	=> esc_html__( 'Suffix', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_counter_after', '.cz_counter:hover .cz_counter_after' ),
			]
		);

		$this->add_responsive_control(
			'svg_bg',
			[
				'label' 	=> esc_html__( 'Background layer', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'top', 'left', 'width', 'height' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_svg_bg:before' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Classes
		$classes = array();
		$classes[] = 'cz_counter clr';
		$classes[] = $settings['position'];
		$classes[] = $settings['text_center'] ? 'cz_mobile_text_center' : '';

		$before = $settings['before'] ? '<span class="cz_counter_before">' . $settings['before'] . '</span>' : '';
		$after  = $settings['after'] ? '<span class="cz_counter_after">' . $settings['after'] . '</span>' : '';
		$symbol = $settings['symbol'] ? '<i>' . $settings['symbol'] . '</i>' : '';
		$number = $settings['number'] ? $settings['number'] : '0';
		$number = '<span class="cz_counter_num_wrap"><span class="cz_counter_num">' . $number . '</span>' . $symbol . '</span>';

		Xtra_Elementor::parallax( $settings );

		?>

		<div<?php echo Codevz_Plus::classes( [], $classes ); ?> data-disable-comma="<?php echo $settings['comma']; ?>" data-duration="<?php echo $settings['duration']; ?>000" data-delay="<?php echo $settings['delay']; ?>000">

			<div class="<?php echo $settings['svg_bg'] ? 'cz_svg_bg' : ''; ?>">
				<?php echo wp_kses_post( $before . $number . $after ); ?>
			</div>

		</div>

		<?php

		Xtra_Elementor::parallax( $settings, true );

	}

	public function content_template() {
		?>

		<#
			var classes = 'cz_counter clr', 
				classes = classes + ( settings.position ? ' ' + settings.position : '' ),
				classes = classes + ( settings.text_center ? ' cz_mobile_text_center' : '' ),
				
				svg_bg = settings.svg_bg ? 'cz_svg_bg' : '',
				before = settings.before ? '<span class="cz_counter_before">' + settings.before + '</span>' : '',
				after  = settings.after ? '<span class="cz_counter_after">' + settings.after + '</span>' : '',
				symbol = settings.symbol ? '<i>' + settings.symbol + '</i>' : '',
				number = settings.number ? settings.number : '0',
				number = '<span class="cz_counter_num_wrap"><span class="cz_counter_num">' + number + '</span>' + symbol + '</span>',

				parallaxOpen = xtraElementorParallax( settings ),
				parallaxClose = xtraElementorParallax( settings, true );
		#>

		{{{ parallaxOpen }}}

		<div class="{{{classes}}}" data-disable-comma="{{{settings.comma}}}" data-duration="{{{settings.duration}}}000" data-delay="{{{settings.delay}}}000">

			<div class="{{{svg_bg}}}">
				{{{ before }}}{{{ number }}}{{{ after }}}
			</div>

		</div>

		{{{ parallaxClose }}}

		<?php
	}
}