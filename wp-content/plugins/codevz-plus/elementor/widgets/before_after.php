<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_before_after extends Widget_Base {
	
	protected $id = 'cz_before_after';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Before After', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-before-after';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Before', 'codevz' ),
			esc_html__( 'After', 'codevz' ),
			esc_html__( 'Image', 'codevz' ),
			esc_html__( 'Comparision', 'codevz' ),

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
			'section_ba',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
			]
		);

		$this->add_control(
			'image_2',
			[
				'label' => esc_html__( 'Before Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Codevz_Plus::$url . 'assets/img/p.svg',
				],
			]
		);

		$this->add_control(
			'title_2',
			[
				'label' => esc_html__('Before Title','codevz'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Before','codevz'),
				'placeholder' => esc_html__('Before','codevz'),
			]
		);

		$this->add_control(
			'image_1',
			[
				'label' => esc_html__( 'After Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Codevz_Plus::$url . 'assets/img/p.svg',
				],
			]
		);

		$this->add_control(
			'title_1',
			[
				'label' => esc_html__('After title','codevz'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('After','codevz'),
				'placeholder' => esc_html__('After','codevz'),
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'full'
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );
		
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_overall',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_image_comparison_slider' ),
			]
		);

		$this->add_responsive_control(
			'sk_handle',
			[
				'label' 	=> esc_html__( 'Handle', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_image_comparison_slider .cz_handle' ),
			]
		);

		$this->add_responsive_control(
			'sk_title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_image_comparison_slider .cz_image_label' ),
			]
		);

		$this->add_responsive_control(
			'svg_bg',
			[
				'label' 	=> esc_html__( 'Background layer', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_svg_bg:before' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		if ( empty( $settings['image_2']['url'] ) ) {
			return;
		}

		if ( empty( $settings['image_1']['url'] ) ) {
			return;
		}
		// Images
		$img1 = $settings['image_1'];
		$img2 = $settings['image_2'];

		// Classes
		$classes = array();
		$classes[] = 'cz_image_comparison_slider';
		$classes[] = $settings['svg_bg'] ? 'cz_svg_bg' : '';

		Xtra_Elementor::parallax( $settings );
		?>
		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>>
			<figure class="cz_image_container is_visible">
				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image_1' ); ?>
				<span class="cz_image_label" data-type="original"><?php echo $settings['title_1']; ?></span>
				<div class="cz_resize_img"><?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image_2' ); ?>
					<span class="cz_image_label" data-type="modified"><?php echo $settings['title_2']; ?></span>
				</div>
				<span class="cz_handle"></span>
			</figure>
		</div>
		<?php

		Xtra_Elementor::parallax( $settings, true );
	}

	public function content_template() {
		?>
		<#
		if ( settings.image_2.url ) {
			var image_2 = {
				id: settings.image_2.id,
				url: settings.image_2.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url_2 = elementor.imagesManager.getImageUrl( image_2 );

			if ( ! image_url_2 ) {
				return;
			}
		}

		if ( settings.image_1.url ) {
			var image_1 = {
				id: settings.image_1.id,
				url: settings.image_1.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url_1 = elementor.imagesManager.getImageUrl( image_1 );

			if ( ! image_url_1 ) {
				return;
			}
		}

		var parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true ),

			svg_bg = settings.svg_bg ? 'cz_svg_bg' : '';
		#>

		{{{ parallaxOpen }}}
		<div class="cz_image_comparison_slider {{{ svg_bg }}}">
		<figure class="cz_image_container is_visible"> <img src="{{ image_url_1 }}"/> <span class="cz_image_label" data-type="original">{{{settings.title_1}}}</span>
		<div class="cz_resize_img"> <img src="{{ image_url_2 }}"/><span class="cz_image_label" data-type="modified">{{{settings.title_2}}}</span>
		</div>	<span class="cz_handle"></span>
		</figure>
		</div>

		{{{ parallaxClose }}}
		<?php
	}
}