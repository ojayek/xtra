<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_social_icons extends Widget_Base {

	protected $id = 'cz_social_icons';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Icon', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-social-icons';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Social', 'codevz' ),
			esc_html__( 'Icon', 'codevz' ),
			esc_html__( 'Share', 'codevz' ),

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
			'section_content',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title', [
				'label' => esc_html__( 'Title', 'codevz' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control (
			'icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fa fa-facebook-f',
					'library' => 'solid',
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'codevz' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://xtratheme.com',
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'social',
			[
				'label' => esc_html__( 'Add icon(s)', 'codevz' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'icon' => 'fas fa-facebook',
						'title' => '',
						'link' => '#'
					],
				],
			]
		);

		$this->add_control(
			'position',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tal',
				'options' => [
					'tal' => esc_html__( 'Left', 'codevz' ),
					'tac' => esc_html__( 'Center', 'codevz' ),
					'tar' => esc_html__( 'Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'tooltip',
			[
				'label' => esc_html__( 'Tooltip?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'cz_tooltip cz_tooltip_up' => esc_html__( 'Up', 'codevz' ),
					'cz_tooltip cz_tooltip_down' => esc_html__( 'Down', 'codevz' ),
					'cz_tooltip cz_tooltip_left' => esc_html__( 'Left', 'codevz' ),
					'cz_tooltip cz_tooltip_right' => esc_html__( 'Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'fx',
			[
				'label' => esc_html__( 'Hover effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'cz_social_fx_0' => esc_html__( 'ZoomIn', 'codevz' ),
					'cz_social_fx_1' => esc_html__( 'ZoomOut', 'codevz' ),
					'cz_social_fx_2' => esc_html__( 'Bottom to Top', 'codevz' ),
					'cz_social_fx_3' => esc_html__( 'Top to Bottom', 'codevz' ),
					'cz_social_fx_4' => esc_html__( 'Left to Right', 'codevz' ),
					'cz_social_fx_5' => esc_html__( 'Right to Left', 'codevz' ),
					'cz_social_fx_6' => esc_html__( 'Rotate', 'codevz' ),
					'cz_social_fx_7' => esc_html__( 'Infinite Shake', 'codevz' ),
					'cz_social_fx_8' => esc_html__( 'Infinite Wink', 'codevz' ),
					'cz_social_fx_9' => esc_html__( 'Quick Bob', 'codevz' ),
					'cz_social_fx_10' => esc_html__( 'Flip Horizontal', 'codevz' ),
					'cz_social_fx_11' => esc_html__( 'Flip Vertical', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'inline_title',
			[
				'label' => esc_html__( 'Inline title?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				
			]
		);

		$this->add_control(
			'color_mode',
			[
				'label' => esc_html__( 'Color Mode', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'cz_social_colored' => esc_html__( 'Original colors', 'codevz' ),
					'cz_social_colored_hover' => esc_html__( 'Original colors on hover', 'codevz' ),
					'cz_social_colored_bg' => esc_html__( 'Original background', 'codevz' ),
					'cz_social_colored_bg_hover' => esc_html__( 'Original background on hover', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'center_on_mobile',
			[
				'label' => esc_html__( 'Center on mobile', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
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
			'sk_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_social_icons' ),
			]
		);

		$this->add_responsive_control(
			'sk_icons',
			[
				'label' 	=> esc_html__( 'Icons', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'width', 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_social_icons a', '.cz_social_icons a:hover' ),
			]
		);


		$this->add_responsive_control(
			'sk_inner_icon',
			[
				'label' 	=> esc_html__( 'Inner icons', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'width', 'height', 'color', 'line-height', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_social_icons a i:before', '.cz_social_icons a:hover i:before' ),
			]
		);

		$this->add_responsive_control(
			'sk_title',
			[
				'label' 	=> esc_html__( 'Inline title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_social_icons span', '.cz_social_icons a:hover span' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$inline_title = $settings['inline_title'] ? 'cz_social_inline_title' : '';

		$social = '';
		foreach ( $settings['social'] as $i ) {
			
			if ( empty( $i[ 'icon' ][ 'value' ] ) ) {
				continue;
			}

			$i['title'] = empty( $i['title'] ) ? '' : $i['title'];
			$social_class = 'cz-' . str_replace( Codevz_Plus::$social_fa_upgrade, '', $i[ 'icon' ][ 'value' ] );

			$this->add_link_attributes( 'link', $i['link'] );

			$social .= '<a ' . $this->get_render_attribute_string( 'link' ) . ' class="' . $social_class . '"' . ( ( $settings['tooltip'] && $i['title'] ) ? ' data-' : '' ) . 'title="' . $i['title'] . '"' . ( $i['title'] ? ' aria-label="' . ( $i['title'] ) . '"' : '' ) . '><i class="' . $i[ 'icon' ][ 'value' ] . '">' . ( $inline_title ? '<span class="ml10">' . $i['title'] . '</span>' : '' ) . '</i></a>';
		}

		// Classes
		$classes = array();
		$classes[] = 'cz_social_icons cz_social clr';
		$classes[] = $inline_title;
		$classes[] = $settings['fx'];
		$classes[] = $settings['position'];
		$classes[] = $settings['color_mode'];
		$classes[] = $settings['tooltip'];
		$classes[] = $settings['center_on_mobile'] ? 'center_on_mobile' : '';
		
		Xtra_Elementor::parallax( $settings );
		
		?>
			<div<?php echo Codevz_Plus::classes( [], $classes ); ?>><?php echo wp_kses_post( $social ); ?></div>
		<?php

		Xtra_Elementor::parallax( $settings, true );
	}

	protected function content_template() {
		?>
		<#

		var inline_title = settings.inline_title ? 'cz_social_inline_title' : '',
			social ='';

		_.each( settings.social, function( i ) {

			if ( i.icon.value ) {

				var social_class = 'cz-' + i.icon.value.replace( /fa-|far-|fas-|fab-|fa |fas |far |fab |czico-|-square|-official|-circle'/gi, '' );

				social = social + '<a href="' + i.link.url + '" class="' + social_class + '"' + ( settings.tooltip && i.title ? ' data-' : '' ) + 'title="' + i.title + '"' + ( i.title ? ' aria-label="' + i.title + '"' : '' ) + '><i class="' + i.icon.value + '">' + ( inline_title ? '<span class="ml10">' + i.title + '</span>' : '' ) + '</i></a>';

			}

		});

		var classes = 'cz_social_icons cz_social clr', 
			classes = settings.fx ? classes + ' ' + settings.fx : classes;
			classes = inline_title ? classes + ' ' + inline_title : classes;
			classes = settings.position ? classes + ' ' + settings.position : classes;
			classes = settings.color_mode ? classes + ' ' + settings.color_mode : classes;
			classes = settings.tooltip ? classes + ' ' + settings.tooltip : classes,
			classes = settings.center_on_mobile ? classes + ' center_on_mobile' : classes;

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );
		#>

		{{{ parallaxOpen }}}

		<div class="{{{classes}}}">{{{social}}}</div>

		{{{ parallaxClose }}}

		<?php
	}
}