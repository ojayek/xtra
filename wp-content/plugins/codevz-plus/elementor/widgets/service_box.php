<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Codevz_Plus as Codevz_Plus;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_service_box extends Widget_Base {

	protected $id = 'cz_service_box';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Service Box', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-service-box';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Service', 'codevz' ),
			esc_html__( 'Box', 'codevz' ),
			esc_html__( 'Icon', 'codevz' ),
			esc_html__( 'Item', 'codevz' ),

		];

	}

	public function get_style_depends() {

		$array = [ $this->id, 'cz_button', 'cz_parallax' ];

		if ( Codevz_Plus::$is_rtl ) {
			$array[] = $this->id . '_rtl';
			$array[] = 'cz_button_rtl';
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
			'type',
			[
				'label' => esc_html__( 'Layout', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'codevz' ),
					'vertical' => esc_html__( 'Vertical', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'align',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'left' => esc_html__( 'Left', 'codevz' ),
					'right' => esc_html__( 'Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Your Title' ,'codevz' )
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__('Description','codevz'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem ipsum dolor sit amet, conse ctetur adipi scing elit. duis odio nisl, tinci dunt eturn sed molis velit.',
			]
		);

		$this->add_control(
			'btn',
			[
				'label' => esc_html__( 'Button', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'btn_icon',
			[
				'label' 		=> esc_html__( 'Button Icon', 'codevz' ),
				'type' 			=> Controls_Manager::ICONS,
				'skin' 			=> 'inline',
				'label_block' 	=> false
			]
		);

		$this->add_control(
			'btn_pos',
			[
				'label' => esc_html__( 'Button position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( '~ Default ~', 'codevz' ),
					'left' => esc_html__( 'Left', 'codevz' ),
					'center' => esc_html__( 'Center', 'codevz' ),
					'right' => esc_html__( 'Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'codevz' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://xtratheme.com',
			]
		);

		$this->add_control(
			'link_only_btn',
			[
				'label' => esc_html__( 'Link only button', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'xtra_service_box_cover',
			[
				'label' => esc_html__( 'Cover', 'codevz' )
			]
		);

		$this->add_control(
			'cover',
			[
				'label' 	=> esc_html__( 'Cover', 'codevz' ),
				'type' 		=> Controls_Manager::MEDIA
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cz_title_icons',
			[
				'label' => esc_html__( 'Icon', 'codevz' )
			]
		);

		$this->add_control(
			'style',
			[
				'label' => esc_html__( 'Icon type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => esc_html__( 'Icon', 'codevz' ),
					'style9' => esc_html__( 'Hexagon Icon', 'codevz' ),
					'style11' => esc_html__( 'Image', 'codevz' ),
					'style10' => esc_html__( 'Number', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'icon_fx',
			[
				'label' => esc_html__( 'Hover effect?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'cz_sbi_fx_0' => esc_html__( 'ZoomIn', 'codevz' ),
					'cz_sbi_fx_1' => esc_html__( 'ZoomOut', 'codevz' ),
					'cz_sbi_fx_2' => esc_html__( 'Bottom to Top', 'codevz' ),
					'cz_sbi_fx_3' => esc_html__( 'Top to Bottom', 'codevz' ),
					'cz_sbi_fx_4' => esc_html__( 'Left to Right', 'codevz' ),
					'cz_sbi_fx_5' => esc_html__( 'Right to Left', 'codevz' ),
					'cz_sbi_fx_6' => esc_html__( 'Rotate', 'codevz' ),
					'cz_sbi_fx_7a' => esc_html__( 'Shake', 'codevz' ),
					'cz_sbi_fx_7' => esc_html__( 'Shake Infinite', 'codevz' ),
					'cz_sbi_fx_8a' => esc_html__( 'Wink', 'codevz' ),
					'cz_sbi_fx_8' => esc_html__( 'Wink Infinite', 'codevz' ),
					'cz_sbi_fx_9a' => esc_html__( 'Quick Bob', 'codevz' ),
					'cz_sbi_fx_9' => esc_html__( 'Quick Bob Infinite', 'codevz' ),
					'cz_sbi_fx_10' => esc_html__( 'Flip Horizontal', 'codevz' ),
					'cz_sbi_fx_11' => esc_html__( 'Flip Vertical', 'codevz' ),
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
					'style' => ['style1','style9'],
				]
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
				'condition' => [
					'style' => 'style11',
				]
			]
		);

		$this->add_control(
			'image_hover',
			[
				'label' => esc_html__( 'Image Hover', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'style' => 'style11',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
				'separator' => 'none',
				'condition' => [
					'style' => 'style11',
				]
			]
		);

		$this->add_control(
			'number',
			[
				'label' => esc_html__( 'Number', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => 1,
				'condition' => [
					'style' => 'style10',
				]
			]
		);

		$this->end_controls_section();

		//Separator

		$this->start_controls_section(
			'section_separator',
			[
				'label' => esc_html__( 'Separator', 'codevz' ),
				'condition' => [
					'type' => 'vertical',
				]
			]
		);

		$this->add_control(
			'separator',
			[
				'label' => esc_html__( 'Separator', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'off',
				'options' => [
					'off' => esc_html__( 'Off', 'codevz' ),
					'line' => esc_html__( 'Line', 'codevz' ),
					'icon' => esc_html__( 'Icon', 'codevz' ),
				],
				'condition' => [
					'type' => 'vertical',
				]
			]
		);

		$this->add_control(
			'sep_icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'separator' => 'icon',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cz_title',
			[
				'label' => esc_html__( 'Hover Effect', 'codevz' )
			]
		);

		$this->add_control(
			'fx',
			[
				'label' => __( 'Normal', 'plugin-domain' ),
				'type' => Controls_Manager::SELECT,
				'options' => array_flip( Codevz_Plus::fx() )
			]
		);

		$this->add_control(
			'fx_hover',
			[
				'label' => __( 'Hover', 'plugin-domain' ),
				'type' => Controls_Manager::SELECT,
				'options' => array_flip( Codevz_Plus::fx( '_hover' ) )
			]
		);
		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );


		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_overall',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'margin', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services', '.services:hover' ),
			]
		);

		$this->add_responsive_control(
			'sk_title',
			[
				'label' 	=> esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services h3, services h3 a', '.services:hover h3, services:hover h3 a' ),
			]
		);

		$this->add_responsive_control(
			'sk_con',
			[
				'label' 	=> esc_html__( 'Content', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'margin', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services .service_text', '.services:hover .service_text' ),
			]
		);

		$this->add_responsive_control(
			'sk_button',
			[
				'label' 	=> esc_html__( 'Button', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services .cz_btn', '.services:hover .cz_btn' ),
			]
		);

		$this->add_responsive_control(
			'sk_button_icon',
			[
				'label' 	=> esc_html__( 'Button Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services .cz_btn i', '.services:hover .cz_btn i' ),
			]
		);

		$this->add_responsive_control(
			'sk_line',
			[
				'label' 	=> esc_html__( 'Line', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'width', 'height', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services .cz_sb_sep_line', '.services:hover .cz_sb_sep_line' ),
			]
		);

		$this->add_responsive_control(
			'sk_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services .cz_hexagon, .services i:not(.cz_sb_sep_icon)', '.services:hover .cz_hexagon, .services:hover i:not(.cz_sb_sep_icon)' ),
			]
		);

		$this->add_responsive_control(
			'sk_icon_con',
			[
				'label' 	=> esc_html__( 'Icon container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'margin', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services .service_custom' ),
			]
		);

		$this->add_responsive_control(
			'sk_image',
			[
				'label' 	=> esc_html__( 'Image', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services .service_img:not(.service_number)', '.services:hover .service_img:not(.service_number)' ),
				'condition' => [
					'style' => 'style11',
				]
			]
		);

		$this->add_responsive_control(
			'sk_num',
			[
				'label' 	=> esc_html__( 'Number', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.services .service_number', '.services:hover .service_number' ),
				'condition' => [
					'style' => 'style10',
				]
			]
		);

		$this->add_responsive_control(
			'sk_sep',
			[
				'label' 	=> esc_html__( 'Separator Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'condition' => [
					'type' => 'vertical',
				],
				'selectors' => Xtra_Elementor::sk_selectors( '.services .cz_sb_sep_icon', '.services:hover .cz_sb_sep_icon' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();
		$this->add_link_attributes( 'link', $settings['link'] );

		ob_start();
		Icons_Manager::render_icon( $settings['icon'] );
		$icon = ob_get_clean();

		// Style
		$style = $settings['style'];

		// Title
		$title = $settings['title'] ? '<h3>' . $settings['title'] . '</h3>' : '';
		
		$return2 = '';

		if ( $style === 'style10' ) {

			$return2 = '<div class="service_img service_number">' . $settings['number'] . '</div>' ;

		} else {

			if ( $settings['image'] ) {

				$img = Group_Control_Image_Size::get_attachment_image_html( $settings );

				$settings[ 'image' ] = $settings[ 'image_hover' ];

				$img .= Group_Control_Image_Size::get_attachment_image_html( $settings );

				$return2 = '<div class="service_img' . ( ! empty( $settings['image_hover'][ 'url' ] ) ? ' services_img_have_hover' : '' ) . '">' . $img . '</div>' ;

			} else if ( $settings['icon'] ){

				if ( $style == 'style9' ) {
					$return2 = '<div class="cz_hexagon service_custom">' . $icon . '</div>';
				} else {
					$return2 = $icon;
				}

			}

		}

		if ( $style == 'style1' ) {
			$return2 = '<div class="service_custom">' . $return2 .'</div>';
		}

		// Content
		$content = $settings['content'];

		// Separator
		ob_start();
		Icons_Manager::render_icon( $settings['sep_icon'], [ 'class' => 'cz_sb_sep_icon' ] );
		$icon = ob_get_clean();

		$separator = '';
		if ( $settings['separator'] === 'line' ) {
			$separator = '<span class="cz_sb_sep_line bar"></span>';
		} else if ( $settings['separator'] === 'icon' ) {
			$separator = $icon;
		}

		// Link 
		$a_attr = $this->get_render_attribute_string( 'link' );

		// Button
		$btn = '';
		if ( $settings['btn'] || ! empty( $settings['btn_icon'][ 'value' ] ) ) {

			ob_start();
			Icons_Manager::render_icon( $settings['btn_icon'] );
			$settings['btn'] .= ob_get_clean();

			$btn_pos = $settings['btn_pos'] ? ' xtra-service-btn-' . $settings['btn_pos'] : '';
			$btn = $settings['link_only_btn'] ? '<a ' . $a_attr . ' class="cz_btn cz_btn_icon_after' . $btn_pos . '">' . $settings['btn'] . '</a>' : '<div class="cz_btn cz_btn_icon_after' . $btn_pos . '">' . $settings['btn'] . '</div>';
		
		}

		// Classes
		$classes = array();
		$classes[] = 'services clr';
		$classes[] = $style;
		$classes[] = $settings['icon_fx'] ? $settings['icon_fx'] : '';

		if ( $settings['type'] === 'vertical' && $settings['align'] ) {
			$return2 .= '<div class="clr"></div>';
		}

		$cover = Group_Control_Image_Size::get_attachment_image_html( $settings, 'cover' );

		// Type
		if ( $settings['type'] === 'vertical' ) {
			$classes[] = 'services_b';
			$classes[] = $settings['align'];
			$return = '<div' . Codevz_Plus::classes( [], $classes ) . '>' . $cover;
			$return .= $return2 . '<div class="service_text">' . $title . $separator . '<div class="cz_wpe_content">' . $content . '</div>' . $btn . '</div></div>';
		} else {
			$classes[] = $settings['align'] ? $settings['align'] : 'left';
			$return = '<div' . Codevz_Plus::classes( [], $classes ) . '>' . $cover;
			$return .= $return2 . '<div class="service_text">' . $title . '<div class="cz_wpe_content">' . $content . '</div>' . $btn . '</div></div>';
		}

		Xtra_Elementor::parallax( $settings );

		echo $settings['fx'] ? '<div class="' . $settings['fx'] . '">' : '';
		echo $settings['fx_hover'] ? '<div class="' . $settings['fx_hover'] . '">' : '';

		if ( $a_attr && ! $settings['link_only_btn'] ) {
			$return = '<a ' . $a_attr . '>' . preg_replace( '/<a .*?<\/a>/', '', $return ) . '</a>';
		}

		echo $return;
		echo $settings['fx_hover'] ? '</div>' : '';
		echo $settings['fx'] ? '</div>' : '';

		Xtra_Elementor::parallax( $settings, true );

	}

	public function content_template() {
		?>
		<#

		if ( settings.cover.url ) {
			var cover = {
				id: settings.cover.id,
				url: settings.cover.url,
				size: settings.cover,
				dimension: settings.cover_custom_dimension,
				model: view.getEditModel()
			};

			var cover_url = elementor.imagesManager.getImageUrl( cover );

			if ( ! cover_url ) {
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
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			if ( ! image_url ) {
				return;
			}
		}

		if ( settings.image_hover.url ) {
			var image_hover = {
				id: settings.image_hover.id,
				url: settings.image_hover.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_hover_url = elementor.imagesManager.getImageUrl( image_hover );

			if ( ! image_hover_url ) {
				return;
			}
		}

		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' ),
			buttonIcon = elementor.helpers.renderIcon( view, settings.btn_icon, { 'aria-hidden': true }, 'i' , 'object' ),
			sepiconHTML = elementor.helpers.renderIcon( view, settings.sep_icon, { 'aria-hidden': true, 'class': 'cz_sb_sep_icon' }, 'i' , 'object' ),
			style = settings.style,
			title = settings.title ? '<h3>' + settings.title + '</h3>' : '';

		if ( style === 'style10' ) {
			var return2 = '<div class ="service_img service_number">' + settings.number + '</div>';
		} else {

			if ( settings.style === 'style11' && settings.image ) {

				var img = '<img src ="' + image_url + '">',
					img = img + '<img src ="' + ( image_hover_url ? image_hover_url : image_url ) + '">';

				var return2 = '<div class ="service_img' + ( settings.image_hover ? ' services_img_have_hover' : '' ) + '">' + img + '</div>';

			} else if ( settings.icon ) {
				iconHTML.value = iconHTML.value ? iconHTML.value : '';
				if ( style == 'style9' ) {
					var return2 = '<div class="cz_hexagon service_custom">' + iconHTML.value +'</div>';
				} else {
					var return2 = iconHTML.value;
				}
			}
		}

		if ( style == 'style1' ) {
			var return2 = '<div class="service_custom">' + return2 + '</div>';
		}

		var content = settings.content;

		var separator = '';
		if ( settings.separator === 'line' ) {
			separator = '<span class="cz_sb_sep_line bar"></span>';
		} else if ( settings.separator === 'icon' && sepiconHTML.value ) {
			separator = sepiconHTML.value;
		}

		var btn = '';
		if ( settings.btn || buttonIcon.value ) {
			var btn_pos = settings.btn_pos ? ' xtra-service-btn-' + settings.btn_pos : '';
			btn = settings.link_only_btn ? '<a href="' + settings.link.url + '" class="cz_btn cz_btn_icon_after' + btn_pos + '">' + settings.btn + buttonIcon.value + '</a>' : '<div class="cz_btn cz_btn_icon_after' + btn_pos + '">' + settings.btn + buttonIcon.value + '</div>';
		}

		var classes = 'services clr', 
			classes = settings.icon_fx ? classes + ' ' + settings.icon_fx : classes;
			classes = style ? classes + ' ' + style : classes;

		if ( settings.type === 'vertical' && settings.align ) {
			return2 = return2 + '<div class="clr"></div>';
		}

		var html = '',
			cover = cover_url ? '<img src="' + cover_url + '" />' : '';

		if ( settings.type === 'vertical' ) {
			classes = classes +  ' services_b';
			classes = classes + ' ' + settings.align;
			html = '<div class="' + classes + '">' + cover;
			html = html + return2 + '<div class="service_text">' + title + separator + '<div class="cz_wpe_content">' + content + '</div>' + btn + '</div></div>';
		}else {
			classes = settings.align ? classes + ' ' + settings.align : classes + ' ' + 'left';
			html = '<div class="' + classes + '">' + cover;
			html = html + return2 + '<div class="service_text">' + title + '<div class="cz_wpe_content">' + content + '</div>' + btn + '</div></div>';
		}

		if ( settings.fx  ) {
			#><div class="{{{ settings.fx }}}"><#
		}

		if ( settings.fx_hover ) {
			#><div class="{{{settings.fx_hover}}}"><#
		}

		var parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );

		#>

		{{{ parallaxOpen }}}

		<# if ( ! settings.link_only_btn ) {
			html = '<a href="' + settings.link.url + '">' + html + '</a>';
		 } #>

		{{{html}}}

		<# if ( settings.fx_hover ) {
			#></div><#
		}

		if ( settings.fx ) {
			#></div><#
		} #>

		{{{ parallaxClose }}}

		<?php
	}
}