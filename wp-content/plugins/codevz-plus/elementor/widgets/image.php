<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_image extends Widget_Base { 

	protected $id = 'cz_image';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Image', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-image';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [
			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Image', 'codevz' ),
			esc_html__( 'Photo', 'codevz' ),
			esc_html__( 'Shot', 'codevz' )
		];

	}

	public function get_style_depends() {
		return [ $this->id, 'cz_parallax', 'codevz-tilt' ];
	}

	public function get_script_depends() {
		return [ $this->id, 'cz_parallax', 'codevz-tilt' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
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

		$this->add_control(
			'fx_hover',
			[
				'label' => esc_html__( 'Hover effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_image_no_fx',
				'options' => [
					'cz_image_no_fx' => esc_html__( 'Select', 'codevz' ),
					'cz_image_simple_fade' => esc_html__( 'Simple Fade', 'codevz' ),
					'cz_image_flip_h' => esc_html__( 'Flip Horizontal', 'codevz' ),
					'cz_image_flip_v' => esc_html__( 'Flip Vertical', 'codevz' ),
					'cz_image_fade_to_top' => esc_html__( 'Fade To Top', 'codevz' ),
					'cz_image_fade_to_bottom' => esc_html__( 'Fade To Bottom', 'codevz' ),
					'cz_image_fade_to_left' => esc_html__( 'Fade To Left', 'codevz' ),
					'cz_image_fade_to_right' => esc_html__( 'Fade To Right', 'codevz' ),
					'cz_image_zoom_in' => esc_html__( 'Zoom In', 'codevz' ),
					'cz_image_zoom_out' => esc_html__( 'Zoom Out', 'codevz' ),
					'cz_image_blurred' => esc_html__( 'Blurred', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'hover_image',
			[
				'label' => esc_html__( 'Hover Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Codevz_Plus::$url . 'assets/img/p.svg',
				],
				'condition' => [
					'fx_hover' => [
						'cz_image_simple_fade',
						'cz_image_flip_h',
						'cz_image_flip_v',
						'cz_image_fade_to_top',
						'cz_image_fade_to_bottom',
						'cz_image_fade_to_left',
						'cz_image_fade_to_right',
						'cz_image_zoom_in',
						'cz_image_zoom_out',
						'cz_image_blurred',
					],
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

		$this->add_control(
			'css_position',
			[
				'label' => esc_html__( 'Image Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'relative',
				'options' => [
					'relative' 									=> esc_html__( 'Inline', 'codevz' ),
					'relative;display: block;text-align:center' => esc_html__( 'Block', 'codevz' ),
					'relative;float:left' 						=> esc_html__( 'Left', 'codevz' ),
					'relative;display: table;margin:0 auto' 	=> esc_html__( 'Center', 'codevz' ),
					'relative;float:right' 						=> esc_html__( 'Right', 'codevz' ),
					'relative;float:left;margin:0 auto' 		=> esc_html__( 'Left (center_in_mobile)', 'codevz' ),
					'relative;float:right;margin:0 auto' 		=> esc_html__( 'Right (center_in_mobile)', 'codevz' ),
				],
				'selectors' => [
					'{{WRAPPER}} .cz_image > div' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_type',
			[
				'label' => esc_html__( 'Link Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'codevz' ),
					'lightbox' => __( 'Link to large image (Lightbox)', 'codevz' ),
					'custom' => __( 'Custom', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'codevz' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://xtratheme.com',
				'condition' => [
					'link_type' => 'custom',
				]
			]
		);

		$this->add_control(
			'tooltip',
			[
				'label' => esc_html__('Tooltip','codevz'),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_caption',
			[
				'label' => esc_html__( 'Caption', 'codevz' )
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__('Caption','codevz'),
				'type' => Controls_Manager::WYSIWYG
			]
		);

		$this->add_control(
			'sticky_caption',
			[
				'label' => esc_html__( 'Sticky on mouse hover?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'smart_fs',
			[
				'label' => esc_html__( 'Mobile smart font size?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section(
			'cz_title',
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
					'' => esc_html__( 'Off', 'codevz' ),
					'on' => esc_html__( 'On', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'glare',
			[
				'label' => esc_html__( 'Glare', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'0' 	=> '0',
					'0.2' 	=> '0.2',
					'0.4' 	=> '0.4',
					'0.6' 	=> '0.6',
					'0.8' 	=> '0.8',
					'1' 	=> '1',
				],
				'condition' => [
					'tilt' => 'on'
				],
			]
		);

		$this->add_control(
			'scale',
			[
				'label' => esc_html__( 'Scale', 'codevz' ),
				'type' => Controls_Manager::SELECT,
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
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'css_width',
			[
				'label' => esc_html__( 'Custom Width', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 50,
						'max' => 500,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_image > div' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sk_image',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_image' ),
			]
		);

		$this->add_responsive_control(
			'sk_image_in',
			[
				'label' 	=> esc_html__( 'Image', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_image_in', '.cz_image:hover .cz_image_in' ),
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
			'sk_caption',
			[
				'label' 	=> esc_html__( 'Caption', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'color', 'border', 'position', 'top', 'left' ],
				'selectors' => Xtra_Elementor::sk_selectors( 
					'.cz_image_caption', 
					'.cz_image:hover .cz_image_caption'
				),
			]
		);

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Link.
		$a_before = $a_after = '';

		if ( $settings['link_type'] === 'lightbox' && ! empty( $settings[ 'image' ][ 'url' ] ) ) {

			$a_before = '<a href="' . $settings[ 'image' ][ 'url' ] . '">';
			$a_after = '</a>';

		} else if ( $settings['link_type'] === 'custom' ) {

			$this->add_link_attributes( 'link', $settings['link'] );

			$a_before = '<a '. $this->get_render_attribute_string( 'link' ) . '>';
			$a_after = '</a>';

		}

		// Tooltip.
		$tooltip = $settings['tooltip'] ? ' data-title="' . $settings['tooltip'] . '"' : '';

		// Get images.
		$image = Group_Control_Image_Size::get_attachment_image_html( $settings );

		// Hover effect.
		if ( ! empty( $settings['hover_image']['id'] ) ) {
			
			$settings['image'] = $settings['hover_image'];
			$hover_image = Group_Control_Image_Size::get_attachment_image_html( $settings );

		} else {

			$hover_image = $image;

		}

		// Hover.
		$hover_image_tag = '<div class="cz_hover_image">' . $hover_image .'</div>';

		// Caption.
		$caption = $settings['content'] ? '<div class="cz_image_caption mt10">' . $settings['content'] . '</div>' : '';

		// Background layer.
		$svg_bg = $settings['svg_bg'] ? ' class="cz_svg_bg"' : '';

		// Widget classes.
		$classes = array();
		$classes[] = 'cz_image clr';
		$classes[] = $settings['fx_hover'];
		$classes[] = $settings['smart_fs'] ? 'cz_smart_fs' : '';
		$classes[] = $settings['tooltip'] ? 'cz_tooltip_up' : '';
		$classes[] = $settings['sticky_caption'] ? 'cz_image_caption_sticky' : '';
		$classes[] = ( $settings['css_position'] === 'relative' ) ? 'center_on_mobile' : '';
		$classes[] = Codevz_Plus::contains( $settings['css_position'], 'margin' ) ? 'center_on_mobile' : '';

		// Fullwidth image.
		if ( ! empty( $settings[ 'css_width' ][ 'size' ] ) ) {

			if ( $settings[ 'css_width' ][ 'size' ] == '100' && $settings[ 'css_width' ][ 'unit' ] == '%' ) {

				$classes[] = 'xtra-image-full-width';

			}

		}
		if ( ! empty( $settings[ 'css_width_tablet' ][ 'size' ] ) ) {

			if ( $settings[ 'css_width_tablet' ][ 'size' ] == '100' && $settings[ 'css_width_tablet' ][ 'unit' ] == '%' ) {

				$classes[] = 'xtra-image-full-width-tablet';

			}

		}
		if ( ! empty( $settings[ 'css_width_mobile' ][ 'size' ] ) ) {

			if ( $settings[ 'css_width_mobile' ][ 'size' ] == '100' && $settings[ 'css_width_mobile' ][ 'unit' ] == '%' ) {

				$classes[] = 'xtra-image-full-width-mobile';

			}

		}

		// Parallax.
		Xtra_Elementor::parallax( $settings );

		// Link before.
		echo wp_kses_post( $a_before );

		?>

		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>>

			<div<?php echo wp_kses_post( $svg_bg . $tooltip ); ?>>

				<div class="cz_image_in"<?php echo Codevz_Plus::tilt( $settings ); ?>>

					<div class="cz_main_image"><?php echo wp_kses_post( $image ); ?></div>

					<?php echo wp_kses_post( $hover_image_tag ); ?>

				</div>

				<?php echo wp_kses_post( $caption ); ?>

			</div>

		</div>

		<?php

		// Close link.
		echo wp_kses_post( $a_after );

		// Close parallax.
		Xtra_Elementor::parallax( $settings, true );

	}

	protected function content_template(){
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

		if ( settings.hover_image.url ) {
			var hover_image = {
				id: settings.hover_image.id,
				url: settings.hover_image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var hover_image_url = elementor.imagesManager.getImageUrl( hover_image );

			if ( ! hover_image_url ) {
				return;
			}
		}

	// Images.
	var image = '<img src="' + image_url + '" />',
		image_url = settings.hover_image.id ? hover_image_url : image_url,
		hover_image = '<img src="' + image_url + '" />',
		a_before = a_after = '';

	// Link.
	if ( settings.link_type === 'lightbox' ) {
		a_before = '<a href="{{ settings.link.url }}" data-xtra-lightbox>';
		a_after = '</a>';
	} else if ( settings.link_type === 'custom' ) {
		a_before = '<a href="{{ settings.link.url }}">';
		a_after = '</a>';
	}

	// Widget classes.
	var classes = 'cz_image clr', 
		classes = classes + ( settings.fx_hover ? ' ' + settings.fx_hover : '' ),
		classes = classes + ( settings.smart_fs ? ' cz_smart_fs' : '' ),
		classes = classes + ( settings.tooltip ? ' cz_tooltip_up' : '' ),
		classes = classes + ( settings.sticky_caption ? ' cz_image_caption_sticky' : '' ),
		classes = classes + ( settings.css_position === 'relative' ? 'center_on_mobile' : '' ),
		classes = classes + ( settings.css_position.indexOf( 'margin' ) >= 0 ? 'center_on_mobile' : '' ),

		caption = settings.content ? '<div class="cz_image_caption mt10">' + settings.content + '</div>' : '',

		tooltip = settings.tooltip ? ' data-title="' + settings.tooltip + '"' : '',
		hover_image_tag = hover_image ? '<div class="cz_hover_image">' + hover_image +'</div>' : '',

		parallaxOpen = xtraElementorParallax( settings ),
		parallaxClose = xtraElementorParallax( settings, true ),

		tilt = xtraElementorTilt( settings ),
		svg_bg = settings.svg_bg ? ' class="cz_svg_bg"' : '';

	#>

	{{{ parallaxOpen }}}

	{{{ a_before }}}

	<div class="{{{ classes }}}">

		<div{{{ svg_bg }}}{{{ tooltip }}}>

			<div class="cz_image_in" {{{ tilt }}}>

				<div class="cz_main_image">{{{ image }}}</div>

				{{{ hover_image_tag }}}

			</div>

			{{{ caption }}}

		</div>

	</div>

	{{{ a_after }}}

	{{{ parallaxClose }}}

	<?php

	}

}