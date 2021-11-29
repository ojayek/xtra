<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_team extends Widget_Base {

	protected $id = 'cz_team';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Team Member', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-team';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Member', 'codevz' ),
			esc_html__( 'Team', 'codevz' ),
			esc_html__( 'Group', 'codevz' ),
			esc_html__( 'About', 'codevz' ),
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

		$this->add_control(
			'style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_team_1',
				'options' => [
					'cz_team_1'  => esc_html__( 'No hover', 'codevz' ),
					'cz_team_2' => esc_html__( 'Social icons on image', 'codevz' ),
					'cz_team_3' => esc_html__( 'Social icons on image 2', 'codevz' ),
					'cz_team_4' => esc_html__( 'Social and title on image', 'codevz' ),
					'cz_team_5' => esc_html__( 'Social and title on image 2', 'codevz' ),
					'cz_team_6' => esc_html__( 'Only title on mouse moves', 'codevz' ),
					'cz_team_7' => esc_html__( 'Title on mouse moves and social below image', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'hover_mode',
			[
				'label' => esc_html__( 'Hover mode?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Selcet', 'codevz' ),
					'cz_team_rev_hover' => esc_html__( 'Reverse hover mode', 'codevz' ),
					'cz_team_always_show' => esc_html__( 'Always show details', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://xtratheme.com/img/450x450.jpg',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'full',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__('Name and job title','codevz'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '<h4><strong>John Carter</strong></h4><span style="color: #999999;">Developer</span>',
				'placeholder' => '<h4><strong>John Carter</strong></h4><span style="color: #999999;">Developer</span>',
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'title', 
			[
				'label' => esc_html__( 'Title', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'codevz' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://xtratheme.com'
			]
		);

		$this->add_control(
			'social',
			[
				'label' => esc_html__( 'Social', 'codevz' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls()
			]
		);
		
		$this->add_control(
			'color_mode',
			[
				'label' => esc_html__( 'Color mode?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Selcet', 'codevz' ),
					'cz_social_colored' => esc_html__( 'Original colors', 'codevz' ),
					'cz_social_colored_hover' => esc_html__( 'Original colors on hover', 'codevz' ),
					'cz_social_colored_bg'  => esc_html__( 'Original background', 'codevz' ),
					'cz_social_colored_bg_hover'  => esc_html__( 'Original background on hover', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'social_tooltip',
			[
				'label' => esc_html__( 'Tooltip?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Selcet', 'codevz' ),
					'cz_tooltip cz_tooltip_up' => esc_html__( 'Up', 'codevz' ),
					'cz_tooltip cz_tooltip_down' => esc_html__( 'Down', 'codevz' ),
					'cz_tooltip cz_tooltip_left'  => esc_html__( 'Left', 'codevz' ),
					'cz_tooltip cz_tooltip_right'  => esc_html__( 'Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'social_align',
			[
				'label' => esc_html__( 'Position?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  	=> esc_html__( '~ Default ~', 'codevz' ),
					'tal' 	=> esc_html__( 'Left', 'codevz' ),
					'tac'  	=> esc_html__( 'Center', 'codevz' ),
					'tar' 	=> esc_html__( 'Right', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'fx',
			[
				'label' => esc_html__( 'Hover effect?', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Select', 'codevz' ),
					'cz_social_fx_0' => esc_html__( 'ZoomIn', 'codevz' ),
					'cz_social_fx_1' => esc_html__( 'ZoomOut', 'codevz' ),
					'cz_social_fx_2'  => esc_html__( 'Bottom to Top', 'codevz' ),
					'cz_social_fx_3'  => esc_html__( 'Top to Bottom', 'codevz' ),
					'cz_social_fx_4'  => esc_html__( 'Left to Right', 'codevz' ),
					'cz_social_fx_5'  => esc_html__( 'Right to Left', 'codevz' ),
					'cz_social_fx_6'  => esc_html__( 'Rotate', 'codevz' ),
					'cz_social_fx_7'  => esc_html__( 'Infinite Shake', 'codevz' ),
					'cz_social_fx_8'  => esc_html__( 'Infinite Wink', 'codevz' ),
					'cz_social_fx_9'  => esc_html__( 'Quick Bob', 'codevz' ),
					'cz_social_fx_10'  => esc_html__( 'Flip Horizontal', 'codevz' ),
					'cz_social_fx_11'  => esc_html__( 'Flip Vertical', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'social_v',
			[
				'label' => esc_html__( 'Vertical mode?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cz_title',
			[
				'label' => esc_html__( 'Tilt effect on hover', 'codevz' )
			]
		);

		$this->add_control(
			'tilt',
			[
				'label' => esc_html__( 'Tilt effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Off', 'codevz' ),
					'on' => esc_html__( 'On', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'glare',
			[
				'label' => esc_html__( 'Glare', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'0' 	=> '0',
					'0.2' 	=> '0.2',
					'0.4' 	=> '0.4',
					'0.6' 	=> '0.6',
					'0.8' 	=> '0.8',
					'1' 	=> '1',
				],
				'condition' => [
					'tilt' => 'on'
				],
			]
		);

		$this->add_control(
			'scale',
			[
				'label' => esc_html__( 'Scale', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'0.9' 	=> '0.9',
					'0.8' 	=> '0.8',
					'1' 	=> '1',
					'1.1' 	=> '1.1',
					'1.2' 	=> '1.2',
				],
				'condition' => [
					'tilt' => 'on'
				],
			]
		);
		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section (
			'section_style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control (
			'sk_overall',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_team', '.cz_team:hover' ),
			]
		);

		$this->add_responsive_control (
			'svg_bg',
			[
				'label' 	=> esc_html__( 'Background layer', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'top', 'left', 'border', 'width', 'height' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_team .cz_svg_bg', '.cz_team:before .cz_svg_bg' ),
			]
		);

		$this->add_responsive_control (
			'sk_image_con',
			[
				'label' 	=> esc_html__( 'Image container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_team .cz_team_img' ),
			]
		);

		$this->add_responsive_control (
			'sk_image_img',
			[
				'label' 	=> esc_html__( 'Image', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_team .cz_team_img img', '.cz_team:hover .cz_team_img img' ),
			]
		);

		$this->add_responsive_control (
			'sk_content',
			[
				'label' 	=> esc_html__( 'Content', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'margin', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_team .cz_team_content' ),
			]
		);

		$this->add_responsive_control(
			'sk_social_con',
			[
				'label' 	=> esc_html__( 'Icons container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_team .cz_team_social_in' ),
			]
		);

		$this->add_responsive_control(
			'sk_icons',
			[
				'label' 	=> esc_html__( 'Icons', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_team .cz_team_social a', '.cz_team .cz_team_social a:hover' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$this->add_link_attributes( 'link', $settings['link'] );
		$a_attr = $this->get_render_attribute_string( 'link' );
		$img = Group_Control_Image_Size::get_attachment_image_html( $settings );
		$content = '<div class="cz_team_content cz_wpe_content">' . do_shortcode( $settings['content'] ) . '</div>';

		if ( $a_attr ) {
			$img = '<a ' . $a_attr . '>' . $img . '</a>';
			$content = '<a ' . $a_attr . '>' . $content . '</a>';
		}

		// Social
		$social = '<div class="' . implode( ' ', array_filter( array( 'cz_team_social cz_social clr', $settings['color_mode'], $settings['fx'], $settings['social_align'], $settings['social_tooltip'] ) ) ) . '">';
		$social .= '<div class="cz_team_social_in">';

		foreach ( $settings['social'] as $index => $i ) {

			if ( ! empty( $i['icon'][ 'value' ] ) ) {

				ob_start();
				Icons_Manager::render_icon( $i['icon'] );
				$icon = ob_get_clean();

				$class = isset( $i[ 'icon' ][ 'value' ] ) ? 'cz-' . str_replace( Codevz_Plus::$social_fa_upgrade, '', $i[ 'icon' ][ 'value' ] ) : '';

				$this->add_link_attributes( $class, $i['link'] );

				$social .= '<a ' . $this->get_render_attribute_string( $class ) . ' class="' . esc_attr( $class ) . '"' . ( empty( $i['title'] ) ? '' : ( $settings['social_tooltip'] ? ' data-title' : ' title' ) . '="' . esc_html( $i['title'] ) . '"' ) . '>' . wp_kses_post( $icon ) .'</a>';

			}

		}

		$social .= '</div></div>';

		// Classes
		$classes = array();
		$classes[] = 'cz_team mb30 clr';
		$classes[] = $settings['hover_mode'];
		$classes[] = $settings['svg_bg'] ? 'cz_svg_bg' : '';
		$classes[] = $settings['style'];
		$classes[] = $settings['social_v'] ? 'cz_social_v' : '';

		Xtra_Elementor::parallax( $settings );
		?>
		<div<?php echo Codevz_Plus::classes( [], $classes );  ?>>
			<?php if ( empty( $settings['style'] ) || $settings['style'] === 'cz_team_1' ) {
				echo '<div class="cz_team_img"' . Codevz_Plus::tilt( $settings ) . '>' . $img . '</div>' . $content . $social;
			} else if ( $settings['style'] === 'cz_team_2' || $settings['style'] === 'cz_team_4' ) {
				echo '<div class="cz_team_img"' . Codevz_Plus::tilt( $settings ) . '>' . $img . $social . '</div>' . $content;
			} else if ( $settings['style'] === 'cz_team_3' || $settings['style'] === 'cz_team_5' ) {
				echo '<div class="cz_team_img"' . Codevz_Plus::tilt( $settings ) . '>' . $img . $content . $social . '</div>';
			} else if ( $settings['style'] === 'cz_team_6' ) {
				echo '<div class="cz_team_img"' . Codevz_Plus::tilt( $settings ) . '>' . $img . $content . '</div>';
			} else if ( $settings['style'] === 'cz_team_7' ) {
				echo '<div class="cz_team_img"' . Codevz_Plus::tilt( $settings ) . '>' . $img . $content . '</div>' . $social;
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

		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' ),
			img = '<img src="' + image_url + '" />',
			content = '<div class="cz_team_content cz_wpe_content">' + settings.content + '</div>';

		if ( settings.link.url ) {
			img = '<a href="' + settings.link.url + '">' + img + '</a>';
			content = '<a href="' + settings.link.url + '">' + content + '</a>';
		}

		var social = '<div class="cz_team_social cz_social clr ' + settings.color_mode + ' ' + settings.fx + ' ' + settings.social_align + ' ' + settings.social_tooltip + '">',
			social = social + '<div class="cz_team_social_in">';

		_.each( settings.social, function( i, index ) {

			if ( i.icon.value ) {

				var iconHTML = elementor.helpers.renderIcon( view, i.icon, { 'aria-hidden': true }, 'i' , 'object' ),
					classname = 'cz-' + ( i.icon.value.replace( /fa-|far-|fas-|fab-|fa |fas |far |fab |czico-|-square|-official|-circle'/g, '' ) );

				social = social + ( iconHTML.value ? '<a href="' + i.link.url  + '" class="' + classname + '"' + ( i.title ? ( settings.social_tooltip ? ' data-title="' : ' title="' ) + i.title : '' ) + '">' + iconHTML.value + '</a>' : '' );

			}

		});
		social = social + '</div></div>';

		var classes = 'cz_team mb30 clr', 
			classes = settings.hover_mode 	? classes + ' ' + settings.hover_mode : classes,
			classes = settings.style 		? classes + ' ' + settings.style : classes,
			classes = settings.svg_bg 		? classes + ' cz_svg_bg' : classes,
			classes = settings.social_v 	? classes + ' cz_social_v' : classes,

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );
			tilt = xtraElementorTilt( settings );
		#>

		{{{ parallaxOpen }}}
		
		<div  class="{{{classes}}}">
		<# if ( settings.style  || settings.style  === 'cz_team_1' ) { #>
			 <div class="cz_team_img" {{{ tilt }}}><img src="{{ image_url }}"></div>{{{content}}}{{{social}}} 
		<# } else if ( settings.style  === 'cz_team_2' || settings.style  === 'cz_team_4' ) { #>
			 <div class="cz_team_img" {{{ tilt }}}><img src="{{ image_url }}">{{{social}}}</div>content;
		<# } else if ( settings.style  === 'cz_team_3' || settings.style  === 'cz_team_5' ) { #>
			 <div class="cz_team_img" {{{ tilt }}}><img src="{{ image_url }}">{{{content}}}{{{social}}}</div>
		<# } else if ( settings.style  === 'cz_team_6' ) { #>
			<div class="cz_team_img" {{{ tilt }}}><img src="{{ image_url }}">{{{content}}}</div>
		<# } else if ( settings.style  === 'cz_team_7' ) { #>
			<div class="cz_team_img" {{{ tilt }}}><img src="{{ image_url }}">{{{content}}}</div>{{{social}}};
		<# } #>
		</div>
		
		{{{ parallaxClose }}}
		<?php
	}


}