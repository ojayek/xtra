<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Gap
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_gap {

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
			'name'			=> esc_html__( 'Gap', 'codevz' ),
			'description'	=> esc_html__( 'Space with custom height', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Height", 'codevz'),
					"param_name"  	=> "height",
					'value'			=> '50px',
					'options' 		=> array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 300 ),
					"edit_field_class" => 'vc_col-xs-99',
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("On Tablet", 'codevz'),
					"param_name"  	=> "height_tablet",
					"edit_field_class" => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 300 ),
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("On Mobile", 'codevz'),
					"param_name"  	=> "height_mobile",
					"edit_field_class" => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => 'px', 'step' => 1, 'min' => 0, 'max' => 300 ),
				),
			)
		);

		return $wpb ? vc_map( $settings ) : $settings;
	}

	/**
	 * Shortcode output
	 */
	public function out( $atts, $content = '' ) {
		$atts = Codevz_Plus::shortcode_atts( $this, $atts );

		$primary_class = $atts['height_tablet'] ? ' hide_on_tablet hide_on_mobile' : '';
		$primary_class .= ( $atts['height_mobile'] && ! $atts['height_tablet'] ) ? ' hide_on_mobile' : '';

		$out = $atts['height'] ? '<div class="cz_gap clr ' . $primary_class . '" style="height: ' . $atts['height'] . '"></div>' : '';
		$show_only_tablet = $atts['height_mobile'] ? ' show_only_tablet' : '';
		$out .= $atts['height_tablet'] ? '<div class="cz_gap show_on_tablet clr ' . $show_only_tablet . '" style="height: ' . $atts['height_tablet'] . '"></div>' : '';
		$out .= $atts['height_mobile'] ? '<div class="cz_gap show_on_mobile clr" style="height: ' . $atts['height_mobile'] . '"></div>' : '';

		return $out;
	}

}