<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_process_line_vertical extends Widget_Base {

	protected $id = 'cz_process_line_vertical';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Process Line Vertical', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-process-line-vertical';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Process', 'codevz' ),
			esc_html__( 'Line', 'codevz' ),
			esc_html__( 'Steps', 'codevz' ),

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
			'number',
			[
				'label' 	=> esc_html__('Number','codevz'),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> '1',
				'placeholder' => '1',
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
			'height',
			[
				'label' => esc_html__( 'Height of line', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 600,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_plv_number' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pos',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' 			=> esc_html__( '~ Default ~', 'codevz' ),
					'cz_plv_t' 	=> esc_html__( 'Top', 'codevz' ),
					'cz_plv_m' 	=> esc_html__( 'Middle', 'codevz' ),
				],
			]
		);


		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section(
			'cz_title',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_plv' ),
			]
		);

		$this->add_responsive_control(
			'sk_num',
			[
				'label' 	=> esc_html__( 'Number', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_plv_number span', '.cz_plv:hover .cz_plv_number span' ),
			]
		);

		$this->add_responsive_control(
			'sk_line',
			[
				'label' 	=> esc_html__( 'Line', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'width', 'height', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_plv_number b', '.cz_plv:hover .cz_plv_number b' ),
			]
		);

		$this->add_responsive_control(
			'sk_content',
			[
				'label' 	=> esc_html__( 'Content', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'text-align', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_plv_content', '.cz_plv:hover .cz_plv_content' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$classes = [];
		$classes[] = 'cz_plv clr';
		$classes[] = $settings['pos'];

		?>

		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>>
			
			<div class="cz_plv_item">

				<div class="cz_plv_number">
					<b></b>
					<span><?php echo wp_kses_post( $settings['number'] ); ?></span>
				</div>

				<div class="cz_plv_content">
					<?php
						if ( $settings[ 'type' ] === 'template' ) {
							echo Codevz_Plus::get_page_as_element( $settings[ 'xtra_elementor_template' ] );
						} else {
							echo do_shortcode( $settings[ 'content' ] );
						}
					?>
				</div>

			</div>

		</div>

		<?php
	}

}