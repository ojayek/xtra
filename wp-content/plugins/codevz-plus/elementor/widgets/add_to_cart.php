<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_add_to_cart extends Widget_Base {

	protected $id = 'cz_add_to_cart';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Add to cart', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-product';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Grid', 'codevz' ),
			esc_html__( 'Product', 'codevz' ),
			esc_html__( 'Woocommerce', 'codevz' ),
			esc_html__( 'Shop', 'codevz' ),
			esc_html__( 'Store', 'codevz' ),

		];

	}

	public function get_products() {

		$args = [
			'post_type' 		=> 'product',
			'posts_per_page' 	=> -1,
		];

		$options[] = esc_html__( '~ Select ~', 'codevz' );

		$products = get_posts( $args );

		foreach( $products as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		return $options;
	}

	public function register_controls() {

		$this->start_controls_section(
			'settings',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'add_to_cart',
			[
				'label' 	=> esc_html__( 'Select Product', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> $this->get_products()
			]
		);

		$this->add_control(
			'show_price',
			[
				'label' 	=> esc_html__( 'Price', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'el_class',
			[
				'label' 	=> esc_html__( 'Class', 'codevz' ),
				'type' 		=> Controls_Manager::TEXT
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
			'sk_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.add_to_cart_inline' ),
			]
		);

		$this->add_responsive_control(
			'sk_button',
			[
				'label' 	=> esc_html__( 'Button', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( 'a.button' ),
			]
		);
		
		$this->add_responsive_control(
			'sk_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.add_to_cart_button:before, [class*="product_type_"].button:before' ),
			]
		);

		$this->add_responsive_control(
			'sk_price',
			[
				'label' 	=> esc_html__( 'Price', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.amount' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		Xtra_Elementor::parallax( $settings );

		$show_price = $settings['show_price'] == 'yes' ? 1 : 0;

		echo do_shortcode( '[add_to_cart id="' . esc_attr( $settings[ 'add_to_cart' ] ) . '" show_price="' . esc_attr( $show_price ) . '" quantity="1" class="' . esc_attr( $settings[ 'el_class' ] ) . '" style=""]' );

		Xtra_Elementor::parallax( $settings, true );

	}

}