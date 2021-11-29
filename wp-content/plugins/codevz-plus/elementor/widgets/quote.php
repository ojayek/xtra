<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_quote extends Widget_Base {

	protected $id = 'cz_quote';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Quote', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-quote';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Quote', 'codevz' ),
			esc_html__( 'Testimonials', 'codevz' ),
			esc_html__( 'Blockquote', 'codevz' ),
			esc_html__( 'Cite', 'codevz' ),

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

	public function register_controls(){
		$this->start_controls_section(
			'section_content',
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
				'default' => '',
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_quote_center' => esc_html__( 'Default center', 'codevz' ),
					'cz_quote_arrow' => esc_html__( 'Arrow bottom', 'codevz' ),
					'cz_quote_arrow cz_quote_center' => esc_html__( 'Arrow bottom center', 'codevz' ),
					'cz_quote_arrow cz_quote_top' => esc_html__( 'Arrow top', 'codevz' ),
					'cz_quote_arrow cz_quote_top cz_quote_center' => esc_html__( 'Arrow top center', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__('Content','codevz'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Great things in business are never done by one person. They are done by a team of people.',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'full',
				'separator' => 'none',
				'condition' => [
					'xxx' => 'xxx',
				],
			]
		);

		$this->add_control(
			'name',
			[
				'label' => esc_html__( "Name", 'codevz'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'John Doe', 'codevz' ),
				'placeholder' => esc_html__( 'John Doe', 'codevz' ),
			]
		);

		$this->add_control(
			'subname',
			[
				'label' => esc_html__( "Sub name", 'codevz'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Businessman', 'codevz' ),
				'placeholder' => esc_html__( 'Businessman', 'codevz' ),
			]
		);

		$this->add_control(
			'rating',
			[
				'label' => esc_html__( 'Stars rating?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'5' => '5',
					'4.5' => '4.5',
					'4' => '4',
					'3.5' => '3.5',
					'3' => '3',
					'2.5'  => '2.5',
					'2'  => '2',
					'1.5'  => '1.5',
					'1'  => '1',
					'0.5'  => '0.5',
					'Zero'  => '0',
				],
			]
		);

		$this->add_control(
			'quote_position',
			[
				'label' => __( 'Quote shape', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => esc_html__( 'None', 'codevz' ),
					'absolute;top: 10%;left: 10%' => esc_html__( 'Top left', 'codevz' ),
					'absolute;top: 10%;left: calc(50% - 60px)' => esc_html__( 'Top center', 'codevz' ),
					'absolute;top: 10%;right: 10%' => esc_html__( 'Top right', 'codevz' ),
					'absolute;bottom: 10%;left: 10%' => esc_html__( 'Bottom left', 'codevz' ),
					'absolute;bottom: 10%;left: calc(50% - 60px)' => esc_html__( 'Bottom center', 'codevz' ),
					'absolute;bottom: 10%;right: 10%'  => esc_html__( 'Bottom right', 'codevz' ),
					'absolute;top: calc(50% - 60px);right: calc(50% - 60px)'  => esc_html__( 'Center center', 'codevz' ),
					'relative;margin: 0 0 20px;font-size: 40px;opacity: 1'  => esc_html__( 'Left relative', 'codevz' ),
					'relative;margin: 0 auto 20px;font-size: 40px;text-align: center;opacity: 1'  => esc_html__( 'Center relative', 'codevz' ),
					'relative;margin: 0 auto 20px;font-size: 40px;text-align: right;opacity: 1'  => esc_html__( 'Right relative', 'codevz' ),
					'absolute;top: 10%;left: 10%;'  => esc_html__( 'Top left + Bottom right', 'codevz' ),
				],
				'selectors' => [
					'{{WRAPPER}} .cz_quote_shape' => 'position: {{VALUE}};display: block !important;',
				],
			]
		);

		$this->add_control(
			'text_center',
			[
				'label' => __( 'Center on mobile?', 'codevz' ),
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

		$this->add_control(
			'rating_color',
			[
				'label' => esc_html__( 'Stars color', 'codevz' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cz_quote_rating' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quote_color',
			[
				'label' => esc_html__( 'Quote color', 'codevz' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cz_quote_shape' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sk_overall',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_quote blockquote' ),
			]
		);

		$this->add_responsive_control(
			'sk_image',
			[
				'label' 	=> esc_html__( 'Image', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border', 'padding' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_quote .cz_quote_info img' ),
			]
		);

		$this->add_responsive_control(
			'sk_name',
			[
				'label' 	=> esc_html__( 'Name', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_quote h4' ),
			]
		);

		$this->add_responsive_control(
			'sk_subname',
			[
				'label' 	=> esc_html__( 'Sub name', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_quote h4 small' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Variable's
		if ( $settings['rating'] !== '' ) {
			$rating_number = $settings['rating'];
			$half_rating = ( strpos( $rating_number, '.' ) !== false ) ? '<i class="fa fa-star-half-o"></i>' : '';
			$settings['rating'] = '<div class="cz_quote_rating" style="color: ' . $settings['rating_color'] . '">' . str_repeat( '<i class="fa fa-star"></i>', floor( $rating_number ) ) . $half_rating;
			$settings['rating'] .= str_repeat( '<i class="fa fa-star-o"></i>', ( 5 - round( $rating_number ) ) );
			$settings['rating'] .= '</div>';
		}

		$cite 	= $settings['name'] ? '<h4>' . $settings['name'] . '<small>' . $settings['subname'] . '</small></h4>' : '';
		$cite 	= $cite . $settings['rating'];
		$image 	= Group_Control_Image_Size::get_attachment_image_html( $settings );
		$text 	= '<div class="cz_quote_content cz_wpe_content">' . $settings['content'] . '</div>';
		$quote_start_end = ( strpos( $settings['quote_position'], 'left' ) !== false ) ? 'left' : 'right';
		
		$icon 	= '<i class="fa fa-quote-' . $quote_start_end . ' cz_quote_shape hidden"></i>';
		$sub 	= ( $image || $cite ) ? '<div class="cz_quote_info">' . $image . $cite . '</div>' : '';

		// Check if both quote selected
		if ( $settings['quote_position'] === 'absolute;top: 10%;left: 10%;' ) {
			$icon .= '<i class="fa fa-quote-right cz_quote_shape cz_quote_both_second"' . ( $icon_color ? ' style="' . $icon_color. '"' : '' ) . '></i>';
		}

		// Classes
		$classes = array();
		$classes[] = 'cz_quote';
		$classes[] = $settings['style'];
		$classes[] = $settings['text_center'] ? 'cz_mobile_text_center' : '';

		Xtra_Elementor::parallax( $settings );

		?>
		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>>
		<?php if ( strpos( $settings['style'], 'cz_quote_top' ) !== false ) {
			echo wp_kses_post( $sub . '<blockquote>' . $icon . $text . '</blockquote>' );
		} else if ( strpos( $settings['style'], 'cz_quote_arrow' ) !== false ) {
			 echo wp_kses_post( '<blockquote>' . $icon . $text . '</blockquote>' . $sub );
		} else {
			echo '<blockquote>' . wp_kses_post( $icon . $text . $sub ) . '</blockquote>';
		}
		?>
		</div>
		<?php

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

		if ( settings.rating ) {

			var rating_number = settings.rating,
				half_rating = rating_number.indexOf( '+' ) >= 0 ? '<i class="fa fa-star-half-o"></i>' : '';

			settings.rating = '<div class="cz_quote_rating" style="color: ' + settings.rating_color + '">' + ( '<i class="fa fa-star"></i>'.repeat( Math.floor( rating_number ) ) ) + half_rating + '<i class="fa fa-star-o"></i>'.repeat( 5 - Math.round( rating_number ) ) + '</div>';

		}

		var cite 	= settings.name ? '<h4>' + settings.name + '<small>' + settings.subname + '</small></h4>' : '',
			cite 	= cite + settings.rating,

			image 	= settings.image ? '<img src="' + image_url + '">' : '',

			text 	= '<div class="cz_quote_content cz_wpe_content">' + settings.content + '</div>',

			quote_start_end = settings.quote_position.indexOf( 'left' ) >= 0 ? 'left' : 'right',

			icon 	= '<i class="fa fa-quote-' + quote_start_end + ' cz_quote_shape hidden"></i>',

			sub 	= ( image || cite ) ? '<div class="cz_quote_info">' + image + cite + '</div>' : '';

		if ( settings.quote_position === 'absolute;top: 10%;left: 10%;' ) {
			icon = icon + '<i class="fa fa-quote-right cz_quote_shape cz_quote_both_second"></i>';
		}

		var classes = 'cz_quote', 
			classes = settings.style ? classes + ' ' + settings.style : classes,
			classes = settings.text_center ? classes + ' cz_mobile_text_center' : classes,

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );
		#>

		{{{ parallaxOpen }}}

		<div class="{{{classes}}}">
			<# if ( settings.style.indexOf( 'cz_quote_top' ) >= 0 ) { #>
				{{{sub}}}<blockquote>{{{icon}}}{{{text}}}</blockquote>
			<# } else if ( settings.style.indexOf( 'cz_quote_arrow' ) >= 0 ) { #>
				 <blockquote>{{{icon}}}{{{text}}}</blockquote>{{{sub}}}
			<# } else { #>
				 <blockquote>{{{icon}}}{{{text}}}{{{sub}}}</blockquote>
			<# } #>
		</div>

		{{{ parallaxClose }}}
		<?php

	}

}