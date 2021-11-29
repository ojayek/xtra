<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_products extends Widget_Base {

	protected $id = 'cz_products';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Products', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-products';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Grid', 'codevz' ),
			esc_html__( 'Products', 'codevz' ),
			esc_html__( 'Woocommerce', 'codevz' ),
			esc_html__( 'Shop', 'codevz' ),
			esc_html__( 'Store', 'codevz' ),

		];

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
			'type',
			[
				'label' 	=> esc_html__( 'Products type', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'products',
				'options' 	=> [
					'products'  			=> esc_html__( 'Products', 'codevz' ),
					'featured_products' 	=> esc_html__( 'Featured Products', 'codevz' ),
					'sale_products' 		=> esc_html__( 'Sale Products', 'codevz' ),
					'best_selling_products' => esc_html__( 'Best Selling Products', 'codevz' ),
					'recent_products' 		=> esc_html__( 'Recent Products', 'codevz' ),
					'product_attribute' 	=> esc_html__( 'Product Attribute', 'codevz' ),
					'top_rated_products' 	=> esc_html__( 'Top Rated Products', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'limit',
			[
				'label' 	=> esc_html__( 'Limit', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> 6
			]
		);

		$this->add_control(
			'columns',
			[
				'label' 	=> esc_html__( 'Columns', 'codevz' ),
				'type' 		=> Controls_Manager::NUMBER,
				'default' 	=> 4
			]
		);
		
		$this->add_control(
			'paginate',
			[
				'label' => esc_html__( 'Paginate', 'codevz' ),
				'type' 	=> Controls_Manager::SWITCHER
				 
			]
		);
		
		$this->add_control(
			'orderby',
			[
				'label' 	=> esc_html__( 'Orderby', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'date',
				'options' 	=> [
					'date' 		=> esc_html__( 'Date', 'codevz' ),
					'ID' 		=> esc_html__( 'ID', 'codevz' ),
					'rand' 		=> esc_html__( 'Menu_order', 'codevz' ),
					'author' 	=> esc_html__( 'Popularity', 'codevz' ),
					'title' 	=> esc_html__( 'Rand', 'codevz' ),
					'name' 		=> esc_html__( 'Rating', 'codevz' ),
					'type' 		=> esc_html__( 'Title', 'codevz' ),

				],
			]
		);
			
		$this->add_control(
			'on_sale',
			[
				'label' 	=> esc_html__( 'On sale', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'best_selling',
				'options' 	=> [
					'best_selling' 	=> esc_html__( 'Best Selling', 'codevz' ),
					'top_rated' 	=> esc_html__( 'Top Rated', 'codevz' ),
				],
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
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce' ),
			]
		);

		$this->add_responsive_control(
			'sk_products',
			[
				'label' 	=> esc_html__( 'Products', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.products li.product' ),
			]
		);

		$this->add_responsive_control(
			'sk_image',
			[
				'label' 	=> esc_html__( 'Image', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.products img', '.woocommerce ul.products li.product:hover a img' ),
			]
		);

		$this->add_responsive_control(
			'sk_title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce ul.products li.product .woocommerce-loop-category__title, .woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3,.woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-category__title, .woocommerce.woo-template-2 ul.products li.product .woocommerce-loop-product__title, .woocommerce.woo-template-2 ul.products li.product h3', '.woocommerce ul.products li.product:hover .woocommerce-loop-category__title, .woocommerce ul.products li.product:hover .woocommerce-loop-product__title, .woocommerce ul.products li.product:hover h3,.woocommerce.woo-template-2 ul.products li.product:hover .woocommerce-loop-category__title, .woocommerce.woo-template-2 ul.products li.product:hover .woocommerce-loop-product__title, .woocommerce.woo-template-2 ul.products li.product:hover h3' ),
			]
		);

		$this->add_responsive_control(
			'sk_rate',
			[
				'label' 	=> esc_html__( 'Rating Stars', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce ul.products li.product .star-rating' ),
			]
		);

		$this->add_responsive_control(
			'sk_onsale',
			[
				'label' 	=> esc_html__( 'Sale Badge', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce span.onsale, .woocommerce ul.products li.product .onsale,.woocommerce.single span.onsale, .woocommerce.single ul.products li.product .onsale' ),
			]
		);

		$this->add_responsive_control(
			'sk_price',
			[
				'label' 	=> esc_html__( 'Price', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce ul.products li.product .price', '.woocommerce ul.products li.product:hover .price' ),
			]
		);

		$this->add_responsive_control(
			'sk_sale_price',
			[
				'label' 	=> esc_html__( 'Sale Price', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce ul.products li.product .price del span', '.woocommerce ul.products li.product:hover .price del span' ),
			]
		);

		$this->add_responsive_control(
			'sk_add_to_cart',
			[
				'label' 	=> esc_html__( 'Add to cart', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( 'ul.products li.product .button.add_to_cart_button, ul.products li.product .button[class*="product_type_"]', '.woocommerce ul.products li.product .button.add_to_cart_button:hover, .woocommerce ul.products li.product .button[class*="product_type_"]:hover' ),
			]
		);

		$this->add_responsive_control(
			'sk_view_cart',
			[
				'label' 	=> esc_html__( 'View cart', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce a.added_to_cart' ),
			]
		);

		$this->add_responsive_control(
			'sk_icons',
			[
				'label' 	=> esc_html__( 'Icons', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.products .product .xtra-product-icons' ),
			]
		);

		$this->add_responsive_control(
			'sk_quick_view',
			[
				'label' 	=> esc_html__( 'Quick view', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.products .product .xtra-product-quick-view' ),
			]
		);
		
		$this->add_responsive_control(
			'sk_wishlist',
			[
				'label' 	=> esc_html__( 'Wishlist', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.products .product .xtra-add-to-wishlist' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		Xtra_Elementor::parallax( $settings );

		$paginate = $settings['paginate'] == 'yes' ? 1 : 0;

		echo do_shortcode( '[' . esc_attr( $settings['type'] ) . ' columns="' . esc_attr( $settings['columns'] ) . '" limit="' . esc_attr( $settings['limit'] ) . '" paginate="' . esc_attr( $paginate ) . '" orderby="' . esc_attr( $settings['orderby'] ) . '" on_sale="' . esc_attr( $settings['on_sale'] ) . '"]' );

		Xtra_Elementor::parallax( $settings, true );

	}

}