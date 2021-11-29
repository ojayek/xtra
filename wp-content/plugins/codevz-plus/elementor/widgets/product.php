<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_product extends Widget_Base {

	protected $id = 'cz_product';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Product', 'codevz' );
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

		$options 	= [];
		$options[] 	= esc_html__( '~ Select ~', 'codevz' );

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
			'product_id',
			[
				'label' 	=> esc_html__( 'Select Product', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> $this->get_products()
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
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce .xtra-single-product' ),
			]
		);

		$this->add_responsive_control(
			'sk_title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product .product_title' ),
			]
		);

		$this->add_responsive_control(
			'sk_image',
			[
				'label' 	=> esc_html__( 'Image', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product div.images img' ),
			]
		);

		$this->add_responsive_control(
			'sk_zoom',
			[
				'label' 	=> esc_html__( 'Zoom Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product div.images .woocommerce-product-gallery__trigger' ),
			]
		);

		$this->add_responsive_control(
			'sk_rate',
			[
				'label' 	=> esc_html__( 'Rating Stars', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce .woocommerce-product-rating .star-rating' ),
			]
		);

		$this->add_responsive_control(
			'sk_price',
			[
				'label' 	=> esc_html__( 'Price', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product .summary p.price, .woocommerce div.product .summary span.price' ),
			]
		);

		$this->add_responsive_control(
			'sk_sale_price',
			[
				'label' 	=> esc_html__( 'Sale Price', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product .summary p.price del span, .woocommerce div.product .summary span.price del span' ),
			]
		);

		$this->add_responsive_control(
			'sk_onsale',
			[
				'label' 	=> esc_html__( 'Sale Badge', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce span.onsale, .woocommerce ul.product li.product .onsale,.woocommerce.single span.onsale, .woocommerce.single ul.product li.product .onsale' ),
			]
		);

		$this->add_responsive_control(
			'sk_metatext',
			[
				'label' 	=> esc_html__( 'Meta Text', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product .posted_in' ),
			]
		);

		$this->add_responsive_control(
			'sk_metalink',
			[
				'label' 	=> esc_html__( 'Meta Link', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product .posted_in a:hover' ),
			]
		);

		$this->add_responsive_control(
			'sk_qtydown',
			[
				'label' 	=> esc_html__( 'Qty Down', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.quantity-down' ),
			]
		);

		$this->add_responsive_control(
			'sk_qtyup',
			[
				'label' 	=> esc_html__( 'Qty Up', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.quantity-up' ),
			]
		);

		$this->add_responsive_control(
			'sk_qty',
			[
				'label' 	=> esc_html__( 'Qty Input', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce .quantity .qty' ),
			]
		);

		$this->add_responsive_control(
			'sk_add_to_cart',
			[
				'label' 	=> esc_html__( 'Add to cart', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product form.cart .button' ),
			]
		);

		$this->add_responsive_control(
			'sk_wishlist',
			[
				'label' 	=> esc_html__( 'Wishlist', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce .cart .xtra-product-icons' ),
			]
		);

		$this->add_responsive_control(
			'sk_tabs',
			[
				'label' 	=> esc_html__( 'Tabs', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product .woocommerce-tabs ul.tabs li' ),
			]
		);

		$this->add_responsive_control(
			'sk_active_tab',
			[
				'label' 	=> esc_html__( 'Active Tab', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product .woocommerce-tabs ul.tabs li.active' ),
			]
		);

		$this->add_responsive_control(
			'sk_tab_content',
			[
				'label' 	=> esc_html__( 'Tab Content', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce div.product .woocommerce-tabs .panel' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		Xtra_Elementor::parallax( $settings );

		echo do_shortcode( '[product_page id="' . esc_attr( $settings[ 'product_id' ] ) . '"]' );

		Xtra_Elementor::parallax( $settings, true );

	}

}