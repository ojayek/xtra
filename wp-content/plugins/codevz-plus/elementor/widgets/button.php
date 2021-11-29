<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_button extends Widget_Base {

	protected $id = 'cz_button';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Button', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-button';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Button', 'codevz' ),
			esc_html__( 'Btn', 'codevz' ),
			esc_html__( 'Call to action', 'codevz' ),

		];

	}

	public function get_style_depends() {

		$array = [ $this->id, 'cz_parallax' ];

		if ( Codevz_Plus::$is_rtl ) {
			$array[] = $this->id . '_rtl';
		}

		return $array;

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
		
		$this->add_control(
			'title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> esc_html__( 'Button title', 'codevz' ),
				'placeholder' => esc_html__( 'Button title', 'codevz' ),
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' =>esc_html__( 'Subtitle', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);
		
		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'codevz' ),
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
			'btn_position',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' 				=> esc_html__( 'Select', 'codevz' ),
					'cz_btn_inline' => esc_html__( 'Inline', 'codevz' ),
					'cz_btn_block' 	=> esc_html__( 'Block', 'codevz' ),
					'cz_btn_left' 	=> ( Codevz_Plus::$is_rtl ? esc_html__( "Right", 'codevz') : esc_html__( "Left", 'codevz') ),
					'cz_btn_center' => esc_html__( "Center", 'codevz'),
					'cz_btn_right' 	=> ( Codevz_Plus::$is_rtl ? esc_html__( "Left", 'codevz') : esc_html__( "Right", 'codevz') ),
					'cz_btn_left cz_mobile_btn_center' 	=> ( Codevz_Plus::$is_rtl ? esc_html__( "Right", 'codevz') : esc_html__( "Left", 'codevz') ) . ' ' . esc_html__( '(Center in mobile)', 'codevz'),
					'cz_btn_right cz_mobile_btn_center'	=> ( Codevz_Plus::$is_rtl ? esc_html__( "Left", 'codevz') : esc_html__( "Right", 'codevz') ) . ' ' . esc_html__( '(Center in mobile)', 'codevz'),
					'cz_btn_left cz_mobile_btn_block' 	=> ( Codevz_Plus::$is_rtl ? esc_html__( "Right", 'codevz') : esc_html__( "Left", 'codevz') ) . ' ' . esc_html__( '(Block in mobile)', 'codevz'),
					'cz_btn_right cz_mobile_btn_block' 	=> ( Codevz_Plus::$is_rtl ? esc_html__( "Left", 'codevz') : esc_html__( "Right", 'codevz') ) . ' ' . esc_html__( '(Block in mobile)', 'codevz'),
				],
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__( 'Icon', 'codevz' ),
					'image' => esc_html__( 'Image', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'icon_type' => 'icon'
				]
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => __( 'Icon Position', 'plugin-domain' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'before' => esc_html__( 'Before title', 'plugin-domain' ),
					'after' => esc_html__( 'After title', 'plugin-domain' ),
				],
				'default' => 'before'
			]
		);


		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Codevz_Plus::$url . 'assets/img/p.svg',
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);
		$this->add_control(
			'hover_image',
			[
				'label' => esc_html__( 'Hover Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_fx',
			[
				'label' => esc_html__( 'Effects', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
			  
		$this->add_control(
			'btn_effect',
			[
				'label' => esc_html__( 'Button Effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_btn_no_fx',
				'options' => [
					'cz_btn_no_fx' => esc_html__( 'Select', 'codevz' ),
					'cz_btn_move_up' => esc_html__( 'Move Up', 'codevz' ),
					'cz_btn_zoom_in' => esc_html__( 'Zoom In', 'codevz' ),
					'cz_btn_zoom_out' => esc_html__( 'Zoom Out', 'codevz' ),
					'cz_btn_winkle' => esc_html__( 'Winkle', 'codevz' ),
					'cz_btn_absorber' => esc_html__( 'Absorber', 'codevz' ),
					'cz_btn_half_to_fill' => esc_html__( 'Low to Fill', 'codevz' ),
					'cz_btn_half_to_fill_v' => esc_html__( 'Low to Fill Vertical', 'codevz' ),
					'cz_btn_fill_up' => esc_html__( 'Fill Up', 'codevz' ),
					'cz_btn_fill_down' => esc_html__( 'Fill Down', 'codevz' ),
					'cz_btn_fill_left' => esc_html__( 'Fill Left', 'codevz' ),
					'cz_btn_fill_right' => esc_html__( 'Fill Right', 'codevz' ),
					'cz_btn_beat' => esc_html__( 'Single Hard Beat', 'codevz' ),
					'cz_btn_flash' => esc_html__( 'Flash', 'codevz' ),
					'cz_btn_shine' => esc_html__( 'Shine', 'codevz' ),
					'cz_btn_circle_fade' => esc_html__( 'Circle Fade', 'codevz' ),
					'cz_btn_blur' => esc_html__( 'Blur', 'codevz' ),
					'cz_btn_unroll_v' => esc_html__( 'Unroll Vertical', 'codevz' ),
					'cz_btn_unroll_h' => esc_html__( 'Unroll Horizontal', 'codevz' ),
					
				],
			]
		);
		
		$this->add_control(
			'text_effect',
			[
				'label' => esc_html__( 'Text Effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_btn_txt_no_fx',
				'options' => [
					'cz_btn_txt_no_fx' => esc_html__( 'Select', 'codevz' ),
					'cz_btn_txt_fade' => esc_html__( 'Simple Fade', 'codevz' ),
					'cz_btn_txt_move_up' => esc_html__( 'Text Move Up', 'codevz' ),
					'cz_btn_txt_move_down' => esc_html__( 'Text Move Down', 'codevz' ),
					'cz_btn_txt_move_right' => esc_html__( 'Text Move Right', 'codevz' ),
					'cz_btn_txt_move_left' => esc_html__( 'Text Move Left', 'codevz' ),
					'cz_btn_move_up_icon' => esc_html__( 'Move Up Show Icon', 'codevz' ),
					'cz_btn_show_hidden_icon' => esc_html__( 'Show Hidden Icon', 'codevz' ),
					'cz_btn_ghost_icon' => esc_html__( 'Ghost Icon', 'codevz' ),
					'cz_btn_zoom_out_in' => esc_html__( 'Zoom Out In', 'codevz' ), 
				],
			]
		);
		
		$this->add_control(
			'alt_title',
			[
				'label' => esc_html__( 'Alternative title', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'text_effect!' => 'cz_btn_txt_no_fx'
				],
			]
		);

		$this->add_control(
			'alt_subtitle',
			[
				'label' => esc_html__( 'Alternative subtitle', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'text_effect!' => 'cz_btn_txt_no_fx'
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
			'sk_button',
			[
				'label' 	=> esc_html__( 'Button', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_btn, .cz_btn:before', '.cz_btn:hover, .cz_btn:after' ),
			]
		);

		$this->add_responsive_control(
			'sk_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_btn i', '.cz_btn:hover i' ),
			]
		);

		$this->add_responsive_control(
			'sk_subtitle',
			[
				'label' 	=> esc_html__( 'Subtitle', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_btn small', '.cz_btn:hover small' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$this->add_link_attributes( 'link', $settings['link'] );
	
		$icon = $icon_after = '';

		if ( $settings['icon_type'] === 'icon' && $settings['icon'] ) {

			ob_start();
			Icons_Manager::render_icon( $settings['icon'] );
			$icon = ob_get_clean();

		} else if ( $settings['icon_type'] === 'image' && $settings['image'] ) {

			$icon = '<i>' . Group_Control_Image_Size::get_attachment_image_html( $settings );

			if ( empty( $settings[ 'hover_image' ][ 'url' ] ) ) {
				$settings[ 'hover_image' ] = $settings[ 'image' ];
			}

			$icon .= Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'hover_image' );

			$icon .= '</i>';

		}
		
		// Icon position.	
		if( $settings['icon_position'] === 'after' ) {
			$icon_after = $icon;
			$icon = '';
			$settings['btn_effect'] .= ' cz_btn_icon_after';
		}

		// Subtitle
		$subtitle = $settings['subtitle'] ? '<small>' . $settings['subtitle'] . '</small>' : '';
		$alt_subtitle = $settings['alt_subtitle'] ? '<small>' . $settings['alt_subtitle'] . '</small>' : $subtitle;

		// Classes
		$classes = [];
		$classes[] = 'cz_btn';
		$classes[] = $subtitle ? 'cz_btn_subtitle' : '';
		$classes[] = $settings['text_effect'];
		$classes[] = $settings['btn_effect'];
		$classes[] = empty( $settings['btn_position'] ) ? 'cz_mobile_btn_center' : '';
		$classes[] = $settings['icon_type'] === 'image' ? 'cz_btn_has_image' : '';
		$clr = Codevz_Plus::contains( $settings['btn_position'], [ 'btn_left', 'btn_right' ] ) ? '<div class="clr"></div>' : '';

		// Parallax.
		Xtra_Elementor::parallax( $settings );

		?>
		<div class="<?php echo esc_attr( $settings['btn_position'] ); ?>">
			<div>
				<a <?php echo $this->get_render_attribute_string( 'link' ) . Codevz_Plus::classes( [], $classes ); ?>>

					<span><?php echo wp_kses_post( $icon ); ?>
						<strong><?php echo do_shortcode( wp_kses_post( $settings['title'] ) ); ?>
							<?php echo do_shortcode( wp_kses_post( $subtitle ) ); ?>
						</strong>
						<?php echo wp_kses_post( $icon_after ); ?>
					</span>

					<b class="cz_btn_onhover">
						<?php echo wp_kses_post( $icon ); ?>
						<strong>
							<?php echo do_shortcode( wp_kses_post( $settings['alt_title'] ? $settings['alt_title'] : $settings['title'] ) ); ?>
							<?php echo do_shortcode( wp_kses_post( $alt_subtitle ) ); ?>
						</strong>
						<?php echo wp_kses_post( $icon_after ); ?>
					</b>

				</a>
			</div>
		</div> <?php echo wp_kses_post( $clr ); ?>
		<?php

		// Close parallax.
		Xtra_Elementor::parallax( $settings, true );
	}

	protected function content_template() {
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

		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' ),

			subtitle = settings.subtitle ? '<small>' + settings.subtitle + '</small>' : '',
			alt_subtitle = settings.alt_subtitle  ? '<small>' + settings.alt_subtitle + '</small>' : subtitle,

			icon = icon_after = '',
			clr = '';

		if ( settings.btn_position.indexOf( 'left' ) >= 0 || settings.btn_position.indexOf( 'right' ) >= 0 ) {
			clr = '<div class="clr"></div>';
		}

		if ( settings.icon_type === 'icon' && settings.icon ) {

			icon = iconHTML.value;

		} else if ( settings.icon_type === 'image' && settings.image ) {

			hover_image_url = hover_image_url ? hover_image_url : image_url;

			icon = '<i><img src="' + image_url + '"><img src="' + hover_image_url + '"></i>';

		}

		// Icon position.	
		if ( settings.icon_position === 'after' ) {
			icon_after = icon;
			icon = '';
			settings.btn_effect = settings.btn_effect + ' cz_btn_icon_after';
		}

		var classes = 'cz_btn',
			classes = classes + ( subtitle ? ' cz_btn_subtitle': '' ), 
			classes = classes + ' ' + settings.text_effect,
			classes = classes + ' ' + settings.btn_effect,
			classes = classes + ( settings.btn_position ? ' cz_mobile_btn_center' : '' ),
			classes = classes + ( settings.icon_type == 'image' ? ' cz_btn_has_image': '' ),

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );

		#>

		{{{ parallaxOpen }}}

		<div class="{{{settings.btn_position}}}">
			<div>
				<a class="{{{classes}}}" href="{{{settings.link.url}}}">
				
				<span>{{{ icon }}}
					<strong>{{{settings.title}}}
						{{{subtitle}}}
					</strong>
					{{{icon_after}}}
				</span>

				<b class="cz_btn_onhover">
				{{{icon}}}
				<strong>
					<#
						if ( settings.alt_title ) {
							#>{{{settings.alt_title}}}<#
						} else {
							#>{{{settings.title}}}<#
						}
					 #>
					{{{ alt_subtitle }}}
				</strong>
				{{{icon_after}}}</b></a>
			</div>
		</div>{{{clr}}}

		{{{ parallaxClose }}}
		<?php
	}
}