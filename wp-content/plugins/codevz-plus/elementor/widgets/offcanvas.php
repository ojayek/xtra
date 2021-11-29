<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_offcanvas extends Widget_Base {

	protected $id = 'cz_offcanvas';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Header - Offcanvas', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-offcanvas';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'codevz', 'codevz' ),
			esc_html__( 'Offcanvas', 'codevz' ),
			esc_html__( 'Ajax', 'codevz' ),

		];

	}

	public function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' 	=> esc_html__( 'Settings', 'codevz' ),
				'tab' 		=> Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'inview_position_widget',
			[
				'label' 		=> esc_html__( 'Position', 'codevz' ),
				'type' 			=> Controls_Manager::SELECT,
				'options' 		=> [
					'inview_left' 	=> esc_html__( 'Left', 'codevz' ),
					'inview_right' 	=> esc_html__( 'Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'offcanvas_icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false
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
			'sk_offcanvas_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.offcanvas_container > i' ),
			]
		);

		$this->add_responsive_control(
			'sk_offcanvas',
			[
				'label' 	=> esc_html__( 'Offcanvas', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.offcanvas_area' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		Xtra_Elementor::parallax( $settings );

		//$icon = empty( $settings['offcanvas_icon'] ) ? 'fa fa-bars' : $settings['offcanvas_icon'];

		ob_start();
		Icons_Manager::render_icon( $settings['offcanvas_icon'], [ 'class' => 'xtra-search-icon', 'data-xtra-icon' => ( empty( $settings['offcanvas_icon'][ 'value' ] ) ? 'fa fa-bars' : $settings['offcanvas_icon'][ 'value' ] ) ] );
		$icon = ob_get_clean();

		$menu_title = isset( $settings['menu_title'] ) ? $settings['menu_title'] : '';

		echo '<div class="offcanvas_container"><i class="' . esc_attr( empty( $settings['search_icon'][ 'value' ] ) ? 'fa fa-bars' : $settings['offcanvas_icon'][ 'value' ] ) . ( $menu_title ? ' icon_plus_text' : '' ) . '"><span>' . esc_html( $menu_title ) . '</span></i><div class="offcanvas_area offcanvas_original ' . ( empty( $settings['inview_position_widget'] ) ? 'inview_left' : esc_attr( $settings['inview_position_widget'] ) ) . '">';

		if ( is_active_sidebar( 'offcanvas_area' ) ) {

			ob_start();
			dynamic_sidebar( 'offcanvas_area' );
			$offcanvas = ob_get_clean();

			if ( Codevz_Plus::option( 'lazyload' ) ) {
				echo Codevz_Plus::lazyload( $offcanvas );
			} else {
				echo do_shortcode( $offcanvas );
			}

		}

		echo '</div></div>';

		Xtra_Elementor::parallax( $settings, true );

	}

}