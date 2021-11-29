<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_attribute_box extends Widget_Base {

	protected $id = 'cz_attribute_box';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Attribute Box', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-attribute-box';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Attribute', 'codevz' ),
			esc_html__( 'Box', 'codevz' ),
			esc_html__( 'Info', 'codevz' ),

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

		$repeater = new Repeater();

		$repeater->add_control(
			'content', [
				'label' 	=> esc_html__( 'Content', 'codevz' ),
				'type' 		=> Controls_Manager::WYSIWYG,
				'default' 	=> '<span style="font-size:12px">Type</span><h5>Title</h5>'
			]
		);

		$repeater->add_responsive_control(
			'sk_container',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '{{CURRENT_ITEM}}' ),
			]
		);

		$this->add_control(
			'attributes',
			[
				'label' 	=> esc_html__( 'Attribute(s)', 'codevz' ),
				'type' 		=> Controls_Manager::REPEATER,
				'fields' 	=> $repeater->get_controls(),
				'default' 	=> [
					[
						'content' => '<span style="font-size:12px">Category</span><h5>Resident</h5>'
					],
					[
						'content' => '<span style="font-size:12px">Type</span><h5>Building</h5>'
					],
					[
						'content' => '<span style="font-size:12px">Year</span><h5>2021</h5>'
					],
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' 		=> esc_html__( 'Icon', 'codevz' ),
				'type' 			=> Controls_Manager::ICONS,
				'skin' 			=> 'inline',
				'label_block' 	=> false,
				'default' 		=> [
					'value' 		=> 'fas fa-check',
					'library' 		=> 'fa-solid',
				]
			]
		);

		$this->add_control(
			'link',
			[
				'label' 		=> esc_html__( 'Link', 'codevz' ),
				'type' 			=> Controls_Manager::URL,
				'default' 		=> [
					'url' 			=> '#'
				],
				'placeholder' 	=> 'https://xtratheme.com',
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
			'box_width',
			[
				'label' => esc_html__( 'Box width', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .xtra-attribute-box' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' 		=> 'float:left;',
					'center' 	=> 'margin: 0 auto;',
					'right' 	=> 'float:right;',
				],
				'selectors' => [
					'{{WRAPPER}} .xtra-attribute-box' => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'sk_container',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.xtra-attribute-box > div' ),
			]
		);

		$this->add_responsive_control(
			'svg_bg',
			[
				'label' 	=> esc_html__( 'Background layer', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'top', 'left', 'width', 'height' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_svg_bg:before' ),
			]
		);

		$this->add_responsive_control(
			'sk_items',
			[
				'label' 	=> esc_html__( 'Items', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.xtra-attribute-box-item' ),
			]
		);

		$this->add_responsive_control(
			'sk_button',
			[
				'label' 	=> esc_html__( 'Button', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.xtra-attribute-box > div > a' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$atts = $this->get_settings_for_display();

		$classes = [];
		$classes[] = 'xtra-attribute-box cz_svg_bg clr';

		$children = '';

		foreach( $atts[ 'attributes' ] as $item ) {

			$content = do_shortcode( $item[ 'content' ] );

			$children .= '<div class="xtra-attribute-box-item elementor-repeater-item-' . esc_attr( $item[ '_id' ] ) . '">' . $content . '</div>';

		}

		ob_start();
		Icons_Manager::render_icon( $atts['icon'], [ 'class' => 'cz-attribute-box-icon' ] );
		$icon = ob_get_clean();

		$this->add_link_attributes( 'link', $atts['link'] );

		$icon = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $icon . '</a>';

		echo '<div' . Codevz_Plus::classes( [], $classes ) . '><div>' . $children . $icon . '</div></div>';

	}

}