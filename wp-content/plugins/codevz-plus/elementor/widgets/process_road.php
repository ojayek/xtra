<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

class Xtra_Elementor_Widget_process_road extends Widget_Base {

	protected $id = 'cz_process_road';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Process Road', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-process-road';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Process', 'codevz' ),
			esc_html__( 'Road', 'codevz' ),
			esc_html__( 'Way', 'codevz' ),
			esc_html__( 'Direction', 'codevz' ),

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
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'road',
			[
				'label' => esc_html__( 'Road', 'codevz' ),
				'type' 	=> Controls_Manager::SELECT,
				'default' => 'cz_road_cr',
				'options' => [
					'cz_road_cr' => esc_html__( 'Center to Right', 'codevz' ),
					'cz_road_cb' => esc_html__( 'Center to Bottom', 'codevz' ),
					'cz_road_tl cz_road_cl' => esc_html__( 'Center to Left', 'codevz' ),
					'cz_road_tl cz_road_ct' => esc_html__( 'Center to Top', 'codevz' ),
					'cz_road_rb' => esc_html__( 'Right to bottom ( ┌ )', 'codevz' ),
					'cz_road_tb' => esc_html__( 'Top to bottom ( │ )', 'codevz' ),
					'cz_road_tr' => esc_html__( 'Top to right ( └ )', 'codevz' ),
					'cz_road_tl' => esc_html__( 'Top to left ( ┘ )', 'codevz' ),
					'cz_road_lr' => esc_html__( 'Left to right ( ─ )', 'codevz' ),
					'cz_road_lb' => esc_html__( 'Left to bottom ( ┐ )', 'codevz' ),
					'cz_road_vr' => esc_html__( 'Vertical & Right ( ├ )', 'codevz' ),
					'cz_road_lb cz_road_vl' => esc_html__( 'Vertical & Left ( ┤ )', 'codevz' ),
					'cz_road_tr cz_road_hu' => esc_html__( 'Horizontal & Up ( ┴ )', 'codevz' ),
					'cz_road_hd' => esc_html__( 'Horizontal & Down ( ┬ )', 'codevz' ),
					'cz_road_crs' => esc_html__( 'Cross ( ┼ )', 'codevz' ),
				],
			]
		);

		$this->add_responsive_control(
			'line_height',
			[
				'label' => __( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_process_road' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'line_border-style',
			[
				'label' => esc_html__( 'Lines style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'solid' => esc_html__( 'Solid', 'codevz' ),
					'dotted' => esc_html__( 'Dotted', 'codevz' ),
					'dashed' => esc_html__( 'Dashed', 'codevz' ),
				],
				'selectors' => [
					'{{WRAPPER}} .cz_process_road' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'line_size',
			[
				'label' => esc_html__( 'Line size', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' 				=> esc_html__( 'Select', 'codevz' ),
					'cz_road_0px' 	=> '0px',
					'cz_road_1px' 	=> '1px',
					'cz_road_2px' 	=> '2px',
					'cz_road_3px' 	=> '3px',
					'cz_road_4px' 	=> '4px',
					'cz_road_5px' 	=> '5px',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_indicator',
			[
				'label' => esc_html__( 'Indicator', 'codevz' )
			]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__( 'Icon', 'codevz' ),
					'number' => esc_html__( 'Number', 'codevz' ),
					'image' => esc_html__( 'Image', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'condition' => [
					'type' => 'icon',
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'type' => 'image',
				],
			]
		);

		$this->add_control(
			'number',
			[
				'label' => esc_html__( 'Number', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => '1',
				'placeholder' => '1',
				'condition' => [
					'type' => 'number',
				],
			]
		);

		$this->add_control(
			'icon_style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_road_icon_rhombus',
				'options' => [
					'cz_road_icon_rhombus' => esc_html__( 'Rhombus', 'codevz' ),
					'cz_road_icon_rhombus_2' => esc_html__( 'Rhombus Radius', 'codevz' ),
					'cz_road_icon_rhombus_3' => esc_html__( 'Pin', 'codevz' ),
					'cz_road_icon_custom' => esc_html__( 'Custom', 'codevz' ),
				],
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'line_border-color',
			[
				'label' => esc_html__( 'Line Color', 'codevz' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cz_process_road' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'sk_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_process_road i', '.cz_process_road:hover i' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		ob_start();
		Icons_Manager::render_icon( $settings['icon'] );
		$icon = ob_get_clean();

		// Size-width
		if ( ! empty( $settings[ 'sk_icon' ][ 'normal' ] ) ) {
			$size = Codevz_Plus::get_string_between( $settings[ 'sk_icon' ][ 'normal' ], 'font-size:', ';' );
			$size = $size ? ' style="width:' . $size . '"' : '';
		} else {
			$size = '';
		}

		// Icon
		$icon = ( $settings['type'] === 'icon' ) ? $icon  : ( ( $settings['type'] === 'image' ) ? '<i><b>' . str_replace( '/>', $size . '>',  Group_Control_Image_Size::get_attachment_image_html( $settings )  ) . '</b></i>' : '<i><span>' . $settings['number'] . '</span></i>' );

		// Classes
		$classes = array();
		$classes[] = 'cz_process_road';
		$classes[] = $settings['road'];
		$classes[] = $settings['icon_style'];
		$classes[] = $settings['line_size'];

		Xtra_Elementor::parallax( $settings );

		?>
		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>>
			<?php echo wp_kses_post( $icon ); ?>
			<div class="cz_process_road_a"></div>
			<div class="cz_process_road_b"></div>
		</div>
		<?php

		Xtra_Elementor::parallax( $settings, true );

	}

	public function content_template() {
		?>
		<#

		if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			if ( ! image_url ) {
				return;
			}
		}

		var classes = 'cz_process_road', 
			classes = settings.road ? classes + ' ' + settings.road : classes;
			classes = settings.icon_style ? classes + ' ' + settings.icon_style : classes;
			classes = settings.line_size ? classes + ' ' + settings.line_size : classes,
			
			iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' ),
			
			size = '',
			imageHTML = '<img src="' + image_url + '" />',
			icon = ( settings.type == 'icon' ) ? iconHTML.value || '<i class="fas fa-star"></i>' : ( ( settings.type == 'image' ) ? '<i><b>' + imageHTML.replace( '/>', size + '>' ) + '</b></i>' : '<i><span>' + settings.number + '</span></i>' ),

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );

		#>

		{{{ parallaxOpen }}}

		<div class="{{{classes}}}">
			{{{ icon }}}
			<div class="cz_process_road_a"></div>
			<div class="cz_process_road_b"></div>
		</div>

		{{{ parallaxClose }}}
		<?php
	}
}