<?php if ( ! defined( 'ABSPATH' ) ) {exit;}

/**
 * Get page content as template
 */

class Codevz_WPBakery_get_page_content {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );

		$pages = get_posts( 'post_type="page"&numberposts=-1' );
		$list = array( esc_html__( 'Select Page', 'codevz' ) => 0 );
		if ( $pages ) {
			foreach ( $pages as $page ) {
				$list[ $page->post_title ] = $page->ID;
			}
		} else {
			$list[ esc_html__( 'No contact forms found', 'codevz' ) ] = 0;
		}

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Page Content', 'codevz' ),
			'description'	=> esc_html__( 'Show other page content as template', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Select Page', 'codevz' ),
					'description' 	=> esc_html__( 'Show another page content as a section in this page.', 'codevz' ),
					'param_name' 	=> 'id',
					'value' 		=> $list,
					'edit_field_class' => 'vc_col-xs-99',
					'admin_label' 	=> true,
					'save_always' 	=> true
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

		if ( isset( $atts['id'] ) ) {
			$id = $atts['id'];
		} else if ( isset( $atts['title'] ) ) {
			$id = $atts['title'];
		}

		// Output
		$out = Codevz_Plus::get_page_as_element( $id );
		$out = '<div class="codevz-page-content-element">' . $out . '</div>';

		return Codevz_Plus::_out( $atts, $out, 'css' );
	}

}