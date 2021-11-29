<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_history_line extends Widget_Base {

	protected $id = 'cz_history_line';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'History Line', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-history-line';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'History', 'codevz' ),
			esc_html__( 'Line', 'codevz' ),
			esc_html__( 'Date', 'codevz' ),
			esc_html__( 'Timeline', 'codevz' )

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
			'section_content',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$year = date( "Y" );

		$this->add_control(
			'year',
			[
				'label' => esc_html__( 'Year', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => $year,
				'placeholder' => $year
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

		$this->end_controls_section();

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
				'settings' 	=> [ 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_history_1' ),
			]
		);

		$this->add_responsive_control(
			'sk_line',
			[
				'label' 	=> esc_html__( 'Line', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'border-color' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_history_1:after' ),
			]
		);

		$this->add_responsive_control(
			'sk_year',
			[
				'label' 	=> esc_html__( 'Year', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_history_1 > span', '.cz_history_1:hover > span' ),
			]
		);

		$this->add_responsive_control(
			'sk_circle',
			[
				'label' 	=> esc_html__( 'Circle', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_history_1:before', '.cz_history_1:hover:before' ),
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$classes = array();
		$classes[] = 'cz_history_1';
		$classes[] = $settings['year'] ? 'cz_has_year' : '';

		$settings['year'] = $settings['year'] ? '<span>' . $settings['year'] . '</span>' : '';

		Xtra_Elementor::parallax( $settings );

		?>
		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>>

			<?php echo $settings['year']; ?>

			<div>
				<?php

					if ( $settings[ 'type' ] === 'template' ) {
						echo Codevz_Plus::get_page_as_element( $settings[ 'xtra_elementor_template' ] );
					} else {
						echo do_shortcode( $settings[ 'content' ] );
					}

				?>
			</div>

		</div>
		<?php

		Xtra_Elementor::parallax( $settings, true );
	}
}