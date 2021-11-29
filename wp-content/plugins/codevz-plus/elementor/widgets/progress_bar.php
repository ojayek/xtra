<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_progress_bar extends Widget_Base {

	protected $id = 'cz_progress_bar';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Progress Bar', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-progress-bar';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Growth', 'codevz' ),
			esc_html__( 'Progress', 'codevz' ),
			esc_html__( 'Bar', 'codevz' ),

		];

	}

	public function get_style_depends() {

		$array = [ $this->id, 'cz_parallax' ];

		if ( Codevz_Plus::$is_rtl ) {
			$array[] = $this->id . '_rtl';
		}

		return $array;

	}

	public function get_script_depends() {
		return [ $this->id, 'cz_parallax' ];
	}

	public function register_controls() {

		$this->start_controls_section(
			'section_progress_bar',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'My Skill', 'codevz' ),
				'placeholder' => esc_html__( 'My Skill', 'codevz' ),
			]
		);

		$this->add_control(
			'number',
			[
				'label' 	=> __( 'Number', 'elementor' ),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> 70
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'pbar1',
				'options' => [
					'pbar1' => esc_html__( 'Style 1', 'codevz' ),
					'pbar2' => esc_html__( 'Style 2', 'codevz' ),
					'pbar3' => esc_html__( 'Style 3', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'stripes',
			[
				'label' => esc_html__( 'Animated stripes?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
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
			'sk_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '' ),
			]
		);

		$this->add_responsive_control(
			'sk_title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.progress_bar p' ),
			]
		);

		$this->add_responsive_control(
			'sk_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.progress_bar i' ),
			]
		);

		$this->add_responsive_control(
			'sk_num',
			[
				'label' 	=> esc_html__( 'Number', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.progress_bar b' ),
			]
		);

		$this->add_responsive_control(
			'sk_bar',
			[
				'label' 	=> esc_html__( 'Bar', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'height', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.progress_bar' ),
			]
		);

		$this->add_responsive_control(
			'sk_progress',
			[
				'label' 	=> esc_html__( 'Progress', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.progress_bar span' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {
		$settings = $this->get_settings_for_display();

		ob_start();
		Icons_Manager::render_icon( $settings['icon'], [ 'class' => 'mr8', 'aria-hidden' => 'true' ] );
		$icon = ob_get_clean();

		// Icon
		$settings['title'] = $icon ? $icon . $settings['title'] : $settings['title'];

		$classes = [];
		$classes[] = 'progress_bar';
		$classes[] = $settings['style'];
		$classes[] = $settings['stripes'] ? 'stripes' : '';

		if ( isset( $settings['number']['size'] ) ) {
			$settings['number'] = $settings['number'][ 'size' ];
		}

		// Number
		$number = $number_fix = '';
		if ( $settings['style'] === 'pbar3' ) {
			$number_fix = '<b>' . $settings['number'] . '</b>';
		} else {
			$number = '<b>' . $settings['number'] . '</b>';
		}

		Xtra_Elementor::parallax( $settings );

		?>
		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>>
			<span><?php echo wp_kses_post( $number ); ?></span>
			<p><?php echo do_shortcode( wp_kses_post( $settings['title'] ) ); ?></p>
			<?php echo wp_kses_post( $number_fix ); ?>
		</div>
		<?php

		Xtra_Elementor::parallax( $settings, true );

		// Fix live preivew.
		Xtra_Elementor::render_js( 'progress_bar' );
	}

	public function content_template() {
		?>
		<#
			var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'class': 'mr8', 'aria-hidden': true }, 'i', 'object' ),

				title = ( iconHTML.value ? iconHTML.value : '' ) + settings.title,

				classes = 'progress_bar',
				classes = settings.style ? classes + ' ' + settings.style : classes,
				classes = settings.stripes ? classes + ' stripes' : classes,

				parallaxOpen = xtraElementorParallax( settings ),
				parallaxClose = xtraElementorParallax( settings, true ),

				number = settings.number.size ? settings.number.size : settings.number,
				number_fix = '';

			if ( settings.style === 'pbar3' ) {
				number_fix = '<b>' + number + '</b>';
			} else {
				number = '<b>' + number + '</b>';
			}
		#>

		{{{ parallaxOpen }}}

		<div class="{{{classes}}}">

			<span>
				{{{number}}}
			</span>

			<p>
				{{{title}}}
			</p>

			{{{number_fix}}}

		</div>

		{{{ parallaxClose }}}

		<?php

	}

}