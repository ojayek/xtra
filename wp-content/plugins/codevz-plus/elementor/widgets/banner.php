<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_banner extends Widget_Base { 

	protected $id = 'cz_banner';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Banner', 'codevz' );;
	}

	public function get_icon() {
		return 'xtra-banner';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Banner', 'codevz' ),
			esc_html__( 'Image', 'codevz' ),
			esc_html__( 'Text', 'codevz' ),
			esc_html__( 'Advertisement', 'codevz' )
		];

	}

	public function get_style_depends() {

		$array = [ $this->id, 'codevz-tilt', 'cz_parallax' ];

		if ( Codevz_Plus::$is_rtl ) {
			$array[] = $this->id . '_rtl';
		}

		return $array;

	}

	public function get_script_depends() {
		return [ $this->id, 'cz_parallax', 'codevz-tilt' ];
	}

	public function register_controls() {

		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => esc_html__( 'Style', 'codevz' ) . ' 1',
					'style2' => esc_html__( 'Style', 'codevz' ) . ' 2',
					'style3' => esc_html__( 'Style', 'codevz' ) . ' 3',
					'style4' => esc_html__( 'Style', 'codevz' ) . ' 4',
					'style5' => esc_html__( 'Style', 'codevz' ) . ' 5',
					'style6' => esc_html__( 'Style', 'codevz' ) . ' 6',
					'style7' => esc_html__( 'Style', 'codevz' ) . ' 7',
					'style8' => esc_html__( 'Style', 'codevz' ) . ' 8',
					'style9' => esc_html__( 'Style', 'codevz' ) . ' 9',
					'style10' => esc_html__( 'Style', 'codevz' ) . ' 10',
					'style11' => esc_html__( 'Style', 'codevz' ) . ' 11',
					'style12' => esc_html__( 'Style', 'codevz' ) . ' 12',
					'style13' => esc_html__( 'Style', 'codevz' ) . ' 13',
					'style14' => esc_html__( 'Style', 'codevz' ) . ' 14',
					'style15' => esc_html__( 'Style', 'codevz' ) . ' 15',
					'style16' => esc_html__( 'Style', 'codevz' ) . ' 16',
					'style17' => esc_html__( 'Style', 'codevz' ) . ' 17',
					'style18' => esc_html__( 'Style', 'codevz' ) . ' 18',
					'style19' => esc_html__( 'Style', 'codevz' ) . ' 19',
					'style20' => esc_html__( 'Style', 'codevz' ) . ' 20',
					'style21' => esc_html__( 'Style', 'codevz' ) . ' 21',
					'style22' => esc_html__( 'Style', 'codevz' ) . ' 22',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' 	=> esc_html__('Title','codevz'),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> esc_html__( 'Banner title','codevz' ),
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__('Caption','codevz'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Banner description','codevz' ),
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

		$this->add_control(
			'text_center',
			[
				'label' => esc_html__( 'Center on mobile?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'No', 'codevz' ),
					'1' => esc_html__( 'Yes', 'codevz' ),
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'codevz' )
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
				'default' => 'large',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => esc_html__( 'Image opacity', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''      => esc_html__( '~ Select ~', 'codevz' ),
					'0'     => '0',
					'0.1'   => '0.1',
					'0.2'   => '0.2',
					'0.3'   => '0.3',
					'0.4'   => '0.4',
					'0.5'   => '0.5',
					'0.6'   => '0.6',
					'0.7'   => '0.7',
					'0.8'   => '0.8',
					'0.9'   => '0.9',
					'1'     => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .cz_banner img' => 'opacity: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_hover_opacity',
			[
				'label' => esc_html__( 'Image hover opacity', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''      => esc_html__( '~ Select ~', 'codevz' ),
					'0'     => '0',
					'0.1'   => '0.1',
					'0.2'   => '0.2',
					'0.3'   => '0.3',
					'0.4'   => '0.4',
					'0.5'   => '0.5',
					'0.6'   => '0.6',
					'0.7'   => '0.7',
					'0.8'   => '0.8',
					'0.9'   => '0.9',
					'1'     => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .cz_banner:hover img' => 'opacity: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section(
			'section_tilt',
			[
				'label' => esc_html__( 'Tilt effect on hover', 'codevz' )
			]
		);

		$this->add_control(
			'tilt',
			[
				'label' => esc_html__( 'Tilt effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' 		=> esc_html__( 'Off', 'codevz' ),
					'on' 	=> esc_html__( 'On', 'codevz' ),
				],
			]
		);

		$this->add_control(
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

		$this->add_control(
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
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_box',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_banner figure' ),
			]
		);

		$this->add_responsive_control(
			'sk_title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_banner h4' ),
			]
		);

		$this->add_responsive_control(
			'sk_caption',
			[
				'label' 	=> esc_html__( 'Content', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_banner figcaption' ),
			]
		);

		$this->add_responsive_control(
			'svg_bg',
			[
				'label' 	=> esc_html__( 'Background layer', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'top', 'left', 'border', 'width', 'height' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_banner.cz_svg_bg:before' ),
			]
		);
		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$this->add_link_attributes( 'link', $settings['link'] );

		$image =  $settings['image'];

		$content = $settings['content'] ? '<p class="cz_wpe_content">' . do_shortcode( Codevz_Plus::fix_extra_p( $settings['content'] ) ) . '</p>' : '';

		// Classes
		$classes = [];
		$classes[] = 'cz_banner clr';
		$classes[] = $settings['svg_bg'] ? 'cz_svg_bg' : '';
		$classes[] = $settings['text_center'] ? 'cz_mobile_text_center' : '';

		// Parallax.
		Xtra_Elementor::parallax( $settings );

		?>
		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>>
			<figure class="effect-<?php echo esc_attr( $settings['style'] ) . Codevz_Plus::tilt( $settings ); ?>">
				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
				<figcaption>
					<div>
						<h4><?php echo wp_kses_post( $settings['title'] ); ?></h4>
						<?php echo wp_kses_post( $content ); ?>
					</div> 
					<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'link' ) ); ?>></a>
				</figcaption>
			</figure>
		</div>
		<?php

		// Close parallax.
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

		var classes = 'cz_banner clr', 
			classes = classes + ( settings.svg_bg ? ' cz_svg_bg' : '' ),
			classes = classes + ( settings.text_center ? ' cz_mobile_text_center' : '' ),

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true ),

			tilt = xtraElementorTilt( settings );

		#>

		{{{ parallaxOpen }}}

		<div class="{{{ classes }}}">
			<figure class="effect-{{{ settings.style }}}"{{{ tilt }}}>
				<img src="{{ image_url }}"/>
				<figcaption>
					<div>
						<h4>{{{ settings.title }}}</h4>
						<p class="cz_wpe_content">{{{ settings.content }}}</p>
					</div>
					<a href="{{{ settings.link.url }}}"> </a>
				</figcaption>
			</figure>
		</div>

		{{{ parallaxClose }}}
		<?php
	}
}