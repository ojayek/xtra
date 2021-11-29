<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_show_more_less extends Widget_Base {
	
	protected $id = 'cz_show_more_less';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Show More,Less', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-show-more-less';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'More', 'codevz' ),
			esc_html__( 'Less', 'codevz' ),
			esc_html__( 'Show', 'codevz' ),
			esc_html__( 'Collapse', 'codevz' ),
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
			'xtra_elementor_template',
			[
				'label' 	=> esc_html__( 'Select template', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> Xtra_Elementor::get_templates()
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 400,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_sml_inner' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'more',
			[
				'label' 	=> esc_html__('Show more','codevz'),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> esc_html__('Show more','codevz'),
				'placeholder' => esc_html__('Show more','codevz'),
			]
		);

		$this->add_control(
			'icon_more',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-down',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'less',
			[
				'label' 	=> esc_html__('Show less','codevz'),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> esc_html__('Show less','codevz'),
				'placeholder' => esc_html__('Show less','codevz'),
			]
		);

		$this->add_control(
			'icon_less',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-up',
					'library' => 'solid',
				],
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html( 'Style', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'gradient',
			[
				'label' => esc_html__( 'Overlay color', 'codevz' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .cz_sml .cz_sml_overlay' => 'background:linear-gradient(to bottom, rgba(255,255,255,0), {{VALUE}})',
				]
			]
		);

		$this->add_responsive_control(
			'sk_button',
			[
				'label' 	=> esc_html__( 'Button styling', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'display', 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_sml > a' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Icons
		ob_start();
		Icons_Manager::render_icon( $settings['icon_more'], [ 'class' => 'ml8' ] );
		$icon_more = ob_get_clean();

		ob_start();
		Icons_Manager::render_icon( $settings['icon_less'], [ 'class' => 'ml8' ] );
		$icon_less = ob_get_clean();

		Xtra_Elementor::parallax( $settings );

		?>

		<div<?php echo Codevz_Plus::classes( [], array( 'cz_sml' ) ); ?>>

			<div class="cz_sml_inner">

				<div class="cz_wpe_content">
					<?php echo Codevz_Plus::get_page_as_element( $settings[ 'xtra_elementor_template' ] ); ?>
				</div>

				<div class="cz_sml_overlay"></div>

			</div>

			<a id="cz_show_more_less_btn" href="#more_less">
				<span>
					<?php echo wp_kses_post( $settings['more'] . $icon_more ); ?>
				</span>
				<span>
					<?php echo wp_kses_post( $settings['less'] . $icon_less ); ?>
				</span>
			</a>

		</div>

		<?php

		Xtra_Elementor::parallax( $settings, true );

	}

}