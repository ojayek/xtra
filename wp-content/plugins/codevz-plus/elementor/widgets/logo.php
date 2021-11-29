<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_logo extends Widget_Base { 

	protected $id = 'cz_logo';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Header - Logo', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-logo';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [
			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Image', 'codevz' ),
			esc_html__( 'Photo', 'codevz' ),
			esc_html__( 'Logo', 'codevz' ),
			esc_html__( 'Site', 'codevz' )
		];

	}

	public function get_style_depends() {
		return [ $this->id, 'cz_parallax', 'codevz-tilt' ];
	}

	public function get_script_depends() {
		return [ $this->id, 'cz_parallax', 'codevz-tilt' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_logo',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Logo', 'codevz' ),
				'type' => Controls_Manager::MEDIA
			]
		);
		
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label' => esc_html__( 'Custom Width', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 50,
						'max' => 500,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_logo img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'css_position',
			[
				'label' => esc_html__( 'Image Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'relative',
				'options' => [
					'relative' 									=> esc_html__( '~ Default ~', 'codevz' ),
					'relative;float:left' 						=> esc_html__( 'Left', 'codevz' ),
					'relative;display: table;margin:0 auto' 	=> esc_html__( 'Center', 'codevz' ),
					'relative;float:right' 						=> esc_html__( 'Right', 'codevz' ),
					'relative;float:left;margin:0 auto' 		=> esc_html__( 'Left (center_in_mobile)', 'codevz' ),
					'relative;float:right;margin:0 auto' 		=> esc_html__( 'Right (center_in_mobile)', 'codevz' ),
				],
				'selectors' => [
					'{{WRAPPER}} .cz_logo' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Custom link', 'codevz' ),
				'type' => Controls_Manager::URL
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section(
			'section_style_logo',
			[
				'label' => esc_html__( 'Styling', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_logo',
			[
				'label' 	=> esc_html__( 'Logo', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_logo img' ),
			]
		);

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$image = Group_Control_Image_Size::get_attachment_image_html( $settings );

		if ( ! empty( $settings['link']['url'] ) ) {

			$this->add_link_attributes( 'link', $settings['link'] );

			$logo = '<a '. $this->get_render_attribute_string( 'link' ) . '>' . wp_kses_post( $image ) . '</a>';

		} else {

			$logo = '<a href="' . esc_url( get_site_url() ) . '">' . wp_kses_post( $image ) . '</a>';

		}

		// Widget classes.
		$classes = array();
		$classes[] = 'cz_logo clr';
		$classes[] = ( $settings['css_position'] === 'relative' ) ? 'center_on_mobile' : '';
		$classes[] = Codevz_Plus::contains( $settings['css_position'], 'margin' ) ? 'center_on_mobile' : '';

		// Parallax.
		Xtra_Elementor::parallax( $settings );

		echo '<div' . Codevz_Plus::classes( [], $classes ) . '>';

		echo wp_kses_post( $logo );

		echo '</div>';

		// Close parallax.
		Xtra_Elementor::parallax( $settings, true );

	}

}