<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_product_category extends Widget_Base {

	protected $id = 'cz_product_category';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Product Category', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-product-category';
	}


	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Grid', 'codevz' ),
			esc_html__( 'category', 'codevz' ),
			esc_html__( 'categories', 'codevz' ),
			esc_html__( 'Product', 'codevz' ),
			esc_html__( 'Woocommerce', 'codevz' ),
			esc_html__( 'Shop', 'codevz' ),
			esc_html__( 'Store', 'codevz' ),

		];

	}

	public function get_product_category() {

		$args = [
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 0
		];

		$options = [];

		$options[] = esc_html__( '~ Select ~', 'codevz' );

		$all_categories = get_categories( $args );

		foreach( $all_categories as $cat ) {
			if ( $cat->category_parent == 0 ) {
				$options[ $cat->term_id ] = $cat->name;
			}
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
			'product_category',
			[
				'label' 	=> esc_html__( 'Select Product Category', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> $this->get_product_category()
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
			'hide_empty',
			[
				'label' 	=> esc_html__( 'Hide Empty', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
				
			]
		);

		$this->add_control(
			'parent',
			[
				'label' 	=> esc_html__( 'Parent', 'codevz' ),
				'type' 		=> Controls_Manager::SWITCHER,
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
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce ul.products li.product' ),
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
			'sk_image',
			[
				'label' 	=> esc_html__( 'Image', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce ul.products li.product a img' ),
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
			'sk_price',
			[
				'label' 	=> esc_html__( 'Price', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'margin' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.woocommerce ul.products li.product .price', '.woocommerce ul.products li.product:hover .price' ),
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

		$hide_empty = $settings['hide_empty'] == 'yes' ? 1 : 0;

		echo do_shortcode( '[product_category category="' . esc_attr( $settings[ 'product_category' ] ) . '" columns="' . esc_attr( $settings['columns'] ) . '" limit="' . esc_attr( $settings['limit'] ) . '" hide_empty="' . esc_attr( $hide_empty ) . '" parent="' . esc_attr( $settings['parent'] ) . '" orderby="' . esc_attr( $settings['orderby'] ) . '"]' );

		Xtra_Elementor::parallax( $settings, true );

	}

}