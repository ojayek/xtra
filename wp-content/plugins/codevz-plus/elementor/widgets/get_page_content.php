<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_get_page_content extends Widget_Base {

	protected $id = 'cz_get_page_content';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Saved Template', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-get-page-content';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Page Content', 'codevz' ),
			esc_html__( 'Template', 'codevz' ),
			esc_html__( 'Saved', 'codevz' ),
			esc_html__( 'Hook', 'codevz' ),
			esc_html__( 'Custom', 'codevz' ),
			esc_html__( 'Section', 'codevz' ),

		];

	}

	public function register_controls() {

		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		Xtra_Elementor::parallax( $settings );

		echo Codevz_Plus::get_page_as_element( $settings[ 'xtra_elementor_template' ] );

		Xtra_Elementor::parallax( $settings, true );

	}

}