<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_parallax_group extends Widget_Base {

	protected $id = 'cz_parallax_group';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Parallax Layers', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-parallax-group';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Parallax', 'codevz' ),
			esc_html__( 'Layers', 'codevz' ),
			esc_html__( 'Group', 'codevz' ),

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
				'tab' 	=> Controls_Manager::TAB_CONTENT
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label' 	=> esc_html__( 'Image', 'codevz' ),
				'type' 		=> Controls_Manager::MEDIA,
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' 		=> 'image',
				'default' 	=> 'full',
				'separator' => 'none'
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' 	=> esc_html__( 'Width', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' 	=> [
					'px' 		=> [
						'min' 		=> 1,
						'max' 		=> 1000,
						'step' 		=> 1,
					],
					'%' 	=> [
						'min' 	=> 1,
						'max' 	=> 100,
						'step' 	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} > div' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'height',
			[
				'label' 	=> esc_html__( 'Height', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' 	=> [
					'px' 		=> [
						'min' 		=> 1,
						'max' 		=> 1000,
						'step' 		=> 1,
					],
					'%' 	=> [
						'min' 	=> 1,
						'max' 	=> 100,
						'step' 	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} > div' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'position_top',
			[
				'label' 	=> esc_html__( 'Position top', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' 	=> [
					'px' 		=> [
						'min' 		=> -300,
						'max' 		=> 300,
						'step' 		=> 1,
					],
					'%' 	=> [
						'min' 	=> -100,
						'max' 	=> 200,
						'step' 	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'position_left',
			[
				'label' 	=> esc_html__( 'Position left', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' 	=> [
					'px' 		=> [
						'min' 		=> -300,
						'max' 		=> 300,
						'step' 		=> 1,
					],
					'%' 	=> [
						'min' 	=> -100,
						'max' 	=> 200,
						'step' 	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'position_right',
			[
				'label' 	=> esc_html__( 'Position right', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' 	=> [
					'px' 		=> [
						'min' 		=> -300,
						'max' 		=> 300,
						'step' 		=> 1,
					],
					'%' 	=> [
						'min' 	=> -100,
						'max' 	=> 200,
						'step' 	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'position_bottom',
			[
				'label' 	=> esc_html__( 'Position bottom', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' 	=> [
					'px' 		=> [
						'min' 		=> -300,
						'max' 		=> 300,
						'step' 		=> 1,
					],
					'%' 	=> [
						'min' 	=> -100,
						'max' 	=> 200,
						'step' 	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'sk_item',
			[
				'label' 	=> esc_html__( 'Styling', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '{{CURRENT_ITEM}} > div' ),
			]
		);

		$repeater->add_control(
			'title', [
				'label' => esc_html__( 'Hide on mobile?', 'codevz' ),
				'type' 	=> Controls_Manager::SWITCHER
			]
		);

		Xtra_Elementor::parallax_settings( $repeater, true );

		$this->add_control(
			'items',
			[
				'label' => esc_html__( 'Item(s)', 'codevz' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls()
			]
		);

		$this->add_responsive_control(
			'position_bottom',
			[
				'label' 	=> esc_html__( 'Container height', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' 	=> [
					'px' 		=> [
						'min' 		=> 100,
						'max' 		=> 1000,
						'step' 		=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_group_parallax' => 'height: {{SIZE}}{{UNIT}};',
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
			'sk_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_group_parallax' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		ob_start();

		foreach ( $settings[ 'items' ] as $i ) {

			$image = Group_Control_Image_Size::get_attachment_image_html( $i );

			$class = empty( $i['hide_on_mobile'] ) ? '' : ' hide_on_mobile';

			Xtra_Elementor::parallax( $i );

			echo '<div class="elementor-repeater-item-' . esc_attr( $i[ '_id' ] ) . ' cz_layer_parallax' . $class . '"><div>' . $image . '</div></div>';

			Xtra_Elementor::parallax( $i, true );

		}

		$layers = ob_get_clean();

		echo '<div class="cz_group_parallax">' . $layers . '</div>';

	}

}