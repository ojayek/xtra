<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_image_hover_zoom extends Widget_Base {

	protected $id = 'cz_image_hover_zoom';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Image Hover Zoom', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-image-hover-zoom';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Images', 'codevz' ),
			esc_html__( 'Img', 'codevz' ),
			esc_html__( 'Hover', 'codevz' ),
			esc_html__( 'Zoom', 'codevz' ),
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
			'section_ihz',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Codevz_Plus::$url . 'assets/img/p.svg',
				],
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

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section(
			'section_styling',
			[
				'label' => esc_html__( 'Styling', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_css',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_image_hover_zoom' ),
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

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Image
		$img = Group_Control_Image_Size::get_attachment_image_html( $settings );
		$link = isset( $settings[ 'image' ][ 'url' ] ) ? $settings[ 'image' ][ 'url' ] : '';

		// Classes
		$classes = array();
		$classes[] = 'cz_image_hover_zoom';
		$classes[] = $settings['svg_bg'] ? 'cz_svg_bg' : '';

		Xtra_Elementor::parallax( $settings );

		?>
		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>> 
			<div><a href="<?php echo esc_url( $link ); ?>"><?php echo wp_kses_post( $img );?></a>
			</div>
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

		var classes = 'cz_image_hover_zoom', 
			classes = settings.svg_bg ? classes + ' cz_svg_bg' : classes,

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );
		#>
		
		{{{ parallaxOpen }}}
		
		<div class="{{{classes}}}"> 
			<div> <a href="{{{image_url}}}"><img src="{{{image_url}}}"></a>
			</div>
		</div>

		{{{ parallaxClose }}}
		<?php
	}
}