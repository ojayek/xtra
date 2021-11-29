<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Content Box
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_particles {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Particles', 'codevz' ),
			'description'	=> esc_html__( 'Canvas particles container', 'codevz' ),
			'icon'			=> 'czi',
			'is_container' 	=> true,
			'js_view'		=> 'VcColumnView',
			'content_element'=> true,
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'cz_slider',
					'heading' 		=> esc_html__( 'Minimum Height', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => 'px', 'step' => 1, 'min' => 300, 'max' => 1000 ),
					'description' 	=> 'e.g. 300px',
					'param_name' 	=> 'min_height'
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Content in Grid?', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'inner_row'
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Shape', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__( "Shape", 'codevz' ),
					"param_name"  	=> "shape_type",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Circle', 'codevz' ) 		=> 'circle',
						esc_html__( 'Edge', 'codevz' ) 			=> 'edge',
						esc_html__( 'Triangle', 'codevz' ) 		=> 'triangle',
						esc_html__( 'Polygon', 'codevz' ) 		=> 'polygon',
						esc_html__( 'Star', 'codevz' ) 			=> 'star'
					)
				),
				array(
					'type' 			=> 'colorpicker',
					'heading' 		=> esc_html__( 'Shapes Color', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'shapes_color'
				),
				array(
					'type' 			=> 'cz_slider',
					'heading' 		=> esc_html__( 'Number of shapes', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 10, 'min' => 10, 'max' => 200 ),
					'param_name' 	=> 'shapes_number'
				),
				array(
					'type' 			=> 'cz_slider',
					'heading' 		=> esc_html__( 'Shapes Size', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 5, 'min' => 5, 'max' => 200 ),
					'param_name' 	=> 'shapes_size'
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Lines', 'codevz' )
				),
				array(
					'type' 			=> 'cz_slider',
					'heading' 		=> esc_html__( 'Lines Distance', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 10, 'min' => 100, 'max' => 700 ),
					'param_name' 	=> 'lines_distance'
				),
				array(
					'type' 			=> 'colorpicker',
					'heading' 		=> esc_html__( 'Lines Color', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'lines_color'
				),
				array(
					'type' 			=> 'cz_slider',
					'heading' 		=> esc_html__( 'Lines Width', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ),
					'param_name' 	=> 'lines_width'
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Move', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__( "Move Direction", 'codevz' ),
					"param_name"  	=> "move_direction",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( '~ Default ~', 'codevz' ) 		=> 'none',
						esc_html__( 'Top', 'codevz' ) 			=> 'top',
						esc_html__( 'Right', 'codevz' ) 		=> 'right',
						esc_html__( 'Bottom', 'codevz' ) 		=> 'bottom',
						esc_html__( 'Left', 'codevz' ) 			=> 'left',
					)
				),
				array(
					'type' 			=> 'cz_slider',
					'heading' 		=> esc_html__( 'Move Speed', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 50 ),
					'param_name' 	=> 'move_speed'
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__( "Move Out Mode", 'codevz' ),
					"param_name"  	=> "move_out_mode",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Out', 'codevz' ) 		=> 'out',
						esc_html__( 'Bounce', 'codevz' ) 	=> 'bounce',
					)
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Inter Activity', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__( "On Hover", 'codevz' ),
					"param_name"  	=> "on_hover",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Grab', 'codevz' ) 		=> 'grab',
						esc_html__( 'Bubble', 'codevz' ) 	=> 'bubble',
						esc_html__( 'Repulse', 'codevz' ) 	=> 'repulse',
					)
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__( "On Click", 'codevz' ),
					"param_name"  	=> "on_click",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Push', 'codevz' ) 		=> 'push',
						esc_html__( 'Remove', 'codevz' ) 	=> 'remove',
						esc_html__( 'Bubble', 'codevz' ) 	=> 'bubble',
						esc_html__( 'Repulse', 'codevz' ) 	=> 'repulse',
					)
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__( "Extra Class", 'codevz' ),
					"param_name"  	=> "class",
					'edit_field_class' => 'vc_col-xs-99'
				),
			)

		);

		return $wpb ? vc_map( $settings ) : $settings;
	}

	/**
	 *
	 * Shortcode output
	 * 
	 * @return string
	 * 
	 */
	public function out( $atts, $content = '' ) {
		$atts = Codevz_Plus::shortcode_atts( $this, $atts );

		// ID
		if ( empty( $atts['id'] ) ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}

		// Classes
		$classes = array();
		$classes[] = 'cz-particles';
		$classes[] = $atts['class'];

		// Height
		$style = $atts['min_height'] ? ' style="min-height:' . $atts['min_height'] . ' !important"' : '';

		// Inner row.
		$content = do_shortcode( $content );
		$content = $atts['inner_row'] ? '<div class="row clr">' . $content . '</div>' : $content;

		// Output
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . $style . '>' . $content . '</div>';
		$out .= '
<script>

	! function( $ ) {

		setTimeout(function() {
			if ( typeof particlesJS != "undefined" ) {

				particlesJS("' . $atts['id'] . '", {
				  "particles": {
				    "number": {
				      "value": ' . ( $atts['shapes_number'] ? $atts['shapes_number'] : 100 ) . '
				    },
				    "color": {
				      "value": "' . ( $atts['shapes_color'] ? $atts['shapes_color'] : '#a7a7a7' ) . '"
				    },
				    "shape": {
				      "type": "' . $atts['shape_type'] . '",
				    },
				    "line_linked": {
				      "enable": ' . ( ( $atts['lines_width'] == 0 ) ? 'false' : 'true' ) . ',
				      "distance": ' . ( $atts['lines_distance'] ? $atts['lines_distance'] : 150 ) . ',
				      "color": "' . ( $atts['lines_color'] ? $atts['lines_color'] : '#a7a7a7' ) . '",
				      "opacity": 0.4,
				      "width": ' . ( $atts['lines_width'] ? $atts['lines_width'] : 1 ) . '
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
				      "value": ' . ( $atts['shapes_size'] ? $atts['shapes_size'] : 5 ) . ',
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
				      "speed": ' . ( $atts['move_speed'] ? $atts['move_speed'] : 6 ) . ',
				      "direction": "' . $atts['move_direction'] . '",
				      "random": false,
				      "straight": false,
				      "out_mode": "' . $atts['move_out_mode'] . '",
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
				        "mode": "' . $atts['on_hover'] . '"
				      },
				      "onclick": {
				        "enable": true,
				        "mode": "' . $atts['on_click'] . '"
				      },
				      "resize": true
				    },
				    "modes": {
				      "grab": {
				        "distance": 100,
				        "line_linked": {
				          "opacity": ' . ( ( $atts['lines_width'] == 0 ) ? '0' : '1' ) . '
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

	}( jQuery )

</script>';

		return Codevz_Plus::_out( $atts, $out, false, $this->name );
	}

}