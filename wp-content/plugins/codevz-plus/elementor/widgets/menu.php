<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_menu extends Widget_Base {

	protected $id = 'cz_menu';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Header - Menu', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-menu';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'codevz', 'codevz' ),
			esc_html__( 'Menu', 'codevz' ),
			esc_html__( 'Navigation', 'codevz' ),

		];

	}

	public function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' 	=> esc_html__( 'Settings', 'codevz' ),
				'tab' 		=> Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'menu_location',
			[
				'label' 		=> esc_html__( 'Menu', 'codevz' ),
				'type' 			=> Controls_Manager::SELECT,
				'options' 		=> [
					'' 			=> esc_html__( '~ Select ~', 'codevz' ), 
					'primary' 	=> esc_html__( 'Primary', 'codevz' ), 
					'secondary' => esc_html__( 'Secondary', 'codevz' ), 
					'one-page'  => esc_html__( 'One Page', 'codevz' ), 
					'footer'  	=> esc_html__( 'Footer', 'codevz' ),
					'mobile'  	=> esc_html__( 'Mobile', 'codevz' ),
					'custom-1' 	=> esc_html__( 'Custom 1', 'codevz' ), 
					'custom-2' 	=> esc_html__( 'Custom 2', 'codevz' ), 
					'custom-3' 	=> esc_html__( 'Custom 3', 'codevz' ),
					'custom-4' 	=> esc_html__( 'Custom 4', 'codevz' ),
					'custom-5' 	=> esc_html__( 'Custom 5', 'codevz' ),
					'custom-6' 	=> esc_html__( 'Custom 6', 'codevz' ),
					'custom-7' 	=> esc_html__( 'Custom 7', 'codevz' ),
					'custom-8' 	=> esc_html__( 'Custom 8', 'codevz' )
				],
			]
		);

		$this->add_control(
			'menu_type',
			[
				'label' 		=> esc_html__( 'Menu', 'codevz' ),
				'type' 			=> Controls_Manager::SELECT,
				'options' 		=> [
					'' 							   => esc_html__( '~ Default ~', 'codevz' ),
					'offcanvas_menu_left' 		   => esc_html__( 'Offcanvas left', 'codevz' ),
					'offcanvas_menu_right' 		   => esc_html__( 'Offcanvas right', 'codevz' ),
					'fullscreen_menu' 			   => esc_html__( 'Full screen', 'codevz' ),
					'dropdown_menu' 			   => esc_html__( 'Dropdown', 'codevz' ),
					'open_horizontal inview_left'  => esc_html__( 'Sliding menu left', 'codevz' ),
					'open_horizontal inview_right' => esc_html__( 'Sliding menu right', 'codevz' ),
					'left_side_dots side_dots' 	   => esc_html__( 'Vertical dots left', 'codevz' ),
					'right_side_dots side_dots'    => esc_html__( 'Vertical dots right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'menu_icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false
			]
		);

		$this->add_control(
			'menu_title',
			[
				'label' => esc_html__( 'Title', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'menu_disable_dots',
			[
				'label' => esc_html__( 'Disable Dots', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
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
			'sk_menu_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '> i' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '> span' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_li',
			[
				'label' 	=> esc_html__( 'Menus li', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu > .cz' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_a',
			[
				'label' 	=> esc_html__( 'Menus', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu > .cz > a', '.sf-menu > .cz > a:hover, .sf-menu > .cz:hover > a, .sf-menu > .cz.current_menu > a, .sf-menu > .current-menu-parent > a' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_shape',
			[
				'label' 	=> esc_html__( 'Shape', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu > .cz > a:before' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_subtitle',
			[
				'label' 	=> esc_html__( 'Subtitle', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu > .cz > a > .cz_menu_subtitle', '.sf-menu > .cz > a:hover > .cz_menu_subtitle, .sf-menu > .cz:hover > a > .cz_menu_subtitle, .sf-menu > .cz.current_menu > a > .cz_menu_subtitle, .sf-menu > .current-menu-parent > a > .cz_menu_subtitle' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_icons',
			[
				'label' 	=> esc_html__( 'Subtitle', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu > .cz > a span i', '.sf-menu > .cz > a:hover span i, .sf-menu > .cz:hover > a span i, .sf-menu > .cz.current_menu > a span i, .sf-menu > .current-menu-parent > a span i' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_indicator',
			[
				'label' 	=> esc_html__( 'Indicator', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu > .cz > a .cz_indicator' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_delimiter',
			[
				'label' 	=> esc_html__( 'Delimiter', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu > .cz:after' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_dropdown',
			[
				'label' 	=> esc_html__( 'Dropdown', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu .cz .sub-menu:not(.cz_megamenu_inner_ul), .sf-menu .cz_megamenu_inner_ul .cz_megamenu_inner_ul' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_ul_a',
			[
				'label' 	=> esc_html__( 'Inner Menus', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu .cz .cz a', '.sf-menu .cz .cz a:hover, .sf-menu .cz .cz:hover > a, .sf-menu .cz .cz.current_menu > a, .sf-menu .cz .current_menu > .current_menu' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_ul_a_indicator',
			[
				'label' 	=> esc_html__( 'Inner Idicator', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu .cz .cz a .cz_indicator' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_ul_ul',
			[
				'label' 	=> esc_html__( '3rd Level', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu .sub-menu .sub-menu:not(.cz_megamenu_inner_ul)' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_inner_megamenu',
			[
				'label' 	=> esc_html__( 'Megamenu', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu .cz_parent_megamenu > [class^="cz_megamenu_"] > .cz, .sf-menu .cz_parent_megamenu > [class*=" cz_megamenu_"] > .cz' ),
			]
		);

		$this->add_responsive_control(
			'sk_menu_a_h6',
			[
				'label' 	=> esc_html__( 'Megamenu Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.sf-menu .cz .cz h6' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		Xtra_Elementor::parallax( $settings );

		//$icon = empty( $settings['shopcart_icon'] ) ? 'fa fa-bars' : $settings['shopcart_icon'];

		ob_start();
		Icons_Manager::render_icon( $settings['menu_icon'], [ 'class' => 'xtra-search-icon', 'data-xtra-icon' => ( empty( $settings['search_icon'][ 'value' ] ) ? 'fa fa-bars' : $settings['search_icon'][ 'value' ] ) ] );
		$menu_icon = ob_get_clean();

		$type = empty( $settings['menu_type'] ) ? 'cz_menu_default' : $settings['menu_type'];
		if ( $type === 'offcanvas_menu_left' ) {
			$type = 'offcanvas_menu inview_left';
		} else if ( $type === 'offcanvas_menu_right' ) {
			$type = 'offcanvas_menu inview_right';
		}

		$menu_title = isset( $settings['menu_title'] ) ? do_shortcode( $settings['menu_title'] ) : '';
		$menu_icon_class = $menu_title ? ' icon_plus_text' : '';

		// Add icon and mobile menu
		if ( $type && $type !== 'offcanvas_menu' && $type !== 'cz_menu_default' ) {
			echo '<i class="' . esc_attr( ( empty( $settings['search_icon'][ 'value' ] ) ? 'fa fa-bars' : $settings['search_icon'][ 'value' ] ) . ' icon_' . $type . $menu_icon_class ) . '"><span>' . esc_html( $menu_title ) . '</span></i>';
		}
		echo '<i class="' . esc_attr( ( empty( $settings['search_icon'][ 'value' ] ) ? 'fa fa-bars' : $settings['search_icon'][ 'value' ] ) . ' hide icon_mobile_' . $type . $menu_icon_class ) . '"><span>' . esc_html( $menu_title ) . '</span></i>';

		// Default
		if ( empty( $settings['menu_location'] ) ) {
			$settings['menu_location'] = 'primary';
		}

		// Check for meta box and set one page instead primary
		$page_menu = Codevz_Plus::meta( 0, 'one_page' );
		if ( $settings['menu_location'] === 'primary' && $page_menu ) {
			$settings['menu_location'] = ( $page_menu === '1' ) ? 'one-page' : $page_menu;
		}

		// Disable three dots auto responsive
		$type .= empty( $settings['menu_disable_dots'] ) ? '' : ' cz-not-three-dots';

		// Indicators
		$indicator  = Codevz_Plus::get_string_between( Codevz_Plus::option( 'sk_menu_indicator' ), '_class_indicator:', ';' );
		$indicator2 = Codevz_Plus::get_string_between( Codevz_Plus::option( 'sk_menu_ul_a_indicator' ), '_class_indicator:', ';' );

		// Menu
		wp_nav_menu(
			apply_filters( 'xtra_nav_menu',
				[
					'theme_location' 	=> esc_attr( $settings['menu_location'] ),
					'cz_row_id' 		=> '',
					'cz_indicator' 		=> $indicator,
					'container' 		=> false,
					'fallback_cb' 		=> false,
					'walker' 			=> class_exists( 'Codevz_Walker_nav' ) ? new Codevz_Walker_nav() : false,
					'items_wrap' 		=> '<ul class="sf-menu clr ' . esc_attr( $type ) . '" data-indicator="' . esc_attr( $indicator ) . '" data-indicator2="' . esc_attr( $indicator2 ) . '">%3$s</ul>'
				]
			)
		);

		echo '<i class="fa czico-198-cancel cz_close_popup xtra-close-icon hide"></i>';

		Xtra_Elementor::parallax( $settings, true );

	}

}