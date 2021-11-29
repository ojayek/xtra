<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_popup extends Widget_Base {

	protected $id = 'cz_popup';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Popup', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-popup';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Popup', 'codevz' ),
			esc_html__( 'Modal', 'codevz' ),
			esc_html__( 'Box', 'codevz' ),
			esc_html__( 'Dialog', 'codevz' ),

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
				'label' => esc_html__( 'Popup', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'id_popup',
			[
				'label' => esc_html__( 'Unique ID', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'cz_popup_1',
				'placeholder' => 'cz_popup_1',
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

		$this->add_control(
			'visibility',
			[
				'label' => esc_html__( 'Visibility mode?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_popup_page_start cz_popup_show_once' => esc_html__( 'Once open on page start loading', 'codevz' ),
					'cz_popup_page_start cz_popup_show_always' => esc_html__( 'Everytime open on page start loading', 'codevz' ),
					'cz_popup_page_loaded cz_popup_show_once' => esc_html__( 'Once open when page fully loaded', 'codevz' ),
					'cz_popup_page_loaded cz_popup_show_always' => esc_html__( 'Everytime open when page fully loaded', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'settimeout',
			[
				'label' => esc_html__( 'Show popup after specific seconds', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 60,
				'step' => 1,
			]
		);

		$this->add_control(
			'after_scroll',
			[
				'label' => esc_html__( 'Show popup after scrolling page', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
			]
		);

		$this->add_control(
			'icon',
			[
				'label' 	=> esc_html__( 'Close icon', 'codevz' ),
				'type' 		=> Controls_Manager::ICONS,
				'skin' 		=> 'inline',
				'label_block' => false,
				'default' 	=> [
					'value'  	=> 'fas fa-times',
					'library'  	=> 'fa-solid'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_bg',
			[
				'label' => esc_html__( 'Overlay background', 'codevz' ),
				'type' 	=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cz_overlay' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'sk_popup',
			[
				'label' 	=> esc_html__( 'Popup', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding', 'width', 'height' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_popup_in' ),
			]
		);

		$this->add_responsive_control(
			'sk_icon',
			[
				'label' 	=> esc_html__( 'Close icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'box-shadow', 'top', 'right' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_close_popup' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$atts = $this->get_settings_for_display();

		// Data.
		$data = $atts['settimeout'] 	? ' data-settimeout="' . $atts['settimeout'] . '000"' : '';
		$data .= $atts['after_scroll'] 	? ' data-after-scroll="' . $atts['after_scroll'] . '"' : '';
		$data .= $atts['overlay_bg'] 	? ' data-overlay-bg="' . $atts['overlay_bg'] . '"' : '';

		// Content.
		if ( $atts[ 'type' ] === 'template' ) {
			$content = Codevz_Plus::get_page_as_element( $atts[ 'xtra_elementor_template' ] );
		} else {
			$content = do_shortcode( $atts[ 'content' ] );
		}

		// Classes
		$classes = array();
		$classes[] = 'cz_popup_modal clr';
		$classes[] = $atts['visibility'];

		// Out
		echo '<div class="xtra-elementor-popup"><div id="' . $atts['id_popup'] . '"' . Codevz_Plus::classes( [], $classes ) . $data . '><div class="cz_popup_in"><div>' . $content . '</div></div>';

		Icons_Manager::render_icon( $atts['icon'], [ 'class' => 'cz_close_popup' ] );

		echo '<div class="cz_overlay"></div></div></div>';

		// Fix live preivew.
		Xtra_Elementor::render_js( 'countdown' );

	}

}