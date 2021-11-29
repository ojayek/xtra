<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_title extends Widget_Base {

	protected $id = 'cz_title';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Title & Text', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-title';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Title', 'codevz' ),
			esc_html__( 'Text', 'codevz' ),
			esc_html__( 'Heading', 'codevz' ),
			esc_html__( 'Icon', 'codevz' ),

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
			'title_text',
			[
				'label' => esc_html__('Settings','codevz'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__('Title','codevz'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Title Element'
			]
		);

		$this->add_control(
			'title_pos',
			[
				'label' => esc_html__( 'Position?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_title_pos_inline',
				'options' => [
					'cz_title_pos_inline' => esc_html__( 'Inline', 'codevz' ),
					'cz_title_pos_block' => esc_html__( 'Block', 'codevz' ),
					'cz_title_pos_left' => esc_html__( 'Left', 'codevz' ),
					'cz_title_pos_center' => esc_html__( 'Center', 'codevz' ),
					'cz_title_pos_right' => esc_html__( 'Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'codevz' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://xtratheme.com'
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'title_line',
			[
				'label' => esc_html__( 'Title Line', 'codevz' )
			]
		);

		$this->add_control(
			'bline',
			[
				'label' => esc_html__( 'Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'cz_line_before_title' => esc_html__( 'Above title', 'codevz' ),
					'cz_line_after_title' => esc_html__( 'Below title', 'codevz' ),
					'cz_line_left_side' => esc_html__( 'Left Side', 'codevz' ),
					'cz_line_right_side' => esc_html__( 'Right Side', 'codevz' ),
					'cz_line_both_side' => esc_html__( 'Both Side', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'css_right_line_left',
			[
				'label' => esc_html__( 'Horizontal Offset Right Line', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [  'px' ],
				'range' => [
					'px' => [
						'min' => -80,
						'max' => 80,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_line_side_after' => ( Codevz_Plus::$is_rtl ? 'right' : 'left' ) . ': {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'bline' => ['cz_line_right_side' , 'cz_line_both_side'],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cz_title_icbt',
			[
				'label' => esc_html__( 'Icon Before Title', 'codevz' )
			]
		);

		$this->add_control(
			'icon_before_type',
			[
				'label' => esc_html__( 'Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'icon' => esc_html__( 'Iocn', 'codevz' ),
					'image' => esc_html__( 'Image', 'codevz' ),
					'number' => esc_html__( 'Number', 'codevz' ),
				],
			]
		);
		
		
		$this->add_control(
			'image_as_icon',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'icon_before_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_as_icon', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension1`.
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'icon_before_type' => 'image',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'icon_before_type' => 'icon'
				],
			]
		);

		$this->add_control(
			'number',
			[
				'label' => esc_html__( 'Number', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'icon_before_type' => 'number',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cz_title_icat',
			[
				'label' => esc_html__( 'Icon After Title', 'codevz' )
			]
		);

		$this->add_control(
			'icon_after_type',
			[
				'label' => esc_html__( 'Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'icon' => esc_html__( 'Iocn', 'codevz' ),
					'image' => esc_html__( 'Image', 'codevz' ),
					'number' => esc_html__( 'Number', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'image_as_icon_after',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'icon_after_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_as_icon_after', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension2`.
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'icon_after_type' => 'image',
				],
			]
		);

		$this->add_control(
			'icon_after',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'icon_after_type' => 'icon'
				],
			]
		);

		$this->add_control(
			'number_after',
			[
				'label' => esc_html__( 'Number', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'icon_after_type' => 'number',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'cz_title_shp',
			[
				'label' => esc_html__( 'Shape', 'codevz' )
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => esc_html__( 'Shape', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'text' => esc_html__( 'Text', 'codevz' ),
					'icon' => esc_html__( 'Icon', 'codevz' ),
					'image' => esc_html__( 'Image', 'codevz' ),
					'circle' => esc_html__( 'Circle', 'codevz' ),
					'circle cz_title_shape_outline' => esc_html__( 'Circle Outline', 'codevz' ),
					'square' => esc_html__( 'Square', 'codevz' ),
					'square cz_title_shape_outline' => esc_html__( 'Square Outline', 'codevz' ),
					'rhombus' => esc_html__( 'Rhombus', 'codevz' ),
					'rhombus cz_title_shape_outline' => esc_html__( 'Rhombus Outline', 'codevz' ),
					'rhombus_radius' => esc_html__( 'Rhombus Radius', 'codevz' ),
					'rhombus_radius cz_title_shape_outline' => esc_html__( 'Rhombus Radius Outline', 'codevz' ),
					'rectangle' => esc_html__( 'Rectangle', 'codevz' ),
					'rectangle cz_title_shape_outline' => esc_html__( 'Rectangle Outline', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'shape' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension3`.
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'shape' => 'image',
				],
			]
		);

		$this->add_control(
			'shape_icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'shape' => 'icon'
				],
			]
		);

		$this->add_control(
			'shape_text',
			[
				'label' => esc_html__( 'Text', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'shape' => 'text',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'cz_title_shp2',
			[
				'label' => esc_html__( 'Shape 2', 'codevz' )
			]
		);

		$this->add_control(
			'shape2',
			[
				'label' => esc_html__( 'Shape 2', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'text' => esc_html__( 'Text', 'codevz' ),
					'icon' => esc_html__( 'Icon', 'codevz' ),
					'image' => esc_html__( 'Image', 'codevz' ),
					'circle' => esc_html__( 'Circle', 'codevz' ),
					'circle cz_title_shape_outline' => esc_html__( 'Circle Outline', 'codevz' ),
					'square' => esc_html__( 'Square', 'codevz' ),
					'square cz_title_shape_outline' => esc_html__( 'Square Outline', 'codevz' ),
					'rhombus' => esc_html__( 'Rhombus', 'codevz' ),
					'rhombus cz_title_shape_outline' => esc_html__( 'Rhombus Outline', 'codevz' ),
					'rhombus_radius' => esc_html__( 'Rhombus Radius', 'codevz' ),
					'rhombus_radius cz_title_shape_outline' => esc_html__( 'Rhombus Radius Outline', 'codevz' ),
					'rectangle' => esc_html__( 'Rectangle', 'codevz' ),
					'rectangle cz_title_shape_outline' => esc_html__( 'Rectangle Outline', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'image2',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'shape2' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image2', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case2 `image_size` and `image_custom_dimension4`.
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'shape2' => 'image',
				],
			]
		);

		$this->add_control(
			'shape_icon2',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'shape2' => 'icon'
				],
			]
		);

		$this->add_control(
			'shape_text2',
			[
				'label' => esc_html__( 'Text', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'shape2' => 'text',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_advanced',
			[
				'label' => esc_html__( 'Advanced', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'smart_fs',
			[
				'label' => esc_html__( 'Smart font size', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'text_center',
			[
				'label' => esc_html__( 'Center on mobile', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'vertical',
			[
				'label' => esc_html__( 'Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'cz_title_vertical' => esc_html__( 'Vertical 1', 'codevz' ),
					'cz_title_vertical cz_title_vertical_outside' => esc_html__( 'Vertical Outside 1', 'codevz' ),
					'cz_title_vertical_2' => esc_html__( 'Vertical 2', 'codevz' ),
					'cz_title_vertical_2 cz_title_vertical_outside' => esc_html__( 'Vertical Outside 2', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'css_transform',
			[
				'label' => esc_html__( 'Rotate', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_wpe_content' => 'transform:rotate({{SIZE}}deg);'
				]
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
			'sk_overall',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_content', '.cz_title:hover .cz_title_content, .cz_title_parent_box:hover .cz_title .cz_title_content' ),
			]
		);

		$this->add_responsive_control(
			'sk_h1',
			[
				'label' 	=> esc_html__( 'H1', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_content h1', '.cz_title:hover .cz_title_content h1' ),
			]
		);

		$this->add_responsive_control(
			'sk_h2',
			[
				'label' 	=> esc_html__( 'H2', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_content h2', '.cz_title:hover .cz_title_content h2' ),
			]
		);

		$this->add_responsive_control(
			'sk_h3',
			[
				'label' 	=> esc_html__( 'H3', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_content h3', '.cz_title:hover .cz_title_content h3' ),
			]
		);

		$this->add_responsive_control(
			'sk_h4',
			[
				'label' 	=> esc_html__( 'H4', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_content h4', '.cz_title:hover .cz_title_content h4' ),
			]
		);

		$this->add_responsive_control(
			'sk_h5',
			[
				'label' 	=> esc_html__( 'H5', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_content h5', '.cz_title:hover .cz_title_content h5' ),
			]
		);

		$this->add_responsive_control(
			'sk_h6',
			[
				'label' 	=> esc_html__( 'H6', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_content h6', '.cz_title:hover .cz_title_content h6' ),
			]
		);

		$this->add_responsive_control(
			'sk_links',
			[
				'label' 	=> esc_html__( 'Links', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_content a' ),
			]
		);

		$this->add_responsive_control(
			'sk_lines',
			[
				'label' 	=> esc_html__( 'Line(s)', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'width', 'height', 'top', 'left' ],
				'condition' => [
					'bline!' => ''
				],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_line span, .cz_title .cz_line_side_solo' ),
			]
		);

		$this->add_responsive_control(
			'sk_lines_con',
			[
				'label' 	=> esc_html__( 'Line(s) container', 'codevz' ),
				'type' 		=> 'stylekit',
				'condition' => [
					'bline!' => ''
				],
				'settings' 	=> [ 'background', 'height', 'top' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_line' ),
			]
		);

		$this->add_responsive_control(
			'sk_icon_before',
			[
				'label' 	=> esc_html__( 'Icon Before', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'transform', 'color', 'font-size', 'background', 'border' ],
				'condition' => [
					'icon_before_type!' => ''
				],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_icon_before', '.cz_title:hover .cz_title_icon_before' ),
			]
		);

		$this->add_responsive_control(
			'sk_icon_after',
			[
				'label' 	=> esc_html__( 'Icon After', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'transform', 'color', 'font-size', 'background', 'border' ],
				'condition' => [
					'icon_after_type!' => ''
				],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_title_icon_after', '.cz_title:hover .cz_title_icon_after' ),
			]
		);

		$this->add_responsive_control(
			'sk_shape',
			[
				'label' 	=> esc_html__( 'Shape', 'codevz' ),
				'type' 		=> 'stylekit',
				'condition' => [
					'shape!' => ''
				],
				'settings' 	=> [ 'top', 'left', 'width', 'height', 'color', 'text-align', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_shape_1', '.cz_title:hover .cz_shape_1' ),
			]
		);

		$this->add_responsive_control(
			'sk_shape2',
			[
				'label' 	=> esc_html__( 'Shape', 'codevz' ) . ' 2',
				'type' 		=> 'stylekit',
				'condition' => [
					'shape2!' => ''
				],
				'settings' 	=> [ 'top', 'left', 'width', 'height', 'color', 'text-align', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_title .cz_shape_2', '.cz_title:hover .cz_shape_2' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$line_before = $line_after = $icon = $icon_after = '';

		ob_start();
		Icons_Manager::render_icon( $settings['shape_icon'] );
		$shape_icon = ob_get_clean();

		// Shape
		$shape = $settings['shape'];
		if ( $shape === 'text' ) {
			$shape_content = $settings['shape_text'];
		} else if ( $shape === 'icon' ) {
			$shape_content = $shape_icon;
		} else if ( $shape === 'image' ) {
			$shape_content = Group_Control_Image_Size::get_attachment_image_html( $settings );
		} else {
			$shape_content = '';
		}

		$shape_out = '';

		if ( $shape ) {
			$shape_out .= $shape ? '<div class="cz_title_shape shape_' . $shape . ' cz_shape_1">' . $shape_content . '</div>' : '';
		}

		ob_start();
		Icons_Manager::render_icon( $settings['shape_icon2'] );
		$shape_icon2 = ob_get_clean();

		// Shape 2
		$shape2 = $settings['shape2'];

		if ( $shape2 === 'text' ) {
			$shape_content2 = $settings['shape_text2'];
		} else if ( $shape2 === 'icon' ) {
			$shape_content2 = $shape_icon2;
		} else if ( $shape2 === 'image' ) {
			$shape_content2 = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image2' );
		} else {
			$shape_content2 = '';
		}

		if ( $shape2 ) {
			$shape_out .= $shape2 ? '<div class="cz_title_shape shape_' . $shape2 . ' cz_shape_2">' . $shape_content2 . '</div>' : '';
		}

		if ( $settings['bline'] ) {
			$line = '<div class="cz_title_line ' . $settings['bline'] . '"><span>_</span></div>';
			$line_before = ( $settings['bline'] === 'cz_line_before_title' ) ? $line : '';
			$line_before = ( $settings['bline'] === 'cz_line_left_side' || $settings['bline'] === 'cz_line_both_side' ) ? '<span class="cz_line_side_solo">_</span>' : $line_before;
			$line_after = ( $settings['bline'] === 'cz_line_after_title' ) ? $line : '';
			//$bline_css = ( $settings['css_right_line_left'] && $settings['bline'] === 'cz_line_both_side' ) ? ' style="' . $settings['css_right_line_left'] . '"' : '';
			$line_after = ( $settings['bline'] === 'cz_line_both_side' || $settings['bline'] === 'cz_line_right_side' ) ? '<span class="cz_line_side_solo cz_line_side_after">_</span>' : $line_after;
		}

		ob_start();
		Icons_Manager::render_icon( $settings['icon'], [ 'class' => 'cz_title_icon_before' ] );
		$icon = ob_get_clean();

		// Icon before
		if ( $settings['icon_before_type'] === 'image' && $settings['image_as_icon'] ) {
			$icon = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_as_icon' );
			$icon = '<span class="cz_title_icon_before cz_title_image">' . $icon . '</span>';
		} else if ( $settings['icon_before_type'] === 'icon' && $settings['icon'] ) {
			$icon =  $icon;
		} else if ( $settings['icon_before_type'] === 'number' && $settings['number'] ) {
			$icon = '<i class="cz_title_icon_before cz_title_number"><span>' . $settings['number'] . '</span></i>';
		}

		ob_start();
		Icons_Manager::render_icon( $settings['icon_after'], [ 'class' => 'cz_title_icon_after' ] );
		$icon_after = ob_get_clean();

		// Icon after
		if ( $settings['icon_after_type'] === 'image' && $settings['image_as_icon_after'] ) {
			$icon_after = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_as_icon_after' );
			$icon_after = '<span class="cz_title_icon_after cz_title_image">' . $icon_after . '</span>';
		} else if ( $settings['icon_after_type'] === 'icon' && $settings['icon_after'] ) {
			$icon_after = $icon_after;
		} else if ( $settings['icon_after_type'] === 'number' && $settings['number_after'] ) {
			$icon_after = '<i class="cz_title_icon_after cz_title_number"><span>' . $settings['number_after'] . '</span></i>';
		}

		// Classes
		$classes = array();
		$classes[] = 'cz_title clr';
		$classes[] = $settings['smart_fs'] ? 'cz_smart_fs' : '';
		$classes[] = $settings['text_center'] ? 'cz_mobile_text_center' : '';
		$classes[] =  $icon || $icon_after  ? 'cz_title_has_icon' : '';

		if ( isset( $settings['sk_overall']['normal'] ) ) {
			$classes[] = Codevz_Plus::contains( $settings['sk_overall']['normal'], 'background' )  ? 'cz_title_has_bg' : '';
			$classes[] = Codevz_Plus::contains( $settings['sk_overall']['normal'], 'border-width' )  ? 'cz_title_has_border' : '';
		}

		$classes[] = ( Codevz_Plus::contains( $settings['bline'], 'before' )  || Codevz_Plus::contains( $settings['bline'], 'after' ) )  ? 'cz_title_ba_line' : '';
		$classes[] = $settings['vertical'];
		$classes[] = $settings['title_pos'];
		$content =  $settings['content']; 

		if ( strpos( $content, 'center;' ) !== false || strpos( $content, ': center' ) !== false ) {
			$classes[] = 'tac';
		} else if ( strpos( $content, 'right;' ) !== false || strpos( $content, ': right' ) !== false ) {
			$classes[] = 'tar';
		}

		// Final content
		$out_content = $shape_out . '<div class="cz_title_content">' . $line_before . $icon . '<div class="cz_wpe_content">' . ( function_exists( 'wpb_js_remove_wpautop' ) ? wpb_js_remove_wpautop( $content, true ) : do_shortcode( $content ) ) . '</div>' . $icon_after . $line_after . '</div>';

		// Check link
		if ( ! empty( $settings[ 'link' ][ 'url' ] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
			$out_content = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . preg_replace( '/<a .*?<\/a>/', '', $out_content ) . '</a>';
		}

		Xtra_Elementor::parallax( $settings );
		?>
	
		<div>
			<div<?php echo Codevz_Plus::classes( [], $classes ); ?>><?php echo wp_kses_post( $out_content ); ?></div>
		</div>
		
		<?php

		Xtra_Elementor::parallax( $settings, true );
	}
	
	
	public function content_template() {
		?>
		<#
		if ( settings.image_as_icon.url ) {
			var image_as_icon = {
				id: settings.image_as_icon.id,
				url: settings.image_as_icon.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_as_icon_url = elementor.imagesManager.getImageUrl( image_as_icon );

			if ( ! image_as_icon_url ) {
				return;
			}
		}

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

		if ( settings.image2.url ) {
			var image2 = {
				id: settings.image2.id,
				url: settings.image2.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image2_url = elementor.imagesManager.getImageUrl( image2 );

			if ( ! image2_url ) {
				return;
			}
		}

		if ( settings.image_as_icon_after.url ) {
			var image_as_icon_after = {
				id: settings.image_as_icon_after.id,
				url: settings.image_as_icon_after.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_as_icon_after_url = elementor.imagesManager.getImageUrl( image_as_icon_after );

			if ( ! image_as_icon_after_url ) {
				return;
			}
		}

		// Shapes
		var line_before = line_after = icon = icon_after = '',
			iconShape = elementor.helpers.renderIcon( view, settings.shape_icon, {}, 'i' , 'object' ),
			iconShape2 = elementor.helpers.renderIcon( view, settings.shape_icon2, {}, 'i' , 'object' ),
			shape = settings.shape, 
			shape_content = '';

		if ( shape === 'text' ) {
		   shape_content = settings.shape_text;
		} else if ( shape === 'icon' ) {
			shape_content = iconShape.value || '';
		} else if ( shape === 'image' ) {
			shape_content = '<img src="' + image_url + '">';
		}

		var shape_out = '';
		if ( shape ) {
			shape_out = shape ? '<div class="cz_title_shape shape_' + shape + ' cz_shape_1">' + shape_content + '</div>' : '';
		}

		//Shape2
		var shape2 = settings.shape2,
			shape_content2 = '';

		if ( shape2 === 'text' ) {
			shape_content2 = settings.shape_text2;
		} else if ( shape2 === 'icon' ) {
			shape_content2 = iconShape2.value || '';
		} else if ( shape2 === 'image' ) {
			shape_content2 = '<img src="' + image2_url + '">';
		}

		if ( shape2 ) {
			shape_out = shape_out + ( shape2 ? '<div class="cz_title_shape shape_' + shape2 + ' cz_shape_2">' + shape_content2 + '</div>' : '' );
		}

		if ( settings.bline ) {
			var line = '<div class="cz_title_line ' + settings.bline + '"><span>_</span></div>';
			var line_before = ( settings.bline === 'cz_line_before_title' ) ? line : '',
				line_before = ( settings.bline === 'cz_line_left_side' || settings.bline === 'cz_line_both_side' ) ? '<span class="cz_line_side_solo">_</span>' : line_before;
			var line_after = ( settings.bline === 'cz_line_after_title' ) ? line : '';
			var bline_css = ( settings.css_right_line_left && settings.bline === 'cz_line_both_side' ) ? ' style="' + settings.css_right_line_left + '"' : '';
			var line_after = ( settings.bline === 'cz_line_both_side' || settings.bline === 'cz_line_right_side' ) ? '<span class="cz_line_side_solo cz_line_side_after" + bline_css + >_</span>' : line_after;
		}

		var iconBefore = elementor.helpers.renderIcon( view, settings.icon, { 'class': 'cz_title_icon_before' }, 'i' , 'object' );
		var iconAfter = elementor.helpers.renderIcon( view, settings.icon_after, { 'class': 'cz_title_icon_after' }, 'i' , 'object' );

		// Icon before
		if ( settings.icon_before_type === 'image' && settings.image_as_icon ) {
			icon = '<img src="' + image_as_icon_url + '">';
			icon = '<span class="cz_title_icon_before cz_title_image">' + icon + '</span>';
		} else if ( settings.icon_before_type === 'icon' && settings.icon ) {
			icon = iconBefore.value || '';
		} else if ( settings.icon_before_type === 'number' && settings.number ) {
			icon = '<i class="cz_title_icon_before cz_title_number"><span>' + settings.number + '</span></i>';
		}

		// Icon after
		if ( settings.icon_after_type === 'image' && settings.image_as_icon_after ) {
			icon_after = '<img src="' + image_as_icon_after_url + '">';
			icon_after = '<span class="cz_title_icon_after cz_title_image">' + icon_after + '</span>';
		} else if ( settings.icon_after_type === 'icon' && settings.icon_after ) {
			icon_after = iconAfter.value || '';
		} else if ( settings.icon_after_type === 'number' && settings.number_after ) {
			icon_after = '<i class="cz_title_icon_after cz_title_number"><span>' + settings.number_after + '</span></i>';
		}

		var classes = 'cz_title clr', 
			classes = settings.vertical 	? classes + ' ' + settings.vertical : classes,
			classes = settings.title_pos 	? classes + ' ' + settings.title_pos : classes,
			classes = settings.smart_fs 	? classes + ' cz_smart_fs' : classes,
			classes = settings.text_center 	? classes + ' cz_mobile_text_center' : classes,
			classes = icon || icon_after 	? classes + ' cz_title_has_icon' : classes;

		if ( settings.sk_overall.normal ) {
			classes = classes + ( settings.sk_overall.normal.indexOf( 'background' ) >= 0 ? ' cz_title_has_bg' : '' );
			classes = classes + ( settings.sk_overall.normal.indexOf( 'border-widt' ) >= 0 ? ' cz_title_has_border' : '' );
		}

		classes = classes + ( ( settings.bline.indexOf( 'before' ) >= 0 || settings.bline.indexOf( 'after' ) >= 0 ) ? ' cz_title_ba_line' : '' );

		var content =  settings.content; 
		if ( content.indexOf( 'center;' ) >= 0 || content.indexOf( ': center' ) >= 0 ) {
			classes = classes + ' tac';
		} else if ( content.indexOf( 'right;' ) >= 0 || content.indexOf( ': right' ) >= 0 ) {
			classes = classes + ' tar';
		}

		// Final content
		var out_content = shape_out + '<div class="cz_title_content">' + line_before + icon + '<div class="cz_wpe_content">' + content + '</div>' + icon_after + line_after + '</div>';

		if ( settings.link.url ) {
			out_content = '<a href="' + settings.link.url + '">' + out_content.replace( /<a\b[^>]*>(.*?)<\/a>/i, "" ) + '</a>';
		}

		var parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );
		#>

		{{{ parallaxOpen }}}

		<div>
			<div class="{{{classes}}}">{{{out_content}}}</div>
		</div>

		{{{ parallaxClose }}}    
		<?php

	}
}