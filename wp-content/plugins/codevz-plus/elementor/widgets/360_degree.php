<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_360_degree extends Widget_Base { 

	protected $id = 'cz_360_degree';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( '360 Degree', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-360-degree';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Images', 'codevz' ),
			esc_html__( '360 Degree', 'codevz' ),

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
			'360_degree',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Placeholder (loading image)', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://xtratheme.com/img/360.jpg',
				],
			]
		);

		$this->add_control(
			'stripe_image',
			[
				'label' => esc_html__( 'Stripe image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://xtratheme.com/img/360s.jpg',
				],
			]
		);

		$this->add_control(
			'count',
			[
				'label' 	=> esc_html__( 'Frames count', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'min' 		=> 1,
				'step' 		=> 1,
				'max' 		=> 40,
				'default'   => 8,
			]
		);

		$this->add_control(
			'action',
			[
				'label' => esc_html__( 'Rotate by', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'drag',
				'options' => [
					'drag' => esc_html__( 'Mouse Dragging', 'codevz' ),
					'hover' => esc_html__( 'Mouse Hover', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'handle',
			[
				'label' => esc_html__( 'Show handle', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
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
			'sk_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_product-viewer-wrapper .product-viewer' ),
			]
		);

		$this->add_responsive_control(
			'sk_handle',
			[
				'label' 	=> esc_html__( 'Handle', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_product-viewer-wrapper .handle' ),
			]
		);
		
		$this->add_responsive_control(
			'sk_bar',
			[
				'label' 	=> esc_html__( 'Bar', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_product-viewer-wrapper .cz_product-viewer-handle' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$imgsrc = '';

		// Images
		if ( ! empty( $settings[ 'stripe_image' ][ 'url' ] ) ) {
			$imgsrc = $settings[ 'stripe_image' ][ 'url' ];
		}

		$plc_imgsrc = Group_Control_Image_Size::get_attachment_image_html( $settings );

		// Count
		$count = $settings['count'] ? $settings['count'] : 16;

		// Classes
		$classes = [];
		$classes[] = 'cz_product-viewer-wrapper';

		?>
		<div data-frame="<?php echo esc_attr( $count ); ?>" data-friction="0.33" data-action="<?php echo esc_attr( $settings['action'] ); ?>"<?php echo Codevz_Plus::classes( [], $classes ); ?>>
			<div>
				<figure class="product-viewer">
					<?php echo wp_kses_post( $plc_imgsrc ); ?>
					<div class="product-sprite" data-image="<?php echo esc_url( $imgsrc ); ?>" style="width: <?php echo esc_attr( $count ) * 100; ?>%;background-image:url(<?php echo esc_url( $imgsrc ); ?>)"></div>
				</figure>
				<?php if ( $settings['handle'] == true ) { ?>	
						<div class="cz_product-viewer-handle"><span class="fill"></span><span class="handle"><i class="fa fa-arrows-h"></i></span></div>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	public function content_template___() {
		?>
		<#
		if ( settings.stripe_image.url ) {
			var stripe_image = {
				id: settings.stripe_image.id,
				url: settings.stripe_image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			},
			stripe_image_url = elementor.imagesManager.getImageUrl( stripe_image );

			if ( ! stripe_image_url ) {
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
			},
			image_url = elementor.imagesManager.getImageUrl( image );

			if ( ! image_url ) {
				return;
			}
		}

		var imgsrc = stripe_image_url,
			plc_imgsrc = image_url,
			count = settings.count ? settings.count : 16,
			classes = 'cz_product-viewer-wrapper';

		#>

		<div data-frame={{{count}}} data-friction="0.33" data-action={{{settings.action}}} class="{{{classes}}}">
			<div>
				<figure class="product-viewer">
					<img src={{{plc_imgsrc}}} alt="Loading">
					<div class="product-sprite" data-image={{{imgsrc}}} style="width:{{{count}}}%;background-image:url({{{imgsrc}}})"></div>
				</figure>
				<# if ( settings.handle == true ) { #>	
						<div class="cz_product-viewer-handle"><span class="fill"></span><span class="handle"><i class="fa fa-arrows-h"></i></span></div>
				<# } #>
			</div>
		</div>

		<?php
	}
}