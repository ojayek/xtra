<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

/**
 * Elementor extensions.
 * 
 * @since  4.2.0
 * @author XtraTheme
 */

class Xtra_Elementor {

	protected static $instance = null;

	protected function __construct() {

		// Register categories.
		add_action( 'elementor/elements/categories_registered', [ $this, 'categories' ] );

		// Register controls.
		add_action( 'elementor/controls/controls_registered', [ $this, 'controls' ] );

		// Register widgets.
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'widgets' ], 11 );

		// Enqueue scripts for Elementor.
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// Frontend.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts_frontend' ], 11 );

		// Add custom icons.
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'icons_manager' ] );

		// Add custom controls to exiting widgets.
		add_action( 'elementor/element/after_section_end', [ $this, 'after_section_end' ], 10, 3 );

		// Add particles to section.
		add_action( 'elementor/frontend/section/before_render', [ $this, 'before_section_render' ], 10 );

		// Add tilt effect to 
		add_action( 'elementor/frontend/column/before_render', [ $this, 'before_column_render' ], 10 );

		// AJAX.
		add_action( 'wp_ajax_cz_ajax_elementor_posts', [ $this, 'posts_grid_items' ] );
		add_action( 'wp_ajax_nopriv_cz_ajax_elementor_posts', [ $this, 'posts_grid_items' ] );

	}

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Add elementor widgets categories.
	 * 
	 * @var $elements_manager = Elementor manager object.
	 */
	public function categories( $elements_manager ) {

		$elements_manager->add_category(
			'xtra',
			[
				'title' => esc_html__( 'XTRA', 'plugin-name' ),
				'icon' => 'fa fa-plug',
			]
		);

	}

	/**
	 * Register custom elementor controls.
	 * 
	 * @var $controls_registry = Elementor control manager.
	 */
	public function controls( $controls_registry ) {

		// Require all new controls.
		foreach( glob( Codevz_Plus::$dir . 'elementor/controls/*.php' ) as $i ) {

			require_once( $i );

			$name = str_replace( '.php', '', basename( $i ) );

			$class = 'Xtra_Elementor_Control_' . $name;

			\Elementor\Plugin::$instance->controls_manager->register_control( $name, new $class() );

		}

	}

	/**
	 * Register elementor widgets.
	 * 
	 * @var $elements_manager = Elementor manager object.
	 */
	public function widgets( $elements_manager ) {

		// Require all new widgets.
		foreach( glob( Codevz_Plus::$dir . 'elementor/widgets/*.php' ) as $i ) {

			require_once( $i );

			$name = str_replace( '.php', '', basename( $i ) );
			$class = 'Xtra_Elementor_Widget_' . $name;

			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class() );

		}

	}

	/**
	 * Enqueue scripts for elementor.
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'xtra-elementor', Codevz_Plus::$url . 'elementor/assets/js/elementor.js', [], Codevz_Plus::$ver, false );

	}

	/**
	 * Enqueue scripts for elementor.
	 */
	public function enqueue_scripts_frontend() {

		wp_enqueue_style( 'xtra-elementor-front', Codevz_Plus::$url . 'assets/css/elementor.css', [], Codevz_Plus::$ver );

		// Font families in Elementor.
		$elementor_data = Codevz_Plus::get_string_between( get_post_meta( get_the_id(), '_elementor_data', true ), 'font-family:', ';', true );

		if ( is_array( $elementor_data ) ) {

			foreach( $elementor_data as $font ) {

				Codevz_Plus::load_font( esc_html( Codevz_Plus::get_string_between( $font, 'font-family:', ';' ) ) );

			}

		}

	}

	/**
	 * StyleKit selectors.
	 * 
	 * @var $normal = normal CSS selector.
	 * @var $hover = optional hover CSS selector.
	 * 
	 * @return array
	 */
	public static function sk_selectors( $normal, $hover = '' ) {

		// Replaces.
		$normal = str_replace( ', ', ',', $normal );
		$hover = str_replace( ', ', ',', $hover );

		// Fix empty hover
		if ( ! $hover ) {
			$hover = $normal . ':hover';
			$hover = str_replace( ',', ':hover,', $hover );
		}

		// Selectors.
		$normal = '{{WRAPPER}} ' . str_replace( ',', ',{{WRAPPER}} ', $normal );
		$hover = '{{WRAPPER}} ' . str_replace( ',', ',{{WRAPPER}} ', $hover );
		$rtl = '.rtl ' . str_replace( ',', ',.rtl ', $normal );

		return [
			$normal => '{{NORMAL}}',
			$hover 	=> '{{HOVER}}',
			$rtl 	=> '{{RTL}}'
		];

	}

	/**
	 * Adding custom icons to icons control.
	 * 
	 * @var 	$tabs = Available icons manager tabs.
	 * @return  array
	 */
	public function icons_manager( $tabs = [] ) {

		$icons = file_get_contents( Codevz_Plus::$dir . 'admin/fields/icon/01-codevz-icons.json' );
		$icons = str_replace( [ 'fa ', 'czico-' ], '', $icons );
		$icons = json_decode( $icons, true );

		$tabs[ 'xtra-custom-icons' ] = array(
			'name'          => 'xtra-custom-icons',
			'label'         => esc_html__( 'XTRA Custom Icons', 'text-domain' ),
			'labelIcon'     => 'czi czico-xtra',
			'prefix'        => 'czico-',
			'displayPrefix' => 'czi',
			'url'           => CSF_PLUGIN_URL . '/fields/codevz_fields/icons/czicons.css',
			'icons'         => $icons[ 'icons' ],
			'ver'           => Codevz_Plus::$ver
		);

		return $tabs;

	}

	/**
	 * Get array list of available templates.
	 * 
	 * @var $type 		= Template category type
	 * @var $options 	= List of options as array
	 */
	public static function get_templates( $type = null, $options = [] ) {

		$args = [
			'post_type' 		=> 'elementor_library',
			'posts_per_page' 	=> -1,
		];

		if ( $type ) {

			$args[ 'tax_query' ] = [
				[
					'taxonomy' 	=> 'elementor_library_type',
					'field' 	=> 'slug',
					'terms' 	=> $type,
				],
			];

		}

		$options[] = esc_html__( '~ Select ~', 'codevz' );

		$saved_templates = get_posts( $args );

		foreach( $saved_templates as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		return $options;
	}

	/**
	 * Reload JS function on element render in live editor.
	 * 
	 * @var $widget = JS widget function name
	 */
	public static function render_js( $widget ) {

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			echo '<script>Codevz_Plus.tilt();Codevz_Plus.' . esc_attr( $widget ) . '();</script>';
		}

	}

	/**
	 * Add custom contorls to exiting widgets.
	 * 
	 * @var $section 	= object of current widget
	 * @var $section_id = control section ID
	 * @var $args 		= settings
	 */
	public function after_section_end( $section, $section_id, $args ) {

		if ( $section->get_name() === 'section' && $section_id === 'section_advanced' ) {

			$section->start_controls_section(
				'xtra_section_particles',
				[
					'label' 	=> esc_html__( 'XTRA', 'codevz' ) . ' ' . esc_html__( 'Particles', 'codevz' ),
					'tab' 		=> Controls_Manager::TAB_ADVANCED
				]
			);

			$section->add_control(
				'xtra_section_particles_on',
				[
					'label' => esc_html__( 'Particles?', 'codevz' ),
					'type' => Controls_Manager::SWITCHER
				]
			);

			$section->add_responsive_control(
				'xtra_section_particles_min_height',
				[
					'label' => esc_html__( 'Minimum Height', 'codevz' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 300,
							'max' => 1000,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}}' => 'min-height: {{SIZE}}{{UNIT}} !important;',
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_responsive_control(
				'xtra_section_particles_particle_padding',
				[
					'label' => esc_html__( 'Padding', 'plugin-domain' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_shape_type',
				[
					'label' => esc_html__( 'Shape', 'codevz' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'circle',
					'options' => [
						'circle' => esc_html__( 'Circle', 'codevz' ),
						'edge' => esc_html__( 'Edge', 'codevz' ),
						'triangle' => esc_html__( 'Triangle', 'codevz' ),
						'polygon' => esc_html__( 'Polygon', 'codevz' ),
						'star' => esc_html__( 'Star', 'codevz' ),
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_shapes_color',
				[
					'label' => esc_html__( 'Shapes Color', 'codevz' ),
					'type' => Controls_Manager::COLOR,
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_shapes_number',
				[
					'label' => esc_html__( 'Number of shapes', 'codevz' ),
					'type' => Controls_Manager::NUMBER,
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 200,
							'step' => 10,
						],
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_shapes_size',
				[
					'label' => esc_html__( 'Shapes Size', 'codevz' ),
					'type' => Controls_Manager::NUMBER,
					'range' => [
						'px' => [
							'min' => 5,
							'max' => 200,
							'step' => 5,
						],
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_lines_distance',
				[
					'label' => esc_html__( 'Lines Distance', 'codevz' ),
					'type' => Controls_Manager::NUMBER,
					'range' => [
						'px' => [
							'min' => 100,
							'max' => 700,
							'step' => 10,
						],
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_lines_color',
				[
					'label' => esc_html__( 'Lines Color', 'codevz' ),
					'type' => Controls_Manager::COLOR,
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_lines_width',
				[
					'label' => esc_html__( 'Lines Width', 'codevz' ),
					'type' => Controls_Manager::NUMBER,
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 10,
							'step' => 1,
						],
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_move_direction',
				[
					'label' 	=> esc_html__( 'Move Direction', 'codevz' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'none',
					'options' 	=> [
						'none' 		=> esc_html__( '~ Default ~', 'codevz' ),
						'top' 		=> esc_html__( 'Top', 'codevz' ),
						'right' 	=> esc_html__( 'Right', 'codevz' ),
						'bottom' 	=> esc_html__( 'Bottom', 'codevz' ),
						'left' 		=> esc_html__( 'Left', 'codevz' ),
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);
			
			$section->add_control(
				'xtra_section_particles_move_speed',
				[
					'label' => esc_html__( 'Move Speed', 'codevz' ),
					'type' => Controls_Manager::NUMBER,
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 50,
							'step' => 1,
						],
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_move_out_mode',
				[
					'label' => esc_html__( 'Move Out Mode', 'codevz' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'out',
					'options' => [
						'out' => esc_html__( 'Out', 'codevz' ),
						'bounce' => esc_html__( 'Bounce', 'codevz' ),
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control(
				'xtra_section_particles_on_hover',
				[
					'label' => esc_html__( 'On Hover', 'codevz' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'grab',
					'options' => [
						'grab' => esc_html__( 'Grab', 'codevz' ),
						'bubble' => esc_html__( 'Bubble', 'codevz' ),
						'repulse' => esc_html__( 'Repulse', 'codevz' ),
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->add_control (
				'xtra_section_particles_on_click',
				[
					'label' => esc_html__( 'On Click', 'codevz' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'push',
					'options' => [
						'push' => esc_html__( 'Push', 'codevz' ),
						'remove' => esc_html__( 'Remove', 'codevz' ),
						'bubble' => esc_html__( 'Bubble', 'codevz' ),
						'repulse' => esc_html__( 'Repulse', 'codevz' ),
					],
					'condition' 	=> [
						'xtra_section_particles_on!' 	=> ''
					],
				]
			);

			$section->end_controls_section();

		} else if ( $section->get_name() === 'column' ) {

			if ( $section_id === 'section_typo' ) {

				$section->start_controls_section(
					'xtra_column_sks',
					[
						'label' 	=> esc_html__( 'XTRA', 'codevz' ) . ' ' . esc_html__( 'StyleKits', 'codevz' ),
						'tab' 		=> Controls_Manager::TAB_STYLE
					]
				);

				$section->add_control(
					'xtra_stretch_column',
					[
						'label'        => esc_html__( 'Background stretch', 'codevz' ),
						'description'  => esc_html__( 'Background color for container is required.', 'codevz' ),
						'type'         => Elementor\Controls_Manager::SELECT,
						'options'      => [
							'' 					=> 'Select', 
							'xtra-full-before' 	=> esc_html__( 'Stretch to left', 'codevz' ),
							'xtra-full-after' 	=> esc_html__( 'Stretch to right', 'codevz' ),
						],
						'prefix_class' => 'column-'
					]
				);

				$section->add_responsive_control(
					'xtra_column_sk',
					[
						'label' 	=> esc_html__( 'Container', 'codevz' ),
						'type' 		=> 'stylekit',
						'settings' 	=> [ 'color', 'background', 'border' ],
						'selectors' => self::sk_selectors( ' > .elementor-element-populated' ),
					]
				);

				$section->add_responsive_control(
					'xtra_column_background_layer_sk',
					[
						'label' 	=> esc_html__( 'Background layer', 'codevz' ),
						'type' 		=> 'stylekit',
						'settings' 	=> [ 'background', 'top', 'left', 'border', 'width', 'height' ],
						'selectors' => self::sk_selectors( ' > .elementor-element-populated:before' ),
					]
				);

				$section->add_responsive_control(
					'xtra_column_links_sk',
					[
						'label' 	=> esc_html__( 'Links', 'codevz' ),
						'type' 		=> 'stylekit',
						'settings' 	=> [ 'color', 'background', 'border' ],
						'selectors' => self::sk_selectors( ' > .elementor-element-populated a', ' > .elementor-element-populated:hover a' ),
					]
				);

				$section->end_controls_section();

			} else if ( $section_id === 'section_advanced' ) {

				$section->start_controls_section(
					'section_xtra_column_advanced',
					[
						'label' 	=> esc_html__( 'XTRA', 'codevz' ) . ' ' . esc_html__( 'Advanced', 'codevz' ),
						'tab' 		=> Controls_Manager::TAB_ADVANCED
					]
				);

				$section->add_control(
					'xtra_nomral_effect',
					[
						'label'        => esc_html__( 'Normal effect', 'codevz' ),
						'type'         => Elementor\Controls_Manager::SELECT,
						'options'      => array_flip( Codevz_Plus::fx() ),
						'prefix_class' => 'column-'
					]
				);

				$section->add_control(
					'xtra_hover_effect',
					[
						'label'        => esc_html__( 'Hover effect', 'codevz' ),
						'type'         => Elementor\Controls_Manager::SELECT,
						'options'      => array_flip( Codevz_Plus::fx( '_hover' ) ),
						'prefix_class' => 'column-'
					]
				);

				$section->add_control(
					'xtra_sticky_column' ,
					[
						'label'        	=> esc_html__( 'Sticky column?', 'codevz' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'prefix_class' 	=> 'column-',
						'label_on' 		=> esc_html__( 'Yes', 'codevz' ),
						'label_off'		=> esc_html__( 'No', 'codevz' ),
						'return_value' 	=> 'xtra-sticky',
					]
				);

				$section->add_control(
					'tilt',
					[
						'label'        	=> esc_html__( 'Tilt effect', 'codevz' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> '',
						'label_on' 		=> esc_html__( 'Yes', 'codevz' ),
						'label_off'		=> esc_html__( 'No', 'codevz' ),
						'return_value' 	=> 'on',
					]
				);

				$section->add_control(
					'glare',
					[
						'label' => esc_html__( 'Glare', 'codevz' ),
						'type' => Controls_Manager::SELECT,
						'default' => '0',
						'options' => [
							'0' 	=> '0',
							'0.2' 	=> '0.2',
							'0.4' 	=> '0.4',
							'0.6' 	=> '0.6',
							'0.8' 	=> '0.8',
							'1' 	=> '1',
						],
						'condition' => [
							'tilt' 		=> 'on'
						],
					]
				);

				$section->add_control(
					'scale',
					[
						'label' => esc_html__( 'Scale', 'codevz' ),
						'type' => Controls_Manager::SELECT,
						'default' => '1',
						'options' => [
							'0.9' 	=> '0.9',
							'0.8' 	=> '0.8',
							'1' 	=> '1',
							'1.1' 	=> '1.1',
							'1.2' 	=> '1.2',
						],
						'condition' => [
							'tilt' => 'on'
						],
					]
				);

				$section->end_controls_section();

			}

		}

	}

	/**
	 * Add particles before section.
	 * 
	 * @var $widget = elementor widget object
	 */
	public function before_section_render( $widget ) {

		$settings = $widget->get_active_settings();

		if ( $settings[ 'xtra_section_particles_on' ] ) {

			$widget->add_render_attribute( '_wrapper', [
				'id' 					=> 'xtra_' . esc_attr( $widget->get_id() ),
				'class' 				=> 'cz-particles'
			] );

			wp_enqueue_style( 'cz_particles' );
			wp_enqueue_script( 'cz_particles' );

			echo '
<script>

	jQuery( function( $ ) {

		setTimeout(function() {
			if ( typeof particlesJS != "undefined" ) {

				particlesJS("xtra_' . esc_attr( $widget->get_id() ) . '", {
				  "particles": {
				    "number": {
				      "value": ' . ( $settings['xtra_section_particles_shapes_number'] ? $settings['xtra_section_particles_shapes_number'] : 100 ) . '
				    },
				    "color": {
				      "value": "' . ( $settings['xtra_section_particles_shapes_color'] ? $settings['xtra_section_particles_shapes_color'] : '#a7a7a7' ) . '"
				    },
				    "shape": {
				      "type": "' . $settings['xtra_section_particles_shape_type'] . '",
				    },
				    "line_linked": {
				      "enable": ' . ( ( $settings['xtra_section_particles_lines_width'] == 0 ) ? 'false' : 'true' ) . ',
				      "distance": ' . ( $settings['xtra_section_particles_lines_distance'] ? $settings['xtra_section_particles_lines_distance'] : 150 ) . ',
				      "color": "' . ( $settings['xtra_section_particles_lines_color'] ? $settings['xtra_section_particles_lines_color'] : '#a7a7a7' ) . '",
				      "opacity": 0.4,
				      "width": ' . ( $settings['xtra_section_particles_lines_width'] ? $settings['xtra_section_particles_lines_width'] : 1 ) . '
				    },
				    "opacity": {
				      "value": 0.5,
				      "random": true,
				      "anim": {
				        "enable": false,
				        "speed": 1,
				        "opacity_min": 0.1,
				        "sync": false
				      }
				    },
				    "size": {
				      "value": ' . ( $settings['xtra_section_particles_shapes_size'] ? $settings['xtra_section_particles_shapes_size'] : 5 ) . ',
				      "random": true,
				      "anim": {
				        "enable": false,
				        "speed": 40,
				        "size_min": 0.1,
				        "sync": false
				      }
				    },
				    "move": {
				      "enable": true,
				      "speed": ' . ( $settings['xtra_section_particles_move_speed'] ? $settings['xtra_section_particles_move_speed'] : 6 ) . ',
				      "direction": "' . $settings['xtra_section_particles_move_direction'] . '",
				      "random": false,
				      "straight": false,
				      "out_mode": "' . $settings['xtra_section_particles_move_out_mode'] . '",
				      "bounce": false,
				      "attract": {
				        "enable": false,
				        "rotateX": 600,
				        "rotateY": 1200
				      }
				    }
				  },
				  "interactivity": {
				    "detect_on": "canvas",
				    "events": {
				      "onhover": {
				        "enable": true,
				        "mode": "' . $settings['xtra_section_particles_on_hover'] . '"
				      },
				      "onclick": {
				        "enable": true,
				        "mode": "' . $settings['xtra_section_particles_on_click'] . '"
				      },
				      "resize": true
				    },
				    "modes": {
				      "grab": {
				        "distance": 100,
				        "line_linked": {
				          "opacity": ' . ( ( $settings['xtra_section_particles_lines_width'] == 0 ) ? '0' : '1' ) . '
				        }
				      },
				      "bubble": {
				        "distance": 400,
				        "size": 40,
				        "duration": 2,
				        "opacity": 8,
				        "speed": 3
				      },
				      "repulse": {
				        "distance": 200,
				        "duration": 0.4
				      },
				      "push": {
				        "particles_nb": 4
				      },
				      "remove": {
				        "particles_nb": 2
				      }
				    }
				  },
				  "retina_detect": true
				});
			}
		}, 2000 );

	});

</script>';

		}

	}

	/**
	 * Add custom attributes before render section.
	 * 
	 * @var $widget = elementor widget object
	 */
	public function before_column_render( $widget ) {

		$settings = $widget->get_active_settings();

		if ( ! empty( $settings[ 'tilt' ] ) ) {

			wp_enqueue_style( 'codevz-tilt' );
			wp_enqueue_script( 'codevz-tilt' );

			$widget->add_render_attribute( '_wrapper', [
				'data-tilt' 			=> 'true',
				'data-tilt-maxGlare' 	=> $settings['glare'],
				'data-tilt-scale' 		=> $settings['scale'],
			] );

		}

	}

	/**
	 * Parallax settings for widget.
	 * 
	 * @var $widget = elementor widget object
	 */
	public static function parallax_settings( $widget, $repeater = false ) {

		if ( ! $repeater ) {

			$widget->start_controls_section(
				'section_parallax',
				[
					'label' => esc_html__( 'Parallax', 'codevz' )
				]
			);

		}

		$widget->add_control(
			'parallax',
			[
				'label' 	=> esc_html__( 'Parallax', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> [
					'' 			=> esc_html__( 'Select', 'codevz' ),
					'v' 		=> esc_html__( 'Vertical', 'codevz' ),
					'vmouse' 	=> esc_html__( 'Vertical + Mouse Parallax', 'codevz' ),
					'true' 		=> esc_html__( 'Horizontal', 'codevz' ),
					'truemouse' => esc_html__( 'Horizontal + Mouse Parallax', 'codevz' ),
					'mouse' 	=> esc_html__( 'Mouse Parallax', 'codevz' ),
				],
			]
		);

		$widget->add_control(
			'parallax_speed',
			[
				'label' 	=> esc_html__( 'Parallax Speed', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'min' 		=> -100,
				'step' 		=> 1,
				'max' 		=> 100,
				'condition' => [
					'parallax' => [ 'v', 'vmouse', 'true', 'truemouse' ]
				]
			]
		);

		$widget->add_control(
			'parallax_stop',
			[
				'label' 	=> esc_html__( 'Stop when done', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'condition' => [
					'parallax' => [ 'v', 'vmouse', 'true', 'truemouse' ]
				]
			]
		);

		$widget->add_control(
			'mouse_speed',
			[
				'label' 	=> esc_html__( 'Mouse Speed', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'min' 		=> -100,
				'step' 		=> 1,
				'max' 		=> 100,
				'condition' => [
					'parallax' => [ 'vmouse', 'truemouse', 'mouse' ]
				]
			]
		);

		if ( ! $repeater ) {

			$widget->end_controls_section();

		}

	}

	/**
	 * Parallax HTML for widget.
	 * 
	 * @var $settings = elementor widget settings array
	 * @var $close = close parallax html
	 * 
	 * @return string
	 */
	public static function parallax( $settings = [], $close = false ) {

		if ( ! empty( $settings['parallax'] ) && ! isset( $settings['parallax']['size'] ) ) {

			if ( $close ) {

				if ( $settings['parallax'] !== 'mouse' ) {
					echo '</div>';
				}

				if ( ! empty( $settings['mouse_speed'] ) && Codevz_Plus::contains( $settings['parallax'], 'mouse' ) ) {
					echo '</div>';
				}

				if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] === 'elementor' ) {
					echo '<script>Codevz_Plus.parallax();Codevz_Plus.tilt();</script>';
				}

			} else {

				$ph = empty( $settings['parallax'] ) ? '' : $settings['parallax'];
				$pp = empty( $settings['parallax_speed'] ) ? '' : $settings['parallax_speed'];
				$pp .= empty( $settings['parallax_stop'] ) ? '' : ' cz_parallax_stop';

				if ( ! empty( $settings['mouse_speed'] ) && Codevz_Plus::contains( $ph, 'mouse' ) ) {
					echo '<div class="cz_mparallax_' . $settings['mouse_speed'] . '">';
				}

				if ( $pp ) {

					$d = ( $ph == 'true' || $ph === 'truemouse' ) ? 'h' : 'v';
					echo '<div class="clr cz_parallax_' . $d . '_' . $pp . '">';

				}

			}

		}

	}

	/**
	 * Generate carousel content for elementor elements.
	 * 
	 * @var $atts = element settings
	 * @var $slides = element custom slides
	 * 
	 * @since 4.1.0
	 */
	public static function carousel_elementor( $atts, $slides = '' ) {

		// Slick
		$slick = array(
			'selector'			=> isset( $atts['selector'] ) ? $atts['selector'] : null,
			'slidesToShow'		=> $atts['slidestoshow'] ? (int) $atts['slidestoshow'] : 3, 
			'slidesToScroll'	=> $atts['slidestoscroll'] ? (int) $atts['slidestoscroll'] : 1, 
			'rows'				=> $atts['rows'] ? (int) $atts['rows'] : 1,
			'fade'				=> $atts['fade'] ? true : false, 
			'vertical'			=> $atts['vertical'] ? true : false, 
			'verticalSwiping'	=> $atts['vertical'] ? true : false, 
			'infinite'			=> $atts['infinite'] ? true : false, 
			'speed'				=> 1000, 
			'swipeToSlide' 		=> true,
			'centerMode'		=> $atts['centermode'] ? true : false, 
			'centerPadding'		=> $atts['centerpadding'], 
			'variableWidth'		=> $atts['variablewidth'] ? true : false, 
			'autoplay'			=> $atts['autoplay'] ? true : false, 
			'autoplaySpeed'		=> (int) $atts['autoplayspeed'], 
			'dots'				=> true,
			'counts' 			=> empty( $atts['counts'] ) ? false : true, 
			'adaptiveHeight'	=> false,
			'responsive'		=> array(
  				array(
					'breakpoint'	=> 769,
					'settings'		=> array(
						'slidesToShow' 		=> isset( $atts['slidestoshow_tablet'] ) ? (int) $atts['slidestoshow_tablet'] : 2,
						'slidesToScroll' 	=> isset( $atts['slidestoscroll_tablet'] ) ? (int) $atts['slidestoscroll_tablet'] : 2,
						'touchMove' 		=> true
					)
				),
  				array(
					'breakpoint'	=> 481,
					'settings'		=> array(
						'slidesToShow' 		=> isset( $atts['slidestoshow_mobile'] ) ? (int) $atts['slidestoshow_mobile'] : 1,
						'slidesToScroll' 	=> isset( $atts['slidestoscroll_mobile'] ) ? (int) $atts['slidestoscroll_mobile'] : 2,
						'touchMove' 		=> true
					)
				),
			)
		);

		// Sync to another
		$sync = '';
		if ( ! empty( $atts['sync'] ) ) {
			$slick['asNavFor'] = '.' . $atts['sync'];
			$slick['focusOnSelect'] = true;
			$sync = 'is_synced ' . str_replace( '.', '', $atts['selector'] );
		}

		// Classes
		$classes = [];
		$classes[] = 'slick';
		$classes[] = $sync;
		$classes[] = $atts['arrows_position'];
		$classes[] = $atts['dots_position'];
		$classes[] = $atts['dots_style'];
		$classes[] = $atts['even_odd'];
		$classes[] = $atts['dots_inner'] ? 'dots_inner' : '';
		$classes[] = $atts['mousewheel'] ? 'cz_mousewheel' : '';
		$classes[] = $atts['dots_show_on_hover'] ? 'dots_show_on_hover' : '';
		$classes[] = $atts['arrows_inner'] ? 'arrows_inner' : '';
		$classes[] = $atts['arrows_show_on_hover'] ? 'arrows_show_on_hover' : '';
		$classes[] = $atts['overflow_visible'] ? 'overflow_visible' : '';
		$classes[] = $atts['centermode'] ? 'is_center' : '';
		$classes[] = $atts['disable_links'] ? 'cz_disable_links' : '';
		$classes[] = $atts['vertical'] ? 'xtra-slick-vertical' : '';

		if ( ! $slides ) {

			$slides = '';

			foreach( $atts[ 'items' ] as $item ) {

				if ( isset( $item[ 'type' ] ) && $item[ 'type' ] === 'template' ) {

					$content = Codevz_Plus::get_page_as_element( $item['xtra_elementor_template'] );

				} else {

					$content = $item[ 'content' ];

				}

				$slides .= '<div class="elementor-repeater-item-' . esc_attr( $item[ '_id' ] ) . '">' . $content . '</div>';

			}

		}

		// Out
		echo '<div' . Codevz_Plus::classes( [], $classes ) . ' data-slick=\'' . json_encode( $slick ) . '\' data-slick-prev="' . esc_attr( isset( $atts['prev_icon']['value'] ) ? $atts['prev_icon']['value'] : '' ) . '" data-slick-next="' . esc_attr( isset( $atts['next_icon']['value'] ) ? $atts['next_icon']['value'] : '' ) . '">' . $slides . '</div>';

		// Fix live preivew.
		Xtra_Elementor::render_js( 'slick' );
	}

	/**
	 * Ajax query get posts
	 * @return string
	 */
	public static function posts_grid_items( $settings = '', $out = '' ) {

		if ( ! empty( $_GET['nonce_id'] ) ) {

			check_ajax_referer( $_GET['nonce_id'], 'nonce' );

			$settings = $_GET;

		}

		// Tax query
		$tax_query = array();

		// Categories
		if ( $settings['cat'] && ! empty( $settings['cat_tax'] ) ) {

			$tax_query[] = array(
				'taxonomy'  => $settings['cat_tax'],
				'field'     => 'term_id',
				'terms'     => is_array( $settings['cat'] ) ? $settings['cat'] : explode( ',', str_replace( ', ', ',', $settings['cat'] ) )
			);

		}

		// Exclude Categories
		if ( $settings['cat_exclude'] && ! empty( $settings['cat_tax'] ) ) {

			$tax_query[] = array(
				'taxonomy'  => $settings['cat_tax'],
				'field'     => 'term_id',
				'terms'     => is_array( $settings['cat_exclude'] ) ? $settings['cat_exclude'] : explode( ',', str_replace( ', ', ',', $settings['cat_exclude'] ) ),
				'operator' 	=> 'NOT IN',
			);

		}

		// Tags
		if ( $settings['tag_id'] && ! empty( $settings['tag_tax'] ) ) {

			$tax_query[] = array(
				'taxonomy'  => $settings[ 'tag_tax' ],
				'field'     => 'term_id',
				'terms'     => is_array( $settings[ 'tag_id' ] ) ? $settings[ 'tag_id' ] : explode( ',', str_replace( ', ', ',', $settings['tag_id'] ) )
			);

		}

		// Exclude Tags
		if ( $settings['tag_exclude'] && ! empty( $settings['tag_tax'] ) ) {

			$tax_query[] = array(
				'taxonomy'  => $settings['tag_tax'],
				'field'     => 'term_id',
				'terms'     => is_array( $settings['tag_exclude'] ) ? $settings['tag_exclude'] : explode( ',', str_replace( ', ', ',', $settings['tag_exclude'] ) ),
				'operator' 	=> 'NOT IN',
			);

		}

		// Post types.
		$settings['post_type'] = $settings['post_type'] ? explode( ',', str_replace( ', ', ',', $settings['post_type'] ) ) : 'post';
		
		// Query args.
		$query = array(
			'post_type' 		=> $settings['post_type'],
			'post_status' 		=> 'publish',
			's' 				=> $settings['s'],
			'posts_per_page' 	=> $settings['posts_per_page'],
			'order' 			=> $settings['order'],
			'orderby' 			=> $settings['orderby'],
			'post__in' 			=> $settings['post__in'],
			'author__in' 		=> $settings['author__in'],
			'tax_query' 		=> $tax_query,
			'paged'				=> get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' )
		);

		// Exclude loaded IDs.
		if ( isset( $settings['ids'] ) && $settings['ids'] !== '0' ) {
			$query['post__not_in'] = explode( ',', $settings['ids'] );
		}

		if ( isset( $settings['category_name'] ) ) {
			$query['category_name'] = $settings['category_name'];
		}
		if ( isset( $settings['tag'] ) ) {
			$query['tag'] = $settings['tag'];
		}
		if ( isset( $settings['s'] ) ) {
			$query['s'] = $settings['s'];
		}

		// Anniversary posts on current day.
		if ( ! empty( $settings['class'] ) && Codevz_Plus::contains( $settings['class'], 'anniversary' ) ) {

			$current_timestamp = current_time( 'timestamp' );

			$query['date_query'] = array(
				'month' => date( 'm', $current_timestamp ),
				'day'   => date( 'j', $current_timestamp )
			);

		}

		// Generate query.
		$query = isset( $settings['wp_query'] ) ? $GLOBALS['wp_query'] : new WP_Query( $query );

		$custom_size = isset( $settings[ 'image_size' ] ) ? $settings[ 'image_size' ] : 'full';

		// Loop
		if ( $query->have_posts() ) {

			$custom_items = [];

			if ( is_array( $settings[ 'custom_items' ] ) ) {

				foreach( $settings[ 'custom_items' ] as $item ) {

					$custom_items[ $item[ 'position' ] - 1 ] = $item;

				}

			}

			$nn = 0;

			while ( $query->have_posts() ) {

				if ( isset( $custom_items[ $nn ] ) ) {

					$out .= '<div class="cz_grid_item elementor-repeater-item-' . esc_attr( $custom_items[ $nn ][ '_id' ] ) . '"><div class="clr">';

					if ( $custom_items[ $nn ][ 'type' ] === 'template' ) {

						$out .= Codevz_Plus::get_page_as_element( $custom_items[ $nn ][ 'xtra_elementor_template' ] );

					} else {

						$out .= do_shortcode( $custom_items[ $nn ][ 'content' ] );

					}

					$out .= '</div></div>';

				}

				$query->the_post();

				$custom_class = '';
				if ( empty( $_GET['nonce_id'] ) && $settings['layout'] === 'cz_posts_list_5' && $nn === 0 ) {
					$custom_class .= ' cz_posts_list_first';
					$settings[ 'image_size' ] = 'codevz_1200_500';
				} else {
					$settings[ 'image_size' ] = $custom_size;
				}

				// Var's
				$id = get_the_id();
				$settings[ 'image' ] = [ 'id' => get_post_thumbnail_id( $id ) ];
				$thumb = Group_Control_Image_Size::get_attachment_image_html( $settings );
				//$thumb = Codevz_Plus::get_image( get_post_thumbnail_id( $id ) );
				$issvg = $thumb ? '' : ' cz_grid_item_svg';
				$thumb = $thumb ? $thumb : '<img src="data:image/svg+xml,%3Csvg%20xmlns%3D&#39;http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg&#39;%20width=&#39;600&#39;%20height=&#39;600&#39;%20viewBox%3D&#39;0%200%20600%20600&#39;%2F%3E" alt="Placeholder" />';
				$no_link = ( Codevz_Plus::contains( $settings['hover'], 'cz_grid_1_subtitle_on_img' ) || ! Codevz_Plus::contains( $settings['hover'], 'cz_grid_1_title_sub_after' ) ) ? 1 : 0;
				$img_fx = empty( $settings['img_fx'] ) ? '' : ' ' . $settings['img_fx'];

				// Excerpt
				if ( $settings['el'] == '-1' ) {

					if ( Codevz_Plus::contains( $settings['hover'], 'excerpt' ) ) {

						$excerpt = '<div class="cz_post_excerpt cz_post_full_content">';

						ob_start();
						the_content();
						$excerpt .= ob_get_clean();

						$excerpt .= '</div>';

					}

				} else {

					if ( $settings['el'] && Codevz_Plus::option( 'post_excerpt' ) < $settings['el'] ) {
						add_action( 'excerpt_length', [ __CLASS__, 'excerpt_length' ], 999 );
					}

					$excerpt = Codevz_Plus::contains( $settings['hover'], 'excerpt' ) ? '<div class="cz_post_excerpt">' . Codevz_Plus::limit_words( get_the_excerpt(), $settings['el'], ( ! empty( $settings['excerpt_rm'] ) ? $settings['excerpt_rm'] : '' ) ) . '</div>' : '';

				}

				// Even & odd
				$custom_class .= ( $nn % 2 == 0 ) ? ' cz_posts_list_even' : ' cz_posts_list_odd';

				// Template
				$out .= '<div data-id="' . get_the_ID() . '" class="' . $settings['post_class'] . ' ' . $custom_class . ' ' . implode( ' ', get_post_class() ) . '"><div class="clr">';

				$add_to_cart = Codevz_Plus::contains( json_encode( $settings['subtitles'] ), 'add_to_cart' );

				$out .= '<a class="cz_grid_link' . $img_fx . $issvg . '" href="' . get_the_permalink() . '"' . $settings['tilt_data'] . '>';
				$out .= Codevz_Plus::contains( $settings['hover'], 'cz_grid_1_no_image' ) ? '' : $thumb;

				if ( $add_to_cart ) {
					$out .= '</a>';
				}

				// Subtitle
				$subs = (array) $settings['subtitles'];
				$subtitle = '';
				foreach ( $subs as $i ) {

					if ( empty( $i['t'] ) ) {
						continue;
					}

					$i['p'] = empty( $i['p'] ) ? '' : $i['p'];
					$i['i'] = empty( $i['i'] ) ? '' : $i['i'];
					$i['tc'] = empty( $i['tc'] ) ? 10 : $i['tc'];
					$i['t'] .= empty( $i['r'] ) ? '' : ' ' . $i['r'];
					$i['ct'] = empty( $i['ct'] ) ? '' : $i['ct'];
					$i['cm'] = empty( $i['cm'] ) ? '' : $i['cm'];

					if ( Codevz_Plus::contains( $i['t'], 'author' ) ) {
						$subtitle .= Codevz_Plus::get_post_data( get_the_author_meta( 'ID' ), $i['t'], $no_link, $i['p'], $i['i'] );
					} else if ( $i['t'] === 'custom_text' || $i['t'] === 'readmore' ) {
						$subtitle .= Codevz_Plus::get_post_data( $id, $i['t'], $i['ct'], '', $i['i'], 0, $i );
					} else if ( $i['t'] === 'custom_meta' ) {
						$subtitle .= Codevz_Plus::get_post_data( $id, $i['t'], $i['cm'], '', $i['i'] );
					} else {
						$subtitle .= Codevz_Plus::get_post_data( $id, $i['t'], $no_link, $i['p'], $i['i'], $i['tc'] );
					}

				}

				// Subtitle b4 or after title
				$small_a = $small_b = $small_c = $det = '';
				if ( $subtitle ) {
					if ( $settings['subtitle_pos'] === 'cz_grid_1_title_rev' ) {
						$small_a = '<small class="clr">' . $subtitle . '</small>';
					} else if ( $settings['subtitle_pos'] === 'cz_grid_1_sub_after_ex' ) {
						$small_c = '<small class="clr">' . $subtitle . '</small>';
					} else {
						$small_b = '<small class="clr">' . $subtitle . '</small>';
					}
				}

				// Post title
				$post_title = $settings['title_lenght'] ? Codevz_Plus::limit_words( get_the_title(), $settings['title_lenght'], '' ) : get_the_title();

				ob_start();
				Icons_Manager::render_icon( $settings['icon']['value'] );
				$icon = ob_get_clean();

				// Details after title
				if ( Codevz_Plus::contains( $settings['hover'], 'cz_grid_1_title_sub_after' ) ) {

					if ( Codevz_Plus::contains( $settings['hover'], 'cz_grid_1_subtitle_on_img' ) ) {
						$out .= '<div class="cz_grid_details">' . $small_a . $small_b . $small_c . '</div>';
						$small_a = $small_b = $small_c = '';
					} else {
						$out .= '<div class="cz_grid_details"><i class="' . ( empty( $settings['icon']['value'] ) ? '' : $settings['icon']['value'] ) . ' cz_grid_icon"></i></div>';
					}

					$det = '<div class="cz_grid_details cz_grid_details_outside">' . $small_a . '<a class="cz_grid_title" href="' . get_the_permalink() . '"><h3>' . $post_title . '</h3></a>' . $small_b . $excerpt . $small_c . '</div>';
				} else {
					$out .= '<div class="cz_grid_details"><i class="' . ( empty( $settings['icon']['value'] ) ? '' : $settings['icon']['value'] ) . ' cz_grid_icon"></i>' . $small_a . '<h3>' . $post_title . '</h3>' . $small_b . $excerpt . $small_c . '</div>';
				}

				if ( ! $add_to_cart ) {
					$out .= '</a>';
				}

				$out .= isset( $det ) ? $det : '';
				$out .= '</div></div>';

				$nn++;
			}
		}

		$settings['loadmore'] = isset( $settings['loadmore'] ) ? $settings['loadmore'] : 0;

		if ( $settings['loadmore'] === 'pagination' ) {
			ob_start();
			$total = $GLOBALS['wp_query']->max_num_pages;
			$GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;

			if ( isset( $GLOBALS['wp_query']->query['paged'] ) ) {
				$current = $GLOBALS['wp_query']->query['paged'];
			//} else if ( isset( $GLOBALS['wp_query']->query['page'] ) ) {
			//	$current = $GLOBALS['wp_query']->query['page'];
			} else {
				$current = 1;
			}

			the_posts_pagination(
				[
					'current'			 => $current,
					'prev_text'          => Codevz_Plus::$is_rtl ? '<i class="fa fa-angle-double-right mr4"></i>' : '<i class="fa fa-angle-double-left mr4"></i>',
					'next_text'          => Codevz_Plus::$is_rtl ? '<i class="fa fa-angle-double-left ml4"></i>' : '<i class="fa fa-angle-double-right ml4"></i>',
					'before_page_number' => ''
				]
			);
			
			$GLOBALS['wp_query']->max_num_pages = $total;
			$out .= '<div class="tac mt40 cz_no_grid">' . ob_get_clean() . '</div>';
		} else if ( $settings['loadmore'] === 'older' ) {
			ob_start();
			$total = $GLOBALS['wp_query']->max_num_pages;
			$GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;
			previous_posts_link();
			next_posts_link();
			$GLOBALS['wp_query']->max_num_pages = $total;
			$out .= '<div class="tac mt40 pagination pagination_old cz_no_grid">' . ob_get_clean() . '</div>';
		}

		// Reset query/postdata
		wp_reset_postdata();
		wp_reset_query();

		// Out
		if ( ! empty( $_GET['nonce_id'] ) ) {
			wp_die( $out );
		} else {
			return $out;
		}
	}

	// Fix custom excerpt length
	public static function excerpt_length() {
		return 99;
	}

}

// Run.
Xtra_Elementor::instance();
